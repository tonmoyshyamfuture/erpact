<style type="text/css">
    .bold_red {
        background: #eeeeee;
        font-weight: bold;
        color: #dd3950;
    }
    
    .indent-2,.indent-3 {
    display: none;
}
</style>
<?php
$amount_field_count = 0;
?>
<?php
$receipt_total = 0;
$payment_total = 0;
?>
<div class="wrapper2">    
<!--   <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <i class="fa fa-bold"></i> Balance Sheet </h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <div class="row"> 
                        <div class="col-xs-6 col-md-6 text-right">
                            <button id="details" id="details" class="btn btn-sm btn-primary" data-toggle="tooltip" title="See the Report details up to 3rd level"><i class="fa fa-list"></i></button>  
                            <button class="btn btn-sm btn-warning" data-toggle="tooltip" title="Download Excel Report"><i class="fa fa-file-pdf-o"></i></button>
                            <button class="btn btn-sm btn-primary printreport" data-toggle="tooltip" title="Print Report"><i class="fa fa-print"></i></button>
                            <button class="btn btn-default btn-sm toggle-settings" data-toggle="tooltip" title="Settings"><i class="fa fa-gears"></i></button>
                        </div>   
                        <div class="col-xs-6 col-md-6">    
                          
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input class="form-control pldaterange" id="pldaterange" placeholder="filter by date range" type="text">

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
                    <a href="<?php echo base_url('admin/vertical-balance-sheet'); ?>">Vertical</a><br>
                    <!--<a href="<?php echo base_url('admin/accounts-balance-sheet'); ?>">Horizontal</a>-->
                </div>



            </form>
        </div>
    </div> 
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <i class="fa fa-bold"></i> Balance Sheet </h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">                    
                    <div class="btn-group btn-svg">
                        <button id="select-branch" class="btn btn-default"  data-toggle="tooltip" data-placement="bottom" title="Select Branch"><i data-feather="layers"></i></button> 
                        <button class="btn btn-default" id="details" ><i data-feather="list"></i></button>  
                        <button class="btn btn-default"><i class="fa fa-file-pdf-o"></i></button>
                        <button class="btn btn-default printreport" onclick=""><i class="fa fa-print"></i></button>
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
    <section>
        <?php
        $breadcrumbs=array(
            'Home'=>'/admin/dashboard',
            'Report'=>'#',
            'Business Overview'=>'aa',
            'Balance Sheet'=>'/admin/vertical-balance-sheet',
        );
        $this->session->set_userdata('_breadcrumbs',$breadcrumbs);
        foreach ($breadcrumbs as $k=>$b) {
        $this->breadcrumbs->push($k, $b);    
        }
        $this->breadcrumbs->show(); 
        ?>
    </section>
    <!-- Main content --> 
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box" id="box-html">              
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
                                if ($opening_balance >= 0) {
                                    $assets_total -= $opening_balance;
                                } else {
                                    $assets_total += str_replace('-', '', $opening_balance);
                                }
                                
                                //oping profit and loss including in Liabilites 27032017
                                if ($opening_pl['account_type'] == 'Cr') {
                                        $liabilits_total += $opening_pl['opening_balance'];
                                    } else {
                                        $liabilits_total -= $opening_pl['opening_balance'];
                                    }
                                    
                                if ($assets_total >= $liabilits_total) {
                                    $profit_current = ($assets_total - $liabilits_total);
                                    
                                    if ($opening_pl['account_type'] == 'Cr') {
                                        $profit = $profit_current +  $opening_pl['opening_balance'];
                                    } else {
                                        $profit = $profit_current - str_replace('-', '', $opening_pl['opening_balance']);
                                    }
                                
                                    $net_loss = '<tr>'
                                            . '<td style="font-style: italic;">' . $opening_pl['ladger_name'] . '</td>'
                                            . '<td class="width-130" >&nbsp;</td>'
                                            . '<td class="width-130 text-right"  ><strong>' . number_format($profit, 2) . '</strong></td>'
                                            . '</tr>'
                                            . '<tr class="trSub">'
                                            . '<td style="padding-left:10px !important">Opening Balance</td>'
                                            . '<td class="width-130 text-right" >' . number_format($opening_pl['opening_balance'], 2) . '</td>'
                                            . '<td class="width-130" >&nbsp;</td>'
                                            . '</tr>'
                                            . '<tr class="trSub">'
                                            . '<td style="padding-left:10px !important">Current Period</td>'
                                            . '<td class="width-130 text-right" >' . number_format($profit_current, 2) . '</td>'
                                            . '<td class="width-130" >&nbsp;</td>'
                                            . '</tr>'; //Net Profit
                                    $assets_grand_total = number_format($assets_total, 2);
                                    $libility_grand_total = number_format(($liabilits_total + $profit_current), 2);
                                }
                                if ($assets_total < $liabilits_total) {
                                    $loss_current = ($liabilits_total - $assets_total);
                                    if ($opening_pl['account_type'] == 'Dr') {
                                        $loss = $loss_current + $opening_pl['opening_balance'];
                                    } else {
                                        $loss =$loss_current - str_replace('-', '', $opening_pl['opening_balance']);
                                    }
                                    $net_loss = '<tr>'
                                            . '<td style="font-style: italic;">' . $opening_pl['ladger_name'] . '</td>'
                                            . '<td class="width-130">&nbsp;</td>'
                                            . '<td class="width-130 text-right"><strong>(-)' . number_format($loss, 2) . '</strong></td>'
                                            . '</tr>'
                                            . '<tr class="trSub">'
                                            . '<td style="padding-left:10px !important">Opening Balance</td>'
                                            . '<td class="width-130 text-right" >' . number_format($opening_pl['opening_balance'], 2) . '</td>'
                                            . '<td class="width-130" >&nbsp;</td>'
                                            . '</tr>'
                                            . '<tr class="trSub">'
                                            . '<td style="padding-left:10px !important">Current Period</td>'
                                            . '<td class="width-130 text-right" >' . number_format($loss_current, 2) . '</td>'
                                            . '<td class="width-130" >&nbsp;</td>'
                                            . '</tr>'; //Net Loss
                                    $assets_grand_total = number_format($assets_total, 2);
                                    $libility_grand_total = number_format(($liabilits_total - $loss_current), 2);
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
                                                        echo $net_loss;
                                                    }
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
//                                                            echo '<pre>';
//                                                            print_r($assets);
//                                                            die();

                                                            if (isset($assets[0]) && !empty($assets[0])) {
                                                                if (count($assets[0]) > 0) {
                                                                    $group_closing_balance = 0;
                                                                    foreach ($assets[0] as $key => $value) {
//                                                                        echo $key.'<br>';
                                                                        if ($ass_arr['group_name'] == $key) {
//                                                                            echo $ass_arr['group_name'].'<br>';
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
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->

</div>

<script>
    $(function () {
        var loadUrl = "<?php echo site_url('accounts/reports/ajax_balance_sheet_by_date'); ?>";
        $('.pldaterange').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            maxDate: '03/31/2017',
            minDate: '04/01/2016'
        }, function (start, end) {
            var staring_day = start.format('YYYY-MM-D');
            var ending_day = end.format('YYYY-MM-D');
            $.ajax({
                type: "POST",
                url: loadUrl,
                dataType: "json",
                data: {staring_day: staring_day, ending_day: ending_day},
                success: function (data) {
                    if (data.res == 'success') {
                        $("#box-html").html(data.html);
                    }
                },
                error: function (jqXHR, exception) {
                    alert("error occured");
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
