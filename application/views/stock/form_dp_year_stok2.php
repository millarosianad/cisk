<?php echo form_open($url);?>
<div class='title'>
<?php echo form_label($page_title);?>
</div>

<div class='con'>
<?php 
//echo form_label("Tahun : ");
//$year =  date("Y");
//$option = array($year-1=>$year-1,$year=>$year,$year+1=>$year+1);
//echo form_dropdown('tahun',$option,$year);
?>
</div>

<div class='con'>
<?php
//echo form_label("Bulan : ");
//$bulan = array(1 => 'Januari', 'Februari', 'Maret','April','Mei','Juni','Juli','Agustus',
//		'September','Oktober','November','Desember');
//echo form_dropdown('bulan',$bulan,date('n'));
?>
</div>
<div class='con'>
<?php
//$tampil=array(0=>'Layar','Cetak');
//echo form_label("Tampilan : ");
//echo form_dropdown('tampil',$tampil,0);
?>
</div>
<div class="con">
<?php
    echo form_label("DP : ");
    foreach($query->result() as $value)
    {
        $dd[$value->naper]= $value->nama_comp;
    }
    echo form_dropdown('nocab',$dd);
    echo br(2);
    echo form_label(" Year : ");
    $interval=date('Y')-2010;
    $options=array();
    $options['2010']='2010';
    for($i=1;$i<=$interval;$i++)
    {
        $options[''.$i+2010]=''.$i+2010;
    }
    //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
    echo form_dropdown('year', $options, date('Y'));
    echo br(2);
    echo form_label(" Format : ");
    $options=array();
    $options['1']='MONITOR';
    $options['2']='PDF';
    $options['3']='EXCEL';

    echo form_dropdown('format', $options, 'MONITOR');
    echo br(2);
    echo form_label(" UNIT/VALUE : ");
    $options=array();
    $options['0']='UNIT';
    $options['1']='VALUE';
    echo form_dropdown('uv', $options, 'UNIT');
?>
</div>
<div class='con'>
<?php
echo '<br/>'.form_submit('submit','Proses');
?>
</div>
<?php echo form_close();?>


