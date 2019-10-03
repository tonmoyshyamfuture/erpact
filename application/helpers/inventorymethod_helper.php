<?php
    function getClosingStockUsingAverageCost($from_date, $to_date) {
        $CI = & get_instance();
        $CI->load->model('../modules/reports/models/admin/inventorymodel');
        $category = $CI->inventorymodel->getCategoryDetailsByDate(0, $from_date, $to_date);//new
        $category_list = [];
        foreach ($category as $val){
            $category_list[] = $val->id;
        }
        $category_id_list = implode("','", $category_list);
        $prodstocklist = $CI->inventorymodel->getProductListByCatList($category_id_list);
        $stockbalance = 0;
        $closing_cost = 0;
        foreach ($prodstocklist as $product) {
            $quantity = $CI->inventorymodel->getTotalQuantity($product['id'], $from_date, $to_date);

            $stockin = $CI->inventorymodel->getTotalStockIn($product['id'], $from_date, $to_date);
            $stockout = $CI->inventorymodel->getTotalStockOut($product['id'], $from_date, $to_date);
            $stockin_qty = isset($stockin->stockin) ? $stockin->stockin : 0;
            $stockin_price = isset($stockin->added_price) ? $stockin->added_price : 0;
            $stockout_qty = isset($stockout->stockout) ? $stockout->stockout : '-';
            $avg_price = ($quantity->opening_price + $stockin_price) / ($quantity->quantity + $stockin_qty);
            $stockbalance = ($quantity->quantity + $stockin_qty) - $stockout_qty;
            $closing_price = $avg_price * $stockbalance;
            $closing_cost += $closing_price;
        }
        return $closing_cost;
    }
    
    function getOpeningBalance(){
        $CI = & get_instance();
        $CI->load->model('../modules/accounts/models/admin/account');
        $ledger= $CI->account->getOpeningBalance();
        return $ledger->price;
    }
    
    
    // Average method for Stock Summary
    function getClosingStockSummaryUsingAverageCost($category_id, $from_date, $to_date) {
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        
        $category_id_list = implode("','", $category_id);
        $prodstocklist = $CI->inventorymodel->getProductListByCatList($category_id_list);
        $closing_price = 0;
        $total_opening_qty = 0;
        $total_opening_value = 0;
        $total_in_qty = 0;
        $total_in_value = 0;
        $total_out_qyt = 0;
        foreach ($prodstocklist as $product) {
            $prodstocklist = $CI->inventorymodel->getProductListByProductId($product['id']);
            $stockbalance = 0;
            $totalQuenty = 0;
            foreach ($prodstocklist as $product) {
                $stockin = $CI->inventorymodel->getTotalStockInNew($product->id, $from_date, $to_date);
                $stockout = $CI->inventorymodel->getTotalStockOutNew($product->id, $from_date, $to_date);
                $stockin_qty = isset($stockin->stockin) ? $stockin->stockin : 0;
                $stockin_price = isset($stockin->added_price) ? $stockin->added_price : 0;
                $stockout_qty = isset($stockout->stockout) ? $stockout->stockout : '-';
                $totalQuenty = $product->quantity;
                  if(($totalQuenty + $stockin_qty)==0){
                    continue;
                  }
                
                $total_opening_qty += $totalQuenty;
                $total_opening_value += ($product->purchase_price * $totalQuenty);
                $total_in_qty += $stockin_qty;
                $total_in_value += $stockin_price;
                $total_out_qyt += $stockout_qty;
            }
            
        }
        if(($total_opening_qty + $total_in_qty)==0){
            $avg_price = 0;
        }else{
            $avg_price = ($total_opening_value + $total_in_value) / ($total_opening_qty + $total_in_qty);
        }
        
        $stockbalance = ($total_opening_qty + $total_in_qty) - $total_out_qyt;
        $closing_price = $avg_price * $stockbalance;
        return $closing_price;
    }
//    function getClosingStockSummaryUsingAverageCost($category_id, $from_date, $to_date) {
//        $CI = & get_instance();
//        $CI->load->model('reports/admin/inventorymodel');
//        
//        $category_id_list = implode("','", $category_id);
//        $prodstocklist = $CI->inventorymodel->getProductListByCatList($category_id_list);
//        $closing_cost = 0;
//        foreach ($prodstocklist as $product) {
//            $closing_price = getClosingStockDetailsUsingAverageCost($product['id'], $from_date, $to_date);
//            $closing_cost += $closing_price;
//        }
//        return $closing_cost;
//    }
    
