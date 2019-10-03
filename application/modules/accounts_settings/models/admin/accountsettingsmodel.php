<?php

class accountsettingsmodel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('front/usermodel', 'currentusermodel');
    }

    public function loadAll($id = NULL) {

        $query = $this->db->select('*')
                ->from(tablename('account_settings'))
                ->get();
        return $row = $query->first_row();
    }

    public function getAllCurrency() {

        $query = $this->db->select('*')->from(tablename('currency'))->get();
        return $query->result();
    }

    public function getAllTimezone() {

        $query = $this->db->select('*')->from(tablename('timezone'))->get();
        return $query->result();
    }

    public function getAllState() {

        $query = $this->db->select('*')->from(tablename('states'))->get();
        return $query->result();
    }

    public function getAllCountries() {
        $query = $this->db->select('*')->from(tablename('countries'))->get();
        return $query->result();
    }

    public function modifyData($data, $id) {
        $log = array(
            'user_id' => $this->session->userdata('admin_uid'),
            'branch_id' => $this->session->userdata('branch_id'),
            'module' => 'account-setting',
            'action' => 'Account settings <b>modified</b>',
            'previous_data' => '',
            'performed_at' => date('Y-m-d H:i:s', time())
        );
        $this->currentusermodel->updateLog($log);

        $this->db->where('id', $id);
        $this->db->update(tablename('account_settings'), $data);
        return $this->db->affected_rows();
    }
    
    /*
     * get current logo
     */
    public function getCurrentLogo() {
        $query = $this->db->select('logo')
                ->from(tablename('account_settings'))
                ->where('id', 1)
                ->get()
                ->row();
        return $query->logo;
    }


    public function getStandardFormatData($id = NULL) {

        $query = $this->db->select('*')
                ->from(tablename('account_standard_format'))
                ->get();
        return $row = $query->first_row();
    }

    public function modifyStandardFormatData($data, $id) {

        $this->db->where('id', $id);
        $this->db->update(tablename('account_standard_format'), $data);
        // echo $this->db->last_query();die();
        return $this->db->affected_rows();
    }

    public function get_ledger_tracking() {
        $query = $this->db->query('SELECT distinct(LD.ladger_id),L.id,L.ladger_name,L.group_id,L.ledger_code,G.group_name, (SELECT count(ladger_id) FROM ' . tablename('ladger_account_detail') . ' WHERE ladger_id = LD.ladger_id AND status = "1" AND deleted = "0") AS total_use FROM ' . tablename('group AS G') . ' ,' . tablename('ladger_account_detail AS LD') . ' , ' . tablename('ladger AS L') . ' 
WHERE LD.ladger_id = L.id AND LD.status = "1" AND LD.deleted = "0" AND G.id = L.group_id AND L.tracking_status = "1"');
        return $query->result_array();
    }

    public function getGroups($id = NULL) {

        $query = $this->db->select('*')
                ->from(tablename('group'))
                ->get();
        return $row = $query->result_array();
    }

    public function getEntry($id) {
        $query = $this->db->query('SELECT L.ladger_name ,LD.entry_id FROM ' . tablename('ladger_account_detail AS LD') . ' , ' . tablename('ladger AS L') . ' WHERE LD.ladger_id = L.id AND LD.ladger_id = ' . $id . ' AND LD.status = "1" AND LD.deleted = "0"');
        return $query->result_array();
    }

    public function getLadgerByEntryId($entry_id, $ladger_id) {
        $query = $this->db->query('SELECT L.ladger_name ,L.id FROM ' . tablename('ladger_account_detail AS LD') . ' , ' . tablename('ladger AS L') . ' WHERE LD.ladger_id = L.id AND LD.entry_id = ' . $entry_id . ' AND LD.ladger_id != ' . $ladger_id);
        return $query->row_array();
    }
    
    public function modifySaasData($data){
        $dbname     = $this->db->database;
        $len1       = strlen(SUB_DB_PREFIX);
        $dbname_1   = substr($dbname,$len1);
        
        $mysqli = new mysqli(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD);
        if(mysqli_select_db ($mysqli,SAAS_DB_NAME)){
            
            $sql  = "UPDATE saas_company_details SET ";
            $sql .= " `company_name` = '".$data['company_name']."', ";
            $sql .= " `mailing_name` = '".$data['mailing_name']."', ";
            $sql .= " `email` = '".$data['email']."', ";
            $sql .= " `country` = '".$data['country']."', ";
            $sql .= " `state_id` = '".$data['state_id']."', ";
            $sql .= " `appt_number` = '".$data['appt_number']."', ";
            $sql .= " `street_address` = '".$data['street_address']."', ";
            $sql .= " `city_name` = '".$data['city_name']."', ";
            $sql .= " `zip_code` = '".$data['zip_code']."', ";
            $sql .= " `telephone` = '".$data['telephone']."', ";
            $sql .= " `mobile` = '".$data['mobile']."', ";
            $sql .= " `vat` = '".$data['vat']."', ";
            $sql .= " `cst` = '".$data['cst']."', ";
            $sql .= " `gst` = '".$data['gst']."', ";
            $sql .= " `pan` = '".$data['pan']."', ";
            $sql .= " `cin` = '".$data['cin']."', ";
            $sql .= " `service_tax` = '".$data['service_tax']."', ";
            $sql .= " `tan` = '".$data['tan']."' ";
            $sql .= "WHERE comp_db_name = '".$dbname_1."'";
            $mysqli->query($sql);
        }
        
        /*echo $dbname.'<br>';
        echo $dbname_1.'<br>';;
        echo $len1.'<br>';;
        echo '<pre>';print_r($data);exit;*/
    }

    public function getDashboardSetting()
    {
        $this->db->where('branch_id', $this->session->userdata('branch_id'));
        $setting =  $this->db->get('dashboard_setting')->row();
        return $setting;
    }

    public function modifyDashboardSetting($data = array(), $id = NULL) {
        $log = array(
            'user_id' => $this->session->userdata('admin_uid'),
            'branch_id' => $this->session->userdata('branch_id'),
            'module' => 'dashboard-setting',
            'action' => '`Dashboard-setting` <b>modified</b>',
            'previous_data' => '',
            'performed_at' => date('Y-m-d H:i:s', time())
        );
        $this->currentusermodel->updateLog($log);
        if ($id) {
            $this->db->where('id', $id);
            $this->db->update(tablename('dashboard_setting'), $data);
            return true;
        } else {
            $this->db->insert(tablename('dashboard_setting'), $data);
            return true;
        }
    }
}
