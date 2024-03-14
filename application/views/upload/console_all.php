<h3>Your file is being consoled !</h3>

<ul>
<?php foreach ($message as $item => $value):?>
<li><?php echo $item;?>: <?php echo $value;?></li>
<?php endforeach; ?>
</ul>


<?php echo anchor('upload/console_all', 'Reupload'); ?>
