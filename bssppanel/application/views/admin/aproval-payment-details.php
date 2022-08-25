
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
<div class="tab-content bgf9">
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
                        <th>Aproval Date</th>
                        <th>Status</th>

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
                            <td><i class="fa fa-inr"></i> &nbsp;<?php echo $show_transation_log['txn_amount']; ?></td>

                            <td><?php echo $show_transation_log['txn_type']; ?></td>
                            <td><?php echo $show_transation_log['txn_date']; ?></td>
                            <td><?php echo $show_transation_log['aproval_date']; ?></td>
                            <td>
                               <?php
                                if ($show_transation_log['txn_status'] == 0) {
                                    ?>
                                    <b><div style=" height: 26px; width: 65px; background-color: #62c462;border-radius: 2px; float:left; " >
                                            <div style="margin-left: 4px;"><a style=" color: white;text-decoration: none; "href="<?php echo base_url()."admin/payment_aprova_update/".$show_transation_log['txn_log_id'];?>" >Approve</a></div>
                                        </div></b>
                                 <label for="" class="">
                                            <input class="check_payment" type="checkbox" name="checkbox[]" 
                                                   value="<?php echo $show_transation_log['txn_log_id']; ?>" onclick="selectAprovePayment();" style="height: 20px; width: 34px;" />
                                            <span></span>
                                        </label>
                                    <?php
                                } else {
                                    ?>
                                    <b><div style=" height: 26px; width: 85px; background-color: #E74C3C;border-radius: 2px; " >
                                            <div style="margin-left: 4px;"><a style=" color: white;text-decoration: none; " href ="<?php echo base_url()."admin/payment_disaprova/".$show_transation_log['txn_log_id'];?>" >Disapprove</a></div>
                                        </div></b>
                                 <label for="contact" class="">
                                            <input class="check_payment" type="checkbox" name="checkbox[]" 
                                                   value="<?php echo $show_transation_log['txn_log_id']; ?>" onclick="selectAprovePayment();" style="height: 20px; width: 34px;"/>
                                            <span></span>
                                        </label>
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
                        <td align="center" colspan="10">No Record Found!</td>
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
               $("#stage").load('<?php  base_url(); ?>/index.php/admin/search_aproval_payment', {"name":name} );
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