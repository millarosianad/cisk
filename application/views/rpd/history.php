<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }

    table th,
    table td {
        white-space: normal !important;
    }
</style>

<?php

// var_dump($get_history);
// die;

?>
<button class="btn btn-primary btn-primary" onclick="addRpd()"><i class="fa fa-plus"></i>Tambah
    RPD</button>
<a href="<?= base_url() . 'rpd/master_karyawan' ?>" class="btn btn-primary btn-warning admin" target="_blank">Master
    Karyawan</a>
<a href="<?= base_url() . 'rpd/master_biaya' ?>" class="btn btn-primary btn-warning admin" target="_blank">Master
    Biaya</a>
<br><br>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive mt-4">
                <!-- <table id="multi-colum-dt" class="table table-striped table-bordered nowrap"> -->
                <table id="multi-colum-dt" class="table table-hover m-b-0">
                    <thead>
                        <!-- <tr>
                            <th colspan="4">
                                <center></center>
                            </th>
                            <th colspan="3">
                                <center>KM</center>
                            </th>
                            <th colspan="3">
                                <center>Konsumsi</center>
                            </th>
                            <th colspan="1"></th>
                        </tr> -->
                        <tr>
                            <!-- <th width="1%">No</th> -->
                            <th width="10%">Pelaksana</th>
                            <th width="17%">Kode</th>
                            <th width="30%">Tujuan</th>
                            <th width="10%">Tanggal Berangkat</th>
                            <th width="1%">Status</th>
                            <th><center>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_history as $key) : ?>
                            <tr>
                                <!-- <td><?= $no++; ?></td> -->
                                <td><?= ucwords($key->pelaksana); ?></td>
                                <td>
                                    <?php 
                                        if ($key->kode === null) { ?>
                                            <i>need approval</i>
                                        <?php
                                        } else{
                                            echo $key->kode;
                                        }
                                    ?>
                                </td>
                                <td><?= $key->tempat_tujuan; ?></td>
                                <td><?= $key->tanggal_berangkat; ?></td>
                                <td>
                                    <?php
                                    if ($key->status_approval == null) {
                                        echo 'Open';
                                    } elseif ($key->status_approval == '0') {
                                        echo 'Pending Approval';
                                    } elseif ($key->status_approval == '1') {
                                        echo 'Approved';
                                    } elseif ($key->status_approval == '9') {
                                        echo 'Reject';
                                    } else {
                                        echo "Lainnya";
                                    }; ?>
                                </td>
                                <!-- <td><?= $key->tanggal_tiba; ?></td> -->
                                <!-- <td><?= $key->tempat_berangkat; ?></td> -->
                                <!-- <td><?= $key->tempat_tujuan; ?></td> -->
                                <!-- <td><?= $key->keterangan; ?></td> -->
                                <td>
                                    <center>
                                        <a href="<?= base_url() . "rpd/aktivitas/" . $key->signature; ?>" class="btn btn-info btn-sm" target="_blank"><i class="fa fa-plus"></i>aktivitas</a>
                                        
                                        <?php
                                        echo anchor(
                                            'rpd/delete_rpd/' . $key->signature,
                                            'delete',
                                            array(
                                                'class' => 'btn btn-danger btn-sm',
                                                'onclick' => 'return confirm(\'Are you sure?\')'
                                            )
                                        );
                                        ?>
                                        
                                        <a href="<?= base_url() . 'rpd/report_pdf/' . $key->signature ?>" class="btn btn-warning btn-sm" target="_blank">Report</a>
                                    </center>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('rpd/modal_rpd');
?>