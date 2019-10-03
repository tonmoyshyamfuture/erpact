<?php

class custom extends CI_Model {

    public function __construct() {

        parent::__construct();
        $this->load->database();
    }
    
    public function setDespatchDetails($entryId) {
        $data['despatch_doc_no'] = $this->input->post('despatch_doc_no');
        $data['despatch_through'] = $this->input->post('despatch_through');
        $data['courier_gstn'] = $this->input->post('courier_gstn');
        $data['destination'] = $this->input->post('destination');
        $data['bill_lr_rr'] = $this->input->post('bill_lr_rr');
        $data['bill_lr_rr_date'] = date("Y-m-d", strtotime(str_replace('/', '-', $this->input->post('bill_lr_rr_date'))));
        $data['motor_vehicle_no'] = $this->input->post('motor_vehicle_no');
        $data['entry_id'] = $entryId;
        $update_status = $this->db->get_where('despatch_details', array('entry_id' => $entryId))->row();
        if($update_status){
            $this->db->where('entry_id', $entryId);
            $this->db->update('despatch_details', $data);
        }else{
            $this->db->insert('despatch_details', $data);
        }
        
    }

    

}

?>
