<fieldset>
    <legend align="center"><h4>My Profile</h4></legend>
    <?php
    $data = array('onsubmit' => "return validateUserProfile()");
    echo form_open_multipart('smpp_user/save_user_profile', $data);
    ?>
    <table border="0" cellpadding="10" cellspacing="10">
        <tbody>
            <tr>
                <td colspan="2">
                    <strong>Username</strong>
                    <br/>
                    <input type="text" disabled="" name="username" id="username" value="<?php echo $username; ?>" style="width: 400px;height: 20px;" />
                    <br/>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Name</strong>
                    <br/>
                    <input type="text" name="name" id="name" value="<?php echo $smpp_user->smpp_user_name; ?>" placeholder="Enter Name" style="width: 200px;height: 20px;" />
                    <br/>
                </td>
                <td>
                    <strong>Email Address</strong>
                    <br/>
                    <input type="text" name="email" id="email" value="<?php echo $smpp_user->smpp_user_email; ?>" placeholder="Enter Email Address" style="width: 200px;height: 20px;" />
                    <br/>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Contact Number</strong>
                    <br/>
                    <input type="text" name="contact" id="contact" value="<?php echo $smpp_user->smpp_user_contact; ?>" placeholder="Enter Contact Number" style="width: 200px;height: 20px;" />
                    <br/>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Creation Date</strong>
                    <br/>
                    <?php echo $smpp_user->creation_date; ?>
                    <br/>
                </td>
                <td>
                    <strong>Last login</strong>
                    <br/>
                    <?php echo $smpp_user->last_login_date; ?>
                    <br/>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2">
                    <br/>
                    <input type="submit" value="Save Profile" />
                    <br/>
                </td>
            </tr>
        </tbody>
    </table>
</form>
</fieldset>