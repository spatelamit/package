<?php
// Core Group
if ($page == 'core') {
    $conf = new ConfigTool();
    // Get File
    $flag = false;
    if (file_exists("KannelC/Config/" . $file) && filesize('KannelC/Config/' . $file)) {
        if ($conf->setConfigFromFile("KannelC/Config/" . $file)) {
            $flag = true;
        }
    }
    ?>
    <form action="<?php echo base_url(); ?>kannel_control/save_config/<?php echo $page; ?>" method="post">
        <div class="row">
            <div class="col-md-3 padding0">
                <div class="col-md-12 form-group">
                    <label>Group</label>
                    <input type="text" class="form-control" name="field[group]" value="core" readonly="" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Admin Port</label>
                    <input type="text" class="form-control" name="field[admin-port]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('admin-port')) ? $conf->get('admin-port') : '0'; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Admin Password</label>
                    <input type="text" class="form-control" name="field[admin-password]"
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('admin-password')) ? $conf->get('admin-password') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Status Password</label>
                    <input type="text" class="form-control" name="field[status-password]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('status-password')) ? $conf->get('status-password') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Admin Deny IP</label>
                    <input type="text" class="form-control" name="field[admin-deny-ip]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('admin-deny-ip')) ? $conf->get('admin-deny-ip') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Admin Allow IP</label>
                    <input type="text" class="form-control" name="field[admin-allow-ip]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('admin-allow-ip')) ? $conf->get('admin-allow-ip') : ''; ?>" />
                </div>
            </div>
            <div class="col-md-3 padding0">
                <div class="col-md-12 form-group">
                    <label>SMSBox Interface</label>
                    <input type="text" class="form-control" name="field[smsbox-interface]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('smsbox-interface')) ? $conf->get('smsbox-interface') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>SMSBox Port</label>
                    <input type="text" class="form-control" name="field[smsbox-port]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('smsbox-port')) ? $conf->get('smsbox-port') : '0'; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Log File</label>
                    <input type="text" class="form-control" name="field[log-file]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('log-file')) ? $conf->get('log-file') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Log Level</label>
                    <input type="text" class="form-control" name="field[log-level]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('log-level')) ? $conf->get('log-level') : '0'; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Access Log File</label>
                    <input type="text" class="form-control" name="field[access-log]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('access-log')) ? $conf->get('access-log') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>DLR Storage</label>
                    <input type="text" class="form-control" name="field[dlr-storage]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('dlr-storage')) ? $conf->get('dlr-storage') : ''; ?>" />
                </div>
            </div>
            <div class="col-md-3 padding0">
                <div class="col-md-12 form-group">
                    <label>SMS Incoming Queue Limit</label>
                    <input type="text" class="form-control" name="field[sms-incoming-queue-limit]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('sms-incoming-queue-limit')) ? $conf->get('sms-incoming-queue-limit') : '0'; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>SMS Outgoing Queue Limit</label>
                    <input type="text" class="form-control" name="field[sms-outgoing-queue-limit]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('sms-outgoing-queue-limit')) ? $conf->get('sms-outgoing-queue-limit') : '0'; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>SMSBox Max Pending</label>
                    <input type="text" class="form-control" name="field[smsbox-max-pending]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('smsbox-max-pending')) ? $conf->get('smsbox-max-pending') : '0'; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>SMS Resend Frequency</label>
                    <input type="text" class="form-control" name="field[sms-resend-freq]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('sms-resend-freq')) ? $conf->get('sms-resend-freq') : '0'; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>SMS Resend Retry</label>
                    <input type="text" class="form-control" name="field[sms-resend-retry]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('sms-resend-retry')) ? $conf->get('sms-resend-retry') : '0'; ?>" />
                </div>
            </div>
            <div class="col-md-12 padding0">
                <div class="col-md-12 form-group">
                    <input type="hidden" name="group_type" value="core" />
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
    <?php
}

// SMSBox Group
if ($page == 'smsbox') {
    $conf = new ConfigTool();
    // Get File
    $flag = false;
    if (file_exists("KannelC/Config/" . $file) && filesize('KannelC/Config/' . $file)) {
        if ($conf->setConfigFromFile("KannelC/Config/" . $file)) {
            $flag = true;
        }
    }
    ?>
    <form action="<?php echo base_url(); ?>kannel_control/save_config/<?php echo $page; ?>" method="post">
        <div class="row">
            <div class="col-md-3 padding0">
                <div class="col-md-12 form-group">
                    <label>Group</label>
                    <input type="text" class="form-control" name="field[group]" value="smsbox" readonly="" />
                </div>
                <div class="col-md-12 form-group">
                    <label>SMSBox ID</label>
                    <input type="text" class="form-control" name="field[smsbox-id]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('smsbox-id')) ? $conf->get('smsbox-id') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Bearerbox Host</label>
                    <input type="text" class="form-control" name="field[bearerbox-host]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('bearerbox-host')) ? $conf->get('bearerbox-host') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Bearerbox Port</label>
                    <input type="text" class="form-control" name="field[bearerbox-port]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('bearerbox-port')) ? $conf->get('bearerbox-port') : '0'; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>SendSMS Port</label>
                    <input type="text" class="form-control" name="field[sendsms-port]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('sendsms-port')) ? $conf->get('sendsms-port') : '0'; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Log File</label>
                    <input type="text" class="form-control" name="field[log-file]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('log-file')) ? $conf->get('log-file') : ''; ?>" />
                </div>
            </div>
            <div class="col-md-3 padding0">
                <div class="col-md-12 form-group">
                    <label>Log Level</label>
                    <input type="text" class="form-control" name="field[log-level]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('log-level')) ? $conf->get('log-level') : '0'; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Access Log File</label>
                    <input type="text" class="form-control" name="field[access-log]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('access-log')) ? $conf->get('access-log') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>SendSMS Chars</label>
                    <input type="text" class="form-control" name="field[sendsms-chars]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('sendsms-chars')) ? $conf->get('sendsms-chars') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Max Pending Requests</label>
                    <input type="text" class="form-control" name="field[max-pending-requests]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('max-pending-requests')) ? $conf->get('max-pending-requests') : '0'; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>SendSMS URL</label>
                    <input type="text" name="field[sendsms-url]" class="form-control"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('sendsms-url')) ? $conf->get('sendsms-url') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>MO Recode</label>
                    <input type="text" class="form-control" name="field[mo-recode]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('mo-recode')) ? $conf->get('mo-recode') : '0'; ?>" />
                </div>
            </div>
            <div class="col-md-12 padding0">
                <div class="col-md-12 form-group">
                    <input type="hidden" name="group_type" value="smsbox" />
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
    <?php
}

