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
    $interval=date('Y')-2015;
    $year=array();
    $year['2021']='2021';
    for($i=1;$i<=$interval;$i++)
    {
        $year[''.$i+2015]=''.$i+2015;
    }

    $bulan = array(
        '1'  => 'Januari',
        '2'  => 'Februari',
        '3'  => 'Maret', 
        '4'  => 'April',
        '5'  => 'Mei',
        '6'  => 'Juni',
        '7'  => 'Juli',
        '8'  => 'Agustus',
        '9'  => 'September',
        '10'  => 'Oktober',
        '11'  => 'November',
        '12'  => 'Desember'               
    );

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
    <div class="col-sm-10">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tahun</label>
            <div class="col-sm-10">
                <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
            </div>
        </div>
    </div>

    <div class="col-sm-10">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Bulan</label>
            <div class="col-sm-10">
                <?php echo form_dropdown('bulan', $bulan,'','class="form-control"');?>
            </div>
        </div>
    </div>

    <div class="col-sm-10">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Supplier</label>
            <div class="col-sm-10">
                <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
            </div>
        </div>
    </div>

    <div class="col-sm-10">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Group</label>
            <div class="col-sm-10">
                <?php  echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"'); ?>
            </div>
        </div>
    </div>

    <div class="col-sm-10">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Breakdown</label>
            <div class="col-sm-10">        

                <div class="checkbox-color checkbox-primary">
                    <input id="checkbox1" type="checkbox"  name="tipe_1" value="1">
                    <label for="checkbox1">
                        Kode produk
                    </label>
                </div>
                
                <div class="checkbox-color checkbox-primary">
                    <input id="checkbox2" type="checkbox"  name="tipe_2" value="1">
                    <label for="checkbox2">
                        Class Outlet
                    </label>
                </div>

                <div class="checkbox-color checkbox-primary">
                    <input id="checkbox3" type="checkbox"  name="tipe_3" value="1">
                    <label for="checkbox3">
                    Tipe Outlet
                    </label>
                </div>

            </div>
        </div>
    </div>

    <div class="col-sm-10">
        <div class="form-group row">            
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
                <?php echo form_close();?>
            </div>
        </div>
    </div>

    <!-- <hr> -->
    
    <!-- <div class="card-block"></div> -->
</div>







