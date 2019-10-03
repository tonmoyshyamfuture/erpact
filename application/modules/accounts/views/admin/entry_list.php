<style>
    #receiptTable>tbody>tr>td:nth-child(5) {
        text-align: right;
    }
    #postdatedTable>tbody>tr>td:nth-child(5) {
        text-align: right;
    }
    #recurringTable>tbody>tr>td:nth-child(6) {
        text-align: right;
    }
    .ui-widget-header {
        border: none;; 
        background: #fff url(images/ui-bg_gloss-wave_55_5c9ccc_500x100.png) 50% 50% repeat-x;
    }
    .ui-widget-content {
        border: none;
    }
    .ui-state-active a:link, .ui-state-active a:visited {
        color: #fff;
        background-color: #3c8dbc;
    }
    .transaction-footer{
        background: rgba(242,232,222,0.5);
        border-top: 1px solid #ddd;
        display: block;
        box-sizing: border-box;
        padding: 25px;
        position: absolute;
        margin-top: -58px;
        z-index: 1;
        width: 100%;
        height: 60px;
    }
    .transaction-footer input{
        margin-top: -15px;
    }
    .table{position: relative;}    
    
    span.postdated-status{
        font-size: 18px;
        -webkit-animation: color-change 1s infinite;
        -moz-animation: color-change 1s infinite;
        -o-animation: color-change 1s infinite;
        -ms-animation: color-change 1s infinite;
        animation: color-change 1s infinite;
    }

    @-webkit-keyframes color-change {
        0% { color: red; }
        50% { color: blue; }
        100% { color: red; }
    }
    @-moz-keyframes color-change {
        0% { color: red; }
        50% { color: blue; }
        100% { color: red; }
    }
    @-ms-keyframes color-change {
        0% { color: red; }
        50% { color: blue; }
        100% { color: red; }
    }
    @-o-keyframes color-change {
        0% { color: red; }
        50% { color: blue; }
        100% { color: red; }
    }
    @keyframes color-change {
        0% { color: red; }
        50% { color: blue; }
        100% { color: red; }
    }
