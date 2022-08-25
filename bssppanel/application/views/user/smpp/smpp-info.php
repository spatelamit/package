<fieldset>
    <legend align="center"><h4>SMPP Information</h4></legend>
    <?php
    $data = array('onsubmit' => "return validateSendMessage()");
    echo form_open_multipart('smpp_user/send_sms', $data);
    ?>
    <table border="0" cellpadding="10" cellspacing="10">
        <tbody>
            <tr>
                <td>
                    <strong>Promotional SMPP Port</strong>
                    <br/>
                    <input type="text" disabled="" name="pr_port" id="pr_port" value="<?php echo $smpp_pr_port; ?>" style="width: 200px;height: 20px;" />
                    <br/>
                </td>
                <td>
                    <strong>Transactional SMPP Port</strong>
                    <br/>
                    <input type="text" disabled="" name="tr_port" id="tr_port" value="<?php echo $smpp_tr_port; ?>" style="width: 200px;height: 20px;" />
                    <br/>
                </td>
            </tr>
        </tbody>
    </table>
</form>
</fieldset>