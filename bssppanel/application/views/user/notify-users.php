<div id="nav_sub">
    <div class="row">
        <ul class="nav nav-pills">
            <li class="<?php echo (isset($tab) && $tab == 'by_sms') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/notify_users/by_sms">Notify By SMS</a>
            </li>
           
            <li class="<?php echo (isset($tab) && $tab == 'by_email') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/notify_users/by_email">Notify By E-Mail</a>
            </li>
            
        </ul>
    </div>
</div>
</div>
<div class="container" id="reseller_settings">
    <?php
    if (isset($tab) && $tab == 'by_sms') {
        ?>
        <div class="row">
            <div class="col-sm-3 borderR">
                <div class="portlet">
                    <h2 class="content-header-title">Notify By SMS</h2>
                    <div class="portlet-content">
                        <div class="row">
                            <?php
                            $data = array('id' => "validate-basic", 'class' => "form parsley-form");
                            echo form_open('user/send_notify_users/' . $tab, $data);
                            ?>
                            <div class="form-group col-md-12 padding0">
                                <label for="notify_users">Select Users</label>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <select id="notify_users" name="notify_users[]" class="form-control" multiple="multiple" required=""
                                        data-parsley-required-message="Please select at least on user">
                                            <?php
                                            if (isset($result_resellers) && $result_resellers) {
                                                ?>
                                        <optgroup label="Resellers">
                                            <?php
                                            foreach ($result_resellers as $reseller) {
                                                ?>
                                                <option value="<?php echo $reseller['contact_number']; ?>"><?php echo $reseller['name']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </optgroup>
                                        <?php
                                    }
                                    if (isset($result_users) && $result_users) {
                                        ?>
                                        <optgroup label="Users">
                                            <?php
                                            foreach ($result_users as $user) {
                                                ?>
                                                <option value="<?php echo $user['contact_number']; ?>"><?php echo $user['name']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </optgroup>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6 padding0">
                                <label for="route">Route</label>
                                <select class="form-control" name="route" id="route" required="" data-parsley-required-message="Please select route">
                                    <option value="">Select Route</option>
                                    <option value="A">Promotional Route</option>
                                    <option value="B">Transactional Route</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="sender">Sender Id</label>
                                <input type="text" id="sender" name="sender" class="form-control" required="" data-parsley-minlength="6"  
                                       data-parsley-maxlength="6" placeholder="Sender Id" 
                                       value="<?php echo (isset($user_info) && $user_info['default_sender_id']) ? $user_info['default_sender_id'] : ""; ?>"
                                       data-parsley-required-message="Please enter sender id" data-parsley-maxlength-message="Sender id must be of 6 character long" 
                                       data-parsley-minlength-message="Sender id must be of 6 character long" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="message">Message</label>
                                <textarea id="message" name="message"  cols="10" rows="3" required="" class="form-control" placeholder="Message" 
                                          data-parsley-required-message="Please enter message"></textarea>
                            </div>
                            <div class="form-group col-md-12 padding0 mt5">
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </div>
                            </form>
                        </div>
                    </div> 
                </div> 
            </div>
            <div class="col-sm-9">
                <div class="portlet">
                    <h2 class="content-header-title">Previous Sent SMS</h2>
                    <div class="portlet-content">
                        <div class="row">
                            <div class="col-md-12 padding0 table-responsive mt5" id="data_table">
                                <table class="table table-hover bgf">
                                    <thead>
                                        <tr>
                                            <th>Route</th>
                                            <th>Sender Id</th>
                                            <th>Message</th>
                                            <th>Sent Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($previous) && $previous) {
                                            foreach ($previous AS $sms) {
                                                ?>
                                                <tr>
                                                    <td><?php echo ($sms['notify_route'] == 'A') ? "Promotional" : "Transactional"; ?></td>
                                                    <td><?php echo $sms['notify_sender']; ?></td>
                                                    <td><?php echo urldecode($sms['notify_message']); ?></td>
                                                    <td><?php echo $sms['notify_date']; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="4">No Record!</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div>
        </div>
        <?php
    }

    if (isset($tab) && $tab == 'by_email') {
        ?>
        <div class="row">
            <div class="col-sm-3 borderR">
                <div class="portlet">
                    <h2 class="content-header-title">Notify By E-Mail</h2>
                    <div class="portlet-content">
                        <div class="row">
                            <?php
                            $data = array('id' => "validate-basic", 'class' => "form parsley-form");
                            echo form_open('user/send_notify_users/' . $tab, $data);
                            ?>
                            <div class="form-group col-md-12 padding0">
                                <label>Select Users</label>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <select id="notify_users" name="notify_users[]" class="form-control" multiple="multiple" required=""
                                        data-parsley-required-message="Please select at least on user">
                                            <?php
                                            if (isset($result_resellers) && $result_resellers) {
                                                ?>
                                        <optgroup label="Resellers">                                    
                                            <?php
                                            foreach ($result_resellers as $reseller) {
                                                ?>
                                                <option value="<?php echo $reseller['email_address']; ?>"><?php echo $reseller['name']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </optgroup>
                                        <?php
                                    }
                                    if (isset($result_users) && $result_users) {
                                        ?>
                                        <optgroup label="Users">
                                            <?php
                                            foreach ($result_users as $user) {
                                                ?>
                                                <option value="<?php echo $user['email_address']; ?>"><?php echo $user['name']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </optgroup>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="email_address">Email Address</label>
                                <input type="text" id="email_address" name="email_address" class="form-control" required="" placeholder="Enter Email Address"
                                       value="<?php echo (isset($user_info) && $user_info['email_address']) ? $user_info['email_address'] : ""; ?>"
                                       data-parsley-required-message="Please enter email address"  />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="subject">Subject</label>
                                <input type="text" id="subject" name="subject" class="form-control" required="" placeholder="Enter Subject" value=""
                                       data-parsley-required-message="Please enter subject"  />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="message">Message Body</label>
                                <textarea id="message" name="message"  cols="10" rows="8" required="" class="form-control" placeholder="Enter Message Body" 
                                          data-parsley-required-message="Please enter message!"></textarea>
                            </div>
                            <div class="form-group col-md-12 padding0 mt5">
                                <button type="submit" class="btn btn-primary">Send Email</button>
                            </div>
                            </form>
                        </div>
                    </div> 
                </div> 
            </div>
            <div class="col-sm-9">
                <div class="portlet">
                    <h2 class="content-header-title">Previous Sent Email(s)</h2>
                    <div class="portlet-content">
                        <div class="row">
                            <div class="col-md-12 padding0 table-responsive mt5" id="data_table">
                                <table class="table table-hover bgf">
                                    <thead>
                                        <tr>
                                            <th>Email Address</th>
                                            <th>Subject</th>
                                            <th>Content</th>
                                            <th>Sent Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($previous) && $previous) {
                                            foreach ($previous AS $email) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $email['notify_email']; ?></td>
                                                    <td><?php echo $email['notify_subject']; ?></td>
                                                    <td><?php echo $email['notify_body']; ?></td>
                                                    <td><?php echo $email['notify_date']; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="4">No Record!</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div>
        </div>
       
        <?php
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function ()
        {
            $('#notify_users').multiselect({
                //includeSelectAllOption: true,
                //selectAllText: 'Select All',
                maxHeight: 200,
                enableFiltering: true,
                enableClickableOptGroups: true,
                enableCaseInsensitiveFiltering: true
            });
        });
        $('.tab-forms').parsley();
    </script>