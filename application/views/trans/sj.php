<script type="text/javascript">
  $(document).ready(function() {
    $("#datepicker").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
    $(document).ready(function() {
    $("#datepicker2").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
function changeNo()
{
    var tgl=document.getElementById("datepicker").value;
    var nomor=document.getElementById("nomor").value;
    /*alert(nomor.substr(0,3)+'/'+tgl.substr(5,2)+'/'+tgl.substr(0,4));*/
    document.getElementById("nomor").value=nomor.substr(0,3)+'/'+rome(tgl.substr(5,2))+'/'+tgl.substr(0,4);
}
function rome(bulan)
{
    switch(bulan)
    {
        case '01': bulan='I';break;
        case '02': bulan='II';break;
        case '03': bulan='III';break;
        case '04': bulan='IV';break;
        case '05': bulan='V';break;
        case '06': bulan='VI';break;
        case '07': bulan='VII';break;
        case '08': bulan='VIII';break;
        case '09': bulan='IX';break;
        case '10': bulan='X';break;
        case '11': bulan='XI';break;
        case '12': bulan='XII';break;
    }
    return bulan;
}
function onlyNumbers(event)
{
	var e = event || evt; // for trans-browser compatibility
	var charCode = e.which || e.keyCode;

	if ((charCode < 48 || charCode > 57) && (charCode < 37 || charCode>40) && (charCode < 8 || charCode >8) && (charCode < 46 || charCode > 46) )
            return false;
         return true;

}
</script>

<?php
echo br(2);
switch($state)
{
    case 'show':
    {
        echo $add.$printer.br(2);
        echo form_open($uri);
        echo form_input('keyword',$keyword,'id="datepicker"');
        echo form_submit('submit','FIND');
        echo form_close();

    }break;
    case 'print_range_dialog':
    {
        echo "<div class='con'>";
        echo form_open($uri);
        echo 'START:'.form_input('awal','','id="datepicker"').'-END:'.form_input('akhir','','id="datepicker2"').br(2);
        echo form_submit('submit','PRINT');
        echo form_close();
        echo "</div>";
    }break;
    case 'show_add':
    {
        
        echo form_open($uri2);
        echo form_label('TANGGAL : ');
        echo form_input('tanggal',date('Y-m-d'),'id="datepicker" onchange="changeNo()"').br(2);
        echo form_label('NOMOR&nbsp&nbsp&nbsp&nbsp : ');
        echo form_input('nomor',$nomor,'id="nomor"').br(2);
        $options=array();
        foreach ($job->result() as $row)
        {
            $options[$row->job]=$row->job.'---'.$row->desc;
        }
        echo form_label('PENERIMA : ');
        echo form_dropdown('job',$options).br(2);

        echo form_submit('submit','SAVE');
        echo form_close();
        echo form_open($uri);?>
        <table>
            <tr>
                <th bgcolor="#666666">Product</th>
                <th bgcolor="#666666" align="center">Amount</th>
                <th bgcolor="#666666" align="center">Tambah</th>
            </tr>
            <tr bgcolor="#DEF3CC">
            <td>
                <?php echo form_input('namaprod','','size="75"'); ?>
            </td>
            <td>
                <input type="text" Style="text-align:right;" value="1"  id="amount" name="banyak" size="10" onkeypress="return onlyNumbers(event);" />
            </td>
           
            <td>
                <?php echo form_submit('submit','Tambah')?>
            </td>
            </tr>
        </table>
<?php
        echo form_close();

        echo $query;
    }break;
}
?>
