<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>

<!--- for searching keywords Script-->

<script>
    $(document).ready(function() {
        var activeSystemClass = $('.list-group-item.active');

        //something is entered in search form
        $('#system-search').keyup(function() {
            var that = this;
            // affect all table rows on in systems table
            var tableBody = $('.table-list-search tbody');
            var tableRowsClass = $('.table-list-search tbody tr');
            $('.search-sf').remove();
            tableRowsClass.each(function(i, val) {

                //Lower text for case insensitive
                var rowText = $(val).text().toLowerCase();
                var inputText = $(that).val().toLowerCase();
                if (inputText != '')
                {
                    $('.search-query-sf').remove();
                    tableBody.prepend('<tr class="search-query-sf"><td colspan="6"><strong>Searching For: "'
                            + $(that).val()
                            + '"</strong></td></tr>');
                }
                else
                {
                    $('.search-query-sf').remove();
                }

                if (rowText.indexOf(inputText) == -1)
                {
                    //hide rows
                    tableRowsClass.eq(i).hide();

                }
                else
                {
                    $('.search-sf').remove();
                    tableRowsClass.eq(i).show();
                }
            });
            //all tr elements are hidden
            if (tableRowsClass.children(':visible').length == 0)
            {
                tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="6">No entries found.</td></tr>');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        var activeSystemClass = $('.list-group-item.active');

        //something is entered in search form
        $('#system-search1').keyup(function() {
            var that = this;
            // affect all table rows on in systems table
            var tableBody = $('.table-list-search1 tbody');
            var tableRowsClass = $('.table-list-search1 tbody tr');
            $('.search-sf3').remove();
            tableRowsClass.each(function(i, val) {

                //Lower text for case insensitive
                var rowText = $(val).text().toLowerCase();
                var inputText = $(that).val().toLowerCase();
                if (inputText != '')
                {
                    $('.search-query-sf3').remove();
                    tableBody.prepend('<tr class="search-query-sf3"><td colspan="6"><strong>Searching For: "'
                            + $(that).val()
                            + '"</strong></td></tr>');
                }
                else
                {
                    $('.search-query-sf3').remove();
                }

                if (rowText.indexOf(inputText) == -1)
                {
                    //hide rows
                    tableRowsClass.eq(i).hide();

                }
                else
                {
                    $('.search-sf3').remove();
                    tableRowsClass.eq(i).show();
                }
            });
            //all tr elements are hidden
            if (tableRowsClass.children(':visible').length == 0)
            {
                tableBody.append('<tr class="search-sf3"><td class="text-muted" colspan="6">No entries found.</td></tr>');
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        var activeSystemClass = $('.list-group-item.active');

        //something is entered in search form
        $('#black-keyword-search').keyup(function() {
            var that = this;
            // affect all table rows on in systems table
            var tableBody = $('.table-list-search2 tbody');
            var tableRowsClass = $('.table-list-search2 tbody tr');
            $('.search-sf').remove();
            tableRowsClass.each(function(i, val) {

                //Lower text for case insensitive
                var rowText = $(val).text().toLowerCase();
                var inputText = $(that).val().toLowerCase();
                if (inputText != '')
                {
                    $('.search-query-sf').remove();
                    tableBody.prepend('<tr class="search-query-sf"><td colspan="6"><strong>Searching for: "'
                            + $(that).val()
                            + '"</strong></td></tr>');
                }
                else
                {
                    $('.search-query-sf').remove();
                }

                if (rowText.indexOf(inputText) == -1)
                {
                    //hide rows
                    tableRowsClass.eq(i).hide();

                }
                else
                {
                    $('.search-sf').remove();
                    tableRowsClass.eq(i).show();
                }
            });
            //all tr elements are hidden
            if (tableRowsClass.children(':visible').length == 0)
            {
                tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="6">No entries found.</td></tr>');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        var activeSystemClass = $('.list-group-item.active');

        //something is entered in search form
        $('#user-black-keyword-search').keyup(function() {
            var that = this;
            // affect all table rows on in systems table
            var tableBody = $('.table-list-search3 tbody');
            var tableRowsClass = $('.table-list-search3 tbody tr');
            $('.search-sf').remove();
            tableRowsClass.each(function(i, val) {

                //Lower text for case insensitive
                var rowText = $(val).text().toLowerCase();
                var inputText = $(that).val().toLowerCase();
                if (inputText != '')
                {
                    $('.search-query-sf').remove();
                    tableBody.prepend('<tr class="search-query-sf"><td colspan="6"><strong>Searching for: "'
                            + $(that).val()
                            + '"</strong></td></tr>');
                }
                else
                {
                    $('.search-query-sf').remove();
                }

                if (rowText.indexOf(inputText) == -1)
                {
                    //hide rows
                    tableRowsClass.eq(i).hide();

                }
                else
                {
                    $('.search-sf').remove();
                    tableRowsClass.eq(i).show();
                }
            });
            //all tr elements are hidden
            if (tableRowsClass.children(':visible').length == 0)
            {
                tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="6">No entries found.</td></tr>');
            }
        });
    });
</script>
<!--- end searching keywords Script-->

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
    <li class="<?php echo (isset($subtab) && $subtab == "4") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getKeywordTab('4');">User's Black Keywords</a>
    </li>
</ul>

<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active">

        <?php if (isset($subtab) && $subtab == '1') { ?>        
            <!--- for searching keywords -->
            <form action="#" method="get">
                <div class="input-group">
                    <!-- USE TWITTER TYPEAHEAD JSON WITH API TO SEARCH -->
                    <input class="form-control" id="system-search" name="q" placeholder="Search By Username / Keyword" required>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form><br>
            <!--- end searching keywords -->
            <form name="bulk_action_form" action="<?php echo base_url(); ?>admin/delete_data" method="post" onSubmit="return delete_confirm();"/>
            <input type="submit" class="btn btn-danger" name="bulk_delete_submit" value="Delete All / Multiple" style="float: right;margin-top: -17px;"/><br>
            <div class="table-responsive">
                <table class="table table-list-search table-hover table-bordered">
                    <thead>
                        <tr>
                            <th width="120"><input type="checkbox" name="select_all" id="select_all" value=""/>Check All</th>    
                            <th width="150">Username</th>
                            <th width="450">Keyword</th>
                            <th width="200">Matching Ratio (User) (%)</th>
                            <th width="100">Matching Ratio (All Users) (%)</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($approve_keywords) && $approve_keywords) {
                            $i = 1;
                            foreach ($approve_keywords as $keyword) {
                                ?>
                                <tr>
                                    <td align="center"><input type="checkbox" name="checked_id[]" class="checkbox" value="<?php echo $keyword['keyword_id']; ?>"/>
                                    </td>  
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
            </form>

        <?php } ?>

        <?php if (isset($subtab) && $subtab == '2') { ?>
            <form action="#" method="get">
                <div class="input-group">
                    <!-- USE TWITTER TYPEAHEAD JSON WITH API TO SEARCH -->
                    <input class="form-control" id="system-search1" name="qk" placeholder="Search By Username / Keyword" required>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form><br>

            <div class="table-responsive">
                <table class="table table-list-search1 table-hover table-bordered" >
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
                        if (isset($pending_keywords) && $pending_keywords) {
                            $i = 1;
                            foreach ($pending_keywords as $keyword) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $keyword['username']; ?> ( <?php
                                        echo ($keyword['parent_username'] == "") ? $keyword
                                                ['admin_username'] : $keyword['parent_username'];
                                        ?> )
                                    </td>
                                    <td  class="word-break">
            <?php echo urldecode(mysql_real_escape_string($keyword['keywords'])); ?>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-xs" placeholder="0-100" id="keyword_ratio<?php echo $i; ?>" 
                                               name="keyword_ratio" />
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-xs"  onclick="approveKeyword('user', '<?php echo $keyword['keyword_id']; ?>', <?php echo $i; ?>);">User</button>
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
                                        <button type="button" class="btn btn-danger btn-xs" onclick="deleteKeyword('pending', '<?php echo $subtab; ?>',
                            '<?php echo $keyword['keyword_id']; ?>');"
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

<?php if (isset($subtab) && $subtab == '3') { ?>
            <div class="row">
                <div class="col-md-3 padding0">
                    <form role="form" class="tab-forms" id="keywordForm" method='post' action="javascript:saveKeyword(<?php echo $subtab; ?>);">
                        <div class="form-group">
                            <label for="black_keyword">Add Black Keyword</label>
                            <textarea name="black_keyword" id="black_keyword" value="" placeholder="Enter Keyword" class="form-control"
                                      required="" data-parsley-error-message="Please Enter Keyword" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
                <div class="col-md-11">
                    <form action="#" method="get">
                        <div class="input-group">
                            <!-- USE TWITTER TYPEAHEAD JSON WITH API TO SEARCH -->
                            <input class="form-control" id="black-keyword-search" name="Keyword" placeholder="Search Keyword" required>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form><br>
                    <div class="table-responsive">
                        <table class="table table-list-search2 table-hover table-bordered" >
                            <thead>
                                <tr>
                                    <th>Keyword</th>
                                    <th width="10%">Status</th>
                                    <th colspan="2" width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php
                                if (isset($black_keywords) && $black_keywords) {
                                    $i = 1;
                                    foreach ($black_keywords as $black_keyword) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php
                                                echo urldecode(mysql_escape_string(urldecode($black_keyword['black_keyword'])));
                                                ?>
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
                                                    <button onclick="changeKeywordStatus('<?php echo $black_keyword['black_keyword_id']; ?>', 0, '<?php echo $subtab; ?>');"
                                                            type="button" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Disbale Black Keyword">
                                                        <i class="fa fa-ban"></i>
                                                    </button>
                                                    <?php
                                                } elseif ($black_keyword['black_keyword_status'] == 0) {
                                                    ?>
                                                    <button onclick="changeKeywordStatus('<?php echo $black_keyword['black_keyword_id']; ?>', 1, '<?php echo $subtab; ?>');"
                                                            type="button" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Enable Black Keyword">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <?php
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

<?php if (isset($subtab) && $subtab == '4') { ?>
            <form action="#" method="get">
                <div class="input-group">
                    <!-- USE TWITTER TYPEAHEAD JSON WITH API TO SEARCH -->
                    <input class="form-control" id="user-black-keyword-search" name="Keyword" placeholder="Search User Black Keyword" required>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form><br>

            <div class="row">
                <div class="col-md-12 padding0">
                    <div class="table-responsive">
                        <table class="table table-list-search3 table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Keyword</th>
                                    <th width="15%">Username</th>
                                    <th width="10%">User Type</th>
                                    <th width="10%">Status</th>
                                    <th colspan="2" width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php
                                if (isset($black_keywords) && $black_keywords) {
                                    $i = 1;
                                    foreach ($black_keywords as $black_keyword) {
                                        ?>
                                        <tr>
                                            <td>
            <?php echo urldecode(mysql_real_escape_string($black_keyword['black_keyword'])); ?>
                                            </td>
                                            <td>
            <?php echo $black_keyword['username']; ?>
                                            </td>
                                            <td>
            <?php echo $black_keyword['utype']; ?>
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
                                                    <button onclick="changeKeywordStatus('<?php echo $black_keyword['black_keyword_id']; ?>', 0, '<?php echo $subtab; ?>');"
                                                            type="button" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Disbale Black Keyword">
                                                        <i class="fa fa-ban"></i>
                                                    </button>
                                                    <?php
                                                } elseif ($black_keyword['black_keyword_status'] == 0) {
                                                    ?>
                                                    <button onclick="changeKeywordStatus('<?php echo $black_keyword['black_keyword_id']; ?>', 1, '<?php echo $subtab; ?>');"
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
    $(function() {
        $('.approve-btn').popover({
            html: true,
            placement: 'top',
            container: 'body'
        });
    })

    $('#keywordForm').parsley();

    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>