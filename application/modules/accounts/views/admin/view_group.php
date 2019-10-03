<div class="wrapper2">    
    <?php //echo form_open(base_url('accounts/groups/add_group'), array('target' => '_parent', 'id' => 'add_group')); ?>
<section class="content-header">
    <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-sitemap"></i><?php echo !empty($group) ? 'View Group' : 'Add Group'; ?> </h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <a href="<?php echo site_url('admin/accounts-groups').'?_tb=1' ?>" class="btn btn-default"> Back</a>
                        <?php if ($group[0]['operation_status'] == '1') { 
                                    $permissionedit=admin_users_permission('E','groups',$rtype=FALSE);
                                    if($permissionedit)
                                    {
                            ?>
                            <a href="<?php echo site_url('admin/edit-accounts-groups') . "/" . $group[0]['id']; ?>" class="btn btn-info"> Edit</a>
                        <?php 
                                }
                                    } ?>
                            <input type="button" class="btn btn-primary" value="Add" onclick="window.location.href = '<?php echo site_url('admin/add-accounts-groups'); ?>'" />
                    </div>
                </div> 
            </div>     
</section>
    <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Chart of Accounts', '/admin/accounts-groups');
        $this->breadcrumbs->push('View Group', '/admin/view-accounts-groups');
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
                        <div class="col-md-12">                    
                    <div class="form-group">
                        <label>Parent Name</label>
                        <?php
                            echo form_dropdown('group_id', $groups, !empty($group) ? $group[0]['parent_id'] : "", 'class="form-control select2me" disabled="disabled"');
                        ?>
                    </div>  
                        </div>
                        <div class="col-md-8">
                    <div class="form-group">
                        <label>Group Name</label>
                        <?php
                        $data = array(
                            'name' => 'group_name',
                            'id' => 'group_name_id',
                            'class' => 'form-control placeholder-no-fix',
                            'placeholder' => 'Group Name',
                            'value' => !empty($group) ? $group[0]['group_name'] : "",
                            'disabled'=> 'disabled'
                        );
                        echo form_input($data);
                        ?>
                    </div>
                    <span class="Existence_alert" style="color: red;"></span>
                        </div>
                        <div class="col-md-4">
                    <div class="form-group">
                     <label>Code</label>                     
                                              
                    <input disabled="disabled" name="group_code" id="group_code" type="text" placeholder="Group Code" class="form-control placeholder-no-fix" value="<?php echo !empty($group) ? $group[0]['group_code'] : '' ?>" />                    
                    <span class="Existence_alert" style="color: red;"></span>
                    
                    <?php echo form_hidden('id', !empty($group) ? $group[0]['id'] : ""); ?>
                    <?php echo form_hidden('user_id', $this->session->userdata('user_id')); ?>
                      </div>  
                                     
                </div>
                        </div>                    
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="footer-button">                        
                        <?php if ($group[0]['operation_status'] == '1') { 
                                    $permissionedit=admin_users_permission('E','groups',$rtype=FALSE);
                                    if($permissionedit)
                                    {
                            ?>
                            <a href="<?php echo site_url('admin/edit-accounts-groups') . "/" . $group[0]['id']; ?>" class="btn btn-info"> Edit</a>
                        <?php 
                                }
                                    } ?>
                            <a href="<?php echo site_url('admin/accounts-groups').'?_tb=1' ?>" class="btn btn-default"> Back</a>
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
<?php //echo form_close(); ?>
</div>