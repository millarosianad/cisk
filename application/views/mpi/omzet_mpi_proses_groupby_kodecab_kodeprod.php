<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View All Omzet</title>

        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

        <script src="<?php echo base_url() ?>assets/js/script.js"></script>
    
    </head>
    <body>

    <?php echo form_open($url);?>

    <div class="row">        
        <div class="col-xs-11">
            <?php echo br(3); ?>
            <h3><?php echo $page_title; ?></h3><hr />
        </div>
    </div>

    <div class="col-xs-2">
        Tahun
    </div>
    <div class="col-xs-3">
    
    <?php 
        $tahun = array(
            '2020'  => '2020',
            '2021'  => '2021'         
            );
    ?>
       <?php echo form_dropdown('tahun', $tahun,'','class="form-control"');?>
    </div>

    <div class="col-xs-11">
        &nbsp;
    </div>

    <div class="col-xs-2">
        Unit / Value
    </div>
    <div class="col-xs-3">
    
    <?php 
        $uv = array(
            '1'  => 'Unit',
            '2'  => 'Value',          
            );
    ?>
       <?php echo form_dropdown('uv', $uv,'','class="form-control"');?>
    </div>

    <div class="col-xs-11">
        &nbsp;
    </div>

    <div class="col-xs-2">
        Group By
    </div>
    <div class="col-xs-3">
    
    <?php 
        $group_by = array(
            '1'  => 'Kode Cabang',
            '2'  => 'Kode Produk',
            '3'  => 'Kode Cabang dan Kode Produk ',         
            );
    ?>
       <?php echo form_dropdown('group_by', $group_by,'','class="form-control"');?>
    </div>

    <div class="col-xs-11">
        &nbsp;
    </div>

    <div class="col-xs-2">
        Tipe Outlet
    </div>
    <div class="col-xs-8">

    <div class="row">
        <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="apotik" value="1"><span> Apotik (tanpa kimia farma)</span> &nbsp;</span>
            </label>
        </div>

        <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="kimia_farma" value="1"><span> Apotik Kimia Farma</span> &nbsp;</span>
            </label>
        </div> 

        <!-- <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="pbf" value="1"><span> PBF (tanpa kimia farma)</span> &nbsp;</span>
            </label>
        </div>
        
        <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="pbf_kimia_farma" value="1"><span> PBF Kimia Farma</span> &nbsp;</span>
            </label>
        </div>  -->

        

    </div>

    <div class="row">

        <!-- <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="minimarket" value="1"><span> MINIMARKET</span> &nbsp;</span>
            </label>
        </div>
       
        <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="pd" value="1"><span> P&D</span> &nbsp;</span>
            </label>
        </div> -->

        
    </div>

    <div class="row">

        <!-- <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="rs" value="1"><span> RS SWASTA</span> &nbsp;</span>
            </label>
        </div> 
       
        <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="supermarket" value="1"><span> SUPERMARKET</span> &nbsp;</span>
            </label>
        </div>

        <div class="col-xs-10">
            <label class="fancy-checkbox">
                <input type="checkbox" name="tokoobat" value="1"><span> TOKO OBAT</span> &nbsp;</span>
            </label>
        </div> -->

    </div>

    
    
    
    </div>

    <div class="col-xs-11">
        &nbsp;
    </div>



    <div class="col-xs-2">
        &nbsp;
    </div>

    <div class="col-xs-7">  
        <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
        <?php echo form_close();?>
        <a href="<?php echo base_url()."omzet/export_omzet_mpi_groupby_kodecab_kodeprod_temp"; ?>" class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
    </div>

</div>
    <hr />
   

    </div>
    <?php $no = 1; ?>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">Cabang</th>
                            <th width="1"><font size="2px">ProdukMPI</th>
                            <th width="1"><font size="2px">b1</th>
                            <th width="1"><font size="2px">t1</th>
                            <th width="1"><font size="2px">b2</th>
                            <th width="1"><font size="2px">t2</th>
                            <th width="1"><font size="2px">b3</th>
                            <th width="1"><font size="2px">t3</th>
                            <th width="1"><font size="2px">b4</th>
                            <th width="1"><font size="2px">t4</th>
                            <th width="1"><font size="2px">b5</th>
                            <th width="1"><font size="2px">t5</th>
                            <th width="1"><font size="2px">b6</th>
                            <th width="1"><font size="2px">t6</th>
                            <th width="1"><font size="2px">b7</th>
                            <th width="1"><font size="2px">t7</th>
                            <th width="1"><font size="2px">b8</th>
                            <th width="1"><font size="2px">t8</th>
                            <th width="1"><font size="2px">b9</th>
                            <th width="1"><font size="2px">t9</th>
                            <th width="1"><font size="2px">b10</th>
                            <th width="1"><font size="2px">t10</th>
                            <th width="1"><font size="2px">b11</th>
                            <th width="1"><font size="2px">t11</th>
                            <th width="1"><font size="2px">b12</th>
                            <th width="1"><font size="2px">t12</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($omzets as $omzet) : ?>
                        <tr>
                            <td><font size="2px"><?php echo $omzet->nama_cab; ?></td>
                            <td><font size="2px"><?php echo $omzet->namaprod_mpi; ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b1); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->t1); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b2); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->t2); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b3); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->t3); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b4); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->t4); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b5); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->t5); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b6); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->t6); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b7); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->t7); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b8); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->t8); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b9); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->t9); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b10); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->t10); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b11); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->t11); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b12); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->t12); ?></td>
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