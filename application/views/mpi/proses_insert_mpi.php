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
        $interval=date('Y')-2019;
        $year=array();
        $year['2020']='2020';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2019]=''.$i+2019;
        }
    ?>
 
    <div class="row">        
        <div class="col-xs-11">
            <?php echo br(3); ?>
            <h3><?php echo $page_title; ?></h3><hr />
        </div>
    </div>

    <div class="col-xs-3">
        Di Masukkan ke dalam tahun
    </div>

    <div class="col-xs-3">        
        <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
    </div>

    <div class="col-xs-11">
        &nbsp;
    </div>

    <div class="col-xs-3">
        Periode dari
    </div>

    <div class="col-xs-3">        
        <input type="text" class = 'form-control' id="datepicker2" name="from" placeholder="" autocomplete="off">
    </div>

    <div class="col-xs-1">
        sampai
    </div>
    <div class="col-xs-3">  
        <input type="text" class = 'form-control' id="datepicker" name="to" placeholder="" autocomplete="off">   
    </div>

    <div class="col-xs-11">
        &nbsp;
    </div>

    <div class="col-xs-3">
        &nbsp;
    </div>

    <div class="col-xs-8">  
        <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
        <?php echo form_close();?>
        <a href="<?php echo base_url()."omzet/insert_mpi_to_db/" ; ?>   " class="btn btn-success" role="button"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Insert ke Db MPM</a>
        <a href="<?php echo base_url()."omzet/export_omzet_mpi"; ?>   " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
    
    </div>

    </div>
    <hr />

    </div>
    <?php $no = 1; ?>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">Tgl Invoice</font></th>
                            <th width="1"><font size="2px">Cabang</th>
                            <th width="1"><font size="2px">Jenis</th>
                            <th width="1"><font size="2px">NoInvoice</th>
                            <th width="1"><font size="2px">NamaLang</th>
                            <th width="1"><font size="2px">SalesType</th>
                            <th width="1"><font size="2px">NamaProduk</th>
                            <th width="1"><font size="2px">Kemasan</th>
                            <th width="1"><font size="2px">Qty</th>
                            <th width="1"><font size="2px">Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($omzets as $omzet) : ?>
                        <tr>
                            <td><font size="2px"><?php echo $omzet->tgl_invoice; ?></td>
                            <td><font size="2px"><?php echo $omzet->nama_cab; ?></td>
                            <td><font size="2px"><?php echo $omzet->jenis; ?></td>                            
                            <td><font size="2px"><?php echo $omzet->no_invoice; ?></td>                        
                            <td><font size="2px"><?php echo $omzet->namalang; ?></td>
                            <td><font size="2px"><?php echo $omzet->sales_type; ?></td>
                            <td><font size="2px"><?php echo $omzet->nama_produk; ?></td>
                            <td><font size="2px"><?php echo $omzet->kemasan; ?></td>
                            <td><font size="2px"><?php echo $omzet->banyak; ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->hna * $omzet->banyak); ?></td>
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
                "ordering": false,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>