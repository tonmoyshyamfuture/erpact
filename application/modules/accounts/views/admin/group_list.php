<style>    
    .canstyle{padding-top:20px; padding-left:20px; padding-right:20px; }    
    ol li {padding-left: 5px;}
    .modal-body{padding: 15px 5px}    
</style>
<div class="wrapper2">    
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6"><h1><i class="fa fa-cogs"></i> Chart of Accounts  </h1></div>
            <div class="col-xs-6"> 
                <div class="pull-right">
                <?php
                $permission = ua(106, 'add');
                if ($permission):
                    ?>
                    <input type="button" class="btn btn-primary btn-group  <?php echo ((isset($_GET['_tb']) && $_GET['_tb'] == '1') || (!isset($_GET['_tb']))) ? '' : 'hidden' ?>" value="Add" onclick="window.location.href = '<?php echo site_url('admin/add-accounts-groups'); ?>'" />
                    <input type="button" class="btn btn-info btn-ledger  <?php echo (isset($_GET['_tb']) && $_GET['_tb'] == '2') ? "" : 'hidden' ?>" value="Add" onclick="window.location.href = '<?php echo site_url('admin/add-accounts-ledger'); ?>'" />   
                    
                        
                    <?php if ($group_csv_status): ?>                        
                    <span class="dropdown dd-group">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-angle-down"></i></button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                    <form name="uploadcontact" id="getupload" enctype="multipart/form-data">
                        <input type="file" name="postcsv" id="postcsvGroup" style="display: none;">
                        <a href="javascript:void(0)" class="postImgGroup   <?php echo ((isset($_GET['_tb']) && $_GET['_tb'] == '1') || (!isset($_GET['_tb']))) ? '' : 'hidden' ?>"><i class="fa fa-upload"></i> Upload Group</a>
                    </form>
                        </li>
                        <li>
                    <a href="<?php echo base_url(); ?>accounts/groups/downloadGroupsAsCSV" class="  <?php echo ((isset($_GET['_tb']) && $_GET['_tb'] == '1') || (!isset($_GET['_tb']))) ? '' : 'hidden' ?>"><i class="fa fa-download"></i> Download Group</a>
                    </li>
                    </ul>
                    </span>
                        <?php endif ?>

                    
                    <?php if ($ledger_csv_status): ?> 
                    <span class="dropdown dd-ledger hidden">
                    <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-angle-down"></i></button>
                    <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                    <form name="uploadcontact" id="getupload1" enctype="multipart/form-data">
                        <input type="file" name="postcsv" id="postcsvLedger" style="display: none;">
                        <a href="javascript:void(0)" class="postImgLedger  btn-ledger  <?php echo (isset($_GET['_tb']) && $_GET['_tb'] == '2') ? "" : 'hidden' ?>"><i class="fa fa-upload"></i> Upload Ledger</a>
                    </form>
                    </li>
                    <li>
                    <a href="<?php echo base_url(); ?>accounts/groups/downloadLedgersAsCSV" class="btn  btn-ledger  <?php echo (isset($_GET['_tb']) && $_GET['_tb'] == '2') ? "" : 'hidden' ?>"><i class="fa fa-download"></i> Download Ledger</a>
                    </li>
                    </ul>
                    </span>
                        <?php endif ?>                    
                    
                    
                <?php endif; ?>
                    </div>
            </div>
        </div>
    </section>
    <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Chart of Accounts', '/admin/accounts-groups');
        $this->breadcrumbs->show();
        ?>
    </section>
     <section class="content">          
         <div class="row">
             <div class="col-md-8">
                 <div class="box">
                
                     <div class="box-body table-fullwidth" style="padding-top:0">
                    <div class="accounttabs clearfix">
                        <ul class="nav nav-tabs">
                            <li class="<?php echo (isset($_GET['_tb']) && $_GET['_tb'] == '1') ? 'active' : (!isset($_GET['_tb'])) ? 'active' : '' ?>"><a id="tabgroup" href="#group" data-toggle="tab" data-source="<?php echo base_url('accounts/groups/groupAjaxListing');?>" data-table="refundList"> Groups </a></li>
                           <li class="<?php echo (isset($_GET['_tb']) && $_GET['_tb'] == '2') ? 'active' : '' ?>"><a id="tabledger" href="#ledger" data-toggle="tab" data-source="<?php echo base_url('accounts/groups/ledgerAjaxListing');?>"  data-table="receiveList"> Ledger</a></li>
                        </ul>
                    </div>
                         
                    <div class="table-responsive"> 
                        <!--first tab starts-->
                        <div id="group" class="tab_view  <?php echo (isset($_GET['_tb']) && $_GET['_tb'] == '1') ? 'active in' : (!isset($_GET['_tb'])) ? 'active in' : '' ?>">                         
                            <table id="refundList" class="table table-striped lcol-70">
                                <thead>
                                    <tr>
                                        <th>Group Name</th>
                                        <th class="text-left">Parent Group</th>
                                        <!-- <th width="75">Watch List</th> -->
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($groups)) {
                                        foreach ($groups as $group) {
                                            ?>
                                            <tr id="group_<?php echo $group['id']; ?>">

                                                <td>
                                                    <?php
                                                    $permission = ua(106, 'view');
                                                    if ($permission):
                                                        ?>
                                                        <a href="<?php echo site_url('admin/view-accounts-groups') . "/" . $group['id']; ?>">
                                                            <?php echo (isset($configuration->code_before_name) && $configuration->code_before_name == 1) ? ' (' . $group['group_code'] . ')' : ''; ?>    
                                                            &nbsp;<?php echo $group['group_name'] ?> &nbsp;
                                                            <?php echo (isset($configuration->code_before_name) && $configuration->code_before_name == 0) ? ' (' . $group['group_code'] . ')' : ''; ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-left"><?php
                                                    if (isset($group['parent_group_name'])) {
                                                        ?>
                                                        <?php echo (isset($configuration->code_before_name) && $configuration->code_before_name == 1) ? ' (' . $group['parent_group_code'] . ')' : ''; ?>
                                                        &nbsp; <?php echo $group['parent_group_name'] ?>&nbsp;
                                                        <?php echo (isset($configuration->code_before_name) && $configuration->code_before_name == 0) ? ' (' . $group['parent_group_code'] . ')' : ''; ?>
                                                        <?php
                                                    } else {
                                                        echo '--';
                                                    };
                                                    ?></td>
                                                <!-- <td class="text-center">
                                                    <?php echo (isset($group['watch_list_status']) && $group['watch_list_status'] == '1') ? "Yes" : "-" ?>
                                                </td> -->
                                                <td>
                                                    <?php
                                                    if ($group['operation_status'] == '1') {

                                                    $permission = ua(106, 'delete');
                                                    if ($permission):

                                                            ?>
                                                    <div class="dropdown circle">
                                <a aria-expanded="true" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v"></i></a>                                                        
                                                        <ul class="dropdown-menu tablemenu">
                                                            <li>


                                                                                                <!--<a class="btn btn-default btn-xs" href="<?php echo site_url('admin/view-accounts-groups') . "/" . $group['id']; ?>"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;-->
                                                                                                <!--<a class="btn btn-danger btn-xs white" href="<?php //echo base_url('accounts/groups/remove') . "/" . $group['id'];     ?>" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;-->
                                                            <a href="javascript:void(0);" title="Delete"  data-id="<?php echo $group['id']; ?>" class="delete-group"><span class="text-red"><i class="fa fa-trash" aria-hidden="true"></i></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                            <?php
                                                        endif;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>


                                </tbody>
                            </table> 
                        </div> 
                        <!--first tab ends-->

                        <!--second tab starts-->
                        <div id="ledger" class="hidden tab_view <?php echo (isset($_GET['_tb']) && $_GET['_tb'] == '2') ? 'active in' : '' ?>">                      
                            <table id="receiveList" class="table table-striped lcol-70">
                                <thead>
                                    <tr>												
                                        <th>Ledger Name</th>
                                        <th>Parent Name</th>
                                        <th class="width-70 text-center"></th>
                                        <!-- <th class="width-70 text-center">Show on Watch List</th> -->
                                        <th></th>
                                    </tr>
                                </thead>
                                 <tbody>
                                    <?php
                                    if (isset($ledger_list)) {
                                        foreach ($ledger_list as $row) {
                                            ?>
                                            <tr id="ledger_<?php echo $row['id']; ?>">

                    <!--                                                            <td><a href="<?php echo site_url('admin/company-details-ledger') . "/" . $row['id']; ?>">
                                                <?php echo $row['ladger_name'] . ' (' . $row['ledger_code'] . ')'; ?>
                                                                                    </a></td>-->
                                                <td class="ledger_name">
                                                    <?php
                                                    $permission = ua(106, 'view');
                                                    if ($permission):
                                                    ?>
                                                    <a href="<?php echo site_url('admin/view-accounts-ledger') . "/" . $row['id']; ?>">
                                                        <?php echo (isset($configuration->code_before_name) && $configuration->code_before_name == 1) ? ' (' . $row['ledger_code'] . ')' : ''; ?>                                                                           
                                                        &nbsp; <?php echo $row['ladger_name'] ?> &nbsp;
                                                        <?php echo (isset($configuration->code_before_name) && $configuration->code_before_name == 0) ? ' (' . $row['ledger_code'] . ')' : ''; ?>
                                                    </a>
                                                    <?php if ($row['discontinue'] == 1): ?>
                                                        <span class="text-danger"> (Discontinued)</span>
                                                    <?php endif ?>
                                                <?php endif;?>
                                                </td>

                                                <td><?php echo $row['group_name']; ?></td>
                                                <td class="text-center"><?php echo $row['no_of_ledger']; ?></td>
                                                <!-- <td class="text-center"><input type="checkbox" class="watch-input" value="<?php echo $row['id']; ?>" <?php echo (isset($row['watch_list_status']) && $row['watch_list_status'] == '1') ? "checked" : "" ?>></td> -->
                                                <!-- <td class="text-center"><?php echo (isset($row['watch_list_status']) && $row['watch_list_status'] == '1') ? "Yes" : "-" ?></td> -->
                                                <td class="text-center" style="font-size: 100% !important;">




                                                    <?php if ($row['operation_status'] == '1' && $row['branch_id'] == 0) { ?>    
                                                                                            <!--<a class="btn btn-default btn-xs" href="<?php echo site_url('admin/view-accounts-ledger') . "/" . $row['id']; ?>"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;-->
                                                        <?php
                                                        if ($row['no_of_ledger'] == 0) {
                                                           $permission = ua(106, 'delete');
                                                    if ($permission):
                                                                ?>
                                                        <div class="dropdown circle">
                                                        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-v"></i></a>
                                                        <ul class="dropdown-menu tablemenu">
                                                            <li>                                                                <!--<a class="btn btn-danger btn-xs white" href="<?php //echo base_url('accounts/accounts/remove') . "/" . $row['id'];    ?>" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                                                                <a href="javascript:void(0);" title="Delete"  data-id="<?php echo $row['id']; ?>" class="delete-ledger"><span class="text-red"><i class="fa fa-trash" aria-hidden="true"></i></span></a>
                                                                <?php if ($row['discontinue'] == 0): ?>
                                                                    <a href="javascript:void(0);" title="Discontinue"  data-id="<?php echo $row['id']; ?>" class="discontinue-ledger"><span class="text-red"><i class="fa fa-ban" aria-hidden="true"></i></span></a>
                                                                <?php endif ?>                                                                                    
                                                                </li>
                                                            </ul>
                                                            </div>

                                                            <?php endif; ?>    
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>


                                </tbody>
                            </table> 
                        </div> 
                        <!--second tab ends-->  
                    </div>
                </div>
                
            </div>
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
</div>


<!--ledger maim start-->



<script>
    
    
</script>
<!-- Delete Confirm Modal -->
<div id="deleteGroupConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo site_url('accounts/groups/ajax_delete_group'); ?>" method="post" id="delete-group-form">
                <div class="modal-header" style="background: #4b83ee;color: #fff">
                    <h4 class="modal-title">Confirmation</h4>
                    <input type="hidden" value="" id="delete-group-id" name="delete_entry_id">
                </div>
                <div class="modal-body">
                    <p style="font-size:16px;">Are you sure to delete this entry?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="submit" class="btn ladda-button btn-primary" data-color="blue" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="deleteLedgerConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo site_url('accounts/accounts/ajax_delete_ledger'); ?>" method="post" id="delete-ledger-form">
                <div class="modal-header" style="background: #4b83ee;color: #fff">
                    <h4 class="modal-title">Confirmation</h4>
                    <input type="hidden" value="" id="delete-ledger-id" name="delete_entry_id">
                </div>
                <div class="modal-body">
                    <p id="deleteLedgerText" style="font-size:16px;">Are you sure to delete this entry?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="submit" class="btn ladda-button btn-primary" data-color="blue" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="discontinueLedgerConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo site_url('accounts/accounts/ajax_discontinue_ledger'); ?>" method="post" id="discontinue-ledger-form">
                <div class="modal-header" style="background: #4b83ee;color: #fff">
                    <h4 class="modal-title">Confirmation</h4>
                    <input type="hidden" value="" id="discontinue-ledger-id" name="discontinue_entry_id">
                </div>
                <div class="modal-body">
                    <p style="font-size:16px;">Are you sure to discontinue this entry?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="submit" class="btn ladda-button btn-primary" data-color="blue" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="watchLedgerConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo site_url('accounts/groups/ajax_save_watch_list'); ?>" method="post" id="save-watchlist-ledger-form">
                <div class="modal-header" style="background: #4b83ee;color: #fff">
                    <h4 class="modal-title">Confirmation</h4>
                    <input type="hidden" value="" id="watch-ledger-id" name="watch_ledger_id">
                    <input type="hidden" value="" id="watch-status" name="watch_status">
                </div>
                <div class="modal-body">
                    <p style="font-size:16px;" id="confirm-msg">Are you sure this ledger showing on watch list?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="submit" class="btn ladda-button save-watch-list-form" data-color="mint" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="watchGroupConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo site_url('accounts/groups/ajax_save_group_watch_list'); ?>" method="post" id="save-watchlist-group-form">
                <div class="modal-header" style="background: #4b83ee;color: #fff">
                    <h4 class="modal-title">Confirmation</h4>
                    <input type="hidden" value="" id="watch-group-id" name="watch_group_id">
                    <input type="hidden" value="" id="watch-group-status" name="watch_group_status">
                </div>
                <div class="modal-body">
                    <p style="font-size:16px;" id="group-confirm-msg">Are you sure this Group showing on watch list?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="submit" class="btn ladda-button save-group-watch-list-form" data-color="mint" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets') ?>/js/jquery.dataTables.min.js"></script>

<script>
    $(".watch-group").click(function() {
        var group_id = $(this).val();
        $("#watch-group-id").val(group_id);
        if (this.checked === true) {
            $("#watch-group-status").val('1');
            $("#group-confirm-msg").html('Are you sure this Group showing on watch list?');

        } else {
            $("#watch-group-status").val('0');
            $("#group-confirm-msg").html('Are you sure this Group remove from watch list?');
        }
        $("#watchGroupConfirm").modal('show');
    });
    $("#save-watchlist-group-form").submit(function(event) {
        event.preventDefault();
        var l = Ladda.create(document.querySelector('.save-group-watch-list-form'));
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
                    $("#watchGroupConfirm").modal('hide');
                    Command: toastr["success"](data.message);
                } else {
                    Command: toastr["error"]('Error Occured please try again.');
                }
            }
        });

    });
    //
    $(".watch-input").click(function() {
        var ledger_id = $(this).val();
        $("#watch-ledger-id").val(ledger_id);
        if (this.checked === true) {
            $("#watch-status").val('1');
            $("#confirm-msg").html('Are you sure this ledger showing on watch list?');

        } else {
            $("#watch-status").val('0');
            $("#confirm-msg").html('Are you sure this ledger remove from watch list?');
        }
        $("#watchLedgerConfirm").modal('show');
    });
    $("#save-watchlist-ledger-form").submit(function(event) {
        event.preventDefault();
        var l = Ladda.create(document.querySelector('.save-watch-list-form'));
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
                    $("#watchLedgerConfirm").modal('hide');
                    Command: toastr["success"](data.message);
                } else {
                    Command: toastr["error"]('Error Occured please try again.');
                }
            }
        });

    });
