<div class="col-sm-12">
        - periode : <b> <?php echo $periode_1.' - '.$periode_2; ?> </b> <br>
        - kodeproduk : <b> <?php echo $kodeprod; ?><hr>
        Informasi !! :<br> 
        - Pemilihan periode dengan gap 2 tahun ke atas tidak bisa diproses karena akan mengambil resource yang besar.
    </div>

        
<div class="card-block">

    <div class="col-sm-10">
        <div class="form-group row">            
            <div class="col-sm-10">
                <a href="<?php echo base_url()."outlet_transaksi/pengambilan/"; ?>"class="btn btn-dark" role="button">
                kembali</a>
                <a href="<?php echo base_url()."outlet_transaksi/export_pengambilan/1"; ?>"class="btn btn-warning" role="button">
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
                    <th colspan="4"><center>Pengambilan</th>
                </tr>
                <tr>
                    <th>Branch</th>
                    <th>Sub Branch</th>
                    <th>1X</th> 
                    <th>2X</th> 
                    <th>3X</th> 
                    <th>>3X</th>                     
                </tr>
            </thead>
            <tbody>

            <?php 
            // var_dump($proses);
            foreach($proses as $a) : ?>
                <tr>                 
                    <td><font size="2px"><?php echo $a->branch_name; ?></td>
                    <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                    <td><font size="2px"><?php echo $a->satu; ?></td>
                    <td><font size="2px"><?php echo $a->dua; ?></td>
                    <td><font size="2px"><?php echo $a->tiga; ?></td>
                    <td><font size="2px"><?php echo $a->lebih_tiga; ?></td>
                </tr>
            <?php endforeach; ?>
            
            </tbody>
            <tfoot>
                <tr>
                    <th>Branch</th>
                    <th>Sub Branch</th>
                    <th>1X</th> 
                    <th>2X</th> 
                    <th>3X</th> 
                    <th>>3X</th> 
                </tr>
            </tfoot>
        </table>
    </div>
    </div>
</div>







