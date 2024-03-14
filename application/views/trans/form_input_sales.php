
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

<?php echo form_fieldset($page_title);?>
<?php echo form_open($url);?>

<table>
<tr>
    <th bgcolor="#666666">Product</th><th bgcolor="#666666" align="right">Amount</th><th bgcolor="#666666" align="center">Submit</th>
</tr>
<tr bgcolor="#DEF3CC"><td>
<?php
foreach($query->result() as $value)
{
    $product[$value->kodeprod]= $value->namaprod;
}
echo form_dropdown('product', $product);
$query->free_result();
?></td>
<td>
<input type="text" Style="text-align:right;" name="amount" size="10" onkeypress="return onlyNumbers(event);" />
</td>
<td>
<?php echo form_submit('submit','Submit')?>
</td>
</tr>
</table>
<?php echo form_close();
$finish=$table.br(1).'Klik "PURCHASE" to finish the transaction';
$finish.=form_open($url2);
$finish.=br(1).form_submit('submit','PURCHASE');

?>

<?php echo isset($table) ? $finish : '';?>
<?php echo form_fieldset_close();?>
