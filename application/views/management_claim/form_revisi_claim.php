<style>
    td{
        font-size: 11px;
    }
    th{
        font-size: 12px; 
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
    <div class="col-md-7 az-content-label text-center">
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
        (*) Isi data dibawah ini :
      <hr class="batas2">
    </div>
    <div class="col-md-4">
    </div>
</div>

<div class="row mt-2">
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

        <div class="row mt-3">
            <div class="col-md-7">
                <hr class="batas2">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-7">
                <p>Silahkan kirim ajuan claim anda dengan menggunakan template di bawah ini : </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
                <a href="" class="btn btn-dark btn-sm rounded"><?= $upload_template_program ?></a>
            </div>
        </div>

    <?php    
    }
?>

<div class="row mt-5">
    <div class="col-md-3">
        <label for="tanggal_terima_barang">Upload Excel Claim (.xlsx)</label>
    </div>
    <div class="col-md-4">
        <input class="form-control form-control-md" id="revisi_excel" type="file" name="revisi_excel" required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <label for="tanggal_terima_barang">Upload Dokumen Pendukung yang sudah di ZIP (.zip)</label>
    </div>
    <div class="col-md-4">
        <input class="form-control form-control-md" id="revisi_zip" type="file" name="revisi_zip">
        <input type="hidden" name="signature_program" value="<?= $signature_program ?>" required>
        <input type="hidden" name="signature_ajuan" value="<?= $signature_ajuan ?>" required>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <label for="tanggal_terima_barang">Keterangan</label>
    </div>
    <div class="col-md-4">
        <textarea name="keterangan" cols="30" rows="3" class="form-control" required></textarea>
    </div>
</div>

<div class="row mt-4 mb-5">
    <div class="col-md-3">
        
    </div>
    <div class="col-md-3">
        <?php
            // echo "created_at : ".$created_at;
            if ($revisi_created_at == null) { ?>
                <button type="submit" class="btn btn-info">Proses</button>
                <?php 
            }else{ ?>
                <button type="submit" class="btn btn-dark" disabled>data anda sudah masuk</button>             
            <?php }
        ?>
        <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="btn btn-dark btn-sm">Back</a>
    </div>
</div>

</form>


</div>
</div>
