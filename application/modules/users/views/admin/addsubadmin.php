<div class="wrapper2">
    <!-- form start -->
                <form role="form" action="<?php echo base_url('users/admin/formsubadmin');?>" method="post" enctype="multipart/form-data" name="addsubadmin" id="addsubadmin" >
        <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-user"></i> Add User</h1>  
                </div>
                <div class="col-xs-6"> 
                    <div class="pull-right">
                        <a href="" class="btn btn-danger hidden checkdelbtn"><i class="fa fa-times"></i></a>
                        <input type="submit" class="btn btn-primary" name="submit" value="Add" >
                    </div>
                </div>
            </div>            
        </section>
                    <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Users', '/admin/users');
        $this->breadcrumbs->push('Add', '/admin/add-users');
        $this->breadcrumbs->show();
        ?>
    </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
              <div class="box box-primary">                                
                  <div class="box-body">
                      <div class="row">
                          <div class="col-md-3">
                              <div class="form-group">
                     
                      <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail userimg">
                          <img  src="<?php echo $this->config->item('upload_dir')."img0.jpg"; ?>" data-src="<?php echo $this->config->item('upload_dir')."img0.jpg"; ?>" alt="..."> 
                          <div class="hover-user-img">
                           <span class="btn btn-default btn-file"><span class="fileinput-new"><i class="fa fa-image"></i></span><span class="fileinput-exists">Change</span><input type="file" name="profile_pic"></span>
                          <a href="javascript:void(0);" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                          </div>
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px; background: #fff"></div>                        
                      </div>
                    </div>
                          </div>
                          <div class="col-md-9">
                              <div class="row">
                                  <div class="form-group col-md-6">
                      <label for="fname">First Name</label>
                      <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter First Name" >
                    </div>
                    <div class="form-group col-md-6">
                      <label for="lname">Last Name</label>
                      <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter Last Name" >
                    </div>
                    <div class="form-group col-md-6">
                      <label for="username">Username</label>
                      <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" >
                    </div>
                    <div class="form-group col-md-6">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" >
                    </div>
                    <div class="form-group col-md-6">
                      <label for="email">Password</label>
                      <input type="password" class="form-control" id="pass" name="pass" placeholder="Enter Password" >
                    </div>
                    <div class="form-group col-md-6">
                      <label>Role Group</label>

                      <select class="form-control" name=group>
                       <option value="">Select</option>

                      <?php
                        foreach($list as $li)
                        {
                      ?>
                          <option value="<?php echo $li->id ?>"><?php echo $li->group_name ?></option>>
                      <?php
                        }
                      ?>
                      </select>
                    </div>
                              </div>
                          </div>
                      </div>                    
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                      <div class="footer-button">
                    <input type="submit" class="btn btn-primary" name="submit" value="Add" >
                      </div>
                  </div>                
              </div><!-- /.box -->
          </div>   <!-- /.row -->
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
     </form>
        </div>
<script type="text/javascript">
 $('.fileinput').on('change.bs.fileinput', function () {
  
});
</script>

<script>
          $("document").ready(function(){
            $("#addsubadmin").validate({
              rules:{
                fname:"required",
                lname:"required",
                username:"required",
                pass:"required",
                email:{
                  "required":true,
                  "email":true,
                  "remote":{
                           url:"<?php echo base_url() . 'users/admin/checkUnique' ?>",
                           method:"post",
                           data:{
                                 field:"email",
                                 value:function(){return $("#email").val()}
                                }
                            },
                },
               group:"required",

              },
              messages:{
                fname:"Please Enter First Name..!!",
                lname:"Please Enter Last Name..!!",
                username:"Please Enter Username..!!",
                pass:"Please Enter Password..!!",
                email:{
                  "required":"Please Enter an Email Address..!!",
                  "email":"Please Enter a Valid Email Address..!!",
                  "remote":"Email Address is already taken..!!"

                },
                group:"Please Choose a Role Group..!!",
              }
            });
          });
        </script>
        <style>
          .error{
            color: #dd0000;
          }
        </style>
