<div class="row">
    <div class="col-md-4 col-sm-12 col-xs-12 padding0">
        <?php
        // Main Configuration Files
        if ($page == 'main_config') {
            ?>
            <form action="<?php echo base_url(); ?>kannel_control/generate_config/main" method="post">
                <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                    <h4>Main Configuration Files</h4>
                    <table class="table">
                        <tbody>
                            <?php
                            // Get File Names From Config Directory
                            $config_files = glob("KannelC/Config/*.*", 0);
                            // Check Size
                            if (isset($config_files) && sizeof($config_files)) {
                                // Remove Element
                                if (in_array('KannelC/Config/gw.conf', $config_files)) {
                                    unset($config_files[array_search('KannelC/Config/gw.conf', $config_files)]);
                                }
                                if (in_array('KannelC/Config/sqlbox.conf', $config_files)) {
                                    unset($config_files[array_search('KannelC/Config/sqlbox.conf', $config_files)]);
                                }
                                $i = 1;
                                foreach ($config_files as $key => $value) {
                                    $remain = str_replace("KannelC/Config/", "", $value);
                                    ?>
                                    <tr>
                                        <td class="col-md-2">
                                            <label for="file<?php echo $i; ?>" class="fancy-check">
                                                <input type="checkbox" name="files[]" id="file<?php echo $i; ?>" value="<?php echo $remain; ?>" />
                                                <span></span>
                                            </label>
                                        </td>
                                        <td class="col-md-10">
                                            <a href="#"><?php echo $remain; ?></a>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <button type="submit" name="generate" class="btn btn-primary">Generate Config</button>
                </div>
            </form>
            <?php
        }

        // SQLBox Configuration Files
        if ($page == 'sqlbox_config') {
            ?>
            <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                <h4>SQLBox Configuration Files</h4>
                <table class="table">
                    <tbody>
                        <?php
                        // Get File Names From SQLBox Directory
                        $sqlbox_files = glob("KannelC/SQLBox/*.*", 0);
                        // Check Size
                        if (isset($sqlbox_files) && sizeof($sqlbox_files)) {
                            $sqlbox_files = array_reverse($sqlbox_files);
                            foreach ($sqlbox_files as $key => $value) {
                                $remain = str_replace("KannelC/SQLBox/", "", $value);
                                ?>
                                <tr>
                                    <td class="col-md-12">
                                        <a href="#" data-toggle="modal" data-target="#sqlbox<?php echo $key; ?>"><?php echo $remain; ?></a>
                                        <!-- Modal -->
                                        <div class="modal fade" id="sqlbox<?php echo $key; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Configuration <?php echo $remain; ?></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row" style="height: 350px;overflow-y: auto;">
                                                            <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                                                                <table class="table table-bordered">
                                                                    <tbody>
                                                                        <?php
                                                                        $conf = new ConfigTool();
                                                                        if (file_exists("KannelC/SQLBox/" . $remain) && filesize('KannelC/SQLBox/' . $remain)) {
                                                                            if ($conf->setConfigFromFile("KannelC/SQLBox/" . $remain)) {
                                                                                if ($conf && sizeof($conf)) {
                                                                                    foreach ($conf->LINES as $key => $value) {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td><?php echo $value; ?></td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>                    
                </table>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <a href="<?php echo base_url(); ?>kannel_control/generate_config/sqlbox" class="btn btn-primary">Generate Config</a>
            </div>
            <?php
        }

        // Real SMSC (TRX, TX, RX) Configuration Files
        if ($page == 'smsc_config') {
            ?>
            <form action="<?php echo base_url(); ?>kannel_control/generate_config/smsc" method="post">
                <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                    <h4>Real SMSC Configuration Files</h4>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="col-md-12" colspan="2">
                                    SMSC Connection (TRX Mode)
                                </th>
                            </tr>
                            <?php
                            // Get File Names From SMSC TRX Directory
                            $smsc_files = glob("KannelC/SMSC_TRX/*.*", 0);
                            // Check Size
                            $flag1 = 0;
                            $i = 1;
                            if (isset($smsc_files) && sizeof($smsc_files)) {
                                $flag1 = 1;
                                foreach ($smsc_files as $key => $value) {
                                    $remain = str_replace("KannelC/SMSC_TRX/", "", $value);
                                    ?>
                                    <tr>
                                        <td class="col-md-2">
                                            <label for="file<?php echo $i; ?>" class="fancy-check">
                                                <input type="checkbox" name="files[]" id="file<?php echo $i; ?>" value="<?php echo $remain; ?>" />
                                                <span></span>
                                            </label>
                                        </td>
                                        <td class="col-md-10">
                                            <a href="#" data-toggle="modal" data-target="#smsc_trx<?php echo $key; ?>"><?php echo $remain; ?></a>
                                            <!-- Modal -->
                                            <div class="modal fade" id="smsc_trx<?php echo $key; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Configuration <?php echo $remain; ?></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row" style="height: 350px;overflow-y: auto;">
                                                                <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <tbody>
                                                                            <?php
                                                                            $conf = new ConfigTool();
                                                                            if (file_exists("KannelC/SMSC_TRX/" . $remain) && filesize('KannelC/SMSC_TRX/' . $remain)) {
                                                                                if ($conf->setConfigFromFile("KannelC/SMSC_TRX/" . $remain)) {
                                                                                    if ($conf && sizeof($conf)) {
                                                                                        foreach ($conf->LINES as $key => $value) {
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td><?php echo $value; ?></td>
                                                                                            </tr>
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                            <tr>
                                <th class="col-md-12" colspan="2">
                                    SMSC Connection (TX Mode)
                                </th>
                            </tr>
                            <?php
                            // Get File Names From SMSC TX Directory
                            $smsc_files = glob("KannelC/SMSC_TX/*.*", 0);
                            // Check Size
                            $flag1 = 0;
                            if (isset($smsc_files) && sizeof($smsc_files)) {
                                $flag1 = 1;
                                foreach ($smsc_files as $key => $value) {
                                    $remain = str_replace("KannelC/SMSC_TX/", "", $value);
                                    ?>
                                    <tr>
                                        <td class="col-md-2">
                                            <label for="file<?php echo $i; ?>" class="fancy-check">
                                                <input type="checkbox" name="files[]" id="file<?php echo $i; ?>" value="<?php echo $remain; ?>" />
                                                <span></span>
                                            </label>
                                        </td>
                                        <td class="col-md-10">
                                            <a href="#" data-toggle="modal" data-target="#smsc_tx<?php echo $key; ?>"><?php echo $remain; ?></a>
                                            <!-- Modal -->
                                            <div class="modal fade" id="smsc_tx<?php echo $key; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Configuration <?php echo $remain; ?></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row" style="height: 350px;overflow-y: auto;">
                                                                <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <tbody>
                                                                            <?php
                                                                            $conf = new ConfigTool();
                                                                            if (file_exists("KannelC/SMSC_TX/" . $remain) && filesize('KannelC/SMSC_TX/' . $remain)) {
                                                                                if ($conf->setConfigFromFile("KannelC/SMSC_TX/" . $remain)) {
                                                                                    if ($conf && sizeof($conf)) {
                                                                                        foreach ($conf->LINES as $key => $value) {
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td><?php echo $value; ?></td>
                                                                                            </tr>
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                            <tr>
                                <th class="col-md-12" colspan="2">
                                    SMSC Connection (RX Mode)
                                </th>
                            </tr>
                            <?php
                            // Get File Names From SMSC RX Directory
                            $smsc_files = glob("KannelC/SMSC_RX/*.*", 0);
                            // Check Size
                            $flag1 = 0;
                            if (isset($smsc_files) && sizeof($smsc_files)) {
                                $flag1 = 1;
                                foreach ($smsc_files as $key => $value) {
                                    $remain = str_replace("KannelC/SMSC_RX/", "", $value);
                                    ?>
                                    <tr>
                                        <td class="col-md-2">
                                            <label for="file<?php echo $i; ?>" class="fancy-check">
                                                <input type="checkbox" name="files[]" id="file<?php echo $i; ?>" value="<?php echo $remain; ?>" />
                                                <span></span>
                                            </label>
                                        </td>
                                        <td class="col-md-10">
                                            <a href="#" data-toggle="modal" data-target="#smsc_rx<?php echo $key; ?>"><?php echo $remain; ?></a>
                                            <!-- Modal -->
                                            <div class="modal fade" id="smsc_rx<?php echo $key; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Configuration <?php echo $remain; ?></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row" style="height: 350px;overflow-y: auto;">
                                                                <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <tbody>
                                                                            <?php
                                                                            $conf = new ConfigTool();
                                                                            if (file_exists("KannelC/SMSC_RX/" . $remain) && filesize('KannelC/SMSC_RX/' . $remain)) {
                                                                                if ($conf->setConfigFromFile("KannelC/SMSC_RX/" . $remain)) {
                                                                                    if ($conf && sizeof($conf)) {
                                                                                        foreach ($conf->LINES as $key => $value) {
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td><?php echo $value; ?></td>
                                                                                            </tr>
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <button type="submit" name="generate" class="btn btn-primary">Generate Config</button>
                </div>
            </form>
            <?php
        }

        // Fake SMSC Configuration Files
        if ($page == 'fake_smsc_config') {
            ?>
            <form action="<?php echo base_url(); ?>kannel_control/generate_config/fake_smsc" method="post">
                <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                    <h4>Fake SMSC Configuration Files</h4>
                    <table class="table">
                        <tbody>
                            <?php
                            $flag2 = 0;
                            // Get File Names From SMSC Directory
                            $fake_smsc_files = glob("KannelC/Fake_SMSC/*.*", 0);
                            if (isset($fake_smsc_files) && sizeof($fake_smsc_files)) {
                                $flag2 = 1;
                                $i = 1;
                                foreach ($fake_smsc_files as $key => $value) {
                                    $remain = str_replace("KannelC/Fake_SMSC/", "", $value);
                                    ?>
                                    <tr>
                                        <td class="col-md-2">
                                            <label for="file<?php echo $i; ?>" class="fancy-check">
                                                <input type="checkbox" name="files[]" id="file<?php echo $i; ?>" value="<?php echo $remain; ?>" />
                                                <span></span>
                                            </label>
                                        </td>
                                        <td class="col-md-10">
                                            <a href="#" data-toggle="modal" data-target="#smsc_fake<?php echo $key; ?>"><?php echo $remain; ?></a>
                                            <!-- Modal -->
                                            <div class="modal fade" id="smsc_fake<?php echo $key; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Configuration <?php echo $remain; ?></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row" style="height: 350px;overflow-y: auto;">
                                                                <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <tbody>
                                                                            <?php
                                                                            $conf = new ConfigTool();
                                                                            if (file_exists("KannelC/Fake_SMSC/" . $remain) && filesize('KannelC/Fake_SMSC/' . $remain)) {
                                                                                if ($conf->setConfigFromFile("KannelC/Fake_SMSC/" . $remain)) {
                                                                                    if ($conf && sizeof($conf)) {
                                                                                        foreach ($conf->LINES as $key => $value) {
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td><?php echo $value; ?></td>
                                                                                            </tr>
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <button type="submit" name="generate" class="btn btn-primary">Generate Config</button>
                </div>
            </form>
            <?php
        }

        // SendSMS User Configuration Files
        if ($page == 'sendsms_user_config') {
            ?>
            <form action="<?php echo base_url(); ?>kannel_control/generate_config/sendsms_user" method="post">
                <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                    <h4>SendSMS User Configuration Files</h4>
                    <table class="table">
                        <tbody>
                            <?php
                            // Get File Names From SendSMS User Directory
                            $sendsms_user_files = glob("KannelC/SendSMS_User/*.*", 0);
                            // Check Size
                            if (isset($sendsms_user_files) && sizeof($sendsms_user_files)) {
                                $i = 1;
                                foreach ($sendsms_user_files as $key => $value) {
                                    $remain = str_replace("KannelC/SendSMS_User/", "", $value);
                                    ?>
                                    <tr>
                                        <td class="col-md-2">
                                            <label for="file<?php echo $i; ?>" class="fancy-check">
                                                <input type="checkbox" name="files[]" id="file<?php echo $i; ?>" value="<?php echo $remain; ?>" />
                                                <span></span>
                                            </label>
                                        </td>
                                        <td class="col-md-10">
                                            <a href="#" data-toggle="modal" data-target="#user<?php echo $key; ?>"><?php echo $remain; ?></a>
                                            <!-- Modal -->
                                            <div class="modal fade" id="user<?php echo $key; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Configuration <?php echo $remain; ?></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row" style="height: 245px;overflow-y: auto;">
                                                                <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <tbody>
                                                                            <?php
                                                                            $conf = new ConfigTool();
                                                                            if (file_exists("KannelC/SendSMS_User/" . $remain) && filesize('KannelC/SendSMS_User/' . $remain)) {
                                                                                if ($conf->setConfigFromFile("KannelC/SendSMS_User/" . $remain)) {
                                                                                    if ($conf && sizeof($conf)) {
                                                                                        foreach ($conf->LINES as $key => $value) {
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td><?php echo $value; ?></td>
                                                                                            </tr>
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <button type="submit" name="generate" class="btn btn-primary">Generate Config</button>
                </div>
            </form>
            <?php
        }

        // SMSBox Route Configuration Files
        if ($page == 'smsbox_route_config') {
            ?>
            <form action="<?php echo base_url(); ?>kannel_control/generate_config/smsbox_route" method="post">
                <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                    <h4>SMSBox Route Configuration Files</h4>
                    <table class="table">
                        <tbody>
                            <?php
                            // Get File Names From SMSBox Route Directory
                            $smsbox_route_files = glob("KannelC/SMSBox_Route/*.*", 0);
                            // Check Size
                            if (isset($smsbox_route_files) && sizeof($smsbox_route_files)) {
                                $i = 1;
                                foreach ($smsbox_route_files as $key => $value) {
                                    $remain = str_replace("KannelC/SMSBox_Route/", "", $value);
                                    ?>
                                    <tr>
                                        <td class="col-md-2">
                                            <label for="file<?php echo $i; ?>" class="fancy-check">
                                                <input type="checkbox" name="files[]" id="file<?php echo $i; ?>" value="<?php echo $remain; ?>" />
                                                <span></span>
                                            </label>
                                        </td>
                                        <td class="col-md-10">
                                            <a href="#" data-toggle="modal" data-target="#route<?php echo $key; ?>"><?php echo $remain; ?></a>
                                            <!-- Modal -->
                                            <div class="modal fade" id="route<?php echo $key; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Configuration <?php echo $remain; ?></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row" style="height: 200px;overflow-y: auto;">
                                                                <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <tbody>
                                                                            <?php
                                                                            $conf = new ConfigTool();
                                                                            if (file_exists("KannelC/SMSBox_Route/" . $remain) && filesize('KannelC/SMSBox_Route/' . $remain)) {
                                                                                if ($conf->setConfigFromFile("KannelC/SMSBox_Route/" . $remain)) {
                                                                                    if ($conf && sizeof($conf)) {
                                                                                        foreach ($conf->LINES as $key => $value) {
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td><?php echo $value; ?></td>
                                                                                            </tr>
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <button type="submit" name="generate" class="btn btn-primary">Generate Config</button>
                </div>
            </form>
            <?php
        }
        ?>
    </div>
</div>