<!DOCTYPE html>
<html> 
  <head>
    <meta charset="UTF-8">
    <title><?php echo $title;?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="<?php echo $this->config->item('base_url');?>assets/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->config->item('base_url');?>assets/admin/bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>assets/admin/plugins/select2/select2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>assets/admin/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>assets/admin/plugins/daterangepicker/daterangepicker-bs3.css">    
    <!-- Font Awesome Icons -->
    <!--     <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> -->
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- fullCalendar 2.2.5-->
    <link href="<?php echo $this->config->item('base_url');?>assets/admin/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->config->item('base_url');?>assets/admin/plugins/fullcalendar/fullcalendar.print.css" rel="stylesheet" type="text/css" media='print' />
    <!-- Theme style -->
    <link href="<?php echo $this->config->item('base_url');?>assets/admin/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo $this->config->item('base_url');?>assets/admin/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
     <!-- DATA TABLES -->
    <link href="<?php echo $this->config->item('base_url');?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <!-- FONT AWESOME -->
<!--     <link href="<?php echo $this->config->item('base_url');?>assets/font-awesome/font-awesome.css" rel="stylesheet" type="text/css" /> -->
    <link href="<?php echo $this->config->item('base_url');?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck for checkboxes and radio inputs -->
    <link href="<?php echo $this->config->item('base_url');?>assets/admin/plugins/iCheck/all.css" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="<?php echo $this->config->item('base_url');?>assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
    <!-- token Input CSS -->
    <link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>assets/admin/styles/token-input.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>assets/admin/styles/token-input-facebook.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>assets/admin/css/jquery.gridly.css" type="text/css" />
    <!-- ANUP -->        
    <!-- PACE Loader-->
    <link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>assets/admin/sketch_custom/plugins/pace/themes/blue/pace-theme-minimal.css" />
    
    <script data-pace-options='{ "ajax": false }' src="<?php echo $this->config->item('base_url');?>assets/admin/sketch_custom/plugins/pace/pace.js"></script>
    <!-- /PACE Loader -->
    <!-- New icon set -->    
    <link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>assets/admin/sketch_custom/css/linearicons.css" type="text/css" />
    <!-- Custom CSS -->    
    <link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>assets/admin/sketch_custom/css/custom.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>assets/admin/sketch_custom/css/responsive.css" type="text/css" />
    <!-- /ANUP -->   
    
    <!-- Developer CSS --> 
    <link href="<?php echo $this->config->item('base_url');?>assets/developer.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script> 
    <![endif]-->
    <style>
div.error
{
color:#dd0000;
}

