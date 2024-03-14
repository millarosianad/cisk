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

    <div class="row mt-2 ms-5">
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
            <label for="status_approval">Tanggal Pemusnahan</label>
        </div>
        <div class="col-md-4">
            <input type="date" class="form-control" name="tanggal_pemusnahan" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <label for="nama_pemusnahan">Nama PIC Pemusnahan</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="nama_pemusnahan" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <label for="file_pemusnahan">File Berita Acara Pemusnahan</label>
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control" id="file_pemusnahan" name="file_pemusnahan" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <label for="foto_pemusnahan_1">File Foto Pemusnahan 1</label>
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control" id="foto_pemusnahan_1" name="foto_pemusnahan_1" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <label for="foto_pemusnahan_2">File Foto Pemusnahan 2</label>
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control" id="foto_pemusnahan_2" name="foto_pemusnahan_2" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <label for="foto_pemusnahan_2">File Video</label>
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control" id="video" name="video" required>
        </div>
    </div>


    <div class="row mt-4">
        <div class="col-md-3">
            <label for="customerid"></label>
        </div>
        <div class="col-md-4">
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <input type="hidden" name="supp" value="<?= $supp ?>">
            <?php 
                if ($pemusnahan_at) { ?>
                    <button type="submit" class="btn btn-dark" disabled>data anda sudah masuk</button>
                <?php
                }else{ ?>
                    <?php 
                        if (substr($site_code,0,3) == $this->session->userdata('username') || $this->session->userdata('id') == 588) { ?>
                            <input type="submit" class="btn btn-info" value="Save Data">
                        <?php
                        }
                    ?>
                <?php
                } ?>
            <a href="<?= base_url().'management_inventory/dashboard' ?>" class="btn btn-dark">Back</a>
                
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