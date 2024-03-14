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
                <a href="<?php echo base_url() . "status/mapping_stock_pekanbaru/$kode/$tahun/$bulan"; ?>" class="btn btn-warning" role="button">
                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Proses Mapping ke produk MPM</a>
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
                    <th>kodebarang</th>
                    <th>namabarang</th>
                    <th>barcode</th>
                    <th>gol</th>
                    <th>merk</th>
                    <th>konversi</th>
                    <th>sisa</th>
                    <th>hargapokok</th>
                    <th>jumlah</th>
                    <th>catatan</th>
                    <th>kodesup</th>
                    <th>rasio</th>
                    <th>satuan</th>
                    <th>rak</th>
                    <th>adamutasi</th>
                    <th>Nama File</th>
                    <th>Tanggal Upload</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($get_upload_stock as $a) : ?>
                    <tr>
                        <td>
                            <font size="2px"><?php echo $a->kodebarang; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->namabarang; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->barcode; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->gol; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->merk; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->konversi; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->sisa; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->hargapokok; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->jumlah; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->catatan; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->kodesup; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->rasio; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->satuan; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->rak; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->adamutasi; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->filename; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->created_date; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>kodebarang</th>
                    <th>namabarang</th>
                    <th>barcode</th>
                    <th>gol</th>
                    <th>merk</th>
                    <th>konversi</th>
                    <th>sisa</th>
                    <th>hargapokok</th>
                    <th>jumlah</th>
                    <th>catatan</th>
                    <th>kodesup</th>
                    <th>rasio</th>
                    <th>satuan</th>
                    <th>rak</th>
                    <th>adamutasi</th>
                    <th>Nama File</th>
                    <th>Tanggal Upload</th>
                </tr>
            </tfoot>
        </table>
    </div>


</div>
</div>