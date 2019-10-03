<style>
    .ui-autocomplete{z-index: 9999999!important;}
</style>
<?php
$opening_balance = 0;
$closing_balance = 0;
$total_closing_balance = 0;
$cr_balance = 0;
$dr_balance = 0;
$account_type = '';
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
            <form role="form" style="padding: 6px;" class="form-inline" action="<?php echo base_url('admin/ledger-statement'); ?>" method="get" id="settings_form" enctype="multipart/form-data"> 
                <div class="form-group">
                    <input type="text" placeholder="Select Ledger Name" class="form-control ledger" name="ledger_name" value=""  id="listfocus" >
                </div>  
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <i class="fa fa-clone"></i>Ledger Report</h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">                    
                    <div class="btn-group btn-svg">
                        <?php if($number_of_branch > 1): ?>
                        <!--somnath - if branch exists then the button will show otherwise not-->
                        <button id="select-branch" class="btn btn-default"  data-toggle="tooltip" data-placement="bottom" title="Select Branch"><i data-feather="layers"></i>
                        </button>
                        <?php endif; ?>
                        <a href="<?php echo base_url('admin/accounts-ledger-statement'); ?> " class="btn btn-default"><i class="fa fa-chevron-left"></i></a>                        
                        <button class="btn btn-default" id="details" ><i data-feather="list"></i></button>  
                        <button class="btn btn-default save-pdf" data-pdf="dpf_content"><i data-feather="mail"></i></button>
                        <button class="btn btn-default download-pdf"><i data-feather="printer"></i></button>
                        <button class="btn btn-default export-excel"><i data-feather="save"></i></button>
                        <!--<button class="btn btn-default printreport" onclick="printDiv('printreport');"><i class="fa fa-print"></i></button>-->                        
                    </div>   

                    <button id="button" class="myButton btn btn-settings btn-svg pull-right"><i data-feather="settings"></i></button>
                    <?php
                    // if (isset($_GET['starting_day']) && isset($_GET['ending_day'])) {
                    //     $from_date_arr = explode('-', $_GET['starting_day']);
                    //     $from_date = $from_date_arr[1] . '/' . $from_date_arr[2] . '/' . $from_date_arr[0];
                    //     $to_date_arr = explode('-', $_GET['ending_day']);
                    //     $to_date = $to_date_arr[1] . '/' . $to_date_arr[2] . '/' . $to_date_arr[0];
                    // }
                    ?>                
                    <!--<button id="pldaterange" class="btn btn-info btn-sm pldaterange pull-right"><i class="fa fa-calendar"></i></button>-->
                    <button id="daterange" class="btn btn-calendar btn-svg daterange pull-right" data-toggle="modal" data-target="#myModal"><i data-feather="calendar"></i></button>
                </div>                 
            </div> 
        </div> 
    </section>
    <section>
        <?php
        $ledger_name=  isset($_GET['ledger_name'])?$_GET['ledger_name']:'';
        $prev_breadcrumbs = $this->session->userdata('_breadcrumbs');
        $current_breadcrumbs = array($ledger_name => '/admin/ledger-statement.aspx?ledger_name='.$ledger_name);
        $breadcrumbs = array_merge($prev_breadcrumbs, $current_breadcrumbs);
        $this->session->set_userdata('_breadcrumbs', $breadcrumbs);
        foreach ($breadcrumbs as $k => $b) {
            $this->breadcrumbs->push($k, $b);
            if($k==$ledger_name){
                break;
            }
        }
        $this->breadcrumbs->show();
        ?>
    </section>
    <!-- Main content --> 
    <section class="content">
        <div class="row">
            <div class="col-xs-12">              
                <div class="box">   
                    <div class="box-body pdf_class" id="printreport" data-pdf="Ledger Report<?php echo (isset($_GET['ledger_name'])) ? ' - '.$_GET['ledger_name'] : '' ?>"> 
                        <div class="table-responsive">                    
                            <table class="table table-report">
                                <thead>
                                    <tr>
                                        <th colspan="7">
                                            <div class="row report-header-company-info">
                                                <div class="col-xs-12 text-center">
                                                    <h3><?php echo $company_details->company_name ?></h3>
                                                    <p><?php echo $company_details->street_address ?> <?php echo ($company_details->city_name) ? ', ' . $company_details->city_name : '' ?> <?php echo ($company_details->zip_code) ? ' - ' . $company_details->zip_code : '' ?><?php echo ($company_details->state_name) ? ', ' . $company_details->state_name : '' ?><?php echo ($company_details->country) ? ', ' . $company_details->country : '' ?>.<br>
                                                        Mobile: <?php echo $company_details->mobile ?>, Phone: <?php echo $company_details->telephone ?>,<br> Email: <?php echo $company_details->email ?></p>
                                                </div>
                                            </div>
                                            <div class="row report-header">
                                                <div class="col-xs-12">
                                                    <h3><?php echo (isset($_GET['ledger_name'])) ? $_GET['ledger_name'] : '' ?></h3>
                                                    <p><?php echo date('d-F-Y', strtotime($starting_day)) . ' to ' . date('d-F-Y', strtotime($ending_day)) ?></p>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="width-date">Date</th>                              
                                        <th class="width-100">Voucher No</th>                
                                        <th>Particulars</div></th>
                                        <th class="width-date">Type</th>
                                        <th class="width-100 text-right">Debit</th>                
                                        <th class="width-100 text-right">Credit</th>
                                        <th class="width-110 text-right">Current<br> Balance</th>
                                    </tr>            
                                </thead>

                                <tbody>
                                    <tr class="bold">
                                        <td style="text-align: left !important"><?php echo get_date_format($starting_day); ?></td> 
                                        <td></td>
                                        <td style="text-align: left !important">Opening Balance</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <?php
                                        if (isset($opening_bal) && count($opening_bal) > 0) {
                                            if ($opening_bal['account_type'] == 'Dr') {
                                                echo ' <td>' . '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) sprintf('%0.2f', $opening_bal['opening_balance']), $get_standard_format_data->decimal_places) . '</span>' . '</td>';
                                                $account_type = 'Dr';
                                            }

                                            if ($opening_bal['account_type'] == 'Cr') {
                                                echo '<td>' . '<span class="balence_show_' . ++$amount_field_count . '">' . number_format((float) sprintf('%0.2f', $opening_bal['opening_balance']), $get_standard_format_data->decimal_places) . '</span>' . '</td> ';
                                                $account_type = 'Cr';
                                            }
                                            $opening_balance = $opening_bal['opening_balance'];
                                        }
                                        ?>
                                <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) sprintf('%0.2f', $opening_bal['opening_balance']), $get_standard_format_data->decimal_places, '.', ''); ?>">

                                </tr>
                                <?php
                                if (isset($ledger_result)) {
                                    foreach ($ledger_result as $row) {
                                        if ($row['account'] == 'Dr') {
                                            $dr_balance += $row['balance'];
                                        }
                                        if ($row['account'] == 'Cr') {
                                            $cr_balance += $row['balance'];
                                        }
                                        ?>
                                        <tr>
                                            <!-- <td style="text-align: left !important"><?php echo date("d-m-Y", strtotime($row['create_date'])); ?></td>                     -->
                                            <td style="text-align: left !important"><?php echo get_date_format($row['create_date']); ?></td>                    
                                            <td style="text-align: left !important"><?php echo $row['entry_no']; ?></td>
                                            <!-- <td><?php //echo $row['ladger_name'];           ?></td> -->
                                            <td  style="text-align: left !important">
                                                <?php $invoice = [5,6,7,8,9,10,12,13,14]; ?>
                                                <?php if(in_array($row['entry_type_id'], $invoice)){ ?>
                                                    <a href="<?php echo base_url('transaction/invoice') . '.aspx/' . $row['id'] .'/'.$row['entry_type_id']; ?>">
                                                <?php }else{ ?>
                                                    <a href="<?php echo base_url('admin/trasaction-details') . '.aspx/' . $row['id']; ?>">
                                                <?php } ?>
                                                
                                                    <!-- <?php
                                                    // $led = array();
                                                    // $devit = json_decode($row['ledger_ids_by_accounts']);
                                                    // echo "<strong>Dr </strong>";
                                                    // for ($i = 0; $i < count($devit->Dr); $i++) {
                                                    //     echo $devit->Dr[$i];
                                                    //     if (count($devit->Dr) > 1) {
                                                    //         echo ' + ';
                                                    //     }
                                                    //     break;
                                                    // }
                                                    ?>
                                                    /
                                                    <?php
                                                    // echo "<strong>Cr </strong>";
                                                    // for ($i = 0; $i < count($devit->Cr); $i++) {
                                                    //     echo $devit->Cr[$i];
                                                    //     if (count($devit->Cr) > 1) {
                                                    //         echo ' + ';
                                                    //     }
                                                    //     break;
                                                    // }
                                                    ?> -->
                                                    <strong><?php echo $row['details'][0]->ledger_name . " [".$row['details'][0]->account_type . "] / " . $row['details'][1]->ledger_name . " [".$row['details'][1]->account_type . "]" ?></strong>
                                                </a>
                                            </td>
                                            <td style="text-align: left !important"><?php echo $row['type']; ?></td>                    
                                            <?php
                                            if ($row['account'] == 'Dr') {
                                                echo '<td>' . '<span class="balence_show_' . ++$amount_field_count . '">' . $this->price_format($row['balance']) . '</span>' . '</td><td></td>';
                                            }
                                            if ($row['account'] == 'Cr') {
                                                echo '<td></td><td>' . '<span class="balence_show_' . ++$amount_field_count . '">' . $this->price_format($row['balance']) . '</span>' . '</td>';
                                            }
                                            ?>
                                        <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) $row['balance'], $get_standard_format_data->decimal_places, '.', ''); ?>">
                                        <td>
                                            <?php
                                            if ($row['account'] == 'Dr') {
                                                $diff_drcr = $row['balance'];
                                            }
                                            if ($row['account'] == 'Cr') {
                                                $diff_drcr = -$row['balance'];
                                            }

                                            if ($diff_drcr >= 0) {
                                                if ($account_type == 'Dr') {
                                                    $closing_balance = $diff_drcr + $opening_balance;
                                                    $opening_balance = $closing_balance;
                                                    $account_type = 'Dr';
                                                    echo '<span class="balence_show_' . ++$amount_field_count . '">' . $this->price_format(abs($closing_balance)) . '</span>' . '(Dr)';
                                                    $closing_balance_hidd = sprintf('%0.2f', $closing_balance);
                                                }
                                                if ($account_type == 'Cr') {
                                                    $closing_balance = $diff_drcr - $opening_balance;

                                                    if ($closing_balance >= 0) {
                                                        $opening_balance = $closing_balance;
                                                        $account_type = 'Dr';
                                                        echo '<span class="balence_show_' . ++$amount_field_count . '">' . $this->price_format(abs($closing_balance)) . '</span>' . '(Dr)';
                                                        $closing_balance_hidd = sprintf('%0.2f', $closing_balance);
                                                    } else {
                                                        echo '<span class="balence_show_' . ++$amount_field_count . '">' . $this->price_format(abs($closing_balance)) . '</span>' . '(Cr)';
                                                        $closing_balance_hidd = str_replace('-', '', sprintf('%0.2f', $closing_balance));
                                                        $opening_balance = str_replace('-', '', sprintf('%0.2f', $closing_balance));
                                                        $account_type = 'Cr';
                                                    }
                                                }
                                            }

                                            if ($diff_drcr < 0) {
                                                if ($account_type == 'Cr') {
                                                    $closing_balance = str_replace('-', '', $diff_drcr) + $opening_balance;
                                                    $opening_balance = $closing_balance;
                                                    $account_type = 'Cr';
                                                    echo '<span class="balence_show_' . ++$amount_field_count . '">' . $this->price_format(abs($closing_balance)) . '</span>' . '(Cr)';
                                                    $closing_balance_hidd = sprintf('%0.2f', $closing_balance);
                                                }
                                                if ($account_type == 'Dr') {
                                                    $closing_balance = str_replace('-', '', $diff_drcr) - $opening_balance;
                                                    if ($closing_balance >= 0) {
                                                        echo '<span class="balence_show_' . ++$amount_field_count . '">' . $this->price_format(abs($closing_balance)) . '</span>' . '(Cr)';
                                                        $closing_balance_hidd = sprintf('%0.2f', $closing_balance);
                                                        $opening_balance = $closing_balance;
                                                        $account_type = 'Cr';
                                                    } else {
                                                        echo '<span class="balence_show_' . ++$amount_field_count . '">' . $this->price_format(abs($closing_balance)) . '</span>' . '(Dr)';
                                                        $closing_balance_hidd = str_replace('-', '', sprintf('%0.2f', $closing_balance));
                                                        $opening_balance = str_replace('-', '', sprintf('%0.2f', $closing_balance));
                                                        $account_type = 'Dr';
                                                    }
                                                }
                                            }
                                            ?>
                                            <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) $closing_balance_hidd, $get_standard_format_data->decimal_places, '.', ''); ?>">
                                        </td>
                                        </tr>
                                        <tr class="indent-1" style="display:none;">
                                            <td colspan="1"></td>                                            
                                            <td colspan="2" style="padding-top: 0 !important;padding-bottom: 0 !important">
                                                <table class="table table-ledger-report-details">
                                                    <tbody>
                                                            
                                                        <?php foreach ($row['details'] as $key => $value): ?>
                                                            <tr>            
                                                                <td><?php echo $value->ledger_name; ?></td>
                                                                <td><?php echo $this->price_format($value->balance) . " (". $value->account_type .")"; ?></td>
                                                            </tr>
                                                        <?php endforeach ?>
                                                        <?php if ($row['details'][0]->narration != ""): ?>
                                                            <tr>
                                                                <td>Narration</dt>
                                                                <td><?php echo $row['details'][0]->narration; ?></td>
                                                            </tr>                                               
                                                        <?php endif ?>
                                                    </tbody>                                                    
                                                </table>
                                            </td>
                                            <td colspan="4"></td>                                            
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="4">Grand Total</td>
                                        <td>
                                            <?php echo '<span class="balence_show_' . ++$amount_field_count . '">' . $this->price_format($dr_balance) . '</span>'; ?>
                                            <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) sprintf('%0.2f', $dr_balance), $get_standard_format_data->decimal_places, '.', ''); ?>">
                                        </td>
                                        <td>
                                            <?php echo '<span class="balence_show_' . ++$amount_field_count . '">' . $this->price_format($cr_balance) . '</span>'; ?>
                                            <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) sprintf('%0.2f', $cr_balance), $get_standard_format_data->decimal_places, '.', ''); ?>">
                                        </td>
                                        <td>
                                            <?php echo '<span class="balence_show_' . ++$amount_field_count . '">' . $this->price_format($opening_balance) . '</span>' . '(' . $account_type . ')'; ?>
                                            <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?php echo number_format((float) sprintf('%0.2f', $opening_balance), $get_standard_format_data->decimal_places, '.', ''); ?>">
                                        </td>               
                                    </tr>
                                </tfoot>

                            </table>


                        </div>
                    </div><!-- /.box-body -->
                </div>
            </div><!-- /row -->
        </div>
    </section>
