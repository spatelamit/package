</section>
</div>
</div>
</div>
</div>

<!-- Start Sidebar -->
<div id="main-sidebar" class="main-sidebar main-sidebar-right">
    <div id="main-sidebar-wrapper" class="main-sidebar-wrapper">
        <nav>
            <ul>
                <li>
                    <h3>Online Users</h3>
                </li>
                <?php
                if (isset($online_users) && $online_users) {
                    $i = 1;
                    foreach ($online_users as $online_user) {
                        $cur_date = date('d-m-Y h:i:s');
                        $last_seen = $online_user['last_seen'];
                        $diff_in_sec = intval(strtotime($cur_date) - strtotime($last_seen));
                        $diff_in_min = intval($diff_in_sec / 60);
                        $diff_in_hrs = intval($diff_in_sec / 3600);
                        $diff_in_days = intval($diff_in_hrs / 24);
                        ?>
                        <li>
                            <a href="#" data-container="body" data-toggle="popover" data-placement="left" data-html='true' data-trigger='hover'
                               title="<?php echo ucwords($online_user['name']); ?>" class="chatbox"
                               data-content="<?php
                               echo ($online_user['parent_username'] == "") ? "<h5>Parent: " . $online_user['admin_username'] . "</h5>" : "<h5>Parent: " . $online_user['parent_username'] . "</h5>";
                               echo ($online_user['ref_username'] == "") ? "" : "<h5>Reseller: " . $online_user['ref_username'] . "</h5>";
                               echo "<p><i class='fa fa-envelope'></i> " . $online_user['email_address'] . "</p>";
                               echo "<p><i class='fa fa-phone'></i> " . $online_user['contact_number'] . "</p>";
                               echo "<p><i class='fa fa-check'></i> " . $online_user['pr_user_group_name'] . "</p>";
                               echo "<p><i class='fa fa-check'></i> " . $online_user['tr_user_group_name'] . "</p>";
                               if ($last_seen != "") {
                                   if ($diff_in_sec > 0 && $diff_in_min <= 0)
                                       echo "<i class='fa fa-clock-o'></i> " . $diff_in_sec . " secs ago";
                                   elseif ($diff_in_min > 0 && $diff_in_hrs <= 0)
                                       echo "<i class='fa fa-clock-o'></i> " . $diff_in_min . " mins ago";
                                   elseif ($diff_in_hrs > 0 && $diff_in_days <= 0)
                                       echo "<i class='fa fa-clock-o'></i> " . $diff_in_hrs . " hrs ago";
                                   elseif ($diff_in_days > 0 && $diff_in_days <= 10)
                                       echo "<i class='fa fa-clock-o'></i> " . $diff_in_days . " days ago";
                                   elseif ($diff_in_days > 10)
                                       echo "<i class='fa fa-clock-o'></i> " . $last_seen;
                               }
                               ?>">
                                <i class="fa fa-circle active"></i> <?php echo ucwords($online_user['name']) . " (" . $online_user['username'] . ")"; ?>
                            </a>
                        </li>
                        <?php
                    }
                }else {
                    ?>
                    <li>
                        <a href="#">No Users!</a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </nav>
    </div>
</div>
<!-- End Sidebar -->

<!--JS Scripts-->
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/simpler-sidebar.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/bootbox.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/admin/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/admin/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/plugins/nice-scroll/jquery.nicescroll.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/plugins/simple-slider-master/js/simple-slider.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/validator.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/custom.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/notify.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>node_modules/socket.io/node_modules/socket.io-client/socket.io.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/admin-chatbox.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/metro/notify-metro.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/admin.script.js"></script>

<?php
if ($this->session->flashdata('message') && $this->session->flashdata('message_type')) {
    $message = $this->session->flashdata('message');
    $message_type = $this->session->flashdata('message_type');
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $.notify(
                    {
                        title: 'Success',
                        text: '<?php echo (isset($message) && $message) ? $message : ""; ?>',
                        image: '<i class="fa fa-check-circle"></i>'
                    },
                    {
                        style: 'metro',
                        className: '<?php echo (isset($message_type) && $message_type) ? $message_type : ""; ?>'
                    }
            );
        });
    </script>
    <?php
}
?>  
<script type="text/javascript">
    /*
     $(document).ready(function () {
     $.notify.defaults({breakNewLines: true});
     $.notify('Hello Worlds\nHello Worlds\nHello Worlds\nHello Worlds', 'success');
     });
     */
</script>


</body>
</html>
<?php $this->session->unset_userdata('message_data'); ?>