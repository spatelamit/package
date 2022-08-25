<div class="page-content-title txt-center">
    <h3><i class="fa fa-search"></i> Search User: <?php echo $username; ?></h3> 
</div>

<div id="user-mngmnt">
    <div class="container-fluid padding15">

        <div class="row" id="searchnew">
            <?php if ($user) { ?>
                <div class="col-md-12">
                    <div class="col-md-2">
                        <h4>User Info</h4>
                        <h5><i class="fa fa-user-plus"></i> <?php echo $user['username']; ?> </h5>
                        <h5><i class="fa fa-check-circle"></i> <?php echo ($user['parent_username'] == "") ? $user['admin_username'] : $user['parent_username']; ?></h5>
                        <h5><?php echo ($user['ref_username'] == "") ? "" : '<i class="fa fa-check-circle-o"></i> ' . $user['ref_username']; ?></h5>
                        <input type="hidden" id="user_id" value="<?php echo $user['user_id']; ?>" />
                        <input type="hidden" id="user_type" value="<?php echo $user['utype']; ?>" />
                        <input type="hidden" id="username" value="<?php echo $user['username']; ?>" />                            
                    </div>
                    <div class="col-md-2">
                        <h4>Basic Info</h4>
                        <h5><i class="fa fa-user"></i> <?php echo $user['name']; ?></h5>
                        <h5><i class="fa fa-life-ring"></i> <?php echo $user['utype']; ?></h5>
                    </div>
                    <div class="col-md-2">
                        <h4>Contact Info</h4>
                        <h5><i class="fa fa-phone"></i> <?php echo $user['contact_number']; ?></h5>
                        <h5><i class="fa fa-envelope"></i> <?php echo $user['email_address']; ?></h5>
                    </div>
                    <div class="col-md-2">
                        <h4>SMS Balance</h4>
                        <h5>
                            <div class="row">
                                <div class="col-md-2 col-xs-2 col-sm-2">
                                    <button class="btn btn-success btn-xs">P</button>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $user['pr_sms_balance']; ?>
                                </div>
                            </div>
                        </h5>
                        <h5>
                            <div class="row">
                                <div class="col-md-2 col-xs-2 col-sm-2">
                                    <button class="btn btn-primary btn-xs">T</button>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $user['tr_sms_balance']; ?>
                                </div>
                            </div>
                        </h5>
                    </div>
                    <div class="col-md-2">
                        <h4>Long/Short Balance</h4>
                        <h5>
                            <div class="row">
                                <div class="col-md-2 col-xs-2 col-sm-2">
                                    <button class="btn btn-success btn-xs">L</button>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $user['long_code_balance']; ?>
                                </div>
                            </div>
                        </h5>
                        <h5>
                            <div class="row">
                                <div class="col-md-2 col-xs-2 col-sm-2">
                                    <button class="btn btn-primary btn-xs">S</button>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $user['short_code_balance']; ?>
                                </div>
                            </div>
                        </h5>
                    </div>
                    <!--
                    <div class="col-md-2">
                        <h4>Overall Balance</h4>
                        <h5><button class="btn btn-success btn-xs">P</button> <?php //echo $user['total_pr_balance'];                                 ?> SMS</h5>
                        <h5><button class="btn btn-primary btn-xs">T</button> <?php //echo $user['total_tr_balance'];                                 ?> SMS</h5>
                    </div>
                    -->
                    <div class="col-md-2 text-right">
                        <!--
                        <h5>
                            <button class="btn btn-<?php echo ($user['user_status'] == 0) ? "success" : "warning"; ?> btn-xs" type="button" 
                                    onclick="enableDisableItem('user', <?php echo ($user['user_status'] == 0) ? "1" : "0"; ?>);">
                        <?php echo ($user['user_status'] == 0) ? "Active" : "Deactive"; ?>
                            </button>
                        </h5>
                        -->
                        <h5>
                            <button class="btn btn-<?php echo ($user['user_status'] == 0) ? "success" : "danger"; ?> btn-xs" type="button" 
                                    onclick="blockReseller(<?php echo ($user['user_status'] == 0) ? "1" : "0"; ?>);">
                                        <?php echo ($user['user_status'] == 0) ? "Un-Block" : "Block"; ?>
                            </button>
                        </h5>
                        <h5>
                            <a href="<?php echo base_url(); ?>admin/login_as/<?php echo $user['user_id']; ?>" class="btn btn-success btn-xs">
                                Login As
                            </a>
                        </h5>
                    </div>
                </div>

                <div class="col-md-12 padding0" id="user_tab">

                    <nav class="navbar navbar-default" role="navigation">
                        <div class="container-fluid">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed radius0" data-toggle="collapse" data-target="#user-navbar-collapse-1">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <div class="collapse navbar-collapse" id="user-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <li class="<?php echo (isset($user_tab) && $user_tab == 1) ? "active" : ""; ?>">
                                        <a href="javascript:void(0)" onclick="getUserTab('user', '1');">User Setting</a>
                                    </li>
                                    <li class="<?php echo (isset($user_tab) && $user_tab == 2) ? "active" : ""; ?>">
                                        <a href="javascript:void(0)" onclick="getUserTab('user', '2');">Rule Setting</a>
                                    </li>
                                    <li class="<?php echo (isset($user_tab) && $user_tab == 3) ? "active" : ""; ?>">
                                        <a href="javascript:void(0)" onclick="getUserTab('user', '3');">Fund Transfer</a>
                                    </li>
                                    <li class="<?php echo (isset($user_tab) && $user_tab == 4) ? "active" : ""; ?>">
                                        <a href="javascript:void(0)" onclick="getUserTab('user', '4');">User Profile</a>
                                    </li>
                                    <li class="<?php echo (isset($user_tab) && $user_tab == 5) ? "active" : ""; ?>">
                                        <a href="javascript:void(0)" onclick="getUserTab('user', '5');">Reset Password</a>
                                    </li>
                                    <li class="<?php echo (isset($user_tab) && $user_tab == 6) ? "active" : ""; ?>">
                                        <a href="javascript:void(0)" onclick="getUserTab('user', '6');">Low Balance Alert</a>
                                    </li>
                                    <li class="<?php echo (isset($user_tab) && $user_tab == 7) ? "active" : ""; ?>">
                                        <a href="javascript:void(0)" onclick="getUserTab('user', '7');">Short/Long Codes</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>

                    <?php if ($user_tab == 1) { ?>
                        <!-- User SMPP Routing -->
                        <div class="col-md-2">
                            <form role="form" class="tab-forms" id="smppRoutingForm" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'routing');">
                                <div class="col-md-12">
                                    <h4>SMPP Routing</h4>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Promotional Route</label>
                                        <select class="form-control" name="pr_route" id="pr_route">
                                            <option value="">Select User Group</option>
                                            <?php
                                            if ($pr_user_groups) {
                                                foreach ($pr_user_groups as $pr_ugroup) {
                                                    if ($user['pro_user_group_id'] == $pr_ugroup['user_group_id']) {
                                                        ?>
                                                        <option value="<?php echo $pr_ugroup['user_group_id']; ?>" selected="">
                                                            <?php echo $pr_ugroup['user_group_name']; ?>
                                                        </option>    
                                                        <?php
                                                    } else {
                                                        ?>  
                                                        <option value="<?php echo $pr_ugroup['user_group_id']; ?>">
                                                            <?php echo $pr_ugroup['user_group_name']; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Transactional Route</label>
                                        <select class="form-control" name="tr_route" id="tr_route">
                                            <option value="">Select User Group</option>
                                            <?php
                                            if ($tr_user_groups) {
                                                foreach ($tr_user_groups as $tr_ugroup) {
                                                    if ($user['tr_user_group_id'] == $tr_ugroup['user_group_id']) {
                                                        ?>
                                                        <option value="<?php echo $tr_ugroup['user_group_id']; ?>" selected="">
                                                            <?php echo $tr_ugroup['user_group_name']; ?></option>    
                                                        <?php
                                                    } else {
                                                        ?>  
                                                        <option value="<?php echo $tr_ugroup['user_group_id']; ?>">
                                                            <?php echo $tr_ugroup['user_group_name']; ?></option>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" id="btnrouting<?php echo $user_tab; ?>" class="btn btn-primary btn-xs" data-loading-text="Processing..." autocomplete="off">Save Routing</button>
                                </div>
                            </form>
                        </div>

                        <!-- User Ratio -->
                        <div class="col-md-2">
                            <form role="form" class="tab-forms" id="ratioTRForm" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'trratio');">
                                <div class="col-md-12">
                                    <h4>Transactional Ratio</h4>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Set Overall Ratio</label>
                                        <div class="input-group">
                                            <input type="text" name="user_ratio" value="<?php echo $user['user_ratio']; ?>" id="user_ratio" placeholder="User Overall Ratio " 
                                                   class="form-control" data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                                            <div class="input-group-addon">%</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Fake Delivered Ratio</label>
                                        <div class="input-group">
                                            <input type="text" name="user_fake_ratio" value="<?php echo $user['user_fake_ratio']; ?>" id="user_fake_ratio" placeholder="User Fake Delivered Ratio" class="form-control"
                                                   data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                                            <div class="input-group-addon">%</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Fake Failed Ratio</label>
                                        <div class="input-group">
                                            <input type="text" name="user_fail_ratio" value="<?php echo $user['user_fail_ratio']; ?>" id="user_fail_ratio" placeholder="User Fake Failed Ratio" class="form-control"
                                                   data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                                            <div class="input-group-addon">%</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="ratio_type" value="transactional" />
                                    <button type="submit" id="btntrratio<?php echo $user_tab; ?>" class="btn btn-primary btn-xs" data-loading-text="Processing..." autocomplete="off">Set Ratio</button>
                                </div>
                            </form>
                        </div>

                        <!-- User Ratio -->
                        <div class="col-md-2">
                            <form role="form" class="tab-forms" id="ratioPRForm" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'prratio');">
                                <div class="col-md-12">
                                    <h4>Promotional Ratio</h4>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Set Overall Ratio</label>
                                        <div class="input-group">
                                            <input type="text" name="user_ratio" value="<?php echo $user['pr_user_ratio']; ?>" id="user_ratio" placeholder="User Overall Ratio " 
                                                   class="form-control" data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                                            <div class="input-group-addon">%</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Fake Delivered Ratio</label>
                                        <div class="input-group">
                                            <input type="text" name="user_fake_ratio" value="<?php echo $user['pr_user_fake_ratio']; ?>" id="user_fake_ratio" placeholder="User Fake Delivered Ratio" class="form-control"
                                                   data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                                            <div class="input-group-addon">%</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Fake Failed Ratio</label>
                                        <div class="input-group">
                                            <input type="text" name="user_fail_ratio" value="<?php echo $user['pr_user_fail_ratio']; ?>" id="user_fail_ratio" placeholder="User Fake Failed Ratio" class="form-control"
                                                   data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                                            <div class="input-group-addon">%</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="ratio_type" value="promotional" />
                                    <button type="submit" id="btnprratio<?php echo $user_tab; ?>" class="btn btn-primary btn-xs" data-loading-text="Processing..." autocomplete="off">Set Ratio</button>
                                </div>
                            </form>
                        </div>

                        <!-- User Expiry -->
                        <div class="col-md-3">
                            <form role="form" class="tab-forms" id="expiryForm" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'set_expiry');">
                                <div class="col-md-12">
                                    <h4>Set Expiry</h4>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Expiry Date</label>
                                        <input type="text" required="" name="expiry_date" value="<?php echo $user['expiry_date']; ?>" id="expiry_date" 
                                               placeholder="Expiry Date" class="form-control" data-parsley-required-message="Please Enter Date" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" id="btnset_expiry<?php echo $user_tab; ?>" class="btn btn-primary btn-xs" data-loading-text="Processing..." autocomplete="off">Set Expiry</button>
                                    <button type="button" class="btn btn-inverse btn-xs" id="btnremove_expiry<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off"
                                            onclick="saveUserInfo('user', <?php echo $user_tab; ?>, 'remove_expiry');">Remove Expiry</button>
                                </div>
                            </form>
                        </div>

                        <!-- Account Type -->
                        <div class="col-md-3">
                            <div class="col-md-12">
                                <form role="form" class="tab-forms" id="accountForm" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'account_type');">
                                    <div class="col-md-12">
                                        <h4>Account Type</h4>
                                    </div>
                                    <div class="col-md-12 padding0">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label for="account_type" class="fancy-check">
                                                    <input type="checkbox" name="account_type" id="account_type"
                                                           value="1" <?php echo ($user['check_demo_user']) ? "checked" : ""; ?> />
                                                    <span>Demo Account</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-xs" id="btnaccount_type<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Save</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12">
                                <form role="form" class="tab-forms" id="verifyForm" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'verify_location');">
                                    <div class="col-md-12">
                                        <h4>Verify User Location</h4>
                                    </div>
                                    <div class="col-md-12 padding0">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label for="check_verify_location" class="fancy-check">
                                                    <input type="checkbox" name="check_verify_location" id="check_verify_location"
                                                           value="1" <?php echo ($user['check_verification']) ? "checked" : ""; ?> />
                                                    <span>Check</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-xs" id="btnverify_location<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

        $('#expiry_date').datepicker({
            format: "dd-mm-yyyy",
            startDate: today
        });

        $('#dob').datepicker({
            format: "dd-mm-yyyy",
            endDate: today
        });
    });
    $('.tab-forms').parsley();
</script>