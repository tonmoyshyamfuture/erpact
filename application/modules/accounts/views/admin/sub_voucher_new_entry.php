
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
            $("#new_form").submit();
        }
    }
    
</script>
<div class="wrapper2"> 
    <?php echo validation_errors(); ?>   
    <?php echo form_open(base_url('accounts/entries/new_entry'),  array('name' => 'form_new_form', 'enctype'=>'application/json', 'class' => 'new-entry accounts-form', 'id' => 'new_form', 'onsubmit' => 'return chech_difference()')); ?>
<section class="content-header">
    <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-list-alt"></i>Add Sub Voucher Entry </h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <input class="btn btn-default" type="reset" value="Discard"/>
                        <input type="submit" class="btn btn-primary" id="submitBtn" value="Save">
                    </div>
                </div> 
            </div>     
</section>
<!-- Main content -->
<section class="content">
    <div class="row">        
        <div class="col-md-12">            
            <div class="box box-warning">
                <div class="box-body">                         
                        <div class="form-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="control-label col-md-2">Selected Currency</label>
                                    <div class="col-md-8">
                                        <select  class="form-control select2me" name="currency" id="currency">
                                            <?php if(isset($currencies)){
                                                foreach($currencies AS $currency){
                                                    if(isset($defoultCurrency['base_currency'])){
                                                        if($currency['id'] == $defoultCurrency['base_currency']){
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
                                <div class="col-md-2"><label class="control-label " for="firstname">Entry Number</label></div>
                                
                                <div class="col-md-3">
                                    <input type="text"  name="entry_no" id="entry_no" value="<?php if(isset($auto_number)){ echo $auto_number;} ?>" class="form-control input-icon entry_no" placeholder="Enter entry no" />
                                </div>
                                
                                <div class="col-md-2 text-right"><label class="control-label ">Date</label></div>
                                <div class="col-md-3">
                                     <?php
                                    $date = array(
                                        'name' => 'create_date',
                                        'class' => 'form-control  placeholder-no-fix input-icon',
                                        'placeholder' => 'Date',
                                        'value'=> date('d/m/Y'),
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
                            <?php for ($i = 0; $i < 2; $i++) { ?>
                                
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="col-sm-2">
                                                <?php if($entry_type_id == '2'){ ?>
                                                    <input id="account_<?php echo $i; ?>" value="<?php if($i == 0){ echo 'Dr';}else{ echo 'Cr';} ?>" name="account[]"  onblur="active_amount_box(this.value, <?php echo $i; ?>);" class="form-control debitTags input-mg select2me validate[required]">
                                                <?php  }else{ ?>
                                                    <input id="account_<?php echo $i; ?>" value="<?php if($i == 0){ echo 'Cr';}else{ echo 'Dr';} ?>" name="account[]"  onblur="active_amount_box(this.value, <?php echo $i; ?>);" class="form-control debitTags input-mg select2me validate[required]">
                                                <?php } ?>
                                           <!--07072016--> <!-- <input id="account_<?php echo $i; ?>" value="<?php if($i == 0){ echo 'Dr';}else{ echo 'Cr';} ?>" name="account[]"  onblur="active_amount_box(this.value, <?php echo $i; ?>);" class="form-control debitTags input-mg select2me validate[required]"> -->    
                                            </div>
                                            <div class="form-group col-md-2">
                                                <input id="select2_sample4" name="" onblur="get_ledger(this.value, <?php echo $i; ?>);" class="form-control ledger-account ">
                                                <input id="ledger_id_<?php echo $i; ?>" name="ledger_id[]" type="hidden">
                                            </div>     
                                            <div class="col-md-2">
                                                <?php
                                                $data = array(
                                                    'name' => 'amount[]',
                                                    'id' => 'amount_d_' . $i,
                                                    'class' => 'form-control txt_dr text-input',
                                                    'value' => '',
                                                    'placeholder' => 'Amount',
                                                    'readonly' => ''
                                                );
                                                echo form_input($data);                                               
                                                ?>
                                            </div>
                                        
                                            <div class="col-md-2">
                                                <?php
                                                $data = array(
                                                    'name' => 'amount[]',
                                                    'id' => 'amount_c_' . $i,
                                                    'class' => 'form-control txt_cr validate[required,custom[integer]] text-input',
                                                    'value' => '',
                                                    'placeholder' => 'Amount',
                                                    'readonly' => ''
                                                );
                                                echo form_input($data);
                                                ?>
                                            </div>
                                            <div class="col-md-1">
                                                <label>&nbsp;</label>
                                                <!--<button class="add_field_button">Add</button>-->
                                                <!--<a href="javascript:void(0)" class="add_field_button" >Add</a>-->
                                            </div> 

                                            <div class="col-md-2 subhasis">
                                                <!--<input type="text" name="update_balance" id="current_balance_<?= $i; ?>" style="border: none;" value="" class="form-control" readonly="">-->
                                                <lebel id="current_balance_<?= $i; ?>"></lebel>
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
                                    <span id="sum_dr"></span>
                                    <input type="hidden" name="sum_dr" id="hidden_dr" class="validate[required] text-input" style="" >
                                </div>
                                <div class="col-md-2">
                                    <span id="sum_cr"></span>
                                    <input type="hidden" name="sum_cr" id="hidden_cr" class="validate[required,equals[sum_dr]] text-input" style="border: none;">
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
                                    <input name="narration" class="form-control"  type="text">
                                </div>
                            </div>
                        </div>
                        <input type="hidden"  name="entry_type_id" value="<?php echo $entry_type_id; ?>">
                        <input type="hidden"  name="sub_voucher" value="<?php echo $sub_voucher_id; ?>">
                        <div class="footer-button">
                            <input type="submit" class="btn btn-primary" id="submitBtn" value="Save">
                            <input class="btn btn-default" type="reset" value="Discard"/>
                        </div>
                        
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!--/.col (right) -->
<!--        <div class="col-md-4">
                    <div class="box settings-help">
                        <div class="box box-solid">
                            <div class="box-header">
                                <h3 class="box-title"> <i class="fa fa-info-circle"></i> Help</h3>
                            </div>                        
                            <div class="box-body">
                                <div class="row">                                
                                    <div class="col-md-12">
                                        coming soon...
                                    </div>
                                </div>
                            </div>
                        </div>      
                    </div>
                </div>-->
    </div>   <!-- /.row -->
</section><!-- /.content -->
<?php echo form_close(); ?>
</div>