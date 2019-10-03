<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class reports extends MX_Controller {

    public $store = array();

    public function __construct() {
        parent::__construct();
        $this->load->model('accounts/report');
        $this->load->model('accounts/account');
        $this->load->helper('url', 'form');
        $this->load->helper('financialyear');
        $this->load->helper('inventorymethod');
        $this->load->library('session');
        admin_authenticate();
    }

    //add query string params
    function add_querystring_var($url, $key, $value) {
        $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
        $url = substr($url, 0, -1);
        if (strpos($url, '?') === false) {
            return ($url . '?' . $key . '=' . $value);
        } else {
            return ($url . '&' . $key . '=' . $value);
        }
    }

    //remove query string params
    function remove_querystring_var($url, $key) {
        $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
        $url = substr($url, 0, -1);
        return ($url);
    }

    public function index() {
        $from_date = '01/01/2016';
        //$from_date = date('Y-m-d', strtotime($_POST['from_date']));
        if (!empty($_POST['to_date'])) {
            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
        }
        $to_date = '03/31/2016';
//        if (!empty($_POST['to_date'])) {
//            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
//        }
//        if (empty($_POST['to_date'])) {
//            $to_date = date('Y-m-d', strtotime('2050-12-31'));
//        }

        echo $from_date . '<br>';
        echo $to_date;
        $where = array(
            'ladger_account_detail.status' => 1,
            'ladger_account_detail.deleted' => 0,
            'ladger_account_detail.entry_id !=' => 0,
            'ladger_account_detail.create_date >=' => $from_date,
            'ladger_account_detail.create_date <=' => $to_date
        );
        $entries = $this->report->allEntry($where);
        echo '<pre/>';
        print_r($entries);
        exit;
        $ledger_base_entry = array();
        foreach ($entries as $entry) {
            $ledger_base_entry[$entry['group_id']][$entry['ladger_name']][] = $entry;
        }
        echo '<pre/>';
        print_r($ledger_base_entry);
        exit;
    }

    public function profit_loss_vertical() {
        user_permission(120,'list');
        $this->load->helper('core');
        $financial_year = get_financial_year();
        $data = array();
        $data['cur_financial_year'] = date('Y', strtotime(current($financial_year)));
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-t", strtotime(end($financial_year)));
        $finans_start_date = $from_date;
        if (isset($_GET['staring_day']) && $_GET['staring_day'] != '' && isset($_GET['ending_day']) && $_GET['ending_day'] != '') {

//            $from_date = $_GET['staring_day'];
//            $to_date = $_GET['ending_day'];
            $from_date = date('Y-m-d', strtotime($_GET['staring_day'])); // somnath - date converted to sql format for query compare
            $to_date = date('Y-m-d', strtotime($_GET['ending_day'])); // somnath - date converted to sql format for query compare
        }


        
        $data['staring_day'] = $from_date;
        $data['ending_day'] = $to_date;
        $finalArray = array();

        //income
        
        $direct_income_id = 34;
        $indirect_income_id = 35;
        //expenses
        $direct_expenses_id = 29;
        $indirect_expenses_id = 30;
        $exp_id_arr=[$direct_expenses_id,$indirect_expenses_id];
        $exp_sub = $this->report->getSubGroup($exp_id_arr);
        $exp_id_array[0]='opening';
        $exp_id_array[1]=29; //SUDIP 24072018
        $exp_id_array[2]=30; //SUDIP 24072018
        foreach ($exp_sub as $value) {
          $exp_id_array[]=$value->id;  
        }
        $data['exp_id_array']=$exp_id_array;
        $totalClosingPrice = 0;
        $totalOpeningPrice = 0;
        $totalClosingPrice = $this->getClosingStock($from_date,$to_date);
        
        // somnath - Closing stock calculation using (Average Cost, Last Sale Price, Last Purchase Price, FIFO, LIFO with session-wise handling and opening stock calculation
        
//        // valuation method setting to session, default is 1(Average Cost)
//        if( isset($_GET['method']) || $this->session->userdata('inventory_method') ) {
//                $method = (isset($_GET['method'])) ? $_GET['method'] : $this->session->userdata('inventory_method');
//                $this->session->unset_userdata('inventory_method', $method);
//                $this->session->set_userdata('inventory_method', $method);
//        }else{
//            $method = 1;
//        }
//
//        $this->load->model('reports/admin/inventorymodel');
//        $category = $this->inventorymodel->getCategoryDetailsByDate(0, $from_date, $to_date); // to get list of categories
//        $category_list = [];
//        foreach ($category as $val) {
//            $category_list[] = $val->id;
//        }
//        $totalClosingPrice = 0;
//        $totalOpeningPrice = 0;
//        foreach ($category_list as $cat) {
//            $cat_id[0] = $cat;
//            $opening = $this->inventorymodel->getOpeningProductByCatId($cat_id, $from_date, $to_date);
//            $in_product = $this->inventorymodel->getInProductByCatId($cat_id, $from_date, $to_date);
//            $out_product = $this->inventorymodel->showoutstock($cat_id, $from_date, $to_date);
//
//            $opening_stock = (!empty($opening)) ? $opening[0]->opening : 0;
//            $opening_price = (!empty($opening)) ? $opening[0]->price : 0;
////            $totalOpeningPrice += $opening_price; // for getting total opening price individual opening price added to a variable
//            $in_stock = (!empty($in_product)) ? $in_product[0]->in_product : 0;
//            $in_price = (!empty($in_product)) ? $in_product[0]->price : 0;
//            $out_stock = (!empty($out_product)) ? $out_product[0]->totalquantity : 0;
//            $out_price = (!empty($out_product)) ? $out_product[0]->price : 0;
//            $closing_stock = (($opening_stock + $in_stock) - $out_stock);
//            //$closing_cost = getClosingStockSummaryUsingLastSalesPrice($cat_id, $from_date, $to_date, $closing_stock);
//            switch ($method) {
//                case 1:
//                    $closing_cost = getClosingStockSummaryUsingAverageCost($cat_id, $from_date, $to_date);
//                    break;
//                case 2:
//                    $closing_cost = getClosingStockSummaryUsingLastSalesPrice($cat_id, $from_date, $to_date, $closing_stock);
//                    break;
//                case 3:
//                    $closing_cost = getClosingStockSummaryUsingLastPurchasePrice($cat_id, $from_date, $to_date, $closing_stock);
//                    break;
//                case 4:
//                    $closing_cost = 0;
//                    break;
//                case 5:
//                    $closing_cost = getClosingStockSummaryUsingFIFO($cat_id, $from_date, $to_date, $closing_stock);
//                    break;
//                case 6:
//                    $closing_cost = getClosingStockSummaryUsingLIFO($cat_id, $from_date, $to_date, $closing_stock);
//                    break;
//                default :
//                    $closing_cost = getClosingStockSummaryUsingAverageCost($cat_id, $from_date, $to_date);
//                    break;
//            }
//            
//
//            $totalClosingPrice += $closing_cost; // added invidual category closing price to get total closing price
//        }
        
        
        // somnath - ends
        
        //Closing stock
        $closingStockArray[0]['site'] = 'stock';
        $closingStockArray[0]['type'] = 'stock';
        $closingStockArray[0]['level'] = 'level_first';
        $closingStockArray[0]['id'] = 'closing';
        $closingStockArray[0]['name'] = 'Closing Stock';
        $closingStockArray[0]['code'] = 00;
        $closingStockArray[0]['parent_id'] = 0;
        $closingStockArray[0]['account_type'] = 'Cr';
        $closingStockArray[0]['opening_balance'] = 0.00;
//        $closingStockArray[0]['amount'] = getClosingStockUsingAverageCost($from_date,$to_date);
        $closingStockArray[0]['amount'] = $totalClosingPrice;
        
        //Opening stock
        $openingStockArray[0]['site'] = 'stock';
        $openingStockArray[0]['type'] = 'stock';
        $openingStockArray[0]['level'] = 'level_first';
        $openingStockArray[0]['id'] = 'opening';
        $openingStockArray[0]['name'] = 'Opening Stock';
        $openingStockArray[0]['code'] = 00;
        $openingStockArray[0]['parent_id'] = 0;
        $openingStockArray[0]['account_type'] = 'Dr';
        $openingStockArray[0]['opening_balance'] = 0.00;
        $openingStockArray[0]['amount'] = getOpeningBalance();
//        $openingStockArray[0]['amount'] = $totalOpeningPrice; //somnath
        
        
        
        $direct_income = $this->getCalculatedValue(get_balance_as_group($direct_income_id,$from_date,$to_date,$finans_start_date),'income');
        $indirect_income = $this->getCalculatedValue(get_balance_as_group($indirect_income_id,$from_date,$to_date,$finans_start_date),'income');

        $direct_expenses = $this->getCalculatedValue(get_balance_as_group($direct_expenses_id,$from_date,$to_date,$finans_start_date),'expenses');
        $indirect_expenses = $this->getCalculatedValue(get_balance_as_group($indirect_expenses_id,$from_date,$to_date,$finans_start_date),'expenses');

        //$closingStock = getClosingStockUsingAverageCost($from_date,$to_date);
        $closingStock = $totalClosingPrice;
        
        $direct_income = array_merge($closingStockArray,$direct_income);
        $direct_expenses = array_merge($openingStockArray,$direct_expenses);

        $directArray=array_merge($direct_income,$direct_expenses);
        $indirectArray=array_merge($indirect_income,$indirect_expenses);
        $total_direct_income = $this->getTotalValueForFirstLevel($direct_income);
        $total_direct_expenses = $this->getTotalValueForFirstLevel($direct_expenses);

        $grossvalue = $total_direct_income + $total_direct_expenses;//plus operator use for plus minus concept
        $gross = '';
        if($grossvalue > 0 ){
            $gross = 'Gross Profit';
        }else{
            $gross = 'Gross Loss';
        }
        //gross profit array
        $grossvalueArray[0]['site'] = 'gross';
        $grossvalueArray[0]['type'] = 'other';
        $grossvalueArray[0]['level'] = 'level_first';
        $grossvalueArray[0]['id'] = 0;
        $grossvalueArray[0]['name'] = $gross;
        $grossvalueArray[0]['code'] = 00;
        $grossvalueArray[0]['parent_id'] = 0;
        $grossvalueArray[0]['account_type'] = 'other';
        $grossvalueArray[0]['opening_balance'] = 0.00;
        $grossvalueArray[0]['amount'] = str_replace( '-','',$grossvalue);
        
        
        $total_indirect_income = $this->getTotalValueForFirstLevel($indirect_income);
        $total_indirect_expenses = $this->getTotalValueForFirstLevel($indirect_expenses);
        

        $indirectvalue = $total_indirect_income + $total_indirect_expenses;//plus operator use for plus minus concept
        $netValue = $grossvalue + $indirectvalue;
        $net = '';
        if($netValue > 0 ){
            $net = 'Net Profit';
        }else{
            $net = 'Net Loss';
        }
        //net profit array
        $netvalueArray[0]['site'] = 'net';
        $netvalueArray[0]['type'] = 'other';
        $netvalueArray[0]['level'] = 'level_first';
        $netvalueArray[0]['id'] = 0;
        $netvalueArray[0]['name'] = $net;
        $netvalueArray[0]['code'] = 00;
        $netvalueArray[0]['parent_id'] = 0;
        $netvalueArray[0]['account_type'] = 'other';
        $netvalueArray[0]['opening_balance'] = 0.00;
        $netvalueArray[0]['amount'] = str_replace( '-','',$netValue);
        
        $directWithGrossArray=array_merge($directArray,$grossvalueArray);
        $directWithGrossWithIndirectArray=array_merge($directWithGrossArray,$indirectArray);
        $profitLoss=array_merge($directWithGrossWithIndirectArray,$netvalueArray);
        
        $data['trial_balance_arr']=$profitLoss;
        $data['number_of_branch'] = $this->report->getNumberOfBranch();
        $getsitename = getsitename();
        $this->load->model('admin/report');
        $data['get_standard_format_data'] = $this->report->getStandardFormatData();
        $this->layouts->set_title($getsitename . ' | Profit Loss');
        $this->layouts->render('admin/profit-loss-vertical', $data, 'admin');
    }
    
    public function balance_sheet_vertical() {
         user_permission(119,'list');
        $this->load->helper('core');
        $financial_year = get_financial_year();
        $data = array();        
        $data['cur_financial_year'] = date('Y', strtotime(current($financial_year)));
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-t", strtotime(end($financial_year)));
        $finans_start_date = $from_date;
        if (isset($_GET['staring_day']) && $_GET['staring_day'] != '' && isset($_GET['ending_day']) && $_GET['ending_day'] != '') {

//            $from_date = $_GET['staring_day'];
//            $to_date = $_GET['ending_day'];
            $from_date = date('Y-m-d', strtotime($_GET['staring_day'])); // somnath - date converted to sql format for query compare
            $to_date = date('Y-m-d', strtotime($_GET['ending_day'])); // somnath - date converted to sql format for query compare
        }

        
        
        $data['staring_day'] = $from_date;
        $data['ending_day'] = $to_date;
        $finalArray = array();
        
        $assets_id = 1;
        $liabilities_id = 4;
        
        $income_id = 3;
        $expenses_id = 2;
        
        $profit_loss_id = 5;
        
        
        $assets_details = get_balance_as_group($assets_id,$from_date,$to_date,$finans_start_date);
        
        $totalClosingPrice = 0;
        $totalOpeningPrice = 0;
        $totalClosingPrice = $this->getClosingStock($from_date,$to_date);
        
        // somnath - Closing stock calculation using (Average Cost, Last Sale Price, Last Purchase Price, FIFO, LIFO with session-wise handling and opening stock calculation
        
//        // valuation method setting to session, default is 1(Average Cost)
//        if (isset($_GET['method']) || $this->session->userdata('inventory_method')) {
//            $method = (isset($_GET['method'])) ? $_GET['method'] : $this->session->userdata('inventory_method');
//            $this->session->unset_userdata('inventory_method', $method);
//            $this->session->set_userdata('inventory_method', $method);
//        } else {
//            $method = 1;
//        }
//
//
//        $this->load->model('reports/admin/inventorymodel');
//        $category = $this->inventorymodel->getCategoryDetailsByDate(0, $from_date, $to_date); // to get list of categories
//        $category_list = [];
//        foreach ($category as $val) {
//            $category_list[] = $val->id;
//        }
//
//        $totalClosingPrice = 0;
//        $totalOpeningPrice = 0;
//        foreach ($category_list as $cat) {
//            $cat_id[0] = $cat;
//            $opening = $this->inventorymodel->getOpeningProductByCatId($cat_id, $from_date, $to_date);
//            $in_product = $this->inventorymodel->getInProductByCatId($cat_id, $from_date, $to_date);
//            $out_product = $this->inventorymodel->showoutstock($cat_id, $from_date, $to_date);
//
//            $opening_stock = (!empty($opening)) ? $opening[0]->opening : 0;
//            $opening_price = (!empty($opening)) ? $opening[0]->price : 0;
//            $totalOpeningPrice += $opening_price; // for getting total opening price individual opening price added to a variable
//            $in_stock = (!empty($in_product)) ? $in_product[0]->in_product : 0;
//            $in_price = (!empty($in_product)) ? $in_product[0]->price : 0;
//            $out_stock = (!empty($out_product)) ? $out_product[0]->totalquantity : 0;
//            $out_price = (!empty($out_product)) ? $out_product[0]->price : 0;
//            $closing_stock = (($opening_stock + $in_stock) - $out_stock);
//            //$closing_cost = getClosingStockSummaryUsingLastSalesPrice($cat_id, $from_date, $to_date, $closing_stock);
//            switch ($method) {
//                case 1:
//                    $closing_cost = getClosingStockSummaryUsingAverageCost($cat_id, $from_date, $to_date);
//                    break;
//                case 2:
//                    $closing_cost = getClosingStockSummaryUsingLastSalesPrice($cat_id, $from_date, $to_date, $closing_stock);
//                    break;
//                case 3:
//                    $closing_cost = getClosingStockSummaryUsingLastPurchasePrice($cat_id, $from_date, $to_date, $closing_stock);
//                    break;
//                case 4:
//                    $closing_cost = 0;
//                    break;
//                case 5:
//                    $closing_cost = getClosingStockSummaryUsingFIFO($cat_id, $from_date, $to_date, $closing_stock);
//                    break;
//                case 6:
//                    $closing_cost = getClosingStockSummaryUsingLIFO($cat_id, $from_date, $to_date, $closing_stock);
//                    break;
//                default :
//                    $closing_cost = getClosingStockSummaryUsingAverageCost($cat_id, $from_date, $to_date);
//                    break;
//            }
//
//            $totalClosingPrice += $closing_cost; // added invidual category closing price to get total closing price
//        }


        // somnath - ends
        
        
        
        //Closing stock
        //using for addtion closing stock with current assets in level one group
        foreach ($assets_details as $key=>$val){
            $closingStockArray[0]['type'] = 'stock';
            $closingStockArray[0]['level'] = 'level_second';
            $closingStockArray[0]['id'] = 'closing';
            $closingStockArray[0]['name'] = 'Closing Stock';
            $closingStockArray[0]['code'] = 00;
            $closingStockArray[0]['parent_id'] = 9;
            $closingStockArray[0]['account_type'] = 'Dr';
            $closingStockArray[0]['opening_balance'] = 0.00;
            $closingStockArray[0]['cr_balance'] = 0.00;
            //$closingStockArray[0]['dr_balance'] = getClosingStockUsingAverageCost($from_date,$to_date);
            $closingStockArray[0]['dr_balance'] = $totalClosingPrice;
            if($val['level'] == 'level_first' && $val['id'] == 9){
                $assets_details[$key]['dr_balance'] = $val['dr_balance'] + $totalClosingPrice;
                array_splice($assets_details, ($key+1), 0, $closingStockArray);
                break;
            }
        }
       
        
        $assets = $this->getCalculatedValue($assets_details,'assets');
        $liabilities = $this->getCalculatedValue(get_balance_as_group($liabilities_id,$from_date,$to_date,$finans_start_date),'liabilities');
        $income = $this->getCalculatedValue(get_balance_as_group($income_id,$from_date,$to_date,$finans_start_date),'income');
        $expenses = $this->getCalculatedValue(get_balance_as_group($expenses_id,$from_date,$to_date,$finans_start_date),'expenses');
        
        $opening_profit_loss = $profitLoss = $this->report->getProfitLoss($profit_loss_id);
        
        $total_assets = $this->getTotalValueForFirstLevel($assets);
        $total_liabilities = $this->getTotalValueForFirstLevel($liabilities);
        
        $total_income = $this->getTotalValueForFirstLevel($income);
        $total_expenses = $this->getTotalValueForFirstLevel($expenses);
        
        //Closing stock
        //$closingStock = getClosingStockUsingAverageCost($from_date,$to_date);
        $closingStock = $totalClosingPrice;
        $total_income = $total_income + $closingStock;
        
        //Opening Stock
        $openingStock = getOpeningBalance();
        $total_expenses = $total_expenses-$openingStock;//(-$total_expenses - (+)$openingStock)
//        $opening_bal_profit_loss = $this->getTotalValueForFirstLevel($opening_profit_loss);
        
        $opening_balance = 0;
        $profit_loss_ledger = '';
        if(isset($opening_profit_loss) && !empty($opening_profit_loss)){
            $opening_balance = $opening_profit_loss['opening_balance'];
            $profit_loss_ledger = $opening_profit_loss['ladger_name'];
        }
        
        
        $diff_in_bal = 0;
        $profit_loss = 0;
        $liabilities_site = 0;
        $current_profit_loss = $total_income + $total_expenses;//($total_income + (-)$total_expenses)
        $profit_loss = $current_profit_loss;
        if($opening_profit_loss['account_type'] = 'Cr'){
            $profit_loss = $profit_loss + $opening_balance; 
        }else{
            $profit_loss = $profit_loss - $opening_balance;
        }

        $liabilities_site = $total_liabilities + $profit_loss;
        $diff_in_bal = round($liabilities_site,2) + round($total_assets,2);
        //source
        $source[0]['site'] = 'profit and Loss';
        $source[0]['type'] = 'level';
        $source[0]['level'] = 'level_first';
        $source[0]['id'] = 0;
        $source[0]['name'] = 'Sources Of Funds:';
        $source[0]['code'] = 00;
        $source[0]['parent_id'] = 0;
        $source[0]['account_type'] = 'other';
        $source[0]['opening_balance'] = 0;
        $source[0]['amount'] = '';
        
        $total[0]['site'] = 'profit and Loss';
        $total[0]['type'] = 'other';
        $total[0]['level'] = 'level_first';
        $total[0]['id'] = 0;
        $total[0]['name'] = 'Total';
        $total[0]['code'] = 00;
        $total[0]['parent_id'] = 0;
        $total[0]['account_type'] = 'other';
        $total[0]['opening_balance'] = 0;
        $total[0]['amount'] = str_replace( '-','',$total_assets);
        
        $liabilities=array_merge($source,$liabilities);
        
        //Application
        $application[0]['site'] = 'profit and Loss';
        $application[0]['type'] = 'level';
        $application[0]['level'] = 'level_first';
        $application[0]['id'] = 0;
        $application[0]['name'] = 'Application of Funds:';
        $application[0]['code'] = 00;
        $application[0]['parent_id'] = 0;
        $application[0]['account_type'] = 'other';
        $application[0]['opening_balance'] = 0;
        $application[0]['amount'] = '';
        
        $assets=array_merge($application,$assets);
        
        //Profit and Loss
        $grossvalueArray[0]['site'] = 'profit and Loss';
        $grossvalueArray[0]['type'] = 'other';
        $grossvalueArray[0]['level'] = 'level_first';
        $grossvalueArray[0]['id'] = 0;
        $grossvalueArray[0]['name'] = $profit_loss_ledger;
        $grossvalueArray[0]['code'] = 00;
        $grossvalueArray[0]['parent_id'] = 0;
        $grossvalueArray[0]['account_type'] = 'other';
        $grossvalueArray[0]['opening_balance'] = 0.00;
        $grossvalueArray[0]['amount'] = str_replace( '-','',$profit_loss);
        
        //opening
        $grossvalueArray[1]['site'] = 'profit and Loss';
        $grossvalueArray[1]['type'] = 'other';
        $grossvalueArray[1]['level'] = 'level_second';
        $grossvalueArray[1]['id'] = 0;
        $grossvalueArray[1]['name'] = 'Opening Balance';
        $grossvalueArray[1]['code'] = 00;
        $grossvalueArray[1]['parent_id'] = 0;
        $grossvalueArray[1]['account_type'] = 'other';
        $grossvalueArray[1]['opening_balance'] = 0.00;
        $grossvalueArray[1]['amount'] = str_replace( '-','',$opening_balance);
        //Current
        $grossvalueArray[2]['site'] = 'profit and Loss';
        $grossvalueArray[2]['type'] = 'other';
        $grossvalueArray[2]['level'] = 'level_second';
        $grossvalueArray[2]['id'] = 0;
        $grossvalueArray[2]['name'] = 'Current Period';
        $grossvalueArray[2]['code'] = 00;
        $grossvalueArray[2]['parent_id'] = 0;
        $grossvalueArray[2]['account_type'] = 'other';
        $grossvalueArray[2]['opening_balance'] = 0.00;
        $grossvalueArray[2]['amount'] = str_replace( '-','',$current_profit_loss);
        
        
        
        $liabilities_site_array=array_merge($liabilities,$grossvalueArray);
        $liabilities_site_array=array_merge($liabilities_site_array,$total);
        
        if($diff_in_bal != 0){
            $diff_in_bal_array[0]['site'] = 'profit and Loss';
            $diff_in_bal_array[0]['type'] = 'other';
            $diff_in_bal_array[0]['level'] = 'level_first';
            $diff_in_bal_array[0]['id'] = 0;
            $diff_in_bal_array[0]['name'] = 'Diff. in Opening Balances';
            $diff_in_bal_array[0]['code'] = 00;
            $diff_in_bal_array[0]['parent_id'] = 0;
            $diff_in_bal_array[0]['account_type'] = 'other';
            $diff_in_bal_array[0]['opening_balance'] = 0.00;
            $diff_in_bal_array[0]['amount'] = str_replace( '-','',$diff_in_bal);
            $liabilities_site_array=array_merge($liabilities_site_array,$diff_in_bal_array);
        }
        
        $data['number_of_branch'] = $this->report->getNumberOfBranch();
        
        $balance_sheet_array = array_merge($liabilities_site_array,$assets);
        $balance_sheet_array = array_merge($balance_sheet_array,$total);
        $data['trial_balance_arr']=$balance_sheet_array;
        $getsitename = getsitename();
        $this->load->model('admin/report');
        $data['get_standard_format_data'] = $this->report->getStandardFormatData();
        $this->layouts->set_title($getsitename . ' | Balance Sheet');
        $this->layouts->render('admin/balance-sheet-vertical', $data, 'admin');
    }

    public function getTotalValueForFirstLevel($total_balance_arr) {
        $total_balance_val = 0;
        if (!empty($total_balance_arr)) {
            foreach ($total_balance_arr as $total_balance) {
                if ($total_balance['level'] == 'level_first') {
                    if ($total_balance['account_type'] == 'Cr') {
                        $total_balance_val += $total_balance['amount'];
                    }
                    if($total_balance['account_type'] == 'Dr') {
                        $total_balance_val -= $total_balance['amount'];
                    }
                    
                }
            }
        }
        return $total_balance_val;
    }

    public function getCalculatedValue($trial_balance_arr = array(), $site) {
        $finalArray = array();
        if (!empty($trial_balance_arr)) {
            $closing_balance = 0;
            $total_debit_closing_balance = 0;
            $total_credit_closing_balance = 0;
            $finalArray = array();
            $i = 0;
            foreach ($trial_balance_arr as $trial_balance) {
                if ($trial_balance['account_type'] == 'Dr') {
                    $closing_balance = str_replace('-', '', $trial_balance['opening_balance']) + $trial_balance['dr_balance'] - $trial_balance['cr_balance'];
                    if ($closing_balance > 0) {
                        $account_type = 'Dr';
                        if ($trial_balance['type'] == 'ledger') {
                            $total_debit_closing_balance += $closing_balance;
                        }
                    } else {
                        $account_type = 'Cr';
                        if ($trial_balance['type'] == 'ledger') {
                            $total_credit_closing_balance += str_replace('-', '', sprintf('%0.2f', $closing_balance));
                        }
                    }
                }

                if ($trial_balance['account_type'] == 'Cr') {
                    $closing_balance = str_replace('-', '', $trial_balance['opening_balance']) + $trial_balance['cr_balance'] - $trial_balance['dr_balance'];
                    if ($closing_balance > 0) {
                        $account_type = 'Cr';
                        if ($trial_balance['type'] == 'ledger') {
                            $total_credit_closing_balance += str_replace('-', '', sprintf('%0.2f', $closing_balance));
                        }
                    } else {
                        $account_type = 'Dr';
                        if ($trial_balance['type'] == 'ledger') {
                            $total_debit_closing_balance += $closing_balance;
                        }
                    }
                }
                $finalArray[$i]['site'] = $site;
                $finalArray[$i]['type'] = $trial_balance['type'];
                $finalArray[$i]['level'] = $trial_balance['level'];
                $finalArray[$i]['id'] = $trial_balance['id'];
                $finalArray[$i]['name'] = $trial_balance['name'];
                $finalArray[$i]['code'] = $trial_balance['code'];
                $finalArray[$i]['parent_id'] = $trial_balance['parent_id'];
                $finalArray[$i]['account_type'] = $account_type;
                $finalArray[$i]['opening_balance'] = str_replace('-', '', $trial_balance['opening_balance']);
                $finalArray[$i]['amount'] = str_replace('-', '', $closing_balance);
                $i++;
            }
        }
        return $finalArray;
    }

    public function trial_balance($group_id = NULL) {
        
        user_permission(118,'list');
        $group_id = isset($_GET['id']) ? $_GET['id'] : $group_id;
        $data = array();
        $financial_year = get_financial_year();
        $data['cur_financial_year'] = date('Y', strtotime(current($financial_year)));
        
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-t", strtotime(end($financial_year)));
        $finans_start_date = $from_date;
        if (isset($_GET['staring_day']) && $_GET['staring_day'] != '' && isset($_GET['ending_day']) && $_GET['ending_day'] != '') {
            $from_date = date('Y-m-d', strtotime($_GET['staring_day'])); // somnath - date converted to sql format for query compare
            $to_date = date('Y-m-d', strtotime($_GET['ending_day'])); // somnath - date converted to sql format for query compare       
        }
        
        // somnath - opening stock calculation
        
//        $this->load->model('reports/admin/inventorymodel');
//        $category = $this->inventorymodel->getCategoryDetailsByDate(0, $from_date, $to_date); // to get list of categories
//        $category_list = [];
//        foreach ($category as $val) {
//            $category_list[] = $val->id;
//        }
//
//        $totalClosingPrice = 0;
//        $totalOpeningPrice = 0;
//        foreach ($category_list as $cat) {
//            $cat_id[0] = $cat;
//            $opening = $this->inventorymodel->getOpeningProductByCatId($cat_id, $from_date, $to_date);
//            $in_product = $this->inventorymodel->getInProductByCatId($cat_id, $from_date, $to_date);
//            $out_product = $this->inventorymodel->showoutstock($cat_id, $from_date, $to_date);
//
//            $opening_stock = (!empty($opening)) ? $opening[0]->opening : 0;
//            $opening_price = (!empty($opening)) ? $opening[0]->price : 0;
////            $totalOpeningPrice += $opening_price; // for getting total opening price individual opening price added to a variable
//            
//        }

        // somnath - ends
        

        
        $data['staring_day'] = $from_date;
        $data['ending_day'] = $to_date;
        // sudip rework 27082016
        $income_id = 1;
        $expenses_id = 2;
        $assets_id = 3;
        $liabilities_id = 4;
        $profit_loss_id = 5;
        $parent_id = 0;
        $trial_balance_arr = array();
        $i = 0;
        $profitLoss = $this->report->getProfitLoss($profit_loss_id);
        //$parentGoroups = $this->report->getAllGroupsById($parent_id);
        
       
        if (is_null($group_id)) {
           
            $levelOneGoroups = $this->report->getAllGroupsLevelOne($income_id, $expenses_id, $assets_id, $liabilities_id, $finans_start_date, $to_date);
        } else {
             
            $data['group']=$this->report->getGroup($group_id);
            if (isset($from_date) && $from_date && isset($to_date) && $to_date) {
                $levelOneGoroups = $this->report->getAllGroupsByIdByDate($group_id, $finans_start_date, $to_date);
            } else {
                $levelOneGoroups = $this->report->getAllGroupsById($group_id);
            }
            if (empty($levelOneGoroups)) {
                $this->group_report_group_id($group_id, $from_date, $to_date);
                
                return FALSE;
            }
        }
        
        //$levelOneGoroups = $this->report->getAllGroupsLevelOne($income_id,$expenses_id,$assets_id,$liabilities_id);
        if (!empty($levelOneGoroups)) {
            If (!is_null($group_id)) {
                $levelOneledgerDetails = $this->report->getAllLedgerByGroupIdByDate($group_id, $from_date, $to_date, $finans_start_date);
                //ledger related data    
                if (!empty($levelOneledgerDetails)) {
                    foreach ($levelOneledgerDetails as $levelTwoledgerDetail) {
                        $trial_balance_arr[$i]['type'] = 'ledger';
                        $trial_balance_arr[$i]['level'] = 'level_first';
                        $trial_balance_arr[$i]['id'] = $levelTwoledgerDetail['id'];
                        $trial_balance_arr[$i]['name'] = $levelTwoledgerDetail['ladger_name'];
                        $trial_balance_arr[$i]['code'] = $levelTwoledgerDetail['ledger_code'];
                        $trial_balance_arr[$i]['parent_id'] = $levelTwoledgerDetail['group_id'];
                        $trial_balance_arr[$i]['account_type'] = $levelTwoledgerDetail['account_type'];
                        $trial_balance_arr[$i]['opening_balance'] = $levelTwoledgerDetail['opening_balance'];
                        $trial_balance_arr[$i]['prev_cr_balance'] = $levelTwoledgerDetail['prev_cr_balance'];
                        $trial_balance_arr[$i]['prev_dr_balance'] = $levelTwoledgerDetail['prev_dr_balance'];
                        $trial_balance_arr[$i]['cr_balance'] = $levelTwoledgerDetail['cr_balance'];
                        $trial_balance_arr[$i]['dr_balance'] = $levelTwoledgerDetail['dr_balance'];

                        $i++;
                    }
                }
            }

            foreach ($levelOneGoroups AS $levelOneGoroup) {
                $trial_balance_arr[$i]['type'] = 'group';
                $trial_balance_arr[$i]['level'] = 'level_first';
                $trial_balance_arr[$i]['id'] = $levelOneGoroup['id'];
                $trial_balance_arr[$i]['name'] = $levelOneGoroup['group_name'];
                $trial_balance_arr[$i]['code'] = $levelOneGoroup['group_code'];
                $trial_balance_arr[$i]['parent_id'] = $levelOneGoroup['parent_id'];
                $trial_balance_arr[$i]['account_type'] = $this->get_account_type_by_date($levelOneGoroup['id'], $finans_start_date, $to_date);
                $trial_balance_arr[$i]['opening_balance'] = $this->get_opening_balance_by_date($levelOneGoroup['id'], $finans_start_date, $to_date);
                $trial_balance_arr[$i]['prev_cr_balance'] = $this->get_prev_cr_balance_by_date($levelOneGoroup['id'], $finans_start_date, $from_date,$finans_start_date);
                $trial_balance_arr[$i]['prev_dr_balance'] = $this->get_prev_dr_balance_by_date($levelOneGoroup['id'], $finans_start_date, $from_date,$finans_start_date);
                $trial_balance_arr[$i]['cr_balance'] = $this->get_cr_balance_by_date($levelOneGoroup['id'], $from_date, $to_date,$finans_start_date);
                $trial_balance_arr[$i]['dr_balance'] = $this->get_dr_balance_by_date($levelOneGoroup['id'], $from_date, $to_date,$finans_start_date);

                $i++;
                // For Level Second 
                $levelTwoGoroups = $this->report->getAllGroupsByIdByDate($levelOneGoroup['id'], $finans_start_date, $to_date);
                $levelTwoledgerDetails = $this->report->getAllLedgerByGroupIdByDate($levelOneGoroup['id'], $from_date, $to_date, $finans_start_date);
                //ledger related data    
                if (!empty($levelTwoledgerDetails)) {
                    foreach ($levelTwoledgerDetails as $levelTwoledgerDetail) {
                        $trial_balance_arr[$i]['type'] = 'ledger';
                        $trial_balance_arr[$i]['level'] = 'level_second';
                        $trial_balance_arr[$i]['id'] = $levelTwoledgerDetail['id'];
                        $trial_balance_arr[$i]['name'] = $levelTwoledgerDetail['ladger_name'];
                        $trial_balance_arr[$i]['code'] = $levelTwoledgerDetail['ledger_code'];
                        $trial_balance_arr[$i]['parent_id'] = $levelTwoledgerDetail['group_id'];
                        $trial_balance_arr[$i]['account_type'] = $levelTwoledgerDetail['account_type'];
                        $trial_balance_arr[$i]['opening_balance'] = $levelTwoledgerDetail['opening_balance'];
                        $trial_balance_arr[$i]['prev_cr_balance'] = $levelTwoledgerDetail['prev_cr_balance'];
                        $trial_balance_arr[$i]['prev_dr_balance'] = $levelTwoledgerDetail['prev_dr_balance'];
                        $trial_balance_arr[$i]['cr_balance'] = $levelTwoledgerDetail['cr_balance'];
                        $trial_balance_arr[$i]['dr_balance'] = $levelTwoledgerDetail['dr_balance'];

                        $i++;
                    }
                }

                //group related data
                if (!empty($levelTwoGoroups)) {
                    foreach ($levelTwoGoroups AS $levelTwoGoroup) {
                        $trial_balance_arr[$i]['type'] = 'group';
                        $trial_balance_arr[$i]['level'] = 'level_second';
                        $trial_balance_arr[$i]['id'] = $levelTwoGoroup['id'];
                        $trial_balance_arr[$i]['name'] = $levelTwoGoroup['group_name'];
                        $trial_balance_arr[$i]['code'] = $levelTwoGoroup['group_code'];
                        $trial_balance_arr[$i]['parent_id'] = $levelTwoGoroup['parent_id'];
                        $trial_balance_arr[$i]['account_type'] = $this->get_account_type_by_date($levelTwoGoroup['id'], $finans_start_date, $to_date);
                        $trial_balance_arr[$i]['opening_balance'] = $this->get_opening_balance_by_date($levelTwoGoroup['id'], $finans_start_date, $to_date);
                        $trial_balance_arr[$i]['prev_cr_balance'] = $this->get_prev_cr_balance_by_date($levelTwoGoroup['id'], $finans_start_date, $from_date,$finans_start_date);
                        $trial_balance_arr[$i]['prev_dr_balance'] = $this->get_prev_dr_balance_by_date($levelTwoGoroup['id'], $finans_start_date, $from_date,$finans_start_date);
                        $trial_balance_arr[$i]['cr_balance'] = $this->get_cr_balance_by_date($levelTwoGoroup['id'], $from_date, $to_date,$finans_start_date);
                        $trial_balance_arr[$i]['dr_balance'] = $this->get_dr_balance_by_date($levelTwoGoroup['id'], $from_date, $to_date,$finans_start_date);

                        $i++;
                        // For Level Third 
                        $levelThreeGoroups = $this->report->getAllGroupsByIdByDate($levelTwoGoroup['id'], $finans_start_date, $to_date);
                        $levelThreeledgerDetails = $this->report->getAllLedgerByGroupIdByDate($levelTwoGoroup['id'], $from_date, $to_date, $finans_start_date);
                        //ledger related data    
                        if (!empty($levelThreeledgerDetails)) {
                            foreach ($levelThreeledgerDetails as $levelThreeledgerDetail) {
                                $trial_balance_arr[$i]['type'] = 'ledger';
                                $trial_balance_arr[$i]['level'] = 'level_third';
                                $trial_balance_arr[$i]['id'] = $levelThreeledgerDetail['id'];
                                $trial_balance_arr[$i]['name'] = $levelThreeledgerDetail['ladger_name'];
                                $trial_balance_arr[$i]['code'] = $levelThreeledgerDetail['ledger_code'];
                                $trial_balance_arr[$i]['parent_id'] = $levelThreeledgerDetail['group_id'];
                                $trial_balance_arr[$i]['account_type'] = $levelThreeledgerDetail['account_type'];
                                $trial_balance_arr[$i]['opening_balance'] = $levelThreeledgerDetail['opening_balance'];
                                $trial_balance_arr[$i]['prev_cr_balance'] = $levelThreeledgerDetail['prev_cr_balance'];
                                $trial_balance_arr[$i]['prev_dr_balance'] = $levelThreeledgerDetail['prev_dr_balance'];
                                $trial_balance_arr[$i]['cr_balance'] = $levelThreeledgerDetail['cr_balance'];
                                $trial_balance_arr[$i]['dr_balance'] = $levelThreeledgerDetail['dr_balance'];

                                $i++;
                            }
                        }
                        //group related data  
                        if (!empty($levelThreeGoroups)) {
                            foreach ($levelThreeGoroups AS $levelThreeGoroup) {
                                $trial_balance_arr[$i]['type'] = 'group';
                                $trial_balance_arr[$i]['level'] = 'level_third';
                                $trial_balance_arr[$i]['id'] = $levelThreeGoroup['id'];
                                $trial_balance_arr[$i]['name'] = $levelThreeGoroup['group_name'];
                                $trial_balance_arr[$i]['code'] = $levelThreeGoroup['group_code'];
                                $trial_balance_arr[$i]['parent_id'] = $levelThreeGoroup['parent_id'];
                                $trial_balance_arr[$i]['account_type'] = $this->get_account_type_by_date($levelThreeGoroup['id'], $finans_start_date, $to_date);
                                $trial_balance_arr[$i]['opening_balance'] = $this->get_opening_balance_by_date($levelThreeGoroup['id'], $finans_start_date, $to_date);
                                $trial_balance_arr[$i]['prev_cr_balance'] = $this->get_prev_cr_balance_by_date($levelThreeGoroup['id'], $finans_start_date, $from_date,$finans_start_date);
                                $trial_balance_arr[$i]['prev_dr_balance'] = $this->get_prev_dr_balance_by_date($levelThreeGoroup['id'], $finans_start_date, $from_date,$finans_start_date);
                                $trial_balance_arr[$i]['cr_balance'] = $this->get_cr_balance_by_date($levelThreeGoroup['id'], $from_date, $to_date,$finans_start_date);
                                $trial_balance_arr[$i]['dr_balance'] = $this->get_dr_balance_by_date($levelThreeGoroup['id'], $from_date, $to_date,$finans_start_date);
                                $i++;
                            }
                        }
                    }
                }
            }
        }
        
        //Opening stock is added with current assets
        
        foreach ($trial_balance_arr as $key=>$val){
            $opening_stock_arr[0]['type'] = 'stock';
            $opening_stock_arr[0]['level'] = 'level_second';
            $opening_stock_arr[0]['id'] = 'opening';
            $opening_stock_arr[0]['name'] = 'Opening Stock';
            $opening_stock_arr[0]['code'] = 'O/S';
            $opening_stock_arr[0]['parent_id'] = 9;
            $opening_stock_arr[0]['account_type'] = 'Dr';
            $opening_stock_arr[0]['opening_balance'] = getOpeningBalance();
//            $opening_stock_arr[0]['opening_balance'] = $totalOpeningPrice; // somnath - opening balace
            $opening_stock_arr[0]['prev_cr_balance'] = 0.00;
            $opening_stock_arr[0]['prev_dr_balance'] = 0.00;
            $opening_stock_arr[0]['cr_balance'] = 0.00;
            $opening_stock_arr[0]['dr_balance'] = 0.00;
            if($val['level']=='level_first' && $val['id']== 9){
                $trial_balance_arr[$key]['opening_balance'] = $trial_balance_arr[$key]['opening_balance'] + getOpeningBalance();
                $opening_stock_arr[0]['level'] = 'level_second';
                array_splice($trial_balance_arr, ($key+1), 0, $opening_stock_arr);
                break;
            }
            if($group_id == 9){
                $opening_stock_arr[0]['level'] = 'level_first';
                array_splice($trial_balance_arr, 0, 0, $opening_stock_arr);
                break;
            }
        }
        
        $data['number_of_branch'] = $this->report->getNumberOfBranch();
        
        $data['trial_balance_arr'] = $trial_balance_arr;
        $data['profitLoss'] = $profitLoss;
        $data['configuration'] = $this->report->getCodePosition();
        $getsitename = getsitename();
        $this->load->model('admin/report');
        $data['get_standard_format_data'] = $this->report->getStandardFormatData();
        
        $this->layouts->set_title($getsitename . ' | Trial Balance');
        $this->layouts->render('admin/trial_balance', $data, 'admin');
    }

    public function group_report_group_id($group_id, $from_date, $to_date) {
        $data = array();
        $financial_year = get_financial_year();
        $data['cur_financial_year'] = date('Y', strtotime(current($financial_year)));
        $finans_start_date = date("Y-m-d", strtotime(current($financial_year)));

        if ($from_date && $to_date) {
            $result = $this->report->getLedgerDetailsByGroupIdByDate($group_id, $from_date, $to_date);
        } else {
            $result = $this->report->getLedgerDetailsByGroupId($group_id);
        }
        $data['number_of_branch'] = $this->report->getNumberOfBranch();
        $data['group'] = $this->report->getGroup($group_id);
        $data['gr_name'] = $data['group']->group_name;
        $data['staring_day'] = $from_date;
        $data['ending_day'] = $to_date;
        $data['result'] = $result;
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Groups Report');
        $this->load->model('admin/report');
        $data['get_standard_format_data'] = $this->report->getStandardFormatData();
        $this->layouts->render('reports/admin/group_details', $data, 'admin');
    }

    public function get_cr_balance($group_id) {
        $cr_balance = 0;
        $CrBalance = array();
        $arr = array();
        $resultOpeningBalance = $this->report->getCrBalanceByGroupId($group_id);
        $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
        $childDetails = $this->report->getChildIdParentId($group_id);
        foreach ($childDetails as $childDetail) {
            $id = $childDetail['id'];
            array_push($arr, $id);
            $resultOpeningBalance = $this->report->getCrBalanceByGroupId($childDetail['id']);
            $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
        }
        for ($i = 0; $i < 5; $i++) {
            $newArr = array();
            for ($j = 0; $j < count($arr); $j++) {
                $childDetails1 = $this->report->getChildIdParentId($arr[$j]);
                foreach ($childDetails1 as $childDetail) {
                    array_push($newArr, $childDetail['id']);
                    $resultOpeningBalance = $this->report->getCrBalanceByGroupId($childDetail['id']);
                    $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
                }
            }
            $arr = $newArr;
        }

        foreach ($CrBalance as $row) {
            $cr_balance += $row['cr_balance'];
        }

        return $cr_balance;
    }

    //get cr balence by group id and date range--@asit
    public function get_cr_balance_by_date($group_id, $from_date, $to_date,$finans_start_date) {
        $cr_balance = 0;
        $CrBalance = array();
        $arr = array();
        $resultOpeningBalance = $this->report->getCrBalanceByGroupIdByDate($group_id, $from_date, $to_date,$finans_start_date);
        $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
        $childDetails = $this->report->getChildIdParentIdByDate($group_id, $finans_start_date, $to_date);
        foreach ($childDetails as $childDetail) {
            $id = $childDetail['id'];
            array_push($arr, $id);
            $resultOpeningBalance = $this->report->getCrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date,$finans_start_date);
            $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
        }
        for ($i = 0; $i < 5; $i++) {
            $newArr = array();
            for ($j = 0; $j < count($arr); $j++) {
                $childDetails1 = $this->report->getChildIdParentIdByDate($arr[$j], $finans_start_date, $to_date);
                foreach ($childDetails1 as $childDetail) {
                    array_push($newArr, $childDetail['id']);
                    $resultOpeningBalance = $this->report->getCrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date,$finans_start_date);
                    $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
                }
            }
            $arr = $newArr;
        }

        foreach ($CrBalance as $row) {
            $cr_balance += $row['cr_balance'];
        }

        return $cr_balance;
    }
    
    //get prev cr balence by group id and date range--@asit
    public function get_prev_cr_balance_by_date($group_id, $from_date, $to_date,$finans_start_date) {
        $cr_balance = 0;
        $CrBalance = array();
        $arr = array();
        $resultOpeningBalance = $this->report->getPrevCrBalanceByGroupIdByDate($group_id, $from_date, $to_date,$finans_start_date);
        $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
        $childDetails = $this->report->getChildIdParentIdByDate($group_id, $finans_start_date, $to_date);
        foreach ($childDetails as $childDetail) {
            $id = $childDetail['id'];
            array_push($arr, $id);
            $resultOpeningBalance = $this->report->getPrevCrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date,$finans_start_date);
            $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
        }
        for ($i = 0; $i < 5; $i++) {
            $newArr = array();
            for ($j = 0; $j < count($arr); $j++) {
                $childDetails1 = $this->report->getChildIdParentIdByDate($arr[$j], $finans_start_date, $to_date);
                foreach ($childDetails1 as $childDetail) {
                    array_push($newArr, $childDetail['id']);
                    $resultOpeningBalance = $this->report->getPrevCrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date,$finans_start_date);
                    $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
                }
            }
            $arr = $newArr;
        }

        foreach ($CrBalance as $row) {
            $cr_balance += $row['cr_balance'];
        }

        return $cr_balance;
    }

    public function get_dr_balance($group_id) {
        $dr_balance = 0;
        $drBalance = array();
        $arr = array();
        $resultOpeningBalance = $this->report->getDrBalanceByGroupId($group_id);
        $drBalance = array_merge($drBalance, $resultOpeningBalance);
        $childDetails = $this->report->getChildIdParentId($group_id);
        foreach ($childDetails as $childDetail) {
            $id = $childDetail['id'];
            array_push($arr, $id);
            $resultOpeningBalance = $this->report->getDrBalanceByGroupId($childDetail['id']);
            $drBalance = array_merge($drBalance, $resultOpeningBalance);
        }
        for ($i = 0; $i < 5; $i++) {
            $newArr = array();
            for ($j = 0; $j < count($arr); $j++) {
                $childDetails1 = $this->report->getChildIdParentId($arr[$j]);
                foreach ($childDetails1 as $childDetail) {
                    array_push($newArr, $childDetail['id']);
                    $resultOpeningBalance = $this->report->getDrBalanceByGroupId($childDetail['id']);
                    $drBalance = array_merge($drBalance, $resultOpeningBalance);
                }
            }
            $arr = $newArr;
        }

        foreach ($drBalance as $row) {
            $dr_balance += $row['dr_balance'];
        }

        return $dr_balance;
        //echo $dr_balance;die();
    }

    //get DR balence by group id and date range--@asit
    public function get_dr_balance_by_date($group_id, $from_date, $to_date,$finans_start_date) {
        $dr_balance = 0;
        $drBalance = array();
        $arr = array();
        $resultOpeningBalance = $this->report->getDrBalanceByGroupIdByDate($group_id, $from_date, $to_date,$finans_start_date);

        $drBalance = array_merge($drBalance, $resultOpeningBalance);
        $childDetails = $this->report->getChildIdParentIdByDate($group_id, $finans_start_date, $to_date);

        foreach ($childDetails as $childDetail) {
            $id = $childDetail['id'];
            array_push($arr, $id);
            $resultOpeningBalance = $this->report->getDrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date,$finans_start_date);
            $drBalance = array_merge($drBalance, $resultOpeningBalance);
        }

        for ($i = 0; $i < 5; $i++) {
            $newArr = array();
            for ($j = 0; $j < count($arr); $j++) {
                $childDetails1 = $this->report->getChildIdParentIdByDate($arr[$j], $finans_start_date, $to_date);
                foreach ($childDetails1 as $childDetail) {
                    array_push($newArr, $childDetail['id']);
                    $resultOpeningBalance = $this->report->getDrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date,$finans_start_date);
                    $drBalance = array_merge($drBalance, $resultOpeningBalance);
                }
            }
            $arr = $newArr;
        }
        foreach ($drBalance as $row) {
            $dr_balance += $row['dr_balance'];
        }

        return $dr_balance;
        //echo $dr_balance;die();
    }
    
      //get DR balence by group id and date range--@asit
    public function get_prev_dr_balance_by_date($group_id, $from_date, $to_date,$finans_start_date) {
        $dr_balance = 0;
        $drBalance = array();
        $arr = array();
        $resultOpeningBalance = $this->report->getPrevDrBalanceByGroupIdByDate($group_id, $from_date, $to_date,$finans_start_date);

        $drBalance = array_merge($drBalance, $resultOpeningBalance);
        $childDetails = $this->report->getChildIdParentIdByDate($group_id, $finans_start_date, $to_date);

        foreach ($childDetails as $childDetail) {
            $id = $childDetail['id'];
            array_push($arr, $id);
            $resultOpeningBalance = $this->report->getPrevDrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date,$finans_start_date);
            $drBalance = array_merge($drBalance, $resultOpeningBalance);
        }

        for ($i = 0; $i < 5; $i++) {
            $newArr = array();
            for ($j = 0; $j < count($arr); $j++) {
                $childDetails1 = $this->report->getChildIdParentIdByDate($arr[$j], $finans_start_date, $to_date);
                foreach ($childDetails1 as $childDetail) {
                    array_push($newArr, $childDetail['id']);
                    $resultOpeningBalance = $this->report->getPrevDrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date,$finans_start_date);
                    $drBalance = array_merge($drBalance, $resultOpeningBalance);
                }
            }
            $arr = $newArr;
        }
        foreach ($drBalance as $row) {
            $dr_balance += $row['dr_balance'];
        }

        return $dr_balance;
        //echo $dr_balance;die();
    }

    //sudip add new function get opening balance by group id
    public function get_opening_balance($group_id) {
        $returnArr = $this->get_opening_balance_cal($group_id);
        $opening_balance = 0;
        foreach ($returnArr as $row) {
            if ($row['account_type'] == 'Dr') {
                $opening_balance = $opening_balance + $row['opening_balance'];
            }
            if ($row['account_type'] == 'Cr') {
                $opening_balance = $opening_balance - $row['opening_balance'];
            }
        }
        return $opening_balance;
    }

    //get opening balence by group id and date range--@asit
    public function get_opening_balance_by_date($group_id, $from_date, $to_date) {
        $returnArr = $this->get_opening_balance_cal_by_date($group_id, $from_date, $to_date);
        $opening_balance = 0;
        foreach ($returnArr as $row) {
            if ($row['account_type'] == 'Dr') {
                $opening_balance = $opening_balance + $row['opening_balance'];
            }
            if ($row['account_type'] == 'Cr') {
                $opening_balance = $opening_balance - $row['opening_balance'];
            }
        }
        return $opening_balance;
    }

    public function get_account_type($group_id) {
        $returnArr = $this->get_opening_balance_cal($group_id);
        $opening_balance = 0;
        $dr_opening_bal = 0;
        $cr_opening_bal = 0;
        foreach ($returnArr as $row) {
            if ($row['account_type'] == 'Dr') {
                $opening_balance = $opening_balance + $row['opening_balance'];
                $dr_opening_bal = $dr_opening_bal + $row['opening_balance'];
            }
            if ($row['account_type'] == 'Cr') {
                $opening_balance = $opening_balance - str_replace('-', '', $row['opening_balance']);
                $cr_opening_bal = $cr_opening_bal + str_replace('-', '', $row['opening_balance']);
            }
        }
        if ($opening_balance >= 0) {
            return 'Dr';
        }
        if ($opening_balance < 0) {
            return 'Cr';
        }
    }

    //get  account type by group id and date range--@asit
    public function get_account_type_by_date($group_id, $from_date, $to_date) {
        $returnArr = $this->get_opening_balance_cal_by_date($group_id, $from_date, $to_date);
        $opening_balance = 0;
        $dr_opening_bal = 0;
        $cr_opening_bal = 0;
        foreach ($returnArr as $row) {
            if ($row['account_type'] == 'Dr') {
                $opening_balance = $opening_balance + $row['opening_balance'];
                $dr_opening_bal = $dr_opening_bal + $row['opening_balance'];
            }
            if ($row['account_type'] == 'Cr') {
                $opening_balance = $opening_balance - str_replace('-', '', $row['opening_balance']);
                $cr_opening_bal = $cr_opening_bal + str_replace('-', '', $row['opening_balance']);
            }
        }
        if ($opening_balance >= 0) {
            return 'Dr';
        }
        if ($opening_balance < 0) {
            return 'Cr';
        }
    }

    public function get_opening_balance_cal($group_id) {
        $openingBalance = array();
        $arr = array();
        $resultOpeningBalance = $this->report->getOpeningBalanceByGroupId($group_id);
        $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
        $childDetails = $this->report->getChildIdParentId($group_id);
        foreach ($childDetails as $childDetail) {
            $id = $childDetail['id'];
            array_push($arr, $id);
            $resultOpeningBalance = $this->report->getOpeningBalanceByGroupId($childDetail['id']);
            $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
        }
        for ($i = 0; $i < 5; $i++) {
            $newArr = array();
            for ($j = 0; $j < count($arr); $j++) {
                $childDetails1 = $this->report->getChildIdParentId($arr[$j]);
                foreach ($childDetails1 as $childDetail) {
                    array_push($newArr, $childDetail['id']);
                    $resultOpeningBalance = $this->report->getOpeningBalanceByGroupId($childDetail['id']);
                    $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
                }
            }
            $arr = $newArr;
        }

        return $openingBalance;

        //echo '<pre>';print_r($openingBalance);die();
    }

    //get opening balence by group id and date range--@asit 
    public function get_opening_balance_cal_by_date($group_id, $from_date, $to_date) {
        $openingBalance = array();
        $arr = array();
        $resultOpeningBalance = $this->report->getOpeningBalanceByGroupIdByDate($group_id, $from_date, $to_date);
        $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
        $childDetails = $this->report->getChildIdParentIdByDate($group_id, $from_date, $to_date);
        foreach ($childDetails as $childDetail) {
            $id = $childDetail['id'];
            array_push($arr, $id);
            $resultOpeningBalance = $this->report->getOpeningBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date);
            $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
        }
        for ($i = 0; $i < 5; $i++) {
            $newArr = array();
            for ($j = 0; $j < count($arr); $j++) {
                $childDetails1 = $this->report->getChildIdParentIdByDate($arr[$j], $from_date, $to_date);
                foreach ($childDetails1 as $childDetail) {
                    array_push($newArr, $childDetail['id']);
                    $resultOpeningBalance = $this->report->getOpeningBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date);
                    $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
                }
            }
            $arr = $newArr;
        }

        return $openingBalance;

        //echo '<pre>';print_r($openingBalance);die();
    }

    public function tree($id = 1) {
        $sum = 0;
        $arr = array();
        $childDetails = $this->report->getTreeChild($id);
        foreach ($childDetails as $childDetail) {
            $sum += $childDetail['amount'];
            $id = $childDetail['id'];
            array_push($arr, $id);
        }

        for ($i = 0; $i < 2; $i++) {
            $newArr = array();
            for ($j = 0; $j < count($arr); $j++) {
                $childDetails1 = $this->report->getTreeChild($arr[$j]);
                foreach ($childDetails1 as $childDetail) {
                    $sum += $childDetail['amount'];
                    array_push($newArr, $childDetail['id']);
                }
            }
            $arr = $newArr;
        }

        echo $sum;
        print_r($newArr);
        die();
    }

    public function trial_balance_by_date() {
        $from_dateOld = str_replace("/", "-", $_POST['from_date']);
        $to_dateOld = str_replace("/", "-", $_POST['to_date']);
        $from_date = date('Y-m-d', strtotime($from_dateOld));

        if (!empty($to_dateOld)) {
            $to_date = date('Y-m-d', strtotime($to_dateOld));
        }
        if (empty($to_dateOld)) {
            $to_date = date('Y-m-d', strtotime('2050-12-31'));
        }
        //echo $from_date . '<br>';
        // echo $to_date;



        $where = array(
            'ladger.status' => 1,
        );
        //$ledgers = $this->account->getAllLedger($where);
        $ledgers = $this->report->trialBalance($where);
        unset($where);
        //echo '<pre>'; print_r($ledgers);
        //'entry.create_date <' => $before_selected_date,  
        $where = array(
            'ladger_account_detail.status' => 1,
            'ladger_account_detail.deleted' => 0,
            'ladger_account_detail.entry_id !=' => 0,
            'ladger_account_detail.create_date >=' => $from_date,
            'ladger_account_detail.create_date <=' => $to_date
        );
        $entries = $this->report->allEntry($where);
        //echo '<pre>'; print_r($entries); exit;
        $ledger_gr = array();
        $ledger_grand = array();
        $group_detail = array();
        $newtestarray = array();
        foreach ($ledgers as $ledger) {

//            if(!in_array($ledger['group_name'],$newtestarray ))
//            {
//                echo $ledger['group_name'].'<br>';
//            $newtestarray[]=$ledger['group_name'];
//            $group_opening_balance_total = 0;
//            $group_closing_balance_total = 0; 
//            }



            $where = array(
                'entry.company_id' => 1,
                'entry.create_date <' => $from_date,
                'entry.status' => 1,
                'entry.deleted' => 0,
                'ladger_account_detail.status' => 1,
                'ladger_account_detail.deleted' => 0,
                'ladger.id' => $ledger['id'],
            );
            $entry_detail_before_date = $this->report->allTransaction($where);
            $opening_balance12 = 0;
            $opening_balance123 = 0;
            $final_previous_balance = 0;
            if (count($entry_detail_before_date) > 0) {

                $all_debit = 0;
                $all_cradit = 0;
                foreach ($entry_detail_before_date as $value) {
                    if ($value['account'] == 'Dr') {
                        $all_debit += $value['balance'];
                    }
                    if ($value['account'] == 'Cr') {
                        $all_cradit += $value['balance'];
                    }
                }
                $final_previous_balance = ($all_debit - $all_cradit);
                $opening_balance12 = $final_previous_balance + $ledger['opening_balance'];
            } else {
                $opening_balance12 = $ledger['opening_balance'];
            }

            $account = 'Dr';
            $balance = 0;
            $total_dr_amount = 0;
            $total_dr_balance = 0;
            $total_cr_balance = 0;
            $group_dr_amount = 0;
            $group_cr_amount = 0;
            $group_grand_debit_total = 0;
            $group_opening_balance_total = 0;
            $group_closing_balance_total = 0;

            $newToalopening = $opening_balance12;

            $newToalclosing = $ledger['current_balance'];
            if (count($entries) > 0) {
                foreach ($entries as $entry) {
                    // $group_opening_balance_total += $newToalopening;
                    //  $group_closing_balance_total += $newToalclosing;
                    // $newToalopening=0;
                    // $newToalclosing=0;
                    // echo $group_opening_balance_total.'/'.$newToalopening.'<br>';
                    if ($ledger['group_id'] == $entry['group_id']) {
                        if ($entry['account'] == 'Dr') {
                            $group_dr_amount += $entry['balance'];
                        }
                        if ($entry['account'] == 'Cr') {
                            $group_cr_amount += $entry['balance'];
                        }
//                        $group_opening_balance_total += $ledger['opening_balance'];
//                        $group_closing_balance_total += $ledger['current_balance'];
                    }
                    if ($ledger['ladger_name'] == $entry['ladger_name']) {
                        if ($entry['account'] == 'Dr') {
                            $total_dr_balance += $entry['balance'];
                        }
                        if ($entry['account'] == 'Cr') {
                            $total_cr_balance += $entry['balance'];
                        }
                    }
                }
            } else {
                $entry = array();
            }

            $parent_category = array();
            $where = array(
                //'group.user_id' => $this->session->userdata('user_id'),
                //'group.company_id' => 1,
                'group.status' => 1,
                'group.deleted' => 0,
                'group.id' => $ledger['group_id']
            );
            $group_name = $this->report->getGrandParentid($where);
            $group = '';
            if ($group_name[0]['parent_id'] != 0 &&
                    $group_name[0]['user_id'] == $this->session->userdata('user_id') &&
                    $group_name[0]['company_id'] == 1) {
                $this->gete_parent($group_name[0]['parent_id']);
                $parent_category = array_pop($this->store);
                $group = $parent_category[0]['group_name'];
            } else {
                $group = $group_name[0]['group_name'];
            }

            if (!in_array($ledger['group_name'], $ledger_gr)) {
                $ledger_gr[] = $ledger['group_name'];
                $group_detail[$group][$ledger['group_name']] = array('group_dr_amount' => $group_dr_amount, 'group_cr_amount' => $group_cr_amount, 'group_opening_balance_total' => $group_opening_balance_total, 'group_closing_balance_total' => $group_closing_balance_total);
            }

            $group_detail[$group][$ledger['group_name']]['ladger'][] = array(
                'ladger_name' => $ledger['ladger_name'],
                'opening_balance' => $ledger['opening_balance'] + $final_previous_balance,
                'current_balance' => $ledger['current_balance'],
                'opening_balance_account_type' => $ledger['account_type'],
                'account' => count($entry) > 0 ? $entry['account'] : 0.00,
                'balance' => count($entry) > 0 ? $entry['balance'] : 'Dr',
                'total_dr_balance' => $total_dr_balance,
                'total_cr_balance' => $total_cr_balance
            );
        }

        foreach ($group_detail as $gkey => $groups) {

            foreach ($groups as $igkey => $innergroup) {


                foreach ($innergroup['ladger'] as $leg) {
                    // if($leg['opening_balance_account_type']=='Dr'){
                    // $group_detail[$gkey][$igkey]['group_opening_balance_total'] += $leg['opening_balance'];
                    // }
                    // else{
                    //    $group_detail[$gkey][$igkey]['group_opening_balance_total'] -= $leg['opening_balance']; 
                    // }
                    $group_detail[$gkey][$igkey]['group_closing_balance_total'] += $leg['current_balance'];
                    $group_detail[$gkey][$igkey]['group_opening_balance_total'] += $leg['opening_balance'];
                }
            }
        }
        // echo '<pre>'; print_r($group_detail);exit;
        $data['all_details'] = $group_detail;
        echo $this->load->view('ajax_trial_balance', $data, true);
    }

    public function getTotalBalanceBS($entries, $ladger_id, $group_name) {
        $totalBalance = 0;
        foreach ($entries AS $entry) {
            if ($ladger_id == $entry['ladger_id']) {
                if ($group_name == 'Assets') {
                    $totalBalance = $entry['balance_dr'] - str_replace('-', '', $entry['balance_cr']);
                    if ($entry['op_account'] == 'Dr') {
                        $totalBalance = $totalBalance + $entry['op_balance'];
                    } else {
                        $totalBalance = $totalBalance - str_replace('-', '', $entry['op_balance']);
                    }
                } else {

                    $totalBalance = str_replace('-', '', $entry['balance_cr']) - $entry['balance_dr'];
                    if ($entry['op_account'] == 'Cr') {
                        $totalBalance = $totalBalance + str_replace('-', '', $entry['op_balance']);
                    } else {
                        $totalBalance = $totalBalance - $entry['op_balance'];
                    }
                }
            }
        }

        return $totalBalance;
    }

    public function balance_sheet() {
        $financial_year = get_financial_year();
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-d", strtotime(end($financial_year)));
        $data['staring_day'] = $from_date;
        $data['ending_day'] = $to_date;
        $assets_ordering_array = array(6, 7, 9, 11, 10, 8, 15);
        $data['assetes_ordering'] = $this->report->assetesOrdering($assets_ordering_array);

        $where = array(
            'ladger.status' => 1,
            'ladger.deleted' => 0
        );

        $ledgers = $this->report->trialBalance($where);

        unset($where);
        $where = array(
            'ladger.status' => 1,
            'ladger.deleted' => 0,
        );
        $ledger_op_balance = $this->report->getOpeningBalance($where);


        unset($where);

        $where = array(
            'ladger_account_detail.status' => 1,
            'ladger_account_detail.deleted' => 0
        );

        $entries = $this->report->allEntryBS($where);

        $ledger_gr = array();
        $ledger_grand = array();
        $entry['account'] = 0;
        $entry['balance'] = 0;

        foreach ($ledgers as $ledger) {
            $total_dr_amount = 0;
            $total_dr_balance = 0;
            $total_cr_balance = 0;
            $group_dr_amount = 0;
            $group_cr_amount = 0;
            $group_grand_debit_total = 0;
            $total_opening_balance = 0;
            $total_dr_opening_balance = 0;
            $total_cr_opening_balance = 0;

            foreach ($entries as $entry) {
                if ($ledger['group_id'] == $entry['group_id']) {
                    if ($entry['account'] == 'Dr') {
                        $group_dr_amount += $entry['balance'];
                    }
                    if ($entry['account'] == 'Cr') {
                        $group_cr_amount += $entry['balance'];
                    }
                }
                if ($ledger['ladger_name'] == $entry['ladger_name']) {
                    if ($entry['account'] == 'Dr') {
                        $total_dr_balance += $entry['balance'];
                    }
                    if ($entry['account'] == 'Cr') {
                        $total_cr_balance += str_replace('-', '', $entry['balance']); //sudip//15092016
                    }
                }
            }

            $parent_category = array();
            $where = array(
                'group.status' => 1,
                'group.deleted' => 0,
                'group.id' => $ledger['group_id']
            );

            $group_name = $this->report->getGrandParentid($where);
            $group = '';
            if ($group_name[0]['parent_id'] != 0) {
                $this->gete_parent($group_name[0]['parent_id']);
                $parent_category = array_pop($this->store);
                $group = $parent_category[0]['group_name'];
            } else {
                $group = $group_name[0]['group_name'];
            }

            $group_grand_debit_total += $group_dr_amount;

            if (!in_array($ledger['group_name'], $ledger_gr)) {
                $ledger_gr[] = $ledger['group_name'];
                $group_detail[$group][$ledger['group_name']] = array('group_dr_amount' => $group_dr_amount, 'group_cr_amount' => $group_cr_amount, 'group_opening_balance_total' => 0, 'group_closing_balance_total' => 0);
            }

            $group_detail[$group][$ledger['group_name']][] = array(
                'ladger_name' => $ledger['ladger_name'],
                'opening_balance' => $ledger['opening_balance'],
                'current_balance' => $ledger['current_balance'],
                'account' => $entry['account'],
                'balance' => $entry['balance'],
                'total_balance' => $this->getTotalBalanceBS($entries, $ledger['id'], $group),
                'total_dr_balance' => $total_dr_balance,
                'total_cr_balance' => $total_cr_balance
            );
        }

        if (!empty($ledger_op_balance)) {
            foreach ($ledger_op_balance AS $op) {
                if ($op['account_type'] == 'Dr') {
                    $total_dr_opening_balance += $op['opening_balance'];
                }
                if ($op['account_type'] == 'Cr') {
                    $total_cr_opening_balance += str_replace('-', '', $op['opening_balance']);
                }
            }
        }

        $total_opening_balance = $total_dr_opening_balance - $total_cr_opening_balance;

        foreach ($group_detail as $gkey => $groups) {
            foreach ($groups as $igkey => $innergroup) {
                foreach ($innergroup as $legKey => $leg) {
                    $group_detail[$gkey][$igkey]['group_closing_balance_total'] += $leg['total_balance'];
                }
            }
        }
        $liabilits = $this->sortingt_parent_group_array_bs($group_detail, 'Liabilities');
        $assets = $this->sortingt_parent_group_array_bs($group_detail, 'Assets');


        $data['liabilits'] = $liabilits;
        $data['assets'] = $assets;
        $data['opening_balance'] = $total_opening_balance;
        $data['opening_pl'] = $this->report->openingPL();
        $getsitename = getsitename();
        $this->load->model('admin/report');
        $data['get_standard_format_data'] = $this->report->getStandardFormatData();
        $this->layouts->set_title($getsitename . ' | Reports');
        $this->layouts->render('admin/balance_sheet', $data, 'admin');
    }

    //balence sheet ajax by date-----asit
    public function ajax_balance_sheet_by_date() {
        $data = [];
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $financial_year = get_financial_year();
            $from_date = date("Y-m-d", strtotime(current($financial_year)));
            $to_date = $this->input->post('ending_day');
            $data['staring_day'] = $from_date;
            $data['ending_day'] = $to_date;
            $assets_ordering_array = array(6, 7, 9, 11, 10, 8, 15);
            $data['assetes_ordering'] = $this->report->assetesOrderingByDate($assets_ordering_array, $from_date, $to_date);
            $where = array(
                'ladger.status' => 1,
                'ladger.deleted' => 0
            );

            $ledgers = $this->report->trialBalanceByDate($where, $from_date, $to_date);

            unset($where);
            $where = array(
                'ladger.status' => 1,
                'ladger.deleted' => 0,
            );
            $ledger_op_balance = $this->report->getOpeningBalanceByDate($where, $from_date, $to_date);
            unset($where);

            $where = array(
                'ladger_account_detail.status' => 1,
                'ladger_account_detail.deleted' => 0
            );
            $entries = $this->report->allEntryBSByDate($where, $from_date, $to_date);
            $ledger_gr = array();
            $ledger_grand = array();
            $entry['account'] = 0;
            $entry['balance'] = 0;

            foreach ($ledgers as $ledger) {
                $total_dr_amount = 0;
                $total_dr_balance = 0;
                $total_cr_balance = 0;
                $group_dr_amount = 0;
                $group_cr_amount = 0;
                $group_grand_debit_total = 0;
                $total_opening_balance = 0;
                $total_dr_opening_balance = 0;
                $total_cr_opening_balance = 0;

                foreach ($entries as $entry) {
                    if ($ledger['group_id'] == $entry['group_id']) {
                        if ($entry['account'] == 'Dr') {
                            $group_dr_amount += $entry['balance'];
                        }
                        if ($entry['account'] == 'Cr') {
                            $group_cr_amount += $entry['balance'];
                        }
                    }
                    if ($ledger['ladger_name'] == $entry['ladger_name']) {
                        if ($entry['account'] == 'Dr') {
                            $total_dr_balance += $entry['balance'];
                        }
                        if ($entry['account'] == 'Cr') {
                            $total_cr_balance += str_replace('-', '', $entry['balance']); //sudip//15092016
                        }
                    }
                }

                $parent_category = array();
                $where = array(
                    'group.status' => 1,
                    'group.deleted' => 0,
                    'group.id' => $ledger['group_id']
                );
                $group_name = $this->report->getGrandParentidByDate($where, $from_date, $to_date);
                $group = '';
                if ($group_name) {
                    if ($group_name[0]['parent_id'] != 0) {
                        $this->gete_parent_by_date($group_name[0]['parent_id'], $from_date, $to_date);
                        $parent_category = array_pop($this->store);
                        $group = $parent_category[0]['group_name'];
                    } else {
                        $group = $group_name[0]['group_name'];
                    }
                }
                $group_grand_debit_total += $group_dr_amount;

                if (!in_array($ledger['group_name'], $ledger_gr)) {
                    $ledger_gr[] = $ledger['group_name'];
                    $group_detail[$group][$ledger['group_name']] = array('group_dr_amount' => $group_dr_amount, 'group_cr_amount' => $group_cr_amount, 'group_opening_balance_total' => 0, 'group_closing_balance_total' => 0);
                }

                $group_detail[$group][$ledger['group_name']][] = array(
                    'ladger_name' => $ledger['ladger_name'],
                    'opening_balance' => $ledger['opening_balance'],
                    'current_balance' => $ledger['current_balance'],
                    'account' => $entry['account'],
                    'balance' => $entry['balance'],
                    'total_balance' => $this->getTotalBalanceBS($entries, $ledger['id'], $group),
                    'total_dr_balance' => $total_dr_balance,
                    'total_cr_balance' => $total_cr_balance
                );
            }

            if (!empty($ledger_op_balance)) {
                foreach ($ledger_op_balance AS $op) {
                    if ($op['account_type'] == 'Dr') {
                        $total_dr_opening_balance += $op['opening_balance'];
                    }
                    if ($op['account_type'] == 'Cr') {
                        $total_cr_opening_balance += str_replace('-', '', $op['opening_balance']);
                    }
                }
            }

            $total_opening_balance = $total_dr_opening_balance - $total_cr_opening_balance;

            foreach ($group_detail as $gkey => $groups) {
                foreach ($groups as $igkey => $innergroup) {
                    foreach ($innergroup as $legKey => $leg) {
                        $group_detail[$gkey][$igkey]['group_closing_balance_total'] += $leg['total_balance'];
                    }
                }
            }

            $liabilits = $this->sortingt_parent_group_array_bs($group_detail, 'Liabilities');
            $assets = $this->sortingt_parent_group_array_bs($group_detail, 'Assets');


            $data['liabilits'] = $liabilits;
            $data['assets'] = $assets;
            $data['opening_balance'] = $total_opening_balance;
            $data['opening_pl'] = $this->report->openingPLByDate($from_date, $to_date);
            $data_msg['html'] = $this->load->view('admin/ajax_balance_sheet', $data, TRUE);
            $data_msg['res'] = 'success';
            echo json_encode($data_msg);
            exit;
        }
    }

    //balence sheet backup 10-01-2017
    public function balance_sheet_bk_10_01_2017() {
        $assets_ordering_array = array(6, 7, 9, 11, 10, 8);
        $assetes_ordering = array();
        for ($k = 0; $k < count($assets_ordering_array); $k++) {
            $result = $this->report->assetesOrdering($assets_ordering_array[$k]);
            $assetes_ordering = array_merge($assetes_ordering, $result);
        }
        $data['assetes_ordering'] = $assetes_ordering;

        $where = array(
            'ladger.status' => 1,
            'ladger.deleted' => 0
        );

        $ledgers = $this->report->trialBalance($where);

        unset($where);
        $where = array(
            'ladger.status' => 1,
            'ladger.deleted' => 0,
        );
        $ledger_op_balance = $this->report->getOpeningBalance($where);
        unset($where);

        $where = array(
            'ladger_account_detail.status' => 1,
            'ladger_account_detail.deleted' => 0
        );
        $entries = $this->report->allEntryBS($where);
        $ledger_gr = array();
        $ledger_grand = array();
        $entry['account'] = 0;
        $entry['balance'] = 0;

        foreach ($ledgers as $ledger) {
            $total_dr_amount = 0;
            $total_dr_balance = 0;
            $total_cr_balance = 0;
            $group_dr_amount = 0;
            $group_cr_amount = 0;
            $group_grand_debit_total = 0;
            $total_opening_balance = 0;
            $total_dr_opening_balance = 0;
            $total_cr_opening_balance = 0;

            foreach ($entries as $entry) {
                if ($ledger['group_id'] == $entry['group_id']) {
                    if ($entry['account'] == 'Dr') {
                        $group_dr_amount += $entry['balance'];
                    }
                    if ($entry['account'] == 'Cr') {
                        $group_cr_amount += $entry['balance'];
                    }
                }
                if ($ledger['ladger_name'] == $entry['ladger_name']) {
                    if ($entry['account'] == 'Dr') {
                        $total_dr_balance += $entry['balance'];
                    }
                    if ($entry['account'] == 'Cr') {
                        $total_cr_balance += str_replace('-', '', $entry['balance']); //sudip//15092016
                    }
                }
            }

            $parent_category = array();
            $where = array(
                'group.status' => 1,
                'group.deleted' => 0,
                'group.id' => $ledger['group_id']
            );
            $group_name = $this->report->getGrandParentid($where);
            $group = '';
            if ($group_name[0]['parent_id'] != 0) {
                $this->gete_parent($group_name[0]['parent_id']);
                $parent_category = array_pop($this->store);
                $group = $parent_category[0]['group_name'];
            } else {
                $group = $group_name[0]['group_name'];
            }

            $group_grand_debit_total += $group_dr_amount;

            if (!in_array($ledger['group_name'], $ledger_gr)) {
                $ledger_gr[] = $ledger['group_name'];
                $group_detail[$group][$ledger['group_name']] = array('group_dr_amount' => $group_dr_amount, 'group_cr_amount' => $group_cr_amount, 'group_opening_balance_total' => 0, 'group_closing_balance_total' => 0);
            }

            $group_detail[$group][$ledger['group_name']][] = array(
                'ladger_name' => $ledger['ladger_name'],
                'opening_balance' => $ledger['opening_balance'],
                'current_balance' => $ledger['current_balance'],
                'account' => $entry['account'],
                'balance' => $entry['balance'],
                'total_balance' => $this->getTotalBalanceBS($entries, $ledger['id'], $group),
                'total_dr_balance' => $total_dr_balance,
                'total_cr_balance' => $total_cr_balance
            );
        }

        if (!empty($ledger_op_balance)) {
            foreach ($ledger_op_balance AS $op) {
                if ($op['account_type'] == 'Dr') {
                    $total_dr_opening_balance += $op['opening_balance'];
                }
                if ($op['account_type'] == 'Cr') {
                    $total_cr_opening_balance += str_replace('-', '', $op['opening_balance']);
                }
            }
        }

        $total_opening_balance = $total_dr_opening_balance - $total_cr_opening_balance;

        foreach ($group_detail as $gkey => $groups) {
            foreach ($groups as $igkey => $innergroup) {
                foreach ($innergroup as $legKey => $leg) {
                    $group_detail[$gkey][$igkey]['group_closing_balance_total'] += $leg['total_balance'];
                }
            }
        }

        $liabilits = $this->sortingt_parent_group_array_bs($group_detail, 'Liabilities');
        $assets = $this->sortingt_parent_group_array_bs($group_detail, 'Assets');


        $data['liabilits'] = $liabilits;
        $data['assets'] = $assets;
        $data['opening_balance'] = $total_opening_balance;
        $data['opening_pl'] = $this->report->openingPL();

//        echo '<pre>';print_r($data['opening_pl']);die();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Reports');
        $this->layouts->render('admin/balance_sheet', $data, 'admin');
    }

    public function profit_loss() {
        $expenses_ordering_array = array(32, 29);
        $expenses_ordering = array();
        for ($k = 0; $k < count($expenses_ordering_array); $k++) {
            $result = $this->report->assetesOrdering($expenses_ordering_array[$k]);
            $expenses_ordering = array_merge($expenses_ordering, $result);
        }
        $data['expenses_ordering'] = $expenses_ordering;
        $data['expenses_indirect'] = $this->report->assetesOrdering(31);

        $income_ordering_array = array(37, 34);
        $income_ordering = array();
        for ($k = 0; $k < count($income_ordering_array); $k++) {
            $result = $this->report->assetesOrdering($income_ordering_array[$k]);
            $income_ordering = array_merge($income_ordering, $result);
        }
        $data['income_ordering'] = $income_ordering;
        $data['income_indirect'] = $this->report->assetesOrdering(36);
//        echo '<pre>';print_r($data['income_indirect']);die();

        $where = array(
            'ladger.status' => 1,
            'ladger.deleted' => 0
        );
        $ledgers = $this->report->trialBalance($where);
        $where = array(
            'ladger_account_detail.status' => 1,
            'ladger_account_detail.deleted' => 0
        );
        $entries = $this->report->allEntryPL($where);
        $ledger_gr = array();
        $ledger_grand = array();
        $entry['account'] = 0;
        $entry['balance'] = 0;
        foreach ($ledgers as $ledger) {
            $total_dr_amount = 0;
            $total_dr_balance = 0;
            $total_cr_balance = 0;
            $group_dr_amount = 0;
            $group_cr_amount = 0;
            $group_grand_debit_total = 0;
            foreach ($entries as $entry) {
                if ($ledger['group_id'] == $entry['group_id']) {
                    if ($entry['account'] == 'Dr') {
                        $group_dr_amount += $entry['balance'];
                    }
                    if ($entry['account'] == 'Cr') {
                        $group_cr_amount += $entry['balance'];
                    }
                }
                if ($ledger['ladger_name'] == $entry['ladger_name']) {
                    if ($entry['account'] == 'Dr') {
                        $total_dr_balance += $entry['balance'];
                    }
                    if ($entry['account'] == 'Cr') {
                        $total_cr_balance += $entry['balance'];
                    }
                }
            }
            $parent_category = array();
            $where = array(
                'group.status' => 1,
                'group.deleted' => 0,
                'group.id' => $ledger['group_id']
            );
            $group_name = $this->report->getGrandParentid($where);
            $group = '';
            if ($group_name[0]['parent_id'] != 0) {
                $this->gete_parent($group_name[0]['parent_id']);
                $parent_category = array_pop($this->store);
                $group = $parent_category[0]['group_name'];
            } else {
                $group = $group_name[0]['group_name'];
            }

            $group_grand_debit_total += $group_dr_amount;

            if (!in_array($ledger['group_name'], $ledger_gr)) {
                $ledger_gr[] = $ledger['group_name'];
                $group_detail[$group][$ledger['group_name']] = array('group_dr_amount' => $group_dr_amount, 'group_cr_amount' => $group_cr_amount, 'group_opening_balance_total' => 0, 'group_closing_balance_total' => 0);
            }

            $group_detail[$group][$ledger['group_name']][] = array(
                'ladger_name' => $ledger['ladger_name'],
                'opening_balance' => $ledger['opening_balance'],
                'current_balance' => $ledger['current_balance'],
                'account' => $entry['account'],
                'balance' => $entry['balance'],
                'total_balance' => $this->getTotalBalancePL($entries, $ledger['id'], $group),
                'total_dr_balance' => $total_dr_balance,
                'total_cr_balance' => $total_cr_balance
            );
        }


        foreach ($group_detail as $gkey => $groups) {
            foreach ($groups as $igkey => $innergroup) {
                foreach ($innergroup as $legKey => $leg) {
                    $group_detail[$gkey][$igkey]['group_closing_balance_total'] += $leg['total_balance'];
                }
            }
        }
        $income = $this->sortingt_parent_group_array_pl($group_detail, 'Income', $income_ordering);
        $expenses = $this->sortingt_parent_group_array_pl($group_detail, 'Expenses', $expenses_ordering);
        $data['income'] = $income;
        $data['expenses'] = $expenses;
//        $data['opening_pl'] = $this->report->openingPL();
//        echo '<pre>';print_r($data['opening_pl']);die();
        $this->load->model('admin/report');
        $data['get_standard_format_data'] = $this->report->getStandardFormatData();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Reports');
        $this->layouts->render('admin/profit_&-loss', $data, 'admin');
    }

    public function ajax_profit_loss_search_date() {
        if (isset($_POST) && ($_POST['ajax'] = TRUE)) {
            $from_date = $this->input->post('staring_day');
            $to_date = $this->input->post('ending_day');
            $data['staring_day'] = $from_date;
            $data['ending_day'] = $to_date;
            //=============
            $expenses_ordering_array = array(32, 29);
            $expenses_ordering = array();
            for ($k = 0; $k < count($expenses_ordering_array); $k++) {
                $result = $this->report->assetesOrdering($expenses_ordering_array[$k]);
                $expenses_ordering = array_merge($expenses_ordering, $result);
            }
            $data['expenses_ordering'] = $expenses_ordering;
            $data['expenses_indirect'] = $this->report->assetesOrdering(31);

            $income_ordering_array = array(37, 34);
            $income_ordering = array();
            for ($k = 0; $k < count($income_ordering_array); $k++) {
                $result = $this->report->assetesOrdering($income_ordering_array[$k]);
                $income_ordering = array_merge($income_ordering, $result);
            }
            $data['income_ordering'] = $income_ordering;
            $data['income_indirect'] = $this->report->assetesOrdering(36);
            //        echo '<pre>';print_r($data['income_indirect']);die();

            $where = array(
                'ladger.status' => 1,
                'ladger.deleted' => 0
            );
            $ledgers = $this->report->trialBalance($where);
            $where = array(
                'ladger_account_detail.status' => 1,
                'ladger_account_detail.deleted' => 0,
                'ladger_account_detail.create_date >=' => $from_date,
                'ladger_account_detail.create_date <=' => $to_date
            );
            $entries = $this->report->allEntryPL($where);
            $ledger_gr = array();
            $ledger_grand = array();
            $entry['account'] = 0;
            $entry['balance'] = 0;
            foreach ($ledgers as $ledger) {
                $total_dr_amount = 0;
                $total_dr_balance = 0;
                $total_cr_balance = 0;
                $group_dr_amount = 0;
                $group_cr_amount = 0;
                $group_grand_debit_total = 0;
                foreach ($entries as $entry) {
                    if ($ledger['group_id'] == $entry['group_id']) {
                        if ($entry['account'] == 'Dr') {
                            $group_dr_amount += $entry['balance'];
                        }
                        if ($entry['account'] == 'Cr') {
                            $group_cr_amount += $entry['balance'];
                        }
                    }
                    if ($ledger['ladger_name'] == $entry['ladger_name']) {
                        if ($entry['account'] == 'Dr') {
                            $total_dr_balance += $entry['balance'];
                        }
                        if ($entry['account'] == 'Cr') {
                            $total_cr_balance += $entry['balance'];
                        }
                    }
                }
                $parent_category = array();
                $where = array(
                    'group.status' => 1,
                    'group.deleted' => 0,
                    'group.id' => $ledger['group_id']
                );
                $group_name = $this->report->getGrandParentid($where);
                $group = '';
                if ($group_name[0]['parent_id'] != 0) {
                    $this->gete_parent($group_name[0]['parent_id']);
                    $parent_category = array_pop($this->store);
                    $group = $parent_category[0]['group_name'];
                } else {
                    $group = $group_name[0]['group_name'];
                }

                $group_grand_debit_total += $group_dr_amount;

                if (!in_array($ledger['group_name'], $ledger_gr)) {
                    $ledger_gr[] = $ledger['group_name'];
                    $group_detail[$group][$ledger['group_name']] = array('group_dr_amount' => $group_dr_amount, 'group_cr_amount' => $group_cr_amount, 'group_opening_balance_total' => 0, 'group_closing_balance_total' => 0);
                }

                $group_detail[$group][$ledger['group_name']][] = array(
                    'ladger_name' => $ledger['ladger_name'],
                    'opening_balance' => $ledger['opening_balance'],
                    'current_balance' => $ledger['current_balance'],
                    'account' => $entry['account'],
                    'balance' => $entry['balance'],
                    'total_balance' => $this->getTotalBalancePL($entries, $ledger['id'], $group),
                    'total_dr_balance' => $total_dr_balance,
                    'total_cr_balance' => $total_cr_balance
                );
            }


            foreach ($group_detail as $gkey => $groups) {
                foreach ($groups as $igkey => $innergroup) {
                    foreach ($innergroup as $legKey => $leg) {
                        $group_detail[$gkey][$igkey]['group_closing_balance_total'] += $leg['total_balance'];
                    }
                }
            }
            $income = $this->sortingt_parent_group_array_pl($group_detail, 'Income', $income_ordering);
            $expenses = $this->sortingt_parent_group_array_pl($group_detail, 'Expenses', $expenses_ordering);
            $data['income'] = $income;
            $data['expenses'] = $expenses;
            //=============
            echo $this->load->view('admin/ajax-profit-loss', $data, TRUE);
            exit;
        } else {
            echo "";
            exit;
        }
    }

    /*
      public function ledger_statements() {
      $entry_detail = array();
      $where = array(
      'ladger.status' => 1
      );
      $all_ledger = $this->report->getAllLedgerName($where);
      $ledgers = array();
      $ledgers[] = 'Select Ledger Name';
      foreach ($all_ledger as $value) {
      $ledgers[$value['id']] = $value['ladger_name'];
      }
      $data['ledgers'] = $ledgers;
      $data['all_entries'] = $entry_detail;
      $getsitename = getsitename();
      $this->layouts->set_title($getsitename . ' | All Transactions');
      $this->layouts->render('admin/ledger_statements', $data, 'admin');
      } */

    public function ledger_statements() {
         user_permission(148,'list');
        $data = array();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | All Transactions');
        $this->layouts->render('admin/ledger_list', $data, 'admin');
    }

