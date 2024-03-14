<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View Stock By Principal</title>
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
            <h3>Data Stock</h3><hr />
    </div>
    <div class="col-xs-16">

        <a href="<?php echo base_url()."all_stock/stock_principal/"; ?>  " class="btn btn-success" role="button"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> kembali</a>
        
        <a href="<?php echo base_url()."all_stock/export_principal/". $id."/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>

        
    </div>
</div>
    <hr>
    <?php $no = 1; ?>

    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>         
                            <th width="1"><font size="2px">No</font></th> 
                            <th width="1"><font size="2px">SubBranch</th>
                            <th>kodeprod</th>
                            <th>namaprod</th>
                            <th><font size="2px">Jan</th>
                            <th><font size="2px">Feb</th>
                            <th><font size="2px">Mar</th>
                            <th><font size="2px">Apr</th>
                            <th><font size="2px">Mei</th>
                            <th><font size="2px">Jun</th>
                            <th><font size="2px">Jul</th> 
                            <th><font size="2px">Agus</th> 
                            <th><font size="2px">Sep</th> 
                            <th><font size="2px">Okt</th> 
                            <th><font size="2px">Nov</th> 
                            <th>Des</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(is_array($stocks)): ?>
                        <?php foreach($stocks as $stock) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                            <td><font size="2px"><?php echo $stock->namacomp; ?></td>
                            <td><font size="2px"><?php echo $stock->kodeprod; ?></td>
                            <td><font size="2px"><?php echo $stock->namaprod; ?></td>
                            <td><font size="2px"><?php echo number_format($stock->b1); ?></td>
                            <td><font size="2px"><?php echo number_format($stock->b2); ?></td>
                            <td><font size="2px"><?php echo number_format($stock->b3); ?></td>
                            <td><font size="2px"><?php echo number_format($stock->b4); ?></td>
                            <td><font size="2px"><?php echo number_format($stock->b5); ?></td>
                            <td><font size="2px"><?php echo number_format($stock->b6); ?></td>
                            <td><font size="2px"><?php echo number_format($stock->b7); ?></td>
                            <td><font size="2px"><?php echo number_format($stock->b8); ?></td>
                            <td><font size="2px"><?php echo number_format($stock->b9); ?></td>
                            <td><font size="2px"><?php echo number_format($stock->b10); ?></td>
                            <td><font size="2px"><?php echo number_format($stock->b11); ?></td>
                            <td><font size="2px"><?php echo number_format($stock->b12); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                    </table>
                    </div>
                    <div class="col-xs-11">&nbsp; </div>
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