<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <ul class="page-sidebar-menu " data-auto-scroll="true" data-slide-speed="200">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <li class="sidebar-toggler-wrapper">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler">
                </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>
            <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
            <li class="sidebar-search-wrapper">
                <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
                <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
                <form class="sidebar-search " action="extra_search.html" method="POST">
                    <a href="javascript:;" class="remove">
                        <i class="icon-close"></i>
                    </a>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                            <a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
                        </span>
                    </div>
                </form>
                <!-- END RESPONSIVE QUICK SEARCH FORM -->
            </li>
            
            <li class="<?php echo ($this->router->fetch_class() == 'logins') ? "start active open" : "" ?>">
                <a href="javascript:;">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                    <span class="<?php echo ($this->router->fetch_class() == 'logins') ? "selected" : "" ?>"></span>
                    <span class="<?php echo ($this->router->fetch_class() == 'logins') ? "arrow open" : "" ?>"></span>
                </a>
                <ul class="sub-menu">
                    <li class="<?php echo ($this->router->fetch_method() == 'dashboard') ? "active" : "" ?>">
                        <a href="<?php echo base_url('index.php/logins/dashboard')?>">
                            <i class="icon-bar-chart"></i>
                            Dashboard Status</a>
                    </li>   
                </ul>
            </li>
            <li class="<?php echo ($this->router->fetch_class() == 'groups' || $this->router->fetch_class() == 'accounts') ? "start active open" : "" ?>">
                <a href="javascript:;">
                    <i class="fa fa-users"></i>
                    <span class="title">Masters</span>
                    <span class="<?php echo ($this->router->fetch_class() == 'groups' || $this->router->fetch_class() == 'accounts') ? "selected" : "" ?>"></span>
                    <span class="<?php echo ($this->router->fetch_class() == 'groups' || $this->router->fetch_class() == 'accounts') ? "arrow open" : "" ?>"></span>
                </a>
                <ul class="sub-menu">
                    <li class="<?php echo ($this->router->fetch_class() == 'groups' && ($this->router->fetch_method() == 'index' || $this->router->fetch_method() == 'add_group')) ? "active" : "" ?>">
                        <a href="<?php echo site_url("admin/accounts-groups")?>">
                            <i class="fa fa-list"></i>
                            Groups</a>
                    </li>
                    <li class="<?php echo ($this->router->fetch_class() == 'accounts' && ($this->router->fetch_method() == 'index' || $this->router->fetch_method() == 'add_ledger')) ? "active" : "" ?>">
                        <a href="<?php echo site_url("admin/accounts-ledger")?>">
                            <i class="fa fa-list"></i>
                            Ledger</a>
                    </li>
                </ul>
            </li>
            
            <li class="<?php echo 
            ($this->router->fetch_class() == 'entries' 
                    && ($this->uri->segment(1) == 'receipts' 
                    || $this->uri->segment(1) == 'payments' 
                    || $this->uri->segment(1) == 'contres' 
                    || $this->uri->segment(1) == 'jurnals' 
                    || $this->router->fetch_method() == 'new_entry'
                    || $this->router->fetch_method() == 'edit_entry'
                    )
            ) ? "start active open" : "" ?>">
                <a href="javascript:;">
                    <i class="fa fa-users"></i>
                    <span class="title">Transaction</span>
                    <span class="<?php echo ($this->router->fetch_class() == 'entries') ? "selected" : "" ?>"></span>
                    <span class="<?php echo ($this->router->fetch_class() == 'entries') ? "arrow open" : "" ?>"></span>
                </a>
                <ul class="sub-menu">
                    <li class="<?php echo ($this->router->fetch_class() == 'entries' && ($this->router->fetch_method() == 'index' && $this->uri->segment(1) == 'Receipts') || ($this->router->fetch_method() == 'new_entry' && $this->uri->segment(3) == 1) || ($this->router->fetch_method() == 'edit_entry' && $this->uri->segment(4) == 1)) ? "active" : "" ?>">
                        <a href="<?php echo site_url("admin/receipts")?>">
                            <i class="fa fa-list"></i>
                            Receipts</a>
                    </li>
                    <li class="<?php echo ($this->router->fetch_class() == 'entries' && ($this->router->fetch_method() == 'index' && $this->uri->segment(1) == 'Payments') || ($this->router->fetch_method() == 'new_entry' && $this->uri->segment(3) == 2) || ($this->router->fetch_method() == 'edit_entry' && $this->uri->segment(4) == 2)) ? "active" : "" ?>">
                        <a href="<?php echo site_url("admin/payments")?>">
                            <i class="fa fa-list"></i>
                            Payments</a>
                    </li>
                    <li class="<?php echo ($this->router->fetch_class() == 'entries' && ($this->router->fetch_method() == 'index' && $this->uri->segment(1) == 'Contres') || ($this->router->fetch_method() == 'new_entry' && $this->uri->segment(3) == 3) || ($this->router->fetch_method() == 'edit_entry' && $this->uri->segment(4) == 3)) ? "active" : "" ?>">
                        <a href="<?php echo site_url("admin/contres")?>">
                            <i class="fa fa-list"></i>
                            Contres</a>
                    </li>
                    <li class="<?php echo ($this->router->fetch_class() == 'entries' && ($this->router->fetch_method() == 'index' && $this->uri->segment(1) == 'Jurnals') || ($this->router->fetch_method() == 'new_entry' && $this->uri->segment(3) == 4) || ($this->router->fetch_method() == 'edit_entry' && $this->uri->segment(4) == 4)) ? "active" : "" ?>">
                        <a href="<?php echo site_url("admin/jurnals")?>">
                            <i class="fa fa-list"></i>
                            Jurnals</a>
                    </li>
                </ul>
            </li>
            
            <li class="<?php echo ($this->router->fetch_class() == 'reports') ? "start active open" : "" ?>">
                <a href="javascript:;">
                    <i class="fa fa-repeat"></i>
                    <span class="title">Reports</span>
                    <span class="<?php echo ($this->router->fetch_class() == 'reports') ? "" : "" ?>"></span>
                    <span class="<?php echo ($this->router->fetch_class() == 'reports') ? "arrow open" : "" ?>"></span>
                </a>
                <ul class="sub-menu">
                    <li class="<?php echo ($this->router->fetch_method() == 'ledger_statements') ? "active" : "" ?>">
                        <a href="<?php echo site_url("admin/accounts-ledger-statement")?>">
                            <i class="fa fa-repeat"></i>
                            Ledger Statement</a>
                    </li>
                    <li class="<?php echo ($this->router->fetch_method() == 'trial_balance') ? "active" : "" ?>">
                        <a href="<?php echo site_url("admin/accounts-trial-balance")?>">
                            <i class="fa fa-repeat"></i>
                            Trial Balance </a>
                    </li>
                    <li class="<?php echo ($this->router->fetch_method() == 'balance_sheet') ? "active" : "" ?>">
                        <a href="<?php echo site_url("admin/accounts-balance-sheet")?>">
                            <i class="fa fa-repeat"></i>
                            Balance Sheet</a>
                    </li>
                    <li class="<?php echo ($this->router->fetch_method() == 'profit_loss') ? "active" : "" ?>">
                        <a href="<?php echo site_url("admin/accounts-profit-loss")?>">
                            <i class="fa fa-repeat"></i>
                            Profit & Loss</a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>
<!-- END SIDEBAR -->