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
        $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier[$value->supp]= $value->namasupp;
        }
    ?>
    <?php 
        $interval=date('Y')-2010;
        $year=array();
        $year['2019']='2019';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2010]=''.$i+2010;
        }
    ?>

    <div class="row">        
        <div class="col-xs-16">
            <?php echo br(1); ?>
            <h3><?php echo $page_title; ?></h3><hr />
        </div>

        <div class="col-md-3">
            <div class="form-group">
            </div>
        </div>
    </div>

        <div class="col-xs-2">
            Tahun &sup1
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Supplier &sup2
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
        </div>
        <div class="col-xs-11">&nbsp;</div>
        <div class="col-xs-2">
            Group Product &sup3
        </div>

        <div class="col-xs-5">
                <?php
                    $group=array();
                    $group['0']='--';                   
                ?>
            <?php  echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"'); ?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Status Closing di Bulan
        </div>
        <?php 
            $bulan = array(
                // '1'  => 'Januari',
                // '2'  => 'Februari',
                // '3'  => 'Maret', 
                // '4'  => 'April',
                // '5'  => 'Mei',
                // '6'  => 'Juni',
                // '7'  => 'Juli',
                // '8'  => 'Agustus',
                // '9'  => 'September',
                // '10'  => 'Oktober',
                '11'  => 'November',
                // '12'  => 'Desember'               
              );
        ?>
        <div class="col-xs-5">
            <?php echo form_dropdown('bulan', $bulan,'','class="form-control"');?>
        </div>
        <div class="col-xs-11">&nbsp;</div>


        <div class="col-xs-2"></div>
        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>

        </div>
    </div>
