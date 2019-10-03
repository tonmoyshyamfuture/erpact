<?php

function get_balance_as_group($group_id,$from_date,$to_date) {
    $CI = & get_instance();
    $CI->load->helper('traialbalance');
    $CI->load->model('account/report');
    $i = 0;
    $trial_balance_arr = array();
    if (isset($from_date) && $from_date && isset($to_date) && $to_date) {
        $levelOneGoroups = $CI->report->getAllGroupsByIdByDate($group_id, $from_date, $to_date);
    } else {
        $levelOneGoroups = $CI->report->getAllGroupsById($group_id);
    }
    if (empty($levelOneGoroups)) {
        If (!is_null($group_id)) {
            $levelOneledgerDetails = $CI->report->getAllLedgerByGroupIdByDate($group_id, $from_date, $to_date);
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
            $levelOneledgerDetails = $CI->report->getAllLedgerByGroupIdByDate($group_id, $from_date, $to_date);
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
        if (!empty($levelOneGoroups)) {
            foreach ($levelOneGoroups AS $levelOneGoroup) {
                $trial_balance_arr[$i]['type'] = 'group';
                $trial_balance_arr[$i]['level'] = 'level_first';
                $trial_balance_arr[$i]['id'] = $levelOneGoroup['id'];
                $trial_balance_arr[$i]['name'] = $levelOneGoroup['group_name'];
                $trial_balance_arr[$i]['code'] = $levelOneGoroup['group_code'];
                $trial_balance_arr[$i]['parent_id'] = $levelOneGoroup['parent_id'];
                $trial_balance_arr[$i]['account_type'] = get_account_type($levelOneGoroup['id']);
                $trial_balance_arr[$i]['opening_balance'] = get_opening_balance($levelOneGoroup['id']);
                $trial_balance_arr[$i]['cr_balance'] = get_cr_balance($levelOneGoroup['id']);
                $trial_balance_arr[$i]['dr_balance'] = get_dr_balance($levelOneGoroup['id']);

                $i++;
                // For Level Second 
                $levelTwoGoroups = $CI->report->getAllGroupsById($levelOneGoroup['id']);
                $levelTwoledgerDetails = $CI->report->getAllLedgerByGroupId($levelOneGoroup['id']);
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
                        $trial_balance_arr[$i]['account_type'] = get_account_type($levelTwoGoroup['id']);
                        $trial_balance_arr[$i]['opening_balance'] = get_opening_balance($levelTwoGoroup['id']);
                        $trial_balance_arr[$i]['cr_balance'] = get_cr_balance($levelTwoGoroup['id']);
                        $trial_balance_arr[$i]['dr_balance'] = get_dr_balance($levelTwoGoroup['id']);

                        $i++;
                        // For Level Third 
                        $levelThreeGoroups = $CI->report->getAllGroupsById($levelTwoGoroup['id']);
                        $levelThreeledgerDetails = $CI->report->getAllLedgerByGroupId($levelTwoGoroup['id']);
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
                                $trial_balance_arr[$i]['account_type'] = get_account_type($levelThreeGoroup['id']);
                                $trial_balance_arr[$i]['opening_balance'] = get_opening_balance($levelThreeGoroup['id']);
                                $trial_balance_arr[$i]['cr_balance'] = get_cr_balance($levelThreeGoroup['id']);
                                $trial_balance_arr[$i]['dr_balance'] = get_cr_balance($levelThreeGoroup['id']);
                                $i++;
                            }
                        }
                    }
                }
            }
        }
    }
    return $trial_balance_arr;
}



