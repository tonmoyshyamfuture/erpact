             
                    <div class="box-body">
                        <div class="row report-header">
                            <div class="col-xs-12">
                                <h3>Balance Sheet</h3>
                                 <p><?php echo date('d-F-Y',  strtotime($staring_day)).' to '.date('d-F-Y',  strtotime($ending_day))?></p>
                            </div>
                        </div>

                        <div class="table-responsivex">
                            <table class="table table-report2" id="sample_1" role="grid" aria-describedby="sample_1_info">                                
                                <thead>                                
                                    <tr>
                                        <th style="width: 50%; text-align: center !important;">Liabilities</th>
                                        <th style="width: 50%; text-align: center !important;">Assets</th>
                                    </tr>
                                </thead>                            

                                <?php
                                //set total income, expenses, net loss, net profit variable
                                $libility_grand_total = 0;
                                $assets_grand_total = 0;
                                $net_profit = '';
                                $net_loss = '';
                                $assets_total = 0;
                                $liabilits_total = 0;
                                if (isset($liabilits[1])) {
                                    if ($liabilits[1] < 0) {
                                        $liabilits_total = $liabilits[1];
                                    } else {
                                        $liabilits_total = $liabilits[1];
                                    }
                                }
                                if (isset($assets[1])) {
                                    if ($assets[1] < 0) {
                                        $assets_total = $assets[1];
                                    } else {
                                        $assets_total = $assets[1];
                                    }
                                }
                                if ($opening_balance > 0) {
                                    $assets_total -= $opening_balance;
                                } else {
                                    $assets_total += str_replace('-', '', $opening_balance);
                                }              
                                if ($assets_total > $liabilits_total) {
                                    $profit = ($assets_total - $liabilits_total);
                                    if ($opening_pl['account_type'] == 'Cr') {
                                        $profit += $opening_pl['opening_balance'];
                                    } else {
                                        $profit -= str_replace('-', '', $opening_pl['opening_balance']);
                                    }
                                    $net_loss = '<tr>'
                                            . '<td style="font-style: italic;">' . $opening_pl['ladger_name'] . '</td>'
                                            . '<td class="width-130" >&nbsp;</td>'
                                            . '<td class="width-130 text-right" >' . number_format($profit, 2) . '</td>'
                                            . '</tr>'
                                            . '<tr class="trSub">'
                                            . '<td style="padding-left:10px !important">Opening Balance</td>'
                                            . '<td class="width-130 text-right" >' . number_format($opening_pl['opening_balance'], 2) . '</td>'
                                            . '<td class="width-130" >&nbsp;</td>'
                                            . '</tr>'
                                            . '<tr class="trSub">'
                                            . '<td style="padding-left:10px !important">Current Period</td>'
                                            . '<td class="width-130 text-right" >' . number_format($profit, 2) . '</td>'
                                            . '<td class="width-130" >&nbsp;</td>'
                                            . '</tr>'; //Net Profit
                                    $assets_grand_total = number_format($assets_total, 2);
                                    $libility_grand_total = number_format(($liabilits_total + $profit), 2);
                                }
                                if ($assets_total < $liabilits_total) {
                                    $loss = ($liabilits_total - $assets_total);
                                    if ($opening_pl['account_type'] == 'Dr') {
                                        $loss += $opening_pl['opening_balance'];
                                    } else {
                                        $loss -= str_replace('-', '', $opening_pl['opening_balance']);
                                    }
                                    $net_loss = '<tr>'
                                            . '<td style="font-style: italic;">' . $opening_pl['ladger_name'] . '</td>'
                                            . '<td class="width-130">&nbsp;</td>'
                                            . '<td class="width-130 text-right">(-)' . number_format($loss, 2) . '</td>'
                                            . '</tr>'
                                            . '<tr class="trSub">'
                                            . '<td style="padding-left:10px !important">Opening Balance</td>'
                                            . '<td class="width-130 text-right" >' . number_format($opening_pl['opening_balance'], 2) . '</td>'
                                            . '<td class="width-130" >&nbsp;</td>'
                                            . '</tr>'
                                            . '<tr class="trSub">'
                                            . '<td style="padding-left:10px !important">Current Period</td>'
                                            . '<td class="width-130 text-right" >' . number_format($loss, 2) . '</td>'
                                            . '<td class="width-130" >&nbsp;</td>'
                                            . '</tr>'; //Net Loss
                                    $assets_grand_total = number_format($assets_total, 2);
                                    $libility_grand_total = number_format(($liabilits_total - $loss), 2);
                                }
                                ?>
                                <tbody id="balance_sheet">
                                    <tr>
                                        <td class="border-r">
                                            <table class="table table-report2 subTable subTable1">
                                                <tbody>
                                                    <?php
                                                    if (isset($liabilits[0]) && !empty($liabilits[0])) {
                                                        if (count($liabilits[0]) > 0) {
                                                            $group_closing_balance = 0;
                                                            foreach ($liabilits[0] as $key => $value) {
                                                                ?>
                                                                <tr>
                                                                    <td><strong><?php echo $key; ?></strong></td>
                                                                    <td class="width-130">&nbsp;</td>
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
                                                                        <tr class="trSub">
                                                                            <td style="padding-left:10px !important"><?php echo $val['ladger_name']; ?></td>
                                                                            <td  class="width-130 text-right">
                                                                                <?php
                                                                                if ($val['total_balance'] < 0) {
//                                                                                echo number_format($val['current_balance'], 2);
//                                                                                echo '(-)'.number_format(substr($val['current_balance'], 1), 2);
                                                                                    echo '(-)' . number_format(substr($val['total_balance'], 1), 2);
                                                                                } else {
                                                                                    echo number_format($val['total_balance'], 2);
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td class="width-130">&nbsp;</td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <?php if ($opening_balance < 0) { ?>
                                                            <tr>
                                                                <td style="color:red;">Diff. in Opening Balance</td>
                                                                <td></td>
                                                                <td style="text-align: right;"><?php echo '(-)' . number_format(abs($opening_balance), 2); ?>
                                                                </td></tr>
                                                        <?php } elseif ($opening_balance > 0) { ?>
                                                            <tr>
                                                                <td style="color:red;">Diff. in Opening Balance</td>
                                                                <td></td>
                                                                <td style="text-align: right;"><?php echo number_format(abs($opening_balance), 2); ?></td>
                                                            </tr>
                                                        <?php }
                                                        ?>            
    <!--                                                        <tr><td style="color:red;">Diff. in Opening Balance</td><td style="text-align: right;"><?php
//                                                        if ($opening_balance < 0) {
//                                                            echo '(-)' . number_format(abs($opening_balance), 2);
//                                                        } elseif ($opening_balance >= 0) {
//                                                            echo number_format(abs($opening_balance), 2);
//                                                        }
                                                        ?></td></tr>-->

                                                        <?php
                                                        
                                                    }
                                                    echo $net_loss;
                                                    ?>
                                                </tbody>
                                            </table>
                                            <!--End:Table For Liabilities-->
                                        </td>
                                        <td>
                                            <!--Start:Table For Assets-->
                                            <table class="table table-report2 subTable subTable2">
                                                <tbody>
                                                    <?php
                                                    if (isset($assetes_ordering) && !empty($assetes_ordering)) {
                                                        foreach ($assetes_ordering as $ass_arr) {
                                                            if (isset($assets[0]) && !empty($assets[0])) {
                                                                if (count($assets[0]) > 0) {
                                                                    $group_closing_balance = 0;
                                                                    foreach ($assets[0] as $key => $value) {
                                                                        if ($ass_arr['group_name'] == $key) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><strong><?php echo $key; ?></strong></td>
                                                                                <td  class="width-130"></td>
                                                                                <td  class="width-130 text-right">
                                                                                    <strong>
                                                                                        <?php
                                                                                        // $group_closing_balance = number_format($value['group_dr_amount'] - $value['group_cr_amount'], 2);
                                                                                        $group_closing_balance = number_format($value['group_closing_balance_total'], 2);
                                                                                        if ($group_closing_balance < 0) {
//                                                                                echo $group_closing_balance;
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
                                                                                    <tr class="trSub">
                                                                                        <td style="padding-left:10px !important"><?php echo $val['ladger_name']; ?></td>
                                                                                        <td  class="width-130 text-right">
                                                                                            <?php
                                                                                            if ($val['total_balance'] < 0) {
//                                                                                echo number_format($val['current_balance'], 2);
                                                                                                echo '(-)' . number_format(substr($val['total_balance'], 1), 2);
//                                                                                echo '(-)'.number_format(substr($val['current_balance'], 1), 2);
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

//                                                        echo $net_profit;
//                                                        echo $net_loss;
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <!--End:Table For Assets-->
                                        </td>
                                    </tr>
                                <tfoot>
                                    <tr>
                                        <td><div class="pull-left">Total</div> <div class="pull-right"><?php echo $libility_grand_total; ?></div></td>                                        
                                        <td><div class="pull-left">Total</div> <div class="pull-right"><?php echo $assets_grand_total; ?></div></td>                                        
                                    </tr>
                                </tfoot>
                                </tbody>
                            </table>
                        </div>

                    </div><!-- /.box-body -->                    
           

