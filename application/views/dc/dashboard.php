<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }
</style>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">

            <div class="row">
                <div class="col text-center">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#barang_masuk">Barang Masuk</button>
                    <?php $this->load->view('dc/modal_barang_masuk_rev') ?>

                    <button class="btn btn-success" onclick="barang_keluar()">Barang Keluar</button>
                    <?php $this->load->view('dc/modal_barang_keluar'); ?>
                </div>
            </div>
            <br><br>
            <hr>
            <div class="title mt-4">
                <div class="row">
                    <div class="col">
                        <h3>Kartu Stock</h3>
                    </div>
                    <div class="col text-right">
                        <a href="<?= base_url() ?>dc/export_kartu_stock" target="_blank" class="btn btn-warning">export</a>
                    </div>
                </div>
            </div>
            <div class="dt-responsive table-responsive mt-4">
                <!-- <table id="multi-colum-dt" class="table table-striped table-bordered nowrap"> -->
                <table id="multi-colum-dt" class="table table-columned">
                    <thead>
                        <tr>
                            <!-- <th>No</th> -->
                            <th>Kodeprod</th>
                            <th>Namaprod</th>
                            <th>Saldoawal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Total</th>
                            <th>Nodo</th>
                            <th>Nopo</th>
                            <!-- <th>Branch</th> -->
                            <th>SubBranch</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_data_row_mutasi as $key) : ?>
                            <tr>
                                <!-- <td><?= $no++; ?></td> -->
                                <td><?php echo $key->kodeprod; ?></td>
                                <td><?php echo $key->namaprod; ?></td>
                                <td><?php echo $key->saldo_awal; ?></td>
                                <td><?php echo $key->masuk; ?></td>
                                <td><?php echo $key->keluar; ?></td>
                                <td><?php echo $key->total; ?></td>
                                <td><?php echo $key->nodo; ?></td>
                                <td><?php echo $key->nopo; ?></td>
                                <!-- <td><?php echo $key->branch_name; ?></td> -->
                                <td><?php echo $key->nama_comp; ?></td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <br><br><br>
            <hr>


<!-- <div class="col-xl-4 col-md-12">
    <div class="card analytic-card card-green">
        <div class="card-body">
            <div class="row align-items-center m-b-30">
                <div class="col-auto">
                    <i class="fas fa-shopping-cart text-c-green f-18 analytic-icon"></i>
                </div> -->
                <!-- <div class="col text-right">
                    <h3 class="m-b-5 text-white"></h3>
                    <h6 class="m-b-0 text-white">Note : Pastikan stock selalu '0'</h6>
                </div> -->
            <!-- </div>
            <p class="m-b-0  text-white d-inline-block">Total Stock DC Saat Ini : </p>
            <h3 class=" text-white d-inline-block m-b-0 m-l-10">10</h3> -->
            <!-- <h6 class="m-b-0 d-inline-block  text-white float-right">
                <i class="fas fa-caret-up m-r-10 f-18"></i>
            10%</h6> -->
        <!-- </div>
    </div>
</div> -->


<!-- <div class="col-xl-3 col-md-6">
    <div class="card ticket-card">
        <div class="card-body">
            <p class="m-b-30 bg-c-red lbl-card"><i class="fas fa-folder-open"></i> Total Stock DC</p>
            <div class="text-center">
                <h2 class="m-b-0 d-inline-block text-c-red">
                    <?php 
                        if ($get_data_row_mutasi_by_produk_total->total == null) {
                            echo "0";
                        }else{
                            echo $get_data_row_mutasi_by_produk_total->total; 
                        }
                    ?>
                </h2>
                <p class="m-b-0 d-inline-block">Unit</p>
                <p class="m-b-0 m-t-15"></p>
            </div>
        </div>
    </div>
</div> -->

<!-- <div class="col-xl-3 col-md-6">
    <div class="card ticket-card">
        <div class="card-body">
            <p class="m-b-30 bg-c-red lbl-card"><i class="fas fa-folder-open"></i> Total Stock Masuk</p>
            <div class="text-center">
                <h2 class="m-b-0 d-inline-block text-c-red">
                    <?php 
                        if ($get_data_masuk == null) {
                            echo "0";
                        }else{
                            echo $get_data_masuk->total_masuk; 
                        }
                    ?>
                </h2>
                <p class="m-b-0 d-inline-block">Unit</p>
                <p class="m-b-0 m-t-15"></p>
            </div>
        </div>
    </div>
</div> -->



