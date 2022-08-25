<?php
if (isset($active_users) && $active_users && sizeof($active_users)) {
    $i = 1;
    $total = 0;
    foreach ($active_users as $key => $user) {
        $total+=$user['total_consumed'];
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo ($user['name'] && $user['name'] != null) ? $user['name'] : "Admin Messages"; ?></td>
            <td><?php echo ($user['username'] && $user['username'] != null) ? $user['username'] : "Admin"; ?></td>
            <td class="text-right"><?php echo $user['total_consumed']; ?></td>
        </tr>
        <?php
        $i++;
    }
    ?>
    <tr>
        <th colspan="3">Grand Total</th>
        <th class="text-right"><?php echo $total; ?></th>
    </tr>
    <?php
}
?>