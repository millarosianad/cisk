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

    <?php 
        $no = 1;  
    ?>
    <h2>Detail DO</h2>

    </div>
    <hr>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">No</font></th>
                            <th><font size="2px">No DO/SJ</th>
                            <th><font size="2px">Tgl DO/SJ</th>
                            <th><font size="2px">No Sales</th>
                            <th><font size="2px">No Inv</th>
                            <th><font size="2px">Kode Produk</th>
                            <th><font size="2px">Nama Produk</th>
                            <th><font size="2px">Unit</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($getpo as $po) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>  
                            <td><font size="2px"><?php echo $po->no_sj; ?></td>
                            <td><font size="2px"><?php echo $po->tgl_sj; ?></td>
                            <td><font size="2px"><?php echo $po->no_sales; ?></td>
                            <td><font size="2px"><?php echo $po->no_inv; ?></td>
                            <td><font size="2px"><?php echo $po->productid; ?></td>
                            <td><font size="2px"><?php echo $po->namaprod; ?></td>
                            <td><font size="2px"><?php echo $po->qty3; ?></td>
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