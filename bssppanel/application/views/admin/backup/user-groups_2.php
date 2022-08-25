<div class="page-content-title txt-center">
    <h3><i class="fa fa-users"></i> User Group Management</h3> 
</div>

<div id="settings">

    <div class="horizontal-tabs tab-color padding15" id="user_groups">
        
        <ul class="nav nav-tabs" role="tablist">
            <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
                <a href="javascript:void(0)" onclick="getUserGroupTab('1');">Promotional User Groups</a>
            </li>
            <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
                <a href="javascript:void(0)" onclick="getUserGroupTab('2');">Transactional User Groups</a>
            </li>
            <li class="<?php echo (isset($subtab) && $subtab == "3") ? 'active"' : '' ?>">
                <a href="javascript:void(0)" onclick="getUserGroupTab('3');">Add User Group</a>
            </li>
        </ul>
        <div class="panel-group panel-color visible-xs"></div>

        <div class="tab-content bgf9">
            <div class="tab-pane fade in active">

                <?php if ($subtab == '1') { ?>
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Keyword</th>
                                    <th>Matching Ratio (User) (%)</th>
                                    <th>Matching Ratio (All Users) (%)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($approve_keywords) {
                                    $i = 1;
                                    foreach ($approve_keywords as $keyword) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $keyword['username']; ?> ( <?php echo ($keyword['parent_username'] == "") ? $keyword['admin_username'] : $keyword['parent_username']; ?> )
                                                <?php //echo $username; ?>
                                            </td>
                                            <td>
                                                <?php echo mysql_real_escape_string($keyword['keywords']); ?>
                                            </td>
                                            <td>
                                                <?php echo $keyword['percent_ratio_user']; ?>
                                            </td>
                                            <td>
                                                <?php echo $keyword['percent_ratio_all_users']; ?>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="deleteKeyword('approved', '<?php echo $subtab; ?>', '<?php echo $keyword['keyword_id']; ?>');" class="btn btn-sm btn-danger">
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
                                        <td colspan="5">No Approved Keywords!.</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                <?php } ?>

            </div>
        </div>

    </div>

</div>

<div class="horizontal-tabs tab-color padding15">
    <div class="row">
        <div class="col-md-12 col-sm-12 padding15">
            <a href="<?php echo base_url(); ?>admin/add_user_group" class="btn btn-primary pull-right">Add New</a>
        </div>
    </div>
    <ul class="nav nav-tabs" role="tablist">
        <li class="<?php echo (isset($subtab) && $subtab == "Promotional") ? 'active' : '' ?>">
            <a href="<?php echo base_url(); ?>admin/user_groups/Promotional">Promotional User Groups</a>
        </li>
        <li class="<?php echo (isset($subtab) && $subtab == "Transactional") ? 'active' : '' ?>">
            <a href="<?php echo base_url(); ?>admin/user_groups/Transactional">Transactional User Groups</a>
        </li>
    </ul>
    <div class="panel-group panel-color visible-xs"></div>

    <div class="tab-content bgf9">
        <div class="tab-pane fade in active">
            <?php if ($subtab == 'Promotional') { ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>User Group</th>
                                <th>Connected SMSC</th>
                                <th>Sender Id Type</th>
                                <th>Sender Id Length</th>
                                <th>Status</th>
                                <th colspan="4">Action</th>
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
                                        <td><?php echo $row['sender_id_type']; ?></td>
                                        <td><?php echo $row['sender_id_length']; ?></td>
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
                                            <?php
                                            if ($row['default_user_group'] == 0) {
                                                ?>
                                                <a href="<?php echo base_url(); ?>admin/set_default/<?php echo $row['user_group_id']; ?>/<?php echo $subtab; ?>/1" class="btn btn-sm btn-info">
                                                    Set Default
                                                </a>
                                                <?php
                                            } elseif ($row['default_user_group'] == 1) {
                                                ?>
                                                <a href="<?php echo base_url(); ?>admin/set_default/<?php echo $row['user_group_id']; ?>/<?php echo $subtab; ?>/1" class="btn btn-sm btn-success">
                                                    Default
                                                </a>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['user_group_status'] == 1) {
                                                ?>
                                                <a href="<?php echo base_url(); ?>admin/enable_disable_ugroup/<?php echo $row['user_group_id']; ?>/<?php echo $subtab; ?>/0" class="btn btn-sm btn-inverse">
                                                    Disable
                                                </a>
                                                <?php
                                            } elseif ($row['user_group_status'] == 0) {
                                                ?>
                                                <a href="<?php echo base_url(); ?>admin/enable_disable_ugroup/<?php echo $row['user_group_id']; ?>/<?php echo $subtab; ?>/1" class="btn btn-sm btn-success">
                                                    Enable
                                                </a>
                                                <?php
                                            }
                                            ?>    
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url(); ?>admin/update_user_group/<?php echo $row['user_group_id']; ?>/<?php echo $subtab; ?>" class="btn btn-sm btn-primary">
                                                Update
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url(); ?>admin/delete_user_group/<?php echo $row['user_group_id']; ?>/<?php echo $subtab; ?>" class="btn btn-sm btn-danger">
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
                                    <td colspan="11" align="center">
                                        <strong>No User Groups</strong>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <!--
                    <nav class="pull-right">
                        <ul class="pagination radius0">
                    <?php //echo $pagination_helper; ?>
                        </ul>
                    </nav>
                    -->
                </div>
            <?php } ?>

            <?php if ($subtab == 'Transactional') { ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>User Group</th>
                                <th>Connected SMSC</th>
                                <th>Sender Id Type</th>
                                <th>Sender Id Length</th>
                                <th>Status</th>
                                <th colspan="4">Action</th>
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
                                        <td><?php echo $row['sender_id_type']; ?></td>
                                        <td><?php echo $row['sender_id_length']; ?></td>
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
                                            <?php
                                            if ($row['default_user_group'] == 0) {
                                                ?>
                                                <a href="<?php echo base_url(); ?>admin/set_default/<?php echo $row['user_group_id']; ?>/<?php echo $subtab; ?>/1" class="btn btn-sm btn-info">
                                                    Set Default
                                                </a>
                                                <?php
                                            } elseif ($row['default_user_group'] == 1) {
                                                ?>
                                                <a href="<?php echo base_url(); ?>admin/set_default/<?php echo $row['user_group_id']; ?>/<?php echo $subtab; ?>/1" class="btn btn-sm btn-success">
                                                    Default
                                                </a>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['user_group_status'] == 1) {
                                                ?>
                                                <a href="<?php echo base_url(); ?>admin/enable_disable_ugroup/<?php echo $row['user_group_id']; ?>/<?php echo $subtab; ?>/0" class="btn btn-sm btn-warning">
                                                    Disable
                                                </a>
                                                <?php
                                            } elseif ($row['user_group_status'] == 0) {
                                                ?>
                                                <a href="<?php echo base_url(); ?>admin/enable_disable_ugroup/<?php echo $row['user_group_id']; ?>/<?php echo $subtab; ?>/1" class="btn btn-sm btn-success">
                                                    Enable
                                                </a>
                                                <?php
                                            }
                                            ?>   
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url(); ?>admin/update_user_group/<?php echo $row['user_group_id']; ?>/<?php echo $subtab; ?>" class="btn btn-sm btn-primary">
                                                Update
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url(); ?>admin/delete_user_group/<?php echo $row['user_group_id']; ?>/<?php echo $subtab; ?>" class="btn btn-sm btn-danger">
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
                                    <td colspan="11" align="center">
                                        <strong>No User Groups</strong>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <!--
                    <nav class="pull-right">
                        <ul class="pagination radius0">
                    <?php //echo $pagination_helper; ?>
                        </ul>
                    </nav>
                    -->
                </div>
            <?php } ?>

        </div>
    </div>
</div>
</div>