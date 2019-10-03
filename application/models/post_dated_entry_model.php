<?php

class post_dated_entry_model extends CI_Model
{
    
    public function __construct() {

        parent::__construct();
        $this->load->database();
    }
    
  public function get_all_post_dated_entry($date)
  {
   $from_date=$date." 00:00:00"; 
   $to_date=$date." 23:59:59";
    return $this->db->select('id')->from('entry')->where('create_date >=', $from_date)->where('create_date <=', $to_date)->where('deleted =', 2)->get()->result();
       
  }
  
  
  
 
 
  
}

