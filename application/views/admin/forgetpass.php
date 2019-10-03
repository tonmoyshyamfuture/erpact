
    <div class="forgot_wrapper">
      <div class="login-logo">
        <a href="<?php echo $this->config->item('base_url');?>admin"><b><img src="<?php echo base_url('assets/admin/sketch_custom/images/cf-logo-blu-green.png');?>" width="200" /></b></a>
      </div><!-- /.login-logo -->
      <div class="login-box-bodyx">        
        <form id="froget-password-frm" name="froget-password-frm" action="<?php echo site_url('admin/checkemail');?>" method="post">
            <p class="text-center" style="margin-bottom: 20px; font-weight: bold">Put your Email to get the Password</p>
            <div class="group">
                <input type="email" name="email" id="emailfocus"><span class="highlight"></span><span class="bar"></span>
                <label>Email</label>
              </div>
            
            <div class="form-group has-feedback hidden">
            <input type="text" name="email" class="form-control" placeholder="Email"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="row" style="margin:10px 0">
            <div class="xx">
              <button type="submit" class="btn btn-primary btn-flat btn-block">Submit</button>
            </div><!-- /.col -->
          </div>
            <div class="row">
              <div class="col-xs-12">
                  <p><a href="<?php echo base_url('admin');?>">Back to Login</a></p>
              </div>            
          </div>
        </form>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
    
    <?php
    $errormessage=$this->session->flashdata('errormessage');
    $successmessage=$this->session->flashdata('successmessage');
   
    ?>
   
        