//    function getClosingStockSummaryUsingAverageCost($category_id, $from_date, $to_date) {
//        
//        $CI = & get_instance();
//        $CI->load->model('reports/admin/inventorymodel');
//        
//        $category_id_list = implode("','", $category_id);
//        $prodstocklist = $CI->inventorymodel->getProductListByCatList($category_id_list);
//        
//        $stockbalance = 0;
//        $closing_cost = 0;
//        foreach ($prodstocklist as $product) {
//            $quantity = $CI->inventorymodel->getTotalQuantity($product['id'], $from_date, $to_date);
//            $stockin = $CI->inventorymodel->getTotalStockIn($product['id'], $from_date, $to_date);
//            $stockout = $CI->inventorymodel->getTotalStockOut($product['id'], $from_date, $to_date);
//            $stockin_qty = isset($stockin->stockin) ? $stockin->stockin : 0;
//            $stockin_price = isset($stockin->added_price) ? $stockin->added_price : 0;
//            $stockout_qty = isset($stockout->stockout) ? $stockout->stockout : '-';
//            if(($quantity->quantity + $stockin_qty)==0){
//                continue;
//            }
//            $avg_price = ($quantity->opening_price + $stockin_price) / ($quantity->quantity + $stockin_qty);
//            $stockbalance = ($quantity->quantity + $stockin_qty) - $stockout_qty;
//            $closing_price = $avg_price * $stockbalance;
//            $closing_cost += $closing_price;
//        }
//        return $closing_cost;
//    }

    // Average method for Category Details
    function getClosingStockDetailsUsingAverageCost($product_id, $from_date, $to_date) {
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        $prodstocklist = $CI->inventorymodel->getProductListByProductId($product_id);
        $stockbalance = 0;
//        $closing_cost = 0;
        $totalQuenty = 0;
        
        $total_opening_qty = 0;
        $total_opening_value = 0;
        $total_in_qty = 0;
        $total_in_value = 0;
        $total_out_qyt = 0;
        foreach ($prodstocklist as $product) {
            $stockin = $CI->inventorymodel->getTotalStockInNew($product->id, $from_date, $to_date);
            $stockout = $CI->inventorymodel->getTotalStockOutNew($product->id, $from_date, $to_date);
            $stockin_qty = isset($stockin->stockin) ? $stockin->stockin : 0;
            $stockin_price = isset($stockin->added_price) ? $stockin->added_price : 0;
            $stockout_qty = isset($stockout->stockout) ? $stockout->stockout : '-';
            $totalQuenty = $product->quantity;
            if(($totalQuenty + $stockin_qty)==0){
                continue;
            }
            $total_opening_qty += $totalQuenty;
            $total_opening_value += ($product->purchase_price * $totalQuenty);
            $total_in_qty += $stockin_qty;
            $total_in_value += $stockin_price;
            $total_out_qyt += $stockout_qty;
        }
        
        if(($total_opening_qty + $total_in_qty)==0){
            $avg_price = 0;
        }else{
            $avg_price = ($total_opening_value + $total_in_value) / ($total_opening_qty + $total_in_qty);
        }
        
        $stockbalance = ($total_opening_qty + $total_in_qty) - $total_out_qyt;
        $closing_price = $avg_price * $stockbalance;
        
        return $closing_price;
        
    }
    
    
