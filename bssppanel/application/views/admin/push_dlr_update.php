<script src="<?php echo base_url(); ?>Assets/admin/js/highcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>Assets/admin/js/exporting.js" type="text/javascript"></script>
<?php
$total = 0;
$total_pending = 0;
$total_cancelled = 0;
$total_sent = 0;
$total_dnd = 0;
$total_reject = 0;
$total_blocked = 0;
$total_submit = 0;
$total_failed = 0;
$total_delivered = 0;

if (isset($sent_sms_status) && $sent_sms_status) {

    foreach ($sent_sms_status AS $status) {
        $smsc_no = $status['user_group_id'];
        if ($status['status'] == "1" || $status['status'] == "PROCESSED" || $status['status'] == 'ANSWERED_MACHINE' || $status['status'] == 'ANSWERED') {
            $total_delivered += $status['Count_Status'];
        } elseif ($status['status'] == "2" || $status['status'] == "ERROR" || $status['status'] == 'ERROR_NO_ANSWER' || $status['status'] == 'INVALID') {
            $total_failed += $status['Count_Status'];
        } elseif ($status['status'] == "31" || $status['status'] == "PENDING" || $status['status'] == "23") {
            $total_pending += $status['Count_Status'];
        } elseif ($status['status'] == "8" || $status['status'] == "ERROR_USER_BUSY" || $status['status'] == 'SUBMITTED') {
            $total_submit += $status['Count_Status'];
        } elseif ($status['status'] == "DND" || $status['status'] == "9") {
            $total_dnd += $status['Count_Status'];
        } elseif ($status['status'] == "16" || $status['status'] == "Rejected" || $status['status'] == "ERROR_NOT_ENOUGH_CREDITS" || $status['status'] == "ERROR_NETWORK_NOT_AVAILABLE" || $status['status'] == 'ERROR_ROUTE_NOT_AVAILABLE' || $status['status'] == 'ERROR_UNSUPPORTED_AUDIO_FORMAT' || $status['status'] == 'ERROR_DOWNLOADING_FILE') {
            $total_reject += $status['Count_Status'];
        } elseif ($status['status'] == "Blocked") {
            $total_blocked += $status['Count_Status'];
        } elseif ($status['status'] == "3" || $status['status'] == "4") {
            $total_sent += $status['Count_Status'];
        } elseif ($status['status'] == "25") {
            $total_cancelled += $status['Count_Status'];
        }
    }
}
$total = $total_delivered + $total_blocked + $total_cancelled + $total_dnd + $total_failed + $total_pending + $total_reject + $total_sent + $total_submit;
?>
<div class="page-content-title txt-center">
    <h3><i class="fa fa-download"></i> DLR Push </h3> 
</div>
<div id="custom-sms">
    <div class="col-md-4 padding15">
        <h4>campaign Status Table</h4>
    </div>
    <div class="col-md-12 padding15">




        <div class="table-responsive">
            <table class="table table-bordered bgf9">
                <tbody>
                    <tr>
                        <th>Status</th>
                        <?php echo ($total_dnd) ? "<th>NDNC</th>" : ""; ?>
                        <?php 
                      
                        if ($campaign_status->campaign_status == 2) {
                            ?>
                            <?php echo ($total_pending) ? "<th>Pending</th>" : ""; ?> 
                        <?php } else {
                            ?>
                            <?php echo ($total_pending) ? "<th>Submit</th>" : ""; ?>   
                            <?php
                        }
                        ?>
                        <?php echo ($total_blocked) ? "<th>Blocked</th>" : ""; ?>
                        <?php echo ($total_reject) ? "<th>Rejected</th>" : ""; ?>
                        <?php echo ($total_submit) ? "<th>Submitted</th>" : ""; ?>
                        <?php echo ($total_sent) ? "<th>Sent</th>" : ""; ?>
                        <?php echo ($total_failed) ? "<th>Failed</th>" : ""; ?>
                        <?php echo ($total_delivered) ? "<th>Delivered</th>" : ""; ?>
