<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/admin/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/admin/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<?php
error_reporting(0);
$hostname = "localhost";
$username = "mayank4t_bulksms";
$password = "bulksms";
$database = "mayank4t_bulksms";


$conn = mysql_connect("$hostname", "$username", "$password") or die(mysql_error());
mysql_select_db("$database", $conn) or die(mysql_error());
?>
<div class="page-content-title txt-center" >
    <h3><i class="fa fa-paypal"></i>&nbsp;  Sub-Admin Payment Approval Details</h3> 
</div>


<div style="margin-left: 750px;" class="col-md-3 padding15">
    <div class="form-group"> <input class="form-control" type = "input" id = "name" size = "30" placeholder="Search Approval Payment By Date"  /></div></div>
<div class="col-md-1 padding15">
    <div class="form-group">   	
        <input class="btn btn-primary" data-loading-text="Searching..." autocomplete="off" type = "button" id = "driver" value = "GET REPORT" style="background-color: #2780E3; color: white; height: 33px;" />
    </div>
</div><br/>




<!-- Tab panes -->
<div class="table-responsive" class="col-md-6" id="search_payment_report_table" style="width: 1330px;">
    <div class="tab-content bgf9"  >
        <div class="tab-pane fade in active">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="stage">
                    <thead>
                        <tr>

                            <th>Account Manager</th>
                            <th>Username</th>
                            <th>Route</th>
                            <th>Prizing</th>
                            <th>No of SMS</th>
                            <th>Amount (<i class="fa fa-inr">)</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Approval Date</th>

                            <th> Payment Status</th>
                            <th>Comment </th>
                            <th>Action</th>
                            <th>Invoice</th>

                        </tr>
                    </thead>
                    <?php
                    if (isset($transation_log) && $transation_log) {

                        foreach ($transation_log as $show_transation_log) {
                            ?>
                            <tr>
                                <td>
                                    <?php
                                    $admin_id = $show_transation_log['txn_admin_from'];
                                    $query1 = "SELECT `admin_username` FROM `administrators` WHERE `admin_id` = '" . $admin_id . "'";
                                    $result1 = mysql_query($query1);
                                    $row1 = mysql_fetch_array($result1);
                                    $admin_username = $row1["admin_username"];
                                    echo $admin_username;
                                    ?>

                                </td>
                                <td><?php
                                    $user_id = $show_transation_log['txn_user_to'];
                                    $query = "SELECT `username`,`gst_no`,`state` FROM `users` WHERE `user_id` = '" . $user_id . "'";
                                    $result = mysql_query($query);
                                    $row = mysql_fetch_array($result);
                                    $username = $row["username"];
                                    if ($username) {
                                        echo $username;
                                    } else {

                                        $admin_id = $show_transation_log['txn_admin_to'];
                                        $query = "SELECT `admin_username` FROM `administrators` WHERE `admin_id` = '" . $admin_id . "'";
                                        $result = mysql_query($query);
                                        $row = mysql_fetch_array($result);
                                        $admin_username = $row["admin_username"];
                                        echo $admin_username;
                                    }
                                    ?> </td>

                                <td><?php echo $show_transation_log['txn_route']; ?></td> 
                                <td><?php echo $show_transation_log['txn_price']; ?></td>
                                <td><?php echo $show_transation_log['txn_sms']; ?></td>
                                <td><?php echo $show_transation_log['txn_amount']; ?></i></td>

                                <td><?php echo $show_transation_log['txn_type']; ?></td>
                                <td><?php echo $show_transation_log['txn_date']; ?></td>
                                <td><?php echo $show_transation_log['aproval_date']; ?></td>
                            <form method="post" action="<?php echo base_url(); ?>admin/update_payment_subadmin"> 
                                <td>
                                    <input type="hidden" name="txn_log_id" value="<?php echo $show_transation_log['txn_log_id']; ?>"/>
                                    <select name="payment_status" required="required">
                                        <option value="0">Pending</option>
                                        <?php
                                        if ($show_transation_log['txn_status'] == 1) {
                                            ?>
                                            <option value="1" selected="">Done</option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="1">Done</option>  
                                        <?php }
                                        ?>

                                    </select>
                                </td>
                                <td><textarea name="comment" value="" placeholder="Comment " required="required"><?php echo $show_transation_log['txn_description']; ?></textarea>
                                </td>

                                <td>   <?php
                                    if ($show_transation_log['txn_status'] == 1) {
                                        ?>
                                        <input style=" font-weight: bold ; background-color: #7FB338;border-color: #7FB338;" class="btn btn-success" type="submit"  name="submit" value="save"/>
                                        <?php
                                    } else {
                                        ?>
                                        <input style="font-weight: bold ;background-color:#0b79ea;border-color:#0b79ea;" class="btn btn-primary" type="submit"  name="submit" value="save"/>
                                    <?php }
                                    ?>



                                </td>

                            </form>
                            <td> 
                                <?php
                                if ($row["state"] == "Madhya Pradesh") {
                                    ?>

                                    <a href="<?php echo "http://theofficearea.in/convert-pdf/bssp_index.php?type=" . $show_transation_log['txn_route'] . "&gst=" . $row["gst_no"] . "&amount=" . $show_transation_log['txn_amount']; ?>" role="button" class="btn btn-warning" style="font-weight: bold;">Invoice</a>

                                    <?php
                                } else {
                                    ?>
                                    <a href="<?php echo "http://theofficearea.in/convert-pdf/bssp_center.php?type=" . $show_transation_log['txn_route'] . "&gst=" . $row["gst_no"] . "&amount=" . $show_transation_log['txn_amount']; ?>" role="button" class="btn btn-warning" style="font-weight: bold;">Invoice</a>
                                    <?php
                                }
                                ?>



                            </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </table>

                <!-- Pagination -->
                  <?php echo $paging; ?>
            </div>


        </div>
    </div>
</div>
<script type = "text/javascript" language = "javascript">
    $(document).ready(function () {
        $("#driver").click(function (event) {
            var name = $("#name").val();
            $("#stage").load('<?php base_url(); ?>/index.php/admin/search_payment_by_date', {"name": name});
        });
    });
</script>



<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate());
        $('#name').datetimepicker({
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