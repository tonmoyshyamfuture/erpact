<?php

class custominventorymodel extends CI_Model {

    public function __construct() {

        parent::__construct();
        $this->load->database();
    }


     public function getLedger($ledger, $ledger_array) {
       $this->db->select('id, ladger_name');
       $this->db->from('ladger');
       $this->db->like('ladger_name', $ledger, 'after');
       $this->db->where(array('status' => '1', 'deleted' => '0'));
       $this->db->where_in('id', $ledger_array);
       $query = $this->db->get();
       return $query->result();
    }

    public function getShippingDet($id){

       $this->db->select('country, state');
       $this->db->from('shipping_address');
       $this->db->where(array('users_id' => $id));
       $query = $this->db->get();
       return $query->row();

    }

    public function ledgerLimitDetails($id){
       $this->db->select('credit_date, credit_limit');
       $this->db->from('ladger');
       $this->db->where(array('id' => $id));

       $query = $this->db->get();
       return $query->row();

    }

    public function getShippingAddress($id){
      $this->db->select('s.address, s.city, s.country, s.state, s.zip, c.company_name, c.sales_tax_no');
      $this->db->from('shipping_address as s');
      $this->db->join('customer_details as c', 's.users_id = c.ledger_id', 'left');
      $this->db->where(array('s.users_id' => $id));
      $query = $this->db->get();
      return $query->row();
    }
    
    public function getShippingAddressByID($id){
      $this->db->select('s.address, s.city, s.country, s.state, s.zip, c.company_name, c.sales_tax_no');
      $this->db->from('shipping_address as s');
      $this->db->join('customer_details as c', 's.users_id = c.ledger_id', 'left');
      $this->db->where(array('s.id' => $id));
      $query = $this->db->get();
      return $query->row();
    }

    public function getBillingAddress(){
      $this->db->select('company_name, street_address, country,country_id, state_id, city_name, zip_code, service_tax');
      $this->db->from('account_settings');
      $query = $this->db->get();
      return $query->row();
    }

     public function getCountryName($cId){

      $this->db->select('name');
      $this->db->from('countries');
      $this->db->where(array('id' => $cId));

      $query = $this->db->get();
      return $query->row();

    }

    public function getStateName($sId){

      $this->db->select('s.name AS state, c.name AS country');
      $this->db->from('states as s');
      $this->db->join('countries as c', 'c.id = s.country_id');
      $this->db->where(array('s.id' => $sId));

      $query = $this->db->get();
      return $query->row();

    }


    public function getProducts($productSearch,$type_service_product){

      $this->db->select('pid as product_id, s.id, p.name, SUM(s.quantity) as quantity, s.stockdet, p.type,p.sku', FALSE);
      $this->db->from('product_stock as s');
      $this->db->join('products as p', 'p.id = s.pid');
//      if($type_service_product==1){
//            $this->db->where(array('type' => 1));    
//      }else{
//            $this->db->where(array('type' => 0));      
//      }
      // $this->db->like('p.name', $productSearch, 'after');
      $this->db->where(array('status' => 1, 'p.discontinue' => 0));
      $this->db->where(array('archive_status' => 1)); 
      $this->db->where('s.branch_id',$this->session->userdata('branch_id')); //@sudip For opening balance
      if ($productSearch) {
      $this->db->where("(p.name LIKE '%".$productSearch."%' OR p.sku LIKE '%".$productSearch."%')", NULL, FALSE);
      }
      $this->db->group_by('p.id');
      $query = $this->db->get();
      // echo $this->db->last_query();exit();   
      return $query->result();   
    }

    public function getProductTax($pId){

      $this->db->select('tax_class');
      $this->db->from('products');
      $this->db->where(array('id' => $pId));

      $query = $this->db->get();
      return $query->row();

    }
    
    public function getProductShortDescription($pId){

      $this->db->select('short_description');
      $this->db->from('products');
      $this->db->where(array('id' => $pId));

      $query = $this->db->get();
      return $query->row();

    }