<?php echo ($total_cancelled) ? "<th>Cancelled</th>" : ""; ?>
                        <th>Total</th>
                    </tr>
                    <tr>
                        <th>Count</th>
                        <?php echo ($total_dnd) ? "<td>$total_dnd</td>" : ""; ?>
                        <?php echo ($total_pending) ? "<td>$total_pending</td>" : ""; ?>
                        <?php echo ($total_blocked) ? "<td>$total_blocked</td>" : ""; ?>
                        <?php echo ($total_reject) ? "<td>$total_reject</td>" : ""; ?>
                        <?php echo ($total_submit) ? "<td>$total_submit</td>" : ""; ?>
                        <?php echo ($total_sent) ? "<td>$total_sent</td>" : ""; ?>
                        <?php echo ($total_failed) ? "<td>$total_failed</td>" : ""; ?>
                        <?php echo ($total_delivered) ? "<td>$total_delivered</td>" : ""; ?>
<?php echo ($total_cancelled) ? "<td>$total_cancelled</td>" : ""; ?>
                        <th><?php echo $total; ?></th>
                    </tr>
                </tbody>
            </table>
        </div>      
        <div class="col-md-12">
            <form action="javascript:update_push_dlr();" method="post" id="update_dlr_form" class="form parsley-form">
                <div class="col-md-3">
                    <label>Select Action Type</label>
                    <select name="dlr_action_type" id="dlr_action_type" class="form-control" required="" 
                            data-parsley-required-message="Please Select Action!">
                        <option value="">Select</option>
                        <option value="1">All</option>
                        <option value="2">Failed</option>
                        <option value="3">Sent</option>
                        <option value="4">Delivered</option>
                        <option value="7">Rejected</option>
                        <option value="8">Submit</option>
                    </select><br>


                    <label> Delivered Ratio</label>
                    <div class="input-group">
                        <input type="text" name="delivered_ratio"   placeholder=" Delivered Ratio" class="form-control"
                               data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                        <div class="input-group-addon">%</div>
                    </div><br>

                    <label> Failed Ratio</label>
                    <div class="input-group">
                        <input type="text" name="failed_ratio"  placeholder="Failed Ratio" class="form-control"
                               data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                        <div class="input-group-addon">%</div>
                    </div>
                    <br>
                    <label>Sent Ratio</label>
                    <div class="input-group">
                        <input type="text" name="sent_ratio"   placeholder="Sent Ratio" class="form-control"
                               data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                        <div class="input-group-addon">%</div>
                    </div>
                    <br>
                    <label>Submit Ratio</label>
                    <div class="input-group">
                        <input type="text" name="submit_ratio" placeholder="Submit Ratio" class="form-control"
                               data-parsley-type-message="Please Enter Valid Percentage" data-parsley-type="integer" data-parsley-range="[0,100]" />
                        <div class="input-group-addon">%</div>
                    </div>
                    <br>
                    <input type="hidden" name="campaign_id" id="campaign_id" value="<?php echo $campaign_id; ?>" />
                    <input type="hidden" name="no_of_sms" id="no_of_sms" value="<?php echo $total; ?>" />
                    <input type="hidden" name="total_delivered" id="total_delivered" value="<?php echo $total_delivered; ?>" />
                    <input type="hidden" name="total_failed" id="total_failed" value="<?php echo $total_failed; ?>" />
                    <input type="hidden" name="total_submit" id="total_submit" value="<?php echo $total_pending; ?>" />
                    <input type="hidden" name="total_sent" id="total_sent" value="<?php echo $total_sent; ?>" />
                    <input type="hidden" name="total_reject" id="total_reject" value="<?php echo $total_reject; ?>" />
                    <button type="submit" name="update_push_dlr" id="update_push_dlr" class="btn btn-primary"
                            data-loading-text="Resending..." autocomplete="off">Update DLR</button>
                </div>
            </form>
        </div>
    </div>
</div>
