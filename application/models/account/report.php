<?php

class report extends CI_Model {

    public function __construct() {

        parent::__construct();
        $this->load->database();
    }

    public function getAllTrialBalance($where) {
        $this->db->select('group.*, ladger.*, ladger_account_detail.*');
        $this->db->from('group');
        $this->db->join('ladger', 'ladger.group_id = group.id', 'left');
        $this->db->join('ladger_account_detail', 'ladger_account_detail.ladger_id = ladger.id', 'left');
        $this->db->join('entry', 'entry.id = ladger_account_detail.entry_id', 'left');
        $this->db->where_in('entry.company_id',$this->session->userdata('selected_branch'));
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getParentGroup($where) {
        $this->db->select('group.*');
        $this->db->from('group');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function allTransaction($where) {
        $this->db->select('entry.*, ladger_account_detail.current_opening_balance, ladger_account_detail.current_closing_balance, ladger_account_detail.ladger_id, ladger_account_detail.account, ladger_account_detail.balance, ladger.group_id, ladger.ladger_name, ladger.opening_balance, ladger.current_balance, ladger.last_opening_balance, group.group_name, group.id, group.parent_id, entry_type.type');
        $this->db->from('entry');
        $this->db->join('ladger_account_detail', 'ladger_account_detail.entry_id = entry.id', 'left');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        $this->db->join('group', 'group.id = ladger.group_id', 'left');
        $this->db->join('entry_type', 'entry.entry_type_id = entry_type.id', 'left');

        $this->db->where_in('entry.company_id',$this->session->userdata('selected_branch'));
        $this->db->where($where);
        $this->db->order_by("entry.create_date", 'asc');
        $query = $this->db->get();
        //echo $this->db->last_query();die();
        return $query->result_array();
    }

    public function allEntry($where) {
        $this->db->select('ladger.ladger_name, ladger.group_id, ladger.opening_balance, ladger.current_balance, ladger_account_detail.*');
        $this->db->from('ladger_account_detail');

        $this->db->join('entry', 'entry.id = ladger_account_detail.entry_id', 'left');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        //$this->db->join('group', 'group.id = ladger.group_id', 'left');
        $this->db->where_in('entry.company_id',$this->session->userdata('selected_branch'));
        $this->db->where($where);
        $query = $this->db->get();
//        echo $this->db->last_query();die(); 
        return $query->result_array();
    }

    public function allEntryBS($where) {
        $this->db->select('(SELECT SUM(balance) FROM ' . tablename('ladger_account_detail') . ' WHERE account = "Dr" AND ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0") AS balance_dr,(SELECT SUM(balance) FROM ' . tablename('ladger_account_detail') . ' WHERE account = "Cr" AND ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0") AS balance_cr, ' . tablename('ladger') . '.ladger_name, ' . tablename('ladger') . '.group_id, ' . tablename('ladger') . '.opening_balance, ' . tablename('ladger') . '.current_balance, ' . tablename('ladger_account_detail') . '.*');
        $this->db->from('ladger_account_detail');

        $this->db->join('entry', 'entry.id = ladger_account_detail.entry_id', 'left');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        $this->db->where_in('entry.company_id',$this->session->userdata('selected_branch'));
        $this->db->where($where);
        $query = $this->db->get();
//        echo $this->db->last_query();die(); 
        return $query->result_array();
    }

    public function allEntryPL($where) {
        $this->db->select('(SELECT SUM(balance) FROM ' . tablename('ladger_account_detail') . ' WHERE account = "Dr" AND ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id != 0) AS balance_dr,(SELECT SUM(balance) FROM ' . tablename('ladger_account_detail') . ' WHERE account = "Cr" AND ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id != 0) AS balance_cr,(SELECT balance FROM ' . tablename('ladger_account_detail') . ' WHERE ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id = 0) AS op_balance,(SELECT account FROM ' . tablename('ladger_account_detail') . ' WHERE ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id = 0) AS op_account, ' . tablename('ladger') . '.ladger_name, ' . tablename('ladger') . '.group_id, ' . tablename('ladger') . '.opening_balance, ' . tablename('ladger') . '.current_balance, ' . tablename('ladger_account_detail') . '.*');
        $this->db->from('ladger_account_detail');

        $this->db->join('entry', 'entry.id = ladger_account_detail.entry_id', 'left');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        $this->db->group_by('ladger.id');
        $this->db->where_in('entry.company_id',$this->session->userdata('selected_branch'));
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function allEntry_date($where) {
        $this->db->select('SUM(ladger_account_detail.balance) AS total_balance, ladger.ladger_name, ladger.group_id, ladger.opening_balance, ladger.current_balance, ladger_account_detail.*');
        $this->db->from('ladger_account_detail');

        $this->db->join('entry', 'entry.id = ladger_account_detail.entry_id', 'left');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        $this->db->group_by('ladger.id');
        //$this->db->join('group', 'group.id = ladger.group_id', 'left');
        $this->db->where_in('entry.company_id',$this->session->userdata('selected_branch'));
        $this->db->where($where);
        $query = $this->db->get();
//        echo $this->db->last_query();die(); 
        return $query->result_array();
    }

    public function getAllLedgerName($where) {
        $this->db->select('ladger.id, ladger.ladger_name, ladger.parent_group_id');
        $this->db->from('ladger');
        $this->db->where($where);
        //$this->db->order_by('group_name', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function trialBalance($where) {
        $this->db->select('group.group_name, ladger.*');
        $this->db->from('ladger');
        $this->db->join('group', 'group.id = ladger.group_id', 'left');
        $this->db->where($where);
        $this->db->order_by('group_name', 'asc');
        $query = $this->db->get();
//         echo $this->db->last_query();die(); 
        return $query->result_array();
    }

    public function getGrandParentid($where) {
        $this->db->select('*');
        $this->db->from('group');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function goGrandParent($where) {
        $this->db->select('*');
        $this->db->from('group');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getGroups($where) {
        $this->db->select('group.*, ladger.ladger_name, ladger.current_balance');
        $this->db->from('group');
        $this->db->join('ladger', 'ladger.group_id = group.id', 'left');
        $this->db->where($where);
        $this->db->order_by('group_name', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function allEntrySelectByDate($where) {
        $this->db->select('ladger.ladger_name, ladger.group_id, ladger_account_detail.*');
        $this->db->from('ladger_account_detail');

        $this->db->join('entry', 'entry.id = ladger_account_detail.entry_id', 'left');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        $this->db->where_in('entry.company_id',$this->session->userdata('selected_branch'));
        $this->db->where($where);
        $query = $this->db->get();
    }

    public function getOpeningBalance($where) {
        $this->db->select('ladger.*');
        $this->db->from('ladger');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLastOpeningBalance($where) {
        $this->db->select('entry.*, ladger_account_detail.current_closing_balance');
        $this->db->from('entry');
        $this->db->join('ladger_account_detail', 'ladger_account_detail.entry_id = entry.id', 'left');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');

        $this->db->where_in('entry.company_id',$this->session->userdata('selected_branch'));
        $this->db->where($where);
        $this->db->order_by("ladger_account_detail.create_date", 'DESC');
        $query = $this->db->get('', 1);
        return $query->result_array();
    }

    public function getledgerDetailsById($ledger_id) {
        $this->db->select('ET.type,E.entry_no,E.ledger_ids_by_accounts,LD.ladger_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance,LD.account,LD.balance,LD.modified_date');
        $this->db->from('ladger_account_detail AS LD');
        $this->db->join('ladger AS L', 'L.id = LD.ladger_id');
        $this->db->join('entry AS E', 'E.id = LD.entry_id');
        $this->db->join('entry_type AS ET', 'ET.id = E.entry_type_id');
        $this->db->where('LD.ladger_id', $ledger_id);
        $this->db->where_in('E.company_id',$this->session->userdata('selected_branch'));
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.status', '1');
        $this->db->order_by('LD.modified_date', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getOpeningBalanceById($ledger_id) {
        $this->db->select('L.ladger_name,L.account_type,L.ledger_code,L.opening_balance,L.id');
        $this->db->from('ladger AS L');
        $this->db->where('L.id', $ledger_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getledgerId($ledger) {
        $record = $this->db->select('id')
                ->from('ladger')
                ->like('ladger_name', $ledger)
                ->get();
        return $record->row_array();
    }

    public function getAllGroupsLevelOne($income_id, $expenses_id, $assets_id, $liabilities_id) {
        $this->db->select('id,group_name,group_code,parent_id');
        $this->db->from('group');
        $this->db->or_where('parent_id', $income_id);
        $this->db->or_where('parent_id', $expenses_id);
        $this->db->or_where('parent_id', $assets_id);
        $this->db->or_where('parent_id', $liabilities_id);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllGroupsById($group_id) {
        $this->db->select('id,group_name,group_code,parent_id');
        $this->db->from('group');
        $this->db->where('parent_id', $group_id);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result_array();
    }

    //get all group between date range---@asit
    public function getAllGroupsByIdByDate($group_id, $from_date, $to_date) {
        $this->db->select('id,group_name,group_code,parent_id');
        $this->db->from('group');
        $this->db->where('parent_id', $group_id);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $this->db->where('create_date <=', $to_date);
        $this->db->where('create_date >=', $from_date);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllLedgerByGroupId($group_id) {
        $query = $this->db->query("SELECT L.id,L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = 'Cr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0) AS cr_balance,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = 'Dr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0) AS dr_balance
                FROM  pb_ladger AS L WHERE L.status = '1' AND L.deleted = '0' AND L.group_id = " . $group_id);

        return $query->result_array();
    }

    //get all ledger by group id between date--@Asit
    public function getAllLedgerByGroupIdByDate($group_id, $from_date, $to_date) {
         $this->db->select('L.id,L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = "Cr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND LD.create_date >=' . "'$from_date'" . ' AND LD.create_date <=' . "'$to_date'" . ') AS cr_balance,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = "Dr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND LD.create_date >=' . "'$from_date'" . ' AND LD.create_date <=' . "'$to_date'" . ') AS dr_balance');
        $this->db->from('ladger_account_detail AS LD');
        $this->db->join('entry AS E', 'E.id = LD.entry_id');
        $this->db->join('ladger AS L', 'L.id = LD.ladger_id');
        $this->db->where_in('E.company_id',$this->session->userdata('selected_branch'));
        $this->db->where('L.group_id', $group_id);
        $this->db->where('L.deleted', '0');
        $this->db->where('L.status', '1');
       // $this->db->where('LD.create_date >=', $from_date);
       // $this->db->where('LD.create_date <=', $to_date); 
        $this->db->group_by('L.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
     public function getAllLedgerByGroupIdByDate_bk_4_1_2017($group_id, $from_date, $to_date) {
          $between = '';
           $this->db->select('L.id,L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance,SUM(CASE WHEN LD.account = "Cr" AND LD.entry_id != 0 AND LD.deleted = 0 AND LD.status = 1 THEN LD.balance ELSE 0 END) AS cr_balance,SUM(CASE WHEN LD.account = "Dr" AND LD.entry_id != 0 AND LD.deleted = 0 AND LD.status = 1 THEN LD.balance ELSE 0 END) AS dr_balance');
        if ($from_date && $to_date) {
            $between.=" AND LD.create_date >= " . $from_date . " AND LD.create_date <= " . $to_date;
        }
        $query = $this->db->query("SELECT L.id,L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = 'Cr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0) AS cr_balance,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = 'Dr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0) AS dr_balance
                FROM  pb_ladger AS L WHERE L.status = '1' AND L.deleted = '0' AND L.group_id = " . $group_id);
      
        return $query->result_array();
    }

    public function getOpeningBalanceByGroupId($group_id) {

        $query = $this->db->query("SELECT L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance 
                FROM  pb_ladger AS L WHERE L.status = '1' AND L.deleted = '0' AND L.group_id = " . $group_id);
        return $query->result_array();
    }

    public function getOpeningBalanceByGroupIdByDate($group_id, $from_date, $to_date) {

        $this->db->select('L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance');
        $this->db->from('ladger AS L');
        $this->db->where('L.group_id', $group_id);
        $this->db->where('L.status', '1');
        $this->db->where('L.deleted', '0');
        $this->db->where('L.created_date <=', $to_date);
        $this->db->where('L.created_date >=', $from_date);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getTreeChild($id) {
        $query = $this->db->query("SELECT * FROM tree WHERE p_id = " . $id);
        return $query->result_array();
    }

    public function getChildIdParentId($parent_id) {
        $query = $this->db->query("SELECT id FROM pb_group WHERE status = '1' AND deleted = '0' AND parent_id =" . $parent_id);
        return $query->result_array();
    }

    public function getChildIdParentIdByDate($parent_id, $from_date, $to_date) {
        $this->db->select('id');
        $this->db->from('pb_group');
        $this->db->where('parent_id', $parent_id);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $this->db->where('create_date <=', $to_date);
        $this->db->where('create_date >=', $from_date);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getDrBalanceByGroupId($group_id) {
         $selected_branch_str=$this->session->userdata("selected_branch_str"); 
        $query = $this->db->query("SELECT L.id,L.group_id,L.ladger_name,L.ledger_code,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD INNER JOIN pb_entry AS E ON E.id=LD.entry_id WHERE LD.ladger_id = L.id AND LD.account = 'Dr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND E.company_id IN($selected_branch_str)) AS dr_balance
                FROM  pb_ladger AS L WHERE L.status = '1' AND L.deleted = '0' AND L.group_id = " . $group_id);

        return $query->result_array();
    }

    //get DR Balence by date range
    public function getDrBalanceByGroupIdByDate($group_id, $from_date, $to_date) {
        
        $this->db->select('L.id,L.group_id,L.ladger_name,L.ledger_code,SUM(LD.balance) as dr_balance');
        $this->db->from('ladger_account_detail AS LD');
        $this->db->join('entry AS E', 'E.id = LD.entry_id');
        $this->db->join('ladger AS L', 'L.id = LD.ladger_id');
        $this->db->where_in('E.company_id',$this->session->userdata('selected_branch'));
        $this->db->where('L.group_id', $group_id);
        $this->db->where('LD.account =', 'Dr');
        $this->db->where('LD.entry_id !=', '0');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.create_date <=', $to_date);
        $this->db->where('LD.create_date >=', $from_date);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function getCrBalanceByGroupId($group_id) {
        $query = $this->db->query("SELECT L.id,L.group_id,L.ladger_name,L.ledger_code,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = 'Cr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0) AS cr_balance
                FROM  pb_ladger AS L WHERE L.status = '1' AND L.deleted = '0' AND L.group_id = " . $group_id);

        return $query->result_array();
    }

    //get CR balence by date range--@asit
    public function getCrBalanceByGroupIdByDate($group_id, $from_date, $to_date) {
        $this->db->select('L.id,L.group_id,L.ladger_name,L.ledger_code,SUM(LD.balance) as cr_balance');
        $this->db->from('ladger_account_detail AS LD');
        $this->db->join('entry AS E', 'E.id = LD.entry_id','left');
        $this->db->join('ladger AS L', 'L.id = LD.ladger_id');
        $this->db->where_in('E.company_id',$this->session->userdata('selected_branch'));
        $this->db->where('L.group_id', $group_id);
        $this->db->where('LD.account =', 'Cr');
        $this->db->where('LD.entry_id !=', '0');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.create_date <=', $to_date);
        $this->db->where('LD.create_date >=', $from_date);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function getLedgerDetailsByGroupId($group_id) {
        $query = $this->db->query("SELECT G.group_name,L.id,L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = 'Cr' AND LD.deleted = 0 AND LD.status = 1) AS cr_balance,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = 'Dr' AND LD.deleted = 0 AND LD.status = 1) AS dr_balance
                FROM pb_group AS G, pb_ladger AS L WHERE G.id = L.group_id AND L.group_id = " . $group_id);
        return $query->result_array();
    }

    public function allEntryType($where) {
        $this->db->select('entry_type.id, entry_type.type');
        $this->db->from('entry_type');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getEntryById($where) {
         $this->db->select('entry.*,entry_type.type');
        $this->db->from('entry');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getEntryDetailsById($where) {
         $this->db->select('entry.*,entry_type.type');
        $this->db->from('entry');
        $this->db->join('entry_type', 'entry_type.id = entry.entry_type_id', 'left');
        if ($this->session->userdata('selected_branch')) {
            $this->db->where_in('entry.company_id',$this->session->userdata('selected_branch'));
        } else {
            $this->db->where_in('entry.company_id',1);
        }
        
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllEntryDetailById($where) {
        $this->db->select('ladger_account_detail.selected_currency,ladger_account_detail.unit_price,ladger_account_detail.ladger_id, ladger_account_detail.account, ladger_account_detail.balance,ladger_account_detail.narration, ladger.current_balance,ladger.ladger_name'); //Sudip 18/07/2016
        $this->db->from('ladger_account_detail');

        
        $this->db->join('entry', 'entry.id = ladger_account_detail.entry_id', 'left');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        $this->db->where_in('entry.company_id',$this->session->userdata('selected_branch'));
        $this->db->where($where)->order_by('ladger_account_detail.id', "asc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllCurrency() {
        $this->db->select('id,currency');
        $this->db->from('currency');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function assetesOrdering($id) {
        $this->db->select('id,group_name');
        $this->db->from('group');
        $this->db->where('id', $id);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function openingPL() {
        $this->db->select('id,opening_balance,ladger_name,account_type');
        $this->db->from('ladger');
        $this->db->where('id', 2);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->row_array();
    }

}

?>
