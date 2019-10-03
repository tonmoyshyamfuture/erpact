<?php
class pluginmodel extends CI_Model
{
    public function __construct()
	{
        parent::__construct();
        $this->load->database();
	}
    
	public function loadplugin()
	{
		$sql="select * from ".tablename('modules');
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

	public function installplugin($name,$description,$alias,$version,$author)
	{
		$sql="select id from ".tablename('modules')." where name='".$name."'";
		$query=$this->db->query($sql);
		$r=$query->row();
		if(!empty($r))
		{
			return;
		}
		else
		{
			$sql="insert into ".tablename('modules')." set name='".$name."',alias='".$alias."',description='".$description."',version='".$version."',author='".$author."'";
			$query=$this->db->query($sql);
			if(!empty($query))
			{
				return 1;
			}
		}
	}

	public function delplugin($pluginid)
	{
		$sql="select alias from ".tablename('modules')." where id='".$pluginid."'";
		$query=$this->db->query($sql);
		$r=$query->row();
		if(!empty($r))
		{
			$name=$r->alias;
			$flg=$this->DeleteDir('application/modules/'.$name);

			if(!empty($flg))
			{
				$sql="delete from ".tablename('modules')." where id='".$pluginid."'";
				$query=$this->db->query($sql);

				if(!empty($query))
				{
					return 1;
				}
			}
		}
		else
		{
			return;
		}
	}

	public function DeleteDir($path)
	{
	   if (is_dir($path) === true)
	   {
	       $files = array_diff(scandir($path), array('.', '..'));

	       foreach ($files as $file)
	       {
	           $this->DeleteDir(realpath($path) . '/' . $file);
	       }

	       return rmdir($path);
	   }

	   else if (is_file($path) === true)
	   {
	       return unlink($path);
	   }

	   return;
	}
        
        public function checkplugin($plugin_name)
	{
		$sql="select id from ".tablename('modules')." where alias='".$plugin_name."' and status='1'";
		$query=$this->db->query($sql);
		$r=$query->row();
		if(!empty($r))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function activeplugin($pluginid)
	{
		$sql="select alias from ".tablename('modules')." where id='".$pluginid."'";
		$query=$this->db->query($sql);
		$r=$query->row();
		if(!empty($r))
		{
			$name=$r->alias;
		}

		if(file_exists('application/modules/'.$name.'/security/sql.sample.txt'))
		{
			$fp=fopen('application/modules/'.$name.'/security/sql.sample.txt','r');
			$plugsql="";
			while(!feof($fp))
			{
				$plugsql.=fgets($fp);
			}
			$plugsql=str_replace('PRFX', DBPREFIX, $plugsql);
		}

		if(file_exists('application/modules/'.$name.'/security/routes.sample.txt'))
		{
			$rp=fopen('application/modules/'.$name.'/security/routes.sample.txt','r');
			$rp1=fopen('application/config/routes.php','a');

			$rpgets="";
			while(!feof($rp))
			{
				$rpgets.=fgets($rp);
			}

			$rpgets=str_replace('PAGESUFFIX', PAGESUFFIX, $rpgets);

			fwrite($rp1, "\n". $rpgets);
			fclose($rp1);
			fclose($rp);
			$plugsql_arr = explode(';',$plugsql);

			$flg = false;
			if(!empty($plugsql_arr))
			{
				$this->db->trans_start();
				foreach ($plugsql_arr as $query) {
					$query = trim($query);
					if(!empty($query))
						$this->db->query($query);		
				}
				$this->db->trans_complete();
			}
			
			if ($this->db->trans_status() === TRUE)
			{
			   $flg = True;
			} 
		}
		else
		{
			$flg = True;
		}
		if(!empty($flg))
		{
			$sql="update ".tablename('modules')." set status='1' where id='".$pluginid."'";
			$query=$this->db->query($sql);
			if(!empty($query))
			{
				$menflg=1;
				if(file_exists('application/modules/'.$name.'/security/menu.json'))
				{
					$mp=fopen('application/modules/'.$name.'/security/menu.json','r');
			        $menujson=fgets($mp);

			        $menuarrs=json_decode($menujson);

			        $sql="insert into ".tablename('menu')." set group_menu='".$menuarrs->menu_group."',parentid='0',slug='".$menuarrs->slug."',active='".$menuarrs->active."',url='".$menuarrs->url."',icon='".$menuarrs->icon."',label='".$menuarrs->menuname."',module_slug='".$pluginid."',status='1',created_date='".date('Y-m-d H:i:s')."'";
			        $query=$this->db->query($sql);
			        $menuid=$this->db->insert_id();

			        $childcheck=$menuarrs->child;
			        if(!empty($childcheck))
			        {
			        	foreach($childcheck as $childval)
			        	{
			        		$sql="insert into ".tablename('menu')." set group_menu='".$childval->menu_group."',parentid='".$menuid."',slug='".$childval->slug."',active='".$childval->active."',url='".$childval->url."',icon='".$childval->icon."',label='".$childval->menuname."',module_slug='".$pluginid."',status='1',created_date='".date('Y-m-d H:i:s')."'";
			        		$query=$this->db->query($sql);
			        	}
			        }
		    	}
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
		else
		{
			return;
		}
	}

	public function deactiveplugin($pluginid)
	{
		$sql="select alias from ".tablename('modules')." where id='".$pluginid."'";
		$query=$this->db->query($sql);
		$r=$query->row();
		if(!empty($r))
		{
			$name=$r->alias;
		}

		if(file_exists('application/modules/'.$name.'/security/delsql.sample.txt'))
		{
			$fp=fopen('application/modules/'.$name.'/security/delsql.sample.txt','r');
			$plugsql="";
			while(!feof($fp))
			{
				$plugsql.=fgets($fp);
			}
			$plugsql=str_replace('PRFX', DBPREFIX, $plugsql);
		}

		if(file_exists('application/modules/'.$name.'/security/routes.sample.txt'))
		{
			$rp=fopen('application/modules/'.$name.'/security/routes.sample.txt','r');
			$rp1=fopen('application/config/routes.php','r');
			$rp2=fopen('application/config/routes.sample.php','w+');
			chmod('application/config/routes.sample.php',0777);

			$rpgets="";
			while(!feof($rp))
			{
				$rpgets.=fgets($rp);
			}

			$rpgets=str_replace('PAGESUFFIX', PAGESUFFIX, $rpgets);

			$rpgets1="";
			while(!feof($rp1))
			{
				$rpgets1.=fgets($rp1);
			}

			$newrpgets1=str_replace($rpgets, '', $rpgets1);

			file_put_contents('application/config/routes.sample.php', $newrpgets1);

			fclose($rp2);
			fclose($rp1);
			fclose($rp);

			$plugsql_arr = explode(';',$plugsql);

			unlink('application/config/routes.php');
			rename('application/config/routes.sample.php','application/config/routes.php');

			$flg = false;
			if(!empty($plugsql_arr))
			{
				$this->db->trans_start();
				foreach ($plugsql_arr as $query) {
					$query = trim($query);
					if(!empty($query))
						$this->db->query($query);		
				}
				$this->db->trans_complete();
			}
			
			if ($this->db->trans_status() === TRUE)
			{
			   $flg = True;
			}
		}
		else
		{
			$flg = True;
		}

		if(!empty($flg))
		{
			$sql="update ".tablename('modules')." set status='0' where id='".$pluginid."'";
			$query=$this->db->query($sql);
			if(!empty($query))
			{
				$sql="delete from ".tablename('menu')." where module_slug='".$pluginid."'";
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
		else
		{
			return;
		}
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
}