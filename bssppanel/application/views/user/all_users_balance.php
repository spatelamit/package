
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="portlet">
                
                
                
                <div class="row">
                    <div class="col-sm-12">
                         <form role="form" class="tab-forms" id="searchExistBalance" method='post' action="javascript:searchExistBalance();">
                        
                 <div class="col-md-2">
                        <div class="form-group">
                            <label>Select Route</label>
                            <select class="form-control" name="select_route" id="" required="required">
                                <option value="" selected="">Select Route</option>
                              
                                       
                                            <option value="A">
                                                PR SMS
                                            </option>
                                              <option value="B">
                                                TR SMS
                                          </option>
                                           <option value="C">
                                               STOCK SMS
                                          </option>
                                           <option value="D">
                                               Premium SMS
                                          </option>
                            </select>
                        </div>
                    </div>
                <div class="col-md-2">
                        <div class="form-group">
                            <label>Balance</label>
                            <div class="input-group">
                                <input type="text" name="exist_balance" value="" id="" placeholder="Enter Amount" class="form-control" required="required"/>
                                
                            </div>
                        </div>
                    </div>
                        <div class="col-md-2" style="margin-top: 27px;">
                          
                            <button type="submit" class="btn btn-primary btn-xs" id="search" data-loading-text="Processing..." autocomplete="off" style="width: 85px; height: 33px; margin-left: -60px;" >Search</button>           
                         
                    </div>
                         </form>    
                        <div class="col-md-2 col-sm-3 col-xs-12 pull-right" style="margin-top: 19px;">
                                <div class="input-group um-search">
                                    <input class="form-control" placeholder="Search User Name" type="text" onkeyup="searchAllBalance(this.value);">
                                    <span class="input-group-addon">
                                        <i class="fa fa-search"></i>
                                    </span>
                                </div>
                            </div>
                    </div>
                    
            </div>
                
                
                
                
                <div class="row">
                <h2 class="content-header-title tbl">All Users Existing Balance</h2>
                <h2 class="content-header-title tbl" style="float:  right;margin-top: -55px;">Total Users :  <?php echo $total_users;  ?></h2>
                </div>
                <div class="portlet-content">
                    <div class="table-responsive">
                        <table class="table table-bordered bgf" id="exist_balance">
                            <thead>
                                <tr style="background-color: darkgrey;">
                                    <th>Sr. No</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                      <th>Email</th>
                                    <th>Contact</th>
                                     <th>SignUp Date</th>
                                   <th>PR SMS</th>
                                    <th>TR SMS</th>
                                     <th>STOCK SMS</th>
                                    <th>Premium SMS</th>
                                    <th>PR Voice</th>
                                    <th>TR voice</th>
                                    <th>Short Code</th>
                                    <th>Long Code</th>
                                    <th>User Status</th>
                                    

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($users_balance) && $users_balance) {
                                    $i = 1;
                                    foreach ($users_balance as $row) {
                                        ?>
                                        <tr  <?php if ($i % 2 == 0) { ?>
                                                style="background-color: lightgray;"  
                                            <?php } else { ?>
                                                style="background-color: ghostwhite"  

                                            <?php } ?>>
                                             <td><?php echo $i; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['username']; ?></td>
                                            <td><?php echo $row['email_address']; ?></td>
                                             <td><?php echo $row['contact_number']; ?></td>
                                                <td><?php echo $row['creation_date']; ?></td>
                                             <td><?php echo $row['pr_sms_balance']; ?></td>
                                             <td><?php echo $row['tr_sms_balance']; ?></td>
                                             <td><?php echo $row['stock_balance']; ?></td>
                                             <td><?php echo $row['prtodnd_balance']; ?></td>
                                              <td><?php echo $row['pr_voice_balance']; ?></td>
                                               <td><?php echo $row['tr_voice_balance']; ?></td>
                                                <td><?php echo $row['short_code_balance']; ?></td>
                                                 <td><?php echo $row['long_code_balance']; ?></td>
                                                 <?php if($row['user_status']==1){ ?>
                                                 <td><button style="background-color: #7FB338;font-weight: bold; color: white;">Open</button></td>
                                              <?php  } ?>
                                                 <?php if($row['user_status']==0){ ?>
                                                 <td><button style="background-color: #cc3f44;font-weight: bold; color: white;">Block</button></td>
                                              <?php  } ?>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="20" align="center">
                                            <strong><b>No User Exist!</b></strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td align="center" colspan="20">
                                        <ul class="pagination margin0">
                                            <?php echo $pagination_helper; ?>
                                        </ul>
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div> 
            </div> 
        </div>
    </div>
</div>