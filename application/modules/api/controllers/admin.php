<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class admin extends MX_Controller {


    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->model('admin/apimodel');
    }

   
    public function test(){
        echo 43434;exit;
    }
}

