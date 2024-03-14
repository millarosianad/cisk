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

    <div class="col-xs-3">  
        <?php 
            $id = $this->session->userdata('id');
            if ($id == '297' || $id == '547') {
                echo form_submit('submit','Update Stock Terbaru dari Web MPI','class="btn btn-primary"');
            }else{
                echo "";
            }
        ?>
        <?php echo form_close();?>
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
                            <th width="1">Tanggal Cut Off</font></th>
                            <th width="1">Total Stock On Hand</font></th>
                            <th width="1">Total GIT</font></th>
                            <th width="1">Total Stock (OnHand + GIT)</font></th>
                            <th width="1">Total Stock in Value (OnHand + GIT)</font></th>
                            <th width="1">Last Update</th>
                            <th width="1">Proses Oleh</th>
                            <th width="1">Export to CSV</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($stock as $x) : ?>
                        <tr>
                            <td><?php echo $x->cut_off; ?></td>
                            <td><?php echo number_format($x->onhand); ?></td>
                            <td><?php echo number_format($x->git); ?></td>
                            <td><?php echo number_format($x->stock); ?></td>
                            <td><?php echo "Rp ".number_format($x->stock_value); ?></td>
                            <td><?php echo $x->last_update; ?></td>
                            <td><?php echo $x->username; ?></td>
                            <td><?php
                                echo anchor('all_stock/export_stock_mpi/' . $x->tgl, ' ',array('class' => 'glyphicon glyphicon-floppy-disk', 'target' => 'new'));
                                ?>
                            </td>        
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
                "order": [[ 0, "desc" ]],
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>