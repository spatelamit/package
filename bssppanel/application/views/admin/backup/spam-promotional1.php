<div class="page-content-title txt-center">
    <h3><i class="fa fa-cloud-download"></i> Spam Promotional SMS</h3> 
</div>

<div id="sp-tss">
    <div class="container-fluid padding15">
        <div class="row" id="spam_promotional">

            <div class="col-lg-4 scroll">
                <div class="list-group">
                    <?php
                    if ($spam_promotional) {
                        $i = 1;
                        foreach ($spam_promotional as $sms) {
                            ?>
                            <a href="javascript:void(0);" onclick="showAllSMS('pr', '<?php echo $sms['username']; ?>');">
                                <form role="form" class="tab-forms" id="validate-basic" data-parsley-validate method='post' action="javascript:void(0);">
                                    <div class="list-group-item" id="spam<?php echo $i; ?>">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5 class="pull-right">
                                                    <strong>Total Requests:</strong> <?php echo $sms['total_messages']; ?>
                                                    <?php //echo date('d F Y, h:i A', strtotime($sms['submit_date'])); ?>
                                                </h5>
                                                <h5>
                                                    <button class="btn btn-xs btn-success"><?php echo $sms['total_sms']; ?></button>
                                                    <strong>
                                                        <?php echo strtoupper($sms['username']); ?> ( <?php echo ($sms['parent_username'] == "") ? strtoupper($sms['admin_username']) : strtoupper($sms['parent_username']); ?> )
                                                    </strong>
                                                </h5>
                                            </div>
                                            <!--
                                            <div class="col-md-12">
                                                <h5 class="pull-right">
                                                    <strong>Sender Id:</strong> <span><?php echo $sms['sender_id']; ?></span>
                                                </h5>
                                                <h5><strong>ID:</strong> <?php echo $sms['campaign_uid']; ?></h5>
                                            </div>
                                            <div class="col-md-12">
                                                <h5><strong>Total Requests:</strong> <?php echo $sms['total_messages']; ?></h5>
                                            </div>
                                            -->
                                        </div>
                                        <hr/>
                                        <p>
                                            <?php echo urldecode($sms['message']); ?>
                                        </p>
                                        <hr/>
                                        <div class="row">
                                            <!--
                                            <div class="col-md-3 col-sm-3 col-xs-3">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <label class="control-labelly">Delivered Ratio</label>
                                                    <div class="input-group">
                                                        <input style="padding-right: 0px;" type="text" class="form-control" placeholder="Delivered Ratio" value="0" id="dlrd_ratio<?php echo $i; ?>" name="dlrd_ratio" />
                                                        <div class="input-group-addon">%</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-3">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <label class="control-label">Failed Ratio</label>
                                                    <div class="input-group">
                                                        <input style="padding-right: 0px;" type="text" class="form-control" placeholder="Failed Ratio" value="0" id="failed_ratio<?php echo $i; ?>" name="failed_ratio" />
                                                        <div class="input-group-addon">%</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" col-md-6 col-sm-6 col-xs-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <label class="control-label">SMSC Route</label>
                                                    <select name="smsc_id" id="smsc_id<?php echo $i; ?>" class="form-control">
                                                        <option value="-1">Select Route</option>
                                            <?php
                                            if ($promotional_smsc) {
                                                foreach ($promotional_smsc as $smsc_key => $smsc_value) {
                                                    echo "<option value=" . $smsc_value[smsc_id] . ">" . $smsc_value[user_group_name] . " (" . $smsc_value[smsc_id] . ")</option>";
                                                }
                                            }
                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                            -->
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="user_id<?php echo $i; ?>" id="user_id<?php echo $i; ?>" value="<?php echo $sms['user_id']; ?>" />
                                                <input type="hidden" name="campaign_id<?php echo $i; ?>" id="campaign_id<?php echo $i; ?>" value="<?php echo $sms['campaign_id']; ?>" />
                                                <button type="button" name="resend" id="resend" value="resend" class="btn btn-primary btn-xs"
                                                        onclick="sendPrMessage(<?php echo $i; ?>, 'resend', '<?php echo $sms['username']; ?>', 'outer');">
                                                    <i class="fa fa-external-link"></i> Resend
                                                </button>
                                                <button type="button" name="reject" id="reject" value="reject" class="btn btn-danger btn-xs"
                                                        onclick="sendPrMessage(<?php echo $i; ?>, 'reject', '<?php echo $sms['username']; ?>', 'outer');">
                                                    <i class="fa fa-ban"></i> Reject
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </a>
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
                                        <input type="hidden" id="total_pr_count" value="0" />
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

            <div class="col-lg-8 scroll">
                <div class="list-group" id="show_user_pr_sms"></div>
            </div>

        </div>
    </div>
</div>
