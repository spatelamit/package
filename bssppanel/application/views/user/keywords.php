<div id="nav_sub">
    <div class="row">
        <ul class="nav nav-pills">
            <li class="<?php echo (isset($tab) && $tab == 'keywords') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/keywords">Keywords</a>
            </li>
            <li class="<?php echo (isset($tab) && $tab == 'black_keywords') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/black_keywords">Black Keywords</a>
            </li>
        </ul>
    </div>
</div>
</div>
<div class="container" id="reseller_settings">
    <?php
    if (isset($tab) && $tab == 'keywords') {
        ?>
        <div class="row">        
            <div class="col-sm-8">
                <div class="portlet">
                    <h2 class="content-header-title tbl">Keywords</h2>
                    <div class="portlet-content">
                        <div class="table-responsive" id="data_table">
                            <table class="table table-hover bgf">
                                <thead>
                                    <tr>
                                        <th width="60%">Keyword</th>
                                        <th width="20%">Status</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    if (isset($keywords) && $keywords) {
                                        foreach ($keywords as $keyword) {
                                            ?>
                                            <tr>
                                                <td class="word-break"><?php echo mysql_real_escape_string(urldecode($keyword['keywords'])); ?></td>
                                                <td>
                                                    <?php
                                                    if ($keyword['keyword_status'] == '1')
                                                        echo "<span class='label label-success'>Approved</span>";
                                                    elseif ($keyword['keyword_status'] == '0')
                                                        echo "<span class='label label-danger'>Disapproved</span>";
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" onclick="deleteUserData('keyword', <?php echo $keyword['keyword_id']; ?>)"
                                                       class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="Delete Keyword">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                    }else {
                                        ?>
                                        <tr>
                                            <td colspan="3">No Record Found!</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="col-sm-3">
                <div class="portlet">
                    <h2 class="content-header-title">Add New Keyword</h2>
                    <div class="portlet-content">
                        <form id="keywordForm" class="tab-forms" action="javascript:saveKeyword('Normal');">
                            <div class="row">
                                <div class="form-group col-md-12 padding0">
                                    <label for="keyword">Keyword</label>
                                    <textarea name="keyword" id="keyword" placeholder="Please Enter Keyword" value="" rows="5"
                                              class="form-control" required="" data-parsley-required-message="Please Enter Keyword"></textarea>
                                </div>
                                <div class="form-group col-md-12 padding0 mt5">
                                    <button type="submit" class="btn btn-primary">Approve Keyword</button>
                                </div>
                            </div>
                        </form>
                    </div> 
                </div> 
            </div>
        </div>
        <?php
    }

    if (isset($tab) && $tab == 'black_keywords') {
        ?>
        <div class="row">        
            <div class="col-sm-8">
                <div class="portlet">
                    <h2 class="content-header-title tbl">Black Keywords</h2>
                    <div class="portlet-content">
                        <div class="table-responsive" id="data_table">
                            <table class="table table-hover bgf">
                                <thead>
                                    <tr>
                                        <th width="60%">Keyword</th>
                                        <th width="20%">Status</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    if (isset($black_keywords) && $black_keywords) {
                                        foreach ($black_keywords as $keyword) {
                                            ?>
                                            <tr>
                                                <td class="word-break"><?php echo mysql_real_escape_string(urldecode($keyword['black_keyword'])); ?></td>
                                                <td>
                                                    <?php
                                                    if ($keyword['black_keyword_status'] == '1')
                                                        echo "<span class='label label-success'>Active</span>";
                                                    elseif ($keyword['black_keyword_status'] == '0')
                                                        echo "<span class='label label-danger'>Deactive</span>";
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" onclick="deleteUserData('black_keyword', <?php echo $keyword['black_keyword_id']; ?>)"
                                                       class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="Delete Black Keyword">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                    }else {
                                        ?>
                                        <tr>
                                            <td colspan="3">No Record Found!</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="col-sm-3">
                <div class="portlet">
                    <h2 class="content-header-title">Add New Black Keyword</h2>
                    <div class="portlet-content">
                        <form id="keywordForm" class="tab-forms" action="javascript:saveKeyword('Black');">
                            <div class="row">
                                <div class="form-group col-md-12 padding0">
                                    <label for="keyword">Black Keyword</label>
                                    <textarea name="keyword" id="keyword" placeholder="Please Enter Black Keyword" value="" rows="5"
                                              class="form-control" required="" data-parsley-required-message="Please Enter Black Keyword"></textarea>
                                </div>
                                <div class="form-group col-md-12 mt5 padding0">
                                    <button type="submit" class="btn btn-primary">Save Black Keyword</button>
                                </div>
                            </div>
                        </form>
                    </div> 
                </div> 
            </div>
        </div>
        <?php
    }
    ?>