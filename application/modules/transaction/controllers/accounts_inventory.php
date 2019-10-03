<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class accounts_inventory extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->model('admin/trackingmodel');
        $this->load->model('accounts/group');
        $this->load->model('accounts/account');
        $this->load->model('admin/custommodel');
        $this->load->model('accounts/entry');
        $this->load->model('admin/custominventorymodel');
        $this->load->model('transaction_inventory/inventory/inventorymodel');
        $this->load->model('admin/financialyearmodel');
        $this->load->helper('financialyear');
        $this->load->helper('custom');
        $this->load->library('session');
        
    }

    public function index($id = NULL) {


        $this->load->helper('text');
        $data = array();
        $data['ledger'] = array();
        $ledger_id = NULL;

        if ($id == NULL) {
            $id = 5;
        }


        if ($ledger_id != 0) {
            $where = array(
                'ladger.id' => $ledger_id,
                'ladger.status' => 1,
                'ladger.deleted' => 0
            );
            $data['ledger'] = $this->account->getLedgerDetailsById($where);
        }



        $entry_type = $this->entry->getEntryTypeById($id);
        $data['voucher'] = $entry_type['type'];
        $data['transaction_type_id'] = $id;


        $entry_type_id = $id;
        $entry_type = $this->entry->getEntryTypeById($entry_type_id);
        $data['auto_no_status'] = $entry_type['transaction_no_status'];

        $date_type = $this->custommodel->checkAutoDate();
        $data['auto_date'] = $date_type['skip_date_create'];
        $ledger_details = array();



        $ledger_code_status = $this->account->getLedgerCodeStatus();
        $data['ledger_code_status'] = $ledger_code_status['ledger_code_status'];

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Transaction');
        $this->layouts->render('admin/inventory_form', $data, 'admin');
    }

    public function getLedgerDebtors() {

        $ledger = $_POST['ledger'];
        $ledger_details = array();
        $array_ledger = [12, 25];

        $ledger_details = $this->custominventorymodel->getLedger($ledger, $array_ledger);

        $ledgerName = '';

        $ledgerName = '[';

        foreach ($ledger_details as $value) {
            $ledgerName .= " { \"label\": \"$value->ladger_name\", \"value\": \"$value->id\"},";
        }

        $ledgerName = substr($ledgerName, 0, -1);
        $ledgerName .= ' ]';

        echo $ledgerName;
    }

    public function getLedgerSales() {

        $ledger = $_POST['ledger'];
        $ledger_details = array();
        $array_group = [37];

        $ledger_details = $this->custommodel->getLedger($array_group);

        for ($i = 0; $i < count($array_group); $i++) {
            array_push($ledger_details, $array_group[$i]);
        }



        $ledger_final = $this->custommodel->getLedgerFinalIn($ledger, $ledger_details);

        $ledgerName = '';

        $ledgerName = '[';

        foreach ($ledger_final as $value) {
            $ledgerName .= " { \"label\": \"$value->ladger_name\", \"value\": \"$value->id\"},";
        }

        $ledgerName = substr($ledgerName, 0, -1);
        $ledgerName .= ' ]';

        echo $ledgerName;
    }

    public function getLedgerRounding() {

        $ledger = $_POST['ledger'];
        $ledger_details = array();
        $array_group = [30, 35];

        $ledger_details = $this->custommodel->getLedger($array_group);

        for ($i = 0; $i < count($array_group); $i++) {
            array_push($ledger_details, $array_group[$i]);
        }



        $ledger_final = $this->custommodel->getLedgerFinalIn($ledger, $ledger_details);

        $ledgerName = '';

        $ledgerName = '[';

        foreach ($ledger_final as $value) {
            $ledgerName .= " { \"label\": \"$value->ladger_name\", \"value\": \"$value->id\"},";
        }

        $ledgerName = substr($ledgerName, 0, -1);
        $ledgerName .= ' ]';

        echo $ledgerName;
    }

    public function getShippingDetails() {

        $ledgerId = $_POST['ledger'];
        $arr = array();

        $transactionType = $this->custommodel->getTType($ledgerId);

        $shippingDetails = $this->custominventorymodel->getShippingDet($ledgerId);

        $ledgerLimitDetails = $this->custominventorymodel->ledgerLimitDetails($ledgerId);
        $shippingAddress = $this->custominventorymodel->getShippingAddress($ledgerId);


        $getCountryName = $this->custominventorymodel->getCountryName($shippingAddress->country)->name;
        $getStateName = $this->custominventorymodel->getStateName($shippingAddress->state)->name;

        $billingAddress = $this->custominventorymodel->getBillingAddress($ledgerId);


        if (!empty($billingAddress->country)) {
            $getCountryNameBilling = $this->custominventorymodel->getCountryName($billingAddress->country)->name;
        } else {
            $getCountryNameBilling = "";
        }

        $getStateNameBilling = $this->custominventorymodel->getStateName($billingAddress->state_id)->name;



        $countryId = $shippingDetails->country;
        $stateId = $shippingDetails->state;

        $arr['country'] = $countryId;
        $arr['state'] = $stateId;

        /* For Ledger Limits */

        $arr['LL_creditLimit'] = $ledgerLimitDetails->credit_limit;
        $arr['LL_creditDays'] = $ledgerLimitDetails->credit_date;

        /* For Shipping Addr */

        $arr['Sh_companyName'] = $shippingAddress->company_name;
        $arr['Sh_address'] = $shippingAddress->address;
        $arr['Sh_city'] = $shippingAddress->city;
        $arr['Sh_zip'] = $shippingAddress->zip;
        $arr['Sh_state'] = $getStateName;
        $arr['Sh_country'] = $getCountryName;
        $arr['Sh_tax'] = $shippingAddress->sales_tax_no;

        /* For Billing Addr */


        $arr['Bi_companyName'] = $billingAddress->company_name;
        $arr['Bi_address'] = $billingAddress->street_address;
        $arr['Bi_city'] = $billingAddress->city_name;
        $arr['Bi_zip'] = $billingAddress->zip_code;
        $arr['Bi_state'] = $getStateNameBilling;
        $arr['Bi_country'] = $getCountryNameBilling;
        $arr['Bi_tax'] = $billingAddress->service_tax;

        $arr['transactionType'] = $transactionType->account_type;

        echo json_encode($arr);
    }

    public function getProducts() {

        $productSearch = $_POST['term'];
        $type_service_product = isset($_POST['type_service_product']) ? $_POST['type_service_product'] : '';
        $productsArr = $this->custominventorymodel->getProducts($productSearch, $type_service_product);

        $productName = '';

        $productName = '[';

        foreach ($productsArr as $value) {
            if($value->type){
                $productName .= " { \"label\": \"$value->name\", \"value\": \"$value->id\"},";
            }else{
                $quantity=$this->getProductQuantity($value->id);
                if($value->stockdet){
                    $productName .= " { \"label\": \"$value->name: $value->stockdet [Stock: $quantity]\", \"value\": \"$value->id\"},";
                }else{
                    $productName .= " { \"label\": \"$value->name ($value->sku) [Stock: $quantity]\", \"value\": \"$value->id\"},";
                }
            }
            
            
        }

        $productName = substr($productName, 0, -1);
        $productName .= ' ]';

        echo $productName;
    }
    
    function getProductQuantity($id){
     $total=0;
     $product = $this->custominventorymodel->getOpeningQuantityNew($id);  
     $stockin = $this->custominventorymodel->getTotalStockInNew($id);
     $stockout = $this->custominventorymodel->getTotalStockOutNew($id);
     if($product){
      $total+=$product->opening_qty;   
     }if($stockin){
      $total+= $stockin->stockin;  
     }if($stockout){
     $total=($total-$stockout->stockout);    
     }
     return $total;
    }

    public function getProductDetails() {

        $sId = $_POST['sId'];
        $shippingCountry = $_POST['shippingcountry'];
        $shippingState = $_POST['shippingstate'];
        $type_service_product = $_POST['type_service_product'];
        
        $pId = $this->custominventorymodel->getProductIdByStockId($sId)->pid;

        $billingAddress = $this->custominventorymodel->getBillingAddress();
        $getStateNameBilling = $this->custominventorymodel->getStateName($billingAddress->state_id)->state;
        // $countryId = $this->custominventorymodel->getStateName($billingAddress->state_id)->country_id;

        $getCountryNameBilling = $this->custominventorymodel->getStateName($billingAddress->state_id)->country;

        // echo $getStateNameBilling; exit;

        $jsonArr = array();

        /* Find tax class */
        $productTax = $this->custominventorymodel->getProductTax($pId)->tax_class;
        
        /* product type */
        $type = $this->custominventorymodel->getProductType($pId)->type;

        /* getProdDetails */
        $jsonArr['pId'] = $pId;
        $jsonArr['productPrice'] = $this->custominventorymodel->getProductPrice($sId)->price;
        $jsonArr['productUnitName'] = (!$type) ? $this->custominventorymodel->getProductUnit($sId)->name : 'No';
        $jsonArr['productUnitId'] = (!$type) ? $this->custominventorymodel->getProductUnit($sId)->id : 'No';
        // $jsonArr['productTax'] = $this->custominventorymodel->getProductTaxPercent($productTax, $shippingCountry, $shippingState)->tax_rate;
        $jsonArr['productTax'] = $this->custominventorymodel->getProductTaxPercentGST($pId)->tax_amount;
        $jsonArr['cess_present'] = $this->custominventorymodel->getProductTaxPercentGST($pId)->cess_present;
        $jsonArr['cess_value'] = $this->custominventorymodel->getProductTaxPercentGST($pId)->cess_value;
        $jsonArr['billingState'] = $getStateNameBilling;
        $jsonArr['billingStateId'] = $billingAddress->state_id;
        $jsonArr['billingCountry'] = $getCountryNameBilling;
        $jsonArr['billingCountryId'] = $billingAddress->country_id;
        $jsonArr['productBatchStatus'] = $this->custominventorymodel->getProductBatchByStockId($sId)->having_batch;
        $jsonArr['shortDescription'] = $this->custominventorymodel->getProductShortDescription($pId)->short_description;
        $jsonArr['productSalesPrice'] = ($this->custominventorymodel->getProductSalesProceBypsId($sId))?$this->custominventorymodel->getProductSalesProceBypsId($sId)->sales_price:0.00;
        
        echo json_encode($jsonArr);
    }

    public function getProductDetailsBOM() {

        $pId = $_POST['pId'];

        $jsonArr = array();

        /* getProdDetails */
        $jsonArr['stockId'] = $this->custominventorymodel->getProductPrice($pId)->id;
        $jsonArr['productPrice'] = $this->custominventorymodel->getProductPrice($pId)->price;
        $jsonArr['productUnitName'] = $this->custominventorymodel->getProductUnit($pId)->name;
        $jsonArr['productUnitId'] = $this->custominventorymodel->getProductUnit($pId)->id;
        $jsonArr['productTax'] = '';

        echo json_encode($jsonArr);
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
                if (0 < $month && $month <= $m) {
                    $year = date("Y") + 1;
                } else {
                    $year = date("Y");
                }
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

    /* Form submit */

    public function ajax_save_form_data() {
        $data_msg = [];
        // echo json_encode($_POST);exit();
        if ($this->input->is_ajax_request()) {
            echo "<pre>";
            print_r($_POST);
            die();
            $advance_bill_name = $this->input->post('advance_bill_name');
            $reverse_entry = $this->input->post('reverse_entry');
            $type_service_product = $this->input->post('type_service_product');
            $recurring_freq = $this->input->post('recurring_freq');
            $postdated = $this->input->post('postdated');
            $selectedCurrency = $this->input->post('currency');
            $user_id = $this->session->userdata('admin_uid');
            $branch_id = $this->session->userdata('branch_id');
            $entry_number = $this->input->post('entry_number');
            $date_form = $this->input->post('date_form');
            $account_type = $this->input->post('tr_type');
            $credit_days = $this->input->post('credit_days');
            $product_grand_total = $this->input->post('product_grand_total');
            $ledger_name = $this->input->post('tr_ledger');
            $netTotal = $this->input->post('netTotal');
            $discount_value_hidden = $this->input->post('discount_value_hidden');
            $discount_percent_hidden = $this->input->post('discount_percent_hidden');
            $narration = $this->input->post('notes');

            $tr_ledger_id = $this->input->post('tr_ledger_id');
            $dataTracking = isset($_POST['tracking']) ? $_POST['tracking'] : null;

            //For Product 
            $product_id = $this->input->post('product_id');
            $stock_id = $this->input->post('stock_id');
            $product_name = $this->input->post('product_name');
            $product_description = $this->input->post('product_description');//19042018
            $product_quantity = $this->input->post('product_quantity');
            $unit = $this->input->post('product_unit_hidden_id');//27112018
            $complex_unit = $this->input->post('product_complex_unit_hidden_id');//27112018
            $product_price = $this->input->post('product_price');
            $tax_percent = $this->input->post('tax_percent');
            $tax_value = $this->input->post('tax_value');
            $product_discount = $this->input->post('product_discount');//16072018
            $total_price_per_prod = $this->input->post('total_price_per_prod');
            $gross_total_price_per_prod = $this->input->post('gross_total_price_per_prod');
            $total_price_per_prod_with_tax = $this->input->post('total_price_per_prod_with_tax');
            $entry_id = $this->input->post('entry_id');
            
            //GST
            $igst_tax_percent = $this->input->post('igst_tax_percent');
            $igst_tax_value = $this->input->post('igst_tax_value');

            $sgst_tax_percent = $this->input->post('sgst_tax_percent');
            $sgst_tax_value = $this->input->post('sgst_tax_value');

            $cgst_tax_percent = $this->input->post('cgst_tax_percent');
            $cgst_tax_value = $this->input->post('cgst_tax_value');

            $cess_status_col = $this->input->post('cess_status_col');
            $igst_status = $this->input->post('igst_status');

            $cess_tax_percent = $this->input->post('cess_percent');
            $cess_tax_value = $this->input->post('cess_value');
            
            //Address
            //Shiping Address ID
            $shipping_id = $this->input->post('shipping_id');
            //bank details add in invoice
            $bank_id = $this->input->post('bank_id');
            //despatch detaild in invoice
            $despatch_through = $this->input->post('despatch_through');
            $motor_vehicle_no = $this->input->post('motor_vehicle_no');
            
            //godown and batch =============
            $batchGodown = isset($_POST['batchGodown']) ? $_POST['batchGodown'] : null;
            $godown_status = $this->input->post('godown_status');
            $batch_status = $this->input->post('batch_status');
            



            $count = count($tr_ledger_id);

            $entry_type_id = $this->input->post('entry_type');
            $entry_type = $this->entry->getEntryTypeById($entry_type_id);
            $parent_id = $this->input->post('parent_id');
            $created_date = date("Y-m-d", strtotime(str_replace('/', '-', $date_form)));
            $voucher_no = $this->input->post('voucher_no');
            $voucher_date = $this->input->post('voucher_date');
            $voucher_date = date("Y-m-d", strtotime(str_replace('/', '-', $voucher_date)));
             $tr_branch_id = $this->input->post('tr_branch_id');
            $branch_entry_no = $this->input->post('branch_entry_no');

            $deleted = ($postdated == 1) ? '2' : '0';
            $order_deleted = ($postdated == 1) ? '2' : '1';
            if ($entry_type_id == 6) {
                $this->form_validation->set_rules('tr_ledger[0]', 'Creditors', 'required');
                $this->form_validation->set_rules('tr_ledger[1]', 'Purchase', 'required');
            } else if($entry_type_id == 5) {
                $this->form_validation->set_rules('tr_ledger[0]', 'Debtors', 'required');
                $this->form_validation->set_rules('tr_ledger[1]', 'Sales', 'required');
            }
            
            
            if ($entry_number != 'Auto' || $entry_number != null || $entry_number != '') {
                $this->form_validation->set_rules('entry_number', 'Entry Number', 'required|callback_alpha_dash_space');
            }

            if ($postdated == 1) {
                $this->form_validation->set_rules('date_form', 'Transaction date', 'required|check_postdated_date');
            } else {
                $this->form_validation->set_rules('date_form', 'Transaction date', 'required');
            }
            if ($this->form_validation->run() === TRUE) {

                //generate auto entry no
                if ($entry_number == 'Auto' || $entry_number == null || $entry_number == '') {
                    //$entry_type_id= $this->input->post('entry_type_id');

                    
                    $countid = 1;
                    $today = date("Y-m-d H:i:s");
                    $auto_number = $this->entry->getNoOfByTypeId($entry_type_id,$parent_id, $today, $entry_type['strating_date']);
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


                $order = array();
                $entry = array();
                $new_array = array();
                $bill_data = array();
                //calculate tax
                $igst_value = 0;
                $cgst_value = 0;
                $sgst_value = 0;
                if ($igst_status == 1) {
                    foreach ($igst_tax_value as $igst) {
                        $igst_value+=$igst;
                    }
                } else {
                    foreach ($cgst_tax_value as $cgst) {
                        $cgst_value+=$cgst;
                    }
                    foreach ($sgst_tax_value as $sgst) {
                        $sgst_value+=$sgst;
                    }
                }
                $cess_status = 0;
                $cess_value = 0;
                foreach ($cess_status_col as $k => $cess) {
                    if ($cess == 1) {
                        $cess_status = 1;
                        $cess_value+=$cess_tax_value[$k];
                    }
                }

                if ($entry_type_id == 5 || $parent_id==5) {
                    $gst_type = 'Cr';
                    if ($igst_status == 1) {
                        $igst_ledger_id = 31;
                    } else {
                        $cgst_ledger_id = 33;
                        $sgst_ledger_id = 35;
                    }
                    $cess_type = 'Cr';
                    $cess_ledger_id = 37;
            } else if ($entry_type_id == 6 || $parent_id==6) {
                    $gst_type = 'Dr';
                    if ($igst_status == 1) {
                        $igst_ledger_id = 32;
                    } else {
                        $cgst_ledger_id = 34;
                        $sgst_ledger_id = 36;
                    }
                    $cess_type = 'Dr';
                    $cess_ledger_id = 38;
                }
                //end calculate GST
                $sub_voucher = 0;
                $is_inventry = 1;
                $order_id = "";
                $discount_sum = 0;
                $product_grand_total = $netTotal; //16/02/2018
                if ($reverse_entry == 1 && ($entry_type_id == 6 || $parent_id==6)) {
                    $debtors_amount = $product_grand_total - $tax_value;
                } else {
                    $debtors_amount = $product_grand_total;
                }
                for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
                    $discount_sum+=$discount_value_hidden[$i];
                }
                if ($entry_type_id == 5 || $parent_id==5) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                } else if ($entry_type_id == 6 || $parent_id==6) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                }
                
                //IF $isDataNewRef == 1 : credit ELSE $isDataNewRef == 0 : cash or bank END IF
                $isDataNewRef = 1;

                // $bill_data = array();

                $baseCurrency = $this->entry->getDefoultCurrency();
                if ($baseCurrency) {
                    $currency_unit = $this->entry->getCurrencyUnitById($baseCurrency['base_currency']);
                }


                $base_unit = $currency_unit['unit_price'];
                $selected_currency = $currency_unit['id'];

//                for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
//                    if ($account_type[$i] == 'Cr') {
//                        if ($entry_type_id == 5) {
//                            $ledger_value += $discount_value_hidden[$i];
//                        } else if ($entry_type_id == 6) {
//                            $ledger_value -= $discount_value_hidden[$i];
//                        }
//                    }
//                    if (!empty($discount_value_hidden[$i])) {
//                        if ($account_type[$i] == 'Dr') {
//                            if ($entry_type_id == 5) {
//                                $debtors_amount -= $discount_value_hidden[$i];
//                            } else if ($entry_type_id == 6) {
//                                $debtors_amount += $discount_value_hidden[$i];
//                            }
//                        }
//                        if ($account_type[$i] == 'Cr') {
//                            if ($entry_type_id == 5) {
//                                $debtors_amount += $discount_value_hidden[$i];
//                            } else if ($entry_type_id == 6) {
//                                $debtors_amount -= $discount_value_hidden[$i];
//                            }
//                        }
//                    }
//                }
                
                //using for case sales and bank sales
                $cash_bank_group = [10,11];
                $all_sub_group = $this->inventorymodel->getAllSubGroup($cash_bank_group);
                for ($i = 0; $i < count($cash_bank_group); $i++) {
                    array_push($all_sub_group, $cash_bank_group[$i]);
                }
                $ledgerIdArr = $this->inventorymodel->getLedgerByGroupsId($all_sub_group);

                if(in_array($tr_ledger_id[0],array_column($ledgerIdArr,'id'))){
                    $isDataNewRef = 0; 
                }
                
                if ($isDataNewRef == 1) {
                    // $newReferenceLedgerArray = $_POST['newReferenceLedgerArray'];
                    // for ($i = 0; $i < count($newReferenceLedgerArray); $i++) {
                    $ledgeId = $tr_ledger_id[0];

                    $hasBilling = $this->custommodel->getTBilling($ledgeId);
                    $hasBillingFinal = $hasBilling->bill_details_status;

//                    if ($hasBillingFinal == "1") {

                        /* Fetch data */
                        $ledgeDet = $this->custommodel->getNewRefLedgerDetails($ledgeId);

                        $credit_date_get = $credit_days;
                        $credit_limit_get = $ledgeDet->credit_limit;




                        // if ($entry_type_id == 5) {
                        $getDiffDrCrBilling = $this->custommodel->getDiffDrCrBillingSales($ledgeId);
                        // } else if ($entry_type_id == 6) {
                        //     $getDiffDrCrBilling = $this->custommodel->getDiffDrCrBillingPurchase($ledgeId);
                        // }



                        $diff = $getDiffDrCrBilling->diff;

                        // if ($newReferenceLedgerArray[$i]["drAmt"] > 0) {
                        $total = $debtors_amount + $diff;
                        $amount = $debtors_amount;
                        // } else if ($newReferenceLedgerArray[$i]["crAmt"] > 0) {
                        //     $total = $newReferenceLedgerArray[$i]["crAmt"] + $diff;
                        //     $amount = $newReferenceLedgerArray[$i]["crAmt"];
                        // }
                        
                        
                        
                        if ($entry_type_id == 5 || $parent_id==5) {
                            if ($total <= $credit_limit_get) {
                                $bill_data[0]['branch_id'] = $branch_id;
                                $bill_data[0]['ledger_id'] = $tr_ledger_id[0];
                                $bill_data[0]['dr_cr'] = $account_type[0];
                                $bill_data[0]['ref_type'] = 'New Reference';
                                $bill_data[0]['bill_name'] = (isset($advance_bill_name) && $advance_bill_name != '') ? $advance_bill_name : $entry_number;
                                $bill_data[0]['credit_days'] = $credit_date_get;
                                $bill_credit_date = strtotime("+".$credit_date_get." days", strtotime($created_date));
                                $bill_data[0]['credit_date'] = date('Y-m-d', $bill_credit_date);
                                // $bill_data[0]['credit_date'] = date('Y-m-d', strtotime("+" . $credit_date_get . " days"));
                                $bill_data[0]['bill_amount'] = ($amount * $base_unit);
                                $bill_data[0]['entry_id'] = "";
                                $bill_data[0]['created_date'] = $created_date;
                            } else {

                                $data_msg['res'] = 'save_err';
                                $data_msg['message'] = "Your credit limit has exceeded!";
                                echo json_encode($data_msg);
                                exit;
                            }
                        } else if ($entry_type_id == 6 || $parent_id==6) {
                            $bill_data[0]['branch_id'] = $branch_id;
                            $bill_data[0]['ledger_id'] = $tr_ledger_id[0];
                            $bill_data[0]['dr_cr'] = $account_type[0];
                            $bill_data[0]['ref_type'] = 'New Reference';
                            $bill_data[0]['bill_name'] = $entry_number;
                            $bill_data[0]['credit_days'] = $credit_date_get;
                            $bill_credit_date = strtotime("+".$credit_date_get." days", strtotime($created_date));
                            $bill_data[0]['credit_date'] = date('Y-m-d', $bill_credit_date);
                            // $bill_data[0]['credit_date'] = date('Y-m-d', strtotime("+" . $credit_date_get . " days"));
                            $bill_data[0]['bill_amount'] = ($amount * $base_unit);
                            $bill_data[0]['entry_id'] = "";
                            $bill_data[0]['created_date'] = $created_date;
                        }
                        
//                    }
                    // }
                }

                for ($i = 0; $i < (count($ledger_name) - 1); $i++) {

                    $new_array[$account_type[$i]][] = $ledger_name[$i];
                }

                $ledger_name_json = json_encode($new_array);

                if ($reverse_entry == 1 && ($entry_type_id == 6 || $parent_id==6)) {
                    $sum_dr = $debtors_amount;
                    $sum_cr = $sales_amount - $discount_sum;
                } else {
                    $sum_dr = $debtors_amount + $discount_sum;
                    $sum_cr = $sales_amount + $tax_value;
                }

                // For Entry Table
                $entry = array(
                    'entry_no' => $entry_number,
                    'create_date' => $created_date,
                    'ledger_ids_by_accounts' => $ledger_name_json,
                    'dr_amount' => ($entry_type_id == 5 || $parent_id==5) ? ($sum_dr * $base_unit) : ($sum_dr * $base_unit),
                    'cr_amount' => ($entry_type_id == 5 || $parent_id==5) ? ($sum_cr * $base_unit) : ($sum_cr * $base_unit),
                    'unit_price_dr' => ($entry_type_id == 5 || $parent_id==5) ? $sum_dr : $sum_dr,
                    'unit_price_cr' => ($entry_type_id == 5 || $parent_id==5) ? $sum_cr : $sum_cr,
                    'entry_type_id' => ($entry_type['parent_id']==0)?$entry_type_id:$entry_type['parent_id'],
                    'sub_voucher' => ($entry_type['parent_id']!=0)?$entry_type_id:$entry_type['parent_id'],
                    'user_id' => $user_id,
                    'company_id' => $branch_id,
                    'is_inventry' => $is_inventry,
                    'narration' => $narration,
                    'order_id' => $order_id,
                    'voucher_no' => $voucher_no,
                    'voucher_date' => $voucher_date,
                    'is_reverse_entry' => ($reverse_entry == 1 && ($entry_type_id == 6 || $parent_id==6)) ? 1 : 0,
                    'is_service_product' => ($type_service_product == 1) ? 1 : 0,
                    'is_advance_sell' => (isset($advance_bill_name) && $advance_bill_name != '') ? 1 : 0,
                    'bank_id' => (isset($bank_id) && $bank_id != '') ? $bank_id : 0,
                    'deleted' => $deleted
                );

                $this->db->trans_begin();
                $entryId = $this->custommodel->setEntry($entry);

                if($tr_branch_id){
                    if($branch_entry_no){
                    $this->custommodel->updateBranchTransaction($branch_entry_no);     
                    }else{
                 $branch=$this->custommodel->getBranchName();
                 $ledger_name = $this->inventorymodel->getLedgerName($tr_ledger_id[0]);
                 $notification_msg="You have a new ".$entry_type['type']." (".$entry_number.") transaction of Rs. ".$sum_dr." from ".$ledger_name.".";
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
                // For Ladger Account Details Table
                $ledgerDetails = array();
                $balance = 0;

                $index = 0;

                // debtors
                $ledgerDetails[0]['branch_id'] = $branch_id;
                $ledgerDetails[0]['account'] = $account_type[0];
                $ledgerDetails[0]['balance'] = ($debtors_amount * $base_unit);
                $ledgerDetails[0]['entry_id'] = $entryId;
                $ledgerDetails[0]['ladger_id'] = $tr_ledger_id[0];
                $ledgerDetails[0]['create_date'] = $created_date;
                $ledgerDetails[0]['narration'] = $narration;
                $ledgerDetails[0]['selected_currency'] = $selected_currency;
                $ledgerDetails[0]['unit_price'] = $base_unit;
                $ledgerDetails[0]['discount_type'] = 0;
                $ledgerDetails[0]['discount_amount'] = '';
                $ledgerDetails[0]['deleted'] = $deleted;
                // sales
                $ledgerDetails[1]['branch_id'] = $branch_id;
                $ledgerDetails[1]['account'] = $account_type[1];
                $ledgerDetails[1]['balance'] = ($sales_amount * $base_unit);
                $ledgerDetails[1]['entry_id'] = $entryId;
                $ledgerDetails[1]['ladger_id'] = $tr_ledger_id[1];
                $ledgerDetails[1]['create_date'] = $created_date;
                $ledgerDetails[1]['narration'] = $narration;
                $ledgerDetails[1]['selected_currency'] = $selected_currency;
                $ledgerDetails[1]['unit_price'] = $base_unit;
                $ledgerDetails[1]['discount_type'] = 0;
                $ledgerDetails[1]['discount_amount'] = '';
                $ledgerDetails[1]['deleted'] = $deleted;

                // GST tax
                if ($igst_status == 1) {
                    //IGST  
                    $ledgerDetails[2]['branch_id'] = $branch_id;
                    $ledgerDetails[2]['account'] = $gst_type;
                    $ledgerDetails[2]['balance'] = ($reverse_entry == 1 && ($entry_type_id == 6 || $parent_id==6)) ? 0 : ($igst_value * $base_unit);
                    $ledgerDetails[2]['entry_id'] = $entryId;
                    $ledgerDetails[2]['ladger_id'] = $igst_ledger_id;
                    $ledgerDetails[2]['create_date'] = $created_date;
                    $ledgerDetails[2]['narration'] = $narration;
                    $ledgerDetails[2]['selected_currency'] = $selected_currency;
                    $ledgerDetails[2]['unit_price'] = $base_unit;
                    $ledgerDetails[2]['discount_type'] = 0;
                    $ledgerDetails[2]['discount_amount'] = '';
                    $ledgerDetails[2]['deleted'] = $deleted;
                } else {
                    //CGST   
                    $ledgerDetails[2]['branch_id'] = $branch_id;
                    $ledgerDetails[2]['account'] = $gst_type;
                    $ledgerDetails[2]['balance'] = ($reverse_entry == 1 && ($entry_type_id == 6 || $parent_id==6)) ? 0 : ($cgst_value * $base_unit);
                    $ledgerDetails[2]['entry_id'] = $entryId;
                    $ledgerDetails[2]['ladger_id'] = $cgst_ledger_id;
                    $ledgerDetails[2]['create_date'] = $created_date;
                    $ledgerDetails[2]['narration'] = $narration;
                    $ledgerDetails[2]['selected_currency'] = $selected_currency;
                    $ledgerDetails[2]['unit_price'] = $base_unit;
                    $ledgerDetails[2]['discount_type'] = 0;
                    $ledgerDetails[2]['discount_amount'] = '';
                    $ledgerDetails[2]['deleted'] = $deleted;
                    //SGST
                    $ledgerDetails[3]['branch_id'] = $branch_id;
                    $ledgerDetails[3]['account'] = $gst_type;
                    $ledgerDetails[3]['balance'] = ($reverse_entry == 1 && ($entry_type_id == 6 || $parent_id==6)) ? 0 : ($sgst_value * $base_unit);
                    $ledgerDetails[3]['entry_id'] = $entryId;
                    $ledgerDetails[3]['ladger_id'] = $sgst_ledger_id;
                    $ledgerDetails[3]['create_date'] = $created_date;
                    $ledgerDetails[3]['narration'] = $narration;
                    $ledgerDetails[3]['selected_currency'] = $selected_currency;
                    $ledgerDetails[3]['unit_price'] = $base_unit;
                    $ledgerDetails[3]['discount_type'] = 0;
                    $ledgerDetails[3]['discount_amount'] = '';
                    $ledgerDetails[3]['deleted'] = $deleted;
                }
                //CESS Tax
                if ($igst_status == 1) {
                    $index = 2;
                } else {
                    $index = 3;
                }
                if ($cess_status == 1) {
                    $index = $index + 1;
                    $ledgerDetails[$index]['branch_id'] = $branch_id;
                    $ledgerDetails[$index]['account'] = $gst_type;
                    $ledgerDetails[$index]['balance'] = ($reverse_entry == 1 && ($entry_type_id == 6 || $parent_id==6)) ? 0 : ($cess_value * $base_unit);
                    $ledgerDetails[$index]['entry_id'] = $entryId;
                    $ledgerDetails[$index]['ladger_id'] = $cess_ledger_id;
                    $ledgerDetails[$index]['create_date'] = $created_date;
                    $ledgerDetails[$index]['narration'] = $narration;
                    $ledgerDetails[$index]['selected_currency'] = $selected_currency;
                    $ledgerDetails[$index]['unit_price'] = $base_unit;
                    $ledgerDetails[$index]['discount_type'] = 0;
                    $ledgerDetails[$index]['discount_amount'] = '';
                    $ledgerDetails[$index]['deleted'] = $deleted;
                }
                if (isset($discount_value_hidden) && count($discount_value_hidden) > 1) {
                    for ($i = 1; $i <= count($discount_value_hidden) - 1; $i++) {
                        $index++;
                        $j = $i + 1;
                        if (!empty($discount_value_hidden[$i - 1])) {
                            $balance = $discount_value_hidden[$i - 1];
                        }
                        $ledgerDetails[$index]['branch_id'] = $branch_id;
                        $ledgerDetails[$index]['account'] = $account_type[$j];
                        $ledgerDetails[$index]['balance'] = ($balance * $base_unit);
                        $ledgerDetails[$index]['entry_id'] = $entryId;
                        $ledgerDetails[$index]['ladger_id'] = $tr_ledger_id[$j];
                        $ledgerDetails[$index]['create_date'] = $created_date;
                        $ledgerDetails[$index]['narration'] = $narration;
                        $ledgerDetails[$index]['selected_currency'] = $selected_currency;
                        $ledgerDetails[$index]['unit_price'] = $base_unit;
                        $ledgerDetails[$index]['discount_type'] = 1;
                        $ledgerDetails[$index]['discount_amount'] = $discount_percent_hidden[$i - 1];
                        $ledgerDetails[$index]['deleted'] = $deleted;
                    }
                }

                if ($reverse_entry == 1 && ($entry_type_id == 6 || $parent_id==6)) {
                    $this->custommodel->setEntryDetails($ledgerDetails);
                    //computation
                    $debtors_amount = $debtors_amount + $tax_value;
                    $sales_amount = $sales_amount;

                    // For Ladger Account Details Table
                    $ledgerDetails = array();
                    $balance = 0;

                    $index = 0;

                    // debtors
                    $ledgerDetails[0]['branch_id'] = $branch_id;
                    $ledgerDetails[0]['account'] = $account_type[0];
                    $ledgerDetails[0]['balance'] = ($debtors_amount * $base_unit);
                    $ledgerDetails[0]['entry_id'] = $entryId;
                    $ledgerDetails[0]['ladger_id'] = $tr_ledger_id[0];
                    $ledgerDetails[0]['create_date'] = $created_date;
                    $ledgerDetails[0]['narration'] = $narration;
                    $ledgerDetails[0]['selected_currency'] = $selected_currency;
                    $ledgerDetails[0]['unit_price'] = $base_unit;
                    $ledgerDetails[0]['discount_type'] = 0;
                    $ledgerDetails[0]['discount_amount'] = '';
                    $ledgerDetails[0]['deleted'] = $deleted;
                    // sales
                    $ledgerDetails[1]['branch_id'] = $branch_id;
                    $ledgerDetails[1]['account'] = $account_type[1];
                    $ledgerDetails[1]['balance'] = ($sales_amount * $base_unit);
                    $ledgerDetails[1]['entry_id'] = $entryId;
                    $ledgerDetails[1]['ladger_id'] = $tr_ledger_id[1];
                    $ledgerDetails[1]['create_date'] = $created_date;
                    $ledgerDetails[1]['narration'] = $narration;
                    $ledgerDetails[1]['selected_currency'] = $selected_currency;
                    $ledgerDetails[1]['unit_price'] = $base_unit;
                    $ledgerDetails[1]['discount_type'] = 0;
                    $ledgerDetails[1]['discount_amount'] = '';
                    $ledgerDetails[1]['deleted'] = $deleted;

                    // GST tax
                    if ($igst_status == 1) {
                        //IGST   
                        $ledgerDetails[2]['branch_id'] = $branch_id;
                        $ledgerDetails[2]['account'] = $gst_type;
                        $ledgerDetails[2]['balance'] = ($igst_value * $base_unit);
                        $ledgerDetails[2]['entry_id'] = $entryId;
                        $ledgerDetails[2]['ladger_id'] = $igst_ledger_id;
                        $ledgerDetails[2]['create_date'] = $created_date;
                        $ledgerDetails[2]['narration'] = $narration;
                        $ledgerDetails[2]['selected_currency'] = $selected_currency;
                        $ledgerDetails[2]['unit_price'] = $base_unit;
                        $ledgerDetails[2]['discount_type'] = 0;
                        $ledgerDetails[2]['discount_amount'] = '';
                        $ledgerDetails[2]['deleted'] = $deleted;
                    } else {
                        //CGST
                        $ledgerDetails[2]['branch_id'] = $branch_id;
                        $ledgerDetails[2]['account'] = $gst_type;
                        $ledgerDetails[2]['balance'] = ($cgst_value * $base_unit);
                        $ledgerDetails[2]['entry_id'] = $entryId;
                        $ledgerDetails[2]['ladger_id'] = $cgst_ledger_id;
                        $ledgerDetails[2]['create_date'] = $created_date;
                        $ledgerDetails[2]['narration'] = $narration;
                        $ledgerDetails[2]['selected_currency'] = $selected_currency;
                        $ledgerDetails[2]['unit_price'] = $base_unit;
                        $ledgerDetails[2]['discount_type'] = 0;
                        $ledgerDetails[2]['discount_amount'] = '';
                        $ledgerDetails[2]['deleted'] = $deleted;
                        //SGST
                        $ledgerDetails[3]['branch_id'] = $branch_id;
                        $ledgerDetails[3]['account'] = $gst_type;
                        $ledgerDetails[3]['balance'] = ($sgst_value * $base_unit);
                        $ledgerDetails[3]['entry_id'] = $entryId;
                        $ledgerDetails[3]['ladger_id'] = $sgst_ledger_id;
                        $ledgerDetails[3]['create_date'] = $created_date;
                        $ledgerDetails[3]['narration'] = $narration;
                        $ledgerDetails[3]['selected_currency'] = $selected_currency;
                        $ledgerDetails[3]['unit_price'] = $base_unit;
                        $ledgerDetails[3]['discount_type'] = 0;
                        $ledgerDetails[3]['discount_amount'] = '';
                        $ledgerDetails[3]['deleted'] = $deleted;
                    }
                    //CESS Tax
                    if ($igst_status == 1) {
                        $index = 2;
                    } else {
                        $index = 3;
                    }
                    if ($cess_status == 1) {
                        $index = $index + 1;
                        $ledgerDetails[$index]['branch_id'] = $branch_id;
                        $ledgerDetails[$index]['account'] = $gst_type;
                        $ledgerDetails[$index]['balance'] = ($cess_value * $base_unit);
                        $ledgerDetails[$index]['entry_id'] = $entryId;
                        $ledgerDetails[$index]['ladger_id'] = $cess_ledger_id;
                        $ledgerDetails[$index]['create_date'] = $created_date;
                        $ledgerDetails[$index]['narration'] = $narration;
                        $ledgerDetails[$index]['selected_currency'] = $selected_currency;
                        $ledgerDetails[$index]['unit_price'] = $base_unit;
                        $ledgerDetails[$index]['discount_type'] = 0;
                        $ledgerDetails[$index]['discount_amount'] = '';
                        $ledgerDetails[$index]['deleted'] = $deleted;
                    }
                    if (isset($discount_value_hidden) && count($discount_value_hidden) > 1) {
                        for ($i = 1; $i <= count($discount_value_hidden) - 1; $i++) {
                            $index++;
                            $j = $i + 1;
                            if (!empty($discount_value_hidden[$i - 1])) {
                                $balance = $discount_value_hidden[$i - 1];
                            }
                            $ledgerDetails[$index]['branch_id'] = $branch_id;
                            $ledgerDetails[$index]['account'] = $account_type[$j];
                            $ledgerDetails[$index]['balance'] = ($balance * $base_unit);
                            $ledgerDetails[$index]['entry_id'] = $entryId;
                            $ledgerDetails[$index]['ladger_id'] = $tr_ledger_id[$j];
                            $ledgerDetails[$index]['create_date'] = $created_date;
                            $ledgerDetails[$index]['narration'] = $narration;
                            $ledgerDetails[$index]['selected_currency'] = $selected_currency;
                            $ledgerDetails[$index]['unit_price'] = $base_unit;
                            $ledgerDetails[$index]['discount_type'] = 1;
                            $ledgerDetails[$index]['discount_amount'] = $discount_percent_hidden[$i - 1];
                            $ledgerDetails[$index]['deleted'] = $deleted;
                        }
                    }

                    $this->custommodel->setComputationEntryDetails($ledgerDetails);
                    //endcomputation
                } else {
                    $this->custommodel->setEntryDetails($ledgerDetails);
                }
                
                
                //Closing Balance update 03052018
                $financial_year = get_financial_year();
                $from_date = date("Y-m-d", strtotime(current($financial_year)));
                $to_date = date("Y-m-t", strtotime(end($financial_year)));
                foreach($ledgerDetails AS $ledger_id){
                    $ledger_detail = $this->inventorymodel->getLedgerByLedgerIdByDate($ledger_id['ladger_id'],$from_date,$to_date,$branch_id);
                    $where= array(
                        'branch_id'=>$branch_id,
                        'entry_id'=>0,
                        'ladger_id'=>$ledger_id['ladger_id']
                    );
                    $closingValue['current_closing_balance'] = ($ledger_detail['account_type'] == 'Dr')?$ledger_detail['dr_balance'] - $ledger_detail['cr_balance'] + $ledger_detail['opening_balance']:$ledger_detail['cr_balance'] - $ledger_detail['dr_balance'] + $ledger_detail['opening_balance'];
                    $this->inventorymodel->updateClosingBalance($where,$closingValue);
                }
                
                // For Billwise Details Auto submission (without popup)

                if (count($bill_data) > 0) {

                    for ($k = 0; $k < count($bill_data); $k++) {

                        $bill_data[$k]['entry_id'] = $entryId;
                        $bill_data[$k]['deleted'] = $deleted;
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
                            $tracking_details_array[$t]['account_type'] = $trackingOther[$j]->tracking_id;
//                            $tracking_details_array[$t]['account_type'] = $trackingOther[$j]->account_type;
                            $tracking_details_array[$t]['tracking_amount'] = $trackingOther[$j]->tracking_amount;
                            $tracking_details_array[$t]['entry_id'] = $entryId;
                            $tracking_details_array[$t]['created_date'] = $created_date;
                            $tracking_details_array[$t]['deleted'] = $deleted;
                            $t++;
                        }
                    }
                    $this->custommodel->insertTracking($tracking_details_array);
                }

                // For Order
                $order = array(
                    'branch_id' => $branch_id,
                    'users_id' => $tr_ledger_id[0],
                    'total' => ($product_grand_total * $base_unit),
                    'spl_discount_json' => (isset($product_discount[0]) && !empty($product_discount[0]))?'1':'',
                    'order_date' => $created_date,
                    'creation_date' => $created_date,
                    'tax_amount' => ($tax_value * $base_unit),
                    'entry_id' => $entryId,
                    'grand_total' => ($product_grand_total * $base_unit),
                    'currency_code' => $selected_currency,
                    'order_type' => ($entry_type_id == 5 || $parent_id==5) ? '1' : '2',
                    'flow_type' => ($entry_type_id == 5 || $parent_id==5) ? '1' : '0',
                    'is_igst_included' => $igst_status,
                    'is_cess_included' => $cess_status,
                    'status' => $order_deleted
                );
                if ($type_service_product != 1) {
                    $orderId = $this->custominventorymodel->setOrder($order);
                } else {
                    $orderId = $this->custominventorymodel->setTempOrder($order);
                }
                
//                //shiping address add
                if($shipping_id != ''){
                    $table = 'orders';
                    $this->setOrderAddressDetails($orderId,$tr_ledger_id[0],$shipping_id,$table,$isDataNewRef);
                }
                
                //despatch detaild
                if($despatch_through != '' || $motor_vehicle_no != ''  ){
                    despatchDetails($entryId);
                }
                
                // For Order Details
                if (count($product_id) > 0) {
                    $productDetails = array();
                    $godown_array = array();

                    for ($j = 0; $j < count($product_id); $j++) {
                        
                        $base_unit_id           = 0;
                        $complex_unit_id        = 0;
                        $base_qty               = 0;
                        $complex_qty            = 0;
                        $complex_product_price  = 0;
                        $base_product_price     = 0;
                        if($complex_unit[$j] == $unit[$j]){
                            $base_unit_id           = $unit[$j];
                            $complex_unit_id        = $unit[$j];
                            $base_qty               = $product_quantity[$j];
                            $complex_qty            = $product_quantity[$j];
                            $complex_product_price  = $product_price[$j];
                            $base_product_price     = $product_price[$j];
                        }else{
                            $calculate_qty = ($this->calculateBaseUnit($unit[$j], $complex_unit[$j]))?$this->calculateBaseUnit($unit[$j], $complex_unit[$j]):1;
                            $base_unit_id           = $unit[$j];
                            $complex_unit_id        = ($calculate_qty > 0)?$complex_unit[$j]:$unit[$j];
                            $base_qty               = ($calculate_qty > 0)?$calculate_qty:$product_quantity[$j];
                            $complex_qty            = $product_quantity[$j];
                            $complex_product_price  = $product_price[$j];
                            $base_product_price     = ($calculate_qty > 0)?$product_price[$j]/$calculate_qty:$product_price[$j];
                        }
                        
                        $productDetails[$j]['branch_id'] = $branch_id;
                        $productDetails[$j]['order_id'] = $orderId;
                        $productDetails[$j]['product_id'] = $product_id[$j];
                        $productDetails[$j]['product_description'] = $product_description[$j];//19042018
                        $productDetails[$j]['stock_id'] = $stock_id[$j];
                        $productDetails[$j]['quantity'] = $base_qty;
                        
                        $productDetails[$j]['unit_id'] = $complex_unit_id;
                        $productDetails[$j]['transaction_qty'] = $product_quantity[$j];
                        $productDetails[$j]['transaction_price'] = $product_price[$j];
                        
                        $productDetails[$j]['original_price'] = $base_product_price;//change
                        $productDetails[$j]['base_price'] =$base_product_price;//change
                        $productDetails[$j]['discount_percentage'] = $product_discount[$j];//16072018
                        $productDetails[$j]['discount_amount'] = (($product_quantity[$j] * $product_price[$j]) - $gross_total_price_per_prod[$j]);//16072018
                        $productDetails[$j]['price'] = $gross_total_price_per_prod[$j];
                        $productDetails[$j]['igst_tax_percent'] = ($igst_tax_percent[$j]) ? $igst_tax_percent[$j] : 0;
                        $productDetails[$j]['igst_tax'] = ($igst_tax_value[$j]) ? $igst_tax_value[$j] : 0;
                        $productDetails[$j]['cgst_tax_percent'] = ($cgst_tax_percent[$j]) ? $cgst_tax_percent[$j] : 0;
                        $productDetails[$j]['cgst_tax'] = ($cgst_tax_value[$j]) ? $cgst_tax_value[$j] : 0;
                        $productDetails[$j]['sgst_tax_percent'] = ($sgst_tax_percent[$j]) ? $sgst_tax_percent[$j] : 0;
                        $productDetails[$j]['sgst_tax'] = ($sgst_tax_value[$j]) ? $sgst_tax_value[$j] : 0;
                        $productDetails[$j]['cess_tax_percent'] = ($cess_tax_percent[$j]) ? $cess_tax_percent[$j] : 0;
                        $productDetails[$j]['cess_tax'] = ($cess_tax_value[$j]) ? $cess_tax_value[$j] : 0;
                        $productDetails[$j]['total'] = $total_price_per_prod_with_tax[$j];
                        $productDetails[$j]['creation_date'] = $created_date;
                        $productDetails[$j]['flow_type'] = ($entry_type_id == 5 || $parent_id==5) ? '66' : '55';
                        $productDetails[$j]['order_type'] = ($entry_type_id == 5 || $parent_id==5) ? '1' : '2';
                        $productDetails[$j]['status'] = $order_deleted;
                        
                        //For primary godown Godown if godown status == 0 and batch status == 0 
                         if($batch_status == 0 && $godown_status == 0){
                            $godown_array[$j]['entry_id'] = $entryId;
                            $godown_array[$j]['stock_id'] = $orderId;
                            $godown_array[$j]['godown_id'] = 1;
                            $godown_array[$j]['godown_name'] = 'Local Store';
                            $godown_array[$j]['product_id'] = $product_id[$j];
                            $godown_flow_type = ($entry_type_id == 5 || $parent_id==5) ? 'quantity_out' : 'quantity_in';
                            $godown_array[$j][$godown_flow_type] = $product_quantity[$j];
                            $godown_array[$j]['transaction_type'] = ($entry_type_id == 5 || $parent_id==5) ? '1' : '2';
                         }
                        
                    }
                    if ($type_service_product != 1) {
                        $this->custominventorymodel->insertOrderDetails($productDetails);
                        
                        //For primary godown Godown if godown status == 0 and batch status == 0 
                        if($batch_status == 0 && $godown_status == 0){
                            $this->inventorymodel->insertGodown($godown_array);
                        }
                    } else {
                        $this->custominventorymodel->insertTempOrderDetails($productDetails);
                    }
                }
                
                
                
                //For Batch AND Godown 
//                if($entry_type_id == 6 || $parent_id==6){
                    if (count($batchGodown) > 0) {
                        // $dataBanking = json_decode($dataBanking);
                        if(($godown_status == 0 && $batch_status == 1) || ($godown_status == 1 && $batch_status == 1) || ($godown_status == 1 && $batch_status == 0)){
//                            $batch_array = array();
//                            $godown_array = array();
//                            $b = 0;
//                            $g = 0;
                            for ($k = 0; $k < count($batchGodown); $k++) {
                                $batch_array = array();
                                $godown_array = array();
                                $b = 0;
                                $g = 0;
                                $batchValue = json_decode($batchGodown[$k]['value']);
                                for ($j = 0; $j < count($batchValue); $j++) {
                                    if($batchValue[$j]->productBatchStatus == 1 && $batch_status == 1){
                                        // For bathch 
                                        $manufact_date = date("Y-m-d", strtotime(str_replace('/', '-', $batchValue[$j]->manufact_date)));
                                        if ($batchValue[$j]->exp_type_id == 1) {
                                            $expiry_date = date('Y-m-d', strtotime(str_replace('/', '-',$batchValue[$j]->exp_days)));
                                        } else {
                                            $expiry_date = date('Y-m-d', strtotime("+".$batchValue[$j]->exp_days." " . $batchValue[$j]->exp_type, strtotime($manufact_date)));
                                        }
                                        
                                        $batch_array[$b]['branch_id'] = $branch_id;
                                        $batch_array[$b]['stock_id'] = $orderId; //as order id in many plase
                                        $batch_array[$b]['godown_id'] = ($batchValue[$j]->batch_godown_id)?($batchValue[$j]->batch_godown_id):1;
                                        $batch_array[$b]['product_id'] = $batchGodown[$k]['product_id'];
                                        $batch_array[$b]['batch_no'] = $batchValue[$j]->batch_no;
                                        $batch_array[$b]['parent_id'] = ($entry_type_id == 5 || $parent_id==5) ? $batchValue[$j]->batch_id : 0;
                                        $batch_array[$b]['in_out'] = ($entry_type_id == 5 || $parent_id==5) ? 2 : 1;
                                        $batch_array[$b]['quantity'] = $batchValue[$j]->batch_qty;
                                        $batch_array[$b]['rate'] = $batchValue[$j]->batch_rate;
                                        $batch_array[$b]['value'] = $batchValue[$j]->batch_value;
                                        $batch_array[$b]['exp_type'] = $batchValue[$j]->exp_type_id;
                                        $batch_array[$b]['exp_days_given'] = $batchValue[$j]->exp_days;
                                        $batch_array[$b]['manufact_date'] = $manufact_date;
                                        $batch_array[$b]['exp_date'] = $expiry_date;
                                        $batch_array[$j]['transaction_type'] = ($entry_type_id == 5 || $parent_id==5) ? '1' : '2';
                                        $b++;
                                    }
                                    // For godown 
                                    $godown_array[$g]['entry_id'] = $entryId;
                                    $godown_array[$g]['stock_id'] = $orderId;
                                    $godown_array[$g]['godown_id'] = ($batchValue[$j]->batch_godown_id)?$batchValue[$j]->batch_godown_id:1;
                                    $godown_array[$g]['godown_name'] = ($batchValue[$j]->batch_godown_name)?$batchValue[$j]->batch_godown_name:'Local Store';
                                    $godown_array[$g]['product_id'] = $batchGodown[$k]['product_id'];
                                    $godown_flow_type = ($entry_type_id == 5 || $parent_id==5) ? 'quantity_out' : 'quantity_in';
                                    $godown_array[$g][$godown_flow_type] = ($batchValue[$j]->productBatchStatus == 1 && $batch_status == 1)?$batchValue[$j]->batch_qty:$batchValue[$j]->godown_qty;
                                    $godown_array[$g]['transaction_type'] = ($entry_type_id == 5 || $parent_id==5) ? '1' : '2';
                                    $g++;
                                    
                                }
                                
                                if(count($batch_array) > 0){
                                    $this->inventorymodel->insertBatch($batch_array);
                                }
                                $this->inventorymodel->insertGodown($godown_array);
                            }
//                            if(count($batch_array) > 0){
//                                $this->inventorymodel->insertBatch($batch_array);
//                            }
//                            $this->inventorymodel->insertGodown($godown_array);
                        }
                    }
//                }


                $res = $this->inventorymodel->deleteRequestTransaction($entry_id);
                
                //recurring
                if (isset($recurring_freq) && ($recurring_freq == 'Daily' || $recurring_freq == 'Weekly' || $recurring_freq == 'Monthly' || $recurring_freq == 'Yearly')) {
                    if ($type_service_product != 1) {
                        $this->saveRecurring($recurring_freq, $created_date, $entryId);
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'save_err';
                    $data_msg['message'] = "There was an error submitting the form";
                } else {
                    $this->db->trans_commit();

                    $pref = $this->inventorymodel->getPreferences();
                    if($pref->sales_mail == 1) {
                        //mail
                        // $this->load->helper('email');
                        $this->load->helper('actmail');
                        $company_details = $this->inventorymodel->getCompanyDetails();
                        $voucher = ($entry_type_id == 5 || $parent_id==5) ? 'Sales' : 'Purchase';
                        $message = "Your " . $voucher . " transaction added successfully. " . $voucher . " number is " . $entry_number;
                        $company_mail_data = array($company_details->company_name, $message);
                        
                        if ($entry_type_id == 5 || $parent_id == 5) {
                            // sendMail($template = 'transaction', $slug = 'transaction_mail', $to = $company_details->email, $company_mail_data);
                            $attach = $this->testInvoice($entryId); // invoice attachment
                            $company_name_for_mail = $company_details->company_name;
                            // sendActMail($template = 'sales_template', $slug = 'sales_product', $to = $company_details->email, $company_mail_data, $attach, $company_name_for_mail);
                            // sendActMail($template = 'recurring_template', $slug = 'recurring_entry', $to = $company_details->email, $mail_data);
                            $ledger_contact_details = $this->inventorymodel->getLedgerContactDetails($tr_ledger_id[0]);
                            if ($ledger_contact_details) {
                                //mail   
                                $ledger_mail_data = array($ledger_contact_details->company_name, $message);
                                // sendMail($template = 'transaction', $slug = 'transaction_mail', $to = $ledger_contact_details->email, $ledger_mail_data);
                                sendActMail($template = 'sales_template', $slug = 'sales_product', $to = $ledger_contact_details->email, $ledger_mail_data, $attach, $company_name_for_mail);
                                //end mail 
                            }
                        }
                    }
                    
                    
                    //end mail
                    $data_msg['res'] = 'success';
                    $print_url = base_url('transaction/invoice') . '.aspx/' . $entryId . '/' . $entry_type_id;
                    $redirect_url=base_url('admin/inventory-transaction-list'). "/" .  str_replace(' ','-',strtolower(trim($entry_type['type']))).'/'.$entry_type['id'].'/all';
                    $data_msg['redirect_url']=$redirect_url;
                    $data_msg['print_url'] = $print_url;
                    $data_msg['message'] = "Transaction added successfully. Entry number #" . $entry_number;
                }
            } else {
                $data_msg['res'] = 'error';
                // $data_msg['message'] = $this->form_validation->error_array();
                $data_msg['message'] = validation_errors();
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

    public function ajax_order_add() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $user_id = $this->session->userdata('admin_uid');
            $branch_id = $this->session->userdata('branch_id');
            $entry_number = $this->input->post('entry_number');
            $date_form = $this->input->post('date_form');
            $account_type = $this->input->post('tr_type');
            $credit_days = $this->input->post('credit_days');
            $postdated = $this->input->post('postdated');
            $product_grand_total = $this->input->post('product_grand_total');
            $ledger_name = $this->input->post('tr_ledger');
            $netTotal = $this->input->post('netTotal');
            $discount_value_hidden = $this->input->post('discount_value_hidden');
            $discount_percent_hidden = $this->input->post('discount_percent_hidden');
            $tax_value = $this->input->post('tax_value');
            $narration = $this->input->post('notes');

            $tr_ledger_id = $this->input->post('tr_ledger_id');
            $dataTracking = isset($_POST['tracking']) ? $_POST['tracking'] : null;

            //For Product 
            $product_id = $this->input->post('product_id');
            $stock_id = $this->input->post('stock_id');
            $product_name = $this->input->post('product_name');
            $product_description = $this->input->post('product_description');//19042018
            $product_quantity = $this->input->post('product_quantity');
            $product_price = $this->input->post('product_price');
            $tax_percent = $this->input->post('tax_percent');
            $tax_value = $this->input->post('tax_value');
            $product_discount = $this->input->post('product_discount');//16072018
            $total_price_per_prod = $this->input->post('total_price_per_prod');
            $gross_total_price_per_prod = $this->input->post('gross_total_price_per_prod');
            $total_price_per_prod_with_tax = $this->input->post('total_price_per_prod_with_tax');
            $entry_id = $this->input->post('entry_id');
            $terms_and_conditions = $this->input->post('terms_and_conditions');

            //GST
            $igst_tax_percent = $this->input->post('igst_tax_percent');
            $igst_tax_value = $this->input->post('igst_tax_value');

            $sgst_tax_percent = $this->input->post('sgst_tax_percent');
            $sgst_tax_value = $this->input->post('sgst_tax_value');

            $cgst_tax_percent = $this->input->post('cgst_tax_percent');
            $cgst_tax_value = $this->input->post('cgst_tax_value');

            $cess_status_col = $this->input->post('cess_status_col');
            $igst_status = $this->input->post('igst_status');

            $cess_tax_percent = $this->input->post('cess_percent');
            $cess_tax_value = $this->input->post('cess_value');
            
            //bank details add in invoice
            $bank_id = $this->input->post('bank_id');
            
            //despatch detaild in invoice
            $despatch_through = $this->input->post('despatch_through');
            $motor_vehicle_no = $this->input->post('motor_vehicle_no');
            

            //GST
            $voucher_no = $this->input->post('voucher_no');
            $voucher_date = $this->input->post('voucher_date');
            $voucher_date = date("Y-m-d", strtotime(str_replace('/', '-', $voucher_date)));
            $count = count($tr_ledger_id);

            $entry_type_id = $this->input->post('entry_type');
            $parent_id = $this->input->post('parent_id');
            $entry_type = $this->entry->getEntryTypeById($entry_type_id);
            $created_date = date("Y-m-d", strtotime(str_replace('/', '-', $date_form)));
            if ($entry_type_id == 6) {
                $this->form_validation->set_rules('tr_ledger[0]', 'Creditors', 'required');
                $this->form_validation->set_rules('tr_ledger[1]', 'Purchase', 'required');
            } else if($entry_type_id == 5) {
                $this->form_validation->set_rules('tr_ledger[0]', 'Debtors', 'required');
                $this->form_validation->set_rules('tr_ledger[1]', 'Sales', 'required');
            }
            if ($entry_number != 'Auto' || $entry_number != null || $entry_number != '') {
                $this->form_validation->set_rules('entry_number', 'Entry Number', 'required|alpha_dash_space');
            }
            if ($postdated == 1) {
                $this->form_validation->set_rules('date_form', 'Transaction date', 'required|check_postdated_date');
            } else {
                $this->form_validation->set_rules('date_form', 'Transaction date', 'required');
            }
            $this->form_validation->set_rules('tr_ledger_id[0]', 'Ledger', 'required');
            $this->form_validation->set_rules('tr_ledger_id[1]', 'Ledger', 'required');
            $this->form_validation->set_rules('tr_type[0]', 'Account Type', 'trim|required|check_account_type');
            $this->form_validation->set_rules('tr_type[1]', 'Account Type', 'trim|required|check_account_type');
            $this->form_validation->set_rules('voucher_no', 'Referrence No', 'required');
            $this->form_validation->set_rules('voucher_date', 'Referrence Date', 'required');
            $this->form_validation->set_rules('product_id[]', 'Product', 'required');
            $this->form_validation->set_rules('product_price[]', 'Product Price', 'required|numeric');
            if ($this->form_validation->run() === TRUE) {
                //generate auto entry no
                if ($entry_number == 'Auto' || $entry_number == null || $entry_number == '') {
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
                }

                $order = array();
                $entry = array();
                $new_array = array();
                $bill_data = array();

                //calculate tax
                $igst_value = 0;
                $cgst_value = 0;
                $sgst_value = 0;
                if ($igst_status == 1) {
                    foreach ($igst_tax_value as $igst) {
                        $igst_value+=$igst;
                    }
                } else {
                    foreach ($cgst_tax_value as $cgst) {
                        $cgst_value+=$cgst;
                    }
                    foreach ($sgst_tax_value as $sgst) {
                        $sgst_value+=$sgst;
                    }
                }
                $cess_status = 0;
                $cess_value = 0;
                foreach ($cess_status_col as $k => $cess) {
                    if ($cess == 1) {
                        $cess_status = 1;
                        $cess_value+=$cess_tax_value[$k];
                    }
                }
                if ($entry_type_id == 5 || $parent_id==5) {
                    $gst_type = 'Cr';
                    if ($igst_status == 1) {
                        $igst_ledger_id = 31;
                    } else {
                        $cgst_ledger_id = 33;
                        $sgst_ledger_id = 35;
                    }
                    $cess_type = 'Cr';
                    $cess_ledger_id = 37;
                } else if ($entry_type_id == 6 || $parent_id==6) {
                    $gst_type = 'Dr';
                    if ($igst_status == 1) {
                        $igst_ledger_id = 32;
                    } else {
                        $cgst_ledger_id = 34;
                        $sgst_ledger_id = 36;
                    }
                    $cess_type = 'Dr';
                    $cess_ledger_id = 38;
                }
                //end calculate GST
                $sub_voucher = 0;
                $is_inventry = 1;
                $order_id = "";
                $discount_sum = 0;
                $product_grand_total = $netTotal; //08032018@sudip
                $debtors_amount = $product_grand_total;
                for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
                    $discount_sum+=$discount_value_hidden[$i];
                }
                if ($entry_type_id == 5 || $parent_id==5) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                } else if ($entry_type_id == 6 || $parent_id==6) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                }

                $isDataNewRef = 1;

                // $bill_data = array();

                $baseCurrency = $this->entry->getDefoultCurrency();
                if ($baseCurrency) {
                    $currency_unit = $this->entry->getCurrencyUnitById($baseCurrency['base_currency']);
                }


                $base_unit = $currency_unit['unit_price'];
                $selected_currency = $currency_unit['id'];

//                for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
//                    if ($account_type[$i] == 'Cr') {
//                        if ($entry_type_id == 5) {
//                            $ledger_value += $discount_value_hidden[$i];
//                        } else if ($entry_type_id == 6) {
//                            $ledger_value -= $discount_value_hidden[$i];
//                        }
//                    }
//                    if (!empty($discount_value_hidden[$i])) {
//                        if ($account_type[$i] == 'Dr') {
//                            if ($entry_type_id == 5) {
//                                $debtors_amount -= $discount_value_hidden[$i];
//                            } else if ($entry_type_id == 6) {
//                                $debtors_amount += $discount_value_hidden[$i];
//                            }
//                        }
//                        if ($account_type[$i] == 'Cr') {
//                            if ($entry_type_id == 5) {
//                                $debtors_amount += $discount_value_hidden[$i];
//                            } else if ($entry_type_id == 6) {
//                                $debtors_amount -= $discount_value_hidden[$i];
//                            }
//                        }
//                    }
//                }

                if ($isDataNewRef == 1) {

                    // $newReferenceLedgerArray = $_POST['newReferenceLedgerArray'];
                    // for ($i = 0; $i < count($newReferenceLedgerArray); $i++) {

                    $ledgeId = $tr_ledger_id[0];

                    $hasBilling = $this->custommodel->getTBilling($ledgeId);
                    $hasBillingFinal = $hasBilling->bill_details_status;

                    if ($hasBillingFinal == "1") {

                        /* Fetch data */
                        $ledgeDet = $this->custommodel->getNewRefLedgerDetails($ledgeId);

                        $credit_date_get = $credit_days;
                        $credit_limit_get = $ledgeDet->credit_limit;




                        // if ($entry_type_id == 5) {
                        $getDiffDrCrBilling = $this->custommodel->getDiffDrCrBillingSales($ledgeId);
                        // } else if ($entry_type_id == 6) {
                        //     $getDiffDrCrBilling = $this->custommodel->getDiffDrCrBillingPurchase($ledgeId);
                        // }



                        $diff = $getDiffDrCrBilling->diff;

                        // if ($newReferenceLedgerArray[$i]["drAmt"] > 0) {
                        $total = $debtors_amount + $diff;
                        $amount = $debtors_amount;
                        // } else if ($newReferenceLedgerArray[$i]["crAmt"] > 0) {
                        //     $total = $newReferenceLedgerArray[$i]["crAmt"] + $diff;
                        //     $amount = $newReferenceLedgerArray[$i]["crAmt"];
                        // }




                        if ($entry_type_id == 5 || $parent_id==5) {
                            if ($total <= $credit_limit_get) {


                                $bill_data[0]['branch_id'] = $branch_id;
                                $bill_data[0]['ledger_id'] = $tr_ledger_id[0];
                                $bill_data[0]['dr_cr'] = $account_type[0];
                                $bill_data[0]['ref_type'] = 'New Reference';
                                $bill_data[0]['bill_name'] = $entry_number;
                                $bill_data[0]['credit_days'] = $credit_date_get;
                                $bill_data[0]['credit_date'] = date('Y-m-d', strtotime("+" . $credit_date_get . " days"));
                                $bill_data[0]['bill_amount'] = ($amount * $base_unit);
                                $bill_data[0]['entry_id'] = "";
                                $bill_data[0]['created_date'] = $created_date;
                            } else {

                                $data_msg['res'] = 'save_err';
                                $data_msg['message'] = "Your credit limit has exceeded!";

                                echo json_encode($data_msg);
                                exit;
                            }
                        } else if ($entry_type_id == 6 || $parent_id==6) {
                            $bill_data[0]['branch_id'] = $branch_id;
                            $bill_data[0]['ledger_id'] = $tr_ledger_id[0];
                            $bill_data[0]['dr_cr'] = $account_type[0];
                            $bill_data[0]['ref_type'] = 'New Reference';
                            $bill_data[0]['bill_name'] = $entry_number;
                            $bill_data[0]['credit_days'] = $credit_date_get;
                            $bill_data[0]['credit_date'] = date('Y-m-d', strtotime("+" . $credit_date_get . " days"));
                            $bill_data[0]['bill_amount'] = ($amount * $base_unit);
                            $bill_data[0]['entry_id'] = "";
                            $bill_data[0]['created_date'] = $created_date;
                        }
                    }
                    // }
                }

                for ($i = 0; $i < (count($ledger_name) - 1); $i++) {

                    $new_array[$account_type[$i]][] = $ledger_name[$i];
                }

                $ledger_name_json = json_encode($new_array);

                $sum_dr = $debtors_amount + $discount_sum;
                $sum_cr = $sales_amount + $tax_value;
                // For Entry Table
                $entry = array(
                    'entry_no' => $entry_number,
                    'create_date' => $created_date,
                    'ledger_ids_by_accounts' => $ledger_name_json,
                    'dr_amount' => ($entry_type_id == 5 || $parent_id==5) ? ($sum_dr * $base_unit) : ($sum_dr * $base_unit),
                    'cr_amount' => ($entry_type_id == 5 || $parent_id==5) ? ($sum_cr * $base_unit) : ($sum_cr * $base_unit),
                    'unit_price_dr' => ($entry_type_id == 5 || $parent_id==5) ? $sum_dr : $sum_dr,
                    'unit_price_cr' => ($entry_type_id == 5 || $parent_id==5) ? $sum_cr : $sum_cr,
                    'entry_type_id' => ($entry_type['parent_id']==0)?$entry_type_id:$entry_type['parent_id'],
                    'sub_voucher' => ($entry_type['parent_id']!=0)?$entry_type_id:$entry_type['parent_id'],
                    'user_id' => $user_id,
                    'company_id' => $branch_id,
                    'voucher_no' => $voucher_no,
                    'voucher_date' => $voucher_date,
                    'is_inventry' => $is_inventry,
                    'narration' => $narration,
                    'order_id' => $order_id,
                    'bank_id' => (isset($bank_id) && $bank_id != '') ? $bank_id : 0
                );

                $this->db->trans_begin();

                $entryId = $this->custommodel->setEntry($entry);

                // For Ladger Account Details Table
                $ledgerDetails = array();
                $balance = 0;

                $index = 0;


                // debtors
                $ledgerDetails[0]['branch_id'] = $branch_id;
                $ledgerDetails[0]['account'] = $account_type[0];
                $ledgerDetails[0]['balance'] = ($debtors_amount * $base_unit);
                $ledgerDetails[0]['entry_id'] = $entryId;
                $ledgerDetails[0]['ladger_id'] = $tr_ledger_id[0];
                $ledgerDetails[0]['create_date'] = $created_date;
                $ledgerDetails[0]['narration'] = $narration;
                $ledgerDetails[0]['selected_currency'] = $selected_currency;
                $ledgerDetails[0]['unit_price'] = $base_unit;
                $ledgerDetails[0]['discount_type'] = 0;
                $ledgerDetails[0]['discount_amount'] = '';
                $ledgerDetails[0]['deleted'] = '0';
                // sales
                $ledgerDetails[1]['branch_id'] = $branch_id;
                $ledgerDetails[1]['account'] = $account_type[1];
                $ledgerDetails[1]['balance'] = ($sales_amount * $base_unit);
                $ledgerDetails[1]['entry_id'] = $entryId;
                $ledgerDetails[1]['ladger_id'] = $tr_ledger_id[1];
                $ledgerDetails[1]['create_date'] = $created_date;
                $ledgerDetails[1]['narration'] = $narration;
                $ledgerDetails[1]['selected_currency'] = $selected_currency;
                $ledgerDetails[1]['unit_price'] = $base_unit;
                $ledgerDetails[1]['discount_type'] = 0;
                $ledgerDetails[1]['discount_amount'] = '';
                $ledgerDetails[1]['deleted'] = '0';

                // GST tax
                if ($igst_status == 1) {
                    //IGST  
                    $ledgerDetails[2]['branch_id'] = $branch_id;
                    $ledgerDetails[2]['account'] = $gst_type;
                    $ledgerDetails[2]['balance'] = ($igst_value * $base_unit);
                    $ledgerDetails[2]['entry_id'] = $entryId;
                    $ledgerDetails[2]['ladger_id'] = $igst_ledger_id;
                    $ledgerDetails[2]['create_date'] = $created_date;
                    $ledgerDetails[2]['narration'] = $narration;
                    $ledgerDetails[2]['selected_currency'] = $selected_currency;
                    $ledgerDetails[2]['unit_price'] = $base_unit;
                    $ledgerDetails[2]['discount_type'] = 0;
                    $ledgerDetails[2]['discount_amount'] = '';
                    $ledgerDetails[2]['deleted'] = '0';
                } else {
                    //CGST  
                    $ledgerDetails[2]['branch_id'] = $branch_id;
                    $ledgerDetails[2]['account'] = $gst_type;
                    $ledgerDetails[2]['balance'] = ($cgst_value * $base_unit);
                    $ledgerDetails[2]['entry_id'] = $entryId;
                    $ledgerDetails[2]['ladger_id'] = $cgst_ledger_id;
                    $ledgerDetails[2]['create_date'] = $created_date;
                    $ledgerDetails[2]['narration'] = $narration;
                    $ledgerDetails[2]['selected_currency'] = $selected_currency;
                    $ledgerDetails[2]['unit_price'] = $base_unit;
                    $ledgerDetails[2]['discount_type'] = 0;
                    $ledgerDetails[2]['discount_amount'] = '';
                    $ledgerDetails[2]['deleted'] = '0';
                    //SGST
                    $ledgerDetails[3]['branch_id'] = $branch_id;
                    $ledgerDetails[3]['account'] = $gst_type;
                    $ledgerDetails[3]['balance'] = ($sgst_value * $base_unit);
                    $ledgerDetails[3]['entry_id'] = $entryId;
                    $ledgerDetails[3]['ladger_id'] = $sgst_ledger_id;
                    $ledgerDetails[3]['create_date'] = $created_date;
                    $ledgerDetails[3]['narration'] = $narration;
                    $ledgerDetails[3]['selected_currency'] = $selected_currency;
                    $ledgerDetails[3]['unit_price'] = $base_unit;
                    $ledgerDetails[3]['discount_type'] = 0;
                    $ledgerDetails[3]['discount_amount'] = '';
                    $ledgerDetails[3]['deleted'] = '0';
                }
                //CESS Tax
                if ($igst_status == 1) {
                    $index = 2;
                } else {
                    $index = 3;
                }
                if ($cess_status == 1) {
                    $index = $index + 1;
                    $ledgerDetails[$index]['branch_id'] = $branch_id;
                    $ledgerDetails[$index]['account'] = $gst_type;
                    $ledgerDetails[$index]['balance'] = ($cess_value * $base_unit);
                    $ledgerDetails[$index]['entry_id'] = $entryId;
                    $ledgerDetails[$index]['ladger_id'] = $cess_ledger_id;
                    $ledgerDetails[$index]['create_date'] = $created_date;
                    $ledgerDetails[$index]['narration'] = $narration;
                    $ledgerDetails[$index]['selected_currency'] = $selected_currency;
                    $ledgerDetails[$index]['unit_price'] = $base_unit;
                    $ledgerDetails[$index]['discount_type'] = 0;
                    $ledgerDetails[$index]['discount_amount'] = '';
                    $ledgerDetails[$index]['deleted'] = '0';
                }
                if (isset($discount_value_hidden) && count($discount_value_hidden) > 1) {
                    for ($i = 1; $i <= count($discount_value_hidden) - 1; $i++) {
                        $index++;
                        $j = $i + 1;
                        if (!empty($discount_value_hidden[$i - 1])) {
                            $balance = $discount_value_hidden[$i - 1];
                        }
                        $ledgerDetails[$index]['branch_id'] = $branch_id;
                        $ledgerDetails[$index]['account'] = $account_type[$j];
                        $ledgerDetails[$index]['balance'] = ($balance * $base_unit);
                        $ledgerDetails[$index]['entry_id'] = $entryId;
                        $ledgerDetails[$index]['ladger_id'] = $tr_ledger_id[$j];
                        $ledgerDetails[$index]['create_date'] = $created_date;
                        $ledgerDetails[$index]['narration'] = $narration;
                        $ledgerDetails[$index]['selected_currency'] = $selected_currency;
                        $ledgerDetails[$index]['unit_price'] = $base_unit;
                        $ledgerDetails[$index]['discount_type'] = 1;
                        $ledgerDetails[$index]['discount_amount'] = $discount_percent_hidden[$i - 1];
                        $ledgerDetails[$index]['deleted'] = '0';
                    }
                }

                $this->custommodel->setEntryDetails($ledgerDetails);
                
                //Closing Balance update 03052018
                $financial_year = get_financial_year();
                $from_date = date("Y-m-d", strtotime(current($financial_year)));
                $to_date = date("Y-m-t", strtotime(end($financial_year)));
                foreach($ledgerDetails AS $ledger_id){
                    $ledger_detail = $this->inventorymodel->getLedgerByLedgerIdByDate($ledger_id['ladger_id'],$from_date,$to_date,$branch_id);
                    $where= array(
                        'branch_id'=>$branch_id,
                        'entry_id'=>0,
                        'ladger_id'=>$ledger_id['ladger_id']
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
                            $tracking_details_array[$t]['account_type'] = $trackingOther[$j]->tracking_id;
                            $tracking_details_array[$t]['tracking_amount'] = $trackingOther[$j]->tracking_amount;
                            $tracking_details_array[$t]['entry_id'] = $entryId;
                            $tracking_details_array[$t]['created_date'] = $created_date;
                            $tracking_details_array[$t]['deleted'] = $deleted;
                            $t++;
                        }
                    }
                    $this->custommodel->insertTracking($tracking_details_array);
                }
                
                //for shiping address and billing address
                $getAddress = $this->inventorymodel->getRequestOrder($entry_id);
                
                // For Order
                $order = array(
                    'branch_id' => $branch_id,
                    'users_id' => $tr_ledger_id[0],
                    'total' => ($product_grand_total * $base_unit),
                    'spl_discount_json' => (isset($product_discount[0]) && !empty($product_discount[0]))?'1':'',
                    'order_date' => $created_date,
                    'creation_date' => $created_date,
                    'tax_amount' => ($tax_value * $base_unit),
                    'entry_id' => $entryId,
                    'grand_total' => ($product_grand_total * $base_unit),
                    'currency_code' => $selected_currency,
                    'order_type' => ($entry_type_id == 5 || $parent_id==5) ? '1' : '2',
                    'flow_type' => ($entry_type_id == 5 || $parent_id==5) ? '1' : '0',
                    'is_igst_included' => $igst_status,
                    'is_cess_included' => $cess_status,
                    'terms_and_conditions' => $terms_and_conditions,
                    'billing_first_name' => $getAddress->billing_first_name,
                    'billing_address' => $getAddress->billing_address,
                    'billing_city' => $getAddress->billing_city,
                    'billing_zip' => $getAddress->billing_zip,
                    'billing_state' => $getAddress->billing_state,
                    'billing_country' => $getAddress->billing_country,
                    'shipping_first_name' => $getAddress->shipping_first_name,
                    'shipping_address' => $getAddress->shipping_address,
                    'shipping_city' => $getAddress->shipping_city,
                    'shipping_zip' => $getAddress->shipping_zip,
                    'shipping_state' => $getAddress->shipping_state,
                    'shipping_country' => $getAddress->shipping_country
                );
                
                $orderId = $this->custominventorymodel->setOrder($order);
                
                 //despatch detaild
                if($despatch_through != '' || $motor_vehicle_no != ''  ){
                    despatchDetails($entryId);
                }
                
                

                // For Order Details
                if (count($product_id) > 0) {
                    $productDetails = array();

                    for ($j = 0; $j < count($product_id); $j++) {
                        $productDetails[$j]['branch_id'] = $branch_id;
                        $productDetails[$j]['order_id'] = $orderId;
                        $productDetails[$j]['product_id'] = $product_id[$j];
                        $productDetails[$j]['product_description'] = $product_description[$j];
                        $productDetails[$j]['stock_id'] = $stock_id[$j];
                        $productDetails[$j]['quantity'] = $product_quantity[$j];
                        $productDetails[$j]['original_price'] = $product_price[$j];
                        $productDetails[$j]['base_price'] = $product_price[$j];
                        $productDetails[$j]['discount_percentage'] = $product_discount[$j];
                        $productDetails[$j]['discount_amount'] = (($product_quantity[$j] * $product_price[$j]) - $gross_total_price_per_prod[$j]);
                        $productDetails[$j]['price'] = $gross_total_price_per_prod[$j];
                        $productDetails[$j]['igst_tax_percent'] = ($igst_tax_percent[$j]) ? $igst_tax_percent[$j] : 0;
                        $productDetails[$j]['igst_tax'] = ($igst_tax_value[$j]) ? $igst_tax_value[$j] : 0;
                        $productDetails[$j]['cgst_tax_percent'] = ($cgst_tax_percent[$j]) ? $cgst_tax_percent[$j] : 0;
                        $productDetails[$j]['cgst_tax'] = ($cgst_tax_value[$j]) ? $cgst_tax_value[$j] : 0;
                        $productDetails[$j]['sgst_tax_percent'] = ($sgst_tax_percent[$j]) ? $sgst_tax_percent[$j] : 0;
                        $productDetails[$j]['sgst_tax'] = ($sgst_tax_value[$j]) ? $sgst_tax_value[$j] : 0;
                        $productDetails[$j]['cess_tax_percent'] = ($cess_tax_percent[$j]) ? $cess_tax_percent[$j] : 0;
                        $productDetails[$j]['cess_tax'] = ($cess_tax_value[$j]) ? $cess_tax_value[$j] : 0;
                        $productDetails[$j]['total'] = $total_price_per_prod_with_tax[$j];
                        $productDetails[$j]['creation_date'] = $created_date;
                        $productDetails[$j]['flow_type'] = ($entry_type_id == 5 || $parent_id==5) ? '66' : '55';
                        $productDetails[$j]['order_type'] = ($entry_type_id == 5 || $parent_id==5) ? '1' : '2';
                    }
                    $this->custominventorymodel->insertOrderDetails($productDetails);
                }

//                if ($entry_id) {
//                    $res = $this->inventorymodel->deleteRequestTransaction($entry_id);
//                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'save_err';
                    $data_msg['message'] = "There was an error submitting the form";
                } else {
                    $this->db->trans_commit();
                    //mail
                    $this->load->helper('email');
                    $company_details = $this->inventorymodel->getCompanyDetails();
                    $voucher = $entry_type['type'];
                    $message = "Your " . $voucher . " transaction added successfully. " . $voucher . " number is " . $entry_number;
                    $company_mail_data = array($company_details->company_name, $message);
                    sendMail($template = 'transaction', $slug = 'transaction_mail', $to = $company_details->email, $company_mail_data);
                    $ledger_contact_details = $this->inventorymodel->getLedgerContactDetails($tr_ledger_id[0]);
                    if ($ledger_contact_details) {
                        //mail   
                        $ledger_mail_data = array($ledger_contact_details->company_name, $message);
                        sendMail($template = 'transaction', $slug = 'transaction_mail', $to = $ledger_contact_details->email, $ledger_mail_data);
                        //end mail 
                    }
                    //end mail
                    $data_msg['res'] = 'success';
                    $data_msg['type'] = 'order';
                    $print_url = base_url('transaction/invoice') . '.aspx/' . $entryId . '/' . $entry_type_id;
                    $data_msg['redirect_url'] =base_url('admin/inventory-transaction-list'). "/" .  str_replace(' ','-',strtolower(trim($entry_type['type']))).'/'.$entry_type['id'].'/all';
                    $data_msg['print_url'] = $print_url;
                    $data_msg['message'] = "Transaction added successfully. Entry number #" . $entry_number;
                }
            } else {
                $data_msg['res'] = 'error';
                $errors = $this->form_validation->error_array();
                $data_msg['message'] = current($errors);
            }
        }

        echo json_encode($data_msg);
    }

    //credit note
    public function ajax_add_cr_dr_note() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $type_service_product = $this->input->post('type_service_product');
            $user_id = $this->session->userdata('admin_uid');
            $branch_id = $this->session->userdata('branch_id');
            $entry_number = $this->input->post('entry_number');
            $date_form = $this->input->post('date_form');
            $account_type = $this->input->post('tr_type');
            $credit_days = $this->input->post('credit_days');
            $product_grand_total = $this->input->post('product_grand_total');
            $ledger_name = $this->input->post('tr_ledger');
            $netTotal = $this->input->post('netTotal');
            $discount_percent_hidden = $this->input->post('discount_percent_hidden');
            $discount_value_hidden = $this->input->post('discount_value_hidden');

            $narration = $this->input->post('notes');

            $tr_ledger_id = $this->input->post('tr_ledger_id');
            $dataTracking = isset($_POST['tracking']) ? $_POST['tracking'] : null;

            //For Product 
            $product_id = $this->input->post('product_id');
            $stock_id = $this->input->post('stock_id');
            $product_name = $this->input->post('product_name');
            $product_description = $this->input->post('product_description');//19042018
            $product_quantity = $this->input->post('product_quantity');
            $product_price = $this->input->post('product_price');
            $tax_percent = $this->input->post('tax_percent');
            $tax_value = $this->input->post('tax_value');
            //GST
            $igst_tax_percent = $this->input->post('igst_tax_percent');
            $igst_tax_value = $this->input->post('igst_tax_value');

            $sgst_tax_percent = $this->input->post('sgst_tax_percent');
            $sgst_tax_value = $this->input->post('sgst_tax_value');

            $cgst_tax_percent = $this->input->post('cgst_tax_percent');
            $cgst_tax_value = $this->input->post('cgst_tax_value');

            $cess_status_col = $this->input->post('cess_status_col');
            $igst_status = $this->input->post('igst_status');

            $cess_tax_percent = $this->input->post('cess_percent');
            $cess_tax_value = $this->input->post('cess_value');
            
            //Shiping Address ID
            $shipping_id = $this->input->post('shipping_id');
            //bank details add in invoice
            $bank_id = $this->input->post('bank_id');
            
            //godown and batch =============
            $batchGodown = isset($_POST['batchGodown']) ? $_POST['batchGodown'] : null;
            $godown_status = $this->input->post('godown_status');
            $batch_status = $this->input->post('batch_status');
            

            //GST
            $total_price_per_prod = $this->input->post('total_price_per_prod');
            $gross_total_price_per_prod = $this->input->post('gross_total_price_per_prod');
            $total_price_per_prod_with_tax = $this->input->post('total_price_per_prod_with_tax');
            $entry_id = $this->input->post('entry_id');


            $count = count($tr_ledger_id);
            $terms_and_conditions = $this->input->post('terms_and_conditions');
            $voucher_no = $this->input->post('voucher_no');
            $voucher_date = $this->input->post('voucher_date');
            $voucher_date = date("Y-m-d", strtotime(str_replace('/', '-', $voucher_date)));
            $entry_type_id = $this->input->post('entry_type');
            $parent_id = $this->input->post('parent_id');
             $entry_type = $this->entry->getEntryTypeById($entry_type_id);
            $created_date = date("Y-m-d", strtotime(str_replace('/', '-', $date_form)));
            if ($entry_type_id == 12) {
                $this->form_validation->set_rules('tr_ledger[0]', 'Creditors', 'required');
                $this->form_validation->set_rules('tr_ledger[1]', 'Purchase', 'required');
            } else if($entry_type_id == 14) {
                $this->form_validation->set_rules('tr_ledger[0]', 'Debtors', 'required');
                $this->form_validation->set_rules('tr_ledger[1]', 'Sales', 'required');
            }
            if ($entry_number != 'Auto' || $entry_number != null || $entry_number != '') {
                $this->form_validation->set_rules('entry_number', 'Entry Number', 'required|callback_alpha_dash_space');
            }


            $this->form_validation->set_rules('date_form', 'Transaction date', 'required');

            if ($this->form_validation->run() === TRUE) {
          
                //generate auto entry no
                if ($entry_number == 'Auto' || $entry_number == null || $entry_number == '') {
                    //$entry_type_id= $this->input->post('entry_type_id');

                   
                    $countid = 1;
                    $today = date("Y-m-d H:i:s");
                    $auto_number = $this->entry->getNoOfByTypeId($entry_type_id,$parent_id, $today, $entry_type['strating_date']);
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

                $order = array();
                $entry = array();
                $new_array = array();
                $bill_data = array();
                //calculate tax
                $igst_value = 0;
                $cgst_value = 0;
                $sgst_value = 0;
                if ($igst_status == 1) {
                    foreach ($igst_tax_value as $igst) {
                        $igst_value+=$igst;
                    }
                } else {
                    foreach ($cgst_tax_value as $cgst) {
                        $cgst_value+=$cgst;
                    }
                    foreach ($sgst_tax_value as $sgst) {
                        $sgst_value+=$sgst;
                    }
                }
                $cess_status = 0;
                $cess_value = 0;
                foreach ($cess_status_col as $k => $cess) {
                    if ($cess == 1) {
                        $cess_status = 1;
                        $cess_value+=$cess_tax_value[$k];
                    }
                }
                if ($entry_type_id == 12 || $parent_id==12) {
                    $gst_type = 'Cr';
                    if ($igst_status == 1) {
                        $igst_ledger_id = 31;
                    } else {
                        $cgst_ledger_id = 33;
                        $sgst_ledger_id = 35;
                    }
                    $cess_type = 'Cr';
                    $cess_ledger_id = 37;
                } else if ($entry_type_id == 14 || $parent_id==14) {
                    $gst_type = 'Dr';
                    if ($igst_status == 1) {
                        $igst_ledger_id = 32;
                    } else {
                        $cgst_ledger_id = 34;
                        $sgst_ledger_id = 36;
                    }
                    $cess_type = 'Dr';
                    $cess_ledger_id = 38;
                }
                //end calculate GST

                $sub_voucher = 0;
                $is_inventry = 1;
                $order_id = "";
                $discount_sum = 0;
                $product_grand_total = $netTotal; //16/02/2018
                $debtors_amount = $product_grand_total;
                for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
                    $discount_sum+=$discount_value_hidden[$i];
                }
                if ($entry_type_id == 12 || $parent_id==12) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                } else if ($entry_type_id == 14 || $parent_id==14) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                }

                $isDataNewRef = 1;

                // $bill_data = array();

                $baseCurrency = $this->entry->getDefoultCurrency();
                if ($baseCurrency) {
                    $currency_unit = $this->entry->getCurrencyUnitById($baseCurrency['base_currency']);
                }


                $base_unit = $currency_unit['unit_price'];
                $selected_currency = $currency_unit['id'];

