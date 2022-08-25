<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />
<?php
if (isset($tab) && $tab == 'settings') {
    ?>
    <div class="row">
        <div class="col-sm-3">
            <div class="portlet">
                <h2 class="content-header-title">Signup Notification</h2>
                <div class="portlet-content">
                    <form role="form" class="tab-forms" id="notificationForm" method='post' action="javascript:saveResellerSetting(5, '<?php echo $tab; ?>');">
                        <div class="row">
                            <div class="form-group col-md-12 padding0">
                                <label for="signup_notification">
                                    <input type="checkbox" name="signup_notification" id="signup_notification" value="1" 
                                           <?php echo (isset($signup_notification) && $signup_notification) ? "checked" : ""; ?> />
                                    Yes, I want Email Notification (<?php echo (isset($email_address) && $email_address) ? $email_address : ""; ?>)
                                </label>
                            </div>
                            <div class="form-group col-md-12 mt5 padding0">
                                <button type="submit" class="btn btn-primary"
                                        id="btnreseller5" data-loading-text="Processing..." autocomplete="off">Save Setting</button>
                            </div>
                        </div>
                    </form>
                </div> 
            </div> 
        </div>
    </div>
    <?php
}

if (isset($tab) && $tab == 'signup_sms') {
    ?>
    <div class="row">
        <div class="col-sm-6">
            <div class="portlet">
                <h2 class="content-header-title">Signup SMS</h2>
                <div class="portlet-content">
                    <div class="row">
                        <div class="col-md-5 padding0">
                            <form role="form" class="tab-forms" id="resellerSSMSForm" method='post' action="javascript:saveResellerSetting(1, '<?php echo $tab; ?>');">
                                <div class="form-group col-md-12 padding0">
                                    <label for="signup_sender_id">Sender ID</label>
                                    <input type="text" id="signup_sender_id" name="signup_sender_id" class="form-control" placeholder="Sender ID"
                                           data-parsley-minlength="6" data-parsley-maxlength="6" data-parsley-minlength-message="Sender Id must be 6 characters long"
                                           data-parsley-maxlength-message="Sender Id must be 6 characters long"
                                           value="<?php echo (isset($signup_sender) && $signup_sender) ? $signup_sender : ""; ?>" />
                                </div>
                                <div class="form-group col-md-12 padding0">
                                    <label for="signup_message">Message</label>
                                    <textarea id="signup_message" name="signup_message" rows="3" class="form-control" placeholder="Message"><?php echo (isset($signup_message) && $signup_message) ? $signup_message : ""; ?></textarea>
                                </div>
                                <div class="form-group col-md-12 padding0">
                                    <input type="submit" class="btn btn-primary" value="Save Setting"
                                           id="btnreseller1" data-loading-text="Processing..." autocomplete="off" />
                                </div>
                            </form>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-5">
                            <div class="form-group col-md-12 padding0">
                                <h4>Example</h4>
                                <p>
                                    Hello <span class="text-danger">##fname##</span>,
                                    Thank you for registering.
                                    Please login with following details.
                                    URL: <span class="text-danger">##url##</span>;
                                    Username: <span class="text-danger">##username##</span>;
                                    Password: <span class="text-danger">##password##</span>
                                </p>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <h4>Dynamic Variables</h4>
                                <p>
                                    For First Name: <span class="text-danger">##fname##</span><br/>
                                    For Username: <span class="text-danger">##username##</span><br/>
                                    For Password: <span class="text-danger">##password##</span><br/>
                                    For Url: <span class="text-danger">##url##</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
    <?php
}

