<style>
    .errorMessage{color: red;}
</style>
<div class="wrapper2">    
    <?php echo form_open(base_url('index.php/accounts/ajax_save_ledger_data'), array('class' => 'accounts-form', 'id' => 'add_ledger_form')); ?>
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <i class="fa fa-plus-circle"></i>
                    <?php
                    if ($ledger) {
                        ?>
                        Edit Account Ledger    
                        <?php
                    } else {
                        ?>
                        Add Account Ledger 
                        <?php
                    }
                    ?>
                </h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <input name="id" id="ledger_id" type="hidden"  value="<?php echo (isset($ledger->id)) ? $ledger->id : '' ?>" />   
                    <?php echo form_hidden('ladger_account_detail_id', count($ledger) > 0 ? $ledger->ladger_account_detail_id : ''); ?>

                    <?php if (!empty($ledger)) { ?>
                        <a href="<?php echo site_url('admin/view-accounts-ledger') . "/" . $ledger->id; ?>" class="btn btn-default"> Discard</a>
                    <?php } else { ?>
                        <a href="<?php echo site_url('admin/accounts-groups') . '?_tb=2' ?>" class="btn btn-default"> Back</a>
                    <?php } ?>   
                        <button type="submit" class="btn btn-primary ladda-button" data-color="blue" data-style="expand-right" data-size="xs">Save</button>
                </div> 
            </div>     
    </section>
     <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Chart of Accounts', '/admin/accounts-groups');
        if(isset($ledger->id)){
        $this->breadcrumbs->push('Update Ledger', '/admin/add-accounts-ledger');    
        }else{
        $this->breadcrumbs->push('Add Ledger', '/admin/add-accounts-ledger');
        }
        $this->breadcrumbs->show();
        ?>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8">

                <div class="box">
                    <div class="box-body">        
                        <div class="form-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Group</label>                                        
                                        <div class="input-group input-block">
                                        <input type="text" id="group_name" name="" placeholder="Group Name" maxlength="50" class="form-control" value="<?php echo count($ledger) > 0 ? $ledger->group_name : ""; ?>" >
                                        <input id="group_id" name="group_id" value="<?php echo (isset($ledger->group_id)) ? $ledger->group_id : ''; ?>" type="hidden">
                                        <input id="contact_required" name="contact_required" value="<?php echo (isset($contacts->id))?1:0;?>" type="hidden">                                        
                                        <!-- <span class="input-group-addon btn input-block" id="addGroupBtn" style="background: #3c8dbc; color: #fff !important; height: 40px; border-color: #3c8dbc !important"> -->
                                        <span class="input-group-addon btn input-block btn-primary" id="addGroupBtn" style="height: 40px;">
                                            <i class="fa fa-plus"></i>
                                        </span>                                        
                                        </div>
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>                                
                                <div class="col-md-6">
                                    <div class="form-group input-block">
                                        <label>Ledger Name</label>
                                        <input type="text" class="form-control" id="ladger_name" name="ladger_name" placeholder="Ledger Name" maxlength="50" value="<?php echo (isset($ledger->ladger_name)) ? $ledger->ladger_name : '' ?>" autocomplete="off">                
                                        <span class="errorMessage"></span>
                                    </div>   
                                </div>
                                
                                <!-- bank details section -->
                                <div id="bank_details_section" >
                                    <div class="col-md-6">
                                        <div class="form-group input-block">
                                            <label>Bank Name</label>
                                            <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Bank Name" value="<?php echo (isset($bank_details->bank_name)) ? $bank_details->bank_name : '' ?>"  autocomplete="off">
                                        </div>   
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group input-block">
                                            <label>Branch Name</label>
                                            <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Branch Name" value="<?php echo (isset($bank_details->branch_name)) ? $bank_details->branch_name : '' ?>"  autocomplete="off">
                                        </div>   
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group input-block">
                                            <label>Account No</label>
                                            <input type="text" class="form-control" id="acc_no" name="acc_no" placeholder="Account No" value="<?php echo (isset($bank_details->acc_no)) ? $bank_details->acc_no : '' ?>"  autocomplete="off">
                                        </div>   
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group input-block">
                                            <label>IFSC Code</label>
                                            <input type="text" class="form-control" id="ifsc" name="ifsc" placeholder="IFSC Code" value="<?php echo (isset($bank_details->ifsc)) ? $bank_details->ifsc : '' ?>" autocomplete="off">
                                        </div>   
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group input-block">
                                            <label>Bank Address</label>
                                            <textarea type="text" class="form-control" id="bank_address" name="bank_address" placeholder="Bank Address"><?php echo (isset($bank_details->bank_address)) ? $bank_details->bank_address : '' ?></textarea>
                                        </div>   
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tracking</label><br>
                                        <div class="radio-inline">
                                        <label>
                                            <input name="tracking_status" id="tracking_status" type="radio"   <?php
                                        if (count($ledger) > 0) {
                                            if ($ledger->tracking_status == '1') {
                                                echo 'checked';
                                            }
                                        }
                                        ?>  value="1" /> Yes</label>
                                        </div>
                                        <div class="radio-inline">
                                        <label><input name="tracking_status" id="tracking_status" type="radio"  <?php
                                        if (count($ledger) > 0) {
                                            if ($ledger->tracking_status == '2') {
                                                echo 'checked';
                                            }
                                        } else {
                                            echo 'checked';
                                        }
                                        ?>    value="2" /> No</label>
                                    </div>
                                        </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">       
                                        <label>Bill Wise Details</label><br> 
                                        <div class="radio-inline">
                                        <label>
                                        <input name="bill_details_status" id="bill_details_status" type="radio"   <?php
                                        if (count($ledger) > 0) {
                                            if ($ledger->bill_details_status == '1') {
                                                echo 'checked';
                                            }
                                        }
                                        ?>  value="1" /> Yes
                                        </label>
                                        </div>
                                        <div class="radio-inline">
                                        <label>
                                        <input name="bill_details_status" id="bill_details_status" type="radio"  <?php
                                        if (count($ledger) > 0) {
                                            if ($ledger->bill_details_status == '2') {
                                                echo 'checked';
                                            }
                                        } else {
                                            echo 'checked';
                                        }
                                        ?>    value="2" /> No
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">       
                                        <label>Service Activated</label><br> 
                                        <div class="radio-inline">
                                            <label>
                                        <input name="service_status" id="service_status" type="radio"   <?php
                                        if (count($ledger) > 0) {
                                            if ($ledger->service_status == '1') {
                                                echo 'checked';
                                            }
                                        }
                                        ?>  value="1" /> Yes</label>
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                        <input name="service_status" id="service_status" type="radio"  <?php
                                        if (count($ledger) > 0) {
                                            if ($ledger->service_status == '2') {
                                                echo 'checked';
                                            }
                                        } else {
                                            echo 'checked';
                                        }
                                        ?>    value="2" /> No</label>
                                    </div>
                                </div>
                                </div>
                                
                                
                                
                                <?php
                                    if ($ledger_code_status != '1')
                                    {
                                ?>
                                    <div class="col-md-4"> 
                                        <div class="form-group">
                                            <?php
                                            if ($ledger_code_status != '1') {

                                                echo '<label>Ledger Code</label>';
                                            }
                                            ?>

                                            <input type="<?php if ($ledger_code_status == '1') { echo 'hidden'; } else {  echo 'text'; } ?>"  name="ledger_code" id="ledger_code" value="<?php echo count($ledger) > 0 ? $ledger->ledger_code : '' ?>" class="form-control" placeholder="Ledger Code" />                                    

                                        </div>
                                    </div>
                                <?php
                                    }
                                ?>
                                
                                
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Opening Balance</label>
                                        <div class="row">
                                            <div class="col-xs-12 input-block">
                                                <input type="text" id="account"  name="account" placeholder="Dr/Cr" value="<?php echo isset($ledger->account) ? $ledger->account : ""; ?>" class="form-control debitTags account_type" style="display: inline-block; width:28%;">
                                                    <input type="text" name="opening_balance" id="opening_balance" autocomplete="off" class="form-control" value="<?php echo (isset($ledger->balance)) ? $ledger->balance : '' ?>" placeholder="Balance" style="display: inline-block; width:72%; margin-left: -4px">
                                                <span class="errorMessage"></span>
                                            </div>                                            
                                        </div>                                        
                                    </div>
                                </div>

                                <div class="col-md-8 credit_limit_div" style="display:<?php echo ((count($ledger) > 0) && ($ledger->bill_details_status == '1' )) ? 'block' : 'none'; ?>">
                                    <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Credit Days</label> 
                                            <input type="number" min="0" class="form-control onlyNumeric" name="credit_date" placeholder="Credit days" value="<?php echo isset($ledger->credit_date) ? $ledger->credit_date : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Credit Limit</label> 
                                            <input type="number" min="0" class="form-control onlyNumeric" name="credit_limit" placeholder="Credit limit" value="<?php echo isset($ledger->credit_limit) ? $ledger->credit_limit : ''; ?>">
                                        </div>
                                    </div>
                                        </div>
                                </div>
                                
                                <div class="clearfix">
                                    <div id="contact_section">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Select Contact</label> 
                                            <div class="input-group input-block">
                                            <input type="text" class="form-control contact"  placeholder="Select Contact" autocomplete="off" value="<?php echo (isset($contacts->company_name))?$contacts->company_name:'';?>">
                                            <span class="input-group-addon btn input-block btn-primary" id="addContactBtn" style="height: 40px;">
                                                <i class="fa fa-plus"></i>
                                            </span> 
                                            <input type="hidden" name="contact_id" id="contact_id" value="<?php echo (isset($contacts->id))?$contacts->id:'';?>">
                                            <span class="errorMessage"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-5"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">            
                        <input name="id" id="ledger_id" type="hidden"  value="<?php echo!empty($ledger) ? $ledger->id : 0 ?>" />   
                        <?php echo form_hidden('ladger_account_detail_id', count($ledger) > 0 ? $ledger->ladger_account_detail_id : 0); ?>
                        <div class="footer-button">
                            <button type="submit" class="btn btn-primary ladda-button" data-color="blue" data-style="expand-right" data-size="xs">Save</button>
                            <?php if (!empty($ledger)) { ?>
                                <a href="<?php echo site_url('admin/view-accounts-ledger') . "/" . $ledger->id; ?>" class="btn btn-default"> Back</a>
                            <?php } else { ?>
                                <a href="<?php echo site_url('admin/accounts-groups') . '?_tb=2' ?>" class="btn btn-default"> Back</a>
                            <?php } ?>   
                            
                        </div>        
                    </div>

                </div>   <!-- /.row -->

            </div>

            <div class="col-md-4">
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
        </div>
    </section><!-- /.content -->
    <?php echo form_close(); ?>
