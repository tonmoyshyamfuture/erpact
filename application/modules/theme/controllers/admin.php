<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends MX_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    private $category = 1;
    public function __construct() {
        parent::__construct();
        $this->load->helper('url', 'form');
        $this->load->helper('directory');
        $this->load->library('form_validation');
        $this->load->model('admin/thememodel','tm');
        $this->load->model('admin/themecategorymodel','tcm');
        admin_authenticate();
    }
    

    public function index($category='all',$type='all',$sortby='newest',$offset=0) {
        $data=array();
        $options['theme_category'] = $category;
        $options['theme_type'] = $type;
        $options['sortby'] = $sortby;
        $options['offset'] = $offset;

        $path_root=$_SERVER['DOCUMENT_ROOT'];
        $data['directory_structure_list'] = $path_root.'/P-007-projectbuilder/';
        
        $data['categories'] = $this->tcm->getAllCategories();
        $data['themes'] = $this->tm->getAllThemes($options);
        $data['activetheme'] = $this->tm->getActiveTheme();

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Theme View');
        $this->layouts->render('admin/theme', $data, 'admin');
	
    } 
    
   public function activate($tid)
   {
      if(empty($tid)) {
        $redirect = site_url('admin/theme');
        redirect($redirect);
      }

      $tid = doUrlDecode($tid);

     $themeinfo = $this->tm->getThemeInfo($tid);
     
     if(!file_exists(APPPATH.'../assets/themes/'.$themeinfo->theme_slug))
     {
        $this->session->set_flashdata('errormessage', "Unable to activate theme !!");
        $redirect = site_url('admin/theme');
        redirect($redirect);
     }

     if($this->tm->makeActiveTheme($tid))
     {

      if ( file_exists( APPPATH . '/config/twiggy.php' ))
        {
              $config_file = file( APPPATH . '/config/twiggy.php' );

              // Not a PHP5-style by-reference foreach, as this file must be parseable by PHP4.
              foreach ( $config_file as $line_num => $line ) {
                if ( ! preg_match( '/^\$config\[\'twiggy\'\]\[\'default_theme\'\]/', $line, $match ) ){continue;}

                   $config_file[ $line_num ] = '$config[\'twiggy\'][\'default_theme\'] = \''.getActiveTheme()."/views';\r\n";
                    
                  
              }
              

                  $path_to_config = APPPATH . '/config/twiggy.php';
                  if(!is_writable($path_to_config))
                  {
                    chmod($path_to_config,0777);
                  }

                  $handle = fopen( $path_to_config, 'w' );
                  foreach( $config_file as $line ) {
                    fwrite( $handle, $line );
                  }
                  fclose( $handle );
                  chmod($path_to_config,0644);
        }

        $this->session->set_flashdata('successmessage', "New theme activated successfully !!");
     }

        $redirect = site_url('admin/theme');
        redirect($redirect);
   }

  public function preview()
   {

   }

  public function upload()
   {
        $data=array();
        $data['categories'] = $this->tcm->getAllCategories();
        if($this->input->post('upload'))
        {   
            $this->category = $this->input->post('category');
            $this->_doupload();
        }

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Theme Upload');
        $this->layouts->render('admin/upload', $data, 'admin');
   }

  private function _doupload()
  {
        $config['upload_path'] = APPPATH.'../assets/uploads/';
        $config['allowed_types'] = 'zip';
        $config['max_size'] = 1024*50;
        $config['overwrite'] = true;


        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('theme'))
        {
            $this->session->set_flashdata('errormessage', $this->upload->display_errors());
            $redirect = site_url('admin/theme/upload');
            redirect($redirect);
            die;
        }
        else
        {
            $data = $this->upload->data();
            
            if(!$this->category)
            {
                $this->session->set_flashdata('errormessage', "Please select a theme category and upload again !!");
                @chmod($data['full_path'], 0777);
                @unlink($data['full_path']);
                $redirect = site_url('admin/theme/upload');
                redirect($redirect);
            }

            $datadb = $this->_zipUploadToTheme($data);
            if(!$datadb)
            {
                $this->session->set_flashdata('errormessage', $this->upload->display_errors());
                $redirect = site_url('admin/theme/upload');
                redirect($redirect);
            }
            else
            {
                $res = $this->tm->addTheme($datadb);
                if($res)
                {
                    $this->session->set_flashdata('successmessage', "Theme uploaded successfully");
                    $redirect = site_url('admin/theme/upload');
                    redirect($redirect);
                }
                else
                {
                    $this->session->set_flashdata('errormessage', "Ops !! something went wrong plz try again !!");
                    $redirect = site_url('admin/theme/upload');
                    redirect($redirect);
                }
            }
            die;
        }
  }

  private function _zipUploadToTheme($data)
  {
    $this->load->library('unzip');
    $themes_folder = get_themes_folder_path();
    $theme_zip_path = $data['full_path'];
    $theme_name = rtrim($data['file_name'],'.zip');
    $theme_name = str_replace(array('_',' '),'-',$theme_name);
    
    @chmod($theme_zip_path, 0777);
    $themes_folder.$theme_name;

    if(file_exists($themes_folder.$theme_name)){ 
                $this->session->set_flashdata('errormessage', "Theme already exists use another theme name !!");
                $redirect = site_url('admin/theme/upload');
                redirect($redirect); 
    }
    // Optional: Only take out these files, anything else is ignored
    //$this->unzip->allow(array('css', 'js', 'png', 'gif', 'jpeg', 'jpg', 'twig', 'html', 'php','swf'));

    // or specify a destination directory
    $this->unzip->extract($theme_zip_path,$themes_folder);
    $themeinfo = array(
      'theme_name' => $theme_name,
      'theme_author' => '---',
      'theme_description' => '---',
      /*'theme_version' => '0.0.0'*/
    );
    if(file_exists($themes_folder.$theme_name.'/readme.txt'))
    {
        $info = file_get_contents($themes_folder.$theme_name.'/readme.txt');
        $info = json_decode($info,true);
        $themeinfo['theme_name'] =  str_replace(array('_','-'),' ',$info['name']);
        $themeinfo['theme_slug'] = strtolower(str_replace(array('_',' '),'-',$info['name']));
        $themeinfo['theme_author'] = $info['author'];
        $themeinfo['theme_description'] = $info['description'];
        $themeinfo['theme_category'] = $this->category;
        /*$themeinfo['theme_version'] = $info['version'];*/
        if(file_exists($themes_folder.$themeinfo['theme_name']) && ($theme_name != $themeinfo['theme_name'])){ 
                $this->session->set_flashdata('errormessage', "Theme already exists use another theme name !!");
                $this->_recursiveRemoveDirectory($themes_folder.$themeinfo['theme_name']); 
                $redirect = site_url('admin/theme/upload');
                redirect($redirect);
                die;

        }
        
        rename($themes_folder.$theme_name, $themes_folder.$themeinfo['theme_slug']);
    }
    
    return $themeinfo;
  }

    private function _recursiveRemoveDirectory($directory)
    {
        foreach(glob("{$directory}/*") as $file)
        {
            @chmod($file, 0777);
            if(is_dir($file)) { 
                $this->_recursiveRemoveDirectory($file);
            } else {
                @unlink($file);
            }
        }
        rmdir($directory);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
