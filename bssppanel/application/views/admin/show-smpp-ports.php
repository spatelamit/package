<?php
if (isset($smpp_type) && $smpp_type == 'PR') {
    ?>
    <select name="pr_port" id="pr_port" style="width: 200px;">
        <option value="-1">Select SMPP Port</option>
        <?php
        if ($smpp_ports) {
            foreach ($smpp_ports as $smpp_port) {
                ?>
                <option value="<?php echo $smpp_port['virtual_port_id']; ?>"><?php echo $smpp_port['virtual_port_no']; ?></option>
                <?php
            }
        }
        ?>
    </select>
    <?php
} elseif (isset($smpp_type) && $smpp_type == 'TR') {
    ?>
    <select name="tr_port" id="tr_port" style="width: 200px;">
        <option value="-1">Select SMPP Port</option>
        <?php
        if ($smpp_ports) {
            foreach ($smpp_ports as $smpp_port) {
                ?>
                <option value="<?php echo $smpp_port['virtual_port_id']; ?>"><?php echo $smpp_port['virtual_port_no']; ?></option>
                <?php
            }
        }
        ?>
    </select>
    <?php
} elseif (isset($smpp_type) && $smpp_type == 'OPEN') {
    ?>
    <select name="open_port" id="open_port" style="width: 200px;">
        <option value="-1">Select SMPP Port</option>
        <?php
        if ($smpp_ports) {
            foreach ($smpp_ports as $smpp_port) {
                ?>
                <option value="<?php echo $smpp_port['virtual_port_id']; ?>"><?php echo $smpp_port['virtual_port_no']; ?></option>
                <?php
            }
        }
        ?>
    </select>
    <?php
}
?>