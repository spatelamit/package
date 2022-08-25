<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />


<div class="col-md-12 col-sm-12 padding0">
    <ul class="list-group" id="show_attach_drafts" style="width: 100%;">

        <?php
        if (isset($result_drafts) && $result_drafts) {
            foreach ($result_drafts as $key => $value) {
                
                ?>

                <li class="list-group-item">
                    
                    <label for="attach_file<?php echo $key; ?>">
                        <input type="radio" value="<?php echo base_url() . "Attechment/" . $value->draft_message . "|" . $value->draft_message; ?>" name="upload_attach_file"
                               id="attach_file<?php echo $key; ?>" />
                        <?php echo $value->draft_message; ?>
                    </label>
                    <button data-original-title="Delete Draft" onclick="deleteDraftAttach('drafts', '<?php echo $value->draft_message_id; ?>')" type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" style="margin-left: 20px;">
                        <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                </li>

                <?php
            }
        }
        ?>
    </ul>
</div>
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>