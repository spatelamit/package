<table class="table table-hover bgf">
    <thead>
        <tr>
            <th>S.No.</th>
            <th>Receiver Number</th>
            <th>Status</th>
            <th>Submit Date</th>
            <th>Done Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($sent_sms) && $sent_sms) {
            $i = 1;
            foreach ($sent_sms as $sms) {
                $campaign_id = $sms['campaign_id'];
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $sms['mobile_no']; ?></td>
                    <td>
                        <?php
                        if ($sms['status'] == "1") {
                            echo "Delivered";
                        } elseif ($sms['status'] == "2") {
                            echo "Failed";
                        } elseif ($sms['status'] == "31" || $sms['status'] == "4") {
                            echo "Pending";
                        } elseif ($sms['status'] == "8") {
                            echo "Submit";
                        } elseif ($sms['status'] == "DND" || $sms['status'] == "16") {
                            echo "DND";
                        } elseif ($sms['status'] == "Blocked") {
                            echo "Block By Operator";
                        } else {
                            echo $sms['status'];
                        }
                        ?>
                    </td>
                    <td><?php echo $sms['submit_date']; ?></td>
                    <td><?php echo $sms['done_date']; ?></td>
                </tr>
                <?php
                $i++;
            }
        }
        ?>
    </tbody>
</table>