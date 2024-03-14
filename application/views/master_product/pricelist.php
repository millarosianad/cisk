<a href="export_product" role="button" class="btn btn-warning btn-sm">Export Pricelist</a>
<button class="btn btn-primary btn-primary btn-sm" data-toggle="modal" data-target="#pricelist"><i class="fa fa-plus"></i>Tambah Pricelist</button>
<button class="btn btn-dark btn-dark btn-sm" data-toggle="modal" data-target="#cekapi"><i class="fa fa-plus"></i>Cek API</button>
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
                            <th width="2"><font size="2px">versi_pricelist</th>
                            <th width="2"><font size="2px">Keterangan</th>
                            <th width="2"><font size="2px">tgl_naik_harga</th>
                            <th width="2"><font size="2px">status_aktif</th>
                            <th width="2"><font size="2px">tambah dp</th>
                            <th width="2"><font size="2px">detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pricelist as $a) : ?>
                            <tr>
                                <td><font size="2px"><?php echo $a->versi; ?></td>
                                <td><font size="2px"><?php echo $a->keterangan; ?></td>
                                <td><font size="2px"><?php echo $a->tgl_naik_harga; ?></td>
                                <td><font size="2px"><?php echo $a->status_aktif; ?></td>
                                
                                <td>
                                    <font size="2px">
                                        <button class="fa fa-plus fa-xl btn-info" id="testOnclick" onclick="get_pricelist_by_id('<?= $a->id ?>')"></button>
                                </td>
                                <td>
                                    <font size="2px">
                                        <button class="fa fa-eye fa-xl btn-info" onclick='window.location.href="<?php echo base_url() . "master_product/pricelist_detail/$a->id"; ?>"'></button>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('master_product/modal_pricelist'); ?>
<?php $this->load->view('master_product/modal_pricelist_by_id'); ?>
<?php $this->load->view('master_product/modal_pricelist_cekapi'); ?>