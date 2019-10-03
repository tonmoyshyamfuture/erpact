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
                <h1> <i class="fa fa-calendar"></i>Day Book</h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <div class="btn-group btn-svg">
                        <?php if($number_of_branch > 1): ?>
                        <!--somnath - if branch exists then the button will show otherwise not-->
                            <button id="select-branch" class="btn btn-default"  data-toggle="tooltip" data-placement="bottom" title="Select Branch"><i data-feather="layers"></i></button> 
                        <?php endif; ?>
                        <button class="btn btn-default download-pdf"><i data-feather="printer"></i></button>
                          <button class="btn btn-default export-excel"><i data-feather="save"></i></button>
                        <!--<button class="btn btn-default printreport" onclick="printDiv('printreport');"><i class="fa fa-print"></i></button>-->                    
                                            
                          <button class="btn btn-default" data-toggle="modal" data-target="#searchModal"><i data-feather="search"></i></button>                        
                    </div>
                    <button id="daterange" class="btn btn-default btn-calendar btn-svg daterange" data-toggle="modal" data-target="#myModal"><i data-feather="calendar"></i></button>
                    <button id="button" class="myButton btn btn-settings btn-svg pull-right"><i data-feather="settings"></i></button>
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
            'Day Book'=>'/admin/day-book',
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
                <div class="box"> 
                    <div class="box-body pdf_class" id="printreport" data-pdf="Day Book">
                        <div class="table-responsive">                            
                            <table class="table table-report table-rpt-daybook" id="daybook">
                                <thead>
                                    <tr>
                                        <th colspan="5">
                                            <div class="row report-header-company-info">
                                                <?= $this->company_address();?>
                                            </div>
                                            <div class="row report-header">
                                                <div class="col-xs-12">
                                                    <h3>Day Book</h3>
                                                    <?php if($from_date != "" && $to_date != "") { ?>
                                                        <p><?php echo date('d-F-Y', strtotime($from_date)); ?> to <?php echo date('d-F-Y', strtotime($to_date)); ?></p>
                                                    <?php }else if(!empty ($results)) { ?>
                                                        <p> <?php echo date('d-F-Y', strtotime($results[0]['entry_date'])); ?></p>
                                                    <?php } ?>
                                                </div>
                                                <!--<div class="col-sm-4 col-sm-offset-8">
                                                    <input type="text" name="search_value" id="search_value" class="form-control" placeholder="Search" />
                                                    </div>-->
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="width-80">Date</th>
                                        <th class="width-120">Voucher  #</th>
                                        <th>Particulars</th>
                                        <th class="width-80">Type</th>
                                        <th class="text-right width-120">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php foreach($results as $result) : ?>
                                    
                                            <tr>
                                                <td>
                                                    <?= date($current_date_format, strtotime($result['entry_date'])); ?>
                                                </td>
                                                <td>
                                                    <?= $result['voucher_no']; ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url('transaction/invoice') . '.aspx/' . $result['entry_id'] . '/' . $result['entry_type_id']; ?>">
                                                        <?php
                                                        $led = array();
                                                        $devit = json_decode($result['ledger_ids_by_accounts']);

                                                        echo "<strong>Dr </strong>";
                                                        if (isset($devit->Dr)) {
                                                            for ($i = 0; $i < count($devit->Dr); $i++) {
                                                                echo $devit->Dr[$i];
                                                                if (isset($devit->Dr) && count($devit->Dr) > 1) {
                                                                    echo ' + ';
                                                                }
                                                                break;
                                                            }
                                                        }
                                                        ?>
                                                        /
                                                        <?php
                                                        echo "<strong>Cr </strong>";
                                                        for ($i = 0; $i < count($devit->Cr); $i++) {
                                                            echo $devit->Cr[$i];
                                                            if (count($devit->Cr) > 1) {
                                                                echo ' + ';
                                                            }
                                                            break;
                                                        }
                                                        ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?= $result['voucher_type']; ?>
                                                </td>                                                
                                                <td class="text-right">
                                                    <?php echo '<span class="balence_show_' . ++$amount_field_count . '">' . $this->price_format($result['cr_amount']) . '</span>'; ?>
                                                    <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?= $result['cr_amount']; ?>">
                                                </td>
                                                
                                            </tr>
                                            <?php endforeach; ?>

                                </tbody>

                            </table>

                            


                        </div>
                    </div><!-- /.box-body -->

                </div><!-- /.box -->
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
            <button type="button" class="btn btn-primary" id="submitDaterange">Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


<!-- Search Modal -->
<div class="modal fade" id="searchModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="width: 400px;margin: 0 auto;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Search Here</h4>
            </div>
            <div class="modal-body">
                <form action="" method="">
                    <input type="text" name="search_value" id="search_value" class="form-control" placeholder="Search" autofocus="" autocomplete="off" value=""/>
                </form>
            </div>
            <div class="text-center" id="err-div" style="color: red;"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="searchSubmit">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>








<script>
    var startDate = '<?php //echo date('m/d/Y', strtotime($staring_day)); ?>';
    var endDate = '<?php //echo date('m/d/Y', strtotime($ending_day)); ?>';
    var maxDate = '<?php //echo date('m/d/Y', strtotime($year_ending_day)); ?>';
    var minDate = '<?php //echo date('m/d/Y', strtotime($year_staring_day)); ?>';
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
            window.location.replace(window.location.pathname+'?&from_date='+from+'&to_date='+to);
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
$(document).ready(function() {
    $("#search_value").keypress(function(e) {
        if( e.which == 13 ) { // if enter is pressed
            $("#searchSubmit").click();            
            e.preventDefault();
        }
    });
    
    $('#searchModal').on('shown.bs.modal', function () {
        $('#search_value').focus();
    });    
    
    $("#searchSubmit").on("click", function() {
        var search_val = $("#search_value").val();
        
        $.ajax({
            url: "<?php echo base_url(); ?>admin/day-book-search",
            type: "POST",
            data: {search_value : search_val},
            success: function(response) {
                $("#daybook").html(response);
                $("#searchModal").modal('hide');
            }
        });
        
    });
});
</script>
