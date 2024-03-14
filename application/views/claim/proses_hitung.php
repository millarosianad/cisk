<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View All Omzet</title>

        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
    
    </head>
    <body>

    
    <br>
    </div>

    <br>
    

    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2><?php echo $page_title; ?></h2>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-12">
                <a href="<?php echo base_url()."monitor_claim/export_branch_report/" ?>" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> report by Branch</a>
                <a href="<?php echo base_url()."monitor_claim/export_summary_report/" ?>" class="btn btn-info" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> report by sub Branch</a> 
                <a href="<?php echo base_url()."monitor_claim/export_detail_report/" ?>" class="btn btn-success" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> report by outlet</a>
                <a href="<?php echo base_url()."monitor_claim/export_summary_faktur/" ?>" class="btn btn-default" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> faktur summary</a> 
                <a href="<?php echo base_url()."monitor_claim/export_detail_faktur/" ?>" class="btn btn-default" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> faktur detail</a>
            </div>
        </div>
        <hr>
        <br>
        <div class="row">
            <div class="col-xs-12">
                
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                <thead>
                    <tr>   
                        <th><font size="2px">Program</th>
                        <th><font size="2px">Periode</th>             
                        <th><font size="2px">Branch</th>
                        <th><font size="2px">SubBranch</th>
                        <th><font size="2px">TotalBonus</th>
                    </tr>
                </thead>
                <tbody>                
                    <?php foreach($proses as $a) : ?>
                    <tr>                     
                        <td><font size="2px"><?php echo $a->nama_program; ?></td>
                        <td><font size="2px"><?php echo $a->from.'_'.$a->to; ?></td>
                        <td><font size="2px"><?php echo $a->branch_name; ?></td>
                        <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                        <td><font size="2px"><?php echo $a->total_bonus; ?></td>
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