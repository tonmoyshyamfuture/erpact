<?php
class productdetailsmodel extends CI_Model
{
    public function __construct()
	{
        parent::__construct();
        $this->load->database();
	}

	function object_to_array($data)
	{
	    if (is_array($data) || is_object($data))
	    {
	        $result = array();
	        foreach ($data as $key => $value)
	        {
	            $result[$key] = $this->object_to_array($value);
	        }
	        return $result;
	    }
	    return $data;
	}

	
	function getProductDetails($pid,$str_attribute="")
	{

		// Strat Get Product Details
		$sql_product = "SELECT * FROM ". tablename('products')." WHERE id = '".$pid."'";
		$query_product = $this->db->query($sql_product);
        $res_product = $query_product->row();
        $ary_product_dtls = $this->object_to_array($res_product);
        // .. End Get Product Details


        $str_attribute=trim($str_attribute);

        $search_attribute="";
        if($str_attribute!="")
        {
        	$search_attribute=" and op.product_attribute='".$str_attribute."' ";
        }

		// Start Get No of quantity order placed for this product
        $sql_qnty_ordered = "SELECT SUM(op.quantity) AS qnty_ordered
        FROM ". tablename('ordered_products')."  as op 
        LEFT JOIN ". tablename('orders')." as o ON op.order_id = o.id 
        WHERE op.product_id = '".$pid."' ".$search_attribute." and o.delivery_status!='4' 
        GROUP BY op.product_id";
        $query_qnty_ordered = $this->db->query($sql_qnty_ordered);
        $res_qnty_ordered = $query_qnty_ordered->row();

        $qnty_ordered=0;
        if(!empty($res_qnty_ordered))
        {
            if(!empty($res_qnty_ordered->qnty_ordered))
            {
                $qnty_ordered=$res_qnty_ordered->qnty_ordered;
            }
        }
        $ary_product_dtls['qnty_ordered']=$qnty_ordered;
        // .. End Get No of quantity order placed for this product


        // Start Get Quantity for the product
        $product_qnty=0;
        if($str_attribute!="")
        {
        	$sql_product_qnty = "SELECT SUM(quantity) AS product_qnty
	        FROM ". tablename('product_stock')." WHERE pid = '".$pid."' and stockdet='".$str_attribute."' ";

	        $query_product_qnty = $this->db->query($sql_product_qnty);
	        $res_product_qnty = $query_product_qnty->row();

	       
	        if(!empty($res_product_qnty))
	        {
	            if(!empty($res_product_qnty->product_qnty))
	            {
	                $product_qnty=$res_product_qnty->product_qnty;
	            }
	        }
        }
        
        if($product_qnty<=0)
        {
            $product_qnty=$res_product->stock;
        }

        $ary_product_dtls['product_qnty']=$product_qnty;
        // .. End Get Quantity for the product
        
        
        // somnath - stock in, out calculation starts
        
        // stock in calculation for a product
        $sql = "SELECT SUM(op.quantity) AS stockin
       FROM " . tablename('ordered_products') . "  as op 
       LEFT JOIN " . tablename('orders') . " as o ON op.order_id = o.id
       LEFT JOIN " . tablename('entry') . " as e ON e.id = o.entry_id
       WHERE o.order_type IN(2,4,5,7,9) AND op.status='1' AND e.company_id IN(".$this->session->userdata('selected_branch_str').") AND o.flow_type='0' AND op.product_id = '" . $pid . "' AND (op.return_status != '4' and op.return_status != '5' and op.return_status != '6')  and o.delivery_status!='4' and (o.is_paid = '1' OR o.payment_method = 'COD')  
       GROUP BY op.product_id";

        $query = $this->db->query($sql);
        $stockin = $query->row();
        $stockin_qty = 0;
        if($stockin) {
            $stockin_qty = $stockin->stockin;
        }
        
        // stock out calculation for a product
        $sql = "SELECT SUM(op.quantity) AS stockout
       FROM " . tablename('ordered_products') . "  as op 
       LEFT JOIN " . tablename('orders') . " as o ON op.order_id = o.id
           LEFT JOIN " . tablename('entry') . " as e ON e.id = o.entry_id
       WHERE o.order_type IN(1,3,6,8,9) AND op.status='1' AND e.company_id IN(".$this->session->userdata('selected_branch_str').") AND o.flow_type='1' AND op.product_id = '" . $pid . "' AND (op.return_status != '4' and op.return_status != '5' and op.return_status != '6')  and o.delivery_status!='4' and (o.is_paid = '1' OR o.payment_method = 'COD')  
       GROUP BY op.product_id";

        $query = $this->db->query($sql);
        $stockout = $query->row();
        $stockout_qty = 0;
        if($stockout) {
            $stockout_qty = $stockout->stockout;
        }
        
        
        
        //somnath - stock in, out calculation ends


        // Start Get Availbale Stock for the product
        //$available_qnty=$product_qnty-$qnty_ordered;
        $available_qnty = ($product_qnty + $stockin_qty) - $stockout_qty; // somnath - new stock calculation with in, out calculation
        if($available_qnty<=0 || $available_qnty==NULL)
        {
            $available_qnty=0;
        }

        $ary_product_dtls['available_qnty']=$available_qnty;
        // .. End Get Availbale Stock for the product


        // Start Get Price for the product
        $product_price=0;
        if($str_attribute!="")
        {
        	$sql_product_price = "SELECT price AS product_price
	        FROM ". tablename('product_stock')." WHERE pid = '".$pid."' and stockdet='".$str_attribute."' ";

	        $query_product_price = $this->db->query($sql_product_price);
	        $res_product_price = $query_product_price->row();

	        
	        if(!empty($res_product_price))
	        {
	            if(!empty($res_product_price->product_price))
	            {
	                $product_price=$res_product_price->product_price;
	            }
	        }
        }

        
        if($product_price<=0)
        {
            $product_price=$res_product->price;
        }

        $ary_product_dtls['product_price']=$product_price;
        // .. End Get Quantity for the product

        // Get All Discount
        $resrplugin= $this->checkproductdiscountplugin();
        $ary_product_dtls['all_discount_dtls']=array();
        $ary_product_dtls['on_sale']='N';
        if(!empty($resrplugin))
        {
	        $ary_all_discount= $this->getalldiscountforproduct($pid,$str_attribute);

	        if(!empty($ary_all_discount))
	        {
	            $ary_product_dtls['all_discount_dtls']=$ary_all_discount;
                $ary_product_dtls['on_sale']='Y';
	        }
        }
        //.. Get All Discount

       return $ary_product_dtls;
        
       /* //echo "<pre>";
        print_r( $ary_product_dtls);
        exit;*/
	}


