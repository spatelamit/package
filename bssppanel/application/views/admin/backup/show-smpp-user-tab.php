<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="submit" class="navbar-toggle collapsed radius0" data-toggle="collapse" data-target="#user-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="user-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="<?php echo (isset($user_tab) && $user_tab == 1) ? "active" : ""; ?>">
                    <a href="javascript:void(0)" onclick="getUserTab('smpp', '1');">SMPP Routing</a>
                </li>
                <li class="<?php echo (isset($user_tab) && $user_tab == 2) ? "active" : ""; ?>">
                    <a href="javascript:void(0)" onclick="getUserTab('smpp', '2');">Set Expiry</a>
                </li>
                <li class="<?php echo (isset($user_tab) && $user_tab == 3) ? "active" : ""; ?>">
                    <a href="javascript:void(0)" onclick="getUserTab('smpp', '3');">Funds</a>
                </li>
                <li class="<?php echo (isset($user_tab) && $user_tab == 4) ? "active" : ""; ?>">
                    <a href="javascript:void(0)" onclick="getUserTab('smpp', '4');">Transaction Logs</a>
                </li>
                <li class="<?php echo (isset($user_tab) && $user_tab == 5) ? "active" : ""; ?>">
                    <a href="javascript:void(0)" onclick="getUserTab('smpp', '5');">Reset Password</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- User SMPP Routing -->
