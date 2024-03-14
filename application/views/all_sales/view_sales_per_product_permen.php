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
            <h3>Data Sales Per Product</h3><hr />
    </div>
    <div class="col-xs-16">

        <a href="<?php echo base_url()."all_sales/sales_product_permen"; ?>  " class="btn btn-success" role="button"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> kembali</a>
        
        <a href="<?php echo base_url()."all_sales/export/". $tahun."/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        
    </div>
    <hr>
    <?php $no = 1; ?>
    <div class="row">
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>                
                        <th width="1"><font size="2px">No</th>   
                        <th width="1"><font size="2px">SubBranch</th>
                        <th width="1"><font size="2px">T</th>
                        <th width="1"><font size="2px">Jan</th>
                        <th width="1"><font size="2px">T</th>
                        <th width="1"><font size="2px">Feb</th>
                        <th width="1"><font size="2px">T</th>
                        <th width="1"><font size="2px">Mar</th>
                        <th width="1">T</th>
                        <th width="1"><font size="2px">Apr</th>
                        <th width="1"><font size="2px">T</th>
                        <th width="1"><font size="2px">Mei</th>
                        <th width="1"><font size="2px">T</th>
                        <th width="1"><font size="2px">Jun</th>
                        <th width="1"><font size="2px">T</th>
                        <th width="1"><font size="2px">Jul</th>
                        <th width="1"><font size="2px">T</th>
                        <th width="1"><font size="2px">Agus</th>
                        <th width="1"><font size="2px">T</th>
                        <th width="1"><font size="2px">Sep</th>
                        <th width="1"><font size="2px">T</th>
                        <th width="1"><font size="2px">Okt</th> 
                        <th width="1"><font size="2px">T</th>
                        <th width="1"><font size="2px">Nov</th> 
                        <th width="1"><font size="2px">T</th>
                        <th width="1"><font size="2px">Des</th>                                      
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