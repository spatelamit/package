
<div class="page-content-title txt-center">
    <h3><i class="fa fa-search"></i> Search Users <strong><?php echo $username; ?></strong></h3> 
</div>

<div id="user-mngmnt">
    <div class="container-fluid padding15">
        <div class="row" id="searchnew">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php
                if ($search_users) {
                    foreach ($search_users as $user) {
                        ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="panel panel-<?php echo ($user['check_demo_user']) ? "danger" : "success"; ?>">
                                <div class="panel-heading">
                                    <strong class="text-uppercase">
                                        <?php echo $user['username']; ?>
                                        <?php echo ($user['parent_username'] == "") ? "(" . $user['admin_username'] . ")" : "(" . $user['parent_username'] . ")"; ?>
                                        <?php echo ($user['ref_username'] == "") ? "" : "(" . $user['ref_username'] . ")"; ?>
                                    </strong>
                                </div>
                                <div class="panel-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <i class="fa fa-user"></i> <?php echo $user['name']; ?>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fa fa-envelope"></i> <?php echo $user['email_address']; ?>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fa fa-phone"></i> <?php echo $user['contact_number']; ?>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fa fa-life-ring"></i> <?php echo $user['utype']; ?>
                                        </li>
                                    </ul>
                                </div>
                                <div class="panel-footer">
                                    <button onclick="getUser('<?php echo $user['username']; ?>');" class="btn btn-primary btn-xs">
                                        Go To Profile
                                    </button>
                                    <a href="<?php echo base_url(); ?>admin/login_as/<?php echo $user['user_id']; ?>" class="btn btn-success btn-xs pull-right">
                                        Login As
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="col-md-12 col-sm-12">
                        <h4>
                            No record found for <strong><?php echo $username; ?></strong>
                        </h4>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>