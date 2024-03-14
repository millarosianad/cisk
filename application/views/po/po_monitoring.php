<!doctype html>
<html>
    <head>        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>List Order</title>
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
        <script type="text/javascript">       
        $(document).ready(function() { 
        $("#supp").click(function(){
            $.ajax({
            url:"<?php echo base_url(); ?>omzet/buildgroup",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
        });            
        </script>
    </head>
    <body>
    <br><br><br>
    </div>

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-11">
            <h3><?php echo br(1).' '.$page_title; ?></h3><hr />
        </div>
    </div>

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-11">

            <a href="<?php echo base_url()."all_po/insert_do" ?> " class="btn btn-success" role="button" target="blank">Insert DO Deltomed</a>
            <a href="https://deltomed-prod.operations.dynamics.com/" class="btn btn-info" role="button" target="blank">Go to Web Deltomed</a>
            <a href="<?php echo base_url()."all_po/insert_do_us" ?> " class="btn btn-primary" role="button" target="blank">Insert DO US</a>
            <a href="http://backup.muliaputramandiri.com:81/cisk/assets/us/do/archive" class="btn btn-info" role="button" target="blank">Download DO US</a>
            <a href="<?php echo base_url()."inventory/po_outstanding" ?> " class="btn btn-warning" role="button" target="blank">PO Outstanding</a>

        </div>
    </div><br>

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-1"></div>
        <div class="col-xs-5">
            
            
        </div>
        
    </div>
    <div class="col-xs-4"></div>

</div>
    <hr>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-5"><center><h3>History DO Deltomed</h3></center><br>
                <table id="myTable" class="table table-striped table-bordered table-hover">     
                <thead>
                <tr>                
                    <th width='10%'><center>Tgl DO</th>
                    <th width='10%'><center>Total Unit DO</th>
                    <th width='10%'><center>UpdatedBy</th>
                    <th width='10%'><center>LastUpdate</th>   
                    <th width='10%'><center>Export</th>                 
                </tr>
                </thead>
                <tbody>                    
                    <?php foreach($query as $x) : ?>
                    <tr>          
                        <td><font size="2"><?php echo $x->tgldo; ?></td>
                        <td><font size="2"><?php echo number_format($x->unit_do); ?></td>
                        <td><font size="2"><?php echo $x->username; ?></td>
                        <td><font size="2"><?php echo $x->lastupdate; ?></td>
                        <td><center>
                            <?php echo anchor('all_po/export_do_deltomed/' . $x->tgldo, ' ',array('class' => 'glyphicon glyphicon-floppy-disk', 'target' => 'new'));
                            ?></center>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>

            <div class="col-xs-5"><center><h3>History DO Us</h3></center><br>
                <table id="myTableUs" class="table table-striped table-bordered table-hover">     
                <thead>
                <tr>                
                    <th width='10%'><center>Tgl DO</th>
                    <th width='10%'><center>Total Unit DO</th>
                    <th width='10%'><center>UpdatedBy</th>
                    <th width='10%'><center>LastUpdate</th>   
                    <th width='10%'><center>Export</th>                  
                </tr>
                </thead>
                <tbody>                    
                    <?php foreach($queryUs as $x) : ?>
                    <tr>          
                        <td><font size="2"><?php echo $x->tgldo; ?></td>
                        <td><font size="2"><?php echo number_format($x->unit_do); ?></td>
                        <td><font size="2"><?php echo $x->username; ?></td>
                        <td><font size="2"><?php echo $x->lastupdate; ?></td>
                        <td><center>
                            <?php echo anchor('all_po/export_do_us/' . $x->tgldo, ' ',array('class' => 'glyphicon glyphicon-floppy-disk', 'target' => 'new'));
                            ?></center>
                        </td>
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
            "ordering": true,
            "order": [[ 0, "desc" ]],
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
        });
    });
    </script>
    <script>
    $(document).ready(function(){
        $('#myTableUs').DataTable( {
            "ordering": true,
            "order": [[ 0, "desc" ]],
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
        });
    });
    </script>
    </body>
</html>