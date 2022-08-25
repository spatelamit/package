<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />
<?php
if (isset($mc_auto_reply) && $mc_auto_reply) {
    ?>
    <ul class="padding0">
        <li class="padding0">
            <h5>[<strong><?php echo $mc_auto_reply['auto_reply_sender']; ?></strong>]</h5>
            <p><?php echo $mc_auto_reply['auto_reply_message']; ?></p>
        </li>
        <?php
        ?>
    </ul>
    <?php
}
?>