<style>
    html{height: auto !important}
</style>
<div class="login-box">
      <div class="login-logo">
        <a href="<?php echo $this->config->item('base_url');?>admin"><b><img src="<?php echo base_url('assets/admin/sketch_custom/images/cf-logo-blu-green.png');?>" width="300" /></b></a>
      </div><!-- /.login-logo -->
<!--      <div class="login-logo">
        <a href="<?php echo $this->config->item('base_url');?>admin"><b><img src="<?php echo base_url('assets/admin/sketch_custom/images/cf-logo-blu-green2.png');?>" width="300" /></b></a>
      </div> /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Please login to your Account</p>
        <!-- <form id="cf-loginForm" action="<?php echo $this->config->item('base_url');?>admin/checklogin" method="post">
          <div class="form-group has-feedback">
            <input type="text" name="email" class="form-control" placeholder="Email"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
              <div  style="float: left; width: 290px; position: relative; margin-bottom: 15px">
            <input type="password" name="password" class="form-control" placeholder="Password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
              <div class="forgotpass"><a title="Forgot password?" data-toggle="tooltip" href="<?php echo site_url('admin/forget-password');?>"> <i class="fa fa-question-circle"></i></a></div>
          </div>
            <div class="clearfix"></div>
          <div class="row"> 
              <div class="col-xs-8" style="margin-top:-5px">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="checkon" value="1">&nbsp; &nbsp; Stay signed in
                </label>
              </div>                        
            </div>
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div>
          </div>
        </form> -->

        <form id="cf-loginForm" action="<?php echo $this->config->item('base_url');?>admin/checklogin" method="post">          
            <div class="form-group">                
                <i class="fa fa-envelope"></i>
                <input type="text" name="email" id="email" class="form-control DevadminloginDev" placeholder="Email">                
            </div>
            
            <div class="form-group">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" id="password" class="form-control noradious DevadminloginDev" placeholder="Password">
                <div class="forgotpass"><a title="Forgot password?" data-toggle="tooltip" href="<?php echo site_url('admin/forget-password');?>"> <i class="fa fa-question-circle"></i></a></div>
            </div>
             
          
            <div class="clearfix"></div>
          <div class="row"> 
              <div class="col-xs-8" style="margin-top:-5px">
              <div class="checkbox icheck">
                <label>
                  <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" name="checkon" value="1" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>&nbsp; &nbsp; Stay signed in
                </label>
              </div>                        
            </div><!-- /.col --> 
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>

            </div><!-- /.col -->
          </div>
        </form>

      </div><!-- /.login-box-body -->  
     <!--  <div class="text-right" style="margin-top: 20px; font-size: 14px;">
        Powered by : <a href="http://www.cartface.com/" target="_blank"><img src="<?php echo base_url('assets/admin/sketch_custom/images/logo-blue-orange.png');?>" width="100" /></a>
      </div> -->
    </div><!-- /.login-box -->            