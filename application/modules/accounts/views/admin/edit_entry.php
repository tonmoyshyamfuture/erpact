<style>
     .bank-form .row div:nth-child(7) input{
        width: 85% !important;   
    } 
    .close-div{ position: relative;}
    .close-btn{ position: absolute;right: 11px;top: -3px;color: red !important;}
    .close-btn .fa-times {
        color: red;
    }
    .min-padding{
        padding: 0 2px;
    }
</style>
<link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/ladda/ladda.min.css">
<script type="text/javascript">
    function chech_difference() {
        var dr_value = parseFloat(document.forms["form_new_form"]["sum_dr"].value);
        var cr_value = parseFloat(document.forms["form_new_form"]["sum_cr"].value);
        var entryNo = document.forms["form_new_form"]["entry_no"].value;
        var date = document.forms["form_new_form"]["create_date"].value;
        var ledger_id_0 = document.getElementById("ledger_id_0").value;
        var ledger_id_1 = document.getElementById("ledger_id_1").value;
        var entry_type_id = document.getElementById("entry_type_id").value;
        
        if(ledger_id_0 == ''){
            alert("Ledger is required.");
            return false;
        }
        if(ledger_id_1 == ''){
            alert("Ledger is required.");
            return false;
        }
       
       if(entryNo==''){
             alert("Entry no is required.");
            return false;
       }
       
       if(date==''){
             alert("Date is required.");
            return false;
       }
       
//       if(entry_type_id == '2'){
//            if(ledger_id_0 != '2'){
//                    if( ledger_id_0 != '26'){
//                        if(ledger_id_1 != '2'){
//                            if(ledger_id_1 != '26'){
//                                alert("Please Select Cash / Bank Any Ledger.");
//                                return false;
//                            }
//                        }
//                 }
//             }
//        }
//       if(entry_type_id == '3'){
//            if(ledger_id_0 != '2'){
//                if(ledger_id_0 != '26'){
//                    alert("Please Select Cash / Bank Cr. Ledger.");
//                    return false;
//                }
//             }
//            if(ledger_id_1 != '2'){
//                if(ledger_id_1 != '26'){
//                    alert("Please Select Cash / Bank Dr. Ledger.");
//                    return false;
//                }
//             }
//        }
       
        if (dr_value > cr_value) {
            alert("your debit balance not equel to credit balance");
            return false;
        }
        if (cr_value > dr_value) {
            alert("your credit balance not equel to debit balance");
            return false;
        }
        if (dr_value == cr_value) {
            $('#new_form').submit();
            return true;
        }
    }
</script>
<style>
    .content-header {
    z-index: 999;
}
</style>
<div class="wrapper2"> 
    <?php echo validation_errors(); ?>   
    <?php // echo form_open(base_url('accounts/entries/update_entry'), array('name' => 'form_new_entry', 'id' => 'new_entry', 'onsubmit' => 'return chech_difference()')); ?>
<section class="content-header">
    <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-bold"></i>Edit Entry </h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <?php /*<input class="btn btn-default" onclick="javascript:location.href='<?php echo site_url('admin/blog-posts/');?>'" value="Discard" type="reset">*/ ?>
                        <!--<input class="btn btn-primary" type="submit" value="Save"/>-->
                    </div>
                </div> 
            </div>     
</section>
    <input type="hidden" id="entry_id" value="<?= isset($id)?$id:''?>">   
