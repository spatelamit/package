<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="<?php echo (isset($subtab) && $subtab == "1") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="geVirtualTab('1');">Short Code- Keywords</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "2") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="geVirtualTab('2');">Long Code- Keywords</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "3") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="geVirtualTab('3');">Short Numbers</a>
    </li>
    <li class="<?php echo (isset($subtab) && $subtab == "4") ? 'active"' : '' ?>">
        <a href="javascript:void(0)" onclick="geVirtualTab('4');">Long Numbers</a>
    </li>
</ul>

<div class="panel-group panel-color visible-xs"></div>

<!-- Tab panes -->
<div class="tab-content bgf9">
    <div class="tab-pane fade in active">

        <?php if ($subtab == '1') { ?>
            <div class="table-responsive padding15">
                <table class="table table-hover bgf9">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Short Number</th>
                            <th>Number Type</th>
                            <th>Keyword</th>
                            <th>Date</th>
                            <th>Validity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="bgf7">
                        <?php
                        if ($keywords) {
                            $i = 1;
                            foreach ($keywords as $row) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $row['username']; ?>
                                        ( <?php echo ($row['parent_username'] == "") ? $row['admin_username'] : $row['parent_username']; ?> )
                                    </td>
                                    <td><?php echo $row['short_number']; ?></td>
                                    <td>
                                        <?php
                                        if ($row['short_number_type'] == 1) {
                                            echo "<label class='label label-success'>Shared</label>";
                                        } elseif ($row['short_number_type'] == 2) {
                                            echo "<label class='label label-warning'>Dedicated</label>";
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $row['short_keyword']; ?></td>
                                    <td><?php echo $row['short_keyword_date']; ?></td>
                                    <td><?php echo $row['short_keyword_expiry']; ?></td>
                                    <td>
                                        <?php if ($row['short_keyword_status']) { ?>
                                            <span class="label label-success">Approved</span> 
                                        <?php } else { ?>
                                            <span class="label label-danger">Disapproved</span> 
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="switch switch-success switch-sm round switch-inline">
                                            <?php
                                            if ($row['short_keyword_status']) {
                                                ?>
                                                <input type="checkbox" id="status<?php echo $i; ?>" checked
                                                       onclick="changeSLStatus(<?php echo $row['short_keyword_id']; ?>, 0, '<?php echo $subtab; ?>');" />
                                                       <?php
                                                   } else {
                                                       ?>
                                                <input type="checkbox" id="status<?php echo $i; ?>" 
                                                       onclick="changeSLStatus(<?php echo $row['short_keyword_id']; ?>, 1, '<?php echo $subtab; ?>');" />
                                                       <?php
                                                   }
                                                   ?>
                                            <label for="status<?php echo $i; ?>"></label>
                                        </div>
                                        <!--
                                                <button onclick="deleteSLData('<?php echo $row['short_keyword_id']; ?>', <?php echo $subtab; ?>);"
                                                        type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Short Keyword">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                        -->
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="8" align="center">
                                    <strong>No Keyword!</strong>
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
        <?php } ?>

        <?php if ($subtab == '2') { ?>
            <div class="table-responsive padding15">
                <table class="table table-hover bgf9">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Long Number</th>
                            <th>Number Type</th>
                            <th>Keyword</th>
                            <th>Date</th>
                            <th>Validity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="bgf7">
                        <?php
                        if ($keywords) {
                            $i = 1;
                            foreach ($keywords as $row) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $row['username']; ?>
                                        ( <?php echo ($row['parent_username'] == "") ? $row['admin_username'] : $row['parent_username']; ?> )
                                    </td>
                                    <td><?php echo $row['long_number']; ?></td>
                                    <td>
                                        <?php
                                        if ($row['long_number_type'] == 1) {
                                            echo "<label class='label label-success'>Shared</label>";
                                        } elseif ($row['long_number_type'] == 2) {
                                            echo "<label class='label label-warning'>Dedicated</label>";
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $row['long_keyword']; ?></td>
                                    <td><?php echo $row['long_keyword_date']; ?></td>
                                    <td><?php echo $row['long_keyword_expiry']; ?></td>
                                    <td>
                                        <?php if ($row['long_keyword_status']) { ?>
                                            <span class="label label-success">Approved</span> 
                                        <?php } else { ?>
                                            <span class="label label-danger">Disapproved</span> 
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="switch switch-success switch-sm round switch-inline">
                                            <?php
                                            if ($row['long_keyword_status']) {
                                                ?>
                                                <input type="checkbox" id="status<?php echo $i; ?>" checked
                                                       onclick="changeSLStatus(<?php echo $row['long_keyword_id']; ?>, 0, '<?php echo $subtab; ?>');" />
                                                       <?php
                                                   } else {
                                                       ?>
                                                <input type="checkbox" id="status<?php echo $i; ?>" 
                                                       onclick="changeSLStatus(<?php echo $row['long_keyword_id']; ?>, 1, '<?php echo $subtab; ?>');" />
                                                       <?php
                                                   }
                                                   ?>
                                            <label for="status<?php echo $i; ?>"></label>
                                        </div>
                                        <!--
                                                <button onclick="deleteSLData('<?php echo $row['long_keyword_id']; ?>', <?php echo $subtab; ?>);"
                                                        type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Long Keyword">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                        -->
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="8" align="center">
                                    <strong>No Keyword!</strong>
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
        <?php } ?>

        <?php if ($subtab == '3') { ?>
            <div class="row">    
                <div class="col-md-3 padding15">
                    <form role="form" id="virtual_data1" class="settingClass" method='post' action="javascript:saveNumber('short', 1, <?php echo $subtab; ?>);">
                        <div class="form-group">
                            <label for="virtual_number">Add Short Number</label>
                            <input type="text" name="virtual_number" id="virtual_number" value="" placeholder="Enter Short Number" class="form-control"
                                   required="" data-parsley-error-message="Please Enter Valid Number" data-parsley-type="integer"
                                   data-parsley-minlength="6" data-parsley-maxlength="6" />
                        </div>
                        <div class="form-group">
                            <label for="number_type">Number Type</label>
                            <div class="input-group">
                                <label for="number_type1" class="fancy-check">
                                    <input type="radio" name="number_type" id="number_type1" value="1" />
                                    <span>Shared</span>
                                </label>
                                <label for="number_type2" class="fancy-check">
                                    <input type="radio" name="number_type" id="number_type2" value="2" />
                                    <span>Dedicated</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="btn_short" data-loading-text="Processing..." autocomplete="off">
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
                                    <th>Short Number</th>
                                    <th>Number Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php
                                if ($numbers) {
                                    $i = 1;
                                    foreach ($numbers as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['short_number']; ?></td>
                                            <td>
                                                <?php
                                                if ($row['short_number_type'] == 1) {
                                                    echo "<label class='label label-success'>Shared</label>";
                                                } elseif ($row['short_number_type'] == 2) {
                                                    echo "<label class='label label-warning'>Dedicated</label>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="switch switch-success switch-sm round switch-inline">
                                                    <?php
                                                    if ($row['short_number_status']) {
                                                        ?>
                                                        <input type="checkbox" id="status<?php echo $i; ?>" checked
                                                               onclick="changeSLStatus(<?php echo $row['short_number_id']; ?>, 0, '<?php echo $subtab; ?>');" />
                                                               <?php
                                                           } else {
                                                               ?>
                                                        <input type="checkbox" id="status<?php echo $i; ?>"
                                                               onclick="changeSLStatus(<?php echo $row['short_number_id']; ?>, 1, '<?php echo $subtab; ?>');" />
                                                               <?php
                                                           }
                                                           ?>
                                                    <label for="status<?php echo $i; ?>"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <button onclick="deleteSLData('<?php echo $row['short_number_id']; ?>', <?php echo $subtab; ?>);"
                                                        type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Short Number">
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
                                        <td colspan="4" align="center">
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

        <?php if ($subtab == '4') { ?>
            <div class="row">
                <div class="col-md-3 padding15">
                    <form role="form" class="settingClass" id="virtual_data2" method='post' action="javascript:saveNumber('long', 2, <?php echo $subtab; ?>);">
                        <div class="form-group">
                            <label for="virtual_number">Add Long Number</label>
                            <input type="text" name="virtual_number" id="virtual_number" value="" placeholder="Enter Long Number" class="form-control"
                                   required="" data-parsley-error-message="Please Enter Valid Number" data-parsley-type="integer"
                                   data-parsley-minlength="6" data-parsley-maxlength="6" />
                        </div>
                        <div class="form-group">
                            <label for="number_type">Number Type</label>
                            <div class="input-group">
                                <label for="number_type1" class="fancy-check">
                                    <input type="radio" name="number_type" id="number_type1" value="1" />
                                    <span>Shared</span>
                                </label>
                                <label for="number_type2" class="fancy-check">
                                    <input type="radio" name="number_type" id="number_type2" value="2" />
                                    <span>Dedicated</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="btn_long" data-loading-text="Processing..." autocomplete="off">
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
                                    <th>Long Number</th>
                                    <th>Number Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="bgf7">
                                <?php
                                if ($numbers) {
                                    $i = 1;
                                    foreach ($numbers as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['long_number']; ?></td>
                                            <td>
                                                <?php
                                                if ($row['long_number_type'] == 1) {
                                                    echo "<label class='label label-success'>Shared</label>";
                                                } elseif ($row['long_number_type'] == 2) {
                                                    echo "<label class='label label-warning'>Dedicated</label>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="switch switch-success switch-sm round switch-inline">
                                                    <?php
                                                    if ($row['long_number_status']) {
                                                        ?>
                                                        <input type="checkbox" id="status<?php echo $i; ?>" checked
                                                               onclick="changeSLStatus(<?php echo $row['long_number_id']; ?>, 0, '<?php echo $subtab; ?>');" />
                                                               <?php
                                                           } else {
                                                               ?>
                                                        <input type="checkbox" id="status<?php echo $i; ?>" 
                                                               onclick="changeSLStatus(<?php echo $row['long_number_id']; ?>, 1, '<?php echo $subtab; ?>');" />
                                                               <?php
                                                           }
                                                           ?>
                                                    <label for="status<?php echo $i; ?>"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <button onclick="deleteSLData('<?php echo $row['long_number_id']; ?>', <?php echo $subtab; ?>);"
                                                        type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Long Number">
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
                                        <td colspan="4" align="center">
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
        $('[data-toggle="tooltip"]').tooltip()
    });
    $('#validate-basic').parsley();
    $('.settingClass').parsley();
</script>