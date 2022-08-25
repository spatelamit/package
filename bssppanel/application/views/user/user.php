
<?php 
  $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
?>

<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />

<div role="tabpanel" class="mt5">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="<?php echo ($subtab == 1) ? "active" : ""; ?>">
            <a href="#um_fund" aria-controls="home" role="tab" data-toggle="tab" onclick="getUserTabs(<?php echo $ref_user['user_id']; ?>, 1)">Funds & Expiry</a>
        </li>
        <li role="presentation" class="<?php echo ($subtab == 2) ? "active" : ""; ?>">
            <a href="#um_profile" aria-controls="settings" role="tab" data-toggle="tab" onclick="getUserTabs(<?php echo $ref_user['user_id']; ?>, 2)">Profile</a>
        </li>
        <li role="presentation" class="<?php echo ($subtab == 3) ? "active" : ""; ?>">
            <a href="#um_dlrs" aria-controls="settings" role="tab" data-toggle="tab" onclick="getUserTabs(<?php echo $ref_user['user_id']; ?>, 3)">Delivery Reports</a>
        </li>
        <li role="presentation" class="<?php echo ($subtab == 4) ? "active" : ""; ?>">
            <a href="#um_dlrs2" aria-controls="settings" role="tab" data-toggle="tab" onclick="getUserTabs(<?php echo $ref_user['user_id']; ?>, 4)">DLR Summary</a>
        </li>
        <div class="text-right"> 
            <?php
            if (isset($ref_user) && $ref_user['user_status']) {
                ?>
                <a href="javascript:void(0);" onclick="changeUserStatus(<?php echo $ref_user['user_id'] . ", 0, " . $subtab; ?>);" class="btn btn-warning btn-sm">Disable</a>
                <?php
            } else {
                ?>
                <a href="javascript:void(0);" onclick="changeUserStatus(<?php echo $ref_user['user_id'] . ", 1, " . $subtab; ?>);" class="btn btn-success btn-sm">Enable</a>
                <?php
            }
            ?>
       <?php
       if($user_id == 65 || $user_id = 526){
           
            if (isset($reseller_user) && $reseller_user != "") {
                if (isset($login_from) && $login_from) {
                    if ($login_from == 'user') {
                        ?>
                        <a href="<?php echo base_url(); ?>user/back_to_account/<?php echo $reseller_user; ?>" class="btn btn-info btn-sm">Login As</a>
                        <?php
                    } elseif ($login_from == 'admin') {
                        ?>
                        <a href="<?php echo base_url(); ?>admin/back_to_account/<?php echo $reseller_user; ?>" class="btn btn-info btn-sm">Login As</a>
                        <?php
                    }
                }
            } else {
                ?>
                <a href="<?php echo base_url(); ?>user/login_as/<?php echo $ref_user_id."/".$user_id; ?>" class="btn btn-info btn-sm">Login As</a>
                <?php
            }
            }
            ?>
              
        </div>
    </ul>

    <div class="tab-content">
        <?php
        if (isset($subtab) && $subtab == 1) {
            ?>

            <div role="tabpanel" class="tab-pane <?php echo ($subtab == 1) ? "active" : ""; ?>" id="um_fund">
                <div class="row ptb15">
                    <div class="col-md-5 borderR">
                        <h3 class="content-header-title">Fund Transfer <br><br>
                            <span class="label label-default pull-right" style="margin-left: 2px;">PR-V <?php echo $ref_user['pr_voice_balance']; ?></span>
                            <span class="label label-default pull-right" style="margin-left: 1px;">PR-V <?php echo $ref_user['pr_voice_balance']; ?></span>
                            <span class="label label-default pull-right" style="margin-left: 1px;">TR-V <?php echo $ref_user['tr_voice_balance']; ?></span>
                            <span class="label label-default pull-right" style="margin-left: 1x;">SC <?php echo $ref_user['short_code_balance']; ?></span>
                            <span class="label label-default pull-right" style="margin-left: 1px;">LC <?php echo $ref_user['long_code_balance']; ?></span>
                            <span class="label label-default pull-right" style="margin-left: 1px;">TR <?php echo $ref_user['tr_sms_balance']; ?></span>
                            <span class="label label-default pull-right" style="margin-left: -5px;">Premium <?php echo $ref_user['prtodnd_balance']; ?></span>
                            <span class="label label-default pull-right" style="margin-left: -1px;">Stock <?php echo $ref_user['stock_balance']; ?></span>
                            <span class="label label-default pull-right" style="margin-left: 1px;">PR <?php echo $ref_user['pr_sms_balance']; ?></span>

                        </h3>
                        <form role="form" class="tab-forms" id="fundForm" data-parsley-validate method='post' 
                              action="javascript:saveUserInfo(<?php echo $ref_user['user_id'] . ", " . $subtab . ", 1"; ?>);">
                            <div class="row">
                                <div class="form-group col-md-6 padding0">

                                    <label for="route">Route</label>
                                    <select name="route" id="route" class="form-control" required="" data-parsley-required-message="Please Select Route">
                                        <option value="">Select Route</option>
                                        <option value="A">Promotional</option>
                                        <option value="C">Stock Promotional</option>
                                        <option value="D">Premium Promotional</option>
                                        <option value="B">Transactional</option>
                                          <option value="I">International</option>
                                        <option value="Long">Long Code</option>
                                        <option value="Short">Short Code</option>
                                        <option value="VA">Promotional Voice</option>
                                        <option value="VB">Dynamic Voice</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="type">Type</label>
                                    <select name="type" id="type" class="form-control" required="" data-parsley-required-message="Please Select Type">
                                        <option value="">Select Type</option>
                                        <option value="Add">Add</option>
                                        <option value="Reduce">Reduce</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 padding0">
                                    <label for="sms_balance">Number of SMS</label>
                                    <input name="sms_balance" id="sms_balance" onkeyup="getNumberToWordsU(this.value);" type="text"
                                           placeholder="Number Of SMS" class="form-control" data-parsley-required-message="Please Enter SMS" data-parsley-type="digits" 
                                           required="" data-parsley-type-message="SMS must be a number" data-parsley-min="1"
                                           data-parsley-min-message="SMS must be greater than 0">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="sms_price">Price Per SMS</label>
                                    <input name="sms_price" id="sms_price" type="text" value="" onkeyup="calculateAmount(this.value);" 
                                           placeholder="Price Per SMS" class="form-control" data-parsley-required-message="Please Enter Price" data-parsley-type="number" 
                                           data-parsley-type-message="Price must be a number" data-parsley-min="0" required=""
                                           data-parsley-min-message="Price must be greater than 0">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 padding0">
                                    <label for="amount">Total Amount</label>
                                    <input name="amount" id="amount" placeholder="Enter Amount" value="" class="form-control" type="text" readonly="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" placeholder="Description" cols="35" rows="2" class="form-control"></textarea>
                                </div>
                                <div class="form-group col-md-12 mt5 padding0">
                                    <button type="submit" class="btn btn-primary btn-sm" id="btn<?php echo $subtab; ?>1" data-loading-text="Processing..." autocomplete="off">Transaction</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--<div class="col-md-1"></div>-->
                    <div class="col-md-3 borderR">
                        <div class="row">
                            <h3 class="content-header-title fhead">Set Expiry</h3>
                            <form role="form" class="tab-forms" id="expiryForm" method='post' 
                                  action="javascript:saveUserInfo(<?php echo $ref_user['user_id'] . ", " . $subtab . ", 2"; ?>)">
                                <div class="form-group col-md-12 padding0">
                                    <label for="expiry_date">Choose Expiry Date</label>
                                    <input name="expiry_date" id="expiry_date" placeholder="Enter Expiry Date" class="form-control" required="" 
                                           data-parsley-required-message="Please Enter Expiry Date" type="text" value="<?php echo $ref_user['expiry_date']; ?>">
                                </div>
                                <div class="form-group col-md-12 mt5 padding0">
                                    <button type="submit" class="btn btn-primary btn-sm" id="btn<?php echo $subtab; ?>2" data-loading-text="Processing..." autocomplete="off">
                                        Set Expiry
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" id="btn<?php echo $subtab; ?>3" data-loading-text="Processing..." autocomplete="off"
                                            onclick="saveUserInfo(<?php echo $ref_user['user_id'] . ", " . $subtab . ", 3"; ?>)">
                                        Remove Expiry
                                    </button>
                                </div>
                            </form>
                        </div>
                        <br/><br/>
                        <div class="row">
                            <h3 class="content-header-title fhead">Account Type</h3>
                            <form role="form" class="tab-forms" id="accountForm" method='post' 
                                  action="javascript:saveUserInfo(<?php echo $ref_user['user_id'] . ", " . $subtab . ", 4"; ?>)">
                                <div class="form-group col-md-12 padding0">
                                    <div class="checkbox">
                                        <label for="account_type" class="fancy-check padding0">
                                            <input type="checkbox" name="account_type" value="1" id="account_type" 
                                                   <?php echo ($ref_user['check_demo_user']) ? "checked" : ""; ?> />
                                            <span>Demo Account</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 padding0 mt5">
                                    <button type="submit" class="btn btn-primary btn-sm" id="btn<?php echo $subtab; ?>4" data-loading-text="Processing..." autocomplete="off">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <h3 class="content-header-title fhead">Black Keywords</h3>
                            <form role="form" class="tab-forms" id="bKeywordForm" method='post' 
                                  action="javascript:saveUserInfo(<?php echo $ref_user['user_id'] . ", " . $subtab . ", 5"; ?>)">
                                <div class="form-group col-md-12 padding0">
                                    <div class="checkbox">
                                        <label for="check_black_keyword" class="fancy-check padding0">
                                            <input type="checkbox" name="check_black_keyword" value="1" id="check_black_keyword" 
                                                   <?php echo ($ref_user['check_black_keyword']) ? "checked" : ""; ?> />
                                            <span>Check Black Keywords</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 padding0 mt5">
                                    <button type="submit" class="btn btn-primary btn-sm" id="btn<?php echo $subtab; ?>5" data-loading-text="Processing..." autocomplete="off">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                    if ($spacial_id_user) {
                        ?>
                        <div class="col-md-2">
                            <h3 class="content-header-title fhead">Set User Ratio</h3>
                            <form role="form" class="tab-forms" id="spacialRatioTR" method='post' 
                                  action="javascript:saveUserInfo(<?php echo $ref_user['user_id'] . ", " . $subtab . ", 6"; ?>)">
                                <div class="col-md-12">
                                    <h4>Transactional Ratio</h4>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Fake Delivered Ratio</label>
                                        <div class="input-group">
                                            <input type="text" name="spatial_tr_deliver_ratio" value="<?php echo $ref_user['spacial_deliver_tr_ratio']; ?>" placeholder="User Fake Delivered Ratio" class="form-control"
                                                   data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                                            <div class="input-group-addon">%</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="ratio_type" value="promotional" />
                                    <input type="submit" class="btn btn-primary btn-xs"   data-loading-text="Processing..." autocomplete="off" value="Set Ratio"/>
                                </div>
                            </form>
                            <form role="form" class="tab-forms" id="spacialRatioPR" method='post' 
                                  action="javascript:saveUserInfo(<?php echo $ref_user['user_id'] . ", " . $subtab . ", 7"; ?>)">
                                <div class="col-md-12">
                                    <h4>Promotional Ratio</h4>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Fake Delivered Ratio</label>
                                        <div class="input-group">
                                            <input type="text" name="spatial_pr_deliver_ratio" value="<?php echo $ref_user['spacial_deliver_pr_ratio']; ?>" placeholder="User Fake Delivered Ratio" class="form-control"
                                                   data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                                            <div class="input-group-addon">%</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <input type="hidden" name="ratio_type" value="promotional" />
                                    <input type="submit" class="btn btn-primary btn-xs"   data-loading-text="Processing..." autocomplete="off" value="Set Ratio"/>
                                </div>
                            </form>
                        </div>

                        <?php
                    }
                    ?>
                    <div class="col-md-12">
                        <hr/>
                        <h3 class="content-header-title tbl">Transaction Logs</h3>
                        <div class="table-responsive">
                            <table class="table table-hover bgf">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Balance Type</th>
                                        <th class="text-right">SMS</th>
                                        <th class="text-right">Pricing</th>
                                        <th class="text-right">Amount</th>
                                        <th>From</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($txn_logs) && $txn_logs) {
                                        foreach ($txn_logs as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row['txn_date']; ?></td>
                                                <td>
                                                    <?php if ($row['txn_type'] == 'Add') { ?>
                                                        <span class="label label-success"><?php echo $row['txn_type']; ?></span>
                                                    <?php } elseif ($row['txn_type'] == 'Reduce') { ?>
                                                        <span class="label label-danger"><?php echo $row['txn_type']; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php if ($row['txn_route'] == 'A') { ?>
                                                        <span class="label label-success">Promotional</span>
                                                    <?php } elseif ($row['txn_route'] == 'B') { ?>
                                                        <span class="label label-danger">Transactional</span>
                                                    <?php } elseif ($row['txn_route'] == 'Long') { ?>
                                                        <span class="label label-info">Long Code</span>
                                                    <?php } elseif ($row['txn_route'] == 'Short') { ?>
                                                        <span class="label label-primary">Short Code</span>
                                                    <?php } elseif ($row['txn_route'] == 'VA') { ?>
                                                        <span class="label label-primary">Promotional Voice</span>
                                                    <?php } elseif ($row['txn_route'] == 'VB') { ?>
                                                        <span class="label label-primary">Dynamic Voice</span>
                                                    <?php } elseif ($row['txn_route'] == 'C') { ?>
                                                        <span class="label label-primary">Stock Promotion</span>
                                                    <?php }elseif ($row['txn_route'] == 'I') { ?>
                                                        <span class="label label-info"> International</span>
                                                    <?php }elseif ($row['txn_route'] == 'D') { ?>
                                                        <span class="label label-primary">Premium Promotion</span>
                                                    <?php }?>
                                                </td>
                                                <td class="text-right">
                                                    <?php
                                                    if ($row['txn_type'] == 'Add')
                                                        echo "+" . $row['txn_sms'];
                                                    elseif ($row['txn_type'] == 'Reduce')
                                                        echo "-" . $row['txn_sms'];
                                                    ?>
                                                </td>
                                                <td class="text-right"><?php echo $row['txn_price']; ?></td>
                                                <td class="text-right"><?php echo $row['txn_amount']; ?></td>
                                                <td>
                                                    <?php
                                                    if ($row['to_user_id'] == $ref_user_id) {
                                                        if ($row['from_admin_id'] == "")
                                                            echo $row['from_name'];
                                                        else
                                                            echo $row['from_admin_name'];
                                                    }
                                                    elseif ($row['from_user_id'] == $ref_user_id) {
                                                        if ($row['to_admin_id'] == "")
                                                            echo $row['to_name'];
                                                        else
                                                            echo $row['to_admin_name'];
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $row['txn_description']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7" align="center">
                                                <strong>No Record Found!</strong>
                                            </td>
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
            <?php
        }
        if (isset($subtab) && $subtab == 2) {
            $alert = explode('|', $ref_user['low_balance_alert']);
            ?>
            <div role="tabpanel" class="tab-pane <?php echo ($subtab == 2) ? "active" : ""; ?>" id="um_profile">
                <div class="row ptb15">
                    <div class="col-md-3">
                        <h2 class="content-header-title fhead">Update Profile</h2>
                        <form role="form" class="tab-forms" id="profileForm" data-parsley-validate method='post' 
                              action="javascript:saveUserInfo(<?php echo $ref_user['user_id'] . ", " . $subtab . ", 1"; ?>);">
                            <div class="row">
                                <div class="form-group col-md-12 padding0">
                                    <label for="name">Name</label>
                                    <input name="name" id="name" placeholder="Enter Name"
                                           value="<?php echo (isset($ref_user) && $ref_user['name']) ? $ref_user['name'] : ""; ?>" class="form-control" required="" 
                                           data-parsley-pattern-message="Please Enter First & Last Name" data-parsley-required-message="Please Enter Full Name" 
                                           data-parsley-pattern="^[A-Za-z]([-']?[A-Za-z]+)*( [A-Za-z]([-']?[A-Za-z]+)*)+$" type="text">
                                </div>
                                <div class="form-group col-md-12 padding0">
                                    <label for="contact">Contact Number</label>
                                    <input name="contact_number" id="contact_number" placeholder="Enter Contact Number" 
                                           value="<?php echo (isset($ref_user) && $ref_user['contact_number']) ? $ref_user['contact_number'] : ""; ?>"
                                           class="form-control" type="text" data-parsley-required-message="Please Enter Contact Number" data-parsley-type-message="Please Enter Valid Contact Number"
                                           data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="10" required=""
                                           data-parsley-minlength-message="Please Enter Valid Contact Number" data-parsley-maxlength-message="Please Enter Valid Contact Number">
                                </div>
                                <div class="form-group col-md-12 padding0">
                                    <label for="email_address">Email Address</label>
                                    <input name="email_address" id="email_address" placeholder="Enter Email Address" 
                                           value="<?php echo (isset($ref_user) && $ref_user['email_address']) ? $ref_user['email_address'] : ""; ?>"
                                           class="form-control" required="" data-parsley-type="email" type="text"
                                           data-parsley-required-message="Please Enter Email Address" data-parsley-type-message="Please Enter Valid Email Address">
                                </div>
                                <div class="form-group col-md-12 padding0">
                                    <label for="company_name">Company Name</label>
                                    <input name="company_name" id="company_name" placeholder="Enter Company Name" 
                                           value="<?php echo (isset($ref_user) && $ref_user['company_name']) ? $ref_user['company_name'] : ""; ?>"
                                           class="form-control" required="" data-parsley-required-message="Please Enter Company Name" type="text">
                                </div>
                                <div class="form-group col-md-12 padding0">
                                    <label for="industry">Industry</label>
                                    <select id="industry" name="industry" class="form-control">
                                        <option value="" <?php echo (isset($ref_user) && $ref_user['industry'] == "") ? 'selected="selected"' : '' ?>>Select Industry</option>
                                        <option value="Agriculture " <?php echo (isset($ref_user) && $ref_user['industry'] == "Agriculture") ? 'selected="selected"' : '' ?>>Agriculture </option>
                                        <option value="Automobile & Transport" <?php echo (isset($ref_user) && $ref_user['industry'] == "Automobile & Transport") ? 'selected="selected"' : '' ?>>Automobile & Transport</option>
                                        <option value="Ecommerce" <?php echo (isset($ref_user) && $ref_user['industry'] == "Ecommerce") ? 'selected="selected"' : '' ?>>E-Commerce</option>
                                        <option value="Education" <?php echo (isset($ref_user) && $ref_user['industry'] == "Education") ? 'selected="selected"' : '' ?>>Education</option>
                                        <option value="Financial Institution" <?php echo (isset($ref_user) && $ref_user['industry'] == "Financial Institution") ? 'selected="selected"' : '' ?>>Financial Institution</option>
                                        <option value="Gym" <?php echo (isset($ref_user) && $ref_user['industry'] == "Gym") ? 'selected="selected"' : '' ?>>Gym</option>
                                        <option value="Hospitality" <?php echo (isset($ref_user) && $ref_user['industry'] == "Hospitality") ? 'selected="selected"' : '' ?>>Hospitality</option>
                                        <option value="IT Company" <?php echo (isset($ref_user) && $ref_user['industry'] == "IT Company") ? 'selected="selected"' : '' ?>>IT Company</option>
                                        <option value="Lifestyle Clubs" <?php echo (isset($ref_user) && $ref_user['industry'] == "Lifestyle Clubs") ? 'selected="selected"' : '' ?>>Lifestyle Clubs</option>
                                        <option value="Logistics" <?php echo (isset($ref_user) && $ref_user['industry'] == "Logistics") ? 'selected="selected"' : '' ?>>Logistics</option>
                                        <option value="Marriage Bureau" <?php echo (isset($ref_user) && $ref_user['industry'] == "Marriage Bureau") ? 'selected="selected"' : '' ?>>Marriage Bureau</option>
                                        <option value="Media & Advertisement" <?php echo (isset($ref_user) && $ref_user['industry'] == "Media & Advertisement") ? 'selected="selected"' : '' ?>>Media & Advertisement</option>
                                        <option value="Personal Use" <?php echo (isset($ref_user) && $ref_user['industry'] == "Personal Use") ? 'selected="selected"' : '' ?>>Personal Use</option>
                                        <option value="Political" <?php echo (isset($ref_user) && $ref_user['industry'] == "Political") ? 'selected="selected"' : '' ?>>Political </option>
                                        <option value="Public Sector" <?php echo (isset($ref_user) && $ref_user['industry'] == "Public Sector") ? 'selected="selected"' : '' ?>>Public Sector</option>
                                        <option value="Real estate" <?php echo (isset($ref_user) && $ref_user['industry'] == "Real estate") ? 'selected="selected"' : '' ?>>Real estate</option>
                                        <option value="Reseller" <?php echo (isset($ref_user) && $ref_user['industry'] == "Reseller") ? 'selected="selected"' : '' ?>>Reseller</option>
                                        <option value="Retail & FMCG" <?php echo (isset($ref_user) && $ref_user['industry'] == "Retail & FMCG") ? 'selected="selected"' : '' ?>>Retail & FMCG</option>
                                        <option value="Stock and Commodity" <?php echo (isset($ref_user) && $ref_user['industry'] == "Stock and Commodity") ? 'selected="selected"' : '' ?>>Stock and Commodity</option>
                                        <option value="Telecom" <?php echo (isset($ref_user) && $ref_user['industry'] == "Telecom") ? 'selected="selected"' : '' ?>>Telecom</option>
                                        <option value="Tips And Alert" <?php echo (isset($ref_user) && $ref_user['industry'] == "Tips And Alert") ? 'selected="selected"' : '' ?>>Tips And Alert</option>
                                        <option value="Travel" <?php echo (isset($ref_user) && $ref_user['industry'] == "Travel") ? 'selected="selected"' : '' ?>>Travel</option>
                                        <option value="Wholesalers Distributors or Manufacturers" <?php echo (isset($ref_user) && $ref_user['industry'] == "Wholesalers Distributors or Manufacturers") ? 'selected="selected"' : '' ?>>Wholesalers Distributors or Manufacturers</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12 mt5 padding0">
                                    <button type="submit" class="btn btn-primary btn-sm" id="btn<?php echo $subtab; ?>1" data-loading-text="Processing..." autocomplete="off">
                                        Update Profile
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-3">
                        <h2 class="content-header-title fhead">Reset Password</h2>
                        <form role="form" class="tab-forms" id="passwordForm" data-parsley-validate method='post' 
                              action="javascript:saveUserInfo(<?php echo $ref_user['user_id'] . ", " . $subtab . ", 2"; ?>);">
                            <div class="row">
                                <div class="form-group col-md-12 padding0">
                                    <label for="password">Password</label>
                                    <input name="password" id="password" placeholder="Enter Password" class="form-control" required="" 
                                           data-parsley-required-message="Please Enter Password" type="password">
                                </div>

                                <div class="form-group col-md-12 mt5 padding0">
                                    <button type="submit" class="btn btn-primary btn-sm" id="btn<?php echo $subtab; ?>2" data-loading-text="Processing..." autocomplete="off">
                                        Reset Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-3">
                        <h2 class="content-header-title fhead">Low Balance Alert</h2>
                        <form role="form" class="tab-forms" id="alertForm" data-parsley-validate method='post' 
                              action="javascript:saveUserInfo(<?php echo $ref_user['user_id'] . ", " . $subtab . ", 3"; ?>);">
                            <div class="row">
                                <div class="form-group col-md-6 padding0">
                                    <div class="checkbox">
                                        <label for="alert_by_sms" class="fancy-check padding0">
                                            <input type="checkbox" name="alert_by_sms" value="1" id="alert_by_sms" 
                                                   <?php echo (isset($alert) && $alert[0] == 1) ? "checked" : ""; ?> />
                                            <span>SMS</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 padding0">
                                    <div class="checkbox">
                                        <label for="alert_by_email" class="fancy-check padding0">
                                            <input type="checkbox" name="alert_by_email" value="1" id="alert_by_email" 
                                                   <?php echo (isset($alert) && $alert[1] == 1) ? "checked" : ""; ?> /> 
                                            <span>Email</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 padding0">
                                    <label>Minimum Promotional SMS</label>
                                    <input type="text" name="pr_sms" id="pr_sms" placeholder="Promotional SMS" class="form-control" required=""
                                           data-parsley-required-message="Please Enter SMS" 
                                           value="<?php echo (isset($ref_user) && $ref_user['low_balance_pr']) ? $ref_user['low_balance_pr'] : ""; ?>"
                                           data-parsley-type="integer" data-parsley-type-message="Please enter valid number or 0"
                                           data-parsley-min="0" data-parsley-min-message="Please enter valid number or 0" />
                                </div>
                                <div class="form-group col-md-12 padding0">
                                    <label>Minimum Transactional SMS</label>
                                    <input type="text" name="tr_sms" id="tr_sms" placeholder="Transactional SMS" class="form-control" required=""
                                           data-parsley-required-message="Please Enter SMS" 
                                           value="<?php echo (isset($ref_user) && $ref_user['low_balance_tr']) ? $ref_user['low_balance_tr'] : ""; ?>"
                                           data-parsley-type="integer" data-parsley-type-message="Please enter valid number or 0"
                                           data-parsley-min="0" data-parsley-min-message="Please enter valid number or 0" />
                                </div>
                                <div class="form-group col-md-12 mt5 padding0">
                                    <button type="submit" class="btn btn-primary btn-sm" id="btn<?php echo $subtab; ?>3" data-loading-text="Processing..." autocomplete="off">
                                        Set Alert
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>   
                </div>
            </div>
            <?php
        }
        if (isset($subtab) && $subtab == 3) {
            ?>
            <div role="tabpanel" class="tab-pane <?php echo ($subtab == 3) ? "active" : ""; ?>" id="um_dlrs">
                <div class="row ptb15">
                    <div class="col-md-12">
                        <h2 class="content-header-title tbl">Delivery Reports</h2>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped bgf">
                                <thead>
                                    <tr>
                                        <th>Sent Time</th>
                                        <th>Campaign</th>
                                        <th>Message</th>
                                        <th>SMS</th>
                                        <th>Request</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($delivery_reports) && $delivery_reports) {
                                        $i = 1;
                                        foreach ($delivery_reports as $delivery_report) {
                                            ?>
                                            <tr>
                                                <td><?php echo $delivery_report['submit_date']; ?></td>
                                                <td><?php echo $delivery_report['campaign_name']; ?></td>
                                                <td width="382px">
                                                    <b>
                                                        <?php echo $delivery_report['sender_id']; ?>
                                                    </b>
                                                    <br/>
                                                    <?php echo urldecode($delivery_report['message']); ?>
                                                </td>
                                                <td>
                                                    <strong>Submitted: </strong><?php echo $delivery_report['total_messages']; ?><br/>
                                                    <strong>Credit: </strong><?php echo $delivery_report['total_credits']; ?><br/>
                                                    <strong>Deducted: </strong><?php echo $delivery_report['total_deducted']; ?><br/>
                                                    <strong>Processed: </strong><?php echo $delivery_report['actual_message']; ?><br/>
                                                </td>
                                                <td>
                                                    <ul class="fa-ul padding0">
                                                        <li>
                                                            <i class="fa-li fa fa-envelope-o"></i>
                                                            <?php
                                                            if ($delivery_report['route'] == 'A')
                                                                echo "Promotional";
                                                            elseif ($delivery_report['route'] == 'B')
                                                                echo "Transactional";
                                                            elseif ($delivery_report['route'] == 'C')
                                                                echo "Stock Promotion";
                                                            elseif ($delivery_report['route'] == 'D')
                                                                echo "Premium Promotion";
                                                            ?>
                                                        </li>
                                                        <li>
                                                            <i class="fa-li fa fa-bolt"></i>
                                                            <?php
                                                            if ($delivery_report['message_type'] == 2)
                                                                echo "Unicode";
                                                            elseif ($delivery_report['message_type'] == 1)
                                                                echo "Normal";
                                                            ?>
                                                        </li>
                                                        <li>
                                                            <i class="fa-li fa fa-bookmark-o"></i>
                                                            <?php
                                                            if ($delivery_report['flash_message'])
                                                                echo "<li>Flash</li>";
                                                            ?>
                                                            <?php echo strtolower($delivery_report['campaign_uid']); ?>
                                                        </li>
                                                        <li>
                                                            <i class="fa-li fa fa-tags"></i>
                                                            <?php echo $delivery_report['request_by']; ?>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="5">No Record Found!</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td align="center" colspan="5">
                                            <ul id="pagination-demo" class="pagination margin0"></ul>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        if (isset($subtab) && $subtab == 4) {
            ?>
        <input type="hidden" id="ref_id" value="<?php echo $ref_user_id; ?>">
        <div style="margin-top: 20px;">
        <div style="margin-left: 750px;" class="col-md-3 padding15">
     <input class="form-control " type = "input" id = "search_id" size = "30" placeholder="Search DLR Summary By Date"  />
    
      </div>
	  
    <input class="btn btn-primary" data-loading-text="Searching..." autocomplete="off" type = "button" id = "click_report" value = "GET REPORT" style="background-color: #2780E3; color: white; height: 33px;" />
        </div>
        <br/>
        
        

            <div role="tabpanel" class="tab-pane <?php echo ($subtab == 4) ? "active" : ""; ?>" id="um_dlrs2">
                <div class="row ptb15">
                    <div class="col-md-12">
                        <h2 class="content-header-title tbl"> DLR Summary</h2>
                        <div class="table-responsive">
                            <div class="table-responsive" id="search_info">
                                <table class="table table-bordered bgf">
                                    <tr>   <th style="color:#4A9AB5;">Submit</th> 
                                         <th style="color:#4A9AB5;">DND</th> 
                                        <th style="color:#4271A5;">Sent</th> 
                                        <th style="color:#8CAA52;">Delivered</th> 
                                        <th style="color:#AD4942;">Failed</th> 
                                        <th style="color:#DE8742;">Rejected</th> 
                                        <th>Total</th>
                                        <th>Total Deduction</th> </tr>

                                    <tr> 
                                        <?php
                                        if (isset($total_sms) && $total_sms) {
                                            $delivered = 0;
                                            $sent = 0;
                                            $failed = 0;
                                            $rejected = 0;
                                            $submit = 0;
                                            $dnd = 0;
                                            $total_record = sizeof($total_sms);
                                            foreach ($total_sms as $total_data) {
                                                $status = $total_data['status'];
                                                if ($status == 1) {
                                                    $delivered++;
                                                } else if ($status == 2) {
                                                    $failed++;
                                                } else if ($status == 3) {
                                                    $sent++;
                                                } else if ($status == 16) {
                                                    $rejected++;
                                                } else if ($status == 31) {
                                                    $submit++;
                                                }else if ($status == 'DND') {
                                                    $dnd++;
                                                }
                                            }
                                            ?>
                                            
                                            <th style="color:#4A9AB5;"><?php echo $submit; ?></th>
                                            <th style="color:#4A9AB5;"><?php echo  $dnd; ?></th>
                                            <th style="color:#4271A5;"><?php echo $sent; ?></th>
                                            <th style="color:#8CAA52;"><?php echo $delivered; ?></th>
                                            <th style="color:#AD4942;"><?php echo $failed; ?></th>
                                            <th style="color:#DE8742;"><?php echo $rejected; ?></th>
                                            <th><?php echo $total_record; ?></th>
                                            <th><?php echo $total_deduction[0]['SUM(`total_deducted`)']; ?></th>

                                            <?php
                                        } else {
                                            ?> 
                                            <th colspan="10" style="text-align:center;"> No Result</th> 
                                            <?php
                                        }
                                        ?>
                                    </tr> 



                                </table>

                            </div>                                    
                        </div>
                        <?php if (isset($total_sms) && $total_sms) { ?>
                            <form method="POST" target="_blank" action="<?php echo base_url(); ?>user/get_dlr_graph">
                                <input type="hidden" name="total_submit" value="<?php echo $submit; ?>">
                                <input type="hidden" name="sent"  value="<?php echo $sent; ?>">
                                <input type="hidden" name="delivered"  value="<?php echo $delivered; ?>">
                                <input type="hidden" name="failed"  value="<?php echo $failed; ?>">
                                <input type="hidden" name="rejected"  value="<?php echo $rejected; ?>">

                                <div class="form-group col-md-12 mt5 padding0" style="text-align: center;">
                                    <button type="submit" class="btn btn-primary btn-sm" data-loading-text="Processing..." autocomplete="off" style=" font-weight: bold;">Click to See Delivery Report Graph</button>
                                </div>
                            </form>
                        <?php } ?>

                    </div>
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
            startDate: today
        });
    });
    $('.tab-forms').parsley();
</script>
<script type="text/javascript">
        $(document).ready(function ()
        {
            var nowDate = new Date();
            var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate());
            $('#search_id').datetimepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                endDate: today,
                todayHighlight: true
            });
           
        });
    </script>
    <script type = "text/javascript" language = "javascript">
         $(document).ready(function() {
            $("#click_report").click(function(event){
               var search = $("#search_id").val();
               var ref_user_id = $("#ref_id").val();
               
               $("#search_info").load('<?php  base_url(); ?>/index.php/user/search_dlr_by_date', {"search":search,"ref_user_id":ref_user_id} );
            });
         });
      </script>



