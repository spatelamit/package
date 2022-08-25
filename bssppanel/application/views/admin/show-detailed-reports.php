<?php
if (isset($detailed_reports) && $detailed_reports) {
    $i = 1;
    foreach ($detailed_reports as $sms) {
        $dlr_reciept = $sms['dlr_receipt'];
        $decode_dlr_reciept = urldecode($dlr_reciept);
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $sms['mobile_no']; ?></td>
            <td><?php echo $sms['user_group_name']; ?></td>
            <td>
                <?php
                if ($sms['status'] == "1") {
                    echo "Delivered";
                } elseif ($sms['status'] == "2" && strpos($decode_dlr_reciept, 'err:006') === false) {
                    echo "Failed";
                } elseif ($sms['status'] == "31" || $sms['status'] == "23") {
                    echo "Pending";
                } elseif ($sms['status'] == "8") {
                    echo "Submitted";
                } elseif ($sms['status'] == "DND" || (strpos($decode_dlr_reciept, 'err:006') !== false && strpos($decode_dlr_reciept, 'stat:UNDELIV') !== false)) {
                    echo "NDNC";
                } elseif ($sms['status'] == "16" || $sms['status'] == "Rejected") {
                    echo "Rejected From Operator";
                } elseif ($sms['status'] == "Blocked") {
                    echo "Blocked By Operator";
                } elseif ($sms['status'] == "3" || $sms['status'] == "4") {
                    echo "Report Pending";
                } elseif ($sms['status'] == "25") {
                    echo "Cancelled By User";
                }
                ?>
            </td>
            <td><?php echo $sms['submit_date']; ?></td>
            <td><?php echo ($sms['done_date'] != "") ? $sms['done_date'] : "-"; ?></td>
            <td>
                <?php
                echo substr($decode_dlr_reciept, strpos($decode_dlr_reciept, "stat") + 0);
                ?>
            </td>
        </tr>
        <?php
        $i++;
    }
} else {
    ?>
    <tr>
        <td colspan="7">No Record Found!</td>
    </tr>
    <?php
}
?>