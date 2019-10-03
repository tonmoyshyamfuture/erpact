<?php
class sitesettingsmodel extends CI_Model
{
    public function __construct()
	{
        parent::__construct();
        $this->load->database();
	}
    
	public function loadsitesettings()
	{
		$sql="select * from ".tablename('site_settings')." where id='1'";
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
	
	public function updsitesettings()
	{
		$sitename=$this->input->post('sitename');
		$fb_link=$this->input->post('fb_link');
		$tw_link=$this->input->post('tw_link');
		$gp_link=$this->input->post('gp_link');
		$li_link=$this->input->post('li_link');
		$ins_link=$this->input->post('ins_link');
		$pin_link=$this->input->post('pin_link');
		$online_status=$this->input->post('online_status');

		$logback_color='#'.$this->input->post('logback_color');
		$base_color='#'.$this->input->post('base_color');
		$contrast_color='#'.$this->input->post('contrast_color');

		$old_logback_color=$this->input->post('old_logback_color');
		$old_base_color=$this->input->post('old_base_color');
		$old_contrast_color=$this->input->post('old_contrast_color');

		$newcss=fopen('assets/newdeveloper.css', 'w');
		chmod('assets/newdeveloper.css',0777);

		$css=fopen('assets/developer.css','r');

		$cssgets="";
		while(!feof($css))
		{
			$cssgets.=fgets($css);
		}

		$newcssgets=str_replace($old_logback_color, $logback_color, $cssgets);
		$newcssgets=str_replace($old_base_color, $base_color, $newcssgets);
		$newcssgets=str_replace($old_contrast_color, $contrast_color, $newcssgets);

		file_put_contents('assets/newdeveloper.css', $newcssgets);

		unlink('assets/developer.css');
		rename('assets/newdeveloper.css','assets/developer.css');

		fclose($newcss);
		fclose($css);

        $sitelogo="";
		if($_FILES['sitelogo']['error']==0)
			{
				//$this->load->library('upload');
                $logo=$_FILES['sitelogo']['name'];

			
				$logo=$_FILES['sitelogo']['name']	;
				$destination="./assets/images/".$logo;
				if(move_uploaded_file($_FILES['sitelogo']['tmp_name'], $destination))
				{
				    $sitelogo=$logo;
					$sql1="update ".tablename('site_settings')." set sitelogo='".$sitelogo."' where id='1'";
					$query1=$this->db->query($sql1);
                  $oldfile=$this->input->post('oldsitelogo');
                  $olddestination="./assets/images/".$oldfile;
                  @unlink($olddestination);

				}
    		}




    		$sql="update ".tablename('site_settings')." set sitename='".$sitename."',fb_link='".$fb_link."',tw_link='".$tw_link."',gp_link='".$gp_link."',li_link='".$li_link."',ins_link='".$ins_link."',pin_link='".$pin_link."',online_status='".$online_status."',logback_color='".$logback_color."',base_color='".$base_color."',contrast_color='".$contrast_color."' where id='1'";
		$query=$this->db->query($sql);
		if(!empty($query))
		{
			return 1;
		}
		else
		{
			return "";
		}

	}

        public function updstoresettings()
	{
            $sitename=$this->input->post('sitename');
            $sql="update ".tablename('stores')." set store_name='".$sitename."' where id='1'";
            $query=$this->db->query($sql);
                
            if(!empty($query))
            {
                    return 1;
            }
            else
            {
                    return "";
            }

        }
        
	public function getsociallinks()
	{
		$sql="select fb_link,tw_link,gp_link,li_link,ins_link,pin_link from ".tablename('site_settings')." where id='1'";
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

	public function getmenu($name)
	{
		return $this->db->where('name',$name)->get(tablename('menu_settings'))->result();
	}

	public function getallmenu()
	{
		return $this->db->get(tablename('menu_settings'))->result();
	}

	public function addmenu($data,$where,$pid=null)
	{
		$menu = (array)get_menu_from_setting($where);
		$c = count($menu);
		if($pid != null)
			$menu[$pid] = $data;
		else
			$menu['p'.$c] = $data;
		$update['menu'] = json_encode($menu);

		return $this->db->where('name',$where)->update(tablename('menu_settings'),$update);

	}

	public function updatemenu($data,$where)
	{

		$update['menu'] = json_encode($data);

		return $this->db->where('name',$where)->update(tablename('menu_settings'),$update);

	}

   public function getmenuitem($id)
   {
   		$menu = get_menu_from_setting('admin_sidebar');
   		if(!empty($menu))
   		{

   			return is_object($menu) ? $menu->$id : $menu[$id];
   		}

   		return array();

   }

  public function updatemenuitem($id,$data,$pid=null)
  {	
  		$where = 'admin_sidebar';
  		$m = get_menu_from_setting($where);
  		$menu = &$m;
   		if(!empty($menu))
   		{
   			if($pid != null)
   			{
   				$menu[$pid] = $data;
   			}
   			else
   			{
   			 $menu->$id = $data;
   			}
   		}
		
		$update['menu'] = json_encode($menu);

		return $this->db->where('name',$where)->update(tablename('menu_settings'),$update);

  }

}
