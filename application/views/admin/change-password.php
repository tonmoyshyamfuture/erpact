<div class="wrapper2">
    <?php echo form_open('admin/changePassword',array("id"=>"changepwd","name"=>"changepwd"));?>
    <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                  <h1><span class="fa fa-key"></span> Change Password</h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <?php   
                            $data = array('name'=> 'Submit','id' => 'Submit','value'=> 'Save','class'=> 'btn btn-primary');                    
                            echo form_submit($data);
                        ?>
                    </div>
                </div>
            </div>          
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
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
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
                    </div>
                    </div>


                    <div class="row">
                      <div class="col-md-6 col-md-offset-3">
                        <div id="password_req">
                          <b>* Password </b><br>
                          <span class="cond" id="alpha">Should contain a-z..</span><br>
                          <span class="cond" id="spec">Should contain $#@..</span><br>
                          <span class="cond" id="numeric">Should contain 1234..</span>
                        </div>
                      </div>
                    </div>





                    <div class="footer-button">                      
                        <input type="hidden" name="id" value="<?php if(count($details)>0){echo $details[0]->id;}?>" />
                        <input type="hidden" name="oldpass_actual" value="<?php if(count($details)>0){echo $details[0]->password;}?>" />
                        <?php   
                            $data = array('name'=> 'Submit','id' => 'submitForm','value'=> 'Save','class'=> 'btn btn-primary');                    
                            echo form_submit($data);
                        ?>
                  
                    <!--</div>-->
                    </div>
                 
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!--/.col (right) -->
          </div>   <!-- /.row -->
        </section><!-- /.content -->
        <?php echo form_close();?>
        </div>
    
        <script>
          $("document").ready(function(){
            $("#changepwd").validate({
              rules:{
                oldpassword:"required",
                password:"required",
                passconf:{
                  "required":true,
                  "equalTo":"#pass"
                }
              },
              messages:{
                oldpassword:"Please Enter Old Password..!!",
                password:"Please Enter New Password (min 6 character)..!!",
                passconf:{
                  "required":"Please Enter Confirm Password..!!",
                  "equalTo":"New Password and Confirm Password must be same..!!"
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

        <script>
      $(document).ready(function() {
        $("input[name='Submit']").hide(); 
        $(".cond").css('color', '#ccc');
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
            $("#alpha").css('color', '#ccc');
          }

          if(/^[a-zA-Z0-9- ]*$/.test(str) == false) {
            spec_check = 1;
            $("#spec").css('color', 'green');
          }else{
            spec_check = 0;
            $("#spec").css('color', '#ccc');
          }

          if (/[0-9]/.test(str)) {
            num_check = 1;
            $("#numeric").css('color', 'green');
          }else{
            num_check = 0;
            $("#numeric").css('color', '#ccc');
          }

          if(alpha_check == 1 && num_check == 1 && spec_check == 1) {
            $("#password_req").hide();            
            $("input[name='Submit']").show();
          }else{
            $("#password_req").show();            
            $("input[name='Submit']").hide();            
          }

        });
      });
    </script>