</div>


<!-- Add Group Modal -->
<div id="addGroup" class="modal fade" role="dialog">
    <div class="modal-dialog" style="top: 70px;">

        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo base_url(); ?>accounts/groups/ajax_save_group_data" id='add_group_form_te'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        Add Group
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Parent Name</label>
                            <select class="select2 form-control" name="parent_id" id="parent_id">
                                <option value="">Select parent</option>
                            </select>
                        <span class="errorMessage"></span>
                    </div> 

                    <div class="form-group" >
                        <label>Group Name</label>
                        <input type="text" class="form-control" name="group_name" id="group_name">
                        <span class="errorMessage"></span>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn ladda-button group-add-btn btn-primary" data-color="blue" data-style="expand-right" data-size="s">Save</button>
                </div>
            </form>   
        </div>

    </div>

</div>


<!-- contact modal -->
<div id="contactModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <form id="customer-details-form" action="<?php echo base_url(); ?>customer_details/admin/add_details_ajax" id='add_group_form_te'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        Add Contact Details
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="box">
                                        
                        <div class="box-header">
                            <h3 class="box-title">Ledger & Company Details</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6 hidden">
                                    <div class="form-group ">
                                        <label>Select ledge Name</label>
                                        <input type="hidden" name="ledger_id" id="ledger_id">
                                        <input type="text" name="ledger_name" id="ledger_name" class="form-control" placeholder="Select Ledger">
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>                    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <input type="text" class="form-control" name="company_name" id="company_name" placeholder="Enter Company Name">
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <h5>Mailing Details</h5>
                                </div>                        
                            </div> 
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number">
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email Id</label>
                                        <input type="text" class="form-control" name="email" id="email" placeholder="Enter Email Id">
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <select name="country" class="form-control" id="country">
                                            <option value="">Select Country</option>
                                            <?php
                                            foreach ($country as $value) {
                                                ?>
                                                <option value="<?php echo $value->id ?>" <?php if($value->id == 101){ echo "selected"; } ?>><?php echo $value->name ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <!--<input type="text" class="form-control" name="country" id="country" placeholder="select country">-->
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State</label>
                                        <select name="state" class="form-control" id="state">
                                            <option value="">Select State</option>
                                            <?php foreach($states as $state): ?>
                                                <option value="<?php echo $state->id; ?>" data-code="<?php echo $state->state_code; ?>"><?php echo $state->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <!--<input type="text" class="form-control" name="state" id="state" placeholder="Select State">-->
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" class="form-control" name="city" id="city" placeholder="Enter City">
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea class="form-control" cols="6" name="address" id="address" placeholder="Address"></textarea>
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zip-code</label>
                                        <input type="text" class="form-control" name="zipcode" id="zipcode" placeholder="Enter zip-code">
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Web site</label>
                                        <input type="text" class="form-control" name="website" id="website" placeholder="Enter web site name">
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <h5> Shipping Details </h5>
                                    </div>
                                </div>
                                <div class="col-sm-6 same_as_mailing">
                                <div class="form-group">
                                    <div class="checkbox">
                                    <label class="shipping_billing_same" for="shipping_billing_same"> <input type="checkbox" id="shipping_billing_same" value="1">  Same as mailing details</label>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="shipping_wrap">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Country</label>
                                            <select name="shipping_country[]" class="form-control" id="shipping_country">
                                                <option value="">Select Country</option>
                                                <?php
                                                foreach ($country as $value) {
                                                    ?>
                                                    <option value="<?php echo $value->id ?>" <?php if($value->id == 101){ echo "selected"; } ?>><?php echo $value->name ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <!--<input type="text" class="form-control" name="shipping_country" id="shipping_country" placeholder="Country">-->
                                            <span class="errorMessage"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>State</label>
                                            <select name="shipping_state[]" class="form-control" id="shipping_state">
                                                <option value="">Select State</option>
                                                <?php foreach ($states as $state): ?>
                                                    <option value="<?php echo $state->id; ?>" data-code="<?php echo $state->state_code; ?>"><?php echo $state->name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <!--<input type="text" class="form-control" name="shipping_state" id="shipping_state" placeholder="State">-->
                                            <span class="errorMessage"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" class="form-control" name="shipping_city[]" id="shipping_city" placeholder="City">
                                            <span class="errorMessage"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" class="form-control" name="shipping_address[]" id="shipping_address" placeholder="Address">
                                            <span class="errorMessage"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Zip-Code</label>
                                            <input type="text" class="form-control" name="shipping_zipcode[]" id="shipping_zipcode" placeholder="Zip-code">
                                            <span class="errorMessage"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-9 default_ship_margin-top">
                                        <div class="form-group">
                                            <div class="radio-inline">
                                                <label>
                                                    <input type="radio" class="default_ship" name="default_ship[]" value="1" checked=""> Default
                                                    <input type="hidden" class="default_ship_address" name="default_ship_address[]" value="1" >
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>                           
                            </div>
                            <button class="btn btn-info btn-sm add_shipping_button" autocomplete="off"><i class="fa fa-plus"></i> Add alternative address</button>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <h5>Company Tax Details</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>PAN/IT Number</label>
                                        <input type="text" class="form-control" name="pan_it_no" id="pan_it_no" placeholder="Enter PAN/IT Number" maxlength="10">
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>
                                <div class=" col-md-6">
                                    <div class="form-group">
                                        <label for="consumer-type" class="black">Company Type</label><br>
                                        
                                        <select name="company_type" class="form-control" id="company_type">
                                            <option value="1">Regular</option>
                                            <option value="2">Composite</option>
                                            <option value="3">Unregister</option>
                                        </select>
                                        <span class="help-block with-errors"></span>
                                    </div>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-md-6" id="gstn_section">
                                    <div class="form-group">
                                        <label>GSTN Number</label>
                                        <input type="text" class="form-control" name="gstn_no" id="gstn_no" placeholder="Enter GSTN Number" maxlength="15">
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Registration Number</label>
                                        <input type="text" class="form-control" name="registration_number" id="registration_number" placeholder="Enter Registration Number">
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="accounttabs contacttabs clearfix">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#contactperson">Contact Person</a></li>
                                    <li><a data-toggle="tab" href="#notes">Notes</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="contactperson" class="tab-pane fade in active">                                   
                                        <div class="row hidden">
                                            <div class="form-group col-md-12">
                                                <h5>Contact Person</h5>
                                            </div>
                                        </div>
                                        <div class="input_fields_wrap">    
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="contact_name[]" id="contact_name_1" placeholder="Name">
                                                        <span class="errorMessage"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="contact_email[]" id="contact_email_1" placeholder="Email">
                                                        <span class="errorMessage"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="contact_phone[]" id="contact_phone_1" placeholder="Phone Number">
                                                        <span class="errorMessage"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-xs-9">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="contact_designation[]" id="contact_designation_1" placeholder="Designation">
                                                        <span class="errorMessage"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 col-xs-3 text-right">
                                                    <div class="form-group">
                                                        <button class="btn btn-info add_field_button"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div> 

                                        </div>                                   
                                    </div>
                                    <div id="notes" class="tab-pane fade">
                                        <div class="row hidden">
                                            <div class="form-group col-md-12">
                                                <h5>Notes</h5>
                                            </div>
                                        </div>
                                        <div class="notes_multiple note_input_fields_wrap">    

                                            <div class="row">
                                                <div class="col-lg-11 col-md-11 col-sm-10 col-xs-9">
                                                    <div class="form-group">
                                                        <textarea class="form-control" name="notes[]" rows="3" placeholder="Notes"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-2 col-xs-3 text-right">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-info add_notes add_notes_button"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>                                    
                                    </div>    
                                </div>

                            </div>         
                        </div>
                    
                </div><!-- /.box --> 
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn ladda-button contact-add-submit btn-primary" data-color="blue" data-style="expand-right" data-size="s">Save</button>
                </div>
            </form>   
        </div>

    </div>

</div>


<script>

    $(function() {
        
        //get group
        $("#group_name").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: '<?php echo site_url('accounts/accounts/getAllGroup'); ?>',
                    data: "group=" + request.term,
                    dataType: "json",
                    success: function(data) {
                        
                        var words = request.term.split(' ');
                        var results = $.grep(data, function(name, index) {
                            var sentence = name.label.toLowerCase();
                            return words.every(function(word) {
                                return sentence.indexOf(word.toLowerCase()) >= 0;
                            });
                        });
                        response(results);
                    },
                    error: function(request, error) {
                        //alert('connection error. please try again.');
                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                e.preventDefault() // <--- Prevent the value from being inserted.
                $(this).val(ui.item.label);
                $("#group_id").val(ui.item.value);
                var group_id = ui.item.value;

                $.ajax({
                    type: "POST",
                    url: '<?php echo site_url('accounts/accounts/checkGroup'); ?>',
                    data: "group_id=" + ui.item.value,
                    dataType: "json",
                    success: function(data){
                    if(data.res){
                     $("#contact_required").val(1);   
                    }else{
                     $("#contact_required").val(0);   
                    }
                    },
                    error: function(request, error) {
                        alert('connection error. please try again.');
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('accounts/groups/checkCrDr'); ?>",
                    data: { group_id : group_id },
                    success: function(data){
                        $("#account").val(data.trim());
                    },
                    error: function(request, error) {
                        // alert('connection error. please try again.');
                    }
                });
            }
        }).focus(function() {
            $(this).autocomplete("search", "");
        });
        //end group

        $(".contact").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: '<?php echo site_url('accounts/accounts/getAllContacts'); ?>',
                    data: "company=" + request.term,
                    dataType: "json",
                    success: function(data) {
                        var words = request.term.split(' ');
                        var results = $.grep(data, function(name, index) {
                            var sentence = name.label.toLowerCase();
                            return words.every(function(word) {
                                return sentence.indexOf(word.toLowerCase()) >= 0;
                            });
                        });
                        response(results);
                    },
                    error: function(request, error) {
                        alert('connection error. please try again.');
                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                e.preventDefault() // <--- Prevent the value from being inserted.
                $(this).val(ui.item.label);
                $("#contact_id").val(ui.item.value);
            }
        }).focus(function() {
            $(this).autocomplete("search", "");
        });
    });

    $("#add_ledger_form").submit(function(event) {
        event.preventDefault();
        var l = Ladda.create(document.querySelector('.ladda-button'));
        l.start();
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
                $('.errorMessage').html('');
                $('.form-group').removeClass('has-error');
                if (data.res == 'error') {
                    $.each(data.message, function(index, value) {
                        $('#' + index).closest('.form-group').addClass('has-error');
                        $('#' + index).closest('.input-block').find('.errorMessage').html(value);
                    });
                } else if (data.res == 'save_err') {
                    Command: toastr["error"](data.message);
                } else {
                    Command: toastr["success"](data.message);
                    window.location.href = "<?php echo site_url('admin/accounts-groups') . '?_tb=2'; ?>";
                }
            }
        });

    });
