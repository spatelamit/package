<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />

<form id="sendVoiceSMSForms" action="javascript:void(0);" method="post" enctype="multipart/form-data">
    <div class="col-md-3 col-sm-5 borderR">
        <div class="portlet">
            <div class="row">
                <div class="col-md-12 content-header-title fhead">
                    <div class="col-xs-4 col-md-5 padding0">Send Voice SMS</div>
                    <div class="col-md-7 btns-route text-right padding0" id="show_change_route">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <?php echo (isset($user_info) && $user_info['default_voice_route'] && $user_info['default_voice_route'] == 'A') ? "Promotional" : "Dynamic"; ?> Route <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                <li>
                                    <a href="javascript:void(0)" onclick="changeVoiceRoute('A');"
                                       data-toggle="tooltip"  data-container="body" data-placement="right" 
                                       animation="true" delay="100" data-html="true"
                                       title='<ul class="padding0 text-left">
                                       <li class="padding0">Default Route</li>
                                       <li class="padding0">Fixed Caller Id</li>
                                       <li class="padding0">NDNC Blocked</li>
                                       <li class="padding0">Normal</li>
                                       <li class="padding0">Working in between 9:00 AM - 9:00PM</li>
                                       <li class="padding0">For Promotional SMS</li>
                                       </ul>'>
                                        Promotional Route (<?php echo (isset($user_info) && $user_info['pr_voice_balance']) ? $user_info['pr_voice_balance'] : 0; ?>)
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="javascript:void(0)" onclick="changeVoiceRoute('B');"
                                       data-toggle="tooltip"  data-container="body" data-placement="right" 
                                       animation="true" delay="100" data-html="true"
                                       title='<ul class="padding0 text-left">
                                       <li class="padding0">Dynamic Route</li>
                                       <li class="padding0">Dynamic Caller-ID</li>
                                       <li class="padding0">Work on DND and Non DND Both </li>
                                       <li class="padding0">Working 24*7</li>
                                       <li class="padding0">Premium</li>
                                       <li class="padding0">For Informational SMS</li>
                                       </ul>'>
                                        Dynamic Route (<?php echo (isset($user_info) && $user_info['tr_voice_balance']) ? $user_info['tr_voice_balance'] : 0; ?>)
                                    </a>
                                </li>
                            </ul>
                            <input type="hidden" name="route" id="route" value="<?php echo (isset($user_info) && $user_info['default_voice_route']) ? $user_info['default_voice_route'] : 0; ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="portlet-content">
                <div class="row">
                    <div class="form-group col-md-12 padding0">
                        <input type="text" id="campaign_name" name="campaign_name" class="form-control" required="" onclick="getFieldDataVoice('campaigns');" 
                               value="New Campaign" placeholder="Campaign Name" data-parsley-required-message="Please Enter Campaign Name!" />
                    </div>
                    <div class="form-group col-md-12 padding0">
                        <input type="text" id="caller_id" name="caller_id" class="form-control" required="" onclick="getFieldDataVoice('caller_ids');" 
                               value="" placeholder="Caller ID" data-parsley-required-message="Please Enter Caller ID!"
                               data-parsley-type-message="Please Enter Numeric Caller ID!" data-parsley-type="integer" />
                    </div>
                    <div class="form-group col-md-12 padding0">
                        <span id="show_groups"></span>
                        <input type="hidden" name="selected_groups" id="selected_groups" value="" />
                        <textarea type="text" id="mobile_numbers" name="mobile_numbers" class="form-control" rows="3"
                                  placeholder="Mobile Numbers Comma Seperated" onclick="getFieldDataVoice('mobile');"></textarea>
                    </div>
                    <div class="form-group col-md-12 padding0">
                        <label>Add Voice File</label>
                    </div>
                    <div class="form-group col-md-12 padding0">
                        <small>Duration ( 30 Seconds per credit )</small>
                    </div>
                    <div class="form-group col-md-7 padding0">
                        <input type="text" name="duration" id="duration" class="form-control" value="" required=""
                               data-parsley-required-message="Please Enter Duration!" data-parsley-type="integer"
                               data-parsley-type-message="Please Enter Numeric Duration!" />
                    </div>
                    <div class="form-group col-md-5 text-right">
                        <button type="button" class="btn btn-info" onclick="getFieldDataVoice('get_files');">Add File</button>
                    </div>
                    <!--
                    <div class="form-group col-md-6 padding0">
                        <input type="text" id="start_date_time" name="start_date_time" class="form-control" required="" 
                               value="" placeholder="Start Date-Time" data-parsley-required-message="Please Enter Start Date-Time!" />
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" id="end_date_time" name="end_date_time" class="form-control" required=""
                               value="" placeholder="End Date-Time" data-parsley-required-message="Please Enter End Date-Time!" />
                    </div>
                    -->
                    <div class="col-md-12 col-xs-12 mt5 text-center padding0">
                        <button type="submit" name="send_voice_sms" id="send_voice_sms" class="btn-group btn-group-justified btn btn-primary"
                                data-loading-text="Sending SMS..." autocomplete="off">
                            Send Voice SMS
                        </button>
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
                            </div>       
                        </div>
                    </div>
                </div>

                <!-- Caller Ids Div -->
                <div id="caller_ids_div" class="hidden">
                    <div class="row">
                        <div class="col-md-12 padding0">
                            <h4>Select Caller ID</h4>
                        </div>
                        <div class="col-md-8 padding0">
                            <div class="table-responsive"></div>       
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

                <!-- Files Div -->
                <div id="files_div" class="hidden">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 padding0">
                            <h4>You can add files by below defined ways</h4>
                            <!-- Record File Div -->
                            <!--
                            <div class="col-md-3 col-sm-6 file-item">
                                <a href="javascript:void(0);" onclick="showFileDiv('FileDiv1')">
                                    <figure>
                                        <div class="file-ico">
                                            <i class="fa fa-phone"></i>
                                        </div>
                                        <figcaption>
                                            <p>Click to receive a call and record your message</p>
                                        </figcaption>    
                                    </figure>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 file-item">
                                <input type="file" id="upload_file" name="upload_file" multiple class="hidden" accept=".mp3, .wav" />
                                <a href="javascript:void(0);" onclick="$('#upload_file').click(); return false">
                                    <figure>
                                        <div class="file-ico blue">
                                            <i class="fa fa-upload"></i>
                                        </div>
                                        <figcaption>
                                            <p>You can upload .wav and .mp3 formats</p>
                                        </figcaption>
                                    </figure>
                                </a>
                            </div>
                            -->
                            <!-- File URL Div -->
                            <div class="col-md-3 col-sm-6 file-item">
                                <a href="javascript:void(0);" onclick="showFileDiv('FileDiv3')">
                                    <figure>
                                        <div class="file-ico pink">
                                            <i class="fa fa-link"></i>
                                        </div> 
                                        <figcaption>
                                            <p>Copy and Paste URL of .mp3 or .wav file</p>
                                        </figcaption>  
                                    </figure>
                                </a>
                            </div>
                            <!-- File Upload Div -->
                            <div class="col-md-3 col-sm-6 file-item">
                                <a href="javascript:void(0);" onclick="showFileDiv('FileDiv2')">
                                    <figure>
                                        <div class="file-ico blue">
                                            <i class="fa fa-upload"></i>
                                        </div>
                                        <figcaption>
                                            <p>You can upload .wav and .mp3 formats</p>
                                        </figcaption>
                                    </figure>
                                </a>
                            </div>
                            <!-- File Drafts Div -->
                            <div class="col-md-3 col-sm-6 file-item">
                                <a href="javascript:void(0);" onclick="showFileDiv('FileDiv4')">
                                    <figure>
                                        <div class="file-ico red">
                                            <i class="fa fa-file-text"></i>
                                        </div>
                                        <figcaption>
                                            <p>Click to open voice draft</p>
                                        </figcaption>
                                    </figure>
                                </a>
                            </div>
                             
                            
                              <!-- compress file -->
                              <div class="col-md-3 col-sm-6 file-item" >
                                <a href="javascript:void(0);" onclick="showFileDiv('FileDiv6')">
                                    <figure>
                                        <div class="file-ico green">
                                            <i class="fa fa-file-text"></i>
                                        </div>
                                        <figcaption>
                                            <p>Click to compress your file</p>
                                        </figcaption>
                                    </figure>
                                </a>
                            </div>
                               
                        <div class="col-md-12 col-sm-12 padding0">

                            <!-- Record File Div -->
                            <!--
                            <div class="col-md-12 col-sm-12 padding0 hidden" id="FileDiv1">
                                <div class="col-md-12 col-sm-12 padding0">
                                    <h5>Record file by your Phone</h5>
                                </div>
                                <div class="col-md-6 col-sm-12 padding0">
                                    <div class="form-group">
                                        <input type="text" name="record_mobile_no" id="record_mobile_no" class="form-control" placeholder="Mobile Number" />
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="record_duration" id="record_duration" class="form-control" placeholder="Duration" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12 text-right">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary">Call Now</button>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 padding0">
                                    <ul class="list-group">
                                        <li class="list-group-item">Step-1 We will call you.</li>
                                        <li class="list-group-item">Step-2 After the beep record your voice.</li>
                                        <li class="list-group-item">Step-3 Press any key to complete recording.</li>
                                    </ul>
                                </div>
                                <div class="col-md-12 col-sm-12 padding0">
                                    <table class="table table-responsive table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Choose your recording</th>
                                                <th>Play</th>
                                                <th>Download</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Choose your recording</td>
                                                <td>Play</td>
                                                <td>Download</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            -->

                            <!-- File URL Div -->
                            <div class="col-md-12 col-sm-12 padding0 hidden" id="FileDiv3">
                                <div class="form-group">
                                    <input type="text" name="voice_file_url" id="voice_file_url" class="form-control" placeholder="Paste URL of File" />
                                </div>
                                <ul class="list-group">
                                    <li class="list-group-item">Step 1: Go to <a href="http://www.fromtexttospeech.com/" target="_blank">http://www.fromtexttospeech.com/</a></li>
                                    <li class="list-group-item">Step 2: Select language, voice and speed of voice.</li>
                                    <li class="list-group-item">Step 3: Type the content to be converted from text to speech in the text box.</li>
                                    <li class="list-group-item">Step 4: Click on 'Create Audio File'.</li>
                                    <li class="list-group-item">Step 5: Click on 'Download Audio File'.</li>
                                    <li class="list-group-item">Step 6: The browser will take you to a new page. Copy the new URL and paste it here.</li>
                                </ul>
                            </div>
                                
                              <!-- compress file -->
                            <div class="col-md-12 col-sm-12 padding0 hidden" id="FileDiv6">
                                
                                <ul class="list-group">
                                    <li class="list-group-item">Step 1: Go to <a href="http://www.mp3smaller.com" target="_blank">http://www.mp3smaller.com</a></li>
                                    <li class="list-group-item">Step 2: Click on browser button and select a audio file for compress.</li>
                                    <li class="list-group-item">Step 3: Selcet the formate of output audio file (first opation is better).</li>
                                    <li class="list-group-item">Step 4: Click on upload button.</li>
                                    <li class="list-group-item">Step 5: Just wait a moment file is getting compressed.</li>
                                    <li class="list-group-item">Step 6: Download this file and upload this file for voice call .</li>
                                </ul>
                            </div>

                            <!-- File Upload Div -->
                            <div class="col-md-12 col-sm-12 padding0 hidden" id="FileDiv2">
                                <div class="form-group">
                                    <input type="file" id="upload_voice_file" name="upload_voice_file" accept=".mp3, .wav" class="upload_files" />
                                </div>
                            </div>

                            <!-- File Drafts Div -->
                            <div class="col-md-12 col-sm-12 padding0 hidden" id="FileDiv4">
                                <div class="col-md-12 col-sm-12 padding0">
                                    <h5>Select a file from your draft list</h5>
                                </div>
                                <div class="col-md-12 col-sm-12 padding0">
                                    <small>Choose Recorded file and Upload it to Voice Draft</small>
                                </div>
                                <div class="col-md-6 col-sm-6 padding0">
                                    <div class="form-group">
                                        <input type="file" name="upload_audio_file" id="upload_audio_file" class="upload_files" />
                                    </div>
                                 
                                   
                                </div>
                                <!--
                                <div class="col-md-6 col-sm-6 padding0 text-center">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary">Save File</button>
                                    </div>
                                </div>
                                -->
                                <div class="col-md-12 col-sm-12 padding0">
                                    <h5>Click on below mention File Name to select File and use it to send Voice SMS</h5>
                                </div>
                                <div class="col-md-12 col-sm-12 padding0">
                                    <label>Draft Lists</label>
                                </div>
                                <div class="col-md-12 col-sm-12 padding0">
                                                <ul class="list-group" style="width: 60%;">
                                                    
                                                   
                                                            <li class="list-group-item">
                                                                 
                                                                    <input type="radio" value="<?php echo base_url() . "Voice/" . $result_demo . "|" . $result_demo; ?>" name="voice_draft_file"/>
                                                                           <?php echo $result_demo; ?>
                                                                 
                                                            </li></ul>  </div>
                                <div class="col-md-12 col-sm-12 padding0">
                                    <ul class="list-group" id="show_voice_drafts">
                                 
                                      <?php  
                                        if (isset($result_drafts) && $result_drafts) {
                                            foreach ($result_drafts as $key => $value) {
                                                ?>
                                                <li class="list-group-item">
                                                    <label for="voice_draft<?php echo $key; ?>">
                                                        <input type="radio" value="<?php echo base_url() . "Voice/" . $value->draft_message . "|" . $value->draft_message; ?>" name="voice_draft_file"
                                                               id="voice_draft<?php echo $key; ?>" />
                                                               <?php echo $value->draft_message; ?>
                                                    </label>
                                                </li>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <li class="list-group-item">None</li>
                                                <?php
                                            }
                                            ?>
                                    </ul>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    // Send Voice SMS
    $(document).ready(function () {
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
        $("#start_date_time").datetimepicker({
            format: "yyyy-mm-dd hh:ii:00",
            autoclose: true,
            todayBtn: true,
            startDate: today,
            minuteStep: 5,
            orientation: 'left'
        });
        $("#end_date_time").datetimepicker({
            format: "yyyy-mm-dd hh:ii:00",
            autoclose: true,
            todayBtn: true,
            startDate: today,
            minuteStep: 5,
            orientation: 'left'
        });
        $(".upload_files").filestyle();
        // Check Validations Then Send SMS
        $("form#sendVoiceSMSForms").validate({
            rules: {
                "campaign_name": {
                    required: true
                },
                "caller_id": {
                    required: true,
                    minlength: 10,
                    maxlength: 10
                },
                "duration": {
                    required: true
                }
            },
            messages: {
                "campaign_name": {
                    required: "Please enter campaign name"
                },
                "caller_id": {
                    required: 'Please enter caller id',
                    minlength: 'Caller_id id must be 10 digits long',
                    maxlength: 'Caller_id id must be 10 digits long'
                },
                "duration": {
                    required: 'Please enter duration'
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
                    $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please provide mobile number(s) or upload csv");
                    return false;
                }
                var upload_voice_file = $('#upload_voice_file').val();
                var voice_file_url = $('#voice_file_url').val();
                if ((upload_voice_file === "" && upload_voice_file.length === 0) && (voice_file_url === "" && voice_file_url.length === 0) && $("input[name=voice_draft_file]").val() === "") {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please provide voice file or file url");
                    return false;
                }
                /*
                 // If Schedule SMS
                 var start_date_time = $('#start_date_time').val();
                 var end_date_time = $('#end_date_time').val();
                 if (start_date_time === "" && start_date_time.length === 0) {
                 $('span#notification').addClass("notification alert-danger");
                 $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please enter start date-time");
                 return false;
                 }
                 if (end_date_time === "" && end_date_time.length === 0) {
                 $('span#notification').addClass("notification alert-danger");
                 $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please enter end date-time");
                 return false;
                 }
                 if (start_date_time <= cur_date) {
                 $('span#notification').addClass("notification alert-danger");
                 $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please enter valid start date-time. It must be greater than now");
                 return false;
                 }
                 if (end_date_time <= cur_date) {
                 $('span#notification').addClass("notification alert-danger");
                 $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please enter valid end date-time. It must be greater than now");
                 return false;
                 }
                 if (end_date_time <= start_date_time) {
                 $('span#notification').addClass("notification alert-danger");
                 $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please enter valid end date-time. It must be greater than start date-time date");
                 return false;
                 }
                 */
                var $btn = $('#send_voice_sms').button('loading');
                var base_url = $('#base_url').val();
                // Grab all Form Data
                var formData = new FormData($('form#sendVoiceSMSForms')[0]);
                $.ajax({
                    url: base_url + '' + controller + '/send_voice_sms',
                    type: 'POST',
                    data: formData,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $btn.button('reset');
                        var container = $('#send_voice_sms_form');
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
                            $('#form#sendVoiceSMSForms')[0].reset();
                        }
                    }
                });
            }
        });
    });
</script>