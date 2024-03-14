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
            <label for="status">Status</label>
        </div>
        <div class="col-md-4">
            <select name="status" id="status" class="form-control" required>
                <option value=""> -- Pilih Status -- </option>
                <option value="1"> PENDING DP </option>
                <option value="2"> PENDING MPM </option>
                <option value="3"> REJECT MPM </option>
                <option value="4"> PENDING PRINCIPAL </option>
                <option value="5"> REJECT PRINCIPAL </option>
                <option value="6"> APPROVE </option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <label for="keterangan">Keterangan / Note</label>
        </div>
        <div class="col-md-4">
            <textarea name="keterangan" cols="30" rows="10" id="keterangan" class="form-control" required></textarea>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <label for="file">Attach File</label>
        </div>
        <div class="col-md-4">
            <input class="form-control form-control-md" id="ajuan_excel" type="file" name="file">
        </div>
    </div>

    <div class="row mt-4 mb-5">
        <div class="col-md-3">
            
        </div>
        <div class="col-md-3">
            <input type="hidden" name="signature_program" value="<?= $signature_program ?>">
            <input type="hidden" name="signature_ajuan" value="<?= $signature_ajuan ?>">
            <button type="submit" class="btn btn-submit" id="btnKirim" onclick="return button()">Proses</button>
            <button class="btn btn-info" id="btnLoading" type="button" disabled>
            ... Sedang update data. Mohon menunggu ...
            </button>
            <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="btn btn-dark btn-sm" id="btnBack">Back</a>
        </div>
    </div>
</form>


</div>
</div>

<script>
    function button()
    {
        var status   = document.getElementById('status').value;
        var keterangan  = document.getElementById('keterangan').value;
        if (status) {
            if (keterangan) {
                $("#btnKirim").hide();
                $("#btnBack").hide();
                $("#btnLoading").show();  
            }
        }
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>
