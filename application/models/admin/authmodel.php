<?php

class authmodel extends CI_Model {

    public function __construct() {

        parent::__construct();
        $this->load->database();
    }

    public function getBranch($id) {
        $this->db->select("id,company_type");
        $this->db->from('account_settings');
        $this->db->where_in('company_id', $id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getInventoryStatus() {
        $this->db->select("is_inventory");
        $this->db->from('account_configuration');
        $query = $this->db->get();
        return $query->row();
    }

    public function getUser($user_id, $password) {
        $sql = "select * from " . tablename('admins') . " WHERE sass_user_id='" . trim($user_id) . "' AND password='" . trim($password) . "' AND status='1'";
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function checklogin($email, $password) {
        $sql = "select * from " . tablename('admins') . " where email='" . addslashes(trim($email)) . "' and password='" . sha1($password) . "'";

        $query = $this->db->query($sql);
        $adminrow = $query->row();

        if (!empty($adminrow)) {
            $adminid = $adminrow->id;
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            $ipaddress = $_SERVER['REMOTE_ADDR'];
            $online = 1;
            $modified_date = date('Y-m-d H:i:s');
            // update admins table
            $updsql = "update " . tablename('admins') . " set ip_address='" . addslashes($ipaddress) . "',user_agent='" . addslashes($useragent) . "',online_status='" . $online . "',modified_date='" . $modified_date . "' where id='" . $adminid . "'";
            $upquery = $this->db->query($updsql);

            if (!empty($upquery)) {
                $sql = "select * from " . tablename('admins') . " where email='" . addslashes(trim($email)) . "' and password='" . sha1($password) . "'";
                $query = $this->db->query($sql);
                $adminrow = $query->row();

                $adminid = $adminrow->id;
                $login_datetime = date('Y-m-d H:i:s');
                $logdata = array('user_id' => $adminid, 'ip_address' => $ipaddress, 'user_agent' => $useragent, 'login_datetime' => $login_datetime);
                $this->db->insert(tablename('logs'), $logdata);
                $logid = $this->db->insert_id();


                $userarr = (array) $adminrow;
                $js_userarray = json_encode($userarr);
                $this->session->set_userdata('admin_uid', $adminid);
                $this->session->set_userdata('admin_detail', $js_userarray);
                $this->session->set_userdata('admin_logid', $logid);

                if (!empty($checkon)) {
                    $expire = 3660 * 24 * 30;
                    set_cookie('admin_uid', $adminid, $expire);
                    set_cookie('admin_detail', $js_userarray, $expire);
                }
                return 1;
            }
            return "";
        }
        return "";
    }

    public function CompanyLogin($adminrow, $company_id) {
        if (!empty($adminrow)) {
            $adminid = $adminrow->id;
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            $ipaddress = $_SERVER['REMOTE_ADDR'];
            $online = 1;
            $modified_date = date('Y-m-d H:i:s');
            // update admins table
            $updsql = "update " . tablename('admins') . " set ip_address='" . addslashes($ipaddress) . "',user_agent='" . addslashes($useragent) . "',online_status='" . $online . "',modified_date='" . $modified_date . "' where id='" . $adminid . "'";
            $upquery = $this->db->query($updsql);

            if (!empty($upquery)) {
                $sql = "select * from " . tablename('admins') . " where email='" . addslashes(trim($adminrow->email)) . "' and password='" . $adminrow->password . "'";
                $query = $this->db->query($sql);
                $adminrow = $query->row();

                $adminid = $adminrow->id;
                $login_datetime = date('Y-m-d H:i:s');
                $logdata = array('user_id' => $adminid, 'ip_address' => $ipaddress, 'user_agent' => $useragent, 'login_datetime' => $login_datetime);
                $this->db->insert(tablename('logs'), $logdata);
                $logid = $this->db->insert_id();


                $userarr = (array) $adminrow;
                $js_userarray = json_encode($userarr);
                $this->session->set_userdata('admin_uid', $adminid);
                $this->session->set_userdata('admin_detail', $js_userarray);
                $this->session->set_userdata('admin_logid', $logid);
                $this->session->set_userdata('company_id', $company_id);

                if (!empty($checkon)) {
                    $expire = 3660 * 24 * 30;
                    set_cookie('admin_uid', $adminid, $expire);
                    set_cookie('admin_detail', $js_userarray, $expire);
                }
                return 1;
            }
            return "";
        }
        return "";
    }

    public function checklogout($uid) {
        $modified_date = date('Y-m-d H:i:s');
        $sql = "update " . tablename('admins') . " set ip_address='',user_agent='',online_status='0',last_login='" . $modified_date . "',modified_date='" . $modified_date . "' where id='" . $uid . "'";
        $q = $this->db->query($sql);
        $sql1 = "update " . tablename('logs') . " set logout_datetime='" . $modified_date . "' where id='" . $this->session->userdata('admin_logid') . "'";
        $this->db->query($sql1);
        if (!empty($q)) {
            $this->session->set_userdata('admin_uid', '');
            $this->session->set_userdata('admin_detail', '');
            $this->session->set_userdata('admin_logid', '');
            delete_cookie('admin_uid');
            delete_cookie('admin_detail');
            return 1;
        } else {
            return "";
        }
    }

    public function emailcheck($email) {
        $sql = "select id from " . tablename('admins') . " where email='" . addslashes(trim($email)) . "'";
        $query = $this->db->query($sql);
        $row = $query->row();
        if (!empty($row)) {
            $this->load->library('parser');
            $adminid = $row->id;
            $emailarr = explode("@", $email);
            $emuname = sha1($emailarr[0]);
            $activationlink = $this->config->item('base_url') . "admin/new-password.html/" . $emuname;
            $usql = "update " . tablename('admins') . " set hash='" . $emuname . "' where id='" . $adminid . "'";
            $q = $this->db->query($usql);
            if (!empty($q)) {
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: <' . get_admin_email() . '>' . "\r\n";

                $message = "Please click the  link to reset your password.<a href='" . $activationlink . "'> Click Here</a>";

                $tdata['date'] = date('l F d, Y');
                $tdata['year'] = date("Y");
                $tdata['siteurl'] = $this->config->item('base_url');
                $tdata['logo'] = $tdata['siteurl'] . "assets/images/logo.png";
                $tdata['heading'] = "Recover Your Password";
                $tdata['message'] = $message;

                $msg = $this->parser->parse('mail/mail-template', $tdata, TRUE);

                $mm = mail($email, "Recover Password for TCR Member", $msg, $headers);


                //$mm=mail($email,"Recover Password for TCR Member",$activationlink,$headers);
                //sendmail for resetpass
                return 1;
            }
        } else {
            return "";
        }
    }

    public function passwdupd($password, $activationcode) {
        $sql = "update " . tablename('admins') . " set password='" . sha1($password) . "',hash='' where hash='" . $activationcode . "'";
        $q = $this->db->query($sql);
        if (!empty($q)) {
            return 1;
        } else {
            return "";
        }
    }

    public function checkAdmin($adminid) {

        $this->db->from(tablename('admins'));
        $this->db->where('id', $adminid);

        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function changePwd($password, $adminid) {
        // $sql = "update " . tablename('admins') . " set password='" . sha1($password) . "' where id='" . $adminid . "'";
        // $q = $this->db->query($sql);
        $this->db->where('id', $adminid);
        $q = $this->db->update('admins', array('password' => md5($password)));
        if (!empty($q)) {
            return 1;
        } else {
            return "";
        }
    }

    public function pwdupdate_dbs($data) {

        $accdbname = SAAS_DB_NAME;
        $mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
        if (mysqli_select_db($mysqli, $accdbname)) {
            //echo 'DB selected..+++.';
            $pwd = md5($data['newpass']);
            $sql = "update `" . $accdbname . "`.`saas_user` set password = '" . $pwd . "' where emailid = '" . $data['email'] . "'";
            $result = $mysqli->query($sql);

            $sql = "select * from `" . $accdbname . "`.`saas_company_details` where email = '" . $data['email'] . "'";
            $result = $mysqli->query($sql);
            $row = $result->fetch_array(MYSQLI_ASSOC);

            $sql = "select co.comp_db_name from ".SAAS_DB_NAME.".`saas_user_relation` ur, ".SAAS_DB_NAME.".`saas_company_details` co WHERE ur.user_id = '".$row['user_id']."' AND ur.status = '1' AND co.id = ur.company_id";
            $result = $mysqli->query($sql);


            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $accdbname = SUB_DB_PREFIX . $row['comp_db_name'];
                mysqli_select_db($mysqli, $accdbname);

                $sql = "update `" . $accdbname . "`.`pb_admins` set password = MD5('" . $data['newpass'] . "') where email = '" . $data['email'] . "' ";
                $mysqli->query($sql);
            }
        } else {
            echo 'DB not selected.......***...';
        }
        //exit;
        /*





          $str = '';
          $accdbname = 'PRO_ACTERP_DB_SAAS';

          $con = mysqli_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,$accdbname);
          if(mysqli_select_db ($con,$accdbname)){

          $pwd = md5($data['newpass']);
          $sql = "update `".$accdbname."`.`saas_user` set password = '".$pwd."' where emailid = '".$data['email']."'";
          mysqli_query($con, $sql);
          $str .= $sql.'<br>';
          //echo $sql;exit;


          $sql = "select * from `".$accdbname."`.`saas_company_details` where email = '".$data['email']."'";
          $res = mysqli_query($con, $sql);
          $str .= $sql.'<br>';


          while($result = mysqli_fetch_array($res)){
          $accdbname = 'PRO_ACTERP_DB_CUST_'.$result['comp_db_name'];
          @mysql_select_db ($con,$accdbname);

          $sql = "update `".$accdbname."`.`pb_admins` set password = SHA1('".$data['newpass']."') where email = '".$data['email']."' ";
          mysqli_query($con,$sql);
          $str .= $sql.'<br>';
          }


          }else{
          //echo 'DB not selected';exit;
          }


         */


        //echo $str;exit;    
        //echo 'Here 02';exit;
    }

    public function userBranchAccess($user_id, $branch_id, $company_id) {
        $this->db->select("module");
        $this->db->from('user_branch_access');
        $this->db->where('company_id', $company_id);
        $this->db->where('branch_id', $branch_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 1);
        $query = $this->db->get();
        return $query->row();
    }

}