//financial year
    public function get_financial_year() {
        $m = 4;
        $finance_arr = [];
        if ($m > date("m")) {
            $year = date("Y") - 1;
        } else {
            $year = date("Y");
        }
        for ($i = $m; $i <= 12; $i++) {
            $finance_arr[] = $year . '-' . $i;
        }
        for ($i = 1; $i < $m; $i++) {
            $finance_arr[] = ($year + 1) . '-' . $i;
        }
        return $finance_arr;
    }

    public function ledger_report() {
        $data = array();
        $financial_year = get_financial_year();
        $data['cur_financial_year'] = date('Y', strtotime(current($financial_year)));
        $finans_start_date = date("Y-m-d", strtotime(current($financial_year)));
        $from_date = date("Y-m-d", strtotime(current($financial_year)));
        $to_date = date("Y-m-t", strtotime(end($financial_year)));
        if (isset($_GET['starting_day']) && $_GET['starting_day'] != '' && isset($_GET['ending_day']) && $_GET['ending_day'] != '') {

            $_GET['starting_day'] = str_replace('/', '-', $_GET['starting_day']);
            $_GET['ending_day'] = str_replace('/', '-', $_GET['ending_day']);

            $from_date = date('Y-m-d', strtotime($_GET['starting_day'])); // somnath - date converted to sql format for query compare
            $to_date = date('Y-m-d', strtotime($_GET['ending_day'])); // somnath - date converted to sql format for query compare
        }


        

        $data['starting_day'] = $from_date;
        $data['ending_day'] = $to_date;
        $ledger_name = $this->input->get('ledger_name');
        $ledgerIdArr = $this->report->getledgerId($ledger_name);
        $ledger_id = $ledgerIdArr['id'];

        /*
         * If ledger report is showing full financial year, then it will take the very first opening balance
         * otherwise it search the immediate previous date's closing cost 
         * which will became opening cost for now
         * somnath - 09/02/2018
         */ 
        if ($finans_start_date == $from_date) {
            $data['opening_bal'] = $this->report->getOpeningBalanceById($ledger_id);
        } else {
            $opening_bal = $this->report->getOpeningBalanceForLedgerByDate($ledger_id, $finans_start_date, $from_date);
            if(empty($opening_bal['account_type'])) {
                $data['opening_bal'] = $this->report->getOpeningBalanceById($ledger_id);
            }else{
               $data['opening_bal'] = $opening_bal; 
            }
        }
        // $data['opening_bal'] = $this->report->getOpeningBalanceById($ledger_id);
        // $data['ledger_result'] = $this->report->getledgerDetailsByIdByDate($ledger_id, $from_date, $to_date);
        $ledger_result = $this->report->getledgerDetailsByIdByDate($ledger_id, $from_date, $to_date);
        foreach ($ledger_result as $key => $value) {
            $ledger_result[$key]['details'] = $this->report->getTransactionDetailsByEntry($value['id']);
        }
        // echo "<pre>";print_r($ledger_result);exit();
        $data['ledger_result'] = $ledger_result;
        $data['company_details'] = $this->report->getCompanyDetails();
        $data['number_of_branch'] = $this->report->getNumberOfBranch();
        $data['invoice'] = array(5,6,7,8,9,10,12,14);
        $getsitename = getsitename();
        $this->load->model('admin/report');
        $data['get_standard_format_data'] = $this->report->getStandardFormatData();
        $this->layouts->set_title($getsitename . ' | All Transactions');
        $this->layouts->render('admin/ledger_report', $data, 'admin');
    }

    public function ajax_ledger_statements() {
        $ledger_id = $_POST['ledger_id'];
        $data = array();
        $data['opening_bal'] = $this->report->getOpeningBalanceById($ledger_id);
        $data['ledger_result'] = $this->report->getledgerDetailsById($ledger_id);

        $res['status'] = 0;
        $res['html'] = $this->load->view('admin/ajax_statements', $data, true);
        echo json_encode($res);
        exit;
    }

    public function ajax_ledger_statements_by_date() {

        $ledger_id = $_POST['ledger_id'];
        $from_dateOld = str_replace("/", "-", $_POST['from_date']);
        $to_dateOld = str_replace("/", "-", $_POST['to_date']);
        $from_date = date('Y-m-d', strtotime($from_dateOld));

        if (!empty($_POST['to_date'])) {
            $to_date = date('Y-m-d', strtotime($to_dateOld));
        }
        if (empty($_POST['to_date'])) {
            $to_date = date('Y-m-d', strtotime('2050-12-31'));
        }

        if (!empty($from_dateOld)) {
            $before_selected_date = date('Y-m-d', strtotime($from_dateOld));
        }
        if (empty($from_dateOld)) {
            $before_selected_date = '';
        }
//echo $from_date;
//echo $to_date;exit;
        //Start: Get Opening Balance In Ledger //
        $where = array(
            //'ladger.user_id' => $this->session->userdata('user_id'),
            //'ladger.company_id' => 1,
            'ladger.status' => 1,
            'ladger.id' => $ledger_id,
        );
        $ledger_balance = $this->report->getOpeningBalance($where);
        unset($where);
//        echo '<pre>';
//        print_r($ledger_balance);
        //End: Get Opening Balance In Ledger //
        //Start: Get All Entry Befour Selecting Date //
        $where = array(
            'entry.user_id' => $this->session->userdata('user_id'),
            'entry.company_id' => 1,
            'entry.create_date <' => $before_selected_date,
            'entry.status' => 1,
            'entry.deleted' => 0,
            'ladger_account_detail.status' => 1,
            'ladger_account_detail.deleted' => 0,
            'ladger.id' => $ledger_id,
        );
        $entry_detail_before_date = $this->report->allTransaction($where);
        //Start: Get All Entry Befour Selecting Date //
        //Start: CalColate Opening Balance//
        $opening_balance1 = 0;

        if (count($entry_detail_before_date) > 0) {
            $final_previous_balance = 0;
            $all_debit = 0;
            $all_cradit = 0;
            foreach ($entry_detail_before_date as $value) {
                if ($value['account'] == 'Dr') {
                    $all_debit += $value['balance'];
                }
                if ($value['account'] == 'Cr') {
                    $all_cradit += $value['balance'];
                }
            }
            $final_previous_balance = ($all_debit - $all_cradit);
            $opening_balance1 = $final_previous_balance + $ledger_balance[0]['opening_balance'];
        } else {
            $opening_balance1 = $ledger_balance[0]['opening_balance'];
        }
        //if($opening_balance1<0){
        // $opening_balance=substr($opening_balance1,1);
        // }
        //else{
        $opening_balance = $opening_balance1;
        // }
        // echo  $opening_balance;exit;
        //Start: CalColate Opening Balance//
//        echo '<pre>';
//        print_r($entry_detail_before_date);
        $where = array(
            'entry.user_id' => $this->session->userdata('user_id'),
            'entry.company_id' => 1,
            'entry.create_date >=' => $from_date,
            'entry.create_date <=' => $to_date,
            'entry.status' => 1,
            'entry.deleted' => 0,
            'ladger_account_detail.status' => 1,
            'ladger_account_detail.deleted' => 0,
            'ladger.id' => $ledger_id,
        );
        $entry_detail = $this->report->allTransaction($where);
//        echo '<pre>';
//        print_r($entry_detail);
//        exit;
        unset($where);
        if (count($entry_detail) > 0) {
            $opening_balance = $opening_balance;
            $TotalDebitClosingBalance = 0;
            $TotalCraditClosingBalance = 0;
            $lastClosingBalance = $opening_balance;
            $totalDebitBalance = 0;
            $totalCreditBalance = 0;
            $setDr = 0;
            $setCr = 0;
            $DrBal = 0;
            $CrBal = 0;
            $Difference = 0;
            $setCurrentBalance = 0;
            $opening_debit_balance = 0;
            $opening_credit_balance = 0;
            if ($opening_balance > 0 || $opening_balance == 0) {
                $odb = $opening_balance;
                $opening_debit_balance = number_format($opening_balance, 2);
            } else {
                $odb = 0;
                $opening_debit_balance = '&nbsp';
            }
            if ($opening_balance < 0) {
                $ocd1 = $opening_balance;
                if ($ocd1 < 0) {
                    $ocd = substr($ocd1, 1);
                } else {
                    $ocd = $ocd1;
                }
                $opening_credit_balance = number_format(substr($opening_balance, 1), 2);
            } else {
                $ocd = 0;
                $opening_credit_balance = '&nbsp';
            }
            $tableString = '';
            $tableString .= '<tr>
                                <td style="border-right: none"></td>
                                <td style="border-right: none">&nbsp</td>
                                <td style="border-right: none"><strong>Opening balance</strong></td>
                                <td>&nbsp</td>
                                <td style="text-align: right;">' . $opening_debit_balance . '</td>
                                <td style="text-align: right;">' . $opening_credit_balance . '</td>
                                <td>&nbsp</td>
                                </tr>';
            foreach ($entry_detail as $entry) {
                $ledger = json_decode($entry['ledger_ids_by_accounts']);
                $ledger_name = '';
                $drAmount = '';
                $crAmount = '';
                $currentBalance = 0;
                if ($entry['account'] == 'Dr') {
                    $ledgerName = $ledger->Cr[0];
                }
                if ($entry['account'] == 'Cr') {
                    $ledgerName = $ledger->Dr[0];
                }
                if ($entry['account'] == 'Dr') {
                    $drAmount = number_format($entry['dr_amount'], 2);
                }

                if ($entry['account'] == 'Cr') {
                    $crAmount = number_format($entry['cr_amount'], 2);
                }
                if ($entry['account'] == 'Dr') {
                    $currentBalance = $lastClosingBalance + $entry['balance'];
                }
                if ($entry['account'] == 'Cr') {
                    $currentBalance = $lastClosingBalance - $entry['balance'];
                }
                $accountType = '';
                if ($currentBalance > 0) {
                    $accountType = '(Dr)';
                } else {
                    $accountType = '(Cr)';
                }
                if ($currentBalance < 0) {
                    $setCurrentBalance = number_format(substr($currentBalance, 1), 2);
                } else {
                    $setCurrentBalance = number_format($currentBalance, 2);
                }
                $tableString .= '<tr>
                <td>' . date('m / d/ Y', strtotime($entry['create_date'])) . '</td>
                <td>' . $entry['entry_no'] . '</td>
                <td>' . $ledgerName . '</td>
                <td>' . $entry['type'] . '</td>
                <td style = "text-align: right;">' . $drAmount . '</td>
                <td style = "text-align: right;">' . $crAmount . '</td>
                <td style = "text-align: right;">' . $setCurrentBalance . '&nbsp;&nbsp' . $accountType . '</td>
                </tr >';
                $lastClosingBalance = $currentBalance;
                if ($entry['account'] == 'Dr') {
                    $setDr = $totalDebitBalance + $entry['dr_amount'];
                }
                if ($entry['account'] == 'Cr') {
                    $setCr = $totalCreditBalance + $entry['cr_amount'];
                }
                $totalDebitBalance = $setDr;
                $totalCreditBalance = $setCr;
                if ($setDr < 0) {
                    $untilDebit = $odb + $setDr;
                    $DrBal = substr($untilDebit, 1);
                } else {
                    $untilDebit = $odb + $setDr;
                    $DrBal = $untilDebit;
                }
                if ($setCr < 0) {
                    $untilCredit = $ocd + $setCr;
                    $CrBal = substr($untilCredit, 1);
                } else {
                    $untilCredit = $ocd + $setCr;
                    $CrBal = $untilCredit;
                }
                //echo $totalCreditBalance + $ocd;exit;
                $Difference = ($totalDebitBalance + $odb) - ($totalCreditBalance + $ocd);
            }

            $debitClosingBalance = 0;
            $craditClosingBalance = 0;
            if ($Difference < 0) {
                $debitClosingBalance = $Difference;
            } else {
                $craditClosingBalance = $Difference;
            }

            $tableString .= '<tr>
                            <th style="border-right: none;">Total</th>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <th style="border-right: none; text-align: right;">' . number_format($DrBal, 2) . '</th>
                            <th style="border-right: none; text-align: right;">' . number_format($CrBal, 2) . '</th>
                            <th style="border-right: none;">&nbsp;</th>
                            </tr>';
            if ($debitClosingBalance < 0) {
                $TotalDebitClosingBalance = substr($debitClosingBalance, 1);
            } else {
                $TotalDebitClosingBalance = $debitClosingBalance;
            }
            if ($craditClosingBalance < 0) {
                $TotalCraditClosingBalance = substr($craditClosingBalance, 1);
            } else {
                $TotalCraditClosingBalance = $craditClosingBalance;
            }
            $tableString .= '<tr>
                            <th style="border-right: none;">Closing Balance</th>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <th style="border-right: none; text-align: right;">' . number_format($TotalDebitClosingBalance, 2) . '</th>
                            <th style="border-right: none; text-align: right;">' . number_format($TotalCraditClosingBalance, 2) . '</th>
                            <td style="border-right: none;">&nbsp;</td>
                            </tr>';
            $ledgerTotalDebit = 0;
            $ledgerTotalCradit = 0;
            if ($totalDebitBalance < 0) {
                $TDB = substr($totalDebitBalance, 1);
            } else {
                $TDB = $totalDebitBalance;
            }
            if ($debitClosingBalance < 0) {
                $DCB = substr($debitClosingBalance, 1);
            } else {
                $DCB = $debitClosingBalance;
            }
            if ($totalCreditBalance < 0) {
                $TCB = substr($totalCreditBalance, 1);
            } else {
                $TCB = $totalCreditBalance;
            }
            if ($craditClosingBalance < 0) {
                $CCB = substr($craditClosingBalance);
            } else {
                $CCB = $craditClosingBalance;
            }
            $xrt = $TDB + $DCB;
            $yxt = $CrBal + $CCB;
            //echo $TCB.'/'.$CCB.'<br>';exit;
            if ($xrt < 0) {
                $deb = $odb + $xrt;
                $ledgerTotalDebit = substr($deb, 1);
            } else {
                $deb = $odb + $xrt;
                $ledgerTotalDebit = $deb;
            }
            if ($yxt < 0) {
                $ledgerTotalCradit = substr($yxt, 1);
            } else {
                $ledgerTotalCradit = $yxt;
            }
            $tableString .= '<tr>
                            <th style="border-right: none;">&nbsp</th>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <th style="border-right: none; text-align: right;">' . number_format($ledgerTotalDebit, 2) . '</th>
                            <th style="border-right: none; text-align: right;">' . number_format($ledgerTotalCradit, 2) . '</th>
                            <td style="border-right: none;">&nbsp;</td>
                            </tr>';
        } else {
            $where = array(
                'entry.user_id' => $this->session->userdata('user_id'),
                'entry.company_id' => 1,
                'entry.create_date <=' => $from_date,
                'entry.status' => 1,
                'entry.deleted' => 0,
                'ladger_account_detail.status' => 1,
                'ladger_account_detail.deleted' => 0,
                'ladger.id' => $ledger_id,
            );
            $previous_entry_detail = $this->report->getLastOpeningBalance($where);
            unset($where);
            if (count($previous_entry_detail) > 0) {
                $previous_debit_balance = 0;
                $previous_credit_balance1 = 0;
                $pdb = 0;
                $pcb = 0;
                if ($previous_entry_detail[0]['current_closing_balance'] > 0) {
                    $pdb = $previous_entry_detail[0]['current_closing_balance'];
                    $previous_debit_balance = number_format($pdb, 2);
                } else {
                    $previous_debit_balance = '&nbsp';
                }
                if ($previous_entry_detail[0]['current_closing_balance'] < 0) {
                    $pcb = $previous_entry_detail[0]['current_closing_balance'];
                    $previous_credit_balance1 = number_format($pcb, 2);
                } else {
                    $previous_credit_balance1 = '&nbsp';
                }
                if ($previous_credit_balance1 < 0) {
                    $previous_credit_balance = substr($previous_credit_balance1, 1);
                } else {
                    $previous_credit_balance = $previous_credit_balance1;
                }
                $totaldebitValue1 = $pcb + $pdb;
                if ($totaldebitValue1 < 0) {
                    $totaldebitValue = substr($totaldebitValue1, 1);
                } else {
                    $totaldebitValue = $totaldebitValue1;
                }


                $totalcreditValue1 = $pcb + $pdb;
                if ($totalcreditValue1 < 0) {
                    $totalcreditValue = substr($totalcreditValue1, 1);
                } else {
                    $totalcreditValue = $totalcreditValue1;
                }

                $tableString = '';
                $tableString .= '<tr>
                                <td style="border-right: none"><strong>' . date('d / m / Y', strtotime($previous_entry_detail[0]['create_date'])) . '</strong></td>
                                <td style="border-right: none">&nbsp</td>
                                <td style="border-right: none"><strong>Opening balance</strong></td>
                                <td>&nbsp</td>
                                <td style="text-align: right;">' . $previous_debit_balance . '</td>
                                <td style="text-align: right;">' . $previous_credit_balance . '</td>
                                <td>&nbsp</td>
                                </tr>';
                foreach ($previous_entry_detail as $previous_entry) {
                    $tableString .= '<tr><td colspan="7" style="text-align: center;">No Transuction Occord</td></tr>';
                }
                $tableString .= '<tr>
                            <th style="border-right: none;">Total</th>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <th style="border-right: none; text-align: right;">' . $previous_debit_balance . '</th>
                            <th style="border-right: none; text-align: right;">' . $previous_credit_balance . '</th>
                            <th style="border-right: none;">&nbsp;</th>
                            </tr>';

                $tableString .= '<tr>
                            <th style="border-right: none;">Closing Balance</th>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <th style="border-right: none; text-align: right;">' . $previous_credit_balance . '</th>
                            <th style="border-right: none; text-align: right;">' . $previous_debit_balance . '</th>
                            <td style="border-right: none;">&nbsp;</td>
                            </tr>';
                $tableString .= '<tr>
                            <th style="border-right: none;">&nbsp</th>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <th style="border-right: none; text-align: right;">' . number_format(($totaldebitValue), 2) . '</th>
                            <th style="border-right: none; text-align: right;">' . number_format(($totalcreditValue), 2) . '</th>
                            <td style="border-right: none;">&nbsp;</td>
                            </tr>';
            } else {
                $where = array(
                    //'ladger.user_id' => $this->session->userdata('user_id'),
                    //'ladger.company_id' => 1,
                    'ladger.status' => 1,
                    'ladger.id' => $ledger_id,
                );
                $ledger_balance = $this->report->getOpeningBalance($where);
                if (count($ledger_balance) > 0) {
                    $previous_debit_balance = 0;
                    $previous_credit_balance = 0;
                    $pdb = 0;
                    $pcb = 0;
                    if ($ledger_balance[0]['opening_balance'] > 0) {
                        $pdb = $ledger_balance[0]['opening_balance'];
                        $previous_debit_balance = number_format($pdb, 2);
                    } else {
                        $previous_debit_balance = '&nbsp';
                    }
                    if ($ledger_balance[0]['opening_balance'] < 0) {
                        $pcb = $ledger_balance[0]['opening_balance'];
                        $previous_credit_balance = number_format(substr($pcb, 1), 2);
                    } else {
                        $previous_credit_balance = number_format($pdb, 2);
                    }
                    $tableString = '';
                    $tableString .= '<tr>
                                <td style="border-right: none"><strong>' . date('d / m / Y', strtotime($ledger_balance[0]['created_date'])) . '</strong></td>
                                <td style="border-right: none">&nbsp</td>
                                <td style="border-right: none"><strong>Opening balance</strong></td>
                                <td>&nbsp</td>
                                <td style="text-align: right;">' . $previous_debit_balance . '</td>
                                <td style="text-align: right;">' . $previous_credit_balance . '</td>
                                <td>&nbsp</td>
                                </tr>';
                    foreach ($ledger_balance as $balance) {
                        $tableString .= '<tr><td colspan="7" style="text-align: center;">No Transuction Occord</td></tr>';
                    }
                    $tableString .= '<tr>
                            <th style="border-right: none;">Total</th>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <th style="border-right: none; text-align: right;">' . $previous_debit_balance . '</th>
                            <th style="border-right: none; text-align: right;">' . $previous_credit_balance . '</th>
                            <th style="border-right: none;">&nbsp;</th>
                            </tr>';

                    $tableString .= '<tr>
                            <th style="border-right: none;">Closing Balance</th>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <th style="border-right: none; text-align: right;">' . $previous_credit_balance . '</th>
                            <th style="border-right: none; text-align: right;">' . $previous_debit_balance . '</th>
                            <td style="border-right: none;">&nbsp;</td>
                            </tr>';
                    $tableString .= '<tr>
                            <th style="border-right: none;">&nbsp</th>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <td style="border-right: none;">&nbsp;</td>
                            <th style="border-right: none; text-align: right;">' . number_format(substr(($pcb + $pdb), 1), 2) . '</th>
                            <th style="border-right: none; text-align: right;">' . number_format(substr(($pcb + $pdb), 1), 2) . '</th>
                            <td style="border-right: none;">&nbsp;</td>
                            </tr>';
                }
            }
            //$tableString = '<tr><td colspan="7" style="text-align: center;">Data not found</td></tr>';
        }
        //echo json_encode($entry_detail);
        echo $tableString;
        exit;
    }

    public function sortingt_parent_group_array($arr, $searchKey) {
        $final_array = array();
        $group_total = 0;
        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $key => $value) {
                if ($key == $searchKey) {
                    foreach ($value as $val) {
                        foreach ($val as $sub) {
                            if ($sub['current_balance'] < 0) {
                                $cb = substr($sub['current_balance'], 1);
//                                $cb = $sub['current_balance'];
                                $group_total += $cb;
                            } else {
                                $group_total += $sub['current_balance'];
                            }
                        }
                    }
                    $final_array[] = $value;
                }
            }
            $final_array[] = $group_total;
        }
        return $final_array;
    }

    public function sortingt_parent_group_array_new($arr, $searchKey) {
        $final_array = array();
        $group_total = 0;
        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $key => $value) {
                if ($key == $searchKey) {
                    foreach ($value as $val) {
                        foreach ($val as $sub) {
                            if ($sub['current_balance'] < 0) {
//                                $cb = substr($sub['current_balance'], 1);
                                $cb = $sub['current_balance'];
                                $group_total += $cb;
                            } else {
                                $group_total += $sub['current_balance'];
                            }
                        }
                    }
                    $final_array[] = $value;
                }
            }
            $final_array[] = $group_total;
        }
        return $final_array;
    }

    public function balance_sheet_bydate() {

        // echo "<pre>";print_r($_POST);exit;
        // $from_date = date('Y-m-d', strtotime($_POST['from_date']));
        // $to_date = date('Y-m-d', strtotime($_POST['to_date']));

        $from_dateOld = str_replace("/", "-", $_POST['from_date']);
        $to_dateOld = str_replace("/", "-", $_POST['to_date']);

        $from_date = date('Y-m-d', strtotime($from_dateOld));

        if (!empty($to_dateOld)) {
            $to_date = date('Y-m-d', strtotime($to_dateOld));
        }

        if (empty($to_dateOld)) {
            $to_date = date('Y-m-d', strtotime('2050-12-31'));
        }


//       $where = array(
//            'ladger_account_detail.status' => 1,
//            'ladger_account_detail.deleted' => 0
//        );
//        $where = array(
//            'ladger.status' => 1,
//            'ladger.deleted' => 0
//        );
        $where = array(
            'ladger.status' => 1
        );
        $ledgers = $this->report->trialBalance($where);
//        echo '<pre>';print_r($ledgers);exit;
        $where = array(
            'ladger_account_detail.status' => 1,
            'ladger_account_detail.create_date >=' => $from_date,
            'ladger_account_detail.create_date <=' => $to_date,
            'ladger_account_detail.deleted' => 0
        );
        $entries = $this->report->allEntry($where);
        //$entries = $this->report->allEntrySelectByDate($where);
//         echo '<pre>';print_r($entries);exit;
        $ledger_gr = array();
        $ledger_grand = array();
        $group_detail = array();
        if (count($ledgers) > 0 && count($entries) > 0) {
            foreach ($ledgers as $ledger) {
                $total_dr_amount = 0;
                $total_dr_balance = 0;
                $total_cr_balance = 0;
                $group_dr_amount = 0;
                $group_cr_amount = 0;
                $group_grand_debit_total = 0;
                foreach ($entries as $entry) {
                    if ($ledger['group_id'] == $entry['group_id']) {
                        if ($entry['account'] == 'Dr') {
                            $group_dr_amount += $entry['balance'];
                        }
                        if ($entry['account'] == 'Cr') {
                            $group_cr_amount += $entry['balance'];
                        }
                    }
                    if ($ledger['ladger_name'] == $entry['ladger_name']) {
                        if ($entry['account'] == 'Dr') {
                            $total_dr_balance += $entry['balance'];
                        }
                        if ($entry['account'] == 'Cr') {
                            $total_cr_balance += $entry['balance'];
                        }
                    }
                }

                $where = array(
                    //'group.user_id' => $this->session->userdata('user_id'),
                    //'group.company_id' => 1,
                    'group.status' => 1,
                    'group.deleted' => 0,
                    'group.id' => $ledger['group_id']
                );
                $group_name = $this->report->getGrandParentid($where);
                $group = '';
                if ($group_name[0]['parent_id'] != 0 &&
                        $group_name[0]['user_id'] == $this->session->userdata('user_id') &&
                        $group_name[0]['company_id'] == 1) {
                    $where = array(
                        //'group.user_id' => $this->session->userdata('user_id'),
                        //'group.company_id' => 1,
                        'group.status' => 1,
                        'group.deleted' => 0,
                        'group.id' => $group_name[0]['parent_id']
                    );

                    $parent_group_name = $this->report->getGrandParentid($where);
                    $group = $parent_group_name[0]['group_name'];
                } else {
                    $group = $group_name[0]['group_name'];
                }

                $group_grand_debit_total += $group_dr_amount;

                if (!in_array($ledger['group_name'], $ledger_gr)) {
                    $ledger_gr[] = $ledger['group_name'];
                    $group_detail[$group][$ledger['group_name']] = array('group_dr_amount' => $group_dr_amount, 'group_cr_amount' => $group_cr_amount, 'group_opening_balance_total' => 0, 'group_closing_balance_total' => 0);
                }

                $group_detail[$group][$ledger['group_name']][] = array(
                    'ladger_name' => $ledger['ladger_name'],
                    'opening_balance' => $ledger['opening_balance'],
                    'current_balance' => $ledger['current_balance'],
                    'account' => $entry['account'],
                    'balance' => $entry['balance'],
                    'total_dr_balance' => $total_dr_balance,
                    'total_cr_balance' => $total_cr_balance
                );
            }
        }

//         echo "<pre>";print_r($group_detail);exit;

        foreach ($group_detail as $gkey => $groups) {
            foreach ($groups as $igkey => $innergroup) {
                foreach ($innergroup as $legKey => $leg) {
                    // $group_detail[$gkey][$igkey]['group_opening_balance_total'] += abs($leg['opening_balance']);
                    $group_detail[$gkey][$igkey]['group_closing_balance_total'] += $leg['current_balance'];
//                    $group_detail[$gkey][$igkey]['group_closing_balance_total'] += abs($leg['current_balance']);
                }
            }
        }

//         echo "<pre>";print_r($group_detail);exit;

        $liabilits = $this->sortingt_parent_group_array_new($group_detail, 'Liabilities');
        $assets = $this->sortingt_parent_group_array_new($group_detail, 'Assets');
        $data['liabilits'] = $liabilits;
        $data['assets'] = $assets;

        //Create Balance Sheet Taole
        //set total income, expenses, net loss, net profit variable
        $libility_grand_total = 0;
        $assets_grand_total = 0;
        $net_profit = '';
        $net_loss = '';
        $assets_total = 0;
        $liabilits_total = 0;
        if (count($liabilits) > 0) {
            if ($liabilits[1] < 0) {
                $liabilits_total = $liabilits[1];
//                $liabilits_total = substr($liabilits[1], 1);
            } else {
                $liabilits_total = $liabilits[1];
            }
        }
        if (count($assets) > 0) {
            if ($assets[1] < 0) {
                $assets_total = $assets[1];
//                $assets_total = substr($assets[1], 1);
            } else {
                $assets_total = $assets[1];
            }
        }
//        echo $assets_total.'L'.$liabilits_total;die();
        if ($assets_total > $liabilits_total) {
            $profit = (abs($assets_total) - abs($liabilits_total));
            $net_profit = '<tr><td>Net Profit</td><td style="text-align: right;">' . number_format(($profit), 2) . '</td></tr>';
            $assets_grand_total = number_format($assets_total, 2);
            $libility_grand_total = number_format(($liabilits_total + $profit), 2);
        }
        if ($assets_total < $liabilits_total) {
            $loss = (abs($liabilits_total) - abs($assets_total));
            $net_loss = '<tr><td>Net Loss</td><td style="text-align: right;">' . number_format(substr($loss, 1), 2) . '</td></tr>';
            $assets_grand_total = number_format((abs($assets_total) + $loss), 2);
            $libility_grand_total = number_format($liabilits_total, 2);
        }
        if (count($liabilits) > 0 && count($assets) > 0) {
            $balance_sheet = '<tr>
                            <td width = 50%>
                                <!--Start:Table For Liabilities-->
                                <table width = "100%">
                                    <tbody>';
            if (count($liabilits) > 0) {
                $group_closing_balance = 0;
                foreach ($liabilits[0] as $key => $value) {
                    $balance_sheet.= '<tr>
                                                    <td><strong>' . $key . '</strong></td>
                                                    <td style="text-align: right;">
                                                        <strong>';
                    // $group_closing_balance = number_format($value['group_dr_amount'] - $value['group_cr_amount'], 2);
                    $group_closing_balance = number_format($value['group_closing_balance_total'], 2);
                    if ($group_closing_balance < 0) {
                        $balance_sheet.= $group_closing_balance;
//                        $balance_sheet.= substr($group_closing_balance, 1);
                    } else {
                        $balance_sheet.= $group_closing_balance;
                    }
                    $balance_sheet.= '</strong>
                                                    </td>
                                                </tr>';
                    if (array_key_exists('group_dr_amount', $value)) {
                        unset($value['group_dr_amount']);
                        unset($value['group_cr_amount']);
                    }
                    foreach ($value as $val) {
                        if ($val['ladger_name'] != "") {
                            $balance_sheet.= '<tr>
                                                            <td style="padding-left: 20px;">' . $val['ladger_name'] . '</td>
                                                            <td style="text-align: left;">';
                            if ($val['current_balance'] < 0) {
                                $balance_sheet.= '(-) ';
//                                $balance_sheet.= number_format($val['current_balance'], 2);
                                $balance_sheet.= number_format(substr($val['current_balance'], 1), 2);
                            } else {
                                $balance_sheet.= number_format($val['current_balance'], 2);
                            }
                            $balance_sheet.='</td>
                                                        </tr>';
                        }
                    }
                }
            }
            $balance_sheet.= $net_profit
                    . '</tbody>
                                </table>
                                <!--End:Table For Liabilities-->
                            </td>
                            <td width = 50%>
                                <!--Start:Table For Assets-->
                                <table width = "100%">
                                    <tbody>';
            if (count($assets) > 0) {
                $group_closing_balance = 0;
                foreach ($assets[0] as $key => $value) {
                    $balance_sheet.= '<tr>
                                                    <td><strong>' . $key . '</strong></td>
                                                    <td style="text-align: right;">
                                                        <strong>';
                    // $group_closing_balance = number_format($value['group_dr_amount'] - $value['group_cr_amount'], 2);
                    $group_closing_balance = number_format($value['group_closing_balance_total'], 2);
                    if ($group_closing_balance < 0) {
                        $balance_sheet.= '(-)';
//                        $balance_sheet.= $group_closing_balance;
                        $balance_sheet.= substr($group_closing_balance, 1);
                    } else {
                        $balance_sheet.= $group_closing_balance;
                    }
                    $balance_sheet.='</strong>
                                                    </td>
                                                </tr>';
                    if (array_key_exists('group_dr_amount', $value)) {
                        unset($value['group_dr_amount']);
                        unset($value['group_cr_amount']);
                    }
                    foreach ($value as $val) {
                        if ($val['ladger_name'] != "") {
                            $balance_sheet.='<tr>
                                            <td style="padding-left: 20px;">' . $val['ladger_name'] . '</td>
                                            <td style="text-align: left;">';
                            if ($val['current_balance'] < 0) {
                                $balance_sheet.= '(-)';
//                                $balance_sheet.= number_format($val['current_balance'], 2);
                                $balance_sheet.= number_format(substr($val['current_balance'], 1), 2);
                            } else {
                                $balance_sheet.= number_format($val['current_balance'], 2);
                            }
                            $balance_sheet.='</td>
                                            </tr>';
                        }
                    }
                }
            }
            $balance_sheet.= $net_loss;
            $balance_sheet.='</tbody>
                </table>
                <!--End:Table For Assets-->
            </td>
        </tr><tr>
                <td>
                    <strong>
                        <div style="width: 50%; float: left; text-align: left;">Total</div>
                        <div style="width: 50%; float: left; text-align: right;">' . $libility_grand_total . '</div>
                    </strong>
                </td>
                <td>
                    <strong>
                        <div style="width: 50%; float: left; text-align: left;">Total</div>
                        <div style="width: 50%; float: left; text-align: right;">' . $assets_grand_total . '</div>
                    </strong>
                </td>
             </tr>';
        } else {
            $balance_sheet = '<tr><td colspan="2" style="text-align: center;">Data not found</td></tr>';
        }
        echo $balance_sheet;
        exit;
    }

    public function sortingt_parent_group_array_pl($arr, $searchKey, $income_ordering) {
        $final_array = array();
        $group_total = 0;
        $gross_total = 0;
        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $key => $value) {
                if ($key == $searchKey) {
                    foreach ($value as $group => $val) {
                        foreach ($val as $sub) {
                            if ($sub['total_balance'] < 0) {
                                $cb = $sub['total_balance']; //15092916
                                //$cb = substr($sub['total_balance'], 1);
                                $group_total += $cb;
                                foreach ($income_ordering AS $ord) {
                                    if ($ord['group_name'] == $group) {
                                        $gross_total += $cb;
                                    }
                                }
                            } else {
                                $group_total += $sub['total_balance'];
                                foreach ($income_ordering AS $ord) {
                                    if ($ord['group_name'] == $group) {
                                        $gross_total += $sub['total_balance'];
                                    }
                                }
                            }
                        }
                    }
                    $final_array[] = $value;
                }
            }
            $final_array[] = $group_total;
            $final_array[] = $gross_total;
        }
        return $final_array;
    }

    public function sortingt_parent_group_array_bs($arr, $searchKey) {
        $final_array = array();
        $group_total = 0;
        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $key => $value) {
                if ($key == $searchKey) {
                    foreach ($value as $val) {
                        foreach ($val as $sub) {
                            if ($sub['total_balance'] < 0) {
                                $cb = $sub['total_balance']; //15092916
                                //$cb = substr($sub['total_balance'], 1);
                                $group_total += $cb;
                            } else {
                                $group_total += $sub['total_balance'];
                            }
                        }
                    }
                    $final_array[] = $value;
                }
            }
            $final_array[] = $group_total;
        }
        return $final_array;
    }

    public function getTotalBalancePL($entries, $ladger_id, $group_name) {
        $totalBalance = 0;
        foreach ($entries AS $entry) {
            if ($ladger_id == $entry['ladger_id']) {
                if ($group_name == 'Assets') {
                    $totalBalance = $entry['balance_dr'] - str_replace('-', '', $entry['balance_cr']);
                    if ($entry['op_account'] == 'Dr') {
                        $totalBalance = $totalBalance + $entry['op_balance'];
                    } else {
                        $totalBalance = $totalBalance - str_replace('-', '', $entry['op_balance']);
                    }
                } else {

                    $totalBalance = str_replace('-', '', $entry['balance_cr']) - $entry['balance_dr'];
                    if ($entry['op_account'] == 'Cr') {
                        $totalBalance = $totalBalance + str_replace('-', '', $entry['op_balance']);
                    } else {
                        $totalBalance = $totalBalance - $entry['op_balance'];
                    }
                }
            }
        }

        return $totalBalance;
    }

