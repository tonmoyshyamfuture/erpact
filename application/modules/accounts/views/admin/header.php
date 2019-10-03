<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title>Sketch | Accounts</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta content="" name="description"/>
        <meta content="" name="author"/>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $this->config->base_url(); ?>assets/account/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $this->config->base_url(); ?>assets/account/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $this->config->base_url(); ?>assets/account/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $this->config->base_url(); ?>assets/account/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $this->config->base_url(); ?>assets/account/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
<!--        <link href="<?php //echo $this->config->base_url('assets/global/plugins/gritter/css/jquery.gritter.css');     ?>" rel="stylesheet" type="text/css"/>-->
<!--        <link href="<?php //echo $this->config->base_url('assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css');     ?>" rel="stylesheet" type="text/css"/>-->
<!--        <link href="<?php //echo $this->config->base_url('assets/global/plugins/fullcalendar/fullcalendar/fullcalendar.css');     ?>" rel="stylesheet" type="text/css"/>-->
<!--        <link href="<?php //echo $this->config->base_url('assets/global/plugins/jqvmap/jqvmap/jqvmap.css');     ?>" rel="stylesheet" type="text/css"/>-->
        <!-- END PAGE LEVEL PLUGIN STYLES -->
        <!-- BEGIN PAGE STYLES -->
        <link href="<?php echo $this->config->base_url('assets/account/admin/pages/css/tasks.css'); ?>" rel="stylesheet" type="text/css"/>
        <!-- END PAGE STYLES -->
        <!-- BEGIN THEME STYLES -->
        <link href="<?php echo $this->config->base_url('assets/account/global/css/components.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $this->config->base_url('assets/account/global/css/plugins.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $this->config->base_url('assets/account/admin/layout/css/layout.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $this->config->base_url('assets/account/admin/layout/css/themes/default.css'); ?>" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="<?php echo $this->config->base_url('assets/account/admin/layout/css/custom.css'); ?>" rel="stylesheet" type="text/css"/>
        <!-- END THEME STYLES -->
        <link href="<?php echo $this->config->base_url('/assets/account/global/plugins/select2/select2.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $this->config->base_url('/assets/account/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $this->config->base_url('/assets/account/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $this->config->base_url('/assets/account/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'); ?>" rel="stylesheet" type="text/css"/>        
        <link rel="stylesheet" type="text/css" href="<?php echo $this->config->base_url('assets/jquery-treetable/jquery.treetable.css'); ?>" media="screen" /> 
        <!---------------This is the Css Changes[ Keep this file bottom of all css files to effect the changes]------------------------------------------------->
      
       <link href="<?php echo $this->config->base_url('assets/account/css/sketch_amitabha.css'); ?>" rel="stylesheet" type="text/css"/>
		<script src="<?php //echo $this->config->base_url('assets/js/jquery-1.11.2.min.js');    ?>" type="text/javascript"></script>

    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
    <!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
    <!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
    <!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
    <!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
    <!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
    <!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
    <!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
    <!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
    <body class="page-header-fixed page-quick-sidebar-over-content">

        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="dashboard">
                        <img src="<?php echo base_url('/assets/account/admin/layout/img/sketch_logo.png') ?>" alt="logo" class="logo-default" width="65px" style="margin-top: 0"/>
                    </a>
                    <div class="menu-toggler sidebar-toggler hide">
                        <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                    </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <!-- BEGIN NOTIFICATION DROPDOWN -->
                        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="icon-bell"></i>
                                <span class="badge badge-default">
                                    7 </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <p>
                                        You have 14 new notifications
                                    </p>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 250px;">
                                        <li>
                                            <a href="#">
                                                <span class="label label-sm label-icon label-success">
                                                    <i class="fa fa-plus"></i>
                                                </span>
                                                New user registered. <span class="time">
                                                    Just now </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="label label-sm label-icon label-danger">
                                                    <i class="fa fa-bolt"></i>
                                                </span>
                                                Server #12 overloaded. <span class="time">
                                                    15 mins </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="label label-sm label-icon label-warning">
                                                    <i class="fa fa-bell-o"></i>
                                                </span>
                                                Server #2 not responding. <span class="time">
                                                    22 mins </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="label label-sm label-icon label-info">
                                                    <i class="fa fa-bullhorn"></i>
                                                </span>
                                                Application error. <span class="time">
                                                    40 mins </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="label label-sm label-icon label-danger">
                                                    <i class="fa fa-bolt"></i>
                                                </span>
                                                Database overloaded 68%. <span class="time">
                                                    2 hrs </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="label label-sm label-icon label-danger">
                                                    <i class="fa fa-bolt"></i>
                                                </span>
                                                2 user IP blocked. <span class="time">
                                                    5 hrs </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="label label-sm label-icon label-warning">
                                                    <i class="fa fa-bell-o"></i>
                                                </span>
                                                Storage Server #4 not responding. <span class="time">
                                                    45 mins </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="label label-sm label-icon label-info">
                                                    <i class="fa fa-bullhorn"></i>
                                                </span>
                                                System Error. <span class="time">
                                                    55 mins </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="label label-sm label-icon label-danger">
                                                    <i class="fa fa-bolt"></i>
                                                </span>
                                                Database overloaded 68%. <span class="time">
                                                    2 hrs </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="external">
                                    <a href="#">
                                        See all notifications <i class="m-icon-swapright"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END NOTIFICATION DROPDOWN -->
                        <!-- BEGIN INBOX DROPDOWN -->
                        <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="icon-envelope-open"></i>
                                <span class="badge badge-default">
                                    4 </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <p>
                                        You have 12 new messages
                                    </p>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 250px;">
                                        <li>
                                            <a href="inbox.html?a=view">
                                                <span class="photo">
                                                    <img src="<?php echo base_url('/assets/account/admin/layout/img/avatar2.jpg') ?>" alt=""/>
                                                </span>
                                                <span class="subject">
                                                    <span class="from">
                                                        Lisa Wong </span>
                                                    <span class="time">
                                                        Just Now </span>
                                                </span>
                                                <span class="message">
                                                    Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="inbox.html?a=view">
                                                <span class="photo">
                                                    <img src="<?php echo base_url('assets/account/admin/layout/img/avatar3.jpg') ?>" alt=""/>
                                                </span>
                                                <span class="subject">
                                                    <span class="from">
                                                        Richard Doe </span>
                                                    <span class="time">
                                                        16 mins </span>
                                                </span>
                                                <span class="message">
                                                    Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="inbox.html?a=view">
                                                <span class="photo">
                                                    <img src="<?php echo base_url('/assets/account/admin/layout/img/avatar1.jpg') ?>" alt=""/>
                                                </span>
                                                <span class="subject">
                                                    <span class="from">
                                                        Bob Nilson </span>
                                                    <span class="time">
                                                        2 hrs </span>
                                                </span>
                                                <span class="message">
                                                    Vivamus sed nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="inbox.html?a=view">
                                                <span class="photo">
                                                    <img src="<?php echo base_url('/assets/account/admin/layout/img/avatar2.jpg') ?>" alt=""/>
                                                </span>
                                                <span class="subject">
                                                    <span class="from">
                                                        Lisa Wong </span>
                                                    <span class="time">
                                                        40 mins </span>
                                                </span>
                                                <span class="message">
                                                    Vivamus sed auctor 40% nibh congue nibh... </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="inbox.html?a=view">
                                                <span class="photo">
                                                    <img src="<?php echo base_url('/assets/account/admin/layout/img/avatar3.jpg') ?>" alt=""/>
                                                </span>
                                                <span class="subject">
                                                    <span class="from">
                                                        Richard Doe </span>
                                                    <span class="time">
                                                        46 mins </span>
                                                </span>
                                                <span class="message">
                                                    Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="external">
                                    <a href="inbox.html">
                                        See all messages <i class="m-icon-swapright"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END INBOX DROPDOWN -->
                        <!-- BEGIN TODO DROPDOWN -->
                        <li class="dropdown dropdown-extended dropdown-tasks" id="header_task_bar">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="icon-calendar"></i>
                                <span class="badge badge-default">
                                    3 </span>
                            </a>
                            <ul class="dropdown-menu extended tasks">
                                <li>
                                    <p>
                                        You have 12 pending tasks
                                    </p>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 250px;">
                                        <li>
                                            <a href="page_todo.html">
                                                <span class="task">
                                                    <span class="desc">
                                                        New release v1.2 </span>
                                                    <span class="percent">
                                                        30% </span>
                                                </span>
                                                <div class="progress">
                                                    <div style="width: 40%;" class="progress-bar progress-bar-success" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="sr-only">
                                                            40% Complete
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="page_todo.html">
                                                <span class="task">
                                                    <span class="desc">
                                                        Application deployment </span>
                                                    <span class="percent">
                                                        65% </span>
                                                </span>
                                                <div class="progress progress-striped">
                                                    <div style="width: 65%;" class="progress-bar progress-bar-danger" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="sr-only">
                                                            65% Complete
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="page_todo.html">
                                                <span class="task">
                                                    <span class="desc">
                                                        Mobile app release </span>
                                                    <span class="percent">
                                                        98% </span>
                                                </span>
                                                <div class="progress">
                                                    <div style="width: 98%;" class="progress-bar progress-bar-success" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="sr-only">
                                                            98% Complete
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="page_todo.html">
                                                <span class="task">
                                                    <span class="desc">
                                                        Database migration </span>
                                                    <span class="percent">
                                                        10% </span>
                                                </span>
                                                <div class="progress progress-striped">
                                                    <div style="width: 10%;" class="progress-bar progress-bar-warning" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="sr-only">
                                                            10% Complete
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="page_todo.html">
                                                <span class="task">
                                                    <span class="desc">
                                                        Web server upgrade </span>
                                                    <span class="percent">
                                                        58% </span>
                                                </span>
                                                <div class="progress progress-striped">
                                                    <div style="width: 58%;" class="progress-bar progress-bar-info" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="sr-only">
                                                            58% Complete
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="page_todo.html">
                                                <span class="task">
                                                    <span class="desc">
                                                        Mobile development </span>
                                                    <span class="percent">
                                                        85% </span>
                                                </span>
                                                <div class="progress progress-striped">
                                                    <div style="width: 85%;" class="progress-bar progress-bar-success" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="sr-only">
                                                            85% Complete
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="page_todo.html">
                                                <span class="task">
                                                    <span class="desc">
                                                        New UI release </span>
                                                    <span class="percent">
                                                        18% </span>
                                                </span>
                                                <div class="progress progress-striped">
                                                    <div style="width: 18%;" class="progress-bar progress-bar-important" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="sr-only">
                                                            18% Complete
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="external">
                                    <a href="page_todo.html">
                                        See all tasks <i class="icon-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END TODO DROPDOWN -->
                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <li class="dropdown dropdown-user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <img alt="" class="img-circle hide1" src="<?php echo base_url('/assets/account/admin/layout/img/avatar3_small.jpg') ?>"/>
                                <span class="username username-hide-on-mobile">
                                    Nick </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="extra_profile.html">
                                        <i class="icon-user"></i> My Profile </a>
                                </li>
                                <li class="divider">
                                </li>
                                <li>
                                    <a href="<?php echo base_url('index.php/logins/logout') ?>">
                                        <i class="icon-key"></i> Log Out </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->
                        <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                        <li class="dropdown dropdown-quick-sidebar-toggler">
                            <a href="javascript:;" class="dropdown-toggle">
                                <i class="icon-logout"></i>
                            </a>
                        </li>
                        <!-- END QUICK SIDEBAR TOGGLER -->
                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <div class="clearfix">
        </div>
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
            <!-- BEGIN CORE PLUGINS -->
            <!--[if lt IE 9]>
            <script src="../../assets/global/plugins/respond.min.js"></script>
            <script src="../../assets/global/plugins/excanvas.min.js"></script> 
            <![endif]-->
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jquery-1.11.0.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jquery-migrate-1.2.1.min.js'); ?>" type="text/javascript"></script>
            <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/bootstrap/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jquery.blockui.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jquery.cokie.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/uniform/jquery.uniform.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>" type="text/javascript"></script>
	    <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
            <script type="text/javascript" src="<?php echo $this->config->base_url('assets/global/plugins/jquery-validation/js/additional-methods.min.js'); ?>"></script>
            <script src="<?php echo $this->config->base_url('assets/account/js/validation.js'); ?>" type="text/javascript"></script> 
            <!-- END CORE PLUGINS -->
            <!--START FANCYBOX JS-->                                    
            <script type="text/javascript" src="<?php echo base_url('assets/account/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo base_url('assets/account/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo base_url('assets/account/admin/pages/scripts/components-pickers.js'); ?>"></script>                                   
            <!--END FANCYBOX JS-->
            <!-- BEGIN PAGE LEVEL PLUGINS -->
            <script src="<?php //echo $this->config->base_url('assets/global/plugins/bootstrap-select/bootstrap-select.min.js');     ?>" type="text/javascript"></script>
            <script src="<?php //echo $this->config->base_url('assets/global/plugins/select2/select2.min.js');     ?>" type="text/javascript"></script>
            <script src="<?php //echo $this->config->base_url('assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js');     ?>" type="text/javascript"></script>
            <!-- END PAGE LEVEL PLUGINS -->
            <!-- BEGIN PAGE LEVEL SCRIPTS -->
            <script src="<?php echo $this->config->base_url('assets/account/global/scripts/metronic.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/admin/layout/scripts/layout.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/admin/layout/scripts/quick-sidebar.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/admin/layout/scripts/demo.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/admin/pages/scripts/components-dropdowns.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/admin/pages/scripts/index.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/admin/pages/scripts/tasks.js'); ?>" type="text/javascript"></script>
            <!-- END PAGE LEVEL SCRIPTS -->

            <!--START LEFT PANEL MENU-->
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jqvmap/jqvmap/jquery.vmap.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/flot/jquery.flot.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/flot/jquery.flot.resize.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/flot/jquery.flot.categories.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jquery.pulsate.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/bootstrap-daterangepicker/moment.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/bootstrap-daterangepicker/daterangepicker.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/fullcalendar/fullcalendar/fullcalendar.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/jquery.sparkline.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo $this->config->base_url('assets/account/global/plugins/gritter/js/jquery.gritter.js'); ?>" type="text/javascript"></script>
            <!--END LEFT PANEL MENU-->




            <script src="<?php echo $this->config->base_url('/assets/account/global/plugins/select2/select2.min.js'); ?>"></script>
            <script src="<?php echo $this->config->base_url('/assets/account/global/plugins/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
            <script src="<?php echo $this->config->base_url('/assets/account/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js'); ?>"></script>
            <script src="<?php echo $this->config->base_url('/assets/account/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js'); ?>"></script>
            <script src="<?php echo $this->config->base_url('/assets/account/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js'); ?>"></script>
            <script src="<?php echo $this->config->base_url('/assets/account/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'); ?>"></script>
            <script src="<?php echo $this->config->base_url('assets/account/admin/pages/scripts/table-advanced.js'); ?>"></script>
            <!--START TREE TABLE-->
            <script src="<?php echo $this->config->base_url('assets/account/jquery-treetable/jquery.treetable.js'); ?>"></script>
            <script src="<?php echo $this->config->base_url('assets/account/tree-datatable/dataTables.treeTable.js'); ?>"></script>
            <script src="<?php echo $this->config->base_url('assets/account/js/sketch-custom.js'); ?>"></script>
            <script src="<?php echo $this->config->base_url('assets/account/js/subhasis_custom.js'); ?>" type="text/javascript"></script>
            <!--END TREE TABLE-->
            <!--------LIST JS----->
            <script src="<?php echo $this->config->base_url('assets/account/list-js/list.js'); ?>"></script>
            <script src="<?php echo $this->config->base_url('assets/account/list-js/list.pagination.min.js'); ?>"></script>
            <!-------SKETCH CUSTOM JS---->
            <script>

                jQuery(document).ready(function() {
                    Metronic.init(); // init metronic core componets
                    Layout.init(); // init layout
                    QuickSidebar.init(); // init quick sidebar
		    FormValidation.init();
                    //Demo.init(); // init demo features 
                    //Index.init();
                    //Index.initDashboardDaterange();
                    //Index.initJQVMAP(); // init index page's custom scripts
                    //Index.initCalendar(); // init index page's custom scripts
                    //Index.initCharts(); // init index page's custom scripts
                    //Index.initChat();
                    //Index.initMiniCharts();
                    //Index.initIntro();
                    //Tasks.initDashboardWidget();
                    TableAdvanced.init();
                    //                $('table.dataTable').dataTable({
                    //                    "lengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]],
                    //                    "iDisplayLength": 5,
                    //                    "bInfo": false
                    //                });
                });
            </script>
            <!-- END JAVASCRIPTS -->
