<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View All Omzet</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>"> 
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
        <?php 
            $interval=date('Y')-2018;
            $year=array();
            $year['2020']='2020';
            for($i=1;$i<=$interval;$i++)
            {
                $year[''.$i+2018]=''.$i+2018;
            }
        ?>
    
    </head>
    <body>
    <?php echo form_open($url);?>

    </div>

    <div class="row">
        <div class="col-xs-11">
            <div class="col-xs-9">
                <h3><br><br><?php echo $page_title; $no = 1; ?></h3><hr />
            </div>
        </div>
    </div><br>

    <div class="row">
        <div class="col-xs-11">
            <div class="col-xs-9">
                <a href="javascript:window.history.go(-1)   " class="btn btn-default" role="button"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Kembali</a>
                <a href="<?php echo base_url()."monitor_claim/export_claim_detail_produk/" ?>   " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export</a>  
            </div>
        </div>
    </div><br>

    <hr>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px"><center> No</font></th>
                            <th><font size="2px"><center> NoClaim</th>
                            <th><font size="2px"><center> ProductId</th>
                            <th><font size="2px"><center> NamaProd</th>
                            <th><font size="2px"><center> MemoId</th>
                            <th><font size="2px"><center> QtyBonus</th>
                            <th><font size="2px"><center> NilaiBonus</th>
                            <th><font size="2px"><center> Periode1</th>
                            <th><font size="2px"><center> Periode2</th>
                            <th><font size="2px"><center> CreatedDate</th>
                            <th><font size="2px"><center> UpdateDate</th>
                        </tr>
                    </thead>
                    <tbody>
                                        
                        <?php foreach($query as $x) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                            <td><font size="2px"><?php echo $x->no_claim; ?></td>
                            <td><font size="2px"><?php echo $x->productid; ?></td>
                            <td><font size="2px"><?php echo $x->namaprod; ?></td>
                            <td><font size="2px"><?php echo $x->memoid; ?></td>
                            <td><font size="2px"><?php echo $x->qty_bonus; ?></td>
                            <td><font size="2px"><?php echo $x->nilai_bonus; ?></td>
                            <td><font size="2px"><?php echo $x->periode_1; ?></td>
                            <td><font size="2px"><?php echo $x->periode_2; ?></td>
                            <td><font size="2px"><?php echo $x->create_date; ?></td>
                            <td><font size="2px"><?php echo $x->update_date; ?></td>
                         
                        </tr>
                    <?php endforeach; ?>
                    
                    </tbody>
                    </table>
                    </div>
                    <div class="col-xs-11">&nbsp; </div>
        </div>
    </div>
    
    <script src="<?php echo base_url() ?>assets/js/script.js"></script>                       
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