<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- left column -->
        <!-- right column -->
        <div class="col-md-12">
            <!-- general form elements disabled -->
            <div class="box box-warning">
                <div class="box-body">
                        
                         <?php echo form_open(base_url('accounts/entries/update_entry'), array('name' => 'form_new_form', 'enctype'=>'application/json' , 'class' => 'accounts-form','id' => 'new_form', 'onsubmit' => 'return chech_difference()')); ?>
                        <div class="form-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="control-label col-md-2">Selected Currency</label>
                                    <div class="col-md-8">
                                        <select  class="form-control select2me" name="currency" id="currency">
                                            <?php if(isset($currencies)){
                                                foreach($currencies AS $currency){
                                                    if(isset($entry_details[0]['selected_currency'])){
                                                        if($currency['id'] == $entry_details[0]['selected_currency']){
                                                            echo '<option selected value="'.$currency['id'].'">'.$currency['currency'].'</option>';
                                                            //continue;
                                                        }else{
                                                            echo '<option  value="'.$currency['id'].'">'.$currency['currency'].'</option>';
                                                        }
                                                    }
                                                    
                                               }
                                            } ?>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                             <div class="form-group clearfix">
                                <label class="control-label col-md-2">Entry Number</label>
                                <div class="col-md-3">
                                    <?php
                                    $date = array(
                                        'name' => 'entry_no',
                                        'class' => 'form-control validate[required,custom[integer]]',
                                        'value' => count($entry) > 0 ? $entry[0]['entry_no'] : "",
                                        'placeholder' => 'Enter entry no',
                                        'readonly' => '',
                                        'autofocus'=>'autofocus',
                                        'id' => 'entry_no'
                                    );
                                    echo form_input($date);
                                    ?>
                                </div>
                                <label class="control-label col-md-2">Date</label>
                                <div class="col-md-3">
                                    <?php
                                    $date = array(
                                        'name' => 'create_date',
                                        'class' => 'form-control validate[required] date-focus',
                                        'value' => count($entry) > 0 ? date('d/m/Y', strtotime($entry[0]['create_date'])) : "",
                                        'placeholder' => 'DD/MM/YYYY'
                                    );
                                    echo form_input($date);
                                    ?>
                                </div>
                             </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label col-md-2"><strong>Dr/Cr</strong></label>
                                    <label class="control-label col-md-2"><strong>Ledger</strong></label>
                                    <label class="control-label col-md-2"><strong>Dr Amount</strong></label>
                                    <label class="control-label col-md-2"><strong>Cr Amount</strong></label>
                                    <label class="control-label col-md-1">&nbsp;</label>
                                    <label class="control-label col-md-2"><strong>Current Balance</strong></label>
                                </div>

                            </div>
                            <hr/>
                            <!-----Start: Add New row for ledger details---->
                            <?php
                            for ($i = 0; $i < count($entry_details); $i++) { 
                                    
                                ?>
                           
                                <div class="form-group ledger-block">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="col-sm-2">
                                               
                                                <input id="account_<?php echo $i; ?>" value="<?php echo $entry_details[$i]['account'];?>" name="account[]"  onblur="active_amount_box(this.value, <?php echo $i; ?>);" class="account-type form-control debitTags input-mg select2me validate[required]">
                                            </div>
                                            <input type="hidden" name="hidden_account[]" value="<?php echo $entry_details[$i]['account'];?>">
                                            <div class="col-md-2">
                                               
                                                
                                            <input id="select2_sample_<?php echo $i; ?>" name="" value="<?php echo $entry_details[$i]['ladger_name']; ?>" onblur="get_ledger(this.value, <?php echo $i; ?>),get_current_balance( <?php echo $i; ?>,<?php echo $entry_details[$i]['ladger_id'];?>);" class="form-control ledger-account ">
                                            <input id="ledger_id_<?php echo $i; ?>" value="<?php echo $entry_details[$i]['ladger_id']; ?>" name="ledger_id[]" type="hidden">
                                            
                                            </div>
                                            <input type="hidden" name="hidden_ledger_id[]" value="<?php echo $entry_details[$i]['ladger_id'];?>">
                                            <div class="col-md-2">
                                                <?php
                                                $data = array(
                                                    'name' => count($entry_details) > 0 && $entry_details[$i]['account'] == 'Dr' ? 'amount[]' : "",
                                                    'id' => 'amount_d_' . $i,
                                                    'class' => 'form-control txt_dr txt_tracking amount validate[required,custom[integer,custom[integer]], text-input',
                                                    'value' => count($entry_details) > 0 && $entry_details[$i]['account'] == 'Dr' ? $entry_details[$i]['unit_price'] : "",
                                                    'placeholder' => 'Amount',
                                                    'type' => count($entry_details) > 0 && $entry_details[$i]['account'] == 'Dr' ? 'text' : "hidden",
                                                    'readonly' => '',
                                                    'data-id'=>$entry_details[$i]['ladger_id']
                                                );
                                                echo form_input($data);
                                                ?>
                                            </div>
                                            <input type="hidden" name="hidden_amount[]" value="<?php echo $entry_details[$i]['unit_price'];?>">
                                            <div class="col-md-2">
                                                <?php
                                                $data = array(
                                                    'name' => count($entry_details) > 0 && $entry_details[$i]['account'] == 'Cr' ? 'amount[]' : "",
                                                    'id' => 'amount_c_' . $i,
                                                    'class' => 'form-control txt_cr txt_tracking amount validate[required,custom[integer] text-input',
                                                    'value' => count($entry_details) > 0 && $entry_details[$i]['account'] == 'Cr' ? $entry_details[$i]['unit_price'] : "",
                                                    'placeholder' => 'Amount',
                                                    'type' => count($entry_details) > 0 && $entry_details[$i]['account'] == 'Cr' ? 'text' : "hidden",
                                                    'readonly' => '',
                                                     'data-id'=>$entry_details[$i]['ladger_id']
                                                );
                                                echo form_input($data);
                                                ?>
                                            </div>
                                            <div class="col-md-1">
                                                <label>&nbsp;</label>
                                               <!--<a href="javascript:void(0)" class="add_field_button" >Add</a>-->
                                            </div> 

                                            <div class="col-md-2 subhasis">
                                                <!--<input type="text" name="update_balance" id="current_balance_<?= $i; ?>" style="border: none;" value="<?php echo count($entry_details) > 0 ? $entry_details[$i]['current_balance'] : "" ?>" class="form-control" readonly="">-->
                                                <level id="current_balance_<?= $i; ?>" ><?php echo count($entry_details) > 0 ? $entry_details[$i]['current_balance'] : "" ?></level>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                            
                            <?php } ?>
                            <input type="hidden"  id="ledger_no" value="<?php echo $i;?>">
                            <!-----End: Add New row for ledger details---->
                            <div id="input_fields_wrap"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="control-label col-md-2"><strong>Total</strong></label>
                                <label class="control-label col-md-2">&nbsp;</label>
                                <div class="col-md-2">
                                    <span id="sum_dr"><?php echo count($entry) > 0 ? $entry[0]['unit_price_dr'] : "" ?></span>
                                    <input type="hidden" name="sum_dr" id="hidden_dr" class="validate[required] text-input" style="border: none;" value="<?php echo count($entry) > 0 ? $entry[0]['unit_price_dr'] : "" ?>">
                                </div>
                                <div class="col-md-2">
                                    <span id="sum_cr"><?php echo count($entry) > 0 ? $entry[0]['unit_price_cr'] : "" ?></span>
                                    <input type="hidden" name="sum_cr" id="hidden_cr" class="validate[required,equals[sum_dr]] text-input" style="border: none;" value="<?php echo count($entry) > 0 ? $entry[0]['unit_price_cr'] : "" ?>">
                                </div>
                                <label class="control-label col-md-1">&nbsp;</label>
                                <label class="control-label col-md-2"></label>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="control-label col-md-2"><strong>Difference</strong></label>
                                <label class="control-label col-md-2">&nbsp;</label>
                                <div class="col-md-2">
                                    <span id="differ_dr"></span>
                                </div>
                                <div class="col-md-2">
                                    <span id="differ_cr"></span>
                                </div>
                                <label class="control-label col-md-1">&nbsp;</label>
                                <label class="control-label col-md-2"></label>
                            </div>
                        </div>
                         <hr/>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="control-label col-md-2">Narration</label>
                                <div class="col-md-8">
                                    <input name="narration" class="form-control narration" value="<?php if($entry_details){ echo $entry_details[0]['narration'];}  ?>"  type="text">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="entry_id" value=<?php echo $entry_id; ?>>
                        <input type="hidden" id="entry_type_id" name="entry_type_id" value=<?php echo $entry_type_id; ?>>
                        <input name="sub_voucher" id="sub_voucher" class="form-control" placeholder="Sub Voucher" value="<?php if($entry){ echo $entry[0]['sub_voucher'];} ?>"  type="hidden">
                        <div class="footer-button">
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </div>
                        <?php echo form_close(); ?>             
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!--/.col (right) -->
    </div>   <!-- /.row -->
