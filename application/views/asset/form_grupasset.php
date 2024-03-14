<br/>
<div class='col-md-6'>
<?php echo form_open($url);?>
<?php
$req='<sup>*</sup>';
$attr = array(
    'style' => 'size: 100;',
);
?>
<?php
if(isset($edit))
{
        foreach($edit->result() as $value)
        {
            $namagrup = $value->namagrup;
            $deskripsi= $value->deskripsi;
        }
}
?>

<?php echo form_fieldset($page_title);?>
<?php echo form_label('Grup Asset');?><br/>
<?php echo isset($edit)? form_input('namagrup',$namagrup,'class="form-control"'):form_input('namagrup','','class="form-control"');?><br/>
<?php echo form_label("Deskripsi");?><br/>
<?php echo isset($edit)? form_textarea('deskripsi',$deskripsi,'class="form-control"'):form_textarea('deskripsi','','class="form-control"');?><br/>

<?php echo form_submit('submit','SUBMIT','class="btn btn-info"').'&nbsp&nbsp'?>
<?php echo form_reset('reset','RESET',"class='btn btn-default'")?>

<?php echo form_fieldset_close();?>
<?php echo form_close();?>
</div>