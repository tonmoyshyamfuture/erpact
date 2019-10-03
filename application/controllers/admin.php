<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller {

    /**
     * This is the Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->model('admin/dashboardmodel');
        $this->load->helper('financialyear');
    }

    public function test() {

        $n = 10;

        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $i; $j++) {
                echo (($i - $j) % 2) . '&nbsp;';
            }
            echo '<br>';
        }
    }

    public function mail() {
        $this->layouts->render('mail/template', array(), 'admin');
    }

    public function error() {
        $this->layouts->render('admin/404', array(), 'branch');
    }


    public function index() {
      // echo 'dfdsdfsdf';
      // die();
        //cookie_authenticate();
        $admin_uid = $this->session->userdata('admin_uid');
        //$data['pagetype']="Log In";
        //$this->load->view('admin/login',$data);
        if (!empty($admin_uid)) {
            redirect(site_url('admin/dashboard'));
        }

        // redirect(SAAS_URL, 'refresh');
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Log in');
        $this->layouts->render('admin/login', array(), 'admin_login');
    }


    public function forgetpass() {
        //cookie_authenticate();
        //$data['pagetype']="Forget Password";
        //$this->load->view('admin/forgetpass',$data);
        $admin_uid = $this->session->userdata('admin_uid');

        if (!empty($admin_uid)) {
            redirect(site_url('admin/branch'));
        }

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Forget Password');
        $this->layouts->render('admin/forgetpass', array(), 'admin_login');
    }


    //shuffle an array
    function shuffle_assoc($my_array) {
        $keys = array_keys($my_array);

        shuffle($keys);
        $new = [];
        foreach ($keys as $key) {
            $new[$key] = $my_array[$key];
        }

        $my_array = $new;

        return $my_array;
    }

    public function add_branch() {
        admin_authenticate();
        $data = [];
        $data['countries'] = $this->dashboardmodel->getAllCountries();
        $data['states'] = $this->dashboardmodel->getStateByCountryId(101); // India country_id = 101
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Add Branch');
        $this->layouts->render('admin/create_branch', $data, 'branch');
    }

    public function branch() {
        admin_authenticate();
        $this->load->model('admin/companymodel');
        $data = [];
        $user_id = $this->session->userdata('admin_uid');

        //somnath - get user profile
        $cur_user = $this->dashboardmodel->getUserProfile($this->session->userdata('admin_uid'));
        $this->session->set_userdata('user_image', $cur_user->image);
        $this->session->set_userdata('fname', $cur_user->fname);
        $last_login = date('Y-m-d H:i:s', time());
        $this->session->set_userdata('last_login', $last_login);
        $this->dashboardmodel->updateLastLogin(array('last_login' => $last_login), $this->session->userdata('admin_uid'));

        $company_id = $this->session->userdata('company_id');
        $this->session->unset_userdata('inventory_method'); // somnath - unset inventory method after branch logoout
        $data['branch'] = $this->dashboardmodel->getCompanyBranch($user_id);
        $data['company'] = $this->companymodel->getCompany($company_id);
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Branch');
        $this->layouts->render('admin/branch', $data, 'branch');
    }

    public function set_branch() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $branch_id = $this->input->post('branch_id');
            $this->session->set_userdata('branch_id', $branch_id);
            $selected_branch = [$branch_id];
            $this->session->set_userdata('selected_branch', $selected_branch);


            $selected_branch_str = '';
            foreach ($this->session->userdata('selected_branch') as $b) {
                $selected_branch_str.="'" . $b . "'" . ',';
            }
            $selected_branch_str = rtrim($selected_branch_str, ',');
            $this->session->set_userdata('selected_branch_str', $selected_branch_str);
            if ($this->session->userdata('branch_id')) {
                $data_msg['res'] = 'success';
                $data_msg['url'] = site_url('admin/dashboard');
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = 'You do not have access for this branch.';
            }
            echo json_encode($data_msg);
            exit;
        }
    }

    public function dashboard() {

        admin_authenticate();
        $this->load->helper('core');
        $this->load->helper('financialyear');
        $financial_year = get_financial_year();
        $data = array();
        $data['dashboard_setting'] = $this->dashboardmodel->getDashboardSetting();

        //somnath - get user profile
        $cur_user = $this->dashboardmodel->getUserProfile($this->session->userdata('admin_uid'));
        $this->session->set_userdata('user_image', $cur_user->image);
        $this->session->set_userdata('fname', $cur_user->fname);
        $last_login = date('Y-m-d H:i:s', time());
        $this->session->set_userdata('last_login', $last_login);
        $this->dashboardmodel->updateLastLogin(array('last_login' => $last_login), $this->session->userdata('admin_uid'));


        $data['cur_financial_year'] = date('Y', strtotime(current($financial_year)));
        $data['year_staring_day'] = date("Y-m-d", strtotime(current($financial_year)));
        $data['year_ending_day'] = date("Y-m-t", strtotime(end($financial_year)));
        $from_date = (isset($_GET['staring_day']) && $_GET['staring_day'] != '') ? $_GET['staring_day'] : $data['year_staring_day'];

//        $max_entry = $this->dashboardmodel->getMaxEntryDate();
//        if (empty($max_entry->create_date)) {
//            $max_entry->create_date = date('Y-m-d');
//        }

//        $to_date = (isset($_GET['ending_day']) && $_GET['ending_day'] != '') ? $_GET['ending_day'] : $max_entry->create_date;

        $max_entry_date = lastEntryDate();
        $to_date = (isset($_GET['ending_day']) && $_GET['ending_day'] != '') ? $_GET['ending_day'] : $max_entry_date;


        if (!$this->session->userdata('current_ending_day')) {
            $this->session->set_userdata('current_ending_day', $to_date);
        } else {
            $to_date = $this->session->userdata('current_ending_day');
        }


        $overdue_date = date("Y-m-d H:i:s");
        if ($to_date < $overdue_date) {
            $overdue_date = $to_date;
        }

        $finans_start_date = $data['year_staring_day'];
        $data['staring_day'] = $from_date;
        $data['ending_day'] = $to_date;
        $receivable = 0;
        $payable = 0;

        //receivable
        $overdue_amount = 0;
        $sundry_detors_id = 15;
        $sundry_detors_id_arr = [$sundry_detors_id];
        $sub_id_arr = [];
        $sub_group = $this->dashboardmodel->getSubGroup($sundry_detors_id_arr);
        foreach ($sub_group as $sub) {
            $sundry_detors_id_arr[] = $sub->id;
            $sub_id_arr[] = $sub->id;
            $sub_sub_group = $this->dashboardmodel->getSubGroup($sub_id_arr);
            foreach ($sub_sub_group as $sub_sub) {
                $sundry_detors_id_arr[] = $sub_sub->id;
            }
        }
        $debitor_ledger_id_arr = [null];
        $all_ledger = $this->dashboardmodel->getAllLedgerId($sundry_detors_id_arr);
        foreach ($all_ledger as $ledger) {
            $debitor_ledger_id_arr[] = $ledger->id;
        }
        // $dr_bill = $this->dashboardmodel->getBillAmount('Dr', $debitor_ledger_id_arr, $finans_start_date, $to_date);
        // $cr_bill = $this->dashboardmodel->getBillAmount('Cr', $debitor_ledger_id_arr, $finans_start_date, $to_date);
        $all_overdue_bill = $this->dashboardmodel->getOverdueBill($debitor_ledger_id_arr, $finans_start_date, $to_date, $overdue_date);
        foreach ($all_overdue_bill as $bill) {
            $dr_overdue_bill = $this->dashboardmodel->getOverdueBillAmount('Dr', $bill->bill_name, $debitor_ledger_id_arr, $finans_start_date, $to_date);
            $cr_overdue_bill = $this->dashboardmodel->getOverdueBillAmount('Cr', $bill->bill_name, $debitor_ledger_id_arr, $finans_start_date, $to_date);
            $overdue_amount+=($dr_overdue_bill->total - $cr_overdue_bill->total);
        }
        // $data['total_receivable'] = ($dr_bill->total - $cr_bill->total);
        $data['overdue_receivable'] = $overdue_amount;
        // $data['current_receivable'] = ($dr_bill->total - $cr_bill->total) - $overdue_amount;

        /* ------------------ */

        $this->load->model('accounts/report', 'reportModel');
        $sundry_detors_balance = $this->reportModel->getLedgerDetailsByGroupIdByDate(15, $from_date, $to_date);

        $opening_balance = 0;
        $closing_balance = 0;
        $total_closing_balance = 0;
        $cr_balance = 0;
        $dr_balance = 0;
        if (isset($sundry_detors_balance)) {

            foreach ($sundry_detors_balance as $row) {
                if ($row['account_type'] == 'Dr') {
                    $opening_balance += $row['opening_balance'];
                }
                if ($row['account_type'] == 'Cr') {
                    $opening_balance -= $row['opening_balance'];
                }

                $cr_balance += $row['cr_balance'];
                $dr_balance += $row['dr_balance'];
            }
            $total_closing_balance = $dr_balance - $cr_balance + $opening_balance;
        }

        $data['total_receivable'] = $total_closing_balance;
        // $data['overdue_receivable'] = 0.00;
        $data['current_receivable'] = $data['total_receivable'] - $data['overdue_receivable'];

        /* ------------------ */




        //end receivable
        //payable
        $overdue_amount = 0;
        $sundry_creditor_id = 23;
        $sundry_creditor_id_arr = [$sundry_creditor_id];
        $sub_id_arr = [];
        $sub_group = $this->dashboardmodel->getSubGroup($sundry_creditor_id_arr);
        foreach ($sub_group as $sub) {
            $sundry_creditor_id_arr[] = $sub->id;
            $sub_id_arr[] = $sub->id;
            $sub_sub_group = $this->dashboardmodel->getSubGroup($sub_id_arr);
            foreach ($sub_sub_group as $sub_sub) {
                $sundry_creditor_id_arr[] = $sub_sub->id;
            }
        }
        $creditor_ledger_id_arr = [null];
        $all_ledger = $this->dashboardmodel->getAllLedgerId($sundry_creditor_id_arr);
        foreach ($all_ledger as $ledger) {
            $creditor_ledger_id_arr[] = $ledger->id;
        }
        // $dr_bill = $this->dashboardmodel->getBillAmount('Dr', $creditor_ledger_id_arr, $finans_start_date, $to_date);
        // $cr_bill = $this->dashboardmodel->getBillAmount('Cr', $creditor_ledger_id_arr, $finans_start_date, $to_date);
        $all_overdue_bill = $this->dashboardmodel->getOverdueBill($creditor_ledger_id_arr, $finans_start_date, $to_date, $overdue_date);
        foreach ($all_overdue_bill as $bill) {
            $dr_overdue_bill = $this->dashboardmodel->getOverdueBillAmount('Dr', $bill->bill_name, $creditor_ledger_id_arr, $finans_start_date, $to_date);
            $cr_overdue_bill = $this->dashboardmodel->getOverdueBillAmount('Cr', $bill->bill_name, $creditor_ledger_id_arr, $finans_start_date, $to_date);
            $overdue_amount+=($cr_overdue_bill->total - $dr_overdue_bill->total);
        }
        // $data['total_payable'] = ($cr_bill->total - $dr_bill->total);
        $data['overdue_payable'] = $overdue_amount;
        // $data['current_payable'] = ($cr_bill->total - $dr_bill->total) - $overdue_amount;

        /* --------------- */

        $sundry_creditor_balance = $this->reportModel->getLedgerDetailsByGroupIdByDate(23, $from_date, $to_date);

        $opening_balance = 0;
        $closing_balance = 0;
        $total_closing_balance = 0;
        $cr_balance = 0;
        $dr_balance = 0;
        if (isset($sundry_creditor_balance)) {

            foreach ($sundry_creditor_balance as $row) {
                if ($row['account_type'] == 'Dr') {
                    $opening_balance += $row['opening_balance'];
                }
                if ($row['account_type'] == 'Cr') {
                    $opening_balance -= $row['opening_balance'];
                }

                $cr_balance += $row['cr_balance'];
                $dr_balance += $row['dr_balance'];
            }
            $total_closing_balance = $dr_balance - $cr_balance + $opening_balance;
        }

        $data['total_payable'] = $total_closing_balance;
        // $data['overdue_payable'] = 0.00;
        $data['current_payable'] = $data['total_payable'] - $data['overdue_payable'];

        /* --------------- */



        //end payable======================================================

        $cash_id = 11;  //for Cash Group
        $bank_id = 10; //for Bank Group
        $sales_id = 37; //sales id
        $purchase_id = 32;

        $sundry_debtors_id = 15;
        $sundry_creditors_id = 23;

        //fand flow=========================================================
//        $current_assets_group_id = 9;
//        $current_liability_group_id = 16;
//        $fixed_assets_group_id = 6;
//        $investment_group_id = 7;
//        $fand_flow_group_id_arr = [$current_assets_group_id, $current_liability_group_id, $fixed_assets_group_id, $investment_group_id];
//        //get child group againest parent
//        $fand_sub_id_arr = [];
//        $fand_sub_group = $this->dashboardmodel->getSubGroup($fand_flow_group_id_arr);
//        foreach ($fand_sub_group as $sub) {
//            $fand_flow_group_id_arr[] = $sub->id;
//            $fand_sub_id_arr[] = $sub->id;
//            $sub_sub_group = $this->dashboardmodel->getSubGroup($fand_sub_id_arr);
//            foreach ($sub_sub_group as $sub_sub) {
//                $fand_flow_group_id_arr[] = $sub_sub->id;
//            }
//        }
//        $fand_ledger_id_arr = [];
//        $fand_flow_all_ledger = $this->dashboardmodel->getAllLedgerId($fand_flow_group_id_arr);
//        foreach ($fand_flow_all_ledger as $ledger) {
//            $fand_ledger_id_arr[] = $ledger->id;
//        }
//        $fand_flow_opening_transaction = $this->dashboardmodel->getOpeningBalance($fand_ledger_id_arr, $finans_start_date, $to_date);
//        $fand_flow_cr_transaction = $this->dashboardmodel->getCrBalance($fand_ledger_id_arr, $from_date, $to_date);
//        $fand_flow_dr_transaction = $this->dashboardmodel->getDrBalance($fand_ledger_id_arr, $from_date, $to_date);
//        $data['fand_flow_opening'] = $fand_flow_opening_transaction[0]->opening_balance;
//        $data['fand_flow_in'] = $fand_flow_dr_transaction[0]->dr_balance;
//        $data['fand_flow_out'] = $fand_flow_cr_transaction[0]->cr_balance;
//        $net_fand_flow = 0;
//        if ($fand_flow_opening_transaction[0]->type == 'Dr') {
//            $net_fand_flow = $fand_flow_opening_transaction[0]->opening_balance + $fand_flow_dr_transaction[0]->dr_balance - $fand_flow_cr_transaction[0]->cr_balance;
//        } else if ($fand_flow_opening_transaction[0]->type == 'Cr') {
//            $net_fand_flow = $fand_flow_opening_transaction[0]->opening_balance + $fand_flow_cr_transaction[0]->cr_balance - $fand_flow_dr_transaction[0]->dr_balance;
//        }
//        $data['fand_net_flow'] = $net_fand_flow;

        $finans_start_date = $from_date;
        $inflow = array();
        $outflow = array();
        $working_capital = 0;
        $current_assets_group_id = 9;
        $current_liability_group_id = 16;
        $fixed_assets_group_id = 6;
        $investment_group_id = 7;
        $data['fand_flow_in'] = 0;
        $data['fand_flow_out'] = 0;
        $dr_fixed_assets_balance = get_dr_balance_by_date($fixed_assets_group_id, $from_date, $to_date, $finans_start_date);//add $finans_start_date @sudip28032018 outflow
        $cr_fixed_assets_balance = get_cr_balance_by_date($fixed_assets_group_id, $from_date, $to_date, $finans_start_date);//add $finans_start_date @sudip28032018 inflow

        $dr_investment_balance = get_dr_balance_by_date($investment_group_id, $from_date, $to_date, $finans_start_date);//add $finans_start_date @sudip28032018 outflow
        $cr_investment_balance = get_cr_balance_by_date($investment_group_id, $from_date, $to_date, $finans_start_date);//add $finans_start_date @sudip28032018 inflow

        //working Capital Calculation
        $dr_current_assets_balance = get_dr_balance_by_date($current_assets_group_id, $from_date, $to_date, $finans_start_date);//add $finans_start_date @sudip28032018
        $cr_current_assets_balance = get_cr_balance_by_date($current_assets_group_id, $from_date, $to_date, $finans_start_date);//add $finans_start_date @sudip28032018
        $current_assets_balance = $dr_current_assets_balance - $cr_current_assets_balance;

        $dr_current_liability_balance = get_dr_balance_by_date($current_liability_group_id, $from_date, $to_date, $finans_start_date);//add $finans_start_date @sudip28032018
        $cr_current_liability_balance = get_cr_balance_by_date($current_liability_group_id, $from_date, $to_date, $finans_start_date);//add $finans_start_date @sudip28032018
        $current_liability_balance = $cr_current_liability_balance - $dr_current_liability_balance;

        if ($current_assets_balance >= 0) {
            if ($current_liability_balance >= 0) {
                $working_capital = $current_assets_balance - $current_liability_balance;
                $data['fand_flow_out'] = $working_capital + $dr_fixed_assets_balance + $dr_investment_balance;
            } else {
                $working_capital = $current_assets_balance + str_replace('-', '', $current_liability_balance);
                $data['fand_flow_out'] = $working_capital + $dr_fixed_assets_balance + $dr_investment_balance;
            }
        } else {
            if ($current_liability_balance >= 0) {
                $working_capital = str_replace('-', '', $current_assets_balance) + $current_liability_balance;
                $data['fand_flow_in'] = $working_capital + $cr_fixed_assets_balance + $cr_investment_balance;
            } else {
                $working_capital = str_replace('-', '', $current_liability_balance) - str_replace('-', '', $current_assets_balance);
                if ($working_capital >= 0) {
                    $working_capital = $working_capital;
                    $data['fand_flow_out'] = $working_capital + $dr_fixed_assets_balance + $dr_investment_balance;
                } else {
                    $working_capital = str_replace('-', '', $working_capital);
                    $data['fand_flow_in'] = $working_capital + $cr_fixed_assets_balance + $cr_investment_balance;
                }
            }
        }

        $data['fand_net_flow'] = $data['fand_flow_in'] - $data['fand_flow_out'];

        //end fand flow==================================================


        $group_id_arr = [$cash_id, $bank_id];
        //get child group againest parent
        $sub_id_arr = [];
        $sub_group = $this->dashboardmodel->getSubGroup($group_id_arr);
        foreach ($sub_group as $sub) {
            $group_id_arr[] = $sub->id;
            $sub_id_arr[] = $sub->id;
            $sub_sub_group = $this->dashboardmodel->getSubGroup($sub_id_arr);
            foreach ($sub_sub_group as $sub_sub) {
                $group_id_arr[] = $sub_sub->id;
            }
        }
        //all ledger within cash and bank group
        $ledger_id_arr = [];
        $all_ledger = $this->dashboardmodel->getAllLedgerId($group_id_arr);
        foreach ($all_ledger as $ledger) {
            $ledger_id_arr[] = $ledger->id;
        }
        $dr_opening_transaction = $this->dashboardmodel->getOpeningBalanceForFundFlow($ledger_id_arr, $finans_start_date, $to_date,'Dr');
        $cr_opening_transaction = $this->dashboardmodel->getOpeningBalanceForFundFlow($ledger_id_arr, $finans_start_date, $to_date,'Cr');
        $cr_transaction = $this->dashboardmodel->getCrBalance($ledger_id_arr, $from_date, $to_date);
        $dr_transaction = $this->dashboardmodel->getDrBalance($ledger_id_arr, $from_date, $to_date);
        $data['cash_flow_opening'] = ($dr_opening_transaction->opening_balance - $cr_opening_transaction->opening_balance);
        $data['cash_flow_in'] = $dr_transaction[0]->dr_balance;
        $data['cash_flow_out'] = $cr_transaction[0]->cr_balance;
        $net_flow = 0;
        $net_flow = ((($dr_opening_transaction->opening_balance+ $dr_transaction[0]->dr_balance) - ($cr_opening_transaction->opening_balance+$cr_transaction[0]->cr_balance)));
        $data['cash_net_flow'] = $net_flow;
        //end cashflow
        //watch list
        $watch_list_arr = [];
        //ledger
        $watch_ledger_list_arr = [];
        $all_watch_list_ledger = $this->dashboardmodel->getAllWatchlistLedger();
        foreach ($all_watch_list_ledger as $value) {
            $ledger_arr = [];
            $ledger_id_arr = [$value->id];
            $ledger_arr['name'] = $value->ladger_name;
            $ledger_arr['account_type'] = $value->account_type;
            $ledger_arr['group_ledger_id'] = $value->group_ledger_id;
            $ledger_arr['type'] = 'ledger';
            $opening = $this->dashboardmodel->getOpeningBalance($ledger_id_arr, $finans_start_date, $to_date);
            $cr_balance = $this->dashboardmodel->getCrBalance($ledger_id_arr, $finans_start_date, $to_date);
            $dr_balance = $this->dashboardmodel->getDrBalance($ledger_id_arr, $finans_start_date, $to_date);
            $closing_balance = 0;
            if (isset($opening[0]->type) && $opening[0]->type == 'Dr') {
                $closing_balance = ($opening[0]->opening_balance + $dr_balance[0]->dr_balance) - $cr_balance[0]->cr_balance;
                if ($closing_balance > 0) {
                    $ledger_arr['balence'] = $closing_balance;
                    $ledger_arr['balance_type'] = "Dr";
                } else {
                    $ledger_arr['balence'] = 0 - $closing_balance;
                    $ledger_arr['balance_type'] = "Cr";
                }
            } else {
                $closing_balance = ($opening[0]->opening_balance + $cr_balance[0]->cr_balance) - $dr_balance[0]->dr_balance;
                if ($closing_balance > 0) {
                    $ledger_arr['balence'] = $closing_balance;
                    $ledger_arr['balance_type'] = "Cr";
                } else {
                    $ledger_arr['balence'] = 0 - $closing_balance;
                    $ledger_arr['balance_type'] = "Dr";
                }
            }
            $watch_ledger_list_arr[] = $ledger_arr;
        }
        //end ledger
        //group
        $all_watch_list_group = $this->dashboardmodel->getAllWatchlistGroup();
        $watch_list_group_arr = [];
        foreach ($all_watch_list_group as $value) {
            $closing = 0;
            $group_arr = [];
            $group_id_arr = [$value->id];
            $group_arr['name'] = $value->group_name;
            $group_arr['account_type'] = $value->account_type;
            $group_arr['group_ledger_id'] = $value->group_ledger_id;
            $group_arr['type'] = 'group';
            //get child group againest parent
            $sub_id_arr = [];
            $sub_group = $this->dashboardmodel->getSubGroup($group_id_arr);
            foreach ($sub_group as $sub) {
                $group_id_arr[] = $sub->id;
                $sub_id_arr[] = $sub->id;
                $sub_sub_group = $this->dashboardmodel->getSubGroup($sub_id_arr);
                foreach ($sub_sub_group as $sub_sub) {
                    $group_id_arr[] = $sub_sub->id;
                }
            }
            $ledger_id_arr = [];
            $all_ledger = $this->dashboardmodel->getAllLedgerId($group_id_arr);
            foreach ($all_ledger as $ledger) {
                $ledger_id_arr[] = $ledger->id;
            }
            if(count($ledger_id_arr) > 0){
                $dr_sum = $this->dashboardmodel->getDrSumByDate($ledger_id_arr, $finans_start_date, $to_date);
                $cr_sum = $this->dashboardmodel->getCrSumBYDate($ledger_id_arr, $finans_start_date, $to_date);
            }else{
                $dr_sum = 0;
                $cr_sum = 0;
            }


            $closing = $dr_sum['dr_sum'] - $cr_sum['cr_sum'];
            if ($closing > 0) {
                $group_arr['balance_type'] = "Dr";
                $group_arr['balence'] = $closing;
            } else {
                $group_arr['balance_type'] = "Cr";
                $group_arr['balence'] = 0 - $closing;
            }
            $watch_list_group_arr[] = $group_arr;
        }
        $watch_list_arr = array_merge($watch_ledger_list_arr, $watch_list_group_arr);
        $watch_list_arr = $this->shuffle_assoc($watch_list_arr);
        //end group
        $data['watch_list_arr'] = $watch_list_arr;
        //end watch list
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Dashboard');
        $this->layouts->render('admin/home', $data, 'admin');
    }

    public function getAjaxDashboard() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $this->load->helper('core');
            $this->load->helper('financialyear');
            $financial_year = get_financial_year();
            $data = array();
            $data['year_staring_day'] = date("Y-m-d", strtotime(current($financial_year)));
            $data['year_ending_day'] = date("Y-m-t", strtotime(end($financial_year)));
            $from_date = (isset($_POST['staring_day']) && $_POST['staring_day'] != '') ? $_POST['staring_day'] : $data['year_staring_day'];
            $to_date = (isset($_POST['ending_day']) && $_POST['ending_day'] != '') ? $_POST['ending_day'] : $data['year_ending_day'];

            $this->session->unset_userdata('current_ending_day');
            $this->session->set_userdata('current_ending_day', $to_date);

            $overdue_date = date("Y-m-d H:i:s");
            if ($to_date < $overdue_date) {
                $overdue_date = $to_date;
            }

            $finans_start_date = $data['year_staring_day'];
            $data['staring_day'] = $from_date;
            $data['ending_day'] = $to_date;
            $receivable = 0;
            $payable = 0;

            //receivable
            $overdue_amount = 0;
            $sundry_detors_id = 15;
            $sundry_detors_id_arr = [$sundry_detors_id];
            $sub_id_arr = [];
            $sub_group = $this->dashboardmodel->getSubGroup($sundry_detors_id_arr);
            foreach ($sub_group as $sub) {
                $sundry_detors_id_arr[] = $sub->id;
                $sub_id_arr[] = $sub->id;
                $sub_sub_group = $this->dashboardmodel->getSubGroup($sub_id_arr);
                foreach ($sub_sub_group as $sub_sub) {
                    $sundry_detors_id_arr[] = $sub_sub->id;
                }
            }
            $debitor_ledger_id_arr = [];
            $all_ledger = $this->dashboardmodel->getAllLedgerId($sundry_detors_id_arr);
            foreach ($all_ledger as $ledger) {
                $debitor_ledger_id_arr[] = $ledger->id;
            }
            $dr_bill = $this->dashboardmodel->getBillAmount('Dr', $debitor_ledger_id_arr, $finans_start_date, $to_date);
            $cr_bill = $this->dashboardmodel->getBillAmount('Cr', $debitor_ledger_id_arr, $finans_start_date, $to_date);
            $all_overdue_bill = $this->dashboardmodel->getOverdueBill($debitor_ledger_id_arr, $finans_start_date, $to_date, $overdue_date);
            foreach ($all_overdue_bill as $bill) {
                $dr_overdue_bill = $this->dashboardmodel->getOverdueBillAmount('Dr', $bill->bill_name, $debitor_ledger_id_arr, $finans_start_date, $to_date);
                $cr_overdue_bill = $this->dashboardmodel->getOverdueBillAmount('Cr', $bill->bill_name, $debitor_ledger_id_arr, $finans_start_date, $to_date);
                $overdue_amount+=($dr_overdue_bill->total - $cr_overdue_bill->total);
            }
            $data['total_receivable'] = ($dr_bill->total - $cr_bill->total);
            $data['overdue_receivable'] = $overdue_amount;
            $data['current_receivable'] = ($dr_bill->total - $cr_bill->total) - $overdue_amount;
            //end receivable
            //payable
            $overdue_amount = 0;
            $sundry_creditor_id = 23;
            $sundry_creditor_id_arr = [$sundry_creditor_id];
            $sub_id_arr = [];
            $sub_group = $this->dashboardmodel->getSubGroup($sundry_creditor_id_arr);
            foreach ($sub_group as $sub) {
                $sundry_creditor_id_arr[] = $sub->id;
                $sub_id_arr[] = $sub->id;
                $sub_sub_group = $this->dashboardmodel->getSubGroup($sub_id_arr);
                foreach ($sub_sub_group as $sub_sub) {
                    $sundry_creditor_id_arr[] = $sub_sub->id;
                }
            }
            $creditor_ledger_id_arr = [];
            $all_ledger = $this->dashboardmodel->getAllLedgerId($sundry_creditor_id_arr);
            foreach ($all_ledger as $ledger) {
                $creditor_ledger_id_arr[] = $ledger->id;
            }
            $dr_bill = $this->dashboardmodel->getBillAmount('Dr', $creditor_ledger_id_arr, $finans_start_date, $to_date);
            $cr_bill = $this->dashboardmodel->getBillAmount('Cr', $creditor_ledger_id_arr, $finans_start_date, $to_date);
            $all_overdue_bill = $this->dashboardmodel->getOverdueBill($creditor_ledger_id_arr, $finans_start_date, $to_date, $overdue_date);
            foreach ($all_overdue_bill as $bill) {
                $dr_overdue_bill = $this->dashboardmodel->getOverdueBillAmount('Dr', $bill->bill_name, $creditor_ledger_id_arr, $finans_start_date, $to_date);
                $cr_overdue_bill = $this->dashboardmodel->getOverdueBillAmount('Cr', $bill->bill_name, $creditor_ledger_id_arr, $finans_start_date, $to_date);
                $overdue_amount+=($cr_overdue_bill->total - $dr_overdue_bill->total);
            }
            $data['total_payable'] = ($cr_bill->total - $dr_bill->total);
            $data['overdue_payable'] = $overdue_amount;
            $data['current_payable'] = ($cr_bill->total - $dr_bill->total) - $overdue_amount;
            //end payable

            $cash_id = 11;  //for Cash Group
            $bank_id = 10; //for Bank Group
            $sales_id = 37; //sales id
            $purchase_id = 32;

            $sundry_debtors_id = 15;
            $sundry_creditors_id = 23;
            //fand flow
            $current_assets_group_id = 9;
            $current_liability_group_id = 16;
            $fixed_assets_group_id = 6;
            $investment_group_id = 7;
            $fand_flow_group_id_arr = [$current_assets_group_id, $current_liability_group_id, $fixed_assets_group_id, $investment_group_id];
            //get child group againest parent
            $fand_sub_id_arr = [];
            $fand_sub_group = $this->dashboardmodel->getSubGroup($fand_flow_group_id_arr);
            foreach ($fand_sub_group as $sub) {
                $fand_flow_group_id_arr[] = $sub->id;
                $fand_sub_id_arr[] = $sub->id;
                $sub_sub_group = $this->dashboardmodel->getSubGroup($fand_sub_id_arr);
                foreach ($sub_sub_group as $sub_sub) {
                    $fand_flow_group_id_arr[] = $sub_sub->id;
                }
            }
            $fand_ledger_id_arr = [];
            $fand_flow_all_ledger = $this->dashboardmodel->getAllLedgerId($fand_flow_group_id_arr);
            foreach ($fand_flow_all_ledger as $ledger) {
                $fand_ledger_id_arr[] = $ledger->id;
            }
            $fand_flow_opening_transaction = $this->dashboardmodel->getOpeningBalance($fand_ledger_id_arr, $finans_start_date, $to_date);
            $fand_flow_cr_transaction = $this->dashboardmodel->getCrBalance($fand_ledger_id_arr, $from_date, $to_date);
            $fand_flow_dr_transaction = $this->dashboardmodel->getDrBalance($fand_ledger_id_arr, $from_date, $to_date);
            $data['fand_flow_opening'] = $fand_flow_opening_transaction[0]->opening_balance;
            $data['fand_flow_in'] = $fand_flow_dr_transaction[0]->dr_balance;
            $data['fand_flow_out'] = $fand_flow_cr_transaction[0]->cr_balance;
            $net_fand_flow = 0;
            if ($fand_flow_opening_transaction[0]->type == 'Dr') {
                $net_fand_flow = $fand_flow_opening_transaction[0]->opening_balance + $fand_flow_dr_transaction[0]->dr_balance - $fand_flow_cr_transaction[0]->cr_balance;
            } else if ($fand_flow_opening_transaction[0]->type == 'Cr') {
                $net_fand_flow = $fand_flow_opening_transaction[0]->opening_balance + $fand_flow_cr_transaction[0]->cr_balance - $fand_flow_dr_transaction[0]->dr_balance;
            }
            $data['fand_net_flow'] = $net_fand_flow;
