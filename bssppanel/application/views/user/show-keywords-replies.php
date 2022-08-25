<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />
<?php
// Short Codes
if (isset($type) && $type == 'short') {
    if (isset($short_keyword_reply) && $short_keyword_reply) {
        $j = 1;
        foreach ($short_keyword_reply as $key => $reply) {
            $reply_content = substr($reply['keyword_reply'], 0, 40);
            ?>
            <ul class="padding0">
                <li class="padding0">
                    <?php echo $j . ". " . $reply_content; ?> [<strong><?php echo $reply['keyword_reply_sender']; ?></strong>]
                </li>
                <?php
                ?>
            </ul>
            <?php
            $j++;
        }
    }
    ?>
    <button type="button" class="btn btn-primary btn-xs keyword_reply" data-toggle="popover" id="popover<?php echo $number; ?>"
            data-content="<div class='form-group'>
            <input type='hidden' name='keyword_type' id='keyword_type<?php echo $number; ?>' value='short' />
            <input type='text' name='reply_sender' id='reply_sender<?php echo $number; ?>' class='form-control' placeholder='Enter Sender Id' /></div>
            <div class='form-group'>
            <textarea class='form-control' name='reply_content' id='reply_content<?php echo $number; ?>'
            rows='2' placeholder='Enter Reply Content'></textarea></div>
            <button class='btn btn-default btn-xs' type='button' 
            onclick='saveKeywordReply(<?php echo $number; ?>, <?php echo $keyword_id; ?>);' id='btn'>Save</button>">
        Add New
    </button>
    <?php
} 

// Long Codes
if (isset($type) && $type == 'long') {
    if (isset($long_keyword_reply) && $long_keyword_reply) {
        $j = 1;
        foreach ($long_keyword_reply as $key => $reply) {
            $reply_content = substr($reply['keyword_reply'], 0, 40);
            ?>
            <ul class="padding0">
                <li class="padding0">
                    <?php echo $j . ". " . $reply_content; ?> [<strong><?php echo $reply['keyword_reply_sender']; ?></strong>]
                </li>
                <?php
                ?>
            </ul>
            <?php
            $j++;
        }
    }
    ?>
    <button type="button" class="btn btn-primary btn-xs keyword_reply" data-toggle="popover" id="popover<?php echo $number; ?>"
            data-content="<div class='form-group'>
            <input type='text' hidden='keyword_type' id='keyword_type<?php echo $number; ?>' value='long' />
            <input type='text' name='reply_sender' id='reply_sender<?php echo $number; ?>' class='form-control' placeholder='Enter Sender Id' /></div>
            <div class='form-group'>
            <textarea class='form-control' name='reply_content' id='reply_content<?php echo $number; ?>'
            rows='2' placeholder='Enter Reply Content'></textarea></div>
            <button class='btn btn-default btn-xs' type='button' 
            onclick='saveKeywordReply(<?php echo $number; ?>, <?php echo $keyword_id; ?>);' id='btn'>Save</button>">
        Add New
    </button>
    <?php
}
?>