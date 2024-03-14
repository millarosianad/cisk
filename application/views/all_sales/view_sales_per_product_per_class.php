<!doctype html>
<html>
    <head>        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>View Sales Per Product</title>
        <!-- Load Jquery, Bootstrap, dan DataTables dari CDN -->
        <!-- buka url ini: http://pastebin.com/index/WeaY5Fra -->
        <!-- load Jquery dari CDN -->
        
        <!--
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        -->

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
            <h3>Data Sales Per Product (Class)</h3><hr />
    </div>
    <div class="col-xs-16">

        <a href="<?php echo base_url()."all_sales/sales_product_per_class"; ?>  " class="btn btn-success" role="button"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> kembali</a>
        
        <a href="<?php echo base_url()."all_sales/export_per_class/". $tahun."/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        
    </div>
    <hr>
    <?php $no = 1; ?>
    <div class="row">
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>                
                        <th width="1"><font size="2px">No</font></th>   
                        <th width="1"><font size="2px">SubBranch</font></th>
                        <th width="4"><font size="2px">Class</font></th>
                        <th width="1"><font size="2px">T</font></th>
                        <th width="1"><font size="2px">Jan</font></th>
                        <th width="1"><font size="2px">T</font></th>
                        <th width="1"><font size="2px">Feb</font></th>
                        <th width="1"><font size="2px">T</font></th>
                        <th width="1"><font size="2px">Mar</font></th>
                        <th width="1"><font size="2px">T</font></th>
                        <th width="1"><font size="2px">Apr</font></th>
                        <th width="1"><font size="2px">T</font></th>
                        <th width="1"><font size="2px">Mei</font></th>
                        <th width="1"><font size="2px">T</font></th>
                        <th width="1"><font size="2px">Jun</font></th>
                        <th width="1"><font size="2px">T</font></th>
                        <th width="1"><font size="2px">Jul</font></th>
                        <th width="1"><font size="2px">T</font></th>
                        <th width="1"><font size="2px">Agus</font></th>
                        <th width="1"><font size="2px">T</font></th>
                        <th width="1"><font size="2px">Sep</font></th>
                        <th width="1"><font size="2px">T</font></th>
                        <th width="1"><font size="2px">Okt</font></th> 
                        <th width="1"><font size="2px">T</font></th>
                        <th width="1"><font size="2px">Nov</font></th> 
                        <th width="1"><font size="2px">T</font></th>
                        <th width="1"><font size="2px">Des</font></th>                                      
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($products)): ?>
                    <?php foreach($products as $product) : ?>
                    <tr>                            
                        <td><center><font size="2px"><?php echo $no++; ?></font></center></td>
                        <td><font size="2px">
                            <?php 
                                echo $product->nama_comp.'(';
                                echo $product->kode_comp.')';
                            ?>                            
                        </td>
                        <td><font size="2px"><?php echo $product->jenis; ?></td>
                        <td><font size="2px"><?php echo $product->t1; ?></td>
                        <td><font size="2px"><?php echo number_format($product->b1); ?></td>
                        <td><font size="2px"><?php echo $product->t2; ?></td>
                        <td><font size="2px"><?php echo number_format($product->b2); ?></td>
                        <td><font size="2px"><?php echo $product->t3; ?></td>
                        <td><font size="2px"><?php echo number_format($product->b3); ?></td>
                        <td><font size="2px"><?php echo $product->t4; ?></td>
                        <td><font size="2px"><?php echo number_format($product->b4); ?></td>
                        <td><font size="2px"><?php echo $product->t5; ?></td>
                        <td><font size="2px"><?php echo number_format($product->b5); ?></td>
                        <td><font size="2px"><?php echo $product->t6; ?></td>
                        <td><font size="2px"><?php echo number_format($product->b6); ?></td>
                        <td><font size="2px"><?php echo $product->t7; ?></td>
                        <td><font size="2px"><?php echo number_format($product->b7); ?></td>
                        <td><font size="2px"><?php echo $product->t8; ?></td>
                        <td><font size="2px"><?php echo number_format($product->b8); ?></td>
                        <td><font size="2px"><?php echo $product->t9; ?></td>
                        <td><font size="2px"><?php echo number_format($product->b9); ?></td>
                        <td><font size="2px"><?php echo $product->t10; ?></td>
                        <td><font size="2px"><?php echo number_format($product->b10); ?></td>
                        <td><font size="2px"><?php echo $product->t11; ?></td>
                        <td><font size="2px"><?php echo number_format($product->b11); ?></td>
                        <td><font size="2px"><?php echo $product->t12; ?></td>
                        <td><font size="2px"><?php echo number_format($product->b12); ?></td>
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