<?php
echo form_fieldset($page_title);
echo isset($back)? $back : '';
if($do->nodo=='')
{
    echo form_open($url);
    echo form_label('<b>NO PO : '.$do->nopo.'</b>');
    echo br(2);
    echo form_label('NO DO : ');
    echo form_input('nodo');
    echo form_submit('submit','PROCESS');
    echo form_close();
    echo br(1);
}
else
{
    ?>
    <table>
        <tr>
            <td><?php echo form_label('Tanggal PO')?></td>
            <td>:</td>
            <td><?php echo $do->tglpo?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><?php echo form_label('NO PO')?></td>
            <td>:</td>
            <td><?php echo $do->nopo?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><?php echo form_label('Tanggal DO')?></td>
            <td>:</td>
            <td><?php echo $do->tgldo?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><?php echo form_label('NO DO')?></td>
            <td>:</td>
            <td><?php echo $do->nodo?></td>
            <td><?php echo $edit?></td>
        </tr>
    </table>
    <?php
}
?>
<table>
<tr>
<td><?php echo form_label('Pemesan')?></td>
<td>:</td>
<td>PT. MULIA PUTRA MANDIRI</td>
</tr>
<tr>
<td><?php echo form_label('Dikirim Kepada')?></td>
<td>:</td>
<td><?php echo $client->charge;?></td>
</tr>
<tr>
<td><?php echo form_label('DP / PBF')?></td>
<td>:</td>
<td><?php echo $client->company;?></td>
</tr>
<tr>
<td><?php echo form_label('N P W P')?></td>
<td>:</td>
<td><?php echo $client->npwp;?></td>
</tr>
<tr>
<td><?php echo form_label('Alamat')?></td>
<td>:</td>
<td><?php echo $client->address;?></td>
</tr>

</table>
<?php

echo br(1);
echo $table;
echo form_fieldset_close();
?>
