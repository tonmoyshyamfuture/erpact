<div class="login_wrapper">
<div class="login-logo">
        <a href="<?php echo $this->config->item('base_url');?>admin"><b><img src="<?php echo base_url('assets/admin/sketch_custom/images/cf-logo-blu-green.png');?>" width="200" /></b></a>
      </div><!-- /.login-logo -->      
<form id="cf-loginForm" action="<?php echo $this->config->item('base_url');?>admin/checklogin" method="post">
    <p class="text-center" style="margin-bottom: 20px; font-weight: bold">Please login to your Account</p>
  <div class="group">
      <input type="email" name="email" id="loginemailfocus"><span class="highlight"></span><span class="bar"></span>
    <label>Email</label>
  </div>
    <div class="group">
    <input type="password" name="password" ><span class="highlight"></span><span class="bar"></span>
    <label>Password</label>
  </div>
    <div class="row">
        <div class="col-xs-12">
    <div class="checkbox icheck">      
        <input type="checkbox" name="checkon" value="1">&nbsp; &nbsp; Remember me      
    </div>  
        </div>
  </div>
    <div class="row">
        <div class="col-xs-12">
    <a title="Forgot password?" data-toggle="tooltip" href="<?php echo site_url('admin/forget-password');?>"> Forgot Password ?</a>
  </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
    <div class="text-center clearfix" style="position: relative; margin-top: 10px;">
        <button type="submit" class="btn btn-primary buttonBlue btn-block">Login
    <div class="ripples buttonRipples"><span class="ripplesCircle"></span></div>
  </button>
    </div>
            </div>
    </div>
</form>
</div>





<div class="login-box hidden">
      <div class="login-logo">
        <a href="<?php echo $this->config->item('base_url');?>admin"><b><img src="<?php echo base_url('assets/admin/sketch_custom/images/cf-logo-blu-green.png');?>" width="300" /></b></a>
      </div><!-- /.login-logo -->
<!--      <div class="login-logo">
        <a href="<?php echo $this->config->item('base_url');?>admin"><b><img src="<?php echo base_url('assets/admin/sketch_custom/images/cf-logo-blu-green2.png');?>" width="300" /></b></a>
      </div> /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Log in to manage your store</p>
        <form id="cf-loginForm" action="<?php echo $this->config->item('base_url');?>admin/checklogin" method="post">
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
            </div><!-- /.col --> 
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>
      </div><!-- /.login-box-body -->  
      <div class="text-right" style="margin-top: 20px; font-size: 14px;">
        Powered by : <a href="http://www.cartface.com/" target="_blank"><img src="<?php echo base_url('assets/admin/sketch_custom/images/logo-blue-orange.png');?>" width="100" /></a>
      </div>
    </div><!-- /.login-box -->            
    
    
    <script>
        $(window, document, undefined).ready(function() {

  $('input').blur(function() {
    var $this = $(this);
    if ($this.val())
      $this.addClass('used');
    else
      $this.removeClass('used');
  });

  var $ripples = $('.ripples');

  $ripples.on('click.Ripples', function(e) {

    var $this = $(this);
    var $offset = $this.parent().offset();
    var $circle = $this.find('.ripplesCircle');

    var x = e.pageX - $offset.left;
    var y = e.pageY - $offset.top;

    $circle.css({
      top: y + 'px',
      left: x + 'px'
    });

    $this.addClass('is-active');

  });

  $ripples.on('animationend webkitAnimationEnd mozAnimationEnd oanimationend MSAnimationEnd', function(e) {
  	$(this).removeClass('is-active');
  });

});
    </script>