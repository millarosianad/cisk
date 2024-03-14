
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
                                <th><font size="2px">Apps Harga Ritel GT</th>
                                <th><font size="2px">Apps Harga Grosir MT</th>
                                <th><font size="2px">Apps Harga Semi Grosir</th>
                                <th><font size="2px">Apps Harga Promosi Coret</th>
                                <th><font size="2px">Apps Harga Promosi Coret Ritel GT</th>
                                <th><font size="2px">Apps Harga Promosi Coret Grosir MT</th>
                                <th><font size="2px">Apps Harga Promosi Coret Semi Grosir</th>
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
                                <td><font size="2px"><?= $a->apps_harga_ritel_gt;?></td>
                                <td><font size="2px"><?= $a->apps_harga_grosir_mt;?></td>
                                <td><font size="2px"><?= $a->apps_harga_semi_grosir;?></td>
                                <td><font size="2px"><?= $a->apps_harga_promosi_coret;?></td>
                                <td><font size="2px"><?= $a->apps_harga_promosi_coret_ritel_gt;?></td>
                                <td><font size="2px"><?= $a->apps_harga_promosi_coret_grosir_mt;?></td>
                                <td><font size="2px"><?= $a->apps_harga_promosi_coret_semi_grosir;?></td>
                                <td><font size="2px"><button class="fa fa-edit fa-xl btn btn-info btn-sm" id="testOnclick" onclick="get_hargaproduct_apps('<?= $a->id ?>')"></button></td>
                                <td><font size="2px"><a href="<?php echo base_url()."master_product/delete_harga_apps/$a->kodeprod/$a->id" ; ?>" class=" fa fa-trash fa-xl btn btn-danger btn-sm" role='button' onclick="return confirm('Apakah anda yakin ingin menghapus harga?');"></a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>                
                                <th width="1"><font size="2px">Kodeprod</th>
                                <th><font size="2px">Namaprod</th>
                                <th><font size="2px">Date</th>
                                <th><font size="2px">Apps Harga Ritel GT</th>
                                <th><font size="2px">Apps Harga Grosir MT</th>
                                <th><font size="2px">Apps Harga Semi Grosir</th>
                                <th><font size="2px">Apps Harga Promosi Coret</th>
                                <th><font size="2px">Apps Harga Promosi Coret Ritel GT</th>
                                <th><font size="2px">Apps Harga Promosi Coret Grosir MT</th>
                                <th><font size="2px">Apps Harga Promosi Coret Semi Grosir</th>
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
<?php $this->load->view('master_product/modal_editharga_apps'); ?>