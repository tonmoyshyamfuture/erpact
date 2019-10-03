<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class inventory extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->model('inventory/inventorymodel');
        $this->load->model('transaction/admin/custominventorymodel');
        $this->load->model('accounts/entry');
        $this->load->helper('financialyear');
        $this->load->helper('custom');
        $this->load->model('accounts/account');
        admin_authenticate();
    }

    public function index($name = NULL,$id = NULL, $month = null) {

        $id = (isset($_GET['id'])) ? $_GET['id'] : $id; // @somnath - id has to be set for get the purchase details & set the module for permission (statistics/purchase/purchase details by month 404 )
        $month = (isset($_GET['month'])) ? $_GET['month'] : $month;

        $entry_type=$this->inventorymodel->getVoucherType($id);
        //access
        if($id==5 || $entry_type['parent_id'] == 5){
         $module=194;
        }elseif ($id==6 || $entry_type['parent_id'] == 6) {
         $module=195;
        }elseif ($id==7 || $entry_type['parent_id'] == 7) {
         $module=196;
        }elseif ($id==8 || $entry_type['parent_id'] == 8) {
         $module=197;
        }elseif ($id==10 || $entry_type['parent_id'] == 10) {
         $module=199;
        }elseif ($id==9 || $entry_type['parent_id'] == 9) {
         $module=200;
        }elseif ($id==14 || $entry_type['parent_id'] == 14) {
         $module=202;
        }elseif ($id==12 || $entry_type['parent_id'] == 12) {
         $module=203;
        }elseif ($id==17 || $entry_type['parent_id'] == 17) {
         $module=196;
        }

        user_permission($module,'list');
        //access
        $data = [];
        $financial_year = get_financial_year();
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-t", strtotime(end($financial_year)));
        if ($month != 'all') {
            $from_date = date("Y-m-d", strtotime($month));
            $to_date = date("Y-m-t", strtotime($month));
        }
//        $entry_type=$this->inventorymodel->getVoucherType($id);

        $data['entry_type_id'] = $id;
        $data['parent_id']=$entry_type['parent_id'];
        $data['month'] = $month;
        $data['voucher_name'] = str_replace(' ', '-', strtolower(trim($name)));

        $service_voucher_id = 15;//It is sub voucher of purchase.20032018 @sudip
        $data['sub_vouchers'] = $this->inventorymodel->getAllSubVouchersById($id,$service_voucher_id);

        $data['voucher_type'] = $entry_type;
        if ($id == 5 || $id == 6 || $entry_type['parent_id']==5 || $entry_type['parent_id']==6) {
            $data['all_entries'] = $this->inventorymodel->getAllEntry($id,$entry_type['parent_id'],$from_date, $to_date);
            $data['all_post_dated_entries'] = $this->inventorymodel->getAllPostDatedEntry($id,$entry_type['parent_id']);
            $data['all_recurring_entries'] = $this->inventorymodel->getAllRecurringEntry($id,$entry_type['parent_id']);

            $data['dataCount'] = $this->inventorymodel->getCount($id,$entry_type['parent_id']);
            $data['recurringCount'] = $this->inventorymodel->getRecurringCount($id,$entry_type['parent_id']);
            $data['postdatedCount'] = $this->inventorymodel->getAllPostDatedEntryCount($id,$entry_type['parent_id']);

        } elseif ($id == 7 || $id == 8 || $entry_type['parent_id']==7 || $entry_type['parent_id']==8) {
//            if ($id == 7) {
                $data['all_entries'] = $this->getROrderDetails($id,$entry_type['parent_id']);
//            } else {
//                $data['all_entries'] = $this->inventorymodel->getAllRequestEntry($id);
//            }
        } elseif ($id == 9 || $id == 10 || $entry_type['parent_id']==9 || $entry_type['parent_id']==10) {
            $data['all_entries'] = $this->inventorymodel->getAllTempEntry($id,$entry_type['parent_id']);
        } elseif ($id == 12 || $id == 14 || $entry_type['parent_id']==12 || $entry_type['parent_id']==14) {
            $data['all_entries'] = $this->inventorymodel->getAllEntry($id,$entry_type['parent_id'],$from_date, $to_date);
        }elseif ($id == 17 || $entry_type['parent_id']==17) {
            // $data['all_entries'] = $this->inventorymodel->getAllEntry($id,$entry_type['parent_id'],$from_date, $to_date);
            $all_entry = $this->inventorymodel->getAllQuotationRequestEntry($id,$entry_type['parent_id'], $limit=0, $offset=0);
            $entry = [];
            foreach ($all_entry as $row) {
                $item = array(
                    'id' => $row['id'],
                    'user_id' => $row['user_id'],
                    'company_id' => $row['company_id'],
                    'entry_type_id' => $row['entry_type_id'],
                    'sub_voucher' => $row['sub_voucher'],
                    'entry_no' => $row['entry_no'],
                    'create_date' => $row['create_date'],
                    'ledger_ids_by_accounts' => $row['ledger_ids_by_accounts'],
                    'dr_amount' => $row['dr_amount'],
                    'cr_amount' => $row['cr_amount'],
                    'unit_price_dr' => $row['unit_price_dr'],
                    'unit_price_cr' => $row['unit_price_cr'],
                    'narration' => $row['narration'],
                    'voucher_no' => $row['voucher_no'],
                    'voucher_date' => $row['voucher_date'],
                    'status' => $row['status'],
                    'deleted' => $row['deleted'],
                    'is_inventry' => $row['is_inventry'],
                    'order_id' => $row['order_id'],
                    'type' => $row['type'],
                    'cancel_status' => $row['cancel_status'],
                    'order_status' => $this->checkOrderStatusForQuotation($row['entry_no']),
                );
                $entry[] = $item;
            }

            $data['all_entries'] = $all_entry;
            $data['dataCount'] = $this->inventorymodel->getCountQuotation();
        }

        $data['accounts_configuration'] = $this->inventorymodel->getPreferences();

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | ' . $entry_type['type']);
        $this->layouts->render('inventory/transaction_list', $data, 'admin');
    }

    public function salesAjaxListing(){
        $draw       = $this->input->get_post('draw');
        $start      = $this->input->get_post('start');
        $length     = $this->input->get_post('length');
        $search     = $this->input->get_post('search')['value'];

        $accounts_configuration = $this->inventorymodel->getPreferences();

        $name = $_GET['name'];
        $id = $_GET['eid'];
        $month = $_GET['emonth'];
        $entry_type=$this->inventorymodel->getVoucherType($id);
        $parent_id=$entry_type['parent_id'];
        $financial_year = get_financial_year();
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-t", strtotime(end($financial_year)));

        $result     = $this->inventorymodel->getAllEntry($id,$entry_type['parent_id'],$from_date, $to_date, $length, $start,$search);
        foreach($result AS $entry){
            $name = "";
            $del = '';
            $subArr = array();

            $subArr[] = get_date_format($entry['create_date']);
            $subArr[] = $entry['entry_no'];

            $l = "";
            if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $l = "<a href=" . base_url('transaction/invoice') . '.aspx/' . $entry['id'] . '/' . $entry['entry_type_id'].'/'.$entry['sub_voucher'] . ">";
                   if (empty($entry['ledger_detail'])){

                       $led = array();
                       $devit = json_decode($entry['ledger_ids_by_accounts']);

                       $l .= "<strong>Dr </strong>";
                       if (isset($devit->Dr)) {
                           for ($i = 0; $i < count($devit->Dr); $i++) {
                               $l .= $devit->Dr[$i];
                               if (isset($devit->Dr) && count($devit->Dr) > 1) {
                                   $l .= ' + ';
                               }
                               break;
                           }
                       }

                       $l .= '/';

                       $l .= "<strong>Cr </strong>";
                       for ($i = 0; $i < count($devit->Cr); $i++) {
                           $l .= $devit->Cr[$i];
                           if (count($devit->Cr) > 1) {
                               $l .= ' + ';
                           }
                           break;
                       }

                   } else {

                    $ld = explode('/',$entry['ledger_detail']);
                    $l .= "<strong>" . $ld[0] . " / " . $ld[1] . "</strong>";
                   }

                $l .= "</a>";
            }else{
                $l = '<span class="label label-danger"><b>Canceled</b></span>';
            }

            $subArr[] = $l;

            if($entry['entry_type_id'] == 5 || $parent_id == 5){
                $eway = "";
                if($entry['w_cancel_status']){
                    $eway .= '<a entry-id="'. $entry['id'].'" class="text-danger" href="javascript:void(0)"  id="eway_bill_canceled" >'. $entry['eway_bill_no'] .'</a>';
                }else{
                    $eway .= '<a entry-id="'. $entry['id'].'" class="text-success bold" href="javascript:void(0)"  id="eway_bill_no" >'. $entry['eway_bill_no'] .'</a>';
                }
                $subArr[] = $eway;
            }
            $subArr[] = $entry['type'];

            if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $subArr[] = $this->price_format($entry['cr_amount']);
            }else {
                $subArr[] = "";
            }

            $max_time = strtotime("+".$accounts_configuration->entry_action_limit." Days", strtotime($entry['create_date']));
            $t = time();
            if ($entry['entry_type_id'] == 5 || $parent_id == 5) {
                $module = 194;
            } elseif ($entry['entry_type_id'] == 6 || $parent_id == 6) {
                $module = 195;
            } elseif ($entry['entry_type_id'] == 7 || $parent_id == 7) {
                $module = 196;
            } elseif ($entry['entry_type_id'] == 8 || $parent_id == 8) {
                $module = 197;
            } elseif ($entry['entry_type_id'] == 10 || $parent_id == 10) {
                $module = 199;
            } elseif ($entry['entry_type_id'] == 9 || $parent_id == 9) {
                $module = 200;
            } elseif ($entry['entry_type_id'] == 14 || $parent_id == 14) {
                $module = 202;
            } elseif ($entry['entry_type_id'] == 12 || $parent_id == 12) {
                $module = 203;
            }
            if ($t < $max_time){
                if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $del = '<div class="dropdown circle">
                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-ellipsis-v"></i></a>
                    <ul class="dropdown-menu tablemenu">';

                    $permission = ua($module, 'edit');
                        if ($permission){

                            if ($entry['entry_type_id'] == 5 || $parent_id == 5){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/sales-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            } elseif ($entry['entry_type_id'] == 6 || $parent_id == 6){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/purchase-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            } elseif ($entry['entry_type_id'] == 14 || $parent_id == 14){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/credit-note-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            } elseif ($entry['entry_type_id'] == 12 || $parent_id == 12){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/debit-note-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            } elseif ($entry['entry_type_id'] == 7 || $parent_id == 7){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/sales-order-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                                if (isset($entry['order_status']) && ($entry['order_status'] == 'Open' || $entry['order_status'] == 'Partial')){
                                    $del .= '<li>
                                                <a href="'.site_url('transaction/sales-order-sales') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/t"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                            </li>';
                                }
                            } elseif ($entry['entry_type_id'] == 8 || $parent_id == 8) {
                                $del .= '<li>
                                            <a href="'.site_url('transaction/purchase-order-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                                if (isset($entry['order_status']) && ($entry['order_status'] == 'Open' || $entry['order_status'] == 'Partial')){
                                    $del .= '<li>
                                                <a href="'.site_url('transaction/purchase-order-purchase') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/t"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                            </li>';
                                }
                            } elseif ($entry['entry_type_id'] == 9 || $parent_id == 9){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/receive-note-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                                $del .= '<li>
                                            <a href="'.site_url('transaction/receive-note-purchase') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/t"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';

                            } elseif ($entry['entry_type_id'] == 10 || $parent_id == 10){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/delivery-note-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                                $del .= '<li>
                                            <a href="'.site_url('transaction/delivery-note-sales') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/t"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            }
                        }


                        $del .= '<li>';
                        $permission = ua($module, 'delete');
                        if ($permission){
                            if ($entry['entry_type_id'] == 5 || $entry['entry_type_id'] == 6 || $entry['entry_type_id'] == 12 || $entry['entry_type_id'] == 14){

                                $del .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="'. $entry['id'] . '" delete-type="current"  data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>';
                            } elseif ($entry['entry_type_id'] == 7 || $entry['entry_type_id'] == 8) {
                                $del .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="'. $entry['id'] . '" delete-type="current"  data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>';
                            } elseif ($entry['entry_type_id'] == 9 || $entry['entry_type_id'] == 10) {
                                $del .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="'. $entry['id'] . '" delete-type="current"  data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>';
                            }
                        }

                    $del .= '</li>
                            </ul>
                            </div>';
                }
            }
            $subArr[] = $del;

            $data['data'][] = $subArr;
        }
        $count = $this->inventorymodel->getCount($id,$entry_type['parent_id'],$search);
        $data['draw']              = $draw;
        $data['recordsTotal']      = $count;
        $data['recordsFiltered']   = $count;
        echo json_encode($data);exit;
    }

    public function recurringAjaxListing(){
        $draw       = $this->input->get_post('draw');
        $start      = $this->input->get_post('start');
        $length     = $this->input->get_post('length');
        $search     = $this->input->get_post('search')['value'];

        $accounts_configuration = $this->inventorymodel->getPreferences();

        $name = $_GET['name'];
        $id = $_GET['eid'];
        $month = $_GET['emonth'];
        $entry_type=$this->inventorymodel->getVoucherType($id);
        $parent_id=$entry_type['parent_id'];

        $result     = $this->inventorymodel->getAllRecurringEntry($id,$entry_type['parent_id'], $length, $start, $search);
        foreach($result AS $entry){
            $name = "";
            $del = '';
            $subArr = array();

            $subArr[] = get_date_format($entry['create_date']);
            $subArr[] = $entry['entry_no'];

            $l = "";
            // if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $l = "<a href=" . base_url('transaction/invoice') . '.aspx/' . $entry['id'] . '/' . $entry['entry_type_id'].'/'.$entry['sub_voucher'] . ">";
                   if (empty($entry['ledger_detail'])){

                       $led = array();
                       $devit = json_decode($entry['ledger_ids_by_accounts']);

                       $l .= "<strong>Dr </strong>";
                       if (isset($devit->Dr)) {
                           for ($i = 0; $i < count($devit->Dr); $i++) {
                               $l .= $devit->Dr[$i];
                               if (isset($devit->Dr) && count($devit->Dr) > 1) {
                                   $l .= ' + ';
                               }
                               break;
                           }
                       }

                       $l .= '/';

                       $l .= "<strong>Cr </strong>";
                       for ($i = 0; $i < count($devit->Cr); $i++) {
                           $l .= $devit->Cr[$i];
                           if (count($devit->Cr) > 1) {
                               $l .= ' + ';
                           }
                           break;
                       }

                   } else {

                    $ld = explode('/',$entry['ledger_detail']);
                    $l .= "<strong>" . $ld[0] . " / " . $ld[1] . "</strong>";
                   }

                $l .= "</a>";
            // }else{
            //     $l = '<span class="label label-danger"><b>Canceled</b></span>';
            // }

            $subArr[] = $l;

            $subArr[] = $entry['type'];

            if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $subArr[] = $this->price_format($entry['cr_amount']);
            }else {
                $subArr[] = "";
            }

            $max_time = strtotime("+".$accounts_configuration->entry_action_limit." Days", strtotime($entry['create_date']));
            $t = time();
            if ($entry['entry_type_id'] == 5 || $parent_id == 5) {
                $module = 194;
            } elseif ($entry['entry_type_id'] == 6 || $parent_id == 6) {
                $module = 195;
            } elseif ($entry['entry_type_id'] == 7 || $parent_id == 7) {
                $module = 196;
            } elseif ($entry['entry_type_id'] == 8 || $parent_id == 8) {
                $module = 197;
            } elseif ($entry['entry_type_id'] == 10 || $parent_id == 10) {
                $module = 199;
            } elseif ($entry['entry_type_id'] == 9 || $parent_id == 9) {
                $module = 200;
            } elseif ($entry['entry_type_id'] == 14 || $parent_id == 14) {
                $module = 202;
            } elseif ($entry['entry_type_id'] == 12 || $parent_id == 12) {
                $module = 203;
            }
            if ($t < $max_time){
                if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $del = '<div class="dropdown circle">
                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-ellipsis-v"></i></a>
                    <ul class="dropdown-menu tablemenu">';

                    $permission = ua($module, 'edit');
                        if ($permission){

                            if ($entry['entry_type_id'] == 5 || $parent_id == 5){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/sales-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            } elseif ($entry['entry_type_id'] == 6 || $parent_id == 6){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/purchase-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            } elseif ($entry['entry_type_id'] == 14 || $parent_id == 14){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/credit-note-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            } elseif ($entry['entry_type_id'] == 12 || $parent_id == 12){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/debit-note-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            } elseif ($entry['entry_type_id'] == 7 || $parent_id == 7){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/sales-order-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                                if (isset($entry['order_status']) && ($entry['order_status'] == 'Open' || $entry['order_status'] == 'Partial')){
                                    $del .= '<li>
                                                <a href="'.site_url('transaction/sales-order-sales') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/t"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                            </li>';
                                }
                            } elseif ($entry['entry_type_id'] == 8 || $parent_id == 8) {
                                $del .= '<li>
                                            <a href="'.site_url('transaction/purchase-order-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                                if (isset($entry['order_status']) && ($entry['order_status'] == 'Open' || $entry['order_status'] == 'Partial')){
                                    $del .= '<li>
                                                <a href="'.site_url('transaction/purchase-order-purchase') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/t"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                            </li>';
                                }
                            } elseif ($entry['entry_type_id'] == 9 || $parent_id == 9){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/receive-note-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                                $del .= '<li>
                                            <a href="'.site_url('transaction/receive-note-purchase') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/t"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';

                            } elseif ($entry['entry_type_id'] == 10 || $parent_id == 10){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/delivery-note-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                                $del .= '<li>
                                            <a href="'.site_url('transaction/delivery-note-sales') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/t"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            }
                        }


                        $del .= '<li>';
                        $permission = ua($module, 'delete');
                        if ($permission){
                            if ($entry['entry_type_id'] == 5 || $entry['entry_type_id'] == 6 || $entry['entry_type_id'] == 12 || $entry['entry_type_id'] == 14){

                                $del .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="'. $entry['id'] . '" delete-type="current"  data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>';
                            } elseif ($entry['entry_type_id'] == 7 || $entry['entry_type_id'] == 8) {
                                $del .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="'. $entry['id'] . '" delete-type="current"  data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>';
                            } elseif ($entry['entry_type_id'] == 9 || $entry['entry_type_id'] == 10) {
                                $del .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="'. $entry['id'] . '" delete-type="current"  data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>';
                            }
                        }

                    $del .= '</li>
                            </ul>
                            </div>';
                }
            }
            $subArr[] = $del;

            $data['data'][] = $subArr;
        }
        $count = $this->inventorymodel->getRecurringCount($id,$entry_type['parent_id'],$search);
        $data['draw']              = $draw;
        $data['recordsTotal']      = $count;
        $data['recordsFiltered']   = $count;
        $data['name'] = $name;
        $data['id'] = $id;
        $data['month'] = $month;
        echo json_encode($data);exit;
    }

    public function postdatedAjaxListing(){
        $draw       = $this->input->get_post('draw');
        $start      = $this->input->get_post('start');
        $length     = $this->input->get_post('length');
        $search     = $this->input->get_post('search')['value'];

        $accounts_configuration = $this->inventorymodel->getPreferences();

        $name = $_GET['name'];
        $id = $_GET['eid'];
        $month = $_GET['emonth'];
        $entry_type=$this->inventorymodel->getVoucherType($id);
        $parent_id=$entry_type['parent_id'];

        $result     = $this->inventorymodel->getAllPostDatedEntry($id,$entry_type['parent_id'], $length, $start,$search);
        foreach($result AS $entry){
            $name = "";
            $del = '';
            $subArr = array();

            $subArr[] = get_date_format($entry['create_date']);
            $subArr[] = $entry['entry_no'];

            $l = "";
            // if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $l = "<a href=" . base_url('transaction/invoice') . '.aspx/' . $entry['id'] . '/' . $entry['entry_type_id'].'/'.$entry['sub_voucher'] . ">";
                   if (empty($entry['ledger_detail'])){

                       $led = array();
                       $devit = json_decode($entry['ledger_ids_by_accounts']);

                       $l .= "<strong>Dr </strong>";
                       if (isset($devit->Dr)) {
                           for ($i = 0; $i < count($devit->Dr); $i++) {
                               $l .= $devit->Dr[$i];
                               if (isset($devit->Dr) && count($devit->Dr) > 1) {
                                   $l .= ' + ';
                               }
                               break;
                           }
                       }

                       $l .= '/';

                       $l .= "<strong>Cr </strong>";
                       for ($i = 0; $i < count($devit->Cr); $i++) {
                           $l .= $devit->Cr[$i];
                           if (count($devit->Cr) > 1) {
                               $l .= ' + ';
                           }
                           break;
                       }

                   } else {

                    $ld = explode('/',$entry['ledger_detail']);
                    $l .= "<strong>" . $ld[0] . " / " . $ld[1] . "</strong>";
                   }

                $l .= "</a>";
            // }else{
            //     $l = '<span class="label label-danger"><b>Canceled</b></span>';
            // }

            $subArr[] = $l;

            $subArr[] = $entry['type'];

            if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $subArr[] = $this->price_format($entry['cr_amount']);
            }else {
                $subArr[] = "";
            }

            if ($entry['create_date'] == date("Y-m-d")){
                $subArr[] = '<span class="postdated-status" data-toggle="tooltip" title="Add Now"><i class="fa fa-check-circle" aria-hidden="true"></i></span>';
            } else {
                $subArr[] = "";
            }

            $max_time = strtotime("+".$accounts_configuration->entry_action_limit." Days", strtotime($entry['create_date']));
            $t = time();
            if ($entry['entry_type_id'] == 5 || $parent_id == 5) {
                $module = 194;
            } elseif ($entry['entry_type_id'] == 6 || $parent_id == 6) {
                $module = 195;
            } elseif ($entry['entry_type_id'] == 7 || $parent_id == 7) {
                $module = 196;
            } elseif ($entry['entry_type_id'] == 8 || $parent_id == 8) {
                $module = 197;
            } elseif ($entry['entry_type_id'] == 10 || $parent_id == 10) {
                $module = 199;
            } elseif ($entry['entry_type_id'] == 9 || $parent_id == 9) {
                $module = 200;
            } elseif ($entry['entry_type_id'] == 14 || $parent_id == 14) {
                $module = 202;
            } elseif ($entry['entry_type_id'] == 12 || $parent_id == 12) {
                $module = 203;
            }
            if ($t < $max_time){
                if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $del = '<div class="dropdown circle">
                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-ellipsis-v"></i></a>
                    <ul class="dropdown-menu tablemenu">';

                    $permission = ua($module, 'edit');
                        if ($permission){

                            if ($entry['entry_type_id'] == 5 || $parent_id == 5){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/sales-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            } elseif ($entry['entry_type_id'] == 6 || $parent_id == 6){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/purchase-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            } elseif ($entry['entry_type_id'] == 14 || $parent_id == 14){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/credit-note-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            } elseif ($entry['entry_type_id'] == 12 || $parent_id == 12){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/debit-note-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            } elseif ($entry['entry_type_id'] == 7 || $parent_id == 7){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/sales-order-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                                if (isset($entry['order_status']) && ($entry['order_status'] == 'Open' || $entry['order_status'] == 'Partial')){
                                    $del .= '<li>
                                                <a href="'.site_url('transaction/sales-order-sales') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/t"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                            </li>';
                                }
                            } elseif ($entry['entry_type_id'] == 8 || $parent_id == 8) {
                                $del .= '<li>
                                            <a href="'.site_url('transaction/purchase-order-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                                if (isset($entry['order_status']) && ($entry['order_status'] == 'Open' || $entry['order_status'] == 'Partial')){
                                    $del .= '<li>
                                                <a href="'.site_url('transaction/purchase-order-purchase') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/t"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                            </li>';
                                }
                            } elseif ($entry['entry_type_id'] == 9 || $parent_id == 9){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/receive-note-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                                $del .= '<li>
                                            <a href="'.site_url('transaction/receive-note-purchase') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/t"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';

                            } elseif ($entry['entry_type_id'] == 10 || $parent_id == 10){
                                $del .= '<li>
                                            <a href="'.site_url('transaction/delivery-note-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                                $del .= '<li>
                                            <a href="'.site_url('transaction/delivery-note-sales') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/t"' . 'data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                        </li>';
                            }
                        }


                        $del .= '<li>';
                        $permission = ua($module, 'delete');
                        if ($permission){
                            if ($entry['entry_type_id'] == 5 || $entry['entry_type_id'] == 6 || $entry['entry_type_id'] == 12 || $entry['entry_type_id'] == 14){

                                $del .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="'. $entry['id'] . '" delete-type="current"  data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>';
                            } elseif ($entry['entry_type_id'] == 7 || $entry['entry_type_id'] == 8) {
                                $del .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="'. $entry['id'] . '" delete-type="current"  data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>';
                            } elseif ($entry['entry_type_id'] == 9 || $entry['entry_type_id'] == 10) {
                                $del .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="'. $entry['id'] . '" delete-type="current"  data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>';
                            }
                        }

                    $del .= '</li>
                            </ul>
                            </div>';
                }
            }
            $subArr[] = $del;

            $data['data'][] = $subArr;
        }
        $count = $this->inventorymodel->getAllPostDatedEntryCount($id,$entry_type['parent_id'],$search);
        $data['draw']              = $draw;
        $data['recordsTotal']      = $count;
        $data['recordsFiltered']   = $count;
        $data['name'] = $name;
        $data['id'] = $id;
        $data['month'] = $month;
        echo json_encode($data);exit;
    }

    public function getROrderDetails($id,$parent_id, $limit=0, $offset=0) {
        $all_entry = $this->inventorymodel->getAllRequestEntry($id,$parent_id, $limit, $offset);
        $entry = [];
        foreach ($all_entry as $row) {
            $item = array(
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'company_id' => $row['company_id'],
                'entry_type_id' => $row['entry_type_id'],
                'sub_voucher' => $row['sub_voucher'],
                'entry_no' => $row['entry_no'],
                'create_date' => $row['create_date'],
                'ledger_ids_by_accounts' => $row['ledger_ids_by_accounts'],
                'dr_amount' => $row['dr_amount'],
                'cr_amount' => $row['cr_amount'],
                'unit_price_dr' => $row['unit_price_dr'],
                'unit_price_cr' => $row['unit_price_cr'],
                'narration' => $row['narration'],
                'voucher_no' => $row['voucher_no'],
                'voucher_date' => $row['voucher_date'],
                'status' => $row['status'],
                'deleted' => $row['deleted'],
                'is_inventry' => $row['is_inventry'],
                'order_id' => $row['order_id'],
                'type' => $row['type'],
                'cancel_status' => $row['cancel_status'],
                'order_status' => $this->checkOrderStatus($row['entry_no']),
            );
            $entry[] = $item;
        }
        return $entry;

    }

    public function checkOrderStatus($entry_no){
        $order_details = $this->inventorymodel->getOrderDetailsByEno($entry_no);
        $sales_details = $this->inventorymodel->getSalesDetails($entry_no);
        $sales_qty_arr = [];
        foreach ($sales_details as $value) {
            $sales_qty_arr[$value->product_id][$value->stock_id] = $value->TotalQuantity;
        }
        foreach ($order_details as $row) {
            $sales_qty = isset($sales_qty_arr[$row->product_id][$row->stock_id]) ? $sales_qty_arr[$row->product_id][$row->stock_id] : 0;
            if(($row->quantity - $sales_qty)>0){
            if($sales_qty==0){
             return 'Open';
            }else{
            return 'Partial';
            }
            }else{
            return 'Completed';
            }
        }

    }

    public function checkOrderStatusForQuotation($entry_no){
        $order_details = $this->inventorymodel->getQuotationOrderDetailsByEno($entry_no);
        $sales_details = $this->inventorymodel->getSalesDetails($entry_no);
        $sales_qty_arr = [];
        foreach ($sales_details as $value) {
            $sales_qty_arr[$value->product_id][$value->stock_id] = $value->TotalQuantity;
        }
        foreach ($order_details as $row) {
            $sales_qty = isset($sales_qty_arr[$row->product_id][$row->stock_id]) ? $sales_qty_arr[$row->product_id][$row->stock_id] : 0;
            if(($row->quantity - $sales_qty)>0){
            if($sales_qty==0){
             return 'Open';
            }else{
            return 'Partial';
            }
            }else{
            return 'Completed';
            }
        }

    }

    public function transaction_form_php($id = NULL, $type_id = null, $action = null){
      //access
        if($action=='a'){
       $ac='add';
       }elseif($action=='e'){
        $ac='edit';
       }elseif($action=='c'){
         $ac='add';
       }elseif($action=='t'){
         $ac='edit';
       }

        $entry_type=$this->inventorymodel->getVoucherType($type_id);
        if($type_id==5 || $entry_type['parent_id'] == 5){
            $module=194;
        }elseif ($type_id==6 || $entry_type['parent_id'] == 6) {
         $module=195;
        }elseif ($type_id==7 || $entry_type['parent_id'] == 7) {
         $module=196;
        }elseif ($type_id==8 || $entry_type['parent_id'] == 8) {
         $module=197;
        }elseif ($type_id==10 || $entry_type['parent_id'] == 10) {
         $module=199;
        }elseif ($type_id==9 || $entry_type['parent_id'] == 9) {
         $module=200;
        }elseif ($type_id==14 || $entry_type['parent_id'] == 14) {
         $module=202;
        }elseif ($type_id==12 || $entry_type['parent_id'] == 12) {
         $module=203;
        }


        user_permission($module,$ac);
        //access
        $data = array();
        $data['action'] = $action;
        $entry_type_id = $type_id;
        $data['entry_type'] = $type_id;
        $data['bank_id'] = '';
        $entry_type=$this->inventorymodel->getEntryTypeById($type_id);
        $bank_details=$this->inventorymodel->getDefauldBankId();
        $date_type = $this->inventorymodel->checkAutoDate();

        $parent_id=$entry_type['parent_id'];
        $data['parent_id'] = $parent_id;
        if ($action == 'e' || $action == 't') {
            if ($entry_type_id == 5 || $entry_type_id == 6 || $parent_id==5 || $parent_id==6) {
                if ($entry_type_id == 5 || $parent_id==5) {
                    $order_type = 1;
                } else {
                    $order_type = 2;
                }
                $entry = $this->inventorymodel->getEntry($id);
                $data['entry'] = $entry;
                if ($entry->is_reverse_entry == 1 && ($entry_type_id == 6 || $parent_id==6)) {
                    $data['entry_details'] = $this->inventorymodel->getComputationEntryDetails($id);
                } else {
                    $data['entry_details'] = $this->inventorymodel->getEntryDetails($id);
                }

                if ($entry->is_service_product == 0) {
                    $order = $this->inventorymodel->getOrder($id, $order_type);
                    $data['order'] = $order;
                    $data['order_details'] = $this->inventorymodel->getOrderDetails($order->id);

                    //For batch and godown
                    if($date_type['batch'] == 1){
                        $data['batch_details'] = $this->inventorymodel->getBatchDetailsByStockId($order->id);
                        $data['godown_details'] = $this->inventorymodel->getGodownDetailsStockId($order->id);
                    }else if($date_type['godown'] == 1){
                        $data['godown_details'] = $this->inventorymodel->getGodownDetailsStockId($order->id);
                    }

                } else {
                    $order = $this->inventorymodel->getTempOrder($id, $order_type);

                    $data['order'] = $order;
                    $data['order_details'] = $this->inventorymodel->getTempOrderDetails($order->id);
                }

                //bank detauls update
                $data['bank_id'] = (isset($entry->bank_id) && $entry->bank_id != 0)?$entry->bank_id:isset($bank_details->id)?$bank_details->id:'';
                //courier detaild
                $data['courier'] = $this->inventorymodel->getDespatchDetailsByEntryId($id);

            } else if ($entry_type_id == 14 || $entry_type_id == 12 || $parent_id==14 || $parent_id==12) {
                if ($entry_type_id == 14 || $parent_id==14) {
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
                    $data['order_details'] = $this->inventorymodel->getOrderDetails($order->id);

                     //For batch and godown
                    if($date_type['batch'] == 1){
                        $data['batch_details'] = $this->inventorymodel->getBatchDetailsByStockId($order->id);
                        $data['godown_details'] = $this->inventorymodel->getGodownDetailsStockId($order->id);
                    }else if($date_type['godown'] == 1){
                        $data['godown_details'] = $this->inventorymodel->getGodownDetailsStockId($order->id);
                    }

                } else {
                    $order = $this->inventorymodel->getTempOrder($id, $order_type);

                    $data['order'] = $order;
                    $data['order_details'] = $this->inventorymodel->getTempOrderDetails($order->id);
                }
            $data['bank_id'] = (isset($entry->bank_id) && $entry->bank_id != 0)?$entry->bank_id:isset($bank_details->id)?$bank_details->id:'';

            } elseif ($entry_type_id == 7 || $entry_type_id == 8 || $parent_id==7 || $parent_id==8) {
                $entry = $this->inventorymodel->getRequestEntry($id);
                $data['entry'] = $entry;
                $data['entry_details'] = $this->inventorymodel->getRequestEntryDetails($id);
                $order = $this->inventorymodel->getRequestOrder($id);
                $data['order'] = $order;
                if (($entry_type_id == 7 || $entry_type_id == 8 || $parent_id==7 || $parent_id==8) && $action == 't') {
                    $data['order_details'] = $this->getOrderDetails($order->id, $entry->entry_no);
                } else {
                    $data['order_details'] = $this->inventorymodel->getRequestOrderDetails($order->id);

                }
            } elseif ($entry_type_id == 9 || $entry_type_id == 10 || $parent_id==9 || $parent_id==10) {
                if ($entry_type_id == 10 || $parent_id==10) {
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
                $data['bank_id'] = (isset($entry->bank_id) && $entry->bank_id != 0)?$entry->bank_id:isset($bank_details->id)?$bank_details->id:'';

                //For batch and godown
                if($date_type['batch'] == 1){
                    $data['batch_details'] = $this->inventorymodel->getBatchDetailsByStockId($order->id);
                    $data['godown_details'] = $this->inventorymodel->getGodownDetailsStockId($order->id);
                }else if($date_type['godown'] == 1){
                    $data['godown_details'] = $this->inventorymodel->getGodownDetailsStockId($order->id);
                }
            }
        }else{
            $data['bank_id'] = isset($bank_details->id)?$bank_details->id:'';
        }
        if (($entry_type_id == 7 || $parent_id==7) && $action == 't') {
            $entry_type_id = 5;
        } else {
            $entry_type_id == 7;
        }if (($entry_type_id == 8 || $parent_id==8) && $action == 't') {
            $entry_type_id = 6;
        } else {
            $entry_type_id == 8;
        }
        $data['voucher'] = $entry_type['type'];
        $data['voucher_id'] = $entry_type_id;
        $data['transaction_type_id'] = $entry_type_id;
        $data['auto_no_status'] = $entry_type['transaction_no_status'];

        $data['auto_date'] = $date_type['skip_date_create'];
        $data['recurring'] = $date_type['want_recurring'];
        $data['godown_status'] = $date_type['godown'];
        $data['batch_status'] = $date_type['batch'];
        $ledger_code_status = $this->inventorymodel->getLedgerCodeStatus();
        $data['ledger_code_status'] = $ledger_code_status['ledger_code_status'];
        $data['groups'] = $this->inventorymodel->getAllGroups();
        $data['contacts'] = $this->account->getContact();
        $data['currency'] = $this->inventorymodel->getAllCurrency();


        // country and state for contact form
        $this->load->model('customer_details/admin/customer_details');
        $data['country']    = $this->customer_details->getAllCountry();
        $data['states']     = $this->customer_details->getAllState(101);

        if ($action == 'e' || $action == 't') {
            $cash_bank_group = [10,11];
                $all_sub_group = $this->inventorymodel->getAllSubGroup($cash_bank_group);
                for ($i = 0; $i < count($cash_bank_group); $i++) {
                    array_push($all_sub_group, $cash_bank_group[$i]);
                }
                $ledgerIdArr = $this->inventorymodel->getLedgerByGroupsId($all_sub_group);

            if(!in_array($data['entry_details'][0]->ladger_id,array_column($ledgerIdArr,'id'))){
                    //shipping
                    $ledgerId = $data['entry_details'][0]->ladger_id;
                    $ledgerLimitDetails = $this->inventorymodel->ledgerLimitDetails($ledgerId);

                     /* For Ledger Limits */
                    $arr['LL_creditLimit'] = $ledgerLimitDetails->credit_limit;
                    $arr['LL_creditDays'] = $ledgerLimitDetails->credit_date;



//                    // $shippingAddress = $this->inventorymodel->getShippingAddress($ledgerId);
                    $ship = $this->inventorymodel->getShippingAddress($ledgerId);
                    if (!empty($ship)) {
                        $shippingAddress = $ship[0];
                    } else {
                        $shippingAddress = array();
                    }
//
//                    $getCountryName = $this->inventorymodel->getCountryName($shippingAddress->country)->name;
//                    $getStateName = $this->inventorymodel->getStateName($shippingAddress->state)->name;

//        //            $billingAddress = $this->inventorymodel->getBillingAddress($ledgerId);
//        //            if (!empty($billingAddress->country)) {
//        //                $getCountryNameBilling = $this->inventorymodel->getCountryName($billingAddress->country)->name;
//        //            } else {
//        //                $getCountryNameBilling = "";
//        //            }
//        //            $getStateNameBilling = $this->inventorymodel->getStateName($billingAddress->state_id)->name;
//
//
//                    $countryId = $shippingAddress->country;
//                    $stateId = $shippingAddress->state;

                    $arr['country'] = $order->shipping_country;
                    $arr['state'] = $order->shipping_state;
                    /* For Shipping Addr */

                    $arr['Sh_companyName'] = $order->shipping_first_name;
                    $arr['Sh_address'] = $order->shipping_address;
                    $arr['Sh_city'] = $order->shipping_city;
                    $arr['Sh_zip'] = $order->shipping_zip;
                    $arr['Sh_state'] = $order->shipping_state_name;
                    $arr['Sh_country'] = $order->shipping_country_name;

                    /* For Billing Addr */

                    $arr['Bi_companyName'] = $order->billing_first_name;
                    $arr['Bi_address'] = $order->billing_address;
                    $arr['Bi_city'] = $order->billing_city;
                    $arr['Bi_zip'] = $order->billing_zip;
                    $arr['Bi_state'] = $order->billing_state_name;
                    $arr['Bi_country'] = $order->billing_country_name;
                    $arr['Bi_tax'] = $shippingAddress->sales_tax_no;
                    $data['ledger'] = $arr;
                    //billing


                }else{
                    $company_details = $this->inventorymodel->getBranchDetails();
                    $arr['sale_type'] = 'cash';
                    $arr['country'] = $company_details->country_id;
                    $arr['state'] = $company_details->state_id;
                    $arr['Bi_state'] = $company_details->state_name;

                    /* For Shipping Addr */
                    $arr['Bi_companyName'] = $order->billing_first_name;

                    $arr['Sh_companyName'] = $order->shipping_first_name;
                    $arr['Sh_address'] = $order->shipping_address;
                    $arr['Sh_city'] = $order->shipping_city;
                    $arr['Sh_zip'] = $order->shipping_zip;
                    $arr['Sh_state'] = $order->shipping_state_name;
                    $arr['Sh_country'] = $order->shipping_country_name;

                    $data['ledger'] = $arr;

                }
        }
        $getsitename = getsitename();
        if ($action == 'e') {
            $this->layouts->set_title($getsitename . ' | Transaction:: Update');
        } else {
            $this->layouts->set_title($getsitename . ' | Transaction:: Add');
        }

//        echo "<pre>";
//        print_r($data);
//        die();
        $this->layouts->render('inventory/transaction_form', $data, 'admin_new');
    }

    public function transaction_form($id = NULL, $type_id = null, $action = null){
      $data = [];
      $getsitename = getsitename();
      $this->layouts->set_title($getsitename . ' | Transaction:: Add');
      $this->layouts->render('inventory/transaction_form_html', $data, 'admin_new');
    }

    public function getOrderDetails($order_id, $entry_no) {
        $order_details = $this->inventorymodel->getRequestOrderDetails($order_id);
        $sales_details = $this->inventorymodel->getSalesDetails($entry_no);
        $sales_qty_arr = [];
        $final_array = [];
        foreach ($sales_details as $value) {
            $sales_qty_arr[$value->product_id][$value->stock_id] = $value->TotalQuantity;
        }
        foreach ($order_details as $row) {

            $sales_qty = isset($sales_qty_arr[$row->product_id][$row->stock_id]) ? $sales_qty_arr[$row->product_id][$row->stock_id] : 0;
            $qty = ($row->quantity - $sales_qty);
            $price = ($row->base_price * $qty);
            $price = ((($row->base_price * $qty)*(100-$row->discount_percentage))/100);
            $igst = ($row->igst_tax_percent / 100) * $price;
            $sgst = ($row->sgst_tax_percent / 100) * $price;
            $cgst = ($row->cgst_tax_percent / 100) * $price;
            $total_tax = $igst + $sgst + $cgst;
            $cess = ($row->cess_tax_percent / 100) * $total_tax;
            $item = (object) array(
                        'id' => $row->id,
                        'order_id' => $row->order_id,
                        'product_id' => $row->product_id,
                        'product_description' => $row->product_description,
                        'stock_id' => $row->stock_id,
                        'product_attribute' => $row->product_attribute,
                        'hsn_code' => $row->hsn_code,
                        'quantity' => $qty,
                        'original_price' => $row->original_price,
                        'base_price' => $row->base_price,
                        'discount_json' => $row->discount_json,
                        'discount_percentage' => $row->discount_percentage,
                        'discount_amount' => $row->discount_amount,
                        'igst_tax_percent' => intval($row->igst_tax_percent),
                        'igst_tax' => $igst,
                        'sgst_tax_percent' => intval($row->sgst_tax_percent),
                        'sgst_tax' => $sgst,
                        'cgst_tax_percent' => intval($row->cgst_tax_percent),
                        'cgst_tax' => $cgst,
                        'cess_tax_percent' => intval($row->cess_tax_percent),
                        'cess_tax' => $cess,
                        'price' => $price,
                        'total' => ($price + $total_tax),
                        'creation_date' => $row->creation_date,
                        'modified_date' => $row->modified_date,
                        'status' => $row->status,
                        'on_off' => $row->on_off,
                        'return_reason' => $row->return_reason,
                        'return_status' => $row->return_status,
                        'request_date' => $row->request_date,
                        'approved_disapproved_date' => $row->approved_disapproved_date,
                        'receive_date' => $row->receive_date,
                        'refund_date' => $row->refund_date,
                        'flow_type' => $row->flow_type,
                        'order_type' => $row->order_type,
                        'entry_id' => $row->entry_id,
                        'terms_and_conditions' => $row->terms_and_conditions,
                        'stockdet' => $row->stockdet,
                        'name' => $row->name,
                        'unit_name' => $row->unit_name,
                        'hsn_number' => $row->hsn_number
            );
            $final_array[] = $item;
        }
        return $final_array;
    }

    public function getLedgerDebtors() {
        if ($this->input->is_ajax_request()) {
            $tran_type = $_POST['tran_type'];
            $entry_type=$this->inventorymodel->getVoucherType($tran_type);
            $parent_id=$entry_type['parent_id'];
//            if ($tran_type == 5 || $tran_type == 7 || $tran_type == 10 || $tran_type == 14 || $parent_id == 5 || $parent_id == 7 || $parent_id == 10 || $parent_id == 14) {
//                $group_id = 15; //sunday debitor
//            } else if ($tran_type == 6 || $tran_type == 8 || $tran_type == 9 || $tran_type == 12 || $parent_id == 6 || $parent_id == 8 || $parent_id == 9 || $parent_id == 12) {
//                $group_id = 23; //sunday creditor
//            }
            switch(TRUE){
                case ($tran_type == 5 || $tran_type == 14 || $parent_id == 5 || $parent_id == 14) :
                    $arr_group_id = [15,10,11];
                    break;
                case ($tran_type == 25 || $tran_type == 7 || $tran_type == 10 || $parent_id == 7 || $parent_id == 10 || $parent_id == 25) :
                   $arr_group_id = [15];
                   break;
                case ($tran_type == 6|| $tran_type == 12 || $parent_id == 6 || $parent_id == 12) :
                    $arr_group_id = [23,10,11];
                    break;
                case ($tran_type == 8 || $tran_type == 9 ||$parent_id == 8 || $parent_id == 9) :
                    $arr_group_id = [23];
                    break;
            }

            $branch_group_id=15;
//            $all_group_id = $group_id;
//            $arr_group_id=[$group_id,$branch_group_id];
            array_push($arr_group_id, $branch_group_id);
            $all_sub_group = $this->inventorymodel->getAllSubGroup($arr_group_id);

            for ($i = 0; $i < count($arr_group_id); $i++) {
                array_push($all_sub_group, $arr_group_id[$i]);
            }
            $ledger = $_POST['ledger'];
            $ledger_details = array();

            $ledger_details = $this->inventorymodel->getLedger($ledger, $all_sub_group);
            $ledgerName = '';

            $ledgerName = '[';

            foreach ($ledger_details as $value) {
                //For closing balance
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

                $ledgerName .= " { \"label\": \"$value->ladger_name ($value->ledger_code)   [Cur. Bal. $current_closing_balance - $account_type]\", \"value\": \"$value->id\"},";
            }

            $ledgerName = substr($ledgerName, 0, -1);
            $ledgerName .= ' ]';

            echo $ledgerName;
        }
    }

    public function getShippingDetails() {
        $cash_bank_group = [10,11];

        $ledgerId = $_POST['ledger'];
        $arr = array();

        $transactionType = $this->inventorymodel->getTType($ledgerId);

        $shippingDetails = $this->inventorymodel->getShippingDet($ledgerId);

        $ledgerLimitDetails = $this->inventorymodel->ledgerLimitDetails($ledgerId);

        $billAddress = $this->inventorymodel->getLedgerContactDetails($ledgerId);

//         $shippingAddress = $this->inventorymodel->getShippingAddress($ledgerId);
        $ship = $this->inventorymodel->getShippingAddress($ledgerId);
        if(!empty($ship)){
            $shippingAddress = $ship[0];
        }else{
            $shippingAddress = FALSE;
        }


        if ($shippingAddress) {
            $arr['shipping'] = true;
            $getCountryName = $this->inventorymodel->getCountryName($shippingAddress->country)->name;
            $getStateName = $this->inventorymodel->getStateName($shippingAddress->state)->name;

            $billingAddress = $this->inventorymodel->getBillingAddress($ledgerId);


//        if (!empty($billingAddress->country)) {
//            $getCountryNameBilling = $this->inventorymodel->getCountryName($billingAddress->country)->name;
//        } else {
//            $getCountryNameBilling = "";
//        }
//
//        $getStateNameBilling = $this->inventorymodel->getStateName($billingAddress->state_id)->name;



            $countryId = $shippingAddress->country;
            $stateId = $shippingAddress->state;

            $arr['country'] = $countryId;
            $arr['state'] = $stateId;

            /* Company Type IF company_type == 1 ? texapply = 1 eles texapply = 1 */

            $arr['companyType'] = $shippingAddress->company_type;

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
            $arr['Sh_id'] = $shippingAddress->id;


//        same
            $arr['Bi_companyName'] = $shippingAddress->company_name;
            $arr['Bi_address'] = $billAddress->address;
            $arr['Bi_city'] = $billAddress->city;
            $arr['Bi_zip'] = $billAddress->zipcode;
            $arr['Bi_state'] = $billAddress->state_name;
            $arr['Bi_country'] = $billAddress->country_name;
            $arr['Bi_tax'] = $shippingAddress->sales_tax_no;

            $arr['multiple_ship'] = $ship;
        } else {
            $arr['sale_type'] = 'cash';
            //For Cash and bank group
            $all_sub_group = $this->inventorymodel->getAllSubGroup($cash_bank_group);
            for ($i = 0; $i < count($cash_bank_group); $i++) {
                array_push($all_sub_group, $cash_bank_group[$i]);
            }
            $ledgersId = $this->inventorymodel->getLedgerByGroupsId($all_sub_group);
            if(in_array($ledgerId,array_column($ledgersId,'id'))){
                $company_details = $this->inventorymodel->getBranchDetails();
                $arr['sale_type'] = 'cash';
                $arr['country'] = $company_details->country_id;
                $arr['state'] = $company_details->state_id;
                $arr['state_name'] = $company_details->state_name;
            }else{
                $arr['shipping'] = false;
                $arr['message'] = "Please add the shipping address for this Ledger.";
            }

        }

        /* For Billing Addr */
//        $arr['Bi_companyName'] = $billingAddress->company_name;
//        $arr['Bi_address'] = $billingAddress->street_address;
//        $arr['Bi_city'] = $billingAddress->city_name;
//        $arr['Bi_zip'] = $billingAddress->zip_code;
//        $arr['Bi_state'] = $getStateNameBilling;
//        $arr['Bi_country'] = $getCountryNameBilling;
//        $arr['Bi_tax'] = $billingAddress->service_tax;

        $arr['transactionType'] = $transactionType->account_type;

        $arr['tr_branch_id'] = $this->session->userdata('branch_id');

        echo json_encode($arr);
    }

    public function getLedgerSales() {

        if ($this->input->is_ajax_request()) {
            $tran_type = $_POST['tran_type'];
            $entry_type=$this->inventorymodel->getVoucherType($tran_type);
            $parent_id=$entry_type['parent_id'];
            if ($tran_type == 5 || $tran_type == 25 || $tran_type == 7 || $tran_type == 10 || $tran_type == 14 || $parent_id == 5 || $parent_id == 7 || $parent_id == 10 || $parent_id == 14 || $parent_id == 25) {
                $array_group = [37]; // sales account
            } else if ($tran_type == 6 || $tran_type == 8 || $tran_type == 9 || $tran_type == 12 || ($parent_id == 6 && $tran_type != 15 ) || $parent_id == 8 || $parent_id == 9 || $parent_id == 12) {
                $array_group = [32]; // purchase account
            }else if($tran_type == 15){
                $array_group = [30];//service expenses
            }
            $ledger = $_POST['ledger'];
            $ledger_details = array();

            $ledger_details = $this->inventorymodel->getSalesLedger($array_group);

            for ($i = 0; $i < count($array_group); $i++) {
                array_push($ledger_details, $array_group[$i]);
            }



            $ledger_final = $this->inventorymodel->getLedgerFinalIn($ledger, $ledger_details);

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


                $ledgerName .= " { \"label\": \"$value->ladger_name ($value->ledger_code)  [Cur. Bal. $current_closing_balance - $account_type]\", \"value\": \"$value->id\"},";
            }

            $ledgerName = substr($ledgerName, 0, -1);
            $ledgerName .= ' ]';

            echo $ledgerName;
        }
    }

    public function ajax_update_transaction() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $advance_bill_name = $this->input->post('advance_bill_name');
            $reverse_entry = $this->input->post('reverse_entry');
            $type_service_product = $this->input->post('type_service_product');
            $recurring_freq = $this->input->post('recurring_freq');
            $postdated = $this->input->post('postdated');
            $selectedCurrency = $this->input->post('currency');
            $entry_id = $this->input->post('entry_id');
            $entry_number = $this->input->post('entry_number');
            $date_form = $this->input->post('date_form');
            $created_date = date("Y-m-d", strtotime(str_replace('/', '-', $date_form)));
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

            //Branch id
            $branch_id = $this->session->userdata('branch_id');

            //For Product
            $product_id = $this->input->post('product_id');
            $stock_id = $this->input->post('stock_id');
            $product_name = $this->input->post('product_name');
            $product_description = $this->input->post('product_description');//19042018
            $product_quantity = $this->input->post('product_quantity');
            $product_discount = $this->input->post('product_discount');//16072018
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
            //despatch detaild in invoice
            $despatch_through = $this->input->post('despatch_through');
            $motor_vehicle_no = $this->input->post('motor_vehicle_no');

            //godown and batch =============
            $batchGodown = isset($_POST['batchGodown']) ? $_POST['batchGodown'] : null;
            $godown_status = $this->input->post('godown_status');
            $batch_status = $this->input->post('batch_status');

            //GST
            $total_price_per_prod = $this->input->post('total_price_per_prod');
            $gross_total_price_per_prod = $this->input->post('gross_total_price_per_prod');
            $total_price_per_prod_with_tax = $this->input->post('total_price_per_prod_with_tax');
            $terms_and_conditions = $this->input->post('terms_and_conditions');
            $voucher_no = $this->input->post('voucher_no');
            $voucher_date = $this->input->post('voucher_date');
            $voucher_date = date("Y-m-d", strtotime(str_replace('/', '-', $voucher_date)));

            $deleted = ($postdated == 1) ? '2' : '0';
            $order_deleted = ($postdated == 1) ? '2' : '1';
            if ($entry_number != 'Auto' || $entry_number != null || $entry_number != '') {
                $this->form_validation->set_rules('entry_number', 'Entry Number', 'required|callback_alpha_dash_space');
            }
            if ($postdated == 1) {
                $this->form_validation->set_rules('date_form', 'Give a date', 'required|check_postdated_date');
            } else {
                $this->form_validation->set_rules('date_form', 'Give a date', 'required');
            }
            if ($this->form_validation->run() === TRUE) {
                $count = count($tr_ledger_id);

                $entry_type_id = $this->input->post('entry_type');
                $parent_id = $this->input->post('parent_id');

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
                if ($entry_type_id == 5 || $entry_type_id == 12 || $parent_id==5 || $parent_id==12) {
                    $gst_type = 'Cr';
                    if ($igst_status == 1) {
                        $igst_ledger_id = 31;
                    } else {
                        $cgst_ledger_id = 33;
                        $sgst_ledger_id = 35;
                    }
                    $cess_type = 'Cr';
                    $cess_ledger_id = 37;
                } else if ($entry_type_id == 6 || $entry_type_id == 14 || $parent_id==6 || $parent_id==14) {
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
                $product_grand_total = $netTotal; //08032018
                if ($reverse_entry == 1 && ($entry_type_id == 6 || $parent_id==6)) {
                    $debtors_amount = $product_grand_total - $tax_value;
                } else {
                    $debtors_amount = $product_grand_total;
                }

                for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
                    $discount_sum+=$discount_value_hidden[$i];
                }
                if ($entry_type_id == 5 || $entry_type_id == 12 || $parent_id==5 || $parent_id==12) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                } else if ($entry_type_id == 6 || $entry_type_id == 14 || $parent_id==6 || $parent_id==14) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                }

                $isDataNewRef = 1;

                $baseCurrency = $this->entry->getDefoultCurrency();
                if ($baseCurrency) {
                    $currency_unit = $this->entry->getCurrencyUnitById($baseCurrency['base_currency']);
                }


                $base_unit = $currency_unit['unit_price'];
                $selected_currency = $currency_unit['id'];

