<script src="<?php echo base_url(); ?>Assets/admin/js/highcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>Assets/admin/js/exporting.js" type="text/javascript"></script>

<div class="page-content-title txt-center">
    <h3><i class="fa fa-user-plus"></i> Sells Tracker</h3> 
</div> 
<div id="">
      <form role="form" id="sellerReportForm" method='post' action="javascript:getSellerReport();" class="notify-forms">
    <div class="col-md-3 padding15">
        <div class="form-group">                         
            <select name="admin_id" id="admin_id" class="form-control" required="" data-parsley-required-message="Please Select Admin">
                <option value="">Select Admin Name</option>
                <?php
                if (isset($admin_names) && $admin_names) {
                    foreach ($admin_names as $admin_name) {
                        ?>
                        <option value="<?php echo $admin_name['admin_id']; ?>"><?php echo $admin_name['admin_name']; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div> 
    </div>
    <div class="col-md-3 padding15">
        <div class="form-group">                         
            <select name="txn_type" id="txn_type" class="form-control" required="" data-parsley-required-message="Please Select User Type">
                <option value="0">Select Type</option>

                <option value="Add">Add</option>
                <option value="Reduce">Reduce</option>
            </select>
        </div>  
    </div>

    <div class="col-md-4 padding15">
        <div class="input-daterange input-group" id="datepicker">
            <input type="text" class="form-control datepicker" name="by_from_date" id="by_from_date" placeholder="YYYY-MM--DD" />
            <span class="input-group-addon">to</span>
            <input type="text" class="form-control datepicker" name="by_to_date" id="by_to_date" placeholder="YYYY-MM--DD" />
        </div>
    </div>

          
    <div class="col-md-1 padding15">
        <div class="form-group">   
            <button name="get_report_btn" id="get_report_btn" class="btn btn-primary"
                    data-loading-text="Searching..." autocomplete="off" type="submit">
                Get Report
            </button>
        </div>                        
    </div>
     </form>

    <div class="col-md-12 padding15" id="get_seller_reports"> </div>


