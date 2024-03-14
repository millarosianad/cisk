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
        font-size: 13px;
    }
    th{
        font-size: 14px; 
    }
    .accordion {
        cursor: pointer;
        padding: 1px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.2s;
        /* border: 2px solid;
        border-radius: 25px; */
        border-top: 5px solid darkslategray;
        border-bottom: 5px solid darkslategray;
        border-left: 5px solid darkslategray;
        border-right: 5px solid darkslategray;
        border-radius: 14px;
        margin-top: 1rem;
        border-top: 1em solid darkslategray;

    }
</style>

</div>




<div class="container mt-5">

    <?= form_open_multipart($url); ?>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="catatan">Catatan</label>
        </div>
        <div class="col-md-4">
            <textarea name="catatan_principal_area" id="catatan_principal_area" cols="30" rows="5" class="form-control"></textarea>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="catatan">Upload File Pendukung (opsional)</label>
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control" id="file" name="file">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="status_approval_principal_area">Pilih Status Verifikasi</label>
        </div>
        <div class="col-md-4">
            <select name="status_approval_principal_area" class="form-control" id="status_approval_principal_area" required>
                <option value="">-- Pilih Status Approval --</option>
                <option value="1">Approve</option>
                <option value="0">Reject</option>
            </select>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-2">
            <label for="customerid"></label>
        </div>
        <div class="col-md-4">
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <input type="hidden" name="supp" value="<?= $supp ?>">
            <?php 
                if ($status == 4) { ?>
                    <button type="submit" class="btn btn-dark" disabled>data anda sudah masuk</button>
                <?php
                }else{
                    if ($this->session->userdata('id') == '297' || $this->session->userdata('id') == '444') { ?>
                        <input type="submit" class="btn btn-info" value="Save Data">
                    <?php 
                    }
                
                } ?>

                <a href="<?= base_url().'management_inventory/dashboard' ?>" class="btn btn-dark">Back to dashboard</a>
                
        </div>
    </div>

    <?= form_close();?>

    <hr><br>

<script>
      $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 100,
            // "ordering": false,
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

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/kodeprod') ?>',
        data: 'supp=<?= $this->uri->segment('4') ?>',
        success: function(hasil_kodeprod) {
            $("select[name = kodeprod]").html(hasil_kodeprod);
        }
    });

</script>