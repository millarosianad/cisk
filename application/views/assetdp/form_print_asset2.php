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
<?php echo form_label(" Date : ");?>
   <input type="text" name="tanggal" size="12" id="datepicker" />
<?php echo br(2)?>
<?php
   echo form_label("Grup : ");
    echo '<table><tr>';
    $count=1;
    foreach($query->result() as $value)
    {
        if($count%2!=0)
        {
        ?>
        <td>
            <INPUT NAME="options[]" TYPE="CHECKBOX" VALUE="<?php echo $value->id ?>"><?php echo $value->namagrup?>
        </td>

        <?php
        }
        else
        {
        ?>
            <td>
            <INPUT NAME="options[]" TYPE="CHECKBOX" VALUE="<?php echo $value->id ?>"><?php echo $value->namagrup?>
            </td>
        <?php
            echo '</tr><tr>';
        }
        $count++;
    }
    echo '</tr></table>';
    ?>
<?php //echo form_label(" Grup : ");?>
<?php
    /*if(isset($query))
    {
        $grup['']='All';
        foreach($query->result() as $value)
        {
            $grup[$value->id]= $value->namagrup;
        }

        echo form_dropdown('grup',$grup,'');
    }*/

    ?>
<?php echo br(2)?>
<?php
    echo form_label(" Format : ");
    $options = array('PDF'=>'PDF','EXCEL'=>'EXCEL');
    echo form_dropdown('format', $options);
    echo form_label("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wilayah : ");
    $grup2['0']='ALL';
    foreach($query2->result() as $value)
    {
        $grup2[$value->id]= $value->wilayah;
    }
    echo form_dropdown('wilayah',$grup2,0);
    
?>
</div>
</div>
<div class='con'>
<?php
echo '<br/>'.form_submit('submit','Proses');
?>
</div>
<?php echo form_close();?>



