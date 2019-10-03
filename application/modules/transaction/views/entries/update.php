<link rel="stylesheet" href="<?php echo base_url(); ?>assets/account/css/act-main.css">
<style>
    .modal {
        text-align: center;
    }
    .modal:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
    }

    .modal-dialog {
        display: inline-block;
        vertical-align: middle;

    }
    .ui-autocomplete{z-index: 99999!important}
</style>
<div class="side_toggle">
    <div id="myDiv"><button class="btn btn-sm btn-danger myButton  btn-closePanel"><i class="fa fa-times"></i></button>
        <form style="padding:20px;">    
            <div class="form-group">
                <label for="">Form Submission</label>
                <div class="form-group"> 
                    <div class="radio">
                        <label><input type="radio" value="1" name="activity_submit" checked="true">Submit &amp; Show New Form</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" value="2" name="activity_submit">Submit &amp; Show List</label>
                    </div>

                    <div class="radio">
                        <label><input type="radio" value="3" name="activity_submit">Submit &amp; Print</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="">Adder Account</label>
                <div class="form-group"> 
                    <a href="javascript:void(0);" class="new-ledger-btn">Add Ledger</a><br>
                    <a href="javascript:void(0);" class="add-group-btn">Add Group</a>

                </div>
            </div>
            <?php if (isset($entry_type['id']) && ($entry_type['id'] == 1 || $entry_type['parent_id']==1)): ?>
                <div class="form-group">
                    <label for="">Do you want advance entry?</label>
                    <div class="form-group"> 
                        <label class="radio-inline"><input type="radio" value="1" name="advance_entry" <?php echo (isset($entry->tax_or_advance) && $entry->tax_or_advance == 1) ? 'checked' : '' ?>>Yes</label>
                        <label class="radio-inline"><input type="radio" value="0" name="advance_entry"  <?php echo (isset($entry->tax_or_advance) && $entry->tax_or_advance == 0) ? 'checked' : '' ?>>No</label>

                    </div>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>
