
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/account/css/act-main.css">
<style type="text/css">
    .ui-autocomplete{ z-index: 9999; }
    .errorMessage{color:red;}
    #serviceModal table{cellspacing:0;cellpadding:0}
    #serviceModal table tr th:first-child{text-align: left; width: 15%;}
    #serviceModal table tr th,td{text-align: center;}
    #serviceModal table tr th:nth-child(2) {
        text-align: left; width: 20%;
    }
    #serviceModal table tr th:nth-child(3) {
        text-align: left; width: 15%;
    }
</style>
<script>
    $(document).ready(function() {
        localStorage.clear();
    });
    var full_path = window.location.protocol + "//" + window.location.host + '/';</script>

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
            <div class="form-group">
                <label for="">Do you want to set tax 0 for different export country?</label>
                <div class="form-group">
                    <label class="radio-inline"><input type="radio" value="1" name="tax_status_country">Yes</label>
                    <label class="radio-inline"><input type="radio" value="0" name="tax_status_country" checked="true">No</label>

                </div>
            </div>

            <div class="form-group">
                <label for="">Do you want reverse entry with respect to branch?</label>
                <div class="form-group">
                    <label class="radio-inline"><input type="radio" value="1" name="select_branch_entry_no" class="branch-entry-no">Yes</label>
                    <label class="radio-inline"><input type="radio" value="0" name="select_branch_entry_no" checked="true" class="branch-entry-no">No</label>

                </div>
            </div>
            <?php if (isset($voucher_id) && ($voucher_id == 1 || $parent_id == 1 || $voucher_id == 2 || $parent_id == 2)): ?>
            <div class="form-group" style="<?php echo ($voucher_id == 2 || $parent_id == 2)?'display:none':'' ?>;">
                    <label for="">Do you want advance entry?</label>
                    <div class="form-group">
                        <label class="radio-inline"><input type="radio" value="1" name="advance_entry">Yes</label>
                        <label class="radio-inline"><input type="radio" value="0" name="advance_entry" checked="true">No</label>

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
            <div class="col-xs-5">
                <h1><i class="fa fa-list"></i><?php echo $voucher; ?></h1>
            </div>
            <div class="col-xs-7 text-right">
                <div class="btn-wrapper">
                    <!--<div class="btn-group btn-svg">
                        <a href="trans-receipt-list.php" class="btn btn-default" >Cancel</a>
                        <a href="trans-receipt-list.php" class="btn btn-success" >Save</a>
                    </div>-->
                    <button id="button" class="myButton btn btn-settings btn-svg pull-right"><i data-feather="settings"></i></button>
                </div>
            </div>
        </div>
    </section>
    <section>
        <?php
        $bread = isset($_GET['breadcrumbs']) ? $_GET['breadcrumbs'] : '';
        if ($bread == 'false') {
            $breadcrumbs = array(
                'Home' => '/admin/dashboard',
                "Add $voucher" => '/admin/transaction/add'
            );
            foreach ($breadcrumbs as $k => $b) {
                $this->breadcrumbs->push($k, $b);

                if ($k == 'Add') {
                    break;
                }
            }
            $this->breadcrumbs->show();

        } else {
            $prev_breadcrumbs = $this->session->userdata('_breadcrumbs');
            $current_breadcrumbs = array('Add' => '/admin/transaction/add');
            $breadcrumbs = array_merge($prev_breadcrumbs, $current_breadcrumbs);
            $this->session->set_userdata('_breadcrumbs', $breadcrumbs);
            foreach ($breadcrumbs as $k => $b) {
                $this->breadcrumbs->push($k, $b);

                if ($k == 'Add') {
                    break;
                }
            }
            $this->breadcrumbs->show();
        }

        ?>
    </section>
    <!-- Main content -->
    <section class="content">
        <form method="POST" action="<?php echo base_url(); ?>transaction/admin/ajax_save_form_data" class="formSubmitAll transaction-form" id="transaction-form">
            <div class="box">
                <div class="box-body">
                    <input type="hidden" class="in_ledger_state" name="in_ledger_state" value="">
                    <input type="hidden" class="in_ledger_country" name="in_ledger_country" value="">
                    <input type="hidden" class="expences_ledger_id" name="expences_ledger_id" value="">
                    <input type="hidden" class="advance_ledger_id" name="advance_ledger_id" value="">
                    <input type="hidden" class="tr_branch_id" name="tr_branch_id" value="">

                    <input type="hidden" class="company_type" name="company_type" value="<?php echo $this->session->userdata('company_type'); ?>">
                    <input type="hidden" class="is_inventory" name="is_inventory" value="<?php echo $this->session->userdata('is_inventory'); ?>">

                    <div class="row">
                        <div class="col-md-2">
                            <label class="mtb-10">Voucher Number</label>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?php
                                if ($auto_no_status == "1") {

                                    echo '<input type="text" class="form-control entry_no" value="Auto" readonly="readonly" autofocus placeholder="Enter entry no" name="entry_number" />';
                                } else {

                                    echo '<input type="text" class="form-control entry_no" autofocus placeholder="Enter entry no" name="entry_number" />';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <?php if ($recurring == 1): ?>
                                <input type="text" name="recurring_freq" class="form-control recurring" placeholder="Recurring Frequency">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-1">
                            <label class="mtb-10">Date</label>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <?php
                                    if ($auto_date == "1") {

                                        $date = date('d/m/Y');

                                        echo '<input class="form-control" type="text" id="tr_date" readonly="readonly" placeholder="DD/MM/YYYY" value="' . $date . '" name="date_form">';
                                    } else {
                                        echo '<input class="form-control" type="text" id="tr_date" placeholder="DD/MM/YYYY" name="date_form">';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($voucher_id) && ($voucher_id == 5 || $voucher_id == 6 || $parent_id == 5 || $parent_id == 6)): ?>
                        <div class="row">
                            <div class="col-md-2"> <label>Ref. No</label>  </div>
                            <div class="col-md-3"><input class="form-control" name="voucher_no" placeholder="Ref. No"></div>
                            <div class="col-md-2"></div>
                            <div class="col-md-2"><label>Ref. Date</label></div>
                            <div class="col-md-3"><input class="form-control" name="voucher_date" id="voucher_date" placeholder="Ref. Date"></div>
                        </div>
                        <br>
                    <?php endif; ?>

                    <div class="row" id="branch-div" style="display:none;">
                        <div class="col-md-3"><input class="form-control" name="branch_entry_no" placeholder="Branch Entry No" value=""></div>

                    </div>
                        <br>

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
                            <tr class="tr_row_ledger" id="1">
                                <td>
                                    <input type="text" class="form-control ui-autocomplete-input tr_ledger" name="tr_ledger[]" placeholder="Select Ledger">
                                    <input type="hidden" class="tr_ledger_id" name="tr_ledger_id[]">

                                </td>
                                <td><input type="text" class="form-control tr_type" name="tr_type[]" placeholder="Dr"></td>
                                <td><input type="text" class="form-control text-right tr_dr_amount" name="amount[]" autocomplete="off"></td>
                                <td><input type="text" class="form-control text-right tr_cr_amount" name="amount[]" autocomplete="off"></td>
                            </tr>
                            <tr class="tr_row_ledger" id="2">
                                <td>
                                    <input type="text" class="form-control ui-autocomplete-input tr_ledger" name="tr_ledger[]" placeholder="Select Ledger">
                                    <input type="hidden" class="tr_ledger_id" name="tr_ledger_id[]">
                                </td>
                                <td><input type="text" class="form-control tr_type" name="tr_type[]" placeholder="Dr"></td>
                                <td><input type="text" class="form-control text-right tr_dr_amount" name="amount[]" autocomplete="off"></td>
                                <td><input type="text" class="form-control text-right tr_cr_amount" name="amount[]" autocomplete="off"></td>
                            </tr>

                            <tr class="bg-bluel tr_total_row">
                                <td><label>Total</label></td>
                                <td></td>
                                <td><label class="tr_total_dr"></label></td>
                                <td><label class="tr_total_cr"></label></td>
                            </tr>
                            <tr class="bg-redl tr_difference_row" style="display: none;">
                                <td><label>Difference</label></td>
                                <td></td>
                                <td><label class="text-success tr_difference_dr"></label></td>
                                <td><label class="text-danger tr_difference_cr"></label></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="hidden">
                        <div class="row">
                            <label class="col-md-7"><div class="form-group"></div></label>
                            <label class="col-md-1"><div class="form-group"></div></label>
                            <label class="col-md-2"><div class="form-group"></div></label>
                            <label class="col-md-2"><div class="form-group"></div></label>
                            <div class="col-md-7">
                                <div class="form-group">

                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6  col-xs-3">
                                <div class="form-group">
                                    <label>Total</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-3 text-right">
                                <div class="form-group">
                                    <label>0.00</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-3 text-right">
                                <div class="form-group">
                                    <label>0.00</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-3 text-right">
                                <div class="form-group">
                                    <label>0.00</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-xs-3">
                                <div class="form-group">
                                    <label>Difference</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-3 text-right">
                                <div class="form-group">
                                    <label>0.00</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-3 text-right">
                                <div class="form-group">
                                    <label>0.00</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-3 text-right">
                                <div class="form-group">
                                    <label>0.00</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1 col-xs-2">
                            <div class="form-group">
                                <label>Naration</label>
                            </div>
                        </div>
                        <div class="col-md-11  col-xs-10">
                            <div class="form-group">
                                <input type="text" class="form-control tr_narration" name="tr_narration">
                            </div>
                        </div>
                    </div>

                    <div class="footer-button" style="display: none;">
                        <button type="submit" class="btn ladda-button transaction-submit-btn" data-color="blue"  data-size="s" id="totalFormSubmitBtn">Save</button>
                    </div>


                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </form>

    </section>
</div><!-- /.content-wrapper -->

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
                    <div class="form-group clone_div_bill" id="clone_div_bill" style="display:none">
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
                        <div class="s2-example">
                            <p>
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
                            </p>
                        </div>
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
                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-5">
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
                        <div class="col-md-1" style="margin-left:0px;padding-left: 0px;">
                            <label>&nbsp;</label>
                            <a href="<?php echo site_url('admin/add-accounts-groups'); ?>" target="_blank" class="btn btn-primary" data-toggle="tooltip" title="Add Group"><i class="fa fa-plus"></i></a></div>
                        <div class="col-md-6">
                            <div class="form-group input-block">
                                <label>Ledger Name</label>
                                <input type="text" class="form-control" id="ladger_name" name="ladger_name" placeholder="Ledger Name" autocomplete="off">
                                <span class="errorMessage"></span>
                            </div>
                        </div>

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

                <div class="modal-footer">
                    <button type="submit" class="btn ladda-button add-ledger-btn" data-color="blue" data-style="expand-right" data-size="s">Save</button>

                </div>

            </form>

        </div>



    </div>

</div>


<!-- Debit Modal -->
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

<!-- Banking Modal -->


<!-- Capture Bank Information Modal -->
<div id="bankModal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg" style="top: 70px; width: 1100px;">
        <!-- Modal content-->
        <div class="modal-content">
            <form class="form" role="form" id="tr_bank_details_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Enter Bank Information</h4>
                    <input type="hidden" name="tr_banking_ledger_id_hidden" value="" id="tr_banking_ledger_id_hidden">


                </div>
                <div class="modal-body">

                    <div id="banking_container">
                        <div class="row tr_banking_row" id="1">
                            <div class="col-md-2" style="padding: 0 2px;">
                                <div class="form-group">
                                    <input type="text" class="form-control tr_banking_transaction_type" placeholder="Transaction Type" name="tr_banking_transaction_type[]">
                                    <input type="hidden" class="tr_transaction_id_modal" name="tr_transaction_id_modal[]">
                                </div>
                            </div>
                            <div class="col-md-1 min-padding" style="padding: 0 2px;">
                                <div class="form-group">
                                    <input type="text" class="form-control tr_banking_instrument_no" placeholder="Ins No" name="instrument_no[]">
                                </div>
                            </div>
                            <div class="col-md-1 min-padding" style="padding: 0 2px;">
                                <div class="form-group">
                                    <input type="text" class="form-control tr_banking_instrument_date" placeholder="Date" name="tr_banking_instrument_date[]">
                                </div>
                            </div>
                            <div class="col-md-2" style="padding: 0 2px;">
                                <div class="form-group">
                                    <input type="text" class="form-control tr_banking_bank_name" placeholder="Bank Name" name="tr_banking_bank_name[]">
                                </div>
                            </div>
                            <div class="col-md-2" style="padding: 0 2px;">
                                <div class="form-group">
                                    <input type="text" class="form-control tr_banking_branch_name" placeholder="Branch Name" name="tr_banking_branch_name[]">
                                </div>
                            </div>
                            <div class="col-md-2" style="padding: 0 2px;">
                                <div class="form-group">
                                    <input type="text" class="form-control tr_banking_ifsc_code" placeholder="IFSC Code" name="tr_banking_ifsc_code[]">
                                </div>
                            </div>
                            <div class="col-md-2" style="padding: 0 2px;">
                                <div class="form-group">
                                    <input type="text" class="form-control tr_banking_bank_amount text-right" placeholder="Amount" name="tr_banking_bank_amount[]">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-xs-10 text-right"><label>Total</label></div>
                        <div class="col-xs-2" style="padding: 0 2px;">
                            <input type="text" class="form-control text-right" id="tr_banking_amount_total" readonly="readonly">
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Capture Bank Information Modal -->
<div id="billingModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="top: 70px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="tr_bill_ledger_name"> </h4>
                <input type="hidden" id="tr_bill_ledger_id_hidden" value="">
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
                    <div id="billing_body">
                        <div class="form-group tr_bill_row" id="1">
                            <div class="row">

                                <div class="col-xs-2">
                                    <input type="text" class="form-control tr_ref_bill_type" readonly="readonly">
                                </div>

                                <div class="col-xs-3">
                                    <input type="text" class="form-control tr_bill_name " id="">
                                    <input type="hidden" class="form-control tr_bill_id_hidden">
                                </div>
                                <div class="col-xs-2"><input type="number" class="form-control tr_bill_credit_day" readonly="readonly" id=""></div>
                                <div class="col-xs-2"><input type="text" class="form-control tr_bill_credit_date" id="bill_credit_date" readonly="readonly" ></div>
                                <div class="col-xs-2"><input type="text" class="form-control tr_bill_amount" id=""></div>
                                <div class="col-xs-1"><input type="text" class="form-control tr_bill_account_type" id="" readonly="readonly"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="row">

                    <div class="col-xs-8 text-right"><label>Total</label></div>
                    <div class="col-xs-3"><input type="text" class="form-control" id="tr_bill_total_bill"  readonly="readonly"></div>
                    <div class="col-xs-1 "><input type="text" class="form-control" id="tr_bill_cr_dr_cal"  readonly="readonly"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Service Modal -->
<div class="modal fade" id="serviceModal" role="dialog">
    <div class="modal-dialog modal-lg" style="top: 70px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">GST</h4>
            </div>
            <div class="modal-body">
                <form id="service_modal_form">
                    <input type="hidden" name="tax_or_advance" class="tax_or_advance" value="">
                    <input type="hidden" name="igst_status" class="igst_status" id="igst_status" value="">
                    <input type="hidden" name="cess_status" class="cess_status" id="cess_status" value="">
                    <input type="hidden" name="export_status" class="export_status" id="export_status" value="">
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
                                        <input type="text" class="form-control service_amount" name="service_amount[]" placeholder="Amount">
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
                        </tbody>

                    </table>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>

    </div>
</div>
<script src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/jquery.inputmask.bundle.min.js"></script>
<script>
                                    var ledgerData = [];
                                    var LS_tracking = [];
                                    var LS_banking = [];
                                    var LS_billing = [];
                                    var LS_tracking_get = [];
                                    var LS_banking_get = [];
                                    var LS_billing_get = [];
                                    var debtorsArray = [];
                                    var creditorsArray = [];
                                    var salesArray = [];
                                    var purchaseArray = [];
                                    var cashBankArray = [];
                                    $(document).ready(function() {
//init transaction date
                                        $("#voucher_date").inputmask("d/m/y", {placeholder: "dd/mm/yyyy", "oncomplete": function() {

                                            }});
                                        /* get debtors array */

                                        var ajaxDebtorsArr = {action: 'debtors_array'};
                                        $.ajax({
                                            type: "POST",
                                            url: '<?php echo base_url(); ?>transaction/admin/ajaxGetDebtorsArray',
                                            data: ajaxDebtorsArr,
                                            dataType: "json",
                                            success: function(DATA) {
                                                //console.log(DATA.sundry_debtors_ledger_final);

                                                for (var i = 0; i < DATA['sundry_debtors_ledger_final'].length; i++) {
                                                    debtorsArray.push(DATA['sundry_debtors_ledger_final'][i]['id']);
                                                }
                                            },
                                            error: function(request, error) {
                                                // alert('connection error. please try again.');
                                            }
                                        });
                                        //cash bank
                                        var ajaxCashBankArr = {action: 'cash_bank_array'};
                                        $.ajax({
                                            type: "POST",
                                            url: '<?php echo base_url(); ?>transaction/admin/ajaxGetCashBankArray',
                                            data: ajaxCashBankArr,
                                            dataType: "json",
                                            success: function(DATA) {
                                                //console.log(DATA.sundry_debtors_ledger_final);

                                                for (var i = 0; i < DATA['cash_bank_ledger'].length; i++) {
                                                    cashBankArray.push(DATA['cash_bank_ledger'][i]['id']);
                                                }
                                            },
                                            error: function(request, error) {
                                                // alert('connection error. please try again.');
                                            }
                                        });
                                        var ajaxDebtorsArr = {action: 'creditors_array'};
                                        $.ajax({
                                            type: "POST",
                                            url: '<?php echo base_url(); ?>transaction/admin/ajaxGetCreditorsArray',
                                            data: ajaxDebtorsArr,
                                            dataType: "json",
                                            success: function(DATA) {
                                                //console.log(DATA.sundry_creditors_ledger_final);

                                                for (var i = 0; i < DATA['sundry_creditors_ledger_final'].length; i++) {
                                                    creditorsArray.push(DATA['sundry_creditors_ledger_final'][i]['id']);
                                                }
                                            },
                                            error: function(request, error) {
                                                // alert('connection error. please try again.');
                                            }
                                        });
                                        var ajaxPurchaseArr = {action: 'purchase_array'};
                                        $.ajax({
                                            type: "POST",
                                            url: '<?php echo base_url(); ?>transaction/admin/ajaxGetPurchaseArray',
                                            data: ajaxPurchaseArr,
                                            dataType: "json",
                                            success: function(DATA) {
                                                //console.log(DATA.sundry_creditors_ledger_final);

                                                for (var i = 0; i < DATA['purchase_ledger_final'].length; i++) {
                                                    purchaseArray.push(DATA['purchase_ledger_final'][i]['id']);
                                                }
                                            },
                                            error: function(request, error) {
                                                // alert('connection error. please try again.');
                                            }
                                        });
                                        var ajaxSalesArr = {action: 'sales_array'};
                                        $.ajax({
                                            type: "POST",
                                            url: '<?php echo base_url(); ?>transaction/admin/ajaxGetSalesArray',
                                            data: ajaxSalesArr,
                                            dataType: "json",
                                            success: function(DATA) {
                                                //console.log(DATA.sundry_creditors_ledger_final);

                                                for (var i = 0; i < DATA['sales_ledger_final'].length; i++) {
                                                    salesArray.push(DATA['sales_ledger_final'][i]['id']);
                                                }
                                            },
                                            error: function(request, error) {
                                                // alert('connection error. please try again.');
                                            }
                                        });
                                    });
                                    //voucher date input
                                    $('#voucher_date').bind('keyup', 'keydown', function(e) {
                                        $.post("<?php echo base_url(); ?>api-get-date-by-finance-year", {date: e.target.value}, function(data) {
                                            if (data.res) {
                                                $("#voucher_date").val(data.date);
                                            }
                                        }, "json");
                                    })
</script>

<script>
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
    });</script>
