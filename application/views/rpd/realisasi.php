<a href="<?= base_url().$url; ?>" class="btn btn-dark">Kembali</a>
<div class="dt-responsive table-responsive mt-4">
    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
        <thead>
            <tr>
                <th>No</th>
                <th>Rencana</th>
                <th>Tanggal</th>
                <th>Detail</th>
                <th>Jenis Pengeluaran</th>
                <th>Nominal Biaya</th>
                <th>Keterangan</th>
                <th>
                    <center>#
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php 
                            // var_dump($get_aktivitas);
                        ?>
            <?php foreach ($get_realisasi as $key) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $key->rencana; ?></td>
                <td><?= $key->tanggal; ?></td>
                <td><?= $key->detail; ?></td>
                <td><?= $key->jenis_pengeluaran; ?></td>
                <td>Rp. <?= number_format($key->nominal_biaya); ?></td>
                <td><?= $key->keterangan; ?></td>
                <td>
                    <center>
                        <button class="btn btn-warning btn-sm" onclick="editRealisasi('<?= $key->id; ?>')">Edit</button>
                        |
                        <?php
                        echo anchor(
                            'rpd/delete_realisasi/'.$key->signature.'/' . md5($key->id).'/' .$key->aktivitas_id,
                            'delete',
                            array(
                                'class' => 'btn btn-danger btn-sm',
                                'onclick' => 'return confirm(\'Are you sure?\')'
                                )
                            );?>
                    </center>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$this->load->view('rpd/modal_edit_realisasi');
?>