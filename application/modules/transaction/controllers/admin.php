<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class admin extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->model('admin/trackingmodel');
        $this->load->model('accounts/group');
        $this->load->model('accounts/account');
        $this->load->model('admin/custommodel');
        $this->load->model('accounts/entry');
        $this->load->model('admin/financialyearmodel');
        $this->load->model('transaction_inventory/inventory/inventorymodel');
        $this->load->helper('financialyear');
        $this->load->library('session');
         admin_authenticate();
    }

    public function index($id = NULL) {
        
        $entry_type = $this->entry->getEntryTypeById($id);
       //access
        if($id==1 || $entry_type['parent_id']== 1){
            $module=84;
        }elseif ($id==2 || $entry_type['parent_id']== 2) {
         $module=85;   
        }elseif ($id==3 || $entry_type['parent_id']== 3) {
         $module=86;   
        }elseif ($id==4 || $entry_type['parent_id']== 4) {
         $module=87;   
        }elseif ($id==5 || $entry_type['parent_id']== 5) {
         $module=183;   
        }elseif ($id==6 || $entry_type['parent_id']== 6) {
         $module=184;   
        }
         user_permission($module,'add');
        //access
        $this->load->helper('text');
        $data = array();
        $data['ledger'] = array();
        $ledger_id = NULL;

        if ($id == NULL) {
            $id = 1;
        }


        if ($ledger_id != 0) {
            $where = array(
                'ladger.id' => $ledger_id,
                'ladger.status' => 1,
                'ladger.deleted' => 0
            );
            $data['ledger'] = $this->account->getLedgerDetailsById($where);
        }
        $data['groups'] = $this->custommodel->getAllGroups();
        $data['contacts'] = $this->account->getContact();
        
        $data['voucher'] = $entry_type['type'];
        $data['voucher_id'] = $id;
        $data['parent_id'] = $entry_type['parent_id'];
        $data['transaction_type_id'] = $id;


        $entry_type_id = $id;
        $entry_type = $this->entry->getEntryTypeById($entry_type_id);
        $data['auto_no_status'] = $entry_type['transaction_no_status'];

        $date_type = $this->custommodel->checkAutoDate();
        $data['auto_date'] = $date_type['skip_date_create'];
        $data['recurring'] = $date_type['want_recurring'];
        $ledger_details = array();



        $ledger_code_status = $this->account->getLedgerCodeStatus();
        $data['ledger_code_status'] = $ledger_code_status['ledger_code_status'];
        $data['currency'] = $this->custommodel->getAllCurrency();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Transaction');
        $this->layouts->render('admin/transaction_entry', $data, 'admin');
    }
    
    
   

    public function ajaxGetDebtorsArray() {

        if ($_POST['action'] == "debtors_array") {

            $array_group_sundry_debtors = [15];
            $ledger_details_sundry_debtors = $this->custommodel->getLedger($array_group_sundry_debtors);

            for ($i = 0; $i < count($array_group_sundry_debtors); $i++) {
                array_push($ledger_details_sundry_debtors, $array_group_sundry_debtors[$i]);
            }

            $data['sundry_debtors_ledger_final'] = $this->custommodel->getLedgerFinalIn("", $ledger_details_sundry_debtors);
            echo json_encode($data);
        }
    }

    public function ajaxGetCreditorsArray() {

        if ($_POST['action'] == "creditors_array") {

            $array_group_sundry_creditors = [23];
            $ledger_details_sundry_creditors = $this->custommodel->getLedger($array_group_sundry_creditors);

            for ($i = 0; $i < count($array_group_sundry_creditors); $i++) {
                array_push($ledger_details_sundry_creditors, $array_group_sundry_creditors[$i]);
            }

            $data['sundry_creditors_ledger_final'] = $this->custommodel->getLedgerFinalIn("", $ledger_details_sundry_creditors);

            echo json_encode($data);
        }
    }

    public function ajaxGetCashBankArray() {

        if ($_POST['action'] == "cash_bank_array") {

            $array_group_bank_cash = [10, 11];
            $all_group_bank_cash = $this->custommodel->getGroups($array_group_bank_cash);

            $data['cash_bank_ledger'] = $this->custommodel->getAllLedger($all_group_bank_cash);
            echo json_encode($data);
        }
    }

    public function ajaxGetPurchaseArray() {

        if ($_POST['action'] == "purchase_array") {

            $array_group_purchase = [32];
            $ledger_details_purchase = $this->custommodel->getLedger($array_group_purchase);

            for ($i = 0; $i < count($array_group_purchase); $i++) {
                array_push($ledger_details_purchase, $array_group_purchase[$i]);
            }

            $data['purchase_ledger_final'] = $this->custommodel->getLedgerFinalIn("", $ledger_details_purchase);

            echo json_encode($data);
        }
    }

    public function ajaxGetSalesArray() {

        if ($_POST['action'] == "sales_array") {

            $array_group_sales = [37];
            $ledger_details_sales = $this->custommodel->getLedger($array_group_sales);

            for ($i = 0; $i < count($array_group_sales); $i++) {
                array_push($ledger_details_sales, $array_group_sales[$i]);
            }

            $data['sales_ledger_final'] = $this->custommodel->getLedgerFinalIn("", $ledger_details_sales);

            echo json_encode($data);
        }
    }

    public function getLedgerDetails() {

        if (isset($ledger_details)) {
            unset($ledger_details);
        }
        $ledger_details = array();

        $ledger = $_POST['ledger'];
        $transaction_type_id = $_POST['trans_type'];
        $entry_type = $this->entry->getEntryTypeById($transaction_type_id);
        $parent_id=$entry_type['parent_id'];
        if ($transaction_type_id == 1 || $transaction_type_id == 2 || $parent_id == 1 || $parent_id == 2) {

//            $array_group = [32, 37];
            $array_group = [9,12, 16,20];
            $ledger_details = $this->custommodel->getGroups($array_group);

//            for ($i = 0; $i < count($array_group); $i++) {
//                array_push($ledger_details, $array_group[$i]);
//            }
            $gorup_ids = explode(",",$ledger_details);

            $ledger_final = $this->custommodel->getLedgerFinalIn($ledger, $gorup_ids);
        } else if ($transaction_type_id == 4 || $parent_id == 4) {

            $array_group = [10, 11];
            $ledger_details = $this->custommodel->getLedger($array_group);

            for ($i = 0; $i < count($array_group); $i++) {
                array_push($ledger_details, $array_group[$i]);
            }

            $ledger_final = $this->custommodel->getLedgerFinal($ledger, $ledger_details);
        } else if ($transaction_type_id == 3 || $parent_id == 3) {

            $array_group = [10, 11];
            $ledger_details = $this->custommodel->getLedger($array_group);

            for ($i = 0; $i < count($array_group); $i++) {
                array_push($ledger_details, $array_group[$i]);
            }

            $ledger_final = $this->custommodel->getLedgerFinalIn($ledger, $ledger_details);
        } else if ($transaction_type_id == 5 || $parent_id == 5) {

            $array_group = [15, 10, 11, 37, 21, 30, 35];
            $ledger_details = $this->custommodel->getLedger($array_group);

            for ($i = 0; $i < count($array_group); $i++) {
                array_push($ledger_details, $array_group[$i]);
            }

            $ledger_final = $this->custommodel->getLedgerFinalIn($ledger, $ledger_details);
        } else if ($transaction_type_id == 6 || $parent_id == 6) {

            $array_group = [23, 10, 11, 32, 21, 30, 35];
            $ledger_details = $this->custommodel->getLedger($array_group);

            for ($i = 0; $i < count($array_group); $i++) {
                array_push($ledger_details, $array_group[$i]);
            }

            $ledger_final = $this->custommodel->getLedgerFinalIn($ledger, $ledger_details);
        }
        
        
        
        $ledgerName = '';

        $ledgerName = '[';

        foreach ($ledger_final as $value) {
            //For Closing Balance
            $account_type = '';
            if($value->current_closing_balance >= 0){
                $account_type = $value->account;
                $current_closing_balance = $value->current_closing_balance;
            }else{
                if($value->account == 'Cr'){
                    $account_type = 'Dr';
                }
                if($value->account == 'Dr'){
                    $account_type = 'Cr';
                }
                $current_closing_balance = abs($value->current_closing_balance);
            }
            
            $ledgerName .= " { \"label\": \"$value->ladger_name ($value->ledger_code)    [Cur. Bal. $current_closing_balance - $account_type]\", \"value\": \"$value->id\"},";
        }

        $ledgerName = substr($ledgerName, 0, -1);
        $ledgerName .= ' ]';

        echo $ledgerName;
    }

    public function getLedgerExtraDetails() {
        if ($this->input->post('action') == "get-ledger-extra-details") {

            $ledgerId = $this->input->post('ledgerId');
            $transactionType = $this->custommodel->getTType($ledgerId);
            $hasTracking = $this->custommodel->getTTracking($ledgerId);
            $ledgerName = $this->custommodel->getLedgerName($ledgerId);

            $hasBilling = $this->custommodel->getTBilling($ledgerId);
            $hasService = $this->custommodel->getServiceStatus($ledgerId);

            $data = array();

            $data['transactionType'] = $transactionType->account_type;
            $data['ledgerName'] = $ledgerName->ladger_name;

            $data['branch_id'] = $ledgerName->branch_id;
            $data['hasTracking'] = $hasTracking->tracking_status;
            $data['hasBankingDetails'] = $this->check_bank_betails($ledgerId);
            $data['hasBilling'] = $hasBilling->bill_details_status;
            $data['hasService'] = $hasService->service_status;
            $data['isExpencesGroup'] = $this->check_expences_group($ledgerId);
            $data['isCreditorDebitorGroup'] = $this->check_creditor_debitor_group($ledgerId);


            // echo $transactionType->account_type;
            echo json_encode($data);
        }
    }

    public function check_creditor_debitor_group($ledgerId) {
        $data_msg = [];
        $group_id_arr = [];
        $ledger_id_arr = [];
        $group_id = array(15, 23);
        $all_sub_group = $this->custommodel->getAllSubGroup($group_id);
        $group_id_arr = $all_sub_group;
        $get_all_ledger = $this->entry->getAllLedger($group_id_arr);
        foreach ($get_all_ledger as $val) {
            $ledger_id_arr[] = $val->id;
        }
        if (in_array($ledgerId, $ledger_id_arr)) {
            $shipping = $this->custommodel->saveShippingAddress($ledgerId);
            $data_msg['country'] = isset($shipping->country) ? $shipping->country : '';
            $data_msg['state'] = isset($shipping->state) ? $shipping->state : '';
            $data_msg['res'] = true;
        } else {
            $data_msg['res'] = false;
        }
        return $data_msg;
    }

    public function check_expences_group($ledgerId) {
        // $data_msg = [];
        $group_id_arr = [];
        $ledger_id_arr = [];
        $group_id = array(2);

        $all_sub_group = $this->custommodel->getAllSubGroup($group_id);
        $group_id_arr = $all_sub_group;
        $get_all_ledger = $this->entry->getAllLedger($group_id_arr);
        foreach ($get_all_ledger as $val) {
            $ledger_id_arr[] = $val->id;
        }
        if (in_array($ledgerId, $ledger_id_arr)) {
            return "yes";
        } else {
            return "no";
        }
    }

    public function check_bank_betails($ledgerId) {
        // $data_msg = [];
        $group_id_arr = [];
        $ledger_id_arr = [];
        $group_id = array(10);

        $all_sub_group = $this->custommodel->getAllSubGroup($group_id);
        $group_id_arr = $all_sub_group;
        $get_all_ledger = $this->entry->getAllLedger($group_id_arr);
        foreach ($get_all_ledger as $val) {
            $ledger_id_arr[] = $val->id;
        }
        //$ledger_id = $this->input->post('ledger_id');
        if (in_array($ledgerId, $ledger_id_arr)) {
            // $data_msg['res'] = 'success';
            return "success";
        } else {
            // $data_msg['res'] = 'error';
            return "error";
        }
        //echo json_encode($data_msg);
    }

    public function getTrackingName() {
        $tracking = $_POST['tracking'];
        $trackingArr = $_POST['trackingArr'];

        $trackingArr = json_decode($trackingArr);

        $tracking_details = $this->custommodel->getTTrackingName($tracking, $trackingArr);

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

    public function getTransactionTypes() {

        $transaction_types = $this->entry->allTransactionType();

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

    public function getBillByReferenceLedgerId() {

        $transaction_type_id = $_POST['transaction_type_id'];
        $parent_id = $_POST['parent_id'];
        $bill_name = $_POST['bill_name'];
        $bill_name = trim(substr($bill_name, 0, strpos($bill_name, ":")));
        $ledger_id = $_POST['ledger_id'];
        $total_bill_array = json_decode($_POST['total_bill_array']);
        
        
        $group_id = $this->custommodel->getLedgerName($ledger_id);
        //this condition is used for billwise details show logicaly on respect to debtors or creditors 
        if((in_array($group_id->group_id, [15,10,11]) && ($transaction_type_id == 1 || $parent_id == 1)) || (in_array($group_id->group_id, [23,10,11]) && ($transaction_type_id == 2 || $parent_id == 2))){
            $where = '';

            if (!empty($total_bill_array)) {
                $billCode = '';
                foreach ($total_bill_array as $value) {
                    $billCode .= $value . ',';
                }
                $billCode = rtrim($billCode, ',');
                $where = " AND bill_name NOT IN ('" . $billCode . "')";
            }

            // print_r($total_bill_array); exit;

            $ledge = array();
            $ledger_name = $this->custommodel->getBillNameForJson($ledger_id, $bill_name, $where);

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


                        // foreach ($transaction_types as $value) {
                        $transactionName .= " { \"label\": \"$ledge\", \"value\": \"$billName\"},";
                        // }
                    }
                }

                $transactionName = substr($transactionName, 0, -1);
                $transactionName .= ' ]';

                echo $transactionName;
            } else {
                echo json_encode("");
            }
        }else{
            echo json_encode("");
        }
        // else {
        //     $ledge[] = '';
        // }
        // echo json_encode($ledge);
    }

    public function getBillByBillnameLedgerId() {
        if ($this->input->post('ajax', TRUE)) {

            $bill_name = $_POST['bill_name'];

            $ledger_id = $_POST['ledger_id'];
            $returnArr = $this->trackingmodel->getBillByBillname($bill_name, $ledger_id);
            $resultArr = array();
            $ledger_type = '';
            //$resultArr['bill_name'] = $returnArr['bill_name'];
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

    public function getFinancialYear() {

        if ($this->input->post('action') == "get-financial-date") {
            $financial_year_start = $this->financialyearmodel->getFinancialYear();

            echo $financial_year_start->finalcial_year_from;
        }
    }

    public function alpha_dash_space($str) {
        return (!preg_match("/^([-a-z_ ])+$/i", $str)) ? FALSE : TRUE;
    }

    public function ajax_save_form_data() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            echo "<pre>";
            print_r($_POST);
            die();
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
            $recurring_freq = $this->input->post('recurring_freq');
            $postdated = $this->input->post('postdated');
            $selectedCurrency = $this->input->post('currency');
            $voucher_no = $this->input->post('voucher_no');
            $voucher_date = $this->input->post('voucher_date');

            $tr_branch_id = $this->input->post('tr_branch_id');
            $branch_entry_no = $this->input->post('branch_entry_no');

            $entry_number = $_POST['entry_number'];
            $deleted = ($postdated == 1) ? '2' : '0';
            if ($entry_number != 'Auto' || $entry_number != null || $entry_number != '') {
                $this->form_validation->set_rules('entry_number', 'Entry Number', 'required|callback_alpha_dash_space');
            }
            if ($postdated == 1) {
                $this->form_validation->set_rules('date_form', 'Give a date', 'required|check_postdated_date');
            } else {
                $this->form_validation->set_rules('date_form', 'Give a date', 'required');
            }
            $this->form_validation->set_rules('tr_ledger_id[]', 'Select Ledger name', 'required');
            $this->form_validation->set_rules('tr_type[]', 'Select Account type', 'required');

            $this->form_validation->set_rules('amount[]', 'Amount', 'required|numeric');

            if ($this->form_validation->run() === TRUE) {

                $voucher_date = date("Y-m-d", strtotime(str_replace('/', '-', $voucher_date)));
                $dataBilling = isset($_POST['bill']) ? $_POST['bill'] : null;
                $dataBanking = isset($_POST['bank']) ? $_POST['bank'] : null;
                $dataTracking = isset($_POST['tracking']) ? $_POST['tracking'] : null;

                $isDataNewRef = $_POST['newRefCall'];


                $entry_type_id = $_POST['entry_type'];
                 $entry_type = $this->entry->getEntryTypeById($entry_type_id);
                 

                //generate auto entry no
                if ($postdated == 0 && ($entry_number == 'Auto' || $entry_number == null || $entry_number == '')) {
                    //$entry_type_id= $this->input->post('entry_type_id');
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
                    $entry_number = $uniqueid;
                } else {
                    $entry_number = $entry_number;
                }
                
                // log update
                 $this->load->model('front/usermodel', 'currentusermodel');
                 $log = array(
                    'user_id' => $this->session->userdata('admin_uid'),
                    'branch_id' => $this->session->userdata('branch_id'),
                    'module' => strtolower($entry_type['type']),
                    'action' => '`' . $entry_number . '` <b>created</b>',
                    'performed_at' => date('Y-m-d H:i:s', time())
                );
                $this->currentusermodel->updateLog($log);


                $created_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['date_form'])));
                $ledger_id = $_POST['tr_ledger_id'];
                $ledger_name = $_POST['tr_ledger'];
                $account_type = $_POST['tr_type'];
                $ledger_amount = $this->input->post('amount');
                $narration = $_POST['tr_narration'];


                $sub_voucher = 0;
                $is_inventry = 0;
                $user_id = $this->session->userdata('admin_uid');
                $branch_id = $this->session->userdata('branch_id');
                $order_id = "";

                $entry = array();
                $new_array = array();
                $count = count($ledger_id);
                $totalDr = 0;
                $totalCr = 0;

                $baseCurrency = $this->entry->getDefoultCurrency();
                if ($selectedCurrency) {
                    $currency_unit = $this->entry->getCurrencyUnitById($selectedCurrency);
                } else {
                    $currency_unit = $this->entry->getCurrencyUnitById($baseCurrency['base_currency']);
                }




                $base_unit = $currency_unit['unit_price'];
                $selected_currency = $currency_unit['id'];

                $bill_data = array();


                if ($isDataNewRef == 1) {

                    $newReferenceLedgerArray = $_POST['newReferenceLedgerArray'];

                    for ($i = 0; $i < count($newReferenceLedgerArray); $i++) {

                        $ledgeId = $newReferenceLedgerArray[$i]["ledgerId"];

                        $hasBilling = $this->custommodel->getTBilling($ledgeId);
                        $hasBillingFinal = $hasBilling->bill_details_status;

                        if ($hasBillingFinal == "1") {

                            /* Fetch data */
                            $ledgeDet = $this->custommodel->getNewRefLedgerDetails($ledgeId);

                            $credit_date_get = $ledgeDet->credit_date;
                            $credit_limit_get = $ledgeDet->credit_limit;




                            if ($entry_type_id == 5 || $entry_type['parent_id']==5) {
                                $getDiffDrCrBilling = $this->custommodel->getDiffDrCrBillingSales($ledgeId);
                            } else if ($entry_type_id == 6 || $entry_type['parent_id']==6) {
                                $getDiffDrCrBilling = $this->custommodel->getDiffDrCrBillingPurchase($ledgeId);
                            }



                            $diff = $getDiffDrCrBilling->diff;

                            if ($newReferenceLedgerArray[$i]["drAmt"] > 0) {
                                $total = $newReferenceLedgerArray[$i]["drAmt"] + $diff;
                                $amount = $newReferenceLedgerArray[$i]["drAmt"];
                            } else if ($newReferenceLedgerArray[$i]["crAmt"] > 0) {
                                $total = $newReferenceLedgerArray[$i]["crAmt"] + $diff;
                                $amount = $newReferenceLedgerArray[$i]["crAmt"];
                            }





                            if ($total <= $credit_limit_get) {



                                $bill_data[$i]['branch_id'] = $this->session->userdata('branch_id');
                                $bill_data[$i]['ledger_id'] = $ledgeId;
                                $bill_data[$i]['dr_cr'] = $newReferenceLedgerArray[$i]["accType"];
                                $bill_data[$i]['ref_type'] = 'New Reference';
                                $bill_data[$i]['bill_name'] = $entry_number;
                                $bill_data[$i]['credit_days'] = $credit_date_get;
                                $bill_data[$i]['credit_date'] = date('Y-m-d', strtotime("+" . $credit_date_get . " days"));
                                $bill_data[$i]['bill_amount'] = ($amount * $base_unit);
                                $bill_data[$i]['entry_id'] = "";
                                $bill_data[$i]['created_date'] = $created_date;
                            } else {

                                $data_msg['res'] = 'save_err';
                                $data_msg['message'] = "Your credit limit has exceeded!";

                                echo json_encode($data_msg);
                                exit;
                            }
                        }
                    }
                }

                foreach ($account_type as $i => $type) {
                    if ($type == 'Cr') {
                        $totalCr+=$ledger_amount[$i];
                    } else if ($type == 'Dr') {
                        $totalDr+=$ledger_amount[$i];
                    }
                }

                for ($i = 0; $i < $count; $i++) {

                    $new_array[$account_type[$i]][] = $ledger_name[$i];
                }

                $ledger_name_json = json_encode($new_array);







                // For Entry Table
                $entry = array(
                    'entry_no' => $entry_number,
                    'tax_or_advance' => $tax_or_advance,
                    'create_date' => $created_date,
                    'ledger_ids_by_accounts' => $ledger_name_json,
                    'dr_amount' => ($totalDr * $base_unit),
                    'cr_amount' => ($totalCr * $base_unit),
                    'unit_price_dr' => $totalDr,
                    'unit_price_cr' => $totalCr,
                    'entry_type_id' => ($entry_type['parent_id']==0)?$entry_type_id:$entry_type['parent_id'],
                    'sub_voucher' => ($entry_type['parent_id']!=0)?$entry_type_id:$entry_type['parent_id'],
                    'company_id' => $branch_id,
                    'user_id' => $user_id,
                    'is_inventry' => $is_inventry,
                    'order_id' => $order_id,
                    'voucher_no' => $voucher_no,
                    'voucher_date' => $voucher_date,
                    'narration' => $narration,
                    'deleted' => $deleted
                );

                $this->db->trans_begin();

                $entryId = $this->custommodel->setEntry($entry);
                if($tr_branch_id){
                     if($branch_entry_no){
                    $this->custommodel->updateBranchTransaction($branch_entry_no);     
                    }else{
                 $branch=$this->custommodel->getBranchName();
                 $notification_msg="You have a new ".$entry_type['type']." (".$entry_number.") transaction of Rs. ".$totalDr." from ".$branch->company_name." Branch.";
                 $branch_data=array(
                     'from_branch'=>$this->session->userdata('branch_id'),
                     'to_branch'=>$tr_branch_id,
                     'entry_id'=>$entryId,
                     'entry_no'=>$entry_number,
                     'notification_msg'=>$notification_msg,
                     'status'=>1,
                     'created_at'=>date("Y-m-d H:i:s"),
                 ); 
                  $this->custommodel->addBranchTransaction($branch_data);
                    }
                }
                // $entryId  = 0;
                // For Ladger Account Details Table
                 
                $ledgerDetails = array();
                $balance = 0;
                for ($i = 0; $i < $count; $i++) {
                    if (!empty($ledger_amount[$i])) {
                        $balance = $ledger_amount[$i];
                    }
                    
                    $ledgerDetails[$i]['branch_id'] = $branch_id;
                    $ledgerDetails[$i]['account'] = $account_type[$i];
                    $ledgerDetails[$i]['balance'] = ($balance * $base_unit);
                    $ledgerDetails[$i]['entry_id'] = $entryId;
                    $ledgerDetails[$i]['ladger_id'] = $ledger_id[$i];
                    $ledgerDetails[$i]['create_date'] = $created_date;
                    $ledgerDetails[$i]['narration'] = $narration;
                    $ledgerDetails[$i]['selected_currency'] = $selected_currency;
                    $ledgerDetails[$i]['unit_price'] = $base_unit;
                    $ledgerDetails[$i]['deleted'] = $deleted;
                    
                }

                $this->custommodel->setEntryDetails($ledgerDetails);
                
                 
                //Closing Balance update 03052018
                $financial_year = get_financial_year();
                $from_date = date("Y-m-d", strtotime(current($financial_year)));
                $to_date = date("Y-m-t", strtotime(end($financial_year)));
                for ($j = 0; $j < $count; $j++) {
                    $ledger_detail = $this->inventorymodel->getLedgerByLedgerIdByDate($ledger_id[$j],$from_date,$to_date,$branch_id);
                    $where= array(
                        'branch_id'=>$branch_id,
                        'entry_id'=>0,
                        'ladger_id'=>$ledger_id[$j]
                    );
                    $closingValue['current_closing_balance'] = ($ledger_detail['account_type'] == 'Dr')?$ledger_detail['dr_balance'] - $ledger_detail['cr_balance'] + $ledger_detail['opening_balance']:$ledger_detail['cr_balance'] - $ledger_detail['dr_balance'] + $ledger_detail['opening_balance'];
                    $this->inventorymodel->updateClosingBalance($where,$closingValue);
                }
                

                // For Billwise Details Auto submission (without popup)

                if (count($bill_data) > 0) {

                    for ($k = 0; $k < count($bill_data); $k++) {

                        $bill_data[$k]['entry_id'] = $entryId;
                    }

                    $this->custommodel->insertBillwiseAuto($bill_data);
                }

                // For Tracking table

                if (count($dataTracking) > 0) {
                    //$dataTracking = json_decode($dataTracking);
                    $tracking_details_array = array();
                    $t = 0;
                    for ($k = 0; $k < count($dataTracking); $k++) {
                        $trackingOther = json_decode($dataTracking[$k]['value']);
                        for ($j = 0; $j < count($trackingOther); $j++) {
                            $tracking_details_array[$t]['tracking_id'] = $trackingOther[$j]->tracking_id;
                            $tracking_details_array[$t]['ledger_id'] = $dataTracking[$k]['ledgeId'];
                            $tracking_details_array[$t]['account_type'] = $trackingOther[$j]->account_type;
                            $tracking_details_array[$t]['tracking_amount'] = $trackingOther[$j]->tracking_amount;
                            $tracking_details_array[$t]['entry_id'] = $entryId;
                            $tracking_details_array[$t]['created_date'] = $created_date;
                            $tracking_details_array[$t]['deleted'] = $deleted;
                            $t++;
                        }
                    }
                    $this->custommodel->insertTracking($tracking_details_array);
                }

                // For Banking detaild
                if (count($dataBanking) > 0) {
                    // $dataBanking = json_decode($dataBanking);
                    $bank_details_array = array();
                    $b = 0;
                    for ($k = 0; $k < count($dataBanking); $k++) {

                        $bankingOther = json_decode($dataBanking[$k]['value']);

                        for ($j = 0; $j < count($bankingOther); $j++) {
                            $bank_details_array[$b]['entry_id'] = $entryId;
                            $bank_details_array[$b]['ledger_id'] = $dataBanking[$k]['ledgeId'];
                            $bank_details_array[$b]['transaction_type'] = $entry_type_id;
                            $bank_details_array[$b]['instrument_no'] = $bankingOther[$j]->instrument_no;
                            $bank_details_array[$b]['instrument_date'] = date("Y-m-d", strtotime(str_replace('/', '-', $bankingOther[$j]->instrument_date)));
                            $bank_details_array[$b]['bank_name'] = $bankingOther[$j]->bank_name;
                            $bank_details_array[$b]['branch_name'] = $bankingOther[$j]->branch_name;
                            $bank_details_array[$b]['ifsc_code'] = $bankingOther[$j]->ifsc_code;
                            $bank_details_array[$b]['bank_amount'] = $bankingOther[$j]->bank_amount;
                            $bank_details_array[$b]['create_date'] = $created_date;
                            $bank_details_array[$b]['deleted'] = $deleted;
                            $b++;
                        }
                    }
                    $this->custommodel->insertBanking($bank_details_array);
                }

                // For billwish details
                if ($tax_or_advance == 1) {
                    $bill_details_array = array();
                    $bill_details_array[0]['branch_id'] =$this->session->userdata('branch_id');
                    $bill_details_array[0]['ledger_id'] =$advance_ledger_id;
                    $bill_details_array[0]['dr_cr'] = 'Cr';
                    $bill_details_array[0]['ref_type'] = 'Advance Reference';
                    $bill_details_array[0]['bill_name'] = $entry_number;
                    $bill_details_array[0]['credit_days'] ='';
                    $bill_details_array[0]['credit_date'] = '';
                    $bill_details_array[0]['bill_amount'] = ($totalCr * $base_unit);
                    $bill_details_array[0]['entry_id'] = $entryId;
                    $bill_details_array[0]['created_date'] = $created_date;
                    $bill_details_array[0]['deleted'] = $deleted;
                    $this->custommodel->insertBillWise($bill_details_array);
                } else {
                    if (count($dataBilling) > 0) {
                        //$dataBilling = json_decode($dataBilling);
                        $bill_details_array = array();
                        $v = 0;
                        for ($k = 0; $k < count($dataBilling); $k++) {
                            $billOther = json_decode($dataBilling[$k]['value']);

                            for ($j = 0; $j < count($billOther); $j++) {
                                $bill_details_array[$v]['branch_id'] = $this->session->userdata('branch_id');
                                $bill_details_array[$v]['ledger_id'] = $dataBilling[$k]['ledgeId'];
                                $bill_details_array[$v]['dr_cr'] = $billOther[$j]->bill_acc_type;
                                $bill_details_array[$v]['ref_type'] = $billOther[$j]->reference_type;
                                $bill_details_array[$v]['bill_name'] = $billOther[$j]->bill_name;
                                $bill_details_array[$v]['credit_days'] = $billOther[$j]->bill_credit_day;
                                $bill_details_array[$v]['credit_date'] = $billOther[$j]->bill_credit_date;
                                $bill_details_array[$v]['bill_amount'] = $billOther[$j]->bill_amount;
                                $bill_details_array[$v]['entry_id'] = $entryId;
                                $bill_details_array[$v]['created_date'] = $created_date;
                                $bill_details_array[$v]['deleted'] = $deleted;
                                $v++;
                            }
                        }
                        $this->custommodel->insertBillWise($bill_details_array);
                    }
                }

                if (count($tr_service_id) > 0) {
                    $service_arr = [];
                    if($tax_or_advance==1){
                    $expences_ledger_id=$advance_ledger_id;
                    }
                    foreach ($tr_service_id as $k => $sr) {
                        $service_data = array(
                            'transaction_type' => $tax_or_advance,
                            'entry_id' => $entryId,
                            'expences_ledger_id' => $expences_ledger_id,
                            'service_product_id' => $sr,
                            'type' => $service_product[$k],
                            'service_amount' => $service_amount[$k],
                            'tax_percentage' => $tax_percentage[$k],
                            'cess_percentage' => (isset($cess_percentage[$k])) ? $cess_percentage[$k] : 0,
                            'igst_status' => (isset($igst_status)) ? $igst_status : 0,
                            'cess_status' => (isset($cess_status)) ? $cess_status : 0,
                            'export_status' => (isset($export_status)) ? $export_status : 0,
                            'status' => 1
                        );
                        $service_arr[] = $service_data;
                    }

                    $this->custommodel->insertService($service_arr);
                }

                //recurring
                if (isset($recurring_freq) && ($recurring_freq == 'Daily' || $recurring_freq == 'Weekly' || $recurring_freq == 'Monthly' || $recurring_freq == 'Yearly')) {
                    $this->saveRecurring($recurring_freq, $created_date, $entryId);
                }
                //end recurring
                $redirect_url = base_url('admin/transaction-list') . "/" .  str_replace(' ','-',strtolower(trim($entry_type['type']))).'/'.$entry_type['id'].'/all';
              
                //print url
                $print_url = base_url('admin/trasaction-details') . '.aspx/' . $entryId;
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'save_err';
                    $data_msg['message'] = "There was an error submitting the form";
                } else {
                    $this->db->trans_commit();

                    if ($entry_type_id == 1) {
                        $pref = $this->custommodel->getPreferences();
                        if($pref->receipt_mail == 1) {
                            $this->load->helper('actmail');
                            $this->load->model('transaction_inventory/inventory/inventorymodel');
                            $company_details = $this->inventorymodel->getCompanyDetails();
                            $voucher = ($entry_type_id == 1 || $parent_id==1) ? 'Receipt' : 'Payment';
                            $message = "Your " . $voucher . " transaction added successfully. " . $voucher . " number is " . $entry_number;
                            $company_mail_data = array($company_details->company_name, $message);


                            $attach = $this->voucherForMail($entryId); // invoice attachment
                            // $attach = '';
                            $company_name_for_mail = $company_details->company_name;
                            // sendActMail($template = 'receipt_template', $slug = 'receipt', $to = $company_details->email, $company_mail_data, $attach, $company_name_for_mail);
                            $ledger_contact_details = $this->inventorymodel->getLedgerContactDetails($ledger_id[0]);
                            if ($ledger_contact_details) {
                                //mail   
                                $ledger_mail_data = array($ledger_contact_details->company_name, $message);
                                sendActMail($template = 'receipt_template', $slug = 'receipt', $to = $ledger_contact_details->email, $ledger_mail_data, $attach, $company_name_for_mail);
                                //end mail 
                            }
                        }
                    }


                    $data_msg['res'] = 'success';
                    $data_msg['redirect_url'] = $redirect_url;
                    $data_msg['print_url'] = $print_url;
                    $data_msg['message'] = "Transaction added successfully. Entry number #" . $entry_number;
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = $this->form_validation->error_array();
            }
        }




        echo json_encode($data_msg);
    }

    //recurring

    public function saveRecurring($recurring_freq, $created_date, $entryId) {
        $data = array(
            'entry_id' => $entryId,
            'recurring_date' => $created_date,
            'frequency' => $recurring_freq,
            'status' => 1,
            'create_date' => date("Y-m-d H:i:s")
        );
        $this->custommodel->saveRecurringData($data);
    }

    public function voucherForMail($entry_id)
    {
        $this->load->model('reports/admin/reportsmodel');
        $data = array();
        // $this->load->helper('traialbalance');
        $this->load->helper('numtoword');
        // $result = get_transaction_details(8);
        $result = $this->custommodel->getEntryDetailsForVoucherMail($entry_id);
        $entry_type_id=$result['entry'][0]['entry_type_id'];
        $data['voucher'] = $this->reportsmodel->getVoucherType($entry_type_id);
        $data['entry_id'] = $result['entry_id'];
        $data['types'] = $result['types'];
        $data['ledger'] = $result['ledger'];
        $data['entry'] = $result['entry'];
        $entry_details_result = $result['entry_details'];
        $data['currencies'] = $result['currencies'];
        $data['amountInWord'] = numberTowords($data['entry'][0]['unit_price_dr']);
        $against_ref = array();
        for ($i = 0; $i < count($entry_details_result); $i++) {
            $entry_details[$i]['selected_currency'] = $entry_details_result[$i]['selected_currency'];
            $entry_details[$i]['unit_price'] = $entry_details_result[$i]['unit_price'];
            $entry_details[$i]['balance'] = $entry_details_result[$i]['balance'];
            $entry_details[$i]['ladger_id'] = $entry_details_result[$i]['ladger_id'];
            $entry_details[$i]['account'] = $entry_details_result[$i]['account'];
            $entry_details[$i]['narration'] = $entry_details_result[$i]['narration'];
            $entry_details[$i]['current_balance'] = $entry_details_result[$i]['current_balance'];
            $entry_details[$i]['ladger_name'] = $entry_details_result[$i]['ladger_name'];
            $entry_details[$i]['against_ref'] = $this->reportsmodel->get_bill_by_entry_details($result['entry'][0]['id'], $entry_details_result[$i]['ladger_id'], $entry_details_result[$i]['account']);
            $entry_details[$i]['bank_details'] = $this->reportsmodel->get_bank_details($result['entry'][0]['id'], $entry_details_result[$i]['ladger_id']);
        }
        $data['entry_details'] = $entry_details;

        $data['company_details'] = $this->reportsmodel->getCompanyDetails();
        // $getsitename = getsitename();
        // $this->layouts->set_title($getsitename . ' | Transaction Details');
        $this->load->model('reports/admin/reportsmodel');
        $data['get_standard_format_data'] = $this->reportsmodel->getStandardFormatData();

        $html = $this->load->view('transaction/admin/voucher_for_mail', $data, TRUE);

        $htmlContent = '<!DOCTYPE html>
                      <html>
                      <head>
                          <title></title>
                      </head>
                      <body>'
                      .$html.
                      '</body>
                      </html>';
        $this->load->library('dompdf1');
        // ob_start();
        // $htmlContent = ob_get_clean();
        // $htmlContent = $html;
        $pdf_name = 'Voucher'.time();

        // create directory is not exist otherwise give the permission
        if (!file_exists(FCPATH . "assets/pdf_for_mail_uploads")) {
           mkdir(FCPATH . "assets/pdf_for_mail_uploads", 0777, true);
        } else {
           chmod(FCPATH . "assets/pdf_for_mail_uploads", 0777);
        }

        $fileName = $pdf_name . '.pdf';
        // chmod(FCPATH . "assets/pdf_for_mail_uploads/".$fileName, 0777);
        $this->dompdf1->generatePdf($htmlContent, $fileName);
        return $fileName;


        // $this->layouts->render('admin/trasaction_details', $data, 'admin');
    }

    //entry_number=4535&date_form=&tr_ledger%5B%5D=Bank&tr_ledger_id%5B%5D=3&tr_type%5B%5D=Dr&tr_dr_amount%5B%5D=500&tr_ledger%5B%5D=Sundry+Creditors&tr_ledger_id%5B%5D=17&tr_type%5B%5D=Cr&tr_cr_amount%5B%5D=500&tr_narration=
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
