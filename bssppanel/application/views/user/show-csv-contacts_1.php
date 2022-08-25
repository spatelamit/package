<?php
$csvFile = "./ContactCSV/" . $temp_file_name;
$filehandle = fopen($csvFile, "r");
$first = trim(fgets($filehandle));
$line = explode(',', $first);
fclose($filehandle);
$data = array('id' => "validate-basic", 'class' => "form parsley-form", 'onsubmit' => "return validateForm()");
echo form_open('user/save_csv_contact', $data);
?>
<div class="form-group col-md-12">
    <label for="groups">Choose Group (you can choose multiple group)</label>
</div>
<div class="form-group col-md-8">
    <select name="groups[]" id="groups" multiple required="" class="form-control" data-parsley-required-message="Please choose at least one group">
        <?php
        if ($contact_groups) {
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
        In order to maintain the flow you must choose a suitable category for which the column belongs to..
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
                    <td colspan="3" id="field_col<?php echo $i; ?>">
                        <select name="field_name[]" id="field_name<?php echo $i; ?>" class="form-control fields" required=""
                                data-parsley-required-message="Please select field for this column"
                                onchange="checkDuplicateField(this.value, <?php echo $i; ?>)">
                            <option value="">Select Field</option>
                            <option value="number">Contact Number</option>
                            <option value="name">Contact Name</option>
                            <option value="email">Email Address</option>
                            <option value="city">City</option>
                            <!--<option value="_add_">Add New Field</option>-->
                        </select>
                    </td>
                    <!--
                    <td id="newfn_col<?php echo $i; ?>" class="hidden">
                        <input type="text" name="new_field_name<?php echo $i; ?>" id="new_field_name<?php echo $i; ?>" value="" 
                               class="form-control" />
                    </td>
                    <td id="newft_col<?php echo $i; ?>" class="hidden">
                        <select name="new_field_type<?php echo $i; ?>" id="new_field_type<?php echo $i; ?>" class="form-control">
                            <option value="">Select Field Type</option>
                            <option value="number">Number</option>
                            <option value="text">Text</option>
                        </select>
                    </td>
                    <td id="newc_col<?php echo $i; ?>" class="hidden">
                        <button type="button" name="close<?php echo $i; ?>" id="close<?php echo $i; ?>" class="btn btn-default btn-xs"
                                onclick="removeNewField(<?php echo $i; ?>);">
                            <i class="fa fa-times"></i>
                        </button>
                    </td>
                    -->
                    <td>
                        <input type="hidden" name="field_type<?php echo $i; ?>" id="field_type<?php echo $i; ?>" 
                               value="<?php echo (is_numeric($value) ? 'Number' : 'Text'); ?>" />
                               <?php echo (is_numeric($value) ? 'Number' : 'Text'); ?>
                    </td>
                    <td>
                        <label for="action<?php echo $i; ?>" class="fancy-check">
                            <input type="checkbox" name="action<?php echo $i; ?>" id="action<?php echo $i; ?>" value="1" checked=""
                                   onclick="takeAction(<?php echo $i; ?>)" />
                            <span>Import</span>
                        </label>
                        <!--
                        <button type="button" name="action[]" id="action<?php echo $i; ?>" class="btn btn-danger btn-sm" 
                                onclick="selectAction(<?php echo $i . ", " . $key; ?>);">Don't Import</button>
                        -->
                    </td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
</div>
</form>

<script type="text/javascript">
    function takeAction(i)
    {
        if ($('#action' + i).prop('checked'))
        {
            $("#field_name" + i).attr("required", "");
            $("#field_name" + i).attr("data-parsley-required-message", "Please select field for this column");
        }
        else
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
        if (field_value.length !== 10)
        {
            $('span#notification').removeClass("notification alert-danger");
            $('span#notification').addClass("notification alert-danger");
            $('span#notification').html('Error: Please select valid contact number field! It should be 10 digits long!');
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
                if ($(value1).val() === value)
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

    function addNewField(number)
    {
        if ($('#field_name' + number).val() === '_add_')
        {
            $('#field_col' + number).addClass('hidden');
            $('#newfn_col' + number).removeClass('hidden');
            $('#newft_col' + number).removeClass('hidden');
            $('#newc_col' + number).removeClass('hidden');
        }
    }

    function removeNewField(number)
    {
        $('#newfn_col' + number).addClass('hidden');
        $('#newft_col' + number).addClass('hidden');
        $('#newc_col' + number).addClass('hidden');
        //$('#field_name' + number + ' option[value=""]').attr("selected", "selected");
        $("#field_name" + number).val("");
        $('#field_col' + number).removeClass('hidden');
    }
</script>