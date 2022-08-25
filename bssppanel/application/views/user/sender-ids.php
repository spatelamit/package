<?php 
  $hostname = "localhost";
  $username = "bulksms_user";
  $password = "BALAJI@sr#ts7828";
  $database = "bulksms_system";


  $conn = mysql_connect("$hostname","$username","$password") or die(mysql_error());
  mysql_select_db("$database", $conn) or die(mysql_error());

?>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-9 col-sm-8">
            <div class="portlet">
                <h2 class="content-header-title tbl">Sender Ids</h2>
                
                <h4 style="color: red;">You have open route so you don't need to approve your sender id !</h4>
                <div class="portlet-content">
                    <div class="table-responsive" id="data_table">
                        <table class="table table-hover bgf">
                            <thead>
                                <tr>
                                    <th>Sender Id</th>
                                    <th>Status</th>
                                    <th>No of SMS</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($sender_id) && $sender_id) {
                                    $i = 1;
                                    $sender_id = $sender_id;
                                    $sender_ids_array = explode(',', $user_sender_ids);
                                    $sender_status_array = explode(',', $user_sender_status);
                                    foreach ($sender_ids_array as $sender_key => $sender_value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $sender_value; ?></td>
                                            <td>
                                                <?php
                                                if ($sender_status_array[$sender_key] == '1')
                                                    echo "<span class='label label-success'>Approved</span>";
                                                elseif ($sender_status_array[$sender_key] == '0')
                                                    echo "<span class='label label-danger'>Disapproved</span>";
                                                ?>
                                            </td>
                                            <td>
                                               <?php 
                                              
                                                $sql    = "SELECT Sum(`total_messages`) AS totalnomsgs FROM `campaigns` where `user_id` = '".$user_id."' AND `sender_id`= '".$sender_value."'";
                                                $query  = mysql_query($sql);
                                                $result = mysql_fetch_row($query);
                                                foreach ($result as $number_of_sms)
                                                {?>
                                                <a href="#"><?php 
                                                    if($number_of_sms == 0)
                                                        {
                                                                    echo "Null";
                                                                } else {
                                                                    echo $number_of_sms;
                                                                }
                                                                ?></a>
                                                
                                                <?php
                                                //<?php echo base_url()."user/show_senderid_sms/".$user_id."/".$sender_value; 
                                                
                                                }?>
                                               
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="deleteUserData('sender', <?php echo $sender_key; ?>)"
                                                   class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="Delete Sender Id">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </td>
                                            
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                }else {
                                    ?>
                                    <tr>
                                        <td colspan="3">No Record Found!</td>
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
        <div class="col-md-3 col-sm-4">
            <div class="portlet">
                <h2 class="content-header-title">Add Sender ID</h2>
                <div class="portlet-content">
                    <form id="senderIdForm" class="tab-forms" action="javascript:saveSenderId();">
                        <div class="row">
                            <div class="form-group col-md-12 padding0">
                                <label for="sender">Sender ID</label>
                                <input type="text" name="sender" id="sender" placeholder="Please Enter Sender Id" value=""
                                       class="form-control" required="" data-parsley-required-message="Please Enter Sender Id"
                                       data-parsley-minlength="6" data-parsley-maxlength="6" data-parsley-minlength-message="Sender Id must be of 6 characters long"
                                       data-parsley-maxlength-message="Sender Id must be of 6 characters long" />
                            </div>
                            <div class="form-group col-md-12 padding0 mt5">
                                <button type="submit" class="btn btn-primary">Approve Sender Id</button>
                            </div>
                        </div>
                    </form>
                </div> 
            </div> 
        </div>
    </div>
</div>