<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }

    table th,
    table td {
        white-space: normal !important;
    }
</style>

<?php 

if ($this->session->userdata('id') == 297 || $this->session->userdata('id') == 306) { ?>

<div class="row mb-5">
    <div class="col-md-5">
        <a href="<?= base_url() ?>monitor/library_raw_data" class="btn btn-warning"> Library Raw Data</a>
    </div>
</div>

<?php } ?>

<div class="dt-responsive table-responsive">
    <table id="multi-colum-dt" class="table table-hover m-b-0">
        <thead>
            <tr>
                <th>Principal</th>
                <th>File</th>
                <th>Keterangan</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($list_data as $key => $value) { ?>
            <tr>
                <td><?= $value->NAMASUPP;?></td>
                <td><?= $value->nama;?></td>
                <td><?= $value->keterangan;?></td>
                <td>
                    <!-- KODINGAN LAMA -->
                    <!-- <?php 
                        if ($value->target_csv == null) {
                            echo "belum tersedia";
                        } else {
                            echo anchor(base_url("portal_raw/download_file/$value->target_csv"), 'download', "class='btn btn-primary btn-sm'");
                        }
                    ?> --> 
                    <!-- END -->

                    <?php 
                        $file = './assets/file/portal_raw/raw_data/'.$value->target_csv; 
                        if (file_exists($file)) {
                            echo anchor(base_url("portal_raw/download_file/$value->target_csv"), 'download', "class='btn btn-primary btn-sm'"); 
                       
                        } else {
                            echo "belum tersedia"; ?>   
                        <?php 
                        }
                    ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>