<?php
class Themecategorymodel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllCategories()
    {
    	return $this->db->get(tablename('theme_categories'))->result();
    }
    
}