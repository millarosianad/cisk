<div class ='con'>
<br />
<?php echo $error;?>
<br />

<?php echo form_open_multipart($uri);?>
<?php echo isset($upl->last) ? 'Last Upload : <b>'.$upl->last.' WIB</b>'.br(2) : '';?>
Upload data (DTXXXXXX.ZIP) : <input type="file" name="userfile" size="20" />
<?php
    echo br(2);
    //echo form_label(" Year : ");
    echo form_label(" Year : ");
    $interval=date('Y')-2010;
    $options=array();
    $options['2010']='2010';
    for($i=1;$i<=$interval;$i++)
    {
        $options[''.$i+2010]=''.$i+2010;
    }
    echo form_dropdown('year', $options, date('Y'));
    echo br(2);
?>

<input type="submit" value="upload" />


<?php echo form_close()?>
</div>