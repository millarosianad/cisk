<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<?php echo form_open($url);?>

<h2>
<?php echo form_label($page_title);?>
</h2>
<hr />
<div class='row'>
    <div class="col-md-3">
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <?php
                echo form_input('tanggal','','id="datepicker" class="form-control" autocomplete="off"');
            ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?php echo form_label("Range"); ?>
            <?php $range=array(
                    0=>'Minggu',
                    1=>'Bulan'
                );?>
                <?php echo form_dropdown('range',$range,'','class="form-control"');?>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
        </div>
    </div>         
</div>
<?php echo form_close();?>
<!-- Load SCRIPT.JS which will create datepicker for input field  -->        
<script src="<?php echo base_url() ?>assets/js/script.js"></script>