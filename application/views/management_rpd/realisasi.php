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
        border: 2px solid black;
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

    .btn-generate {
        color: #f0f0f0;
        background-color: #638889;
        border-radius: 5px;
        border: 2px solid black;
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

    <?php 
    $i = 1; 
    foreach ($get_aktivitas->result() as $a) : 
    ?>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="status_approval">Aktivitas</label>
            </div>
            <div class="col-md-4">
                <label for=""><?= $a->aktivitas ?></label>
                <!-- <input type="text" class="form-control" name="aktivitas" value="<?= $a->aktivitas ?>"> -->
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="status_approval">Tanggal Aktivitas</label>
            </div>
            <div class="col-md-4">
                <label for=""><?= $a->tanggal_aktivitas ?></label>
                <!-- <input type="text" class="form-control" name="aktivitas" value="<?= $a->tanggal_aktivitas ?>"> -->
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="status_approval">Tanggal Aktivitas</label>
            </div>
            <div class="col-md-4">
                <label for=""><?= $a->detail_aktivitas ?></label>
                <!-- <textarea name="detail_aktivitas" cols="30" rows="10" class="form-control"><?= $a->detail_aktivitas ?></textarea> -->
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="status_realisasi" class="form-label">Apakah Tercapai ?</label>
            </div>

            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status_realisasi[<?= $i ?>]" value="1" <?= $a->status_realisasi == 1 ? 'checked' : '' ?>>
                    <label class="form-check-label" >
                        Ya 
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status_realisasi[<?= $i ?>]" value="0" <?= $a->status_realisasi == 0 ? 'checked' : '' ?>>
                    <label class="form-check-label" >
                        Tidak
                    </label>
                </div>
            </div>
        </div>

        <div class="row mt-2 mb-2">
            <div class="col-md-2">
                <label for="keterangan_realisasi">Keterangan Realisasi</label>
            </div>
            <div class="col-md-4">
                <input type="hidden" name="signature_aktivitas[]" value="<?= $a->signature ?>">
                <textarea name="keterangan_realisasi[]" cols="30" rows="10" class="form-control" placeholder="masukkan realisasi berdasarkan aktivitas tsb"><?= $a->keterangan_realisasi ?></textarea>
            </div>
        </div>

        <div class="row mt-2 mb-1">
            <div class="col-md-2">
                <label for="keterangan_realisasi">Attachment <p style="color:grey">(Opsional)</p></label>
            </div>
            <div class="col-md-4">
                <?php
                    if ($a->attachment_realisasi) {
                        $file = "$a->attachment_link";
                        if (file_exists($file)) {
                            echo '<a href="'.base_url("$a->attachment_link").'" class="btn btn-pdf btn-sm" download="'.$a->attachment_realisasi.'">Download '.$a->attachment_realisasi.'</a>';
                            echo '<br><br><input type="file" class="form-control" name="attachment[]">';
                        } else {
                            echo "File Tidak Ada";
                        }
                    } else {
                        echo '<input type="file" class="form-control" name="attachment[]">';
                    }
                    
                ?>
            </div>
        </div>
    <?php $i = $i+1;
    endforeach; ?>   

        <div class="row mb-5">
            <div class="col-md-2">

            </div>
            <div class="col-md-4">
                <input type="hidden" name="signature_pengajuan" value="<?= $signature_pengajuan ?>">
                <input type="submit" class="btn btn-generate" value="Save Realisasi">
                <a href="<?= base_url().'management_rpd/pengajuan' ?>" class="btn btn-pending">back to pengajuan RPD</a>
            </div>
        </div>
    <?= form_close();?>
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