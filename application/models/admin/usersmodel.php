<?php

class usersmodel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function loadusers() {
        $sql = "select * from ".tablename('users')."";
        $query = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            return $result;
        } else {
            return "";
        }
    }

    public function loadusersactive() {
        $sql = "select * from ".tablename('users')." where status='1'";
        $query = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            return $result;
        } else {
            return "";
        }
    }
public function loadusersinactive()
	{
		$sql="select * from ".tablename('users')." where status='0'";
		$query=$this->db->query($sql);
		$result=$query->result();
		if(!empty($result))
		{
			return $result;
		}
		else
		{
			return "";
		}
	}
    public function inactiveusercount()
	{
		$sql="select * from ".tablename('users')." where status='0'";
		$query=$this->db->query($sql);
		
		
			 return $totRow=$query->num_rows();
		
	}
        public function activeusercount()
	{
		$sql="select * from ".tablename('users')." where status='1'";
		$query=$this->db->query($sql);
		
		
			 return $totRow=$query->num_rows();
		
	}
    public function loadusersadminsactive() {
        $sql = "select * from ".tablename('users')." where status='1'";
        $query = $this->db->query($sql);
        $resultuser = $query->result();

        $sql = "select * from ".tablename('admins')." where status='1'";
        $query = $this->db->query($sql);
        $resultadmin = $query->result();
        $result = array();
        if (!empty($resultuser)) {
            $result['user'] = $resultuser;
        }
        if (!empty($resultadmin)) {
            $result['admin'] = $resultadmin;
        }
        if (!empty($result)) {
            return $result;
        } else {
            return "";
        }
    }

    public function loaduserssingle($id) {
        $sql = "select * from ".tablename('users')." where id='" . $id . "'";
        $query = $this->db->query($sql);
        $row = $query->row();
        if (!empty($row)) {
            return $row;
        } else {
            return "";
        }
    }

    public function get($id) {
        $where = array(
            tablename('users').'.id' => $id
        );


        $this->db->where($where);

        $this->db->join(tablename('membership'), tablename('users').'.id='.tablename('user_membership').'.user_id', 'INNER');
        $this->db->join(tablename('membership_plan'), tablename('membership_plan').'.id='.tablename('user_membership').'.membership_id', 'INNER');

        $list = $this->db->get(tablename('users'))->result();
        //$list = $this->db->where(array('id'=>$id))->get('".tablename('users')."')->result();
        return !empty($list) ? $list[0] : array();
    }

    public function changeusers($id, $image_name) {
        $fname = $this->input->post('fname');
        $lname = $this->input->post('lname');
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $profile_image = $image_name;
        //echo $profile_image;exit;
        if (empty($id)) {
            $sql = "insert into ".tablename('users')." set fname='" . addslashes($fname) . "',lname='" . addslashes($lname) . "',username='" . addslashes($username) . "',email='" . addslashes($email) . "',address='" . addslashes($address) . "',phone='" . addslashes($phone) . "',creation_date='" . date('Y-m-d H:i:s') . "',modified_date='" . date('Y-m-d H:i:s') . "'";
        } else {
            $sql = "update ".tablename('users')." set fname='" . addslashes($fname) . "',lname='" . addslashes($lname) . "',username='" . addslashes($username) . "',email='" . addslashes($email) . "',image='" . $profile_image . "',address='" . addslashes($address) . "',phone='" . addslashes($phone) . "',modified_date='" . date('Y-m-d H:i:s') . "' where id='" . $id . "'";
        }
        $query = $this->db->query($sql);
        if (!empty($query)) {

            if (!empty($id)) {

                $this->load->library('parser');

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: <' . get_from_session('email') . '>' . "\r\n";

                $message = "Your profile has been changed by TCR Member Admin";

                $tdata['date'] = date('l F d, Y');
                $tdata['year'] = date("Y");
                $tdata['siteurl'] = $this->config->item('base_url');
                $tdata['logo'] = $tdata['siteurl'] . "assets/images/logo.png";
                $tdata['heading'] = "TCR Member User Profile Edited";
                $tdata['message'] = $message;

                $msg = $this->parser->parse('mail/mail-template', $tdata, TRUE);

                $mm = @mail($email, "TCR Member User Profile Edited", $msg, $headers);
            }

            return 1;
        } else {
            return "";
        }
    }

    public function deleteusers($id) {


        $sqldata = "select status,email from ".tablename('users')." where id='" . $id . "'";
        $query = $this->db->query($sqldata);
        $r = $query->row();
        $status = $r->status;
        $email = $r->email;

        $sql = "delete from ".tablename('users')." where id='" . $id . "'";
        $query = $this->db->query($sql);
        if (!empty($query)) {


            $this->load->library('parser');

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <' . get_from_session('email') . '>' . "\r\n";

            $message = "Your profile has been deleted by TCR Member Admin";

            $tdata['date'] = date('l F d, Y');
            $tdata['year'] = date("Y");
            $tdata['siteurl'] = $this->config->item('base_url');
            $tdata['logo'] = $tdata['siteurl'] . "assets/images/logo.png";
            $tdata['heading'] = "TCR Member User Profile Delete";
            $tdata['message'] = $message;

            $msg = $this->parser->parse('mail/mail-template', $tdata, TRUE);

            $mm = @mail($email, "TCR Member User Profile Delete", $msg, $headers);


            return 1;
        } else {
            return "";
        }
    }

    public function statususers($id) {
        $sql = "select status,email from ".tablename('users')." where id='" . $id . "'";
        $query = $this->db->query($sql);
        $r = $query->row();
        $status = $r->status;
        $email = $r->email;
        $message = "";
        if ($status == 1) {
            $ssql = "update ".tablename('users')." set status='0' where id='" . $id . "'";

            $message = "Your profile has been deactivated by TCR Member Admin";
        }
        if ($status == 0) {
            $ssql = "update ".tablename('users')." set status='1' where id='" . $id . "'";
            $message = "Your profile has been activated by TCR Member Admin";
        }
        $qquery = $this->db->query($ssql);

        if (!empty($qquery)) {
            $this->load->library('parser');

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <' . get_from_session('email') . '>' . "\r\n";



            $tdata['date'] = date('l F d, Y');
            $tdata['year'] = date("Y");
            $tdata['siteurl'] = $this->config->item('base_url');
            $tdata['logo'] = $tdata['siteurl'] . "assets/images/logo.png";
            $tdata['heading'] = "Status Change for TCR Member Users";
            $tdata['message'] = $message;

            $msg = $this->parser->parse('mail/mail-template', $tdata, TRUE);

            $mm = @mail($email, "Status Change for TCR Member Users", $msg, $headers);


            return 1;
        } else {
            return "";
        }
    }
    
    
    
    public function getUserProduct($id) {
       
                
        $sql = "select pr.* from ".tablename('user_product')." p,".tablename('users')." u,".tablename('product')." pr where u.id=p.uid and p.pid=pr.id and p.uid='" . $id . "'";
        $query = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            return $result;
        } else {
            return "";
        }
    }

}
