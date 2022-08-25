<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />
<?php
// Keywords
if (isset($type) && $type == 'keyword') {
    ?>
    <table class="table table-hover bgf">
        <thead>
            <tr>
                <th>Number</th>
                <th>Keyword</th>
                <th>Date</th>
                <th>Validity</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($short_keywords) && $short_keywords) {
                foreach ($short_keywords as $key => $keyword) {
                    ?>
                    <tr>
                        <td><?php echo $keyword['short_number']; ?></td>
                        <td><?php echo $keyword['short_keyword']; ?></td>
                        <td><?php echo $keyword['short_keyword_date']; ?></td>
                        <td><?php echo $keyword['short_keyword_expiry']; ?></td>
                        <td>
                            <?php
                            if ($keyword['short_keyword_status'])
                                echo "<span class='label label-success'>Approved</span>";
                            else
                                echo "<span class='label label-danger'>Disapproved</span>";
                            ?>
                        </td>
                        <td>
                            <a href="javascript:void(0);" onclick="deleteSLKeyword('short', <?php echo $keyword['short_keyword_id']; ?>)"
                               class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="Delete Keyword">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            }else {
                ?>
                <tr>
                    <td colspan="6">No Record Found!</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
} 
// Keyword Reply
if (isset($type) && $type == 'keyword_reply') {
    ?>
    <table class="table table-hover bgf">
        <thead>
            <tr>
                <th>Number</th>
                <th>Keyword</th>
                <th>Reply Content</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($short_keyword_reply) && $short_keyword_reply) {
                foreach ($short_keyword_reply as $key => $keyword_reply) {
                    ?>
                    <tr>
                        <td><?php echo $keyword_reply['short_number']; ?></td>
                        <td><?php echo $keyword_reply['short_keyword']; ?></td>
                        <td>
                            <?php echo $keyword_reply['keyword_reply']; ?> [<?php echo $keyword_reply['keyword_reply_sender']; ?>]
                        </td>
                        <td>
                            <a href="javascript:void(0);" onclick="deleteSLKReply('short', <?php echo $keyword_reply['keyword_reply_id']; ?>)"
                               class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Keyword Reply">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
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
}
?>
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>