<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View Surat Jalan</title>        
        <script src="<?php echo base_url() ?>assets/js/script.js"></script>
    </head>
<body>

    <?php echo form_open($url);?>   
    <div class="row">        
        <div class="col-xs-12">
            <?php echo br(1); ?>
            <h3><?php echo $page_title; ?><hr />
        </div>
    </div>

    <div class = "row">    
        <div class="col-xs-2">
            Periode            
        </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" id="datepicker3" name="periode" placeholder="" autocomplete="off"> 
        </div>
        

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">        
            
        </div>
        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>

        </div>
    </div>
