<?php

class post_dated_entry extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('post_dated_entry_model');
    }

    public function index() {
//        if (!$this->input->is_cli_request()) {
//            echo "This script can only be accessed via the command line" . PHP_EOL;
//            return;
//        }
        $date = date("Y-m-d");
        $entry_id_arr = [];
        $all_post_dated_entry = $this->post_dated_entry_model->get_all_post_dated_entry($date);
        if (!empty($all_post_dated_entry)) {
            foreach ($all_post_dated_entry as $post_entry) {
                $entry_id_arr[] = $post_entry->id;
            }
        }
        $data = array('deleted' => 0);
        if ($entry_id_arr) {
            $this->db->trans_begin();
            //entry
            $this->db->where_in('id', $entry_id_arr);
            $this->db->update(tablename('entry'), $data);
            //ledger account details
            $this->db->where_in('entry_id', $entry_id_arr);
            $this->db->update(tablename('ladger_account_detail'), $data);
            //bank_details
            $this->db->where_in('entry_id', $entry_id_arr);
            $this->db->update(tablename('bank_details'), $data);
            //billwish_details
            $this->db->where_in('entry_id', $entry_id_arr);
            $this->db->update(tablename('billwish_details'), $data);
            //tracking_details
            $this->db->where_in('entry_id', $entry_id_arr);
            $this->db->update(tablename('tracking_details'), $data);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        }
    }

}
