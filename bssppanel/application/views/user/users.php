</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 padding0">
            <div class="row um-upperdiv">
                <div class="col-md-2 padding0">
                    <div class="input-group um-search">
                        <input type="text" class="form-control" placeholder="Search user" aria-describedby="basic-addon2"
                               onkeyup="searchUser(this.value);" />
                        <span class="input-group-addon" id="basic-addon2">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </div>
                <div class="col-md-10">
                    <ul class="list-inline">
                        <!--
                        <li>
                            <div class="total-u actv">
                                <i class="fa fa-users"></i>
                            </div>
                            <h4 class="tu-head">Active : 
                                <span class="digit"><?php echo (isset($users_info) && $users_info->total_users) ? $users_info->total_users : 0; ?>
                                </span>
                            </h4>  
                        </li>
                        <li>
                            <div class="total-u inactv">
                                <i class="fa fa-users"></i>
                            </div>
                            <h4 class="tu-head">Inactive : 
                                <span class="digit">0<?php //echo $users_info->total_users;             ?></span>
                            </h4>
                        </li>
                        -->
                        <li>
                            <div class="total-u prom">
                                <i class="fa fa-envelope-o"></i>
                            </div>
                            <h4 class="tu-head">Pr : 
                                <span class="digit"><?php echo (isset($users_info) && $users_info->total_pr_balance) ? $users_info->total_pr_balance : 0; ?></span>
                            </h4>
                        </li>
                        <li>
                            <div class="total-u prom">
                                <i class="fa fa-envelope-o"></i>
                            </div>
                            <h4 class="tu-head">Stock : 
                                <span class="digit"><?php echo (isset($users_info) && $users_info->total_stock_balance) ? $users_info->total_stock_balance : 0; ?></span>
                            </h4>
                        </li>
                        <li>
                            <div class="total-u prom">
                                <i class="fa fa-envelope-o"></i>
                            </div>
                            <h4 class="tu-head">Premium : 
                                <span class="digit"><?php echo (isset($users_info) && $users_info->total_prtodnd_balance) ? $users_info->total_prtodnd_balance : 0; ?></span>
                            </h4>
                        </li>
                        <li>
                            <div class="total-u trans">
                                <i class="fa fa-envelope-o"></i>
                            </div>
                            <h4 class="tu-head">Tr : 
                                <span class="digit"><?php echo (isset($users_info) && $users_info->total_tr_balance) ? $users_info->total_tr_balance : 0; ?></span>
                            </h4>
                        </li>
                        <li>
                            <div class="total-u prom">
                                <i class="fa fa-sort-numeric-desc "></i>
                            </div>
                            <h4 class="tu-head">Long : 
                                <span class="digit"><?php echo (isset($users_info) && $users_info->long_code_balance) ? $users_info->long_code_balance : 0; ?></span>
                            </h4>
                        </li>
                        <li>
                            <div class="total-u trans">
                                <i class="fa fa-sort-numeric-desc "></i>
                            </div>
                            <h4 class="tu-head">Short : 
                                <span class="digit"><?php echo (isset($users_info) && $users_info->short_code_balance) ? $users_info->short_code_balance : 0; ?></span>
                            </h4>
                        </li>
                        <li>
                            <div class="total-u prom">
                                <i class="fa fa-volume-up "></i>
                            </div>
                            <h4 class="tu-head">VPR : 
                                <span class="digit"><?php echo (isset($users_info) && $users_info->total_pr_voice_balance) ? $users_info->total_pr_voice_balance : 0; ?></span>
                            </h4>
                        </li>
                        <li>
                            <div class="total-u trans">
                                <i class="fa fa-volume-up "></i>
                            </div>
                            <h4 class="tu-head">VD : 
                                <span class="digit"><?php echo (isset($users_info) && $users_info->total_tr_voice_balance) ? $users_info->total_tr_voice_balance : 0; ?></span>
                           
                            </h4>
                        </li>
                        <li class="pull-right">
                            <a href="javascript:void(0);" onclick="getUserTabs(0, 0)" class="btn btn-default"><i class="fa fa-plus"></i> Add New User</a> 
                        </li>
                    </ul>
                </div>      
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 padding0">
                <div class="list-group"  id="show_users" style="overflow: scroll;" >
                    <?php
                    if (isset($users) && $users) {
                    
                        $i = 1;
                        foreach ($users as $user) {
                            ?>
                    
                            <a href="javascript:void(0);" class="list-group-item" id="select_user<?php echo $i; ?>" 
                               onclick="getUserTabs(<?php echo $user['user_id']; ?>, 1)">
                                <h5 class="list-group-item-heading"><?php echo $user['name']; ?></h5>
                                <ul class="padding0">
                                    <li><i class="fa fa-user"></i> <?php echo $user['username']; ?></li>
                                    <li><i class="fa fa-envelope"></i> <?php echo $user['email_address']; ?></li>
                                    <li><i class="fa fa-phone"></i> <?php echo $user['contact_number']; ?></li>
                                    <li><span style="font-weight: bold;">PR : </span><span> <?php echo $user['pr_sms_balance']; ?>  </span> <span style="font-weight: bold;">&nbsp; Stock :</span><span> <?php echo $user['stock_balance'];?></span></li>
                                    <li><span style="font-weight: bold;">TR :</span><span> <?php echo $user['tr_sms_balance']; ?>   </span> <span style="font-weight: bold;">&nbsp; Premium :</span><span> <?php echo $user['prtodnd_balance'];?></span></li>
                                </ul>
                            </a>
                            <?php
                            $i++;
                        } if ($i == 1) {
                            ?>
                            <a href="javascript:void(0);" class="list-group-item">
                                <h5 class="list-group-item-heading">No user exists!</h5>
                            </a>
                            <?php
                        }
                    } else {
                        ?>
                        <a href="javascript:void(0);" class="list-group-item">
                            <h5 class="list-group-item-heading">No users exists!</h5>
                        </a>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-10 um-widget" id="show_users_tab"></div>            
        </div>
    </div>
</div>
<script>
$(function(){
    $('#show_users').slimScroll({
        height: '550px'
    });
});
</script>