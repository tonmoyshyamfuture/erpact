<div class="wrapper2">
    <!-- form start -->
                <form role="form" action="<?php echo base_url('users/admin/formsubadmin');?>/<?php echo urlencode(base64_encode($result->id));?>" method="post" enctype="multipart/form-data" name="editsubadmin" id="editsubadmin">
        <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-user"></i>Edit User Info</h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <a href="" class="btn btn-danger hidden checkdelbtn"><i class="fa fa-times"></i></a>
                        <input type="submit" class="btn btn-primary" name="submit" value="Save" >
                    </div>
                </div>
            </div>               
        </section>
          <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Users', '/admin/users');
        $this->breadcrumbs->push('Update', '/admin/edit-users');
        $this->breadcrumbs->show();
        ?>
    </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="box box-primary">                                
                  <div class="box-body">
                      <div class="row">
                          <div class="col-md-3">
                              <div class="form-group">
                     
                      <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail userimg-edit">
                          <img  src="<?php if(!empty($result->image)){ echo $this->config->item('upload_dir').$result->image; } else {echo $this->config->item('upload_dir')."placeholder.png";}?>" data-src="<?php if(!empty($result->image)){ echo $this->config->item('upload_dir').$result->image; } else {echo $this->config->item('upload_dir')."placeholder.png";}?>" alt="...">
                        <div class="hover-user-img-edit">
                          <span class="btn btn-default btn-file"><span class="fileinput-new"><i class="fa fa-pencil"></i></span><span class="fileinput-exists">Change</span><input type="file" name="profile_pic"></span>
                          <a href="javascript:void(0);" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                        </div>
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>                        
                      </div>
                    </div>
                    <input type="hidden" name="old-profile-pic" value="<?php if(!empty($result->image)){echo $result->image;} ?>" id="old-profile-pic" />
                    <input type="hidden" name="id" value="<?php echo $result->id; ?>">
                          </div>
                          <div class="col-md-9">
                              <div class="form-group col-md-6">
                      <label for="fname">First Name</label>
                      <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter First Name" value="<?php echo $result->fname; ?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="lname">Last Name</label>
                      <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter Last Name" value="<?php echo $result->lname; ?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="username">Username</label>
                      <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" value="<?php echo $result->username; ?>" readonly>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="<?php echo $result->email; ?>" readonly>
                    </div>
                    <div class="form-group col-md-6">
                      <label>Role Group </label>

                      <select class="form-control" name="group" id="group">
                      <option value="">Select</option>
                      <?php
                        foreach($list as $li)
                        {
                      ?>
                          <option <?php if($li->id==$result->group_code) echo"selected"; ?> value="<?php echo $li->id ?>"><?php echo $li->group_name ?></option>>
                      <?php
                        }
                      ?>
                      </select>
                    </div>
                          </div>                    
                      </div>                    
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                      <div class="footer-button">
                    <input type="submit" class="btn btn-primary" name="submit" value="Save" >
                      </div>
                  </div>
                
              </div><!-- /.box -->
          </div>   <!-- /.row -->
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
            $("#editsubadmin").validate({
              rules:{
                fname:"required",
                lname:"required",
                group:"required",
              },
              messages:{
                fname:"Please Enter First Name..!!",
                lname:"Please Enter Last Name..!!",
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
