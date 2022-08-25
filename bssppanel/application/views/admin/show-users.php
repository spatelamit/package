<?php
if (isset($type) && $type) {
    if ($type == 'user') {
        ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Parent</th>
                    <th>Reseller</th>
                    <th>Name</th>
                    <th>Contact Number</th>
                    <th>Email Address</th>
                    <th>User Type</th>
                    <th>Date</th>
                    <th>Account Type</th>
                </tr>
            </thead>
            <tbody class="bgf7">
                <?php
                if (isset($users) && $users) {
                    foreach ($users as $row) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $row['username']; ?>
                            </td>
                            <td><?php echo ($row['parent_username'] == "") ? $row['admin_username'] : $row['parent_username']; ?></td>
                            <td><?php echo ($row['ref_username'] == "") ? "-" : $row['ref_username']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['contact_number']; ?></td>
                            <td><?php echo $row['email_address']; ?></td>
                            <td>
                                <?php if ($row['utype'] == 'Reseller') { ?>
                                    <span class="label label-success">Reseller</span>
                                <?php } elseif ($row['utype'] == 'User') { ?>
                                    <span class="label label-warning">User</span>
                                <?php } ?>
                            </td>
                            <td><?php echo $row['creation_date']; ?></td>
                            <td>
                                <?php if ($row['check_demo_user']) { ?>
                                    <span class="label label-success">Demo</span>
                                <?php } else { ?>
                                    <span class="label label-danger">Active</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="9" align="center">
                            <strong>No Users</strong>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
    }

    if (isset($type) && $type == 'admin') {
        ?>
        <span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
        <span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th rowspan="2">Name</th>
                    <th rowspan="2">Username</th>
                    <th rowspan="2">Contact Number</th>
                    <th rowspan="2">Email Address</th>
                    <th rowspan="2">Role</th>
                    <th class="text-center" colspan="2">Balance</th>
                    <th rowspan="2">Status</th>
                    <th rowspan="2" colspan="3">Action</th>
                </tr>
                <tr>
                    <th>PR Balance</th>
                    <th>TR Balance</th>
                </tr>
            </thead>
            <tbody class="bgf7">
                <?php
                if (isset($account_managers) && $account_managers) {
                    $i = 1;
                    foreach ($account_managers as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row['admin_name']; ?></td>
                            <td><?php echo $row['admin_username']; ?></td>
                            <td><?php echo $row['admin_contact']; ?></td>
                            <td><?php echo $row['admin_email']; ?></td>
                            <td>
                                <?php if ($row['atype'] == 1) { ?>
                                    Administrator
                                <?php } elseif ($row['atype'] == 2) { ?>
                                    Sub-Administrator
                                <?php } ?>
                            </td>
                            <td><?php echo $row['total_pr_balance']; ?></td>
                            <td><?php echo $row['total_tr_balance']; ?></td>
                            <td>
                                <?php if ($row['admin_status']) { ?>
                                    <span class="label label-success">Enabled</span>
                                <?php } else { ?>
                                    <span class="label label-danger">Disabled</span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php
                                if ($row['admin_id'] != $admin_id) {
                                    if ($atype == 1) {
                                        ?>
                                        <div class="switch switch-success switch-sm round switch-inline">
                                            <?php
                                            if ($row['admin_status']) {
                                                ?>
                                                <input type="checkbox" id="status<?php echo $i; ?>" checked
                                                       onclick="changeAMStatus('<?php echo $row['admin_id']; ?>', 0);" />
                                                       <?php
                                                   } else {
                                                       ?>
                                                <input type="checkbox" id="status<?php echo $i; ?>" 
                                                       onclick="changeAMStatus('<?php echo $row['admin_id']; ?>', 1);" />
                                                       <?php
                                                   }
                                                   ?>
                                            <label for="status<?php echo $i; ?>"></label>
                                        </div>
                                        <?php
                                    } else {
                                        echo "-";
                                    }
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                            <td>
                                <button onclick="updateAManager('<?php echo $row['admin_id']; ?>');"
                                        type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </td>
                            <td>
                                <?php
                                if ($row['admin_id'] != $admin_id) {
                                    if ($atype == 1) {
                                        ?>
                                        <button onclick="deleteAManager('<?php echo $row['admin_id']; ?>');"
                                                type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <?php
                                    } else {
                                        echo "-";
                                    }
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="11" align="center">
                            <strong>No Account Manager</strong>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
    }
}
?>
<!-- Pagination -->
<?php echo $paging; ?>

<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>