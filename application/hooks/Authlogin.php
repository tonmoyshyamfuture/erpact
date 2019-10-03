<?php


class AuthLogin
{

    private $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        if(!isset($this->CI->session)){  
              $this->CI->load->library('session'); 
        }
    }
    
   function checkLogin()
   {
       
       $arr=array("forget-password","new-password","checklogin","forgetpass","checkemail","newpass","updatepass");
       $path=$_SERVER['REQUEST_URI'];

       //echo $path;

        if(strpos($path,"admin"))
          {

          }
       else if(strpos($path,"admin/")){
        $flag=0;
                foreach($arr as $key=>$val){
                                 if(strpos($path,$val)){
                                  $flag=1;   
                                     break;
                                 }
                }
      
        if($this->CI->session->userdata('admin_uid')=="" && $flag==0){

                     $this->session->set_flashdata('errormessage','Invalid Access in Admin Panel');

                                        
                       $redirect=site_url('admin');
                          redirect($redirect);
                    }

       }

       else
       {

       
            $this->CI->load->model('admin/sitesettingsmodel', 'settings');
	    $this->CI->load->helper('sitevisitors');
            $result = $this->CI->settings->loadsitesettings();
            if(!empty($result))
            {
              $onlinestatus = intval($result->online_status);
              if ($onlinestatus == 0) {
                  $redirect = $this->CI->config->item('base_url') . "site-offline";
                  $fullurl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                  if ($redirect != $fullurl) {
                      redirect($redirect);
                  }
              }
	      else
	      {
		users_site_visit();
		page_visits();
	      }
            }
        



       }
       
    
   
             
        }
}
