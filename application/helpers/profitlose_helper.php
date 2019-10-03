<?php

function profit_loss() {
    $CI = & get_instance();
    $CI->load->helper('profitlose');
    $CI->load->model('accounts/report');
    $expenses_ordering_array = array(32, 29);
    $expenses_ordering = array();
    for ($k = 0; $k < count($expenses_ordering_array); $k++) {
        $result = $CI->report->assetesOrdering($expenses_ordering_array[$k]);
        $expenses_ordering = array_merge($expenses_ordering, $result);
    }
    $data['expenses_ordering'] = $expenses_ordering;
    $data['expenses_indirect'] = $CI->report->assetesOrdering(31);

    $income_ordering_array = array(37, 34);
    $income_ordering = array();
    for ($k = 0; $k < count($income_ordering_array); $k++) {
        $result = $CI->report->assetesOrdering($income_ordering_array[$k]);
        $income_ordering = array_merge($income_ordering, $result);
    }
    $data['income_ordering'] = $income_ordering;
    $data['income_indirect'] = $CI->report->assetesOrdering(36);
//        echo '<pre>';print_r($data['income_indirect']);die();

    $where = array(
        'ladger.status' => 1,
        'ladger.deleted' => 0
    );
    $ledgers = $CI->report->trialBalance($where);
    $where = array(
        'ladger_account_detail.status' => 1,
        'ladger_account_detail.deleted' => 0
    );
    $entries = $CI->report->allEntryPL($where);
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
        $group_name = $CI->report->getGrandParentid($where);
        $group = '';
        if ($group_name[0]['parent_id'] != 0) {
            $store = gete_parent($group_name[0]['parent_id']);
            $parent_category = array_pop($store);
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
            'total_balance' => getTotalBalancePL($entries, $ledger['id'], $group),
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
    $income = sortingt_parent_group_array_pl($group_detail, 'Income', $income_ordering);
    $expenses = sortingt_parent_group_array_pl($group_detail, 'Expenses', $expenses_ordering);
    $data['income'] = $income;
    $data['expenses'] = $expenses;
    return $data;
}


//get profit and lass by date
function profit_loss_by_date($from_date, $to_date) {
    $CI = & get_instance();
    $CI->load->helper('profitlose');
    $CI->load->model('accounts/report');
    $expenses_ordering_array = array(32, 29);
    $expenses_ordering = array();
    for ($k = 0; $k < count($expenses_ordering_array); $k++) {
        $result = $CI->report->assetesOrdering($expenses_ordering_array[$k]);
        $expenses_ordering = array_merge($expenses_ordering, $result);
    }
    $data['expenses_ordering'] = $expenses_ordering;
    $data['expenses_indirect'] = $CI->report->assetesOrdering(31);

    $income_ordering_array = array(37, 34);
    $income_ordering = array();
    for ($k = 0; $k < count($income_ordering_array); $k++) {
        $result = $CI->report->assetesOrdering($income_ordering_array[$k]);
        $income_ordering = array_merge($income_ordering, $result);
    }
    $data['income_ordering'] = $income_ordering;
    $data['income_indirect'] = $CI->report->assetesOrdering(36);
//        echo '<pre>';print_r($data['income_indirect']);die();

    $where = array(
        'ladger.status' => 1,
        'ladger.deleted' => 0
    );
    $ledgers = $CI->report->trialBalanceByDate($where,$from_date, $to_date);
    $where = array(
        'ladger_account_detail.status' => 1,
        'ladger_account_detail.deleted' => 0
    );
    $entries = $CI->report->allEntryPL($where);
    $ledger_gr = array();
    $ledger_grand = array();
    $entry['account'] = 0;
    $entry['balance'] = 0;
    $group_detail=[];
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
        $group_name = $CI->report->getGrandParentid($where);
        $group = '';
        if ($group_name[0]['parent_id'] != 0) {
            $store = gete_parent($group_name[0]['parent_id']);
            $parent_category = array_pop($store);
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
            'total_balance' => getTotalBalancePL($entries, $ledger['id'], $group),
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
    $income = sortingt_parent_group_array_pl($group_detail, 'Income', $income_ordering);
    $expenses = sortingt_parent_group_array_pl($group_detail, 'Expenses', $expenses_ordering);
    $data['income'] = $income;
    $data['expenses'] = $expenses;
    return $data;
}

function gete_parent($id) {
    $CI = & get_instance();
    $CI->load->model('account/report');
    $store = array();
        $where = array(
            'group.status' => 1,
            'group.deleted' => 0,
            'group.id' => $id
        );
        $group_name = $CI->report->getGrandParentid($where);
        if (!empty($group_name)) {
            $store[] = $group_name;
            gete_parent($group_name[0]['parent_id']);
        }

        return $store;
    }
    
function getTotalBalancePL($entries, $ladger_id,$group_name) {
        $totalBalance = 0;
        foreach ($entries AS $entry) {
            if ($ladger_id == $entry['ladger_id']) {
                if ($group_name == 'Assets') {
                    $totalBalance = $entry['balance_dr'] - str_replace('-', '', $entry['balance_cr']); 
                    if($entry['op_account'] == 'Dr'){
                        $totalBalance = $totalBalance + $entry['op_balance'];
                    }else{
                        $totalBalance = $totalBalance - str_replace('-', '',$entry['op_balance']);
                    }
                } else {
                    
                    $totalBalance = str_replace('-', '', $entry['balance_cr']) - $entry['balance_dr']; 
                    if($entry['op_account'] == 'Cr'){
                        $totalBalance = $totalBalance + str_replace('-', '',$entry['op_balance']);
                    }else{
                        $totalBalance = $totalBalance - $entry['op_balance'];
                    }
                }
            }
        }

        return $totalBalance;
    }
    
    function sortingt_parent_group_array_pl($arr, $searchKey,$income_ordering) {
        $final_array = array();
        $group_total = 0;
        $gross_total = 0;
        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $key => $value) {
                if ($key == $searchKey) {
                    foreach ($value as $group=>$val) {
                        foreach ($val as $sub) {
                            if ($sub['total_balance'] < 0) {
                                $cb = $sub['total_balance']; //15092916
                                //$cb = substr($sub['total_balance'], 1);
                                $group_total += $cb;
                                foreach($income_ordering AS $ord){
                                    if($ord['group_name'] == $group){
                                        $gross_total += $cb;
                                    }
                                }
                            } else {
                                $group_total += $sub['total_balance'];
                                foreach($income_ordering AS $ord){
                                    if($ord['group_name'] == $group){
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
