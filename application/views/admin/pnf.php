<section class="content-header">
          <ol class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">404 error</li>
          </ol>
</section>

<section class="content">
          <div class="error-page">
            <h2 class="headline text-yellow"> 404</h2>
            <div class="error-content">
              <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
              <p>
                We could not find the page you were looking for.
                 Meanwhile, you may <a href="<?php echo site_url('dashboard');?>">return to dashboard</a>
              </p>
            </div><!-- /.error-content -->
          </div><!-- /.error-page -->
</section>

<style>
.text-yellow
{
  color:#000 !important;
  opacity:0.6 !important;
}

.btn-warning
{
  background: rgba(0,0,0,0.6);
  border-color: #000;
}

.btn-warning:hover
{
  background: rgba(0,0,0,0.8);
  border-color: #000;
}
</style>