</div><!-- /.col -->


<!-- date-range Modal -->
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
                    <div class="">              
                        <!--              <div class="col-lg-2">
                                        <label>From Date</label>
                                      </div>-->
                        <div class="col-lg-6">
                            <label>From Date</label>
                            <input type="text" placeholder="" name="from_date" id="from_date" maxlength="10" autofocus="" data-year="<?php echo $cur_financial_year; ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class=""> 
                        <!--              <div class="col-lg-2">
                                        <label>To date</label>
                                      </div>-->
                        <div class="col-lg-6">
                            <label>To date</label>
                            <input type="text" placeholder="" name="to_date" id="to_date" maxlength="10" data-year="<?php echo $cur_financial_year; ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="clearfix"></div>

                </form>
            </div>
            <div class="text-center" id="err-div" style="color: red;"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submitDaterange">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!-- Ledger Modal -->
<div class="modal fade" id="ledgerModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="width: 400px;margin: 0 auto;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">List of Ledgers</h4>
            </div>
            <div class="modal-body">
                <form role="form" class="form-inline" action="<?php echo base_url('admin/ledger-statement'); ?>" method="get" id="settings_form" enctype="multipart/form-data"> 
                    <input type="text" placeholder="Select Ledger Name" class="form-control ledger" name="ledger_name" value=""  id="listfocus" maxlength="50" style="width: 100%;">
                </form>
            </div>
            <div class="text-center" id="err-div" style="color: red;"></div>
            <div class="modal-footer">
                
            </div>
        </div>

    </div>
