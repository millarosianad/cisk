<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
    td{
        font-size: 12px;
        text-align: left;
    }
    th{
        font-size: 13px; 
        text-align: left;
    }
</style>

</div>

<div class="container">

<div class="row">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

</form>

    <?= form_open($url); ?>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Pelaksana</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="pelaksana" value="<?= $username ?>" readonly>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Maksud Perjalanan Dinas</label>
        </div>
        <div class="col-md-5">
            <textarea name="maksud_perjalanan_dinas" class="form-control" cols="30" rows="5" required></textarea>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Tanggal Berangkat</label>
        </div>
        <div class="col-md-5">
            <input class="form-control" type="datetime-local" name="tanggal_berangkat" required />
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Tempat Berangkat</label>
        </div>
        <div class="col-md-5">
            <input class="form-control" type="text" name="tempat_berangkat" required />
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Tanggal Tiba</label>
        </div>
        <div class="col-md-5">
            <input class="form-control" type="datetime-local" name="tanggal_tiba" required />
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Tempat Tiba</label>
        </div>
        <div class="col-md-5">
            <input class="form-control" type="text" name="tempat_tiba" required />
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2"></div>
        <div class="col-md-5">
            <button type="submit" class="btn btn-info">Save to pengisian aktivitas</button>
        </div>
    </div>
    
    <?= form_close();?>
    
    <hr>

</div>

<div class="container">
    <div class="row mt-5 ms-5">
        <div class="col-md-12 az-content-label text-center">
            History Perjalanan Dinas
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="example" class="cell-border" style="display: inline-block; overflow-y: scroll">
                <thead>
                    <tr>
                        <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">No</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Pelaksana</th>
                        <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">Maksud RPD</th>
                        <!-- <th style="background-color: darkgreen;" class="text-center col-2"><font color="white">Berangkat</th> -->
                        <!-- <th style="background-color: darkgreen;" class="text-center col-2"><font color="white">Tiba</th> -->
                        <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">Total Biaya</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Status</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Verifikasi1</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Verifikasi2</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">realisasi</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">pdf</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_pengajuan->result() as $a) : ?>
                    <tr>
                        <td align="left">
                            <?php 
                                if ($a->no_rpd) { ?>
                                    <a href="<?= base_url().'management_rpd/aktivitas/'.$a->signature ?>" class="btn btn-outline-info btn-sm"><?= $a->no_rpd; ?></a>
                                <?php
                                }else{ ?>
                                    <a href="<?= base_url().'management_rpd/aktivitas/'.$a->signature ?>" class="btn btn-warning btn-sm">Pending</a>
                                <?php
                                }
                            ?>
                            
                            
                        </td>
                        <td><?= $a->pelaksana; ?></td>
                        <td><?= $a->maksud_perjalanan_dinas; ?></td>
                        <!-- <td><?= $a->tempat_berangkat.' at '.$a->tanggal_berangkat; ?></td> -->
                        <!-- <td><?= $a->tempat_tiba.' at '.$a->tanggal_tiba; ?></td> -->
                        <td><strong>Rp.<?= number_format($a->total_biaya) ?></strong></td>
                        <td>
                            <?= $a->nama_status ?>
                            <?php 
                                if($a->jumlah_verifikasi == 2 && $a->verifikasi2_status == null){ 
                                    echo "*";
                                }
                            ?>
                        </td>
                        <td><?= $a->verifikasi1_name ?></td>
                        <td><?= $a->verifikasi2_name ?></td>
                        <td>
                            <?php 
                                if ($a->status_realisasi == 1) {
                                    $params_realisasi = "done";
                                }else{
                                    $params_realisasi = "null";
                                }
                            ?>
                            <a href="<?= base_url().'management_rpd/realisasi/'.$a->signature ?>" class="btn btn-info btn-sm" target="_blank"><?= $params_realisasi ?></a>
                        </td>
                        <td><a href="<?= base_url().'management_rpd/generate_pdf/'.$a->signature ?>" class="btn btn-warning btn-sm">pdf</a></td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>
        </div>
    </div>
    
    <hr>
    <br>
    <br>

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

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/nama_comp_bonus') ?>',
        data: '',
        success: function(hasil_branch) {
            $("select[name = site_code]").html(hasil_branch);
        }
    });
</script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/nama_program_bonus') ?>',
        data: '',
        success: function(hasil_branch) {
            $("select[name = nama_program]").html(hasil_branch);
        }
    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>
