<?php

class inventorymodel extends CI_Model {

    public function __construct() {

        parent::__construct();
        $this->load->database();
    }

     public function getAllGroups() {
        $query = $this->db->query("SELECT G.*, PG.group_name as parent_group_name,PG.group_code as parent_group_code FROM pb_group AS G LEFT JOIN pb_group AS PG ON G.parent_id = PG.id WHERE G.status = '1' AND G.deleted = '0' AND G.id != '17' ORDER BY G.parent_id");
        return $query->result_array();
    }

    public function getAllEntry($id,$parent_id,$from_date, $to_date, $limit=10, $offset=0,$search='') {
        $this->db->select("E.*, entry_type.type, GROUP_CONCAT(CONCAT(l.ladger_name, ' [',ld.account, ']') SEPARATOR '/') as ledger_detail,W.eway_bill_no,W.supply_type as w_type,W.cancel_status as w_cancel_status", FALSE);
        $this->db->from('entry AS E');
        /* ========= */

        $this->db->join('ladger_account_detail as ld', 'ld.entry_id = E.id', 'left');
        $this->db->join('ladger as l', 'l.id = ld.ladger_id', 'left');

        /* ========= */

         if ($parent_id == 0) {
            $this->db->join('entry_type', 'entry_type.id = E.entry_type_id', 'left');
            $this->db->where('E.sub_voucher', $parent_id);
            $this->db->where('E.entry_type_id', $id);
        } else {
            $this->db->join('entry_type', 'entry_type.id = E.sub_voucher', 'left');
            $this->db->where('E.sub_voucher', $id);
            $this->db->where('E.entry_type_id', $parent_id);
        }
        //Ewaybill
        $this->db->join('ewaybill W', 'W.entry_id = E.id', 'left');
        
        $this->db->where('E.status', 1);
        $this->db->where('E.company_id', $this->session->userdata('branch_id'));
        $where = '(E.deleted = 0 OR (E.deleted = 1 AND E.cancel_status = 1))';
//        $this->db->where('E.deleted', 0);
        $this->db->where($where);
        $this->db->where('E.create_date >=', $from_date);
        $this->db->where('E.create_date <=', $to_date);
        $this->db->where('E.is_inventry', 1);
//        $this->db->or_like('E.create_date', $search);
        $this->db->like('E.entry_no', $search);
//        $this->db->like('E.cr_amount', $search);
//        $this->db->or_like('W.eway_bill_no', $search);
//        $this->db->or_like('l.ladger_name', $search);
        if ($limit != 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->group_by('E.id');
        $this->db->order_by("E.create_date", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllPostDatedEntry($id,$parent_id, $limit=10, $offset=0,$search='') {
        $this->db->select('entry.*, entry_type.type');
        $this->db->from('entry');
        $this->db->order_by("entry.id", "asc");
        $this->db->join('entry_type', 'entry_type.id = entry.entry_type_id', 'left');
         if ($parent_id == 0) {
            $this->db->where('entry.sub_voucher', $parent_id);
            $this->db->where('entry.entry_type_id', $id);
        } else {
            $this->db->where('entry.sub_voucher', $id);
            $this->db->where('entry.entry_type_id', $parent_id);
        }
        $this->db->where('entry.company_id', $this->session->userdata('branch_id'));
        $this->db->where('entry.status', 1);
        $this->db->where('entry.deleted', 2);
        $this->db->where('entry.is_inventry', 1);
        $this->db->like('entry.entry_no', $search);
        if ($limit != 0) {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllRecurringEntry($id,$parent_id, $limit=10, $offset=0,$search='') {
        $this->db->select('entry.*, entry_type.type');
        $this->db->from('recurring_entry');

        $this->db->join('entry', 'entry.id = recurring_entry.entry_id', 'left');
        $this->db->join('entry_type', 'entry_type.id = entry.entry_type_id', 'left');
         if ($parent_id == 0) {
            $this->db->where('entry.sub_voucher', $parent_id);
            $this->db->where('entry.entry_type_id', $id);
        } else {
            $this->db->where('entry.sub_voucher', $id);
            $this->db->where('entry.entry_type_id', $parent_id);
        }
        $this->db->where('entry.company_id', $this->session->userdata('branch_id'));
        $this->db->where('entry.status', 1);
        $this->db->where('entry.deleted', 0);
        $this->db->where('entry.is_inventry', 1);

        $this->db->where("recurring_entry.status", '1');
        $this->db->like('entry.entry_no', $search);
        $this->db->order_by("entry.id", "asc");
        $this->db->group_by("recurring_entry.entry_id");
        if ($limit != 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getVoucherType($id) {

        $this->db->select('*');
        $this->db->from('entry_type');
        $this->db->where('id', $id);
        $this->db->where('status', 1);
        $this->db->where('deleted', 0);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getEntry($id) {
        $this->db->select('entry.*,recurring_entry.frequency,b.ledger_id,b.bank_name,b.branch_name,b.acc_no,b.ifsc,b.bank_address');
        $this->db->from('entry');
        $this->db->join('recurring_entry', 'recurring_entry.entry_id=entry.id', 'left');
        $this->db->join('bank_details_company b', 'b.id=entry.bank_id', 'left');
        $this->db->where('entry.id', $id);
        $this->db->where('entry.status', 1);
        $this->db->where('entry.deleted !=', 1);
        $query = $this->db->get();
        return $query->row();
    }


    public function getRequestEntry($id) {
        $this->db->select('e.*,b.ledger_id,b.bank_name,b.branch_name,b.acc_no,b.ifsc,b.bank_address');
        $this->db->from('entry_request e');
        $this->db->join('bank_details_company b', 'b.id=e.bank_id', 'left');
        $this->db->where('e.id', $id);
        $this->db->where('e.status', 1);
        $this->db->where('e.deleted', 0);
        $query = $this->db->get();
        return $query->row();
    }


    public function getRequestEntryForQuotation($id) {
        $this->db->select('e.*,b.ledger_id,b.bank_name,b.branch_name,b.acc_no,b.ifsc,b.bank_address');
        $this->db->from('quotation_entry_request e');
        $this->db->join('bank_details_company b', 'b.id=e.bank_id', 'left');
        $this->db->where('e.id', $id);
        $this->db->where('e.status', 1);
        $this->db->where('e.deleted', 0);
        $query = $this->db->get();
        return $query->row();
    }


    public function getTempEntry($id) {
        $this->db->select('e.*,b.ledger_id,b.bank_name,b.branch_name,b.acc_no,b.ifsc,b.bank_address');
        $this->db->from('entry_temp e');
        $this->db->join('bank_details_company b', 'b.id=e.bank_id', 'left');
        $this->db->where('e.id', $id);
        $this->db->where('e.status', 1);
        $this->db->where('e.deleted', 0);
        $query = $this->db->get();
        return $query->row();
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

    public function checkAutoDate() {
        $this->db->select('*');
        $this->db->from('account_configuration');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getAllCurrency() {
        $this->db->select('*');
        $this->db->from('currency');
        $query = $this->db->get();
        return $query->result();
    }

    public function getLedgerCodeStatus() {
        $this->db->select('ledger_code_status');
        $query = $this->db->get('account_configuration');
        return $query->row_array();
    }

    public function getEntryDetails($id) {
        $this->db->select('LD.*,L.ladger_name,L.credit_date');
        $this->db->from('ladger_account_detail LD');
        $this->db->join('ladger L', 'L.id = LD.ladger_id');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.deleted !=', '1');
        $this->db->where('LD.entry_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

     public function getComputationEntryDetails($id) {
        $this->db->select('LD.*,L.ladger_name');
        $this->db->from('computation_entry LD');
        $this->db->join('ladger L', 'L.id = LD.ladger_id');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.deleted !=', '1');
        $this->db->where('LD.entry_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getRequestEntryDetails($id) {
        $this->db->select('LD.*,L.ladger_name,L.credit_date');
        $this->db->from('entry_request_details LD');
        $this->db->join('ladger L', 'L.id = LD.ladger_id');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.entry_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getRequestEntryDetailsForQuotation($id) {
        $this->db->select('LD.*,L.ladger_name,L.credit_date');
        $this->db->from('quotation_entry_request_details LD');
        $this->db->join('ladger L', 'L.id = LD.ladger_id');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.entry_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getTempEntryDetails($id) {
        $this->db->select('LD.*,L.ladger_name,,L.credit_date');
        $this->db->from('entry_temp_details LD');
        $this->db->join('ladger L', 'L.id = LD.ladger_id');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.deleted', '0');
        $this->db->where('LD.entry_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getOrder($id,$type) {
        $this->db->select('o.*,c.name AS shipping_country_name,c1.name AS billing_country_name,s.name AS shipping_state_name,s1.name AS billing_state_name,s.state_code AS shipping_state_code,s1.state_code AS billing_state_code');
        $this->db->from('orders o');
//        $this->db->where('o.status', '1');
        $this->db->where('o.order_type', $type);
        $this->db->where('o.entry_id', $id);
        $this->db->join('countries c', 'c.id = o.shipping_country','LEFT');
        $this->db->join('states s', 's.id = o.shipping_state','LEFT');
        $this->db->join('countries c1', 'c1.id = o.billing_country','LEFT');
        $this->db->join('states s1', 's1.id = o.billing_state','LEFT');

        $query = $this->db->get();
        return $query->row();
    }

    public function getTempOrder($id,$type) {
        $this->db->select('*');
        $this->db->from('order_temp o');
//        $this->db->where('o.status', '1');
        $this->db->where('o.order_type', $type);
        $this->db->where('o.entry_id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function getRequestOrder($id) {
        $this->db->select('o.*,c.name AS shipping_country_name,c1.name AS billing_country_name,s.name AS shipping_state_name,s1.name AS billing_state_name,s.state_code AS shipping_state_code,s1.state_code AS billing_state_code');
        $this->db->from('order_request o');
        $this->db->where('o.status', '1');
        $this->db->where('o.entry_id', $id);
        $this->db->join('countries c', 'c.id = o.shipping_country','LEFT');
        $this->db->join('states s', 's.id = o.shipping_state','LEFT');
        $this->db->join('countries c1', 'c1.id = o.billing_country','LEFT');
        $this->db->join('states s1', 's1.id = o.billing_state','LEFT');
        $query = $this->db->get();
        return $query->row();
    }

    public function getRequestOrderForQuotation($id) {
        $this->db->select('o.*,c.name AS shipping_country_name,c1.name AS billing_country_name,s.name AS shipping_state_name,s1.name AS billing_state_name,s.state_code AS shipping_state_code,s1.state_code AS billing_state_code');
        $this->db->from('quotation_order_request o');
        $this->db->where('o.status', '1');
        $this->db->where('o.entry_id', $id);
        $this->db->join('countries c', 'c.id = o.shipping_country','LEFT');
        $this->db->join('states s', 's.id = o.shipping_state','LEFT');
        $this->db->join('countries c1', 'c1.id = o.billing_country','LEFT');
        $this->db->join('states s1', 's1.id = o.billing_state','LEFT');
        $query = $this->db->get();
        return $query->row();
    }

    public function getOrderDetails($id) {
        $this->db->select('op.*,ps.stockdet,p.name,u.name as unit_name,gg.hsn_number,ps.having_batch');
        $this->db->from('ordered_products op');
        $this->db->join('product_stock ps', 'ps.id = op.stock_id');
        $this->db->join('units u', 'u.id = ps.qty_unit','LEFT');
        $this->db->join('products p', 'p.id = op.product_id');
        $this->db->join('gst_for_good gg', 'gg.id = p.goods_gst_id');
        $this->db->where('op.status !=', '0');
        $this->db->where('op.order_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getTempOrderDetails($id) {
        $this->db->select('op.*,ps.stockdet,p.name,CONCAT("No") as unit_name,gg.hsn_number');
        $this->db->from('order_temp_details op');
        $this->db->join('product_stock ps', 'ps.id = op.stock_id');
//        $this->db->join('units u', 'u.id = ps.qty_unit');
        $this->db->join('products p', 'p.id = op.product_id');
        $this->db->join('gst_for_good gg', 'gg.id = p.goods_gst_id');
        $this->db->where('op.status !=', '0');
        $this->db->where('op.order_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getRequestOrderDetails($id) {
        $this->db->select('op.*,ps.stockdet,p.name,u.name as unit_name,gg.hsn_number');
        $this->db->from('order_request_details op');
        $this->db->join('product_stock ps', 'ps.id = op.stock_id');
        $this->db->join('units u', 'u.id = ps.qty_unit');
        $this->db->join('products p', 'p.id = op.product_id');
        $this->db->join('gst_for_good gg', 'gg.id = p.goods_gst_id');
        $this->db->where('op.status', '1');
        $this->db->where('op.order_id', $id);

        $query = $this->db->get();
        return $query->result();
    }

    public function getRequestOrderDetailsForQuotation($id) {
        $this->db->select('op.*,ps.stockdet,p.name,u.name as unit_name,gg.hsn_number');
        $this->db->from('quotation_order_request_details op');
        $this->db->join('product_stock ps', 'ps.id = op.stock_id');
        $this->db->join('units u', 'u.id = ps.qty_unit');
        $this->db->join('products p', 'p.id = op.product_id');
        $this->db->join('gst_for_good gg', 'gg.id = p.goods_gst_id');
        $this->db->where('op.status', '1');
        $this->db->where('op.order_id', $id);

        $query = $this->db->get();
        return $query->result();
    }

    public function ledgerLimitDetails($id) {
        $this->db->select('credit_date, credit_limit');
        $this->db->from('ladger');
        $this->db->where(array('id' => $id));

        $query = $this->db->get();
        return $query->row();
    }

    public function getShippingAddress($id) {

        $this->db->select('s.id, s.address, s.city, s.country, s.state, s.zip, s.default_ship, c.company_name, c.gstn_no AS sales_tax_no, co.name as country_name, st.name as state_name,c.company_type');
        $this->db->from('shipping_address as s');
        $this->db->join('customer_details as c', 's.users_id = c.ledger_id', 'left');
        $this->db->join('countries as co', 's.country = co.id', 'left');
        $this->db->join('states as st', 's.state = st.id', 'left');
        $this->db->where(array('s.users_id' => $id));
        $this->db->order_by('default_ship', 'desc');
        $query = $this->db->get();
//         return $query->row();
        return $query->result();
    }

    public function getCountryName($cId) {

        $this->db->select('name');
        $this->db->from('countries');
        $this->db->where(array('id' => $cId));

        $query = $this->db->get();
        return $query->row();
    }

    public function getStateName($sId) {

        $this->db->select('name');
        $this->db->from('states');
        $this->db->where(array('id' => $sId));

        $query = $this->db->get();
        return $query->row();
    }

    public function getBillingAddress() {

        $this->db->select('company_name, street_address, country, state_id, city_name, zip_code, service_tax');
        $this->db->from('account_settings');
        $query = $this->db->get();


        return $query->row();
    }

    public function getLedger($ledger, $all_group_id) {
        $this->db->select('L.id, L.ladger_name,LD.current_closing_balance,LD.account, L.ledger_code');
        $this->db->from('ladger L');
        $this->db->join('ladger_account_detail LD', 'LD.ladger_id = L.id', 'left');
        $this->db->where(array('LD.entry_id' => 0, 'LD.branch_id' => $this->session->userdata('branch_id')));
        $this->db->where(array('L.status' => '1', 'L.deleted' => '0', 'L.discontinue' => '0'));
        $this->db->where('L.branch_id !=', $this->session->userdata('branch_id'));
        $this->db->where_in('L.group_id', $all_group_id);
        // $this->db->like('L.ladger_name', $ledger, 'after');
        if ($ledger) {
            $this->db->where("(L.ladger_name LIKE '%".$ledger."%' OR L.ledger_code LIKE '%".$ledger."%')", NULL, FALSE);
        }
        // $this->db->or_like('L.ledger_code', $ledger, 'after');
        $query = $this->db->get();
        return $query->result();
    }

    public function getSalesLedger($array_group) {

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
        $this->db->select('account_type,branch_id');
        $this->db->from('ladger');
        $this->db->where(array('id' => $ledgerId));

        $query = $this->db->get();
        return $query->row();
    }

    public function getShippingDet($id) {

        $this->db->select('country, state');
        $this->db->from('shipping_address');
        $this->db->where(array('users_id' => $id));
        $query = $this->db->get();
        return $query->row();
    }

    public function getLedgerFinalIn($ledger, $ledger_array) {
        $this->db->select('L.id, L.ladger_name,LD.current_closing_balance,LD.account, L.ledger_code');
        $this->db->from('ladger L');
        $this->db->join('ladger_account_detail LD', 'LD.ladger_id = L.id', 'left');
        // $this->db->like('L.ladger_name', $ledger, 'after');
        $this->db->where(array('LD.entry_id' => 0, 'LD.branch_id' => $this->session->userdata('branch_id')));
        $this->db->where(array('L.status' => '1', 'L.deleted' => '0'));
        $this->db->where_in('L.group_id', $ledger_array);
        $this->db->where("(L.ladger_name LIKE '%".$ledger."%' OR L.ledger_code LIKE '%".$ledger."%')", NULL, FALSE);
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

    public function updateEntry($entry_data, $entry_id) {
        $this->db->where('id', $entry_id);
        return $this->db->update('entry', $entry_data);
    }

    public function updateRequestEntry($entry_data, $entry_id) {
        $this->db->where('id', $entry_id);
        return $this->db->update('entry_request', $entry_data);
    }

    public function updateQuotationRequestEntry($entry_data, $entry_id) {
        $this->db->where('id', $entry_id);
        return $this->db->update('quotation_entry_request', $entry_data);
    }

    public function updateTempEntry($entry_data, $entry_id) {
        $this->db->where('id', $entry_id);
        return $this->db->update('entry_temp', $entry_data);
    }

    public function deleteEntryDetails($entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->update('ladger_account_detail', array('deleted' => '1'));
    }

     public function deleteComputationEntryDetails($entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->update('computation_entry', array('deleted' => '1'));
    }

    public function deleteRequestEntryDetails($entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->update('entry_request_details', array('deleted' => '1'));
    }

    public function deleteQuotationRequestEntryDetails($entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->update('quotation_entry_request_details', array('deleted' => '1'));
    }

    public function deleteTempEntryDetails($entry_id) {
        $this->db->where('entry_id', $entry_id);
        $this->db->update('entry_temp_details', array('deleted' => '1'));
    }

    public function getTBilling($ledgerId) {
        $this->db->select('bill_details_status');
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

    public function getLedgerDetails($entry_id, $ladger_id) {
        $this->db->select('id');
        $this->db->from('ladger_account_detail');
        $this->db->where(array('entry_id' => $entry_id, 'ladger_id' => $ladger_id));
        $query = $this->db->get();
        return $query->row();
    }

    public function getLedgerDetailsRequest($entry_id, $ladger_id) {
        $this->db->select('id');
        $this->db->from('entry_request_details');
        $this->db->where(array('entry_id' => $entry_id, 'ladger_id' => $ladger_id));
        $query = $this->db->get();
        return $query->row();
    }

    public function getLedgerDetailsTemp($entry_id, $ladger_id) {
        $this->db->select('id');
        $this->db->from('entry_temp_details');
        $this->db->where(array('entry_id' => $entry_id, 'ladger_id' => $ladger_id));
        $query = $this->db->get();
        return $query->row();
    }

    public function updateLedgerDetails($entry_id, $ladger_id, $ledger_details) {
        $this->db->where('entry_id', $entry_id);
        $this->db->where('ladger_id', $ladger_id);
        return $this->db->update('ladger_account_detail', $ledger_details);
    }

     public function updateLedgerDetailsRequest($entry_id, $ladger_id, $ledger_details) {
        $this->db->where('entry_id', $entry_id);
        $this->db->where('ladger_id', $ladger_id);
        return $this->db->update('entry_request_details', $ledger_details);
    }

     public function updateLedgerDetailsTemp($entry_id, $ladger_id, $ledger_details) {
        $this->db->where('entry_id', $entry_id);
        $this->db->where('ladger_id', $ladger_id);
        return $this->db->update('entry_temp_details', $ledger_details);
    }

    public function insertLedgerDetails($ledger_details) {
        return $this->db->insert_batch('ladger_account_detail', $ledger_details);
    }

    public function insertComputationLedgerDetails($ledger_details) {
        return $this->db->insert_batch('computation_entry', $ledger_details);
    }

    public function insertLedgerDetailsRequest($ledger_details) {
        return $this->db->insert('entry_request_details', $ledger_details);
    }

     public function insertLedgerDetailsTemp($ledger_details) {
        return $this->db->insert('entry_temp_details', $ledger_details);
    }

    public function updateBillwise($entry_id, $bill_data) {
        $this->db->where('entry_id', $entry_id);
        $this->db->where('ref_type', 'New Reference');
        return $this->db->update('billwish_details', $bill_data);
    }

    public function updateOrder($order_id, $order) {
        $this->db->where('id', $order_id);
        return $this->db->update('orders', $order);
    }

    public function updateTempOrder($order_id, $order) {
        $this->db->where('id', $order_id);
        return $this->db->update('order_temp', $order);
    }

     public function updateOrderRequest($entry_id, $order) {
        $this->db->where('entry_id', $entry_id);
        return $this->db->update('order_request', $order);
    }

     public function updateQuotationOrderRequest($entry_id, $order) {
        $this->db->where('entry_id', $entry_id);
        return $this->db->update('quotation_order_request', $order);
    }

    public function updateOrderRequestByOrderId($order_id, $order) {
        $this->db->where('id', $order_id);
        return $this->db->update('order_request', $order);
    }

    public function updateQuotationOrderRequestByOrderId($order_id, $order) {
        $this->db->where('id', $order_id);
        return $this->db->update('quotation_order_request', $order);
    }

    public function getOrderId($entry_id,$order_type) {
        $this->db->select('id');
        $this->db->from('orders');
        $this->db->where('status !=', '0');
        $this->db->where('entry_id', $entry_id);
        $this->db->where('order_type', $order_type);
        $query = $this->db->get();
        return $query->row();
    }


    public function getTempOrderId($entry_id) {
        $this->db->select('id');
        $this->db->from('order_temp');
        $this->db->where('status !=', '0');
        $this->db->where('entry_id', $entry_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function getOrderIdTemp($entry_id,$order_type) {
        $this->db->select('id');
        $this->db->from('orders');
        $this->db->where(array('entry_id' => $entry_id,'order_type' => $order_type));
        $query = $this->db->get();
        return $query->row();
    }

    public function getOrderIdRequest($entry_id) {
        $this->db->select('id');
        $this->db->from('order_request');
        $this->db->where(array('entry_id' => $entry_id));
        $query = $this->db->get();
        return $query->row();
    }

    public function getOrderIdRequestForQuotation($entry_id) {
        $this->db->select('id');
        $this->db->from('quotation_order_request');
        $this->db->where(array('entry_id' => $entry_id));
        $query = $this->db->get();
        return $query->row();
    }

    public function getOrderProduct($orderId, $product_id, $stock_id) {
        $this->db->select('id');
        $this->db->from('ordered_products');
        $this->db->where(array('order_id' => $orderId, 'product_id' => $product_id, 'stock_id' => $stock_id));
        $query = $this->db->get();
        return $query->row();
    }

      public function getTempOrderProduct($orderId, $product_id, $stock_id) {
        $this->db->select('id');
        $this->db->from('order_temp_details');
        $this->db->where(array('order_id' => $orderId, 'product_id' => $product_id, 'stock_id' => $stock_id));
        $query = $this->db->get();
        return $query->row();
    }

     public function getRequestOrderProduct($orderId, $product_id, $stock_id) {
        $this->db->select('id');
        $this->db->from('order_request_details');
        $this->db->where(array('order_id' => $orderId, 'product_id' => $product_id, 'stock_id' => $stock_id));
        $query = $this->db->get();
        return $query->row();
    }

    public function deleteOrderProduct($orderId) {

        $this->db->where('order_id', $orderId);
        $this->db->update('ordered_products', array('status' => '0'));
    }

     public function deleteTempOrderProduct($orderId) {

        $this->db->where('order_id', $orderId);
        $this->db->update('order_temp_details', array('status' => '0'));
    }

    public function deleteRequestOrderProduct($orderId) {

        $this->db->where('order_id', $orderId);
        $this->db->update('order_request_details', array('status' => '0'));
    }

    public function deleteRequestOrderProductForQuotation($orderId) {

        $this->db->where('order_id', $orderId);
        $this->db->update('quotation_order_request_details', array('status' => '0'));
    }

    public function updateOrderProduct($orderId, $product_id, $stock_id, $productDetails) {
        $this->db->where('order_id', $orderId);
        $this->db->where('product_id', $product_id);
        $this->db->where('stock_id', $stock_id);
        return $this->db->update('ordered_products', $productDetails);
    }

     public function updateTempOrderProduct($orderId, $product_id, $stock_id, $productDetails) {
        $this->db->where('order_id', $orderId);
        $this->db->where('product_id', $product_id);
        $this->db->where('stock_id', $stock_id);
        return $this->db->update('order_temp_details', $productDetails);
    }



    public function updateRequestOrderProduct($orderId, $product_id, $stock_id, $productDetails) {
        $this->db->where('order_id', $orderId);
        $this->db->where('product_id', $product_id);
        $this->db->where('stock_id', $stock_id);
        return $this->db->update('order_request_details', $productDetails);
    }

    public function updateQuotationRequestOrderProduct($orderId, $product_id, $stock_id, $productDetails) {
        $this->db->where('order_id', $orderId);
        $this->db->where('product_id', $product_id);
        $this->db->where('stock_id', $stock_id);
        return $this->db->update('quotation_order_request_details', $productDetails);
    }

    public function insertOrderProduct($productDetails) {
        return $this->db->insert('ordered_products', $productDetails);
    }

    public function insertTempOrderProduct($productDetails) {
        return $this->db->insert('order_temp_details', $productDetails);
    }

    public function insertRequestOrderProduct($productDetails) {
        return $this->db->insert('order_request_details', $productDetails);
    }

    public function insertQuotationRequestOrderProduct($productDetails) {
        return $this->db->insert('quotation_order_request_details', $productDetails);
    }

    public function getOrderTax($orderId, $product_id, $stock_id) {
        $this->db->select('id');
        $this->db->from('order_tax_details');
        $this->db->where(array('order_id' => $orderId, 'product_id' => $product_id, 'stock_id' => $stock_id));
        $query = $this->db->get();
        return $query->row();
    }

    public function getRequestOrderTax($orderId, $product_id, $stock_id) {
        $this->db->select('id');
        $this->db->from('order_request_tax_details');
        $this->db->where(array('order_id' => $orderId, 'product_id' => $product_id, 'stock_id' => $stock_id));
        $query = $this->db->get();
        return $query->row();
    }

    public function updateTaxDetails($orderId, $product_id, $stock_id, $taxData) {
        $this->db->where('order_id', $orderId);
        $this->db->where('product_id', $product_id);
        $this->db->where('stock_id', $stock_id);
        return $this->db->update('order_tax_details', $taxData);
    }

    public function updateRequestTaxDetails($orderId, $product_id, $stock_id, $taxData) {
        $this->db->where('order_id', $orderId);
        $this->db->where('product_id', $product_id);
        $this->db->where('stock_id', $stock_id);
        return $this->db->update('order_request_tax_details', $taxData);
    }



     public function insertTaxDetailsRequest($taxData) {
        return $this->db->insert('order_request_tax_details', $taxData);
    }

   //transaction delete
    public function deleteTransaction($entry_id,$cancel=0) {
//    public function deleteTransaction($entry_id,$is_service_product=1,$cancel=0) {

        $this->db->trans_begin();
        $this->db->where('id', $entry_id);
        $this->db->update('entry', array('deleted' => 1,'cancel_status'=>$cancel));

        $this->db->where('entry_id', $entry_id);
        $this->db->update('ladger_account_detail', array('deleted' => 1));

        $this->db->where('entry_id', $entry_id);
        $this->db->update('tracking_details', array('deleted' => 1));

        $this->db->where('entry_id', $entry_id);
        $this->db->update('bank_details', array('deleted' => 1));

        $this->db->where('entry_id', $entry_id);
        $this->db->update('billwish_details', array('deleted' => 1));

        $this->db->where('entry_id', $entry_id);
        $o = $this->db->get('orders');
        if ($o->num_rows() > 0) {
            $is_service_product = 0;
        }

        if($is_service_product == 0){
            $this->db->select('id');
            $this->db->from('orders');
            $this->db->where('entry_id', $entry_id);
            $query = $this->db->get();
            $order= $query->row();
            if($order){
                $this->db->where('entry_id', $entry_id);
                $this->db->update('orders', array('status' => '0'));

                $this->db->where('order_id', $order->id);
                $this->db->update('ordered_products', array('status' => '0'));
            }
        }else{
            $this->db->select('id');
            $this->db->from('order_temp');
            $this->db->where('entry_id', $entry_id);
            $query = $this->db->get();
            $order= $query->row();
            if($order){
                $this->db->where('entry_id', $entry_id);
                $this->db->update('order_temp', array('status' => '0'));

                $this->db->where('order_id', $order->id);
                $this->db->update('order_temp_details', array('status' => '0'));
            }
        }
        if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         return 0;
        }else{
         $this->db->trans_commit();
         return $entry_id;
        }
    }

     //transaction delete
    public function deleteRequestTransaction($entry_id) {
        $this->db->trans_begin();
        $this->db->where('id', $entry_id);
        $this->db->update('entry_request', array('deleted' => 1));

        $this->db->where('entry_id', $entry_id);
        $this->db->update('entry_request_details', array('deleted' => 1));

        $this->db->select('id');
        $this->db->from('order_request');
        $this->db->where('entry_id', $entry_id);
        $query = $this->db->get();
        $order= $query->row();
        if($order){
        $this->db->where('entry_id', $entry_id);
        $this->db->update('order_request', array('status' => '0'));

        $this->db->where('order_id', $order->id);
        $this->db->update('order_request_details', array('status' => '0'));
        }
        if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
        }else{
         $this->db->trans_commit();
         return $entry_id;
        }
    }

     //transaction delete
    public function deleteRequestTransactionForQuotation($entry_id) {
        $this->db->trans_begin();
        $this->db->where('id', $entry_id);
        $this->db->update('quotation_entry_request', array('deleted' => 1));

        $this->db->where('entry_id', $entry_id);
        $this->db->update('quotation_entry_request_details', array('deleted' => 1));

        $this->db->select('id');
        $this->db->from('quotation_order_request');
        $this->db->where('entry_id', $entry_id);
        $query = $this->db->get();
        $order= $query->row();
        if($order){
        $this->db->where('entry_id', $entry_id);
        $this->db->update('quotation_order_request', array('status' => '0'));

        $this->db->where('order_id', $order->id);
        $this->db->update('quotation_order_request_details', array('status' => '0'));
        }
        if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
        }else{
         $this->db->trans_commit();
         return $entry_id;
        }
    }

     //transaction delete
    public function deleteTempEntry($entry_id) {
        $this->db->trans_begin();
        $this->db->where('id', $entry_id);
        $this->db->update('entry_temp', array('deleted' => 1));

        $this->db->where('entry_id', $entry_id);
        $this->db->update('entry_temp_details', array('deleted' => 1));

        if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
        }else{
         $this->db->trans_commit();
         return $entry_id;
        }
    }

     //transaction delete
    public function deleteTempTransaction($entry_id,$order_type) {
        $this->db->trans_begin();
        $this->db->where('id', $entry_id);
        $this->db->update('entry_temp', array('deleted' => 1));

        $this->db->where('entry_id', $entry_id);
        $this->db->update('entry_temp_details', array('deleted' => 1));

        $this->db->select('id');
        $this->db->from('orders');
        $this->db->where('entry_id', $entry_id);
        $this->db->where_in('order_type', $order_type);
        $query = $this->db->get();
        $order= $query->row();
        if($order){
        $this->db->where('entry_id', $entry_id);
        $this->db->where_in('order_type', $order_type);
        $this->db->update('orders', array('status' => '0'));

        $this->db->where('order_id', $order->id);
        $this->db->update('ordered_products', array('status' => '0'));
        }
        if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
        }else{
         $this->db->trans_commit();
         return $entry_id;
        }
    }

    public function getAllSubGroup($array_group) {
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

    public function getTotalEntryByType($entry_type_id,$parent_id) {
       $this->db->select('COUNT(id) as total_transaction');
        $this->db->from('entry_request');
        if ($parent_id == 0) {
            $this->db->where('sub_voucher', $parent_id);
            $this->db->where('entry_type_id', $entry_type_id);
        }else{
        $this->db->where('entry_type_id', $parent_id);
         $this->db->where('sub_voucher', $entry_type_id);
        }
        $query = $this->db->get();
        return $query->row();
    }

    public function getTotalQuotationByType($entry_type_id,$parent_id) {
       $this->db->select('COUNT(id) as total_transaction');
        $this->db->from('quotation_entry_request');
        $this->db->where('entry_type_id', $entry_type_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function getTotalTempEntryByType($entry_type_id,$parent_id) {
       $this->db->select('COUNT(id) as total_transaction');
        $this->db->from('entry_temp');
        if ($parent_id == 0) {
            $this->db->where('sub_voucher', $parent_id);
            $this->db->where('entry_type_id', $entry_type_id);
        }else{
        $this->db->where('entry_type_id', $parent_id);
         $this->db->where('sub_voucher', $entry_type_id);
        }
        $query = $this->db->get();
        return $query->row();
    }

    public function saveRequestEntry($entry) {
        $this->db->insert('entry_request', $entry);
        $entry_id = $this->db->insert_id();
        return $entry_id;
    }

    public function saveQuotationRequestEntry($entry) {
        $this->db->insert('quotation_entry_request', $entry);
        $entry_id = $this->db->insert_id();
        return $entry_id;
    }

     public function saveTempEntry($entry) {
        $this->db->insert('entry_temp', $entry);
        $entry_id = $this->db->insert_id();
        return $entry_id;
    }

    public function saveRequestEntryDetails($entryDetails) {
        $this->db->insert_batch('entry_request_details', $entryDetails);
    }

    public function saveQuotationRequestEntryDetails($entryDetails) {
        $this->db->insert_batch('quotation_entry_request_details', $entryDetails);
    }
    public function saveTempEntryDetails($entryDetails) {
        $this->db->insert_batch('entry_temp_details', $entryDetails);
    }

    public function saveRequestOrder($orders){
       $this->db->insert('order_request', $orders);
       $order_id = $this->db->insert_id();
       return $order_id;
   }

    public function saveQuotationRequestOrder($orders){
       $this->db->insert('quotation_order_request', $orders);
       $order_id = $this->db->insert_id();
       return $order_id;
   }

   public function saveOrder($orders){
       $this->db->insert('orders', $orders);
       $order_id = $this->db->insert_id();
       return $order_id;
   }

   public function insertOrderDetails($poducts){
     $this->db->insert_batch('ordered_products', $poducts);
   }

   public function insertRequestOrderDetails($poducts){
     $this->db->insert_batch('order_request_details', $poducts);
   }

   public function insertQuotationRequestOrderDetails($poducts){
     $this->db->insert_batch('quotation_order_request_details', $poducts);
   }

   public function insertTaxDetails($taxData){
    $this->db->insert_batch('order_tax_details', $taxData);
   }

   public function insertRequestTaxDetails($taxData){
    $this->db->insert_batch('order_request_tax_details', $taxData);
   }

    public function getAllRequestEntry($id,$parent_id, $limit=0, $offset=0) {
        $this->db->select('ER.*, entry_type.type');
        $this->db->from('entry_request ER');
        $this->db->order_by("ER.id", "asc");
        $this->db->join('entry_type', 'entry_type.id = ER.entry_type_id', 'left');
        $this->db->where('ER.status', 1);
//        $this->db->where('ER.deleted', 0);
        $where = '(ER.deleted = 0 OR (ER.deleted = 1 AND ER.cancel_status = 1))';
        $this->db->where($where);
        if($parent_id==0){
        $this->db->where('ER.sub_voucher', $parent_id);
        $this->db->where('ER.entry_type_id', $id);
        }else{
          $this->db->where('ER.sub_voucher', $id);
        $this->db->where('ER.entry_type_id', $parent_id);
        }
        $this->db->where('ER.is_inventry', 1);
        if ($limit != 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllQuotationRequestEntry($id,$parent_id, $limit=0, $offset=0) {
        $this->db->select('ER.*, entry_type.type');
        $this->db->from('quotation_entry_request ER');
        $this->db->join('entry_type', 'entry_type.id = ER.entry_type_id', 'left');
        
        $this->db->where('ER.status', 1);
//        $this->db->where('ER.deleted', 0);
        $where = '(ER.deleted = 0 OR (ER.deleted = 1 AND ER.cancel_status = 1))';
        $this->db->where($where);
        if($parent_id==0){
        $this->db->where('ER.sub_voucher', $parent_id);
        $this->db->where('ER.entry_type_id', $id);
        }else{
          $this->db->where('ER.sub_voucher', $id);
        $this->db->where('ER.entry_type_id', $parent_id);
        }
        $this->db->where('ER.is_inventry', 1);
        if ($limit != 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by("ER.id", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }

     public function getAllTempEntry($id,$parent_id, $limit=0, $offset=0) {
        $this->db->select('ER.*, entry_type.type');
        $this->db->from('entry_temp ER');
        $this->db->order_by("ER.id", "asc");
        $this->db->join('entry_type', 'entry_type.id = ER.entry_type_id', 'left');
       if($parent_id==0){
        $this->db->where('ER.sub_voucher', $parent_id);
        $this->db->where('ER.entry_type_id', $id);
        }else{
          $this->db->where('ER.sub_voucher', $id);
        $this->db->where('ER.entry_type_id', $parent_id);
        }
        $this->db->where('ER.status', 1);
//        $this->db->where('ER.deleted', 0);
        $where = '(ER.deleted = 0 OR (ER.deleted = 1 AND ER.cancel_status = 1))';
        $this->db->where($where);

        $this->db->where('ER.is_inventry', 1);
        if ($limit != 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        return $query->result_array();
    }


     public function getCompanyDetails() {
        $this->db->select('account_settings.*,states.name as state_name,states.state_code as state_code');
        $this->db->from('account_settings');
        $this->db->join('states', 'states.id = account_settings.state_id');
        $this->db->where('account_settings.id', 1);
        $query = $this->db->get();
        return $query->row();
    }

    public function getBranchDetails() {
        $this->db->select('account_settings.*,states.name as state_name,states.state_code as state_code');
        $this->db->from('account_settings');
        $this->db->join('states', 'states.id = account_settings.state_id');
        $this->db->where('account_settings.id',$this->session->userdata('branch_id'));
        $query = $this->db->get();
        return $query->row();
    }

     public function getLedgerContactDetails($ledger_id) {
        $this->db->select('customer_details.*,states.name as state_name,states.state_code as state_code,countries.name as country_name');
        $this->db->from('customer_details');
        $this->db->join('states', 'states.id = customer_details.state');
        $this->db->join('countries', 'countries.id = customer_details.country');
        $this->db->where('ledger_id', $ledger_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function allAdvanceBill($bill_name,$ledger_id) {
        $this->db->select('id, bill_name');
        $this->db->from('billwish_details');
        $this->db->like('bill_name', $bill_name, 'after');
        $this->db->where('ref_type', 'Advance Reference');
        $this->db->where('deleted','0');
        $this->db->where('ledger_id', $ledger_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getSalesDetails($entry_no){
        $query = $this->db->query("SELECT f.product_id, f.stock_id, s.TotalQuantity FROM pb_ordered_products AS f JOIN (SELECT OP.product_id,OP.stock_id, SUM(OP.quantity) AS TotalQuantity FROM pb_ordered_products as OP LEFT JOIN pb_orders as O ON O.id=OP.order_id LEFT JOIN pb_entry as E ON E.id=O.entry_id WHERE E.status='1' AND E.deleted='0' AND E.voucher_no='".$entry_no."' AND OP.status='1' GROUP BY OP.product_id,OP.stock_id ) AS s ON (f.product_id = s.product_id AND f.stock_id=s.stock_id) GROUP BY f.product_id,f.stock_id");
        return $query->result();
    }

    public function getOrderDetailsByEno($entry_no){
        $query = $this->db->query("SELECT OP.product_id,OP.stock_id, OP.quantity FROM pb_order_request_details as OP LEFT JOIN pb_order_request as O ON O.id=OP.order_id LEFT JOIN pb_entry_request as E ON E.id=O.entry_id WHERE E.status='1' AND E.deleted='0' AND E.entry_no='".$entry_no."' AND OP.status='1'");
        return $query->result();
    }

    public function getQuotationOrderDetailsByEno($entry_no){
        $query = $this->db->query("SELECT OP.product_id,OP.stock_id, OP.quantity FROM pb_quotation_order_request_details as OP LEFT JOIN pb_quotation_order_request as O ON O.id=OP.order_id LEFT JOIN pb_quotation_entry_request as E ON E.id=O.entry_id WHERE E.status='1' AND E.deleted='0' AND E.entry_no='".$entry_no."' AND OP.status='1'");
        return $query->result();
    }

    public function getAllSubVouchersById($id,$service_voucher_id='') {
        $this->db->select('ET.*,E.type as parent');
        $this->db->from('entry_type ET');
        $this->db->join('entry_type E', 'ET.parent_id = E.id');
        $this->db->order_by('ET.id', 'desc');
        $this->db->where('ET.parent_id', $id);
        if($service_voucher_id){ //It is sub voucher of purchase but not showing under purchase .It is shown on main list. 20032018 @sudip
           $this->db->where('ET.id !=', $service_voucher_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function allContacts($term){
        $this->db->select('id, company_name');
        $this->db->from('customer_details');
        $this->db->like('company_name', $term, 'after');
        $this->db->where('status','1');
        $query = $this->db->get();
        return $query->result();
    }

    public function getLedgerByGroupsId($all_group_id) {
        $this->db->select('id');
        $this->db->from('ladger');
        $this->db->where(array('status' => '1', 'deleted' => '0'));
        $this->db->where_in('group_id', $all_group_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function checkDuplicateEntryno($entry_number, $entry_type_id)
    {
        $this->db->where(
            array(
                'entry_no' => $entry_number,
                'entry_type_id' => $entry_type_id,
                'company_id' => $this->session->userdata('branch_id'),
                'deleted'   => 0
            )
        );
        $query = $this->db->get('entry');
        return $query->num_rows();
    }

    public function checkDuplicateVoucherno($voucher_no, $entry_type_id, $action, $entry_id)
    {
        if($action == "e"){
            $this->db->where('id !=', $entry_id);
        }
        $this->db->where(
            array(
                'voucher_no' => $voucher_no,
                'entry_type_id' => $entry_type_id,
                'company_id' => $this->session->userdata('branch_id'),
                'deleted'   => 0
            )
        );

        $query = $this->db->get('entry');
        return $query->num_rows();
    }

    public function getMultiShippingAddress($id) {

        $this->db->select('s.id, s.address, s.city, s.country, s.state, s.zip, s.default_ship, s.default_ship, c.company_name, c.gstn_no AS sales_tax_no, co.name as country_name, st.name as state_name');
        $this->db->from('shipping_address as s');
        $this->db->join('customer_details as c', 's.users_id = c.ledger_id', 'left');
        $this->db->join('countries as co', 's.country = co.id', 'left');
        $this->db->join('states as st', 's.state = st.id', 'left');
        $this->db->where(array('s.customer_details_id' => $id));
        $this->db->order_by('default_ship', 'desc');
        $query = $this->db->get();
        // return $query->row();
        return $query->result();
    }

    public function getTransactionDetailsByEntry($entry_id)
    {
        $this->db->select('e.entry_no, e.create_date, e.system_time,e.narration, et.type as entry_type, L.account_type as account_type, L.ladger_name as ledger_name, o.total as order_total, o.tax_amount as total_tax, LD.balance');
        $this->db->from('entry e');
        $this->db->join('entry_type et', 'e.entry_type_id = et.id', 'left');
        $this->db->join('ladger_account_detail LD', 'LD.entry_id = e.id', 'left');
        $this->db->join('ladger L', 'L.id = LD.ladger_id');
        $this->db->join('orders o', 'e.id = o.entry_id');
        $this->db->where('e.status', '1');
        $this->db->where('e.deleted =', '0');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.deleted =', '0');
        $this->db->where('e.id', $entry_id);
        $query = $this->db->get();
        // return $this->db->last_query();
        return $query->result();
    }

    public function getPreferences()
    {
        $this->db->where('id', 1);
        return $this->db->get('account_configuration')->row();
    }

    public function getLedgerName($ledger_id)
    {
        $this->db->select('ladger_name');
        $this->db->from('ladger');
        $this->db->where('id', $ledger_id);
        $ledger = $this->db->get()->row();
        return $ledger->ladger_name;
    }

    public function getAvailableContact($company = '')
    {
        $this->db->select("CD.company_name,CD.id");
        $this->db->from('customer_details CD');
        $this->db->where('ledger_id', '0');
        $this->db->where('status', '1');
        if($company){
        $this->db->like('CD.company_name', $company);
        }
        $this->db->order_by("CD.company_name", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    public function getDefauldBankId(){
        return $this->db->select("*")->from('bank_details_company')->where('default_bank', '1')->get()->row();
    }

     //get ledger by ledger id and date range @sudip 02052018
    public function getLedgerByLedgerIdByDate($id = NULL, $from_date, $to_date,$branch_id) {
        $from_date=$from_date.' 00:00:00';
        $to_date=$to_date.' 23:59:59';
        $this->db->select('L.id,L.group_id,L.ladger_name,LD.account AS account_type,L.ledger_code,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id = 0 AND LD.branch_id IN ('.$branch_id.')) AS opening_balance,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = "Cr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND LD.branch_id IN ('.$branch_id.') AND LD.create_date >=' . "'$from_date'" . ' AND LD.create_date <=' . "'$to_date'" . ') AS cr_balance,(SELECT SUM(LD.balance) FROM pb_ladger_account_detail AS LD WHERE LD.ladger_id = L.id AND LD.account = "Dr" AND LD.deleted = 0 AND LD.status = 1 AND LD.entry_id != 0 AND LD.branch_id IN ('.$branch_id.') AND LD.create_date >=' . "'$from_date'" . ' AND LD.create_date <=' . "'$to_date'" . ') AS dr_balance',FALSE);
        $this->db->from('ladger AS L');
        $this->db->join('ladger_account_detail AS LD', 'L.id = LD.ladger_id');
        $this->db->where('L.id', $id);
        $this->db->where('L.deleted', '0');
        $this->db->where('L.status', '1');
        $this->db->where('L.created_date >=', $from_date);
        $this->db->where('L.created_date <=', $to_date);
        $this->db->group_by('L.id');
        $query = $this->db->get();
        return $query->row_array();
    }

   //update Closing balance
    public function updateClosingBalance($where,$closingValue) {
        $this->db->where($where);
        return $this->db->update('ladger_account_detail', $closingValue);
    }

     public function getLedgerByEntryId($entry_id){
        $this->db->select('LD.ladger_id');
        $this->db->from('ladger_account_detail LD');
        $this->db->where('LD.status', '1');
        $this->db->where('LD.deleted =', '1');
        $this->db->where('LD.entry_id', $entry_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
     public function getDespatchDetailsById($id){
        $this->db->select('*');
        $this->db->from('despatch_details');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }
    
    /**
     * get despatch details for listing in despatch details section if get despatch through
     * 
     * @return type object
     */
    public function getAllDespatchDetails($despatch_through){
        $this->db->select('id,despatch_through');
        $this->db->from('despatch_details');
        $this->db->where('despatch_through !=', '');
        $this->db->like('despatch_through', $despatch_through, 'after');
        $this->db->group_by('despatch_through');
        $query = $this->db->get();
        return $query->result();
       
    }
    
    public function getDespatchDetailsByEntryId($entry_id){
        $this->db->select('*');
        $this->db->from('despatch_details');
        $this->db->where('entry_id', $entry_id);
        $query = $this->db->get();
        return $query->row();
    }
    
    public function getEwaybillByEntryId($entry_id){
        $this->db->select('*');
        $this->db->from('ewaybill');
        $this->db->where('entry_id', $entry_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function getCount($id,$parent_id,$search='') {
        $this->db->select("E.*, entry_type.type, GROUP_CONCAT(CONCAT(l.ladger_name, ' [',ld.account, ']') SEPARATOR '/') as ledger_detail,W.eway_bill_no,W.supply_type as w_type,W.cancel_status as w_cancel_status", FALSE);
        $this->db->from('entry AS E');
        /* ========= */

        $this->db->join('ladger_account_detail as ld', 'ld.entry_id = E.id', 'left');
        $this->db->join('ladger as l', 'l.id = ld.ladger_id', 'left');

        /* ========= */

         if ($parent_id == 0) {
            $this->db->join('entry_type', 'entry_type.id = E.entry_type_id', 'left');
            $this->db->where('E.sub_voucher', $parent_id);
            $this->db->where('E.entry_type_id', $id);
        } else {
            $this->db->join('entry_type', 'entry_type.id = E.sub_voucher', 'left');
            $this->db->where('E.sub_voucher', $id);
            $this->db->where('E.entry_type_id', $parent_id);
        }
        //Ewaybill
        $this->db->join('ewaybill W', 'W.entry_id = E.id', 'left');
        
        $this->db->where('E.status', 1);
        $this->db->where('E.company_id', $this->session->userdata('branch_id'));
        $where = '(E.deleted = 0 OR (E.deleted = 1 AND E.cancel_status = 1))';
        $this->db->where($where);
        $this->db->where('E.is_inventry', 1);
//        $this->db->or_like('E.create_date', $search);
        $this->db->like('E.entry_no', $search);
//        $this->db->or_like('entry_type.type', $search);
//        $this->db->or_like('E.cr_amount', $search);
//        $this->db->or_like('W.eway_bill_no', $search);
//        $this->db->or_like('l.ladger_name', $search);
        $this->db->group_by('E.id');
        $this->db->order_by("E.create_date", "asc");
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getRecurringCount($id,$parent_id,$search='') {
        $this->db->select('entry.*, entry_type.type');
        $this->db->from('recurring_entry');

        $this->db->join('entry', 'entry.id = recurring_entry.entry_id', 'left');
        $this->db->join('entry_type', 'entry_type.id = entry.entry_type_id', 'left');
         if ($parent_id == 0) {
            $this->db->where('entry.sub_voucher', $parent_id);
            $this->db->where('entry.entry_type_id', $id);
        } else {
            $this->db->where('entry.sub_voucher', $id);
            $this->db->where('entry.entry_type_id', $parent_id);
        }
        $this->db->where('entry.company_id', $this->session->userdata('branch_id'));
        $this->db->where('entry.status', 1);
        $this->db->where('entry.deleted', 0);
        $this->db->where('entry.is_inventry', 1);

        $this->db->where("recurring_entry.status", '1');
        $this->db->like('entry.entry_no', $search);
        $this->db->order_by("entry.id", "asc");
        $this->db->group_by("recurring_entry.entry_id");
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getAllPostDatedEntryCount($id,$parent_id,$search='') {
        $this->db->select('entry.*, entry_type.type');
        $this->db->from('entry');
        $this->db->order_by("entry.id", "asc");
        $this->db->join('entry_type', 'entry_type.id = entry.entry_type_id', 'left');
        if ($parent_id == 0) {
            $this->db->where('entry.sub_voucher', $parent_id);
            $this->db->where('entry.entry_type_id', $id);
        } else {
            $this->db->where('entry.sub_voucher', $id);
            $this->db->where('entry.entry_type_id', $parent_id);
        }
        $this->db->where('entry.company_id', $this->session->userdata('branch_id'));
        $this->db->where('entry.status', 1);
        $this->db->where('entry.deleted', 2);
        $this->db->where('entry.is_inventry', 1);
        $this->db->like('entry.entry_no', $search);

        $query = $this->db->get();
        return $query->num_rows();
    }
    
    public function insertBatch($batchs){
     $this->db->insert_batch('batch_stock', $batchs);
   }
   
    public function insertGodown($godowns){
     $this->db->insert_batch('godown_stock', $godowns);
   }
   
   public function getAllGodownsByProductId($search,$pro_id){
        $this->db->select('godown_id,godown_name,ABS(SUM(quantity_in) - SUM(quantity_out)) AS quantity');
        $this->db->from('godown_stock');
        $this->db->where('active_status', 1);
        $this->db->where('delete_status', 0);
        $this->db->where('product_id', $pro_id);
        $this->db->group_by('godown_id');
        if($search != ""){
            $this->db->like('godown_name',$search);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
   public function getAllBatchByGodownIdProductId($search,$pro_id,$godown_id){
        $this->db->select('id,batch_no,ABS(SUM(IF(in_out = 1,quantity,0)) - SUM(IF(in_out = 2,quantity,0))) AS quantity,exp_type,exp_days_given,DATE_FORMAT(manufact_date,"%d-%m-%Y") AS manufact_date,exp_date',FALSE);
        $this->db->from('batch_stock');
        $this->db->where('active_status', 1);
        $this->db->where('delete_status', 0);
        $this->db->where('product_id', $pro_id);
        $this->db->where('godown_id', $godown_id);
        $this->db->group_by('batch_no');
        if($search != ""){
            $this->db->like('batch_no',$search);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
   public function getAllBatchByBatchId($batchId){
        $this->db->select('id,batch_no,exp_type,exp_days_given,manufact_date,exp_date');
        $this->db->from('batch_stock');
        $this->db->where('id', $batchId);
        $query = $this->db->get();
        return $query->row();
    }
   public function getBatchDetailsByStockId($stockId){
        $this->db->select('B.id,B.batch_no,B.exp_type,B.exp_days_given,DATE_FORMAT(B.manufact_date,"%d-%m-%Y") AS manufact_date,B.godown_id,B.product_id,B.quantity,B.rate,B.value,exp_date,G.name',FALSE);
        $this->db->from('batch_stock B');
        $this->db->join('godowns G', 'G.id = B.godown_id');
        $this->db->where('B.stock_id', $stockId);
        $this->db->where('B.delete_status', 0);
        $query = $this->db->get();
        return $query->result();
    }
   public function getGodownDetailsStockId($stockId){
        $this->db->select('G.*');
        $this->db->from('godown_stock G');
        $this->db->where('G.stock_id', $stockId);
        $this->db->where('G.delete_status', 0);
        $query = $this->db->get();
        return $query->result();
    }
    
    public function deleteGodown($orderId,$product_id) {
        $this->db->where('stock_id', $orderId);
        $this->db->where('product_id', $product_id);
        $this->db->update('godown_stock', array('delete_status' => '1'));
    }
    
    public function deleteBatch($orderId,$product_id) {
        $this->db->where('stock_id', $orderId);
        $this->db->where('product_id', $product_id);
        $this->db->update('batch_stock', array('delete_status' => '1'));
    }

    public function getCountQuotation()
    {
        $this->db->where('status', 1);
        $this->db->where('deleted', 0);
        $query = $this->db->get('quotation_entry_request');
        return $query->num_rows();
    }

    public function saveQuotationSalesOrderRelation($data)
    {
        $this->db->insert('quotation_sales_order_relation', $data);
    }
    
    public function getUnitTree($id){
        $query = $this->db->query("select name,unit_id,qty
                from (select pb_complex_units.*,pb_units.name from pb_complex_units, pb_units
                  where pb_units.id = pb_complex_units.unit_id order by base_unit_id, unit_id) pb_complex_units,
                    (select @pv := ".$id.") initialisation 
                where find_in_set(base_unit_id, @pv) >= 0
                and @pv := concat(@pv, ',', unit_id)",FALSE);
//        return  $this->db->last_query();
        return $query->result();
        
    }
    
    public function getUnitDetailsByComplexUnitId($complex_unit_id){
        $this->db->select('*');
        $this->db->from('pb_complex_units');
        $this->db->where('unit_id', $complex_unit_id);
        $query = $this->db->get();
        return $query->row();
    }

}
?>
