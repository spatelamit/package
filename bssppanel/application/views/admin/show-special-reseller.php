<div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Reseller Name</th>
                            <th>User Type</th>
                            <th>TR Balance</th>
                            <th>PR Balance</th>
                            <th>Special TR Balance</th>
                            <th>Special PR Balance</th>
                            <th>Update Special Reseller</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($resellers) && $resellers) {
                            $i = 1;
                            foreach ($resellers AS $reseller) {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $reseller['username']; ?></td>
                                    <td><?php echo $reseller['utype']; ?></td>
                                    <td><?php echo $reseller['tr_sms_balance']; ?></td>
                                    <td><?php echo $reseller['pr_sms_balance']; ?></td>
                                    <td><?php echo $reseller['special_tr_balance']; ?></td>
                                    <td><?php echo $reseller['special_pr_balance']; ?></td>
                                    <?php if ($reseller['spacial_reseller_status'] == 1) { ?>
                                        <td><button data-original-title="Click to approve" onclick="makeMeSpecialReseller('<?php echo $reseller['user_id']; ?>', '<?php echo $reseller['spacial_reseller_status']; ?>');" type="button" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Make Me Normal Reseller">Special Reseller</button></td>
                                    <?php } else { ?>
                                        <td><button data-original-title="Click to approve" onclick="makeMeSpecialReseller('<?php echo $reseller['user_id']; ?>', '<?php echo $reseller['spacial_reseller_status']; ?>');" type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Make Me Special Reseller">Make Me Special Reseller</button></td>
                                    <?php } ?>

                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4">No Record!</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>


        </div>