// DB Connection Group
if ($page == 'db_connection') {
    $conf = new ConfigTool();
    // Get File
    $flag = false;
    if (file_exists("KannelC/Config/" . $file) && filesize('KannelC/Config/' . $file)) {
        if ($conf->setConfigFromFile("KannelC/Config/" . $file)) {
            $flag = true;
        }
    }
    ?>
    <form action="<?php echo base_url(); ?>kannel_control/save_config/<?php echo $page; ?>" method="post">
        <div class="row">
            <div class="col-md-3 padding0">
                <div class="col-md-12 form-group">
                    <label>Group</label>
                    <input type="text" class="form-control" name="field[group]" value="mysql-connection" readonly="" />
                </div>
                <div class="col-md-12 form-group">
                    <label>ID</label>
                    <input type="text" class="form-control" name="field[id]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('id')) ? $conf->get('id') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Host</label>
                    <input type="text" class="form-control" name="field[host]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('host')) ? $conf->get('host') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" name="field[username]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('username')) ? $conf->get('username') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Password</label>
                    <input type="text" class="form-control" name="field[password]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('password')) ? $conf->get('password') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Database</label>
                    <input type="text" class="form-control" name="field[database]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('database')) ? $conf->get('database') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Max Connections</label>
                    <input type="text" class="form-control" name="field[max-connections]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('max-connections')) ? $conf->get('max-connections') : '0'; ?>" />
                </div>
            </div>
            <div class="col-md-12 padding0">
                <div class="col-md-12 form-group">
                    <input type="hidden" name="group_type" value="db_connection" />
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
    <?php
}

// DLR DB Group
if ($page == 'dlr_db') {
    $conf = new ConfigTool();
    // Get File
    $flag = false;
    if (file_exists("KannelC/Config/" . $file) && filesize('KannelC/Config/' . $file)) {
        if ($conf->setConfigFromFile("KannelC/Config/" . $file)) {
            $flag = true;
        }
    }
    ?>
    <form action="<?php echo base_url(); ?>kannel_control/save_config/<?php echo $page; ?>" method="post">
        <div class="row">
            <div class="col-md-3 padding0">
                <div class="col-md-12 form-group">
                    <label>Group</label>
                    <input type="text" class="form-control" name="field[group]" value="dlr-db" readonly="" />
                </div>
                <div class="col-md-12 form-group">
                    <label>ID</label>
                    <input type="text" class="form-control" name="field[id]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('id')) ? $conf->get('id') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Table</label>
                    <input type="text" class="form-control" name="field[table]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('table')) ? $conf->get('table') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Field SMSC</label>
                    <input type="text" class="form-control" name="field[field-smsc]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('field-smsc')) ? $conf->get('field-smsc') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Field Timestamp</label>
                    <input type="text" class="form-control" name="field[field-timestamp]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('field-timestamp')) ? $conf->get('field-timestamp') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Field Destination</label>
                    <input type="text" class="form-control" name="field[field-destination]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('field-destination')) ? $conf->get('field-destination') : ''; ?>" />
                </div>
            </div>
            <div class="col-md-3 padding0">
                <div class="col-md-12 form-group">
                    <label>Field Source</label>
                    <input type="text" class="form-control" name="field[field-source]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('field-source')) ? $conf->get('field-source') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Field Service</label>
                    <input type="text" class="form-control" name="field[field-service]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('field-service')) ? $conf->get('field-service') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Field URL</label>
                    <input type="text" class="form-control" name="field[field-url]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('field-url')) ? $conf->get('field-url') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Field Mask</label>
                    <input type="text" class="form-control" name="field[field-mask]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('field-mask')) ? $conf->get('field-mask') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Field Status</label>
                    <input type="text" class="form-control" name="field[field-status]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('field-status')) ? $conf->get('field-status') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Field Boxc-id</label>
                    <input type="text" class="form-control" name="field[field-boxc-id]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('field-boxc-id')) ? $conf->get('field-boxc-id') : ''; ?>" />
                </div>
            </div>
            <div class="col-md-12 padding0">
                <div class="col-md-12 form-group">
                    <input type="hidden" name="group_type" value="dlr_db" />
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
    <?php
}

// SMS Service Group
if ($page == 'sms_service') {
    $conf = new ConfigTool();
    // Get File
    $flag = false;
    if (file_exists("KannelC/Config/" . $file) && filesize('KannelC/Config/' . $file)) {
        if ($conf->setConfigFromFile("KannelC/Config/" . $file)) {
            $flag = true;
        }
    }
    ?>
    <form action="<?php echo base_url(); ?>kannel_control/save_config/<?php echo $page; ?>" method="post">
        <div class="row">
            <div class="col-md-3 padding0">
                <div class="col-md-12 form-group">
                    <label>Group</label>
                    <input type="text" class="form-control" name="field[group]" value="sms-service" readonly="" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Keyword</label>
                    <input type="text" class="form-control" name="field[keyword]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('keyword')) ? $conf->get('keyword') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Text</label>
                    <input type="text" class="form-control" name="field[text]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('text')) ? $conf->get('text') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Accept X-Kannel Headers</label>
                    <input type="text" class="form-control" name="field[accept-x-kannel-headers]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('accept-x-kannel-headers')) ? $conf->get('accept-x-kannel-headers') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Concatenation</label>
                    <input type="text" class="form-control" name="field[concatenation]" 
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('concatenation')) ? $conf->get('concatenation') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Catch All</label>
                    <input type="text" class="form-control" name="field[catch-all]"  
                           value="<?php echo ($file && file_exists("KannelC/Config/" . $file) && $conf->get('catch-all')) ? $conf->get('catch-all') : ''; ?>" />
                </div>
            </div>
            <div class="col-md-12 padding0">
                <div class="col-md-12 form-group">
                    <input type="hidden" name="group_type" value="sms_service" />
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
    <?php
}

