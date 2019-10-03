<?php

class custommodel extends CI_Model {

    public function __construct() {

        parent::__construct();
        $this->load->database();
    }

    public function getAllGroups() {
        $query = $this->db->query("SELECT G.*, PG.group_name as parent_group_name,PG.group_code as parent_group_code FROM pb_group AS G LEFT JOIN pb_group AS PG ON G.parent_id = PG.id WHERE G.status = '1' AND G.deleted = '0' ORDER BY G.parent_id");
        return $query->result_array();
    }

    public function getLedgerFinal($ledger, $ledger_array) {
        $this->db->select('L.id, L.ladger_name,LD.current_closing_balance,LD.account, L.ledger_code');
        $this->db->from('ladger L');
        // $this->db->like('L.ladger_name', $ledger, 'after');
        $this->db->join('ladger_account_detail LD', 'LD.ladger_id = L.id', 'left');
        $this->db->where(array('L.status' => '1', 'L.deleted' => '0'));
        $this->db->where(array('LD.entry_id' => 0, 'LD.branch_id' => $this->session->userdata('branch_id')));
        $this->db->where('L.branch_id !=', $this->session->userdata('branch_id'));
        $this->db->where_not_in('L.group_id', $ledger_array);
        $this->db->where("(L.ladger_name LIKE '%".$ledger."%' OR L.ledger_code LIKE '%".$ledger."%')", NULL, FALSE);
        $query = $this->db->get();

        // echo $this->db->last_query(); die();
        return $query->result();
    }

    public function getLedgerFinalIn($ledger, $ledger_array) {
        $this->db->select('L.id, L.ladger_name,L.ledger_code,LD.current_closing_balance,LD.account');
        $this->db->from('ladger L');
        $this->db->join('ladger_account_detail LD', 'LD.ladger_id = L.id', 'left');
        $this->db->like('L.ladger_name', $ledger, 'after');
        $this->db->where(array('L.status' => '1', 'L.deleted' => '0'));
        $this->db->where(array('LD.entry_id' => 0, 'LD.branch_id' => $this->session->userdata('branch_id')));
        $this->db->where_in('L.group_id', $ledger_array);
        $this->db->where('L.branch_id !=', $this->session->userdata('branch_id'));
        $query = $this->db->get();
        return $query->result();
    }

    public function getGroups($array_group) {

        $all_group_ids = '';
        for ($i = 0; $i < count($array_group); $i++) {
            $all_group_ids.=$array_group[$i] . ',';
            $sql = "SELECT GROUP_CONCAT(lv SEPARATOR ',') as ids FROM ( SELECT @pv:=(SELECT GROUP_CONCAT(id SEPARATOR ',') FROM pb_group WHERE FIND_IN_SET(parent_id,@pv)) AS lv FROM pb_group JOIN (SELECT @pv:=" . $array_group[$i] . ") tmp ) a";
            $query = $this->db->query($sql);
            $res = $query->row();
            if (!empty($res->ids)) {
                $all_group_ids.=$res->ids . ',';
            }
        }
        $all_group_ids = rtrim($all_group_ids, ',');

        return $all_group_ids;
    }


