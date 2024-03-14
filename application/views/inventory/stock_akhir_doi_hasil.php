<div class="col-sm-12">
        - Kodeprod : <b> <?php echo $kodeprod; ?> <br>
        - Bulan : <b> <?php echo $bulan; ?> <br>
        - Khusus Jombang dan Mojokerto sudah di masukkan ke dalam Sales Sidoarjo <br>
    </div>

        
<div class="card-block">

    <div class="col-sm-10">
        <div class="form-group row">   
            <div class="col-sm-10">
                <a href="<?php echo base_url()."inventory/stock_akhir_doi/"; ?>"class="btn btn-dark" role="button">
                kembali</a>
                <a href="<?php echo base_url()."inventory/export_stock_akhir_doi/"; ?>"class="btn btn-warning" role="button">
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> export (csv)</a>    
            </div>    
            <div class="col-sm-5"></div>
        </div>
    </div>
</div>
    <hr>
    <div class="card-block">

    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th colspan="2"><center>DP</center></th>
                </tr>
                <tr>
                    <th>Branch</th>
                    <th>Sub Branch</th>
                    <th>Kodeprod</th> 
                    <th>Namaprod</th> 
                    <th>Avg</th> 
                    <th>Stock</th> 
                    <th>Doi</th>
                </tr>
            </thead>
            <tbody>

            <?php 
            // var_dump($proses);
            foreach($proses as $a) : ?>
                <tr>                 
                    <td><font size="2px"><?php echo $a->branch_name; ?></td>
                    <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                    <td><font size="2px"><?php echo $a->kodeprod; ?></td>
                    <td><font size="2px"><?php echo $a->namaprod; ?></td>
                    <td><font size="2px"><?php echo $a->avg_unit; ?></td>
                    <td><font size="2px"><?php echo $a->stock_akhir; ?></td>
                    <td><font size="2px"><?php echo $a->doi_unit; ?></td>
                </tr>
            <?php 
            endforeach; 
            ?>
            
            </tbody>
            <tfoot>
                <tr>
                    <th>Branch</th>
                    <th>Sub Branch</th>
                    <th>Kodeprod</th> 
                    <th>Namaprod</th> 
                    <th>Avg</th> 
                    <th>Stock</th> 
                    <th>Doi</th>
                </tr>
            </tfoot>
        </table>
    </div>
    </div>
</div>







