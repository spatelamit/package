<?php
if (isset($users) && $users) {
    $i = 1;
    foreach ($users as $user) {
        if ($user['user_id'] != $user_id && $user['ref_user_id'] == $user_id) {
            ?>
            <a href="javascript:void(0);" class="list-group-item" id="select_user<?php echo $i; ?>" 
               onclick="getUserTabs(<?php echo $user['user_id']; ?>, 1)">
                <!--
                <span class="label label-<?php //echo ($user['user_status'] ? "primary" : 'warning');          ?> pull-right">
                <?php //echo ($user['user_status'] ? "Active" : 'Inactive'); ?>
                </span>
                -->
                <h5 class="list-group-item-heading"><?php echo $user['name']; ?></h5>
                <ul class="padding0">
                    <li><i class="fa fa-user"></i> <?php echo $user['username']; ?></li>
                    <li><i class="fa fa-envelope"></i> <?php echo $user['email_address']; ?></li>
                    <li><i class="fa fa-phone"></i> <?php echo $user['contact_number']; ?></li>
                </ul>
            </a>
            <?php
            $i++;
        }
    }
    if ($i == 1) {
        ?>
        <a href="javascript:void(0);" class="list-group-item">
            <h5 class="list-group-item-heading">No user exists!</h5>
        </a>
        <?php
    }
} else {
    ?>
    <a href="javascript:void(0);" class="list-group-item">
        <h5 class="list-group-item-heading">No user exists!</h5>
    </a>
    <?php
}
?>