</script>
<script>
    $("body").delegate(".delete-ledger", "click", function() {
        var ledger_id = $(this).attr("data-id");
        console.log(ledger_id);
        let url = "<?php echo base_url('accounts/accounts/getNoOfVoucher')?>";
        $.ajax({
            url: url,
            type: 'POST',
            data: "ledger_id=" + ledger_id,
            dataType: 'json',
            success: function(data) {
                if (data.res == 'success') {
                    $("#delete-ledger-id").val(ledger_id);
                    $("#deleteLedgerConfirm").modal('show');
                    $('#deleteLedgerText').text('If you delete this ledger, first delete realeted voucher(s).');
                    $("button[type=submit]").attr('disabled', true);
                } else {
                    $("#delete-ledger-id").val(ledger_id);
                    $("#deleteLedgerConfirm").modal('show');
                    $('#deleteLedgerText').text('Are you sure to delete this ledger ?');
                    $("button[type=submit]").attr('disabled', false);
                }
            }
        });
        
    });
    $(".discontinue-ledger").click(function() {
        var ledger_id = $(this).attr("data-id");
        $("#discontinue-ledger-id").val(ledger_id);
        $("#discontinueLedgerConfirm").modal('show');
    });
    $("#delete-ledger-form").submit(function(event) {
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
                if (data.res == 'success') {
                    $('#ledger_' + data.ledger_id).remove();
                    $("#deleteLedgerConfirm").modal('hide');
                    Command: toastr["success"](data.message);
                } else {
                    Command: toastr["error"]('Error Occured please try again.');
                }
            }
        });

    });
    $("#discontinue-ledger-form").submit(function(event) {
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
                if (data.res == 'success') {
                    // $('#ledger_' + data.ledger_id).remove();
                    $(".discontinue-ledger[data-id="+data.ledger_id+"]").hide();
                    $('#ledger_' + data.ledger_id +' .ledger_name').append(' (Discontinued)').css('color', '#a94442');
                    $("#discontinueLedgerConfirm").modal('hide');
                    Command: toastr["success"](data.message);
                } else {
                    Command: toastr["error"]('Error Occured please try again.');
                }
            }
        });

    });