.wrapper2
{
  display: none;
}
</style>
    <!-- jQuery 2.1.3 -->
    <?php /*<script src="<?php echo $this->config->item('base_url');?>assets/admin/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    */ ?>
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
     <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
     <script>
        paceOptions = {
        // Disable the 'elements' source
        elements: false,

        // Only show the progress on regular and ajax-y page navigation,
        // not every request
        restartOnRequestAfter: false
      }

      Pace.on('start', function(){
        $('.fullloader').fadeIn();

      });
      Pace.on('done', function(){
        $('.fullloader').delay(200).fadeOut(function(){
          $('.wrapper2').fadeIn();
        });
      });
    </script>
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/js/jquery.validate.min.js"></script>
    <script src="<?php echo $this->config->item('base_url');?>assets/jscolor/jscolor.js"></script>

    	<!-- For Role management perpus start -->
	<script>
		$('document').ready(function(){
			$(".not-permited").prepend('<span class="mySecondDiv">You are not permitted</span>');

			$('.not-permited').each(function() {
			var href = $(this).attr('href');
			$('.not-permited').attr('onclick', "window.location='" + href + "#url")
			.removeAttr('href');
			});

		});

	</script>
	<!-- For Role management perpus End -->

  </head>
  <body class="skin-blue">
    <div class="wrapper">
      <div class="fullloader">
        <div id="fountainTextG"><div id="fountainTextG_1" class="fountainTextG">C</div><div id="fountainTextG_2" class="fountainTextG">a</div><div id="fountainTextG_3" class="fountainTextG">r</div><div id="fountainTextG_4" class="fountainTextG">t</div><div id="fountainTextG_5" class="fountainTextG">F</div><div id="fountainTextG_6" class="fountainTextG">a</div><div id="fountainTextG_7" class="fountainTextG">c</div><div id="fountainTextG_8" class="fountainTextG">e</div></div>
      </div>
      
      <header class="main-header">
        <a href="<?php echo $this->config->item('base_url');?>admin" class="logo">Control Panel</a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a style="cursor:pointer;" class="dropdown-toggle" data-toggle="dropdown">
                    <div class="online"></div>
                  <img src="<?php $imgadmin=get_from_session('image'); if(!empty($imgadmin)) { echo $this->config->item('upload_dir').get_from_session('image');} else { echo $this->config->item('upload_dir')."placeholder.png"; } ?>" class="user-image" alt="User Image"/>
                  <span class="hidden-xs"><?php echo get_from_session('fname'); ?></span>

                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php $imgadmin=get_from_session('image'); if(!empty($imgadmin)) { echo $this->config->item('upload_dir').get_from_session('image');} else { echo $this->config->item('upload_dir')."placeholder.png"; } ?>" class="img-circle" alt="User Image" />
                    <p>
                      <?php echo get_from_session('email'); ?>
                      <small>Last Login <?php echo date('jS M Y \a\t h:i:s A',strtotime(get_from_session('last_login'))); ?></small>
                    </p>
                  </li>
                  <!-- Menu Body -->

                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?php echo site_url('admin/profile');?>" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo site_url('admin/logout');?>" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>

          <div class="navbar-custom-menu">
            <?php
            if (file_exists(APPPATH."modules/language/views/language.php"))
            {
              echo langview();
            }
            ?>
          </div>
        </nav>
      </header>
      <?php
      $errormessage=$this->session->flashdata('errormessage');
      $successmessage=$this->session->flashdata('successmessage');
      if(!empty($errormessage) or !empty($successmessage))
      {
      ?>
      <div class="overlay"></div>
      <?php
      }
      ?>
      
      <?php
      if($errormessage)
      {
      ?>
      <div class="errormessage">
        <span><i class="fa fa-warning"></i></span>
        <div><?php echo $errormessage;?></div>
        <span class="closebutn"><i class="fa fa-close"></i></span>
      </div>
      <?php
      }
      if($successmessage)
      {
      ?>
      <div class="successmessage">
        <span><i class="fa fa-check-circle"></i></span>
        <div><?php echo $successmessage;?></div>
        <span class="closebutn"><i class="fa fa-close"></i></span>
      </div>
      <?php
      }
      ?>
