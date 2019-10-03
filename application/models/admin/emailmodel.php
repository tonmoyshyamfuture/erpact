<?php

class emailmodel extends CI_Model {

    public function __construct() {

        parent::__construct();
        $this->load->database();
    }

    public function getDBEmailData($slug) {
        $this->db->select('*');
        $this->db->from('email_template');
        $this->db->where('email_template_key', $slug);
        $query = $this->db->get();
        return $query->row();
    }

}
