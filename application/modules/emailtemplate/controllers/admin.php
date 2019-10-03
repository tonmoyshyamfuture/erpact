<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class admin extends MX_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -  
     *      http://example.com/index.php/welcome/index
     *  - or -
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
        $this->load->model('admin/emailtemplatemodel');
        admin_authenticate();
    }

     /* -- Newsletter -- */
    public function emailtemplate($liferayload=NULL)
    {
       user_permission(193,'list');
            $this->load->model('admin/emailtemplatemodel', 'emailtemplate');
            $emailtemplate = $this->emailtemplate->getAllData();
            $data['emailtemplate'] = $emailtemplate;

        	

            if(!empty($liferayload) && $liferayload=='liferayload')
            {
                $liferayloadview=$this->load->view('admin/emailtemplate-list', $data,true);
                echo $liferayloadview;
                exit;
            }
            else
            {
                $getsitename = getsitename();
                $this->layouts->set_title($getsitename . ' | All Email Templates');
                $this->layouts->render('admin/emailtemplate-list', $data, 'admin');
            }            
            


    	
    }


    public function addemailtemplate($id = NULL)
    {
        user_permission(193,'add');
            $this->load->model('admin/emailtemplatemodel', 'emailtemplate');
            $this->load->helper('directory');

            $name = "";
            $data = array();
            if (!empty($id)) {
                $id = base64_decode(urldecode($id));
                $emailtemplate = $this->emailtemplate->getData($id);

                // echo "<pre>";
                // print_r($emailtemplate );
                // exit;

                $data['emailtemplate'] = $emailtemplate;
                $name = "Edit Email Template";
            } else {

                $name = "Add Email Template";
            }
            
            $getsitename = getsitename();
            $this->layouts->set_title($getsitename . ' | ' . $name);
            $this->layouts->render('admin/addemailtemplate', $data, 'admin');
      
    }


    public function emailtemplateupdate($id = NULL)
    {
        user_permission(193,'edit');
            $this->load->model('admin/emailtemplatemodel', 'emailtemplate');
            if (!empty($id))
            {
                $id = base64_decode(urldecode($id));

                $name = trim($this->input->post('name'));
                $email_from = trim($this->input->post('email_from'));
                $email_cc = trim($this->input->post('email_cc'));
                $email_subject = trim($this->input->post('email_subject'));
                $content = $this->input->post('content');

                $menu_arr = array(
                    'name' => $name,
                    'email_from'  =>$email_from,
                    'email_cc'  =>$email_cc,
                    'email_subject' => $email_subject,
                    'content' => $content,
                    'modifiedDate'  =>date("Y-m-d H:i:s")
                );

                $res = $this->emailtemplate->updateemailtemplate($id,$menu_arr);

                if (!empty($res)){
                    $this->session->set_flashdata('successmessage', 'Email Template updated successfully');
                } else {
                    $this->session->set_flashdata('errormessage', 'Oops! Something went wrong. Please try again.');
                }
                $redirect = site_url('admin/email-template');
                redirect($redirect);
            }
       
    }

    public function statusemailtemplate($id)
    {
        $permission=admin_users_permission('S','emailtemplate',$rtype=TRUE);
        if($permission)
        {
            $id = base64_decode(urldecode($id));
            $this->load->model('admin/emailtemplatemodel', 'emailtemplate');
            $flg = $this->emailtemplate->statusData($id);
            if (!empty($flg)) {
                $this->session->set_flashdata('successmessage', 'Status changed successfully');
            } else {
                $this->session->set_flashdata('errormessage', 'Oops! something went wrong. Please try again');
            }
            $redirect = site_url('admin/emailtemplate');
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
