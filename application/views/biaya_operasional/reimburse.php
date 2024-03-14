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
<a href="<?= base_url('biaya_operasional');?>" class="btn btn-dark btn-sm">Kembali</a>
<hr>

<?= form_open($url);?>
<input type="text" name="from" value="<?= $from; ?>" hidden>
<input type="text" name="to" value="<?= $to; ?>" hidden>
<div class="card-header">
    <button class="btn btn-success btn-sm">Proses Reimburse dan Export</button>
    <div class="card-block">
        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap" width="100%">
                <thead>
                    <th rowspan="2">
                        <center>
                            <input type="button" class="btn btn-default btn-sm" id="toggle" value="click all"
                                onclick="click_all_request()">
                    </th>
                    </center>
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
                    <?php foreach($data_reimburse as $key) : ?>
                    <tr>
                        <td>
                            <center>
                                <input type="checkbox" id="<?= $key->id ?>" name="options[]" value="<?= $key->id ?>">
                        </td>
                        </center>
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
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= form_close();?>