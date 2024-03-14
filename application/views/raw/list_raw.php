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
    <?php //echo br(3); 
        $no = 1;
    ?>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px"><center>No</font></th>
                            <th width="10%"><font size="2px"><center>Principal</th>
                            <th><font size="2px"><center>Keterangan</th>
                            <th><font size="2px"><center>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($query as $x) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                            <td>
                                <center>
                                <font size="2px">
                                    <?php
                                        if ($x->supp == '001') {
                                            $principal = "deltomed";
                                        }elseif ($x->supp == '002') {
                                            $principal = "marguna";
                                        }elseif ($x->supp == '005') {
                                            $principal = "ultra sakti";
                                        }elseif ($x->supp == '012') {
                                            $principal = "intrafood";
                                        }else{
                                            $principal = "all";
                                        }
                                        echo $principal;
                                    ?>
                                </font>
                                
                                </center>
                            </td>
                            <td><font size="2px"><?php echo $x->keterangan; ?></font></td>
                            <td><center>
                                <?php
                                    if ($x->link <> null) {
                                        echo anchor(base_url().'assets/file/raw_data/'.$x->link, 'download',"class='btn btn-primary btn-sm'");
                                    }else{
                                        echo "belum ada";
                                    }                                    
                                ?>
                                </center>
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
                "ordering": false,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>