</div>
<?php
  $country_status = $user_info['country_status'];
 $country = $user_info['country'];
?>
<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div class="portlet">
                <h2 class="content-header-title"><?php echo (isset($contact_info) && $contact_info) ? "Update" : "Add New"; ?> Contact</h2>
                <div class="portlet-content">
                    <?php
                    $extra_column_names = array();
                    $group_ids_array = array();
                    $extra_column_values = array();
                    $extra_column_id = array();
                    $contact_id = 0;
                    if (isset($contact_info) && $contact_info) {
                        $contact_id = $contact_info->contact_id;
                        $contact_name = $contact_info->contact_name;
                        $mobile_no = $contact_info->mobile_number;
                        $group_ids_array = explode(',', $contact_info->contact_group_ids);
                        $extra_column_values = explode('|', $contact_info->extra_column_values);
                        $extra_column_id = explode('|', $contact_info->extra_column_ids);
                    }
                    $data = array('id' => "validate-basic", 'class' => "form parsley-form", 'onsubmit' => "return validateForm()");
                    echo form_open('user/save_contact' . (isset($contact_id) ? "/" . $contact_id : ""), $data);
                    ?>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="mobile_no">Contact Number</label>
                        </div>
                        <?php 
                        if($country_status == 2){
                           ?>
                        <div class="form-group col-md-8">
                            <input type="text" id="mobile_no_out" name="mobile_no_out" value="<?php echo (isset($mobile_no) && $mobile_no) ? substr($mobile_no, 2) : ""; ?>" class="form-control" placeholder="Enter Contact Number" 
                                   data-parsley-required-message="Please enter contact number" data-parsley-type="integer" regex="/^[a-zA-Z0-9_]+$/"
                                   data-parsley-type-message="Please enter valid contact number" data-parsley-maxlength-message="Please enter valid contact number" 
                                   data-parsley-minlength-message="Please enter valid contact number"  required="" />
                        </div>
                      <?php 
                      }else{
                          ?>
                        
                       <div class="form-group col-md-8">
                            <input type="text" id="mobile_no" name="mobile_no" value="<?php echo (isset($mobile_no) && $mobile_no) ? substr($mobile_no, 2) : ""; ?>" class="form-control" placeholder="Enter Contact Number" 
                                   data-parsley-required-message="Please enter contact number" data-parsley-type="integer" regex="/^[a-zA-Z0-9_]+$/"
                                   data-parsley-type-message="Please enter valid contact number" data-parsley-maxlength-message="Please enter valid contact number" 
                                   data-parsley-minlength-message="Please enter valid contact number" data-parsley-minlength="10" data-parsley-maxlength="10" required="" />
                        </div> 
                        <?php 
                      }
                        ?>
                        
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="name">Contact Name</label>
                        </div>
                        <div class="form-group col-md-8">
                            <input type="text" id="name" name="name" data-parsley-required-message="Please enter contact name"
                                   value="<?php echo (isset($contact_name) && $contact_name) ? $contact_name : ""; ?>" required="" class="form-control" 
                                   placeholder="Enter Contact Name" list="name" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="groups">Choose Groups</label>
                        </div>
                        <div class="form-group col-md-8">
                            <select name="groups[]"  id="groups" multiple required="" class="form-control" data-parsley-required-message="Please choose at least one group">
                                <?php
                                if (isset($contact_groups) && $contact_groups) {
                                    foreach ($contact_groups as $group) {
                                        if ($group['extra_column_names'] != "") {
                                            $extra_column_names = array_merge($extra_column_names, explode('|', $group['extra_column_names']));
                                        }
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
                    <div id="show_extra_fields">
                        <?php
                        if (isset($contact_group) && $contact_group) {
                            if ($contact_group->extra_column_ids != "") {
                                $extra_column_ids = explode('|', $contact_group->extra_column_ids);
                                $extra_column_names = explode('|', $contact_group->extra_column_names);
                                $extra_column_types = explode('|', $contact_group->extra_column_types);
                                $extra_column_status = explode('|', $contact_group->extra_column_status);
                                if (sizeof($extra_column_ids)) {
                                    $i = 1;
                                    foreach ($extra_column_names as $key => $extra_column) {
                                        if ($extra_column_status[$key]) {
                                            if (in_array($extra_column_ids[$key], $extra_column_id)) {
                                                $search_key = array_search($extra_column_ids[$key], $extra_column_id);
                                                ?>
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label for="extra_field"><?php echo ucfirst($extra_column); ?></label>
                                                    </div>
                                                    <div class="form-group col-md-8">
                                                        <input type="hidden" name="extra_field_names[]" value="<?php echo $extra_column; ?>" />
                                                        <input type="hidden" name="extra_field_ids[]" value="<?php echo $extra_column_ids[$key]; ?>" />
                                                        <input type="hidden" name="extra_field_types[]" value="<?php echo $extra_column_types[$key]; ?>" />
                                                        <input type="text" id="extra_field_value<?php echo $i; ?>" name="extra_field_values[]" 
                                                               value="<?php echo (isset($extra_column_values[$key])) ? $extra_column_values[$key] : $extra_column_values[$search_key]; ?>" 
                                                               class="form-control" placeholder="Enter <?php echo ucfirst($extra_column); ?>"
                                                               <?php echo (isset($extra_column_types[$key]) && $extra_column_types[$key] == 'number') ? "data-parsley-type='number' data-parsley-type-message='Please enter numeric value'" : ""; ?> />
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label for="extra_field"><?php echo ucfirst($extra_column); ?></label>
                                                    </div>
                                                    <div class="form-group col-md-8">
                                                        <input type="hidden" name="extra_field_names[]" value="<?php echo $extra_column; ?>" />
                                                        <input type="hidden" name="extra_field_ids[]" value="<?php echo $extra_column_ids[$key]; ?>" />
                                                        <input type="hidden" name="extra_field_types[]" value="<?php echo $extra_column_types[$key]; ?>" />
                                                        <input type="text" id="extra_field_value<?php echo $i; ?>" name="extra_field_values[]"  value="" 
                                                               class="form-control" placeholder="Enter <?php echo ucfirst($extra_column); ?>"
                                                               <?php echo (isset($extra_column_types[$key]) && $extra_column_types[$key] == 'number') ? "data-parsley-type='number' data-parsley-type-message='Please enter numeric value'" : ""; ?> />
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            $i++;
                                        }
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="row" id="show_new_fields"></div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <a href="javascript:void(0);" id="add_new_field">Add New Field</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 mt5">
                            <input type="hidden" id="group_id" value="<?php echo (isset($group_id) && $group_id) ? $group_id : 0; ?>" />
                            <input type="hidden" id="contact_id" value="<?php echo (isset($contact_id) && $contact_id) ? $contact_id : 0; ?>" />
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
    #columns{float:left;list-style:none;margin:0;padding:0;width:137px;z-index: 1000;position: absolute;}
    #columns li{padding: 8px; background:#E4E4E4;border-bottom:#F0F0F0 1px solid;}
    #columns li:hover{background:#F0F0F0;}
</style>
<script type="text/javascript">
    //var col_array =<?php //echo json_encode($extra_column_names);   ?>;
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
                } else
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
                } else
                {
                    $($('#new_field_value' + (index + 1))).removeClass('errorClass');
                }

                var type = $('#new_field_type' + (index + 1)).val();
                if (type === 'number' || type === 'Number')
                {
                    if (!$.isNumeric($('#new_field_value' + (index + 1)).val()))
                    {
                        $($('#new_field_type' + (index + 1))).addClass('errorClass');
                        if (message !== "")
                            message += "<br/>Please enter Numeric value Or Change the Field Type.";
                        else
                            message = "Please enter Numeric value Or Change the Field Type.";
                        i++;
                    } else
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
                } else
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