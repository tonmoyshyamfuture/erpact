<?php

class trackingmodel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getTracking($where) {
        $this->db->select('tracking.*,PT.tracking_name AS parent_tracking_name');
        $this->db->where($where);
        $this->db->join('tracking AS PT', 'tracking.parent_id = PT.id', 'left');
        $this->db->order_by("parent_id", "asc");
        $query = $this->db->get('tracking');
        return $query->result_array();
    }

    public function getTrackingById($where) {
        $this->db->select('tracking.*,PT.tracking_name AS parent_tracking_name');
        $this->db->where($where);
        $this->db->join('tracking AS PT', 'tracking.parent_id = PT.id');
        $query = $this->db->get('tracking');
        return $query->row_array();
    }

    public function saveTracking($data = NULL) {
        if ($this->input->post('id') == "") {
            $this->db->insert('tracking', $data);
            return $this->db->insert_id();
        } else {
            $this->db->where('id', $data['id']);
           return $this->db->update('tracking', $data);
        }
    }

    public function updateTrackingCode($data = NULL, $last_id = NULL) {
        $this->db->where('id', $last_id);
        $this->db->update('tracking', $data);
    }

    public function getAllParentTrackingNameForJson($where, $group) {
        $this->db->select('tracking.tracking_name');
        $this->db->from('tracking');
        $this->db->like('tracking_name', $group, 'after');
        $this->db->where($where);
        $query = $this->db->get();
        $list = $query->result_array();
        return !empty($list) ? $list : array();
    }

    public function getTrackingId($tracking) {
        $record = $this->db->select('id')
                ->from('tracking')
                ->like('tracking_name', $tracking)
                ->get();
        return $record->row_array();
    }

    public function getGroupCodeStatus() {
        $this->db->select('group_code_status');
        $query = $this->db->get('account_configuration');
        return $query->row_array();
    }

    public function getBillNameForJson($ledger_id, $bill_name, $where = NULL) {
        if (is_null($where)) {
            $query = $this->db->query("SELECT DISTINCT B.bill_name, B.ledger_id ,(SELECT SUM(bill_amount) FROM pb_billwish_details WHERE  ledger_id =" . $ledger_id . " AND B.bill_name =  pb_billwish_details.bill_name GROUP BY dr_cr HAVING dr_cr = 'Cr' ) AS cr_sum, (SELECT SUM(bill_amount) FROM pb_billwish_details WHERE  ledger_id =" . $ledger_id . " AND B.bill_name =  pb_billwish_details.bill_name GROUP BY dr_cr HAVING dr_cr = 'Dr' ) AS dr_sum  FROM pb_billwish_details AS B WHERE B.ledger_id =" . $ledger_id . " AND B.bill_name LIKE '" . $bill_name . "%'");
        } else {
            $query = $this->db->query("SELECT DISTINCT B.bill_name, B.ledger_id ,(SELECT SUM(bill_amount) FROM pb_billwish_details WHERE  ledger_id =" . $ledger_id . " AND B.bill_name =  pb_billwish_details.bill_name GROUP BY dr_cr HAVING dr_cr = 'Cr' ) AS cr_sum, (SELECT SUM(bill_amount) FROM pb_billwish_details WHERE  ledger_id =" . $ledger_id . " AND B.bill_name =  pb_billwish_details.bill_name GROUP BY dr_cr HAVING dr_cr = 'Dr' ) AS dr_sum  FROM pb_billwish_details AS B WHERE B.ledger_id =" . $ledger_id . " AND B.bill_name LIKE '" . $bill_name . "%'" . $where);
        }
        $list = $query->result_array();
        return !empty($list) ? $list : array();
    }

    public function getBillByBillname($bill_name, $ledger_id) {
        $query = $this->db->query("SELECT DISTINCT B.bill_name,  B.ledger_id , B.credit_days ,B.credit_date ,(SELECT SUM(bill_amount) FROM pb_billwish_details WHERE  ledger_id =" . $ledger_id . " AND B.bill_name =  pb_billwish_details.bill_name GROUP BY dr_cr HAVING dr_cr = 'Cr' ) AS cr_sum, (SELECT SUM(bill_amount) FROM pb_billwish_details WHERE  ledger_id =" . $ledger_id . " AND B.bill_name =  pb_billwish_details.bill_name GROUP BY dr_cr HAVING dr_cr = 'Dr' ) AS dr_sum  FROM pb_billwish_details AS B WHERE B.ledger_id = " . $ledger_id . " AND B.bill_name LIKE '" . $bill_name . "%'");
        return $query->row_array();
    }

    public function getGetLevelByParentId($parentId) {
        $record = $this->db->select('id,level')
                ->from('tracking')
                ->where('id', $parentId)
                ->get();
        return $record->row_array();
    }

    //check entry existance
    public function checkEntryExist($tracking_id) {
        $query = $this->db->select('id')
                ->from(tablename('tracking_details'))
                ->where('tracking_id =', $tracking_id)
                ->get();
        return $row = $query->result();
    }

    public function deleteTracking($tracking_id) {
        $data = array('deleted' =>1);
        $this->db->where('id', $tracking_id);
        return $this->db->update('tracking', $data);
    }

}
