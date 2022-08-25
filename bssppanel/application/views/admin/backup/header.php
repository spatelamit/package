<?php
$message = "";
$message_type = "";
if ($this->session->userdata('message_data')) {
    $message_data = $this->session->userdata('message_data');
    $message = $message_data['message'];
    $message_type = $message_data['message_type'];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?php echo PANEL_TITLE; ?></title>
        <link rel="shortcut icon" type="image/png" href="<?php echo base_url(); ?>Assets/admin/img/favicon.ico"/>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/jquery-1.11.1.min.js"></script>
        <link href="<?php echo base_url(); ?>Assets/admin/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>Assets/admin/css/animations.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/font-awesome.min.css">
        <link href="<?php echo base_url(); ?>Assets/admin/css/sidebar-menu.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/controller.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/spam-mark.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/bootstrap-datetimepicker.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/plugins/simple-slider-master/css/simple-slider.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/plugins/simple-slider-master/css/simple-slider-volume.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/sweet-alert.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/sidebar.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/bootstrap-multiselect.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/bootstrap-select.min.css"> 
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/switch.css"> 
    </head>
    <body>
        <input type="hidden" name="base_url" id="base_url" value="<?php echo site_url(); ?>" />

        <div class="alert <?php echo ($message_type) ? $message_type : ""; ?>" id="notification" role="alert"><?php echo ($message) ? $message : ""; ?></div>

        <div id="wrapper">
            <div id="sidebar-wrapper">
                <!-- Sidebar -->
                <ul class="sidebar-nav">
                    <li class="sidebar-brand">
                        <a href="<?php echo base_url(); ?>admin/spam_transactional">
                            <img src="<?php echo base_url(); ?>Assets/admin/img/logo.png" alt="Bulk24SMS Networks">
                        </a>
                    </li>
                    <li class="<?php echo (isset($page) && $page == "spam_transactional") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>admin/spam_transactional"><?php echo SPAM_TRN; ?></a>
                    </li>
                    <li class="<?php echo (isset($page) && $page == "spam_promotional") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>admin/spam_promotional"><?php echo SPAM_PRN; ?></a>
                    </li>
                    <li class="<?php echo (isset($page) && $page == "delivery_reports") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>admin/delivery_reports"><?php echo SMS_TRACK; ?></a>
                    </li>
                    <li class="<?php echo (isset($page) && $page == "settings") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>admin/settings"><?php echo SETTINGS; ?></a>
                    </li>
                    <li class="<?php echo (isset($page) && $page == "account_managers") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>admin/account_managers"><?php echo ACC_MANAGER; ?></a>
                    </li>
                    <li class="<?php echo (isset($page) && $page == "user_groups") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>admin/user_groups"><?php echo USER_GROUPS; ?></a>
                    </li>
                    <li class="<?php echo (isset($page) && $page == "users") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>admin/users"><?php echo USER_MGMT; ?></a>
                    </li>
                    <li class="<?php echo (isset($page) && $page == "users_balance") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>admin/users_balance"><?php echo USERS_BALANCE; ?></a>
                    </li>
                    <li class="<?php echo (isset($page) && $page == "all_logs") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>admin/all_logs"><?php echo LOGS; ?></a>
                    </li>
                    <!--
                    <li class="<?php //echo (isset($page) && $page == "smpp_users") ? 'active"' : ''     ?>">
                        <a href="<?php //echo base_url();     ?>admin/smpp_users"><?php //echo SMPP_USERS;     ?></a>
                    </li>
                    -->
                    <li class="<?php echo (isset($page) && $page == "sender_ids") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>admin/sender_ids"><?php echo SENDER_IDS; ?></a>
                    </li>
                    <li class="<?php echo (isset($page) && $page == "keywords") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>admin/keywords"><?php echo KEYWORDS; ?></a>
                    </li>
                    <li class="<?php echo (isset($page) && $page == "smpp_logs") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>admin/smpp_logs"><?php echo SMPP_LOGS; ?></a>
                    </li>
                    <li class="<?php echo (isset($page) && $page == "virtual_numbers") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>admin/virtual_numbers"><?php echo VIRTUAL_NO; ?></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>admin/monitor" target="_blank"><?php echo KANNEL_MONITOR; ?></a>
                    </li>
                </ul>
            </div>
            <!-- /#sidebar-wrapper -->
            <!-- Page Content -->
            <div id="page-content-wrapper">
                <div class="container-fluid padding0">
                    <div class="row-fluid">
                        <header>
                            <a href="#menu-toggle" class="" id="menu-toggle"><i class="fa fa-outdent"></i></a>
                            <div class="col-md-3 col-sm-4 col-xs-6 padding0">
                                <div id="search-form">
                                    <form class="" id="searchform" action="#">
                                        <div class="input-group">
                                            <input type="text" class="form-control search-form-stl" placeholder="Search User" id="search" 
                                                   onkeyup="searchUsers(this.value);" />
                                            <span class="input-group-btn">
                                                <button class="btn btn-default search-form-stl" type="button">
                                                    <i class="fa fa-search"></i> 
                                                </button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="toggle-sidebar" class="icon right">
                                <a href="#menu-toggle" class="" id="icon" data-toggle="tooltip" data-placement="bottom" title="Online Users">
                                    <i class="fa fa-user"></i>
                                </a>
                            </div>
                            <div id="btn-logout" class="hidden-xs">
                                <a href="<?php echo base_url(); ?>admin/logout" class="btn btn-danger btn-main"><i class="fa fa-power-off"></i> <?php echo LOGOUT; ?></a>
                            </div>
                            <div id="btn-logout" class="visible-xs">
                                <a href="<?php echo base_url(); ?>admin/logout" class="btn btn-danger btn-main"><i class="fa fa-power-off"></i></a>
                            </div>
                        </header>
                        <section id="show_search_result">
