<div class="row report-header">
    <div class="col-xs-12">
        <h3>Profit & Loss</h3>
        <p><?php if(isset($staring_day)) echo $staring_day;?> to <?php if(isset($ending_day)) echo $ending_day;?></p>
    </div>
</div>                    

<div class="table-responsive" >
<table class="table table-report2 dataTable" id="sample_1" role="grid" aria-describedby="sample_1_info">
    <thead>
        <tr role="row" class="sub-childs">
            <th width="50%" tabindex="0" aria-controls="sample_1" style="text-align: center !important">
                Particulars
            </th>
            <th style="text-align: center !important">
                Particulars
            </th>
        </tr>
    </thead>
    <?php
    //set total income, expenses, net loss, net profit variable
    $income_grand_total = 0;
    $expenses_grand_total = 0;
    $net_profit = '';
    $net_loss = '';
    $income_total = 0;
    $expenses_total = 0;
    $income_gross_total = 0;
    $expenses_gross_total = 0;
    $gross_profit = '';
    $gross_total ='';
    $gross_val = 0;
    $gross_loss = '';

    $income_gross_total = str_replace( '-','',$income[2]);
    $expenses_gross_total = str_replace( '-','',$expenses[2]);
    if($income_gross_total > $expenses_gross_total){
        $gross_val = $income_gross_total - $expenses_gross_total;
        $gross_profit = '<tr><td style="font-style: italic;">Gross Profit</td><td class="width-130" >&nbsp;</td><td class="width-130 text-right" >' . number_format($gross_val, 2) . '</td></tr>';
        $gross_total = '<tr><td style="font-style: italic;">&nbsp;</td><td class="width-130" >&nbsp;</td><td class="width-130 text-right" ><u>' . number_format($income_gross_total, 2) . '</u></td></tr>';
    }else{

        $gross_val =  $expenses_gross_total - $income_gross_total;
        $gross_loss = '<tr><td style="font-style: italic;"> Gross Loss</td><td class="width-130" >&nbsp;</td><td class="width-130 text-right" >' . number_format($gross_val, 2) . '</td></tr>';
        $gross_total = '<tr><td style="font-style: italic;">&nbsp;</td><td class="width-130" >&nbsp;</td><td class="width-130 text-right" ><u>' . number_format($expenses_gross_total, 2) . '</u></td></tr>';
//                                    
    }

    if (isset($income[1])) {
        if ($income[1] < 0) {
            $income_total = substr($income[1], 1);
        } else {
            $income_total = $income[1];
        }
    }
    if (isset($expenses[1])) {
        if ($expenses[1] < 0) {
            $expenses_total = substr($expenses[1], 1);
        } else {
            $expenses_total = $expenses[1];
        }
    }
    if ($income_total > $expenses_total) {
        $profit = ($income_total - $expenses_total);
        $net_profit = '<tr><td style="font-style: italic;"> Net Profit</td><td class="width-130" >&nbsp;</td><td class="width-130 text-right" >' . number_format(($income_total - $expenses_total), 2) . '</td></tr>';
        $income_grand_total = number_format($income_total + $gross_val, 2);
        $expenses_grand_total = number_format(($expenses_total + $profit +$gross_val), 2);
    }
    if ($income_total < $expenses_total) {
        $loss = ($expenses_total - $income_total);
        $net_loss = '<td style="font-style: italic;"> Net Loss</td><td class="width-130">&nbsp;</td><td class="width-130 text-right">' . number_format(($expenses_total - $income_total), 2) . '</td></tr>';
        $income_grand_total = number_format(($income_total + $loss + $gross_val), 2);
        $expenses_grand_total = number_format($expenses_total + $gross_val, 2);
    }




    ?>
    <tbody id="profit_and_loss">
        <tr>
            <td width="50%" class="border-r"><!--Start:Table For Income-->
                <table class="table table-report2">
                    <tbody>
