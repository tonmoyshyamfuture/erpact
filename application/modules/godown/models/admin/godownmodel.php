<?php

class Godownmodel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('front/usermodel', 'currentusermodel');
        $this->load->database();
    }

    public function getAllGodowns($limit = 10,$offset = 0) {
        $query = $this->db->select('g.*, p.name as parent_name')
                ->from(tablename('godowns as g'))
                ->join('godowns as p', 'p.id = g.parent_id', 'left')
                ->where(array('g.active_status' => 1, 'g.delete_status' => 0))
                ->limit($limit,$offset)
                ->get();
        return $row = $query->result_array();
    }
    
    public function getAllGodownsAjax($limit = 10,$offset = 0) {
        $query = $this->db->select('*')
                ->from(tablename('godowns'))
                ->where(array('active_status' => 1, 'delete_status' => 0))
                ->limit($limit,$offset)
                ->get();
        return $row = $query->result();
    }
    
    public function getCount(){
        $query = $this->db->get_where('godowns', array('active_status' => 1, 'delete_status' => 0));
        return $query->num_rows();
    }

    public function getGodownById($id = NULL) {
        $query = $this->db->select('*')
                ->from(tablename('godowns'))
                ->where('id', $id)
                ->get();
        return $row = $query->row_array();
    }

    public function saveData($data = array(), $id = NULL, $log) {
        $this->currentusermodel->updateLog($log);
        if ($id) {
            $this->db->where('id', $id);
            $this->db->update(tablename('godowns'), $data);
            return true;
        } else {
            $this->db->insert(tablename('godowns'), $data);
            return true;
        }
    }

}