</script>
<script>
    $("body").delegate(".delete-group", "click", function() {
        var group_id = $(this).attr("data-id");
        $("#delete-group-id").val(group_id);
        $("#deleteGroupConfirm").modal('show');
    });
    $("#delete-group-form").submit(function(event) {
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
                if (data.res == 'success') {
                    $('#group_' + data.group_id).remove();
                    $("#deleteGroupConfirm").modal('hide');
                    Command: toastr["success"](data.message);
                } else {
                    Command: toastr["error"]('Error Occured please try again.');
                }
            }
        });

    });
</script>

<!-- download csv -->
<script src="<?php echo base_url(); ?>assets/js/jquery.tabletoCSV.js"></script>
<!-- <script src="https://www.healthspring.in/assets/customjs/jquery.tabletoCSV.js"></script> -->
<script type="text/javascript">
    
    $(document).ready(function(){
        var i =0;

        $('.postImgGroup').on('click', function(e){
            $('#postcsvGroup').click();
        });

        $('#postcsvGroup').on('change', function(event){
            var filename = $("#postcsvGroup").val();
            var extension = filename.replace(/^.*\./, '');
            var msg = '';
            if(extension!='csv')
            {
                msg = 'File type is not allowed';
                Command: toastr["error"](msg);

            }else{
                var formData = new FormData();
                var filedata = $(this)[0].files[0];
                formData.append('formData', filedata);
                $.ajax({
                    url : '<?php echo base_url();?>admin/save-the-group',
                    type : 'POST',
                    dataType: "JSON",
                    processData: false,  
                    contentType: false,
                    // async:false,
                    enctype: 'multipart/form-data',
                    data : formData,
                    success : function(data) {
                        // console.log(data);
                        if (data.res == 'error') {
                            Command: toastr["error"](data.msg);
                        } else {
                            Command: toastr["success"](data.msg);
                            setTimeout(function(){ location.reload(); }, 2000);
                        }
                        $("form#getupload")[0].reset();
                    },
                    error: function(response) {
                        // console.log(data);
                    },
                    beforeSend: function(){
                       $('.task-loader').show();
                    },
                    complete: function(){
                       $('.task-loader').hide();
                    }
                });
            }

        });

        $('.downloadGroup').click(function(){
            $("#groupListTable").tableToCSV('group');
        });
    });



