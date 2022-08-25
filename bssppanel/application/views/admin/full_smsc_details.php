<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SMSC FULL DETAILS</title>


    </head>
    <body bgcolor="#C9C9C9">
        <table border="1" width="1450" >
            <tr><td align="center" colspan="20"><h3  style="color: #900;">SMSC FULL DETAILS</h3></td></tr>
            <tr>
                  <th>SR. NO</th>
                <th>SMS ID</th>
                <th>USER GROUP ID</th>
                <th>CAMPAIGN ID</th>
                <th>USER ID</th>
                <th>MSG ID</th>
                <th>MESSAGE ID</th>
                 <th>MOBILE NO</th>
                 <th>STATUS</th>
                  <th>SUBMIT DATE</th>
                   <th>DONE DATE</th>
                    <th>DLR STATUS</th>
                     <th>ACTUAL ROUTE</th>
                      <th>DEFAULT ROUTE</th>

            </tr>

            <?php
            $count_id = 1;
            if (isset($full_details) && $full_details) {
                foreach ($full_details as $full_details1) {
                    ?>
                    <tr>
                        <td align="center"><?php echo $count_id; ?></td> 
                        <td align="center"><?php echo $full_details1['sms_id']; ?></td> 
                        <td align="center"><?php echo $full_details1['user_group_id']; ?></td>
                        <td align="center"><?php echo $full_details1['campaign_id']; ?></td>
                        <td align="center"><?php echo $full_details1['user_id']; ?></td>
                        <td align="center"><?php echo $full_details1['msg_id']; ?></td>
                        <td align="center"><?php echo $full_details1['message_id']; ?></td>
                         <td align="center"><?php echo $full_details1['mobile_no']; ?></td>
                          <td align="center"><?php echo $full_details1['status']; ?></td>
                           <td align="center"><?php echo $full_details1['submit_date']; ?></td>
                            <td align="center"><?php echo $full_details1['done_date']; ?></td>
                             <td align="center"><?php echo $full_details1['dlr_status']; ?></td>
                              <td align="center"><?php echo $full_details1['actual_route']; ?></td>
                               <td align="center"><?php echo $full_details1['default_route']; ?></td>

                        <?php
                        $count_id++;
                    }
                } else {
                    ?>
                <tr><td align="center" colspan="20"  style="color: #900;">Result not found</td></tr>
                <?php
            }
            ?>
        </tr>
        <tr>
            <?php 
              $count_id = $count_id -1;
            ?>
            <td align="center" colspan="20"  style="color: #900;"><h4>Total No. Of  Records : <?php echo $count_id; ?></h4></td>
        </tr>



    </table>




</body>
</html>
