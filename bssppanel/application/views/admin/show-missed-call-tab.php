<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active' : '' ?>">
        <a href="javascript:void(0)" onclick="getMissedCallTab('1');">Service Numbers</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active' : '' ?>">
        <a href="javascript:void(0)" onclick="getMissedCallTab('2');">Assign Service Numbers</a>
    </li>
</ul>

<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active">

        <?php if (isset($subtab) && $subtab == '1') { ?>
            <div class="row">
                <div class="col-md-3 padding15">
                    <form role="form" id="missed_call<?php echo $subtab; ?>" class="settingClass" method='post' action="javascript:saveMissedCall(<?php echo $subtab; ?>);">
                        <div class="form-group">
                            <label for="service_number">Add Service Number</label>
                            <input type="text" name="service_number" id="service_number" value="" placeholder="Enter Service Number" class="form-control"
                                   required="" data-parsley-error-message="Please Enter Valid Number" data-parsley-type="integer" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="btn_missed_call<?php echo $subtab; ?>" data-loading-text="Processing..." autocomplete="off">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-9">
                    <div class="table-responsive">
                        <table class="table table-hover bgf9">
                            <thead>
                                <tr>
                                    <th>Service Number</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php
                                if (isset($missed_call) && $missed_call) {
                                    $i = 1;
                                    foreach ($missed_call as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['mc_number']; ?></td>
                                            <td>
                                                <div class="switch switch-success switch-sm round switch-inline">
                                                    <?php
                                                    if ($row['mc_number_status']) {
                                                        ?>
                                                        <input type="checkbox" id="status<?php echo $i; ?>" checked
                                                               onclick="statusMissedCall(<?php echo $row['mc_number_id']; ?>, 0, '<?php echo $subtab; ?>');" />
                                                               <?php
                                                           } else {
                                                               ?>
                                                        <input type="checkbox" id="status<?php echo $i; ?>" 
                                                               onclick="statusMissedCall(<?php echo $row['mc_number_id']; ?>, 1, '<?php echo $subtab; ?>');" />
                                                               <?php
                                                           }
                                                           ?>
                                                    <label for="status<?php echo $i; ?>"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <button onclick="deleteMissedCall('<?php echo $row['mc_number_id']; ?>', <?php echo $subtab; ?>);"
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
                                        <td colspan="3" align="center">
                                            <strong>No Number!</strong>
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
            </div>
        <?php } ?>

        <?php if (isset($subtab) && $subtab == '2') { ?>
            <div class="row">
                <div class="col-md-3 padding15">
                    <form role="form" id="missed_call<?php echo $subtab; ?>" class="settingClass" method='post' action="javascript:saveMissedCall(<?php echo $subtab; ?>);">
                        <div class="form-group">
                            <label for="select_user">Select User</label>
                            <select id="select_user" class="form-control" data-live-search="true" name="select_user"
                                    required="" data-parsley-error-message="Please Select User">
                                <option value="">Select User</option>
                                <?php
                                if (isset($users) && $users) {
                                    foreach ($users as $key => $user) {
                                        ?>
                                        <option value="<?php echo $user['user_id']; ?>"><?php echo $user['username']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="service_number">Select Service Number</label>
                            <select class="form-control" name="service_number" id="service_number"
                                    required="" data-parsley-error-message="Please Select Service Number">
                                <option value="">Select Service Number</option>
                                <?php
                                if (isset($service_numbers) && $service_numbers) {
                                    foreach ($service_numbers as $key => $service_number) {
                                        ?>
                                        <option value="<?php echo $service_number['mc_number_id']; ?>"><?php echo $service_number['mc_number']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="validity_date">Validity</label>
                            <input type="text" name="validity_date" id="validity_date" value="" placeholder="Enter Validity Date" class="form-control"
                                   required="" data-parsley-error-message="Please Enter Validity Date" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="btn_missed_call<?php echo $subtab; ?>" data-loading-text="Processing..." autocomplete="off">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-9">
                    <div class="table-responsive">
                        <table class="table table-hover bgf9">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Service Number</th>
                                    <th>Validity</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php
                                if (isset($missed_call) && $missed_call) {
                                    $i = 1;
                                    foreach ($missed_call as $row) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $row['username']; ?>
                                                ( <?php echo ($row['parent_username'] == "") ? $row['admin_username'] : $row['parent_username']; ?> )
                                            </td>
                                            <td><?php echo $row['mc_number']; ?></td>
                                            <td><?php echo $row['mc_service_expiry']; ?></td>
                                            <td>
                                                <div class="switch switch-success switch-sm round switch-inline">
                                                    <?php
                                                    if ($row['mc_service_status']) {
                                                        ?>
                                                        <input type="checkbox" id="status<?php echo $i; ?>" checked
                                                               onclick="statusMissedCall(<?php echo $row['mc_service_id']; ?>, 0, '<?php echo $subtab; ?>');" />
                                                               <?php
                                                           } else {
                                                               ?>
                                                        <input type="checkbox" id="status<?php echo $i; ?>" 
                                                               onclick="statusMissedCall(<?php echo $row['mc_service_id']; ?>, 1, '<?php echo $subtab; ?>');" />
                                                               <?php
                                                           }
                                                           ?>
                                                    <label for="status<?php echo $i; ?>"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <button onclick="deleteMissedCall('<?php echo $row['mc_service_id']; ?>', <?php echo $subtab; ?>);"
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
                                        <td colspan="5" align="center">
                                            <strong>No Number!</strong>
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
            </div>
        <?php } ?>

    </div>
</div>
<script type="text/javascript">
    $(function () {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
        $('#validity_date').datepicker({
            format: "dd-mm-yyyy",
            startDate: today,
            autoclose: true,
            todayHighlight: true
        });
        $('[data-toggle="tooltip"]').tooltip()
        $('.settingClass').parsley();
        $('#select_user').selectpicker({
            liveSearch: true,
            maxOptions: 1
        });
    });
</script>