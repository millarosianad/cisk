<?php
if (isset($messages) && !empty($messages)) {
    foreach ($messages as $msg) {
        ?>
        <div class="load-data-wrap message_box" data-id="<?php echo $msg->id; ?>">
            <div class="load-data-msg"><?php echo $msg->message; ?></div>
            <div class="msg-number"><?php echo $msg->id; ?></div>
        </div>
        <?php
    }
}
?>