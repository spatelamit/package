<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSMSRateTab('1');">SMS Rates</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="getSMSRateTab('2');"><?php echo (isset($sms_rate) && $sms_rate) ? 'Update' : 'Add'; ?> SMS Rate</a>
    </li>
</ul>
<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active">

        <?php if (isset($subtab) && $subtab == '1') { ?>
            <div class="row">
                <div class="table-responsive" id="show_account_managers">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Service Type</th>
                                <th>Min. SMS</th>
                                <th>Max. SMS</th>
                                <th>Price/SMS (In Paisa)</th>
                                <th>Status</th>
                                <th colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bgf7">
                            <?php
                            if (isset($sms_rates) && $sms_rates) {
                                $i = 1;
                                foreach ($sms_rates as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['rate_plan_name']; ?></td>
                                        <td>
                                            <?php
                                            if ($row['service_type'] == 'A')
                                                echo '<label class="label label-success">Promotional Text SMS</label>';
                                            if ($row['service_type'] == 'B')
                                                echo '<label class="label label-info">Transactional Text SMS</label>';
                                            if ($row['service_type'] == 'VA')
                                                echo '<label class="label label-danger">Promotional Voice SMS</label>';
                                            if ($row['service_type'] == 'VB')
                                                echo '<label class="label label-warning">Dynamic Voice SMS</label>';
                                            if ($row['service_type'] == 'Short')
                                                echo '<label class="label label-primary">Short Code</label>';
                                            if ($row['service_type'] == 'Long')
                                                echo '<label class="label label-default">Long Code</label>';
                                            ?>
                                        </td>
                                        <td><?php echo $row['rate_plan_min']; ?></td>
                                        <td><?php echo $row['rate_plan_max']; ?></td>
                                        <td><?php echo $row['rate_plan_price']; ?></td>
                                        <td>
                                            <div class="switch switch-success switch-sm round switch-inline">
                                                <?php
                                                if ($row['rate_plan_status']) {
                                                    ?>
                                                    <input type="checkbox" id="status<?php echo $i; ?>" checked
                                                           onclick="changeSMSRateStatus('<?php echo $row['rate_plan_id']; ?>', 0);" />
                                                           <?php
                                                       } else {
                                                           ?>
                                                    <input type="checkbox" id="status<?php echo $i; ?>" 
                                                           onclick="changeSMSRateStatus('<?php echo $row['rate_plan_id']; ?>', 1);" />
                                                           <?php
                                                       }
                                                       ?>
                                                <label for="status<?php echo $i; ?>"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <button onclick="updateSMSRate('<?php echo $row['rate_plan_id']; ?>');"
                                                    type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button onclick="deleteSMSRate('<?php echo $row['rate_plan_id']; ?>');"
                                                    type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="8" align="center">
                                        <strong>No SMS Rate</strong>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <?php echo $paging; ?>
                </div>
            </div>
        <?php } ?>

        <?php
        if (isset($subtab) && $subtab == '2') {
            if (isset($sms_rate) && $sms_rate) {
                ?>
                <div class="row">
                    <form role="form" class="tab-forms" id="addSMSRateForm" data-parsley-validate method='post' 
                          action="javascript:saveSMSRate(<?php echo (isset($sms_rate) && $sms_rate) ? $sms_rate->rate_plan_id : ''; ?>);">
                        <div class="col-md-4 padding0">
                            <div class="form-group">
                                <label>Plan Name</label>
                                <input type="text" class="form-control" name="rate_plan_name" id="rate_plan_name" placeholder="Please Enter Name" required=""
                                       data-parsley-required-message="Please Enter Plan Name" 
                                       value="<?php echo (isset($sms_rate) && $sms_rate) ? $sms_rate->rate_plan_name : ''; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Service Type</label>
                                <select class="form-control" name="service_type" id="service_type" data-parsley-required-message="Please Select Service Type" required="">
                                    <option value="">Select Service Type</option>
                                    <option value="A" <?php echo (isset($sms_rate) && $sms_rate && $sms_rate->service_type == 'A') ? 'selected' : ''; ?>>Promotional Route</option>
                                    <option value="B" <?php echo (isset($sms_rate) && $sms_rate && $sms_rate->service_type == 'B') ? 'selected' : ''; ?>>Transactional Route</option>
                                    <option value="Long" <?php echo (isset($sms_rate) && $sms_rate && $sms_rate->service_type == 'Long') ? 'selected' : ''; ?>>Long Code</option>
                                    <option value="Short" <?php echo (isset($sms_rate) && $sms_rate && $sms_rate->service_type == 'Short') ? 'selected' : ''; ?>>Short Code</option>
                                    <option value="VA" <?php echo (isset($sms_rate) && $sms_rate && $sms_rate->service_type == 'VA') ? 'selected' : ''; ?>>Voice Promotional</option>
                                    <option value="VB" <?php echo (isset($sms_rate) && $sms_rate && $sms_rate->service_type == 'VB') ? 'selected' : ''; ?>>Voice Dynamic</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Min. SMS</label>
                                <input type="text" class="form-control" name="rate_plan_min" id="rate_plan_min" data-parsley-type="integer"
                                       placeholder="Please Enter Min. SMS" required="" value="<?php echo (isset($sms_rate) && $sms_rate) ? $sms_rate->rate_plan_min : ''; ?>"
                                       data-parsley-min="1" data-parsley-min-message="Number of SMS Must Be Greater Than 0"
                                       data-parsley-type-message="Please Enter Valid Valid Number of SMS" data-parsley-required-message="Please Enter Min. SMS" />
                            </div>
                            <div class="form-group">
                                <label>Max. SMS</label>
                                <input type="text" class="form-control" name="rate_plan_max" id="rate_plan_max" data-parsley-type="integer"
                                       placeholder="Please Enter Max. SMS" required="" value="<?php echo (isset($sms_rate) && $sms_rate) ? $sms_rate->rate_plan_max : ''; ?>"
                                       data-parsley-min="1" data-parsley-min-message="Number of SMS Must Be Greater Than 0"
                                       data-parsley-type-message="Please Enter Valid Valid Number of SMS" data-parsley-required-message="Please Enter Max. SMS" />
                            </div>
                            <div class="form-group">
                                <label>SMS Price</label>
                                <input type="text" class="form-control" name="rate_plan_price" id="rate_plan_price" data-parsley-type="number"
                                       placeholder="Please Enter SMS Price" required="" data-parsley-min="0.01"
                                       data-parsley-min-message="SMS Price Must Be Greater Than 0" value="<?php echo (isset($sms_rate) && $sms_rate) ? $sms_rate->rate_plan_price : ''; ?>"
                                       data-parsley-type-message="Please Enter Valid SMS Price" data-parsley-required-message="Please Enter SMS Price" />
                            </div>
                        </div>
                        <div class="col-md-12 padding0 form-group">
                            <button type="submit" class="btn btn-primary btn-sm" name="save" id="save" data-loading-text="Processing..."
                                    autocomplete="off">Update</button>
                        </div>
                    </form>                    
                </div>
                <?php
            } else {
                ?>
                <div class="row">
                    <form role="form" class="tab-forms" id="addSMSRateForm" data-parsley-validate method='post' action="javascript:saveSMSRate(0);">
                        <div class="col-md-4 padding0">
                            <div class="form-group">
                                <label>Plan Name</label>
                                <input type="text" class="form-control" name="rate_plan_name" id="rate_plan_name" placeholder="Please Enter Name" required=""
                                       data-parsley-required-message="Please Enter Plan Name" value="" />
                            </div>
                            <div class="form-group">
                                <label>Service Type</label>
                                <select class="form-control" name="service_type" id="service_type" data-parsley-required-message="Please Select Service Type" required="">
                                    <option value="">Select Service Type</option>
                                    <option value="A">Promotional Route</option>
                                    <option value="B">Transactional Route</option>
                                    <option value="Long">Long Code</option>
                                    <option value="Short">Short Code</option>
                                    <option value="VA">Voice Promotional</option>
                                    <option value="VB">Voice Dynamic</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Min. SMS</label>
                                <input type="text" class="form-control" name="rate_plan_min" id="rate_plan_min" data-parsley-type="integer"
                                       placeholder="Please Enter Min. SMS" required="" value=""
                                       data-parsley-min="1" data-parsley-min-message="Number of SMS Must Be Greater Than 0"
                                       data-parsley-type-message="Please Enter Valid Number of SMS" data-parsley-required-message="Please Enter Min. SMS" />
                            </div>
                            <div class="form-group">
                                <label>Max. SMS</label>
                                <input type="text" class="form-control" name="rate_plan_max" id="rate_plan_max" data-parsley-type="integer"
                                       placeholder="Please Enter Max. SMS" required="" value=""
                                       data-parsley-min="1" data-parsley-min-message="Number of SMS Must Be Greater Than 0"
                                       data-parsley-type-message="Please Enter Valid Number of SMS" data-parsley-required-message="Please Enter Max. SMS" />
                            </div>
                            <div class="form-group">
                                <label>SMS Price</label>
                                <input type="text" class="form-control" name="rate_plan_price" id="rate_plan_price" data-parsley-type="number"
                                       placeholder="Please Enter SMS Price" required="" data-parsley-min="0.01"
                                       data-parsley-min-message="SMS Price Must Be Greater Than 0" value=""
                                       data-parsley-type-message="Please Enter Valid SMS Price" data-parsley-required-message="Please Enter SMS Price" />
                            </div>
                        </div>
                        <div class="col-md-12 padding0">
                            <button type="submit" class="btn btn-primary btn-sm" name="save" id="save" data-loading-text="Processing..."
                                    autocomplete="off">Save</button>
                        </div>
                    </form>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
        $('#expiry_date').datepicker({
            format: "dd-mm-yyyy",
            startDate: today,
            autoclose: true,
            todayHighlight: true
        });
    });
    $('.tab-forms').parsley();

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>