<!-- Sitebar -->
        <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <div class="sitelogo"> 
                <img src="<?php echo base_url('assets/admin/sketch_custom/images/logo-cf.png');?>" height="50" border="0">
                <div class="gotoFront" title="Goto Frontend" data-toggle="tooltip" data-placement="right"><a href="<?php echo base_url();?>" target="_blank" ><i class="fa fa-external-link"></i></a></div>                
            </div> 
          
          <div class="user-panel hidden">
            <div class="pull-left image">
              <img src="<?php $imgadmin=get_from_session('image'); if(!empty($imgadmin)) { echo $this->config->item('upload_dir').get_from_session('image');} else { echo $this->config->item('upload_dir')."placeholder.png"; } ?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p><?php echo get_from_session('fname')." ".get_from_session('lname');?></p>

              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
            <div class="clearfix"></div>
          <!-- search form -->
          <!--<form action="#" method="get" class="sidebar-form">-->
            <!--<div class="input-group">-->
              <!--<input type="text" name="q" class="form-control" placeholder="Search..."/>-->
              <!--<span class="input-group-btn">-->
              <!--  <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>-->
              <!--</span>-->
            <!--</div>-->
          <!--</form>-->
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
            <?php 
            /*
           $current_uri=$this->uri->segment(2);
           $menu = get_menu_from_setting('admin_sidebar');
           $admintype = $this->session->userdata('admin_type');
     
           ?>
          <ul class="sidebar-menu">
           <?php 
           if(!empty($menu))
           {
             foreach ($menu as $item) {
              if($admintype == 'manager')
              {
                if(!in_array($item->url,$this->config->item('manager_menu')))
                {
                  continue;
                }

                $parentmenu = $item->url;
              }
              else
              {

                if(in_array($item->url,$this->config->item('only_manager_menu')))
                {
                  continue;
                }
              }
              $active = explode(',',$item->active);
              if($item->status == 1)
              {
                $submanuclass = !empty($item->submenu) ? "treeview" : ""; 
              ?>
              <li class="<?php if(in_array($current_uri,$active)) echo 'active '; ?><?= $submanuclass ?>">
                 <a href="<?php echo site_url($item->url);?>">
                   <i class="fa <?= $item->icon; ?>"></i> <span><?= $item->label; ?></span>
                   <?php if(!empty($item->submenu)){  ?><i class="fa fa-angle-left pull-right"></i> <?php } ?>
                 </a>

                 <?php

                  if(!empty($item->submenu)){ 
                  ?>
                  <ul class="treeview-menu">
                  <li class="<?php if(in_array($current_uri,$active)) echo 'active '; ?>">
                 <a href="<?php echo site_url($item->url);?>">
                   <i class="fa <?= $item->icon; ?>"></i> <span><?= $item->label; ?></span>
                 </a>
                 </li>
                  <?php
                  foreach ($item->submenu as $key => $item) {
                  if($admintype == 'manager')
                  {
                    $m = $this->config->item('manager_sub_menu');
                    if(!in_array($item->url,$m[$parentmenu]))
                    {
                      continue;
                    }
                  }
                  else
                  {

                    if(in_array($item->url,$this->config->item('only_manager_menu')))
                    {
                      continue;
                    }
                  }
                  $active = explode(',',$item->active);
                  if($item->status == 1)
                  {
                  ?>
                <li <?php if(in_array($current_uri,$active)) echo "class='active'"; ?>>
                 <a href="<?php echo site_url($item->url);?>">
                   <i class="fa <?= $item->icon; ?>"></i> <span><?= $item->label; ?></span>
                 </a>
               </li>
                 <?php
                    }
                   }

                echo '</ul>';
                  } 
                ?>              <?php
              }
             }
           }
           ?>

                 <?php $str=ENVIRONMENT;$a=$_SERVER['REQUEST_URI'];
                 if($str=="development") : ?>

           <li <?php if (strpos($a,'plugin-manager') !== false) { echo "class='active'";}?>>
             <a href="<?php echo site_url('admin/plugin-manager');?>">
               <i class="fa fa-plug"></i> <span>Plugin Management</span>
             </a>
           </li>

           <li <?php if (strpos($a,'menu-manager') !== false) { echo "class='active'";}?>>
             <a href="<?php echo site_url('admin/menu-manager');?>">
               <i class="fa fa-bars"></i> <span>Menu Management</span>
             </a>
           </li>
 

         <?php endif; ?>

           
          </ul>

          <?php */ ?>

          <?php get_sitebar(); ?>
        </section>
        <!-- /.sidebar -->
      </aside>
      <!--End of Sidebar-->

<!-- Sidebar-->

<!-- Content -->

<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        
<?php echo $content;?>

        
      </div><!-- /.content-wrapper -->
      

