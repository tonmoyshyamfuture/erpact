<?php 
function admin_users_permission($permission,$modulenm=NULL,$rtype=false)
{
    $CI = & get_instance();
    $db =& DB();
    $selsql="select * from ".tablename('modules')." where alias='users'";
    $query=$db->query($selsql);
    $usersexistance=$query->row();
    if(!empty($usersexistance))
    {
        $admin_uid=$CI->session->userdata('admin_uid');
        $CI->load->model('users/admin/subadminmodel','adminsmodel');
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
                      $CI->session->set_flashdata('errormessage', "You are not permitted to perform this action");
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
    else 
    {
        return true;
    }

}


function getPreferences() {
  $CI = & get_instance();
  $CI->load->model('accounts_configuration/admin/accountconfigurationmodel');

  $result = $CI->accountconfigurationmodel->loadAll();
  $data = [];
  $data['branch'] = $CI->accountconfigurationmodel->getAllBranch();
  $getsitename = getsitename();
  $data['configuration'] = $result;
  $data['currency'] = $CI->accountconfigurationmodel->getAllCurrency();
  $data['date_format'] = $CI->accountconfigurationmodel->getAllDateFormat();
  $data['number_of_branch'] = $CI->accountconfigurationmodel->getNumberOfBranch();
  return $data;
}