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
<br/><br/>
    <input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/>Select / Unselect All
<br/><br/>
<?php

echo form_label("Product : ");

    echo '<table><tr>';
    $count=1;
    $supp='';
    foreach($query->result() as $value)
    {
        if($supp!=$value->supp)
        {
            //echo $col=4-($count%4);
            $supp=$value->supp;
            $namasupp=$value->namasupp;
            echo '<tr><td><h3>'.$namasupp.'</h3></td></tr><tr>';
            $count=1;
        }
        if($count%4!=0)
        {
        ?>
        <td>
            <INPUT NAME="options[]" TYPE="CHECKBOX" id="options" VALUE="<?php echo $value->kodeprod ?>"><?php echo $value->namaprod?>
        </td>

        <?php
        }
        else
        {
        ?>
            <td>
            <INPUT NAME="options[]" TYPE="CHECKBOX" id="options" VALUE="<?php echo $value->kodeprod ?>"><?php echo $value->namaprod?>
            </td>
        <?php
            echo '</tr><tr>';
        }
        $count++;
    }
    echo '</tr></table>';

    echo br(2);
    echo form_label(" Year : ");
    //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
    $interval=date('Y')-2010;
    $options=array();
    $options['2010']='2010';
    for($i=1;$i<=$interval;$i++)
    {
        $options[''.$i+2010]=''.$i+2010;
    }
    echo form_dropdown('year', $options, date('Y'));
    echo br(2);
    echo form_label(" Format : ");
    $options=array();
    $options['1']='MONITOR';
    $options['2']='PDF';
    $options['3']='EXCEL';
    $options['4']='GRAFIK';
    echo form_dropdown('format', $options, 'MONITOR');
     echo br(2);
     echo form_label(" UNIT/VALUE : ");
    $options=array();
    $options['0']='UNIT';
    $options['1']='VALUE';
     echo form_dropdown('uv', $options, 'UNIT');
   
?>
<!--div class="con">
<?php
    echo form_label("Product : ");
    foreach($query->result() as $value)
    {
        $dd[$value->kodeprod]= $value->namaprod;
    }
    echo form_dropdown('kodeprod',$dd);
    
    
?>
</div-->

<div class='con'>
<?php
echo '<br/>'.form_submit('submit','Proses','onclick="return ValidateCompare();"');
?>
</div>
<?php echo form_close();?>


