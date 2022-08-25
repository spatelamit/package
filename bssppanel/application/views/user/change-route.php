<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />
<div class="btn-group">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <?php 
        if($route == "A"){
            echo "Promotional";
        }else if($route == "D"){
             echo "Premium Promotion";
        }else  if($route == "C"){
            echo "Stock Promotion";
        }else if($route == "B"){
             echo "Transactional";
        }
        
        ?> Route <span class="caret"></span>
        
       
    </button>
    <ul class="dropdown-menu" role="menu">
        <li>
            <a href="javascript:void(0)" onclick="changeRoute('A');" data-toggle="tooltip"  data-container="body" data-placement="right" 
               animation="true" delay="100" data-html="true"
               title='<ul class="padding0 text-left">
               <li class="padding0">Default Route</li>
               <li class="padding0">NDNC Restricted</li>
               <li class="padding0">Normal</li>
               <li class="padding0">For Promotional SMS</li>
               </ul>'>
               Promotional  Route (<?php echo (isset($pr_sms_balance) && $pr_sms_balance) ? $pr_sms_balance : 0; ?>)
            </a>
        </li>
        <li class="divider"></li>
           <li>
            <a href="javascript:void(0)" onclick="changeRoute('D');" data-toggle="tooltip"  data-container="body" data-placement="right" 
               animation="true" delay="100" data-html="true"
               title='<ul class="padding0 text-left">
               <li class="padding0">Default Route</li>
               <li class="padding0">NDNC Restricted</li>
               <li class="padding0">Normal</li>
               <li class="padding0">For Promotional SMS</li>
               </ul>'>
                Premium Promotion (<?php echo (isset($prtodnd_balance) && $prtodnd_balance) ? $prtodnd_balance : 0; ?>)
            </a>
        </li>
        <li class="divider"></li>
           <li>
            <a href="javascript:void(0)" onclick="changeRoute('C');" data-toggle="tooltip"  data-container="body" data-placement="right" 
               animation="true" delay="100" data-html="true"
               title='<ul class="padding0 text-left">
               <li class="padding0">Default Route</li>
               <li class="padding0">NDNC Restricted</li>
               <li class="padding0">Normal</li>
               <li class="padding0">For Promotional SMS</li>
               </ul>'>
               Stock Promotion (<?php echo (isset($stock_balance) && $stock_balance) ? $stock_balance : 0; ?>)
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="javascript:void(0)" onclick="changeRoute('B');" data-toggle="tooltip"  data-container="body" data-placement="right" 
               animation="true" delay="100" data-html="true"
               title='<ul class="padding0 text-left">
               <li class="padding0">Template Route</li>
               <li class="padding0">NDNC Allowed</li>
               <li class="padding0">Premium</li>
               <li class="padding0">For Informational SMS</li>
               </ul>'>
                Transactional Route (<?php echo (isset($tr_sms_balance) && $tr_sms_balance) ? $tr_sms_balance : 0; ?>)
            </a>
        </li>
    </ul>
    <input type="hidden" name="route" value="<?php echo (isset($route) && $route) ? $route : ""; ?>" />
</div>
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>