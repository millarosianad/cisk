<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

<script type="text/javascript">       
    $(document).ready(function() { 
        $("#dp").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>sales/buildSalesName",    
            data: {id: $(this).val()},
            type: "POST",
            success: function(data){
                $("#salesman").html(data);
                }
            });
        });
    });
            
</script>

<?php echo form_open($url);?>
<h2>
<?php echo form_label($page_title);?>
</h2><hr />

<div class='row'>

    <div class="col-md-2">
        <div class="form-group">
            <?php
                echo form_label(" Year : ");
                //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
                $interval=date('Y')-2010;
                $options=array();
                $options['2010']='2010';
                for($i=1;$i<=$interval;$i++)
                {
                    $options[''.$i+2010]=''.$i+2010;
                }
                echo form_dropdown('year', $options, date('Y'),'class="form-control"');
            ?>
        </div>
    </div>


    <?php
        //echo form_label(" SUPPLIER : ");
        $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier[$value->supp]= $value->namasupp;
        }
    ?>

    <div class="col-xs-5">
            <?php 
            echo form_label(" Supplier : ");
            echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
        </div>
    






    <div class="col-md-2">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
        </div>
    </div>    
</div>

<br />
<?php
echo "<pre>";
echo "info tambahan : Data Value akan tampil hanya di dalam File Excel Setelah Klik Export";
echo "</pre>";
?>
<?php echo form_close();?>