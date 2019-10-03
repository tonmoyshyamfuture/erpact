<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class dashboardmodel extends CI_Model {

    public function __construct() {

        parent::__construct();
        $this->load->database();
        $this->load->model('front/usermodel', 'currentusermodel');
    }

    public function getSubGroup($group_id_arr) {
        $this->db->select("id");
        $this->db->from('group');
        $this->db->where_in('parent_id', $group_id_arr);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllLedgerId($group_id_arr) {
        $this->db->select("id");
        $this->db->from('ladger');
        $this->db->where_in('group_id', $group_id_arr);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result();
    }

    //get Cr of cash and bank group

    public function getCrBalance($ledger_id_arr, $from_date, $to_date) {
        $this->db->select("SUM(LD.balance) AS cr_balance");
        $this->db->from('ladger_account_detail as LD');
        $this->db->join('entry E', 'E.id = LD.entry_id');
        $this->db->where('E.company_id', $this->session->userdata('branch_id'));
        $this->db->where_in('LD.ladger_id', $ledger_id_arr);
        $this->db->where('LD.account', 'Cr');
        $this->db->where('LD.entry_id !=', '0');
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.create_date >=', $from_date);
        $this->db->where('LD.create_date <=', $to_date);
        $query = $this->db->get();
        return $query->result();
    }

    //get Cr of cash and bank group

    public function getDrBalance($ledger_id_arr, $from_date, $to_date) {
        $this->db->select("SUM(LD.balance) AS dr_balance");
        $this->db->from('ladger_account_detail as LD');
        $this->db->join('entry E', 'E.id = LD.entry_id');
        $this->db->where('E.company_id', $this->session->userdata('branch_id'));
        $this->db->where_in('LD.ladger_id', $ledger_id_arr);
        $this->db->where('LD.account', 'Dr');
        $this->db->where('LD.entry_id !=', '0');
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.create_date >=', $from_date);
        $this->db->where('LD.create_date <=', $to_date);
        $query = $this->db->get();
        return $query->result();
    }

    public function getOpeningBalance($ledger_id_arr, $from_date, $to_date) {
        $this->db->select("SUM(LD.balance) AS opening_balance,LD.account AS type");
        $this->db->from('ladger_account_detail as LD');
        $this->db->join('entry E', 'E.id = LD.entry_id');
        $this->db->where('E.company_id', $this->session->userdata('branch_id'));
        $this->db->where_in('LD.ladger_id', $ledger_id_arr);
        $this->db->where('LD.entry_id =', '0');
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.status', '1');
        $this->db->where('E.create_date >=', $from_date);
        $this->db->where('E.create_date <=', $to_date);
        $query = $this->db->get();
        return $query->result();
    }

    public function getOpeningBalanceForFundFlow($ledger_id_arr, $from_date, $to_date,$type) {
        $this->db->select("SUM(LD.balance) AS opening_balance,LD.account AS type");
        $this->db->from('ladger_account_detail as LD');
        $this->db->where('LD.branch_id', $this->session->userdata('branch_id'));
        $this->db->where_in('LD.ladger_id', $ledger_id_arr);
        $this->db->where('LD.entry_id =', '0');
        $this->db->where('LD.account =', $type);
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.create_date >=', $from_date);
        $this->db->where('LD.create_date <=', $to_date);
        $query = $this->db->get();
        return $query->row();
    }

    //get debit sum by date range
    public function getDebitSumByDate($date, $ledger_id_arr) {
        $from_date = date("Y-m-d", strtotime($date));
        $to_date = date("Y-m-t", strtotime($date));
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        $this->db->select('SUM(LD.balance) AS dr_sum');
        $this->db->from('ladger_account_detail LD');
        $this->db->join('entry E', 'E.id = LD.entry_id');
        $this->db->where('E.company_id', $this->session->userdata('branch_id'));
        $this->db->where_in('LD.ladger_id', $ledger_id_arr);
        $this->db->where('LD.account =', 'Dr');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.create_date <=', $to_date);
        $this->db->where('LD.create_date >=', $from_date);
        $query = $this->db->get();
        return $query->row_array();
    }

    //get credit sum by date range
    public function getCreditSumBYDate($date, $ledger_id_arr) {
        $from_date = date("Y-m-d", strtotime($date));
        $to_date = date("Y-m-t", strtotime($date));
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        $this->db->select('SUM(LD.balance) AS cr_sum');
        $this->db->from('ladger_account_detail LD');
        $this->db->join('entry E', 'E.id = LD.entry_id');
        $this->db->where('E.company_id', $this->session->userdata('branch_id'));

        $this->db->where_in('LD.ladger_id', $ledger_id_arr);
        $this->db->where('LD.account =', 'Cr');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.create_date <=', $to_date);
        $this->db->where('LD.create_date >=', $from_date);
        $query = $this->db->get();
        return $query->row_array();
    }

    //ger DR Sum by
    public function getDrSumByDate($ledger_id_arr, $from_date, $to_date) {
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        $this->db->select('SUM(LD.balance) AS dr_sum');
        $this->db->from('ladger_account_detail LD');
        $this->db->join('entry E', 'E.id = LD.entry_id');
        $this->db->where('E.company_id', $this->session->userdata('branch_id'));
        $this->db->where_in('LD.ladger_id', $ledger_id_arr);
        $this->db->where('LD.account =', 'Dr');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.create_date <=', $to_date);
        $this->db->where('LD.create_date >=', $from_date);
        $query = $this->db->get();
        return $query->row_array();
    }

    //get CR Sum

    public function getCrSumBYDate($ledger_id_arr, $from_date, $to_date) {
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        $this->db->select('SUM(LD.balance) AS cr_sum');
        $this->db->from('ladger_account_detail LD');
        $this->db->join('entry E', 'E.id = LD.entry_id');
        $this->db->where('E.company_id', $this->session->userdata('branch_id'));
        $this->db->where_in('LD.ladger_id', $ledger_id_arr);
        $this->db->where('LD.account =', 'Cr');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.create_date <=', $to_date);
        $this->db->where('LD.create_date >=', $from_date);
        $query = $this->db->get();
        return $query->row_array();
    }

    //get all watch list ledger
    public function getAllWatchlistLedger() {
        $this->db->select("l.id as id,l.ladger_name as ladger_name, w.account_type as account_type, w.group_ledger_id as group_ledger_id");
        $this->db->from('ladger as l');
        // somnath - joining for user specific watchlist
        $this->db->join('watchlist_access as w', 'l.id = w.group_ledger_id');
        $this->db->where('w.account_type', 2);
        $this->db->where('w.user_id', $this->session->userdata('admin_uid'));

        $this->db->where('l.watch_list_status', '1');
        $this->db->where('l.watch_list_status', '1');
        $this->db->where('l.deleted', '0');
        $this->db->where('l.status', '1');
        $query = $this->db->get();
        return $query->result();
    }

    //get all watch list Group
    public function getAllWatchlistGroup() {
        $this->db->select("g.id as id,g.group_name as group_name, w.account_type as account_type, w.group_ledger_id as group_ledger_id");
        $this->db->from('group g');
        // somnath - joining for user specific watchlist
        $this->db->join('watchlist_access as w', 'g.id = w.group_ledger_id');
        $this->db->where('w.account_type', 1);
        $this->db->where('w.user_id', $this->session->userdata('admin_uid'));

        $this->db->where('watch_list_status', '1');
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result();
    }

    //get bill amount
    public function getBillAmount($account, $ledger_id_arr, $finans_start_date, $to_date) {
        $this->db->select("SUM(BD.bill_amount) as total");
        $this->db->from('billwish_details BD');
         $this->db->join('entry E', 'E.id = BD.entry_id');
        if ($ledger_id_arr) {
            $this->db->where_in('BD.ledger_id', $ledger_id_arr);
        }
        $this->db->where('E.company_id', $this->session->userdata('branch_id'));
        $this->db->where('BD.dr_cr', $account);
        $this->db->where('BD.deleted', 0);
        $this->db->where('BD.created_date <=', $to_date);
        $this->db->where('BD.created_date >=', $finans_start_date);
        $query = $this->db->get();
        return $query->row();
    }

    //get overdue
    public function getOverdueBill($ledger_id_arr, $finans_start_date, $to_date, $overdue_date) {
        $today = date("Y-m-d H:i:s");
        $this->db->select("BD.bill_name");
        $this->db->from('billwish_details BD');
        $this->db->join('entry E', 'E.id = BD.entry_id');
        if ($ledger_id_arr) {
            $this->db->where_in('BD.ledger_id', $ledger_id_arr);
        }
        $this->db->where('E.company_id', $this->session->userdata('branch_id'));
        $this->db->where('BD.dr_cr', 'Dr');
        $this->db->where('BD.deleted', 0);
        $this->db->where('BD.credit_date <', $overdue_date);
        $this->db->where('BD.created_date <=', $to_date);
        $this->db->where('BD.created_date >=', $finans_start_date);
        $this->db->group_by('BD.bill_name');
        $query = $this->db->get();
        return $query->result();
    }

    public function getOverdueBillAmount($account, $bill_name, $ledger_id_arr, $finans_start_date, $to_date) {
        $this->db->select("SUM(BD.bill_amount) as total");
        $this->db->from('billwish_details BD');
         $this->db->join('entry E', 'E.id = BD.entry_id');
        $this->db->where_in('BD.ledger_id', $ledger_id_arr);
        $this->db->where('E.company_id', $this->session->userdata('branch_id'));
        $this->db->where('BD.dr_cr', $account);
        $this->db->where('BD.bill_name', $bill_name);
        $this->db->where('BD.deleted', 0);
        $this->db->where('BD.created_date <=', $to_date);
        $this->db->where('BD.created_date >=', $finans_start_date);
        $query = $this->db->get();
        return $query->row();
    }

    public function getPriceFormat() {
        $this->db->select("price_format");
        $this->db->from('account_configuration');
        $this->db->where('id', 1);
        $query = $this->db->get();
        return $query->row();
    }

    public function getCompanyDetails() {
        $this->db->select('account_settings.*,states.name as state_name,states.state_code as state_code,countries.name as country_name');
        $this->db->from('account_settings');
        $this->db->join('states', 'states.id = account_settings.state_id');
        $this->db->join('countries', 'countries.id = account_settings.country_id');
        $this->db->where('account_settings.id', 1);
        $query = $this->db->get();
        return $query->row();
    }

    public function getCompanyBranch($user_id) {
        $this->db->select("account_settings.*");
        $this->db->from('user_branch');
        $this->db->join('account_settings', 'account_settings.id = user_branch.company_id');
        $this->db->where('user_branch.user_id', $user_id);
        $this->db->where('user_branch.status', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllCountries() {
        $query = $this->db->select('*')->from(tablename('countries'))->get();
        return $query->result();
    }

    public function saveData($data) {
        $log = array(
            'user_id' => $this->session->userdata('admin_uid'),
            'branch_id' => $this->session->userdata('branch_id'),
            'module' => 'account-setting',
            'action' => '`'.$data['company_name'].'` <b>added</b>',
            'previous_data' => '',
            'performed_at' => date('Y-m-d H:i:s', time())
        );
        $this->currentusermodel->updateLog($log);

        $this->db->insert('account_settings', $data);
        return $this->db->insert_id();
    }

    public function addBranchUser($data) {
        return $this->db->insert('user_branch', $data);
    }

    public function addBranchUserAccess($data) {
        return $this->db->insert('user_branch_access', $data);
    }

    public function addBranchLedger($data) {
        $this->db->insert('ladger', $data);
        $ledger_id = $this->db->insert_id();
        $branchs = $this->db->select("id")->get('account_settings');
//        if no opening balance concept in branch
//        $ladger_account_detail = array(
//            'ladger_id' => $ledger_id,
//            'account' => 'Dr',
//            'balance' => '0',
//            'current_opening_balance' => '0',
//            'current_closing_balance' => '0',
//            'create_date' => date("Y-m-d H:i:s")
//        );
//        $this->db->insert('ladger_account_detail', $ladger_account_detail);
//        if opening balance concept in branch
        $ledger_details = array();
        foreach($branchs->result_array() AS $key=>$branch){
            $ledger_details[$key]['branch_id']= $branch['id'];
            $ledger_details[$key]['ladger_id']= $ledger_id;
            $ledger_details[$key]['account']= 'Dr';
            $ledger_details[$key]['balance']= 0.00;
            $ledger_details[$key]['current_opening_balance']= 0.00;
            $ledger_details[$key]['current_closing_balance']= 0.00;
            $ledger_details[$key]['create_date']= date("Y-m-d H:i:s");
        }
        $this->db->insert_batch('ladger_account_detail', $ledger_details);
        return $ledger_id;
    }

    public function getLedgerCodeStatus() {
        $this->db->select('ledger_code_status');
        $query = $this->db->get('account_configuration');
        return $query->row_array();
    }

    public function updateLedgerCode($data = NULL, $last_id = NULL) {
        $this->db->where('id', $last_id);
        $this->db->update('ladger', $data);
    }

    /*
     * to get the states of respective countries
     */
    public function getStateByCountryId($country_id) {
        $this->db->select('*');
        $this->db->from('states');
        $this->db->where('country_id =', $country_id);
        $query = $this->db->get();
        return $query->result();
    }

    /*
     * get user details
     */
    public function getUserProfile($user_id) {
        $this->db->where('id', $user_id);
        return $this->db->get('admins')->row();
    }
    /*
     * update the last login
     */
    public function updateLastLogin($user, $user_id) {
        $this->db->where('id', $user_id);
        $this->db->update('admins', $user);
    }

    public function getLedgerId() {
        $this->db->select('id,account_type');
        $query = $this->db->get('ladger');
        return $query->result_array();
    }

    public function setLedgerAsBranch($ledger_details){
        $this->db->insert_batch('ladger_account_detail', $ledger_details);
    }

    public function newRowInsertForOpeningCost($opening_cost){
        $this->db->insert('opening_cost', $opening_cost);
    }

    public function getAllCompanyByUserId($user_id) {
        $this->db2 = $this->load->database('db2', TRUE);
        $this->db2->select('company_details.*,user_relation.id as r_id,user_relation.user_id as u_id');
        $this->db2->from('user_relation');
        $this->db2->join('company_details', 'company_details.id = user_relation.company_id');
        $this->db2->where('user_relation.user_id', $user_id);
        $this->db2->where('user_relation.status', '1');
        $query = $this->db2->get();
        return $query->result();
    }

    public function getSaasId($user_id) {
        // $this->db->where('id', $user_id);
        // $user = $this->db->get('admins')->row();
        // return $user->sass_user_id;
        return 10;
    }

    public function searchCompanyByUserId($user_id, $company_name) {
        $this->db2 = $this->load->database('db2', TRUE);
        $this->db2->select('company_details.*,user_relation.id as r_id,user_relation.user_id as u_id');
        $this->db2->from('user_relation');
        $this->db2->join('company_details', 'company_details.id = user_relation.company_id');
        $this->db2->where('user_relation.user_id', $user_id);
        $this->db2->where('user_relation.status', '1');
        $this->db2->like('company_details.company_name', $company_name);
        $query = $this->db2->get();
        return $query->result();
    }

    /*
     * get all groups
     */
    public function getAllGroups(){
        $query = $this->db->query("SELECT G.*, PG.group_name as parent_group_name,PG.group_code as parent_group_code FROM pb_group AS G LEFT JOIN pb_group AS PG ON G.parent_id = PG.id WHERE G.status = '1' AND G.deleted = '0' AND G.watch_list_status = '0' ORDER BY G.parent_id");
        return $query->result();
    }

    /*
     * get all ledger
     */
    public function getAllLedger() {
        $this->db->select("group.group_name,group.id as group_table_id, ladger.*,(SELECT COUNT(id) FROM pb_ladger_account_detail WHERE  status = '1' AND deleted = '0' AND entry_id != 0 AND ladger_id = pb_ladger.id) as no_of_ledger");
        $this->db->from('ladger');
        $this->db->join('group', 'group.id = ladger.group_id', 'left');
        $this->db->where('branch_id !=', $this->session->userdata('branch_id'));
        $this->db->where('ladger.status', 1);
        $this->db->where('ladger.deleted', 0);
        $this->db->where('ladger.watch_list_status', 0);
        $this->db->order_by("group.id", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    /*
     * to save watchlist from dashboard
     */
    public function saveWatch_list($id,$type) {
        if($type == 1){
            $this->db->where('id', $id);
            $this->db->update('group', array('watch_list_status' => 1));
        }else if($type == 2){
            $this->db->where('id', $id);
            $this->db->update('ladger', array('watch_list_status' => 1));
        }
        $data = array();
        $data['account_type'] = $type; // 1 = group, ledger = 2
        $data['group_ledger_id'] = $id;
        $data['user_id'] = $this->session->userdata('admin_uid');
        $data['created_date'] = date('Y-m-d h:i:s', time());
        $data['modified_date'] = date('Y-m-d h:i:s', time());
        $this->db->insert('watchlist_access', $data);
    }

    /*
     * delete watchlist from dashboard
     */
    public function delete_watchlist($account_type, $id) {
        if($account_type == 1) {
             $this->db->where('id', $id);
            $this->db->update('group', array('watch_list_status' => 0));
        } else {
            $this->db->where('id', $id);
            $this->db->update('ladger', array('watch_list_status' => 1));
        }
        $this->db->where(array(
            'account_type' => $account_type,
            'group_ledger_id' => $id,
            'user_id' => $this->session->userdata('admin_uid')
        ));
        $this->db->delete('watchlist_access');
    }

    /*
     *
     */
    public function getCurrentDbSize($dbname)
    {
        // $query = $this->db->query("SELECT table_schema 'db_name', SUM( data_length + index_length) / 1024 / 1024 'db_size' FROM information_schema.TABLES WHERE table_schema='".$dbname."' GROUP BY table_schema");
        // $result = $query->row();
        // return $result->db_size;
        return 123;
    }

    /*
     * get the current project's disk space
     */
    function folderSize ($dir=FCPATH)
    {
        $size = 0;
        foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : folderSize($each);
        }
        return $size/(1024*1024);
    }

    public function getReportMenu()
    {
        $this->db->select('m1.id as parent_id, m1.label as parent_name, m2.id child_id, m2.label as child_name, m2.url as url');
        $this->db->from('menu as  m1');
        $this->db->join('menu as m2', 'm2.parentid = m1.id', 'left');
        $this->db->where(array('m1.group_menu' => 11, 'm1.status' => 1, 'm2.url != ' => ''));
        $query = $this->db->get();
        return $query->result();
    }

    /*
     * search report
     */
    public function searchReportMenu($search_value)
    {
        $this->db->select("label, url");
        $this->db->from('menu');
        $this->db->like('label', $search_value);
        $this->db->where(array('group_menu' => 11, 'status' => 1, 'parentid !=' => 0, 'url != ' => ''));
        $query = $this->db->get();
        return $query->result();
    }

    // all list of hotkeys
    public function getAllHotKeys()
    {
        $this->db2 = $this->load->database('db2', TRUE  );
        return $this->db2->get('hot_keys')->result();
    }

    /*
     * delete notification
     */
    public function deleteNotification($id = "")
    {
        $this->db->where('id', $id);
        return $this->db->update('branch_entry_notification', array('status' => 0));
    }

    /*
     * get all deleted entries
     */
    public function getAllTrashEntries()
    {
        $this->db->where(['deleted' => 1, 'permanent_delete' => 0, 'company_id' => $this->session->userdata('branch_id')]);
        return $this->db->get('entry')->result();
    }

    /*
     * delete entry permanently
     */
    public function permanentlyDeleteEntry($entry_id)
    {
        $this->db->where('id', $entry_id);
        return $this->db->update('entry', array('permanent_delete' => 1));
    }

    /*
     * restore entry
     */
    public function restoreEntry($entry_id)
    {
        $this->db->where('id', $entry_id);
        return $this->db->update('entry', array('deleted' => 0, 'permanent_delete' => 0));
    }

    public function getMaxEntryDate()
    {
        $this->db->select_max('create_date');
        return $this->db->get('entry')->row();
    }

    public function getCurrentSetting()
    {
        return $this->db->get_where('account_configuration', ['id' => 1])->row();
    }

    public function getDashboardSetting()
    {
        $this->db->where('branch_id', $this->session->userdata('branch_id'));
        $setting =  $this->db->get('dashboard_setting')->row();
        return $setting;
    }

}
