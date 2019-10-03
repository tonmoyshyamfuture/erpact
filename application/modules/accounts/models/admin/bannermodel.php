<?php
class bannermodel extends CI_Model
{
    public function __construct()
	{
        parent::__construct();
        $this->load->database();
	}
    
	public function loadbanner($id=NULL)
	{
		$sql="select * from ".tablename('banner')." WHERE archive_status = '0'";
		$query=$this->db->query($sql);
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
	
	public function loadbannersingle($id)
	{
		$sql="select * from ".tablename('banner')." where id='".$id."'";
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

	public function modifybanner($bannerid=NULL,$description,$newimage=NULL)
	{
		if(!empty($bannerid))
		{
			if(!empty($newimage))
			{
				$presql="select image from ".tablename('banner')." where id='".$bannerid."'";
				$query=$this->db->query($presql);
				$r=$query->row();
				$oldimage=$r->image;
				if(!empty($oldimage))
				{
				  	unlink("application/modules/banner/uploads/".$oldimage);
					unlink("application/modules/banner/uploads/thumb/".$oldimage);
				}
				$sql="update ".tablename('banner')." set image='".$newimage."',description='".$description."',status='1' where id='".$bannerid."'";
			}
			else
			{
				$sql="update ".tablename('banner')." set description='".$description."',status='1' where id='".$bannerid."'";
			}
		}
		else
		{
			$sql="insert into ".tablename('banner')." set image='".$newimage."',description='".$description."',status='1'";
		}
		$flg=$this->db->query($sql);
		if(!empty($flg))
		{
			return 1;
		}
		else
		{
			return;
		}
	}

	public function statusbanner($bannerid)
	{
		$sql="select status from ".tablename('banner')." where id='".$bannerid."'";
		$q=$this->db->query($sql);
		$r=$q->row();
		if(!empty($r))
		{
			$status=$r->status;
			if($status==0)
			{
				$newstatus=1;
			}
			else
			{
				$newstatus=0;
			}
			$sql="update ".tablename('banner')." set status='".$newstatus."' where id='".$bannerid."'";
			$query=$this->db->query($sql);
			if(!empty($query))
			{
				return 1;
			}
			else
			{
				return;
			}
		}
		else
		{
			return;
		}
	}

	public function deletebanner($bannerid)
	{
		$presql="select image from ".tablename('banner')." where id='".$bannerid."'";
		$query=$this->db->query($presql);
		$r=$query->row();
		$image=$r->image;
		if(!empty($image))
		{
		    //	unlink("application/modules/banner/uploads/".$image);
		    //	unlink("application/modules/banner/uploads/thumb/".$image);
			$sql="UPDATE ".tablename('banner')." SET status='0', archive_status='1' where id='".$bannerid."'";
			$q=$this->db->query($sql);
			if(!empty($q))
			{
				return 1;
			}
			else
			{
				return;
			}
		}
		else
		{
			return;
		}
	}
}
