<div class="wrapper2">    
    <?php echo form_open(base_url('accounts/groups/ajax_save_group_data'), array('id' => 'add_group_form')); ?>
<section class="content-header">
    <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-sitemap"></i><?php echo !empty($group) ? 'Edit Group' : 'Add Group'; ?> </h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <?php if(!empty($group)){ ?>
                            <a href="<?php echo site_url('admin/view-accounts-groups') . "/" . $group->id; ?>" class="btn btn-default"> Back</a>
                         <?php }else{ ?>
                            <a href="<?php echo site_url('admin/accounts-groups').'?_tb=1' ?>" class="btn btn-default"> Back</a>
                         <?php } ?> 
                            <button type="submit" class="btn ladda-button btn-primary" data-color="blue" data-style="expand-right" data-size="xs">Save</button>
                    </div>
                </div> 
            </div>     
</section>
    <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Chart of Accounts', '/admin/accounts-groups');
        if(isset($group->id)){
         $this->breadcrumbs->push('Update Group', '/admin/edit-accounts-groups');   
        }else{
        $this->breadcrumbs->push('Add Group', '/admin/add-accounts-groups');
        }
        $this->breadcrumbs->show();
        ?>
    </section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- left column -->
        <!-- right column -->
        <div class="col-md-8">
            <!-- general form elements disabled -->
            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">                        
                        <div class="col-md-6">                    
                    <div class="form-group">
                        <label>Parent Name</label>
                        <?php
                            echo form_dropdown('parent_id', $groups, !empty($group) ? $group->parent_id : "", 'class="select2 form-control select2-hidden-accessible"');
                        ?>
                    </div>  
                        </div>
                        <div class="col-md-6">
                    <div class="form-group">
                        <label>Group Name</label>
                        <?php
                        $data = array(
                            'name' => 'group_name',
                            'id' => 'group_name',
                            'class' => 'form-control placeholder-no-fix',
                            'placeholder' => 'Group Name',
                            'value' => !empty($group) ? $group->group_name : "",
                            ''
                        );
                        echo form_input($data);
                        ?>
                    </div>
                    <span class="Existence_alert" style="color: red;"></span>
                        </div>
                        <div class="col-md-4">
                    <div class="form-group">
                        <?php if($group_code_status != '1'){ echo '<label>Code</label>';} ?>                        
                                              
                        <input name="group_code" id="group_code" type="<?php if($group_code_status == '1'){ echo 'hidden';}else{ echo 'text';} ?>" placeholder="Group Code" class="form-control placeholder-no-fix" value="<?php echo !empty($group) ? $group->group_code : '' ?>" />                    
                    <span class="Existence_alert" style="color: red;"></span>
                    
                    <?php // echo form_hidden('id', !empty($group) ? $group[0]['id'] : ""); ?>
                    <input name="id" id="group_id" type="hidden"  value="<?php echo !empty($group) ? $group->id : '' ?>" />   
                    <?php echo form_hidden('user_id', $this->session->userdata('user_id')); ?>
                      </div>  
                                     
                </div>
                        </div>                    
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="footer-button">
                        <button type="submit" class="btn ladda-button btn-primary" data-color="blue" data-style="expand-right" data-size="xs">Save</button>
                        <?php if(!empty($group)){ ?>
                            <a href="<?php echo site_url('admin/view-accounts-groups') . "/" . $group->id; ?>" class="btn btn-default"> Back</a>
                         <?php }else{ ?>
                            <a href="<?php echo site_url('admin/accounts-groups').'?_tb=1' ?>" class="btn btn-default"> Back</a>
                         <?php } ?>                             
                    </div>   
                </div>
            </div><!-- /.box -->
        </div><!--/.col (right) -->
        
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
    $("#add_group_form").submit(function(event) {
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
                    window.location.href = "<?php echo site_url('admin/accounts-groups').'?_tb=1'; ?>";
                }
            }
        });

    });
</script>
<script>
    function chech_validation() {
        var group_name = document.getElementById("group_name_id").value;
        var group_id = document.getElementById("group_id").value;
        if(group_name==''){
             alert("Group Name is required.");
            return false;
       }else if(group_id == ''){
           var url = "<?php echo base_url().'accounts/groups/group_name_check' ?>";
           var queryString = "group_name=" + group_name  + '&ajax=1';
           $.ajax({
                    type: "POST",
                    url: url,
                    data: queryString,
                    dataType: "json",
                    success: function (data) {
                           if(data.SUCESS){
                               alert("Group Name is already exists..");
                               return false;
                           }else{
                               $("#add_group").submit();
                           }
                    },
                    error: function (request, error) {
                    }
                });
       }else{
            $("#add_group").submit();
       }
    }
</script>