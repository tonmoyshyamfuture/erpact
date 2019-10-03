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
        $this->db->where($where);
        $this->db->order_by("entry.create_date", 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function allEntry($where) {
        $this->db->select('ladger.ladger_name, ladger.group_id, ladger.opening_balance, ladger.current_balance, ladger_account_detail.*');
        $this->db->from('ladger_account_detail');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        //$this->db->join('group', 'group.id = ladger.group_id', 'left');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function allEntryBS($where) {
        $this->db->select('(SELECT SUM(balance) FROM ' . tablename('ladger_account_detail') . ' WHERE account = "Dr" AND ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id != 0) AS balance_dr,(SELECT SUM(balance) FROM ' . tablename('ladger_account_detail') . ' WHERE account = "Cr" AND ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id != 0) AS balance_cr,(SELECT balance FROM ' . tablename('ladger_account_detail') . ' WHERE ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id = 0) AS op_balance,(SELECT account FROM ' . tablename('ladger_account_detail') . ' WHERE ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id = 0) AS op_account, ' . tablename('ladger') . '.ladger_name, ' . tablename('ladger') . '.group_id, ' . tablename('ladger') . '.opening_balance, ' . tablename('ladger') . '.current_balance, ' . tablename('ladger_account_detail') . '.*');
        $this->db->from('ladger_account_detail');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    //get all entry by date 
    public function allEntryBSByDate($where, $from_date, $to_date) {
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
        $this->db->select('(SELECT SUM(balance) FROM ' . tablename('ladger_account_detail') . ' WHERE account = "Dr" AND ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id != 0) AS balance_dr,(SELECT SUM(balance) FROM ' . tablename('ladger_account_detail') . ' WHERE account = "Cr" AND ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id != 0) AS balance_cr,(SELECT balance FROM ' . tablename('ladger_account_detail') . ' WHERE ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id = 0) AS op_balance,(SELECT account FROM ' . tablename('ladger_account_detail') . ' WHERE ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id = 0) AS op_account, ' . tablename('ladger') . '.ladger_name, ' . tablename('ladger') . '.group_id, ' . tablename('ladger') . '.opening_balance, ' . tablename('ladger') . '.current_balance, ' . tablename('ladger_account_detail') . '.*');
        $this->db->from('ladger_account_detail');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        $this->db->where($where);
        $this->db->where('create_date <=', $to_date);
        $this->db->where('create_date >=', $from_date);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function allEntryPL($where) {
//        $this->db->select('SUM('.tablename('ladger_account_detail').'.balance) AS total_balance, ladger.ladger_name, ladger.group_id, ladger.opening_balance, ladger.current_balance, '.tablename('ladger_account_detail').'.*');
        $this->db->select('(SELECT SUM(balance) FROM ' . tablename('ladger_account_detail') . ' WHERE account = "Dr" AND ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id != 0) AS balance_dr,(SELECT SUM(balance) FROM ' . tablename('ladger_account_detail') . ' WHERE account = "Cr" AND ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id != 0) AS balance_cr,(SELECT balance FROM ' . tablename('ladger_account_detail') . ' WHERE ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id = 0) AS op_balance,(SELECT account FROM ' . tablename('ladger_account_detail') . ' WHERE ladger_id = ' . tablename('ladger') . '.id AND status = "1" AND deleted = "0" AND entry_id = 0) AS op_account, ' . tablename('ladger') . '.ladger_name, ' . tablename('ladger') . '.group_id, ' . tablename('ladger') . '.opening_balance, ' . tablename('ladger') . '.current_balance, ' . tablename('ladger_account_detail') . '.*');
        $this->db->from('ladger_account_detail');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        $this->db->group_by('ladger.id');
        $this->db->where($where);
        $query = $this->db->get();
//        echo $this->db->last_query();die(); 
        return $query->result_array();
    }

    public function allEntry_date($where) {
        $this->db->select('SUM(ladger_account_detail.balance) AS total_balance, ladger.ladger_name, ladger.group_id, ladger.opening_balance, ladger.current_balance, ladger_account_detail.*');
        $this->db->from('ladger_account_detail');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        $this->db->group_by('ladger.id');
        //$this->db->join('group', 'group.id = ladger.group_id', 'left');
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

    //get trial balence by date range
    public function trialBalanceByDate($where, $from_date, $to_date) {
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
        $this->db->select('group.group_name, ladger.*');
        $this->db->from('ladger');
        $this->db->join('group', 'group.id = ladger.group_id', 'left');
        $this->db->where($where);
        $this->db->where('created_date <=', $to_date);
        $this->db->where('created_date >=', $from_date);
        $this->db->order_by('group_name', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getGrandParentid($where) {
        $this->db->select('*');
        $this->db->from('group');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    //get grand parent id by date
    public function getGrandParentidByDate($where, $from_date, $to_date) {
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
        $this->db->select('*');
        $this->db->from('group');
        $this->db->where($where);
        $this->db->where('create_date <=', $to_date);
        $this->db->where('create_date >=', $from_date);
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
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        $this->db->join('entry', 'entry.id = ladger_account_detail.entry_id', 'left');
        //$this->db->join('group', 'group.id = ladger.group_id', 'left');
        $this->db->where_in('entry.company_id', $this->session->userdata('selected_branch'));
        $this->db->where($where);
        $query = $this->db->get();
        //return $this->db->last_query();
        //return $query->result_array();
//        $from_date = $data['start_from_date'];
//        $to_date = $data['end_to_date'];
//        $query=$this->db->query("SELECT ladger_account_detail.*, ladger.ladger_name, ladger.group_id FROM ladger_account_detail LEFT JOIN ladger ON ladger_account_detail.ladger_id=ladger.id WHERE (create_date BETWEEN '".$from_date."' AND '".$to_date."')");
//        return $query->result_array();
    }

    public function getOpeningBalance($where) {
        $this->db->select('ladger.*');
        $this->db->from('ladger');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    //get opening balence by date
    public function getOpeningBalanceByDate($where, $from_date, $to_date) {
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
        $this->db->select('ladger.*');
        $this->db->from('ladger');
        $this->db->where($where);
        $this->db->where('created_date <=', $to_date);
        $this->db->where('created_date >=', $from_date);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLastOpeningBalance($where) {
        $this->db->select('entry.*, ladger_account_detail.current_closing_balance');
        $this->db->from('entry');
        $this->db->join('ladger_account_detail', 'ladger_account_detail.entry_id = entry.id', 'left');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        //$this->db->join('group', 'group.id = ladger.group_id', 'left');
        //$this->db->join('entry_type', 'entry.entry_type_id = entry_type.id', 'left');
         $this->db->where_in('entry.company_id', $this->session->userdata('selected_branch'));
        $this->db->where($where);
        $this->db->order_by("ladger_account_detail.create_date", 'DESC');
        $query = $this->db->get('', 1);
        //echo $this->db->last_query();
        return $query->result_array();
    }

    public function getledgerDetailsById($ledger_id) {
        $this->db->select('ET.type,E.entry_no,E.id,E.ledger_ids_by_accounts,LD.ladger_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance,LD.account,LD.balance,LD.modified_date,LD.create_date');
        $this->db->from('ladger_account_detail AS LD');
        $this->db->join('ladger AS L', 'L.id = LD.ladger_id');
        $this->db->join('entry AS E', 'E.id = LD.entry_id');
        $this->db->join('entry_type AS ET', 'ET.id = E.entry_type_id');
         $this->db->where_in('E.company_id', $this->session->userdata('selected_branch'));
        $this->db->where('LD.ladger_id', $ledger_id);
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.status', '1');
        $this->db->order_by('LD.modified_date', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    //get ledger details by date range
    public function getledgerDetailsByIdByDate($ledger_id, $from_date, $to_date) {
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
        $this->db->select('ET.type,E.entry_no,E.id,E.ledger_ids_by_accounts,E.create_date,LD.ladger_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance,LD.account,LD.balance,LD.modified_date,E.entry_type_id, LD.balance');
        $this->db->from('ladger_account_detail AS LD');
        $this->db->join('ladger AS L', 'L.id = LD.ladger_id');
        $this->db->join('entry AS E', 'E.id = LD.entry_id');
        $this->db->join('entry_type AS ET', 'ET.id = E.entry_type_id');
        $this->db->where_in('E.company_id', $this->session->userdata('selected_branch'));
        $this->db->where('LD.ladger_id', $ledger_id);
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.create_date <=', $to_date);
        $this->db->where('LD.create_date >=', $from_date);
        $this->db->where_in('E.company_id', $this->session->userdata('selected_branch')); // Koushik merge branch. Line added
//        $this->db->order_by('LD.modified_date', 'ASC');
//        $this->db->order_by('E.id', 'ASC');
        $this->db->order_by('E.create_date', 'ASC');
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

    public function getAllGroupsLevelOne($income_id, $expenses_id, $assets_id, $liabilities_id, $from_date, $to_date) {
       $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
        $this->db->select('id,group_name,group_code,parent_id,create_date');
        $ids = [];
        $ids[] = $income_id;
        $ids[] = $expenses_id;
        $ids[] = $assets_id;
        $ids[] = $liabilities_id;
        $this->db->from('group');
        $this->db->where_in('parent_id', $ids);
//        $this->db->or_where('parent_id', $income_id);
//        $this->db->or_where('parent_id', $expenses_id);
//        $this->db->or_where('parent_id', $assets_id);
//        $this->db->or_where('parent_id', $liabilities_id);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $this->db->where('create_date <=', $to_date);
        $this->db->where('create_date >=', $from_date);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getProfitLoss($profit_loss_id){
        $this->db->select('L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance');
        $this->db->from('ladger AS L');
        $this->db->where('L.group_id', $profit_loss_id);
        $this->db->where('L.deleted', '0');
        $this->db->where('L.status', '1');
//        $this->db->where('L.created_date >=', $from_date);
//        $this->db->where('L.created_date <=', $to_date);
        $query = $this->db->get();
        return $query->row_array();
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

    //get all group by id between date range
    public function getAllGroupsByIdByDate($group_id, $from_date, $to_date) {
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
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

//get all ledger by group id and date range
    public function getAllLedgerByGroupIdByDate($group_id = NULL, $from_date, $to_date,$finans_start_date) {
        $finans_start_date=$finans_start_date.' 00:00:00';
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
        //add branch openig balance 
//        $this->db->select('L.id,L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = "Cr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND LD.create_date >=' . "'$from_date'" . ' AND LD.create_date <=' . "'$to_date'" . ') AS cr_balance,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = "Dr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND LD.create_date >=' . "'$from_date'" . ' AND LD.create_date <=' . "'$to_date'" . ') AS dr_balance,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = "Cr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND LD.create_date >=' . "'$finans_start_date'" . ' AND LD.create_date <=' . "'$from_date'" . ') AS prev_cr_balance,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = "Dr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND LD.create_date >=' . "'$finans_start_date'" . ' AND LD.create_date <=' . "'$from_date'" . ') AS prev_dr_balance');
        $this->db->select('L.id,L.group_id,L.ladger_name,LD.account AS account_type,L.ledger_code,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id = 0 AND LD.branch_id IN ('.$this->session->userdata('selected_branch_str').')) AS opening_balance,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = "Cr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND LD.branch_id IN ('.$this->session->userdata('selected_branch_str').') AND LD.create_date >=' . "'$from_date'" . ' AND LD.create_date <=' . "'$to_date'" . ') AS cr_balance,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = "Dr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND LD.branch_id IN ('.$this->session->userdata('selected_branch_str').') AND LD.create_date >=' . "'$from_date'" . ' AND LD.create_date <=' . "'$to_date'" . ') AS dr_balance,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = "Cr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND LD.branch_id IN ('.$this->session->userdata('selected_branch_str').') AND LD.create_date >=' . "'$finans_start_date'" . ' AND LD.create_date <=' . "'$from_date'" . ') AS prev_cr_balance,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = "Dr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND LD.branch_id IN ('.$this->session->userdata('selected_branch_str').') AND LD.create_date >=' . "'$finans_start_date'" . ' AND LD.create_date <=' . "'$from_date'" . ') AS prev_dr_balance',FALSE);
        $this->db->from('ladger AS L');
        $this->db->join('ladger_account_detail AS LD', 'L.id = LD.ladger_id');
        $this->db->where('L.group_id', $group_id);
        $this->db->where('L.deleted', '0');
        $this->db->where('L.status', '1');
        $this->db->where('L.created_date >=', $finans_start_date);
        $this->db->where('L.created_date <=', $to_date);
        $this->db->group_by('L.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllLedgerByGroupId($group_id = NULL) {
         $selected_branch_str=$this->session->userdata("selected_branch_str");  
        $query = $this->db->query("SELECT L.id,L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD INNER JOIN pb_entry AS E ON E.id=LD.entry_id WHERE LD.ladger_id = L.id AND LD.account = 'Cr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND E.company_id IN($selected_branch_str)) AS cr_balance,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD INNER JOIN pb_entry AS E ON E.id=LD.entry_id WHERE LD.ladger_id = L.id AND LD.account = 'Dr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND E.company_id IN($selected_branch_str)) AS dr_balance
                FROM  pb_ladger AS L WHERE L.status = '1' AND L.deleted = '0' AND L.group_id = " . $group_id);

        return $query->result_array();
    }

    public function getOpeningBalanceByGroupId($group_id) {

        $query = $this->db->query("SELECT L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance 
                FROM  pb_ladger AS L WHERE L.status = '1' AND L.deleted = '0' AND L.group_id = " . $group_id);
        return $query->result_array();
    }

    //get opening balence by group id and date range--@asit
    public function getOpeningBalanceByGroupIdByDate($group_id, $from_date, $to_date) {
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
//        $this->db->select('L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance');
        $this->db->select('L.group_id,L.ladger_name,LD.account AS account_type,L.ledger_code,SUM(LD.balance) AS opening_balance');
        $this->db->from('ladger_account_detail AS LD');
        $this->db->join('ladger AS L', 'L.id = LD.ladger_id');
        $this->db->where('L.group_id', $group_id);
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.entry_id', '0');
        $this->db->where_in('LD.branch_id', $this->session->userdata('selected_branch'),FALSE);
       // $this->db->where('L.created_date >=', $from_date);
        //$this->db->where('L.created_date <=', $to_date);
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

    //get child group by group id and date range--@asit
    public function getChildIdParentIdByDate($parent_id, $from_date, $to_date) {
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
        $this->db->select('id');
        $this->db->from('group');
        $this->db->where('parent_id', $parent_id);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $this->db->where('create_date >=', $from_date);
        $this->db->where('create_date <=', $to_date);
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

    //get DR balence by group id and date range--@asit
    public function getDrBalanceByGroupIdByDate($group_id, $from_date, $to_date,$finans_start_date) {
        
         $selected_branch_str=$this->session->userdata("selected_branch_str");  
        $finans_start_date=$finans_start_date.' 00:00:00';
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
        $this->db->select('L.id,L.group_id,L.ladger_name,L.ledger_code,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD INNER JOIN pb_entry AS E ON E.id=LD.entry_id WHERE LD.ladger_id = L.id AND LD.account = "Dr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND E.company_id IN('.$selected_branch_str.') AND LD.create_date >=' . "'$from_date'" . ' AND LD.create_date <=' . "'$to_date'" . ') AS dr_balance',FALSE);
        $this->db->from('ladger AS L');
        //$this->db->join('ladger_account_detail AS LD', 'L.id = LD.ladger_id');
        $this->db->where('L.group_id', $group_id);
        $this->db->where('L.deleted', '0');
        $this->db->where('L.status', '1');
        $this->db->where('L.created_date >=', $finans_start_date);
        $this->db->where('L.created_date <=', $to_date);
        $query = $this->db->get();
        return $query->result_array();
    }
    
     //get prev DR balence by group id and date range--@asit
    public function getPrevDrBalanceByGroupIdByDate($group_id, $from_date, $to_date,$finans_start_date) {
         $selected_branch_str=$this->session->userdata("selected_branch_str");  
        $finans_start_date=$finans_start_date.' 00:00:00';
        $from_date=$from_date.' 00:00:00';
        $to_date1 = date('Y-m-d',strtotime($to_date . "-1 days"));
        $to_date1=$to_date1.' 23:59:59';
        $to_date=$to_date.' 23:59:59';
        $this->db->select('L.id,L.group_id,L.ladger_name,L.ledger_code,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD INNER JOIN pb_entry AS E ON E.id=LD.entry_id WHERE LD.ladger_id = L.id AND LD.account = "Dr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND E.company_id IN('.$selected_branch_str.') AND LD.create_date >=' . "'$from_date'" . ' AND LD.create_date <=' . "'$to_date1'" . ') AS dr_balance',FALSE);
        $this->db->from('ladger AS L');
        //$this->db->join('ladger_account_detail AS LD', 'L.id = LD.ladger_id');
        $this->db->where('L.group_id', $group_id);
        $this->db->where('L.deleted', '0');
        $this->db->where('L.status', '1');
        $this->db->where('L.created_date >=', $finans_start_date);
        $this->db->where('L.created_date <=', $to_date);
      
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getCrBalanceByGroupId($group_id) {
         $selected_branch_str=$this->session->userdata("selected_branch_str");  
        $query = $this->db->query("SELECT L.id,L.group_id,L.ladger_name,L.ledger_code,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD INNER JOIN pb_entry AS E ON E.id=LD.entry_id WHERE LD.ladger_id = L.id AND LD.account = 'Cr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND E.company_id IN($selected_branch_str)) AS cr_balance
                FROM  pb_ladger AS L WHERE L.status = '1' AND L.deleted = '0' AND L.group_id = " . $group_id);

        return $query->result_array();
    }

    //get CR balence by group id and date range--@asit
    public function getCrBalanceByGroupIdByDate($group_id, $from_date, $to_date,$finans_start_date) {
        $selected_branch_str=$this->session->userdata("selected_branch_str");
        $finans_start_date=$finans_start_date.' 00:00:00';
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
        $this->db->select('L.id,L.group_id,L.ladger_name,L.ledger_code,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD INNER JOIN pb_entry AS E ON E.id=LD.entry_id WHERE LD.ladger_id = L.id AND LD.account = "Cr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND E.company_id IN('.$selected_branch_str.') AND LD.create_date >=' . "'$from_date'" . ' AND LD.create_date <=' . "'$to_date'" . ') AS cr_balance',FALSE);
        $this->db->from('ladger AS L');
       // $this->db->join('ladger_account_detail AS LD', 'L.id = LD.ladger_id');
        $this->db->where('L.group_id', $group_id);
        $this->db->where('L.deleted', '0');
        $this->db->where('L.status', '1');
        $this->db->where('L.created_date >=', $finans_start_date);
        $this->db->where('L.created_date <=', $to_date);
        $query = $this->db->get();
        return $query->result_array();
    }
    
     //get CR balence by group id and date range--@asit
    public function getPrevCrBalanceByGroupIdByDate($group_id, $from_date, $to_date,$finans_start_date) {
        $selected_branch_str=$this->session->userdata("selected_branch_str");  
        $finans_start_date=$finans_start_date.' 00:00:00';
        $from_date=$from_date.' 00:00:00';
        $to_date1 = date('Y-m-d',strtotime($to_date . "-1 days"));
        $to_date=$to_date.' 23:59:59';
        $to_date1=$to_date1.' 23:59:59';
        $this->db->select('L.id,L.group_id,L.ladger_name,L.ledger_code,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD INNER JOIN pb_entry AS E ON E.id=LD.entry_id WHERE LD.ladger_id = L.id AND LD.account = "Cr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND E.company_id IN('.$selected_branch_str.') AND LD.create_date >=' . "'$from_date'" . ' AND LD.create_date <=' . "'$to_date1'" . ') AS cr_balance',FALSE);
        $this->db->from('ladger AS L');
       // $this->db->join('ladger_account_detail AS LD', 'L.id = LD.ladger_id');
        $this->db->where('L.group_id', $group_id);
        $this->db->where('L.deleted', '0');
        $this->db->where('L.status', '1');
        $this->db->where('L.created_date >=', $finans_start_date);
        $this->db->where('L.created_date <=', $to_date);
        $query = $this->db->get();
      
        return $query->result_array();
    }

    public function getLedgerDetailsByGroupId($group_id) {

        $selected_branch_str=$this->session->userdata("selected_branch_str");
        $query = $this->db->query("SELECT G.group_name,L.id,L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = 'Cr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND E.company_id IN($selected_branch_str)) AS cr_balance,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = 'Dr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND E.company_id IN($selected_branch_str)) AS dr_balance
                FROM pb_group AS G, pb_ladger AS L WHERE G.id = L.group_id AND L.group_id = " . $group_id);

        return $query->result_array();
    }

    //get ledger details by group id--@asit
    public function getLedgerDetailsByGroupIdByDate($group_id, $from_date, $to_date) {

        $selected_branch_str=$this->session->userdata("selected_branch_str");
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
        $this->db->select('G.group_name,L.id,L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD INNER JOIN pb_entry AS E ON E.id=LD.entry_id WHERE LD.ladger_id = L.id AND LD.account = "Cr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND E.company_id IN('.$selected_branch_str.') AND LD.create_date >=' . "'$from_date'" . ' AND LD.create_date <=' . "'$to_date'" . ') AS cr_balance, (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD INNER JOIN pb_entry AS E ON E.id=LD.entry_id WHERE LD.ladger_id = L.id AND LD.account = "Dr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND E.company_id IN('.$selected_branch_str.') AND LD.create_date >=' . "'$from_date'" . ' AND LD.create_date <=' . "'$to_date'" . ') AS dr_balance',false);
        $this->db->from('ladger_account_detail AS LD');
        $this->db->join('entry AS E', 'E.id = LD.entry_id','LEFT');//08122017
        $this->db->join('ladger AS L', 'L.id = LD.ladger_id');
        $this->db->join('group AS G', 'G.id = L.group_id');
        $this->db->where('L.group_id', $group_id);
//        $this->db->where_in('E.company_id', $this->session->userdata('selected_branch'));//08122017
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.create_date >=', $from_date);
        $this->db->where('LD.create_date <=', $to_date);
        $this->db->group_by('L.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLedgerDetailsByGroupIdByDate_bk_4_1_2017($group_id, $from_date, $to_date) {
        $between = '';
        $between1 = '';
        if ($from_date && $to_date) {
            $between.=" AND LD.create_date >= " . $from_date . " AND LD.create_date <= " . $to_date;
            $between1.=" AND L.created_date >= " . $from_date . " AND L.created_date <= " . $to_date;
        }
        $query = $this->db->query("SELECT G.group_name,L.id,L.group_id,L.ladger_name,L.account_type,L.ledger_code,L.opening_balance,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = 'Cr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0) AS cr_balance,
                (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = 'Dr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0) AS dr_balance
                FROM pb_group AS G, pb_ladger AS L WHERE G.id = L.group_id AND L.group_id = " . $group_id);
        return $query->result_array();
    }

    public function assetesOrdering($ids) {
        $this->db->select('id,group_name');
        $this->db->from('group');
        $this->db->where_in('id', $ids);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result_array();
    }

    //get all groups by date range
    public function assetesOrderingByDate($ids, $from_date, $to_date) {
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
        $this->db->select('id,group_name');
        $this->db->from('group');
        $this->db->where_in('id', $ids);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $this->db->where('create_date >=', $from_date);
        $this->db->where('create_date <=', $to_date);
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

    //get opening PL by date
    public function openingPLByDate($from_date, $to_date) {
        $this->db->select('id,opening_balance,ladger_name,account_type');
        $this->db->from('ladger');
        $this->db->where('id', 2);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        //  $this->db->where('created_date >=', $from_date);
        //$this->db->where('created_date <=', $to_date);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getStandardFormatData($id = NULL) {
        $query = $this->db->select('*')
                ->from(tablename('account_standard_format'))
                ->get();
        return $row = $query->first_row();
    }
    
    // get company details
    public function getCompanyDetails() {
        $this->db->select('act.*,s.name as state_name');
        $this->db->from('account_settings AS act');
        $this->db->join('states AS s', 's.id = act.state_id');
        $this->db->where('act.id =', 1);
        $query = $this->db->get();
        return $query->row();
    }
    
     public function getSubGroup($exp_id_arr) {
        $this->db->select('id');
        $this->db->from('group');
        $this->db->where_in('parent_id', $exp_id_arr);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result();
    }
    
     public function getCodePosition() {
        $this->db->select('code_before_name');
        $query = $this->db->get('account_configuration');
        return $query->row();
    }
    
    public function getGroup($group_id) {
        $this->db->select('id,group_name');
        $this->db->where('id', $group_id);
        $query = $this->db->get('group');
        return $query->row();
    }
    
   

    // public function getBill($entryId,$ledgerId,$drcr){
    //     $this->db->select('created_date,bill_amount,bill_name,dr_cr');
    //     $this->db->from('billwish_details');
    //     $this->db->where('entry_id', $entryId);
    //     $this->db->where('ledger_id', $ledgerId);
    //     $this->db->where('dr_cr', $drcr);
    //     $query = $this->db->get();
    //     return $query->result_array();
    // }
    
    
    // somnath - daybook model
    public function getDayBookForAllLedger($from_date, $to_date) {
        $company_id = $this->session->userdata("selected_branch");
        
        //to get the last entry date        
        $this->db->select("max(create_date) as max_entry");
        $this->db->from('entry');
        $result = $this->db->get()->row();
        $max_date = $result->max_entry;
        
        //to get the debit and creadit ledgerwise data
        $this->db->select('e.create_date as entry_date, e.entry_no as voucher_no, et.type as voucher_type, e.dr_amount as dr_amount, e.cr_amount as cr_amount, e.ledger_ids_by_accounts as ledger_ids_by_accounts, e.id as entry_id, e.entry_type_id as entry_type_id');
//        $this->db->from('ladger_account_detail as ad');
//        $this->db->join('ladger as l', 'ad.ladger_id = l.id');
        $this->db->from('entry as e');
        $this->db->join('entry_type as et', 'e.entry_type_id = et.id');
//        $this->db->where('ad.status', '1');
//        $this->db->where('l.status', '1');
        $this->db->where('e.status', '1');
        $this->db->where('e.deleted', 0);
        $this->db->where('e.is_inventry', 1);
        $this->db->where('et.status', '1');
        $this->db->where_in('e.company_id', $company_id);
        if( $from_date != "" && $to_date != ""){
            $from = date('Y-m-d', strtotime($from_date));
            $to = date('Y-m-d', strtotime($to_date));
            $this->db->where('e.create_date >=', $from);
            $this->db->where('e.create_date <=', $to);
        }else{
            $this->db->where('e.create_date', $max_date);            
        }
        $this->db->order_by('entry_date');
        $this->db->group_by('e.entry_no');
        $query = $this->db->get();
        return $query->result_array();
        
    }
    
    public function getCurrentDateFormat() {
        $this->db->select('df.date_format_sign as date_format');
        $this->db->from('date_format as df');
        $this->db->join('account_configuration as ac', 'ac.selected_date_format = df.id');
        $result = $this->db->get()->row();
        return $result->date_format;
    }
    
    public function searchDayBook($search) {
        $company_id = $this->session->userdata("selected_branch");
        
        //to get the debit and creadit ledgerwise data
        $this->db->select('e.create_date as entry_date, e.entry_no as voucher_no, et.type as voucher_type, e.dr_amount as dr_amount, e.cr_amount as cr_amount, e.ledger_ids_by_accounts as ledger_ids_by_accounts, e.id as entry_id, e.entry_type_id as entry_type_id');
        $this->db->from('ladger_account_detail as ad');
        $this->db->join('ladger as l', 'ad.ladger_id = l.id');
        $this->db->join('entry as e', 'e.id = ad.entry_id');
        $this->db->join('entry_type as et', 'e.entry_type_id = et.id');
        $this->db->where('e.status', '1');
        $this->db->where('e.deleted', 0);
        $this->db->where('e.is_inventry', 1);
        $this->db->where('et.status', '1');
        $this->db->where_in('e.company_id', $company_id);
        $this->db->like('e.entry_no', $search);
        $this->db->or_like('et.type', $search);
        $this->db->or_like('l.ladger_name', $search);
        $this->db->or_like('e.narration', $search);
        
        $this->db->order_by('entry_date');
        $this->db->group_by('e.entry_no');
        $query = $this->db->get();
//        return $this->db->last_query();
        return $query->result_array();
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

    // opeing balance calculation for a ledger
    public function getOpeningBalanceForLedgerByDate($ledger_id, $from_date, $to_date) {
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
        $this->db->select('L.account_type,SUM(LD.balance) as opening_balance');
        $this->db->from('ladger_account_detail AS LD');
        $this->db->join('ladger AS L', 'L.id = LD.ladger_id');
        $this->db->join('entry AS E', 'E.id = LD.entry_id');
        $this->db->join('entry_type AS ET', 'ET.id = E.entry_type_id');
        $this->db->where_in('E.company_id', $this->session->userdata('selected_branch'));
        $this->db->where('LD.ladger_id', $ledger_id);
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.create_date <=', $to_date);
        $this->db->where('LD.create_date >=', $from_date);
        $this->db->order_by('LD.modified_date', 'ASC');

        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function getTransactionDetailsByEntry($entry_id)
    {
        $this->db->select('e.entry_no, e.create_date, e.system_time,e.narration, et.type as entry_type, L.account_type as account_type, L.ladger_name as ledger_name, LD.balance');
        $this->db->from('entry e');
        $this->db->join('entry_type et', 'e.entry_type_id = et.id', 'left');
        $this->db->join('ladger_account_detail LD', 'LD.entry_id = e.id', 'left');
        $this->db->join('ladger L', 'L.id = LD.ladger_id');
        // $this->db->join('orders o', 'e.id = o.entry_id');
        $this->db->where('e.status', '1');
        $this->db->where('e.deleted =', '0');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.deleted =', '0');
        $this->db->where('e.id', $entry_id);
        $query = $this->db->get();
        return $query->result();
    }
    

}

?>