if (isset($tab) && $tab == 'signup_mail') {
    ?>
    <div class="row">
        <div class="col-sm-6">
            <div class="portlet">
                <h2 class="content-header-title">Signup Mail</h2>
                <div class="portlet-content">
                    <div class="row">
                        <div class="col-md-5 padding0">
                            <form role="form" class="tab-forms" id="resellerSMailForm" method='post' action="javascript:saveResellerSetting(2, '<?php echo $tab; ?>');">
                                <div class="form-group col-md-12 padding0">
                                    <label for="signup_subject">Subject</label>
                                    <input type="text" id="signup_subject" name="signup_subject" class="form-control" required="" value="<?php echo (isset($signup_subject) && $signup_subject) ? $signup_subject : ""; ?>" placeholder="Subject" />
                                </div>
                                <div class="form-group col-md-12 padding0">
                                    <label for="signup_body">Message Body</label>
                                    <textarea id="signup_body" name="signup_body" rows="3" class="form-control" placeholder="Message"><?php echo (isset($signup_body) && $signup_body) ? $signup_body : ""; ?></textarea>
                                </div>
                                <div class="form-group col-md-12 padding0">
                                    <input type="submit" class="btn btn-primary" value="Save Setting"
                                           id="btnreseller2" data-loading-text="Processing..." autocomplete="off" />
                                </div>
                            </form>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-5">
                            <div class="form-group col-md-12 padding0">
                                <h4>Dynamic Variables</h4>
                                <p>
                                    For First Name: <span class="text-danger">##fname##</span>
                                    For Username: <span class="text-danger">##username##</span>
                                    For Password: <span class="text-danger">##password##</span>
                                    For Url: <span class="text-danger">##url##</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
        </div>
    </div>
    <?php
}

if (isset($tab) && $tab == 'demo_sms') {
    ?>
    <div class="row">
        <div class="col-sm-3">
            <div class="portlet">
                <h2 class="content-header-title">Demo SMS</h2>
                <div class="portlet-content">
                    <form role="form" class="tab-forms" id="resellerDemoForm" method='post' action="javascript:saveResellerSetting(4, '<?php echo $tab; ?>');">
                        <div class="form-group col-md-12 padding0">
                            <label for="demo_sender">Sender ID</label>
                            <input type="text" id="demo_sender" name="demo_sender" class="form-control" placeholder="Sender ID"
                                   data-parsley-minlength="6" data-parsley-maxlength="6" data-parsley-minlength-message="Sender Id must be 6 characters long"
                                   data-parsley-maxlength-message="Sender Id must be 6 characters long"
                                   value="<?php echo (isset($demo_sender) && $demo_sender) ? $demo_sender : ""; ?>" />
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <label for="demo_message">Message</label>
                            <textarea id="demo_message" name="demo_message" rows="3" class="form-control" placeholder="Message"><?php echo (isset($demo_message) && $demo_message) ? $demo_message : ""; ?></textarea>
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <input type="submit" class="btn btn-primary" value="Save Setting"
                                   id="btnreseller4" data-loading-text="Processing..." autocomplete="off" />
                        </div>
                    </form>
                </div> 
            </div> 
        </div>
    </div>
    <?php
}

if (isset($tab) && $tab == 'expiry_balance') {
    ?>
    <div class="row">
        <div class="col-sm-3">
            <div class="portlet">
                <h2 class="content-header-title">Expiry & Demo Balance</h2>
                <div class="portlet-content">
                    <form role="form" class="tab-forms" id="resellerOtherForm" method='post' action="javascript:saveResellerSetting(3, '<?php echo $tab; ?>');">
                        <div class="row">
                            <div class="form-group col-md-6 padding0">
                                <label for="expiry_days">Expiry</label>
                                <div class="input-group">
                                    <input type="text" id="expiry_days" name="expiry_days" class="form-control" placeholder="Expiry Days"
                                           data-parsley-type="integer" data-parsley-type-message="Expiry days must be a number"
                                           value="<?php echo (isset($expiry_days) && $expiry_days) ? $expiry_days : "0"; ?>" /> 
                                    <div class="input-group-addon">Days</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 padding0">
                                <label for="demo_balance">Demo Balance</label>
                                <div class="input-group">
                                    <input type="text" id="demo_balance" name="demo_balance" class="form-control" placeholder="Demo Balance"
                                           data-parsley-type="integer" data-parsley-type-message="Balance must be a number"
                                           value="<?php echo (isset($demo_balance) && $demo_balance) ? $demo_balance : "1"; ?>" />
                                    <div class="input-group-addon">SMS</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 padding0">
                                <input type="submit" class="btn btn-primary" value="Save Setting"
                                       id="btnreseller3" data-loading-text="Processing..." autocomplete="off" />
                            </div>
                        </div>
                    </form>
                </div> 
            </div> 
        </div>
    </div>
    <?php
}
?>
<script type="text/javascript">
    $('.tab-forms').parsley();
</script>