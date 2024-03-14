<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<script type="text/javascript">       
      
    $(document).ready(function() { 
        $("#year").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>c_dp/build_namacomp",    
            data: {id_year: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
    });

    $(document).ready(function() { 
        $("#supp").click(function(){
            $.ajax({
            url:"<?php echo base_url(); ?>c_dp/buildgroup",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#group").html(data);
                }
            });
        });
    });          
            
</script>
<h2><?php echo $page_title;?></h2>
<?php echo form_open($url);
?>
<hr>
<div class='row'>
    <div class="col-md-3">
        <div class="form-group">
            <?php
                echo form_label("Tahun : (1)");
                //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
                $interval=date('Y')-2015;
                $options=array();
                $options['0']='- Pilih Tahun -';
                for($i=1;$i<=$interval;$i++)
                {
                    $options[''.$i+2015]=''.$i+2015;
                }
                echo form_dropdown('year', $options, $options['0'],'class="form-control"  id="year"');
            ?>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <?php        
                echo form_label("Sub Branch : (2)");
                $options=array();
                $options['0']='- Pilih Sub Branch -';
                echo form_dropdown('nocab', $options, 'ALL','class="form-control" id="subbranch"');
            ?>
        </div>
    </div> 

    <div class="col-md-3">
        <div class="form-group">
            <?php        
                echo form_label("Supplier : (3)");
                $supplier=array();
                foreach($query->result() as $value)
                {
                    $supplier[$value->supp]= $value->namasupp;
                }
                echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');
            ?>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <?php        
                echo form_label("Group Product : (4)");
                $group=array();
                $group['0']='--';
                echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"');
            ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?php        
                echo form_label("UNIT/VALUE : (5)");
                $options=array();
                $options['0']='UNIT';
                $options['1']='VALUE';
                echo form_dropdown('uv', $options, 'UNIT','class="form-control"');
            ?>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
        </div>
    </div>    
</div>
