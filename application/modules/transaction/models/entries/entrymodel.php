<?php

class entrymodel extends CI_Model {

    public function __construct() {

        parent::__construct();
        $this->load->database();
    }
    
   public function getAllGroups(){
		$query = $this->db->query("SELECT G.*, PG.group_name as parent_group_name,PG.group_code as parent_group_code FROM pb_group AS G LEFT JOIN pb_group AS PG ON G.parent_id = PG.id WHERE G.status = '1' AND G.deleted = '0' ORDER BY G.parent_id");
		return $query->result_array();
	}
        
        public function getLedgerCodeStatus() {
        $this->db->select('ledger_code_status');
        $query = $this->db->get('account_configuration');
        return $query->row_array();
    }

    public function getLedgerByGroup($group_arr = null) {
        $this->db->select('id, ladger_name,ledger_code,current_balance,account_type');
        $this->db->from('ladger');
        $query = $this->db->get();
        return $query->result();
    }
    
     public function updateRecurringData($data, $entry_id) {
        $this->db->select('id');
        $this->db->from('recurring_entry');
        $this->db->where(array('entry_id' => $entry_id));
        $query = $this->db->get();
        $result= $query->row();
        if(count($result)>0){
        $this->db->where('entry_id', $entry_id);
        return $this->db->update('recurring_entry', $data);
        }else{
          return $this->db->insert('recurring_entry', $data);  
        }
    }

