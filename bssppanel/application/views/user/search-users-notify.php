<table class="table table-hover bgf">
    <tbody>
        <?php
        if (isset($result_users) && $result_users) {
            $i = 1;
            $check = 0;
            foreach ($result_users as $user) {
                if ($user_id != $user['user_id']) {
                    $check++;
                    ?>
                    <tr>
                        <td>
                            <label for="select_user<?php echo $i; ?>" class="fancy-check">
                                <input type="checkbox" name="select_user[]" id="select_user<?php echo $i; ?>" checked=""
                                       value="<?php echo ($tab == 'by_sms') ? $user['contact_number'] : $user['email_address']; ?>" />
                                <span>
                                    <?php echo $user['name'] . " (" . $user['username'] . ")"; ?>
                                </span>
                            </label>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
            }
            if ($check == 0) {
                ?>
                <tr>
                    <td>No Users!</td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td>No Users!</td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>