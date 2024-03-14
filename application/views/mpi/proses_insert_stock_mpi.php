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

    <div class="col-xs-8">  
        <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
        <?php echo form_close();?>
        <a href="<?php echo base_url()."all_stock/insert_mpi_to_db/" ; ?>   " class="btn btn-success" role="button"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Insert ke Db MPM</a>
        <a href="<?php echo base_url()."all_stock/export_stock_mpi"; ?>   " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
    
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
                            <th width="1"><font size="2px">Stock Cut Off</font></th>
                            <th width="1"><font size="2px">Produk</th>
                            <th width="1"><font size="2px">HNA</th>
                            <th width="1"><font size="2px">Kemasan</th>
                            <th width="1"><font size="2px">ED</th>
                            <th width="1"><font size="2px">OnHand</th>
                            <th width="1"><font size="2px">GIT</th>
                            <th width="1"><font size="2px">BranchName</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($proses as $x) : ?>
                        <tr>
                            <td><font size="2px"><?php echo $x->cut_off; ?></td>
                            <td><font size="2px"><?php echo $x->produk; ?></td>
                            <td><font size="2px"><?php echo $x->hna; ?></td>                            
                            <td><font size="2px"><?php echo $x->kemasan; ?></td>                        
                            <td><font size="2px"><?php echo $x->ed; ?></td>
                            <td><font size="2px"><?php echo $x->onhand; ?></td>
                            <td><font size="2px"><?php echo $x->git; ?></td>
                            <td><font size="2px"><?php echo $x->branch_name; ?></td>
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