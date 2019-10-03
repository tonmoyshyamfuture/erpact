<style>

    .table>tbody>tr>td:nth-child(6) {
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
        font-size: 25px;
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
    .box{margin-bottom: 0}
</style>
<div class="wrapper2">  

    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1><div class="dropdown"><i class="fa fa-list dropdown-toggle" data-toggle="dropdown" style="cursor: pointer;"></i>&nbsp;Quotation List  <ul class="dropdown-menu"><?php //getTransactionListMenu(); ?></ul></div></h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">  
                    <?php if (isset($sub_vouchers) && !empty($sub_vouchers)) { ?>
                        <div class="dropdown" style="float: left; margin-right: 8px;">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Sub Voucher
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <?php foreach ($sub_vouchers as $sub_voucher) { ?> 
                                    <li><a href="<?php echo site_url() . "admin/inventory-transaction-list/" . str_replace(' ', '-', strtolower(trim($sub_voucher['type']))) . '/' . $sub_voucher['id'] . '/all'; ?>"><?php echo $sub_voucher['type']; ?> (<?php echo $sub_voucher['entry_code']; ?>)</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                    <?php
                    if ($entry_type_id == 25 || $parent_id==25) {
                        $module = 196;
                    }
                    $permission = ua($module, 'add');
                    if ($permission):
                        ?>
                        <?php if ($entry_type_id == 25 || $parent_id==25): ?>
                            <input type="button" class="btn btn-primary" value="New Entry" onclick="window.location.href = '<?php echo site_url('admin/quotation')?>'" />
                        <?php endif; ?>   
                    <?php endif; ?>    
                </div>
            </div> 


        </div> 
    </section>
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
                        <thead></thead>
                        <tbody>
                            <?php foreach ($sub_vouchers as $sub_voucher) { ?> 
                                <tr>
                                    <td><a href="<?php echo site_url() . "admin/inventory-transaction-list/" . str_replace(' ', '-', strtolower(trim($sub_voucher['type']))) . '/' . $sub_voucher['id'] . '/all'; ?>"><?php echo $sub_voucher['type']; ?></a></td>
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
    <!-- Modal -->
    <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Quotations', '/admin/quotation-list');
        $this->breadcrumbs->show();
        ?>
    </section>
    <!-- Main content --> 
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">                    
                    <div id="tabx" class="nav-tabs">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#Predated" data-toggle="tab" aria-expanded="false">Current</a></li>
                            <!-- <li class=""><a href="#Postdated" data-toggle="tab" aria-expanded="false">Postdated</a></li> -->
                            <!-- <?php if($entry_type_id == 5 || $parent_id == 5):?>
                            <li class=""><a href="#Recurring" data-toggle="tab" aria-expanded="true">Recurring</a></li>
                            <?php endif; ?> -->
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="Predated"> 

                                <div class="box">
                                    <div class="box-body table-fullwidth">
                                        <div class="table-responsive">
                                            <table id="quotationTable" class="table table-striped lcol-70  transaction-list">
                                                <?php if(!empty($all_entries)){ ?>
                                                <thead>
                                                    <tr>
                                                        <th class="width-80">Date</th>
                                                        <th class="width-120">Number</th>                        
                                                        <th>Ledger</th>
                                                        <?php if($entry_type_id == 5 || $parent_id == 5){ ?>
                                                        <th class="width-120">Eway Bill No</th>
                                                        <?php } ?>
                                                        <th class="width-80">Type</th>
                                                        <?php
                                                        if ($entry_type_id == 7 || $entry_type_id == 8 || $parent_id == 7 || $parent_id == 8 || $entry_type_id == 25 || $parent_id==25):
                                                            ?>
                                                            <!-- <th class="width-80">Status</th> -->
                                                        <?php endif; ?>
                                                        <th class="text-right width-120">Amount</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <?php } else{ ?>
                                                    <thead></thead>
                                                <?php } ?>
                                                <tbody>

                                                    <?php 
                                                    if(!empty($all_entries))
                                                    {
                                                        foreach ($all_entries as $entry) { ?> 
                                                        <tr id="current_row_<?php echo $entry['id']; ?>">
                                                            <!-- <td><?php echo date('d-m-Y', strtotime($entry['create_date'])); ?></td> -->
                                                            <td><?php echo get_date_format($entry['create_date']); ?></td>
                                                            <td class="tran_click" data-entry="<?php echo $entry['id']; ?>"><?php echo $entry['entry_no']; ?>&nbsp;&nbsp;<span data-toggle="tooltip" title="<?php echo 'Ref. No. ' . $entry['voucher_no'] ?>"><?php echo (strlen($entry['voucher_no']) > 0) ? ((strlen($entry['voucher_no']) > 6) ? '(Ref. No. ' . substr($entry['voucher_no'], 0, 6) . '...' . ')' : '(Ref. No. ' . $entry['voucher_no'] . ')') : ''; ?></span></td>
                                                            <td>
                                                                <?php if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){ ?>
                                                                    <!-- <a href="<?php echo base_url('transaction/invoice') . '.aspx/' . $entry['id'] . '/' . $entry['entry_type_id'].'/'.$entry['sub_voucher']; ?>"> -->
                                                                    <a href="<?php echo base_url('admin/view-quotation') . '/' . $entry['id']; ?>">
                                                                       <?php if (empty($entry['ledger_detail'])): ?>
                                                                           <?php
                                                                           $led = array();
                                                                           $devit = json_decode($entry['ledger_ids_by_accounts']);
                                                                           // echo "<pre>";print_r($devit);exit();

                                                                           echo "<strong>Dr </strong>";
                                                                           if (isset($devit->Dr)) {
                                                                               for ($i = 0; $i < count($devit->Dr); $i++) {
                                                                                   // echo $devit->Dr[$i];
                                                                                    $lname = explode(' ', $devit->Dr[$i]);
                                                                                    echo $lname[0];
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
                                                                               // echo $devit->Cr[$i];
                                                                                $lname = explode(' ', $devit->Cr[$i]);
                                                                                echo $lname[0];
                                                                               if (count($devit->Cr) > 1) {
                                                                                   echo ' + ';
                                                                               }
                                                                               break;
                                                                           }
                                                                           ?>
                                                                       <?php else: ?>

                                                                        <?php $ld = explode('/',$entry['ledger_detail']); ?>
                                                                        <strong><?php echo $ld[0] . " / " . $ld[1]; ?></strong>
                                                                       <?php endif ?>

                                                                    </a>
                                                                <?php }else{ 
                                                                    echo '<span class="label label-danger"><b>Canceled</b></span>';
                                                                } ?>
                                                                
                                                            </td>
                                                            <?php if($entry_type_id == 5 || $parent_id == 5){ ?>
                                                            <td>
                                                                <?php
                                                                    if($entry['w_cancel_status']){ ?>
                                                                            <a entry-id="<?php echo $entry['id']; ?>" class="text-danger" href="javascript:void(0)" id="eway_bill_canceled"><?php echo isset($entry['eway_bill_no'])?$entry['eway_bill_no']:$entry['eway_bill_no']; ?></a>
                                                                <?php }else{ ?>
                                                                            <a entry-id="<?php echo $entry['id']; ?>" class="text-success bold" href="javascript:void(0)" id="eway_bill_no"><?php echo isset($entry['eway_bill_no'])?$entry['eway_bill_no']:$entry['eway_bill_no']; ?></a>
                                                                <?php }
                                                                ?>
                                                                
                                                            </td>
                                                            <?php } ?>
                                                            <td><?php echo $entry['type']; ?></td>
                                                            <?php
                                                            if ($entry_type_id == 25 || $parent_id==25):
                                                                ?>
                                                                <!-- <td><span class="label <?php echo (isset($entry['order_status']) && $entry['order_status'] == 'Open') ? 'label-success' : ((isset($entry['order_status']) && $entry['order_status'] == 'Partial') ? 'label-primary' : 'label-danger') ?>"><?php echo $entry['order_status']; ?></span></td> -->
                                                            <?php endif; ?>
                                                            <td class="text-right">
                                                                <?php if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){ ?>
                                                                    <?php echo $this->price_format($entry['cr_amount']); ?>
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $max_time = strtotime("+".$accounts_configuration->entry_action_limit." Days", strtotime($entry['create_date']));
                                                                    $t = time();
                                                                    ?>
                                                                    <?php if ($t < $max_time): ?>
                                                                        
                                                                    
                                                                <?php if(isset($entry['cancel_status']) && $entry['cancel_status'] == 0){ ?>
                                                                <div class="dropdown circle">
                                                                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                                        <i class="fa fa-ellipsis-v"></i></a>
                                                                    <ul class="dropdown-menu tablemenu">

                                                                        <?php
                                                                        $permission = ua($module, 'edit');
                                                                        if ($permission):
                                                                            if ($entry_type_id == 25 || $parent_id==25): ?>
                                                                                <li>
                                                                                    <a href="<?php echo base_url('admin/edit-quotation') . "/" . $entry['id'] . "/" . $entry_type_id . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>  
                                                                                    <!-- <a href="javascript:void(0);" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>   -->
                                                                                </li>
                                                                                <?php if (isset($entry['order_status']) && ($entry['order_status'] == 'Open' || $entry['order_status'] == 'Partial')): ?>
                                                                                    <li>
                                                                                        <a href="<?php echo base_url('admin/edit-quotation') . "/" . $entry['id'] . "/" . $entry_type_id . "/t"; ?>" data-toggle="tooltip" title="Sales Order" > <i class="fa fa-shopping-basket" aria-hidden="true"></i></a>  
                                                                                        <!-- <a href="javascript:void(0);" data-toggle="tooltip" title="Sales Order" > <i class="fa fa-shopping-basket" aria-hidden="true"></i></a>   -->
                                                                                    </li>
                                                                                <?php endif; ?>
                                                                            
                                                                                <?php
                                                                            endif;
                                                                        endif;
                                                                        ?>

                                                                        <li>
                                                                            <?php
                                                                            $permission = ua($module, 'delete');
                                                                            if ($permission):
                                                                                if ($entry['entry_type_id'] == 25):
                                                                                    ?>
                                                                                    <a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="<?php echo $entry['id']; ?>" delete-type="current" data-type="request" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>
                                                                                    <?php
                                                                                endif;
                                                                            endif;
                                                                            ?>
                                                                        </li>
                                                                    </ul>


                                                                </div>
                                                                <?php } ?>
                                                                <?php endif ?>
                                                            </td>
                                                        </tr>
                                                        <!-- <tr class="transaction_details" style="display: none;"><td></td></tr> -->
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
                                            <div class="transaction_details" style="display: none; padding: 10px; border: 2px solid #aaa;"></div>
                                        </div>
                                    </div><!-- /.box-body -->     
                                </div>
                            </div>
                            <div class="tab-pane" id="Postdated">                            
                                <div class="box">  
                                    <div class="box-body table-fullwidth">
                                        <div class="table-responsive">
                                            <table id="postdatedTable" class="table table-striped lcol-70  transaction-list">
                                                <thead>
                                                    <?php if(!empty($all_post_dated_entries)){ ?>
                                                        <tr>
                                                            <th class="width-80">Date</th>
                                                            <th class="width-120">Number</th>                        
                                                            <th>Ledger</th>
                                                            <th class="width-80">Type</th>
                                                            <th class="text-right width-120">Amount</th>
                                                            <th></th>                                                        
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
                                                                            <?php
                                                                            $permission = ua($module, 'edit');
                                                                            if ($permission):
                                                                            if ($entry['entry_type_id'] == 5):
                                                                                ?>
                                                                                <li>
                                                                                    <a href="<?php echo site_url('transaction/sales-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                                                                </li>
                                                                            <?php elseif ($entry['entry_type_id'] == 6): ?>
                                                                                <li>
                                                                                    <a href="<?php echo site_url('transaction/purchase-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>                                                          
                                                                                </li>
                                                                            <?php 
                                                                            endif;
                                                                            endif;
                                                                             $permission = ua($module, 'delete');
                                                                            if ($permission):
                                                                            ?>
                                                                            <li>
                                                                                <a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="<?php echo $entry['id']; ?>" delete-type="postdated" data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>
                                                                            </li>
                                                                            <?php endif;?>
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
                                    <div class="box-footer transaction-footer hidden">

                                        <input type="button" class="btn btn-primary pull-right" value="New Entry" onclick="window.location.href = '<?php echo site_url('admin/add-accounts-entry') . '/' ?><?php echo $entry_type_id; ?>'" />

                                    </div>     
                                </div>
                            </div>

                            <div class="tab-pane" id="Recurring">
                                <div class="box">  
                                    <div class="box-body table-fullwidth">
                                        <div class="table-responsive">
                                            <table id="recurringTable" class="table table-striped lcol-70  transaction-list">
                                                <?php if(!empty($all_recurring_entries)){ ?>
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
                                                <?php }else { ?>
                                                <thead></thead>
                                                <?php } ?>
                                                <tbody>                                                    
                                                        <?php 
                                                        if(!empty($all_recurring_entries))
                                                        {
                                                            foreach ($all_recurring_entries as $entry) { ?> 
                                                            <tr id="recurring_row_<?php echo $entry['id']; ?>">
                                                                <td><?php echo get_date_format($entry['create_date']); ?></td>
                                                                <td><?php echo $entry['entry_no']; ?></td>
                                                                <td>
                                                                    <a href="<?php echo base_url('transaction/invoice') . '.aspx/' . $entry['id'] . '/' . $entry['entry_type_id'].'/'.$entry['sub_voucher']; ?>">
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
                                                                </td>
                                                                <td>ssssss<?php echo $entry['type']; ?></td>
                                                                <!--<td><?php //echo sprintf('%0.2f', $entry['dr_amount']);                    ?></td>-->
                                                                <td class="text-right"><?php echo $this->price_format($entry['cr_amount']); ?></td>
                                                                <td>
                                                                    <div class="dropdown circle">
                                                                        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                                            <i class="fa fa-ellipsis-v"></i></a>
                                                                        <ul class="dropdown-menu tablemenu">
                                                                            <?php
                                                                             $permission = ua($module, 'edit');
                                                                            if ($permission):
                                                                            if ($entry['entry_type_id'] == 5):
                                                                                ?>
                                                                                <li>
                                                                                    <a href="<?php echo site_url('transaction/sales-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
                                                                                </li>
                                                                            <?php elseif ($entry['entry_type_id'] == 6): ?>
                                                                                <li>
                                                                                    <a href="<?php echo site_url('transaction/purchase-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>                                                          
                                                                                </li>
                                                                            <?php 
                                                                            endif;
                                                                            endif;
                                                                            $permission = ua($module, 'edit');
                                                                            if ($permission):
                                                                            ?>
                                                                            <li>
                                                                                <a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="<?php echo $entry['id']; ?>" delete-type="recurring" data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>
                                                                            </li>
                                                                            <?php  endif;?>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->

</div>
<div id="deleteEntryConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo site_url('transaction_inventory/inventory/ajax_delete_quotation'); ?>" method="post" id="delete-transaction-form">
                <div class="modal-header" style="background: #4b83ee;color: #fff">
                    <h4 class="modal-title">Confirmation</h4>
                    <input type="hidden" value="" id="delete-entry-id" name="delete_entry_id">
                    <input type="hidden" value="" id="delete-entry-type" name="delete_entry_type">
                    <input type="hidden" value="" id="delete-type" name="delete_type">
                </div>
                <div class="modal-body">
                    <!-- <p style="font-size:16px;" id="group-confirm-msg">Are you sure want to <label><input type="radio" value="0" id="delete_or_cancel" checked="checked" name="cancel"> delete</label> / <label><input type="radio" value="1" id="delete_or_cancel" name="cancel"> Cancel</label> this entry?</p> -->
                    <p style="font-size:16px;" id="group-confirm-msg">Are you sure want to <label><input type="hidden" value="0" id="delete_or_cancel" checked="checked" name="cancel"> delete</label> this entry?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="submit" class="btn btn-primary ladda-button delete-entry-btn" data-color="blue" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>

 <!--Eway bill start-->
 <section style="display:none"  class="content" id="eway_bill_section_list">
        <div class="clearfix bill-wrapper">
            <div class="box">   
                <div class="box-body eway_bill_pdf_class eway_bill_section_list" id="printreport" data-pdf="Invoice">
                    
                </div>
        </div>    
        </div>
    </section><!-- /.content -->

    <script type="text/javascript" src="<?php echo base_url('assets') ?>/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript">

        $(document).ready(function () {
            <?php if (!empty($all_entries)): ?>
                $('#quotationTable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": "<?php echo site_url('transaction_inventory/inventory/quotationAjaxListing') . '?name=quotation&eid=17&emonth='; ?>",
                    "deferLoading": <?php echo $dataCount; ?>,
                    "bSort":false,
                    "bAutoWidth": false,
                    "pageLength": 10,
                    "oLanguage": {
                    "sSearch":"",
                    "sSearchPlaceholder": "Search here..."
                    },
                    "columnDefs": [
                        { className: "text-right", "targets": [ 4 ] }
                    ]
                    
                });
            <?php endif ?>
            
            <?php if (!empty($all_recurring_entries)): ?>
                $('#recurringTable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": "<?php echo site_url('transaction_inventory/inventory/recurringAjaxListing') . '?name=' . $this->uri->segment(3) . '&eid=' . $this->uri->segment(4) . '&emonth=' . $this->uri->segment(5); ?>",
                    "deferLoading": <?php echo $recurringCount; ?>,
                    "bSort":false,
                    "pageLength": 10,
                    "bAutoWidth": false,
                    "oLanguage": {
                        "sSearch":"",
                        "sSearchPlaceholder": "Search here..."
                    }
                    
                });
            <?php endif ?>
            
            <?php if (!empty($all_post_dated_entries)): ?>
                $('#postdatedTable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": "<?php echo site_url('transaction_inventory/inventory/postdatedAjaxListing') . '?name=' . $this->uri->segment(3) . '&eid=' . $this->uri->segment(4) . '&emonth=' . $this->uri->segment(5); ?>",
                    "deferLoading": <?php echo $postdatedCount; ?>,
                    "bSort":false,
                    "pageLength": 10,
                    "bAutoWidth": false,
                    "oLanguage": {
                        "sSearch":"",
                        "sSearchPlaceholder": "Search here..."
                    }
                }
                    
                });
            <?php endif ?>
            
        });

    </script>
    
<script>


    //delete entry
    $("body").delegate(".delete-entry", "click", function() {
        var entry_id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var delete_type = $(this).attr("delete-type");
        $("#delete-entry-id").val(entry_id);
        $("#delete-entry-type").val(type);
        $("#delete-type").val(delete_type);

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