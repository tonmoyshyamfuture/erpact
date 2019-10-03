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
                <h1> <i class="fa fa-list"></i> Vouchers List </h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <a href="" class="btn btn-danger hidden checkdelbtn"><i class="fa fa-times"></i></a>
                    <?php
                    $permission = ua(113, 'add');
                    if ($permission):
                        ?>
                        <input type="button" class="btn btn-primary" value="Add" onclick="window.location.href = '<?php echo site_url('admin/sub-voucher-add'); ?>'" />

                        <?php if ($csv_status): ?>
                            <span class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-angle-down"></i></button>
                                <ul class="dropdown-menu dropdown-menu-right">                        
                                    <li>
                                        <form name="uploadvoucher" id="getupload" enctype="multipart/form-data">
                                            <input type="file" name="postcsv" id="postcsvVoucher" style="display: none;">
                                            <a href="javascript:void(0)" class="postImgVoucher"><i class="fa fa-upload"></i> Upload Voucher</a>
                                        </form>
                                    </li><li>
                                        <a href="<?php echo base_url(); ?>accounts/entries/downloadVouchersAsCSV" class=""><i class="fa fa-download"></i> Download Voucher</a>
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
        $this->breadcrumbs->push('Vouchers List', '/admin/vouchers');
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
                            <table id="example00" class="table table-striped lcol-70">
                                <thead>
                                    <tr>
                                        <th>Voucher Name</th>                        
                                        <th>Code</th>              
                                        <th>Parent Name</th>              
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sub_vouchers as $sub_voucher) {
                                        ?> 
                                        <tr id="row_<?php echo $sub_voucher['id']; ?>">
                                            <td><?php echo $sub_voucher['voucher_name']; ?></td>
                                            <td><?php echo!empty($sub_voucher['voucher_no']) ? $sub_voucher['voucher_no'] : '--'; ?></td>
                                            <td><?php echo!empty($sub_voucher['parent_voucher_name']) ? $sub_voucher['parent_voucher_name'] : '--'; ?></td>

                                            <td>
                                                <div class="dropdown circle">
                                                    <a aria-expanded="true" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-v"></i></a>                                                        
                                                    <ul class="dropdown-menu tablemenu">
                                                        <?php if ($sub_voucher['voucher_type'] != '0') { ?>

                                                            <?php
                                                            $permission = ua(113, 'edit');
                                                            if ($permission):
                                                                ?><li>
                                                                    <a href="<?php echo site_url('admin/edit-parent-voucher') . "/" . $sub_voucher['id']; ?>"><i class="fa fa-pencil"></i></a>
                                                                </li>
                                                            <?php endif; ?>                                                                                                                 
                                                            <?php
                                                            $permission = ua(113, 'delete');
                                                            if ($permission):
                                                                ?>
                                                                <li>
                                                                    <a href="javascript:void(0);" title="Delete"  data-id="<?php echo $sub_voucher['id']; ?>" class="delete-entry"><span class="text-red"><i class="fa fa-trash" aria-hidden="true"></i></span></a>
                                                                </li>
                                                            <?php endif; ?>
                                                        <?php }else { ?>

                                                            <?php
                                                            $permission = ua(113, 'edit');
                                                            if ($permission):
                                                                ?><li>
                                                                    <a href="<?php echo site_url('admin/edit-parent-voucher') . "/" . $sub_voucher['id']; ?>"><i class="fa fa-pencil"></i></a>
                                                                <?php endif; ?>  
                                                            </li>

                                                        <?php }
                                                        ?>
                                                    </ul>
                                                </div> 
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>                    
                </div><!-- /.box -->
            </div><!-- /.col -->
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
        </div><!-- /.row -->
    </section><!-- /.content -->

</div>
<!-- Delete Confirm Modal -->
<div id="deleteConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo site_url('accounts/entries/ajax_delete_sub_voucher'); ?>" method="post" id="delete-entry-form">
                <div class="modal-header" style="background: #367fa9;color: #fff">
                    <h4 class="modal-title">Confirmation</h4>
                    <input type="hidden" value="" id="delete-entry-id" name="delete_entry_id">
                </div>
                <div class="modal-body">
                    <p style="font-size:16px;">Are you sure to delete this Voucher?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="submit" class="btn ladda-button btn-primary" data-color="blue" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(".delete-entry").click(function () {
        var voucher_id = $(this).attr("data-id");
        $("#delete-entry-id").val(voucher_id);
        $("#deleteConfirm").modal('show');
    });
    $("#delete-entry-form").submit(function (event) {
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
            success: function (data) {
                l.stop();
                if (data.res == 'success') {
                    $('#row_' + data.voucher_id).remove();
                    $("#deleteConfirm").modal('hide');
                    Command: toastr["success"](data.message);
                } else {
                    Command: toastr["error"](data.message);
                }
            }
        });

    });
</script>

<script type="text/javascript">

    $(document).ready(function () {

        $('.postImgVoucher').click(function () {
            $('#postcsvVoucher').click();
        });

        $('#postcsvVoucher').change(function () {
            var filename = $("#postcsvVoucher").val();
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
                    url: '<?php echo base_url(); ?>admin/save-the-voucher',
                    type: 'POST',
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
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
                        $("form#getupload")[0].reset();
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
</script>