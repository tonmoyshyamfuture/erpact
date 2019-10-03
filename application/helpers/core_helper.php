<?php

function get_balance_as_group($group_id,$from_date,$to_date,$finans_start_date) {

    $CI = & get_instance();
    $CI->load->helper('core');
    $CI->load->model('account/corereport');
    $i = 0;
    $trial_balance_arr = array();
    if (isset($from_date) && $from_date && isset($to_date) && $to_date) {
        $levelOneGoroups = $CI->corereport->getAllGroupsByIdByDate($group_id, $finans_start_date, $to_date);
    } else {
        $levelOneGoroups = $CI->corereport->getAllGroupsById($group_id);
    }

    if (empty($levelOneGoroups)) {
        If (!is_null($group_id)) {
            $levelOneledgerDetails = $CI->corereport->getAllLedgerByGroupIdByDate($group_id, $from_date, $to_date,$finans_start_date);
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
                    $trial_balance_arr[$i]['cr_balance'] = $levelTwoledgerDetail['cr_balance'];
                    $trial_balance_arr[$i]['dr_balance'] = $levelTwoledgerDetail['dr_balance'];

                    $i++;
                }
            }
        }
    }
    if (!empty($levelOneGoroups)) {
            If (!is_null($group_id)) {
                $levelOneledgerDetails = $CI->corereport->getAllLedgerByGroupIdByDate($group_id, $from_date, $to_date,$finans_start_date);
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
//                $trial_balance_arr[$i]['account_type'] = get_account_type($levelOneGoroup['id'], $from_date, $to_date);
//                $trial_balance_arr[$i]['opening_balance'] = get_opening_balance($levelOneGoroup['id'], $from_date, $to_date);
//                $trial_balance_arr[$i]['cr_balance'] = get_cr_balance($levelOneGoroup['id'], $from_date, $to_date);
//                $trial_balance_arr[$i]['dr_balance'] = get_dr_balance($levelOneGoroup['id'], $from_date, $to_date);
                // echo $finans_start_date;exit();
                $trial_balance_arr[$i]['account_type'] = get_account_type_by_date($levelOneGoroup['id'], $finans_start_date, $to_date);
                $trial_balance_arr[$i]['opening_balance'] = get_opening_balance_by_date($levelOneGoroup['id'], $finans_start_date, $to_date);
                $trial_balance_arr[$i]['cr_balance'] = get_cr_balance_by_date($levelOneGoroup['id'], $from_date, $to_date,$finans_start_date);
                $trial_balance_arr[$i]['dr_balance'] = get_dr_balance_by_date($levelOneGoroup['id'], $from_date, $to_date,$finans_start_date);

                $i++;
                // For Level Second 
                $levelTwoGoroups = $CI->corereport->getAllGroupsByIdByDate($levelOneGoroup['id'], $finans_start_date, $to_date);
                $levelTwoledgerDetails = $CI->corereport->getAllLedgerByGroupIdByDate($levelOneGoroup['id'], $from_date, $to_date,$finans_start_date);
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
//                        $trial_balance_arr[$i]['account_type'] = get_account_type($levelTwoGoroup['id'], $from_date, $to_date);
//                        $trial_balance_arr[$i]['opening_balance'] = get_opening_balance($levelTwoGoroup['id'], $from_date, $to_date);
//                        $trial_balance_arr[$i]['cr_balance'] = get_cr_balance($levelTwoGoroup['id'], $from_date, $to_date);
//                        $trial_balance_arr[$i]['dr_balance'] = get_dr_balance($levelTwoGoroup['id'], $from_date, $to_date);
                        $trial_balance_arr[$i]['account_type'] = get_account_type_by_date($levelTwoGoroup['id'], $finans_start_date, $to_date);
                        $trial_balance_arr[$i]['opening_balance'] = get_opening_balance_by_date($levelTwoGoroup['id'], $finans_start_date, $to_date);
                        $trial_balance_arr[$i]['cr_balance'] = get_cr_balance_by_date($levelTwoGoroup['id'], $from_date, $to_date,$finans_start_date);
                        $trial_balance_arr[$i]['dr_balance'] = get_dr_balance_by_date($levelTwoGoroup['id'], $from_date, $to_date,$finans_start_date);

                        $i++;
                        // For Level Third 
                        $levelThreeGoroups = $CI->corereport->getAllGroupsByIdByDate($levelTwoGoroup['id'], $finans_start_date, $to_date);
                        $levelThreeledgerDetails = $CI->corereport->getAllLedgerByGroupIdByDate($levelTwoGoroup['id'], $from_date, $to_date,$finans_start_date);
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
//                                $trial_balance_arr[$i]['account_type'] = get_account_type($levelThreeGoroup['id'], $from_date, $to_date);
//                                $trial_balance_arr[$i]['opening_balance'] = get_opening_balance($levelThreeGoroup['id'], $from_date, $to_date);
//                                $trial_balance_arr[$i]['cr_balance'] = get_cr_balance($levelThreeGoroup['id'], $from_date, $to_date);
//                                $trial_balance_arr[$i]['dr_balance'] = get_dr_balance($levelThreeGoroup['id'], $from_date, $to_date);
                                $trial_balance_arr[$i]['account_type'] = get_account_type_by_date($levelThreeGoroup['id'], $finans_start_date, $to_date);
                                $trial_balance_arr[$i]['opening_balance'] = get_opening_balance_by_date($levelThreeGoroup['id'], $finans_start_date, $to_date);
                                $trial_balance_arr[$i]['cr_balance'] = get_cr_balance_by_date($levelThreeGoroup['id'], $from_date, $to_date,$finans_start_date);
                                $trial_balance_arr[$i]['dr_balance'] = get_dr_balance_by_date($levelThreeGoroup['id'], $from_date, $to_date,$finans_start_date);
                                $i++;
                            }
                        }
                    }
                }
            }
        }
        
        return $trial_balance_arr;
}

