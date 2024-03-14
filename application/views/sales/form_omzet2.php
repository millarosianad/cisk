<?php echo form_open($url);?>
<div class='title'>
<?php echo form_label($page_title);?>
</div>
<div class="con">
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
    echo form_dropdown('year', $options,date('Y'));
    echo br(2);
    echo form_label(" Format : ");
    $options=array();
    $options['1']='MONITOR';
    $options['2']='PDF';
    $options['3']='EXCEL';
    $options['4']='GRAFIK';
    echo form_dropdown('format', $options, 'MONITOR');
    echo br(2);
    echo form_label(" SUPPLIER : ");
    $options=array();
    $options['0']='ALL';
    $options['1']='DELTOMED';
    $options['2']='PILKITA';
    $options['3']='FRESH CARE';
    $options['4']='JAHE KERATON';
     echo form_dropdown('supp', $options, 'All');
?>
</div>
<div class='con'>
<?php
echo '<br/>'.form_submit('submit','Proses');
?>
</div>
<?php echo form_close();?>



