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

    <!-- <div class="col-xs-3">
        Di Masukkan ke dalam tahun
    </div> -->

    <!-- <div class="col-xs-3">        
        <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
    </div>

    <div class="col-xs-11">
        &nbsp;
    </div> -->

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

    <div class="col-xs-3">  
        <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
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
                            <th width="1"><font size="2px">No</font></th>
                            <th>Tanggal Invoice</font></th>
                            <th>Proses Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($omzets as $omzet) : ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $omzet->tgl_invoice; ?></td>
                            <td><?php echo $omzet->username; ?></td>         
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