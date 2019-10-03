<?php
class Thememodel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllThemes($options=array())
    {
    	if(!empty($options))
    	{
    		if(isset($options['theme_category']) && $options['theme_category'] != 'all')
    		{
    			$cat = $this->db->where('category_slug',$options['theme_category'])->get(tablename('theme_categories'))->row();
    			$this->db->where('theme_category',$cat->cid);
    		}

    		if(isset($options['theme_type']) && $options['theme_type'] != 'all')
    		{
    			$type = $options['theme_type'] == 'free' ? '0' : '1';
    			$this->db->where('theme_type',$type);
    		}

    		if(isset($options['sortby']) && $options['sortby'])
    		{
    			switch($options['sortby'])
    			{
    				case 'newest':
    				default:
    					$this->db->order_by('created_date','desc');
    				break;
    			}
    		}
    	}
    	return $this->db->get(tablename('themes'))->result();
    }

    function getActiveTheme()
    {
        return $this->db->where('active_theme','1')->get(tablename('themes'))->row();
    }

    function getThemeInfo($themeid)
    {
       return $this->db->where('tid',$themeid)->get(tablename('themes'))->row(); 
    }

    function makeActiveTheme($themeid)
    {
               $this->db->update(tablename('themes'),array('active_theme' => '0'));
        return $this->db->where('tid',$themeid)->update(tablename('themes'),array('active_theme' => '1'));
    }

    function addTheme($data)
    {
        return $this->db->insert(tablename('themes'),$data);
    }
    
}