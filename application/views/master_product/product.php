<a href="export_product" role="button" class="btn btn-warning btn-sm">Export Product</a>
<br>
<br>
<div class="card table-card">
    <div class="card-header">
        <!-- <p id="loadingImage" style="font-size: 60px; display: none">Loading ...</p> -->

        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th width="1">
                                <font size="1px">Kode Produk
                            </th>
                            <!-- <th><font size="1px">Kode PRC</th> -->
                            <th>
                                <font size="1px">Nama Produk
                            </th>
                            <th>
                                <font size="1px">Supplier
                            </th>
                            <th>
                                <font size="1px">Active
                            </th>
                            <th>
                                <font size="1px">Produksi
                            </th>
                            <th>
                                <font size="1px">Edit
                            </th>
                            <th>
                                <font size="1px">Harga
                            </th>
                            <th>
                                <font size="1px">Detail
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($product as $a) :
                            switch ($a->active) {
                                case 1:
                                    $active = anchor(
                                        'master_product/activer_product/0/' . $a->kodeprod . '/' . $a->active,
                                        ' ',
                                        array(
                                            'class' => 'fa fa-check fa-2x', 'style' => 'color:green',
                                            'onclick' => 'return confirm(\'Are you sure?\')'
                                        )
                                    );
                                    break;
                                case 0:
                                    $active = anchor(
                                        'master_product/activer_product/1/' . $a->kodeprod . '/' . $a->active,
                                        ' ',
                                        array(
                                            'class' => 'fa fa-times fa-2x', 'style' => 'color:red',
                                            'onclick' => 'return confirm(\'Are you sure?\')'
                                        )
                                    );
                                    break;
                            }
                            switch ($a->produksi) {
                                case 1:
                                    $produksi = anchor(
                                        'master_product/activer_produksi/0/' . $a->kodeprod . '/' . $a->produksi,
                                        ' ',
                                        array(
                                            'class' => 'fa fa-check fa-2x', 'style' => 'color:green',
                                            'onclick' => 'return confirm(\'Are you sure?\')'
                                        )
                                    );
                                    break;
                                case 0:
                                    $produksi = anchor(
                                        'master_product/activer_produksi/1/' . $a->kodeprod . '/' . $a->produksi,
                                        ' ',
                                        array(
                                            'class' => 'fa fa-times fa-2x', 'style' => 'color:red',
                                            'onclick' => 'return confirm(\'Are you sure?\')'
                                        )
                                    );
                                    break;
                            }
                            switch ($a->report) {
                                case 1:
                                    $report = anchor(
                                        'master_product/activer_report/0/' . $a->kodeprod . '/' . $a->report,
                                        ' ',
                                        array(
                                            'class' => 'fa fa-check fa-2x', 'style' => 'color:green',
                                            'onclick' => 'return confirm(\'Are you sure?\')'
                                        )
                                    );
                                    break;
                                case 0:
                                    $report = anchor(
                                        'master_product/activer_report/1/' . $a->kodeprod . '/' . $a->report,
                                        ' ',
                                        array(
                                            'class' => 'fa fa-times fa-2x', 'style' => 'color:red',
                                            'onclick' => 'return confirm(\'Are you sure?\')'
                                        )
                                    );
                                    break;
                            }
                        ?>
                            <tr>
                                <td>
                                    <font size="1px"><?php echo $a->kodeprod; ?>
                                </td>
                                <!-- <td><font size="1px"><?php echo $a->kode_prc; ?></td> -->
                                <td>
                                    <font size="1px"><?php echo $a->namaprod; ?>
                                </td>
                                <td>
                                    <font size="1px"><?php echo $a->namasupp; ?>
                                </td>
                                <td>
                                    <font size="1px"><?php echo $active; ?>
                                </td>
                                <td><font size="2px"><?php echo $produksi; ?></td>
                        <!-- <td><font size="2px"><?php echo $report; ?></td> -->
                                <td>
                                    <font size="5px">
                                        <button class="fa fa-edit fa-xl btn-info" id="testOnclick" onclick="getEditIDProduct('<?= $a->kodeprod ?>')"></button>
                                </td>
                                <td>
                                    <font size="5px">
                                        <button class="fa fa-plus fa-xl btn-info" id="testOnclick" onclick="getHargaIDProduct('<?= $a->kodeprod ?>')"></button>
                                </td>
                                <td>
                                    <font size="5px">
                                        <button class="fa fa-eye fa-xl btn-info" onclick='window.location.href="<?php echo base_url() . "master_product/product_detail_harga/$a->kodeprod"; ?>"'></button>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== Edit Product =============================================== -->
<?php $this->load->view('master_product/modal_editproduct'); ?>

<!-- =========================================== Tambah Harga =============================================== -->
<?php $this->load->view('master_product/modal_addharga'); ?>