<!-- Content  -->
<!-- Footer-->

            <footer class="main-footer">
        <div class="pull-right hidden-xs">
            Powered by <b><a title="Sketch Web Solutions" data-toggle="tooltip" href="http://sketchwebsolutions.com/" target="_blank">CartFace (v2.0)</a></b>
        </div>
                Used space <strong>520MB</strong> out of 2GB.
                <!-- Copyright <strong>&copy;<a href="<?php //echo $this->config->item('base_url');?>"><?php echo getsitename();?></a></strong> (v2.0), <?php //echo date('Y');?>. All rights reserved.-->
      </footer>
    </div><!-- ./wrapper -->
    <style>
    #fountainTextG{
  width:480px;
  margin:auto;
}

.fountainTextG{
  color:rgba(5,23,31,0.77);
  font-family:Arial;
  font-size:50px;
  text-decoration:none;
  font-weight:normal;
  font-style:normal;
  float:left;
  animation-name:bounce_fountainTextG;
    -o-animation-name:bounce_fountainTextG;
    -ms-animation-name:bounce_fountainTextG;
    -webkit-animation-name:bounce_fountainTextG;
    -moz-animation-name:bounce_fountainTextG;
  animation-duration:2.37s;
    -o-animation-duration:2.37s;
    -ms-animation-duration:2.37s;
    -webkit-animation-duration:2.37s;
    -moz-animation-duration:2.37s;
  animation-iteration-count:infinite;
    -o-animation-iteration-count:infinite;
    -ms-animation-iteration-count:infinite;
    -webkit-animation-iteration-count:infinite;
    -moz-animation-iteration-count:infinite;
  animation-direction:normal;
    -o-animation-direction:normal;
    -ms-animation-direction:normal;
    -webkit-animation-direction:normal;
    -moz-animation-direction:normal;
  transform:scale(.5);
    -o-transform:scale(.5);
    -ms-transform:scale(.5);
    -webkit-transform:scale(.5);
    -moz-transform:scale(.5);
}#fountainTextG_1{
  animation-delay:0.85s;
    -o-animation-delay:0.85s;
    -ms-animation-delay:0.85s;
    -webkit-animation-delay:0.85s;
    -moz-animation-delay:0.85s;
}
#fountainTextG_2{
  animation-delay:1.01s;
    -o-animation-delay:1.01s;
    -ms-animation-delay:1.01s;
    -webkit-animation-delay:1.01s;
    -moz-animation-delay:1.01s;
}
#fountainTextG_3{
  animation-delay:1.18s;
    -o-animation-delay:1.18s;
    -ms-animation-delay:1.18s;
    -webkit-animation-delay:1.18s;
    -moz-animation-delay:1.18s;
}
#fountainTextG_4{
  animation-delay:1.35s;
    -o-animation-delay:1.35s;
    -ms-animation-delay:1.35s;
    -webkit-animation-delay:1.35s;
    -moz-animation-delay:1.35s;
}
#fountainTextG_5{
  animation-delay:1.52s;
    -o-animation-delay:1.52s;
    -ms-animation-delay:1.52s;
    -webkit-animation-delay:1.52s;
    -moz-animation-delay:1.52s;
}
#fountainTextG_6{
  animation-delay:1.69s;
    -o-animation-delay:1.69s;
    -ms-animation-delay:1.69s;
    -webkit-animation-delay:1.69s;
    -moz-animation-delay:1.69s;
}
#fountainTextG_7{
  animation-delay:1.86s;
    -o-animation-delay:1.86s;
    -ms-animation-delay:1.86s;
    -webkit-animation-delay:1.86s;
    -moz-animation-delay:1.86s;
}
#fountainTextG_8{
  animation-delay:2.03s;
    -o-animation-delay:2.03s;
    -ms-animation-delay:2.03s;
    -webkit-animation-delay:2.03s;
    -moz-animation-delay:2.03s;
}




@keyframes bounce_fountainTextG{
  0%{
    transform:scale(1);
    color:rgb(4,75,77);
  }

  100%{
    transform:scale(.5);
    color:rgb(255,255,255);
  }
}

