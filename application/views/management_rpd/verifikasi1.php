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

        <div class="col-md-12 mt-2 mb-4">     
            <label for="keterangan" class="form-label">Keterangan / alasan</label>
            <div class="col-md-10 d-flex flex-row">
                <textarea name="keterangan_verifikasi" id="keterangan" cols="30" rows="5" class="form-control" required></textarea>
            </div>
        </div>

        <hr>

        <!-- <div class="col-md-12 mt-2 mb-4">     
            <label for="keterangan" class="form-label">Pilih salah satu metode verifikasi di bawah ini (ttd / password website)</label>
        </div> -->
        <input type="hidden" name="signature" value="<?= $signature_pengajuan ?>">
        <input type="hidden" name="id_pengajuan" value="<?= $id_pengajuan ?>">
        <input type="hidden" name="userid_verifikasi1" value="<?= $userid_verifikasi1 ?>">

        <div class="col-md-12 mt-2">     
            <div class="col-md-9">                
            <!-- <label for="keterangan" class="form-label text-center">tanda tangan</label> -->
            <label for="keterangan" class="form-label">tanda tangan digital</label>
            </div>
            <div class="col-md-12">
                <div id="sig"></div>                
                <!-- <textarea name="signed" id="signature64" style="display: none;"></textarea> -->
                
            </div>
        </div>

        <textarea name="signed" id="signature64" cols="35" rows="5" style="display: none;"></textarea>

        <button id="clear" class="btn btn-dark btn-sm mt-2" type="reset">Clear TTD</button>

        <div class="col-md-12 mt-4 mb-4">     
            <div class="col-md-9">                
                <label for="keterangan" class="form-label">password web</label>
            </div>
            <div class="col-md-9">
                <input type="password" name="password_login" class="form-control" id="password_login" placeholder="masukkan password login web anda" required>
            </div>
        </div>

        <div class="col-md-12 mt-2 mb-5">     
            <label for="keterangan" class="form-label"></label>
            <div class="col-md-12 d-flex flex-row">
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
                        <a href="<?= base_url().'management_rpd/verifikasi1/'.$signature_pengajuan ?>" class="btn btn-info btn-sm btn-rounded" target="_blank">verifikasi</a>
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
                        <a href="<?= base_url().'management_rpd/verifikasi1/'.$signature_pengajuan ?>" class="btn btn-info btn-sm btn-rounded" target="_blank">verifikasi</a>
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
                        <a href="<?= base_url().'management_rpd/verifikasi2/'.$signature_pengajuan ?>" class="btn btn-info btn-sm btn-rounded" target="_blank">verifikasi</a>
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


<script type="text/javascript">
    var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
    $('#clear').click(function (e){
    //   e.preventDefault();
      sig.signature('clear');
      $("#signature64").val('');
    });
  </script>

    <script
      src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
      integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
      integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
      crossorigin="anonymous"
    ></script>