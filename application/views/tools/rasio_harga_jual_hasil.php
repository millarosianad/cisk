<div class="col-sm-10">
    - Periode : <?php echo $from. " - ".$to; ?> <br>
    - Kodeprod : <?php echo $kodeprod; ?><hr>
    <br>
    <br>
    <a href="<?php echo base_url()."tools/rasio_harga_jual"; ?>"class="btn btn-dark" role="button">kembali</a>
    <a href="<?php echo base_url()."tools/export_rasio_harga_jual"; ?>" class="btn btn-warning" role="button">export (.csv)</a>
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
                            <th width="1"><font size="2px">Branch</th>
                            <th><font size="2px">Sub Branch</th>
                            <th><font size="2px">faktur</th>
                            <th><font size="2px">tgl_faktur</th>
                            <th><font size="2px">kodeprod</th>
                            <th><font size="2px">banyak</th>
                            <th><font size="2px">harga</th>
                            <th><font size="2px">harga_2</th>
                            <th><font size="2px">h_jual_dp_retail</th>
                            <th><font size="2px">tgl_naik_harga</th>
                            <th><font size="2px">selisih_harga</th>
                            <th><font size="2px">selisih_harga_2</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($proses as $a) : ?>
                        <tr>                      
                            <td><font size="2px"><?php echo $a->branch_name; ?></td>
                            <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                            <td><font size="2px"><?php echo $a->faktur; ?></td>
                            <td><font size="2px"><?php echo $a->tgl_faktur; ?></td>
                            <td><font size="2px"><?php echo $a->kodeprod; ?></td>
                            <td><font size="2px"><?php echo $a->banyak; ?></td>
                            <td><font size="2px"><?php echo $a->harga; ?></td>
                            <td><font size="2px"><?php echo $a->harga_2; ?></td>
                            <td><font size="2px"><?php echo $a->h_jual_dp_retail; ?></td>
                            <td><font size="2px"><?php echo $a->tgl_naik_harga; ?></td>
                            <td><font size="2px"><?php echo $a->selisih_harga; ?></td>
                            <td><font size="2px"><?php echo $a->selisih_harga_2; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    
                    </tbody>
                    </table>
                    </div>
                    <div class="col-xs-11">&nbsp; </div>
        </div>
    </div>
    

     <!-- <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                "ordering": false,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
    </script> -->
    </body>
</html>