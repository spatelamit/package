<select name="user_group" id="user_group" class="form-control" required="" data-parsley-error-message="Please Select User Group">
    <option value="-1">Select User Group</option>
    <?php
    if (isset($user_groups) && $user_groups) {
        foreach ($user_groups as $user_group) {
            ?>
            <option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['user_group_name'] . " [" . $user_group['smsc_id'] . "]"; ?></option>
            <?php
        }
    }
    ?>
</select>