<table class="table table-hover">
    <thead>
        <tr>
            <th>Connected SMPP</th>
            <th>Pending</th>
            <th>DND</th>
            <th>Rejected</th>
            <th>Blocked</th>
            <th>Submit</th>
            <th>Failed</th>
            <th>Delivered</th>
            <th>Total</th>
            <th>Actual Deduction*</th>
        </tr>
    </thead>
    <tbody class="bgf7">
        <?php
        if ($smpp_logs) {
            $i = 1;
            foreach ($smpp_logs as $row) {
                $total_pending = 0;
                $total_dnd = 0;
                $total_rejected = 0;
                $total_blocked = 0;
                $total_submit = 0;
                $total_failed = 0;
                $total_delivered = 0;
                $total = 0;
                if (isset($row['31'])) {
                    $total_pending = $total_pending + $row['31'];
                }
                if (isset($row['4'])) {
                    $total_pending = $total_pending + $row['4'];
                }
                $total = $total + $total_pending;
                if (isset($row['DND'])) {
                    $total_dnd = $total_dnd + $row['DND'];
                    $total = $total + $total_dnd;
                }
                if (isset($row['16'])) {
                    $total_rejected = $total_rejected + $row['16'];
                    $total = $total + $total_rejected;
                }
                if (isset($row['Rejected'])) {
                    $total_rejected = $total_rejected + $row['Rejected'];
                    $total = $total + $total_rejected;
                }
                if (isset($row['Blocked'])) {
                    $total_blocked = $total_blocked + $row['Blocked'];
                    $total = $total + $total_blocked;
                }
                if (isset($row['8'])) {
                    $total_submit = $total_submit + $row['8'];
                    $total = $total + $total_submit;
                }
                if (isset($row['2'])) {
                    $total_failed = $total_failed + $row['2'];
                    $total = $total + $total_failed;
                }
                if (isset($row['3'])) {
                    $total_submit = $total_submit + $row['3'];
                    $total = $total + $total_submit;
                }
                if (isset($row['1'])) {
                    $total_delivered = $total_delivered + $row['1'];
                    $total = $total + $total_delivered;
                }
                ?>
                <tr>
                    <td><?php echo $row['user_group_name'] . " (" . $row['smsc_id'] . ")"; ?></td>                            
                    <td><?php echo $total_pending; ?></td>
                    <td><?php echo $total_dnd; ?></td>
                    <td><?php echo $total_rejected; ?></td>
                    <td><?php echo $total_blocked; ?></td>
                    <td><?php echo $total_submit; ?></td>
                    <td><?php echo $total_failed; ?></td>
                    <td><?php echo $total_delivered; ?></td>
                    <td><?php echo $total; ?></td>
                    <td><?php echo $row['total_deduction']; ?></td>
                </tr>
                <?php
                $i++;
            }
        } else {
            ?>
            <tr>
                <td colspan="10" align="center">
                    <strong>No Logs!</strong>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>