</script>

<script type="text/javascript">
    
    $(document).ready(function(){

        $('.postImgLedger').click(function(){
            $('#postcsvLedger').click();            
        });

        $('#postcsvLedger').change(function(){

            var filename = $("#postcsvLedger").val();
            var extension = filename.replace(/^.*\./, '');
            var msg = '';
            if(extension!='csv')
            {
                msg = 'File type is not allowed';
                Command: toastr["error"](msg);
            }else{
                var formData = new FormData();
                var filedata = $(this)[0].files[0];
                formData.append('formData', filedata);
                $.ajax({
                    url : '<?php echo base_url();?>admin/save-the-ledger',
                    type : 'POST',
                    dataType: 'JSON',
                    processData: false,  
                    contentType: false,
                    enctype: 'multipart/form-data',
                    data : formData,
                    success : function(data) {
                        console.log(data);
                        if (data.res == 'error') {
                            Command: toastr["error"](data.msg);
                        } else {
                            Command: toastr["success"](data.msg);
                            setTimeout(function(){ location.reload(); }, 2000);
                        }
                        $("form#getupload1")[0].reset();
                    },
                    error: function(res) {
                        console.log(res);
                    },
                    beforeSend: function(){
                       $('.task-loader').show();
                    },
                    complete: function(){
                       $('.task-loader').hide();
                    }
                });
            }
        });
    });
    
    
 $(document).ready(function(){
 
 
 $("#tabgroup").click(function() {
        var tableId = $(this).data("table");
        var source = $(this).data("source");
        let rowCount = "<?php echo $groupCount; ?>"
        initiateTable(tableId, source,rowCount);
        $(".btn-ledger").addClass('hidden');
        $(".dd-group").removeClass('hidden');
        $(".dd-ledger").addClass('hidden');
        $(".btn-group").removeClass('hidden');
        
        $('#group').removeClass('hidden')
        $('#ledger').addClass('hidden')
    });
    
    $("#tabledger").click(function() {
        var tableId = $(this).data("table");
        var source = $(this).data("source");
        let ledgerCount = "<?php echo $ledgerCount; ?>"
        initiateTable(tableId, source, ledgerCount);
        $(".btn-ledger").removeClass('hidden');
        $(".dd-group").addClass('hidden');
        $(".dd-ledger").removeClass('hidden')
        $(".btn-group").addClass('hidden')
        
        $('#ledger').removeClass('hidden')
        $('#group').addClass('hidden')
        
    });
 initiateTable("refundList", "<?php echo base_url('accounts/groups/groupAjaxListing');?>",<?php echo $groupCount; ?>);
    
        <?php 
//            if(isset($_GET['_tb']) && $_GET['_tb'] == '1'){ ?>
                    console.log('group_tb_1')
                initiateTable("refundList", "<?php echo base_url('accounts/groups/groupAjaxListing');?>",<?php echo $groupCount; ?>);
        <?php // }elseif(isset($_GET['_tb']) && $_GET['_tb'] == '2'){ ?>
//             console.log('ledger')
//                initiateTable("receiveList", "<?php echo base_url('accounts/groups/ledgerAjaxListing');?>",<?php echo $ledgerCount; ?>);
        <?php // }else{ ?>
//            console.log('group')
//                initiateTable("refundList", "<?php echo base_url('accounts/groups/groupAjaxListing');?>",<?php echo $groupCount; ?>);
        <?php // } ?>

});


    function initiateTable(tableId, source,rowCount = 0){
        var table = $('#'+tableId).DataTable( {
                 "bSort": false,
                 "processing": true,
                 "serverSide": true,
                 "retrieve": true,
                 "pageLength": 10,
                 "ajax": source,
                 "deferLoading": rowCount,
                 "bSearchable":true,
                 "bFilter": true,
                 "bLengthChange": false,
                 "bInfo": true,
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
    }


</script>