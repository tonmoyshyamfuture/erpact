<style>
    .modal-body {
        max-height: calc(100vh - 210px);
        overflow-y: auto;
    }
</style>
<div class="wrapper2">
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <i class="fa fa-users"></i> All Users</h1>  
            </div>
            <div class="col-xs-6"> 
                <div class="pull-right">
                    <a href="" class="btn btn-danger hidden checkdelbtn"><i class="fa fa-times"></i></a>

<a class="btn btn-primary" href="<?php echo base_url('admin/add-user'); ?>" >Add</a>

                </div>
            </div> 
        </div>           
    </section>
    <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Users', '/admin/users');
        $this->breadcrumbs->show();
        ?>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="box">                              
                            
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#home">Active</a></li>
                                <li><a data-toggle="tab" href="#menu1" style="<?php if(empty($invited)){ echo "display:none;";} ?>">Invited</a></li>
                            </ul>

                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <div class="box-body table-fullwidth"> 
                                        <div class="table-responsive multipagination">
                                            <table id="example00" class="table table-striped fcol-50 lcol-70">
                                                <thead>
                                                    <tr>                      
                                                        <th></th>
                                                        <th>Name</th>
                                                        <th>Email</th>                                      
                                                        <th>Role</th>
                                                        <th></th> 
                                                        <th>Log</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($subadmins)) {
                                                        $x = 0;
                                                        foreach ($subadmins as $key => $subadmin) {
                                                            ?>
                                                            <tr id="row_<?php echo $subadmin->sass_user_id; ?>">

                                                                <td><img src="<?php
                                                                    if ($subadmin->image != "") {
                                                                        echo SAAS_URL . "assets/upload/user_profile/" . $subadmin->image;
                                                                    } else {
                                                                        echo SAAS_URL . "assets/admin/images/svg/user-grey.svg";
                                                                    }
                                                                    ?>"></td>
                                                                <td><?php echo $subadmin->fname . " " . $subadmin->lname; ?></td>                          

                                                                <td><?php echo $subadmin->email; ?></td>                          
                                                                <td>
                                                                    <button class="btn btn-primary btn-xs edit-roll" data-id="<?php echo $subadmin->sass_user_id; ?>"> <i class="fa fa-pencil"></i></button>
                                                                </td>
                                                                <td>
                                                                    <?php if ($subadmin->id == 1): ?>
                                                                        <span class="text-red">Admin</span>
                                                                    <?php else: ?>
                                                                        <button class="btn btn-danger btn-xs delete-user" data-id="<?php echo $subadmin->sass_user_id; ?>"> <i class="fa fa-trash"></i></button>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><a href="javascript:void(0);" data-id="<?php echo $subadmin->id; ?>" class="btn btn-info btn-xs logButton" title="Log"><i class="fa fa-file-text-o" aria-hidden="true"></i></a></td>
                                                            </tr>

                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>                    
                                            </table>
                                        </div>
                                    </div><!-- /.box-body -->
                                    <!-- this div is required for holding pagination -->
                                    

                                    
                                </div>
                                <div id="menu1" class="tab-pane fade">
                                    <div class="box-body table-fullwidth"> 
                                        <div class="table-responsive multipagination">    
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>                      
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($invited)) : ?>
                                                        <?php foreach ($invited as $user) : ?>
                                                            <tr>
                                                                <td><?php echo $user->fname . " " . $user->lname; ?> </td>
                                                                <td><?php echo $user->emailid; ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>                    
                                            </table>
                                        </div>
                                    </div><!-- /.box-body -->
                                </div>
                            </div>
                            
                            
                            
                            
                        
                    
                </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="col-md-4">
                <div class="box settings-help">
                    <div class="box box-solid">
                        <div class="box-header">
                            <h3 class="box-title"> <i class="fa fa-info-circle"></i> Help</h3>
                            <!--<button type="button" class="btn btn-xs btn-info pull-right" data-toggle="modal" data-target="#logModal">Log</button>-->
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
        </div><!-- /.row -->
    </section><!-- /.content -->
    <div id="dialog_status"><div id="dialog_status_msg"></div></div> 

</div>
<!-- Modal -->
<div id="rollModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <form method="POST" id="module-access-form" action="<?php echo site_url('role/admin/save_module_access'); ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Roll</h4>
                </div>
                <div class="modal-body" style="padding:15px 0">
                    <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $this->session->userdata('branch_id') ?>">
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sl. No.</th>
                                <th>Module</th>
                                <th><input type="checkbox" class="all-view" > View</th>
                                <th><input type="checkbox" class="all-add"> Add</th>
                                <th><input type="checkbox" class="all-edit"> Edit</th>  
                                <th><input type="checkbox" class="all-delete"> Delete</th>
                                <th><input type="checkbox" class="all-all"> All</th>
                            </tr>
                        </thead>
                        <tbody id="dynamic-module">

                        </tbody>
                    </table>                    

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary pull-right ladda-button module-submit-btn" data-color="blue" data-style="expand-right" data-size="s">Save</button>
                </div>
            </form>
        </div>

    </div>
</div>
<div id="deleteUserConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo site_url('role/admin/ajax_delete_user'); ?>" method="post" id="delete-user-form">
                <div class="modal-header" style="background: #367fa9;color: #fff">
                    <h4 class="modal-title">Confirmation</h4>
                    <input type="hidden" value="" id="delete-user-id" name="delete_user_id">
                </div>
                <div class="modal-body">
                    <p style="font-size:16px;" id="group-confirm-msg">Are you sure want to delete this user?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="submit" class="btn ladda-button delete-user-btn" data-color="blue" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Log Modal -->
<div id="logModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 60%;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closeLog" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Log Details</h4>
            </div>
            <div class="modal-body pdf_class" data-pdf="Log">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger delete_log" style="display: none;">Delete</button>
                <button type="button" class="btn btn-primary download-pdf"><i class="fa fa-print"></i> Export</button>
                <button type="button" class="btn btn-default closeLog" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<div id="deleteLogConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <!-- <form action="<?php echo site_url('role/admin/ajax_delete_user'); ?>" method="post" id="delete-user-form"> -->
                <div class="modal-header" style="background: #367fa9;color: #fff">
                    <h4 class="modal-title">Confirmation</h4>
                    <input type="hidden" value="" id="delete-user-id" name="delete_user_id">
                </div>
                <div class="modal-body">
                    <p style="font-size:16px;" id="group-confirm-msg">Are you sure want to delete this log?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="button" class="btn ladda-button delete_log_confirm" data-color="blue" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
            <!-- </form> -->
        </div>
    </div>
</div>

<script type="text/javascript">
    $("body").delegate(".edit-roll", "click", function() {
        var user_id = $(this).attr("data-id");
        var branch_id = $("#branch_id").val();
        $.ajax({
            method: "POST",
            url: "<?php echo site_url('role/admin/ajax_get_module'); ?>",
            data: {branch_id: branch_id, user_id: user_id},
            dataType: "json",
        }).done(function(data) {
            if (data.res == 'error') {
                $.each(data.message, function(index, value) {
                    Command: toastr["error"](value);
                });
            } else {
                $("#user_id").val(user_id);
                $("#dynamic-module").html(data.html);
                $("#rollModal").modal("show");
            }
        });
    });
    $("body").delegate(".all-view", "change", function() {
        if (this.checked) {
            $(".view").prop('checked', true);
        } else {
            $(".view").prop('checked', false);
        }
    });
    $("body").delegate(".all-add", "change", function() {
        if (this.checked) {
            $(".add").prop('checked', true);
        } else {
            $(".add").prop('checked', false);
        }
    });
    $("body").delegate(".all-edit", "change", function() {
        if (this.checked) {
            $(".edit").prop('checked', true);
        } else {
            $(".edit").prop('checked', false);
        }
    });
    $("body").delegate(".all-delete", "change", function() {
        if (this.checked) {
            $(".delete").prop('checked', true);
        } else {
            $(".delete").prop('checked', false);
        }
    });
    $("body").delegate(".all", "change", function() {
        var self = $(this);
        if (this.checked) {
            self.closest('tr').find("input[type='checkbox']").prop('checked', true);
        } else {
            self.closest('tr').find("input[type='checkbox']").prop('checked', false);
        }
    });
    $("body").delegate(".all-all", "change", function() {
        if (this.checked) {
            $(".view").prop('checked', true);
            $(".add").prop('checked', true);
            $(".edit").prop('checked', true);
            $(".delete").prop('checked', true);
            $(".all").prop('checked', true);
        } else {
            $(".view").prop('checked', false);
            $(".add").prop('checked', false);
            $(".edit").prop('checked', false);
            $(".delete").prop('checked', false);
            $(".all").prop('checked', false);
        }
    });



    $("#module-access-form").submit(function(event) {
        event.preventDefault();
        var l = Ladda.create(document.querySelector('.module-submit-btn'));
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
                if (data.res == 'error') {
                    $.each(data.message, function(index, value) {
                        Command: toastr["error"](value);
                    });
                } else if (data.res == 'save_err') {
                    Command: toastr["error"](data.message);
                } else {
                    Command: toastr["success"](data.message);
                }
            }
        });

    });

    //delete entry
    $(".delete-user").click(function() {
        var user_id = $(this).attr("data-id");
        $("#delete-user-id").val(user_id);
        $("#deleteUserConfirm").modal('show');
    });

    $("#delete-user-form").submit(function(event) {
        event.preventDefault();
        var l = Ladda.create(document.querySelector('.delete-user-btn'));
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
                    $('#row_' + data.user_id).remove();
                    $("#deleteUserConfirm").modal('hide');
                    Command: toastr["success"](data.message);
                } else {
                    Command: toastr["error"]('Error Occured please try again.');
                }
            }
        });

    });
    
    
    $(document).ready(function(){
        $(".logButton").click(function(){
            var user_id = $(this).data('id');
//            console.log(user_id);
            $.ajax({
                url:"<?php echo base_url(); ?>admin/getLogByUserId",
                type:"POST",
                data:{user_id: user_id},
                success: function(response){
                    $("#logModal .modal-body").html(response);
                    $("#logModal").modal('show');
                },
                error: function(response){
                    
                }
            });
            
//            $("#logModal").modal('show');
        });
    });
