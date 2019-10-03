<div class="wrapper2">
        
            <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                  <h1><span class="lnr lnr-user"></span> Profile</h1>  
                </div> 
                <div class="col-xs-6">
                    <div class="pull-right">
<!--                        <button type="submit" class="btn btn-primary">Save</button>-->
                </div>
                </div>
            </div>          
        </section>  
        <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Profile', '/admin/profile');
        $this->breadcrumbs->show();
        ?>
    </section>
        
        <!-- Main content -->
        <section class="content"> 
            <div class="row">
            <div class="col-md-8">
              <!-- general form elements -->
              <div class="box box-primary">                
                <!-- form start -->
                <form role="form" id="profform" action="<?php echo $this->config->item('base_url');?>admin/profile" method="post" enctype="multipart/form-data">                    
                  <div class="box-body">
                      <div class="row"> 
<!--                      <div class="col-sm-4">
                       <div class="form-group">                     
                      <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new profileimg">
                            <?php if ($this->session->userdata('user_image') != ""): ?>
                                <img class="img-responsive" src="<?php echo SAAS_URL . "assets/upload/user_profile/" . $this->session->userdata('user_image'); ?>">
                            <?php else: ?>
                                <img class="img-responsive" src="<?php echo SAAS_URL; ?>/images/svg/user-white.svg">
                            <?php endif; ?>
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail"></div>
                        
                      </div>
                    </div>
                    <input type="hidden" name="old-profile-pic" value="<?php echo $this->config->item('upload_dir').get_from_session('image'); ?>" id="old-profile-pic" />
                      </div>-->
                      <div class="col-sm-12">
                          <div class="row">
                          <div class="form-group col-md-6">
                      <label for="fname">First Name <span class="red">*</span></label>
                      <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter First Name" value="<?php echo $profile->fname; ?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="lname">Last Name <span class="red">*</span></label>
                      <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter Last Name" value="<?php echo $profile->lname; ?>">
                    </div>
                              </div>
                    <div class="form-group hidden">
                      <label for="username">Username</label>
                      <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" value="<?php echo get_from_session('username'); ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="<?php echo $profile->email; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="tel">Phone</label>
                      <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone" value="<?php echo $profile->phone; ?>">
                    </div>
                    <div class="form-group">
                      <label for="tel">Image</label>
                      <input type="file" name="user_image" class="form-control" id="image" placeholder="image" />
                      <?php if(!empty($profile->image)):?>
                          <img src="<?php echo SAAS_URL; ?>assets/upload/user_profile/<?php echo $profile->image;?>" alt="No Image" width="50" class="img-thumbnail"/>
                      <?php endif; ?>
                    </div>
                    <?php //echo get_from_session('password') ?>
                    <div class="form-group">
                        <!-- <a href="change-password.aspx" class="btn btn-default">Change Password</a> -->
                        <input type="submit" name="profile_update" value="Update" class="btn btn-primary">
                        <button id="changeBtn" class="btn btn default pull-right">Change Password</button>
                    </div>
                           
                      </div>
                      </div>

                    
                  </div><!-- /.box-body -->
                </form>                
                
              </div>
              </div> 
            <div class="col-md-4">                         
              <div class="box box-primary">		
                  <div class="box-body" style="min-height: 238px">
                    <h4> <i class="fa fa-info-circle"></i> Help</h4>
                    Coming soon....
                </div>              
            </div>
                </div>
                </div>

                      
         <div id="change_section" style="display: none;">
            <div class="row">
              <div class="col-md-8">
                <div class="box">
                  <div class="box-body">
                       <?php echo form_open('admin/changePassword',array("id"=>"changepwd","name"=>"changepwd"));?>
                       <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Old Password <span class="red">*</span></label>
                            <?php 
                            $data = array('name'=> 'oldpassword','id' => 'passold','placeholder'=> 'Old Password','class'=> 'form-control');
                            echo  form_password($data);
                            ?>          
                          </div>
                          <div class="form-group">
                            <label>New Password <span class="red">*</span></label>
                            <?php          
                            $data1 = array('name'=> 'password','id' => 'pass','placeholder'=> 'New Password','class'=> 'form-control', 'minlength' => 6);          
                            echo  form_password($data1);          
                            ?>          
                          </div>                    
                          <div class="form-group">
                            <label>Confirm Password <span class="red">*</span></label>                   
                            <?php 
                            $data2 = array('name'=> 'passconf','id' => 'con_pass','placeholder'=> 'Confirm Password','class'=> 'form-control');          
                            echo  form_password($data2);         
                            ?>
                          </div> 
                          <div class="form-group">
                            <input type="hidden" name="id" value="<?php echo $profile->id; ?>" />
                            <input type="hidden" name="oldpass_actual" value="<?php echo $profile->password; ?>" />
                            <?php   
                            $data = array('name'=> 'Submit','id' => 'submitForm','value'=> 'Save','class'=> 'btn btn-primary');                    
                            echo form_submit($data);
                            ?>
                          </div>
                        </div>
                      </div>


                      <div class="row">
                        <div class="col-md-12">
                          <div id="password_req">
                            <b>** Password </b><br>
                            <span class="cond" id="alpha">Should contain a-z..</span><br>
                            <span class="cond" id="spec">Should contain $#@..</span><br>
                            <span class="cond" id="numeric">Should contain 1234..</span>
                          </div>
                        </div>
                      </div>



                    </div>
                  </div>
                </div>
              </div>
            </div>












            <div class="row">
                <div class="col-md-12">
                 <div class="box box-primary loginhistory">
                  <div class="box-header">
                      <h4><span class="lnr lnr-dice"></span> Login History</h4>  
                  </div>
		<div class="box-body table-fullwidth">
                <table id="example1x" class="table table-bordered table-striped fcol-50">
                <thead>
                  <tr>
                    <th>#</th>                    
                    <th>Date</th>
                    <th>Time</th>
                    <th>Browser</th>
                    <th>OS</th>
                    <th>IP Address</th>
                  </tr>
                </thead>
                <tbody>
		<?php
			if(!empty($logs))
			{
		?>
		
		<?php
			$cnt=1;
			foreach($logs as $logrow)
			{
			?>
			<tr>
				<td><?php echo $cnt; ?></td>				
				<td><?php echo date('d-m-Y', strtotime($logrow->login_datetime)); ?></td>
				<td><?php echo date('h:i A', strtotime($logrow->login_datetime)); ?></td>
                                <td>
                                    <?php
                                        if (strpos($logrow->user_agent, 'MSIE') !== FALSE) {
                                            echo "<img src='".base_url()."assets/uploads/ie.png' alt='IE'>";
                                        } elseif (strpos($logrow->user_agent, 'Firefox') !== FALSE) {
                                            echo "<img src='".base_url()."assets/uploads/firefox.jpg' alt='Firefox'>";
                                        } elseif (strpos($logrow->user_agent, 'Chrome') !== FALSE) {
                                            echo "<img src='".base_url()."assets/uploads/chrome.png' alt='Chrome'>";
                                        } elseif (strpos($logrow->user_agent, 'Safari') !== FALSE) {
                                            echo "<img src='".base_url()."assets/uploads/safari.png' alt='Safari'>";
                                        } else {
                                            echo "<img src='".base_url()."assets/uploads/browser.png' alt='Browser'>";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        if (strpos($logrow->user_agent, 'Linux') !== FALSE) {
                                            echo "<img src='".base_url()."assets/uploads/linux-logo.jpg' alt='Linux'>";
                                        } elseif (strpos($logrow->user_agent, 'Windows') !== FALSE) {
                                            echo "<img src='".base_url()."assets/uploads/windows-logo.jpg' alt='Windows'>";
                                        } elseif (strpos($logrow->user_agent, 'Mac') !== FALSE) {
                                            echo "<img src='".base_url()."assets/uploads/mac-logo.png' alt='Mac'>";
                                        } else {
                                            echo 'Others';
                                        }
                                    ?>
                                </td>
                                <td><?php echo $logrow->ip_address; ?></td>
			</tr>
			<?php
			$cnt++;
			}
		?>
		
		<?php 
			}
                        else
                        {
		?>
                                <tr><td colspan="4">
                            
                                        <div class="empty-data">
                        <h2>You have no previous log history</h2>
                        <p>&nbsp;</p>
                        <p class="lead">Latest few record will be displayed here...</p>                        
                        
                    </div>
                            </td></tr>
                <?php
                        }
                ?>
                </tbody>
		</table>
		</div>
                
                
              </div><!-- /.box -->   
                </div>
            </div>
            </section>                                    
        </div>
                
    
        
        

<script type="text/javascript">
 $('.fileinput').on('change.bs.fileinput', function () {
  
});
</script>
<style>
.error
{
  color: #ff0000;
}
</style>

<script>
          $("document").ready(function(){
            $("#profform").validate({
              rules:{
                fname:"required",
                lname:"required"
              },
              messages:{
                fname:"Please Enter First Name..!",
                lname:"Please Enter Last Name..!"
              }
            });

            $("#changeBtn").on('click', function(e) {
              e.preventDefault();
              $("#change_section").slideToggle('slow');
            });





          });


          
        </script>

        

        <script>
      $(document).ready(function() {
        $("#submitForm").attr('disabled', true); 
        // $("#submitForm").hide(); 
        $(".cond").css('color', '#bbb');
        var alpha_check = 0,
            spec_check = 0,
            num_check = 0;
        $("#pass").keyup(function() {
          var str = $(this).val();
          if (/[a-zA-Z]/.test(str)) {
            $("#alpha").css('color', 'green');
            alpha_check = 1;
          }else{
            alpha_check = 0;
            $("#alpha").css('color', '#bbb');
          }

          if(/^[a-zA-Z0-9- ]*$/.test(str) == false) {
            spec_check = 1;
            $("#spec").css('color', 'green');
          }else{
            spec_check = 0;
            $("#spec").css('color', '#bbb');
          }

          if (/[0-9]/.test(str)) {
            num_check = 1;
            $("#numeric").css('color', 'green');
          }else{
            num_check = 0;
            $("#numeric").css('color', '#bbb');
          }

          if(alpha_check == 1 && num_check == 1 && spec_check == 1) {
            $("#password_req").hide();            
            // $("#submitForm").show();
            $("#submitForm").attr('disabled', false);
          }else{
            $("#password_req").show();            
            // $("#submitForm").hide(); 
            $("#submitForm").attr('disabled', true);           
          }

        });

        // $("#changepwd").on('submit', function(e) {
        //   e.preventDefault();
        //   var url = $("#changepwd").attr('action');
        //   var data = $("#changepwd").serialize();
        //   console.log(url);
        //   console.log(data);
        //   $.ajax({
        //     url: url,
        //     type: "POST",
        //     data: data,
        //     dataType: "JSON",
        //     success:function(response) {
        //       if (response.res == "error") {
        //         Command: toastr["error"](response.msg);
        //       } else {
        //         Command: toastr["success"](response.msg);
        //       }
        //     },
        //     error:function(response) {
        //       console.log(response);
        //     }
        //   });



        // });

        $("#profform").validate({
          rules:{
            fname:"required",
            lname:"required",
          },
          messages:{
            fname:"Please enter first name",
            lname:"Please enter last name",
          }
        });


      });
    </script>