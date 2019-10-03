<?php

class accountconfigurationmodel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('front/usermodel', 'currentusermodel');
    }

    public function loadAll($id = NULL) {

        $query = $this->db->select('*')
                ->from(tablename('account_configuration'))
                ->get();
        return $row = $query->first_row();
    }

    public function modifyData($data, $id) {
        $log = array(
            'user_id' => $this->session->userdata('admin_uid'),
            'branch_id' => $this->session->userdata('branch_id'),
            'module' => 'general-setting',
            'action' => 'General Setting <b>modified</b>',
            'previous_data' => '',
            'performed_at' => date('Y-m-d H:i:s', time())
        );
        $this->currentusermodel->updateLog($log);

        $inv_id = [195,192, 194, 187, 188, 189,190, 124,116,196,197,199,200,201,202,203,160,167,207];
        $non_inv_id = [183, 184];
        if ($data['is_inventory'] == '1') {
            $data1['status'] = 1;
            $data2['status'] = 0;
            
            foreach ($inv_id as $m_id1) {
                $this->db->where('id', $m_id1);
                $this->db->update(tablename('menu'), $data1);
            
            }
            foreach ($non_inv_id as $m_id2) {
                $this->db->where('id', $m_id2);
                $this->db->update(tablename('menu'), $data2);
            }
        }else{
            $data1['status'] = 0;
            $data2['status'] = 1;
            foreach ($inv_id as $m_id1) {
                $this->db->where('id', $m_id1);
                $this->db->update(tablename('menu'), $data1);
            }
            foreach ($non_inv_id as $m_id2) {
                $this->db->where('id', $m_id2);
                $this->db->update(tablename('menu'), $data2);
            }   
        }
        $this->db->where('id', $id);
        $response = $this->db->update(tablename('account_configuration'), $data);
        if ($response) {
            return true;
        } else {
            return false;
        }
        
        // return $this->db->affected_rows();
    }
    
    public function getAllCurrency(){
         $this->db->select('*');
        $this->db->from('currency');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllDateFormat()
    {
        $this->db->select('*');
        $this->db->from('date_format');
        $query = $this->db->get();
        return $query->result();
    }

    public function selected_date_format()
    {
        $this->db->select('date_format_sign');
        $this->db->from('date_format');
        $this->db->join('account_configuration', 'account_configuration.selected_date_format = date_format.id');
        $query = $this->db->get();
        return $query->row();
    }
    
    public function getAllBranch(){
         $this->db->select('id,company_name');
        $this->db->from('account_settings');
        $query = $this->db->get();
        return $query->result();
    }
    
    /*
     * to get the number of branch of current company
     */
    public function getNumberOfBranch() {
        $this->db->select("COUNT(id) as numberOfBranch");
        $this->db->from('account_settings');
        $result = $this->db->get()->row();
        return $result->numberOfBranch;
    }

    public function getAllBankDetailsCompany()
    {
        return $this->db->get('bank_details_company')->result();
    }

    public function saveDefaultBank($bank_id)
    {
        $this->db->update('bank_details_company', array('default_bank' => 0));
        
        $this->db->where('id', $bank_id);
        $this->db->update('bank_details_company', array('default_bank' => 1));
    }

    /*
     * check the default bank for transaction and return the details
     */
    public function getBankDetailsStatus()
    {
        $this->db->where('default_bank', 1);
        return $this->db->get('bank_details_company')->row();
    }
    
    /**
     * get despatch details for listing in despatch details section if get despatch through
     * 
     * @return type object
     */
    public function getAllDespatchDetails(){
        $this->db->select('id,despatch_through');
        $this->db->from('despatch_details');
        $this->db->where('despatch_through !=', '');
        $this->db->group_by('despatch_through');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAccountSettings()
    {
        return $this->db->where('id', $this->session->userdata('branch_id'))->get('account_settings')->row();
    }

    public function saveUserCredentialsForEwayBill($data)
    {
        $this->db->where('id', $this->session->userdata('branch_id'));
        $res = $this->db->update('account_settings', $data);
        return ($res) ? true : false;
    }

    public function deleteAllBatch()
    {
        $this->db->empty_table('batch_opening_stock');
    }

    public function disableGodownFromMenu($status)
    {
        $this->db->where('slug', 'godown');
        $this->db->update('menu', ['status' => $status]);
    }
    
    

}
?>