// SMSC Group
if ($page == 'smsc') {
    ?>
<!--
    <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-12">
            <a href="<?php echo base_url(); ?>kannel_control/create/smsc/transceiver">SMSC (Transceiver Mode)</a>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <a href="<?php echo base_url(); ?>kannel_control/create/smsc/transmitter">SMSC (Transmitter Mode)</a>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <a href="<?php echo base_url(); ?>kannel_control/create/smsc/receiver">SMSC (Receiver Mode)</a>
        </div>
    </div>
-->
    <?php
    // Transceiver Mode
    if ($id == 'smsc_trx') {
        $conf = new ConfigTool();
        // Get File
        $flag = false;
        if (file_exists("KannelC/SMSC_TRX/" . $file) && filesize('KannelC/SMSC_TRX/' . $file)) {
            if ($conf->setConfigFromFile("KannelC/SMSC_TRX/" . $file)) {
                $flag = true;
            }
        }
        ?>
        <form action="<?php echo base_url(); ?>kannel_control/save_config/<?php echo $page . '/' . $id; ?>" method="post">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="sub-header"></h4>
                    <h4 class="sub-header">Transceiver Mode</h4>
                </div>
                <div class="col-md-3 padding0">
                    <div class="col-md-12 form-group">
                        <label>Group</label>
                        <input type="text" class="form-control" name="field[group]" value="smsc" readonly="" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>SMSC</label>
                        <input type="text" class="form-control" name="field[smsc]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('smsc')) ? $conf->get('smsc') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>SMSC ID</label>
                        <input type="text" class="form-control" name="field[smsc-id]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('smsc-id')) ? $conf->get('smsc-id') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Log File</label>
                        <input type="text" class="form-control" name="field[log-file]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('log-file')) ? $conf->get('log-file') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Log Level</label>
                        <input type="text" class="form-control" name="field[log-level]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('log-level')) ? $conf->get('log-level') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Host</label>
                        <input type="text" class="form-control" name="field[host]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('host')) ? $conf->get('host') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Port</label>
                        <input type="text" class="form-control" name="field[port]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('port')) ? $conf->get('port') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Transceiver Mode</label>
                        <input type="text" class="form-control" name="field[transceiver-mode]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('transceiver-mode')) ? $conf->get('transceiver-mode') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <input type="hidden" name="group_type" value="smsc" />
                        <input type="hidden" name="smsc_type" value="<?php echo $id; ?>" />
                        <input type="hidden" name="file_name" value="<?php echo ($flag && $file) ? $file : "0"; ?>" />
                        <button type="submit" name="save" class="btn btn-primary">Save</button>
                    </div>
                </div>
                <div class="col-md-3 padding0">
                    <div class="col-md-12 form-group">
                        <label>SMSC Username</label>
                        <input type="text" class="form-control" name="field[smsc-username]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('smsc-username')) ? $conf->get('smsc-username') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>SMSC Password</label>
                        <input type="text" class="form-control" name="field[smsc-password]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('smsc-password')) ? $conf->get('smsc-password') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Denied SMSC Id</label>
                        <input type="text" class="form-control" name="field[denied-smsc-id]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('denied-smsc-id')) ? $conf->get('denied-smsc-id') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Allowed SMSC Id</label>
                        <input type="text" class="form-control" name="field[allowed-smsc-id]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('allowed-smsc-id')) ? $conf->get('allowed-smsc-id') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Throughput</label>
                        <input type="text" class="form-control" name="field[throughput]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('throughput')) ? $conf->get('throughput') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Service Type</label>
                        <input type="text" class="form-control" name="field[service-type]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('service-type')) ? $conf->get('service-type') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>System Type</label>
                        <input type="text" class="form-control" name="field[system-type]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('system-type')) ? $conf->get('system-type') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Interface Version</label>
                        <input type="text" class="form-control" name="field[interface-version]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('interface-version')) ? $conf->get('interface-version') : ''; ?>" />
                    </div>
                </div>
                <div class="col-md-3 padding0">
                    <div class="col-md-12 form-group">
                        <label>Source Address TON</label>
                        <input type="text" class="form-control" name="field[source-addr-ton]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('source-addr-ton')) ? $conf->get('source-addr-ton') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Source Address NPI</label>
                        <input type="text" class="form-control" name="field[source-addr-npi]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('source-addr-npi')) ? $conf->get('source-addr-npi') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Destination Address TON</label>
                        <input type="text" class="form-control" name="field[dest-addr-ton]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('dest-addr-ton')) ? $conf->get('dest-addr-ton') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Destination Address NPI</label>
                        <input type="text" class="form-control" name="field[dest-addr-npi]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('dest-addr-npi')) ? $conf->get('dest-addr-npi') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Alt Charset</label>
                        <input type="text" class="form-control" name="field[alt-charset]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('alt-charset')) ? $conf->get('alt-charset') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Alt Addr Charset</label>
                        <input type="text" class="form-control" name="field[alt-addr-charset]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('alt-addr-charset')) ? $conf->get('alt-addr-charset') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Enquire Link Interval</label>
                        <input type="text" class="form-control" name="field[enquire-link-interval]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('enquire-link-interval')) ? $conf->get('enquire-link-interval') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Reconnect Delay</label>
                        <input type="text" class="form-control" name="field[reconnect-delay]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('reconnect-delay')) ? $conf->get('reconnect-delay') : '0'; ?>" />
                    </div>
                </div>
                <div class="col-md-3 padding0">
                    <div class="col-md-12 form-group">
                        <label>Bind Address TON</label>
                        <input type="text" class="form-control" name="field[bind-addr-ton]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('bind-addr-ton')) ? $conf->get('bind-addr-ton') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Bind Address NPI</label>
                        <input type="text" class="form-control" name="field[bind-addr-npi]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('bind-addr-npi')) ? $conf->get('bind-addr-npi') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Connection Timeout</label>
                        <input type="text" class="form-control" name="field[connection-timeout]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('connection-timeout')) ? $conf->get('connection-timeout') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Reroute DLR</label>
                        <input type="text" class="form-control" name="field[reroute-dlr]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('reroute-dlr')) ? $conf->get('reroute-dlr') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Wait Ack</label>
                        <input type="text" class="form-control" name="field[wait-ack]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('wait-ack')) ? $conf->get('wait-ack') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Wait Ack Expire</label>
                        <input type="text" class="form-control" name="field[wait-ack-expire]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('wait-ack-expire')) ? $conf->get('wait-ack-expire') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Validity Period</label>
                        <input type="text" class="form-control" name="field[validityperiod]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TRX/" . $file) && $flag && $conf->get('validityperiod')) ? $conf->get('validityperiod') : '0'; ?>" />
                    </div>
                </div>
            </div>
        </form>
        <?php
    }

    // Transmitter Mode
    if ($id == 'smsc_tx') {
        $conf = new ConfigTool();
        // Get File
        $flag = false;
        if (file_exists("KannelC/SMSC_TX/" . $file) && filesize('KannelC/SMSC_TX/' . $file)) {
            if ($conf->setConfigFromFile("KannelC/SMSC_TX/" . $file)) {
                $flag = true;
            }
        }
        ?>
        <form action="<?php echo base_url(); ?>kannel_control/save_config/<?php echo $page . '/' . $id; ?>" method="post">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="sub-header"></h4>
                    <h4 class="sub-header">Transmitter Mode</h4>
                </div>
                <div class="col-md-3 padding0">
                    <div class="col-md-12 form-group">
                        <label>Group</label>
                        <input type="text" class="form-control" name="field[group]" value="smsc" readonly="" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>SMSC</label>
                        <input type="text" class="form-control" name="field[smsc]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('smsc')) ? $conf->get('smsc') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>SMSC ID</label>
                        <input type="text" class="form-control" name="field[smsc-id]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('smsc-id')) ? $conf->get('smsc-id') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Log File</label>
                        <input type="text" class="form-control" name="field[log-file]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('log-file')) ? $conf->get('log-file') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Log Level</label>
                        <input type="text" class="form-control" name="field[log-level]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('log-level')) ? $conf->get('log-level') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Host</label>
                        <input type="text" class="form-control" name="field[host]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('host')) ? $conf->get('host') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Port</label>
                        <input type="text" class="form-control" name="field[port]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('port')) ? $conf->get('port') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Transceiver Mode</label>
                        <input type="text" class="form-control" name="field[transceiver-mode]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('transceiver-mode')) ? $conf->get('transceiver-mode') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <input type="hidden" name="group_type" value="smsc" />
                        <input type="hidden" name="smsc_type" value="<?php echo $id; ?>" />
                        <input type="hidden" name="file_name" value="<?php echo ($flag && $file) ? $file : "0"; ?>" />
                        <button type="submit" name="save" class="btn btn-primary">Save</button>
                    </div>
                </div>
                <div class="col-md-3 padding0">
                    <div class="col-md-12 form-group">
                        <label>SMSC Username</label>
                        <input type="text" class="form-control" name="field[smsc-username]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('smsc-username')) ? $conf->get('smsc-username') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>SMSC Password</label>
                        <input type="text" class="form-control" name="field[smsc-password]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('smsc-password')) ? $conf->get('smsc-password') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Denied SMSC Id</label>
                        <input type="text" class="form-control" name="field[denied-smsc-id]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('denied-smsc-id')) ? $conf->get('denied-smsc-id') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Allowed SMSC Id</label>
                        <input type="text" class="form-control" name="field[allowed-smsc-id]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('allowed-smsc-id')) ? $conf->get('allowed-smsc-id') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Throughput</label>
                        <input type="text" class="form-control" name="field[throughput]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('throughput')) ? $conf->get('throughput') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Service Type</label>
                        <input type="text" class="form-control" name="field[service-type]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('service-type')) ? $conf->get('service-type') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>System Type</label>
                        <input type="text" class="form-control" name="field[system-type]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('system-type')) ? $conf->get('system-type') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Interface Version</label>
                        <input type="text" class="form-control" name="field[interface-version]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('interface-version')) ? $conf->get('interface-version') : ''; ?>" />
                    </div>
                </div>
                <div class="col-md-3 padding0">
                    <div class="col-md-12 form-group">
                        <label>Source Address TON</label>
                        <input type="text" class="form-control" name="field[source-addr-ton]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('source-addr-ton')) ? $conf->get('source-addr-ton') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Source Address NPI</label>
                        <input type="text" class="form-control" name="field[source-addr-npi]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('source-addr-npi')) ? $conf->get('source-addr-npi') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Destination Address TON</label>
                        <input type="text" class="form-control" name="field[dest-addr-ton]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('dest-addr-ton')) ? $conf->get('dest-addr-ton') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Destination Address NPI</label>
                        <input type="text" class="form-control" name="field[dest-addr-npi]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('dest-addr-npi')) ? $conf->get('dest-addr-npi') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Alt Charset</label>
                        <input type="text" class="form-control" name="field[alt-charset]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('alt-charset')) ? $conf->get('alt-charset') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Alt Addr Charset</label>
                        <input type="text" class="form-control" name="field[alt-addr-charset]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('alt-addr-charset')) ? $conf->get('alt-addr-charset') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Enquire Link Interval</label>
                        <input type="text" class="form-control" name="field[enquire-link-interval]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('enquire-link-interval')) ? $conf->get('enquire-link-interval') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Reconnect Delay</label>
                        <input type="text" class="form-control" name="field[reconnect-delay]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('reconnect-delay')) ? $conf->get('reconnect-delay') : '0'; ?>" />
                    </div>
                </div>
                <div class="col-md-3 padding0">
                    <div class="col-md-12 form-group">
                        <label>Bind Address TON</label>
                        <input type="text" class="form-control" name="field[bind-addr-ton]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('bind-addr-ton')) ? $conf->get('bind-addr-ton') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Bind Address NPI</label>
                        <input type="text" class="form-control" name="field[bind-addr-npi]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('bind-addr-npi')) ? $conf->get('bind-addr-npi') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Connection Timeout</label>
                        <input type="text" class="form-control" name="field[connection-timeout]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('connection-timeout')) ? $conf->get('connection-timeout') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Reroute DLR</label>
                        <input type="text" class="form-control" name="field[reroute-dlr]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('reroute-dlr')) ? $conf->get('reroute-dlr') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Wait Ack</label>
                        <input type="text" class="form-control" name="field[wait-ack]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('wait-ack')) ? $conf->get('wait-ack') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Wait Ack Expire</label>
                        <input type="text" class="form-control" name="field[wait-ack-expire]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('wait-ack-expire')) ? $conf->get('wait-ack-expire') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Validity Period</label>
                        <input type="text" class="form-control" name="field[validityperiod]" 
                               value="<?php echo (file_exists("KannelC/SMSC_TX/" . $file) && $flag && $conf->get('validityperiod')) ? $conf->get('validityperiod') : '0'; ?>" />
                    </div>
                </div>
            </div>
        </form>
        <?php
    }

    // Receiver Mode
    if ($id == 'smsc_rx') {
        $conf = new ConfigTool();
        // Get File
        $flag = false;
        if (file_exists("KannelC/SMSC_RX/" . $file) && filesize('KannelC/SMSC_RX/' . $file)) {
            if ($conf->setConfigFromFile("KannelC/SMSC_RX/" . $file)) {
                $flag = true;
            }
        }
        ?>
        <form action="<?php echo base_url(); ?>kannel_control/save_config/<?php echo $page . '/' . $id; ?>" method="post">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="sub-header"></h4>
                    <h4 class="sub-header">Receiver Mode</h4>
                </div>
                <div class="col-md-3 padding0">
                    <div class="col-md-12 form-group">
                        <label>Group</label>
                        <input type="text" class="form-control" name="field[group]" value="smsc" readonly="" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>SMSC</label>
                        <input type="text" class="form-control" name="field[smsc]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('smsc')) ? $conf->get('smsc') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>SMSC ID</label>
                        <input type="text" class="form-control" name="field[smsc-id]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('smsc-id')) ? $conf->get('smsc-id') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Log File</label>
                        <input type="text" class="form-control" name="field[log-file]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('log-file')) ? $conf->get('log-file') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Log Level</label>
                        <input type="text" class="form-control" name="field[log-level]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('log-level')) ? $conf->get('log-level') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Host</label>
                        <input type="text" class="form-control" name="field[host]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('host')) ? $conf->get('host') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Receive Port</label>
                        <input type="text" class="form-control" name="field[receive-port]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('receive-port')) ? $conf->get('receive-port') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Transceiver Mode</label>
                        <input type="text" class="form-control" name="field[transceiver-mode]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('transceiver-mode')) ? $conf->get('transceiver-mode') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <input type="hidden" name="group_type" value="smsc" />
                        <input type="hidden" name="smsc_type" value="<?php echo $id; ?>" />
                        <input type="hidden" name="file_name" value="<?php echo ($flag && $file) ? $file : "0"; ?>" />
                        <button type="submit" name="save" class="btn btn-primary">Save</button>
                    </div>
                </div>
                <div class="col-md-3 padding0">

                    <div class="col-md-12 form-group">
                        <label>SMSC Username</label>
                        <input type="text" class="form-control" name="field[smsc-username]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('smsc-username')) ? $conf->get('smsc-username') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>SMSC Password</label>
                        <input type="text" class="form-control" name="field[smsc-password]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('smsc-password')) ? $conf->get('smsc-password') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Denied SMSC Id</label>
                        <input type="text" class="form-control" name="field[denied-smsc-id]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('denied-smsc-id')) ? $conf->get('denied-smsc-id') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Allowed SMSC Id</label>
                        <input type="text" class="form-control" name="field[allowed-smsc-id]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('allowed-smsc-id')) ? $conf->get('allowed-smsc-id') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Throughput</label>
                        <input type="text" class="form-control" name="field[throughput]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('throughput')) ? $conf->get('throughput') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Service Type</label>
                        <input type="text" class="form-control" name="field[service-type]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('service-type')) ? $conf->get('service-type') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>System Type</label>
                        <input type="text" class="form-control" name="field[system-type]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('system-type')) ? $conf->get('system-type') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Interface Version</label>
                        <input type="text" class="form-control" name="field[interface-version]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('interface-version')) ? $conf->get('interface-version') : ''; ?>" />
                    </div>
                </div>
                <div class="col-md-3 padding0">
                    <div class="col-md-12 form-group">
                        <label>Source Address TON</label>
                        <input type="text" class="form-control" name="field[source-addr-ton]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('source-addr-ton')) ? $conf->get('source-addr-ton') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Source Address NPI</label>
                        <input type="text" class="form-control" name="field[source-addr-npi]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('source-addr-npi')) ? $conf->get('source-addr-npi') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Destination Address TON</label>
                        <input type="text" class="form-control" name="field[dest-addr-ton]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('dest-addr-ton')) ? $conf->get('dest-addr-ton') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Destination Address NPI</label>
                        <input type="text" class="form-control" name="field[dest-addr-npi]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('dest-addr-npi')) ? $conf->get('dest-addr-npi') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Alt Charset</label>
                        <input type="text" class="form-control" name="field[alt-charset]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('alt-charset')) ? $conf->get('alt-charset') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Alt Addr Charset</label>
                        <input type="text" class="form-control" name="field[alt-addr-charset]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('alt-addr-charset')) ? $conf->get('alt-addr-charset') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Enquire Link Interval</label>
                        <input type="text" class="form-control" name="field[enquire-link-interval]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('enquire-link-interval')) ? $conf->get('enquire-link-interval') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Reconnect Delay</label>
                        <input type="text" class="form-control" name="field[reconnect-delay]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('reconnect-delay')) ? $conf->get('reconnect-delay') : '0'; ?>" />
                    </div>
                </div>
                <div class="col-md-3 padding0">
                    <div class="col-md-12 form-group">
                        <label>Bind Address TON</label>
                        <input type="text" class="form-control" name="field[bind-addr-ton]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('bind-addr-ton')) ? $conf->get('bind-addr-ton') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Bind Address NPI</label>
                        <input type="text" class="form-control" name="field[bind-addr-npi]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('bind-addr-npi')) ? $conf->get('bind-addr-npi') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Connection Timeout</label>
                        <input type="text" class="form-control" name="field[connection-timeout]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('connection-timeout')) ? $conf->get('connection-timeout') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Reroute DLR</label>
                        <input type="text" class="form-control" name="field[reroute-dlr]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('reroute-dlr')) ? $conf->get('reroute-dlr') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Wait Ack</label>
                        <input type="text" class="form-control" name="field[wait-ack]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('wait-ack')) ? $conf->get('wait-ack') : '0'; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Wait Ack Expire</label>
                        <input type="text" class="form-control" name="field[wait-ack-expire]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('wait-ack-expire')) ? $conf->get('wait-ack-expire') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Validity Period</label>
                        <input type="text" class="form-control" name="field[validityperiod]" 
                               value="<?php echo (file_exists("KannelC/SMSC_RX/" . $file) && $flag && $conf->get('validityperiod')) ? $conf->get('validityperiod') : '0'; ?>" />
                    </div>
                </div>
            </div>
        </form>
        <?php
    }
}

