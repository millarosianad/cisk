<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
   
    th{
        /* font-weight: bold; */
        background-color: #ffb64d;
        /* background-color: #FFA447; */
        border: 0.1px solid #383838;
        color: #000000;
        /* color: #f0f0f0; */
        font-size: 13px;
    }
    td{
        background-color: #ffffff;
        border: 0.5px solid #000000;
        font-size: 12px;
        /* line-height: 5px; */
        overflow:hidden;
    }

    table {
        border-collapse: collapse;
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
        border: 1px solid #294B29;
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

    .btn-generate {
        color: #f0f0f0;
        /* background-color: #789461; #4c753a */
        background-color: #789461;
        border-radius: 5px;
        border: 1px solid #294B29;
    }
    .btn-average:hover {
        color: #f0f0f0;
        background-color: #638889;
    }

    .btn-delete{
        color: #f0f0f0;
        background-color: brown;
        border-radius: 5px;
        border: 1px solid black;
        padding: 5px 5px 5px 5px;
    }

    .btn-edit{
        color: #f0f0f0;
        background-color: #5b82a1;
        border-radius: 5px;
        border: 1px solid black;
        padding: 5px 5px 5px 5px;
    }

    a:link { text-decoration: none; }
    a:visited { text-decoration: none; }
    a:hover { text-decoration: none; }
    a:active { text-decoration: none; }
    
</style>



</div>

<div class="container">

<div class="row mt-5 ms-5">
    <div class="col-md-12 az-content-label text-center">
        <?php 
            if($this->session->flashdata('pesan')){ ?>
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('pesan'); ?>
                </div>
            <?php
            }elseif($this->session->flashdata('pesan_success')){ ?>
                <div class="alert alert-success" role="alert">
                    <?= $this->session->flashdata('pesan_success'); ?>
                </div>
            <?php
            }
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

</form>

    <?= form_open_multipart($url); ?>

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
            <label for="nama_program">Jabatan / Job Level</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="jabatan" value="<?= $jabatan. ' / ' .$level_karyawan; ?>" readonly>
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
            <input class="form-control" type="text" name="radius_perjalanan" placeholder="Contoh : 100 KM" />
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="nama_program">Attachment Map</label> 
        </div>
        <div class="col-md-5">
            <input type="file" class="form-control" id="files" name="attachment_radius_perjalanan">
        </div>
    </div>

    <hr>

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
            <button type="submit" class="btn btn-generate">Simpan dan Lanjut ke Pengisian Aktivitas</button>
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
            <!-- <table id="workspace" style="width: 100%;"> -->
            <table id="example" class="display" style="overflow-x: scroll;">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 150px;">Detail RPD</th>
                        <!-- <th class="text-center">Atasan 1</th>
                        <th class="text-center">Atasan 2</th> -->
                        <th class="text-center">Status</th>
                        <th class="text-center">Pelaksana</th>
                        <th class="text-center">Periode</th>
                        <th class="text-center">Total Biaya</th>
                        <th class="text-center" style="width: 200px;">Maksud RPD</th>
                        <th class="text-center">realisasi | akomodasi</th>
                        <th class="text-center">export</th>
                        <th class="text-center">#</th>
                        <th class="text-center">createdAt</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_pengajuan->result() as $a) : ?>
                    <tr>
                        <td align="center">
                            <?php 
                                if ($a->no_rpd) { ?>
                                    <a href="<?= base_url().'management_rpd/aktivitas/'.$a->signature ?>" class="btn btn-generate btn-sm"><?= $a->no_rpd; ?></a>
                                <?php
                                }else{ ?>
                                <div class="btn-group">
                                    <a href="<?= base_url().'management_rpd/aktivitas/'.$a->signature ?>" class="btn btn-pending btn-sm">Belum di ajukan</a>
                                    <!-- <a href="<?= base_url().'management_rpd/pengajuan_delete_soft/'.$a->signature ?>" class="btn btn-delete btn-sm" onclick="return confirm('Yakin menghapus row ini ?')">x</a> -->
                                </div>
                                <?php
                                }
                            ?>
                        </td>
                        
                        <!-- <td><?= $a->nama_status ?></td> -->

                        <!-- <td><?= $a->verifikasi1_name; ?></td>
                        <td><?= $a->verifikasi2_name; ?></td> -->
                        <td><?= $a->nama_status; ?></td>
                        <td><?= $a->pelaksana; ?></td>
                        <td><?= $a->tanggal_mulai.' - '.$a->tanggal_akhir; ?></td>
                        <!-- <td><?= $a->tempat_tiba.' at '.$a->tanggal_tiba; ?></td> -->
                        <td><strong><?= number_format($a->total_biaya) ?></strong></td>
                        <td><?= $a->maksud_perjalanan_dinas; ?></td>
                        
                        <td>
                            <div class="btn-group">
                                <?php 
                                    if ($a->status_realisasi == 1) {
                                        $params_realisasi = "realisasi";
                                        ?>
                                    <a href="<?= base_url().'management_rpd/realisasi/'.$a->signature ?>" class="btn btn-pending btn-sm" target="_blank"><?= $params_realisasi ?></a>
                                    <?php
                                    }else{
                                        $params_realisasi = "realisasi";
                                        ?>
                                    <a href="<?= base_url().'management_rpd/realisasi/'.$a->signature ?>" class="btn btn-pending btn-sm" target="_blank"><?= $params_realisasi ?></a>
                                    <?php
                                    }
                                ?>
                                <a href="<?= base_url().'management_rpd/input_akomodasi/'.$a->signature ?>" class="btn btn-generate btn-sm" target="_blank">akomodasi</a>
                            
                            </div>
                            
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="<?= base_url().'management_rpd/generate_pdf/'.$a->signature ?>" class="btn btn-generate btn-sm" target="_blank">pdf</a>
                                <a href="<?= base_url().'management_rpd/generate_excel/'.$a->signature ?>" class="btn btn-pending btn-sm" target="_blank">excel</a>
                            </div>
                        </td>
                        <td align="center">
                            <div class="col-md-12 d-flex justify-content-center">
                                <div class="col-12 input-group">
                                    <a href="<?= base_url('management_rpd/pengajuan_delete_soft/'.$a->signature) ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: white"></i> Delete</a>
                                </div>
                                <!-- <div class="col-6 input-group">
                                    <a href="<?= base_url('management_event/pelaporan_edit/'.$a->signature) ?>" class="btn-edit"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                                </div> -->
                            </div>
                        </td>
                        <td>
                            <?= $a->created_at ?>
                        </td>
                       
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
        "pageLength": 7,
        "ordering": true,
        "order": [9, 'desc'],
        "aLengthMenu": [
            [10, 20, 50, -1],
            [10, 20, 50, "All"]
        ],
        "fixedHeader": {
            header: true,
            footer: true
        },
        scrollX: true
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
</s>

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