</section><!-- /.content -->
<!-- Debit Modal -->
<div id="DrModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">    
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="tracking_ledger_name"></h4>
                <input type="hidden" value="" id="tracking_ledger_id">
            </div>
            <div class="modal-body">
                <form class="form" role="form" id="form">              
                    <!-- set 1-->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-7"><label>Tracking Name</label></div>
                            <div class="col-xs-5"><label>Amount</label></div>              
                        </div> 
                    </div> 
		          <div class="form-group clone_div" id="clone_div"  style="display:none">
                        <div class="row">
                            <div class="col-xs-7"><input type="text" name="tracking_name[]" class="form-control modal_tracking current" id=""></div>
                            <div class="col-xs-5"><input type="text"  name="tracking_amount[]" class="form-control close_pop_dr sub_tracking_amount" ></div>              
                        </div> 
                    </div> 
                <div id="sub_tracking_body">
                    <div class="form-group" >
                        <div class="row" id="tracking_row_0">
                            <div class="col-xs-7"><input type="text" name="tracking_name[]" class="form-control modal_tracking current" id=""></div>
                            <div class="col-xs-5"><input type="text" name="tracking_amount[]"  class="form-control close_pop_dr sub_tracking_amount" ></div>              
                        </div> 
                    </div>
                </div>
                    
                </form>        
            </div> 
            <div class="modal-footer">            
                <div class="row">
                    <div class="col-xs-7 text-right"><label>Total</label></div>
                    <div class="col-xs-5"><input type="text" class="form-control" id="total_sub_tracking" readonly="readonly"></div>              
                </div>            
            </div>  
        </div>

    </div>