// Fake SMSC Group
if ($page == 'fake_smsc') {
    $conf = new ConfigTool();
    // Get File
    $flag = false;
    if (file_exists("KannelC/Fake_SMSC/" . $file) && filesize('KannelC/Fake_SMSC/' . $file)) {
        if ($conf->setConfigFromFile("KannelC/Fake_SMSC/" . $file)) {
            $flag = true;
        }
    }
    ?>
    <form action="<?php echo base_url(); ?>kannel_control/save_config/<?php echo $page; ?>" method="post">
        <div class="row">
            <div class="col-md-3 padding0">
                <div class="col-md-12 form-group">
                    <label>Group</label>
                    <input type="text" class="form-control" name="field[group]" value="smsc" readonly="" />
                </div>
                <div class="col-md-12 form-group">
                    <label>SMSC</label>
                    <input type="text" class="form-control" name="field[smsc]" 
                           value="<?php echo (file_exists("KannelC/Fake_SMSC/" . $file) && $flag && $conf->get('smsc')) ? $conf->get('smsc') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>SMSC ID</label>
                    <input type="text" class="form-control" name="field[smsc-id]" 
                           value="<?php echo (file_exists("KannelC/Fake_SMSC/" . $file) && $flag && $conf->get('smsc-id')) ? $conf->get('smsc-id') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Host</label>
                    <input type="text" class="form-control" name="field[host]"
                           value="<?php echo (file_exists("KannelC/Fake_SMSC/" . $file) && $flag && $conf->get('host')) ? $conf->get('host') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Port</label>
                    <input type="text" class="form-control" name="field[port]"
                           value="<?php echo (file_exists("KannelC/Fake_SMSC/" . $file) && $flag && $conf->get('port')) ? $conf->get('port') : '0'; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Connect Allow IP</label>
                    <input type="text" class="form-control" name="field[connect-allow-ip]" 
                           value="<?php echo (file_exists("KannelC/Fake_SMSC/" . $file) && $flag && $conf->get('connect-allow-ip')) ? $conf->get('connect-allow-ip') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Throughput</label>
                    <input type="text" class="form-control" name="field[throughput]" 
                           value="<?php echo (file_exists("KannelC/Fake_SMSC/" . $file) && $flag && $conf->get('throughput')) ? $conf->get('throughput') : '0'; ?>" />
                </div>
            </div>
            <div class="col-md-3 padding0">
                <div class="col-md-12 form-group">
                    <label>Log File</label>
                    <input type="text" class="form-control" name="field[log-file]"
                           value="<?php echo (file_exists("KannelC/Fake_SMSC/" . $file) && $flag && $conf->get('log-file')) ? $conf->get('log-file') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Log Level</label>
                    <input type="text" class="form-control" name="field[log-level]" 
                           value="<?php echo (file_exists("KannelC/Fake_SMSC/" . $file) && $flag && $conf->get('log-level')) ? $conf->get('log-level') : '0'; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Denied SMSC Id</label>
                    <input type="text" class="form-control" name="field[denied-smsc-id]" 
                           value="<?php echo (file_exists("KannelC/Fake_SMSC/" . $file) && $flag && $conf->get('denied-smsc-id')) ? $conf->get('denied-smsc-id') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Allowed SMSC Id</label>
                    <input type="text" class="form-control" name="field[allowed-smsc-id]"
                           value="<?php echo (file_exists("KannelC/Fake_SMSC/" . $file) && $flag && $conf->get('allowed-smsc-id')) ? $conf->get('allowed-smsc-id') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Preferred SMSC Id</label>
                    <input type="text" class="form-control" name="field[preferred-smsc-id]" 
                           value="<?php echo (file_exists("KannelC/Fake_SMSC/" . $file) && $flag && $conf->get('preferred-smsc-id')) ? $conf->get('preferred-smsc-id') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Reroute DLR</label>
                    <input type="text" class="form-control" name="field[reroute-dlr]" 
                           value="<?php echo (file_exists("KannelC/Fake_SMSC/" . $file) && $flag && $conf->get('reroute-dlr')) ? $conf->get('reroute-dlr') : ''; ?>" />
                </div>
            </div>
            <div class="col-md-12 padding0">
                <div class="col-md-12 form-group">
                    <input type="hidden" name="group_type" value="fake_smsc" />
                    <input type="hidden" name="file_name" value="<?php echo ($flag && $file) ? $file : "0"; ?>" />
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
    <?php
}

