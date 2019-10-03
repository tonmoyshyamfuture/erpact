<?php
class offlineonlinemodel extends CI_Model
{
    public function __construct()
	{
		
        	parent::__construct();
          	$this->load->database();
	}
    
    public function admindetail()
	{
		$sql="select online_status from ".tablename('admins')." where id='1'";
		$query=$this->db->query($sql);
		$admindet=$query->row();
		$online_status=$admindet->online_status;
		return $online_status;
	}
}