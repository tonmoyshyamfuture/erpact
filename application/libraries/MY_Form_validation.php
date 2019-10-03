<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

    function __construct() {
        parent::__construct();
    }

    function error_array() {
        return $this->_error_array;
    }

    public function is_unique($str, $field) {
        $field_ar = explode('.', $field);
        $query = $this->CI->db->get_where($field_ar[0], array($field_ar[1] => $str), 1, 0);
       
        if ($query->num_rows() === 0) {
            return TRUE;
        }

        return FALSE;
    }

    public function is_unique_update($str, $field) {
        $field_ar = explode('.', $field);
        $this->CI = & get_instance();
        $bom_number = $this->CI->input->post('entry_number');
        if ($bom_number) {
            $query = $this->CI->db->get_where($field_ar[0], array($field_ar[1] => $str, 'bill_id !=' => $bom_number), 1, 0);
        } else {
            $query = $this->CI->db->get_where($field_ar[0], array($field_ar[1] => $str), 1, 0);
        }
            $this->CI->form_validation->set_message('is_unique_update', "The %s field  is already being used.");
        if ($query->num_rows() === 0) {
            return TRUE;
        } else {    
            return FALSE;
        }
    }

    function alpha_dash_space($str) {
        $this->CI = & get_instance();
        $this->CI->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha characters & White spaces.');
        if (!preg_match("/^([-a-z_ ])+$/i", $str)) { // do your validations
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function is_unique_update_ledger($str, $field) {
        $this->CI = & get_instance();
        $field_ar = explode('.', $field);
        $ledger_id = $this->CI->input->post('id');
        if ($ledger_id) {
            $query = $this->CI->db->get_where($field_ar[0], array($field_ar[1] => $str, 'id !=' => $ledger_id), 1, 0);
        } else {
            $query = $this->CI->db->get_where($field_ar[0], array($field_ar[1] => $str), 1, 0);
        }
        $this->CI->form_validation->set_message('is_unique_update_ledger', 'The %s field already exist.');
        if ($query->num_rows() === 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    function check_postdated_date($str) {
        $this->CI = & get_instance();
        $this->CI->form_validation->set_message('check_postdated_date', 'The transaction date field may only contain future date for postdated entry.');
         $tr_date = date("Y-m-d", strtotime(str_replace('/', '-', $str)));
        if ($tr_date<=date("Y-m-d")) { // do your validations
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function is_unique_hsn_number($str, $field) {
        $this->CI = & get_instance();
        $field_ar = explode('.', $field);
        $hsn_id = $this->CI->input->post('id');
        if ($hsn_id) {
            $query = $this->CI->db->get_where($field_ar[0], array($field_ar[1] => $str, 'id !=' => $hsn_id), 1, 0);
        } else {
            $query = $this->CI->db->get_where($field_ar[0], array($field_ar[1] => $str), 1, 0);
        }
        $this->CI->form_validation->set_message('is_unique_hsn_number', 'The %s field already exist.');
        if ($query->num_rows() === 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>