//                for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
//                    if ($account_type[$i] == 'Cr') {
//                        if ($entry_type_id == 12) {
//                            $ledger_value += $discount_value_hidden[$i];
//                        } else if ($entry_type_id == 14) {
//                            $ledger_value -= $discount_value_hidden[$i];
//                        }
//                    }
//                    if (!empty($discount_value_hidden[$i])) {
//                        if ($account_type[$i] == 'Dr') {
//                            if ($entry_type_id == 12) {
//                                $debtors_amount -= $discount_value_hidden[$i];
//                            } else if ($entry_type_id == 14) {
//                                $debtors_amount += $discount_value_hidden[$i];
//                            }
//                        }
//                        if ($account_type[$i] == 'Cr') {
//                            if ($entry_type_id == 12) {
//                                $debtors_amount += $discount_value_hidden[$i];
//                            } else if ($entry_type_id == 14) {
//                                $debtors_amount -= $discount_value_hidden[$i];
//                            }
//                        }
//                    }
//                }



                for ($i = 0; $i < (count($ledger_name) - 1); $i++) {

                    $new_array[$account_type[$i]][] = $ledger_name[$i];
                }

                $ledger_name_json = json_encode($new_array);
                $sum_dr = $debtors_amount + $discount_sum;
                $sum_cr = $sales_amount + $tax_value;

                // For Entry Table
                $entry = array(
                    'entry_no' => $entry_number,
                    'create_date' => $created_date,
                    'ledger_ids_by_accounts' => $ledger_name_json,
                    'dr_amount' => ($entry_type_id == 12 || $parent_id==12) ? ($sum_dr * $base_unit) : ($sum_dr * $base_unit),
                    'cr_amount' => ($entry_type_id == 12 || $parent_id==12) ? ($sum_cr * $base_unit) : ($sum_cr * $base_unit),
                    'unit_price_dr' => ($entry_type_id == 12 || $parent_id==12) ? $sum_dr : $sum_dr,
                    'unit_price_cr' => ($entry_type_id == 12 || $parent_id==12) ? $sum_cr : $sum_cr,
                    'entry_type_id' => ($entry_type['parent_id']==0)?$entry_type_id:$entry_type['parent_id'],
                    'sub_voucher' => ($entry_type['parent_id']!=0)?$entry_type_id:$entry_type['parent_id'],
                    'user_id' => $user_id,
                    'company_id' => $branch_id,
                    'is_inventry' => $is_inventry,
                    'narration' => $narration,
                    'voucher_no' => $voucher_no,
                    'voucher_date' => $voucher_date,
                    'is_service_product' => ($type_service_product == 1) ? 1 : 0,
                    'order_id' => $order_id,
                    'bank_id' => (isset($bank_id) && $bank_id != '') ? $bank_id : 0
                );

                $this->db->trans_begin();

                $entryId = $this->custommodel->setEntry($entry);
                // For Ladger Account Details Table
                $ledgerDetails = array();
                $balance = 0;

                $index = 0;
                // debtors
                $ledgerDetails[0]['branch_id'] = $branch_id;
                $ledgerDetails[0]['account'] = $account_type[0];
                $ledgerDetails[0]['balance'] = ($debtors_amount * $base_unit);
                $ledgerDetails[0]['entry_id'] = $entryId;
                $ledgerDetails[0]['ladger_id'] = $tr_ledger_id[0];
                $ledgerDetails[0]['create_date'] = $created_date;
                $ledgerDetails[0]['narration'] = $narration;
                $ledgerDetails[0]['selected_currency'] = $selected_currency;
                $ledgerDetails[0]['unit_price'] = $base_unit;
                $ledgerDetails[0]['discount_type'] = 0;
                $ledgerDetails[0]['discount_amount'] = '';
                $ledgerDetails[0]['deleted'] = 0;
                // sales
                $ledgerDetails[1]['branch_id'] = $branch_id;
                $ledgerDetails[1]['account'] = $account_type[1];
                $ledgerDetails[1]['balance'] = ($sales_amount * $base_unit);
                $ledgerDetails[1]['entry_id'] = $entryId;
                $ledgerDetails[1]['ladger_id'] = $tr_ledger_id[1];
                $ledgerDetails[1]['create_date'] = $created_date;
                $ledgerDetails[1]['narration'] = $narration;
                $ledgerDetails[1]['selected_currency'] = $selected_currency;
                $ledgerDetails[1]['unit_price'] = $base_unit;
                $ledgerDetails[1]['discount_type'] = 0;
                $ledgerDetails[1]['discount_amount'] = '';
                $ledgerDetails[1]['deleted'] = 0;

                // GST tax
                if ($igst_status == 1) {
                    //IGST  
                    $ledgerDetails[2]['branch_id'] = $branch_id;
                    $ledgerDetails[2]['account'] = $gst_type;
                    $ledgerDetails[2]['balance'] = ($igst_value * $base_unit);
                    $ledgerDetails[2]['entry_id'] = $entryId;
                    $ledgerDetails[2]['ladger_id'] = $igst_ledger_id;
                    $ledgerDetails[2]['create_date'] = $created_date;
                    $ledgerDetails[2]['narration'] = $narration;
                    $ledgerDetails[2]['selected_currency'] = $selected_currency;
                    $ledgerDetails[2]['unit_price'] = $base_unit;
                    $ledgerDetails[2]['discount_type'] = 0;
                    $ledgerDetails[2]['discount_amount'] = '';
                    $ledgerDetails[2]['deleted'] = 0;
                } else {
                    //CGST 
                    $ledgerDetails[2]['branch_id'] = $branch_id;
                    $ledgerDetails[2]['account'] = $gst_type;
                    $ledgerDetails[2]['balance'] = ($cgst_value * $base_unit);
                    $ledgerDetails[2]['entry_id'] = $entryId;
                    $ledgerDetails[2]['ladger_id'] = $cgst_ledger_id;
                    $ledgerDetails[2]['create_date'] = $created_date;
                    $ledgerDetails[2]['narration'] = $narration;
                    $ledgerDetails[2]['selected_currency'] = $selected_currency;
                    $ledgerDetails[2]['unit_price'] = $base_unit;
                    $ledgerDetails[2]['discount_type'] = 0;
                    $ledgerDetails[2]['discount_amount'] = '';
                    $ledgerDetails[2]['deleted'] = 0;
                    //SGST
                    $ledgerDetails[3]['branch_id'] = $branch_id;
                    $ledgerDetails[3]['account'] = $gst_type;
                    $ledgerDetails[3]['balance'] = ($sgst_value * $base_unit);
                    $ledgerDetails[3]['entry_id'] = $entryId;
                    $ledgerDetails[3]['ladger_id'] = $sgst_ledger_id;
                    $ledgerDetails[3]['create_date'] = $created_date;
                    $ledgerDetails[3]['narration'] = $narration;
                    $ledgerDetails[3]['selected_currency'] = $selected_currency;
                    $ledgerDetails[3]['unit_price'] = $base_unit;
                    $ledgerDetails[3]['discount_type'] = 0;
                    $ledgerDetails[3]['discount_amount'] = '';
                    $ledgerDetails[3]['deleted'] = 0;
                }
                //CESS Tax
                if ($igst_status == 1) {
                    $index = 2;
                } else {
                    $index = 3;
                }
                if ($cess_status == 1) {
                    $index = $index + 1;
                    $ledgerDetails[$index]['branch_id'] = $branch_id;
                    $ledgerDetails[$index]['account'] = $gst_type;
                    $ledgerDetails[$index]['balance'] = ($cess_value * $base_unit);
                    $ledgerDetails[$index]['entry_id'] = $entryId;
                    $ledgerDetails[$index]['ladger_id'] = $cess_ledger_id;
                    $ledgerDetails[$index]['create_date'] = $created_date;
                    $ledgerDetails[$index]['narration'] = $narration;
                    $ledgerDetails[$index]['selected_currency'] = $selected_currency;
                    $ledgerDetails[$index]['unit_price'] = $base_unit;
                    $ledgerDetails[$index]['discount_type'] = 0;
                    $ledgerDetails[$index]['discount_amount'] = '';
                    $ledgerDetails[$index]['deleted'] = 0;
                }
                if (isset($discount_value_hidden) && count($discount_value_hidden) > 1) {
                    for ($i = 1; $i <= count($discount_value_hidden) - 1; $i++) {
                        $index++;
                        $j = $i + 1;
                        if (!empty($discount_value_hidden[$i - 1])) {
                            $balance = $discount_value_hidden[$i - 1];
                        }
                        $ledgerDetails[$index]['branch_id'] = $branch_id;
                        $ledgerDetails[$index]['account'] = $account_type[$j];
                        $ledgerDetails[$index]['balance'] = ($balance * $base_unit);
                        $ledgerDetails[$index]['entry_id'] = $entryId;
                        $ledgerDetails[$index]['ladger_id'] = $tr_ledger_id[$j];
                        $ledgerDetails[$index]['create_date'] = $created_date;
                        $ledgerDetails[$index]['narration'] = $narration;
                        $ledgerDetails[$index]['selected_currency'] = $selected_currency;
                        $ledgerDetails[$index]['unit_price'] = $base_unit;
                        $ledgerDetails[$index]['discount_type'] = 1;
                        $ledgerDetails[$index]['discount_amount'] = $discount_percent_hidden[$i - 1];
                        $ledgerDetails[$index]['deleted'] = 0;
                    }
                }


                $this->custommodel->setEntryDetails($ledgerDetails);
                
                //Closing Balance update 03052018
                $financial_year = get_financial_year();
                $from_date = date("Y-m-d", strtotime(current($financial_year)));
                $to_date = date("Y-m-t", strtotime(end($financial_year)));
                foreach($ledgerDetails AS $ledger_id){
                    $ledger_detail = $this->inventorymodel->getLedgerByLedgerIdByDate($ledger_id['ladger_id'],$from_date,$to_date,$branch_id);
                    $where= array(
                        'branch_id'=>$branch_id,
                        'entry_id'=>0,
                        'ladger_id'=>$ledger_id['ladger_id']
                    );
                    $closingValue['current_closing_balance'] = ($ledger_detail['account_type'] == 'Dr')?$ledger_detail['dr_balance'] - $ledger_detail['cr_balance'] + $ledger_detail['opening_balance']:$ledger_detail['cr_balance'] - $ledger_detail['dr_balance'] + $ledger_detail['opening_balance'];
                    $this->inventorymodel->updateClosingBalance($where,$closingValue);
                }

                // For Billwise Details Auto submission (without popup)
                // For Order
                $order = array(
                    'branch_id' => $branch_id,
                    'users_id' => $tr_ledger_id[0],
                    'total' => ($product_grand_total * $base_unit),
                    'order_date' => $created_date,
                    'creation_date' => $created_date,
                    'tax_amount' => ($tax_value * $base_unit),
                    'entry_id' => $entryId,
                    'grand_total' => ($product_grand_total * $base_unit),
                    'currency_code' => $selected_currency,
                    'order_type' => ($entry_type_id == 14 || $parent_id==14) ? '7' : '8',
                    'flow_type' => ($entry_type_id == 12 || $parent_id==12) ? '1' : '0',
                    'is_igst_included' => $igst_status,
                    'is_cess_included' => $cess_status,
                );
                if ($type_service_product != 1) {
                    $orderId = $this->custominventorymodel->setOrder($order);
                } else {
                    $orderId = $this->custominventorymodel->setTempOrder($order);
                }
                
                $table = 'orders';
                $this->setOrderAddressDetails($orderId,$tr_ledger_id[0],$shipping_id,$table,$isDataNewRef);

                // For Order Details
                if (count($product_id) > 0) {
                    $productDetails = array();
                    $godown_array = array();

                    for ($j = 0; $j < count($product_id); $j++) {
                        $productDetails[$j]['branch_id'] = $branch_id;
                        $productDetails[$j]['order_id'] = $orderId;
                        $productDetails[$j]['product_id'] = $product_id[$j];
                        $productDetails[$j]['product_description'] = $product_description[$j];//19042018
                        $productDetails[$j]['stock_id'] = $stock_id[$j];
                        $productDetails[$j]['quantity'] = $product_quantity[$j];
                        $productDetails[$j]['original_price'] = $product_price[$j];
                        $productDetails[$j]['base_price'] = $product_price[$j];
                        $productDetails[$j]['price'] = $gross_total_price_per_prod[$j];
                        $productDetails[$j]['igst_tax_percent'] = ($igst_tax_percent[$j]) ? $igst_tax_percent[$j] : 0;
                        $productDetails[$j]['igst_tax'] = ($igst_tax_value[$j]) ? $igst_tax_value[$j] : 0;
                        $productDetails[$j]['cgst_tax_percent'] = ($cgst_tax_percent[$j]) ? $cgst_tax_percent[$j] : 0;
                        $productDetails[$j]['cgst_tax'] = ($cgst_tax_value[$j]) ? $cgst_tax_value[$j] : 0;
                        $productDetails[$j]['sgst_tax_percent'] = ($sgst_tax_percent[$j]) ? $sgst_tax_percent[$j] : 0;
                        $productDetails[$j]['sgst_tax'] = ($sgst_tax_value[$j]) ? $sgst_tax_value[$j] : 0;
                        $productDetails[$j]['cess_tax_percent'] = ($cess_tax_percent[$j]) ? $cess_tax_percent[$j] : 0;
                        $productDetails[$j]['cess_tax'] = ($cess_tax_value[$j]) ? $cess_tax_value[$j] : 0;
                        $productDetails[$j]['total'] = $total_price_per_prod_with_tax[$j];
                        $productDetails[$j]['creation_date'] = $created_date;
                        $productDetails[$j]['flow_type'] = ($entry_type_id == 12 || $parent_id==12) ? '66' : '55';
                        $productDetails[$j]['order_type'] = ($entry_type_id == 14 || $parent_id==14) ? '7' : '8';
                        
                        //For primary godown Godown if godown status == 0 and batch status == 0 
                         if($batch_status == 0 && $godown_status == 0){
                            $godown_array[$j]['entry_id'] = $entryId;
                            $godown_array[$j]['stock_id'] = $orderId;
                            $godown_array[$j]['godown_id'] = 1;
                            $godown_array[$j]['godown_name'] = 'Local Store';
                            $godown_array[$j]['product_id'] = $product_id[$j];
                            $godown_flow_type = ($entry_type_id == 12 || $parent_id==12 ) ? 'quantity_out' : 'quantity_in';
                            $godown_array[$j][$godown_flow_type] = $product_quantity[$j];
                            $godown_array[$j]['transaction_type'] = ($entry_type_id == 14 || $parent_id==14) ? '7' : '8';
                         }
                    }
                    if ($type_service_product != 1) {
                        $this->custominventorymodel->insertOrderDetails($productDetails);
                        //For primary godown Godown if godown status == 0 and batch status == 0 
                        if($batch_status == 0 && $godown_status == 0){
                            $this->inventorymodel->insertGodown($godown_array);
                        }
                    } else {
                        $this->custominventorymodel->insertTempOrderDetails($productDetails);
                    }
                }
                
                
                //For Batch AND Godown
                if (count($batchGodown) > 0) {
                    if(($godown_status == 0 && $batch_status == 1) || ($godown_status == 1 && $batch_status == 1) || ($godown_status == 1 && $batch_status == 0)){
//                        $batch_array = array();
//                        $godown_array = array();
//                        $b = 0;
//                        $g = 0;
                        for ($k = 0; $k < count($batchGodown); $k++) {
                            $batch_array = array();
                            $godown_array = array();
                            $b = 0;
                            $g = 0;
                            $batchValue = json_decode($batchGodown[$k]['value']);
                            for ($j = 0; $j < count($batchValue); $j++) {
                                if($batchValue[$j]->productBatchStatus == 1 && $batch_status == 1){
                                    // For bathch
                                    $manufact_date = date("Y-m-d", strtotime(str_replace('/', '-', $batchValue[$j]->manufact_date)));
                                    if ($batchValue[$j]->exp_type_id == 1) {
                                        $expiry_date = date('Y-m-d', strtotime($batchValue[$j]->exp_days));
                                    } else {
                                        $expiry_date = date('Y-m-d', strtotime("+".$batchValue[$j]->exp_days." " . $batchValue[$j]->exp_type, strtotime($manufact_date)));
                                    }

                                    $batch_array[$b]['branch_id'] = $branch_id;
                                    $batch_array[$b]['stock_id'] = $orderId;
                                    $batch_array[$b]['godown_id'] = ($batchValue[$j]->batch_godown_id)?($batchValue[$j]->batch_godown_id):1;
                                    $batch_array[$b]['product_id'] = $batchGodown[$k]['product_id'];
                                    $batch_array[$b]['batch_no'] = $batchValue[$j]->batch_no;
                                    $batch_array[$b]['parent_id'] = ($entry_type_id == 12 || $parent_id==12 ) ? $batchValue[$j]->batch_id : 0;
                                    $batch_array[$b]['in_out'] = ($entry_type_id == 12 || $parent_id==12 ) ? 2 : 1;
                                    $batch_array[$b]['quantity'] = $batchValue[$j]->batch_qty;
                                    $batch_array[$b]['rate'] = $batchValue[$j]->batch_rate;
                                    $batch_array[$b]['value'] = $batchValue[$j]->batch_value;
                                    $batch_array[$b]['exp_type'] = $batchValue[$j]->exp_type_id;
                                    $batch_array[$b]['exp_days_given'] = $batchValue[$j]->exp_days;
                                    $batch_array[$b]['manufact_date'] = $manufact_date;
                                    $batch_array[$b]['exp_date'] = $expiry_date;
                                    $batch_array[$b]['transaction_type'] = ($entry_type_id == 14 || $parent_id==14) ? '7' : '8';
                                    $b++;
                                }
                                // For godown 
                                $godown_array[$g]['entry_id'] = $entryId;
                                $godown_array[$g]['stock_id'] = $orderId;
                                $godown_array[$g]['godown_id'] = ($batchValue[$j]->batch_godown_id)?$batchValue[$j]->batch_godown_id:1;
                                $godown_array[$g]['godown_name'] = ($batchValue[$j]->batch_godown_name)?$batchValue[$j]->batch_godown_name:'Local Store';
                                $godown_array[$g]['product_id'] = $batchGodown[$k]['product_id'];
                                $godown_flow_type = ($entry_type_id == 12 || $parent_id==12 ) ? 'quantity_out' : 'quantity_in';
                                $godown_array[$g][$godown_flow_type] = ($batchValue[$j]->productBatchStatus == 1 && $batch_status == 1)?$batchValue[$j]->batch_qty:$batchValue[$j]->godown_qty;
                                $godown_array[$g]['transaction_type'] = ($entry_type_id == 14 || $parent_id==14) ? '7' : '8';
                                $g++;

                            }
                            
                            if(count($batch_array) > 0){
                                $this->inventorymodel->insertBatch($batch_array);
                            }
                            $this->inventorymodel->insertGodown($godown_array);
                        }
//                        if(count($batch_array) > 0){
//                            $this->inventorymodel->insertBatch($batch_array);
//                        }
//                        $this->inventorymodel->insertGodown($godown_array);
                    }
                }
                

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'save_err';
                    $data_msg['message'] = "There was an error submitting the form";
                } else {
                    $this->db->trans_commit();
                    //mail
                    $this->load->helper('email');
                    $company_details = $this->inventorymodel->getCompanyDetails();
                    $voucher = $entry_type['type'];
                    $message = "Your " . $voucher . " transaction added successfully. " . $voucher . " number is " . $entry_number;
                    $company_mail_data = array($company_details->company_name, $message);
                    sendMail($template = 'transaction', $slug = 'transaction_mail', $to = $company_details->email, $company_mail_data);
                    $ledger_contact_details = $this->inventorymodel->getLedgerContactDetails($tr_ledger_id[0]);
                    if ($ledger_contact_details) {
                        //mail   
                        $ledger_mail_data = array($ledger_contact_details->company_name, $message);
                        sendMail($template = 'transaction', $slug = 'transaction_mail', $to = $ledger_contact_details->email, $ledger_mail_data);
                        //end mail 
                    }
                    //end mail
                    $data_msg['res'] = 'success';
                    $print_url = base_url('transaction/invoice') . '.aspx/' . $entryId . '/' . $entry_type_id;
                   $data_msg['redirect_url'] = base_url('admin/inventory-transaction-list'). "/" .  str_replace(' ','-',strtolower(trim($entry_type['type']))).'/'.$entry_type['id'].'/all';
                    
                    $data_msg['print_url'] = $print_url;
                    $data_msg['message'] = "Transaction added successfully. Entry number #" . $entry_number;
                }
            } else {
                $data_msg['res'] = 'error';
                // $data_msg['message'] = $this->form_validation->error_array();
                $data_msg['message'] = validation_errors();
            }
        }

        echo json_encode($data_msg);
    }

    public function ajax_note_add_final() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $recurring_freq = $this->input->post('recurring_freq');
            $postdated = $this->input->post('postdated');
            $user_id = $this->session->userdata('admin_uid');
            $entry_number = $this->input->post('entry_number');
            $date_form = $this->input->post('date_form');
            $account_type = $this->input->post('tr_type');
            $credit_days = $this->input->post('credit_days');
            $product_grand_total = $this->input->post('product_grand_total');
            $ledger_name = $this->input->post('tr_ledger');
            $netTotal = $this->input->post('netTotal');
            $discount_value_hidden = $this->input->post('discount_value_hidden');
            $discount_percent_hidden = $this->input->post('discount_percent_hidden');
            $tax_value = $this->input->post('tax_value');
            $narration = $this->input->post('notes');

            $tr_ledger_id = $this->input->post('tr_ledger_id');
            $dataTracking = isset($_POST['tracking']) ? $_POST['tracking'] : null;
            
            //Branch id
            $branch_id = $this->session->userdata('branch_id');

            //For Product 
            $product_id = $this->input->post('product_id');
            $stock_id = $this->input->post('stock_id');
            $product_name = $this->input->post('product_name');
            $product_description = $this->input->post('product_description');//19042018
            $product_quantity = $this->input->post('product_quantity');
            $product_price = $this->input->post('product_price');
            $tax_percent = $this->input->post('tax_percent');
            $tax_value = $this->input->post('tax_value');
            //GST
            $igst_tax_percent = $this->input->post('igst_tax_percent');
            $igst_tax_value = $this->input->post('igst_tax_value');

            $sgst_tax_percent = $this->input->post('sgst_tax_percent');
            $sgst_tax_value = $this->input->post('sgst_tax_value');

            $cgst_tax_percent = $this->input->post('cgst_tax_percent');
            $cgst_tax_value = $this->input->post('cgst_tax_value');

            $cess_status_col = $this->input->post('cess_status_col');
            $igst_status = $this->input->post('igst_status');

            $cess_tax_percent = $this->input->post('cess_percent');
            $cess_tax_value = $this->input->post('cess_value');
            
            //bank details add in invoice
            $bank_id = $this->input->post('bank_id');


            //GST
            $total_price_per_prod = $this->input->post('total_price_per_prod');
            $gross_total_price_per_prod = $this->input->post('gross_total_price_per_prod');
            $total_price_per_prod_with_tax = $this->input->post('total_price_per_prod_with_tax');
            $entry_id = $this->input->post('entry_id');
            
            //godown and batch =============
            $batchGodown = isset($_POST['batchGodown']) ? $_POST['batchGodown'] : null;
            $godown_status = $this->input->post('godown_status');
            $batch_status = $this->input->post('batch_status');

            $count = count($tr_ledger_id);

            $entry_type_id = $this->input->post('entry_type');
            $parent_id = $this->input->post('parent_id');
            $voucher_no = $this->input->post('voucher_no');
            $voucher_date = $this->input->post('voucher_date');
            $voucher_date = date("Y-m-d", strtotime(str_replace('/', '-', $voucher_date)));
            $deleted = ($postdated == 1) ? '2' : '0';
            $order_deleted = ($postdated == 1) ? '2' : '1';
            $order_type = ($entry_type_id == 10 || $parent_id==10) ? 6 : 5;
            $entry_type_id = ($entry_type_id == 10 || $parent_id==10) ? 5 : 6;
            $entry_type = $this->inventorymodel->getEntryTypeById($entry_type_id);
            $created_date = date("Y-m-d", strtotime(str_replace('/', '-', $date_form)));
            if ($entry_number != 'Auto' || $entry_number != null || $entry_number != '') {
                $this->form_validation->set_rules('entry_number', 'Entry Number', 'required|callback_alpha_dash_space');
            }


            $this->form_validation->set_rules('date_form', 'Transaction date', 'required');

            if ($this->form_validation->run() === TRUE) {
                //generate auto entry no
                if ($entry_number == 'Auto' || $entry_number == null || $entry_number == '') {
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
                }

                $order = array();
                $entry = array();
                $new_array = array();
                $bill_data = array();
                //calculate tax
                $igst_value = 0;
                $cgst_value = 0;
                $sgst_value = 0;
                if ($igst_status == 1) {
                    foreach ($igst_tax_value as $igst) {
                        $igst_value+=$igst;
                    }
                } else {
                    foreach ($cgst_tax_value as $cgst) {
                        $cgst_value+=$cgst;
                    }
                    foreach ($sgst_tax_value as $sgst) {
                        $sgst_value+=$sgst;
                    }
                }
                $cess_status = 0;
                $cess_value = 0;
                foreach ($cess_status_col as $k => $cess) {
                    if ($cess == 1) {
                        $cess_status = 1;
                        $cess_value+=$cess_tax_value[$k];
                    }
                }
                if ($entry_type_id == 5 || $parent_id==5) {
                    $gst_type = 'Cr';
                    if ($igst_status == 1) {
                        $igst_ledger_id = 31;
                    } else {
                        $cgst_ledger_id = 33;
                        $sgst_ledger_id = 35;
                    }
                    $cess_type = 'Cr';
                    $cess_ledger_id = 37;
                } else if ($entry_type_id == 6 || $parent_id==6) {
                    $gst_type = 'Dr';
                    if ($igst_status == 1) {
                        $igst_ledger_id = 32;
                    } else {
                        $cgst_ledger_id = 34;
                        $sgst_ledger_id = 36;
                    }
                    $cess_type = 'Dr';
                    $cess_ledger_id = 38;
                }
                //end calculate GST
                $sub_voucher = 0;
                $is_inventry = 1;
                $order_id = "";
                $discount_sum = 0;
                $product_grand_total = $netTotal; //08/03/2018 @sudip
                $debtors_amount = $product_grand_total;

                for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
                    $discount_sum+=$discount_value_hidden[$i];
                }
                if ($entry_type_id == 5 || $parent_id==5) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                } else if ($entry_type_id == 6 || $parent_id==6) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                }

                $isDataNewRef = 1;

                // $bill_data = array();

                $baseCurrency = $this->entry->getDefoultCurrency();
                if ($baseCurrency) {
                    $currency_unit = $this->entry->getCurrencyUnitById($baseCurrency['base_currency']);
                }


                $base_unit = $currency_unit['unit_price'];
                $selected_currency = $currency_unit['id'];

