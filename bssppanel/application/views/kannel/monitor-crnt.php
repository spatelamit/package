<?php
/*
 * kannel-status.php -- display a summarized kannel status page
 *
 * This php script acts as client for the status.xml output of
 * Kannel's bearerbox and aggregates information about smsc link
 * status and message flow. It is the first step to provide an
 * external Kannel Control Center interface via HTTP.
 *
 * Stipe Tolj <stolj@wapme.de>
 * Copyright (c) 2003 Kannel Group.
 */
require APPPATH . '/libraries/xmlfunc.php';
date_default_timezone_set("Asia/Kolkata");
/* config section: define which Kannel status URLs to monitor */
$config_array = array("base_url" => "http://localhost:15000",
    "status_passwd" => "bulksms",
    "admin_passwd" => "bulksms",
    "name" => "Kannel"
);
$configs = array(
    array("base_url" => "http://localhost:15000",
        "status_passwd" => "bulksms",
        "admin_passwd" => "bulksms",
        "name" => "Kannel 1"
    )
);

// Refresh Rate
$refresh = 120;
$timeout = (!empty($refresh)) ? $refresh : 120;
/* some constants */
$CONST_QUEUE_ERROR = 100;
$depth = array();
$status = array();
/* set php internal error reporting level */
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="refresh" content="<?php echo "$timeout; URL=" . base_url() . "kannel_control/monitor"; ?>" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Kannel Monitor</title>
        <link href="<?php echo base_url(); ?>Assets/kannel/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>Assets/kannel/css/dashboard.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>Assets/kannel/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>Assets/kannel/css/ie10-viewport-bug-workaround.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/kannel/css/jqClock.css" />
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
                    <a class="navbar-brand" href="<?php echo base_url(); ?>kannel_control">Kannel Status Monitor</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="<?php echo base_url(); ?>kannel_control">Kannel Dashboard</a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                Default Kannel
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="http://166.62.16.78:15000/status?password=bulksms" target="_blank">Bulk24SMS Kannel</a>
                                </li>
                                <li>
                                    <a href="http://192.169.236.101:15000/status?password=bulksms" target="_blank">BSSP Kannel</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                Admin Commands
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="javascript:void(0);"
                                       onClick="admin_url('suspend', '<?php echo $config_array["base_url"] . "/suspend"; ?>', '<?php echo $config_array["admin_passwd"]; ?>');">Suspend</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                       onClick="admin_url('isolate', '<?php echo $config_array["base_url"] . "/isolate"; ?>', '<?php echo $config_array["admin_passwd"]; ?>');">Isolate</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                       onClick="admin_url('resume', '<?php echo $config_array["base_url"] . "/resume"; ?>', '<?php echo $config_array["admin_passwd"]; ?>');">Resume</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                       onClick="admin_url('flush-dlr', '<?php echo $config_array["base_url"] . "/flush-dlr"; ?>', '<?php echo $config_array["admin_passwd"]; ?>');">Flush DLR</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                       onClick="admin_url('shutdown', '<?php echo $config_array["base_url"] . "/shutdown"; ?>', '<?php echo $config_array["admin_passwd"]; ?>');">Shutdown</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                       onClick="admin_url('restart', '<?php echo $config_array["base_url"] . "/restart"; ?>', '<?php echo $config_array["admin_passwd"]; ?>');">Restart</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Container -->
        <div class="container-fluid">
            <div class="row">
                <div id="main_container" class="col-sm-12 col-md-12 col-xs-12 main">
                    <!--Kannel Configuration-->
                    <div class="col-sm-12 col-md-12 col-xs-12">
                        <h4 class="sub-header">
                            <span>
                                Kannel Configuration: <?php echo sizeof($configs); ?> Instance(s)
                            </span>
                            <span class="pull-right" id="clock1"></span>
                        </h4>
                        <?php
                        /* loop through all configured URLs */
                        foreach ($configs as $inst => $config) {
                            $xml_parser = xml_parser_create();
                            xml_set_element_handler($xml_parser, "startElement", "endElement");
                            /* get the status.xml URL of one config */
                            $url = $config["base_url"] . "/status.xml?password=" . $config["status_passwd"];
                            $status[$inst] = "";
                            /* open the file description to the URL */
                            if (($fp = fopen($url, "r"))) {
                                echo "<span class=green>($inst) (" . $config["name"] . ") <b>$url</b></span> <br /> \n";
                                /* read the XML input */
                                while (!feof($fp)) {
                                    $status[$inst] .= fread($fp, 200000);
                                }
                            } else {
                                echo "<span class=red>($inst) (" . $config["name"] . ") <b>$url</b></span> <br /> \n";
                            }
                            fclose($fp);
                            /* get the status of this bearerbox */
                            $s = XPathValue("gateway/status", $status[$inst]);
                            if (preg_match("/(.*), uptime (.*)d (.*)h (.*)m (.*)s/i", $s, $regs)) {
                                $ts = ($regs[2] * 24 * 60 * 60) + ($regs[3] * 60 * 60) + ($regs[4] * 60) + $regs[5];
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                echo "<b>$regs[1]</b>, ";
                                echo "started " . date("Y-m-d H:i:s", mktime() - $ts);
                                $bb_time[$inst] = mktime() - $ts;
                                echo ", uptime $regs[2]d $regs[3]h $regs[4]m $regs[5]s <br />\n";
                            }
                            /* get the inbound load of this bearerbox */
                            $s = XPathValue("gateway/sms/inbound", $status[$inst]);
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Inbound:</b> $s<br/>";
                            /* get the outbound load of this bearerbox */
                            $s = XPathValue("gateway/sms/outbound", $status[$inst]);
                            echo "&nbsp;&nbsp;&nbsp;<b>Outbound:</b> $s<br/>";
                            /* get the info of this bearerbox */
                            $s = XPathValue("gateway/version", $status[$inst]);
                            $s = str_replace(chr(10), '<br />&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .
                                    '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $s);
                            $s = str_replace(chr(13), '', $s);
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Version:</b> $s<br/>";
                            xml_parser_free($xml_parser);
                        }
                        ?>
                    </div>
                    <!--Overall SMS Traffic-->
                    <div class="col-sm-12 col-md-12 col-xs-12" id="destination1">
                        <h4 class="sub-header">Overall SMS Traffic</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-right">
                                            Received (MO)
                                        </th>
                                        <th class="text-right">
                                            Inbound (MO)
                                        </th>
                                        <th class="text-right">
                                            Sent (MT)
                                        </th>
                                        <th class="text-right">
                                            Outbound (MT)
                                        </th>
                                        <th class="text-right">
                                            Queued (MO)
                                        </th>
                                        <th class="text-right">
                                            Queued (MT)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-right">
                                            <?php
                                            $sum = 0;
                                            foreach ($configs as $inst => $config) {
                                                $mo[$inst] = XPathValue("gateway/sms/received/total", $status[$inst]);
                                                $sum += $mo[$inst];
                                                echo "($inst) <b>" . nf($mo[$inst]) . "</b> msgs<br />\n";
                                            }
                                            echo "<hr size=1>\n";
                                            echo "(all) <b>" . nf($sum) . "</b> msgs<br />\n";
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $sum = 0;
                                            foreach ($configs as $inst => $config) {
                                                $in[$inst] = XPathValue("gateway/sms/inbound", $status[$inst]);
                                                $sum += $in[$inst];
                                                echo "($inst) <b>" . nfd($in[$inst]) . "</b> msgs/s<br />\n";
                                            }
                                            echo "<hr size=1>\n";
                                            echo "(all) <b>" . nfd($sum) . "</b> msgs/s<br />\n";
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $sum = 0;
                                            foreach ($configs as $inst => $config) {
                                                $mt[$inst] = XPathValue("gateway/sms/sent/total", $status[$inst]);
                                                $sum += $mt[$inst];
                                                echo "($inst) <b>" . nf($mt[$inst]) . "</b> msgs<br />\n";
                                                //echo "($inst) <b>" . $mt[$inst] . "</b> msgs<br />\n";
                                            }
                                            echo "<hr size=1>\n";
                                            echo "(all) <b>" . nf($sum) . "</b> msgs<br />\n";
                                            //echo "(all) <b>" . $sum . "</b> msgs<br />\n";
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $sum = 0;
                                            foreach ($configs as $inst => $config) {
                                                $out[$inst] = XPathValue("gateway/sms/outbound", $status[$inst]);
                                                $sum += $out[$inst];
                                                echo "($inst) <b>" . nfd($out[$inst]) . "</b> msgs/s<br />\n";
                                            }
                                            echo "<hr size=1>\n";
                                            echo "(all) <b>" . nfd($sum) . "</b> msgs/s<br />\n";
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $sum = 0;
                                            foreach ($configs as $inst => $config) {
                                                $mo_q[$inst] = XPathValue("gateway/sms/received/queued", $status[$inst]);
                                                $sum += $mo_q[$inst];
                                                echo "($inst) " . nf($mo_q[$inst]) . " msgs<br />\n";
                                            }
                                            echo "<hr size=1>\n";
                                            echo "(all) ";
                                            if ($sum > $CONST_QUEUE_ERROR) {
                                                echo "<span class=red>" . nf($sum) . " msgs</span>";
                                            } else {
                                                echo nf($sum) . " msgs";
                                            }
                                            echo " <br />\n";
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $sum = 0;
                                            foreach ($configs as $inst => $config) {
                                                $mt_q[$inst] = XPathValue("gateway/sms/sent/queued", $status[$inst]);
                                                $sum += $mt_q[$inst];
                                                echo "($inst) " . nf($mt_q[$inst]) . " msgs<br />\n";
                                            }
                                            echo "<hr size=1>\n";
                                            echo "(all) ";
                                            if ($sum > $CONST_QUEUE_ERROR) {
                                                echo "<span class=red>" . nf($sum) . " msgs</span>";
                                            } else {
                                                echo nf($sum) . " msgs";
                                            }
                                            echo " <br />\n";
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--Box Connections-->
                    <div class="col-sm-12 col-md-12 col-xs-12" id="destination2">
                        <h4 class="sub-header">Box Connections</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            Instance
                                        </th>
                                        <th>
                                            Type
                                        </th>
                                        <th>
                                            ID
                                        </th>
                                        <th>
                                            IP
                                        </th>
                                        <th class="text-right">
                                            Queued (MO)
                                        </th>
                                        <th class="text-right"></th>
                                        <th>
                                            Started
                                        </th>
                                        <th>
                                            SSL
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($configs as $inst => $config) {
                                        $x = XPathValue("gateway/boxes", $status[$inst]);
                                        $x = trim($x); // the boxes number sometimes returns a few blank spaces
                                        /* drop an error in case we have no boxes connected */
                                        if (empty($x)) {
                                            echo "<tr><td>\n";
                                            echo "($inst)";
                                            echo "</td><td colspan=7 class='text-center'>\n";
                                            echo "<span class='text-danger'><b>no boxes connected to this bearerbox!</b></span> <br /> \n";
                                            echo "</td></tr>\n";
                                        } else {
                                            /* loop the boxes */
                                            $i = 0;
                                            while (($y = XPathValue("box", $x)) != "") {
                                                $i++;
                                                echo "<tr><td>\n";
                                                echo "($inst)";
                                                echo "</td><td>\n";
                                                echo "<b>" . XPathValue("type", $y) . "</b>";
                                                echo "</td><td nowrap>\n";
                                                echo XPathValue("id", $y);
                                                echo "</td><td nowrap>\n";
                                                echo XPathValue("IP", $y);
                                                echo "</td><td align=right nowrap>\n";
                                                echo "<b>" . XPathValue("queue", $y) . "</b> msgs";
                                                echo "</td><td nowrap></td>";
                                                echo "<td nowrap>\n";
                                                if (preg_match("/on-line (.*)d (.*)h (.*)m (.*)s/i", XPathValue("status", $y), $regs)) {
                                                    $ts = ($regs[1] * 24 * 60 * 60) + ($regs[2] * 60 * 60) + ($regs[3] * 60) + $regs[4];
                                                    echo date("Y-m-d H:i:s", mktime() - $ts) . ", ";
                                                    echo "uptime $regs[1]d $regs[2]h $regs[3]m $regs[4]s";
                                                }
                                                echo "</td><td nowrap>\n";
                                                echo XPathValue("ssl", $y);
                                                echo "</td></tr>\n";
                                                $a = substr($x, strpos($x, "</box>") + 6);
                                                $x = $a;
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--SMSC Connections-->
                    <div class="col-sm-12 col-md-12 col-xs-12" id="destination3">
                        <h4 class="sub-header">SMSC Connections</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-right">
                                            Links
                                        </th>
                                        <th class="text-right">
                                            Online
                                        </th>
                                        <th class="text-right">
                                            Disconnected
                                        </th>
                                        <th class="text-right">
                                            Connecting
                                        </th>
                                        <th class="text-right">
                                            Re-connecting
                                        </th>
                                        <th class="text-right">
                                            Dead
                                        </th>
                                        <th class="text-right">
                                            Unknown
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-right">
                                            <?php
                                            $sum = 0;
                                            foreach ($configs as $inst => $config) {
                                                echo "($inst) ";
                                                if (!empty($status[$inst])) {
                                                    $links[$inst] = XPathValue("gateway/smscs/count", $status[$inst]);
                                                    $sum += $links[$inst];
                                                    echo $links[$inst] . " links";
                                                } else {
                                                    echo "none";
                                                }
                                                echo "<br />\n";
                                            }
                                            echo "<hr size=1>\n";
                                            echo "(all) $sum links <br />\n";
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $sum = 0;
                                            echo "<span class=green>";
                                            foreach ($configs as $inst => $config) {
                                                echo "($inst) ";
                                                if (!empty($status[$inst])) {
                                                    $x = check_status("online", $status[$inst]);
                                                    $sum += $x;
                                                    echo ($links[$inst] == $x ? "<b>all</b> links" : "$x links");
                                                } else {
                                                    echo "none";
                                                }
                                                echo "<br />\n";
                                            }
                                            echo "<hr size=1>\n";
                                            echo "(all) $sum links <br />\n";
                                            echo "</span>\n";
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $sum = 0;
                                            foreach ($configs as $inst => $config) {
                                                $x = check_status("disconnected", $status[$inst]);
                                                $sum += $x;
                                                echo "($inst) ";
                                                if ($x == 0) {
                                                    echo "<span>none</span>";
                                                } else {
                                                    echo "<a href=\"#\" class=href onClick=\"do_alert('";
                                                    echo "smsc-ids in disconnected state are\\n\\n";
                                                    echo get_smscids("disconnected", $status[$inst]);
                                                    echo "');\"><span class=red><b>$x</b> links</span></a>";
                                                }
                                                echo "<br />\n";
                                            }
                                            echo "<hr size=1>\n";
                                            echo "(all) $sum links <br />\n";
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $sum = 0;
                                            foreach ($configs as $inst => $config) {
                                                $x = check_status("connecting", $status[$inst]);
                                                $sum += $x;
                                                echo "($inst) ";
                                                if ($x == 0) {
                                                    echo "<span>none</span>";
                                                } else {
                                                    echo "<a href=\"#\" class=href onClick=\"do_alert('";
                                                    echo "smsc-ids in connecting state are\\n\\n";
                                                    echo get_smscids("connecting", $status[$inst]);
                                                    echo "');\"><span class=red><b>$x</b> links</span></a>";
                                                }
                                                echo "<br />\n";
                                            }
                                            echo "<hr size=1>\n";
                                            echo "(all) $sum links <br />\n";
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $sum = 0;
                                            foreach ($configs as $inst => $config) {
                                                $x = check_status("re-connecting", $status[$inst]);
                                                $sum += $x;
                                                echo "($inst) ";
                                                if ($x == 0) {
                                                    echo "<span>none</span>";
                                                } else {
                                                    echo "<a href=\"#\" class=href onClick=\"do_alert('";
                                                    echo "smsc-ids in re-connecting state are\\n\\n";
                                                    echo get_smscids("re-connecting", $status[$inst]);
                                                    echo "');\"><span class=red><b>$x</b> links</span></a>";
                                                }
                                                echo "<br />\n";
                                            }
                                            echo "<hr size=1>\n";
                                            echo "(all) $sum links <br />\n";
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $sum = 0;
                                            foreach ($configs as $inst => $config) {
                                                $x = check_status("dead", $status[$inst]);
                                                $sum += $x;
                                                echo "($inst) ";
                                                if ($x == 0) {
                                                    echo "<span>none</span>";
                                                } else {
                                                    echo "<a href=\"#\" class=href onClick=\"do_alert('";
                                                    echo "smsc-ids in dead state are\\n\\n";
                                                    echo get_smscids("dead", $status[$inst]);
                                                    echo "');\"><span><b>$x</b> links</span></a>";
                                                }
                                                echo "<br />\n";
                                            }
                                            echo "<hr size=1>\n";
                                            echo "(all) $sum links <br />\n";
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $sum = 0;
                                            foreach ($configs as $inst => $config) {
                                                $x = check_status("unknown", $status[$inst]);
                                                $sum += $x;
                                                echo "($inst) ";
                                                if ($x == 0) {
                                                    echo "<span>none</span>";
                                                } else {
                                                    echo "<a href=\"#\" class=href onClick=\"do_alert('";
                                                    echo "smsc-ids in unknown state are\\n\\n";
                                                    echo get_smscids("unknown", $status[$inst]);
                                                    echo "');\"><span><b>$x</b> links</span></a>";
                                                }
                                                echo "<br />\n";
                                            }
                                            echo "<hr size=1>\n";
                                            echo "(all) $sum links <br />\n";
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--SMSC Connections Details-->
                    <div class="col-sm-12 col-md-12 col-xs-12" id="destination4">
                        <h4 class="sub-header">SMSC Connections Details</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            Instance
                                        </th>
                                        <th>
                                            SMSC-ID
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th>
                                            Started
                                        </th>
                                        <th class="text-right">
                                            Received (MO)
                                        </th>
                                        <th class="text-right">
                                            Sent (MT)
                                        </th>
                                        <th class="text-right">
                                            Failed (MT)
                                        </th>
                                        <th class="text-right">
                                            Queued (MT)
                                        </th>
                                        <th class="text-center">
                                            Admin Command
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $demo = array();
                                    
                                    foreach ($configs as $inst => $config) {
                                       /* $demo[] = $status[$inst];
                                
                                     $new_demo = implode(',', $demo);
                                     $new_demo1 = explode(',', $new_demo);
                                    $string1 = $new_demo1[151];
                                  //   echo  $string1;
                                  $d =  strpos($string1,"online");
                            echo $d;
                              */
                                         smsc_details($inst, $status[$inst]);
                                        
                                        
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/kannel/js/jqClock.js"></script>
        <script src="<?php echo base_url(); ?>Assets/kannel/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>Assets/kannel/js/kannel.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>Assets/kannel/js/holder.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/kannel/js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>