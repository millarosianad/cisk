<script type="text/javascript">
  $(document).ready(function() {
    $("#inputField").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
</script>

<?php echo form_open($uri_asset);?>
<?php echo '<h2>'.form_label($page_title).'</h2>';?>
<?php echo form_label(" Date : ");?>
<input type="text" name="tanggal" size="12" class='form-inline' id="inputField" />
<?php
    echo br(2).form_label("Grup : ");
    echo '<table class="table"><tr>';
    $count=1;
    foreach($query->result() as $value)
    {
        if($count%4!=0)
        {
        ?>
        <td>
            <div class="checkbox-inline">
                <INPUT NAME="options[]"  TYPE="CHECKBOX" VALUE="<?php echo $value->id ?>"><?php echo $value->namagrup?>
            </div>
        </td>

        <?php
        }
        else
        {
        ?>
            <td>
                <div class="checkbox-inline">
                    <INPUT NAME="options[]" TYPE="CHECKBOX" VALUE="<?php echo $value->id ?>"><?php echo $value->namagrup?>
                </div>
            </td>
        <?php
            echo '</tr><tr>';
        }
        $count++;
    }
    echo '</tr></table>';
    ?>


<div class='row'>
    <div class='col-md-3'>    
        <?php
            echo form_label(" Format : ");
            $options = array('PDF'=>'PDF','EXCEL'=>'EXCEL');
            echo form_dropdown('format', $options,'','class=form-control');
            
        ?>
    </div>
    <div class='col-md-3'>    
    <?php
        $grup2['0']='ALL';
        echo form_label("Wilayah : ");
        foreach($query2->result() as $value)
        {
            $grup2[$value->id]= $value->wilayah;
        }
        echo form_dropdown('wilayah',$grup2,0,'class="form-control"');
    ?>
    </div>
    <div class='col-md-3'>   
        <?php echo br().form_submit('submit','PRINT','class="btn btn-primary"');?>
    </div>
    
    
</div>

<?php echo form_close();?>

<?php 
echo form_open($uri_asset_jual);
echo br();
?>
<h3>Asset Terjual</h3>
<?php
    echo form_label(" Format : ");
    echo "<div class='form-inline'>";
    $options = array('PDF'=>'PDF','EXCEL'=>'EXCEL');
    echo form_dropdown('format', $options,'','class=form-control');
    echo form_submit('submit','PRINT','class="btn btn-primary"');
?>
<?php 
echo form_close();
echo br(2);
?>


