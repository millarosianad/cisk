<div class="col-sm-10">
    - Tahun : <?php echo $year; ?> <br>
    - Kodeprod : <?php echo $kodeprod; ?> <br>
    - Class Outlet : <?php echo $kdc; ?> <br>
    - Breakdown : SubBranch, Kodeprod, Tipe<br>

    <hr>
    Informasi !! : Halaman ini hanya menampilkan nilai Unit. Untuk melihat OT dan Value. Silahkan klik Export
    <br>
    <br>
    <a href="<?php echo base_url()."sales_omzet/sell_out_product"; ?>"class="btn btn-dark" role="button">
                kembali</a>
    <a href="<?php echo base_url()."sales_omzet/export_sales_per_product_kode_kodeprod_kode_type"; ?>"class="btn btn-warning" role="button">
    export(.csv)</a>
    <a href="<?php echo base_url()."sales_omzet/export_excel_sales_per_product/12"; ?>"class="btn btn-success" role="button">
    export(.excel)</a>
    </div>
    <br>
 

<div class="card table-card">
    <div class="card-header">
    <div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
            <thead>     
                <tr>    
                    <th width="1"><font size="2px">Branch</font></th>
                    <th width="1"><font size="2px">SubBranch</font></th>
                    <th width="1"><font size="2px">Produk</font></th>
                    <th width="1"><font size="2px">Tipe</font></th>
                    <th width="1"><font size="2px">Sektor</font></th>
                    <th width="1"><font size="2px">Segment</font></th>
                    <th width="1"><font size="2px">Jan</font></th>
                    <th width="1"><font size="2px">Feb</font></th>
                    <th width="1"><font size="2px">Mar</font></th>
                    <th width="1"><font size="2px">Apr</font></th>
                    <th width="1"><font size="2px">Mei</font></th>
                    <th width="1"><font size="2px">Jun</font></th>
                    <th width="1"><font size="2px">Jul</font></th>
                    <th width="1"><font size="2px">Agus</font></th>
                    <th width="1"><font size="2px">Sep</font></th>
                    <th width="1"><font size="2px">Okt</font></th> 
                    <th width="1"><font size="2px">Nov</font></th> 
                    <th width="1"><font size="2px">Des</font></th>                                      
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($proses)): ?>
                <?php foreach($proses as $product) : ?>
                <tr>      
                    <td><font size="2px"><?php echo $product->branch_name; ?></td>
                    <td><font size="2px"><?php echo $product->nama_comp; ?></td>          
                    <td><font size="2px"><?php echo $product->namaprod; ?></td>
                    <td><font size="2px"><?php echo $product->kode_type; ?></td>
                    <td><font size="2px"><?php echo $product->sektor; ?></td>
                    <td><font size="2px"><?php echo $product->segment; ?></td>
                    <td><font size="2px"><?php echo number_format($product->unit_1); ?></td>
                    <td><font size="2px"><?php echo number_format($product->unit_2); ?></td>
                    <td><font size="2px"><?php echo number_format($product->unit_3); ?></td>
                    <td><font size="2px"><?php echo number_format($product->unit_4); ?></td>
                    <td><font size="2px"><?php echo number_format($product->unit_5); ?></td>
                    <td><font size="2px"><?php echo number_format($product->unit_6); ?></td>
                    <td><font size="2px"><?php echo number_format($product->unit_7); ?></td>
                    <td><font size="2px"><?php echo number_format($product->unit_8); ?></td>
                    <td><font size="2px"><?php echo number_format($product->unit_9); ?></td>
                    <td><font size="2px"><?php echo number_format($product->unit_10); ?></td>
                    <td><font size="2px"><?php echo number_format($product->unit_11); ?></td>
                    <td><font size="2px"><?php echo number_format($product->unit_12); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot><tr>    
                    <th width="1"><font size="2px">Branch</font></th>
                    <th width="1"><font size="2px">SubBranch</font></th>
                    <th width="1"><font size="2px">Produk</font></th>
                    <th width="1"><font size="2px">Tipe</font></th>
                    <th width="1"><font size="2px">Sektor</font></th>
                    <th width="1"><font size="2px">Segment</font></th>
                    <th width="1"><font size="2px">Jan</font></th>
                    <th width="1"><font size="2px">Feb</font></th>
                    <th width="1"><font size="2px">Mar</font></th>
                    <th width="1"><font size="2px">Apr</font></th>
                    <th width="1"><font size="2px">Mei</font></th>
                    <th width="1"><font size="2px">Jun</font></th>
                    <th width="1"><font size="2px">Jul</font></th>
                    <th width="1"><font size="2px">Agus</font></th>
                    <th width="1"><font size="2px">Sep</font></th>
                    <th width="1"><font size="2px">Okt</font></th> 
                    <th width="1"><font size="2px">Nov</font></th> 
                    <th width="1"><font size="2px">Des</font></th>                                      
                </tr>
            </tfoot>                    
        </table>
    </div> 
</div>