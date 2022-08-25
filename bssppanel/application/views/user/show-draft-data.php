<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />

<?php
// Senders


// Drafts
if (isset($field_type) && $field_type == 'drafts') {
    ?>
    <?php
    if (isset($result_message) && $result_message) {
        $signatures = $result_message['signatures'];
        $drafts = $result_message['drafts'];
        $sent = $result_message['sent'];
        ?>
        <div class="row">
            <?php
            if (isset($signatures) && $signatures) {
                $check_signature = $signatures->check_signature;
                $signature = $signatures->signature;
                if ($check_signature) {
                    ?>
                    <div class="col-md-3 padding0">
                        <label for="check_signature" class="fancy-check">
                            <input type="checkbox" checked="" name="check_signature" id="check_signature" onclick="showSignatureField();" />
                            <span>Add Signature</span> 
                        </label>
                    </div>
                    <div class="col-md-4 padding0">
                        <input type="text" name="signature" id="signature" value="<?php echo $signature; ?>" class="form-control" onkeyup="saveSignature();" />
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="col-md-3 padding0">
                        <label for="check_signature" class="fancy-check">
                            <input type="checkbox" name="check_signature" id="check_signature" onclick="showSignatureField();" />
                            <span>Add Signature</span> 
                        </label>
                    </div>
                    <div class="col-md-4 padding0">
                        <input type="text" name="signature" id="signature" value="<?php echo $signature; ?>" class="form-control hidden" onkeyup="saveSignature();" />
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="col-md-3 padding0">
                    <label for="check_signature">
                        <input type="checkbox" name="check_signature" id="check_signature" onclick="showSignatureField();" />
                        <span>Add Signature</span> 
                    </label>                                        
                </div>
                <div class="col-md-4 padding0">
                    <input type="text" name="signature" id="signature" value="" class="form-control" onkeyup="saveSignature();" />
                </div>
                <?php
            }
            ?>
        </div>
        <div class="row">
            <div class="col-md-12 padding0">
                <h4>Drafts</h4>     
            </div>
            <div class="col-md-8 padding0">
                <div class="table-responsive">
                    <table class="table table-hover bgf">
                        <tbody>
                            <?php
                            if (isset($drafts) && $drafts) {
                                foreach ($drafts as $value) {
                                    ?>
                                    <tr>
                                        <td>
                                            <button data-original-title="Delete Draft" onclick="deleteDraftMsg('drafts', '<?php echo $value->draft_message_id; ?>')" type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="">
                                                   <i class="fa fa-trash-o" aria-hidden="true"></i>

 
                                                                </button></td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="pickField('message', '<?php echo $value->draft_message; ?>')">
                                                <?php echo $value->draft_message; ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                if ($sent) {
                                    foreach ($sent as $value) {
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0)" onclick="pickField('message', '<?php echo urldecode($value->message); ?>')">
                                                    <?php echo urldecode($value->message); ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table> 
                </div>       
            </div>
        </div>
        <?php
    }
}
?>
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>