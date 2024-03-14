<script type="text/javascript">
 <?php
    echo 'alert("Nilai Omzet upload terakhir = Rp.'.$omzet.'")';
 ?>
</script>

<h3>Your file was successfully uploaded!</h3>
<h1><?php echo 'Nilai Omzet upload terakhir = Rp.'.$omzet?></h1>
<ul>
<?php foreach ($upload_data as $item => $value):?>
<li><?php echo $item;?>: <?php echo $value;?></li>
<?php endforeach; ?>
</ul>


<?php echo anchor('upload', 'Upload Another File!'); ?>
<ul>
<?php foreach ($msg as $item => $value):?>
<li><?php echo $item;?>: <?php echo $value;?></li>
<?php endforeach; ?>

</ul>