<?php echo br(5)?>
<div class="col-md-offset-4">
    <div class="col-md-5">
<?php echo form_open($url);?>

<?php echo '<h2>'.form_label($page_title).'</h2>';?>

<?php
    echo form_label(" Year : ");
    //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
    $interval=date('Y')-2010;
    $options=array();
    $options['2010']='2010';
    for($i=1;$i<=$interval;$i++)
    {
        $options[''.$i+2010]=''.$i+2010;
    }
    echo form_dropdown('year', $options,date('Y'),'class="form-control"');
    echo br();
    echo form_label(" Format : ");
    $options=array();
    $options['1']='MONITOR';
    $options['2']='PDF';
    $options['3']='EXCEL';
    echo form_dropdown('format', $options, 'MONITOR','class="form-control"');
    echo br();
    echo form_label(" Unit/Value : ");
    $options=array();
    $options['0']='UNIT';
    $options['1']='VALUE';
    echo form_dropdown('uv', $options, 'UNIT','class="form-control"');
   
    /** Tambahan Tizar 
    echo br();
    echo form_label("WILAYAH : ");
            $options=array();
            $options['0']='SEMUA WILAYAH';
            $options['1']='WILAYAH BARAT';
            $options['2']='WILAYAH TIMUR';
            echo form_dropdown('wilayah', $options, "0",'class="form-control"');
            */
?>


<?php
echo '<br/>'.form_submit('submit','Proses','class="btn btn-primary"');
?>

<?php echo form_close();?>
</div></div>


