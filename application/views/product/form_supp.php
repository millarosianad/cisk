<script type="text/javascript">
function onlyNumbers(event)
{
	var e = event || evt; // for trans-browser compatibility
	var charCode = e.which || e.keyCode;

	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;

	return true;

}
</script>
<div class='col-md-6'>
<?php
/*$data = array(
              'name'        => 'username',
              'id'          => 'username',
              'value'       => 'johndoe',
              'maxlength'   => '100',
              'size'        => '50',
              'style'       => 'width:50%',
            );*/

echo br().form_fieldset('New Supplier');?>
<table class="table">
<?php echo form_open($url);?>
<?php
$req='<sup>*</sup>';
$attr = array(
    'style' => 'size: 100;',
);
if(isset($edit))
{
    foreach($edit->result() as $row)
    {
        $supp     =$row->supp;
        $namasupp =$row->namasupp;
        $telp     =$row->telp;
        $email    =$row->email;
        $npwp     =$row->npwp;
        $alamat   =$row->alamat_wp;
    }
    $edit->free_result();
}
else
{
        $supp     ='';
        $namasupp ='';
        $telp     ='';
        $email    ='';
        $npwp     ='';
        $alamat   ='';
}

?>

<?php //echo form_hidden('id',$id)?>
<tr>
<?php echo form_error('supp','<div class="error">','</div>');?>
<td>
<?php echo form_label('Code'.$req,'supp');?><br/>

</td>
<td>:</td>
<td>
<?php

if($supp=='')
{
    $text['name']='supp';
    $text['size']=3;
    $text['maxlength']=3;
    echo form_input($text);
}
else
{
    $text['name']='supp';
    $text['size']=3;
    $text['maxlength']=3;
    $text['readonly']='readonly';
    $text['value'] = $supp;
    echo form_input($text);
}
?>

</td>
</tr>
<tr>
    <td><?php echo form_label("Supplier Name");?></td>
    <td>:</td>
    <td><?php
    $text2['name']='namasupp';
    $text2['size']=60;
    $text2['maxlength']=60;
    if(isset($edit))
    {
        $text2['value']=$namasupp;
    }
    echo form_input($text2);?></td>
</tr>
<tr>
    <td><?php echo form_label("Telp");?></td>
    <td>:</td>
    <td><?php echo form_input('telp',$telp,'class="form-control"');?></td>
</tr>
<tr>
    <td><?php echo form_label("Email");?></td>
    <td>:</td>
    <td><?php echo form_input('email',$email,'class="form-control"');?></td>
</tr>
<tr>
    <td><?php echo form_label("NPWP");?></td>
    <td>:</td>
    <td><?php echo form_input('npwp',$npwp,'class="form-control"');?></td>
</tr>
<tr>
    <td><?php echo form_label("Address");?></td>
    <td>:</td>
    <td><?php echo form_textarea('alamat',$alamat,'class="form-control"');?></td>
</tr>


<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>
    <?php echo form_submit('submit','SUBMIT','class="btn btn-info"')?>
    <?php echo form_reset('reset','RESET','class="btn btn-default"')?>
    </td>
</tr>
<?php echo form_fieldset_close();?>
<?php echo form_close();?>
</table>
</div>