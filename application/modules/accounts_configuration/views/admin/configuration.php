<div class="wrapper2">
    <form role="form" action="<?php echo base_url('accounts_configuration/admin/configurationmodify'); ?>" method="post" id="configuration_form" enctype="multipart/form-data">    
        <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                    <h1> <i class="fa fa-gears"></i>Preference</h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <!--<button class="btn btn-sm btn-default">Discard</button>-->
                        <?php
                        $permissionedit = admin_users_permission('E', 'configurations', $rtype = FALSE);
                        if ($permissionedit) {
                            ?>
                            <button class="btn btn-sm btn-primary">Save</button>
                        <?php } ?>

                    </div>
                </div> 
            </div>     
        </section>
        <section>
            <?php
            $this->breadcrumbs->push('Home', '/admin/dashboard');
            $this->breadcrumbs->push('Settings', '#');
            $this->breadcrumbs->push('Preference', '/admin/accounts-configuration');
            $this->breadcrumbs->show();
            ?>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-solid">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-gear"></i> General</h3>                            
                        </div>

                        <div class="box-body table-fullwidth table-configuration">
                            <div class="table-responsive">
                            <table class="table table-striped lcol-125">
                                <thead>
                                    <tr role="row" class="sub-childs">
                                        <th>Properties </th>
                                        <th>Settings </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Skip date field in create mode (for faster entry)</td>
                                        <td><label>
                                                <input type="radio" value="1" name="skip_date_create" class="minimal" <?php
                                                if (isset($configuration->skip_date_create) && $configuration->skip_date_create == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="skip_date_create" class="minimal" <?php
                                                if (isset($configuration->skip_date_create) && $configuration->skip_date_create == '2') {
                                                    echo 'checked';
                                                }
                                                ?>>
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr class="hidden">
                                        <td>Use To/By instead of Dr/Cr during entry</td>
                                        <td><label>
                                                <input type="radio" value="1" name="toby_instead_drcr" class="minimal" <?php
                                                if (isset($configuration->toby_instead_drcr) && $configuration->toby_instead_drcr == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label> 
                                                <input type="radio" value="2" name="toby_instead_drcr" class="minimal" <?php
                                                if (isset($configuration->toby_instead_drcr) && $configuration->toby_instead_drcr == '2') {
                                                    echo 'checked';
                                                }
                                                ?>>
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr class="hidden">
                                        <td>Use cheque printing for contra</td>
                                        <td><label>
                                                <input type="radio" value="1" name="contra_cheque_printing" class="minimal" <?php
                                                if (isset($configuration->contra_cheque_printing) && $configuration->contra_cheque_printing == '1') {
                                                    echo 'checked';
                                                }
                                                ?>>
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="contra_cheque_printing" class="minimal" <?php
                                                if (isset($configuration->contra_cheque_printing) && $configuration->contra_cheque_printing == '2') {
                                                    echo 'checked';
                                                }
                                                ?>>
                                                No
                                            </label>  </td>
                                    </tr>
                                    <tr class="hidden">
                                        <td>Warn on Nagetive cash balance(beep with every entry)</td>
                                        <td> <label>
                                                <input type="radio" value="1" name="warn_nagetive_cash_balance" class="minimal" <?php
                                                if (isset($configuration->warn_nagetive_cash_balance) && $configuration->warn_nagetive_cash_balance == '1') {
                                                    echo 'checked';
                                                }
                                                ?>>
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="warn_nagetive_cash_balance" class="minimal" <?php
                                                if (isset($configuration->warn_nagetive_cash_balance) && $configuration->warn_nagetive_cash_balance == '2') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
<!--                                    <tr>
                                        <td>Allow cash account in Journal</td>
                                        <td><label>
                                            <input type="radio" value="1" name="cash_account_journal" class="minimal" <?php
                                    if (isset($configuration->cash_account_journal) && $configuration->cash_account_journal == '1') {
                                        echo 'checked';
                                    }
                                    ?>>
                                            Yes
                                        </label>
                                        <label>
                                            <input type="radio" value="2" name="cash_account_journal" class="minimal" <?php
                                    if (isset($configuration->cash_account_journal) && $configuration->cash_account_journal == '2') {
                                        echo 'checked';
                                    }
                                    ?> >
                                            No
                                        </label> </td>
                                    </tr>-->
                                    <tr class="hidden">
                                        <td>Show ledger current balance in voucher</td>
                                        <td><label>
                                                <input type="radio" value="1" name="voucher_ledger_current_balance" class="minimal" <?php
                                                if (isset($configuration->voucher_ledger_current_balance) && $configuration->voucher_ledger_current_balance == '1') {
                                                    echo 'checked';
                                                }
                                                ?>>
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="voucher_ledger_current_balance" class="minimal" <?php
                                                if (isset($configuration->voucher_ledger_current_balance) && $configuration->voucher_ledger_current_balance == '2') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr class="hidden">
                                        <td >Show balance as on voucher date</td>
                                        <td><label>
                                                <input type="radio" value="1" name="voucher_date_balance_show" class="minimal" <?php
                                                if (isset($configuration->voucher_date_balance_show) && $configuration->voucher_date_balance_show == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="voucher_date_balance_show" class="minimal" <?php
                                                if (isset($configuration->voucher_date_balance_show) && $configuration->voucher_date_balance_show == '2') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label>  </td>
                                    </tr>
                                    <tr>
                                        <td>Code for group creation form will be auto generated</td>
                                        <td><label>
                                                <input type="radio" value="1" name="group_code_status" class="minimal" <?php
                                                if (isset($configuration->group_code_status) && $configuration->group_code_status == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="group_code_status" class="minimal" <?php
                                                if (isset($configuration->group_code_status) && $configuration->group_code_status == '2') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label></td>
                                    </tr>
                                    <tr>
                                        <td>Code for ledger creation form will be auto generated </td>
                                        <td><label>
                                                <input type="radio" value="1" name="ledger_code_status" class="minimal" <?php
                                                if (isset($configuration->ledger_code_status) && $configuration->ledger_code_status == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="ledger_code_status" class="minimal" <?php
                                                if (isset($configuration->ledger_code_status) && $configuration->ledger_code_status == '2') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label></td>
                                    </tr>
                                    <tr>
                                        <td>Number for voucher creation form will be auto generated </td>
                                        <td><label>
                                                <input type="radio" value="1" name="voucher_no_status" class="minimal" <?php
                                                if (isset($configuration->voucher_no_status) && $configuration->voucher_no_status == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="voucher_no_status" class="minimal" <?php
                                                if (isset($configuration->voucher_no_status) && $configuration->voucher_no_status == '2') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>  
                                    <tr>
                                        <td>Do you want product attributes?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="requir_product_attributes" class="minimal" <?php
                                                if (isset($configuration->requir_product_attributes) && $configuration->requir_product_attributes == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="requir_product_attributes" class="minimal" <?php
                                                if (isset($configuration->requir_product_attributes) && $configuration->requir_product_attributes == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want your account with inventory?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="is_inventory" class="minimal" <?php
                                                if (isset($configuration->is_inventory) && $configuration->is_inventory == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="is_inventory" class="minimal" <?php
                                                if (isset($configuration->is_inventory) && $configuration->is_inventory == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want updated currency value time of entry?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="required_updated_currency" class="minimal" <?php
                                                if (isset($configuration->required_updated_currency) && $configuration->required_updated_currency == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="required_updated_currency" class="minimal" <?php
                                                if (isset($configuration->required_updated_currency) && $configuration->required_updated_currency == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want to show Ledger and Group Code before name?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="code_before_name" class="minimal" <?php
                                                if (isset($configuration->code_before_name) && $configuration->code_before_name == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="code_before_name" class="minimal" <?php
                                                if (isset($configuration->code_before_name) && $configuration->code_before_name == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want which type of currency format?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="price_format" class="minimal" <?php
                                                if (isset($configuration->price_format) && $configuration->price_format == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                US
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="price_format" class="minimal" <?php
                                                if (isset($configuration->price_format) && $configuration->price_format == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                IN
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want recurring entry?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="want_recurring" class="minimal" <?php
                                                if (isset($configuration->want_recurring) && $configuration->want_recurring == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="want_recurring" class="minimal" <?php
                                                if (isset($configuration->want_recurring) && $configuration->want_recurring == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want sales mail?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="sales_mail" class="minimal" <?php
                                                if (isset($configuration->sales_mail) && $configuration->sales_mail == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="sales_mail" class="minimal" <?php
                                                if (isset($configuration->sales_mail) && $configuration->sales_mail == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want sales order mail?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="sales_order_mail" class="minimal" <?php
                                                if (isset($configuration->sales_order_mail) && $configuration->sales_order_mail == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="sales_order_mail" class="minimal" <?php
                                                if (isset($configuration->sales_order_mail) && $configuration->sales_order_mail == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want receipt mail?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="receipt_mail" class="minimal" <?php
                                                if (isset($configuration->receipt_mail) && $configuration->receipt_mail == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="receipt_mail" class="minimal" <?php
                                                if (isset($configuration->receipt_mail) && $configuration->receipt_mail == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want bank details in invoice?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="bank_details" id="yesBank" class="minimal" <?php
                                                if (isset($configuration->bank_details) && $configuration->bank_details == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="bank_details" class="minimal" <?php
                                                if (isset($configuration->bank_details) && $configuration->bank_details == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want e-way bill?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="eway_bill" id="eway-bill" class="minimal" <?php
                                                if (isset($configuration->eway_bill) && $configuration->eway_bill == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="eway_bill" class="minimal" <?php
                                                if (isset($configuration->eway_bill) && $configuration->eway_bill == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want to enable godown?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="godown" id="" class="minimal" <?php
                                                if (isset($configuration->godown) && $configuration->godown == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="godown" class="minimal" <?php
                                                if (isset($configuration->godown) && $configuration->godown == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want to enable batch?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="batch" id="" class="minimal" <?php
                                                if (isset($configuration->batch) && $configuration->batch == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="batch" class="minimal" <?php
                                                if (isset($configuration->batch) && $configuration->batch == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want to enable print option?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="print" id="" class="minimal" <?php
                                                if (isset($configuration->print) && $configuration->print == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="print" class="minimal" <?php
                                                if (isset($configuration->print) && $configuration->print == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>How many days an entry can be edited?</td>
                                        <td><input type="number" name="entry_action_limit" min="0" class="form-control" value="<?php echo $configuration->entry_action_limit; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td>Which currency unit want?</td>
                                        <td style="padding-top:5px !important;padding-bottom:4px !important">
                                            <select class="form-control" name="selected_currency">
                                                <?php
                                                if (count($currency) > 0):
                                                    foreach ($currency as $value) {
                                                        ?>
                                                        <option value="<?php echo $value->id ?>" <?php echo ($value->id == $configuration->selected_currency) ? 'selected' : '' ?>><?php echo $value->currency ?></option>
                                                        <?php
                                                    }
                                                endif;
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Which date format do you want?</td>
                                        <td style="padding-top:4px !important;padding-bottom:4px !important">
                                            <select class="form-control" name="selected_date_format">
                                                <?php
                                                if (count($date_format) > 0):
                                                    foreach ($date_format as $value) {
                                                        ?>
                                                        <option value="<?php echo $value->id ?>" <?php echo ($value->id == $configuration->selected_date_format) ? 'selected' : '' ?>><?php echo $value->dateformat ?></option>
                                                        <?php
                                                    }
                                                endif;
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php if ($number_of_branch > 1): ?>
                                    <tr>
                                        <td colspan="2" style="padding-top:0 !important;padding-bottom:0 !important;">
                                            <span class="pull-left" style="padding:10px 0;">Which branch data are you want to show?</span>
                                            <span  style="float: right;padding:3px 0;" class="config-select2" >
                                            <select multiple="multiple" class="form-control select2" name="selected_branch[]" id="selected_branch">
                                                <?php                                             
                                                $arr =($this->session->userdata('selected_branch'))? $this->session->userdata('selected_branch'):[$this->session->userdata('branch_id')];
                                                foreach ($branch as $b) {  
                                                    ?>
                                                    <option value="<?php echo $b->id ?>" <?php echo in_array($b->id, $arr) ? 'selected' : '' ?>><?php echo $b->company_name ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                                </span>
                                        </td>
                                    </tr>
                                    <?php endif ?>
                                </tbody>
                            </table>
</div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-4">
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
                </div>
            </div>
        </section>   
    </form>             
</div>
<!-- /.content -->

<!-- bank details Modal -->
<!-- <div id="bankDetailsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Bank Details</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" id="bankForInvoiceSave" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // if bank details in invoice yes then open up the bank details modal to save a primary bank
        $("#yesBank").on('click', function() {
            $('#bankDetailsModal').modal('show');
            $.ajax({
                url: "<?php echo base_url(); ?>admin/getAllBankDetailsCompanyForInvoice",
                type: "POST",
                success: function(response) {
                    if(response.length < 1){
                        $('#bankForInvoiceSave').hide();
                    }
                    $("#bankDetailsModal .modal-body").html(response);
                }
            });
        });
        
        // primary bank update after submit
        $('#bankForInvoiceSave').on('click', function(e) {
            e.preventDefault();
            var data = $("#bankForInvoiceForm").serialize();
            $('#bankForInvoiceSave').text('working...');
            $('#bankForInvoiceSave').prop('disabled', true);
            $.ajax({
                url: "<?php echo base_url(); ?>admin/saveDefaultBank",
                type: 'POST',
                data: data,
                success: function(response) {
                    $('#bankForInvoiceSave').text('Save');
                    $('#bankForInvoiceSave').prop('disabled', false);
                    $("#bankDetailsModal").modal('hide');
                    Command:toastr['success']('Default bank saved');
                }
            });
        });

        // before save the preference check for current bank details status
        $(".save_preference").on('click', function(e) {
            e.preventDefault();
            var bank = $("input[type=radio][name=bank_details]:checked").val();
            // if bank details checked as no then submit otherwise
            // check there is a bank selected for transaction
            if (bank == 1) {
                $.ajax({
                    url: "<?php echo base_url(); ?>admin/getBankDetailsStatus",
                    dataType: 'JSON',
                    success: function(response) {
                        // if response it true then there is a bank selected for transaction
                        // otherwise not
                        if (response.flag) {
                            $("#configuration_form").submit();
                        } else {                            
                            Command:toastr['error']('Please select a primary bank for transaction first');
                        }
                    }
                });
            } else {
                $("#configuration_form").submit();
            }
        });


    });
</script> -->