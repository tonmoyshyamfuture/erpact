<!DOCTYPE html>
<html> 
    <head>
        
        <meta charset="UTF-8">
        <title><?php         $title = substr($title,14);
        $title = 'ACT '.$title;echo $title; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel='shortcut icon' type='image/x-icon' href='<?php echo ACCOUNT_URL; ?>assets/images/favicon.ico' />
        <!-- Bootstrap 3.3.2 -->
        <link href="<?php echo $this->config->item('base_url'); ?>assets/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $this->config->item('base_url'); ?>assets/admin/bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Select2 -->

        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/select2/select2.css">
        <!-- Date Picker -->
        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/datepicker/datepicker3.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/daterangepicker/daterangepicker-bs3.css">    
        <!-- Ionicons -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_url'); ?>assets/admin/css/ionicons.min.css" rel="stylesheet" type="text/css" />
     
        <!-- Theme style -->
        <link href="<?php echo $this->config->item('base_url'); ?>assets/admin/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. Choose a skin from the css/skins 
             folder instead of downloading all of them to reduce the load. -->
        <link href="<?php echo $this->config->item('base_url'); ?>assets/admin/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <!-- DATA TABLES -->
        <link href="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <!-- FONT AWESOME -->
        
        <link href="<?php echo $this->config->item('base_url'); ?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- iCheck for checkboxes and radio inputs -->
        <link href="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/iCheck/all.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
        <!-- ANUP -->        
        <!-- PACE Loader-->
        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/plugins/pace/themes/blue/pace-theme-minimal.css" /> 
        <script data-pace-options='{ "ajax": false }' src="<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/plugins/pace/pace.js"></script>
        <!-- /PACE Loader -->
        <!-- New icon set -->    
        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/css/linearicons.css" type="text/css" />
        <!-- Custom CSS -->    
        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/css/custom.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/css/print.css" type="text/css" />        
        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/css/responsive.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/styles/scalling.css" type="text/css" />

        <!-- Sagnik -->
        <link href="<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/css/simple-sidebar.css" rel="stylesheet">

        <!-- /Sagnik -->          
        <style>
            .content-wrapper{margin-left: 0px !important;}
            .main-header{background-color: #fff;}
            .user-header{background-color:#015e90;}
            li.active{ background-color:#87CEEB;color: #fff;}
            div.error
            {  
                color:#dd0000;
            }
            .wrapper2
            {
                display: none;
            }
            .main-footer{background: #d9e3e7 /*#e8e7e3*/; border-top: 1px solid rgb(210, 214, 222); bottom: 0 !important}

            .sidemenu-active{
                color: #fff;
                background: rgba(255,255,255,0.2);
            }
            .main-header .navbar-custom-menu{margin-right: 0;}
        </style>
        <!-- jQuery 2.1.3 -->
      
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>

        <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/scalling.js"></script>
        <!--message-->
        <link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>assets/admin/message/css/toastr.min.css" /> 
        <script src="<?php echo site_url(); ?>assets/admin/message/js/toastr.min.js" type="text/javascript"></script>
        <!--end message-->
        <!--button loader-->
        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/ladda/ladda.min.css">
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/ladda/spin.min.js"></script>
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/ladda/ladda.min.js"></script>
        <script src="<?php echo $this->config->item('base_url'); ?>assets/js/feather.min.js"></script>
        <script>
            $(function() {
            // feather icon svg     
            feather.replace();
        });
        </script>
        <!--end button loader-->
        
        <script>
            $(function() {
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-center",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            })
        </script>
       
    </head>
    <body class="hold-transition fixed"> 
        <div class="wrapper">
                      

            <header class="main-header">
                <a href="#" class="logo"><img src="<?php echo base_url('assets/admin/sketch_custom/images/act-logo.png'); ?>"></a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <div class="container-fluidx">
                        <div class="rowx">                            
                            <div class="col-xs-12" style="padding-right:0">
                                <div class="navbar-custom-menu">
                                    <ul class="nav navbar-nav">
                                        <li class="dropdown notifications-menu hidden">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-bell-o"></i>
                                                <span class="label label-warning">2</span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="header">You have 2 notifications</li>
                                                <li>
                                                    <!-- inner menu: contains the actual data -->
                                                    <ul class="menu">
                                                        <li>
                                                            <a href="#">
                                                                <i class="fa fa-user text-aqua"></i> Anup has shared a Spreadsheet
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <i class="fa fa-user text-teal"></i> Anup is checking Responsiveness
                                                            </a>
                                                        </li>
                                                    </ul> 
                                                </li>
                                                <li class="footer"><a href="#">View all</a></li>
                                            </ul>
                                        </li>
                                        <!-- User Account: style can be found in dropdown.less -->
                                        <li class="dropdown user user-menu">
                                            <a style="cursor:pointer; padding: 16px 15px 14px" class="dropdown-toggle" data-toggle="dropdown">
                                                <div class="online"></div>
                                                <?php if ($this->session->userdata('user_image') != ""): ?>
                                                    <img class="user-image" src="<?php echo SAAS_URL . "assets/upload/user_profile/" . $this->session->userdata('user_image'); ?>">
                                                <?php else: ?>
                                                    <img class="user-image" src="<?php echo SAAS_URL; ?>assets/admin/images/svg/user-grey.svg" alt="">
                                                <?php endif; ?>
                                                <span class="hidden-xs"><?php echo (strlen(get_from_session('fname')) > 20) ? substr(get_from_session('fname'), 0, 20) . '...' : get_from_session('fname'); ?></span> 

                                            </a>
                                            <ul class="dropdown-menu custom-dropdown">
                                                
                                                <!-- User image -->
                                                <li class="user-header">
                                                    <div class="left_sectn">
                                                        <?php if ($this->session->userdata('user_image') != ""): ?>
                                                            <img class="img-circle user-img" src="<?php echo SAAS_URL . "assets/upload/user_profile/" . $this->session->userdata('user_image'); ?>">
                                                        <?php else: ?>
                                                            <img class="img-circle user-img" src="<?php echo SAAS_URL; ?>assets/admin/images/svg/user-white.svg">
                                                        <?php endif; ?>
                                                        <p>

                                                            <span class="user-email"><?php echo (strlen(get_from_session('fname')) > 20) ? substr(get_from_session('fname'), 0, 20) . '...' : get_from_session('fname'); ?></span>
                                                            <small><?php echo date('jS M Y \a\t h:i:s A', strtotime(get_from_session('last_login'))); ?></small>
                                                        </p>
                                                    </div>
                                                    <div class="right_sectn">
                                                        <ul class="user-menu">
                                                            <li><a href="<?php echo site_url('admin/profile'); ?>"><i data-feather="user"></i>&nbsp;  Profile</a></li>
                                                            <li><a href="<?php echo site_url('admin/logout'); ?>"><i data-feather="x-circle"></i>&nbsp; Close</a></li>
                                                            <li><a href="<?php echo base_url(); ?>admin/project_logout"><i data-feather="log-out"></i>&nbsp; Logout</a></li>
                                                            <li><a href="javascript:void"><i data-feather="life-buoy"></i>&nbsp; Help</a></li>

                                                            
                                                        </ul>
                                                    </div>
                                                </li>
                                                <!-- Menu Body -->

                                                <!-- Menu Footer-->
                                                <?php if(!empty($companylist)) : ?>
                                                <li class="user-footer">
                                                    <h4 class="list-heading">Switch Company</h4>
                                                    <input type="search" id="search_company_name" class="form-control" placeholder="Search company">
                                                    <ul class="company-list">

                                                        <?php foreach($companylist as $company): ?>
                                                        <li>
                                                            <a href="#" data-url="<?php echo ACCOUNT_URL; ?>admin/checklogin" data-act="<?php echo $company->id . '_' . $this->session->userdata('userid'); ?>"  class="switch_company"> <?php echo $company->company_name; ?></a>
                                                        </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="navbar-custom-menu">
                        <?php
                        if (file_exists(APPPATH . "modules/language/views/language.php")) {
                            echo langview();
                        }
                        ?>
                    </div>
                </nav>
            </header>
            <link href="<?php echo base_url(); ?>assets/css/jquery-confirm.min.css" rel="stylesheet">
            <script src="<?php echo $this->config->item('base_url'); ?>assets/js/jquery-confirm.min.js"></script>

            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <?php echo $content; ?>
            </div><!-- /.content-wrapper -->

            <!-- Content  -->
            <!-- Footer-->

            <footer class="main-footer hidden">
                <div class="pull-right hidden-xs">
                    Powered by <b><a title="Sketch Web Solutions" data-toggle="tooltip" href="http://sketchwebsolutions.com/" target="_blank">CartFace (v2.0)</a></b>
                </div>
                Used space <strong><?= PROJECT_CURRENT_SIZE_FORMATED ?></strong> out of <?= PROJECT_SIZE_LIMIT_FORMATED ?>.
                <!-- Copyright <strong>&copy;<a href="<?php //echo $this->config->item('base_url');         ?>"><?php echo getsitename(); ?></a></strong> (v2.0), <?php //echo date('Y');         ?>. All rights reserved.-->
            </footer>
        </div><!-- ./wrapper -->
        
        <script>
            $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.2 JS -->    
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/bootstrap/js/jasny-bootstrap.min.js" type="text/javascript"></script>    
        <!-- Select2 -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/select2/select2.full.min.js"></script>
        <!-- Slimscroll -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <!-- xeditable -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/js/bootstrap-editable.min.js"></script>
        <!-- FastClick -->
        <script src='<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/fastclick/fastclick.min.js'></script>
        <!-- AdminLTE App -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/dist/js/app.min.js" type="text/javascript"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/dist/js/demo.js" type="text/javascript"></script>
        
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>

        <!-- DATA TABES SCRIPT -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
        <!-- iCheck 1.0.1 -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/iCheck/icheck.min.js" type="text/javascript"></script>

        <!-- CKEDITOR -->
        <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/ckeditor/ckeditor.js"></script>

        <!-- Bootstrap WYSIHTML5 -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
        <!-- Token Input -->
        <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/jquery.gridly.js"></script>

        <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/admin/src/jquery.tokeninput.js"></script>
        <!-- Developer JS -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/developer.js"></script>

        <!-- EDITOR -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/ace/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>

        <!-- Accounts Js start -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/account/global/scripts/metronic.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/account/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/account/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/account/admin/pages/scripts/components-pickers.js"></script>

        <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/account/js/validation.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/account/js/sketch-custom.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/account/js/subhasis_custom.js"></script>
        <!-- Account Is end -->
        
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/custom_scroll/js/jquery.mCustomScrollbar.concat.min.js"></script>

         <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/html2canvas.min.js"></script>

        <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/jspdf.min.js"></script>
        
        <!--Avoid dropdown menu close on click inside-->
        <script>
            $('body').on("click", ".custom-dropdown", function (e) {
                $(this).parent().is(".open") && e.stopPropagation();
            });
        </script>
        
        <script>
        $(document).ready(function(){
            // company search
            $("#search_company_name").on('keyup', function() {
                var company_name = $(this).val();
                $.ajax({
                    url: "<?php echo base_url(); ?>admin/searchCompany",
                    type: "POST",
                    data: {company_name: company_name},
                    success: function(response){
                        $(".company-list").html(response);
                    }
                });
            });
        });

    $(function() {
        $(':input').attr('autocomplete', 'nope');

        $(".onlyNumeric").on('keyup', function() {
            $(this).val($(this).val().replace(/[^0-9]/, ''));
        });

        $(".nonNumeric").on('keyup', function() {
            $(this).val($(this).val().replace(/[0-9]/, ''));
        });
    });
        </script>
    
    </body>
</html>
 
<!-- Footer-->