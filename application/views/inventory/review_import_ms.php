<!-- <a href="<?php echo base_url().'retur/produk_pengajuan/'.$this->uri->segment('3').'/'.$this->uri->segment('4'); ?>"
    class="btn btn-dark btn-sm" role="button">kembali</a> -->

<!-- <br><br> -->
<!-- <p id="loadingImage" style="font-size: 60px; display: none">Loading ...</p> -->



<?php 
    // var_dump($get_import_ms);
    // die;
?>

<?php echo form_open_multipart('inventory/save_import_ms');?>
<input type="hidden" name="created_at" value="<?php echo $created_at; ?>">
<input type="hidden" name="created_by" value="<?php echo $created_by; ?>">

<?php echo form_submit('submit','save','class="btn btn-warning btn-md"');?>
<?php echo form_close();?>

<!-- <a href="<?php echo base_url()."inventory/save_import_ms"; ?>" class="btn btn-success btn-sm btn-mat" role="button">Simpan</a> -->

<div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th width="1"><font size="1px">SiteCode</th>
                    <th width="1"><font size="1px">Branch</th>
                    <th width="1"><font size="1px">SubBranch</th>
                    <th width="1"><font size="1px">Kodeprod</th>
                    <th width="1"><font size="1px">Namaprod</th>
                    <th width="1"><font size="1px">EstimasiSales</th>
                    <th width="1"><font size="1px">StockIdeal</th>
                    <th width="1"><font size="1px">Hapus</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($get_import_ms as $a) : ?>
                <tr>
                    <td><font size="1px"><?php echo $a->site_code; ?></td>
                    <td><font size="1px"><?php echo $a->branch_name; ?></td>
                    <td><font size="1px"><?php echo $a->nama_comp; ?></td>
                    <td><font size="1px"><?php echo $a->kodeprod; ?></td>
                    <td><font size="1px"><?php echo $a->namaprod; ?></td>
                    <td><font size="1px"><?php echo $a->estimasi_sales; ?></td>
                    <td><font size="1px"><?php echo $a->stock_ideal; ?></td>
                    <td>
                        <font size="1px">
                            <?php
                            echo anchor(
                                'inventory/delete_import_ms/'.$a->id,
                                ' ',
                                array(
                                    'class' => 'fa fa-times fa-1x', 'style' => 'color:red',
                                    'onclick' => 'return confirm(\'Hapus data ini ?\')'
                                )
                            );
                        ?>
                    </td>
                </tr>

                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
    
</div>