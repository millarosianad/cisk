
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

<?= form_open($url); ?>

<div class="container">

    <div class="row mt-5">
        <div class="col-md-12">
            <table id="example" class="display" style="display: inline-block; overflow-y: scroll">
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
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Satuan</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Nama Outlet</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Ket</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Jumlah</th>
                        <th class="text-center col-1"><font color="black">Approval</th>
                        <th class="text-center col-1"><font color="black">Qty Approval</th>
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
                        <td><?= $a->satuan ?></td>
                        <td><?= $a->nama_outlet ?></td>
                        <td><?= $a->keterangan ?></td>
                        <td><?= $a->jumlah ?></td>
                        <td></td>
                        <td><input type="number" class="form-control" name="qty_approval" value="<?= $a->jumlah ?>"></td>
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
                <option value="1">Approve All</option>
                <option value="0">Reject All</option>
                <option value="0">Revision</option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-2">
            <label for="keterangan_principal_area">Deskripsi</label>
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
                if ($this->session->userdata('id') == '297' || $this->session->userdata('id') == '444') { ?>
                    <input type="submit" class="btn btn-info" value="update data">
                <?php 
                }
            ?>
            
            <a href="<?= base_url().'management_inventory/dashboard' ?>" class="btn btn-dark">Back to dashboard</a>
        </div>
    </div>

    <?= form_close();?>
    
    <hr>
    

    <div class="row mb-5 mt-5">
        <div class="col-md-12 text-center">
            <p>Cek kembali data anda. Jika sudah ok, klik Button di bawah ini :</p>
        </div>

        <div class="col-md-12 d-flex justify-content-center">
            <div class="form-inline row">
                <div class="col-sm-12">

                    <?php 
                        if ($status == 2) { ?>
                            <a href="<?= base_url().'management_inventory/update_status_pengajuan/'.$signature.'/1'.'/'.$supp ?>" class="btn btn-danger">Proses DP</a>
                            <a href="<?= base_url().'management_inventory/update_status_pengajuan/'.$signature.'/3'.'/'.$supp ?>" class="btn btn-primary">Proses Principal</a>
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
    
    <hr>
    <br>
    <br>

    <br>

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