</script>
<script>
    $("input[name='bill_details_status']").click(function() {
        var val = $(this).val();
        if (parseInt(val) == 1) {
            $(".credit_limit_div").show();
        } else {
            $(".credit_limit_div").hide();
        }
    });
</script>
<script type="text/javascript">

    function chech_validation() {
        var group_name = document.getElementById("group_name").value;

        if (group_name == '') {
            alert("Group Name is required.");
            return false;
        }

        var account_type = document.getElementById("account_type").value;
        if (account_type == '') {
            alert("Account Type is required.");
            return false;
        } else if (account_type == 'Cr') {
            return true;
        } else if (account_type == 'Dr') {
            return true;
        } else {
            alert("Account type is only Dr or Cr.");
            return false;
        }

        var opening_balance = document.getElementById("opening_balance").value;
        if (opening_balance == '') {
            alert("Opening Balance is required.");
            return false;
        }

        var ladger_name = document.getElementById("ladger_name").value;
        var ledger_id = document.getElementById("ledger_id").value;
        if (ladger_name == '') {
            alert("Ledger Name is required.");
            return false;
        } else if (ledger_id == 0) {
            var url = "<?php echo base_url() . 'accounts/accounts/ledger_name_check' ?>";
            var queryString = "ladger_name=" + ladger_name + '&ajax=1';
            $.ajax({
                type: "POST",
                url: url,
                data: queryString,
                dataType: "json",
                success: function(data) {
                    if (data.SUCESS) {
                        alert("Ledger Name is already exists..");
                        return false;
                    } else {
                        document.ledger_from.submit();
                    }
                },
                error: function(request, error) {
                }
            });
            return false;
        } else {
            $("#add_ledger").submit();
        }
    }
    $("#opening_balance").on("keyup", function() {
        var valid = /^\d{0,12}(\.\d{0,2})?$/.test(this.value),
                val = this.value;

        if (!valid) {
            this.value = val.substring(0, val.length - 1);
        }
    });

    // bank details section will show if bank_account is selected as group name
    $("#ladger_name").on("keypress", function(e) {
        if (e.keyCode == 13 && $(this).val() != "" && $("#group_id").val() == 10) {
            // bank details modal will pop-up if banck-account is selected as group and ledger name is not null
            $("#bank_details_section").show();

        }else if (e.keyCode == 13 && ($("#group_id").val() != 10 || $(this).val() == "")){
           $("#bank_details_section").hide(); 
           $("#account").focus();
        }
    });

    if($("#group_id").val() == 15 || $("#group_id").val() == 23) {
        $("#contact_section").show();
    }else{
        $("#contact_section").hide();
    }
    if($("#group_id").val() == 10) {
        $("#bank_details_section").show();
    }else{
        $("#bank_details_section").hide(); 
    }
    $("#group_name").on("focusout", function() {
        if($("#group_id").val() == 10) {
            $("#bank_details_section").show();
        }else{
            $("#bank_details_section").hide(); 
        }

        if($("#group_id").val() == 15 || $("#group_id").val() == 23) {
            $("#contact_section").show();
        }else{
            $("#contact_section").hide();
        }

    });

