<?php
class settingsmodel extends CI_Model
{
    public function __construct()
	{
        parent::__construct();
        $this->load->database();
	}

	function getSettings()
	{
		return $this->db->get(tablename('settings'))->result_array();
	}

	function getSettingByName($name)
	{
		return $this->db->where('name',$name)->get(tablename('settings'))->row();
	}
        
            function get_email_template_data($key)
	{

		$sql = "SELECT * FROM ". tablename('email_template')." WHERE email_template_key = '".$key."'";
		$query = $this->db->query($sql);
        $res = $query->row();
        return $res;
    }

	function updateSetting($data,$where)
	{
		$this->db->where($where)->update(tablename('settings'),$data);
	}

	function get_settings_data($name)
	{
		return $this->db->where('name',$name)->get(tablename('settings'))->row();
	}


	public function uploadGeneralSettingsLogo($ary_file,$keyname,$extension)
	{


        $sitelogo="";
		if($ary_file['error']==0)
		{
			$logo=$ary_file['name']	;
			$fileextension = substr($logo, -3);

			/*echo $extension."--".$fileextension;
			exit;*/

			if($extension!="" && $fileextension!=$extension)
			{
				return "exterror";
			}

			$destination="./assets/images/".$logo;
			if(move_uploaded_file($ary_file['tmp_name'], $destination))
			{
				$sitelogo=$logo;
				$sql1="update ".tablename('settings')." set value='".$sitelogo."'  where name='".$keyname."'";
				$query1=$this->db->query($sql1);
				/*$oldfile=$this->input->post('oldsitelogo');
				$olddestination="./assets/images/".$oldfile;
				@unlink($olddestination);*/
			}
		}

		if(!empty($query1))
		{
			return 1;
		}
		else
		{
			return '';
		}

	}

	function getCountryStateCityName($id,$tableName)
	{

		$sql = "SELECT * FROM ". tablename($tableName)." WHERE id = '".$id."'";
		$query = $this->db->query($sql);
        $res = $query->row();
        $name=$res->name;
        return $name;

    }
}