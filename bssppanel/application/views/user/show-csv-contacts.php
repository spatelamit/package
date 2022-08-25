<?php
// Read CSV File
$csvFile = "./ContactCSV/" . $temp_file_name;
$filehandle = fopen($csvFile, "r");
$first = array();
$flag = 0;
while ($line = fgetcsv($filehandle)) {
    //$line = explode(',', $array);
    foreach ($line as $value) {
      
        if (is_numeric($value)) {
            if (strlen($value) == 10 || strlen($value) == 12) {
                $flag = 1;
            }
        }
    }
    if ($flag) {
        break;
    } else {
        continue;
    }
}
fclose($filehandle);
// Contact Groups Extra Columns
$extra_column_names_array = array();
$extra_column_types_array = array();
$extra_column_ids_array = array();
$extra_column_status_array = array();
if (isset($extra_fields) && $extra_fields) {
    foreach ($extra_fields as $key => $extra) {
        if ($extra->extra_column_names != null) {
            $extra_column_ids_array = array_merge($extra_column_ids_array, explode('|', $extra->extra_column_ids));
            $extra_column_names_array = array_merge($extra_column_names_array, explode('|', $extra->extra_column_names));
            $extra_column_types_array = array_merge($extra_column_types_array, explode('|', $extra->extra_column_types));
            $extra_column_status_array = array_merge($extra_column_status_array, explode('|', $extra->extra_column_status));
        }
    }
}
// Form Start
$data = array('id' => "validate-basic", 'class' => "form parsley-form", 'onsubmit' => "return validateForm()");
echo form_open('user/save_csv_contact', $data);
?>
<div class="form-group col-md-12">
    <label for="groups">Choose Group (you can choose multiple group)</label>
</div>
<div class="form-group col-md-8">
    <select name="groups[]" id="groups" multiple required="" class="form-control" data-parsley-required-message="Please choose at least one group">
        <?php
        if (isset($contact_groups) && $contact_groups) {
            foreach ($contact_groups as $group) {
                ?>
                <option value = "<?php echo $group['contact_group_id']; ?>"><?php echo $group['contact_group_name']; ?></option>
                <?php
            }
        }
        ?>
    </select>
</div>
<div class="form-group col-md-4 text-center">
    <input type="hidden" name="temp_file_name" id="temp_file_name" value="<?php echo $temp_file_name; ?>" />
    <input type="hidden" name="total_columns" id="total_columns" value="<?php echo sizeof($line); ?>" />
    <button type="submit" class="btn btn-primary" name="import_contacts">Import Contacts</button>
</div>
<div class="form-group col-md-12">
    <label>Categorize your csv columns:-</label>
    <p>
        In order to maintain the flow you must choose a suitable category for which the column belongs to.
    </p>
    <!--
    <p>
        * Preferred date Format is dd-mm-yyyy (eg : 30-03-2015)
    </p>
    -->
    <p>
        <strong>Note: </strong>If you don't want to import particular column then unselect the checkbox.
    </p>