// SMSBox Route Group
if ($page == 'smsbox_route') {
    $conf = new ConfigTool();
    // Get File
    $flag = false;
    if (file_exists("KannelC/SMSBox_Route/" . $file) && filesize('KannelC/SMSBox_Route/' . $file)) {
        if ($conf->setConfigFromFile("KannelC/SMSBox_Route/" . $file)) {
            $flag = true;
        }
    }
    ?>
    <form action="<?php echo base_url(); ?>kannel_control/save_config/<?php echo $page; ?>" method="post">
        <div class="row">
            <div class="col-md-3 padding0">
                <div class="col-md-12 form-group">
                    <label>Group</label>
                    <input type="text" class="form-control" name="field[group]" value="smsbox-route" readonly="" />
                </div>
                <div class="col-md-12 form-group">
                    <label>SMSBox Id</label>
                    <input type="text" class="form-control" name="field[smsbox-id]" 
                           value="<?php echo ($file && file_exists("KannelC/SMSBox_Route/" . $file) && $flag && $conf->get('smsbox-id')) ? $conf->get('smsbox-id') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>SMSC Id</label>
                    <input type="text" class="form-control" name="field[smsc-id]"
                           value="<?php echo ($file && file_exists("KannelC/SMSBox_Route/" . $file) && $flag && $conf->get('smsc-id')) ? $conf->get('smsc-id') : ''; ?>" />
                </div>
            </div>
            <div class="col-md-12 padding0">
                <div class="col-md-12 form-group">
                    <input type="hidden" name="group_type" value="smsbox_route" />
                    <input type="hidden" name="file_name" value="<?php echo ($flag && $file) ? $file : "0"; ?>" />
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
    <?php
}

