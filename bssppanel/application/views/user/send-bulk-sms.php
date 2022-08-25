<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/jquerysctipttop.css" />
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/rotator.js"></script>
<style>
    #my_div {
        display:none;
    }
</style>
</div>
<div class="container">
    <div class="row" id="send_sms_form">
        <?php
        $data = array('id' => "validate-basic");
        echo form_open_multipart('user/send_sms', $data);
        ?>
        <!--<form id="sendSMSForm" action="javascript:sendBulkSMS();" method="post" enctype="multipart/form-data">-->
        <div class="col-md-3 col-sm-5 borderR">
            <div class="portlet">
                <div class="row">
                    <div class="col-md-12 content-header-title fhead">
                        <div class="col-xs-4 col-md-5">Send SMS</div>
                        <div class="col-md-7 btns-route text-right" id="show_change_route">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <?php echo (isset($user_info) && $user_info['default_route'] && $user_info['default_route'] == 'A') ? "Promotional" : "Transactional"; ?> Route <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="javascript:void(0)" onclick="changeRoute('A');">
                                            Promotional Route (<?php echo (isset($user_info) && $user_info['pr_sms_balance']) ? $user_info['pr_sms_balance'] : 0; ?>)
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="javascript:void(0)" onclick="changeRoute('B');">
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

                        <div class="form-group col-md-7">
                            <label for="campaign_name">Campaign Name</label>
                            <input type="text" id="campaign_name" name="campaign_name" class="form-control" required="" onclick="getFieldData('campaign');" 
                                   value="New Campaign" placeholder="Campaign Name" data-parsley-required-message="Please enter campaign name!" />
                        </div>

                        <div class="form-group col-md-7">
                            <label for="sender">Sender Id</label>
                            <input type="text" id="sender1" name="sender" class="form-control input-md-7" onclick="getFieldData('sender');" required=""   
                                   placeholder="Sender Id" value="<?php echo (isset($last_sender_id) && $last_sender_id) ? $last_sender_id : ""; ?>">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="mobile_numbers">Mobile Number </label>
                            <span id="show_groups"></span>
                            <input type="hidden" name="selected_groups" id="selected_groups" value="" />
                            <textarea type="text" id="mobile_numbers" name="mobile_numbers" class="form-control" rows="3"
                                      placeholder="Mobile Numbers Comma Seperated" onclick="getFieldData('mobile');" style="overflow: scroll;overflow-wrap: break-word;resize: horizontal;min-height: 30px;max-height: 200px;"></textarea>

                        </div>
                        <div class="form-group col-md-6">
                            <select id="message_type" name="message_type" class="form-control" onchange="getUnicodeLang(this.value);">
                                <option value="1">English</option>
                                <option value="2">Unicode</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <!--
                            <div class="btn-group" id="unicode_div">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle hidden" data-toggle="dropdown" aria-expanded="false" id="unicode_type">
                                    Unicode <span class="caret"></span>
                                </button>
                            <!--<ul class="dropdown-menu hidden" role="menu" id="unicode_type"></ul>-->
                            <!--
                        </div>
                            -->
                                <!--<select id="unicode_type" name="unicode_type" class="form-control hidden" onchange="javascript:languageChangeHandler()"></select>-->
                            <!--
                            <label for="checkboxId" class="fancy-check">
                                <input type="checkbox" id="checkboxId" onclick="javascript:checkboxClickHandler()" />
                                <span>Unicode</span>
                            </label>
                            -->
                            <div id='translControl' class="hidden"></div>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="message">Message</label>
                            <textarea id="message" name="message"  cols="10" rows="1" required="" class="form-control" placeholder="Message" 
                                      onclick="getFieldData('message');" data-parsley-error-message="Please enter message!"
                                      onkeyup="countMessageLength(this.value)"></textarea>
                        </div>

                        <div class="form-group col-md-4">
                            <span class="label label-primary" id="message_length">0</span> Characters
                        </div>
                        <div class="form-group col-md-4">
                            <span class="label label-info" id="credit">0</span> Credits
                            <input type="hidden" name="total_credits" id="total_credits" value="" />
                        </div>
                        <div class="form-group col-md-4">
                            <a href="javascript:void(0)" onclick="saveAsDraft();" title="Save As Draft">
                                Save As Draft
                            </a>
                        </div>

                        <div class="col-md-12 padding0">
                            <div class="form-group col-md-6">
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
                        <div class="col-md-3 col-xs-5 mt5 text-center">
                            <input type="submit" name="send_sms" id="send_sms" class="btn btn-primary" value="Send SMS" />
                        </div>
                        <div class="col-md-3 col-xs-1 mt5 text-center">
                            <span>OR</span>
                        </div>
                        <div class="col-md-3 col-xs-5 mt5 text-center">
                            <button type="button" class="btn btn-default" onclick="getFieldData('schedule');"><i class="fa fa-clock-o" aria-hidden="true"></i>Schedule SMS</button>
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
                                                                        <a href="javascript:void(0)" onclick="pickField('message', '<?php echo $value->draft_message; ?>')">
                                                                            <?php echo $value->draft_message; ?>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        } elseif ($sent) {
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
                            <input type="submit" name="schedule_sms" id="schedule_sms" value="Schedule SMS" class="btn btn-primary" />
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
    </div>
</div>
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