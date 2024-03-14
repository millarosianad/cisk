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
        text-transform: uppercase;
        font-size: 13px;
    }

    .detail {
        /* cursor: pointer; */
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
    <div class="col">
        <div class="row">
            <div class="col">
                <p class="az-content-label">PENGAJUAN ASSET - KONFIRMASI PENGAJUAN ASSET (IT)</p>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <!-- form pengajuan -->
                        <form action="<?= base_url('management_asset/pengajuan_asset_konfirm_it_tambah')?>"
                            method="post">
                            <input class="form-control" name="signature" id="signature" value="<?= $signature; ?>"
                                hidden>

                            <div id="barang">
                                <div class="row">
                                    <div class="col-3">
                                        <label>Barang</label>
                                    </div>
                                    <div class="col-9">
                                        <input class="form-control" id="barang_it" name="barang_it[]">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-3">
                                        <label>Spesifikasi</label>
                                    </div>
                                    <div class="col-9">
                                        <textarea class="form-control" id="spesifikasi" name="spesifikasi[]" cols="30"
                                            rows="3"></textarea>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-3">
                                        <label>Harga</label>
                                    </div>
                                    <div class="col-9">
                                        <input class="form-control" type="number" id="harga" name="harga[]">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-3">
                                        <label>Link</label>
                                    </div>
                                    <div class="col-9">
                                        <textarea class="form-control" id="link" name="link[]" cols="30"
                                            rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <p align="center">
                                <a href="#" id="btn_add_barang">+ Tambah</a>
                            </p>
                            <div class="row">
                                <div class="col-3">
                                    <label>Keterangan</label>
                                </div>
                                <div class="col-9">
                                    <textarea class="form-control" name="keterangan_it" id="keterangan_it" cols="30"
                                        rows="3"></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-3">
                                    <label>Tanda Tangan Digital</label>
                                </div>
                                <div class="col-9">
                                    <div id="sig"></div>
                                    <textarea name="signed" id="signature64" cols="35" rows="4" style="display: none;"
                                        required></textarea>
                                    <br>
                                    <button id="clear" class="btn btn-dark btn-sm mt-2" type="reset">Hapus TTD</button>
                                </div>
                            </div>
                            <hr>
                            <div class="col-md-12 mt-3 d-flex justify-content-center">
                                <p style="text-transform:capitalize">
                                    <button type="submit" class="btn btn-info" value="1" name="submit"
                                        id="simpan">Simpan</button>
                                    <button type="submit" class="btn btn-danger" value="9" name="submit"
                                        id="reject">Tolak</button>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6" style="margin: 10px;">
                <div class="detail">
                    <div class="col-12">
                        <br>
                        <h3 align="Center" style="text-transform: uppercase;"><u>PENGAJUAN ASSET</u></h3>
                        <br>
                        <?php foreach ($pr_summary as $key): ?>
                        <div class="row" style="justify-content: center; align-items: center;">
                            <div class="col-md-4">
                                <label for="no_pr">No. Purchase Request</label>
                            </div>
                            <div class="col-md-8">
                                <label class="form-control">
                                    <?php 
                                if ($key->no_pr != null) {
                                    echo "$key->no_pr"; 
                                } else {
                                    echo "-";
                                }
                                ?></label>
                            </div>
                        </div>

                        <div class="row" style="justify-content: center; align-items: center;">
                            <div class="col-md-4">
                                <label for="divisi">Divisi</label>
                            </div>
                            <div class="col-md-8">
                                <label class="form-control"><?= $key->divisi; ?></label>
                            </div>
                        </div>

                        <div class="row" style="justify-content: center; align-items: center;">
                            <div class="col-md-4">
                                <label for="username">Nama Yang Mengajukan</label>
                            </div>
                            <div class="col-md-8">
                                <label class="form-control"
                                    style="text-transform:capitalize"><?= $key->username; ?></label>
                            </div>
                        </div>

                        <div class="row" style="justify-content: center; align-items: center;">
                            <div class="col-md-4">
                                <label for="tanggal">Tanggal</label>
                            </div>
                            <div class="col-md-8">
                                <label class="form-control"><?= $key->created_at; ?></label>
                            </div>
                        </div>

                        <div class="row" style="justify-content: center; align-items: center;">
                            <div class="col-md-4">
                                <label for="barang">Status</label>
                            </div>
                            <div class="col-md-8">
                                <label class="form-control"
                                    style="text-transform:capitalize"><?= $key->nama_status; ?></label>
                            </div>
                        </div>

                        <div class="row" style="justify-content: center; align-items: center;">
                            <div class="col-md-4">
                                <label for="barang">Barang</label>
                            </div>
                            <div class="col-md-8">
                                <label class="form-control"
                                    style="text-transform:capitalize"><?= $key->barang; ?></label>
                            </div>
                        </div>

                        <div class="row" style="justify-content: center; align-items: center;">
                            <div class="col-md-4">
                                <label for="keterangan">Keterangan</label>
                            </div>
                            <div class="col-md-8">
                                <label class="form-control"
                                    style="text-transform:capitalize"><?= $key->keterangan; ?></label>
                            </div>
                        </div>

                        <div class="row" style="justify-content: center; align-items: center;">
                            <div class="col-md-4">
                                <label for="keterangan">Total Biaya :</label>
                            </div>
                            <div class="col-md-8">
                                <label class="form-control">Rp. <?= number_format($key->total_biaya); ?></label>
                            </div>
                        </div>

                        <hr>

                        <div class="row" style="justify-content: center; align-items: center;">
                            <div class="col-md-4">
                                <label for="keterangan">Verifikasi 1 (Atasan)</label>
                            </div>
                            <div class="col-md-8">
                                <label class="form-control" style="text-transform:capitalize">
                                    <?php 
                                if ($key->flag_atasan == 1) {
                                    echo "Approved at $key->tgl_konfirmasi_atasan by $key->username_atasan "; 
                                } elseif ($key->flag_atasan == 9) {
                                    echo "Rejected at $key->tgl_konfirmasi_atasan by $key->username_atasan "; 
                                }else {
                                    echo "-";
                                }
                                ?>
                                </label>
                            </div>
                        </div>

                        <div class="row" style="justify-content: center; align-items: center;">
                            <div class="col-md-4">
                                <label for="keterangan">Verifikasi 2 (IT)</label>
                            </div>
                            <div class="col-md-8">
                                <label class="form-control" style="text-transform:capitalize">
                                    <?php 
                                if ($key->flag_it == 1) {
                                    echo "Approved at $key->tgl_konfirmasi_it by $key->username_it "; 
                                } elseif ($key->flag_it == 9) {
                                    echo "Rejected at $key->tgl_konfirmasi_it by $key->username_it "; 
                                }else {
                                    echo "-";
                                }
                                ?>
                                </label>
                            </div>
                        </div>

                        <div class="row" style="justify-content: center; align-items: center;">
                            <div class="col-md-4">
                                <label for="keterangan">Verifikasi 3 (Finance)</label>
                            </div>
                            <div class="col-md-8">
                                <label class="form-control" style="text-transform:capitalize">
                                    <?php 
                                if ($key->flag_finance == 1) {
                                    echo "Approved at $key->tgl_konfirmasi_finance by $key->username_finance "; 
                                } elseif ($key->flag_finance == 9) {
                                    echo "Rejected at $key->tgl_konfirmasi_finance by $key->username_finance "; 
                                }else {
                                    echo "-";
                                }
                                ?>
                                </label>
                            </div>
                        </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="col">
        <hr>
        <br>
        <p class="az-content-label" align="center">RINCIAN PENGAJUAN ASSET</p>
        <br>
        <table id="example" class="display" style="display: inline-block; overflow-y: scroll">
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
                    <th class="text-center col-1">
                        <font color="white">Link
                    </th>
                    <th class="text-center col-1">
                        <font color="white">#
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
                    <td class="text-center">
                        <?php 
                            if ($key->deleted == 0) { ?>
                        <a href="<?= base_url().'management_asset/pengajuan_asset_konfirm_it_delete/'.$signature.'/'. $key->id; ?>"
                            class="btn btn-danger btn-sm" onclick="return confirm('Yakin menghapus row ini ?')">x</a>
                        <?php 
                            }else{ ?>
                        <button type="submit" class="btn btn-dark" disabled>X</button>
                        <?php
                            }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php $jumlah = count($pr_detail);
        if ($jumlah == 0) { ?>
        <div style="text-align: center;">
            <p">Klik "Setujui dan Kirim Ke Finance" untuk melanjutkan proses finance</p>
                <button type="submit" class="btn btn-dark" disabled>Setujui dan Kirim Ke Finance</a>
        </div>
        <?php } else { ?>
        <div style="text-align: center;">
            <p">Klik "Setujui dan Kirim Ke Finance" untuk melanjutkan proses finance</p>
                <a href="<?= base_url('management_asset/pengajuan_asset_konfirm_it/'.$signature); ?>" type="submit"
                    class="btn btn-info" style="background-color: darkgreen;">Setujui dan Kirim Ke Finance</a>
        </div>
        <?php } ?>
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

    $("#btn_add_barang").click(function () {
        // var userid = 547;
        var i = 1;

        var append_barang = '<div class="row">' +
            '<div class="col-3">' +
            '<label>Barang</label>' +
            '</div>' +
            '<div class="col-8">' +
            '<input class="form-control" id="barang_it" name="barang_it[]">' +
            '</div>' +
            '</div>' +
            '<br>' +
            '<div class="row">' +
            '<div class="col-3">' +
            '<label>Spesifikasi</label>' +
            '</div>' +
            '<div class="col-8">' +
            '<textarea class="form-control" id="spesifikasi" name="spesifikasi[]" cols="30 rows="3"></textarea>' +
            '</div>' +
            '</div>' +
            '<br>' +
            '<div class="row">' +
            '<div class="col-3">' +
            '<label>Harga</label>' +
            '</div>' +
            '<div class="col-8">' +
            '<input class="form-control" type="number" id="harga" name="harga[]">' +
            '</div>' +
            '</div>' +
            '<br>' +
            '<div class="row">' +
            '<div class="col-3">' +
            '<label>Link</label>' +
            '</div>' +
            '<div class="col-8">' +
            '<textarea class="form-control" id="link" name="link[]" cols="30" rows="3"></textarea>' +
            '<br><a href="#" class="btn btn-danger btn-sm" onclick="Delete_barang('.concat(+i,
                ')">Delete</a>') +
            '</div>' +
            '</div>'
        $("div#barang").append('<div class="append_barang append_barang'.concat(+i, '">', '<hr>',
            append_barang));
        index++;
    });

    function Delete_barang(param) {
        $(".append_barang" + param).remove();
    }
</script>

<script>
    $('button#reject').click(function (e) {
        $("#signature64").removeAttr("required");
    });
</script>

<script>
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