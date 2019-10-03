<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class entries extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('accounts/entry');
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('financialyear');
        $this->load->model('accounts/group', 'groupmodel');
        $this->load->model('admin/companymodel');
        //$this->load->helper('admin_permission');
        admin_authenticate();
    }

    public function index($name = NULL, $id = NULL, $month = null) {
        //access
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        if(isset($_GET['month'])){
            $month = $_GET['month'];
        }
        
        $entry_type = $this->entry->getEntryTypeById($id);
        
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
         user_permission($module,'list');
        //access
        $financial_year = get_financial_year();
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-t", strtotime(end($financial_year)));
        if ($month != 'all') {
            $from_date = date("Y-m-d", strtotime($month));
            $to_date = date("Y-m-t", strtotime($month));
        }
        $entry_type = $this->entry->getEntryTypeById($id);
        $parent_id=$entry_type['parent_id'];
        $data['entry_type_id'] = $id;
        $data['parent_id'] = $parent_id;
        $data['month'] = $month;
        $data['voucher_name'] = str_replace(' ', '-', strtolower(trim($entry_type['type'])));
        $data['all_entries'] = $this->entry->getAllEntry($id,$parent_id, $from_date, $to_date);
        $data['dataCount'] = $this->entry->getCountEntries($id,$parent_id);
        // echo "<pre>";print_r($data['all_entries']);exit();
        $data['all_post_dated_entries'] = $this->entry->getAllPostDatedEntry($id,$parent_id, $from_date, $to_date);
        $data['all_recurring_entries'] = $this->entry->getAllRecurringEntry($id,$parent_id, $from_date, $to_date);

        $data['recurringCount'] = $this->entry->getAllRecurringEntryCount($id,$parent_id);
        $data['postdatedCount'] = $this->entry->getAllPostDatedEntryCount($id,$parent_id);

        $data['sub_vouchers'] = $this->entry->getAllSubVouchersById($id);

        $data['voucher_type'] = $entry_type;



        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | All Transactions');
        $this->layouts->render('admin/entry_list', $data, 'admin');
    }

    public function receiptAjaxListing(){
        $draw       = $this->input->get_post('draw');
        $start      = $this->input->get_post('start');
        $length     = $this->input->get_post('length');
        $search     = $this->input->get_post('search')['value'];

        // $accounts_configuration = $this->inventorymodel->getPreferences();

        $name = $_GET['name'];
        $id = $_GET['eid'];
        $month = $_GET['emonth'];
        $entry_type = $this->entry->getEntryTypeById($id);
        $parent_id=$entry_type['parent_id'];
        $financial_year = get_financial_year();
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-t", strtotime(end($financial_year)));

        $result     = $this->entry->getAllEntry($id,$parent_id, $from_date, $to_date, $length, $start,$search);
        foreach($result AS $entry){
            $name = "";
            $del = '';
            $subArr = array();
            
            $subArr[] = get_date_format($entry['create_date']);
            $subArr[] = $entry['entry_no'];

            $l = "";
            // if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $l = "<a href=" . base_url('admin/trasaction-details') . '.aspx/' . $entry['id'] . ">";
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

            if ($entry['entry_type_id'] == 1 || $parent_id == 1) {
                $module = 84;
            } elseif ($entry['entry_type_id'] == 2 || $parent_id == 2) {
                $module = 85;
            } elseif ($entry['entry_type_id'] == 3 || $parent_id == 3) {
                $module = 86;
            } elseif ($entry['entry_type_id'] == 4 || $parent_id == 4) {
                $module = 87;
            } elseif ($entry['entry_type_id'] == 5 || $parent_id == 5) {
                $module = 183;
            } elseif ($entry['entry_type_id'] == 6 || $parent_id == 6) {
                $module = 184;
            }

            $del = '<div class="dropdown circle">
                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-ellipsis-v"></i></a>
                <ul class="dropdown-menu tablemenu">
                    <li>';
                        $permission = ua($module, 'edit');
                        if ($permission):
                            $del .= '<a href="' . site_url('admin/transaction-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>';
                        endif;
                        $permission = ua($module, 'add');
                        if ($permission):
                            $del .= '<a href="' . site_url('admin/transaction-copy') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/c" data-toggle="tooltip" title="Copy Transaction" > <i class="fa fa-clone" aria-hidden="true"></i></a>';
                        endif;
                    $del .= '</li>
                    <li>';

                        $permission = ua($module, 'delete');
                        if ($permission):
                            $del .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="' . $entry['id'] . '" data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>';
                        endif;
                    $del .= '</li>
                </ul>
            </div>';


            $subArr[] = $del;            
            
            $data['data'][] = $subArr;
        }
        $count = $this->entry->getCountEntries($id,$parent_id,$search);
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

        // $accounts_configuration = $this->inventorymodel->getPreferences();

        $name = $_GET['name'];
        $id = $_GET['eid'];
        $month = $_GET['emonth'];
        $entry_type = $this->entry->getEntryTypeById($id);
        $parent_id=$entry_type['parent_id'];
        $financial_year = get_financial_year();
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-t", strtotime(end($financial_year)));

        $result     = $this->entry->getAllRecurringEntry($id,$parent_id, $from_date, $to_date, $length, $start,$search);
        foreach($result AS $entry){
            $name = "";
            $del = '';
            $subArr = array();
            
            $subArr[] = get_date_format($entry['create_date']);
            $subArr[] = $entry['entry_no'];

            $l = "";
            // if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $l = "<a href=" . base_url('admin/trasaction-details') . '.aspx/' . $entry['id'] . ">";
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
            $subArr[] = $entry['frequency'];

            if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $subArr[] = $this->price_format($entry['cr_amount']);
            }else {
                $subArr[] = "";
            }

            if ($entry['entry_type_id'] == 1 || $parent_id == 1) {
                $module = 84;
            } elseif ($entry['entry_type_id'] == 2 || $parent_id == 2) {
                $module = 85;
            } elseif ($entry['entry_type_id'] == 3 || $parent_id == 3) {
                $module = 86;
            } elseif ($entry['entry_type_id'] == 4 || $parent_id == 4) {
                $module = 87;
            } elseif ($entry['entry_type_id'] == 5 || $parent_id == 5) {
                $module = 183;
            } elseif ($entry['entry_type_id'] == 6 || $parent_id == 6) {
                $module = 184;
            }

            $del = '<div class="dropdown circle">
                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-ellipsis-v"></i></a>
                <ul class="dropdown-menu tablemenu">
                    
                    <li>';

                        $permission = ua($module, 'delete');
                        if ($permission):
                            $del .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="' . $entry['id'] . '" data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>';
                        endif;
                    $del .= '</li>
                </ul>
            </div>';


            $subArr[] = $del;            
            
            $data['data'][] = $subArr;
        }
        $count = $this->entry->getAllRecurringEntryCount($id,$parent_id,$search);
        $data['draw']              = $draw;
        $data['recordsTotal']      = $count;
        $data['recordsFiltered']   = $count;
        echo json_encode($data);exit;
    }

    public function postdatedAjaxListing(){
        $draw       = $this->input->get_post('draw');
        $start      = $this->input->get_post('start');
        $length     = $this->input->get_post('length');
        $search     = $this->input->get_post('search')['value'];

        // $accounts_configuration = $this->inventorymodel->getPreferences();

        $name = $_GET['name'];
        $id = $_GET['eid'];
        $month = $_GET['emonth'];
        $entry_type = $this->entry->getEntryTypeById($id);
        $parent_id=$entry_type['parent_id'];
        $financial_year = get_financial_year();
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-t", strtotime(end($financial_year)));

        $result     = $this->entry->getAllPostDatedEntry($id,$parent_id, $from_date, $to_date, $length, $start,$search);
        foreach($result AS $entry){
            $name = "";
            $del = '';
            $subArr = array();
            
            $subArr[] = get_date_format($entry['create_date']);
            $subArr[] = $entry['entry_no'];

            $l = "";
            // if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){
                $l = "<a href=" . base_url('admin/trasaction-details') . '.aspx/' . $entry['id'] . ">";
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

            if ($entry['entry_type_id'] == 1 || $parent_id == 1) {
                $module = 84;
            } elseif ($entry['entry_type_id'] == 2 || $parent_id == 2) {
                $module = 85;
            } elseif ($entry['entry_type_id'] == 3 || $parent_id == 3) {
                $module = 86;
            } elseif ($entry['entry_type_id'] == 4 || $parent_id == 4) {
                $module = 87;
            } elseif ($entry['entry_type_id'] == 5 || $parent_id == 5) {
                $module = 183;
            } elseif ($entry['entry_type_id'] == 6 || $parent_id == 6) {
                $module = 184;
            }

            $del = '<div class="dropdown circle">
                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-ellipsis-v"></i></a>
                <ul class="dropdown-menu tablemenu">
                    <li>';
                        $permission = ua($module, 'edit');
                        if ($permission):
                            $del .= '<a href="' . site_url('admin/transaction-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/e" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>';
                        endif;
                        $permission = ua($module, 'add');
                        if ($permission):
                            $del .= '<a href="' . site_url('admin/transaction-copy') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . '/c" data-toggle="tooltip" title="Copy Transaction" > <i class="fa fa-clone" aria-hidden="true"></i></a>';
                        endif;
                    $del .= '</li>
                    <li>';

                        $permission = ua($module, 'delete');
                        if ($permission):
                            $del .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="' . $entry['id'] . '" data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>';
                        endif;
                    $del .= '</li>
                </ul>
            </div>';


            $subArr[] = $del;            
            
            $data['data'][] = $subArr;
        }
        $count = $this->entry->getAllPostDatedEntryCount($id,$parent_id,$search);
        $data['draw']              = $draw;
        $data['recordsTotal']      = $count;
        $data['recordsFiltered']   = $count;
        echo json_encode($data);exit;
    }

    public function post_dated_entry() {
        $financial_year = get_financial_year();
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-t", strtotime(end($financial_year)));
        $data['all_entries'] = $this->entry->getAllPostDatedEntry($from_date, $to_date);
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Post Dated Entry');
        $this->layouts->render('admin/post_dated_entry_list', $data, 'admin');
    }

    public function recurring_entry() {
        $financial_year = get_financial_year();
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-t", strtotime(end($financial_year)));
        $data['all_entries'] = $this->entry->getAllRecurringEntry($from_date, $to_date);
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Post Dated Entry');
        $this->layouts->render('admin/recurring_entry_list', $data, 'admin');
    }

    public function sub_voucher_entry($id = NULL, $entry_type_id = NULL) {
        $data['sub_voucher_id'] = $id;
        $data['entry_type_id'] = $entry_type_id;
        $where = "";
        $where = array(
            'entry.sub_voucher' => $id,
            'entry.status' => '1',
            'entry.deleted' => '0'
        );
        $data['all_entries'] = $this->entry->getAllEntrySubVoucher($where);
        $data['sub_voucher'] = $this->entry->getSubVoucher($id);

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | All Transactions');
        $this->layouts->render('admin/entry_list_sub_voucher', $data, 'admin');
    }

    public function sub_voucher_new_entry($id = NULL, $entry_type_id = NULL) {

        $entry_type = $this->entry->getSubVoucherById($id);
        $data['voucher_no_status'] = $entry_type['entry_no_status'];
        if ($entry_type['entry_no_status'] == '1') {
            $today = date('Y-m-d');
            if ($today >= $entry_type['strating_date']) {
                $countid = 1;
                $auto_number = $this->entry->getNoOfByTypeId($id, $today, $entry_type['strating_date']);
                $start_length = $entry_type['starting_entry_no'];
                $countid = $countid + $auto_number['total_transaction'];
                $id_length = strlen($countid);
                if ($start_length > $id_length) {
                    $remaining = $start_length - $id_length;
                    $uniqueid = $entry_type['prefix_entry_no'] . str_repeat("0", $remaining) . $countid . $entry_type['suffix_entry_no'];
                } else {
                    $uniqueid = $entry_type['prefix_entry_no'] . $countid . $entry_type['suffix_entry_no'];
                }

                $data['auto_number'] = $uniqueid;
            }
        }

        $data['sub_voucher_id'] = $id;
        $data['entry_type_id'] = $entry_type_id;
        $where = "";
        $where = array(
            'status' => 1
        );
        $ledger_name = $this->entry->getAllLedgerName($where);
        $ledger = array();
        $ledger[''] = 'Select..';
        foreach ($ledger_name as $val) {
            $ledger[$val['id']] = $val['ladger_name'];
        }

        $all_type = $this->entry->allEntryType($where);
        $types = array();
        $types[''] = 'Select Entry Type';
        foreach ($all_type as $type) {
            $types[$type['id']] = $type['type'];
        }
        $data['types'] = $types;
        $data['ledger'] = $ledger;
        // For curremcy
        $data['currencies'] = $this->entry->getAllCurrency();
        $data['defoultCurrency'] = $this->entry->getDefoultCurrency();

        $this->form_validation->set_rules('entry_no', 'Entry no', 'required');
        $this->form_validation->set_rules('create_date', 'Date', 'required');
        $this->form_validation->set_rules('entry_type_id', 'Entry type', 'required');
        $this->form_validation->set_rules('ledger_id', 'Ledger name', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required');

        if ($this->form_validation->run() == FALSE) {
            $getsitename = getsitename();
            $this->layouts->set_title($getsitename . ' | Add Entry');
            $this->layouts->render('admin/sub_voucher_new_entry', $data, 'admin');
        } else {
            $this->add_entry();
        }
    }

    public function new_entry($id = NULL) {
        if (!empty($id)) {
            $data['entry_type_id'] = $id;
        } else {
            $data['entry_type_id'] = $this->input->post('entry_type_id');
        }

        $entry_type = $this->entry->getEntryTypeById($data['entry_type_id']);
        $data['auto_no_status'] = $entry_type['transaction_no_status'];
        $alias = $entry_type['alias'];
        $permission = admin_users_permission('A', $alias, $rtype = TRUE);
        if ($permission) {

            $data['voucher_no_status'] = $entry_type['transaction_no_status'];

            if ($entry_type['transaction_no_status'] == '1') {
                $today = date('Y-m-d');
                //   if ($today >= $entry_type['strating_date']) {
                $countid = 1;
                $auto_number = $this->entry->getNoOfByTypeId($data['entry_type_id'], $today, $entry_type['strating_date']);
                $start_length = $entry_type['starting_entry_no'];
                $countid = $countid + $auto_number['total_transaction'];
                $id_length = strlen($countid);
                if ($start_length > $id_length) {
                    $remaining = $start_length - $id_length;
                    $uniqueid = $entry_type['prefix_entry_no'] . str_repeat("0", $remaining) . $countid . $entry_type['suffix_entry_no'];
                } else {
                    $uniqueid = $entry_type['prefix_entry_no'] . $countid . $entry_type['suffix_entry_no'];
                }

                $data['auto_number'] = $uniqueid;
                //  }
            }


            $data['voucher_name'] = $entry_type['type'];

            $where = "";
            $where = array(
                'status' => 1
            );
            $ledger_name = $this->entry->getAllLedgerName($where);
            $ledger = array();
            $ledger[''] = 'Select..';
            foreach ($ledger_name as $val) {
                $ledger[$val['id']] = $val['ladger_name'];
            }

            $all_type = $this->entry->allEntryType($where);
            $types = array();
            $types[''] = 'Select Entry Type';
            foreach ($all_type as $type) {
                $types[$type['id']] = $type['type'];
            }
            $data['transaction_types'] = $this->entry->allTransactionType();
            $data['types'] = $types;
            $data['ledger'] = $ledger;
            $data['currencies'] = $this->entry->getAllCurrency();
            $data['defoultCurrency'] = $this->entry->getDefoultCurrency();

            //$this->form_validation->set_rules('entry_no', 'Entry no', 'required');
            $this->form_validation->set_rules('create_date', 'Date', 'required');
            $this->form_validation->set_rules('entry_type_id', 'Entry type', 'required');
            $this->form_validation->set_rules('ledger_id', 'Ledger name', 'required');
            $this->form_validation->set_rules('amount', 'Amount', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->entry->tableTruncate();
                $this->entry->billTableTruncate();
                $getsitename = getsitename();
                $this->layouts->set_title($getsitename . ' | Add Entry');
                $this->layouts->render('admin/new_entry', $data, 'admin');
            } else {
                $this->add_entry();
            }
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    //new entry backup
    public function new_entry_bk_23_1_2017($id = NULL) {
        if (!empty($id)) {
            $data['entry_type_id'] = $id;
        } else {
            $data['entry_type_id'] = $this->input->post('entry_type_id');
        }

        $entry_type = $this->entry->getEntryTypeById($data['entry_type_id']);
        $alias = $entry_type['alias'];

        $permission = admin_users_permission('A', $alias, $rtype = TRUE);
        if ($permission) {

            $data['voucher_no_status'] = $entry_type['transaction_no_status'];
            if ($entry_type['transaction_no_status'] == '1') {
                $today = date('Y-m-d');
                if ($today >= $entry_type['strating_date']) {
                    $countid = 1;
                    $auto_number = $this->entry->getNoOfByTypeId($data['entry_type_id'], $today, $entry_type['strating_date']);
                    $start_length = $entry_type['starting_entry_no'];
                    $countid = $countid + $auto_number['total_transaction'];
                    $id_length = strlen($countid);
                    if ($start_length > $id_length) {
                        $remaining = $start_length - $id_length;
                        $uniqueid = $entry_type['prefix_entry_no'] . str_repeat("0", $remaining) . $countid . $entry_type['suffix_entry_no'];
                    } else {
                        $uniqueid = $entry_type['prefix_entry_no'] . $countid . $entry_type['suffix_entry_no'];
                    }

                    $data['auto_number'] = $uniqueid;
                }
            }


            $data['voucher_name'] = $entry_type['type'];

            $where = "";
            $where = array(
                'status' => 1
            );
            $ledger_name = $this->entry->getAllLedgerName($where);
            $ledger = array();
            $ledger[''] = 'Select..';
            foreach ($ledger_name as $val) {
                $ledger[$val['id']] = $val['ladger_name'];
            }

            $all_type = $this->entry->allEntryType($where);
            $types = array();
            $types[''] = 'Select Entry Type';
            foreach ($all_type as $type) {
                $types[$type['id']] = $type['type'];
            }
            $data['types'] = $types;
            $data['ledger'] = $ledger;
            $data['currencies'] = $this->entry->getAllCurrency();
            $data['defoultCurrency'] = $this->entry->getDefoultCurrency();

            $this->form_validation->set_rules('entry_no', 'Entry no', 'required');
            $this->form_validation->set_rules('create_date', 'Date', 'required');
            $this->form_validation->set_rules('entry_type_id', 'Entry type', 'required');
            $this->form_validation->set_rules('ledger_id', 'Ledger name', 'required');
            $this->form_validation->set_rules('amount', 'Amount', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->entry->tableTruncate();
                $this->entry->billTableTruncate();
                $getsitename = getsitename();
                $this->layouts->set_title($getsitename . ' | Add Entry');
                $this->layouts->render('admin/new_entry', $data, 'admin');
            } else {
                $this->add_entry();
            }
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    public function check_ledger_group($ledger_id) {
        $sundry_debtors = 15;
        $sundry_creditors = 23;
        $group_id_arr = [$sundry_debtors, $sundry_creditors];
        //get child group againest parent
        $sub_id_arr = [];
        $sub_group = $this->entry->getSubGroup($group_id_arr);
        foreach ($sub_group as $sub) {
            $group_id_arr[] = $sub->id;
            $sub_id_arr[] = $sub->id;
            $sub_sub_group = $this->entry->getSubGroup($sub_id_arr);
            foreach ($sub_sub_group as $sub_sub) {
                $group_id_arr[] = $sub_sub->id;
            }
        }
        //all ledger within cash and bank group
        $ledger_id_arr = [];
        $all_ledger = $this->entry->getAllLedgerId($group_id_arr);
        foreach ($all_ledger as $ledger) {
            $ledger_id_arr[] = $ledger->id;
        }
        if (in_array($ledger_id, $ledger_id_arr)) {
            return true;
        } else {
            return false;
        }
    }

    public function add_entry() {

        $data['account'] = $this->input->post('account');
        $data['amounts'] = $this->input->post('amount');
        $data['ledger_id'] = $this->input->post('ledger_id');
        $data['entry_no'] = $this->input->post('entry_no');
        $data['create_date'] = $this->input->post('create_date');
        $data['narration'] = $this->input->post('narration');
        $entry_type_id = $this->input->post('entry_type_id');
        $data['narration'] = $this->input->post('narration');
        //send mail for receipt and payment
        if ($entry_type_id == 1 || $entry_type_id == 2) {
            foreach ($this->input->post('ledger_id') as $row) {
                if ($row) {
                    $res = $this->check_ledger_group($row);
                    if ($res) {
                        $ledger_contact_details = $this->entry->getLedgerContactDetails($row);
                        if ($ledger_contact_details) {
                            //mail
                            $this->load->helper('email');
                            $mail_data = array($ledger_contact_details->company_name, '#555555555555');
                            sendMail($template = '', $slug = 'payment', $ledger_contact_details->email, $mail_data);
                            //end mail   
                        }
                    }
                }
            }
        }
        //end send mail for receipt and payment
        //For Different Curreency
        $currency = $this->input->post('currency');
        $currency_unit = $this->entry->getCurrencyUnitById($currency);
        $data['selected_currency'] = $currency_unit['id'];
        $data['base_unit'] = $currency_unit['unit_price'];

        $this->entry->saveEntry($data);
        $this->session->set_flashdata('successmessage', 'Transaction Add successfully');

        if ($entry_type_id == 1) {
            $redirect = site_url('admin/receipts');
            redirect($redirect);
        }
        if ($entry_type_id == 2) {
            $redirect = site_url('admin/payments');
            redirect($redirect);
        }
        if ($entry_type_id == 3) {
            $redirect = site_url('admin/contres');
            redirect($redirect);
        }
        if ($entry_type_id == 4) {
            $redirect = site_url('admin/jurnals');
            redirect($redirect);
        }
        if ($entry_type_id == 5) {
            $redirect = site_url('admin/sales');
            redirect($redirect);
        }
        if ($entry_type_id == 6) {
            $redirect = site_url('admin/purchases');
            redirect($redirect);
        }
    }

    public function ajax_ledger_name() {
        $where = "";
        $where = array(
            'status' => 1,
            'deleted' => 0
        );
        $ledger_name = $this->entry->getAllLedgerName($where);
        $ledgers = array();
        foreach ($ledger_name as $val) {
            $ledgers[$val['id']] = $val['ladger_name'];
        }
        echo json_encode($ledger_name);
        exit;
    }

    public function get_temp_bill_by_id() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $ledger = isset($_POST['ledger']) ? $_POST['ledger'] : null;
            $entry_id = (isset($_POST['entry_id']) && $_POST['entry_id']) ? $_POST['entry_id'] : null;
            if ($ledger && $entry_id) {
                $all_temp_bill = $this->entry->getAllBill($entry_id, $ledger);
            } else {
                $all_temp_bill = $this->entry->getAllTempBill($ledger);
            }
            $ledger_details = $this->entry->getLedgerDetailsById($ledger);
            $data_msg['ledger_name'] = $ledger_details['ladger_name'];
            if ($all_temp_bill) {
                $data_msg['bill_arr'] = $all_temp_bill;
                $data_msg['res'] = 'success';
            } else {
                $data_msg['res'] = 'error';
            }
            echo json_encode($data_msg);
        }
    }

    //get temp track by id

    public function get_temp_tracking_by_id() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $ledger = isset($_POST['ledger']) ? $_POST['ledger'] : null;
            $entry_id = (isset($_POST['entry_id']) && $_POST['entry_id']) ? $_POST['entry_id'] : null;
            if ($entry_id && $ledger) {
                $all_temp_tracking = $this->entry->getAllTracking($entry_id, $ledger);
            } else {
                $all_temp_tracking = $this->entry->getAllTempTracking($ledger);
            }
            $ledger_details = $this->entry->getLedgerDetailsById($ledger);
            $data_msg['ledger_name'] = $ledger_details['ladger_name'];
            if ($all_temp_tracking) {
                $data_msg['tracking_arr'] = $all_temp_tracking;

                $data_msg['res'] = 'success';
            } else {
                $data_msg['res'] = 'error';
            }
            echo json_encode($data_msg);
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

    public function current_balance() {
        $data = [];
        $ledger_id = $_POST['ledger_id'];
        //get current balence balance
        $transaction_details = $this->sumBalance($ledger_id);
        $opening_balance_arr = $this->openingBalance($ledger_id);
        $cr_sum = 0;
        $dr_sum = 0;
        if ($transaction_details) {
            $cr_sum = $transaction_details[0]->cr_balance;
            $dr_sum = $transaction_details[0]->dr_balance;
        }
        if ($opening_balance_arr->account_type == 'Dr') {
            $current_balance = (($opening_balance_arr->opening_balance + $dr_sum) - $cr_sum);
        }
        if ($opening_balance_arr->account_type == 'Cr') {
            $current_balance = (($opening_balance_arr->opening_balance + $cr_sum) - $dr_sum);
        }
        $data['amount'] = $current_balance;
        $data['status'] = 'success';
        echo json_encode($data);
        exit;
    }

    // backup
    public function current_balance_bk_23_1_2017() {
        $ledger_id = $_POST['ledger_id'];
        $where = "";
        $where = array(
            'id' => $ledger_id,
            'deleted' => 0,
            'status' => 1
        );
        $last_balance = $this->entry->last_balance($where);
        echo json_encode($last_balance);
        exit;
    }

    public function edit_entry($id = NULL, $entry_type_id = NULL) {

        $entry_type = $this->entry->getEntryTypeById($entry_type_id);
        $alias = $entry_type['alias'];
        $permission = admin_users_permission('E', $alias, $rtype = TRUE);
        if ($permission) {
            $data['entry_type_id'] = $entry_type_id;
            $data['entry_id'] = $id;
            $where = "";
            $where = array(
                'status' => 1,
            );
            $ledger_name = $this->entry->getAllLedgerName($where);
            $ledger = array();
            $ledger[''] = 'Select..';
            foreach ($ledger_name as $val) {
                $ledger[$val['id']] = $val['ladger_name'];
            }

            $all_type = $this->entry->allEntryType($where);
            $types = array();
            $types[''] = 'Select Entry Type';
            foreach ($all_type as $type) {
                $types[$type['id']] = $type['type'];
            }
            unset($where);
            $data['types'] = $types;
            $data['ledger'] = $ledger;

            $where = array(
                'id' => $id,
                'status' => 1,
                'deleted' => 0
            );
            $data['entry'] = $this->entry->getEntryById($where);

            unset($where);
            $data['transaction_types'] = $this->entry->allTransactionType();
            $where = array(
                'ladger_account_detail.entry_id' => $id,
                'ladger_account_detail.status' => 1,
                'ladger_account_detail.deleted' => 0
            );

            $data['entry_details'] = $this->entry->getAllEntryDetailById($where);
            $this->entry->copyBankDetailstoTemp($data['entry'][0]['entry_no'], $id);
            $data['currencies'] = $this->entry->getAllCurrency();
            $data['id'] = $id;
            $getsitename = getsitename();
            $this->layouts->set_title($getsitename . ' | Edit Entry');
            $this->layouts->render('admin/edit_entry', $data, 'admin');
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    public function update_entry() {
        $data['entry_id'] = $this->input->post('entry_id');
        $entry_type_id = $this->input->post('entry_type_id');
        $where = array(
            'ladger_account_detail.entry_id' => $data['entry_id'],
            'ladger_account_detail.status' => 1
        );

        $entry_details = $this->entry->getAllEntryDetailById($where);
        $hidden_account = $this->input->post('hidden_account');
        $hidden_ledger_id = $this->input->post('hidden_ledger_id');
        $hidden_amount = $this->input->post('hidden_amount');
        $count = count($this->input->post('hidden_ledger_id'));
        $last_balance = array();
        for ($i = 0; $i < count($this->input->post('hidden_ledger_id')); $i++) {//calculate current balance
            $led_id = $hidden_ledger_id[$i];
            if ($hidden_account[$i] == 'Dr') {
                $new_dr_amount = 0;
                $where = array(
                    'id' => $led_id
                );
                $last_balance = $this->entry->last_balance($where);
                $new_dr_amount = $last_balance[0]['current_balance'] - $hidden_amount[$i];

                $data = array(
                    'current_balance' => $new_dr_amount
                );

                $this->db->where('id', $led_id); //update current balance
                $this->db->update('ladger', $data);
                unset($data);
            }
            if ($hidden_account[$i] == 'Cr') {
                $new_cr_amount = 0;
                $where = array(
                    'id' => $led_id
                );
                $last_balance = $this->entry->last_balance($where);
                $new_cr_amount = $last_balance[0]['current_balance'] + $hidden_amount[$i];

                $data = array(
                    'current_balance' => $new_cr_amount
                );

                $this->db->where('id', $led_id); //update current balance
                $this->db->update('ladger', $data);
                unset($data);
            }
        }

        $data['account'] = $this->input->post('account');
        $data['amounts'] = $this->input->post('amount');
        $data['ledger_id'] = $this->input->post('ledger_id');
        $data['entry_no'] = $this->input->post('entry_no');
        $data['create_date'] = $this->input->post('create_date');
        $data['narration'] = $this->input->post('narration');

        //For Different Curreency
        $currency = $this->input->post('currency');
        $currency_unit = $this->entry->getCurrencyUnitById($currency);
        $data['selected_currency'] = $currency_unit['id'];
        $data['base_unit'] = $currency_unit['unit_price'];
        $this->entry->updateEntry($data);

        $this->session->set_flashdata('successmessage', 'Transaction Update successfully');
        if ($entry_type_id == 1) {
            $redirect = site_url('admin/receipts');
            redirect($redirect);
        }
        if ($entry_type_id == 2) {
            $redirect = site_url('admin/payments');
            redirect($redirect);
        }
        if ($entry_type_id == 3) {
            $redirect = site_url('admin/contres');
            redirect($redirect);
        }
        if ($entry_type_id == 4) {
            $redirect = site_url('admin/jurnals');
            redirect($redirect);
        }
        if ($entry_type_id == 5) {
            $redirect = site_url('admin/sales');
            redirect($redirect);
        }
        if ($entry_type_id == 6) {
            $redirect = site_url('admin/purchases');
            redirect($redirect);
        }
    }

    public function delete($id = NULL, $entry_type_id = NULL) {
        $entry_type = $this->entry->getEntryTypeById($entry_type_id);
        $alias = $entry_type['alias'];
        $permission = admin_users_permission('D', $alias, $rtype = TRUE);
        if ($permission) {
            $result = $this->entry->modifyLedgerBalance($id);
            $this->session->set_flashdata('successmessage', 'Transaction Deleted successfully');
            if ($entry_type_id == 1) {
                $redirect = site_url('admin/receipts');
                redirect($redirect);
            }
            if ($entry_type_id == 2) {
                $redirect = site_url('admin/payments');
                redirect($redirect);
            }
            if ($entry_type_id == 3) {
                $redirect = site_url('admin/contres');
                redirect($redirect);
            }
            if ($entry_type_id == 4) {
                $redirect = site_url('admin/jurnals');
                redirect($redirect);
            }
            if ($entry_type_id == 5) {
                $redirect = site_url('admin/sales');
                redirect($redirect);
            }
            if ($entry_type_id == 6) {
                $redirect = site_url('admin/purchases');
                redirect($redirect);
            }
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    //ajax delete entry
    public function ajax_delete_entry() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            if (isset($_POST['delete_entry_id'])) {
                $entry_id = $_POST['delete_entry_id'];
                $entry = $this->entry->getEntryByIdOrder($entry_id);
                if ($entry && $entry->order_id) {
                    $res1 = $this->entry->modifyLedgerBalance($entry_id);
                    $res2 = $this->entry->deleteOrder($entry->order_id);
                    if ($res1 && $res2) {
                        $data_msg['message'] = 'Entry Deleted Successfully.';
                        $data_msg['res'] = 'success';
                        $data_msg['entry_id'] = $entry_id;
                    }
                }
            }
            echo json_encode($data_msg);
        }
    }

    //ajax delete entry
    public function ajax_delete_request_entry() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            if (isset($_POST['delete_entry_id'])) {
                $entry_id = $_POST['delete_entry_id'];
                $entry = $this->entry->getRequestEntryByIdOrder($entry_id);
                if ($entry && $entry->order_id) {
                    $res1 = $this->entry->deleteRequestEntry($entry_id);
                    $res2 = $this->entry->deleteRequestOrder($entry->order_id);
                    if ($res1 && $res2) {
                        $data_msg['message'] = 'Entry Deleted Successfully.';
                        $data_msg['res'] = 'success';
                        $data_msg['entry_id'] = $entry_id;
                    }
                }
            }
            echo json_encode($data_msg);
        }
    }

    //ajax delete entry
    public function ajax_delete_temp_entry() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            if (isset($_POST['delete_entry_id'])) {
                $entry_id = $_POST['delete_entry_id'];
                $entry = $this->entry->getTempEntryByIdOrder($entry_id);
                if ($entry && $entry->order_id) {
                    $res1 = $this->entry->deleteTempEntry($entry_id);
                    $res2 = $this->entry->deleteTempOrder($entry->order_id);
                    if ($res1 && $res2) {
                        $data_msg['message'] = 'Entry Deleted Successfully.';
                        $data_msg['res'] = 'success';
                        $data_msg['entry_id'] = $entry_id;
                    }
                }
            }
            echo json_encode($data_msg);
        }
    }

    public function get_ledger_id() {
        if ($this->input->post('ajax', TRUE)) {
            $ledger = $_POST['ledger'];
            $returnId = $this->entry->getLedgerId($ledger);
            if (empty($returnId)) {
                echo json_encode(array('SUCESS' => 0, 'MSG' => ''));
            } else {
                echo json_encode(array('SUCESS' => 1, 'MSG' => '', 'MENU' => $returnId));
            }
        } else {
            echo json_encode(array('SUCESS' => 0, 'MSG' => 'This page only access by ajax'));
        }
    }

    //get ledger details by id
    public function get_ledger_by_id() {
        if ($this->input->post('ajax', TRUE)) {
            $ledger = $_POST['ledger'];
            $returnId = $this->entry->getLedgerDetailsById($ledger);
            if (empty($returnId)) {
                echo json_encode(array('SUCESS' => 0, 'MSG' => ''));
            } else {
                echo json_encode(array('SUCESS' => 1, 'MSG' => '', 'MENU' => $returnId));
            }
        } else {
            echo json_encode(array('SUCESS' => 0, 'MSG' => 'This page only access by ajax'));
        }
    }

    public function getLedgerDetails() {
        $ledger = $_POST['ledger'];
        $where = array(
            'status' => 1
        );
        $ledger_name = $this->entry->getAllLedgerNameForJson($where, $ledger);
        foreach ($ledger_name as $value) {
            $ledge[] = $value['ladger_name'];
        }
        echo json_encode($ledge);
    }

    public function getSaleLedgerDetails() {
        $ledger = $_POST['ledger'];
        $where = array(
            'ladger.status' => 1,
            'ladger.deleted' => 0
        );
        $sale_group_id = 37;
        $tax_group_id = 21;
        $in_exp_group_id = 31;
        $ledger_name = $this->entry->getSalesLedgerNameForJson($where, $ledger, $sale_group_id, $tax_group_id, $in_exp_group_id);
        foreach ($ledger_name as $value) {
            $ledge[] = $value['ladger_name'];
        }
        echo json_encode($ledge);
    }

    public function getPurchaseLedgerDetails() {
        $ledger = $_POST['ledger'];
        $where = array(
            'ladger.status' => 1,
            'ladger.deleted' => 0
        );
        $purchase_group_id = 32;
        $tax_group_id = 21;
        $in_exp_group_id = 31;
        $ledger_name = $this->entry->getPurchaseLedgerNameForJson($where, $ledger, $purchase_group_id, $tax_group_id, $in_exp_group_id);
        foreach ($ledger_name as $value) {
            $ledge[] = $value['ladger_name'];
        }
        echo json_encode($ledge);
    }

    public function getContraLedgerDetails() {
        $ledger = $_POST['ledger'];
        $cash_id = 11;
        $bank_id = 10;
        $group_id_arr = [$cash_id, $bank_id];
        //get child group againest parent
        $sub_id_arr = [];
        $sub_group = $this->entry->getSubGroup($group_id_arr);
        foreach ($sub_group as $sub) {
            $group_id_arr[] = $sub->id;
            $sub_id_arr[] = $sub->id;
            $sub_sub_group = $this->entry->getSubGroup($sub_id_arr);
            foreach ($sub_sub_group as $sub_sub) {
                $group_id_arr[] = $sub_sub->id;
            }
        }
        $ledger_name = $this->entry->getContraLedgerNameForJson($ledger, $group_id_arr);
        foreach ($ledger_name as $value) {
            $ledge[] = $value['ladger_name'];
        }
        echo json_encode($ledge);
    }

    public function getEntryType() {
        $entry_type = $_POST['entry_type'];
        $where = array(
            'status' => 1,
            'deleted' => 0
        );
        $entrytype = $this->entry->getAllEntryTypeNameForJson($where, $entry_type);
        foreach ($entrytype as $value) {
            $typeName[] = $value['type'];
        }
        echo json_encode($typeName);
    }

    public function get_entry_type_id() {
        if ($this->input->post('ajax', TRUE)) {
            $entry_type = $_POST['entry_type'];
            $returnId = $this->entry->getEntryTypeId($entry_type);
            if (empty($returnId)) {
                echo json_encode(array('SUCESS' => 0, 'MSG' => ''));
            } else {
                echo json_encode(array('SUCESS' => 1, 'MSG' => '', 'MENU' => $returnId));
            }
        } else {
            echo json_encode(array('SUCESS' => 0, 'MSG' => 'This page only access by ajax'));
        }
    }

    public function sub_voucher_add($id = NULL) {
        if (!empty($id))
            $param = 'E';
        else
            $param = 'A';

        $permission = admin_users_permission($param, 'vouchers', $rtype = TRUE);
        if ($permission) {
            $data['entry_types'] = $this->entry->getAllEntryType();
            $data['sub_voucher_code_status'] = $this->entry->getSubVoucherCodeStatus();
            $getsitename = getsitename();
            $this->layouts->set_title($getsitename . ' | Add Sub Voucher');
            $this->layouts->render('admin/add_sub_voucher', $data, 'admin');
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    public function edit_parent_vobcher($id = NULL) {
        $permission = admin_users_permission('E', 'vouchers', $rtype = TRUE);
        if ($permission) {

            if (isset($_POST) && !empty($_POST)) {

                $data['type'] = $this->input->post('type');
                $data['entry_code'] = $this->input->post('entry_code');
                $data['reset_interval'] = $this->input->post('interval');
                $data['title'] = $this->input->post('title');
                $data['sub_title'] = $this->input->post('sub_title');
                $data['declaration'] = $this->input->post('declaration');
                $data['transaction_no_status'] = $this->input->post('transaction_no_status');
                if ($this->input->post('transaction_no_status') == '1') {
                    $data['suffix_entry_no'] = $this->input->post('suffix_entry_no');
                    $data['prefix_entry_no'] = $this->input->post('prefix_entry_no');
                    $data['starting_entry_no'] = $this->input->post('strating_no');
                    $strating_date = explode('/', $this->input->post('strating_date'));
                    $data['strating_date'] = $strating_date[2] . '-' . $strating_date[0] . '-' . $strating_date[1];
                }
                $id = $this->input->post('id');
                $result = $this->entry->updateVoucher($data, $id);
                if (!empty($result)) {
                    $this->session->set_flashdata('successmessage', 'Voucher Upadate successfully');
                } else {
                    $this->session->set_flashdata('errormessage', 'Oops! Something went wrong');
                }
                $redirect = site_url('admin/vouchers');
                redirect($redirect);
            }
            $data['entry_types'] = $this->entry->getEntryTypeById($id);
            $data['sub_voucher_code_status'] = $this->entry->getSubVoucherCodeStatus();
            $getsitename = getsitename();
            $this->layouts->set_title($getsitename . ' | Edit Sub Voucher');
            $this->layouts->render('admin/edit_parent_voucher', $data, 'admin');
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    public function edit_sub_vobcher($sub_voucher_id = NULL, $id = NULL) {
        $data['parent_type_id'] = $id;
        $data['sub_voucher'] = $this->entry->getSubVoucherId($sub_voucher_id);
        $data['entry_types'] = $this->entry->getAllEntryType();
        $data['sub_voucher_code_status'] = $this->entry->getSubVoucherCodeStatus();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Edit Sub Voucher');
        $this->layouts->render('admin/edit_sub_voucher', $data, 'admin');
    }

    public function sub_voucher_edit($sub_voucher_id = NULL) {
        echo 'fgf';
        die();
        $data['parent_type_id'] = $id;
        $data['sub_voucher'] = $this->entry->getSubVoucherId($sub_voucher_id);
        if ($id == 1) {
            $data['parent_type'] = 'Receipt';
        }
        if ($id == 2) {
            $data['parent_type'] = 'Payment';
        }
        if ($id == 3) {
            $data['parent_type'] = 'Contra';
        }
        if ($id == 4) {
            $data['parent_type'] = 'Journal';
        }
        if ($id == 5) {
            $data['parent_type'] = 'Sales';
        }
        if ($id == 6) {
            $data['parent_type'] = 'Purchase';
        }

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Edit Sub Voucher');
        $this->layouts->render('admin/edit_sub_voucher', $data, 'admin');
    }

    public function sub_voucher_save() {
        $data['parent_id'] = $this->input->post('entry_type_id');
        $data['type'] = $this->input->post('sub_voucher');
        //$data['tracking_status'] = $this->input->post('tracking_status');
        $entry_no = $this->input->post('entry_no');
        $data['reset_interval'] = $this->input->post('interval');
        $data['title'] = $this->input->post('title');
        $data['sub_title'] = $this->input->post('sub_title');
        $data['declaration'] = $this->input->post('declaration');
        $data['transaction_no_status'] = $entry_no;
        // echo "<pre>";print_r($data);exit();
        if ($entry_no == '1') {
            $data['suffix_entry_no'] = $this->input->post('suffix_entry_no');
            $data['prefix_entry_no'] = $this->input->post('prefix_entry_no');
            $data['starting_entry_no'] = $this->input->post('strating_no');
            $strating_date = explode('/', $this->input->post('strating_date'));
            $data['strating_date'] = $strating_date[2] . '-' . $strating_date[0] . '-' . $strating_date[1];
        }
        $sub_voucher_code_status = $this->entry->getSubVoucherCodeStatus();
        if ($sub_voucher_code_status['voucher_no_status'] == '1') {
            $last_id = $this->entry->saveSubVoucher($data);
            if (strlen($last_id) == 1) {
                $updatedata['entry_code'] = 'SV00' . $last_id;
            } else if (strlen($last_id) == 2) {
                $updatedata['entry_code'] = 'SV0' . $last_id;
            } else {
                $updatedata['entry_code'] = 'SV' . $last_id;
            }
            $this->entry->updateSubVoucher($updatedata, $last_id);
        } else {
            $data['entry_code'] = $this->input->post('sub_voucher_no');
            $result = $this->entry->saveSubVoucher($data);
        }
        // if (!empty($result)) {
        $this->session->set_flashdata('successmessage', 'Sub Voucher Created successfully');
        // } else {
        //     $this->session->set_flashdata('errormessage', 'Oops! Something went wrong');
        // }
        $redirect = site_url('admin/vouchers');
        redirect($redirect);
    }

    public function sub_voucher_update() {
        $data['entry_type_id'] = $this->input->post('entry_type_id');
        $data['sub_voucher'] = $this->input->post('sub_voucher');
        $data['sub_voucher_no'] = $this->input->post('sub_voucher_no');
        $data['entry_no_status'] = $this->input->post('entry_no');
        if ($this->input->post('entry_no') == '1') {
            $data['suffix_entry_no'] = $this->input->post('suffix_entry_no');
            $data['prefix_entry_no'] = $this->input->post('prefix_entry_no');
            $data['starting_entry_no'] = $this->input->post('strating_no');
            $strating_date = explode('/', $this->input->post('strating_date'));
            $data['strating_date'] = $strating_date[2] . '-' . $strating_date[0] . '-' . $strating_date[1];
        }
        $sub_voucher_id = $this->input->post('id');
        $this->entry->updateSubVoucher($data, $sub_voucher_id);
        $this->session->set_flashdata('successmessage', 'Sub Voucher Updated successfully');
        $redirect = site_url('admin/vouchers');
        redirect($redirect);
    }

    public function sub_voucher($id = NULL) {
        $data['entry_type_id'] = $id;
        $where = "";
        $where = array(
            'entry_type_id' => $id,
            'status' => '1',
            'deleted' => '0'
        );
        $data['sub_vouchers'] = $this->entry->getAllSubVouchers($where);
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Sub Vouchers');
        $this->layouts->render('admin/sub_voucher_list', $data, 'admin');
    }

    public function delete_sub_voucher($sub_voucher_id = NULL, $type_id = NULL) {
        $data['status'] = '2';
        $data['deleted'] = '1';
        $this->entry->updateSubVoucher($data, $sub_voucher_id);

        $this->session->set_flashdata('successmessage', 'Sub Voucher Deleted successfully');
        // if ($type_id == 1) {
        //     $redirect = site_url('admin/receipts');
        //     redirect($redirect);
        // }
        // if ($type_id == 2) {
        //     $redirect = site_url('admin/payments');
        //     redirect($redirect);
        // }
        // if ($type_id == 3) {
        //     $redirect = site_url('admin/contres');
        //     redirect($redirect);
        // }
        // if ($type_id == 4) {
        //     $redirect = site_url('admin/jurnals');
        //     redirect($redirect);
        // }
        $redirect = site_url('admin/vouchers');
        redirect($redirect);
    }

    public function vouchers() {
        user_permission(113,'list');
            $where = "";
            $where = array(
                'sub_voucher.status' => '1',
                'sub_voucher.deleted' => '0'
            );

            $vaucher_arr = array();
            $parent_vouchers = $this->entry->getAllParentVouchers();
//            $sub_vouchers = $this->entry->getAllSubVouchers($where);

            $i = 0;
            foreach ($parent_vouchers as $vouchers) {
                $vaucher_arr[$i]['voucher_type'] = $vouchers['parent_id'];
                $vaucher_arr[$i]['id'] = $vouchers['id'];
                $vaucher_arr[$i]['voucher_name'] = $vouchers['type'];
                $vaucher_arr[$i]['parent_voucher_name'] = '';
                $vaucher_arr[$i]['entry_type_id'] = '';
                $vaucher_arr[$i]['voucher_no'] = $vouchers['entry_code'];
                $i++;
            }
//            foreach ($sub_vouchers as $vouchers) {
//                $vaucher_arr[$i]['voucher_type'] = 'C';
//                $vaucher_arr[$i]['id'] = $vouchers['id'];
//                $vaucher_arr[$i]['voucher_name'] = $vouchers['sub_voucher'];
//                $vaucher_arr[$i]['parent_voucher_name'] = $vouchers['type'];
//                $vaucher_arr[$i]['entry_type_id'] = $vouchers['entry_type_id'];
//                $vaucher_arr[$i]['voucher_no'] = $vouchers['sub_voucher_no'];
//                $i++;
//            }
            $data['sub_vouchers'] = $vaucher_arr;

            $addpermission = admin_users_permission('A', 'vouchers', $rtype = FALSE);
            if ($addpermission)
                $data['addpermission'] = "";
            else
                $data['addpermission'] = "not-permited";

            $statuspermission = admin_users_permission('S', 'vouchers', $rtype = FALSE);
            if ($statuspermission)
                $data['statuspermission'] = "";
            else
                $data['statuspermission'] = "not-permited";

            $editpermission = admin_users_permission('E', 'vouchers', $rtype = FALSE);
            if ($editpermission)
                $data['editpermission'] = "";
            else
                $data['editpermission'] = "not-permited";

            $deletepermission = admin_users_permission('D', 'vouchers', $rtype = FALSE);
            if ($deletepermission)
                $data['deletepermission'] = "";
            else
                $data['deletepermission'] = "not-permited";

            $data['csv_status'] = $this->companymodel->getCsvUploadHistory('voucher');

            $getsitename = getsitename();
            $this->layouts->set_title($getsitename . ' | Sub Vouchers');
            $this->layouts->render('admin/sub_voucher_list', $data, 'admin');
        
    }

    public function set_temp_tracking_data() {

        if ($this->input->post('ajax', TRUE)) {
            $ledger_index = $_POST['index'];
            $returnExisting = $this->entry->ledgerIndexIsExist($ledger_index);
            if ($returnExisting) {
                $this->entry->delExistLedgerIndex($ledger_index);
            }
            $returnId = $this->entry->setTrackingLedger(json_decode($_POST['jsonTracking'], true));
            if (empty($returnId)) {
                echo json_encode(array('SUCESS' => 0, 'MSG' => ''));
            } else {
                echo json_encode(array('SUCESS' => 1, 'MSG' => '', 'MENU' => $returnId));
            }
        } else {
            echo json_encode(array('SUCESS' => 0, 'MSG' => 'This page only access by ajax'));
        }
    }

    //30072016
    public function set_temp_billwish_data() {

        if ($this->input->post('ajax', TRUE)) {
            $ledger_index = $_POST['index'];
            //print_r(json_decode($_POST['jsonTracking']));die();
            $returnExisting = $this->entry->ledgerIndexIsExistInBill($ledger_index);
            if ($returnExisting) {
                $this->entry->delExistLedgerIndexInBill($ledger_index);
            }
            $returnId = $this->entry->setBillLedger(json_decode($_POST['jsonBill'], true));
            if (empty($returnId)) {
                echo json_encode(array('SUCESS' => 0, 'MSG' => ''));
            } else {
                echo json_encode(array('SUCESS' => 1, 'MSG' => '', 'MENU' => $returnId));
            }
        } else {
            echo json_encode(array('SUCESS' => 0, 'MSG' => 'This page only access by ajax'));
        }
    }

    //save temp bank details

    public function save_bank_details_ajax() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('transaction_type[]', 'Transaction Type', 'required');
            $this->form_validation->set_rules('instrument_no[]', 'Instrument Number', 'required');
            $this->form_validation->set_rules('instrument_date[]', 'Instrument Date', 'required');
            $this->form_validation->set_rules('bank_name[]', 'Bank Name', 'required');
            $this->form_validation->set_rules('branch_name[]', 'Branch Name', 'required');
            $this->form_validation->set_rules('ifsc_code[]', 'IFSC Code', 'required');
            $this->form_validation->set_rules('bank_amount[]', 'Amount', 'required|numeric');
            if ($this->form_validation->run() === TRUE) {
                $data = [];
                $total_amount = 0;
                $ledger_id = $this->input->post('ledger_id');
                $entry_no = $this->input->post('entry_no');
                $transaction_type = $this->input->post('transaction_type');
                $instrument_no = $this->input->post('instrument_no');
                $instrument_date = $this->input->post('instrument_date');
                $bank_name = $this->input->post('bank_name');
                $branch_name = $this->input->post('branch_name');
                $ifsc_code = $this->input->post('ifsc_code');
                $bank_amount = $this->input->post('bank_amount');
                //delete previous entry
                $this->db->where('entry_no =', $entry_no);
                $this->db->delete('temp_bank_details');
                //end delete previous entry
                foreach ($instrument_no as $key => $value) {
                    $total_amount+=$bank_amount[$key];
                    $item = array(
                        'entry_no' => $entry_no,
                        'ledger_id' => $ledger_id,
                        'transaction_type' => $transaction_type[$key],
                        'instrument_no' => $value,
                        'instrument_date' => $instrument_date[$key],
                        'bank_name' => $bank_name[$key],
                        'branch_name' => $branch_name[$key],
                        'ifsc_code' => $ifsc_code[$key],
                        'bank_amount' => $bank_amount[$key],
                        'create_date' => date("Y-m-d H:i:s")
                    );
                    $data[] = $item;
                }
                $res = $this->entry->saveTempBankDetails($data);
                if ($res) {
                    $data_msg['res'] = 'success';
                    $data_msg['amount'] = $total_amount;
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = $this->form_validation->error_array();
            }
            echo json_encode($data_msg);
            exit;
        }
    }

    public function check_bank_betails() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $group_id_arr = [];
            $ledger_id_arr = [];
            $group_id = 10;
            $group_id_arr[] = $group_id;
            $all_sub_group = $this->entry->getAllSubGroup($group_id);
            foreach ($all_sub_group as $value) {
                $group_id_arr[] = $value->id;
            }
            $get_all_ledger = $this->entry->getAllLedger($group_id_arr);
            foreach ($get_all_ledger as $val) {
                $ledger_id_arr[] = $val->id;
            }
            $ledger_id = $this->input->post('ledger_id');
            if (in_array($ledger_id, $ledger_id_arr)) {
                $data_msg['res'] = 'success';
            } else {
                $data_msg['res'] = 'error';
            }
            echo json_encode($data_msg);
        }
    }

    //get temp bank details

    public function get_temp_bank_betails() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $ledger_id = $this->input->post('ledger_id');
            $entry_no = $this->input->post('entry_no');
            $data_msg['transaction_types'] = $this->entry->allTransactionType();
            $temp_bank_data = $this->entry->allTempBankData($ledger_id, $entry_no);
            if ($temp_bank_data) {
                $data_msg['temp_bank_data'] = $temp_bank_data;
                $data_msg['res'] = 'success';
            } else {
                $data_msg['temp_bank_data'] = array();
            }
            echo json_encode($data_msg);
        }
    }

    public function ajax_delete_sub_voucher() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $voucher_id = $this->input->post('delete_entry_id');
            $row = $this->entry->checkEntryExist($voucher_id);
            if (count($row) > 0) {
                $data_msg['res'] = 'error';
                $data_msg['message'] = 'This Sub Voucher can not be deleted. Because This are already in used.';
            } else {
                $res = $this->entry->deleteVoucher($voucher_id);
                if ($res) {
                    $data_msg['voucher_id'] = $voucher_id;
                    $data_msg['res'] = 'success';
                    $data_msg['message'] = 'Sub-voucher deleted successfully.';
                } else {
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = 'Error occured please try again.';
                }
            }
            echo json_encode($data_msg);
        }
    }

    public function updateCurrencyVal() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $this->load->helper('currency_helper');
            $currency = $this->input->post('currency');
            $configuration = $this->entry->getSettingsConfiguration();
            $settings = $this->entry->getSettings();
            $from_currency = $this->entry->getCurrency($currency)->currency;
            $to_currency = $this->entry->getCurrency($settings->base_currency)->currency;
            if ($configuration && $configuration->required_updated_currency == 1) {
                $res = $this->entry->updateCurrency($currency, $from_currency, $to_currency);
                if ($res) {
                    $data_msg['res'] = 'success';
                    $data_msg['message'] = 'Currency updated successfully.';
                } else {
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = 'Error occured please try again.';
                }
            }
            echo json_encode($data_msg);
        }
    }

    //delete recurring entry
    public function ajax_delete_recurring_entry() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $entry_id = $this->input->post('delete_entry_id');
            $res = $this->entry->deleteRecurringEntry($entry_id);
            if ($res) {
                $data_msg['entry_id'] = $entry_id;
                $data_msg['res'] = 'success';
                $data_msg['message'] = 'Entry deleted successfully.';
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = 'Error occured please try again.';
            }
            echo json_encode($data_msg);
        }
    }

    //delete post dated entry
    public function ajax_delete_post_dated_entry() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $entry_id = $this->input->post('delete_entry_id');
            if ($entry_id) {
                $data = array('deleted' => 1);
                $this->db->trans_begin();
                //entry
                $this->db->where('id', $entry_id);
                $this->db->update(tablename('entry'), $data);
                //ledger account details
                $this->db->where('entry_id', $entry_id);
                $this->db->update(tablename('ladger_account_detail'), $data);
                //bank_details
                $this->db->where('entry_id', $entry_id);
                $this->db->update(tablename('bank_details'), $data);
                //billwish_details
                $this->db->where('entry_id', $entry_id);
                $this->db->update(tablename('billwish_details'), $data);
                //tracking_details
                $this->db->where('entry_id', $entry_id);
                $this->db->update(tablename('tracking_details'), $data);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = 'Error occured please try again.';
                } else {
                    $this->db->trans_commit();
                    $data_msg['entry_id'] = $entry_id;
                    $data_msg['res'] = 'success';
                    $data_msg['message'] = 'Entry deleted successfully.';
                }
            }
            echo json_encode($data_msg);
        }
    }

    //post dated approve button
    //delete recurring entry
    public function ajax_postdated_approve() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $entry_id = $this->input->post('post_entry_id');
            $entry = $this->entry->getEntryType($entry_id);
            if ($entry) {
                $entry_type_id = $entry->entry_type_id;
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
                $entry_data = array(
                    'entry_no' => $entry_number,
                    'deleted' => '0'
                );
                $this->db->trans_begin();
                $this->entry->approvePostDateEntry($entry_data, $entry_id);
                $this->entry->approvePostDateEntryDetails($entry_id);
                $this->entry->approvePostDateEntryBillDetails($entry_id);
                $this->entry->approvePostDateEntryBankDetails($entry_id);
                $this->entry->approvePostDateEntryTrackingDetails($entry_id);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = "There was an error please try again.";
                } else {
                    $this->db->trans_commit();
                    $data_msg['entry_id'] = $entry_id;
                    $data_msg['res'] = 'success';
                    $data_msg['message'] = 'Entry added in current entry successfully.';
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = 'Error occured please try again.';
            }
            echo json_encode($data_msg);
        }
    }

    //ajax delete entry
    public function ajax_delete_transaction() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $entry_id = $this->input->post('delete_entry_id');
            $type = $this->input->post('delete_entry_type');
            if ($entry_id) {
                $entry = $this->entry->getEntry($entry_id);
                if ($entry) {
                    if ($type != 'recurring') {
                        $res = $this->entry->deleteTransaction($entry_id);
                    } else {
                        $res = $this->entry->deleteRecurringEntry($entry_id);
                    }
                    if ($res) {
                        $data_msg['message'] = 'Entry Deleted Successfully.';
                        $data_msg['res'] = 'success';
                        $data_msg['entry_id'] = $entry_id;
                        $data_msg['type'] = $type;
                    } else {
                        $data_msg['message'] = 'Error in process. Please try again.';
                        $data_msg['res'] = 'error';
                    }
                }
                
                // log update
                $this->load->model('front/usermodel', 'currentusermodel');
                $log = array(
                    'user_id' => $this->session->userdata('admin_uid'),
                    'branch_id' => $this->session->userdata('branch_id'),
                    'module' => strtolower($entry->entry_type),
                    'action' => '`' . $entry->entry_no . '` <b>deleted</b>',
                    'performed_at' => date('Y-m-d H:i:s', time())
                );
                $this->currentusermodel->updateLog($log);
            }
            echo json_encode($data_msg);
        }
    }

    function save_the_voucher()
    {
        $data_msg = [];
        $data_msg['res'] = 'error';
        if(!empty($_FILES['formData']['name']))
        {
            $contactfileheadersChecking = fopen($_FILES['formData']['tmp_name'], "r");
            $headers = fgetcsv($contactfileheadersChecking, 1000, ";");
            $headers = explode(',', $headers[0]);
            $arrFileheaders = array(
                'Voucher Name', 'Parent Voucher Name', 'Declaration', 'Title', 'Sub Title', 'Numbering Format', 'Prefix', 'Decimal', 'Suffix'
            );
            $getDifferents = array_diff($arrFileheaders, $headers);
            $notallowedGroups = array();
            if(count($getDifferents) == 0)  
            {
                $filename = date('Y_m_d_H_I').rand(100,1000).strtolower('voucher').str_replace(' ','_',str_replace('-','_',$_FILES['formData']['name']));
                $filepath = getcwd().'/assets/uploads/';
                // $filepath = FCPATH.'assets/uploads/';
                $fullPath = $filepath.$filename;
                if(move_uploaded_file($_FILES['formData']['tmp_name'], $fullPath))
                {
                    $filePath = getcwd().'/assets/uploads/'.$filename;
                    $fileData =   $this->csvreader->parse_file($filePath);

                    if (empty($fileData)) {
                        $data_msg['msg'] = 'No data found in csv';
                        echo json_encode($data_msg);exit();
                    }

                    $striker = 2;
                    $getargsduplicate = array();
                    $getargscurrect = array();
                    for($countArr=1;$countArr<count($fileData);$countArr++)
                    {
                        if($fileData[$countArr]['Voucher Name'] == $fileData[$striker]['Voucher Name'])
                        {
                            $getargs[] = $fileData[$countArr];                        
                        }else{
                            $getargscurrect[$countArr] = $fileData[$countArr];
                        }
                        $striker++;
                    }
                    $getargscurrect[$countArr] = $fileData[$countArr];

                    if(!empty($fileData))
                    {
                        $financial_year = get_financial_year();            
                        $finans_start_date = date("Y-m-d", strtotime(current($financial_year)));
                        $x = 1;
                        $lastID = 1;
                        $st = 0;
                        $getAlldata = $this->groupmodel->all_uploaded_data_by_order('pb_entry_type','DESC','id',1);

                        if(empty($getAlldata))
                        {
                            $lastID = 1;
                        }else{
                            $lastID = $getAlldata[0]->id;
                        }

                        $max_id = $this->groupmodel->get_max_id('pb_entry_type');
                        foreach ($getargscurrect as $allval) {
                            $arr = array('type'=>$allval['Voucher Name']);
                            $getExisting = $this->groupmodel->get_existing($arr,'pb_entry_type');
                            $arrgs = array('type'=>$allval['Parent Voucher Name'], 'id !=' => 13, 'parent_id' => 0);
                            $getParent = $this->groupmodel->get_tabledescription($arrgs,'pb_entry_type');

                            if(!empty($getParent) && $getParent[0]->id>0){
                                if($getExisting == 1){
                                    $arrIDS[$st] = $lastID++;
                                    $arrPush[] = array(
                                        'type'=>$allval['Voucher Name'],
                                        'alias'=>strtolower($allval['Voucher Name']),
                                        'parent_id'=>$getParent[0]->id,
                                        'entry_code'=>'SV'.str_pad(++$max_id, 4, '0', STR_PAD_LEFT),
                                        'reset_interval'=>$allval['Numbering Format'],
                                        'declaration'=>$allval['Declaration'],
                                        'title'=>$allval['Title'],
                                        'sub_title'=>$allval['Sub Title'],
                                        'prefix_entry_no'=>$allval['Prefix'],
                                        'suffix_entry_no'=>$allval['Suffix'],
                                        'starting_entry_no'=>$allval['Decimal'],
                                        'status'=>1,
                                        'deleted'=>0,
                                        'transaction_no_status'=>1,
                                        'strating_date' => date('Y-m-d'),
                                        'create_date' => date('Y-m-d')
                                    );
                                    $st++;

                                }else{
                                    unset($arrPush);
                                }
                            }else{
                                if ($allval['Parent Voucher Name'] != '') {
                                    $notallowedGroups[] = $allval['Parent Voucher Name'];
                                }                                
                            }

                            $x++;

                        }

                        if(!empty($arrPush)){
                            $inserted = $this->groupmodel->save_data_from_file($arrPush,'pb_entry_type');
                            if($inserted)
                            {
                                $csv_history = array(
                                    'module' => 'voucher',
                                    'status' => 1,
                                    'uploaded_by' => $this->session->userdata('admin_uid'),
                                    'branch_id' => $this->session->userdata('branch_id'),
                                    'uploaded_on' => date('Y-m-d H:i:s', time())
                                );
                                $this->companymodel->updateCsvUploadHistory($csv_history);

                                $data_msg['res'] = 'success';
                                $data_msg['msg'] = 'File and content saved successfully';
                                if (!empty($notallowedGroups)) {
                                    $data_msg['msg'] .= '<br>Unknown Parents :: ' . implode(', ',$notallowedGroups);
                                }
                            }else{
                                $data_msg['msg'] = 'Please try later';
                            }
                        }else{
                            $data_msg['msg'] = 'No new vouchers found';
                            if (!empty($notallowedGroups)) {
                                $data_msg['msg'] .= '<br>Unknown Parents :: ' . implode(', ',$notallowedGroups);
                            }
                            
                        }
                    }
                    unlink($filePath);
                }

                if(!empty($notallowedGroups))
                {
                    $xyz = implode(',', $notallowedGroups);
                }

            }else{
                $data_msg['msg'] = 'File format does not match,Try again';
            }
        }else{
            $data_msg['msg'] = 'A csv file is required';
        }

        echo json_encode($data_msg);
    }


    function downloadVouchersAsCSV()
    {
            $this->load->dbutil();
            $this->load->helper('file');
            $this->load->helper('download');
            $query = $this->groupmodel->getCsvQuery("E.type as `Voucher Name`, PE.type as `Parent Voucher Name`, E.declaration as `Declaration`, E.title as `Title`, E.sub_title as `Sub Title`, E.reset_interval as `Numbering Format`, E.prefix_entry_no as `Prefix`, E.starting_entry_no as `Decimal`, E.suffix_entry_no as `Suffix`", 'pb_entry_type E', 'pb_entry_type PE', 'E.parent_id = PE.id', 'left', array('E.status' => '1', 'E.deleted' => '0'), 'PE.type, E.type');
            $delimiter = ",";
            $newline = "\n";
            $enclosure = '';
            $data = $this->dbutil->csv_from_result($query, $delimiter, $newline, $enclosure);
            ob_clean();
            force_download('voucher.csv', $data);
    }

}

/* End of file entries.php */
/* Location: ./application/controllers/entries.php */
