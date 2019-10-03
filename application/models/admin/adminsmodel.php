<?php

class adminsmodel extends CI_Model {

    public function __construct() {

        parent::__construct();
        $this->load->database();
    }

    public function updateadmin($fname, $lname, $email, $profilepic, $username) {
        $admin_uid = $this->session->userdata('admin_uid');
        if (!empty($profilepic)) {
            $profilepic = json_decode($profilepic);

            $this->load->library('upload');

            $filename = $profilepic->profile_pic->name;

            $imarr = explode(".", $filename);

            $ext = end($imarr);

            $_FILES['profile_pic']['name'] = $profilepic->profile_pic->name;
            $_FILES['profile_pic']['type'] = $profilepic->profile_pic->type;
            $_FILES['profile_pic']['tmp_name'] = $profilepic->profile_pic->tmp_name;
            $_FILES['profile_pic']['error'] = $profilepic->profile_pic->error;
            $_FILES['profile_pic']['size'] = $profilepic->profile_pic->size;

            $config = array(
                'file_name' => str_replace(".", "", microtime(true)) . "." . $ext,
                'allowed_types' => 'gif|png|jpg|jpeg',
                'upload_path' => APPPATH . '../assets/uploads',
                'max_size' => 2000
            );

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('profile_pic')) {
                $errormsg = $this->upload->display_errors();
                $arr = array('error' => 1, 'success' => '', 'errormsg' => strip_tags($errormsg));
                return json_encode($arr);
            } else {
                $image_data = $this->upload->data();
                $upName = $image_data['file_name'];
                $viewdet['status'] = 1;

                $config = array(
                    'source_image' => $image_data['full_path'],
                    'new_image' => APPPATH . '../assets/uploads/thumbs',
                    'maintain_ration' => true,
                    'width' => 200,
                    'height' => 150
                );

                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                $imagename = $image_data['file_name'];

                $sql = "update " . tablename('admins') . " set image='" . $imagename . "' where id='" . $admin_uid . "'";
                $query = $this->db->query($sql);

                $adminimage = get_from_session('image');

                @unlink(APPPATH . '../assets/uploads/' . $adminimage);
                @unlink(APPPATH . '../assets/uploads/thumbs/' . $adminimage);
            }
        }

        $sql = "update " . tablename('admins') . " set fname='" . $fname . "',lname='" . $lname . "',username='" . $username . "',email='" . $email . "' where id='" . $admin_uid . "'";
        $qq = $this->db->query($sql);