<script>
    $("#add_ledger_form_te").submit(function(event) {
        event.preventDefault();
        var l = Ladda.create(document.querySelector('.add-ledger-btn'));
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
                    $('#ladger_name, #opening_balance, #credit_date, #credit_limit').val("");
                    $('#addLedger').modal('toggle');
                }
            }
        });
    });</script>
<script>

    function get_ledger(ledger, index) {
        $('#tracking_ledger_name').text(ledger);
        $('#bill_ledger_name').text(ledger);
        var url = base_url + 'index.php/accounts/entries/get_ledger_id';
        var queryString = "ledger=" + ledger + '&ajax=1';
        $.ajax({
            type: "POST",
            url: url,
            data: queryString,
            dataType: "json",
            success: function(DATA) {
                if (DATA.SUCESS) {
                    $('#tracking_ledger_id').val(DATA.MENU.id);
                    $('#bill_ledger_id').val(DATA.MENU.id);
                    $('#ledger_id_' + index).val(DATA.MENU.id);
                    $('#ledger_id_' + index).closest('.ledger-block').find('.amount').attr('data-id', DATA.MENU.id);
                    if (DATA.MENU.tracking_status == '1') {
                        $('#amount_d_' + index).attr('class', 'form-control amount txt_dr txt_tracking text-input');
                        $('#amount_c_' + index).attr('class', 'form-control txt_cr amount txt_tracking validate[required,custom[integer]] text-input');
                        modal_tracking_bind();
                    } else if (DATA.MENU.bill_details_status == '1') {
                        $('#amount_d_' + index).attr('class', 'form-control txt_dr amount txt_billing text-input');
                        $('#amount_c_' + index).attr('class', 'form-control txt_cr amount txt_billing validate[required,custom[integer]] text-input');
                        modal_billing_bind();
                    }
                    check_for_bank_details(index, DATA.MENU.id)
                    get_current_balance(index, DATA.MENU.id);
                    //modal_tracking_bind();
                }
            },
            error: function(request, error) {
                alert('connection error. please try again.');
            }
        });
    }

</script>
<script>
    $("input[name='bill_details_status']").click(function() {
        var val = $(this).val();
        if (parseInt(val) == 1) {
            $(".credit_limit_div").show();
        } else {
            $(".credit_limit_div").hide();
        }
    });</script>


<script>
    $(document).ready(function() {
        $('.entry_date').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
    });</script>
<script>
    $(".myButton").click(function() {

        // Set the effect type
        var effect = 'slide';
        // Set the options for the effect type chosen
        var options = {direction: $('.mySelect').val(0)};
        // Set the duration (default: 400 milliseconds)
        var duration = 500;
        $('#myDiv').toggle(effect, options, duration);
    });</script>
