<?php echo form_open($url);?>
<?php
$req='<sup>*</sup>';
$attr = array(
    'style' => 'size: 100;',
);

?>
<?php echo form_fieldset($page_title);?>
<?php echo form_label('Username'.$req.' <small><i>min 3 character, max 20 character</i></small>','username');?><br/>
<?php echo form_error('username','<div class="error">','</div>');?>
<?php echo form_input('username',set_value('username'));?><br/><br/>
<?php echo form_label("Password".$req.'<small><i>min 8 character</i></small>');?><br/>
<?php echo form_error('password','<div class="error">','</div>');?>
<?php echo form_password('password',set_value('password'));?><br/><br/>
<?php echo form_label("Confirm Password");?><br/>
<?php echo form_error('cpassword','<div class="error">','</div>');?>
<?php echo form_password('cpassword',set_value('cpassword'));?><br/><br/>
<?php echo form_label("Email");?><br/>
<?php echo form_error('email','<div class="error">','</div>');?>
<?php echo form_input('email',set_value('email'));?><br/><br/>
<?php echo form_label("Level");?><br/>
<?php
$options = array(1=>'Admin',2=>'Manager',3=>'User',4=>'DP',5=>'PBF',6=>'Guest');
$selected = 3;
echo form_dropdown('level', $options,$selected);
?><br/><br/>

<?php echo form_submit('submit','SUBMIT')?>
<?php echo form_reset('reset','RESET')?><br/><br/>

<?php echo form_fieldset_close();?>
<?php echo form_close();?>