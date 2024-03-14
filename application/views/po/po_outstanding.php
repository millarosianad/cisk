<!doctype html>
<html>
    <head>        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>List Order</title>
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
        <script type="text/javascript">       
        $(document).ready(function() { 
        $("#supp").click(function(){
            $.ajax({
            url:"<?php echo base_url(); ?>omzet/buildgroup",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
        });            
        </script>
    </head>
    <body>
    <br><br><br>
    </div>

    <?php echo form_open($url);?>   
    <?php
        //echo form_label(" SUPPLIER : ");
        $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier[$value->supp]= $value->namasupp;
        }
    ?>

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-11">
            <h3><?php echo br(1).' '.$page_title; ?></h3><hr />
        </div>
    </div>

    <div class="row">   
        <div class="col-xs-1"></div>
        
        <div class="col-xs-3">
            Supplier
        </div>

        <div class="col-xs-4">
            <?php $jenis_data=array(
                '001'=>'DELTOMED',     
                '005'=>'ULTRA SAKTI',                   
                '012'=>'INTRAFOOD',                   
            );?>
            <?php echo form_dropdown('supp',$jenis_data,'','class="form-control"');?>
        </div>
        
    </div>
    <br>

    <div class="row">   
        <div class="col-xs-1"></div>        
        <div class="col-xs-3">
            Periode Tanggal PO
        </div>
        <div class="col-xs-4">
            <div class="input-group input-daterange">
                <input type="text" class = 'form-control' id="datepicker2" name="periode1" placeholder="" autocomplete="off">
                <div class="input-group-addon">to</div>
                <input type="text" class = 'form-control' id="datepicker" name="periode2" placeholder="" autocomplete="off">
            </div>
        </div>        
    </div><br>
            
    <div class="row">   
        <div class="col-xs-1"></div>
        
        <div class="col-xs-3">
            Filter
        </div>

        <div class="col-xs-4">
            <label class="fancy-checkbox">
                <input type="checkbox" name="status_do" value="1"><span> Tampilkan hanya PO yang belum dipenuhi</span><br>
            </label><br>
            <label class="fancy-checkbox">
                <input type="checkbox" name="status_total" value="1"><span> Tampilkan Total (Unit & Value)</span><br>
            </label>
        </div>
   
        
    </div>
    <br>

    <div class="row">   
        <div class="col-xs-1"></div>        
        <div class="col-xs-3">
            
        </div>
        <div class="col-xs-3">
            <div class="input-group input-daterange">               
                <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
                <?php echo form_close();?>
            </div>
        </div>
        
    </div>
    




</div>
<script src="<?php echo base_url() ?>assets/js/script.js"></script>
    </body>
</html>