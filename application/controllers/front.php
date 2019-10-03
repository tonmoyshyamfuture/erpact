<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Front extends CI_Controller {


    public function __construct() {
        parent::__construct();
       
    }

    public function index() {
        redirect(base_url());

    }
  
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