<div class="">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1><i class="fa fa-list"></i> 
                    <?php
                    echo isset($entry_type['type'])? ucwords($entry_type['type']):'';?>
                </h1>
            </div>
            <div class="col-xs-6 text-right">
                <div class="pull-right">               
                    <button id="button" class="myButton btn btn-settings btn-svg pull-right"><i data-feather="settings"></i></button>  
                </div>
            </div>
        </div>
    </section>
    <section>
        <?php
        $prev_breadcrumbs = $this->session->userdata('_breadcrumbs');
         if ($action == 'e') {
             $_action='Update';
             $current_breadcrumbs = array($_action => '/admin/transaction/update');
        } else {
            $_action='Copy';
            $current_breadcrumbs = array($_action => '/admin/transaction/copy');
        }
        
        $breadcrumbs = array_merge($prev_breadcrumbs, $current_breadcrumbs);
        $this->session->set_userdata('_breadcrumbs', $breadcrumbs);
        foreach ($breadcrumbs as $k => $b) {
            $this->breadcrumbs->push($k, $b);
            if($k==$_action){
                break;
            }
        }
        $this->breadcrumbs->show();
        ?>
    </section>
    <script>
        var transaction_type_id = '<?php echo $entry_type['id'] ?>';
        var parent_id = '<?php echo $entry_type['parent_id']; ?>';
    </script>
    <!-- Main content -->
    <section class="content"> 
        <?php
        if ($action == 'e'):
            ?>
            <form method="POST" id="tran-update-form" action="<?php echo base_url(); ?>transaction/entries/ajax_update_transaction" class="formSubmitAll transaction-form">
                <?php
            else:
                ?>
                <form method="POST" id="tran-update-form" action="<?php echo base_url(); ?>transaction/entries/ajax_copy_transaction" class="formSubmitAll">
                <?php
                endif;
                ?>
                <div class="box">            
                    <div class="box-body">
                        <input type="hidden" class="advance_ledger_id" name="advance_ledger_id" value="<?php echo (isset($entry_service_details[0]->transaction_type) && $entry_service_details[0]->transaction_type == 1) ? $entry_service_details[0]->expences_ledger_id : '' ?>">
                        <input type="hidden" class="in_ledger_state" name="in_ledger_state" value="">
                        <input type="hidden" class="in_ledger_country" name="in_ledger_country" value="">
                        <input type="hidden" class="expences_ledger_id" name="expences_ledger_id" value="<?php echo (isset($entry_service_details[0]->transaction_type) && $entry_service_details[0]->transaction_type == 0) ? $entry_service_details[0]->expences_ledger_id : '' ?>">
                        <div class="row">
                            <div class="col-md-2">
                                <label class="mtb-10">Entry Number</label>                    
                            </div>
                            <div class="col-md-3">
                                <div class="form-group"> 
                                    <input type="hidden" name="entry_type_id" value="<?php echo $entry_type['id'] ?>">
                                    <input type="hidden" name="parent_id" value="<?php echo $entry_type['parent_id'] ?>">
                                    <input type="hidden" id="entry_id" name="entry_id" value="<?php echo $entry->id; ?>">
                                    <?php if ($action == 'c'): ?>
                                        <input type="text" class="form-control entry_no" value="<?php echo ($auto_no_status == "1") ? 'Auto' : '' ?>" <?php echo ($auto_no_status == "1") ? 'readonly' : '' ?> autofocus placeholder="Enter entry no" name="entry_number" />
                                    <?php else: ?>
                                        <input type="text" class="form-control"  readonly="readonly" autofocus placeholder="Enter entry no" value="<?php echo $entry->entry_no; ?>" name="entry_number" id="entry_number"/>
                                    <?php endif; ?>
                                </div>
                            </div>                    
                            <div class="col-md-2">
                                <?php if ($recurring == 1): ?>
                                    <input type="text" name="recurring_freq" class="form-control recurring" placeholder="Recurring Frequency" value="<?php echo isset($entry->frequency) ? $entry->frequency : '' ?>" >
                                <?php endif; ?>
                            </div>
                            <div class="col-md-2">                    
                                <label class="mtb-10">Date</label>                        
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <?php if ($action == 'c'): ?>
                                            <input class="form-control" type="text" id="tr_date" placeholder="DD/MM/YYYY" value="<?php echo ($auto_date == "1") ? date('d/m/Y') : '' ?>" name="tr_date">
                                        <?php else: ?>
                                            <input class="form-control" type="text" id="tr_date" placeholder="DD/MM/YYYY" name="tr_date" value="<?php echo date("d/m/Y", strtotime($entry->create_date)); ?>" autocomplete="off">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>                    
                        </div>   
                        <?php if (isset($voucher_id) && ($voucher_id == 5 || $voucher_id == 6 || $parent_id == 5 || $parent_id == 6)): ?>
                            <div class="row">
                                <div class="col-md-2"> <label>Ref. No</label>  </div>  
                                <div class="col-md-3"><input class="form-control" name="voucher_no" placeholder="Ref. No" value="<?php echo $entry->voucher_no; ?>"></div> 
                                <div class="col-md-2"></div>
                                <div class="col-md-2"><label>Ref. Date</label></div> 
                                <div class="col-md-3"><input class="form-control" name="voucher_date" id="voucher_date" placeholder="Ref. Date" value="<?php echo date("d/m/Y", strtotime($entry->voucher_date)); ?>"></div> 
                            </div>
                            <br>
                        <?php endif; ?>
                        <table class="table table-add-receipt">
                            <thead>
                                <tr>
                                    <th>Ledger</th>
                                    <th>Dr./Cr.</th>
                                    <th>Dr. Amount</th>
                                    <th>Cr. Amount</th>
                                </tr>
                            </thead>
                            <tbody class="tr_transaction_main">
                                <?php if (count($entry_details) > 0): ?>
                                    <?php foreach ($entry_details as $key => $row): ?>

                                        <tr class="tr_row_ledger" id="<?php echo $key + 1 ?>">
                                            <td>
                                                <input type="text" id="tr_ledger_<?php echo $key + 1 ?>" class="form-control tr_ledger" selected value="<?php echo $row->ladger_name ?>" name="tr_ledger[]">
                                                <input type="hidden" class="tr_ledger_id" name="tr_ledger_id[]" value="<?php echo $row->ladger_id ?>">

                                            </td>    
                                            <td><input type="text" class="form-control tr_type tr_type_<?php echo $key + 1 ?>" name="tr_type[]" value="<?php echo $row->account; ?>" placeholder="Dr" autocomplete="off"></td>
                                            <td><input type="text" class="form-control text-right tr_dr_amount price" name="amount[]" value="<?php echo ($row->account == 'Dr') ? $row->balance : ''; ?>" <?php echo ($row->account == 'Cr') ? "disabled" : ""; ?> autocomplete="off"></td>
                                            <td><input type="text" class="form-control text-right tr_cr_amount price" name="amount[]" value="<?php echo ($row->account == 'Cr') ? $row->balance : ''; ?>" <?php echo ($row->account == 'Dr') ? "disabled" : ""; ?> autocomplete="off"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <tr class="bg-bluel tr_total_row">
                                    <td><label>Total</label></td>
                                    <td></td>
                                    <td><label class="tr_total_dr"></label></td>
                                    <td><label class="tr_total_cr"></label></td>
                                    <input type="hidden" name="tr_total_dr" value="">
                                    <input type="hidden" name="tr_total_cr" value="">
                                </tr>

                            </tbody>
                        </table>

                        <div class="row">                     
                            <div class="col-md-1 col-xs-2">
                                <div class="form-group">
                                    <label>Naration</label>
                                </div>
                            </div>
                            <div class="col-md-11  col-xs-10">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="tr_narration" value="<?php echo $entry->narration; ?>">
                                </div>
                            </div>                
                        </div>

                        <div class="footer-button" id="tran-update-btn" style="display: block;">
                            <button type="submit" class="btn ladda-button btn-primary" data-color="blue" data-style="expand-right" data-size="s">Save</button>

                        </div>


                    </div><!-- /.box-body -->            
                </div><!-- /.box -->
            </form>

    </section>
