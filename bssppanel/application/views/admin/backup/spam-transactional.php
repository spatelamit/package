<div class="page-content-title txt-center">
    <h3><i class="fa fa-cloud-download"></i> Spam Transactional SMS</h3> 
</div>
<div id="sp-tss">
    <div class="container-fluid padding15">
        <div class="row" id="spam_transactional">
            <div class="col-lg-4 scroll">
                <div class="list-group">
                    <?php
                    if ($spam_transactional) {
                        $i = 1;
                        foreach ($spam_transactional as $sms) {
                            $condition_status_array = explode(',', $sms['condition_status']);
                            $sender_condition = $sms['sender_status'];
                            $keyword_condition = $sms['keyword_status'];
                            $number_db_condition = $sms['number_db_status'];
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
                                                <!--<h5>Status: <?php echo ($sms['black_listed']) ? "Black" : ""; ?></h5>-->
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
                                            <strong><?php echo urldecode($sms['sender_id']); ?></strong><br/>
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
                                        <input type="hidden" id="total_tr_count" value="0" />
                                        No Record Found!
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
                <div class="list-group" id="show_user_tr_sms"></div>
            </div>

        </div>
    </div>
</div>