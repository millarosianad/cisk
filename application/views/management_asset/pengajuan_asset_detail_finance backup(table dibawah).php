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
        background-color: darkgreen;
    }

    td {
        text-transform: uppercase;
        font-size: 13px;
    }
</style>

<div class="container">
    <div class="col">
        <div class="row">
            <div class="col-md-8" style="margin: 10px;">
                <p class="az-content-label">PENGAJUAN ASSET - KONFIRMASI PENGAJUAN ASSET (FINANCE)</p>
                <br>
                <div class="detail border border-primary rounded-20 shadow p-3"
                    style="margin-left: 10px; margin-right: 10px;">
                    <br>
                    <h3 align="Center" style="text-transform: uppercase;"><u>PENGAJUAN ASSET</u></h3>
                    <br>
                    <?php foreach ($pr_summary as $key): ?>
                    <div class="col-md-12 mt-3 d-flex justify-content-center">
                        <div class="col-md-5">
                            <label for="no_pr">No. Purchase Request</label>
                        </div>
                        :
                        <div class="col-md-7">
                            <p><?= $key->no_pr; ?>
                                <?php 
                                if ($key->no_pr != null) {
                                    echo "$key->no_pr"; 
                                } else {
                                    echo "-";
                                }
                                ?></p>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3 d-flex justify-content-center">
                        <div class="col-md-5">
                            <label for="divisi">Divisi</label>
                        </div>
                        :
                        <div class="col-md-7">
                            <p><?= $key->divisi; ?></p>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3 d-flex justify-content-center">
                        <div class="col-md-5">
                            <label for="username">Nama Yang Mengajukan</label>
                        </div>
                        :
                        <div class="col-md-7">
                            <p style="text-transform:capitalize"><?= $key->username; ?></p>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3 d-flex justify-content-center">
                        <div class="col-md-5">
                            <label for="tanggal">Tanggal</label>
                        </div>
                        :
                        <div class="col-md-7">
                            <p><?= $key->created_at; ?></p>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3 d-flex justify-content-center">
                        <div class="col-md-5">
                            <label for="barang">Status</label>
                        </div>
                        :
                        <div class="col-md-7">
                            <p style="text-transform:capitalize"><?= $key->nama_status; ?></p>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3 d-flex justify-content-center">
                        <div class="col-md-5">
                            <label for="barang">Barang</label>
                        </div>
                        :
                        <div class="col-md-7">
                            <p style="text-transform:capitalize"><?= $key->barang; ?></p>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3 d-flex justify-content-center">
                        <div class="col-md-5">
                            <label for="keterangan">Keterangan</label>
                        </div>
                        :
                        <div class="col-md-7">
                            <p style="text-transform:capitalize"><?= $key->keterangan; ?></p>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3 d-flex justify-content-center">
                        <div class="col-md-5">
                            <label for="keterangan">Total Biaya :</label>
                        </div>
                        :
                        <div class="col-md-7">
                            <p>Rp. <?= number_format($key->total_biaya); ?></p>
                        </div>
                    </div>

                    <hr>
                    <p class="az-content-label" align="center">RINCIAN PENGAJUAN ASSET</p>
                    <br>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="background-color: darkgreen; width: 2%;" class="text-center col-1">
                                    <font color="white">No
                                </th>
                                <th style="background-color: darkgreen;" class="text-center col-1">
                                    <font color="white">Barang
                                </th>
                                <th style="background-color: darkgreen;" class="text-center col-1">
                                    <font color="white">Spesifikasi
                                </th>
                                <th style="background-color: darkgreen;" class="text-center col-1">
                                    <font color="white">Harga
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $jml_barang = count($pr_detail);
                            for ($i=0; $i < $jml_barang; $i++) { ?>
                            <tr>
                                <td style="text-align: center;"><?= $no++?></td>
                                <td><?= $pr_detail[$i]->barang; ?></td>
                                <td><?= $pr_detail[$i]->spesifikasi; ?></td>
                                <td>Rp. <?= number_format($pr_detail[$i]->harga); ?></td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                    <br>
                    <hr>

                    <div class="col-md-12 mt-3 d-flex justify-content-center">
                        <div class="col-md-5">
                            <label for="keterangan">Verifikasi 1 (Atasan)</label>
                        </div>
                        :
                        <div class="col-md-7">
                            <p style="text-transform:capitalize">
                                <?php 
                                if ($key->flag_atasan == 1) {
                                    echo "Approved at $key->tgl_konfirmasi_atasan by $key->username_atasan "; 
                                } elseif ($key->flag_atasan == 9) {
                                    echo "Rejected at $key->tgl_konfirmasi_atasan by $key->username_atasan "; 
                                }else {
                                    echo "-";
                                }
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3 d-flex justify-content-center">
                        <div class="col-md-5">
                            <label for="keterangan">Verifikasi 2 (IT)</label>
                        </div>
                        :
                        <div class="col-md-7">
                            <p style="text-transform:capitalize">
                                <?php 
                                if ($key->flag_it == 1) {
                                    echo "Approved at $key->tgl_konfirmasi_it by $key->username_it "; 
                                } elseif ($key->flag_it == 9) {
                                    echo "Rejected at $key->tgl_konfirmasi_it by $key->username_it "; 
                                }else {
                                    echo "-";
                                }
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3 d-flex justify-content-center">
                        <div class="col-md-5">
                            <label for="keterangan">Verifikasi 3 (Finance)</label>
                        </div>
                        :
                        <div class="col-md-7">
                            <p style="text-transform:capitalize">
                                <?php 
                                if ($key->flag_finance == 1) {
                                    echo "Approved at $key->tgl_konfirmasi_finance by $key->username_finance "; 
                                } elseif ($key->flag_finance == 9) {
                                    echo "Rejected at $key->tgl_konfirmasi_finance by $key->username_finance "; 
                                }else {
                                    echo "-";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    <br>
                    <?php if ($key->flag_finance == 0) { ?>
                    <form action="<?= base_url("management_asset/pengajuan_asset_konfirm_finance") ?>" method="post">
                        <input type="text" class="form-control" style="text-transform: capitalize;"
                            value="<?= $signature; ?>" name="signature" id="signature" hidden>
                        <div class="col-md-12 mt-3 d-flex justify-content-right">
                            <div class="col-md-5">
                                <label for="signature64">Tanda Tangan Digital</label>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3 d-flex justify-content-center">
                            <div class="col-md-12">
                                <div id="sig"></div>
                                <textarea name="signed" id="signature64" cols="35" rows="5"
                                    style="display: none;"></textarea>
                                <br>
                                <button id="clear" class="btn btn-dark btn-sm mt-2" type="reset">Clear TTD</button>
                            </div>
                        </div>

                        <hr>

                        <div class="col-md-12 mt-3 d-flex justify-content-center">
                            <p style="text-transform:capitalize">
                                <button type="submit" class="btn btn-info" style="background-color: darkgreen;">Simpan
                                    Verifikasi</button>
                            </p>
                        </div>
                    </form>
                    <?php } ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="container">
    <div class="col">
        <hr>
        <br>
        <p class="az-content-label" align="center">RINCIAN PENGAJUAN ASSET</p>
        <br>
        <table id="example" class="display" style="display: inline-block; overflow-y: scroll">
            <thead>
                <tr>
                    <th style="background-color: darkgreen; width: 2%;" class="text-center col-1">
                        <font color="white">No
                    </th>
                    <th style="background-color: darkgreen;" class="text-center col-1">
                        <font color="white">Barang
                    </th>
                    <th style="background-color: darkgreen;" class="text-center col-1">
                        <font color="white">Spesifikasi
                    </th>
                    <th style="background-color: darkgreen;" class="text-center col-1">
                        <font color="white">Harga
                    </th>
                    <th style="background-color: darkgreen;" class="text-center col-1">
                        <font color="white">Link
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $no = 1;
                    foreach ($pr_detail as $key) :?>
                <tr>
                    <td><?= $no++?></td>
                    <td><?= $key->barang; ?></td>
                    <td><?= $key->spesifikasi; ?></td>
                    <td>Rp. <?= number_format($key->harga); ?></td>
                    <td><?= $key->link; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div> -->

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

<script type="text/javascript">
    var sig = $('#sig').signature({
        syncField: '#signature64',
        syncFormat: 'PNG'
    });
    $('#clear').click(function (e) {
        //   e.preventDefault();
        sig.signature('clear');
        $("#signature64").val('');
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>