<?php
class authmodel extends CI_Model
{
    public function __construct()
    {

            parent::__construct();
            $this->load->database();
    }
    
    //Checkout login function for front end
    public function checkoutlogin($email,$password)
    {
        $sql="select id,fname,lname,phone,email,image,password from ".tablename('users')." where email='".addslashes(trim($email))."' and password='". sha1($password)."' and is_guest='0'";

        $query=$this->db->query($sql);
        $row=$query->row();

        if(!empty($row))
        {
            $frontuserid=$row->id;
            $useragent=$_SERVER['HTTP_USER_AGENT'];
            $ipaddress=$_SERVER['REMOTE_ADDR'];
            $online=1;
            $modified_date=date('Y-m-d H:i:s');
            
            $js_userarray=json_encode($row);
            $this->session->set_userdata('front_uid',$frontuserid);
            $this->session->set_userdata('front_detail',$js_userarray);
            return 1;
            
            /*$updsql="update ".tablename('admins')." set ip_address='".addslashes($ipaddress)."',user_agent='".addslashes($useragent)."',online_status='".$online."',modified_date='".$modified_date."' where id='".$adminid."'";
            $upquery=$this->db->query($updsql);

            if(!empty($upquery))
            {
                $sql="select * from ".tablename('admins')." where email='".addslashes(trim($email))."' and password='". sha1($password)."'";
                $query=$this->db->query($sql);
                $adminrow=$query->row();

                $adminid=$adminrow->id;
                $login_datetime=date('Y-m-d H:i:s');
                $logdata=array('user_id'=>$adminid,'ip_address'=>$ipaddress,'user_agent'=>$useragent,'login_datetime'=>$login_datetime);
                $this->db->insert(tablename('logs'), $logdata);
                $logid=$this->db->insert_id();


                $userarr=(array)$adminrow;
                $js_userarray=json_encode($userarr);
                $this->session->set_userdata('admin_uid',$adminid);
                $this->session->set_userdata('admin_detail',$js_userarray);
                $this->session->set_userdata('admin_logid', $logid);

                if(!empty($checkon))
                {
                    $expire=3660*24*30;
                    set_cookie('admin_uid', $adminid, $expire);
                    set_cookie('admin_detail', $js_userarray, $expire);
                }
                return 1;
            }
            return "";*/
        }
        return "";
    }
    
    //Fetch loged user data
    public function fetchlogeduserdata()
    {
        if(!empty($this->session->userdata('front_uid')))
        {
            $sql="select * from ".tablename('users')." where id='".$this->session->userdata('front_uid')."'";
            $query=$this->db->query($sql);
            $row=$query->row();
            
            return $row; 
        }
        else 
        {
            return array();
        }
    }
    
    //Fetch loged user shipping address
    public function fetchlogedshippingaddress()
    {
        if(!empty($this->session->userdata('front_uid')))
        {
            $sql="select * from ".tablename('shipping_address')." where users_id='".$this->session->userdata('front_uid')."' order by creation_date desc limit 1";
            $query=$this->db->query($sql);
            $row=$query->row();
            
            return $row; 
        }
        else 
        {
            return array();
        }
    }
    
    //Fetch Lodeg user billing address
    public function fetchlogedbillingaddress()
    {
        if(!empty($this->session->userdata('front_uid')))
        {
            $sql="select * from ".tablename('billing_address')." where users_id='".$this->session->userdata('front_uid')."' order by creation_date desc limit 1";
            $query=$this->db->query($sql);
            $row=$query->row();
            
            return $row; 
        }
        else 
        {
            return array();
        }
    }

    public function sendmail($msg,$email,$sub='')
    {
        $to = $email;
        $subject = "Project Builder ".$sub;
        $txt = $msg;
        $headers = "From:admin@projectbuilder.com";
        if(mail($to,$subject,$txt,$headers))
        {
            return true;
        }
        else
        {
            return;
        }
    }
    