//    function getClosingStockDetailsUsingAverageCost($quantity, $stockin_price, $stockin_qty, $stockbalance) {
//        $closing_price = 0;
//        if($quantity->quantity + $stockin_qty > 0){
//            $avg_price = ($quantity->opening_price + $stockin_price) / ($quantity->quantity + $stockin_qty);
//            $closing_price = $avg_price * $stockbalance;
//        }
//        return $closing_price;
//    }
    
    // Average method for Product Summary - Monthly
    function getClosingMonthlyReportUsingAverageCost($quantity, $from_begin_stock_in_price, $frombegin_stock_in_qty, $closing_unit) {
        $closing_price = 0;
        $total_availble_unit = $quantity->quantity + $frombegin_stock_in_qty;
        if($total_availble_unit > 0){
            $total_cost_inventory = $quantity->opening_price + $from_begin_stock_in_price;
            $average_unit_price = $total_cost_inventory/$total_availble_unit;
            $closing_price = $average_unit_price * $closing_unit;
        }
        return $closing_price;
    }
    
    // Average method for Product Summary - Monthly Details
    function getClosingMonthlyDetailsReportUsingAverageCost($quantity, $from_begin_stock_in_price, $frombegin_stock_in_qty, $closing_unit) {
        $closing_price = 0;
        $total_availble_unit = $quantity->quantity + $frombegin_stock_in_qty;
        if($total_availble_unit > 0){
            $total_cost_inventory = $quantity->opening_price + $from_begin_stock_in_price;
            $average_unit_price = $total_cost_inventory/$total_availble_unit;
            $closing_price = $average_unit_price * $closing_unit;
        }
        return $closing_price;
    }
    
    //last sales cost method for stock summary
    function getClosingStockSummaryUsingLastSalesPrice($category_id, $from_date, $to_date, $closingUnit) {
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        $closingPrice = $CI->inventorymodel->getLastSalesPriceByCategoryId($category_id, $from_date, $to_date);
        return $closingPrice;
    }
    
    
    //last sales cost method for category details
    function getClosingStockDetailsUsingLastSalesPrice($pid, $from_date, $to_date, $closingUnit) {
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        $lastSalePrice = $CI->inventorymodel->getLastSalesPriceByProductId($pid, $from_date, $to_date);
        $closingPrice = $lastSalePrice * $closingUnit;
        return $closingPrice;
    }
    
    //last sales cost method for Monthly Report
    function getClosingMonthlyReportUsingLastSalesPrice($pid, $from_date, $to_date, $closingUnit) {
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        $lastSalePrice = $CI->inventorymodel->getLastSalesPriceByProductId($pid, $from_date, $to_date);
        $closingPrice = $lastSalePrice * $closingUnit;
        return $closingPrice;
    }
    
    //last sales cost method for Monthly Report details
    function getClosingMonthlyDetailsReportUsingLastSalesPrice($pid, $from_date, $to_date, $closingUnit) {
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        $lastSalePrice = $CI->inventorymodel->getLastSalesPriceByProductId($pid, $from_date, $to_date);
        $closingPrice = $lastSalePrice * $closingUnit;
        return $closingPrice;
    }
    
    //last purchase cost method for stock summary
    function getClosingStockSummaryUsingLastPurchasePrice($category_id, $from_date, $to_date, $closingUnit) {
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        $closingPrice = $CI->inventorymodel->getLastPurchasePriceByCategoryId($category_id, $from_date, $to_date);
        return $closingPrice;
    }
    
    //last purchase cost method for category details
    function getClosingStockDetailsUsingLastPurchasePrice($pid, $from_date, $to_date, $closingUnit) {
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        $lastPurchasePrice = $CI->inventorymodel->getLastPurchasePriceByProductId($pid, $from_date, $to_date);
        $closingPrice = $lastPurchasePrice * $closingUnit;
        return $closingPrice;
    }
    
    //last purchase cost method for Monthly Report
    function getClosingMonthlyReportUsingLastPurchasePrice($pid, $from_date, $to_date, $closingUnit) {
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        $lastPurchasePrice = $CI->inventorymodel->getLastPurchasePriceByProductId($pid, $from_date, $to_date);
        $closingPrice = $lastPurchasePrice * $closingUnit;
        return $closingPrice;
    }
    
    //last purchase cost method for Monthly Report details
    function getClosingMonthlyDetailsReportUsingLastPurchasePrice($pid, $from_date, $to_date, $closingUnit) {
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        $lastPurchasePrice = $CI->inventorymodel->getLastPurchasePriceByProductId($pid, $from_date, $to_date);
        $closingPrice = $lastPurchasePrice * $closingUnit;
        return $closingPrice;
    }
    
    // cost valuation using FIFO for stock category details, monthly report and monthly report details
    function getClosingStockDetailsUsingFIFO($product_id, $from_date, $to_date, $closingUnit) {
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        $quantity = $CI->inventorymodel->getTotalQuantity($product_id, $from_date, $to_date);
        $stockOut = $CI->inventorymodel->getTotalStockOut($product_id, $from_date, $to_date);
        $stockOutQty = ($stockOut) ? $stockOut->stockout : 0;
        
        $totalClosingPrice = 0;
        if($stockOutQty > $quantity->quantity){
            $restQty = $stockOutQty -  $quantity->quantity; // if stock_out is greater than opening_stock
        }else{
            $balanceQty = $quantity->quantity - $stockOutQty; // (opening_quantity - total_stock_out_up_to_date)
            $totalClosingPrice = $balanceQty * $quantity->purchase_price;
            $restQty = 0;
        }
        
        $productInOut = $CI->inventorymodel->getProductInOutDetails($product_id, $from_date, $to_date);
        $totalPurchaseSalePrice = 0;
        foreach ($productInOut as $product) {
            if($restQty > 0) {
                if ($restQty > $product->quantity) {
                    $restQty -= $product->quantity;
                } else {
                    $totalPurchaseSalePrice += ($product->quantity - $restQty) * $product->original_price;
                    $restQty -= $product->quantity;                    
                }
            }else{
                $totalPurchaseSalePrice += $product->quantity * $product->original_price; // if no remaining out product ( for sales -> salesPrice * salesQuantity, for purchase -> purchasePrice * purchaseQuantity, then add them all together)
            }     
            
        }
        
        $totalClosingPrice += $totalPurchaseSalePrice; // ( balanceQuantity * openingPrice) + (RespectiveSalesQuantity * RespectiveSalesQuantity) +  (RespectivePurchaseQuantity * RespectivePurchaseQuantity)
        return $totalClosingPrice;
    }
    
    // cost valuation using LIFO for stock category details, monthly report and monthly report details
    function getClosingStockDetailsUsingLIFO($product_id, $from_date, $to_date, $closingUnit) {
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        $quantity = $CI->inventorymodel->getTotalQuantity($product_id, $from_date, $to_date);
        $stockOut = $CI->inventorymodel->getTotalStockOut($product_id, $from_date, $to_date);
        $stockOutQty = ($stockOut) ? $stockOut->stockout : 0;
        
        $totalClosingPrice = 0;
        $totalPurchaseSalePrice = 0;
        $restQty = $stockOutQty;
        $productInOut = $CI->inventorymodel->getProductInOutDetailsDesc($product_id, $from_date, $to_date);
        foreach ($productInOut as $product) {
            if ($restQty > $product->quantity) {
                $restQty -= $product->quantity;
            } else if ($restQty < $product->quantity && $restQty > 0){
                $totalPurchaseSalePrice += ($product->quantity - $restQty) * $product->original_price;
                $restQty -= $product->quantity;                
            }
        }
        
        if($restQty > 0){
          $balanceQty = $quantity->quantity - $restQty; // (opening_quantity - rest_of_the_stock_out)  
        }else{
            $balanceQty = $quantity->quantity;
        }
        
        $totalClosingPrice += $balanceQty * $quantity->purchase_price;
        $totalClosingPrice += $totalPurchaseSalePrice;
        return $totalClosingPrice;
    }
    
    // cost valuation using FIFO for stock summary
    function getClosingStockSummaryUsingFIFO($category_id, $from_date, $to_date, $closingUnit) {
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        $category_id_list = implode("','", $category_id);
        $productList = $CI->inventorymodel->getProductListByCatList($category_id_list);
        $totalClosingPrice = 0;
        foreach ($productList as $product) {
            $totalClosingPrice += getClosingStockDetailsUsingFIFO($product['id'], $from_date, $to_date, $closingUnit) ;
        }
        return $totalClosingPrice;
    }
    
    // cost valuation using LIFO for stock summary
    function getClosingStockSummaryUsingLIFO($category_id, $from_date, $to_date, $closingUnit) {
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        $category_id_list = implode("','", $category_id);
        $productList = $CI->inventorymodel->getProductListByCatList($category_id_list);
        $totalClosingPrice = 0;
        foreach ($productList as $product) {
            $totalClosingPrice += getClosingStockDetailsUsingLIFO($product['id'], $from_date, $to_date, $closingUnit) ;
        }
        return $totalClosingPrice;
    }
    
    //product list
    function productList($from_date='', $to_date='', $limit = 10, $offset = 0, $search=""){
        $CI = & get_instance();
        $CI->load->model('reports/admin/inventorymodel');
        $prodstocklist = $CI->inventorymodel->getProductListWithCategory($limit, $offset, $search);
        
        $stocklist = array();
        $stockbalance = 0;
        foreach ($prodstocklist as $product) {
                $quantity = $CI->inventorymodel->getTotalQuantity($product['id'], $from_date, $to_date);
                $stockin = $CI->inventorymodel->getTotalStockIn($product['id'], $from_date, $to_date);
                $stockout = $CI->inventorymodel->getTotalStockOut($product['id'], $from_date, $to_date);
                $stockin_qty = isset($stockin->stockin) ? $stockin->stockin : 0;
                $stockout_qty = isset($stockout->stockout) ? $stockout->stockout : '-';
                $stockbalance = ($quantity->quantity + $stockin_qty) - $stockout_qty;
                
                $stocklist[] = (object)array(
                    'id' => $product['id'],
                    'stock_id' => $product['stock_id'],
                    'name' => $product['name'],
                    'unit' => $product['qty_unit'],
                    'catname' => $product['cat_name'],
                    'available_qnty' => $stockbalance,
                    'slug' => '',
                    'discontinue' => $product['discontinue'],
                    'godown_name' => $product['godown_name'],
                    'having_batch' => $product['having_batch'],
                );
        }
        
        return $stocklist;

    }
    
    
    
   
?>