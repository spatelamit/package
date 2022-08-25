<div class="page-content-title txt-center">
    <h3><i class="fa fa-exclamation-circle"></i> SMS Error Logs</h3> 
</div>
<div id="custom-sms">
    <div class="table-responsive padding15">
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="200">Date</th>
                    <th width="200">Username</th>
                    <th width="150">By</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody class="bgf7">
                <?php
                if ($sms_logs) {
                    $i = 1;
                    foreach ($sms_logs as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row['sms_log_time']; ?></td>
                            <td>
                                <?php echo $row['username']; ?>
                                ( <?php echo ($row['parent_username'] == "") ? $row['admin_username'] : $row['parent_username']; ?> )
                            </td>
                            <td><?php echo $row['sms_log_by']; ?></td>
                            <td><span class="text-danger"><?php echo $row['sms_log_reason']; ?></span></td>
                        </tr>
                        <?php
                        $i++;
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="4" align="center">
                            <strong>No Error Logs!</strong>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <nav class="pull-right">
            <ul class="pagination radius0">
                <?php echo $pagination_helper; ?>
            </ul>
        </nav>

    </div>
</div>