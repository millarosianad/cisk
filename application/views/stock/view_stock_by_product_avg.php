<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View All Omzet</title>
        <!-- Load Jquery, Bootstrap, dan DataTables dari CDN -->
        <!-- buka url ini: http://pastebin.com/index/WeaY5Fra -->
        <!-- load Jquery dari CDN -->
        
        <!--
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        -->

        <script type="text/javascript" language="javascript" src="<?php echo base_url('assets/js/jquery-1.10.2.min.js') ?>"></script>
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
    </head>
    <body>
    <?php $no = 1; ?>
    <div class="col-xs-16">            
            <h3>Data AVG Sales per 6 bulan dan Stok Akhir</h3><hr />
    </div>
    <div class="col-xs-16">

        <a href="<?php echo base_url()."all_stock/stock_avg/"; ?>  " class="btn btn-success" role="button"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> kembali</a>
        
        <a href="<?php echo base_url()."all_stock/export_avg/". $tahun."/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>

        
    </div>
    <hr>
    <div class="col-xs-16">

    keterangan tambahan : <br>
     - Bulan berjalan tidak di hitung dalam pencarian nilai `Total 6 bln` <br>
     - Bulan yang di dalamnya tidak ada penjualan, tidak dianggap sebagai pembagi dalam mencari nilai rata-rata (`AVG 6 Bln`)
       
        
    </div>
    <hr>
    <?php $no = 1; ?>
    <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>                
                        <th>No</th>                          
                        <th>SubBranch</th>
                        <th>KodeProduk</th>
                        <th>NamaProduk</th>
                        <th>Total6bln(Unit)</th>
                        <th>Total6bln(Value)</th>
                        <th>Avg6bln(Unit)</th>
                        <th>Avg6bln(Value)</th>
                        <th>Stok Akhir</th> 
                        <th>DOI</th>                                                               
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($stocks)): ?>
                    <?php foreach($stocks as $stock) : ?>
                    <tr>                            
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $stock->nama_comp; ?></td>
                        <td><?php echo $stock->kodeprod; ?></td>
                        <td><?php echo $stock->namaprod; ?></td>
                        <td><?php echo number_format($stock->total_unit); ?></td>
                        <td><?php echo number_format($stock->total_value); ?></td>
                        <td><?php echo number_format($stock->avg_unit); ?></td>
                        <td><?php echo number_format($stock->avg_value); ?></td>
                        <td><?php echo number_format($stock->stok_akhir); ?></td>
                        <td><?php echo $stock->doi; ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
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