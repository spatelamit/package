<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />
<?php
$total_columns = "";
?>
<form id="sendCustomForm" action="javascript:void(0);" method="post" enctype="multipart/form-data">
    <div class="col-md-3 col-sm-5 borderR">
        <div class="portlet">
            <div class="row">
                <div class="col-md-12 content-header-title fhead">
                    <div class="col-xs-4 col-md-6">Send Custom SMS</div>
                    <div class="col-md-6 btns-route text-right" id="show_change_route">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <?php echo (isset($user_info) && $user_info['default_route'] && $user_info['default_route'] == 'A') ? "Promotional" : "Transactional"; ?> Route <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
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
                            <input type="hidden" name="route" value="<?php echo (isset($user_info) && $user_info['default_route']) ? $user_info['default_route'] : ""; ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-7">
                    <!--<label for="campaign_name">Campaign Name</label>-->
                    <input type="text" id="campaign_name" name="campaign_name" class="form-control" required="" onclick="getFieldData('campaign');" 
                           value="New Campaign" placeholder="Campaign Name" data-parsley-error-message="Please enter campaign!" />
                </div>
                <div class="form-group col-md-7">
                    <!--<label for="sender">Sender Id</label>-->
                    <input type="text" id="sender" name="sender" class="form-control" onclick="getFieldData('sender');" required="" data-parsley-minlength="6"  
                           data-parsley-maxlength="6" placeholder="Sender Id" parsley-minlength-message="Sender id must be of 6 character!" 
                           parsley-maxlength-message="Sender id must be of 6 character!" 
                           value="<?php echo (isset($last_sender_id) && $last_sender_id) ? $last_sender_id : ""; ?>"
                           data-parsley-error-message="Please enter sender id!" />
                </div>
                <div class="form-group col-md-7">
                    <!--<label for="contact_column">Contact Number Column</label>-->
                    <select id="contact_column" name="contact_column" class="form-control" required="" data-parsley-error-message="Please select contact column!">
                        <option value="">Select Number Column</option>
                        <?php
                        for ($i = 0; $i < 20; $i++) {
                            ?>
                            <option value="<?php echo $i; ?>">Column <?php echo $i + 1; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <select id="message_type" name="message_type" class="form-control" onchange="getUnicodeLang(this.value);">
                        <option value="1">English</option>
                        <option value="2">Unicode</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <div id='translControl' class="hidden"></div>
                </div>
                <div class="form-group col-md-12">
                    <!--<label for="message">Message</label>-->
                    <textarea id="message" name="message"  cols="10" rows="1" required="" class="form-control" placeholder="Message" 
                              onclick="getFieldData('message');" onkeyup="countMessageLength(this.value)" data-parsley-error-message="Please enter message!"></textarea>
                </div>
                <div class="form-group col-md-4">
                    <span class="label label-primary" id="message_length">0</span> Characters
                </div>
                <div class="form-group col-md-4">
                    <span class="label label-info" id="credit">0</span> Credits
                    <input type="hidden" name="total_credits" id="total_credits" value="" />
                </div>
                <div class="form-group col-md-4">
                    <input type="button" id="preview" value="Preview" class="btn btn-info btn-xs" onclick="getSMSPreview();"
                           data-toggle="tooltip" data-placement="bottom" title="Preview Message" />
                </div>

                <div class="col-md-12 padding0">
                    <div class="form-group col-md-8">
                        <label for="flash_message" class="fancy-check">
                            <input type="checkbox" name="flash_message" id="flash_message" value="1" />
                            <span>Send as Flash SMS</span> 
                        </label>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="label label-default text-justify" data-toggle="tooltip"  data-container="body" data-placement="right" animation="true" delay="100" 
                               title="A Flash SMS appears directly on the phone's screen, instead of the INBOX. 
                               Its an useful alternative to normal SMS when you want to catch the recipients attention immediately. 
                               The recipient has the option of Saving the Flash SMS to his INBOX.">
                            <i class="fa fa-1x fa-question"></i>
                        </label>
                    </div>
                </div>
                <div class="col-md-5 col-xs-5 mt5 text-center">
                    <button type="submit" class="btn-group btn-group-justified btn btn-primary" name="send_sms" id="send_sms"
                            data-loading-text="Sending..." autocomplete="off">
                        Send SMS
                    </button>
                </div>
                <div class="col-md-2 col-xs-1 mt5 text-center">
                    <span>OR</span>
                </div>
                <div class="col-md-5 col-xs-5 mt5 text-center">
                    <button type="button" class="btn-group btn-group-justified btn btn-default" onclick="getFieldData('schedule');">Schedule SMS</button>
                    <input type="hidden" id="check_sch_sms" value="0" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9 col-sm-7">
        <div class="portlet">
            <div class="portlet-content">
                <div class="row">
                    <div class="col-sm-12 padding0">
                        <div class="table-responsive" id="data_table">
                            <table class="table bgf table-hover">
                                <thead>
                                    <tr>
                                        <?php
                                        for ($j = 1; $j <= $numcols; $j++) {
                                            if ($total_columns == "")
                                                $total_columns = "##C" . $j . "##";
                                            else
                                                $total_columns = $total_columns . ",##C" . $j . "##";
                                            ?>
                                            <th>
                                                Column <?php echo $j; ?>
                                                <a href="javascript:void(0)" onclick="selectColumn('<?php echo $j; ?>');" class="btn btn-info btn-xs">Select</a>
                                            </th>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $myfile = "./Uploads/" . $temp_file_name;
                                    $total_messages = 0;
                                    $fp = fopen($myfile, "r");
                                    if ($fp) {
                                        while (!feof($fp)) {
                                            $content = fgets($fp);
                                            if ($content)
                                                $total_messages++;
                                        }
                                    }
                                    fclose($fp);

                                    $limit = 1;
                                    $filehandle = fopen($myfile, "r");
                                    $ii = 0;
                                    while (!feof($filehandle)) {
                                        $line = explode(',', fgets($filehandle));
                                        if ($ii == $limit) {
                                            break;
                                        } else {
                                            ?>
                                            <tr>
                                                <?php
                                                for ($k = 0; $k < $numcols; $k++) {
                                                    ?>
                                                    <td><?php echo $line[$k]; ?></td>
                                                    <?php
                                                }
                                                ?>
                                            </tr>
                                            <?php
                                        }
                                        $ii++;
                                    }
                                    fclose($filehandle);
                                    ?>
                                </tbody>
                            </table>
                            <hr/>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="temp_file_name" id="temp_file_name" value="<?php echo $temp_file_name; ?>" />
                <input type="hidden" name="total_column" id="total_column" value="<?php echo $total_columns; ?>" />
                <input type="hidden" name="total_messages" id="total_messages" value="<?php echo $total_messages; ?>" />

                <!-- Campaign Div -->
                <div id="campaign_div" class="hidden">
                    <div class="row">
                        <div class="col-md-12 padding0">
                            <h4>Select Folder</h4>
                        </div>
                        <div class="col-md-4 padding0">
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
                        <div class="col-md-4 padding0">
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
                <div id="mobile_div" class="hidden"></div>

                <!-- Message Div -->
                <div id="message_div" class="hidden">
                    <div class="row">
                        <div class="col-md-12 padding0">
                            <h4>Signature & Draft</h4>
                        </div>
                        <div class="col-md-7 padding0" id="show_drafts">
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
                                                <input type="text" name="signature" id="signature" value="<?php echo $signature; ?>" class="form-control" />
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
                                                <input type="text" name="signature" id="signature" value="<?php echo $signature; ?>" class="form-control hidden" />
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
                                            <input type="text" name="signature" id="signature" value="" class="form-control" />
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
                                                                    <a href="javascript:void(0)" onclick="pickField('message', '<?php echo $value->draft_message; ?>')">
                                                                        <?php echo $value->draft_message; ?>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        if (isset($sent) && $sent) {
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
                                                        }
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
                    <div class="col-md-3 col-sm-6 padding0">
                        <input type="text" id="schedule_date" name="schedule_date" class="form-control" placeholder="Schedule Date" />
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <button type="submit" class="btn btn-primary" name="schedule_sms" id="schedule_sms"
                                data-loading-text="Sending..." autocomplete="off">
                            Schedule SMS 
                        </button>
                        <button type="button" value="cancel" class="btn btn-warning" onclick="getScheduleDateTime(2);">Cancel</button>
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
        $('#sendCustomForm textarea').autosize({append: "\n"});
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

        // Send Custom SMS Form
        // Check Validations Then Send SMS
        $("form#sendCustomForm").validate({
            rules: {
                "campaign_name": {
                    required: true
                },
                "sender": {
                    required: true,
                    minlength: 6,
                    maxlength: 6
                },
                "contact_column": {
                    required: true
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
                "contact_column": {
                    required: 'Please select contact column'
                },
                "message": {
                    required: 'Please enter message'
                }
            },
            // Perform an AJAX
            submitHandler: function () {
                $('span#notification').html("");
                $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
                // If Schedule SMS
                if (global_button === 'schedule_sms') {
                    var schedule_date = $('#schedule_date').val();
                    if (schedule_date === "" && schedule_date.length === 0) {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html("Error: Please enter schedule date");
                        return false;
                    }
                    if (schedule_date <= cur_date) {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html("Error: Please enter valid schedule date. It must be greater than now");
                        return false;
                    }
                }
                var $btn = $('#' + global_button).button('loading');
                var base_url = $('#base_url').val();
                // Grab all Form Data
                var formData = new FormData($('form#sendCustomForm')[0]);
                $.ajax({
                    url: base_url + '' + controller + '/send_advance_sms/' + global_button,
                    type: 'POST',
                    data: formData,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $btn.button('reset');
                        if (response.type) {
                            $('span#notification').addClass("notification alert-danger");
                            $('span#notification').html(response.message);
                        } else {
                            var container = $('#custom_sms_section');
                            if (response) {
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
                                $('#form#sendCustomForm')[0].reset();
                                global_button = "";
                            }
                        }
                    }
                });
            }
        });
    });
</script>