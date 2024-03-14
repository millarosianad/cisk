<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>


<?php echo form_open($url1);?>
<h2>
<?php echo form_label($page_title);?>
</h2>
<hr />

<div class='row'>
    <div class="col-md-5">
        <div class="form-group">
            <?php
                echo form_label("Client : ");
                foreach($query->result() as $value)
                {
                    $dd[$value->grup_lang]= $value->grup_nama;                                         
                }
                echo form_dropdown('grup_lang',$dd,'','class="form-control" id="grup_lang"');

            ?>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
        </div>
    </div>    
</div>

<?php echo form_close();?>



<?php
/*
if(isset($kode))
{
        //echo form_open($uri2);
        
        $fakturdd=array();
        foreach ($kode->result() as $row)
        {
            $fakturdd[$row->nodokjdi]=$row->nomor;
        }
        echo $lang;
}
*/
?>
<br>


<?php echo form_open($url2);?>

<div class='row'>
    <div class='col-md-10'>

<?php
    echo form_label("Silahkan Pilih Faktur No : ");
    $supp='';
    echo '<table class="table table-hover">';
    $count=1;


    foreach($kode->result() as $value)
    {

        if($count%6!=0)
        {
        ?>
        <td>
           <div class="checkbox-inline">
           <input name="options[]" type="CHECKBOX" id="" value="<?php echo $value->nodokjdi ?>"><?php echo $value->nomor ?>
           </div>
        </td>
        <!-- <?php //echo $value->supp."[]" ?> -->
        
        <?php
        }
        else
        {
            ?>
            <td>
                <div class="checkbox-inline">
                    <input name="options[]"" type="CHECKBOX" id="" value="<?php echo $value->nodokjdi ?>"><?php echo $value->nomor ?>
                </div>
            </td>
        <?php
            echo '</tr><tr>';
        }
        $count++;
    }
    echo '</tr></table>';
?>

    </div>
</div>

<div class='row'>
    <div class='col-md-3'>    
        <?php
            echo form_label("Keterangan : ");
            $ketdd=array('Faktur Lunas'=>'Faktur Lunas','Copy Faktur'=>'Copy Faktur');
            echo form_dropdown('keterangan',$ketdd,'','class="form-control"');
        ?>  
    </div>
</div>

<div class='row'>
    <div class='col-md-10'>    
        <?php
        echo br(1);
            echo form_submit('submit','Tambah','class="btn btn-info"');
        ?>  
    </div>

</div>

<div class='row'>
    <div class='col-md-5'> 
        &nbsp;
        <?php echo br(2); ?>
    </div>
</div>

<?php echo form_close();?>