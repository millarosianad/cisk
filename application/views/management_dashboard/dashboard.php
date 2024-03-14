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
            <input type="submit" class="btn btn-info rounded-1" value="Generate Data Dashboard" id="btnKirim" onclick="return button()">
            <button class="btn btn-info" id="btnLoading" type="button" disabled>
            generating data. Please wait ...
            </button>
            <a href="<?= base_url().'management_dashboard/target' ?>" class="btn btn-dark">back to target</a> 
        </div>
    </div>
</div>
</form>

<hr>

<form action=<?= base_url().$url_search ?> method="get">
<div class="container mt-3">
    <div class="row mb-5">            
        <div class="col-md-3">
            <label for="tahun">tampilan data berdasarkan bulan</label>
        </div>
        <div class="col-md-3">
            <input type="month" class="form-control" id="bulan" name="bulan" min="2023-01" max="2024-12" value="<?= $this->input->get('bulan') ?>" required>
        </div>
        <div class="col-md-6">
            <input type="submit" class="btn btn-info btn-sm rounded-1" value="search">
            <!-- <input type="submit" class="btn btn-outline-danger btn-sm rounded-1" name="delete" value="delete" onclick="return confirm('Yakin menghapus data di bulan yang di pilih?')"> -->
            <input type="submit" class="btn btn-outline-success btn-sm rounded-1" name="btn_dashboard" value="export">
        </div>
    </div>
</div>
</form>


<div class="container mt-4">
    <div class="row mt-2 ms-5">
        <div class="col-md-12">
            <table id="example" class="display" style="display: inline-block; overflow-x: scroll; width:100%">
                <thead>
                    <tr>
                        <th colspan="4" class="text-center"> Branch </th>
                        <th colspan="6" class="text-center"> Report Sales </th>
                        <th colspan="3" class="text-center"> Report OT </th>
                        <th colspan="3" class="text-center"> Report OT Mundur </th>
                    </tr>
                    <tr>
                        <th>bulan</th>
                        <th>site code</th>
                        <th>subbranch</th>
                        <th>divisi</th>
                        <th>target principal</th>
                        <th>realisasi + poh</th>
                        <th>ach</th>
                        <th>be</th>
                        <th>realisasi</th>
                        <th>ach</th>
                        <th>kpi</th>
                        <th>realisasi</th>
                        <th>ach</th>
                        <th>target mundur</th>
                        <th>realisasi</th>
                        <th>ach</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_dashboard_report_sales->result() as $a) : ?>
                    <tr>
                        <td><?= $a->tahun.'-'.$a->bulan ?></td>
                        <td><?= $a->site_code ?></td>
                        <td><?= $a->nama_comp ?></td>
                        <td><?= $a->divisi ?></td>
                        <td><?= number_format($a->target_principal) ?></td>
                        <td><?= number_format($a->realisasi_poh) ?></td>
                        <td><?= number_format($a->ach_poh * 100, 1) ?> %</td>
                        <td><?= number_format($a->target_value_be) ?></td>
                        <td><?= number_format($a->realisasi_be) ?></td>
                        <td><?= number_format($a->ach_be * 100, 1) ?> %</td>
                        <td><?= $a->target_ot ?></td>
                        <td><?= $a->realisasi_ot ?></td>
                        <td><?= $a->ach_ot ?></td>
                        <td><?= $a->ot_mundur ?></td>
                        <td><?= $a->realisasi_ot_berjalan ?></td>
                        <td><?= number_format($a->ach_ot_mundur * 100, 1) ?> %</td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>
        </div>
    </div>
</div>

<hr>

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

<script>
    function button()
    {
        $("#btnKirim").hide();
        $("#btnLoading").show();
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>