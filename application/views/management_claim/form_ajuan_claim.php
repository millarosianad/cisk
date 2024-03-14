<style>
    td{
        font-size: 11px;
    }
    th{
        font-size: 12px; 
    }

    .btn-submit {
        color: #f0f0f0;
        background-color: #383838;
    }

    .btn-pendingmpm {
        color: #f0f0f0;
        background-color: #2D3250;
    }

    .btn-pendingdp {
        color: #f0f0f0;
        background-color: #7077A1;
    }

</style>

<?php
foreach ($site_code->result() as $a) {
    $site_code      = $a->site_code;
    $nama_comp      = $a->nama_comp;
    $branch_name    = $a->branch_name;
    $site[$a->site_code] = $a->branch_name.' - '.$a->site_code;
}
?>

</div>

<div class="container">
    
<?php echo form_open_multipart($url); ?>

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

<div class="row mt-2">
    <div class="col-md-7">
        Summary data
      <hr class="batas2">
    </div>
    <div class="col-md-4">
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <label for="tanggal_terima_barang">Status</label>
    </div>
    <div class="col-md-4">
        <label class="form-control" readonly>
            <?php
                if($nama_status){
                    echo $nama_status;
                }else{ ?>
                    belum ada. Silahkan ajukan claim terlebih dahulu
                <?php
                }
            ?>
        </label>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-3">
        <label for="tanggal_terima_barang">Nomor Surat</label>
    </div>
    <div class="col-md-4">
        <label class="form-control" readonly><?= $nomor_surat ?></label>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-3">
        <label for="tanggal_terima_barang">Nama Program</label>
    </div>
    <div class="col-md-4">
        <label class="form-control" readonly><?= $nama_program ?></label>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-3">
        <label for="tanggal_terima_barang">Branch | SubBranch | SiteCode</label>
    </div>
    <div class="col-md-4 d-flex flex-row">
        <input type="text" class="form-control" name="branch_name" value="<?= $branch_name ?>" readonly>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-3">
        
    </div>
    <div class="col-md-4 d-flex flex-row">
        <input type="text" class="form-control" name="nama_comp" value="<?= $nama_comp ?>" readonly>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-3">
        
    </div>
    <div class="col-md-4 d-flex flex-row">
        <input type="text" class="form-control" name="site_code" value="<?= $site_code ?>" readonly>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-7">
        <hr class="batas2">
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-7">
        (*) Isi data dibawah ini :
      <hr class="batas2">
    </div>
    <div class="col-md-4">
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <label for="tanggal_terima_barang">Nama Pengirim</label>
    </div>
    <div class="col-md-4">
        <input class="form-control form-control-md" id="nama_pengirim" type="text" name="nama_pengirim" required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <label for="tanggal_terima_barang">Email Pengirim</label>
    </div>
    <div class="col-md-4">
        <input class="form-control form-control-md" id="email_pengirim" type="text" name="email_pengirim" required>
    </div>
</div>

<?php 
    if ($upload_template_program) { ?>

        <div class="row mt-4">
            <div class="col-md-7">
                <marquee behavior="scroll" direction="" scrolldelay="1"><p><strong>Silahkan kirim ajuan claim anda dengan menggunakan template di bawah ini : </strong></p></marquee>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
                <a href="<?= base_url().'assets/uploads/management_claim/'.$upload_template_program ?>" class="btn btn-dark btn-sm rounded" target="_blank"><?= $upload_template_program ?></a> <font color="brown"><b> <-- download template disamping</b></font>
            </div>
        </div>

    <?php    
    }
?>

<?php
    if ($kategori == 'bonus_barang') { ?>
        <div class="row mt-5">
            <div class="col-md-7">
                <marquee onmouseover="this.stop();" onmouseout="this.start();" behavior="scroll" direction="" scrolldelay="1"><p><strong>Khusus kategori bonus barang, DP diharuskan mengikuti standar template di bawah ini :</strong></p></marquee>                
                <a href="<?= base_url().'management_claim/export_template_bonus_barang' ?>" class="btn btn-dark btn-sm rounded mt-3" id="download">download template bonus barang.xlsx</a>
                <a href="<?= base_url().'management_claim/export_master_site/'.$site_code ?>" class="btn btn-warning btn-sm rounded mt-3" id="download">download master site MPM</a>
                <a href="<?= base_url().'management_claim/export_master_class' ?>" class="btn btn-warning btn-sm rounded mt-3" id="download">download master class MPM</a>
            </div>
        </div>
    <?php    
    }
