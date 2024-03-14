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



<div class="container mt-1">

    <?= form_open_multipart($url); ?>

    <div class="row mt-2">
        <div class="col-md-12">
            <table id="example" class="display">
                <thead>
                    <tr>
                        <th class="text-center col-1" style="background-color: darkslategray;" >
                            <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                            value="click all" onclick="click_all_request()">
                        </th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">No Retur</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Tanggal</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Site</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Total Qty</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Status</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_pengajuan->result() as $a) : ?>
                    <tr>                                          
                        <td>
                            <center>
                            <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                            </center>
                        </td>
                        <td><a href="<?= base_url().'management_inventory/generate_pdf/'.$a->signature.'/'.$a->supp ?>" class="btn btn-warning btn-sm" target="_blank"><?= ($a->no_pengajuan) ? $a->no_pengajuan : 'NULL'; ?></a></td>
                        <td><?= $a->tanggal_pengajuan ?></td>
                        <td><?= $a->nama_comp ?></td>
                        <td><?= $a->total_qty_approval ?></td>
                        <td><?= $a->nama_status ?></td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>         

        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-md-2">
            <label for="status_principal_ho">Approve / Reject ?</label>
        </div>
        <div class="col-md-4">
            <select name="status_principal_ho" class="form-control" id="status_principal_ho" required>
                <option value="">Pilih</option>
                <option value="14">Approve</option>
                <option value="15">Reject</option>
            </select> 
        </div>
    </div>

    <div class="row mt-2" hidden id="mydiv">
        <div class="col-md-2">
            <label for="status">Pilih Action</label>
        </div>
        <div class="col-md-4">
            <select name="status" class="form-control" id="status" required>
                <option value="">Pilih</option>
                <option value="5">Kirim Barang</option>
                <option value="7">Pemusnahan</option>
            </select> 
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="catatan_principal_ho">Catatan</label>
        </div>
        <div class="col-md-4">
            <textarea name="catatan_principal_ho" id="catatan_principal_ho" cols="30" rows="5" class="form-control"></textarea>
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
        <div class="col-md-2">
            <label for="customerid"></label>
        </div>
        <div class="col-md-4">

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

<script>
    $("select[name = status_principal_ho]").on("change", function() {
        var status_principal_ho_terpilih = document.getElementById('status_principal_ho').value;
        let element = document.getElementById("mydiv");
        console.log(status_principal_ho_terpilih);
        if (status_principal_ho_terpilih == '14') { //jika appprove tampil action kirim barang atau pemusnahan
            document.getElementById("file").required = true;
            element.removeAttribute("hidden");
        }else{
            element.setAttribute("hidden", "hidden");
            document.getElementById('status').removeAttribute('required');
        }
    });
</script>