<?php
error_reporting(0);
$hostname = "localhost";
$username = "bulksms_user";
$password = "BALAJI@sr#ts7828";
$database = "bulksms_system";


$conn = mysql_connect("$hostname", "$username", "$password") or die(mysql_error());
mysql_select_db("$database", $conn) or die(mysql_error());
?>

<?php
$user_route_name = array();
$actual_size = sizeof($daily_sms);
$user_id = array();
$no_of_pre_user = array();
$total_user_id = array();
foreach ($daily_sms as $sms_user_id) {
    $user_id[] = $sms_user_id['user_id'];
    $user_route_name[] = $sms_user_id['route'];
    $total_sms[] = $sms_user_id['total_messages'];
}


$sizeof_user_id = sizeof($user_id);



$user_ids = array_unique($user_id);


$user_ids = array_values($user_ids);

$size = sizeof($user_ids);

for ($j = 0; $j < $size; $j++) {
    $tr = 0;
    $pr = 0;
    for ($k = 0; $k < $sizeof_user_id; $k++) {
        if ($user_ids[$j] == $user_id[$k] && $user_route_name[$k] == "A") {

            $pr = $pr + $total_sms[$k];
        } else if ($user_ids[$j] == $user_id[$k] && $user_route_name[$k] == "B") {
            $tr = $tr + $total_sms[$k];
        }
    }
    $no_of_pre_user[] = $data = array(
        'user_id' => $user_ids[$j],
        'pr' => $pr,
        'tr' => $tr
    );
}
?>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="portlet">
                <h2 class="content-header-title tbl">Users SMS Consumption</h2>
                <div class="portlet-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover bgf">
                            <thead>
                                <tr>
                                    <th>SR NO.</th>
                                    <th>Username</th>
                                    <th>Transactional</th>
                                    <th>Pramotional</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($no_of_pre_user) && $no_of_pre_user) {
                                    $i = 1;
                                    foreach ($no_of_pre_user as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>  
                                            <td><?php
                                                $user_id = $row['user_id'];
                                                ;
                                                $query = "SELECT `username` FROM `users` WHERE `user_id` = '" . $user_id . "'";
                                                $result = mysql_query($query);
                                                $row1 = mysql_fetch_array($result);
                                                $username = $row1["username"];
                                                echo $username;
                                                ?></td> 
                                            <td><?php echo $row['tr']; ?></td>  
                                            <td><?php echo $row['pr']; ?></td>  

                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="9" align="center">
                                            <strong>No Record Found!</strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td align="center" colspan="9">
                                        <ul class="pagination margin0">
                                            <?php echo $pagination_helper; ?>
                                        </ul>
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div> 
            </div> 
        </div>
    </div>
</div>