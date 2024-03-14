
<button class="btn btn-dark btn-sm" onclick='window.location.href="<?php echo base_url()."master_product/product" ; ?>"'>Kembali</button>
<br>
<br>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                        <thead>
                            <tr>                
                                <th width="1"><font size="2px">Kodeprod</th>
                                <th><font size="2px">Namaprod</th>
                                <th><font size="2px">Date</th>
                                <th><font size="2px">Beli Dp</th>
                                <th><font size="2px">Disc Beli Dp</th>
                                <th><font size="2px">Beli BSP</th>
                                <th><font size="2px">Disc Beli BSP</th>
                                <th><font size="2px">Beli PBF</th>
                                <th><font size="2px">Disc Beli PBF</th>
                                <th><font size="2px">H.DP</th>
                                <th><font size="2px">Disc DP</th>
                                <th><font size="2px">H.BSP</th>
                                <th><font size="2px">Disc BSP</th>
                                <th><font size="2px">H.PBF</th>
                                <th><font size="2px">Disc PBF</th>
                                <th width="1"><font size="2px">Edit</th>
                                <th width="1"><font size="2px">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($harga as $a) : ?>
                            <tr>
                                <td><font size="2px"><?= $a->kodeprod;?></td>
                                <td><font size="2px"><?= $a->namaprod;?></td>
                                <td><font size="2px"><?= $a->tgl;?></td>
                                <td><font size="2px"><?= $a->h_beli_dp;?></td>
                                <td><font size="2px"><?= $a->d_beli_dp;?></td>
                                <td><font size="2px"><?= $a->h_beli_bsp;?></td>
                                <td><font size="2px"><?= $a->d_beli_bsp;?></td>
                                <td><font size="2px"><?= $a->h_beli_pbf;?></td>
                                <td><font size="2px"><?= $a->d_beli_pbf;?></td>
                                <td><font size="2px"><?= $a->h_dp;?></td>
                                <td><font size="2px"><?= $a->d_dp;?></td>
                                <td><font size="2px"><?= $a->h_bsp;?></td>
                                <td><font size="2px"><?= $a->d_bsp;?></td>
                                <td><font size="2px"><?= $a->h_pbf;?></td>
                                <td><font size="2px"><?= $a->d_pbf;?></td>
                                <td><font size="2px"><button class="fa fa-edit fa-xl btn btn-info btn-sm" id="testOnclick" onclick="get_hargaproduct('<?= $a->id ?>')"></button></td>
                                <td><font size="2px"><a href="<?php echo base_url()."master_product/delete_harga/$a->kodeprod/$a->id" ; ?>" class=" fa fa-trash fa-xl btn btn-danger btn-sm" role='button' onclick="return confirm('Apakah anda yakin ingin menghapus harga?');"></a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>                
                                <th width="1"><font size="2px">Kodeprod</th>
                                <th><font size="2px">Namaprod</th>
                                <th><font size="2px">Date</th>
                                <th><font size="2px">Beli DP</th>
                                <th><font size="2px">Disc Beli DP</th>
                                <th><font size="2px">Beli BSP</th>
                                <th><font size="2px">Disc Beli BSP</th>
                                <th><font size="2px">Beli PBF</th>
                                <th><font size="2px">Disc Beli PBF</th>
                                <th><font size="2px">H.DP</th>
                                <th><font size="2px">Disc DP</th>
                                <th><font size="2px">H.BSP</th>
                                <th><font size="2px">Disc BSP</th>
                                <th><font size="2px">H.PBF</th>
                                <th><font size="2px">Disc PBF</th>
                                <th width="1"><font size="2px">Edit</th>
                                <th width="1"><font size="2px">Delete</th>
                            </tr>
                        </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== Edit Product =============================================== -->
<?php $this->load->view('master_product/modal_editharga'); ?>