</style>
<div class="wrapper2">  
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                    Widget settings form goes here
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn blue">Save changes</button>
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <div class="dropdown"><i class="fa fa-list dropdown-toggle" data-toggle="dropdown" style="cursor: pointer;"></i>&nbsp;<?php
                        if (isset($voucher_type)) {
                            echo $voucher_type['type'];
                        }
                        ?>  <ul class="dropdown-menu"><?php getTransactionListMenu(); ?></ul></div></h1>  
            </div>
            <div class="col-xs-6 col-sm-6">
                <div class="pull-right">
                    <?php if (isset($sub_vouchers) && !empty($sub_vouchers)) { ?>
                        <div class="dropdown" style="float: left; margin-right: 8px;">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Sub Voucher
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <?php foreach ($sub_vouchers as $sub_voucher) { ?> 
                                    <li><a href="<?php echo site_url() . "admin/transaction-list/" . str_replace(' ', '-', strtolower(trim($sub_voucher['type']))) . '/' . $sub_voucher['id'] . '/all'; ?>"><?php echo $sub_voucher['type']; ?> &nbsp;(<?php echo $sub_voucher['entry_code']; ?>)</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                    <?php
                    if ($entry_type_id == 1 || $parent_id == 1) {
                        $module = 84;
                    } elseif ($entry_type_id == 2 || $parent_id == 2) {
                        $module = 85;
                    } elseif ($entry_type_id == 3 || $parent_id == 3) {
                        $module = 86;
                    } elseif ($entry_type_id == 4 || $parent_id == 4) {
                        $module = 87;
                    } elseif ($entry_type_id == 5 || $parent_id == 5) {
                        $module = 183;
                    } elseif ($entry_type_id == 6 || $parent_id == 6) {
                        $module = 184;
                    }
                    $permission = ua($module, 'add');
                    if ($permission):
                        ?>
                        <input type="button" class="btn btn-primary" value="New Entry" onclick="window.location.href = '<?php echo base_url('admin/transaction/' . $entry_type_id) ?>'" />
                    <?php endif; ?>

                </div>
            </div> 


        </div> 
    </section>
    <!-- Modal -->



    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Sub Voucher List </h4>
                </div>
                <div class="modal-body" style="padding:0 0 15px 0">
                    <table class="table table-bordered table-striped transaction-list">                                    
                        <tbody>
                            <?php foreach ($sub_vouchers as $sub_voucher) { ?> 
                                <tr>
                                    <td><a href="<?php echo site_url() . "admin/transaction-list/" . str_replace(' ', '-', strtolower(trim($sub_voucher['type']))) . '/' . $sub_voucher['id'] . '/all'; ?>"><?php echo $sub_voucher['type']; ?></a></td>
                                    <td><?php echo $sub_voucher['entry_code']; ?></td>

                                </tr>
                            <?php } ?>

                        </tbody>

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <section>
        <?php
        $arr = isset($_SERVER['HTTP_REFERER']) ? explode('/', $_SERVER['HTTP_REFERER']) : '';
        $referer = isset($arr[5]) ? $arr[5] : '';
        if ($referer == 'monthly-statistics-report.aspx') {
            $prev_breadcrumbs = $this->session->userdata('_breadcrumbs');
        } else {
            $prev_breadcrumbs = array(
                'Home' => '/admin/dashboard',
                'Transaction' => '#',
            );
        }
        if ($month == 'all') {
            $current_breadcrumbs = array($voucher_name => 'admin/transaction-list/' . $voucher_name . '/' . $entry_type_id . '/' . $month);
        } else {
            $_m = date("F", strtotime($month));
            $current_breadcrumbs = array($_m => 'admin/transaction-list/' . $voucher_name . '/' . $entry_type_id . '/' . $month);
        }
        $breadcrumbs = array_merge($prev_breadcrumbs, $current_breadcrumbs);
        $this->session->set_userdata('_breadcrumbs', $breadcrumbs);
        foreach ($breadcrumbs as $k => $b) {
            $this->breadcrumbs->push($k, $b);
            if ($k == $voucher_name) {
                break;
            }
        }
        $this->breadcrumbs->show();
        ?>
    </section>
    <!-- Main content --> 
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">                    
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#Predated" data-toggle="tab" aria-expanded="false">Current</a></li>
                        <li class=""><a href="#Postdated" data-toggle="tab" aria-expanded="false">Postdated</a></li>
                        <li class=""><a href="#Recurring" data-toggle="tab" aria-expanded="true">Recurring</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="Predated">                        
                            <div class="box">  
                                <div class="box-body table-fullwidth">
                                    <div class="table-responsive">
                                        <table id="receiptTable" class="table table-striped lcol-70  transaction-list">
                                            <?php if(!empty($all_entries)){ ?>
                                            <thead>
                                                <tr>
                                                    <th class="width-80">Date</th>
                                                    <th class="width-120">Number</th>                        
                                                    <th>Ledger</th>
                                                    <th class="width-80">Type</th>
                                                    <th class="text-right width-120">Amount</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <?php } ?>
                                            <tbody>
                                                <?php 
                                                if(!empty($all_entries))
                                                {
                                                    foreach ($all_entries as $entry) { ?> 
                                                    <tr id="current_row_<?php echo $entry['id']; ?>">
                                                        <!-- <td><?php echo date('d-m-Y', strtotime($entry['create_date'])); ?></td> -->
                                                        <td><?php echo get_date_format($entry['create_date']); ?></td>
                                                        <td><?php echo $entry['entry_no']; ?></td>
                                                        <td>
                                                            <?php
                                                            $permission = ua($module, 'view');
                                                            if ($permission):
                                                                ?>
                                                                <a href="<?php echo base_url('admin/trasaction-details') . '.aspx/' . $entry['id']; ?>">
                                                                  
                                                                    <?php $ld = explode('/',$entry['ledger_detail']); ?>
                                                                    <strong><?php echo $ld[0] . " / " . $ld[1]; ?></strong>
                                                                </a>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo $entry['type']; ?></td>
                                                        <!--<td><?php //echo sprintf('%0.2f', $entry['dr_amount']);               ?></td>-->
                                                        <td class="text-right"><?php echo $this->price_format($entry['cr_amount']); ?></td>
                                                        <td>
                                                            <div class="dropdown circle">
                                                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                                    <i class="fa fa-ellipsis-v"></i></a>
                                                                <ul class="dropdown-menu tablemenu">
                                                                    <li>
                                                                        <?php
                                                                        $permission = ua($module, 'edit');
                                                                        if ($permission):
                                                                            ?>
                                                                            <a href="<?php echo site_url('admin/transaction-update') . "/" . $entry['id'] . "/" . $entry_type_id; ?>/e" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                                                            <?php
                                                                        endif;
                                                                        $permission = ua($module, 'add');
                                                                        if ($permission):
                                                                            ?>
                                                                            <a href="<?php echo site_url('admin/transaction-copy') . "/" . $entry['id'] . "/" . $entry_type_id; ?>/c" data-toggle="tooltip" title="Copy Transaction" > <i class="fa fa-clone" aria-hidden="true"></i></a>
                                                                        <?php endif; ?>
                                                                    </li>
                                                                    <li>
                                                                        <?php
                                                                        $permission = ua($module, 'delete');
                                                                        if ($permission):
                                                                            ?>
                                                                            <a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="<?php echo $entry['id']; ?>" data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>
                                                                        <?php endif; ?>
                                                                    </li>
                                                                </ul>


                                                            </div>

                                                        </td>
                                                    </tr>
                                                <?php
                                                    }
                                                   }
                                                   else 
                                                   {
                                                   ?>
                                                   <div class="empty-data">                          
                                                       <img class="img-responsive" src="<?php echo base_url(); ?>assets/images/norecordfound.png">                          
                                                   </div>
                                                   <?php
                                                   }
                                                   ?>
                                            </tbody>                    
                                        </table>
                                    </div>
                                </div><!-- /.box-body -->
                                <!-- <div class="box-footer transaction-footer hidden">

                                    <input type="button" class="btn btn-primary pull-right" value="New Entry" onclick="window.location.href = '<?php echo site_url('admin/add-accounts-entry') . '/' ?><?php echo $entry_type_id; ?>'" />

                                </div> -->     
                            </div>
                        </div>
                        <div class="tab-pane" id="Postdated">                            
                            <div class="box">  
                                <div class="box-body table-fullwidth">
                                    <div class="table-responsive">
                                        <table id="postdatedTable" class="table table-striped  lcol-70  transaction-list">
                                            <thead>
                                             <?php if(!empty($all_post_dated_entries)){ ?>
                                                <tr>
                                                    <th class="width-80">Date</th>
                                                    <th class="width-120">Number</th>                        
                                                    <th>Ledger</th>
                                                    <th class="width-80">Type</th>
                                                    <th class="text-right width-120">Amount</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                            <?php } ?>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                if(!empty($all_post_dated_entries))
                                                {
                                                foreach ($all_post_dated_entries as $entry) { ?> 
                                                    <tr id="post_row_<?php echo $entry['id']; ?>">
                                                        <td><?php echo get_date_format($entry['create_date']); ?></td>
                                                        <td><?php echo $entry['entry_no']; ?></td>
                                                        <td>
                                                            <?php
                                                                        $permission = ua($module, 'view');
                                                                        if ($permission):
                                                                            ?>
                                                            <a href="#">
                                                                <?php
                                                                $led = array();
                                                                $devit = json_decode($entry['ledger_ids_by_accounts']);

                                                                echo "<strong>Dr </strong>";
                                                                for ($i = 0; $i < count($devit->Dr); $i++) {
                                                                    echo $devit->Dr[$i];
                                                                    if (count($devit->Dr) > 1) {
                                                                        echo ' + ';
                                                                    }
                                                                    break;
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
                                                            <?php endif;?>
                                                        </td>
                                                        <td><?php echo $entry['type']; ?></td>
                                                        <td class="text-right"><?php echo $this->price_format($entry['cr_amount']); ?></td>
                                                        <td>
                                                            <?php
                                                            if ($entry['create_date'] == date("Y-m-d")):
                                                                ?>
                                                                <span class="postdated-status" data-toggle="tooltip" title="Add Now"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown circle">
                                                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                                    <i class="fa fa-ellipsis-v"></i></a>
                                                                <ul class="dropdown-menu tablemenu">
                                                                    <?php
                                                                    if ($entry['create_date'] == date("Y-m-d")) {
                                                                        ?>
                                                                        <li>
                                                                            <a href="javascript:void(0);" data-toggle="tooltip" title="Add Now"  data-id="<?php echo $entry['id']; ?>" class="approved-postdated"><span class="text-green"><i class="fa fa-check-circle" aria-hidden="true"></i></span></a>
                                                                        </li>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    <li><?php
                                                                        $permission = ua($module, 'edit');
                                                                        if ($permission):
                                                                            ?>

                                                                            <a class="" href="<?php echo site_url('admin/transaction-update') . "/" . $entry['id'] . "/" . $entry_type_id; ?>"><span><i class="fa fa-pencil"></i></span></a>
                                                                        <?php endif; ?></li>
                                                                    <li><?php
                                                                       $permission = ua($module, 'delete');
                                                                        if ($permission):
                                                                            ?>
                                                                            <a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="<?php echo $entry['id']; ?>" data-type="postdated" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>
                                                                        <?php endif; ?></li>
                                                                </ul>


                                                            </div>

                                                        </td>
                                                    </tr>
                                                <?php
                                                    }
                                                   }
                                                   else 
                                                   {
                                                   ?>
                                                   <div class="empty-data">                          
                                                       <img class="img-responsive" src="<?php echo base_url(); ?>assets/images/norecordfound.png">                          
                                                   </div>
                                                   <?php
                                                   }
                                                   ?>
                                            </tbody>                    
                                        </table>
                                    </div>
                                </div><!-- /.box-body -->
                                <!-- <div class="box-footer transaction-footer hidden">
                                    <div class="footer-button">
                                        <input type="button" class="btn btn-primary pull-right " value="New Entry" onclick="window.location.href = '<?php echo site_url('admin/add-accounts-entry') . '/' ?><?php echo $entry_type_id; ?>'" />
                                    </div>
                                </div>  -->    
                            </div>
                        </div>

                        <div class="tab-pane" id="Recurring">
                            <div class="box">  
                                <div class="box-body table-fullwidth">
                                    <div class="table-responsive">
                                        <table id="recurringTable" class="table table-striped  lcol-70  transaction-list">
                                             <?php if(!empty($all_recurring_entries)){ ?>
                                            <thead>
                                                <tr>
                                                    <th class="width-80">Date</th>
                                                    <th class="width-120">Number</th>                        
                                                    <th>Ledger</th>
                                                    <th class="width-80">Type</th>                                                    
                                                    <th class="width-80">Frequency</th>
                                                    <th class="text-right width-120">Amount</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <?php } ?>
                                            <tbody>
                                                <?php 
                                                if(!empty($all_recurring_entries))
                                                    {
                                                foreach ($all_recurring_entries as $entry) { ?> 
                                                    <tr id="recurring_row_<?php echo $entry['id']; ?>">
                                                        <!-- <td><?php echo date('d-m-Y', strtotime($entry['create_date'])); ?></td> -->
                                                        <td><?php echo get_date_format($entry['create_date']); ?></td>
                                                        <td><?php echo $entry['entry_no']; ?></td>
                                                        <td>
                                                            <?php
                                                            $permission = ua($module, 'view');
                                                                        if ($permission):
                                                            ?>
                                                            <a href="<?php echo base_url('admin/trasaction-details') . '.aspx/' . $entry['id']; ?>">
                                                                <?php
                                                                $led = array();
                                                                $devit = json_decode($entry['ledger_ids_by_accounts']);

                                                                echo "<strong>Dr </strong>";
                                                                for ($i = 0; $i < count($devit->Dr); $i++) {
                                                                    echo $devit->Dr[$i];
                                                                    if (count($devit->Dr) > 1) {
                                                                        echo ' + ';
                                                                    }
                                                                    break;
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
                                                            <?php endif;?>
                                                        </td>
                                                        <td><?php echo $entry['type']; ?></td>
                                                        <td><?php echo $entry['frequency']; ?></td>
                                                        <td class="text-right"><?php echo $this->price_format($entry['cr_amount']); ?></td>
                                                        <td>
                                                            <div class="dropdown circle">
                                                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                                    <i class="fa fa-ellipsis-v"></i></a>
                                                                <ul class="dropdown-menu tablemenu">    
                                                                    <li><?php
                                                                        $permission = ua($module, 'delete');
                                                                        if ($permission):
                                                                            ?>
                                                                            <a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="<?php echo $entry['id']; ?>" data-type="recurring" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>
                                                                        <?php endif; ?></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php
                                                    }
                                                   }
                                                   else 
                                                   {
                                                   ?>
                                                   <div class="empty-data">                          
                                                       <img class="img-responsive" src="<?php echo base_url(); ?>assets/images/norecordfound.png">                          
                                                   </div>
                                                   <?php
                                                   }
                                                   ?>
                                            </tbody>                    
                                        </table>
                                    </div>
                                </div><!-- /.box-body -->
                                <!-- <div class="box-footer transaction-footer hidden">

                                    <input type="button" class="btn btn-primary pull-right" value="New Entry" onclick="window.location.href = '<?php echo site_url('admin/add-accounts-entry') . '/' ?><?php echo $entry_type_id; ?>'" />

                                </div>  -->  
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->

</div>
<div id="postdatedConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo site_url('accounts/entries/ajax_postdated_approve'); ?>" method="post" id="postdated-approve-form">
                <div class="modal-header" style="background: #16a085;color: #fff">
                    <h4 class="modal-title">Confirmation</h4>
                    <input type="hidden" value="" id="post-entry-id" name="post_entry_id">
                </div>
                <div class="modal-body">
                    <p style="font-size:16px;" id="group-confirm-msg">Are you want to add this entry within current entry?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="submit" class="btn ladda-button postdated-approve-btn" data-color="mint" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="deleteEntryConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo site_url('accounts/entries/ajax_delete_transaction'); ?>" method="post" id="delete-transaction-form">
                <div class="modal-header" style="background: #367fa9;color: #fff">
                    <h4 class="modal-title">Confirmation</h4>
                    <input type="hidden" value="" id="delete-entry-id" name="delete_entry_id">
                    <input type="hidden" value="" id="delete-entry-type" name="delete_entry_type">
                </div>
                <div class="modal-body">
                    <p style="font-size:16px;" id="group-confirm-msg">Are you sure want to delete this entry?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="submit" class="btn ladda-button delete-entry-btn" data-color="blue" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url('assets') ?>/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">

    $(document).ready(function () {
        <?php if (!empty($all_entries)): ?>
            $('#receiptTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "<?php echo site_url('accounts/entries/receiptAjaxListing') . '?name=' . $this->uri->segment(3) . '&eid=' . $this->uri->segment(4) . '&emonth=' . $this->uri->segment(5); ?>",
                "deferLoading": <?php echo $dataCount; ?>,
                "bSort":false,
                "pageLength": 10,
                "bAutoWidth": false,
                "oLanguage": {
                "sSearch":"",
                "sSearchPlaceholder": "Search here..."
                },
                "fnDrawCallback": function(oSettings) {
                if (oSettings._iDisplayLength >= oSettings.fnRecordsDisplay()) {
                    $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                }
            }
                
            });
        <?php endif ?>
        
        <?php if (!empty($all_recurring_entries)): ?>
            $('#recurringTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "<?php echo site_url('accounts/entries/recurringAjaxListing') . '?name=' . $this->uri->segment(3) . '&eid=' . $this->uri->segment(4) . '&emonth=' . $this->uri->segment(5); ?>",
                "deferLoading": <?php echo $recurringCount; ?>,
                "bSort":false,
                "pageLength": 10,
                "bAutoWidth": false,
                "oLanguage": {
                "sSearch":"",
                "sSearchPlaceholder": "Search here..."
                },
                "fnDrawCallback": function(oSettings) {
                if (oSettings._iDisplayLength >= oSettings.fnRecordsDisplay()) {
                    $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                }
            }
                
            });            
        <?php endif ?>

        <?php if (!empty($all_post_dated_entries)): ?>            
            $('#postdatedTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "<?php echo site_url('accounts/entries/postdatedAjaxListing') . '?name=' . $this->uri->segment(3) . '&eid=' . $this->uri->segment(4) . '&emonth=' . $this->uri->segment(5); ?>",
                "deferLoading": <?php echo $postdatedCount; ?>,
                "bSort":false,
                "pageLength": 10,
                "bAutoWidth": false,
                "oLanguage": {
                "sSearch":"",
                "sSearchPlaceholder": "Search here..."
                },
                "fnDrawCallback": function(oSettings) {
                if (oSettings._iDisplayLength >= oSettings.fnRecordsDisplay()) {
                    $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                }
            }
                
            });
        <?php endif ?>
    });

</script>
<script>

    $(".approved-postdated").click(function() {
        var entry_id = $(this).attr("data-id");
        $("#post-entry-id").val(entry_id);
        $("#postdatedConfirm").modal('show');
    });
    $("#postdated-approve-form").submit(function(event) {
        event.preventDefault();
        var l = Ladda.create(document.querySelector('.postdated-approve-btn'));
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
                if (data.res == 'success') {
                    $('#post_row_' + data.entry_id).remove();
                    $("#postdatedConfirm").modal('hide');
                    Command: toastr["success"](data.message);
                } else {
                    Command: toastr["error"]('Error Occured please try again.');
                }
            }
        });

    });

    //delete entry
    $("body").delegate(".delete-entry", "click", function() {
        var entry_id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        $("#delete-entry-id").val(entry_id);
        $("#delete-entry-type").val(type);
        $("#deleteEntryConfirm").modal('show');
    });

    $("#delete-transaction-form").submit(function(event) {
        event.preventDefault();
        var l = Ladda.create(document.querySelector('.delete-entry-btn'));
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
                if (data.res == 'success') {
                    if (data.type == 'current') {
                        $('#current_row_' + data.entry_id).remove();
                    } else if (data.type == 'postdated') {
                        $('#post_row_' + data.entry_id).remove();
                    } else if (data.type == 'recurring') {
                        $('#recurring_row_' + data.entry_id).remove();
                    }

                    $("#deleteEntryConfirm").modal('hide');
                    Command: toastr["success"](data.message);
                } else {
                    Command: toastr["error"]('Error Occured please try again.');
                }
            }
        });

    });

</script>

<style>
    .tab-content .box{margin-bottom: 0 !important;}
</style>