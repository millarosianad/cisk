<style>
    td{
        font-size: 13px;
    }
    th{
        font-size: 13px; 
    }
</style>

</div>

<div class="container">

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <h4>Preview Pengajuan RPD</h4>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" class="form-label">Pelaksana</label>
                </div>
                <div class="col-md-8">
                    <!-- <input type="text" class="form-control" value="<?= $pelaksana ?>"> -->
                    <?= $pelaksana ?>
                </div>
            </div>

            <div class="col-md-12 mt-2 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" class="form-label">Maksud Perjalanan Dinas</label>
                </div>
                <div class="col-md-8">
                    <!-- <textarea name="" id="" cols="30" rows="5" class="form-control"><?= $maksud_perjalanan_dinas ?></textarea> -->
                    <?= $maksud_perjalanan_dinas ?>
                </div>
            </div>

            <div class="col-md-12 mt-2 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" class="form-label">Berangkat</label>
                </div>
                <div class="col-md-8">
                    <!-- <input type="text" class="form-control" value="<?= $berangkat ?>"> -->
                    <?= $berangkat ?>
                </div>
            </div>

            <div class="col-md-12 mt-2 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" class="form-label">Tiba</label>
                </div>
                <div class="col-md-8">
                    <!-- <input type="text" class="form-control" value="<?= $tiba ?>"> -->
                    <?= $tiba ?>
                </div>
            </div>

            <div class="col-md-12 mt-2 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" class="form-label">Status</label>
                </div>
                <div class="col-md-8">
                </div>
            </div>

            <div class="col-md-12 mt-2 d-flex justify-content-center">     
                <div class="col-md-3">
                    <label for="kategori" class="form-label">Total Biaya</label> 
                </div>
                <div class="col-md-8">
                    <font color="black" size="4px">Rp. <?= number_format($total_biaya) ?></font>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12 mt-4">
                <h4>Rincian Aktivitas</h4>
            </div>

            <div class="col-md-12 mb-5 mt-4">
                <table id="example" width="100%" class="display" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th style="background-color: gray;" class="text-center col-3"><font color="white">Tanggal</th>
                            <th style="background-color: gray;" class="text-center col-5"><font color="white">Aktivitas</th>
                            <th style="background-color: gray;" class="text-center col-8"><font color="white">Detail</th>
                            <th style="background-color: gray;" class="text-center col-2"><font color="white">Biaya</th>
                            <th style="background-color: gray;" class="text-center col-2"><font color="white">Claim</th>
                            <th style="background-color: gray;" class="text-center col-3"><font color="white">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        foreach ($get_aktivitas->result() as $a) : ?>
                        <tr>
                            <td><?= $a->tanggal_aktivitas; ?></td>
                            <td><?= $a->aktivitas; ?></td>
                            <td><?= $a->detail_aktivitas; ?></td>
                            <td><?= $a->biaya; ?></td>
                            <td><?= $a->status_claim; ?></td>
                            <td><?= $a->keterangan; ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<hr>
<div class="container mb-5">

    <div class="col-md-12 text-center">
        <p>PIC Approval</p>
    </div>

    <div class="col-md-12 mt-5 d-flex justify-content-center">     
        <div class="col-md-6 text-center">
            <p></p>
        </div>
        <div class="col-md-6 text-center">
            <p></p>
        </div>
    </div>

    <div class="col-md-12 mt-5 d-flex justify-content-center">     
        <div class="col-md-6 text-center">
            <p>Suffy</p>
        </div>
        <div class="col-md-6 text-center">
            <p>Yanuar</p>
        </div>
    </div>
</div>

<hr>
<div class="container mb-5">
    <div class="row">
        <div class="col-md-12 text-center">
           <p>Cek kembali data anda. Jika sudah ok, klik Button "Meminta Persetujuan RPD di bawah ini" :</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
           <input type="submit" class="btn btn-danger" value="meminta persetujuan RPD">
        </div>
    </div>
</div>

<script>
      $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 10,
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