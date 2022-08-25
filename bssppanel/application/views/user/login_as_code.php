  <?php
            if (isset($reseller_user) && $reseller_user != "") {
                if (isset($login_from) && $login_from) {
                    if ($login_from == 'user') {
                        ?>
                        <a href="<?php echo base_url(); ?>user/back_to_account/<?php echo $reseller_user; ?>" class="btn btn-info btn-sm">Login As</a>
                        <?php
                    } elseif ($login_from == 'admin') {
                        ?>
                        <a href="<?php echo base_url(); ?>admin/back_to_account/<?php echo $reseller_user; ?>" class="btn btn-info btn-sm">Login As</a>
                        <?php
                    }
                }
            } else {
                ?>
                        <form method="POST" action="<?php echo base_url(); ?>user/login_as">
                    
                                  <input type="hidden" name="parant_id" value=" <?php echo $user_id; ?>">
                            <input type="hidden" name="ref_id" value="<?php echo $ref_user['user_id']; ?>">
                        <button type="submit" class="btn btn-info btn-sm">Login As</button>
                        </form>
                <?php
            }
            ?>