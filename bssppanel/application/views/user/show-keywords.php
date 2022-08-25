<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />
<?php
if (isset($tab) && $tab == 'keywords') {
    ?>
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
            if (isset($keywords) && $keywords) {
                $i = 1;
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
                    <td colspan="4">No Record Found!</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
}if (isset($tab) && $tab == 'black_keywords') {
    ?>
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
    <?php
}
?>
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>