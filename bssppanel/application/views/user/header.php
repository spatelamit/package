<?php
$user_info['encription'];
// Theme
$theme = "";
if ($this->session->userdata('theme_data')) {
    $theme_data = $this->session->userdata('theme_data');
    $theme = $theme_data['theme'];
}
// Flash Message For Notification
$message = "";
$message_type = "";
if ($this->session->flashdata('message_type') && $this->session->flashdata('message')) {
    $message = $this->session->flashdata('message');
    $message_type = $this->session->flashdata('message_type');
}
?>

<?php
//error_reporting(0);
$DBhostname = "localhost";
$DBusername = "kannel_kannel";
$DBpassword = "Kannel@#$321";
$DBname = "kannel_kannel";


$conn = mysqli_connect("$DBhostname", "$DBusername", "$DBpassword") or die(mysqli_error());
mysqli_select_db($conn,"$DBname") or die(mysqli_error());
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="sitelock-site-verification" content="2157" />
        <meta name="robots" content="noindex,nofollow">
        <meta name="google-site-verification" content="TaITqcfYlPPqRC7M6XmkJt1YFvuvLiXEKaATl0arg_k" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bulk SMS- User Control Panel</title>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/jquery.min.js"></script>
        <link href="<?php echo base_url(); ?>Assets/user/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>Assets/user/css/fonts.css" rel="stylesheet" />
        <!--<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/bootstrap-datetimepicker.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/datepicker.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/style.css" />
        <!--<link rel="stylesheet" type="text/css" href="<?php //echo base_url();                                  ?>Assets/user/css/bootstrap.colorpickersliders.css" />-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/bootstrap-colorpicker.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/style-selector.css" />
        <link id="color" rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/color-schemes/<?php echo (isset($theme) && $theme != "") ? $theme : "default"; ?>.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/bootstrap-multiselect.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/bootstrap-editable.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/chat.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/popup.css" />

        <style type="text/css">
            /* Paste this css to your style sheet file or under head tag */
            /* This only works with JavaScript, 
            if it's not present, don't show loader */
            /*
            .no-js #loader { display: none;  }
            .js #loader { display: block; position: absolute; left: 100px; top: 0; }
            .se-pre-con {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url(<?php //echo base_url();                                             ?>Assets/user/img/loading.gif) center no-repeat #fff;
            }
            */
            <?php
            if (isset($user_info) && $user_info['utype']) {
                if ($user_info['utype'] == 'Reseller') {
                    ?>
                    #menubar .col-md-1 {
                        width: 7.6%;
                        padding: 22px 0;
                        text-align: center;
                    }
                    <?php
                } elseif ($user_info['utype'] == 'User') {
                    ?>
                    #menubar .col-md-1 {
                        width: 9%;
                        padding: 22px 0;
                        text-align: center;
                    }
                    <?php
                }
            }
            ?>
        </style>
    </head>
    <body>
        <input type="hidden" name="base_url" id="base_url" value="<?php echo site_url(); ?>" />
        <input type="hidden" name="base_url1" id="base_url1" value="<?php echo base_url(); ?>" />
        <input type="hidden" name="page_id" id="page_id" value="<?php echo (isset($page)) ? $page : ""; ?>" />

        <div class="color-switcher" id="choose_color">
            <a href="#." class="picker_close">
                <i class="fa fa-gear fa-spin"></i>
            </a>
            <div class="theme-colours">
                <h4>Choose Colour Style</h4>
                <ul>
                    <li><a href="#." class="blue" id="default"></a></li>
                    <li><a href="#." class="green" id="green"></a></li>
                    <li><a href="#." class="red" id="red"></a></li>
                    <li><a href="#." class="light-blue" id="light-blue"></a></li>
                    <li><a href="#." class="light-green" id="light-green"></a></li>
                    <li><a href="#." class="orange" id="orange"></a></li>
                    <li><a href="#." class="pink" id="pink"></a></li>
                    <li><a href="#." class="black" id="black"></a></li>
                </ul>
            </div>
        </div>
        <!--
        <div id="loading">
            <div id="loading-center">
                <div id="loading-center-absolute">
                    <div class="object" id="object_four"></div>
                    <div class="object" id="object_three"></div>
                    <div class="object" id="object_two"></div>
                    <div class="object" id="object_one"></div>
                </div>
            </div>
        </div>
        -->
        <div class="se-pre-con"></div>

        <span id="notification" class="<?php echo (isset($message_type) && $message_type) ? $message_type : ""; ?>">
            <?php echo (isset($message) && $message) ? $message : ""; ?>
        </span>

        <nav class="navbar navbar-inverse" role="navigation" style="background: #610505;">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo base_url(); ?>user/index">
                        <?php echo (isset($user_info) && $user_info['company_name']) ? strtoupper($user_info['company_name']) : strtoupper("Company Name") ?>
                    </a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <?php
                        if (isset($reseller_user) && $reseller_user != "") {
                            if (isset($login_from) && $login_from) {
                                if ($login_from == 'user') {
                                    ?>
                                    <li class="dropdown">
                                        <a href="<?php echo base_url(); ?>user/back_to_account/<?php echo $reseller_user . "/" . $user_id; ?>" 
                                           data-toggle="tooltip" data-placement="bottom" title="Back to your Account">
                                            <i class="fa fa-backward"></i>
                                        </a>
                                    </li>
                                    <?php
                                } elseif ($login_from == 'admin') {
                                    ?>
                                    <li class="dropdown">
                                        <a href="<?php echo base_url(); ?>admin/back_to_account/<?php echo $reseller_user; ?>" 
                                           data-toggle="tooltip" data-placement="bottom" title="Back to your Account">
                                            <i class="fa fa-backward"></i>
                                        </a>
                                    </li>                                    
                                    <?php
                                }
                            }
                        }
                        ?>
                    </ul>
                    <?php if (isset($response) && $response['user_alert']) {
                        ?>
                        <div align="center" style="background-color: #dada9c; width: 500px; border-radius: 5px; height: 30px; float: left ;margin-left: 200px; font-size: 15px; margin-top: 12px; color: #483737 ;  ">
                            <div align="center"  class="fa fa-exclamation-triangle" aria-hidden="true"  style="background-color: #dada9c; width: 500px; border-radius: 5px; height: 22px; float: left ; font-size: 15px; margin-top: 10px; color: #483737 ; ">

                                <span>Alert :- <?php echo $response['user_alert'];
                        ?></span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <ul class="nav navbar-nav navbar-right">

  <!--<span style=" float: left ; margin-left: 30px; font-size: 15px; margin-top: 16px; " ><i class="fa fa-bell" aria-hidden="true"></i></span> -->
                        <li>
                            <a href="<?php echo base_url(); ?>user/account_settings/other" data-toggle="tooltip" data-placement="bottom" 
                               title="Your Timezone is GMT <?php echo (isset($user_info) && $user_info['default_timezone']) ? $user_info['default_timezone'] : ""; ?>">
                                   <?php //echo date('l', strtotime((isset($user_info) && $user_info['default_timezone']) ? $user_info['default_timezone'] : ""));    ?>
                                <span id="clock1"></span>
                            </a>
                        </li>
                        <?php if ($user_info['spacial_reseller_status'] == 1) { ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Credits <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li tabindex="1">
                                        <label  style="font-weight: bold;">Credits</label>
                                    </li>
                                    <li class="divider"></li>
                                    <li tabindex="2">
                                        <label>Promotional</label><br/>
                                        <label><?php echo (isset($user_info) && $user_info['special_pr_balance']) ? $user_info['special_pr_balance'] : 0; ?></label>
                                    </li>
                                    <li class="divider"></li>
                                    <li tabindex="3">
                                        <label>Transactional</label><br/>
                                        <label><?php echo (isset($user_info) && $user_info['special_tr_balance']) ? $user_info['special_tr_balance'] : 0; ?></label>
                                    </li>
                                    <li class="divider"></li>
                                    <li tabindex="4">
                                        <label>Stock Promotion</label><br/>
                                        <label><?php echo (isset($user_info) && $user_info['stock_credits']) ? $user_info['stock_credits'] : 0; ?></label>
                                    </li>
                                    <li class="divider"></li>
                                    <li tabindex="5">
                                        <label>Premium Promo</label><br/>
                                        <label><?php echo (isset($user_info) && $user_info['prtodnd_credits']) ? $user_info['prtodnd_credits'] : 0; ?></label>
                                    </li>

                                </ul>
                            </li>
                        <?php } ?>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Balance <span class="caret"></span></a>
                            <?php
                            if ($user_info['country_status'] == 2) {
                                ?>
                                <ul class="dropdown-menu" role="menu">
                                    <li tabindex="1">
                                        <label style="font-weight: bold;">International SMS</label>
                                    </li>
                                    <li tabindex="2">
                                        <label><?php echo (isset($user_info) && $user_info['international_balance']) ? $user_info['international_balance'] : 0; ?></label>
                                    </li>
                                </ul>
                                <?php
                            } else {
                                ?>
                                <ul class="dropdown-menu" role="menu">
                                    <li tabindex="1">
                                        <label style="font-weight: bold;">TEXT SMS</label>
                                    </li>
                                    <li tabindex="2">
                                        <label>Promotional</label><br/>
                                        <label><?php echo (isset($user_info) && $user_info['pr_sms_balance']) ? $user_info['pr_sms_balance'] : 0; ?></label>
                                    </li>
                                    <li tabindex="3">
                                        <label>Transactional</label><br/>
                                        <label><?php echo (isset($user_info) && $user_info['tr_sms_balance']) ? $user_info['tr_sms_balance'] : 0; ?></label>
                                    </li>
                                    <li tabindex="4">
                                        <label>Stock Promotion</label><br/>
                                        <label><?php echo (isset($user_info) && $user_info['stock_balance']) ? $user_info['stock_balance'] : 0; ?></label>
                                    </li>
                                    <li tabindex="5">
                                        <label>Premium Promo</label><br/>
                                        <label><?php echo (isset($user_info) && $user_info['prtodnd_balance']) ? $user_info['prtodnd_balance'] : 0; ?></label>
                                    </li>
                                    <li class="divider"></li>
                                    <li tabindex="6">
                                        <label style="font-weight: bold;">Voice Call</label>
                                    </li>
                                    <li tabindex="7">
                                        <label>Promotional</label><br/>
                                        <label><?php echo (isset($user_info) && $user_info['pr_voice_balance']) ? $user_info['pr_voice_balance'] : 0; ?></label>
                                    </li>
                                    <li tabindex="8">
                                        <label>Dynamic</label><br/>
                                        <label><?php echo (isset($user_info) && $user_info['tr_voice_balance']) ? $user_info['tr_voice_balance'] : 0; ?></label>
                                    </li>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Settings <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li tabindex="1">
                                    <a href="<?php echo base_url(); ?>user/change_password">Change Password</a> 
                                </li>
                                <?php
                                if (isset($user_info) && $user_info['utype'] && $user_info['utype'] == 'Reseller') {
                                    ?>
                                    <li tabindex="2">
                                        <a href="<?php echo base_url(); ?>user/reseller_settings/settings">Reseller Setting</a> 
                                    </li>
                                    <li tabindex="3">
                                        <a href="<?php echo base_url(); ?>user/notify_users/by_sms">Notifications</a> 
                                    </li>
                                    <?php
                                }
                                ?>
                                <?php if ($user_info['spacial_reseller_status'] == 1) { ?>
                                    <li><a href="<?php echo base_url(); ?>user/white_list_numbers">White List Numbers</a></li>
                                <?php } ?>
                                <li tabindex="4">
                                    <a href="<?php echo base_url(); ?>user/sender_ids">Sender IDs</a>
                                </li>
                                <li tabindex="5">
                                    <a href="<?php echo base_url(); ?>user/keywords">Keywords</a>
                                </li>
                                <li tabindex="6">
                                    <a href="<?php echo base_url(); ?>user/webhooks">Webhooks</a>
                                </li>
                                <li tabindex="7">
                                    <a href="<?php echo base_url(); ?>user/api_security">API Security</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Support <span class="caret"></span></a>
                            <ul class="dropdown-menu custom-dropdown-menu" role="menu">
                                <li tabindex="1">
                                    <label>Account Manager</label>
                                </li>
                                <li class="divider"></li>
                                <li tabindex="2">
                                    <label><?php echo (isset($account_manager) && $account_manager['name']) ? $account_manager['name'] : ""; ?></label>
                                </li>
                                <li tabindex="3">
                                    <label><?php echo (isset($account_manager) && $account_manager['contact_number']) ? $account_manager['contact_number'] : ""; ?></label>
                                </li>
                                <li tabindex="4">
                                    <label><?php echo (isset($account_manager) && $account_manager['email_address']) ? $account_manager['email_address'] : ""; ?></label>
                                </li>
                                <?php
                                if (isset($response) && $response['user_id']) {

                                    $user_id = $response['user_id'];
                                    $query = "SELECT `admin_id` FROM `users` WHERE `user_id` = '" . $user_id . "'";
                                    $result = mysqli_query($query);
                                    $row = mysqli_fetch_array($result);
                                    $admin_id = $row["admin_id"];

                                    if ($admin_id == 1) {
                                        ?>

                                        <li tabindex="5">
                                            <label><div id="update_manager">Update Manager Info</div></label>
                                        </li>

                                        <?php
                                    } else {
                                        
                                    }
                                }
                                ?>


                            </ul>
                        </li>


                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo strtoupper(isset($username) ? $username : "User"); ?> 
                                <span class="caret"></span> 
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <label>Expiry Date</label>
                                    <p><?php echo date('d F Y', strtotime((isset($user_info) && $user_info['expiry_date']) ? $user_info['expiry_date'] : "")); ?></p>
                                </li>
                                <li class="divider"></li>
                                <li><a href="<?php echo base_url(); ?>user/account_settings/general">Account Setting</a></li>
                                <li><a href="<?php echo base_url(); ?>user/my_transactions">Transaction Logs</a></li>
                                <li><a href="<?php echo base_url(); ?>user/generate_key">Generate Key</a></li>
                                <li><a href="<?php echo base_url(); ?>user/get_senderids_tracker/<?php echo $user_id; ?>">Sender_Ids Tracker</a></li>
                                <li><a href="<?php echo base_url(); ?>user/daily_consumption">Daily SMS Consumption</a></li>

                                <?php if ($user_info['spacial_reseller_status'] == 1) { ?>
                                    <li><a href="<?php echo base_url(); ?>user/credit_transactions">Credits Log</a></li>
                                <?php } ?>
                                <?php
                                if (isset($user_info) && $user_info['utype']) {
                                    if ($user_info['utype'] == 'Reseller') {
                                        if (isset($user_info['user_settings_col']) && $user_info['user_settings_col']) {
                                            ?>
                                            <li><a href="<?php echo base_url(); ?>user/make_me_user">Make Me User</a></li>
                                            <li><a href="<?php echo base_url(); ?>user/all_users_balance">Existing Users Balance</a></li>
                                            <li><a href="<?php echo base_url(); ?>user/account_sms_consumption">Total Account Consumption</a></li>
                                            <?php
                                        }
                                    } elseif ($user_info['utype'] == 'User') {
                                        ?>
                                        <li><a href="<?php echo base_url(); ?>user/make_me_reseller">Make Me Reseller</a></li>

                                        <?php
                                    }
                                }
                                ?>
                                <li class="divider"></li>
                                <li><a href="<?php echo base_url(); ?>user/logout">Logout</a></li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        <div id="menubar" class="hidden-xs">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-1 col-sm-12 <?php echo (isset($page) && $page == "send_sms") ? 'active' : '' ?>">
                        <a href="<?php echo base_url(); ?>user/index"><i class="fa fa-envelope"></i> Send SMS</a>
                    </div>
                    <div class="col-md-1 col-sm-12 <?php echo (isset($page) && $page == "advance_send_sms") ? 'active' : '' ?>">
                        <a href="<?php echo base_url(); ?>user/advance_send_sms"><i class="fa fa-bookmark-o"></i> Custom SMS</a>
                    </div>
                    <div class="col-md-1 col-sm-12 <?php echo (isset($page) && $page == "text_delivery_reports") ? 'active' : '' ?>">
                        <a href="<?php echo base_url(); ?>user/text_delivery_reports"><i class="fa fa-files-o"></i> Text DLR</a>
                    </div>
                    <div class="col-md-1 col-sm-12 <?php echo (isset($page) && $page == "schedule_reports") ? 'active' : '' ?>">
                        <a href="<?php echo base_url(); ?>user/schedule_reports"><i class="fa fa-clock-o"></i> Schedule DLR</a>
                    </div>
                    <div class="col-md-1 col-sm-12 <?php echo (isset($page) && $page == "voice_sms") ? 'active' : '' ?>">
                        <a href="<?php echo base_url(); ?>user/voice_sms"><i class="fa fa-phone"></i> Voice SMS</a>
                    </div>
                    <div class="col-md-1 col-sm-12 <?php echo (isset($page) && $page == "voice_delivery_reports") ? 'active' : '' ?>">
                        <a href="<?php echo base_url(); ?>user/voice_delivery_reports"><i class="fa fa-files-o"></i> Voice DLR</a>
                    </div>
                    <div class="col-md-1 col-sm-12 <?php echo (isset($page) && $page == "short_codes") ? 'active' : '' ?>">
                        <a href="<?php echo base_url(); ?>user/short_codes/dashboard"><i class="fa fa-list-ol"></i> Short Code</a>
                    </div>
                    <div class="col-md-1 col-sm-12 <?php echo (isset($page) && $page == "long_codes") ? 'active' : '' ?>">
                        <a href="<?php echo base_url(); ?>user/long_codes/dashboard"><i class="fa fa-list-ol"></i> Long Code</a>
                    </div>
                    <div class="col-md-1 col-sm-12 <?php echo (isset($page) && $page == "missed-call-alerts") ? 'active' : '' ?>">
                        <a href="<?php echo base_url(); ?>user/missed_call_alerts/dashboard"><i class="fa fa-list-ol"></i> Missed Call</a>
                    </div>
                    <div class="col-md-1 col-sm-12 <?php echo (isset($page) && $page == "phonebook") ? 'active' : '' ?>">
                        <a href="<?php echo base_url(); ?>user/phonebook"><i class="fa fa-folder-open-o"></i> Phonebook</a>
                    </div>
                    <?php
                    if (isset($user_info) && $user_info['utype']) {
                        if ($user_info['utype'] == 'Reseller') {
                            if (isset($user_info['user_settings_col']) && $user_info['user_settings_col']) {
                                ?>
                                <div class="col-md-1 col-sm-12 <?php echo (isset($page) && $page == "users") ? 'active' : '' ?>">
                                    <a href="<?php echo base_url(); ?>user/users"><i class="fa fa-users"></i> User Management</a>
                                </div>
                                <div class="col-md-1 col-sm-12 <?php echo (isset($page) && $page == "manage_website") ? 'active' : '' ?>">
                                    <a href="<?php echo base_url(); ?>user/manage_website"><i class="fa fa-gears"></i> Manage Website</a>
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>

                    <div class="col-md-1 col-sm-12 <?php echo (isset($page) && $page == "check_balance") ? 'active' : '' ?>">
                        <a target="_blank" href="<?php echo base_url(); ?>api_docs/check_balance"><i class="fa fa-life-ring"></i> Developer Tools</a>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-success form-control btn-block visible-xs menuxs" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Menu
        </button>
        <div class="collapse" id="collapseExample">
            <div id="menubar" class="visible-xs">
                <div class="container-fluid padding0">
                    <div class="row">
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page == "send_sms") ? 'active' : '' ?>">
                            <a href="<?php echo base_url(); ?>user/index"><i class="fa fa-envelope"></i> Send SMS</a>
                        </div>
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page == "advance_send_sms") ? 'active' : '' ?>">
                            <a href="<?php echo base_url(); ?>user/advance_send_sms"><i class="fa fa-bookmark-o"></i> Custom SMS</a>
                        </div>
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page == "voice_sms") ? 'active' : '' ?>">
                            <a href="<?php echo base_url(); ?>user/voice_sms"><i class="fa fa-phone"></i> Voice SMS</a>
                        </div>
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page == "voice_delivery_reports") ? 'active' : '' ?>">
                            <a href="<?php echo base_url(); ?>user/voice_delivery_reports"><i class="fa fa-files-o"></i> Voice DLR</a>
                        </div>
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page == "schedule_reports") ? 'active' : '' ?>">
                            <a href="<?php echo base_url(); ?>user/schedule_reports"><i class="fa fa-clock-o"></i> Schedule SMS</a>
                        </div>
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page == "text_delivery_reports") ? 'active' : '' ?>">
                            <a href="<?php echo base_url(); ?>user/text_delivery_reports"><i class="fa fa-files-o"></i> Text DLR</a>
                        </div>
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page == "short_codes") ? 'active' : '' ?>">
                            <a href="<?php echo base_url(); ?>user/short_codes/dashboard"><i class="fa fa-list-ol"></i> Short Code</a>
                        </div>
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page == "long_codes") ? 'active' : '' ?>">
                            <a href="<?php echo base_url(); ?>user/long_codes/dashboard"><i class="fa fa-list-ol"></i> Long Code</a>
                        </div>
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page == "phonebook") ? 'active' : '' ?>">
                            <a href="<?php echo base_url(); ?>user/phonebook"><i class="fa fa-folder-open-o"></i> Phonebook</a>
                        </div>
                        <?php
                        if (isset($user_info) && $user_info['utype']) {
                            if ($user_info['utype'] == 'Reseller') {
                                if (isset($user_info['user_settings_col']) && $user_info['user_settings_col']) {
                                    ?>
                                    <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page == "users") ? 'active' : '' ?>">
                                        <a href="<?php echo base_url(); ?>user/users"><i class="fa fa-users"></i> User Management</a>
                                    </div>
                                    <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page == "manage_website") ? 'active' : '' ?>">
                                        <a href="<?php echo base_url(); ?>user/manage_website"><i class="fa fa-gears"></i> Manage Website</a>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        ?>
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page == "check_balance") ? 'active' : '' ?>">
                            <a target="_blank" href="<?php echo base_url(); ?>api_docs/check_balance"><i class="fa fa-life-ring"></i> Developer Tools</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="contactForm">

            <h3>Select Your Account Manager</h3>


            <form  method="post" action="<?php echo base_url(); ?>index.php/user/update_account_manager">

                <select name="account_manager" class="name_dropdown">
                    <?php
                    if (isset($AdminName) && $AdminName) {
                        foreach ($AdminName as $admin_name1) {
                            ?>

                            <option value="<?php echo $admin_name1['admin_id']; ?>"><?php echo $admin_name1['admin_name']; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                <input class="formBtn" type="submit" value="Submit" />

            </form>
        </div>

        <script>
            $(function () {

                // contact form animations
                $('#update_manager').click(function () {
                    $('#contactForm').fadeToggle();
                })
                $(document).mouseup(function (e) {
                    var container = $("#contactForm");

                    if (!container.is(e.target) // if the target of the click isn't the container...
                            && container.has(e.target).length === 0) // ... nor a descendant of the container
                    {
                        container.fadeOut();
                    }
                });

            });

        </script>