function get_balance_as_group_between_date($levelOneGoroups, $from_date, $to_date) {
    $CI = & get_instance();
    $CI->load->helper('traialbalance');
    $CI->load->model('account/report');
    $i = 0;
    $trial_balance_arr = [];
    if (!empty($levelOneGoroups)) {
        foreach ($levelOneGoroups AS $levelOneGoroup) {
            $trial_balance_arr[$i]['type'] = 'group';
            $trial_balance_arr[$i]['level'] = 'level_first';
            $trial_balance_arr[$i]['id'] = $levelOneGoroup['id'];
            $trial_balance_arr[$i]['name'] = $levelOneGoroup['group_name'];
            $trial_balance_arr[$i]['code'] = $levelOneGoroup['group_code'];
            $trial_balance_arr[$i]['parent_id'] = $levelOneGoroup['parent_id'];
            $trial_balance_arr[$i]['account_type'] = get_account_type_by_date($levelOneGoroup['id'], $from_date, $to_date);
            $trial_balance_arr[$i]['opening_balance'] = get_opening_balance_by_date($levelOneGoroup['id'], $from_date, $to_date);
            $trial_balance_arr[$i]['cr_balance'] = get_cr_balance_by_date($levelOneGoroup['id'], $from_date, $to_date);
            $trial_balance_arr[$i]['dr_balance'] = get_dr_balance_by_date($levelOneGoroup['id'], $from_date, $to_date);

            $i++;
            // For Level Second 
            $levelTwoGoroups = $CI->report->getAllGroupsByIdByDate($levelOneGoroup['id'], $from_date, $to_date);
            $levelTwoledgerDetails = $CI->report->getAllLedgerByGroupIdByDate($levelOneGoroup['id'], $from_date, $to_date);
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
                    $trial_balance_arr[$i]['account_type'] = get_account_type_by_date($levelTwoGoroup['id'], $from_date, $to_date);
                    $trial_balance_arr[$i]['opening_balance'] = get_opening_balance_by_date($levelTwoGoroup['id'], $from_date, $to_date);
                    $trial_balance_arr[$i]['cr_balance'] = get_cr_balance_by_date($levelTwoGoroup['id'], $from_date, $to_date);
                    $trial_balance_arr[$i]['dr_balance'] = get_dr_balance_by_date($levelTwoGoroup['id'], $from_date, $to_date);

                    $i++;
                    // For Level Third 
                    $levelThreeGoroups = $CI->report->getAllGroupsByIdByDate($levelTwoGoroup['id'], $from_date, $to_date);
                    $levelThreeledgerDetails = $CI->report->getAllLedgerByGroupIdByDate($levelTwoGoroup['id'], $from_date, $to_date);
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
                            $trial_balance_arr[$i]['account_type'] = get_account_type_by_date($levelThreeGoroup['id'], $from_date, $to_date);
                            $trial_balance_arr[$i]['opening_balance'] = get_opening_balance_by_date($levelThreeGoroup['id'], $from_date, $to_date);
                            $trial_balance_arr[$i]['cr_balance'] = get_cr_balance_by_date($levelThreeGoroup['id'], $from_date, $to_date);
                            $trial_balance_arr[$i]['dr_balance'] = get_dr_balance_by_date($levelThreeGoroup['id'], $from_date, $to_date);
                            $i++;
                        }
                    }
                }
            }
        }
    }
    return $trial_balance_arr;
}

