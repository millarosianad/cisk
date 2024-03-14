<!doctype html>
<html>
    <head>        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>List Order</title>
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
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
    <br>
    </div>



    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-6"><h3>Import Sales Berhasil. Berikut datanya :</h3></div>
    </div><br>

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-2">Total</div>
        <div class="col-xs-5"><?php echo "<strong>RP. ".$omzet."</strong>"; ?>
            
        </div>
    </div><br>
    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-2">Periode Transaksi</div>
        <div class="col-xs-5"><?php echo $tanggal."-".$bulan."-".$tahun; ?>
            
        </div>
    </div><br>

    
    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-6">Jika hasil omzet tidak sesuai, harap segera hubungi IT</div>
    </div><br>

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-11">
            <h3><?php echo br(1).' '.$page_title; ?></h3><hr />
        </div>
    </div>


    <?php echo form_open_multipart($url);?>
    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-1"></div>
        <div class="col-xs-5">
            
        </div>
    </div><br>

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-2">Pilih XLS Stock</div>
        <div class="col-xs-5">
            <input type="file" name="file" id="file" class="form-control" placeholder="file">
        </div>
    </div><br>

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-2"></div>
        <div class="col-xs-5">            
            
            <!-- <?php echo form_submit('submit','submit','class="btn btn-primary"')?>
            <?php echo form_close(); ?> -->
            </div>
        
    </div>
</div>
    </body>
</html>