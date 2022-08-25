<table class="table table-responsive" id="show_drafts">
    <tbody>
        <?php
        if (isset($result_drafts) && $result_drafts) {
            foreach ($result_drafts as $value) {
                ?>
                <tr>
                    <td style="border-bottom: 1px #D0D0D0 solid;">
                        <a href="javascript:void(0)" onclick="pickField('message', '<?php echo $value->draft_message; ?>')">
                            <?php echo $value->draft_message; ?>
                        </a>
                    </td>
                    <td width="50" style="border-bottom: 1px #D0D0D0 solid;">
                        <a href="javascript:void(0)" onclick="deleteDraft('<?php echo $value->draft_message_id; ?>')" class="btn btn-info btn-xs">
                            DELETE
                        </a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>