<script>
    var trackingArray = [];
    // var transactionArray = [];
    var referenceArray = [];
    var transaction_type_id = <?php echo $transaction_type_id; ?>;
    var parent_id = <?php echo $parent_id; ?>;
    // blahblah

    $(function() {


        $('body').delegate('.tr_ledger', 'focusin', function() {

            $(this).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>transaction/admin/getLedgerDetails',
                        data: "ledger=" + request.term + '&trans_type=' + transaction_type_id + '&ajax=1',
                        dataType: "json",
                        success: function(data) {
                            response(data);
                            //console.log(data);
                        },
                        error: function(request, error) {
                            //alert('connection error. please try again.');
                        }
                    });
                },
                minLength: 0,
                select: function(e, ui) {
                    var self = $(this);
                    e.preventDefault(); // <--- Prevent the value from being inserted.
                    $(this).val(ui.item.label); // display the selected text
                    $(this).next(".tr_ledger_id").val(ui.item.value); // save selected id to hidden input

                    var dataLType = {'action': 'get-ledger-extra-details', ledgerId: ui.item.value};
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>transaction/admin/getLedgerExtraDetails',
                        data: dataLType,
                        dataType: "json",
                        success: function(data) {
                            if (data.isCreditorDebitorGroup.res) {
                                self.closest('tr').find('.tr_type').attr('ledger-type', 'debitors');
                                $('.in_ledger_state').val(data.isCreditorDebitorGroup.state);
                                $('.in_ledger_country').val(data.isCreditorDebitorGroup.country);

                            }

                            if (data.branch_id && data.branch_id != 0) {
                                $('.tr_branch_id').val(data.branch_id);
                            }
                            if (data.isExpencesGroup == 'yes') {
                                $('.expences_ledger_id').val(ui.item.value);
                            }
                            // var data = JSON.parse(data);

//                            self.closest('tr').find(".tr_type").val($.trim(data.transactionType));
                            /* Check for popup conditions  */

                            var ledgeId = self.next(".tr_ledger_id").val();
                            if (transaction_type_id == 1 || parent_id == 1) {

                                /*  condition for receipt */

                                debtorsArray.forEach(function(val) {
                                    if (val == ledgeId) {
                                        self.closest('tr').find(".tr_type").val($.trim("Cr"));
                                        data.transactionType = "Cr";
                                    }
                                })


                            } else if (transaction_type_id == 2 || parent_id == 2) {

                                /* condition for payment */
                                creditorsArray.forEach(function(val) {
                                    if (val == ledgeId) {
                                        self.closest('tr').find(".tr_type").val($.trim("Dr"));
                                        data.transactionType = "Dr";
                                    }
                                })

                                //      condition for cash bank payment

                                cashBankArray.forEach(function(val) {
                                    if (val == ledgeId) {
                                        self.closest('tr').find(".tr_type").val($.trim("Cr"));
                                        data.transactionType = "Cr";
                                    }
                                })

                            } else if (transaction_type_id == 3 || parent_id == 3) {
                                /* condition for contra. 1st value would be credit */

                                if (self.closest('tr').attr('id') == "1") {
                                    self.closest('tr').find(".tr_type").val($.trim("Cr"));
                                    data.transactionType = "Cr";
                                }


                            }







                            var totalDr = 0;
                            var totalCr = 0;
                            var diff = 0;
                            setTimeout(function() {

                                calculateDifference(totalDr, totalCr, function(diff) {

                                    if (diff < 0) {

                                        if ($.trim(data.transactionType) == "Dr") {

                                            diff = Math.abs(diff);
                                            $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val(diff);
                                            $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val("");
                                            $('tr[class="tr_row_ledger"]:last .tr_cr_amount').prop('disabled', true);
                                            $('tr[class="tr_row_ledger"]:last .tr_dr_amount').prop('disabled', false);
                                            $('tr[class="tr_row_ledger"]:last .tr_type').val("Dr");
                                            $('.footer-button').hide();
                                        }

                                    } else if (diff > 0) {

                                        if ($.trim(data.transactionType) == "Cr") {

                                            $('.tr_difference_cr').html(diff);
                                            $('.tr_difference_dr').html(0);
                                            $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val(diff);
                                            $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val("");
                                            $('tr[class="tr_row_ledger"]:last .tr_dr_amount').prop('disabled', true);
                                            $('tr[class="tr_row_ledger"]:last .tr_cr_amount').prop('disabled', false);
                                            $('tr[class="tr_row_ledger"]:last .tr_type').val("Cr");
                                            $('.footer-button').hide();
                                        }
                                    }

                                    calculateDifference(totalDr, totalCr, function(diff) {

                                    });
                                });
                            }, 500);

                            if ($.trim(data.transactionType) == "Cr") {
                                self.closest('tr').find(".tr_dr_amount").val("");
                                self.closest('tr').find(".tr_dr_amount").prop('disabled', true);
                                self.closest('tr').find(".tr_cr_amount").prop('disabled', false);
                            } else if ($.trim(data.transactionType) == "Dr") {
                                self.closest('tr').find(".tr_cr_amount").val("");
                                self.closest('tr').find(".tr_cr_amount").prop('disabled', true);
                                self.closest('tr').find(".tr_dr_amount").prop('disabled', false);
                            }

                            // console.log(self);
                        },
                        error: function(request, error) {
                            //alert('connection error. please try again.');

                        }
                    });
                }
            }).focus(function() {
                $(this).autocomplete("search", "");
            });
        });
        /*  Tracking modal  */

        $('#trackingModal').delegate('.tr_tracking_name_modal', 'focusin', function() {
            $(this).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>transaction/admin/getTrackingName',
                        data: "tracking=" + request.term + '&trackingArr=' + JSON.stringify(trackingArray) + '&ajax=1',
                        dataType: "json",
                        success: function(data) {
                            response(data);
                            //console.log(data);
                        },
                        error: function(request, error) {
                            //alert('connection error. please try again.');
                        }
                    });
                },
                minLength: 0,
                select: function(e, ui) {
                    var self = $(this);
                    e.preventDefault(); // <--- Prevent the value from being inserted.
                    $(this).val(ui.item.label); // display the selected text
                    $(this).next(".tr_tracking_id_modal").val(ui.item.value); // save selected id to hidden input

                    var found = jQuery.inArray(ui.item.value, trackingArray);
                    if (found >= 0) {
                        // Element was found, remove it.
                        trackingArray.splice(ui.item.value, 1);
                    } else {
                        // Element was not found, add it.
                        trackingArray.push(ui.item.value);
                    }


                }
            }).focus(function() {
                $(this).autocomplete("search", "");
            });
        });
        /* Banking Modal */

        $('#bankModal').delegate('.tr_banking_transaction_type', 'focusin', function() {

            $(this).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>transaction/admin/getTransactionTypes',
                        data: "transaction=" + request.term + '&ajax=1',
                        dataType: "json",
                        success: function(data) {
                            response(data);
                            //console.log(data);
                        },
                        error: function(request, error) {
                            //alert('connection error. please try again.');
                        }
                    });
                },
                minLength: 0,
                select: function(e, ui) {
                    var self = $(this);
                    e.preventDefault(); // <--- Prevent the value from being inserted.
                    $(this).val(ui.item.label); // display the selected text
                    $(this).next(".tr_transaction_id_modal").val(ui.item.value); // save selected id to hidden input

                }
            }).focus(function() {
                $(this).autocomplete("search", "");
            });
        });
        $("#billingModal").delegate('.tr_bill_name', 'focusin', function() {
            $(this).autocomplete({
                source: function(request, response) {

                    var ledger_id = $('#billingModal #tr_bill_ledger_id_hidden').val();
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>transaction/admin/getBillByReferenceLedgerId',
                        data: "bill_name=" + request.term + "&ajax=1&ledger_id=" + ledger_id + "&total_bill_array=" + JSON.stringify(referenceArray) +"&transaction_type_id="+transaction_type_id+"&parent_id="+parent_id,
                        dataType: "json",
                        success: function(data) {
                            response(data);
                        },
                        error: function(request, error) {

                        }
                    });
                },
                minLength: 0,
                select: function(e, ui) {
                    var self = $(this);
                    e.preventDefault();
                    var billGot = ui.item.label;
                    var billNameRegenerated = $.trim(billGot.substring(0, billGot.indexOf(':')));
                    $(this).val(billNameRegenerated);
                    var found = jQuery.inArray(ui.item.value, referenceArray);
                    if (found >= 0) {
                        // Element was found, remove it.
                        referenceArray.splice(ui.item.value, 1);
                    } else {
                        // Element was not found, add it.
                        referenceArray.push(ui.item.value);
                    }


                    var ledgerId = $('#billingModal #tr_bill_ledger_id_hidden').val();
                    var url = '<?php echo base_url(); ?>transaction/admin/getBillByBillnameLedgerId';
                    var queryString = "bill_name=" + billNameRegenerated + "&ledger_id=" + ledgerId + '&ajax=1';
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: queryString,
                        dataType: "json",
                        success: function(DATA) {

                            if (DATA.SUCCESS) {
                                var dateAr = DATA.MENU.credit_date.split('-');
                                var newDate = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];

                                $(self).closest('.tr_bill_row').find(".tr_bill_credit_day").val(DATA.MENU.credit_days);
                                $(self).closest('.tr_bill_row').find(".tr_bill_credit_date").val(newDate);
                            }
                        },
                        error: function(request, error) {
                            // alert('connection error. please try again.');

                        }
                    });
                }
            }).focus(function() {
                $(this).autocomplete("search");
            });
        });
    });
    $(function() {

        $(".tr_transaction_main").delegate(".tr_type", "keyup", function(e) {
            if (e.keyCode == "68") {
                var self = $(this);
                $(this).val("Dr");
                $(this).closest('tr').find(".tr_cr_amount").val("");
                $(this).closest('tr').find(".tr_cr_amount").prop('disabled', true);
                $(this).closest('tr').find(".tr_dr_amount").prop('disabled', false);
                setTimeout(function() {

                    var totalCr = 0;
                    var totalDr = 0;
                    var diff = 0;
                    calculateDifference(totalDr, totalCr, function(diff) {

                        if (diff < 0) {

                            if (self.val() == "Dr") {

                                diff = Math.abs(diff);
                                $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val(diff);
                                $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val("");
                                $('tr[class="tr_row_ledger"]:last .tr_cr_amount').prop('disabled', true);
                                $('tr[class="tr_row_ledger"]:last .tr_dr_amount').prop('disabled', false);
                                $('tr[class="tr_row_ledger"]:last .tr_type').val("Dr");
                                $('.footer-button').hide();
                            }

                        } else if (diff > 0) {

                            if (self.val() == "Cr") {

                                $('.tr_difference_cr').html(diff);
                                $('.tr_difference_dr').html(0);
                                $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val(diff);
                                $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val("");
                                $('tr[class="tr_row_ledger"]:last .tr_dr_amount').prop('disabled', true);
                                $('tr[class="tr_row_ledger"]:last .tr_cr_amount').prop('disabled', false);
                                $('tr[class="tr_row_ledger"]:last .tr_type').val("Cr");
                                $('.footer-button').hide();
                            }
                        }

                        // calculateDifference(totalDr, totalCr, function(diff){
                        //     console.log("total value updated!");
                        // });

                        if (totalDr != 0) {
                            $('.tr_total_row .tr_total_dr').html(totalDr);
                        }

                        if (totalCr != 0) {
                            $('.tr_total_row .tr_total_cr').html(totalCr);
                        }

                    });
                }, 2000);
            } else if (e.keyCode == "67") {
                var self = $(this);
                $(this).val("Cr");
                $(this).closest('tr').find(".tr_dr_amount").val("");
                $(this).closest('tr').find(".tr_dr_amount").prop('disabled', true);
                $(this).closest('tr').find(".tr_cr_amount").prop('disabled', false);
                setTimeout(function() {

                    var totalCr = 0;
                    var totalDr = 0;
                    var diff = 0;
                    calculateDifference(totalDr, totalCr, function(diff) {

                        if (diff < 0) {

                            if (self.val() == "Dr") {

                                diff = Math.abs(diff);
                                $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val(diff);
                                $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val("");
                                $('tr[class="tr_row_ledger"]:last .tr_cr_amount').prop('disabled', true);
                                $('tr[class="tr_row_ledger"]:last .tr_dr_amount').prop('disabled', false);
                                $('tr[class="tr_row_ledger"]:last .tr_type').val("Dr");
                                $('.footer-button').hide();
                            }

                        } else if (diff > 0) {

                            if (self.val() == "Cr") {

                                $('.tr_difference_cr').html(diff);
                                $('.tr_difference_dr').html(0);
                                $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val(diff);
                                $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val("");
                                $('tr[class="tr_row_ledger"]:last .tr_dr_amount').prop('disabled', true);
                                $('tr[class="tr_row_ledger"]:last .tr_cr_amount').prop('disabled', false);
                                $('tr[class="tr_row_ledger"]:last .tr_type').val("Cr");
                                $('.footer-button').hide();
                            }
                        }

                        // calculateDifference(totalDr, totalCr, function(diff){
                        //     console.log("total value updated!");
                        // });

                        if (totalDr != 0) {
                            $('.tr_total_row .tr_total_dr').html(totalDr);
                        }

                        if (totalCr != 0) {
                            $('.tr_total_row .tr_total_cr').html(totalCr);
                        }


                    });
                }, 2000);
            } else {
                if ($(this).val() == "Cr" || $(this).val() == "Dr") {
                    return;
                } else {
                    $(this).val("");
                }
            }
        });
        $('body').delegate('.tr_cr_amount, .tr_dr_amount', 'blur', function() {


            /* Calculate Credit Debit Balance */

            var totalDr = 0;
            var totalCr = 0;
            var diff = 0;
            var self = $(this);
            calculateDifference(totalDr, totalCr, function(diff) {


                if ($('.tr_row_ledger').length == 2) {
                    if (self.closest('tr').attr('id') == "1") {
                        $('.tr_difference_row').hide();
                        if (diff < 0) {

                            diff = Math.abs(diff);
                            $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val(diff);
                            $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val("");
                            $('tr[class="tr_row_ledger"]:last .tr_cr_amount').prop('disabled', true);
                            $('tr[class="tr_row_ledger"]:last .tr_dr_amount').prop('disabled', false);
                            $('tr[class="tr_row_ledger"]:last .tr_type').val("Dr");
                            $('.footer-button').hide();
                        } else if (diff > 0) {

                            $('.tr_difference_cr').html(diff);
                            $('.tr_difference_dr').html(0);
                            $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val(diff);
                            $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val("");
                            $('tr[class="tr_row_ledger"]:last .tr_dr_amount').prop('disabled', true);
                            $('tr[class="tr_row_ledger"]:last .tr_cr_amount').prop('disabled', false);
                            $('tr[class="tr_row_ledger"]:last .tr_type').val("Cr");
                            $('.footer-button').hide();
                        }

                        calculateDifference(totalDr, totalCr, function(diff) {

                        });
                        return;
                    } else {

                        if (diff == 0) {
                            //This condition is used for inventory status and company type status
                            if($('.is_inventory').val() == 1 && $('.company_type').val() == 1){
                                if (parseInt($('.expences_ledger_id').val())) {
                                    $('.tax_or_advance').val(0);
                                    $('#serviceModal').addClass('service-entry');
                                    $('#serviceModal').find('.modal-title').html('GST');
                                    $("#serviceModal").modal('show');
                                }
                            }
                            $('.footer-button').show();
                            return;
                        } else {


                            var con1 = $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val();
                            var con2 = $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val();
                            con1 = parseInt(con1);

                            if (parseInt(con1) != 0 || parseInt(con2) != 0) {

                                // $('.tr_difference_row').show();

                                if ($.trim(self.val()) > 0 || $.trim(self.val()) != "") {

                                    generateNewRow(diff, function() {

                                        $('tr[class="tr_row_ledger"]:last .tr_ledger').val('');
                                        $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val('');
                                        $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val('');
                                        $('tr[class="tr_row_ledger"]:last .tr_type').val('');
                                        if (diff < 0) {

                                            diff = Math.abs(diff);
                                            $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val(diff);
                                            $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val("");
                                            $('tr[class="tr_row_ledger"]:last .tr_cr_amount').prop('disabled', true);
                                            $('tr[class="tr_row_ledger"]:last .tr_dr_amount').prop('disabled', false);
                                            $('tr[class="tr_row_ledger"]:last .tr_type').val("Dr");
                                            $('.footer-button').hide();
                                        } else if (diff > 0) {

                                            $('.tr_difference_cr').html(diff);
                                            $('.tr_difference_dr').html(0);
                                            $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val(diff);
                                            $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val("");
                                            $('tr[class="tr_row_ledger"]:last .tr_dr_amount').prop('disabled', true);
                                            $('tr[class="tr_row_ledger"]:last .tr_cr_amount').prop('disabled', false);
                                            $('tr[class="tr_row_ledger"]:last .tr_type').val("Cr");
                                            $('.footer-button').hide();
                                        }


                                        calculateDifference(totalDr, totalCr, function(diff) {

                                        });
                                    });
                                }


                            }

                        }


                    }



                } else if ($('.tr_row_ledger').length > 2) {


                    if (diff == 0) {

                        if (parseInt($('.expences_ledger_id').val())) {
                            $('.tax_or_advance').val(0);
                            $('#serviceModal').addClass('service-entry');
                            $('#serviceModal').find('.modal-title').html('GST');
                            $("#serviceModal").modal('show');
                        }

                        $('.footer-button').show();
                        return;
                    } else {

                        var con1 = $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val();
                        var con2 = $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val();
                        con1 = parseInt(con1);

                        if (parseInt(con1) != 0 || parseInt(con2) != 0) {


                            // $('.tr_difference_row').show();

                            if ($.trim(self.val()) > 0 || $.trim(self.val()) != "") {


                                generateNewRow(diff, function() {

                                    $('tr[class="tr_row_ledger"]:last .tr_ledger').val('');
                                    $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val('');
                                    $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val('');
                                    $('tr[class="tr_row_ledger"]:last .tr_type').val('');
                                    if (diff < 0) {

                                        diff = Math.abs(diff);
                                        $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val(diff);
                                        $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val("");
                                        $('tr[class="tr_row_ledger"]:last .tr_cr_amount').prop('disabled', true);
                                        $('tr[class="tr_row_ledger"]:last .tr_dr_amount').prop('disabled', false);
                                        $('tr[class="tr_row_ledger"]:last .tr_type').val("Dr");
                                        $('.footer-button').hide();
                                    } else if (diff > 0) {

                                        $('.tr_difference_cr').html(diff);
                                        $('.tr_difference_dr').html(0);
                                        $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val(diff);
                                        $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val("");
                                        $('tr[class="tr_row_ledger"]:last .tr_dr_amount').prop('disabled', true);
                                        $('tr[class="tr_row_ledger"]:last .tr_cr_amount').prop('disabled', false);
                                        $('tr[class="tr_row_ledger"]:last .tr_type').val("Cr");
                                        $('.footer-button').hide();
                                    }

                                    calculateDifference(totalDr, totalCr, function(diff) {

                                    });
                                });
                            }


                        }

                    }




                }

                calculateDifference(totalDr, totalCr, function(diff) {

                });
            });
        });
    });
    function generateNewRow(diff, callback) {

        // get the last DIV which ID starts with ^= "klon"
        var $trNum = $('tr[class="tr_row_ledger"]:last').attr('id');
        var num = parseInt($trNum) + 1;
        var $tr = $('tr[class="tr_row_ledger"]:last');
        var $klon = $tr.clone().prop('id', num);
        // Finally insert $klon wherever you want


        $tr.after($klon);
        setTimeout(function() {
            $('tr[class="tr_row_ledger"]:last .tr_ledger').focus();
            callback();
        }, 500);
    }


    function generateNewRowTrackingModal(diff, callback) {

        var $trNum = $('.tr_tracking_row:last').attr('id');
        var num = parseInt($trNum) + 1;
        var $tr = $('.tr_tracking_row:last').closest('.form-group');
        var $klon = $tr.clone();
        // Finally insert $klon wherever you want


        $tr.after($klon);
        setTimeout(function() {
            $('.tr_tracking_row:last').prop('id', num);
            $('.tr_tracking_row:last .tr_tracking_name_modal').val("");
            $('.tr_tracking_row:last .tr_tracking_name_modal').focus();
            callback();
        }, 500);
    }


    function generateNewRowBankingModal(diff, callback) {

        var $trNum = $('.tr_banking_row:last').attr('id');
        var num = parseInt($trNum) + 1;
        var $tr = $('.tr_banking_row:last').prop('id', num);
        var $klon = $tr.clone();
        // Finally insert $klon wherever you want


        $tr.after($klon);
        setTimeout(function() {
            // $('.tr_tracking_row:last').prop('id', num);
            $('.tr_banking_row:last .tr_banking_transaction_type').val("");
            $('.tr_banking_row:last .tr_banking_instrument_no').val("");
            $('.tr_banking_row:last .tr_banking_instrument_date').val("");
            $('.tr_banking_row:last .tr_banking_bank_name').val("");
            $('.tr_banking_row:last .tr_banking_branch_name').val("");
            $('.tr_banking_row:last .tr_banking_ifsc_code').val("");
            $('.tr_banking_row:last .tr_banking_bank_amount').val("");
            $('.tr_banking_row:last .tr_banking_transaction_type').focus();
            callback();
        }, 500);
    }

    function generateNewRowBillModal(diff, callback) {

        var $trNum = $('.tr_bill_row:last').attr('id'); //$('.tr_bill_row:last .tr_bill_amount').val(diff);
        var num = parseInt($trNum) + 1;
        var $tr = $('.tr_bill_row:last').prop('id', num);
        var $klon = $tr.clone();
        // Finally insert $klon wherever you want


        $tr.after($klon);
        setTimeout(function() {
            // $('.tr_tracking_row:last').prop('id', num);
            $('.tr_bill_row:last .tr_bill_name').val("");
            $('.tr_bill_row:last .tr_bill_credit_day').val("");
            $('.tr_bill_row:last .tr_bill_credit_date').val("");
            $('.tr_bill_row:last .tr_bill_amount').val("");
            $('.tr_bill_row:last .tr_bill_name').focus();
            callback();
        }, 500);
    }

    function calculateDifference(totalDr, totalCr, callback) {

        $('.tr_row_ledger').each(function() {

            if ($(this).find(".tr_dr_amount").val() != "") {
                //$(this).find(".tr_dr_amount").val(0.00);
                totalDr = totalDr + parseFloat($(this).find(".tr_dr_amount").val());
                totalDr = parseFloat(parseFloat(totalDr).toFixed(2));
            }

            if ($(this).find(".tr_cr_amount").val() != "") {
                //$(this).find(".tr_cr_amount").val(0.00);
                totalCr = totalCr + parseFloat($(this).find(".tr_cr_amount").val());
                totalCr = parseFloat(parseFloat(totalCr).toFixed(2));
            }



            //totalDr = totalDr + parseFloat($(this).find(".tr_dr_amount").val());
            //totalCr = totalCr + parseFloat($(this).find(".tr_cr_amount").val());

        });
        diff = totalDr - totalCr;
        if (totalDr != 0) {
            $('.tr_total_row .tr_total_dr').html(totalDr);
        }

        if (totalCr != 0) {
            $('.tr_total_row .tr_total_cr').html(totalCr);
        }



        callback(diff);
    }




    // register jQuery extension
    jQuery.extend(jQuery.expr[':'], {
        focusable: function(el, index, selector) {
            return $(el).is('a, button, :input, [tabindex]');
        }
    });
    $(document).on('keypress', 'input,select', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            var self = $(this);
            // Get all focusable elements on the page
            if (self.hasClass('tr_dr_amount') || self.hasClass('tr_cr_amount')) {

                var ledgerId = self.closest('.tr_row_ledger').find('.tr_ledger_id').val();
                var ledgerAccType = self.closest('.tr_row_ledger').find('.tr_type').val();
                var dataLType = {'action': 'get-ledger-extra-details', ledgerId: ledgerId};
                // console.log("dataLtype ========= ", dataLType);

                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>transaction/admin/getLedgerExtraDetails',
                    data: dataLType,
                    dataType: "json",
                    success: function(data) {

                        if (data.branch_id && data.branch_id != 0) {
                            $('.tr_branch_id').val(data.branch_id);
                        }

                        var ledgerAmt = self.val();
                        if (data.hasTracking == "1") {
                            $('#trackingModal').modal('show');
                            $('#trackingModal .tr_tracking_row:first .tr_tracking_name_modal').focus();
                            $('#trackingModal #tr_tracking_ledger_name').html(data.ledgerName);
                            $('#trackingModal #tr_tracking_ledger_id_hidden').val(ledgerId);
                            $('#trackingModal .tr_acc_type_hidden').val(ledgerAccType);
                            /** Get tracking details of this ledger from local storage **/

                            var LedgerTracking = localStorage.getItem('ledgerTrackingId' + ledgerId);
                            if (LedgerTracking == null) {
                                /* Insert the value of the ledger amount */



                                $('.tr_tracking_row:last .tr_tracking_amount_modal').val(ledgerAmt);
                                $('#trackingModal #tr_total_sub_tracking').val(ledgerAmt);
                            } else {



                                $('#trackingModal #tr_total_sub_tracking').val(ledgerAmt);
                                LS_tracking.length = 0;
                                var LS_found = JSON.parse(LedgerTracking);

                                $('#trackingModal .tr_tracking_row').each(function() {
                                    $(this).closest('.form-group').remove();
                                });
                                for (var i = 1; i <= LS_found.length; i++) {

                                    var element = '<div class="form-group">' +
                                            '<div class="row tr_tracking_row" id="' + i + '">' +
                                            '<div class="col-xs-7">' +
                                            '<input type="text" name="tr_tracking_name_modal[]" class="form-control tr_tracking_name_modal" value=' + LS_found[i - 1].tracking_name + '>' +
                                            '<input type="hidden" class="tr_tracking_id_modal" name="tr_tracking_id_modal[]" value=' + LS_found[i - 1].tracking_id + '>' +
                                            '</div>' +
                                            '<div class="col-xs-5">' +
                                            '<input type="text" name="tr_tracking_amount_modal[]"  class="form-control tr_tracking_amount_modal" value=' + LS_found[i - 1].tracking_amount + '>' +
                                            '</div></div></div>';
                                    $('#tracking_container').append(element);
                                    //console.log("things :", LS_found[i-1]);

                                }



                                $('#trackingModal').modal('show');

                            }




                        } else if (data.hasBankingDetails == "success") {
                            $("#bankModal").modal('show');
                            $('#bankModal .tr_banking_row:first .tr_banking_transaction_type').focus();
                            $('#bankModal #tr_banking_ledger_id_hidden').val(ledgerId);
                            var LedgerBanking = localStorage.getItem('ledgerBankingId' + ledgerId);
                            if (LedgerBanking == null) {
                                /* Insert the value of the ledger amount */



                                $('.tr_banking_row:last .tr_banking_bank_amount').val(ledgerAmt);
                                $('#bankModal #tr_banking_amount_total').val(ledgerAmt);
                            } else {

                                $('#bankModal #tr_banking_amount_total').val(ledgerAmt);
                                LS_banking.length = 0;
                                var LS_found = JSON.parse(LedgerBanking);

                                $('#bankModal .tr_banking_row').each(function() {
                                    $(this).remove();
                                });
                                for (var i = 1; i <= LS_found.length; i++) {

                                    var element = '<div class="row tr_banking_row" id="' + i + '">' +
                                            '<div class="col-md-2" style="padding: 0 2px;">' +
                                            '<div class="form-group">' +
                                            '<input type="text" class="form-control tr_banking_transaction_type" placeholder="Transaction Type" name="tr_banking_transaction_type[]" value="' + LS_found[i - 1].transaction_type + '">' +
                                            '<input type="hidden" class="tr_transaction_id_modal" name="tr_transaction_id_modal[]" value="' + LS_found[i - 1].transaction_id + '">' +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="col-md-1 min-padding" style="padding: 0 2px;">' +
                                            '<div class="form-group">' +
                                            '<input type="text" class="form-control tr_banking_instrument_no" placeholder="Ins No" name="instrument_no[]" value="' + LS_found[i - 1].instrument_no + '">' +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="col-md-1 min-padding" style="padding: 0 2px;">' +
                                            '<div class="form-group">' +
                                            '<input type="text" class="form-control tr_banking_instrument_date" placeholder="Date" name="tr_banking_instrument_date[]" value="' + LS_found[i - 1].instrument_date + '">' +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="col-md-2" style="padding: 0 2px;">' +
                                            '<div class="form-group">' +
                                            '<input type="text" class="form-control tr_banking_bank_name" placeholder="Bank Name" name="tr_banking_bank_name[]" value="' + LS_found[i - 1].bank_name + '">' +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="col-md-2" style="padding: 0 2px;">' +
                                            '<div class="form-group">' +
                                            '<input type="text" class="form-control tr_banking_branch_name" placeholder="Branch Name" name="tr_banking_branch_name[]" value="' + LS_found[i - 1].branch_name + '">' +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="col-md-2" style="padding: 0 2px;">' +
                                            '<div class="form-group">' +
                                            '<input type="text" class="form-control tr_banking_ifsc_code" placeholder="IFSC Code" name="tr_banking_ifsc_code[]" value="' + LS_found[i - 1].ifsc_code + '">' +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="col-md-2" style="padding: 0 2px;">' +
                                            '<div class="form-group">' +
                                            '<input type="text" class="form-control tr_banking_bank_amount text-right" placeholder="Amount" name="tr_banking_bank_amount[]" value="' + LS_found[i - 1].bank_amount + '">' +
                                            '</div>' +
                                            '</div>' +
                                            '</div>';
                                    $('#bankModal #banking_container').append(element);
                                    //console.log("things :", LS_found[i-1]);

                                }



                                $('#bankModal').modal('show');

                            }



                        } else if (data.hasBilling == "1" && parseInt($('input[name="advance_entry"]:checked').val()) == 0) {

//                            self.closest('tr').find(".tr_type").val($.trim(data.transactionType)); //@sudip19020218
                            /* Check for popup conditions  */

                            var ledgeId = self.closest('tr').find(".tr_ledger_id").val();
                            if (transaction_type_id != 5 && transaction_type_id != 6 && transaction_type_id != 4 && parent_id != 5 && parent_id != 6 && parent_id != 4) {
                                var billpayType  = 'Cr'; //@sudip19020218
                                $('#billingModal').modal('show');
                                $('#billingModal .bill_row:first .tr_ref_bill_type').focus();
                                $('#billingModal #tr_bill_ledger_name').html(data.ledgerName);
                                $('#billingModal #tr_bill_ledger_id_hidden').val(ledgerId);
                                $('#billingModal .tr_ref_bill_type').val("Against Reference");
                                var LedgerBillingReceipt = localStorage.getItem('ledgerBillingReceiptId' + ledgerId);
                                if (LedgerBillingReceipt == null) {
                                    /* Insert the value of the ledger amount */

                                    $('#billingModal .tr_bill_row:last .tr_bill_amount').val(ledgerAmt);
                                    $('#billingModal #tr_bill_total_bill').val(ledgerAmt);

                                    if(transaction_type_id == 1 || parent_id == 1){ //@sudip19020218
                                        billpayType  = 'Cr';
                                    }else if(transaction_type_id == 2 || parent_id == 2){
                                        billpayType  = 'Dr';
                                    }

                                    $('#billingModal .tr_bill_row:last .tr_bill_account_type').val(billpayType);
                                    $('#billingModal #tr_bill_cr_dr_cal').val(billpayType);

                                } else {



                                    $('#billingModal #tr_bill_total_bill').val(ledgerAmt);
                                    $('#billingModal #tr_bill_cr_dr_cal').val('Cr');
                                    LS_billing.length = 0;
                                    var LS_found = JSON.parse(LedgerBillingReceipt);

                                    $('#billingModal .tr_bill_row').each(function() {
                                        $(this).remove();
                                    });
                                    for (var i = 1; i <= LS_found.length; i++) {

                                        var element = '<div class="form-group tr_bill_row" id="' + i + '">' +
                                                '<div class="row">' +
                                                '<div class="col-xs-2">' +
                                                '<input type="text" class="form-control tr_ref_bill_type" readonly="readonly" value="Against Reference">' +
                                                '</div>' +
                                                '<div class="col-xs-3">' +
                                                '<input type="text" class="form-control tr_bill_name " id="" value="' + LS_found[i - 1].bill_name + '">' +
                                                '<input type="hidden" class="form-control tr_bill_id_hidden">' +
                                                '</div>' +
                                                '<div class="col-xs-2"><input type="number" class="form-control tr_bill_credit_day" readonly="readonly" id="" value="' + LS_found[i - 1].bill_credit_day + '"></div>' +
                                                '<div class="col-xs-2"><input type="text" class="form-control tr_bill_credit_date" id="bill_credit_date" value="' + LS_found[i - 1].bill_credit_date + '" readonly="readonly" ></div>' +
                                                '<div class="col-xs-2"><input type="text" class="form-control tr_bill_amount" id="" value="' + LS_found[i - 1].bill_amount + '"></div>' +
                                                '<div class="col-xs-1"><input type="text" class="form-control tr_bill_account_type" value="' + LS_found[i - 1].bill_acc_type + '" id="" readonly="readonly"></div>' +
                                                '</div>' +
                                                '</div>';
                                        $('#billing_body').append(element);
                                        //console.log("things :", LS_found[i-1]);

                                    }



                                    $('#billingModal').modal('show');

                                }


                            } else {
                                var $canfocus = $(':focusable');
                                var index = $canfocus.index(document.activeElement) + 1;
                                if (index >= $canfocus.length)
                                    index = 0;
                                $canfocus.eq(index).focus();
                            }





                        } else if (data.isCreditorDebitorGroup.res && parseInt($('input[name="advance_entry"]:checked').val()) == 1 && (transaction_type_id == 1 || transaction_type_id == 2 || parent_id == 1 || parent_id == 2)) {
                            $('.advance_ledger_id').val(ledgerId);
                            $('.tax_or_advance').val(1);
                            $('#serviceModal').addClass('advance-entry');
                            $('#serviceModal').find('.modal-title').html('Advance Entry');
                            $("#serviceModal").modal('show');
                        } else {
                            var $canfocus = $(':focusable');
                            var index = $canfocus.index(document.activeElement) + 1;
                            if (index >= $canfocus.length)
                                index = 0;
                            $canfocus.eq(index).focus();
                        }
                    },
                    error: function(request, error) {
                        //alert('connection error. please try again.');
                    }
                });
            } else {

                var $canfocus = $(':focusable');
                var index = $canfocus.index(document.activeElement) + 1;
                if (index >= $canfocus.length)
                    index = 0;
                $canfocus.eq(index).focus();
            }
        }
    });
    $('#trackingModal').on('keypress', '.tr_tracking_amount_modal', function(e) {
        if (e.which == 13) {

            var self = $(this);
            /*  check if tracking name is blank */

            if ($.trim(self.closest('.tr_tracking_row').find('.tr_tracking_name_modal').val()) == "") {

                alert("Please select a tracking name");
                return;
            } else {

                /* check total amounts are equal  */
                var totalSum = 0;
                var actualSum = $('#trackingModal #tr_total_sub_tracking').val();
                $('#trackingModal .tr_tracking_row').each(function() {

                    if ($(this).find(".tr_tracking_amount_modal").val() != "") {
                        totalSum = totalSum + parseFloat($(this).find(".tr_tracking_amount_modal").val());
                    }
                    //totalDr = totalDr + parseFloat($(this).find(".tr_dr_amount").val());
                    //totalCr = totalCr + parseFloat($(this).find(".tr_cr_amount").val());

                });
                if (parseFloat(totalSum) == parseFloat(actualSum)) {



                    if (self.closest('.tracking-form-group').next('.tracking-form-group').length == 0) {

                        var trackingAccType = $('#trackingModal .tr_acc_type_hidden').val();
                        /*  Insert all the datas into local storage */

                        var getLedgerId = $("#trackingModal #tr_tracking_ledger_id_hidden").val();
                        var LedgerTracking = localStorage.getItem('ledgerTrackingId' + getLedgerId);
                        if (LedgerTracking != null) {
                            localStorage.removeItem('ledgerTrackingId' + getLedgerId);
                        }

                        LS_tracking.length = 0;
                        $('#trackingModal .tr_tracking_row').each(function() {

                            var newItem = {
                                'tracking_name': $(this).find(".tr_tracking_name_modal").val(),
                                'tracking_amount': $(this).find(".tr_tracking_amount_modal").val(),
                                'tracking_id': $(this).find(".tr_tracking_id_modal").val(),
                                'account_type': trackingAccType
                            };
                            LS_tracking.push(newItem);
                        });
                        localStorage.setItem('ledgerTrackingId' + getLedgerId, JSON.stringify(LS_tracking));
                        /* HIDE AND DELETE */

                        $('#trackingModal').modal('hide');
                        /* remove modal name + hidden id */

                        $('#trackingModal #tr_tracking_ledger_name').html("");
                        $('#trackingModal #tr_tracking_ledger_id_hidden').val("");
                        /* remove all modal fields except 1st */

                        $('#trackingModal .tr_tracking_row').each(function() {
                            if (parseInt($(this).attr('id')) > 1) {
                                $(this).closest('.form-group').remove();
                            } else if (parseInt($(this).attr('id')) == 1) {
                                $(this).find('.tr_tracking_name_modal').val("");
                                $(this).find('.tr_tracking_amount_modal').val("");
                            }

                        });
                        /* Check if banking Modal exists or not  */

                        var dataLType = {'action': 'get-ledger-extra-details', ledgerId: getLedgerId};
                        // console.log("dataLtype ========= ", dataLType);

                        $.ajax({
                            type: "POST",
                            url: '<?php echo base_url(); ?>transaction/admin/getLedgerExtraDetails',
                            data: dataLType,
                            dataType: "json",
                            success: function(data) {


                                // getLedgerId
                                //actualSum


                                if (data.hasBankingDetails == "success") {
                                    $("#bankModal").modal('show');
                                    $('#bankModal .tr_banking_row:first .tr_banking_transaction_type').focus();
                                    $('#bankModal #tr_banking_ledger_id_hidden').val(getLedgerId);
                                    var LedgerBanking = localStorage.getItem('ledgerBankingId' + getLedgerId);
                                    if (LedgerBanking == null) {
                                        /* Insert the value of the ledger amount */



                                        $('.tr_banking_row:last .tr_banking_bank_amount').val(actualSum);
                                        $('#bankModal #tr_banking_amount_total').val(actualSum);
                                    } else {

                                        $('#bankModal #tr_banking_amount_total').val(actualSum);
                                        LS_banking.length = 0;
                                        var LS_found = JSON.parse(LedgerBanking);

                                        $('#bankModal .tr_banking_row').each(function() {
                                            $(this).remove();
                                        });
                                        for (var i = 1; i <= LS_found.length; i++) {

                                            var element = '<div class="row tr_banking_row" id="' + i + '">' +
                                                    '<div class="col-md-2" style="padding: 0 2px;">' +
                                                    '<div class="form-group">' +
                                                    '<input type="text" class="form-control tr_banking_transaction_type" placeholder="Transaction Type" name="tr_banking_transaction_type[]" value="' + LS_found[i - 1].transaction_type + '">' +
                                                    '<input type="hidden" class="tr_transaction_id_modal" name="tr_transaction_id_modal[]" value="' + LS_found[i - 1].transaction_id + '">' +
                                                    '</div>' +
                                                    '</div>' +
                                                    '<div class="col-md-1 min-padding" style="padding: 0 2px;">' +
                                                    '<div class="form-group">' +
                                                    '<input type="text" class="form-control tr_banking_instrument_no" placeholder="Ins No" name="instrument_no[]" value="' + LS_found[i - 1].instrument_no + '">' +
                                                    '</div>' +
                                                    '</div>' +
                                                    '<div class="col-md-1 min-padding" style="padding: 0 2px;">' +
                                                    '<div class="form-group">' +
                                                    '<input type="text" class="form-control tr_banking_instrument_date" placeholder="Date" name="tr_banking_instrument_date[]" value="' + LS_found[i - 1].instrument_date + '">' +
                                                    '</div>' +
                                                    '</div>' +
                                                    '<div class="col-md-2" style="padding: 0 2px;">' +
                                                    '<div class="form-group">' +
                                                    '<input type="text" class="form-control tr_banking_bank_name" placeholder="Bank Name" name="tr_banking_bank_name[]" value="' + LS_found[i - 1].bank_name + '">' +
                                                    '</div>' +
                                                    '</div>' +
                                                    '<div class="col-md-2" style="padding: 0 2px;">' +
                                                    '<div class="form-group">' +
                                                    '<input type="text" class="form-control tr_banking_branch_name" placeholder="Branch Name" name="tr_banking_branch_name[]" value="' + LS_found[i - 1].branch_name + '">' +
                                                    '</div>' +
                                                    '</div>' +
                                                    '<div class="col-md-2" style="padding: 0 2px;">' +
                                                    '<div class="form-group">' +
                                                    '<input type="text" class="form-control tr_banking_ifsc_code" placeholder="IFSC Code" name="tr_banking_ifsc_code[]" value="' + LS_found[i - 1].ifsc_code + '">' +
                                                    '</div>' +
                                                    '</div>' +
                                                    '<div class="col-md-2" style="padding: 0 2px;">' +
                                                    '<div class="form-group">' +
                                                    '<input type="text" class="form-control tr_banking_bank_amount text-right" placeholder="Amount" name="tr_banking_bank_amount[]" value="' + LS_found[i - 1].bank_amount + '">' +
                                                    '</div>' +
                                                    '</div>' +
                                                    '</div>';
                                            $('#bankModal #banking_container').append(element);
                                            //console.log("things :", LS_found[i-1]);

                                        }



                                        $('#bankModal').modal('show');

                                    }



                                }


                            }})


                    }



                } else if (parseFloat(actualSum) > parseFloat(totalSum)) {

                    var diff = parseFloat(actualSum) - parseFloat(totalSum);
                    /* generate new field */
                    generateNewRowTrackingModal(diff, function() {
                        $('.tr_tracking_row:last .tr_tracking_amount_modal').val(diff);
                    });
                } else if (parseFloat(actualSum) < parseFloat(totalSum)) {
                    alert("Please check all the values correctly!");
                    return;
                }



            }
        }
    });
    $('#bankModal').on('keypress', '.tr_banking_bank_amount', function(e) {
        if (e.which == 13) {

            var self = $(this);
            /*  check if tracking name is blank */
            var transactionType = $.trim(self.closest('.tr_banking_row').find('.tr_banking_transaction_type').val());
            var instrumentNo = $.trim(self.closest('.tr_banking_row').find('.tr_banking_instrument_no').val());
            var instrumentDate = $.trim(self.closest('.tr_banking_row').find('.tr_banking_instrument_date').val());
            var bankName = $.trim(self.closest('.tr_banking_row').find('.tr_banking_bank_name').val());
            var branchName = $.trim(self.closest('.tr_banking_row').find('.tr_banking_branch_name').val());
            var ifscCode = $.trim(self.closest('.tr_banking_row').find('.tr_banking_ifsc_code').val());
            var bankAmount = $.trim(self.closest('.tr_banking_row').find('.tr_banking_bank_amount').val());
            if (transactionType == "" || branchName == "" || bankAmount == "") {

                alert("Please fill all the fields");
                return;
            } else {

                /* check total amounts are equal  */
                var totalSum = 0;
                var actualSum = $('#bankModal #tr_banking_amount_total').val();
                $('#bankModal .tr_banking_row').each(function() {

                    if ($(this).find(".tr_banking_bank_amount").val() != "") {
                        totalSum = totalSum + parseFloat($(this).find(".tr_banking_bank_amount").val());
                    }


                });
                if (parseFloat(totalSum) == parseFloat(actualSum)) {

                    if (self.closest('.tr_banking_row').next('.tr_banking_row').length == 0) {


                        /*  Insert all the datas into local storage */

                        var getLedgerId = $("#bankModal #tr_banking_ledger_id_hidden").val();
                        var LedgerBanking = localStorage.getItem('ledgerBankingId' + getLedgerId);
                        if (LedgerBanking != null) {
                            localStorage.removeItem('ledgerBankingId' + getLedgerId);
                        }

                        LS_banking.length = 0;
                        $('#bankModal .tr_banking_row').each(function() {

                            var newItem = {
                                'transaction_type': $(this).find(".tr_banking_transaction_type").val(),
                                'transaction_id': $(this).find(".tr_transaction_id_modal").val(),
                                'instrument_no': $(this).find(".tr_banking_instrument_no").val(),
                                'instrument_date': $(this).find(".tr_banking_instrument_date").val(),
                                'bank_name': $(this).find(".tr_banking_bank_name").val(),
                                'branch_name': $(this).find(".tr_banking_branch_name").val(),
                                'ifsc_code': $(this).find(".tr_banking_ifsc_code").val(),
                                'bank_amount': $(this).find(".tr_banking_bank_amount").val()


                            };
                            LS_banking.push(newItem);
                        });
                        localStorage.setItem('ledgerBankingId' + getLedgerId, JSON.stringify(LS_banking));
                        /* HIDE AND DELETE */

                        $('#bankModal').modal('hide');
                        /* remove modal name + hidden id */

                        $('#bankModal #tr_banking_ledger_id_hidden').val("");
                        /* remove all modal fields except 1st */

                        $('#bankModal .tr_banking_row').each(function() {
                            if (parseInt($(this).attr('id')) > 1) {
                                $(this).closest('.form-group').remove();
                            } else if (parseInt($(this).attr('id')) == 1) {
                                $(this).find('.tr_banking_transaction_type').val("");
                                $(this).find('.tr_transaction_id_modal').val("");
                                $(this).find('.tr_banking_instrument_no').val("");
                                $(this).find('.tr_banking_instrument_date').val("");
                                $(this).find('.tr_banking_bank_name').val("");
                                $(this).find('.tr_banking_branch_name').val("");
                                $(this).find('.tr_banking_ifsc_code').val("");
                                $(this).find('.tr_banking_bank_amount').val("");
                            }

                        });
                        /*  Check for tracking Modal */


                    }

                } else if (parseFloat(actualSum) > parseFloat(totalSum)) {

                    var diff = parseFloat(actualSum) - parseFloat(totalSum);
                    /* generate new field */
                    generateNewRowBankingModal(diff, function() {
                        $('.tr_banking_row:last .tr_banking_bank_amount').val(diff);
                    });
                } else if (parseFloat(actualSum) < parseFloat(totalSum)) {
                    alert("Please check all the values correctly!");
                    return;
                }



            }
        }
    });
    /* Bill Receipt Modal */



    $('#billingModal').on('keypress', '.tr_bill_account_type', function(e) {
        if (e.which == 13) {

            var self = $(this);
            /*  check if tracking name is blank */
            var billName = $.trim(self.closest('.tr_bill_row').find('.tr_bill_name').val());
            var billCreditDay = $.trim(self.closest('.tr_bill_row').find('.tr_bill_credit_day').val());
            var billCreditDate = $.trim(self.closest('.tr_bill_row').find('.tr_bill_credit_date').val());
            var billAmount = $.trim(self.closest('.tr_bill_row').find('.tr_bill_amount').val());
            var billAccType = $.trim(self.closest('.tr_bill_row').find('.tr_bill_account_type').val());
            if (billName == "" || billCreditDay == "" || billCreditDate == "" || billAmount == "" || billAccType == "") {

                alert("Please fill all the fields");
                return;
            } else {

                /* check total amounts are equal  */
                var totalSum = 0;
                var actualSum = $('#billingModal #tr_bill_total_bill').val();
                $('#billingModal .tr_bill_row').each(function() {

                    if ($(this).find(".tr_bill_amount").val() != "") {
                        totalSum = totalSum + parseFloat($(this).find(".tr_bill_amount").val());
                    }


                });
                if (parseFloat(totalSum) == parseFloat(actualSum)) {

                    if (self.closest('.tr_bill_row').next('.tr_bill_row').length == 0) {


                        /*  Insert all the datas into local storage */

                        var getLedgerId = $("#billingModal #tr_bill_ledger_id_hidden").val();
                        var LedgerBillingReceipt = localStorage.getItem('ledgerBillingReceiptId' + getLedgerId);
                        if (LedgerBillingReceipt != null) {
                            localStorage.removeItem('ledgerBillingReceiptId' + getLedgerId);
                        }

                        LS_billing.length = 0;
                        $('#billingModal .tr_bill_row').each(function() {

                            var newItem = {
                                'reference_type': $(this).find(".tr_ref_bill_type").val(),
                                'bill_name': $(this).find(".tr_bill_name").val(),
                                'bill_credit_day': $(this).find(".tr_bill_credit_day").val(),
                                'bill_credit_date': $(this).find(".tr_bill_credit_date").val(),
                                'bill_amount': $(this).find(".tr_bill_amount").val(),
                                'bill_acc_type': $(this).find(".tr_bill_account_type").val()

                            };
                            LS_billing.push(newItem);
                        });
                        localStorage.setItem('ledgerBillingReceiptId' + getLedgerId, JSON.stringify(LS_billing));
                        /* HIDE AND DELETE */

                        $('#billingModal').modal('hide');
                        /* remove modal name + hidden id */
                        // advance entry modal
                        var ledgerId = $('#billingModal #tr_bill_ledger_id_hidden').val();
                        var dataLType = {'action': 'get-ledger-extra-details', ledgerId: ledgerId};
                        $.ajax({
                            method: "POST",
                            url: '<?php echo base_url(); ?>transaction/admin/getLedgerExtraDetails',
                            data: dataLType,
                            dataType: "json",
                        }).done(function(data) {
                            if (data.isCreditorDebitorGroup.res && parseInt($('input[name="advance_entry"]:checked').val()) == 1 && (transaction_type_id == 1 || transaction_type_id == 2 || parent_id == 1 || parent_id == 2)) {
                                $('.tax_or_advance').val(1);
                                $('#serviceModal').addClass('advance-entry');
                                $('#serviceModal').find('.modal-title').html('Advance Entry');
                                $("#serviceModal").modal('show');
                            } else {
                                //end advance entry modal
                                $('#billingModal #tr_bill_ledger_id_hidden').val("");
                                /* remove all modal fields except 1st */

                                $('#billingModal .tr_bill_row').each(function() {
                                    if (parseInt($(this).attr('id')) > 1) {
                                        $(this).remove();
                                    } else if (parseInt($(this).attr('id')) == 1) {
                                        $(this).find('.tr_bill_name').val("");
                                        $(this).find('.tr_bill_credit_day').val("");
                                        $(this).find('.tr_bill_credit_date').val("");
                                        $(this).find('.tr_bill_amount').val("");
                                    }

                                });
                                /*  Check for tracking Modal */
                            }
                        });




                    }

                } else if (parseFloat(actualSum) > parseFloat(totalSum)) {

                    var diff = parseFloat(actualSum) - parseFloat(totalSum);
                    /* generate new field */
                    generateNewRowBillModal(diff, function() {
                        $('.tr_bill_row:last .tr_bill_amount').val(diff);
                    });
                } else if (parseFloat(actualSum) < parseFloat(totalSum)) {
                    alert("Please check all the values correctly!");
                    return;
                }



            }
        }
    });
    $(document).ready(function() {

        $('#totalFormSubmitBtn').on('keypress', function(e) {
            if (e.which == 13) {

                $(".formSubmitAll").submit();
            }

        });
        function getAllStorageItems(callback) {


            $('.tr_row_ledger').each(function() {
                var getLedgeId = $(this).find('.tr_ledger_id').val();
                // check if local storage exists


                var LS_data_billing = localStorage.getItem('ledgerBillingReceiptId' + getLedgeId);
                var LS_data_banking = localStorage.getItem('ledgerBankingId' + getLedgeId);
                var LS_data_tracking = localStorage.getItem('ledgerTrackingId' + getLedgeId);
                if (LS_data_billing !== null) {

                    var billing_data = {'ledgeId': getLedgeId, value: LS_data_billing};
                    LS_billing_get.push(billing_data);
                }

                if (LS_data_banking !== null) {

                    var banking_data = {'ledgeId': getLedgeId, value: LS_data_banking};
                    LS_banking_get.push(banking_data);
                }

                if (LS_data_tracking !== null) {

                    var tracking_data = {'ledgeId': getLedgeId, value: LS_data_tracking};
                    LS_tracking_get.push(tracking_data);
                }

            });
            callback(LS_billing_get, LS_banking_get, LS_tracking_get);
        }

        function formToJSON(selector) {
            var form = {};
            $(selector).find(':input[name]:enabled').each(function() {
                var self = $(this);
                var name = self.attr('name');
                if (form[name]) {
                    form[name] = form[name] + ',' + self.val();
                }
                else {
                    form[name] = self.val();
                }
            });
            return form;
        }

        $(".formSubmitAll").submit(function(event) {




            event.preventDefault();
            var self = $(this);
            var l = Ladda.create(document.querySelector('.transaction-submit-btn'));
            l.start();
            var newReferenceLedgerArray = [];

            var extraFuncFlag = 0;

            if (transaction_type_id == 5 || parent_id == 5) {


                $('.tr_row_ledger').each(function() {

                    var self1 = $(this);
                    var ledgeId1 = $(this).find('.tr_ledger_id').val();
                    debtorsArray.forEach(function(val) {
                        if (val == ledgeId1) {
                            extraFuncFlag++;
                            var accType = self1.find('.tr_type').val();
                            var drAmt = self1.find('.tr_dr_amount').val();
                            var crAmt = self1.find('.tr_cr_amount').val();
                            var obj = {ledgerId: ledgeId1, accType: accType, drAmt: drAmt, crAmt: crAmt};
                            newReferenceLedgerArray.push(obj);
                        }
                    });
                    salesArray.forEach(function(val) {
                        if (val == ledgeId1) {
                            extraFuncFlag++;
                        }
                    });
                });
            } else if (transaction_type_id == 6 || parent_id == 6) {

                $('.tr_row_ledger').each(function() {

                    var self1 = $(this);
                    var ledgeId1 = $(this).find('.tr_ledger_id').val();
                    creditorsArray.forEach(function(val) {
                        if (val == ledgeId1) {

                            var accType = self1.find('.tr_type').val();
                            var drAmt = self1.find('.tr_dr_amount').val();
                            var crAmt = self1.find('.tr_cr_amount').val();
                            var obj = {ledgerId: ledgeId1, accType: accType, drAmt: drAmt, crAmt: crAmt};
                            newReferenceLedgerArray.push(obj);
                            extraFuncFlag++;
                        }
                    });
                    purchaseArray.forEach(function(val) {
                        if (val == ledgeId1) {
                            extraFuncFlag++;
                        }
                    });
                });
            }




            var newRefCall = 0;
            if (extraFuncFlag >= 2) {
                newRefCall = 1;
            } else {
                newRefCall = 0;
            }

            var currency = $("#selected_currency").val();
            var postdated = $('input[name="postdated"]:checked').val();
            getAllStorageItems(function(billingArr, bankingArr, trackingArr) {


                var extra = {bill: billingArr, bank: bankingArr, tracking: trackingArr, newRefCall: newRefCall, newReferenceLedgerArray: newReferenceLedgerArray, entry_type: transaction_type_id, parent_id: parent_id, currency: currency, postdated: postdated};


                // testing



                var dataString = $("#transaction-form, #service_modal_form").serialize();
                var form = self,
                        url = form.attr('action'),
                        formData = dataString + '&' + $.param(extra);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);return false;

                        l.stop();

                        $('.form-group').removeClass('has-error');
                        if (data.res == 'error') {

                            $.each(data.message, function(index, value) {
                                Command: toastr["error"](value);
                            });
                        } else if (data.res == 'save_err') {
                            Command: toastr["error"](data.message);
                        } else {
                            var activity = $('input[name="activity_submit"]:checked').val();
                            Command: toastr["success"](data.message);
                            //console.log('success blah');

                            localStorage.clear();
                            $('.tr_ledger, .tr_type, .tr_dr_amount, .tr_cr_amount').val("");
                            $('.tr_total_dr, .tr_total_cr').html("");
                            if ($.trim($('.entry_no').val()) != "Auto") {
                                $('.entry_no').val("");
                            }

                            $('.tr_transaction_main .tr_row_ledger').each(function() {
                                if (parseInt($(this).attr('id')) > 2) {
                                    $(this).remove();
                                }

                            });
                            if (parseInt(activity) == 1) {
                                location.reload();
                            } else if (parseInt(activity) == 2) {
                                window.location.href = data.redirect_url;
                            } else if (parseInt(activity) == 3) {
                                window.location.href = data.print_url;
                            }

                        }


                    },
                    error: function(response) {
                        // console.log(response);
                    }
                });
            });
        });
        // $('#tr_date, .tr_banking_instrument_date').bind('keyup', 'keydown', function(event) {
        //     var inputLength = event.target.value.length;
        //     //console.log(inputLength);

        //     if (event.keyCode != 8) {

        //         if (inputLength === 2 || inputLength === 5) {
        //             var thisVal = event.target.value;
        //             thisVal += '/';
        //             $(event.target).val(thisVal);
        //         }


        //         inputLength = event.target.value.length;
        //         //setTimeout(function(){

        //         if (inputLength == 6) {

        //             var ajaxDate = {action: 'get-financial-date'};
        //             $.ajax({
        //                 type: "POST",
        //                 url: '<?php echo base_url(); ?>transaction/admin/getFinancialYear',
        //                 data: ajaxDate,
        //                 success: function(data) {

        //                     var dateRetreived = data;
        //                     var date = new Date(dateRetreived);
        //                     var month = date.getMonth();
        //                     var financialMonth = month + 1;
        //                     var dateGiven = $("#tr_date").val();
        //                     var parts = dateGiven.split('/');
        //                     var dateMonth = parseInt(parts[1]);

        //                     if (parseInt(financialMonth) <= parseInt(dateMonth)) {


        //                         var date1 = new Date(dateRetreived);
        //                         var yearGet = date1.getFullYear();
        //                         var trDate = $('#tr_date');
        //                         if (trDate.val().charAt(trDate.val().length - 1) == "/") {
        //                             trDate.val(trDate.val() + yearGet);
        //                         }

        //                     } else {


        //                         var date1 = new Date(dateRetreived);
        //                         var yearGet = date1.getFullYear() + 1;
        //                         var trDate = $('#tr_date');
        //                         if (trDate.val().charAt(trDate.val().length - 1) == "/") {
        //                             trDate.val(trDate.val() + yearGet);
        //                         }

        //                     }

        //                 }
        //             });
        //         }

        //     }



        // })



    });
