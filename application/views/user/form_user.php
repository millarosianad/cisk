<div class='col-md-4'>
<?php echo form_open($url);?>
<?php
$req='<sup>*</sup>';
$attr = array(
    'style' => 'size: 100;',
);

?>
<h2>New User</h2>
<div class='form-group'>
<?php echo form_label('Username'.$req.' <small><i>min 3 character, max 20 character</i></small>','username');?><br/>
<?php echo form_error('username','<div class="error">','</div>');?>
<?php echo form_input('username',set_value('username'),'class="form-control"');?>
</div>
<div class='form-group'>
<?php echo form_label("Password".$req.'<small><i>min 8 character</i></small>');?>
<?php echo form_error('password','<div class="error">','</div>');?>
<?php echo form_password('password',set_value('password'),'class="form-control"');?>
</div>
<div class='form-group'>
<?php echo form_label("Confirm Password");?>
<?php echo form_error('cpassword','<div class="error">','</div>');?>
<?php echo form_password('cpassword',set_value('cpassword'),'class="form-control"');?>
</div>
<div class='form-group'>
<?php echo form_label("Email");?>
<?php echo form_error('email','<div class="error">','</div>');?>
<?php echo form_input('email',set_value('email'),'class="form-control"');?>
</div>
<div class='form-group'>
<?php echo form_label("Email Finance");?>
<?php echo form_error('email_finance','<div class="error">','</div>');?>
<?php echo form_input('email_finance',set_value('email'),'class="form-control"');?>
</div>
<div class='form-group'>
<?php echo form_label("Supplier");?>
<?php
foreach($query->result() as $value)
{
    $options[$value->supp]= $value->namasupp;
}

echo form_dropdown('supp', $options,'','class="form-control"');?>
</div>
<div class='form-group'>
<?php echo form_label("Level");?>
<?php
$options = array(1=>'Admin',2=>'Manager',3=>'User',4=>'DP',5=>'PBF',6=>'BSP',7=>'Permen');
$selected = 3;
echo form_dropdown('level', $options,$selected,'class="form-control"');
?>
<div>
<div class='form-group'>
<?php echo br(2).form_submit('submit','Submit','class="btn btn-primary"').'&nbsp&nbsp';?>
<?php echo form_reset('reset','Clear','class="btn btn-default"').br(3);?>
</div>    

<?php echo form_close();?>
</div>