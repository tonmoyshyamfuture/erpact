<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->model('admin/godownmodel');
        $this->load->model('accounts/group', 'groupmodel');
        $this->load->model('admin/companymodel');
        admin_authenticate();
    }

    /*
     * Godown Listing
     */
    public function index() {
        user_permission(116,'list');
        $data = array();
        $result = $this->godownmodel->getAllGodowns();
        $getsitename = getsitename();
        $data['godowns'] = $result;
        $data['count'] = $this->godownmodel->getCount();
        $data['csv_status'] = $this->companymodel->getCsvUploadHistory('godown');
        $this->layouts->set_title($getsitename . ' | Godowns');
        
        $this->layouts->render('admin/godown_list', $data, 'admin');
    }
    
    public function ajaxListing(){
        $draw       = $this->input->get_post('draw');
        $start      = $this->input->get_post('start');
        $length     = $this->input->get_post('length');
        $search     = $this->input->get_post('search')['value'];
        $result = $this->godownmodel->getAllGodowns($length,$start);
        foreach($result AS $res){
            $subArr = array();
            $permission = ua(116, 'edit');
            if($permission){
                $subArr[] = '<a  href="javascript:void(0)" class="edit-godown" data-id="' . $res['id'] . '">'. $res['name'].'</a>';
            }else{
                $subArr[] = $res['name'];
            }

            $subArr[] = $res['parent_name'];
            $subArr[] = (strlen($res['address']) > 50) ? substr($res['address'], 0, 50) . '...' : $res['address'] ;
            
            $permission = ua(116, 'delete');
            if ($permission){
                $subArr[] = '<div class="dropdown circle">
                            <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-ellipsis-v"></i></a>
                            <ul class="dropdown-menu tablemenu">
                                <li>
                                    <a onclick="delete_godown('.$res['id'].');" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </li>
                            </ul>
                        </div>';
            }else{
                $subArr[] ='';
            }
            
            $data['data'][]         = $subArr;
        }
        $data['draw']              = $draw;
        $data['recordsTotal']      = $this->godownmodel->getCount();
        $data['recordsFiltered']   = $this->godownmodel->getCount();
        echo json_encode($data);exit;
    }

    /*
     * Godown add/edit
     */
    public function godown_add($id = NULL) {
         user_permission(116,'add');
        $data = [];
        if (!empty($id))
            $param = 'E';
        else
            $param = 'A';

        $data['param'] = $param;
        if ($id) {
            $data['godown'] = $this->godownmodel->getGodownById($id);
        }
        if ($this->input->is_ajax_request()) {
            $data_message = [];

            if (isset($_POST['id']) && $_POST['id'] != '') {                
                $this->form_validation->set_rules('name', 'Name', 'trim|required');
            } else {                
                $this->form_validation->set_rules('name', 'Name', 'trim|required|is_unique[pb_godowns.name]');
            }

            if ($this->form_validation->run() == TRUE) {
                if (isset($_POST['id']) && $_POST['id'] != '') {
                    $id = $this->input->post('id');
                    $data = [
                        'name'      => $this->input->post('name'),
                        'parent_id' => $this->input->post('parent_id'),
                        'address'   => $this->input->post('address'),
                        'updated_date' => date("Y-m-d H:i:s")
                    ];
                    
                    // log update for godown update
                    $godown = $this->godownmodel->getGodownById($id);
                    $log = array(
                        'user_id' => $this->session->userdata('admin_uid'),
                        'branch_id' => $this->session->userdata('branch_id'),
                        'module' => 'godowns',
                        'action' => '`' . $data['name'] . '` <b>edited</b>',
                        'previous_data' => json_encode($godown),
                        'performed_at' => date('Y-m-d H:i:s', time())
                    );

                    $this->godownmodel->saveData($data, $id, $log);
                    $data_message['res'] = "success";
                    $data_message['message'] = "Godown updated successfully!";
                } else {
                    $data = [
                        'name'          => $this->input->post('name'),
                        'parent_id'     => $this->input->post('parent_id'),
                        'address'       => $this->input->post('address'),
                        'user_id'       => $this->session->userdata('admin_uid'),
                        'branch_id'     => $this->session->userdata('branch_id'),
                        'active_status' => 1,
                        'delete_status' => 0,
                        'created_date'  => date("Y-m-d H:i:s")
                    ];
                    
                    // log update for godown insertion
                    $log = array(
                        'user_id' => $this->session->userdata('admin_uid'),
                        'branch_id' => $this->session->userdata('branch_id'),
                        'module' => 'godowns',
                        'action' => '`' . $data['name'] . '` <b>created</b>',
                        'previous_data' => '',
                        'performed_at' => date('Y-m-d H:i:s', time())
                    );

                    $this->godownmodel->saveData($data, $id = NULL, $log);

                    $data_message['res'] = "success";
                    $data_message['message'] = "Godown added successfully!";
                }
            }else{
                $data_message['res'] = "save_err";
                $data_message['message'] = validation_errors();
            }
            echo json_encode($data_message);
            exit();
        }
        $data['all_godowns'] = $this->godownmodel->getAllGodowns();
        
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Godown Add');
        $this->layouts->render('admin/godown_add', $data, 'admin');
    }

    /*
     * Godown Delete
     */
    public function godown_delete($id) {
        $data = [
            'delete_status' => '1',
            'updated_date' => date("Y-m-d H:i:s")
        ];
        
        $godown = $this->godownmodel->getGodownById($id);
        $log = array(
            'user_id' => $this->session->userdata('admin_uid'),
            'branch_id' => $this->session->userdata('branch_id'),
            'module' => 'godowns',
            'action' => '`' . $godown['name'] . '` <b>deleted</b>',
            'previous_data' => json_encode($godown),
            'performed_at' => date('Y-m-d H:i:s', time())
        );

        $this->godownmodel->saveData($data, $id, $log);
        $this->session->set_flashdata('successmessage', "Godown deleted successfully!");
        redirect(base_url('admin/godown'));
    }

    /*
     * Godown add using csv upload
     */
    function save_the_godown()
    {
        $data_msg = [];
        $data_msg['res'] = 'error';
        if(!empty($_FILES['formData']['name']))
        {
            $contactfileheadersChecking = fopen($_FILES['formData']['tmp_name'], "r");
            $headers = fgetcsv($contactfileheadersChecking, 1000, ";");
            $headers = explode(',', $headers[0]);
            $arrFileheaders = array(
                'Name'
            );
            $getDifferents = array_diff($arrFileheaders, $headers);
            $notallowedGroups = array();
            if(count($getDifferents) == 0)  
            {
                $filename = date('Y_m_d_H_I').rand(100,1000).'godown'.str_replace(' ','_',str_replace('-','_',$_FILES['formData']['name']));
                $filepath = getcwd().'/assets/uploads/';
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
                        if($fileData[$countArr]['Name'] == $fileData[$striker]['Name'])
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
                        $x = 1;
                        $lastID = 1;
                        $st = 0;
                        $getAlldata = $this->groupmodel->all_uploaded_data_by_order('pb_godowns','DESC','id',1);

                        if(empty($getAlldata))
                        {
                            $lastID = 1;
                        }else{
                            $lastID = $getAlldata[0]->id;
                        }

                        foreach ($getargscurrect as $allval) {
                            $arr = array('name'=>$allval['Name']);
                            $getExisting = $this->groupmodel->get_existing($arr,'pb_godowns');

                            if($getExisting == 1){
                                $arrIDS[$st] = $lastID++;
                                $arrPush[] = array(
                                    'name'=>$allval['Name'],
                                    'user_id' => $this->session->userdata('admin_uid'),
                                    'branch_id' => $this->session->userdata('branch_id'),
                                    'active_status' => 1,
                                    'delete_status' => 0,
                                    'created_date' => date("Y-m-d H:i:s")
                                );
                                $st++;

                            }else{
                                unset($arrPush);
                                $notallowedGroups[] = $allval['Name'];
                            }

                            $x++;

                        }

                        if(!empty($arrPush)){
                            $inserted = $this->groupmodel->save_data_from_file($arrPush,'pb_godowns');
                            if($inserted)
                            {
                                $csv_history = array(
                                    'module' => 'godown',
                                    'status' => 1,
                                    'uploaded_by' => $this->session->userdata('admin_uid'),
                                    'branch_id' => $this->session->userdata('branch_id'),
                                    'uploaded_on' => date('Y-m-d H:i:s', time())
                                );
                                $this->companymodel->updateCsvUploadHistory($csv_history);
                                $data_msg['res'] = 'success';
                                $data_msg['msg'] = 'File and content saved successfully';
                                if (!empty($notallowedGroups)) {
                                    $data_msg['msg'] .= '<br>Duplicate godowns :: ' . implode(', ',$notallowedGroups);
                                }
                            }else{
                                $data_msg['msg'] = 'Please try later';
                            }
                        }else{
                            $data_msg['msg'] = 'No new godowns found';
                            if (!empty($notallowedGroups)) {
                                $data_msg['msg'] .= '<br>Duplicate godowns :: ' . implode(', ',$notallowedGroups);
                            }
                            
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


    function downloadGodownsAsCSV()
    {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $query = $this->groupmodel->getCsvQuery("U.name as `Name`", 'pb_godowns U', '', '', '', array('U.active_status' => 1,'U.delete_status' => 0), 'U.id');
        $delimiter = ",";
        $newline = "\n";
        $enclosure = '';
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline, $enclosure);
        ob_clean();
        force_download('godown.csv', $data);
    }

    public function getEditGodown()
    {
        $id = $this->input->post('godown_id');
        $data['godowns'] = $this->godownmodel->getAllGodowns();
        $data['editGodown'] = $this->godownmodel->getGodownById($id);
        $formHtml = $this->load->view('admin/edit_godown', $data, false);
        echo $formHtml;
    }

    public function getAddGodown()
    {
        $data['godowns'] = $this->godownmodel->getAllGodowns();
        $formHtml = $this->load->view('admin/add_godown', $data, false);
        echo $formHtml;
    }


}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */
