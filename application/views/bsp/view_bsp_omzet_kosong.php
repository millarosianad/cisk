<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

<script type="text/javascript">       
    $(document).ready(function() { 
        $("#supp").click(function(){
            $.ajax({
            url:"<?php echo base_url(); ?>all_bsp/buildgroup",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#group").html(data);
                }
            });
        });
    });            
</script>


    <?php //echo br(3); ?>
    <?php echo form_open('all_bsp/data_omzet_bsp_hasil/');?>
    <?php //echo form_label($page_title);?>
    <?php 
        //echo form_label(" Year : ");
        //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
        $interval=date('Y')-2010;
        $year=array();
        $year['2010']='2010';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2010]=''.$i+2010;
        }
        //echo br(5);
        //echo form_dropdown('tahun', $year,date('Y'),"class='form-control'");
    ?>

    <?php
        //echo form_label(" SUPPLIER : ");
        $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier['x'] = ' - Pilih Supplier - ';
            $supplier[$value->supp]= $value->namasupp;
        }
        //echo form_dropdown('supp', $options, 'All',"class='form-control'");
    ?>
    
    <div class="row">        
        <div class="col-xs-16">
            <?php echo br(3); ?>
            <h3>Omzet BSP</h3><hr />
        </div>

        <div class="col-xs-2">
            Tahun (*) 
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,date('Y'),"class='form-control'");?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
           Supplier (*) 
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,date('Y'),'class="form-control"  id="supp"');?>
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
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>
        </div>
    </div>
        <!--jquery dan select2-->
        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/select2/js/select2.min.js') ?>"></script>
        <script>
            $(document).ready(function () {
                $(".select2").select2({
                    placeholder: "Please Select"
                });
            });
        </script>
    </body>
</html>