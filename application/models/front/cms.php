<?php
class cms extends CI_Model
{
    public function __construct()
	{
        parent::__construct();
        $this->load->database();
	}
    
	
	
	public function getCms($slug)
	{
		$sql="select * from ".tablename('cms')." where alias='".$slug."' and on_off=1";
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
	
	 public function getCMSPages(){

		$sql="select * from ".tablename('cms')." where id NOT IN(13,17,18) and on_off=1";
		$query=$this->db->query($sql);
		$row=$query->result();
		if(!empty($row))
		{
			return $row;
		}
		else
		{
			return "";
		}

         }


      public function getPrivacyTerms(){

		$sql="select * from ".tablename('cms')." where id IN(13,17) and on_off=1";
		$query=$this->db->query($sql);
		$row=$query->result();
		if(!empty($row))
		{
			return $row;
		}
		else
		{
			return "";
		}

         }


      public function getBootom(){

		$sql="select * from ".tablename('cms')." where id  NOT IN(13,17) and on_off=1";
		$query=$this->db->query($sql);
		$row=$query->result();
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
