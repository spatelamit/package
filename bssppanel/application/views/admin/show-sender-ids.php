
<table class="table table-hover">
    <thead>
        <tr>
            <th>S.No.</th>
            <th>Username</th>
            <th>Sender Id</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody class="bgf7">
        <?php
        if (isset($user_sender_ids) && $user_sender_ids) {
            $i = 1;
            foreach ($user_sender_ids as $user_sender_id) {
                $sender_id = $user_sender_id['sender_id'];
                $username = $user_sender_id['username'];
                $sender_ids_array = explode(',', $user_sender_id['sender_ids']);
                $sender_status_array = explode(',', $user_sender_id['sender_status']);
                foreach ($sender_ids_array as $sender_key => $sender_value) {
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td>
                            <?php echo $user_sender_id['username']; ?> ( <?php echo ($user_sender_id['parent_username'] == "") ? $user_sender_id['admin_username'] : $user_sender_id['parent_username']; ?> )
                            <?php //echo $username; ?>
                        </td>
                        <td><?php echo $sender_value; ?></td>
                        <td>
                            <?php if ($sender_status_array[$sender_key] == '1') { ?>
                                <span class="text-success">Approved</span>
                            <?php } elseif ($sender_status_array[$sender_key] == '0') { ?>
                                <span class="text-danger">Disapproved</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php
                            if ($sender_status_array[$sender_key] == '1') {
                                ?>
                                <a href="javascript:void(0);" onclick="changeSIdStatus('<?php echo $sender_id; ?>', '<?php echo $sender_key; ?>', 0);" 
                                   class="btn btn-xs btn-inverse">
                                    <i class="fa fa-close"></i>
                                </a>
                                <?php
                            } elseif ($sender_status_array[$sender_key] == '0') {
                                ?>
                                <a href="javascript:void(0);" onclick="changeSIdStatus('<?php echo $sender_id; ?>', '<?php echo $sender_key; ?>', 1);" 
                                   class="btn btn-xs btn-success">
                                    <i class="fa fa-check"></i>
                                </a>
                                <?php
                            }
                            ?>
                            <a href="javascript:void(0);" onclick="deleteSenderId('<?php echo $sender_id; ?>', '<?php echo $sender_key; ?>');" 
                               class="btn btn-xs btn-danger">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
            }
        } else {
            ?>
            <tr>
                <td colspan="5" align="center">
                    <strong>No Sender Id</strong>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>