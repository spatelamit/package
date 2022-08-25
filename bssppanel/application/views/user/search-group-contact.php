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