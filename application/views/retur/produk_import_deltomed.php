<a href="<?= base_url() . 'retur/produk_pengajuan/' . $this->uri->segment('3') . '/' . $this->uri->segment('4'); ?>" class="btn btn-dark btn-sm" role="button">kembali</a>

<a href="<?= base_url() . 'retur/delete_history_import/' . $this->uri->segment('3') . '/' . $this->uri->segment('4'); ?>" class="btn btn-danger btn-sm" role="button">Delete History Import</a>

<br><br>
<!-- <p id="loadingImage" style="font-size: 60px; display: none">Loading ...</p> -->

<div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th width="2"><font size="2px">Category</th>
                    <th width="2"><font size="2px">Kodeprod</th>
                    <th width="2">
                        <font size="2px">Namaprod
                    </th>
                    <th width="2">
                        <font size="2px">BatchNumber
                    </th>
                    <th width="2">
                        <font size="2px">ExpiredDate
                    </th>
                    <th width="2">
                        <font size="2px">Jumlah
                    </th>
                    <th width="2" class="supp-us">
                        <font size="2px">Satuan
                    </th>
                    <th width="2">
                        <font size="2px">Alasan Retur
                    </th>
                    <th width="2" class="supp-us">
                        <font size="2px">Nama Outlet
                    </th>
                    <th width="2" class="supp-us">
                        <font size="2px">Keterangan
                    </th class="supp-us">
                    <th width="2">Hapus</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($product as $a) : ?>
                    <tr>
                        <td><font size="2px"><?= $a->category; ?></td>
                        <td><font size="2px"><?= $a->kodeprod; ?></td>
                        <td>
                            <font size="2px"><?= $a->namaprod; ?>
                        </td>
                        <td>
                            <font size="2px"><?= $a->batch_number; ?>
                        </td>
                        <td>
                            <font size="2px"><?= $a->expired_date; ?>
                        </td>
                        <td>
                            <font size="2px"><?= $a->jumlah; ?>
                        </td>
                        <td class="supp-us">
                            <font size="2px"><?= $a->satuan; ?>
                        </td>
                        <td>
                            <font size="2px"><?= $a->alasan; ?>
                        </td>
                        <td class="supp-us">
                            <font size="2px"><?= $a->nama_outlet; ?>
                        </td>
                        <td class="supp-us">
                            <font size="2px"><?= $a->keterangan; ?>
                        </td>
                        <td>
                            <font size="2px">
                                <?php
                                echo anchor(
                                    'retur/produk_import_delete/' . $this->uri->segment('3') . '/' . $this->uri->segment('4') . '/' . $a->id,
                                    ' ',
                                    array(
                                        'class' => 'fa fa-times fa-2x', 'style' => 'color:red',
                                        'onclick' => 'return confirm(\'Hapus produk ini ?\')'
                                    )
                                );
                                ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
    <a href="<?= base_url() . "retur/produk_import_simpan/$signature/$supp"; ?>" class="btn btn-success btn-sm btn-mat" role="button">Simpan</a>
</div>
<script>
    $supp = <?= $this->uri->segment('4') ?>;
    if ($supp == '005') {
        $('.supp-us').show()
    } else {
        $('.supp-us').hide()
    }
</script>