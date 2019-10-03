<style type="text/css">
    .ui-autocomplete{ z-index: 9999; }  
    .table-addorder > tbody > tr > td > .btn,.table-addorder > tbody > tr:focus > td > .btn,.table-addorder > tbody > tr:active > td > .btn{display:none;opacity:0; transition: all 0.3s;}

    .table-addorder > tbody > tr:hover > td > .btn{opacity:1; display: block}
    .table-addorder .form-control,.table-addorder .form-control:hover,.table-addorder .form-control:focus,.table-addorder .form-control:active{min-height: 30px !important; box-shadow: none;}
    .form-control.product-unit,.form-control.product-name,.form-control.product-description{height: 31px !important}
    .form-control.product-quantity{height: 31px !important; width:75px; min-height: 31px; padding-left: 5px;}
    .search_product {
        border-color: transparent;
        height: 40px !important;
    }
    .form-control[readonly]{
        background: none !important;
        border: none !important;
    }
    .disabledDiv{
        pointer-events: none;
        opacity: 0.4;
    }
    .alert-warning {
        color: #8a6d3b !important;
        background-color: #fcf8e3 !important;
        border-color: #faebcc !important;
    }
    .product-search-row:hover,.product-search-row:hover td{background: transparent !important}
    .tax-percent,.cess-percent{padding: 0 !important;line-height: 1; border:0; background: transparent; float: right; width:50%}
    .product-description-td {
        position: relative;
        overflow: visible !important;
    }
    .product-description:focus {
        width: 295px;
        position: absolute;
        right: 0;
        z-index: 9;
        top: 21px;
    }
</style>
<div class="side_toggle">
    <div id="myDiv"><button class="btn btn-sm btn-danger myButton  btn-closePanel"><i class="fa fa-times"></i></button>
        <form style="padding:20px;">    
            <div class="form-group">
                <label for="">Form Submission</label>
                <div class="form-group"> 
                    <?php if($action != 't'): ?>
                    <div class="radio">
                        <label><input type="radio" value="1" name="activity_submit" <?php echo ($action != 't')? 'checked':''; ?>>Submit &amp; Show New Form</label>
                    </div>
                    <?php endif ?>
                    <div class="radio">
                        <label><input type="radio" value="2" name="activity_submit" <?php echo ($action == 't')? 'checked':''; ?> >Submit &amp; Show List</label>
                    </div>
                    <?php if($action != 't'): ?>
                    <div class="radio">
                        <label><input type="radio" value="3" name="activity_submit">Submit &amp; View</label>
                    </div>
                    <?php endif ?>
                </div>
            </div>

            <div class="form-group">
                <label for="">Adder Account</label>
                <div class="form-group"> 
                    <a href="javascript:void(0);" class="new-ledger-btn">Add Ledger</a><span class="pull-right text-muted">Ctrl+Shift+l</span><br>
                    <a href="javascript:void(0);" class="add-group-btn">Add Group</a><span class="pull-right text-muted">Ctrl+Alt+g</span><br>
                    <!-- <a href="<?php echo site_url('admin/add-category-subcategory'); ?>"  target="_blank">Add Category</a><br> -->
                    <!-- <a href="<?php echo site_url('admin/add-product-attributes'); ?>" target="_blank">Add Attribute</a><br> -->
                    <!-- <a href="<?php echo site_url('admin/unit-add'); ?>" target="_blank">Add Unit</a><br> -->
                    <!-- <a href="<?php echo site_url('admin/add-products'); ?>" target="_blank">Add Product</a><br> -->
                    <!-- <a href="<?php echo site_url('admin/add-service'); ?>" target="_blank">Add Service</a> -->

                </div>
            </div>
            <div class="form-group">
                <label for="">Select currency</label>
                <div class="form-group"> 
                    <select class="form-control" id="selected_currency">
                        <?php
                        if (count($currency) > 0):
                            foreach ($currency as $value) {
                                ?>
                                <option value="<?php echo $value->id ?>" <?php echo ($value->id == 6) ? 'selected' : '' ?>><?php echo $value->currency ?></option>
                                <?php
                            }
                        endif;
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="">Make this entry as post dated entry?</label>
                <div class="form-group"> 
                    <label class="radio-inline"><input type="radio" value="1" name="postdated">Yes</label>
                    <label class="radio-inline"><input type="radio" value="0" name="postdated" checked="true">No</label>

                </div>
            </div>
            <?php if ($transaction_type_id == 5 || $transaction_type_id == 6 || $transaction_type_id == 12 || $transaction_type_id == 14 || $parent_id == 5 || $parent_id == 6 || $parent_id == 12 || $parent_id == 14): ?>
                <!-- <div class="form-group">
                    <label for="">Do you want service entry?</label>
                    <div class="form-group <?php echo (isset($entry->is_service_product) && $entry->is_service_product == 1) ? 'disabledDiv' : '' ?>"> 
                        <label class="radio-inline"><input type="radio" value="1" name="service_product" <?php echo (isset($entry->is_service_product) && $entry->is_service_product == 1) ? 'checked' : '' ?>>Yes</label>
                        <label class="radio-inline"><input type="radio" value="0" name="service_product" <?php echo ((isset($entry->is_service_product) && $entry->is_service_product == 0) || (!isset($entry->is_service_product))) ? 'checked' : '' ?>>No</label>

                    </div>
                </div> -->
            <div class="form-group">
                <label for="">Do you want reverse entry with respect to branch?</label>
                <div class="form-group"> 
                    <label class="radio-inline"><input type="radio" value="1" name="select_branch_entry_no" class="branch-entry-no">Yes</label>
                    <label class="radio-inline"><input type="radio" value="0" name="select_branch_entry_no" checked="true" class="branch-entry-no">No</label>

                </div>
            </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="">Do you want to set tax 0 for different export country?</label>
                <div class="form-group"> 
                    <label class="radio-inline"><input type="radio" value="1" name="tax_status_country" class="tax-status-country">Yes</label>
                    <label class="radio-inline"><input type="radio" value="0" name="tax_status_country" checked="true" class="tax-status-country">No</label>

                </div>
            </div>
            <?php
            if (isset($transaction_type_id) && ($transaction_type_id == 6 || $parent_id == 6)):
                ?>
                <div class="form-group">
                    <label for="">Do you want reverse entry?</label>
                    <div class="form-group"> 
                        <label class="radio-inline"><input type="radio" value="1" name="reverse_entry" <?php echo (isset($entry->is_reverse_entry) && $entry->is_reverse_entry == 1) ? 'checked' : '' ?>>Yes</label>
                        <label class="radio-inline"><input type="radio" value="0" name="reverse_entry" <?php echo (isset($entry->is_reverse_entry) && $entry->is_reverse_entry == 0) ? 'checked' : '' ?>>No</label>

                    </div>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>
