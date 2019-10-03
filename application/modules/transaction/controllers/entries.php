<?php

class entries extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->model('entries/entrymodel');
        $this->load->model('admin/financialyearmodel');
        $this->load->model('accounts/account');
        $this->load->model('transaction_inventory/inventory/inventorymodel');
        $this->load->helper('financialyear');
        admin_authenticate();
    }

    public function update($id, $type, $action) {
        // echo "test";exit();
         //access
        if($type==1){
            $module=84;
        }elseif ($type==2) {
         $module=85;   
        }elseif ($type==3) {
         $module=86;   
        }elseif ($type==4) {
         $module=87;   
        }elseif ($type==5) {
         $module=183;   
        }elseif ($type==6) {
         $module=184;   
        }
         user_permission($module,'edit');
        //access
        $data = [];
        $data['groups'] = $this->entrymodel->getAllGroups();
        $data['contacts'] = $this->account->getContact();
        $data['ledger'] = array();
        $ledger_code_status = $this->entrymodel->getLedgerCodeStatus();
        $data['ledger_code_status'] = $ledger_code_status['ledger_code_status'];
        $data['entry'] = $this->entrymodel->getEntry($id);
        $data['entry_details'] = $this->entrymodel->getEntryDetails($id);
        $data['entry_service_details'] = $this->entrymodel->getEntryServiceDetails($id);
        $entry_type = $this->entrymodel->getEntryTypeById($type);
        $data['voucher'] = $entry_type['type'];
        $data['voucher_id'] = $entry_type['id'];
        $data['parent_id'] = $entry_type['parent_id'];
        $data['auto_no_status'] = $entry_type['transaction_no_status'];
        $data['entry_type'] = $entry_type;
        $voucher = $this->entrymodel->checkVoucher();
        $data['auto_date'] = $voucher['skip_date_create'];
        $data['recurring'] = $voucher['want_recurring'];
        $this->entrymodel->copyBankDetailstoTemp($id);
        $this->entrymodel->copyTrackingDetailstoTemp($id);
        $this->entrymodel->copyBillingDetailstoTemp($id);
        $data['action'] = $action;
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Transaction Update');
        $this->layouts->render('entries/update', $data, 'admin_new');
    }

    public function getLedgerByVoucher() {
        $ledger = [];
        if ($this->input->is_ajax_request()) {
            $get_all_ledgers = $this->entrymodel->getLedgerByGroup();
            foreach ($get_all_ledgers as $value) {
                $item = array(
                    'id' => $value->id,
                    'ledger' => $value->ladger_name,
                    'code' => $value->ledger_code,
                    'type' => $value->account_type,
                    'balance' => $value->current_balance
                );
                $ledger[] = $item;
            }
            echo json_encode($ledger);
        }
    }

    public function checkDate() {
        if ($this->input->is_ajax_request()) {
            $data_msg = [];
            $date = trim($_POST['date']);
            $financial_year_start = $this->financialyearmodel->getFinancialYear();
            $arr = explode("-", $financial_year_start->finalcial_year_from);
            $y = isset($arr) ? $arr[0] : date("Y");
            $m = isset($arr) ? $arr[1] : 4;
            if (is_numeric(substr($date, 3, 2))) {
                $day = substr($date, 0, 2);
                $month = substr($date, 3, 2);
                if (0 < $month && $month < $m) {
                    $year = $y + 1;
                } else {
                    $year = $y;
                }
//                if (0 < $month && $month <= $m) {
//                    $year = date("Y") + 1;
//                } else {
//                    $year = date("Y");
//                }
                $leap = date('L', mktime(0, 0, 0, 1, 1, $year));
                if (intval($month) == 2 && intval($day) == 29 && (!$leap)) {
                    $date = 'dd/mm/yyyy';
                } else if (intval($month) == 2 && (intval($day) == 30 || intval($day) == 31)) {
                    $date = 'dd/mm/yyyy';
                } else {
                    $date = $day . '/' . $month . '/' . $year;
                }

                $data_msg['res'] = true;
            }
            $data_msg['date'] = $date;
            echo json_encode($data_msg);
        }
    }

    //get ledger details for modal
    public function getLedgerDelails() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $ledger_id = $this->input->post('ledger_id');
            if ($ledger_id) {
                $data_msg['ledger_details'] = $this->entrymodel->getLedgerDetails($ledger_id);
                $data_msg['is_bank_group'] = $this->check_bank_betails($ledger_id);
                $data_msg['is_detors_creditors_group'] = $this->is_detors_creditors_group($ledger_id);
                $data_msg['res'] = 'success';
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = 'Please select the ledger first.';
            }
            echo json_encode($data_msg);
        }
    }

    //get ledger details for modal
    public function getTempTrackingData() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $ledger_id = $this->input->post('ledger_id');
            $entry_id = $this->input->post('entry_id');
            if ($ledger_id && $entry_id) {
                $temp_tracking_details = $this->entrymodel->getTempTrackingDetails($ledger_id, $entry_id);

                if ($temp_tracking_details) {
                    $data_msg['temp_tracking_details'] = $temp_tracking_details;
                    $data_msg['res'] = 'success';
                } else {
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = 'No tracking Available.';
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = 'Please select the ledger first.';
            }
            echo json_encode($data_msg);
        }
    }

    //get ledger details for modal
    public function getTempBillingData() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $ledger_id = $this->input->post('ledger_id');
            $entry_id = $this->input->post('entry_id');
            if ($ledger_id && $entry_id) {
                $temp_billing_details = $this->entrymodel->getTempBillingDetails($ledger_id, $entry_id);
                if ($temp_billing_details) {
                    $data_msg['temp_billing_details'] = $temp_billing_details;
                    $data_msg['res'] = 'success';
                } else {
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = 'No Bill details available.';
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = 'Please select the ledger first.';
            }
            echo json_encode($data_msg);
        }
    }

    //get temp banking for modal
    public function getTempBankingData() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $ledger_id = $this->input->post('ledger_id');
            $entry_id = $this->input->post('entry_id');
            if ($ledger_id && $entry_id) {
                $temp_banking_details = $this->entrymodel->getTempBankingDetails($ledger_id, $entry_id);
                if ($temp_banking_details) {
                    $data_msg['temp_banking_details'] = $temp_banking_details;
                    $data_msg['res'] = 'success';
                } else {
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = 'No Bank details available.';
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = 'Please select the ledger first.';
            }
            echo json_encode($data_msg);
        }
    }

    public function getTrackingName() {
        if ($this->input->is_ajax_request()) {
            $tracking = $_POST['tracking'];
            $trackingArr = $_POST['trackingArr'];

            $trackingArr = json_decode($trackingArr);

            $tracking_details = $this->entrymodel->getTTrackingName($tracking, $trackingArr);

            if (count($tracking_details) == 0) {
                echo json_encode("");
            } else {

                $trackingName = '';

                $trackingName = '[';

                foreach ($tracking_details as $value) {
                    $trackingName .= " { \"label\": \"$value->tracking_name\", \"value\": \"$value->id\"},";
                }

                $trackingName = substr($trackingName, 0, -1);
                $trackingName .= ' ]';

                echo $trackingName;
            }
        }
    }

    //save temp tracking data
    public function saveTempTrackingData() {

        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $account_type = $this->input->post('popup_tr_ledger_type');
            $ledger_id = $this->input->post('popup_tr_ledger_id');
            $entry_id = $this->input->post('popup_tr_entry_id');
            $tr_tracking_id = $this->input->post('tr_tracking_id');
            $tr_tracking_amount = $this->input->post('tr_tracking_amount');
            $this->form_validation->set_rules('popup_tr_ledger_type', 'Ledger Type', 'required');
            $this->form_validation->set_rules('popup_tr_ledger_id', 'Ledger', 'required');
            $this->form_validation->set_rules('popup_tr_entry_id', 'Entry Id', 'required');
            $this->form_validation->set_rules('tr_tracking_id[]', 'Tracking Name', 'required');
            $this->form_validation->set_rules('tr_tracking_amount[]', 'Tracking Amount', 'required|numeric');
            if ($this->form_validation->run() === TRUE) {
                $this->entrymodel->deleteTempTrackingDetails($ledger_id, $entry_id);
                $data = [];
                foreach ($tr_tracking_id as $key => $row) {
                    $item = array(
                        'entry_id' => $entry_id,
                        'account_type' => $account_type,
                        'ledger_id' => $ledger_id,
                        'tracking_id' => $row,
                        'tracking_amount' => $tr_tracking_amount[$key],
                        'created_date' => date('Y-m-d H:i:s')
                    );
                    $data[] = $item;
                }

                $res = $this->entrymodel->saveTempTrackingDataModel($data);
                if ($res) {
                    $data_msg['is_bank_group'] = $this->check_bank_betails($ledger_id);
                    $data_msg['ledger_details'] = $this->entrymodel->getLedgerDetails($ledger_id);
                    $data_msg['is_detors_creditors_group'] = $this->is_detors_creditors_group($ledger_id);
                    $data_msg['res'] = 'success';
                } else {
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = 'Error In process.';
                }
            } else {
                $data_msg['res'] = 'validation_error';
                $data_msg['message'] = $this->form_validation->error_array();
            }
            echo json_encode($data_msg);
        }
    }

    //save temp billing data
    public function saveTempBillingData() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $ledger_id = $this->input->post('popup_tr_ledger_id');
            $entry_id = $this->input->post('popup_tr_entry_id');
            $tr_type = $this->input->post('popup_tr_ledger_type');
            $ref_bill_type = $this->input->post('ref_bill_type');
            $bill_name = $this->input->post('bill_name');
            $bill_credit_day = $this->input->post('bill_credit_day');
            $bill_credit_date = $this->input->post('bill_credit_date');
            $bill_amount = $this->input->post('bill_amount');
            $this->form_validation->set_rules('popup_tr_ledger_id', 'Ledger', 'required');
            $this->form_validation->set_rules('popup_tr_entry_id', 'Entry Id', 'required');
            $this->form_validation->set_rules('popup_tr_ledger_id', 'Ledger Type', 'required');
            $this->form_validation->set_rules('ref_bill_type[]', 'Bill Type', 'required');
            $this->form_validation->set_rules('bill_name[]', 'Bill Name', 'required');
            $this->form_validation->set_rules('bill_credit_day[]', 'Bill Credit Day', 'required');
            $this->form_validation->set_rules('bill_credit_date[]', 'Bill Credit Date', 'required');
            $this->form_validation->set_rules('bill_amount[]', 'Bill Amount', 'required|numeric');
            if ($this->form_validation->run() === TRUE) {
                $this->entrymodel->deleteTempBillingDetails($ledger_id, $entry_id);
                $data = [];
                foreach ($ref_bill_type as $key => $row) {
                    $item = array(
                        'ledger_id' => $ledger_id,
                        'dr_cr' => $tr_type,
                        'ref_type' => $ref_bill_type[$key],
                        'bill_name' => $bill_name[$key],
                        'credit_days' => $bill_credit_day[$key],
                        'credit_date' => date('Y-m-d', strtotime($bill_credit_date[$key])),
                        'bill_amount' => $bill_amount[$key],
                        'entry_id' => $entry_id,
                        'created_date' => date('Y-m-d H:i:s')
                    );
                    $data[] = $item;
                }
                $res = $this->entrymodel->saveTempBillData($data);
                if ($res) {
                    $data_msg['is_detors_creditors_group'] = $this->is_detors_creditors_group($ledger_id);
                    $data_msg['res'] = 'success';
                } else {
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = 'Error In process.';
                }
            } else {
                $data_msg['res'] = 'validation_error';
                $data_msg['message'] = $this->form_validation->error_array();
            }
            echo json_encode($data_msg);
        }
    }

    //get bill name
    public function getBillByReferenceLedgerId() {

        $bill_name = $_POST['bill_name'];
        $bill_name = trim(substr($bill_name, 0, strpos($bill_name, ":")));
        $ledger_id = $_POST['ledger_id'];
        $total_bill_array = json_decode($_POST['total_bill_array']);
        $where = '';
        if (!empty($total_bill_array)) {
            $billCode = '';
            foreach ($total_bill_array as $value) {
                $billCode .= $value . ',';
            }
            $billCode = rtrim($billCode, ',');
            $where = " AND bill_name NOT IN (" . $billCode . ")";
        }
        $ledge = array();
        $ledger_name = $this->entrymodel->getBillNameForJson($ledger_id, $bill_name, $where);

        if ($ledger_name) {
            $dr_cr = '';
            $transactionName = '';

            $transactionName = '[';


            foreach ($ledger_name as $value) {
                $bill_amount = ($value['cr_sum'] - $value['dr_sum']);

                if ($bill_amount != 0) {
                    if ($bill_amount > 0) {
                        $dr_cr = 'Cr';
                    } else {
                        $dr_cr = 'Dr';
                    }
                    $ledge = $value['bill_name'] . ': Rs.-' . str_replace('-', '', $bill_amount) . '(' . $dr_cr . ')';
                    $billName = $value['bill_name'];
                    $transactionName .= " { \"label\": \"$ledge\", \"value\": \"$billName\"},";
                }
            }

            $transactionName = substr($transactionName, 0, -1);
            $transactionName .= ' ]';

            echo $transactionName;
        } else {
            echo json_encode("");
        }
    }

    //get bill details
    public function getBillByBillnameLedgerId() {
        if ($this->input->post('ajax', TRUE)) {

            $bill_name = $_POST['bill_name'];

            $ledger_id = $_POST['ledger_id'];
            $returnArr = $this->entrymodel->getBillByBillname($bill_name, $ledger_id);
            $resultArr = array();
            $ledger_type = '';
            $resultArr['credit_days'] = $returnArr['credit_days'];
            $resultArr['credit_date'] = $returnArr['credit_date'];
            $resultArr['bill_amount'] = str_replace('-', '', $returnArr['cr_sum'] - $returnArr['dr_sum']);
            if ($returnArr['cr_sum'] > $returnArr['dr_sum']) {
                $resultArr['dr_cr'] = 'Cr';
            } else {
                $resultArr['dr_cr'] = 'Dr';
            }


            if (empty($resultArr)) {
                echo json_encode(array('SUCCESS' => 0, 'MSG' => ''));
            } else {
                echo json_encode(array('SUCCESS' => 1, 'MSG' => '', 'MENU' => $resultArr));
            }
        } else {
            echo json_encode(array('SUCCESS' => 0, 'MSG' => 'This page only access by ajax'));
        }
    }

    public function check_bank_betails($ledgerId) {
        $bank_group_id = 10;
        $all_group_id = 10;
        $all_sub_group = $this->entrymodel->getAllSubGroup($bank_group_id);
        if (isset($all_sub_group->sub_id)) {
            $all_group_id.=',' . $all_sub_group->sub_id;
        }
        $get_all_ledger = $this->entrymodel->getAllLedger($all_group_id);
        foreach ($get_all_ledger as $val) {
            $ledger_id_arr[] = $val->id;
        }
        if (in_array($ledgerId, $ledger_id_arr)) {
            return "yes";
        } else {
            return "no";
        }
    }

    //check detors creditors group
    public function is_detors_creditors_group($ledgerId) {
        $debitors_group_id = 15;
        $creditors_group_id = 23;
        $all_group_id = '15,23';
        $all_debitors_sub_group = $this->entrymodel->getAllSubGroup($debitors_group_id);
        if (isset($all_debitors_sub_group->sub_id)) {
            $all_group_id.=',' . $all_debitors_sub_group->sub_id;
        }
        $all_creditors_sub_group = $this->entrymodel->getAllSubGroup($creditors_group_id);
        if (isset($all_creditors_sub_group->sub_id)) {
            $all_group_id.=',' . $all_creditors_sub_group->sub_id;
        }

        $get_all_ledger = $this->entrymodel->getAllLedger($all_group_id);
        foreach ($get_all_ledger as $val) {
            $ledger_id_arr[] = $val->id;
        }
        if (in_array($ledgerId, $ledger_id_arr)) {
            return "yes";
        } else {
            return "no";
        }
    }

    //get banking transaction type
    public function getTransactionTypes() {

        $transaction_types = $this->entrymodel->allTransactionType();

        if (count($transaction_types) == 0) {
            echo json_encode("");
        } else {

            $transactionName = '';

            $transactionName = '[';

            foreach ($transaction_types as $value) {
                $transactionName .= " { \"label\": \"$value->name\", \"value\": \"$value->id\"},";
            }

            $transactionName = substr($transactionName, 0, -1);
            $transactionName .= ' ]';

            echo $transactionName;
        }
    }

    //save temp bank data
    public function saveTempBankData() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $ledger_id = $this->input->post('popup_tr_ledger_id');
            $entry_id = $this->input->post('popup_tr_entry_id');
            $tr_type = $this->input->post('popup_tr_ledger_type');
            $tr_banking_transaction_type = $this->input->post('tr_banking_transaction_type');
            $tr_transaction_id_modal = $this->input->post('tr_transaction_id_modal');
            $instrument_no = $this->input->post('instrument_no');
            $tr_banking_instrument_date = $this->input->post('tr_banking_instrument_date');
            $tr_banking_bank_name = $this->input->post('tr_banking_bank_name');
            $tr_banking_branch_name = $this->input->post('tr_banking_branch_name');
            $tr_banking_ifsc_code = $this->input->post('tr_banking_ifsc_code');
            $tr_banking_bank_amount = $this->input->post('tr_banking_bank_amount');
            $this->form_validation->set_rules('popup_tr_ledger_id', 'Ledger', 'required');
            $this->form_validation->set_rules('popup_tr_entry_id', 'Entry Id', 'required');
            $this->form_validation->set_rules('popup_tr_ledger_type', 'Ledger Type', 'required');
            $this->form_validation->set_rules('tr_transaction_id_modal[]', 'Bank Transaction Type', 'required');
            $this->form_validation->set_rules('instrument_no[]', 'Instrument No', 'required');
            $this->form_validation->set_rules('tr_banking_instrument_date[]', 'Instrument Date', 'required');
            $this->form_validation->set_rules('tr_banking_bank_name[]', 'Bank Name', 'required');
            $this->form_validation->set_rules('tr_banking_branch_name[]', 'Branch Name', 'required');
            $this->form_validation->set_rules('tr_banking_ifsc_code[]', 'Ifsc Code', 'required');
            $this->form_validation->set_rules('tr_banking_bank_amount[]', 'Bank Amount', 'required|numeric');
            if ($this->form_validation->run() === TRUE) {
                $this->entrymodel->deleteTempBankDetails($ledger_id, $entry_id);
                $data = [];
                foreach ($tr_banking_transaction_type as $key => $row) {
                    $instrument_date = str_replace("/", "-", $tr_banking_instrument_date[$key]);
                    $item = array(
                        'ledger_id' => $ledger_id,
                        'entry_no' => $entry_id,
                        'transaction_type' => $tr_transaction_id_modal[$key],
                        'instrument_no' => $instrument_no[$key],
                        'instrument_date' => date("Y-m-d", strtotime($instrument_date)),
                        'bank_name' => $tr_banking_bank_name[$key],
                        'branch_name' => $tr_banking_branch_name[$key],
                        'ifsc_code' => $tr_banking_ifsc_code[$key],
                        'bank_amount' => $tr_banking_bank_amount[$key],
                        'create_date' => date('Y-m-d H:i:s')
                    );
                    $data[] = $item;
                }
                $res = $this->entrymodel->saveTempBankData($data);
                if ($res) {
                     $data_msg['is_detors_creditors_group'] = $this->is_detors_creditors_group($ledger_id);
                    $data_msg['res'] = 'success';
                } else {
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = 'Error In process.';
                }
            } else {
                $data_msg['res'] = 'validation_error';
                $data_msg['message'] = $this->form_validation->error_array();
            }
            echo json_encode($data_msg);
        }
    }

    public function ajax_update_transaction() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
        
            $tax_or_advance = $this->input->post('tax_or_advance');
            $advance_ledger_id = $this->input->post('advance_ledger_id');
            //service
            $expences_ledger_id = $this->input->post('expences_ledger_id');
            $service_amount = $this->input->post('service_amount');
            $tr_service_id = $this->input->post('tr_service_id');
            $service_product = $this->input->post('service_product');
            $tax_percentage = $this->input->post('tax_percentage');
            $cess_percentage = $this->input->post('cess_percentage');
            $igst_status = $this->input->post('igst_status');
            $cess_status = $this->input->post('cess_status');
            $export_status = $this->input->post('export_status');
            //end service
            $this->form_validation->set_rules('entry_id', 'Entry Id', 'required|numeric');
            $this->form_validation->set_rules('entry_number', 'Entry Number', 'required');
            $this->form_validation->set_rules('tr_ledger_id[]', 'Ledger', 'required|numeric');
            $this->form_validation->set_rules('tr_type[]', 'Ledger Type', 'required');
            $this->form_validation->set_rules('amount[]', 'Amount', 'required|numeric');
           $postdated = $this->input->post('postdated');
           $entry_type_id =$this->input->post('entry_type_id');
           $parent_id =$this->input->post('parent_id');
           
           $branch_id = $this->session->userdata('branch_id');
           
            if($postdated==1){
              $this->form_validation->set_rules('tr_date', 'Give a date', 'required|check_postdated_date');   
            }else{
            $this->form_validation->set_rules('tr_date', 'Give a date', 'required');
            }
            $total_cr = 0;
            $total_dr = 0;
            $entry_id = $this->input->post('entry_id');
            $tr_type = $this->input->post('tr_type');
            $tr_date = $this->input->post('tr_date');
            $tr_ledger = $this->input->post('tr_ledger');
            $tr_ledger_id = $this->input->post('tr_ledger_id');
            $amount = $this->input->post('amount');
            $narration = $this->input->post('tr_narration');
            $tr_date = str_replace("/", "-", $tr_date);
             $voucher_no = $this->input->post('voucher_no');
            $voucher_date = $this->input->post('voucher_date');
            $voucher_date = date("Y-m-d", strtotime(str_replace('/', '-', $voucher_date)));
            // foreach ($tr_type as $i => $type) {
            //     if ($type == 'Cr') {
            //         $total_cr+=$amount[$i];
            //     } else if ($type == 'Dr') {
            //         $total_dr+=$amount[$i];
            //     }
            // }
            
            // new calculation using hidden field
            $total_cr = $this->input->post('tr_total_cr');
            $total_dr = $this->input->post('tr_total_dr');


            $entry_type = $this->entrymodel->getEntryTypeById($entry_type_id);
            if ($this->form_validation->run() === TRUE && ($total_dr == $total_cr)) {
                 $recurring_freq = $this->input->post('recurring_freq');
            
            $selectedCurrency = $this->input->post('currency');
            $deleted=($postdated==1)?'2':'0';
                $data_msg['res'] = 'success';
                //currency
                $baseCurrency = $this->entrymodel->getDefoultCurrency();
                $currency_unit = $this->entrymodel->getCurrencyUnitById($baseCurrency['base_currency']);
                $base_unit = $currency_unit['unit_price'];
                //currency
                $new_array = [];
                foreach ($tr_type as $k => $row) {
                    $new_array[$row][] = $tr_ledger[$k];
                }

                $ledger_name_json = json_encode($new_array);
                $entry_data = array(
                    'tax_or_advance' => $tax_or_advance,
                    'create_date' => date("Y-m-d", strtotime($tr_date)),
                    'ledger_ids_by_accounts' => $ledger_name_json,
                    'dr_amount' => ($total_dr * $base_unit),
                    'cr_amount' => ($total_cr * $base_unit),
                    'unit_price_dr' => $total_dr,
                    'unit_price_cr' => $total_cr,
                    'narration' => $narration,
                    'voucher_no' => $voucher_no,
                    'voucher_date' => $voucher_date,
                    'deleted' => $deleted
                );
                $this->db->trans_begin();
                $res = $this->entrymodel->updateEntry($entry_data, $entry_id);
                $del_res = $this->entrymodel->deleteEntryDetails($entry_id);

                //update entry details
                foreach ($tr_ledger_id as $key => $value) {

                    $exist = $this->entrymodel->checkExistEntry($value, $entry_id);
                    $ledger_data = array(
                        'ladger_id' => $value,
                        'entry_id' => $entry_id,
                        'account' => $tr_type[$key],
                        'balance' => (($amount[$key]) * $base_unit),
                        'selected_currency' => $baseCurrency['base_currency'],
                        'unit_price' => $base_unit,
                        'status' => '1',
                        'deleted' => $deleted,
                        'narration' => $narration,
                        'branch_id' => $branch_id,
                        'create_date' => date("Y-m-d", strtotime($tr_date))
                    );
                    if (count($exist) > 0) {
                        $this->db->where('ladger_id', $value);
                        $this->db->where('entry_id', $entry_id);
                        $this->db->update('ladger_account_detail', $ledger_data);
                    } else {
                        $this->db->insert('ladger_account_detail', $ledger_data);
                    }
                }
                
                 //Closing Balance update 03052018
                $financial_year = get_financial_year();
                $from_date = date("Y-m-d", strtotime(current($financial_year)));
                $to_date = date("Y-m-t", strtotime(end($financial_year)));
                foreach ($tr_ledger_id as $key => $value) { 
                    $ledger_detail = $this->inventorymodel->getLedgerByLedgerIdByDate($value,$from_date,$to_date,$branch_id);
                    $where= array(
                        'branch_id'=>$branch_id,
                        'entry_id'=>0,
                        'ladger_id'=>$value
                    );
                    $closingValue['current_closing_balance'] = ($ledger_detail['account_type'] == 'Dr')?$ledger_detail['dr_balance'] - $ledger_detail['cr_balance'] + $ledger_detail['opening_balance']:$ledger_detail['cr_balance'] - $ledger_detail['dr_balance'] + $ledger_detail['opening_balance'];
                    $this->inventorymodel->updateClosingBalance($where,$closingValue);
                }
                
                
                //end update entry details
                // //delete all modal data
                $this->entrymodel->deleteBank($entry_id);
                $this->entrymodel->deleteTracking($entry_id);
                $this->entrymodel->deleteBilling($entry_id);
                //end delete all modal data
                //copy all modal data
                $new_entry_id=$entry_id;
                $this->entrymodel->copyBankDetails($entry_id,$new_entry_id,$tr_ledger_id);
                $this->entrymodel->copyTrackingDetails($entry_id,$new_entry_id);
                $this->entrymodel->copyBillingDetails($entry_id,$new_entry_id);
                //end copy all modal data
                //delete all modal data
                $this->entrymodel->deleteTempBank($entry_id);
                $this->entrymodel->deleteTempTracking($entry_id);
                $this->entrymodel->deleteTempBilling($entry_id);
                //end delete all modal data
                //service
                $this->entrymodel->deleteService($entry_id);
                 if (count($tr_service_id) > 0) {
                    $service_arr = [];
                    if($tax_or_advance==1){
                    $expences_ledger_id=$advance_ledger_id;
                    }
                    foreach ($tr_service_id as $k => $sr) {
                        $service_data = array(
                            'transaction_type'=>$tax_or_advance,
                            'entry_id' => $entry_id,
                            'expences_ledger_id' => $expences_ledger_id,
                            'service_product_id' => $sr,
                            'type' => $service_product[$k],
                            'service_amount' => $service_amount[$k],
                            'tax_percentage' => $tax_percentage[$k],
                            'cess_percentage' => (isset($cess_percentage[$k]))?$cess_percentage[$k]:0,
                            'igst_status' => (isset($igst_status))?$igst_status:0,
                            'cess_status' => (isset($cess_status))?$cess_status:0,
                            'export_status' => (isset($export_status))?$export_status:0,
                            'status' => 1
                        );
                        $service_arr[]=$service_data;
                    }
  
                    $this->entrymodel->insertService($service_arr);
                }
                //end service
                //recurring
                if(isset($recurring_freq) && ($recurring_freq=='Daily' || $recurring_freq=='Weekly' || $recurring_freq=='Monthly' || $recurring_freq=='Yearly')){
                    $this->updateRecurring($recurring_freq,date("Y-m-d", strtotime($tr_date)),$entry_id);
                }
                 $redirect_url = base_url('admin/transaction-list') . "/" .  str_replace(' ','-',strtolower(trim($entry_type['type']))).'/'.$entry_type['id'].'/all';
               
               //print url
               $print_url= base_url('admin/trasaction-details') . '.aspx/' . $entry_id;
                
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'save_err';
                    $data_msg['message'] = "There was an error please try again.";
                } else {
                    $this->db->trans_commit();
                    $data_msg['res'] = 'success';
                    $data_msg['redirect_url'] = $redirect_url;
                    $data_msg['print_url'] = $print_url;
                    $data_msg['message'] = "Transaction updated successfully";
                }
            } else {
                $errors = $this->form_validation->error_array();
                if ((is_numeric($total_dr) && is_numeric($total_cr)) && $total_dr != $total_cr) {
                    $errors['amount_err'] = 'Dr amount do not match with Cr amount';
                }
                $data_msg['res'] = 'error';
                $data_msg['message'] = current($errors);
            }
            
            // log update
            $this->load->model('front/usermodel', 'currentusermodel');
            $log = array(
                'user_id' => $this->session->userdata('admin_uid'),
                'branch_id' => $this->session->userdata('branch_id'),
                'module' => strtolower($entry_type['type']),
                'action' => '`' . $this->input->post('entry_number') . '` <b>edited</b>',
                'performed_at' => date('Y-m-d H:i:s', time())
            );
            $this->currentusermodel->updateLog($log);

            echo json_encode($data_msg);
        }
    }
    
    public function updateRecurring($recurring_freq,$created_date,$entryId){
      $data=array(
          'entry_id'=>$entryId,
          'recurring_date'=>$created_date,
          'frequency'=>$recurring_freq,
          'status'=>1,
          'create_date'=>date("Y-m-d H:i:s")
      );  
      $this->entrymodel->updateRecurringData($data,$entryId);
        
    }

    function generateEntryNo($entry_type_id) {
        $this->load->model('accounts/entry');
        $entry_type = $this->entry->getEntryTypeById($entry_type_id);
        $countid = 1;
        $today = date("Y-m-d H:i:s");
        $auto_number = $this->entry->getNoOfByTypeId($entry_type_id,$entry_type['parent_id'], $today, $entry_type['strating_date']);
        $start_length = $entry_type['starting_entry_no'];
        $countid = $countid + $auto_number['total_transaction'];
        $id_length = strlen($countid);
        if ($start_length > $id_length) {
            $remaining = $start_length - $id_length;
            $uniqueid = $entry_type['prefix_entry_no'] . str_repeat("0", $remaining) . $countid . $entry_type['suffix_entry_no'];
        } else {
            $uniqueid = $entry_type['prefix_entry_no'] . $countid . $entry_type['suffix_entry_no'];
        }
        return $uniqueid;
    }

    public function ajax_copy_transaction() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('entry_number', 'Entry Number', 'required');
            $this->form_validation->set_rules('tr_date', 'Transaction Date', 'required');
            $this->form_validation->set_rules('tr_ledger_id[]', 'Ledger', 'required|numeric');
            $this->form_validation->set_rules('tr_type[]', 'Ledger Type', 'required');
            $this->form_validation->set_rules('amount[]', 'Amount', 'required|numeric');
            $entry_number = $this->input->post('entry_number');
            $entry_type_id = $this->input->post('entry_type_id');
            $parent_id = $this->input->post('parent_id');
            $old_entry_id = $this->input->post('entry_id');
            if ($entry_number == 'Auto' || $entry_number == null || $entry_number == '') {
                $entry_number = $this->generateEntryNo($entry_type_id);
            }
            $total_cr = 0;
            $total_dr = 0;
            $tr_type = $this->input->post('tr_type');
            $tr_date = $this->input->post('tr_date');
            $tr_ledger = $this->input->post('tr_ledger');
            $tr_ledger_id = $this->input->post('tr_ledger_id');
            $amount = $this->input->post('amount');
            $narration = $this->input->post('tr_narration');
            $tr_date = str_replace("/", "-", $tr_date);
            $tr_date = date("Y-m-d", strtotime(str_replace('/', '-', $tr_date)));
            foreach ($tr_type as $i => $type) {
                if ($type == 'Cr') {
                    $total_cr+=$amount[$i];
                } else if ($type == 'Dr') {
                    $total_dr+=$amount[$i];
                }
            }
            if ($this->form_validation->run() === TRUE && ($total_dr == $total_cr)) {
                //currency
                $baseCurrency = $this->entrymodel->getDefoultCurrency();
                $currency_unit = $this->entrymodel->getCurrencyUnitById($baseCurrency['base_currency']);
                $base_unit = $currency_unit['unit_price'];
                $selected_currency = $currency_unit['id'];
                //end currency
                for ($i = 0; $i < count($tr_type); $i++) {
                    $new_array[$tr_type[$i]][] = $tr_ledger[$i];
                }
                $ledger_name_json = json_encode($new_array);
                $is_inventry = 0;
                $user_id = $this->session->userdata('admin_uid');
                $order_id = "";
                $sub_voucher = 0;
                // For Entry Table
                $entry = array(
                    'entry_no' => $entry_number,
                    'create_date' => $tr_date,
                    'ledger_ids_by_accounts' => $ledger_name_json,
                    'dr_amount' => ($total_dr * $base_unit),
                    'cr_amount' => ($total_cr * $base_unit),
                    'unit_price_dr' => $total_dr,
                    'unit_price_cr' => $total_cr,
                    'entry_type_id' => ($parent_id==0)?$entry_type_id:$parent_id,
                    'sub_voucher' => ($parent_id!=0)?$entry_type_id:$parent_id,
                    'user_id' => $user_id,
                    'is_inventry' => $is_inventry,
                    'order_id' => $order_id,
                    'narration' => $narration,
                    'deleted' => '0'
                );
                $this->db->trans_begin();
                $entryId = $this->entrymodel->saveEntry($entry);
                $ledgerDetails = array();
                foreach ($tr_type as $i => $type) {
                    $ledgerDetails[$i]['account'] = $type;
                    $ledgerDetails[$i]['balance'] = ($amount[$i] * $base_unit);
                    $ledgerDetails[$i]['entry_id'] = $entryId;
                    $ledgerDetails[$i]['ladger_id'] = $tr_ledger_id[$i];
                    $ledgerDetails[$i]['create_date'] = $tr_date;
                    $ledgerDetails[$i]['narration'] = $narration;
                    $ledgerDetails[$i]['selected_currency'] = $selected_currency;
                    $ledgerDetails[$i]['unit_price'] = $base_unit;
                    $ledgerDetails[$i]['deleted'] = '0';
                }
                 $this->entrymodel->saveEntryDetails($ledgerDetails);
                 //copy all modal data
                $this->entrymodel->copyBankDetails($old_entry_id,$entryId);
                $this->entrymodel->copyTrackingDetails($old_entry_id,$entryId);
                $this->entrymodel->copyBillingDetails($old_entry_id,$entryId);
                //end copy all modal data
                //delete all modal data
                $this->entrymodel->deleteTempBank($old_entry_id);
                $this->entrymodel->deleteTempTracking($old_entry_id);
                $this->entrymodel->deleteTempBilling($old_entry_id);
                //end delete all modal data
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'save_err';
                    $data_msg['message'] = "There was an error please try again.";
                } else {
                    $this->db->trans_commit();
                    $data_msg['res'] = 'success';
                    $data_msg['message'] = "Transaction Copy successfully";
                }
                
            } else {
                $errors = $this->form_validation->error_array();
                if ((is_numeric($total_dr) && is_numeric($total_cr)) && $total_dr != $total_cr) {
                    $errors['amount_err'] = 'Dr amount do not match with Cr amount';
                }
                $data_msg['res'] = 'error';
                $data_msg['message'] = current($errors);
            }
            echo json_encode($data_msg);
        }
    }

    public function getTransactionLedgerDetails() {

        if (isset($ledger_details)) {
            unset($ledger_details);
        }

        $ledger_details = array();

        $ledger = $_POST['ledger'];
        $transaction_type_id = $_POST['trans_type']; 
        $entry_type = $this->entrymodel->getEntryTypeById($transaction_type_id);
        if ($transaction_type_id == 1 || $transaction_type_id == 2 || $entry_type['parent_id']==1 || $entry_type['parent_id']==2) {

            $array_group = [32, 37];
            $ledger_details = $this->entrymodel->getLedger($array_group);

            for ($i = 0; $i < count($array_group); $i++) {
                array_push($ledger_details, $array_group[$i]);
            }

            $ledger_final = $this->entrymodel->getLedgerFinal($ledger, $ledger_details);
        } else if ($transaction_type_id == 4 || $entry_type['parent_id']==4) {

            $array_group = [10, 11];
            $ledger_details = $this->entrymodel->getLedger($array_group);

            for ($i = 0; $i < count($array_group); $i++) {
                array_push($ledger_details, $array_group[$i]);
            }

            $ledger_final = $this->entrymodel->getLedgerFinal($ledger, $ledger_details);
        } else if ($transaction_type_id == 3 || $entry_type['parent_id']==3) {

            $array_group = [10, 11];
            $ledger_details = $this->entrymodel->getLedger($array_group);

            for ($i = 0; $i < count($array_group); $i++) {
                array_push($ledger_details, $array_group[$i]);
            }

            $ledger_final = $this->entrymodel->getLedgerFinalIn($ledger, $ledger_details);
        } else if ($transaction_type_id == 5 || $entry_type['parent_id']==5) {

            $array_group = [15, 10, 11, 37, 21];
            $ledger_details = $this->entrymodel->getLedger($array_group);

            for ($i = 0; $i < count($array_group); $i++) {
                array_push($ledger_details, $array_group[$i]);
            }

            $ledger_final = $this->custommodel->getLedgerFinalIn($ledger, $ledger_details);
        } else if ($transaction_type_id == 6 || $entry_type['parent_id']==6) {

            $array_group = [23, 10, 11, 32, 21];
            $ledger_details = $this->entrymodel->getLedger($array_group);

            for ($i = 0; $i < count($array_group); $i++) {
                array_push($ledger_details, $array_group[$i]);
            }

            $ledger_final = $this->entrymodel->getLedgerFinalIn($ledger, $ledger_details);
        }







        $ledgerName = '';

        $ledgerName = '[';

        foreach ($ledger_final as $value) {
            $ledgerName .= " { \"label\": \"$value->ladger_name\", \"value\": \"$value->id\"},";
        }

        $ledgerName = substr($ledgerName, 0, -1);
        $ledgerName .= ' ]';

        echo $ledgerName;
    }

    public function getLedgerType() {
        $data = [];
        if ($this->input->is_ajax_request()) {
            $ledger_id = $this->input->post('ledger_id');
            $ledger = $this->entrymodel->getLedgerType($ledger_id);
            if ($ledger) {
                $data['type'] = $ledger->account_type;
            }
            echo json_encode($data);
        }
    }

}
