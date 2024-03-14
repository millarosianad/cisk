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
    </div>


    <div class="col-xs-3">
        Tahun
    </div>
    <div class="col-xs-5">
        <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
    </div>
    <div class="col-xs-11">&nbsp;</div>
    <div class="col-xs-3">        
        
    </div>
    <div class="col-xs-5">
        <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
        <?php echo form_close();?>

    </div>
</div>