//            for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
//                if ($account_type[$i] == 'Cr') {
//                    if ($entry_type_id == 5 || $entry_type_id == 12) {
//                        $ledger_value += $discount_value_hidden[$i];
//                    } else if ($entry_type_id == 6 || $entry_type_id == 14) {
//                        $ledger_value -= $discount_value_hidden[$i];
//                    }
//                }
//                if (!empty($discount_value_hidden[$i])) {
//                    if ($account_type[$i] == 'Dr') {
//                        if ($entry_type_id == 5 || $entry_type_id == 12) {
//                            $debtors_amount -= $discount_value_hidden[$i];
//                        } else if ($entry_type_id == 6 || $entry_type_id == 14) {
//                            $debtors_amount += $discount_value_hidden[$i];
//                        }
//                    }
//                    if ($account_type[$i] == 'Cr') {
//                        if ($entry_type_id == 5 || $entry_type_id == 12) {
//                            $debtors_amount += $discount_value_hidden[$i];
//                        } else if ($entry_type_id == 6 || $entry_type_id == 14) {
//                            $debtors_amount -= $discount_value_hidden[$i];
//                        }
//                    }
//                }
//            }

                if ($isDataNewRef == 1) {

                    $ledgeId = $tr_ledger_id[0];

                    $hasBilling = $this->inventorymodel->getTBilling($ledgeId);
                    $hasBillingFinal = $hasBilling->bill_details_status;

                    if ($hasBillingFinal == "1") {

                        /* Fetch data */
                        $ledgeDet = $this->inventorymodel->getNewRefLedgerDetails($ledgeId);

                        $credit_date_get = $credit_days;
                        $credit_limit_get = $ledgeDet->credit_limit;

                        $getDiffDrCrBilling = $this->inventorymodel->getDiffDrCrBillingSales($ledgeId);
                        $diff = $getDiffDrCrBilling->diff;
                        $total = $debtors_amount + $diff;
                        $amount = $debtors_amount;
                        if ($entry_type_id == 5 || $parent_id==5) {
                            if ($total <= $credit_limit_get) {
                                $bill_data = array(
                                    'ledger_id' => $tr_ledger_id[0],
                                    'dr_cr' => $account_type[0],
                                    'bill_name' => (isset($advance_bill_name) && $advance_bill_name != '') ? $advance_bill_name : $entry_number,
                                    'credit_days' => $credit_date_get,
                                    'credit_date' => date('Y-m-d', strtotime("+" . $credit_date_get . " days")),
                                    'bill_amount' => ($amount * $base_unit),
                                    'deleted' => $deleted
                                );
                            } else {

                                $data_msg['res'] = 'save_err';
                                $data_msg['message'] = "Your credit limit has exceeded!";

                                echo json_encode($data_msg);
                                exit;
                            }
                        } else if ($entry_type_id == 6 || $parent_id==6) {
                            $bill_data = array(
                                'ledger_id' => $tr_ledger_id[0],
                                'dr_cr' => $account_type[0],
                                'bill_name' => $entry_number,
                                'credit_days' => $credit_date_get,
                                'credit_date' => date('Y-m-d', strtotime("+" . $credit_date_get . " days")),
                                'bill_amount' => ($amount * $base_unit),
                                'deleted' => $deleted
                            );
                        }
                    }
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
                    'ledger_ids_by_accounts' => $ledger_name_json,
                    'dr_amount' => ($entry_type_id == 5 || $entry_type_id == 12 || $parent_id==5 || $parent_id==12) ? ($sum_dr * $base_unit) : ($sum_dr * $base_unit),
                    'cr_amount' => ($entry_type_id == 5 || $entry_type_id == 12 || $parent_id==5 || $parent_id==12) ? ($sum_cr * $base_unit) : ($sum_cr * $base_unit),
                    'unit_price_dr' => ($entry_type_id == 5 || $entry_type_id == 12 || $parent_id==5 || $parent_id==12) ? $sum_dr : $sum_dr,
                    'unit_price_cr' => ($entry_type_id == 5 || $entry_type_id == 12 || $parent_id==5 || $parent_id==12) ? $sum_cr : $sum_cr,
                    'narration' => $narration,
                    'voucher_no' => $voucher_no,
                    'voucher_date' => $voucher_date,
                    'deleted' => $deleted,
                    'bank_id' => (isset($bank_id) && $bank_id != '') ? $bank_id : 0
                );


                $this->db->trans_begin();

                $res = $this->inventorymodel->updateEntry($entry, $entry_id);

                //delete ledger details
                $this->inventorymodel->deleteEntryDetails($entry_id);

                // For Ladger Account Details Table
                $ledgerDetails = array();
                $balance = 0;

                $index = 0;

                // debtors
                $ledgerDetails[0]['branch_id'] = $branch_id;
                $ledgerDetails[0]['account'] = $account_type[0];
                $ledgerDetails[0]['balance'] = ($debtors_amount * $base_unit);
                $ledgerDetails[0]['entry_id'] = $entry_id;
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
                $ledgerDetails[1]['entry_id'] = $entry_id;
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
                    $ledgerDetails[2]['entry_id'] = $entry_id;
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
                    $ledgerDetails[2]['entry_id'] = $entry_id;
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
                    $ledgerDetails[3]['entry_id'] = $entry_id;
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
                    $ledgerDetails[$index]['entry_id'] = $entry_id;
                    $ledgerDetails[$index]['ladger_id'] = $cess_ledger_id;
                    $ledgerDetails[$index]['create_date'] = $created_date;
                    $ledgerDetails[$index]['narration'] = $narration;
                    $ledgerDetails[$index]['selected_currency'] = $selected_currency;
                    $ledgerDetails[$index]['unit_price'] = $base_unit;
                    $ledgerDetails[$index]['discount_type'] = 0;
                    $ledgerDetails[$index]['discount_amount'] = '';
                    $ledgerDetails[$index]['deleted'] = $deleted;
                }

                if (is_array($discount_value_hidden) && count($discount_value_hidden) > 1) {
                    for ($i = 1; $i <= count($discount_value_hidden) - 1; $i++) {
                        $index++;
                        $j = $i + 1;
                        if (!empty($discount_value_hidden[$i - 1])) {
                            $balance = $discount_value_hidden[$i - 1];
                        }
                        $ledgerDetails[$index]['branch_id'] = $branch_id;
                        $ledgerDetails[$index]['account'] = $account_type[$j];
                        $ledgerDetails[$index]['balance'] = ($balance * $base_unit);
                        $ledgerDetails[$index]['entry_id'] = $entry_id;
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

                    $this->inventorymodel->insertLedgerDetails($ledgerDetails);
                    //computation
                    $this->inventorymodel->deleteComputationEntryDetails($entry_id);
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
                    $ledgerDetails[0]['entry_id'] = $entry_id;
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
                    $ledgerDetails[1]['entry_id'] = $entry_id;
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
                        $ledgerDetails[2]['entry_id'] = $entry_id;
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
                        $ledgerDetails[2]['entry_id'] = $entry_id;
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
                        $ledgerDetails[3]['entry_id'] = $entry_id;
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
                        $ledgerDetails[$index]['entry_id'] = $entry_id;
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
                            $ledgerDetails[$index]['entry_id'] = $entry_id;
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
                    $this->inventorymodel->insertComputationLedgerDetails($ledgerDetails);
                    //endcomputation
                } else {
                    $this->inventorymodel->insertLedgerDetails($ledgerDetails);
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

                    $this->inventorymodel->updateBillwise($entry_id, $bill_data);
                }



                // For Order
                $order = array(
                    'branch_id' => $branch_id,
                    'users_id' => $tr_ledger_id[0],
                    'total' => ($product_grand_total * $base_unit),
                    'creation_date' => $created_date,
                    'spl_discount_json' => (isset($product_discount[0]) && !empty($product_discount[0]))?'1':'',
                    'tax_amount' => ($tax_value * $base_unit),
                    'grand_total' => ($product_grand_total * $base_unit),
                    'currency_code' => $selected_currency,
                    'terms_and_conditions' => $terms_and_conditions,
                    'is_igst_included' => $igst_status,
                    'is_cess_included' => $cess_status,
                    'status' => $order_deleted
                );
                if ($type_service_product != 1) {
                    $order_type = 0;

                    if($entry_type_id == 5 || $parent_id==5){
                        $order_type = '1';
                    }elseif($entry_type_id == 6 || $parent_id==6){
                        $order_type = '2';
                    }elseif($entry_type_id == 12 || $parent_id==12){
                        $order_type = '8';
                    }elseif($entry_type_id == 14 || $parent_id==14){
                        $order_type = '7';
                    }


                    $order_data = $this->inventorymodel->getOrderId($entry_id,$order_type);
                    $orderId = $order_data->id;
                    $res = $this->inventorymodel->updateOrder($orderId, $order);

                    $this->inventorymodel->deleteOrderProduct($orderId);
                } else {
                    $order_data = $this->inventorymodel->getTempOrderId($entry_id);
                    $orderId = $order_data->id;
                    $res = $this->inventorymodel->updateTempOrder($orderId, $order);

                    $this->inventorymodel->deleteTempOrderProduct($orderId);
                }


                //Shipping Address
                if($shipping_id != ''){
                    $table = 'orders';
                    $this->setOrderAddressDetails($orderId,$tr_ledger_id[0],$shipping_id,$table,$isDataNewRef);
                }

                //despatch detaild
                if($despatch_through != '' || $motor_vehicle_no != ''  ){
                    despatchDetails($entry_id);
                }

                // For Order Details
                if (count($product_id) > 0) {


                    for ($j = 0; $j < count($product_id); $j++) {
                        if ($type_service_product != 1) {
                            $order_product = $this->inventorymodel->getOrderProduct($orderId, $product_id[$j], $stock_id[$j]);
                        } else {
                            $order_product = $this->inventorymodel->getTempOrderProduct($orderId, $product_id[$j], $stock_id[$j]);
                        }
                        $productDetails = array(
                            'branch_id' => $branch_id,
                            'order_id' => $orderId,
                            'product_id' => $product_id[$j],
                            'product_description' => $product_description[$j],
                            'stock_id' => $stock_id[$j],
                            'quantity' => $product_quantity[$j],
                            'original_price' => $product_price[$j],
                            'base_price' => $product_price[$j],
                            'discount_percentage' => $product_discount[$j],
                            'discount_amount' => (($product_quantity[$j] * $product_price[$j]) - $gross_total_price_per_prod[$j]),
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
                            'order_type' => ($entry_type_id == 5 || $parent_id==5) ? '1' : '2',
                            'status' => $order_deleted,
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

                        if ($type_service_product != 1) {
//                        if ($order_product) {
//                            $this->inventorymodel->updateOrderProduct($orderId, $product_id[$j], $stock_id[$j], $productDetails);
//                        } else {
                            $this->inventorymodel->insertOrderProduct($productDetails);
//                        }
                        } else {
//                          if ($order_product) {
//                            $this->inventorymodel->updateTempOrderProduct($orderId, $product_id[$j], $stock_id[$j], $productDetails);
//                        } else {
                            $this->inventorymodel->insertTempOrderProduct($productDetails);
//                        }
                        }
                    }

                    //For primary godown Godown if godown status == 0 and batch status == 0
                    if($batch_status == 0 && $godown_status == 0){
                        $this->inventorymodel->deleteGodown($orderId);
                        $this->inventorymodel->insertGodown($godown_array);
                    }
                }

                //For Batch AND Godown
                    if (count($batchGodown) > 0) {
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
//                            if(count($batch_array) > 0){
//                                $this->inventorymodel->deleteBatch($orderId);
//                                $this->inventorymodel->insertBatch($batch_array);
//                            }
//                            $this->inventorymodel->deleteGodown($orderId);
//                            $this->inventorymodel->insertGodown($godown_array);
                        }
                    }

                //recurring
                if (isset($recurring_freq) && ($recurring_freq == 'Daily' || $recurring_freq == 'Weekly' || $recurring_freq == 'Monthly' || $recurring_freq == 'Yearly')) {
                    if ($type_service_product != 1) {
                        $this->updateRecurring($recurring_freq, $created_date, $entry_id);
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'save_err';
                    $data_msg['message'] = "There was an error submitting the form";
                } else {
                    $this->db->trans_commit();
                    $entry_type = $this->inventorymodel->getEntryTypeById($entry_type_id);
                    $data_msg['res'] = 'success';
                    $data_msg['redirect_url'] = base_url('admin/inventory-transaction-list'). "/" .  str_replace(' ','-',strtolower(trim($entry_type['type']))).'/'.$entry_type['id'].'/all';
                    // $data_msg['redirect_url'] = $redirect_url;
                    // $data_msg['print_url'] = $print_url;
                    $data_msg['message'] = "Transaction Updated successfully.";
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = $this->form_validation->error_array();
            }
            echo json_encode($data_msg);
        }
    }

    public function updateRecurring($recurring_freq, $created_date, $entryId) {
        $data = array(
            'entry_id' => $entryId,
            'recurring_date' => $created_date,
            'frequency' => $recurring_freq,
            'status' => 1,
            'create_date' => date("Y-m-d H:i:s")
        );
        $this->inventorymodel->updateRecurringData($data, $entryId);
    }

    //ajax delete entry
    public function ajax_delete_transaction() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $entry_id = $this->input->post('delete_entry_id');
            $entry_type = $this->input->post('delete_entry_type');
            $delete_type = $this->input->post('delete_type');
            $cancel = $this->input->post('cancel');
            $branch_id = $this->session->userdata('branch_id');

            if ($entry_id) {
                if ($entry_type == 'current') {
                    $entry = $this->inventorymodel->getEntry($entry_id);
                    if ($entry) {
                        $res = $this->inventorymodel->deleteTransaction($entry_id,$cancel);
                        if ($res) {
                            $ledgerDetails = $this->inventorymodel->getLedgerByEntryId($entry_id);
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

                            $data_msg['message'] = 'Entry Deleted Successfully.';
                            $data_msg['res'] = 'success';
                            $data_msg['entry_id'] = $entry_id;
                            $data_msg['type'] = $delete_type;
                        } else {
                            $data_msg['message'] = 'Error in process. Please try again.';
                            $data_msg['res'] = 'error';
                        }
                    }
                } else if ($entry_type == 'request') {
                    $entry = $this->inventorymodel->getRequestEntry($entry_id);
                    if ($entry) {
                        $res = $this->inventorymodel->deleteRequestTransaction($entry_id);
                        if ($res) {
                            $data_msg['message'] = 'Entry Deleted Successfully.';
                            $data_msg['res'] = 'success';
                            $data_msg['entry_id'] = $entry_id;
                            $data_msg['type'] = $delete_type;
                        } else {
                            $data_msg['message'] = 'Error in process. Please try again.';
                            $data_msg['res'] = 'error';
                        }
                    }
                } else if ($entry_type == 'temp-note') {
                    $order_type = [5, 6];
                    $entry = $this->inventorymodel->getTempEntry($entry_id);
                    if ($entry) {
                        $res = $this->inventorymodel->deleteTempTransaction($entry_id, $order_type);
                        if ($res) {
                            $data_msg['message'] = 'Entry Deleted Successfully.';
                            $data_msg['res'] = 'success';
                            $data_msg['entry_id'] = $entry_id;
                            $data_msg['type'] = $delete_type;
                        } else {
                            $data_msg['message'] = 'Error in process. Please try again.';
                            $data_msg['res'] = 'error';
                        }
                    }
                }
            }
            echo json_encode($data_msg);
        }
    }

    //ajax delete entry
    public function ajax_delete_quotation() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $entry_id = $this->input->post('delete_entry_id');
            $entry_type = $this->input->post('delete_entry_type');
            $delete_type = $this->input->post('delete_type');
            $cancel = $this->input->post('cancel');
            $branch_id = $this->session->userdata('branch_id');

            if ($entry_id) {
                $entry = $this->inventorymodel->getRequestEntryForQuotation($entry_id);
                if ($entry) {
                    $res = $this->inventorymodel->deleteRequestTransactionForQuotation($entry_id);
                    if ($res) {
                        $data_msg['message'] = 'Entry Deleted Successfully.';
                        $data_msg['res'] = 'success';
                        $data_msg['entry_id'] = $entry_id;
                        $data_msg['type'] = $delete_type;
                    } else {
                        $data_msg['message'] = 'Error in process. Please try again.';
                        $data_msg['res'] = 'error';
                    }
                }
            }
            echo json_encode($data_msg);
        }
    }

    function getEntryNumber($entry_type_id) {
        $countid = 1;
        $entry_type = $this->inventorymodel->getEntryTypeById($entry_type_id);
        $auto_number = $this->inventorymodel->getTotalEntryByType($entry_type_id,$entry_type['parent_id']);
        $start_length = $entry_type['starting_entry_no'];
        $countid = $countid + $auto_number->total_transaction;
        $id_length = strlen($countid);
        if ($start_length > $id_length) {
            $remaining = $start_length - $id_length;
            $uniqueid = $entry_type['prefix_entry_no'] . str_repeat("0", $remaining) . $countid . $entry_type['suffix_entry_no'];
        } else {
            $uniqueid = $entry_type['prefix_entry_no'] . $countid . $entry_type['suffix_entry_no'];
        }
        return $uniqueid;
    }

    function getQuotationNumber($entry_type_id) {
        $countid = 1;
        $entry_type = $this->inventorymodel->getEntryTypeById($entry_type_id);
        $auto_number = $this->inventorymodel->getTotalQuotationByType($entry_type_id,$entry_type['parent_id']);
        $start_length = $entry_type['starting_entry_no'];
        $countid = $countid + $auto_number->total_transaction;
        $id_length = strlen($countid);
        if ($start_length > $id_length) {
            $remaining = $start_length - $id_length;
            $uniqueid = $entry_type['prefix_entry_no'] . str_repeat("0", $remaining) . $countid . $entry_type['suffix_entry_no'];
        } else {
            $uniqueid = $entry_type['prefix_entry_no'] . $countid . $entry_type['suffix_entry_no'];
        }
        return $uniqueid;
    }

    function getEntryNumberTemp($entry_type_id) {
        $countid = 1;
        $entry_type = $this->inventorymodel->getEntryTypeById($entry_type_id);
        $auto_number = $this->inventorymodel->getTotalTempEntryByType($entry_type_id,$entry_type['parent_id']);
        $start_length = $entry_type['starting_entry_no'];
        $countid = $countid + $auto_number->total_transaction;
        $id_length = strlen($countid);
        if ($start_length > $id_length) {
            $remaining = $start_length - $id_length;
            $uniqueid = $entry_type['prefix_entry_no'] . str_repeat("0", $remaining) . $countid . $entry_type['suffix_entry_no'];
        } else {
            $uniqueid = $entry_type['prefix_entry_no'] . $countid . $entry_type['suffix_entry_no'];
        }
        return $uniqueid;
    }

    public function ajax_add_order_data() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {

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
            $product_discount = $this->input->post('product_discount');//16072018
            $tax_percent = $this->input->post('tax_percent');
            $tax_value = $this->input->post('tax_value');
            $total_price_per_prod = $this->input->post('total_price_per_prod');
            $gross_total_price_per_prod = $this->input->post('gross_total_price_per_prod');
            $total_price_per_prod_with_tax = $this->input->post('total_price_per_prod_with_tax');
            $count = count($tr_ledger_id);
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


            //GST
            $voucher_no = $this->input->post('voucher_no');
            $voucher_date = $this->input->post('voucher_date');
            $voucher_date = date("Y-m-d", strtotime(str_replace('/', '-', $voucher_date)));
            $entry_type_id = $this->input->post('entry_type');
            $entry_type = $this->inventorymodel->getEntryTypeById($entry_type_id);
            $parent_id = $this->input->post('parent_id');
            $created_date = date("Y-m-d", strtotime(str_replace('/', '-', $date_form)));

            if ($entry_type_id == 8) {
                $this->form_validation->set_rules('tr_ledger[0]', 'Creditors', 'required');
                $this->form_validation->set_rules('tr_ledger[1]', 'Purchase', 'required');
            } else if($entry_type_id == 7) {
                $this->form_validation->set_rules('tr_ledger[0]', 'Debtors', 'required');
                $this->form_validation->set_rules('tr_ledger[1]', 'Sales', 'required');
            }
            if ($entry_number != 'Auto' || $entry_number != null || $entry_number != '') {
                $this->form_validation->set_rules('entry_number', 'Entry Number', 'required|callback_alpha_dash_space');
            }
            $this->form_validation->set_rules('date_form', 'Transaction date', 'required');
            if ($this->form_validation->run() === TRUE) {

                if ($entry_number == 'Auto' || $entry_number == null || $entry_number == '') {
                    $entry_number = $this->getEntryNumber($entry_type_id);
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
                if ($entry_type_id == 7 || $parent_id==7) {
                    $gst_type = 'Cr';
                    if ($igst_status == 1) {
                        $igst_ledger_id = 31;
                    } else {
                        $cgst_ledger_id = 33;
                        $sgst_ledger_id = 35;
                    }
                    $cess_type = 'Cr';
                    $cess_ledger_id = 37;
                } else if ($entry_type_id == 8 || $parent_id==8) {
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
                if ($entry_type_id == 7 || $parent_id==7) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                } else if ($entry_type_id == 8 || $parent_id==8) {
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
//                        if ($entry_type_id == 7) {
//                            $ledger_value += $discount_value_hidden[$i];
//                        } else if ($entry_type_id == 8) {
//                            $ledger_value -= $discount_value_hidden[$i];
//                        }
//                    }
//                    if (!empty($discount_value_hidden[$i])) {
//                        if ($account_type[$i] == 'Dr') {
//                            if ($entry_type_id == 7) {
//                                $debtors_amount -= $discount_value_hidden[$i];
//                            } else if ($entry_type_id == 8) {
//                                $debtors_amount += $discount_value_hidden[$i];
//                            }
//                        }
//                        if ($account_type[$i] == 'Cr') {
//                            if ($entry_type_id == 7) {
//                                $debtors_amount += $discount_value_hidden[$i];
//                            } else if ($entry_type_id == 8) {
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
                    'dr_amount' => ($entry_type_id == 7 || $parent_id==7) ? ($sum_dr * $base_unit) : ($sum_dr * $base_unit),
                    'cr_amount' => ($entry_type_id == 7 || $parent_id==7) ? ($sum_cr * $base_unit) : ($sum_cr * $base_unit),
                    'unit_price_dr' => ($entry_type_id == 7 || $parent_id==7) ? $sum_dr : $sum_dr,
                    'unit_price_cr' => ($entry_type_id == 7 || $parent_id==7) ? $sum_cr : $sum_cr,
                    'entry_type_id' => ($entry_type['parent_id']==0)?$entry_type_id:$entry_type['parent_id'],
                    'sub_voucher' => ($entry_type['parent_id']!=0)?$entry_type_id:$entry_type['parent_id'],
                    'user_id' => $user_id,
                    'company_id' => $branch_id,
                    'is_inventry' => $is_inventry,
                    'narration' => $narration,
                    'order_id' => $order_id,
                    'voucher_no' => $voucher_no,
                    'voucher_date' => $voucher_date,
                    'bank_id' => (isset($bank_id) && $bank_id != '') ? $bank_id : 0
                );

                // quotation id
                $quotation_id = $this->input->post('quotation_id');

                $this->db->trans_begin();

                $entryId = $this->inventorymodel->saveRequestEntry($entry);

                if ($quotation_id && $entryId) {
                    $this->inventorymodel->saveQuotationSalesOrderRelation(['quotation_id' => $quotation_id, 'entry_request_id' => $entryId]);
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

                $this->inventorymodel->saveRequestEntryDetails($ledgerDetails);

                // For Billwise Details Auto submission (without popup)
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
                    'order_type' => ($entry_type_id == 7 || $parent_id==7) ? '1' : '2',
                    'flow_type' => ($entry_type_id == 7 || $parent_id==7) ? '1' : '0',
                    'is_igst_included' => $igst_status,
                    'is_cess_included' => $cess_status,
                    'status' => '1'
                );

                $orderId = $this->inventorymodel->saveRequestOrder($order);

                $table = 'request';
                $this->setOrderAddressDetails($orderId,$tr_ledger_id[0],$shipping_id,$table,$isDataNewRef);


                // For Order Details
                if (count($product_id) > 0) {
                    $productDetails = array();

                    for ($j = 0; $j < count($product_id); $j++) {
                        $productDetails[$j]['branch_id'] = $branch_id;
                        $productDetails[$j]['order_id'] = $orderId;
                        $productDetails[$j]['product_id'] = $product_id[$j];
                        $productDetails[$j]['product_description'] = $product_description[$j];//19042018
                        $productDetails[$j]['stock_id'] = $stock_id[$j];
                        $productDetails[$j]['quantity'] = $product_quantity[$j];
                        $productDetails[$j]['original_price'] = $product_price[$j];
                        $productDetails[$j]['base_price'] = $product_price[$j];
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
                        $productDetails[$j]['flow_type'] = ($entry_type_id == 7 || $parent_id==7) ? '66' : '55';
                        $productDetails[$j]['order_type'] = ($entry_type_id == 7 || $parent_id==7) ? '1' : '2';
                    }
                    $this->inventorymodel->insertRequestOrderDetails($productDetails);
                }



                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'save_err';
                    $data_msg['message'] = "There was an error submitting the form";
                } else {
                    $this->db->trans_commit();

                    $pref = $this->inventorymodel->getPreferences();
                    if($pref->sales_order_mail == 1) {
                        //mail
                        // $this->load->helper('email');
                        $this->load->helper('actmail');
                        $company_details = $this->inventorymodel->getCompanyDetails();
                        $voucher = ($entry_type_id == 7 || $parent_id==7) ? 'Sales Order' : 'Purchase Order';
                        $message = "Your " . $voucher . " transaction added successfully. " . $voucher . " number is " . $entry_number;
                        $company_mail_data = array($company_details->company_name, $message);
                        if ($entry_type_id == 7 || $parent_id==7) {
                            // sendMail($template = 'transaction', $slug = 'transaction_mail', $to = $company_details->email, $company_mail_data);
                            $attach = $this->testInvoice($entryId); // invoice attachment
                            $company_name_for_mail = $company_details->company_name;
                            // sendActMail($template = 'sales_template', $slug = 'sales_order', $to = $company_details->email, $company_mail_data, $attach, $company_name_for_mail);
                            $ledger_contact_details = $this->inventorymodel->getLedgerContactDetails($tr_ledger_id[0]);
                            if ($ledger_contact_details) {
                                //mail
                                $ledger_mail_data = array($ledger_contact_details->company_name, $message);
                                // sendMail($template = 'transaction', $slug = 'transaction_mail', $to = $ledger_contact_details->email, $ledger_mail_data);
                                sendActMail($template = 'sales_template', $slug = 'sales_order', $to = $ledger_contact_details->email, $ledger_mail_data, $attach, $company_name_for_mail);
                                //end mail
                            }
                            //end mail
                        }
                    }


                    $data_msg['res'] = 'success';
                    $data_msg['print_url'] = base_url('transaction/invoice') . '.aspx/' . $entryId . '/' . $entry_type_id;
                    $data_msg['redirect_url'] = base_url('admin/inventory-transaction-list'). "/" .  str_replace(' ','-',strtolower(trim($entry_type['type']))).'/'.$entry_type['id'].'/all';

                    $data_msg['message'] = "Transaction added successfully. Entry number #" . $entry_number;
                }
            } else {
                $data_msg['res'] = 'error';
                // $data_msg['message'] = $this->form_validation->error_array();
                $data_msg['message'] = validation_errors();
            }
            echo json_encode($data_msg);
        }
    }

    public function ajax_update_order_data() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {

            $entry_id = $this->input->post('entry_id');
            $entry_number = $this->input->post('entry_number');
            $date_form = $this->input->post('date_form');
            $created_date = date("Y-m-d", strtotime(str_replace('/', '-', $date_form)));
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

            //baranch id
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
            $product_discount = $this->input->post('product_discount');//16072018
            $total_price_per_prod = $this->input->post('total_price_per_prod');
            $gross_total_price_per_prod = $this->input->post('gross_total_price_per_prod');
            $total_price_per_prod_with_tax = $this->input->post('total_price_per_prod_with_tax');
            $terms_and_conditions = $this->input->post('terms_and_conditions');



            $count = count($tr_ledger_id);
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


            //GST
            $voucher_no = $this->input->post('voucher_no');
            $voucher_date = $this->input->post('voucher_date');
            $voucher_date = date("Y-m-d", strtotime(str_replace('/', '-', $voucher_date)));
            $entry_type_id = $this->input->post('entry_type');
            $parent_id = $this->input->post('parent_id');
            $entry_type = $this->entry->getEntryTypeById($entry_type_id);
            if ($entry_number != 'Auto' || $entry_number != null || $entry_number != '') {
                $this->form_validation->set_rules('entry_number', 'Entry Number', 'required|callback_alpha_dash_space');
            }


            $this->form_validation->set_rules('date_form', 'Transaction date', 'required');

            if ($this->form_validation->run() === TRUE) {
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
                if ($entry_type_id == 7 || $parent_id==7) {
                    $gst_type = 'Cr';
                    if ($igst_status == 1) {
                        $igst_ledger_id = 31;
                    } else {
                        $cgst_ledger_id = 33;
                        $sgst_ledger_id = 35;
                    }
                    $cess_type = 'Cr';
                    $cess_ledger_id = 37;
                } else if ($entry_type_id == 8 || $parent_id==8) {
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
                $product_grand_total = $netTotal; //08/03/2018
                $debtors_amount = $product_grand_total;
                for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
                    $discount_sum+=$discount_value_hidden[$i];
                }
                if ($entry_type_id == 7 || $parent_id==7) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                } else if ($entry_type_id == 8 || $parent_id==8) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                }

                $isDataNewRef = 1;

                $baseCurrency = $this->entry->getDefoultCurrency();
                if ($baseCurrency) {
                    $currency_unit = $this->entry->getCurrencyUnitById($baseCurrency['base_currency']);
                }


                $base_unit = $currency_unit['unit_price'];
                $selected_currency = $currency_unit['id'];

