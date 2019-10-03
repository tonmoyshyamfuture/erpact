<?php

class account extends CI_Model {

    public function __construct() {

        parent::__construct();
        $this->load->database();
        $this->load->model('front/usermodel', 'currentusermodel');
        $this->load->helper('financialyear');
    }

    public function getLedgerCodeStatus() {
        $this->db->select('ledger_code_status');
        $query = $this->db->get('account_configuration');
        return $query->row_array();
    }

    public function getAllLedger($where) {
        $this->db->select("group.group_name,group.id as group_table_id, ladger.*,(SELECT COUNT(id) FROM pb_ladger_account_detail WHERE  status = '1' AND deleted = '0' AND entry_id != 0 AND ladger_id = pb_ladger.id) as no_of_ledger");
        $this->db->from('ladger');
        $this->db->join('group', 'group.id = ladger.group_id', 'left');
        $this->db->where($where);

        $this->db->where('branch_id !=', $this->session->userdata('branch_id'));
        $this->db->order_by("group.id", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getAllLedgerBylimit($where,$limit = 10,$offset = 0,$search='') {
        $this->db->select("group.group_name,group.id as group_table_id, ladger.id,ladger.ladger_name,ladger.ledger_code,ladger.discontinue,ladger.operation_status,ladger.branch_id, '' as no_of_ledger",FALSE);
        $this->db->from('ladger');
        $this->db->join('group', 'group.id = ladger.group_id', 'left');
        $this->db->where($where);
        $this->db->where('branch_id !=', $this->session->userdata('branch_id'));
        $this->db->or_like('group.group_name', $search);
        $this->db->or_like('ladger.ladger_name', $search);
        $this->db->or_like('ladger.ledger_code', $search);
        $this->db->order_by("group.id", "asc");
        $this->db->limit($limit,$offset);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getCountLedger($where,$search='') {
        $this->db->select("group.group_name,group.id as group_table_id, ladger.*,'' as no_of_ledger",FALSE);
        $this->db->from('ladger');
        $this->db->join('group', 'group.id = ladger.group_id', 'left');
        $this->db->where($where);
        $this->db->where('branch_id !=', $this->session->userdata('branch_id'));
        $this->db->or_like('group.group_name', $search);
        $this->db->or_like('ladger.ladger_name', $search);
        $this->db->or_like('ladger.ledger_code', $search);
        $this->db->order_by("group.id", "asc");
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getContact($company='') {
        $this->db->select("CD.company_name,CD.id");
        $this->db->from('customer_details CD');
        $this->db->where('ledger_id', '0');
        $this->db->where('status', '1');
        if($company){
        $this->db->like('CD.company_name', $company);
        }
        $this->db->order_by("CD.company_name", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllGroup($group) {
        $this->db->select("G.group_name,G.id");
        $this->db->from('group G');
        $this->db->like('G.group_name', $group);
        $this->db->order_by("G.group_name", "asc");
        $query = $this->db->get();
        return $query->result();
    }
    
    public function getGroupByName($group,$not_in = array()) {
        $this->db->select("G.group_name,G.id");
        $this->db->from('group G');
        $this->db->like('G.group_name', $group);
        $this->db->where_not_in('G.id', $not_in);
        $this->db->where('G.deleted', 0);
        $this->db->order_by("G.group_name", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    public function getLedgerDetailsById($where) {
        $this->db->select('ladger.*, group.group_name, ladger_account_detail.id as ladger_account_detail_id, ladger_account_detail.account as account, ladger_account_detail.balance as balance');
        $this->db->from('ladger');
        $this->db->join('group', 'group.id = ladger.group_id', 'left');
        $this->db->join('ladger_account_detail', 'ladger_account_detail.ladger_id = ladger.id', 'left');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row();
    }

    public function getLedgerById($where) {
        $this->db->select('ladger.*, group.group_name, ladger_account_detail.id as ladger_account_detail_id, ladger_account_detail.account as account, ladger_account_detail.balance as balance');
        $this->db->from('ladger');
        $this->db->join('group', 'group.id = ladger.group_id', 'left');
        $this->db->join('ladger_account_detail', 'ladger_account_detail.ladger_id = ladger.id', 'left');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    //get sum balance
    public function sumBalance($ledger_id) {
        $this->db->select("(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.account = 'Cr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND ladger_id='" . $ledger_id . "') AS cr_balance, (SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.account = 'Dr' AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND ladger_id='" . $ledger_id . "') AS dr_balance");
        $this->db->from('ladger_account_detail');
        $this->db->where('entry_id !=', '0');
        $this->db->where('ladger_id =', $ledger_id);
        $this->db->where('deleted', '0');
        $this->db->where('status', '1');
        $this->db->group_by('ladger_id');
        $query = $this->db->get();
        return $query->result();
    }

    public function saveLedgerData($data = NULL) {
        $opening_balance = $data['opening_balance'];
        if ($data['ledger_id'] != 0) {
            //get current balence balance
            $transaction_details = $this->sumBalance($data['ledger_id']);
            $cr_sum = 0;
            $dr_sum = 0;
            if ($transaction_details) {
                $cr_sum = $transaction_details[0]->cr_balance;
                $dr_sum = $transaction_details[0]->dr_balance;
            }
            if ($data['account'] == 'Dr') {
                $last_closing_balance = (($opening_balance + $dr_sum) - $cr_sum);
            }
            if ($data['account'] == 'Cr') {
                $last_closing_balance = (($opening_balance + $cr_sum) - $dr_sum);
            }
            
            $this->db->where('id', $data['ledger_id']);
            $ledger = $this->db->get('ladger')->row_array();
            $log = array(
                'user_id' => $this->session->userdata('admin_uid'),
                'branch_id' => $this->session->userdata('branch_id'),
                'module' => 'ledger',
                'action' => '`'. $data['ladger_name'] .'` <b>edited</b>',
                'previous_data' => json_encode($ledger),
                'performed_at' => date('Y-m-d H:i:s', time())
            );
            $this->currentusermodel->updateLog($log);            
            
            $ledger = array(
                'group_id' => $data['group_id'],
                'opening_balance' => $opening_balance,
                'last_opening_balance' => $opening_balance,
                'current_balance' => $last_closing_balance,
                'account_type' => $data['account'],
                'ladger_name' => $data['ladger_name'],
                'tracking_status' => $data['tracking_status'],
                'bill_details_status' => $data['bill_details_status'],
                'service_status' => $data['service_status'],
                'ledger_code' => $data['ledger_code'],
                'credit_date' => $data['credit_date'],
                'credit_limit' => $data['credit_limit']
            );
            $this->db->where('id', $data['ledger_id']);
            $this->db->update('ladger', $ledger);
            //get all transuction acoding to ledger id
            $update_data = array(
                'balance' => $opening_balance,
                'account' => $data['account'],
                'current_opening_balance' => $opening_balance,
                'current_closing_balance' => $last_closing_balance,
            );
//            $this->db->where(array('ladger_id' => $data['ledger_id'], 'status' => '1', 'deleted' => '0', 'entry_id' => '0')); //not branch concept 
            $this->db->where(array('ladger_id' => $data['ledger_id'], 'status' => '1', 'deleted' => '0', 'entry_id' => '0','branch_id' =>  $this->session->userdata('branch_id')));//branch concept
            $this->db->update('ladger_account_detail', $update_data);

            /*
             * bank details update
             */
            if($data['ladger_name'] != "" && $data['group_id'] == 10) {
                $bank = array(
                    'bank_name' =>  $data['bank_name'],
                    'branch_name' =>  $data['branch_name'],
                    'acc_no' =>  $data['acc_no'],
                    'ifsc' =>  $data['ifsc'],
                    'bank_address' =>  $data['bank_address'],
                );
                $this->db->where('ledger_id', $data['ledger_id']);
                $this->db->update('bank_details_company', $bank);
            }


            return $data['ledger_id'];
        } else {

            $log = array(
                'user_id' => $this->session->userdata('admin_uid'),
                'branch_id' => $this->session->userdata('branch_id'),
                'module' => 'ledger',
                'action' => '`' . $data['ladger_name'] . '` <b>created</b>',
                'previous_data' => '',
                'performed_at' => date('Y-m-d H:i:s', time())
            );
            $this->currentusermodel->updateLog($log);

            $financial_year = get_financial_year();            
            $finans_start_date = date("Y-m-d", strtotime(current($financial_year)));

            $ledger = array(
                'group_id' => $data['group_id'],
                'opening_balance' => $opening_balance,
                'last_opening_balance' => $opening_balance,
                'current_balance' => $opening_balance,
                'account_type' => $data['account'],
                'ladger_name' => $data['ladger_name'],
                'tracking_status' => $data['tracking_status'],
                'bill_details_status' => $data['bill_details_status'],
                'service_status' => $data['service_status'],
                'ledger_code' => $data['ledger_code'],
                'credit_date' => $data['credit_date'],
                'credit_limit' => $data['credit_limit'],
                'created_date' => $finans_start_date,
                'financial_year' => $finans_start_date
            );
            $this->db->insert('ladger', $ledger);

//        if no opening balance concept in branch
//            $ladger_account_detail = array(
//                'ladger_id' => $this->db->insert_id(),
//                'account' => $data['account'],
//                'balance' => $opening_balance,
//                'current_opening_balance' => $opening_balance,
//                'current_closing_balance' => $opening_balance,
//                'create_date' => date("Y-m-d H:i:s")
//            );
//            $this->db->insert('ladger_account_detail', $ladger_account_detail);
//            return $ladger_account_detail['ladger_id'];
            
//        if opening balance concept in branch
            $ledger_id = $this->db->insert_id();
            $branchs = $this->db->select("id")->get('account_settings');
            $ledger_details = array();
                foreach($branchs->result_array() AS $key=>$branch){
                    $ledger_details[$key]['branch_id']= $branch['id'];
                    $ledger_details[$key]['ladger_id']= $ledger_id;
                    $ledger_details[$key]['account']= 'Dr';
                    $ledger_details[$key]['balance']= 0.00;
                    $ledger_details[$key]['current_opening_balance']= 0.00;
                    $ledger_details[$key]['current_closing_balance']= 0.00;
                    $ledger_details[$key]['create_date']= $finans_start_date;
                }
            $this->db->insert_batch('ladger_account_detail', $ledger_details);

            /*
             * bank details insertion
             */
            if($data['ladger_name'] != "" && $data['group_id'] == 10) {
                $bank = array(
                    'ledger_id' => $ledger_id,
                    'bank_name' =>  $data['bank_name'],
                    'branch_name' =>  $data['branch_name'],
                    'acc_no' =>  $data['acc_no'],
                    'ifsc' =>  $data['ifsc'],
                    'bank_address' =>  $data['bank_address'],
                );
                $this->db->insert('bank_details_company', $bank);
            }
            
            
            if($opening_balance > 0){
                $update_data = array(
                'balance' => $opening_balance,
                'account' => $data['account'],
                'current_opening_balance' => $opening_balance,
                'current_closing_balance' => $opening_balance,
                );
                
                $this->db->where(array('ladger_id' => $ledger_id, 'status' => '1', 'deleted' => '0', 'entry_id' => '0','branch_id' =>  $this->session->userdata('branch_id')));//branch concept
                $this->db->update('ladger_account_detail', $update_data);
            }
            
            return $ledger_id;
              
        }
    }

    public function saveLedger($data = NULL) {
        if ($data['account'] == 'Dr') {
            $opening_balance = $data['opening_balance'];
        }
        if ($data['account'] == 'Cr') {
            if ($data['opening_balance'] != 0) {
                $opening_balance = $data['opening_balance'];
            } else {
                $opening_balance = $data['opening_balance'];
            }
        }
        if ($data['ledger_id'] != 0) {

            //get current balence balance
            $transaction_details = $this->sumBalance($data['ledger_id']);
            $cr_sum = 0;
            $dr_sum = 0;
            if ($transaction_details) {
                $cr_sum = $transaction_details[0]->cr_balance;
                $dr_sum = $transaction_details[0]->dr_balance;
            }
            if ($data['account'] == 'Dr') {
                $last_closing_balance = (($opening_balance + $dr_sum) - $cr_sum);
            }
            if ($data['account'] == 'Cr') {
                $last_closing_balance = (($opening_balance + $cr_sum) - $dr_sum);
            }
            $ledger = array(
                'group_id' => $data['group_id'],
                'opening_balance' => $opening_balance,
                'last_opening_balance' => $opening_balance,
                'current_balance' => $last_closing_balance,
                'account_type' => $data['account'],
                'reconciliation' => $data['reconciliation'],
                'cash/bank' => $data['cash/bank'],
                'ladger_name' => $data['ladger_name'],
                'mailing_name' => $data['mailing_name'],
                'street_address' => $data['street_address'],
                'country' => $data['country'],
                'state' => $data['state'],
                'city_name' => $data['city_name'],
                'zip_code' => $data['zip_code'],
                'pan_it_no' => $data['pan_it_no'],
                'sale_tax_no' => $data['sale_tax_no'],
                'cst_no' => $data['cst_no'],
                'contact_person' => $data['contact_person'],
                'contact_person2' => $data['contact_person2'],
                'email' => $data['email'],
                'email2' => $data['email2'],
                'phone_no' => $data['phone_no'],
                'phone_no2' => $data['phone_no2'],
                'designation' => $data['designation'],
                'designation2' => $data['designation2'],
                'tracking_status' => $data['tracking_status'],
                'bill_details_status' => $data['bill_details_status'],
                'ledger_code' => $data['ledger_code'],
                'credit_date' => $data['credit_date'],
                'credit_limit' => $data['credit_limit']
            );
            $this->db->where('id', $data['ledger_id']);
            $this->db->update('ladger', $ledger);
            //get all transuction acoding to ledger id
            $update_data = array(
                'balance' => $opening_balance,
                'current_closing_balance' => $last_closing_balance,
            );
            $this->db->where('ladger_id', $data['ledger_id'], 'status', 1, 'deleted', 0, 'entry_id', 0);
            $this->db->update('ladger_account_detail', $update_data);
        } else {
            $ledger = array(
                'group_id' => $data['group_id'],
                'opening_balance' => $opening_balance,
                'current_balance' => $opening_balance,
                'reconciliation' => $data['reconciliation'],
                'cash/bank' => $data['cash/bank'],
                'ladger_name' => $data['ladger_name'],
                'account_type' => $data['account'],
                'mailing_name' => $data['mailing_name'],
                'street_address' => $data['street_address'],
                'country' => $data['country'],
                'state' => $data['state'],
                'city_name' => $data['city_name'],
                'zip_code' => $data['zip_code'],
                'pan_it_no' => $data['pan_it_no'],
                'sale_tax_no' => $data['sale_tax_no'],
                'cst_no' => $data['cst_no'],
                'contact_person' => $data['contact_person'],
                'contact_person2' => $data['contact_person2'],
                'email' => $data['email'],
                'email2' => $data['email2'],
                'phone_no' => $data['phone_no'],
                'phone_no2' => $data['phone_no2'],
                'designation' => $data['designation'],
                'designation2' => $data['designation2'],
                'tracking_status' => $data['tracking_status'],
                'bill_details_status' => $data['bill_details_status'],
                'ledger_code' => $data['ledger_code'],
                'credit_date' => $data['credit_date'],
                'credit_limit' => $data['credit_limit']
            );

            $this->db->insert('ladger', $ledger);

            $ladger_account_detail = array(
                'ladger_id' => $this->db->insert_id(),
                'account' => $data['account'],
                'balance' => $opening_balance,
                'current_opening_balance' => $opening_balance,
                'current_closing_balance' => $opening_balance
            );
            $this->db->insert('ladger_account_detail', $ladger_account_detail);
            return $ladger_account_detail['ladger_id'];
        }
    }

    //backup
    public function saveLedger_bk_23_01_2017($data = NULL) {
        if ($data['account'] == 'Dr') {
            $opening_balance = $data['opening_balance'];
        }
        if ($data['account'] == 'Cr') {
            if ($data['opening_balance'] != 0) {
                $opening_balance = 0 - $data['opening_balance'];
            } else {
                $opening_balance = $data['opening_balance'];
            }
        }
        if ($data['ledger_id'] != 0) {

            //get opening balance
            $this->db->select('ladger.*');
            $this->db->from('ladger');
            $this->db->where('id', $data['ledger_id']);
            $query = $this->db->get();
            $ledger = $query->result_array();
//            if ($ledger[0]['opening_balance'] > 0) {
            $last_opening_balance = ($opening_balance - $ledger[0]['opening_balance']);
            $last_closing_balance = ($ledger[0]['current_balance'] + $last_opening_balance);
            //}
            $ledger = array(
                'group_id' => $data['group_id'],
                //'opening_balance' => $data['opening_balance'],
                'opening_balance' => $opening_balance,
                // Newly added 16022016
                'last_opening_balance' => $opening_balance,
                'current_balance' => $last_closing_balance,
                'account_type' => $data['account'],
                'reconciliation' => $data['reconciliation'],
                'cash/bank' => $data['cash/bank'],
                'ladger_name' => $data['ladger_name'],
                'mailing_name' => $data['mailing_name'],
                'street_address' => $data['street_address'],
                'country' => $data['country'],
                'state' => $data['state'],
                'city_name' => $data['city_name'],
                'zip_code' => $data['zip_code'],
                'pan_it_no' => $data['pan_it_no'],
                'sale_tax_no' => $data['sale_tax_no'],
                'cst_no' => $data['cst_no'],
                'contact_person' => $data['contact_person'],
                'contact_person2' => $data['contact_person2'],
                'email' => $data['email'],
                'email2' => $data['email2'],
                'phone_no' => $data['phone_no'],
                'phone_no2' => $data['phone_no2'],
                'designation' => $data['designation'],
                'designation2' => $data['designation2'],
                'tracking_status' => $data['tracking_status'],
                'bill_details_status' => $data['bill_details_status'],
                'ledger_code' => $data['ledger_code']
            );
            $this->db->where('id', $data['ledger_id']);
            $this->db->update('ladger', $ledger);
            //get all transuction acoding to ledger id
            $where = array(
                'ladger_account_detail.status' => 1,
                'ladger_account_detail.deleted' => 0,
                'ladger_account_detail.ladger_id' => $data['ledger_id']
            );
            $this->db->select('ladger_account_detail.*');
            $this->db->from('ladger_account_detail');
            $this->db->where($where);
            $query = $this->db->get();
            $entry_details = $query->result_array();
            //echo '<pre>';print_r($entry_details);exit;
            $entry_detail_opening_balance = 0;
            $last_opening_balance = 0;
            for ($i = 0; $i < count($entry_details); $i++) {

                if ($last_opening_balance == 0) {
                    $last_opening_balance = $opening_balance;
                }
                if ($entry_details[$i]['account'] == 'Dr') {
                    $entry_detail_opening_balance = $last_opening_balance;
                    $entry_balance = $last_opening_balance + $entry_details[$i]['balance'];


                    $last_opening_balance = $entry_balance;
                }
                if ($entry_details[$i]['account'] == 'Cr') {
                    $entry_detail_opening_balance = $last_opening_balance;
                    $entry_balance = $last_opening_balance - $entry_details[$i]['balance'];

                    $last_opening_balance = $entry_balance;
                }
                if ($entry_details[$i]['entry_id'] == 0) {
                    $balance = $opening_balance;
                } else {
                    $balance = $entry_details[$i]['balance'];
                }

                $update_data = array(
                    'balance' => $balance,
                    'current_opening_balance' => $entry_detail_opening_balance,
                    'modified_date' => date("Y/m/d"),
                    'current_closing_balance' => $last_opening_balance,
                    'account' => $data['account'],
                );
                $this->db->where('id', $entry_details[$i]['id']);
                $this->db->update('ladger_account_detail', $update_data);
            }
        } else {
            $ledger = array(
                'group_id' => $data['group_id'],
                'opening_balance' => $opening_balance,
                'current_balance' => $opening_balance,
                'reconciliation' => $data['reconciliation'],
                'cash/bank' => $data['cash/bank'],
                'ladger_name' => $data['ladger_name'],
                'account_type' => $data['account'],
                'mailing_name' => $data['mailing_name'],
                'street_address' => $data['street_address'],
                'country' => $data['country'],
                'state' => $data['state'],
                'city_name' => $data['city_name'],
                'zip_code' => $data['zip_code'],
                'pan_it_no' => $data['pan_it_no'],
                'sale_tax_no' => $data['sale_tax_no'],
                'cst_no' => $data['cst_no'],
                'contact_person' => $data['contact_person'],
                'contact_person2' => $data['contact_person2'],
                'email' => $data['email'],
                'email2' => $data['email2'],
                'phone_no' => $data['phone_no'],
                'phone_no2' => $data['phone_no2'],
                'designation' => $data['designation'],
                'designation2' => $data['designation2'],
                'tracking_status' => $data['tracking_status'],
                'bill_details_status' => $data['bill_details_status'],
                'ledger_code' => $data['ledger_code']
            );

            $this->db->insert('ladger', $ledger);

            $ladger_account_detail = array(
                'ladger_id' => $this->db->insert_id(),
                'account' => $data['account'],
                'balance' => $opening_balance,
                'current_opening_balance' => $opening_balance,
                'current_closing_balance' => $opening_balance
            );
            $this->db->insert('ladger_account_detail', $ladger_account_detail);
            return $ladger_account_detail['ladger_id'];
        }
    }

    public function updateLedgerCode($data = NULL, $last_id = NULL) {
        $this->db->where('id', $last_id);
        $this->db->update('ladger', $data);
    }

    public function getLedgerName($where) {
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('ladger');
        return $query->result_array();
    }

    public function getAllGroupNameForJson($where, $group) {
        $this->db->select('group.group_name');
        $this->db->from('group');
        $this->db->like('group_name', $group, 'after');
        $this->db->where($where);
        $query = $this->db->get();
        $list = $query->result_array();
        return !empty($list) ? $list : array();
    }

    public function getAllLedgerNameForJson($where, $ledger) {
        $this->db->select('ladger.ladger_name, ladger.id');
        $this->db->from('ladger');
        $this->db->like('ladger_name', $ledger, 'after');
        $this->db->where($where);
        $query = $this->db->get();
        $list = $query->result_array();
        return !empty($list) ? $list : array();
    }

    public function getGroupId($group) {
        $record = $this->db->select('id')
                ->from('group')
                ->like('group_name', $group)
                ->get();
        return $record->row_array();
    }

    public function delete($id = NULL, $data = array()) {
        $this->db->where('id', $id);
        $this->db->update('ladger', $data);

        $this->db->where('ladger_id', $id);
        $this->db->update('ladger_account_detail', $data);
        return;
    }

    public function getCashBankLedgerNameForJson($where, $ledger) {
        $this->db->select('ladger.ladger_name');
        $this->db->from('ladger');
        $this->db->like('ladger_name', $ledger, 'after');
        $this->db->where($where);
        $this->db->where('group_id', 10);
        $this->db->or_where('group_id', 11);
        $query = $this->db->get();
        $list = $query->result_array();
        return !empty($list) ? $list : array();
    }

    public function getLedgerIdByName($ledeger_name) {
        $this->db->select('id');
        $this->db->where('ladger_name', $ledeger_name);
        $this->db->where('status', '1');
        $this->db->where('deleted', '0');
        $query = $this->db->get('ladger');

        return $query->row_array();
    }

    //check entry existance
    public function checkLedgerExist($ledger_id) {
        $query = $this->db->select('id')
                ->from(tablename('ladger_account_detail'))
                ->where('ladger_id =', $ledger_id)
                ->where('entry_id !=', 0)
                ->where('status', 1)
                ->where('deleted', 0)
                ->get();
        return $row = $query->result();
    }

    public function deleteLadger($ledger_id) {
        $this->db->where('id', $ledger_id);
        $ledger = $this->db->get('ladger')->row_array();
        $log = array(
            'user_id' => $this->session->userdata('admin_uid'),
            'branch_id' => $this->session->userdata('branch_id'),
            'module' => 'ledger',
            'action' => '`' . $ledger['ladger_name'] . '` <b>deleted</b>',
            'previous_data' => json_encode($ledger),
            'performed_at' => date('Y-m-d H:i:s', time())
        );
        $this->currentusermodel->updateLog($log);

        $this->db->where('id', $ledger_id);
        return $this->db->update('ladger', array('deleted' => 1));
    }

    public function discontinueLadger($ledger_id) {
        $this->db->where('id', $ledger_id);
        $ledger = $this->db->get('ladger')->row_array();
        $log = array(
            'user_id' => $this->session->userdata('admin_uid'),
            'branch_id' => $this->session->userdata('branch_id'),
            'module' => 'ledger',
            'action' => '`' . $ledger['ladger_name'] . '` <b>discontinued</b>',
            'previous_data' => json_encode($ledger),
            'performed_at' => date('Y-m-d H:i:s', time())
        );
        $this->currentusermodel->updateLog($log);

        $this->db->where('id', $ledger_id);
        return $this->db->update('ladger', array('discontinue' => 1));
    }

    public function getAllSubGroup($array_group) {
        $total_group_array = array();
        $total_group_array = array_merge($total_group_array, $array_group);
        for ($i = 0; $i < count($array_group); $i++) {
            $sql = "SELECT GROUP_CONCAT(lv SEPARATOR ',') as ids FROM ( SELECT @pv:=(SELECT GROUP_CONCAT(id SEPARATOR ',') FROM pb_group WHERE FIND_IN_SET(parent_id,@pv)) AS lv FROM pb_group JOIN (SELECT @pv:=" . $array_group[$i] . ") tmp ) a";
            $query = $this->db->query($sql);
            $res = $query->row();
            if (!empty($res->ids)) {
                $ids = explode(',', $res->ids);
                $total_group_array = array_merge($total_group_array, $ids);
            }
        }
        return $total_group_array;
    }

    public function updateCustomerAddress($contact_id, $ledger_id) {

        $this->db->where('ledger_id', $ledger_id);
        $this->db->update('customer_details', array('ledger_id' => 0));

        $data = array('ledger_id' => $ledger_id);
        $this->db->where('id', $contact_id);
        return $this->db->update('customer_details', $data);
    }
    
    public function updateShippingAddress($contact_id, $ledger_id) {
        $data = array('users_id' => $ledger_id);
        $this->db->where('customer_details_id', $contact_id);
        return $this->db->update('shipping_address', $data);
    }
    
     public function getContactsByLedgerId($ledger_id) {
        $this->db->select('id,company_name');
        $this->db->where('ledger_id', $ledger_id);
        $this->db->where('status', '1');
        $query = $this->db->get('customer_details');
        return $query->row();
    }
    
    public function getOpeningBalance(){
        $this->db->select('id,user_id,SUM(price) AS price,branch_id');
        $this->db->where_in('branch_id', $this->session->userdata('selected_branch'),FALSE); //if using branch concept
        $query = $this->db->get('opening_cost');
        return $query->row(); //if using branch concept
//        return $query->first_row(); //else not using branch concept
    }

    public function getBankDetailsCompany($ledger_id)
    {
        $this->db->where('ledger_id', $ledger_id);
        return $this->db->get('bank_details_company')->row();
    }
    
    public function getNoOfVoucherById($ledger_id){
        $this->db->where('ladger_id', $ledger_id);
        $this->db->where('status', 1);
        $this->db->where('deleted', 0);
        $this->db->where('entry_id !=', 0);
        return $this->db->get('ladger_account_detail')->num_rows();
    }

}

?>
