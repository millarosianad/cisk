<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<!-- Load Datatables dan Bootstrap dari CDN -->
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">


<h2>Upload File</h2><hr />

<div class='row'>
    <div class="col-md-20">
        <div class="form-group">
            <?php
                //echo "<pre>";
                //echo "<h1>omzet : Rp. ".$omzet."</h1><br>";
                //echo "</pre>";
                echo "<pre>";
                echo "<h3> ~ PREVIEW UPLOAD OMZET ~</h3>";
                echo "Anda sudah meng-Upload Data sebagai berikut :<br>";
                echo "- nocab : ".$nocab."<br>"; 
                echo "- tahun : ".$tahun."<br>";
                echo "- bulan : ".$bulan."<br>";
                echo "- filename : ".$filenamezip."<br>";
                
                echo "- omzet : <strong><font size = 4px>".$omzet."</font></strong><br>";
                echo "</pre>";
                echo "<h3>Apakah omzet sesuai ?</h3> <br>";
                echo "- Jika total omzet sudah benar, klik tombol di bawah ini <br>";
                echo " &nbsp;   ";?>
                <a href="<?php echo base_url()."all_upload/prosesOmzet/$nocab/$tahun/$bulan/$id" ?> " class="btn btn-primary btn-large" role="button" target="_blank"> Submit Omzet</a>
                <?php
                echo "<br><br>- jika tidak, silahkan <strong> Upload ulang / hubungi IT </strong> <br>";
                
                ?>
                
        </div>        
    </div>
</div>
<hr>
<div class='row'>
    <div class="col-md-4">
        <div class="form-group">
            <?php 
                //print_r($msg);                
            ?>
        </div>
 
    </div>
</div>