<div class="table-responsive">
                            <div class="table-responsive" id="search_info">
                                <table class="table table-bordered bgf">
                                    <tr>   <th style="color:#4A9AB5;">Submit</th> 
                                         <th style="color:#4A9AB5;">DND</th> 
                                        <th style="color:#4271A5;">Sent</th> 
                                        <th style="color:#8CAA52;">Delivered</th> 
                                        <th style="color:#AD4942;">Failed</th> 
                                        <th style="color:#DE8742;">Rejected</th> 
                                        <th>Total</th>
                                        <th>Total Deduction</th> </tr>

                                    <tr> 
                                        <?php
                                        if (isset($total_sms) && $total_sms) {
                                            $delivered = 0;
                                            $sent = 0;
                                            $failed = 0;
                                            $rejected = 0;
                                            $submit = 0;
                                             $dnd = 0;
                                            $total_record = sizeof($total_sms);
                                            foreach ($total_sms as $total_data) {
                                                $status = $total_data['status'];
                                                if ($status == 1) {
                                                    $delivered++;
                                                } else if ($status == 2) {
                                                    $failed++;
                                                } else if ($status == 3) {
                                                    $sent++;
                                                } else if ($status == 16) {
                                                    $rejected++;
                                                } else if ($status == 31) {
                                                    $submit++;
                                                }else if ($status == 'DND') {
                                                    $dnd++;
                                                }
                                            }
                                            ?>
                                            <th style="color:#4A9AB5;"><?php echo $submit; ?></th>
                                            <th style="color:#4A9AB5;"><?php echo  $dnd; ?></th>
                                            <th style="color:#4271A5;"><?php echo $sent; ?></th>
                                            <th style="color:#8CAA52;"><?php echo $delivered; ?></th>
                                            <th style="color:#AD4942;"><?php echo $failed; ?></th>
                                            <th style="color:#DE8742;"><?php echo $rejected; ?></th>
                                            <th><?php echo $total_record; ?></th>
                                            <th><?php echo $total_deduction[0]['SUM(`total_deducted`)']; ?></th>

                                            <?php
                                        } else {
                                            ?> 
                                            <th colspan="10" style="text-align:center;"> No Result</th> 
                                            <?php
                                        }
                                        ?>
                                    </tr> 



                                </table>

                            </div>                                    
                        </div>