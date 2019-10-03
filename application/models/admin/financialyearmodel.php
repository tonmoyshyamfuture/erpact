<?php
class financialyearmodel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

   //get financial year 
    public function getFinancialYear() {
        $record = $this->db->select('finalcial_year_from')
                ->from('account_standard_format')
                ->where('id', 1)
                ->get();
        return $record->row();
    }
    
    public function getMaxEntryDate()
    {
        $this->db->select_max('create_date');
        $this->db->where('deleted', 0);
        $this->db->where('status', 1);
        $this->db->where('company_id =', $this->session->userdata('branch_id'));
        return $this->db->get('entry')->row();
    }
}
