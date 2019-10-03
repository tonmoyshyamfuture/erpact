<?php
/************************************************
 * This helper is used for Admin Permission *
 ************************************************/
 
function admin_permission($permission,$modulenm=NULL,$rtype=false)
{
    $CI = & get_instance();
    $admin_uid=$CI->session->userdata('admin_uid');
    $CI->load->model('admin/subadminmodel','adminsmodel');
    $row= $CI->adminsmodel->getsubadmin($admin_uid);
    if(!empty($row) && $row->group_code!=0)
    {
    $result=$CI->adminsmodel->getRole($row->group_code);
    $module=$CI->router->fetch_module();
    $role=(array)json_decode($result->group_role);
     if($modulenm!=$module)
      { 

        $module=$modulenm; 

      }
    if(array_key_exists($module, $role))
    {
       $arr= (array)$role[$module];
       if(array_key_exists($permission, $arr))
        {
               return true; 
        }
        else
        {

            if($rtype){
              $CI->session->set_flashdata('errormessage', "You are permitted to perform this action");
              $redirect = site_url('admin/dashboard');
              redirect($redirect);
            }
            else
              {
                return false; 
              }
        }
    }

  }

}



function admin_email($arr)
{

		    $CI = & get_instance();
		    $admin_uid=$CI->session->userdata('admin_uid');

            $CI->load->library('parser');
            /*
              message to be sent to the user
             */
           
            $tdata['date'] = date('l F d, Y');
            $tdata['year'] = date("Y");
            $tdata['siteurl'] = $CI->config->item('base_url');
            $tdata['logo'] = $tdata['siteurl'] . "assets/images/logo.png";
            $tdata['heading'] = $arr['subject'];
            $tdata['message'] = $arr['message'];

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <' . get_admin_email() . '>' . "\r\n";
            /*
              sending date to email tempelate
             */
            $msg = $CI->parser->parse('mail/mail-template', $tdata, TRUE);

            /*
              sending mail for reply to contact us
             */

              $subject=getsitename()." | ".$arr['subject'];
            mail($arr['email'], $subject, $msg, $headers);


}