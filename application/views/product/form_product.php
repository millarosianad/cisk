<script type="text/javascript">
function onlyNumbers(event)
{
	var e = event || evt; // for trans-browser compatibility
	var charCode = e.which || e.keyCode;

	if (charCode > 31 && (charCode < 45 || charCode > 57))
		return false;

	return true;

}
</script>

<?php
$data = array(
              'name'        => 'username',
              'id'          => 'username',
              'value'       => 'johndoe',
              'maxlength'   => '100',
              'size'        => '50',
              'style'       => 'width:50%',
            );

echo form_fieldset($page_title);?>
<table>
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
        $kodeprod =$row->kodeprod;
        $namaprod =$row->namaprod;
        $selected =$row->supp;
        $grupprod =$row->grupprod;
        $satuan = $row->satuan;
        $isisatuan=$row->isisatuan;
        $odrunit=$row->odrunit;
        $h_dp   =$row->h_dp;
        $h_bsp   =$row->h_bsp;
        $h_pbf   =$row->h_pbf;
        $kode_prc =$row->kode_prc;
    }
    $edit->free_result();
}
else
{
    $kodeprod ='';
    $namaprod ='';
    $supp     ='';
    $grupprod ='';
    $isisatuan='';
    $odrunit='';
    $satuan='';
    $kode_prc ='';
    $selected ='001';
}

?>

<?php //echo form_hidden('id',$id)?>
<table class='table'>
<tr>
<?php echo form_error('kodeprod','<div class="error">','</div>');?>
<td>
<?php echo form_label('Product Code'.$req,'kodeprod');?><br/>

</td>
<td>:</td>
<td>
<?php

if($kodeprod=='')
{
    $text['name']='kodeprod';
    $text['size']=6;
    $text['maxlength']=6;
    echo form_input($text);
}
else
{
    $text['name']='kodeprod';
    $text['size']=6;
    $text['maxlength']=6;
    $text['readonly']='readonly';
    $text['value'] = $kodeprod;
    echo form_input($text);
}
?>

</td>
</tr>
<tr>
    <td><?php echo form_label("Product Name");?></td>
    <td>:</td>
    <td><?php
    $text2['name']='namaprod';
    $text2['size']=60;
    $text2['maxlength']=60;
    if(isset($edit))
    {
        $text2['value']=$namaprod;
    }
    echo form_input($text2);?></td>
</tr>
<tr>
    <td><?php echo form_label("PRC Code");?></td>
    <td>:</td>
    <td><?php echo form_input('kode_prc',$kode_prc);?></td>
</tr>
<tr>
    <td><?php echo form_label("Group Product");?></td>
    <td>:</td>
    <td><?php echo form_input('grupprod',$grupprod);?></td>
</tr>
<tr>
    <td><?php echo form_label("Content Unit (* harus satuan terkecil)");?></td>
    <td>:</td>
    <td><input type="text" value="<?php echo isset($edit)? $isisatuan : '' ;?>" Style="text-align:right;" name="isisatuan" size="10" onkeypress="return onlyNumbers(event);" /></td>
</tr>
<tr>
    <td><?php echo form_label("Unit");?></td>
    <td>:</td>
    <td><input type="text" value="<?php echo isset($edit)? $satuan : '' ;?>" name="satuan" size="10" /></td>
</tr>
<tr>
    <td><?php echo form_label("Order Unit");?></td>
    <td>:</td>
    <td><input type="text" value="<?php echo isset($edit)? $odrunit : '' ;?>" name="odrunit" size="10" /></td>
</tr>
<!--tr>
    <td><?php echo form_label("Price DP");?></td>
    <td>:</td>
    <td><input type="text" value="<?php echo isset($edit)? $h_dp : '' ;?>" Style="text-align:right;" name="h_dp" size="10" onkeypress="return onlyNumbers(event);" /></td>
</tr>
<tr>
    <td><?php echo form_label("Price PBF");?></td>
    <td>:</td>
    <td><input type="text" value="<?php echo isset($edit)? $h_pbf : '' ;?>" Style="text-align:right;" name="h_pbf" size="10" onkeypress="return onlyNumbers(event);" /></td>
</tr>
<tr>
    <td><?php echo form_label("Price BSP");?></td>
    <td>:</td>
    <td><input type="text" value="<?php echo isset($edit)? $h_bsp : '' ;?>" Style="text-align:right;" name="h_bsp" size="10" onkeypress="return onlyNumbers(event);" /></td>
</tr-->
<tr>
<td><?php echo form_label("Supplier");?></td>
<td>:</td>
<?php
foreach($suppq->result() as $value)
{
    $supp[$value->supp]= $value->namasupp;
}
?>
<td>
<?php
echo form_dropdown('supp',$supp,$selected);
$suppq->free_result();?>
</td>
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