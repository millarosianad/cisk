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
        foreach($supp->result() as $value)
        {
            $supplier['0']= ' - Pilih Supplier - ';
            $supplier[$value->supp]= $value->namasupp;
        }
    ?>

    <?php 
        $interval=date('Y')-2010;
        $year=array();
        $year['0']=' - Pilih Tahun - ';
        //$year['2019']='2019';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2010]=''.$i+2010;
        }
    ?>

    <div class="row">        
        <div class="col-xs-12">
            <?php echo br(1); ?>
            <h3><?php echo $page_title; ?><hr />
        </div>
    </div>

    <div class = "row">    
        <div class="col-xs-2">
            Tahun            
        </div>
        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
        </div>
        <div class="col-xs-12">&nbsp;</div>
        <div class="col-xs-2">
            Supplier
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
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

        <div class="col-xs-5">
            
            <?php
                $data = array(
                  'menuid'  => $getmenuid
                );
            echo form_hidden($data);
            //echo "menuid di view : ".$getmenuid;
            ?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">        
            
        </div>
        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>

        </div>
    </div>
