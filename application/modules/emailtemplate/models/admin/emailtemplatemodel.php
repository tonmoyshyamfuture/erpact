<?php
class emailtemplatemodel extends CI_Model
{
    public function __construct()
	{
        parent::__construct();
        $this->load->database();
	}
    
	public function getAllData()
	{
		$this-> db->from(tablename('email_template'));
                $this->db->where('status', '1');
		$query = $this->db-> get();
		$result=$query->result();
		if(!empty($result))
		{
			return $result;
		}
		else
		{
			return "";
		}
	}
	
	public function getData($id)
	{
		$this->db->from(tablename('email_template'));
		$this->db->where('id', $id);
		$query = $this->db-> get();
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
	

	public function updateemailtemplate($id,$menu_arr)
	{
		return $this->db->where('id',$id)->update(tablename('email_template'),$menu_arr);
	}

	public function statusData($id)
	{
		$sql="select status from ".tablename('email_template')." where id='".$id."'";
		$query=$this->db->query($sql);
		$r=$query->row();
		$status=$r->status;
		if($status==1)
		{
			$ssql="update ".tablename('email_template')." set status='0' where id='".$id."'";
		}
		if($status==0)
		{
			$ssql="update ".tablename('email_template')." set status='1' where id='".$id."'";
		}
		$qquery=$this->db->query($ssql);
		if(!empty($qquery))
		{
			return 1;
		}
		else
		{
			return "";
		}
	}

	public function getCurrentCompnayMail()
	{
		$this->db->select('email');
		$this->db->where('id', 1);
		$this->db->from('account_settings');
		$query = $this->db->get();
		$result = $query->row();
		return $result->email;

	}
}
