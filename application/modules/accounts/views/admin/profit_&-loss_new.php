<?php
$amount_field_count = 0;
?>
<div class="wrapper2">    
    <div class="side_toggle">        
        <div id="myDiv"><button class="btn btn-sm btn-danger myButton  btn-closePanel"><i class="fa fa-times"></i></button>
            <form style="padding:20px;">
                <div class="form-group">
                    <label for="">Scalling</label>
                    <select class="form-control" onchange="change_amount_format()" id="amount_format_input">
                        <option value="">None</option>
                        <option value="K">Thousand</option>
                        <option value="L">Lakh</option>
                        <option value="CR">Crore</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">decimal Point</label>
                    <input type="test" class="form-control" value="2" onkeyup="change_amount_format()" id="decimal_number_input" placeholder="1">
                </div>
                <div class="form-group"> 
                    <div class="radio">
                        <label><input type="radio" onclick="change_amount_format()" value="ro" name="decimal_type" checked="true">Round Off</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" onclick="change_amount_format()" value="ru" name="decimal_type">Round Up</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" onclick="change_amount_format()" value="rd" name="decimal_type">Round Down</label>
                    </div>
                    <div class="radio"></div>
                </div>



            </form>
        </div>
    </div>
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <i class="fa fa-bold"></i>Profit and Loss</h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <div class="btn-group btn-svg">
                    <button type="button" value="" class="btn btn-default blue-hoki" onclick="dateByProfitAndLoss();"><i class="fa fa-search"></i></button>
                    <button class="btn btn-default"><i class="fa fa-file-pdf-o"></i></button>                    
                    <button class="btn btn-default printreport" onclick="printDiv('printreport');"><i class="fa fa-print"></i></button>                    
                    </div>
                    <button id="button" class="myButton btn btn-settings btn-svg pull-right"><i data-feather="settings"></i></button>
                    <?php
                if (isset($_GET['staring_day']) && isset($_GET['ending_day'])) {
                    $from_date_arr = explode('-', $_GET['staring_day']);
                    $from_date = $from_date_arr[1] . '/' . $from_date_arr[2] . '/' . $from_date_arr[0];
                    $to_date_arr = explode('-', $_GET['ending_day']);
                    $to_date = $to_date_arr[1] . '/' . $to_date_arr[2] . '/' . $to_date_arr[0];
                }
                ?>
                <button id="pldaterange" class="btn btn-info btn-sm pldaterange pull-right"><i class="fa fa-calendar"></i></button>
                </div>
            </div> 
        </div> 
    </section>
    <!-- Main content --> 
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body" id="profit_loss"> 
                        <div class="row report-header-company-info">
                            <div class="col-xs-12 text-center">
                                <h3>Company Name</h3>
                                <p>150 A.J.C. Bose Road, Kolkata - 700001, WB, India.<br>
                                    Mobile: 98303030330, Phone: (033) 2440 2440,<br> Email: info@companyname.com, Website: www.companyname.com</p>
                            </div>
                        </div>
                        <div class="row report-header">
                            <div class="col-xs-12">
                                <h3>Profit & Loss</h3>
                                <p>01-Apr-2016 to 31-Mar-2017</p>
                            </div>
                        </div>                    

                        <div class="table-responsive" id="printreport" >
                            <table class="table table-report">                                
                                    <tr class="sub-childs">
                                        <td style="padding-left: 0 !important">
                                            <strong>PARTICULARS</strong>
                                        </td>                                        
                                        <td class="width-130 text-right"><strong>SUB-TOTAL</strong></td>
                                        <td class="width-130 text-right"><strong>TOTAL</strong></td>
                                    </tr>                                
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
                                $gross_total = '';
                                $gross_val = 0;
                                $gross_loss = '';

                                $income_gross_total = str_replace('-', '', $income[2]);
                                $expenses_gross_total = str_replace('-', '', $expenses[2]);
                                if ($income_gross_total > $expenses_gross_total) {
                                    $gross_val = $income_gross_total - $expenses_gross_total;
                                    $gross_profit = $gross_val;
                                    $gross_total = $income_gross_total;
                                } else {
                                    $gross_val = $expenses_gross_total - $income_gross_total;
                                    $gross_loss = $gross_val;
                                    $gross_total = $expenses_gross_total;
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
                                    $income_grand_total = $income_total + $gross_val;
                                    $expenses_grand_total = ($expenses_total + $profit + $gross_val);
                                }
                                if ($income_total < $expenses_total) {
                                    $loss = ($expenses_total - $income_total);
                                    $net_loss = '<td style="font-style: italic;"> Net Loss</td><td class="width-130">&nbsp;</td><td class="width-130 text-right">' . '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) ($expenses_total - $income_total), $get_standard_format_data->decimal_places) . '</span><input type="hidden" class="balence_hidden" data-id="' . $amount_field_count . '" value="' . number_format((float) ($expenses_total - $income_total), $get_standard_format_data->decimal_places, '.', '') . '">' . '</td></tr>';
                                    $income_grand_total = ($income_total + $loss + $gross_val);
                                    $expenses_grand_total = $expenses_total + $gross_val;
                                }
                                ?>
                                <tbody id="profit_and_loss">
                                    <tr>
                                        <td colspan="3" style="border: 0 !important; padding: 0 !important">
                                            <table class="table table-report2" style="border: 0 !important">
                                                <tbody>
                                                    <?php
                                                    if (isset($expenses_ordering) && !empty($expenses_ordering)) {
                                                        foreach ($expenses_ordering AS $exp) {
                                                            if (isset($expenses[0]) && !empty($expenses[0])) {
                                                                if (count($expenses[0]) > 0) {
                                                                    $group_closing_balanice = 0;
                                                                    foreach ($expenses[0] as $key => $value) {
                                                                        if ($exp['group_name'] == $key) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><strong><?php echo $key; ?></strong></td>
                                                                                <td  class="width-130"></td>
                                                                                <td  class="width-130 text-right">
                                                                                    <strong>
                                                                                        <?php
                                                                                        $group_closing_balance = $value['group_closing_balance_total'];
                                                                                        if ($group_closing_balance < 0) {
                                                                                            echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) substr($group_closing_balance, 1), $get_standard_format_data->decimal_places) . '</span>';
                                                                                            $group_closing_balance_hidd = substr($group_closing_balance, 1);
                                                                                        } else {
                                                                                            $group_closing_balance_hidd = $group_closing_balance;
                                                                                            echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $group_closing_balance, $get_standard_format_data->decimal_places) . '</span>';
                                                                                        }
                                                                                        ?>
                                                                                        <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) $group_closing_balance_hidd, $get_standard_format_data->decimal_places, '.', ''); ?>">
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
                                                                                            <?php echo $val['ladger_name']; ?>
                                                                                        </td>                                                                        
                                                                                        <td  class="width-130 text-right">
                                                                                            <?php
                                                                                            if ($val['total_balance'] < 0) {
                                                                                                $total_balance_hidd = substr($val['total_balance'], 1);
                                                                                                echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) substr($val['total_balance'], 1), $get_standard_format_data->decimal_places) . '</span>';
                                                                                            } else {
                                                                                                $total_balance_hidd = $val['total_balance'];
                                                                                                echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $val['total_balance'], $get_standard_format_data->decimal_places) . '</span>';
                                                                                            }
                                                                                            ?>
                                                                                            <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) $total_balance_hidd, $get_standard_format_data->decimal_places, '.', ''); ?>">
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
                                                    echo '<tr><td style="font-style: italic;">Gross Profit</td><td class="width-130" >&nbsp;</td><td class="width-130 text-right" >' . '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $gross_profit, $get_standard_format_data->decimal_places) . '</span><input type="hidden" class="balence_hidden" data-id="' . $amount_field_count . '" value="' . number_format((float) $gross_profit, $get_standard_format_data->decimal_places, '.', '') . '">' . '</td></tr>';
                                                    echo '<tr><td style="font-style: italic;">&nbsp;</td><td class="width-130" >&nbsp;</td><td class="width-130 text-right" ><u>' . '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $gross_total, $get_standard_format_data->decimal_places) . '</span><input type="hidden" class="balence_hidden" data-id="' . $amount_field_count . '" value="' . number_format((float) $gross_total, $get_standard_format_data->decimal_places, '.', '') . '">' . '</u></td></tr>';
                                                    echo '<tr><td style="font-style: italic;"> Gross Loss</td><td class="width-130" >&nbsp;</td><td class="width-130 text-right" >' . '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $gross_loss, $get_standard_format_data->decimal_places) . '</span><input type="hidden" class="balence_hidden" data-id="' . $amount_field_count . '" value="' . number_format((float) $gross_loss, $get_standard_format_data->decimal_places, '.', '') . '">' . '</td></tr>';
                                                    if (isset($expenses[0]) && !empty($expenses[0])) {
                                                        if (count($expenses[0]) > 0) {
                                                            $group_closing_balanice = 0;
                                                            foreach ($expenses[0] as $key => $value) {
                                                                if ($expenses_indirect[0]['group_name'] == $key) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><strong><?php echo $key; ?></strong></td>
                                                                        <td  class="width-130"></td>
                                                                        <td  class="width-130 text-right">
                                                                            <strong>
                                                                                <?php
                                                                                $group_closing_balance = $value['group_closing_balance_total'];
                                                                                if ($group_closing_balance < 0) {
                                                                                    echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) substr($group_closing_balance, 1), $get_standard_format_data->decimal_places) . '</span>';
                                                                                    $group_closing_balance_hidd = substr($group_closing_balance, 1);
                                                                                } else {
                                                                                    echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $group_closing_balance, $get_standard_format_data->decimal_places) . '</span>';
                                                                                    $group_closing_balance_hidd = $group_closing_balance;
                                                                                }
                                                                                ?>
                                                                                <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) $group_closing_balance_hidd, $get_standard_format_data->decimal_places, '.', ''); ?>">
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
                                                                                    <?php echo $val['ladger_name']; ?>
                                                                                </td>                                                                        
                                                                                <td  class="width-130 text-right">
                                                                                    <?php
                                                                                    if ($val['total_balance'] < 0) {
                                                                                        echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) substr($val['total_balance'], 1), $get_standard_format_data->decimal_places) . '</span>';
                                                                                        $total_balance_hidd = substr($val['total_balance'], 1);
                                                                                    } else {
                                                                                        echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $val['total_balance'], $get_standard_format_data->decimal_places) . '</span>';
                                                                                        $total_balance_hidd = $val['total_balance'];
                                                                                    }
                                                                                    ?>
                                                                                    <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) $total_balance_hidd, $get_standard_format_data->decimal_places, '.', ''); ?>">
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
                                        </td>
                                    </tr>
                                    
                                    <tr class="bold">
                                        <td colspan="3"><div class="pull-left">Total</div> <div class="pull-right">
                                                <?php echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $expenses_grand_total, $get_standard_format_data->decimal_places) . '</span>'; ?>
                                                <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) $expenses_grand_total, $get_standard_format_data->decimal_places, '.', ''); ?>">
                                            </div></td>
                                </tr>
                                <tr>                                        
                                    <td colspan="3" height="50"></td>
                                    </tr> 
                                    
                                    
                                    <tr role="row" class="sub-childs">
                                        <tr class="sub-childs">
                                            <td style="padding-left: 0 !important">
                                            <strong>PARTICULARS</strong>
                                        </td>         
                                        <td class="width-130 text-right"><strong>SUB-TOTAL</strong></td>
                                        <td class="width-130 text-right"><strong>TOTAL</strong></td>
                                    </tr>                                          
                                    <tr>

                                        <td colspan="3" style="border: 0 !important; padding: 0 !important">
                                            <table class="table table-report2" style="border: 0 !important">
                                                <tbody>
                                                    <?php
                                                    if (isset($income_ordering) && !empty($income_ordering)) {
                                                        foreach ($income_ordering AS $inc) {
                                                            if (isset($income[0]) && !empty($income[0])) {
                                                                if (count($income[0]) > 0) {
                                                                    $group_closing_balanice = 0;
                                                                    foreach ($income[0] as $key => $value) {
                                                                        if ($inc['group_name'] == $key) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><strong><?php echo $key; ?></strong></td>
                                                                                <td  class="width-130"></td>
                                                                                <td  class="width-130 text-right">
                                                                                    <strong>
                                                                                        <?php
                                                                                        $group_closing_balance = $value['group_closing_balance_total'];
                                                                                        if ($group_closing_balance < 0) {
                                                                                            echo '(-)' . '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) substr($group_closing_balance, 1), $get_standard_format_data->decimal_places) . '</span>';
                                                                                            $group_closing_balance_hidd = substr($group_closing_balance, 1);
                                                                                        } else {
                                                                                            echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $group_closing_balance, $get_standard_format_data->decimal_places) . '</span>';
                                                                                            $group_closing_balance_hidd = $group_closing_balance;
                                                                                        }
                                                                                        ?>
                                                                                        <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) $group_closing_balance_hidd, $get_standard_format_data->decimal_places, '.', ''); ?>">
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
                                                                                            <?php echo $val['ladger_name']; ?>
                                                                                        </td>                                                                        
                                                                                        <td  class="width-130 text-right">
                                                                                            <?php
                                                                                            if ($val['total_balance'] < 0) {
                                                                                                echo '(-)' . '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) substr($val['total_balance'], 1), $get_standard_format_data->decimal_places) . '</span>';
                                                                                                $total_balance_hidd = substr($val['total_balance'], 1);
                                                                                            } else {
                                                                                                echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $val['total_balance'], $get_standard_format_data->decimal_places) . '</span>';
                                                                                                $total_balance_hidd = $val['total_balance'];
                                                                                            }
                                                                                            ?>
                                                                                            <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) $total_balance_hidd, $get_standard_format_data->decimal_places, '.', ''); ?>">
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
                                                    echo '<tr><td style="font-style: italic;"> Gross Loss</td><td class="width-130" >&nbsp;</td><td class="width-130 text-right" >' . '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $gross_loss, $get_standard_format_data->decimal_places) . '</span><input type="hidden" class="balence_hidden" data-id="' . $amount_field_count . '" value="' . number_format((float) $gross_loss, $get_standard_format_data->decimal_places, '.', '') . '">' . '</td></tr>';
                                                    echo '<tr><td style="font-style: italic;">&nbsp;</td><td class="width-130" >&nbsp;</td><td class="width-130 text-right" ><u>' . '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $gross_total, $get_standard_format_data->decimal_places) . '</span><input type="hidden" class="balence_hidden" data-id="' . $amount_field_count . '" value="' . number_format((float) $gross_total, $get_standard_format_data->decimal_places, '.', '') . '">' . '</u></td></tr>';
                                                    echo '<tr><td style="font-style: italic;">Gross Profit</td><td class="width-130" >&nbsp;</td><td class="width-130 text-right" >' . '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $gross_profit, $get_standard_format_data->decimal_places) . '</span><input type="hidden" class="balence_hidden" data-id="' . $amount_field_count . '" value="' . number_format((float) $gross_profit, $get_standard_format_data->decimal_places, '.', '') . '">' . '</td></tr>';
                                                    ;
                                                    if (isset($income[0]) && !empty($income[0])) {
                                                        if (count($income[0]) > 0) {
                                                            $group_closing_balanice = 0;
                                                            foreach ($income[0] as $key => $value) {
                                                                if ($income_indirect[0]['group_name'] == $key) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><strong><?php echo $key; ?></strong></td>
                                                                        <td  class="width-130"></td>
                                                                        <td  class="width-130 text-right">
                                                                            <strong>
                                                                                <?php
                                                                                $group_closing_balance = $value['group_closing_balance_total'];
                                                                                if ($group_closing_balance < 0) {
                                                                                    echo '(-)' . '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) substr($group_closing_balance, 1), $get_standard_format_data->decimal_places) . '</span>';
                                                                                    $group_closing_balance_hidd = substr($group_closing_balance, 1);
                                                                                } else {
                                                                                    echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $group_closing_balance, $get_standard_format_data->decimal_places) . '</span>';
                                                                                    $group_closing_balance_hidd = $group_closing_balance;
                                                                                }
                                                                                ?>
                                                                                <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) $group_closing_balance_hidd, $get_standard_format_data->decimal_places, '.', ''); ?>">
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
                                                                                    <?php echo $val['ladger_name']; ?>
                                                                                </td>                                                                        
                                                                                <td  class="width-130 text-right">
                                                                                    <?php
                                                                                    if ($val['total_balance'] < 0) {
                                                                                        echo '(-)' . '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) substr($val['total_balance'], 1), $get_standard_format_data->decimal_places) . '</span>';
                                                                                        $total_balance_hidd = substr($val['total_balance'], 1);
                                                                                    } else {
                                                                                        echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $val['total_balance'], $get_standard_format_data->decimal_places) . '</span>';
                                                                                        $total_balance_hidd = $val['total_balance'];
                                                                                    }
                                                                                    ?>
                                                                                    <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) $total_balance_hidd, $get_standard_format_data->decimal_places, '.', ''); ?>">
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
                                
                                <tr class="bold border-b">
                                    <td colspan="3"><div class="pull-left">Total</div> <div class="pull-right">
                                            <?php echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) $income_grand_total, $get_standard_format_data->decimal_places) . '</span>'; ?>
                                            <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) $income_grand_total, $get_standard_format_data->decimal_places, '.', ''); ?>">
                                        </div></td>                                        
                                </tr>   
                                </tbody>                                
                            </table>
                        </div>

                    </div><!-- /.box-body -->                
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->

</div>

<script>
    $(function() {
        var loadUrl = "<?php echo site_url('accounts/reports/ajax_profit_loss_search_date'); ?>";
        $('.pldaterange').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        }, function(start, end) {
//        window.alert("Selected asd range: " + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            var staring_day = start.format('YYYY-MM-D');
            var ending_day = end.format('YYYY-MM-D');
            $.ajax({
                type: "POST",
                url: loadUrl,
                dataType: "html",
                data: "ajax=1&staring_day=" + staring_day + "&ending_day=" + ending_day,
                success: function(responseText) {
                    if (responseText != "") {
                        $("#profit_loss").html(responseText);
                    } else {
                        $("#profit_loss").html('');
                    }
                },
                error: function(jqXHR, exception) {
                    alert("error");
                    return false;
                }
            });



        });
    });
</script>
<script>
    $(".myButton").click(function() {

        // Set the effect type
        var effect = 'slide';

        // Set the options for the effect type chosen
        var options = {direction: $('.mySelect').val(0)};

        // Set the duration (default: 400 milliseconds)
        var duration = 500;

        $('#myDiv').toggle(effect, options, duration);
    });
</script>