</script>

<script>
    $(document).ready(function() {

        // manual checkbox for delete logs
        $('#logModal').delegate('input.log_check[type=checkbox]', 'change',  function () {
            var len = $('#logModal input.log_check[type=checkbox]:checked').length;
            if (len > 0) {
                $(".delete_log").show();
            } else if (len === 0) {
                $(".delete_log").hide();
            }
        }).trigger('change');

        // delete all logs at once
        $('#logModal').delegate('input.log_check_all[type=checkbox]', 'change',  function () {
            if($(this).prop("checked") == true){
                $('input.log_check[type=checkbox]').each(function () {
                   $(this).prop("checked", true);
                });
                // $(".delete_log").show();
                var len = $('#logModal input.log_check[type=checkbox]:checked').length;
                if (len > 0) {
                    $(".delete_log").show();
                } else if (len === 0) {
                    $(".delete_log").hide();
                }
            }else{
                $('input.log_check[type=checkbox]').each(function () {
                   $(this).prop("checked", false);
                });
                $(".delete_log").hide();
            }
        }).trigger('change');

        $(".delete_log").on('click', function(e) {
            e.preventDefault();
            $("#deleteLogConfirm").modal('show');
        });
        // trigger the delete log using ajax
        $(".delete_log_confirm").on('click', function(e) {
            e.preventDefault();
            var input_check = $("input[name='log_check[]']:checked")
              .map(function(){return $(this).val();}).get();
            $.ajax({
                url: "<?php echo base_url(); ?>users/admin/deleteLogs",
                type: "POST",
                data: {ids: input_check},
                success: function(response) {
                    $("#deleteLogConfirm").modal('hide');
                    if (response == "TRUE") {
                        $(".delete_log").hide();
                        $(".log_check_all").prop('checked', false);
                        Command:toastr['success']("Log deleted");

                        $('input.log_check[type=checkbox]:checked').each(function () {
                           $(this).parent('td').parent('tr').hide();
                        });
                    } else {
                        Command:toastr['error']("Something went wrong...try again!");
                    }

                },
                error: function(response) {
                    // console.log(response);
                }
            });
        });

        $(".closeLog").on('click', function() {
            $(".delete_log").hide();
        });


    });
</script>