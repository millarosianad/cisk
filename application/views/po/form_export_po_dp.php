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
    <div class="row">        
        <div class="col-xs-16">
            <?php echo br(3); ?>
            <h3>Export PO DP</h3><hr />
        </div>
    </div>

        <div class="col-xs-2">
            Tahun
        </div>

        <div class="col-xs-5">
        <?php 
            $interval=date('Y')-2010;
            $options=array();
            $options['0']='- Pilih Tahun -';
            $options['2010']='2010';
            for($i=1;$i<=$interval;$i++)
            {
                $options[''.$i+2010]=''.$i+2010;
            }
            echo form_dropdown('tahun', $options, $options['0'],'class="form-control"  id="year"');          
        ?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Bulan
        </div>

        <div class="col-xs-5">
            <?php        
                $bulan=array();
                $bulan['']=' -- Pilih Bulan --';
                $bulan['1']='Januari';
                $bulan['2']='Februari';
                $bulan['3']='Maret';
                $bulan['4']='April';
                $bulan['5']='Mei';
                $bulan['6']='Juni';
                $bulan['7']='Juli';
                $bulan['8']='Agustus';
                $bulan['9']='September';
                $bulan['10']='Oktober';
                $bulan['11']='November';
                $bulan['12']='Desember';
                echo form_dropdown('bulan', $bulan, '0','class="form-control"');
            ?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">        
            
        </div>
        <div class="col-xs-5">
            <?php echo form_submit('submit','Export to Excel','class="btn btn-primary"');?>
            <?php echo form_close();?>

        </div>
    </div>
