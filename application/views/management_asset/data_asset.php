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
        <p class="az-content-label">ASSET - INPUT ASSET</p>
        <br>
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col-md-12">
                        <p><strong><u>Mengambil Data Dari Sistem SDS</u></strong></p>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <label>From</label>
                                        <input class="form-control" type="date" name="from" id="from" required />
                                    </div>
                                    <div class="col-6">
                                        <label>To</label>
                                        <input class="form-control" type="date" name="to" id="to" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div align="center">
                            <button class="btn btn-info btn-sm" id="search">Seacrh</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- form input asset -->
            <div class="col-md-6">
                <form action="<?= base_url('management_asset/data_asset_tambah')?>" method="post">
                    <p><strong><u>Input Data Asset</u></strong></p>
                    <div class="row">
                        <div class="col-4">
                            <label>Kode (SDS)</label>
                        </div>
                        <div class="col-8">
                            <select class="form-control" type="text" name="kode_sds" id="kode_sds" required>
                                <option value=''>Pilih Kode Sds</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-4">
                            <label>Tanggal Pembelian</label>
                        </div>
                        <div class="col-8">
                            <input type="date" class="form-control" id="tgl_pembelian" name="tgl_pembelian" required>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-4">
                            <label>Barang</label>
                        </div>
                        <div class="col-8">
                            <textarea type="text" class="form-control" cols="30" rows="3" id="barang" name="barang"
                                required></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-4">
                            <label>Jumlah</label>
                        </div>
                        <div class="col-8">
                            <input type="number" class="form-control" min="1" name="jumlah" required>
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
                            <input type="number" class="form-control" id="nilai_perolehan" name="nilai_perolehan"
                                required>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-4">
                            <label>Keperluan</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" id="keperluan" name="keperluan" required>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4 d-flex justify-content-center">
                        <p style="text-transform:capitalize">
                            <button type="submit" class="btn btn-info">Simpan
                                Asset</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<br>

<div class="container">
    <div class="col">
        <hr>
        <br>
        <p class="az-content-label" style="text-align: center;">ASSET - DATA ASSET</p>
        <br>
        <table id="example" class="display" style="display: inline-block; overflow-y: scroll; height:400px;">
            <thead>
                <tr>
                    <th style="width:1%;" class="text-center col-1">
                        <font color="white">No
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Kode
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Barang
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Group
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Tanggal Pembelian
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Faktur
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Pengguna
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Status Barang
                    </th>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($asset as $key) :?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td style="text-align: center;"><a
                            href="<?= base_url("management_asset/data_asset_detail/$key->id") ?>"
                            class="btn btn-warning btn-sm"><?= $key->kode; ?></a></td>
                    <td><?= $key->barang; ?></td>
                    <td><?= $key->namagrup; ?></td>
                    <td><?= $key->tglperol; ?></td>
                    <td><a href="<?= base_url('/assets/file/faktur_asset/'.$key->upload_faktur); ?>"
                            target="_blank"><?= $key->upload_faktur; ?></a>
                    </td>
                    <td><?= $key->username; ?></td>
                    <td>
                        <?php 

                            if($key->nj==0||$key->nj=='')
                            {
                                $status ='Aktif';
                            }
                            else
                            {
                                $status ='Jual';
                            }

                            echo $status; 
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- input data -->
<script>
    $(document).ready(function () {
        $('form').hide();

        //mengambil kode sds
        $('button#search').click(function (e) {
            $('button#search').attr('disabled', true);
            $('textarea#barang').val('');
            $('input#nilai_perolehan').val('');
            $('input#keperluan').val('');
            $('input#tgl_pembelian').val('');

            $.ajax({
                url: "<?= base_url().'management_asset/get_asset_sds'; ?>",
                method: "POST",
                data: {
                    from: document.getElementById("from").value,
                    to: document.getElementById("to").value,
                },
                success: function () {
                    alert('Berhasil Mengambil Data Dari SDS');
                    $('form').show();
                    $.ajax({
                        url: "<?= base_url(). 'management_asset/get_asset'; ?>",
                        method: "POST",
                        async: true,
                        dataType: "json",
                        success: function (data) {
                            $('button#search').attr('disabled', false);
                            var html =
                                "<option value=''>Pilih Kode Sds</option>";
                            var i;

                            for (i = 0; i < data.length; i++) {
                                html += '<option value=' + data[i].nojurnal +
                                    '-' +
                                    data[i].nourut + '>' + data[i].nojurnal +
                                    ' - ' +
                                    data[i].nourut + '</option>';
                            }
                            $('#kode_sds').html(html);
                        }
                    });
                },
            });
        });

        $("select#kode_sds").change(function () {
            var kode_sds = document.getElementById("kode_sds").value;

            $.ajax({
                type: "POST",
                url: "<?= base_url() . 'management_asset/get_asset_by_kode'; ?>",
                data: {
                    kode_sds: kode_sds
                },
                dataType: "json",
                success: function (response) {
                    var debet = response.debet
                    var kredit = response.kredit

                    if (debet > kredit) {
                        var nilai_perolehan = debet
                    } else {
                        var nilai_perolehan = kredit
                    }

                    $('textarea#barang').val(response.keterangan)
                    $('input#nilai_perolehan').val(nilai_perolehan);
                    $('input#keperluan').val(response.description)
                    $('input#tgl_pembelian').val(response.tgl_trans.substr(0, 10))
                        .change()
                }
            });
        });
    });
</script>

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