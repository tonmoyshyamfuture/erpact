<?php

class companymodel extends CI_Model {

    public function __construct() {
        $this->db2 = $this->load->database('db2', TRUE);
        parent::__construct();
    }

    public function getCompanyDetails($company_id,$user_id) {
        $this->db2->select("company_details.comp_db_name,user.id as user_id,user.password");
        $this->db2->from('user_relation');
        $this->db2->join('user', 'user.id = user_relation.user_id', 'left');
        $this->db2->join('company_details', 'company_details.id = user_relation.company_id', 'left');
        $this->db2->where('company_details.id', $company_id);
        $this->db2->where('user.id', $user_id);
        $this->db2->where('user_relation.status', '1');
        $this->db2->where('user.status', '1');
        $query = $this->db2->get();
        return $query->row();
    }

    public function getCompany($company_id) {
        $this->db2->select("*");
        $this->db2->from('company_details');
        $this->db2->where('status', '1');
        $this->db2->where('id', $company_id);
        $query = $this->db2->get();
        return $query->row();
    }

    // update last login for a specific company at the time of login
    public function updateCompanyLastLogin($company_id)
    {
        $this->db2->where('id', $company_id);
        $this->db2->update("company_details", array( 'last_login' => date("Y-m-d H:i:s", time())));
    }

    public function updateCsvUploadHistory($data)
    {
        $this->db->insert('csv_upload_history', $data);
    }

    public function getCsvUploadHistory($module='')
    {
        $this->db->where('module', $module);
        $query = $this->db->get('csv_upload_history');
        $row = $query->row();
        if (empty($row)) {
            $entry = $this->db->get('entry')->result();
            if (empty($entry)) {
                return true;
            } else {
                return false;
            } 
        } else {
            return false;
        }
        

    }

    public function getDataFromTable($table = '', $where = '', $ret = 0)
    {
        $this->db->where($where);
        $query = $this->db->get($table);
        if ($ret) {
            return $query->row();
        } else {
            return $query->result();
        }
        
    }

    public function insertBatch($table = '', $data = '')
    {
        $this->db->insert_batch($table, $data);
    }

}
