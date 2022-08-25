<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />
<?php
if (isset($result_drafts) && $result_drafts) {
    foreach ($result_drafts as $key => $value) {
        ?>
        <li class="list-group-item">
            <label for="voice_draft<?php echo $key; ?>">
                <input type="radio" value="<?php echo base_url() . "Voice/" . $value->draft_message . "|" . $value->draft_message; ?>" name="voice_draft_file"
                       id="voice_draft<?php echo $key; ?>" />
                       <?php echo $value->draft_message; ?>
            </label>
            <button data-original-title="Delete Draft" onclick="deleteDraftVoice('drafts', '<?php echo $value->draft_message_id; ?>')" type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" style="margin-left: 20px;">
                        <i class="fa fa-trash-o" aria-hidden="true"></i></button>
        </li>
        <?php
    }
} else {
    ?>
    <li class="list-group-item">None</li>
    <?php
}
?>