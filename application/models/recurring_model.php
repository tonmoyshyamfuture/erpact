<?php

class recurring_model extends CI_Model
{
    
    public function __construct() {

        parent::__construct();
        $this->load->database();
    }
    
    public function get_all_dealy_recurring()
  {
    return $this->db->select('*')->from('recurring_entry')->where('frequency =','Daily')->where('status =', 1)->get()->result();
       
  }
  
  public function get_all_weekly_recurring(){
     return $this->db->select('*')->from('recurring_entry')->where('frequency =','Weekly')->where('status =', 1)->get()->result();  
  }
  
  public function get_all_monthly_recurring(){
     return $this->db->select('*')->from('recurring_entry')->where('frequency =','Monthly')->where('status =', 1)->get()->result();  
  }
  
  public function get_all_yearly_recurring(){
     return $this->db->select('*')->from('recurring_entry')->where('frequency =','Yearly')->where('status =', 1)->get()->result();  
  }

  
  //get all entry
  public function get_all_entry($entry_id_arr)
  {
    return $this->db->select('*')->from('entry')->where_in('id', $entry_id_arr)->get()->result();
       
  }
  
  //get all entry details
  public function get_all_entry_details($entry_id)
  {
    return $this->db->select('*')->from('ladger_account_detail')->where('entry_id', $entry_id)->get()->result();
       
  }
  
  //get all entry details
  public function get_all_voucher($entry_id)
  {
    return $this->db->select('*')->from('billwish_details')->where('entry_id', $entry_id)->get()->result();
       
  }
  
   //get all tracking details
  public function get_all_tracking($entry_id)
  {
    return $this->db->select('*')->from('tracking_details')->where('entry_id', $entry_id)->get()->result();
       
  }
  
   //get all bank details
  public function get_all_bank_data($entry_id)
  {
    return $this->db->select('*')->from('bank_details')->where('entry_id', $entry_id)->get()->result();
       
  }
  
  //save entry
  public function saveEntry($entry_data){
   $this->db->insert('entry', $entry_data);
   $insert_id = $this->db->insert_id();
   return  $insert_id;    
  }
  
   public function getEntryTypeById($id) {
        $this->db->select('*');
        $this->db->from('entry_type');
        $this->db->where('status', '1');
        $this->db->where('deleted', '0');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function getNoOfByTypeId($id, $to, $from) {
        return $result = $this->db->select('COUNT(id) AS total_transaction')
                ->where('entry_type_id', $id)
               // ->where('create_date >= ', $from)
               // ->where('create_date <= ', $to)
               // ->where('status', '1')
                ->get('entry')
                ->row_array();
    }
    
    
    public function get_voucher_details($voucher_id,$sub_voucher_id){
         if($sub_voucher_id != 0){
             return $this->db->select('*')->from('entry_type')->where('id', $sub_voucher_id)->get()->row_array();
         }else{
             return $this->db->select('*')->from('entry_type')->where('id', $voucher_id)->get()->row_array();
         }
        
      }
      
    public function get_company_details(){
        return $this->db->select('*')->from('account_settings')->where('id', 1)->get()->row();
    }
 
  
}

