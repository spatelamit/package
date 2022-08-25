<div class="page-content-title txt-center">
    <h3><i class="fa fa-users"></i> User Group Management</h3> 
</div>

<div class="table-responsive padding15">

    <div class="row">
        <div class="col-md-12 col-sm-12 padding15">
            <a href="<?php echo base_url(); ?>admin/add_user_group" class="btn btn-primary pull-right">Add New</a>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>S.No.</th>
                <th>User Group</th>
                <th>Connected SMSC</th>
                <th>Purpose</th>
                <th>Sender Id Type</th>
                <th>Sender Id Length</th>
                <th>Default</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="bgf7">
            <?php
            if ($user_groups) {
                $i = 1;
                foreach ($user_groups as $row) {
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row['user_group_name']; ?></td>
                        <td><?php echo $row['smsc_id']; ?></td>
                        <td><?php echo $row['purpose']; ?></td>
                        <td><?php echo $row['sender_id_type']; ?></td>
                        <td><?php echo $row['sender_id_length']; ?></td>
                        <td>
                            <?php
                            if ($row['default_user_group'] == 1) {
                                ?>
                                <span class="text-success">Default</span>
                                <?php
                            } elseif ($row['default_user_group'] == 0) {
                                ?>
                                <span class="text-danger">Not Default</span>
                                <?php
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($row['user_group_status'] == 1) {
                                ?>
                                <span class="text-success">Activated</span>
                                <?php
                            } elseif ($row['user_group_status'] == 0) {
                                ?>
                                <span class="text-danger">Deactivated</span>
                                <?php
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo base_url(); ?>admin/set_default/<?php echo $row['user_group_id']; ?>" class="btn btn-sm btn-info">
                                Set Default
                            </a>
                            <?php
                            if ($row['user_group_status'] == 1) {
                                ?>
                                <a href="<?php echo base_url(); ?>admin/enable_disable_ugroup/<?php echo $row['user_group_id']; ?>/0" class="btn btn-sm btn-inverse">
                                    Disable
                                </a>
                                <?php
                            } elseif ($row['user_group_status'] == 0) {
                                ?>
                                <a href="<?php echo base_url(); ?>admin/enable_disable_ugroup/<?php echo $row['user_group_id']; ?>/1" class="btn btn-sm btn-success">
                                    Enable
                                </a>
                                <?php
                            }
                            ?>                               
                            <a href="<?php echo base_url(); ?>admin/update_user_group/<?php echo $row['user_group_id']; ?>" class="btn btn-sm btn-primary">
                                Update
                            </a>
                            <a href="<?php echo base_url(); ?>admin/delete_user_group/<?php echo $row['user_group_id']; ?>" class="btn btn-sm btn-danger">
                                Delete
                            </a>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
            } else {
                ?>
                <tr>
                    <td colspan="10" align="center">
                        <strong>No User Groups</strong>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

    <nav class="pull-right">
        <ul class="pagination radius0">
            <?php echo $pagination_helper; ?>
        </ul>
    </nav>

</div>