@-o-keyframes bounce_fountainTextG{
  0%{
    -o-transform:scale(1);
    color:rgb(4,75,77);
  }

  100%{
    -o-transform:scale(.5);
    color:rgb(255,255,255);
  }
}

@-ms-keyframes bounce_fountainTextG{
  0%{
    -ms-transform:scale(1);
    color:rgb(4,75,77);
  }

  100%{
    -ms-transform:scale(.5);
    color:rgb(255,255,255);
  }
}

@-webkit-keyframes bounce_fountainTextG{
  0%{
    -webkit-transform:scale(1);
    color:rgb(4,75,77);
  }

  100%{
    -webkit-transform:scale(.5);
    color:rgb(255,255,255);
  }
}

@-moz-keyframes bounce_fountainTextG{
  0%{
    -moz-transform:scale(1);
    color:rgb(4,75,77);
  }

  100%{
    -moz-transform:scale(.5);
    color:rgb(255,255,255);
  }
}
    </style>
    <!-- jQuery UI 1.11.1 -->
    <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js" type="text/javascript"></script>
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.2 JS -->    
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/bootstrap/js/jasny-bootstrap.min.js" type="text/javascript"></script>    
    <!-- Select2 -->
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/plugins/select2/select2.full.min.js"></script>
    <!-- Slimscroll -->
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- xeditable -->
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/sketch_custom/js/bootstrap-editable.min.js"></script>
    <!-- FastClick -->
    <script src='<?php echo $this->config->item('base_url');?>assets/admin/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/dist/js/app.min.js" type="text/javascript"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/dist/js/demo.js" type="text/javascript"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.7.0/moment.min.js" type="text/javascript"></script>    
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>    
    
    <!--- chart in dashboard by Anup --->
    <!---  <script type="text/javascript" src="https://www.google.com/jsapi"></script> --->
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/plugins/chartjs/Chart.min.js"></script>
    
    <!-- DATA TABES SCRIPT -->
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
    <!-- iCheck 1.0.1 -->
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    
    <!-- CKEDITOR -->
    <script type="text/javascript" src="<?php echo $this->config->item('base_url');?>assets/ckeditor/ckeditor.js"></script>

    <!-- Bootstrap WYSIHTML5 -->
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
    <!-- Token Input -->
    <script type="text/javascript" src="<?php echo $this->config->item('base_url');?>assets/admin/js/jquery.gridly.js"></script>
    
    <script type="text/javascript" src="<?php echo $this->config->item('base_url');?>assets/admin/src/jquery.tokeninput.js"></script>
    <!-- Developer JS -->
    <script src="<?php echo $this->config->item('base_url');?>assets/developer.js"></script>
    
    <!-- EDITOR -->
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/plugins/ace/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
        
    
    <!-- Page specific script -->
   <script>
    $( document ).ready(function() {
        $("#btnSubmit").click(function() {
          var selectedLanguage = new Array();
                                                           
            $("input[name='productprintids[]']:checked").each(function (){
          // $('input[name="orderprintids[]"]:checked').each(function() {
               selectedLanguage.push(this.value);

               $('#test').append('<input type="hidden" name="hodo[]" value="'+this.value+'"/>');


           });
         if(selectedLanguage.length > 0)
             {

               $('#matching_Form').submit();
              return true;
            }
             else{
                   alert("Please Select At Least One Select Box");
                    return false;}
      //$('#matching_Form').submit();
      var url = "<?php echo $this->config->item('base_url');?>index.php/order/admin/index";
      //$(location).attr('href',url);
    });
  
});
    </script>
    
    <script type="text/javascript">

	var jsurl='<?php echo $this->config->item('base_url');?>';

      $(function () {

        /* initialize the external events
         -----------------------------------------------------------------*/
        function ini_events(ele) {
          ele.each(function () {

            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
              title: $.trim($(this).text()) // use the element's text as the event title
            };

            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);

            // make the event draggable using jQuery UI
            $(this).draggable({
              zIndex: 1070,
              revert: true, // will cause the event to go back to its
              revertDuration: 0  //  original position after the drag
            });

          });
        }
        ini_events($('#external-events div.external-event'));

        /* initialize the calendar
         -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date();
        var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
        $('#calendar').fullCalendar({
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
          },
          buttonText: {
            today: 'today',
            month: 'month',
            week: 'week',
            day: 'day'
          },
          //Random default events
          events: [
            {
              title: 'All Day Event',
              start: new Date(y, m, 1),
              backgroundColor: "#f56954", //red
              borderColor: "#f56954" //red
            },
            {
              title: 'Long Event',
              start: new Date(y, m, d - 5),
              end: new Date(y, m, d - 2),
              backgroundColor: "#f39c12", //yellow
              borderColor: "#f39c12" //yellow
            },
            {
              title: 'Meeting',
              start: new Date(y, m, d, 10, 30),
              allDay: false,
              backgroundColor: "#0073b7", //Blue
              borderColor: "#0073b7" //Blue
            },
            {
              title: 'Lunch',
              start: new Date(y, m, d, 12, 0),
              end: new Date(y, m, d, 14, 0),
              allDay: false,
              backgroundColor: "#00c0ef", //Info (aqua)
              borderColor: "#00c0ef" //Info (aqua)
            },
            {
              title: 'Birthday Party',
              start: new Date(y, m, d + 1, 19, 0),
              end: new Date(y, m, d + 1, 22, 30),
              allDay: false,
              backgroundColor: "#00a65a", //Success (green)
              borderColor: "#00a65a" //Success (green)
            },
            {
              title: 'Click for Google',
              start: new Date(y, m, 28),
              end: new Date(y, m, 29),
              url: 'http://google.com/',
              backgroundColor: "#3c8dbc", //Primary (light-blue)
              borderColor: "#3c8dbc" //Primary (light-blue)
            }
          ],
          editable: true,
          droppable: true, // this allows things to be dropped onto the calendar !!!
          drop: function (date, allDay) { // this function is called when something is dropped

            // retrieve the dropped element's stored Event Object
            var originalEventObject = $(this).data('eventObject');

            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;
            copiedEventObject.backgroundColor = $(this).css("background-color");
            copiedEventObject.borderColor = $(this).css("border-color");

            // render the event on the calendar
            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
              // if so, remove the element from the "Draggable Events" list
              $(this).remove();
            }

          }
        });

        /* ADDING EVENTS */
        var currColor = "#3c8dbc"; //Red by default
        //Color chooser button
        var colorChooser = $("#color-chooser-btn");
        $("#color-chooser > li > a").click(function (e) {
          e.preventDefault();
          //Save color
          currColor = $(this).css("color");
          //Add color effect to button
          $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
        });
        $("#add-new-event").click(function (e) {
          e.preventDefault();
          //Get value and make sure it is not null
          var val = $("#new-event").val();
          if (val.length == 0) {
            return;
          }

          //Create events
          var event = $("<div />");
          event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
          event.html(val);
          $('#external-events').prepend(event);

          //Add draggable funtionality
          ini_events(event);

          $("#new-event").val("");
        });
      });
      
      $(document).ready(function(){
        $('.closebutn').click(function(){
          $('.overlay').fadeOut();
          $('.successmessage').fadeOut();
          $('.errormessage').fadeOut();
        });
      });
      
      $(document).ready(function(){
        $('.overlay').delay(1000).fadeOut();
        $('.successmessage').delay(1000).fadeOut();
        $('.errormessage').delay(1000).fadeOut();
      });
      
      $(document).ready(function(){
        $('.no-print').hide();
      });
    </script>
    
    <script type="text/javascript">
      $(function () {
        //   $(".stock_table").dataTable({
        //   "bPaginate": true,
        //   "bLengthChange": false,
        //   "iDisplayLength":25,
        //   "bFilter": true,
        //   "bSort": false,
        //   "bInfo": true,
        //   "bAutoWidth": false
        // }).redraw();
        
        $("#example00").dataTable({
          "bPaginate": true,
          "oLanguage": {
            "oPaginate": {
            "sNext": "",
            "sPrevious": ""
            },
            "sSearch":""
          },
          "bLengthChange": false,
          "bFilter": true,
          "bSort": false,
          "bInfo": true,
          "bAutoWidth": false
        });
        $("#example1").dataTable({
          "bPaginate": true,
          "oLanguage": {
            "oPaginate": {
            "sNext": "",
            "sPrevious": ""
            },
            "sSearch":""
          },
          "bLengthChange": false,
          "bFilter": false,
          "bSort": true,
          "bInfo": true,
          "bAutoWidth": false
        });
        $('#example2').dataTable({
          "bPaginate": true,
          "oLanguage": {
            "oPaginate": {
            "sNext": "", 
            "sPrevious": ""
            },
            "sSearch":""
          },
          "bLengthChange": false,
          "bFilter": false,
          "bSort": true,
          "bInfo": true,
          "bAutoWidth": false
        });
         
        // added by Anup 
         $(".select2").select2();
        $(".content-header .col-xs-6:first-child").append($(".dataTables_filter"));
        $('.footer-button').append($('.dataTables_info'));
        $('.footer-button').append($('.dataTables_paginate'));        
        
        $('.content-header').find("input[type=text]").each(function(ev)
            {
                if(!$(this).val()) { 
               $(this).attr("placeholder", "Search...");
            }
            });
            
        // chart date - dashboard                
        $('.chartdaterange').daterangepicker({
            ranges: {
              'Today': [moment(), moment()],
              'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'Last 7 Days': [moment().subtract(6, 'days'), moment()],
              'Last 30 Days': [moment().subtract(29, 'days'), moment()],
              'This Month': [moment().startOf('month'), moment().endOf('month')],
              'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
          }, function (start, end) {
            window.alert("Selected range: " + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));            
                
      });
        // added by Anup         
        
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
      });
    </script>
       
    