<!-- Content Wrapper. Contains page content -->
<?php if ($transaction_type_id == 5 || $transaction_type_id == 6 || $parent_id == 5 || $parent_id == 6): ?>
    <?php if ($action == 'e'): ?>
        <form method="POST" action="<?php echo base_url(); ?>transaction_inventory/inventory/ajax_update_transaction" class="formSubmitAll">
        <?php elseif ($action == 't'): ?>
            <form method="POST" action="<?php echo base_url(); ?>transaction/accounts_inventory/ajax_order_add" class="formSubmitAll">
            <?php else: ?>
                <form method="POST" action="<?php echo base_url(); ?>transaction/accounts_inventory/ajax_save_form_data" class="formSubmitAll">
                <?php endif; ?>
            <?php elseif ($transaction_type_id == 7 || $transaction_type_id == 25 || $transaction_type_id == 8 || $parent_id == 7 || $parent_id == 25 || $parent_id == 8): ?>
                <?php if ($action == 'e'): ?>
                    <form method="POST" action="<?php echo base_url(); ?>transaction_inventory/inventory/update_quotation_data" class="formSubmitAll">
                    <?php elseif ($action == 't'): ?>
                        <form method="POST" action="<?php echo base_url(); ?>transaction_inventory/inventory/ajax_add_order_data" class="formSubmitAll">
                        <?php else: ?>
                            <form method="POST" action="<?php echo base_url(); ?>transaction_inventory/inventory/quotation_add" class="formSubmitAll">
                            <?php endif; ?>
                        <?php elseif ($transaction_type_id == 9 || $transaction_type_id == 10 || $parent_id == 9 || $parent_id == 10): ?>
                            <?php if ($action == 'e'): ?>
                                <form method="POST" action="<?php echo base_url(); ?>transaction_inventory/inventory/ajax_update_note" class="formSubmitAll">
                                <?php elseif ($action == 't'): ?>
                                    <form method="POST" action="<?php echo base_url(); ?>transaction/accounts_inventory/ajax_note_add_final" class="formSubmitAll">
                                    <?php else: ?>
                                        <form method="POST" action="<?php echo base_url(); ?>transaction_inventory/inventory/ajax_add_note" class="formSubmitAll">
                                        <?php endif; ?>
                                    <?php elseif ($transaction_type_id == 14 || $transaction_type_id == 12 || $parent_id == 14 || $parent_id == 12): ?>
                                        <?php if ($action == 'e'): ?>
                                            <form method="POST" action="<?php echo base_url(); ?>transaction_inventory/inventory/ajax_update_transaction" class="formSubmitAll">   
                                            <?php else: ?>
                                                <form method="POST" action="<?php echo base_url(); ?>transaction/accounts_inventory/ajax_add_cr_dr_note" class="formSubmitAll">
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <script>
                                                var tran_type =<?php echo isset($transaction_type_id) ? $transaction_type_id : ''; ?>;
                                                var parent_id =<?php echo isset($parent_id) ? $parent_id : ''; ?>;
                                            </script>          
                                            <div class="">
                                                <!-- Content Header (Page header) -->
                                                <section class="content-header">
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                             <h1><i class="fa fa-list"></i> Quotation</h1>
                                                            
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <div class="pull-right">                                                                
                                                                <!-- <button type="button" class="myButton btn btn-settings btn-svg"><i data-feather="settings"></i></button> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>
                                                <section>
                                                    <?php
                                                    $this->breadcrumbs->push('Home', '/admin/dashboard');
                                                    $this->breadcrumbs->push('Quotations', '/admin/quotation-list');
                                                    $this->breadcrumbs->push('Quotation', '/admin/quotation');
                                                    $this->breadcrumbs->show();
                                                    ?>
                                                </section>
                                                <!-- Main content -->
                                                <input type="hidden" name="igst_status" id="igst_status" value="<?php echo isset($order->is_igst_included) ? $order->is_igst_included : '' ?>">
                                                <section class="content"> 
                                                    <div class="box">            
                                                        <div class="box-body">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group invice_number">
                                                                        <label>Invoice Number</label><span id="duplicate_entryno_msg" style="color: red;"></span>
                                                                        <input type="hidden" name="tr_branch_id" class="tr_branch_id" value="">
                                                                        <input type="hidden" id="action" value="<?php echo $action; ?>">
                                                                        <input type="hidden" id="entry_type_id" value="<?php echo isset($transaction_type_id) ? $transaction_type_id : ''; ?>">
                                                                        <input type="hidden" id="parent_id" value="<?php echo isset($parent_id) ? $parent_id : ''; ?>">
                                                                        <input type="hidden" id="entry_id" name="entry_id" value="<?php echo isset($entry->id) ? $entry->id : ''; ?>">
                                                                        <input type="hidden" id="bank_id" name="bank_id" value="<?php echo isset($bank_id) ? $bank_id : ''; ?>">
                                                                        <input type="text" class="form-control entry_no" autofocus placeholder="Enter entry no" <?php echo ($action == 'e') ? 'readonly' : (($auto_no_status == "1") ? 'readonly' : '') ?> value="<?php echo ($action == 'e') ? $entry->entry_no : (($auto_no_status == "1") ? 'Auto' : '') ?>" name="entry_number" />    
                                                                        <!--despatch details-->
                                                                        <input type="hidden"  name="despatch_doc_no" id="despatch_doc_no" value="<?php echo isset($courier->despatch_doc_no) ? $courier->despatch_doc_no : ''; ?>" >
                                                                        <input type="hidden"  name="despatch_through" id="despatch_through" value="<?php echo isset($courier->despatch_through) ? $courier->despatch_through : ''; ?>" >
                                                                        <input type="hidden"  name="courier_gstn" id="courier_gstn" value="<?php echo isset($courier->courier_gstn) ? $courier->courier_gstn : ''; ?>" >
                                                                        <input type="hidden"  name="destination" id="destination" value="<?php echo isset($courier->destination) ? $courier->destination : ''; ?>" >
                                                                        <input type="hidden"  name="bill_lr_rr" id="bill_lr_rr" value="<?php echo isset($courier->bill_lr_rr) ? $courier->bill_lr_rr : ''; ?>" >
                                                                        <input type="hidden"  name="bill_lr_rr_date" id="bill_lr_rr_date" value="<?php echo isset($courier->bill_lr_rr_date) ? $courier->bill_lr_rr_date : ''; ?>" >
                                                                        <input type="hidden"  name="motor_vehicle_no" id="motor_vehicle_no" value="<?php echo isset($courier->motor_vehicle_no) ? $courier->motor_vehicle_no : ''; ?>" >
                                                                        <?php if ($action == "t"): ?>
                                                                            <input type="hidden" name="quotation_id" value="<?php echo isset($entry->id) ? $entry->id : ''; ?>">
                                                                        <?php endif ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Date </label>
                                                                        <!-- <input class="form-control" type="text" id="tr_date" placeholder="DD/MM/YYYY" <?php echo ($action == 'e') ? 'readonly' : (($auto_date == 1) ? 'readonly' : '') ?> value="<?php echo ($action == 'e') ? date('d/m/Y', strtotime($entry->create_date)) : (($auto_date == "1") ? date('d/m/Y') : '') ?>" name="date_form"/> -->
                                                                        <input class="form-control" type="text" id="tr_date" placeholder="DD/MM/YYYY" value="<?php if($action == 'e') { echo date('d/m/Y', strtotime($entry->create_date)); } else { echo date('d/m/Y'); } ?>" name="date_form" <?php if($auto_date == 1) { echo "readonly"; } ?>/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>
                                                                            <?php
                                                                            if ($transaction_type_id == 5 || $transaction_type_id == 7 || $transaction_type_id == 25 || $transaction_type_id == 10 || $parent_id == 5 || $parent_id == 7 || $parent_id == 25 || $parent_id == 10):
                                                                                echo 'Debtors';
                                                                            elseif ($transaction_type_id == 6 || $transaction_type_id == 8 || $transaction_type_id == 9 || ($parent_id == 6 && $transaction_type_id != 15) || $parent_id == 8 || $parent_id == 9):
                                                                                echo 'Creditor';
                                                                            endif;
                                                                            ?> 
                                                                        </label>
                                                                        <input type="text" class="form-control entry_debtors" name="tr_ledger[]" value="<?php echo isset($entry_details[0]->ladger_name) ? $entry_details[0]->ladger_name : '' ?>" autocomplete="off" placeholder="Click to select" />
                                                                        <input type="hidden" class="tr_ledger_id_debtors" name="tr_ledger_id[]" value="<?php echo isset($entry_details[0]->ladger_id) ? $entry_details[0]->ladger_id : '' ?>"/>
                                                                        <input type="hidden" class="in_ledger_state" name="in_ledger_state" value="<?php echo isset($ledger['state']) ? $ledger['state'] : '' ?>"/>
                                                                        <input type="hidden" class="in_ledger_country" name="in_ledger_country" value="<?php echo isset($ledger['country']) ? $ledger['country'] : '' ?>"/>
                                                                        <input type="hidden" class="tr_type_debtors" name="tr_type[]" value="<?php echo isset($entry_details[0]->account) ? $entry_details[0]->account : '' ?>"/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>
                                                                            <?php
                                                                            if ($transaction_type_id == 5 || $transaction_type_id == 7 || $transaction_type_id == 25 || $transaction_type_id == 10 || $parent_id == 5 || $parent_id == 7 || $parent_id == 25 || $parent_id == 10):
                                                                                echo 'Sales';
                                                                            elseif ($transaction_type_id == 6 || $transaction_type_id == 8 || $transaction_type_id == 9 || ($parent_id == 6 && $transaction_type_id != 15) || $parent_id == 8 || $parent_id == 9):
                                                                                echo 'Purchase';
                                                                            endif;
                                                                            ?>  
                                                                        </label>
                                                                        <input type="text" class="form-control entry_sales" name="tr_ledger[]" value="<?php echo isset($entry_details[1]->ladger_name) ? $entry_details[1]->ladger_name : '' ?>" autocomplete="off" placeholder="Click to select"/>
                                                                        <input type="hidden" class="tr_ledger_id_sales" name="tr_ledger_id[]" value="<?php echo isset($entry_details[1]->ladger_id) ? $entry_details[1]->ladger_id : '' ?>"/>
                                                                        <input type="hidden" class="tr_type_creditors" name="tr_type[]" value="<?php echo isset($entry_details[1]->account) ? $entry_details[1]->account : '' ?>"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3"> 
                                                                    <?php if ($transaction_type_id == 5 || $parent_id == 5): ?>
                                                                        <input type="text" name="advance_bill_name" class="form-control advance_bill_name"  placeholder="Advance Bill Name" value="<?php echo (isset($entry->is_advance_sell) && $entry->is_advance_sell == 1) ? $entry->entry_no : '' ?>">
                                                                    <?php endif; ?>
                                                                </div> 
                                                                <div class="col-md-3">
                                                                    <?php // if ($recurring == 1): ?>
                                                                    <?php if ($recurring == 1 && ($transaction_type_id == 5 || $parent_id == 5)): ?>
                                                                        <input type="text" name="recurring_freq" class="form-control recurring" value="<?php echo isset($entry->frequency) ? $entry->frequency : '' ?>" placeholder="Recurring Frequency">
                                                                    <?php endif; ?>
                                                                </div>

                                                                <?php
                                                                if ($action === 't' && ($entry_type == 7 || $entry_type == 25 || $entry_type == 8 || $parent_id == 7 || $parent_id == 25 || $parent_id == 8)) {
                                                                    ?>
                                                                    <div class="col-md-3"><input class="form-control" name="voucher_no" placeholder="Ref. No" value="<?php echo isset($entry->entry_no) ? $entry->entry_no : ''; ?>"><span id="voucher_no_msg" style="color:red;"></span></div> 
                                                                    <div class="col-md-3"><input class="form-control" name="voucher_date" id="voucher_date" placeholder="Ref. Date" value="<?php echo (isset($entry->create_date) && $entry->voucher_date != '1970-01-01') ? date("d/m/Y", strtotime($entry->create_date)) : ''; ?>"></div> 
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <div class="col-md-3"><input class="form-control" name="voucher_no" placeholder="<?php echo ($entry_type==6 || $parent_id==6)?'Supplier Invoice No':'Ref. No'?>" value="<?php echo isset($entry->voucher_no) ? $entry->voucher_no : ''; ?>"><span id="voucher_no_msg" style="color:red;"></span></div> 
                                                                    <div class="col-md-3"><input class="form-control" name="voucher_date" id="voucher_date" placeholder="<?php echo ($entry_type==6 || $parent_id==6)?'Supplier Invoice Date':'Ref. Date'?>" value="<?php echo (isset($entry->voucher_date) && $entry->voucher_date != '1970-01-01') ? date("d/m/Y", strtotime($entry->voucher_date)) : ''; ?>"></div> 
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>

                                                            <div class="row" id="branch-div" style="display:none;">
                                                            <div class="col-md-3"><input class="form-control" name="branch_entry_no" placeholder="Branch Entry No" value=""></div>     
                                                            </div>
                                                            <hr style="margin:10px 0 20px;">

                                                            <div class="row debtors_details" style="display:<?php echo ($action == 'a' || !isset($ledger['Bi_companyName'])) ? 'none' : 'block'; ?>"> 
                                                                <div class="col-md-3 billing_addr" style="display:<?php echo ($action == 'a' || !isset($ledger['Bi_address'])) ? 'none' : 'block'; ?>">
                                                                    <label style="margin-bottom: 5px;">Billing Address</label>
                                                                    <p><strong class="c_name"><?php echo isset($ledger['Bi_companyName']) ? $ledger['Bi_companyName'] : '' ?></strong></p>
                                                                    <p><span class="addr"><?php echo isset($ledger['Bi_address']) ? $ledger['Bi_address'] : '' ?></span>
                                                                        <br><span class="city"><?php echo isset($ledger['Bi_city']) ? $ledger['Bi_city'] : '' ?></span> - <span class="zip"><?php echo isset($ledger['Bi_zip']) ? $ledger['Bi_zip'] : '' ?></span>, 
                                                                        <span class="state"><?php echo isset($ledger['Bi_state']) ? $ledger['Bi_state'] : '' ?></span>, <span class="country"><?php echo isset($ledger['Bi_country']) ? $ledger['Bi_country'] : '' ?></span> </p>
                                                                    <p>GSTN: <span class="tax"><?php echo isset($ledger['Bi_tax']) ? $ledger['Bi_tax'] : '' ?></span></p>
                                                                </div>                    
                                                                <div class="col-md-3 shipping_addr" style="display:<?php echo ($action == 'a' || !isset($ledger['Sh_companyName'])) ? 'none' : 'block'; ?>">
                                                                    <label style="margin-bottom: 5px;">Shipping Address</label>
                                                                    <p><strong class="c_name"><?php echo isset($ledger['Sh_companyName']) ? $ledger['Sh_companyName'] : '' ?></strong></p>
                                                                    <p><span class="addr"><?php echo isset($ledger['Sh_address']) ? $ledger['Sh_address'] : '' ?></span><br>
                                                                        <span class="city"><?php  echo isset($ledger['Sh_city']) ? $ledger['Sh_city'] : '' ?></span> - <span class="zip"><?php echo isset($ledger['Sh_zip']) ? $ledger['Sh_zip'] : '' ?></span>, 
                                                                        <span class="state"><?php echo isset($ledger['Sh_state']) ? $ledger['Sh_state'] : '' ?></span>, <span class="country"><?php echo isset($ledger['Sh_country']) ? $ledger['Sh_country'] : '' ?></span> </p>
                                                                    <p style="display:none;" >GSTN: <span class="tax"><?php echo isset($ledger['Sh_tax']) ? $ledger['Sh_tax'] : '' ?></span></p>
                                                                    <input type="hidden" class="shipping_id" name="shipping_id" value="1"/>
                                                                </div>
                                                                <div class="col-md-3 credit_days_div" style="display:<?php echo ($action == 'a' || !isset($ledger['LL_creditDays'])) ? 'none' : 'block'; ?>">
                                                                    <div class="form-group">
                                                                        <label>Credit Days</label>
                                                                        <input type="text" class="form-control credit_days" name="credit_days" value="<?php echo isset($ledger['LL_creditDays']) ? $ledger['LL_creditDays'] : '' ?>"/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 credit_limit_div" style="display:<?php echo ($action == 'a' || !isset($ledger['LL_creditLimit'])) ? 'none' : 'block'; ?>">
                                                                    <div class="form-group">
                                                                        <label>Credit Limit</label>
                                                                        <input type="text" class="form-control credit_limit" readonly="readonly" value="<?php echo isset($ledger['LL_creditLimit']) ? $ledger['LL_creditLimit'] : '' ?>"/>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            
                                                            <div class="row">
                                                                <?php 
                                                                    $displayFlag = TRUE;
                                                                ?>
                                                                <div class="col-md-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-addorder">
                                                                        <thead>
                                                                            <tr>
                                                                                <th height="40"></th>
                                                                                <th>Name</th>
                                                                                <th class="width-80">Description</th>
                                                                                <th class="width-50 text-center">Qty.</th>
                                                                                <th class="text-center">Unit</th>
                                                                                <th class="width-75 text-center">Rate</th>
                                                                                <th class="width-60 text-right">Dis.(%)</th>
                                                                                <th style="display:none" class="width-150 text-right">Total</th>
                                                                                <th style="<?php echo ($displayFlag)?'':'display:none'; ?>" class="width-110 text-right">Gross Total</th>
                                                                                <th style="<?php echo ($displayFlag)?'':'display:none'; ?>" class="width-90">Tax(%)</th>
                                                                                <th style="<?php echo ($displayFlag)?'':'display:none'; ?>" class="width-110 text-right">Tax Value</th>

                                                                                <th class="width-120 text-right">Total</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="product-listing-table">
                                                                            <?php
                                                                            $total_qty = 0.00;
                                                                            $total_tax_val = 0.00;
                                                                            $total_product_val = 0.00;
                                                                            $gross_total_product_val = 0.00;
                                                                            $grand_product_val = 0.00;
                                                                            $netTotal = 0.00;
                                                                            if (isset($order_details) && count($order_details) > 0):
                                                                                foreach ($order_details as $row) {
                                                                                    ?>
                                                                                    <tr class="each-product">
                                                                                        <td height="40"><button class="btn btn-xs btn-danger removeitem"><i class="fa fa-trash-o"></i></button>
                                                                                            <input type="hidden" class="product_id_hidden" name="product_id[]" value="<?php echo isset($row->product_id) ? $row->product_id : '' ?>">
                                                                                            <input type="hidden" class="stock_id_hidden" name="stock_id[]" value="<?php echo isset($row->stock_id) ? $row->stock_id : '' ?>">
                                                                                            <input type="hidden" class="cess-status-col" name="cess_status_col[]" value="<?php echo (intval($row->cess_tax) !== 0) ? '1' : '0'; ?>">
                                                                                        </td>
                                                                                        <td><input type="text" class="product-name text-left form-control" name="product_name[]" value="<?php echo isset($row->name) ? $row->name : '' ?>"/></td>
                                                                                        <td class="product-description-td"><input type="text" class="product-description text-left form-control" name="product_description[]" value="<?php echo isset($row->product_description) ? $row->product_description : '' ?>"/></td>
                                                                                        <td><input type="text" class="form-control text-right product-quantity" name="product_quantity[]"  value="<?php echo isset($row->quantity) ? $row->quantity + 0 : '1' ?>"/><input type="hidden" class="max-product-qty" value="<?php echo isset($row->quantity) ? $row->quantity : '1' ?>"></td>
                                                                                        <td  style="width: 75px;"><input type="text" class="form-control product-unit text-center" readonly="readonly" name="product_unit[]" value="<?php echo isset($row->unit_name) ? $row->unit_name : '' ?>"/><input type="hidden" class="product-unit-hidden-id" name="product_unit_hidden_id"></td>
                                                                                        <td><input type="text" class="form-control text-right product-price" name="product_price[]" value="<?php echo isset($row->base_price) ? $row->base_price : '' ?>"/></td>
                                                                                        <td><input type="text" class="form-control text-right product-discount" name="product_discount[]" value="<?php echo isset($row->discount_percentage) ? $row->discount_percentage : '' ?>"/></td>
                                                                                        <?php
                                                                                        $tax_val = ($row->igst_tax + $row->cgst_tax + $row->sgst_tax + $row->cess_tax);
                                                                                        $product_val = $row->quantity * $row->base_price;
                                                                                        $product_total = (($row->quantity * $row->price) + $tax_val);
                                                                                        $total_qty+=$row->quantity;
                                                                                        $total_tax_val+=$tax_val;
                                                                                        $total_product_val+=$product_val;
                                                                                        $gross_total_product_val+=$row->price;
                                                                                        $grand_product_val+=$row->total;
                                                                                        ?>
                                                                                        <td style="display:none;"><input  type="text" readonly="readonly" class="total-price-per-prod form-control text-right" name="total_price_per_prod[]" value="<?php echo $product_val ?>"/></td>
                                                                                        <td><input type="text" readonly="readonly" class="total-price-per-prod form-control text-right" name="gross_total_price_per_prod[]" value="<?php echo $row->price ?>"/></td>
                                                                                        <td>
                                                                                            <?php
                                                                                            if (intval($row->igst_tax) !== 0) {
                                                                                                ?>
                                                                                                <div style="font-size: 11px; margin-bottom: 5px" class="clearfix"><span class="pull-left" style="width: 50%;">IGST:</span> <input type="text" class="tax-percent" name="igst_tax_percent[]" readonly="readonly"  value="<?php echo $row->igst_tax_percent ?>"></div>
                                                                                            <?php } else { ?>
                                                                                                <div style="font-size: 11px; margin-bottom: 5px" class="clearfix"><span class="pull-left" style="width: 50%;">CGST:</span> <input type="text" class="tax-percent " name="cgst_tax_percent[]" readonly="readonly" value="<?php echo $row->cgst_tax_percent ?>"></div>
                                                                                                <div style="font-size: 11px; margin-bottom: 5px" class="clearfix"><span class="pull-left" style="width: 50%;">SGST:</span><input type="text" class="tax-percent" name="sgst_tax_percent[]" readonly="readonly" value="<?php echo $row->sgst_tax_percent ?>"></div>
                                                                                            <?php } ?>
                                                                                            <?php
                                                                                            if ($order->is_cess_included) {
                                                                                                ?>
                                                                                                <div class="clearfix" style="font-size: 11px; margin-top:-6px; float:left;display: <?php echo (intval($row->cess_tax) !== 0) ? 'block' : 'none'; ?>;"><span class="pull-left" style="width: 50%;">CESS:</span><input type="text" class="cess-percent" name="cess_percent[]" readonly="readonly"  value="<?php echo $row->cess_tax_percent ?>"></div>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php
                                                                                            if (intval($row->igst_tax) !== 0) {
                                                                                                ?>
                                                                                                <div style="font-size: 11px;"><input type="text" readonly="readonly" class="tax-value form-control text-right" name="igst_tax_value[]" style="margin-top:-11px; font-size:11px;" value="<?php echo $row->igst_tax ?>"></div>
                                                                                            <?php } else { ?>
                                                                                                <div style="font-size: 11px;"><input type="text" readonly="readonly" class="tax-value form-control text-right" name="cgst_tax_value[]" style="margin-top:-11px; font-size:11px;" value="<?php echo $row->cgst_tax ?>"></div>
                                                                                                <div style="font-size: 11px;"><input type="text" readonly="readonly" class="tax-value form-control text-right" name="sgst_tax_value[]" style="margin-top:-12px; font-size:11px;" value="<?php echo $row->sgst_tax ?>"></div>
                                                                                            <?php } ?>
                                                                                            <?php
                                                                                            if ($order->is_cess_included) {
                                                                                                ?>
                                                                                                <div style="font-size: 11px; display: <?php echo (intval($row->cess_tax) !== 0) ? 'block' : 'none'; ?>;"><input type="text" readonly="readonly" class="cess-value form-control text-right" name="cess_value[]" style="margin-top:-12px; font-size:11px;" value="<?php echo $row->cess_tax ?>"></div>
                                                                                            <?php } ?>
                                                                                        </td>

                                                                                        <td><input type="text" readonly="readonly" class="total-price-per-prod-with-tax form-control text-right" name="total_price_per_prod_with_tax[]" value="<?php echo isset($row->total) ? $row->total : '' ?>"/></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                            endif;
                                                                            ?>
                                                                            <tr class="product-search-row">

                                                                                <td height="40" colspan="11" style="padding: 2px !important">
                                                                                    <?php if ($action !== 't' && ($entry_type != 7 || $entry_type != 8 || $parent_id != 7 || $parent_id != 8)): ?>
                                                                                        <input type="text" class="form-control search_product" placeholder="Search product..." style="min-height:40px"/>
                                                                                        <input type="hidden" class="search_product_id" name="search_product_id[]"/>
                                                                                    <?php else:; ?>
                                                                                        <div class="alert alert-warning">
                                                                                            <p>You can not add no more new product now.</p>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </td>

                                                                            </tr> 
                                                                            <tr class="sub-total" style="border-bottom: 1px solid #e5e5e5; background: #f1f1f1;">                                                                                <td></td>     
                                                                                <td height="40"></td>    
                                                                                <td>&nbsp;</td>
                                                                                <td class="text-right" id="quantity"><?php $total_qty; ?></td>
                                                                                <td>&nbsp;</td>
                                                                                <td>&nbsp;</td>
                                                                                <td>&nbsp;</td>
                                                                                <td style="display:none" class="text-right" id="product_total" ><?php echo $total_product_val; ?></td>
                                                                                <td class="text-right" id="product_total_gross" ><?php echo number_format((float)$gross_total_product_val, 2, '.', ''); ?></td>
                                                                                <td colspan="2" style="padding-top:0 !important; padding-bottom: 0 !important "><input type="text" name="tax_value" id="tax_value" class="form-control text-right" readonly="readonly" value="<?php echo number_format((float)$total_tax_val, 2, '.', ''); ?>" style="height:35px !important"/></td>
                                                                                <td style="padding-top:0 !important; padding-bottom: 0 !important "><input type="text" name="product_grand_total" id="product_grand_total" class="form-control text-right" readonly="readonly" value="<?php echo number_format((float)$grand_product_val, 2, '.', ''); ?>"  style="height:35px !important"/></td>
                                                                            </tr>                               
                                                                        </tbody>
                                                                    </table>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="row">                                                            
                                                                <div class="col-md-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-addorder-total discounts-table" style="margin:0; border: 0 !important">
                                                                        <tbody>

                                                                            <?php
                                                                            $discount = 0;
                                                                            $total_discount = 0;
                                                                            if ($action == 'e' || $action == 't'):
                                                                                $netTotal = $grand_product_val;
                                                                                if ($order->is_igst_included && $order->is_cess_included) {
                                                                                    $index = 4;
                                                                                } else if ($order->is_igst_included && !$order->is_cess_included) {
                                                                                    $index = 3;
                                                                                } else if (!$order->is_igst_included && $order->is_cess_included) {
                                                                                    $index = 5;
                                                                                } else if (!$order->is_igst_included && !$order->is_cess_included) {
                                                                                    $index = 4;
                                                                                }
                                                                                if (isset($entry_details) && count($entry_details) > 3):
                                                                                    for ($i = $index; $i < (count($entry_details)); $i++) {
                                                                                        $total_discount = isset($entry_details[$i]->balance) ? $entry_details[$i]->balance : 0;
                                                                                        
                                                                                        
                                                                                        ?>
                                                                                        <tr class="discounts-tr">
                                                                                            <td style="border: 0 !important; padding-left: 0 !important">
                                                                                                <input type="text" class="form-control discount_allowed_ledger"  name="tr_ledger[]" value="<?php echo isset($entry_details[$i]->ladger_name) ? $entry_details[$i]->ladger_name : '' ?>" autocomplete="off"/>
                                                                                                <input type="hidden" class="discount_allowed_ledger_hidden" name="tr_ledger_id[]" value="<?php echo isset($entry_details[$i]->ladger_id) ? $entry_details[$i]->ladger_id : '' ?>"/>
                                                                                                <input type="hidden" class="discount_acc_type_hidden" name="tr_type[]" value="<?php echo isset($entry_details[$i]->account) ? $entry_details[$i]->account : '' ?>"/>  
                                                                                            </td>
                                                                                            <td style="border: 0 !important; width:150px;  padding-right: 0 !important">
<!--                                                                                                <input type="text" class="form-control discount_allowed_input"  style="text-align: right;" value="<?php echo (isset($entry_details[$i]->balance) && $entry_details[$i]->discount_type == 1) ? $entry_details[$i]->discount_amount . '%' : ((isset($entry_details[$i]->balance) && $entry_details[$i]->discount_type == 0) ? $entry_details[$i]->balance : '0.00') ?>"/>
                                                                                                <input type="hidden" class="discount_percent_hidden" name="discount_percent_hidden[]"  value="<?php echo (isset($entry_details[$i]->balance) && $entry_details[$i]->discount_type == 1) ? $entry_details[$i]->discount_amount : ((isset($entry_details[$i]->balance) && $entry_details[$i]->discount_type == 0) ? $entry_details[$i]->balance : '0.00') ?>"/>
                                                                                                <input type="hidden" class="discount_value_hidden" name="discount_value_hidden[]" value="<?php echo isset($entry_details[$i]->balance) ? $entry_details[$i]->balance : '0' ?>"/>-->
                                                                                                 <?php
                                                                                                    $discount_value = 0;
                                                                                                    $tran_type = $entry_type;
                                                                                                    $parent_id = $parent_id;
                                                                                                    if ($tran_type == 5 || $tran_type == 7 || $tran_type == 25 || $tran_type == 10 || $tran_type == 12 || $parent_id == 5 || $parent_id == 7 || $parent_id == 25 || $parent_id == 10 || $parent_id == 12) {
                                                                                                        if(isset($entry_details[$i]->account) &&  $entry_details[$i]->account == 'Dr'){
                                                                                                            $discount_value = isset($entry_details[$i]->balance) ? $entry_details[$i]->balance : '';
                                                                                                            $netTotal -= $discount_value;
                                                                                                        }else{
                                                                                                            $discount_value = isset($entry_details[$i]->balance) ? '-'.$entry_details[$i]->balance : '';
                                                                                                            $netTotal -= $discount_value;
                                                                                                        }
                                                                                                        
                                                                                                    } else if ($tran_type == 6 || $tran_type == 8 || $tran_type == 9 || $tran_type == 14 || $parent_id == 6 || $parent_id == 8 || $parent_id == 9 || $parent_id == 14) {
                                                                                                        if(isset($entry_details[$i]->account) &&  $entry_details[$i]->account == 'Cr'){
                                                                                                            $discount_value = isset($entry_details[$i]->balance) ? $entry_details[$i]->balance : '';
                                                                                                            $netTotal -= $discount_value;
                                                                                                        }else{
                                                                                                            $discount_value = isset($entry_details[$i]->balance) ? '-'.$entry_details[$i]->balance : '';
                                                                                                            $netTotal -= $discount_value;
                                                                                                        }
                                                                                                    }
                                                                                                ?>
                                                                                                <input type="text" class="form-control discount_allowed_input" style="text-align: right;"  value="<?php echo $discount_value; ?>"/>
                                                                                                <input type="hidden" class="discount_value_hidden" name="discount_value_hidden[]" value="<?php echo $discount_value; ?>"/>
                                                                                                <input type="hidden" class="discount_percent_hidden" name="discount_percent_hidden[]"/>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                endif;
                                                                            endif;
                                                                            ?>
                                                                            <tr class="discounts-tr">
                                                                                <td style="border: 0 !important; padding-left: 0 !important">
                                                                                    <input type="text" class="form-control discount_allowed_ledger" style="min-height: 30px;" name="tr_ledger[]"/>
                                                                                    <input type="hidden" class="discount_allowed_ledger_hidden" name="tr_ledger_id[]"/>
                                                                                    <input type="hidden" class="discount_acc_type_hidden" name="tr_type[]"/>  
                                                                                </td>
                                                                                <td style="border: 0 !important; width:150px;  padding-right: 0 !important">
                                                                                    <input type="text" class="form-control discount_allowed_input" style="text-align: right;" value="<?php echo number_format((float)0, 2, '.', '');  ?>" disabled="disabled"/>
                                                                                    <input type="hidden" class="discount_value_hidden" name="discount_value_hidden[]"/>
                                                                                    <input type="hidden" class="discount_percent_hidden" name="discount_percent_hidden[]"/>
                                                                                </td>
                                                                            </tr>

                                                                        </tbody>
                                                                    </table>  
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-10 col-sm-8 col-xs-6  text-right" style="margin-top: 8px;font-size: 16px; font-weight: bold;">
                                                                    Total (<i class="fa fa-inr"></i>)
                                                                </div>

                                                                <div class="col-md-2 col-sm-4 col-xs-6">
                                                                    <input type="text" name="netTotal" id="netTotal" class="form-control text-right" readonly="readonly" value="<?php echo number_format((float)$netTotal, 2, '.', ''); ?>" style="height:35px !important; border: 1px solid #c6d6db !important;background: #eee !important;"/>
                                                                </div>

                                                                <!-- <div class="col-md-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-addorder-grandtotal" style="border:1px solid #ddd;">
                                                                            <tbody>
                                                                                <tr>                                            
                                                                                    <td height="40"> <strong></strong> </td>                                            
                                                                                    <td class="text-right"></td>                                                    
                                                                                    <td class="text-right" style="width:480px; padding-top: 0 !important; padding-bottom: 0 !important">
                                                                                        <input type="text" name="netTotal" id="netTotal" class="form-control text-right" readonly="readonly" value="<?php echo ($grand_product_val) ?>" style="height:35px !important"/>
                                                                                    </td>
                                                                                </tr>                                                
                                                                            </tbody>
                                                                        </table>
                                                                    </div>                                    
                                                                </div> --> 
                                                            </div>


                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <label style="padding-top: 10px">Notes</label>
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" name="notes" value="<?php echo isset($entry->narration) ? $entry->narration : '' ?>" placeholder="Write notes, if any... " />
                                                                    </div>
                                                                </div>                                                        


                                                                <div class="col-md-12">
                                                                    <div class="terms clearfix">
                                                                        <label>Terms &amp; Conditions:</label>
                                                                        <input type="text" class="form-control" name="terms_and_conditions" value="<?php echo isset($order->terms_and_conditions) ? $order->terms_and_conditions : 'Goods once sold cannot be returned except manufacturing defects.' ?>"/>
                                                                    </div>
                                                                </div> 

                                                            </div>
                                                        </div> 
                                                        <div class="box-footer">
                                                            <div class="footer-button" style="display: <?php echo ($action == 'a') ? 'none' : 'block'; ?>">
                                                                <button type="submit" class="btn ladda-button btn-primary" data-color="blue" data-style="slide-right" id="totalFormSubmitBtn">Save</button>
                                                            </div>
                                                        </div>


                                                    </div><!-- /.box-body -->                                                        

                                                </section>
                                            </div><!-- /.content-wrapper -->

                                        </form>


                                        <div id="trackingModal" class="modal fade" role="dialog">
                                            <div class="modal-dialog modal-sm" style="top: 70px;">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">    
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title" id="tr_tracking_ledger_name"></h4>
                                                        <input type="hidden" value="" id="tr_tracking_ledger_id_hidden" name="tr_tracking_ledger_id_hidden">
                                                        <input type="hidden" class="tr_acc_type_hidden">
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="form" role="form" id="tr_tracking_form">              
                                                            <!-- set 1-->
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-xs-7"><label>Tracking Name</label></div>
                                                                    <div class="col-xs-5"><label>Amount</label></div>              
                                                                </div> 
                                                            </div>  

                                                            <div id="tracking_container">  
                                                                <div class="form-group tracking-form-group">
                                                                    <div class="row tr_tracking_row" id="1">
                                                                        <div class="col-xs-7">
                                                                            <input type="text" name="tr_tracking_name_modal[]" class="form-control tr_tracking_name_modal">
                                                                            <input type="hidden" class="tr_tracking_id_modal" name="tr_tracking_id_modal[]">
                                                                        </div>
                                                                        <div class="col-xs-5">
                                                                            <input type="text" name="tr_tracking_amount_modal[]"  class="form-control tr_tracking_amount_modal">
                                                                        </div>              
                                                                    </div> 
                                                                </div>

                                                            </div>  


                                                        </form>        
                                                    </div> 
                                                    <div class="modal-footer">            
                                                        <div class="row">
                                                            <div class="col-xs-7 text-right"><label>Total</label></div>
                                                            <div class="col-xs-5"><input type="text" class="form-control" id="tr_total_sub_tracking" readonly="readonly"></div>              
                                                        </div>            
                                                    </div>  
                                                </div>

                                            </div>
                                        </div>
                                        <!-- ============= Modal Add Group ============ -->

                                        <!-- Modal -->
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
                                                                <!-- <div class="s2-example"> -->
                                                                    <!-- <p> -->
                                                                        <select class="select2 form-control" name="parent_id" id="parent_id">
                                                                            <option value="">Select parent</option>
                                                                            <?php
                                                                            // if (isset($groups)) {

                                                                            //     foreach ($groups as $group) {
                                                                            //         echo '<option value="' . $group["id"] . '">' . $group["group_name"] . '</option>';
                                                                            //     }
                                                                            // }
                                                                            ?>
                                                                        </select>
                                                                    <!-- </p> -->
                                                                <!-- </div>  -->
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


                                        <!-- Modal -->
                                        <div id="addLedger" class="modal fade" role="dialog">
                                            <div class="modal-dialog" style="top: 70px;">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <form action="<?php echo base_url(); ?>index.php/accounts/ajax_save_ledger_data" class="accounts-form" id='add_ledger_form_te'>

                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">
                                                                Add Ledger
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body" style="overflow-y: auto;">

                                                            <div class="row">

                                                                <div class="col-md-6">
                                                                    <div class="form-group input-block">
                                                                        <label>Group</label>
                                                                        <select class="select2 form-control" name="group_id" id="group_id" onchange="return checkGroup(this)">
                                                                            <option value="">Select group</option>
                                                                            <?php
                                                                            // if (isset($groups)) {

                                                                            //     foreach ($groups as $group) {
                                                                            //         echo '<option value="' . $group["id"] . '">' . $group["group_name"] . '</option>';
                                                                            //     }
                                                                            // }
                                                                            ?>
                                                                        </select>
                                                                        <input type="hidden" id="contact_required" name="contact_required" value="">
                                                                        <span class="errorMessage"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group input-block">
                                                                        <label>Ledger Name</label>
                                                                        <input type="text" class="form-control" id="ladger_name" name="ladger_name" placeholder="Ledger Name" autocomplete="off">                
                                                                        <span class="errorMessage"></span>
                                                                    </div>   
                                                                </div>


                                                                <!-- bank details section -->
                                                                <div id="bank_details_section" style="display: none;">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group input-block">
                                                                            <label>Bank Name</label>
                                                                            <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Bank Name"  autocomplete="off">
                                                                        </div>   
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group input-block">
                                                                            <label>Branch Name</label>
                                                                            <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Branch Name"  autocomplete="off">
                                                                        </div>   
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group input-block">
                                                                            <label>Account No</label>
                                                                            <input type="text" class="form-control" id="acc_no" name="acc_no" placeholder="Account No"  autocomplete="off">
                                                                        </div>   
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group input-block">
                                                                            <label>IFSC Code</label>
                                                                            <input type="text" class="form-control" id="ifsc" name="ifsc" placeholder="IFSC Code"  autocomplete="off">
                                                                        </div>   
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group input-block">
                                                                            <label>Bank Address</label>
                                                                            <input type="text" class="form-control" id="bank_address" name="bank_address" placeholder="Bank Address"  autocomplete="off">
                                                                        </div>   
                                                                    </div>
                                                                </div>
                                                                <!-- bank details section -->
        



                                                                <div class="col-md-6">
                                                                    <div class="form-group">                
                                                                        <label>Tracking</label><br>                    
                                                                        <input name="tracking_status" id="tracking_status" type="radio" value="1" /> Yes
                                                                        <input name="tracking_status" id="tracking_status" type="radio" checked value="2" /> No
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">       
                                                                        <label>Bill Wise Details</label><br>                
                                                                        <input name="bill_details_status" id="bill_details_status" type="radio" value="1" /> Yes
                                                                        <input name="bill_details_status" id="bill_details_status" type="radio" checked value="2" /> No
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <?php
                                                                        if ($ledger_code_status != '1') {
                                                                            echo '<label>Ledger Code</label>';
                                                                        }
                                                                        ?>

                                                                        <input type="<?php
                                                                        if ($ledger_code_status == '1') {
                                                                            echo 'hidden';
                                                                        } else {
                                                                            echo 'text';
                                                                        }
                                                                        ?>"  name="ledger_code" id="ledger_code"  class="form-control" placeholder="Ledger Code" />
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Opening Balance</label>
                                                                        <div class="row">
                                                                            <div class="col-xs-3 padding-r-0 input-block">

                                                                                <select class="select2 form-control" name="account">
                                                                                    <option value="Dr">Dr</option>
                                                                                    <option value="Cr">Cr</option>
                                                                                </select>

                                                                                <span class="errorMessage"></span>
                                                                            </div>
                                                                            <div class="col-xs-9  padding-l-0 input-block">
                                                                                <input type="text" name="opening_balance" id="opening_balance" autocomplete="off" class="form-control" value="<?php echo (isset($ledger->opening_balance)) ? $ledger->opening_balance : '' ?>" placeholder="Balance">
                                                                                <span class="errorMessage"></span>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>


                                                                <div class="credit_limit_div" style="display: none;">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Credit Date</label> 
                                                                            <input type="number" class="form-control" id="credit_date" name="credit_date" placeholder="credit date" value="<?php echo isset($ledger->credit_date) ? $ledger->credit_date : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Credit Limit</label> 
                                                                            <input type="number" class="form-control" id="credit_limit" name="credit_limit" placeholder="credit limit" value="<?php echo isset($ledger->credit_limit) ? $ledger->credit_limit : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix">
                                                                    <div id="contact_section">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group input-block">
                                                                            <label>Select Contact</label> 
                                                                           
                                                                            <select class="form-control" name="contact_id" id="contact_id">
                                                                               
                                                                            </select>
                                                                            <span class="errorMessage"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <label>&nbsp;</label> 
                                                                        <a href="<?php echo site_url('admin/add-customer-details'); ?>" target="_blank" class="btn btn-primary" data-toggle="tooltip" title="Add Contacts">Add New Contacts</a></div>
                                                                    <div class="col-md-5"></div>
                                                                    </div>
                                                                </div>

                                                            </div> 
                                                        </div>    

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn ladda-button add-ledger-btn btn-primary" data-color="blue" data-style="expand-right" data-size="s">Save</button>

                                                        </div>   

                                                    </form>                        

                                                </div>



                                            </div>

                                        </div>



                                        <!-- Multiple Shipping Modal -->
                                        <div id="multipleShipModal" class="modal fade" role="dialog">
                                          <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Shipping Options</h4>
                                              </div>
                                              <div class="modal-body">
                                                    <div class="form-group"><input type="text" class="form-control" id="multiple_ship_contact" placeholder="Select Contact"/></div>
                                                    <div class="list-group">
                                                    </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>

                                          </div>
                                        </div>



                                        <script>
                                            $(document).ready(function() {
                                                $("#ladger_name").on("keyup", function(e) {
                                                    if ($(this).val() != "" && $("#group_id").val() == 10) {
                                                        // bank details modal will pop-up if banck-account is selected as group and ledger name is not null
                                                        // $("#bankModal").modal("show");
                                                        $("#bank_details_section").show();

                                                    }else if ($("#group_id").val() != 10 || $(this).val() == ""){
                                                       $("#bank_details_section").hide(); 
                                                       $("#account").focus();
                                                    }
                                                });

                                                $("#contact_section").hide();
                                                $("#group_id").on("change", function() {
                                                    if($("#group_id").val() == 10) {
                                                        $("#bank_details_section").show();
                                                    }else{
                                                        $("#bank_details_section").hide(); 
                                                    }

                                                    if($("#group_id").val() == 15 || $("#group_id").val() == 23) {
                                                        $("#contact_section").show();
                                                        $('#contact_id').select2({
                                                           placeholder: "Select contacts",
                                                          ajax: {
                                                            url: "<?php echo base_url(); ?>"+'transaction_inventory/inventory/getAvailableContact',
                                                            dataType: 'json',
                                                            processResults: function (data) {
                                                              // Tranforms the top-level key of the response object from 'items' to 'results'
                                                              return {
                                                                results: data
                                                              };
                                                            }
                                                          }
                                                        });
                                                    }else{
                                                        $("#contact_section").hide();
                                                    }

                                                });


                                                $("input[name='entry_number']").on("focusout", function(){
                                                    var action = $("#action").val();
                                                    var entry_type_id = $("#entry_type_id").val();
                                                    var entry_number = $(this).val();
                                                    if(entry_number && action == "a") {
                                                        $.ajax({
                                                            url: "<?php echo base_url(); ?>admin/checkDuplicateEntryno",
                                                            type: "POST", 
                                                            data: {
                                                                entry_number: entry_number,
                                                                entry_type_id: entry_type_id
                                                            },
                                                            success: function(response) {
                                                                $("#duplicate_entryno_msg").html(response);
                                                            }
                                                        });
                                                    }
                                                });


                                                $("input[name='voucher_no']").on("focusout", function(){
                                                    var action = $("#action").val();
                                                    var entry_id = $("#entry_id").val();
                                                    var entry_type_id = $("#entry_type_id").val();
                                                    var voucher_no = $(this).val();
                                                    if(voucher_no) {
                                                        $.ajax({
                                                            url: "<?php echo base_url(); ?>admin/checkDuplicateVoucherno",
                                                            type: "POST", 
                                                            data: {
                                                                action: action,
                                                                entry_id: entry_id,
                                                                voucher_no: voucher_no,
                                                                entry_type_id: entry_type_id
                                                            },
                                                            success: function(response) {
                                                                $("#voucher_no_msg").html(response);
                                                            }
                                                        });
                                                    }
                                                });

                                                // if entry date is manual and previously a transaction is done in current session
                                                // then last entry date will automatically is added as entry date field
                                                <?php if ($auto_date != '1' && $action != 'e'): ?>
                                                    $("#tr_date").val(sessionStorage.getItem('entry_date'));
                                                <?php endif ?>
                                                
                                                
                                            });
                                            
                                           
                                        </script>