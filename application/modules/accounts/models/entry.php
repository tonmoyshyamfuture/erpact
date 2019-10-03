<?php

class entry extends CI_Model {

    public function __construct() {

        parent::__construct();
        $this->load->database();
        $this->load->model('front/usermodel', 'currentusermodel');
    }

    public function getAllLedgerName($where) {
        $this->db->select('ladger.id, ladger.ladger_name');
        $this->db->from('ladger');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function allEntryType($where) {
        $this->db->select('entry_type.id, entry_type.type');
        $this->db->from('entry_type');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllEntry($id, $parent_id, $from_date, $to_date, $limit=10, $offset=0,$search='') {
        $this->db->select("entry.*, entry_type.type, GROUP_CONCAT(CONCAT(l.ladger_name, ' [',ld.account, ']') SEPARATOR '/') as ledger_detail", FALSE);
        $this->db->from('entry');        
        $this->db->join('entry_type', 'entry_type.id = entry.entry_type_id', 'left');

        /* ========= */

        $this->db->join('ladger_account_detail as ld', 'ld.entry_id = entry.id', 'left');
        $this->db->join('ladger as l', 'l.id = ld.ladger_id', 'left');

        /* ========= */

        if ($parent_id == 0) {
            $this->db->where('entry.sub_voucher', $parent_id);
            $this->db->where('entry.entry_type_id', $id);
        } else {
            $this->db->where('entry.sub_voucher', $id);
            $this->db->where('entry.entry_type_id', $parent_id);
        }

        $this->db->where('entry.company_id', $this->session->userdata('branch_id'));
        $this->db->where('entry.status', 1);
        $this->db->where('entry.deleted', 0);
        $this->db->where('ld.deleted', 0);
        $this->db->where('entry.is_inventry', 0);
        $this->db->where('entry.create_date <=', $to_date);
        $this->db->where('entry.create_date >=', $from_date);
        $this->db->like('entry.entry_no', $search);
        $this->db->group_by('entry.id');
        $this->db->order_by("entry.create_date", "asc");
        if ($limit != 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllPostDatedEntry($id, $parent_id, $from_date, $to_date, $limit=10, $offset=0,$search='') {
        $this->db->select('entry.*, entry_type.type');
        $this->db->from('entry');
        $this->db->order_by("entry.id", "asc");
        $this->db->join('entry_type', 'entry_type.id = entry.entry_type_id', 'left');
        if ($parent_id == 0) {
            $this->db->where('entry.sub_voucher', $parent_id);
            $this->db->where('entry.entry_type_id', $id);
        } else {
            $this->db->where('entry.sub_voucher', $id);
            $this->db->where('entry.entry_type_id', $parent_id);
        }

        $this->db->where('entry.company_id', $this->session->userdata('branch_id'));
        $this->db->where('entry.status', 1);
        $this->db->where('entry.deleted', 2);
        $this->db->where('entry.is_inventry', 0);
        $this->db->where('entry.create_date <=', $to_date);
        $this->db->where('entry.create_date >=', $from_date);
        $this->db->like('entry.entry_no', $search);
        if ($limit != 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllRecurringEntry($id, $parent_id, $from_date, $to_date, $limit=10, $offset=0,$search='') {
        $this->db->select('entry.*, entry_type.type,recurring_entry.frequency');
        $this->db->from('recurring_entry');

        $this->db->join('entry', 'entry.id = recurring_entry.entry_id', 'left');
        $this->db->join('entry_type', 'entry_type.id = entry.entry_type_id', 'left');
        if ($parent_id == 0) {
            $this->db->where('entry.sub_voucher', $parent_id);
            $this->db->where('entry.entry_type_id', $id);
        } else {
            $this->db->where('entry.sub_voucher', $id);
            $this->db->where('entry.entry_type_id', $parent_id);
        }

        $this->db->where('entry.company_id', $this->session->userdata('branch_id'));
        $this->db->where('entry.status', 1);
        $this->db->where('entry.deleted', 0);
        $this->db->where('entry.is_inventry', 0);
        $this->db->where('entry.create_date <=', $to_date);
        $this->db->where('entry.create_date >=', $from_date);
        $this->db->where("recurring_entry.status", '1');
        $this->db->like('entry.entry_no', $search);
        $this->db->order_by("entry.id", "asc");
        $this->db->group_by("recurring_entry.entry_id");
        if ($limit != 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllEntrySubVoucher($where) {
        $this->db->select('entry.*, entry_type.type,sub_voucher.sub_voucher as sub_voucher_name');
        $this->db->from('entry');
        
        $this->db->order_by("entry.id", "desc");
        $this->db->join('entry_type', 'entry_type.id = entry.entry_type_id', 'left');
        $this->db->join('sub_voucher', 'sub_voucher.id = entry.sub_voucher');
        $this->db->where('entry.company_id', $this->session->userdata('branch_id'));
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getEntryById($where) {
        $this->db->select('entry.*,entry_type.type');
        $this->db->from('entry');
        $this->db->join('entry_type', 'entry_type.id = entry.entry_type_id', 'left');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllEntryDetailById($where) {
//        $this->db->select('ladger_account_detail.ladger_id, ladger_account_detail.account, ladger_account_detail.balance, ladger.current_balance');
        //$this->db->select('ladger_account_detail.ladger_id, ladger_account_detail.account, ladger_account_detail.balance,ladger_account_detail.narration, ladger.current_balance,ladger.ladger_name'); //Sudip
        $this->db->select('ladger_account_detail.selected_currency,ladger_account_detail.unit_price,ladger_account_detail.ladger_id, ladger_account_detail.account, ladger_account_detail.balance,ladger_account_detail.narration, ladger.current_balance,ladger.ladger_name'); //Sudip 18/07/2016
        $this->db->from('ladger_account_detail');
        $this->db->join('ladger', 'ladger.id = ladger_account_detail.ladger_id', 'left');
        $this->db->where($where)->order_by('ladger_account_detail.id', "asc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function last_balance($where) {
        $this->db->select('ladger.id, ladger.current_balance');
        $this->db->from('ladger');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function fildUpdate($led_id, $data) {
        $ledger_id = $led_id;
        $this->db->where('id', $ledger_id);
        $this->db->update('ladger', $data);
    }

    public function saveEntry($data = NULL) {
        $selected_currency_id = $data['selected_currency'];
        $base_unit = $data['base_unit'];
        $account = $data['account'];
        $amounts = $data['amounts'];
        $order_id = isset($data['order_id']) ? $data['order_id'] : '';
        $ledger_id = $data['ledger_id'];
        $count = count($data['account']);
        $newdate = str_replace("/", "-", $data['create_date']);
        $createDare = date('Y-m-d', strtotime($newdate));
        $entry_number = $data['entry_no'];
        //generate auto entry no
        if ($entry_number == '' || $entry_number == null) {
            $entry_type_id = $this->input->post('entry_type_id');
            $entry_type = $this->entry->getEntryTypeById($entry_type_id);
            $countid = 1;
            $today = date("Y-m-d H:i:s");
            $auto_number = $this->entry->getNoOfByTypeId($entry_type_id, $today, $entry_type['strating_date']);
            $start_length = $entry_type['starting_entry_no'];
            $countid = $countid + $auto_number['total_transaction'];
            $id_length = strlen($countid);
            if ($start_length > $id_length) {
                $remaining = $start_length - $id_length;
                $uniqueid = $entry_type['prefix_entry_no'] . str_repeat("0", $remaining) . $countid . $entry_type['suffix_entry_no'];
            } else {
                $uniqueid = $entry_type['prefix_entry_no'] . $countid . $entry_type['suffix_entry_no'];
            }
            $entry_number = $uniqueid;
        }
        //end generate auto entry no
        $new_array = array();
        for ($i = 0; $i < $count; $i++) {
            $this->db->select('ladger.ladger_name, ladger.current_balance');
            $this->db->from('ladger');
            $this->db->where(array('ladger.id' => $ledger_id[$i]));
            $query = $this->db->get();
            $ee = $query->result_array();
            $new_array[$account[$i]][] = $ee[0]['ladger_name'];
            //echo '<pre>';print_r($ee);exit;
            //calculate current balance
            $operater = substr($ee[0]['current_balance'], 0, 1);

            if ($account[$i] == 'Dr') {
                $new_balance = 0;
                $crnt_bal = $ee[0]['current_balance'];
                $new_balance = ($crnt_bal + ($amounts[$i] * $base_unit));
                $transaction_details = $this->sumBalance($ledger_id[$i]);
                $opening_balance_arr = $this->openingBalance($ledger_id[$i]);
                $cr_sum = 0;
                $dr_sum = 0;
                if ($transaction_details) {
                    $cr_sum = $transaction_details[0]->cr_balance;
                    $dr_sum = $transaction_details[0]->dr_balance;
                }

                $current_balance = (($opening_balance_arr->opening_balance + $dr_sum) - $cr_sum);


                $this->db->where('id', $ledger_id[$i]);
                $this->db->update('ladger', array('last_opening_balance' => $ee[0]['current_balance'], 'current_balance' => $current_balance));
            }
            if ($account[$i] == 'Cr') {
                $new_balance = 0;
                $new_balance = ($ee[0]['current_balance'] - ($amounts[$i] * $base_unit));

                $transaction_details = $this->sumBalance($ledger_id[$i]);
                $opening_balance_arr = $this->openingBalance($ledger_id[$i]);
                $cr_sum = 0;
                $dr_sum = 0;
                if ($transaction_details) {
                    $cr_sum = $transaction_details[0]->cr_balance;
                    $dr_sum = $transaction_details[0]->dr_balance;
                }

                $current_balance = (($opening_balance_arr->opening_balance + $cr_sum) - $dr_sum);

                $this->db->where('id', $ledger_id[$i]);
                $this->db->update('ladger', array('last_opening_balance' => $ee[0]['current_balance'], 'current_balance' => $current_balance));
            }
        }

        $ledger_name_json = json_encode($new_array);
        $entry = array(
            'entry_no' => $entry_number,
            'create_date' => $createDare,
            'ledger_ids_by_accounts' => $ledger_name_json,
            'dr_amount' => ($this->input->post('sum_dr') * $base_unit),
            'cr_amount' => ($this->input->post('sum_cr') * $base_unit),
            'unit_price_dr' => $this->input->post('sum_dr'),
            'unit_price_cr' => $this->input->post('sum_cr'),
            'entry_type_id' => $this->input->post('entry_type_id'),
            'sub_voucher' => $this->input->post('sub_voucher'),
            'user_id' => $this->session->userdata('user_id'),
            'is_inventry' => $this->input->post('is_inventry'),
            'order_id' => $order_id,
        );

        $this->db->insert('entry', $entry);
        $entry_id = $this->db->insert_id();

        for ($j = 0; $j < $count; $j++) {
            //get closing balance
            $this->db->select('ladger.current_balance');
            $this->db->from('ladger');
            $this->db->where(array('ladger.id' => $ledger_id[$j]));
            $query = $this->db->get();
            $closing_balance = $query->result_array();
            //echo '<pre>';print_r($closing_balance);exit;
            if ($account[$j] == 'Dr') {
                $current_opening_balance = 0;
                $current_opening_balance = ($closing_balance[0]['current_balance'] - ($amounts[$j] * $base_unit));
                $transaction_details = $this->sumBalance($ledger_id[$j]);
                $opening_balance_arr = $this->openingBalance($ledger_id[$j]);
                $cr_sum = 0;
                $dr_sum = 0;
                if ($transaction_details) {
                    $cr_sum = $transaction_details[0]->cr_balance;
                    $dr_sum = $transaction_details[0]->dr_balance;
                }

                $current_balance = (($opening_balance_arr->opening_balance + $dr_sum) - $cr_sum);
                $data = array(
                    'account' => $account[$j],
                    'balance' => ($amounts[$j] * $base_unit),
                    'ladger_id' => $ledger_id[$j],
                    'entry_id' => $entry_id,
                    'current_opening_balance' => $current_opening_balance,
                    'current_closing_balance' => $current_balance,
                    'create_date' => $createDare,
                    'narration' => $data['narration'],
                    'selected_currency' => $selected_currency_id,
                    'unit_price' => $amounts[$j],
                );
                $this->db->insert('ladger_account_detail', $data);
            }
            if ($account[$j] == 'Cr') {
                $current_opening_balance = 0;
                $current_opening_balance = ($closing_balance[0]['current_balance'] + ($amounts[$j] * $base_unit));
                $transaction_details = $this->sumBalance($ledger_id[$j]);
                $opening_balance_arr = $this->openingBalance($ledger_id[$j]);
                $cr_sum = 0;
                $dr_sum = 0;
                if ($transaction_details) {
                    $cr_sum = $transaction_details[0]->cr_balance;
                    $dr_sum = $transaction_details[0]->dr_balance;
                }

                $current_balance = (($opening_balance_arr->opening_balance + $cr_sum) - $dr_sum);
                $data = array(
                    'account' => $account[$j],
                    'balance' => ($amounts[$j] * $base_unit),
                    'ladger_id' => $ledger_id[$j],
                    'entry_id' => $entry_id,
                    'current_opening_balance' => $current_opening_balance,
                    'current_closing_balance' => $current_balance,
                    'create_date' => $createDare,
                    'narration' => $data['narration'],
                    'selected_currency' => $selected_currency_id,
                    'unit_price' => $amounts[$j],
                );
                $this->db->insert('ladger_account_detail', $data);
            }
            //create voucher for related to order
            if (isset($ledger_id[$j]) && $ledger_id[$j] && $order_id) {
                $this->db->select('*');
                $this->db->from('ladger');
                $this->db->where('id =', $ledger_id[$j]);
                $query = $this->db->get();
                $ledger_result = $query->row();

                if (isset($ledger_result) && $ledger_result->bill_details_status == 1) {
                    if ($ledger_result->credit_date == '') {
                        $credit_days = 0;
                    } else {
                        $credit_days = $ledger_result->credit_date;
                    }
                    $bill_data = array(
                        'ledger_id' => $ledger_id[$j],
                        'dr_cr' => $ledger_result->account_type,
                        'ref_type' => 'New Reference',
                        'bill_name' => $entry_number,
                        'credit_days' => $ledger_result->credit_date,
                        'credit_date' => date('Y-m-d', strtotime("+" . $credit_days . " days")),
                        'bill_amount' => ($amounts[$j] * $base_unit),
                        'entry_id' => $entry_id,
                        'created_date' => date('Y-m-d'),
                    );
                    $this->db->insert('billwish_details', $bill_data);
                }
            }
            //end create voucher related to oreder
        }
        //data set tracking details 
        $this->db->select('tracking_temp.*,tracking.id as tracking_id');
        $this->db->from('tracking_temp');
        $this->db->join('tracking', 'tracking.tracking_name = tracking_temp.tracking_name');
        $query = $this->db->get();
        $tracking_temp_data = $query->result_array();
        if (!empty($tracking_temp_data)) {
            $tracking_details_array = array();
            for ($k = 0; $k < count($tracking_temp_data); $k++) {
                $tracking_details_array[$k]['tracking_id'] = $tracking_temp_data[$k]['tracking_id'];
                $tracking_details_array[$k]['ledger_id'] = $tracking_temp_data[$k]['ledger_id'];
                $tracking_details_array[$k]['account_type'] = $tracking_temp_data[$k]['account_type'];
                $tracking_details_array[$k]['tracking_amount'] = $tracking_temp_data[$k]['tracking_amount'];
                $tracking_details_array[$k]['entry_id'] = $entry_id;
                $tracking_details_array[$k]['created_date'] = date('Y-m-d');
            }
            $this->db->insert_batch('tracking_details', $tracking_details_array);
        }

        //data set bill details 
        $this->db->select('billwish_temp.*');
        $this->db->from('billwish_temp');
        $query_bill = $this->db->get();
        $bill_temp_data = $query_bill->result_array();
        if (!empty($bill_temp_data)) {
            $bill_details_array = array();
            for ($k = 0; $k < count($bill_temp_data); $k++) {
                $bill_details_array[$k]['ledger_id'] = $bill_temp_data[$k]['ledger_id'];
                $bill_details_array[$k]['dr_cr'] = $bill_temp_data[$k]['dr_cr'];
                $bill_details_array[$k]['ref_type'] = $bill_temp_data[$k]['ref_type'];
                $bill_details_array[$k]['bill_name'] = $bill_temp_data[$k]['bill_name'];
                $bill_details_array[$k]['credit_days'] = $bill_temp_data[$k]['credit_days'];
                $bill_details_array[$k]['credit_date'] = date('Y-m-d', strtotime($bill_temp_data[$k]['credit_date']));
                $bill_details_array[$k]['bill_amount'] = $bill_temp_data[$k]['bill_amount'];
                $bill_details_array[$k]['entry_id'] = $entry_id;
                $bill_details_array[$k]['created_date'] = date('Y-m-d');
            }
            $this->db->insert_batch('billwish_details', $bill_details_array);
        }
        //save bank details
        $this->db->select('*');
        $this->db->from('temp_bank_details');
        $this->db->where('entry_no =', $entry_number);
        $query_bank = $this->db->get();
        $temp_bank_data = $query_bank->result();
        if (!empty($temp_bank_data)) {
            $bank_details_array = array();
            for ($k = 0; $k < count($temp_bank_data); $k++) {
                $bank_details_array[$k]['entry_id'] = $entry_id;
                $bank_details_array[$k]['ledger_id'] = $temp_bank_data[$k]->ledger_id;
                $bank_details_array[$k]['transaction_type'] = $temp_bank_data[$k]->transaction_type;
                $bank_details_array[$k]['instrument_no'] = $temp_bank_data[$k]->instrument_no;
                $bank_details_array[$k]['instrument_date'] = $temp_bank_data[$k]->instrument_date;
                $bank_details_array[$k]['bank_name'] = $temp_bank_data[$k]->bank_name;
                $bank_details_array[$k]['branch_name'] = $temp_bank_data[$k]->branch_name;
                $bank_details_array[$k]['ifsc_code'] = $temp_bank_data[$k]->ifsc_code;
                $bank_details_array[$k]['bank_amount'] = $temp_bank_data[$k]->bank_amount;
                $bank_details_array[$k]['create_date'] = date("Y-m-d H:i:s");
            }
            $this->db->insert_batch('bank_details', $bank_details_array);
        }
        $this->db->where('entry_no =', $entry_number);
        $this->db->delete('temp_bank_details');
        //end save bank details
        if ($entry_id) {
            return $entry_id;
        } else {
            return '';
        }
    }

    public function saveRequestEntry($data = NULL) {
        $selected_currency_id = $data['selected_currency'];
        $base_unit = $data['base_unit'];
        $account = $data['account'];
        $amounts = $data['amounts'];
        $order_id = $data['order_id'];
        $ledger_id = $data['ledger_id'];
        $count = count($data['account']);
        $newdate = str_replace("/", "-", $data['create_date']);
        $createDare = date('Y-m-d', strtotime($newdate));

        $new_array = array();
        for ($i = 0; $i < $count; $i++) {
            $this->db->select('ladger.ladger_name, ladger.current_balance');
            $this->db->from('ladger');
            $this->db->where(array('ladger.id' => $ledger_id[$i]));
            $query = $this->db->get();
            $ee = $query->result_array();
            $new_array[$account[$i]][] = $ee[0]['ladger_name'];
            //echo '<pre>';print_r($ee);exit;
            //calculate current balance
            $operater = substr($ee[0]['current_balance'], 0, 1);

            if ($account[$i] == 'Dr') {
                $new_balance = 0;
                $crnt_bal = $ee[0]['current_balance'];
                $new_balance = ($crnt_bal + ($amounts[$i] * $base_unit));
                $transaction_details = $this->sumBalance($ledger_id[$i]);
                $opening_balance_arr = $this->openingBalance($ledger_id[$i]);
                $cr_sum = 0;
                $dr_sum = 0;
                if ($transaction_details) {
                    $cr_sum = $transaction_details[0]->cr_balance;
                    $dr_sum = $transaction_details[0]->dr_balance;
                }

                $current_balance = (($opening_balance_arr->opening_balance + $dr_sum) - $cr_sum);


                $this->db->where('id', $ledger_id[$i]);
                $this->db->update('ladger', array('last_opening_balance' => $ee[0]['current_balance'], 'current_balance' => $current_balance));
            }
            if ($account[$i] == 'Cr') {
                $new_balance = 0;
                $new_balance = ($ee[0]['current_balance'] - ($amounts[$i] * $base_unit));

                $transaction_details = $this->sumBalance($ledger_id[$i]);
                $opening_balance_arr = $this->openingBalance($ledger_id[$i]);
                $cr_sum = 0;
                $dr_sum = 0;
                if ($transaction_details) {
                    $cr_sum = $transaction_details[0]->cr_balance;
                    $dr_sum = $transaction_details[0]->dr_balance;
                }

                $current_balance = (($opening_balance_arr->opening_balance + $cr_sum) - $dr_sum);

                //$this->db->where('id', $ledger_id[$i]);
                //$this->db->update('ladger', array('last_opening_balance' => $ee[0]['current_balance'], 'current_balance' => $current_balance));
            }
        }

        $ledger_name_json = json_encode($new_array);
        $entry = array(
            'entry_no' => $data['entry_no'],
            'create_date' => $createDare,
            'ledger_ids_by_accounts' => $ledger_name_json,
            'dr_amount' => ($this->input->post('sum_dr') * $base_unit),
            'cr_amount' => ($this->input->post('sum_cr') * $base_unit),
            'unit_price_dr' => $this->input->post('sum_dr'),
            'unit_price_cr' => $this->input->post('sum_cr'),
            'entry_type_id' => $this->input->post('entry_type_id'),
            'sub_voucher' => $this->input->post('sub_voucher'),
            'user_id' => $this->session->userdata('user_id'),
            'is_inventry' => $this->input->post('is_inventry'),
            'order_id' => $order_id,
        );

        $this->db->insert('pb_entry_request', $entry);
        $entry_id = $this->db->insert_id();

        for ($j = 0; $j < $count; $j++) {
            //get closing balance
            $this->db->select('ladger.current_balance');
            $this->db->from('ladger');
            $this->db->where(array('ladger.id' => $ledger_id[$j]));
            $query = $this->db->get();
            $closing_balance = $query->result_array();
            //echo '<pre>';print_r($closing_balance);exit;
            if ($account[$j] == 'Dr') {
                $current_opening_balance = 0;
                $current_opening_balance = ($closing_balance[0]['current_balance'] - ($amounts[$j] * $base_unit));
                $transaction_details = $this->sumBalance($ledger_id[$j]);
                $opening_balance_arr = $this->openingBalance($ledger_id[$j]);
                $cr_sum = 0;
                $dr_sum = 0;
                if ($transaction_details) {
                    $cr_sum = $transaction_details[0]->cr_balance;
                    $dr_sum = $transaction_details[0]->dr_balance;
                }

                $current_balance = (($opening_balance_arr->opening_balance + $dr_sum) - $cr_sum);
                $data = array(
                    'account' => $account[$j],
                    'balance' => ($amounts[$j] * $base_unit),
                    'ladger_id' => $ledger_id[$j],
                    'entry_id' => $entry_id,
                    'current_opening_balance' => $current_opening_balance,
                    'current_closing_balance' => $current_balance,
                    'create_date' => $createDare,
                    'narration' => $data['narration'],
                    'selected_currency' => $selected_currency_id,
                    'unit_price' => $amounts[$j],
                );
                $this->db->insert('pb_entry_request_details', $data);
            }
            if ($account[$j] == 'Cr') {
                $current_opening_balance = 0;
                $current_opening_balance = ($closing_balance[0]['current_balance'] + ($amounts[$j] * $base_unit));
                $transaction_details = $this->sumBalance($ledger_id[$j]);
                $opening_balance_arr = $this->openingBalance($ledger_id[$j]);
                $cr_sum = 0;
                $dr_sum = 0;
                if ($transaction_details) {
                    $cr_sum = $transaction_details[0]->cr_balance;
                    $dr_sum = $transaction_details[0]->dr_balance;
                }

                $current_balance = (($opening_balance_arr->opening_balance + $cr_sum) - $dr_sum);
                $data = array(
                    'account' => $account[$j],
                    'balance' => ($amounts[$j] * $base_unit),
                    'ladger_id' => $ledger_id[$j],
                    'entry_id' => $entry_id,
                    'current_opening_balance' => $current_opening_balance,
                    'current_closing_balance' => $current_balance,
                    'create_date' => $createDare,
                    'narration' => $data['narration'],
                    'selected_currency' => $selected_currency_id,
                    'unit_price' => $amounts[$j],
                );
                $this->db->insert('pb_entry_request_details', $data);
            }
        }
    }

    //save temp entry

    public function saveTempEntry($data = NULL) {
        $selected_currency_id = $data['selected_currency'];
        $base_unit = $data['base_unit'];
        $account = $data['account'];
        $amounts = $data['amounts'];
        $order_id = $data['order_id'];
        $ledger_id = $data['ledger_id'];
        $count = count($data['account']);
        $newdate = str_replace("/", "-", $data['create_date']);
        $createDare = date('Y-m-d');

        $new_array = array();
        for ($i = 0; $i < $count; $i++) {
            $this->db->select('ladger.ladger_name, ladger.current_balance');
            $this->db->from('ladger');
            $this->db->where(array('ladger.id' => $ledger_id[$i]));
            $query = $this->db->get();
            $ee = $query->result_array();
            $new_array[$account[$i]][] = $ee[0]['ladger_name'];
            //echo '<pre>';print_r($ee);exit;
            //calculate current balance
            $operater = substr($ee[0]['current_balance'], 0, 1);

            if ($account[$i] == 'Dr') {
                $new_balance = 0;
                $crnt_bal = $ee[0]['current_balance'];
                $new_balance = ($crnt_bal + ($amounts[$i] * $base_unit));
                $transaction_details = $this->sumBalance($ledger_id[$i]);
                $opening_balance_arr = $this->openingBalance($ledger_id[$i]);
                $cr_sum = 0;
                $dr_sum = 0;
                if ($transaction_details) {
                    $cr_sum = $transaction_details[0]->cr_balance;
                    $dr_sum = $transaction_details[0]->dr_balance;
                }

                $current_balance = (($opening_balance_arr->opening_balance + $dr_sum) - $cr_sum);


                $this->db->where('id', $ledger_id[$i]);
                $this->db->update('ladger', array('last_opening_balance' => $ee[0]['current_balance'], 'current_balance' => $current_balance));
            }
            if ($account[$i] == 'Cr') {
                $new_balance = 0;
                $new_balance = ($ee[0]['current_balance'] - ($amounts[$i] * $base_unit));

                $transaction_details = $this->sumBalance($ledger_id[$i]);
                $opening_balance_arr = $this->openingBalance($ledger_id[$i]);
                $cr_sum = 0;
                $dr_sum = 0;
                if ($transaction_details) {
                    $cr_sum = $transaction_details[0]->cr_balance;
                    $dr_sum = $transaction_details[0]->dr_balance;
                }

                $current_balance = (($opening_balance_arr->opening_balance + $cr_sum) - $dr_sum);

                //$this->db->where('id', $ledger_id[$i]);
                //$this->db->update('ladger', array('last_opening_balance' => $ee[0]['current_balance'], 'current_balance' => $current_balance));
            }
        }

        $ledger_name_json = json_encode($new_array);
        $entry = array(
            'entry_no' => $data['entry_no'],
            'create_date' => $createDare,
            'ledger_ids_by_accounts' => $ledger_name_json,
            'dr_amount' => ($this->input->post('sum_dr') * $base_unit),
            'cr_amount' => ($this->input->post('sum_cr') * $base_unit),
            'unit_price_dr' => $this->input->post('sum_dr'),
            'unit_price_cr' => $this->input->post('sum_cr'),
            'entry_type_id' => $this->input->post('entry_type_id'),
            'sub_voucher' => $this->input->post('sub_voucher'),
            'user_id' => $this->session->userdata('user_id'),
            'is_inventry' => $this->input->post('is_inventry'),
            'order_id' => $order_id,
        );

        $this->db->insert('pb_entry_temp', $entry);
        $entry_id = $this->db->insert_id();

        for ($j = 0; $j < $count; $j++) {
            //get closing balance
            $this->db->select('ladger.current_balance');
            $this->db->from('ladger');
            $this->db->where(array('ladger.id' => $ledger_id[$j]));
            $query = $this->db->get();
            $closing_balance = $query->result_array();
            //echo '<pre>';print_r($closing_balance);exit;
            if ($account[$j] == 'Dr') {
                $current_opening_balance = 0;
                $current_opening_balance = ($closing_balance[0]['current_balance'] - ($amounts[$j] * $base_unit));
                $transaction_details = $this->sumBalance($ledger_id[$j]);
                $opening_balance_arr = $this->openingBalance($ledger_id[$j]);
                $cr_sum = 0;
                $dr_sum = 0;
                if ($transaction_details) {
                    $cr_sum = $transaction_details[0]->cr_balance;
                    $dr_sum = $transaction_details[0]->dr_balance;
                }

                $current_balance = (($opening_balance_arr->opening_balance + $dr_sum) - $cr_sum);
                $data = array(
                    'account' => $account[$j],
                    'balance' => ($amounts[$j] * $base_unit),
                    'ladger_id' => $ledger_id[$j],
                    'entry_id' => $entry_id,
                    'current_opening_balance' => $current_opening_balance,
                    'current_closing_balance' => $current_balance,
                    'create_date' => $createDare,
                    'narration' => $data['narration'],
                    'selected_currency' => $selected_currency_id,
                    'unit_price' => $amounts[$j],
                );
                $this->db->insert('pb_entry_temp_details', $data);
            }
            if ($account[$j] == 'Cr') {
                $current_opening_balance = 0;
                $current_opening_balance = ($closing_balance[0]['current_balance'] + ($amounts[$j] * $base_unit));
                $transaction_details = $this->sumBalance($ledger_id[$j]);
                $opening_balance_arr = $this->openingBalance($ledger_id[$j]);
                $cr_sum = 0;
                $dr_sum = 0;
                if ($transaction_details) {
                    $cr_sum = $transaction_details[0]->cr_balance;
                    $dr_sum = $transaction_details[0]->dr_balance;
                }

                $current_balance = (($opening_balance_arr->opening_balance + $cr_sum) - $dr_sum);
                $data = array(
                    'account' => $account[$j],
                    'balance' => ($amounts[$j] * $base_unit),
                    'ladger_id' => $ledger_id[$j],
                    'entry_id' => $entry_id,
                    'current_opening_balance' => $current_opening_balance,
                    'current_closing_balance' => $current_balance,
                    'create_date' => $createDare,
                    'narration' => $data['narration'],
                    'selected_currency' => $selected_currency_id,
                    'unit_price' => $amounts[$j],
                );
                $this->db->insert('pb_entry_temp_details', $data);
            }
        }
    }

    public function updateEntry($data = NULL) {
        //  echo '<pre/>';
        //   print_r($data);
        //   exit;
        $entry_id = $this->input->post('entry_id');
        //echo $entry_id;exit; 
        //Start:Return ledger amount
        //End:Return ledger amount
        $newdate = str_replace("/", "-", $data['create_date']);
//echo date('Y-m-d', strtotime($date1));
        //$newdate=$data['create_date'];

        $selected_currency_id = $data['selected_currency'];
        $base_unit = $data['base_unit'];
        $createDate = date('Y-m-d', strtotime($newdate));
        $account = $data['account'];
        $amounts = $data['amounts'];
        $ledger_id = $data['ledger_id'];
        $count = count($data['account']);
        $narration = $data['narration'];
        $order_id = isset($data['order_id']) ? $data['order_id'] : '';
        $entry_number = $data['entry_no'];
        //echo '<pre/>';print_r($ledger_id);exit;
        $new_array = array();
        for ($i = 0; $i < $count; $i++) {
            $this->db->select('ladger.ladger_name, ladger.current_balance');
            $this->db->from('ladger');
            $this->db->where(array('ladger.id' => $ledger_id[$i]));
            $query = $this->db->get();
            $ee = $query->result_array();
            $new_array[$account[$i]][] = $ee[0]['ladger_name'];

            //calculate current balance
            $operater = substr($ee[0]['current_balance'], 0, 1);

            if ($account[$i] == 'Dr') {
                $new_balance = 0;

                if ($operater == '-') {
                    $crnt_bal = substr($ee[0]['current_balance'], 1);
                    if ($crnt_bal > ($amounts[$i] * $base_unit)) {
                        $new_balance = ($crnt_bal - ($amounts[$i] * $base_unit));
                    }
                    if ($crnt_bal < ($amounts[$i] * $base_unit)) {
                        $new_balance = (($amounts[$i] * $base_unit) - $crnt_bal);
                    }
                    if ($crnt_bal == ($amounts[$i] * $base_unit)) {
                        $new_balance = 0;
                    }
                } else { //its also plus figer
                    $crnt_bal = substr($ee[0]['current_balance'], 1);
                    $new_balance = ($crnt_bal + ($amounts[$i] * $base_unit));
                }

                $this->db->where('id', $ledger_id[$i]);
                $this->db->update('ladger', array('current_balance' => $new_balance, 'created_date' => $createDate));
            }
            if ($account[$i] == 'Cr') {
                $new_balance = 0;

                if ($operater == '-') {
                    $new_balance = '-' . ($ee[0]['current_balance'] + ($amounts[$i] * $base_unit));
                } else {//its also plus figer
                    if ($ee[0]['current_balance'] > ($amounts[$i] * $base_unit)) {
                        $new_balance = '-' . (($amounts[$i] * $base_unit) - $ee[0]['current_balance']);
                    }
                    if ($ee[0]['current_balance'] < ($amounts[$i] * $base_unit)) {
                        $new_balance = '+' . ($ee[0]['current_balance'] - ($amounts[$i] * $base_unit));
                    }
                    if ($ee[0]['current_balance'] == ($amounts[$i] * $base_unit)) {
                        $new_balance = '+0';
                    }
                }
                $new_balance = $ee[0]['current_balance'] - ($amounts[$i] * $base_unit);
                $this->db->where('id', $ledger_id[$i]);
                $this->db->update('ladger', array('current_balance' => $new_balance, 'created_date' => $createDate));
            }
        }

        $ledger_name_json = json_encode($new_array);
        //echo $createDate;exit;
        $entry = array(
            'entry_no' => $data['entry_no'],
            'create_date' => $createDate,
            'ledger_ids_by_accounts' => $ledger_name_json,
            'dr_amount' => ($this->input->post('sum_dr') * $base_unit),
            'cr_amount' => ($this->input->post('sum_cr') * $base_unit),
            'unit_price_dr' => $this->input->post('sum_dr'),
            'unit_price_cr' => $this->input->post('sum_cr'),
            'entry_type_id' => $this->input->post('entry_type_id'),
            'sub_voucher' => $this->input->post('sub_voucher'),
            'order_id' => $order_id
        );

        $this->db->where('id', $entry_id);
        $this->db->update('entry', $entry);

        $data = array(
            'deleted' => 1,
            'create_date' => $createDate
        );
        $this->db->where_in('ladger_account_detail.entry_id', $entry_id);
        $this->db->update('ladger_account_detail', $data);
        unset($data);

//        $this->db->where_in('id', $entry_id);
//        $this->db->update('ladger_account_detail', array('deleted'=>1));

        for ($j = 0; $j < $count; $j++) {
            $data = array();
            $data = array(
                'ladger_id' => $ledger_id[$j],
                'entry_id' => $entry_id,
                'account' => $account[$j],
                'balance' => ($amounts[$j] * $base_unit),
                'narration' => $narration,
                'selected_currency' => $selected_currency_id,
                'unit_price' => $amounts[$j]
            );
            $this->db->insert('ladger_account_detail', $data);
            unset($data);
        }
        //save bank details
        $this->db->select('*');
        $this->db->from('temp_bank_details');
        $this->db->where('entry_no =', $entry_number);
        $query_bank = $this->db->get();
        $temp_bank_data = $query_bank->result();
        if (!empty($temp_bank_data)) {
            foreach ($temp_bank_data as $value) {
                //select 
                $this->db->select('*');
                $this->db->from('bank_details');
                $this->db->where('entry_id =', $entry_id);
                $this->db->where('ledger_id =', $value->ledger_id);
                $query_bank = $this->db->get();
                $bank_data = $query_bank->row();
                //check  
                $bank_details_array = array(
                    'entry_id' => $entry_id,
                    'ledger_id' => $value->ledger_id,
                    'transaction_type' => $value->transaction_type,
                    'instrument_no' => $value->instrument_no,
                    'instrument_date' => $value->instrument_date,
                    'bank_name' => $value->bank_name,
                    'branch_name' => $value->branch_name,
                    'ifsc_code' => $value->ifsc_code,
                    'bank_amount' => $value->bank_amount,
                    'create_date' => date("Y-m-d H:i:s"),
                );
                $ledger_id = $value->ledger_id;
                if ($bank_data) {
                    $this->db->where('entry_id =', $entry_id);
                    $this->db->where('ledger_id =', $ledger_id);
                    $this->db->update('bank_details', $bank_details_array);
                } else {
                    $this->db->insert('bank_details', $bank_details_array);
                }
            }
        }
        $this->db->where('entry_no =', $entry_number);
        $this->db->delete('temp_bank_details');
        //end save bank details
        if ($entry_id) {
            return $entry_id;
        } else {
            return '';
        }
    }

    //update request entry
    public function updateRequestEntry($data = NULL) {
        //  echo '<pre/>';
        //   print_r($data);
        //   exit;
        $entry_id = $this->input->post('entry_id');
        //echo $entry_id;exit; 
        //Start:Return ledger amount
        //End:Return ledger amount
        $newdate = str_replace("/", "-", $data['create_date']);
//echo date('Y-m-d', strtotime($date1));
        //$newdate=$data['create_date'];

        $selected_currency_id = $data['selected_currency'];
        $base_unit = $data['base_unit'];
        $createDate = date('Y-m-d', strtotime($newdate));
        $account = $data['account'];
        $amounts = $data['amounts'];
        $ledger_id = $data['ledger_id'];
        $count = count($data['account']);
        $narration = $data['narration'];
        $order_id = $data['order_id'];

        //echo '<pre/>';print_r($ledger_id);exit;
        $new_array = array();
        for ($i = 0; $i < $count; $i++) {
            $this->db->select('ladger.ladger_name, ladger.current_balance');
            $this->db->from('ladger');
            $this->db->where(array('ladger.id' => $ledger_id[$i]));
            $query = $this->db->get();
            $ee = $query->result_array();
            $new_array[$account[$i]][] = $ee[0]['ladger_name'];

            //calculate current balance
            $operater = substr($ee[0]['current_balance'], 0, 1);

            if ($account[$i] == 'Dr') {
                $new_balance = 0;

                if ($operater == '-') {
                    $crnt_bal = substr($ee[0]['current_balance'], 1);
                    if ($crnt_bal > ($amounts[$i] * $base_unit)) {
                        $new_balance = ($crnt_bal - ($amounts[$i] * $base_unit));
                    }
                    if ($crnt_bal < ($amounts[$i] * $base_unit)) {
                        $new_balance = (($amounts[$i] * $base_unit) - $crnt_bal);
                    }
                    if ($crnt_bal == ($amounts[$i] * $base_unit)) {
                        $new_balance = 0;
                    }
                } else { //its also plus figer
                    $crnt_bal = substr($ee[0]['current_balance'], 1);
                    $new_balance = ($crnt_bal + ($amounts[$i] * $base_unit));
                }

                $this->db->where('id', $ledger_id[$i]);
                $this->db->update('ladger', array('current_balance' => $new_balance, 'created_date' => $createDate));
            }
            if ($account[$i] == 'Cr') {
                $new_balance = 0;

                if ($operater == '-') {
                    $new_balance = '-' . ($ee[0]['current_balance'] + ($amounts[$i] * $base_unit));
                } else {//its also plus figer
                    if ($ee[0]['current_balance'] > ($amounts[$i] * $base_unit)) {
                        $new_balance = '-' . (($amounts[$i] * $base_unit) - $ee[0]['current_balance']);
                    }
                    if ($ee[0]['current_balance'] < ($amounts[$i] * $base_unit)) {
                        $new_balance = '+' . ($ee[0]['current_balance'] - ($amounts[$i] * $base_unit));
                    }
                    if ($ee[0]['current_balance'] == ($amounts[$i] * $base_unit)) {
                        $new_balance = '+0';
                    }
                }
                $new_balance = $ee[0]['current_balance'] - ($amounts[$i] * $base_unit);
                $this->db->where('id', $ledger_id[$i]);
                $this->db->update('ladger', array('current_balance' => $new_balance, 'created_date' => $createDate));
            }
        }

        $ledger_name_json = json_encode($new_array);
        //echo $createDate;exit;
        $entry = array(
            'entry_no' => $data['entry_no'],
            'create_date' => $createDate,
            'ledger_ids_by_accounts' => $ledger_name_json,
            'dr_amount' => ($this->input->post('sum_dr') * $base_unit),
            'cr_amount' => ($this->input->post('sum_cr') * $base_unit),
            'unit_price_dr' => $this->input->post('sum_dr'),
            'unit_price_cr' => $this->input->post('sum_cr'),
            'entry_type_id' => $this->input->post('entry_type_id'),
            'sub_voucher' => $this->input->post('sub_voucher'),
            'order_id' => $order_id
        );

        $this->db->where('id', $entry_id);
        $this->db->update('entry_request', $entry);

        $data = array(
            'deleted' => 1,
            'create_date' => $createDate
        );
        $this->db->where_in('entry_request_details.entry_id', $entry_id);
        $this->db->update('entry_request_details', $data);
        unset($data);

//        $this->db->where_in('id', $entry_id);
//        $this->db->update('ladger_account_detail', array('deleted'=>1));

        for ($j = 0; $j < $count; $j++) {
            $data = array();
            $data = array(
                'ladger_id' => $ledger_id[$j],
                'entry_id' => $entry_id,
                'account' => $account[$j],
                'balance' => ($amounts[$j] * $base_unit),
                'narration' => $narration,
                'selected_currency' => $selected_currency_id,
                'unit_price' => $amounts[$j]
            );
            $this->db->insert('entry_request_details', $data);
            unset($data);
        }
    }

    //update temp entry
    public function updateTempEntry($data = NULL) {
        //  echo '<pre/>';
        //   print_r($data);
        //   exit;
        $entry_id = $this->input->post('entry_id');
        //echo $entry_id;exit; 
        //Start:Return ledger amount
        //End:Return ledger amount
        $newdate = str_replace("/", "-", $data['create_date']);
//echo date('Y-m-d', strtotime($date1));
        //$newdate=$data['create_date'];

        $selected_currency_id = $data['selected_currency'];
        $base_unit = $data['base_unit'];
        $createDate = date('Y-m-d', strtotime($newdate));
        $account = $data['account'];
        $amounts = $data['amounts'];
        $ledger_id = $data['ledger_id'];
        $count = count($data['account']);
        $narration = $data['narration'];
        $order_id = $data['order_id'];

        //echo '<pre/>';print_r($ledger_id);exit;
        $new_array = array();
        for ($i = 0; $i < $count; $i++) {
            $this->db->select('ladger.ladger_name, ladger.current_balance');
            $this->db->from('ladger');
            $this->db->where(array('ladger.id' => $ledger_id[$i]));
            $query = $this->db->get();
            $ee = $query->result_array();
            $new_array[$account[$i]][] = $ee[0]['ladger_name'];

            //calculate current balance
            $operater = substr($ee[0]['current_balance'], 0, 1);

            if ($account[$i] == 'Dr') {
                $new_balance = 0;

                if ($operater == '-') {
                    $crnt_bal = substr($ee[0]['current_balance'], 1);
                    if ($crnt_bal > ($amounts[$i] * $base_unit)) {
                        $new_balance = ($crnt_bal - ($amounts[$i] * $base_unit));
                    }
                    if ($crnt_bal < ($amounts[$i] * $base_unit)) {
                        $new_balance = (($amounts[$i] * $base_unit) - $crnt_bal);
                    }
                    if ($crnt_bal == ($amounts[$i] * $base_unit)) {
                        $new_balance = 0;
                    }
                } else { //its also plus figer
                    $crnt_bal = substr($ee[0]['current_balance'], 1);
                    $new_balance = ($crnt_bal + ($amounts[$i] * $base_unit));
                }

                $this->db->where('id', $ledger_id[$i]);
                $this->db->update('ladger', array('current_balance' => $new_balance, 'created_date' => $createDate));
            }
            if ($account[$i] == 'Cr') {
                $new_balance = 0;

                if ($operater == '-') {
                    $new_balance = '-' . ($ee[0]['current_balance'] + ($amounts[$i] * $base_unit));
                } else {//its also plus figer
                    if ($ee[0]['current_balance'] > ($amounts[$i] * $base_unit)) {
                        $new_balance = '-' . (($amounts[$i] * $base_unit) - $ee[0]['current_balance']);
                    }
                    if ($ee[0]['current_balance'] < ($amounts[$i] * $base_unit)) {
                        $new_balance = '+' . ($ee[0]['current_balance'] - ($amounts[$i] * $base_unit));
                    }
                    if ($ee[0]['current_balance'] == ($amounts[$i] * $base_unit)) {
                        $new_balance = '+0';
                    }
                }
                $new_balance = $ee[0]['current_balance'] - ($amounts[$i] * $base_unit);
                $this->db->where('id', $ledger_id[$i]);
                $this->db->update('ladger', array('current_balance' => $new_balance, 'created_date' => $createDate));
            }
        }

        $ledger_name_json = json_encode($new_array);
        //echo $createDate;exit;
        $entry = array(
            'entry_no' => $data['entry_no'],
            'create_date' => $createDate,
            'ledger_ids_by_accounts' => $ledger_name_json,
            'dr_amount' => ($this->input->post('sum_dr') * $base_unit),
            'cr_amount' => ($this->input->post('sum_cr') * $base_unit),
            'unit_price_dr' => $this->input->post('sum_dr'),
            'unit_price_cr' => $this->input->post('sum_cr'),
            'entry_type_id' => $this->input->post('entry_type_id'),
            'sub_voucher' => $this->input->post('sub_voucher'),
            'order_id' => $order_id
        );

        $this->db->where('id', $entry_id);
        $this->db->update('entry_temp', $entry);

        $data = array(
            'deleted' => 1,
            'create_date' => $createDate
        );
        $this->db->where_in('entry_temp_details.entry_id', $entry_id);
        $this->db->update('entry_temp_details', $data);
        unset($data);

//        $this->db->where_in('id', $entry_id);
//        $this->db->update('ladger_account_detail', array('deleted'=>1));

        for ($j = 0; $j < $count; $j++) {
            $data = array();
            $data = array(
                'ladger_id' => $ledger_id[$j],
                'entry_id' => $entry_id,
                'account' => $account[$j],
                'balance' => ($amounts[$j] * $base_unit),
                'narration' => $narration,
                'selected_currency' => $selected_currency_id,
                'unit_price' => $amounts[$j]
            );
            $this->db->insert('entry_temp_details', $data);
            unset($data);
        }
    }

    public function modifyLedgerBalance($id = NULL) {
        $entry_id = $id;
        $this->db->select('ladger_account_detail.ladger_id, ladger_account_detail.account, ladger_account_detail.balance, ladger_account_detail.id');
        $this->db->from('ladger_account_detail');
        $this->db->where(array('entry_id' => $entry_id, 'deleted' => '0'));
        $query = $this->db->get();
        $account_details = $query->result_array();
        //echo '<pre/>';print_r($account_details);exit;
        for ($i = 0; $i < count($account_details); $i++) {
            if ($account_details[$i]['account'] == 'Dr') {

                $set_bal = 0;
                $this->db->select('ladger.current_balance');
                $this->db->from('ladger');
                $this->db->where('ladger.id', $account_details[$i]['ladger_id']);
                $query = $this->db->get();
                $current_balance = $query->result_array();
                $set_bal = ($current_balance[0]['current_balance'] - $account_details[$i]['balance']);
                $data = ['current_balance' => $set_bal];
                $this->db->where('id', $account_details[$i]['ladger_id']);
                $this->db->update('ladger', $data);
                unset($data);
            }
            if ($account_details[$i]['account'] == 'Cr') {

                $ballance = 0;
                $this->db->select('ladger.current_balance');
                $this->db->from('ladger');
                $this->db->where('ladger.id', $account_details[$i]['ladger_id']);
                $query = $this->db->get();
                $current_balance = $query->result_array();
                $ballance = ($current_balance[0]['current_balance'] + $account_details[$i]['balance']);
                $data = ['current_balance' => $ballance];
                $this->db->where('id', $account_details[$i]['ladger_id']);
                $this->db->update('ladger', $data);
            }

            $this->db->where('entry_id', $entry_id);
            $this->db->update('ladger_account_detail', array('deleted' => 1));
        }
        $this->db->where('id', $entry_id);
        $this->db->update('entry', array('deleted' => 1));
        return $entry_id;
    }

    public function getLedgerId($ledger) {
        $record = $this->db->select('id,tracking_status,bill_details_status')
                ->from('ladger')
                ->where("ladger_name", $ledger)
                ->get();
        return $record->row_array();
    }

    //get all temp bill by ledger id
    public function getAllTempBill($ledger) {
        $record = $this->db->select('*')
                ->from('billwish_temp')
                ->where("ledger_id", $ledger)
                ->get();
        return $record->result();
    }

    //get all save bill 
    public function getAllBill($entry_id, $ledger) {
        $record = $this->db->select('*')
                ->from('billwish_details')
                ->where("ledger_id", $ledger)
                ->where("entry_id", $entry_id)
                ->get();
        return $record->result();
    }

    //get all temp bill by ledger id
    public function getAllTempTracking($ledger) {
        $record = $this->db->select('*')
                ->from('tracking_temp')
                ->where("ledger_id", $ledger)
                ->get();
        return $record->result();
    }

    //get all save tracking
    public function getAllTracking($entry_id, $ledger) {
        $record = $this->db->select('*')
                ->from('tracking_details')
                ->join('tracking', 'tracking.id = tracking_details.tracking_id', 'left')
                ->where("ledger_id", $ledger)
                ->where("entry_id", $entry_id)
                ->get();
        return $record->result();
    }

    //get ledger details by id
    public function getLedgerDetailsById($ledger) {
        $record = $this->db->select('id,tracking_status,bill_details_status,ladger_name')
                ->from('ladger')
                ->where("id", $ledger)
                ->get();
        return $record->row_array();
    }

    public function getAllLedgerNameForJson($where, $ledger) {
        $this->db->select(' ladger.ladger_name');
        $this->db->from('ladger');
        $this->db->like('ladger_name', $ledger, 'after');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getSalesLedgerNameForJson($where, $ledger, $sales_group_id, $tax_group_id, $in_exp_group_id) {
        $this->db->select('ladger.ladger_name');
        $this->db->from('ladger');
        $this->db->join('group', 'group.id = ladger.group_id');
        $this->db->like('ladger.ladger_name', $ledger, 'after');
        $this->db->where($where);
        $this->db->where('group.id', $sales_group_id);
        $this->db->or_where('group.id', $tax_group_id);
        $this->db->or_where('group.id', $in_exp_group_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPurchaseLedgerNameForJson($where, $ledger, $purchase_group_id, $tax_group_id, $in_exp_group_id) {
        $this->db->select('ladger.ladger_name');
        $this->db->from('ladger');
        $this->db->join('group', 'group.id = ladger.group_id');
        $this->db->like('ladger.ladger_name', $ledger, 'after');
        $this->db->where($where);
        $this->db->where('group.id', $purchase_group_id);
        $this->db->or_where('group.id', $tax_group_id);
        $this->db->or_where('group.id', $in_exp_group_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getContraLedgerNameForJson($ledger, $group_id_arr) {
        $this->db->select('ladger.ladger_name');
        $this->db->from('ladger');
        $this->db->join('group', 'group.id = ladger.group_id');
        $this->db->like('ladger.ladger_name', $ledger, 'after');
        $this->db->where('ladger.status', '1');
        $this->db->where('ladger.deleted', '0');
        $this->db->where_in('group.id', $group_id_arr);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllEntryTypeNameForJson($where, $entry_type) {
        $this->db->select('entry_type.type');
        $this->db->from('entry_type');
        $this->db->like('type', $entry_type, 'after');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getEntryTypeId($entrytype) {
        $record = $this->db->select('id')
                ->from('entry_type')
                ->where("type", $entrytype)
                ->get();
        return $record->row_array();
    }

    public function saveSubVoucher($data = NULL) {
        // echo "<pre>";print_r($data);exit();
        if ($this->input->post('id') == "") {
            $log = array(
                'user_id' => $this->session->userdata('admin_uid'),
                'branch_id' => $this->session->userdata('branch_id'),
                'module' => 'voucher',
                'action' => '`' . $data['type'] . '` <b>created</b>',
                'previous_data' => '',
                'performed_at' => date('Y-m-d H:i:s', time())
            );
            $this->currentusermodel->updateLog($log);

            $this->db->insert('entry_type', $data);
            return $this->db->insert_id();
        } else {
            $this->db->where('id', $data['id']);
            $voucher = $this->db->get('entry_type')->row_array();
            $log = array(
                'user_id' => $this->session->userdata('admin_uid'),
                'branch_id' => $this->session->userdata('branch_id'),
                'module' => 'voucher',
                'action' => '`' . $data['type'] . '` <b>edited</b>',
                'previous_data' => json_encode($voucher),
                'performed_at' => date('Y-m-d H:i:s', time())
            );
            $this->currentusermodel->updateLog($log);

            $this->db->where('id', $data['id']);
            $this->db->update('entry_type', $data);
        }
    }

    public function getAllSubVouchers($where) {
        $this->db->select('sub_voucher.*,entry_type.type');
        $this->db->from('sub_voucher');
        $this->db->join('entry_type', 'sub_voucher.entry_type_id = entry_type.id');
        $this->db->order_by('sub_voucher.id', 'desc');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllSubVouchersById($id) {
        $this->db->select('ET.*,E.type as parent');
        $this->db->from('entry_type ET');
        $this->db->join('entry_type E', 'ET.parent_id = E.id');
        $this->db->order_by('ET.id', 'desc');
        $this->db->where('ET.parent_id', $id);
        $this->db->where('ET.status', 1);
        $this->db->where('ET.deleted', 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllParentVouchers() {
        $this->db->select('*');
        $this->db->from('entry_type');
        $this->db->where('status', '1');
        $this->db->where('deleted', '0');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getSubVoucherType($where) {
        $this->db->select('id,type');
        $this->db->from('entry_type');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getSubVoucherId($id) {
        $this->db->select('*');
        $this->db->from('sub_voucher');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getAllEntryType() {
        $this->db->select('entry_type.id, entry_type.type');
        $this->db->from('entry_type');
        $this->db->where('status', 1);
        $this->db->where('deleted', 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getSubVoucherCodeStatus() {
        $this->db->select('voucher_no_status');
        $query = $this->db->get('account_configuration');
        return $query->row_array();
    }

    public function updateSubVoucher($data = NULL, $last_id = NULL) {
        $this->db->where('id', $last_id);
        $this->db->update('entry_type', $data);
    }

    public function getAllCurrency() {
        $this->db->select('id,currency');
        $this->db->from('currency');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getDefoultCurrency() {
        $this->db->select('base_currency');
        $this->db->from('account_standard_format');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getCurrencyUnitById($id) {
        $this->db->select('id,unit_price');
        $this->db->from('currency');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function ledgerIndexIsExist($id) {
        $this->db->select('*');
        $this->db->from('tracking_temp');
        $this->db->where('ledger_index', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function delExistLedgerIndex($id) {
        $this->db->where('ledger_index', $id);
        $this->db->delete('tracking_temp');
    }

    function setTrackingLedger($data = array()) {
        $this->db->insert_batch('tracking_temp', $data);
        return $this->db->insert_id();
    }

    function tableTruncate() {
        $this->db->truncate('tracking_temp');
    }

    //30072016
    public function ledgerIndexIsExistInBill($id) {
        $this->db->select('*');
        $this->db->from('billwish_temp');
        $this->db->where('ledger_index', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
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

    //get opening balance
    public function openingBalance($ledger_id) {
        $record = $this->db->select('id,account_type,opening_balance')
                ->from('ladger')
                ->where('id', $ledger_id)
                ->where('deleted', '0')
                ->where('status', '1')
                ->get();
        return $record->row();
    }

    function delExistLedgerIndexInBill($id) {
        $this->db->where('ledger_index', $id);
        $this->db->delete('billwish_temp');
    }

    function setBillLedger($data = array()) {
        $this->db->insert_batch('billwish_temp', $data);
        return $this->db->insert_id();
    }

    function billTableTruncate() {
        $this->db->truncate('billwish_temp');
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

    public function updateVoucher($data = array(), $id = NULL) {
        $this->db->where('id', $id);
        $voucher = $this->db->get('entry_type')->row_array();
        $log = array(
            'user_id' => $this->session->userdata('admin_uid'),
            'branch_id' => $this->session->userdata('branch_id'),
            'module' => 'voucher',
            'action' => '`' . $data['type'] . '` <b>edited</b>',
            'previous_data' => json_encode($voucher),
            'performed_at' => date('Y-m-d H:i:s', time())
        );
        $this->currentusermodel->updateLog($log);

        $this->db->where('id', $id);
        $this->db->update('entry_type', $data);
        return $this->db->affected_rows();
    }

    public function getAutoNumberConfiguration() {
        $this->db->select('voucher_no_status');
        $this->db->from('account_configuration');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getLastId() {
        return $last_row = $this->db->select('id')->order_by('id', "desc")->limit(1)->get('entry')->row_array();
    }

    public function getNoOfByTypeId($id, $parent_id, $to, $from) {
        $this->db->select('COUNT(id) AS total_transaction');
        $this->db->from('entry');
        if ($parent_id == 0) {
            $this->db->where('sub_voucher', $parent_id);
            $this->db->where('entry_type_id', $id);
        }else{
        $this->db->where('entry_type_id', $parent_id);
         $this->db->where('sub_voucher', $id);   
        }
        $this->db->where('deleted !=', '2');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getNoOfBySubvoucherId($id) {
        return $result = $this->db->select('COUNT(id) AS total_transaction')->where('sub_voucher', $id)->where('status', '1')->where('deleted', '0')->get('entry')->row_array();
    }

    public function getSubVoucherById($id) {
        $this->db->select('*');
        $this->db->from('sub_voucher');
        $this->db->where('status', '1');
        $this->db->where('deleted', '0');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function saveTempBankDetails($data) {
        return $this->db->insert_batch('temp_bank_details', $data);
    }

    public function getAllSubGroup($group_id) {
        $this->db->select('id');
        $this->db->from('group');
        $this->db->where('status', '1');
        $this->db->where('deleted', '0');
        $this->db->where('parent_id =', $group_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllLedger($group_id_arr) {
        $this->db->select('id');
        $this->db->from('ladger');
        $this->db->where('status', '1');
        $this->db->where('deleted', '0');
        $this->db->where_in('group_id', $group_id_arr);
        $query = $this->db->get();
        return $query->result();
    }

    public function allTransactionType() {
        $this->db->select('*');
        $this->db->from('transaction_type');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result();
    }

    public function allTempBankData($ledger_id, $entry_no) {
        $this->db->select('*');
        $this->db->from('temp_bank_details');
        $this->db->where('entry_no =', $entry_no);
        $this->db->where('ledger_id =', $ledger_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function copyBankDetailstoTemp($entry_no, $entry_id) {
        $this->db->select('*');
        $this->db->from('bank_details');
        $this->db->where('entry_id =', $entry_id);
        $query = $this->db->get();
        $bank_details = $query->result();
        $this->db->where('entry_no =', $entry_no);
        $this->db->delete('temp_bank_details');
        $bank_details_array = [];
        foreach ($bank_details as $val) {
            $data = array(
                'entry_no' => $entry_no,
                'ledger_id' => $val->ledger_id,
                'transaction_type' => $val->transaction_type,
                'instrument_no' => $val->instrument_no,
                'instrument_date' => $val->instrument_date,
                'bank_name' => $val->bank_name,
                'branch_name' => $val->branch_name,
                'ifsc_code' => $val->ifsc_code,
                'bank_amount' => $val->bank_amount,
                'create_date' => date("Y-m-d H:i:s")
            );
            $bank_details_array[] = $data;
        }
        if ($bank_details_array) {
            $this->db->insert_batch('temp_bank_details', $bank_details_array);
        }
    }

    //get entry by id
    public function getEntryByIdOrder($entry_id) {
        $this->db->select('order_id');
        $this->db->from('entry');
        $this->db->where('id =', $entry_id);
        $query = $this->db->get();
        return $query->row();
    }

    //delete from order table using order id
    public function deleteOrder($orderid) {
        $sql = "update " . tablename('orders') . " set status = '0' where id='" . $orderid . "'";
        $query = $this->db->query($sql);
        $sql1 = "update " . tablename('ordered_products') . " set status = '0' where order_id='" . $orderid . "'";
        $query1 = $this->db->query($sql1);
        if (!empty($query)) {
            return 1;
        } else {
            return;
        }
    }

    //get entry by id
    public function getRequestEntryByIdOrder($entry_id) {
        $this->db->select('order_id');
        $this->db->from('entry_request');
        $this->db->where('id =', $entry_id);
        $query = $this->db->get();
        return $query->row();
    }

    //delete from order table using order id
    public function deleteRequestOrder($orderid) {
        $sql = "update " . tablename('order_request') . " set status = '0' where id='" . $orderid . "'";
        $query = $this->db->query($sql);
        $sql1 = "update " . tablename('order_request_details') . " set status = '0' where order_id='" . $orderid . "'";
        $query1 = $this->db->query($sql1);
        if (!empty($query)) {
            return 1;
        } else {
            return;
        }
    }

    //request entry delete
    public function deleteRequestEntry($entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->update('entry_request_details', array('deleted' => 1));

        $this->db->where('id', $entry_id);
        $this->db->update('entry_request', array('deleted' => 1));
        return $entry_id;
    }

    //temp
    //get temp entry by id
    public function getTempEntryByIdOrder($entry_id) {
        $this->db->select('order_id');
        $this->db->from('entry_temp');
        $this->db->where('id =', $entry_id);
        $query = $this->db->get();
        return $query->row();
    }

    //delete from order table using order id
    public function deleteTempOrder($orderid) {
        $sql = "update " . tablename('orders') . " set status = '0' where id='" . $orderid . "'";
        $query = $this->db->query($sql);
        $sql1 = "update " . tablename('ordered_products') . " set status = '0' where order_id='" . $orderid . "'";
        $query1 = $this->db->query($sql1);
        if (!empty($query)) {
            return 1;
        } else {
            return;
        }
    }

    //request entry delete
    public function deleteTempEntry($entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->update('entry_temp_details', array('deleted' => 1));

        $this->db->where('id', $entry_id);
        $this->db->update('entry_temp', array('deleted' => 1));
        return $entry_id;
    }

    //check entry existance
    public function checkEntryExist($voucher_id) {
        $query = $this->db->select('id')
                ->from(tablename('entry'))
                ->where('sub_voucher =', $voucher_id)
                ->where('status', 1)
                ->where('deleted', 0)
                ->get();
        return $row = $query->result();
    }

    public function deleteVoucher($voucher_id) {
        $this->db->where('id', $voucher_id);
        $voucher = $this->db->get('entry_type')->row_array();
        $log = array(
            'user_id' => $this->session->userdata('admin_uid'),
            'branch_id' => $this->session->userdata('branch_id'),
            'module' => 'voucher',
            'action' => '`' . $voucher['type'] . '` <b>deleted</b>',
            'previous_data' => json_encode($voucher),
            'performed_at' => date('Y-m-d H:i:s', time())
        );
        $this->currentusermodel->updateLog($log);

        $this->db->where('id', $voucher_id);
        $this->db->update('entry_type', array('deleted' => '1'));
        return $voucher_id;
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

    //get configuration
    public function getSettingsConfiguration() {
        $this->db->select('*');
        $this->db->from('account_configuration');
        $this->db->where('id =', 1);
        $query = $this->db->get();
        return $query->row();
    }

    //get settings
    public function getSettings() {
        $this->db->select('*');
        $this->db->from('account_settings');
        $this->db->where('id =', 1);
        $query = $this->db->get();
        return $query->row();
    }

    //get settings
    public function getCurrency($id) {
        $this->db->select('currency');
        $this->db->from('currency');
        $this->db->where('id =', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function updateCurrency($id, $from, $to) {
        $amount = 1;
        $results = converCurrency($from, $to, $amount);
        if ($results) {
            $regularExpression = '#\<span class=bld\>(.+?)\<\/span\>#s';
            preg_match($regularExpression, $results, $finalData);
            if ($finalData) {
                preg_match('/([0-9,]+\.[0-9]+)/', $finalData[0], $matches);
            }
            if (isset($matches) && $matches) {
                $data['unit_price'] = $matches[0];
            }
            if ($id == 6) {
                $data['unit_price'] = 1;
            }
            $data['ecchange_date'] = date('Y-m-d');
            $this->db->where('id', $id);
            return $this->db->update('currency', $data);
        }
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

    public function getLedgerContactDetails($ledger_id) {
        $this->db->select('CD.email,CD.company_name');
        $this->db->from('customer_details as CD');
        $this->db->where('CD.ledger_id', $ledger_id);
        $this->db->where('CD.status', 1);
        $query = $this->db->get();
        return $query->row();
    }

    public function deleteRecurringEntry($entry_id) {
        $this->db->where('entry_id', $entry_id);
        return $this->db->update('recurring_entry', array('status' => '3'));
    }

    public function getEntryType($entry_id) {
        $record = $this->db->select('entry_type_id')
                ->from('entry')
                ->where("id", $entry_id)
                ->get();
        return $record->row();
    }

    public function approvePostDateEntry($entry_data, $entry_id) {
        $this->db->where('id', $entry_id);
        return $this->db->update('entry', $entry_data);
    }

    public function approvePostDateEntryDetails($entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->where('deleted', '2');
        return $this->db->update('ladger_account_detail', array('deleted' => '0'));
    }

    public function approvePostDateEntryBillDetails($entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->where('deleted', '2');
        return $this->db->update('billwish_details', array('deleted' => '0'));
    }

    public function approvePostDateEntryBankDetails($entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->where('deleted', '2');
        return $this->db->update('bank_details', array('deleted' => '0'));
    }

    public function approvePostDateEntryTrackingDetails($entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->where('deleted', '2');
        return $this->db->update('tracking_details', array('deleted' => '0'));
    }

    public function getEntry($entry_id) {
        $this->db->select('entry.id as id, entry.entry_no as entry_no, entry_type.type as entry_type');
        $this->db->from('entry');
        $this->db->join('entry_type', 'entry.entry_type_id = entry_type.id');
        $this->db->where('entry.id =', $entry_id);
        $this->db->where('entry.deleted', '0');
        $query = $this->db->get();
        return $query->row();
    }

    //transaction delete
    public function deleteTransaction($entry_id) {
        $this->db->trans_begin();
        $this->db->where('id', $entry_id);
        $this->db->update('entry', array('deleted' => 1));

        $this->db->where('entry_id', $entry_id);
        $this->db->update('ladger_account_detail', array('deleted' => 1));

        $this->db->where('entry_id', $entry_id);
        $this->db->update('tracking_details', array('deleted' => 1));

        $this->db->where('entry_id', $entry_id);
        $this->db->update('bank_details', array('deleted' => 1));
        
        $this->db->where('entry_id', $entry_id);
        $this->db->delete('brs'); 

        $this->db->where('entry_id', $entry_id);
        $this->db->update('billwish_details', array('deleted' => 1));
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            return $entry_id;
        }
    }

    public function getSubVoucher($id) {
        $this->db->select('SV.sub_voucher as name');
        $this->db->from('sub_voucher as SV');
        $this->db->where('SV.id', $id);
        $this->db->where('SV.status', '1');
        $this->db->where('SV.deleted', '0');
        $query = $this->db->get();
        return $query->row();
    }

    public function getCountEntries($id, $parent_id,$search='') {
        $this->db->select("entry.*, entry_type.type, GROUP_CONCAT(CONCAT(l.ladger_name, ' [',ld.account, ']') SEPARATOR '/') as ledger_detail", FALSE);
        $this->db->from('entry');        
        $this->db->join('entry_type', 'entry_type.id = entry.entry_type_id', 'left');

        /* ========= */

        $this->db->join('ladger_account_detail as ld', 'ld.entry_id = entry.id', 'left');
        $this->db->join('ladger as l', 'l.id = ld.ladger_id', 'left');

        /* ========= */

        if ($parent_id == 0) {
            $this->db->where('entry.sub_voucher', $parent_id);
            $this->db->where('entry.entry_type_id', $id);
        } else {
            $this->db->where('entry.sub_voucher', $id);
            $this->db->where('entry.entry_type_id', $parent_id);
        }

        $this->db->where('entry.company_id', $this->session->userdata('branch_id'));
        $this->db->where('entry.status', 1);
        $this->db->where('entry.deleted', 0);
        $this->db->where('entry.is_inventry', 0);
        // $this->db->where('entry.create_date <=', $to_date);
        // $this->db->where('entry.create_date >=', $from_date);
        $this->db->like('entry.entry_no', $search);
        $this->db->group_by('entry.id');
        $this->db->order_by("entry.create_date", "asc");
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getAllRecurringEntryCount($id, $parent_id,$search='') {
        $this->db->select('entry.*, entry_type.type,recurring_entry.frequency');
        $this->db->from('recurring_entry');

        $this->db->join('entry', 'entry.id = recurring_entry.entry_id', 'left');
        $this->db->join('entry_type', 'entry_type.id = entry.entry_type_id', 'left');
        if ($parent_id == 0) {
            $this->db->where('entry.sub_voucher', $parent_id);
            $this->db->where('entry.entry_type_id', $id);
        } else {
            $this->db->where('entry.sub_voucher', $id);
            $this->db->where('entry.entry_type_id', $parent_id);
        }

        $this->db->where('entry.company_id', $this->session->userdata('branch_id'));
        $this->db->where('entry.status', 1);
        $this->db->where('entry.deleted', 0);
        $this->db->where('entry.is_inventry', 0);
        // $this->db->where('entry.create_date <=', $to_date);
        // $this->db->where('entry.create_date >=', $from_date);
        $this->db->like('entry.entry_no', $search);
        $this->db->where("recurring_entry.status", '1');
        $this->db->order_by("entry.id", "asc");
        $this->db->group_by("recurring_entry.entry_id");
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getAllPostDatedEntryCount($id, $parent_id,$search='') {
        $this->db->select('entry.*, entry_type.type');
        $this->db->from('entry');
        $this->db->order_by("entry.id", "asc");
        $this->db->join('entry_type', 'entry_type.id = entry.entry_type_id', 'left');
        if ($parent_id == 0) {
            $this->db->where('entry.sub_voucher', $parent_id);
            $this->db->where('entry.entry_type_id', $id);
        } else {
            $this->db->where('entry.sub_voucher', $id);
            $this->db->where('entry.entry_type_id', $parent_id);
        }

        $this->db->where('entry.company_id', $this->session->userdata('branch_id'));
        $this->db->where('entry.status', 1);
        $this->db->where('entry.deleted', 2);
        $this->db->where('entry.is_inventry', 0);
        $this->db->like('entry.entry_no', $search);
        $query = $this->db->get();
        return $query->num_rows();
    }

}

?>