//                for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
//                    if ($account_type[$i] == 'Cr') {
//                        if ($entry_type_id == 7) {
//                            $ledger_value += $discount_value_hidden[$i];
//                        } else if ($entry_type_id == 8) {
//                            $ledger_value -= $discount_value_hidden[$i];
//                        }
//                    }
//                    if (!empty($discount_value_hidden[$i])) {
//                        if ($account_type[$i] == 'Dr') {
//                            if ($entry_type_id == 7) {
//                                $debtors_amount -= $discount_value_hidden[$i];
//                            } else if ($entry_type_id == 8) {
//                                $debtors_amount += $discount_value_hidden[$i];
//                            }
//                        }
//                        if ($account_type[$i] == 'Cr') {
//                            if ($entry_type_id == 7) {
//                                $debtors_amount += $discount_value_hidden[$i];
//                            } else if ($entry_type_id == 8) {
//                                $debtors_amount -= $discount_value_hidden[$i];
//                            }
//                        }
//                    }
//                }

                if ($isDataNewRef == 1) {

                    $ledgeId = $tr_ledger_id[0];

                    $hasBilling = $this->inventorymodel->getTBilling($ledgeId);
                    $hasBillingFinal = $hasBilling->bill_details_status;

                    if ($hasBillingFinal == "1") {

                        /* Fetch data */
                        $ledgeDet = $this->inventorymodel->getNewRefLedgerDetails($ledgeId);

                        $credit_date_get = $credit_days;
                        $credit_limit_get = $ledgeDet->credit_limit;

                        $getDiffDrCrBilling = $this->inventorymodel->getDiffDrCrBillingSales($ledgeId);
                        $diff = $getDiffDrCrBilling->diff;
                        $total = $debtors_amount + $diff;
                        $amount = $debtors_amount;
                        if ($entry_type_id == 7 || $parent_id==7) {
                            if ($total <= $credit_limit_get) {
                                $bill_data = array(
                                    'ledger_id' => $tr_ledger_id[0],
                                    'dr_cr' => $account_type[0],
                                    'bill_name' => $entry_number,
                                    'credit_days' => $credit_date_get,
                                    'credit_date' => date('Y-m-d', strtotime("+" . $credit_date_get . " days")),
                                    'bill_amount' => ($amount * $base_unit)
                                );
                            } else {

                                $data_msg['res'] = 'save_err';
                                $data_msg['message'] = "Your credit limit has exceeded!";

                                echo json_encode($data_msg);
                                exit;
                            }
                        } else if ($entry_type_id == 8 || $parent_id==8) {
                            $bill_data = array(
                                'ledger_id' => $tr_ledger_id[0],
                                'dr_cr' => $account_type[0],
                                'bill_name' => $entry_number,
                                'credit_days' => $credit_date_get,
                                'credit_date' => date('Y-m-d', strtotime("+" . $credit_date_get . " days")),
                                'bill_amount' => ($amount * $base_unit)
                            );
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
                    'ledger_ids_by_accounts' => $ledger_name_json,
                    'dr_amount' => ($entry_type_id == 7 || $parent_id==7) ? ($sum_dr * $base_unit) : ($sum_dr * $base_unit),
                    'cr_amount' => ($entry_type_id == 7 || $parent_id==7) ? ($sum_cr * $base_unit) : ($sum_cr * $base_unit),
                    'unit_price_dr' => ($entry_type_id == 7 || $parent_id==7) ? $sum_dr : $sum_dr,
                    'unit_price_cr' => ($entry_type_id == 7 || $parent_id==7) ? $sum_cr : $sum_cr,
                    'narration' => $narration,
                    'voucher_no' => $voucher_no,
                    'voucher_date' => $voucher_date,
                    'bank_id' => (isset($bank_id) && $bank_id != '') ? $bank_id : 0
                );



                $this->db->trans_begin();

                $res = $this->inventorymodel->updateRequestEntry($entry, $entry_id);

                //delete ledger details
                $this->inventorymodel->deleteRequestEntryDetails($entry_id);

                // For Ladger Account Details Table
                $ledgerDetails = array();
                $balance = 0;

                $index = 0;

                // debtors
                $ledgerDetails[0]['branch_id'] = $branch_id;
                $ledgerDetails[0]['account'] = $account_type[0];
                $ledgerDetails[0]['balance'] = ($debtors_amount * $base_unit);
                $ledgerDetails[0]['entry_id'] = $entry_id;
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
                $ledgerDetails[1]['entry_id'] = $entry_id;
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
                    $ledgerDetails[2]['entry_id'] = $entry_id;
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
                    $ledgerDetails[2]['entry_id'] = $entry_id;
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
                    $ledgerDetails[3]['entry_id'] = $entry_id;
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
                    $ledgerDetails[$index]['entry_id'] = $entry_id;
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
                        $ledgerDetails[$index]['entry_id'] = $entry_id;
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

                //update ledger details
                $this->inventorymodel->saveRequestEntryDetails($ledgerDetails);


                // For Billwise Details Auto submission (without popup)

                if (count($bill_data) > 0) {

                    //  $this->inventorymodel->updateBillwise($entry_id, $bill_data);
                }



                // For Order
                $order = array(
                    'branch_id' => $branch_id,
                    'users_id' => $tr_ledger_id[0],
                    'total' => ($product_grand_total * $base_unit),
                    'spl_discount_json' => (isset($product_discount[0]) && !empty($product_discount[0]))?'1':'',
                    'creation_date' => $created_date,
                    'tax_amount' => ($tax_value * $base_unit),
                    'grand_total' => ($product_grand_total * $base_unit),
                    'currency_code' => $selected_currency,
                    'order_type' => ($entry_type_id == 7 || $parent_id==7) ? '1' : '2',
                    'flow_type' => ($entry_type_id == 7 || $parent_id==7) ? '1' : '0',
                    'is_igst_included' => $igst_status,
                    'is_cess_included' => $cess_status,
                    'terms_and_conditions' => $terms_and_conditions
                );
                $order_data = $this->inventorymodel->getOrderIdRequest($entry_id);
                $orderId = $order_data->id;
                $res = $this->inventorymodel->updateOrderRequest($entry_id, $order);

                if($shipping_id != ''){
                    $table = 'request';
                    $this->setOrderAddressDetails($orderId,$tr_ledger_id[0],$shipping_id,$table,$isDataNewRef);
                }

                $this->inventorymodel->deleteRequestOrderProduct($orderId);
                // For Order Details
                if (count($product_id) > 0) {


                    for ($j = 0; $j < count($product_id); $j++) {
                        $order_product = $this->inventorymodel->getRequestOrderProduct($orderId, $product_id[$j], $stock_id[$j]);
                        $productDetails = array(
                            'branch_id' => $branch_id,
                            'order_id' => $orderId,
                            'product_id' => $product_id[$j],
                            'product_description' => $product_description[$j],
                            'stock_id' => $stock_id[$j],
                            'quantity' => $product_quantity[$j],
                            'original_price' => $product_price[$j],
                            'base_price' => $product_price[$j],
                            'discount_percentage' => $product_discount[$j],
                            'discount_amount' => (($product_quantity[$j] * $product_price[$j]) - $gross_total_price_per_prod[$j]),
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
                            'flow_type' => ($entry_type_id == 7 || $parent_id==7) ? '66' : '55',
                            'status' => '1',
                            'order_type' => ($entry_type_id == 7 || $parent_id==7) ? '1' : '2'
                        );
                        if ($order_product) {
                            $this->inventorymodel->updateRequestOrderProduct($orderId, $product_id[$j], $stock_id[$j], $productDetails);
                        } else {
                            $this->inventorymodel->insertRequestOrderProduct($productDetails);
                        }
                    }
                }



                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'save_err';
                    $data_msg['message'] = "There was an error submitting the form";
                } else {
                    $this->db->trans_commit();
                    $data_msg['res'] = 'success';
                    $data_msg['print_url'] = base_url('transaction/(') . '.aspx/' . $entry_id . '/' . $entry_type_id;
                    $data_msg['redirect_url'] =base_url('admin/inventory-transaction-list'). "/" .  str_replace(' ','-',strtolower(trim($entry_type['type']))).'/'.$entry_type['id'].'/all';
                    // $data_msg['redirect_url'] = $redirect_url;
                    // $data_msg['print_url'] = $print_url;
                    $data_msg['message'] = "Transaction Updated successfully.";
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = $this->form_validation->error_array();
            }
            echo json_encode($data_msg);
        }
    }

    public function ajax_add_note() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {

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
            $product_price = $this->input->post('product_price');
            $tax_percent = $this->input->post('tax_percent');
            $tax_value = $this->input->post('tax_value');
            $total_price_per_prod = $this->input->post('total_price_per_prod');
            $gross_total_price_per_prod = $this->input->post('gross_total_price_per_prod');
            $total_price_per_prod_with_tax = $this->input->post('total_price_per_prod_with_tax');
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
            $count = count($tr_ledger_id);

            $entry_type_id = $this->input->post('entry_type');
            $parent_id = $this->input->post('parent_id');
            $voucher_no = $this->input->post('voucher_no');
            $voucher_date = $this->input->post('voucher_date');
            $voucher_date = date("Y-m-d", strtotime(str_replace('/', '-', $voucher_date)));
            $entry_type = $this->inventorymodel->getEntryTypeById($entry_type_id);
            $terms_and_condition = $this->input->post('terms_and_conditions');
            $created_date = date("Y-m-d", strtotime(str_replace('/', '-', $date_form)));

            if ($entry_type_id == 9) {
                $this->form_validation->set_rules('tr_ledger[0]', 'Creditors', 'required');
                $this->form_validation->set_rules('tr_ledger[1]', 'Purchase', 'required');
            } else if($entry_type_id == 10) {
                $this->form_validation->set_rules('tr_ledger[0]', 'Debtors', 'required');
                $this->form_validation->set_rules('tr_ledger[1]', 'Sales', 'required');
            }
            if ($entry_number == 'Auto' || $entry_number == null || $entry_number == '') {
                $entry_number = $this->getEntryNumberTemp($entry_type_id);
            }
            if ($entry_number != 'Auto' || $entry_number != null || $entry_number != '') {
                $this->form_validation->set_rules('entry_number', 'Entry Number', 'required|callback_alpha_dash_space');
            }


            $this->form_validation->set_rules('date_form', 'Transaction date', 'required');

            if ($this->form_validation->run() === TRUE) {

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
                if ($entry_type_id == 10 || $parent_id==10) {
                    $gst_type = 'Cr';
                    if ($igst_status == 1) {
                        $igst_ledger_id = 31;
                    } else {
                        $cgst_ledger_id = 33;
                        $sgst_ledger_id = 35;
                    }
                    $cess_type = 'Cr';
                    $cess_ledger_id = 37;
                } else if ($entry_type_id == 9 || $parent_id==9) {
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
                if ($entry_type_id == 10 || $parent_id==10) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                } else if ($entry_type_id == 9 || $parent_id==9) {
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

//            for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
//                if ($account_type[$i] == 'Cr') {
//                    if ($entry_type_id == 10) {
//                        $ledger_value += $discount_value_hidden[$i];
//                    } else if ($entry_type_id == 9) {
//                        $ledger_value -= $discount_value_hidden[$i];
//                    }
//                }
//                if (!empty($discount_value_hidden[$i])) {
//                    if ($account_type[$i] == 'Dr') {
//                        if ($entry_type_id == 10) {
//                            $debtors_amount -= $discount_value_hidden[$i];
//                        } else if ($entry_type_id == 9) {
//                            $debtors_amount += $discount_value_hidden[$i];
//                        }
//                    }
//                    if ($account_type[$i] == 'Cr') {
//                        if ($entry_type_id == 10) {
//                            $debtors_amount += $discount_value_hidden[$i];
//                        } else if ($entry_type_id == 9) {
//                            $debtors_amount -= $discount_value_hidden[$i];
//                        }
//                    }
//                }
//            }
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
                    'dr_amount' => ($entry_type_id == 10 || $parent_id==10) ? ($sum_dr * $base_unit) : ($sum_dr * $base_unit),
                    'cr_amount' => ($entry_type_id == 10 || $parent_id==10) ? ($sum_cr * $base_unit) : ($sum_cr * $base_unit),
                    'unit_price_dr' => ($entry_type_id == 10 || $parent_id==10) ? $sum_dr : $sum_dr,
                    'unit_price_cr' => ($entry_type_id == 10 || $parent_id==10) ? $sum_cr : $sum_cr,
                    'entry_type_id' => ($parent_id==0)?$entry_type_id:$parent_id,
                    'sub_voucher' => ($parent_id!=0)?$entry_type_id:$parent_id,
                    'company_id' => $branch_id,
                    'user_id' => $user_id,
                    'is_inventry' => $is_inventry,
                    'narration' => $narration,
                    'order_id' => $order_id,
                    'voucher_no' => $voucher_no,
                    'voucher_date' => $voucher_date,
                    'bank_id' => (isset($bank_id) && $bank_id != '') ? $bank_id : 0
                );

                $this->db->trans_begin();
                $entryId = $this->inventorymodel->saveTempEntry($entry);
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
                    }
                }

                $this->inventorymodel->saveTempEntryDetails($ledgerDetails);

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
                    'flow_type' => ($entry_type_id == 10 || $parent_id==10) ? 1 : 0,
                    'order_type' => ($entry_type_id == 10 || $parent_id==10) ? 6 : 5,
                    'is_igst_included' => $igst_status,
                    'is_cess_included' => $cess_status,
                    'terms_and_conditions' => $terms_and_condition
                );

                $orderId = $this->inventorymodel->saveOrder($order);

                //shiping address update
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
                        $productDetails[$j]['flow_type'] = ($entry_type_id == 10 || $parent_id==10) ? '66' : '55';
                        $productDetails[$j]['order_type'] = ($entry_type_id == 10 || $parent_id==10) ? '6' : '5';

                        //For primary godown Godown if godown status == 0 and batch status == 0
                         if($batch_status == 0 && $godown_status == 0){
                            $godown_array[$j]['entry_id'] = $entryId;
                            $godown_array[$j]['stock_id'] = $orderId;
                            $godown_array[$j]['godown_id'] = 1;
                            $godown_array[$j]['godown_name'] = 'Local Store';
                            $godown_array[$j]['product_id'] = $product_id[$j];
                            $godown_flow_type = ($entry_type_id == 10 || $parent_id==10 ) ? 'quantity_out' : 'quantity_in';
                            $godown_array[$j][$godown_flow_type] = $product_quantity[$j];
                            $godown_array[$j]['transaction_type'] = ($entry_type_id == 10 || $parent_id==10) ? '6' : '5';
                         }
                    }

                    $this->inventorymodel->insertOrderDetails($productDetails);
                    //For primary godown Godown if godown status == 0 and batch status == 0
                    if($batch_status == 0 && $godown_status == 0){
                        $this->inventorymodel->insertGodown($godown_array);
                    }
                }

                //For Batch AND Godown
                if (count($batchGodown) > 0) {
                    // $dataBanking = json_decode($dataBanking);
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
                                    $batch_array[$b]['stock_id'] = $orderId; //as order id in many plase
                                    $batch_array[$b]['godown_id'] = ($batchValue[$j]->batch_godown_id)?($batchValue[$j]->batch_godown_id):1;
                                    $batch_array[$b]['product_id'] = $batchGodown[$k]['product_id'];
                                    $batch_array[$b]['batch_no'] = $batchValue[$j]->batch_no;
                                    $batch_array[$b]['parent_id'] = ($entry_type_id == 10 || $parent_id==10 ) ? $batchValue[$j]->batch_id : 0;
                                    $batch_array[$b]['in_out'] = ($entry_type_id == 10 || $parent_id==10 ) ? 2 : 1;
                                    $batch_array[$b]['quantity'] = $batchValue[$j]->batch_qty;
                                    $batch_array[$b]['rate'] = $batchValue[$j]->batch_rate;
                                    $batch_array[$b]['value'] = $batchValue[$j]->batch_value;
                                    $batch_array[$b]['exp_type'] = $batchValue[$j]->exp_type_id;
                                    $batch_array[$b]['exp_days_given'] = $batchValue[$j]->exp_days;
                                    $batch_array[$b]['manufact_date'] = $manufact_date;
                                    $batch_array[$b]['exp_date'] = $expiry_date;
                                    $batch_array[$b]['transaction_type'] = ($entry_type_id == 10 || $parent_id==10) ? '6' : '5';
                                    $b++;
                                }
                                // For godown
                                $godown_array[$g]['entry_id'] = $entryId;
                                $godown_array[$g]['stock_id'] = $orderId;
                                $godown_array[$g]['godown_id'] = ($batchValue[$j]->batch_godown_id)?$batchValue[$j]->batch_godown_id:1;
                                $godown_array[$g]['godown_name'] = ($batchValue[$j]->batch_godown_name)?$batchValue[$j]->batch_godown_name:'Local Store';
                                $godown_array[$g]['product_id'] = $batchGodown[$k]['product_id'];
                                $godown_flow_type = ($entry_type_id == 10 || $parent_id==10 ) ? 'quantity_out' : 'quantity_in';
                                $godown_array[$g][$godown_flow_type] = ($batchValue[$j]->productBatchStatus == 1 && $batch_status == 1)?$batchValue[$j]->batch_qty:$batchValue[$j]->godown_qty;
                                $godown_array[$g]['transaction_type'] = ($entry_type_id == 10 || $parent_id==10) ? '6' : '5';
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
                    $data_msg['res'] = 'success';
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
                    $data_msg['print_url'] = base_url('transaction/invoice') . '.aspx/' . $entryId . '/' . $entry_type_id;
                    $data_msg['redirect_url'] = base_url('admin/inventory-transaction-list'). "/" .  str_replace(' ','-',strtolower(trim($entry_type['type']))).'/'.$entry_type['id'].'/all';
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

    public function ajax_update_note() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {

            $entry_id = $this->input->post('entry_id');
            $entry_number = $this->input->post('entry_number');
            $date_form = $this->input->post('date_form');
            $created_date = date("Y-m-d", strtotime(str_replace('/', '-', $date_form)));
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

            //branch id
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

            //Shiping Address ID
            $shipping_id = $this->input->post('shipping_id');

            //GST
            $total_price_per_prod = $this->input->post('total_price_per_prod');
            $gross_total_price_per_prod = $this->input->post('gross_total_price_per_prod');
            $total_price_per_prod_with_tax = $this->input->post('total_price_per_prod_with_tax');
            $terms_and_conditions = $this->input->post('terms_and_conditions');
            $voucher_no = $this->input->post('voucher_no');
            $voucher_date = $this->input->post('voucher_date');
            $voucher_date = date("Y-m-d", strtotime(str_replace('/', '-', $voucher_date)));

            //godown and batch =============
            $batchGodown = isset($_POST['batchGodown']) ? $_POST['batchGodown'] : null;
            $godown_status = $this->input->post('godown_status');
            $batch_status = $this->input->post('batch_status');


            $count = count($tr_ledger_id);

            $entry_type_id = $this->input->post('entry_type');
            $parent_id = $this->input->post('parent_id');
            $entry_type = $this->inventorymodel->getEntryTypeById($entry_type_id);
            if ($entry_number != 'Auto' || $entry_number != null || $entry_number != '') {
                $this->form_validation->set_rules('entry_number', 'Entry Number', 'required|callback_alpha_dash_space');
            }


            $this->form_validation->set_rules('date_form', 'Transaction date', 'required');

            if ($this->form_validation->run() === TRUE) {
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
                if ($entry_type_id == 10 || $parent_id==10) {
                    $gst_type = 'Cr';
                    if ($igst_status == 1) {
                        $igst_ledger_id = 31;
                    } else {
                        $cgst_ledger_id = 33;
                        $sgst_ledger_id = 35;
                    }
                    $cess_type = 'Cr';
                    $cess_ledger_id = 37;
                } else if ($entry_type_id == 9 || $parent_id==9) {
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
                $product_grand_total = $netTotal; //08/03/2018
                $debtors_amount = $product_grand_total;

                for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
                    $discount_sum+=$discount_value_hidden[$i];
                }
                if ($entry_type_id == 10 || $parent_id==10) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                } else if ($entry_type_id == 9 || $parent_id==9) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                }

                $isDataNewRef = 1;

                $baseCurrency = $this->entry->getDefoultCurrency();
                if ($baseCurrency) {
                    $currency_unit = $this->entry->getCurrencyUnitById($baseCurrency['base_currency']);
                }


                $base_unit = $currency_unit['unit_price'];
                $selected_currency = $currency_unit['id'];

