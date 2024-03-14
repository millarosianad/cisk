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
                <a href="<?php echo base_url() . "status/upload_stock_padang/"; ?>" class="btn btn-dark" role="button">
                    kembali</a>
                <a href="<?php echo base_url() . "status/export_mapping_stock_padang/$kode"; ?>" class="btn btn-primary" role="button">
                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Export (.csv)</a>
                <a href="<?php echo base_url() . "status/stock_rilis_padang/$kode/$tahun/$bulan"; ?>" class="btn btn-warning" role="button">
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
                    <th colspan="6">
                        <center>Excel DP</center>
                    </th>
                    <th colspan="6">
                        <center>Mapping MPM
                    </th>
                </tr>
                <tr>
                    <th>Kode</th>
                    <th>Kodeprod_dp</th>
                    <th>Namaprod dp</th>
                    <th>G Ctn</th>
                    <th>G Unt</th>
                    <th>Rp Gdg</th>
                    <th>Konversi Ctn</th>
                    <th>Konversi Unt</th>
                    <th>(Stock Akhir)</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($mapping_stock as $a) : ?>
                    <tr>
                        <td>
                            <font size="2px"><?php echo $a->kode; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->kodeprod_original; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->nama; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->g_ctn; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->g_unt; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->rp_gdg; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->konversi_ctn_to_pcs; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->konversi_box_to_pcs; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->total_pcs; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->created_date; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Kode</th>
                    <th>Kodeprod_dp</th>
                    <th>Namaprod dp</th>
                    <th>G Ctn</th>
                    <th>G Unt</th>
                    <th>Rp Gdg</th>
                    <th>Konversi Ctn</th>
                    <th>Konversi Unt</th>
                    <th>(Stock Akhir)</th>
                    <th>Created Date</th>
                </tr>
            </tfoot>
        </table>
    </div>


</div>
</div>