// SendSMS User Group
if ($page == 'sendsms_user') {
    $conf = new ConfigTool();
    // Get File
    $flag = false;
    if (file_exists("KannelC/SendSMS_User/" . $file) && filesize('KannelC/SendSMS_User/' . $file)) {
        if ($conf->setConfigFromFile("KannelC/SendSMS_User/" . $file)) {
            $flag = true;
        }
    }
    ?>
    <form action="<?php echo base_url(); ?>kannel_control/save_config/<?php echo $page; ?>" method="post">
        <div class="row">
            <div class="col-md-3 padding0">
                <div class="col-md-12 form-group">
                    <label>Group</label>
                    <input type="text" class="form-control" name="field[group]" value="sendsms-user" readonly="" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="field[name]" 
                           value="<?php echo ($file && file_exists("KannelC/SendSMS_User/" . $file) && $flag && $conf->get('name')) ? $conf->get('name') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" name="field[username]"
                           value="<?php echo ($file && file_exists("KannelC/SendSMS_User/" . $file) && $flag && $conf->get('username')) ? $conf->get('username') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Password</label>
                    <input type="text" class="form-control" name="field[password]"
                           value="<?php echo ($file && file_exists("KannelC/SendSMS_User/" . $file) && $flag && $conf->get('password')) ? $conf->get('password') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Forced SMSC</label>
                    <input type="text" class="form-control" name="field[forced-smsc]"
                           value="<?php echo ($file && file_exists("KannelC/SendSMS_User/" . $file) && $flag && $conf->get('forced-smsc')) ? $conf->get('forced-smsc') : ''; ?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label>Max Messages</label>
                    <input type="text" class="form-control" name="field[max-messages]" 
                           value="<?php echo ($file && file_exists("KannelC/SendSMS_User/" . $file) && $flag && $conf->get('max-messages')) ? $conf->get('max-messages') : '0'; ?>" />
                </div>
            </div>
            <div class="col-md-12 padding0">
                <div class="col-md-12 form-group">
                    <input type="hidden" name="group_type" value="sendsms_user" />
                    <input type="hidden" name="file_name" value="<?php echo ($flag && $file) ? $file : "0"; ?>" />
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
    <?php
}

