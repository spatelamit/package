<?php


//error_reporting(0);
$DBhostname = "localhost";
$DBusername = "mayank4t_bulksms";
$DBpassword = "bulksms";
$DBname = "mayank4t_bulksms";


try {
    $conn = new PDO("mysql:host=$DBhostname;dbname=$DBname", $DBusername, $DBpassword);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }

?>



<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
<?php
if (isset($type) && $type == 'Promotional') {
    ?>
    <div class="col-lg-4 scroll">
        <div class="list-group">
            <?php
            if (isset($spam_promotional) && $spam_promotional) {
                $i = 1;
                foreach ($spam_promotional as $sms) {
                    // Calculate Time
                    $submit_date = urldecode($sms['submit_date']);
                    $current_date = date('Y-m-d H:i:s');
                    $diff_in_sec = intval(strtotime($current_date) - strtotime($submit_date));
                    $diff_in_min = intval($diff_in_sec / 60);
                    $diff_in_hrs = intval($diff_in_sec / 3600);
                    $diff_in_days = intval($diff_in_hrs / 24);
                    ?>
                    <form role="form" class="tab-forms" id="txn_sms_request<?php echo $i; ?>" method='post' action="javascript:void(0);">
                        <div class="list-group-item">
                            <a href="javascript:void(0);" onclick="showAllSMS('pr', '<?php echo $sms['user_id']; ?>', <?php echo $sms['total_sms']; ?>);">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-6 round">
                                        <h4 class="txt-primary">
                                            <i class="fa fa-user-plus"></i> <?php echo ($sms['username']); ?>  
                                        </h4>
                                        <h5>
                                            <i class="fa fa-check-circle"></i> 
                                            <?php echo ($sms['parent_username'] == "") ? ($sms['admin_username']) : ($sms['parent_username']); ?>
                                        </h5>
                                        <h5>
                                            <?php echo ($sms['ref_username'] == "") ? "" : '<i class="fa fa-check-circle-o"></i> ' . ($sms['ref_username']); ?>
                                        </h5>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                        <span class="badge">
                                            <?php echo $sms['total_sms']; ?>
                                        </span>
                                        <h5>Numbers: <?php echo $sms['total_messages']; ?></h5>
                                        <h5>Type: <?php echo ($sms['message_category'] == 1) ? "Normal" : "Scheduled"; ?></h5>
                                        <h5>Time:
                                            <?php
                                            if ($diff_in_sec > 0 && $diff_in_min <= 0)
                                                echo "<label class='label label-primary'>" . $diff_in_sec . " Secs</label>";
                                            elseif ($diff_in_min > 0 && $diff_in_hrs <= 0)
                                                echo "<label class='label label-primary'>" . $diff_in_min . " Mins</label>";
                                            elseif ($diff_in_hrs > 0 && $diff_in_days <= 0)
                                                echo "<label class='label label-primary'>" . $diff_in_hrs . " Hrs</label>";
                                            elseif ($diff_in_days > 0 && $diff_in_days <= 10)
                                                echo "<label class='label label-primary'>" . $diff_in_days . " Days</label>";
                                            elseif ($diff_in_days > 10)
                                                echo "<label class='label label-primary'>" . $submit_date . "</label>";
                                            ?>
                                        </h5>
                                        <?php
                                        $black_listed_array = explode('|', $sms['black_listed']);
                                        if ($black_listed_array[0] || $black_listed_array[1]) {
                                            ?>
                                            <h5>
                                                <label class="label label-danger" data-toggle="tooltip" data-placement="bottom" 
                                                       title="<?php
                                                       echo ($black_listed_array[0]) ? "Keyword" : "";
                                                       echo ($black_listed_array[1]) ? " Sender Id" : "";
                                                       ?>">Black</label>
                                            </h5>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <hr>
                                <h5  class="msg-content word-break">
                                     <span  class="text-left">
                                             <strong> <i class="fa fa-level-up" aria-hidden="true"></i> &nbsp; <?php
                                $user_group_id = $sms['user_group_id'];
                                $query = "SELECT `user_group_name` FROM `user_groups` WHERE `user_group_id` = '" . $user_group_id . "'";
                                $result = mysql_query($query);
                                $row = mysql_fetch_array($result);
                                $user_group_name = $row["user_group_name"];
                                echo $user_group_name;
                                ?> </strong> </span> <br/> <br/>
                                    <span  class="text-left">
                                        <strong><?php echo urldecode($sms['sender_id']); ?></strong>
                                    </span>
                                    <span  class="pull-right">
                                        <?php echo urldecode($sms['submit_date']); ?>
                                    </span>
                                    <br/>
                                    <?php echo urldecode($sms['message']); ?>
                                </h5>
                                <hr>
                            </a>
                            <div class="row">
                                <div class="col-md-12 alignC">
                                    <input type="hidden" name="user_id" id="user_id<?php echo $i; ?>" value="<?php echo $sms['user_id']; ?>" />
                                    <input type="hidden" name="username" id="username<?php echo $i; ?>" value="<?php echo $sms['username']; ?>" />
                                    <input type="hidden" name="campaign_id" id="campaign_id<?php echo $i; ?>" value="<?php echo $sms['campaign_id']; ?>" />
                                    <input type="hidden" name="position" id="position<?php echo $i; ?>" value="outer" />
                                    <input type="hidden" name="total_sms" id="total_sms<?php echo $i; ?>" value="<?php echo $sms['total_sms']; ?>" />
                                    <button type="button" class="btn btn-inverse btn-xs" onclick="sendPrMessage(<?php echo $i; ?>, 'resend');"
                                            id="btnresend<?php echo $i; ?>" data-loading-text="Processing..." autocomplete="off">
                                        Resend
                                    </button>
                                    <button type="button" class="btn btn-danger btn-xs" onclick="sendPrMessage(<?php echo $i; ?>, 'reject');"
                                            id="btnreject<?php echo $i; ?>" data-loading-text="Processing..." autocomplete="off">
                                        Reject
                                    </button>
                                    <button type="button" class="btn btn-danger btn-xs reject_sms_outer2" data-toggle="popover"
                                            data-content="<div class='form-group'><textarea class='form-control' name='reject_sms' id='reject_sms<?php echo $i; ?>'
                                            rows='2' placeholder='Reason For Message Rejection'></textarea></div><button class='btn btn-primary btn-xs' type='button' 
                                            onclick='sendPrMessage(<?php echo $i; ?>, 3);' id='btn3<?php echo $i; ?>' data-loading-text='Processing...'
                                            autocomplete='off'>Send</button>">
                                        Reject With SMS
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    $i++;
                }
                ?>
                <input type="hidden" id="total_pr_count" value="1" />
                <?php
            } else {
                ?>
                <div class="list-group-item">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>
                                No Record Found!
                                <input type="hidden" id="total_pr_count" value="0" />
                            </h5>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="list-group" id="show_user_pr_sms"></div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.tab-forms').parsley();
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });
            $('.reject_sms_outer2').popover({
                placement: 'top',
                animation: true,
                container: '#spam_promotional',
                html: true,
                selector: false
            });
            $('.reject_sms_outer2').click(function () {
                $('.reject_sms_outer2').not(this).popover('hide'); //all but this
            });
        });
    </script>
    <?php
} elseif (isset($type) && $type == 'Transactional') {
    ?>
    <div class="col-lg-4 scroll">
        <div class="list-group">
            <?php
            if (isset($spam_transactional) && $spam_transactional) {
                $i = 1;
                foreach ($spam_transactional as $sms) {
                    $condition_status_array = explode(',', $sms['condition_status']);
                    $sender_condition = $sms['sender_status'];
                    $keyword_condition = $sms['keyword_status'];
                    $number_db_condition = $sms['number_db_status'];
                    // Calculate Time
                    $submit_date = urldecode($sms['submit_date']);
                    $current_date = date('Y-m-d H:i:s');
                    $diff_in_sec = intval(strtotime($current_date) - strtotime($submit_date));
                    $diff_in_min = intval($diff_in_sec / 60);
                    $diff_in_hrs = intval($diff_in_sec / 3600);
                    $diff_in_days = intval($diff_in_hrs / 24);
                    ?>
                    <form role="form" class="tab-forms" id="txn_sms_request<?php echo $i; ?>" method='post' action="javascript:void(0);">
                        <div class="list-group-item">
                            <a href="javascript:void(0);" onclick="showAllSMS('tr', '<?php echo $sms['user_id']; ?>', <?php echo $sms['total_sms']; ?>);">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-6 round">
                                        <h4 class="txt-primary">
                                            <i class="fa fa-user-plus"></i> <?php echo ($sms['username']); ?>  
                                        </h4>
                                        <h5>
                                            <i class="fa fa-check-circle"></i> 
                                            <?php echo ($sms['parent_username'] == "") ? ($sms['admin_username']) : ($sms['parent_username']); ?>
                                        </h5>
                                        <h5>
                                            <?php echo ($sms['ref_username'] == "") ? "" : '<i class="fa fa-check-circle-o"></i> ' . ($sms['ref_username']); ?>
                                        </h5>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                        <span class="badge">
                                            <?php echo $sms['total_sms']; ?>
                                        </span>
                                        <h5>Numbers: <?php echo $sms['total_messages']; ?></h5>
                                        <h5>Type: <?php echo ($sms['message_category'] == 1) ? "Normal" : "Scheduled"; ?></h5>
                                        <h5>Time:
                                            <?php
                                            if ($diff_in_sec > 0 && $diff_in_min <= 0)
                                                echo "<label class='label label-primary'>" . $diff_in_sec . " Secs</label>";
                                            elseif ($diff_in_min > 0 && $diff_in_hrs <= 0)
                                                echo "<label class='label label-primary'>" . $diff_in_min . " Mins</label>";
                                            elseif ($diff_in_hrs > 0 && $diff_in_days <= 0)
                                                echo "<label class='label label-primary'>" . $diff_in_hrs . " Hrs</label>";
                                            elseif ($diff_in_days > 0 && $diff_in_days <= 10)
                                                echo "<label class='label label-primary'>" . $diff_in_days . " Days</label>";
                                            elseif ($diff_in_days > 10)
                                                echo "<label class='label label-primary'>" . $submit_date . "</label>";
                                            ?>
                                        </h5>
                                        
                                        <?php
                                        $black_listed_array = explode('|', $sms['black_listed']);
                                        if ($black_listed_array[0] || $black_listed_array[1]) {
                                            ?>
                                            <h5>
                                                <label class="label label-danger" data-toggle="tooltip" data-placement="bottom" 
                                                       title="<?php
                                                       echo (sizeof($black_listed_array) == 1 && $black_listed_array[0]) ? "Keyword" : "";
                                                       echo (sizeof($black_listed_array) == 2 && $black_listed_array[1]) ? " Sender Id" : "";
                                                       ?>">Black</label>
                                            </h5>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <hr>
                                <h5  class="msg-content word-break">
                                     <span  class="text-left">
                                         <strong>  <i class="fa fa-level-up" aria-hidden="true"></i> &nbsp;<?php
                                $user_group_id = $sms['user_group_id'];
                                $query = "SELECT `user_group_name` FROM `user_groups` WHERE `user_group_id` = '" . $user_group_id . "'";
                                $result = mysql_query($query);
                                $row = mysql_fetch_array($result);
                                $user_group_name = $row["user_group_name"];
                                echo $user_group_name;
                                ?> </strong> </span> <br/> <br/>
                                    <span  class="text-left">
                                        <strong><?php echo urldecode($sms['sender_id']); ?></strong>
                                    </span>
                                    <span  class="pull-right">
                                        <?php echo urldecode($sms['submit_date']); ?>
                                    </span>
                                    <br/>
                                    <?php echo urldecode($sms['message']); ?>
                                </h5>
                                <hr>
                            </a>
                            <div class="row">
                                <div class="col-md-12 alignC">
                                    <input type="hidden" name="user_id" id="user_id<?php echo $i; ?>" value="<?php echo $sms['user_id']; ?>" />
                                    <input type="hidden" name="username" id="username<?php echo $i; ?>" value="<?php echo $sms['username']; ?>" />
                                    <input type="hidden" name="campaign_id" id="campaign_id<?php echo $i; ?>" value="<?php echo $sms['campaign_id']; ?>" />
                                    <input type="hidden" name="position" id="position<?php echo $i; ?>" value="outer" />
                                    <input type="hidden" name="total_sms" id="total_sms<?php echo $i; ?>" value="<?php echo $sms['total_sms']; ?>" />
                                    <button type="button" class="btn btn-inverse btn-xs" onclick="sendTrMessage(<?php echo $i; ?>, 'resend');"
                                            id="btnresend<?php echo $i; ?>" data-loading-text="Processing..." autocomplete="off">
                                        Approve Without Save
                                    </button>
                                    <button type="button" class="btn btn-danger btn-xs" onclick="sendTrMessage(<?php echo $i; ?>, 'reject');"
                                            id="btnreject<?php echo $i; ?>" data-loading-text="Processing..." autocomplete="off">
                                        Reject
                                    </button>
                                    <button type="button" class="btn btn-danger btn-xs reject_sms_outer1" data-toggle="popover"
                                            data-content="<div class='form-group'><textarea class='form-control' name='reject_sms' id='reject_sms<?php echo $i; ?>'
                                            rows='2' placeholder='Reason For Message Rejection'></textarea></div><button class='btn btn-primary btn-xs' type='button' 
                                            onclick='sendTrMessage(<?php echo $i; ?>, 3);' id='btn3<?php echo $i; ?>' data-loading-text='Processing...'
                                            autocomplete='off'>Send</button>">
                                        Reject With SMS
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    $i++;
                }
                ?>
                <input type="hidden" id="total_tr_count" value="<?php echo sizeof($spam_transactional); ?>" />
                <?php
            } else {
                ?>
                <div class="list-group-item">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>
                                No Record Found!
                                <input type="hidden" id="total_tr_count" value="0" />
                            </h5>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="col-lg-8 scroll">
        <div class="list-group" id="show_user_tr_sms"></div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.tab-forms').parsley();
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });
            $('.reject_sms_outer1').popover({
                placement: 'top',
                animation: true,
                container: '#spam_transactional',
                html: true,
                selector: false
            });
            $('.reject_sms_outer1').click(function () {
                $('.reject_sms_outer1').not(this).popover('hide'); //all but this
            });
        });
    </script>
    <?php
}
?>