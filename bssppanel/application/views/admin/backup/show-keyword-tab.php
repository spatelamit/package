<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getKeywordTab('1');">Approved Keywords</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getKeywordTab('2');">Pending Keywords</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "3") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getKeywordTab('3');">Black Keywords</a>
    </li>
</ul>

<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active">

        <?php if ($subtab == '1') { ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th width="150">Username</th>
                            <th width="450">Keyword</th>
                            <th width="200">Matching Ratio (User) (%)</th>
                            <th width="100">Matching Ratio (All Users) (%)</th>
                            <th width="100">Action</th>
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
                                        <?php echo $keyword['username']; ?> 
                                        ( <?php echo ($keyword['parent_username'] == "") ? $keyword['admin_username'] : $keyword['parent_username']; ?> )
                                    </td>
                                    <td  class="word-break">
                                        <?php echo urldecode(mysql_real_escape_string($keyword['keywords'])); ?>
                                    </td>
                                    <td>
                                        <?php echo $keyword['percent_ratio_user']; ?>
                                    </td>
                                    <td>
                                        <?php echo $keyword['percent_ratio_all_users']; ?>
                                    </td>
                                    <td>
                                        <button onclick="deleteKeyword('approved', '<?php echo $subtab; ?>', '<?php echo $keyword['keyword_id']; ?>');"
                                                type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Keyword">
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
                                <td colspan="5">No Approved Keywords!.</td>
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

        <?php if ($subtab == '2') { ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="pending_keywords">
                    <thead>
                        <tr>
                            <th width="15%">Username</th>
                            <th>Keyword</th>
                            <th width="12%">Matching Ratio (%)</th>
                            <th colspan="3" width="20%">Approve</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($pending_keywords) {
                            $i = 1;
                            foreach ($pending_keywords as $keyword) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $keyword['username']; ?> ( <?php echo ($keyword['parent_username'] == "") ? $keyword['admin_username'] : $keyword['parent_username']; ?> )
                                    </td>
                                    <td  class="word-break">
                                        <?php echo urldecode(mysql_real_escape_string($keyword['keywords'])); ?>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-xs" placeholder="0-100" id="keyword_ratio<?php echo $i; ?>" name="keyword_ratio" />
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-xs" 
                                                onclick="approveKeyword('user', '<?php echo $keyword['keyword_id']; ?>', <?php echo $i; ?>);">User</button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-xs"
                                                onclick="approveKeyword('users', '<?php echo $keyword['keyword_id']; ?>', <?php echo $i; ?>);">Users</button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-xs"
                                                onclick="approveKeyword('both', '<?php echo $keyword['keyword_id']; ?>', <?php echo $i; ?>);">Both</button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" onclick="deleteKeyword('pending', '<?php echo $subtab; ?>', '<?php echo $keyword['keyword_id']; ?>');"
                                                data-toggle="tooltip" data-placement="top" title="Delete Keyword"><i class="fa fa-trash-o"></i></button>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td align="center" colspan="4">No Pending Keywords!.</td>
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

        <?php if ($subtab == '3') { ?>
            <div class="row">
                <div class="col-md-3 padding0">
                    <form role="form" class="tab-forms" id="validate-basic" data-parsley-validate method='post' action="javascript:saveKeyword(<?php echo $subtab; ?>);">
                        <div class="form-group">
                            <label for="contact_number">Add Black Keyword</label>
                            <textarea name="black_keyword" id="black_keyword" value="" placeholder="Enter Keyword" class="form-control"
                                      required="" data-parsley-error-message="Please Enter Keyword" rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
                <div class="col-md-9">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Keyword</th>
                                    <th width="10%">Status</th>
                                    <th colspan="2" width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php
                                if ($black_keywords) {
                                    $i = 1;
                                    foreach ($black_keywords as $black_keyword) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo urldecode(mysql_real_escape_string($black_keyword['black_keyword'])); ?>
                                            </td>
                                            <td>
                                                <?php if ($black_keyword['black_keyword_status'] == 1) { ?>
                                                    <span class="label label-success">Activated</span>
                                                <?php } elseif ($black_keyword['black_keyword_status'] == 0) { ?>
                                                    <span class="label label-danger">Deactivated</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($black_keyword['black_keyword_status'] == 1) {
                                                    ?>
                                                    <button onclick="changeKeywordStatus('<?php echo $black_keyword['black_keyword_id']; ?>', 0);"
                                                            type="button" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Disbale Black Keyword">
                                                        <i class="fa fa-ban"></i>
                                                    </button>
                                                    <?php
                                                } elseif ($black_keyword['black_keyword_status'] == 0) {
                                                    ?>
                                                    <button onclick="changeKeywordStatus('<?php echo $black_keyword['black_keyword_id']; ?>', 1);"
                                                            type="button" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Enable Black Keyword">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <button onclick="deleteKeyword('black', '<?php echo $subtab; ?>', '<?php echo $black_keyword['black_keyword_id']; ?>');"
                                                        type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Black Keyword">
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
                                        <td colspan="4" align="center">No Records Found!</td>
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

    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('.approve-btn').popover({
            html: true,
            placement: 'top',
            container: 'body'
        });
    })

    $('#validate-basic').parsley();

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>