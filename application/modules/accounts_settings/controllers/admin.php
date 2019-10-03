<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->model('admin/accountsettingsmodel');
        admin_authenticate();
    }

    public function index() {
        user_permission(104,'edit');
        
        $this->load->helper('text');
        $data = array();
        $result = $this->accountsettingsmodel->loadAll();
        $data['currency'] = $this->accountsettingsmodel->getAllCurrency();
        $data['state'] = $this->accountsettingsmodel->getAllState();
        $data['countries'] = $this->accountsettingsmodel->getAllCountries();
        $data['settings'] = $result;

        $data['standardFormat'] = $this->accountsettingsmodel->getStandardFormatData();
        $data['currency'] = $this->accountsettingsmodel->getAllCurrency();
        $data['timezone'] = $this->accountsettingsmodel->getAllTimezone();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Settings');
        $this->layouts->render('admin/settings', $data, 'admin');
    }

    public function settingsmodify() {
       
        if(isset( $_FILES['company_logo'])){
        
        $file = $_FILES['company_logo'];
        if (isset($_FILES['company_logo']) && isset($file['name']) && $file['name'] != '') {
            $imagename = $file['name'];
            $imagearr = explode('.', $imagename);
            $ext = end($imagearr);
            $newimage = time() . rand() . "." . $ext;
            if ($ext == "jpg" or $ext == "jpeg" or $ext == "png" or $ext == "bmp") {
                
                /*
                 * somnath
                 * if thumbs directory is not there then create it
                 * otherwise give the write permission
                 */
                if(!file_exists(FCPATH.'assets/uploads/thumbs')){
                    mkdir(FCPATH.'assets/uploads/thumbs', 0777);
                }else{
                    chmod(FCPATH.'assets/uploads', 0777);
                    chmod(FCPATH.'assets/uploads/thumbs', 0777);
                }                
                
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
                
                $current_logo = $this->accountsettingsmodel->getCurrentLogo();
                if(!empty($current_logo)){
                   unlink(FCPATH . 'assets/uploads/thumbs/'.$current_logo); 
                   unlink(FCPATH . 'assets/uploads/'.$current_logo); 
                }
                
            } 
        } 
        }
        $data['company_name'] = $this->input->post('company_name');
        $data['mailing_name'] = $this->input->post('mailing_name');
        $data['mailing_name'] = $this->input->post('company_name');
        $data['email'] = $this->input->post('email');
        // $data['country'] = $this->input->post('country');
        $data['country_id'] = $this->input->post('country_id');
        $data['state_id'] = $this->input->post('state_id');
        $data['appt_number'] = $this->input->post('appt_number');
        $data['street_address'] = $this->input->post('street_address');
        $data['city_name'] = $this->input->post('city_name');
        $data['zip_code'] = $this->input->post('zip_code');
        $data['telephone'] = $this->input->post('telephone');
        $data['mobile'] = $this->input->post('mobile');
//        $data['vat']=$this->input->post('vat');
//        $data['cst']=$this->input->post('cst');
        $data['company_type']=$this->input->post('company_type');
        if($data['company_type'] == 3){
            $data['gst']="";
        }else{
            $data['gst']=$this->input->post('gst');
        }
//        $data['gst']=$this->input->post('gst');
        $data['pan']=$this->input->post('pan');
//        $data['cin']=$this->input->post('cin');
//        $data['service_tax']=$this->input->post('service_tax');
//        $data['tan']=$this->input->post('tan');
        $data['updated_date']=date("Y-m-d H:i:s");
        $id = 1;
        $updflg = $this->accountsettingsmodel->modifyData($data, $id);
        $finalcial_year_from = explode('/', $this->input->post('finalcial_year_from'));
        $data1['finalcial_year_from'] = $finalcial_year_from[1] . '-' . $finalcial_year_from[0] . '-' . '01';
        $books_begining_form = explode('/', $this->input->post('books_begining_form'));
        $data1['books_begining_form'] = $books_begining_form[1] . '-' . $books_begining_form[0] . '-' . '01';
        $data1['base_currency'] = $this->input->post('base_currency');
        $data1['formal_name'] = $this->input->post('formal_name');
        $data1['base_currency_symbol'] = $this->input->post('base_currency_symbol');
        $data1['decimal_places'] = $this->input->post('decimal_places');
        $data1['time_zone_id'] = $this->input->post('time_zone_id');
        $data1['updated_date']=date("Y-m-d H:i:s");

        $id1 = 1;
        $this->db->trans_begin();
        $updflg = $this->accountsettingsmodel->modifyStandardFormatData($data1, $id1);
        $this->accountsettingsmodel->modifySaasData($data);

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            $this->session->set_flashdata('successmessage', 'Settings updated successfully');
        } else {
            $this->db->trans_rollback();
            $this->session->set_flashdata('errormessage', 'Oops! Something went wrong');
        }
        $redirect = site_url('admin/accounts-settings');
        redirect($redirect);
    }

    public function standard_format() {

        if (isset($_POST) && !empty($_POST)) {
            $finalcial_year_from = explode('/', $this->input->post('finalcial_year_from'));
            $data1['finalcial_year_from'] = $finalcial_year_from[2] . '-' . $finalcial_year_from[1] . '-' . $finalcial_year_from[0];
            $books_begining_form = explode('/', $this->input->post('books_begining_form'));
            $data1['books_begining_form'] = $books_begining_form[2] . '-' . $books_begining_form[1] . '-' . $books_begining_form[0];
            $data1['base_currency'] = $this->input->post('base_currency');
            $data1['formal_name'] = $this->input->post('formal_name');
            $data1['base_currency_symbol'] = $this->input->post('base_currency_symbol');
            $data1['decimal_places'] = $this->input->post('decimal_places');
            $data1['time_zone_id'] = $this->input->post('time_zone_id');


            $id = 1;

            $updflg = $this->accountsettingsmodel->modifyStandardFormatData($data1, $id);

            if (!empty($updflg)) {
                $this->session->set_flashdata('successmessage', 'Settings updated successfully');
            } else {
                $this->session->set_flashdata('errormessage', 'Oops! Something went wrong');
            }
        }
        $result = $this->accountsettingsmodel->getStandardFormatData();
        $data['currency'] = $this->accountsettingsmodel->getAllCurrency();
        $data['timezone'] = $this->accountsettingsmodel->getAllTimezone();
        $data['settings'] = $result;
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Standard Format');
        $this->layouts->render('admin/standard-format', $data, 'admin');
    }

    public function tracking() {
        $this->load->model('accounts/group');
        $ledgers = $this->accountsettingsmodel->get_ledger_tracking();
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
            $group_track = 0;
            foreach ($ledgers as $ledger) {
                if ($value == $ledger['group_name']) {
                    //print_r($ledger);
                    $ledger_list[$value][] = $ledger;
                    //$ledger_list['group_track'][] = $group_track + $ledger['total_use'];   
                }
            }
        }
        // echo '<pre>';
        // print_r($ledger_list);die();
        $data['ledger_list'] = $ledger_list;

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Tracking List');
        $this->layouts->render('admin/tracking_list', $data, 'admin');
    }

    public function tracking_details($id) {
        $entries = $this->accountsettingsmodel->getEntry($id);

        $entry_details = array();
        foreach ($entries as $entry) {
            if ($entry['entry_id'] == 0) {
                continue;
            }
            $entry_details[] = $this->getLadgerValue($entry['entry_id'], $id);
        }
        $data['entry_details'] = array_count_values($entry_details);
        $data['ladger_name'] = $entries[0]['ladger_name'];
        $data['total_used'] = count($entry_details);

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Tracking List');
        $this->layouts->render('admin/tracking_details', $data, 'admin');
    }

    public function getLadgerValue($entry_id, $ladger_id) {
        $ladgers = $this->accountsettingsmodel->getLadgerByEntryId($entry_id, $ladger_id);
        return $ladgers['ladger_name'];
    }

    public function dashboard_setting()
    {
        $data = [];
        if ($this->input->is_ajax_request()) {
            $data_message = [];
            $type = $this->input->post('type');

            $total_receivable   = $this->input->post('total_receivable');
            $total_payable      = $this->input->post('total_payable');
            $cash_flow          = $this->input->post('cash_flow');
            $fund_flow          = $this->input->post('fund_flow');
            $watchlist          = $this->input->post('watchlist');
            $sales_summary      = $this->input->post('sales_summary');
            $purchase_summary   = $this->input->post('purchase_summary');

            
            if (empty($total_receivable) && empty($total_payable) && empty($cash_flow) && empty($fund_flow) && empty($sales_summary) && empty($purchase_summary) && empty($watchlist) ) {

                $data_message['res'] = "error";
                $data_message['message'] = 'Please select any option to proceed';
                echo json_encode($data_message);
                exit();
            }
            

            if (isset($_POST['id']) && $_POST['id'] != '') {
                $id = $this->input->post('id');
                $data = [
                    'total_receivable'  => $total_receivable,
                    'total_payable'     => $total_payable,
                    'cash_flow'         => $cash_flow,
                    'fund_flow'         => $fund_flow,
                    'sales_summary'     => $sales_summary,
                    'purchase_summary'  => $purchase_summary,
                    'watchlist'         => $watchlist,
                    'user_id'           => $this->session->userdata('admin_uid'),
                    'branch_id'         => $this->session->userdata('branch_id')
                ];

                $this->accountsettingsmodel->modifyDashboardSetting($data, $id);
                $data_message['res'] = "success";
                $data_message['message'] = "Dashboard setting updated successfully!";
            } else {
                $data = [
                    'total_receivable'  => $total_receivable,
                    'total_payable'     => $total_payable,
                    'cash_flow'         => $cash_flow,
                    'fund_flow'         => $fund_flow,
                    'sales_summary'     => $sales_summary,
                    'purchase_summary'  => $purchase_summary,
                    'watchlist'         => $watchlist,
                    'user_id'           => $this->session->userdata('admin_uid'),
                    'branch_id'         => $this->session->userdata('branch_id')
                ];

                $this->accountsettingsmodel->modifyDashboardSetting($data, $id = NULL);

                $data_message['res'] = "success";
                $data_message['message'] = "Dashboard setting added successfully!";
            }
            echo json_encode($data_message);
            exit();
        }
        $data['setting'] = $this->accountsettingsmodel->getDashboardSetting();
        
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Dashboard Setting');
        $this->layouts->render('admin/dashboard_setting', $data, 'admin');
    }



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
