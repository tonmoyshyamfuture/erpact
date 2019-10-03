<?php

class bill_reminder_model extends CI_Model
{
    
    public function __construct() {

        parent::__construct();
        $this->load->database();
    }
    
  public function get_next_days_bill($day)
  {
    $day_start = date('Y-m-d 00:00:00', $day);
    $day_end = date('Y-m-d 23:59:59', $day);
    return $this->db->select('*')
      ->from('billwish_details')
      ->where('credit_date >=', $day_start) ->where('credit_date <=', $day_end)->where('ref_type =', 'New Reference')->where('deleted =', '0')
      ->get()->result();
       
  }
  
  public function get_todays_bill($day)
  {
    $day_start = date('Y-m-d 00:00:00', $day);
    $day_end = date('Y-m-d 23:59:59', $day);
    return $this->db->select('*')
      ->from('billwish_details')
      ->where('credit_date >=', $day_start) ->where('credit_date <=', $day_end)->where('ref_type =', 'New Reference')->where('deleted =', '0')
      ->get()->result();
       
  }
  
  public function get_previous_days_bill($from_day,$to_days)
  {
    $day_start = date('Y-m-d 00:00:00', $from_day);
    $day_end = date('Y-m-d 23:59:59', $to_days);
    return $this->db->select('*')
      ->from('billwish_details')
      ->where('credit_date >=', $day_start) ->where('credit_date <=', $day_end)->where('ref_type =', 'New Reference')->where('deleted =', '0')
      ->get()->result();
       
  }
  
   public function getContactDetails($ledger_id) {
        $this->db->select('CD.email,CD.company_name');
        $this->db->from('customer_details as CD');
        $this->db->where('CD.ledger_id', $ledger_id);
         $this->db->where('CD.status', 1);
        $query = $this->db->get();
        return $query->row();
    }
 
  public function mark_reminded($bill_id)
  {
    return $this->db->where('id', $bill_id)->update('billwish_details', array('is_reminded' => 1));
  }
}