//                for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
//                    if ($account_type[$i] == 'Cr') {
//                        if ($entry_type_id == 5) {
//                            $ledger_value += $discount_value_hidden[$i];
//                        } else if ($entry_type_id == 6) {
//                            $ledger_value -= $discount_value_hidden[$i];
//                        }
//                    }
//                    if (!empty($discount_value_hidden[$i])) {
//                        if ($account_type[$i] == 'Dr') {
//                            if ($entry_type_id == 5) {
//                                $debtors_amount -= $discount_value_hidden[$i];
//                            } else if ($entry_type_id == 6) {
//                                $debtors_amount += $discount_value_hidden[$i];
//                            }
//                        }
//                        if ($account_type[$i] == 'Cr') {
//                            if ($entry_type_id == 5) {
//                                $debtors_amount += $discount_value_hidden[$i];
//                            } else if ($entry_type_id == 6) {
//                                $debtors_amount -= $discount_value_hidden[$i];
//                            }
//                        }
//                    }
//                }

                if ($isDataNewRef == 1) {

                    // $newReferenceLedgerArray = $_POST['newReferenceLedgerArray'];
                    // for ($i = 0; $i < count($newReferenceLedgerArray); $i++) {

                    $ledgeId = $tr_ledger_id[0];

                    $hasBilling = $this->custommodel->getTBilling($ledgeId);
                    $hasBillingFinal = $hasBilling->bill_details_status;

                    if ($hasBillingFinal == "1") {

                        /* Fetch data */
                        $ledgeDet = $this->custommodel->getNewRefLedgerDetails($ledgeId);

                        $credit_date_get = $credit_days;
                        $credit_limit_get = $ledgeDet->credit_limit;




                        // if ($entry_type_id == 5) {
                        $getDiffDrCrBilling = $this->custommodel->getDiffDrCrBillingSales($ledgeId);
                        // } else if ($entry_type_id == 6) {
                        //     $getDiffDrCrBilling = $this->custommodel->getDiffDrCrBillingPurchase($ledgeId);
                        // }



                        $diff = $getDiffDrCrBilling->diff;

                        // if ($newReferenceLedgerArray[$i]["drAmt"] > 0) {
                        $total = $debtors_amount + $diff;
                        $amount = $debtors_amount;
                        // } else if ($newReferenceLedgerArray[$i]["crAmt"] > 0) {
                        //     $total = $newReferenceLedgerArray[$i]["crAmt"] + $diff;
                        //     $amount = $newReferenceLedgerArray[$i]["crAmt"];
                        // }




                        if ($entry_type_id == 5 || $parent_id==5) {
                            if ($total <= $credit_limit_get) {



                                $bill_data[0]['ledger_id'] = $tr_ledger_id[0];
                                $bill_data[0]['dr_cr'] = $account_type[0];
                                $bill_data[0]['ref_type'] = 'New Reference';
                                $bill_data[0]['bill_name'] = $entry_number;
                                $bill_data[0]['credit_days'] = $credit_date_get;
                                $bill_data[0]['credit_date'] = date('Y-m-d', strtotime("+" . $credit_date_get . " days"));
                                $bill_data[0]['bill_amount'] = ($amount * $base_unit);
                                $bill_data[0]['entry_id'] = "";
                                $bill_data[0]['created_date'] = $created_date;
                            } else {

                                $data_msg['res'] = 'save_err';
                                $data_msg['message'] = "Your credit limit has exceeded!";

                                echo json_encode($data_msg);
                                exit;
                            }
                        } else if ($entry_type_id == 6 || $parent_id==6) {
                            $bill_data[0]['ledger_id'] = $tr_ledger_id[0];
                            $bill_data[0]['dr_cr'] = $account_type[0];
                            $bill_data[0]['ref_type'] = 'New Reference';
                            $bill_data[0]['bill_name'] = $entry_number;
                            $bill_data[0]['credit_days'] = $credit_date_get;
                            $bill_data[0]['credit_date'] = date('Y-m-d', strtotime("+" . $credit_date_get . " days"));
                            $bill_data[0]['bill_amount'] = ($amount * $base_unit);
                            $bill_data[0]['entry_id'] = "";
                            $bill_data[0]['created_date'] = $created_date;
                        }
                    }
                    // }
                }

                for ($i = 0; $i < (count($ledger_name) - 1); $i++) {

                    $new_array[$account_type[$i]][] = $ledger_name[$i];
                }

                $ledger_name_json = json_encode($new_array);

                $sum_dr = $debtors_amount + $discount_sum;
                $sum_cr = $sales_amount + $tax_value;
                // For Entry Table
                $entry = array(
                    'entry_no' => $entry_number,
                    'create_date' => $created_date,
                    'ledger_ids_by_accounts' => $ledger_name_json,
                    'dr_amount' => ($entry_type_id == 5 || $parent_id==5) ? ($sum_dr * $base_unit) : ($sum_dr * $base_unit),
                    'cr_amount' => ($entry_type_id == 5 || $parent_id==5) ? ($sum_cr * $base_unit) : ($sum_cr * $base_unit),
                    'unit_price_dr' => ($entry_type_id == 5 || $parent_id==5) ? $sum_dr : $sum_dr,
                    'unit_price_cr' => ($entry_type_id == 5 || $parent_id==5) ? $sum_cr : $sum_cr,
                    'entry_type_id' => $entry_type_id,
                    'sub_voucher' => $sub_voucher,
                    'user_id' => $user_id,
                    'is_inventry' => $is_inventry,
                    'narration' => $narration,
                    'voucher_no' => $voucher_no,
                    'voucher_date' => $voucher_date,
                    'order_id' => $order_id,
                    'bank_id' => (isset($bank_id) && $bank_id != '') ? $bank_id : 0
                );

                $this->db->trans_begin();

                $entryId = $this->custommodel->setEntry($entry);

                // For Ladger Account Details Table
                $ledgerDetails = array();
                $balance = 0;

                $index = 0;

                // debtors
                $ledgerDetails[0]['branch_id'] = $branch_id;
                $ledgerDetails[0]['account'] = $account_type[0];
                $ledgerDetails[0]['balance'] = ($debtors_amount * $base_unit);
                $ledgerDetails[0]['entry_id'] = $entryId;
                $ledgerDetails[0]['ladger_id'] = $tr_ledger_id[0];
                $ledgerDetails[0]['create_date'] = $created_date;
                $ledgerDetails[0]['narration'] = $narration;
                $ledgerDetails[0]['selected_currency'] = $selected_currency;
                $ledgerDetails[0]['unit_price'] = $base_unit;
                $ledgerDetails[0]['discount_type'] = 0;
                $ledgerDetails[0]['discount_amount'] = '';
                $ledgerDetails[0]['deleted'] = $deleted;
                // sales
                $ledgerDetails[1]['branch_id'] = $branch_id;
                $ledgerDetails[1]['account'] = $account_type[1];
                $ledgerDetails[1]['balance'] = ($sales_amount * $base_unit);
                $ledgerDetails[1]['entry_id'] = $entryId;
                $ledgerDetails[1]['ladger_id'] = $tr_ledger_id[1];
                $ledgerDetails[1]['create_date'] = $created_date;
                $ledgerDetails[1]['narration'] = $narration;
                $ledgerDetails[1]['selected_currency'] = $selected_currency;
                $ledgerDetails[1]['unit_price'] = $base_unit;
                $ledgerDetails[1]['discount_type'] = 0;
                $ledgerDetails[1]['discount_amount'] = '';
                $ledgerDetails[1]['deleted'] = $deleted;

                // GST tax
                if ($igst_status == 1) {
                    //IGST   
                    $ledgerDetails[2]['branch_id'] = $branch_id;
                    $ledgerDetails[2]['account'] = $gst_type;
                    $ledgerDetails[2]['balance'] = ($igst_value * $base_unit);
                    $ledgerDetails[2]['entry_id'] = $entryId;
                    $ledgerDetails[2]['ladger_id'] = $igst_ledger_id;
                    $ledgerDetails[2]['create_date'] = $created_date;
                    $ledgerDetails[2]['narration'] = $narration;
                    $ledgerDetails[2]['selected_currency'] = $selected_currency;
                    $ledgerDetails[2]['unit_price'] = $base_unit;
                    $ledgerDetails[2]['discount_type'] = 0;
                    $ledgerDetails[2]['discount_amount'] = '';
                    $ledgerDetails[2]['deleted'] = $deleted;
                } else {
                    //CGST   
                    $ledgerDetails[2]['branch_id'] = $branch_id;
                    $ledgerDetails[2]['account'] = $gst_type;
                    $ledgerDetails[2]['balance'] = ($cgst_value * $base_unit);
                    $ledgerDetails[2]['entry_id'] = $entryId;
                    $ledgerDetails[2]['ladger_id'] = $cgst_ledger_id;
                    $ledgerDetails[2]['create_date'] = $created_date;
                    $ledgerDetails[2]['narration'] = $narration;
                    $ledgerDetails[2]['selected_currency'] = $selected_currency;
                    $ledgerDetails[2]['unit_price'] = $base_unit;
                    $ledgerDetails[2]['discount_type'] = 0;
                    $ledgerDetails[2]['discount_amount'] = '';
                    $ledgerDetails[2]['deleted'] = $deleted;
                    //SGST
                    $ledgerDetails[3]['branch_id'] = $branch_id;
                    $ledgerDetails[3]['account'] = $gst_type;
                    $ledgerDetails[3]['balance'] = ($sgst_value * $base_unit);
                    $ledgerDetails[3]['entry_id'] = $entryId;
                    $ledgerDetails[3]['ladger_id'] = $sgst_ledger_id;
                    $ledgerDetails[3]['create_date'] = $created_date;
                    $ledgerDetails[3]['narration'] = $narration;
                    $ledgerDetails[3]['selected_currency'] = $selected_currency;
                    $ledgerDetails[3]['unit_price'] = $base_unit;
                    $ledgerDetails[3]['discount_type'] = 0;
                    $ledgerDetails[3]['discount_amount'] = '';
                    $ledgerDetails[3]['deleted'] = $deleted;
                }
                //CESS Tax
                if ($igst_status == 1) {
                    $index = 2;
                } else {
                    $index = 3;
                }
                if ($cess_status == 1) {
                    $index = $index + 1;
                    $ledgerDetails[$index]['branch_id'] = $branch_id;
                    $ledgerDetails[$index]['account'] = $gst_type;
                    $ledgerDetails[$index]['balance'] = ($cess_value * $base_unit);
                    $ledgerDetails[$index]['entry_id'] = $entryId;
                    $ledgerDetails[$index]['ladger_id'] = $cess_ledger_id;
                    $ledgerDetails[$index]['create_date'] = $created_date;
                    $ledgerDetails[$index]['narration'] = $narration;
                    $ledgerDetails[$index]['selected_currency'] = $selected_currency;
                    $ledgerDetails[$index]['unit_price'] = $base_unit;
                    $ledgerDetails[$index]['discount_type'] = 0;
                    $ledgerDetails[$index]['discount_amount'] = '';
                    $ledgerDetails[$index]['deleted'] = $deleted;
                }
                if (isset($discount_value_hidden) && count($discount_value_hidden) > 1) {
                    for ($i = 1; $i <= count($discount_value_hidden) - 1; $i++) {
                        $index++;
                        $j = $i + 1;
                        if (!empty($discount_value_hidden[$i - 1])) {
                            $balance = $discount_value_hidden[$i - 1];
                        }
                        $ledgerDetails[$index]['branch_id'] = $branch_id;
                        $ledgerDetails[$index]['account'] = $account_type[$j];
                        $ledgerDetails[$index]['balance'] = ($balance * $base_unit);
                        $ledgerDetails[$index]['entry_id'] = $entryId;
                        $ledgerDetails[$index]['ladger_id'] = $tr_ledger_id[$j];
                        $ledgerDetails[$index]['create_date'] = $created_date;
                        $ledgerDetails[$index]['narration'] = $narration;
                        $ledgerDetails[$index]['selected_currency'] = $selected_currency;
                        $ledgerDetails[$index]['unit_price'] = $base_unit;
                        $ledgerDetails[$index]['discount_type'] = 1;
                        $ledgerDetails[$index]['discount_amount'] = $discount_percent_hidden[$i - 1];
                        $ledgerDetails[$index]['deleted'] = $deleted;
                    }
                }

                $this->custommodel->setEntryDetails($ledgerDetails);
                
                //Closing Balance update 03052018
                $financial_year = get_financial_year();
                $from_date = date("Y-m-d", strtotime(current($financial_year)));
                $to_date = date("Y-m-t", strtotime(end($financial_year)));
                foreach($ledgerDetails AS $ledger_id){
                    $ledger_detail = $this->inventorymodel->getLedgerByLedgerIdByDate($ledger_id['ladger_id'],$from_date,$to_date,$branch_id);
                    $where= array(
                        'branch_id'=>$branch_id,
                        'entry_id'=>0,
                        'ladger_id'=>$ledger_id['ladger_id']
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
                            $tracking_details_array[$t]['account_type'] = $trackingOther[$j]->tracking_id;
                            $tracking_details_array[$t]['tracking_amount'] = $trackingOther[$j]->tracking_amount;
                            $tracking_details_array[$t]['entry_id'] = $entryId;
                            $tracking_details_array[$t]['created_date'] = $created_date;
                            $tracking_details_array[$t]['deleted'] = $deleted;
                            $t++;
                        }
                    }
                    $this->custommodel->insertTracking($tracking_details_array);
                }

                // For Order
                $order = array(
                    'branch_id' => $branch_id,
                    'users_id' => $tr_ledger_id[0],
                    'total' => ($product_grand_total * $base_unit),
                    'order_date' => $created_date,
                    'creation_date' => $created_date,
                    'tax_amount' => ($tax_value * $base_unit),
                    'entry_id' => $entryId,
                    'grand_total' => ($product_grand_total * $base_unit),
                    'currency_code' => $selected_currency,
                    'order_type' => ($entry_type_id == 5 || $parent_id==5) ? '1' : '2',
                    'flow_type' => ($entry_type_id == 5 || $parent_id==5) ? '1' : '0',
                    'is_igst_included' => $igst_status,
                    'is_cess_included' => $cess_status,
                    'status' => $order_deleted
                );

                $order_data = $this->inventorymodel->getOrderIdTemp($entry_id, $order_type);
                $orderId = $order_data->id;
                $res = $this->inventorymodel->updateOrder($orderId, $order);

                $this->inventorymodel->deleteOrderProduct($orderId);
                // For Order Details
                if (count($product_id) > 0) {
                    for ($j = 0; $j < count($product_id); $j++) {
                        $order_product = $this->inventorymodel->getOrderProduct($orderId, $product_id[$j], $stock_id[$j]);
                        $productDetails = array(
                            'branch_id' => $branch_id,
                            'order_id' => $orderId,
                            'product_id' => $product_id[$j],
                            'product_description' => $product_description[$j],
                            'stock_id' => $stock_id[$j],
                            'quantity' => $product_quantity[$j],
                            'original_price' => $product_price[$j],
                            'base_price' => $product_price[$j],
                            'price' => $gross_total_price_per_prod[$j],
                            'igst_tax_percent' => ($igst_tax_percent[$j]) ? $igst_tax_percent[$j] : 0,
                            'igst_tax' => ($igst_tax_value[$j]) ? $igst_tax_value[$j] : 0,
                            'cgst_tax_percent' => ($cgst_tax_percent[$j]) ? $cgst_tax_percent[$j] : 0,
                            'cgst_tax' => ($cgst_tax_value[$j]) ? $cgst_tax_value[$j] : 0,
                            'sgst_tax_percent' => ($sgst_tax_percent[$j]) ? $sgst_tax_percent[$j] : 0,
                            'sgst_tax' => ($sgst_tax_value[$j]) ? $sgst_tax_value[$j] : 0,
                            'cess_tax_percent' => ($cess_tax_percent[$j]) ? $cess_tax_percent[$j] : 0,
                            'cess_tax' => ($cess_tax_value[$j]) ? $cess_tax_value[$j] : 0,
                            'total' => $total_price_per_prod_with_tax[$j],
                            'creation_date' => $created_date,
                            'flow_type' => ($entry_type_id == 5 || $parent_id==5) ? '66' : '55',
                            'status' => $order_deleted,
                            'order_type' => ($entry_type_id == 5 || $parent_id==5) ? '1' : '2'
                        );
                        
                        //For primary godown Godown if godown status == 0 and batch status == 0 
                         if($batch_status == 0 && $godown_status == 0){
                            $godown_array[$j]['entry_id'] = $entry_id;
                            $godown_array[$j]['stock_id'] = $orderId;
                            $godown_array[$j]['godown_id'] = 1;
                            $godown_array[$j]['godown_name'] = 'Local Store';
                            $godown_array[$j]['product_id'] = $product_id[$j];
                            $godown_flow_type = ($entry_type_id == 5 || $parent_id==5) ? 'quantity_out' : 'quantity_in';
                            $godown_array[$j][$godown_flow_type] = $product_quantity[$j];
                            $godown_array[$j]['transaction_type'] = ($entry_type_id == 5 || $parent_id==5) ? '1' : '2';
                         }
                        
                        if ($order_product) {
                            $this->inventorymodel->updateOrderProduct($orderId, $product_id[$j], $stock_id[$j], $productDetails);
                        } else {
                            $this->inventorymodel->insertOrderProduct($productDetails);
                        }
                        
                        if($batch_status == 0 && $godown_status == 0){
                            $this->inventorymodel->deleteGodown($orderId);
                            $this->inventorymodel->insertGodown($godown_array);
                        }
                        
                    }
                }
                
                //For Batch AND Godown 
                if (count($batchGodown) > 0) {
                    if(($godown_status == 0 && $batch_status == 1) || ($godown_status == 1 && $batch_status == 1) || ($godown_status == 1 && $batch_status == 0)){

                        for ($k = 0; $k < count($batchGodown); $k++) {
                            $batch_array = array();
                            $godown_array = array();
                            $b = 0;
                            $g = 0;
                            $batchValue = json_decode($batchGodown[$k]['value']);
                            for ($j = 0; $j < count($batchValue); $j++) {
                                if($batchValue[$j]->productBatchStatus == 1 && $batch_status == 1){
                                    // For bathch 
                                    $manufact_date = date("Y-m-d", strtotime(str_replace('/', '-', $batchValue[$j]->manufact_date)));
                                    if ($batchValue[$j]->exp_type_id == 1) {
                                        $expiry_date = date('Y-m-d', strtotime(str_replace('/', '-',$batchValue[$j]->exp_days)));
                                    } else {
                                        $expiry_date = date('Y-m-d', strtotime("+".$batchValue[$j]->exp_days." " . $batchValue[$j]->exp_type, strtotime($manufact_date)));
                                    }

                                    $batch_array[$b]['branch_id'] = $branch_id;
                                    $batch_array[$b]['stock_id'] = $orderId; //as order id in many plase
                                    $batch_array[$b]['godown_id'] = ($batchValue[$j]->batch_godown_id)?($batchValue[$j]->batch_godown_id):1;
                                    $batch_array[$b]['product_id'] = $batchGodown[$k]['product_id'];
                                    $batch_array[$b]['batch_no'] = $batchValue[$j]->batch_no;
                                    $batch_array[$b]['parent_id'] = ($entry_type_id == 5 || $parent_id==5) ? $batchValue[$j]->batch_id : 0;
                                    $batch_array[$b]['in_out'] = ($entry_type_id == 5 || $parent_id==5) ? 2 : 1;
                                    $batch_array[$b]['quantity'] = $batchValue[$j]->batch_qty;
                                    $batch_array[$b]['rate'] = $batchValue[$j]->batch_rate;
                                    $batch_array[$b]['value'] = $batchValue[$j]->batch_value;
                                    $batch_array[$b]['exp_type'] = $batchValue[$j]->exp_type_id;
                                    $batch_array[$b]['exp_days_given'] = $batchValue[$j]->exp_days;
                                    $batch_array[$b]['manufact_date'] = $manufact_date;
                                    $batch_array[$b]['exp_date'] = $expiry_date;
                                    $batch_array[$j]['transaction_type'] = ($entry_type_id == 5 || $parent_id==5) ? '1' : '2';
                                    $b++;
                                }
                                // For godown 
                                $godown_array[$g]['entry_id'] = $entry_id;
                                $godown_array[$g]['stock_id'] = $orderId;
                                $godown_array[$g]['godown_id'] = ($batchValue[$j]->batch_godown_id)?$batchValue[$j]->batch_godown_id:1;
                                $godown_array[$g]['godown_name'] = ($batchValue[$j]->batch_godown_name)?$batchValue[$j]->batch_godown_name:'Local Store';
                                $godown_array[$g]['product_id'] = $batchGodown[$k]['product_id'];
                                $godown_flow_type = ($entry_type_id == 5 || $parent_id==5) ? 'quantity_out' : 'quantity_in';
                                $godown_array[$g][$godown_flow_type] = ($batchValue[$j]->productBatchStatus == 1 && $batch_status == 1)?$batchValue[$j]->batch_qty:$batchValue[$j]->godown_qty;
                                $godown_array[$g]['transaction_type'] = ($entry_type_id == 5 || $parent_id==5) ? '1' : '2';
                                $g++;

                            }
                            if(count($batch_array) > 0){
                                $this->inventorymodel->deleteBatch($orderId,$batchGodown[$k]['product_id']);
                                $this->inventorymodel->insertBatch($batch_array);
                            }
                            $this->inventorymodel->deleteGodown($orderId,$batchGodown[$k]['product_id']);
                            $this->inventorymodel->insertGodown($godown_array);
                        }
                    }
                }

                if ($entry_id) {
                    $res = $this->inventorymodel->deleteTempEntry($entry_id);
                }
                
                
                
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'save_err';
                    $data_msg['message'] = "There was an error submitting the form";
                } else {
                    $this->db->trans_commit();
                    //mail
                    $this->load->helper('email');
                    $company_details = $this->inventorymodel->getCompanyDetails();
                    $voucher = $entry_type['type'];
                    $message = "Your " . $voucher . " transaction added successfully. " . $voucher . " number is " . $entry_number;
                    $company_mail_data = array($company_details->company_name, $message);
                    sendMail($template = 'transaction', $slug = 'transaction_mail', $to = $company_details->email, $company_mail_data);
                    $ledger_contact_details = $this->inventorymodel->getLedgerContactDetails($tr_ledger_id[0]);
                    if ($ledger_contact_details) {
                        //mail   
                        $ledger_mail_data = array($ledger_contact_details->company_name, $message);
                        sendMail($template = 'transaction', $slug = 'transaction_mail', $to = $ledger_contact_details->email, $ledger_mail_data);
                        //end mail 
                    }
                    //end mail
                    $data_msg['type'] = 'delivery notes sales';
                    $data_msg['res'] = 'success';
                    $data_msg['redirect_url'] =  base_url('admin/inventory-transaction-list'). "/" .  str_replace(' ','-',strtolower(trim($entry_type['type']))).'/'.$entry_type['id'].'/all';
                  
                    $data_msg['message'] = "Transaction added successfully. Entry number #" . $entry_number;
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = $this->form_validation->error_array();
            }
        }
        echo json_encode($data_msg);
    }
    
    public function setOrderAddressDetails($orderId,$ledgerId,$shipping_id, $update = 'orders',$isDataNewRef){
        $shippingAddress = $this->custominventorymodel->getShippingAddressByID($shipping_id);
        if($isDataNewRef == 1){
            $billingAddress = $this->inventorymodel->getLedgerContactDetails($ledgerId);
            //billing address
            $order['billing_first_name'] = $billingAddress->company_name;
            $order['billing_address'] = $billingAddress->address;
            $order['billing_city'] = $billingAddress->city;
            $order['billing_zip'] = $billingAddress->zipcode;
            $order['billing_state'] = $billingAddress->state;
            $order['billing_country'] = $billingAddress->country;
        }else if($isDataNewRef == 0){
             //billing address
            $order['billing_first_name'] = $shippingAddress->company_name;
            $order['billing_address'] = $shippingAddress->address;
            $order['billing_city'] = $shippingAddress->city;
            $order['billing_zip'] = $shippingAddress->zip;
            $order['billing_state'] = $shippingAddress->state;
            $order['billing_country'] = $shippingAddress->country;
        }
        
        
        //shipping address
        $order['shipping_first_name'] = $shippingAddress->company_name;
        $order['shipping_address'] = $shippingAddress->address;
        $order['shipping_city'] = $shippingAddress->city;
        $order['shipping_zip'] = $shippingAddress->zip;
        $order['shipping_state'] = $shippingAddress->state;
        $order['shipping_country'] = $shippingAddress->country;
        
        
        if($update == 'orders'){
            $this->inventorymodel->updateOrder($orderId, $order);
        }
        
        if($update == 'temp'){
            $this->inventorymodel->updateOrder($orderId, $order);
        }
        
        if($update == 'request'){
            $this->inventorymodel->updateOrderRequestByOrderId($orderId, $order);
        }
        return;
    }


    public function testInvoice($id)
    {
        $data = array();
        $entry_type_id = 5;
        // $id = ($id) ? $id:2;
        $sub_voucher_id = 0;
        $this->load->model('transaction_inventory/inventory/inventorymodel');
        $entry_type=$this->inventorymodel->getEntryTypeById($entry_type_id);
        $parent_id=$entry_type['parent_id'];
        if ($entry_type_id == 5 || $parent_id == 5) {
           $order_type = 1;
        } else {
           $order_type = 2;
        }
        $entry = $this->inventorymodel->getEntry($id);

        $data['entry'] = $entry;

        $data['entry_details'] = $this->inventorymodel->getEntryDetails($id);
        if ($entry->is_service_product == 0) {
           $order = $this->inventorymodel->getOrder($id, $order_type);

           $data['order'] = $order;
           if ($order) {
               $data['order_details'] = $this->inventorymodel->getOrderDetails($order->id);
           } else {
               $data['order_details'] = '';
           }

        } else {
           $order = $this->inventorymodel->getTempOrder($id, $order_type);

           $data['order'] = $order;
           if ($order) {
               $data['order_details'] = $this->inventorymodel->getTempOrderDetails($order->id);
           } else {
               $data['order_details'] = '';
           }
        }

        $data['company_details'] = $this->inventorymodel->getCompanyDetails();
        if ($order) {
           $data['ledger_contact_details'] = $this->inventorymodel->getLedgerContactDetails($order->users_id);
        } else {
           $data['ledger_contact_details'] = '';
        }
        if($sub_voucher_id == 0){
           $data['voucher'] = $this->inventorymodel->getVoucherType($entry_type_id);
        }else{
           $data['voucher'] = $this->inventorymodel->getVoucherType($sub_voucher_id);
        }

        $data['entry_type_id'] = $entry_type_id;
        $html = $this->load->view('transaction_inventory/inventory/test_invoice', $data, TRUE);

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
        $pdf_name = 'Invoice'.time();

        // create directory is not exist otherwise give the permission
        if (!file_exists(FCPATH . "assets/pdf_for_mail_uploads")) {
           mkdir(FCPATH . "assets/pdf_for_mail_uploads", 0777, true);
        } else {
           // chmod(FCPATH . "assets/pdf_for_mail_uploads", 0777);
        }

        $fileName = $pdf_name . '.pdf';
        // chmod(FCPATH . "assets/pdf_for_mail_uploads/".$fileName, 0777);
        $this->dompdf1->generatePdf($htmlContent, $fileName);
        return $fileName;

        // $this->load->helper('actmail');
        // sendActMail('', '', 'sketch.dev24@gmail.com', [], $fileName);
    }
    
    public function calculateBaseUnit($base_unit_id,$complex_unit_id){
        $qty = 1;
        do{
            $result = $this->inventorymodel->getUnitDetailsByComplexUnitId($complex_unit_id);
            $qty = $qty * $result->qty;
            $complex_unit_id = $result->base_unit_id;
        } while($base_unit_id == $result->unit_id);
        return $qty;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