</script>

<script>    

    // add group
    $(document).ready(function() {        

        $('body').delegate('#addGroupBtn', 'click', function(e) {
            e.preventDefault();

            $.ajax({
                url: '<?php echo base_url(); ?>'+'transaction_inventory/inventory/getAllGroupsByAjax',
                success: function(response) {
                    $("#addGroup #parent_id").html(response);
                }
            });
            $("#addGroup").modal('show');
        });

        // submit add group modal
        $("#add_group_form_te").submit(function(event) {
            event.preventDefault();
            var l = Ladda.create(document.querySelector('.group-add-btn'));
            l.start();

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
                    $('.errorMessage').html('');
                    $('.form-group').removeClass('has-error');
                    if (data.res == 'error') {
                        $.each(data.message, function(index, value) {
                            $('#' + index).closest('.form-group').addClass('has-error');
                            $('#' + index).closest('.form-group').find('.errorMessage').html(value);
                        });
                    } else if (data.res == 'save_err') {
                        Command: toastr["error"](data.message);
                    } else {
                        Command: toastr["success"](data.message);
                        $('#group_id').val("");
                        $('#addGroup').modal('toggle');

                    }
                }
            });


        });
    });
</script>


<!-- contact add scripts -->
<script>
    $(document).ready(function() {
        var max_fields = 10; //maximum input boxes allowed
        var wrapper = $(".note_input_fields_wrap"); //Fields wrapper
        var add_button = $(".add_notes_button"); //Add button ID

        var x = 1; //initlal text box count
        $(add_button).click(function(e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                var html = '';
                html += '<div class="row remove-notes-row">';
                html += '<div class="col-md-11">';
                html += '<div class="form-group">';
                html += '<textarea class="form-control" rows="3" name="notes[]" placeholder="Notes"></textarea>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-1">';
                html += '<span class="remove_notes btn btn-danger"><i class="fa fa-trash-o"></i></span>';
                html += '</div>';
                html += '</div>';
                $(wrapper).append(html); //add input box
            }
        });

        $(wrapper).on("click", ".remove_notes", function(e) { //user click on remove text
            e.preventDefault();
            $(this).closest('.remove-notes-row').remove();
            x--;
        })
    });

    $(document).ready(function() {
        var max_fields = 10; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap"); //Fields wrapper
        var add_button = $(".add_field_button"); //Add button ID

        var x = 1; //initlal text box count
        $(add_button).click(function(e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                var html = '';
                html += '<div class="row remove-row">';
                html += '<div class="col-md-3">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control" name="contact_name[]" id="contact_name_' + x + '" placeholder="Name">';
                html += '<span class="errorMessage"></span>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-3">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control" name="contact_email[]" id="contact_email_' + x + '" placeholder="Email">';
                html += '<span class="errorMessage"></span>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-3">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control" name="contact_phone[]" id="contact_phone_' + x + '" placeholder="Phone Number">';
                html += '<span class="errorMessage"></span>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-2">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control" name="contact_designation[]" id="contact_designation_' + x + '" placeholder="Designation">';
                html += '<span class="errorMessage"></span>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-1"><span class="remove_field btn btn-danger"><i class="fa fa-trash-o"></i></span></div>';
                html += '</div>';
                $(wrapper).append(html); //add input box
            }
        });

        $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
            e.preventDefault();
            $(this).closest('.remove-row').remove();
            x--;
        })
    });

    //get all ledger
    $(function() {
        $("#ledger_name").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: '<?php echo site_url('customer_details/admin/getAllLedger'); ?>',
                    data: "group=" + request.term + '&ajax=1',
                    dataType: "json",
                    success: function(data) {
                        var words = request.term.split(' ');
                        var results = $.grep(data, function(name, index) {
                            var sentence = name.label.toLowerCase();
                            return words.every(function(word) {
                                return sentence.indexOf(word.toLowerCase()) >= 0;
                            });
                        });
                        response(results);
                    },
                    error: function(request, error) {
                        alert('connection error. please try again.');
                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                e.preventDefault() // <--- Prevent the value from being inserted.
                $(this).val(ui.item.label);
                $("#ledger_id").val(ui.item.value)
            }
        }).focus(function() {
            $(this).autocomplete("search", "");
        });
    });
