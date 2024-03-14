<style>
    th{
        font-weight: bold;
        background-color: #ffffff;
        border: 1px solid #000000;
        text-align: center;
    }
    td{
        background-color: #ffffff;
        border: 1px solid #000000;
    }
    *{
        text-transform: capitalize;
    }
</style>

</div>

<div class="container">

    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>
</div>

<div class="container mt-s">
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
</div>

<form action=<?= base_url().$url ?> method="post">
<div class="container">
    <div class="row mt-1 ms-5">
        <div class="col-md-3">
            <label for="tahun">Tahun Bulan</label>
        </div>
        <div class="col-md-3">
            <input type="month" class="form-control" id="bulan" name="bulan" min="2023-01" max="2024-12" required>
        </div>
        <div class="col-md-6">
            <input type="submit" class="btn btn-info rounded-1" value="Generate Target Baru">
            <a href="<?= base_url() ?>management_dashboard/dashboard" class="btn btn-dark rounded-1">back to dashboard</a>
        </div>
    </div>
</div>
</form>
<hr>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-2">
            <h5>Input Data Target</h5>
        </div>
    </div>
</div>


<form action=<?= base_url().$url_search ?> method="get">
<div class="container mt-3">
    <div class="row mb-5">            
        <div class="col-md-3">
            <label for="tahun">tampilkan target berdasarkan bulan</label>
        </div>
        <div class="col-md-3">
            <input type="month" class="form-control" id="bulan" name="bulan" min="2023-01" max="2024-12" value="<?= $this->input->get('bulan') ?>" required>
        </div>
        <div class="col-md-6">
            <input type="submit" class="btn btn-info btn-sm rounded-1" value="search">
            <!-- <input type="submit" class="btn btn-outline-danger btn-sm rounded-1" name="delete" value="delete" onclick="return confirm('Yakin menghapus data di bulan yang di pilih?')"> -->
            <input type="submit" class="btn btn-outline-success btn-sm rounded-1" name="btn_target" value="export">
        </div>
    </div>
</div>
</form>

<form action=<?= base_url().$url_save ?> method="post">
<div class="container mt-4">
    <div class="row mt-2 ms-5">
        <div class="col-md-12">
            <table id="example" class="display" style="display: inline-block; overflow-x: scroll; width:100%">
                <thead>
                    <tr>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center" colspan="5"><font color="black">branch & periode</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center" colspan="3"><font color="black">Target Value</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center" colspan="3"><font color="black">Target OT</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center" colspan="3"><font color="black">user created</th>
                    </tr>
                    <tr>
                        <th class="text-center col-1" style="background-color: white; border: 1px solid darkslategray" >
                            <font size="1px" style="color: white;"><input type="button" class="btn btn-sm btn-custom" id="toggle"
                            value="click all" onclick="click_all_request()">
                        </th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center"><font color="black">bulan</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center"><font color="black">site code</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center"><font color="black">subbranch</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center"><font color="black">divisi</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center"><font color="black">BE</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center"><font color="black">POH</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center"><font color="black">Target Principal</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center"><font color="black">KPI</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center"><font color="black">OTSC</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center"><font color="black">Created at</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center"><font color="black">Created by</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center"><font color="black">Updated at</th>
                        <th style="background-color: white; border: 1px solid darkslategray" class="text-center"><font color="black">Updated by</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_master_target->result() as $a) : ?>
                    <tr>
                        <td>
                            <center>
                            <input type="checkbox" id="<?= $a->id; ?>" name="options[]" class="<?= $a->id; ?>" value="<?= $a->id; ?>">
                            </center>
                        </td>
                        <td><?= $a->tahun.'-'.$a->bulan ?></td>
                        <td><?= $a->site_code ?></td>
                        <td><?= $a->nama_comp ?></td>
                        <td><?= $a->divisi ?></td>
                        <td>
                            <input type="text" name="target_value_be[<?= $a->id; ?>]" value="<?= $a->target_value_be ?>" size="10">
                        </td>
                        <td>
                            <input type="text" name="target_value_poh[<?= $a->id; ?>]" value="<?= $a->target_value_poh ?>" size="10">
                        </td>
                        <td>
                            <input type="text" name="target_principal[<?= $a->id; ?>]"  value="<?= $a->target_principal ?>" size="10">
                        </td>
                        <td>
                            <input type="text" name="target_ot_kpi[<?= $a->id; ?>]" value="<?= $a->target_ot_kpi ?>" size="10">
                        </td>
                        <td>
                            <input type="text" name="target_ot_otsc[<?= $a->id; ?>]" value="<?= $a->target_ot_otsc ?>" size="10">
                        </td>
                        <td><?= $a->created_at ?></td>
                        <td><?= $a->created_username ?></td>
                        <td><?= $a->updated_at ?></td>
                        <td><?= $a->updated_username ?></td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>
        </div>
    </div>
</div>

<hr>

<div class="container">
    <div class="row mt-2 mb-5">
        <div class="col-md-12 text-center">
            <p>Cek kembali data anda yang ter-ceklist di atas. Jika sudah ok, klik Button "Update Data" di bawah ini :</p>
        </div>
        <div class="col-12 text-center">
            <input type="hidden" value="<?= $this->input->get('bulan') ?>" name="bulan">
            <input type="submit" class="btn btn-info" value="update data">
        </div>
    </div>
</div>
</form>

<script>
      $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            },
        });
      });
</script>


<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>