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
                <a href="<?php echo base_url() . "status/mapping_stock_padang/$kode/$tahun/$bulan"; ?>" class="btn btn-warning" role="button">
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
                    <th>Kode</th>
                    <th>kodeprod_original</th>
                    <th>nama</th>
                    <th>isi</th>
                    <th>harga_beli</th>
                    <th>m_ctn</th>
                    <th>m_unt</th>
                    <th>rp_mbl</th>
                    <th>g_ctn</th>
                    <th>g_unt</th>
                    <th>rp_gdg</th>
                    <th>t_ctn</th>
                    <th>t_unt</th>
                    <th>rp_stock</th>
                    <th>Nama File</th>
                    <th>Tanggal Upload</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($get_upload_stock as $a) : ?>
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
                            <font size="2px"><?php echo $a->isi; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->harga_beli; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->m_ctn; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->m_unt; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->rp_mbl; ?>
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
                            <font size="2px"><?php echo $a->t_ctn; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->t_unt; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo $a->rp_stock; ?>
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
                    <th>Kode</th>
                    <th>kodeprod_original</th>
                    <th>nama</th>
                    <th>isi</th>
                    <th>harga_beli</th>
                    <th>m_ctn</th>
                    <th>m_unt</th>
                    <th>rp_mbl</th>
                    <th>g_ctn</th>
                    <th>g_unt</th>
                    <th>rp_gdg</th>
                    <th>t_ctn</th>
                    <th>t_unt</th>
                    <th>rp_stock</th>
                    <th>Nama File</th>
                    <th>Tanggal Upload</th>
                </tr>
            </tfoot>
        </table>
    </div>


</div>
</div>