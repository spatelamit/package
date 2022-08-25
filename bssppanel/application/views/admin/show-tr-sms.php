<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
<?php
if (isset($total_sms) && $total_sms > 1) {
    ?>
    <form role="form" class="tab-forms" id="txn_sms_request" method='post' action="javascript:void(0);">
        <div class="list-group-item">
            <span class="pull-right">
                <a href="javascript:void(0);" id="fa-close" onclick="ajaxTrSpam();">
                    <i class="fa fa-times-circle"></i>
                </a>
            </span>
            <ul class="list-inline">
                <li>
                    <h4>
                        <i class="fa fa-user-plus"></i> <?php echo ($user['username']); ?> 
                    </h4>
                </li>
                <li>
                    <i class="fa fa-check-circle"></i>
                    <?php echo ($user['parent_username'] == "") ? ($user['admin_username']) : ($user['parent_username']); ?>
                </li>
                <li>
                    <?php echo ($user['ref_username'] == "") ? "" : '<i class="fa fa-check-circle-o"></i> ' . ($user['ref_username']); ?>
                </li>
            </ul>
            <hr/>
            <div class="row">
                <div class="col-md-2 col-sm-4 col-xs-6">
                    <div class="form-group has-feedback has-feedback-left">
                        <label class="control-labelly">Database</label>
                        <div class="input-group">
                            <input style="padding-right: 0px;" type="text" class="form-control" placeholder="Numbers Allowed" value="<?php echo $user['number_allowed']; ?>"
                                   id="database_limit" name="database_limit" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="updateUserDB(<?php echo $user['user_id']; ?>)">
                                    <i class="fa fa-check"></i> 
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6">
                    <div class="form-group has-feedback has-feedback-left">
                        <label class="control-labelly">Keyword Ratio</label>
                        <div class="input-group">
                            <input style="padding-right: 0px;" type="text" class="form-control" placeholder="Keyword Ratio" value="0" 
                                   id="all_keyword_ratio" name="keyword_ratio" />
                            <div class="input-group-addon">%</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6">
                    <div class="form-group has-feedback has-feedback-left">
                        <label class="control-labelly">Delivery Ratio</label>
                        <div class="input-group">
                            <input style="padding-right: 0px;" type="text" class="form-control" placeholder="Delivered Ratio" value="0" 
                                   id="all_fake_delivered_ratio" name="fake_delivered_ratio" />
                            <div class="input-group-addon">%</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6">
                    <div class="form-group has-feedback has-feedback-left">
                        <label class="control-labelly">Fail Ratio</label>
                        <div class="input-group">
                            <input style="padding-right: 0px;" type="text" class="form-control" placeholder="Failed Ratio" value="0" 
                                   id="all_fake_failed_ratio" name="fake_failed_ratio" />
                            <div class="input-group-addon">%</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-8 col-xs-12">
                    <div class="form-group has-feedback has-feedback-left">
                        <label class="control-label">SMSC Route</label>
                        <select name="all_smsc_route_id" id="smsc_route_id" class="form-control">
                            <option value="">Select Route</option>
                            <?php
                            if ($transactional_smsc) {
                                foreach ($transactional_smsc as $smsc_key => $smsc_value) {
                                    ?>
                                    <option value="<?php echo $smsc_value['smsc_id']; ?>"><?php echo $smsc_value['user_group_name'] . " (" . $smsc_value['smsc_id'] . ")"; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7 alignC">
                    <input type="hidden" name="all_user_id" id="all_user_id" value="<?php echo $user['user_id']; ?>" />
                    <input type="hidden" name="all_username" id="all_username" value="<?php echo $user['username']; ?>" />
                    <input type="hidden" name="total_sms" id="total_sms" value="<?php echo $total_sms; ?>" />
                    <button type="button" class="btn btn-inverse btn-xs" onclick="sendAllTrMessage('resend');"
                            id="btnresend" data-loading-text="Processing..." autocomplete="off">
                        Approve Without Save
                    </button>
                    <button type="button" class="btn btn-inverse btn-xs" onclick="sendAllTrMessage('approve');"
                            id="btnapprove" data-loading-text="Processing..." autocomplete="off">
                        Approve With Save
                    </button>
                    <button type="button" class="btn btn-inverse btn-xs" onclick="sendAllTrMessage('approve_all');"
                            id="btnapprove_all" data-loading-text="Processing..." autocomplete="off">
                        Approve To All
                    </button>
                </div>
                <div class="col-md-5 text-right alignC">
                    <button type="button" class="btn btn-danger btn-xs" onclick="sendAllTrMessage('reject');">
                        Reject
                    </button>
                    <button type="button" class="btn btn-danger btn-xs reject_sms_inner" data-toggle="popover" data-container="#show_user_tr_sms"
                            data-content="<div class='form-group'><textarea class='form-control' name='reject_sms' id='all_reject_sms'
                            rows='2' placeholder='Reason For Message Rejection'></textarea></div><button class='btn btn-primary btn-xs' type='button' 
                            onclick='sendAllTrMessage(3);' id='btn3' data-loading-text='Processing...'
                            autocomplete='off'>Send</button>">
                        Reject With SMS
                    </button>
                </div>
            </div>
        </div>
    </form>
    <br/>
    <?php
    if (isset($spam_transactional) && $spam_transactional) {
        ?>
        <div id="show_all_transactionals">
            <?php
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
                <form role="form" class="tab-forms" id="txn_sms_request<?php echo $sms['user_id'] . "" . $i; ?>" method='post' action="javascript:void(0);">
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-md-3">
                                <?php if ($sender_condition) { ?>
                                    <label>Sender</label><br/>
                                    <strong class="text-success"><?php echo $sms['sender_id']; ?></strong>
                                <?php } else { ?>
                                    <label>Sender</label><br/>
                                    <strong class="text-danger"><?php echo $sms['sender_id']; ?></strong> | 
                                    <button class="btn btn-success btn-xs approve-sender"
                                            onclick="approveSender('<?php echo $sms['sender_id']; ?>', '<?php echo $sms['user_id']; ?>', <?php echo $total_sms; ?>)">
                                        Approve
                                    </button>
                                <?php } ?>
                                <br/>
                                <strong class="text-right"><?php echo urldecode($sms['submit_date']); ?></strong>
                            </div>
                            <div class="col-md-6 border word-break">
                                <p>
                                    <?php echo urldecode($sms['message']); ?>
                                </p>
                                <?php
                                if ($sms['is_custom']) {
                                    ?>
                                    <p>
                                        <button type="button" class="btn btn-info btn-xs detail-message" data-toggle="modal" data-target=".bs-example-modal-sm"
                                                id="<?php echo $sms['campaign_id']; ?>">
                                            View All
                                        </button>
                                    </p>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="col-md-3">
                                <label>Time:
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
                                </label><br/>
                                <label>Numbers: </label><?php echo $sms['total_messages']; ?><br/>
                                <label>Type: </label><?php echo ($sms['message_category'] == 1) ? "Normal" : "Scheduled"; ?><br/>
                                <label>Exceed Numbers: </label><?php echo ($sms['exceed_numbers'] > 0) ? "<strong class='text-danger'>" . $sms['exceed_numbers'] . "</strong>" : "0"; ?><br/>
                                <?php
                                $black_listed_array = explode('|', $sms['black_listed']);
                                if ($black_listed_array[0] || $black_listed_array[1]) {
                                    ?>
                                    <label class="label label-danger" data-toggle="tooltip" data-placement="bottom" 
                                           title="<?php
                                           echo ($black_listed_array[0]) ? "Keyword" : "";
                                           echo ($black_listed_array[1]) ? " Sender Id" : "";
                                           ?>">Black</label>
                                           <?php
                                       }
                                       ?>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <div class="form-group has-feedback has-feedback-left">
                                    <label class="control-labelly">Keyword Ratio</label>
                                    <div class="input-group">
                                        <input style="padding-right: 0px;" type="text" class="form-control" placeholder="Keyword Ratio" value="0" 
                                               id="ratio<?php echo $sms['user_id'] . "" . $i; ?>" name="ratio" />
                                        <div class="input-group-addon">%</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <div class="form-group has-feedback has-feedback-left">
                                    <label class="control-labelly">Delivery Ratio</label>
                                    <div class="input-group">
                                        <input style="padding-right: 0px;" type="text" class="form-control" placeholder="Delivered Ratio" value="0" 
                                               id="dlrd_ratio<?php echo $sms['user_id'] . "" . $i; ?>" name="dlrd_ratio" />
                                        <div class="input-group-addon">%</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <div class="form-group has-feedback has-feedback-left">
                                    <label class="control-labelly">Fail Ratio</label>
                                    <div class="input-group">
                                        <input style="padding-right: 0px;" type="text" class="form-control" placeholder="Failed Ratio" value="0" 
                                               id="failed<?php echo $sms['user_id'] . "" . $i; ?>" name="failed" />
                                        <div class="input-group-addon">%</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-6">
                                <div class="form-group has-feedback has-feedback-left">
                                    <label class="control-label">SMSC Route</label>
                                    <select name="smsc_id" id="smsc_id<?php echo $sms['user_id'] . "" . $i; ?>" class="form-control">
                                        <option value="">Select Route</option>
                                        <?php
                                        if ($transactional_smsc) {
                                            foreach ($transactional_smsc as $smsc_key => $smsc_value) {
                                                ?>
                                                <option value="<?php echo $smsc_value['smsc_id']; ?>"><?php echo $smsc_value['user_group_name'] . " (" . $smsc_value['smsc_id'] . ")"; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7 alignC">
                                <input type="hidden" name="user_id" id="user_id<?php echo $sms['user_id'] . "" . $i; ?>" value="<?php echo $sms['user_id']; ?>" />
                                <input type="hidden" name="username" id="username<?php echo $sms['user_id'] . "" . $i; ?>" value="<?php echo $sms['username']; ?>" />
                                <input type="hidden" name="campaign_id" id="campaign_id<?php echo $sms['user_id'] . "" . $i; ?>" value="<?php echo $sms['campaign_id']; ?>" />
                                <input type="hidden" name="position" id="position<?php echo $sms['user_id'] . "" . $i; ?>" value="inner" />
                                <button type="button" class="btn btn-primary btn-xs" onclick="sendTrMessage(<?php echo $sms['user_id'] . "" . $i; ?>, 'resend');"
                                        id="btnresend<?php echo $sms['user_id'] . "" . $i; ?>" data-loading-text="Processing..." autocomplete="off">
                                    Approve Without Save
                                </button>
                                <button type="button" class="btn btn-primary btn-xs" onclick="sendTrMessage(<?php echo $sms['user_id'] . "" . $i; ?>, 'approve');"
                                        id="btnapprove<?php echo $sms['user_id'] . "" . $i; ?>" data-loading-text="Processing..." autocomplete="off">
                                    Approve With Save
                                </button>
                                <button type="button" class="btn btn-primary btn-xs" onclick="sendTrMessage(<?php echo $sms['user_id'] . "" . $i; ?>, 'approve_all');"
                                        id="btnapprove_all<?php echo $sms['user_id'] . "" . $i; ?>" data-loading-text="Processing..." autocomplete="off">
                                    Approve To All
                                </button>
                            </div>
                            <div class="col-md-5 alignC text-right">
                                <button type="button" class="btn btn-danger btn-xs" onclick="sendTrMessage(<?php echo $i; ?>, 'reject');">
                                    Reject
                                </button>
                                <button type="button" class="btn btn-danger btn-xs reject_sms_inner" data-toggle="popover"
                                        data-content="<div class='form-group'><textarea class='form-control' name='reject_sms' id='reject_sms<?php echo $sms['user_id'] . "" . $i; ?>'
                                        rows='2' placeholder='Reason For Message Rejection'></textarea></div><button class='btn btn-primary btn-xs' type='button' 
                                        onclick='sendTrMessage(<?php echo $sms['user_id'] . "" . $i; ?>, 3);' id='btn3<?php echo $sms['user_id'] . "" . $i; ?>' data-loading-text='Processing...'
                                        autocomplete='off'>Send</button>" data-container="false">
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
        </div>            
        <?php
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.tab-forms').parsley();
            $('.reject_sms_inner').popover({
                placement: 'top',
                animation: true,
                html: true,
                selector: false
            });
            $('.reject_sms_inner').click(function () {
                $('.reject_sms_inner').not(this).popover('hide');
            });
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });
        });
    </script>
    <?php
} elseif (isset($total_sms) && $total_sms == 1) {
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
            <form role="form" class="tab-forms" id="txn_sms_request" method='post' action="javascript:void(0);">
                <div class="list-group-item">
                    <span class="pull-right">
                        <a href="javascript:void(0);" id="fa-close" onclick="ajaxTrSpam();">
                            <i class="fa fa-times-circle"></i>
                        </a>
                    </span>
                    <ul class="list-inline">
                        <li>
                            <h4>
                                <i class="fa fa-user-plus"></i> <?php echo ($sms['username']); ?> 
                            </h4>
                        </li>
                        <li>
                            <i class="fa fa-check-circle"></i>
                            <?php echo ($sms['parent_username'] == "") ? ($sms['admin_username']) : ($sms['parent_username']); ?>
                        </li>
                        <li>
                            <?php echo ($sms['ref_username'] == "") ? "" : '<i class="fa fa-check-circle-o"></i> ' . ($sms['ref_username']); ?>
                        </li>
                    </ul>
                    <hr/>
                    <div class="row">
                        <div class="col-md-3">
                            <?php if ($sender_condition) { ?>
                                <label>Sender</label><br/>
                                <strong class="text-success"><?php echo $sms['sender_id']; ?></strong>
                            <?php } else { ?>
                                <label>Sender</label><br/>
                                <strong class="text-danger"><?php echo $sms['sender_id']; ?></strong> | 
                                <button class="btn btn-success btn-xs approve-sender"
                                        onclick="approveSender('<?php echo $sms['sender_id']; ?>', '<?php echo $sms['user_id']; ?>', <?php echo $total_sms; ?>)">
                                    Approve
                                </button>
                            <?php } ?>
                            <br/>
                            <strong class="text-right"><?php echo urldecode($sms['submit_date']); ?></strong>
                        </div>
                        <div class="col-md-6 border word-break">
                            <p>
                                <?php echo urldecode($sms['message']); ?>
                            </p>
                            <?php
                            if ($sms['is_custom']) {
                                ?>
                                <p>
                                    <button type="button" class="btn btn-info btn-xs detail-message" data-toggle="modal" data-target=".bs-example-modal-sm"
                                            id="<?php echo $sms['campaign_id']; ?>">
                                        View All
                                    </button>
                                </p>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="col-md-3">
                            <label>Time:
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
                            </label><br/>
                            <label>Numbers: </label><?php echo $sms['total_messages']; ?><br/>
                            <label>Type: </label><?php echo ($sms['message_category'] == 1) ? "Normal" : "Scheduled"; ?><br/>
                            <label>Exceed Numbers: </label><?php echo ($sms['exceed_numbers'] > 0) ? "<strong class='text-danger'>" . $sms['exceed_numbers'] . "</strong>" : "0"; ?><br/>
                            <?php
                            $black_listed_array = explode('|', $sms['black_listed']);
                            if ($black_listed_array[0] || $black_listed_array[1]) {
                                ?>
                                <label class="label label-danger" data-toggle="tooltip" data-placement="bottom" 
                                       title="<?php
                                       echo (sizeof($black_listed_array) == 1 && $black_listed_array[0]) ? "Keyword" : "";
                                       echo (sizeof($black_listed_array) == 2 && $black_listed_array[1]) ? " Sender Id" : "";
                                       ?>">Black</label>
                                       <?php
                                   }
                                   ?>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <label class="control-labelly">Database</label>
                                <div class="input-group">
                                    <input style="padding-right: 0px;" type="text" class="form-control" placeholder="Numbers Allowed" value="<?php echo $user['number_allowed']; ?>"
                                           id="database_limit" name="database_limit" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" onclick="updateUserDB(<?php echo $user['user_id']; ?>)">
                                            <i class="fa fa-check"></i> 
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <label class="control-labelly">Keyword Ratio</label>
                                <div class="input-group">
                                    <input style="padding-right: 0px;" type="text" class="form-control" placeholder="Keyword Ratio" value="0" 
                                           id="keyword_ratio" name="keyword_ratio" />
                                    <div class="input-group-addon">%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <label class="control-labelly">Delivery Ratio</label>
                                <div class="input-group">
                                    <input style="padding-right: 0px;" type="text" class="form-control" placeholder="Delivered Ratio" value="0" 
                                           id="fake_delivered_ratio" name="fake_delivered_ratio" />
                                    <div class="input-group-addon">%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <label class="control-labelly">Fail Ratio</label>
                                <div class="input-group">
                                    <input style="padding-right: 0px;" type="text" class="form-control" placeholder="Failed Ratio" value="0" 
                                           id="fake_failed_ratio" name="fake_failed_ratio" />
                                    <div class="input-group-addon">%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-8 col-xs-12">
                            <div class="form-group has-feedback has-feedback-left">
                                <label class="control-label">SMSC Route</label>
                                <select name="smsc_route_id" id="smsc_route_id" class="form-control">
                                    <option value="">Select Route</option>
                                    <?php
                                    if ($transactional_smsc) {
                                        foreach ($transactional_smsc as $smsc_key => $smsc_value) {
                                            ?>
                                            <option value="<?php echo $smsc_value['smsc_id']; ?>"><?php echo $smsc_value['user_group_name'] . " (" . $smsc_value['smsc_id'] . ")"; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 alignC">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo $sms['user_id']; ?>" />
                            <input type="hidden" name="username" id="username" value="<?php echo $sms['username']; ?>" />
                            <input type="hidden" name="campaign_id" id="campaign_id" value="<?php echo $sms['campaign_id']; ?>" />
                            <input type="hidden" name="position" id="position" value="inner" />
                            <input type="hidden" name="total_sms" id="total_sms" value="<?php echo $total_sms; ?>" />
                            <button type="button" class="btn btn-inverse btn-xs" onclick="sendTrMessage('', 'resend');"
                                    id="btnresend" data-loading-text="Processing..." autocomplete="off">
                                Approve Without Save
                            </button>
                            <button type="button" class="btn btn-inverse btn-xs" onclick="sendTrMessage('', 'approve');"
                                    id="btnapprove" data-loading-text="Processing..." autocomplete="off">
                                Approve With Save
                            </button>
                            <button type="button" class="btn btn-inverse btn-xs" onclick="sendTrMessage('', 'approve_all');"
                                    id="btnapprove_all" data-loading-text="Processing..." autocomplete="off">
                                Approve To All
                            </button>
                        </div>
                        <div class="col-md-5 text-right alignC">
                            <button type="button" class="btn btn-danger btn-xs" onclick="sendTrMessage('', 'reject');"
                                    id="btnreject" data-loading-text="Processing..." autocomplete="off">
                                Reject
                            </button>
                            <button type="button" class="btn btn-danger btn-xs reject_sms_inner" data-toggle="popover"
                                    data-content="<div class='form-group'><textarea class='form-control' name='reject_sms' id='reject_sms'
                                    rows='2' placeholder='Reason For Message Rejection'></textarea></div><button class='btn btn-primary btn-xs' type='button' 
                                    onclick='sendTrMessage(null, 3);' id='btn3' data-loading-text='Processing...'
                                    autocomplete='off'>Send</button>" data-container="#show_user_tr_sms">
                                Reject With SMS
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <?php
            $i++;
        }
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.tab-forms').parsley();
            $('.reject_sms_inner').popover({
                placement: 'top',
                animation: true,
                html: true,
                selector: false
            });
            $('.reject_sms_inner').click(function () {
                $('.reject_sms_inner').not(this).popover('hide'); //all but this
            });
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });
        });
    </script>
    <?php
}
?>

<!--Modal Box For Multiple Messages Start-->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Multiple Messages</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive" id="show_all_messages" style="height: 430px; overflow-y: auto;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--Modal Box For Multiple Messages End-->