<!-- <hr> -->

            <div class="row">
                <div class="col-md">
                    <div class="title">
                        <div class="row">
                            <div class="col">
                                <h3>Total Stock Produk</h3>
                            </div>
                            <div class="col text-right">
                                <a href="<?= base_url() ?>dc/export_total_stock" target="_blank" class="btn btn-warning">export</a>                                
                            </div>
                        </div>
                    </div>

                    <center>
                    <div class="col-md mt-5">

                        <div class="col-xl-4 col-md-6 text-center">
                            <div class="card ticket-card">
                                <div class="card-body">
                                    <p class="m-b-30 bg-c-red lbl-card"><i class="fas fa-folder-open"></i> Total Stock DC</p>
                                    <div class="text-center">
                                        <h2 class="m-b-0 d-inline-block text-c-red">
                                            <?php 
                                                if ($get_data_row_mutasi_by_produk_total->total == null) {
                                                    echo "0";
                                                }else{
                                                    echo $get_data_row_mutasi_by_produk_total->total; 
                                                }
                                            ?>
                                        </h2>
                                        <p class="m-b-0 d-inline-block"><strong>UNIT</strong></p>
                                        <p class="m-b-0 m-t-15">
                                            
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    </center>


                    <div class="dt-responsive table-responsive mt-4">
                        <!-- <table id="table-asset" class="table table-striped table-bordered nowrap"> -->
                        <table id="table-asset" class="table table-hover m-b-0">
                            <thead>
                                <tr>
                                    <!-- <th width="1px">No</th> -->
                                    <th>Kodeprod</th>
                                    <th>Namaprod</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($get_data_row_mutasi_by_produk as $key) : ?>
                                    <tr>
                                        <!-- <td><?= $no++; ?></td> -->
                                        <td><?php echo $key->kodeprod; ?></td>
                                        <td><?php echo $key->namaprod; ?></td>
                                        <td><?php echo $key->total; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <hr>
            <br><br><br>

            <div class="title">
                <div class="row">
                    <div class="col">
                        <h3>History Masuk Gudang</h3>
                    </div>
                    <div class="col text-right">
                        <a href="<?= base_url() ?>dc/export_masuk" target="_blank" class="btn btn-warning">export</a>
                    </div>
                </div>
            </div>
            <div class="dt-responsive table-responsive mt-4">
                <!-- <table id="table-listorder" class="table table-striped table-bordered nowrap"> -->
                <table id="table-listorder" class="table table-hover m-b-0">
                    <thead>
                        <tr>
                            <!-- <th>No</th> -->
                            <th>Kode</th>
                            <th>Nodo</th>
                            <th>Tgldo</th>
                            <th>Nopo</th>
                            <th>Tglpo</th>
                            <th>Company</th>
                            <th>TotalUnit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_data_row_masuk as $key) : ?>
                            <tr>
                                <!-- <td><?= $no++; ?></td> -->
                                <td><?php echo $key->kode; ?></td>
                                <td><?php echo $key->nodo; ?></td>
                                <td><?php echo $key->tgldo; ?></td>
                                <td><?php echo $key->nopo; ?></td>
                                <td><?php echo $key->tglpo; ?></td>
                                <td><?php echo $key->company; ?></td>
                                <td><?php echo $key->total; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <hr>
            <br><br><br>

            <div class="title">
                <div class="row">
                    <div class="col">
                        <h3>History Keluar Gudang</h3>
                    </div>
                    <div class="col text-right">
                        <a href="<?= base_url() ?>dc/export_keluar" target="_blank" class="btn btn-warning">export</a>
                    </div>
                </div>
            </div>
            <div class="dt-responsive table-responsive mt-4">
                <!-- <table id="table-dc" class="table table-striped table-bordered nowrap"> -->
                <table id="table-dc" class="table table-hover m-b-0">
                    <thead>
                        <tr>
                            <!-- <th>No</th> -->
                            <th>Kode</th>
                            <th>Email</th>
                            <th>Wa</th>
                            <th>Nodo</th>
                            <th>Tgldo</th>
                            <th>Nopo</th>
                            <th>Tglpo</th>
                            <th>Company</th>
                            <th>TotalUnit</th>
                            <th>status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_data_row_keluar as $key) : ?>
                            <tr>
                                <!-- <td><?= $no++; ?></td> -->
                                <td>
                                    <a href="<?= base_url() ?>dc/generate_pdf_keluar/<?= $key->signature; ?>" class=" btn btn-primary btn-sm" target="blank"><?php echo $key->kode; ?></a>
                                </td>
                                <td><a href="<?= base_url(); ?>dc/email_download/<?= $key->signature; ?>" class="btn btn-danger btn-sm" target="blank">email</a></td>
                                <td><a href="<?= base_url(); ?>dc/send_wa/<?= $key->signature; ?>" class="btn btn-success btn-sm" target="blank">wa</a></td>
                                <td><?php echo $key->nodo; ?></td>
                                <td><?php echo $key->tgldo; ?></td>
                                <td><?php echo $key->nopo; ?></td>
                                <td><?php echo $key->tglpo; ?></td>
                                <td><?php echo $key->company; ?></td>
                                <td><?php echo $key->total; ?></td>
                                <td><?php echo $key->status_kirim; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('dc/nodo_barang_keluar') ?>',
            success: function(hasil_kode) {
                $("select[name = kode_masuk]").html(hasil_kode);
            }
        });
    })
</script>