?>
<?php
    if ($kategori == 'diskon_herbal' || $kategori == 'diskon_candy' || $kategori == 'diskon') { ?>
        <div class="row mt-5">
            <div class="col-md-12">
                <marquee onmouseover="this.stop();" onmouseout="this.start();" behavior="scroll" direction="" scrolldelay="1"><p><strong>Khusus kategori diskon_herbal, diskon_candy, diskon. Maka DP diharuskan mengikuti standar template di bawah ini :</strong></p></marquee>            
                <a href="<?= base_url().'management_claim/export_template_diskon' ?>" class="btn btn-outline-info btn-sm rounded mt-3" id="download">download template diskon.xlsx</a>
                <a href="<?= base_url().'management_claim/export_master_site/'.$site_code ?>" class="btn btn-outline-info btn-sm rounded mt-3" id="download">download master site MPM</a>
                <a href="<?= base_url().'management_claim/export_master_class' ?>" class="btn btn-outline-info btn-sm rounded mt-3" id="download">download master class MPM</a>
            </div>
        </div>
    <?php    
    }
?>

<div class="row mt-5">
    <div class="col-md-3">
        <label for="tanggal_terima_barang">Upload Excel Claim (.xlsx) sesuai template</label>
    </div>
    <div class="col-md-4">
        <input class="form-control form-control-md" id="ajuan_excel" type="file" name="ajuan_excel" required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <label for="tanggal_terima_barang">Upload Dokumen Pendukung yang sudah di ZIP (.zip)</label>
    </div>
    <div class="col-md-4">
        <input class="form-control form-control-md" id="ajuan_zip" type="file" name="ajuan_zip">
        <input type="hidden" name="signature_program" value="<?= $signature_program ?>" required>
        <input type="hidden" name="supp" value="<?= $supp ?>" required>
    </div>
</div>

<!-- <div class="row mt-2">
    <div class="col-md-3">
        <label for="tanggal_terima_barang">Apakah ini data final ?</label>
    </div>
    <div class="col-md-4">
        <select name="status_data_final" id="status_data_final" class="form-control" required>
            <option value=""> -- Pilih Salah Satu -- </option>
            <option value="1">Ya, ini data final</option>
            <option value="0">tidak, ini bukan data final</option>
        </select>
    </div>
</div> -->
<input type="hidden" name="status_data_final" value="1">

<div class="row mt-4 mb-5">
    <div class="col-md-3">
        
    </div>
    <div class="col-md-5">
        <?php
            // echo "created_at : ".$created_at;
            if ($tanggal_claim == null || $tanggal_claim == '0000-00-00 00:00:00') { 
                ?>
                <?php
                // echo "selisih_duedate : ".$selisih_duedate; 
                    if ($selisih_duedate >= 0) { ?>
                        <button type="submit" class="btn btn-submit" id="btnKirim" onclick="return button()">Proses</button>
                    <?php
                    }else{ ?>         
                        <button type="submit" class="btn btn-submit" disabled>Anda sudah melewati deadline</button>
                    <?php
                    }
                    
                ?>
                <?php 
            }else{ ?>
                <button type="submit" class="btn btn-dark" disabled>data anda sudah masuk</button>              
            <?php }
        ?>
        <button class="btn btn-info" id="btnLoading" type="button" disabled>
        ... Sedang verifikasi data. Mohon menunggu ...
        </button>
        <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="btn btn-secondary btn-sm" id="btnBack">Back</a>
    </div>
</div>

</form>


</div>
</div>

<script>
    function button()
    {
        var nama_pengirim   = document.getElementById('nama_pengirim').value;
        var email_pengirim  = document.getElementById('email_pengirim').value;
        var ajuan_excel     = document.getElementById('ajuan_excel').value;
        if (nama_pengirim) {
            if (email_pengirim) {
                if (ajuan_excel) {
                    $("#btnKirim").hide();
                    $("#btnBack").hide();
                    $("#btnLoading").show();
                }   
            }
        }
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>