   /* public function getproductdiscount($pid)
    {
        $sqldiscount = "select * from " . tablename('product_discount') . " where product_id='".$pid."'";
        $querydiscount = $this->db->query($sqldiscount);
        $rrdiscount = $querydiscount->row();

        $newresval=array();
        if (!empty($rrdiscount)) {
            
            if($rrdiscount->fore_ever==1)
            {
                $newresval['discount_amount'] = $rrdiscount->discount_amount;
                $newresval['discount_type'] = $rrdiscount->discount_type;
            }
            elseif((strtotime($rrdiscount->from_date)<=strtotime(date('Y-m-d'))) && (strtotime(date('Y-m-d'))<=strtotime($rrdiscount->to_date)))
            {
                $newresval['discount_amount'] = $rrdiscount->discount_amount;
                $newresval['discount_type'] = $rrdiscount->discount_type;
            }
        }
        return $newresval;        
    }*/



	public function getalldiscountforproduct($pid,$attriparam=NULL)
    {

        $strSearch="";
        if($attriparam!="")
        {
            $strSearch="  and disdt.attr_combination='".$attriparam."'";
        }

        $sqldiscount = "select dis.id as discountid,dis.discount_name,dis.discount_type,dis.discount_amount,dis.fore_ever,dis.from_date,dis.to_date, disdt.* from ". tablename('discount') ." dis,".tablename('discount_dtls')." disdt where dis.id=disdt.discount_id and disdt.pid='".$pid."' and dis.is_active='Y' and  dis.archive_status='0' ".$strSearch;
        $querydiscount = $this->db->query($sqldiscount);
        $newresval = $querydiscount->result();

        $ary_all_discount=array();
        $count=0;
        foreach ($newresval as $muldiskey => $muldisvalue) 
        {
            if (!empty($muldisvalue)) {
            
                if($muldisvalue->fore_ever==1)
                {
                    $ary_all_discount[$count]['discount_name'] = $muldisvalue->discount_name;
                    $ary_all_discount[$count]['discount_amount'] = $muldisvalue->discount_amount;
                    $ary_all_discount[$count]['discount_type'] = $muldisvalue->discount_type;
                }
                elseif((strtotime($muldisvalue->from_date)<=strtotime(date('Y-m-d'))) && (strtotime(date('Y-m-d'))<=strtotime($muldisvalue->to_date)))
                {
                    $ary_all_discount[$count]['discount_name'] = $muldisvalue->discount_name;
                    $ary_all_discount[$count]['discount_amount'] = $muldisvalue->discount_amount;
                    $ary_all_discount[$count]['discount_type'] = $muldisvalue->discount_type;
                }

                 $count++;
            }
        }
      
        return $ary_all_discount;        
    }

    public function checkproductdiscountplugin() {
        
        $sqlplugin = "select id from " . tablename('modules') . " where alias='productdiscount' and status='1'";
        $queryplugin = $this->db->query($sqlplugin);
        $resrplugin = $queryplugin->result();

        return $resrplugin;
        
    }
}