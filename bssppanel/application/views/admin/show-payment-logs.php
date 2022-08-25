<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>

<div style="margin-left: 750px;" class="col-md-3 padding15">
     <div class="form-group"><input class="form-control " type = "input" id = "name" size = "30" placeholder="Search Approval Payment By Date"  />
     </div>
 </div>
	<div class="col-md-1 padding15">
            <div class="form-group"> 
    <input class="btn btn-primary" data-loading-text="Searching..." autocomplete="off" type = "button" id = "driver" value = "GET REPORT" style="background-color: #2780E3; color: white; height: 33px;" /></div>
        </div><br/>
         <div class="col-md-12 hidden" id="select_option">
             <input type="hidden" name="selected_payment" id="selected_payments" value="" />
                    <input type="button" id="select_button" class="btn btn-danger btn-sm pull-right" value="Approve Payment" 
                           onclick="aprovePayment();" />
                </div>
        
        
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
                        <th>Actual Amount</th>
                        <th>Tax</th>
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
                            <td> <i class="fa fa-inr"></i> &nbsp;<?php echo $show_transation_log['txn_amount']; ?></td>

                            <td><?php echo $show_transation_log['txn_type']; ?></td>
                            <td><?php echo $show_transation_log['txn_date']; ?></td>
                            <td><?php echo $show_transation_log['aproval_date']; ?></td>
                            <th><?php echo $show_transation_log['actual_amount']; ?></th>
                            <th><?php echo $show_transation_log['tax_amount']; ?></th>
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
                }
                ?>
            </table>

            <!-- Pagination -->
            <?php echo $paging; ?>
        </div>


    </div>
</div>
</div>
<script  type="text/javascript">
function selectAprovePayment()
{
  
    var selected_payment = "";
    var number = 1;
    var count = 0;
    $('.check_payment').each(function (index, value) {
        if ($(value).prop('checked'))
        {
            if (selected_payment === "")
                selected_payment = $(value).val();
            else
                selected_payment += "," + $(value).val();
            count++;
        }
        number++;
    });
    if (selected_payment === "")
    {
        $('#select_option').addClass('hidden');
        $('#select_button').val('Delete 0 Contacts');
        $('#selected_payments').val(selected_payment);
    } else
    {
        $('#select_option').removeClass('hidden');
        $('#select_button').val('Update ' + count + ' Rows');
        $('#selected_payments').val(selected_payment);
    }
}
</script>

<script>
function aprovePayment()
{
    var total_contacts = $('#total_contacts').val();
    if (total_contacts === '0')
    {
     
        return false;
    } else {
        if (confirm("Are you sure! you want to update status.")) {
           
            
            var selected_payments = $('#selected_payments').val();
           
            $.ajax({'url':"http://sms.bulksmsserviceproviders.com/admin/update_payment_status", 'type': 'POST', 'data': {'selected_payment': selected_payments}, 'success': function (data) {    
            var container = $('#payment_approve');
                    if (data) {
                        container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
                    }
                }});
        }
    }
}
</script>




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