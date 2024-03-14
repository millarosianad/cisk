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
        background-color: darkslategray;
    }

    td {
        text-transform: capitalize;
        font-size: 13px;
    }
</style>

<div class="container">
    <div class="col">
        <p class="az-content-label">PENGAJUAN ASSET - INPUT PENGAJUAN</p>
        <br>
        <div class="row">
            <div class="col-md-8">
                <!-- form pengajuan -->
                <form action="<?= base_url($url)?>" method="post">
                    <div class="row">
                        <div class="col-2">
                            <label>Nama</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" style="text-transform: capitalize;"
                                value="<?= $username; ?>" name="nama" id="nama" readonly>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-2">
                            <label>Divisi</label>
                        </div>
                        <div class="col-8">
                            <select class="form-control" name="divisi" id="divisi" required>
                                <option value="">- Pilih Divisi -</option>
                                <option value="AUDIT">AUDIT</option>
                                <option value="FINANCE & ACCOUNTING">FINANCE & ACCOUNTING</option>
                                <option value="KAM">KAM</option>
                                <option value="IT">IT</option>
                                <option value="SALES & MARKETING">SALES & MARKETING</option>
                                <option value="SUPPLY CHAIN">SUPPLY CHAIN</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-2">
                            <label>Barang</label>
                        </div>
                        <div class="col-8">
                            <textarea type="text" class="form-control" cols="30" rows="3" name="barang"
                                id="barang" required></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-2">
                            <label>Keterangan</label>
                        </div>
                        <div class="col-8">
                            <textarea class="form-control" cols="30" rows="3" name="keterangan"
                                id="keterangan" required></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-2">
                        </div>
                        <div class="col-8">
                            <button class="btn btn-info">Simpan Pengajuan Asset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <hr>
        <br>
    </div>
</div>

<div class="container">
    <div class="col">
        <p class="az-content-label" align="center">HISTORY PENGAJUAN ASSET</p>
        <br>
        <table id="example" class="display" style="display: inline-block; overflow-y: scroll; height:400px;">
            <thead>
                <tr>
                    <th class="text-center col-1">
                        <font color="white">No. PR
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Nama (Pengajuan)
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Barang
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Total Biaya
                    </th>
                    <th class="text-center col-2">
                        <font color="white">Status
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Verifikasi 1 <br> (Atasan)
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Verifikasi 2 <br> (IT)
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Verifikasi 3 <br> (Finance)
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pr->result() as $key) :?>
                <tr>
                    <td>
                        <?php 
                            if ($key->status == 4) { ?>
                        <a href="<?= base_url().'management_asset/download_pdf_pr/'.$key->signature; ?>" target="_blank"
                            class="btn btn-warning btn-sm"><?= $key->no_pr; ?></a>
                        <?php } else { ?>
                        <button class="btn btn-warning btn-sm"><?= $key->no_pr; ?></button>
                        <?php } ?>
                    </td>
                    <td><?= $key->username; ?></td>
                    <td><?= $key->barang; ?></td>
                    <td>Rp. <?= number_format($key->total_biaya); ?></td>
                    <td align="center">
                        <?php 
                            if ($key->status == 0) { ?>
                        <a href="<?= base_url().'management_asset/pengajuan_asset_detail/'.$key->signature.'/detail_atasan'; ?>" class="btn btn-info btn-sm"><?= $key->nama_status; ?></a>
                        <?php 
                            } else if ($key->status == 1) { ?>
                        <a href="<?= base_url().'management_asset/pengajuan_asset_detail/'.$key->signature.'/detail_it'; ?>" class="btn btn-info btn-sm"><?= $key->nama_status; ?></a>
                        <?php 
                            } else if ($key->status == 2) { ?>
                        <a href="<?= base_url().'management_asset/pengajuan_asset_detail/'.$key->signature.'/detail_finance'; ?>" class="btn btn-info btn-sm"><?= $key->nama_status; ?></a>
                        <?php 
                            } else if ($key->status == 9) { ?>
                        <a href="<?= base_url().'management_asset/pengajuan_asset_detail/'.$key->signature.'/detail_it'; ?>" class="btn btn-info btn-sm"><?= $key->nama_status; ?></a><?php
                                } else { ?>
                        <a href="<?= base_url().'management_asset/pengajuan_asset_detail/'.$key->signature.'/detail';?>" class="btn btn-info btn-sm"><?= $key->nama_status; ?></a>
                        <?php } ?>
                    </td>
                    <td><?= $key->username_atasan; ?></td>
                    <td><?= $key->username_it; ?></td>
                    <td><?= $key->username_finance; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 100,
            "ordering": false,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
    });
</script>