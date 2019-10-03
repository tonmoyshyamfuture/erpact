<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  

class Layouts {
    
  private $ci;
    
  private $title = NULL;
 
    
  public function __construct() 
  {
    $this->ci =& get_instance();
  }
    
  public function set_title($title)
  {
    $this->title = $title;
  }
    
  public function render($view_name, $params = array(), $layout = 'site')
  { 
      
      $CI = & get_instance();
      $CI->load->model('admin/dashboardmodel', 'dashboardmodel');
      $CI->load->library('session');
      $sass_id = $CI->dashboardmodel->getSaasId($CI->session->userdata('admin_uid'));
      $params['companylist'] = $CI->dashboardmodel->getAllCompanyByUserId($sass_id);
      $current_db_size = $CI->dashboardmodel->getCurrentDbSize(SUB_DB_PREFIX.$_SESSION['dbname']);
      $folder_size = $CI->dashboardmodel->folderSize();
      $params['current_project_size'] = number_format($current_db_size+$folder_size, 2);
      $params['hot_keys'] = $CI->dashboardmodel->getAllHotKeys();
      $params['trash_entries'] = $CI->dashboardmodel->getAllTrashEntries();
      $params['act_setting'] = $CI->dashboardmodel->getCurrentSetting();
      // $params['report_menu'] = $CI->dashboardmodel->getReportMenu();
      $CI->session->set_userdata('userid', $sass_id);
      
        $view_content = $this->ci->load->view($view_name, $params, TRUE);
  
    $this->ci->load->view('Layouts/' . $layout, array(
      'content' => $view_content,
      'title' => $this->title
    ));
  }
    

  

}


