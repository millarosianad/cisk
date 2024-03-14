<div class="col-sm-10">
    - Tahun : <?php echo $tahun; ?> <br>
    - Kodeprod : <b> <?php echo $kodeprod; ?> <br>
    - Informasi : <b> <?php echo 'Anda dapat mendownload master target di bawah ini'; ?> <br>
    <br>
    <a href="<?php echo base_url()."sales_omzet/actual_vs_target"; ?>"class="btn btn-dark" role="button">kembali</a>
    <a href="<?php echo base_url()."sales_omzet/export_actual_vs_target/2"; ?>" class="btn btn-warning" role="button">export (.csv)</a>
    <a href="<?php echo base_url()."sales_omzet/download_target/$bulan/$tahun"; ?>" class="btn btn-danger" role="button">export master target (.csv)</a>
</div>
    <br>
    </div>

    <div class="card table-card">
        <div class="card-header">
        <div class="card-block">
        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>
                        <tr>
                            <th colspan="2"><center>DP</center></th>
                            <th colspan="3"><center>Produk</center></th>
                            <th colspan="3"><center>Unit</th>
                            <th colspan="3"><center>Value</th>
                        </tr>
                        <tr>                
                            <th><font size="2px">Branch</th>
                            <th><font size="2px">SubBranch</th>
                            <th><font size="2px">Kodeprod</th>
                            <th><font size="2px">Namaprod</th>
                            <th><font size="2px">Bulan</th>
                            <th><font size="2px">Actual</th>
                            <th><font size="2px">Target</th>
                            <th><font size="2px">Ach</th>
                            <th><font size="2px">Actual</th>
                            <th><font size="2px">Target</th>
                            <th><font size="2px">Ach</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($proses as $a) : ?>
                        <tr>                      
                            <td><font size="2px"><?php echo $a->branch_name; ?></td>
                            <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                            <td><font size="2px"><?php echo $a->kodeprod; ?></td>
                            <td><font size="2px"><?php echo $a->namaprod; ?></td>
                            <td><font size="2px"><?php echo $a->bulan; ?></td>
                            <td><font size="2px"><?php echo number_format($a->actual_unit); ?></td>
                            <td><font size="2px"><?php echo number_format($a->target_unit); ?></td>
                            <td><font size="2px"><?php echo number_format($a->acv_unit * 100).' %'; ?></td>
                            <td><font size="2px"><?php echo number_format($a->actual_value); ?></td>
                            <td><font size="2px"><?php echo number_format($a->target_value); ?></td>
                            <td><font size="2px"><?php echo number_format($a->acv_value * 100).' %'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    
                    </tbody>
                    <tfoot>
                            <tr>                
                                <th><font size="2px">Branch</th>
                                <th><font size="2px">SubBranch</th>
                                <th><font size="2px">Kodeprod</th>
                            <th><font size="2px">Namaprod</th>
                                <th><font size="2px">Bulan</th>
                                <th><font size="2px">Actual</th>
                                <th><font size="2px">Target</th>
                                <th><font size="2px">Ach</th>
                                <th><font size="2px">Actual</th>
                                <th><font size="2px">Target</th>
                                <th><font size="2px">Ach</th>    
                            </tr>
                    </tfoot>
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