    public function getEntry($id) {
        $this->db->select('entry.*,recurring_entry.frequency');
        $this->db->from('entry');
         $this->db->join('recurring_entry', 'recurring_entry.entry_id=entry.id', 'left');
        $this->db->where('entry.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function getEntryDetails($id) {
        $this->db->select('LD.*,L.ladger_name');
        $this->db->from('ladger_account_detail LD');
        $this->db->join('ladger L', 'L.id = LD.ladger_id', 'left');
        $this->db->where('LD.entry_id', $id);
        $this->db->where('LD.status', 1);
        $this->db->where('LD.deleted !=', 1);
        $query = $this->db->get();
        return $query->result();
    }
    
    public function getEntryServiceDetails($id) {
        $this->db->select('I.*,P.name');
        $this->db->from('itc I');
        $this->db->join('products P', 'P.id = I.service_product_id', 'left');
        $this->db->where('I.entry_id', $id);
        $this->db->where('I.status', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function getLedgerDetails($id) {
        $this->db->select('tracking_status,bill_details_status,ladger_name');
        $this->db->from('ladger');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function getTempTrackingDetails($ledger_id, $entry_id) {
        $this->db->select('tracking_temp.account_type,tracking_temp.tracking_id,tracking_temp.tracking_amount,tracking.tracking_name');
        $this->db->from('tracking_temp');
        $this->db->join('tracking', 'tracking.id = tracking_temp.tracking_id');
        $this->db->where('tracking_temp.entry_id', $entry_id);
        $this->db->where('tracking_temp.ledger_id', $ledger_id);
        $query = $this->db->get();
        return $query->result();
    }

    //get temp billing data
    public function getTempBillingDetails($ledger_id, $entry_id) {
        $this->db->select('BT.ref_type,BT.bill_name,BT.credit_days,BT.credit_date,BT.bill_amount,BT.dr_cr');
        $this->db->from('billwish_temp  BT');
        $this->db->where('BT.entry_id', $entry_id);
        $this->db->where('BT.ledger_id', $ledger_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getTrackingDetails($q) {
        $this->db->select('id,tracking_name');
        $this->db->from('tracking');
        $this->db->like('tracking_name', $q);
        $this->db->where('tracking_type', '2');
        $this->db->where('status', '1');
        $this->db->where('deleted', '0');
        $query = $this->db->get();
        return $query->result();
    }

    public function deleteTempTrackingDetails($ledger_id, $entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->where('ledger_id', $ledger_id);
        $this->db->delete('tracking_temp');
    }

    public function saveTempTrackingDataModel($data) {
        return $this->db->insert_batch('tracking_temp', $data);
    }

    public function deleteTempBillingDetails($ledger_id, $entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->where('ledger_id', $ledger_id);
        $this->db->delete('billwish_temp');
    }

    public function saveTempBillData($data) {
        return $this->db->insert_batch('billwish_temp', $data);
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

    public function getAllSubGroup($bank_group_id) {
        $sql = "SELECT GROUP_CONCAT(lv SEPARATOR ',') as sub_id FROM ( SELECT @pv:=(SELECT GROUP_CONCAT(id SEPARATOR ',') FROM pb_group WHERE FIND_IN_SET(parent_id,@pv)) AS lv FROM pb_group JOIN (SELECT @pv:=" . $bank_group_id . ") tmp ) a";

        $query = $this->db->query($sql);
        return $query->row();
    }

    public function getAllLedger($all_group_id) {
        $sql = "SELECT id FROM pb_ladger WHERE group_id IN (" . $all_group_id . ")";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //get temp billing data
    public function getTempBankingDetails($ledger_id, $entry_id) {
        $this->db->select('B.transaction_type,B.instrument_no,B.instrument_date,B.bank_name,B.branch_name,B.ifsc_code,B.bank_amount,T.name');
        $this->db->from('temp_bank_details  B');
        $this->db->join('transaction_type T', 'T.id = B.transaction_type');
        $this->db->where('B.entry_no', $entry_id);
        $this->db->where('B.ledger_id', $ledger_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function allTransactionType() {
        $this->db->select('*');
        $this->db->from('transaction_type');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result();
    }

    public function deleteTempBankDetails($ledger_id, $entry_id) {
        $this->db->where('entry_no', $entry_id);
        $this->db->where('ledger_id', $ledger_id);
        $this->db->delete('temp_bank_details');
    }

    public function saveTempBankData($data) {
        return $this->db->insert_batch('temp_bank_details', $data);
    }

    public function copyBankDetailstoTemp($entry_id) {
        $this->db->select('*');
        $this->db->from('bank_details');
        $this->db->where('entry_id =', $entry_id);
        $query = $this->db->get();
        $bank_details = $query->result();
        $this->db->where('entry_no =', $entry_id);
        $this->db->delete('temp_bank_details');
        $bank_details_array = [];
        foreach ($bank_details as $val) {
            $data = array(
                'entry_no' => $entry_id,
                'ledger_id' => $val->ledger_id,
                'transaction_type' => $val->transaction_type,
                'instrument_no' => $val->instrument_no,
                'instrument_date' => $val->instrument_date,
                'bank_name' => $val->bank_name,
                'branch_name' => $val->branch_name,
                'ifsc_code' => $val->ifsc_code,
                'bank_amount' => $val->bank_amount,
                'create_date' => date("Y-m-d H:i:s")
            );
            $bank_details_array[] = $data;
        }
        if ($bank_details_array) {
            $this->db->insert_batch('temp_bank_details', $bank_details_array);
        }
    }

    public function copyTrackingDetailstoTemp($entry_id) {
        $this->db->select('*');
        $this->db->from('tracking_details');
        $this->db->where('entry_id =', $entry_id);
        $query = $this->db->get();
        $tracking_details = $query->result();
        $this->db->where('entry_id =', $entry_id);
        $this->db->delete('tracking_temp');
        $tracking_details_array = [];
        foreach ($tracking_details as $val) {
            $data = array(
                'entry_id' => $entry_id,
                'account_type' => $val->account_type,
                'ledger_id' => $val->ledger_id,
                'tracking_id' => $val->tracking_id,
                'tracking_amount' => $val->tracking_amount,
                'created_date' => $val->created_date,
            );
            $tracking_details_array[] = $data;
        }
        if ($tracking_details_array) {
            $this->db->insert_batch('tracking_temp', $tracking_details_array);
        }
    }

    public function copyBillingDetailstoTemp($entry_id) {
        $this->db->select('*');
        $this->db->from('billwish_details');
        $this->db->where('entry_id =', $entry_id);
        $query = $this->db->get();
        $bill_details = $query->result();
        $this->db->where('entry_id =', $entry_id);
        $this->db->delete('billwish_temp');
        $bill_details_array = [];
        foreach ($bill_details as $val) {
            $data = array(
                'entry_id' => $entry_id,
                'ledger_id' => $val->ledger_id,
                'dr_cr' => $val->dr_cr,
                'ref_type' => $val->ref_type,
                'bill_name' => $val->bill_name,
                'credit_days' => $val->credit_days,
                'credit_date' => $val->credit_date,
                'bill_amount' => $val->bill_amount,
                'created_date' => $val->created_date,
            );
            $bill_details_array[] = $data;
        }
        if ($bill_details_array) {
            $this->db->insert_batch('billwish_temp', $bill_details_array);
        }
    }

    public function getDefoultCurrency() {
        $this->db->select('base_currency');
        $this->db->from('account_standard_format');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getCurrencyUnitById($id) {
        $this->db->select('id,unit_price');
        $this->db->from('currency');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function updateEntry($entry_data, $entry_id) {
        $this->db->where('id', $entry_id);
        return $this->db->update('entry', $entry_data);
    }

    public function deleteEntryDetails($entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->update('ladger_account_detail', array('deleted' => '1'));
    }

    public function checkExistEntry($ledger_id, $entry_id) {
        $this->db->select('id');
        $this->db->from('ladger_account_detail');
        $this->db->where('entry_id', $entry_id);
        $this->db->where('ladger_id', $ledger_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function copyBankDetails($entry_id,$new_entry_id,$tr_ledger_id= array()) {
        $this->db->select('*');
        $this->db->from('temp_bank_details');
        $this->db->where('entry_no =', $entry_id);
        $this->db->where_in('ledger_id', $tr_ledger_id);//24062018 @sudip
        $query = $this->db->get();
        $bank_details = $query->result();
        if($entry_id==$new_entry_id){
        $this->db->where('entry_id =', $entry_id);
        $this->db->delete('bank_details');
        }
        $bank_details_array = [];
        foreach ($bank_details as $val) {
            $data = array(
                'entry_id' => $new_entry_id,
                'ledger_id' => $val->ledger_id,
                'transaction_type' => $val->transaction_type,
                'instrument_no' => $val->instrument_no,
                'instrument_date' => $val->instrument_date,
                'bank_name' => $val->bank_name,
                'branch_name' => $val->branch_name,
                'ifsc_code' => $val->ifsc_code,
                'bank_amount' => $val->bank_amount,
                'create_date' => date("Y-m-d H:i:s")
            );
            $bank_details_array[] = $data;
        }
        if ($bank_details_array) {
            $this->db->insert_batch('bank_details', $bank_details_array);
        }
    }

    public function copyTrackingDetails($entry_id,$new_entry_id) {
        $this->db->select('*');
        $this->db->from('tracking_temp');
        $this->db->where('entry_id =', $entry_id);
        $query = $this->db->get();
        $tracking_details = $query->result();
        if($entry_id==$new_entry_id){
        $this->db->where('entry_id =', $entry_id);
        $this->db->delete('tracking_details');
        }
        $tracking_details_array = [];
        foreach ($tracking_details as $val) {
            $data = array(
                'entry_id' => $new_entry_id,
                'account_type' => $val->account_type,
                'ledger_id' => $val->ledger_id,
                'tracking_id' => $val->tracking_id,
                'tracking_amount' => $val->tracking_amount,
                'created_date' => $val->created_date,
            );
            $tracking_details_array[] = $data;
        }
        if ($tracking_details_array) {
            $this->db->insert_batch('tracking_details', $tracking_details_array);
        }
    }

    public function copyBillingDetails($entry_id,$new_entry_id) {
        $this->db->select('*');
        $this->db->from('billwish_temp');
        $this->db->where('entry_id =', $entry_id);
        $query = $this->db->get();
        $bill_details = $query->result();
        if($entry_id==$new_entry_id){
        $this->db->where('entry_id =', $entry_id);
        $this->db->delete('billwish_details');
        }
        $bill_details_array = [];
        foreach ($bill_details as $val) {
            $data = array(
                'entry_id' => $new_entry_id,
                'ledger_id' => $val->ledger_id,
                'dr_cr' => $val->dr_cr,
                'ref_type' => $val->ref_type,
                'bill_name' => $val->bill_name,
                'credit_days' => $val->credit_days,
                'credit_date' => $val->credit_date,
                'bill_amount' => $val->bill_amount,
                'created_date' => $val->created_date,
            );
            $bill_details_array[] = $data;
        }
        if ($bill_details_array) {
            $this->db->insert_batch('billwish_details', $bill_details_array);
        }
    }

    public function deleteTempBank($entry_id) {
        $this->db->where('entry_no =', $entry_id);
        $this->db->delete('temp_bank_details');
    }

    public function deleteTempTracking($entry_id) {
        $this->db->where('entry_id =', $entry_id);
        $this->db->delete('tracking_temp');
    }

    public function deleteTempBilling($entry_id) {
        $this->db->where('entry_id =', $entry_id);
        $this->db->delete('billwish_temp');
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

    public function getLedgerFinal($ledger, $ledger_array) {
        $this->db->select('id, ladger_name');
        $this->db->from('ladger');
        $this->db->like('ladger_name', $ledger, 'after');
        $this->db->where(array('status' => '1', 'deleted' => '0'));
        $this->db->where_not_in('group_id', $ledger_array);
        $query = $this->db->get();
        return $query->result();
    }

    public function getLedgerFinalIn($ledger, $ledger_array) {
        $this->db->select('id, ladger_name');
        $this->db->from('ladger');
        $this->db->like('ladger_name', $ledger, 'after');
        $this->db->where(array('status' => '1', 'deleted' => '0'));
        $this->db->where_in('group_id', $ledger_array);
        $query = $this->db->get();
        return $query->result();
    }

    public function getLedgerType($ledger_id) {
        $this->db->select('account_type');
        $this->db->from('ladger');
        $this->db->where('id', $ledger_id);
        $query = $this->db->get();
        return $query->row();
    }
    
    public function deleteBank($entry_id) {
        $this->db->where('entry_id =', $entry_id);
        $this->db->delete('bank_details');
    }

    public function deleteTracking($entry_id) {
        $this->db->where('entry_id =', $entry_id);
        $this->db->delete('tracking_details');
    }

    public function deleteBilling($entry_id) {
        $this->db->where('entry_id =', $entry_id);
        $this->db->delete('billwish_details');
    }
    
    public function getEntryTypeById($id) {
        $this->db->select('*');
        $this->db->from('entry_type');
        $this->db->where('status', '1');
        $this->db->where('deleted', '0');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function checkVoucher() {
        $this->db->select('*');
        $this->db->from('account_configuration');
        $query = $this->db->get();
        return $query->row_array();
    }
    
     public function saveEntry($entry) {
        $this->db->insert('entry', $entry);
        $entry_id = $this->db->insert_id();
        return $entry_id;
    }
    
     public function saveEntryDetails($entryDetails) {
        $this->db->insert_batch('ladger_account_detail', $entryDetails);
    }
    
    public function deleteService($entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->update('itc', array('status' => '3'));
    }
    public function insertService($service_arr) {
        $this->db->insert_batch('itc', $service_arr);
    }
   

}