// SQLBox Group
if ($page == 'sqlbox') {
    $conf = new ConfigTool();
    $conf1 = new ConfigTool();
    // Get File
    $flag = false;
    $file1 = 0;
    if (file_exists("KannelC/SQLBox/" . $file) && filesize('KannelC/SQLBox/' . $file)) {
        if ($conf->setConfigFromFile("KannelC/SQLBox/" . $file)) {
            $flag = true;
        }
    }
    if (file_exists("KannelC/SQLBox/" . $file) && filesize('KannelC/SQLBox/' . $file)) {
        $file1 = $file;
        if ($conf1->setConfigFromFile("KannelC/SQLBox/" . $file)) {
            $flag = true;
        }
    }
    ?>
    <div class="row">
        <!-- SQLBox -->
        <div class="col-md-6">
            <h4 class="sub-header">SQLBox Group</h4>
            <form action="<?php echo base_url(); ?>kannel_control/save_config/<?php echo $page; ?>" method="post">
                <div class="col-md-6 padding0">
                    <div class="col-md-12 form-group">
                        <label>Group</label>
                        <input type="text" class="form-control" name="field[group]" value="sqlbox" readonly="" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>ID</label>
                        <input type="text" class="form-control" name="field[id]" 
                               value="<?php echo ($file && file_exists("KannelC/SQLBox/" . $file) && $conf->get('id')) ? $conf->get('id') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>SMSBox Id</label>
                        <input type="text" class="form-control" name="field[smsbox-id]" 
                               value="<?php echo ($file && file_exists("KannelC/SQLBox/" . $file) && $conf->get('smsbox-id')) ? $conf->get('smsbox-id') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>BearerBox Host</label>
                        <input type="text" class="form-control" name="field[bearerbox-host]" 
                               value="<?php echo ($file && file_exists("KannelC/SQLBox/" . $file) && $conf->get('bearerbox-host')) ? $conf->get('bearerbox-host') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>BearerBox Port</label>
                        <input type="text" class="form-control" name="field[bearerbox-port]" 
                               value="<?php echo ($file && file_exists("KannelC/SQLBox/" . $file) && $conf->get('bearerbox-port')) ? $conf->get('bearerbox-port') : ''; ?>" />
                    </div>
                </div>
                <div class="col-md-6 padding0">
                    <div class="col-md-12 form-group">
                        <label>SMSBox Port</label>
                        <input type="text" class="form-control" name="field[smsbox-port]" 
                               value="<?php echo ($file && file_exists("KannelC/SQLBox/" . $file) && $conf->get('smsbox-port')) ? $conf->get('smsbox-port') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>SQL Log Table</label>
                        <input type="text" class="form-control" name="field[sql-log-table]" 
                               value="<?php echo ($file && file_exists("KannelC/SQLBox/" . $file) && $conf->get('sql-log-table')) ? $conf->get('sql-log-table') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>SQL Insert Table</label>
                        <input type="text" class="form-control" name="field[sql-insert-table]" 
                               value="<?php echo ($file && file_exists("KannelC/SQLBox/" . $file) && $conf->get('sql-insert-table')) ? $conf->get('sql-insert-table') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Log File</label>
                        <input type="text" class="form-control" name="field[log-file]" 
                               value="<?php echo ($file && file_exists("KannelC/SQLBox/" . $file) && $conf->get('log-file')) ? $conf->get('log-file') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Log Level</label>
                        <input type="text" class="form-control" name="field[log-level]"  
                               value="<?php echo ($file && file_exists("KannelC/SQLBox/" . $file) && $conf->get('log-level')) ? $conf->get('log-level') : '0'; ?>" />
                    </div>                
                </div>
                <div class="col-md-12 padding0">
                    <div class="col-md-12 form-group">
                        <input type="hidden" name="group_type" value="sqlbox" />
                        <input type="hidden" name="file_name" value="<?php echo ($flag && $file) ? $file : "0"; ?>" />
                        <button type="submit" name="save" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- MySQL Connection -->   
        <div class="col-md-6">
            <form action="<?php echo base_url(); ?>kannel_control/save_config/<?php echo $page; ?>" method="post">
                <h4 class="sub-header">MYSQL Connection</h4>
                <div class="col-md-6 padding0">
                    <div class="col-md-12 form-group">
                        <label>Group</label>
                        <input type="text" class="form-control" name="field[group]" value="mysql-connection" readonly="" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>ID</label>
                        <input type="text" class="form-control" name="field[id]" 
                               value="<?php echo ($file1 && file_exists("KannelC/SQLBox/" . $file1) && $conf1->get('id')) ? $conf1->get('id') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Host</label>
                        <input type="text" class="form-control" name="field[host]" 
                               value="<?php echo ($file1 && file_exists("KannelC/SQLBox/" . $file1) && $conf1->get('host')) ? $conf1->get('host') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Port</label>
                        <input type="text" class="form-control" name="field[port]" 
                               value="<?php echo ($file1 && file_exists("KannelC/SQLBox/" . $file1) && $conf1->get('port')) ? $conf1->get('port') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" name="field[username]" 
                               value="<?php echo ($file1 && file_exists("KannelC/SQLBox/" . $file1) && $conf1->get('username')) ? $conf1->get('username') : ''; ?>" />
                    </div>
                </div>
                <div class="col-md-6 padding0">
                    <div class="col-md-12 form-group">
                        <label>Password</label>
                        <input type="text" class="form-control" name="field[password]" 
                               value="<?php echo ($file1 && file_exists("KannelC/SQLBox/" . $file1) && $conf1->get('password')) ? $conf1->get('password') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Database</label>
                        <input type="text" class="form-control" name="field[database]" 
                               value="<?php echo ($file1 && file_exists("KannelC/SQLBox/" . $file1) && $conf1->get('database')) ? $conf1->get('database') : ''; ?>" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Max Connections</label>
                        <input type="text" class="form-control" name="field[max-connections]" 
                               value="<?php echo ($file1 && file_exists("KannelC/SQLBox/" . $file1) && $conf1->get('max-connections')) ? $conf1->get('max-connections') : '0'; ?>" />
                    </div>
                </div>
                <div class="col-md-12 padding0">
                    <div class="col-md-12 form-group">
                        <input type="hidden" name="group_type" value="sql_connection" />
                        <input type="hidden" name="file_name" value="<?php echo ($flag && $file1) ? $file1 : "0"; ?>" />
                        <button type="submit" name="save" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
}
?>