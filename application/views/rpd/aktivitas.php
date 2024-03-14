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
// echo $get_history->kode;
// die;

?>

<a href="<?= base_url(); ?>rpd/history" class="btn btn-dark">Kembali</a>
<br><hr>
<div class="row">

    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Status Approval</label>
            <div class="col-sm-7">
            <?php 
                    if ($get_history->status_approval == null) {
                        $status ='<input type="text" style="color:#008000;" name="nopo" class="form-control"
                        value="Open" >';
                        echo $status;
                    } elseif ($get_history->status_approval == '0') {
                        $status ='<input type="text" name="nopo" class="form-control"
                        value="Pending Approval" >';
                        echo $status;
                    } elseif ($get_history->status_approval == '1') {
                        $status ='<input type="text" style="color:#008000;" name="nopo" class="form-control"
                        value="Approved" >';
                        echo $status;
                    } elseif ($get_history->status_approval == '9') {
                        $status ='<input type="text" style="color:#FF0000;" name="nopo" class="form-control"
                        value="Reject" >';
                        echo $status;
                    } else {
                        $status ='<input type="text" name="nopo" class="form-control"
                        value="Lainnya" >';
                        echo $status;
                    };
                ?>
            </div>
        </div>
    </div>
    
    <!-- <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Status</label>
            <div class="col-sm-7">
                <input type="text" name="nopo" class="form-control" value="<?= ucwords($get_history->pelaksana); ?>" >
            </div>
        </div>
    </div> -->
    <!-- <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Kode</label>
            <div class="col-sm-7">
                <input type="text" name="nopo" class="form-control" value="<?= $get_history->kode; ?>" >
            </div>
        </div>
    </div> -->
    <!-- <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Status Approval</label>
            <div class="col-sm-7">
                <?php 
                    if ($get_history->status_approval == null) {
                        $status ='<input type="text" name="nopo" class="form-control"
                        value="Open" >';
                        echo $status;
                    } elseif ($get_history->status_approval == '0') {
                        $status ='<input type="text" name="nopo" class="form-control"
                        value="Pending Approval" >';
                        echo $status;
                    } elseif ($get_history->status_approval == '1') {
                        $status ='<input type="text" style="color:#008000;" name="nopo" class="form-control"
                        value="Approved" >';
                        echo $status;
                    } elseif ($get_history->status_approval == '9') {
                        $status ='<input type="text" style="color:#FF0000;" name="nopo" class="form-control"
                        value="Reject" >';
                        echo $status;
                    } else {
                        $status ='<input type="text" name="nopo" class="form-control"
                        value="Lainnya" >';
                        echo $status;
                    };
                ?>
            </div>
        </div>
    </div> -->
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Kode</label>
            <div class="col-sm-7">
                <input type="text" name="nopo" class="form-control" value="<?= ($get_history->kode) ? $get_history->kode : 'Belum ada aktivitas' ?>" >
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Periode</label>
            <div class="col-sm-7">
                <input type="text" name="nopo" class="form-control" value="<?= $get_history->tanggal_berangkat; ?> sd <?= $get_history->tanggal_tiba; ?>"
                    >
            </div>
        </div>
    </div>
    <!-- <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Tempat Berangkat</label>
            <div class="col-sm-7">
                <input type="text" name="nopo" class="form-control" value="<?= ucwords($get_history->tempat_berangkat); ?>"
                    >
            </div>
        </div>
    </div> -->
</div>

<div class="row">
    <!-- <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Tanggal Tiba</label>
            <div class="col-sm-7">
                <input type="text" name="nopo" class="form-control" value="<?= $get_history->tanggal_tiba; ?>" >
            </div>
        </div>
    </div> -->
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Tujuan</label>
            <div class="col-sm-7">
                <input type="text" name="nopo" class="form-control" value="<?= "dari " .ucwords($get_history->tempat_berangkat); ?> ke <?= ucwords($get_history->tempat_tujuan); ?>"
                    >
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Maksud Perjalanan Dinas</label>
            <div class="col-sm-7">
                <textarea type="text" name="nopo" cols="30" rows="3" class="form-control"
                    ><?= $get_history->maksud_perjalanan_dinas; ?></textarea>
            </div>
        </div>
    </div>
    <!-- <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Pelaksana</label>
            <div class="col-sm-7">
                <input type="text" name="nopo" class="form-control" value="<?= ucwords($get_history->pelaksana); ?>" >
            </div>
        </div>
    </div> -->
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Keterangan</label>
            <div class="col-sm-7">
                <textarea name="" id="" class="form-control" cols="30" rows="3"
                    ><?= $get_history->keterangan; ?></textarea>
            </div>
        </div>
    </div>
</div>
<hr>
<button class="btn btn-primary" onclick="addAktivitas()"><i class="fa fa-plus"></i>Tambah Rencana Aktivitas</button>
<?php if ($get_history->status_approval == '1'){?>
<button class="btn btn-success" onclick="addRealisasi()"><i class="fa fa-plus"></i>Realisasi</button>
<?php }else { ?>
<a href="<?= base_url().'rpd/request_approval/'.$get_history->signature ;?>" class="btn btn-warning"
    target="blank">Request Approval</a>
<?php }?>


<div class="dt-responsive table-responsive mt-4">
    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
        <thead>
            <tr>
                <th>No</th>
                <th>Rencana</th>
                <th>Tanggal</th>
                <th>Detail</th>
                <th>Jenis Pengeluaran</th>
                <!-- <th>Budget</th> -->
                <th>Nominal Biaya</th>
                <th>Keterangan</th>
                <th>
                    <center>#
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($get_aktivitas as $key) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $key->rencana; ?></td>
                <td><?= $key->tanggal; ?></td>
                <td><?= $key->detail; ?></td>
                <td><?= $key->nama_kategori; ?></td>
                <!-- <td>Rp. <?= number_format($key->limit_budget); ?></td> -->
                <td>Rp. <?= number_format($key->nominal_biaya); ?></td>
                <td><?= $key->keterangan; ?></td>
                <td>
                    <center>
                    <?php if ($get_history->status_approval == '1'){?>
                        <a href="<?= base_url().'rpd/view_realisasi/'.$key->signature.'/'.$key->id; ?>" class="btn btn-info btn-sm">View Realisasi</a>
                        |
                        <button class="btn btn-warning btn-sm" onclick="editAktivitas('<?= $key->id; ?>')">Edit</button>
                        |
                            <?php
                            echo anchor(
                                'rpd/delete_aktivitas/'.$key->signature.'/' . md5($key->id),
                                'delete',
                                array(
                                    'class' => 'btn btn-danger btn-sm',
                                    'onclick' => 'return confirm(\'Are you sure?\')'
                                    )
                                );
                            ?>
                        <?php }else { ?>
                            <button class="btn btn-warning btn-sm" onclick="editAktivitas('<?= $key->id; ?>')">Edit</button>
                            |
                            <?php
                            echo anchor(
                                    'rpd/delete_aktivitas/'.$key->signature.'/' . md5($key->id),
                                    'delete',
                                    array(
                                        'class' => 'btn btn-danger btn-sm',
                                        'onclick' => 'return confirm(\'Are you sure?\')'
                                        )
                                    );
                            }?>
                    </center>
                </td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$this->load->view('rpd/modal_aktivitas');
$this->load->view('rpd/modal_realisasi');
?>