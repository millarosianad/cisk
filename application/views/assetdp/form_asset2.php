<script type="text/javascript">
  $(document).ready(function() {
    $("#datepicker").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#datepicker2").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
</script>

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


<?php echo form_open($url);?>
<?php
    if(isset($edit))
    {
        foreach($edit->result() as $value)
        {
            $kode= $value->kode;
            $wilayah=$value->wilayah;
            $idwilayah=$value->idwilayah;
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
<table>
    <tr>
    <td><?php echo form_label("No Voucher ");?></td>
    <td>:<td>
    <td><?php echo isset($edit) ? form_input('kode',$kode):form_input('kode');?></td>
    </tr>
    <tr>
    <td><?php echo form_label("Nama Barang ");?></td>
    <td>:<td>
    <td><?php echo isset($edit) ? form_input('namabarang',$namabarang) : form_input('namabarang');?></td>
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
    <td><?php echo isset($edit) ? form_input('untuk',$untuk) : form_input('untuk');?></td>
    </tr>
    <tr>
    <td><?php echo form_label("Tanggal Perol ");?></td>
    <td>:<td>
    <td><?php echo isset($edit) ? '<input type="text" name="tglperol" value="'.$tglperol.'" size="12" id="inputField" />' : '<input type="text" name="tglperol" size="12" id="datepicker" />'?></td>
    </tr>
    <tr>
    <td><?php echo form_label("Golongan");?></td>
    <td>:<td>
    <td>
        <?php
        $gol=array('0.25'=>'GOL I','0.125'=>'GOL II','0.0625'=>'GOL III','0.05'=>'GOL IV');
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
    
        echo isset($edit)?form_dropdown('grup',$grup,$grupid):form_dropdown('grup',$grup);
    }
    
    ?>
    </td>
    </tr>
    
    <tr>
    <td><?php echo form_label("Nilai Perolehan");?></td>
    <td>:<td>
    <td><?php echo isset($edit)?'<input type="text" Style="text-align:right;" name="np" value="'.$np.'" size="10" onkeypress="return onlyNumbers(event);" />':'<input type="text" Style="text-align:right;" name="np" size="10" onkeypress="return onlyNumbers(event);" />'?></td>
    </tr>
  
    <tr>
    <td><?php echo form_label("Wilayah");?></td>
    <td>:<td>
    <td>
    <?php
    if(isset($query2))
    {
        foreach($query2->result() as $value)
        {
            $grup2[$value->id]= $value->wilayah;
        }
        echo isset($edit)?form_dropdown('wilayah',$grup2,$idwilayah):form_dropdown('wilayah',$grup2);
    }
    ?>
    </td>
    </tr>
    
    <tr bgcolor="#F1F1F1">
    <td><?php echo form_label("Nilai Jual");?></td>
    <td>:<td>
    <td><?php echo isset($edit)?'<input type="text" Style="text-align:right;" name="nj" value="'.$nj.'" size="10" onkeypress="return onlyNumbers(event);" />':'<input type="text" Style="text-align:right;" name="nj" size="10" onkeypress="return onlyNumbers(event);" />'?></td>
    </tr>
    <tr bgcolor="#F1F1F1">
        <td><?php echo form_label("Tanggal Jual ");?></td>
        <td>:<td>
        <td><?php echo isset($edit) ? '<input type="text" name="tgljual" value="'.$tgljual.'" size="12" id="datepicker" />' : '<input type="text" name="tgljual" size="12" id="datepicker2" />'?></td>
    </tr>
    <tr bgcolor="#F1F1F1">
        <td><?php echo form_label("Deskripsi Jual");?></td>
        <td>:<td>
        <td><?php echo isset($edit)? form_textarea('deskripsi',$deskripsi):form_textarea('deskripsi');?></td>
    </tr>

    <tr>
    <td colspan='3'><?php echo form_submit('submit','Save');?></td>
    <td><?php echo form_submit('reset','Reset');?></td>
    </tr>

</table>
<div class='con'>

</div>
<?php echo form_close();?>

