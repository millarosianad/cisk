
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
                                <th width="1"><font size="2px">Edit</th>
                                <th width="1"><font size="2px">Kodeprod</th>
                                <th><font size="2px">Namaprod</th>
                                <th><font size="2px">Tgl Naik Harga</th>
                                <th><font size="2px">H jual DP Grosir</th>
                                <th><font size="2px">H jual DP Retail</th>
                                <th><font size="2px">H jual DP Motoris Retail</th>
                                <th><font size="2px">Site Code</th>
                                <th><font size="2px">Branch Name</th>
                                <th><font size="2px">Nama Comp</th>
                                <th width="1"><font size="2px">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($harga as $a) : ?>
                            <tr>
                                <td><font size="2px"><button class="fa fa-edit fa-xl btn btn-info btn-sm" id="testOnclick" onclick="get_hargaproduct_dp('<?= $a->id ?>')"></button></td>
                                <td><font size="2px"><?= $a->kodeprod;?></td>
                                <td><font size="2px"><?= $a->namaprod;?></td>
                                <td><font size="2px"><?= $a->tgl_naik_harga;?></td>
                                <td><font size="2px"><?= $a->h_jual_dp_grosir;?></td>
                                <td><font size="2px"><?= $a->h_jual_dp_retail;?></td>
                                <td><font size="2px"><?= $a->h_jual_dp_motoris_retail;?></td>
                                <td><font size="2px"><?= $a->site_code;?></td>
                                <td><font size="2px"><?= $a->branch_name;?></td>
                                <td><font size="2px"><?= $a->nama_comp;?></td>
                                <td><font size="2px"><a href="<?php echo base_url()."master_product/delete_harga_dp/$a->kodeprod/$a->id" ; ?>" class=" fa fa-trash fa-xl btn btn-danger btn-sm" role='button' onclick="return confirm('Apakah anda yakin ingin menghapus row ini?');"></a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                      
                </table>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== Edit Product =============================================== -->
<?php $this->load->view('master_product/modal_editharga_dp'); ?>