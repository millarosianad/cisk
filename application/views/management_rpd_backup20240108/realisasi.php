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

</style>

</div>

<div class="container">

    <div class="row mb-4">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <?= form_open($url);?>

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
                <label for="status_realisasi" class="form-label">Status Realisasi ?</label>
            </div>

            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status_realisasi[<?= $i ?>]" value="1">
                    <label class="form-check-label" >
                        Ya
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status_realisasi[<?= $i ?>]" value="0">
                    <label class="form-check-label" >
                        Tidak
                    </label>
                </div>
            </div>
        </div>

        <div class="row mt-2 mb-5">
            <div class="col-md-2">
                <label for="keterangan_realisasi">Keterangan Realisasi</label>
            </div>
            <div class="col-md-4">
                <input type="hidden" name="signature_aktivitas[]" value="<?= $a->signature ?>">
                <textarea name="keterangan_realisasi[]" cols="30" rows="10" class="form-control" placeholder="masukkan realisasi berdasarkan aktivitas tsb"></textarea>
            </div>
        </div>
        <hr>
    <?php $i = $i+1;
    endforeach; ?>   


    <input type="hidden" name="signature_pengajuan" value="<?= $signature_pengajuan ?>">
    <input type="submit" class="btn btn-info" value="Save Realisasi">
    <a href="<?= base_url().'management_rpd/pengajuan' ?>" class="btn btn-dark">back to pengajuan RPD</a>

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