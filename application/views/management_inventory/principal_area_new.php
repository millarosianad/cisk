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

<?= form_open($url); ?>

<div class="container">

    <div class="row mt-5">
        <div class="col-md-12">
            <table id="test" class="display" style="display: inline-block; overflow-y: scroll; width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center col-1" style="background-color: darkslategray;" >
                            <font size="1px" color="black"><input type="button" class="btn btn-default btn-sm" id="toggle"
                            value="click all" onclick="click_all_request()">
                        </th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Kodeprod</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Namaprod</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Batch</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">ED</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Alasan</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Ket</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Nama Outlet</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Qty</th>
                        <!-- <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Status</th> -->
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Qty Approval</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Keterangan Area</th>
                    </tr>
                </thead>
                <tbody>     
                    

                    <?php 
                    foreach ($get_pengajuan_detail->result() as $a) : ?>
                    <tr>
                        <td>
                            <center>
                            <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                            </center>
                        </td>
                        <td><?= $a->kodeprod ?></td>
                        <td><?= $a->namaprod ?></td>
                        <td><?= $a->batch_number ?></td>
                        <td><?= $a->expired_date ?></td>
                        <td><?= $a->alasan ?></td>
                        <td><?= $a->keterangan ?></td>
                        <td><?= $a->nama_outlet ?></td>
                        <td><?= $a->jumlah ?></td>
                        <!-- <td><?= $a->nama_status ?></td> -->
                        <td>
                            <?php
                                if ($a->qty_approval == 0 || $a->qty_approval) { ?>
                                    <input type="number" class="form-control" name="qty_approval[<?= $a->id; ?>]" value="<?= $a->qty_approval ?>" max="<?= $a->jumlah ?>">
                                <?php }else{ ?>                                    
                                    <input type="number" class="form-control" name="qty_approval[<?= $a->id; ?>]" value="">
                                <?php
                                }
                            ?>
                        </td>
                        <td><?= $a->keterangan_principal_area ?></td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>
        </div>
    </div>

    <hr>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="status_approval">Pilih Status Verifikasi</label>
        </div>
        <div class="col-md-4">
            <select name="status_approval" class="form-control" id="status_approval" required>
                <option value="">-- Status --</option>
                <option value="12">APPROVE FULL ROW</option>
                <option value="11">APPROVE PARTIAL ROW</option>
                <option value="13">REJECT ROW</option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="keterangan_principal_area">Keterangan</label>
        </div>
        <div class="col-md-4">
            <textarea name="keterangan_principal_area" id="keterangan_principal_area" cols="30" rows="3" class="form-control"></textarea>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="customerid"></label>
        </div>
        <div class="col-md-4">
            <input type="hidden" name="signature" value="<?= $signature ?>">
            <input type="hidden" name="supp" value="<?= $supp ?>">
            <?php 
                if (!$principal_area_at) { ?>
                    <input type="submit" class="btn btn-info" value="update data">
                <?php 
                }
            ?>
            
            <a href="<?= base_url().'management_inventory/dashboard' ?>" class="btn btn-dark">Back to dashboard</a>
        </div>
    </div>

    <?= form_close();?>
    
    <hr>

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
    

    <div class="row mb-5 mt-2">
        <div class="col-md-12 text-center">
            <p>Cek kembali data anda. Jika sudah ok, klik Button di bawah ini :</p>
        </div>

        <div class="col-md-12 d-flex justify-content-center">
            <div class="form-inline row">
                <div class="col-sm-12">

                    <?php
                    
                        if (!$principal_area_at) { ?>
                            <?php echo form_open($url_proses_mpm); ?>
                                <input type="hidden" name="signature" value="<?= $signature ?>">
                                <input type="hidden" name="supp" value="<?= $supp ?>">
                                <input type="submit" value="Proses ke MPM" class="btn btn-info">
                            <?= form_close();?>
                        <?php 
                        }else{ ?>
                            <button type="submit" class="btn btn-dark" disabled>permintaan sudah diajukan</button>
                        <?php
                        }
                    ?>

                </div>
            </div>
        </div>
    </div>

    <?= form_close();?>

    <hr><br>

<script>
      $(document).ready(function () {
        $("#test").DataTable({
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