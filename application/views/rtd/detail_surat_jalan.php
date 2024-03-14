<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }
</style>

<?php

// var_dump($get_history);
// die;

?>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">

            <!-- <button class="btn btn-primary btn-primary" onclick="addRpd()"><i class="fa fa-plus"></i>Tambah Proforma</button> -->

            <button class="btn btn-primary" onclick="addProfile()"><i class="fa fa-plus"></i>Proforma</button>
            <button class="btn btn-info" onclick="addSuratJalan()"><i class="fa fa-plus"></i>Surat Jalan</button>

            <hr>
            <div class="title">
                <div class="row text-center">
                    <div class="col">
                        <h4><u>Row Data Proforma - Barang Masuk</u></h4>
                    </div>
                </div>
            </div>
            <div class="dt-responsive table-responsive mt-4">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Supp</th>
                            <th>Kodeprod</th>
                            <th>Unit</th>
                            <th>ED</th>
                            <th>BatchNumber</th>
                            <th>
                                <center>#
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <!-- <? var_dump($get_history);   ?> -->
                        <?php foreach ($get_data as $key) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?php echo $key->kode; ?></td>
                                <td><?php echo $key->namasupp; ?></td>
                                <td><?php echo $key->kodeprod; ?></td>
                                <td><?php echo $key->unit; ?></td>
                                <td><?php echo $key->ed; ?></td>
                                <td><?php echo $key->batch_number; ?></td>
                                <td>

                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            




        </div>
    </div>
</div>