    //Fornt register user
    public function registeruser()
    {
        $fname=$this->input->post('firstname');
        $lname=$this->input->post('lastname');
        $email=$this->input->post('email');
        $password=$this->input->post('password');
        $creation_date=date('Y-m-d H:i:s');
        $modified_date=date('Y-m-d H:i:s');

        $userdata=array('fname'=>$fname,'lname'=>$lname,'email'=>$email,'password'=>sha1($password),'creation_date'=>$creation_date,'modified_date'=>$modified_date);
        $query=$this->db->insert(tablename('users'), $userdata);
        $userid=$this->db->insert_id();
        

        $cust_regi_email_settings = get_setting_by_name('cust_regi_email');

        if($cust_regi_email_settings=="Y")                
        {
           $msg='You have successfully registered';
           $this->sendmail($msg,$email,$sub='');
        }


        if(!empty($userid))
        {
            $sql="select * from ".tablename('users')." where id='".$userid."'";
            $query=$this->db->query($sql);
            $row=$query->row();
            if(!empty($row))
            {
                
                $res=$this->checkoutlogin($email,$password);
                if(!empty($res))
                    return $res;
                else 
                    return "";
            }
            else 
            { 
                return "";
            }
        }
        else 
        {
            return "";
        }
    }
    
    //Front end logout
    public function checklogout($uid)
    {
        $this->session->set_userdata('front_uid','');
        $this->session->set_userdata('front_detail','');
        if($this->session->userdata('front_uid')=="" && $this->session->userdata('front_detail')=="")
            return 1;
        else 
            return "";
    }
    
    //Front check existing password
    public function checkexistingpassword($password)
    {
        $front_uid=$this->session->userdata('front_uid');
        $password=trim($password);
        $sql="select * from ".tablename('users')." where id='".$front_uid."' and password='". sha1($password)."'";
        $query=$this->db->query($sql);
        $row=$query->row();
        if(!empty($row))
        {
            return $row;
        }
        else
            return "";
    }
    
    //Front change password
    public function changepassword()
    {
        $front_uid=$this->session->userdata('front_uid');
        $password=trim($this->input->post('password'));     
        $updsql="update ".tablename('users')." set password='".sha1($password)."' where id='".$front_uid."'";
        $upquery=$this->db->query($updsql);
        
        if(!empty($upquery))
        {
            return 1;
        }
        else 
        {
            return 0;
        }
    }


    public function emailcheck($email)
    {
        $sql="select id from ".tablename('users')." where email='".addslashes(trim($email))."'";
        $query=$this->db->query($sql);
        $row=$query->row();

        if(!empty($row))
        {
            
            $this->load->library('parser');
            $adminid=$row->id;
            $emailarr=explode("@",$email);
            $emuname=sha1($emailarr[0]);
            $activationlink=$this->config->item('base_url')."new-password.aspx/".$emuname;
            $usql="update ".tablename('users')." set hash='".$emuname."' where id='".$adminid."'";
            $q=$this->db->query($usql);

            if(!empty($q))
            {
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: <mail-noreply@cartface.com>' . "\r\n";


            $message="Please click the  link to reset your password.<a href='".$activationlink."'> Click Here</a>";

            $tdata['date']=date('l F d, Y');
            $tdata['year']=date("Y");
            $tdata['siteurl']= $this->config->item('base_url');
            $tdata['logo']=$tdata['siteurl']."assets/images/logo.png";
            $tdata['heading'] = "Recover Your Password";
            $tdata['message'] = $message;

            $msg = $this->parser->parse('mail/mail-template', $tdata,TRUE);

                $mm=mail($email,"Recover Password for TCR Member",$msg,$headers);


                //$mm=mail($email,"Recover Password for TCR Member",$activationlink,$headers);
                //sendmail for resetpass
                return 1;
            }
        }
        else
        {
            return "";
        }
        
    }

    public function passwdupd($password,$activationcode)
    {
        $sql="update ".tablename('users')." set password='".sha1($password)."',hash='' where hash='".$activationcode."'";
        $q=$this->db->query($sql);
        if(!empty($q))
        {
            return 1;
        }
        else
        {
            return "";
        }
    }
}