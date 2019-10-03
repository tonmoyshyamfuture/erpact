<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php
            $title = substr($title, 14);
            $title = 'ACT ' . $title;
            echo $title;
            ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- <link rel="shortcut icon" type="image/png" href="<?php echo ACCOUNT_URL; ?>assets/images/act_fav.png"/> -->
        <link rel='shortcut icon' type='image/x-icon' href='<?php echo ACCOUNT_URL; ?>assets/images/favicon.ico' />
        <!-- Bootstrap 3.3.2 -->
        <link href="<?php echo $this->config->item('base_url'); ?>assets/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $this->config->item('base_url'); ?>assets/admin/bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Select2 -->

        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/select2/select2.css">

        <link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/bootstrap-daterangepicker/daterangepicker.css" />
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
        <link href="<?php echo $this->config->item('base_url'); ?>assets/admin/css/jquery.auto-complete.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $this->config->item('base_url'); ?>assets/admin/css/tautocomplete.css" rel="stylesheet" type="text/css" />

        <!-- Custom CSS -->
        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/css/custom.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/css/print.css" type="text/css" />
        <link rel="stylesheet" id="switch_page_orientation" href="<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/css/print-portrait.css" type="text/css">
        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/css/responsive.css" type="text/css" />
        <!-- Sagnik -->

        <link href="<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/css/simple-sidebar.css" rel="stylesheet">
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <script src="<?php echo $this->config->item('base_url'); ?>assets/jscolor/jscolor.js"></script>
        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/styles/scalling.css" type="text/css" />
        <script data-pace-options='{ "ajax": false }' src="<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/plugins/pace/pace.js"></script>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">

        <!--message-->
        <link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>assets/admin/message/css/toastr.min.css" />
        <script src="<?php echo site_url(); ?>assets/admin/message/js/toastr.min.js" type="text/javascript"></script>
        <!--end message-->
        <!--button loader-->
        <link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>assets/admin/ladda/ladda.min.css">
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/ladda/spin.min.js"></script>
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/ladda/ladda.min.js"></script>
        <!--end button loader-->

        <script src="<?php echo $this->config->item('base_url'); ?>assets/js/feather.min.js"></script>
        <script>
            $(function() {
            // feather icon svg
            feather.replace();

                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-full-width",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut",
                    "titleClass": 'toast-title',
                    "messageClass": 'toast-message'
                }
            })
        </script>
        <script>
            paceOptions = {
                // Disable the 'elements' source
                elements: false,
                // Only show the progress on regular and ajax-y page navigation,
                // not every request
                restartOnRequestAfter: false
            }

            Pace.on('start', function() {
                $('.fullloader').fadeIn();

            });
            Pace.on('done', function() {
                $('.fullloader').delay(200).fadeOut(function() {
                    $('.wrapper2').fadeIn();
                });
            });
        </script>
        <style>
            .notifications-menu > ul > li:nth-child(2) {
                max-height: 250px !important;
                overflow: auto;
            }
            input.input-sm[type=search] {
                width: 100% !important;
            }
            .dataTables_processing{position:absolute;top:50%;left:50%;width:100%;height:40px;margin-left:-50%;margin-top:-25px;padding-top:20px;text-align:center;font-size:1.2em;background-color:white;background:-webkit-gradient(linear, left top, right top, color-stop(0%, rgba(255,255,255,0)), color-stop(25%, rgba(255,255,255,0.9)), color-stop(75%, rgba(255,255,255,0.9)), color-stop(100%, rgba(255,255,255,0)));background:-webkit-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 25%, rgba(255,255,255,0.9) 75%, rgba(255,255,255,0) 100%);background:-moz-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 25%, rgba(255,255,255,0.9) 75%, rgba(255,255,255,0) 100%);background:-ms-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 25%, rgba(255,255,255,0.9) 75%, rgba(255,255,255,0) 100%);background:-o-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 25%, rgba(255,255,255,0.9) 75%, rgba(255,255,255,0) 100%);background:linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 25%, rgba(255,255,255,0.9) 75%, rgba(255,255,255,0) 100%);z-index: 9999}
            .task-loader {
                display: none;
                position: fixed;
                top: 0;
                right: 0;
                left: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.3);
                z-index: 9999;
            }
            .task-loader i {
                position: absolute;
                top:49%;
                left:49%;
                color: #286284;
                font-size: 100px;
            }
            .sidemenu-active{

                color: #fff;
                background: rgba(255,255,255,0.2);
            }

            .navbar-nav>.notifications-menu>.dropdown-menu>li .menu>li>a {

                white-space: normal;
            }


        </style>
    </head>
    <body class="hold-transition fixed skin-black">
        <div class="wrapper">
            <div class="fullloader">
                <div class="fill">
                    <img src="<?php echo base_url('assets/admin/sketch_custom/images/loaders/three-dots.svg'); ?>" height="20">
                </div>
            </div>
            <header class="main-header">
                <?php
                    $branch_id = $this->session->userdata('branch_id');
                    $where = ['id' => $branch_id];
                    $account_settings = $this->db->select('*')
                            ->from(tablename('account_settings'))
                            ->where($where)
                            ->get()
                            ->row();
                    $account_standard_format = $this->db->select('*')
                            ->from(tablename('account_standard_format'))
                            ->where(['id' => 1])
                            ->get()
                            ->row();
                    ?>

                <div class="admin-company-name">
                    <div class="comp-logo">
                        <img src="<?php
                        $imgadmin = get_from_session('image');
                        if (isset($account_settings->logo) && $account_settings->logo) {
                            //echo $this->config->item('upload_dir') . get_from_session('image');
                            echo $this->config->item('upload_dir') . 'thumbs/' . $account_settings->logo;
                        } else {
                            //echo $this->config->item('upload_dir') . 'thumbs/' . $account_settings->logo;
                            echo $this->config->item('upload_dir') . "comp.png";
                        }
                        ?>" class="img-circle img-responsive" alt="User Image" />
                    </div>

                    <div class="comp-info">
                        <p class="name" data-toggle="tooltip" data-placement="bottom" title="<?php echo (isset($account_settings->company_name) && $account_settings->company_name != '') ? $account_settings->company_name : '' ?>">
                            <?php echo (isset($account_settings->company_name) && $account_settings->company_name != '') ? $account_settings->company_name : '' ?>
                        </p>
                        <p class="finyear"><i data-feather="calendar"></i> <?php echo (isset($account_standard_format->finalcial_year_from) && $account_standard_format->finalcial_year_from != '') ? date('Y', strtotime($account_standard_format->finalcial_year_from)) : '' ?> - <?php echo (isset($account_standard_format->finalcial_year_from) && $account_standard_format->finalcial_year_from != '') ? (date('Y', strtotime($account_standard_format->finalcial_year_from)) + 1) : '' ?></p>


                    </div>
                </div>

                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <div class="container-fluidx">
                        <div class="rowx">
                            <div class="col-xs-3x">
                                <div class="logo-xs hidden-lg hidden-md hidden-sm hidden-xs">
                                    <a href="<?php echo $this->config->item('base_url'); ?>admin"><img src="<?php echo base_url('assets/admin/sketch_custom/images/act-logo.png'); ?>"></a>
                                </div>
                                <!-- Sidebar toggle button-->
                                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                                    <i data-feather="align-justify"></i>
                                </a>
                                <div class="navbar-custom-menu2">
                                    <ul class="nav navbar-nav">
                                        <li class="dropdown">
                                            <a style="cursor:pointer;" class="dropdown-toggle" data-toggle="dropdown"><i data-feather="chevron-down"></i></a>
                                            <ul class="dropdown-menu">

                                                <?php getTransactionMenu(); ?>
                                            </ul>
                                        </li>
                                        <li><a href="javascript:void(0);" data-toggle="modal" data-target="#hotKeyModal">H</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xs-3x">

                                <div class="navbar-custom-menu">
                                    <ul class="nav navbar-nav">

                                        <!-- trash list -->

                                        <li class="dropdown notifications-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i data-feather="trash-2"></i>
                                                <span class="label label-danger trash_entry_count"><?php

                                                    echo count($trash_entries);
                                                    ?></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="header" id="trash_entry_count" data-count="<?php echo count($trash_entries); ?>">You have <span class="trash_entry_count"><?php echo count($trash_entries); ?></span> entries in trash</li>
                                                <li>
                                                    <!-- inner menu: contains the actual data -->
                                                    <ul class="menu-top-notify-dropdown" id="trashEntryMenu">
                                                        <?php
                                                        if (!empty($trash_entries)) {
                                                            foreach ($trash_entries as $n) {
                                                                ?>
                                                                <li class="trashyEntry">
                                                                    <?php echo $n->entry_no; ?> (
                                                                    <?php if ($n->entry_type_id == 5): ?>
                                                                        Sales -
                                                                    <?php elseif($n->entry_type_id == 6): ?>
                                                                        Purchase -
                                                                    <?php endif ?>
                                                                    <?php echo $n->dr_amount; ?>)
                                                                    <a href="<?php echo base_url(); ?>admin/permanentlyDeleteEntry/<?php echo $n->id; ?>" class="permanentlyDeleteEntry text-danger"><i data-feather="trash-2"></i></a>
                                                                    <a href="<?php echo base_url(); ?>admin/restoreEntry/<?php echo $n->id; ?>" class="restoreEntry text-info"><i data-feather="rotate-ccw"></i></a>
                                                                </li>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                </li>
                                                <li class="footer"><a href="#">&nbsp</a></li>
                                            </ul>
                                        </li>
                                        <!-- trash list ends -->

                                        <li class="dropdown notifications-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i data-feather="bell"></i>
                                                <span class="label label-warning notification_count"><?php
                                                    $notification = $this->db->select('*')
                                                            ->from(tablename('branch_entry_notification'))
                                                            ->where(['to_branch' => $this->session->userdata('branch_id')])
                                                            ->where(['status' => 1])
                                                            ->order_by("id", "desc")
                                                            ->get()
                                                            ->result();
                                                    echo count($notification);
                                                    ?></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="header" id="notification_count" data-count="<?php echo count($notification); ?>">You have <span class="notification_count"><?php echo count($notification); ?></span> notifications</li>
                                                <li>
                                                    <!-- inner menu: contains the actual data -->
                                                    <ul class="menu-top-notify-dropdown">
                                                        <?php
                                                        foreach ($notification as $n) {
                                                            ?>
                                                            <li class="notify">
                                                                <?php echo $n->notification_msg; ?>
                                                                <a href="<?php echo base_url(); ?>admin/deleteNotification/<?php echo $n->id; ?>" class="deleteNotification text-danger"><i data-feather="trash-2" style="width: 12px; height: 12px;"></i></a>
                                                            </li>
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </li>
                                                <li class="footer"><a href="#">&nbsp</a></li>
                                            </ul>
                                        </li>
                                        <!-- User Account: style can be found in dropdown.less -->
                                        <li class="dropdown user user-menu">
                                            <a style="cursor:pointer;" class="dropdown-toggle" data-toggle="dropdown">
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
                                                            <small>Last Login <br><?php echo date('jS M Y \a\t h:i:s A', strtotime(get_from_session('last_login'))); ?></small>
                                                        </p>
                                                    </div>
                                                    <div class="right_sectn">
                                                        <ul class="user-menu">
                                                            <?php
                                                            $branch = $this->db->select('id')
                                                                    ->from(tablename('account_settings'))
                                                                    ->get()
                                                                    ->result();
                                                            ?>
                                                            <li><a href="<?php echo site_url('admin/profile'); ?>"><i data-feather="user"></i>&nbsp; Profile</a></li>
                                                            <?php  if (count($branch) == 1) : ?>
                                                                <li><a href="<?php echo site_url('admin/logout'); ?>"><i data-feather="x-circle"></i>&nbsp; Close</a></li>
                                                            <?php else: ?>
                                                                <!-- <li><a href="<?php echo site_url('admin/branch'); ?>"><i data-feather="x-circle"></i>&nbsp; Close</a></li> -->
                                                            <?php endif; ?>
                                                            <li><a href="<?php echo site_url('admin/branch'); ?>"><i data-feather="log-out"></i>&nbsp; Logout</a></li>
                                                            <!-- <li><a href="javascript:void"><i data-feather="life-buoy"></i>&nbsp; Help</a></li> -->
                                                            <li class="hidden"><a href="#hotKeyModal" data-toggle="modal"><i data-feather="life-buoy"></i>&nbsp; Hot Keys</a></li>
                                                        </ul>

                                                    </div>
                                                </li>
                                                <!-- Menu Body -->

                                                <!-- Menu Footer-->
                                                <?php if(!empty($companylist)) : ?>
                                                <!-- <li class="user-footer">

                                                    <input type="search" id="search_company_name" class="form-control" placeholder="Search company">
                                                    <ul class="company-list">

                                                        <?php foreach($companylist as $company): ?>
                                                        <li>
                                                            <a href="#" data-url="<?php echo ACCOUNT_URL; ?>admin/checklogin" data-act="<?php echo $company->id . '_' . $this->session->userdata('userid'); ?>"  class="switch_company"> <?php echo $company->company_name; ?>&nbsp;(<?php echo ($company->company_alias != "") ? $company->company_alias . " -" : ''; ?>&nbsp;<?php echo ($company->company_code != "") ? $company->company_code : ''; ?>)</a>
                                                        </li>
                                                        <?php endforeach; ?>
                                                    </ul>

                                                </li> -->
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
            <!-- Sidebar -->
            <aside class="main-sidebar" >
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar" id="sidebar-wrapper">


                <!-- .main-menu -->
                <div class="clearfix">
                    <ul class="sidebar-nav main-menu" style="top:0;">
                        <?php getSidebarData(); ?>
                    </ul>
                </div>
                <script type="text/javascript">

                        // $(document).ready(function() {

                        //     var base_url = location.protocol + '//' + location.host + '/';// + 'P-008-accounts/';


                        //     $('.menu-btn').click(function() {
                        //         console.log('lll');
                        //         $('#sidemenu-first-level ul').html('');

                        //         var type = $(this).find('.menu-text').attr('data-type');
                        //         var context = $(this).find('.menu-text').text();

                        //         $('#sidemenu-first-level h5 span').text(context);

                        //         $.ajax({
                        //             type: 'POST',
                        //             url: base_url + 'index.php/sidemenu/admin/getSubMenu/' + type,
                        //             cache: false,
                        //             success: function(result) {



                        //                 $('#sidemenu-first-level ul').html(result);

                        //             }

                        //         })

                        //     });






                        // });
                    </script>

                    <div class="sidebar-bottom">
                        <a href="<?php echo $this->config->item('base_url'); ?>admin" class="act-logo-footer"><img src="<?php echo base_url('assets/admin/sketch_custom/images/act-logo-small.png'); ?>"></a>

                        <div class="progress progress-space">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 25%;">
                            </div>
                        </div>
                        <div class="used-space">
                            Used <strong><?php echo  $current_project_size; ?></strong> MB <?php echo number_format(($current_project_size/(PROJECT_SIZE_LIMIT_FORMATED*1024))*100,2)."%"; ?> of<br> <?= PROJECT_SIZE_LIMIT_FORMATED ?>
                        </div>
                    </div>

                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content -->

            <div class="content-wrapper">
                <!-- Content Header (Page header) -->

                <?php echo $content; ?>


            </div><!-- /.content-wrapper -->


            <footer class="main-footer hidden">
                <div class="pull-right hidden-xs">
                    Powered by <b><a title="Sketch Web Solutions" data-toggle="tooltip" href="https://sketchwebsolutions.com/" target="_blank">CartFace (v2.0)</a></b>
                </div>
                Used space <strong><?= PROJECT_CURRENT_SIZE_FORMATED ?></strong> out of <?= PROJECT_SIZE_LIMIT_FORMATED ?>.
            </footer>
        </div><!-- ./wrapper -->

        <div class="task-loader">
            <i class="fa fa-2x fa-pulse fa-spinner" aria-hidden="true"></i>
        </div>

        <!-- Hot Key Modal -->
        <div id="hotKeyModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Hot Key List</h4>
              </div>
              <div class="modal-body">
                <?php if (!empty($hot_keys)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Shortcut</th>
                                <th>Function</th>
                            </tr>
                        </thead>
                        <?php foreach ($hot_keys as $key => $value): ?>
                            <tr>
                                <td><?php echo $value->shortcut; ?></td>
                                <td><?php echo $value->function; ?></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                <?php endif ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>

          </div>
        </div>

        <!-- Sagnik -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/account/js/sagnik_custom.js"></script>
        <!-- Menu Toggle Script -->
        <script>
                        $("#menu-toggle").click(function(e) {
                            e.preventDefault();
                            $("#wrapper").toggleClass("toggled");
                        });

        </script>
        <script>

            $(document).ready(function() {

                var base_url = location.protocol + '//' + location.host + '/' + window.location.pathname.split('/')[1] + '/';

                var url = window.location.href;
                var lastPart = url.split("/").pop();


                $.ajax({
                    type: 'POST',
                    url: base_url + 'index.php/sidemenu/admin/getMenuDetails/' + lastPart,
                    dataType: 'json',
                    cache: false,
                    success: function(result) {
                        var menuId = result['menuId'];
                        var parentId = result['parentId'];
                        var groupId = result['groupId'];



                        /* if no parent id then only call load menu func else both  */
                        var noParent = false;

                        if (parentId == null) {
                            noParent = true;

                        }

                        if (parseInt(groupId) != 1 && parseInt(groupId) != 2) {
                            var LoadMenu = new loadMenu();
                            LoadMenu.run(groupId);
                            LoadMenu.sideMenuChild(groupId, parentId, menuId, function(parentId, menuId) {
                                if (noParent == false) {
                                    LoadMenu.sideMenuChildrenLoad(parentId, menuId);
                                }

                            });

                            $('.main-menu li[class=' + groupId + '] a').addClass('sidemenu-active');

                        } else {
                            // console.log("yo");
                            $('.main-menu li[class=' + groupId + '] a').addClass('sidemenu-active');
                        }










                    }

                })


            });



            function loadMenu() {

                var self = this;


                self.run = function(type) {

                    // var type =  $(this).find('.menu-text').attr('data-type');


                    if ($('.search-container').hasClass('search-div-show')) {
                        $('.search-container').animate({"left": "250px"}, "fast", function() {
                            $(this).fadeOut('fast').addClass('search-div-hide');

                        });
                    }


                    $('#sidebar-wrapper').css({
                        'background': ' #272C30'
                    });

                }


                self.sideMenuChild = function(type, parentId, menuId, callback) {

                    // console.log(status);
                    // console.log("2 " + type);
                    // console.log("2 " + noParent);
                    // console.log("2 " + parentId);

                    $('.side-menu-child[data-type=' + type + ']').animate({"left": "52px"}, "fast", function() {

                        if (parseInt(parentId) == 0 || parentId == null) {
                            $(this).find('[data-target=sideMenuChild' + menuId + ']').addClass('sidemenu-active');
                        } else {
                            $(this).find('[data-target=sideMenuChild' + parentId + ']').addClass('sidemenu-active');
                        }


                        $('.menu-text').fadeOut('fast');
                        $('.side-menu-child-single').fadeOut('fast').removeClass('selected');
                        $('.side-menu-child-single#' + type).toggle('slide', {direction: 'right'}, 100).addClass('selected');

                        $(this).fadeIn('fast').addClass('opened');
                        callback(parentId, menuId);


                    });

                };


                self.sideMenuChildrenLoad = function(target, menuId) {

                    /* For sidemenu child  */

                    // var target = $(this).attr('data-target');
                    var _this = $('.sideMenuBtn[data-target=sideMenuChild' + target + ']');


                    if (_this.find('.chevron-selector-sideMenuChild').hasClass('fa-chevron-right')) {

                        _this.find('.chevron-selector-sideMenuChild').removeClass('fa-chevron-right').addClass('fa-chevron-down');


                        $('.sidemenu-second-level[id=sideMenuChild' + $.trim(target) + ']').slideDown('fast');
                        $('.sidemenu-second-level').find('li a[data-child=' + menuId + ']').addClass('sidemenu-active');
                        //$('.sidemenu-second-level[id=sideMenuChild89]').slideDown('fast');

                    }


                }


            }


        </script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/bootstrap/js/jasny-bootstrap.min.js" type="text/javascript"></script>
        <!-- Select2 -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/select2/select2.full.min.js"></script>
        <!-- Slimscroll -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <!-- FastClick -->
        <script src='<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/fastclick/fastclick.min.js'></script>
        <!-- AdminLTE App -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/dist/js/app.min.js" type="text/javascript"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/dist/js/demo.js" type="text/javascript"></script>
        <!-- daterangepicker-->
        <!-- <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/bootstrap-daterangepicker/moment.min.js"></script> -->
        <!-- <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/admin/plugins/bootstrap-daterangepicker/daterangepicker.js"></script> -->
        <!-- Developer JS -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/developer.js"></script>
        <!-- Accounts Js start -->
        <script src="<?php echo $this->config->item('base_url'); ?>assets/account/global/scripts/metronic.js" type="text/javascript"></script>

        <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/html2canvas.min.js"></script>

        <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/jspdf.min.js"></script>


        <script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>assets/account/js/sketch-custom.js"></script>
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/jquery-ui.js"></script>
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/jquery.auto-complete.min.js"></script>
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/tautocomplete.js"></script>
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/scalling.js"></script>
        <?php $RTR = & load_class('Router', 'core'); ?>
        <?php if ($RTR->fetch_module() == 'transaction_inventory' || $RTR->fetch_module() == 'bill_of_material'): ?>
            <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/transaction_inventory.js"></script>
        <?php endif; ?>
        <?php if ($RTR->fetch_module() == 'transaction'): ?>
            <script src="<?php echo $this->config->item('base_url'); ?>assets/admin/js/transaction.js"></script>
        <?php endif; ?>
        <!-- Account Is end -->
        <script>
            // stickey sub header by Anup
//            $(window).bind('scroll', function() {
//                if ($(window).scrollTop() > 0) {
//                    $('.content-header').addClass('header-sticky');
//                    $('.content').addClass('sticky-padding');
//                } else {
//                    $('.content-header').removeClass('header-sticky');
//                    $('.content').removeClass('sticky-padding');
//                }
//            });


            // Print report
            // function printDiv(divName)
            // {

            //     var printContents = $(divName).html();

            //     var originalContents = $('body').html();
            //     window.print();
            //     $('body').html(originalContents);
            // }

            // new print report function
            function printDiv(divName)
            {
                $('body .wrapper').css({display:'none'});
                var content = $("#printreport").clone();
                $('body .wrapper').before(content);
                window.print();
                $("#printreport").first().remove();
                $('body .wrapper').css({display:''});
            }

            var baseUrl = "<?php echo base_url(); ?>";


            $(function() {
                $('.save-pdf').click(function() {
                    var dataPdf = $(this).attr('data-pdf');
                    $('#mailModal .modal-footer #attachment span').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
                    $("#mailModal").modal('show');
                    var dompdf_css = '<link type="text/css" href="' + baseUrl + 'assets/admin/sketch_custom/css/dompdf.css" id="dompdf_css" rel="stylesheet">';
                    $(".pdf_class").prepend(dompdf_css);
                    var htmlContent = $('.pdf_class').html();
                    // var htmlContent = $('.pdf_class[data-pdf=' + dataPdf + ']').html();

                    $.ajax({
                        type: 'POST',
                        data: {content: htmlContent},
                        cache: false,
                        url: baseUrl + 'reports/admin/savePdf',
                        dataType: 'json',
                        success: function(data) {
                            $('.pdf_class').find('link[id="dompdf_css"]').remove();
                            if (data.res == 'success') {
                                $('#mailModal .modal-footer #attachment span').text(data.filename);
                                $('#mailModal #attachment_file').val(data.filename);
                            } else {
                                Command: toastr["error"](data.message);
                                $("#mailModal").modal('hide');
                            }


                        }

                    });
                })
            });


            $(document).ready(function() {
                $("#mailModalClose").on('click',function () {
                    var attach = $("#attachment span.attachment_name").text();

                    $.ajax({
                        url:"<?php echo base_url(); ?>reports/admin/deletePdf",
                        type: "POST",
                        data: {attach: attach},
                        success:function(response){
                            // console.log(response);
                        }
                    });
                });
            });



            // function savePDF(divName){

            //     var htmlContent = $('.'+divName).html();
            //     // var data = {content: htmlContent.serialize()};


            //     $.ajax({
            //         type: 'POST',
            //         data: {content: htmlContent},
            //         cache: false,
            //         url : baseUrl + 'reports/admin/savePdf',
            //         success: function(r){
            //             console.log(r);
            //             $("#mailModal").modal('show');
            //             $('#mailModal .modal-footer #attachment span').text(r);
            //             $('#mailModal #attachment_file').val(r);
            //         }

            //     });

            //     //console.log(htmlContent);
            // }


            $(function() {
                $('#mailModal #sendMail').click(function() {
                    $('#mailModal #sendMail').text('Sending...');
                    $('#mailModal #sendMail').attr('disabled', true);
                    var fileName = $('#mailModal #attachment_file').val();
                    var mailTo = $('#mailModal #mail_to').val();
                    var mailcc = $('#mailModal #mail_cc').val();
                    var mailBcc = $('#mailModal #mail_bcc').val();
                    var mailBody = $('#mailModal #mail_body').val();
                    var mailSubject = $('#mailModal #mail_subject').val();

                    var data = {fileName: fileName, mailTo: mailTo, mailcc: mailcc, mailBcc: mailBcc, mailBody: mailBody, mailSubject: mailSubject};

                    $.ajax({
                        type: 'POST',
                        data: data,
                        cache: false,
                        url: baseUrl + 'reports/admin/sendMail',
                        success: function(r) {
                            $('#mailModal #sendMail').text('Send');
                            $('#mailModal #sendMail').attr('disabled', false);
                            Command: toastr["success"](r);
                            $('#mailModal #attachment_file').val('');
                            $('#mailModal #mail_to').val('');
                            $('#mailModal #mail_cc').val('');
                            $('#mailModal #mail_bcc').val('');
                            $('#mailModal #mail_body').val('');
                            $('#mailModal #mail_subject').val('');
                            $('#mailModal .modal-footer #attachment span').text('');
                            $("#mailModal").modal('hide');
                        },
                        error: function(res) {
                            console.log(res);
                        }

                    });

                });
            });


            $(".select2").select2();



        </script>
        <script>
            var scrollheight = $(window).height();
            var header_height = $('.main-header').height();
            var title_height = $('.content-header').height();
            var final_height = parseInt(scrollheight) - (parseInt(header_height) + parseInt(title_height));

            $('#chat-box').slimScroll({height: final_height + 'px'});


        </script>

        <!-- mail modal -->

        <div id="mailModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" id="mailModalClose">&times;</button>
                        <h4 class="modal-title">Send Mail</h4>
                    </div>
                    <div class="modal-body sendMailPopup">
                        <div class="form-groupx clearfix">
                            <input class="form-control" id="mail_to" required="required" autocomplete="on" placeholder="To" style="max-width">
                            <div class="mailModalbtns">
                                <button class="btn btn-default btn-cc">CC</button>
                                <button class="btn btn-default btn-bcc">BCC</button>
                            </div>
                        </div>
                        <div id="mailModalcc" class="form-groupx clearfix" style="display:none">
                            <input class="form-control" id="mail_cc" placeholder="CC">
                        </div>
                        <div id="mailModalbcc" class="form-groupx clearfix" style="display:none">
                            <input class="form-control" id="mail_bcc" placeholder="BCC">
                        </div>

                        <div class="form-groupx clearfix">
                            <input class="form-control" id="mail_subject" required="required" placeholder="Subject">
                        </div>
                        <div class="form-groupx clearfix">
                            <textarea class="form-control" id="mail_body" rows="6" placeholder="Message"></textarea>
                            <input type="hidden" id="attachment_file">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p class="pull-left" id="attachment"><i class="fa fa-paperclip"></i> <span class="attachment_name"></span></p>
                        <button type="button" class="btn btn-primary pull-right" id="sendMail">Send</button>
                    </div>
                </div>

            </div>
        </div>
     <!-- add branch Modal -->
<div id="addBranchModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo site_url('admin/save_selected_branch'); ?>" method="POST" id="save-selected-branch">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select Branch</h4>
                </div>
                <div class="modal-body">
                    <?php
                    $all_branch = $this->db->select('id,company_name')
                            ->from(tablename('account_settings'))
                            ->get()
                            ->result();
                    ?>
                    <select class="form-control select2" name="selected_branch[]" id="selected_branch" multiple="multiple">
                        <option value="">Select branch</option>
                        <?php
                         $arr =($this->session->userdata('selected_branch'))? $this->session->userdata('selected_branch'):[$this->session->userdata('branch_id')];
                        foreach ($all_branch as $r) {
                            ?>
                            <option value="<?php echo $r->id; ?>"  <?php echo in_array($r->id, $arr) ? 'selected' : '' ?>><?php echo $r->company_name; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary ladda-button save-selected-branch-btn" data-color="blue" data-style="expand-right" data-size="xs">Save</button>
                </div>
            </form>
        </div>

    </div>
</div>
        <script>
          $("body").delegate("#select-branch", "click", function() {
               $(".select2").select2();
        $("#addBranchModal").modal('show');
    });

    $("#save-selected-branch").submit(function(event) {
        event.preventDefault();
        var l = Ladda.create(document.querySelector('.save-selected-branch-btn'));
        l.start();
        var form = $(this),
                url = form.attr('action'),
                data = form.serialize();
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(data) {
                l.stop();
                if (data.res == 'error') {
                    Command: toastr["error"](data.message);
                } else {
                    Command: toastr["success"](data.message);
                    location.reload();
                }
            }
        });

    });
        </script>
        <script>
            // MailModal CC BCC
            $(".btn-cc").click(function() {
                $("#mailModalcc").slideToggle('fast');
            });
            $(".btn-bcc").click(function() {
                $("#mailModalbcc").slideToggle('fast');
            });

            // swith to a company from another company
            $(document).ready(function(){
                $(".switch_company").on("click", function() {
                    var obj = $(this);
                    var value = obj.data('act');
                    var url = obj.data('url');
                    $.ajax({
                        url: url,
                        dataType: 'jsonp',
                        jsonp: "accountcallback",
                        data: {value: value},
                        success: function(data) {
                            if (data.res == 'success') {
                                Command: toastr["success"](data.message);
                                window.location.href = data.url;
                            } else {
                                Command: toastr["error"](data.message);
                            }
                        }
                    });
                });
            });

        </script>

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
        </script>

        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ajaxdownloader.min.js"></script>
        <script>
            // $(function() {
            //     $('.download-pdf').click(function() {
            //         var baseUrl = "<?php echo base_url(); ?>";
            //         var dompdf_css = '<link type="text/css" href="' + baseUrl + 'assets/admin/sketch_custom/css/dompdf.css" id="dompdf_css" rel="stylesheet">';
            //         $(".pdf_class").prepend(dompdf_css);
            //         var htmlContent = $('.pdf_class').html();
            //         var pdf_name = $('.pdf_class').data('pdf');
            //         // console.log(pdf_name);
            //         $.AjaxDownloader({
            //             url : "<?php echo base_url(); ?>transaction_inventory/inventory/savePdf",
            //             data : {
            //                 content: htmlContent,
            //                 pdf_name: pdf_name
            //             }
            //         });
            //         $('.pdf_class').find('link[id="dompdf_css"]').remove();
            //     });
            // });

            $(function() {
                $('.download-pdf').click(function() {
                    var baseUrl = "<?php echo base_url(); ?>";
                    var dompdf_css = '<link type="text/css" href="' + baseUrl + 'assets/admin/sketch_custom/css/dompdf.css" id="dompdf_css" rel="stylesheet">';

                    $(".pdf_class").prepend(dompdf_css);
                    var htmlContent = $('.pdf_class').html();
                    var pdf_name = $('.pdf_class').data('pdf');
                    $.ajax({
                        url : "<?php echo base_url(); ?>transaction_inventory/inventory/savePdf",
                        type: "POST",
                        data : {
                            content: htmlContent,
                            pdf_name: pdf_name
                        },
                        success: function(response) {
                            $("#pdfPreviewModal").modal('show');
                            $('#thepdf').removeAttr('src');
                            $('#thepdf').attr('src', response);
                            var pieces = response.split('/');
                            $("#previewPdfFile").val(pieces[pieces.length-1]);
                            console.log(pieces[pieces.length-1]);
                        },
                        error: function(response) {

                        }
                    });
                    $('.pdf_class').find('link[id="dompdf_css"]').remove();
                });

                $(".previewPdfClose").on('click', function() {
                    // console.log($("#previewPdfFile").val());
                    var attach = $("#previewPdfFile").val();
                    $.ajax({
                        url:"<?php echo base_url(); ?>reports/admin/deletePdf",
                        type: "POST",
                        data: {attach: attach},
                        success:function(response){
                            // console.log(response);
                        }
                    });
                });
                // $(".printreport").hide();
            });

            $(function() {
                $(':input').attr('autocomplete', 'off');
            });
        </script>

        <!-- Report Modal -->
        <div class="modal fade" id="reportModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Search Reports</h4>
                    </div>
                    <div class="modal-body" style="max-height: 400px;overflow-y: auto;">
                        <div class="form-group">
                            <input type="text" name="report_search" id="report_search" class="form-control" placeholder="Search" autofocus="" autocomplete="off" value=""/>
                            <input type="hidden" name="report_search_url" id="report_search_url" value="">
                        </div>
                        <div id="report_urls"></div>
                    </div>
                    <div class="text-center" id="err-div" style="color: red;"></div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="report_search_submit">Go</button>
                    </div>
                </div>

            </div>
        </div>

        <div id="bankSelectModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Bank Options</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>



        <!-- change preference using modal -->

        <!-- Preference Modal -->
        <div class="modal fade" id="preferenceModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <form role="form" action="<?php echo base_url('accounts_configuration/admin/configurationmodify'); ?>" method="post" id="configuration_form" enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Preference</h4>
                    </div>
                    <div class="modal-body">

                        <!-- preference section -->

                        <?php $preference = getPreferences(); ?>

                        <div class="box-body table-fullwidth table-configuration">
                            <div class="table-responsive">
                                <table class="table table-striped lcol-125">
                                    <thead>
                                        <tr role="row" class="sub-childs">
                                            <th>Properties </th>
                                            <th>Settings </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Skip date field in create mode (for faster entry)</td>
                                            <td><label>
                                                <input type="radio" value="1" name="skip_date_create" class="minimal" <?php
                                                if (isset($preference['configuration']->skip_date_create) && $preference['configuration']->skip_date_create == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="skip_date_create" class="minimal" <?php
                                                if (isset($preference['configuration']->skip_date_create) && $preference['configuration']->skip_date_create == '2') {
                                                    echo 'checked';
                                                }
                                                ?>>
                                                No
                                            </label> </td>
                                        </tr>
                                        <tr class="hidden">
                                            <td>Use To/By instead of Dr/Cr during entry</td>
                                            <td><label>
                                                <input type="radio" value="1" name="toby_instead_drcr" class="minimal" <?php
                                                if (isset($preference['configuration']->toby_instead_drcr) && $preference['configuration']->toby_instead_drcr == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="toby_instead_drcr" class="minimal" <?php
                                                if (isset($preference['configuration']->toby_instead_drcr) && $preference['configuration']->toby_instead_drcr == '2') {
                                                    echo 'checked';
                                                }
                                                ?>>
                                                No
                                            </label> </td>
                                        </tr>
                                        <tr class="hidden">
                                            <td>Use cheque printing for contra</td>
                                            <td><label>
                                                <input type="radio" value="1" name="contra_cheque_printing" class="minimal" <?php
                                                if (isset($preference['configuration']->contra_cheque_printing) && $preference['configuration']->contra_cheque_printing == '1') {
                                                    echo 'checked';
                                                }
                                                ?>>
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="contra_cheque_printing" class="minimal" <?php
                                                if (isset($preference['configuration']->contra_cheque_printing) && $preference['configuration']->contra_cheque_printing == '2') {
                                                    echo 'checked';
                                                }
                                                ?>>
                                                No
                                            </label>  </td>
                                        </tr>
                                        <tr class="hidden">
                                            <td>Warn on Nagetive cash balance(beep with every entry)</td>
                                            <td> <label>
                                                <input type="radio" value="1" name="warn_nagetive_cash_balance" class="minimal" <?php
                                                if (isset($preference['configuration']->warn_nagetive_cash_balance) && $preference['configuration']->warn_nagetive_cash_balance == '1') {
                                                    echo 'checked';
                                                }
                                                ?>>
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="warn_nagetive_cash_balance" class="minimal" <?php
                                                if (isset($preference['configuration']->warn_nagetive_cash_balance) && $preference['configuration']->warn_nagetive_cash_balance == '2') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                        </tr>
                                        <tr class="hidden">
                                            <td>Show ledger current balance in voucher</td>
                                            <td><label>
                                                <input type="radio" value="1" name="voucher_ledger_current_balance" class="minimal" <?php
                                                if (isset($preference['configuration']->voucher_ledger_current_balance) && $preference['configuration']->voucher_ledger_current_balance == '1') {
                                                    echo 'checked';
                                                }
                                                ?>>
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="voucher_ledger_current_balance" class="minimal" <?php
                                                if (isset($preference['configuration']->voucher_ledger_current_balance) && $preference['configuration']->voucher_ledger_current_balance == '2') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                        </tr>
                                        <tr class="hidden">
                                            <td >Show balance as on voucher date</td>
                                            <td><label>
                                                <input type="radio" value="1" name="voucher_date_balance_show" class="minimal" <?php
                                                if (isset($preference['configuration']->voucher_date_balance_show) && $preference['configuration']->voucher_date_balance_show == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="voucher_date_balance_show" class="minimal" <?php
                                                if (isset($preference['configuration']->voucher_date_balance_show) && $preference['configuration']->voucher_date_balance_show == '2') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label>  </td>
                                        </tr>
                                        <tr>
                                            <td>Code for group creation form will be auto generated</td>
                                            <td><label>
                                                <input type="radio" value="1" name="group_code_status" class="minimal" <?php
                                                if (isset($preference['configuration']->group_code_status) && $preference['configuration']->group_code_status == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="group_code_status" class="minimal" <?php
                                                if (isset($preference['configuration']->group_code_status) && $preference['configuration']->group_code_status == '2') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label></td>
                                        </tr>
                                        <tr>
                                            <td>Code for ledger creation form will be auto generated </td>
                                            <td><label>
                                                <input type="radio" value="1" name="ledger_code_status" class="minimal" <?php
                                                if (isset($preference['configuration']->ledger_code_status) && $preference['configuration']->ledger_code_status == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="ledger_code_status" class="minimal" <?php
                                                if (isset($preference['configuration']->ledger_code_status) && $preference['configuration']->ledger_code_status == '2') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label></td>
                                        </tr>
                                        <tr>
                                            <td>Number for voucher creation form will be auto generated </td>
                                            <td><label>
                                                <input type="radio" value="1" name="voucher_no_status" class="minimal" <?php
                                                if (isset($preference['configuration']->voucher_no_status) && $preference['configuration']->voucher_no_status == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="voucher_no_status" class="minimal" <?php
                                                if (isset($preference['configuration']->voucher_no_status) && $preference['configuration']->voucher_no_status == '2') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                        </tr>
                                        <tr>
                                            <td>Do you want product attributes?</td>
                                            <td><label>
                                                <input type="radio" value="1" name="requir_product_attributes" class="minimal" <?php
                                                if (isset($preference['configuration']->requir_product_attributes) && $preference['configuration']->requir_product_attributes == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="requir_product_attributes" class="minimal" <?php
                                                if (isset($preference['configuration']->requir_product_attributes) && $preference['configuration']->requir_product_attributes == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                        </tr>
                                        <tr>
                                            <td>Do you want your account with inventory?</td>
                                            <td><label>
                                                <input type="radio" value="1" name="is_inventory" class="minimal" <?php
                                                if (isset($preference['configuration']->is_inventory) && $preference['configuration']->is_inventory == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="is_inventory" class="minimal" <?php
                                                if (isset($preference['configuration']->is_inventory) && $preference['configuration']->is_inventory == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                        </tr>
                                        <tr>
                                            <td>Do you want updated currency value time of entry?</td>
                                            <td><label>
                                                <input type="radio" value="1" name="required_updated_currency" class="minimal" <?php
                                                if (isset($preference['configuration']->required_updated_currency) && $preference['configuration']->required_updated_currency == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="required_updated_currency" class="minimal" <?php
                                                if (isset($preference['configuration']->required_updated_currency) && $preference['configuration']->required_updated_currency == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                        </tr>
                                        <tr>
                                            <td>Do you want to show Ledger and Group Code before name?</td>
                                            <td><label>
                                                <input type="radio" value="1" name="code_before_name" class="minimal" <?php
                                                if (isset($preference['configuration']->code_before_name) && $preference['configuration']->code_before_name == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="code_before_name" class="minimal" <?php
                                                if (isset($preference['configuration']->code_before_name) && $preference['configuration']->code_before_name == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                        </tr>
                                        <tr>
                                            <td>Do you want which type of currency format?</td>
                                            <td><label>
                                                <input type="radio" value="1" name="price_format" class="minimal" <?php
                                                if (isset($preference['configuration']->price_format) && $preference['configuration']->price_format == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                US
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="price_format" class="minimal" <?php
                                                if (isset($preference['configuration']->price_format) && $preference['configuration']->price_format == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                IN
                                            </label> </td>
                                        </tr>
                                    <tr>
                                        <td>Do you want recurring entry?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="want_recurring" class="minimal" <?php
                                                if (isset($preference['configuration']->want_recurring) && $preference['configuration']->want_recurring == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="want_recurring" class="minimal" <?php
                                                if (isset($preference['configuration']->want_recurring) && $preference['configuration']->want_recurring == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                        <tr>
                                            <td>Do you want sales mail?</td>
                                            <td><label>
                                                <input type="radio" value="1" name="sales_mail" class="minimal" <?php
                                                if (isset($preference['configuration']->sales_mail) && $preference['configuration']->sales_mail == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="sales_mail" class="minimal" <?php
                                                if (isset($preference['configuration']->sales_mail) && $preference['configuration']->sales_mail == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                        </tr>
                                        <tr>
                                            <td>Do you want sales order mail?</td>
                                            <td><label>
                                                <input type="radio" value="1" name="sales_order_mail" class="minimal" <?php
                                                if (isset($preference['configuration']->sales_order_mail) && $preference['configuration']->sales_order_mail == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="sales_order_mail" class="minimal" <?php
                                                if (isset($preference['configuration']->sales_order_mail) && $preference['configuration']->sales_order_mail == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                        </tr>
                                        <tr>
                                            <td>Do you want receipt mail?</td>
                                            <td><label>
                                                <input type="radio" value="1" name="receipt_mail" class="minimal" <?php
                                                if (isset($preference['configuration']->receipt_mail) && $preference['configuration']->receipt_mail == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="receipt_mail" class="minimal" <?php
                                                if (isset($preference['configuration']->receipt_mail) && $preference['configuration']->receipt_mail == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                        </tr>
                                        <tr>
                                            <td>Do you want bank details in invoice?</td>
                                            <td><label>
                                                <input type="radio" value="1" name="bank_details" id="yesBank" class="minimal" <?php
                                                if (isset($preference['configuration']->bank_details) && $preference['configuration']->bank_details == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="bank_details" class="minimal" <?php
                                                if (isset($preference['configuration']->bank_details) && $preference['configuration']->bank_details == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                        </tr>
                                    <tr>
                                        <td>Do you want e-way bill?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="eway_bill" id="eway-bill" class="minimal" <?php
                                                if (isset($preference['configuration']->eway_bill) && $preference['configuration']->eway_bill == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="eway_bill" class="minimal" <?php
                                                if (isset($preference['configuration']->eway_bill) && $preference['configuration']->eway_bill == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want to enable godown?</td>
                                        <td><label>
                                                <input type="radio" value="1" name="godown" id="" class="minimal" <?php
                                                if (isset($preference['configuration']->godown) && $preference['configuration']->godown == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="godown" class="minimal" <?php
                                                if (isset($preference['configuration']->godown) && $preference['configuration']->godown == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label> </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want to enable batch?</td>
                                        <td>
                                            <label>
                                                <input type="radio" value="1" name="batch" id="" class="minimal" <?php
                                                if (isset($preference['configuration']->batch) && $preference['configuration']->batch == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="batch" class="minimal" <?php
                                                if (isset($preference['configuration']->batch) && $preference['configuration']->batch == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Do you want to enable print option?</td>
                                        <td>
                                            <label>
                                                <input type="radio" value="1" name="print" id="" class="minimal" <?php
                                                if (isset($preference['configuration']->print) && $preference['configuration']->print == '1') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="print" class="minimal" <?php
                                                if (isset($preference['configuration']->print) && $preference['configuration']->print == '0') {
                                                    echo 'checked';
                                                }
                                                ?> >
                                                No
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>How many days an entry can be edited?</td>
                                        <td><input type="number" name="entry_action_limit" min="0" class="form-control" value="<?php echo $preference['configuration']->entry_action_limit; ?>"></td>
                                    </tr>
                                        <tr>
                                            <td>Which currency unit want?</td>
                                            <td style="padding-top:5px !important;padding-bottom:4px !important">
                                                <select class="form-control" name="selected_currency">
                                                    <?php
                                                    if (count($preference['currency']) > 0):
                                                        foreach ($preference['currency'] as $value) {
                                                            ?>
                                                            <option value="<?php echo $value->id ?>" <?php echo ($value->id == $preference['configuration']->selected_currency) ? 'selected' : '' ?>><?php echo $value->currency ?></option>
                                                            <?php
                                                        }
                                                    endif;
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Which date format do you want?</td>
                                            <td style="padding-top:4px !important;padding-bottom:4px !important">
                                                <select class="form-control" name="selected_date_format">
                                                    <?php
                                                    if (count($preference['date_format']) > 0):
                                                        foreach ($preference['date_format'] as $value) {
                                                            ?>
                                                            <option value="<?php echo $value->id ?>" <?php echo ($value->id == $preference['configuration']->selected_date_format) ? 'selected' : '' ?>><?php echo $value->dateformat ?></option>
                                                            <?php
                                                        }
                                                    endif;
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php if ($preference['number_of_branch'] > 1): ?>
                                            <tr>
                                                <td colspan="2" style="padding-top:0 !important;padding-bottom:0 !important;">
                                                    <span class="pull-left" style="padding:10px 0;">Which branch data are you want to show?</span>
                                                    <span  style="float: right;padding:3px 0;" class="config-select2" >
                                                        <select multiple="multiple" class="form-control select2" name="selected_branch[]" id="selected_branch">
                                                            <?php
                                                            $arr =($this->session->userdata('selected_branch'))? $this->session->userdata('selected_branch'):[$this->session->userdata('branch_id')];
                                                            foreach ($preference['branch'] as $b) {
                                                                ?>
                                                                <option value="<?php echo $b->id ?>" <?php echo in_array($b->id, $arr) ? 'selected' : '' ?>><?php echo $b->company_name ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endif ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- preference section -->

                    </div>
                    <div class="modal-footer">
                        <?php
                        $permissionedit = admin_users_permission('E', 'configurations', $rtype = FALSE);
                        if ($permissionedit) {
                        ?>
                            <button class="btn btn-sm btn-primary save_preference ladda-button" data-color="blue" data-style="expand-right" data-size="xs">Save</button>
                        <?php } ?>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
                </div>

            </div>
        </div>

        <div id="bankDetailsModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Bank Details</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="bankForInvoiceSave" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- courier details modal -->
        <div id="courierSelectModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Despatch Details</h4>
                    </div>
                    <div class="modal-body">
                       <form action="" method="post" id="courierForInvoiceForm">
                            <div class="row" >
                                <div class="form-group col-md-6">
                                    <label>Despatched Through</label>
                                    <input type="text" class="form-control modal_despatch_through" placeholder="Despatched Through" name="despatch_through" id="modal_despatch_through" value="" autocomplete="off">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Despatch Doc. No.</label>
                                    <input type="text" class="form-control" placeholder="Despatch Doc. No." name="despatch_doc_no" id="modal_despatch_doc_no" value="" autocomplete="off">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>GSTN</label>
                                    <input type="text" class="form-control" placeholder="GSTN" name="courier_gstn" id="modal_courier_gstn" value="" autocomplete="off">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Destination</label>
                                    <input type="text" class="form-control" placeholder="Destination" name="destination" id="modal_destination" value="" autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Bill of Lading/LR-RR No.</label>
                                    <input type="text" class="form-control" placeholder="Bill of Lading/LR-RR No." name="bill_lr_rr" id="modal_bill_lr_rr" value="" autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Date</label>
                                    <input type="text" class="form-control" placeholder="DD/MM/YYYY" name="bill_lr_rr_date" id="modal_bill_lr_rr_date" value="" autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Motor Vehicle No.</label>
                                    <input type="text" class="form-control" placeholder="Motor Vehicle No." name="motor_vehicle_no" id="modal_motor_vehicle_no" value="" autocomplete="off">
                                </div>
                            </div>
                       </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="courierForInvoiceSave" class="btn btn-primary" data-dismiss="modal" >Save</button>
                        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {

                // if bank details in invoice yes then open up the bank details modal to save a primary bank
                $("#yesBank").on('click', function() {
                    $('#bankDetailsModal').modal('show');
                    $.ajax({
                        url: "<?php echo base_url(); ?>admin/getAllBankDetailsCompanyForInvoice",
                        type: "POST",
                        success: function(response) {
                            if(response.length < 1){
                                $('#bankForInvoiceSave').hide();
                            }
                            $("#bankDetailsModal .modal-body").html(response);
                        }
                    });
                });

                // primary bank update after submit
                $('#bankForInvoiceSave').on('click', function(e) {
                    e.preventDefault();
                    var data = $("#bankForInvoiceForm").serialize();
                    $('#bankForInvoiceSave').text('working...');
                    $('#bankForInvoiceSave').prop('disabled', true);
                    $.ajax({
                        url: "<?php echo base_url(); ?>admin/saveDefaultBank",
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            $('#bankForInvoiceSave').text('Save');
                            $('#bankForInvoiceSave').prop('disabled', false);
                            $("#bankDetailsModal").modal('hide');
                            Command:toastr['success']('Default bank saved');
                        }
                    });
                });

                // before save the preference check for current bank details status
                $(".save_preference").on('click', function(e) {
                    e.preventDefault();
                    var bank = $("input[type=radio][name=bank_details]:checked").val();
                    // if bank details checked as no then submit otherwise
                    // check there is a bank selected for transaction
                    if (bank == 1) {
                        $.ajax({
                            url: "<?php echo base_url(); ?>admin/getBankDetailsStatus",
                            dataType: 'JSON',
                            success: function(response) {
                                // if response it true then there is a bank selected for transaction
                                // otherwise not
                                if (response.flag) {
                                    $("#configuration_form").submit();
                                } else {
                                    Command:toastr['error']('Please select a primary bank for transaction first');
                                }
                            }
                        });
                    } else {
                        $("#configuration_form").submit();
                    }
                });

                // preferece modal form submit
                $("#configuration_form").submit(function(event) {
                    event.preventDefault();
                    var l = Ladda.create(document.querySelector('.save_preference'));
                    l.start();
                    var form = $(this),
                        url = form.attr('action'),
                        data = form.serialize();
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        dataType: 'json',
                        success: function(data) {
                            l.stop();
                            if (data.res == 'error') {
                                Command: toastr["error"](data.message);
                            } else if (data.res == 'success') {
                                $("#preferenceModal").modal('hide');
                                Command: toastr["success"](data.message);
                            }
                        }
                    });

                });


            });
        </script>

        <!-- change preference using modal ends -->

        <script>

            function openBankSelectModal() {
                $.ajax({
                    url: "<?php echo base_url(); ?>admin/showbankModalInvoice",
                    type: 'POST',
                    success: function(response) {
                        $("#bankSelectModal").modal('show');
                        $("#bankSelectModal .modal-body").html(response);
                    }
                });

            }

            function openCourierModal() {
                var entry_id = $('#entry_id').val();
                if(entry_id){
                    $.ajax({
                        url: "<?php echo base_url(); ?>transaction_inventory/inventory/despatchDetailsByEntryId",
                        type: 'POST',
                        data: {entry_id: entry_id},
                        dataType: "json",
                        success: function(DATA) {
                            if(DATA.FLAG){
                                $('#modal_despatch_doc_no').val(DATA.RESULT.despatch_doc_no);
                                $('#modal_despatch_through').val(DATA.RESULT.despatch_through);
                                $('#modal_courier_gstn').val(DATA.RESULT.courier_gstn);
                                $('#modal_destination').val(DATA.RESULT.destination);
                                $('#modal_bill_lr_rr').val(DATA.RESULT.bill_lr_rr);
                                $('#modal_bill_lr_rr_date').val(DATA.RESULT.bill_lr_rr_date);
                                $('#modal_motor_vehicle_no').val(DATA.RESULT.motor_vehicle_no);
                            }
                        }
                    });
                }
                $("#courierSelectModal").modal('show');
             }

            $(document).ready(function() {
                document.onkeyup=function(e){

                  var e = e || window.event; // for IE to cover IEs window event-object
                  // console.log(e.which);
                  if(e.altKey && e.ctrlKey && e.which == 82) { // alt+ctrl+r for pop-up the report search
                    $("#reportModal").modal('show');
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 68) { // alt+ctrl+d for pop-up the date-range
                    $("#myModal").modal('show');
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 83) { // alt+ctrl+s for pop-up the search in daybook
                    $("#searchModal").modal('show');
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 69) { // alt+ctrl+e for pop-up the search ledger
                    $("#ledgerModal").modal('show');
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 71) { // alt+ctrl+g for pop-up the add group modal
                    // $("#addGroupBtn").click();
                    $("#addGroup").modal('show');
                    $.ajax({
                        url: full_path+'transaction_inventory/inventory/getAllGroupsByAjax',
                        success: function(response) {
                            $("#addGroup #parent_id").html(response);
                        }
                    });
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 79) { // alt+ctrl+o for pop-up the group list modal
                    $("#groupModal").modal('show');
                    $("#addGroup").modal('show');
                    return false;
                  }else if(e.shiftKey && e.ctrlKey && e.which == 76) { // shift+ctrl+l for pop-up the add ledger modal
                    $("#addLedger").modal('show');
                    $.ajax({
                        url: full_path+'transaction_inventory/inventory/getAllGroupsByAjax',
                        success: function(response) {
                            $("#addLedger #group_id").html(response);
                        }
                    });
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 77) { // alt+ctrl+m for pop-up the add ledger modal
                    $("#multipleShipModal").modal('show');
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 72) { // alt+ctrl+h for pop-up the add ledger modal
                    $("#hotKeyModal").modal('show');
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 66) { // alt+ctrl+b for pop-up the add bank modal
                    openBankSelectModal();
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 80) { // alt+ctrl+p for pop-up the add preference modal
                    $("#preferenceModal").modal('show');
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 67) { // alt+ctrl+c for pop-up the dispatch modal
                    openCourierModal();
                  }else if(e.altKey && e.ctrlKey && e.which == 73) { // alt+ctrl+i for pop-up the  aging modal
                    $("#agingSummaryModal").modal('show');
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 89) { // alt+ctrl+y for pop-up the  only date for aging modal
                    $("#onlyDateAgingModal").modal('show');
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 87) { // alt+ctrl+w for pop-up the  godown modal
                    $(".add-godown").click();
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 90) { // alt+ctrl+z for pop-up the manual contact modal in requisition add
                    $("#addContactModal").modal('show');
                    $('#addContactModal').on('shown.bs.modal', function () {
                        $('#manual_contact_name').focus();
                    });
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 70) { // alt+ctrl+f for pop-up the  manual product modal in requisition add
                    $("#addProductModal").modal('show');
                    $('#addProductModal').on('shown.bs.modal', function () {
                        $('#manual_product_name').focus();
                    });
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 75) { // alt+ctrl+k for pop-up the contact modal in ledger add
                    $("#contactModal").modal('show');
                    // $('#addProductModal').on('shown.bs.modal', function () {
                    //     $('#manual_product_name').focus();
                    // });
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 74) { // alt+ctrl+j for pop-up the sales price modal in product list
                    $("#productSalesModal").modal('show');
                    return false;
                  }else if(e.altKey && e.ctrlKey && e.which == 81) { // alt+ctrl+q for pop-up the ledger edit modal in transaction form
                    var ledger_id = $("#debtors_creditors_id").val();
                    if (ledger_id == "") {
                        $("#editLedgerModal .modal-body").html("please select a ledger first");
                    } else {
                        $.ajax({
                            url: "<?php echo base_url('accounts/accounts/editLedgerForTransaction'); ?>",
                            type: "POST",
                            data: { ledger_id: ledger_id },
                            success: function(results) {
                                $("#editLedgerModal .modal-body").html(results);
                            },
                            error: function(results) {
                                window.location.reload();
                            }
                        });
                    }
                    $("#editLedgerModal").modal('show');
                    return false;
                  }
                }

                $('#reportModal').on('shown.bs.modal', function () {
                    $('#report_search').focus();
                });

                // $("#report_search").on('keyup', function() {
                //     if( $(this).val() != ""){
                //         $.ajax({
                //             url: "<?php echo base_url('admin/searchReportMenu'); ?>",
                //             type: "POST",
                //             data: { search_value: $(this).val() },
                //             success:function(response) {
                //                 $("#report_urls").html(response);
                //             }
                //         });
                //     }else{
                //         $("#report_urls").html("");
                //     }

                // });

                $("#report_search").on('keypress', function(e) {
                    if(e.which == 13){
                        $("#report_search_submit").click();
                    }
                });

                $( ".pdf_class" ).append( "<p class='base_currency'>**Amount is displayed in your base currency <span class='label label-success' ><b><?php echo getBaseCurrency(); ?></b></span></p>" );


                //ACT Date format start
                var monthDate = [0,31,28,31,30,31,30,31,31,30,31,30,31];
                var delimeter;

                $('body').delegate(".act-date-format", 'keyup', function() {

                    var field = $(this);
                    var financial_year;

                    var lastChar = $(this).val().substr($(this).val().length - 1);

                    if ( ($(this).val().length ==1 || $(this).val().length == 4) && isNaN(lastChar)) {
                        $(this).val( $(this).val().slice(0, -1) );
                    }

                    if( $(this).val().length == 2 && isNaN(lastChar)) {
                        if(lastChar == "." || lastChar == "-" || lastChar == "/"){
                            delimeter = lastChar;
                            var arrDate = field.val().split(delimeter);
                            $(this).val('0'+arrDate[0]+delimeter);

                        }else{
                           $(this).val( $(this).val().slice(0, -1) );
                        }
                    }

                    if( $(this).val().length == 5 && isNaN(lastChar)) {
                        if(lastChar == "." || lastChar == "-" || lastChar == "/"){
                            var arrDate = field.val().split(delimeter);
                            $(this).val(arrDate[0]+delimeter+'0'+arrDate[1]);

                        }else{
                           $(this).val( $(this).val().slice(0, -1) );
                        }
                    }

                    // separator should be (.),(/),(-)
                    if( $(this).val().length == 3 || $(this).val().length == 6 ) {
                        if(lastChar != "." && lastChar != "-" && lastChar != "/"){
                            $(this).val( $(this).val().slice(0, -1) );
                        }
                    }

                  // set the user choosen delimeter
                  if($(this).val().length == 3){
                    delimeter = $(this).val().substr(2);
                  }

                  if($(this).val().length == 2 && $(this).val() > 31){
                    $(this).val(31);
                    // $(this).val($(this).val() + '/');
                  }else if($(this).val().length == 5){
                    var arrStartDate = field.val().split(delimeter);


                    // month cannot be greater than 12
                    if(arrStartDate[1] > 12){
                      $(this).val( $(this).val().slice(0, -1) );
                    }else{

                      var month = arrStartDate[1];
                      if( arrStartDate[1] < 10 ){
                        arrStartDate[1] = month[month.length -1];
                      }

                      // you can not enter more days than a month can have,
                      // like if you enter 31/11 then it automatically changes to 30/11
                      // because last day of November is 30
                      if(arrStartDate[0] > monthDate[arrStartDate[1]] && arrStartDate[1] > 9){
                        $(this).val( monthDate[arrStartDate[1]] + delimeter + arrStartDate[1] ); // if month is greater than 9 it will show as it is
                      }else if(arrStartDate[0] > monthDate[arrStartDate[1]] && arrStartDate[1] < 9){
                        $(this).val( monthDate[arrStartDate[1]] + delimeter +'0' + arrStartDate[1] ); // otherwise it will append to 0
                      }

                        var current_url = window.location.protocol + "//" + window.location.hostname;
                        var target_url = current_url+"/admin/getCurrentFinancialYearForDateRange";
                        // console.log(target_url);
                        $.ajax({
                            url: target_url,
                            type:"POST",
                            data:{month:arrStartDate[1]},
                            async: false,
                            success: function(response){
                                financial_year = $.trim(response);
                            }
                        });

                      $(this).val($(this).val() + delimeter + financial_year);
                    }



                  }
                });

                //Act date format end

            });

            $("#report_search").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url:"<?php echo base_url(); ?>admin/searchReportMenuJson",
                        type: 'POST',
                        cache: false,
                        data: { search_value : request.term},
                        dataType: 'json',
                        success: function(data){
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function(e, ui) {
                    e.preventDefault() // <--- Prevent the value from being inserted.
                    $(this).val(ui.item.value);
                    $("#report_search_url").val(ui.item.url);
                }
            });

            $("#report_search_submit").on('click', function(e) {
                e.preventDefault();
                var search_url = $("#report_search_url").val();
                if( search_url != "" ) {
                    window.location.href = "<?php echo base_url(); ?>"+search_url;
                }
            });
        </script>

        <!-- css and js files for excel generation of reports -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/table_export/css/tableexport.css">
        <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.6/xls.core.min.js"></script> -->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/table_export/js/xlsx.core.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/table_export/js/Blob.min.js"></script>
        <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/blob-polyfill/2.0.20171115/Blob.min.js"></script> -->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/table_export/js/FileSaver.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/table_export/js/tableexport.js"></script>
        <script>
            // new TableExport(document.getElementsByTagName("table"));
            $(".export-excel").on('click', function() {
                TableExport(document.getElementsByClassName("table-report"), {
                    headers: true,                              // (Boolean), display table headers (th or td elements) in the <thead>, (default: true)
                    footers: true,                              // (Boolean), display table footers (th or td elements) in the <tfoot>, (default: false)
                    formats: ['xlsx'],             // (String[]), filetype(s) for the export, (default: ['xls', 'csv', 'txt'])
                    filename: $(".pdf_class").data('pdf'),                             // (id, String), filename for the downloaded file, (default: 'id')
                    bootstrap: true,                           // (Boolean), style buttons using bootstrap, (default: true)
                    exportButtons: true,                        // (Boolean), automatically generate the built-in export buttons for each of the specified formats (default: true)
                    position: 'bottom',                         // (top, bottom), position of the caption element relative to table, (default: 'bottom')
                    ignoreRows: null,                           // (Number, Number[]), row indices to exclude from the exported file(s) (default: null)
                    ignoreCols: null,                           // (Number, Number[]), column indices to exclude from the exported file(s) (default: null)
                    trimWhitespace: true                        // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s) (default: false)
                });
                $("button.xlsx").click();
                $("button.xlsx").remove();

            });

        </script>

        <!-- Modal -->
        <div id="pdfPreviewModal" class="modal fade" role="dialog">
          <div class="modal-dialog" style="width: 80%; max-width:900px;">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close previewPdfClose" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PDF Preview</h4>
              </div>
              <div class="modal-body" style="padding: 1px;">
                <input type="hidden" name="previewPdfFile" id="previewPdfFile" value="">
                <embed id="thepdf" src="" width="100%" height="500" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">
              </div>
              <!-- <div class="modal-footer">
                <button type="button" class="btn btn-danger previewPdfClose" data-dismiss="modal">Close</button>
              </div> -->
            </div>

          </div>
        </div>

        <script>
            $(document).ready(function() {

                /* ============= print option enable/disable */
                <?php if ($preference['configuration']->print == 1): ?>
                $(".download-pdf").removeClass('hidden');
                <?php else: ?>
                $(".download-pdf").addClass('hidden');
                <?php endif ?>
                /* ============= print option enable/disable ends */

                // notification remove
                $(".deleteNotification").on('click', function(e) {
                    e.preventDefault();
                    var anchor = $(this);
                    var url = $(this).attr('href');
                    var cur_notification_count = $("#notification_count").data('count');
                    $.ajax({
                        url: url,
                        type: 'POST',
                        success: function(response) {
                            if ( $.trim(response) == "1") {
                                anchor.closest('.notify').remove();
                                $(".notification_count").html((cur_notification_count-1));
                                $("#notification_count").data("count", (cur_notification_count-1));
                            }
                            console.log(response);
                        },
                        error: function(response) {
                            console.log(response);
                        },
                    });

                });

                // notification remove
                $(".permanentlyDeleteEntry").on('click', function(e) {
                    e.preventDefault();
                    var anchor = $(this);
                    var url = $(this).attr('href');
                    var cur_trash_entry_count = $("#trash_entry_count").data('count');
                    $.ajax({
                        url: url,
                        type: 'POST',
                        success: function(response) {
                            if ( $.trim(response) == "1") {
                                anchor.closest('.trashyEntry').remove();
                                $(".trash_entry_count").html((cur_trash_entry_count-1));
                                $("#trash_entry_count").data("count", (cur_trash_entry_count-1));
                            }
                            console.log(response);
                        },
                        error: function(response) {
                            console.log(response);
                        },
                    });

                });

                // entry restore
                $(".restoreEntry").on('click', function(e) {
                    e.preventDefault();
                    var anchor = $(this);
                    var url = $(this).attr('href');
                    var cur_trash_entry_count = $("#trash_entry_count").data('count');
                    $.ajax({
                        url: url,
                        type: 'POST',
                        success: function(response) {
                            if ( $.trim(response) == "1") {
                                anchor.closest('.trashyEntry').remove();
                                $(".trash_entry_count").html((cur_trash_entry_count-1));
                                $("#trash_entry_count").data("count", (cur_trash_entry_count-1));
                            }
                            console.log(response);
                        },
                        error: function(response) {
                            console.log(response);
                        },
                    });

                });




            });

             $('.sidebar-toggle').click(function () {
               $(".content-header").toggleClass('toggle-subheader-width');
           });



// close right panel when click anywhere outside - 06-07-2018
//    $(document).mouseup(function(e) {
//    var container = $("#myDiv");
//    if (!container.is(e.target) && container.has(e.target).length === 0)
//    {
//        container.hide('slide',{direction:'right'},500);
//    }
//});

//                $("#example02").dataTable({
//                    "bPaginate": true,
//                    "pageLength": 10,
//                    "iDisplayLength": 10,
//                    "oLanguage": {
//                        "oPaginate": {
//                            "sNext": "",
//                            "sPrevious": ""
//                        },
//                        "sSearch": ""
//                    },
//                    "bLengthChange": false,
//                    "bFilter": true,
//                    "bSort": false,
//                    "bInfo": true,
//                    "bAutoWidth": false,
//                    "fnDrawCallback": function(oSettings) {
//                        if (oSettings._iDisplayLength >= oSettings.fnRecordsDisplay()) {
//                            $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
//                        }
//                    }
//                });

        </script>
<script>
    $(window).scroll(function() {
        if ($(this).scrollTop() > 10){
            //if ($(window).width() > 768){
            $('.content-header').addClass('shadow');
            //}
        }
        else{
            $('.content-header').removeClass('shadow');
        }
    });
</script>

    </body>
</html>

<!-- Footer-->
