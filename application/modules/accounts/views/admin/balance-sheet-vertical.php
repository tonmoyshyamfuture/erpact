<style type="text/css">
    .bold_red {background: #eeeeee;font-weight: bold;color: #dd3950;}
    .indent-2,.indent-3 {display: none;}
</style>
<?php
$amount_field_count = 0;
?>
<?php
$receipt_total = 0;
$payment_total = 0;
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
                    <!--<a href="<?php echo base_url('admin/vertical-balance-sheet'); ?>">Vertical</a><br>-->
                    <a href="<?php echo base_url('admin/accounts-balance-sheet'); ?>">Horizontal</a>
                </div>



            </form>
            
            <!-- somnath - inventory options-->
            <form style="padding:20px;">
                <div class="form-group">
                    <label>Valuation Method</label>
                    <div class="radio">
                        <label><input type="radio" onclick="window.location.replace(window.location.pathname+'?method=1')" value="A" name="inventory_method" checked="true">Average Cost</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" onclick="window.location.replace(window.location.pathname+'?method=2')" value="LS" name="inventory_method"<?php if ($this->session->userdata('inventory_method') && $this->session->userdata('inventory_method') == 2) { echo "checked";} ?>>Last Sales Price</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" onclick="window.location.replace(window.location.pathname+'?method=3')" value="LP" name="inventory_method"<?php if ($this->session->userdata('inventory_method') && $this->session->userdata('inventory_method') == 3) { echo "checked";} ?>>Last Purchase Price</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" onclick="window.location.replace(window.location.pathname+'?method=4')" value="ZC" name="inventory_method"<?php if ($this->session->userdata('inventory_method') && $this->session->userdata('inventory_method') == 4) { echo "checked";} ?>>Zero Cost/Price</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" onclick="window.location.replace(window.location.pathname+'?&method=5')" value="FIFO" name="inventory_method"<?php if (($this->session->userdata('inventory_method')) && $this->session->userdata('inventory_method') == 5) { echo "checked";} ?>>FIFO</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" onclick="window.location.replace(window.location.pathname+'?&method=6')" value="LIFO" name="inventory_method"<?php if (($this->session->userdata('inventory_method')) && $this->session->userdata('inventory_method') == 6) { echo "checked";} ?>>LIFO</label>
                    </div>
                </div>
            </form>
            
        </div>
    </div>   
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <i class="fa fa-th"></i> Balance Sheet</h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">                    
                    <div class="btn-group btn-svg">
                        <?php if($number_of_branch > 1): ?>
                        <!--somnath - if branch exists then the button will show otherwise not-->
                            <button id="select-branch" class="btn btn-default"  data-toggle="tooltip" data-placement="bottom" title="Select Branch"><i data-feather="layers"></i></button> 
                        <?php endif; ?>
                        <button class="btn btn-default" id="details" ><i data-feather="list"></i></button>  
                        <button class="btn btn-default save-pdf" data-pdf="balance_sheet_vertical"><i data-feather="mail"></i></button>
                        <button class="btn btn-default download-pdf"><i data-feather="printer"></i></button>
                        <button class="btn btn-default export-excel"><i data-feather="save"></i></button>
                        <!--<button class="btn btn-default printreport" onclick="printDiv('printreport');"><i class="fa fa-print"></i></button>-->
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
                <!--<button id="pldaterange" class="btn btn-info btn-sm pldaterange pull-right"><i class="fa fa-calendar"></i></button>-->
                    <button id="daterange" class="btn btn-calendar btn-svg daterange pull-right" data-toggle="modal" data-target="#myModal"><i data-feather="calendar"></i></button>
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
              <?php 
//                $total_opening_balance = 0;
//                $total_debit_balance = 0;
//                $total_credit_balance = 0;
                $total_debit_closing_balance = 0;
                $total_credit_closing_balance = 0;
                $total_debit_opening_balance = 0;
                $total_credit_opening_balance = 0;
              ?>
              <div class="box">                
                  <div class="box-body pdf_class" id="printreport" data-pdf="Balance Sheet">
                      <div class="table-responsive">
                      <table class="table table-balancesheet-vertical table-report nomargin">
                          <thead>
                              <tr>
                                  <th colspan="3">
                                      <div class="row report-header-company-info">                        
                                        <?= $this->company_address(); ?>                            
                                    </div>
                                  <div class="row report-header">
                                        <div class="col-xs-12">
                                            <h3>Balance Sheet</h3>
                                            <p><?php echo date('d-F-Y',  strtotime($staring_day)).' to '.date('d-F-Y',  strtotime($ending_day))?></p>
                                        </div>
                                    </div>
                                  </th>
                              </tr>
                                <tr>                                    
                                    <th >PARTICULARS</th>
                                    <th  class="width-130 text-right">Amount(Rs)</th>
                                    <th  class="width-130 text-right">Amount(Rs.)</th>                                
                                
                                </tr>
                            </thead>
                            <tbody>

                                <?php 
                                    if(isset($trial_balance_arr) && !empty($trial_balance_arr)){
                                        foreach ($trial_balance_arr as $trial_balance) { ?>
                                            <?php 
                                                if($trial_balance['level'] == 'level_first' && $trial_balance['type'] == 'group'){
                                                    echo '<tr  class="indent-1 bold">';
                                                }
                                                if($trial_balance['level'] == 'level_first' && $trial_balance['type'] == 'ledger'){
                                                    echo '<tr  class="indent-1">';
                                                }
                                                if($trial_balance['level'] == 'level_second' && $trial_balance['type'] == 'ledger'){
                                                    echo '<tr  class="indent-2">';
                                                }
                                                if($trial_balance['level'] == 'level_second' && $trial_balance['type'] == 'group'){
                                                    echo '<tr  class="indent-2 bold">';
                                                }
                                                 if($trial_balance['level'] == 'level_second' && $trial_balance['type'] == 'stock'){
                                                    echo '<tr  class="indent-2 bold">';
                                                }
                                                if($trial_balance['level'] == 'level_third' && $trial_balance['type'] == 'ledger'){
                                                    echo '<tr  class="indent-3">';
                                                }
                                                if($trial_balance['level'] == 'level_third' && $trial_balance['type'] == 'group'){
                                                    echo '<tr  class="indent-3 bold">';
                                                }
                                                 if($trial_balance['level'] == 'level_first' && $trial_balance['type'] == 'other'){
                                                    echo '<tr  class="indent-1 bold">';
                                                }
                                                if($trial_balance['level'] == 'level_second' && $trial_balance['type'] == 'other'){
                                                    echo '<tr  class="indent-2">';
                                                }
                                            ?>
                                            
                                            <td>
                                                <?php if($trial_balance['type'] == 'ledger') { ?>
                                                    <a href="<?php echo (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']!='')?site_url('admin/monthly-report') . "?id=" . $trial_balance['id'].'&'.$_SERVER['QUERY_STRING']:site_url('admin/monthly-report') . "?id=" . $trial_balance['id']; ?>"><?php echo $trial_balance['name']; ?></a>
                                                <?php }elseif($trial_balance['type'] == 'group'){ ?>
                                                    <?php
                                                    if(isset($_GET['id']) && isset($_SERVER['QUERY_STRING']) && isset($_GET['staring_day'])  && isset($_GET['ending_day'])){
                                                     $url_arr=  explode('&', $_SERVER['QUERY_STRING']);
                                                     $url_params="?id=" . $trial_balance['id'].'&'.$url_arr[1].'&'.$url_arr[2];
                                                    }else if(!isset($_GET['id']) && isset($_SERVER['QUERY_STRING'])){
                                                     $url_params= "?id=" . $trial_balance['id'].'&'.$_SERVER['QUERY_STRING'];  
                                                    }else if(isset($_GET['id']) && !isset($_GET['staring_day']) && !isset($_GET['ending_day'])){
                                                     $url_params= "?id=" . $trial_balance['id'];  
                                                    }
                                                    ?>
                                                    <a href="<?php echo (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']!='')?site_url('admin/accounts-trial-balance') .$url_params:site_url('admin/accounts-trial-balance') . "?id=" . $trial_balance['id']; ?>"><?php echo $trial_balance['name']; ?></a>
                                                <?php }elseif ($trial_balance['type'] == 'stock') { ?>
                                                            <a href="<?php echo base_url('admin/stock-summary'); ?>"><?php echo $trial_balance['name']; ?></a>    
                                                    <?php  }else{
                                                        if($trial_balance['name']=='Total'){
                                                         echo '<strong class="total">'. $trial_balance['name'].'</strong>';
                                                        }else{
                                                         echo '<strong>'. $trial_balance['name'].'</strong>';
                                                        }
                                                    } ?>
                                            </td>
                                                
                                            <td class="width-130">
                                                <?php
                                                    if($trial_balance['level'] != 'level_first' && ($trial_balance['type'] == 'group' || $trial_balance['type'] == 'ledger' || $trial_balance['type'] == 'other' || $trial_balance['type'] == 'stock')){
//                                                       echo ($trial_balance['account_type'] == 'Dr' && $trial_balance['site'] == 'income')? '(-)'.sprintf('%0.2f',$trial_balance['amount']): sprintf('%0.2f',$trial_balance['amount']);
                                                        //echo  $this->price_format($trial_balance['amount']);
                                                        
                                                        // somnath - round off functionality implemented
                                                        
                                                        $priceAmount = $trial_balance['amount'];
                                                        $priceAmount = number_format($priceAmount, 2, ".", ",");
                                                        echo "<span class='rate_show_".++$amount_field_count."'>"; ?>
                                                        <?php echo $this->price_format($trial_balance['amount']); ?>
                                                        <?= "</span>"; ?>
                                                        <input type="hidden" class="rate_hidden" data-rate-id="<?php echo $amount_field_count; ?>" value="<?php echo $trial_balance['amount']; ?>"> 
                                                        <?php
                                                    }
                                                ?>
                                                
                                            </td>
                                            <td class="width-130">
                                                    <?php
                                                        if($trial_balance['level'] == 'level_first' && ($trial_balance['type'] == 'group' || $trial_balance['type'] == 'ledger' || $trial_balance['type'] == 'other')){
//                                                           
                                                            //echo  $this->price_format($trial_balance['amount']);
                                                            
                                                            // somnath - round off functionality implemented
                                                            
                                                            $priceAmount = $trial_balance['amount'];
                                                            $priceAmount = number_format($priceAmount, 2, ".", ",");
                                                            echo "<span class='rate_show_".++$amount_field_count."'>"; ?>
                                                            
                                                            <?php 
//                                                                if( $trial_balance['id'] == 18 && $trial_balance['type'] == 'group' && $trial_balance['account_type'] == 'Dr'){
//                                                                    echo '(-) '.$priceAmount; 
//                                                                }else{
                                                                    echo $this->price_format($trial_balance['amount']); 
//                                                                }
                                                                
                                                            ?>
                                                            <?= "</span>"; ?>
                                                            <input type="hidden" class="rate_hidden" data-rate-id="<?php echo $amount_field_count; ?>" value="<?php echo $trial_balance['amount']; ?>"> 
                                                            <?php
                                                        }
//                                                       

                                                     ?>
                                                </td>
                                            </tr>
                                <?php     }
                                    }
                                ?>
                            
                            </tbody>
                            
                      </table>
                          <div class="table-responsive">
                  </div>
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
     
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
            <button type="button" class="btn btn-primary" id="submitDaterange" >Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<script>
    $('#details').on('click',function(){
        $('.indent-2,.indent-3').slideToggle('slow');
    })
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
<script>
  $(document).ready(function() {
        
        $("#submitDaterange").on('click', function() {
            var from = $("#from_date").val();
            var to = $("#to_date").val();
            var from_date = from.replace(/[.\/]/g, '-');
            var to_date = to.replace(/[.\/]/g, '-');
            window.location.replace(window.location.pathname+'?staring_day='+from_date+'&ending_day='+to_date);
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
          if (days > 0 || $("#from_date").val() == "") {
//            $(this).val( $(this).val() + delimeter + (new Date).getFullYear() );
            $(this).val( $(this).val() + delimeter + financial_year );
          }else{
//            $(this).val( $(this).val() + delimeter + ((new Date).getFullYear()+1) );
            $(this).val( $(this).val() + delimeter + (parseInt(financial_year)+1) );
          }
        }

        
      }
    });
  });
</script>