<fieldset>
    <legend align="center"><h4>Add New User</h4></legend>
    <?php
    $data = array('onsubmit' => "return validateUser()");
    echo form_open('admin/save_user', $data);
    ?>
    <br/>
    <table border="0" cellpadding="10" cellspacing="10">
        <tbody>
            <tr>
                <td>
                    <strong>Name</strong>
                    <br/>
                    <input type="text" name="name" id="name" placeholder="Name" style="width: 200px;height: 20px;" />
                    <br/>
                </td>
                <td>
                    <strong>Email Address</strong>
                    <br/>
                    <input type="text" name="email_address" id="email_address" placeholder="Email Addess" style="width: 200px;height: 20px;" />
                    <br/>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Username</strong>
                    <br/>
                    <input type="text" name="username" id="username" placeholder="Username" onkeyup="getUsername(this.value);" style="width: 200px;height: 20px;" />
                    <br/>
                </td>
                <td>
                    <strong>Contact Number</strong>
                    <br/>
                    <input type="text" name="contact" id="contact" placeholder="Contact Number" style="width: 200px;height: 20px;" />
                    <br/>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>User Type</strong>
                    <br/>
                    <select name="user_type" id="user_type" style="width: 200px;height: 20px;">
                        <option value="-1">Select User Type</option>
                        <option value="1">Reseller</option>
                        <option value="2">User</option>
                    </select>
                    <br/>
                </td>
                <td>
                    <strong>Unique Number Limit</strong>
                    <br/>
                    <input type="text" name="number_allowed" id="number_allowed" style="width: 200px;height: 20px;" placeholder="Number Allowed" value="<?php echo $setting['sms_limit']; ?>" />
                    <br/>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <br/>
                    <input type="submit" value="Save User" name="save" id="save" />
                    <br/>
                </td>
                <td align="center">
                    <br/>
                    <input type="reset" value="Reset" />
                    <br/>
                </td>
            </tr>
        </tbody>
    </table>
</form>
</fieldset>