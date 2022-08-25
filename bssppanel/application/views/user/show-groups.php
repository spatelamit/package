<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />
<table class="table table-hover bgf">
    <thead>
        <tr>
            <th>Group Name</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($contact_groups) && $contact_groups) {
            foreach ($contact_groups as $group) {
                ?>
                <tr>
                    <td>
                        <a href="javascript:void(0);"
                           onclick="getGroupContacts(<?php echo $group['contact_group_id']; ?>, '<?php echo $group['contact_group_name']; ?>', 1, 30);">
                               <?php echo $group['contact_group_name']; ?>
                        </a>
                    </td>
                    <td class="text-right"><?php echo $group['total_contacts']; ?></td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td align=center colspan=2>No Group Exists!</td></tr>";
        }
        ?>
    </tbody>
</table>
