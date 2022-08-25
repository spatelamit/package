<?php
$tab_array = array(
    1 => 'Spam Transactional',
    2 => 'Spam Promotional',
    3 => 'SMS Tracking',
    4 => 'Settings',
    5 => 'SMS Rate Plans',
    6 => 'Account Managers',
    7 => 'User Groups',
    8 => 'Reseller & Users',
    9 => 'User Balance',
    10 => 'System Logs',
    11 => 'Sender Ids',
    12 => 'Keywords',
    13 => 'SMPP Logs',
    14 => 'Virtual Numbers',
    15 => 'Kannel Monitor',
    16 => 'Kannel Configuration',
    17 => 'Selles Tracker',
    18 => 'Payment Approval',
    19 => 'Payment Details',
    20 => 'Daily Signup',
    21 => 'Controller History',
    22 => 'Daily Reports',
    23 => 'Test OTP',
    24 => 'Subscriptions',
);
$allocated_permissions_array = array();
if (isset($allocated_permissions) && $allocated_permissions) {
    $allocated_permissions_array = explode(',', $allocated_permissions->permissions);
}
?>
<div class="col-md-12 form-group padding0">
    <label>Permissions:</label>
</div>
<?php
if (isset($tab_array) && $tab_array) {
    foreach ($tab_array as $key => $value) {
        ?>
        <div class="col-md-2 form-group padding0">
            <label for="tab<?php echo $key; ?>" class="fancy-check">
                <input type="checkbox" name="permission[]" id="tab<?php echo $key; ?>" value="<?php echo $key; ?>"
                       <?php echo ($allocated_permissions_array && in_array($key, $allocated_permissions_array)) ? "checked" : ""; ?> />
                <span><?php echo $value; ?></span>
            </label>
        </div>
        <?php
    }
}
?>
<div class="col-md-12 padding0">
    <button type="submit" class="btn btn-primary btn-sm" name="save" id="save"
            data-loading-text="Processing..." autocomplete="off">Set Permissions</button>
</div>