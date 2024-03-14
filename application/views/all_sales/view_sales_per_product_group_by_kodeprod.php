<!DOCTYPE html>
<html>
<head>
    <title>Sales Per Produk</title>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>">

</head>
<body>

    <?php $no = 1; ?>
    <div class="col-xs-16">            
            <h3>Data Sales Per Product</h3><hr />
    </div>
    <div class="col-xs-16">

        <a href="<?php echo base_url()."all_sales/"; ?>  " class="btn btn-success" role="button"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> kembali</a>
        
        <a href="<?php echo base_url()."all_sales/export_group_by_kodeprod/". $tahun."/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        
    </div>

</div>
<?php $no = 1; ?>
<hr>
<div class="row">        
        <div class="col-xs-12">
        <div class="col-xs-12">
            <table class="table table table-striped table-bordered table-hover table-sales">
                <thead>
                    <tr>                
                        <th width="1"><font size="2px">No</font></th>   
                        <th width="1"><font size="2px">SubBranch</font></th>
                        <th width="1"><font size="2px">Produk</font></th>
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
                        <td><font size="2px"><?php echo $product->namaprod; ?></td>
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
                    <div class="col-xs-11">&nbsp; </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url('assets/jquery.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.min.js') ?>"></script>

    <script type="text/javascript">
    $(".table-sales").DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?php echo base_url('all_sales/get_sales_product_group_by_kodeprod') ?>",
            type:'POST',
        }
    });

    </script>
    </body>
</html>