</div>

<?php
$query_string = explode('&', $_SERVER['QUERY_STRING']);
?>
<!--<script>
    $(function() {

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
            var staring_day = start.format('YYYY-MM-D');
            var ending_day = end.format('YYYY-MM-D');
            var query_string = "<?php echo isset($query_string[0]) ? $query_string[0] : '' ?>&";
            var loadUrl = "<?php echo base_url('admin/ledger-statement'); ?>";
            var url = loadUrl + '?' + query_string + 'staring_day=' + staring_day + '&ending_day=' + ending_day;
            window.location.href = url;

        });
    });
</script>-->
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
<script>
  $(document).ready(function() {
        
        $("#submitDaterange").on('click', function() {
            var from = $("#from_date").val();
            var to = $("#to_date").val();
            var ledger = "<?php echo $_GET['ledger_name']; ?>";
            ledger = ledger.replace(" ", "+");
            window.location.replace(window.location.pathname+'?ledger_name='+ledger+'&starting_day='+from+'&ending_day='+to);
        });

        $("#to_date").keypress(function(e) {
            if( e.which == 13 ) { // if enter is pressed
                $("#submitDaterange").click();
            }
        });

    // document.onkeyup=function(e){
        
    //   var e = e || window.event; // for IE to cover IEs window event-object
    //   if(e.altKey && e.ctrlKey && e.which == 68) { // alt+ctrl+d for pop-up the date-range
    //     $("#myModal").modal('show');
    //     return false;
    //   }else if(e.altKey && e.ctrlKey && e.which == 83) { // alt+ctrl+s for pop-up the search
    //     $("#searchModal").modal('show');
    //     return false;
    //   }
    // }
    
    $('#myModal').on('shown.bs.modal', function () {
        $('#from_date').focus();
    });

    $('#ledgerModal').on('shown.bs.modal', function () {
        $('input:text:visible:first').focus();
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

//          var to_date = $(this).val() + delimeter + (new Date).getFullYear(); // to_date with cuurent year
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
        $(".ledger").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: base_url + 'index.php/accounts/getLedgerDetails',
                    data: "ledger=" + request.term + '&ajax=1',
                    dataType: "json",
                    success: function(data) {
                        response(data);
                    },
                    error: function(request, error) {
                        alert('connection error. please try again.');
                    }
                });
            },
             select: function(event, ui) {
               //  window.location.href = base_url + 'admin/ledger-statement?ledger_name='+ui.item.label;
                },
            minLength: 0,
        }).focus(function() {
            $(this).autocomplete("search");
        });





    });
    
    $('#details').on('click', function() {
        $('.indent-1').slideToggle('slow');
    });
    
</script>