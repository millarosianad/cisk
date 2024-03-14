<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

<script type="text/javascript">       
    $(document).ready(function() { 
        $("#year").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>all_stock/build_namacomp",    
            data: {id: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
    });
            
</script>

<script type="text/javascript">       
    $(document).ready(function() { 
        $("#year").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>all_stock/build_namacomp",    
            data: {id: $(this).val()},
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

<div class='row'>

    <div class="col-md-3">
        <div class="form-group">
            <?php
                echo form_label(" Tahun : ");
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

    <div class="col-xs-3">
        <div class="form-group">
            <?php   
                echo form_label(" Bulan : ");     
                $bulan=array();
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
    </div>
    <?php
        //echo form_label(" SUPPLIER : ");
        $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier[$value->supp]= $value->namasupp;
        }
    ?>

    <div class="col-xs-5">
            <?php 
            echo form_label(" Supplier : ");
            echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
    </div>
    

    </div>

    <div class='row'>
    <div class="col-xs-5">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
        </div>
    </div>    
</div>

<br />
<?php echo form_close();?>