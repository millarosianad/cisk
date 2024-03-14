<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

<script type="text/javascript">       
    $(document).ready(function() { 
        $("#supp").click(function(){
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

    <?php echo form_open($url);?>    

    <?php
        //echo form_label(" SUPPLIER : ");
        $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier[$value->supp]= $value->namasupp;
        }
    ?>

    <?php 
        $interval=date('Y')-2013;
        $year=array();
        $year['2020']='2020';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2013]=''.$i+2013;
        }
    ?>

    

    <div class="row">        
        <div class="col-xs-16">
            <?php echo br(3); ?>
            <h3>Data Omzet</h3><hr />
        </div>

        <div class="col-md-3">
            <div class="form-group">
                
                
            </div>
        </div>
    </div>


        <div class="col-xs-3">
            Tahun (*)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
            Supplier (*)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
            Group Product
        </div>

        <div class="col-xs-5">
                <?php
                    $group=array();
                    $group['0']='--';
                   
                ?>
            <?php  echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"'); ?>

        </div>
        <div class="col-xs-11">&nbsp;</div>
        <div class="col-xs-3">
            Status Closing di Bulan 
        </div>
        <?php 
            $bulan = array(
                // '1'  => 'Januari',
                // '2'  => 'Februari',
                //'3'  => 'Maret', 
                //  '4'  => 'April',
                // '5'  => 'Mei',
                // '6'  => 'Juni',
                // '7'  => 'Juli',
                // '8'  => 'Agustus',
                '9'  => 'September',
                // '10'  => 'Oktober',
                // '11'  => 'November',
                // '12'  => 'Desember 2019'               
              );
        ?>
        <div class="col-xs-5">
            <?php echo form_dropdown('bulan', $bulan,'','class="form-control"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
            Tipe Outlet <?php echo anchor(base_url()."omzet/info", '(lihat detail ?)'); ?>
        </div>

        <div class="col-xs-8">
            <label class="fancy-checkbox">
                <input type="checkbox" name="tipe_1" value="1"><span> Apotik</span>
            </label>
            <label class="fancy-checkbox">&nbsp;</label>
            <label class="fancy-checkbox">
                <input type="checkbox" name="tipe_2" value="2"><span> Perusahaan Besar Farmasi</span>
            </label>
            <label class="fancy-checkbox">&nbsp;</label>
            <label class="fancy-checkbox">
                <input type="checkbox" name="tipe_3" value="3"><span> MT Lokal</span>
            </label>
        </div>

        
        <div class="col-xs-3"></div>        
        <div class="col-xs-5">
            
            <?php
                $data = array(
                  'menuid'  => $getmenuid
                );
            echo form_hidden($data);
            ?>
        </div>       
        <div class="col-xs-11"><hr></div>

        <div class="col-xs-3">        
            
        </div>
        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>

        </div>
    </div>
