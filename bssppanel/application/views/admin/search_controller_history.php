<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
     
<!-- Tab panes -->
<div class="table-responsive" class="col-md-6" id="search_payment_report_table" style="width: 1200px;">
<div class="tab-content bgf9">
    <div class="tab-pane fade in active">
        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="stage">
                <thead>
                    <tr>
                       
                        <th>Sr. No</th>
                         <th>Admin Name</th>
                     <th>Admin/User</th>
                        <th>IP Address</th>
                        <th>History URL</th>
                        <th>Date & Time</th>
                      

                    </tr>
                </thead>
               
                 <?php
                 $number = 0;
                if (isset($history_details) && $history_details) {

                    foreach ($history_details as $show_history_details) {
                        $number++;
                        ?>
                        <tr>
                            <td><?php 
                            echo $number;
                            ?></td>
                               <td>
                             <?php echo $show_history_details['admin_username']; ?>

                            </td>
                             <td> <?php echo $show_history_details['status']; ?></td>
                            <td>
                              <?php echo $show_history_details['ip_address']; ?>
                            </td>
                         
                            <td>  <?php echo $show_history_details['history_url']; ?></td> 
                            <td>  <?php echo $show_history_details['date_time']; ?></td>
                          
                            
                                    <?php
                                }
                                ?>


                           

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
            $("#get_history_search").click(function(event){
               var search_history = $("#search_history").val();
               $("#stage").load('<?php  base_url(); ?>/index.php/admin/search_controller_history', {"search_history":search_history} );
            });
         });
      </script>
<script type="text/javascript">
        $(document).ready(function ()
        {
            var nowDate = new Date();
            var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate());
            $('#search_history').datetimepicker({
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