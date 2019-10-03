<div class="wrapper2">    
    <form method="POST" id="godown_add_form" action="<?php
    if ($param == 'A') {
        echo base_url('admin/godown-add.aspx');
    } else {
        echo base_url('admin/godown-add.aspx') . "/" . $godown['id'];
    }
    ?>">
        <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                    <h1> <i class="fa fa-bold"></i><?php echo !empty($godown) ? 'Edit Godown' : 'Add Godown'; ?> </h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <a href="<?php echo site_url('admin/godown'); ?>" class="btn btn-default" >Back</a>
                        <input class="btn btn-primary" type="submit" value="Save" name="submit"/>                                
                    </div>
                </div> 
            </div>     
        </section>
        <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Godowns', '/admin/godown');
        if(isset($godown['id'])){
        $this->breadcrumbs->push('Edit', '/admin/godown-add');
        }else{
         $this->breadcrumbs->push('Add', '/admin/godown-add');    
        }
        $this->breadcrumbs->show();
        ?>
    </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">        
                <div class="col-md-8">
                    <!-- general form elements disabled -->
                    <div class="box box-warning">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Parent</label>
                                        <select name="parent_id" id="" class="form-control">
                                            <option value="">Select Godown</option>
                                            <?php foreach ($all_godowns as $key => $value): ?>
                                            <option value="<?php echo $value['id']; ?>" <?php if(isset($godown) && ($godown['parent_id'] == $value['id'])) { echo "selected"; } ?>><?php echo $value['name']; ?></option>                
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input name="name" type="text"  class="form-control placeholder-no-fix" placeholder="Name" value="<?php echo (!empty(set_value('name'))) ? set_value('name') :((isset($godown)) ? $godown['name'] : ''); ?>" id="godown_name" autocomplete="off"/>
                                        <?php echo form_error('name', '<div style="color:red;">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="footer-button">
                                <input name="id" type="hidden"  class="form-control placeholder-no-fix" value="<?php echo (isset($godown)) ? $godown['id'] : ''; ?>" />
                                <!-- <input class="btn btn-primary" type="submit" value="Save" name="submit"/> -->
                                <button type="submit" class="btn ladda-button btn-primary" data-color="blue" data-style="expand-right" data-size="xs">Save</button>
                                <a href="<?php echo site_url('admin/godown'); ?>" class="btn btn-default" >Back</a>
                                
                            </div>                    
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
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
            </div>   <!-- /.row -->
        </section><!-- /.content -->
    </form>
</div>

<script>
    $(document).ready(function(){

        $("#godown_add_form").submit(function(event) {
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






    });
</script>