<?php if ($user_tab == 1) { ?>
    <div class="col-md-8">
        <form role="form" class="tab-forms" id="validate-basic" data-parsley-validate method='post' action="javascript:saveUserInfo('smpp', <?php echo $user_tab; ?>);">
            <table class="table">
                <tbody>
                    <tr>
                        <td>
                            <label>Promotional Route</label>
                        </td>
                        <td>
                            <select name="pr_route" id="pr_route" onchange="getSMPPPort(this.value, 'PR');" class="form-control">
                                <option value="">Select User Group</option>
                                <?php
                                if ($pr_user_groups) {
                                    foreach ($pr_user_groups as $pr_ugroup) {
                                        if ($smpp_user['smpp_pr_ugroup_id'] == $pr_ugroup['user_group_id']) {
                                            ?>
                                            <option value="<?php echo $pr_ugroup['user_group_id']; ?>" selected=""><?php echo $pr_ugroup['user_group_name'] . " [" . $pr_ugroup['smsc_id'] . "]"; ?></option>    
                                            <?php
                                        } else {
                                            ?>  
                                            <option value="<?php echo $pr_ugroup['user_group_id']; ?>"><?php echo $pr_ugroup['user_group_name'] . " [" . $pr_ugroup['smsc_id'] . "]"; ?></option>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>SMPP Port</label>
                        </td>
                        <td>
                            <select name="pr_port" id="pr_port" class="form-control">
                                <option value="">Select SMPP Port</option>
                                <?php
                                if ($pr_ports) {
                                    foreach ($pr_ports AS $pr_port) {
                                        if ($smpp_user['smpp_pr_port'] == $pr_port['virtual_port_id']) {
                                            ?>
                                            <option value="<?php echo $pr_port['virtual_port_id']; ?>" selected=""><?php echo $pr_port['virtual_port_no']; ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="<?php echo $pr_port['virtual_port_id']; ?>"><?php echo $pr_port['virtual_port_no']; ?></option>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Transactional Route</label>
                        </td>
                        <td>
                            <select name="tr_route" id="tr_route" onchange="getSMPPPort(this.value, 'TR');" class="form-control">
                                <option value="">Select User Group</option>
                                <?php
                                if ($tr_user_groups) {
                                    foreach ($tr_user_groups as $tr_ugroup) {
                                        if ($smpp_user['smpp_tr_ugroup_id'] == $tr_ugroup['user_group_id']) {
                                            ?>
                                            <option value="<?php echo $tr_ugroup['user_group_id']; ?>" selected=""><?php echo $tr_ugroup['user_group_name'] . " [" . $tr_ugroup['smsc_id'] . "]"; ?></option>    
                                            <?php
                                        } else {
                                            ?>  
                                            <option value="<?php echo $tr_ugroup['user_group_id']; ?>"><?php echo $tr_ugroup['user_group_name'] . " [" . $tr_ugroup['smsc_id'] . "]"; ?></option>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>SMPP Port</label>
                        </td>
                        <td>
                            <select name="tr_port" id="tr_port" class="form-control">
                                <option value="">Select SMPP Port</option>
                                <?php
                                if ($tr_ports) {
                                    foreach ($tr_ports AS $tr_port) {
                                        if ($smpp_user['smpp_tr_port'] == $tr_port['virtual_port_id']) {
                                            ?>
                                            <option value="<?php echo $tr_port['virtual_port_id']; ?>" selected=""><?php echo $tr_port['virtual_port_no']; ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="<?php echo $tr_port['virtual_port_id']; ?>"><?php echo $tr_port['virtual_port_no']; ?></option>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Open Route (Promotional And Transactional)</label>
                        </td>
                        <td>
                            <select name="open_route" id="open_route" onchange="getSMPPPort(this.value, 'OPEN');" class="form-control">
                                <option value="">Select User Group</option>
                                <?php
                                if ($open_user_groups) {
                                    foreach ($open_user_groups as $open_ugroup) {
                                        if ($smpp_user['smpp_open_ugroup_id'] == $open_ugroup['user_group_id']) {
                                            ?>
                                            <option value="<?php echo $open_ugroup['user_group_id']; ?>" selected=""><?php echo $open_ugroup['user_group_name'] . " [" . $open_ugroup['smsc_id'] . "]"; ?></option>    
                                            <?php
                                        } else {
                                            ?>  
                                            <option value="<?php echo $open_ugroup['user_group_id']; ?>"><?php echo $open_ugroup['user_group_name'] . " [" . $open_ugroup['smsc_id'] . "]"; ?></option>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>SMPP Port</label><br/>
                        </td>
                        <td>
                            <select name="open_port" id="open_port" class="form-control">
                                <option value="">Select SMPP Port</option>
                                <?php
                                if ($open_ports) {
                                    foreach ($open_ports AS $open_port) {
                                        if ($smpp_user['smpp_open_port'] == $open_port['virtual_port_id']) {
                                            ?>
                                            <option value="<?php echo $open_port['virtual_port_id']; ?>" selected=""><?php echo $open_port['virtual_port_no']; ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="<?php echo $open_port['virtual_port_id']; ?>"><?php echo $open_port['virtual_port_no']; ?></option>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" class="btn btn-primary">Save Routing</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
<?php } ?>

<!-- User Set Expiry -->
<?php if ($user_tab == 2) { ?>
    <div class="col-md-7">
        <form role="form" class="tab-forms" id="validate-basic" data-parsley-validate method='post' action="javascript:saveUserInfo('smpp', <?php echo $user_tab; ?>);">
            <table class="table">
                <tbody>
                    <tr>
                        <td>
                            <label>Expiry Date</label>
                        </td>
                        <td>
                            <input type="text" required="" name="expiry_date" value="<?php echo $smpp_user['expiry_date']; ?>" id="expiry_date" 
                                   placeholder="Expiry Date" class="form-control" data-parsley-error-message="Please Enter Date" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" value="Set Expiry" name="set_expiry" class="btn btn-primary" id="set_expiry" />
                            <input type="submit" value="Remove Expiry" class="btn btn-inverse" name="remove_expiry" id="remove_expiry" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            var nowDate = new Date();
            var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
            $('#expiry_date').datepicker({
                format: "dd-mm-yyyy",
                startDate: today
            });
        });
    </script>
<?php } ?>

<!-- User Funds -->
<?php if ($user_tab == 3) { ?>
    <div class="col-md-8">
        <form role="form" class="tab-forms" id="validate-basic" data-parsley-validate method='post' action="javascript:saveUserInfo('smpp', <?php echo $user_tab; ?>);">
            <table class="table">
                <tbody>
                    <tr>
                        <td>
                            <label>Route</label>
                        </td>
                        <td>
                            <select class="form-control" name="route" id="route" data-parsley-error-message="Please Select Route" required="">
                                <option value="">Select Route</option>
                                <option value="A">Promotional Route</option>
                                <option value="B">Transactional Route</option>
                                <option value="C">Open Route</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Type</label>
                        </td>
                        <td>
                            <select class="form-control" name="type" id="type" data-parsley-error-message="Please Select Type" required="">
                                <option value="">Select Type</option>
                                <option value="Add">Add</option>
                                <option value="Reduce">Reduce</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Number Of SMS</label>
                        </td>
                        <td>
                            <input type="text" name="sms_balance" id="sms_balance" maxlength="12" onkeyup="getNumberToWords(this.value);" 
                                   placeholder="Number Of SMS" class="form-control" data-parsley-error-message="Please Enter SMS" data-parsley-type="integer" required="" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Price Per SMS</label>
                        </td>
                        <td>
                            <input type="text" name="sms_price" id="sms_price" placeholder="Price Per SMS" onkeyup="calculateAdminAmount(this.value);" class="form-control"
                                   data-parsley-error-message="Please Enter Price" data-parsley-type="number" required="" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Total Amount</label>
                        </td>
                        <td>
                            <input type="text" name="amount" id="amount" placeholder="Amount" readonly="readonly" class="form-control" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Description</label>
                        </td>
                        <td>
                            <textarea name="description" id="description" placeholder="Description" class="form-control" rows="3"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" class="btn btn-primary">Fund Transfer</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
<?php } ?>

<!-- User Transaction Logs -->
<?php if ($user_tab == 4) { ?>
    <div class="col-md-12 scroll tr">
        <form role="form" class="tab-forms" id="validate-basic" data-parsley-validate>
            <table class="table table-bordered table-responsive table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Balance Type</th>
                        <th>SMS</th>
                        <th>Pricing</th>
                        <th>Amount</th>
                        <th>From</th>
                        <!--<th>Description</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($txn_logs) {
                        foreach ($txn_logs as $row) {
                            ?>
                            <tr>
                                <td><?php echo $row['txn_date']; ?></td>
                                <td><?php echo $row['txn_type']; ?></td>
                                <td>
                                    <?php
                                    if ($row['txn_route'] == 'A')
                                        echo "Promotional";
                                    else
                                        echo "Transactional";
                                    ?>
                                </td>
                                <td align="right">
                                    <?php
                                    if ($row['txn_type'] == 'Add')
                                        echo "+" . $row['txn_sms'];
                                    elseif ($row['txn_type'] == 'Reduce')
                                        echo "-" . $row['txn_sms'];
                                    ?>
                                </td>
                                <td align="right"><?php echo $row['txn_price']; ?></td>
                                <td align="right"><?php echo $row['txn_amount']; ?></td>
                                <td>
                                    <?php
                                    if ($row['from_user_id'] == $smpp_user['smpp_user_id']) {
                                        if ($row['to_admin_id'] == "")
                                            echo $row['to_name'];
                                        else
                                            echo $row['to_admin_name'];
                                    }
                                    if ($row['to_user_id'] == $smpp_user['smpp_user_id']) {
                                        if ($row['from_admin_id'] == "")
                                            echo $row['from_name'];
                                        else
                                            echo $row['from_admin_name'];
                                    }
                                    ?>
                                </td>
                                <!--<td><?php echo $row['txn_description']; ?></td>-->
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6">
                                <strong>No Record Found!</strong>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>
<?php } ?>

<!-- User Reset Password -->
<?php if ($user_tab == 5) { ?>
    <div class="col-md-7">
        <form role="form" class="tab-forms" id="validate-basic" data-parsley-validate method='post' action="javascript:saveUserInfo('smpp', <?php echo $user_tab; ?>);">
            <table class="table">
                <tbody>
                    <tr>
                        <td>
                            <label>Reset Password</label>
                        </td>
                        <td>
                            <input type="password" name="password" id="password" placeholder="Password" class="form-control" required=""
                                   data-parsley-error-message="Please Enter Password" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
<?php } ?>

<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/validator.js"></script>
<script type="text/javascript">
    $('#validate-basic').parsley();
</script>