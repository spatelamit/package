<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/jquerysctipttop.css" />
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/rotator.js"></script>
<style>
    #my_div {
        display:none;
    }
</style>
<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />

<form id="sendSMSForms" action="javascript:void(0);" method="post" enctype="multipart/form-data">
    <div class="col-md-3 col-sm-5 borderR">
        <div class="portlet">
            <div class="row">
                <div class="col-md-12 content-header-title fhead">
                    <div class="col-xs-4 col-md-5 padding0">Send SMS</div>
                    <div class="col-md-7 btns-route text-right padding0" id="show_change_route">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <?php echo (isset($user_info) && $user_info['default_route'] && $user_info['default_route'] == 'A') ? "Promotional" : "Transactional"; ?> Route <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                <li>
                                    <a href="javascript:void(0)" onclick="changeRoute('A');" data-toggle="tooltip"  data-container="body" data-placement="right" 
                                       animation="true" delay="100" data-html="true"
                                       title='<ul class="padding0 text-left">
                                       <li class="padding0">Default Route</li>
                                       <li class="padding0">NDNC Restricted</li>
                                       <li class="padding0">Normal</li>
                                       <li class="padding0">For Promotional SMS</li>
                                       </ul>'>
                                        Promotional Route (<?php echo (isset($user_info) && $user_info['pr_sms_balance']) ? $user_info['pr_sms_balance'] : 0; ?>)
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="javascript:void(0)" onclick="changeRoute('D');" data-toggle="tooltip"  data-container="body" data-placement="right" 
                                       animation="true" delay="100" data-html="true"
                                       title='<ul class="padding0 text-left">
                                       <li class="padding0">Default Route</li>
                                       <li class="padding0">NDNC Restricted</li>
                                       <li class="padding0">Normal</li>
                                       <li class="padding0">For Promotional SMS</li>
                                       </ul>'>
                                        Premium Promotion (<?php echo (isset($user_info) && $user_info['prtodnd_balance']) ? $user_info['prtodnd_balance'] : 0; ?>)
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="javascript:void(0)" onclick="changeRoute('C');" data-toggle="tooltip"  data-container="body" data-placement="right" 
                                       animation="true" delay="100" data-html="true"
                                       title='<ul class="padding0 text-left">
                                       <li class="padding0">Default Route</li>
                                       <li class="padding0">NDNC Restricted</li>
                                       <li class="padding0">Normal</li>
                                       <li class="padding0">For Promotional SMS</li>
                                       </ul>'>
                                        Stock Promotion (<?php echo (isset($user_info) && $user_info['stock_balance']) ? $user_info['stock_balance'] : 0; ?>)
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="javascript:void(0)" onclick="changeRoute('B');" data-toggle="tooltip"  data-container="body" data-placement="right" 
                                       animation="true" delay="100" data-html="true"
                                       title='<ul class="padding0 text-left">
                                       <li class="padding0">Template Route</li>
                                       <li class="padding0">NDNC Allowed</li>
                                       <li class="padding0">Premium</li>
                                       <li class="padding0">For Informational SMS</li>
                                       </ul>'>
                                        Transactional Route (<?php echo (isset($user_info) && $user_info['tr_sms_balance']) ? $user_info['tr_sms_balance'] : 0; ?>)
                                    </a>
                                </li>
                            </ul>
                            <input type="hidden" name="route" id="route" value="<?php echo (isset($user_info) && $user_info['default_route']) ? $user_info['default_route'] : 0; ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="portlet-content">
                <div class="row">
                    <div class="form-group col-md-7 padding0">
                        <!--<label for="campaign_name">Campaign Name</label>-->
                        <input type="text" id="campaign_name" name="campaign_name" class="form-control" required="" onclick="getFieldData('campaign');" 
                               value="New Campaign" placeholder="Campaign Name" data-parsley-required-message="Please enter campaign name!" />
                    </div>
                    <div class="form-group col-md-7 padding0">
                        <!--<label for="sender">Sender Id</label>-->
                        <input type="text" id="sender1" name="sender" class="form-control input-md-7" onclick="getFieldData('sender');" required="" data-parsley-minlength="6"  
                               data-parsley-maxlength="6" placeholder="Sender Id"
                               value="<?php echo (isset($last_sender_id) && $last_sender_id) ? $last_sender_id : ""; ?>"
                               data-parsley-required-message="Please enter sender id"/>
                    </div>
                    <div class="form-group col-md-12 padding0">
                        <!--<label for="mobile_numbers">Mobile Number </label>-->
                        <span id="show_groups"></span>
                        <input type="hidden" name="selected_groups" id="selected_groups" value="" />
                        <textarea type="text" id="mobile_numbers" name="mobile_numbers" class="form-control" rows="1"
                                  placeholder="Mobile Numbers Comma Seperated" onclick="getFieldData('mobile');" style="overflow: scroll;overflow-wrap: break-word;resize: horizontal;min-height: 30px;max-height: 200px;"></textarea>

                    </div>
                    <div class="form-group col-md-6 padding0">
                        <select id="message_type" name="message_type" class="form-control" onchange="getUnicodeLang(this.value);">
                            <option value="1">English</option>
                            <option value="2">Unicode</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <div id='translControl' class="hidden"></div>
                    </div>
                    <div class="form-group col-md-12 padding0">
                        <!--<label for="message">Message</label>-->
                        <textarea id="message" name="message"  cols="10" rows="1" required="" class="form-control" placeholder="Message" 
                                  onclick="getFieldData('message');" data-parsley-error-message="Please enter message!"
                                  onkeyup="countMessageLength(this.value)"></textarea>
                    </div>
                    <div class="form-group col-md-4 col-sm-6 col-xs-6 padding0">
                        <span class="label label-primary" id="message_length">0</span> Characters
                    </div>
                    <div class="form-group col-md-4 col-sm-6 col-xs-6">
                        <span class="label label-info" id="credit">0</span> Credits
                        <input type="hidden" name="total_credits" id="total_credits" value="" />
                    </div>
                    <div class="form-group col-md-4 col-sm-12 col-xs-12">
                        <a href="javascript:void(0)" onclick="saveAsDraft();" title="Save As Draft">
                            Save As Draft
                        </a>
                    </div>
                    <div class="col-md-12 padding0">
                        <div class="form-group col-md-6 padding0">
                            <label for="flash_message" class="fancy-check">
                                <input type="checkbox" name="flash_message" id="flash_message" value="1" />
                                <span>Send as Flash SMS</span> 
                            </label>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="label label-default text-justify" data-toggle="tooltip"  data-container="body" data-placement="right" animation="true" delay="100" 
                                   title="A Flash SMS appears directly on the phone's screen, instead of the INBOX. 
                                   Its an useful alternative to normal SMS when you want to catch the recipients attention immediately. 
                                   The recipient has the option of Saving the Flash SMS to his INBOX.">
                                <i class="fa fa-1x fa-question"></i>
                            </label>
                        </div>
                        <div class="form-group col-md-2">
                            <button type="button" class="btn btn-info" onclick="getFieldData('attach');"> <i class="fa fa-paperclip" aria-hidden="true"></i> &nbsp; Attach File</button>
                        </div>
                    </div>
                    <div class="col-md-5 col-xs-5 mt5 text-center padding0">
                        <button type="submit" name="send_sms" id="send_sms" class="btn-group btn-group-justified btn btn-primary"
                                data-loading-text="Sending SMS..." autocomplete="off">
                            Send SMS
                        </button>
                    </div>
                    <div class="col-md-2 col-xs-2 mt5 text-center">
                        <span>OR</span>
                    </div>
                    <div class="col-md-5 col-xs-5 mt5 text-center padding0">
                        <button type="button" class="btn-group btn-group-justified btn btn-default" onclick="getFieldData('schedule');">
                            Schedule SMS
                        </button>
                        <input type="hidden" id="check_sch_sms" value="0" />
                    </div>
                </div>
            </div> 
        </div>
    </div>
    <div class="col-md-5 col-sm-7">
        <div class="portlet">
            <div class="portlet-content">

                <!-- Campaign Div -->
                <div id="campaign_div" class="hidden">
                    <div class="row">
                        <div class="col-md-12 padding0">
                            <h4>Select Folder</h4>
                        </div>
                        <div class="col-md-8 padding0">
                            <div class="table-responsive">
                                <table class="table table-hover bgf">
                                    <tbody>
                                        <?php
                                        if (isset($result_campaign) && $result_campaign) {
                                            foreach ($result_campaign as $value) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <a href="javascript:void(0)" onclick="pickField('campaign', '<?php echo $value->campaign_name; ?>')">
                                                            <?php echo $value->campaign_name; ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table> 
                            </div>       
                        </div>
                    </div>
                </div>

                <!-- Sender Id Div -->
                <div id="sender_div" class="hidden">
                    <div class="row">
                        <div class="col-md-12 padding0">
                            <h4>Select Sender ID</h4>
                        </div>
                        <div class="col-md-8 padding0">
                            <div class="table-responsive" id="show_senders">
                                <table class="table table-hover bgf">
                                    <tbody>
                                        <?php
                                        if (isset($result_sender) && $result_sender) {
                                            $user_sender_ids = $result_sender->sender_ids;
                                            $user_sender_status = $result_sender->sender_status;
                                            $sender_ids_array = explode(',', $user_sender_ids);
                                            $sender_status_array = explode(',', $user_sender_status);
                                            foreach ($sender_ids_array as $sender_key => $sender_value) {
                                                if ($sender_status_array[$sender_key] == '1') {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <span class="pull-right">
                                                                <a href="javascript:void(0)" onclick="deleteItems('senders', '<?php echo $sender_key; ?>')" class="btn btn-danger btn-xs">
                                                                    <i class="fa fa-trash-o"></i>        
                                                                </a>
                                                            </span>
                                                            <a href="javascript:void(0)" onclick="pickField('sender', '<?php echo $sender_value; ?>')">
                                                                <?php echo $sender_value; ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table> 
                            </div>       
                        </div>
                    </div>
                </div>

                <!-- Mobile Div -->
                <div id="mobile_div" class="hidden">
                    <div class="row">
                        <div class="col-md-12 padding0">
                            <h4>And/Or Upload A File</h4>
                        </div>
                        <div class="col-md-12 padding0">
                            <p> Upload CSV (comma separated values), XLS, XLSX file only. </p>                                
                        </div>
                        <div class="col-md-6 padding0">
                            <input type="file"  class="upload_files" name="mobiles" id="mobiles" onchange="handleFiles(this.files)" accept=".xls,.xlsx,.csv" />
                        </div>
                        <div class="col-md-12 padding0">
                            <h4>And/Or Select A Group</h4>
                        </div>
                        <div class="col-md-8 padding0">
                            <div class="table-responsive" id="data_table">
                                <table class="table table-hover bgf">
                                    <thead>
                                        <tr>
                                            <th>Group</th>
                                            <th>Count</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($result_mobile) && $result_mobile) {
                                            $i = 1;
                                            foreach ($result_mobile as $value) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $value->contact_group_name; ?></td>
                                                    <td><?php echo $value->total_contacts; ?></td>
                                                    <td>
                                                        <label for="check_group<?php echo $i; ?>" class="fancy-check">
                                                            <input class="checkboxes" type="checkbox" name="check_group[]" id="check_group<?php echo $i; ?>" value="<?php echo $value->contact_group_id; ?>"
                                                                   onclick="pickField('mobile', '<?php echo $value->contact_group_id . "|" . $value->contact_group_name . "|" . $i; ?>');" />
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <?php
                                                $i++;
                                            }
                                        } else {
                                            ?>
                                        <td colspan="3">No Contact Exists!</td>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table> 
                            </div>                                      
                        </div>
                    </div>
                </div>

                <!-- Message Div -->
                <div id="message_div" class="hidden">
                    <div class="row">
                        <div class="col-md-12 padding0">
                            <h4>Signature & Draft</h4>
                        </div>
                        <div class="col-md-12 padding0" id="show_drafts">
                            <?php
                            if (isset($result_message) && $result_message) {
                                $signatures = $result_message['signatures'];
                                $drafts = $result_message['drafts'];
                                $sent = $result_message['sent'];
                                ?>
                                <div class="row">
                                    <?php
                                    if (isset($signatures) && $signatures) {
                                        $check_signature = $signatures->check_signature;
                                        $signature = $signatures->signature;
                                        if ($check_signature) {
                                            ?>
                                            <div class="col-md-3 padding0">
                                                <label for="check_signature" class="fancy-check">
                                                    <input type="checkbox" checked="" name="check_signature" id="check_signature" onclick="showSignatureField();" />
                                                    <span>Add Signature</span> 
                                                </label>
                                            </div>
                                            <div class="col-md-4 padding0">
                                                <input type="text" name="signature" id="signature" value="<?php echo $signature; ?>" class="form-control"
                                                       onkeyup="saveSignature();" />
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="col-md-3 padding0">
                                                <label for="check_signature" class="fancy-check">
                                                    <input type="checkbox" name="check_signature" id="check_signature" onclick="showSignatureField();" />
                                                    <span>Add Signature</span> 
                                                </label>
                                            </div>
                                            <div class="col-md-4 padding0">
                                                <input type="text" name="signature" id="signature" value="<?php echo $signature; ?>" class="form-control hidden"
                                                       onkeyup="saveSignature();" />
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="col-md-3 padding0">
                                            <label for="check_signature">
                                                <input type="checkbox" name="check_signature" id="check_signature" onclick="showSignatureField();" />
                                                <span>Add Signature</span> 
                                            </label>                                        
                                        </div>
                                        <div class="col-md-4 padding0">
                                            <input type="text" name="signature" id="signature" value="" class="form-control" onkeyup="saveSignature();" />
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 padding0">
                                        <h4>Drafts</h4>     
                                    </div>
                                    <div class="col-md-8 padding0">
                                        <div class="table-responsive">
                                            <table class="table table-hover bgf">
                                                <tbody>
                                                    <?php
                                                    if (isset($drafts) && $drafts) {
                                                        foreach ($drafts as $value) {
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <button data-original-title="Delete Draft" onclick="deleteDraftMsg('drafts', '<?php echo $value->draft_message_id; ?>')" type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="">
                                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>


                                                                    </button></td>
                                                                <td>
                                                                    <a href="javascript:void(0)" onclick="pickField('message', '<?php echo $value->draft_message; ?>')">
                                                                        <?php echo $value->draft_message; ?>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } elseif (isset($sent) && $sent) {
                                                        foreach ($sent as $value) {
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <a href="javascript:void(0)" onclick="pickField('message', '<?php echo urldecode($value->message); ?>')">
                                                                        <?php echo urldecode($value->message); ?>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                    <td>No Draft!</td>
                                                    <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table> 
                                        </div>       
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Schedule Date-Time Div -->
                <div id="schdeule_div" class="row ptb15 hidden">
                    <div class="form-group col-md-12 padding0">
                        <label for="schedule_date">Schedule Date Time</label>
                    </div>
                    <div class="col-md-5 col-sm-6 padding0">
                        <input type="text" id="schedule_date" name="schedule_date" class="form-control" placeholder="Schedule Date" />
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <button type="submit" name="schedule_sms" id="schedule_sms" class="btn btn-primary"
                                data-loading-text="Schedule SMS..." autocomplete="off">
                            Schedule SMS
                        </button>
                        <button type="button" value="cancel" class="btn btn-warning" onclick="getScheduleDateTime(2);">Cancel</button>
                    </div>
                </div>
                <!-- Attech file -->

                <div id="file_attach" class="row ptb15 hidden">
                    <div class="col-md-12 col-sm-12 padding0" id="FileDiv4">
                        <div class="col-md-12 col-sm-12 padding0">
                            <h5>Select a file from your draft list</h5>
                        </div>
                        <div class="col-md-12 col-sm-12 padding0">
                            <small>Choose  file and Upload it to File Draft </small>
                        </div>
                        <div class="col-md-12 col-sm-12 padding0">
                            <div class="col-md-12 col-sm-12 padding0">
                                <div class="form-group">
                                    <input type="file" name="upload_attach_file" id="upload_attach_file" class="upload_files" data-parsley-id="5677" tabindex="-1" style="position: absolute; clip: rect(0px 0px 0px 0px);">

                                    <ul class="parsley-errors-list" id="parsley-id-5677"></ul>
                                </div>
                                <div id="my_div">
                                <div id="rotator" style="height:300px;width:300px"></div>
                                <h3>Please Wait While Processing...</h3>
                            </div>

                            </div>

                        </div>                           

                        <div class="col-md-12 col-sm-12 padding0">
                            <h5>Click on below mention File Name to select File and use it to send</h5>
                        </div>

                        <div class="col-md-12 col-sm-12 padding0">
                            <label>Draft Lists</label> 
                        </div>
                        <div class="col-md-12 col-sm-12 padding0" id="show-attach-drafts">
                            <?php
                            if (isset($result_drafts) && $result_drafts) {
                                foreach ($result_drafts as $key => $value) {
                                    ?>
                                    <li class="list-group-item">
                                        <label for="attach_file<?php echo $key; ?>">
                                            <input type="radio" value="<?php echo base_url() . "Attechment/" . $value->draft_message . "|" . $value->draft_message; ?>" name="upload_attach_file"
                                                   id="attach_file<?php echo $key; ?>" />
                                                   <?php echo $value->draft_message; ?>
                                        </label>
                                        <button data-original-title="Delete Draft" onclick="deleteItems('ATTACH', '<?php echo $value->draft_message_id; ?>')" type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" style="margin-left: 20px;">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                    </li>
                                    <?php
                                }
                            } else {
                                ?>
                                <li class="list-group-item">None</li>
                                <?php
                            }
                            ?>
                        </div>

                        <div class="col-md-12 col-sm-12 padding0">

                        </div>
                    </div>
                </div>

                <!-- Preview SMS Div -->
                <div id="preview_div" class="hidden"></div>

            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    global_button = "";
    $(document).ready(function ()
    {
        $('#send_sms_form textarea').autosize({append: "\n"});
        $('[data-toggle="tooltip"]').tooltip()
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), nowDate.getHours(), nowDate.getMinutes(), nowDate.getSeconds(), 0);
        var m = nowDate.getMonth();
        if (m < 10) {
            m = parseInt(m) + 1;
            if (m < 10) {
                m = "0" + m;
            }
        }
        var d = nowDate.getDate();
        if (d < 10) {
            d = "0" + d;
        }
        var cur_date = String(nowDate.getFullYear() + "-" + m + "-" + d + " " + nowDate.getHours() + ":" + nowDate.getMinutes() + ":" + nowDate.getSeconds());
        $("#schedule_date").datetimepicker({
            format: "yyyy-mm-dd hh:ii:00",
            autoclose: true,
            todayBtn: true,
            startDate: today,
            minuteStep: 15
        });

        $(".upload_files").filestyle();
        // Identify Action Type (Send Or Schedule)
        $("button[type='submit']").click(function (event) {
            global_button = this.id;
            $('span#notification').html("");
            $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
        });
        // Check Validations Then Send SMS
        $("form#sendSMSForms").validate({
            rules: {
                "campaign_name": {
                    required: true
                },
                "sender": {
                    required: true,
                    minlength: 6,
                    maxlength: 6
                },
                "message": {
                    required: true
                }
            },
            messages: {
                "campaign_name": {
                    required: "Please enter campaign name"
                },
                "sender": {
                    required: 'Please enter sender id',
                    minlength: 'Sender id must be 6 char. long',
                    maxlength: 'Sender id must be 6 char. long'
                },
                "message": {
                    required: 'Please enter message'
                }
            },
            // Perform an AJAX
            submitHandler: function () {
                $('span#notification').html("");
                $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
                // Manual Numbers
                var mobile = $('#mobile_numbers').val();
                // CSV File
                var csv = $('#mobiles').val();
                // From Phonebook
                var checked_groups = 0;
                $('.checkboxes').each(function (index, value) {
                    if (this.checked === true) {
                        checked_groups++;
                    }
                });
                if ((mobile === "" && mobile.length === 0) && (csv === "" && csv.length === 0) && (checked_groups === 0)) {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html("<i class='fa fa-exclamation-circle'></i>Error: Please provide mobile number(s) or upload csv or select contact group");
                    return false;
                }
                // If Schedule SMS
                if (global_button === 'schedule_sms') {
                    var schedule_date = $('#schedule_date').val();
                    if (schedule_date === "" && schedule_date.length === 0) {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html("<i class='fa fa-exclamation-circle'></i>Error: Please enter schedule date");
                        return false;
                    }
                    if (schedule_date <= cur_date) {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html("<i class='fa fa-exclamation-circle'></i>Error: Please enter valid schedule date. It must be greater than now");
                        return false;
                    }
                }
                var $btn = $('#' + global_button).button('loading');
                var base_url = $('#base_url').val();
                // Grab all Form Data
                var formData = new FormData($('form#sendSMSForms')[0]);
                $.ajax({
                    url: base_url + '' + controller + '/send_sms1/' + global_button,
                    type: 'POST',
                    data: formData,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $btn.button('reset');
                        if (response) {
                            if (response.type == '100') {
                                var container = $('span#notification');
                                container.addClass("notification alert-danger");
                                container.html(response.message);
                            } else
                            {
                                var container = $('#send_sms_form');
                                container.html(response);
                                var msg_type = $('#msg_type').val();
                                var msg_data = $('#msg_data').val();
                                if (msg_type === '1') {
                                    $('span#notification').addClass("notification alert-success");
                                    $('span#notification').html(msg_data);
                                } else if (msg_type === '0') {
                                    $('span#notification').addClass("notification alert-danger");
                                    $('span#notification').html(msg_data);
                                }
                                $('#form#sendSMSForms')[0].reset();
                                global_button = "";
                            }
                        }
                    }
                });
            }
        });
    });
</script>
<script>
    $('#upload_attach').change(function () {
        if (this.value !== '')
        {

            $('#my_div').fadeIn();
            $("#rotator").rotator();

        } else
        {
            $('#my_div').fadeOut();
        }
    });

</script>
<script>
    function allLetter(inputtxt)
    {
        var letters = /^[A-Za-z]+$/;
        if (inputtxt.value.match(letters))
        {
            alert('Your name have accepted : you can try another');
            return true;
        } else
        {
            alert('Please input alphabet characters only');
            return false;
        }
    }
</script>
<script>
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover();
    });
</script>
<script>
    $('#upload_attach_file').change(function () {
        if (this.value !== '')
        {
            $('#my_div').fadeIn();
            $("#rotator").rotator();
        } else
        {
            $('#my_div').fadeOut();
        }
    });
</script>