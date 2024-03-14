<?php echo form_open($url);?>
<div class='title'>
<?php echo form_label($page_title);?>
</div>
<div class="con">
<?php

    echo form_label(" SUPPLIER : ");
    $options=array();
    foreach($query->result() as $value)
    {
        $options[$value->supp]= $value->namasupp;
    }
    echo form_dropdown('supp', $options, 'All');
    echo br(2);
    echo form_label(" PICK A DATE : ");
    //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
    $months = array(1=>'JAN',2=>'FEB',3=>'MAR',4=>'APR',5=>'MAY',6=>'JUN',
                    7=>'JUL',8=>'AUG',9=>'SEP',10=>'OCT',11=>'NOV',12=>'DEC');
    echo form_dropdown('month', $months,date('m'));
    $interval=date('Y')-2010;
    $options=array();
    $options['2010']='2010';
    for($i=1;$i<=$interval;$i++)
    {
        $options[''.$i+2010]=''.$i+2010;
    }
    echo form_dropdown('year', $options,date('Y'));
    echo br(2);
    
?>
</div>
<div class='con'>
<?php
echo '<br/>'.form_submit('submit','Proses');
?>
</div>
<?php echo form_close();?>



