<?php
if (isset($extra) && $extra) {
    $extra_column_names = array_map('strtolower', $extra['name']);
    $extra_column_types = array_map('ucfirst', $extra['type']);
}
$input = preg_quote($value, '~');
$result = preg_grep('~' . $input . '~', $extra_column_names);
?>
<ul id="columns" class="columns">
    <?php
    if (isset($result) && $result && sizeof($result)) {
        foreach ($result as $key => $column) {
            ?>
            <li onClick="selectColumnName(<?php echo $index; ?>, '<?php echo $column; ?>', '<?php echo $extra_column_types[$key]; ?>');"><?php echo ucfirst($column); ?></li>
            <?php
        }
    }
    ?>
</ul>
<script type="text/javascript">
    $(document).click(function () {
        $(".suggesstion").hide();
    });
</script>