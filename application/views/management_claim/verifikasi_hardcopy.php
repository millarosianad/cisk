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
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-submit:hover {
        color: #f0f0f0;
        background-color: #365486;
    }

    .btn-hardcopy {
        color: #f0f0f0;
        background-color: #37B5B6;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-hardcopy:hover {
        color: black;
    }

    .btn-null {
        color: black;
        background-color: #F9EFDB;
        border-radius: 10px;
        border: 2px solid black;
    }

</style>


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
        <div class="col-md-3">
            <label for="status_hardcopy">Status Hardcopy</label>
        </div>

        <?php 
            // echo "status_hardcopy : ".$status_hardcopy;
            // die;
        ?>
        
        <div class="col-md-4">
            <select name="status_hardcopy" id="status_hardcopy" class="form-control" required>
                <option value=""> -- Pilih Status -- </option>
                <option value="1" <?= $status_hardcopy == 2 ? 'selected' : '' ?>> PENDING DP (Hardcopy) </option>
                <option value="2" <?= $status_hardcopy == 1 ? 'selected' : '' ?>> PENDING MPM (Hardcopy) </option>
                <option value="3" <?= $status_hardcopy == 3 ? 'selected' : '' ?>> REJECT MPM (Hardcopy) </option>
                <option value="4" <?= $status_hardcopy == 4 ? 'selected' : '' ?>> PENDING PRINCIPAL (Hardcopy) </option>
                <option value="5" <?= $status_hardcopy == 5 ? 'selected' : '' ?>> REJECT PRINCIPAL (Hardcopy) </option>
                <option value="6" <?= $status_hardcopy == 6 ? 'selected' : '' ?>> APPROVE (Hardcopy) </option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="tanggal_terima_hardcopy">Tanggal Terima Hardcopy dari DP</label>
        </div>
        <div class="col-md-4">
            <input type="date" class="form-control" name="tanggal_terima_hardcopy" id="tanggal_terima_hardcopy" value="<?= $tanggal_terima_hardcopy; ?>">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="file_tanda_terima_hardcopy_ke_principal">File Tanda Terima Hardcopy ke Principal</label>
        </div>
        <div class="col-md-4">
             <?php 
                $file = './assets/uploads/management_claim/'.$file_tanda_terima_hardcopy_ke_principal; // 'images/'.$file (physical path)
                if (file_exists($file)) { ?>
                    <a href="<?= base_url() ?>assets/uploads/management_claim/<?= $file_tanda_terima_hardcopy_ke_principal ?>" class="btn btn-outline-dark btn-sm" target="_blank">
                        <?= $file_tanda_terima_hardcopy_ke_principal ?>
                    </a>  
                <?php
                } else { ?>
                    <a href="<?= base_url() ?>assets/uploads/management_claim/<?= $file_tanda_terima_hardcopy_ke_principal ?>" class="btn btn-outline-dark btn-sm" target="_blank">
                        click here
                    </a>  
                <?php 
                }
            ?>            
            <br>
            <input type="hidden" class="form-control" name="file_tanda_terima_hardcopy_ke_principal_old" value="<?= $file_tanda_terima_hardcopy_ke_principal; ?>">
            <div class="mt-2">
                <input type="file" class="form-control" id="file_tanda_terima_hardcopy_ke_principal" name="file_tanda_terima_hardcopy_ke_principal">
            </div>            
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="tanda_terima_hardcopy_ke_principal_nama">Nama Staff Principal Penerima Hardcopy</label>
        </div>
        <div class="col-md-4">
            <input class="form-control form-control-md" type="text" id="tanda_terima_hardcopy_ke_principal_nama" name="tanda_terima_hardcopy_ke_principal_nama" value="<?= $tanda_terima_hardcopy_ke_principal_nama; ?>">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label for="tanggal_tanda_terima_hardcopy_ke_principal">Tanggal Penyerahan Hardcopy ke Principal</label>
        </div>
        <div class="col-md-4">
            <input type="date" class="form-control" name="tanggal_tanda_terima_hardcopy_ke_principal" id="tanggal_tanda_terima_hardcopy_ke_principal" value="<?= $tanggal_tanda_terima_hardcopy_ke_principal; ?>">
        </div>
    </div>

    <div class="row mt-4 mb-5">
        <div class="col-md-3">
            
        </div>
        <div class="col-md-3">
            <input type="hidden" name="signature_program" value="<?= $signature_program ?>">
            <input type="hidden" name="signature_ajuan" value="<?= $signature_ajuan ?>">
            <button type="submit" class="btn btn-submit" id="btnKirim" onclick="return button()">Update Data</button>
            <button class="btn btn-info" id="btnLoading" type="button" disabled>
            ... Sedang update data. Mohon menunggu ...
            </button>
            <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="btn btn-null" id="btnBack">Back</a>
        </div>
    </div>
</form>


</div>
</div>

<script>
    function button()
    {
        $("#btnKirim").hide();
        $("#btnBack").hide();
        $("#btnLoading").show(); 
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>
