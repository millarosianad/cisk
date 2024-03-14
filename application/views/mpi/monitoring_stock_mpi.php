<script src="<?php echo base_url() ?>assets/js/script.js"></script>
<?php echo form_open($url);?>
        
 
    <div class="row">        
        <div class="col-xs-11">
            <?php echo br(3); ?>
            <h3><?php echo $page_title; ?></h3><hr />
        </div>
    </div>

    

    <div class="col-xs-12">
        &nbsp;
    </div>

    <div class="col-xs-2">
        Tanggal Cut Off Stock
    </div>

    <div class="col-xs-3">        
        <input type="text" class = 'form-control' id="datepicker2" name="cut_off_stock" placeholder="" autocomplete="off">
    </div>

    <div class="col-xs-12">
        &nbsp;
    </div>

    <div class="col-xs-2">
        Rata-rata Sales
    </div>

    <div class="col-xs-3">        
        <?php 
            $avg = array(
                '3'  => '3 bulan',
                '6'  => '6 bulan',           
                );
        ?>
        <?php echo form_dropdown('avg', $avg,'','class="form-control"');?>
    
    </div>


    <div class="col-xs-12">
        &nbsp;
    </div>

    <div class="col-xs-2">
        &nbsp;
    </div>

    <div class="col-xs-3">  
        <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
        <?php echo form_close();?>
    </div>