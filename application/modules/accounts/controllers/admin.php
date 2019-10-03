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
    public function __construct() {
        parent::__construct();
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        admin_authenticate();
    }

    public function index() {
	$permission=admin_users_permission('V','banner',$rtype=TRUE);
        if($permission)
        {
        $this->load->model('admin/bannermodel');
        $bannerdet=$this->bannermodel->loadbanner();
        $data=array();
        $data['bannerdet']=$bannerdet;

	$statuspermission=admin_users_permission('S','banner',$rtype=FALSE);
	if($statuspermission)
		$data['statuspermission']="";
	else
		$data['statuspermission']="not-permited";

	$editpermission=admin_users_permission('E','banner',$rtype=FALSE);
	if($editpermission)
		$data['editpermission']="";
	else
		$data['editpermission']="not-permited";
	
	$deletepermission=admin_users_permission('D','banner',$rtype=FALSE);
	if($deletepermission)
		$data['deletepermission']="";
	else
		$data['deletepermission']="not-permited";	
	
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | All Banners');
        $this->layouts->render('admin/bannerlist', $data, 'admin');
	}
	else
	{
		$this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
		$redirect = site_url('admin/dashboard');
		redirect($redirect);
	}
    } 
    


    public function formbanner($bannerid=NULL)
    {
	if(!empty($bannerid))
		$param='E';
	else
		$param='A';

	$permission=admin_users_permission($param,'banner',$rtype=TRUE);
        if($permission)
        {
        $bannerid=base64_decode(urldecode($bannerid));
        $data=array();
        $this->load->model('admin/bannermodel');

        $bannerdetsingle=$this->bannermodel->loadbannersingle($bannerid);
        $data['bannerdetsingle']=$bannerdetsingle;

        $getsitename = getsitename();
        if(!empty($bannerid))
        {
            $this->layouts->set_title($getsitename . ' | Edit-banner');
        }
        else
        {
            $this->layouts->set_title($getsitename . ' | Add-banner');
        }
        $this->layouts->render('admin/bannerform', $data, 'admin');
	}
	else
	{
		$this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
		$redirect = site_url('admin/dashboard');
		redirect($redirect);
	}
    }

    public function bannermodify()
    {
	$permission=admin_users_permission('E','banner',$rtype=TRUE);
        if($permission)
        {
        $bannerid="";
        if(!empty($this->input->post('bannerid')))
        {
            $bannerid=$this->input->post('bannerid');
        }

        $this->load->model('admin/bannermodel');
        $description=htmlentities($this->input->post('description'),ENT_QUOTES,'utf-8');

        $file=$_FILES['image'];

        if(!empty($_FILES))
        {
            $imagename=$file['name'];
            $imagearr=explode('.',$imagename);
            $ext=end($imagearr);
            $newimage=time().rand().".".$ext;

            if($ext=="jpg" or $ext=="jpeg" or $ext=="png" or $ext=="bmp")
            {
                $this->load->library('image_lib');

                $config['image_library'] = 'gd2';
                $config['source_image'] = $file['tmp_name'];
                $config['new_image'] = "application/modules/banner/uploads/thumb/".$newimage;
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = FALSE;
                $config['width'] = 200;
                $config['height'] = 100;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                $this->image_lib->resize();

                $config1['image_library'] = 'gd2';
                $config1['source_image'] = $file['tmp_name'];
                $config1['new_image'] = "application/modules/banner/uploads/".$newimage;
                $config1['create_thumb'] = FALSE;
                $config1['maintain_ratio'] = FALSE;
                $config1['width'] = 1600;
                $config1['height'] = 400;

                $this->image_lib->clear();
                $this->image_lib->initialize($config1);
                $this->image_lib->resize();
            }
            else
            {
                $this->session->set_flashdata('errormessage', 'Only .jpg,.jpeg,.bmp and .png image extensions are supported');
            }
        }
        else
        {
            $newimage="";
        }

        $flg=$this->bannermodel->modifybanner($bannerid,$description,$newimage);
        if (!empty($flg)) 
        {
            $this->session->set_flashdata('successmessage', 'Banner modified successfully');
        } 
        else 
        {
            $this->session->set_flashdata('errormessage', 'Oops! Something went wrong');
        }

        $redirect=site_url('admin/banner');
        redirect($redirect);
	}
	else
	{
		$this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
		$redirect = site_url('admin/dashboard');
		redirect($redirect);
	}
    }

    public function statusbanner($bannerid)
    {
	$permission=admin_users_permission('S','banner',$rtype=TRUE);
        if($permission)
        {
        $bannerid=base64_decode(urldecode($bannerid));
        $data=array();
        $this->load->model('admin/bannermodel');

        $flg=$this->bannermodel->statusbanner($bannerid);
        if (!empty($flg)) 
        {
            $this->session->set_flashdata('successmessage', 'Banner status changed successfully');
        } 
        else 
        {
            $this->session->set_flashdata('errormessage', 'Oops! Something went wrong');
        }
        $redirect=site_url('admin/banner');
        redirect($redirect);
	}
	else
	{
		$this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
		$redirect = site_url('admin/dashboard');
		redirect($redirect);
	}
    }

    public function deletebanner($bannerid)
    {
	$permission=admin_users_permission('D','banner',$rtype=TRUE);
        if($permission)
        {
        $bannerid=base64_decode(urldecode($bannerid));
        $data=array();
        $this->load->model('admin/bannermodel');

        $flg=$this->bannermodel->deletebanner($bannerid);
        if (!empty($flg)) 
        {
            $this->session->set_flashdata('successmessage', 'Banner deleted successfully');
        } 
        else 
        {
            $this->session->set_flashdata('errormessage', 'Oops! Something went wrong');
        }
        $redirect=site_url('admin/banner');
        redirect($redirect);
	}
	else
	{
		$this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
		$redirect = site_url('admin/dashboard');
		redirect($redirect);
	}
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
