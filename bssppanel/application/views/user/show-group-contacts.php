<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />
<?php
$extra_column_ids_array = array();
$extra_column_names_array = array();
$extra_column_types_array = array();
$extra_column_status_array = array();
if (isset($contact_group) && $contact_group) {
    if ($contact_group->extra_column_ids != "") {
        $extra_column_ids_array = explode('|', $contact_group->extra_column_ids);
        $extra_column_names_array = explode('|', $contact_group->extra_column_names);
        $extra_column_types_array = explode('|', $contact_group->extra_column_types);
        $extra_column_status_array = explode('|', $contact_group->extra_column_status);
    }
}
?>
<!--<div class="portlet">-->
<div class="row">
    <div class="col-md-12 padding0">
        <div class="row">
            <form role="form" class="tab-forms" id="updateGroupForm" data-parsley-validate method='post' action="javascript:updateGroupName(<?php echo $group_id; ?>);">
                <div class="col-md-2 col-xs-4  padding0">
                    <div class="input-group">
                        <input type="hidden" id="group_id" value="<?php echo $group_id; ?>" />
                        <input type="hidden" id="contact_group_name" value="<?php echo $group_name; ?>" />
                        <input type="text" name="update_group_name" id="update_group_name" value="<?php echo $group_name; ?>"
                               data-parsley-required-message="Please Enter Group Name" value="" required="" class="form-control input-sm" 
                               placeholder="Enter Group Name"/>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary btn-sm" id="btnupgroup" 
                                    data-loading-text="<i class='fa fa-spinner'></i>" autocomplete="off">
                                <i class="fa fa-check"></i>                                
                            </button>
                        </span>
                    </div>
                </div>

                <div class="col-md-2 col-xs-4">
                    <?php
//                    $size_contact = sizeof($contacts);
//                    if ($size_contact == 0) {
                    ?>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteGroup(<?php echo $group_id; ?>);" data-toggle="tooltip" data-placement="bottom"
                            title="Delete Group">
                        <i class="fa fa-trash-o"></i>
                    </button>
                    <?php //} ?>
                    <?php if (isset($total_records) && $total_records) { ?>
                        <a class="btn btn-info btn-sm" title="Export Group Contacts" href="<?php echo base_url(); ?>export/group_contacts/<?php echo $user_id . "/" . $group_id; ?>"
                           data-toggle="tooltip" data-placement="bottom">
                            <i class="fa fa-download"></i>
                        </a>
                    <?php } ?>
                </div>
                <div class="col-md-8 hidden" id="delete_option">
                    <input type="hidden" name="selected_contacts" id="selected_contacts" value="" />
                    <input type="button" id="delete_button" class="btn btn-danger btn-sm pull-right" value="Delete Contacts" 
                           onclick="deleteContacts(<?php echo $group_id; ?>);" />
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12" style="float: right;">
                    <div class="input-group um-search">
                        <input class="form-control" placeholder="Search" type="text" id="search" onkeyup="searchGroupContact(this.value, <?php echo $group_id; ?>);" autocomplete="off">
                        <span class="input-group-addon">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 padding0">
                    <hr/>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-12 table-responsive ptb15 padding0 data_table" style="height: 470px; overflow-y: auto;" id="show_contacts">
                <table class="table table-hover bgf">
                    <thead>
                        <tr>
                            <th>
                                <label for="select_contacts" class="fancy-check">
                                    <input type="checkbox" id="select_contacts" onclick="selectAllContacts();" />
                                    <span></span>
                                </label>
                            </th>
                            <th>Mobile Number</th>
                            <th>Name</th>
<!--                            <th>Other Groups</th>-->
                            <?php
                            if (isset($extra_column_ids_array) && $extra_column_ids_array && sizeof($extra_column_ids_array)) {
                                foreach ($extra_column_names_array as $key => $extra_column) {
                                    if ($extra_column_status_array[$key]) {
                                        ?>
                                        <th>
                                            <?php echo ucfirst($extra_column); ?>
                                            <a href="javascript:void(0);" class="btn btn-default btn-xs" 
                                               onclick="deleteColumn(<?php echo $key . "," . $group_id . ", '" . $extra_column_ids_array[$key] . "', '" . $group_name . "'"; ?>);">X</a>
                                        </th>
                                        <?php
                                    }
                                }
                            }
                            ?>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($contacts) && $contacts) {
                            $i = 1;
                            $j = 0;
                            foreach ($contacts as $contact) {
                                $extra_column_id_array = explode('|', $contact['extra_column_ids']);
                                $extra_column_values_array = explode('|', $contact['extra_column_values']);
                                $j++;
                                ?>
                                <tr>
                                    <td>
                                        <label for="contact<?php echo $i; ?>" class="fancy-check">
                                            <input class="check_contact" type="checkbox" name="checkbox[]" id="contact<?php echo $i; ?>"
                                                   value="<?php echo $contact['contact_id']; ?>" onclick="showDeleteOption();" />
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo $contact['mobile_number']; ?></td>
                                    <td><?php echo $contact['contact_name']; ?></td>
<!--                                    <td><?php //echo $contact['group_name_string']; ?></td>-->
                                    <?php
                                    if (sizeof($extra_column_ids_array)) {
                                        foreach ($extra_column_names_array as $key => $extra_column) {
                                            if ($extra_column_status_array[$key]) {
                                                if (in_array($extra_column_ids_array[$key], $extra_column_id_array)) {
                                                    $search_key = array_search($extra_column_ids_array[$key], $extra_column_id_array);
                                                    ?>
                                                    <td>
                                                        <?php echo ($extra_column_values_array[$search_key] != "" && $extra_column_values_array[$search_key] != " ") ? $extra_column_values_array[$search_key] : "-"; ?>
                                                    </td>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <td>-</td>
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="<?php echo base_url(); ?>user/update_contact/<?php echo $contact['contact_id'] . "/" . $group_id; ?>"
                                           data-toggle="tooltip" data-placement="bottom" title="Edit Contact">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                            if (!$j) {
                                ?>
                                <tr>
                                    <td colspan="<?php echo sizeof($extra_column_ids_array) + 5; ?>" align="center">
                                        <strong>No Contact Exists!</strong>
                                    </td>
                                </tr>                                    
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="<?php echo sizeof($extra_column_ids_array) + 5; ?>" align="center">
                                    <strong>No Contact Exists!</strong>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php echo $paging; ?>
        </div>
    </div>
</div>
<!--</div>-->
<script type="text/javascript">
    $('.tab-forms').parsley();
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>