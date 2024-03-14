<style>
    table th,
    table td {
        /* overflow: hidden !important; */
        white-space: normal !important;
    }

    th {
        font-size: 14px !important;
        text-transform: capitalize;
        color: white !important;
        background-color: darkslategray !important;
    }

    td {
        text-transform: uppercase;
        font-size: 13px;
    }

    .detail {
        cursor: pointer;
        padding: 1px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.2s;
        /* border: 2px solid;
        border-radius: 25px; */
        border-top: 5px solid darkslategray;
        border-bottom: 5px solid darkslategray;
        border-left: 5px solid darkslategray;
        border-right: 5px solid darkslategray;
        border-radius: 14px;
        margin-top: 1rem;
        border-top: 1em solid darkslategray;

    }
</style>

<div class="container">
    <div class="col-md-12" style="margin: 10px;">
        <p class="az-content-label">PENGAJUAN ASSET - DETAIL PENGAJUAN ASSET</p>
        <br>
        <div class="detail">
            <div class="col-12">
                <br>
                <h3 align="Center" style="text-transform: uppercase;"><u>PENGAJUAN ASSET</u></h3>
                <br>
                <?php foreach ($pr_summary as $key) : ?>
                    <div class="row" style="justify-content: center; align-items: center;">
                        <div class="col-md-3">
                            <label for="no_pr">No. Purchase Request</label>
                        </div>
                        <div class="col-md-7">
                            <label class="form-control">
                                <?php
                                if ($key->no_pr != null) {
                                    echo "$key->no_pr";
                                } else {
                                    echo "-";
                                }
                                ?>
                            </label>
                        </div>
                    </div>

                    <div class="row" style="justify-content: center; align-items: center;">
                        <div class="col-md-3">
                            <label for="divisi">Divisi</label>
                        </div>
                        <div class="col-md-7">
                            <label class="form-control"><?= $key->divisi; ?></label>
                        </div>
                    </div>

                    <div class="row" style="justify-content: center; align-items: center;">
                        <div class="col-md-3">
                            <label for="username">Nama Yang Mengajukan</label>
                        </div>
                        <div class="col-md-7">
                            <label class="form-control" style="text-transform:capitalize"><?= $key->username; ?></label>
                        </div>
                    </div>

                    <div class="row" style="justify-content: center; align-items: center;">
                        <div class="col-md-3">
                            <label for="tanggal">Tanggal</label>
                        </div>
                        <div class="col-md-7">
                            <label class="form-control"><?= $key->created_at; ?></label>
                        </div>
                    </div>

                    <div class="row" style="justify-content: center; align-items: center;">
                        <div class="col-md-3">
                            <label for="barang">Status</label>
                        </div>
                        <div class="col-md-7">
                            <label class="form-control" style="text-transform:capitalize"><?= $key->nama_status; ?></label>
                        </div>
                    </div>

                    <div class="row" style="justify-content: center; align-items: center;">
                        <div class="col-md-3">
                            <label for="barang">Barang</label>
                        </div>
                        <div class="col-md-7">
                            <label class="form-control" style="text-transform:capitalize"><?= $key->barang; ?></label>
                        </div>
                    </div>

                    <div class="row" style="justify-content: center; align-items: center;">
                        <div class="col-md-3">
                            <label for="keterangan">Keterangan</label>
                        </div>
                        <div class="col-md-7">
                            <label class="form-control" style="text-transform:capitalize"><?= $key->keterangan; ?></label>
                        </div>
                    </div>

                    <div class="row" style="justify-content: center; align-items: center;">
                        <div class="col-md-3">
                            <label for="keterangan">Total Biaya</label>
                        </div>
                        <div class="col-md-7">
                            <label class="form-control">Rp. <?= number_format($key->total_biaya); ?></label>
                        </div>
                    </div>
                    <hr>
            </div>

            <div class="col-12" align="center">
                <div class="col-md-10">
                    <p class="az-content-label">RINCIAN BARANG<br>(Diisi Oleh IT)</p>
                    <br>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 2%;" class="text-center col-1">
                                    <font color="white">No
                                </th>
                                <th class="text-center col-1">
                                    <font color="white">Barang
                                </th>
                                <th class="text-center col-1">
                                    <font color="white">Spesifikasi
                                </th>
                                <th class="text-center col-1">
                                    <font color="white">Harga
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $jml_barang = count($pr_detail);
                            for ($i = 0; $i < $jml_barang; $i++) { ?>
                                <tr>
                                    <td style="text-align: center;"><?= $no++ ?></td>
                                    <td><?= $pr_detail[$i]->barang; ?></td>
                                    <td><?= $pr_detail[$i]->spesifikasi; ?></td>
                                    <td>Rp. <?= number_format($pr_detail[$i]->harga); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <hr>
            </div>
            <br>

            <div class="col-12">
                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label for="keterangan">Verifikasi 1 (Atasan)</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control" style="text-transform:capitalize">
                            <?php
                            if ($key->flag_atasan == 1) {
                                echo "Approved at $key->tgl_konfirmasi_atasan by $key->username_atasan ";
                            } elseif ($key->flag_atasan == 9) {
                                echo "Rejected at $key->tgl_konfirmasi_atasan by $key->username_atasan ";
                            } else {
                                echo "-";
                            }
                            ?>
                        </label>
                    </div>
                </div>

                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label for="keterangan">Verifikasi 2 (IT)</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control" style="text-transform:capitalize">
                            <?php
                            if ($key->flag_it == 1) {
                                echo "Approved at $key->tgl_konfirmasi_it by $key->username_it ";
                            } elseif ($key->flag_it == 9) {
                                echo "Rejected at $key->tgl_konfirmasi_it by $key->username_it ";
                            } else {
                                echo "-";
                            }
                            ?>
                        </label>
                    </div>
                </div>

                <div class="row" style="justify-content: center; align-items: center;">
                    <div class="col-md-3">
                        <label for="keterangan">Verifikasi 3 (Finance)</label>
                    </div>
                    <div class="col-md-7">
                        <label class="form-control" style="text-transform:capitalize">
                            <?php
                            if ($key->flag_finance == 1) {
                                echo "Approved at $key->tgl_konfirmasi_finance by $key->username_finance ";
                            } elseif ($key->flag_finance == 9) {
                                echo "Rejected at $key->tgl_konfirmasi_finance by $key->username_finance ";
                            } else {
                                echo "-";
                            }
                            ?>
                        </label>
                    </div>
                </div>
                <br>
                <div class="col-md-12 mt-3 d-flex justify-content-center" align="center">
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Verifikasi By (Atasan)</h5>
                            <br>
                            <?php if ($key->ttd_atasan != null || $key->ttd_atasan != '') { ?>
                                <img src="<?= base_url('assets/uploads/signature/pengajuan_asset/' . $key->ttd_atasan); ?>" width="70%" alt="ttd_atasan">
                            <?php } ?>
                            <br>
                            <p style="text-transform:capitalize"><?= $key->username_atasan; ?></p>
                        </div>
                        <div class="col-md-4">
                            <h5>Verifikasi By (IT)</h5>
                            <br>
                            <?php if ($key->ttd_it != null || $key->ttd_it != '') { ?>
                                <img src="<?= base_url('assets/uploads/signature/pengajuan_asset/' . $key->ttd_it); ?>" width="70%" alt="ttd_it">
                            <?php } ?>
                            <br>
                            <p style="text-transform:capitalize"><?= $key->username_it; ?></p>
                        </div>
                        <div class="col-md-4">
                            <h5>Verifikasi By (Finance)</h5>
                            <br>
                            <?php if ($key->ttd_finance != null || $key->ttd_finance != '') { ?>
                                <img src="<?= base_url('assets/uploads/signature/pengajuan_asset/' . $key->ttd_finance); ?>" width="70%" alt="ttd_finance">
                            <?php } ?>
                            <br>
                            <p style="text-transform:capitalize"><?= $key->username_finance; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>