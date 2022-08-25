<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/admin/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/admin/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<?php
error_reporting(0);
$hostname = "localhost";
$username = "bulksms_user";
$password = "BALAJI@sr#ts7828";
$database = "bulksms_system";


$conn = mysql_connect("$hostname", "$username", "$password") or die(mysql_error());
mysql_select_db("$database", $conn) or die(mysql_error());
?>

<!-- Tab panes -->
<div class="table-responsive" class="col-md-6" id="search_payment_report_table" style="width: 1200px;">
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
                        <th>Amount (<i class="fa fa-inr"></i>)</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Approval Date</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <?php
                if (isset($show_payment_details) && $show_payment_details) {

                    foreach ($show_payment_details as $show_transation_log) {
                        ?>
                        <tr>
                               <td>
                                <?php
                                $admin_id = $show_transation_log['txn_admin_from'];
                                $query = "SELECT `admin_username` FROM `administrators` WHERE `admin_id` = '" . $admin_id . "'";
                                $result = mysql_query($query);
                                $row = mysql_fetch_array($result);
                                $admin_username = $row["admin_username"];
                                echo $admin_username;
                                ?>

                            </td>
                            <td><?php
                                $user_id = $show_transation_log['txn_user_to'];
                                $query = "SELECT `username` FROM `users` WHERE `user_id` = '" . $user_id . "'";
                                $result = mysql_query($query);
                                $row = mysql_fetch_array($result);
                                $username = $row["username"];
                               if($username){
                                  echo $username;  
                               }  else {
                                   
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
                            <td><i class="fa fa-inr"></i> &nbsp;<?php echo $show_transation_log['txn_amount']; ?> </td>

                            <td><?php echo $show_transation_log['txn_type']; ?></td>
                            <td><?php echo $show_transation_log['txn_date']; ?></td>
                            <td><?php echo $show_transation_log['aproval_date']; ?></td>
                            <td>
                                <?php
                                if ($show_transation_log['txn_status'] == 1) {
                                    ?>
                                    <b><div style=" height: 26px; width: 65px; background-color: #62c462;border-radius: 2px; " >
                                            <div style="margin-left: 4px; color: white;text-decoration: none;">Approve</div>
                                        </div></b>
                                    <?php
                                } else {
                                    ?>
                                    <b><div style=" height: 26px; width: 110px; background-color: #E74C3C;border-radius: 2px; " >
                                            <div style="margin-left: 4px; color: white;text-decoration: none;  ">Not Approved</div>
                                        </div></b>
                                    <?php
                                }
                                ?>


                            </td>

                        </tr>
                        <?php
                    }
                }else {
                            ?>
                    <tr>
                        <td  align="center" colspan="10">No Record Found!</td>
                    </tr>
                    <?php
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
         $(document).ready(function() {
            $("#driver").click(function(event){
               var name = $("#name").val();
               $("#stage").load('<?php  base_url(); ?>/index.php/admin/search_payment_by_date', {"name":name} );
            });
         });
      </script>


<script type="text/javascript">
    $(function() {
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