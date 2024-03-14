<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

<script type="text/javascript">       
    $(document).ready(function() { 
        $("#year").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>all_stock/build_namacomp",    
            data: {id_year: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
    });
            
</script>

<?php echo form_open($url);?>
<h2>
<?php echo form_label($page_title);?>
</h2><hr />

<?php echo br(2);?>
<div class='row'>

    <div class="col-md-3">
        <div class="form-group">
            <?php
                echo form_label(" Silahkan Pilih Tahun : ");
                //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
                $interval=date('Y')-2010;
                $options=array();
                $options['0']='- Pilih Tahun -';
                $options['2010']='2010';
                for($i=1;$i<=$interval;$i++)
                {
                    $options[''.$i+2010]=''.$i+2010;
                }
                echo form_dropdown('year', $options, $options['0'],'class="form-control"  id="year"');
            ?>
        </div>
    </div>

    
    <div class="col-md-3">
        <div class="form-group">
            <?php        
                echo form_label("Silahkan Pilih Sub Branch : ");
                $options=array();
                $options['0']='- Pilih Sub Branch -';
                echo form_dropdown('nocab', $options, 'ALL','class="form-control" id="subbranch"');
            ?>
        </div>
    </div>     
    



    
     <div class="col-md-3">
        <div class="form-group">
            <?php        
                echo form_label("Silahkan Pilih UNIT/VALUE : ");
                $options=array();
                $options['0']='UNIT';
                $options['1']='VALUE';
                echo form_dropdown('uv', $options, 'UNIT','class="form-control"');
            ?>
        </div>
    </div>
    

    



    <div class="col-md-4">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
        </div>
    </div>    
</div>

<br />

<?php echo form_close();?>