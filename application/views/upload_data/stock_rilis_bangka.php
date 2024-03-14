<div class="col-sm-12">
        <!-- - periode : <b> <?php echo $periode_1.' - '.$periode_2; ?> </b> <br>
        - Kodeprod : <b> <?php echo $kodeprod; ?><hr>
        Informasi !! :<br> 
        - total faktur antara web dan bra mungkin akan beda karena di web faktur batal juga dihitung. Gap web vs bra kurang dari 0.09. Jika ditemukan lebih dari ini dapat menghubungi IT.<br>
        - Pemilihan periode dengan gap 2 tahun ke atas tidak bisa diproses karena akan mengambil resource yang besar. -->
    </div>
</div>
        
<div class="card-block">

    <div class="col-sm-10">
        <div class="form-group row">            
            <div class="col-sm-10">
                <a href="<?php echo base_url()."status/upload_stock/"; ?>"class="btn btn-dark" role="button">
                kembali</a>
                <a href="<?php echo base_url()."status/export_stock_bangka/$kode/$tahun/$bulan"; ?>"class="btn btn-primary" role="button">
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Export (.csv)</a> 
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
                    <th>kodeprod</th>
                    <th>namaprod</th>
                    <th>stock</th>
                    <th>tahun-bulan</th>
                    <th>nocab</th>                       
                </tr>
            </thead>
            <tbody>

            <?php foreach($mapping_stock as $a) : ?>
                <tr>
                    <td><font size="2px"><?php echo $a->KODEPROD; ?></td>                 
                    <td><font size="2px"><?php echo $a->NAMAPROD; ?></td>                 
                    <td><font size="2px"><?php echo $a->SALDOAWAL; ?></td>
                    <td><font size="2px"><?php echo $a->BULAN; ?></td>
                    <td><font size="2px"><?php echo $a->NOCAB; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>kodeprod</th>
                    <th>namaprod</th>
                    <th>stock</th>
                    <th>tahun-bulan</th>
                    <th>nocab</th>    
                </tr>
            </tfoot>
        </table>
    </div>


    </div>
</div>







