<style> .errorMessage{color:red;}</style>
<div class="wrapper2">    
<?php echo form_open(base_url('tracking/admin/tracking_save_ajax'), array('id' => 'add_tracking_form')); ?>   
<section class="content-header">
    <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-map-marker"></i><?php echo !empty($tracking) ? 'Edit Tracking' : 'Add Tracking'; ?> </h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                       <a href="<?php echo base_url('admin/tracking'); ?>" class="btn btn-default">Discard</a>
                                    <input class="btn btn-primary" type="submit" value="Save"/>
                    </div>
                </div> 
            </div>     
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
    
        <div class="col-md-8">
            <!-- general form elements disabled -->
            <div class="box box-warning">
                <div class="box-body">
                    <div class="row">
                         
                                              
                    <div class="form-group col-md-4">
                        <label>Parent</label>
                        <input id="select2_sample4" name="" onblur="get_tracking(this.value);" class="form-control parent_tracking" placeholder="Select Parent" value="<?php echo !empty($tracking) ? $tracking['parent_tracking_name'] : '' ?>">
                        <input id="parent_id" name="parent_id" type="hidden" value="<?php echo !empty($tracking) ? $tracking['parent_id'] : '' ?>">
                        <span class="errorMessage"></span>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Name</label>
                        <?php
                        $data = array(
                            'name' => 'tracking_name',
                            'id' => 'tracking_name',
                            'class' => 'form-control placeholder-no-fix',
                            'placeholder' => 'Enter Tracking Name',
                            'value' => !empty($tracking) ? $tracking['tracking_name'] : ""
                        );
                        echo form_input($data);
                        ?>
                         <span class="errorMessage"></span>
                    </div> 
                    <span class="Existence_alert" style="color: red;"></span>
                    
                    <div class="form-group col-md-4">
                        <label>Tracking Type</label><br> 
                        <div class="radio-inline"><label>
                        <input name="tracking_type" id="tracking_type" type="radio"   <?php if(isset($tracking['tracking_type'])){ if($tracking['tracking_type'] == '1' ){ echo 'checked';} } ?>  value="1" /> Group Type
                        </label></div>
                        <div class="radio-inline"><label>
                        <input name="tracking_type" id="tracking_type" type="radio"  <?php if(isset($tracking['tracking_type'])){ if($tracking['tracking_type'] == '2' ){ echo 'checked';} }else{ echo 'checked'; } ?>    value="2" /> Access Type
                        </label></div>
                         <span class="errorMessage"></span>
                    </div> 
                    <span class="Existence_alert" style="color: red;"></span>
                    
                    <div class="form-group col-md-4">
                        <?php if($group_code_status != '1'){ echo '<label>Code</label>';} ?>
                        <input name="tracking_code" id="tracking_code" type="<?php if($group_code_status == '1'){ echo 'hidden';}else{ echo 'text';} ?>" placeholder="Code" class="form-control placeholder-no-fix" value="<?php echo !empty($tracking) ? $tracking['tracking_code'] : '' ?>" />
                    </div>
                    <span class="Existence_alert" style="color: red;"></span>
                    
                    <?php echo form_hidden('id', !empty($tracking) ? $tracking['id'] : ""); ?>
                        
                    </div>      
                </div><!-- /.box-body -->
                <div class="box-footer">
                     <div class="footer-button">
                         <button type="submit" class="btn ladda-button btn-primary" data-color="blue" data-style="expand-right" data-size="xs">Save</button>
                        <a href="<?php echo base_url('admin/tracking'); ?>" class="btn btn-default">Discard</a>
                    </div>  
                </div>
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
<?php echo form_close(); ?>
</div>
<script>
    $("#add_tracking_form").submit(function(event) {
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
                        $('#' + index).closest('.form-group').find('.errorMessage').html(value);
                    });
                } else if (data.res == 'save_err') {
                    Command: toastr["error"](data.message);
                } else {
                    Command: toastr["success"](data.message);
                    window.location.href = "<?php echo site_url('admin/tracking'); ?>";
                }
            }
        });

    });
</script>