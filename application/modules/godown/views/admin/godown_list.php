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
                <h1> <i class="fa fa-bus" aria-hidden="true"></i>Godowns</h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <?php
                    $permission = ua(116, 'add');
                    if ($permission):
                        ?>
                        <!-- <input type="button" class="btn btn-primary" value="Add" onclick="window.location.href = '<?php echo site_url('admin/godown-add'); ?>'" /> -->
                        <button class="btn btn-primary add-godown">Add</button>

                        <?php if ($csv_status): ?>
                            <span class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-angle-down"></i></button>
                                <ul class="dropdown-menu dropdown-menu-right">                        
                                    <li>
                                        <form name="uploadgodown" id="getupload" enctype="multipart/form-data">
                                            <input type="file" name="postcsv" id="postcsvGodown" style="display: none;">
                                            <a href="javascript:void(0)" class="postImgGodown"><i class="fa fa-upload"></i> Upload Godown</a>
                                        </form>
                                    </li><li>
                                        <a href="<?php echo base_url(); ?>godown/admin/downloadGodownsAsCSV" class=""><i class="fa fa-download"></i> Download Godown</a>                                    
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
        $this->breadcrumbs->push('Godowns', '/admin/godown');
        $this->breadcrumbs->show();
        ?>
    </section>
    <!-- Main content --> 
    <section class="content">
        <div class="row"> 
            <div class="col-md-8">
                <div class="box">                
                    <div class="box-body table-fullwidth">
                        <div class="table-responsive">
                            <table id="exampleGodown" class="table table-striped  lcol-70 ">
                                <?php if (!empty($godowns)) { ?>
                                    <thead>
                                        <tr>
                                            <th>Godown</th>
                                            <th>Parent</th>
                                            <th>Address</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                <?php } ?>
                                <tbody>
                                    <?php
                                    if (!empty($godowns)) {
                                        foreach ($godowns as $godown) {
                                            ?> 
                                            <tr>
                                                <td>
                                                    <?php
                                                    $permission = ua(116, 'edit');
                                                    if ($permission):
                                                        ?>
                                                        <!-- <a  href="<?php echo site_url('admin/godown-add') . "/" . $godown['id']; ?>"><?php echo $godown['name']; ?></a> -->
                                                        <a  href="javascript:void(0)" class="edit-godown" data-id="<?php echo $godown['id']; ?>"><?php echo $godown['name']; ?></a>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $godown['parent_name']; ?></td>
                                                <td><?php echo (strlen($godown['address']) > 50) ? substr($godown['address'], 0, 50) . '...' : $godown['address'] ; ?></td>
                                                <td>
                                                    <?php
                                                    $permission = ua(116, 'delete');
                                                    if ($permission):
                                                        ?>
                                                        <div class="dropdown circle">
                                                            <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                                <i class="fa fa-ellipsis-v"></i></a>
                                                            <ul class="dropdown-menu tablemenu">
                                                                <li>
                                                                    <a onclick="delete_godown('<?php echo $godown['id']; ?>');" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                                </li>
                                                            </ul>
                                                        </div>


                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else {
                                        ?>
                                    <div class="empty-data">                          
                                        <img class="img-responsive" src="<?php echo site_url(); ?>assets/images/empty-data.png">
                                    </div>
                                    <?php
                                }
                                ?>
                                </tbody>

                            </table>
                        </div>
                    </div><!-- /.box-body -->                    
                </div><!-- /.box -->
            </div><!-- /.col -->

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
        </div><!-- /.row -->
    </section><!-- /.content -->

</div>


<script type="text/javascript" src="<?php echo base_url('assets') ?>/js/jquery.dataTables.min.js"></script>

<script>
    function delete_godown(id) {
        $("#myAlert").on('shown.bs.modal', function () {
            $("#modal_confirm").click(function () {
                window.location.href = "<?php echo base_url('admin/godown-delete'); ?>/" + id;
            });

            $("#notification_heading").html("Confirmation");
            $("#notification_body").html("Do you want to delete this godown?").css('font-size', '16px');
        }).modal("show");
    }
</script>
<div class="modal fade"  id="myAlert">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notification_heading"></h5>
            </div>
            <div class="modal-body">
                <p id="notification_body"></p>
            </div>
            <div class="modal-footer">
                <a data-dismiss="modal" id="modal_confirm" class="btn btn-primary" href="#">Confirm</a> 
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Godown add Modal -->
<div id="godownModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        $('.postImgGodown').click(function () {
            $('#postcsvGodown').click();
        });

        $('#postcsvGodown').change(function () {
            var filename = $("#postcsvGodown").val();
            var extension = filename.replace(/^.*\./, '');
            var msg = '';
            if (extension != 'csv')
            {
                msg = 'File type is not allowed';
                Command: toastr["error"](msg);
            } else {
                var formData = new FormData();
                var filedata = $(this)[0].files[0];
                formData.append('formData', filedata);
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/save-the-godown',
                    type: 'POST',
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    async: false,
                    data: formData,
                    success: function (data) {
                        // console.log(data);
                        if (data.res == 'error') {
                            Command: toastr["error"](data.msg);
                        } else {
                            Command: toastr["success"](data.msg);
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        }
                        $("#getupload")[0].reset();
                    },
                    error: function (res) {
                        // console.log(res);
                    },
                    beforeSend: function(){
                       $('.task-loader').show()
                    },
                    complete: function(){
                       $('.task-loader').hide();
                    }
                });
            }
        });
        
    });
    $(document).ready(function() {

        <?php if (!empty($godowns)): ?>
            $('#exampleGodown').DataTable( {
                "bSort": false,
                "processing": true,
                "serverSide": true,
                "ajax": "<?php echo site_url('godown/admin/ajaxListing');?>",
                "deferLoading": <?php echo $count; ?>,
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
    $(document).ready(function(){

        // godown add modal submit
        $("body").delegate('.godownModalSubmit', 'click', function(event) {
            event.preventDefault();
            var l = Ladda.create(document.querySelector('.ladda-button'));
            l.start();
            var form = $("#godown_add_form"),
                    url = form.attr('action'),
                    data = form.serialize();
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(data) {
                    l.stop();
                    $("#godownModal").modal('hide');
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
                        window.location.replace("<?php echo base_url('admin/godown.aspx'); ?>");
                    }
                }
            });

        });

        // pop the modal for add godown on click of add button
        $(".add-godown").on('click', function(e) {
            e.preventDefault();
            // $("#godownModal").modal('show');
            $.ajax({
                url:"<?php echo base_url('godown/admin/getAddGodown'); ?>",
                type: 'POST',
                data: {},
                success: function(res) {
                    $("#godownModal .modal-content").html(res);
                    $("#godownModal").modal('show');
                },
                error: function(res) {

                }
            });
        });

        // modal pop-up for godown edit after click on godown name
        $("body").delegate('.edit-godown', 'click', function(e) {
            e.preventDefault();
            var godown_id = $(this).data('id');
            $.ajax({
                url:"<?php echo base_url('godown/admin/getEditGodown'); ?>",
                type: 'POST',
                data: { godown_id : godown_id },
                success: function(res) {
                    $("#godownModal .modal-content").html(res);
                    $("#godownModal").modal('show');
                },
                error: function(res) {

                }
            });
        });

    });
</script>