<script>
      $(function () {
        //Add text editor
        $("#compose-textarea").wysihtml5();
      });
    </script>
    <script>
      $(function () {
        //Enable iCheck plugin for checkboxes
        //iCheck for checkbox and radio inputs
        //$('input[type="checkbox"]').iCheck({
          //checkboxClass: 'icheckbox_flat-blue',
          //radioClass: 'iradio_flat-blue'
        //});

        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
          var clicks = $(this).data('clicks');
          if (clicks) {
            //Uncheck all checkboxes
            $("input[type='checkbox']", ".mailbox-messages").iCheck("uncheck");
          } else {
            //Check all checkboxes
            $("input[type='checkbox']", ".mailbox-messages").iCheck("check");
          }
          $(this).data("clicks", !clicks);
        });
      });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#demo-input-facebook-theme").tokenInput({theme: "facebook"});
            $('[data-toggle="tooltip"]').tooltip();             
        });
    </script>
                  <script type="text/javascript">
                    $("#searchticket").on('click',"#pagination button",function(){
                       var a = $("#ajax").val();
                       if(a)
                       { 
                          var uri = $(this).find('a').attr('href'); //pagination link url
                          var arr = uri.split('/');
                          var pageno = arr[arr.length-1];
                          var stateval = $("#stateval").val();
                          var searchval = $("#searchval").val();
                          searchticket(searchval,stateval,pageno);
                         return false;
                      }
                      else
                      {
                        window.location = $(this).find('a').attr('href');
                      }
                    });

                    $("#searchinbox").on('click',"#pagination button",function(){
                       var a = $("#ajax").val();
                       if(a)
                       { 
                          var uri = $(this).find('a').attr('href');
                          var arr = uri.split('/');
                          p = arr[arr.length-2];
                          var pageno = arr[arr.length-1];
                          var searchval = $("#searchval").val();
                          switch(p)
                          {
                            case 'message-inbox':
                              searchinbox(searchval,pageno);
                              break;
                            case 'message-sent':
                              searchsent(searchval,pageno);
                              break;
                            case 'message-trash':
                                searchtrash(searchval,pageno);
                                break;

                          }
                          
                         return false;
                      }
                      else
                      {
                        window.location = $(this).find('a').attr('href');
                      }
                    });
                  </script>
                    <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/jquery.validate.min.js"></script>
                  <script>
                       $(function() {

                                   

                                    // override jquery validate plugin defaults
                                    $.validator.setDefaults({
                                        highlight: function(element) {

                                            $(element).closest('.form-group').addClass('has-error');
                                        },
                                        unhighlight: function(element) {
                                            $(element).closest('.form-group').removeClass('has-error');
                                        },
                                        errorElement: 'span',
                                        errorClass: 'help-block',
                                        errorPlacement: function(error, element) {
                                            if (element.parent('.form-control').length) {
                                                error.insertAfter(element.parent());
                                            } else {
                                                error.insertAfter(element);
                                            }
                                        }
                                    });
                                                    
                                });
                  </script>
                  <script>
                    $('.gridly').gridly({
                      base: 60, // px 
                      gutter: 20, // px
                      columns: 12
                    });