//recurring
    $('.formSubmitAll').delegate('.recurring', 'focusin', function() {
        var availableType = [
            "No",
            "Daily",
            "Weekly",
            "Monthly",
            "Yearly"
        ];
        $(this).autocomplete({
            source: availableType,
            minLength: 0,
            select: function(e, ui) {
                var self = $(this);
                e.preventDefault(); // <--- Prevent the value from being inserted.
                $(this).val(ui.item.label); // display the selected text


            }
        }).focus(function() {
            $(this).autocomplete("search", "");
        });
    });
    $('input[name="postdated"]').click(function() {
        if ($(this).val() == 1) {
            $("#tr_date").removeAttr('readonly');
            $('input[name="recurring_freq"]').val('');
            $('input[name="recurring_freq"]').hide();
        } else {
            $('input[name="recurring_freq"]').show();
        }
    })



</script>

<script>
    $('body').delegate('.new-ledger-btn', 'click', function() {
        // Set the effect type
        var effect = 'slide';
        // Set the options for the effect type chosen
        var options = {direction: $('.mySelect').val(0)};
        // Set the duration (default: 400 milliseconds)
        var duration = 300;
        $('#myDiv').toggle(effect, options, duration);
           //get contacts
$('#contact_id').select2({
  ajax: {
    url: full_path+'transaction_inventory/inventory/getAllContacts',
    dataType: 'json',
    processResults: function (data) {
      // Tranforms the top-level key of the response object from 'items' to 'results'
      return {
        results: data
      };
    }
  }
});

$.ajax({
    url: full_path+'transaction_inventory/inventory/getAllGroupsByAjax',
    success: function(response) {
        $("#addLedger #group_id").html(response);
    }
});


        $("#addLedger").modal('show');
    });
    $('body').delegate('.add-group-btn', 'click', function() {
        // Set the effect type
        var effect = 'slide';
        // Set the options for the effect type chosen
        var options = {direction: $('.mySelect').val(0)};
        // Set the duration (default: 400 milliseconds)
        var duration = 300;
        $('#myDiv').toggle(effect, options, duration);

    $.ajax({
        url: full_path+'transaction_inventory/inventory/getAllGroupsByAjax',
        success: function(response) {
            $("#addGroup #parent_id").html(response);
        }
    });
        $("#addGroup").modal('show');
    });</script>
