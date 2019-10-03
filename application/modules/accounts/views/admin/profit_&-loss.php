<?php
$amount_field_count = 0;
?>
<?php
$receipt_total = 0;
$payment_total = 0;
?>
<div class="wrapper2">    
<!--    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <i class="fa fa-bold"></i>Profit and Loss</h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <div class="row">
                        <div class="col-xs-3">
                            <?php
//                            $date = array(
//                                'name' => 'from_date',
//                                'class' => 'form-control date-picker',
//                                'id' => 'from_date',
//                                'placeholder' => 'From Date'
//                            );
//                            echo form_input($date);
                            ?></div>
                        <div class="col-xs-3">
                         <?php
//                            $date = array(
//                                'name' => 'to_date',
//                                'class' => 'form-control date-picker',
//                                'id' => 'to_date',
//                                'placeholder' => 'To Date'
//                            );
//                            echo form_input($date);
                            ?></div>
                        
                        <div class="col-xs-3"><input type="button" value="Search" class="btn btn-primary blue-hoki" onclick="dateByProfitAndLoss();"></div>
                        <div class="col-xs-3">                    
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control pldaterange" id="pldaterange" type="text">
                            </div>
                        </div>
                    </div>                        
                </div>
            </div> 
        </div> 
    </section>-->
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
                    <label for="">Round</label>
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
                <div class="form-group">
                    <label for="">View Mode</label><br>
                    <a href="<?php echo base_url('admin/vertical-profit-loss'); ?>">Vertical</a>
                    <!--<a href="<?php echo base_url('admin/accounts-profit-loss'); ?>">Horizontal</a>-->
                </div>
            </form>
        </div>
    </div> 
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <i class="fa fa-bold"></i> Profit And Loss </h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">                    
                    <div class="btn-group btn-svg">
                        <button id="select-branch" class="btn btn-default"  data-toggle="tooltip" data-placement="bottom" title="Select Branch"><i data-feather="layers"></i></button> 
                        <button id="details"  class="btn btn-default"><i class="fa fa-list"></i></button>  
                        <button class="btn btn-default"><i class="fa fa-file-pdf-o"></i></button>
                        <!--<button class="btn btn-default printreport" onclick=""><i class="fa fa-print"></i></button>-->
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
                        <div class="row report-header">
                            <div class="col-xs-12">
                                <h3>Profit & Loss</h3>
                                <p>01-Apr-2016 to 31-Mar-2017</p>
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
            success: function (responseText) {
                if (responseText != "") {
                    $("#profit_loss").html(responseText);
                } else {
                    $("#profit_loss").html('');
                }
            },
            error: function (jqXHR, exception) {
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
