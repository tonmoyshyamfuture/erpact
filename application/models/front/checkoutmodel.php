<?php
class checkoutmodel extends CI_Model
{
    public function __construct()
	{
        parent::__construct();
        $this->load->database();
	}
    
	public function getcheckoutdata()
	{
		$sql="select * from ".tablename('checkout')." where id='1'";
		$query=$this->db->query($sql);
		$row=$query->row();
		if(!empty($row))
		{
			return $row;
		}
		else
		{
			return "";
		}
	}
	
	
}
?>
