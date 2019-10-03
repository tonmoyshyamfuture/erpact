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
    .godownBatchModal input{
        height: 31px;
        padding-left: 5px;
    }
    .godownBatchModalSales input{
        height: 31px;
        padding-left: 5px;
    }
    
    .godown_batch{
        padding-right: 3px !important;
        padding-left: 3px !important;
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
            <?php elseif ($transaction_type_id == 7 || $transaction_type_id == 8 || $parent_id == 7 || $parent_id == 8): ?>
                <?php if ($action == 'e'): ?>
                    <form method="POST" action="<?php echo base_url(); ?>transaction_inventory/inventory/ajax_update_order_data" class="formSubmitAll">
                    <?php elseif ($action == 't'): ?>
                        <form method="POST" action="<?php echo base_url(); ?>transaction/accounts_inventory/ajax_order_add" class="formSubmitAll">
                        <?php else: ?>
                            <form method="POST" action="<?php echo base_url(); ?>transaction_inventory/inventory/ajax_add_order_data" class="formSubmitAll">
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
                                                             <h1><i class="fa fa-list"></i> <?php echo $voucher;?></h1>
                                                            
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <div class="pull-right">                                                                
                                                                <button type="button" class="myButton btn btn-settings btn-svg"><i data-feather="settings"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>
                                                <section>
                                                    <?php
                                                    $prev_breadcrumbs = $this->session->userdata('_breadcrumbs');
                                                    if ($action == 'e') {
                                                        $_action = 'Update';
                                                        $current_breadcrumbs = array($_action => '/transaction/update');
                                                    } elseif ($action == 'a') {
                                                        $_action = 'Add';
                                                        $current_breadcrumbs = array($_action => '/transaction/add');
                                                    } else {
                                                        if ($transaction_type_id == 5) {
                                                            $_action = 'Sales';
                                                            $current_breadcrumbs = array($_action => '/transaction/sales-order-sales');
                                                        } else {
                                                            $_action = 'Purchase';
                                                            $current_breadcrumbs = array($_action => '/transaction/purchase-order-purchase');
                                                        }
                                                    }
                                                    $breadcrumb = isset($_GET['breadcrumbs']) ? $_GET['breadcrumbs'] : "";

                                                    if ($breadcrumb != 'false') {
                                                        $breadcrumbs = array_merge($prev_breadcrumbs, $current_breadcrumbs);
                                                        $this->session->set_userdata('_breadcrumbs', $breadcrumbs);
                                                        foreach ($breadcrumbs as $k => $b) {
                                                            $this->breadcrumbs->push($k, $b);
                                                            if ($k == $_action) {
                                                                break;
                                                            }
                                                        }
                                                    }else{
                                                        $this->breadcrumbs->push('Home', '/admin/dashboard');
                                                        $this->breadcrumbs->push("Add $voucher", '/');
                                                    }
                                                    $this->breadcrumbs->show();
                                                    ?>
                                                </section>
                                                <!-- Main content -->
                                                <input type="hidden" name="igst_status" id="igst_status" value="<?php echo isset($order->is_igst_included) ? $order->is_igst_included : '' ?>">
                                                <input type="hidden" name="godown_status" id="godown_status" value="<?php echo isset($godown_status) ? $godown_status : '' ?>">
                                                <input type="hidden" name="batch_status" id="batch_status" value="<?php echo isset($batch_status) ? $batch_status : '' ?>">
                                                <section class="content"> 
                                                    <div class="box">            
                                                        <div class="box-body">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group invice_number">
                                                                        <label>Invoice Number</label><span id="duplicate_entryno_msg" style="color: red;"></span>
                                                                        <input type="hidden" name="tr_branch_id" class="tr_branch_id" value="">
                                                                        <input type="hidden" name="companyTypeStatus" class="company-type-status" value="">
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
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Date </label>
                                                                        <!-- <input class="form-control" type="text" id="tr_date" placeholder="DD/MM/YYYY" <?php echo ($action == 'e') ? 'readonly' : (($auto_date == 1) ? 'readonly' : '') ?> value="<?php echo ($action == 'e') ? date('d/m/Y', strtotime($entry->create_date)) : (($auto_date == "1") ? date('d/m/Y') : '') ?>" name="date_form"/> -->
                                                                        <input class="form-control" type="text" id="tr_date" maxlength="10" placeholder="DD/MM/YYYY" value="<?php if($action == 'e') { echo date('d/m/Y', strtotime($entry->create_date)); } else { echo date('d/m/Y'); } ?>" name="date_form" <?php if($auto_date == 1) { echo "readonly"; } ?>/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label  >
                                                                            <?php
                                                                            if ($transaction_type_id == 5 || $transaction_type_id == 7 || $transaction_type_id == 10 || $parent_id == 5 || $parent_id == 7 || $parent_id == 10):
                                                                                echo 'Debtors';
                                                                            elseif ($transaction_type_id == 6 || $transaction_type_id == 8 || $transaction_type_id == 9 || ($parent_id == 6 && $transaction_type_id != 15) || $parent_id == 8 || $parent_id == 9):
                                                                                echo 'Creditor';
                                                                            endif;
                                                                            ?> 
                                                                            <span class="registerType" ></span>
                                                                        </label>
                                                                        <input type="text" class="form-control entry_debtors" name="tr_ledger[]" value="<?php echo isset($entry_details[0]->ladger_name) ? $entry_details[0]->ladger_name : '' ?>" autocomplete="off" placeholder="Click to select" />
                                                                        <input type="hidden" id="debtors_creditors_id" class="tr_ledger_id_debtors" name="tr_ledger_id[]" value="<?php echo isset($entry_details[0]->ladger_id) ? $entry_details[0]->ladger_id : '' ?>"/>
                                                                        <input type="hidden" class="in_ledger_state" name="in_ledger_state" value="<?php echo isset($ledger['state']) ? $ledger['state'] : '' ?>"/>
                                                                        <input type="hidden" class="in_ledger_country" name="in_ledger_country" value="<?php echo isset($ledger['country']) ? $ledger['country'] : '' ?>"/>
                                                                        <input type="hidden" class="tr_type_debtors" name="tr_type[]" value="<?php echo isset($entry_details[0]->account) ? $entry_details[0]->account : '' ?>"/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>
                                                                            <?php
                                                                            if ($transaction_type_id == 5 || $transaction_type_id == 7 || $transaction_type_id == 10 || $parent_id == 5 || $parent_id == 7 || $parent_id == 10):
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
                                                                if ($action === 't' && ($entry_type == 7 || $entry_type == 8 || $parent_id == 7 || $parent_id == 8)) {
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
                                                                    <input type="hidden" class="shipping_id" name="shipping_id" value=""/>
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
                                                                                <th class="text-center">Unit</th>
                                                                                <th class="width-50 text-center">Qty.</th>
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
                                                                                    <tr product-batch-status="<?php echo isset($row->having_batch) ? $row->having_batch : '' ?>" class="each-product">
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
                                                                                                    if ($tran_type == 5 || $tran_type == 7 || $tran_type == 10 || $tran_type == 12 || $parent_id == 5 || $parent_id == 7 || $parent_id == 10 || $parent_id == 12) {
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
                                                            <button type="submit" class="btn ladda-button group-add-btn" data-color="blue" data-style="expand-right" data-size="s">Save</button>
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
                                                                        <!-- <a href="<?php echo site_url('admin/add-customer-details'); ?>" target="_blank" class="btn btn-primary" data-toggle="tooltip" title="Add Contacts">Add New Contacts</a></div> -->
                                                                        <a href="javascript:void(0);" id="addContactBtn" class="btn btn-primary" data-toggle="tooltip" title="Add Contacts">Add New Contacts</a></div>
                                                                    <div class="col-md-5"></div>
                                                                    </div>
                                                                </div>

                                                            </div> 
                                                        </div>    

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn ladda-button add-ledger-btn" data-color="blue" data-style="expand-right" data-size="s">Save</button>

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
                   
                   
       <!-- Godown Batch Modal -->
        <div id="godownBatchModal" class="modal fade godownBatchModal" role="dialog">
            <div class="modal-dialog" style="width:80% ;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo $godown_status ? 'Godown & Batch':'Batch' ;?></h4>
                        <input type="hidden" name="tr_godown_product_id_hidden" value="" id="tr_godown_product_id_hidden">
                    </div>
                    <div class="modal-body">
                       
                            <div class="row">
                                <?php if($godown_status){ ?>
                                    <div class="col-md-2" class="godown_header">
                                        <label>Godown</label>
                                    </div>
                                <?php }?>
                                
                                <div class="<?php echo $godown_status ? 'col-md-1':'col-md-3' ;?>">
                                    <label>Batch No</label>
                                </div>
                                <div class="col-md-2">
                                    <label>Manufacturing Date</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Exp.Type</label>
                                </div>
                                <div class="col-md-2">
                                    <label>Expiry Date</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Qty.</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Rate</label>
                                </div>
                                <div class="col-md-2">
                                    <label>Value</label>
                                </div>
                            </div>
                            <div class="godownbatchRow">
                                <div class="row each-batch form-group" >
                                    <?php if($godown_status){ ?>
                                    <div class="col-md-2 godown_value godown_batch">
                                        <input type="hidden" class="form-control batch_godown_id" name="batch_godown_id[]" data-id="0" value="">
                                        <input type="text" class="form-control batch_godown_name" name="batch_godown_name[]" data-id="0" placeholder="Godown" value="" autofocus>
                                    </div>
                                    <?php }else{ ?>
                                        <input type="hidden" class="form-control batch_godown_id" name="batch_godown_id[]" data-id="0" value="1">
                                        <input type="hidden" class="form-control batch_godown_name" name="batch_godown_name[]" data-id="0" placeholder="Godown" value="" autofocus>
                                    <?php }?>
                                    <div class="<?php echo $godown_status ? 'col-md-1':'col-md-3' ;?> godown_batch">
                                        <input type="text" name="batch_no[]" value="" class="form-control batch_no" data-id="0" placeholder="Batch No">
                                    </div>
                                    <div class="col-md-2 godown_batch">
                                        <input type="text" name="manufact_date[]" value="" class="form-control manufact_date act-date-format" data-id="0" placeholder="Manufacturing Date">                            
                                    </div>
                                    <div class="col-md-1 godown_batch">
                                        <input type="hidden" name="exp_type_id[]" id="" class="exp_type_id" data-id="0">
                                        <input type="text" name="exp_type[]" id="" data-id="0" class="form-control exp_type" placeholder="Expiry Type">
                                    </div>
                                    <div class="col-md-2 godown_batch">
                                        <input type="text" name="exp_days[]" value="" class="form-control exp_days" data-id="0" placeholder="Expiry Days">                            
                                    </div>
                                    <div class="col-md-1 godown_batch">
                                        <input type="text" name="batch_qty[]" value="0" class="form-control batch_qty" data-id="0" placeholder="Quantity">
                                    </div>
                                    <div class="col-md-1 godown_batch">
                                        <input type="text" name="batch_rate[]" value="0" class="form-control batch_rate" data-id="0" placeholder="Rate">                            
                                    </div>
                                    <div class="col-md-2 godown_batch">
                                        <input type="text" name="batch_value[]" value="0" class="form-control batch_value" data-id="0" placeholder="Value">                            
                                    </div>
                                </div>
                            </div>    
                    </div>
<!--                    <div class="modal-footer">
                        <button type="button" id="courierForInvoiceSave" class="btn btn-primary" data-dismiss="modal" >Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>-->
                </div>
            </div>
        </div>

        <!--Add Godown Modal-->
        <div id="godownModal" class="modal fade" role="dialog">
            <div class="modal-dialog" style="width: 40%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Godown</h4>
                        <input type="hidden" name="tr_godown_product_id_hidden" value="" id="tr_godown_product_id_hidden">
                    </div>
                    <div class="modal-body">
                        <div id="godownRow" class="godownRow" godown-id="1">
                            <div class="row tr_godown_row form-group"  >
                                    <div class="col-md-6">
                                        <input type="hidden" class="form-control batch_godown_id" name="batch_godown_id[]" data-id="0" value="">
                                        <input type="text" class="form-control batch_godown_name" name="batch_godown_name[]" data-id="0" placeholder="Godown" value="">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="godown_qty[]" value="0" class="form-control godown_qty" data-id="0" placeholder="Quantity">
                                    </div>
                            </div>
                        </div>
                    </div>
<!--                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>-->
                </div>
            </div>
        </div>
        
        <!-- Godown Batch Modal For sales -->
        <div id="godownBatchModalSales" class="modal fade godownBatchModalSales" role="dialog">
            <div class="modal-dialog" style="width:60% ;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo $godown_status ? 'Godown & Batch':'Batch' ;?></h4>
                        <input type="hidden" name="tr_godown_product_id_hidden_sales" value="" id="tr_godown_product_id_hidden_sales">
                    </div>
                    <div class="modal-body">
                       
                            <div class="row">
                                <?php if($godown_status){ ?>
                                    <div class="col-md-3" class="godown_header">
                                        <label>Godown</label>
                                    </div>
                                <?php }?>
                                
                                <div class="<?php echo $godown_status ? 'col-md-2':'col-md-3' ;?>">
                                    <label>Batch No</label>
                                </div>
                                
                                <div class="col-md-2">
                                    <label>Qty.</label>
                                </div>
                                <div class="col-md-2">
                                    <label>Rate</label>
                                </div>
                                <div class="col-md-2">
                                    <label>Value</label>
                                </div>
                            </div>
                            <div class="godownbatchRow">
                                <div class="row each-batch form-group" >
                                    <?php if($godown_status){ ?>
                                    <div class="col-md-3 godown_value godown_batch">
                                        <input type="hidden" class="form-control batch_godown_id_sales" name="batch_godown_id_sales[]" data-id="0" value="">
                                        <input type="text" class="form-control batch_godown_name_sales" name="batch_godown_name_sales[]" data-id="0" placeholder="Godown" value="" autofocus>
                                    </div>
                                    <?php }else{ ?>
                                        <input type="hidden" class="form-control batch_godown_id_sales" name="batch_godown_id_sales[]" data-id="0" value="1">
                                        <input type="hidden" class="form-control batch_godown_name_sales" name="batch_godown_name_sales[]" data-id="0" placeholder="Godown" value="" autofocus>
                                    <?php }?>
                                    <div class="<?php echo $godown_status ? 'col-md-2':'col-md-3' ;?> godown_batch">
                                        <input type="text" name="batch_no_sales[]" value="" class="form-control batch_no_sales" data-id="0" placeholder="Batch No">
                                        <input type="hidden" name="batch_id_sales[]" value="" class="form-control batch_id_sales" data-id="0" >
                                        <input type="hidden" name="manufact_date_sales[]" value="" class="form-control manufact_date_sales " data-id="0" > 
                                        <input type="hidden" name="exp_type_id_sales[]" id="" data-id="0" class="form-control exp_type_id_sales" >
                                        <input type="hidden" name="exp_type_sales[]" id="" data-id="0" class="form-control exp_type_sales" >
                                        <input type="hidden" name="exp_days_sales[]" value="" class="form-control exp_days_sales" data-id="0" >
                                    </div>
                                    <div class="<?php echo $godown_status ? 'col-md-2':'col-md-3' ;?> godown_batch">
                                        <input type="text" name="batch_qty_sales[]" value="0" class="form-control batch_qty_sales" data-id="0" placeholder="Quantity">
                                    </div>
                                    <div class="<?php echo $godown_status ? 'col-md-2':'col-md-3' ;?> godown_batch">
                                        <input type="text" name="batch_rate_sales[]" value="0" class="form-control batch_rate_sales" data-id="0" placeholder="Rate">                            
                                    </div>
                                    <div class="col-md-3 godown_batch">
                                        <input type="text" name="batch_value_sales[]" value="0" class="form-control batch_value_sales" data-id="0" placeholder="Value">                            
                                    </div>
                                </div>
                            </div>    
                    </div>
<!--                    <div class="modal-footer">
                        <button type="button" id="courierForInvoiceSave" class="btn btn-primary" data-dismiss="modal" >Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>-->
                </div>
            </div>
        </div>
        
        <!--Add Godown Modal For Sales-->
        <div id="godownModalSales" class="modal fade" role="dialog">
            <div class="modal-dialog" style="width: 40%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Godown</h4>
                        <input type="hidden" name="tr_godown_product_id_hidden_sales" value="" id="tr_godown_product_id_hidden_sales">
                    </div>
                    <div class="modal-body">
                        <div id="godownRow" class="godownRow" godown-id="1">
                            <div class="row tr_godown_row form-group"  >
                                    <div class="col-md-6">
                                        <input type="hidden" class="form-control batch_godown_id_sales" name="batch_godown_id_sales[]" data-id="0" value="">
                                        <input type="text" class="form-control batch_godown_name_sales" name="batch_godown_name_sales[]" data-id="0" placeholder="Godown" value="">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="godown_qty_sales[]" value="0" class="form-control godown_qty_sales" data-id="0" placeholder="Quantity">
                                    </div>
                            </div>
                        </div>
                        
                        
                    </div>
<!--                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>-->
                </div>
            </div>
        </div>
        

    <script>
        $(document).ready(function() {
            localStorage.clear();
            sessionStorage.clear();
            var LS_godown = [];
            var LS_batch = [];
            var product_stock_id = 0;
            
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
                
                
                
                //GODOWN STATR==================pradip=========
                
                    //Set Batch IN For Edit Part
                    var setBatchDataToLocalStorageForEdit = (product_stock_id)=>{
                        <?php if(isset($batch_details) && count($batch_details) > 0): ?>
                        LS_batch.length = 0;
                        <?php foreach ($batch_details as $batch){ ?>
                                    var product_id = <?php echo $batch->product_id;?>;
                                    if(product_stock_id == product_id){
                                        var exp_type_val = '';
                                        var expery_type_id = <?php echo $batch->exp_type ;?>;
                                        if(expery_type_id == 1){
                                            exp_type_val = 'Date'
                                        }else if(expery_type_id == 2){
                                            exp_type_val = 'Month'
                                        }else{
                                            exp_type_val = 'Days'
                                        }
                                        var newItem = {
                                            'batch_godown_id': "<?php echo $batch->godown_id;?>",
                                            'batch_godown_name': "<?php echo $batch->name;?>",
                                            'batch_no': "<?php echo $batch->batch_no;?>",
                                            'manufact_date': "<?php echo $batch->manufact_date;?>",
                                            'exp_type_id': "<?php echo $batch->exp_type;?>",
                                            'exp_type': exp_type_val,
                                            'exp_days': <?php echo $batch->exp_days_given;?>,
                                            'batch_qty': <?php echo $batch->quantity;?>,
                                            'batch_rate': <?php echo $batch->rate;?>,
                                            'batch_value': <?php echo $batch->value;?>,
                                            'product_stock_id':<?php echo $batch->product_id;?>,
                                            'productBatchStatus': 1
                                        };
                                        LS_batch.push(newItem);
                                    }
                                    
                        <?php } ?>
                        localStorage.setItem('batchGodownStockId_' + product_stock_id, JSON.stringify(LS_batch));
                        <?php endif ?>
                    }

                    //Set Godown IN For Edit Part
                    var setGodownDataToLocalStorageForEdit = (product_stock_id)=>{
                        <?php if(isset($godown_details) && count($godown_details) > 0): ?>
                             LS_godown.length = 0;
                        <?php foreach ($godown_details as $godown){ ?>
                                var product_id = <?php echo $godown->product_id;?>;
                                if(product_stock_id == product_id){
                                    var newItem = {
                                        'batch_godown_id': <?php echo $godown->godown_id;?>,
                                        'batch_godown_name': "<?php echo $godown->godown_name;?>",
                                        'godown_qty': <?php echo $godown->quantity_in;?>,
                                        'product_stock_id':<?php echo $godown->product_id;?>,
                                        'productBatchStatus': 0
                                    };
                                    LS_godown.push(newItem);
                                }
                        <?php } ?>
                            localStorage.setItem('batchGodownStockId_' + product_stock_id, JSON.stringify(LS_godown));
                        <?php endif ?>
                    }
                
                    //Set Batch OUT For Edit Part
                    var setBatchOutDataToLocalStorageForEdit = (product_stock_id)=>{
                        <?php if(isset($batch_details) && count($batch_details) > 0): ?>
                        LS_batch.length = 0;
                        <?php foreach ($batch_details as $batch){ ?>
                                    var product_id = <?php echo $batch->product_id;?>;
                                    if(product_stock_id == product_id){
                                        var exp_type_val = '';
                                        var expery_type_id = <?php echo $batch->exp_type ;?>;
                                        if(expery_type_id == 1){
                                            exp_type_val = 'Date'
                                        }else if(expery_type_id == 2){
                                            exp_type_val = 'Month'
                                        }else{
                                            exp_type_val = 'Days'
                                        }
                                        var newItem = {
                                            'batch_godown_id': "<?php echo $batch->godown_id;?>",
                                            'batch_godown_name': "<?php echo $batch->name;?>",
                                            'batch_no': "<?php echo $batch->batch_no;?>",
                                            'batch_id': "<?php echo $batch->id;?>",
                                            'manufact_date': "<?php echo $batch->manufact_date;?>",
                                            'exp_type_id': "<?php echo $batch->exp_type;?>",
                                            'exp_type': exp_type_val,
                                            'exp_days': <?php echo $batch->exp_days_given;?>,
                                            'batch_qty': <?php echo $batch->quantity;?>,
                                            'batch_rate': <?php echo $batch->rate;?>,
                                            'batch_value': <?php echo $batch->value;?>,
                                            'product_stock_id':<?php echo $batch->product_id;?>,
                                            'productBatchStatus': 1
                                        };
                                        LS_batch.push(newItem);
                                    }
                                    
                        <?php } ?>
                        localStorage.setItem('batchGodownStockId_' + product_stock_id, JSON.stringify(LS_batch));
                        <?php endif ?>
                    }

                    //Set Godown OUT For Edit Part
                    var setGodownOutDataToLocalStorageForEdit = (product_stock_id)=>{
                        <?php if(isset($godown_details) && count($godown_details) > 0): ?>
                             LS_godown.length = 0;
                        <?php foreach ($godown_details as $godown){ ?>
                                var product_id = <?php echo $godown->product_id;?>;
                                if(product_stock_id == product_id){
                                    var newItem = {
                                        'batch_godown_id': <?php echo $godown->godown_id;?>,
                                        'batch_godown_name': "<?php echo $godown->godown_name;?>",
                                        'godown_qty': <?php echo $godown->quantity_out;?>,
                                        'product_stock_id':<?php echo $godown->product_id;?>,
                                        'productBatchStatus': 0
                                    };
                                    LS_godown.push(newItem);
                                }
                        <?php } ?>
                            localStorage.setItem('batchGodownStockId_' + product_stock_id, JSON.stringify(LS_godown));
                        <?php endif ?>
                    }
                    
                //First time data puse in localstorage
                <?php if (isset($order_details) && count($order_details) > 0): 
                        foreach ($order_details as $value):
                            if($value->flow_type == 55):
                                if($value->having_batch == 1):
                                ?>
                                    //For Batch
                                    setBatchDataToLocalStorageForEdit(<?php echo $value->stock_id;?>)
                                <?php else: ?>
                                    //For Godown
                                    setGodownDataToLocalStorageForEdit(<?php echo $value->stock_id;?>)
                            <?php endif; ?>
                    
                    <?php else:
                            if($value->having_batch == 1):
                            ?>
                                setBatchOutDataToLocalStorageForEdit(<?php echo $value->stock_id;?>)
                        <?php else: ?>
                        //For Godown
                                setGodownOutDataToLocalStorageForEdit(<?php echo $value->stock_id;?>)
                        <?php endif; ?>
                <?php
                        endif;
                    endforeach;
                endif;
                ?>
                
                
                
                <?php if((isset($transaction_type_id) && $transaction_type_id == 6) || (isset($parent_id) && $parent_id == 6) || 
                        (isset($transaction_type_id) && $transaction_type_id == 9) || (isset($parent_id) && $parent_id == 9) || 
                        (isset($transaction_type_id) && $transaction_type_id == 14) || (isset($parent_id) && $parent_id == 14)) : ?>
                     
                     
                    const preference_godown = <?php if(isset($godown_status)){echo $godown_status;} ?>;
                    const batch_status = <?php if(isset($batch_status)){echo $batch_status;} ?>;
                     
                    //For Edit Section batch
                    <?php //  if (isset($order_details) && count($order_details) > 0): ?>
//                                if(batch_status){
//                                    setBatchDataToLocalStorageForEdit()
//                                }else{
//                                    setGodownDataToLocalStorageForEdit()
//                                }
                    <?php // endif ?>
                        
                    
                    
                var quenty_self = '';
                $('.product-listing-table').delegate('.product-quantity', 'focusout', function() {
//                    const preference_godown = <?php // if(isset($godown_status)){echo $godown_status;} ?>;
//                    const batch_status = <?php // if(isset($batch_status)){echo $batch_status;} ?>;
                    quenty_self = this;
                     product_stock_id = $(this).closest('.each-product').find('.stock_id_hidden').val();
                     let productBatchStatus = $(this).closest('.each-product').attr('product-batch-status');
                    //batch and godown
                    if(batch_status && productBatchStatus == 1){
                         $('#godownBatchModal').modal('show');
                         $('#godownBatchModal #tr_godown_product_id_hidden').val(product_stock_id);
                         var batchProduct = localStorage.getItem('batchGodownStockId_' + product_stock_id);
                         if(batchProduct == null){
                                //For Edit Section batch
                                <?php // if (isset($order_details) && count($order_details) > 0): ?>
//                                        if(batch_status){
//                                            setBatchDataToLocalStorageForEdit(product_stock_id)
//                                        }
                                <?php // endif ?>
                                 $('#godownBatchModal').closest('.each-batch').find('.batch_godown_name').focus();
                         }
                         
                         var batchProduct = localStorage.getItem('batchGodownStockId_' + product_stock_id);
                         if(batchProduct != null){
                             $('#godownBatchModal .godownbatchRow').each(function() {
                                    $(this).find('.row').remove();
                              });
                            LS_batch.length = 0;
                            let LS_found = JSON.parse(batchProduct);
                            for (var i = 0; i < LS_found.length; i++) {
                                 let element = `<div class="row each-batch form-group" >
                                                    ${ (preference_godown)? 
                                                        `<div class="col-md-2 godown_value godown_batch">
                                                            <input type="hidden" class="form-control batch_godown_id" name="batch_godown_id[]" data-id="${i}" value="${LS_found[i].batch_godown_id}">
                                                            <input type="text" class="form-control batch_godown_name" name="batch_godown_name[]" data-id="${i}" placeholder="Godown" value="${LS_found[i].batch_godown_name}">
                                                        </div>
                                                        <div class="col-md-1 godown_batch">`
                                                    : 
                                                            `<input type="hidden" class="form-control batch_godown_id" name="batch_godown_id[]" data-id="${i}" value="${LS_found[i].batch_godown_id}">
                                                            <input type="hidden" class="form-control batch_godown_name" name="batch_godown_name[]" data-id="${i}" placeholder="Godown" value="${LS_found[i].batch_godown_name}">
                                                        <div class="col-md-3 godown_batch">`
                                                    }
                                                        <input type="text" name="batch_no[]" value="${LS_found[i].batch_no}" class="form-control batch_no" data-id="${i}" placeholder="Batch No">
                                                    </div>
                                                    <div class="col-md-2 godown_batch">
                                                        <input type="text" name="manufact_date[]" value="${LS_found[i].manufact_date}" class="form-control manufact_date" data-id="${i}" placeholder="Manufacturing Date">                            
                                                    </div>
                                                    <div class="col-md-1 godown_batch">
                                                        <input type="hidden" name="exp_type_id[]" value="${LS_found[i].exp_type_id}" class="exp_type_id" data-id="${i}">
                                                        <input type="text" name="exp_type[]" value="${LS_found[i].exp_type}" data-id="${i}" class="form-control exp_type" placeholder="Expiry Type">
                                                    </div>
                                                    <div class="col-md-2 godown_batch">
                                                        <input type="text" name="exp_days[]" value="${LS_found[i].exp_days}" class="form-control exp_days" data-id="${i}" placeholder="Expiry Days">                            
                                                    </div>
                                                    <div class="col-md-1 godown_batch">
                                                        <input type="text" name="batch_qty[]" value="${LS_found[i].batch_qty}" class="form-control batch_qty" data-id="${i}" placeholder="Quantity">
                                                    </div>
                                                    <div class="col-md-1 godown_batch">
                                                        <input type="text" name="batch_rate[]" value="${LS_found[i].batch_rate}" class="form-control batch_rate" data-id="${i}" placeholder="Rate">                            
                                                    </div>
                                                    <div class="col-md-2 godown_batch">
                                                        <input type="text" name="batch_value[]" value="${LS_found[i].batch_value}" class="form-control batch_value" data-id="${i}" placeholder="Value">                            
                                                    </div>
                                            </div>`
                                    $(".godownbatchRow").append(element);
                             }
                         }
                    }
//                    only godown
                    if((preference_godown && !batch_status) || (preference_godown && batch_status && productBatchStatus == 0)){
//                        $('#godownModal').modal('show');
                        $('#godownModal #tr_godown_product_id_hidden').val(product_stock_id);
                        var godownProduct = localStorage.getItem('batchGodownStockId_' + product_stock_id);
                        if(godownProduct == null){
                            //For Edit Section godown
                            <?php // if (isset($order_details) && count($order_details) > 0): ?>
//                                setGodownDataToLocalStorageForEdit(product_stock_id)
                            <?php // endif ?>
                        }
                        var godownProduct = localStorage.getItem('batchGodownStockId_' + product_stock_id);
                        if(godownProduct != null){
                             $('#godownModal .godownRow').each(function() {
                                $(this).find('.row').remove();
                            });
                             LS_godown.length = 0;
                             let LS_found = JSON.parse(godownProduct);
                             for (var i = 0; i < LS_found.length; i++) {
                                 let element = `<div class="row tr_godown_row form-group" id="" >
                                                    <div class="col-md-6">
                                                        <input type="hidden" class="form-control batch_godown_id" name="batch_godown_id[]" data-id="${i}" value="${LS_found[i].batch_godown_id}">
                                                        <input type="text" class="form-control batch_godown_name" name="batch_godown_name[]" data-id="${i}" placeholder="Godown" value="${LS_found[i].batch_godown_name}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" name="godown_qty[]" value="${LS_found[i].godown_qty}" class="form-control godown_qty" data-id="${i}" placeholder="Quantity">
                                                    </div>
                                            </div>`
                                    $(" #godownRow").append(element);
                             }
                        }
                        $('#godownModal').modal('show');
                    }
                    
                })
                
//               
                
                $("body").delegate('.godown_qty', 'keydown', function(e) {
                    if (e.which == 13) {
                        e.preventDefault();
                        var _self = this
                        var having_batch = 0;
                        var id = $(this).data('id');
                        var nextId = parseInt(id+1);
                        var qty = $(".godown_qty"+"[data-id="+id+"]").val();
                        var html = '<div class="row tr_godown_row form-group" id="'+(nextId+1)+'">';
                                    <?php // if ($preference->godown == 1): ?>
                                    <?php if (TRUE): ?>
                                    html += '<div class="col-md-6">'+
                                            '<input type="hidden" class="form-control batch_godown_id" name="batch_godown_id[]" data-id="'+nextId+'" value="">'+
                                            '<input type="text" class="form-control batch_godown_name" name="batch_godown_name[]" data-id="'+nextId+'" placeholder="Godown" value="">'+
                                        '</div>';
                                    <?php //endif ?>

                                    <?php //if ($preference->batch == 0 && $preference->godown == 1): ?>

                                    html += '<div class="col-md-6">' +
                                                '<input type="text" name="godown_qty[]" value="0" class="form-control godown_qty" data-id="'+nextId+'" placeholder="Quantity">' +
                                            '</div>' ;
                                    <?php endif ?>
                                    html +=  '</div>';
                        
                                    if (qty <= 0) {
                                        LS_godown.length = 0;
                                        $('#godownModal .tr_godown_row').each(function() {
                                            if($(this).find(".godown_qty").val() > 0){
                                                var newItem = {
                                                    'batch_godown_id': $(this).find(".batch_godown_id").val(),
                                                    'batch_godown_name': $(this).find(".batch_godown_name").val(),
                                                    'godown_qty': $(this).find(".godown_qty").val(),
                                                    'product_stock_id':product_stock_id,
                                                    'productBatchStatus': 0
                                                };
                                                LS_godown.push(newItem);
                                            }
                                        });
                                        localStorage.setItem('batchGodownStockId_' + product_stock_id, JSON.stringify(LS_godown));
                        
                                        $("#godownModal").modal('hide');
                                        
                                        $(quenty_self).closest('.each-product').find('.product-price').focus();

                                        $('#godownModal .godownRow').each(function() {
                                            $(this).find('.row').remove();
                                        });
                                         let html = `<div class="form-group row tr_godown_row" id="" >
                                                    <div class="col-md-6">
                                                        <input type="hidden" class="form-control batch_godown_id" name="batch_godown_id[]" data-id="0" value="">
                                                        <input type="text" class="form-control batch_godown_name" name="batch_godown_name[]" data-id="0" placeholder="Godown" value="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" name="godown_qty[]" value="0" class="form-control godown_qty" data-id="0" placeholder="Quantity">
                                                    </div>
                                            </div>`
                                    $("#godownRow").append(html);

                                    } else {
                                        if(validationGodownFn(_self)){
                                            $("#godownRow").append(html);
                                            $("#godownRow .batch_godown_name[data-id="+nextId+"]").focus();
                                        }
                                    }
                    }

                });
                
                var validationGodownFn = (self) =>{
                    let batch_godown_name = $(self).closest('.tr_godown_row').find('.batch_godown_name').val()
                                   
                    if(batch_godown_name == ''){
                        $(self).closest('.tr_godown_row').find('.batch_godown_name').focus()
                        Command: toastr["error"]("Please select godown.");
                        return false;
                    }
                    
                    return true;
                    
                }
                
                $("#godownModal").delegate('.godown_qty', 'keyup', function(e) {
                    var stock = 0;
                    $("#godownModal .godownRow .godown_qty").each(function() {
                        stock = parseFloat(stock) + parseFloat($(this).val());
                    });
                    $(quenty_self).val(stock);
                });
                
                //================Batch Or Batch with godown================
                $("body").delegate(".exp_type", "focus", function() {
                        var exp_type = $(this);
                        var eid = $(this).data('id');
                        // console.log(eid);
                        exp_type.autocomplete({
                            source:function(request, response) {
                                $.ajax({
                                    url: "<?php echo base_url(); ?>products/admin/getBatchExpiryType",
                                    type: "POST",
                                    cache: false,
                                    data: { search_value: request.term },
                                    dataType: 'JSON',
                                    success: function(res) {
                                        response(res);
                                    },
                                    error: function(res) {
                                        // console.log(res);
                                    }
                                });
                            },
                            minLength:0,
                            select: function(e, ui) {
                                e.preventDefault();
//                                $(".exp_type"+"[data-id="+eid+"]").val(ui.item.value);
//                                $(".exp_type_id"+"[data-id="+eid+"]").val(ui.item.id);
                                $(this).closest('.each-batch').find('.exp_type').val(ui.item.value)
                                $(this).closest('.each-batch').find('.exp_type_id').val(ui.item.id)
                                
                                $(this).closest('.each-batch').find('.exp_days').val("")
                                if (ui.item.id == 1) {     
                                    $(this).closest('.each-batch').find('.exp_days').addClass('act-date-format')  
                                    $(this).closest('.each-batch').find('.exp_days').removeClass('onlyNumeric') 
                                } else {  
                                    $(this).closest('.each-batch').find('.exp_days').removeClass('act-date-format')
                                    $(this).closest('.each-batch').find('.exp_days').addClass('onlyNumeric')
                                }
                            }
                        }).focus(function() {
                            exp_type.autocomplete("search", "");
                        });
                    });
                    
                    $("body").delegate('.batch_value', 'keydown', function(e) {
                        if (e.which == 13) {
                            e.preventDefault();
                            var _self = this
                            var id = $(this).data('id');
                            var nextId = parseInt(id+1);
                            var qty = $(".batch_qty"+"[data-id="+id+"]").val();
                            var batchNo = $(this).closest('.each-batch').find('.batch_no').val()
                            var html = '<div class="row each-batch form-group">';
                                        <?php if ($godown_status == 1){ ?>
                                        html += '<div class="col-md-2 godown_batch">'+
                                                '<input type="hidden" class="form-control batch_godown_id" name="batch_godown_id[]" data-id="'+nextId+'" value="">'+
                                                '<input type="text" class="form-control batch_godown_name" name="batch_godown_name[]" data-id="'+nextId+'" placeholder="Godown" value="">'+
                                            '</div>'+
                                            '<div class="col-md-1 godown_batch"> ';
                                        <?php }else{ ?>
                                             html += '<input type="hidden" class="form-control batch_godown_id" name="batch_godown_id[]" data-id="'+nextId+'" value="">'+
                                                     '<input type="hidden" class="form-control batch_godown_name" name="batch_godown_name[]" data-id="'+nextId+'" placeholder="Godown" value="">'+
                                                '<div class="col-md-3 godown_batch"> ';
                                        <?php  } ?>
                                          html +='<input type="text" name="batch_no[]" value="" class="form-control batch_no" data-id="'+nextId+'" placeholder="Batch No">' +
                                            '</div>' +
                                            '<div class="col-md-2 godown_batch">' +
                                                '<input type="text" name="manufact_date[]" value="" class="form-control manufact_date act-date-format" data-id="'+nextId+'" placeholder="Manufacturing Date">' +
                                            '</div>' +
                                            '<div class="col-md-1 godown_batch">'+
                                                '<input type="hidden" name="exp_type_id[]" id="" class="exp_type_id" data-id="'+nextId+'">'+
                                                '<input type="text" name="exp_type[]" id="" class="form-control exp_type" placeholder="Expiry Type" data-id="'+nextId+'">'+
                                            '</div>'+
                                            '<div class="col-md-2 godown_batch">' +
                                                '<input type="text" name="exp_days[]" value="" class="form-control exp_days" data-id="'+nextId+'" placeholder="Expiry Days">' +
                                            '</div>'+
                                            '<div class="col-md-1 godown_batch">' +
                                                '<input type="text" name="batch_qty[]" value="0" class="form-control batch_qty" data-id="'+nextId+'" placeholder="Quantity">' +
                                            '</div>' +
                                            '<div class="col-md-1 godown_batch">' +
                                                '<input type="text" name="batch_rate[]" value="0" class="form-control batch_rate" data-id="'+nextId+'" placeholder="Rate">' +
                                            '</div>' +

                                            '<div class="col-md-2 godown_batch">'+
                                                '<input type="text" name="batch_value[]" value="0" class="form-control batch_value" data-id="'+nextId+'" placeholder="Value">'+
                                            '</div>';
                                        html +=  '</div>';

                            if (qty <= 0) {
                                 LS_batch.length = 0;
                                    $('#godownBatchModal .each-batch').each(function() {
                                        if($(this).find(".batch_qty").val() > 0){
                                            let newItem = {
                                                'batch_godown_id': $(this).find(".batch_godown_id").val(),
                                                'batch_godown_name': $(this).find(".batch_godown_name").val(),
                                                'batch_no': $(this).find(".batch_no").val(),
                                                'manufact_date': $(this).find(".manufact_date").val(),
                                                'exp_type_id': $(this).find(".exp_type_id").val(),
                                                'exp_type': $(this).find(".exp_type").val(),
                                                'exp_days': $(this).find(".exp_days").val(),
                                                'batch_qty': $(this).find(".batch_qty").val(),
                                                'batch_rate': $(this).find(".batch_rate").val(),
                                                'batch_value': $(this).find(".batch_value").val(),
                                                'product_stock_id':product_stock_id,
                                                'productBatchStatus': 1
                                            };
                                            LS_batch.push(newItem);
                                        }
                                    });
                                localStorage.setItem('batchGodownStockId_' + product_stock_id, JSON.stringify(LS_batch));
                                    
                                $("#godownBatchModal").modal('hide');
                                $(quenty_self).closest('.each-product').find('.product-price').focus();
                                
                                $('#godownBatchModal .godownbatchRow').each(function() {
                                      $(this).find('.row').remove();
                                });
                                var godown_status = '<?php echo $godown_status ;?>'
                                let html = ''
                                html =`<div class="row each-batch form-group" >
                                    ${ (godown_status)? 
                                            `<div class="col-md-2 godown_value godown_batch">
                                                <input type="hidden" class="form-control batch_godown_id" name="batch_godown_id[]" data-id="0" value="">
                                                <input type="text" class="form-control batch_godown_name" name="batch_godown_name[]" data-id="0" placeholder="Godown" value="">
                                            </div>
                                            <div class="col-md-1 godown_batch">`
                                    :
                                            `<input type="hidden" class="form-control batch_godown_id" name="batch_godown_id[]" data-id="0" value="1">
                                             <input type="hidden" class="form-control batch_godown_name" name="batch_godown_name[]" data-id="0" placeholder="Godown" value="Local Godown">
                                        <div class="col-md-3 godown_batch">`
                                    }
                                        <input type="text" name="batch_no[]" value="" class="form-control batch_no" data-id="0" placeholder="Batch No">
                                    </div>
                                    <div class="col-md-2 godown_batch">
                                        <input type="text" name="manufact_date[]" value="" class="form-control manufact_date act-date-format" data-id="0" placeholder="Manufacturing Date">                            
                                    </div>
                                    <div class="col-md-1 godown_batch">
                                        <input type="hidden" name="exp_type_id[]" id="" class="exp_type_id" data-id="0">
                                        <input type="text" name="exp_type[]" id="" data-id="0" class="form-control exp_type" placeholder="Expiry Type">
                                    </div>
                                    <div class="col-md-2 godown_batch">
                                        <input type="text" name="exp_days[]" value="" class="form-control exp_days" data-id="0" placeholder="Expiry Days">                            
                                    </div>
                                    <div class="col-md-1 godown_batch">
                                        <input type="text" name="batch_qty[]" value="0" class="form-control batch_qty" data-id="0" placeholder="Quantity">
                                    </div>
                                    <div class="col-md-1 godown_batch">
                                        <input type="text" name="batch_rate[]" value="0" class="form-control batch_rate" data-id="0" placeholder="Rate">                            
                                    </div>
                                    <div class="col-md-2 godown_batch">
                                        <input type="text" name="batch_value[]" value="0" class="form-control batch_value" data-id="0" placeholder="Value">                            
                                    </div>
                                </div>`
                                $(".godownbatchRow").append(html);
                            } else {
                                 if(validationBatchFn(_self)){
                                    $(".godownbatchRow").append(html);
                                     <?php if ($godown_status == 1){ ?>
                                         $(".batch_godown_name[data-id="+nextId+"]").focus();
                                     <?php }else{ ?>
                                         $(".batch_no[data-id="+nextId+"]").focus();
                                     <?php }?>
                                 }
                            }
                        }

                    });
                 
                 var validationBatchFn = (self) =>{
                    let batchNo = $(self).closest('.each-batch').find('.batch_no').val()
                    let batch_godown_name = $(self).closest('.each-batch').find('.batch_godown_name').val()
                                   
                    if(batch_godown_name == ''){
                        $(self).closest('.each-batch').find('.batch_godown_name').focus()
                        Command: toastr["error"]("Please select godown.");
                        return false;
                    }
                    
                    if(batchNo == ''){
                        $(self).closest('.each-batch').find('.batch_no').focus()
                        Command: toastr["error"]("Please enter batch.");
                        return false;
                    }
                    
                    return true;
                    
                 }
                 
                 
                    
                $("#godownBatchModal").delegate('.batch_value', 'keyup', function(e) {
                    var stock = 0;
                    var value = 0;
                    $("#godownBatchModal .godownbatchRow .batch_qty").each(function() {
                        stock +=  parseFloat($(this).val());
                    });
                    $("#godownBatchModal .godownbatchRow .batch_value").each(function() {
                        value += parseFloat($(this).val());
                    });
                    $(quenty_self).val(stock);
                    $(quenty_self).closest('.each-product').find('.product-price').val(value/stock);
                });
                
                var batchValueVal = (batch_self) =>{
                    let batchQut = $(batch_self).closest('.each-batch').find('.batch_qty').val()
                    let batchRate = $(batch_self).closest('.each-batch').find('.batch_rate').val()
                    $(batch_self).closest('.each-batch').find('.batch_value').val(parseFloat(batchQut) * parseFloat(batchRate))
                }
                
                $("body").delegate('.batch_rate', 'keyup', function(e) {
                    var batch_self = this
                    batchValueVal(batch_self)
                 });
                 
                $("body").delegate('.batch_qty', 'keyup', function(e) {
                  var batch_self = this
                  batchValueVal(batch_self)
               });
               
               //                  Godown nmae autocomplete
                $("body").delegate(".batch_godown_name", "focus", function() {
                    var batch_godown_name = $(this);
                    var eid = $(this).data('id');
                    batch_godown_name.autocomplete({
                        source:function(request, response) {
                            $.ajax({
                                url: "<?php echo base_url(); ?>products/admin/getGodownList",
                                type: "POST",
                                cache: false,
                                data: { search_value: request.term },
                                dataType: 'JSON',
                                success: function(res) {
                                    response(res);
                                },
                                error: function(res) {
                                    // console.log(res);
                                }
                            });
                        },
                        minLength:0,
                        select: function(e, ui) {
                            e.preventDefault();
                            $(".batch_godown_name"+"[data-id="+eid+"]").val(ui.item.value);
                            $(".batch_godown_id"+"[data-id="+eid+"]").val(ui.item.id);
                        }
                    }).focus(function() {
                        batch_godown_name.autocomplete("search", "");
                    });
                });
               
               
               <?php endif ?>
               //========================================sudip===========================================================
               <?php if((isset($transaction_type_id) && $transaction_type_id == 5) || (isset($parent_id) && $parent_id == 5) || 
                       (isset($transaction_type_id) && $transaction_type_id == 10) || (isset($parent_id) && $parent_id == 10) || 
                       (isset($transaction_type_id) && $transaction_type_id == 12) || (isset($parent_id) && $parent_id == 12)): ?>
                 var quenty_self = '';
                $('.product-listing-table').delegate('.product-quantity', 'focusout', function() {
                    const preference_godown = <?php if(isset($godown_status)){echo $godown_status;} ?>;
                    const batch_status = <?php if(isset($batch_status)){echo $batch_status;} ?>;
                    quenty_self = this;
                     product_stock_id = $(this).closest('.each-product').find('.stock_id_hidden').val();
                     let productBatchStatus = $(this).closest('.each-product').attr('product-batch-status');
                     let productSalesPrice = $(this).closest('.each-product').find('.product-price').val();
                    //batch and godown
                    if(batch_status && productBatchStatus == 1){
                         $('#godownBatchModalSales').modal('show');
                         $('#godownBatchModalSales #tr_godown_product_id_hidden_sales').val(product_stock_id);
                         var batchProduct = localStorage.getItem('batchGodownStockId_' + product_stock_id);
                         if(batchProduct == null){
                             //For Edit Section batch
                                <?php // if (isset($order_details) && count($order_details) > 0): ?>
//                                        if(batch_status){
//                                            setBatchOutDataToLocalStorageForEdit(product_stock_id)
//                                        }
                                <?php // endif ?>
                             $('#godownBatchModalSales').closest('.each-batch').find('.batch_godown_name_sales').focus();
                         }
                         
                         batchProduct = localStorage.getItem('batchGodownStockId_' + product_stock_id);
                         if(batchProduct != null){
                             $('#godownBatchModalSales .godownbatchRow').each(function() {
                                    $(this).find('.row').remove();
                              });
                            LS_batch.length = 0;
                            let LS_found = JSON.parse(batchProduct);
                            for (var i = 0; i < LS_found.length; i++) {
                                 let element = `<div class="row each-batch form-group" >
                                                    ${ (preference_godown)? 
                                                        `<div class="col-md-3 godown_value godown_batch">
                                                            <input type="hidden" class="form-control batch_godown_id_sales" name="batch_godown_id_sales[]" data-id="${i}" value="${LS_found[i].batch_godown_id}">
                                                            <input type="text" class="form-control batch_godown_name_sales" name="batch_godown_name_sales[]" data-id="${i}" placeholder="Godown" value="${LS_found[i].batch_godown_name}">
                                                        </div>
                                                        <div class="col-md-2 godown_batch">`
                                                    : 
                                                            `<input type="hidden" class="form-control batch_godown_id_sales" name="batch_godown_id_sales[]" data-id="${i}" value="${LS_found[i].batch_godown_id}">
                                                            <input type="hidden" class="form-control batch_godown_name_sales" name="batch_godown_name_sales[]" data-id="${i}" placeholder="Godown" value="${LS_found[i].batch_godown_name}">
                                                        <div class="col-md-3 godown_batch">`
                                                    }
                                                        <input type="text" name="batch_no_sales[]" value="${LS_found[i].batch_no}" class="form-control batch_no_sales" data-id="${i}" placeholder="Batch No">
                                                        <input type="hidden" name="batch_id_sales[]" value="${LS_found[i].batch_id}" class="form-control batch_id_sales" data-id="${i}" >
                                                        <input type="hidden" name="manufact_date_sales[]" value="${LS_found[i].manufact_date}" class="form-control manufact_date_sales " data-id="${i}" > 
                                                        <input type="hidden" name="exp_type_id_sales[]" id="" value="${LS_found[i].exp_type_id}" data-id="${i}" class="form-control exp_type_id_sales" >
                                                        <input type="hidden" name="exp_type_sales[]" id="" value="${LS_found[i].exp_type}" data-id="${i}" class="form-control exp_type_sales" >
                                                        <input type="hidden" name="exp_days_sales[]" value="${LS_found[i].exp_days}" class="form-control exp_days_sales" data-id="${i}" >
                                                    </div>
                                                    <div class="${ (preference_godown)?`col-md-2`:`col-md-3`} godown_batch">
                                                        <input type="text" name="batch_qty_sales[]" value="${LS_found[i].batch_qty}" class="form-control batch_qty_sales" data-id="${i}" placeholder="Quantity">
                                                    </div>
                                                    <div class="${ (preference_godown)?`col-md-2`:`col-md-3`} godown_batch">
                                                        <input type="text" name="batch_rate_sales[]" value="${LS_found[i].batch_rate}" class="form-control batch_rate_sales" data-id="${i}" placeholder="Rate">                            
                                                    </div>
                                                    <div class="col-md-3 godown_batch">
                                                        <input type="text" name="batch_value_sales[]" value="${LS_found[i].batch_value}" class="form-control batch_value_sales" data-id="${i}" placeholder="Value">                            
                                                    </div>
                                            </div>`
                                    $("#godownBatchModalSales .godownbatchRow").append(element);
                             }
                         }
                    }
//                    only godown
                    if((preference_godown && !batch_status) || (preference_godown && batch_status && productBatchStatus == 0)){
                        $('#godownModalSales').modal('show');
                       
                        $('#godownModalSales #tr_godown_product_id_hidden_sales').val(product_stock_id);
                        var godownProduct = localStorage.getItem('batchGodownStockId_' + product_stock_id);
                        if(godownProduct == null){
                            <?php // if (isset($order_details) && count($order_details) > 0): ?>
//                                setGodownOutDataToLocalStorageForEdit(product_stock_id)
                            <?php // endif ?>
                        }
                        
                        godownProduct = localStorage.getItem('batchGodownStockId_' + product_stock_id);
                        if(godownProduct != null){
                             $('#godownModalSales .godownRow').each(function() {
                                $(this).find('.row').remove();
                            });
                             LS_godown.length = 0;
                             let LS_found = JSON.parse(godownProduct)
                             for (var i = 0; i < LS_found.length; i++) {
                                 let element = `<div class="row tr_godown_row form-group" id="" >
                                                    <div class="col-md-6">
                                                        <input type="hidden" class="form-control batch_godown_id_sales" name="batch_godown_id_sales[]" data-id="${i}" value="${LS_found[i].batch_godown_id}">
                                                        <input type="text" class="form-control batch_godown_name_sales" name="batch_godown_name_sales[]" data-id="${i}" placeholder="Godown" value="${LS_found[i].batch_godown_name}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" name="godown_qty_sales[]" value="${LS_found[i].godown_qty}" class="form-control godown_qty_sales" data-id="${i}" placeholder="Quantity">
                                                    </div>
                                            </div>`
                                    $("#godownModalSales .godownRow").append(element);
                             }
                        }
                    }
                    
                })
                
                
                
                //Godown nmae autocomplete
                $("body").delegate(".batch_no_sales", "focus", function() {
                    var batch_name = $(this);
                    var eid = $(this).data('id');
                    var godown_id = $(batch_name).closest('.each-batch').find('.batch_godown_id_sales').val()
                    batch_name.autocomplete({
                        source:function(request, response) {
                            $.ajax({
                                url: "<?php echo base_url(); ?>transaction_inventory/inventory/getBatchByGodownIdAndProductId",
                                type: "POST",
                                cache: false,
                                data: { search_value: request.term,product_stock_id,godown_id },
                                dataType: 'JSON',
                                success: function(res) {
                                    response(res);
                                },
                                error: function(res) {
                                    // console.log(res);
                                }
                            });
                        },
                        minLength:0,
                        select: function(e, ui) {
                            e.preventDefault();
                            $(".batch_no_sales"+"[data-id="+eid+"]").val(ui.item.batch_no);
                            $(".batch_id_sales"+"[data-id="+eid+"]").val(ui.item.id);
                            $(".manufact_date_sales"+"[data-id="+eid+"]").val(ui.item.manufact_date);
                            $(".exp_type_id_sales"+"[data-id="+eid+"]").val(ui.item.exp_type);
                            
                            let exp_type = ''
                            if(ui.item.exp_type == '1'){
                                exp_type = 'Date'
                            }else if(ui.item.exp_type == '2'){
                                exp_type = 'Month'
                            }else{
                                exp_type = 'Days'
                            }
                            
                            $(".exp_type_sales"+"[data-id="+eid+"]").val(exp_type);
                            $(".exp_days_sales"+"[data-id="+eid+"]").val(ui.item.exp_days_given);
                        }
                    }).focus(function() {
                        batch_name.autocomplete("search", "");
                    });
                });
                
                
                //Get Batch respect to godown and product
                 $("body").delegate(".batch_godown_name_sales", "focus", function() {
                    var batch_godown_name = $(this);
                    var eid = $(this).data('id');
                    batch_godown_name.autocomplete({
                        source:function(request, response) {
                            $.ajax({
                                url: "<?php echo base_url(); ?>transaction_inventory/inventory/getGodownListById",
                                type: "POST",
                                cache: false,
                                data: { search_value: request.term,product_stock_id },
                                dataType: 'JSON',
                                success: function(res) {
                                    response(res);
                                },
                                error: function(res) {
                                    // console.log(res);
                                }
                            });
                        },
                        minLength:0,
                        select: function(e, ui) {
                            e.preventDefault();
                            $(".batch_godown_name_sales"+"[data-id="+eid+"]").val(ui.item.godown_name);
                            $(".batch_godown_id_sales"+"[data-id="+eid+"]").val(ui.item.id);
                            $(".batch_rate_sales"+"[data-id="+eid+"]").val(sessionStorage.getItem('productSalesPrice_' + product_stock_id));
                            $(".batch_godown_name_sales"+"[data-id="+eid+"]").closest('.each-batch').find('.batch_no_sales').val('');
                        }
                    }).focus(function() {
                        batch_godown_name.autocomplete("search", "");
                    });
                });
                
                //Get Godown respect to godown and product 
//                $("body").delegate(".batch_godown_name_sales", "focus", function() {
//                   var batch_godown_name = $(this);
//                   var eid = $(this).data('id');
//                        var godown_name_sales = $(this).tautocomplete({
//                           width: "250px",
//                           columns: ['Godown', 'Stock'],
//                           placeholder: "Godown Search",
//                           theme: "white",
//                           ajax: {
//                               url: "<?php echo base_url(); ?>transaction_inventory/inventory/getGodownListById",
//                               type: "POST",
//                               data: { search_value: '',product_stock_id },
//                               dataType: 'JSON',
//                               success: function (result) {
//                                   var filterData = [];
//
//                                   var searchData = eval("/" + godown_name_sales.searchdata() + "/gi");
//
//                                   $.each(result, function (i, v) {
//                                       if (v.country.search(new RegExp(searchData)) != -1) {
//                                           filterData.push(v);
//                                       }
//                                   });
//                                   return filterData;
//                               }
//                           },
//                           onchange: function () {
////                               alert("You selected \"" + godown_name_sales.text() + "\" and its ID is \"" + godown_name_sales.id() + "\"");
//                            $(".batch_godown_name_sales"+"[data-id="+eid+"]").val(godown_name_sales.text());
//                            $(".batch_godown_id_sales"+"[data-id="+eid+"]").val(godown_name_sales.id());
//                           }
//                       });
//                 });
                 
                 
                 //Get Batch respect to godown and product
//                 $("body").delegate(".batch_no_sales", "focus", function() {
//                   var batch_name = $(this);
//                   var eid = $(this).data('id');
//                   var godown_id = $(batch_name).closest('.each-batch').find('.batch_godown_id_sales').val()
//                        var batch_sales = $(this).tautocomplete({
//                           width: "500px",
//                           columns: ['Batch No', 'Stock','Manufact Date', 'Exp Date'],
//                           placeholder: "Batch Search",
//                           theme: "classic",
//                           ajax: {
//                               url: "<?php echo base_url(); ?>transaction_inventory/inventory/getBatchByGodownIdAndProductId",
//                               type: "POST",
//                               data: { search_value: '',product_stock_id,godown_id },
//                               dataType: 'JSON',
//                               success: function (result) {
//                                   var filterData = [];
//
//                                   var searchData = eval("/" + batch_sales.searchdata() + "/gi");
//
//                                   $.each(result, function (i, v) {
//                                       if (v.country.search(new RegExp(searchData)) != -1) {
//                                           filterData.push(v);
//                                       }
//                                   });
//                                   return filterData;
//                               }
//                           },
//                           onchange: function () {
////                               alert("You selected \"" + godown_name_sales.text() + "\" and its ID is \"" + godown_name_sales.id() + "\"");
//                           console.dir(batch_sales.all())
//                           console.log(JSON.stringify(batch_sales.all()))
//                           let select_data = JSON.stringify(batch_sales.all())
//                           console.log(Object.keys(batch_sales.all()).map(i => batch_sales.all()[i]))
//                           let sss = Object.keys(batch_sales.all()).map(i => batch_sales.all()[i])
//                           console.log(sss[0])
//                            $(".batch_no_sales"+"[data-id="+eid+"]").val(batch_sales.text());
//                            $(".batch_id_sales"+"[data-id="+eid+"]").val(batch_sales.id());
//                           }
//                       });
//                 });
                 
                 //update product quenty and product price
                 $("body").delegate('.batch_value_sales', 'keyup', function(e) {
                    e.preventDefault();
                    var stock = 0;
                    var value = 0;
                    let i= 0;
                    $(".batch_qty_sales").each(function() {
                        stock = parseFloat(stock) + parseFloat($(this).val());
                    });
                    $(".batch_value_sales").each(function() {
                        value = parseFloat(value) + parseFloat($(this).val());
                    });
                    $(quenty_self).val(stock);
                    $(quenty_self).closest('.each-product').find('.product-price').val(value/stock);
                });
                 
                //value calculaton
                   var batchValueVal = (batch_self) =>{
                      let batchQut = $(batch_self).closest('.each-batch').find('.batch_qty_sales').val()
                      let batchRate = $(batch_self).closest('.each-batch').find('.batch_rate_sales').val()
                      $(batch_self).closest('.each-batch').find('.batch_value_sales').val(parseFloat(batchQut) * parseFloat(batchRate))
                  }
                   
                //get batch rate for  value calculation on Item out
                $("body").delegate('.batch_rate_sales', 'keyup', function(e) {
                    var batch_self = this
                    batchValueVal(batch_self)
                 });

                //Get batch qty. for value calculation on Item out
                $("body").delegate('.batch_qty_sales', 'keyup', function(e) {
                  var batch_self = this
                  batchValueVal(batch_self)
               });
                  
                  $("body").delegate('.batch_value_sales', 'keydown', function(e) {
                        if (e.which == 13) {
                            e.preventDefault();
                            var _self = this
                            var id = $(this).data('id');
                            var nextId = parseInt(id+1);
                            var qty = $(".batch_qty_sales"+"[data-id="+id+"]").val();
                            var batchQut = $(this).closest('.each-batch').find('.batch_qty_sales').val()
                            var html = '<div class="row each-batch form-group">';
                                        <?php if ($godown_status == 1){ ?>
                                        html += '<div class="col-md-3 godown_batch">'+
                                                '<input type="hidden" class="form-control batch_godown_id_sales" name="batch_godown_id_sales[]" data-id="'+nextId+'" value="">'+
                                                '<input type="text" class="form-control batch_godown_name_sales" name="batch_godown_name_sales[]" data-id="'+nextId+'" placeholder="Godown" value="">'+
                                            '</div>'+
                                            '<div class="col-md-2 godown_batch"> ';
                                        <?php }else{ ?>
                                             html += '<input type="hidden" class="form-control batch_godown_id_sales" name="batch_godown_id_sales[]" data-id="'+nextId+'" value="1">'+
                                                     '<input type="hidden" class="form-control batch_godown_name_sales" name="batch_godown_name_sales[]" data-id="'+nextId+'" placeholder="Godown" value="Local Godown">'+
                                                '<div class="col-md-3 godown_batch"> ';
                                        <?php  } ?>
                                          html +='<input type="text" name="batch_no_sales[]" value="" class="form-control batch_no_sales" data-id="'+nextId+'" placeholder="Batch No">' +
                                                  '<input type="hidden" name="batch_id_sales[]" value="" class="form-control batch_id_sales" data-id="'+nextId+'" >'+
                                                  '<input type="hidden" name="manufact_date_sales[]" value="" class="form-control manufact_date_sales " data-id="'+nextId+'" > '+
                                                  '<input type="hidden" name="exp_type_id_sales[]" id="" data-id="'+nextId+'" class="form-control exp_type_id_sales" >'+
                                                  '<input type="hidden" name="exp_type_sales[]" id="" data-id="'+nextId+'" class="form-control exp_type_sales" >'+
                                                  '<input type="hidden" name="exp_days_sales[]" value="" class="form-control exp_days_sales" data-id="'+nextId+'" >'+
                                            '</div>' +
                                            '<div class="<?php echo ($godown_status == 1)?'col-md-2':'col-md-3'; ?> godown_batch">'+
                                                '<input type="text" name="batch_qty_sales[]" value="0" class="form-control batch_qty_sales" data-id="'+nextId+'" placeholder="Quantity">'+
                                            '</div>'+
                                            '<div class="<?php echo ($godown_status == 1)?'col-md-2':'col-md-3'; ?> godown_batch">' +
                                                '<input type="text" name="batch_rate_sales[]" value="0" class="form-control batch_rate_sales" data-id="'+nextId+'" placeholder="Rate">' +
                                            '</div>' +

                                            '<div class="col-md-3 godown_batch">'+
                                                '<input type="text" name="batch_value_sales[]" value="0" class="form-control batch_value_sales" data-id="'+nextId+'" placeholder="Value">'+
                                            '</div>';
                                        html +=  '</div>';

                            if (batchQut <= 0) {
                                 LS_batch.length = 0;
                                    $('#godownBatchModalSales .each-batch').each(function() {
                                        if($(this).find(".batch_qty_sales").val() > 0){
                                            let newItem = {
                                                'batch_godown_id': $(this).find(".batch_godown_id_sales").val(),
                                                'batch_godown_name': $(this).find(".batch_godown_name_sales").val(),
                                                'batch_no': $(this).find(".batch_no_sales").val(),
                                                'batch_id': $(this).find(".batch_id_sales").val(),
                                                'manufact_date': $(this).find(".manufact_date_sales").val(),
                                                'exp_type_id': $(this).find(".exp_type_id_sales").val(),
                                                'exp_type': $(this).find(".exp_type_sales").val(),
                                                'exp_days': $(this).find(".exp_days_sales").val(),
                                                'batch_qty': $(this).find(".batch_qty_sales").val(),
                                                'batch_rate': $(this).find(".batch_rate_sales").val(),
                                                'batch_value': $(this).find(".batch_value_sales").val(),
                                                'product_stock_id':product_stock_id,
                                                'productBatchStatus': 1
                                            };
                                            LS_batch.push(newItem);
                                        }
                                    });
                                localStorage.setItem('batchGodownStockId_' + product_stock_id, JSON.stringify(LS_batch));
                                    
                                $("#godownBatchModalSales").modal('hide');
                                $(quenty_self).closest('.each-product').find('.product-price').focus();
                                
                                $('#godownBatchModalSales .godownbatchRow').each(function() {
                                      $(this).find('.row').remove();
                                });
                                var godown_status = '<?php echo $godown_status ;?>'
                                let html = ''
                                html =`<div class="row each-batch form-group" >
                                    ${ (godown_status)? 
                                            `<div class="col-md-3 godown_value godown_batch">
                                                <input type="hidden" class="form-control batch_godown_id_sales" name="batch_godown_id_sales[]" data-id="0" value="">
                                                <input type="text" class="form-control batch_godown_name_sales" name="batch_godown_name_sales[]" data-id="0" placeholder="Godown" value="">
                                            </div>
                                            <div class="col-md-2 godown_batch">`
                                    :
                                            `<input type="hidden" class="form-control batch_godown_id_sales" name="batch_godown_id_sales[]" data-id="0" value="1">
                                             <input type="hidden" class="form-control batch_godown_name_sales" name="batch_godown_name_sales[]" data-id="0" placeholder="Godown" value="">
                                        <div class="col-md-3 godown_batch">`
                                    }
                                        <input type="text" name="batch_no_sales[]" value="" class="form-control batch_no_sales" data-id="0" placeholder="Batch No">
                                        <input type="hidden" name="batch_id_sales[]" value="" class="form-control batch_id_sales" data-id="0" >
                                        <input type="hidden" name="manufact_date_sales[]" value="" class="form-control manufact_date_sales " data-id="0" > 
                                        <input type="hidden" name="exp_type_id_sales[]" id="" data-id="0" class="form-control exp_type_id_sales" >
                                        <input type="hidden" name="exp_type_sales[]" id="" data-id="0" class="form-control exp_type_sales" >
                                        <input type="hidden" name="exp_days_sales[]" value="" class="form-control exp_days_sales" data-id="0" >
                                    </div>
                                    <div class="${ (godown_status)?`col-md-2`:`col-md-3`} godown_batch">
                                        <input type="text" name="batch_qty_sales[]" value="0" class="form-control batch_qty_sales" data-id="0" placeholder="Quantity">
                                    </div>
                                    <div class="${ (godown_status)?`col-md-2`:`col-md-3`} godown_batch">
                                        <input type="text" name="batch_rate_sales[]" value="0" class="form-control batch_rate_sales" data-id="0" placeholder="Rate">                            
                                    </div>
                                    <div class="col-md-3 godown_batch">
                                        <input type="text" name="batch_value_sales[]" value="0" class="form-control batch_value_sales" data-id="0" placeholder="Value">                            
                                    </div>
                                </div>`
                                $("#godownBatchModalSales .godownbatchRow").append(html);
                            } else {
                                if(validationBatchFn(_self)){
                                    $("#godownBatchModalSales .godownbatchRow").append(html);
                                     <?php if ($godown_status == 1){ ?>
                                         $(".batch_godown_name_sales[data-id="+nextId+"]").focus();
                                     <?php }else{ ?>
                                         $(".batch_no_sales[data-id="+nextId+"]").focus();
                                     <?php }?>
                                 }
                                
                            }
                        }

                    });
                    
                    var validationBatchFn = (self) =>{
                        let batchNo = $(self).closest('.each-batch').find('.batch_no_sales').val()
                        let batch_godown_name = $(self).closest('.each-batch').find('.batch_godown_name_sales').val()

                        if(batch_godown_name == ''){
                            $(self).closest('.each-batch').find('.batch_godown_name_sales').focus()
                            Command: toastr["error"]("Please select godown.");
                            return false;
                        }

                        if(batchNo == ''){
                            $(self).closest('.each-batch').find('.batch_no_sales').focus()
                            Command: toastr["error"]("Select enter batch.");
                            return false;
                        }

                        return true;

                     }
                    
                    
                    //update product quenty for sales
                    $("body").delegate('.godown_qty_sales', 'keyup', function(e) {
                        var stock = 0;
                        $(".godown_qty_sales").each(function() {
                            stock = parseFloat(stock) + parseFloat($(this).val());
                        });
                        $(quenty_self).val(stock);
                    });
                    
                    //add Godown row for sales 
                    $("body").delegate('.godown_qty_sales', 'keydown', function(e) {
                    if (e.which == 13) {
                        e.preventDefault();
                        var _self = this
                        var having_batch = 0;
                        var id = $(this).data('id');
                        var nextId = parseInt(id+1);
                        var qty = $(".godown_qty_sales"+"[data-id="+id+"]").val();
                        var godownQut = $(this).closest('.tr_godown_row').find('.godown_qty_sales').val()
                        var html = '<div class="row tr_godown_row form-group" id="'+(nextId+1)+'">';
                                    <?php // if ($preference->godown == 1): ?>
                                    <?php if (TRUE): ?>
                                    html += '<div class="col-md-6">'+
                                            '<input type="hidden" class="form-control batch_godown_id_sales" name="batch_godown_id_sales[]" data-id="'+nextId+'" value="">'+
                                            '<input type="text" class="form-control batch_godown_name_sales" name="batch_godown_name_sales[]" data-id="'+nextId+'" placeholder="Godown" value="">'+
                                        '</div>';
                                    <?php //endif ?>

                                    <?php //if ($preference->batch == 0 && $preference->godown == 1): ?>

                                    html += '<div class="col-md-6">' +
                                                '<input type="text" name="godown_qty_sales[]" value="0" class="form-control godown_qty_sales" data-id="'+nextId+'" placeholder="Quantity">' +
                                            '</div>' ;
                                    <?php endif ?>
                                    html +=  '</div>';
                        
                                    if (godownQut <= 0) {
                                        LS_godown.length = 0;
                                        $('#godownModalSales .tr_godown_row').each(function() {
                                            if($(this).find(".godown_qty_sales").val() > 0){
                                                var newItem = {
                                                    'batch_godown_id': $(this).find(".batch_godown_id_sales").val(),
                                                    'batch_godown_name': $(this).find(".batch_godown_name_sales").val(),
                                                    'godown_qty': $(this).find(".godown_qty_sales").val(),
                                                    'product_stock_id':product_stock_id,
                                                    'productBatchStatus': 0
                                                };
                                                LS_godown.push(newItem);
                                            }
                                        });
                                        localStorage.setItem('batchGodownStockId_' + product_stock_id, JSON.stringify(LS_godown));
                        
                                        $("#godownModalSales").modal('hide');
                                        
                                        $(quenty_self).closest('.each-product').find('.product-price').focus();

                                        $('#godownModalSales .godownRow').each(function() {
                                            $(this).find('.row').remove();
                                        });
                                         let html = `<div class="form-group row tr_godown_row" id="" >
                                                    <div class="col-md-6">
                                                        <input type="hidden" class="form-control batch_godown_id_sales" name="batch_godown_id_sales[]" data-id="0" value="">
                                                        <input type="text" class="form-control batch_godown_name_sales" name="batch_godown_name_sales[]" data-id="0" placeholder="Godown" value="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" name="godown_qty_sales_sales[]" value="0" class="form-control godown_qty_sales" data-id="0" placeholder="Quantity">
                                                    </div>
                                            </div>`
                                    $("#godownModalSales .godownRow").append(html);

                                    } else {
                                        if(validationGodownFn(_self)){
                                            $("#godownModalSales .godownRow").append(html);
                                            $("#godownModalSales .godownRow .batch_godown_name_sales[data-id="+nextId+"]").focus();
                                        }
                                    }
                    }

                });
                
                
                var validationGodownFn = (self) =>{
                    let batch_godown_name = $(self).closest('.tr_godown_row').find('.batch_godown_name_sales').val()
                                   
                    if(batch_godown_name == ''){
                        $(self).closest('.tr_godown_row').find('.batch_godown_name_sales').focus()
                        Command: toastr["error"]("Please select godown.");
                        return false;
                    }
                    
                    return true;
                    
                }
                
                 <?php endif ?>
            
            
            //GODOWN END=============================
        });


    </script>



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


    <!-- edit ledger modal -->
    <div id="editLedgerModal" class="modal fade" role="dialog">
        <?php echo form_open(base_url('index.php/accounts/ajax_save_ledger_data'), array('class' => 'accounts-form', 'id' => 'add_ledger_form')); ?>
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Ledger</h4>
          </div>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary ladda-button" data-color="blue" data-style="expand-right" data-size="xs">Save</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
      <?php echo form_close(); ?>
    </div>

    <!-- edit ledger script -->
    <script type="text/javascript">

    function chech_validation() {
        var group_name = document.getElementById("group_name1").value;

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
    $('body').on("keyup", "#editLedgerModal #opening_balance", function() {
        var valid = /^\d{0,12}(\.\d{0,2})?$/.test(this.value),
                val = this.value;

        if (!valid) {
            this.value = val.substring(0, val.length - 1);
        }
    });

    // bank details section will show if bank_account is selected as group name
    $('body').on("keypress", "#ladger_name", function(e) {
        if (e.keyCode == 13 && $(this).val() != "" && $("#group_id").val() == 10) {
            // bank details modal will pop-up if banck-account is selected as group and ledger name is not null
            $("#editLedgerModal #bank_details_section").show();

        }else if (e.keyCode == 13 && ($("#group_id").val() != 10 || $(this).val() == "")){
           $("#editLedgerModal #bank_details_section").hide(); 
           $("#editLedgerModal #account").focus();
        }
    });

    $("body").on("change focusout", "#editLedgerModal #group_name1", function() {
        contactBankCheck();
        
    });

    
    $('body').on("focusout", "#group_name1", function() {
        if($("#editLedgerModal #group_id").val() == 10) {
            $("#editLedgerModal #bank_details_section").show();
        }else{
            $("#editLedgerModal #bank_details_section").hide(); 
        }

        if($("#editLedgerModal #group_id").val() == 15 || $("#editLedgerModal #group_id").val() == 23) {
            $("#editLedgerModal #contact_section").show();
        }else{
            $("#editLedgerModal #contact_section").hide();
        }

    });

    $("body").on("click", "#editLedgerModal #addGroupBtn", function(e) {
        e.preventDefault();
        $("#addGroup").modal('show');
    });

    function contactBankCheck() {
        var group_id = $('#editLedgerModal #group_id').val();

        if(group_id == 15 || group_id == 23) {
            $("#contact_section").show();
        }else{
            $("#contact_section").hide();
        }
        if(group_id == 10) {
            $("#bank_details_section").show();
        }else{
            $("#bank_details_section").hide(); 
        }
    }

</script>
<script>

    $(function() {
        $("body").delegate("#group_name1", 'focus.autocomplete', function() {
            $(this).autocomplete({
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
                    $("#editLedgerModal #group_id").val(ui.item.value);
                    var group_id = ui.item.value;

                    $.ajax({
                        type: "POST",
                        url: '<?php echo site_url('accounts/accounts/checkGroup'); ?>',
                        data: "group_id=" + ui.item.value,
                        dataType: "json",
                        success: function(data){
                        if(data.res){
                         $("#editLedgerModal #contact_required").val(1);   
                        }else{
                         $("#editLedgerModal #contact_required").val(0);   
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
            });

            $(this).autocomplete("search", "");
        });

        $("body").delegate("#editLedgerModal .contact", 'focus.autocomplete', function() {
            $(this).autocomplete({
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
                $("#editLedgerModal #contact_id").val(ui.item.value);
            }
        });

            $(this).autocomplete("search", "");
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
                        console.log(index + " " + value);
                        $('#editLedgerModal #' + index).closest('.form-group').addClass('has-error');
                        $('#editLedgerModal #' + index).closest('.input-block').find('.errorMessage').html(value);
                    });
                } else if (data.res == 'save_err') {
                    Command: toastr["error"](data.message);
                } else {
                    $("#editLedgerModal").modal('hide');
                    Command: toastr["success"](data.message);
                    // window.location.href = "<?php echo site_url('admin/accounts-groups') . '?_tb=2'; ?>";
                }
            }
        });

    });

    });
</script>
<script>
    $("body").on("click", "input[name='bill_details_status']", function() {
        var val = $(this).val();
        if (parseInt(val) == 1) {
            $(".credit_limit_div").show();
        } else {
            $(".credit_limit_div").hide();
        }
    });
</script>

    