</div>

<!-- Bill wish Modal -->
<div id="billModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="bill_ledger_name"> </h4>  
            <input type="hidden" id="bill_ledger_id" value="">
       </div>
        <div class="modal-body">
                <form class="form" role="form" id="bill_form">
                    <div class="form-group">
                        <div class="row">
                            
                            <div class="col-xs-2"><label>Type of Ref.</label></div> 
                            <div class="col-xs-3"><label>Name </label></div>
                            <div class="col-xs-2"><label>Credit Days</label></div>
                            <div class="col-xs-2"><label>Credit Date</label></div> 
                            <div class="col-xs-2"><label>Amount</label></div> 
                            <div class="col-xs-1"><label>Dr/Cr</label></div>              
                        </div>                        
                    </div>
                    <!-- set 1-->
                    <div class="form-group clone_div_bill" id="clone_div_bill"  style="display:none">
                        <div class="row">
                            
                            <div class="col-xs-2"><input type="text" class="form-control current ref_bill_type" onblur="reference_bill_by_type(this.value)" id=""></div> 
                            
                            <div class="col-xs-3"><input type="text" class="form-control bill_name" id=""></div>
                            <div class="col-xs-2"><input type="number" class="form-control bill_credit_day" id=""></div> 
                            <div class="col-xs-2"><input type="text" class="form-control bill_credit_date"  id="bill_credit_date" readonly="readonly" ></div>
                            <div class="col-xs-2"><input type="text" class="form-control bill_amount " id=""></div>  
                            <div class="col-xs-1"><input type="text" class="form-control close_pop_bill  account_type_modal" id=""></div>               
                        </div>                        
                    </div>
                    <div id="bill_body">
                        <div class="form-group">
                            <div class="row">
                                
                                <div class="col-xs-2"><input type="text" class="form-control current ref_bill_type" onblur="reference_bill_by_type(this.value)" id=""></div> 
                                
                                <div class="col-xs-3"><input type="text" class="form-control bill_name " id=""></div>
                                <div class="col-xs-2"><input type="number" class="form-control bill_credit_day" id=""></div> 
                                <div class="col-xs-2"><input type="text" class="form-control bill_credit_date" id="bill_credit_date" readonly="readonly" ></div>
                                <div class="col-xs-2"><input type="text" class="form-control bill_amount " id=""></div> 
                                <div class="col-xs-1"><input type="text" class="form-control close_pop_bill  account_type_modal" id=""></div>              
                            </div>                        
                        </div>
                    </div>
                </form>        
        </div>
            <div class="modal-footer">            
                <div class="row">
                    
                    <div class="col-xs-8 text-right"><label>Total</label></div>
                    <div class="col-xs-3"><input type="text" class="form-control" id="total_bill"  readonly="readonly"></div> 
                    <div class="col-xs-1 "><input type="text" class="form-control" id="cr_dr_cal"  readonly="readonly"></div>             
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Capture Bank Information Modal -->
    <div id="bankModal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <form class="bank-form" id="bank-details-form" method="POST" action="<?php echo site_url('accounts/entries/save_bank_details_ajax'); ?>">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Enter Bank Information</h4>
                        <input type="hidden" name="ledger_id" value="" id="bank_ledger_id">
                        <input type="hidden" name="entry_no" value="" id="bank_entry_no">
                    </div>
                    <div class="modal-body">

                        <div class="bank_input_fields_wrap">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="usr">Transaction Type:</label>
                                        <select name="transaction_type[]" class="form-control">
                                            <option value="">select</option> 
                                            <?php
                                            if ($transaction_types):
                                                foreach ($transaction_types as $value) {
                                                    ?>
                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option> 
                                                    <?php
                                                }
                                            endif;
                                            ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-1 min-padding">
                                    <div class="form-group">
                                        <label for="usr">Instrument No:</label>
                                        <input type="text" class="form-control" placeholder="Instrument No" name="instrument_no[]">
                                    </div>
                                </div>
                                <div class="col-md-1 min-padding">
                                    <div class="form-group">
                                        <label for="usr">Instrument Date:</label>
                                        <input type="text" class="form-control datepicker" placeholder="Instrument Date" name="instrument_date[]">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="usr">Bank Name:</label>
                                        <input type="text" class="form-control" placeholder="Bank Name" name="bank_name[]">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="usr">Branch Name:</label>
                                        <input type="text" class="form-control" placeholder="Branch Name" name="branch_name[]">
                                    </div>
                                </div>    
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="usr">IFSC Code:</label>
                                        <input type="text" class="form-control" placeholder="IFSC Code" name="ifsc_code[]">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="usr">Amount:</label>
                                        <input type="text" class="form-control amount" placeholder="Amount" name="bank_amount[]">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right clearfix">
                            <button type="button" class="bank_add_field_button btn btn-primary btn-xs"><i class="fa fa-plus" aria-hidden="true"></i> Add More</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn ladda-button btn-primary" data-color="mint" data-style="expand-right" data-size="s">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