    public function getProductPrice($sId){

      $this->db->select('price, id');
      $this->db->from('product_stock');
      $this->db->where(array('id' => $sId));
//      $this->db->where('branch_id',$this->session->userdata('branch_id'));//@sudip For opening balance
      $query = $this->db->get();
      return $query->row();

    }
    
    //@sudip
    public function getProductIdByStockId($sId){
      $this->db->select('pid');
      $this->db->from('product_stock');
      $this->db->where(array('id' => $sId));
      $query = $this->db->get();
      return $query->row();

    }
    
    public function getProductSalesProceBypsId($psId){
      $this->db->select('sales_price');
      $this->db->from('product_sales_prices');
      $this->db->where('ps_id', $psId);
      $this->db->where('sales_date <=', date('Y-m-d'));
      $this->db->where('status', 1);
      $this->db->order_by('id', 'DESC');
      $this->db->limit(1,0);
      $query = $this->db->get();
      return $query->row();

    }
    
    public function getProductBatchByStockId($sId){
      $this->db->select('having_batch');
      $this->db->from('product_stock');
      $this->db->where(array('id' => $sId));
      $query = $this->db->get();
      return $query->row();
    }

  

    public function getProductUnit($sid){
      $this->db->select('u.name, u.id');
      $this->db->from('units as u');
      $this->db->join('product_stock as s', 'u.id = s.qty_unit');
      $this->db->where(array('s.id' => $sid));

      $query = $this->db->get();
      return $query->row();
    }

    public function getProductTaxPercent($taxC, $country, $state){



      $this->db->select('tax_rate');
      $this->db->from('taxclass_details');
      
      if($taxC == 2){
        $this->db->where(array('class_id' => $taxC, 'country_id' => $country, 'state_id' => $state));
      }else if($taxC == 1){
        $this->db->where(array('class_id' => $taxC));
      }
      

      $query = $this->db->get();
      return $query->row();

    }

    public function getProductTaxPercentGST($pId){

      $this->db->select('g.id as gst_goods_id ,s.id as tax_slab_id, s.tax_amount, g.cess_present, g.cess_value');
      $this->db->from('products as p');
      $this->db->where(array('p.id' => $pId));
      $this->db->join('gst_for_good as g', 'p.goods_gst_id = g.id');
      $this->db->join('gst_tax_slab as s', 'g.tax_slab_id = s.id');

      $query = $this->db->get();
     
      return $query->row();
    }  

    public function setOrder($orders){
       $this->db->insert('orders', $orders);
       $order_id = $this->db->insert_id();
       return $order_id;
   }
   //insert temp order
   public function setTempOrder($orders){
       $this->db->insert('order_temp', $orders);
       $order_id = $this->db->insert_id();
       return $order_id;
   }

   public function insertOrderDetails($poducts){
     $this->db->insert_batch('ordered_products', $poducts);
   }
   
   //insert temporder details
   public function insertTempOrderDetails($poducts){
     $this->db->insert_batch('order_temp_details', $poducts);
   }
   
   public function insertTaxDetails($taxData){
    $this->db->insert_batch('order_tax_details', $taxData);   
   }
   
   public function getOpeningQuantity($product_id){
     $sql = "SELECT SUM(`quantity`) AS opening_qty FROM pb_product_stock_dtls WHERE added_by='add' AND pid='" . $product_id . "'";
     $query = $this->db->query($sql);
     return $query->row();  
   }
   public function getOpeningQuantityNew($id){
     $sql = "SELECT SUM(`quantity`) AS opening_qty FROM pb_product_stock WHERE id='" . $id . "' AND branch_id =".$this->session->userdata('branch_id');
     $query = $this->db->query($sql);
     return $query->row();  
   }
   
