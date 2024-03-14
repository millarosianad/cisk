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
                <a href="<?php echo base_url() . "status/export_mapping_stock_batam/$kode"; ?>" class="btn btn-primary" role="button">
                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Export (.csv)</a>
                <a href="<?php echo base_url() . "status/stock_rilis_batam/$kode/$tahun/$bulan"; ?>" class="btn btn-warning" role="button">
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
                    <th colspan="3">
                        <center>Excel DP</center>
                    </th>
                    <th colspan="4">
                        <center>Mapping MPM
                    </th>
                </tr>
                <tr>
                    <th>no</th>
                    <th>Kodeprod</th>
                    <th>Namabarang</th>
                    <th>sisa</th>
                    <th>Isi Satuan</th>
                    <th>Stock akhir</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($mapping_stock as $a) : ?>
                    <tr>
                        <td>
                            <font size="2px"><?php echo $a->no; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->kodeprod; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->namaprod; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->sisa; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->isi_satuan; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->stock; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->created_date; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>no</th>
                    <th>Kodeprod</th>
                    <th>Namabarang</th>
                    <th>sisa</th>
                    <th>Isi Satuan</th>
                    <th>Stock akhir</th>
                    <th>Created Date</th>
                </tr>
            </tfoot>
        </table>
    </div>


</div>
</div>