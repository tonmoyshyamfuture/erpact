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
                <h1> <i class="fa fa-user"></i> Add Users</h1>
            </div>
            <div class="col-xs-6"> 
                <div class="pull-right">
                    <a href="" class="btn btn-danger hidden checkdelbtn"><i class="fa fa-times"></i></a>                    
                    <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-default">Back</a>
                    <!-- <button type="submit" class="btn btn-primary">Save</button> -->
                </div>
            </div> 
        </div>           
    </section>
    <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Users', '/admin/users');
        $this->breadcrumbs->push('Add User', '/admin/users/add user');
        $this->breadcrumbs->show();
        ?>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="box">   
                    <?php if ($this->session->flashdata('user-err')): ?>                    
                            <div class="alert alert-danger alert-dismissable"  style="margin-bottom: 0;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <?php echo $this->session->flashdata('user-err'); ?>
                            </div>                        
                        <?php endif; ?>
                         
                        <?php if ($this->session->flashdata('user-success')): ?>                    
                    <div class="alert alert-success alert-dismissable" style="margin-bottom: 0;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <?php echo $this->session->flashdata('user-success'); ?>                            
                    </div>
                        <?php endif; ?>
                    <form action="" method="post" id="add-user">
                    <div class="box-body">
                        
                            <div class="row">
                                <div class="col-md-6">
                            <div class="form-group">
                                <label for="fname">First Name</label>
                                <input type="text" class="form-control" name="fname" required=""/>
                            </div>
                                </div>
                                <div class="col-md-6">
                            <div class="form-group">
                                <label for="lname">Last Name</label>
                                <input type="text" class="form-control" name="lname" required=""/>
                            </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" name="email" required=""/>
                            </div>                            
                        </div>
                        <div class="box-footer">
                                <div class="footer-button">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-default">Back</a>
                            </div>
                        </div>
                        </form>
                    
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
    <div id="dialog_status"><div id="dialog_status_msg"></div></div> 

</div>

<script>
  $("document").ready(function(){
    $("#add-user").validate({
      rules:{
        fname:"required",
        lname:"required",
        email:"required"
      },
      messages:{
        fname:"Please enter first name",
        lname:"Please enter last name",
        email:"Please enter a valid email",
      }
    });
  });

</script>