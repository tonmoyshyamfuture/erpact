<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Blogger {

	private $_pages = array();
    public function __construct()
	{
		 $this->CI =& get_instance();
         $this->load->model('blogs/admin/blogmodel');
	
	}

    function writtenby($id){
	$res=$this->CI->blogmodel->getuserdetails($id); 
	pr($res);
	}





}


?>