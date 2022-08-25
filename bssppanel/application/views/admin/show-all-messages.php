<table class="table">
    <tbody>
        <?php
        if (isset($all_messages) && $all_messages) {
            foreach ($all_messages as $key => $message) {
                ?>
                <tr>
                    <td>
                        <strong><?php echo $message['mobile_no']; ?></strong>
                        <p><?php echo urldecode($message['message']); ?></p>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>