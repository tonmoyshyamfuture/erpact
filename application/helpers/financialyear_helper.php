<?php
//financial year array
function get_financial_year() {
     $ci = & get_instance();
    $ci->load->model('admin/financialyearmodel');
    $financial_year_start = $ci->financialyearmodel->getFinancialYear();
 
    $arr = explode("-", $financial_year_start->finalcial_year_from);
    $y = isset($arr) ? $arr[0] : date("Y");
    $m = isset($arr) ? $arr[1] : 4;
    $finance_arr = [];
    //for current year
//    if ($m > date("m")) {
//        $year = $y - 1;
//    } else {
//        $year = $y;
//    }
    $year = $y;
    for ($i = $m; $i <= 12; $i++) {
        $finance_arr[] = $year . '-' . $i;
    }
    for ($i = 1; $i < $m; $i++) {
        $finance_arr[] = ($year + 1) . '-' . $i;
    }
    return $finance_arr;
}

//financial year array if date range is set

 function get_financial_year_by_date_range($from_date, $to_date) {
        $ci = & get_instance();
        $ci->load->model('admin/financialyearmodel');
        $financial_year = get_financial_year();
        if (!$from_date || !$to_date) {
            $from_date = date("Y-m-d", strtotime(current($financial_year)));
            $to_date = date("Y-m-t", strtotime(end($financial_year)));
        }
        $from_date_arr = explode('-', $from_date);
        $to_date_arr = explode('-', $to_date);
        $m = isset($from_date_arr[1]) ? $from_date_arr[1] : 4;
        if ($m > date("m")) {
//            $year = date("Y") - 1;
            $year = date('Y', strtotime(current($financial_year))) - 1;//sudip@23042018
        } else {
//            $year = date("Y");
            $year = date('Y', strtotime(current($financial_year)));//sudip@23042018
        }
        $finance_arr = [];
        $n = (isset($to_date_arr[0]) && $to_date_arr[0] > $year) ? 12 : $to_date_arr[1];
        for ($i = $m; $i <= $n; $i++) {
            if (isset($from_date_arr[0]) && $from_date_arr[0] == $year) {
                $finance_arr[] = $year . '-' . $i;
            } else {
                $finance_arr[] = ($year + 1) . '-' . $i;
            }
        }
        for ($i = 1; $i <= $to_date_arr[1]; $i++) {
            if (isset($to_date_arr[0]) && $to_date_arr[0] > $year) {
                $finance_arr[] = ($year + 1) . '-' . $i;
            }
        }
        return $finance_arr;
    }
    
    function lastEntryDate(){
        $ci = & get_instance();
        $ci->load->model('admin/financialyearmodel');
        $max_entry = $ci->financialyearmodel->getMaxEntryDate();
        if (empty($max_entry->create_date)) {
            $max_entry->create_date = date('Y-m-d');
        }
        
        return $max_entry->create_date;
    }
