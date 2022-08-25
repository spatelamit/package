<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />
<div class="btn-group">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <?php echo (isset($route) && $route == 'A') ? "Promotional" : "Dynamic"; ?> Route <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <li>
            <a href="javascript:void(0)" onclick="changeVoiceRoute('A');"
               data-toggle="tooltip"  data-container="body" data-placement="right" 
               animation="true" delay="100" data-html="true"
               title='<ul class="padding0 text-left">
               <li class="padding0">Default Route</li>
               <li class="padding0">Fixed Caller Id</li>
               <li class="padding0">NDNC Blocked</li>
               <li class="padding0">Normal</li>
               <li class="padding0">Working in between 9:00 AM - 9:00PM</li>
               <li class="padding0">For Promotional SMS</li>
               </ul>'>
                Promotional Route (<?php echo (isset($pr_voice_balance) && $pr_voice_balance) ? $pr_voice_balance : 0; ?>)
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="javascript:void(0)" onclick="changeVoiceRoute('B');"
               data-toggle="tooltip"  data-container="body" data-placement="right" 
               animation="true" delay="100" data-html="true"
               title='<ul class="padding0 text-left">
               <li class="padding0">Dynamic Route</li>
               <li class="padding0">Dynamic Caller-ID</li>
               <li class="padding0">Work on DND and Non DND Both </li>
               <li class="padding0">Working 24*7</li>
               <li class="padding0">Premium</li>
               <li class="padding0">For Informational SMS</li>
               </ul>'>
                Dynamic Route (<?php echo (isset($tr_voice_balance) && $tr_voice_balance) ? $tr_voice_balance : 0; ?>)
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