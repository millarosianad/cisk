<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
    /* td{
        font-size: 12px;
        text-align: left;
    }
    th{
        font-size: 13px; 
        text-align: left;
    } */

    th{
        font-weight: bold;
        background-color: #383838;
        border: 1px solid #383838;
        color: #f0f0f0;
        align-items: center;
        align-content: center;
        font-size: 13px;
        /* text-align: center; */
    }
    td{
        background-color: #ffffff;
        border: 1px solid #383838;
        font-size: 11px;
        
        /* align-items: center;
        align-content: center; */
    }

    table.dataTable th,
    table.dataTable td {
        white-space: nowrap;
    }

    .btn-submit {
        color: #f0f0f0;
        background-color: #383838;
        border-radius: 5px;
    }

    .btn-submit:hover {
        color: #f0f0f0;
        background-color: #555454;
    }

    .btn-pending {
        color: #2b2929;
        background-color: #d5d4d4;
        border-radius: 5px;
    }

    .btn-pending:hover {
        color: #f0f0f0;
        background-color: #555454;
    }

    .btn-pdf {
        color: #f0f0f0;
        background-color: #154c79;
        border-radius: 5px;
    }

    .btn-pdf:hover {
        color: #f0f0f0;
        background-color: #5b82a1;
    }

    a:link { text-decoration: none; }
    a:visited { text-decoration: none; }
    a:hover { text-decoration: none; }
    a:active { text-decoration: none; }
    
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
            <input type="text" class="form-control" name="pelaksana" value="<?= $name ?>" readonly>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Jabatan</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="jabatan" value="<?= $jabatan ?>" readonly>
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
        <div class="col-md-2">
            <label for="nama_program">Periode Perdin</label>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <input class="form-control" type="date" name="tanggal_mulai" required />
                <input class="form-control" type="date" name="tanggal_akhir" required />
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Radius Perjalanan</label>
        </div>
        <div class="col-md-5">
            <input class="form-control" type="text" name="radius_perjalanan" placeholder="Contoh : 100 KM" required />
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Attachment Radius Perjalanan</label> 
        </div>
        <div class="col-md-5">
            <input type="file" class="form-control" id="file" name="attachment_radius_perjalanan" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2"></div>
        <div class="col-md-5">
            <button type="submit" class="btn btn-submit">Simpan dan Lanjut ke Pengisian Aktivitas</button>
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
            <table id="example" style="overflow-x: scroll;">
                <thead>
                    <tr>
                        <th class="text-center"><font color="white">NoRPD</th>
                        <!-- <th class="text-center"><font color="white">Status</th> -->
                        <th class="text-center"><font color="white">Pelaksana</th>
                        <th class="text-center"><font color="white">Berangkat</th>
                        <th class="text-center"><font color="white">Total Biaya</th>
                        <th class="text-center"><font color="white">Maksud RPD</th>
                        <!-- <th style="background-color: darkgreen;" class="text-center col-2"><font color="white">Tiba</th> -->
                        
                        <th class="text-center"><font color="white">Atasan-1</th>
                        <th class="text-center"><font color="white">Atasan-2</th>
                        <th class="text-center"><font color="white">realisasi</th>
                        <th class="text-center"><font color="white">pdf</th>
                        <!-- <th class="text-center"><font color="white">createdAt</th> -->
                        <th class="text-center"><font color="white">#</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_pengajuan->result() as $a) : ?>
                    <tr>
                        <td align="center">
                            <?php 
                                if ($a->no_rpd) { ?>
                                    <a href="<?= base_url().'management_rpd/aktivitas/'.$a->signature ?>" class="btn btn-submit btn-sm"><?= $a->no_rpd; ?></a>
                                <?php
                                }else{ ?>
                                    <a href="<?= base_url().'management_rpd/aktivitas/'.$a->signature ?>" class="btn btn-pending btn-sm">Belum di ajukan</a>
                                <?php
                                }
                            ?>
                        </td>
                        <!-- <td>
                            <?= $a->nama_status ?>
                            <?php 
                                if($a->jumlah_verifikasi == 2 && $a->verifikasi2_status == null){ 
                                    echo "*";
                                }
                            ?>
                        </td> -->
                        <td><?= $a->pelaksana; ?></td>
                        <td><?= $a->tempat_berangkat.' at '.$a->tanggal_berangkat; ?></td>
                        <!-- <td><?= $a->tempat_tiba.' at '.$a->tanggal_tiba; ?></td> -->
                        <td><strong><?= number_format($a->total_biaya) ?></strong></td>
                        <td><?= substr($a->maksud_perjalanan_dinas,0,20).' ...'; ?></td>
                        
                        <td><?= $a->verifikasi1_name ?></td>
                        <td><?= $a->verifikasi2_name ?></td>
                        <td>
                            <?php 
                                if ($a->status_realisasi == 1) {
                                    $params_realisasi = "done";
                                    ?>
                                <a href="<?= base_url().'management_rpd/realisasi/'.$a->signature ?>" class="btn btn-submit btn-sm" target="_blank"><?= $params_realisasi ?></a>
                                <?php
                                }else{
                                    $params_realisasi = "belum ada";
                                    ?>
                                <a href="<?= base_url().'management_rpd/realisasi/'.$a->signature ?>" class="btn btn-pending btn-sm" target="_blank"><?= $params_realisasi ?></a>
                                <?php
                                }
                            ?>
                            
                        </td>
                        <td><a href="<?= base_url().'management_rpd/generate_pdf/'.$a->signature ?>" class="btn btn-pdf btn-sm" target="_blank">pdf</a></td>
                        <!-- <td><?= $a->created_at ?></td> -->
                        <td><a href="<?= base_url().'management_rpd/realisasi/'.$a->signature ?>" class="btn btn-submit btn-sm" target="_blank">log</a></td>
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
            "scrollX": true,
            "pageLength": 10,
            "ordering": true,
            "order": [9, 'desc'],
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
