<a href="<?php echo base_url(); ?>file_download/download" class="btn btn-dark btn-sm btn-round" role="button">Kembali</a>
<div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th></th>
                    <th>
                        <font size="2px">Versi
                    </th>
                    <th>
                        <font size="2px">Kode
                    </th>
                    <th>
                        <font size="2px">Sub Branch
                    </th>
                    <th>
                        <font size="2px">Status
                    </th>
                    <th>
                        <font size="2px">Link
                    </th>
                    <th>
                        <font size="2px">Goggle drive
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proses as $a) : ?>
                    <tr>
                        <td>
                        <?php
                                if ($a->status == 0) { ?>
                                    <a href="<?php echo base_url(); ?>file_download/aktiv_detail/<?= $a->id; ?>/<?= $a->versi; ?>" class="btn btn-success btn-sm btn-round" role="button">Aktifkan</a>
                                <?php } else { ?>
                                    <a href="<?php echo base_url(); ?>file_download/nonaktiv_detail/<?= $a->id; ?>/<?= $a->versi; ?>" class="btn btn-danger btn-sm btn-round" role="button">Non-Aktif</a>
                                <?php } ?>
                        </td>
                        <td>
                            <font size="2px"><?= $a->versi; ?>
                        </td>
                        <td>
                            <font size="2px"><?= $a->kode_comp; ?>
                        </td>
                        <td>
                            <font size="2px"><?= $a->nama_comp; ?>
                        </td>
                        <td>
                            <font size="2px">
                                <?php
                                if ($a->status == 0) {
                                    echo 'Tidak Aktif';
                                } else {
                                    echo 'Aktif';
                                } ?>
                        </td>
                        <td>
                            <font size="2px"><?= $a->link; ?>
                        </td>
                        <td>
                            <font size="2px"><?= $a->link_gdrive; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>

            <tfoot>
                <tr>
                    <th></th>
                    <th>
                        <font size="2px">Versi
                    </th>
                    <th>
                        <font size="2px">Kode
                    </th>
                    <th>
                        <font size="2px">Sub Branch
                    </th>
                    <th>
                        <font size="2px">Status
                    </th>
                    <th>
                        <font size="2px">Link
                    </th>
                    <th>
                        <font size="2px">Goggle drive
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>