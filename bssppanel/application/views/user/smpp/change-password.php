<fieldset>
    <legend align="center"><h4>Change Password</h4></legend>
    <?php
    $data = array('onsubmit' => "return validateChangePassword()");
    echo form_open('smpp_user/save_password', $data);
    ?>
    <table border="0" width="25%" style="text-align: left" cellpadding="10" cellspacing="10">
        <tbody>
            <tr>
                <td>
                    <strong>Current Password</strong><br/>
                    <input type="password" name="current_password" id="current_password" placeholder="Enter Current Password" value="" style="width: 300px;height: 20px;" />
                </td>
            </tr>
            <tr>
                <td>
                    <strong>New Password</strong><br/>
                    <input type="password" name="new_password" id="new_password" placeholder="Enter New Password" value="" style="width: 300px;height: 20px;" />
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Confirm New Password</strong><br/>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Enter Confirm Password" value="" style="width: 300px;height: 20px;" />
                </td>
            </tr>
            <tr>
                <td align="center">
                    <br/>
                    <input type="submit" value="Change Password" />
                </td>
            </tr>
        </tbody>
    </table>
</form>
</fieldset>