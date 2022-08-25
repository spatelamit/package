<!-- Online Users Window Start -->
<?php


date_default_timezone_set('Asia/Kolkata');

if (isset($user_info) && $user_info['utype']) {
    if ($user_info['utype'] == 'Reseller') {
        ?>       
        <div class="chat-window col-xs-10 col-md-3 hidden-xs" id="chat_window_1" style="margin-left:10px;">
            <div class="col-xs-12 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading top-bar panel-collapsed" id="panelheading">
                        <div class="col-md-12 col-xs-12 padding0">
                            <h3 class="panel-title">
                                <a href="#" class="icon_minim">
                                    <i class="fa fa-circle active"></i> Online Users
                                </a>
                            </h3>
                        </div>
                    </div>
                    <div class="panel-body msg_container_base" id="chatbox">
                        <div class="row msg_container base_receive">
                            <div class="col-md-12 col-xs-12 padding0">
                                <?php
                                if (isset($online_users) && $online_users) {
                                    foreach ($online_users as $online_user) {
                                        $cur_date = date('d-m-Y h:i:s');
                                        $last_seen = $online_user['last_seen'];
                                        $diff_in_sec = intval(strtotime($cur_date) - strtotime($last_seen));
                                        $diff_in_min = intval($diff_in_sec / 60);
                                        $diff_in_hrs = intval($diff_in_sec / 3600);
                                        $diff_in_days = intval($diff_in_hrs / 24);
                                        ?>
                                        <div class="messages msg_receive">
                                            <p>
                                                <strong><?php echo ucwords($online_user['name']) . " (" . $online_user['username'] . ")"; ?></strong>
                                            </p>
                                            <p>
                                                <i class="fa fa-envelope"></i> <?php echo $online_user['email_address']; ?>
                                            </p>
                                            <p>
                                                <i class="fa fa-phone"></i> <?php echo $online_user['contact_number']; ?>
                                            </p>
                                            <time datetime="2009-11-13T20:00">Last Seen • 
                                                <?php
                                                if ($last_seen != "") {
                                                    if ($diff_in_sec > 0 && $diff_in_min <= 0)
                                                        echo $diff_in_sec . " secs ago";
                                                    elseif ($diff_in_min > 0 && $diff_in_hrs <= 0)
                                                        echo $diff_in_min . " mins ago";
                                                    elseif ($diff_in_hrs > 0 && $diff_in_days <= 0)
                                                        echo $diff_in_hrs . " hrs ago";
                                                    elseif ($diff_in_days > 0 && $diff_in_days <= 10)
                                                        echo $diff_in_days . " days ago";
                                                    elseif ($diff_in_days > 10)
                                                        echo $last_seen;
                                                }
                                                ?>
                                            </time>
                                        </div>
                                        <?php
                                    }
                                }else {
                                    ?>
                                    <div class="messages msg_receive">
                                        <p>
                                            <strong>No Users!</strong>
                                        </p>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- 
                    <div class="panel-footer">
                        <div class="input-group">
                            <input id="btn-input" type="text" class="form-control input-sm chat_input" placeholder="Write your message here..." />
                            <span class="input-group-btn">
                            <button class="btn btn-primary btn-sm" id="btn-chat">Send</button>
                            </span>
                        </div>
                    </div> 
                    -->
                </div>
            </div>
        </div>
        <?php
    }
}
?>

<!-- Online Users Window End -->
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/jsapi.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/tinycolor.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/user/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/user/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/bootstrap-editable.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/chat.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/user/js/jquery.simplyCountable.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/user/js/bootstrap-colorpicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/read-csv.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/user/js/validator.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/user/js/user.script.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/color-theme.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/user/js/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/bootstrap-filestyle.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/user/js/script.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/user/js/jquery.autosize.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/user/js/jquery-validations.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/jqClock.js"></script>


</body>
</html>