<?php
if ($result_users) {
    $i = 1;
    foreach ($result_users as $user) {
        ?>
        <div class="col-md-12 padding0">
            <label for="select_user<?php echo $i; ?>" class="fancy-check">
                <input type="checkbox" name="select_user[]" id="select_user<?php echo $i; ?>"
                       value="<?php echo ($tab == '4') ? $user['contact_number'] : $user['email_address']; ?>" />
                <span>
                    <?php echo $user['name'] . " (" . $user['username'] . ")"; ?>
                </span>
            </label>
        </div>
        <?php
        $i++;
    }
} else {
    ?>
    <div class="col-md-12 padding0">
        No User!
    </div>
    <?php
}
?>