<!--                                                        <tr> 
                            <td>
                                <strong>Expenses</strong>
                            </td>
                            <td style="text-align: right">
                                &nbsp;
                            </td>
                        </tr>Parent-->
                        <?php
                        if (isset($expenses_ordering) && !empty($expenses_ordering)) {
                            foreach ($expenses_ordering AS $exp) {
                                if (isset($expenses[0]) && !empty($expenses[0])) {
                                    if (count($expenses[0]) > 0) {
                                        $group_closing_balanice = 0;
                                        foreach ($expenses[0] as $key => $value) {
                                            if ($exp['group_name'] == $key ) {
                                                ?>
                                                <tr>
                                                    <td><strong><?php echo $key; ?></strong></td>
                                                    <td  class="width-130"></td>
                                                    <td  class="width-130 text-right">
                                                        <strong>
                                                            <?php
                                                            $group_closing_balance = $value['group_closing_balance_total'];
                                                            if ($group_closing_balance < 0) {
                                                                echo number_format(substr($group_closing_balance, 1), 2);
                                                            } else {
                                                                echo number_format($group_closing_balance, 2);
                                                            }
                                                            ?>
                                                        </strong>
                                                    </td>
                                                </tr>
                                                <?php
                                                if (array_key_exists('group_dr_amount', $value)) {
                                                    unset($value['group_dr_amount']);
                                                    unset($value['group_cr_amount']);
                                                }
                                                foreach ($value as $val) {
                                                    if ($val['ladger_name'] != "") {
                                                        ?>
                                                        <tr> 
                                                            <td style="padding-left: 10px !important;">
                                                                <?php echo  $val['ladger_name']; ?>
                                                            </td>                                                                        
                                                            <td  class="width-130 text-right">
                                                                <?php
                                                                if ($val['total_balance'] < 0) {
                                                                    echo number_format(substr($val['total_balance'], 1), 2);
                                                                } else {
                                                                    echo number_format($val['total_balance'], 2);
                                                                }
                                                                ?>
                                                            </td>
                                                            <td  class="width-130"></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }

                                            }

                                        }
                                    }
                                }
                            }
                        }
                        echo $gross_profit;
                        echo $gross_total;
                        echo $gross_loss;
                        if (isset($expenses[0]) && !empty($expenses[0])) {
                                    if (count($expenses[0]) > 0) {
                                        $group_closing_balanice = 0;
                                        foreach ($expenses[0] as $key => $value) {
                                            if ($expenses_indirect[0]['group_name'] == $key ) {
                                                ?>
                                                <tr>
                                                    <td><strong><?php echo $key; ?></strong></td>
                                                    <td  class="width-130"></td>
                                                    <td  class="width-130 text-right">
                                                        <strong>
                                                            <?php
                                                            $group_closing_balance = $value['group_closing_balance_total'];
                                                            if ($group_closing_balance < 0) {
                                                                echo number_format(substr($group_closing_balance, 1), 2);
                                                            } else {
                                                                echo number_format($group_closing_balance, 2);
                                                            }
                                                            ?>
                                                        </strong>
                                                    </td>
                                                </tr>
                                                <?php
                                                if (array_key_exists('group_dr_amount', $value)) {
                                                    unset($value['group_dr_amount']);
                                                    unset($value['group_cr_amount']);
                                                }
                                                foreach ($value as $val) {
                                                    if ($val['ladger_name'] != "") {
                                                        ?>
                                                        <tr> 
                                                            <td style="padding-left: 10px !important;">
                                                                <?php echo  $val['ladger_name']; ?>
                                                            </td>                                                                        
                                                            <td  class="width-130 text-right">
                                                                <?php
                                                                if ($val['total_balance'] < 0) {
                                                                    echo number_format(substr($val['total_balance'], 1), 2);
                                                                } else {
                                                                    echo number_format($val['total_balance'], 2);
                                                                }
                                                                ?>
                                                            </td>
                                                            <td  class="width-130"></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }

                                            }

                                        }
                                    }
                                }
                        echo $net_profit;
                        ?>
                    </tbody>
                </table>
            </td><!--End:Table For Income-->

            <td><!--Start:Table For Expenses-->
                <table class="table table-report2">
                    <tbody>
<!--                                                        <tr> 
                            <td>
                                <strong>Income</strong>
                            </td>
                            <td style="text-align: right">
                                <strong>Amount</strong>
                            </td>
                        </tr>Parent-->
                        <?php
                        if (isset($income_ordering) && !empty($income_ordering)) {
                            foreach ($income_ordering AS $inc) {
                        if (isset($income[0]) && !empty($income[0])) {
                            if (count($income[0]) > 0) {
                                $group_closing_balanice = 0;
                                foreach ($income[0] as $key => $value) {
                                    if ($inc['group_name'] == $key ) {
                                    ?>
                                    <tr>
                                        <td><strong><?php echo $key; ?></strong></td>
                                        <td  class="width-130"></td>
                                        <td  class="width-130 text-right">
                                            <strong>
                                                <?php
                                                $group_closing_balance = number_format($value['group_closing_balance_total'], 2);
                                                if ($group_closing_balance < 0) {
                                                    echo '(-)' . substr($group_closing_balance, 1);
                                                } else {
                                                    echo $group_closing_balance;
                                                }
                                                ?>
                                            </strong>
                                        </td>
                                    </tr>
                                    <?php
                                    if (array_key_exists('group_dr_amount', $value)) {
                                        unset($value['group_dr_amount']);
                                        unset($value['group_cr_amount']);
                                    }
                                    foreach ($value as $val) {
                                        if ($val['ladger_name'] != "") {
                                            ?>
                                            <tr> 
                                                <td style="padding-left: 10px !important;">
                                                    <?php echo  $val['ladger_name']; ?>
                                                </td>                                                                        
                                                <td  class="width-130 text-right">
                                                    <?php
                                                    if ($val['total_balance'] < 0) {
//                                                                                echo '<font color="red">'.number_format($val['current_balance'], 2).'</font>';
//                                                                                echo '(-)'.number_format(substr($val['current_balance'], 1), 2);
                                                        echo '(-)' . number_format(substr($val['total_balance'], 1), 2);
                                                    } else {
                                                        echo number_format($val['total_balance'], 2);
                                                    }
                                                    ?>
                                                </td>
                                                <td  class="width-130"></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                            }
                            }
                        }

                        }

                        }
                        echo $gross_loss;
                        echo $gross_total;
                        echo $gross_profit;
                        if (isset($income[0]) && !empty($income[0])) {
                            if (count($income[0]) > 0) {
                                $group_closing_balanice = 0;
                                foreach ($income[0] as $key => $value) {
                                    if ($income_indirect[0]['group_name'] == $key ) {
                                    ?>
                                    <tr>
                                        <td><strong><?php echo $key; ?></strong></td>
                                        <td  class="width-130"></td>
                                        <td  class="width-130 text-right">
                                            <strong>
                                                <?php
                                                $group_closing_balance = number_format($value['group_closing_balance_total'], 2);
                                                if ($group_closing_balance < 0) {
                                                    echo '(-)' . substr($group_closing_balance, 1);
                                                } else {
                                                    echo $group_closing_balance;
                                                }
                                                ?>
                                            </strong>
                                        </td>
                                    </tr>
                                    <?php
                                    if (array_key_exists('group_dr_amount', $value)) {
                                        unset($value['group_dr_amount']);
                                        unset($value['group_cr_amount']);
                                    }
                                    foreach ($value as $val) {
                                        if ($val['ladger_name'] != "") {
                                            ?>
                                            <tr> 
                                                <td style="padding-left: 10px !important;">
                                                    <?php echo  $val['ladger_name']; ?>
                                                </td>                                                                        
                                                <td  class="width-130 text-right">
                                                    <?php
                                                    if ($val['total_balance'] < 0) {
//                                                                                echo '<font color="red">'.number_format($val['current_balance'], 2).'</font>';
//                                                                                echo '(-)'.number_format(substr($val['current_balance'], 1), 2);
                                                        echo '(-)' . number_format(substr($val['total_balance'], 1), 2);
                                                    } else {
                                                        echo number_format($val['total_balance'], 2);
                                                    }
                                                    ?>
                                                </td>
                                                <td  class="width-130"></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                            }
                            }
                        }
                        echo $net_loss;
                        ?>            
                    </tbody>
                </table>
            </td><!--End:Table For Expenses-->
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td><div class="pull-left">Total</div> <div class="pull-right"><?php echo $expenses_grand_total; ?></div></td>                                        
            <td><div class="pull-left">Total</div> <div class="pull-right"><?php echo $income_grand_total; ?></div></td>                                        
        </tr>
    </tfoot>                                    
</table>
</div>