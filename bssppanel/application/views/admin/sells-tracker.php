<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/admin/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/admin/js/bootstrap-datetimepicker.min.js"></script>
-->
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/admin.script.js"></script>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
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
    <h3><i class="fa fa-inr"></i> Sells Tracker</h3> 
</div>
<div id="custom-sms">
    <form role="form" id="sellerReportForm" method='post' action="javascript:getSellerReport();" class="notify-forms">
        <div class="col-md-3 padding15">
            <div class="form-group">                         
                <select name="admin_id" id="admin_id" class="form-control"  data-parsley-required-message="Please Select Admin">
                    <option value="">Select Admin Name</option>
                    <?php
                    if (isset($admin_names) && $admin_names) {
                        foreach ($admin_names as $admin_name) {
                            ?>
                            <option value="<?php echo $admin_name['admin_id']; ?>"><?php echo $admin_name['admin_name']; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div> 
        </div>
        <div class="col-md-3 padding15">
            <div class="form-group">                         
                <select name="txn_type" id="txn_type" class="form-control"  data-parsley-required-message="Please Select User Type">
                    <option value="">Select Type</option>

                    <option value="Add">Add</option>
                    <option value="reduce">Reduce</option>
                </select>
            </div>  
        </div>
        
        <div class="col-md-4 padding15">
            <div class="input-daterange input-group" id="">
                <input type="text" class="form-control "  name="by_from_date" id="datepicker" placeholder="YYYY-MM-DD"/>
                <span class="input-group-addon">to</span>
                <input  type="text" class="form-control " name="by_to_date" id="datepicker1" placeholder="YYYY-MM-DD" /> 
               
            </div>
        </div>


        <div class="col-md-1 padding15">
            <div class="form-group">   
                <button  id="click_report" class="btn btn-primary"
                        data-loading-text="Searching..." autocomplete="off" type="submit">
                    Get Report
                </button>
            </div>                        
        </div>
    </form>
    <div class="col-md-12 padding15" id="get_seller_reports"> </div>
    <script type="text/javascript">
        $(document).ready(function ()
        {
            var nowDate = new Date();
            var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate());
            $('#datepicker').datetimepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                endDate: today,
                todayHighlight: true
            });
            
              $('#datepicker1').datetimepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                endDate: today,
                todayHighlight: true
            });
            $('#filter_by_date').datepicker({
                format: "yyyy-mm-dd",
                endDate: today,
                autoclose: true,
                todayHighlight: true
            });
            $('#by_users').multiselect({
                //includeSelectAllOption: true,
                //selectAllText: 'Select All',
                maxHeight: 300,
                enableFiltering: true,
                enableClickableOptGroups: true,
                enableCaseInsensitiveFiltering: true
            });
        });
    </script>