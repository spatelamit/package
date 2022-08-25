<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
       <meta name="robots" content="noindex,nofollow">
        <title>Kannel Configuration: Dashboard</title>
        <link href="<?php echo base_url(); ?>Assets/kannel/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>Assets/kannel/css/dashboard.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>Assets/kannel/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>Assets/kannel/css/ie10-viewport-bug-workaround.css" rel="stylesheet" type="text/css" />
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="<?php echo base_url(); ?>Assets/kannel/js/ie-emulation-modes-warning.js" type="text/javascript"></script>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head> 
    <body>
        <!-- Header -->
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!--<a href="#menu-toggle" class="pull-left" id="menu-toggle"><i class="glyphicon glyphicon-th"></i></a>-->
                    <a class="navbar-brand" href="<?php echo base_url(); ?>kannel_control">Kannel Configuration</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="<?php echo base_url(); ?>kannel_control">Dashboard</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>kannel_control/monitor">Kannel Monitor</a>
                        </li>
                        <li>
                            <a href="http://www.kannel.org/" target="_blank">Kannel</a>
                        </li>
                        <li>
                            <a href="http://www.kannel.org/download/1.4.4/userguide-1.4.4/userguide.html" target="_blank">Kannel User Guide</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>kannel_control/sqlbox_userguide" target="_blank">SQLBox User Guide</a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                Group Config
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="http://www.kannel.org/download/1.4.4/userguide-1.4.4/userguide.html#AEN483" target="_blank">Core</a>
                                </li>
                                <li>
                                    <a href="http://www.kannel.org/download/1.4.4/userguide-1.4.4/userguide.html#AEN3745" target="_blank">SMSBox</a>
                                </li>
                                <li>
                                    <a href="http://www.kannel.org/download/1.4.4/userguide-1.4.4/userguide.html#AEN3481" target="_blank">DB Connection</a>
                                </li>
                                <li>
                                    <a href="http://www.kannel.org/download/1.4.4/userguide-1.4.4/userguide.html#AEN3663" target="_blank">DLR DB</a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a href="http://www.kannel.org/download/1.4.4/userguide-1.4.4/userguide.html#AEN1393" target="_blank">Fake SMSC</a>
                                </li>
                                <li>
                                    <a href="http://www.kannel.org/download/1.4.4/userguide-1.4.4/userguide.html#AEN1393" target="_blank">Real SMSC</a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a href="http://www.kannel.org/download/1.4.4/userguide-1.4.4/userguide.html#AEN4743" target="_blank">SendSMS User</a>
                                </li>
                                <li>
                                    <a href="http://www.kannel.org/download/1.4.4/userguide-1.4.4/userguide.html#AEN3945" target="_blank">SMSBox Route</a>
                                </li>
                                <li>
                                    <a href="http://www.kannel.org/download/1.4.4/userguide-1.4.4/userguide.html#AEN3989" target="_blank">SMS Service</a>
                                </li>
                            </ul>
                        </li>
                        <li><a href="http://www.kannel.org/pipermail/users/" target="_blank">Help</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Container -->
        <div class="container-fluid">
            <div class="row">
                <!-- Navigation Bar -->
                <div id="sidebar" class="col-sm-3 col-md-2 sidebar">
                    <!-- Create Forms -->
                    <ul class="nav nav-sidebar">
                        <li class="active">
                            <a href="#"><strong>Create Configuration</strong><span class="sr-only">(current)</span></a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'core') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/create/core">Core Group</a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'smsbox') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/create/smsbox">SMSBox Group</a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'db_connection') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/create/db_connection">DB Connection Group</a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'dlr_db') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/create/dlr_db">DLR DB Group</a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'fake_smsc') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/create/fake_smsc">Fake SMSC Group</a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'smsc') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/create/smsc/transceiver">SMSC Group</a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'sendsms_user') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/create/sendsms_user">SendSMS User Group</a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'smsbox_route') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/create/smsbox_route">SMSBox Route Group</a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'sms_service') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/create/sms_service">SMS Service Group</a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'sqlbox') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/create/sqlbox">SQLBox Group</a>
                        </li>
                    </ul>
                    <!-- Generate Tables -->
                    <ul class="nav nav-sidebar">
                        <li class="active">
                            <a href="#">
                                <strong>Generate Configuration</strong>
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'main_config') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/generate/main_config">Main Config File</a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'smsc_config') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/generate/smsc_config">SMSC Config File</a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'fake_smsc_config') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/generate/fake_smsc_config">Fake SMSC Config File</a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'sendsms_user_config') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/generate/sendsms_user_config">SendSMS User Config File</a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'smsbox_route_config') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/generate/smsbox_route_config">SMSBox Route Config File</a>
                        </li>
                        <li class="<?php echo (isset($page) && $page == 'sqlbox_config') ? 'sub_active' : ''; ?>">
                            <a href="<?php echo base_url(); ?>kannel_control/generate/sqlbox_config">SQLBox Config File</a>
                        </li>
                    </ul>
                </div>
                <!-- Container -->
                <div id="main_container" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <?php echo ((isset($page_title) && $page_title) ? '<h4 class="sub-header">' . ((isset($file) && $file) ? $file . "</h4>" : "</h4>") : ""); ?>