<div class="col-sm-10">
    - Tahun : <?php echo $tahun; ?> <br>
    - Kodeprod : <b> <?php echo $kodeprod; ?> <br>
    - Breakdown : Kodeprod
    <hr>
    Informasi !! : <br>
    - Halaman ini hanya menampilkan nilai Unit. Untuk melihat Value. Silahkan klik Export.
    - Class dan Tipe Outlet berdasarkan data terbaru yang diupload (current)
    <br>
    <br>
    <a href="<?php echo base_url()."sales_omzet/sales_outlet"; ?>"class="btn btn-dark" role="button">
                kembali</a>
    <a href="<?php echo base_url()."sales_omzet/export_outlet/$tahun/2/kodeprod"; ?>"class="btn btn-warning" role="button">
    export(.csv)</a>
    <a href="<?php echo base_url()."sales_omzet/export_excel_outlet/$tahun/2/kodeprod"; ?>"class="btn btn-success" role="button">
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
                        <th width="1"><font size="2px">Kode</th>
                        <th><font size="2px">Outlet</th>
                        <th><font size="2px">Class</th>
                        <th><font size="2px">Nama produk</th>
                        <th><font size="2px">JAN</th>
                        <th><font size="2px">FEB</th>
                        <th><font size="2px">MAR</th>
                        <th><font size="2px">APR</th>
                        <th><font size="2px">MEI</th>
                        <th><font size="2px">JUN</th>
                        <th><font size="2px">JUL</th>
                        <th><font size="2px">AGS</th>
                        <th><font size="2px">SEP</th>
                        <th><font size="2px">OKT</th>
                        <th><font size="2px">NOV</th>
                        <th><font size="2px">DES</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($proses as $a) : ?>
                    <tr>                      
                        <td><font size="2px"><?php echo $a->kode; ?></td>
                        <td><font size="2px"><?php echo ($a->outlet); ?></td>
                        <td><font size="2px"><?php echo ($a->kodesalur); ?></td>
                        <td><font size="2px"><?php echo ($a->namaprod); ?></td>
                        <td><font size="2px"><?php echo number_format($a->unit_1); ?></td>
                        <td><font size="2px"><?php echo number_format($a->unit_2); ?></td>
                        <td><font size="2px"><?php echo number_format($a->unit_3); ?></td>
                        <td><font size="2px"><?php echo number_format($a->unit_4); ?></td>
                        <td><font size="2px"><?php echo number_format($a->unit_5); ?></td>
                        <td><font size="2px"><?php echo number_format($a->unit_6); ?></td>
                        <td><font size="2px"><?php echo number_format($a->unit_7); ?></td>
                        <td><font size="2px"><?php echo number_format($a->unit_8); ?></td>
                        <td><font size="2px"><?php echo number_format($a->unit_9); ?></td>
                        <td><font size="2px"><?php echo number_format($a->unit_10); ?></td>
                        <td><font size="2px"><?php echo number_format($a->unit_11); ?></td>
                        <td><font size="2px"><?php echo number_format($a->unit_12); ?></td>
                    </tr>
                <?php endforeach; ?>
                
                </tbody>
                <tfoot>
                        <tr>                
                            <th width="1"><font size="2px">Kode</th>
                            <th><font size="2px">Outlet</th>
                            <th><font size="2px">Class</th>
                            <th><font size="2px">Nama Produk</th>
                            <th><font size="2px">JAN</th>
                            <th><font size="2px">FEB</th>
                            <th><font size="2px">MAR</th>
                            <th><font size="2px">APR</th>
                            <th><font size="2px">MEI</th>
                            <th><font size="2px">JUN</th>
                            <th><font size="2px">JUL</th>
                            <th><font size="2px">AGS</th>
                            <th><font size="2px">SEP</th>
                            <th><font size="2px">OKT</th>
                            <th><font size="2px">NOV</th>
                            <th><font size="2px">DES</th>
                        </tr>
                </tfoot>
            </table>
         </div>
        </div>   
    </div>
</div>