<div class="page-content-title txt-center">
    <h3><i class="fa fa-file-text"></i>  Daily Analysis  </h3>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <form role="form" id="dailyReportForm" method='post' action="javascript:getDailyAnalysisAmount();" class="notify-forms" autocomplete="off">
            <div class="col-md-4 padding15">
                <input type="text" id="date" name="date" placeholder="Enter Date" class="form-control" />

            </div>

            <div class="col-md-1 padding15">
                <button name="get_sms_btn" id="get_report_btn" class="btn btn-primary"
                        data-loading-text="Fetching..." autocomplete="off" type="submit">
                    Get Data
                </button>
            </div>
        </form>
    </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="table-responsive" class="col-md-6" id="search_system_analysis_table">
        <div class="tab-content bgf9"  >
            <div class="tab-pane fade in active">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="stage">
                        <thead>
                            <tr>

                                <th>Total SMS</th>
                                <th>Total Amount</th>
                                <th>Average Pricing</th>
                                <th>Deliver SMS</th>
                                <th>Total Delivered Amount (<i class="fa fa-inr">)</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <?php
                        if (isset($daily_data) && $daily_data) {

                            foreach ($daily_data as $show_transation_log) {
                                ?>
                                <tr>
                                    <td><?php echo $show_transation_log['total_sms']; ?></td> 
                                    <td><?php echo $show_transation_log['total_amount']; ?></td>
                                    <td><?php echo $show_transation_log['average_pricing']; ?></td>
                                    <td><?php echo $show_transation_log['delivered_sms']; ?></i></td>
                                    <td><?php echo $show_transation_log['total_delivered_amount']; ?></td>
                                    <td><?php echo $show_transation_log['date']; ?></td>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>

                            <tr>

                                <td colspan="6" align="center" style="padding-top:20px;">No Records Found!</td>
                            </tr>   
                        <?php } ?>   
                    </table>

                </div>


            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate());
        $('#date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });

    });
</script>