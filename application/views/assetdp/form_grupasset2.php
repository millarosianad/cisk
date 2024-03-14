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
<?php echo isset($edit)? form_input('namagrup',$namagrup):form_input('namagrup');?><br/><br/>
<?php echo form_label("Deskripsi");?><br/>
<?php echo isset($edit)? form_textarea('deskripsi',$deskripsi):form_textarea('deskripsi');?><br/><br/>
<br/><br/>

<?php echo form_submit('submit','SUBMIT')?>
<?php echo form_reset('reset','RESET')?><br/><br/>

<?php echo form_fieldset_close();?>
<?php echo form_close();?>