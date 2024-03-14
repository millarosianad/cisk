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
    
<?php echo form_open_multipart($url); ?>

<div class="row">
    <div class="col-md-6">

        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>

        
        <div class="col-md-12 mt-4">     
            <label for="biaya" class="form-label">Approve atau Reject ?</label>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status_verifikasi" id="status_verifikasi1" value="1">
                    <label class="form-check-label" for="status_verifikasi1">
                        Approve
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status_verifikasi" id="status_verifikasi2" value="0" checked>
                    <label class="form-check-label" for="status_verifikasi2">
                        Reject
                    </label>
                </div>

        </div>

        <div class="col-md-12 mt-2">     
            <label for="keterangan" class="form-label">Keterangan (opsional)</label>
            <div class="col-md-10 d-flex flex-row">
                <textarea name="keterangan_verifikasi" id="keterangan" cols="30" rows="2" class="form-control"></textarea>
            </div>
        </div>

        <div class="col-md-12 mt-2 mb-5">     
            <label for="keterangan" class="form-label"></label>
            <div class="col-md-10 d-flex flex-row">
                <input class="form-control form-control-md" id="aktivitas" type="hidden" name="id_pengajuan" value="<?= $id_pengajuan ?>" required>
                <input class="form-control form-control-md" id="signature_pengajuan" type="hidden" name="signature_pengajuan" value="<?= $signature_pengajuan ?>" required>


                <?php 
                    if ($status != 10 && $status != 0) { ?>
                        <input type="submit" value="Verifikasi RPD" class="btn btn-info">
                    <?php 
                    }else{ ?>
                        <button type="submit" class="btn btn-dark" disabled>verifikasi tidak bisa dilakukan. Mungkin anda sudah melakukan verifikasi.</button>
                    <?php
                    }
                ?>
            
            </div>
        </div>
    </div>

    
    

    <div class="col-md-6 border border-primary rounded-10 shadow p-3">

        <div class="col-md-12 mt-4 text-center">
            <h4>Rencana Perjalanan Dinas</h4>
        </div>

        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="kategori" class="form-label">No RPD</label>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control" value="<?= $no_rpd ?>">
            </div>
        </div>
        
        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="kategori" class="form-label">Pelaksana</label>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control" value="<?= $pelaksana ?>">
            </div>
        </div>

        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="kategori" class="form-label">Maksud Perjalanan Dinas</label>
            </div>
            <div class="col-md-8">
                <textarea name="" id="" cols="30" rows="5" class="form-control"><?= $maksud_perjalanan_dinas ?></textarea>
            </div>
        </div>

        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="kategori" class="form-label">Berangkat</label>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control" value="<?= $berangkat ?>">
            </div>
        </div>

        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="kategori" class="form-label">Tiba</label>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control" value="<?= $tiba ?>">
            </div>
        </div>

        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="kategori" class="form-label">Status</label>
            </div>
            <div class="col-md-8">
                <?= $nama_status ?>
            </div>
        </div>

        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="kategori" class="form-label">Verifikasi 1</label>
            </div>
            <div class="col-md-8">
                <?php 
                    if ($verifikasi1_at) {
                        echo $verifikasi1_name.' at '.$verifikasi1_at. ' by '.$username_verifikasi1;
                    }
                ?>
            </div>
        </div>

        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="kategori" class="form-label">Verifikasi 2</label>
            </div>
            <div class="col-md-8">
                <?php 
                    if ($verifikasi2_at) {
                        echo $verifikasi2_name.' at '.$verifikasi2_at. ' by '.$username_verifikasi2;
                    }
                ?>
                
            </div>
        </div>

        <div class="col-md-12 mt-3 mb-4 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="kategori" class="form-label">Total Biaya</label> 
            </div>
            <div class="col-md-8">
                <font color="black" size="5px">Rp. <?= number_format($total_biaya) ?></font>
            </div>
        </div>
    </div>

</div>

</form>

<hr>

<div class="container">

<div class="row mt-5">
    <div class="col-md-12 az-content-label text-center">
            Rincian Aktivitas
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table id="example" width="100%" class="display" style="display: inline-block; overflow-y: scroll">
            <thead>
                <tr>
                    <th style="background-color: darkslategray;" class="text-center col-3"><font color="white">Tanggal</th>
                    <th style="background-color: darkslategray;" class="text-center col-5"><font color="white">Aktivitas</th>
                    <th style="background-color: darkslategray;" class="text-center col-8"><font color="white">Detail</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">Biaya</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">Claim</th>
                    <th style="background-color: darkslategray;" class="text-center col-3"><font color="white">Keterangan</th>
                </tr>
            </thead>
            <tbody>     
                <?php 
                foreach ($get_aktivitas->result() as $a) : ?>
                <tr>
                    <td><?= $a->tanggal_aktivitas; ?></td>
                    <td><?= $a->aktivitas; ?></td>
                    <td><?= $a->detail_aktivitas; ?></td>
                    <td><?= number_format($a->biaya); ?></td>
                    <td>
                        <?= ($a->status_claim == 1) ? 'Ya' : 'No' ?>
                    </td>
                    <td><?= $a->keterangan; ?></td>
                </tr>
                <?php endforeach; ?>   
            </tbody>
        </table>
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