$(document).ready(function(){
	// $('.brick').shuffle();        
        $('input[type="text"]').prop("autocomplete","off");        
});

</script>

<!-- added by Anup --> 
<script>
    $(document).on('change', '.btn-file :file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);


    $(setInterval(function(){
      alert('1000');
    }),5000);

  });

    // Check-Uncheck All - with Delete button toggle   
    $(".checkdel").click(function() {
        if ($(this).is(":checked")) {
            $(".checkdelbtn").removeClass("hidden");
        } else {
            $(".checkdelbtn").addClass("hidden");
        }
        });        
        
    $(".checkAll").change(function () {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
            $(".checkdelbtn").toggleClass("hidden");
        });       
        
</script>
<!--- chart in dashboard by Anup --->
<!--<script src="<?php echo $this->config->item('base_url');?>assets/admin/sketch_custom/js/dashboard-charts.js"></script> -->



<?php
if (file_exists(APPPATH."modules/language/views/script.php"))
{
  echo scriptview();
}
?>



    <link href="<?php echo base_url(); ?>/assets/css/jquery-confirm.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>/assets/js/jquery-confirm.min.js"></script>
    <?php if(!empty($this->session->flashdata('errormessage'))) { ?>
    <script>
     $(document).ready(function(){
              var errormessage="<?php echo  $this->session->flashdata('errormessage') ?>";  
              var popconfim=$.confirm({
                  title: false, // hides the title.
                  cancelButton: false ,// hides the cancel button.
                  confirmButton: false, // hides the confirm button.
                  closeIcon: true, // hides the close icon.
                  content: errormessage,
               });
              $(".jconfirm .jconfirm-box div.content").addClass("error-confirm");

            setTimeout(function(){popconfim.close();},2500);

     });

    </script>

    <?php } ?>


        <?php if(!empty($this->session->flashdata('successmessage'))) { ?>
    <script>
     $(document).ready(function(){
            var successmessage="<?php echo  $this->session->flashdata('successmessage') ?>";
            var popconfim=$.confirm({
                  title: false, // hides the title.
                  cancelButton: false ,// hides the cancel button.
                  confirmButton: false, // hides the confirm button.
                  closeIcon: true, // hides the close icon.
                 content: errormessage,
               });

              $(".jconfirm .jconfirm-box div.content").addClass("success-confirm");

            setTimeout(function(){popconfim.close();},2500);


     });

    </script>

    <?php } ?>
    
  </body>
</html>

<!-- Footer-->
 
