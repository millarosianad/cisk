
<script type="text/javascript">
function onlyNumbers(event)
{
	var e = event || evt; // for trans-browser compatibility
	var charCode = e.which || e.keyCode;

	if ((charCode < 48 || charCode > 57) && (charCode < 37 || charCode>40) && (charCode < 8 || charCode >8) && (charCode < 46 || charCode > 46) )
            return false;
         return true;

}
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#inputField").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#inputField2").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
</script>
<br/>

<?php echo form_fieldset($page_title);?>
<div class='col-md-6'>
<?php echo form_open($url);?>
<?php
    if(isset($edit))
    {
        foreach($edit->result() as $value)
        {
            $kode= $value->kode;
            $namabarang= $value->namabarang;
            $jumlah=$value->jumlah;
            $untuk=$value->untuk;
            $tglperol=strftime('%d-%m-%Y',strtotime($value->tglperol));
            $golongan=$value->gol;
            $grupid=$value->grupid;
            $np=$value->np;
            $nj=$value->nj;
            $tgljual=strftime('%d-%m-%Y',strtotime($value->tgljual));
            $deskripsi=$value->deskripsi;
        }
    }
   
?>

<table class='table'>
    <tr>
    <td><?php echo form_label("No Voucher ");?></td>
    <td>:<td>
    <td><?php echo isset($edit) ? form_input('kode',$kode,"class=form-control"):form_input('kode','',"class=form-control");?></td>
    </tr>
    <tr>
    <td><?php echo form_label("Nama Barang ");?></td>
    <td>:<td>
    <td><?php echo isset($edit) ? form_input('namabarang',$namabarang,"class=form-control") : form_input('namabarang','',"class=form-control");?></td>
    </tr>
    <tr>
    <td><?php echo form_label("Jumlah Barang ");?></td>
    <td>:<td>
    <td><?php echo isset($edit) ? '<input type="text" Style="text-align:right;" name="jumlah" size="2" value="'.$jumlah.'" onkeypress="return onlyNumbers(event);" />'
    :'<input type="text" Style="text-align:right;" name="jumlah" size="2" onkeypress="return onlyNumbers(event);" />'?></td>
    </tr>
    <tr>
    <td><?php echo form_label("Untuk");?></td>
    <td>:<td>
    <td><?php echo isset($edit) ? form_input('untuk',$untuk,"class=form-control") : form_input('untuk','',"class=form-control");?></td>
    </tr>
    <tr>
    <td><?php echo form_label("Tanggal Perol ");?></td>
    <td>:<td>
    <td><?php echo isset($edit) ? '<input type="text" name="tglperol" value="'.$tglperol.'" size="12" id="inputField" />' : '<input type="text" name="tglperol" size="12" id="inputField" />'?></td>
    </tr>
    <tr>
    <td><?php echo form_label("Golongan");?></td>
    <td>:<td>
    <td>
        <?php
        $gol=array('0.25'=>'GOL I','0.125'=>'GOL II','0.0625'=>'GOL III','0.05'=>'GOL IV','0'=>'GOL V');
        echo isset($edit)?form_dropdown('gol',$gol,$golongan):form_dropdown('gol',$gol);?>
    </td>
    </tr>
    <tr>
    <td><?php echo form_label("Grup");?></td>
    <td>:<td>
    <td>
    <?php
    if(isset($query))
    {
        foreach($query->result() as $value)
        {
            $grup[$value->id]= $value->namagrup;
        }
    
        echo isset($edit)?form_dropdown('grup',$grup,$grupid,"class=form-control"):form_dropdown('grup',$grup,'',"class=form-control");
    }
    
    ?>
    </td>
    </tr>
    <tr>
    <td><?php echo form_label("Nilai Perolehan");?></td>
    <td>:<td>
    <td><?php echo isset($edit)?'<input type="text" Style="text-align:right;" name="np" value="'.$np.'" size="10" onkeypress="return onlyNumbers(event);" />':'<input type="text" Style="text-align:right;" name="np" size="10" onkeypress="return onlyNumbers(event);" />'?></td>
    </tr>
    </table>
    </div>
    <div class='col-md-6'>
    <table class='table'>
    <tr bgcolor="#F1F1F1">
    <td><?php echo form_label("Nilai Jual");?></td>
    <td>:<td>
    <td><?php echo isset($edit)?'<input type="text" Style="text-align:right;" name="nj" value="'.$nj.'" size="10" onkeypress="return onlyNumbers(event);" />':'<input type="text" Style="text-align:right;" name="nj" size="10" onkeypress="return onlyNumbers(event);" />'?></td>
    </tr>
    <tr bgcolor="#F1F1F1">
        <td><?php echo form_label("Tanggal Jual ");?></td>
        <td>:<td>
        <td><?php echo isset($edit) ? '<input type="text" name="tgljual" value="'.$tgljual.'" size="12" id="inputField2" />' : '<input type="text" name="tgljual" size="12" id="inputField2" />'?></td>
    </tr>
    <tr bgcolor="#F1F1F1">
        <td><?php echo form_label("Deskripsi Jual");?></td>
        <td>:<td>
        <td><?php echo isset($edit)? form_textarea('deskripsi',$deskripsi,"class=form-control"):form_textarea('deskripsi','',"class=form-control");?></td>
    </tr>

    <tr>
    <td colspan='3'>
    <?php echo form_submit('submit','Save','class="btn btn-info"');?>
    <?php echo form_submit('reset','Reset','class="btn btn-default"');?>
    </td>
    </tr>

</table>
<div class='con'>

</div>
<?php echo form_close();?>
<?php echo form_fieldset_close()?>
</div>