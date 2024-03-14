<style>
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
        text-transform: capitalize;
        font-size: 13px;
    }
</style>

<div class="container">
    <div class="col">
        <p class="az-content-label">ASSET - DATA ASSET DETAIL</p>
        <br>
        <div id="accordion">
            <!-- INPUT DETAIL ASSET -->
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                            aria-expanded="true" aria-controls="collapseOne">
                            <strong>DETAIL ASSET</strong>
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <p class="az-content-label">INPUT DETAIL ASSET</p>
                        <br>
                        <form action="<?= base_url('management_asset/data_asset_update')?>" method="post"
                            enctype="multipart/form-data">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="text" name="id" value="<?= $id; ?>" hidden>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Kode</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" class="form-control" name="kode"
                                                        value="<?= $asset->kode; ?>" readonly required>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Tanggal Pembelian</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="date" class="form-control" name="tgl_pembelian"
                                                        value="<?= $asset->tglperol; ?>" required>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Barang</label>
                                                </div>
                                                <div class="col-8">
                                                    <textarea type="text" class="form-control" cols="30" rows="3"
                                                        name="barang" required><?= $asset->barang; ?></textarea>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Jumlah</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="number" class="form-control" min="1" name="jumlah"
                                                        value="<?= $asset->jumlah; ?>" required>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Golongan</label>
                                                </div>
                                                <div class="col-8">
                                                    <select class="form-control" name="golongan" required>
                                                        <option value="">Pilih</option>
                                                        <option value="0.25">GOL I</option>
                                                        <option value="0.125">GOL II</option>
                                                        <option value="0.0625">GOL III</option>
                                                        <option value="0.05">GOL IV</option>
                                                        <option value="0">GOL V</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Grup Asset</label>
                                                </div>
                                                <div class="col-8">
                                                    <select class="form-control" name="grup" required>
                                                        <option value="">Pilih</option>
                                                        <?php foreach ($grup_asset as $key):?>
                                                        <option value="<?= $key->id; ?>"><?= $key->namagrup; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Nilai Perolehan</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" class="form-control" name="nilai_perolehan"
                                                        value="<?= $asset->np; ?>" required>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Keperluan</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" class="form-control" name="keperluan"
                                                        value="<?= $asset->keperluan; ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-4">
                                            <label>Tanggal Jual</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="date" class="form-control" name="tgl_jual"
                                                value="<?= $asset->tgljual; ?>">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-4">
                                            <label>Nilai Jual</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="number" class="form-control" name="nilai_jual"
                                                value="<?= $asset->nj; ?>">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-4">
                                            <label>Keterangan</label>
                                        </div>
                                        <div class="col-8">
                                            <textarea type="text" class="form-control" cols="30" rows="3"
                                                name="keterangan"><?= $asset->keterangan; ?></textarea>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-4">
                                            <label>Upload Faktur</label>
                                        </div>
                                        <div class="col-8">
                                            <?php
                                                if ($asset->upload_faktur == '') {
                                                    echo '<input type="file" class="form-control" name="file" id="file">';
                                                } else {
                                                    # code...
                                                    echo "<a href= ".base_url('assets/file/faktur_asset/'.$asset->upload_faktur)." ><font style='font-size: 18px'><strong>$asset->upload_faktur</strong></font></a>";
                                                    echo '<input type="file" class="form-control" name="file" id="file">';
                                                }
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div align="center">
                                <button type="submit" class="btn btn-info btn-sm">Simpan
                                    Asset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- INPUT PENYERAHAN ASSET -->
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"
                            aria-expanded="false" aria-controls="collapseTwo">
                            <strong>PENYERAHAN ASSET</strong>
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <p class="az-content-label">INPUT PENYERAHAN ASSET</p>
                        <br>
                        <form action="<?= base_url('management_asset/data_asset_penyerahan_tambah')?>" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <input type="text" name="id" value="<?= $id; ?>" hidden>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Kode</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" class="form-control" name="kode"
                                                        value="<?= $asset->kode; ?>" readonly required>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Nama Pengguna</label>
                                                </div>
                                                <div class="col-8">
                                                    <select name="userid_pengguna" class="form-control"
                                                        style="text-transform: capitalize;" required>
                                                        <option value="">- Pilih -</option>
                                                        <?php foreach($user as $value){?>
                                                        <option value="<?= $value->id;?>"><?= $value->username;?> |
                                                            <?= $value->email;?>
                                                        </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Tanggal Penyerahan</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="date" class="form-control" name="tgl_penyerahan"
                                                        required>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Status</label>
                                                </div>
                                                <div class="col-8">
                                                    <select class="form-control" name="status" id="status" required>
                                                        <option value="">- Pilih -</option>
                                                        <option value="baru">Baru</option>
                                                        <option value="mutasi">Mutasi</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Ekspedisi Pengiriman</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" class="form-control" name="ekspedisi_pengiriman">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Resi Pengiriman</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" class="form-control" name="resi_pengiriman">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Biaya Pengiriman</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="number" class="form-control" name="biaya_pengiriman">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div align="center">
                                        <button class="btn btn-info btn-sm" id="search">Simpan Penyerahan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                        <hr>

                        <!-- table history -->
                        <p class="az-content-label" style="text-align: center;">HISTORY PENYERAHAN ASSET</p>
                        <br>
                        <table id="example" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:1%;" class="text-center col-1">
                                        <font color="white">Nama Pengguna
                                    </th>
                                    <th class="text-center col-1">
                                        <font color="white">Tanggal Penyerahan
                                    </th>
                                    <th class="text-center col-1">
                                        <font color="white">Status Barang
                                    </th>
                                    <th class="text-center col-1">
                                        <font color="white">Status
                                    </th>
                            </thead>
                            <tbody style="text-align: center;">
                                <?php foreach ($penyerahan_asset as $key):?>
                                <tr>
                                    <td><?= $key->username; ?></td>
                                    <td><?= $key->tgl_penyerahan; ?></td>
                                    <td><?= $key->status; ?></td>
                                    <td>
                                        <?php
                                        if ($key->flag_pengguna == 1) {
                                            echo 'Aktif';
                                        } else {
                                            echo 'Non-Aktif';
                                        }?>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- history table -->
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