</script>
</script>
<script type='text/javascript'>
    $("#customer-details-form").submit(function(event) {
        event.preventDefault();
        var form = $(this),
                url = form.attr('action'),
                data = form.serialize();
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(data) {
                $('.errorMessage').html('');
                $('.form-group').removeClass('has-error');
                if (data.res == 'error') {
                    $.each(data.message, function(index, value) {
                        $('#' + index).closest('.form-group').addClass('has-error');
                        $('#' + index).closest('.form-group').find('.errorMessage').html(value);
                    });
                    $.each(data.contact_err, function(index, value) {
                        $('#' + index).closest('.form-group').addClass('has-error');
                        $('#' + index).closest('.form-group').find('.errorMessage').html(value);
                    });
                } else {
                    $("#contactModal").modal('hide');
                    Command: toastr["success"](data.message);
                }
            }
        });

    });

    //shipping mailing same
    $("#shipping_billing_same").click(function() {
        if ($("#shipping_billing_same").is(':checked')) {
            var $country_options = $("#country > option").clone();
            $('#shipping_country').append($country_options);
            var $state_options = $("#state > option").clone();
            $('#shipping_state').append($state_options);
            $('#shipping_country').val($('#country').val());
            $('#shipping_state').val($('#state').val());
            $('#shipping_city').val($('#city').val());
            $('#shipping_address').val($('#address').val());
            $('#shipping_zipcode').val($('#zipcode').val());
        } else { 
            $('#shipping_country').val('');
            $('#shipping_state').val('');
            $('#shipping_city').val('');
            $('#shipping_address').val('');
            $('#shipping_zipcode').val('');
        }
    })
