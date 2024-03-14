<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View Kalender Data</title>
        <!-- Load Jquery, Bootstrap, dan DataTables dari CDN -->
        <!-- buka url ini: http://pastebin.com/index/WeaY5Fra -->
        <!-- load Jquery dari CDN -->
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
    </head>
    <body>


    <h2>
        <?php echo form_label($page_title);?>
    </h2>
    <hr />

    <?php $no = 1 ; ?>
    <?php 
        echo "<pre>";
        echo "<h4>Informasi !!</h4>";
        echo "Halaman ini menampilkan <strong>TANGGAL TERAKHIR TRANSAKSI</strong> setiap bulannya<br>";
        echo "anda memilih tahun ";
        echo "</pre>";
    ?>
    <hr>

    <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th><center>No</center></th>                                          
                            <th><center>Sub Branch</center></th>
                            <th><center>Last Upload</center></th>
                            <th><center>Tanggal Data</center></th>
                            <th><center>Zip File</center></th> 
                            <th><center>Status Closing</center></th>
                            <th><center>Omzet</center></th> 
                    </thead>
                    <tbody>
                        <?php foreach($query as $querys) : ?>
                        <tr>        
                            <td width="1%"><center>
                                <?php echo $no; ?></center>
                            </td>
                            <td width="5%">
                                <?php echo $querys->nama_comp; ?>
                            </td>
                            <td width="5%"><center>
                                <?php echo $querys->lastupload; ?></center>
                            </td>
                            <td width="3%"><center>
                                <?php echo $querys->tanggal; ?></center>
                            </td>
                            <td width="5%">
                                <?php echo $querys->filename; ?>                                  
                            </td>
                            <td width="5%"><center>
                                <?php if ($querys->status_closing == 0) {
                                         echo "belum closing";
                                     } else{
                                        echo "closing"; 
                                    }  ?> </center>                        
                            </td>
                            <td width="5%"><center><?php echo $querys->omzet; ?> </center>                        
                            </td>

                        </tr>
                        <?php $no++; ?>
                    
                    <?php endforeach; ?>
                    </tbody>
                    </table>
        </div> 
    </div>
        
       <script>
    $(document).ready(function(){
        $('#myTable').DataTable( {
            
        });
    });
    </script>
    <!--jquery dan select2-->
    <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/select2/js/select2.min.js') ?>"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2({
                placeholder: "Please Select"
            });
        });
    </script>
    </body>
</html>