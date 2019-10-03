<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FacebookLogin extends CI_Controller {

    public $user = "";

    public function __construct() {
        parent::__construct();
        $this->load->model("front/users", "customer");
        $this->load->library('facebook', array('appId' => '1021594431193860', 'secret' => 'b5d136c765968336436e5abd18f7105b'));

        $this->user = $this->facebook->getUser();
    }

    public function index() {

        if ($this->user) {
            $data['user_profile'] = $this->facebook->api('/me');

            $url = $this->facebook->getLogoutUrl(array('next' => base_url() . 'facebookLogin/logout'));



            $data['logout_url'] = $url;


            $sid = $data['user_profile']['id'];

            $sess_array = array();
            $testresult = $this->customer->getFacebookCustomer($sid);

            if (!empty($testresult)) {

                $row = $testresult[0];
                $name = $row->fname . " " . $row->lname;
                $sess_array = array(
                    'uid' => $row->id,
                    'username' => $name,
                    'is_user_logged_in' => true
                );
                $this->session->set_userdata('user_logged_in', $sess_array);
                $this->session->set_userdata('facebook_logout', $url);
                redirect(base_url());
            } else {

                $socialid = $sid;

                ///////////////new implement on 9 th july//////////////////////////////////////
                $sess_array = array(
                    'uid' => $data['user_profile']['id'],
                    'username' => $name,
                    'firstname' => $data['user_profile']['first_name'],
                     'lastname' => $data['user_profile']['last_name'],
                    'email' => $data['user_profile']['email'],
                    'social' =>'social' 
                    
                );
                $this->session->set_userdata('user_fb_signup', $sess_array);
                 redirect(site_url('user-register'));
                
                /////////////new implement end////////////////////////////////////////////////

                /* $resultno= $this->customer->insertSocialData($data['user_profile']['first_name'],$data['user_profile']['last_name'],$socialid);
                  if($resultno>0)
                  {

                  $row=$data['user_profile'];
                  $name=$data['user_profile']['fname']." ".$data['user_profile']['lname'];
                  $sess_array = array(
                  'uid' => $data['user_profile']['id'],
                  'username' => $name,
                  'is_user_logged_in' => true
                  );
                  $this->session->set_userdata('user_logged_in', $sess_array);
                  $this->session->set_userdata('facebook_logout',$url);



                  }
                  else{ echo "<script> location.href='".$url."'; </script>"; } */
            }

               
           
        } else {

            $link = $this->facebook->getLoginUrl(array('scope' => 'email'));
            echo "<script> location.href='" . $link . "'; </script>";
        }
    }

    // Logout from facebook
    public function logout() {

        session_destroy();
        $this->session->sess_destroy();
        redirect(base_url());
    }

}

/* End of file users.php */
/* Location: ./application/controllers/front/facebookLogin.php */