//                for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
//                    if ($account_type[$i] == 'Cr') {
//                        if ($entry_type_id == 10) {
//                            $ledger_value += $discount_value_hidden[$i];
//                        } else if ($entry_type_id == 9) {
//                            $ledger_value -= $discount_value_hidden[$i];
//                        }
//                    }
//                    if (!empty($discount_value_hidden[$i])) {
//                        if ($account_type[$i] == 'Dr') {
//                            if ($entry_type_id == 10) {
//                                $debtors_amount -= $discount_value_hidden[$i];
//                            } else if ($entry_type_id == 9) {
//                                $debtors_amount += $discount_value_hidden[$i];
//                            }
//                        }
//                        if ($account_type[$i] == 'Cr') {
//                            if ($entry_type_id == 10) {
//                                $debtors_amount += $discount_value_hidden[$i];
//                            } else if ($entry_type_id == 9) {
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
                    'ledger_ids_by_accounts' => $ledger_name_json,
                    'dr_amount' => ($entry_type_id == 10 || $parent_id==10) ? ($sum_dr * $base_unit) : ($sum_dr * $base_unit),
                    'cr_amount' => ($entry_type_id == 10 || $parent_id==10) ? ($sum_cr * $base_unit) : ($sum_cr * $base_unit),
                    'unit_price_dr' => ($entry_type_id == 10 || $parent_id==10) ? $sum_dr : $sum_dr,
                    'unit_price_cr' => ($entry_type_id == 10 || $parent_id==10) ? $sum_cr : $sum_cr,
                    'narration' => $narration,
                    'voucher_no' => $voucher_no,
                    'voucher_date' => $voucher_date
                );



                $this->db->trans_begin();

                $res = $this->inventorymodel->updateTempEntry($entry, $entry_id);

                //delete ledger details
                $this->inventorymodel->deleteTempEntryDetails($entry_id);

                // For Ladger Account Details Table
                $ledgerDetails = array();
                $balance = 0;

                $index = 0;

                // debtors
                $ledgerDetails[0]['branch_id'] = $branch_id;
                $ledgerDetails[0]['account'] = $account_type[0];
                $ledgerDetails[0]['balance'] = ($netTotal * $base_unit);
                $ledgerDetails[0]['ladger_id'] = $tr_ledger_id[0];
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
                $ledgerDetails[1]['ladger_id'] = $tr_ledger_id[1];
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
                    $ledgerDetails[$index]['ladger_id'] = $cess_ledger_id;
                    $ledgerDetails[$index]['create_date'] = $created_date;
                    $ledgerDetails[$index]['narration'] = $narration;
                    $ledgerDetails[$index]['selected_currency'] = $selected_currency;
                    $ledgerDetails[$index]['unit_price'] = $base_unit;
                    $ledgerDetails[$index]['discount_type'] = 0;
                    $ledgerDetails[$index]['discount_amount'] = '';
                    $ledgerDetails[$index]['deleted'] = 0;
                }

                if (is_array($discount_value_hidden) && count($discount_value_hidden) > 1) {
                    for ($i = 1; $i <= count($discount_value_hidden) - 1; $i++) {
                        $index++;
                        $j = $i + 1;
                        if (!empty($discount_value_hidden[$i - 1])) {
                            $balance = $discount_value_hidden[$i - 1];
                        }
                        $ledgerDetails[$index]['branch_id'] = $branch_id;
                        $ledgerDetails[$index]['account'] = $account_type[$j];
                        $ledgerDetails[$index]['balance'] = ($balance * $base_unit);
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

                //update ledger details
                foreach ($ledgerDetails as $row) {
                    $exist = $this->inventorymodel->getLedgerDetailsTemp($entry_id, $row['ladger_id']);
                    $ledger_details = array(
                        'entry_id' => $entry_id,
                        'account' => $row['account'],
                        'balance' => $row['balance'],
                        'ladger_id' => $row['ladger_id'],
                        'create_date' => $created_date,
                        'narration' => $narration,
                        'selected_currency' => $row['selected_currency'],
                        'unit_price' => $row['unit_price'],
                        'discount_type' => $row['discount_type'],
                        'discount_amount' => $row['discount_amount'],
                        'status' => '1',
                        'deleted' => '0'
                    );
//                    if ($exist) {
//                        $this->inventorymodel->updateLedgerDetailsTemp($entry_id, $row['ladger_id'], $ledger_details);
//                    } else {
                    $this->inventorymodel->insertLedgerDetailsTemp($ledger_details);
//                    }
                }



                // For Order
                $order = array(
                    'branch_id' => $branch_id,
                    'users_id' => $tr_ledger_id[0],
                    'total' => ($product_grand_total * $base_unit),
                    'creation_date' => $created_date,
                    'tax_amount' => ($tax_value * $base_unit),
                    'grand_total' => ($product_grand_total * $base_unit),
                    'currency_code' => $selected_currency,
                    'terms_and_conditions' => $terms_and_conditions,
                    'is_igst_included' => $igst_status,
                    'is_cess_included' => $cess_status,
                );

                $order_type = ($entry_type_id == 10 || $parent_id==10) ? 6 : 5;
                $order_data = $this->inventorymodel->getOrderId($entry_id,$order_type);
                $orderId = $order_data->id;
                $res = $this->inventorymodel->updateOrder($orderId, $order);

                //shipping address update
                if($shipping_id != ''){
                    $table = 'orders';
                    $this->setOrderAddressDetails($orderId,$tr_ledger_id[0],$shipping_id,$table,$isDataNewRef);
                }

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
                            'flow_type' => ($entry_type_id == 10 || $parent_id==10) ? '66' : '55',
                            'status' => '1',
                            'order_type' => ($entry_type_id == 10 || $parent_id==10) ? '6' : '5'
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
//                        if(count($batch_array) > 0){
//                            $this->inventorymodel->deleteBatch($orderId);
//                            $this->inventorymodel->insertBatch($batch_array);
//                        }
//                        $this->inventorymodel->deleteGodown($orderId);
//                        $this->inventorymodel->insertGodown($godown_array);
                    }
                }


                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'save_err';
                    $data_msg['message'] = "There was an error submitting the form";
                } else {
                    $this->db->trans_commit();
                    $data_msg['res'] = 'success';
                    $data_msg['print_url'] = base_url('transaction/invoice') . '.aspx/' . $entry_id . '/' . $entry_type_id;
                    $data_msg['redirect_url'] =  base_url('admin/inventory-transaction-list'). "/" .  str_replace(' ','-',strtolower(trim($entry_type['type']))).'/'.$entry_type['id'].'/all';
                    // $data_msg['redirect_url'] = $redirect_url;
                    // $data_msg['print_url'] = $print_url;
                    $data_msg['message'] = "Transaction Updated successfully.";
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = $this->form_validation->error_array();
            }
            echo json_encode($data_msg);
        }
    }

    public function invoice($id, $entry_type_id ,$sub_voucher_id = 0) {
        //access
        if($entry_type_id==5){
            $module=194;
        }elseif ($entry_type_id==6) {
         $module=195;
        }elseif ($entry_type_id==7) {
         $module=196;
        }elseif ($entry_type_id==8) {
         $module=197;
        }elseif ($entry_type_id==10) {
         $module=199;
        }elseif ($entry_type_id==9) {
         $module=200;
        }elseif ($entry_type_id==14) {
         $module=202;
        }elseif ($entry_type_id==12) {
         $module=203;
        }
         user_permission($module,'view');
        //access
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
//         echo "<pre>"; print_r($data);
//         exit();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Invoice');
        $this->layouts->render('inventory/invoice', $data, 'admin');
    }

    public function getAdvanceBill() {
        $bill_name = $this->input->post('bill_name');
        $ledger_id = $this->input->post('ledger_id');
        $all_advance_bill = $this->inventorymodel->allAdvanceBill($bill_name, $ledger_id);

        if (count($all_advance_bill) == 0) {
            echo json_encode("");
        } else {

            $billName = '';

            $billName = '[';

            foreach ($all_advance_bill as $value) {
                $billName .= " { \"label\": \"$value->bill_name\", \"value\": \"$value->bill_name\"},";
            }

            $billName = substr($billName, 0, -1);
            $billName .= ' ]';

            echo $billName;
        }
    }

    public function getAllContacts() {
        if ($this->input->is_ajax_request()) {
            $term = $this->input->get('q');
            $all_contacts = $this->inventorymodel->allContacts($term);
            $item=[];
            if (count($all_contacts) == 0) {
                echo json_encode($item);
            } else {
                foreach ($all_contacts as $value) {
                    $item[]=(object)array(
                        'id'=>$value->id,
                        'text'=>$value->company_name,
                        'value'=>$value->company_name
                    );
                }
                echo json_encode($item);
            }
        }
    }



    //  somnath - generate invoice pdf
    public function savePdf(){
        $data_msg=[];
        $this->load->library('dompdf1');
        ob_start();
        $htmlContent = ob_get_clean();
        $htmlContent = $this->input->post('content');
        $pdf_name = $this->input->post('pdf_name');

        // create directory is not exist otherwise give the permission
        if (!file_exists(FCPATH . "assets/pdf_for_mail_uploads")) {
            mkdir(FCPATH . "assets/pdf_for_mail_uploads", 0777, true);
        } else {
            chmod(FCPATH . "assets/pdf_for_mail_uploads", 0777);
        }

        $fileName = $pdf_name . '.pdf';
        if( ($this->dompdf1->generatePdf($htmlContent, $fileName))==1 ){
            $previewUrl = base_url()."assets/pdf_for_mail_uploads/".$fileName;
            echo $previewUrl;exit();

            $data_msg['res']='success';
            $data_msg['filename']=$fileName;

            $path = FCPATH."assets/pdf_for_mail_uploads/".$fileName;

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Content-Length: '.filesize($path));
            ob_clean();
            flush();
            readfile($path);
            unlink($path);
            exit();
        }
    }

    //  somnath - generate invoice pdf
    public function saveInoicePdf(){
        $data_msg=[];
        $this->load->library('dompdf1');
        ob_start();
        $htmlContent    = ob_get_clean();
        $htmlContent    = $this->input->post('content');
        $pdf_name       = $this->input->post('pdf_name');
        $no_of_print    = $this->input->post('no_of_print');

        // create directory is not exist otherwise give the permission
        if (!file_exists(FCPATH . "assets/pdf_for_mail_uploads")) {
            mkdir(FCPATH . "assets/pdf_for_mail_uploads", 0777, true);
        } else {
            chmod(FCPATH . "assets/pdf_for_mail_uploads", 0777);
        }

        $newHtmlContent = "";
        for ($i=0; $i < $no_of_print; $i++) {
            if ($i == 0) {
                $newHtmlContent .= "<div class='multi-copy-text'>Original Copy</div>";
            } else if ($i == 1) {
                $newHtmlContent .= "<div class='multi-copy-text'>Duplicate Copy</div>";
            } else if ($i == 2) {
                $newHtmlContent .= "<div class='multi-copy-text'>Triplicate Copy</div>";
            } else if ($i > 2){
                $newHtmlContent .= "<div class='multi-copy-text'>Copy ".($i+1)."</div>";
            }
            $newHtmlContent .= $htmlContent;
        }

        // $fileName = $pdf_name . time() . '.pdf';
        $fileName = $pdf_name . '.pdf';
        if( ($this->dompdf1->generatePdf($newHtmlContent, $fileName))==1 ){
            $previewUrl = base_url()."assets/pdf_for_mail_uploads/".$fileName;
            echo $previewUrl;exit();
        }

    }

    /*
     * somnath - check the entry no is duplicate or not
     */
    public function checkDuplicateEntryno()
    {
        $entry_number = $this->input->post('entry_number');
        $entry_type_id = $this->input->post('entry_type_id');
        $res = $this->inventorymodel->checkDuplicateEntryno($entry_number, $entry_type_id);
        if ($res > 0) {
            echo "Duplicate entry no";
        } else {
            echo "";
        }

    }

    /*
     * somnath - check the voucher/supplier no is duplicate or not
     */
    public function checkDuplicateVoucherno()
    {
        $action = $this->input->post('action');
        $entry_id = $this->input->post('entry_id');
        $voucher_no = $this->input->post('voucher_no');
        $entry_type_id = $this->input->post('entry_type_id');
        $res = $this->inventorymodel->checkDuplicateVoucherno($voucher_no, $entry_type_id, $action, $entry_id);
        if ($res > 0) {
            echo "Duplicate no";
        } else {
            echo "";
        }

    }

    public function getAllGroupsByAjax()
    {
        $groups = $this->inventorymodel->getAllGroups();
        $html = '<option value="">Select group</option>';
        foreach ($groups as $group) {
            $html .= '<option value="' . $group["id"] . '">' . $group["group_name"] . '</option>';
        }
        echo $html;
    }


    public function loadMoreByAjax()
    {
        $this->load->view('inventory/loadmore');
    }


    public function loadMoreRecords()
    {
        $limit = $this->input->post('limit');
        $offset = $this->input->post('offset');
        // $this->db->limit($limit, $offset);
        // $data['gst'] = $this->db->get('gst_units')->result();

        $name = ( $this->input->post('name')) ?  $this->input->post('name') : null;
        $month = ( $this->input->post('month')) ?  $this->input->post('month') : null;
        $id = ( $this->input->post('id')) ?  $this->input->post('id') : null;
                $data = [];
                $financial_year = get_financial_year();
                $from_date = date("Y-m-d", strtotime(current($financial_year)));
                $to_date = date("Y-m-t", strtotime(end($financial_year)));
                if ($month != 'all') {
                    $from_date = date("Y-m-d", strtotime($month));
                    $to_date = date("Y-m-t", strtotime($month));
                }
                $entry_type=$this->inventorymodel->getVoucherType($id);
                $data['entry_type_id'] = $id;
                $data['parent_id']=$entry_type['parent_id'];
                $data['month'] = $month;
                $data['voucher_name'] = str_replace(' ', '-', strtolower(trim($name)));
                $data['sub_vouchers'] = $this->inventorymodel->getAllSubVouchersById($id);

                $data['voucher_type'] = $entry_type;
                if ($id == 5 || $id == 6 || $entry_type['parent_id']==5 || $entry_type['parent_id']==6) {
                    $data['all_entries'] = $this->inventorymodel->getAllEntry($id,$entry_type['parent_id'],$from_date, $to_date, $limit, $offset);
                    $data['all_post_dated_entries'] = $this->inventorymodel->getAllPostDatedEntry($id,$entry_type['parent_id']);
                    $data['all_recurring_entries'] = $this->inventorymodel->getAllRecurringEntry($id,$entry_type['parent_id']);
                } elseif ($id == 7 || $id == 8 || $entry_type['parent_id']==7 || $entry_type['parent_id']==8) {
        //            if ($id == 7) {
                        $data['all_entries'] = $this->getROrderDetails($id,$entry_type['parent_id'], $limit, $offset);
        //            } else {
        //                $data['all_entries'] = $this->inventorymodel->getAllRequestEntry($id);
        //            }
                } elseif ($id == 9 || $id == 10 || $entry_type['parent_id']==9 || $entry_type['parent_id']==10) {
                    $data['all_entries'] = $this->inventorymodel->getAllTempEntry($id,$entry_type['parent_id'], $limit, $offset);
                } elseif ($id == 12 || $id == 14 || $entry_type['parent_id']==12 || $entry_type['parent_id']==14) {
                    $data['all_entries'] = $this->inventorymodel->getAllEntry($id,$entry_type['parent_id'],$from_date, $to_date, $limit, $offset);
                }

        if(empty($data['all_entries'])){
            echo "";
        }else{
            $this->load->view('inventory/loadmore_records', $data);
        }

    }

    public function getShippingAddress()
    {
        $id = $this->input->post('contact_id');
        $shipping_address = $this->inventorymodel->getMultiShippingAddress($id);
        echo json_encode($shipping_address);
    }

    public function setOrderAddressDetails($orderId,$ledgerId,$shipping_id,$update,$isDataNewRef){
        $shippingAddress = $this->custominventorymodel->getShippingAddressByID($shipping_id);
        $order = [];
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

    public function setQuotationOrderAddressDetails($orderId,$ledgerId,$shipping_id,$update,$isDataNewRef){
        $shippingAddress = $this->custominventorymodel->getShippingAddressByID($shipping_id);
        $order = [];
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
            $this->inventorymodel->updateQuotationOrderRequestByOrderId($orderId, $order);
        }

        return;
    }

    public function getTransactionDetailsByEntry()
    {
        $entry_id = $this->input->post('entry_id');
        $data['details'] = $this->inventorymodel->getTransactionDetailsByEntry($entry_id);
        $this->load->view('transaction_inventory/inventory/pop_up_details', $data);
    }


    public function testInvoice($id)
    {
        $data = array();
        $entry_type_id = 7;
        // $id = ($id) ? $id:2;
        $sub_voucher_id = 0;
        // $this->load->model('transaction_inventory/inventory/inventorymodel');
        $entry_type=$this->inventorymodel->getEntryTypeById($entry_type_id);
        $parent_id=$entry_type['parent_id'];
        $entry = $this->inventorymodel->getRequestEntry($id);
        $data['entry'] = $entry;
        $data['entry_details'] = $this->inventorymodel->getRequestEntryDetails($id);
        $order = $this->inventorymodel->getRequestOrder($id);

        $data['order'] = $order;
        $data['order_details'] = $this->inventorymodel->getRequestOrderDetails($order->id);

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



    public function getAvailableContact() {
        if ($this->input->is_ajax_request()) {
            $term = $this->input->get('q');
            $all_contacts = $this->inventorymodel->getAvailableContact($term);
            $item=[];
            if (count($all_contacts) == 0) {
                echo json_encode($item);
            } else {
                foreach ($all_contacts as $value) {
                    $item[]=(object)array(
                        'id'=>$value->id,
                        'text'=>$value->company_name,
                        'value'=>$value->company_name
                    );
                }
                echo json_encode($item);
            }
        }
    }


    public function despatchDetailsById(){
        $id = $this->input->post('courier_id');
        $despatchDetails = $this->inventorymodel->getDespatchDetailsById($id);
        if(!empty($despatchDetails)){
            echo json_encode(array('FLAG'=>TRUE,'RESULT'=>$despatchDetails,'MSG'=>'Success'));
        }else{
            echo json_encode(array('FLAG'=>FALSE,'MSG'=>'Error'));
        }

    }

    public function showCourierModalInvoice() {
        $despatch_through = $this->input->post('despatch_through');
        $all_advance_bill = $this->inventorymodel->getAllDespatchDetails($despatch_through);

        if (count($all_advance_bill) == 0) {
            echo json_encode("");
        } else {

            $billName = '';

            $billName = '[';

            foreach ($all_advance_bill as $value) {
                $billName .= " { \"label\": \"$value->despatch_through\", \"value\": \"$value->id\"},";
            }

            $billName = substr($billName, 0, -1);
            $billName .= ' ]';

            echo $billName;
        }
    }

    public function despatchDetailsByEntryId(){
        $entry_id = $this->input->post('entry_id');
        $despatchDetails = $this->inventorymodel->getDespatchDetailsByEntryId($entry_id);
        if(!empty($despatchDetails)){
            echo json_encode(array('FLAG'=>TRUE,'RESULT'=>$despatchDetails,'MSG'=>'Success'));
        }else{
            echo json_encode(array('FLAG'=>FALSE,'MSG'=>'Error'));
        }
    }

    public function entry_delete($entry_id = '', $entry_type_id = '')
    {
        // echo $entry_type_id;
        $this->inventorymodel->deleteTransaction($entry_id,$cancel=0);
        if ($entry_type_id == 5) {
            redirect(base_url('admin/inventory-transaction-list/sales/5/all'));
        } else if ($entry_type_id == 6) {
            redirect(base_url('admin/inventory-transaction-list/purchase/6/all'));
        }

    }

    public function getBatchData(){
        if ($this->input->is_ajax_request()) {
            $product_id = $this->input->get('product_stock_id');
            $batch = $this->inventorymodel->getAvailableContact($product_id);
            $item=[];
            if (count($all_contacts) == 0) {
                echo json_encode($item);
            } else {
                foreach ($all_contacts as $value) {
                    $item[]=(object)array(
                        'id'=>$value->id,
                        'text'=>$value->company_name,
                        'value'=>$value->company_name
                    );
                }
                echo json_encode($item);
            }
        }
    }

    public function getGodownListById()
    {
        $search_val = $this->input->post('search_value');
        $product_id = $this->input->post('product_stock_id');
        $response = $this->inventorymodel->getAllGodownsByProductId($search_val,$product_id);
//        $response = $this->inventorymodel->getAllGodownsByProductId();
        $data = [];
        foreach ($response as $key => $value) {
            $data[$key]['id'] = $value->godown_id;
            $data[$key]['value'] = $value->godown_name.' [Stock- '.$value->quantity.']';
            $data[$key]['godown_name'] = $value->godown_name;
        }
//        foreach ($response as $key => $value) {
//            $data[$key]['id'] = $value->godown_id;
//            $data[$key]['country'] = $value->godown_name;
//            $data[$key]['code'] = $value->quantity;
//        }


        echo json_encode($data);
    }

    public function getBatchByGodownIdAndProductId(){
        $search_val = $this->input->post('search_value');
        $product_id = $this->input->post('product_stock_id');
        $godown_id = $this->input->post('godown_id');
        $response = $this->inventorymodel->getAllBatchByGodownIdProductId($search_val,$product_id,$godown_id);
        $data = [];
        foreach ($response as $key => $value) {
            $exp_type_value = '';
            if($value->exp_type == 2){
                $exp_type_value = '(Months)';
            }elseif($value->exp_type == 3){
                $exp_type_value = '(Days)';
            }
            $data[$key]['id'] = $value->id;
            $data[$key]['value'] = $value->batch_no.' [Stock- '.$value->quantity.'] | '.$value->manufact_date.' | '.$value->exp_days_given.' '.$exp_type_value;

            $data[$key]['batch_no'] = $value->batch_no;
            $data[$key]['manufact_date'] = $value->manufact_date;
            $data[$key]['exp_days_given'] = $value->exp_days_given;
            $data[$key]['exp_type'] = $value->exp_type;
        }
//        foreach ($response as $key => $value) {
//            $exp_type_value = '';
//            if($value->exp_type == 2){
//                $exp_type_value = '(Month(s))';
//            }elseif($value->exp_type == 3){
//                $exp_type_value = '(Days)';
//            }
//            $data[$key]['id'] = $value->id;
//            $data[$key]['country'] = $value->batch_no;
//            $data[$key]['code'] = $value->quantity;
//            $data[$key]['manufact_date'] = $value->manufact_date;
//            $data[$key]['exp_date'] = $value->exp_days_given.' '.$exp_type_value;
//        }
        echo json_encode($data);
    }

    public function getBatchByBatchId(){
        $batchId = $this->input->post('batchId');
        $response = $this->inventorymodel->getAllBatchByBatchId($batchId);
        $data = [];
        if($response) {
            $data['id'] = $response->id;
            $data['batch_no'] = $response->batch_no;
            $data['manufact_date'] = $response->manufact_date;
            $data['exp_days_given'] = $response->exp_days_given;
            $data['exp_type'] = $response->exp_type;
            }

        echo json_encode($data);
    }

    public function aaaaaaaaaa(){
        $data[0]['id'] = 1;
        $data[0]['country'] = 'Afghanistan';
        $data[0]['code'] = 'AFG';
        $data[0]['capital'] = 'Kabul';
        $data[0]['ssss'] = 'Kabul';
        $data[1]['id'] = 2;
        $data[1]['country'] = 'Afghanistan';
        $data[1]['code'] = 'AFG';
        $data[1]['capital'] = 'Kabul';
        $data[1]['ssss'] = 'Kabul';
        echo json_encode($data);
    }

    public function quotation($id = 'n', $type_id = 25, $action = 'a'){
      //access
        if($action=='a'){
       $ac='add';
       }elseif($action=='e'){
        $ac='edit';
       }elseif($action=='c'){
         $ac='add';
       }elseif($action=='t'){
         $ac='edit';
       }

        $module=196;

        user_permission($module,$ac);
        //access
        $data = array();
        $data['action'] = $action;
        $entry_type_id = $type_id;
        $data['entry_type'] = $type_id;
        $data['bank_id'] = '';
        $entry_type=$this->inventorymodel->getEntryTypeById($type_id);
        $bank_details=$this->inventorymodel->getDefauldBankId();

        $parent_id=$entry_type['parent_id'];
        $data['parent_id'] = $parent_id;
        if ($action == 'e' || $action == 't') {
            if ($entry_type_id == 25 || $parent_id==25) {
                $entry = $this->inventorymodel->getRequestEntryForQuotation($id);
                $data['entry'] = $entry;
                $data['entry_details'] = $this->inventorymodel->getRequestEntryDetailsForQuotation($id);
                $order = $this->inventorymodel->getRequestOrderForQuotation($id);
                $data['order'] = $order;
                // if (($entry_type_id == 17 || $parent_id==17) && $action == 't') {
                //     $data['order_details'] = $this->getOrderDetails($order->id, $entry->entry_no);
                // } else {
                //     $data['order_details'] = $this->inventorymodel->getRequestOrderDetailsForQuotation($order->id);

                // }
                $data['order_details'] = $this->inventorymodel->getRequestOrderDetailsForQuotation($order->id);
            }
        }else{
            $data['bank_id'] = isset($bank_details->id)?$bank_details->id:'';
        }
        if (($entry_type_id == 25 || $parent_id==25) && $action == 't') {
            $entry_type_id = 7;
        } else {
            $entry_type_id == 25;
        }
        // if (($entry_type_id == 8 || $parent_id==8) && $action == 't') {
        //     $entry_type_id = 6;
        // } else {
        //     $entry_type_id == 8;
        // }
        $data['voucher'] = $entry_type['type'];
        $data['voucher_id'] = $entry_type_id;
        $data['transaction_type_id'] = $entry_type_id;
        $data['auto_no_status'] = $entry_type['transaction_no_status'];

        $date_type = $this->inventorymodel->checkAutoDate();

        $data['auto_date'] = $date_type['skip_date_create'];
        $data['recurring'] = $date_type['want_recurring'];
        $ledger_code_status = $this->inventorymodel->getLedgerCodeStatus();
        $data['ledger_code_status'] = $ledger_code_status['ledger_code_status'];
        $data['groups'] = $this->inventorymodel->getAllGroups();
        $data['contacts'] = $this->account->getContact();
        $data['currency'] = $this->inventorymodel->getAllCurrency();
        if ($action == 'e' || $action == 't') {
            $cash_bank_group = [10,11];
                $all_sub_group = $this->inventorymodel->getAllSubGroup($cash_bank_group);
                for ($i = 0; $i < count($cash_bank_group); $i++) {
                    array_push($all_sub_group, $cash_bank_group[$i]);
                }
                $ledgerIdArr = $this->inventorymodel->getLedgerByGroupsId($all_sub_group);

            if(!in_array($data['entry_details'][0]->ladger_id,array_column($ledgerIdArr,'id'))){
                    //shipping
                    $ledgerId = $data['entry_details'][0]->ladger_id;
                    $ledgerLimitDetails = $this->inventorymodel->ledgerLimitDetails($ledgerId);

                     /* For Ledger Limits */
                    $arr['LL_creditLimit'] = $ledgerLimitDetails->credit_limit;
                    $arr['LL_creditDays'] = $ledgerLimitDetails->credit_date;

                    $ship = $this->inventorymodel->getShippingAddress($ledgerId);
                    if (!empty($ship)) {
                        $shippingAddress = $ship[0];
                    } else {
                        $shippingAddress = array();
                    }

                    $arr['country'] = $order->shipping_country;
                    $arr['state'] = $order->shipping_state;
                    /* For Shipping Addr */

                    $arr['Sh_companyName'] = $order->shipping_first_name;
                    $arr['Sh_address'] = $order->shipping_address;
                    $arr['Sh_city'] = $order->shipping_city;
                    $arr['Sh_zip'] = $order->shipping_zip;
                    $arr['Sh_state'] = $order->shipping_state_name;
                    $arr['Sh_country'] = $order->shipping_country_name;

                    /* For Billing Addr */

                    $arr['Bi_companyName'] = $order->billing_first_name;
                    $arr['Bi_address'] = $order->billing_address;
                    $arr['Bi_city'] = $order->billing_city;
                    $arr['Bi_zip'] = $order->billing_zip;
                    $arr['Bi_state'] = $order->billing_state_name;
                    $arr['Bi_country'] = $order->billing_country_name;
                    $arr['Bi_tax'] = $shippingAddress->sales_tax_no;
                    $data['ledger'] = $arr;
                    //billing


                }else{
                    $company_details = $this->inventorymodel->getBranchDetails();
                    $arr['sale_type'] = 'cash';
                    $arr['country'] = $company_details->country_id;
                    $arr['state'] = $company_details->state_id;
                    $arr['Bi_state'] = $company_details->state_name;

                    /* For Shipping Addr */
                    $arr['Bi_companyName'] = $order->billing_first_name;

                    $arr['Sh_companyName'] = $order->shipping_first_name;
                    $arr['Sh_address'] = $order->shipping_address;
                    $arr['Sh_city'] = $order->shipping_city;
                    $arr['Sh_zip'] = $order->shipping_zip;
                    $arr['Sh_state'] = $order->shipping_state_name;
                    $arr['Sh_country'] = $order->shipping_country_name;

                    $data['ledger'] = $arr;

                }
        }
        $getsitename = getsitename();
        if ($action == 'e') {
            $this->layouts->set_title($getsitename . ' | Transaction:: Update');
        } else {
            $this->layouts->set_title($getsitename . ' | Quotation Add');
        }
        $this->layouts->render('inventory/quotation', $data, 'admin_new');
    }

    public function quotation_add() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {

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
            $product_discount = $this->input->post('product_discount');//16072018
            $tax_percent = $this->input->post('tax_percent');
            $tax_value = $this->input->post('tax_value');
            $total_price_per_prod = $this->input->post('total_price_per_prod');
            $gross_total_price_per_prod = $this->input->post('gross_total_price_per_prod');
            $total_price_per_prod_with_tax = $this->input->post('total_price_per_prod_with_tax');
            $count = count($tr_ledger_id);
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

            //GST
            $voucher_no = $this->input->post('voucher_no');
            $voucher_date = $this->input->post('voucher_date');
            $voucher_date = date("Y-m-d", strtotime(str_replace('/', '-', $voucher_date)));
            $entry_type_id = $this->input->post('entry_type');
            $entry_type = $this->inventorymodel->getEntryTypeById($entry_type_id);
            $parent_id = $this->input->post('parent_id');
            $created_date = date("Y-m-d", strtotime(str_replace('/', '-', $date_form)));

            if ($entry_type_id == 8) {
                $this->form_validation->set_rules('tr_ledger[0]', 'Creditors', 'required');
                $this->form_validation->set_rules('tr_ledger[1]', 'Purchase', 'required');
            } else if($entry_type_id == 7 ||$entry_type_id == 25) {
                $this->form_validation->set_rules('tr_ledger[0]', 'Debtors', 'required');
                $this->form_validation->set_rules('tr_ledger[1]', 'Sales', 'required');
            }
            if ($entry_number != 'Auto' || $entry_number != null || $entry_number != '') {
                $this->form_validation->set_rules('entry_number', 'Entry Number', 'required|callback_alpha_dash_space');
            }
            $this->form_validation->set_rules('date_form', 'Transaction date', 'required');
            if ($this->form_validation->run() === TRUE) {

                if ($entry_number == 'Auto' || $entry_number == null || $entry_number == '') {

                    $entry_number = $this->getQuotationNumber($entry_type_id);
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
                if ($entry_type_id == 7 || $entry_type_id == 25 || $parent_id==7 || $parent_id==25) {
                    $gst_type = 'Cr';
                    if ($igst_status == 1) {
                        $igst_ledger_id = 31;
                    } else {
                        $cgst_ledger_id = 33;
                        $sgst_ledger_id = 35;
                    }
                    $cess_type = 'Cr';
                    $cess_ledger_id = 37;
                } else if ($entry_type_id == 8 || $parent_id==8) {
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
                if ($entry_type_id == 7 || $parent_id==7 || $entry_type_id == 25 || $parent_id==25) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                } else if ($entry_type_id == 8 || $parent_id==8) {
                    $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                }

                $isDataNewRef = 1;

                $baseCurrency = $this->entry->getDefoultCurrency();
                if ($baseCurrency) {
                    $currency_unit = $this->entry->getCurrencyUnitById($baseCurrency['base_currency']);
                }

                $base_unit = $currency_unit['unit_price'];
                $selected_currency = $currency_unit['id'];
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
                    'dr_amount' => ($entry_type_id == 25 || $parent_id==25) ? ($sum_dr * $base_unit) : ($sum_dr * $base_unit),
                    'cr_amount' => ($entry_type_id == 25 || $parent_id==25) ? ($sum_cr * $base_unit) : ($sum_cr * $base_unit),
                    'unit_price_dr' => ($entry_type_id == 25 || $parent_id==25) ? $sum_dr : $sum_dr,
                    'unit_price_cr' => ($entry_type_id == 25 || $parent_id==25) ? $sum_cr : $sum_cr,
                    'entry_type_id' => ($entry_type['parent_id']==0)?$entry_type_id:$entry_type['parent_id'],
                    'sub_voucher' => ($entry_type['parent_id']!=0)?$entry_type_id:$entry_type['parent_id'],
                    'user_id' => $user_id,
                    'company_id' => $branch_id,
                    'is_inventry' => $is_inventry,
                    'narration' => $narration,
                    'order_id' => $order_id,
                    'voucher_no' => $voucher_no,
                    'voucher_date' => $voucher_date,
                    'bank_id' => (isset($bank_id) && $bank_id != '') ? $bank_id : 0
                );

                $this->db->trans_begin();

                $entryId = $this->inventorymodel->saveQuotationRequestEntry($entry);
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

                $this->inventorymodel->saveQuotationRequestEntryDetails($ledgerDetails);

                // For Billwise Details Auto submission (without popup)
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
                    'order_type' => ($entry_type_id == 25 || $parent_id==25) ? '1' : '2',
                    'flow_type' => ($entry_type_id == 25 || $parent_id==25) ? '1' : '0',
                    'is_igst_included' => $igst_status,
                    'is_cess_included' => $cess_status,
                    'status' => '1'
                );

                $orderId = $this->inventorymodel->saveQuotationRequestOrder($order);

                $table = 'request';
                $this->setQuotationOrderAddressDetails($orderId,$tr_ledger_id[0],$shipping_id,$table,$isDataNewRef);

                // For Order Details
                if (count($product_id) > 0) {
                    $productDetails = array();

                    for ($j = 0; $j < count($product_id); $j++) {
                        $productDetails[$j]['branch_id'] = $branch_id;
                        $productDetails[$j]['order_id'] = $orderId;
                        $productDetails[$j]['product_id'] = $product_id[$j];
                        $productDetails[$j]['product_description'] = $product_description[$j];//19042018
                        $productDetails[$j]['stock_id'] = $stock_id[$j];
                        $productDetails[$j]['quantity'] = $product_quantity[$j];
                        $productDetails[$j]['original_price'] = $product_price[$j];
                        $productDetails[$j]['base_price'] = $product_price[$j];
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
                        $productDetails[$j]['flow_type'] = ($entry_type_id == 25 || $parent_id==25) ? '66' : '55';
                        $productDetails[$j]['order_type'] = ($entry_type_id == 25 || $parent_id==25) ? '1' : '2';
                    }
                    $this->inventorymodel->insertQuotationRequestOrderDetails($productDetails);
                }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'save_err';
                    $data_msg['message'] = "There was an error submitting the form";
                } else {
                    $this->db->trans_commit();

                    $data_msg['res'] = 'success';
                    $data_msg['print_url'] = base_url('transaction/invoice') . '.aspx/' . $entryId . '/' . $entry_type_id;
                    $data_msg['redirect_url'] = base_url('admin/inventory-transaction-list'). "/" .  str_replace(' ','-',strtolower(trim($entry_type['type']))).'/'.$entry_type['id'].'/all';

                    $data_msg['message'] = "Transaction added successfully. Entry number #" . $entry_number;
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = validation_errors();
            }
            echo json_encode($data_msg);
        }
    }

    public function quotationList($name = NULL,$id = 25, $month = 'all') {


        $id = (isset($_GET['id'])) ? $_GET['id'] : $id; // @somnath - id has to be set for get the purchase details & set the module for permission (statistics/purchase/purchase details by month 404 )
        $month = (isset($_GET['month'])) ? $_GET['month'] : $month;

        $entry_type=$this->inventorymodel->getVoucherType($id);
        //access
        if($id==5 || $entry_type['parent_id'] == 5){
         $module=194;
        }elseif ($id==6 || $entry_type['parent_id'] == 6) {
         $module=195;
        }elseif ($id==7 || $id==25 || $entry_type['parent_id'] == 7 || $entry_type['parent_id'] == 25) {
         $module=196;
        }elseif ($id==8 || $entry_type['parent_id'] == 8) {
         $module=197;
        }elseif ($id==10 || $entry_type['parent_id'] == 10) {
         $module=199;
        }elseif ($id==9 || $entry_type['parent_id'] == 9) {
         $module=200;
        }elseif ($id==14 || $entry_type['parent_id'] == 14) {
         $module=202;
        }elseif ($id==12 || $entry_type['parent_id'] == 12) {
         $module=203;
        }

        user_permission($module,'list');
        //access
        $data = [];
        $financial_year = get_financial_year();
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-t", strtotime(end($financial_year)));
        if ($month != 'all') {
            $from_date = date("Y-m-d", strtotime($month));
            $to_date = date("Y-m-t", strtotime($month));
        }

        $data['entry_type_id'] = $id;
        $data['parent_id']=$entry_type['parent_id'];
        $data['month'] = $month;
        $data['voucher_name'] = str_replace(' ', '-', strtolower(trim($name)));

        $service_voucher_id = 15;//It is sub voucher of purchase.20032018 @sudip
        $data['sub_vouchers'] = $this->inventorymodel->getAllSubVouchersById($id,$service_voucher_id);

        $data['voucher_type'] = $entry_type;
        // $data['all_entries'] = $this->getROrderDetails($id,$entry_type['parent_id']);

        $all_entry = $this->inventorymodel->getAllQuotationRequestEntry($id,$entry_type['parent_id'], $limit=0, $offset=0);
        $entry = [];
        foreach ($all_entry as $row) {
            $item = array(
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'company_id' => $row['company_id'],
                'entry_type_id' => $row['entry_type_id'],
                'sub_voucher' => $row['sub_voucher'],
                'entry_no' => $row['entry_no'],
                'create_date' => $row['create_date'],
                'ledger_ids_by_accounts' => $row['ledger_ids_by_accounts'],
                'dr_amount' => $row['dr_amount'],
                'cr_amount' => $row['cr_amount'],
                'unit_price_dr' => $row['unit_price_dr'],
                'unit_price_cr' => $row['unit_price_cr'],
                'narration' => $row['narration'],
                'voucher_no' => $row['voucher_no'],
                'voucher_date' => $row['voucher_date'],
                'status' => $row['status'],
                'deleted' => $row['deleted'],
                'is_inventry' => $row['is_inventry'],
                'order_id' => $row['order_id'],
                'type' => $row['type'],
                'cancel_status' => $row['cancel_status'],
                'order_status' => $this->checkOrderStatusForQuotation($row['entry_no']),
            );
            $entry[] = $item;
        }
        $data['all_entries'] = $entry;
        $data['dataCount'] = $this->inventorymodel->getCountQuotation();

        $data['accounts_configuration'] = $this->inventorymodel->getPreferences();

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Quotation List');
        $this->layouts->render('inventory/quotation_list', $data, 'admin');
    }

    public function quotationAjaxListing(){
        $draw       = $this->input->get_post('draw');
        $start      = $this->input->get_post('start');
        $length     = $this->input->get_post('length');
        $search     = $this->input->get_post('search')['value'];

        $accounts_configuration = $this->inventorymodel->getPreferences();

        $name = $_GET['name'];
        $id = $_GET['eid'];
        $month = $_GET['emonth'];
        $entry_type=$this->inventorymodel->getVoucherType($id);
        $parent_id=$entry_type['parent_id'];
        $financial_year = get_financial_year();
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-t", strtotime(end($financial_year)));

        $all_entry = $this->inventorymodel->getAllQuotationRequestEntry($id,$entry_type['parent_id'], $limit=0, $offset=0);
        $entry = [];
        foreach ($all_entry as $row) {
            $item = array(
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'company_id' => $row['company_id'],
                'entry_type_id' => $row['entry_type_id'],
                'sub_voucher' => $row['sub_voucher'],
                'entry_no' => $row['entry_no'],
                'create_date' => $row['create_date'],
                'ledger_ids_by_accounts' => $row['ledger_ids_by_accounts'],
                'dr_amount' => $row['dr_amount'],
                'cr_amount' => $row['cr_amount'],
                'unit_price_dr' => $row['unit_price_dr'],
                'unit_price_cr' => $row['unit_price_cr'],
                'narration' => $row['narration'],
                'voucher_no' => $row['voucher_no'],
                'voucher_date' => $row['voucher_date'],
                'status' => $row['status'],
                'deleted' => $row['deleted'],
                'is_inventry' => $row['is_inventry'],
                'order_id' => $row['order_id'],
                'type' => $row['type'],
                'cancel_status' => $row['cancel_status'],
                'order_status' => $this->checkOrderStatusForQuotation($row['entry_no']),
            );
            $entry[] = $item;
        }

        $result = $entry;

        foreach($result AS $entry){
            $name = "";
            $del = '';
            $subArr = array();

            $subArr[] = get_date_format($entry['create_date']);
            $entryNo = $entry['entry_no'];
            $entryNo .= '&nbsp;&nbsp;<span data-toggle="tooltip" title="Ref. No. ' . $entry['voucher_no'] . '">';
            $entryNo .= (strlen($entry['voucher_no']) > 0) ? ((strlen($entry['voucher_no']) > 6) ? '(Ref. No. ' . substr($entry['voucher_no'], 0, 6) . '...' . ')' : '(Ref. No. ' . $entry['voucher_no'] . ')') : '';
            $entryNo .= '</span>';
            $subArr[] = $entryNo;

            $l = "";
            if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $l = "<a href='" . base_url('admin/view-quotation') . '/' . $entry['id'] . "'>";
                   if (empty($entry['ledger_detail'])){

                       $led = array();
                       $devit = json_decode($entry['ledger_ids_by_accounts']);

                       $l .= "<strong>Dr </strong>";
                       if (isset($devit->Dr)) {
                           for ($i = 0; $i < count($devit->Dr); $i++) {
                               // $l .= $devit->Dr[$i];
                               $lname = explode(' ', $devit->Dr[$i]);
                               $l .= $lname[0];
                               if (isset($devit->Dr) && count($devit->Dr) > 1) {
                                   $l .= ' + ';
                               }
                               break;
                           }
                       }

                       $l .= ' / ';

                       $l .= "<strong>Cr </strong>";
                       for ($i = 0; $i < count($devit->Cr); $i++) {
                           // $l .= $devit->Cr[$i];
                            $lname = explode(' ', $devit->Cr[$i]);
                            $l .= $lname[0];
                           if (count($devit->Cr) > 1) {
                               $l .= ' + ';
                           }
                           break;
                       }

                   } else {

                    $ld = explode('/',$entry['ledger_detail']);
                    $l .= "<strong>" . $ld[0] . " / " . $ld[1] . "</strong>";
                   }

                $l .= "</a>";
            }else{
                $l = '<span class="label label-danger"><b>Canceled</b></span>';
            }

            $subArr[] = $l;
            $subArr[] = $entry['type'];

            $status = "";
            $status .= "<span class='label ";
            $status .= (isset($entry['order_status']) && $entry['order_status'] == 'Open') ? 'label-success' : ((isset($entry['order_status']) && $entry['order_status'] == 'Partial') ? 'label-primary' : 'label-danger');
            $status .= "'>" . $entry['order_status'] . "</span>";
            // $subArr[] = $status;

            if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $subArr[] = $this->price_format($entry['cr_amount']);
            }else {
                $subArr[] = "";
            }

            $max_time = strtotime("+".$accounts_configuration->entry_action_limit." Days", strtotime($entry['create_date']));
            $t = time();
            $module = 196;
            $del = '';
            if ($t < $max_time){
                if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $del = '<div class="dropdown circle">
                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-ellipsis-v"></i></a>
                    <ul class="dropdown-menu tablemenu">';

                    $permission = ua($module, 'edit');
                        if ($permission){

                            $del .= '<li>
                                        <a href="' . base_url('admin/edit-quotation') . '/' . $entry['id'] . '/' . $entry['entry_type_id'] . '/e' . '" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                    </li>';
                            if (isset($entry['order_status']) && ($entry['order_status'] == 'Open' || $entry['order_status'] == 'Partial')){
                                $del .= '<li>
                                            <a href="javascript:void(0);" data-toggle="tooltip" title="Sales Order" > <i class="fa fa-shopping-basket" aria-hidden="true"></i></a>
                                        </li>';
                            }
                        }


                        $del .= '<li>';
                        $permission = ua($module, 'delete');
                        if ($permission){
                            $del .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="'. $entry['id'] . '" delete-type="current"  data-type="request" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>';
                        }

                    $del .= '</li>
                            </ul>
                            </div>';
                }
            }
            $subArr[] = $del;

            $data['data'][] = $subArr;
        }
        $count = $this->inventorymodel->getCountQuotation();
        $data['draw']              = $draw;
        $data['recordsTotal']      = $count;
        $data['recordsFiltered']   = $count;
        echo json_encode($data);exit;
    }

    public function viewQuotation($id, $entry_type_id = 25 ,$sub_voucher_id = 0) {
        //access
        $module=196;
        user_permission($module,'view');
        //access
        $data = array();
        $data['despatchDetails'] = FALSE;
        $entry_type=$this->inventorymodel->getEntryTypeById($entry_type_id);
        $parent_id=$entry_type['parent_id'];

        $entry = $this->inventorymodel->getRequestEntryForQuotation($id);
        $data['entry'] = $entry;
        $data['entry_details'] = $this->inventorymodel->getRequestEntryDetailsForQuotation($id);
        $order = $this->inventorymodel->getRequestOrderForQuotation($id);

        $data['order'] = $order;
        $data['order_details'] = $this->inventorymodel->getRequestOrderDetailsForQuotation($order->id);


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
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | View Quotation');
        $this->layouts->render('inventory/view_quotation', $data, 'admin');
    }



        public function update_quotation_data() {
            $data_msg = [];
            if ($this->input->is_ajax_request()) {

                $entry_id = $this->input->post('entry_id');
                $entry_number = $this->input->post('entry_number');
                $date_form = $this->input->post('date_form');
                $created_date = date("Y-m-d", strtotime(str_replace('/', '-', $date_form)));
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

                //baranch id
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
                $product_discount = $this->input->post('product_discount');//16072018
                $total_price_per_prod = $this->input->post('total_price_per_prod');
                $gross_total_price_per_prod = $this->input->post('gross_total_price_per_prod');
                $total_price_per_prod_with_tax = $this->input->post('total_price_per_prod_with_tax');
                $terms_and_conditions = $this->input->post('terms_and_conditions');



                $count = count($tr_ledger_id);
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


                //GST
                $voucher_no = $this->input->post('voucher_no');
                $voucher_date = $this->input->post('voucher_date');
                $voucher_date = date("Y-m-d", strtotime(str_replace('/', '-', $voucher_date)));
                $entry_type_id = $this->input->post('entry_type');
                $parent_id = $this->input->post('parent_id');
                $entry_type = $this->entry->getEntryTypeById($entry_type_id);
                if ($entry_number != 'Auto' || $entry_number != null || $entry_number != '') {
                    $this->form_validation->set_rules('entry_number', 'Entry Number', 'required|callback_alpha_dash_space');
                }


                $this->form_validation->set_rules('date_form', 'Transaction date', 'required');

                if ($this->form_validation->run() === TRUE) {
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
                    if ($entry_type_id == 7 || $parent_id==7 || $entry_type_id == 25 || $parent_id == 25) {
                        $gst_type = 'Cr';
                        if ($igst_status == 1) {
                            $igst_ledger_id = 31;
                        } else {
                            $cgst_ledger_id = 33;
                            $sgst_ledger_id = 35;
                        }
                        $cess_type = 'Cr';
                        $cess_ledger_id = 37;
                    } else if ($entry_type_id == 8 || $parent_id==8) {
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
                    $product_grand_total = $netTotal; //08/03/2018
                    $debtors_amount = $product_grand_total;
                    for ($i = 0; $i < count($discount_value_hidden) - 1; $i++) {
                        $discount_sum+=$discount_value_hidden[$i];
                    }
                    if ($entry_type_id == 7 || $parent_id==7 || $entry_type_id == 25 || $parent_id == 25) {
                        $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                    } else if ($entry_type_id == 8 || $parent_id==8) {
                        $sales_amount = $product_grand_total + $discount_sum - $tax_value;
                    }

                    $isDataNewRef = 1;

                    $baseCurrency = $this->entry->getDefoultCurrency();
                    if ($baseCurrency) {
                        $currency_unit = $this->entry->getCurrencyUnitById($baseCurrency['base_currency']);
                    }


                    $base_unit = $currency_unit['unit_price'];
                    $selected_currency = $currency_unit['id'];

                    if ($isDataNewRef == 1) {

                        $ledgeId = $tr_ledger_id[0];

                        $hasBilling = $this->inventorymodel->getTBilling($ledgeId);
                        $hasBillingFinal = $hasBilling->bill_details_status;

                        if ($hasBillingFinal == "1") {

                            /* Fetch data */
                            $ledgeDet = $this->inventorymodel->getNewRefLedgerDetails($ledgeId);

                            $credit_date_get = $credit_days;
                            $credit_limit_get = $ledgeDet->credit_limit;

                            $getDiffDrCrBilling = $this->inventorymodel->getDiffDrCrBillingSales($ledgeId);
                            $diff = $getDiffDrCrBilling->diff;
                            $total = $debtors_amount + $diff;
                            $amount = $debtors_amount;
                            if ($entry_type_id == 25 || $parent_id==25) {
                                if ($total <= $credit_limit_get) {
                                    $bill_data = array(
                                        'ledger_id' => $tr_ledger_id[0],
                                        'dr_cr' => $account_type[0],
                                        'bill_name' => $entry_number,
                                        'credit_days' => $credit_date_get,
                                        'credit_date' => date('Y-m-d', strtotime("+" . $credit_date_get . " days")),
                                        'bill_amount' => ($amount * $base_unit)
                                    );
                                } else {

                                    $data_msg['res'] = 'save_err';
                                    $data_msg['message'] = "Your credit limit has exceeded!";

                                    echo json_encode($data_msg);
                                    exit;
                                }
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
                        'ledger_ids_by_accounts' => $ledger_name_json,
                        'dr_amount' => ($entry_type_id == 25 || $parent_id==25) ? ($sum_dr * $base_unit) : ($sum_dr * $base_unit),
                        'cr_amount' => ($entry_type_id == 25 || $parent_id==25) ? ($sum_cr * $base_unit) : ($sum_cr * $base_unit),
                        'unit_price_dr' => ($entry_type_id == 25 || $parent_id==25) ? $sum_dr : $sum_dr,
                        'unit_price_cr' => ($entry_type_id == 25 || $parent_id==25) ? $sum_cr : $sum_cr,
                        'narration' => $narration,
                        'voucher_no' => $voucher_no,
                        'voucher_date' => $voucher_date,
                        'bank_id' => (isset($bank_id) && $bank_id != '') ? $bank_id : 0
                    );



                    $this->db->trans_begin();

                    $res = $this->inventorymodel->updateQuotationRequestEntry($entry, $entry_id);

                    //delete ledger details
                    $this->inventorymodel->deleteQuotationRequestEntryDetails($entry_id);

                    // For Ladger Account Details Table
                    $ledgerDetails = array();
                    $balance = 0;

                    $index = 0;

                    // debtors
                    $ledgerDetails[0]['branch_id'] = $branch_id;
                    $ledgerDetails[0]['account'] = $account_type[0];
                    $ledgerDetails[0]['balance'] = ($debtors_amount * $base_unit);
                    $ledgerDetails[0]['entry_id'] = $entry_id;
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
                    $ledgerDetails[1]['entry_id'] = $entry_id;
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
                        $ledgerDetails[2]['entry_id'] = $entry_id;
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
                        $ledgerDetails[2]['entry_id'] = $entry_id;
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
                        $ledgerDetails[3]['entry_id'] = $entry_id;
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
                        $ledgerDetails[$index]['entry_id'] = $entry_id;
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
                            $ledgerDetails[$index]['entry_id'] = $entry_id;
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

                    //update ledger details
                    $this->inventorymodel->saveQuotationRequestEntryDetails($ledgerDetails);


                    // For Billwise Details Auto submission (without popup)

                    if (count($bill_data) > 0) {

                        //  $this->inventorymodel->updateBillwise($entry_id, $bill_data);
                    }



                    // For Order
                    $order = array(
                        'branch_id' => $branch_id,
                        'users_id' => $tr_ledger_id[0],
                        'total' => ($product_grand_total * $base_unit),
                        'spl_discount_json' => (isset($product_discount[0]) && !empty($product_discount[0]))?'1':'',
                        'creation_date' => $created_date,
                        'tax_amount' => ($tax_value * $base_unit),
                        'grand_total' => ($product_grand_total * $base_unit),
                        'currency_code' => $selected_currency,
                        'order_type' => ($entry_type_id == 25 || $parent_id==25) ? '1' : '2',
                        'flow_type' => ($entry_type_id == 25 || $parent_id==25) ? '1' : '0',
                        'is_igst_included' => $igst_status,
                        'is_cess_included' => $cess_status,
                        'terms_and_conditions' => $terms_and_conditions
                    );
                    $order_data = $this->inventorymodel->getOrderIdRequestForQuotation($entry_id);
                    $orderId = $order_data->id;
                    $res = $this->inventorymodel->updateQuotationOrderRequest($entry_id, $order);

                    if($shipping_id != ''){
                        $table = 'request';
                        $this->setQuotationOrderAddressDetails($orderId,$tr_ledger_id[0],$shipping_id,$table,$isDataNewRef);
                    }

                    $this->inventorymodel->deleteRequestOrderProductForQuotation($orderId);
                    // For Order Details
                    if (count($product_id) > 0) {


                        for ($j = 0; $j < count($product_id); $j++) {
                            $order_product = $this->inventorymodel->getRequestOrderProduct($orderId, $product_id[$j], $stock_id[$j]);
                            $productDetails = array(
                                'branch_id' => $branch_id,
                                'order_id' => $orderId,
                                'product_id' => $product_id[$j],
                                'product_description' => $product_description[$j],
                                'stock_id' => $stock_id[$j],
                                'quantity' => $product_quantity[$j],
                                'original_price' => $product_price[$j],
                                'base_price' => $product_price[$j],
                                'discount_percentage' => $product_discount[$j],
                                'discount_amount' => (($product_quantity[$j] * $product_price[$j]) - $gross_total_price_per_prod[$j]),
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
                                'flow_type' => ($entry_type_id == 7 || $parent_id==7) ? '66' : '55',
                                'status' => '1',
                                'order_type' => ($entry_type_id == 7 || $parent_id==7) ? '1' : '2'
                            );
                            if ($order_product) {
                                $this->inventorymodel->updateQuotationRequestOrderProduct($orderId, $product_id[$j], $stock_id[$j], $productDetails);
                            } else {
                                $this->inventorymodel->insertQuotationRequestOrderProduct($productDetails);
                            }
                        }
                    }



                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $data_msg['res'] = 'save_err';
                        $data_msg['message'] = "There was an error submitting the form";
                    } else {
                        $this->db->trans_commit();
                        $data_msg['res'] = 'success';
                        $data_msg['print_url'] = base_url('admin/view-quotation') . '/' . $entry_id;
                        $data_msg['redirect_url'] =base_url('admin/quotation');
                        // $data_msg['redirect_url'] = $redirect_url;
                        // $data_msg['print_url'] = $print_url;
                        $data_msg['message'] = "Transaction Updated successfully.";
                    }
                } else {
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = $this->form_validation->error_array();
                }
                echo json_encode($data_msg);
            }
        }

        public function getComplexUnitById() {
            if ($this->input->is_ajax_request()) {
                 $unit_id = $this->input->post('unit_id');
                 $units = $this->inventorymodel->getUnitTree($unit_id);
                $data = [];
                foreach ($units as $key => $value) {
                    $data[$key]['id'] = $value->unit_id;
                    $data[$key]['value'] = $value->name;
                }
                echo json_encode($data);
             }
        }

}
