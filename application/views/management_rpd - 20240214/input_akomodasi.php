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
        background-color: #eee;
        color: #444;
        cursor: pointer;
        padding: 1px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.4s;
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

    <div class="row mb-4">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <?= form_open_multipart($url);?>

    <!-- <?php 
    $i = 1; 
    foreach ($get_aktivitas->result() as $a) : 
    ?> -->

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="status_approval">Radius Perjalanan</label>
            </div>
            <div class="col-md-4">
                <label for=""><?= $radius_perjalanan ?></label>
                <!-- <input type="text" class="form-control" name="aktivitas" value="<?= $a->aktivitas ?>"> -->
            </div>
        </div>
        <div class="row mt-2 mb-5">
            <div class="col-md-2">
                <label for="keterangan_realisasi">Attachment</label>
            </div>
            <div class="col-md-4">
            
                <input type="file" class="form-control" id="files" name="attachment_akomodasi" required>
            
            </div>
        </div>
        <hr>
    <!-- <?php $i = $i+1;
    endforeach; ?>    -->


    <input type="hidden" name="signature_pengajuan" value="<?= $signature_pengajuan ?>">
    <input type="submit" class="btn btn-submit" value="Save Akomodasi">
    <a href="<?= base_url().'management_rpd/pengajuan' ?>" class="btn btn-pending">back to pengajuan RPD</a>

    <?= form_close();?>
    
    <br>

</div>


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