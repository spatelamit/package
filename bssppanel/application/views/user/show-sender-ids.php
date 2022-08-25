<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />
<table class="table table-hover bgf">
    <thead>
        <tr>
            <th>Sender Id</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($sender_id) && $sender_id) {
            $i = 1;
            $sender_id = $sender_id;
            $sender_ids_array = explode(',', $user_sender_ids);
            $sender_status_array = explode(',', $user_sender_status);
            foreach ($sender_ids_array as $sender_key => $sender_value) {
                ?>
                <tr>
                    <td><?php echo $sender_value; ?></td>
                    <td>
                        <?php
                        if ($sender_status_array[$sender_key] == '1')
                            echo "<span class='label label-success'>Approved</span>";
                        elseif ($sender_status_array[$sender_key] == '0')
                            echo "<span class='label label-danger'>Disapproved</span>";
                        ?>
                    </td>
                    <td>
                        <a href="javascript:void(0);" onclick="deleteUserData('sender', <?php echo $sender_key; ?>)"
                           class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="Delete Sender Id">
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </td>
                </tr>
                <?php
                $i++;
            }
        }else {
            ?>
            <tr>
                <td colspan="3">No Record Found!</td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>