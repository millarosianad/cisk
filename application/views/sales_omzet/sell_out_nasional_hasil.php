<div class="col-sm-10">
    - Tahun : <?php echo substr($periode_1,0,4) ; ?> <br>
    - Bulan : <?php echo substr($periode_1,5,2) ; ?> <br>
    - Supplier : <?php echo $supp; ?> <br>
    <hr>
    <br>
    <a href="<?php echo base_url()."sales_omzet/sell_out_nasional"; ?>"class="btn btn-dark" role="button">
                kembali</a>
    <a href="<?php echo base_url()."sales_omzet/export_sell_out_nasional/"; ?>"class="btn btn-warning" role="button">
    export(.csv)</a>
    </div>
    <br>

<div class="card table-card">
    <div class="card-header">
    <div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
            <thead>     
                <tr>    
                    <th width="1"><font size="2px">Kode Produk</th>
                    <th><font size="2px">Nama Produk</th>
                    <th><font size="2px">Unit</th>
                    <th><font size="2px">Value</th>
                    <th><font size="2px">Bulan</th>                                    
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($proses)): ?>
                <?php foreach($proses as $x) : ?>
                <tr>                            
                        <td><font size="2px"><?php echo $x->kodeprod; ?></td>
                        <td><font size="2px"><?php echo $x->namaprod; ?></td>
                        <td><font size="2px"><?php echo number_format($x->unit); ?></td>
                        <td><font size="2px"><?php echo number_format($x->value); ?></td>
                        <td><font size="2px"><?php echo number_format($x->bulan); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot><tr>    
                        <th width="1"><font size="2px">Kode Produk</th>
                        <th><font size="2px">Nama Produk</th>
                        <th><font size="2px">Unit</th>
                        <th><font size="2px">Value</th>
                        <th><font size="2px">Bulan</th>                                      
                </tr>
            </tfoot>                    
        </table>
    </div> 
</div>
