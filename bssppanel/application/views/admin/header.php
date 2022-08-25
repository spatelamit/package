<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Flash Message For Notification
$message = "";
$message_type = "";
if ($this->session->userdata('message_data')) {
    $message_data = $this->session->userdata('message_data');
    $message = $message_data['message'];
    $message_type = $message_data['message_type'];
}
$permissions_array = array();
if (isset($permissions) && $permissions) {
    $permissions_array = explode(',', $permissions);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!--<meta http-equiv="refresh" content="60; URL=<?php //echo base_url(uri_string());                              ?>">-->
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="sitelock-site-verification" content="2157" />
        <meta name="description" content="">
        <meta name="robots" content="noindex,nofollow">
        <meta name="author" content="">
        <title><?php echo PANEL_TITLE; ?></title>
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
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/metro/notify-metro.css"> 
    </head>
    <body>
        <input type="hidden" name="base_url" id="base_url" value="<?php echo site_url(); ?>" />

        <div class="alert <?php echo (isset($message_type) && $message_type) ? $message_type : ""; ?>" id="notification" role="alert">
            <?php echo (isset($message) && $message) ? $message : ""; ?>
        </div>

        <div id="wrapper">
            <div id="sidebar-wrapper">
                <!-- Sidebar -->
                <ul class="sidebar-nav">
                    <li class="sidebar-brand">
                        <a href="<?php echo base_url(); ?>admin/spam_transactional">
                            <img src="<?php echo base_url(); ?>Assets/admin/img/logo.png" alt="Bulk24SMS Networks">
                        </a>
                    </li>
                    <?php
                    if (isset($atype) && $atype && $atype == 1) {
                        ?>
                        <li <?php echo (isset($page) && $page == "spam_transactional") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/spam_transactional"><?php echo SPAM_TRN; ?></a>
                        </li>
                        <li <?php echo (isset($page) && $page == "spam_promotional") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/spam_promotional"><?php echo SPAM_PRN; ?></a>
                        </li>
                        <li <?php echo (isset($page) && $page == "delivery_reports") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/delivery_reports"><?php echo SMS_TRACK; ?></a>
                        </li>
                        <li <?php echo (isset($page) && $page == "daily_reports") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/show_daily_reports">DAILY REPORTS</a>
                        </li>
                        <li <?php echo (isset($page) && $page == "settings") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/settings"><?php echo SETTINGS; ?></a>
                        </li>
                        <li <?php echo (isset($page) && $page == "sms_rates") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/sms_rates"><?php echo SMS_RATE_PLANS; ?></a>
                        </li>
                        <li <?php echo (isset($page) && $page == "account_managers") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/account_managers"><?php echo ACC_MANAGER; ?></a>
                        </li>
                        <li <?php echo (isset($page) && $page == "user_groups") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/user_groups"><?php echo USER_GROUPS; ?></a>
                        </li>
                        <li <?php echo (isset($page) && $page == "verify_users_pin") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/verify_users_pin"><?php echo USER_MGMT; ?></a>
                        </li>
                        <li <?php echo (isset($page) && $page == "users_balance") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/users_balance"><?php echo USERS_BALANCE; ?></a>
                        </li>
                        <li <?php echo (isset($page) && $page == "all_logs") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/all_logs"><?php echo LOGS; ?></a>
                        </li>
                        <li <?php echo (isset($page) && $page == "sender_ids") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/sender_ids"><?php echo SENDER_IDS; ?></a>
                        </li>
                        <li <?php echo (isset($page) && $page == "keywords") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/keywords"><?php echo KEYWORDS; ?></a>
                        </li>
                        <li <?php echo (isset($page) && $page == "smpp_logs") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/smpp_logs"><?php echo SMPP_LOGS; ?></a>
                        </li>
                        <li <?php echo (isset($page) && $page == "virtual_numbers") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/virtual_numbers"><?php echo VIRTUAL_NO; ?></a>
                        </li>
                        <li <?php echo (isset($page) && $page == "missed_call_alerts") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/missed_call_alerts"><?php echo MISSED_CALL; ?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>kannel_control/monitor" target="_blank"><?php echo KANNEL_MONITOR; ?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>kannel_control" target="_blank">Kannel Configuration</a>
                        </li>
                        <li  <?php echo (isset($page) && $page == "selles_tracker") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/selles_tracker" >SELLS TRACKER</a>
                        </li>
                        <li <?php echo (isset($page) && $page == "payment_aproval") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/payment_aproval" >Payment Approval</a>
                        </li>
                        <li <?php echo (isset($page) && $page == "show-payment-subadmin") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/show_payment_subadmin" >Payment Details</a>
                        </li>
                        <li <?php echo (isset($page) && $page == "daily-signup") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/daily_signup" >Daily SignUp</a>
                        </li>
                        <li  <?php echo (isset($page) && $page == "Subscriptions") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/daily_subscription" >Subscriptions </a>
                        </li>
                        <li <?php echo (isset($page) && $page == "otp_test") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/otp_test" >OTP Test </a>
                        </li>
                        <li <?php echo (isset($page) && $page == "controller_history") ? 'class="active"' : '' ?>>
                            <a href="<?php echo base_url(); ?>admin/controller_history" >Controller History</a>
                        </li>
                        <?php
                    } elseif (isset($atype) && $atype && $atype == 2) {
                        ?>
                        <?php
                        // Tab 1
                        if (isset($permissions_array) && $permissions_array && in_array('1', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "spam_transactional") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/spam_transactional"><?php echo SPAM_TRN; ?></a>
                            </li>                        
                            <?php
                        }
                        // Tab 2
                        if (isset($permissions_array) && $permissions_array && in_array('2', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "spam_promotional") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/spam_promotional"><?php echo SPAM_PRN; ?></a>
                            </li>                  
                            <?php
                        }
                        // Tab 3
                        if (isset($permissions_array) && $permissions_array && in_array('3', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "delivery_reports") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/delivery_reports"><?php echo SMS_TRACK; ?></a>
                            </li>                       
                            <?php
                        }
                        // Tab 22
                        if (isset($permissions_array) && $permissions_array && in_array('22', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "daily_reports") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/show_daily_reports">DAILY REPORTS</a>
                            </li>                       
                            <?php
                        }
                        // Tab 4
                        if (isset($permissions_array) && $permissions_array && in_array('4', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "settings") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/settings"><?php echo SETTINGS; ?></a>
                            </li>                      
                            <?php
                        }
                        // Tab 5
                        if (isset($permissions_array) && $permissions_array && in_array('5', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "sms_rates") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/sms_rates"><?php echo SMS_RATE_PLANS; ?></a>
                            </li>                        
                            <?php
                        }
                        // Tab 6
                        if (isset($permissions_array) && $permissions_array && in_array('6', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "account_managers") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/account_managers"><?php echo ACC_MANAGER; ?></a>
                            </li>                    
                            <?php
                        }
                        // Tab 7
                        if (isset($permissions_array) && $permissions_array && in_array('7', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "user_groups") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/user_groups"><?php echo USER_GROUPS; ?></a>
                            </li>                     
                            <?php
                        }
                        // Tab 8
                        if (isset($permissions_array) && $permissions_array && in_array('8', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "verify_users_pin") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/verify_users_pin"><?php echo USER_MGMT; ?></a>
                            </li>                   
                            <?php
                        }
                        // Tab 9
                        if (isset($permissions_array) && $permissions_array && in_array('9', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "users_balance") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/users_balance"><?php echo USERS_BALANCE; ?></a>
                            </li>                
                            <?php
                        }
                        // Tab 10
                        if (isset($permissions_array) && $permissions_array && in_array('10', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "all_logs") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/all_logs"><?php echo LOGS; ?></a>
                            </li>                  
                            <?php
                        }
                        // Tab 11
                        if (isset($permissions_array) && $permissions_array && in_array('11', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "sender_ids") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/sender_ids"><?php echo SENDER_IDS; ?></a>
                            </li>                
                            <?php
                        }
                        // Tab 12
                        if (isset($permissions_array) && $permissions_array && in_array('12', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "keywords") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/keywords"><?php echo KEYWORDS; ?></a>
                            </li>                 
                            <?php
                        }
                        // Tab 13
                        if (isset($permissions_array) && $permissions_array && in_array('13', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "smpp_logs") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/smpp_logs"><?php echo SMPP_LOGS; ?></a>
                            </li>                   
                            <?php
                        }
                        // Tab 14
                        if (isset($permissions_array) && $permissions_array && in_array('14', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "virtual_numbers") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/virtual_numbers"><?php echo VIRTUAL_NO; ?></a>
                            </li>

                            <?php
                        }
                        // Tab 17
                        if (isset($permissions_array) && $permissions_array && in_array('17', $permissions_array)) {
                            ?>
                            <li <?php echo (isset($page) && $page == "missed_call_alerts") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/missed_call_alerts"><?php echo MISSED_CALL; ?></a>
                            </li>
                            <?php
                        }
                        // Tab 15
                        if (isset($permissions_array) && $permissions_array && in_array('15', $permissions_array)) {
                            ?>
                            <li>
                                <a href="<?php echo base_url(); ?>kannel_control/monitor" target="_blank"><?php echo KANNEL_MONITOR; ?></a>
                            </li>                 
                            <?php
                        }
                        // Tab 16
                        if (isset($permissions_array) && $permissions_array && in_array('16', $permissions_array)) {
                            ?>
                            <li>
                                <a href="<?php echo base_url(); ?>kannel_control" target="_blank">KANNEL CONFIGURATIONS</a>
                            </li>  

                            <?php
                        }
                        // Tab 17
                        if (isset($permissions_array) && $permissions_array && in_array('17', $permissions_array)) {
                            ?>
                            <li  <?php echo (isset($page) && $page == "sells_tracker") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/selles_tracker" >SELLES TRACKER</a>
                            </li>
                            <?php
                        }
                        // Tab 18
                        if (isset($permissions_array) && $permissions_array && in_array('18', $permissions_array)) {
                            ?>
                            <li  <?php echo (isset($page) && $page == "payment_aproval") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/payment_aproval" >Payment Approval</a>
                            </li>
                            <?php
                        }
                        // Tab 19
                        if (isset($permissions_array) && $permissions_array && in_array('19', $permissions_array)) {
                            ?>
                            <li  <?php echo (isset($page) && $page == "show-payment-subadmin") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/show_payment_subadmin" >Payment Details</a>
                            </li>
                            <?php
                        }
                        // Tab 20
                        if (isset($permissions_array) && $permissions_array && in_array('20', $permissions_array)) {
                            ?>
                            <li  <?php echo (isset($page) && $page == "daily-signup") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/daily_signup" >Daily SignUp</a>
                            </li>
                            <?php
                        }
                        // Tab 24
                        if (isset($permissions_array) && $permissions_array && in_array('24', $permissions_array)) {
                            ?>
                            <li  <?php echo (isset($page) && $page == "Subscriptions") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/daily_subscription" >Subscriptions </a>
                            </li>
                             <li  <?php echo (isset($page) && $page == "Meetings") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/meetings" >Meetings </a>
                            </li>
                            <?php
                        }
                        // Tab 23
                        if (isset($permissions_array) && $permissions_array && in_array('23', $permissions_array)) {
                            ?>
                            <li  <?php echo (isset($page) && $page == "otp_test") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/otp_test" >OTP Test</a>
                            </li>
                            <?php
                        }
                        // Tab 22
                        if (isset($permissions_array) && $permissions_array && in_array('21', $permissions_array)) {
                            ?>
                            <li  <?php echo (isset($page) && $page == "controller_history") ? 'class="active"' : '' ?>>
                                <a href="<?php echo base_url(); ?>admin/controller_history" >Controller History</a>
                            </li>
                            <?php
                        }
                        
                    }
                    ?>
                </ul>
            </div>
            <!-- /#sidebar-wrapper -->
            <!-- Page Content -->
            <div id="page-content-wrapper">
                <div class="container-fluid padding0">
                    <div class="row-fluid">
                        <header>
                            <a href="#menu-toggle" class="" id="menu-toggle"><i class="fa fa-outdent"></i></a>

                            <div class="col-md-4 col-sm-6 col-xs-10 padding0">
                                <div id="search-form">
                                    <form class="" id="searchform" action="">
                                        <div class="input-group">
                                            <input type="text" class="form-control search-form-stl" placeholder="Search User" id="search" 
                                                   onkeyup="searchUsers(this.value);" autocomplete="off"  />                                           
                                            <span class="input-group-btn">
                                                <button class="btn btn-default search-form-stl" type="button">
                                                    <i class="fa fa-search"></i> 
                                                </button>
                                            </span>

                                        </div>                                 
                    <!--       <p>My name is: <span id="demo"></span></p>-->
                                    </form>

                                </div>
                            </div>


                            <div class="col-md-5 col-sm-0 col-xs-0">
                                <span class="left hidden" id="loading_text">Loading...</span>
                            </div>

                            <div class="col-md-2 col-sm-2 col-xs-6 hidden-xs">
                                <?php
                                if ((isset($atype) && $atype && $atype == 1)) {
                                    ?>
                                    <!-- SMSC And Admin Virtual Balance -->
                                    <div class="icon1">
                                        <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                class="btn btn-primary btn-xs" data-placement="bottom" title="SMSC Balance">
                                            <i class="fa fa-bold"></i>
                                        </button>
                                        <ul id="menu1" class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel"> 
                                            <li>
                                                <table class="table" style="width: 370px;">
                                                    <tbody>
                                                        <tr>
                                                            <th colspan="2" class="text-center">SMSC Balance</th>
                                                            <th colspan="2" class="text-center">Virtual Balance</th>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                B24SMS TR
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($smsc_balance) && $smsc_balance) ? $smsc_balance['smsc1'] : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                PR TEXT
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_pr_balance) && $total_pr_balance) ? $total_pr_balance : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                Premium TEXT
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($prtodnd_balance) && $prtodnd_balance) ? $prtodnd_balance : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                STOCK TEXT
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($stock_balance) && $stock_balance) ? $stock_balance : 0; ?></span>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                B24SMS PR
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($smsc_balance) && $smsc_balance) ? $smsc_balance['smsc2'] : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                TR TEXT
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_tr_balance) && $total_tr_balance) ? $total_tr_balance : 0; ?></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                BSSP OPEN
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($smsc_balance) && $smsc_balance) ? $smsc_balance['smsc3'] : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                PR VOICE
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_vpr_balance) && $total_vpr_balance) ? $total_vpr_balance : 0; ?></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"></td>
                                                            <td>
                                                                TR VOICE
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_vtr_balance) && $total_vtr_balance) ? $total_vtr_balance : 0; ?></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"></td>
                                                            <td>
                                                                LONG CODE
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_lcode_balance) && $total_lcode_balance) ? $total_lcode_balance : 0; ?></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"></td>
                                                            <td>
                                                                SHORT CODE
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_scode_balance) && $total_scode_balance) ? $total_scode_balance : 0; ?></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                PR Credits
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($admin_pr_credits) && $admin_pr_credits) ? $admin_pr_credits : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                TR Credits
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($admin_tr_credits) && $admin_tr_credits) ? $admin_tr_credits : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                Premium Credits
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_prtodnd_credits) && $total_prtodnd_credits) ? $total_prtodnd_credits : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                STOCK Credits
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_stock_credits) && $total_stock_credits) ? $total_stock_credits : 0; ?></span>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Missed Call 
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_mcall_balance) && $total_mcall_balance) ? $total_mcall_balance : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                International SMS
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($international_sms) && $international_sms) ? $international_sms : 0; ?></span>
                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php
                                } elseif ((isset($atype) && $atype && $atype == 2)) {
                                    ?>
                                    <!-- Admin Virtual Balance -->
                                    <div class="icon1">
                                        <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                class="btn btn-primary btn-xs" data-placement="bottom" title="SMSC Balance">
                                            <i class="fa fa-bold"></i>
                                        </button>
                                        <ul id="menu1" class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel"> 
                                            <li>
                                                <table class="table" style="width:500px;">
                                                    <tbody>
                                                        <tr>
                                                            <th colspan="8" class="text-center">Virtual Balance</th>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                PR TEXT
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_pr_balance) && $total_pr_balance) ? $total_pr_balance : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                Premium TEXT
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($prtodnd_balance) && $prtodnd_balance) ? $prtodnd_balance : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                STOCK TEXT
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($stock_balance) && $stock_balance) ? $stock_balance : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                TR TEXT
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_tr_balance) && $total_tr_balance) ? $total_tr_balance : 0; ?></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                PR Credits
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($admin_pr_credits) && $admin_pr_credits) ? $admin_pr_credits : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                TR Credits
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($admin_tr_credits) && $admin_tr_credits) ? $admin_tr_credits : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                Premium Credits
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_prtodnd_credits) && $total_prtodnd_credits) ? $total_prtodnd_credits : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                STOCK Credits
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_stock_credits) && $total_stock_credits) ? $total_stock_credits : 0; ?></span>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                PR VOICE
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_vpr_balance) && $total_vpr_balance) ? $total_vpr_balance : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                TR VOICE
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_vtr_balance) && $total_vtr_balance) ? $total_vtr_balance : 0; ?></span>
                                                            </td>

                                                            <td>
                                                                LONG CODE
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_lcode_balance) && $total_lcode_balance) ? $total_lcode_balance : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                SHORT CODE
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_scode_balance) && $total_scode_balance) ? $total_scode_balance : 0; ?></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                PR VOICE
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_vpr_balance) && $total_vpr_balance) ? $total_vpr_balance : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                TR VOICE
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_vtr_balance) && $total_vtr_balance) ? $total_vtr_balance : 0; ?></span>
                                                            </td>

                                                            <td>
                                                                LONG CODE
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_lcode_balance) && $total_lcode_balance) ? $total_lcode_balance : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                SHORT CODE
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_scode_balance) && $total_scode_balance) ? $total_scode_balance : 0; ?></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Missed Call 
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($total_mcall_balance) && $total_mcall_balance) ? $total_mcall_balance : 0; ?></span>
                                                            </td>
                                                            <td>
                                                                International SMS
                                                            </td>
                                                            <td>
                                                                <span class="badge"><?php echo (isset($international_sms) && $international_sms) ? $international_sms : 0; ?></span>
                                                            </td>

                                                        </tr>



                                                    </tbody>
                                                </table>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php
                                }
                                ?>

                                <!-- Chat Support -->
                                <!--
                                <div class="icon1">
                                    <a href="<?php //echo base_url();        ?>admin/chat_support" class="btn btn-primary btn-xs" id="icon" data-toggle="tooltip" data-placement="bottom" title="Chat Support">
                                        <i class="fa fa-comment"></i>
                                    </a>
                                </div>
                                -->
                                <!-- Online Users -->
                                <div id="toggle-sidebar" class="icon">
                                    <a href="#menu-toggle" class="btn btn-primary btn-xs" id="icon" data-toggle="tooltip" data-placement="bottom" title="Online Users">
                                        <i class="fa fa-user"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                                <div id="btn-logout" class="hidden-xs">
                                    <a href="<?php echo base_url(); ?>admin/logout" class="btn btn-danger btn-main"><i class="fa fa-power-off"></i> <?php echo LOGOUT; ?></a>
                                </div>
                                <div id="btn-logout" class="visible-xs">
                                    <a href="<?php echo base_url(); ?>admin/logout" class="btn btn-danger btn-main"><i class="fa fa-power-off"></i></a>
                                </div>
                            </div>
                        </header>
                        <section id="show_search_result">


