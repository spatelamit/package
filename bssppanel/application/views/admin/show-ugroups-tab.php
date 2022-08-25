<?php
$all_ugroups = array();
if (isset($all_user_groups) && $all_user_groups) {
    foreach ($all_user_groups as $key => $value) {
        $all_ugroups[$value['user_group_id']] = $value['user_group_name'];
    }
}
?>
<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUserGroupTab('1');">Promotional User Groups</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUserGroupTab('2');">Transactional User Groups</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "3") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUserGroupTab('3');"><?php echo (isset($user_group)) ? "Update" : 'Add'; ?> User Group</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "4") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUserGroupTab('4');">XML Route</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "5") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getUserGroupTab('5');">Backup Route Setting</a>
    </li>
</ul>
<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active ">

        <?php if (isset($subtab) && $subtab == '1') { ?>
            <div class="table-responsive">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>User Group</th>
                            <th>Connected SMSC</th>
                            <th>Sender Id Type</th>
                            <th>Sender Id Length</th>
                            <th>Route Price</th>
                            <th>For Resend</th>
                            <th>Default</th>
                            <th colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bgf7">
                        <?php
                        if (isset($user_groups) && $user_groups) {
                            $i = 1;
                            foreach ($user_groups as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $row['user_group_name']; ?></td>
                                    <td><?php echo $row['smsc_id']; ?></td>
                                    <td><?php echo $row['sender_id_type']; ?></td>
                                    <td><?php echo $row['sender_id_length']; ?></td>
                                    <td>
                                        <?php
                                        /*
                                        if (isset($all_ugroups) && $all_ugroups) {
                                            if (array_key_exists($row['backup_user_group'], $all_ugroups)) {
                                                echo $all_ugroups[$row['backup_user_group']];
                                            } else {
                                                echo "-";
                                            }
                                        } else {
                                            echo "-";
                                        }
                                         
                                         */
                                        echo $row['route_price'];
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($row['resend_status']) {
                                            ?>
                                            <button onclick="setForResend('<?php echo $row['user_group_id']; ?>', '<?php echo $subtab; ?>', 0, '<?php echo $row['purpose']; ?>');"
                                                    type="button" class="btn btn-success btn-xs">
                                                Yes
                                            </button>
                                            <?php
                                        } else {
                                            ?>
                                            <button onclick="setForResend('<?php echo $row['user_group_id']; ?>', '<?php echo $subtab; ?>', 1, '<?php echo $row['purpose']; ?>');"
                                                    type="button" class="btn btn-danger btn-xs">
                                                No
                                            </button>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($row['default_user_group'] == 0) {
                                            ?>
                                            <button onclick="setDefault('<?php echo $row['user_group_id']; ?>', '<?php echo $subtab; ?>', 1, '<?php echo $row['purpose']; ?>');"
                                                    type="button" class="btn btn-inverse btn-xs">
                                                Set Default
                                            </button>
                                            <?php
                                        } else {
                                            ?>
                                            <button type="button" class="btn btn-success btn-xs">Default</button>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="switch switch-success switch-sm round switch-inline">
                                            <?php
                                            if ($row['user_group_status']) {
                                                ?>
                                                <input type="checkbox" id="status<?php echo $i; ?>" checked
                                                       onclick="changeUGStatus('<?php echo $row['user_group_id']; ?>', '<?php echo $subtab; ?>', 0, '<?php echo $row['purpose']; ?>');" />
                                                       <?php
                                                   } else {
                                                       ?>
                                                <input type="checkbox" id="status<?php echo $i; ?>" 
                                                       onclick="changeUGStatus('<?php echo $row['user_group_id']; ?>', '<?php echo $subtab; ?>', 1, '<?php echo $row['purpose']; ?>');" />
                                                       <?php
                                                   }
                                                   ?>
                                            <label for="status<?php echo $i; ?>"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <button onclick="updateUserGroup('<?php echo $row['user_group_id']; ?>', 'PR');"
                                                type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Edit User Group">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </td>
<!--                                    <td>
                                        <button onclick="deleteUserGroup('<?php //echo $row['user_group_id']; ?>', '<?php //echo $subtab; ?>');"
                                                type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete User Group">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>-->
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="11" align="center">
                                    <strong>No User Groups</strong>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

            </div>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '2') { ?>
            <div class="table-responsive">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>User Group</th>
                            <th>Connected SMSC</th>
                            <th>Sender Id Type</th>
                            <th>Sender Id Length</th>
                             <th>Route Price</th>
                            <th>For Resend</th>
                            <th>Default</th>
                            <th colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bgf7">
                        <?php
                        if (isset($user_groups) && $user_groups) {
                            $i = 1;
                            foreach ($user_groups as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $row['user_group_name']; ?></td>
                                    <td><?php echo $row['smsc_id']; ?></td>
                                    <td><?php echo $row['sender_id_type']; ?></td>
                                    <td><?php echo $row['sender_id_length']; ?></td>
                                    <td>
                                        <?php
                                      /*  if (isset($all_ugroups) && $all_ugroups) {
                                            if (array_key_exists($row['backup_user_group'], $all_ugroups)) {
                                                echo $all_ugroups[$row['backup_user_group']];
                                            } else {
                                                echo "-";
                                            }
                                        } else {
                                            echo "-";
                                        }
                                       
                                       */
                                         echo $row['route_price'];
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($row['resend_status']) {
                                            ?>
                                            <button onclick="setForResend('<?php echo $row['user_group_id']; ?>', '<?php echo $subtab; ?>', 0, '<?php echo $row['purpose']; ?>');"
                                                    type="button" class="btn btn-success btn-xs">
                                                Yes
                                            </button>
                                            <?php
                                        } else {
                                            ?>
                                            <button onclick="setForResend('<?php echo $row['user_group_id']; ?>', '<?php echo $subtab; ?>', 1, '<?php echo $row['purpose']; ?>');"
                                                    type="button" class="btn btn-danger btn-xs">
                                                No
                                            </button>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($row['default_user_group'] == 0) {
                                            ?>
                                            <button onclick="setDefault('<?php echo $row['user_group_id']; ?>', '<?php echo $subtab; ?>', 1, '<?php echo $row['purpose']; ?>');"
                                                    type="button" class="btn btn-inverse btn-xs">
                                                Set Default
                                            </button>
                                            <?php
                                        } else {
                                            ?>
                                            <button type="button" class="btn btn-success btn-xs">Default</button>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="switch switch-success switch-sm round switch-inline">
                                            <?php
                                            if ($row['user_group_status']) {
                                                ?>
                                                <input type="checkbox" id="status<?php echo $i; ?>" checked
                                                       onclick="changeUGStatus('<?php echo $row['user_group_id']; ?>', '<?php echo $subtab; ?>', 0, '<?php echo $row['purpose']; ?>');" />
                                                       <?php
                                                   } else {
                                                       ?>
                                                <input type="checkbox" id="status<?php echo $i; ?>" 
                                                       onclick="changeUGStatus('<?php echo $row['user_group_id']; ?>', '<?php echo $subtab; ?>', 1, '<?php echo $row['purpose']; ?>');" />
                                                       <?php
                                                   }
                                                   ?>
                                            <label for="status<?php echo $i; ?>"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <button onclick="updateUserGroup('<?php echo $row['user_group_id']; ?>', 'TR');"
                                                type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Edit User Group">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </td>
<!--                                    <td>
                                        <button onclick="deleteUserGroup('<?php //echo $row['user_group_id']; ?>', '<?php //echo $subtab; ?>');"
                                                type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete User Group">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>-->
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="11" align="center">
                                    <strong>No User Groups</strong>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '3') { ?>
            <form role="form" class="tab-forms" id="userGroupForm" method='post' action="javascript:saveUserGroup();">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>User Group Name</label>
                            <input type="text" name="user_group_name" id="user_group_name" placeholder="Enter User Group Name" class="form-control" required=""
                                   data-parsley-error-message="Please Enter Group Name" value="<?php echo (isset($user_group) && $user_group) ? $user_group->user_group_name : ''; ?>" />
                        </div>
                        <div class="form-group">
                            <label>Connected SMSC</label>
                            <input type="text" name="smsc_name" id="smsc_name" placeholder="Enter SMSC Name" class="form-control" required=""
                                   data-parsley-error-message="Please Enter Connected SMSC" value="<?php echo (isset($user_group) && $user_group) ? $user_group->smsc_id : ''; ?>" />
                        </div>
                        <div class="form-group">
                            <label>User Group Username</label>
                            <input type="text" name="user_group_username" id="user_group_username" placeholder="Enter User Group Username" class="form-control" 
                                   data-parsley-error-message="Please Enter User Group Username" required="" value="<?php echo (isset($user_group) && $user_group) ? $user_group->user_group_username : ''; ?>" />
                        </div>
                        <div class="form-group">
                            <label>User Group Password</label>
                            <input type="password" name="user_group_password" id="user_group_password" placeholder="Enter User Group Password" class="form-control" 
                                   data-parsley-error-message="Please Enter User Group Password" required="" value="<?php echo (isset($user_group) && $user_group) ? $user_group->user_group_password : ''; ?>" />
                        </div>
                         <div class="form-group">
                            <label>Sender Id Status</label>
                            <select name="sender_id_status" id="sender_id_status" class="form-control" required="" data-parsley-error-message="Please Select SenderId Status">
                                <option value="" <?php echo (isset($user_group) && $user_group->sender_id_status == '') ? 'selected' : ''; ?>>Select SenderId Status</option>
                                <option value="Open" <?php echo (isset($user_group) && $user_group->sender_id_status == 'Open') ? 'selected' : ''; ?>>Open SenderId</option>
                                <option value="Approved" <?php echo (isset($user_group) && $user_group->sender_id_status == 'Approved') ? 'selected' : ''; ?>>Approved SenderId</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Purpose</label>
                            <select name="purpose" id="purpose" class="form-control" required="" data-parsley-error-message="Please Select Purpose">
                                <option value="" <?php echo (isset($user_group) && $user_group->purpose == '') ? 'selected' : ''; ?>>Select Route</option>
                                <option value="Promotional" <?php echo (isset($user_group) && $user_group->purpose == 'Promotional') ? 'selected' : ''; ?>>Promotional SMS</option>
                                <option value="Transactional" <?php echo (isset($user_group) && $user_group->purpose == 'Transactional') ? 'selected' : ''; ?>>Transactional SMS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Sender Id Type</label>
                            <select name="sender_id_type" id="sender_id_type" class="form-control" required="" data-parsley-error-message="Please Select Sender Id Type">
                                <option value="" <?php echo (isset($user_group) && $user_group->sender_id_type == '') ? 'selected' : ''; ?>>Select Type</option>
                                <option value="Numeric" <?php echo (isset($user_group) && $user_group->sender_id_type == 'Numeric') ? 'selected' : ''; ?>>Numeric Type</option>
                                <option value="Alphabetic" <?php echo (isset($user_group) && $user_group->sender_id_type == 'Alphabetic') ? 'selected' : ''; ?>>Alphabetic Type</option>
                                <option value="Alphanumeric" <?php echo (isset($user_group) && $user_group->sender_id_type == 'Alphanumeric') ? 'selected' : ''; ?>>Alphanumeric Type</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Sender Id Length</label>
                            <input type="text" name="sender_length" id="sender_length" placeholder="Enter Enter Sender Id Length" class="form-control" required=""
                                   data-parsley-error-message="Please Enter Sender Id Length" value="<?php echo (isset($user_group) && $user_group) ? $user_group->sender_id_length : ''; ?>" />
                        </div>
                        <div class="form-group">
                            <label>Backup Route</label>
                            <select name="backup_user_group" id="backup_user_group" class="form-control">
                                <option value="0">Select Backup Route</option>
                                <?php
                                if (isset($backup_pr_ugroups) && $backup_pr_ugroups) {
                                    ?>
                                    <optgroup label="Promotional Groups">
                                        <?php
                                        foreach ($backup_pr_ugroups as $key => $backup_pr_ug) {
                                            ?>
                                            <option value="<?php echo (isset($backup_pr_ug) && $backup_pr_ug['user_group_id']) ? $backup_pr_ug['user_group_id'] : '0'; ?>"
                                                    <?php echo (isset($user_group) && $user_group && $user_group->backup_user_group == $backup_pr_ug['user_group_id']) ? 'selected' : ''; ?>>
                                                        <?php echo (isset($backup_pr_ug) && $backup_pr_ug['user_group_name']) ? $backup_pr_ug['user_group_name'] . " [" . $backup_pr_ug['smsc_id'] . "]" : ''; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </optgroup>
                                    <?php
                                }
                                if (isset($backup_tr_ugroups) && $backup_tr_ugroups) {
                                    ?>
                                    <optgroup label="Transactional Groups">
                                        <?php
                                        foreach ($backup_tr_ugroups as $key => $backup_tr_ug) {
                                            ?>
                                            <option value="<?php echo (isset($backup_tr_ug) && $backup_tr_ug['user_group_id']) ? $backup_tr_ug['user_group_id'] : '0'; ?>"
                                                    <?php echo (isset($user_group) && $user_group && $user_group->backup_user_group == $backup_tr_ug['user_group_id']) ? 'selected' : ''; ?>>
                                                        <?php echo (isset($backup_tr_ug) && $backup_tr_ug['user_group_name']) ? $backup_tr_ug['user_group_name'] . " [" . $backup_tr_ug['smsc_id'] . "]" : ''; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </optgroup>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                         <div class="form-group">
                            <label>Route Pricing</label>
                            <input type="text" name="pricing" id="route_price" placeholder="Enter Enter Route Pricing" class="form-control" required=""
                                   data-parsley-error-message="Please Enter Route Pricing" value="<?php echo (isset($user_group) && $user_group) ? $user_group->route_price : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="user_group_id" id="user_group_id" value="<?php echo (isset($user_group) && $user_group) ? $user_group->user_group_id : '0'; ?>" />
                        <button type="submit" class="btn btn-primary" name="save_ugroup" id="save_ugroup" data-loading-text="Processing..." autocomplete="off">
                            <?php echo (isset($user_group_id) && $user_group_id) ? 'Update' : 'Save'; ?>
                        </button> 
                    </div>
                </div>
            </form>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '4') { ?>
            <form role="form" class="tab-forms" id="xmlRouteForm" method='post' action="javascript:saveXMLRouteSetting();">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Authentication Key</label>
                            <input type="text" name="xml_route_authkey" id="xml_route_authkey" placeholder="Enter Auth Key" class="form-control" required=""
                                   data-parsley-error-message="Please Enter Auth Key" value="<?php echo (isset($xml_route_authkey) && $xml_route_authkey) ? $xml_route_authkey : ''; ?>" />
                        </div>
                        <div class="form-group">
                            <label>URL</label>
                            <input type="text" name="xml_route_url" id="xml_route_url" placeholder="Enter URL" class="form-control" required=""
                                   data-parsley-error-message="Please Enter URL" value="<?php echo (isset($xml_route_url) && $xml_route_url) ? $xml_route_url : ''; ?>" />
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="setting_id" id="setting_id" value="<?php echo (isset($setting_id) && $setting_id) ? $setting_id : '0'; ?>" />
                            <button type="submit" class="btn btn-primary" name="save_xml" id="save_xml"
                                    data-loading-text="Processing..." autocomplete="off">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '5') { ?>
            <form role="form" class="tab-forms" id="backupRouteForm" method='post' action="javascript:saveBackupRouteSetting();">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Time-Duration (In Min.)</label>
                            <input type="text" name="backup_time_duration" id="backup_time_duration" placeholder="Enter Time-Duration" class="form-control" required=""
                                   data-parsley-error-message="Please Enter Time-Duration" 
                                   value="<?php echo (isset($backup_time_duration) && $backup_time_duration) ? $backup_time_duration : '0'; ?>" />
                        </div>
                        <div class="form-group">
                            <label>SMS Limit</label>
                            <input type="text" name="backup_limit" id="backup_limit" placeholder="Enter SMS Limit" class="form-control" required=""
                                   data-parsley-error-message="Please Enter SMS Limit" 
                                   value="<?php echo (isset($backup_limit) && $backup_limit) ? $backup_limit : '0'; ?>" />
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="setting_id" id="setting_id" value="<?php echo (isset($setting_id) && $setting_id) ? $setting_id : '0'; ?>" />
                            <button type="submit" class="btn btn-primary" name="save_backup_route" id="save_backup_route"
                                    data-loading-text="Processing..." autocomplete="off">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>

    </div>
</div>

<script type="text/javascript">
    $('#userGroupForm').parsley();
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>