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
        $interval=date('Y')-2010;
        $year=array();
        $year['2018']='2018';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2010]=''.$i+2010;
        }
    ?>

    <div class="row">        
        <div class="col-xs-16">
            <h3><?php echo $page_title; ?></h3><hr />
        </div>

        <div class="col-md-3">
            <div class="form-group">
            </div>
        </div>
    </div>

        <div class="col-xs-2">
            Tahun
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('year', $year,'','class="form-control"');?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Periode
        </div>

        <div class="col-xs-5">
            <?php        
                $periode=array();
                $periode['1']=' 1 Tahun (Januari - Desember) ';
                $periode['2']=' 6 Bulan (Januari - Juni)';
                $periode['3']=' 6 Bulan (Juli - Desember)';
                echo form_dropdown('periode', $periode, '0','class="form-control"');
            ?>
        </div>

        <div class="col-xs-11">&nbsp;</div>


        <div class="col-xs-2">
            Supplier
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supplier', $supplier,'','class="form-control"  id="supp"');?>
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

        <div class="col-xs-2">
            Branch
        </div>

        <div class="col-xs-5">
            <?php        
                $branch=array();
                $branch['88']=' Javas Karya Tripta - JKT ';
                $branch['95']=' Jaya Bakti Raharja - JBR';
                $branch['89']=' Javas Tripta Gemala - JTG';
                $branch['91']=' Javas Tripta Mandala - JTM';
                $branch['27']=' Javas Tripta Sejahtera - JTS';
                $branch['98']=' Duta Intra Yasa - DIY';
                $branch['j2']=' Javas Bali Lestari - JBL';
                echo form_dropdown('branch', $branch, '0','class="form-control"');
            ?>
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

        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>

        </div>
    </div>
