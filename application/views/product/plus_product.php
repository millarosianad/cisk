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
<script type="text/javascript">
  $(document).ready(function() {
    $("#datepicker").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
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
<table class='table'>
<?php echo form_open($uri);?>
<?php
$req='<sup>*</sup>';
$attr = array(
    'style' => 'size: 100;',
);
/*foreach($namaprod->result() as $row)
{
        $nama =$row->namaprod;
}
$namaprod->free_result();
*/
?>

<?php //echo form_hidden('id',$id)?>
<tr>
<?php echo form_error('kodeprod','<div class="error">','</div>');?>
<td>
<?php echo form_label('Product Code'.$req,'kodeprod');?><br/>

</td>
<td>:</td>
<td>
<?php
    $text['name']='kodeprod';
    $text['size']=6;
    $text['maxlength']=6;
    $text['readonly']='readonly';
    $text['value'] = $kodeprod;
    echo form_input($text);
?>

</td>
</tr>
<tr>
    <td><?php echo form_label("Product Name")?></td>
    <td>:</td>
    <td><?php
    $text2['name']='namaprod';
    $text2['size']=60;
    $text2['maxlength']=60;
    $text2['readonly']='readonly';
    $text2['value']=isset($namaprod)?$namaprod:'';
    echo form_input($text2);?></td>
</tr>
<tr>
    <td><?php echo form_label("Tanggal Aktif");?></td>
    <td>:</td>
    <td><?php echo form_input('tgl',isset($edit)?$edit->tgl:'','id="datepicker"') ?></td>
</tr>
<tr>
    <td><?php echo form_label("Harga Beli DP");?></td>
    <td>:</td>
    <td>
        <input type="text" value="<?php echo isset($edit)?$edit->h_beli_dp:'';?>" Style="text-align:right;" name="h_beli_dp" size="10" onkeypress="return onlyNumbers(event);" />
        <?php echo form_label(" - DISC : ");?>
        <input type="text" value="<?php echo isset($edit)?$edit->d_beli_dp:'';?>" Style="text-align:right;" name="d_beli_dp" size="10" onkeypress="return onlyNumbers(event);" />
    </td>
</tr>
<tr>
    <td><?php echo form_label("Harga Beli BSP");?></td>
    <td>:</td>
    <td>
        <input type="text" value="<?php echo isset($edit)?$edit->h_beli_bsp:'';?>" Style="text-align:right;" name="h_beli_bsp" size="10" onkeypress="return onlyNumbers(event);" />
        <?php echo form_label(" - DISC : ");?>
        <input type="text" value="<?php echo isset($edit)?$edit->d_beli_bsp:'';?>" Style="text-align:right;" name="d_beli_bsp" size="10" onkeypress="return onlyNumbers(event);" />
    </td>
</tr>
<tr>
    <td><?php echo form_label("Harga Beli PBF");?></td>
    <td>:</td>
    <td>
        <input type="text" value="<?php echo isset($edit)?$edit->h_beli_pbf:'';?>" Style="text-align:right;" name="h_beli_pbf" size="10" onkeypress="return onlyNumbers(event);" />
        <?php echo form_label(" - DISC : ");?>
        <input type="text" value="<?php echo isset($edit)?$edit->d_beli_pbf:'';?>" Style="text-align:right;" name="d_beli_pbf" size="10" onkeypress="return onlyNumbers(event);" />
    </td>
</tr>
<tr>
    <td><?php echo form_label("Harga DP");?></td>
    <td>:</td>
    <td>
        <input type="text" value="<?php echo isset($edit)?$edit->h_dp:'';?>" Style="text-align:right;" name="h_dp" size="10" onkeypress="return onlyNumbers(event);" />
        <?php echo form_label(" - DISC : ");?>
        <input type="text" value="<?php echo isset($edit)?$edit->d_dp:'';?>" Style="text-align:right;" name="d_dp" size="10" onkeypress="return onlyNumbers(event);" />
    </td>
</tr>
<tr>
    <td><?php echo form_label("Harga PBF");?></td>
    <td>:</td>
    <td>
        <input type="text" value="<?php echo isset($edit)?$edit->h_pbf:'';?>" Style="text-align:right;" name="h_pbf" size="10" onkeypress="return onlyNumbers(event);" />
        <?php echo form_label(" - DISC : ");?>
        <input type="text" value="<?php echo isset($edit)?$edit->d_pbf:'';?>" Style="text-align:right;" name="d_pbf" size="10" onkeypress="return onlyNumbers(event);" />
    </td>
</tr>
<tr>
    <td><?php echo form_label("Harga BSP");?></td>
    <td>:</td>
    <td>
        <input type="text" value="<?php echo isset($edit)?$edit->h_bsp:'';?>" Style="text-align:right;" name="h_bsp" size="10" onkeypress="return onlyNumbers(event);" />
        <?php echo form_label(" - DISC : ");?>
        <input type="text" value="<?php echo isset($edit)?$edit->d_bsp:'';?>" Style="text-align:right;" name="d_bsp" size="10" onkeypress="return onlyNumbers(event);" />
    </td>
</tr>

<tr>
    <td><?php echo form_label("Harga Khusus Batam (Dji Go Lak)");?></td>
    <td>:</td>
    <td>
        <input type="text" value="<?php echo isset($edit)?$edit->h_dpbatam:'';?>" Style="text-align:right;" name="h_dpbatam" size="10" onkeypress="return onlyNumbers(event);" />
        <?php echo form_label(" - DISC : ");?>
        <input type="text" value="<?php echo isset($edit)?$edit->d_dpbatam:'';?>" Style="text-align:right;" name="d_dpbatam" size="10" onkeypress="return onlyNumbers(event);" />
    </td>
</tr>

<tr>
    <td><?php echo form_label("Harga DP (Luar Pulau Jawa)");?></td>
    <td>:</td>
    <td>
        <input type="text" value="<?php echo isset($edit)?$edit->h_luarjawa:'';?>" Style="text-align:right;" name="h_luarjawa" size="10" onkeypress="return onlyNumbers(event);" />
        <?php echo form_label(" - DISC : ");?>
        <input type="text" value="<?php echo isset($edit)?$edit->d_luarjawa:'';?>" Style="text-align:right;" name="d_luarjawa" size="10" onkeypress="return onlyNumbers(event);" />
    </td>
</tr>

<tr>
    <td><?php echo form_label("Harga Beli MPM - DP");?></td>
    <td>:</td>
    <td>
        <input type="text" value="<?php echo isset($edit)?$edit->h_beli_mpm:'';?>" Style="text-align:right;" name="h_beli_mpm" size="10" onkeypress="return onlyNumbers(event);" />
       </td>
</tr>

<tr>
    <td><?php echo form_label("Harga Beli MPM - BSP");?></td>
    <td>:</td>
    <td>
        <input type="text" value="<?php echo isset($edit)?$edit->h_beli_mpm_bsp:'';?>" Style="text-align:right;" name="h_beli_mpm_bsp" size="10" onkeypress="return onlyNumbers(event);" />
       </td>
</tr>

<tr>
    <td><?php echo form_label("Harga Beli MPM - CANDY - P JAWA");?></td>
    <td>:</td>
    <td>
        <input type="text" value="<?php echo isset($edit)?$edit->h_beli_mpm_candy_jawa:'';?>" Style="text-align:right;" name="h_beli_mpm_candy_jawa" size="10" onkeypress="return onlyNumbers(event);" />
       </td>
</tr>

<tr>
    <td><?php echo form_label("Harga Beli MPM - CANDY - Luar P JAWA");?></td>
    <td>:</td>
    <td>
        <input type="text" value="<?php echo isset($edit)?$edit->h_beli_mpm_candy_Ljawa:'';?>" Style="text-align:right;" name="h_beli_mpm_candy_Ljawa" size="10" onkeypress="return onlyNumbers(event);" />
       </td>
</tr>




<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>
    <?php echo form_submit('submit','SUBMIT','class="btn btn-info"')?>
    <?php echo form_reset('reset','RESET','class="btn default"')?>
    </td>
</tr>
</table>
<?php echo form_close();?>
<?php echo form_fieldset_close();?>
