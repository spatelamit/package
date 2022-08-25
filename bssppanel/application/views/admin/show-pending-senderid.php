 <div class="table-responsive" id="search_response">
                <table class="table table-hover table-bordered" >
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
                        if (isset($user_sender_ids) && $user_sender_ids) {
                            $i = 1;
                            foreach ($user_sender_ids as $user_sender_id) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $user_sender_id['username']; ?> 
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

            </div>