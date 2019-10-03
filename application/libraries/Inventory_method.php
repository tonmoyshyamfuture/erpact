<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inventory_method {

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->model('report/admin/inventorymodel');
    }

    // Average method for Stock Summary
    public function getClosingStockSummaryUsingAverageCost($category_id, $from_date, $to_date) {
        
        $category_id_list = implode("','", $category_id);
        $prodstocklist = $this->CI->inventorymodel->getProductListByCatList($category_id_list);
        $stockbalance = 0;
        $closing_cost = 0;
        foreach ($prodstocklist as $product) {
            $quantity = $this->CI->inventorymodel->getTotalQuantity($product['id'], $from_date, $to_date);

            $stockin = $this->CI->inventorymodel->getTotalStockIn($product['id'], $from_date, $to_date);
            $stockout = $this->CI->inventorymodel->getTotalStockOut($product['id'], $from_date, $to_date);
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

    // Average method for Category Details
    public function getClosingStockDetailsUsingAverageCost($quantity, $stockin_price, $stockin_qty, $stockbalance) {
        $avg_price = ($quantity->opening_price + $stockin_price) / ($quantity->quantity + $stockin_qty);
        $closing_price = $avg_price * $stockbalance;
        return $closing_price;
    }
    
    // Average method for Product Summary - Monthly
    public function getClosingMonthlyReportUsingAverageCost($quantity, $from_begin_stock_in_price, $frombegin_stock_in_qty, $closing_unit) {
        $total_availble_unit = $quantity->quantity + $frombegin_stock_in_qty;
        $total_cost_inventory = $quantity->opening_price + $from_begin_stock_in_price;
        $average_unit_price = $total_cost_inventory/$total_availble_unit;
        $closing_price = $average_unit_price * $closing_unit;
        return $closing_price;
    }
    
    // Average method for Product Summary - Monthly Details
    public function getClosingMonthlyDetailsReportUsingAverageCost($quantity, $from_begin_stock_in_price, $frombegin_stock_in_qty, $closing_unit) {
        $total_availble_unit = $quantity->quantity + $frombegin_stock_in_qty;
        $total_cost_inventory = $quantity->opening_price + $from_begin_stock_in_price;
        $average_unit_price = $total_cost_inventory/$total_availble_unit;
        $closing_price = $average_unit_price * $closing_unit;
        return $closing_price;
    }
    
    //last sales cost method for stock summary
    public function getClosingStockSummaryUsingLastSalesPrice($category_id, $from_date, $to_date, $closingUnit) {
        $closingPrice = $this->CI->inventorymodel->getLastSalesPriceByCategoryId($category_id, $from_date, $to_date);
        return $closingPrice;
    }
    
    
    //last sales cost method for category details
    public function getClosingStockDetailsUsingLastSalesPrice($pid, $from_date, $to_date, $closingUnit) {
        $lastSalePrice = $this->CI->inventorymodel->getLastSalesPriceByProductId($pid, $from_date, $to_date);
        $closingPrice = $lastSalePrice * $closingUnit;
        return $closingPrice;
    }
    
    //last sales cost method for Monthly Report
    public function getClosingMonthlyReportUsingLastSalesPrice($pid, $from_date, $to_date, $closingUnit) {
        $lastSalePrice = $this->CI->inventorymodel->getLastSalesPriceByProductId($pid, $from_date, $to_date);
        $closingPrice = $lastSalePrice * $closingUnit;
        return $closingPrice;
    }
    
    //last sales cost method for Monthly Report details
    public function getClosingMonthlyDetailsReportUsingLastSalesPrice($pid, $from_date, $to_date, $closingUnit) {
        $lastSalePrice = $this->CI->inventorymodel->getLastSalesPriceByProductId($pid, $from_date, $to_date);
        $closingPrice = $lastSalePrice * $closingUnit;
        return $closingPrice;
    }
    
    //last purchase cost method for stock summary
    public function getClosingStockSummaryUsingLastPurchasePrice($category_id, $from_date, $to_date, $closingUnit) {
        $closingPrice = $this->CI->inventorymodel->getLastPurchasePriceByCategoryId($category_id, $from_date, $to_date);
        return $closingPrice;
    }
    
    //last purchase cost method for category details
    public function getClosingStockDetailsUsingLastPurchasePrice($pid, $from_date, $to_date, $closingUnit) {
        $lastPurchasePrice = $this->CI->inventorymodel->getLastPurchasePriceByProductId($pid, $from_date, $to_date);
        $closingPrice = $lastPurchasePrice * $closingUnit;
        return $closingPrice;
    }
    
    //last purchase cost method for Monthly Report
    public function getClosingMonthlyReportUsingLastPurchasePrice($pid, $from_date, $to_date, $closingUnit) {
        $lastPurchasePrice = $this->CI->inventorymodel->getLastPurchasePriceByProductId($pid, $from_date, $to_date);
        $closingPrice = $lastPurchasePrice * $closingUnit;
        return $closingPrice;
    }
    
    //last purchase cost method for Monthly Report details
    public function getClosingMonthlyDetailsReportUsingLastPurchasePrice($pid, $from_date, $to_date, $closingUnit) {
        $lastPurchasePrice = $this->CI->inventorymodel->getLastPurchasePriceByProductId($pid, $from_date, $to_date);
        $closingPrice = $lastPurchasePrice * $closingUnit;
        return $closingPrice;
    }
    
    

}

?>