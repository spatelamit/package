<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUTab('1');">Users</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUTab('2');">Add User</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "3") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUTab('3');">SMPP Routing</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "4") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUTab('4');">Notify Users By SMS</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "5") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUTab('5');">Notify Users By Email</a>
    </li>
</ul>
<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active">

        <?php if ($subtab == '1') { ?>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Total Clients</th>
                                <th>Total Resellers</th>
                                <th>Total Users</th>
                                <th>Active Users</th>
                                <th>Demo Users</th>
                            </tr>
                            <tr>
                                <td><?php echo (isset($total_resellers) && isset($total_users)) ? $total_resellers + $total_users : 0; ?></td>
                                <td><?php echo (isset($total_resellers)) ? $total_resellers : 0; ?></td>
                                <td><?php echo (isset($total_users)) ? $total_users : 0; ?></td>
                                <td><?php echo (isset($active_users)) ? $active_users : 0; ?></td>
                                <td><?php echo (isset($demo_users)) ? $demo_users : 0; ?></td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="table-responsive" id="show_users">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Parent</th>
                                <th>Reseller</th>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Email Address</th>
                                <th>User Type</th>
                                <th>Date</th>
                                <th>Account Type</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <?php
                            if ($users) {
                                foreach ($users as $row) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['username']; ?>
                                        </td>
                                        <td><?php echo ($row['parent_username'] == "") ? $row['admin_username'] : $row['parent_username']; ?></td>
                                        <td><?php echo ($row['ref_username'] == "") ? "-" : $row['ref_username']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['contact_number']; ?></td>
                                        <td><?php echo $row['email_address']; ?></td>
                                        <td>
                                            <?php if ($row['utype'] == 'Reseller') { ?>
                                                <span class="label label-success">Reseller</span>
                                            <?php } elseif ($row['utype'] == 'User') { ?>
                                                <span class="label label-warning">User</span>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $row['creation_date']; ?></td>
                                        <td>
                                            <?php if ($row['check_demo_user']) { ?>
                                                <span class="label label-success">Demo</span>
                                            <?php } else { ?>
                                                <span class="label label-danger">Active</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="9" align="center">
                                        <strong>No Users</strong>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <?php echo $paging; ?>
                </div>
            </div>
        <?php } ?>

        <?php if ($subtab == '2') { ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="table-responsive">
                        <form role="form" class="tab-forms" id="addUserForm" data-parsley-validate method='post' action="javascript:saveUser();">
                            <div class="row">
                                <div class="col-md-12 padding0">
                                    <span id="show_message"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 padding0">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" class="form-control" value="" name="name" id="name" placeholder="Please Enter Full Name" required=""
                                               data-parsley-pattern="^[A-Za-z]([-']?[A-Za-z]+)*( [A-Za-z]([-']?[A-Za-z]+)*)+$" 
                                               data-parsley-pattern-message="Please Enter First And Last Name" data-parsley-required-message="Please Enter Full Name" />
                                    </div>    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" value="" name="username" id="username" 
                                               placeholder="Please Enter Username" required="" 
                                               onkeyup="checkUsername(this.value, 'user')" data-parsley-minlength="5"  data-parsley-pattern="/^[A-Za-z][A-Za-z0-9]*$/"
                                               data-parsley-required-message="Please Enter Username" data-parsley-pattern-message="Username must be start with a character!"
                                               data-parsley-minlength-message="Username must be 5 characters long" />
                                    </div>    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 padding0">
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input type="text" class="form-control" value="" name="contact" id="contact" data-parsley-type="integer"
                                               placeholder="Please Enter Contact Number" required=""
                                               data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-minlength-message="Please Enter Valid Contact Number"
                                               data-parsley-type-message="Please Enter Valid Contact Number" data-parsley-required-message="Please Enter Contact Number"
                                               data-parsley-maxlength-message="Please Enter Valid Contact Number"/>
                                    </div>    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="text" class="form-control" value="" name="email" id="email" data-parsley-type="email" 
                                               placeholder="Please Enter Email Address" required=""
                                               data-parsley-type-message="Please Enter Valid Email Address" data-parsley-required-message="Please Enter Email Address" />
                                    </div>    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 padding0">
                                    <div class="form-group">
                                        <label>User Type</label>
                                        <select name="user_type" id="user_type" class="form-control" required="" data-parsley-required-message="Please Select User Type">
                                            <option value="">Select Type</option>
                                            <option value="Reseller">Reseller</option>
                                            <option value="User">User</option>
                                        </select>
                                    </div>    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <input type="text" class="form-control" value="" name="company" id="company" 
                                               placeholder="Please Enter Company Name" data-parsley-required-message="Please Enter Company Name" required="" />
                                    </div>    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 padding0">
                                    <div class="form-group">
                                        <label>Industry</label>
                                        <select name="industry" class="form-control" id="industry" data-parsley-required-message="Please Select Industry" required="">
                                            <option value="" selected="">Select Industry</option>
                                            <option value="Agriculture ">Agriculture </option>
                                            <option value="Automobile &amp; Transport">Automobile &amp; Transport</option>
                                            <option value="Ecommerce">E-commerce</option>
                                            <option value="Education">Education</option>
                                            <option value="Financial Institution">Financial Institution</option>
                                            <option value="Gym">Gym</option>
                                            <option value="Hospitality">Hospitality</option>
                                            <option value="IT Company">IT Company</option>
                                            <option value="Lifestyle Clubs">Lifestyle Clubs</option>
                                            <option value="Logistics">Logistics</option>
                                            <option value="Marriage Bureau">Marriage Bureau</option>
                                            <option value="Media &amp; Advertisement">Media &amp; Advertisement</option>
                                            <option value="Personal Use">Personal Use</option>
                                            <option value="Political ">Political </option>
                                            <option value="Public Sector">Public Sector</option>
                                            <option value="Real estate">Real estate</option>
                                            <option value="Reseller">Reseller</option>
                                            <option value="Retail &amp; FMCG">Retail &amp; FMCG</option>
                                            <option value="Stock and Commodity">Stock and Commodity</option>
                                            <option value="Telecom">Telecom</option>
                                            <option value="Tips And Alert">Tips And Alert</option>
                                            <option value="Travel">Travel</option>
                                        </select>
                                    </div>    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Choose Expiry Date</label>
                                        <input name="expiry_date" id="expiry_date" placeholder="Enter Expiry Date" class="form-control" required="" 
                                               data-parsley-error-message="Please Enter Expiry Date" type="text" value="">
                                    </div>    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 padding0">
                                    <button type="submit" class="btn btn-primary" name="save" id="save"
                                            data-loading-text="Processing..." autocomplete="off">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ($subtab == '3') { ?>
            <!-- User SMPP Routing -->
            <div class="row">
                <div class="col-md-3 padding0">
                    <form role="form" class="tab-forms" id="smppPRRoutingForm" method='post' action="javascript:saveSMPPRouting('pr');">
                        <div class="col-md-12 padding0">
                            <h4>Promotional Routing</h4>
                        </div>
                        <div class="col-md-12 padding0">
                            <div class="form-group">
                                <label>Current Connected SMPP</label>
                                <select class="form-control" name="current_pr_route" id="current_pr_route" required=""
                                        data-parsley-required-message="Please Select SMPP">
                                    <option value="">Select SMPP</option>
                                    <?php
                                    if ($pr_user_groups) {
                                        foreach ($pr_user_groups as $pr_ugroup) {
                                            ?>
                                            <option value="<?php echo $pr_ugroup['user_group_id']; ?>"><?php echo $pr_ugroup['user_group_name'] . " [" . $pr_ugroup['smsc_id'] . "]"; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 padding0">
                            <div class="form-group">
                                <label>New Connected SMPP</label>
                                <select class="form-control" name="new_pr_route" id="new_pr_route" required=""
                                        data-parsley-required-message="Please Select SMPP">
                                    <option value="">Select SMPP</option>
                                    <?php
                                    if ($pr_user_groups) {
                                        foreach ($pr_user_groups as $pr_ugroup) {
                                            ?>
                                            <option value="<?php echo $pr_ugroup['user_group_id']; ?>"><?php echo $pr_ugroup['user_group_name'] . " [" . $pr_ugroup['smsc_id'] . "]"; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 padding0">
                            <button type="submit" class="btn btn-primary btn-sm" id="btnprrouting" data-loading-text="Processing..." autocomplete="off">Save Routing</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-3">
                    <form role="form" class="tab-forms" id="smppTRRoutingForm" method='post' action="javascript:saveSMPPRouting('tr');">
                        <div class="col-md-12">
                            <h4>Transactional Routing</h4>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Current Connected SMPP</label>
                                <select class="form-control" name="current_tr_route" id="current_tr_route" required="" data-parsley-required-message="Please Select SMPP">
                                    <option value="">Select SMPP</option>
                                    <?php
                                    if ($tr_user_groups) {
                                        foreach ($tr_user_groups as $tr_ugroup) {
                                            ?>
                                            <option value="<?php echo $tr_ugroup['user_group_id']; ?>"><?php echo $tr_ugroup['user_group_name'] . " [" . $tr_ugroup['smsc_id'] . "]"; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>New Connected SMPP</label>
                                <select class="form-control" name="new_tr_route" id="new_tr_route" required="" data-parsley-required-message="Please Select SMPP">
                                    <option value="">Select SMPP</option>
                                    <?php
                                    if ($tr_user_groups) {
                                        foreach ($tr_user_groups as $tr_ugroup) {
                                            ?>
                                            <option value="<?php echo $tr_ugroup['user_group_id']; ?>"><?php echo $tr_ugroup['user_group_name'] . " [" . $tr_ugroup['smsc_id'] . "]"; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-sm" id="btntrrouting" data-loading-text="Processing..." autocomplete="off">Save Routing</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>

        <?php if ($subtab == '4') { ?>
            <!-- Notify Users -->
            <div class="row">
                <div class="col-md-3 borderR">
                    <form role="form" id="notifySMSForm" method='post' action="javascript:notifyUsers('sms');" class="notify-forms">
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
                                            <option value="<?php echo $reseller['contact_number']; ?>"><?php echo $reseller['name'] . " (" . $reseller['username'] . ")"; ?></option>
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
                                            <option value="<?php echo $user['contact_number']; ?>"><?php echo $user['name'] . " (" . $user['username'] . ")"; ?></option>
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
                            <label for="route">Route</label>
                            <select class="form-control" name="route" id="route" required="" data-parsley-required-message="Please select route">
                                <option value="">Select Route</option>
                                <option value="A">Promotional Route</option>
                                <option value="B">Transactional Route</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <label for="sender">Sender Id</label>
                            <input type="text" id="sender" name="sender" class="form-control" required="" data-parsley-minlength="6"  
                                   data-parsley-maxlength="6" placeholder="Sender Id" value=""
                                   data-parsley-required-message="Please enter sender id" data-parsley-maxlength-message="Sender id must be of 6 character long" 
                                   data-parsley-minlength-message="Sender id must be of 6 character long" />
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <label for="message">Message</label>
                            <textarea id="message" name="message"  cols="10" rows="3" required="" class="form-control" placeholder="Message" 
                                      data-parsley-required-message="Please enter message"></textarea>
                        </div>
                        <div class="form-group col-md-12 padding0 mt5">
                            <button type="submit" class="btn btn-primary btn-sm" id="notifysms" name="notifysms" data-loading-text="Processing..." autocomplete="off">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-9 table-responsive">
                    <table class="table table-bordered table-striped">
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
                            if ($previous) {
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
        <?php } ?>

        <?php if ($subtab == '5') { ?>
            <!-- Notify Users -->
            <div class="row">
                <div class="col-md-3 borderR">
                    <form role="form" id="notifyEmailForm" method='post' action="javascript:notifyUsers('email');" class="notify-forms">
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
                                            <option value="<?php echo $reseller['email_address']; ?>"><?php echo $reseller['name'] . " (" . $reseller['username'] . ")"; ?></option>
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
                                            <option value="<?php echo $user['email_address']; ?>"><?php echo $user['name'] . " (" . $user['username'] . ")"; ?></option>
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
                                   value="" data-parsley-required-message="Please enter email address" />
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
                        <div class="form-group col-md-12 mt5 padding0">
                            <button type="submit" class="btn btn-primary btn-sm" id="notifyemail" name="notifyemail" data-loading-text="Processing..." autocomplete="off">
                                Send Email
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-9 table-responsive">
                    <table class="table table-bordered table-striped">
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
                            if ($previous) {
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
        <?php } ?>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
        $('#expiry_date').datepicker({
            format: "dd-mm-yyyy",
            startDate: today,
            autoclose: true
        });
        $('#notify_users').multiselect({
            //includeSelectAllOption: true,
            //selectAllText: 'Select All',
            maxHeight: 300,
            enableFiltering: true,
            enableClickableOptGroups: true,
            enableCaseInsensitiveFiltering: true
        });
    });

    $('.tab-forms').parsley();
    $('.notify-forms').parsley();
</script>