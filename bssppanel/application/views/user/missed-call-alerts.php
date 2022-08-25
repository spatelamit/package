<div id="nav_sub">
    <div class="row">
        <ul class="nav nav-pills">
            <li class="<?php echo (isset($tab) && $tab == 'dashboard') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/missed_call_alerts/dashboard">Dashboard</a>
            </li>
            <li class="<?php echo (isset($tab) && $tab == 'inbox') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/missed_call_alerts/inbox">Inbox</a>
            </li>
            <li class="<?php echo (isset($tab) && $tab == 'sentbox') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/missed_call_alerts/sentbox">Sentbox</a>
            </li>
        </ul>
    </div>
</div>
</div>
<div class="container">
    <?php
    // Dashboard
    if (isset($tab) && $tab == 'dashboard') {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-content">
                        <div class="row">
                            <div class="col-md-6 content-header-title tbl">
                                Dashboard
                            </div>
                            <div class="col-md-6 content-header-title tbl text-right">
                                Missed Call Alerts Balance: <?php echo (isset($user_info) && $user_info['missed_call_balance']) ? $user_info['missed_call_balance'] : 0; ?>
                            </div>
                        </div>
                        <div class="table-responsive mt5" id="data_table">
                            <table class="table table-hover bgf">
                                <thead>
                                    <tr>
                                        <th>Missed Call Number</th>
                                        <th>Default Route</th>
                                        <th>
                                            Auto Reply
                                            <a type="button" data-toggle="tooltip" data-placement="top" style="cursor: pointer"
                                               title="Deduct from your transactional balance on each keyword reply">
                                                <i class="fa fa-question-circle"></i>
                                            </a>
                                        </th>
                                        <th>
                                            Forwarding
                                            <a type="button" data-toggle="tooltip" data-placement="top" style="cursor: pointer"
                                               title="Forward to your email and mobile number and Deduct from your transactional balance on each forwarding on mobile number">
                                                <i class="fa fa-question-circle"></i>
                                            </a>
                                        </th>
                                        <th>
                                            Web Hook
                                            <a type="button" data-toggle="tooltip" data-placement="top" style="cursor: pointer"
                                               title="Forward to your domain with following information: sender, message, keyword and datetime. For more info: Developer Tools->Virtual Numbers">
                                                <i class="fa fa-question-circle"></i>
                                            </a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($missed_call_services) && $missed_call_services) {
                                        $i = 1;
                                        foreach ($missed_call_services as $key => $missed_call_service) {
                                            ?>
                                            <tr>
                                                <td><?php echo $missed_call_service['mc_number']; ?></td>
                                                <td>
                                                    <ul class="padding0">
                                                        <li class="padding0">
                                                            <a href="javascript:void(0);" class="default_route" data-pk="<?php echo $missed_call_service['mc_service_id']; ?>"
                                                               data-name='missed_call|auto_reply_route'>
                                                                   <?php echo ($missed_call_service['auto_reply_route']) ? $missed_call_service['auto_reply_route'] : "Your Default Route"; ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td id="auto_reply<?php echo $i; ?>">
                                                    <?php
                                                    if ($missed_call_service['auto_reply_message']) {
                                                        ?>
                                                        <ul class="padding0">
                                                            <li class="padding0">
                                                                <h5>[<strong><?php echo $missed_call_service['auto_reply_sender']; ?></strong>]</h5>
                                                                <p><?php echo $missed_call_service['auto_reply_message']; ?></p>
                                                            </li>
                                                        </ul>
                                                        <?php
                                                    }
                                                    ?>
                                                    <button type="button" class="btn btn-primary btn-xs mc_auto_reply" data-toggle="popover"
                                                            id="popover<?php echo $i; ?>"
                                                            data-content="<form class='forms' action='javascript:void(0);'>
                                                            <div class='form-group'>
                                                            <input type='hidden' name='keyword_type' id='keyword_type<?php echo $i; ?>' value='long' />
                                                            <input type='text' name='reply_sender' id='reply_sender<?php echo $i; ?>' class='form-control' placeholder='Enter Sender Id'                                                             
                                                            data-parsley-minlength='6' data-parsley-minlength-message='Sender Id must be 6 characters'
                                                            data-parsley-maxlength='6' data-parsley-maxlength-message='Sender Id must be 6 characters' />
                                                            </div>
                                                            <div class='form-group'>
                                                            <textarea class='form-control' name='reply_content' id='reply_content<?php echo $i; ?>'
                                                            rows='2' placeholder='Enter Reply Content'></textarea></div>
                                                            <div class='form-group'>
                                                            <button class='btn btn-default btn-xs' type='submit' 
                                                            onclick='saveAutoReply(<?php echo $i; ?>, <?php echo $missed_call_service['mc_service_id']; ?>);'
                                                            id='btn'>Save</button></div></form>">
                                                                <?php
                                                                echo ($missed_call_service['auto_reply_message']) ? "Update" : "Add New";
                                                                ?>                                                        
                                                    </button>
                                                </td>
                                                <td>
                                                    <ul class="padding0">
                                                        <li class="padding0">
                                                            <a href="javascript:void(0);" class="email" data-pk="<?php echo $missed_call_service['mc_service_id']; ?>"
                                                               data-name='missed_call|forward_email'>
                                                                   <?php echo ($missed_call_service['forward_email']) ? $missed_call_service['forward_email'] : "Your Email"; ?>
                                                            </a>
                                                        </li>
                                                        <li class="padding0">
                                                            <a href="javascript:void(0);" class="contact" data-pk="<?php echo $missed_call_service['mc_service_id']; ?>"
                                                               data-name='missed_call|forward_contact'>
                                                                   <?php echo ($missed_call_service['forward_contact']) ? $missed_call_service['forward_contact'] : "Your Contact"; ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <ul class="padding0">
                                                        <li class="padding0">
                                                            <a href="javascript:void(0);" class="webhook" data-pk="<?php echo $missed_call_service['mc_service_id']; ?>"
                                                               data-name='missed_call|forward_webhook'>
                                                                   <?php echo ($missed_call_service['forward_webhook']) ? $missed_call_service['forward_webhook'] : "Your Web Hook URL"; ?>
                                                            </a>
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
                                            <td colspan="6">
                                                No Record Found!
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
        </div>
        <?php
    }

    // Inbox
    if (isset($tab) && $tab == 'inbox') {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-content">
                        <div class="row">
                            <div class="col-md-6 content-header-title tbl">
                                Inbox
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12" id="exportDLR">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" class="form-control" name="export_from_date" id="export_from_date" placeholder="Enter From Date" />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="form-control" name="export_to_date" id="export_to_date" placeholder="Enter To Date" />
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-2 col-xs-12">

                                <button type="button" name="export_all" id="export_all" class="btn btn-primary" data-loading-text="Processing..." autocomplete="off" onclick="exportAllMissedCallReports(<?php echo $user_id; ?>);">Export</button>

                            </div>
                           <div class="col-md-6 content-header-title tbl text-right">
                                Missed Call Alerts Balance: <?php echo (isset($user_info) && $user_info['missed_call_balance']) ? $user_info['missed_call_balance'] : 0; ?>
                            </div>
                        </div>
                        <div class="table-responsive mt5" id="data_table">
                            <table class="table table-hover bgf">
                                <thead>
                                    <tr>
                                        <th>Missed Call Number</th>
                                        <th>Sender</th>
                                        <th>Operator</th>
                                        <th>Circle</th>
                                        <th>Date Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($missed_call_inbox) && $missed_call_inbox) {
                                        foreach ($missed_call_inbox as $key => $inbox) {
                                            ?>
                                            <tr>
                                                <td><?php echo $inbox['mc_number']; ?></td>
                                                <td><?php echo $inbox['mc_inbox_sender']; ?></td>
                                                <td><?php echo $inbox['mc_inbox_operator']; ?></td>
                                                <td><?php echo $inbox['mc_inbox_circle']; ?></td>
                                                <td><?php echo $inbox['mc_inbox_date']; ?></td>
                                            </tr>
                                            <?php
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
                            </table>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <?php
    }

    // Sentbox
    if (isset($tab) && $tab == 'sentbox') {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-content">
                        <div class="row">
                            <div class="col-md-6 content-header-title tbl">
                                Sentbox
                            </div>
                            <div class="col-md-6 content-header-title tbl text-right">
                                Missed Call Alerts Balance: <?php echo (isset($user_info) && $user_info['missed_call_balance']) ? $user_info['missed_call_balance'] : 0; ?>
                            </div>
                        </div>
                        <div class="table-responsive mt5" id="data_table">
                            <table class="table table-hover bgf">
                                <thead>
                                    <tr>
                                        <th>Missed Call Number</th>
                                        <th>Receiver</th>
                                        <th>Content</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($missed_call_sentbox) && $missed_call_sentbox) {
                                        foreach ($missed_call_sentbox as $key => $sentbox) {
                                            ?>
                                            <tr>
                                                <td><?php echo $sentbox['mc_number']; ?></td>
                                                <td><?php echo $sentbox['mc_sentbox_reciever']; ?></td>
                                                <td>
                                                    <h5><?php echo $sentbox['auto_reply_sender']; ?></h5>
                                                    <p><?php echo $sentbox['auto_reply_message']; ?></p>
                                                </td>
                                                <td><?php echo $sentbox['mc_sentbox_datetime']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="4">No Record Found!</td>
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
        </div>
        <?php
    }
    ?>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.forms').parsley();
    });
</script>