<style>
    .modal-content .form-group{margin-bottom: 10px !important;}
    .modal-content .form-control{width:100% !important}
    .modal-footer label{margin-top: 7px;}
    .modal-header {border-bottom-color: #ddd;}
    .modal-footer {border-top-color: #ddd;}

</style>
</div>
<script>
    var transaction_type = <?php echo json_encode($transaction_types); ?>;
</script>
<script src="<?php echo $this->config->item('base_url'); ?>assets/admin/ladda/spin.min.js"></script>
<script src="<?php echo $this->config->item('base_url'); ?>assets/admin/ladda/ladda.min.js"></script>
<script type='text/javascript'>
    $("#bank-details-form").submit(function(event) {
        event.preventDefault();
        var l = Ladda.create(document.querySelector('.ladda-button'));
        l.start();
        var ledger_id = $("#bank_ledger_id").val();
        var form = $(this),
                url = form.attr('action'),
                data = form.serialize();
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(data) {
                l.stop();
                if (data.res == 'success') {
                        $('input[value="' + ledger_id + '"]').closest('.ledger-block').find('input[type=text][name="amount[]"]').val(data.amount);
                        $('input[value="' + ledger_id + '"]').closest('.ledger-block').find('input[type=text][name="amount[]"]').trigger('keyup');
                        $('input[value="' + ledger_id + '"]').closest('.ledger-block').find('input[type=text][name="amount[]"]').focus();
                   
                    $("#bankModal").modal('hide');

                } else {
                    $.each(data.message, function(index, value) {
                        Command: toastr["error"](value);
                    });

                }
            }
        });

    });
</script>
<script>
    $(document).ready(function() {
        var max_fields = 10; //maximum input boxes allowed
        var wrapper = $(".bank_input_fields_wrap"); //Fields wrapper
        var add_button = $(".bank_add_field_button"); //Add button ID
        var tran_type_html = '';
        tran_type_html += '<select name="transaction_type[]" class="form-control">';
        tran_type_html += '<option value="">select</option>';
        $.each(transaction_type, function(index, value) {
            tran_type_html += '<option value="' + value.id + '">' + value.name + '</option>';
        });
        tran_type_html += '</select>';
        var x = 1; //initlal text box count
        $(add_button).click(function(e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                var html = '';
                html += '<div class="row">';
                html += '<div class="col-md-2">';
                html += '<div class="form-group">';
                html += tran_type_html;
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-1 min-padding">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control" placeholder="Instrument No" name="instrument_no[]">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-1 min-padding">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control datepicker" placeholder="Instrument Date" name="instrument_date[]">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-2">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control" placeholder="Bank Name" name="bank_name[]">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-2">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control" placeholder="Branch Name" name="branch_name[]">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-2">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control" placeholder="IFSC Code" name="ifsc_code[]">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-2">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control amount" placeholder="Amount" name="bank_amount[]">';
                html += '</div>';
                html += '</div>';
                html += '<div>';
                html += '<div class="form-group close-div">';
                html += '<a href="javascript:void(0);" class="close-btn remove_field"><i class="fa fa-times" aria-hidden="true"></i></a>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                $(wrapper).append(html); //add input box
                //init date picker
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    todayHighlight: true,
                    autoclose: true
                });
                //end init date picker
            }
        });

        $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
            e.preventDefault();
            $(this).closest('.row').remove();
            x--;
        })
    });

    $(".amount").on("keyup", function() {
        var valid = /^\d{0,12}(\.\d{0,2})?$/.test(this.value),
                val = this.value;

        if (!valid) {
            console.log("Invalid Amount!");
            this.value = val.substring(0, val.length - 1);
        }
    });

    $(document).ready(function() {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });
        ;
    });
</script>