<script type="text/javascript">       
    $(document).ready(function() 
    { 
        // console.log('b');
        $("#supp").click(function()
        {
            // console.log('a');
            $.ajax({
            url:"<?php echo base_url(); ?>omzet/buildgroup",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#group").html(data);
                }
            });
        });
    });            
</script>
<?php 
    $supplier=array();
    foreach($get_supp->result() as $value)
    {
        $supplier[$value->supp]= $value->namasupp;
    }    

    $group=array();
    $group['0']='--';  

    echo form_open($url);  
?>

<div class="card">

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Periode Awal</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="periode_1" required />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Periode Akhir</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="periode_2" required />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Breakdown</label>
            <div class="col-sm-9">        
                <div class="col-sm-6">
                    <div class="checkbox-color checkbox-primary">
                        <input id="checkbox2" type="checkbox"  name="breakdown_2" value="1">
                        <label for="checkbox2">
                            salesman
                        </label>
                    </div>
                    <div class="checkbox-color checkbox-primary">
                        <input id="checkbox3" type="checkbox"  name="breakdown_3" value="1">
                        <label for="checkbox3">
                            class
                        </label>
                    </div>
                    <div class="checkbox-color checkbox-primary">
                        <input id="checkbox4" type="checkbox"  name="breakdown_4" value="1">
                        <label for="checkbox4">
                            type
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>
                    <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <?php
    $this->load->view('templates/layout_button_produk');
    ?>
    <hr>
</div>

</div>