        $selsql = "select * from " . tablename('admins') . " where id='" . $admin_uid . "'";
        $query = $this->db->query($selsql);
        $admindet = $query->row();
        $admindet = json_encode((array) $admindet);
        $arr = array('error' => '', 'success' => 1, 'errormsg' => '');
        $this->session->set_userdata('admin_detail', $admindet);
        return json_encode($arr);
    }

    public function userlogs() {
        $usersid = $this->session->userdata('admin_uid');
        $selsql = "select * from " . tablename('logs') . " where user_id='" . $usersid . "' order by id desc limit 0, 5";
        $query = $this->db->query($selsql);
        $userlog = $query->result();
        
        if (!empty($userlog)) {
            return $userlog;
        } else {
            return "";
        }
    }

    /* Dashboard functions */

    public function todayssale() {
        $selsql = "select * from " . tablename('orders') . " where DATE(order_date)=DATE(now())";
        $query = $this->db->query($selsql);
        $numorders = $query->num_rows();
        if (!empty($numorders)) {
            return $numorders;
        } else {
            return 0;
        }
    }

    public function todaysrevenue() {
        $selsql = "select SUM(grand_total) as todaysrevenue from " . tablename('orders') . " where DATE(order_date)=DATE(now())";
        $query = $this->db->query($selsql);
        $sumorders = $query->row();
        if (!empty($sumorders)) {
            return $sumorders;
        } else {
            return 0;
        }
    }

    public function todaysvisitors() {
        $selsql = "select * from " . tablename('site_visitors') . " where DATE(visiting_date)=DATE(now()) and visitor_type='1'";
        $query = $this->db->query($selsql);
        $numvisitors = $query->num_rows();
        if (!empty($numvisitors)) {
            return $numvisitors;
        } else {
            return 0;
        }
    }

    public function todayspagevisits() {
        $selsql = "select * from " . tablename('site_visitors') . " where DATE(visiting_date)=DATE(now()) and visitor_type='0'";
        $query = $this->db->query($selsql);
        $numvisits = $query->num_rows();
        if (!empty($numvisits)) {
            return $numvisits;
        } else {
            return 0;
        }
    }

    public function todaysordersummary() {
        $selsql = "select SUM(total) as gross, SUM(service_tax) as service_tax, SUM(vat) as vat, SUM(grand_total) as grand_total from " . tablename('orders') . " where DATE(order_date)=DATE(now())";
        $query = $this->db->query($selsql);
        $res = $query->row();

        $numsql = "select * from " . tablename('orders') . " where DATE(order_date)=DATE(now())";
        $numquery = $this->db->query($numsql);
        $numrows = $numquery->num_rows();

        if (!empty($res)) {
            $res->order_count = $numrows;
        }

        return $res;

        /* $numorders=$query->num_rows();
          if(!empty($numorders))
          {
          return $numorders;
          }
          else
          {
          return 0;
          } */
    }

    public function yesterdaysorder() {
        $yesterday = date('Y-m-d', strtotime("-1 days"));

        $selsql = "select SUM(total) as gross, SUM(service_tax) as service_tax, SUM(vat) as vat, SUM(grand_total) as grand_total from " . tablename('orders') . " where DATE(order_date) = CURDATE() - 1";
        $query = $this->db->query($selsql);
        $res = $query->row();

        $numsql = "select * from " . tablename('orders') . " where DATE(order_date) = CURDATE() - 1";
        $numquery = $this->db->query($numsql);
        $numrows = $numquery->num_rows();

        if (!empty($res)) {
            $res->order_count = $numrows;
        }

        return $res;
    }

    public function lastweekorder() {
        $day = date('w');
        $week_start = date("Y-m-d", strtotime('monday last week'));
        $week_end = date("Y-m-d", strtotime('sunday last week'));
        //echo $week_start."<-->".$week_end;exit;

        $selsql = "select SUM(total) as gross, SUM(service_tax) as service_tax, SUM(vat) as vat, SUM(grand_total) as grand_total from " . tablename('orders') . " WHERE DATE(order_date) >='" . $week_start . "' AND DATE(order_date) <='" . $week_end . "'";
        $query = $this->db->query($selsql);
        $res = $query->row();

        $numsql = "select * from " . tablename('orders') . " WHERE DATE(order_date) >='" . $week_start . "' AND DATE(order_date) <='" . $week_end . "'";

        $numquery = $this->db->query($numsql);
        $numrows = $numquery->num_rows();

        if (!empty($res)) {
            $res->order_count = $numrows;
        }

        return $res;
    }

    public function lastmonthorder() {

        $selsql = "select SUM(total) as gross, SUM(service_tax) as service_tax, SUM(vat) as vat, SUM(grand_total) as grand_total from " . tablename('orders') . " where YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
AND MONTH(order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
        $query = $this->db->query($selsql);
        $res = $query->row();

        $numsql = "select * from " . tablename('orders') . " where YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
AND MONTH(order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
        $numquery = $this->db->query($numsql);
        $numrows = $numquery->num_rows();

        if (!empty($res)) {
            $res->order_count = $numrows;
        }

        return $res;
    }

    public function lastyearorder() {
        $selsql = "select SUM(total) as gross, SUM(service_tax) as service_tax, SUM(vat) as vat, SUM(grand_total) as grand_total from " . tablename('orders') . " where YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)";
        $query = $this->db->query($selsql);
        $res = $query->row();

        $numsql = "select * from " . tablename('orders') . " where YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)";
        $numquery = $this->db->query($numsql);
        $numrows = $numquery->num_rows();

        if (!empty($res)) {
            $res->order_count = $numrows;
        }

        return $res;
    }

    public function todaysvisitor() {
        $res = array();

        $numsql = "select * from " . tablename('site_visitors') . " where DATE(visiting_date)=DATE(now()) and visitor_type='1'";
        $numquery = $this->db->query($numsql);
        $numrows = $numquery->num_rows();


        $res['visitor_count'] = $numrows;


        return $res;
    }

    public function yesterdaysvisitor() {
        $yesterday = date('Y-m-d', strtotime("-1 days"));

        $res = array();
        //SELECT DATE(added) as yesterday, COUNT(*) FROM bookings WHERE DATE(added) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) GROUP BY yesterday
        //$numsql="select * from ".tablename('site_visitors')." where DATE(visiting_date) = CURDATE() - 1 and visitor_type='1'";
        $numsql = "select * from " . tablename('site_visitors') . " where DATE(visiting_date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) and visitor_type='1'";
        $numquery = $this->db->query($numsql);
        $numrows = $numquery->num_rows();


        $res['visitor_count'] = $numrows;


        return $res;
    }

    public function lastweekvisitor() {
        $day = date('w');
        $week_start = date("Y-m-d", strtotime('monday last week'));
        $week_end = date("Y-m-d", strtotime('sunday last week'));

        $res = array();

        //$numsql="select * from ".tablename('site_visitors')." WHERE DATE(visiting_date) >='".$week_start."' AND DATE(visiting_date) <='".$week_end."' and visitor_type='1'";
        $numsql = "select * from " . tablename('site_visitors') . " WHERE DATE(visiting_date) BETWEEN DATE_SUB(CURDATE()-7, INTERVAL 1 WEEK) AND CURDATE()-7 and visitor_type='1'";

        $numquery = $this->db->query($numsql);
        $numrows = $numquery->num_rows();


        $res['visitor_count'] = $numrows;


        return $res;
    }

    public function lastmonthvisitor() {

        $res = array();

        $numsql = "select * from " . tablename('site_visitors') . " where YEAR(visiting_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
AND MONTH(visiting_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) and visitor_type='1'";
        $numquery = $this->db->query($numsql);
        $numrows = $numquery->num_rows();


        $res['visitor_count'] = $numrows;


        return $res;
    }

    public function lastyearvisitor() {
        $res = array();

        $numsql = "select * from " . tablename('site_visitors') . " where YEAR(visiting_date) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR) and visitor_type='1'";
        $numquery = $this->db->query($numsql);
        $numrows = $numquery->num_rows();


        $res['visitor_count'] = $numrows;


        return $res;
    }

    public function getCurrentUserProfile()
    {
        $email = get_from_session('email');
        $this->db->where('email', $email);
        return $this->db->get('admins')->row();
    }

    /*
     * for update the user profile
     */
    public function updateUserProfile($user, $user_id) {
        // echo $user_id;exit();
        $this->db2 = $this->load->database('db2', TRUE);
        $this->db2->where('id', $user_id);
        $this->db2->update('user', $user);
        
        $this->session->unset_userdata('fname');
        $this->session->set_userdata('fname', $user['fname']);
        // $this->session->unset_userdata('admin_detail');
        // $this->session->set_userdata('admin_detail', json_encode($user));
        
        /* ================ */
        
        $update_query = "UPDATE `pb_admins` SET `fname` = '" . $user['fname'] . "', lname = '" . $user['lname'] . "', phone = '" . $user['phone'] . "'";
//        if(!empty($user['phone'])) {
//            $update_query .= ", `phone` = '".$user['phone']."'";
//        }
        if(!empty($user['image'])) {
            $update_query .= ", `image` = '".$user['image']."'";
            $this->session->unset_userdata('user_image');
            $this->session->set_userdata('user_image', $user['image']);
        }
        $update_query .= ", modified_date = '" . date("Y-m-d H:i:s", time()) . "' WHERE `pb_admins`.`sass_user_id` = " . $user_id;
               
//        $update_query = $this->input->post('db_query', TRUE);
//        $update_query = rtrim($update_query, ';');
        $qry_arr = explode(';', $update_query);
        $all_company = $this->getAllCompanyDetails();
        foreach ($all_company as $company) {
            if ($company->comp_db_name) {
                //update database
                $dbhost = DB_HOSTNAME;
                $dbuser = DB_USERNAME;
                $dbpass = DB_PASSWORD;
                $dbname = SUB_DB_PREFIX . $company->comp_db_name;
                $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
                if (!$conn) {
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = 'Database connection faild:' . mysqli_connect_error();
                }else {
//                    foreach ($qry_arr as $sql) {
//                        $sql = trim($sql);
//                        $res = mysqli_query($conn, $sql);
//                    }
                    $res = mysqli_query($conn, $update_query);
                    if ($res) {
                        $res_date = $this->updateDBchangesDate($company->id);
                    } else {
                        $data_msg['res'] = 'error';
                        $data_msg['message'] = "Error occured to update the database: " . mysqli_error($conn);
                    }
                    mysqli_close($conn);
                }
                
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = 'Error occured please try again.';
            }
        }
        if ($res) {
            $data_msg['res'] = 'success';
            $data_msg['message'] = "All Database updated successfully";
        }
        
        
        /* ================ */
    }

    public function getAllCompanyDetails() {
        $this->db2 = $this->load->database('db2', TRUE);
        $this->db2->select('id,comp_db_name');
        $this->db2->from('company_details');
        $query = $this->db2->get();
        $result = $query->result();
        return $result;
    }

    public function updateDBchangesDate($company_id) { 
        $this->db2 = $this->load->database('db2', TRUE);
        $date = date("Y-m-d H:i:s");
        $data = array('db_update_date' => $date);
        $this->db2->where('id', $company_id);
        $this->db2->update('company_details', $data);
        return $date;
    }
}
