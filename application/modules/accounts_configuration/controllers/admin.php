<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->model('admin/accountconfigurationmodel');
        admin_authenticate();
    }

    public function index() {
        user_permission(114,'edit');
            $this->load->helper('text');
            $data = array();
            $result = $this->accountconfigurationmodel->loadAll();
            $data['branch'] = $this->accountconfigurationmodel->getAllBranch();
            $getsitename = getsitename();
            $data['configuration'] = $result;
            $data['currency'] = $this->accountconfigurationmodel->getAllCurrency();
            $data['date_format'] = $this->accountconfigurationmodel->getAllDateFormat();
            $data['number_of_branch'] = $this->accountconfigurationmodel->getNumberOfBranch();
            $this->layouts->set_title($getsitename . ' | Configuration');
            $this->layouts->render('admin/configuration', $data, 'admin');

    }

    public function configurationmodify() {

        $permission=admin_users_permission('E','configurations',$rtype=TRUE);
        if($permission)
        {
            $data['skip_date_create'] = $this->input->post('skip_date_create');
            $data['toby_instead_drcr'] = $this->input->post('toby_instead_drcr');
            $data['contra_cheque_printing'] = $this->input->post('contra_cheque_printing');
            $data['warn_nagetive_cash_balance'] = $this->input->post('warn_nagetive_cash_balance');
            $data['cash_account_journal'] = $this->input->post('cash_account_journal');
            $data['voucher_ledger_current_balance'] = $this->input->post('voucher_ledger_current_balance');
            $data['voucher_date_balance_show'] = $this->input->post('voucher_date_balance_show');
            $data['group_code_status'] = $this->input->post('group_code_status');
            $data['ledger_code_status'] = $this->input->post('ledger_code_status');
            $data['voucher_no_status'] = $this->input->post('voucher_no_status');
            $data['requir_product_attributes'] = $this->input->post('requir_product_attributes');
            $data['is_inventory'] = $this->input->post('is_inventory');
            $data['required_updated_currency'] = $this->input->post('required_updated_currency');
            $data['code_before_name'] = $this->input->post('code_before_name');
            $data['price_format'] = $this->input->post('price_format');
            $data['want_recurring'] = $this->input->post('want_recurring');
            $data['sales_mail'] = $this->input->post('sales_mail');
            $data['sales_order_mail'] = $this->input->post('sales_order_mail');
            $data['receipt_mail'] = $this->input->post('receipt_mail');
            $data['bank_details'] = $this->input->post('bank_details');
            $data['selected_currency'] = $this->input->post('selected_currency');
            $data['selected_date_format'] = $this->input->post('selected_date_format');
            $data['entry_action_limit'] = $this->input->post('entry_action_limit');
            $data['eway_bill'] = $this->input->post('eway_bill');
            $data['godown'] = $this->input->post('godown');
            $data['batch'] = $this->input->post('batch');
            $data['print'] = $this->input->post('print');

            $id = 1;
            
            if ($data['godown'] == 0) {
                $this->accountconfigurationmodel->disableGodownFromMenu($status=0);
            }else {
                $this->accountconfigurationmodel->disableGodownFromMenu($status=1);
            }
            
            if ($data['batch'] == 0) {
                $this->accountconfigurationmodel->deleteAllBatch();
            }
            $selected_branch=isset($_POST['selected_branch']) ? $_POST['selected_branch']:array();
            if(!in_array($this->session->userdata('branch_id'), $selected_branch)){
            array_push($selected_branch,$this->session->userdata('branch_id'));
            }
            $this->session->set_userdata('selected_branch', $selected_branch);
            $selected_branch_str = '';
            foreach ($this->session->userdata('selected_branch') as $b) {
                $selected_branch_str.="'" . $b . "'" . ',';
            }
            $selected_branch_str = rtrim($selected_branch_str, ',');
            $this->session->set_userdata('selected_branch_str', $selected_branch_str);

            $updflg = $this->accountconfigurationmodel->modifyData($data, $id);

            $data_msg = [];
            $data_msg['res'] = 'error';

            // if (!empty($updflg)) {
            if ($updflg) {
                $data_msg['res'] = 'success';
                $data_msg['message'] = 'Configuration updated successfully';
                // $this->session->set_flashdata('successmessage', 'Configuration updated successfully');
            } else {
                $data_msg['message'] = 'Oops! Something went wrong';
                // $this->session->set_flashdata('errormessage', 'Oops! Something went wrong');
            }
            if ($this->input->is_ajax_request()) {
                echo json_encode($data_msg);
            } else {
                $redirect = site_url('admin/accounts-configuration');
                redirect($redirect);
            }


            }
            else
            {
                if ($this->input->is_ajax_request()) {
                    $data_msg['message'] = "You are not permitted to perform this action";
                    echo json_encode($data_msg);
                } else {
                    // $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
                    $redirect = site_url('admin/dashboard');
                    redirect($redirect);
                }

            }
        }



    public function getAllBankDetailsCompanyForInvoice()
    {
        $data['banks'] = $this->accountconfigurationmodel->getAllBankDetailsCompany();
        $data['transaction'] = false;
        $this->load->view('accounts_configuration/admin/bank_details_invoice', $data);
        // echo json_encode($banks);
    }

    public function saveDefaultBank()
    {
        $bank_id = $this->input->post('bank_id');
        $this->accountconfigurationmodel->saveDefaultBank($bank_id);
    }

    public function showbankModalInvoice()
    {
        $pref = $this->accountconfigurationmodel->loadAll();
        if ($pref->bank_details) {
            $data['banks'] = $this->accountconfigurationmodel->getAllBankDetailsCompany();
            $data['transaction'] = true;
            $this->load->view('accounts_configuration/admin/bank_details_invoice', $data);
        } else {
            echo "<p class='alert alert-warning'>Please enable bank details for invoice at preference</p>";
        }

        // print_r($pref);
    }

    /*
     * check the if there is a default bank for transaction or not
     */
    public function getBankDetailsStatus()
    {
        $msg = [];
        $msg['flag'] = false;
        $active_bank = $this->accountconfigurationmodel->getBankDetailsStatus();
        if(!empty($active_bank)) {
            $msg['flag'] = true;
        }
        echo json_encode($msg);
    }
    
    
     public function showCourierModalInvoice(){
        $data['couriors'] = $this->accountconfigurationmodel->getAllDespatchDetails();
        if(!empty($data['couriors'])){
            $this->load->view('accounts_configuration/admin/courier_details', $data);
        }else{
            echo FALSE;
        }
    }

    public function getUserDetailsForEwayBill()
    {
        $data['account_settings'] = $this->accountconfigurationmodel->getAccountSettings();
        $this->load->view('accounts_configuration/admin/user_details_for_eway_bill', $data);
    }

    public function saveUserCredentialsForEwayBill()
    {
        $data['eway_username'] = $this->input->post('eway_username');
        $data['eway_password'] = md5($this->input->post('eway_password'));
        $this->accountconfigurationmodel->saveUserCredentialsForEwayBill($data);
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
