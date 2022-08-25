<div class="row pager">
    <div class="col-md-4 text-left padding0">
        <?php echo $records_data; ?>
    </div>
    <?php
    if (isset($total_pages) && $total_pages) {
        if ($total_pages > 1) {
            ?>
            <div class="col-md-4 padding0">
                <nav>
                    <ul>
                        <?php if ($page_no > 1) { ?>
                            <li class="">
                                <a href="javascript:void(0);" onclick="paging<?php echo $function; ?>(<?php echo $page_no - 1; ?>, 30, <?php echo $total_pages; ?>);">
                                    <i class="fa fa-long-arrow-left"></i> Previous
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="disabled">
                                <a href="javascript:void(0);">
                                    <i class="fa fa-long-arrow-left"></i> Previous
                                </a>
                            </li>
                        <?php } ?>
                        <li>
                            Page <strong><?php echo ($total_pages) ? $page_no : "0"; ?></strong> of <strong><?php echo $total_pages; ?></strong>
                        </li>
                        <?php if ($page_no < $total_pages) { ?>
                            <li class="">
                                <a href="javascript:void(0);" onclick="paging<?php echo $function; ?>(<?php echo $page_no + 1; ?>, 30, <?php echo $total_pages; ?>);">
                                    Next <i class="fa fa-long-arrow-right"></i>
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="disabled">
                                <a href="javascript:void(0);">
                                    Next <i class="fa fa-long-arrow-right"></i>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
            <div class="col-md-2 text-right"></div>
            <div class="col-md-2 text-right">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">Page</span>
                    <input type="text" name="page_no" id="page_no" class="form-control input-sm" value="<?php echo $page_no; ?>">
                    <span class="input-group-btn">
                        <button class="btn btn-primary btn-sm" type="button" onclick="paging<?php echo $function; ?>(page_no.value, 30, <?php echo $total_pages; ?>);">Go</button>
                    </span>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>