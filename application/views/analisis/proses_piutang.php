<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View Surat Jalan</title>        
        <script src="<?php echo base_url() ?>assets/js/script.js"></script>
         <!-- Load Datatables dan Bootstrap dari CDN -->
         <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

      
    </head>
<body>

    <?php echo form_open($url);?>   
    <div class="row">        
        <div class="col-xs-12">
            <?php echo br(1); ?>
            <h3><?php echo $page_title; ?><hr />
        </div>
    </div>

    <div class = "row">    
        <div class="col-xs-2">
            Periode            
        </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" id="datepicker3" name="periode" placeholder="" autocomplete="off"> 
        </div>
        

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">        
            
        </div>
        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <a href="<?php echo base_url()."analisis/export/" ?>   " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true" target="blank"></span> Export Report</a>
            <a href="<?php echo base_url()."analisis/export_detail/" ?>   " class="btn btn-success" role="button" target="blank"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Export Detail</a>
            <?php echo form_close();?>

        </div>
    </div>
</div>
    <br>

    <?php $no = 1; ?>

    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-1"></div>
            <div class="col-xs-12">
                <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">No</font></th>
                            <th><font size="2px">Group Customer</th>
                            <th><font size="2px">belum jatuh tempo</th>
                            <th><font size="2px">1-7</th>
                            <th><font size="2px">8-15</th>
                            <th><font size="2px">16-30</th>
                            <th><font size="2px">31-45</th>
                            <th><font size="2px">46-60</th>
                            <th><font size="2px">>60</th>
                            <th><font size="2px">total</th>
                        </tr>
                    </thead>
                    <tbody>                    
                        <?php foreach($hasil as $x) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                            <td><font size="2px"><?php echo $x->group_descr; ?></td>
                            <td><font size="2px"><?php echo number_format($x->a); ?></td>
                            <td><font size="2px"><?php echo number_format($x->b); ?></td>
                            <td><font size="2px"><?php echo number_format($x->c); ?></td>
                            <td><font size="2px"><?php echo number_format($x->d); ?></td>
                            <td><font size="2px"><?php echo number_format($x->e); ?></td>
                            <td><font size="2px"><?php echo number_format($x->f); ?></td>
                            <td><font size="2px"><?php echo number_format($x->g); ?></td>
                            <td><font size="2px"><?php echo number_format($x->total); ?></td>
                        </tr>
                        <?php endforeach; ?>                    
                    </tbody>
                </table>
            </div>                    
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
