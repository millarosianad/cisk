<script type="text/javascript">
	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"inputField",
			dateFormat:"%d-%m-%Y"
			/*selectedDate:{				This is an example of what the full configuration offers.
				day:5,						For full documentation about these settings please see the full version of the code.
				month:9,
				year:2006
			},
			yearsRange:[1978,2020],
			limitToToday:false,
			cellColorScheme:"beige",
			dateFormat:"%m-%d-%Y",
			imgPath:"img/",
			weekStartDay:1*/
		});
	};
</script>
<style type="text/css">
div.ex
{
width:320px;
padding:10px;
border:5px solid gray;
margin:0px;
}
</style>
<?php echo form_open($url);?>
<div class='title'>
<?php echo form_label($page_title);?>
</div>
<div id="myTable">
<div class="ex">

<?php
    echo form_label(" Format : ");
    $options = array('PDF'=>'PDF','EXCEL'=>'EXCEL');
    echo form_dropdown('format', $options);
?>
</div>
</div>
<div class='con'>
<?php
echo '<br/>'.form_submit('submit','Proses');
?>
</div>
<?php echo form_close();?>



