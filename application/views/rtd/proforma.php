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
            <?php
            $this->load->view('rtd/modal_surat_jalan');
            ?>

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

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="title">
                        <div class="row text-center">
                            <div class="col">
                                <h4><u>Breakdown Proforma By ED</u></h4>
                            </div>
                        </div>
                    </div>
                    <div class="dt-responsive table-responsive mt-4">
                        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th width="1px">No</th>
                                    <th>ED</th>
                                    <th>Total Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($get_data_by_ed as $key) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?php echo $key->ed; ?></td>
                                        <td><?php echo $key->total_unit; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="title">
                        <div class="row text-center">
                            <div class="col">
                                <h4><u>Breakdown Proforma By Batch Number</u></h4>
                            </div>
                        </div>
                    </div>
                    <div class="dt-responsive table-responsive mt-4">
                        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th width="1px">No</th>
                                    <th>Batch Number</th>
                                    <th>Total Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($get_data_by_batch_number as $key) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?php echo $key->batch_number; ?></td>
                                        <td><?php echo $key->total_unit; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <hr>
            <div class="title">
                <div class="row text-center">
                    <div class="col">
                        <h4><u>Row Data Surat Jalan - Barang Keluar</u></h4>
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
                        <? var_dump($get_data_surat_jalan);   ?>
                        <?php foreach ($get_data_surat_jalan as $key) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?php echo $key->kode; ?></td>
                                <td><?php echo $key->namasupp; ?></td>
                                <td><?php echo $key->kodeprod; ?></td>
                                <td><?php echo $key->unit; ?></td>
                                <td><?php echo $key->ed; ?></td>
                                <td><?php echo $key->batch_number; ?></td>
                                <td>
                                    <a href="<?= base_url() ?>rtd/detail_surat_jalan/<?= $key->signature; ?>" class=" btn btn-warning btn-sm" target="blank">detail</a>
                                    <a href="<?= base_url() ?>rtd/generate_pdf/surat_jalan/<?= $key->signature; ?>" class=" btn btn-primary btn-sm" target="blank">pdf</a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <hr>

            <div class="title">
                <div class="row text-center">
                    <div class="col">
                        <h4><u>Mutasi Gudang</u></h4>
                    </div>
                </div>
            </div>
            <div class="dt-responsive table-responsive mt-4">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th width="1px">No</th>
                            <th>Kode</th>
                            <th>Kodeprod</th>
                            <th>Namaprod</th>
                            <th>ED</th>
                            <th>Batch Number</th>
                            <th>SaldoAwal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Total</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_data_mutasi as $key) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?php echo $key->kode; ?></td>
                                <td><?php echo $key->kodeprod; ?></td>
                                <td><?php echo $key->namaprod; ?></td>
                                <td><?php echo $key->ed; ?></td>
                                <td><?php echo $key->batch_number; ?></td>
                                <td><?php echo $key->saldo_awal; ?></td>
                                <td><?php echo $key->masuk; ?></td>
                                <td><?php echo $key->keluar; ?></td>
                                <td><?php echo $key->total; ?></td>
                                <td><?php echo $key->created_at; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>




        </div>
    </div>
</div>


<?php
$this->load->view('rtd/modal_proforma');
?>


<script>
    $(document).ready(function() {
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('rtd/supp') ?>',
            success: function(hasil_supp) {
                $("select[name = supp]").html(hasil_supp);
            }
        });
    })
</script>

<script>
    $(document).ready(function() {
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('rtd/get_po') ?>',
            success: function(hasil_po) {
                $("select[name = id_po]").html(hasil_po);
            }
        });
    })
</script>