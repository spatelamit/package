<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSenderTab('1');">Approved Sender Ids</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSenderTab('2');">Pending Sender Ids</a>
    </li>
</ul>

<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active">

        <?php if ($subtab == '1') { ?>
            <div class="row padding15">
                <div class="col-md-12 text-right">
                    <a href="javascript:void(0);" class="btn btn-info btn-sm" id="export_senders" onclick="exportSenderIds();">Export</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Sender Id</th>
                                    <th>Status</th>
                                    <th colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($user_sender_ids) {
                                    $i = 1;
                                    foreach ($user_sender_ids as $user_sender_id) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $user_sender_id['username']; ?> ( <?php echo $user_sender_id['parent_username']; ?> )
                                            </td>
                                            <td><?php echo $user_sender_id['sender']; ?></td>
                                            <td>
                                                <?php if ($user_sender_id['sender_status'] == '1') { ?>
                                                    <span class="label label-success">Approved</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($user_sender_id['sender_status'] == '1') {
                                                    ?>
                                                    <button onclick="changeSIdStatus('<?php echo $user_sender_id['sender_id']; ?>', '<?php echo $user_sender_id['sender_key']; ?>', 0, '<?php echo $subtab; ?>');"
                                                            type="button" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Disapprove Sender Id">
                                                        <i class="fa fa-ban"></i>
                                                    </button>
                                                    <?php
                                                } elseif ($user_sender_id['sender_status'] == '0') {
                                                    ?>
                                                    <button onclick="changeSIdStatus('<?php echo $user_sender_id['sender_id']; ?>', '<?php echo $user_sender_id['sender_key']; ?>', 1, '<?php echo $subtab; ?>');"
                                                            type="button" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Approve Sender Id">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <button onclick="deleteSenderId('<?php echo $user_sender_id['sender_id']; ?>', '<?php echo $user_sender_id['sender_key']; ?>', '<?php echo $subtab; ?>');"
                                                        type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Sender Id">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
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

                        <!-- Pagination -->
                        <?php echo $paging; ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ($subtab == '2') { ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Sender Id</th>
                            <th>Status</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($user_sender_ids) {
                            $i = 1;
                            foreach ($user_sender_ids as $user_sender_id) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $user_sender_id['username']; ?> ( <?php echo $user_sender_id['parent_username']; ?> )
                                    </td>
                                    <td><?php echo $user_sender_id['sender']; ?></td>
                                    <td>
                                        <?php if ($user_sender_id['sender_status'] == '0') { ?>
                                            <span class="label label-danger">Disapproved</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($user_sender_id['sender_status'] == '1') {
                                            ?>
                                            <button onclick="changeSIdStatus('<?php echo $user_sender_id['sender_id']; ?>', '<?php echo $user_sender_id['sender_key']; ?>', 0, '<?php echo $subtab; ?>');"
                                                    type="button" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Disapprove Sender Id">
                                                <i class="fa fa-ban"></i>
                                            </button>
                                            <?php
                                        } elseif ($user_sender_id['sender_status'] == '0') {
                                            ?>
                                            <button onclick="changeSIdStatus('<?php echo $user_sender_id['sender_id']; ?>', '<?php echo $user_sender_id['sender_key']; ?>', 1, '<?php echo $subtab; ?>');"
                                                    type="button" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Approve Sender Id">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <button onclick="deleteSenderId('<?php echo $user_sender_id['sender_id']; ?>', '<?php echo $user_sender_id['sender_key']; ?>', '<?php echo $subtab; ?>');"
                                                type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Sender Id">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php
                                $i++;
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

                <!-- Pagination -->
                <?php echo $paging; ?>
            </div>
        <?php } ?>

    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>