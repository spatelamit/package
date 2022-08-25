<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getAccountTab('1');">Account Managers</a>
    </li>
    <?php
    if ($atype == 1 && !isset($account_manager)) {
        ?>
        <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
            <a href="javascript:void(0)" onclick="getAccountTab('2');">Add Account Manager</a>
        </li>
        <li class="<?php echo (isset($subtab) && $subtab == "3") ? 'active"' : '' ?>">
            <a href="javascript:void(0)" onclick="getAccountTab('3');">Transfer Balance</a>
        </li>
        <li class="<?php echo (isset($subtab) && $subtab == "4") ? 'active"' : '' ?>">
            <a href="javascript:void(0)" onclick="getAccountTab('4');">Reset Password</a>
        </li>
        <?php
    } elseif (isset($account_manager)) {
        ?>
        <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
            <a href="javascript:void(0)" onclick="getAccountTab('2');">Update Account Manager</a>
        </li>
        <?php
    }
    ?>
</ul>
<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active">

        <?php if ($subtab == '1') { ?>
            <div class="row">
                <div class="table-responsive" id="show_account_managers">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2">Name</th>
                                <th rowspan="2">Username</th>
                                <th rowspan="2">Contact Number</th>
                                <th rowspan="2">Email Address</th>
                                <th rowspan="2">Role</th>
                                <th class="text-center" colspan="2">Balance</th>
                                <th rowspan="2">Status</th>
                                <th rowspan="2" colspan="3">Action</th>
                            </tr>
                            <tr>
                                <th>PR Balance</th>
                                <th>TR Balance</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <?php
                            if ($account_managers) {
                                $i = 1;
                                foreach ($account_managers as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['admin_name']; ?></td>
                                        <td><?php echo $row['admin_username']; ?></td>
                                        <td><?php echo $row['admin_contact']; ?></td>
                                        <td><?php echo $row['admin_email']; ?></td>
                                        <td>
                                            <?php if ($row['atype'] == 1) { ?>
                                                Administrator
                                            <?php } elseif ($row['atype'] == 2) { ?>
                                                Sub-Administrator
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $row['total_pr_balance']; ?></td>
                                        <td><?php echo $row['total_tr_balance']; ?></td>
                                        <td>
                                            <?php if ($row['admin_status']) { ?>
                                                <span class="label label-success">Enabled</span>
                                            <?php } else { ?>
                                                <span class="label label-danger">Disabled</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['admin_id'] != $admin_id) {
                                                if ($atype == 1) {
                                                    ?>
                                                    <div class="switch switch-success switch-sm round switch-inline">
                                                        <?php
                                                        if ($row['admin_status']) {
                                                            ?>
                                                            <input type="checkbox" id="status<?php echo $i; ?>" checked
                                                                   onclick="changeAMStatus('<?php echo $row['admin_id']; ?>', 0);" />
                                                                   <?php
                                                               } else {
                                                                   ?>
                                                            <input type="checkbox" id="status<?php echo $i; ?>" 
                                                                   onclick="changeAMStatus('<?php echo $row['admin_id']; ?>', 1);" />
                                                                   <?php
                                                               }
                                                               ?>
                                                        <label for="status<?php echo $i; ?>"></label>
                                                    </div>
                                                    <?php
                                                } else {
                                                    echo "-";
                                                }
                                            } else {
                                                echo "-";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <button onclick="updateAManager('<?php echo $row['admin_id']; ?>');"
                                                    type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['admin_id'] != $admin_id) {
                                                if ($atype == 1) {
                                                    ?>
                                                    <button onclick="deleteAManager('<?php echo $row['admin_id']; ?>');"
                                                            type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                    <?php
                                                } else {
                                                    echo "-";
                                                }
                                            } else {
                                                echo "-";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="11" align="center">
                                        <strong>No Account Manager</strong>
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

        <?php
        if ($subtab == '2') {
            if (isset($account_manager)) {
                ?>
                <div class="row">
                    <div class="col-md-8 padding0">
                        <div class="table-responsive">
                            <form role="form" class="tab-forms" id="addAccMForm" data-parsley-validate method='post' 
                                  action="javascript:saveAccountManager(<?php echo (isset($account_manager)) ? $account_manager->admin_id : ''; ?>);">
                                <div class="row">
                                    <div class="col-md-6 padding0">
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Please Enter Full Name" required=""
                                                   data-parsley-pattern="^[A-Za-z]([-']?[A-Za-z]+)*( [A-Za-z]([-']?[A-Za-z]+)*)+$" 
                                                   data-parsley-pattern-message="Please Enter First And Last Name" data-parsley-required-message="Please Enter Full Name" 
                                                   value="<?php echo (isset($account_manager)) ? $account_manager->admin_name : ''; ?>" />
                                        </div>    
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Company Name</label>
                                            <input type="text" class="form-control" name="company" id="company" 
                                                   placeholder="Please Enter Company Name" data-parsley-required-message="Please Enter Company Name" required=""
                                                   value="<?php echo (isset($account_manager)) ? $account_manager->admin_company : ''; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 padding0">
                                        <div class="form-group">
                                            <label>Contact Number</label>
                                            <input type="text" class="form-control" name="contact" id="contact" data-parsley-type="integer"
                                                   placeholder="Please Enter Contact Number" required=""
                                                   data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-minlength-message="Please Enter Valid Contact Number"
                                                   data-parsley-type-message="Please Enter Valid Contact Number" data-parsley-required-message="Please Enter Contact Number"
                                                   data-parsley-maxlength-message="Please Enter Valid Contact Number"
                                                   value="<?php echo (isset($account_manager)) ? $account_manager->admin_contact : ''; ?>" />
                                        </div>    
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="text" class="form-control" name="email" id="email" data-parsley-type="email" 
                                                   placeholder="Please Enter Email Address" required=""
                                                   data-parsley-type-message="Please Enter Valid Email Address" data-parsley-required-message="Please Enter Email Address"
                                                   value="<?php echo (isset($account_manager)) ? $account_manager->admin_email : ''; ?>" />
                                        </div>    
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 padding0">
                                        <div class="form-group">
                                            <label>User Type</label>
                                            <select name="user_type" id="user_type" class="form-control" required="" data-parsley-required-message="Please Select User Type">
                                                <option value="" <?php echo (isset($account_manager) && $account_manager->atype == "") ? 'selected' : ""; ?>>Select Type</option>
                                                <option value="1" <?php echo (isset($account_manager) && $account_manager->atype == 1) ? "selected" : ''; ?>>Administrator</option>
                                                <option value="2" <?php echo (isset($account_manager) && $account_manager->atype == 2) ? "selected" : ''; ?>>Sub-Administrator</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Choose Expiry Date</label>
                                            <input name="expiry_date" id="expiry_date" placeholder="Enter Expiry Date" class="form-control" required="" 
                                                   data-parsley-error-message="Please Enter Expiry Date" type="text" 
                                                   value="<?php echo (isset($account_manager)) ? $account_manager->expiry_date : ''; ?>" />
                                        </div> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 padding0">
                                        <button type="submit" class="btn btn-primary btn-sm" name="save" id="save" data-loading-text="Processing..."
                                                autocomplete="off">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="row">
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <form role="form" class="tab-forms" id="addAccMForm" data-parsley-validate method='post' action="javascript:saveAccountManager(0);">
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
                                                   onkeyup="checkUsername(this.value, 'admin')" data-parsley-minlength="5"  data-parsley-pattern="/^[a-zA-Z0-9._]+$/"
                                                   data-parsley-required-message="Please Enter Username" data-parsley-pattern-message="Please Enter Valid Username"
                                                   data-parsley-minlength-message="Username must be 5 characters long"/>
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
                                                <option value="1">Administrator</option>
                                                <option value="2">Sub-Administrator</option>
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
                                            <label>Choose Expiry Date</label>
                                            <input name="expiry_date" id="expiry_date" placeholder="Enter Expiry Date" class="form-control" required="" 
                                                   data-parsley-error-message="Please Enter Expiry Date" type="text" value="">
                                        </div>    
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 padding0">
                                        <button type="submit" class="btn btn-primary btn-sm" name="save" id="save" data-loading-text="Processing..."
                                                autocomplete="off">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>      
        <?php } ?>

        <?php if ($subtab == '3') { ?>
            <div class="row">
                <div class="col-md-4 padding0">
                    <div class="table-responsive">
                        <form role="form" class="tab-forms" id="transferBalForm" data-parsley-validate method='post' action="javascript:transferBalance();">
                            <div class="col-md-12 padding0">
                                <div class="form-group">
                                    <label>Select Account Manager</label>
                                    <select name="account_manager" id="account_manager" class="form-control" required=""
                                            data-parsley-required-message="Please Select Account Manager">
                                        <option value="">Select Account Manager</option>
                                        <?php
                                        if (isset($account_managers)) {
                                            foreach ($account_managers AS $account_manager) {
                                                ?>
                                                <option value="<?php echo $account_manager['admin_id']; ?>">
                                                    <?php echo $account_manager['admin_name'] . " (" . $account_manager['admin_username'] . ")"; ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 padding0">
                                <div class="form-group">
                                    <label>Select Balance Type</label>
                                    <select name="balance_type" id="balance_type" class="form-control" required=""
                                            data-parsley-required-message="Please Select Balance Type">
                                        <option value="">Select Balance Type</option>
                                        <option value="A">Promotional SMS</option>
                                        <option value="B">Transactional SMS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 padding0">
                                <div class="form-group">
                                    <label>Select Action</label>
                                    <select name="action_type" id="action_type" class="form-control" required=""
                                            data-parsley-required-message="Please Select Action">
                                        <option value="">Select Action</option>
                                        <option value="Add">Add</option>
                                        <option value="Reduce">Reduce</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 padding0">
                                <div class="form-group">
                                    <label>Balance</label>
                                    <input type="text" name="balance" id="balance" placeholder="Please Enter Balance" class="form-control"
                                           required="" data-parsley-required-message="Please Enter Balance"
                                           data-parsley-type="digits" data-parsley-type-message="Balance must be a number" data-parsley-min="1"
                                           data-parsley-min-message="Balance must be greater than 0" />
                                </div>
                            </div>
                            <div class="col-md-12 padding0">
                                <button type="submit" class="btn btn-primary btn-sm" name="save" id="save"
                                        data-loading-text="Processing..." autocomplete="off">Transfer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ($subtab == '4') { ?>
            <div class="row">
                <div class="col-md-4 padding0">
                    <div class="table-responsive">
                        <form role="form" class="tab-forms" id="resetPwdForm" data-parsley-validate method='post' action="javascript:resetAccPassword();">
                            <div class="col-md-12 padding0">
                                <h4>Reset Password</h4>
                            </div>
                            <div class="col-md-12 padding0">
                                <div class="form-group">
                                    <label>Select Account Manager</label>
                                    <select name="account_manager" id="account_manager" class="form-control" required=""
                                            data-parsley-required-message="Please Select Account Manager">
                                        <option value="">Select Account Manager</option>
                                        <?php
                                        if (isset($account_managers)) {
                                            foreach ($account_managers AS $account_manager) {
                                                ?>
                                                <option value="<?php echo $account_manager['admin_id']; ?>">
                                                    <?php echo $account_manager['admin_name'] . " (" . $account_manager['admin_username'] . ")"; ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 padding0">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="am_password" id="am_password" placeholder="Please Enter Password" class="form-control"
                                           required="" data-parsley-required-message="Please Enter Password" />
                                </div>
                            </div>
                            <div class="col-md-12 padding0">
                                <button type="submit" class="btn btn-primary btn-sm" name="save" id="save"
                                        data-loading-text="Processing..." autocomplete="off">Reset Password</button>
                            </div>
                        </form>
                    </div>
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
            startDate: today
        });
    });
    $('.tab-forms').parsley();

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>