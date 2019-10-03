<div class="wrapper2">    
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <i class="fa fa-bell-o" aria-hidden="true"></i> Notifications</h1>  
            </div>
            <div class="col-xs-6 text-right">

            </div> 
        </div>      
    </section> 
    <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Email Notifications', '/admin/email-template');
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
                            <table id="example00" class="table table-striped fcol-50">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Template Name</th>
                                        <th>Email From</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($emailtemplate)) {
                                        $counter = 0;
                                        foreach ($emailtemplate as $row) {
                                            $counter++;
                                            ?>
                                            <tr>
                                                <td><?php echo $counter; ?></td>
                                                <td>
                                                    <?php
                                                    $permission = ua(193, 'edit');
                                                    if ($permission):
                                                        ?>
                                                        <a href="<?php echo site_url('admin/edit-email-template'); ?>/<?php echo urlencode(base64_encode($row->id)); ?>"><?php echo $row->name; ?></a>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $row->email_from; ?></td>                                                
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
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
    </section><!-- /.content -->
    <div id="dialog_status"><div id="dialog_status_msg"></div></div>     
</div>
<script>
    function change_status(id, alertmsg)
    {
        $("#dialog_status").dialog({
            resizable: false,
            height: 180,
            modal: true,
            title: 'Confirm!',
            buttons: {
                "Yes": function() {
                    location.href = "<?php echo site_url('admin/emailtemplate-status/'); ?>/" + id;
                },
                No: function() {
                    $(this).dialog("close");
                }
            },
            open: function() {
                $('#dialog_status_msg').html(alertmsg);
            }
        });
    }
</script>