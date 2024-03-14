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

    .btn-delete {
        color: #f0f0f0;
        background-color: #8b173e;
        border-radius: 5px;
    }

    .btn-delete:hover {
        color: #f0f0f0;
        background-color: #a15b73;
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
    
<?php echo form_open_multipart($url); ?>

<div class="row">
    <div class="col-md-6">

        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>

        <div class="col-md-12 mt-4">     
            <label for="aktivitas" class="form-label">Aktivitas</label>
            <div class="col-md-10 d-flex flex-row">
                <input class="form-control form-control-md" id="aktivitas" type="text" name="aktivitas" required>
            </div>
        </div>

        <div class="col-md-12 mt-2">     
            <label for="tanggal_aktivitas" class="form-label">Tanggal Aktivitas</label>
            <div class="col-md-10 d-flex flex-row">
                <input class="form-control" type="datetime-local" name="tanggal_aktivitas" id="tanggal_aktivitas" required />
            </div>
        </div>

        <div class="col-md-12 mt-2">     
            <label for="detail_aktivitas" class="form-label">Detail Aktivitas</label>
            <div class="col-md-10 d-flex flex-row">
                <textarea name="detail_aktivitas" id="detail_aktivitas" cols="30" rows="5" class="form-control" required></textarea>
            </div>
        </div>

        <div class="col-md-12 mt-4">     
            <label for="biaya" class="form-label">Biaya yang dibutuhkan (Jika tidak ada, isi dengan angka 0)</label>
            <div class="col-md-10 d-flex flex-row">
                <input class="form-control form-control-md" id="biaya" type="number" name="biaya" placeholder="Contoh : 100000" required>
            </div>
        </div>

        <div class="col-md-12 mt-4">     
            <label for="biaya" class="form-label">Apakah akan di claim ?</label>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status_claim" id="status_claim1" value="1" required>
                    <label class="form-check-label" for="status_claim1">
                        Ya, perlu di claim
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status_claim" id="status_claim2" value="0" required>
                    <label class="form-check-label" for="status_claim2">
                        Tidak perlu
                    </label>
                </div>

        </div>

        <div class="col-md-12 mt-2">     
            <label for="keterangan" class="form-label">Input Akomodasi dan Transportasi</label>
            <div class="col-md-10 d-flex flex-row">
                <textarea name="keterangan" id="keterangan" cols="30" rows="2" class="form-control" placeholder="Untuk akomodasi dan transportasi bisa diinput disini" required></textarea>
            </div>
        </div>

        <div class="col-md-12 mt-2 mb-5">     
            <label for="keterangan" class="form-label"></label>
            <div class="col-md-10">
                <input class="form-control form-control-md" id="aktivitas" type="hidden" name="id_pengajuan" value="<?= $id_pengajuan ?>" required>
                <input class="form-control form-control-md" id="signature_pengajuan" type="hidden" name="signature_pengajuan" value="<?= $signature_pengajuan ?>" required>


                <?php 
                    if ($status == null) { ?>
                        <?php echo form_open_multipart($url_verifikasi); ?>
                            <input type="submit" value="Save Aktivitas" class="btn btn-submit">
                        </form>
                    <?php 
                    }else{ ?>
                        <button type="submit" class="btn btn-dark" disabled>permintaan sudah diajukan</button>
                    <?php
                    }
                ?>

                <a href="<?= base_url().'management_rpd/pengajuan' ?>" class="btn btn-pending">back to pengajuan RPD</a>
            
            </div>
        </div>
    </div>

    
    

    <div class="col-md-6 border border-dark rounded-10 shadow p-3">

        <div class="col-md-12 mt-4 text-center">
            <h4>Form Perjalanan Dinas</h4>
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
                <label for="kategori" class="form-label">Total Biaya</label> 
            </div>
            <div class="col-md-8">
                <font color="black" size="5px">Rp. <?= number_format($total_biaya) ?></font>
            </div>
        </div>

        <hr>

        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="kategori" class="form-label">Jumlah Verifikasi</label>
            </div>
            <div class="col-md-8">
                <?= $jumlah_verifikasi ?>
            </div>
        </div>       

        <?php 

        if ($jumlah_verifikasi == 1) { ?>

        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="kategori" class="form-label">Verifikasi 1</label>
            </div>
            <div class="col-md-8">
                <?php 
                    if ($verifikasi1_at) {
                        echo $verifikasi1_name.' at '.$verifikasi1_at. ' by '.$username_verifikasi1. ' - '.$verifikasi1_keterangan;
                    }
                ?>
            </div>
        </div>

        <div class="col-md-12 mt-5">     
            <div class="col-md-12 text-center">
                <h5>PIC Verifikasi</h5>
            </div>
        </div>

        <div class="col-md-12 mt-5 d-flex justify-content-center">     
            <div class="col-md-6 text-center">

                <?php 
                    if ($this->session->userdata('id') === $userid_verifikasi1) { ?>
                        <a href="<?= base_url().'management_rpd/verifikasi1/'.$signature_pengajuan ?>" class="btn btn-delete btn-sm btn-rounded" target="_blank">verifikasi click disini</a>
                    <?php
                    }
                    
                    if ($verifikasi1_ttd) { ?>
                        <img src="<?= base_url().'assets/uploads/signature/'.$verifikasi1_ttd ?>" alt="ttd1" width="100px">
                    <?php
                    }
                    ?>
            </div>
        </div>

        <div class="col-md-12 mt-5 d-flex justify-content-center">     
            <div class="col-md-6 text-center">
                <p>
                    <?= $username_verifikasi1 ?>
                    (verifikasi 1)
                </p>
            </div>
        </div>        
                    
        <?php
        }elseif($jumlah_verifikasi == 2){ ?>


        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="kategori" class="form-label">Verifikasi 1</label>
            </div>
            <div class="col-md-8">
                <?php 
                    if ($verifikasi1_at) {
                        echo $verifikasi1_name.' at '.$verifikasi1_at. ' by '.$username_verifikasi1. ' - '.$verifikasi1_keterangan;
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
                        echo $verifikasi2_name.' at '.$verifikasi2_at. ' by '.$username_verifikasi2. ' - '.$verifikasi2_keterangan;
                    }
                ?>
            </div>
        </div>

        <div class="col-md-12 mt-5">     
            <div class="col-md-12 text-center">
                <h5>PIC Verifikasi</h5>
            </div>
        </div>

        <div class="col-md-12 mt-5 d-flex justify-content-center">     
            <div class="col-md-6 text-center">

                <?php 
                    if ($this->session->userdata('id') === $userid_verifikasi1) { ?>
                        <a href="<?= base_url().'management_rpd/verifikasi1/'.$signature_pengajuan ?>" class="btn btn-info btn-sm btn-rounded" target="_blank">verifikasi click disini</a>
                    <?php
                    }
                    
                    if ($verifikasi1_ttd) { ?>
                        <img src="<?= base_url().'assets/uploads/signature/'.$verifikasi1_ttd ?>" alt="ttd1" width="100px">
                    <?php
                    }
                ?>
            </div>

            <div class="col-md-6 text-center">

                <?php 
                    if ($this->session->userdata('id') === $userid_verifikasi2) { ?>
                        <a href="<?= base_url().'management_rpd/verifikasi2/'.$signature_pengajuan ?>" class="btn btn-info btn-sm btn-rounded" target="_blank">verifikasi click disini</a>
                    <?php
                    }
                    
                    if ($verifikasi2_ttd) { ?>
                        <img src="<?= base_url().'assets/uploads/signature/'.$verifikasi2_ttd ?>" alt="ttd1" width="100px">
                    <?php
                    }
                ?>
            </div>
        </div>

        <div class="col-md-12 mt-5 d-flex justify-content-center">     
            <div class="col-md-6 text-center">
                <p>
                    <?= $username_verifikasi1 ?>
                    (verifikasi 1)
                </p>
            </div>
            <div class="col-md-6 text-center">
                <p>
                    <?= $username_verifikasi2 ?>
                    (verifikasi 2)
                </p>
            </div>
        </div> 



        <?php
        }

        ?>


    </div>

</div>

</form>

<hr>

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

<div class="row mt-5">
    <div class="col-md-12 az-content-label text-center">
            Detail Aktivitas
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!-- <table id="example" width="100%" class="display" style="display: inline-block; overflow-y: scroll"> -->
        <table id="example" width="100%" class="display" style="overflow-x: scroll;">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Aktivitas</th>
                    <th>Detail</th>
                    <th>Biaya</th>
                    <th>Claim</th>
                    <th>Keterangan</th>
                    <th>#</th>
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
                    <td>
                        <?php 
                            if ($status == null) { ?>
                                <a href="<?= base_url().'management_rpd/aktivitas_delete_soft/'.$a->signature.'/'.$signature_pengajuan ?>" class="btn btn-delete btn-sm" onclick="return confirm('Yakin menghapus row ini ?')">x</a>
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
    </div>
</div>

<hr>

<div class="row mb-5">
    <!-- <div class="row"> -->
        <div class="col-md-12 text-center">
            <p><strong>Isi aktivitas. Jika sudah ok, klik Button "Meminta Persetujuan RPD ke Atasan" :</strong></p>
        </div>
    <!-- </div> -->



    <div class="col-md-12 d-flex justify-content-center">
        
        <div class="form-inline">
            <div class="col-sm-9">

                <?php 
                    if ($status == null) { ?>
                        <?php echo form_open_multipart($url_verifikasi); ?>
                            <input type="hidden" name="signature_pengajuan" value="<?= $signature_pengajuan ?>">
                            <input type="submit" value="Meminta Persetujuan RPD ke Atasan" class="btn btn-delete rounded">
                        </form>
                    <?php 
                    }else{ ?>
                        <button type="submit" class="btn btn-pending" disabled>permintaan sudah diajukan</button>
                    <?php
                    }
                ?>               
            </div>
        </div>
    </div>
</div>

</div>

<!-- <script>
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
</script> -->

<script>
    $(document).ready(function () {
        $('#example').DataTable(
            {
                scrollX: true
            }
        );
    });
</script>