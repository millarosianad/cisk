<div class='col-md-6'>
<?php echo br(3).form_open($url);?>
<?php
$req='<sup>*</sup>';
$attr = array(
    'style' => 'size: 100;',
);

?>
<?php echo form_fieldset($page_title);?>
<?php echo form_label('group Name'.$req.' <small><i>min 3 character, max 20 character</i></small>','groupname');?><br/>
<?php echo form_error('groupname','<div class="error">','</div>');?>
<?php echo form_input('groupname',set_value(''),'class="form-control"');?><br/><br/>
<?php echo form_label("Description");?><br/>
<?php echo form_error('description','<div class="error">','</div>');?>
<?php echo form_textarea('description','','class="form-control"');?><br/><br/>
<br/><br/>

<?php echo form_submit('submit','SUBMIT','class="btn btn-info"')?>
<?php echo form_reset('reset','RESET','class="btn btn-default"')?><br/><br/>

<?php echo form_fieldset_close();?>
<?php echo form_close();?>
</div>