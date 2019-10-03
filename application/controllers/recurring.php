<?php

class recurring extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('recurring_model');
        $this->load->helper('email');
        $this->load->model('transaction_inventory/inventory/inventorymodel');
        $this->load->model('accounts/entry');
        $this->load->helper('financialyear');
        $this->load->helper('actmail');
       
    }

    function generateEntryNumber() {
        return 'RE-' . time();
    }

    function getEntryNumber($entry_type_id) {
        $entry_type = $this->recurring_model->getEntryTypeById($entry_type_id);
        $countid = 1;
        $today = date("Y-m-d H:i:s");
        $auto_number = $this->recurring_model->getNoOfByTypeId($entry_type_id, $today, $entry_type['strating_date']);
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

    function randomString($length, $type = '') {
        // Select which type of characters you want in your random string
        switch ($type) {
            case 'num':
                // Use only numbers
                $salt = '1234567890';
                break;
            case 'lower':
                // Use only lowercase letters
                $salt = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case 'upper':
                // Use only uppercase letters
                $salt = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            default:
                // Use uppercase, lowercase, numbers, and symbols
                $salt = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                break;
        }
        $rand = '';
        $i = 0;
        while ($i < $length) { // Loop until you have met the length
            $num = rand() % strlen($salt);
            $tmp = substr($salt, $num, 1);
            $rand = $rand . $tmp;
            $i++;
        }
        return $rand; // Return the random string
    }

    public function index_old() {
//        if (!$this->input->is_cli_request()) {
//            echo "This script can only be accessed via the command line" . PHP_EOL;
//            return;
//        }
        $entry_id_arr = [];
        //dealy
        $all_dealy_recurring = $this->recurring_model->get_all_dealy_recurring();
        if (!empty($all_dealy_recurring)) {
            foreach ($all_dealy_recurring as $recurring) {
                $entry_id_arr[] = $recurring->entry_id;
            }
        }
        //weekly
        $all_weekly_recurring = $this->recurring_model->get_all_weekly_recurring();
        if (!empty($all_weekly_recurring)) {
            foreach ($all_weekly_recurring as $recurring) {
                if (date('w', strtotime($recurring->recurring_date)) == date('w')) {
                    $entry_id_arr[] = $recurring->entry_id;
                }
            }
        }
        //monthly
        $all_monthly_recurring = $this->recurring_model->get_all_monthly_recurring();
        if (!empty($all_monthly_recurring)) {
            foreach ($all_monthly_recurring as $recurring) {
                if (date('d', strtotime($recurring->recurring_date)) == date('d')) {
                    $entry_id_arr[] = $recurring->entry_id;
                }
            }
        }
        //yearly
        $all_yearly_recurring = $this->recurring_model->get_all_yearly_recurring();
        if (!empty($all_yearly_recurring)) {
            foreach ($all_yearly_recurring as $recurring) {
                if ((date('d', strtotime($recurring->recurring_date)) == date('d')) && (date('m', strtotime($recurring->recurring_date)) == date('m'))) {
                    $entry_id_arr[] = $recurring->entry_id;
                }
            }
        }
        if ($entry_id_arr) {
            $list_html = '<table border="1" width="100%" cellspacing="0" cellpadding="0">';
            $list_html.='<thead>';
            $list_html.='<tr>';
            $list_html.='<td>';
            $list_html.='Date';
            $list_html.='</td>';
            $list_html.='<td>';
            $list_html.='Entry Number';
            $list_html.='</td>';
            $list_html.='<td>';
            $list_html.='Ledger';
            $list_html.='</td>';
            $list_html.='<td>';
            $list_html.='Amount';
            $list_html.='</td>';
            $list_html.='</tr>';
            $list_html.='</thead>';
            $list_html.='<tbody>';
            $all_entry = $this->recurring_model->get_all_entry($entry_id_arr);
            foreach ($all_entry as $row) {
                $all_entry_details = $this->recurring_model->get_all_entry_details($row->id);
                $all_voucher_data = $this->recurring_model->get_all_voucher($row->id);
                $all_tracking_data = $this->recurring_model->get_all_tracking($row->id);
                $all_bank_data = $this->recurring_model->get_all_bank_data($row->id);
                $entry_data = array(
                    'user_id' => $row->user_id,
                    'company_id' => $row->company_id,
                    'entry_type_id' => $row->entry_type_id,
                    'sub_voucher' => $row->sub_voucher,
                    'entry_no' => $this->getEntryNumber($row->entry_type_id),
                    'create_date' => date("Y-m-d H:i:s"),
                    'ledger_ids_by_accounts' => $row->ledger_ids_by_accounts,
                    'dr_amount' => $row->dr_amount,
                    'cr_amount' => $row->cr_amount,
                    'unit_price_dr' => $row->unit_price_dr,
                    'unit_price_cr' => $row->unit_price_cr,
                    'status' => $row->status,
                    'deleted' => $row->deleted,
                    'is_inventry' => $row->is_inventry,
                    'order_id' => $row->order_id,
                    'bank_id' => $row->bank_id
                );
                $this->db->trans_begin();
                $entry_id = $this->recurring_model->saveEntry($entry_data);
                //mail body
                $list_html.='<tr>';
                $list_html.='<td>';
                $list_html.=date("d/m/Y", strtotime($entry_data['create_date']));
                $list_html.='</td>';
                $list_html.='<td>';
                $list_html.=$entry_data['entry_no'];
                $list_html.='</td>';
                $list_html.='<td>';
                $led = array();
                $devit = json_decode($entry_data['ledger_ids_by_accounts']);
                $list_html.="<strong>Dr </strong>";
                for ($i = 0; $i < count($devit->Dr); $i++) {
                    $list_html.= $devit->Dr[$i];
                    if (count($devit->Dr) > 1) {
                        $list_html.= ' + ';
                    }
                    break;
                }
                $list_html.='/';
                $list_html.= "<strong>Cr </strong>";
                for ($i = 0; $i < count($devit->Cr); $i++) {
                    $list_html.= $devit->Cr[$i];
                    if (count($devit->Cr) > 1) {
                        $list_html.= ' + ';
                    }
                    break;
                }
                $list_html.='</td>';
                $list_html.='<td>';
                $list_html.=$entry_data['dr_amount'];
                $list_html.='</td>';
                $list_html.='</tr>';
                //end mail body
                //entry details data
                $entry_details_data = [];
                foreach ($all_entry_details as $row) {
                    $item = array(
                        'ladger_id' => $row->ladger_id,
                        'entry_id' => $entry_id,
                        'account' => $row->account,
                        'balance' => $row->balance,
                        'current_opening_balance' => $row->current_opening_balance,
                        'current_closing_balance' => $row->current_closing_balance,
                        'unit_price' => $row->unit_price,
                        'selected_currency' => $row->selected_currency,
                        'status' => $row->status,
                        'deleted' => $row->deleted,
                        'narration' => $row->narration,
                        'create_date' => date("Y-m-d H:i:s")
                    );
                    $entry_details_data[] = $item;
                }
                if ($entry_details_data) {
                    $this->db->insert_batch('ladger_account_detail', $entry_details_data);
                }
                //voucher data
                $voucher_data = [];
                foreach ($all_voucher_data as $row) {
                    $credit_date = strtotime("+" . $row->credit_days . " days");
                    $item = array(
                        'ladger_id' => $row->ladger_id,
                        'dr_cr' => $row->dr_cr,
                        'ref_type' => $row->ref_type,
                        'bill_name' => $row->bill_name,
                        'credit_days' => $row->credit_days,
                        'credit_date' => $credit_date,
                        'bill_amount' => $row->bill_amount,
                        'entry_id' => $entry_id,
                        'is_reminded' => $row->is_reminded,
                        'created_date' => date("Y-m-d H:i:s")
                    );
                    $voucher_data[] = $item;
                }
                if ($voucher_data) {
                    $this->db->insert_batch('billwish_details', $voucher_data);
                }
                
                //Closing Balance update 03052018
                $financial_year = get_financial_year();
                $from_date = date("Y-m-d", strtotime(current($financial_year)));
                $to_date = date("Y-m-t", strtotime(end($financial_year)));
                foreach ($all_voucher_data as $row) {
                    $ledger_detail = $this->inventorymodel->getLedgerByLedgerIdByDate($row->ladger_id,$from_date,$to_date,$branch_id);
                    $where= array(
                        'branch_id'=>$branch_id,
                        'entry_id'=>0,
                        'ladger_id'=>$row->ladger_id
                    );
                    $closingValue['current_closing_balance'] = ($ledger_detail['account_type'] == 'Dr')?$ledger_detail['dr_balance'] - $ledger_detail['cr_balance'] + $ledger_detail['opening_balance']:$ledger_detail['cr_balance'] - $ledger_detail['dr_balance'] + $ledger_detail['opening_balance'];
                    $this->inventorymodel->updateClosingBalance($where,$closingValue);
                }
                
                //tracking data
                $tracking_data = [];
                foreach ($all_tracking_data as $row) {
                    $item = array(
                        'entry_id' => $entry_id,
                        'account_type' => $row->account_type,
                        'ledger_id' => $row->ledger_id,
                        'tracking_id	' => $row->tracking_id,
                        'tracking_amount' => $row->tracking_amount,
                        'created_date' => date("Y-m-d H:i:s")
                    );
                    $tracking_data[] = $item;
                }
                if ($tracking_data) {
                    $this->db->insert_batch('tracking_details', $tracking_data);
                }
                //tracking data
                $bank_data = [];
                foreach ($all_bank_data as $row) {
                    $item = array(
                        'entry_id' => $entry_id,
                        'ledger_id' => $row->ledger_id,
                        'transaction_type' => $row->transaction_type,
                        'instrument_no	' => $row->instrument_no,
                        'instrument_date' => $row->instrument_date,
                        'bank_name' => $row->bank_name,
                        'branch_name' => $row->branch_name,
                        'ifsc_code' => $row->ifsc_code,
                        'bank_amount' => $row->bank_amount,
                        'create_date' => date("Y-m-d H:i:s")
                    );
                    $bank_data[] = $item;
                }
                if ($bank_data) {
                    $this->db->insert_batch('bank_details', $bank_data);
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }
            }
            $list_html.='</tbody>';
            $list_html .= '</table>';
            //mail  
            $company_details = $this->recurring_model->get_company_details();
            $mail_data = array($company_details->company_name, $list_html);
            sendMail($template = 'recurring_template', $slug = 'recurring_entry', $to = $company_details->email, $mail_data);
            //end mail 
        }
    }
     public function index() {
        $entry_id_arr = [];
        //dealy
        $all_dealy_recurring = $this->recurring_model->get_all_dealy_recurring();
        if (!empty($all_dealy_recurring)) {
            foreach ($all_dealy_recurring as $recurring) {
                $entry_id_arr[] = $recurring->entry_id;
            }
        }
        //weekly
        $all_weekly_recurring = $this->recurring_model->get_all_weekly_recurring();
        if (!empty($all_weekly_recurring)) {
            foreach ($all_weekly_recurring as $recurring) {
                if (date('w', strtotime($recurring->recurring_date)) == date('w')) {
                    $entry_id_arr[] = $recurring->entry_id;
                }
            }
        }
        //monthly
        $all_monthly_recurring = $this->recurring_model->get_all_monthly_recurring();
        if (!empty($all_monthly_recurring)) {
            foreach ($all_monthly_recurring as $recurring) {
                if (date('d', strtotime($recurring->recurring_date)) == date('d')) {
                    $entry_id_arr[] = $recurring->entry_id;
                }
            }
        }
        //yearly
        $all_yearly_recurring = $this->recurring_model->get_all_yearly_recurring();
        if (!empty($all_yearly_recurring)) {
            foreach ($all_yearly_recurring as $recurring) {
                if ((date('d', strtotime($recurring->recurring_date)) == date('d')) && (date('m', strtotime($recurring->recurring_date)) == date('m'))) {
                    $entry_id_arr[] = $recurring->entry_id;
                }
            }
        }
        if ($entry_id_arr) {
            $list_html = '<table border="1" width="100%" cellspacing="0" cellpadding="0">';
            $list_html.='<thead>';
            $list_html.='<tr>';
            $list_html.='<td>';
            $list_html.='Date';
            $list_html.='</td>';
            $list_html.='<td>';
            $list_html.='Entry Number';
            $list_html.='</td>';
            $list_html.='<td>';
            $list_html.='Ledger';
            $list_html.='</td>';
            $list_html.='<td>';
            $list_html.='Amount';
            $list_html.='</td>';
            $list_html.='</tr>';
            $list_html.='</thead>';
            $list_html.='<tbody>';
            $all_entry = $this->recurring_model->get_all_entry($entry_id_arr);

            foreach ($all_entry as $row) {
                $current_entry_id = $row->id;
                $current_entry_type_id = $row->entry_type_id;
                $current_create_date = date('d-m-Y', strtotime($row->create_date));
                $entry_type_id = $row->entry_type_id;
                $parent_id = $row->sub_voucher;
                $recurring_entry_id = $row->id;
                $all_entry_details = $this->recurring_model->get_all_entry_details($row->id);
                $all_voucher_data = $this->recurring_model->get_all_voucher($row->id);
                $all_tracking_data = $this->recurring_model->get_all_tracking($row->id);
                $all_bank_data = $this->recurring_model->get_all_bank_data($row->id);
                
                $voucher_details = $this->recurring_model->get_voucher_details($entry_type_id,$parent_id);
                
                //generate auto entry no
                if ($voucher_details['transaction_no_status'] == 1) {
                    $countid = 1;
                    $today = date("Y-m-d H:i:s");
                    $auto_number = $this->entry->getNoOfByTypeId($entry_type_id,$parent_id, $today, $voucher_details['strating_date']);
                    $start_length = $voucher_details['starting_entry_no'];
                    $countid = $countid + $auto_number['total_transaction'];
                    $id_length = strlen($countid);
                    if ($start_length > $id_length) {
                        $remaining = $start_length - $id_length;
                        $uniqueid = $voucher_details['prefix_entry_no'] . str_repeat("0", $remaining) . $countid . $voucher_details['suffix_entry_no'];
                    } else {
                        $uniqueid = $voucher_details['prefix_entry_no'] . $countid . $voucher_details['suffix_entry_no'];
                    }
                    $entry_number = $uniqueid;
                }else{
                    $countid = 1;
                    $auto_number = $this->entry->getNoOfByTypeId($entry_type_id,$parent_id, $today, $voucher_details['strating_date']);
                    $countid = $countid + $auto_number['total_transaction'];
                    $entry_number = $countid;
                }
                

                $entry_data = array(
                    'entry_no' => $entry_number,
                    'create_date' => date("Y-m-d H:i:s"),
                    'ledger_ids_by_accounts' => $row->ledger_ids_by_accounts,
                    'dr_amount' => $row->dr_amount,
                    'cr_amount' => $row->cr_amount,
                    'unit_price_dr' => $row->unit_price_dr,
                    'unit_price_cr' => $row->unit_price_cr,
                    'entry_type_id' => $row->entry_type_id,
                    'sub_voucher' => $row->sub_voucher,
                    'user_id' => $row->user_id,
                    'company_id' => $row->company_id,
                    'is_inventry' => $row->is_inventry,
                    'narration' => $row->narration,
                    'order_id' => $row->order_id,
                    'voucher_no' =>$row->voucher_no,
                    'voucher_date' => $row->voucher_date,
                    'is_reverse_entry' => $row->is_reverse_entry,
                    'is_service_product' => $row->is_service_product,
                    'is_advance_sell' => $row->is_advance_sell,
                    'deleted' => $row->deleted,
                );
                $this->db->trans_begin();
                $entry_id = $this->recurring_model->saveEntry($entry_data);
                //mail body
                $list_html.='<tr>';
                $list_html.='<td>';
                $list_html.=date("d/m/Y", strtotime($entry_data['create_date']));
                $list_html.='</td>';
                $list_html.='<td>';
                $list_html.=$entry_data['entry_no'];
                $list_html.='</td>';
                $list_html.='<td>';
                $led = array();
                $devit = json_decode($entry_data['ledger_ids_by_accounts']);
                $list_html.="<strong>Dr </strong>";
                for ($i = 0; $i < count($devit->Dr); $i++) {
                    $list_html.= $devit->Dr[$i];
                    if (count($devit->Dr) > 1) {
                        $list_html.= ' + ';
                    }
                    break;
                }
                $list_html.='/';
                $list_html.= "<strong>Cr </strong>";
                for ($i = 0; $i < count($devit->Cr); $i++) {
                    $list_html.= $devit->Cr[$i];
                    if (count($devit->Cr) > 1) {
                        $list_html.= ' + ';
                    }
                    break;
                }
                $list_html.='</td>';
                $list_html.='<td>';
                $list_html.=$entry_data['dr_amount'];
                $list_html.='</td>';
                $list_html.='</tr>';
                //end mail body
                //entry details data
                $entry_details_data = [];
                foreach ($all_entry_details as $row) {
                    $item = array(
                        'branch_id' => $row->branch_id,
                        'ladger_id' => $row->ladger_id,
                        'entry_id' => $entry_id,
                        'account' => $row->account,
                        'balance' => $row->balance,
                        'unit_price' => $row->unit_price,
                        'selected_currency' => $row->selected_currency,
                        'status' => $row->status,
                        'deleted' => $row->deleted,
                        'narration' => $row->narration,
                        'discount_type' => $row->discount_type,
                        'discount_amount' => $row->discount_amount,
                        'create_date' => date("Y-m-d H:i:s")
                    );
                    $entry_details_data[] = $item;
                }
                if ($entry_details_data) {
                    $this->db->insert_batch('ladger_account_detail', $entry_details_data);
                }
                //voucher data
                $voucher_data = [];
                foreach ($all_voucher_data as $row) {
//                    $credit_date = strtotime("+" . $row->credit_days . " days");
                    $bill_credit_date = strtotime("+".$row->credit_days." days", strtotime(date("Y-m-d")));
                    $item = array(
                        'branch_id' => $row->branch_id,
                        'ledger_id' => $row->ledger_id,
                        'dr_cr' => $row->dr_cr,
                        'ref_type' => $row->ref_type,
                        'bill_name' => $row->bill_name,
                        'credit_days' => $row->credit_days,
                        'credit_date' => date('Y-m-d', $bill_credit_date),
                        'bill_amount' => $row->bill_amount,
                        'entry_id' => $entry_id,
                        'is_reminded' => $row->is_reminded,
                        'deleted' => $row->deleted,
                        'created_date' => date("Y-m-d H:i:s")
                    );
                    $voucher_data[] = $item;
                }
                if ($voucher_data) {
                    $this->db->insert_batch('billwish_details', $voucher_data);
                }
                //tracking data
                $tracking_data = [];
                foreach ($all_tracking_data as $row) {
                    $item = array(
                        'entry_id' => $entry_id,
                        'account_type' => $row->account_type,
                        'ledger_id' => $row->ledger_id,
                        'tracking_id' => $row->tracking_id,
                        'tracking_amount' => $row->tracking_amount,
                        'deleted' => $row->deleted,
                        'created_date' => date("Y-m-d H:i:s")
                    );
                    $tracking_data[] = $item;
                }
                if ($tracking_data) {
                    $this->db->insert_batch('tracking_details', $tracking_data);
                }
                //Bank data
                $bank_data = [];
                foreach ($all_bank_data as $row) {
                    $item = array(
                        'entry_id' => $entry_id,
                        'ledger_id' => $row->ledger_id,
                        'transaction_type' => $row->transaction_type,
                        'instrument_no	' => $row->instrument_no,
                        'instrument_date' => $row->instrument_date,
                        'bank_name' => $row->bank_name,
                        'branch_name' => $row->branch_name,
                        'ifsc_code' => $row->ifsc_code,
                        'bank_amount' => $row->bank_amount,
                        'create_date' => date("Y-m-d H:i:s")
                    );
                    $bank_data[] = $item;
                }
                if ($bank_data) {
                    $this->db->insert_batch('bank_details', $bank_data);
                }
                
                //product details add
                if ($entry_type_id == 5 || $entry_type_id == 6 || $parent_id==5 || $parent_id==6) {
                    if ($entry_type_id == 5 || $parent_id==5) {
                        $order_type = 1;
                    } else {
                        $order_type = 2;
                    }
                    
                    $order = $this->inventorymodel->getOrder($recurring_entry_id, $order_type);
                    
                    $order_details = $this->inventorymodel->getOrderDetails($order->id);
                    
                    // For Order
                    $order_data = array(
                        'branch_id' => $order->branch_id,
                        'users_id' => $order->users_id,
                        'total' => $order->total,
                        'order_date' => date("Y-m-d H:i:s"),
                        'creation_date' => date("Y-m-d H:i:s"),
                        'tax_amount' => $order->tax_amount,
                        'entry_id' => $entry_id,
                        'grand_total' => $order->grand_total,
                        'currency_code' => $order->currency_code,
                        'order_type' => $order->order_type,
                        'flow_type' => $order->flow_type,
                        'is_igst_included' => $order->is_igst_included,
                        'is_cess_included' => $order->is_cess_included,
                        'status' => $order->status,
                    );
                    if ($order_data) {
                        $this->db->insert('orders', $order_data);
                        $orderId = $this->db->insert_id();
                    }
                    
                    //Billing Address
                    $address = [];
                    $address['billing_first_name'] = $order->billing_first_name;
                    $address['billing_address'] = $order->billing_address;
                    $address['billing_city'] = $order->billing_city;
                    $address['billing_zip'] = $order->billing_zip;
                    $address['billing_state'] = $order->billing_state;
                    $address['billing_country'] = $order->billing_country;
                    
                    //shipping address
                    $address['shipping_first_name'] = $order->shipping_first_name;
                    $address['shipping_address'] = $order->shipping_address;
                    $address['shipping_city'] = $order->shipping_city;
                    $address['shipping_zip'] = $order->shipping_zip;
                    $address['shipping_state'] = $order->shipping_state;
                    $address['shipping_country'] = $order->shipping_country;
                    
                    if($address){
                        $this->db->where('id', $orderId);
                        $this->db->update('orders', $address);
                    }
                   
                    
                    
                    // For Order Details
                    if (count($order_details) > 0) {
                        $productDetails = array();
                        for ($j = 0; $j < count($order_details); $j++) {
                            $productDetails[$j]['branch_id'] = $order_details[$j]->branch_id;
                            $productDetails[$j]['order_id'] = $orderId;
                            $productDetails[$j]['product_id'] = $order_details[$j]->product_id;
                            $productDetails[$j]['product_description'] = $order_details[$j]->product_description;
                            $productDetails[$j]['stock_id'] = $order_details[$j]->stock_id;
                            $productDetails[$j]['quantity'] = $order_details[$j]->quantity;
                            $productDetails[$j]['original_price'] = $order_details[$j]->original_price;
                            $productDetails[$j]['base_price'] = $order_details[$j]->base_price;
                            $productDetails[$j]['price'] = $order_details[$j]->price;
                            $productDetails[$j]['igst_tax_percent'] = $order_details[$j]->igst_tax_percent;
                            $productDetails[$j]['igst_tax'] = $order_details[$j]->igst_tax;
                            $productDetails[$j]['cgst_tax_percent'] = $order_details[$j]->cgst_tax_percent;
                            $productDetails[$j]['cgst_tax'] = $order_details[$j]->cgst_tax;
                            $productDetails[$j]['sgst_tax_percent'] = $order_details[$j]->sgst_tax_percent;
                            $productDetails[$j]['sgst_tax'] = $order_details[$j]->sgst_tax;
                            $productDetails[$j]['cess_tax_percent'] = $order_details[$j]->cess_tax_percent;
                            $productDetails[$j]['cess_tax'] = $order_details[$j]->cess_tax;
                            $productDetails[$j]['total'] = $order_details[$j]->total;
                            $productDetails[$j]['creation_date'] = date("Y-m-d H:i:s");
                            $productDetails[$j]['flow_type'] = $order_details[$j]->flow_type;
                            $productDetails[$j]['order_type'] = $order_details[$j]->order_type;
                            $productDetails[$j]['status'] = $order_details[$j]->status;
                        }
                        if($productDetails){
                            $this->db->insert_batch('ordered_products', $productDetails);
                        }
                    }
                   
                }
                
                
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();

                    $company_details = $this->recurring_model->get_company_details();
                    $voucher_arr = [1,2,3,4];
                    if (in_array($current_entry_type_id, $voucher_arr)) {
                        $attach = $this->testVoucher($current_entry_id, $current_entry_type_id);
                        $message = "Vocher Date : " . $current_create_date;
                    } else {
                        $message = "Invoice Date : " . $current_create_date;
                        $attach = $this->testInvoice($current_entry_id, $current_entry_type_id);
                    }

                    $mail_data = array($company_details->company_name, $message);
                    sendActMail($template = 'recurring_template', $slug = 'recurring_entry', $to = $company_details->email, $mail_data, $attach, $company_details->company_name);
                }

                // echo $current_entry_type_id;exit();
            }
            $list_html.='</tbody>';
            $list_html .= '</table>';
            //mail  
            // $company_details = $this->recurring_model->get_company_details();
            // $mail_data = array($company_details->company_name, $list_html);
            // echo "<pre>";print_r($mail_data);exit();
            // sendMail($template = 'recurring_template', $slug = 'recurring_entry', $to = $company_details->email, $mail_data);
            //end mail 
        }
    }


    public function testInvoice($id, $entry_type_id)
    {
        $data = array();
        $this->load->model('transaction_inventory/inventory/inventorymodel');
        // $id = ($id) ? $id:2;
        $sub_voucher_id = 0;

        $data = array();
        $data['despatchDetails'] = FALSE;
        $entry_type=$this->inventorymodel->getEntryTypeById($entry_type_id);
        $parent_id=$entry_type['parent_id'];
        if ($entry_type_id == 5 || $entry_type_id == 6 || $parent_id == 5 || $parent_id == 6) {
            if ($entry_type_id == 5 || $parent_id == 5) {
                $order_type = 1;
            } else {
                $order_type = 2;
            }
            $entry = $this->inventorymodel->getEntry($id);
            $data['entry'] = $entry;
            
            $getDespatchDetails = $this->inventorymodel->getDespatchDetailsByEntryId($id);
            $data['despatchDetails'] = $getDespatchDetails;
            
            $getEwaybillDetails = $this->inventorymodel->getEwaybillByEntryId($id);
            $data['ewaybillDetails'] = $getEwaybillDetails;
            
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
        } else if ($entry_type_id == 14 || $entry_type_id == 12 || $parent_id == 14 || $parent_id == 12) {
            if ($entry_type_id == 14  || $parent_id == 14) {
                $order_type = 7;
            } else {
                $order_type = 8;
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
        } elseif ($entry_type_id == 7 || $entry_type_id == 8 || $parent_id == 7 || $parent_id == 8) {
            $entry = $this->inventorymodel->getRequestEntry($id);
            $data['entry'] = $entry;
            $data['entry_details'] = $this->inventorymodel->getRequestEntryDetails($id);
            $order = $this->inventorymodel->getRequestOrder($id);

            $data['order'] = $order;
            $data['order_details'] = $this->inventorymodel->getRequestOrderDetails($order->id);
        } elseif ($entry_type_id == 9 || $entry_type_id == 10 || $parent_id == 9 || $parent_id == 10) {
            if ($entry_type_id == 10 || $parent_id == 10) {
                $order_type = 6;
            } else {
                $order_type = 5;
            }
            $entry = $this->inventorymodel->getTempEntry($id);
            $data['entry'] = $entry;
            $data['entry_details'] = $this->inventorymodel->getTempEntryDetails($id);
            $order = $this->inventorymodel->getOrder($id, $order_type);

            $data['order'] = $order;
            $data['order_details'] = $this->inventorymodel->getOrderDetails($order->id);

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
           chmod(FCPATH . "assets/pdf_for_mail_uploads", 0777);
        }

        $fileName = $pdf_name . '.pdf';
        // chmod(FCPATH . "assets/pdf_for_mail_uploads/".$fileName, 0777);
        $this->dompdf1->generatePdf($htmlContent, $fileName);
        return $fileName;

        // $this->load->helper('actmail');
        // sendActMail('', '', 'sketch.dev24@gmail.com', [], $fileName);
    }


    public function testVoucher($entry_id, $entry_type_id)
    {
        $sub_voucher_id = 0;
        $this->load->model('reports/admin/reportsmodel');
        $data = array();
        $this->load->helper('traialbalance');
        $this->load->helper('numtoword');
        $result = get_transaction_details($entry_id);
        $entry_type_id=$result['entry'][0]['entry_type_id'];
        $data['voucher'] = $this->reportsmodel->getVoucherType($entry_type_id);
        $data['entry_id'] = $result['entry_id'];
        $data['types'] = $result['types'];
        $data['ledger'] = $result['ledger'];
        $data['entry'] = $result['entry'];
        $entry_details_result = $result['entry_details'];
        $data['currencies'] = $result['currencies'];
        $data['amountInWord'] = number_to_words($data['entry'][0]['unit_price_dr']);

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

        $html = $this->load->view('admin/voucher_pdf', $data, TRUE);

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

        // $this->load->helper('actmail');
        // sendActMail('', '', 'sketch.dev24@gmail.com', [], $fileName);
    }

}