<script>
    $('body').delegate('.search_item', 'focusin', function() {
        var self = $(this);
        var shippingCountry = $('.in_ledger_country').val();
        var shippingState = $('.in_ledger_state').val();
        var type_service_product = $(this).closest('tr').find("#service_product").val();
        $(this).autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction/accounts_inventory/getProducts',
                    data: {term: request.term, type_service_product: type_service_product},
                    dataType: "json",
                    success: function(data) {
                        response(data);
                    },
                    error: function(request, error) {

                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                var self = $(this);
                e.preventDefault(); // <--- Prevent the value from being inserted.
                $(this).val(ui.item.label); // display the selected text
                self.closest('tr').find('.tr_service_id').val(ui.item.value);
                var pId = ui.item.value;
                var productName = ui.item.label;
                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction/accounts_inventory/getProductDetails',
                    data: "sId=" + pId + "&shippingcountry=" + shippingCountry + "&shippingstate=" + shippingState + "&type_service_product=" + type_service_product,
                    dataType: "json",
                    success: function(data) {
                        var shippingState = $('.in_ledger_state').val();
                        var shippingCountry = $('.in_ledger_country').val();
                        var price = self.closest('tr').find('.service_amount').val();
                        var taxPerc = parseFloat(data['productTax']);
                        var companyState = data['billingStateId'];
                        var companyCountry = data['billingCountryId'];
                        var taxVal = parseFloat((taxPerc / 100) * price);
                        var cessStatus = data['cess_present'];
                        var cessPercentage = data['cess_value'];
                        self.closest('tr').find('.tax_percentage').val(taxPerc);
                        self.closest('tr').find('.cess_percentage').val(cessPercentage);
                        if (parseInt(cessStatus) == 1) {
                            $(".cess_status").val(1);
                            var cessVal = parseFloat((cessPercentage / 100) * taxVal);
                        } else {
                            cessPercentage = 0;
                            var cessVal = 0;
                            $(".cess_status").val(0);
                        }
                        if ($.trim(parseInt(companyCountry)) == $.trim(parseInt(shippingCountry))) {
                            $(".export_status").val(0);
                            if ($.trim(parseInt(companyState)) == $.trim(parseInt(shippingState))) {
                                $(".igst_status").val(0);
                                self.closest('tr').find('.igst_percentage').html(parseFloat(0));
                                self.closest('tr').find('.igst_value').html(parseFloat(0));
                                self.closest('tr').find('.sgst_percentage').html(parseFloat(taxPerc / 2));
                                self.closest('tr').find('.sgst_value').html(parseFloat(taxVal / 2));
                                self.closest('tr').find('.cgst_percentage').html(parseFloat(taxPerc / 2));
                                self.closest('tr').find('.cgst_value').html(parseFloat(taxVal / 2));
                                self.closest('tr').find('.cess_percentage').html(parseFloat(cessPercentage));
                                self.closest('tr').find('.cess_value').html(parseFloat(cessVal));
                            } else {
                                $(".igst_status").val(1);
                                self.closest('tr').find('.igst_percentage').html(parseFloat(taxPerc));
                                self.closest('tr').find('.igst_value').html(parseFloat(taxVal));
                                self.closest('tr').find('.sgst_percentage').html(parseFloat(0));
                                self.closest('tr').find('.sgst_value').html(parseFloat(0));
                                self.closest('tr').find('.cgst_percentage').html(parseFloat(0));
                                self.closest('tr').find('.cgst_value').html(parseFloat(0));
                                self.closest('tr').find('.cess_percentage').html(parseFloat(cessPercentage));
                                self.closest('tr').find('.cess_value').html(parseFloat(cessVal));
                            }
                        } else {

                            if (parseInt($('input[name="tax_status_country"]:checked').val()) == 1) {
                                $(".export_status").val(2);
                                $(".igst_status").val(1);
                                self.closest('tr').find('.igst_percentage').html(parseFloat(0));
                                self.closest('tr').find('.igst_value').html(parseFloat(0));
                                self.closest('tr').find('.sgst_percentage').html(parseFloat(0));
                                self.closest('tr').find('.sgst_value').html(parseFloat(0));
                                self.closest('tr').find('.cgst_percentage').html(parseFloat(0));
                                self.closest('tr').find('.cgst_value').html(parseFloat(0));
                                self.closest('tr').find('.cess_percentage').html(parseFloat(0));
                                self.closest('tr').find('.cess_value').html(parseFloat(0));
                            } else {
                                $(".export_status").val(1);
                                $(".igst_status").val(1);
                                self.closest('tr').find('.igst_percentage').html(parseFloat(taxPerc));
                                self.closest('tr').find('.igst_value').html(parseFloat(taxVal));
                                self.closest('tr').find('.sgst_percentage').html(parseFloat(0));
                                self.closest('tr').find('.sgst_value').html(parseFloat(0));
                                self.closest('tr').find('.cgst_percentage').html(parseFloat(0));
                                self.closest('tr').find('.cgst_value').html(parseFloat(0));
                                self.closest('tr').find('.cess_percentage').html(parseFloat(cessPercentage));
                                self.closest('tr').find('.cess_value').html(parseFloat(cessVal));
                            }
                        }
                    },
                    error: function(request, error) {
                        //alert('connection error. please try again.');

                    }
                });
            }

        }).focus(function() {
            $(this).autocomplete("search", "");
        });
    }); // autocomplete

    $('#serviceModal').on('keypress keyup', '.service_amount', function(e) {
        if ($("#serviceModal").hasClass("service-entry")) {
            if (e.which == 13) {
                var tr_igst_val = $('.transaction-form').find('input[value="31"]').closest("tr").find('input[name="amount[]"]').val();
                if (!tr_igst_val) {
                    var tr_igst_val = $('.transaction-form').find('input[value="32"]').closest("tr").find('input[name="amount[]"]').val();
                }
                var tr_cgst_val = $('.transaction-form').find('input[value="33"]').closest("tr").find('input[name="amount[]"]').val();
                if (!tr_cgst_val) {
                    var tr_cgst_val = $('.transaction-form').find('input[value="34"]').closest("tr").find('input[name="amount[]"]').val();
                }
                var tr_sgst_val = $('.transaction-form').find('input[value="35"]').closest("tr").find('input[name="amount[]"]').val();
                if (!tr_sgst_val) {
                    var tr_sgst_val = $('.transaction-form').find('input[value="36"]').closest("tr").find('input[name="amount[]"]').val();
                }
                var tr_cess_val = $('.transaction-form').find('input[value="37"]').closest("tr").find('input[name="amount[]"]').val();
                if (!tr_cess_val) {
                    var tr_cess_val = $('.transaction-form').find('input[value="38"]').closest("tr").find('input[name="amount[]"]').val();
                }
                if (typeof tr_igst_val == 'undefined') {
                    tr_igst_val = parseFloat(0);
                } else {
                    tr_igst_val = parseFloat(tr_igst_val);
                }
                if (typeof tr_cgst_val == 'undefined') {
                    tr_cgst_val = parseFloat(0);
                } else {
                    tr_cgst_val = parseFloat(tr_cgst_val);
                }
                if (typeof tr_sgst_val == 'undefined') {
                    tr_sgst_val = parseFloat(0);
                } else {
                    tr_sgst_val = parseFloat(tr_sgst_val);
                }
                if (typeof tr_cess_val == 'undefined') {
                    tr_cess_val = parseFloat(0);
                } else {
                    tr_cess_val = parseFloat(tr_cess_val);
                }
                var _igst_val = 0;
                var igst_arr = $(".igst_value");
                $.each(igst_arr, function(index, element) {
                    _igst_val = _igst_val + parseFloat($(element).html());
                });
                var _cgst_val = 0;
                var cgst_arr = $(".cgst_value");
                $.each(cgst_arr, function(index, element) {
                    _cgst_val = _cgst_val + parseFloat($(element).html());
                });
                var _sgst_val = 0;
                var sgst_arr = $(".sgst_value");
                $.each(sgst_arr, function(index, element) {
                    _sgst_val = _sgst_val + parseFloat($(element).html());
                });
                var _cess_val = 0;
                var cess_arr = $(".cess_value");
                $.each(cess_arr, function(index, element) {
                    _cess_val = _cess_val + parseFloat($(element).html());
                });
                var matched_igst = 0;
                var matched_sgst = 0;
                var matched_cgst = 0;
                var matched_cess = 0;
                if (tr_igst_val > _igst_val.toFixed(2)) {
                    generateServiceRow();
                } else if (tr_igst_val < _igst_val.toFixed(2)) {
                    Command: toastr["error"]('IGST amount do not matched.');
                } else if (tr_igst_val == _igst_val.toFixed(2)) {
                    var matched_igst = 1;
                }
                if (tr_sgst_val > _sgst_val.toFixed(2)) {
                    generateServiceRow();
                } else if (tr_sgst_val < _sgst_val.toFixed(2)) {
                    Command: toastr["error"]('SGST amount do not matched.');
                } else if (tr_sgst_val == _sgst_val.toFixed(2)) {
                    var matched_sgst = 1;
                }
                if (tr_cgst_val > _cgst_val.toFixed(2)) {
                    generateServiceRow();
                } else if (tr_cgst_val < _cgst_val.toFixed(2)) {
                    Command: toastr["error"]('CGST amount do not matched.');
                } else if (tr_cgst_val == _cgst_val.toFixed(2)) {
                    var matched_cgst = 1;
                }
                if (tr_cess_val > _cess_val.toFixed(2)) {
                    generateServiceRow();
                } else if (tr_cess_val < _cess_val.toFixed(2)) {
                    Command: toastr["error"]('CESS amount do not matched.');
                } else if (tr_cess_val == _cess_val.toFixed(2)) {
                    var matched_cess = 1;
                }

                if (matched_igst && matched_sgst && matched_cgst && matched_cess) {
                    Command: toastr["success"]('GST value matched properly.');
                    $('.tr_narration').focus();
                    $("#serviceModal").modal('hide');
                }
            } else {
                var self = $(this);
                var cess_status = $("#cess_status").val();
                var igst_status = $("#igst_status").val();
                var export_status = $("#export_status").val();
                var taxPerc = self.closest('tr').find(".tax_percentage").val();
                var price = $(this).val();
                var taxVal = parseFloat((taxPerc / 100) * price);
                var cessPercentage = self.closest('tr').find('.cess_percentage').val();
                if (!cessPercentage) {
                    cessPercentage = 0;
                }
                var cessVal = parseFloat((cessPercentage / 100) * taxVal);
                if (parseInt(igst_status) == 0) {
                    self.closest('tr').find('.igst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.igst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.sgst_percentage').html(parseFloat(taxPerc / 2).toFixed(2));
                    self.closest('tr').find('.sgst_value').html(parseFloat(taxVal / 2).toFixed(2));
                    self.closest('tr').find('.cgst_percentage').html(parseFloat(taxPerc / 2).toFixed(2));
                    self.closest('tr').find('.cgst_value').html(parseFloat(taxVal / 2).toFixed(2));
                    self.closest('tr').find('.cess_percentage').html(parseFloat(cessPercentage).toFixed(2));
                    self.closest('tr').find('.cess_value').html(parseFloat(cessVal).toFixed(2));
                } else {
                    self.closest('tr').find('.igst_percentage').html(parseFloat(taxPerc).toFixed(2));
                    self.closest('tr').find('.igst_value').html(parseFloat(taxVal).toFixed(2));
                    self.closest('tr').find('.sgst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.sgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cess_percentage').html(parseFloat(cessPercentage).toFixed(2));
                    self.closest('tr').find('.cess_value').html(parseFloat(cessVal).toFixed(2));
                }
                if ((parseInt(export_status) == 1) && parseInt($('input[name="tax_status_country"]:checked').val()) == 1) {
                    self.closest('tr').find('.igst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.igst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.sgst_percentage').html(parseFloat(0));
                    self.closest('tr').find('.sgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cess_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cess_value').html(parseFloat(0).toFixed(2));
                }
            }
        } else if ($("#serviceModal").hasClass("advance-entry")) {
            if (e.which == 13) {
                var self = $(this);
                var advance_amount = $('input[ledger-type="debitors"]').closest('tr').find('input[name="amount[]"][disabled!=disabled]').val();
                advance_amount = parseFloat(advance_amount);

                var _advance_val = 0;
                var advance_arr = $(".service_amount");
                $.each(advance_arr, function(index, element) {
                    if ($(element).val() != '') {
                        _advance_val = _advance_val + parseFloat($(element).val());
                    }
                });
                if (advance_amount > _advance_val.toFixed(2)) {
                    generateServiceRow();
                } else if (advance_amount < _advance_val.toFixed(2)) {
                    self.closest('tr').nextAll('tr').remove();
                    Command: toastr["error"]('Advance amount do not matched.');
                } else if (advance_amount == _advance_val.toFixed(2)) {
                    self.closest('tr').nextAll('tr').remove();
                    Command: toastr["success"]('Advance amount matched properly.');
                    $("#serviceModal").modal('hide');
                    $('input[ledger-type="debitors"]').closest('tr').next('tr').find('input:first').focus();
                }

            } else {
                var self = $(this);
                var cess_status = $("#cess_status").val();
                var igst_status = $("#igst_status").val();
                var export_status = $("#export_status").val();
                var taxPerc = self.closest('tr').find(".tax_percentage").val();
                var price = $(this).val();
                var taxVal = parseFloat((taxPerc / (100 + parseFloat(taxPerc))) * price);
                var cessPercentage = self.closest('tr').find('.cess_percentage').val();
                if (!cessPercentage) {
                    cessPercentage = 0;
                }
                var cessVal = parseFloat((cessPercentage / 100) * taxVal);
                if (parseInt(igst_status) == 0) {
                    self.closest('tr').find('.igst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.igst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.sgst_percentage').html(parseFloat(taxPerc / 2).toFixed(2));
                    self.closest('tr').find('.sgst_value').html(parseFloat(taxVal / 2).toFixed(2));
                    self.closest('tr').find('.cgst_percentage').html(parseFloat(taxPerc / 2).toFixed(2));
                    self.closest('tr').find('.cgst_value').html(parseFloat(taxVal / 2).toFixed(2));
                    self.closest('tr').find('.cess_percentage').html(parseFloat(cessPercentage).toFixed(2));
                    self.closest('tr').find('.cess_value').html(parseFloat(cessVal).toFixed(2));
                } else {
                    self.closest('tr').find('.igst_percentage').html(parseFloat(taxPerc).toFixed(2));
                    self.closest('tr').find('.igst_value').html(parseFloat(taxVal).toFixed(2));
                    self.closest('tr').find('.sgst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.sgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cess_percentage').html(parseFloat(cessPercentage).toFixed(2));
                    self.closest('tr').find('.cess_value').html(parseFloat(cessVal).toFixed(2));
                }
                if ((parseInt(export_status) == 1) && parseInt($('input[name="tax_status_country"]:checked').val()) == 1) {
                    self.closest('tr').find('.igst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.igst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.sgst_percentage').html(parseFloat(0));
                    self.closest('tr').find('.sgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cess_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cess_value').html(parseFloat(0).toFixed(2));
                }
            }
        }
    });

    function generateServiceRow() {
        var html = '';
        html += '<tr>';
        html += '<td>';
        html += '<div class="form-group">';
        html += '<select class="form-control" name="service_product[]" id="service_product">';
        html += '<option value="1">Service</option>';
        html += '<option value="2">Product</option>';
        html += ' </select>';
        html += '</div>';
        html += '</td>';
        html += '<td>';
        html += ' <div class="form-group">';
        html += '<input type="text" class="form-control search_item" placeholder="Select Service">';
        html += '<input type="hidden" class="tr_service_id" name="tr_service_id[]">'
        html += '</div>';
        html += '</td>';
        html += '<td>';
        html += '<div class="form-group">';
        html += '<input type="text" class="form-control service_amount" name="service_amount[]" placeholder="Amount">';
        html += '<input type="hidden" name="tax_percentage[]" class="tax_percentage">';
        html += '<input type="hidden" name="cess_percentage[]" class="cess_percentage">';
        html += '</div>';
        html += '</td>';
        html += '<td>';
        html += '<span class="igst_percentage">0</span>';
        html += '</td>';
        html += '<td>';
        html += '<span class="igst_value">0</span>';
        html += '</td>';
        html += '<td>';
        html += '<span class="sgst_percentage">0</span>';
        html += '</td>';
        html += '<td>';
        html += '<span class="sgst_value">0</span>';
        html += '</td>';
        html += '<td>';
        html += '<span class="cgst_percentage">0</span>';
        html += '</td>';
        html += '<td>';
        html += '<span class="cgst_value">0</span>';
        html += '</td>';
        html += '<td>';
        html += '<span class="cess_percentage">0</span>';
        html += '</td>';
        html += '<td>';
        html += '<span class="cess_value">0</span>';
        html += '</td>';
        html += '</tr>';
        $("#serviceModal").find('table').find('tbody').append(html);
    }

    function checkGroup(obj) {
        var group_id = $(obj).val();
        $.ajax({
            type: "POST",
            url: '<?php echo site_url('accounts/accounts/checkGroup'); ?>',
            data: "group_id=" + group_id,
            dataType: "json",
            success: function(data) {
                if (data.res) {
                    $("#contact_required").val(1);
                } else {
                    $("#contact_required").val(0);
                }
            },
            error: function(request, error) {
                alert('connection error. please try again.');
            }
        });
    }

    //for branch entry
    $(document).on("change", "input[type=radio]", function() {
        var branch_entry_no = $('[name="select_branch_entry_no"]:checked').val();
        if (parseInt(branch_entry_no) == 1) {
            $("#branch-div").css("display", "block");
        } else {
            $('input[name="branch_entry_no"]').val('');
            $("#branch-div").css("display", "none");
        }
    });


    var monthDate = [0,31,28,31,30,31,30,31,31,30,31,30,31];
    var delimeter;

    $("#tr_date").keyup(function() {


        var financial_year;

        var lastChar = $(this).val().substr($(this).val().length - 1);

        if ( ($(this).val().length ==1 || $(this).val().length == 4) && isNaN(lastChar)) {
            $(this).val( $(this).val().slice(0, -1) );
        }

        if( $(this).val().length == 2 && isNaN(lastChar)) {
            if(lastChar == "." || lastChar == "-" || lastChar == "/"){
                delimeter = lastChar;
                var arrDate = $("#tr_date").val().split(delimeter);
                $(this).val('0'+arrDate[0]+delimeter);

            }else{
               $(this).val( $(this).val().slice(0, -1) );
            }
        }

        if( $(this).val().length == 5 && isNaN(lastChar)) {
            if(lastChar == "." || lastChar == "-" || lastChar == "/"){
                var arrDate = $("#tr_date").val().split(delimeter);
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
        var arrStartDate = $("#tr_date").val().split(delimeter);


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

            var current_url = window.location.protocol + "//" + window.location.hostname;
            var target_url = current_url+"/admin/getCurrentFinancialYearForDateRange";
            // console.log(target_url);
            $.ajax({
                url: target_url,
                type:"POST",
                data:{month:arrStartDate[1]},
                async: false,
                success: function(response){
                    financial_year = $.trim(response);
                }
            });

          $(this).val($(this).val() + delimeter + financial_year);
          $( "input.tr_ledger:first" ).focus();
        }



      }
    });

   $("body").delegate(".tr_cr_amount, .tr_dr_amount", "click, focus", function(){
       $(this).select();
   });

</script>
