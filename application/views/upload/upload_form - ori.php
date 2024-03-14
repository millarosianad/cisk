<?php echo br(7)?>
<br />

<div class="col-md-offset-4">
    <div class="col-md-6">
        <div class="form-group">
<?php echo $error;?>
<?php echo form_open_multipart('upload/do_upload');?>
<?php echo isset($upl->last) ? 'Last Upload : <b>'.$upl->last.' WIB</b>'.br(2) : '';?>
<?php
     //echo form_label(" Year : ");
    echo form_label("<b>PILIH TAHUN FILE YANG DIUPLOAD : </b>");
    $interval=date('Y')-2010;
    $options=array();
    $options['2010']='2010';
    for($i=1;$i<=$interval;$i++)
    {
        $options[''.$i+2010]=''.$i+2010;
    }
    echo form_dropdown('year', $options, date('Y'),'class="form-control"');
    echo br();
?>
    <div class="form-inline">
        Upload data (DTXXXXXX.ZIP) : <input type="file" name="userfile" size="20" class="form-control"/>
        <input type="submit" value="upload" class="btn btn-primary"/>
    </div>

<?php echo form_close()?>
</div></div></div>
                    