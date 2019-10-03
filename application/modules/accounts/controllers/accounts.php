<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class accounts extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('accounts/account');
        $this->load->model('accounts/group');
        $this->load->helper('url', 'form');
        $this->load->library('session');
        $this->load->library('form_validation');
        admin_authenticate();
    }

    public function index() {
        $where = '';
        $where = array(
            'ladger.status' => 1
        );
        $ledgers = $this->account->getAllLedger($where);
        unset($where);
        $where = array(
            'status' => 1,
            'deleted' => 0
        );
        $parent_groups = $this->group->getGroups($where);
        $groups = array();
        foreach ($parent_groups as $value) {
            $groups[$value['id']] = $value['group_name'];
        }

        $ledger_list = array();
        foreach ($groups as $key => $value) {
            foreach ($ledgers as $ledger) {
                if ($value == $ledger['group_name']) {
                    $ledger_list[$value][] = $ledger;
                }
            }
        }

        $data['ledger_list'] = $ledger_list;

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Ledgers');
        $this->layouts->render('admin/account_list', $data, 'admin');
    }

    public function account_ledger() {
        $group_id = $this->uri->segment(3, 0);
        $where = '';
        $where = array(
            'ladger.status' => 1
        );
        $ledgers = $this->account->getAllLedger($where);
        unset($where);
        $where = array(
            'status' => 1,
            'deleted' => 0,
            'id' => $group_id
        );
        $parent_groups = $this->group->getGroups($where);
        $groups = array();
        foreach ($parent_groups as $value) {
            $groups[$value['id']] = $value['group_name'];
        }

        $ledger_list = array();
        foreach ($groups as $key => $value) {
            foreach ($ledgers as $ledger) {
                if ($value == $ledger['group_name']) {
                    $ledger_list[$value][] = $ledger;
                }
            }
        }

        $data['ledger_list'] = $ledger_list;

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Ledgers');
        $this->layouts->render('admin/account_list', $data, 'admin');
    }

    public function ledger_company_details() {
        $ledger_id = $this->uri->segment(3, 0);
        $where = '';
        $where = array(
            'ladger.status' => 1,
            'ladger.id' => $ledger_id
        );
        $ledgers = $this->account->getAllLedger($where);
        $data['ledger_company_details'] = $ledgers;

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Ledger Comppany Details');
        $this->layouts->render('admin/ledger_company_details', $data, 'admin');
    }

    public function add_ledger($id = null) {
        $permission = admin_users_permission('A', 'ledger', $rtype = TRUE);
        if ($permission) {
            $ledger_id = $id;
            $ledger_code_status = $this->account->getLedgerCodeStatus();
            $data['ledger_code_status'] = $ledger_code_status['ledger_code_status'];
            $where = '';
            $where = array(
                'status' => 1,
                'deleted' => 0
            );
            $this->load->model('groups/group');
            $all_groups = $this->group->getGroups($where);
            $groups = array();
            foreach ($all_groups as $value) {
                $groups[$value['id']] = $value['group_name'];
            }
            $data['groups'] = $groups;
            $data['ledger'] = array();
            $data['contacts'] = array();
            if ($ledger_id != 0) {
//                $where = array(
//                    'ladger.id' => $ledger_id,
//                    'ladger.status' => 1,
//                    'ladger.deleted' => 0
//                );
                $where = array(
                    'ladger.id' => $ledger_id,
                    'ladger.status' => 1,
                    'ladger.deleted' => 0,
                    'ladger_account_detail.branch_id' => $this->session->userdata('branch_id')
                );
                $data['ledger'] = $this->account->getLedgerDetailsById($where);
                $data['contacts'] = $this->account->getContactsByLedgerId($ledger_id);
                $data['bank_details'] = $this->account->getBankDetailsCompany($ledger_id);
                
            }
            $page_title = ($ledger_id) ? 'Edit Account Ledger' : 'Add Account Ledger';

            $this->load->model('customer_details/admin/customer_details');
            $data['country'] = $this->customer_details->getAllCountry();
            $data['states'] = $this->customer_details->getAllState(101);

            $getsitename = getsitename();
            $this->layouts->set_title($getsitename . ' | ' . $page_title);
            $this->layouts->render('admin/add_ledger', $data, 'admin');
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    //ajax save ledger data
    public function ajax_save_ledger_data() {
        $data_msg = [];
        
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('group_id', 'Group Name', 'required|max_length[50]');
            if($this->input->post('id')!=0){
               $this->form_validation->set_rules('ladger_name', 'Ledger Name', 'required|is_unique_update_ledger[ladger.ladger_name]|max_length[50]');   
            }else{
            $this->form_validation->set_rules('ladger_name', 'Ledger Name', 'required|is_unique[ladger.ladger_name]|max_length[50]');
            }
            $this->form_validation->set_rules('opening_balance', 'Opening Balence', 'numeric');
            $this->form_validation->set_rules('account', 'Account Type', 'required');
            if($this->input->post('contact_required')==1){
             $this->form_validation->set_rules('contact_id', 'Contacts', 'required');   
            }
            if ($this->form_validation->run() === TRUE) {
                //post data
                $contact_id = $this->input->post('contact_id');
                $data['ledger_id'] = $this->input->post('id');
                $data['group_id'] = $this->input->post('group_id');
                $data['ladger_name'] = $this->input->post('ladger_name');
                $data['account'] = $this->input->post('account');
                $data['opening_balance'] = $this->input->post('opening_balance');
                $data['ladger_account_detail_id'] = $this->input->post('ladger_account_detail_id');
                $data['ledger_code'] = $this->input->post('ledger_code');
                $data['tracking_status'] = $this->input->post('tracking_status');
                $data['bill_details_status'] = $this->input->post('bill_details_status');
                $data['service_status'] = $this->input->post('service_status');
                //credit date
                $data['credit_date'] = $this->input->post('credit_date');
                $data['credit_limit'] = $this->input->post('credit_limit');

                $data['bank_name'] = $this->input->post('bank_name');
                $data['branch_name'] = $this->input->post('branch_name');
                $data['acc_no'] = $this->input->post('acc_no');
                $data['ifsc'] = $this->input->post('ifsc');
                $data['bank_address'] = $this->input->post('bank_address');
               
                //credit date
                $ledger_code_status = $this->account->getLedgerCodeStatus();
                if ($ledger_code_status['ledger_code_status'] == '1') {
                    $last_id = $this->account->saveLedgerData($data);
                    if (strlen($last_id) == 1) {
                        $updatedata['ledger_code'] = 'L00' . $last_id;
                    } elseif (strlen($last_id) == 2) {
                        $updatedata['ledger_code'] = 'L0' . $last_id;
                    } else {
                        $updatedata['ledger_code'] = 'L' . $last_id;
                    }
                    $this->account->updateLedgerCode($updatedata, $last_id);
                } else {
                    $last_id=$this->account->saveLedgerData($data);
                }
                if($contact_id && $last_id){
                $this->assign_ledger_id($contact_id,$last_id);    
                }
                if($last_id){    
                $data_msg['res'] = 'success';
                $data_msg['message'] = 'Ledger data saved successfully.';
                }else{
                 $data_msg['res'] = 'save_err';
                $data_msg['message'] = 'Error in process. Please try again';   
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = $this->form_validation->error_array();
            }
            echo json_encode($data_msg);
        }
    }
    
    public function assign_ledger_id($contact_id,$ledger_id){
       $this->account->updateCustomerAddress($contact_id,$ledger_id); 
       $this->account->updateShippingAddress($contact_id,$ledger_id); 
    }

    public function add_ledger_bk_27_03_2017($id = null) {
        $permission = admin_users_permission('A', 'ledger', $rtype = TRUE);
        if ($permission) {
            $this->load->library('form_validation');
            $ledger_id = $this->uri->segment(3, 0);
            $ledger_code_status = $this->account->getLedgerCodeStatus();
            $data['ledger_code_status'] = $ledger_code_status['ledger_code_status'];
            $where = '';
            $where = array(
                'status' => 1,
                'deleted' => 0
            );
            $this->load->model('groups/group');
            $all_groups = $this->group->getGroups($where);
            $groups = array();
            foreach ($all_groups as $value) {
                $groups[$value['id']] = $value['group_name'];
            }
            $data['groups'] = $groups;
            $data['ledger'] = array();
            if ($ledger_id != 0) {
                $where = array(
                    'ladger.id' => $ledger_id,
                    'ladger.status' => 1
                );
                $data['ledger'] = $this->account->getLedgerById($where);
            }

            $this->form_validation->set_rules('group_id', 'Group', 'required');
            $this->form_validation->set_rules('ladger_name', 'Ladger Name', 'required');
            $this->form_validation->set_rules('account', 'Opening', 'required');
            $this->form_validation->set_rules('opening_balance', 'Balance', 'required');

            if ($this->form_validation->run() == FALSE) {
                $getsitename = getsitename();
                $this->layouts->set_title($getsitename . ' | Add Account Ledger');
                $this->layouts->render('admin/add_ledger', $data, 'admin');
            } else {
                $this->new_ledger();
            }
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    public function view_ledger() {
        $this->load->library('form_validation');
        $ledger_id = $this->uri->segment(3, 0);
        $ledger_code_status = $this->account->getLedgerCodeStatus();
        $data['ledger_code_status'] = $ledger_code_status['ledger_code_status'];
        $where = '';
        $where = array(
            'status' => 1,
            'deleted' => 0
        );
        $this->load->model('groups/group');
        $all_groups = $this->group->getGroups($where);
        $groups = array();
        foreach ($all_groups as $value) {
            $groups[$value['id']] = $value['group_name'];
        }
        $data['groups'] = $groups;
        $data['ledger'] = array();
        if ($ledger_id != 0) {
//          if  no branch
//            $where = array(
//                'ladger.id' => $ledger_id,
//                'ladger.status' => 1,
//                'ladger.deleted' => 0,
//            );
           
//            else branch
            $where = array(
                'ladger.id' => $ledger_id,
                'ladger.status' => 1,
                'ladger.deleted' => 0,
                'ladger_account_detail.branch_id' => $this->session->userdata('branch_id')
            );
            $data['ledger'] = $this->account->getLedgerById($where);
        }

        // echo "<pre>";print_r($data['ledger']);exit();
        
        $this->form_validation->set_rules('group_id', 'Group', 'required');
        $this->form_validation->set_rules('ladger_name', 'Ladger Name', 'required');
        $this->form_validation->set_rules('account', 'Opening', 'required');
        $this->form_validation->set_rules('opening_balance', 'Balance', 'required');
        if ($this->form_validation->run() == FALSE) {
            $getsitename = getsitename();
            $this->layouts->set_title($getsitename . ' | Add Account Ledger');
            $this->layouts->render('admin/view_ledger', $data, 'admin');
        } else {
            $this->new_ledger();
        }
    }

    public function new_ledger() {
        $data['ledger_id'] = $this->input->post('id');
        $data['group_id'] = $this->input->post('group_id');
        $data['ladger_name'] = $this->input->post('ladger_name');
        $data['account'] = $this->input->post('account');
        if ($this->input->post('opening_balance') != '') {
            $data['opening_balance'] = $this->input->post('opening_balance');
        } else {
            $data['opening_balance'] = 0.00;
        }
        $data['cash/bank'] = $this->input->post('cash/bank');
        $data['reconciliation'] = $this->input->post('reconciliation');
        $data['ladger_account_detail_id'] = $this->input->post('ladger_account_detail_id');

        $data['mailing_name'] = $this->input->post('mailing_name');
        $data['street_address'] = $this->input->post('street_address');
        $data['country'] = $this->input->post('country');
        $data['state'] = $this->input->post('state');
        $data['city_name'] = $this->input->post('city_name');
        $data['zip_code'] = $this->input->post('zip_code');
        $data['pan_it_no'] = $this->input->post('pan_it_no');
        $data['sale_tax_no'] = $this->input->post('sale_tax_no');
        $data['cst_no'] = $this->input->post('cst_no');
        $data['contact_person'] = $this->input->post('contact_person');
        $data['contact_person2'] = $this->input->post('contact_person2');
        $data['email'] = $this->input->post('email');
        $data['email2'] = $this->input->post('email2');
        $data['phone_no'] = $this->input->post('phone_no');
        $data['phone_no2'] = $this->input->post('phone_no2');
        $data['designation'] = $this->input->post('designation');
        $data['designation2'] = $this->input->post('designation2');

        $data['ledger_code'] = $this->input->post('ledger_code');
        $data['tracking_status'] = $this->input->post('tracking_status');
        $data['bill_details_status'] = $this->input->post('bill_details_status');
        //credit date
        $data['credit_date'] = $this->input->post('credit_date');
        $data['credit_limit'] = $this->input->post('credit_limit');
        //credit date

        $ledger_code_status = $this->account->getLedgerCodeStatus();
        if ($ledger_code_status['ledger_code_status'] == '1') {
            $last_id = $this->account->saveLedger($data);
            if (strlen($last_id) == 1) {
                $updatedata['ledger_code'] = 'L00' . $last_id;
            } elseif (strlen($last_id) == 2) {
                $updatedata['ledger_code'] = 'L0' . $last_id;
            } else {
                $updatedata['ledger_code'] = 'L' . $last_id;
            }
            $this->account->updateLedgerCode($updatedata, $last_id);
        } else {
            $this->account->saveLedger($data);
        }
        $this->session->set_flashdata('successmessage', 'Ledger Add successfully');
        // $redirect = site_url('admin/accounts-ledger');
        $redirect = site_url('admin/accounts-groups');
        redirect($redirect);
    }

    public function edit_ledger() {
        $permission = admin_users_permission('E', 'ledger', $rtype = TRUE);
        if ($permission) {
            $ledger_id = $this->uri->segment(3, 0);
            $where = '';
            $where = array(
                'group.status' => 1,
                'group.deleted' => 0
            );
            $this->load->model('groups/group');
            $all_groups = $this->group->getGroups($where);
            $groups = array();
            foreach ($all_groups as $value) {
                $groups[$value['id']] = $value['group_name'];
            }

            $data['groups'] = $groups;

            if ($ledger_id != 0) {
                $where = array(
                    'ladger.id' => $ledger_id,
                    'ladger.status' => 1,
                    'ladger.deleted' => 0
                );
                $data['ledger'] = $this->account->getLedgerById($where);
            }

//            echo '<pre>';print_r($data['ledger']);die();

            $getsitename = getsitename();
            $this->layouts->set_title($getsitename . ' | Add Account Ledger');
            $this->layouts->render('admin/add_ledger', $data, 'admin');
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    public function check_ledger_name() {
        $ledger_name = $_POST['ledger_name'];
        $where = array(
            'ladger_name' => $ledger_name,
            'status' => 1,
            'deleted' => 0
        );
        $result = $this->account->getLedgerName($where);
        echo json_encode($result);
        exit;
    }

    public function getGroupDetails() {
        $group = $_POST['group'];
        $where = array(
            'status' => 1,
            'deleted' => 0,
            'parent_id !=' => 0
        );
        $ledger_name = $this->account->getAllGroupNameForJson($where, $group);
        if ($ledger_name) {
            foreach ($ledger_name as $value) {
                $ledge[] = $value['group_name'];
            }
        } else {
            $ledge[] = '';
        }
        echo json_encode($ledge);
    }

    public function getAccountType() {

        $ledge[] = '';
        $ledge[] = 'Dr';
        $ledge[] = 'Cr';

        echo json_encode($ledge);
    }

    public function getLedgerDetails() {
        $ledger = $_POST['ledger'];
        $where = array(
            'status' => 1,
            'deleted' => 0
        );
        $ledger_name = $this->account->getAllLedgerNameForJson($where, $ledger);
        if ($ledger_name) {
            foreach ($ledger_name as $value) {
                $ledge[] = $value['ladger_name'];
            }
        } else {
            $ledge[] = '';
        }
        echo json_encode($ledge);
    }

    public function get_group_id() {
        if ($this->input->post('ajax', TRUE)) {
            $group = $_POST['group'];
            $returnId = $this->account->getGroupId($group);
            if (empty($returnId)) {
                echo json_encode(array('SUCESS' => 0, 'MSG' => ''));
            } else {
                echo json_encode(array('SUCESS' => 1, 'MSG' => '', 'MENU' => $returnId));
            }
        } else {
            echo json_encode(array('SUCESS' => 0, 'MSG' => 'This page only access by ajax'));
        }
    }

    public function remove($id = NULL) {
        $permission = admin_users_permission('D', 'ledger', $rtype = TRUE);
        if ($permission) {
            if ($id != "") {
                $data['deleted'] = '1';
                $this->account->delete($id, $data);
            }
            $redirect = site_url('admin/accounts-groups');
            redirect($redirect);
            $this->session->set_flashdata('successmessage', 'Ledger Deleted successfully');
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    public function getCashBank() {
        $ledger = $_POST['ledger'];
        $where = array(
            'status' => 1,
            'deleted' => 0
        );
        $ledger_name = $this->account->getCashBankLedgerNameForJson($where, $ledger);
        if ($ledger_name) {
            foreach ($ledger_name as $value) {
                $ledge[] = $value['ladger_name'];
            }
        } else {
            $ledge[] = '';
        }
        echo json_encode($ledge);
    }

    public function ledger_name_check() {
        if ($this->input->post('ajax', TRUE)) {
            $ladger_name = $_POST['ladger_name'];
            $returnId = $this->account->getLedgerIdByName($ladger_name);
            if (empty($returnId)) {
                echo json_encode(array('SUCESS' => 0));
            } else {
                echo json_encode(array('SUCESS' => 1));
            }
        } else {
            echo json_encode(array('SUCESS' => 0, 'MSG' => 'This page only access by ajax'));
        }
    }
    
      public function ajax_delete_ledger() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $ledger_id = $this->input->post('delete_entry_id');
             $row = $this->account->checkLedgerExist($ledger_id);
             if(count($row)>0){
              $data_msg['res']='error';
              $data_msg['message']='This Ladger can not be deleted. Because This are already in used.';
             }else{
              $res=$this->account->deleteLadger($ledger_id);
              if($res){
               $data_msg['ledger_id']=$ledger_id;
               $data_msg['res']='success';
              $data_msg['message']='Ledger deleted successfully.';   
              }else{
               $data_msg['res']='error';
              $data_msg['message']='Error occured please try again.';   
              }
             }
             echo json_encode($data_msg);
        }
    }
    
      public function ajax_discontinue_ledger() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $ledger_id = $this->input->post('discontinue_entry_id');
             $row = $this->account->checkLedgerExist($ledger_id);
             if(count($row)>0){
              $data_msg['res']='error';
              $data_msg['message']='This Ladger can not be discontinued. Because This are already in used.';
             }else{
              $res=$this->account->discontinueLadger($ledger_id);
              if($res){
               $data_msg['ledger_id']=$ledger_id;
               $data_msg['res']='success';
              $data_msg['message']='Ledger discontinued successfully.';   
              }else{
               $data_msg['res']='error';
              $data_msg['message']='Error occured please try again.';   
              }
             }
             echo json_encode($data_msg);
        }
    }
    
    public function getAllContacts() {
        $data_msg = [];
        $company = $this->input->post('company');
        $contacts = '[';
        $all_contacts = $this->account->getContact($company);
        if($all_contacts){
        foreach ($all_contacts as $value) {
            $contacts .= " { \"label\": \"$value->company_name\", \"value\": \"$value->id\"},";
        }
        $contacts = substr($contacts, 0, -1);
        }
        $contacts .= ' ]';
        echo $contacts;
    }
    
    public function getAllGroup() {
        $notInList = [1,2,3,4,5,29,30,34,35];
        $data_msg = [];
        $group = $this->input->post('group');
        $groups = '[';
        $all_groups = $this->account->getGroupByName($group,$notInList);
        if($all_groups){
        foreach ($all_groups as $value) {
            if($value->group_name != "Branch / Divisions") {
                $groups .= " { \"label\": \"$value->group_name\", \"value\": \"$value->id\"},";
            }
            
        }
        $groups = substr($groups, 0, -1);
        }
        $groups .= ' ]';
        echo $groups;
    }
    
    public function checkGroup(){
        $data_msg=[];
        $check_group_id=$this->input->post('group_id');
        $group_id = array(15, 23);
        $all_sub_group = $this->account->getAllSubGroup($group_id);
        if(in_array($check_group_id, $all_sub_group)){
        $data_msg['res']=true;    
        }else{
        $data_msg['res']=false;    
        }
        echo json_encode($data_msg);
    }
    
    public function getNoOfVoucher() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $ledger_id = $this->input->post('ledger_id');
            $row = $this->account->getNoOfVoucherById($ledger_id);
                if ($row) {
                    $data_msg['rows'] = $row;
                    $data_msg['res'] = 'success';
                    $data_msg['message'] = 'Get Number of ledger..';
                } else {
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = 'Error occured please try again.';
                }
            echo json_encode($data_msg);
        }
    }

    public function editLedgerForTransaction()
    {
        $ledger_id = $this->input->post('ledger_id');
        $ledger_code_status = $this->account->getLedgerCodeStatus();
        $data['ledger_code_status'] = $ledger_code_status['ledger_code_status'];
        $where = '';
        $where = array(
            'status' => 1,
            'deleted' => 0
        );
        $this->load->model('groups/group');
        $all_groups = $this->group->getGroups($where);
        $groups = array();
        foreach ($all_groups as $value) {
            $groups[$value['id']] = $value['group_name'];
        }
        $data['groups'] = $groups;
        $data['ledger'] = array();
        $data['contacts'] = array();
        if ($ledger_id != 0) {
            $where = array(
                'ladger.id' => $ledger_id,
                'ladger.status' => 1,
                'ladger.deleted' => 0,
                'ladger_account_detail.branch_id' => $this->session->userdata('branch_id')
            );
            $data['ledger'] = $this->account->getLedgerDetailsById($where);
            $data['contacts'] = $this->account->getContactsByLedgerId($ledger_id);
            $data['bank_details'] = $this->account->getBankDetailsCompany($ledger_id);
            
        }
        echo $this->load->view('accounts/admin/edit_ledger_in_transaction', $data, TRUE);
    }

}

/* End of file login.php */
/* Location: ./application/controllers/login.php */