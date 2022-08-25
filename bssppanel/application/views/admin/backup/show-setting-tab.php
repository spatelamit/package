<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
<!-- Horizontal-tabs -->
<div class="horizontal-tabs tab-color padding15">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <?php
        if ($atype == 1) {
            ?>
            <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
                <a href="javascript:void(0)" onclick="getSettingTab('1');">Generate Virtual Balance</a>
            </li>
            <?php
        }
        ?>
        <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
            <a href="javascript:void(0)" onclick="getSettingTab('2');">Default Settings</a>
        </li>
        <!--
        <li class="<?php //echo (isset($subtab) && $subtab == "3") ? 'active"' : ''                                                                             ?>">
            <a href="javascript:void(0)" onclick="getSettingTab('3');">Virtual SMPP Ports</a>
        </li>
        <li class="<?php //echo (isset($subtab) && $subtab == "4") ? 'active"' : ''                                                                             ?>">
            <a href="javascript:void(0)" onclick="getSettingTab('4');">Add Virtual SMPP Port</a>
        </li>
        -->
        <li class="<?php echo (isset($subtab) && $subtab == "5") ? 'active"' : '' ?>">
            <a href="javascript:void(0)" onclick="getSettingTab('5');">White Number Lists</a>
        </li>
        <li class="<?php echo (isset($subtab) && $subtab == "6") ? 'active"' : '' ?>">
            <a href="javascript:void(0)" onclick="getSettingTab('6');">Black Number Lists</a>
        </li>
        <li class="<?php echo (isset($subtab) && $subtab == "7") ? 'active"' : '' ?>">
            <a href="javascript:void(0)" onclick="getSettingTab('7');">Black Sender Ids</a>
        </li>
        <li class="<?php echo (isset($subtab) && $subtab == "8") ? 'active"' : '' ?>">
            <a href="javascript:void(0)" onclick="getSettingTab('8');">Default Signup Settings</a>
        </li>
        <li class="<?php echo (isset($subtab) && $subtab == "9") ? 'active"' : '' ?>">
            <a href="javascript:void(0)" onclick="getSettingTab('9');">Other Settings</a>
        </li>
        <li class="<?php echo (isset($subtab) && $subtab == "10") ? 'active"' : '' ?>">
            <a href="javascript:void(0)" onclick="getSettingTab('10');">Retry/Resend Settings</a>
        </li>
    </ul>
    <div class="panel-group panel-color visible-xs"></div>

    <!-- Tab panes -->
    <div class="tab-content bgf9">
        <div class="tab-pane fade in active ">

            <?php if ($subtab == '1') { ?>
                <div class="row">                       
                    <div class="col-md-3">
                        <form role="form" class="tab-forms" id="validate-basic" data-parsley-validate method='post' action="javascript:saveSetting(<?php echo $subtab; ?>, 1);">
                            <div class="form-group">
                                <label>Promotional SMS</label>
                                <input type="text" class="form-control" value="<?php echo (isset($total_pr_balance) && $total_pr_balance) ? $total_pr_balance : "0"; ?>" name="pr_balance" id="pr_balance" 
                                       placeholder="Promotioanl SMS Balance" data-parsley-required-message="Please Enter SMS" data-parsley-type="digits" 
                                       required="" data-parsley-type-message="SMS must be a number" data-parsley-min="1"
                                       data-parsley-min-message="SMS must be greater than 0" />
                            </div>
                            <div class="form-group">
                                <label>Transactional SMS</label>
                                <input type="text" class="form-control" value="<?php echo (isset($total_tr_balance) && $total_tr_balance) ? $total_tr_balance : "0"; ?>" name="tr_balance" id="tr_balance" 
                                       placeholder="Transactional SMS Balance" data-parsley-required-message="Please Enter SMS" data-parsley-type="digits" 
                                       required="" data-parsley-type-message="SMS must be a number" data-parsley-min="1"
                                       data-parsley-min-message="SMS must be greater than 0" />
                            </div>                            
                            <div class="form-group">
                                <label>Long Code</label>
                                <input type="text" class="form-control" value="<?php echo (isset($total_lcode_balance) && $total_lcode_balance) ? $total_lcode_balance : "0"; ?>" name="long_balance" id="long_balance" 
                                       placeholder="Long Code Balance" data-parsley-required-message="Please Enter SMS" data-parsley-type="digits" 
                                       required="" data-parsley-type-message="SMS must be a number" data-parsley-min="1"
                                       data-parsley-min-message="SMS must be greater than 0" />
                            </div>
                            <div class="form-group">
                                <label>Short Code</label>
                                <input type="text" class="form-control" value="<?php echo (isset($total_scode_balance) && $total_scode_balance) ? $total_scode_balance : "0"; ?>" name="short_balance" id="short_balance" 
                                       placeholder="Short Code Balance" data-parsley-required-message="Please Enter SMS" data-parsley-type="digits" 
                                       required="" data-parsley-type-message="SMS must be a number" data-parsley-min="1"
                                       data-parsley-min-message="SMS must be greater than 0" />
                            </div>
                            <button type="submit" class="btn btn-primary" id="btn<?php echo $subtab; ?>1" 
                                    data-loading-text="Processing..." autocomplete="off">Save Balance</button>
                        </form>
                    </div>
                </div>
            <?php } ?>

            <?php if ($subtab == '2') { ?>
                <form role="form" class="tab-forms" id="validate-basic" method='post' action="javascript:saveSetting(<?php echo $subtab; ?>, 1);">
                    <div class="row" id="reseller_settings">
                        <div class="col-md-7">
                            <h4>Promotional SMS</h4>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Sender Id Option</label>
                                    <div class="input-group">
                                        <label for="pr_sender_id_check" class="fancy-check">
                                            <input type="checkbox" name="pr_sender_id_check" id="pr_sender_id_check"
                                                   value="1" <?php echo (isset($settings->pr_sender_id_check) && $settings->pr_sender_id_check) ? "checked" : ""; ?> />
                                            <span>Open</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>DND Checking</label>
                                    <div class="input-group">
                                        <label for="pr_dnd_check" class="fancy-check">
                                            <input type="checkbox" name="pr_dnd_check" id="pr_dnd_check"
                                                   value="1" <?php echo (isset($settings->pr_dnd_check) && $settings->pr_dnd_check) ? "checked" : ""; ?> />
                                            <span>Open</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Sender Id Type</label>
                                    <div class="input-group">
                                        <select name="sender_id_type" id="sender_id_type" class="form-control">
                                            <option value="" <?php echo (isset($settings->pr_sender_id_type) && $settings->pr_sender_id_type == '') ? "selected" : ""; ?>>Select Type</option>
                                            <option value="Numeric" <?php echo (isset($settings->pr_sender_id_type) && $settings->pr_sender_id_type == 'Numeric') ? "selected" : ""; ?>>Numeric</option>
                                            <option value="Alphabetic" <?php echo (isset($settings->pr_sender_id_type) && $settings->pr_sender_id_type == 'Alphabetic') ? "selected" : ""; ?>>Alphabetic</option>
                                            <option value="Alphanumeric" <?php echo (isset($settings->pr_sender_id_type) && $settings->pr_sender_id_type == 'Alphanumeric') ? "selected" : ""; ?>>Alphanumeric</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Sender Id Length</label>
                                    <div class="input-group">
                                        <input type="text" name="pr_sender_id_length" value="<?php echo (isset($settings) && isset($settings->pr_sender_id_length)) ? $settings->pr_sender_id_length : 0; ?>" id="pr_sender_id_length" class="form-control"
                                               data-parsley-error-message="Please Enter Valid Number" data-parsley-type="integer" />
                                    </div>
                                </div>
                            </div>
                            <h4>Transactional SMS</h4>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Sender Id Option</label>
                                    <div class="input-group">
                                        <label for="tr_sender_id_check" class="fancy-check">
                                            <input type="checkbox" name="tr_sender_id_check" id="tr_sender_id_check"
                                                   value="1" <?php echo (isset($settings->tr_sender_id_check) && $settings->tr_sender_id_check) ? "checked" : ""; ?> />
                                            <span>Open</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Keyword Option</label>
                                    <div class="input-group">
                                        <label for="tr_keyword_check" class="fancy-check">
                                            <input type="checkbox" name="tr_keyword_check" id="tr_keyword_check"
                                                   value="1" <?php echo (isset($settings->tr_keyword_check) && $settings->tr_keyword_check) ? "checked" : ""; ?> />
                                            <span>Open</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Sender Id Type</label>
                                    <div class="input-group">
                                        <select name="tr_sender_id_type" id="tr_sender_id_type" class="form-control">
                                            <option value="" <?php echo (isset($settings->tr_sender_id_type) && $settings->tr_sender_id_type == '') ? "selected" : ""; ?>>Select Type</option>
                                            <option value="Numeric" <?php echo (isset($settings->tr_sender_id_type) && $settings->tr_sender_id_type == 'Numeric') ? "selected" : ""; ?>>Numeric</option>
                                            <option value="Alphabetic" <?php echo (isset($settings->tr_sender_id_type) && $settings->tr_sender_id_type == 'Alphabetic') ? "selected" : ""; ?>>Alphabetic</option>
                                            <option value="Alphanumeric" <?php echo (isset($settings->tr_sender_id_type) && $settings->tr_sender_id_type == 'Alphanumeric') ? "selected" : ""; ?>>Alphanumeric</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Sender Id Length</label>
                                    <div class="input-group">
                                        <input type="text" name="tr_sender_id_length" value="<?php echo (isset($settings) && isset($settings->tr_sender_id_length)) ? $settings->tr_sender_id_length : 0; ?>" id="tr_sender_id_length" class="form-control"
                                               data-parsley-error-message="Please Enter Valid Number" data-parsley-type="number" />
                                    </div>
                                </div>
                            </div>
                            <h4>Unique Numbers</h4>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="sms_limit" value="<?php echo (isset($settings) && isset($settings->sms_limit)) ? $settings->sms_limit : "0"; ?>" class="form-control" id="sms_limit"
                                               data-parsley-error-message="Please Enter Valid Number" data-parsley-type="integer" />
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="hidden" name="setting_id" id="setting_id" value="<?php echo (isset($settings) && isset($settings->setting_id)) ? $settings->setting_id : 0; ?>" />
                                    <button type="submit" class="btn btn-primary" id="btn<?php echo $subtab; ?>1" data-loading-text="Processing..." autocomplete="off">Save Setting</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php if ($subtab == '3') { ?>
                <div class="table-responsive padding15">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>User Group</th>
                                <th>SMPP</th>
                                <th>Port Number</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <?php
                            if ($smpp_ports) {
                                $i = 1;
                                foreach ($smpp_ports as $smpp_port) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $smpp_port['user_group_name']; ?></td>
                                        <td><?php echo $smpp_port['smsc_id']; ?></td>
                                        <td><?php echo $smpp_port['virtual_port_no']; ?></td>
                                        <td><?php echo $smpp_port['purpose']; ?></td>
                                        <td>
                                            <?php if ($smpp_port['virtual_port_status'] == 1) { ?>
                                                <span class="label label-success">Activated</span>
                                            <?php } elseif ($smpp_port['virtual_port_status'] == 0) { ?>
                                                <span class="label label-danger">Deactivated</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($smpp_port['virtual_port_status'] == 1) {
                                                ?>
                                                <button onclick="changeStatus('port', '<?php echo $subtab; ?>', '<?php echo $smpp_port['virtual_port_id'] . "_0"; ?>');"
                                                        type="button" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Disable Virtual Port">
                                                    <i class="fa fa-ban"></i>
                                                </button>
                                                <?php
                                            } elseif ($smpp_port['virtual_port_status'] == 0) {
                                                ?>
                                                <button onclick="changeStatus('port', '<?php echo $subtab; ?>', '<?php echo $smpp_port['virtual_port_id'] . "_1"; ?>');"
                                                        type="button" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Enable Virtual Port">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <button onclick="deleteSettings('port', '<?php echo $subtab; ?>', '<?php echo $smpp_port['virtual_port_id']; ?>');"
                                                    type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Virtual Port">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="8" align="center">No Records Found!</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

            <?php if ($subtab == '4') { ?>
                <?php
                $virtual_port_id = 0;
                $smpp_type = -1;
                $user_group_id = 0;
                $virtual_port = "";
                if (isset($smpp_port)) {
                    $virtual_port_id = $smpp_port->virtual_port_id;
                    $smpp_type = $smpp_port->purpose;
                    $user_group_id = $smpp_port->user_group_id;
                    $virtual_port = $smpp_port->virtual_port_no;
                }
                ?>
                <form role="form" class="tab-forms" id="validate-basic" method='post' action="javascript:saveSetting(<?php echo $subtab; ?>, 1);">
                    <div class="form-group">
                        <label>SMPP Type</label>
                        <select class="form-control" name="smpp_type" id="smpp_type" onchange="getUserGroups(this.value);" required="" data-parsley-error-message="Please Select SMPP Type">
                            <option value="" <?php echo ($smpp_type == '' ? "selected" : "") ?>>Select SMPP Type</option>
                            <option value="Promotional" <?php echo ($smpp_type == "Promotional" ? "selected" : "") ?>>Promotional SMPP</option>
                            <option value="Transactional" <?php echo ($smpp_type == "Transactional" ? "selected" : "") ?>>Transactional SMPP</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select User Group</label>
                        <select name="user_group" id="user_group" class="form-control" required="" data-parsley-error-message="Please Select User Group">
                            <option value="">Select User Group</option>
                            <?php
                            if ($user_groups) {
                                foreach ($user_groups as $user_group) {
                                    if ($user_group_id == $user_group['user_group_id']) {
                                        ?>
                                        <option value="<?php echo $user_group['user_group_id'] ?>" selected=""><?php echo $user_group['user_group_name'] ?></option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="<?php echo $user_group['user_group_id'] ?>"><?php echo $user_group['user_group_name'] ?></option>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Assign Virtual Port</label>
                        <input type="text" class="form-control" name="virtual_port" id="virtual_port" value="<?php echo $virtual_port; ?>" 
                               placeholder="Enter Port Number" required="" data-parsley-error-message="Please Enter Virtual Port"
                               data-parsley-type="integer"/>
                    </div>
                    <input type="hidden" name="virtual_port_id" id="virtual_port_id" value="<?php echo $virtual_port_id; ?>" />
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            <?php } ?>

            <?php if ($subtab == '5') { ?>
                <div class="col-md-3 padding15">
                    <form role="form" id="validate-basic" method='post' action="javascript:saveSetting(<?php echo $subtab; ?>, 1);">
                        <div class="form-group">
                            <label for="contact_number">Add White List Number</label>
                            <input type="text" name="contact_number" id="contact_number" value="" placeholder="Enter Conact Number" class="form-control"
                                   required="" data-parsley-error-message="Please Enter Valid Number" data-parsley-type="integer"
                                   data-parsley-minlength="10" data-parsley-maxlength="10">
                        </div>
                        <input type="hidden" name="number_type" id="number_type" value="white" />
                        <button type="submit" class="btn btn-primary" id="btn<?php echo $subtab; ?>1" data-loading-text="Processing..." autocomplete="off">Save</button>
                    </form>
                </div>
                <div class="table-responsive padding15">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Mobile Number</th>
                                <th>Status</th>
                                <th colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <?php
                            if ($white_lists) {
                                $i = 1;
                                foreach ($white_lists as $white_list) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $white_list['white_list_number']; ?></td>
                                        <td>
                                            <?php if ($white_list['white_list_status'] == 1) { ?>
                                                <span class="label label-success">Activated</span>
                                            <?php } elseif ($white_list['white_list_status'] == 0) { ?>
                                                <span class="label label-danger">Deactivated</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="switch switch-success switch-sm round switch-inline">
                                                <?php
                                                if ($white_list['white_list_status']) {
                                                    ?>
                                                    <input type="checkbox" id="status<?php echo $i; ?>" checked
                                                           onclick="changeStatus('white', '<?php echo $subtab; ?>', '<?php echo $white_list['white_list_id'] . "_0"; ?>');" />
                                                           <?php
                                                       } else {
                                                           ?>
                                                    <input type="checkbox" id="status<?php echo $i; ?>" 
                                                           onclick="changeStatus('white', '<?php echo $subtab; ?>', '<?php echo $white_list['white_list_id'] . "_1"; ?>');" />
                                                           <?php
                                                       }
                                                       ?>
                                                <label for="status<?php echo $i; ?>"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <button onclick="deleteSettings('white', '<?php echo $subtab; ?>', '<?php echo $white_list['white_list_id']; ?>');"
                                                    type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Number">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="5" align="center">No Records Found!</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <?php echo $paging; ?>
                </div>
            <?php } ?>

            <?php if ($subtab == '6') { ?>
                <div class="col-md-3 padding15">
                    <form role="form" id="validate-basic" method='post' action="javascript:saveSetting(<?php echo $subtab; ?>, 1);">
                        <div class="form-group">
                            <label for="contact_number">Add Black List Number</label>
                            <input type="text" name="contact_number" id="contact_number" value="" placeholder="Enter Conact Number" class="form-control"
                                   required="" data-parsley-error-message="Please Enter Valid Number" data-parsley-type="integer"
                                   data-parsley-minlength="10" data-parsley-maxlength="10">
                        </div>
                        <input type="hidden" name="number_type" id="number_type" value="black" />
                        <button type="submit" class="btn btn-primary" id="btn<?php echo $subtab; ?>1" data-loading-text="Processing..." autocomplete="off">Save</button>
                    </form>
                </div>
                <div class="table-responsive padding15"><!-- <black list numbers> -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Mobile Number</th>
                                <th>Status</th>
                                <th colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <?php
                            if ($black_lists) {
                                $i = 1;
                                foreach ($black_lists as $black_list) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $black_list['black_list_number']; ?></td>
                                        <td>
                                            <?php if ($black_list['black_list_status'] == 1) { ?>
                                                <span class="label label-success">Activated</span>
                                            <?php } elseif ($black_list['black_list_status'] == 0) { ?>
                                                <span class="label label-danger">Deactivated</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="switch switch-success switch-sm round switch-inline">
                                                <?php
                                                if ($black_list['black_list_status']) {
                                                    ?>
                                                    <input type="checkbox" id="status<?php echo $i; ?>" checked
                                                           onclick="changeStatus('black', '<?php echo $subtab; ?>', '<?php echo $black_list['black_list_id'] . "_0"; ?>');" />
                                                           <?php
                                                       } else {
                                                           ?>
                                                    <input type="checkbox" id="status<?php echo $i; ?>" 
                                                           onclick="changeStatus('black', '<?php echo $subtab; ?>', '<?php echo $black_list['black_list_id'] . "_1"; ?>');" />
                                                           <?php
                                                       }
                                                       ?>
                                                <label for="status<?php echo $i; ?>"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <button onclick="deleteSettings('black', '<?php echo $subtab; ?>', '<?php echo $black_list['black_list_id']; ?>');"
                                                    type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Number">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="5" align="center">No Records Found!</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <?php echo $paging; ?>
                </div>
            <?php } ?>

            <?php if ($subtab == '7') { ?>
                <div class="col-md-3 padding15">
                    <form role="form" id="validate-basic" method='post' action="javascript:saveSetting(<?php echo $subtab; ?>, 1);">
                        <div class="form-group">
                            <label for="black_sender_id">Add Black List Sender Id</label>
                            <input type="text" name="black_sender_id" id="black_sender_id" value="" placeholder="Enter Sender Id" class="form-control"
                                   required="" data-parsley-required-message="Please enter sender id" data-parsley-minlength="6" data-parsley-maxlength="6"
                                   data-parsley-minlength-message="Sender must be 6 characters long" data-parsley-maxlength-message="Sender must be 6 characters long">
                        </div>
                        <button type="submit" class="btn btn-primary" id="btn<?php echo $subtab; ?>1" data-loading-text="Processing..." autocomplete="off">Save</button>
                    </form>
                </div>
                <div class="table-responsive padding15"><!-- <black list numbers> -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Sender Id</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <?php
                            if ($black_lists) {
                                $i = 1;
                                foreach ($black_lists as $black_list) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $black_list['sender']; ?></td>
                                        <td>
                                            <button onclick="deleteSettings('sender', '<?php echo $subtab; ?>', '<?php echo $black_list['sender_id'] . "_" . $black_list['sender_key']; ?>');"
                                                    type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Sender Id">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="3" align="center">No Records Found!</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <?php echo $paging; ?>
                </div> 
            <?php } ?>

            <?php if ($subtab == '8') { ?>
                <div class="row" id="reseller_settings">

                    <div class="form-group col-md-3">
                        <h4>Signup SMS</h4>
                        <form role="form" class="settingClass" id="validate-basic" method='post' action="javascript:saveSetting(<?php echo $subtab; ?>, 1);">
                            <div class="form-group col-md-12 padding0">
                                <label for="signup_sender_id">Sender ID</label>
                                <input type="text" id="signup_sender_id" name="signup_sender_id" class="form-control" placeholder="Sender ID"
                                       data-parsley-minlength="6" data-parsley-maxlength="6" data-parsley-minlength-message="Sender Id must be 6 characters long"
                                       data-parsley-maxlength-message="Sender Id must be 6 characters long"
                                       value="<?php echo (isset($settings) && isset($settings->signup_sender)) ? $settings->signup_sender : ""; ?>" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="signup_message">Message</label>
                                <textarea id="signup_message" name="signup_message" rows="3" class="form-control" placeholder="Message"><?php echo (isset($settings) && isset($settings->signup_message)) ? $settings->signup_message : ""; ?></textarea>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label>Example</label>
                                <p>
                                    Hello <span class="text-danger">##fname##</span>,
                                    Thank you for registering.
                                    Please login with following details.
                                    URL: <span class="text-danger">##url##</span>;
                                    Username: <span class="text-danger">##username##</span>;
                                    Password: <span class="text-danger">##password##</span>;
                                </p>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label>Dynamic Variables</label>
                                <p>
                                    For First Name: <span class="text-danger">##fname##</span><br/>
                                    For Username: <span class="text-danger">##username##</span><br/>
                                    For Password: <span class="text-danger">##password##</span><br/>
                                    For Url: <span class="text-danger">##url##</span>
                                </p>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <input type="submit" class="btn btn-primary btn-sm" value="Save" id="btn<?php echo $subtab; ?>1" data-loading-text="Processing..." autocomplete="off" />
                            </div>
                        </form>
                    </div>

                    <div class="form-group col-md-4">
                        <h4>Signup Mail</h4>
                        <form role="form" class="settingClass" id="validate-basic" method='post' action="javascript:saveSetting(<?php echo $subtab; ?>, 2);">
                            <div class="form-group col-md-12 padding0">
                                <label for="signup_subject">Subject</label>
                                <input type="text" id="signup_subject" name="signup_subject" class="form-control" required="" value="<?php echo (isset($settings) && isset($settings->signup_subject)) ? $settings->signup_subject : ""; ?>" placeholder="Subject" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="signup_body">Message Body</label>
                                <textarea id="signup_body" name="signup_body" rows="3" class="form-control" placeholder="Message"><?php echo (isset($settings) && isset($settings->signup_body)) ? $settings->signup_body : ""; ?></textarea>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label>Dynamic Variables</label>
                                <p>
                                    For First Name: <span class="text-danger">##fname##</span><br/>
                                    For Username: <span class="text-danger">##username##</span><br/>
                                    For Password: <span class="text-danger">##password##</span><br/>
                                    For Url: <span class="text-danger">##url##</span>
                                </p>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <input type="submit" class="btn btn-primary btn-sm" value="Save" id="btn<?php echo $subtab; ?>2" data-loading-text="Processing..." autocomplete="off" />
                            </div>
                        </form>
                    </div>


                    <div class="form-group col-md-3">
                        <h4>Expiry & Demo Balance</h4>
                        <form role="form" class="settingClass" id="validate-basic" method='post' action="javascript:saveSetting(<?php echo $subtab; ?>, 3);">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="expiry_days">Expiry</label>
                                    <div class="input-group">
                                        <input type="text" id="expiry_days" name="expiry_days" class="form-control" placeholder="Expiry Days"
                                               data-parsley-type="integer" data-parsley-type-message="Expiry days must be a number"
                                               value="<?php echo (isset($settings) && isset($settings->expiry_days)) ? $settings->expiry_days : "0"; ?>" /> 
                                        <div class="input-group-addon">Days</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="demo_balance">Demo Balance</label>
                                    <div class="input-group">
                                        <input type="text" id="demo_balance" name="demo_balance" class="form-control" placeholder="Demo Balance"
                                               data-parsley-type="integer" data-parsley-type-message="Balance must be a number"
                                               value="<?php echo (isset($settings) && isset($settings->demo_balance)) ? $settings->demo_balance : "0"; ?>" />
                                        <div class="input-group-addon">SMS</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <input type="hidden" name="setting_id" id="setting_id" value="<?php echo (isset($settings) && isset($settings->setting_id)) ? $settings->setting_id : 0; ?>" />
                                    <input type="submit" class="btn btn-primary btn-sm" value="Save" id="btn<?php echo $subtab; ?>3" data-loading-text="Processing..." autocomplete="off" />
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            <?php } ?>

            <?php if ($subtab == '9') { ?>
                <div class="row" id="reseller_settings">

                    <div class="form-group col-md-3">
                        <h4>Forgot Password SMS</h4>
                        <form role="form" class="settingClass" id="validate-basic" method='post' action="javascript:saveSetting(<?php echo $subtab; ?>, 1);">
                            <div class="form-group col-md-12 padding0">
                                <label for="fp_sender_id">Sender ID</label>
                                <input type="text" id="fp_sender_id" name="fp_sender_id" class="form-control" placeholder="Sender ID"
                                       data-parsley-minlength="6" data-parsley-maxlength="6" data-parsley-minlength-message="Sender Id must be 6 characters long"
                                       data-parsley-maxlength-message="Sender Id must be 6 characters long"
                                       value="<?php echo (isset($settings) && isset($settings->forgot_password_sender)) ? $settings->forgot_password_sender : ""; ?>" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="fp_message">Message</label>
                                <textarea id="fp_message" name="fp_message" rows="3" class="form-control" placeholder="Message"><?php echo (isset($settings) && isset($settings->forgot_password_message)) ? $settings->forgot_password_message : ""; ?></textarea>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label>Example</label>
                                <p>
                                    Hello <span class="text-danger">##fname##</span>,
                                    Enter: <span class="text-danger">##code##</span> to change your password
                                    For Username: <span class="text-danger">##username##</span>;
                                    Keep messaging!
                                </p>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label>Dynamic Variables</label>
                                <p>
                                    For First Name: <span class="text-danger">##fname##</span><br/>
                                    For Username: <span class="text-danger">##username##</span><br/>
                                    For Code: <span class="text-danger">##code##</span>
                                </p>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <input type="submit" class="btn btn-primary btn-sm" value="Save" id="btn<?php echo $subtab; ?>1" data-loading-text="Processing..." autocomplete="off" />
                            </div>
                        </form>
                    </div>

                    <div class="form-group col-md-3">
                        <h4>Location SMS</h4>
                        <form role="form" class="settingClass" id="validate-basic" method='post' action="javascript:saveSetting(<?php echo $subtab; ?>, 2);">
                            <div class="form-group col-md-12 padding0">
                                <label for="location_sender_id">Sender ID</label>
                                <input type="text" id="location_sender_id" name="location_sender_id" class="form-control" placeholder="Sender ID"
                                       data-parsley-minlength="6" data-parsley-maxlength="6" data-parsley-minlength-message="Sender Id must be 6 characters long"
                                       data-parsley-maxlength-message="Sender Id must be 6 characters long"
                                       value="<?php echo (isset($settings) && isset($settings->location_sender)) ? $settings->location_sender : ""; ?>" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="location_message">Message</label>
                                <textarea id="location_message" name="location_message" rows="3" class="form-control" placeholder="Message"><?php echo (isset($settings) && isset($settings->location_message)) ? $settings->location_message : ""; ?></textarea>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label>Example</label>
                                <p>
                                    Hello <span class="text-danger">##fname##</span>,
                                    Enter: <span class="text-danger">##code##</span> to verify your location
                                    For Username: <span class="text-danger">##username##</span>
                                    Keep messaging!
                                </p>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label>Dynamic Variables</label>
                                <p>
                                    For First Name: <span class="text-danger">##fname##</span><br/>
                                    For Username: <span class="text-danger">##username##</span><br/>
                                    For Code: <span class="text-danger">##code##</span>
                                </p>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <input type="submit" class="btn btn-primary btn-sm" value="Save" id="btn<?php echo $subtab; ?>2" data-loading-text="Processing..." autocomplete="off" />
                            </div>
                        </form>
                    </div>

                    <div class="form-group col-md-3">
                        <h4>Demo SMS</h4>
                        <form role="form" class="settingClass" id="validate-basic" method='post' action="javascript:saveSetting(<?php echo $subtab; ?>, 3);">
                            <div class="form-group col-md-12 padding0">
                                <label for="demo_sender_id">Sender ID</label>
                                <input type="text" id="demo_sender_id" name="demo_sender_id" class="form-control" placeholder="Sender ID"
                                       data-parsley-minlength="6" data-parsley-maxlength="6" data-parsley-minlength-message="Sender Id must be 6 characters long"
                                       data-parsley-maxlength-message="Sender Id must be 6 characters long"
                                       value="<?php echo (isset($settings) && isset($settings->demo_sender)) ? $settings->demo_sender : ""; ?>" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="demo_message">Message</label>
                                <textarea id="demo_message" name="demo_message" rows="3" class="form-control" placeholder="Message"><?php echo (isset($settings) && isset($settings->demo_message)) ? $settings->demo_message : ""; ?></textarea>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <input type="hidden" name="setting_id" id="setting_id" value="<?php echo (isset($settings) && isset($settings->setting_id)) ? $settings->setting_id : 0; ?>" />
                                <input type="submit" class="btn btn-primary btn-sm" value="Save" id="btn<?php echo $subtab; ?>3" data-loading-text="Processing..." autocomplete="off" />
                            </div>
                        </form>
                    </div>

                </div>
            <?php } ?>

            <?php if ($subtab == '10') { ?>
                <div class="row" id="reseller_settings">

                    <div class="form-group col-md-3">
                        <h4>How many Retry/Resend</h4>
                        <form role="form" class="settingClass" id="validate-basic" method='post' action="javascript:saveSetting(<?php echo $subtab; ?>, 1);">
                            <div class="form-group col-md-12 padding0">
                                <label for="pr_resend_options">For Promotional SMS</label>
                                <input type="text" id="pr_resend_options" name="pr_resend_options" class="form-control" placeholder="Retry/Resend"
                                       data-parsley-type-message="Value must be an interger" data-parsley-type="integer"
                                       data-parsley-min="0" data-parsley-min-message="Value will not be negative"
                                       value="<?php echo (isset($settings) && isset($settings->pr_resend_options)) ? $settings->pr_resend_options : "0"; ?>" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="tr_resend_options">For Transactional SMS</label>
                                <input type="text" id="tr_resend_options" name="tr_resend_options" class="form-control" placeholder="Retry/Resend"
                                       data-parsley-type-message="Value must be an interger" data-parsley-type="integer"
                                       data-parsley-min="0" data-parsley-min-message="Value will not be negative"
                                       value="<?php echo (isset($settings) && isset($settings->tr_resend_options)) ? $settings->tr_resend_options : "0"; ?>" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <input type="hidden" name="setting_id" id="setting_id" value="<?php echo (isset($settings) && isset($settings->setting_id)) ? $settings->setting_id : 0; ?>" />
                                <input type="submit" class="btn btn-primary btn-sm" value="Save" id="btn<?php echo $subtab; ?>1" data-loading-text="Processing..." autocomplete="off" />
                            </div>
                        </form>
                    </div>

                </div>
            <?php } ?>

        </div>
    </div>
</div>
<!-- /Horizontal-tabs -->
<!--<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/validator.js"></script>-->
<script type="text/javascript">
    $('#validate-basic').parsley();
    $('.settingClass').parsley();
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>