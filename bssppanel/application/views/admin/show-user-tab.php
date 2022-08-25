<style>

    .main-sidebar-right{
        display: none;
    }

</style>


<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span> 

<?php if (isset($user) && $user) { ?>
    <div class="col-md-12" style="overflow-y: scroll; " >
        <div style="width: 130px; float: left;">
            <h4>User Info</h4>
            <h5><i class="fa fa-user-plus"></i> <?php echo $user['username']; ?> </h5>
            <h5><i class="fa fa-check-circle"></i> <?php echo ($user['parent_username'] == "") ? $user['admin_username'] : $user['parent_username']; ?></h5>
            <h5><i class="fa fa-globe"></i> <b><?php echo $user['country']; ?></b> </h5>
            <h5><?php echo ($user['ref_username'] == "") ? "" : '<i class="fa fa-check-circle-o"></i> ' . $user['ref_username']; ?></h5>
            <input type="hidden" id="user_id" value="<?php echo $user['user_id']; ?>" />
            <input type="hidden" id="user_type" value="<?php echo $user['utype']; ?>" />
            <input type="hidden" id="username" value="<?php echo $user['username']; ?>" />                            
        </div>

        <div  style="width: 150px; float: left;">
            <h4>Basic Info</h4>
            <h5><i class="fa fa-user"></i> <?php echo $user['name']; ?></h5>
            <?php if ($user['spacial_reseller_status'] == 1) { ?>
                <h5><i class="fa fa-life-ring"></i> <?php echo "Special Reseller"; ?></h5>
            <?php } else { ?>
                <h5><i class="fa fa-life-ring"></i> <?php echo $user['utype']; ?></h5>
            <?php } ?>

        </div>
        <div  style="width: 225px; float: left;">
            <h4>Contact Info</h4>
            <h5><i class="fa fa-phone"></i> <?php echo $user['contact_number']; ?></h5>
            <h5><i class="fa fa-envelope"></i> <?php echo $user['email_address']; ?></h5>
        </div>
        <div  style="width: 150px; float: left;">
            <h4>SMS Balance</h4>
            <h5>
                <div class="row">
                    <div class="col-md-2 col-xs-2 col-sm-2">
                        <button class="btn btn-success btn-xs">P</button>
                    </div>
                    <div class="col-md-6 col-xs-6 col-sm-6">
                        <?php echo $user['pr_sms_balance']; ?>
                    </div>
                </div>
            </h5>
            <h5>
                <div class="row">
                    <div class="col-md-2 col-xs-2 col-sm-2">
                        <button class="btn btn-primary btn-xs">T</button>
                    </div>
                    <div class="col-md-6 col-xs-6 col-sm-6">
                        <?php echo $user['tr_sms_balance']; ?>
                    </div>
                </div>
            </h5>
            <h5>
                <div class="row">
                    <div class="col-md-2 col-xs-2 col-sm-2">
                        <button class="btn btn-primary btn-xs">I</button>
                    </div>
                    <div class="col-md-6 col-xs-6 col-sm-6">
                        <?php echo $user['international_balance']; ?>
                    </div>
                </div>
            </h5>
        </div>
        <div  style="width: 150px; float: left;">
            <h4>Stock/DND</h4>
            <h5>
                <div class="row">
                    <div class="col-md-2 col-xs-2 col-sm-2">
                        <button class="btn btn-success btn-xs">C</button>
                    </div>
                    <div class="col-md-6 col-xs-6 col-sm-6">
                        <?php echo $user['stock_balance']; ?>
                    </div>
                </div>
            </h5>
            <h5>
                <div class="row">
                    <div class="col-md-2 col-xs-2 col-sm-2">
                        <button class="btn btn-primary btn-xs">D</button>
                    </div>
                    <div class="col-md-6 col-xs-6 col-sm-6">
                        <?php echo $user['prtodnd_balance']; ?>
                    </div>
                </div>
            </h5>
        </div>


        <div  style="width: 200px; float: left;">
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
                    <div class="col-md-6 col-xs-6 col-sm-6">
                        <?php echo $user['short_code_balance']; ?>
                    </div>
                </div>
            </h5>
        </div>
        <div  style="width: 130px; float: left;">
            <h4>Voice Balance</h4>
            <h5>
                <div class="row">
                    <div class="col-md-2 col-xs-2 col-sm-2">
                        <button class="btn btn-success btn-xs">P</button>
                    </div>
                    <div class="col-md-6">
                        <?php echo $user['pr_voice_balance']; ?>
                    </div>
                </div>
            </h5>
            <h5>
                <div class="row">
                    <div class="col-md-2 col-xs-2 col-sm-2">
                        <button class="btn btn-primary btn-xs">D</button>
                    </div>
                    <div class="col-md-6 col-xs-6 col-sm-6">
                        <?php echo $user['tr_voice_balance']; ?>
                    </div>
                </div>
            </h5>
        </div>
        <!--
        <div class="col-md-2">
            <h4>Overall Balance</h4>
            <h5><button class="btn btn-success btn-xs">P</button> <?php echo $user['pr_sms_balance']; ?> SMS</h5>
            <h5><button class="btn btn-primary btn-xs">T</button> <?php echo $user['tr_sms_balance']; ?> SMS</h5>
        </div>
        -->
        <div   style="width: 120px;">
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
                <a href="<?php echo base_url(); ?>admin/login_as/<?php echo $user['user_id'] . "/" . $admin_id; ?>" class="btn btn-success btn-xs">
                    Login As
                </a>
            </h5>
        </div>
    </div>

    <div class="col-md-12 padding0" id="user_tab">

        <!-- User Nav -->
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
                        <li class="<?php echo (isset($user_tab) && $user_tab == 8) ? "active" : ""; ?>">
                            <a href="javascript:void(0)" onclick="getUserTab('user', '8');">Voice SMS</a>
                        </li>
                        <?php if ($user['spacial_reseller_status'] == 1) { ?>
                            <li class="<?php echo (isset($user_tab) && $user_tab == 9) ? "active" : ""; ?>">
                                <a href="javascript:void(0)" onclick="getUserTab('user', '9');">Add Credits</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- User Setting -->
        <?php if (isset($user_tab) && $user_tab == 1) { ?>

            <!-- User SMPP Routing -->
            <div class="col-md-2">
                <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>routing" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'routing');">
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
                                                <?php echo $tr_ugroup['user_group_name']; ?>
                                            </option>    
                                            <?php
                                        } else {
                                            ?>  
                                            <option value="<?php echo $tr_ugroup['user_group_id']; ?>">
                                                <?php echo $tr_ugroup['user_group_name']; ?>
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
                            <label>Premium Route</label>
                            <select class="form-control" name="prtodnd_sms_route" id="prtodnd_sms_route">
                                <option value="">Select User Group</option>
                                <?php
                                if ($pr_user_groups) {
                                    foreach ($pr_user_groups as $pr_ugroup) {
                                        if ($user['prtodnd_route'] == $pr_ugroup['user_group_id']) {
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
                            <label>STOCK Route</label>
                            <select class="form-control" name="stock_sms_route" id="stock_sms_route">
                                <option value="">Select User Group</option>
                                <?php
                                if ($pr_user_groups) {
                                    foreach ($pr_user_groups as $pr_ugroup) {
                                        if ($user['stock_route'] == $pr_ugroup['user_group_id']) {
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
                            <label>International Route</label>
                            <select class="form-control" name="international_route" id="stock_sms_route">
                                <option value="">Select User Group</option>
                                <?php
                                if ($pr_user_groups) {
                                    foreach ($pr_user_groups as $pr_ugroup) {
                                        if ($user['international_route'] == $pr_ugroup['user_group_id']) {
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
                        <button type="submit" class="btn btn-primary btn-xs" id="btnrouting<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Save Routing</button>
                    </div>
                </form>
            </div>

            <!-- User TR Ratio -->
            <div class="col-md-2">
                <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>trratio" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'trratio');">
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
                        <div class="form-group">
                            <label>Fake Sent Ratio</label>
                            <div class="input-group">
                                <input type="text" name="tr_fake_sent" value="<?php echo $user['tr_fake_sent']; ?>" id="user_fail_ratio" placeholder="User Fake Failed Ratio" class="form-control"
                                       data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                                <div class="input-group-addon">%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Ratio Description</label>
                            <div class="input-group">
                                <textarea  name="ratio_discription" id="tr_ratio_discription"  class="form-control" required="required"><?php echo $user['tr_ratio_discription']; ?></textarea>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="ratio_type" value="transactional" />
                        <button type="submit" class="btn btn-primary btn-xs" id="btntrratio<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Set Ratio</button>
                    </div>
                </form>
                <form role="form" class="tab-forms"  method='post' id="userSettingForm<?php echo $user_tab; ?>atrratio" action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'atrratio');">
                    <?php if ($admin_id == 1 || $admin_id == 6) {
                        ?>
                        <div class="col-md-12">

                            <label>Admin Description</label>
                            <textarea  name="admin_discription" id="admin_discription"  class="form-control" required="required"><?php echo $user['admin_discription_tr']; ?></textarea>
                            <input type="hidden" name="admin_value" value="tr" /> 
                            <button type="submit" class="btn btn-primary btn-xs" id="btnatrratio<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Admin Approve</button>

                        </div>
                    <?php }
                    ?>
                </form>
            </div>

            <!-- User PR Ratio -->
            <div class="col-md-2">
                <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>prratio" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'prratio');">
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
                    </div><div class="col-md-12">
                        <div class="form-group">
                            <label>Fake Sent Ratio</label>
                            <div class="input-group">
                                <input type="text" name="pr_fake_sent" value="<?php echo $user['pr_fake_sent']; ?>" id="user_fail_ratio" placeholder="User Fake Failed Ratio" class="form-control"
                                       data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                                <div class="input-group-addon">%</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Ratio Description</label>
                            <div class="input-group">
                                <textarea  name="ratio_discription"  id="pr_ratio_discription"  class="form-control" required="required" ><?php echo $user['pr_ratio_discription']; ?></textarea>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="ratio_type" value="promotional" />
                        <button type="submit" class="btn btn-primary btn-xs" id="btnprratio<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Set Ratio</button>
                    </div>
                </form>
                <form role="form" class="tab-forms"  method='post' id="userSettingForm<?php echo $user_tab; ?>aprratio" action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'aprratio');">
                    <?php if ($admin_id == 1 || $admin_id == 6) {
                        ?>
                        <div class="col-md-12">

                            <label>Admin Description</label>
                            <textarea  name="admin_discription"  id="admin_discription"  class="form-control" required="required" ><?php echo $user['admin_discription_pr']; ?></textarea>
                            <input type="hidden" name="admin_value" value="pr" /> 
                            <button type="submit" class="btn btn-primary btn-xs" id="btnaprratio<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Admin Approve</button>

                        </div>
                    <?php }
                    ?>
                </form>
            </div>


            <!-- User Expiry -->
            <div class="col-md-3">
                <div class="col-md-12 padding0">
                    <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>set_expiry" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'set_expiry');">
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
                            <button type="submit" class="btn btn-primary btn-xs" id="btnset_expiry<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Set Expiry</button>
                            <button type="button" class="btn btn-inverse btn-xs" id="btnremove_expiry<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off"
                                    onclick="saveUserInfo('user', <?php echo $user_tab; ?>, 'remove_expiry');">Remove Expiry</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-12 padding0">

                    <div class="col-md-12">
                        <h4>Account Creation Date</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Date And Time</label>
                            <input type="text" required="" name="expiry_date" value="<?php echo $user['creation_date']; ?>" id="expiry_date" 
                                   placeholder="Expiry Date" class="form-control" data-parsley-required-message="Please Enter Date" />
                        </div>
                    </div>


                </div>
                <div class="col-md-12 padding0">
                    <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>bkeyword" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'bkeyword');">
                        <div class="col-md-12">
                            <h4>Black Keyword</h4>
                        </div>
                        <div class="col-md-12 padding0">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label for="check_black_keyword" class="fancy-check">
                                        <input type="checkbox" name="check_black_keyword" id="check_black_keyword"
                                               value="1" <?php echo ($user['check_black_keyword']) ? "checked" : ""; ?> />
                                        <span>Check Black Keyword</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-xs" id="btnbkeyword<?php echo $user_tab; ?>" 
                                    data-loading-text="Processing..." autocomplete="off">Save</button>
                        </div>
                    </form>
                </div>
                <!--special reseller ratio-->
                <div class="col-md-6 padding0">
                    <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>special_pr_ratio" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'special_pr_ratio');">
                        <div class="col-md-12">
                            <h4>Special PR Ratio</h4>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <input type="text" required="" name="special_pr_ratio" value="<?php echo $user['spacial_deliver_pr_ratio']; ?>"
                                       placeholder="Expiry Date" class="form-control" data-parsley-required-message="Please Enter Date" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-xs" id="btnspecial_pr_ratio<?php echo $user_tab; ?>"  data-loading-text="Processing..." autocomplete="off"
                                    onclick="saveUserInfo('user', <?php echo $user_tab; ?>, 'special_pr_ratio');" style="font-weight: bold;">SET RATIO</button>
                        </div>
                    </form>
                </div><br>
                <div class="col-md-6 padding0">
                    <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>special_tr_ratio" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'special_tr_ratio');">
                        <div class="col-md-12">
                            <h4>Special TR Ratio</h4>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" required="" name="special_tr_ratio" value="<?php echo $user['spacial_deliver_tr_ratio']; ?>" 
                                       placeholder="Expiry Date" class="form-control" data-parsley-required-message="Please Enter Date" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-xs" id="btnspecial_tr_ratio<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off"
                                    onclick="saveUserInfo('user', <?php echo $user_tab; ?>, 'special_tr_ratio');" style="font-weight: bold;">SET RATIO</button>
                        </div>
                    </form>
                </div>


            </div>

            <!-- Account Type -->
            <div class="col-md-3">
                <div class="col-md-12 padding0">
                    <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>account_type" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'account_type');">
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
                <div class="col-md-12 padding0">
                    <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>verify_location" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'verify_location');">
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
                    <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>manager_alert" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'manager_alert');">
                        <div class="col-md-12">
                            <h4>Account Manger Alert</h4>
                        </div>
                        <div class="col-md-12 padding0">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label for="manager_alert" class="fancy-check">
                                        <input type="checkbox" name="manager_alert" id="manager_alert"
                                               value="1" <?php echo ($user['manager_alert']) ? "checked" : ""; ?> />
                                        <span>Check</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-xs" id="btnmanager_alert<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Save</button>
                        </div>
                    </form>
                    <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>otp_user" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'otp_user');">
                        <div class="col-md-12">
                            <h4>OTP User</h4>
                        </div>
                        <div class="col-md-12 padding0">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label for="otp_user" class="fancy-check">
                                        <input type="checkbox" name="otp_user" id="otp_user"
                                               value="1" <?php echo ($user['otp_user']) ? "checked" : ""; ?> />
                                        <span>Check</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-xs" id="btnotp_user<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Save</button>
                        </div>
                    </form>
                    <?php
                    if ($admin_id == 6 || $admin_id == 1 || $admin_id == 2 || $admin_id == 9) {
                        ?>
                        <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>pricing_approval" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'pricing_approval');">
                            <div class="col-md-12">
                                <h4>Pricing Approval</h4>

                            </div>
                            <div class="col-md-12 padding0">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label for="pricing_approval" class="fancy-check">
                                            <input type="checkbox" name="pricing_approval" id="pricing_approval"
                                                   value="1" <?php echo ($user['pricing_approval']) ? "checked" : ""; ?> />
                                            <span>Check</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-xs" id="btnpricing_approval<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Save</button>
                            </div>
                        </form>
                        <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>encription_approve" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'encription_approve');">
                            <div class="col-md-12">
                                <h4>Use Encription</h4>

                            </div>
                            <div class="col-md-12 padding0">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label for="encription_approve" class="fancy-check">
                                            <input type="checkbox" name="encription_approve" id="encription_approve"
                                                   value="1" <?php echo ($user['encription']) ? "checked" : ""; ?> />
                                            <span>Check</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-xs" id="btnencription_approve<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Save</button>
                            </div>
                        </form>

                    <?php } ?>

                    <?php
                    if ($user['utype'] == 'Reseller' && $admin_id == 6 || $admin_id == 1) {
                        ?>

                        <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>open_reseller_setting" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'open_reseller_setting');">
                            <div class="col-md-12">
                                <h4>Reseller open setting</h4>
                            </div>
                            <div class="col-md-12 padding0">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label for="open_reseller" class="fancy-check">
                                            <input type="checkbox" name="open_reseller_status" id="open_reseller"
                                                   value="1" <?php echo ($user['open_account_setting']) ? "checked" : ""; ?> />
                                            <span>Check</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-xs" id="btnopen_reseller_setting<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Save</button>
                            </div>
                        </form>

                    <?php } ?>




                </div>
            </div>
        <?php } ?>

        <!-- User Rule Setting -->
        <?php if (isset($user_tab) && $user_tab == 2) { ?>
            <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>rules" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'rules');">
                <div class="col-md-4">
                    <div class="col-md-12">
                        <h4>Promotional SMS Rules</h4>
                    </div>
                    <div class="col-md-6">
                        <label>Sender Id Option</label>
                    </div>
                    <div class="col-md-6">
                        <label>DND Check</label>
                    </div>

                    <div class="col-md-6 padding0">
                        <div class="form-group">
                            <div class="checkbox">
                                <label for="p_sender_id_option" class="fancy-check">
                                    <input type="checkbox" name="p_sender_id_option" id="p_sender_id_option"
                                           value="1" <?php echo ($user['p_sender_id_option']) ? "checked" : ""; ?> />
                                    <span>Open</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 padding0">
                        <div class="form-group">
                            <div class="checkbox">
                                <label for="dnd_check" class="fancy-check">
                                    <input type="checkbox" name="dnd_check" id="dnd_check"
                                           value="1" <?php echo ($user['dnd_check']) ? "checked" : ""; ?> />
                                    <span>Check</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sender Id Type</label>
                            <select name="p_sender_id_type" id="p_sender_id_type" class="form-control">
                                <option value="" <?php echo (isset($user['p_sender_id_type']) && $user['p_sender_id_type'] == '') ? "selected" : ""; ?>>Select Route</option>
                                <option value="Numeric" <?php echo (isset($user['p_sender_id_type']) && $user['p_sender_id_type'] == 'Numeric') ? "selected" : ""; ?>>Numeric</option>
                                <option value="Alphabetic" <?php echo (isset($user['p_sender_id_type']) && $user['p_sender_id_type'] == 'Alphabetic') ? "selected" : ""; ?>>Alphabetic</option>
                                <option value="Alphanumeric" <?php echo (isset($user['p_sender_id_type']) && $user['p_sender_id_type'] == 'Alphanumeric') ? "selected" : ""; ?>>Alphanumeric</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sender Id Length</label>
                            <input type="text" name="p_sender_id_length" value="<?php echo $user['p_sender_id_length']; ?>" id="p_sender_id_length" class="form-control"
                                   data-parsley-type-message="Length must be a number" data-parsley-type="digits" />
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="col-md-12">
                        <h4>Transactional SMS Rules</h4>
                    </div>
                    <div class="col-md-6">
                        <label>Sender Id Option</label>
                    </div>
                    <div class="col-md-6">
                        <label>Keyword Option</label>
                    </div>
                    <div class="col-md-6 padding0">
                        <div class="form-group">
                            <div class="checkbox">
                                <label for="t_sender_id_option" class="fancy-check">
                                    <input type="checkbox" name="t_sender_id_option" id="t_sender_id_option"
                                           value="1" <?php echo ($user['t_sender_id_option']) ? "checked" : ""; ?> />
                                    <span>Open</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 padding0">
                        <div class="form-group">
                            <div class="checkbox">
                                <label for="keyword_option" class="fancy-check">
                                    <input type="checkbox" name="keyword_option" id="keyword_option"
                                           value="1" <?php echo ($user['keyword_option']) ? "checked" : ""; ?> />
                                    <span>Open</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sender Id Type</label>
                            <select name="sender_id_type" id="sender_id_type" class="form-control">
                                <option value="" <?php echo (isset($user['sender_id_type']) && $user['sender_id_type'] == '') ? "selected" : ""; ?>>Select Route</option>
                                <option value="Numeric" <?php echo (isset($user['sender_id_type']) && $user['sender_id_type'] == 'Numeric') ? "selected" : ""; ?>>Numeric</option>
                                <option value="Alphabetic" <?php echo (isset($user['sender_id_type']) && $user['sender_id_type'] == 'Alphabetic') ? "selected" : ""; ?>>Alphabetic</option>
                                <option value="Alphanumeric" <?php echo (isset($user['sender_id_type']) && $user['sender_id_type'] == 'Alphanumeric') ? "selected" : ""; ?>>Alphanumeric</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sender Id Length</label>
                            <input type="text" name="sender_id_length" value="<?php echo $user['sender_id_length']; ?>" id="sender_id_length" class="form-control"
                                   data-parsley-type-message="Length must be a number" data-parsley-type="digits" />
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <br><div style="height: 10px;"></div>
                    <div class="col-md-12">
                        <h4>Unique Database</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Unique Numbers Allowed</label>
                            <input type="text" name="unique_no" value="<?php echo $user['number_allowed']; ?>" class="form-control" id="unique_no"
                                   data-parsley-type-message="Unique numbers must be a number" data-parsley-type="digits" />
                        </div>
                    </div>
                </div> <div class="col-md-2">
                    <br><br>
                    <div class="col-md-12">
                        <h4>Fix Sender ID</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Sender id is fix for this user</label>
                            <input type="text" name="fix_sender_id" value="<?php echo $user['fix_sender_id']; ?>" class="form-control" id="fix_sender_id"
                                   data-parsley-type-message="Sender ID must be six characters"  />
                        </div>

                    </div>
                    <div class="row" style="margin-left:-925px;  margin-top: 150px;"> 
                        <div>
                            <h4>DND Check</h4>
                        </div>
                        <div >
                            <label>Stock DND Check</label>
                        </div>
                        <div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label for="stock_dnd_check" class="fancy-check">
                                        <input type="checkbox" name="stock_dnd_check" id="stock_dnd_check"
                                               value="1" <?php echo ($user['stock_dnd_check']) ? "checked" : ""; ?> />
                                        <span>Check</span>
                                    </label>
                                </div>
                            </div>
                            <label>Premium DND Check</label>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label for="premium_dnd_check" class="fancy-check">
                                        <input type="checkbox" name="premium_dnd_check" id="premium_dnd_check"
                                               value="1" <?php echo ($user['premium_dnd_check']) ? "checked" : ""; ?> />
                                        <span>Check</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>




                    <div class="col-md-12" style=" margin-top: -168px;">
                        <button type="submit" class="btn btn-primary btn-xs" id="btnrules<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Save Setting</button>
                    </div>
                </div>
            </form>
        <?php } ?>

        <!-- User Funds -->
        <?php if (isset($user_tab) && $user_tab == 3) { ?>
            <div class="col-md-4">
                <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>funds" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'funds');">
                    <div class="col-md-12">
                        <h4>Fund Transfer</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Balance Type</label>
                            <select class="form-control" name="route" id="route" data-parsley-required-message="Please Select Balance Type" required="">
                                <option value="">Select Balance Type</option>
                                <option value="A">Promotional Route</option>
                                <option value="B">Transactional Route</option>
                                <option value="C">STOCK Route</option>
                                <option value="D">Premium Route</option>
                                <option value="I">International Route</option>
                                <option value="Long">Long Code</option>
                                <option value="Short">Short Code</option>
                                <option value="VA">Voice Promotional</option>
                                <option value="VB">Voice Dynamic</option>
                                <option value="Missed">Missed Call Alerts</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" name="type" id="type" data-parsley-required-message="Please Select Type" required="">
                                <option value="">Select Type</option>
                                <option value="Add">Add</option>
                                <option value="Reduce">Reduce</option> 
                                <option value="Demo">Demo</option>
                                <option value="Refund">Refund</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tax</label>
                            <input type="text" name="sms_tax" id="sms_tax" 
                                   value="18"   class="form-control"  />
                            <!--data-parsley-min="0" data-parsley-min-message="Price must be greater than 0"-->
                            <!--data-parsley-pattern="^[0-9.]+$" data-parsley-pattern-message="Price must be number"-->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Price</label>
                            <?php
                            /*
                              $last_prize = array();
                              if($txn_logs){
                              foreach ($txn_logs as $txn_logs_prize) {
                              $last_prize[] = $txn_logs_prize['txn_price'];
                              }
                              $vij = current($last_prize);
                              }

                             */
                            ?>
                            <input type="text" name="sms_price"   id="sms_price"  onkeyup="calculateAdminAmount(this.value);" 
                                   placeholder="Price Per SMS" class="form-control" data-parsley-required-message="Please Enter Price" required="" 
                                   data-parsley-type="number" data-parsley-type-message="Price must be a number" 
                                   data-parsley-min="0.01" data-parsley-min-message="Price must be greater than 0" />
                            <!--data-parsley-min="0" data-parsley-min-message="Price must be greater than 0"-->
                            <!--data-parsley-pattern="^[0-9.]+$" data-parsley-pattern-message="Price must be number"-->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SMS</label>
                            <input type="text" name="sms_balance" id="sms_balance" maxlength="12" onkeyup="getNumberToWords(this.value);" 
                                   placeholder="Number Of SMS" class="form-control" data-parsley-required-message="Please Enter SMS" data-parsley-type="digits" 
                                   required="" data-parsley-type-message="SMS must be a number" data-parsley-min="1"
                                   data-parsley-min-message="SMS must be greater than 0" />
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="text" name="amount" id="amount" placeholder="Amount" readonly="readonly" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="description" placeholder="Description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Admin</label>
                            <select class="form-control" name="admin_name" id="admin_name" data-parsley-required-message="Please Select Admin" required="">
                                <option value="">Select Admin </option>    
                                <?php
                                if (isset($admin_name) && $admin_name) {
                                    foreach ($admin_name as $account_admin) {
                                        ?>
                                        <option value="<?php echo $account_admin['admin_id']; ?>"><?php echo $account_admin['admin_name']; ?></option>  
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 hidden" id="show_sms_words"></div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-xs" id="btnfunds<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Fund Transfer</button>
                    </div>
                </form>
            </div>

            <div class="col-md-8">
                <div class="col-md-12">
                    <h4>Fund Logs</h4>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive scroll" id="user_funds">
                        <?php echo (isset($table) && $table) ? $table : ''; ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!-- User Profile -->
        <?php if (isset($user_tab) && $user_tab == 4) { ?>
            <div class="col-md-6">
                <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>profile" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'profile');">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter Full Name" value="<?php echo $user['name']; ?>"
                                   required="" data-parsley-required-message="Please Enter Full Name" name="name"
                                   data-parsley-pattern="^[A-Za-z]([-']?[A-Za-z]+)*( [A-Za-z]([-']?[A-Za-z]+)*)+$" 
                                   data-parsley-pattern-message="Please Enter First And Last Name" />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="email_address">Email Address</label>
                            <input type="email" class="form-control" id="email_address" placeholder="Enter Email Address" value="<?php echo $user['email_address']; ?>"
                                   data-parsley-type="email" required="" data-parsley-required-message="Please Enter Valid Email Address"
                                   data-parsley-type-message="Please Enter Valid Email Address" name="email_address" />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input name="contact_number" id="contact_number" placeholder="Enter Contact Number" value="<?php echo $user['contact_number']; ?>" 
                                   class="form-control" type="text" data-parsley-required-message="Please Enter Contact Number" data-parsley-type-message="Please Enter Valid Contact Number"
                                   data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="10" required=""
                                   data-parsley-minlength-message="Please Enter Valid Contact Number" data-parsley-maxlength-message="Please Enter Valid Contact Number">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="utype">User Type</label>
                            <select id="utype" name="utype" class="form-control">
                                <option value="" <?php echo ($user['utype'] == "") ? 'selected="selected"' : '' ?>>Select Type</option>
                                <option value="Reseller" <?php echo ($user['utype'] == "Reseller") ? 'selected="selected"' : '' ?>>Reseller</option>
                                <option value="User" <?php echo ($user['utype'] == "User") ? 'selected="selected"' : '' ?>>User</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="company">Company</label>
                            <input type="text" class="form-control" id="company" placeholder="Enter Company" value="<?php echo $user['company_name']; ?>"
                                   name="company">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="industry">Industry</label>
                            <select id="industry" name="industry" class="form-control">
                                <option value="" <?php echo ($user['industry'] == "") ? 'selected="selected"' : '' ?>>Select Industry</option>
                                <option value="Agriculture " <?php echo ($user['industry'] == "Agriculture") ? 'selected="selected"' : '' ?>>Agriculture </option>
                                <option value="Automobile & Transport" <?php echo ($user['industry'] == "Automobile & Transport") ? 'selected="selected"' : '' ?>>Automobile & Transport</option>
                                <option value="Ecommerce" <?php echo ($user['industry'] == "Ecommerce") ? 'selected="selected"' : '' ?>>E-Commerce</option>
                                <option value="Education" <?php echo ($user['industry'] == "Education") ? 'selected="selected"' : '' ?>>Education</option>
                                <option value="Financial Institution" <?php echo ($user['industry'] == "Financial Institution") ? 'selected="selected"' : '' ?>>Financial Institution</option>
                                <option value="Gym" <?php echo ($user['industry'] == "Gym") ? 'selected="selected"' : '' ?>>Gym</option>
                                <option value="Hospitality" <?php echo ($user['industry'] == "Hospitality") ? 'selected="selected"' : '' ?>>Hospitality</option>
                                <option value="IT Company" <?php echo ($user['industry'] == "IT Company") ? 'selected="selected"' : '' ?>>IT Company</option>
                                <option value="Lifestyle Clubs" <?php echo ($user['industry'] == "Lifestyle Clubs") ? 'selected="selected"' : '' ?>>Lifestyle Clubs</option>
                                <option value="Logistics" <?php echo ($user['industry'] == "Logistics") ? 'selected="selected"' : '' ?>>Logistics</option>
                                <option value="Marriage Bureau" <?php echo ($user['industry'] == "Marriage Bureau") ? 'selected="selected"' : '' ?>>Marriage Bureau</option>
                                <option value="Media & Advertisement" <?php echo ($user['industry'] == "Media & Advertisement") ? 'selected="selected"' : '' ?>>Media & Advertisement</option>
                                <option value="Personal Use" <?php echo ($user['industry'] == "Personal Use") ? 'selected="selected"' : '' ?>>Personal Use</option>
                                <option value="Political" <?php echo ($user['industry'] == "Political") ? 'selected="selected"' : '' ?>>Political </option>
                                <option value="Public Sector" <?php echo ($user['industry'] == "Public Sector") ? 'selected="selected"' : '' ?>>Public Sector</option>
                                <option value="Real estate" <?php echo ($user['industry'] == "Real estate") ? 'selected="selected"' : '' ?>>Real estate</option>
                                <option value="Reseller" <?php echo ($user['industry'] == "Reseller") ? 'selected="selected"' : '' ?>>Reseller</option>
                                <option value="Retail & FMCG" <?php echo ($user['industry'] == "Retail & FMCG") ? 'selected="selected"' : '' ?>>Retail & FMCG</option>
                                <option value="Stock and Commodity" <?php echo ($user['industry'] == "Stock and Commodity") ? 'selected="selected"' : '' ?>>Stock and Commodity</option>
                                <option value="Telecom" <?php echo ($user['industry'] == "Telecom") ? 'selected="selected"' : '' ?>>Telecom</option>
                                <option value="Tips And Alert" <?php echo ($user['industry'] == "Tips And Alert") ? 'selected="selected"' : '' ?>>Tips And Alert</option>
                                <option value="Travel" <?php echo ($user['industry'] == "Travel") ? 'selected="selected"' : '' ?>>Travel</option>
                                <option value="Wholesalers Distributors or Manufacturers" <?php echo ($user['industry'] == "Wholesalers Distributors or Manufacturers") ? 'selected="selected"' : '' ?>>Wholesalers Distributors or Manufacturers</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-xs" id="btnprofile<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Update Profile</button>
                    </div>
                </form>
            </div>
        <?php } ?>

        <!-- User Reset Password -->
        <?php if (isset($user_tab) && $user_tab == 5) { ?>
            <div class="col-md-3">
                <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>reset" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'reset');">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Reset Password</label>
                            <input type="password" name="password" id="password" placeholder="Password" class="form-control" required=""
                                   data-parsley-required-message="Please Enter Password" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-xs" id="btnreset<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php } ?>

        <!-- User Low Balance Alert -->
        <?php
        if (isset($user_tab) && $user_tab == 6) {
            $alert = explode('|', $user['low_balance_alert']);
            ?>
            <div class="col-md-3">
                <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>alert" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'alert');">
                    <div class="col-md-6">
                        <label>By SMS</label>
                    </div>
                    <div class="col-md-6">
                        <label>By Email</label>
                    </div>
                    <div class="col-md-6 padding0">
                        <div class="form-group">
                            <div class="checkbox">
                                <label for="alert_by_sms" class="fancy-check">
                                    <input type="checkbox" name="alert_by_sms" id="alert_by_sms"
                                           value="1" <?php echo ($alert[0] == 1) ? "checked" : ""; ?> />
                                    <span>SMS</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 padding0">
                        <div class="form-group">
                            <div class="checkbox">
                                <label for="alert_by_email" class="fancy-check">
                                    <input type="checkbox" name="alert_by_email" id="alert_by_email"
                                           value="1" <?php echo ($alert[1] == 1) ? "checked" : ""; ?> />
                                    <span>Email</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Minimum Promotional SMS</label>
                            <input type="text" name="pr_sms" id="pr_sms" placeholder="Promotional SMS" class="form-control" required=""
                                   data-parsley-required-message="Please Enter SMS" value="<?php echo $user['low_balance_pr']; ?>"
                                   data-parsley-type="integer" data-parsley-type-message="Please enter valid number or 0"
                                   data-parsley-min="0" data-parsley-min-message="Please enter valid number or 0" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Minimum Transactional SMS</label>
                            <input type="text" name="tr_sms" id="tr_sms" placeholder="Transactional SMS" class="form-control" required=""
                                   data-parsley-required-message="Please Enter SMS" value="<?php echo $user['low_balance_tr']; ?>" 
                                   data-parsley-type="integer" data-parsley-type-message="Please enter valid number or 0"
                                   data-parsley-min="0" data-parsley-min-message="Please enter valid number or 0" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-xs" id="btnalert<?php echo $user_tab; ?>" data-loading-text="Processing..." 
                                    autocomplete="off">Set Alert</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php } ?>

        <!-- User Short/Long Tab -->
        <?php if (isset($user_tab) && $user_tab == 7) {
            ?>
            <!-- User Short Code -->
            <div class="col-md-3">
                <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>short" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'short');">
                    <div class="col-md-12">
                        <h4>Dedicated Short Code</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="short_number">Short Number</label>
                            <select name="number" id="short_number" class="form-control" required data-parsley-required-message="Please Select Short Number">
                                <option value="">Select Number</option>
                                <?php
                                if (isset($short_numbers) && $short_numbers) {
                                    foreach ($short_numbers as $key => $number) {
                                        ?>
                                        <option value="<?php echo $number['short_number_id']; ?>"><?php echo $number['short_number']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="short_keyword">Keyword</label>
                            <input type="text" name="keyword" id="short_keyword" placeholder="Please Enter Keyword" value=""
                                   class="form-control" required="" data-parsley-required-message="Please Enter Keyword"
                                   data-parsley-minlength="6" data-parsley-minlength-message="Keyword must be of 6 charater long" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="short_expiry_date">Validity</label>
                            <input type="text" name="expiry_date" id="short_expiry_date" placeholder="Please Enter Validity" value=""
                                   class="form-control" required="" data-parsley-required-message="Please Enter Validity" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-xs" id="btnshort<?php echo $user_tab; ?>" data-loading-text="Processing..."
                                autocomplete="off">Save Keyword</button>
                    </div>
                </form>
            </div>

            <!-- User Long Code -->
            <div class="col-md-3">
                <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>long" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'long');">
                    <div class="col-md-12">
                        <h4>Dedicated Long Code</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="long_number">Long Number</label>
                            <select name="number" id="long_number" class="form-control" required data-parsley-required-message="Please Select Long Number">
                                <option value="">Select Number</option>
                                <?php
                                if (isset($long_numbers) && $long_numbers) {
                                    foreach ($long_numbers as $key => $number) {
                                        ?>
                                        <option value="<?php echo $number['long_number_id']; ?>"><?php echo $number['long_number']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="long_keyword">Keyword</label>
                            <input type="text" name="keyword" id="long_keyword" placeholder="Please Enter Keyword" value=""
                                   class="form-control" required="" data-parsley-required-message="Please Enter Keyword"
                                   data-parsley-minlength="6" data-parsley-minlength-message="Keyword must be of 6 charater long" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="long_expiry_date">Validity</label>
                            <input type="text" name="expiry_date" id="long_expiry_date" placeholder="Please Enter Validity" value=""
                                   class="form-control" required="" data-parsley-required-message="Please Enter Validity" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-xs" id="btnlong<?php echo $user_tab; ?>" data-loading-text="Processing..." 
                                autocomplete="off">Save Keyword</button>
                    </div>
                </form>
            </div>
        <?php } ?>

        <!-- Voice SMS Tab -->
        <?php if (isset($user_tab) && $user_tab == 8) { ?>
            <!-- User Ratio -->
            <div class="col-md-2">
                <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>vtrratio" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'vtrratio');">
                    <div class="col-md-12">
                        <h4>Dynamic Route</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Fake Delivered Ratio</label>
                            <div class="input-group">
                                <input type="text" name="user_fake_ratio" value="<?php echo $user['vtr_fake_ratio']; ?>" id="user_fake_ratio" placeholder="User Fake Delivered Ratio" class="form-control"
                                       data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                                <div class="input-group-addon">%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Fake Failed Ratio</label>
                            <div class="input-group">
                                <input type="text" name="user_fail_ratio" value="<?php echo $user['vtr_fail_ratio']; ?>" id="user_fail_ratio" placeholder="User Fake Failed Ratio" class="form-control"
                                       data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                                <div class="input-group-addon">%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="ratio_type" value="vdynamic" />
                        <button type="submit" class="btn btn-primary btn-xs" id="btnvtrratio<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Set Ratio</button>
                    </div>
                </form>
            </div>

            <!-- User Ratio -->
            <div class="col-md-2">
                <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>vprratio" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'vprratio');">
                    <div class="col-md-12">
                        <h4>Promotional Ratio</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Fake Delivered Ratio</label>
                            <div class="input-group">
                                <input type="text" name="user_fake_ratio" value="<?php echo $user['vpr_fake_ratio']; ?>" id="user_fake_ratio" placeholder="User Fake Delivered Ratio" class="form-control"
                                       data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                                <div class="input-group-addon">%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Fake Failed Ratio</label>
                            <div class="input-group">
                                <input type="text" name="user_fail_ratio" value="<?php echo $user['vpr_fail_ratio']; ?>" id="user_fail_ratio" placeholder="User Fake Failed Ratio" class="form-control"
                                       data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                                <div class="input-group-addon">%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="ratio_type" value="vpromotional" />
                        <button type="submit" class="btn btn-primary btn-xs" id="btnvprratio<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Set Ratio</button>
                    </div>
                </form>
            </div>
            <div class="col-md-2">
                <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>addvoiceid" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'addvoiceid');">
                    <div class="col-md-12">
                        <h4>Add Voice ID</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Voice Id</label>
                            <div class="input-group">
                                <input type="text" name="draft_message" id="draft_message" placeholder="Voice Id" class="form-control" required="required" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Voice File Name</label>
                            <div class="input-group">
                                <input type="text" name="file_name" id="file_name" placeholder="Voice File Name" class="form-control" required="required"  />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Duration</label>
                            <div class="input-group">
                                <input type="text" name="duration" id="duration" placeholder="Duration" class="form-control" required="required" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-xs" id="btnaddvoiceid<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Add</button>
                    </div>
                </form>
            </div>
            <div class="col-md-2">
                <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>save_voice_route" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'save_voice_route');">
                    <div class="col-md-12">
                        <h4> Voice SMS Routing</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Promotional Route</label>
                            <div class="input-group">
                                <select class="form-control" name="pr_voice" id="pr_voice" data-parsley-id="2157">
                                    <option value="">Select Voice Group</option>
                                    <?php
                                    if ($voice_pr_route) {
                                        foreach ($voice_pr_route as $pr_voice) {
                                            if ($user['voice_pr_route'] == $pr_voice['voice_route_id']) {
                                                ?>
                                                <option value="<?php echo $pr_voice['voice_route_id']; ?>" selected="">
                                                    <?php echo $pr_voice['route_name']; ?>
                                                </option>
                                                <?php
                                            } else {
                                                ?>  
                                                <option value="<?php echo $pr_voice['voice_route_id']; ?>">
                                                    <?php echo $pr_voice['route_name']; ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Dynamic Route</label>
                            <div class="input-group">
                                <select class="form-control" name="tr_voice" id="tr_voice" data-parsley-id="2157">
                                    <option value="">Select Voice Group</option>
                                    <?php
                                    if ($voice_tr_route) {
                                        foreach ($voice_tr_route as $tr_voice) {
                                            if ($user['voice_tr_route'] == $tr_voice['voice_route_id']) {
                                                ?>
                                                <option value="<?php echo $tr_voice['voice_route_id']; ?>" selected="">
                                                    <?php echo $tr_voice['route_name']; ?>
                                                </option>
                                                <?php
                                            } else {
                                                ?>  
                                                <option value="<?php echo $tr_voice['voice_route_id']; ?>">
                                                    <?php echo $tr_voice['route_name']; ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-xs" id="btnaddvoiceid<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Save</button>
                    </div>
                </form>
            </div>
        <?php } ?>

        <!-- special reseller setting-->
        <?php if (isset($user_tab) && $user_tab == 9) { ?>
            <p style="color:#FF0000; margin-left: 30px; font-size: medium;">If you are transferring <b>Credits</b> then the user must be a special reseller</p>
            <div style="float:right;">  PR Credits : <?php echo $user['special_pr_balance']; ?><br>
                TR Credits : <?php echo $user['special_tr_balance']; ?><br>
                Premium Promotion : <?php echo $user['prtodnd_credits']; ?><br>
                Stock Promotion : <?php echo $user['stock_credits']; ?><br>
            </div>
            <div class="col-md-4">
                <form role="form" class="tab-forms" id="userSettingForm<?php echo $user_tab; ?>funds" method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>, 'funds');">
                    <div class="col-md-12">
                        <h4>Fund Transfer</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Balance Type</label>
                            <select class="form-control" name="route" id="route" data-parsley-required-message="Please Select Balance Type" required="">
                                <option value="">Select Balance Type</option>
                                <option value="A">Special Promotional</option>
                                <option value="B">Special Transactional</option>
                                <option value="C">STOCK Credits</option>
                                <option value="D">PRTODND Credits</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" name="type" id="type" data-parsley-required-message="Please Select Type" required="">
                                <option value="">Select Type</option>
                                <option value="Add">Add</option>
                                <option value="Reduce">Reduce</option> 
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tax</label>
                            <input type="text" name="sms_tax" id="tax_apply" 
                                   value="18"   class="form-control"  />
                            <!--data-parsley-min="0" data-parsley-min-message="Price must be greater than 0"-->
                            <!--data-parsley-pattern="^[0-9.]+$" data-parsley-pattern-message="Price must be number"-->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Price</label>

                            <input type="text" name="sms_price"   id="sms_price" 
                                   placeholder="Price Per SMS" class="form-control" data-parsley-required-message="Please Enter Price" required="" 
                                   data-parsley-type="number" data-parsley-type-message="Price must be a number" 
                                   data-parsley-min="0.01" data-parsley-min-message="Price must be greater than 0" />
                            <!--data-parsley-min="0" data-parsley-min-message="Price must be greater than 0"-->
                            <!--data-parsley-pattern="^[0-9.]+$" data-parsley-pattern-message="Price must be number"-->
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Credits</label>
                            <?php
                            /*
                              $last_prize = array();
                              if($txn_logs){
                              foreach ($txn_logs as $txn_logs_prize) {
                              $last_prize[] = $txn_logs_prize['txn_price'];
                              }
                              $vij = current($last_prize);
                              }

                             */
                            ?>
                            <input type="text" name="credits"   id="credit"  onkeyup="calculateSpecialAmount(this.value);" 
                                   placeholder="Price Per SMS" class="form-control" data-parsley-required-message="Please Enter Price" required="" 
                                   data-parsley-type="number" data-parsley-type-message="Price must be a number" 
                                   data-parsley-min="0.01" data-parsley-min-message="Price must be greater than 0" />
                            <!--data-parsley-min="0" data-parsley-min-message="Price must be greater than 0"-->
                            <!--data-parsley-pattern="^[0-9.]+$" data-parsley-pattern-message="Price must be number"-->
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="text" name="amount" id="special_amount" placeholder="Amount" readonly="readonly" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="description" placeholder="Description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Admin</label>
                            <select class="form-control" name="admin_name" id="admin_name" data-parsley-required-message="Please Select Admin" required="">
                                <option value="">Select Admin </option>    
                                <?php
                                if (isset($admin_name) && $admin_name) {
                                    foreach ($admin_name as $account_admin) {
                                        ?>
                                        <option value="<?php echo $account_admin['admin_id']; ?>"><?php echo $account_admin['admin_name']; ?></option>  
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 hidden" id="show_sms_words"></div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-xs" id="btnfunds<?php echo $user_tab; ?>" data-loading-text="Processing..." autocomplete="off">Fund Transfer</button>
                    </div>
                </form>
            </div>

            <div class="col-md-8">
                <div class="col-md-12">
                    <h4>Fund Logs</h4>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive scroll" id="user_funds">
                        <?php echo (isset($table) && $table) ? $table : ''; ?>
                    </div>
                </div>
            </div>


        <?php } ?>


    </div>

<?php } ?>

<script type="text/javascript">
    $(document).ready(function () {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
        $('#expiry_date').datepicker({
            format: "dd-mm-yyyy",
            startDate: today,
            autoclose: true,
            todayHighlight: true
        });
        $('#short_expiry_date').datepicker({
            format: "dd-mm-yyyy",
            startDate: today,
            autoclose: true,
            todayHighlight: true
        });
        $('#long_expiry_date').datepicker({
            format: "dd-mm-yyyy",
            startDate: today,
            autoclose: true,
            todayHighlight: true
        });
        $('#dob').datepicker({
            format: "dd-mm-yyyy",
            endDate: today,
            autoclose: true,
            todayHighlight: true
        });
    });
    $('.tab-forms').parsley();
</script>