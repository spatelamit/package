<?php
$theme = "";
if ($this->session->userdata('theme_data')) {
    $theme_data = $this->session->userdata('theme_data');
    $theme = $theme_data['theme'];
} elseif (isset($user_info) && $user_info && $user_info['theme_color']) {
    $theme = $user_info['theme_color'];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
       <meta name="robots" content="noindex,nofollow">
        <title>Bulk SMS-Developer Tools</title>
        <link href="<?php echo base_url(); ?>Assets/user/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>Assets/user/css/fonts.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/datepicker.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/ionicons.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/style.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/style-selector.css" />
        <link id="color" rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/color-schemes/<?php echo (isset($theme) && $theme && $theme != "") ? $theme : "default"; ?>.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/sidenav.css" />
        <!--<link href="<?php echo base_url(); ?>Assets/highlight/prism.css" rel="stylesheet" type="text/css" media="screen" />-->
        <style type="text/css">
            .alert-success{
                background-color: #1E7148;
            }
            .alert-danger{
                background-color: #D45E5E;
            }
            .alert-warning{
                background-color: #B78C40;
            }
            <?php
            if (isset($user_info) && $user_info && $user_info['utype']) {
                if ($user_info['utype'] == 'Reseller') {
                    ?>
                    #menubar .col-md-1 {
                        width: 9.66%;
                        padding: 22px 0;
                        text-align: center;
                    }
                    <?php
                } else {
                    ?>
                    #menubar .col-md-1 {
                        width: 12.30%;
                        padding: 22px 0;
                        text-align: center;
                    }
                    <?php
                }
            }
            ?>
        </style>
    </head>
    <body id="page_container">
        <input type="hidden" name="base_url" id="base_url" value="<?php echo site_url(); ?>" />
        <input type="hidden" name="base_url1" id="base_url1" value="<?php echo base_url(); ?>" />
        <div class="color-switcher" id="choose_color">
            <a href="#." class="picker_close">
                <i class="fa fa-gear fa-spin"></i>
            </a>
            <div class="theme-colours">
                <h4>Choose Color Style</h4>
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

        <nav class="navbar navbar-inverse" role="navigation">
            <div class="container-fluid">

                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <i class="fa fa-sign-out"></i>
                    </button>
                    <a class="navbar-brand" href="<?php echo base_url(); ?>api_docs/check_balance">DEVELOPER TOOLS</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <span id="error_message" style="color:red;font-weight: 700;">
                                <?php
                                if (isset($error_msg) && $error_msg)
                                    echo $error_msg;
                                ?>
                            </span>
                            <span style="color:green;font-weight: 700;">
                                <?php
                                if (isset($success_msg) && $success_msg)
                                    echo $success_msg;
                                ?>
                            </span>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="<?php echo base_url(); ?>user/index">Home</a>
                        </li>
                        <li>
                            <a href="#" data-toggle="modal" data-target="#generateKey">Auth Key</a>
                            <!-- Modal -->
                            <div class="modal fade" id="generateKey" tabindex="-1" role="dialog" aria-labelledby="Generate_Key" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form role="form" id="regenerateKeyForm" method='post' action="javascript:regenerateKey();">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="Generate_Key">Generate Authentication Key</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="auth_key">Authentication Key</label>
                                                        <input type="text" name="auth_key" id="auth_key" placeholder="Generate Authentication Key" 
                                                               value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>"
                                                               class="form-control" readonly="" />
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <span style="color: #E42929;font-size: 12px;">
                                                            <strong>Remember:</strong> Don't change this key until you have some kind of security issue. 
                                                            Changing this key will stop the entire APIs using old authorization key.
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Regenerate</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>user/logout">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <button class="btn btn-success form-control btn-block visible-xs menuxs" type="button" data-toggle="collapse" data-target="#collapse03" aria-expanded="false" aria-controls="collapse03">
            Menu
        </button>
        <div class="collapse" id="collapse03">
            <div id="menubar" class="visible-xs">
                <div class="container-fluid padding0">
                    <div class="row">
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page && $page == "basic") ? 'active"' : '' ?>">
                            <a href="<?php echo base_url(); ?>api_docs/check_balance"><i class="ion ion-clipboard"></i> Basic</a>
                        </div>
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page && $page == "reseller") ? 'active"' : '' ?>">
                            <a href="<?php echo base_url(); ?>api_docs/add_user"><i class="ion ion-android-contacts"></i> Reseller</a>
                        </div>
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page && $page == "phonebook") ? 'active"' : '' ?>">
                            <a href="<?php echo base_url(); ?>api_docs/add_group"><i class="ion ion-ios7-telephone"></i> Phonebook</a>
                        </div>
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page && $page == "send_sms") ? 'active"' : '' ?>">
                            <a href="<?php echo base_url(); ?>api_docs/send_sms_api"><i class="ion ion-ios7-chatboxes"></i> Text SMS</a>
                        </div>
                       
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page && $page == "voice_sms") ? 'active"' : ''                ?>">
                            <a href="<?php echo base_url(); ?>api_docs/voice_sms_api"><i class="fa fa-phone"></i> Voice SMS</a>
                        </div>
                         <!--
                        <div class="col-xs-6 menu_xs <?php //echo (isset($page) && $page && $page == "virtual_numbers") ? 'active"' : ''                ?>">
                            <a href="<?php echo base_url(); ?>api_docs/virtual_numbers"><i class="fa fa-bookmark-o"></i> Virtual Numbers</a>
                        </div>
                        -->
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page && $page == "virtual_numbers") ? 'active"' : '' ?>">
                            <a href="<?php echo base_url(); ?>api_docs/virtual_numbers/long_code"><i class="fa fa-list-ol"></i> Virtual Numbers</a>
                        </div>
                        <div class="col-xs-6 menu_xs <?php echo (isset($page) && $page && $page == "sample_code") ? 'active"' : '' ?>">
                            <a href="<?php echo base_url(); ?>api_docs/in_php"><i class="fa fa-code"></i> Sample Code </a>
                        </div>
                        <div class="col-xs-6 menu_xs">
                            <a href="<?php echo base_url(); ?>user/index"><i class="fa fa-home"></i> Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="menubar" class="hidden-xs">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-1 col-xs-12 <?php echo (isset($page) && $page && $page == "basic") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>api_docs/check_balance"><i class="ion ion-clipboard"></i> Basic</a>
                    </div>
                    <div class="col-md-1 col-xs-12 <?php echo (isset($page) && $page && $page == "reseller") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>api_docs/add_user"><i class="ion ion-android-contacts"></i> Reseller</a>
                    </div>
                    <div class="col-md-1 col-xs-12 <?php echo (isset($page) && $page && $page == "phonebook") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>api_docs/add_group"><i class="ion ion-ios7-telephone"></i> Phonebook</a>
                    </div>
                    <div class="col-md-1 col-xs-12 <?php echo (isset($page) && $page && $page == "send_sms") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>api_docs/send_sms_api"><i class="ion ion-ios7-chatboxes"></i> Text SMS</a>
                    </div>
                    <div class="col-md-1 col-xs-12 <?php echo (isset($page) && $page && $page == "virtual_numbers") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>api_docs/virtual_numbers/long_code"><i class="fa fa-list-ol"></i> Virtual Numbers</a>
                    </div>
             
                    <div class="col-md-1 col-xs-12 <?php echo (isset($page) && $page && $page == "voice_sms") ? 'active"' : ''                ?>">
                        <a href="<?php echo base_url(); ?>api_docs/voice_sms_api"><i class="fa fa-phone"></i> Voice SMS</a>
                    </div>
                           <!--
                    <div class="col-md-1 col-xs-12 <?php //echo (isset($page) && $page && $page == "virtual_numbers") ? 'active"' : ''                ?>">
                        <a href="<?php echo base_url(); ?>api_docs/virtual_numbers"><i class="fa fa-bookmark-o"></i> Virtual Numbers</a>
                    </div>
                    -->
                    <div class="col-md-1 col-xs-12 <?php echo (isset($page) && $page && $page == "sample_code") ? 'active"' : '' ?>">
                        <a href="<?php echo base_url(); ?>api_docs/in_php"><i class="fa fa-code"></i> Sample Code </a>
                    </div>
                </div>
            </div>
