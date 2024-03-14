<?php echo form_open($url);?>
<div class='title'>
<?php echo form_label($page_title);?>
</div>

<div class="con">
<?php
    echo form_label("Supplier : ");
    foreach($query->result() as $value)
    {
        $dd[$value->supp]= $value->namasupp;
    }
    echo form_dropdown('supp',$dd);
    echo br(2);
    //echo form_label(" Year : ");
    //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
    //echo form_dropdown('year', $options, date('Y'));
?>
</div>
<div class='con'>
<?php
echo '<br/>'.form_submit('submit','Proses');
?>
</div>
<?php echo form_close();?>


