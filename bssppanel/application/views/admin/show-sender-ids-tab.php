<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>

<style>
    #settings .tab-content {
        color: #1b1b1b;
        font-size: 17px !important;
        font-weight: 400;   }
    .import_data{
        float: right;
        margin-top: -151px;
    }
    .resposiveTable {
        margin-top: 30px;
    }
    .upload_file{
        margin-top: -33px;
        margin-bottom: 20px;
        background-color: #eee;
    }
    .alert-error{
        color: red;
    }
    .alert-success{
        color: green;
    }
    .export_by_date{
        width: 100%;
        float: left;
    }
    
    
    


</style>
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSenderTab('1');">Approved Sender Ids</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSenderTab('2');">Pending Sender Ids</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "3") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSenderTab('3');">Unique Sender Ids</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "4") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSenderTab('4');">Insert New Sender Ids</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "5") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSenderTab('5');">Sender ID Routing</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "6") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSenderTab('6');">Not Approve Sender ID</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "7") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSenderTab('7');">PR Approve Sender ID</a>
    </li>
</ul>

<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active">

        <?php if (isset($subtab) && $subtab == '1') { ?>
            <div class="row padding15">
                
                <div class="col-md-12 text-right">
                    <a href="javascript:void(0);" class="btn btn-info btn-sm" id="export_senders" onclick="exportSenderIds();">Export</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Sender Id</th>
                                    <th>Status</th>
                                    <th colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($user_sender_ids) && $user_sender_ids) {
                                    $i = 1;
                                    foreach ($user_sender_ids as $user_sender_id) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $user_sender_id['username']; ?> ( <?php echo $user_sender_id['parent_username']; ?> )
                                            </td>
                                            <td><?php echo $user_sender_id['sender']; ?></td>
                                            <td>
                                                <?php if ($user_sender_id['sender_status'] == '1') { ?>
                                                    <span class="label label-success">Approved</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($user_sender_id['sender_status'] == '1') {
                                                    ?>
                                                    <button onclick="changeSIdStatus('<?php echo $user_sender_id['sender_id']; ?>', '<?php echo $user_sender_id['sender_key']; ?>', 0, '<?php echo $subtab; ?>');"
                                                            type="button" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Disapprove Sender Id">
                                                        <i class="fa fa-ban"></i>
                                                    </button>
                                                    <?php
                                                } elseif ($user_sender_id['sender_status'] == '0') {
                                                    ?>
                                                    <button onclick="changeSIdStatus('<?php echo $user_sender_id['sender_id']; ?>', '<?php echo $user_sender_id['sender_key']; ?>', 1, '<?php echo $subtab; ?>');"
                                                            type="button" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Approve Sender Id">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <button onclick="deleteSenderId('<?php echo $user_sender_id['sender_id']; ?>', '<?php echo $user_sender_id['sender_key']; ?>', '<?php echo $subtab; ?>');"
                                                        type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Sender Id">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="5" align="center">
                                            <strong>No Sender Id</strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <?php echo $paging; ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '2') { ?>
        <div class="input-group col-md-4">
            <input class="form-control search-form-stl" placeholder="Search Username" id="senderid_by_username" onkeyup="searchSenderidByUsername(this.value);" autocomplete="off" type="text">                                           
            <span class="input-group-btn">
                <button class="btn btn-default search-form-stl" type="button">
                    <i class="fa fa-search"></i> 
                </button>
            </span>

        </div><br>
        
        
        
            <div class="table-responsive" id="search_response">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Sender Id</th>
                            <th>Status</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($user_sender_ids) && $user_sender_ids) {
                            $i = 1;
                            foreach ($user_sender_ids as $user_sender_id) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $user_sender_id['username']; ?> ( <?php echo $user_sender_id['parent_username']; ?> )
                                    </td>
                                    <td><?php echo $user_sender_id['sender']; ?></td>
                                    <td>
                                        <?php if ($user_sender_id['sender_status'] == '0') { ?>
                                            <span class="label label-danger">Disapproved</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($user_sender_id['sender_status'] == '1') {
                                            ?>
                                            <button onclick="changeSIdStatus('<?php echo $user_sender_id['sender_id']; ?>', '<?php echo $user_sender_id['sender_key']; ?>', 0, '<?php echo $subtab; ?>');"
                                                    type="button" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Disapprove Sender Id">
                                                <i class="fa fa-ban"></i>
                                            </button>
                                            <?php
                                        } elseif ($user_sender_id['sender_status'] == '0') {
                                            ?>
                                            <button onclick="changeSIdStatus('<?php echo $user_sender_id['sender_id']; ?>', '<?php echo $user_sender_id['sender_key']; ?>', 1, '<?php echo $subtab; ?>');"
                                                    type="button" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Approve Sender Id">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <button onclick="deleteSenderId('<?php echo $user_sender_id['sender_id']; ?>', '<?php echo $user_sender_id['sender_key']; ?>', '<?php echo $subtab; ?>');"
                                                type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Sender Id">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="5" align="center">
                                    <strong>No Sender Id</strong>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php echo $paging; ?>
            </div>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '3') { ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>                       
                            <th>Username</th>
                            <th>Unique Sender Id</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($user_sender_ids) && $user_sender_ids) {
                            $i = 1;
                            foreach ($user_sender_ids as $user_sender_id) {
                                ?>
                                <tr>                                     
                                    <td>
                                        <?php echo $user_sender_id['username']; ?> 
                                    </td>
                                    <td>
                                        <?php echo $user_sender_id['sender_id']; ?>
                                    </td>                          
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="5" align="center">
                                    <strong>No Sender Id</strong>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php echo $paging; ?>
            </div>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '4') { ?>
            <!--<script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->
            <form  action="javascript:insertNewSenderID();" id="insertSenderID" method="post">
                <div class="col-md-12">
                    <div id="error" class="alert-error"></div>
                    <div id="success" class="alert-success"></div>
                </div>
               <select name="user_group_id" style="height: 30px;">
                    <?php
                    if (isset($user_sender_ids) && $user_sender_ids) {
                        foreach ($user_sender_ids as $user_sender_id) {
                            ?>
                            <option value="<?php echo $route = $user_sender_id['user_group_id']; ?>"><?php echo $user_sender_id['user_group_name']; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select><br><br>
                <input type="text" name="sender_id" placeholder="Sender Id" id="signup_username" onkeyup="checkSenderID(this.value);" required="required"><br><br>
                
                <!--<input type="submit" name="submit" value="save" class="btn btn-primary btn-sm" style="width: 130px;">-->
            
                <button type="submit" class="btn btn-primary" name="submit" id="save_id" data-loading-text="Processing..." autocomplete="off">
                       Save</button>
                
            </form>
            <!--<a href="javascript:void(0);" class="btn btn-info btn-sm" id="export_senders" onclick="exportSenderIds();">Export</a>-->
            <!--import csv-->
            <div class="import_data col-md-4">
             <p id="msg" class="alert-success"></p>
             
              <!--  <input type="file" name="file" id="file" required="required" class="upload_file">-->
                <select name="user_group_id" id="new_route" style="height: 30px;">
                    <?php
                    if (isset($user_sender_ids) && $user_sender_ids) {
                        foreach ($user_sender_ids as $user_sender_id) {
                            ?>
                            <option value='<?php echo $user_sender_id['user_group_id']; ?>'><?php echo $user_sender_id['user_group_name']; ?></option>
                        <?php
                        }
                    }
                    ?>
                </select><br><br>
                
             <input type="file" id="file" name="file" required="required" /><br>
               <!-- <button id="upload" class="btn btn-primary btn-sm" style="width: 130px;">Import</button>-->
               <button type="submit" class="id_upload btn btn-primary" name="submit" id="import_id" data-loading-text="Processing..." autocomplete="off">
                       Import</button>
                
               <!-- <button type="submit" id="submit" name="import" class="btn btn-primary button-loading">Import</button>-->
            </div>
      <!--     <input type="text" class="form-control search-form-stl" placeholder="Search Sender ID" id="search_new_sender_id"  onkeyup="searchSenderID(this.value);" autocomplete="off" />-->
              <br>
              <table>
                  <tr>
                      <th>Total No. of sender Ids : </th>
                      <th><?php echo $size; ?></th>
                  </tr>
              </table>
            
            <div class="table-responsive resposiveTable">
                <table class="table table-hover table-bordered" id="show_ids">
                    <thead>
                        <tr>                       
                            <th>S.No</th>
                            <th>Sender Id</th>
                            <th>Route</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if (isset($user_sender_ids) && $user_sender_ids) {
                            foreach ($table_data as $data) {
                                // var_dump($data1['sender_id']);die;
                                ?>
                                <tr> 
                                    <td><?php echo $i; ?> </td>   
                                    <td><?php echo $data['sender_id']; ?> </td>
                                    <td><?php echo $data['user_group_name']; ?> </td>
                                    <td> <button onclick="deleteApproveSenderId('<?php echo $data['sender_id']; ?>', '<?php echo $user_sender_ids['sender_key']=0; ?>', '<?php echo $subtab; ?>');"
                                                 type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Sender Id">
                                            <i class="fa fa-trash"></i>
                                        </button></td>    


                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>

                            <tr>
                                <td colspan="5" align="center">
                                    <strong>No Sender Id</strong>
                                </td>
                            </tr>
    <?php } ?>
                    </tbody>
                </table>
<?php echo $paging; ?>
                <!-- Pagination -->
    
            </div>

<?php } ?>

        <!--div 5-->
<?php if (isset($subtab) && $subtab == '5') { ?>

            <h3>Switch Routing</h3>
            <p>Current Route</p>
           <!-- <form method="post" action="<?php echo base_url(); ?>index.php/admin/update_sender_id_routing">-->
                <form method="post" action="javascript:updateSenderidRouting();" id="sender_id_routing">
                <select name="current_route">
                    <option value="">Select Current Route </option>
                    <?php
                    if (isset($user_sender_ids) && $user_sender_ids) {
                        foreach ($user_sender_ids as $user_sender_id) {
                            ?>
                            <option value="<?php echo $user_sender_id['user_group_id']; ?>"><?php echo $user_sender_id['user_group_name']; ?></option>

                        <?php
                        }
                    }
                    ?>

                </select><br>

                <p> New Route</p>
                <select name="new_route">
                    <option value="">Select New Route </option>
                    <?php
                    if (isset($user_sender_ids) && $user_sender_ids) {
                        foreach ($user_sender_ids as $user_sender_id) {
                            ?>
                            <option value="<?php echo $user_sender_id['user_group_id']; ?>"><?php echo $user_sender_id['user_group_name']; ?></option>
                        <?php
                        }
                    }
                    ?>
                </select><br><br>
              <button type="submit" class="btn btn-primary" name="submit" id="save_routing" data-loading-text="Processing..." autocomplete="off">
  Save Route  </button>
            </form><br>

            <!-- Pagination -->


<?php } ?>

<?php if (isset($subtab) && $subtab == '6') { ?>
            <!--<script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->
            <h3 style="text-align:center; margin-bottom: 24px; margin-top: 0px;">Not Approved Sender Id</h3>

            <div class="export_by_date">
                <div class="row">
                    <div class="col-md-4 col-sm-12">   
                        <div><input type = "text" id = "name" size = "20" placeholder="Search By Date">
                            <input class="btn btn-info btn-sm" type = "submit" name="submit" value="GET REPORT" id = "driver" size = "10">
                        </div>
                    </div>    
                    <div class="col-md-6 col-sm-12"> 
                        <form method="post" class="form-inline" action="<?php echo base_url(); ?>admin/export_senderid_by_date">

                            <div class="input-daterange input-group">
                                <input type="text" class="form-control" name="export_from_date" id = "from_date" placeholder="Enter From Date">
                                <span class="input-group-addon">to</span>
                                <input type="text" class="form-control" name="export_to_date" id = "to_date" placeholder="Enter To Date">
                            </div>


                            <button type="submit" name="submit"  class="btn btn-info btn-sm">Export</button>

                        </form>
                    </div>
                    <div class="col-md-2 col-sm-12"><a href="<?php echo base_url(); ?>admin/approve_all_sender_id"  class="btn btn-info btn-sm">Approve To All</a> 
                    </div>   
                </div>
            </div><br><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="stage">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Sender Id</th>
                                    <th>Date</th>
                                    <th colspan="2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($user_sender_ids) && $user_sender_ids) {
                                    $i = 1;
                                    foreach ($user_sender_ids as $user_sender_id) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $i; ?> 
                                            </td>
                                            <td>
            <?php echo $user_sender_id['sender_id']; ?> 
                                            </td>
                                            <td><?php echo $user_sender_id['date']; ?></td>
                                            <td> <button onclick="clickToApprove('<?php echo $user_sender_id['id']; ?>', '<?php echo $subtab; ?>');"
                                                         type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Click to approve">
                                                    Click To Approve
                                                </button></td> 



                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="5" align="center">
                                            <strong>No Sender Id</strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
    <?php echo $paging; ?>
                    </div>
                </div>
            </div>





<?php } ?>
            
             <?php if (isset($subtab) && $subtab == '7') { ?>
            <!--<script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->
            <form  action="javascript:insertPrSenderID();" id="insertPrSenderID" method="post">
                <div class="col-md-12">
                    <div id="error" class="alert-error"></div>
                    <div id="success" class="alert-success"></div>
                </div>
               <select name="user_group_id" style="height: 30px;">
                    <?php
                    if (isset($user_sender_ids) && $user_sender_ids) {
                        foreach ($user_sender_ids as $user_sender_id) {
                            ?>
                            <option value="<?php echo $route = $user_sender_id['user_group_id']; ?>"><?php echo $user_sender_id['user_group_name']; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select><br><br>
                <input type="text" name="sender_id" placeholder="Sender Id" id="check_sender" onkeyup="checkPrSenderID(this.value);" required="required"><br><br>
                
                <!--<input type="submit" name="submit" value="save" class="btn btn-primary btn-sm" style="width: 130px;">-->
            
                <button type="submit" class="btn btn-primary" name="submit" id="save_pr_id" data-loading-text="Processing..." autocomplete="off">
                       Save</button>
                
            </form>
            <!--<a href="javascript:void(0);" class="btn btn-info btn-sm" id="export_senders" onclick="exportSenderIds();">Export</a>-->
            <!--import csv-->
            <div class="import_data col-md-4">
             <p id="msg" class="alert-success"></p>
             
              <!--  <input type="file" name="file" id="file" required="required" class="upload_file">-->
                <select name="pr_user_group_id" id="new_pr_route" style="height: 30px;">
                    <?php
                    if (isset($user_sender_ids) && $user_sender_ids) {
                        foreach ($user_sender_ids as $user_sender_id) {
                            ?>
                            <option value='<?php echo $user_sender_id['user_group_id']; ?>'><?php echo $user_sender_id['user_group_name']; ?></option>
                        <?php
                        }
                    }
                    ?>
                </select><br><br>
                
             <input type="file" id="pr_file" name="file" required="required" /><br>
               <!-- <button id="upload" class="btn btn-primary btn-sm" style="width: 130px;">Import</button>-->
               <button type="submit" class="pr_id_upload btn btn-primary" name="submit" id="import_pr_id" data-loading-text="Processing..." autocomplete="off">
                       Import</button>
                
               <!-- <button type="submit" id="submit" name="import" class="btn btn-primary button-loading">Import</button>-->
            </div>
      <!--     <input type="text" class="form-control search-form-stl" placeholder="Search Sender ID" id="search_new_sender_id"  onkeyup="searchSenderID(this.value);" autocomplete="off" />-->
              <br>
              <table>
                  <tr>
                      <th>Total No. of sender Ids : </th>
                      <th><?php echo $size; ?></th>
                  </tr>
              </table>
            
            <div class="table-responsive resposiveTable">
                <table class="table table-hover table-bordered" id="show_ids">
                    <thead>
                        <tr>                       
                            <th>S.No</th>
                            <th>Sender Id</th>
                            <th>Route</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if (isset($user_sender_ids) && $user_sender_ids) {
                            foreach ($table_pr_data as $data) {
                                // var_dump($data1['sender_id']);die;
                                ?>
                                <tr> 
                                    <td><?php echo $i; ?> </td>   
                                    <td><?php echo $data['sender_id']; ?> </td>
                                    <td><?php echo $data['user_group_name']; ?> </td>
                                    <td> <button onclick="deletePrApproveSenderId('<?php echo $data['sender_id']; ?>', '<?php echo $user_sender_ids['sender_key']=0; ?>', '<?php echo $subtab; ?>');"
                                                 type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Sender Id">
                                            <i class="fa fa-trash"></i>
                                        </button></td>    


                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>

                            <tr>
                                <td colspan="5" align="center">
                                    <strong>No Sender Id</strong>
                                </td>
                            </tr>
    <?php } ?>
                    </tbody>
                </table>

                <!-- Pagination -->
    <?php echo $paging; ?>
            </div>

<?php } ?>
            
            
            


    </div>
</div>
<!--<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>-->
<script>
                            function checkSenderID(username)
                            {
                                $("#error").html('');
                                $("#success").html('');
                                $("#error").addClass('');
                                $("#success").addClass('');
                                /*  if (username.length === 0 && username === "")
                                 {
                                 $("#error").removeClass('hidden');
                                 $("#error").fadeIn('slow').text("Please enter userename!");
                                 return false;
                                 }*/
                                var pattern_username = /^[A-Za-z][A-Za-z0-9]*$/;
                                if (!pattern_username.test(username))
                                {
                                    $("#error").removeClass('');
                                    $("#error").fadeIn('slow').text("");
                                    return false;
                                }
                                if (username.length < 5)
                                {
                                    $("#error").removeClass('');
                                    $("#error").fadeIn('slow').text("");
                                    return false;
                                }
                                var base_url = $('#base_url').val();
                                // alert(base_url);die;
                                if (username.length >= 5)
                                {
                                    //alert('stop');
                                    var dataArray = new Array(1);
                                    dataArray[0] = username;
                                    var ajaxData = {dataArray: JSON.stringify(dataArray)};
                                    $.ajax({'url': "<?php echo base_url(); ?>admin/check_sender_id_availability", 'type': 'POST', 'data': ajaxData, 'success': function (data) {
                                            //   alert(url);die;
                                            if (data) {
                                                $("#error").addClass('');
                                                $("#success").removeClass('');
                                                $("#success").fadeIn('slow').text("");
                                            } else
                                            {
                                                $("#success").addClass('');
                                                $("#error").removeClass('');
                                                $("#error").fadeIn('slow').text("Sorry! Sender ID already exist");
                                                $("input#signup_username").focus();
                                                return false;
                                            }
                                        }});
                                }
                            }

</script>

<script>
                            function checkPrSenderID(check_sender)
                            {
                                $("#error").html('');
                                $("#success").html('');
                                $("#error").addClass('');
                                $("#success").addClass('');
                                /*  if (username.length === 0 && username === "")
                                 {
                                 $("#error").removeClass('hidden');
                                 $("#error").fadeIn('slow').text("Please enter userename!");
                                 return false;
                                 }*/
                                var pattern_username = /^[A-Za-z][A-Za-z0-9]*$/;
                                if (!pattern_username.test(check_sender))
                                {
                                    $("#error").removeClass('');
                                    $("#error").fadeIn('slow').text("");
                                    return false;
                                }
                                if (check_sender.length < 5)
                                {
                                    $("#error").removeClass('');
                                    $("#error").fadeIn('slow').text("");
                                    return false;
                                }
                                var base_url = $('#base_url').val();
                                // alert(base_url);die;
                                if (check_sender.length >= 5)
                                {
                                    //alert('stop');
                                    var dataArray = new Array(1);
                                    dataArray[0] = check_sender;
                                    var ajaxData = {dataArray: JSON.stringify(dataArray)};
                                    $.ajax({'url': "<?php echo base_url(); ?>admin/check_pr_sender_id_availability", 'type': 'POST', 'data': ajaxData, 'success': function (data) {
                                            //   alert(url);die;
                                            if (data) {
                                                $("#error").addClass('');
                                                $("#success").removeClass('');
                                                $("#success").fadeIn('slow').text("");
                                            } else
                                            {
                                                $("#success").addClass('');
                                                $("#error").removeClass('');
                                                $("#error").fadeIn('slow').text("Sorry! Sender ID already exist");
                                                $("input#check_sender").focus();
                                                return false;
                                            }
                                        }});
                                }
                            }

</script>



<!--<script>
    function insertNewSenderID()
    {
        // alert('data');
        // var base_url = $('#base_url').val();
        var formData = $('#insertSenderID').serialize();
        $.ajax({'url': "<?php echo base_url(); ?>admin/insert_new_sender_id", 'type': 'POST', 'data': formData, 'success': function (data) {
                // alert(url);
                if (data)
                {
                    $("#error").addClass('hidden');
                    $("#success").removeClass('hidden');
                    $("#success").fadeIn('slow').text('Sender Id save successfully');
                    $('#insertSenderID').each(function () {
                        this.reset();
                    });
                    $("#success").show().delay(500).fadeOut();


                } else
                {
                    $("#success").addClass('hidden');
                    $("#error").removeClass('hidden');
                    $("#error").fadeIn('slow').text('Sorry! Sender ID already exist');
                    $("#error").show().delay(500).fadeOut();
                    return false;
                }
            }});
    }
</script>-->

<!--<script type="text/javascript">
    $(document).ready(function (e) {
        $('#upload').on('click', function () {
             $('#loading_text').removeClass('hidden');
           var new_route = $("#new_route").val();
                    var file_data = $('#file').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    form_data.append('user_group_id', new_route);
            $.ajax({
                url: '<?php //echo base_url(); ?>admin/import_New_ID', // point to server-side controller method
                dataType: 'text', // what to expect back from the server
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (response) {
                    $('#loading_text').addClass('hidden');
                   //  $("#msg").show().delay(700).fadeOut();
                   $('#msg').html(response); // display success response from the server
                },
                error: function (response) {
                    $('#loading_text').addClass('hidden');
                    $('#msg').html(response); // display error response from the server
                }
            });
        });
    });
</script>-->

<script type="text/javascript">
    $(document).ready(function (e) {
        $('.id_upload').on('click', function () {
            // $('#loading_text').removeClass('hidden');
            var $btn = $('#import_id').button('loading');
           var new_route = $("#new_route").val();
                    var file_data = $('#file').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    form_data.append('user_group_id', new_route);
            $.ajax({
                url: '<?php echo base_url(); ?>admin/import_New_ID', // point to server-side controller method
                dataType: 'text', // what to expect back from the server
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (data) {
                     $('#loading_text').addClass('hidden');
                     var container = $('#sender_ids');
                     $btn.button('reset');
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
                }
               
            });
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function (e) {
        $('.pr_id_upload').on('click', function () {
            // $('#loading_text').removeClass('hidden');
            var $btn = $('#import_pr_id').button('loading');
           var new_route = $("#new_pr_route").val();
                    var file_data = $('#pr_file').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    form_data.append('pr_user_group_id', new_route);
            $.ajax({
                url: '<?php echo base_url(); ?>admin/import_pr_sender_id', // point to server-side controller method
                dataType: 'text', // what to expect back from the server
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (data) {
                     $('#loading_text').addClass('hidden');
                     var container = $('#sender_ids');
                     $btn.button('reset');
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
                }
               
            });
        });
    });
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
<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate());
        $('#from_date').datetimepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });

        $('#to_date').datetimepicker({
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
<script>
    function searchSenderID(search_new_sender_id)
    {
        if (search_new_sender_id === "") {
            $.notify({title: 'Error', text: 'Please Enter Text!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
            $('#pr_route').focus();
            return false;
        } else {

            var dataArray = new Array(1);
            dataArray[0] = search_new_sender_id;
            var ajaxData = {dataArray: JSON.stringify(dataArray)};
            $.ajax({'url': '<?php echo base_url(); ?>admin/search_sender_ids', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
                    var container = $('#show_ids');
                    if (data) {
                        container.html(data);
                    }
                }});
        }
    }
</script>

<script type = "text/javascript" language = "javascript">
    $(document).ready(function () {
        $("#driver").click(function (event) {
            var name = $("#name").val();
            //alert('hii');
            $("#stage").load('<?php base_url(); ?>/index.php/admin/search_not_aproval_sender_id', {"name": name});
        });
    });
</script>

<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>