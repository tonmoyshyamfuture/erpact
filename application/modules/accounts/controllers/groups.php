<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class groups extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('accounts/group');
        $this->load->model('accounts/account');
        $this->load->model('admin/companymodel');
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->helper('financialyear');
        admin_authenticate();
    }

    public function index() {
        user_permission(106,'list');
        $where = '';
        $groups = $this->group->getAllGroupsByLimit();
        $data['groupCount'] = $this->group->getCountGroup();
        $data['groups'] = $groups;
        //================================== ledger part star============================================
        unset($where);
        $where = array(
            'ladger.status' => 1,
            'ladger.deleted' => 0
        );
        $data['ledgerCount'] = $this->account->getCountLedger($where);
        $ledgers = $this->account->getAllLedgerBylimit($where);
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
        
        $data['configuration'] = $this->group->getCodePosition();
        $data['ledger_list'] = $ledgers;
        
        //=======================================ledger part end==============================================

        $data['group_csv_status'] = $this->companymodel->getCsvUploadHistory('group');
        $data['ledger_csv_status'] = $this->companymodel->getCsvUploadHistory('ledger');
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Groups');
        $this->layouts->render('admin/group_list', $data, 'admin');
    }
    
    public function groupAjaxListing(){
        $draw       = $this->input->get_post('draw');
        $start      = $this->input->get_post('start');
        $length     = $this->input->get_post('length');
        $search     = $this->input->get_post('search')['value'];
        $result = $this->group->getAllGroupsByLimit($length,$start,$search);
        $configuration = $this->group->getCodePosition();
        foreach($result AS $res){
            $subArr = array();
            $permission = ua(106, 'view');
            if($permission){
                $groupName = '';
                 if(isset($configuration->code_before_name) && ($configuration->code_before_name == 1)){
                     $groupName .= '( '.$res['group_code'].' )  ' .$res['group_name'];
                 }else{
                     $groupName .= $res['group_name'].'  ( '.$res['group_code'].' ) ';
                 }
                $subArr[] = '<a  href="'.site_url('admin/view-accounts-groups') . '/' . $res['id'].'">'.$groupName.'</a>';
            }else{
                $subArr[] = $res['group_name'];
            }
            if (isset($res['parent_group_name'])) {
                if(isset($configuration->code_before_name) && $configuration->code_before_name == 1){
                    $subArr[] = '('.$res['parent_group_code'].') '.$res['parent_group_name'];
                }else{
                    $subArr[] = $res['parent_group_name'].'  ('.$res['parent_group_code'].') ';
                }
                
            }else{
                $subArr[] = '--';
            }
            
            if ($res['operation_status'] == '1') {
                $permission = ua(106, 'delete');
                if ($permission){
                    $subArr[] = '<div class="dropdown circle">
                                    <a aria-expanded="true" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                      <i class="fa fa-ellipsis-v"></i></a>                                                        
                                        <ul class="dropdown-menu tablemenu">
                                            <li>
                                                 <a href="javascript:void(0);" title="Delete"  data-id="'.$res['id'].'" class="delete-group"><span class="text-red"><i class="fa fa-trash" aria-hidden="true"></i></span></a>
                                            </li>
                                        </ul>
                                    </div>';
                }else{
                    $subArr[] ='';
                }
            }else{
                $subArr[] ='';
            }
            
            $data['data'][]         = $subArr;
        }
        $data['draw']              = $draw;
        $data['recordsTotal']      = $this->group->getCountGroup($search);
        $data['recordsFiltered']   = $this->group->getCountGroup($search);
        echo json_encode($data);exit;
    }
    
    public function ledgerAjaxListing(){
        $draw       = $this->input->get_post('draw');
        $start      = $this->input->get_post('start');
        $length     = $this->input->get_post('length');
        $search     = $this->input->get_post('search')['value'];
        $where = array(
            'ladger.status' => 1,
            'ladger.deleted' => 0
        );
        $result = $this->account->getAllLedgerBylimit($where,$length,$start,$search);
        $configuration = $this->group->getCodePosition();
        foreach($result AS $res){
            $subArr = array();
            $permission = ua(106, 'view');
            if($permission){
                $groupName = '';
                 if(isset($configuration->code_before_name) && ($configuration->code_before_name == 1)){
                     $groupName .= '( '.$res['ledger_code'].' )  ' .$res['ladger_name'];
                 }else{
                     $groupName .= $res['ladger_name'].'  ( '.$res['ledger_code'].' ) ';
                 }
                 if ($res['discontinue'] == 1){
                     $groupName .= '(Discontinued)';
                 }
                 
                $subArr[] = '<a  href="'.site_url('admin/view-accounts-ledger') . '/' . $res['id'].'">'.$groupName.'</a>';
            }else{
                $subArr[] = $res['ladger_name']. ' (' . $res['ledger_code'] . ')';
            }
            
            $subArr[] = $res['group_name'];
            $subArr[] = $res['no_of_ledger'];
            
            if ($res['operation_status'] == '1'  && $res['branch_id'] == 0) {
                 if ($res['no_of_ledger'] == 0) {
                $permission = ua(106, 'delete');
                if ($permission){
                    $deleteLedger = '';
                    $deleteLedger .= '<div class="dropdown circle">
                                        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-ellipsis-v"></i></a>
                                        <ul class="dropdown-menu tablemenu">
                                            <li>  
                                                <a href="javascript:void(0);" title="Delete"  data-id="'. $res['id'].'" class="delete-ledger"><span class="text-red"><i class="fa fa-trash" aria-hidden="true"></i></span></a>';
                                                if ($res['discontinue'] == 0){
                                                    $deleteLedger .= '<a href="javascript:void(0);" title="Discontinue"  data-id="'. $res['id'].'" class="discontinue-ledger"><span class="text-red"><i class="fa fa-ban" aria-hidden="true"></i></span></a>';
                                                }                                                                                  
                          $deleteLedger .= '</li>
                                        </ul>
                                    </div>';
                    $subArr[] = $deleteLedger;
                }else{
                    $subArr[] ='';
                }
                }else{
                $subArr[] ='';
            }
                
            }else{
                $subArr[] ='';
            }
            
            $data['data'][]         = $subArr;
        }
        $data['draw']              = $draw;
        $data['recordsTotal']      = $this->account->getCountLedger($where,$search);
        $data['recordsFiltered']   = $this->account->getCountLedger($where,$search);
        echo json_encode($data);exit;
    }

    public function add_group($id = null) {
        //$id = $this->uri->segment(3);
        if (!empty($id))
            $param = 'E';
        else
            $param = 'A';

        $permission = admin_users_permission($param, 'groups', $rtype = TRUE);
        if ($permission) {
            $this->load->library('form_validation');
            $group_code_status = $this->group->getGroupCodeStatus();
            $data['group_code_status'] = $group_code_status['group_code_status'];
            $where = "";
            $data['group'] = "";
            if ($id != NULL) {
                $where = array(
                    'id' => $id,
                    'status' => 1,
                    'deleted' => 0
                );

                $data['group'] = $this->group->getSingleGroup($where);
                unset($where);
            }

            $where = array(
                'status' => 1,
                'deleted' => 0
            );
            //$parent_groups = $this->group->getParentGroup($where);
            $parent_groups = $this->group->getGroups($where);
            $groups = array();
            foreach ($parent_groups as $value) {
                $groups[$value['id']] = $value['group_name'];
            }
            $data['groups'] = $groups;
            $getsitename = getsitename();
            $this->layouts->set_title($getsitename . ' | Add-Group');
            $this->layouts->render('admin/add_group', $data, 'admin');
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    //ajax save ledger data
    public function ajax_save_group_data() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('parent_id', 'Parent Name', 'required');
            if ($this->input->post('id') != 0) {
                $this->form_validation->set_rules('group_name', 'Group Name', 'required|alpha_dash_space|is_unique_update_ledger[group.group_name]');
            } else {
                $this->form_validation->set_rules('group_name', 'Group Name', 'required|alpha_dash_space|is_unique[group.group_name]');
            }
            if ($this->form_validation->run() === TRUE) {
                //post data
                $data['id'] = $this->input->post('id');
                $data['parent_id'] = $this->input->post('parent_id');
                $data['group_name'] = $this->input->post('group_name');
                $data['user_id'] = $this->input->post('user_id');
                if ($this->input->post('id') != 0) {
                    $data['update_date'] = gmdate("Y-m-d H:i:s");
                } else {
                    $financial_year = get_financial_year();            
                    $finans_start_date = date("Y-m-d", strtotime(current($financial_year)));
                    $data['create_date'] = $finans_start_date;
                }

                $group_code_status = $this->group->getGroupCodeStatus();
                if ($group_code_status['group_code_status'] == '1') {
                    $last_id = $this->group->saveGroup($data);
                    if (strlen($last_id) == 1) {
                        $updatedata['group_code'] = 'AG000' . $last_id;
                    } else {
                        $updatedata['group_code'] = 'AG00' . $last_id;
                    }
                    $this->group->updateGroupCode($updatedata, $last_id);
                } else {
                    $data['group_code'] = $this->input->post('group_code');
                    $last_id = $this->group->saveGroup($data);
                }
                if ($last_id) {
                    $data_msg['res'] = 'success';
                    $data_msg['message'] = 'Group data saved successfully.';
                } else {
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

    public function view_group() {
        $id = $this->uri->segment(3);
        $this->load->library('form_validation');
        $group_code_status = $this->group->getGroupCodeStatus();
        $data['group_code_status'] = $group_code_status['group_code_status'];
        $where = "";
        $data['group'] = "";
        if ($id != NULL) {
            $where = array(
                'id' => $id,
                'status' => 1,
                'deleted' => 0
            );

            $data['group'] = $this->group->getGroups($where);
            unset($where);
        }
        $where = array(
            'status' => 1,
            'deleted' => 0
        );
        //$parent_groups = $this->group->getParentGroup($where);
        $parent_groups = $this->group->getGroups($where);
        $groups = array();
        foreach ($parent_groups as $value) {
            $groups[$value['id']] = $value['group_name'];
        }
        $data['groups'] = $groups;

        $this->form_validation->set_rules('group_id', 'Perent Group', 'required');
        $this->form_validation->set_rules('group_name', 'Group Name', 'required');

        if ($this->form_validation->run() == FALSE) {
            $getsitename = getsitename();
            $this->layouts->set_title($getsitename . ' | View-Group');
            $this->layouts->render('admin/view_group', $data, 'admin');
        } else {
            $this->save();
        }
    }

    public function save() {
        $data['parent_id'] = $this->input->post('group_id');
        $data['group_name'] = $this->input->post('group_name');
        $data['user_id'] = $this->input->post('user_id');
        if ($this->input->post('id') != '') {
            $data['id'] = $this->input->post('id');
        }

        $group_code_status = $this->group->getGroupCodeStatus();
        if ($group_code_status['group_code_status'] == '1') {
            $last_id = $this->group->saveGroup($data);
            if (strlen($last_id) == 1) {
                $updatedata['group_code'] = 'AG000' . $last_id;
            } else {
                $updatedata['group_code'] = 'AG00' . $last_id;
            }
            $this->group->updateGroupCode($updatedata, $last_id);
        } else {
            $data['group_code'] = $this->input->post('group_code');
            $this->group->saveGroup($data);
        }
        $this->session->set_flashdata('successmessage', 'Group Add successfully');

        $redirect = site_url('admin/accounts-groups') . '?_tb=1';
        redirect($redirect);
    }

    public function remove($id = NULL) {
        $permission = admin_users_permission('D', 'groups', $rtype = TRUE);
        if ($permission) {
            if ($id != "") {
                $this->group->delete($id);
            }
            $this->index();
            $this->session->set_flashdata('successmessage', 'Group Deleted successfully');
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    public function find_group_name() {
        $group_name = $_POST['group_name'];
        $where = array(
            'group_name' => $group_name,
            'status' => 1,
            'deleted' => 0
        );
        $result = $this->group->getGroupName($where);
        echo json_encode($result);
        exit;
    }

    public function group_name_check() {
        if ($this->input->post('ajax', TRUE)) {
            $group_name = $_POST['group_name'];
            $returnId = $this->group->getGroupIdByName($group_name);
            if (empty($returnId)) {
                echo json_encode(array('SUCESS' => 0));
            } else {
                echo json_encode(array('SUCESS' => 1));
            }
        } else {
            echo json_encode(array('SUCESS' => 0, 'MSG' => 'This page only access by ajax'));
        }
    }

    public function ajax_delete_group() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $group_id = $this->input->post('delete_entry_id');
            $row = $this->group->checkGroupExist($group_id);
            if (count($row) > 0) {
                $data_msg['res'] = 'error';
                $data_msg['message'] = 'This Group can not be deleted. Because This are already in used.';
            } else {
                $res = $this->group->deleteGroup($group_id);
                if ($res) {
                    $data_msg['group_id'] = $group_id;
                    $data_msg['res'] = 'success';
                    $data_msg['message'] = 'Group deleted successfully.';
                } else {
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = 'Error occured please try again.';
                }
            }
            echo json_encode($data_msg);
        }
    }

    //save watchlist ledger
    public function ajax_save_watch_list() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $ledger_id = $this->input->post('watch_ledger_id');
            $status = $this->input->post('watch_status');
            $res = $this->group->saveWatch_list($ledger_id, $status);
            if ($res) {
                $data_msg['res'] = 'success';
                if ($status) {
                    $data_msg['message'] = 'Ledger added successfully within watchlist.';
                } else {
                    $data_msg['message'] = 'Ledger removed successfully from watchlist.';
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = 'Error occured please try again.';
            }
            echo json_encode($data_msg);
        }
    }
    
    //save watchlist Group
    public function ajax_save_group_watch_list() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            //mail
            $this->load->helper('email');
            $data=array('Asit','#222222222222222');
            sendMail($template='', $slug='sales_product', $to='sketch.dev24@gmail.com', $data);
            //end mail
            $group_id = $this->input->post('watch_group_id');
            $status = $this->input->post('watch_group_status');
            $res = $this->group->saveGroupWatch_list($group_id, $status);
            if ($res) {
                $data_msg['res'] = 'success';
                if ($status) {
                    $data_msg['message'] = 'Group added successfully within watchlist.';
                } else {
                    $data_msg['message'] = 'Group removed successfully from watchlist.';
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = 'Error occured please try again.';
            }
            echo json_encode($data_msg);
        }
    }

    /* ======================= CSV upload section ================= */


    /*
     * group add using csv upload
     */
    function save_the_group()
    {
        $data_msg = [];
        $data_msg['res'] = 'error';
        if(!empty($_FILES['formData']['name']))
        {
            $contactfileheadersChecking = fopen($_FILES['formData']['tmp_name'], "r");
            $headers = fgetcsv($contactfileheadersChecking, 1000, ";");
            $headers = explode(',', $headers[0]);
            $arrFileheaders = array(
                'Parent Name',
                'Group Name',
            );
            $getDifferents = array_diff($arrFileheaders, $headers);
            $notallowedGroups = array();
            if(count($getDifferents) == 0)  
            {
                $filename = date('Y_m_d_H_I').rand(100,1000).'group'.str_replace(' ','_',str_replace('-','_',$_FILES['formData']['name']));
                $filepath = getcwd().'/assets/uploads/';
                // $filepath = FCPATH.'assets/uploads/';
                $fullPath = $filepath.$filename;
                if(move_uploaded_file($_FILES['formData']['tmp_name'], $fullPath))
                {
                    $filePath = getcwd().'/assets/uploads/'.$filename;
                    // $filePath = FCPATH.'assets/uploads/'.$filename;
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
                        if($fileData[$countArr]['Group Name'] == $fileData[$striker]['Group Name'])
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
                        $getAlldata = $this->group->all_uploaded_data_by_order('pb_group','DESC','id',1);

                        if(empty($getAlldata))
                        {
                            $lastID = 1;
                        }else{
                            $lastID = $getAlldata[0]->id;
                        }

                        $max_id = $this->group->get_max_id('pb_group');
                        $this->db->trans_begin();
                        foreach ($getargscurrect as $allval) {
                            $arr = array('group_name'=>$allval['Group Name']);
                            $getExisting = $this->group->get_existing($arr,'pb_group');
                            $arrgs = array('group_name'=>$allval['Parent Name']);
                            $getParent = $this->group->get_tabledescription($arrgs,'pb_group');

                            if(!empty($getParent) && $getParent[0]->id>0){
                                if($getExisting == 1){
                                    $arrIDS[$st] = $lastID++;
                                    $arrPush[] = array(
                                        'group_name'=>$allval['Group Name'],
                                        'parent_id'=>$getParent[0]->id,
                                        'company_id'=>1,
                                        'group_code'=>'AG00'.++$max_id,
                                        'status'=>1,
                                        'deleted'=>0,
                                        'operation_status'=>1,
                                        'watch_list_status'=>0,
                                        'create_date' => $finans_start_date
                                    );
                                    $gData = array(
                                        'group_name'=>$allval['Group Name'],
                                        'parent_id'=>$getParent[0]->id,
                                        'company_id'=>1,
                                        'group_code'=>'AG00'.++$max_id,
                                        'status'=>1,
                                        'deleted'=>0,
                                        'operation_status'=>1,
                                        'watch_list_status'=>0,
                                        'create_date' => $finans_start_date
                                    );
                                    $this->group->singleInsertionFromFile($gData, 'pb_group');
                                    $st++;

                                }else{
                                    unset($arrPush);

                                }
                            }else{
                                if (!empty($allval['Parent Name'])) {
                                    $notallowedGroups[] = $allval['Parent Name'];
                                }
                            }

                            $x++;

                        }

                        if ($this->db->trans_status() === FALSE)
                        {
                            $this->db->trans_rollback();
                            $data_msg['msg'] = 'Please try later';
                        }
                        else
                        {
                            $this->db->trans_commit();
                            $csv_history = array(
                                'module' => 'group',
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
                        }

                        // if(!empty($arrPush)){
                        //     $inserted = $this->group->save_data_from_file($arrPush,'pb_group');
                        //     if($inserted)
                        //     {
                        //         $csv_history = array(
                        //             'module' => 'group',
                        //             'status' => 1,
                        //             'uploaded_by' => $this->session->userdata('admin_uid'),
                        //             'branch_id' => $this->session->userdata('branch_id'),
                        //             'uploaded_on' => date('Y-m-d H:i:s', time())
                        //         );
                        //         $this->companymodel->updateCsvUploadHistory($csv_history);
                        //         $data_msg['res'] = 'success';
                        //         $data_msg['msg'] = 'File and content saved successfully';
                        //         if (!empty($notallowedGroups)) {
                        //             $data_msg['msg'] .= '<br>Unknown Parents :: ' . implode(', ',$notallowedGroups);
                        //         }
                        //     }else{
                        //         $data_msg['msg'] = 'Please try later';
                        //     }
                        // }else{
                        //     $data_msg['msg'] = 'No new group found';
                        //     if (!empty($notallowedGroups)) {
                        //         $data_msg['msg'] .= '<br>Unknown Parents :: ' . implode(', ',$notallowedGroups);
                        //     }

                            
                            
                        // }
                    }
                    unlink($filePath);
                }

                if(!empty($notallowedGroups))
                {
                    $xyz = implode(',', $notallowedGroups);
                }

            }else{
                $data_msg['msg'] = 'File format does not match,Try again';
                // $data_msg['test'] = $headers;
            }
        }else{
            $data_msg['msg'] = 'A csv file is required';
        }

        echo json_encode($data_msg);
    }

    // csv upload for ledger
    function save_the_ledger()
    {
        $data_msg = [];
        $data_msg['res'] = 'error';

        if(!empty($_FILES['formData']['name']))
        {
            $contactfileheadersChecking = fopen($_FILES['formData']['tmp_name'], "r");
            $headers = fgetcsv($contactfileheadersChecking, 1000, ';');
            $headers = explode(',', $headers[0]);
            $arrFileheaders = array(
                'Group',
                'Ledger Name',
                'Tracking',
                'Bill Wise Details',
                'Service Activated',
                'Opening Balance',
                'Account Type',
                'Credit Days',
                'Credit Limit',
            );
            $getDifferents = array_diff($arrFileheaders, $headers);

            if(count($getDifferents) == 0)  
            {
                $filename = date('Y_m_d_H_I').rand(100,1000).'ledger'.str_replace(' ','_',str_replace('-','_',$_FILES['formData']['name']));
                $filepath = getcwd().'/assets/uploads/';
                $fullPath = $filepath.$filename;

                if(move_uploaded_file($_FILES['formData']['tmp_name'], $fullPath))
                {
                    $filePath = getcwd().'/assets/uploads/'.$filename;
                    $fileData =   $this->csvreader->parse_file($filePath);

                    $striker = 2;
                    $getargsduplicate = array();
                    $getargscurrect = array();
                    for($countArr=1;$countArr<count($fileData);$countArr++)
                    {
                        if($fileData[$countArr]['Ledger Name'] == $fileData[$striker]['Ledger Name'])
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
                        
                        $i = 0;
                        $m = 0;
                        $notallowedAccounttype = array();
                        $notallowedgroup = array();
                        $account_typeArr = array('dr','cr');
                        $this->db->trans_begin();
                        foreach ($getargscurrect as $allval) {
                            $arr = array('ladger_name'=>$allval['Ledger Name']);
                            $getExisting = $this->group->get_existing($arr,'pb_ladger');

                            $arrgs = array('group_name'=>$allval['Group']);
                            $getThegroup = $this->group->get_tabledescription($arrgs,'pb_group');

                            $getAlldata = $this->group->all_uploaded_data_by_order('pb_ladger','desc','id',1);
                            if($allval['Tracking'] == 'yes')
                            {
                                $tracking = 1;
                            }else{
                                $tracking = 2;
                            }


                            if($allval['Bill Wise Details'] == 'yes')
                            {
                                $billwisedetails = 1;
                            }else{
                                $billwisedetails = 2;
                            }

                            if($allval['Bill Wise Details'] == 'yes')
                            {
                                $creditdays = $allval['Credit Days'];
                                $creditLimit = $allval['Credit Limit'];
                            }else{
                                $creditdays = 0;
                                $creditLimit = 0;
                            }

                            if($allval['Service Activated'] == 'yes')
                            {
                                $serviceactivated = 1;
                            }else{
                                $serviceactivated = 2;
                            }

                            $account_type = strtolower($allval['Account Type']);

                            if($getExisting == 1){
                                if(!empty($getThegroup) && $getThegroup[0]->id>0){
                                    if(!empty($account_type)){
                                        if(in_array($account_type,$account_typeArr)){
                                            $arrPush[] = array(
                                                'group_id'=>$getThegroup[0]->id,
                                                'tracking_status'=>$tracking,
                                                'bill_details_status'=>$billwisedetails,
                                                'service_status'=>$serviceactivated,
                                                'account_type'=>ucfirst($account_type),
                                                'opening_balance'=>$allval['Opening Balance'],
                                                'credit_date'=>$creditdays,
                                                'credit_limit'=>$creditLimit,
                                                'ladger_name'=>$allval['Ledger Name'],
                                                'ledger_code'=>'L0'.($getAlldata[0]->id+1),
                                                'created_date' => $finans_start_date
                                            );

                                            $arrledgerAccount[] = array(
                                              'branch_id'=>1,
                                              'entry_id'=>0,
                                              'account'=>ucfirst($account_type),
                                              'balance'=>$allval['Opening Balance'],
                                              'discount_type'=>'0',
                                              'discount_amount'=>'0',
                                              'status'=>1,
                                              'create_date' => $finans_start_date
                                            );
                                        }else{
                                            $notallowedAccounttype[] = $allval['Ledger Name'];
                                        }
                                    }
                                }else{
                                    $notallowedgroup[] = $allval['Group'];
                                }
                                $i++;
                            }else{
                                unset($arrPush);

                            }
                        }
                        if(!empty($arrPush)){
                            $inserted = $this->group->save_data_from_file($arrPush,'pb_ladger');
                            if($inserted)
                            {
                                // unlink($filePath);
                                $csv_history = array(
                                    'module' => 'ledger',
                                    'status' => 1,
                                    'uploaded_by' => $this->session->userdata('admin_uid'),
                                    'branch_id' => $this->session->userdata('branch_id'),
                                    'uploaded_on' => date('Y-m-d H:i:s', time())
                                );
                                $this->companymodel->updateCsvUploadHistory($csv_history);
                                $data_msg['res'] = 'success';
                                $data_msg['msg'] = 'File and content saved successfully';
                                if(!empty($notallowedAccounttype))
                                {
                                    $notallowedAccounttypeledgers = '<br>Not allowed account type exist in ledger :: '.implode(',', $notallowedAccounttype);
                                    $data_msg['msg'] .= $notallowedAccounttypeledgers;
                                }

                                if(!empty($notallowedgroup))
                                {
                                    $notallowedAccountgroup = '<br>Not allowed group :: '.implode(',', $notallowedgroup);
                                    $data_msg['msg'] .= $notallowedAccountgroup;
                                }

                                for($loop = 0;$loop<count($arrledgerAccount);$loop++)
                                {
                                    if(!empty($inserted[$loop])){
                                        $arrledgerAccount[$loop]['ladger_id'] = $inserted[$loop];
                                    }
                                }

                                             
                                if(!empty($arrledgerAccount)){
                                    $inserted = $this->group->save_data_from_file($arrledgerAccount,'pb_ladger_account_detail');
                                }

                                if ($this->db->trans_status() === FALSE)
                                {
                                        $this->db->trans_rollback();
                                }
                                else
                                {
                                        $this->db->trans_commit();
                                }

                            }else{
                                $data_msg['msg'] = 'Please try later';
                            }
                        }else{
                            $data_msg['msg'] = 'No new ledger found';
                            if(!empty($notallowedAccounttype))
                            {
                                $notallowedAccounttypeledgers = '<br>Not allowed account type exist in ledger :: '.implode(',', $notallowedAccounttype);
                                $data_msg['msg'] .= $notallowedAccounttypeledgers;
                            }

                            if(!empty($notallowedgroup))
                            {
                                $notallowedAccountgroup = '<br>Not allowed group :: '.implode(',', $notallowedgroup);
                                $data_msg['msg'] .= $notallowedAccountgroup;
                            }
                            $data_msg['test'] = $headers;
                        }
                    }

                    unlink($filePath);
                }

            }else{
                $data_msg['msg'] = 'File format does not match,Try again';
                $data_msg['test'] = $headers;
            }
        }else{
            $data_msg['msg'] = 'A csv file is required';
        }

        echo json_encode($data_msg);
    }

    function downloadGroupsAsCSV()
    {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $query = $this->group->getCsvQuery("PG.group_name as `Parent Name`, G.group_name as `Group Name`", 'pb_group G', 'pb_group PG', 'G.parent_id = PG.id', 'left', array('G.status' => '1', 'G.deleted' => '0'), 'G.parent_id');
        $delimiter = ",";
        $newline = "\n";
        $enclosure = '';
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline, $enclosure);
        ob_clean();
        force_download('group.csv', $data);
    }

    function downloadLedgersAsCSV()
    {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $query = $this->group->getCsvQuery("G.group_name as `Group`, L.ladger_name as `Ledger Name`, (CASE WHEN L.tracking_status = 1 THEN 'yes' ELSE 'no' END) as Tracking,(CASE WHEN L.bill_details_status = 1 THEN 'yes' ELSE 'no' END) as `Bill Wise Details`, (CASE WHEN L.service_status = 1 THEN 'yes' ELSE 'no' END) as `Service Activated`, L.opening_balance as `Opening Balance`, L.account_type as `Account Type`, L.credit_date as `Credit Days`, L.credit_limit as `Credit Limit`", 'pb_ladger L', 'pb_group G', 'G.id = L.group_id', 'left', array('G.status' => '1', 'G.deleted' => '0'), 'L.ladger_name, G.group_name');
        $delimiter = ",";
        $newline = "\n";
        $enclosure = '';
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline, $enclosure);
        ob_clean();
        force_download('ledger.csv', $data);

        // $query = $this->group->getCsvQuery("G.group_name as `Group`, L.ladger_name as `Ledger Name`, (CASE WHEN L.tracking_status = 1 THEN 'yes' ELSE 'no' END) as Tracking,(CASE WHEN L.bill_details_status = 1 THEN 'yes' ELSE 'no' END) as `Bill Wise Details`, (CASE WHEN L.service_status = 1 THEN 'yes' ELSE 'no' END) as `Service Activated`, L.opening_balance as `Opening Balance`, L.account_type as `Account Type`, L.credit_date as `Credit Days`, L.credit_limit as `Credit Limit`", 'pb_ladger L', 'pb_group G', 'G.id = L.group_id', 'left', array('G.status' => '1', 'G.deleted' => '0'), 'L.ladger_name, G.group_name');

        // $this->group->outputTheCSV($query, 'ledger.csv');
    }


    /* ======================= CSV upload section ================= */

    public function checkCrDr()
    {
        $id = $this->input->post('group_id');
        // echo $id;exit();
        $group = $this->group->checkCrDr($id);
        switch ($group->id) {
            case 1:
                echo "Dr";
                break;
            case 2:
                echo "Dr";
                break;
            case 3:
                echo "Cr";
                break;
            case 4:
                echo "Cr";
                break;
            case 5:
                echo "Cr";
                break;
            
            default:
                # code...
                break;
        }
    }


}

/* End of file login.php */
/* Location: ./application/controllers/login.php */