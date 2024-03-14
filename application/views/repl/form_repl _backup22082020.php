
<script src="<?php echo base_url() ?>assets/js/script.js"></script>

<script type="text/javascript">       
    $(document).ready(function() { 
        $("#subbranch").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>outlet/buildSalesName",    
            data: {id_subbranch: $(this).val()},
            type: "POST",
            success: function(data){
                $("#salesman").html(data);
                }
            });
        });
    });
      
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
            
</script>

<?php echo form_open($url);
?>
<h2>
<?php echo form_label($page_title);?>
</h2><hr />
<div class='row'>
    <div class="col-md-2">
        <div class="form-group">
            <?php
                echo form_label("Tahun");
                $interval=date('Y')-2020;
                $options=array();
                $options['0']='- Pilih Tahun -';
                $options['2020']='2020';
                for($i=1;$i<=$interval;$i++)
                {
                    $options[''.$i+2020]=''.$i+2020;
                }
                echo form_dropdown('year', $options, $options['0'],'class="form-control"  id="year"');
            ?>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <?php        
                echo form_label("Sub Branch");
                $options=array();
                $options['0']='- Pilih Sub Branch -';
                echo form_dropdown('nocab', $options, 'ALL','class="form-control" id="subbranch"');
            ?>
        </div>
    </div> 
    <div class="col-md-2">
        <div class="form-group">
            <?php        
                echo form_label("Cycle");
                $options=array();
                $options['0']='- Pilih Cycle -';
                $options['3']=' 3 bulan';
                $options['6']=' 6 bulan';
                echo form_dropdown('cycle', $options, 'UNIT','class="form-control"');
            ?>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <?php        
                echo form_label("Tanggal");
            ?>
            <input type="text" class="form-control" autocomplete="off" id="datepicker" name="tglrepl" placeholder="tanggal repl">
        </div>
    </div>  
    <div class="col-md-2">
        <div class="form-group">
            <?php        
                echo form_label("Bulan GIT");
            ?>
            <input type="text" class="form-control" autocomplete="off" name="git" placeholder="contoh : 1,2,3">
        </div>
    </div>  
</div>
<div class = "row">
    <div class="col-md-2">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
        </div>
    </div>    
</div>