//    public function getTotalBalancePL($entries, $ladger_id,$group) {
//        $totalBalance = 0;
//        foreach ($entries AS $entry) {
//            if ($ladger_id == $entry['ladger_id']) {
//                $totalBalance = $entry['total_balance'];
//                // $totalBalance = $entry['total_balance']+ $entry['opening_balance'];
//            }
//        }
//
//        return $totalBalance;
//    }

    public function profit_loss_search_by_date() {

        $from_dateOld = str_replace("/", "-", $_POST['from_date']);
        $to_dateOld = str_replace("/", "-", $_POST['to_date']);

        $from_date = date('Y-m-d', strtotime($from_dateOld));

        if (!empty($to_dateOld)) {
            $to_date = date('Y-m-d', strtotime($to_dateOld));
        }

        if (empty($to_dateOld)) {
            $to_date = date('Y-m-d', strtotime('2050-12-31'));
        }
        $where = array(
            'ladger.status' => 1
        );
        $ledgers = $this->report->trialBalance($where);

        unset($where);
        $where = array(
            'ladger_account_detail.status' => 1,
            'ladger_account_detail.create_date >=' => $from_date,
            'ladger_account_detail.create_date <=' => $to_date,
            'ladger_account_detail.deleted' => 0
        );
        $entries = $this->report->allEntry_date($where); //26-04-16
        //$entries = $this->report->allEntrySelectByDate($where);
//        echo '<pre/>';print_r($entries);exit;
        $ledger_gr = array();
        $ledger_grand = array();
        $group_detail = array();
        if (count($ledgers) > 0 && count($entries) > 0) {
            foreach ($ledgers as $ledger) {
                $total_dr_amount = 0;
                $total_dr_balance = 0;
                $total_cr_balance = 0;
                $group_dr_amount = 0;
                $group_cr_amount = 0;
                $group_grand_debit_total = 0;
                foreach ($entries as $entry) {
                    if ($ledger['group_id'] == $entry['group_id']) {
                        if ($entry['account'] == 'Dr') {
                            $group_dr_amount += $entry['balance'];
                        }
                        if ($entry['account'] == 'Cr') {
                            $group_cr_amount += $entry['balance'];
                        }
                    }
                    if ($ledger['ladger_name'] == $entry['ladger_name']) {
                        if ($entry['account'] == 'Dr') {
                            $total_dr_balance += $entry['balance'];
                        }
                        if ($entry['account'] == 'Cr') {
                            $total_cr_balance += $entry['balance'];
                        }
                    }
                }

                $where = array(
                    //'group.user_id' => $this->session->userdata('user_id'),
                    //'group.company_id' => 1,
                    'group.status' => 1,
                    'group.deleted' => 0,
                    'group.id' => $ledger['group_id']
                );
                $group_name = $this->report->getGrandParentid($where);
                $group = '';
                if ($group_name[0]['parent_id'] != 0 &&
                        $group_name[0]['user_id'] == $this->session->userdata('user_id') &&
                        $group_name[0]['company_id'] == 1) {
                    $where = array(
                        //'group.user_id' => $this->session->userdata('user_id'),
                        //'group.company_id' => 1,
                        'group.status' => 1,
                        'group.deleted' => 0,
                        'group.id' => $group_name[0]['parent_id']
                    );

                    $parent_group_name = $this->report->getGrandParentid($where);
                    $group = $parent_group_name[0]['group_name'];
                } else {
                    $group = $group_name[0]['group_name'];
                }

                $group_grand_debit_total += $group_dr_amount;

                if (!in_array($ledger['group_name'], $ledger_gr)) {
                    $ledger_gr[] = $ledger['group_name'];
                    $group_detail[$group][$ledger['group_name']] = array('group_dr_amount' => $group_dr_amount, 'group_cr_amount' => $group_cr_amount, 'group_opening_balance_total' => 0, 'group_closing_balance_total' => 0);
                }

                $group_detail[$group][$ledger['group_name']][] = array(
                    'ladger_name' => $ledger['ladger_name'],
                    'opening_balance' => $ledger['opening_balance'],
                    'current_balance' => $ledger['current_balance'],
                    'account' => $entry['account'],
                    'balance' => $entry['balance'],
                    'total_balance' => $this->getTotalBalancePL($entries, $ledger['id']), //26-04-16
                    'total_dr_balance' => $total_dr_balance,
                    'total_cr_balance' => $total_cr_balance
                );
            }
        }

        foreach ($group_detail as $gkey => $groups) {
            foreach ($groups as $igkey => $innergroup) {
                foreach ($innergroup as $legKey => $leg) {
                    // $group_detail[$gkey][$igkey]['group_opening_balance_total'] += abs($leg['opening_balance']);
                    $group_detail[$gkey][$igkey]['group_closing_balance_total'] += $leg['total_balance'];
//                    $group_detail[$gkey][$igkey]['group_closing_balance_total'] += abs($leg['current_balance']);
                }
            }
        }

//        echo '<pre>';
//        print_r($group_detail);die();
        $income = $this->sortingt_parent_group_array_pl($group_detail, 'Income'); //26-04-16
        $expenses = $this->sortingt_parent_group_array_pl($group_detail, 'Expenses');

        //set total income, expenses, net loss, net profit variable
        $income_grand_total = 0;
        $expenses_grand_total = 0;
        $net_profit = '';
        $net_loss = '';
        $income_total = 0;
        $expenses_total = 0;
        if (count($income) > 0) {
            if ($income[1] < 0) {
                $income_total = substr($income[1], 1);
//                $income_total = $income[1];
            } else {
                $income_total = $income[1];
            }
        }
        if (count($expenses) > 0) {
            if ($expenses[1] < 0) {
//                $expenses_total = $expenses[1];
                $expenses_total = substr($expenses[1], 1);
            } else {
                $expenses_total = $expenses[1];
            }
        }
//        echo $income_total.'E'.$expenses_total;die();

        if ($income_total > $expenses_total) {
            $profit = ($income_total - $expenses_total);
            $net_profit = '<tr><td>Net Profit</td><td style="text-align: right;">' . number_format(($income_total - $expenses_total), 2) . '</td></tr>';
            $income_grand_total = number_format($income_total, 2);
            $expenses_grand_total = number_format(($expenses_total + $profit), 2);
        }
        if ($income_total < $expenses_total) {
            $loss = ($expenses_total - $income_total);
            $net_loss = '<tr><td>Net Loss</td><td style="text-align: right;">' . number_format(($expenses_total - $income_total), 2) . '</td></tr>';
            $income_grand_total = number_format(($income_total + $loss), 2);
            $expenses_grand_total = number_format($expenses_total, 2);
        }
        if (count($income) > 0 && count($expenses) > 0) {

            $profit_loss = '<tr>
                            <td width = 50%><!--Start:Table For Income-->
                                          <table width = "100%">
                                              <tbody>';
            if (count($expenses[0]) > 0) {
                $group_closing_balanice = 0;
                foreach ($expenses[0] as $key => $value) {
                    $profit_loss .= '<tr>
                                                            <td><strong>' . $key . '</strong></td>
                                                            <td style="text-align: right;">
                                                                <strong>';
                    $group_closing_balance = $value['group_closing_balance_total'];
                    if ($group_closing_balance < 0) {
                        $profit_loss .= '(-)' . number_format(substr($group_closing_balance, 1), 2);
                    } else {
                        $profit_loss .= number_format($group_closing_balance, 2);
                    }
                    $profit_loss .= '</strong>
                                                    </td>
                                                </tr>';
                    if (array_key_exists('group_dr_amount', $value)) {
                        unset($value['group_dr_amount']);
                        unset($value['group_cr_amount']);
                    }
                    foreach ($value as $val) {
                        if ($val['ladger_name'] != "") {
                            $profit_loss .= '<tr> 
                                                                  <td style="padding-left: 20px;">';
                            $profit_loss .= $val['ladger_name'];
                            $profit_loss .= '</td>
                                                               <td style="text-align: right">';
                            if ($val['current_balance'] < 0) {
                                $profit_loss .= '(-)';
                                $profit_loss .= number_format(substr($val['total_balance'], 1), 2);
//                                                    $profit_loss .= number_format($val['total_balance'],2); //26-04-16
                            } else {
//                                                    $profit_loss .= number_format($val['current_balance'], 2);
                                $profit_loss .= number_format($val['total_balance'], 2); //26-04-16
                            }
                            $profit_loss .= '</td>
                                                                </tr>';
                        }
                    }
                }
            }
            $profit_loss .= $net_profit;
            $profit_loss .= ' </tbody>
                                    </table>
                                </td><!--End:Table For Income-->';

            $profit_loss .= '<td width = 50%><!--Start:Table For Expenses-->
                                        <table width = "100%">
                                            <tbody>';
            if (count($income[0]) > 0) {
                $group_closing_balanice = 0;
                foreach ($income[0] as $key => $value) {
                    $profit_loss .= '<tr>
                                                  <td><strong>';
                    $profit_loss .= $key;
                    $profit_loss .= '</strong></td>
                                            <td style="text-align: right;">
                                                <strong>';
                    $group_closing_balance = number_format($value['group_closing_balance_total'], 2);
                    if ($group_closing_balance < 0) {
                        $profit_loss .= '(-)' . substr($group_closing_balance, 1);
                    } else {
                        $profit_loss .= $group_closing_balance;
                    }
                    $profit_loss .= '</strong>
                                            </td>
                                        </tr>';
                    if (array_key_exists('group_dr_amount', $value)) {
                        unset($value['group_dr_amount']);
                        unset($value['group_cr_amount']);
                    }
                    foreach ($value as $val) {
                        if ($val['ladger_name'] != "") {
                            $profit_loss .= '<tr> 
                                                       <td style="padding-left: 20px;">';
                            $profit_loss .= $val['ladger_name'];
                            $profit_loss .= '</td>
                                                      <td style="text-align: right">';
                            if ($val['current_balance'] < 0) {
                                $profit_loss .= '(-)';
                                $profit_loss .= number_format(substr($val['total_balance'], 1), 2);
//                                        $profit_loss .= number_format($val['total_balance'],2); //26-04-16
                            } else {
//                                        $profit_loss .= number_format($val['current_balance'], 2);
                                $profit_loss .= number_format($val['total_balance'], 2); //26-04-16
                            }
                            $profit_loss .= '</td>
                                                        </tr>';
                        }
                    }
                }
            }
            $profit_loss .= $net_loss;
            $profit_loss .= ' </tbody>
                                                </table>
                                            </td><!--End:Table For Expenses-->
                                        </tr>';
            $profit_loss .= ' <tr>
                                            <td width=50%>
                                                <strong>
                                                    <div style="width: 50%; float: left; text-align: left;">Total</div>
                                                    <div style="width: 50%; float: left; text-align: right;">' . $expenses_grand_total . '</div>
                                                </strong>
                                            </td>
                                            <td width=50%>
                                                <strong>
                                                    <div style="width: 50%; float: left; text-align: left;">Total</div>
                                                    <div style="width: 50%; float: left; text-align: right;">' . $income_grand_total . '</div>
                                                </strong>
                                            </td>
                                        </tr>';
        } else {
            $profit_loss = '<tr><td colspan="2" style="text-align: center;">Data not found</td></tr>';
        }
        echo $profit_loss;
        exit;
    }

    public function profit_loss_search_by_date_back() {
//        $from_date = date('Y-m-d', strtotime($_POST['from_date']));
//        $to_date = date('Y-m-d', strtotime($_POST['to_date']));

        $from_dateOld = str_replace("/", "-", $_POST['from_date']);
        $to_dateOld = str_replace("/", "-", $_POST['to_date']);

        $from_date = date('Y-m-d', strtotime($from_dateOld));

        if (!empty($to_dateOld)) {
            $to_date = date('Y-m-d', strtotime($to_dateOld));
        }

        if (empty($to_dateOld)) {
            $to_date = date('Y-m-d', strtotime('2050-12-31'));
        }
        $where = array(
            'ladger.status' => 1,
            'ladger.deleted' => 0
        );
        $ledgers = $this->report->trialBalance($where);
        unset($where);
        $where = array(
            'ladger_account_detail.status' => 1,
            'ladger_account_detail.create_date >=' => $from_date,
            'ladger_account_detail.create_date <=' => $to_date,
            'ladger_account_detail.deleted' => 0
        );
        $entries = $this->report->allEntry($where);
        //$entries = $this->report->allEntrySelectByDate($where);
//        echo '<pre/>';print_r($entries);exit;
        $ledger_gr = array();
        $ledger_grand = array();
        $group_detail = array();
        if (count($ledgers) > 0 && count($entries) > 0) {
            foreach ($ledgers as $ledger) {
                $total_dr_amount = 0;
                $total_dr_balance = 0;
                $total_cr_balance = 0;
                $group_dr_amount = 0;
                $group_cr_amount = 0;
                $group_grand_debit_total = 0;
                foreach ($entries as $entry) {
                    if ($ledger['group_id'] == $entry['group_id']) {
                        if ($entry['account'] == 'Dr') {
                            $group_dr_amount += $entry['balance'];
                        }
                        if ($entry['account'] == 'Cr') {
                            $group_cr_amount += $entry['balance'];
                        }
                    }
                    if ($ledger['ladger_name'] == $entry['ladger_name']) {
                        if ($entry['account'] == 'Dr') {
                            $total_dr_balance += $entry['balance'];
                        }
                        if ($entry['account'] == 'Cr') {
                            $total_cr_balance += $entry['balance'];
                        }
                    }
                }

                $where = array(
                    //'group.user_id' => $this->session->userdata('user_id'),
                    //'group.company_id' => 1,
                    'group.status' => 1,
                    'group.deleted' => 0,
                    'group.id' => $ledger['group_id']
                );
                $group_name = $this->report->getGrandParentid($where);
                $group = '';
                if ($group_name[0]['parent_id'] != 0 &&
                        $group_name[0]['user_id'] == $this->session->userdata('user_id') &&
                        $group_name[0]['company_id'] == 1) {
                    $where = array(
                        //'group.user_id' => $this->session->userdata('user_id'),
                        //'group.company_id' => 1,
                        'group.status' => 1,
                        'group.deleted' => 0,
                        'group.id' => $group_name[0]['parent_id']
                    );

                    $parent_group_name = $this->report->getGrandParentid($where);
                    $group = $parent_group_name[0]['group_name'];
                } else {
                    $group = $group_name[0]['group_name'];
                }

                $group_grand_debit_total += $group_dr_amount;

                if (!in_array($ledger['group_name'], $ledger_gr)) {
                    $ledger_gr[] = $ledger['group_name'];
                    $group_detail[$group][$ledger['group_name']] = array('group_dr_amount' => $group_dr_amount, 'group_cr_amount' => $group_cr_amount);
                }

                $group_detail[$group][$ledger['group_name']][] = array(
                    'ladger_name' => $ledger['ladger_name'],
                    'opening_balance' => $ledger['opening_balance'],
                    'current_balance' => $ledger['current_balance'],
                    'account' => $entry['account'],
                    'balance' => $entry['balance'],
                    'total_dr_balance' => $total_dr_balance,
                    'total_cr_balance' => $total_cr_balance
                );
            }
        }
        $income = $this->sortingt_parent_group_array($group_detail, 'Income');
        $expenses = $this->sortingt_parent_group_array($group_detail, 'Expenses');

        //set total income, expenses, net loss, net profit variable
        $income_grand_total = 0;
        $expenses_grand_total = 0;
        $net_profit = '';
        $net_loss = '';
        $income_total = 0;
        $expenses_total = 0;
        if (count($income) > 0) {
            if ($income[1] < 0) {
                $income_total = substr($income[1], 1);
            } else {
                $income_total = $income[1];
            }
        }
        if (count($expenses) > 0) {
            if ($expenses[1] < 0) {
                $expenses_total = substr($expenses[1], 1);
            } else {
                $expenses_total = $expenses[1];
            }
        }
        if ($income_total > $expenses_total) {
            $profit = ($income_total - $expenses_total);
            $net_profit = '<tr><td>Net Profit</td><td style="text-align: right;">' . number_format(($income_total - $expenses_total), 2) . '</td></tr>';
            $income_grand_total = number_format($income_total, 2);
            $expenses_grand_total = number_format(($expenses_total + $profit), 2);
        }
        if ($income_total < $expenses_total) {
            $loss = ($expenses_total - $income_total);
            $net_loss = '<tr><td>Net Loss</td><td style="text-align: right;">' . number_format(($expenses_total - $income_total), 2) . '</td></tr>';
            $income_grand_total = number_format(($income_total + $loss), 2);
            $expenses_grand_total = number_format($expenses_total, 2);
        }
        if (count($income) > 0 && count($expenses) > 0) {
            $profit_loss = '<tr>
                            <td width = 50%><!--Start:Table For Income-->
                                <table width = "100%">
                                    <tbody>';
            if (count($income) > 0) {
                $group_closing_balanice = 0;
                foreach ($income[0] as $key => $value) {
                    $profit_loss .='<tr>
                                                    <td><strong>' . $key . '</strong></td>
                                                    <td style="text-align: right;">
                                                        <strong>';
                    $group_closing_balance = number_format($value['group_dr_amount'] - $value['group_cr_amount'], 2);
                    if ($group_closing_balance < 0) {
                        $profit_loss .= substr($group_closing_balance, 1);
                    } else {
                        $profit_loss .= $group_closing_balance;
                    }
                    $profit_loss .='</strong>
                                                    </td>
                                                </tr>';
                    if (array_key_exists('group_dr_amount', $value)) {
                        unset($value['group_dr_amount']);
                        unset($value['group_cr_amount']);
                    }
                    foreach ($value as $val) {
                        $profit_loss .='<tr> 
                                                        <td style="padding-left: 20px;">'
                                . $val['ladger_name'] .
                                '</td>
                                                        <td style="text-align: right">';
                        if ($val['current_balance'] < 0) {
                            $profit_loss.= number_format(substr($val['current_balance'], 1), 2);
                        } else {
                            $profit_loss.= number_format($val['current_balance'], 2);
                        }
                        $profit_loss.='</td>
                                                    </tr>';
                    }
                }
            }
            $profit_loss.= $net_loss;
            $profit_loss .='</tbody>
                                </table>
                            </td><!--End:Table For Income-->
                            <td width = 50%><!--Start:Table For Expenses-->
                                <table width = "100%">
                                    <tbody>';
            if (count($expenses) > 0) {
                $group_closing_balanice = 0;
                foreach ($expenses[0] as $key => $value) {
                    $profit_loss.='<tr>
                                                    <td><strong>' . $key . '</strong></td>
                                                    <td style="text-align: right;">
                                                        <strong>';
                    $group_closing_balance = number_format($value['group_dr_amount'] - $value['group_cr_amount'], 2);
                    if ($group_closing_balance < 0) {
                        $profit_loss .= substr($group_closing_balance, 1);
                    } else {
                        $profit_loss .= $group_closing_balance;
                    }
                    $profit_loss.='</strong>
                                                    </td>
                                                </tr>';
                    if (array_key_exists('group_dr_amount', $value)) {
                        unset($value['group_dr_amount']);
                        unset($value['group_cr_amount']);
                    }
                    foreach ($value as $val) {
                        $profit_loss.='<tr> 
                                                        <td style="padding-left: 20px;">'
                                . $val['ladger_name'] .
                                '</td>
                                                        <td style="text-align: right">';
                        if ($val['current_balance'] < 0) {
                            $profit_loss.= number_format(substr($val['current_balance'], 1), 2);
                        } else {
                            $profit_loss.= number_format($val['current_balance'], 2);
                        }
                        $profit_loss.='</td>
                                                    </tr>';
                    }
                }
            }
            $profit_loss.= $net_profit;
            $profit_loss.='</tbody>
                                </table>
                            </td><!--End:Table For Expenses-->
                        </tr><tr>
                            <td width=50%>
                                <strong>
                                    <div style="width: 50%; float: left; text-align: left;">Total</div>
                                    <div style="width: 50%; float: left; text-align: right;">' . $income_grand_total . '</div>
                                </strong>
                            </td>
                            <td width=50%>
                                <strong>
                                    <div style="width: 50%; float: left; text-align: left;">Total</div>
                                    <div style="width: 50%; float: left; text-align: right;">' . $expenses_grand_total . '</div>
                                </strong>
                            </td>
                        </tr>';
        } else {
            $profit_loss = '<tr><td colspan="2" style="text-align: center;">Data not found</td></tr>';
        }
        echo $profit_loss;
        exit;
    }

    public function gete_parent($id) {
        $where = array(
            //'group.user_id' => $this->session->userdata('user_id'),
            //'group.company_id' => 1,
            'group.status' => 1,
            'group.deleted' => 0,
            'group.id' => $id
        );
        $group_name = $this->report->getGrandParentid($where);
        if (!empty($group_name)) {
            $this->store[] = $group_name;
            $this->gete_parent($group_name[0]['parent_id']);
        }

        return;
    }

    public function gete_parent_by_date($id, $from_date, $to_date) {
        $where = array(
            //'group.user_id' => $this->session->userdata('user_id'),
            //'group.company_id' => 1,
            'group.status' => 1,
            'group.deleted' => 0,
            'group.id' => $id
        );
        $group_name = $this->report->getGrandParentidByDate($where, $from_date, $to_date);
        if (!empty($group_name)) {
            $this->store[] = $group_name;
            $this->gete_parent_by_date($group_name[0]['parent_id'], $from_date, $to_date);
        }

        return;
    }

    //select ledger for bill 
    public function bill_ledger_statements() {
         user_permission(208,'list');
        $data = array();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | All Transactions');
        $this->layouts->render('admin/bill_ledger_list', $data, 'admin');
    }
    
    
    public function dayBook() {
        $data = array();
        $financial_year = get_financial_year();
        
        $data['current_date_format'] = $this->report->getCurrentDateFormat();
        $data['cur_financial_year'] = date('Y', strtotime(current($financial_year)));
        $from_date = (isset($_GET['from_date'])) ? $_GET['from_date'] : '';
        $to_date = (isset($_GET['to_date'])) ? $_GET['to_date'] : '';
        
        
        if( $from_date != "" && $to_date != ""){
            $from_date = str_replace("/", "-", $from_date);
            $to_date = str_replace("/", "-", $to_date);

            $from_date = $from_date . ' 00:00:00';
            $to_date = $to_date . ' 23:59:59';
        }
        
        
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['number_of_branch'] = $this->report->getNumberOfBranch();
        $data['results'] = $this->report->getDayBookForAllLedger($from_date, $to_date);
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Day Book');
        $this->layouts->render('admin/day_book', $data, 'admin');
    }
    
    
    /*
     * day book search
     */
    public function dayBookSearch() {
        $search = $this->input->post('search_value');
        $data = array();
        $data['current_date_format'] = $this->report->getCurrentDateFormat();
        if($search == ""){
            $data['results'] = $data['results'] = $this->report->getDayBookForAllLedger("", "");
        }else{
            $data['results'] = $this->report->searchDayBook($search);            
        }
        
        return $this->load->view('admin/day_book_search', $data);
    }
    
    public function getClosingStock($from_date,$to_date){
        $parent_id = 0;
        $sub_cat_id_arr = [];
        $sub_sub_cat_id_arr = [];
        $totalClosingStock = 0;
        $this->load->model('reports/admin/inventorymodel');
        $category_list = $this->inventorymodel->getCategoryDetailsByDate($parent_id, $from_date, $to_date);
        foreach ($category_list as $val) {
            $cat_id_arr = [];
            $sub_cat_id_arr = [];
            $cat_id_arr[] = $val->id;
            $sub_cat = $this->inventorymodel->getAllSubCat($val->id);
            foreach ($sub_cat as $v) {
                $sub_sub_cat_id_arr = [];
                $sub_cat_id_arr[] = $v->id;
                $sub_sub_cat = $this->inventorymodel->getAllSubCat($v->id);
                foreach ($sub_sub_cat as $sub_sub) {
                    $sub_sub_cat_id_arr[] = $sub_sub->id;
                }
            }
            $cat_id = array_merge($cat_id_arr, $sub_cat_id_arr, $sub_sub_cat_id_arr);
            $child_id = array_merge($sub_cat_id_arr, $sub_sub_cat_id_arr);
            $opening = $this->inventorymodel->getOpeningProductByCatId($cat_id, $from_date, $to_date);
            $in_product = $this->inventorymodel->getInProductByCatId($cat_id, $from_date, $to_date);
            $out_product = $this->inventorymodel->showoutstock($cat_id, $from_date, $to_date);
            
            $opening_stock = (!empty($opening)) ? $opening[0]->opening : 0;
            $opening_price = (!empty($opening)) ? $opening[0]->price : 0;
            $in_stock = (!empty($in_product)) ? $in_product[0]->in_product : 0;
            $in_price = (!empty($in_product)) ? $in_product[0]->price : 0;
            $out_stock = (!empty($out_product)) ? $out_product[0]->totalquantity : 0;
            $out_price = (!empty($out_product)) ? $out_product[0]->price : 0;
            $closing_stock = (($opening_stock + $in_stock) - $out_stock);
            if( isset($_GET['method']) || $this->session->userdata('inventory_method') ) {
                $method = (isset($_GET['method'])) ? $_GET['method'] : $this->session->userdata('inventory_method');
                $this->session->set_userdata('inventory_method', $method);
                
                switch ( $method ) {
                    case 1:
                       $closing_cost = getClosingStockSummaryUsingAverageCost($cat_id, $from_date, $to_date);
                       break;
                    case 2:
                        $closing_cost = getClosingStockSummaryUsingLastSalesPrice($cat_id, $from_date, $to_date, $closing_stock);
                        break;
                    case 3:
                        $closing_cost = getClosingStockSummaryUsingLastPurchasePrice($cat_id, $from_date, $to_date, $closing_stock);
                        break;
                    case 4:
                        $closing_cost = 0;
                        break;
                    case 5:
                        $closing_cost = getClosingStockSummaryUsingFIFO($cat_id, $from_date, $to_date, $closing_stock);
                        break;
                    case 6:
                        $closing_cost = getClosingStockSummaryUsingLIFO($cat_id, $from_date, $to_date, $closing_stock);
                        break;
                    default :
                        $closing_cost = getClosingStockSummaryUsingAverageCost($cat_id, $from_date, $to_date);
                        break;
                }
            }else{
                $closing_cost = getClosingStockSummaryUsingAverageCost($cat_id, $from_date, $to_date);
            }
            
            $closing_cost = (!empty($closing_cost)) ? $closing_cost : 0;
//            $stock_details[] = array(
//                'id' => $val->id,
//                'name' => $val->name,
//                'opening' => $opening_stock,
//                'opening_price' => $opening_price,
//                'in' => $in_stock,
//                'in_price' => $in_price,
//                'out' => $out_stock,
//                'out_price' => $out_price,
//                'closing' => $closing_stock,
//                'closing_cost' => $closing_cost,
//                'sub' => $cat_id,
//                'child' => (count($child_id) > 0) ? 1 : 0
//            );
            $totalClosingStock += $closing_cost; 
        }
         return $totalClosingStock;
    }
    
   

}

/* End of file login.php */
/* Location: ./application/controllers/login$items.php */