<?php
if (isset($contact_info) && $contact_info) {
    $extra_column_values_array = explode('|', $contact_info->extra_column_values);
}
if (isset($extra_fields) && $extra_fields) {
    $column_array = array();
    $extra_column_names_array = array();
    $extra_column_types_array = array();
    $extra_column_ids_array = array();
    $extra_column_status_array = array();
    foreach ($extra_fields as $key => $extra) {
        if ($extra->extra_column_names != null) {
            $extra_column_ids_array = array_merge($extra_column_ids_array, explode('|', $extra->extra_column_ids));
            $extra_column_names_array = array_merge($extra_column_names_array, explode('|', $extra->extra_column_names));
            $extra_column_types_array = array_merge($extra_column_types_array, explode('|', $extra->extra_column_types));
            $extra_column_status_array = array_merge($extra_column_status_array, explode('|', $extra->extra_column_status));
        }
    }
    //$extra_column_names_array = array_unique($extra_column_names_array);
    if (sizeof($extra_column_names_array)) {
        $i = 1;
        foreach ($extra_column_names_array as $key => $column) {
            if ($extra_column_status_array[$key]) {
                if (!in_array($column, $column_array)) {
                    $column_array[] = $column;
                    ?>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="extra_field<?php echo $i; ?>"><?php echo ucfirst($column); ?></label>
                        </div>
                        <div class="form-group col-md-8">
                            <input type="hidden" name="extra_field_names[]" value="<?php echo $column; ?>" />
                            <input type="hidden" name="extra_field_ids[]" value="<?php echo $extra_column_ids_array[$key]; ?>" />
                            <input type="hidden" name="extra_field_types[]" value="<?php echo $extra_column_types_array[$key]; ?>" />
                            <input type="text" id="extra_field_value<?php echo $i; ?>" name="extra_field_values[]" placeholder="Enter <?php echo ucfirst($column); ?>"
                                   value="<?php echo isset($extra_column_values_array[$key]) ? $extra_column_values_array[$key] : ""; ?>" class="form-control"
                                   <?php echo ($extra_column_types_array[$key] == 'number') ? "data-parsley-type='number' data-parsley-type-message='Please enter numeric value'" : ""; ?> />
                        </div>
                    </div>
                    <?php
                    $i++;
                }
            }
        }
    }
}

/*
  if ($extra_fields) {
  foreach ($extra_fields as $key => $extra) {
  if ($extra->extra_column_ids != null) {
  $extra_column_ids_array = explode('|', $extra->extra_column_ids);
  $extra_column_names_array = explode('|', $extra->extra_column_names);
  $extra_column_types_array = explode('|', $extra->extra_column_types);
  if (sizeof($extra_column_ids_array)) {
  foreach ($extra_column_names_array as $key => $column) {
  ?>
  <div class="row">
  <div class="form-group col-md-4">
  <label for="name"><?php echo ucfirst($column); ?></label>
  </div>
  <div class="form-group col-md-8">
  <input type="text" id="name" name="name" value="" class="form-control" placeholder="Enter <?php echo ucfirst($column); ?>" />
  </div>
  </div>
  <?php
  }
  }
  }
  }
  }
 */
?>