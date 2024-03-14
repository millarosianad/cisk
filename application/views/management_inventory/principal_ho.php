

</div>

<div class="container mt-5">

    <?= form_open_multipart($url); ?>

    <div class="row mt-2">
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

    <!-- <div class="row mt-2" hidden id="mydiv">
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
    </div> -->

    <div class="row mt-2" hidden id="mydiv">
        <div class="col-md-2">
            <label for="status">Pilih Action</label>
        </div>
        <div class="col-md-4">
            <select name="status" id="status" class="form-control" required>
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
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <input type="hidden" name="supp" value="<?= $supp ?>">
            <!-- <?= $principal_ho_at ?> -->
            <?php 
                if ($principal_ho_at) { ?>
                    <button type="submit" class="btn btn-dark" disabled>data anda sudah masuk</button>
                <?php
                }else{ ?>
                    <?php          

                        if ($status_ho->num_rows() > 0) {
                            
                            if ($status_ho->row()->status_ho == 1) { ?>
                                <input type="submit" class="btn btn-info" value="Save Data">
                            <?php
                            }
                        }else{ 

                            if ($this->session->userdata('id') == 588 || $this->session->userdata('id') == 857) { ?>
                                <input type="submit" class="btn btn-info" value="Save Data">
                            <?php
                            }                        
                        }
                    ?>                    
                <?php
                } ?>
                
                <a href="<?= base_url().'management_inventory/dashboard' ?>" class="btn btn-dark">Back to dashboard</a>

        </div>
    </div>

    <?= form_close();?>

    <hr><br>

    <!-- <div class="row">
        <div class="col-md-12">
            <label for=""><i>gunakan link dibawah ini untuk approval beberapa ajuan sekaligus</i></label>
        </div>
        <div class="col-md-12">
            
            <?= form_open($url_group); ?>
            <input type="hidden" name="sign_principal_ho_date" value="<?= $sign_principal_ho_date ?>">
            <input type="hidden" name="userid_for_group_approval" value="<?= $this->session->userdata('id') ?>">
            <input type="hidden" name="principal_for_group_approval" value="<?= $this->session->userdata('supp') ?>">
            <input type="submit" class="btn btn-info" value="Group Approval">
            <?= form_close();?>
        </div>
    </div> -->

    <br><br>
    

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

    $("select[name = status_principal_ho]").on("change", function() {
        // var status_principal_ho_terpilih = $("option:selected", this).attr("status_principal_ho");
        var status_principal_ho_terpilih = document.getElementById('status_principal_ho').value;
        console.log(status_principal_ho_terpilih);
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('database_afiliasi/action_pengajuan_retur') ?>',
            data: {
                'status_principal_ho_terpilih': status_principal_ho_terpilih,   
                'supp': '<?= $this->uri->segment('4') ?>',   
                'signature': '<?= $this->uri->segment('3') ?>',   
            },
            success: function(hasil_action) {
                $("select[name = status]").html(hasil_action);
            }
        });

        let element = document.getElementById("mydiv");
        if (status_principal_ho_terpilih == '14') { //jika appprove tampil action kirim barang atau pemusnahan
            // document.getElementById("file").required = true;
            element.removeAttribute("hidden");
        }else{
            element.setAttribute("hidden", "hidden");
            document.getElementById('status').removeAttribute('required');
        }

    });

</script>
