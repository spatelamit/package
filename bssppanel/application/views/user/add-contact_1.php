</div>
<div class="container">
    <div class="row">

        <div class="col-sm-6">
            <div class="portlet">
                <h2 class="content-header-title"><?php echo (isset($contact_info)) ? "Update" : "Add New"; ?> Contact</h2>
                <div class="portlet-content">

                    <?php
                    $group_ids_array = array();
                    if (isset($contact_info)) {
                        $contact_id = $contact_info->contact_id;
                        $contact_name = $contact_info->contact_name;
                        $mobile_no = $contact_info->mobile_number;
                        $email_address = $contact_info->email_address;
                        $city = $contact_info->city;
                        $group_ids_array = explode(',', $contact_info->contact_group_ids);
                    }
                    $data = array('id' => "validate-basic", 'class' => "form parsley-form", 'onsubmit' => "return validateForm()");
                    echo form_open('user/save_contact' . (isset($contact_id) ? "/" . $contact_id : ""), $data);
                    ?>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="mobile_no">Contact Number</label>
                        </div>
                        <div class="form-group col-md-5">
                            <input type="text" id="mobile_no" name="mobile_no" value="<?php echo (isset($mobile_no) ? $mobile_no : ""); ?>" class="form-control" placeholder="Enter Contact Number" 
                                   data-parsley-required-message="Please enter contact number" data-parsley-type="integer" regex="/^[a-zA-Z0-9_]+$/"
                                   data-parsley-type-message="Please enter valid contact number" data-parsley-maxlength-message="Please enter valid contact number" 
                                   data-parsley-minlength-message="Please enter valid contact number" data-parsley-minlength="10" data-parsley-maxlength="10" required="" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="name">Contact Name</label>
                        </div>
                        <div class="form-group col-md-5">
                            <input type="text" id="name" name="name" data-parsley-required-message="Please enter contact name" 
                                   value="<?php echo (isset($contact_name) ? $contact_name : ""); ?>" required="" class="form-control" 
                                   placeholder="Enter Contact Name" />
                        </div>
                    </div>                   
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="groups">Choose Groups</label>
                        </div>
                        <div class="form-group col-md-5">
                            <select name="groups[]" id="groups" multiple required="" class="form-control"
                                    data-parsley-required-message="Please choose at least one group">
                                        <?php
                                        if (isset($contact_groups)) {
                                            foreach ($contact_groups as $group) {
                                                if (in_array($group['contact_group_id'], $group_ids_array)) {
                                                    ?>
                                            <option value="<?php echo $group['contact_group_id']; ?>" selected><?php echo $group['contact_group_name']; ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="<?php echo $group['contact_group_id']; ?>"><?php echo $group['contact_group_name']; ?></option>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div id="show_extra_fields"></div>

                    <div class="row" id="show_new_fields"></div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <a href="javascript:void(0);" id="add_new_field">Add more fields</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 mt5">
                            <button type="submit" class="btn btn-primary">Save Contact</button>
                        </div>
                    </div>

                    </form>
                </div> 
            </div> 
        </div>

    </div>
</div>

<style type="text/css">
    .errorClass{
        border: 1px solid red;
    }   
</style>
<script type="text/javascript">
    function validateForm()
    {
        var i = 0;
        var pattern = /^[a-zA-Z0-9_]+$/;
        $('div#div input.names').each(function (index, value)
        {
            var flag = 0;
            var val = $(value).val();
            var message = "";
            if (val !== "")
            {
                if (!pattern.test(val))
                {
                    $($(value)).addClass('errorClass');
                    message += "Field name allows underscore or alphanumeric characters.";
                    i++;
                }
                else
                {
                    $($(value)).removeClass('errorClass');
                }

                if ($('#new_field_value' + (index + 1)).val() === "")
                {
                    $($('#new_field_value' + (index + 1))).addClass('errorClass');
                    if (message !== "")
                        message += "<br/>Please enter Field value Or Remove this Field.";
                    else
                        message = "Please enter Field value Or Remove this Field.";
                    i++;
                }
                else
                {
                    $($('#new_field_value' + (index + 1))).removeClass('errorClass');
                }

                var type = $('#new_field_type' + (index + 1)).val();
                if (type === 'number')
                {
                    if (!$.isNumeric($('#new_field_value' + (index + 1)).val()))
                    {
                        $($('#new_field_type' + (index + 1))).addClass('errorClass');
                        if (message !== "")
                            message += "<br/>Please enter Numeric value Or Change the Field Type.";
                        else
                            message = "Please enter Numeric value Or Change the Field Type.";
                        i++;
                    }
                    else
                    {
                        $($('#new_field_type' + (index + 1))).removeClass('errorClass');
                    }
                }
            }

            if ($('#new_field_value' + (index + 1)).val() !== "")
            {
                if (val === "")
                {
                    $(value).addClass('errorClass');
                    if (message !== "")
                        message += "<br/>Please enter Field name Or Remove this Field.";
                    else
                        message = "Please enter Field name Or Remove this Field.";
                    i++;
                }
                else
                {
                    $(value).removeClass('errorClass');
                }
            }
            $('#custom_msg' + (index + 1)).html(message);
        });
        if (i !== 0)
        {
            return false;
        }
    }
</script>