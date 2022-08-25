<script src="<?php echo base_url(); ?>Assets/admin/js/highcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>Assets/admin/js/exporting.js" type="text/javascript"></script>
<div class="page-content-title txt-center">
    <h3><i class="fa fa-history" aria-hidden="true"></i> Controller History</h3> 
</div>
<div id="settings">
    <div class="horizontal-tabs tab-color padding15" id="controller_history">
       <?php echo (isset($table) && $table) ? $table : ''; ?>
    </div>
</div>