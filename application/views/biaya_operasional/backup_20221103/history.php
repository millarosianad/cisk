<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }

    table th,
    table td {
        text-transform: Capitalize;
        white-space: normal !important;
    }
</style>

<?php

// var_dump($get_history);
// die;

?>
<button class="btn btn-primary btn-primary" onclick="addBiaya()"><i class="fa fa-plus"></i>Tambah
    Pengajuan</button>
<button class="btn btn-primary btn-warning" data-toggle="modal" data-target="#report">Report</button>
<button class="btn btn-primary btn-success" data-toggle="modal" data-target="#reimburse">Reimburse</button>

<hr>

<div class="card-header">
    <div class="card-block">
        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap" width="100%">
                <thead>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Tanggal Transaksi</th>
                    <th rowspan="2">User</th>
                    <!-- <th>Kode</th> -->
                    <th rowspan="2">Kategori</th>
                    <th colspan="3">
                        <center>KM</center>
                    </th>
                    <th colspan="3">
                        <center>Konsumsi</center>
                    </th>
                    <th rowspan="2">
                        <center>Status Reimburse
                    </th>
                    <th rowspan="2">
                        <center>#
                    </th>
                    </tr>
                    <tr>
                        <th>Awal</th>
                        <th>Akhir</th>
                        <th>Total (Km)</th>
                        <th>Liter (L)</th>
                        <th>Total Biaya (Rp)</th>
                        <th>Konsumsi (L)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <!-- <? var_dump($get_history);   ?> -->
                    <?php foreach($get_history as $key) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $key->tanggal_transaksi; ?></td>
                        <td><?= $key->username; ?></td>
                        <!-- <td><?= $key->kode; ?></td>     -->
                        <td><?php
                                    if ($key->kategori == '1') {
                                        echo "bensin";
                                    }else{
                                        echo $key->kategori; 
                                    } 
                                ?>
                        </td>
                        <td>
                            <?php 
                                    if ($key->km_awal == '') {
                                        echo "<i>null</i>";
                                    }else{
                                        echo $key->km_awal; 
                                    }
                                ?>
                        </td>
                        <td><?= $key->km_akhir; ?></td>
                        <td><?= $key->total_km; ?></td>
                        <td><?= $key->liter; ?></td>
                        <td><?= $key->total_biaya; ?></td>
                        <td><?= $key->konsumsi; ?></td>
                        <td>
                            <?php  if ($key->reimburse == 1) {
                                        echo 'Ya';
                                    } else {
                                        echo 'Tidak';
                                }; ?>
                        </td>
                        <!-- <td><?= substr($key->file,0,15)." ..."; ?></td>     -->
                        <!-- <td><?= $key->username.' at '.$key->created_at; ?></td>     -->
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editBiaya('<?= $key->id; ?>')"><i
                                    class="fa fa-plus"></i>Edit</button>
                            <?= anchor(
                                        'biaya_operasional/delete/' . $key->signature . '/' .$key->id,
                                        'delete',
                                        array(
                                            'class' => 'btn btn-danger btn-sm',
                                            'onclick' => 'return confirm(\'Are you sure?\')'
                                        )
                                    );
                                ?>
                        </td>

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php
$this->load->view('biaya_operasional/modal_tambah_pengajuan');
$this->load->view('biaya_operasional/modal_report');
$this->load->view('biaya_operasional/modal_reimburse');
?>