</script>

<script>
    $('#country').on('change', function() {
        var country_id = this.value;
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('customer_details/admin/getState'); ?>",
            data: 'country_id=' + country_id,
            success: function(result) {
                $("#state").html(result);
            }
        });
    });
</script>
<script>
    $('#shipping_country').on('change', function() {
        var country_id = this.value;
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('customer_details/admin/getState'); ?>",
            data: 'country_id=' + country_id,
            success: function(result) {
                $("#shipping_state").html(result);
            }
        });
    });
    
    $(document).ready(function(){
        // $("input[type='radio'][name='company_type']").click(function() {
        $("#company_type").on('change', function() {
            if($(this).val() == 3){ // 3 = unregistered consumer
                $("#gstn_section").hide();
            }else{
                $("#gstn_section").show();
            }
        });

        // open contact open modal
        $('body').delegate('#addContactBtn', 'click', function(e) {
            e.preventDefault();
            $("#contactModal").modal('show');
        });

        // SOMNATH - pan checking (1-5  => A-Z, 6-9 => 0-9, 10 => A-Z)
        $("#pan_it_no").keypress(function(event) {
            var length = $(this).val().length;
            if (event.keyCode >= 65 && event.keyCode <= 90 && length < 5) {
                // uppercase
            }else if (((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 95 && event.keyCode <= 105)) && length >= 5 && length < 9 ) {
                // numeric
            }else if (event.keyCode >= 65 && event.keyCode <= 90 && length == 9) {
                // uppercase
            }else {
                return false;
            }
        });

        $("#gstn_no").keyup(function(event) {
            var length = $(this).val().length;
            var state = $("#state").find(':selected').data("code");
            var state_match;

            // FIRST TWO CHAR IS STATE CODE
            if(length >= 2 && state != $(this).val().substr(0, 2)) {
                
                $("#gstn_no").parent("div.form-group").addClass('has-error');
                $("#gstn_no").next().html("State code mismatched");
                state_match = false;
            }else{
                state_match = true;
                $("#gstn_no").parent("div.form-group").removeClass('has-error');
                $("#gstn_no").next().html("");
            }

            if (event.keyCode >= 65 && event.keyCode <= 90 && length > 2 && length <= 7) {
                $(this).val($(this).val().toUpperCase());
            }else if (((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 95 && event.keyCode <= 105)) && length > 7 && length <= 11 ) {
                
            }else if ( event.keyCode >= 65 && event.keyCode <= 90 && length == 12) {
                $(this).val($(this).val().toUpperCase());
            }else if (((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 95 && event.keyCode <= 105)) && length == 13 ) {
                
            }else if (event.keyCode == 90 && length == 14 ) {
                $(this).val($(this).val().toUpperCase());
            }else if (((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 95 && event.keyCode <= 105) || (event.keyCode >= 65 && event.keyCode <= 90)) && length == 15 ) {
                $(this).val($(this).val().toUpperCase());
            }else if(length > 2 && length < 16 && event.keyCode != 8) {
                // return false;
                $(this).val($(this).val().slice(0,-1));
            }
        });

        var ship_count = 1;
        $(".add_shipping_button").on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo base_url(); ?>customer_details/admin/add_shipping_address",
                type: "POST",
                data: { count: ship_count },
                success: function(response) {
                    $(".shipping_wrap").append(response);
                    ship_count += 1;
                }
            });
        });

        $("body").on("click", ".remove_ship", function(e) { //user click on remove text
            e.preventDefault();
            $(this).closest('.remove_ship_address').remove();
        });

        $("body").on("click", ".default_ship", function(e) { //user click on remove text
            var cur = $(this);
            $(".default_ship_address").each(function() {
                $(this).val(0);
            });
            cur.siblings('.default_ship_address').val(1);
        });

        $("body").on('change', '.shipping_country_multiple', function() {
            var id = $(this).data('id');
            var country_id = $(this).val();
            var country = $(this);
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('customer_details/admin/getState'); ?>",
                data: 'country_id=' + country_id,
                success: function(result) {
                    country.closest('.row').find('select.shipping_state_multiple[data-id='+id+']').html(result);
                }
            });
        });        
    });
</script>