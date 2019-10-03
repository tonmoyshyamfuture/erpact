
        <section class="content-header">
          <h1>
            Clients            
          </h1>
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <!-- left column -->
            <!-- right column -->
            <div class="col-md-12">
              <!-- general form elements disabled -->
              <div class="box box-warning">
                <div class="box-body">
                    <form role="form" action="<?php echo $this->config->item('base_url');?>admin/usersupdate/<?php echo !empty($userslist->id)?urlencode(base64_encode($userslist->id)):'';?>" method="post" enctype="multipart/form-data" id="users_form_edit" name="users_form_edit">
                    <!-- text input -->
                    <div class="form-group">
                      <label>First Name</label>
                      <input type="text" class="form-control" name="fname" <?php if(!empty($userslist->fname)) { ?> value="<?php echo $userslist->fname;?>" <?php } ?> placeholder="Enter First Name"/>
                    </div>
                    
                    <div class="form-group">
                      <label>Last Name</label>
                      <input type="text" class="form-control" name="lname" <?php if(!empty($userslist->lname)) { ?> value="<?php echo $userslist->lname;?>" <?php } ?> placeholder="Enter Last Name"/>
                    </div>
                    
                    <div class="form-group">
                      <label>Username</label>
                      <input type="text" class="form-control" name="username" <?php if(!empty($userslist->username)) { ?> value="<?php echo $userslist->username;?>" <?php } ?> placeholder="Enter Username"/>
                    </div>
                    
                    <div class="form-group">
                      <label>Email</label>
                      <input type="text" class="form-control" name="email" <?php if(!empty($userslist->lname)) { ?> value="<?php echo $userslist->email;?>" readonly="true" <?php } ?> placeholder="Enter Email"/>
                    </div>
                    
                    <div class="form-group">
                      <label>Address</label>
                      <textarea class="form-control" name="address" placeholder="Enter Address"><?php if(!empty($userslist->address)) { echo $userslist->address; } ?></textarea>
                    </div>
                    
                    <div class="form-group">
                      <label>Phone Number</label>
                      <input type="text" class="form-control" name="phone" <?php if(!empty($userslist->phone)) { ?> value="<?php echo $userslist->phone;?>" <?php } ?> placeholder="Enter Phone Number"/>
                    </div>
                    
                    <div class="form-group">
                            <label>Profile Image 
                            </label>

                            <div class="clearfix margin-top-10"></div>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" >                                                         
                                    <?php if (!empty($userslist) && $userslist->image != '') { ?> 
                                        <img src="<?php echo $this->config->base_url('assets/images/profile_images/' . $userslist->image); ?>" alt="" height="250" width="200">
                                           <?php } else { ?>
                                        <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="">
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                </div>
                                <div>
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new">
                                            Select image </span>
                                        <span class="fileinput-exists">
                                            Change 
                                        </span>
                                        <?php echo form_input(array("type" => "file", "name" => "user_image", "id" => "check_image")); ?>
                                    </span>
                                    <a href="#" class="btn default fileinput-exists" data-dismiss="fileinput">
                                        Remove </a>
                                </div>
                            </div>

                            <span class="help-block"></span>

                            <span id="show_image_error1" style="color:#DD1144;"></span>

                        </div>
                    <?php echo form_input(array("type" => "hidden", "name" => "user_image", "value" => !empty($userslist->image)? $userslist->image : '')); ?>
                    <div class="footer-button">
                      <input class="btn btn-primary" type="submit" value="Submit"/>
                    </div>
                  </form>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!--/.col (right) -->
          </div>   <!-- /.row -->
        </section><!-- /.content -->

        <script>
          $("document").ready(function(){
            $("#users_form_edit").validate({
              rules:{
                fname:"required",
                lname:"required",
                username:"required",
                address:"required",
                phone:{
                  "required":true,
                  "number":true,
                  "minlength":10,
                  "maxlength":10
                },
                user_image:{
                  //extension: "jpg|png|jpeg|gif"
                }
              },
              messages:{
                fname:"Please Enter First Name..!!",
                lname:"Please Enter Last Name..!!",
                username:"Please Enter User Name..!!",
                address:"Please Enter Address..!!",
                phone:{
                  "required":"Please Enter Phone Number..!!",
                  "number":"Phone Number must be number..!!"
                },
                user_image:{
                 // "extension":"Only .jpg,.png,.jpeg and .gif files allowed!!"
                }
              }
            });
          });
        </script>
        <style>
          .error{
            color: #dd0000;
          }
        </style>

    

    