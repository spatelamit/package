
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
    <li class="<?php echo (isset($subtab) && $subtab == "6") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUTab('6');">Set Retry Routing</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "7") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUTab('7');">Special Reseller</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "8") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUTab('8');">Filter User</a>
    </li>
<!--    <li class="<?php echo (isset($subtab) && $subtab == "9") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUTab('9');">User/Reseller Consumption</a>
    </li>-->
</ul>
<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active">

        <?php if (isset($subtab) && $subtab == '1') { ?>

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
                                <td><?php echo (isset($total_resellers) && $total_resellers && isset($total_users) && $total_users) ? $total_resellers + $total_users : 0; ?></td>
                                <td><?php echo (isset($total_resellers) && $total_resellers) ? $total_resellers : 0; ?></td>
                                <td><?php echo (isset($total_users) && $total_users) ? $total_users : 0; ?></td>
                                <td><?php echo (isset($active_users) && $active_users) ? $active_users : 0; ?></td>
                                <td><?php echo (isset($demo_users) && $demo_users) ? $demo_users : 0; ?></td>
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
                         
                                  <th>Lead By</th>
                                    <th>Feedback</th>
                                    <th>Date</th>
                                <th>Account Type</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <?php
                            if (isset($users) && $users) {
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
                                         
                                            <td><?php echo $row['lead_by']; ?></td>
                                             <td><?php echo $row['feedback']; ?></td>
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

        <?php if (isset($subtab) && $subtab == '2') { ?>
            <div class="row">
                <form role="form" class="tab-forms" id="addUserForm" data-parsley-validate method='post' action="javascript:saveUser();">
                    <div class="col-md-4 padding0">
                        <div class="col-md-12 padding0">
                            <span id="show_message"></span>
                        </div>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" class="form-control" value="" name="name" id="name" placeholder="Please Enter Full Name" required=""
                                   data-parsley-pattern="^[A-Za-z]([-']?[A-Za-z]+)*( [A-Za-z]([-']?[A-Za-z]+)*)+$" 
                                   data-parsley-pattern-message="Please Enter First And Last Name" data-parsley-required-message="Please Enter Full Name" />
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="text" class="form-control" value="" name="contact" id="contact" data-parsley-type="integer"
                                   placeholder="Please Enter Contact Number" required=""
                                   data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-minlength-message="Please Enter Valid Contact Number"
                                   data-parsley-type-message="Please Enter Valid Contact Number" data-parsley-required-message="Please Enter Contact Number"
                                   data-parsley-maxlength-message="Please Enter Valid Contact Number"/>
                        </div>    
                        <div class="form-group">
                            <label>User Type</label>
                            <select name="user_type" id="user_type" class="form-control" required="" data-parsley-required-message="Please Select User Type">
                                <option value="">Select Type</option>
                                <option value="Reseller">Reseller</option>
                                <option value="User">User</option>
                            </select>
                        </div> 
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
                    <div class="col-md-4 padding0">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" value="" name="username" id="username" 
                                   placeholder="Please Enter Username" required="" 
                                   onkeyup="checkUsername(this.value, 'user')" data-parsley-minlength="5"  data-parsley-pattern="/^[A-Za-z][A-Za-z0-9]*$/"
                                   data-parsley-required-message="Please Enter Username" data-parsley-pattern-message="Username must be start with a character!"
                                   data-parsley-minlength-message="Username must be 5 characters long" />
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="text" class="form-control" value="" name="email" id="email" data-parsley-type="email" 
                                   placeholder="Please Enter Email Address" required=""
                                   data-parsley-type-message="Please Enter Valid Email Address" data-parsley-required-message="Please Enter Email Address" />
                        </div>
                        <div class="form-group">
                            <label>Company Name</label>
                            <input type="text" class="form-control" value="" name="company" id="company" 
                                   placeholder="Please Enter Company Name" data-parsley-required-message="Please Enter Company Name" required="" />
                        </div>  
                        <div class="form-group">
                            <label>Choose Expiry Date</label>
                            <input name="expiry_date" id="expiry_date" placeholder="Enter Expiry Date" class="form-control" required="" 
                                   data-parsley-error-message="Please Enter Expiry Date" type="text" value="">
                        </div>  
                    </div>
                    <div class="col-md-12 padding0">
                        <button type="submit" class="btn btn-primary" name="save" id="save"
                                data-loading-text="Processing..." autocomplete="off">Save</button>
                    </div>
                </form>
            </div>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '3') { ?>
            <!-- User SMPP Routing -->
            <div class="row" style="overflow-y: scroll; " >
                <div class="col-md-3 padding0">
                    <form role="form" class="tab-forms" id="smppPRRoutingForm" method='post' action="javascript:saveSMPPRouting('pr');">
                        <h4>Promotional Routing</h4>
                        <div class="form-group">
                            <label>Current Connected SMPP</label>
                            <select class="form-control" name="current_pr_route" id="current_pr_route" required=""
                                    data-parsley-required-message="Please Select SMPP">
                                <option value="">Select SMPP</option>
                                <?php
                                if (isset($pr_user_groups) && $pr_user_groups) {
                                    foreach ($pr_user_groups as $pr_ugroup) {
                                        ?>
                                        <option value="<?php echo $pr_ugroup['user_group_id']; ?>"><?php echo $pr_ugroup['user_group_name'] . " [" . $pr_ugroup['smsc_id'] . "]"; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>New Connected SMPP</label>
                            <select class="form-control" name="new_pr_route" id="new_pr_route" required=""
                                    data-parsley-required-message="Please Select SMPP">
                                <option value="">Select SMPP</option>
                                <?php
                                if (isset($pr_user_groups) && $pr_user_groups) {
                                    foreach ($pr_user_groups as $pr_ugroup) {
                                        ?>
                                        <option value="<?php echo $pr_ugroup['user_group_id']; ?>"><?php echo $pr_ugroup['user_group_name'] . " [" . $pr_ugroup['smsc_id'] . "]"; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm" id="btnprrouting" data-loading-text="Processing..." autocomplete="off">Save Routing</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-3 padding0">
                    <form role="form" class="tab-forms" id="smppTRRoutingForm" method='post' action="javascript:saveSMPPRouting('tr');">
                        <h4>Transactional Routing</h4>
                        <div class="form-group">
                            <label>Current Connected SMPP</label>
                            <select class="form-control" name="current_tr_route" id="current_tr_route" required="" data-parsley-required-message="Please Select SMPP">
                                <option value="">Select SMPP</option>
                                <?php
                                if (isset($tr_user_groups) && $tr_user_groups) {
                                    foreach ($tr_user_groups as $tr_ugroup) {
                                        ?>
                                        <option value="<?php echo $tr_ugroup['user_group_id']; ?>"><?php echo $tr_ugroup['user_group_name'] . " [" . $tr_ugroup['smsc_id'] . "]"; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>New Connected SMPP</label>
                            <select class="form-control" name="new_tr_route" id="new_tr_route" required="" data-parsley-required-message="Please Select SMPP">
                                <option value="">Select SMPP</option>
                                <?php
                                if (isset($tr_user_groups) && $tr_user_groups) {
                                    foreach ($tr_user_groups as $tr_ugroup) {
                                        ?>
                                        <option value="<?php echo $tr_ugroup['user_group_id']; ?>"><?php echo $tr_ugroup['user_group_name'] . " [" . $tr_ugroup['smsc_id'] . "]"; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm" id="btntrrouting" data-loading-text="Processing..." autocomplete="off">Save Routing</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-3 padding0">
                    <form role="form" class="tab-forms" id="smppTRRoutingForm" method='post' action="javascript:saveSMPPRouting('otp');">
                        <h4>OTP SMS Routing</h4>
                     <div class="form-group">
                            <label>New Connected SMPP</label>
                            <select class="form-control" name="new_otp_route" id="new_otp_route" required="" data-parsley-required-message="Please Select SMPP">
                                <option value="">Select SMPP</option>
                                <?php
                                if (isset($tr_user_groups) && $tr_user_groups) {
                                    foreach ($tr_user_groups as $tr_ugroup) {
                                        ?>
                                        <option value="<?php echo $tr_ugroup['user_group_id']; ?>"><?php echo $tr_ugroup['user_group_name'] . " [" . $tr_ugroup['smsc_id'] . "]"; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm" id="btntrrouting" data-loading-text="Processing..." autocomplete="off">Save Routing</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row" style="overflow-y: scroll; " >
                <div class="col-md-3 padding0">
                    <form role="form" class="tab-forms" id="smppTRRoutingForm" method='post' action="javascript:saveSMPPRouting('pr_bal');">
                        <h4>PR Balance(less 500) Routing</h4>
                        <div class="form-group">
                            <label>New Connected SMPP</label>
                            <select class="form-control" name="new_pr_bal_route" id="new_pr_bal_route" required=""
                                    data-parsley-required-message="Please Select SMPP">
                                <option value="">Select SMPP</option>
                                <?php
                                if (isset($pr_user_groups) && $pr_user_groups) {
                                    foreach ($pr_user_groups as $pr_ugroup) {
                                        ?>
                                        <option value="<?php echo $pr_ugroup['user_group_id']; ?>"><?php echo $pr_ugroup['user_group_name'] . " [" . $pr_ugroup['smsc_id'] . "]"; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm" id="btntrrouting" data-loading-text="Processing..." autocomplete="off">Save Routing</button>
                        </div>
                    </form> 
                </div>
                 <div class="col-md-1"></div>
                <div class="col-md-3 padding0">
                    <form role="form" class="tab-forms" id="smppTRRoutingForm" method='post' action="javascript:saveSMPPRouting('tr_bal');">
                        <h4>TR Balance(less 500) Routing</h4>
                        <div class="form-group">
                            <label>New Connected SMPP</label>
                            <select class="form-control" name="new_tr_bal_route" id="new_tr_bal_route" required="" data-parsley-required-message="Please Select SMPP">
                                <option value="">Select SMPP</option>
                                <?php
                                if (isset($tr_user_groups) && $tr_user_groups) {
                                    foreach ($tr_user_groups as $tr_ugroup) {
                                        ?>
                                        <option value="<?php echo $tr_ugroup['user_group_id']; ?>"><?php echo $tr_ugroup['user_group_name'] . " [" . $tr_ugroup['smsc_id'] . "]"; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm" id="btntrrouting" data-loading-text="Processing..." autocomplete="off">Save Routing</button>
                        </div>
                    </form> 
                </div>

            </div>

        <?php } ?>

        <?php if (isset($subtab) && $subtab == '4') { ?>
            <!-- Notify Users -->
            <div class="row">
                <div class="col-md-3 borderR">
                    <form role="form" id="notifySMSForm" method='post' action="javascript:notifyUsers('sms');" class="notify-forms">
                        <div class="form-group col-md-12 padding0">
                            <label for="notify_users">Select Users</label>
                        </div>
                        <div class="form-group col-md-12 padding0">                            
                            <select id="notify_users" name="notify_users[]" class="form-control" multiple="multiple" 
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
                            <label for="user_status">Status</label>
                            <select class="form-control" name="user_status" id="user_status" data-parsley-required-message="Please select route">
                                <option value="">Select Status</option>
                                <option value="Demo">Demo</option>
                                <option value="Active">Active</option>
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
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '5') { ?>
            <!-- Notify Users -->
            <div class="row">
                <div class="col-md-3 borderR">
                    <form role="form" id="notifyEmailForm" method='post' action="javascript:notifyUsers('email');" class="notify-forms">
                        <div class="form-group col-md-12 padding0">
                            <label for="notify_users">Select Users</label>
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <select id="notify_users" name="notify_users[]" class="form-control" multiple="multiple" 
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
                            <label for="user_status">Status</label>
                            <select class="form-control" name="user_status" id="user_status" data-parsley-required-message="Please select route">
                                <option value="">Select Status</option>
                                <option value="Demo">Demo</option>
                                <option value="Active">Active</option>
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
        <?php } ?>

        <?php if (isset($retry_user) || $subtab == '6') { ?>

            <div class="row">
                <div class="col-md-3 borderR">
                    <div class="form-group">
                        <h4>Select User For Retry</b></h4><br>
                        <form   method="post" action="javascript:saveRetryUser();">
                            <select class="form-control" name="users[]" id="multiple-checkboxes" multiple="multiple">

                                <?php
                                foreach ($retry_user as $username) {
                                    ?>
                                    <option value="<?php echo $username['user_id']; ?>"><?php echo $username['username']; ?></option>
                                <?php } ?>
                            </select><br><br>
                            <button type="submit" style="width: 140px;" class="btn btn-primary btn-sm" name="saveuser" data-loading-text="Processing..." autocomplete="off">
                                Save User
                            </button>
                        </form>

                    </div>
                </div>



                <div class="col-md-1"></div>
                <div class="col-md-1"></div>
                <div class="col-md-1"></div>
                <div class="col-md-3 borderR">
                    <div class="form-group">
                        <h4>Select Route for Retry</b></h4><br>
                        <form  method="post" action="javascript:saveRetryRoute();" >
                            <select class="form-control" name="route" id="retry_route">
                                <option>Select Retry Route</option>
                                <?php
                                foreach ($all_user_groups as $route) {
                                    ?>
                                    <option value="<?php echo $route['user_group_id']; ?>"><?php echo $route['user_group_name']; ?></option>
                                <?php } ?>
                            </select><br>
                            <button type="submit" style="width: 255px;" class="btn btn-primary btn-sm" name="saveroute" data-loading-text="Processing..." autocomplete="off">
                                Save Route
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        <?php }
        ?>

        <?php if (isset($users_admin) || $subtab == 7) { ?>

            <div class="input-group col-md-4">
                <input type="text" class="form-control search-form-stl" placeholder="Search Reseller" id="special_reseller" 
                       onkeyup="searchSpecialReseller(this.value);" autocomplete="off"  />                                           
                <span class="input-group-btn">
                    <button class="btn btn-default search-form-stl" type="button">
                        <i class="fa fa-search"></i> 
                    </button>
                </span>

            </div><br> 

            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-bordered table-striped" id="search_result">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Reseller Name</th>
                                <th>User Type</th>
                                <th>TR Balance</th>
                                <th>PR Balance</th>
                                <th>Special TR Balance</th>
                                <th>Special PR Balance</th>
                                <th>Update Special Reseller</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($users_admin) && $users_admin) {
                                $i = 1;
                                foreach ($users_admin AS $users1) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $users1['username']; ?></td>
                                        <td><?php echo $users1['utype']; ?></td>
                                        <td><?php echo $users1['tr_sms_balance']; ?></td>
                                        <td><?php echo $users1['pr_sms_balance']; ?></td>
                                        <td><?php echo $users1['special_tr_balance']; ?></td>
                                        <td><?php echo $users1['special_pr_balance']; ?></td>
                                        <?php if ($users1['spacial_reseller_status'] == 1) { ?>
                                            <td><button data-original-title="Click to approve" onclick="makeMeSpecialReseller('<?php echo $users1['user_id']; ?>', '<?php echo $users1['spacial_reseller_status']; ?>');" type="button" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Make Me Normal Reseller">Special Reseller</button></td>
                                        <?php } else { ?>
                                            <td><button data-original-title="Click to approve" onclick="makeMeSpecialReseller('<?php echo $users1['user_id']; ?>', '<?php echo $users1['spacial_reseller_status']; ?>');" type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Make Me Special Reseller">Make Me Special Reseller</button></td>
                                        <?php } ?>

                                    </tr>
                                    <?php
                                    $i++;
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
        <?php }
        ?>

        <?php if ($subtab == 8) { ?>
            <div class="row">
                <form role="form" id="signUpReportForm" method='post' action="javascript:getSignUpReport();" class="notify-forms">
                    <div class="col-md-3 padding15">
                        <div class="form-group">                         
                            <select name="admin_name" id="Parent_id" class="form-control"  data-parsley-required-message="Please Select Parent">
                                <option value="">Select Admin </option>
                                <?php
                                if (isset($admin) && $admin) {
                                    foreach ($admin as $admin_name) {
                                        ?>
                                        <option value="<?php echo $admin_name['admin_name']; ?>"><?php echo $admin_name['admin_name']; ?></option> 
                                        <?php
                                    }
                                }
                                ?>


                            </select>
                        </div> 
                    </div>
                    <div class="col-md-4 padding15">
                        <div class="input-daterange input-group" id="">
                            <input type="text" class="form-control " name="from_date" id="from_date" placeholder="YYYY-MM-DD">
                            <span class="input-group-addon">to</span>
                            <input type="text" class="form-control " name="to_date" id="to_date" placeholder="YYYY-MM-DD"> 

                        </div>
                    </div>



                    <div class="col-md-1 padding15">
                        <div class="form-group">   
                            <button name="get_report_btn" id="get_report_btn" class="btn btn-primary"
                                    data-loading-text="Searching..." autocomplete="off" type="submit">
                                Get Report
                            </button>
                        </div>                        
                    </div>
                </form>
            </div>

            <div class="row">
                <div class="table-responsive" id="show_users">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Email Address</th>
                                <th>User Type</th>
                                <th>Lead By</th>
                                 <th>Comment </th>
                                <th>Date</th>
                                <th>Account Type</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <?php
                            if (isset($users_report) && $users_report) {
                                foreach ($users_report as $row) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['username']; ?>
                                        </td>

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
                                        <td><?php echo $row['lead_by']; ?></td>
                                           <td><?php echo $row['feedback']; ?></td>
                                        
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

        <?php }
        ?>

        <?php if ($subtab == 9) { ?>
            <div class="row">
                <form role="form" id="getSmsReportData" method='post' action="javascript:getSmsReport();" class="notify-forms">

                    <div class="col-md-2 padding15">
                        <div class="form-group">
                            <!--<select id="notify_users" >-->
                            <select  id="notify_users" name="selected_user" class="form-control" multiple="multiple" 
                                     data-parsley-required-message="Please select at least one user">
                                         <?php
                                         if (isset($reseller_users) && $reseller_users) {
                                             ?>
                                    <optgroup label="Resellers">
                                        <?php
                                        foreach ($reseller_users as $reseller) {
                                            ?>
                                            <option value="<?php echo $reseller['user_id']; ?>"><?php echo $reseller['name'] . " (" . $reseller['username'] . ")"; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </optgroup>
                                    <?php
                                }
                                ?>
                            </select> 
                        </div>
                    </div>

                    <div class="col-md-2 padding15">
                        <div class="form-group">                         
                            <select name="user_type" class="form-control"  data-parsley-required-message="Please Select User Type">
                                <option value="">Select User Type</option>

                                <option value="0">User</option>
                                <option value="1">Reseller</option>
                            </select>
                        </div>  
                    </div>
                    <div class="col-md-2 padding15">
                        <div class="form-group">                         
                            <select name="route" class="form-control"  data-parsley-required-message="Please Select User Type">
                                <option value="">Select Route</option>

                                <option value="A">PR</option>
                                <option value="B">TR</option>
                                <option value="C">stock</option>
                                <option value="D">PR TO DND</option>
                            </select>
                        </div>  
                    </div>
                    <div class="col-md-3 padding15">
                        <div class="form-group">                         
                            <input class="form-control" value="" name="search_from_date" placeholder="YYYY-mm-dd"  type="text" id="search_from_date">
                            <span class="input-group-addon">to</span>
                            <input type="text" class="form-control" name="search_to_date" id="search_to_date" placeholder="Enter To Date" />
                        </div>  
                    </div>

                    <div class="col-md-1 padding15">
                        <button name="get_sms_report" id="get_sms_report" class="btn btn-primary" data-loading-text="Processing..." autocomplete="off" type="submit">
                            Get Report 
                        </button>         
                    </div>
                </form></div>

            <div class="row">
                <div class="table-responsive" id="show_report">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Sent</th>
                                <th>Actual Delivered</th>
                                <th>Actual Failed</th>
                                <th>Fake Deliver</th>
                                <th>Fake Failed</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <tr>
                                <td colspan="9" align="center">
                                    <strong>No Users</strong>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <?php // echo $paging;   ?>
                </div>
            </div>

        <?php }
        ?>


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
<script type="text/javascript">

    $(document).ready(function () {

        $('#multiple-checkboxes').multiselect({
            //includeSelectAllOption: true,
            //selectAllText: 'Select All',
            maxHeight: 300,
            enableFiltering: true,
            enableClickableOptGroups: true,
            enableCaseInsensitiveFiltering: true
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate());
        $('#datepicker').datetimepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });

        $('#datepicker1').datetimepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });
        $('#filter_by_date').datepicker({
            format: "yyyy-mm-dd",
            endDate: today,
            autoclose: true,
            todayHighlight: true
        });
        $('#by_users').multiselect({
            //includeSelectAllOption: true,
            //selectAllText: 'Select All',
            maxHeight: 300,
            enableFiltering: true,
            enableClickableOptGroups: true,
            enableCaseInsensitiveFiltering: true
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate());
        $('#search_from_date').datetimepicker({
            format: "yyyy-mm-dd hh:ii:00",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });
        $('#search_to_date').datetimepicker({
            format: "yyyy-mm-dd hh:ii:00",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });

    });
</script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
        $('#search_by_date').datepicker({
            format: "yyyy-mm-dd",
            endDate: today,
            autoclose: true,
            todayHighlight: true
        });

        $('#export_from_date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            endDate: today,
            showMeridian: true,
            todayHighlight: true
        });

        $('#export_to_date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            endDate: today,
            showMeridian: true,
            todayHighlight: true
        });
    });

</script>

<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate());
        $('#from_date').datetimepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });
        $('#to_date').datetimepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });

    });
</script>