</div>
<div class="form-group col-md-12">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>CSV Columns</th>
                <th colspan="3">Field Name</th>
                <th>Field Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($line as $key => $value) {
                ?>
                <tr>
                    <td>
                        <?php echo $value; ?>
                        <input type="hidden" name="field_value<?php echo $i; ?>" id="field_value<?php echo $i; ?>" value="<?php echo $value; ?>" />
                    </td>
                    <td colspan="3" id="field_col<?php echo $i; ?>" class="">
                        <select name="field_name<?php echo $i; ?>" id="field_name<?php echo $i; ?>" class="form-control fields" required=""
                                data-parsley-required-message="Please select field for this column"
                                onchange="checkDuplicateField(this.value, <?php echo $i; ?>);
                                        addNewField(this.value, <?php echo $i; ?>);">
                            <option value="">Select Field</option>
                            <option value="number">Contact Number</option>
                            <option value="name">Contact Name</option>
                            <?php
                            if (sizeof($extra_column_names_array)) {
                                $column_array = array();
                                foreach ($extra_column_names_array as $key => $column) {
                                    if ($extra_column_status_array[$key]) {
                                        if (!in_array($column, $column_array)) {
                                            $column_array[] = $column;
                                            ?>
                                            <option value="<?php echo $extra_column_ids_array[$key] . "|" . $extra_column_names_array[$key] . "|" . $extra_column_types_array[$key] . "|" . $extra_column_status_array[$key]; ?>"><?php echo ucfirst($column); ?></option>
                                            <?php
                                        }
                                    }
                                }
                            }
                            ?>
                            <option value="_add_">Add New Field</option>
                        </select>
                    </td>
                    <td id="col_fname<?php echo $i; ?>" class="hidden">
                        <input type="text" name="new_field_name<?php echo $i; ?>" id="new_field_name<?php echo $i; ?>" class="form-control input-sm" 
                               placeholder="Enter Field Name" />
                        <input type="hidden" name="new_field_id<?php echo $i; ?>" id="new_field_id<?php echo $i; ?>" />
                    </td>
                    <td id="col_ftype<?php echo $i; ?>" class="hidden">
                        <select name="new_field_type<?php echo $i; ?>" id="new_field_type<?php echo $i; ?>" class="form-control input-sm">
                            <option value="number">Number</option>
                            <option value="text">Text</option>
                        </select>
                    </td>
                    <td id="col_rbutton<?php echo $i; ?>" class="hidden">
                        <button id="remove_btn<?php echo $i; ?>" type="button" class="btn btn-danger btn-sm"
                                onclick="removeNewField(<?php echo $i; ?>);">X</button>
                    </td>
                    <td>
                        <input type="hidden" name="field_type<?php echo $i; ?>" id="field_type<?php echo $i; ?>" value="<?php echo (is_numeric($value) ? 'Number' : 'Text'); ?>" />
                        <?php echo (is_numeric($value) ? 'Number' : 'Text'); ?>
                    </td>
                    <td>
                        <label for="action<?php echo $i; ?>" class="fancy-check">
                            <input type="checkbox" name="action<?php echo $i; ?>" id="action<?php echo $i; ?>" value="1" checked=""
                                   onclick="takeAction(<?php echo $i; ?>);" />
                            <span>Import</span>
                        </label>
                    </td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
</div>
<!--<a href="<?php //echo base_url(); ?>/user/phonebook" role="button" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
-->
</form>

<script type="text/javascript">
    function takeAction(i)
    {
        if ($('#action' + i).prop('checked'))
        {
            $("#field_name" + i).attr("required", "");
            $("#field_name" + i).attr("data-parsley-required-message", "Please select field for this column");
        } else
        {
            $("#field_name" + i).removeAttr("required");
            $("#field_name" + i).removeAttr("data-parsley-required-message");
            $("#field_name" + i).val("");
        }
    }

    function validateForm()
    {
        $('span#notification').html("");
        var i = 0, j = 1;
        var field_type = "", field_value = "";
        $('.fields').each(function (index, value)
        {
            if ($(value).val() === 'number')
            {
                field_type = $('#field_type' + j).val();
                field_value = $('#field_value' + j).val();
                i++;
            }
            j++;
        });
        if (i === 0)
        {
            $('span#notification').removeClass("notification alert-danger");
            $('span#notification').addClass("notification alert-danger");
            $('span#notification').html('Error: Please select contact number field!');
            return false;
        }
        if (field_type !== 'Number')
        {
            $('span#notification').removeClass("notification alert-danger");
            $('span#notification').addClass("notification alert-danger");
            $('span#notification').html('Error: Please select valid contact number field! It should be number type!');
            return false;
        }
        if (field_value.length !== 10 && field_value.length !== 12)
        {
            $('span#notification').removeClass("notification alert-danger");
            $('span#notification').addClass("notification alert-danger");
            $('span#notification').html('Error: Please select valid contact number field! It should be 10 or 12 digits long!');
            return false;
        }
        
    }

    function checkDuplicateField(value, number)
    {
        $('span#notification').html("");
        $('span#notification').removeClass("notification alert-danger");
        var i = 1;
        $('.fields').each(function (index, value1)
        {
            if (i !== number)
            {
                if ($(value1).val() === value && value != '_add_')
                {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html('Error: Duplicate fields are not allowed');
                    $('#field_name' + number).val("");
                }
            }
            i++;
        });
    }

    $('#validate-basic').parsley();

    $(document).ready(function () {
        $('#groups').multiselect({
            maxHeight: 200,
            //includeSelectAllOption: true,
            //selectAllText: 'Select all',
            enableFiltering: true
        });
    });

    function addNewField(value, number)
    {
        if (value === '_add_')
        {
            var currentDate = new Date();
            var day = currentDate.getDate();
            var month = currentDate.getMonth() + 1;
            var year = currentDate.getFullYear();
            var hrs = currentDate.getHours();
            var min = currentDate.getMinutes();
            var sec = currentDate.getMilliseconds();
            var date = day + "" + month + "" + year + "" + hrs + "" + min + "" + sec;
            $('#new_field_id' + number).val(date);
            $('#field_col' + number).addClass('hidden');
            $('#col_fname' + number).removeClass('hidden');
            $('#col_ftype' + number).removeClass('hidden');
            $('#col_rbutton' + number).removeClass('hidden');
        }
    }

    function removeNewField(number)
    {
        $('#new_field_id' + number).val('');
        $('#field_col' + number).removeClass('hidden');
        $('#col_fname' + number).addClass('hidden');
        $('#col_ftype' + number).addClass('hidden');
        $('#col_rbutton' + number).addClass('hidden');
        $('#field_name' + number).val('');
    }
</script>