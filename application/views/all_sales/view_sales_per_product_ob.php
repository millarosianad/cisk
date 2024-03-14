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
            <h3>Data Sales Per Product & Outlet Binaan</h3><hr />
    </div>
    <div class="col-xs-16">

        <a href="<?php echo base_url()."all_sales/sales_product_ob"; ?>  " class="btn btn-success" role="button"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> kembali</a>
        
        <a href="<?php echo base_url()."all_sales/export_ob/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        
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
                        <th width="1"><font size="2px">Tahun</font></th>
                        <th width="1"><font size="2px">Bulan</font></th>
                        <th width="1"><font size="2px">OT</font></th>
                        <th width="1"><font size="2px">UNIT</font></th>
                        <th width="1"><font size="2px">VALUE</font></th>
                        <th width="1"><font size="2px">OT OB</font></th>
                        <th width="1"><font size="2px">UNIT OB</font></th>
                        <th width="1"><font size="2px">VALUE OB</font></th>                                  
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($proses)): ?>
                    <?php foreach($proses as $x) : ?>
                    <tr>                            
                        <td><center><font size="2px"><?php echo $no++; ?></font></center></td>
                        <td><?php echo $x->nama_comp; ?></td>
                        <td><?php echo $x->tahun; ?></td>
                        <td><?php echo $x->bulan; ?></td>
                        <td><?php echo $x->ot; ?></td>
                        <td><?php echo number_format($x->unit); ?></td>
                        <td><?php echo number_format($x->value); ?></td>
                        <td><?php echo $x->ot_ob; ?></td>
                        <td><?php echo number_format($x->unit_ob); ?></td>
                        <td><?php echo number_format($x->value_ob); ?></td>
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