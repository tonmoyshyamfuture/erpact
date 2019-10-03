<style>
    .jconfirm{display: none !important}
    #chartdiv {
        width		: 100%;
        height		: 300px;
        font-size	: 16px;
    }
    #chartdiv a{display: none!important;}
    #purchase-chartdiv {
        width		: 100%;
        height		: 300px;
        font-size	: 16px;
    }
    #purchase-chartdiv a{display: none!important;}
    
    .ajax-loading{
                position: fixed;
                top: 0;
                left: 0;
                height:100%;
                width:100%;
                z-index: 9999999;
                background-image: url('http://loadinggif.com/images/image-selection/3.gif');
                background-color:#fff;
                 background-position:  center center;
                background-repeat: no-repeat;
                opacity: 0.7;
                display:block;
            }
            .content{margin-top: 0}
</style>
<?php
 $breadcrumbs= array('Home' => '/admin/dashboard');
 $this->session->set_userdata('_breadcrumbs', $breadcrumbs);
?>
<script src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/amcharts.js"></script>
<script src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/serial.js"></script>
<script src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/export.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/export.css" type="text/css" media="all" />
<script src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/light.js"></script>
<div class="wrapper2">
    <section class="content banner-db"> 
        <div class="welcomeadmin">
            <div class="row">
                <div class="col-md-12">
                    <h1>Hi <?php echo (strlen(get_from_session('fname')) > 20) ? substr(get_from_session('fname'), 0, 20) . '...' : get_from_session('fname'); ?></span>, here's the current status                    
                    </h1>
                    <h4 id="dashboard_daterange"><?php echo date('d-F-Y', strtotime($staring_day)); ?> to <?php //echo date('d-F-Y', strtotime($ending_day)); ?>
                        <?php echo date('d-F-Y', strtotime($this->session->userdata('current_ending_day'))); ?>
                    </h4>
                </div>                
            </div>  
            <img class="home-gst-ready" src="<?php echo site_url(); ?>assets/images/gst-ready.png">
            <button id="daterange" class="btn btn-calendar btn-svg daterange" data-toggle="modal" data-target="#myModal"><i data-feather="calendar"></i></button>
        </div>

        <div class="db-wrapper clearfix ">
            <div id="loader-div" class=""></div>
            <div id="box-html">
                <div class="row" style="margin-bottom: 20px;">
                <?php if (!empty($dashboard_setting) && $dashboard_setting->total_receivable == 1): ?>
                <div class="col-md-6">
                    <div class="box-dashboard">
                        <div class="header">
                            <h3>Total Receivable</h3>
                        </div>
                        <div class="details">
                            <div class="row">
                                <div class="col-xs-6">Total Receivable</div>
                                <div class="col-xs-6 text-right"><span><?php echo $this->price_format(abs($total_receivable)); ?></span></div>
                                <div class="col-xs-12">
                                    <div class="separator clearfix"></div>
                                </div>
                            </div>
                            <div class="row text-right">
                                <div class="col-xs-6 border-right">
                                    <p class="text-info lead">Current</p>
                                    <p><span><?php echo $this->price_format(abs($current_receivable)); ?></span></p>
                                </div>
                                <div class="col-xs-6">
                                    <p class="text-danger lead">Overdue</p>
                                    <p><span><?php echo $this->price_format(abs($overdue_receivable)); ?></span></p>
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif ?>
                <?php if (!empty($dashboard_setting) && $dashboard_setting->total_payable == 1): ?> 
                <div class="col-md-6">
                    <div class="box-dashboard">
                        <div class="header">
                            <h3>Total Payable</h3>
                        </div>
                        <div class="details">
                            <div class="row">
                                <div class="col-xs-6">Total Payable</div>
                                <div class="col-xs-6 text-right"><span><?php echo $this->price_format(abs($total_payable)); ?></span></div>
                                <div class="col-xs-12">
                                    <div class="separator clearfix"></div>
                                </div>
                            </div>
                            <div class="row text-right">
                                <div class="col-xs-6 border-right">
                                    <p class="text-info lead">Current</p>
                                    <p><span><?php echo $this->price_format(abs($current_payable)); ?></span></p>
                                </div>
                                <div class="col-xs-6">
                                    <p class="text-danger lead">Overdue</p>
                                    <p><span><?php echo $this->price_format(abs($overdue_payable)); ?></span></p>
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif ?>
            </div>


            <div class="row">
                <?php if (!empty($dashboard_setting) && $dashboard_setting->cash_flow == 1): ?> 
                <div class="<?php echo (!empty($dashboard_setting) && $dashboard_setting->fund_flow == 1) ? 'col-md-3' : 'col-md-6'; ?>">
                    <div class="box-dashboard">
                        <div class="header">
                            <h3>Cash Flow</h3>
                        </div>
                        <div class="details">                        
                            <div class="row text-right"> 

                                <div class="col-xs-12">                                    
                                    <p class="text-success lead">Inflow</p>
                                    <p><span><?php echo $this->price_format(abs($cash_flow_in)); ?></span></p>
                                    <p class="text-danger lead">Outflow</p>
                                    <p><span><?php echo $this->price_format(abs($cash_flow_out)); ?></span></p>
                                    <p class="text-info lead">Net-flow </p>
                                    <p><span><?php echo $this->price_format(abs($cash_net_flow)); ?></span></p>                                

                                </div>   

                            </div>
                        </div>
                    </div>
                </div>
                <?php endif ?>

                <?php if (!empty($dashboard_setting) && $dashboard_setting->fund_flow == 1): ?> 
                <div class="<?php echo (!empty($dashboard_setting) && $dashboard_setting->cash_flow == 1) ? 'col-md-3' : 'col-md-6'; ?>">
                    <div class="box-dashboard">
                        <div class="header">
                            <h3>Fund Flow</h3>
                        </div>
                        <div class="details">                        
                            <div class="row text-right">
                                <div class="col-xs-12">                                    
                                    <p class="text-success lead">Opening</p>
                                    <p><span><?php echo $this->price_format(abs($fand_flow_in)); ?></span></p>
                                    <p class="text-danger lead">Closing</p>
                                    <p><span><?php echo $this->price_format(abs($fand_flow_out)); ?></span></p>
                                    <p class="text-info lead">Balance</p>
                                    <p><span><?php echo $this->price_format(abs($fand_net_flow)); ?></span></p>                                

                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>  
                <?php endif ?> 
                <?php if (!empty($dashboard_setting) && $dashboard_setting->watchlist == 1): ?>             
                <div class="col-md-6">
                    <div class="box-dashboard">
                        <div class="header" style="position: relative">
                            <h3>Watchlist</h3>
                            <form class="form-inline" role="form" action="<?php echo site_url('admin/add-watchlist'); ?>" method="get" id="watchlist-form" style="position: absolute; top:-4px; right:0;">
                                <input type="text" placeholder="Select Group/Ledger" class="form-control" name="group_ledger" id="watchlist-input" style="min-height:28px; height:28px; padding: 5px 10px; line-height: 1; font-size: 90%;width:130px;">
                            </form>                            
                        </div>                        
                        <div class="details">
                            <div class="row">                                
                                    <?php if ($watch_list_arr): ?>
                                <div class="clearfix" id="watchlist_slimScroll">
                                <div class="col-xs-12">
                                    <table class="table table-watchlist">                                        
                                        <tbody>
                                            <?php
                                                foreach ($watch_list_arr as $row) {
                                                    ?>
                                                    <tr>
                                                        <?php
                                                        if ($row['type'] == 'group') {
                                                            ?>
                                                            <td><a href="<?php echo site_url('admin/groups-report-details') . '?group_name=' . $row['name']; ?>"><?php echo $row['name'] ?></a></td>
                                                            <?php
                                                        } else if ($row['type'] == 'ledger') {
                                                            ?>
                                                            <td><a href="<?php echo site_url('admin/ledger-statement') . '?ledger_name=' . $row['name']; ?>"><?php echo $row['name'] ?></a></td>
                                                            <?php
                                                        }
                                                        ?>
                                                        <td class="text-right"><?php echo $this->price_format($row['balence']) ?> <?php echo '(' . $row['balance_type'] . ')' ?>
                                                            <a href="#" onclick="delete_watchlist(this)" class="btn btn-xs btn-danger" data-account-type="<?php echo $row['account_type']; ?>" data-id="<?php echo $row['group_ledger_id']; ?>"><i class="fa fa-trash-o"></i></a>
                                                        </td>                                                        
                                                    </tr>
                                                    <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div> 
                                    </div>
                                    <?php else: ?>
                                <div class="col-xs-12 text-center">
                                    <img src="<?php echo base_url(); ?>assets/images/norecordfound.png" alt="Watchlist" style="height:156px; width: auto; margin: 0 auto" >
                                </div>
                                <?php endif; ?>                                
                            </div>                        
                        </div>
                    </div>
                </div>
                <?php endif ?>
            </div>

                </div>

            <?php if (!empty($dashboard_setting) && $dashboard_setting->sales_summary == 1): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="box-dashboard">
                        <div class="header">
                            <h3 style="display:inline-block;">Sales Summary</h3> 
                            <!--<button id="daterange" class="btn btn-info btn-sm daterange pull-right" style="margin-top:-5px;"><i class="fa fa-calendar"></i></button>-->
                        </div>
                        <div class="details" style="border-bottom:0 !important; padding-bottom: 5px">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div id="chartdiv"></div>
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>            
            </div>
            <?php endif ?>

            <?php if (!empty($dashboard_setting) && $dashboard_setting->purchase_summary == 1): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="box-dashboard">
                        <div class="header">
                            <h3 style="display:inline-block;">Purchase Summary</h3>
                            <!--<button style="margin-top:-5px;" id="purchase-daterange" class="btn btn-info btn-sm purchase-daterange pull-right"><i class="fa fa-calendar"></i></button>-->
                        </div>
                        <div class="details" style="border-bottom:0 !important; padding-bottom: 5px">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div id="purchase-chartdiv"></div>
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>            
            </div>
            <?php endif ?>
        </div>
        
        <!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        
      <!-- Modal content-->
      <div class="modal-content" style="width: 400px;margin: 0 auto;">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Enter Date Range</h4>
        </div>
        <div class="modal-body">
          <form action="" method="post">
            <div class="row">
              <div class="col-lg-6">
                  <label>From Date</label>
                  <input class="form-control" type="text" placeholder="" name="from_date" id="from_date" maxlength="10" autofocus="" data-year="<?php echo $cur_financial_year; ?>" autocomplete="off">
              </div>            
              <div class="col-lg-6">
                  <label>To Date</label>
                <input class="form-control" type="text" placeholder="" name="to_date" id="to_date" maxlength="10" data-year="<?php echo $cur_financial_year; ?>" autocomplete="off">
              </div>
            </div>            
          </form>
        </div>
          <div class="text-center" id="err-div" style="color: red;"></div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="submitDaterange" >Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
        <script>
              var startDate = '<?php echo date('m/d/Y', strtotime($staring_day)); ?>';
                var endDate = '<?php echo date('m/d/Y', strtotime($ending_day)); ?>';
                var maxDate = '<?php echo date('m/d/Y', strtotime($year_ending_day)); ?>';
                var minDate = '<?php echo date('m/d/Y', strtotime($year_staring_day)); ?>';
//            $(function() {
//                var loadUrl = "<?php echo site_url('admin/getAjaxDashboard'); ?>";
//                $('#daterange').daterangepicker({
//                    ranges: {
//                        'Today': [moment(), moment()],
//                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
//                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
//                        'This Month': [moment().startOf('month'), moment().endOf('month')],
//                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
//                    },
//                    startDate: startDate,
//            endDate: endDate,
//            maxDate: maxDate,
//            minDate: minDate
//                }, function(start, end) {
//                    var staring_day = start.format('YYYY-MM-D');
//                    var ending_day = end.format('YYYY-MM-D');
//                    $("#loader-div").addClass('ajax-loading');
//                    $.ajax({
//                        type: "POST",
//                        url: loadUrl,
//                        dataType: "json",
//                        data: {staring_day: staring_day, ending_day: ending_day},
//                        success: function(data) {
//                            $("#loader-div").removeClass('ajax-loading');
//                            if (data.res == 'success') {
//                                $("#box-html").html(data.html);
//                                //sales
//                                  var chart = AmCharts.makeChart("chartdiv", {
//                                "type": "serial",
//                                "theme": "light",
//                                "dataProvider": data.monthly_sales_report,
//                                "valueAxes": [{
//                                        "gridColor": "#FFFFFF",
//                                        "gridAlpha": 0.2,
//                                        "dashLength": 0
//                                    }],
//                                "gridAboveGraphs": true,
//                                "startDuration": 1,
//                                "graphs": [{
//                                        "balloonText": "[[category]]: <b>[[value]]</b>",
//                                        "fillAlphas": 0.8,
//                                        "lineAlpha": 0.2,
//                                        "type": "column",
//                                        "valueField": "closing"
//                                    }],
//                                "chartCursor": {
//                                    "categoryBalloonEnabled": false,
//                                    "cursorAlpha": 0,
//                                    "zoomable": false
//                                },
//                                "categoryField": "month",
//                                "categoryAxis": {
//                                    "gridPosition": "start",
//                                    "gridAlpha": 0,
//                                    "tickPosition": "start",
//                                    "tickLength": 20
//                                },
//                                "export": {
//                                    "enabled": false
//                                }
//
//                            });
//
//                          
//                                //end sales
//                                //purchase
//                                  var purchase_chart = AmCharts.makeChart("purchase-chartdiv", {
//                                "type": "serial",
//                                "theme": "light",
//                                "dataProvider": data.monthly_purchase_report,
//                                "valueAxes": [{
//                                        "gridColor": "#FFFFFF",
//                                        "gridAlpha": 0.2,
//                                        "dashLength": 0
//                                    }],
//                                "gridAboveGraphs": true,
//                                "startDuration": 1,
//                                "graphs": [{
//                                        "balloonText": "[[category]]: <b>[[value]]</b>",
//                                        "fillAlphas": 0.8,
//                                        "lineAlpha": 0.2,
//                                        "type": "column",
//                                        "valueField": "closing"
//                                    }],
//                                "chartCursor": {
//                                    "categoryBalloonEnabled": false,
//                                    "cursorAlpha": 0,
//                                    "zoomable": false
//                                },
//                                "categoryField": "month",
//                                "categoryAxis": {
//                                    "gridPosition": "start",
//                                    "gridAlpha": 0,
//                                    "tickPosition": "start",
//                                    "tickLength": 20
//                                },
//                                "export": {
//                                    "enabled": false
//                                }
//
//                            });
//
//                            function setDataSet(dataset_url) {
//                                AmCharts.loadFile(dataset_url, {}, function(data) {
//                                    chart.dataProvider = AmCharts.parseJSON(data);
//                                    chart.validateData();
//                                });
//                                AmCharts.loadFile(dataset_url, {}, function(data) {
//                                    purchase_chart.dataProvider = AmCharts.parseJSON(data);
//                                    purchase_chart.validateData();
//                                });
//                            }
//                                //end purchase
//                            }
//                        },
//                        error: function(jqXHR, exception) {
//                            alert("error occured");
//                            return false;
//                        }
//                    });
//
//
//
//                });
//            });
        </script>
        <script>

            $(document).ready(function() {
              
                //
                $.ajax({
                    method: "POST",
                    url: "<?php echo site_url('admin/getAjaxSalesDetails'); ?>",
                    data: {id: 37},
                    dataType: "json"
                }).done(function(data) {
                    var chart = AmCharts.makeChart("chartdiv", {
                        "type": "serial",
                        "theme": "light",
                        "dataProvider": data,
                        "valueAxes": [{
                                "gridColor": "#FFFFFF",
                                "gridAlpha": 0.2,
                                "dashLength": 0
                            }],
                        "gridAboveGraphs": true,
                        "startDuration": 1,
                        "graphs": [{
                                "balloonText": "[[category]]: <b>[[value]]</b>",
                                "fillAlphas": 0.7,
                                "lineAlpha": 0.2,
                                "type": "column",
                                "valueField": "closing",
                                "fillColors": "#4b83ee"
                            }],
                        "chartCursor": {
                            "categoryBalloonEnabled": false,
                            "cursorAlpha": 0,
                            "zoomable": false
                        },
                        "categoryField": "month",
                        "categoryAxis": {
                            "gridPosition": "start",
                            "gridAlpha": 0,
                            "tickPosition": "start",
                            "tickLength": 20
                        },
                        "export": {
                            "enabled": false
                        }
                    });

                    function setDataSet(dataset_url) {
                        AmCharts.loadFile(dataset_url, {}, function(data) {
                            chart.dataProvider = AmCharts.parseJSON(data);
                            chart.validateData();
                        });
                    }
                });

                //date range
//                $('.daterange').daterangepicker({
//                    ranges: {
//                        'Today': [moment(), moment()],
//                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
//                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
//                        'This Month': [moment().startOf('month'), moment().endOf('month')],
//                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
//                    },
//                    startDate: startDate,
//                    endDate: endDate,
//                    maxDate: maxDate,
//                    minDate: minDate
//                }, function(start, end) {
//                    var staring_day = start.format('YYYY-MM-D');
//                    var ending_day = end.format('YYYY-MM-D');
//                    $.ajax({
//                        type: "POST",
//                        url: '<?php echo site_url('admin/getAjaxSalesDetails'); ?>',
//                        dataType: "json",
//                        data: {staring_day: staring_day, ending_day: ending_day},
//                        success: function(data) {
//                            var chart = AmCharts.makeChart("chartdiv", {
//                                "type": "serial",
//                                "theme": "light",
//                                "dataProvider": data,
//                                "valueAxes": [{
//                                        "gridColor": "#FFFFFF",
//                                        "gridAlpha": 0.2,
//                                        "dashLength": 0
//                                    }],
//                                "gridAboveGraphs": true,
//                                "startDuration": 1,
//                                "graphs": [{
//                                        "balloonText": "[[category]]: <b>[[value]]</b>",
//                                        "fillAlphas": 0.8,
//                                        "lineAlpha": 0.2,
//                                        "type": "column",
//                                        "valueField": "closing"
//                                    }],
//                                "chartCursor": {
//                                    "categoryBalloonEnabled": false,
//                                    "cursorAlpha": 0,
//                                    "zoomable": false
//                                },
//                                "categoryField": "month",
//                                "categoryAxis": {
//                                    "gridPosition": "start",
//                                    "gridAlpha": 0,
//                                    "tickPosition": "start",
//                                    "tickLength": 20
//                                },
//                                "export": {
//                                    "enabled": false
//                                }
//
//                            });
//
//                            function setDataSet(dataset_url) {
//                                AmCharts.loadFile(dataset_url, {}, function(data) {
//                                    chart.dataProvider = AmCharts.parseJSON(data);
//                                    chart.validateData();
//                                });
//                            }
//                        },
//                        error: function(jqXHR, exception) {
//                            alert("error");
//                            return false;
//                        }
//                    });
//
//
//
//                });

                //purchase
                $.ajax({
                    method: "POST",
                    url: "<?php echo site_url('admin/getAjaxPurchaseDetails'); ?>",
                    data: {id: 37},
                    dataType: "json"
                }).done(function(data) {
                    var purchase_chart = AmCharts.makeChart("purchase-chartdiv", {
                        "type": "serial",
                        "theme": "light",
                        "dataProvider": data,
                        "valueAxes": [{
                                "gridColor": "#FFFFFF",
                                "gridAlpha": 0.2,
                                "dashLength": 0
                            }],
                        "gridAboveGraphs": true,
                        "startDuration": 1,
                        "graphs": [{
                                "balloonText": "[[category]]: <b>[[value]]</b>",
                                "fillAlphas": 0.7,
                                "lineAlpha": 0.2,
                                "type": "column",
                                "valueField": "closing",
                                "fillColors": "#4b83ee"
                            }],
                        "chartCursor": {
                            "categoryBalloonEnabled": false,
                            "cursorAlpha": 0,
                            "zoomable": false
                        },
                        "categoryField": "month",
                        "categoryAxis": {
                            "gridPosition": "start",
                            "gridAlpha": 0,
                            "tickPosition": "start",
                            "tickLength": 20
                        },
                        "export": {
                            "enabled": false
                        }

                    });

                    function setDataSet(dataset_url) {
                        AmCharts.loadFile(dataset_url, {}, function(data) {
                            purchase_chart.dataProvider = AmCharts.parseJSON(data);
                            purchase_chart.validateData();
                        });
                    }
                });

                //date range
                $('.purchase-daterange').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: startDate,
                    endDate: endDate,
                    maxDate: maxDate,
                    minDate: minDate
                }, function(start, end) {
                    var staring_day = start.format('YYYY-MM-D');
                    var ending_day = end.format('YYYY-MM-D');
                    $.ajax({
                        type: "POST",
                        url: '<?php echo site_url('admin/getAjaxPurchaseDetails'); ?>',
                        dataType: "json",
                        data: {staring_day: staring_day, ending_day: ending_day},
                        success: function(data) {
                            var purchase_chart = AmCharts.makeChart("purchase-chartdiv", {
                                "type": "serial",
                                "theme": "light",
                                "dataProvider": data,
                                "valueAxes": [{
                                        "gridColor": "#FFFFFF",
                                        "gridAlpha": 0.2,
                                        "dashLength": 0
                                    }],
                                "gridAboveGraphs": true,
                                "startDuration": 1,
                                "graphs": [{
                                        "balloonText": "[[category]]: <b>[[value]]</b>",
                                        "fillAlphas": 0.8,
                                        "lineAlpha": 0.2,
                                        "type": "column",
                                        "valueField": "closing"
                                    }],
                                "chartCursor": {
                                    "categoryBalloonEnabled": false,
                                    "cursorAlpha": 0,
                                    "zoomable": false
                                },
                                "categoryField": "month",
                                "categoryAxis": {
                                    "gridPosition": "start",
                                    "gridAlpha": 0,
                                    "tickPosition": "start",
                                    "tickLength": 20
                                },
                                "export": {
                                    "enabled": false
                                }

                            });

                            function setDataSet(dataset_url) {
                                AmCharts.loadFile(dataset_url, {}, function(data) {
                                    purchase_chart.dataProvider = AmCharts.parseJSON(data);
                                    purchase_chart.validateData();
                                });
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

    </section>
</div>

<script>
  $(document).ready(function() {
        
        $("#submitDaterange").on('click', function() {
            var from = $("#from_date").val();
            var to = $("#to_date").val();
            var from_date = from.replace(/[.\/]/g, '-');
            var to_date = to.replace(/[.\/]/g, '-');
            var arrStartDate = from_date.split("-");
            var arrEndDate = to_date.split("-");
            var staring_day = arrStartDate[2] + '-' + arrStartDate[1] + '-' + arrStartDate[0];
            var ending_day = arrEndDate[2] + '-' + arrEndDate[1] + '-' + arrEndDate[0];
            var loadUrl = "<?php echo site_url('admin/getAjaxDashboard'); ?>";
            $("#loader-div").addClass('ajax-loading');
                    $.ajax({
                        type: "POST",
                        url: loadUrl,
                        dataType: "json",
                        data: {staring_day: staring_day, ending_day: ending_day},
                        success: function(data) {
                            $("#loader-div").removeClass('ajax-loading');
                            $("#from_date").val('');
                            $("#to_date").val('');
                            $("#myModal").modal('hide');
                            if (data.res == 'success') {
                                $("#dashboard_daterange").html(from + " to " + to);
                                $("#box-html").html(data.html);
                                //sales
                                  var chart = AmCharts.makeChart("chartdiv", {
                                "type": "serial",
                                "theme": "light",
                                "dataProvider": data.monthly_sales_report,
                                "valueAxes": [{
                                        "gridColor": "#FFFFFF",
                                        "gridAlpha": 0.2,
                                        "dashLength": 0
                                    }],
                                "gridAboveGraphs": true,
                                "startDuration": 1,
                                "graphs": [{
                                        "balloonText": "[[category]]: <b>[[value]]</b>",
                                        "fillAlphas": 0.8,
                                        "lineAlpha": 0.2,
                                        "type": "column",
                                        "valueField": "closing"
                                    }],
                                "chartCursor": {
                                    "categoryBalloonEnabled": false,
                                    "cursorAlpha": 0,
                                    "zoomable": false
                                },
                                "categoryField": "month",
                                "categoryAxis": {
                                    "gridPosition": "start",
                                    "gridAlpha": 0,
                                    "tickPosition": "start",
                                    "tickLength": 20
                                },
                                "export": {
                                    "enabled": false
                                }

                            });

                          
                                //end sales
                                //purchase
                                  var purchase_chart = AmCharts.makeChart("purchase-chartdiv", {
                                "type": "serial",
                                "theme": "light",
                                "dataProvider": data.monthly_purchase_report,
                                "valueAxes": [{
                                        "gridColor": "#FFFFFF",
                                        "gridAlpha": 0.2,
                                        "dashLength": 0
                                    }],
                                "gridAboveGraphs": true,
                                "startDuration": 1,
                                "graphs": [{
                                        "balloonText": "[[category]]: <b>[[value]]</b>",
                                        "fillAlphas": 0.8,
                                        "lineAlpha": 0.2,
                                        "type": "column",
                                        "valueField": "closing"
                                    }],
                                "chartCursor": {
                                    "categoryBalloonEnabled": false,
                                    "cursorAlpha": 0,
                                    "zoomable": false
                                },
                                "categoryField": "month",
                                "categoryAxis": {
                                    "gridPosition": "start",
                                    "gridAlpha": 0,
                                    "tickPosition": "start",
                                    "tickLength": 20
                                },
                                "export": {
                                    "enabled": false
                                }

                            });

                            function setDataSet(dataset_url) {
                                AmCharts.loadFile(dataset_url, {}, function(data) {
                                    chart.dataProvider = AmCharts.parseJSON(data);
                                    chart.validateData();
                                });
                                AmCharts.loadFile(dataset_url, {}, function(data) {
                                    purchase_chart.dataProvider = AmCharts.parseJSON(data);
                                    purchase_chart.validateData();
                                });
                            }
                                //end purchase
                            }
                        },
                        error: function(jqXHR, exception) {
                            alert("error occured");
                            return false;
                        }
                    });
        });

        $("#to_date").keypress(function(e) {
            if(e.which == 13) { // if enter is pressed
                $("#submitDaterange").click();
            }
        });

    document.onkeyup=function(e){
      var e = e || window.event; // for IE to cover IEs window event-object
      if(e.altKey && e.ctrlKey && e.which == 68) { // alt+ctrl+d for pop-up the date-range
        $("#myModal").modal('show');
        return false;
      }
    }
    
    $('#myModal').on('shown.bs.modal', function () {
        $('#from_date').focus();
    });

    var monthDate = [0,31,28,31,30,31,30,31,31,30,31,30,31];
    var delimeter;

    $("#from_date").keyup(function() {
        
                
        var financial_year = $(this).data('year');
        var lastChar = $(this).val().substr($(this).val().length - 1);
        
        if ( ($(this).val().length ==1 || $(this).val().length == 4) && isNaN(lastChar)) {
            $(this).val( $(this).val().slice(0, -1) );
        }

        if( $(this).val().length == 2 && isNaN(lastChar)) {
            if(lastChar == "." || lastChar == "-" || lastChar == "/"){
                delimeter = lastChar;                
                var arrDate = $("#from_date").val().split(delimeter);
                $(this).val('0'+arrDate[0]+delimeter);
                
            }else{
               $(this).val( $(this).val().slice(0, -1) ); 
            }
        }
        
        if( $(this).val().length == 5 && isNaN(lastChar)) {
            if(lastChar == "." || lastChar == "-" || lastChar == "/"){ 
                var arrDate = $("#from_date").val().split(delimeter);
                $(this).val(arrDate[0]+delimeter+'0'+arrDate[1]);
                
            }else{
               $(this).val( $(this).val().slice(0, -1) ); 
            }
        }
        
        // separator should be (.),(/),(-)
        if( $(this).val().length == 3 || $(this).val().length == 6 ) {
            if(lastChar != "." && lastChar != "-" && lastChar != "/"){
                $(this).val( $(this).val().slice(0, -1) );
            }
        }

      // set the user choosen delimeter
      if($(this).val().length == 3){
        delimeter = $(this).val().substr(2);
      }

      if($(this).val().length == 2 && $(this).val() > 31){
        $(this).val(31);
        // $(this).val($(this).val() + '/');
      }else if($(this).val().length == 5){
        var arrStartDate = $("#from_date").val().split(delimeter);
        
        
        // month cannot be greater than 12
        if(arrStartDate[1] > 12){
          $(this).val( $(this).val().slice(0, -1) );
        }else{

          var month = arrStartDate[1];
          if( arrStartDate[1] < 10 ){
            arrStartDate[1] = month[month.length -1];
          }

          // you can not enter more days than a month can have,
          // like if you enter 31/11 then it automatically changes to 30/11
          // because last day of November is 30 
          if(arrStartDate[0] > monthDate[arrStartDate[1]] && arrStartDate[1] > 9){
            $(this).val( monthDate[arrStartDate[1]] + delimeter + arrStartDate[1] ); // if month is greater than 9 it will show as it is
          }else if(arrStartDate[0] > monthDate[arrStartDate[1]] && arrStartDate[1] < 9){
            $(this).val( monthDate[arrStartDate[1]] + delimeter +'0' + arrStartDate[1] ); // otherwise it will append to 0
          }
          
          $.ajax({
                url:"<?php echo base_url(); ?>admin/getCurrentFinancialYearForDateRange",
                type:"POST",
                data:{month:arrStartDate[1]},
                async: false,
                success: function(response){
                    financial_year = $.trim(response);
                }              
            });
          
//          $(this).val($(this).val() + delimeter + (new Date).getFullYear());
          $(this).val($(this).val() + delimeter + financial_year);
          $( "#to_date" ).focus();
        }
        


      }
    });


    $("#to_date").keyup(function() {
        
        var financial_year = $(this).data('year');
        var lastChar = $(this).val().substr($(this).val().length - 1);
        
        if ( ($(this).val().length ==1 || $(this).val().length == 4) && isNaN(lastChar)) {
            $(this).val( $(this).val().slice(0, -1) );
        }

        if( $(this).val().length == 2 && isNaN(lastChar)) {
            if(lastChar == "." || lastChar == "-" || lastChar == "/"){
                delimeter = lastChar;                
                var arrDate = $("#to_date").val().split(delimeter);
                $(this).val('0'+arrDate[0]+delimeter);
                
            }else{
               $(this).val( $(this).val().slice(0, -1) ); 
            }
        }
        
        if( $(this).val().length == 5 && isNaN(lastChar)) {
            if(lastChar == "." || lastChar == "-" || lastChar == "/"){           
                var arrDate = $("#to_date").val().split(delimeter);
                $(this).val(arrDate[0]+delimeter+'0'+arrDate[1]);
                
            }else{
               $(this).val( $(this).val().slice(0, -1) ); 
            }
        }

      // set the user choosen delimeter
      if($(this).val().length == 3){
        delimeter = $(this).val().substr(2);
      }

      if($(this).val().length == 2 && $(this).val() > 31){
        $(this).val(31);
        // $(this).val($(this).val() + '/');
      }else if($(this).val().length == 5){
        // $(this).val($(this).val() + '/' + (new Date).getFullYear());
        var arrEnd = $("#to_date").val().split(delimeter);
        var month = arrEnd[1];

        if(arrEnd[1] > 12){
          $(this).val( $(this).val().slice(0, -1) );
        }else{
            
          var month = arrEnd[1];
          if( arrEnd[1] < 10 ){
            arrEnd[1] = month[month.length -1];
          }

          // you can not enter more days than a month can have,
          // like if you enter 31/11 then it automatically changes to 30/11
          // because last day of November is 30 
          if( arrEnd[0] > monthDate[arrEnd[1]] && arrEnd[1] > 9 ){
            $(this).val( monthDate[arrEnd[1]] + delimeter + arrEnd[1] ); // if month is greater than 9 it will show as it is
            
          }else if(arrEnd[0] > monthDate[arrEnd[1]] && arrEnd[1] < 9){
            $(this).val( monthDate[arrEnd[1]] + delimeter + '0' + arrEnd[1] ); // otherwise it will append to 0
          }
          
          $.ajax({
                url:"<?php echo base_url(); ?>admin/getCurrentFinancialYearForDateRange",
                type:"POST",
                data:{month:arrEnd[1]},
                async: false,
                success: function(response){
                    financial_year = $.trim(response);
                }              
            });

          var to_date = $(this).val() + delimeter + financial_year; // to_date with cuurent year
          
          var arrStartDate = $("#from_date").val().split(delimeter);
          var date1 = new Date(arrStartDate[2], arrStartDate[1], arrStartDate[0]); // from_date
          var arrEndDate = to_date.split(delimeter);
          var date2 = new Date(arrEndDate[2], arrEndDate[1], arrEndDate[0]); // to_date
          var diff = date2 - date1;

          // difference between to_date and from_date

          var days = 0;
          if (diff > 0) {
           days = diff/(1000*60*60*24); 
          }

          // if date_difference > 0 to_date gets current year otherwise it gets immidiate next year
          if (days > 0 || $("#from_date").val() == "" ) {
//            $(this).val( $(this).val() + delimeter + (new Date).getFullYear() );
            $(this).val( $(this).val() + delimeter + financial_year );
          }else{
            $(this).val( $(this).val() + delimeter + (parseInt(financial_year)+1) );
          }
        }

        
      }
    });
  });
</script>

<script>
    $(function() {
        $("#watchlist-input").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: '<?php echo site_url('admin/getAllGroupLedgerList'); ?>',
                    data: "group=" + request.term + '&ajax=1',
                    dataType: "json",
                    success: function(data) {
                        if(data.res=='success'){
                            var words = request.term.split(' ');
                            var results = $.grep(data.product, function(name, index) {
                                var sentence = name.label.toLowerCase();

                                return words.every(function(word) {
                                    return sentence.indexOf(word.toLowerCase()) >= 0;
                                });
                            });
                            response(results);
                        }else if(data.res=='error'){
                            Command: toastr["error"](data.message);
                        }
                    },
                    error: function(request, error) {
                        console.log('connection error. please try again.');
                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                e.preventDefault() // <--- Prevent the value from being inserted.
                $(this).val(ui.item.value);
                $('#watchlist-form').submit();
            }
        }).focus(function() {
             $(this).autocomplete("search", "");
        });
    });
    
    
    function delete_watchlist(obj) {
        var id = $(obj).data('id');
        var account_type = $(obj).data('account-type');
        window.location.href = "<?php echo base_url(); ?>admin/delete_watchlist/"+account_type+"/"+id;
        console.log(account_type);
    }
    
</script>