   public function getTotalStockIn($product_id) {
        $sql = "SELECT SUM(op.quantity) AS stockin
       FROM " . tablename('ordered_products') . "  as op 
       LEFT JOIN " . tablename('orders') . " as o ON op.order_id = o.id
       LEFT JOIN " . tablename('entry') . " as e ON e.id = o.entry_id
       WHERE o.order_type IN(2,4,5,7,9) AND op.status='1' AND o.flow_type='0' AND op.product_id = '" . $product_id . "' AND (op.return_status != '4' and op.return_status != '5' and op.return_status != '6')  and o.delivery_status!='4' and (o.is_paid = '1' OR o.payment_method = 'COD')  
       GROUP BY op.product_id";

        $query = $this->db->query($sql);

        return $query->row();
    }
   public function getTotalStockInNew($stock_id) {
        $sql = "SELECT SUM(op.quantity) AS stockin
       FROM " . tablename('ordered_products') . "  as op 
       LEFT JOIN " . tablename('orders') . " as o ON op.order_id = o.id
       LEFT JOIN " . tablename('entry') . " as e ON e.id = o.entry_id
       WHERE o.order_type IN(2,4,5,7,9) AND op.status='1' AND o.flow_type='0' AND op.stock_id = '" . $stock_id . "' AND (op.return_status != '4' and op.return_status != '5' and op.return_status != '6')  and o.delivery_status!='4' and (o.is_paid = '1' OR o.payment_method = 'COD')  
       GROUP BY op.product_id";

        $query = $this->db->query($sql);

        return $query->row();
    }
    
     public function getTotalStockOut($product_id) {
        $sql = "SELECT SUM(op.quantity) AS stockout
       FROM " . tablename('ordered_products') . "  as op 
       LEFT JOIN " . tablename('orders') . " as o ON op.order_id = o.id
           LEFT JOIN " . tablename('entry') . " as e ON e.id = o.entry_id
       WHERE o.order_type IN(1,3,6,8,9) AND op.status='1' AND o.flow_type='1' AND op.product_id = '" . $product_id . "' AND (op.return_status != '4' and op.return_status != '5' and op.return_status != '6')  and o.delivery_status!='4' and (o.is_paid = '1' OR o.payment_method = 'COD')  
       GROUP BY op.product_id";

        $query = $this->db->query($sql);

        return $query->row();
    }
     public function getTotalStockOutNew($stock_id) {
        $sql = "SELECT SUM(op.quantity) AS stockout
       FROM " . tablename('ordered_products') . "  as op 
       LEFT JOIN " . tablename('orders') . " as o ON op.order_id = o.id
           LEFT JOIN " . tablename('entry') . " as e ON e.id = o.entry_id
       WHERE o.order_type IN('1','3','6','8','9','20') AND op.status='1' AND o.flow_type='1' AND op.stock_id = '" . $stock_id . "' AND (op.return_status != '4' and op.return_status != '5' and op.return_status != '6')  and o.delivery_status!='4' and (o.is_paid = '1' OR o.payment_method = 'COD')  
       GROUP BY op.product_id";

        $query = $this->db->query($sql);

        return $query->row();
    }
    
    public function getProductType($pId){
      $this->db->select('p.type');
      $this->db->from('products as p');
      $this->db->where(array('p.id' => $pId));
      $query = $this->db->get();   
      return $query->row();   
    }

     public function getProductStockOut($product_id) {
         $sql = "SELECT SUM(`op`.`quantity`*`op`.`original_price`) AS out_price,SUM(op.quantity) AS stockout
        FROM " . tablename('ordered_products') . "  as op 
        LEFT JOIN " . tablename('orders') . " as o ON op.order_id = o.id 
        WHERE o.order_type IN('1','8','9','20', '21') AND op.status='1' AND op.branch_id IN(".$this->session->userdata('selected_branch_str').") AND o.flow_type='1' AND op.product_id = '" . $product_id . "' AND (op.return_status != '4' and op.return_status != '5' and op.return_status != '6')  and o.delivery_status!='4' and (o.is_paid = '1' OR o.payment_method = 'COD')  
        GROUP BY op.product_id";
        $stockOut = $query = $this->db->query($sql)->row();
        // print_r($stockOut->stockout);exit();
        return $stockOut->stockout;
    }

}

?>    