    public function getAllLedger($all_group_bank_cash) {
        $sql = "SELECT id FROM pb_ladger WHERE group_id IN (" . $all_group_bank_cash . ")";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getLedger($array_group) {

        if (isset($total_group_array)) {
            unset($total_group_array);
        }

        $total_group_array = array();
        for ($i = 0; $i < count($array_group); $i++) {

            $sql = "SELECT GROUP_CONCAT(lv SEPARATOR ',') as ids FROM ( SELECT @pv:=(SELECT GROUP_CONCAT(id SEPARATOR ',') FROM pb_group WHERE FIND_IN_SET(parent_id,@pv)) AS lv FROM pb_group JOIN (SELECT @pv:=" . $array_group[$i] . ") tmp ) a";

            $query = $this->db->query($sql);
            //$numrows = $query->num_rows();
            $res = $query->row();

            if (!empty($res->ids)) {

                array_push($total_group_array, $res->ids);
            }
        }


        return $total_group_array;
    }

    public function getTType($ledgerId) {
        $this->db->select('account_type');
        $this->db->from('ladger');
        $this->db->where(array('id' => $ledgerId));

        $query = $this->db->get();
        return $query->row();
    }

    public function getLedgerName($ledgerId) {

        $this->db->select('ladger_name,branch_id,group_id');

        $this->db->from('ladger');
        $this->db->where(array('id' => $ledgerId));

        $query = $this->db->get();
        return $query->row();
    }


    public function addBranchTransaction($data) {
        $this->db->insert('branch_entry_notification', $data);
    }

    public function updateBranchTransaction($branch_entry_no) {
        $data = array('status' => 2, 'updated_at' => date('Y-m-d H:i:s'));
        $this->db->where('entry_no', $branch_entry_no);
        $this->db->update('branch_entry_notification', $data);
    }


    public function getTTracking($ledgerId) {
        $this->db->select('tracking_status');
        $this->db->from('ladger');
        $this->db->where(array('id' => $ledgerId));

        $query = $this->db->get();
        return $query->row();
    }

    public function getTBilling($ledgerId) {
        $this->db->select('bill_details_status');
        $this->db->from('ladger');
        $this->db->where(array('id' => $ledgerId));

        $query = $this->db->get();
        return $query->row();
    }


    public function getServiceStatus($ledgerId) {

        $this->db->select('service_status');
        $this->db->from('ladger');
        $this->db->where(array('id' => $ledgerId));

        $query = $this->db->get();
        return $query->row();
    }

    public function getNewRefLedgerDetails($id) {
        $this->db->select('credit_date, credit_limit');
        $this->db->from('ladger');
        $this->db->where(array('id' => $id));

        $query = $this->db->get();
        return $query->row();
    }

    public function getDiffDrCrBillingSales($id) {

        $query = $this->db->query('SELECT ( SUM(IF(`dr_cr` = "Dr", `bill_amount`, 0)) - SUM(IF(`dr_cr` = "Cr", `bill_amount`, 0)) ) AS diff FROM `pb_billwish_details` WHERE `ledger_id` = "' . $id . '" ');

        $result = $query->row();
        return $result;
    }

    public function getDiffDrCrBillingPurchase($id) {

        $query = $this->db->query('SELECT ( SUM(IF(`dr_cr` = "Cr", `bill_amount`, 0)) - SUM(IF(`dr_cr` = "Dr", `bill_amount`, 0)) ) AS diff FROM `pb_billwish_details` WHERE `ledger_id` = "' . $id . '" ');

        $result = $query->row();
        return $result;
    }

    public function getTTrackingName($tracking, $trackingArr) {
        $this->db->select('id, tracking_name');
        $this->db->from('pb_tracking');
        if (count($trackingArr) > 0) {
            $this->db->where_not_in('id', $trackingArr);
        }

        $this->db->like('tracking_name', $tracking, 'after');
        $this->db->where(array('tracking_type' => '2', 'status' => '1', 'deleted' => '0'));

        $query = $this->db->get();

        // echo $this->db->last_query(); die();
        return $query->result();
    }

    public function getAllSubGroup($array_group) {

        $total_group_array = array();
        $total_group_array = array_merge($total_group_array, $array_group);
        for ($i = 0; $i < count($array_group); $i++) {
            $sql = "SELECT GROUP_CONCAT(lv SEPARATOR ',') as ids FROM ( SELECT @pv:=(SELECT GROUP_CONCAT(id SEPARATOR ',') FROM pb_group WHERE FIND_IN_SET(parent_id,@pv)) AS lv FROM pb_group JOIN (SELECT @pv:=" . $array_group[$i] . ") tmp ) a";
            $query = $this->db->query($sql);
            $res = $query->row();
            if (!empty($res->ids)) {

                $ids = explode(',', $res->ids);
                $total_group_array = array_merge($total_group_array, $ids);
            }
        }
        return $total_group_array;
    }

    public function getBillNameForJson($ledger_id, $bill_name, $where = NULL) {
        if (is_null($where)) {
            $query = $this->db->query("SELECT DISTINCT B.bill_name, B.ledger_id ,(SELECT SUM(bill_amount) FROM pb_billwish_details WHERE  ledger_id =" . $ledger_id . " AND deleted !='1' AND B.bill_name =  pb_billwish_details.bill_name GROUP BY dr_cr HAVING dr_cr = 'Cr' ) AS cr_sum, (SELECT SUM(bill_amount) FROM pb_billwish_details WHERE  ledger_id =" . $ledger_id . " AND deleted !='1' AND branch_id = ".$this->session->userdata('branch_id')." AND B.bill_name =  pb_billwish_details.bill_name GROUP BY dr_cr HAVING dr_cr = 'Dr' ) AS dr_sum  FROM pb_billwish_details AS B WHERE B.ledger_id =" . $ledger_id . " AND B.deleted !='1' AND B.branch_id = ".$this->session->userdata('branch_id')." AND B.bill_name LIKE '" . $bill_name . "%'");
        } else {
            $query = $this->db->query("SELECT DISTINCT B.bill_name, B.ledger_id ,(SELECT SUM(bill_amount) FROM pb_billwish_details WHERE  ledger_id =" . $ledger_id . " AND deleted !='1' AND B.bill_name =  pb_billwish_details.bill_name GROUP BY dr_cr HAVING dr_cr = 'Cr' ) AS cr_sum, (SELECT SUM(bill_amount) FROM pb_billwish_details WHERE  ledger_id =" . $ledger_id . " AND deleted !='1' AND branch_id = ".$this->session->userdata('branch_id')." AND B.bill_name =  pb_billwish_details.bill_name GROUP BY dr_cr HAVING dr_cr = 'Dr' ) AS dr_sum  FROM pb_billwish_details AS B WHERE B.ledger_id =" . $ledger_id . " AND B.deleted !='1' AND B.branch_id = ".$this->session->userdata('branch_id')." AND B.bill_name LIKE '" . $bill_name . "%'" . $where);
        }
        $list = $query->result_array();
        return !empty($list) ? $list : array();
    }

    public function getBillByBillname($bill_name, $ledger_id) {

        $query = $this->db->query("SELECT DISTINCT B.bill_name,  B.ledger_id , B.credit_days ,B.credit_date ,(SELECT SUM(bill_amount) FROM pb_billwish_details WHERE  ledger_id =" . $ledger_id . " AND B.bill_name =  pb_billwish_details.bill_name GROUP BY dr_cr HAVING dr_cr = 'Cr' ) AS cr_sum, (SELECT SUM(bill_amount) FROM pb_billwish_details WHERE  ledger_id =" . $ledger_id . " AND B.bill_name =  pb_billwish_details.bill_name GROUP BY dr_cr HAVING dr_cr = 'Dr' ) AS dr_sum  FROM pb_billwish_details AS B WHERE B.ledger_id = " . $ledger_id . " AND B.bill_name LIKE '" . $bill_name . "%'");

        return $query->row_array();
    }

    public function setEntry($entry) {
        $this->db->insert('entry', $entry);
        $entry_id = $this->db->insert_id();
        return $entry_id;
    }

    public function setEntryDetails($entryDetails) {
        $this->db->insert_batch('ladger_account_detail', $entryDetails);
    }

    public function setComputationEntryDetails($entryDetails) {
        $this->db->insert_batch('computation_entry', $entryDetails);
    }

    public function insertTracking($trackingDetails) {
        $this->db->insert_batch('tracking_details', $trackingDetails);
    }

    public function insertBillWiseAuto($bill_data) {
        $this->db->insert_batch('billwish_details', $bill_data);
    }

    public function insertBillWise($billingDetails) {
        $this->db->insert_batch('billwish_details', $billingDetails);
    }


    public function insertService($service_arr) {
        $this->db->insert_batch('itc', $service_arr);
    }

    public function insertBanking($bankingDetails) {
        $this->db->insert_batch('bank_details', $bankingDetails);
    }

    public function checkAutoDate() {
        $this->db->select('*');
        $this->db->from('account_configuration');
        $query = $this->db->get();
        return $query->row_array();
    }

//    @-------ASit
    public function getAllCurrency() {
        $this->db->select('*');
        $this->db->from('currency');
        $query = $this->db->get();
        return $query->result();
    }

    //    @-------ASit
    public function getSelectedCurrency() {
        $this->db->select('selected_currency');
        $this->db->from('account_configuration');
        $this->db->where('id', 1);
        $query = $this->db->get();
        return $query->row();
    }

    //    @-------ASit
    public function saveRecurringData($data) {
        $this->db->insert('recurring_entry', $data);
    }


    //    @-------ASit
    public function saveShippingAddress($ledgerId) {
        $this->db->select('country,state');
        $this->db->from('customer_details');
        $this->db->where('ledger_id', $ledgerId);
        $query = $this->db->get();
        return $query->row();
    }


    public function getBranchName() {
        $this->db->select('company_name');
        $this->db->from('account_settings');
        $this->db->where('id', $this->session->userdata('branch_id'));
        $query = $this->db->get();
        return $query->row();
    }

    public function getEntryDetailsForVoucherMail($id)
    {
            $this->load->model('account/report', 'entrymodel');
            $data['entry_id'] = $id;
            $where = "";
            $where = array(
                'status' => 1,
            );
            $ledger_name = $this->entrymodel->getAllLedgerName($where);
            $ledger = array();
            $ledger[''] = 'Select..';
            foreach ($ledger_name as $val) {
                $ledger[$val['id']] = $val['ladger_name'];
            }

            $all_type = $this->entrymodel->allEntryType($where);
            $types = array();
            $types[''] = 'Select Entry Type';
            foreach ($all_type as $type) {
                $types[$type['id']] = $type['type'];
            }
            unset($where);
            $data['types'] = $types;
            $data['ledger'] = $ledger;

            $where = array(
                'entry.id' => $id,
                'entry.status' => 1,
                'entry.deleted' => 0
            );
            $data['entry'] = $this->entrymodel->getEntryDetailsById($where);
            unset($where);

            $where = array(
                'ladger_account_detail.entry_id' => $id,
                'ladger_account_detail.status' => 1,
                'ladger_account_detail.deleted' => 0
            );

            $data['entry_details'] = $this->entrymodel->getAllEntryDetailById($where);
            $data['currencies'] = $this->entrymodel->getAllCurrency();

            return $data;
    }

    public function getPreferences()
    {
        $this->db->where('id', 1);
        return $this->db->get('account_configuration')->row();
    }

}
?>    