function get_account_type($group_id) {
        $returnArr = get_opening_balance_cal($group_id);
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
    function get_account_type_by_date($group_id, $from_date, $to_date) {
        $returnArr = get_opening_balance_cal_by_date($group_id, $from_date, $to_date);
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
    
    //sudip add new function get opening balance by group id
    function get_opening_balance($group_id) {
        $returnArr = get_opening_balance_cal($group_id);
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
    function get_opening_balance_by_date($group_id, $from_date, $to_date) {
        $returnArr = get_opening_balance_cal_by_date($group_id, $from_date, $to_date);
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
    
    function get_cr_balance($group_id) {
        $CI = & get_instance();
        $CI->load->model('account/corereport');
        $cr_balance = 0;
        $CrBalance = array();
        $arr = array();
        $resultOpeningBalance = $CI->corereport->getCrBalanceByGroupId($group_id);
        $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
        $childDetails = $CI->corereport->getChildIdParentId($group_id);
        foreach ($childDetails as $childDetail) {
            $id = $childDetail['id'];
            array_push($arr, $id);
            $resultOpeningBalance = $CI->corereport->getCrBalanceByGroupId($childDetail['id']);
            $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
        }
        for ($i = 0; $i < 5; $i++) {
            $newArr = array();
            for ($j = 0; $j < count($arr); $j++) {
                $childDetails1 = $CI->corereport->getChildIdParentId($arr[$j]);
                foreach ($childDetails1 as $childDetail) {
                    array_push($newArr, $childDetail['id']);
                    $resultOpeningBalance = $CI->corereport->getCrBalanceByGroupId($childDetail['id']);
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
//    function get_cr_balance_by_date($group_id, $from_date, $to_date) {
//        $CI = & get_instance();
//        $CI->load->model('account/corereport');
//        $cr_balance = 0;
//        $CrBalance = array();
//        $arr = array();
//        $resultOpeningBalance = $CI->corereport->getCrBalanceByGroupIdByDate($group_id, $from_date, $to_date);
//        $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
//        $childDetails = $CI->corereport->getChildIdParentIdByDate($group_id, $from_date, $to_date);
//        foreach ($childDetails as $childDetail) {
//            $id = $childDetail['id'];
//            array_push($arr, $id);
//            $resultOpeningBalance = $CI->corereport->getCrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date);
//            $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
//        }
//        for ($i = 0; $i < 5; $i++) {
//            $newArr = array();
//            for ($j = 0; $j < count($arr); $j++) {
//                $childDetails1 = $CI->corereport->getChildIdParentIdByDate($arr[$j], $from_date, $to_date);
//                foreach ($childDetails1 as $childDetail) {
//                    array_push($newArr, $childDetail['id']);
//                    $resultOpeningBalance = $CI->corereport->getCrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date);
//                    $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
//                }
//            }
//            $arr = $newArr;
//        }
//
//        foreach ($CrBalance as $row) {
//            $cr_balance += $row['cr_balance'];
//        }
//
//        return $cr_balance;
//    }
    //13022018
    function get_cr_balance_by_date($group_id, $from_date, $to_date,$finans_start_date) {
        $CI = & get_instance();
        $CI->load->model('account/corereport');
        $cr_balance = 0;
        $CrBalance = array();
        $arr = array();
        $resultOpeningBalance = $CI->corereport->getCrBalanceByGroupIdByDate($group_id, $from_date, $to_date,$finans_start_date);
        $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
        $childDetails = $CI->corereport->getChildIdParentIdByDate($group_id, $finans_start_date, $to_date);
        foreach ($childDetails as $childDetail) {
            $id = $childDetail['id'];
            array_push($arr, $id);
            $resultOpeningBalance = $CI->corereport->getCrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date,$finans_start_date);
            $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
        }
        for ($i = 0; $i < 5; $i++) {
            $newArr = array();
            for ($j = 0; $j < count($arr); $j++) {
                $childDetails1 = $CI->corereport->getChildIdParentIdByDate($arr[$j], $finans_start_date, $to_date);
                foreach ($childDetails1 as $childDetail) {
                    array_push($newArr, $childDetail['id']);
                    $resultOpeningBalance = $CI->corereport->getCrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date,$finans_start_date);
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

    function get_dr_balance($group_id) {
        $CI = & get_instance();
    $CI->load->model('account/corereport');
        $dr_balance = 0;
        $drBalance = array();
        $arr = array();
        $resultOpeningBalance = $CI->corereport->getDrBalanceByGroupId($group_id);
        $drBalance = array_merge($drBalance, $resultOpeningBalance);
        $childDetails = $CI->corereport->getChildIdParentId($group_id);
        foreach ($childDetails as $childDetail) {
            $id = $childDetail['id'];
            array_push($arr, $id);
            $resultOpeningBalance = $CI->corereport->getDrBalanceByGroupId($childDetail['id']);
            $drBalance = array_merge($drBalance, $resultOpeningBalance);
        }
        for ($i = 0; $i < 5; $i++) {
            $newArr = array();
            for ($j = 0; $j < count($arr); $j++) {
                $childDetails1 = $CI->corereport->getChildIdParentId($arr[$j]);
                foreach ($childDetails1 as $childDetail) {
                    array_push($newArr, $childDetail['id']);
                    $resultOpeningBalance = $CI->corereport->getDrBalanceByGroupId($childDetail['id']);
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
//    function get_dr_balance_by_date($group_id, $from_date, $to_date) {
//        $CI = & get_instance();
//        $CI->load->model('account/corereport');
//        $dr_balance = 0;
//        $drBalance = array();
//        $arr = array();
//        $resultOpeningBalance = $CI->corereport->getDrBalanceByGroupIdByDate($group_id, $from_date, $to_date);
//       
//        $drBalance = array_merge($drBalance, $resultOpeningBalance);
//        $childDetails = $CI->corereport->getChildIdParentIdByDate($group_id, $from_date, $to_date);
//        foreach ($childDetails as $childDetail) {
//            $id = $childDetail['id'];
//            array_push($arr, $id);
//            $resultOpeningBalance = $CI->corereport->getDrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date);
//            $drBalance = array_merge($drBalance, $resultOpeningBalance);
//        }
//        for ($i = 0; $i < 5; $i++) {
//            $newArr = array();
//            for ($j = 0; $j < count($arr); $j++) {
//                $childDetails1 = $CI->corereport->getChildIdParentIdByDate($arr[$j], $from_date, $to_date);
//                foreach ($childDetails1 as $childDetail) {
//                    array_push($newArr, $childDetail['id']);
//                    $resultOpeningBalance = $CI->corereport->getDrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date);
//                    $drBalance = array_merge($drBalance, $resultOpeningBalance);
//                }
//            }
//            $arr = $newArr;
//        }
//
//        foreach ($drBalance as $row) {
//            $dr_balance += $row['dr_balance'];
//        }
//
//        return $dr_balance;
//        //echo $dr_balance;die();
//    }
    //13022018
    function get_dr_balance_by_date($group_id, $from_date, $to_date,$finans_start_date) {
        $CI = & get_instance();
        $CI->load->model('account/corereport');
        $dr_balance = 0;
        $drBalance = array();
        $arr = array();
        // $resultOpeningBalance = $CI->report->getDrBalanceByGroupIdByDate($group_id, $from_date, $to_date);
        $resultOpeningBalance = $CI->corereport->getDrBalanceByGroupIdByDate($group_id, $from_date, $to_date, $finans_start_date);

        $drBalance = array_merge($drBalance, $resultOpeningBalance);
        // $childDetails = $CI->report->getChildIdParentIdByDate($group_id, $finans_start_date, $to_date);
        $childDetails = $CI->corereport->getChildIdParentIdByDate($group_id, $finans_start_date, $to_date);

        foreach ($childDetails as $childDetail) {
            $id = $childDetail['id'];
            array_push($arr, $id);
            // $resultOpeningBalance = $CI->report->getDrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date,$finans_start_date);
            $resultOpeningBalance = $CI->corereport->getDrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date,$finans_start_date);
            $drBalance = array_merge($drBalance, $resultOpeningBalance);
        }

        for ($i = 0; $i < 5; $i++) {
            $newArr = array();
            for ($j = 0; $j < count($arr); $j++) {
                // $childDetails1 = $CI->report->getChildIdParentIdByDate($arr[$j], $finans_start_date, $to_date);
                $childDetails1 = $CI->corereport->getChildIdParentIdByDate($arr[$j], $finans_start_date, $to_date);
                foreach ($childDetails1 as $childDetail) {
                    array_push($newArr, $childDetail['id']);
                    // $resultOpeningBalance = $CI->report->getDrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date,$finans_start_date);
                    $resultOpeningBalance = $CI->corereport->getDrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date,$finans_start_date);
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
    
  function get_opening_balance_cal_by_date($group_id, $from_date, $to_date) {
         $CI = & get_instance();
    $CI->load->model('account/corereport');
        $openingBalance = array();
        $arr = array();
        $resultOpeningBalance = $CI->corereport->getOpeningBalanceByGroupIdByDate($group_id, $from_date, $to_date);
        $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
        $childDetails = $CI->corereport->getChildIdParentIdByDate($group_id, $from_date, $to_date);
        foreach ($childDetails as $childDetail) {
            $id = $childDetail['id'];
            array_push($arr, $id);
            $resultOpeningBalance = $CI->corereport->getOpeningBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date);
            $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
        }
        for ($i = 0; $i < 5; $i++) {
            $newArr = array();
            for ($j = 0; $j < count($arr); $j++) {
                $childDetails1 = $CI->corereport->getChildIdParentIdByDate($arr[$j], $from_date, $to_date);
                foreach ($childDetails1 as $childDetail) {
                    array_push($newArr, $childDetail['id']);
                    $resultOpeningBalance = $CI->corereport->getOpeningBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date);
                    $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
                }
            }
            $arr = $newArr;
        }

        return $openingBalance;

        //echo '<pre>';print_r($openingBalance);die();
    }
    
    
    function getTotalValueForFirstLevel($total_balance_arr){
        $total_balance_val = 0;
        if(!empty($total_balance_arr)){
            foreach ($total_balance_arr as $total_balance) {
                if( $total_balance['level'] == 'level_first'){ 
                    if($total_balance['account_type'] == 'Cr'){
                        $total_balance_val += $total_balance['amount'];
                    }else{
                        $total_balance_val -= $total_balance['amount'];
                    }

                }
            }
        }
        return $total_balance_val;
    }

    function getCalculatedValue($trial_balance_arr = array(),$site){
        $finalArray = array();
        if(!empty($trial_balance_arr)){
            $closing_balance = 0;
            $total_debit_closing_balance = 0;
            $total_credit_closing_balance = 0;
            $finalArray = array();
            $i= 0;
            foreach ($trial_balance_arr as $trial_balance) {
            if($trial_balance['account_type'] == 'Dr'){
                $closing_balance = str_replace( '-','',$trial_balance['opening_balance']) + $trial_balance['dr_balance'] - $trial_balance['cr_balance'];
                    if($closing_balance > 0){
                        $account_type = 'Dr';
                        if( $trial_balance['type'] == 'ledger'){ 
                            $total_debit_closing_balance += $closing_balance;
                        }
                    }else{
                        $account_type = 'Cr';
                        if( $trial_balance['type'] == 'ledger'){ 
                            $total_credit_closing_balance += str_replace( '-','',sprintf('%0.2f',$closing_balance));
                        }
                    }
                    
                }

                if($trial_balance['account_type'] == 'Cr'){
                    $closing_balance = str_replace( '-','',$trial_balance['opening_balance']) + $trial_balance['cr_balance'] - $trial_balance['dr_balance'];
                    if($closing_balance > 0){
                        $account_type = 'Cr';
                        if( $trial_balance['type'] == 'ledger'){ 
                            $total_credit_closing_balance += str_replace( '-','',sprintf('%0.2f',$closing_balance));
                         }   
                    }else{
                        $account_type = 'Dr';
                        if( $trial_balance['type'] == 'ledger'){ 
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
                $finalArray[$i]['opening_balance'] = str_replace( '-','',$trial_balance['opening_balance']);
                $finalArray[$i]['amount'] = str_replace( '-','',$closing_balance);
                $i++;
            }
        }
        return $finalArray;
    }
    
    


