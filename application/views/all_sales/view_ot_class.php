<!doctype html>
<html>
    <head>        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>View OT Class</title>


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
            <h3>Outlet Transaksi per Class (ytd)</h3>
    </div>
    <br><br>
    <div class="col-xs-16">

        <a href="<?php echo base_url()."all_sales/export_ot_class/". $id; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        
    </div>
    <hr>
    <?php $no = 1; ?>
    <div class="row">
        <div class="col-xs-10">
            <table id="myTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>                
                        <th width="1"><font size="2px">No</font></th>   
                        <th width="1"><font size="2px">SubBranch</font></th>
                        <th width="1"><font size="2px"><?php echo $judul; ?></font></th>
                        <th width="1"><font size="2px">YTD</font></th>                                    
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($hasil)): ?>
                    <?php foreach($hasil as $x) : ?>
                    <tr>                            
                        <td><center><font size="2px"><?php echo $no++; ?></font></center></td>
                        <td><font size="2px">
                            <?php 
                                echo $x->nama_comp.' (';
                                echo $x->kode_comp.') ';
                            ?>                            
                        </td>
                        <td><font size="2px"><?php echo $x->jenis; ?></td>
                        <td><font size="2px"><?php echo $x->ytd; ?></td>
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
            "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
        });
    });
    </script>

    <!--jquery dan select2-->
    <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/select2/js/select2.min.js') ?>"></script>    
    </body>
</html>