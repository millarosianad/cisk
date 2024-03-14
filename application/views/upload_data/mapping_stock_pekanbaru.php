<div class="col-sm-12">
    <!-- - periode : <b> <?php echo $periode_1 . ' - ' . $periode_2; ?> </b> <br>
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
                <a href="<?php echo base_url() . "status/upload_stock/"; ?>" class="btn btn-dark" role="button">
                    kembali</a>
                <a href="<?php echo base_url() . "status/export_mapping_stock_pekanbaru/$kode"; ?>" class="btn btn-primary" role="button">
                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Export (.csv)</a>
                <a href="<?php echo base_url() . "status/stock_rilis_pekanbaru/$kode/$tahun/$bulan"; ?>" class="btn btn-warning" role="button">
                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Rilis ke Database Utama</a>
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
                    <th>Kodeprod dp</th>
                    <th>Namaprod dp</th>
                    <th>Kodeprod MPM</th>
                    <th>Namaprod MPM</th>
                    <th>Stock Akhir</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($mapping_stock as $a) : ?>

                <?php
                    // print_r($mapping_stock);    
                ?>
                    <tr>
                        <td>
                            <font size="2px"><?php echo $a->kodebarang; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->namabarang; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->kodeprod_mpm; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->namaprod_mpm; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->sisa; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->created_date; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Kodeprod dp</th>
                    <th>Namaprod dp</th>
                    <th>Kodeprod MPM</th>
                    <th>Namaprod MPM</th>
                    <th>Stock Akhir</th>
                    <th>Created Date</th>
                </tr>
            </tfoot>
        </table>
    </div>


</div>
</div>