</div><!-- /.content-wrapper -->
<!--add group  Modal -->
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
                        <label class="pull-left">Parent Name</label>
                        <div class="s2-example">
                            <p>
                                <select class="select2 form-control" id="parent_id" name="parent_id">
                                    <option value="">Select group</option>
                                    <?php
                                    if (isset($groups)) {
                                        foreach ($groups as $group) {
                                            echo '<option value="' . $group["id"] . '">' . $group["group_name"] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </p>
                        </div>    
                    </div> 

                    <div class="form-group" >
                        <label class="pull-left">Group Name</label>
                        <input type="text" class="form-control" name="group_name" id="group_name">
                    </div> 




                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn ladda-button add-group-btn" data-color="blue" data-style="expand-right" data-size="xs">Save</button>
                </div>
            </form>   
        </div>

    </div>

</div>
<!--add ledger Modal -->
<!-- <div id="addLedger" class="modal fade" role="dialog">
    <div class="modal-dialog" style="top: 70px;">

        <div class="modal-content">
            <form action="<?php echo base_url(); ?>index.php/accounts/ajax_save_ledger_data" class="accounts-form" id='add_ledger_form_te'>

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        Add Ledger
                    </h4>
                </div>
                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-5">
                            <div class="form-group input-block">
                                <label class="pull-left">Group</label>
                                <select class="select2 form-control" name="group_id" id="group_id" onchange="return checkGroup(this)">
                                    <option value="">Select Group</option>
                                    <?php
                                    if (isset($groups)) {

                                        foreach ($groups as $group) {
                                            echo '<option value="' . $group["id"] . '">' . $group["group_name"] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                                <span class="errorMessage"></span>
                                <input type="hidden" id="contact_required" name="contact_required" value="">
                            </div>
                        </div>
                        <div class="col-md-1" style="margin-left:0px;padding-left: 0px;">
                            <label>&nbsp;</label>
                            <a href="<?php echo site_url('admin/add-accounts-groups'); ?>" target="_blank" class="btn btn-primary" data-toggle="tooltip" title="Add Group"><i class="fa fa-plus"></i></a>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group input-block">
                                <label class="pull-left">Ledger Name</label>
                                <input type="text" class="form-control" id="ladger_name" name="ladger_name" placeholder="Ledger Name" autocomplete="off">                
                                <span class="errorMessage"></span>
                            </div>   
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">                
                                <label class="pull-left">Tracking</label><br>                    
                                <input name="tracking_status" id="tracking_status" type="radio" value="1" /> Yes
                                <input name="tracking_status" id="tracking_status" type="radio" checked value="2" /> No
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">       
                                <label class="pull-left">Bill Wise Details</label><br>                
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
                                ?>"  name="ledger_code" id="ledger_code" value="<?php echo count($ledger) > 0 ? $ledger->ledger_code : '' ?>" class="form-control" placeholder="Ledger Code" />
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


                        <div class="credit_limit_div" style="display:<?php echo ((count($ledger) > 0) && ($ledger->bill_details_status == '1' )) ? 'block' : 'none'; ?>">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="pull-left">Credit Date</label> 
                                    <input type="number" class="form-control" id="credit_date" name="credit_date" placeholder="credit date" value="<?php echo isset($ledger->credit_date) ? $ledger->credit_date : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="pull-left">Credit Limit</label> 
                                    <input type="number" class="form-control" id="credit_limit" name="credit_limit" placeholder="credit limit" value="<?php echo isset($ledger->credit_limit) ? $ledger->credit_limit : ''; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="clearfix">
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
                                <a href="<?php echo site_url('admin/add-customer-details'); ?>" target="_blank" class="btn btn-primary" data-toggle="tooltip" title="Add Contacts"><i class="fa fa-plus"></i></a></div>
                            <div class="col-md-5"></div>
                        </div>
                    </div> 
                </div>    

                <div class="modal-footer">
                    <button type="submit" class="btn ladda-button add-ledger-btn" data-color="blue" data-style="expand-right" data-size="xs">Save</button>

                </div>   

            </form>                        

        </div>



    </div>

</div> -->

<!-- new add ledger modal -->
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
                    <button type="submit" class="btn ladda-button add-ledger-btn" data-color="blue" data-style="expand-right" data-size="s">Save</button>

                </div>   

            </form>                        

        </div>



    </div>

</div>
<!-- new add ledger modal ends -->









<!-- popup Modal -->
<div id="popupModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">    
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="tr_tracking_ledger_name"></h4>   
            </div>
            <div class="modal-body">
                <form class="form" role="form" id="popup_form">  



                </form>        
            </div> 
            <div class="modal-footer" id="popup_footer">            

            </div>  
        </div>

    </div>
</div>

<!-- Service Modal -->
<div class="modal fade" id="<?php echo ($this->session->userdata('company_type') == 1 && $this->session->userdata('is_inventory') == 1)?'serviceModal':'';?>" role="dialog">
    <div class="modal-dialog modal-lg" style="top: 70px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">GST</h4>
            </div>
            <div class="modal-body">
                <form id="service_modal_form">
                    <input type="hidden" name="tax_or_advance" class="tax_or_advance" value="<?php echo (isset($entry->tax_or_advance)) ? $entry->tax_or_advance : '' ?>">
                    <input type="hidden" name="igst_status" id="igst_status" value="<?php echo isset($entry_service_details[0]->igst_status) ? $entry_service_details[0]->igst_status : '' ?>">
                    <input type="hidden" name="cess_status" id="cess_status" value="<?php echo isset($entry_service_details[0]->cess_status) ? $entry_service_details[0]->cess_status : '' ?>">
                    <input type="hidden" name="export_status" id="export_status" value="<?php echo isset($entry_service_details[0]->export_status) ? $entry_service_details[0]->export_status : '' ?>">
                    <table width="100%">
                        <thead>
                            <tr>
                                <th>
                                    <label>Type</label>
                                </th>
                                <th>
                                    <label>Item</label>
                                </th>
                                <th>
                                    <label>Amount</label>
                                </th>
                                <th>  
                                    <label>IGST(%)</label>
                                </th>
                                <th>  
                                    <label>Value</label>
                                </th>
                                <th>
                                    <label>SGST(%)</label>
                                </th>
                                <th>
                                    <label>Value</label>
                                </th>
                                <th> 
                                    <label>CGST(%)</label>
                                </th>
                                <th> 
                                    <label>Value</label>
                                </th>
                                <th> 
                                    <label>CESS(%)</label>
                                </th>
                                <th> 
                                    <label>Value</label>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($entry_service_details) > 0): ?>
                                <?php foreach ($entry_service_details as $key => $value) { ?>
                                    <tr>   
                                        <td>
                                            <div class="form-group">   
                                                <select class="form-control" name="service_product[]" id="service_product">
                                                    <option value="1" <?php echo (isset($value->type) && $value->type == 1) ? 'selected' : '' ?>>Service</option> 
                                                    <option value="2" <?php echo (isset($value->type) && $value->type == 2) ? 'selected' : '' ?>>Product</option>
                                                </select>
                                            </div>  
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control search_item" placeholder="Select Service" value="<?php echo isset($value->name) ? $value->name : '' ?>">
                                                <input type="hidden" class="tr_service_id" name="tr_service_id[]" value="<?php echo isset($value->service_product_id) ? $value->service_product_id : '' ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">  
                                                <input type="text" class="form-control service_amount" name="service_amount[]" placeholder="Amount" value="<?php echo isset($value->service_amount) ? $value->service_amount : '' ?>">
                                                <input type="hidden" name="tax_percentage[]" class="tax_percentage" value="<?php echo isset($value->tax_percentage) ? $value->tax_percentage : '' ?>">
                                                <input type="hidden" name="cess_percentage[]" class="cess_percentage" value="<?php echo isset($value->cess_percentage) ? $value->cess_percentage : '' ?>">
                                            </div>
                                        </td>
                                        <?php
                                        $price = isset($value->service_amount) ? $value->service_amount : 0;
                                        $tax_percentage = isset($value->tax_percentage) ? $value->tax_percentage : 0;
                                        if ($entry->tax_or_advance == 1) {
                                            $tax_value = number_format((($tax_percentage / (100 + $tax_percentage)) * $price), 2);
                                        } else {
                                            $tax_value = number_format((($tax_percentage / 100) * $price), 2);
                                        }
                                        $cess_percentage = isset($value->cess_percentage) ? $value->cess_percentage : 0;
                                        if ($value->export_status == 2) {
                                            $tax_value = 0;
                                        } else {
                                            if ($entry->tax_or_advance == 1) {
                                                $tax_value = number_format((($tax_percentage / (100 + $tax_percentage)) * $price), 2);
                                            } else {
                                                $tax_value = number_format((($tax_percentage / 100) * $price), 2);
                                            }
                                        }
                                        if ($value->cess_status == 1) {
                                            $cess_value = number_format((($cess_percentage / 100) * $tax_value), 2);
                                        } else {
                                            $cess_value = 0;
                                        }
                                        ?>
                                        <td>  
                                            <span class="igst_percentage"><?php echo ($value->igst_status == 1) ? $tax_percentage : 0 ?></span>
                                        </td>
                                        <td>  
                                            <span class="igst_value"><?php echo ($value->igst_status == 1) ? $tax_value : 0 ?></span>
                                        </td>
                                        <td>
                                            <span class="sgst_percentage"><?php echo ($value->igst_status == 0) ? number_format($tax_percentage / 2, 2) : 0 ?></span>
                                        </td>
                                        <td>
                                            <span class="sgst_value"><?php echo ($value->igst_status == 0) ? number_format($tax_value / 2, 2) : 0 ?></span>
                                        </td>
                                        <td>
                                            <span class="cgst_percentage"><?php echo ($value->igst_status == 0) ? number_format($tax_percentage / 2, 2) : 0 ?></span>
                                        </td>
                                        <td>
                                            <span class="cgst_value"><?php echo ($value->igst_status == 0) ? number_format($tax_value / 2, 2) : 0 ?></span>
                                        </td>
                                        <td>   
                                            <span class="cess_percentage"><?php echo ($value->cess_status == 1) ? $cess_percentage : 0 ?></span>
                                        </td>
                                        <td>   
                                            <span class="cess_value"><?php echo ($value->cess_status == 1) ? $cess_value : 0 ?></span>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php else: ?>
                                <tr>   
                                    <td>
                                        <div class="form-group">   
                                            <select class="form-control" name="service_product[]" id="service_product">
                                                <option value="1">Service</option> 
                                                <option value="2">Product</option>
                                            </select>
                                        </div>  
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control search_item" placeholder="Select Service">
                                            <input type="hidden" class="tr_service_id" name="tr_service_id[]">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">  
                                            <input type="text" class="form-control service_amount" placeholder="Amount">
                                            <input type="hidden" name="tax_percentage[]" class="tax_percentage">
                                            <input type="hidden" name="cess_percentage[]" class="cess_percentage">
                                        </div>
                                    </td>
                                    <td>  
                                        <span class="igst_percentage">0</span>
                                    </td>
                                    <td>  
                                        <span class="igst_value">0</span>
                                    </td>
                                    <td>
                                        <span class="sgst_percentage">0</span>
                                    </td>
                                    <td>
                                        <span class="sgst_value">0</span>
                                    </td>
                                    <td>
                                        <span class="cgst_percentage">0</span>
                                    </td>
                                    <td>
                                        <span class="cgst_value">0</span>
                                    </td>
                                    <td>   
                                        <span class="cess_percentage">0</span>
                                    </td>
                                    <td>   
                                        <span class="cess_value">0</span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </form>
            </div>
            <div class="modal-footer">
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
            <?php if ($auto_date != '1'): ?>
            //$("#tr_date").val(sessionStorage.getItem('entry_date'));
            <?php endif ?>
            
            
    });

</script>