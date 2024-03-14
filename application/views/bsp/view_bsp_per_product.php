<!doctype html>
<html>
    <head>        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>View Sales Per Product</title>

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
            <h3>Data Sales Per Product</h3><hr />
    </div>
    <div class="col-xs-16">

        <a href="<?php echo base_url()."all_bsp/bsp_per_product"; ?>  " class="btn btn-success" role="button"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> kembali</a>
        
        <a href="<?php echo base_url()."all_bsp/export_per_product/". $tahun."/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        
    </div>
    <hr>
    <?php $no = 1; ?>
    <div class="row">
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>                
                        <th>No</th>   
                        <th>SubBranch</th>
                        <th>Jan</th>
                        <th>Feb</th>
                        <th>Mar</th>
                        <th>Apr</th>
                        <th>Mei</th>
                        <th>Jun</th>
                        <th>Jul</th>
                        <th>Agus</th>
                        <th>Sep</th>
                        <th>Okt</th>
                        <th>Nov</th>
                        <th>Des</th>                                      
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($products)): ?>
                    <?php foreach($products as $product) : ?>
                    <tr>                            
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $product->nama_comp; ?></td>
                        <td><?php echo number_format($product->b1); ?></td>
                        <td><?php echo number_format($product->b2); ?></td>
                        <td><?php echo number_format($product->b3); ?></td>
                        <td><?php echo number_format($product->b4); ?></td>
                        <td><?php echo number_format($product->b5); ?></td>
                        <td><?php echo number_format($product->b6); ?></td>
                        <td><?php echo number_format($product->b7); ?></td>
                        <td><?php echo number_format($product->b8); ?></td>
                        <td><?php echo number_format($product->b9); ?></td>
                        <td><?php echo number_format($product->b10); ?></td>
                        <td><?php echo number_format($product->b11); ?></td>
                        <td><?php echo number_format($product->b12); ?></td>
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
            
        });
    });
    </script>
    <!--jquery dan select2-->
    <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/select2/js/select2.min.js') ?>"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2({
                placeholder: "Please Select"
            });
        });
    </script>
    </body>
</html>