<!--<script type="text/javascript">
   alert('working');
   $(function () {
       var pending =<?php // echo $total_pending;  ?>;
       var dnd =<?php //echo $total_dnd;  ?>;
       var reject =<?php // echo $total_reject;  ?>;
       var block =<?php // echo $total_blocked;  ?>;
       var submit =<?php // echo ($total_submit) ? $total_submit : $total_sent;  ?>;
       var failed =<?php // echo $total_failed;  ?>;
       var dlrd =<?php // echo $total_delivered;  ?>;
       // Build the bar chart
       /*
        $('#bar_graph_container').highcharts({
        chart: {
        type: 'column'
        },
        title: {
        text: 'Delivery Status'
        },
        xAxis: {
        title: {
        text: 'Designations'
        },
        categories: status,
        labels: {
        rotation: 0,
        align: 'center',
        style: {
        fontSize: '15px',
        fontFamily: 'Verdana, sans-serif'
        }
        }
        },
        yAxis: {
        title: {
        text: 'Total requests'
        }
        },
        legend: {
        enabled: false
        },
        plotOptions: {
        series: {
        borderWidth: 0,
        dataLabels: {
        enabled: true,
        format: '{point.y:.f}'
        }
        }
        },
        tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.f}</b><br/>'
        },
        series: [{
        name: 'Status',
        colorByPoint: true,
        data: [
        ['Sent', sent],
        ['DND', dnd],
        ['Blocked', block],
        ['Submit', submit],
        ['Failed', failed],
        ['Delivered', dlrd]
        ]
        }]
        });
        */
       // Build the pie chart
       Highcharts.setOptions({
           colors: ['#4A9AB5', '#C2C2C2', '#DE8742', '#735994', '#4271A5', '#AD4942', '#8CAA52']
       });

       $('#pie_graph_container').highcharts({
          
           chart: {
               plotBackgroundColor: null,
               plotBorderWidth: null,
               plotShadow: false
           },
           title: {
               text: 'Status'
           },
           tooltip: {
               pointFormat: '{series.name}: <b>{point.y:.f}</b>'
           },
           plotOptions: {
               pie: {
                   allowPointSelect: true,
                   cursor: 'pointer',
                   dataLabels: {
                       enabled: false
                   },
                   showInLegend: true
               }
           },
           series: [{
                   type: 'pie',
                   name: 'Total',
                   data: [
                       ['Pending', pending],
                       ['NDNC', dnd],
                       ['Rejected', reject],
                       ['Blocked', block],
                       ['Submit', submit],
                       ['Failed', failed],
                       ['Delivered', dlrd]
                   ]
               }]
       });
   });
</script>-->