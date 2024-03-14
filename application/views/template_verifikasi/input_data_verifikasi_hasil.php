<div class="col-sm-10">
    - Tahun : <?php echo $tahun; ?> <br>
    - Bulan : <?php echo $bln; ?><hr>
    <br>
    <br>
    <a href="<?php echo base_url()."status/input_data_verifikasi"; ?>"class="btn btn-dark" role="button">
                kembali</a>
    <a href="<?php echo base_url()."status/export_verifikasi_harga/"; ?>" class="btn btn-warning" role="button">export (.csv)</a>
    
    </div>
    <br>
        
    <div class="card table-card">
        <div class="card-header">
        <div class="card-block">
        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">   
                    <thead>
                        <tr>
                            <th colspan="2"><center>DP</th>
                            <th colspan="2"><center>Produk</th>
                            <th colspan="2"><center>Harga</th>
                        </tr>
                        <tr>                
                            <th ><font size="2px">Branch</th>
                            <th ><font size="2px">Sub Branch</th>
                            <th><font size="2px">Kodeprod</th>
                            <th width='1%'><font size="2px">Nama Produk</th>
                            <th ><font size="2px">Harga Jual DP</th>
                            <th ><font size="2px">Harga PO</th>                            
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($proses as $a) : ?>
                        <tr>                     
                            <td><font size="2px"><?php echo $a->branch_name; ?></td>
                            <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                            <td><font size="2px"><?php echo $a->kodeprod; ?></td>
                            <td><font size="2px"><?php echo $a->namaprod; ?></td>
                            <td><font size="2px"><?php echo $a->harga; ?></td>
                            <td><font size="2px"><?php echo $a->h_dp; ?></td>                         
                    <?php endforeach; ?>                    
                    </tbody>
                    <tfoot>
                        <tr>
                            <th ><font size="2px">Branch</th>
                            <th ><font size="2px">Sub Branch</th>
                            <th ><font size="2px">Kodeprod</th>
                            <th ><font size="2px">Nama Produk</th>
                            <th ><font size="2px">Harga DP</th>
                            <th ><font size="2px">Harga PO</th>  
                        </tr>
                    </tfoot>
                    </table>
                    </div>