function get_cr_balance($group_id) {
    $CI = & get_instance();
    $CI->load->model('account/report');
    $cr_balance = 0;
    $CrBalance = array();
    $arr = array();
    $resultOpeningBalance = $CI->report->getCrBalanceByGroupId($group_id);
    $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
    $childDetails = $CI->report->getChildIdParentId($group_id);
    foreach ($childDetails as $childDetail) {
        $id = $childDetail['id'];
        array_push($arr, $id);
        $resultOpeningBalance = $CI->report->getCrBalanceByGroupId($childDetail['id']);
        $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
    }
    for ($i = 0; $i < 5; $i++) {
        $newArr = array();
        for ($j = 0; $j < count($arr); $j++) {
            $childDetails1 = $CI->report->getChildIdParentId($arr[$j]);
            foreach ($childDetails1 as $childDetail) {
                array_push($newArr, $childDetail['id']);
                $resultOpeningBalance = $CI->report->getCrBalanceByGroupId($childDetail['id']);
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

//get CR balence by date range
function get_cr_balance_by_date($group_id, $from_date, $to_date) {
    $CI = & get_instance();
    $CI->load->model('account/report');
    $cr_balance = 0;
    $CrBalance = array();
    $arr = array();
    $resultOpeningBalance = $CI->report->getCrBalanceByGroupIdByDate($group_id, $from_date, $to_date);
    $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
    $childDetails = $CI->report->getChildIdParentIdByDate($group_id, $from_date, $to_date);
    foreach ($childDetails as $childDetail) {
        $id = $childDetail['id'];
        array_push($arr, $id);
        $resultOpeningBalance = $CI->report->getCrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date);
        $CrBalance = array_merge($CrBalance, $resultOpeningBalance);
    }
    for ($i = 0; $i < 5; $i++) {
        $newArr = array();
        for ($j = 0; $j < count($arr); $j++) {
            $childDetails1 = $CI->report->getChildIdParentIdByDate($arr[$j], $from_date, $to_date);
            foreach ($childDetails1 as $childDetail) {
                array_push($newArr, $childDetail['id']);
                $resultOpeningBalance = $CI->report->getCrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date);
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
    $CI->load->model('account/report');
    $dr_balance = 0;
    $drBalance = array();
    $arr = array();
    $resultOpeningBalance = $CI->report->getDrBalanceByGroupId($group_id);
    $drBalance = array_merge($drBalance, $resultOpeningBalance);
    $childDetails = $CI->report->getChildIdParentId($group_id);
    foreach ($childDetails as $childDetail) {
        $id = $childDetail['id'];
        array_push($arr, $id);
        $resultOpeningBalance = $CI->report->getDrBalanceByGroupId($childDetail['id']);
        $drBalance = array_merge($drBalance, $resultOpeningBalance);
    }
    for ($i = 0; $i < 5; $i++) {
        $newArr = array();
        for ($j = 0; $j < count($arr); $j++) {
            $childDetails1 = $CI->report->getChildIdParentId($arr[$j]);
            foreach ($childDetails1 as $childDetail) {
                array_push($newArr, $childDetail['id']);
                $resultOpeningBalance = $CI->report->getDrBalanceByGroupId($childDetail['id']);
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

//get DR balence by Date Range
function get_dr_balance_by_date($group_id, $from_date, $to_date) {
    $CI = & get_instance();
    $CI->load->model('account/report');
    $dr_balance = 0;
    $drBalance = array();
    $arr = array();
    $resultOpeningBalance = $CI->report->getDrBalanceByGroupIdByDate($group_id, $from_date, $to_date);
    $drBalance = array_merge($drBalance, $resultOpeningBalance);
    $childDetails = $CI->report->getChildIdParentIdByDate($group_id, $from_date, $to_date);
    foreach ($childDetails as $childDetail) {
        $id = $childDetail['id'];
        array_push($arr, $id);
        $resultOpeningBalance = $CI->report->getDrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date);
        $drBalance = array_merge($drBalance, $resultOpeningBalance);
    }
    for ($i = 0; $i < 5; $i++) {
        $newArr = array();
        for ($j = 0; $j < count($arr); $j++) {
            $childDetails1 = $CI->report->getChildIdParentIdByDate($arr[$j], $from_date, $to_date);
            foreach ($childDetails1 as $childDetail) {
                array_push($newArr, $childDetail['id']);
                $resultOpeningBalance = $CI->report->getDrBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date);
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

function get_account_type($group_id) {
    $CI = & get_instance();
    $CI->load->helper('traialbalance');
    $CI->load->model('account/report');
    $returnArr = get_opening_balance_cal($group_id);
    $opening_balance = 0;
    foreach ($returnArr as $row) {
        if ($row['account_type'] == 'Dr') {
            $opening_balance = $opening_balance + $row['opening_balance'];
        }
        if ($row['account_type'] == 'Cr') {
            $opening_balance = $opening_balance - str_replace('-', '', $row['opening_balance']);
        }
    }
    if ($opening_balance >= 0) {
        return 'Dr';
    }
    if ($opening_balance < 0) {
        return 'Cr';
    }
}

//get account type by date range
function get_account_type_by_date($group_id, $from_date, $to_date) {
    $CI = & get_instance();
    $CI->load->helper('traialbalance');
    $CI->load->model('account/report');
    $returnArr = get_opening_balance_cal_by_date($group_id, $from_date, $to_date);
    $opening_balance = 0;
    foreach ($returnArr as $row) {
        if ($row['account_type'] == 'Dr') {
            $opening_balance = $opening_balance + $row['opening_balance'];
        }
        if ($row['account_type'] == 'Cr') {
            $opening_balance = $opening_balance - str_replace('-', '', $row['opening_balance']);
        }
    }
    if ($opening_balance >= 0) {
        return 'Dr';
    }
    if ($opening_balance < 0) {
        return 'Cr';
    }
}

//get opening balence by date
function get_opening_balance_cal_by_date($group_id, $from_date, $to_date) {
    $CI = & get_instance();
    $CI->load->model('account/report');
    $openingBalance = array();
    $arr = array();
    $resultOpeningBalance = $CI->report->getOpeningBalanceByGroupIdByDate($group_id, $from_date, $to_date);

    $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
    $childDetails = $CI->report->getChildIdParentIdByDate($group_id, $from_date, $to_date);
    foreach ($childDetails as $childDetail) {
        $id = $childDetail['id'];
        array_push($arr, $id);
        $resultOpeningBalance = $CI->report->getOpeningBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date);
        $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
    }
    for ($i = 0; $i < 5; $i++) {
        $newArr = array();
        for ($j = 0; $j < count($arr); $j++) {
            $childDetails1 = $CI->report->getOpeningBalanceByGroupIdByDate($arr[$j], $from_date, $to_date);
            foreach ($childDetails1 as $childDetail) {
                array_push($newArr, $childDetail['id']);
                $resultOpeningBalance = $CI->report->getOpeningBalanceByGroupIdByDate($childDetail['id'], $from_date, $to_date);
                $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
            }
        }
        $arr = $newArr;
    }

    return $openingBalance;

    //echo '<pre>';print_r($openingBalance);die();
}

function get_opening_balance_cal($group_id) {
    $CI = & get_instance();
    $CI->load->model('account/report');
    $openingBalance = array();
    $arr = array();
    $resultOpeningBalance = $CI->report->getOpeningBalanceByGroupId($group_id);
    $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
    $childDetails = $CI->report->getChildIdParentId($group_id);
    foreach ($childDetails as $childDetail) {
        $id = $childDetail['id'];
        array_push($arr, $id);
        $resultOpeningBalance = $CI->report->getOpeningBalanceByGroupId($childDetail['id']);
        $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
    }
    for ($i = 0; $i < 5; $i++) {
        $newArr = array();
        for ($j = 0; $j < count($arr); $j++) {
            $childDetails1 = $CI->report->getChildIdParentId($arr[$j]);
            foreach ($childDetails1 as $childDetail) {
                array_push($newArr, $childDetail['id']);
                $resultOpeningBalance = $CI->report->getOpeningBalanceByGroupId($childDetail['id']);
                $openingBalance = array_merge($openingBalance, $resultOpeningBalance);
            }
        }
        $arr = $newArr;
    }

    return $openingBalance;

    //echo '<pre>';print_r($openingBalance);die();
}

//sudip add new function get opening balance by group id
function get_opening_balance($group_id) {

    $CI = & get_instance();
    $CI->load->helper('traialbalance');
    $CI->load->model('account/report');
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

//get opening balence by date
function get_opening_balance_by_date($group_id, $from_date, $to_date) {

    $CI = & get_instance();
    $CI->load->helper('traialbalance');
    $CI->load->model('account/report');
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

function get_transaction_details($id = NULL) {
    $CI = & get_instance();
    $CI->load->model('account/report', 'accountentry');
    $data['entry_id'] = $id;
    $where = "";
    $where = array(
        'status' => 1,
    );
    $ledger_name = $CI->accountentry->getAllLedgerName($where);
    $ledger = array();
    $ledger[''] = 'Select..';
    foreach ($ledger_name as $val) {
        $ledger[$val['id']] = $val['ladger_name'];
    }

    $all_type = $CI->accountentry->allEntryType($where);
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
    $data['entry'] = $CI->accountentry->getEntryDetailsById($where);
    unset($where);

    $where = array(
        'ladger_account_detail.entry_id' => $id,
        'ladger_account_detail.status' => 1,
        'ladger_account_detail.deleted' => 0
    );

    $data['entry_details'] = $CI->accountentry->getAllEntryDetailById($where);
    $data['currencies'] = $CI->accountentry->getAllCurrency();

    return $data;
}

// function get_bill_by_entry_details($entryId,$ledgerId,$drcr){
//     $CI = & get_instance();
//     $CI->load->model('account/report');
//     $data = array();
//     $data['currencies'] = $CI->report->getBillss($entryId,$ledgerId,$drcr);
//     return $data;

// }
