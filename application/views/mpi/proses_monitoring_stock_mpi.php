<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View All Omzet</title> 
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

        <script src="<?php echo base_url() ?>assets/js/script.js"></script>
    
    </head>
    <body>

    <?php echo form_open($url);?>
        
    <?php 
        $interval=date('Y')-2018;
        $year=array();
        $year['2020']='2020';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2018]=''.$i+2018;
        }
    ?>
 
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

    <div class="col-xs-6">  
        <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
        <?php echo form_close();?>
        <a href="<?php echo base_url()."all_stock/export_monitoring_doi"; ?>" class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
    
    </div>

    </div>
    <hr />

    </div>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th>Nama Cabang</font></th>
                            <th>Nama Produk</th>
                            <th>Total Unit</th>
                            <th>AVG Unit</th>
                            <th>Cut off Stock</th>
                            <th>Stock Onhand (Unit)</th>
                            <th>GIT (Unit)</th>
                            <th>DOI Onhand (Unit)</th>
                            <th>DOI Stock Unit (git+onhand)</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($stock as $x) : ?>
                        <tr>                            
                            <td><?php echo $x->nama_cab; ?></td>
                            <td><?php echo $x->nama_produk; ?></td>
                            <td><?php echo $x->total_unit; ?></td>
                            <td><?php echo $x->avg_unit; ?></td>
                            <td><?php echo $x->cut_off_stock; ?></td>
                            <td><?php echo $x->onhand_unit; ?></td>
                            <td><?php echo $x->git_unit; ?></td>
                            <td><?php echo $x->doi_onhand_unit; ?></td>
                            <td><?php echo $x->doi_stock_unit; ?></td> 
                        </tr>
                    <?php endforeach; ?>
                    
                    </tbody>
                    </table>
                    </div>
                    <div class="col-xs-11">&nbsp; </div>
        </div>
    </div>
    

     <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                "ordering": true,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>