//end fand flow
            $group_id_arr = [$cash_id, $bank_id];
            //get child group againest parent
            $sub_id_arr = [];
            $sub_group = $this->dashboardmodel->getSubGroup($group_id_arr);
            foreach ($sub_group as $sub) {
                $group_id_arr[] = $sub->id;
                $sub_id_arr[] = $sub->id;
                $sub_sub_group = $this->dashboardmodel->getSubGroup($sub_id_arr);
                foreach ($sub_sub_group as $sub_sub) {
                    $group_id_arr[] = $sub_sub->id;
                }
            }
            //all ledger within cash and bank group
            $ledger_id_arr = [];
            $all_ledger = $this->dashboardmodel->getAllLedgerId($group_id_arr);
            foreach ($all_ledger as $ledger) {
                $ledger_id_arr[] = $ledger->id;
            }
            $opening_transaction = $this->dashboardmodel->getOpeningBalance($ledger_id_arr, $finans_start_date, $to_date);
            $cr_transaction = $this->dashboardmodel->getCrBalance($ledger_id_arr, $from_date, $to_date);
            $dr_transaction = $this->dashboardmodel->getDrBalance($ledger_id_arr, $from_date, $to_date);
            $data['cash_flow_opening'] = $opening_transaction[0]->opening_balance;
            $data['cash_flow_in'] = $dr_transaction[0]->dr_balance;
            $data['cash_flow_out'] = $cr_transaction[0]->cr_balance;
            $net_flow = 0;
            if ($opening_transaction[0]->type == 'Dr') {
                $net_flow = $opening_transaction[0]->opening_balance + $dr_transaction[0]->dr_balance - $cr_transaction[0]->cr_balance;
            } else if ($opening_transaction[0]->type == 'Cr') {
                $net_flow = $opening_transaction[0]->opening_balance + $cr_transaction[0]->cr_balance - $dr_transaction[0]->dr_balance;
            }
            $data['cash_net_flow'] = $net_flow;
            //end cashflow
            //watch list
            $watch_list_arr = [];
            //ledger
            $watch_ledger_list_arr = [];
            $all_watch_list_ledger = $this->dashboardmodel->getAllWatchlistLedger();
            foreach ($all_watch_list_ledger as $value) {
                $ledger_arr = [];
                $ledger_id_arr = [$value->id];
                $ledger_arr['name'] = $value->ladger_name;
                $ledger_arr['type'] = 'ledger';
                $opening = $this->dashboardmodel->getOpeningBalance($ledger_id_arr, $finans_start_date, $to_date);
                $cr_balance = $this->dashboardmodel->getCrBalance($ledger_id_arr, $finans_start_date, $to_date);
                $dr_balance = $this->dashboardmodel->getDrBalance($ledger_id_arr, $finans_start_date, $to_date);
                $closing_balance = 0;
                if (isset($opening[0]->type) && $opening[0]->type == 'Dr') {
                    $closing_balance = ($opening[0]->opening_balance + $dr_balance[0]->dr_balance) - $cr_balance[0]->cr_balance;
                    if ($closing_balance > 0) {
                        $ledger_arr['balence'] = $closing_balance;
                        $ledger_arr['balance_type'] = "Dr";
                    } else {
                        $ledger_arr['balence'] = 0 - $closing_balance;
                        $ledger_arr['balance_type'] = "Cr";
                    }
                } else {
                    $closing_balance = ($opening[0]->opening_balance + $cr_balance[0]->cr_balance) - $dr_balance[0]->dr_balance;
                    if ($closing_balance > 0) {
                        $ledger_arr['balence'] = $closing_balance;
                        $ledger_arr['balance_type'] = "Cr";
                    } else {
                        $ledger_arr['balence'] = 0 - $closing_balance;
                        $ledger_arr['balance_type'] = "Dr";
                    }
                }
                $watch_ledger_list_arr[] = $ledger_arr;
            }
            //end ledger
            //group
            $all_watch_list_group = $this->dashboardmodel->getAllWatchlistGroup();
            $watch_list_group_arr = [];
            foreach ($all_watch_list_group as $value) {
                $closing = 0;
                $group_arr = [];
                $group_id_arr = [$value->id];
                $group_arr['name'] = $value->group_name;
                $group_arr['type'] = 'group';
                $group_arr['account_type'] = $value->account_type;
                $group_arr['group_ledger_id'] = $value->group_ledger_id;
                //get child group againest parent
                $sub_id_arr = [];
                $sub_group = $this->dashboardmodel->getSubGroup($group_id_arr);
                foreach ($sub_group as $sub) {
                    $group_id_arr[] = $sub->id;
                    $sub_id_arr[] = $sub->id;
                    $sub_sub_group = $this->dashboardmodel->getSubGroup($sub_id_arr);
                    foreach ($sub_sub_group as $sub_sub) {
                        $group_id_arr[] = $sub_sub->id;
                    }
                }
                $ledger_id_arr = [];
                $all_ledger = $this->dashboardmodel->getAllLedgerId($group_id_arr);
                foreach ($all_ledger as $ledger) {
                    $ledger_id_arr[] = $ledger->id;
                }
                if(count($ledger_id_arr)>0){
                    $dr_sum = $this->dashboardmodel->getDrSumByDate($ledger_id_arr, $finans_start_date, $to_date);
                    $cr_sum = $this->dashboardmodel->getCrSumBYDate($ledger_id_arr, $finans_start_date, $to_date);
                }else{
                    $dr_sum = 0;
                    $cr_sum = 0;
                }

                $closing = $dr_sum['dr_sum'] - $cr_sum['cr_sum'];
                if ($closing > 0) {
                    $group_arr['balance_type'] = "Dr";
                    $group_arr['balence'] = $closing;
                } else {
                    $group_arr['balance_type'] = "Cr";
                    $group_arr['balence'] = 0 - $closing;
                }
                $watch_list_group_arr[] = $group_arr;
            }
            $watch_list_arr = array_merge($watch_ledger_list_arr, $watch_list_group_arr);
            $watch_list_arr = $this->shuffle_assoc($watch_list_arr);
            //end group
            $data['watch_list_arr'] = $watch_list_arr;
            //end watch list
            //monthly sales report
            $sales_id = 37; //sales id
            $group_id_arr = [$sales_id];
            //get child group againest parent
            $sub_id_arr = [];
            $sub_group = $this->dashboardmodel->getSubGroup($group_id_arr);
            foreach ($sub_group as $sub) {
                $group_id_arr[] = $sub->id;
                $sub_id_arr[] = $sub->id;
                $sub_sub_group = $this->dashboardmodel->getSubGroup($sub_id_arr);
                foreach ($sub_sub_group as $sub_sub) {
                    $group_id_arr[] = $sub_sub->id;
                }
            }
            //all ledger within cash and bank group
            $ledger_id_arr = [];
            $all_ledger = $this->dashboardmodel->getAllLedgerId($group_id_arr);
            foreach ($all_ledger as $ledger) {
                $ledger_id_arr[] = $ledger->id;
            }


            $financial_year = get_financial_year_by_date_range($from_date, $to_date);
            $monthly_sales_report_arr = [];
            foreach ($financial_year as $val) {
                $month = [];
                $debitSum = $this->dashboardmodel->getDebitSumByDate($val, $ledger_id_arr);
                $creditSum = $this->dashboardmodel->getCreditSumBYDate($val, $ledger_id_arr);
                $month['month'] = date("F", strtotime($val));
                $month['closing'] = ($creditSum['cr_sum'] - $debitSum['dr_sum']);
                $monthly_sales_report_arr[] = $month;
            }

            $data_msg['monthly_sales_report'] = $monthly_sales_report_arr;
            //end monthly salaes report
            //monthly purchase report
            $purchase_id = 32; //purchase id
            $group_id_arr = [$purchase_id];
            //get child group againest parent
            $sub_id_arr = [];
            $sub_group = $this->dashboardmodel->getSubGroup($group_id_arr);
            foreach ($sub_group as $sub) {
                $group_id_arr[] = $sub->id;
                $sub_id_arr[] = $sub->id;
                $sub_sub_group = $this->dashboardmodel->getSubGroup($sub_id_arr);
                foreach ($sub_sub_group as $sub_sub) {
                    $group_id_arr[] = $sub_sub->id;
                }
            }
            //all ledger within cash and bank group
            $ledger_id_arr = [];
            $all_ledger = $this->dashboardmodel->getAllLedgerId($group_id_arr);
            foreach ($all_ledger as $ledger) {
                $ledger_id_arr[] = $ledger->id;
            }

            $financial_year = get_financial_year_by_date_range($from_date, $to_date);
            $monthly_purchase_report_arr = [];
            foreach ($financial_year as $val) {
                $month = [];
                $debitSum = $this->dashboardmodel->getDebitSumByDate($val, $ledger_id_arr);
                $creditSum = $this->dashboardmodel->getCreditSumBYDate($val, $ledger_id_arr);
                $month['month'] = date("F", strtotime($val));
                $month['closing'] = ($debitSum['dr_sum'] - $creditSum['cr_sum']);
                $monthly_purchase_report_arr[] = $month;
            }

            $data_msg['monthly_purchase_report'] = $monthly_purchase_report_arr;
            //end monthly purchase report
            $data_msg['html'] = $this->load->view('admin/ajax_dashboard', $data, TRUE);
            $data_msg['res'] = 'success';
            echo json_encode($data_msg);
            exit;
        }
    }

    public function getAjaxSalesDetails() {
        $data = array();
        $this->load->helper('financialyear');
        if ($this->input->is_ajax_request()) {
            $sales_id = 37; //sales id
            $group_id_arr = [$sales_id];
            //get child group againest parent
            $sub_id_arr = [];
            $sub_group = $this->dashboardmodel->getSubGroup($group_id_arr);
            foreach ($sub_group as $sub) {
                $group_id_arr[] = $sub->id;
                $sub_id_arr[] = $sub->id;
                $sub_sub_group = $this->dashboardmodel->getSubGroup($sub_id_arr);
                foreach ($sub_sub_group as $sub_sub) {
                    $group_id_arr[] = $sub_sub->id;
                }
            }
            //all ledger within cash and bank group
            $ledger_id_arr = [];
            $all_ledger = $this->dashboardmodel->getAllLedgerId($group_id_arr);
            foreach ($all_ledger as $ledger) {
                $ledger_id_arr[] = $ledger->id;
            }

            $financial_year = get_financial_year();
            $data['year_staring_day'] = date("Y-m-d", strtotime(current($financial_year)));
            $data['year_ending_day'] = date("Y-m-t", strtotime(end($financial_year)));
            $from_date = (isset($_POST['staring_day']) && $_POST['staring_day'] != '') ? $_POST['staring_day'] : $data['year_staring_day'];
            $to_date = (isset($_POST['ending_day']) && $_POST['ending_day'] != '') ? $_POST['ending_day'] : $data['year_ending_day'];
            $data['staring_day'] = $from_date;
            $data['ending_day'] = $to_date;
            $financial_year = get_financial_year_by_date_range($from_date, $to_date);
            $monthly_report_arr = [];
            foreach ($financial_year as $val) {
                $month = [];
                $debitSum = $this->dashboardmodel->getDebitSumByDate($val, $ledger_id_arr);
                $creditSum = $this->dashboardmodel->getCreditSumBYDate($val, $ledger_id_arr);
                $month['month'] = date("F", strtotime($val));
                $month['closing'] = ($creditSum['cr_sum'] - $debitSum['dr_sum']);
                $monthly_report_arr[] = $month;
            }

            $data['monthly_report'] = $monthly_report_arr;
            echo json_encode($monthly_report_arr);
        }
    }

    public function getAjaxPurchaseDetails() {
        $data = array();
        $this->load->helper('financialyear');
        if ($this->input->is_ajax_request()) {
            $sales_id = 32; //sales id
            $group_id_arr = [$sales_id];
            //get child group againest parent
            $sub_id_arr = [];
            $sub_group = $this->dashboardmodel->getSubGroup($group_id_arr);
            foreach ($sub_group as $sub) {
                $group_id_arr[] = $sub->id;
                $sub_id_arr[] = $sub->id;
                $sub_sub_group = $this->dashboardmodel->getSubGroup($sub_id_arr);
                foreach ($sub_sub_group as $sub_sub) {
                    $group_id_arr[] = $sub_sub->id;
                }
            }
            //all ledger within cash and bank group
            $ledger_id_arr = [];
            $all_ledger = $this->dashboardmodel->getAllLedgerId($group_id_arr);
            foreach ($all_ledger as $ledger) {
                $ledger_id_arr[] = $ledger->id;
            }

            $financial_year = get_financial_year();
            $data['year_staring_day'] = date("Y-m-d", strtotime(current($financial_year)));
            $data['year_ending_day'] = date("Y-m-t", strtotime(end($financial_year)));
            $from_date = (isset($_POST['staring_day']) && $_POST['staring_day'] != '') ? $_POST['staring_day'] : $data['year_staring_day'];
            $to_date = (isset($_POST['ending_day']) && $_POST['ending_day'] != '') ? $_POST['ending_day'] : $data['year_ending_day'];
            $data['staring_day'] = $from_date;
            $data['ending_day'] = $to_date;
            $financial_year = get_financial_year_by_date_range($from_date, $to_date);
            $monthly_report_arr = [];
            foreach ($financial_year as $val) {
                $month = [];
                $debitSum = $this->dashboardmodel->getDebitSumByDate($val, $ledger_id_arr);
                $creditSum = $this->dashboardmodel->getCreditSumBYDate($val, $ledger_id_arr);
                $month['month'] = date("F", strtotime($val));
                $month['closing'] = ($debitSum['dr_sum'] - $creditSum['cr_sum']);
                $monthly_report_arr[] = $month;
            }

            $data['monthly_report'] = $monthly_report_arr;
            echo json_encode($monthly_report_arr);
        }
    }

    public function getTotalValueForFirstLevel($total_balance_arr) {
        $total_balance_val = 0;
        if (!empty($total_balance_arr)) {
            foreach ($total_balance_arr as $total_balance) {
                if ($total_balance['level'] == 'level_first') {
                    if ($total_balance['account_type'] == 'Cr') {
                        $total_balance_val += $total_balance['amount'];
                    } else {
                        $total_balance_val -= $total_balance['amount'];
                    }
                }
            }
        }
        return $total_balance_val;
    }

    public function getCalculatedValue($trial_balance_arr = array(), $site) {
        $finalArray = array();
        if (!empty($trial_balance_arr)) {
            $closing_balance = 0;
            $total_debit_closing_balance = 0;
            $total_credit_closing_balance = 0;
            $finalArray = array();
            $i = 0;
            foreach ($trial_balance_arr as $trial_balance) {
                if ($trial_balance['account_type'] == 'Dr') {
                    $closing_balance = str_replace('-', '', $trial_balance['opening_balance']) + $trial_balance['dr_balance'] - $trial_balance['cr_balance'];
                    if ($closing_balance > 0) {
                        $account_type = 'Dr';
                        if ($trial_balance['type'] == 'ledger') {
                            $total_debit_closing_balance += $closing_balance;
                        }
                    } else {
                        $account_type = 'Cr';
                        if ($trial_balance['type'] == 'ledger') {
                            $total_credit_closing_balance += str_replace('-', '', sprintf('%0.2f', $closing_balance));
                        }
                    }
                }

                if ($trial_balance['account_type'] == 'Cr') {
                    $closing_balance = str_replace('-', '', $trial_balance['opening_balance']) + $trial_balance['cr_balance'] - $trial_balance['dr_balance'];
                    if ($closing_balance > 0) {
                        $account_type = 'Cr';
                        if ($trial_balance['type'] == 'ledger') {
                            $total_credit_closing_balance += str_replace('-', '', sprintf('%0.2f', $closing_balance));
                        }
                    } else {
                        $account_type = 'Dr';
                        if ($trial_balance['type'] == 'ledger') {
                            $total_debit_closing_balance += $closing_balance;
                        }
                    }
                }
                $finalArray[$i]['site'] = $site;
                $finalArray[$i]['type'] = $trial_balance['type'];
                $finalArray[$i]['level'] = $trial_balance['level'];
                $finalArray[$i]['id'] = $trial_balance['id'];
                $finalArray[$i]['name'] = $trial_balance['name'];
                $finalArray[$i]['code'] = $trial_balance['code'];
                $finalArray[$i]['parent_id'] = $trial_balance['parent_id'];
                $finalArray[$i]['account_type'] = $account_type;
                $finalArray[$i]['opening_balance'] = str_replace('-', '', $trial_balance['opening_balance']);
                $finalArray[$i]['amount'] = str_replace('-', '', $closing_balance);
                $i++;
            }
        }
        return $finalArray;
    }

    /**
     * This is the functionality for
     * admin login checking
     *

     *
     */
    public function checklogin() {

        $data_msg = array();
        $this->db2 = $this->load->database('db2', TRUE);
        $this->load->model('admin/companymodel');
        $this->load->model('admin/authmodel');
        $callback = 'accountcallback';
        if (1) {
            // $value = trim($_GET['value']);
            // $val_arr = explode('_', $value);
            $callback = 'ok';
            $id = 114;
            $user_id = 10;
            // $callback = $_GET['accountcallback'];
            // $id = $val_arr[0];
            // $user_id = $val_arr[1];
            $company = $this->companymodel->getCompanyDetails($id, $user_id);
            // print_r($company);die();
            //load database config
            $this->db = $this->load->database('default', TRUE);
            $user = $this->authmodel->getUser($company->user_id, $company->password);

            if ($user) {
                $res = $this->authmodel->CompanyLogin($user, $id);
                if ($res) {
                    $branch = $this->authmodel->getBranch($id);
                    $isInventory = $this->authmodel->getInventoryStatus();
                    if (count($branch) == 1) {
                        $branch_id = $branch[0]->id;
                        $company_type = $branch[0]->company_type;
                        $is_inventory = $isInventory->is_inventory;

                        $this->session->set_userdata('branch_id', $branch_id);
                        $this->session->set_userdata('company_type', $company_type);
                        $this->session->set_userdata('is_inventory', $is_inventory);

                        $selected_branch = [$branch_id];
                        $this->session->set_userdata('selected_branch', $selected_branch);
                        $selected_branch_str = '';
                        foreach ($this->session->userdata('selected_branch') as $b) {
                            $selected_branch_str.="'" . $b . "'" . ',';
                        }
                        $selected_branch_str = rtrim($selected_branch_str, ',');
                        $this->session->set_userdata('selected_branch_str', $selected_branch_str);
                        // $data_msg['url'] = site_url('admin/dashboard');
                        $redirect = base_url('admin');
                        redirect($redirect);
                    } else {
                        $data_msg['url'] = site_url('admin/branch');
                    }

                    $this->companymodel->updateCompanyLastLogin($id);

                    $data_msg['res'] = 'success';
                    $data_msg['message'] = "Welcome to your account dashboard.";
                } else {
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = "You do not have access for this company.";
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = "You do not have access for this company.";
            }
        }

        echo $callback . '(' . json_encode($data_msg) . ')';
    }

    public function checklogin_bk_25_09_2017() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $checkon = $this->input->post('checkon');
        admin_login($email, $password);
    }

    /**
     * This functionality is for
     * checking the mail entered is
     * authenticated or not
     *

     *
     */
    public function checkemail() {
        $email = $this->input->post('email');
        $this->load->model('admin/authmodel');
        $emailchk = $this->authmodel->emailcheck($email);
        if (empty($emailchk)) {
            $this->session->set_flashdata('errormessage', 'Invalid email entered');
        } else {
            $this->session->set_flashdata('successmessage', 'An activation link has been sent to your Email');
        }

        $redirect = site_url('admin/forget-password');
        redirect($redirect);
    }

    /**
     * This is the functionality
     * for making admin to enter
     * new password after sending a request
     * for forget password
     *
     */
    public function newpass($activationcode) {
        $data['pagetype'] = "New Password";
        $data['activationcode'] = $activationcode;
        //$this->load->view('admin/newpass',$data);

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | New Password');
        $this->layouts->render('admin/newpass', $data, 'admin_login');
    }

    /**
     * This is the functionality for
     * updating the new password for the admin
     * into the database.
     *
     *
     */
    public function updatepass() {
        $newpass = $this->input->post('password');
        $confpass = $this->input->post('confirm_password');
        $activationcode = $this->input->post('activationcode');
        if ($newpass != $confpass) {
            $this->session->set_flashdata('errormessage', 'Password and confirm Password not matches');
            $redirect = site_url('admin/new-password.html/' . $activationcode);
            redirect($redirect);
        } else {
            $this->load->model('admin/authmodel');
            $chk = $this->authmodel->passwdupd($newpass, $activationcode);
            if (!empty($chk)) {
                $this->session->set_flashdata('successmessage', 'Password updated successfully');
                $redirect = base_url('admin');
                redirect($redirect);
            } else {
                $this->session->set_flashdata('errormessage', 'Wrong activation code');
                $redirect = site_url('admin/new-password.html/' . $activationcode);
                redirect($redirect);
            }
        }
    }

    /**
     * This is the functionality
     * for viewing of
     * the admin profile
     *
     *
     */
    public function profile() {
        admin_authenticate();
        $this->load->model('admin/adminsmodel');

        //$data['pagetype']="Profile";
        //$this->load->view('admin/admin-profile',$data);
        /* echo $this->session->userdata('admin_uid');
          exit(); */

        if (isset($_POST['profile_update'])){
            $user = array();
            $profile = $this->adminsmodel->getCurrentUserProfile();
            if(!empty($_FILES['user_image']['name'])){

                if(!file_exists(dirname( FCPATH ).'/login-saas/assets/upload/user_profile')){
                    mkdir(dirname( FCPATH ).'/login-saas/assets/upload/user_profile',0777);
                }else{
                    chmod(dirname( FCPATH ).'/login-saas/assets/upload/user_profile', 0777);
                }


                $config['upload_path'] = dirname( FCPATH ).'/login-saas/assets/upload/user_profile';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = 10000;
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('user_image')) {
                    $user['image'] = "";
                    $this->session->set_flashdata('profile-err', $this->upload->display_errors());
                } else {
                    $file = $this->upload->data();
                    $user['image'] = $file['file_name'];
                    if($profile->image != ""){
                        unlink(dirname( FCPATH )."/login-saas/assets/upload/user_profile/". $profile->image);
                    }
                }
            }

            $user['fname'] = $this->input->post('fname');
            $user['lname'] = $this->input->post('lname');
            $user['phone'] = $this->input->post('phone');
            $user['modified_date'] = date('Y-m-d H:i:s', time());

            $this->adminsmodel->updateUserProfile($user, $profile->sass_user_id);
            $this->session->set_flashdata('profile-success', "Profile update successful");
            redirect(base_url().'admin/profile');
        }

        $data['profile'] = $this->adminsmodel->getCurrentUserProfile();
        $this->session->unset_userdata('admin_detail');
        $this->session->set_userdata('admin_detail', json_encode($data['profile']));
        $data['logs'] = $this->adminsmodel->userlogs();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Profile');
        $this->layouts->render('admin/admin-profile', $data, 'admin');
    }

    /**
     * This is the functionality for
     * updating admin profile
     *
     *
     */
    public function profupdate() {
        $this->load->model('admin/adminsmodel');
        $fname = $this->input->post('fname');
        $lname = $this->input->post('lname');
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        if (!empty($_FILES['profile_pic']['name'])) {
            $profile_pic = json_encode($_FILES);
        } else {
            $profile_pic = "";
        }
        $returnval = $this->adminsmodel->updateadmin($fname, $lname, $email, $profile_pic, $username);
        $returnval = (array) json_decode($returnval);
        if ($returnval['error'] == 1) {
            $errormsg = $returnval['errormsg'];
            $this->session->set_flashdata('errormessage', $errormsg);
        }
        if ($returnval['success'] == 1) {
            $this->session->set_flashdata('successmessage', 'Profile updated successfully');
        }
        $redirect = site_url('admin/profile');
        redirect($redirect);
    }

    /**
     * Functionality for
     * admin logout
     *
     *
     */
    public function logout() {
        $this->session->unset_userdata('inventory_method'); // somnath - unset inventory method after branch logoout
        admin_authenticate();
        $this->session->set_flashdata('successmessage', 'You have logged out successfully');
        admin_logout();
    }

    public function admin_logout_frm_saas() {
        $this->session->set_userdata('admin_uid', '');
        $this->session->set_userdata('admin_detail', '');
        $this->session->set_userdata('admin_logid', '');
        delete_cookie('admin_uid');
        delete_cookie('admin_detail');


        /*
          $this->load->model('admin/authmodel');
          $admin_uid = $this->session->userdata['admin_uid'];
          $fl = $this->authmodel->checklogout($admin_uid);
          if (!empty($fl)) {
          //$this->session->set_flashdata('successmessage', 'You have logged out successfully');
          //$redirect = base_url('admin');
          //redirect($redirect);
          }

         */
    }

    /**
     * Functionality to
     * view site when offline
     *
     *
     */
    public function siteoffline() {
        $this->load->view('admin/websitedown');
    }

    /**
     * Functionality for
     * listing of
     * site settings
     *
     *
     */
    public function sitesettings() {
        admin_authenticate();
        $permission = admin_users_permission('V', 'site-settings', $rtype = TRUE);
        if ($permission) {
            //$data['pagetype']="Site Settings";
            $this->load->model('admin/sitesettingsmodel');
            $data['sitesettings'] = $this->sitesettingsmodel->loadsitesettings();
            //$this->load->view('admin/sitesettings',$data);


            $getsitename = getsitename();
            $this->layouts->set_title($getsitename . ' |General Settings');
            $this->layouts->render('admin/sitesettings', $data, 'admin');
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    /**
     * Functionality for
     * update site settings
     *
     *
     */
    public function siteupdate() {
        $this->load->model('admin/sitesettingsmodel');

        $flgstore = $this->sitesettingsmodel->updstoresettings();

        $flg = $this->sitesettingsmodel->updsitesettings();
        if (!empty($flg)) {
            $this->session->set_flashdata('successmessage', 'Settings updated successfully');
        } else {
            $this->session->set_flashdata('errormessage', 'Oops! Something went wrong. Please try again');
        }
        $redirect = site_url('admin/sitesettings');
        redirect($redirect);
    }

    function updatesettings() {

        /* echo "<pre>";
          print_r($_FILES);
          exit; */

        $this->load->model('admin/settingsmodel', 'sm');
        $url = $this->input->post('redirect_url');
        unset($_POST['redirect_url']);
        if ($this->input->post()) {
            $post = $this->input->post();
            foreach ($post as $key => $value) {
                $where['name'] = $key;
                $update['value'] = $value;
                $update['modified_date'] = date('Y-m-d H:i:s');
                $this->sm->updateSetting($update, $where);
            }
        }


        if ($url == "admin/sitesettings") {

            $flag_sitelogo = $this->sm->uploadGeneralSettingsLogo($_FILES['sitelogo'], "sitelogo");
            $flag_invoicelogo = $this->sm->uploadGeneralSettingsLogo($_FILES['invoicelogo'], "invoicelogo");
            $flag_favicon = $this->sm->uploadGeneralSettingsLogo($_FILES['favicon'], "favicon", "ico");

            if ($flag_favicon == 'exterror') {
                $this->session->set_flashdata('errormessage', "Fav Icon file Extension should be .ICO format");
            } else {
                $this->session->set_flashdata('successmessage', 'Settings updated successfully');
            }
        } else {
            $this->session->set_flashdata('successmessage', 'Settings updated successfully');
        }
        $redirect = site_url($url);
        redirect($redirect);
    }

    /**
     * Functionality for
     * change password in the
     * admin section.
     *
     *
     */
    public function changePassword() {
        admin_authenticate();

        $this->load->helper('form', 'url');

        $admin_id = $this->session->userdata('admin_uid');
        $this->load->model('admin/authmodel');

        $details = $this->authmodel->checkAdmin($admin_id);

        // $data_msg = [];
        $data = array();
        if ($details) {
            $data['details'] = $details;
        }

        if (!empty($_POST)) {

            $data_msg['res'] = 'error';
            $newpass = $this->input->post('password');
            $confpass = $this->input->post('passconf');

            $oldpassword = $this->input->post('oldpassword');
            $oldpass_actual = $this->input->post('oldpass_actual');
            // $res = sha1($oldpassword);
            $res = md5($oldpassword);


            if (empty($newpass) || empty($confpass) || empty($oldpassword)) {

                $this->session->set_flashdata('errormessage', 'Old password ,New Password and confirm Password are required');
                // $data_msg['msg'] = 'Old password ,New Password and confirm Password are required';
                $redirect = site_url('admin/profile');
                // $redirect = site_url('admin/change-password');
                redirect($redirect);
            } else if ($oldpass_actual != $res) {


                $this->session->set_flashdata('errormessage', 'Old password does not match');
                // $data_msg['msg'] = 'Old password is not match';
                $redirect = site_url('admin/profile');
                // $redirect = site_url('admin/change-password');
                redirect($redirect);
            } else if ($newpass != $confpass) {
                $this->session->set_flashdata('errormessage', 'New Password and confirm Password not matches');
                // $data_msg['msg'] = 'New Password and confirm Password not matches';
                $redirect = site_url('admin/profile');
                // $redirect = site_url('admin/change-password');
                redirect($redirect);
            } else {


                $this->load->model('admin/authmodel');
                $uid = $this->session->userdata('admin_uid');
                $chk = $this->authmodel->changePwd($newpass, $uid);
                if (!empty($chk)) {

                    $this->pwdupdate_dbs($newpass);
                    $this->session->set_flashdata('successmessage', 'Password updated successfully');
                    $redirect = site_url('admin/profile');
                    // $redirect = site_url('admin/change-password');
                    redirect($redirect);
                } else {
                    $this->session->set_flashdata('errormessage', '');
                    $redirect = site_url('admin/profile');
                    // $redirect = site_url('admin/change-password');
                    redirect($redirect);
                }
            }
        } else {
            $this->layouts->set_title(getsitename() . ' | New Password');
            // $this->layouts->render('admin/change-password', $data, 'admin');
            redirect('admin/profile');
        }
        // echo json_encode($data_msg);
    }

    function pwdupdate_dbs($newpass) {
        $this->load->model('admin/authmodel');
        $admin_detail = $this->session->userdata('admin_detail');
        $admin_detail = json_decode($admin_detail);
        $email = $admin_detail->email;
        $data['email'] = $email;
        $data['newpass'] = $newpass;
        //echo '<pre>';print_r($data);exit;
        $this->authmodel->pwdupdate_dbs($data);
    }

    /**
     * Functionality for
     * adding menu from the
     * menu management
     *
     *
     */
    public function addMenu() {
        $this->load->model('admin/menumodel', 'menus');
        if ($this->input->post()) {

            //$flg = $this->menus->addmenu($menu_arr,$group,$pid);
            $datasub['group_menu'] = $this->input->post('m_group');
            $datasub['parentid'] = $this->input->post('m_parent');
            $datasub['icon'] = $this->input->post('m_icon');
            $datasub['url'] = $this->input->post('m_url');
            $datasub['label'] = $this->input->post('m_name');
            $datasub['active'] = $this->input->post('m_active');
            $datasub['status'] = 1;
            if ($this->input->post('m_parent') == "0") {
                $datasub['menu_order'] = 0;
            } else {
                $pid = $this->input->post('m_parent');
                $result = $this->menus->loadAllMenu($pid);
                $datasub['menu_order'] = count($result);
            }
            $datasub['created_date'] = date("Y-m-d H:i:s");
            $resultsub = $this->menus->modifyMenu($datasub);

            if (!empty($resultsub)) {
                $this->session->set_flashdata('successmessage', 'Menu added successfully');
            } else {
                $this->session->set_flashdata('errormessage', 'Oops! something went wrong. Please try again');
            }
            $redirect = site_url('admin/menu-manager');
            redirect($redirect);
        }
        $data = array();
        $data['menues'] = $this->menus->loadAllMenu();
        $data['allgroups'] = $this->menus->getAllGroup();

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Site Settings');
        $this->layouts->render('admin/addmenu', $data, 'admin');
    }

    /**
     * Functionality for
     * listing of the menu
     * in menu management
     *
     * <p>
     *   @author : Riaz Ali Laskar
     *   @param : None
     * </p>
     *
     */
    public function listMenu() {

        $this->load->model('admin/menumodel', 'menus');
        $data['menues'] = $this->menus->loadAllMenu();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' |Site Settings');
        $this->layouts->render('admin/menulist', $data, 'admin');
    }

    /**
     * Functionality for modification
     * of the menu for menu management
     *
     * <p>
     *   @author : Riaz Ali Laskar
     *   @param : Menu ID
     * </p>
     *
     */
    public function editmenu($id) {
        $this->load->model('admin/menumodel', 'menus');

        if ($this->input->post()) {
            $name = $this->input->post('m_name');
            $url = $this->input->post('m_url');
            $icon = $this->input->post('m_icon');
            $active = $this->input->post('m_active');
            $pid = $this->input->post('m_pid');
            $status = $this->input->post('m_status');


            $id = $this->input->post('id');

            $menu_arr = array(
                'group_menu' => $this->input->post('m_group'),
                'icon' => $icon,
                'url' => $url,
                'label' => $name,
                'active' => $active
            );

            $flag = $this->menus->modifyMenu($menu_arr, $id);
            if (!empty($flag)) {
                $this->session->set_flashdata('successmessage', 'Menu updated successfully');
            } else {
                $this->session->set_flashdata('errormessage', 'Oops! something went wrong. Please try again');
            }
            $redirect = site_url('admin/menu-manager');
            redirect($redirect);
        }

        $id = base64_decode(urldecode($id));
        $data['menu'] = $this->menus->getmenuitem($id);
        $data['id'] = $id;
        $data['allgroups'] = $this->menus->getAllGroup();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' |Site Settings');
        $this->layouts->render('admin/editmenu', $data, 'admin');
    }

    /**
     * Functionality for
     * activate or deactivate
     * menu in the menu management
     *
     * <p>
     *   @author : Riaz Ali Laskar
     *   @param : Menu ID
     * </p>
     *
     */
    public function menustatus($id) {
        $id = base64_decode(urldecode($id));
        $m = get_menu_from_setting('admin_sidebar');
        $menu = &$m;
        if (!empty($menu)) {
            $c = $menu->$id;

            if ($c->status == 0) {
                $s = 1;
            } else {
                $s = 0;
            }

            $c->status = $s;
        }

        $flag = $this->ss->updatemenuitem($id, $c);
        if (!empty($flag)) {
            $this->session->set_flashdata('successmessage', 'Status changed successfully');
        } else {
            $this->session->set_flashdata('errormessage', 'Oops! something went wrong. Please try again');
        }
        $redirect = site_url('admin/menu-manager');
        redirect($redirect);
    }

    /**
     * Functionality for
     * deletion of the menus
     * from the menu management
     *
     * <p>
     *   @author : Riaz Ali Laskar
     *   @param : Menu ID
     * </p>
     *
     */
    public function deletemenu($id) {
        $id = base64_decode(urldecode($id));
        $m = get_menu_from_setting('admin_sidebar');
        $newmenu = array();
        $menu = &$m;
        if (!empty($menu)) {
            foreach ($menu as $key => $value) {
                if ($key != $id)
                    $newmenu[] = $value;
            }
        }

        $flag = $this->ss->updatemenu($newmenu, 'admin_sidebar');
        if (!empty($flag)) {
            $this->session->set_flashdata('successmessage', 'Menu deleted successfully');
        } else {
            $this->session->set_flashdata('errormessage', 'Oops! something went wrong. Please try again');
        }
        $redirect = site_url('admin/menu-manager');
        redirect($redirect);
    }

    /**
     * Functionality for
     * the implementation of
     * plugin management listing
     *
     * <p>
     *  @author : Dibya Mitra
     *  @param : None
     * </p>
     *
     */
    public function plugins() {
        $this->load->model('admin/pluginmodel');
        $plugindet = $this->pluginmodel->loadplugin();
        $data['plugindet'] = $plugindet;
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Plugin Managers');
        $this->layouts->render('admin/pluginlist', $data, 'admin');
    }

    /**
     * Functionality for
     * uploading the created
     * or downloaded plugin zip
     * and installing
     *
     * <p>
     *  @author : Dibya Mitra
     *  @param : None
     * </p>
     *
     */
    public function pluginupload() {
        $this->load->model('admin/pluginmodel');
        $pluginfile = $_FILES['pluginfile']['name'];
        $temppluginfile = $_FILES['pluginfile']['tmp_name'];
        $pos = strpos('aa' . $pluginfile, 'plugin_');
        $arr = explode('.', $pluginfile);
        $ext = end($arr);
        if ($ext == "zip") {
            if (!empty($pos)) {
                $pluginname = str_replace('.zip', '', str_replace('plugin_', '', $pluginfile));
                $zip = new ZipArchive;
                if ($zip->open($temppluginfile) === TRUE) {
                    $zip->extractTo('application/modules/');
                    $zip->close();
                    rename('application/modules/plugin_' . $pluginname, 'application/modules/' . $pluginname);
                    exec("find application/modules/" . $pluginname . " -type d -exec chmod 0777 {} +");
                    exec("find application/modules/" . $pluginname . " -type f -exec chmod 0777 {} +");

                    $fp = fopen('application/modules/' . $pluginname . '/security/name.json', 'r');
                    $namejson = fgets($fp);

                    $namearr = json_decode($namejson);

                    $modname = $namearr->name;
                    $moddescription = $namearr->description;
                    $modversion = $namearr->version;
                    $modauthor = $namearr->author;

                    $flg = $this->pluginmodel->installplugin($modname, $moddescription, $pluginname, $modversion, $modauthor);
                    if (!empty($flg)) {
                        $this->session->set_flashdata('successmessage', 'Plugin installed successfully');
                    } else {
                        $this->session->set_flashdata('errormessage', 'Error installing the plugin. Please check if already this name exists');
                    }
                }
            } else {
                $this->session->set_flashdata('errormessage', 'Zip name will have the name format plugin_EXAMPLE');
            }
        } else {
            $this->session->set_flashdata('errormessage', 'Plugin will be in zip format');
        }
        $redirect = site_url('admin/plugin-manager');
        redirect($redirect);
    }

    /**
     * Functionality for
     * deleting the plugin
     * only if the plugin
     * is in deactive mode
     *
     * <p>
     *  @author : Dibya Mitra
     *  @param : None
     * </p>
     *
     */
    public function plugindelete($pluginid) {
        $this->load->model('admin/pluginmodel');
        $flg = $this->pluginmodel->delplugin($pluginid);
        if (!empty($flg)) {
            $this->session->set_flashdata('successmessage', 'Plugin installed successfully');
        } else {
            $this->session->set_flashdata('errormessage', 'Error installing the plugin. Please check if already this name exists');
        }
        $redirect = site_url('admin/plugin-manager');
        redirect($redirect);
    }

    /**
     * Functionality for
     * activating or deactivating
     * the plugin
     *
     * <p>
     *  @author : Dibya Mitra
     *  @param : None
     * </p>
     *
     */
    public function pluginstat($pluginid, $status) {
        $this->load->model('admin/pluginmodel');
        if ($status == 1) {
            $flg = $this->pluginmodel->deactiveplugin($pluginid);
            if (!empty($flg)) {
                $this->session->set_flashdata('successmessage', 'Plugin deactivated successfully');
            } else {
                $this->session->set_flashdata('errormessage', 'Error in activating the plugin');
            }
        } else {
            $flg = $this->pluginmodel->activeplugin($pluginid);
            if (!empty($flg)) {
                $this->session->set_flashdata('successmessage', 'Plugin activated successfully');
            } else {
                $this->session->set_flashdata('errormessage', 'Error in activating the plugin');
            }
        }
        $redirect = site_url('admin/plugin-manager');
        redirect($redirect);
    }

    /* Menu Module */

    public function menucreator() {
        $this->load->model('admin/sitesettingsmodel', 'ss');
        $result = $this->ss->getallmenu();
        $arr = json_decode($result[0]->menu);

        $this->load->model('admin/menumodel', 'menus');

        if (!empty($arr)) {
            foreach ($arr as $items) {

                $data = array();

                $data['group_menu'] = 1;
                $data['parentid'] = 0;
                $data['slug'] = $items->active;
                $data['icon'] = $items->icon;
                $data['url'] = $items->url;
                $data['label'] = $items->label;
                $data['active'] = $items->active;
                $data['status'] = 1;
                $data['menu_order'] = 0;
                $data['created_date'] = date("Y-m-d H:i:s");

                $resultroot = $this->menus->modifyMenu($data);



                if (!empty($items->submenu)) {  // Sub Menu
                    $i = 0;
                    foreach ($items->submenu as $key => $item) {
                        $i++;
                        $datasub = array();
                        $datasub['group_menu'] = 1;
                        $datasub['slug'] = $items->active;
                        $datasub['parentid'] = $resultroot;
                        $datasub['icon'] = $item->icon;
                        $datasub['url'] = $item->url;
                        $datasub['label'] = $item->label;
                        $datasub['active'] = $item->active;
                        $datasub['status'] = 1;
                        $datasub['menu_order'] = $i;
                        $datasub['created_date'] = date("Y-m-d H:i:s");
                        $resultsub = $this->menus->modifyMenu($datasub);
                    }
                }
            }
        }
    }

    public function sortmenu() {
        $this->load->model('admin/menumodel', 'menus');

        if (!empty($_POST['components'])) {
            $list = explode("|", $_POST['components']);
            $ids = explode("|", $_POST['ids']);

            for ($i = 0; $i < count($ids); $i++) {
                $pos = array_search($ids[$i], $list);
                $pos+=1;
                $result = $this->menus->modifyMenuorder($pos, $ids[$i]);
            }
        }
    }

    /* Menu Module */

    public function frontmenu() {
        $data = array();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Front Menu Managers');
        $this->layouts->render('admin/frontmenu', $data, 'admin');
    }

    public function frontmenuedit() {
        $data = array();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Front Menu Managers');
        $this->layouts->render('admin/frontmenuedit', $data, 'admin');
    }

    public function pnf() {
        $data = array();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | 404 Page not found');
        $this->layouts->render('admin/pnf', $data, 'admin');
    }

    public function get_db_tbl_prefix() {
        return tablename("");
    }

    public function add_branch_ajax() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('company_name', 'Company Name', 'required');
            $this->form_validation->set_rules('street_address', 'Address', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('country_id', 'Country', 'required');
            $this->form_validation->set_rules('state_id', 'State', 'required');
            $this->form_validation->set_rules('city_name', 'City', 'required');
            $this->form_validation->set_rules('mobile', 'Mobile No', 'required');
            $this->form_validation->set_rules('zip_code', 'Zip-code', 'required');
            if ($this->input->post('company_type') != 3) {
                $this->form_validation->set_rules('pan', 'PAN', 'required|min_length[10]');
                $this->form_validation->set_rules('gst', 'GSTN No', 'required|min_length[15]');
            }
            if ($this->form_validation->run() === TRUE) {
                $this->db->trans_begin();
                if (isset($_FILES['company_logo'])) {
                    $file = $_FILES['company_logo'];
                    if (isset($_FILES['company_logo']) && isset($file['name']) && $file['name'] != '') {
                        $imagename = $file['name'];
                        $imagearr = explode('.', $imagename);
                        $ext = end($imagearr);
                        $newimage = time() . rand() . "." . $ext;
                        if ($ext == "jpg" or $ext == "jpeg" or $ext == "png" or $ext == "bmp") {
                            $this->load->library('image_lib');

                            $config['image_library'] = 'gd2';
                            $config['source_image'] = $file['tmp_name'];
                            $config['new_image'] = "assets/uploads/thumbs/" . $newimage;
                            $config['create_thumb'] = FALSE;
                            $config['maintain_ratio'] = FALSE;
                            $config['width'] = 200;
                            $config['height'] = 200;

                            $this->image_lib->clear();
                            $this->image_lib->initialize($config);
                            $this->image_lib->resize();


                            $config1['image_library'] = 'gd2';
                            $config1['source_image'] = $file['tmp_name'];
                            $config1['new_image'] = "assets/uploads/" . $newimage;
                            $config1['create_thumb'] = FALSE;
                            $config1['maintain_ratio'] = FALSE;

                            $this->image_lib->clear();
                            $this->image_lib->initialize($config1);
                            $this->image_lib->resize();
                            $data['logo'] = $newimage;
                        }
                    }
                }
                $data['company_id'] = $this->session->userdata('company_id');
                ;
                $data['company_name'] = $this->input->post('company_name');
                if (!$this->input->post('mailing_name')) {
                    $data['mailing_name'] = $this->input->post('company_name');
                } else {
                    $data['mailing_name'] = $this->input->post('mailing_name');
                }
                $data['email'] = $this->input->post('email');
                $data['country_id'] = $this->input->post('country_id');
                $data['state_id'] = $this->input->post('state_id');
                $data['appt_number'] = $this->input->post('appt_number');
                $data['street_address'] = $this->input->post('street_address');
                $data['city_name'] = $this->input->post('city_name');
                $data['zip_code'] = $this->input->post('zip_code');
                $data['telephone'] = $this->input->post('telephone');
                $data['mobile'] = $this->input->post('mobile');
                $data['company_type'] = $this->input->post('company_type');
                $data['gst'] = $this->input->post('gst');
                $data['pan'] = $this->input->post('pan');
                $user_id = $this->session->userdata('admin_uid');
                $branch_id = $this->dashboardmodel->saveData($data);
                $data_branch_user = array('user_id' => $user_id, 'company_id' => $branch_id, 'status' => 1, 'created_at' => date("Y-m-d H:i:s"));
                $res = $this->dashboardmodel->addBranchUser($data_branch_user);
                $str = 'a:73:{i:2;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:84;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:85;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:86;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:87;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:194;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:195;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:196;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:197;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:199;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:200;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:201;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:202;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:203;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:104;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:106;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:107;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:108;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:113;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:114;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:115;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:116;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:117;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:185;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:187;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:188;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:189;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:190;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:192;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:193;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:209;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:118;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:119;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:120;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:126;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:127;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:128;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:129;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:182;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:130;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:131;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:132;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:133;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:136;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:137;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:138;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:139;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:140;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:141;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:144;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:145;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:146;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:147;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:148;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:151;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:186;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:208;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:158;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:159;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:160;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:207;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:165;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:166;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:167;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:172;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:173;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:174;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:177;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:205;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:206;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:211;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:212;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}i:213;O:8:"stdClass":5:{s:4:"list";i:1;s:4:"view";i:1;s:3:"add";i:1;s:4:"edit";i:1;s:6:"delete";i:1;}}';
                $data_branch_user_access = array(
                    'company_id' => $this->session->userdata('company_id'),
                    'branch_id' => $branch_id,
                    'user_id' => $user_id,
                    'module' => $str,
                    'status' => '1',
                    'created_at' => date("Y-m-d H:i:s")
                );
                $this->dashboardmodel->addBranchUserAccess($data_branch_user_access);

                //For add O/B respect to branch start
                $ledger_id = $this->dashboardmodel->getLedgerId();
                $ledger_details = array();
                foreach ($ledger_id AS $key=>$val){
                    $ledger_details[$key]['branch_id']= $branch_id;
                    $ledger_details[$key]['ladger_id']= $val['id'];
                    $ledger_details[$key]['account']= $val['account_type'];
                    $ledger_details[$key]['balance']= 0.00;
                    $ledger_details[$key]['current_opening_balance']= 0.00;
                    $ledger_details[$key]['current_closing_balance']= 0.00;
                    $ledger_details[$key]['create_date']= date("Y-m-d H:i:s");
                }
                $this->dashboardmodel->setLedgerAsBranch($ledger_details);

                // For opening cost
                $opening_cost = array();
                $opening_cost['user_id']= $user_id;
                $opening_cost['price']= 0.00;
                $opening_cost['branch_id']= $branch_id;
                $this->dashboardmodel->newRowInsertForOpeningCost($opening_cost);
                //For add O/B respect to branch end

                $data_branch_ledger = array(
                    'group_id' => '17',
                    'ladger_name' => $this->input->post('company_name'),
                    'opening_balance' => '0',
                    'account_type' => 'Dr',
                    'last_opening_balance' => '0',
                    'current_balance' => '0',
                    'tracking_status' => '2',
                    'bill_details_status' => '2',
                    'service_status' => '2',
                    'branch_id' => $branch_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'status' => '1',
                    'deleted' => '0',
                );
                $ledger_code_status = $this->dashboardmodel->getLedgerCodeStatus();
                if ($ledger_code_status['ledger_code_status'] == '1') {
                    $last_id = $this->dashboardmodel->addBranchLedger($data_branch_ledger);
                    if (strlen($last_id) == 1) {
                        $updatedata['ledger_code'] = 'L00' . $last_id;
                    } elseif (strlen($last_id) == 2) {
                        $updatedata['ledger_code'] = 'L0' . $last_id;
                    } else {
                        $updatedata['ledger_code'] = 'L' . $last_id;
                    }
                    $this->dashboardmodel->updateLedgerCode($updatedata, $last_id);
                } else {
                    $last_id = $this->dashboardmodel->addBranchLedger($data_branch_ledger);
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'save_error';
                    $data_msg['message'] = 'Error in process. Please try again.';
                } else {
                    $this->db->trans_commit();
                    $data_msg['url'] = site_url('admin/branch');
                    $data_msg['res'] = 'success';
                    $data_msg['message'] = 'Branch added successfully.';
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = $this->form_validation->error_array();
            }
        }
        echo json_encode($data_msg);
    }

    public function save_selected_branch() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('selected_branch[0]', 'Branch', 'required');
            if ($this->form_validation->run() === TRUE) {
                $selected_branch = isset($_POST['selected_branch']) ? $_POST['selected_branch'] : array();
                if (!in_array($this->session->userdata('branch_id'), $selected_branch)) {
                    array_push($selected_branch, $this->session->userdata('branch_id'));
                }
                $this->session->set_userdata('selected_branch', $selected_branch);

                $selected_branch_str = '';
                foreach ($this->session->userdata('selected_branch') as $b) {
                    $selected_branch_str.="'" . $b . "'" . ',';
                }
                $selected_branch_str = rtrim($selected_branch_str, ',');
                $this->session->set_userdata('selected_branch_str', $selected_branch_str);
                $data_msg['res'] = 'success';
                $data_msg['message'] = "Branch successfully selected.";
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = "Please select at list one branch.";
            }
        }
        echo json_encode($data_msg);
    }


    public function getCurrentFinancialYearForDateRange() {
        $m = $_POST['month'];
        // $m = str_pad($m, 2, 0, STR_PAD_LEFT);
        $financial_year = get_financial_year();
        $monthArray = array();
        $i=0;
        foreach($financial_year as $fin_yr){
            $monthArray[$i++] = date('n', strtotime($fin_yr));
        }
        $key = array_search($m, $monthArray);
        $year = date('Y', strtotime($financial_year[$key]));
        echo $year;
    }

    public function getCurrentSession() {
        if($this->session->userdata('admin_uid')){
            echo "true";
        }else{
            echo "false";
        }
    }

    /*
     * search a company
     */
    public function searchCompany() {
        $company_name = $this->input->post('company_name');
        $data = array();
        $this->load->model('admin/dashboardmodel');
        $sass_id = $this->dashboardmodel->getSaasId($this->session->userdata('admin_uid'));
        $data['companylist'] = $this->dashboardmodel->searchCompanyByUserId($sass_id, $company_name);

        return $this->load->view('admin/search_company', $data);
    }

    /*
     * get all group and ledger list
     */
    public function getAllGroupLedgerList() {
        $data_msg = [];
        $groups = $this->dashboardmodel->getAllGroups();
        $ledger = $this->dashboardmodel->getAllLedger();
        $index = 0;
        $results = [];
        if (count($groups) > 0) {
            foreach ($groups as $value) {
                $results [$index]["label"] = $value->group_name;
                $results [$index]["value"] = "1_" . $value->id;
                $index++;
            }

            $data_msg['product'] = $results;
            $data_msg['res'] = 'success';
        }
        if (count($ledger) > 0) {
            foreach ($ledger as $value) {
                $results [$index]["label"] = $value->ladger_name;
                $results [$index]["value"] = "2_" . $value->id;
                $index++;
            }

            $data_msg['product'] = $results;
            $data_msg['res'] = 'success';
        }
        if (count($ledger) <= 0 && count($groups) <= 0) {
            $data_msg['message'] = 'No Group/Ledger Available.';
            $data_msg['res'] = 'error';
        }
        echo json_encode($data_msg);
    }

    /*
     * add watchlist
     */
    public function add_watchlist() {
        $x = explode("_", $_GET['group_ledger']);
        $this->dashboardmodel->saveWatch_list($x[1], $x[0]);
        redirect(base_url('admin/dashboard'));
    }

    /*
     * delete watchlist
     */
    public function delete_watchlist($account_type, $id) {
        $this->dashboardmodel->delete_watchlist($account_type, $id);
        redirect(base_url('admin/dashboard'));
    }

    public function project_logout()
    {

        delete_cookie('admin_uid');
        delete_cookie('admin_detail');
        $this->session->sess_destroy();
        redirect(SAAS_URL."user_logout");
    }

    public function searchReportMenu()
    {
        $search_val = $this->input->post('search_value');
        $response = $this->dashboardmodel->searchReportMenu($search_val);
        $html = "<ul class='list-group'>";
        if (!empty($response)) {
            foreach ($response as $key => $value) {
                $html .= "<li class='list-group-item'><a href='" . base_url($value->url) ."'>" . $value->label . "</a></li>";
            }
        } else {
            $html .= "<li class='list-group-item'>No report found</li>";
        }


        $html .= "</ul>";
        echo $html;
    }

    public function searchReportMenuJson()
    {
        $search_val = $this->input->post('search_value');
        $response = $this->dashboardmodel->searchReportMenu($search_val);
        foreach ($response as $key => $value) {
            $data[$key]['url'] = $value->url;
            $data[$key]['value'] = $value->label;
        }
        echo json_encode($data);
    }

    public function force404()
    {
        $data = array();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Work in progress');
        $this->layouts->render('admin/force404', $data, 'admin');
    }

    public function deleteNotification($id = '')
    {
        $res = $this->dashboardmodel->deleteNotification($id);
        if ($res) {
            echo "1";
        } else {
            echo "0";
        }

    }

    public function permanentlyDeleteEntry($id = '')
    {
        $res = $this->dashboardmodel->permanentlyDeleteEntry($id);
        if ($res) {
            echo "1";
        } else {
            echo "0";
        }

    }

    public function restoreEntry($id = '')
    {
        $res = $this->dashboardmodel->restoreEntry($id);
        if ($res) {
            echo "1";
        } else {
            echo "0";
        }

    }




}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
