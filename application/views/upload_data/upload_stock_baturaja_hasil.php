<div class="col-sm-12">
        <!-- - periode : <b> <?php echo $periode_1.' - '.$periode_2; ?> </b> <br>
        - Kodeprod : <b> <?php echo $kodeprod; ?><hr>
        Informasi !! :<br> 
        - total faktur antara web dan bra mungkin akan beda karena di web faktur batal juga dihitung. Gap web vs bra kurang dari 0.09. Jika ditemukan lebih dari ini dapat menghubungi IT.<br>
        - Pemilihan periode dengan gap 2 tahun ke atas tidak bisa diproses karena akan mengambil resource yang besar. -->
    </div>
</div>
        
<div class="card-block">

    <div class="col-sm-10">
        <div class="form-group row">            
            <div class="col-sm-10">
                <a href="<?php echo base_url()."status/upload_stock/"; ?>"class="btn btn-dark" role="button">
                kembali</a>
                <a href="<?php echo base_url()."status/mapping_stock_baturaja/$kode/$tahun/$bulan"; ?>"class="btn btn-warning" role="button">
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Proses Mapping ke produk MPM</a>        
            </div>
            <div class="col-sm-5"></div>
        </div>
    </div>
</div>

    <hr>
    
    <div class="card-block">

    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>kd_barang</th>
                    <th>so_awal</th>
                    <th>masuk</th> 
                    <th>keluar</th> 
                    <th>so_akhir</th>
                    <th>lk_namagudang</th>
                    <th>lk_namacabang</th>
                    <th>cl_soawal</th>
                    <th>lk_namabarang</th>
                    <th>cl_masuk</th>
                    <th>cl_keluar</th>
                    <th>cl_soakhir</th>
                    <th>cl_satuan</th>
                    <th>spec</th>
                    <th>kd_merk</th>
                    <th>nama_barang</th>
                    <th>cl_nama_merk</th>
                    <th>kd_supplier</th>
                    <th>perusahaan</th>
                    <th>Nama File</th>
                    <th>Tanggal Upload</th>                       
                </tr>
            </thead>
            <tbody>

            <?php foreach($get_upload_stock as $a) : ?>
                <tr>
                    <td><font size="2px"><?php echo $a->kode; ?></td>                 
                    <td><font size="2px"><?php echo $a->kd_barang; ?></td>
                    <td><font size="2px"><?php echo $a->so_awal; ?></td>
                    <td><font size="2px"><?php echo $a->masuk; ?></td>
                    <td><font size="2px"><?php echo $a->keluar; ?></td>
                    <td><font size="2px"><?php echo $a->so_akhir; ?></td>
                    <td><font size="2px"><?php echo $a->lk_namagudang; ?></td>
                    <td><font size="2px"><?php echo $a->lk_namacabang; ?></td>
                    <td><font size="2px"><?php echo $a->cl_soawal; ?></td>
                    <td><font size="2px"><?php echo $a->lk_namabarang; ?></td>
                    <td><font size="2px"><?php echo $a->cl_masuk; ?></td>
                    <td><font size="2px"><?php echo $a->cl_keluar; ?></td>
                    <td><font size="2px"><?php echo $a->cl_soakhir; ?></td>
                    <td><font size="2px"><?php echo $a->cl_satuan; ?></td>
                    <td><font size="2px"><?php echo $a->spec; ?></td>
                    <td><font size="2px"><?php echo $a->kd_merk; ?></td>
                    <td><font size="2px"><?php echo $a->nama_barang; ?></td>
                    <td><font size="2px"><?php echo $a->cl_nama_merk; ?></td>
                    <td><font size="2px"><?php echo $a->kd_supplier; ?></td>
                    <td><font size="2px"><?php echo $a->perusahaan; ?></td>
                    <td><font size="2px"><?php echo $a->filename; ?></td>
                    <td><font size="2px"><?php echo $a->created_date; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Kode</th>
                    <th>kd_barang</th>
                    <th>so_awal</th>
                    <th>masuk</th> 
                    <th>keluar</th> 
                    <th>so_akhir</th>
                    <th>lk_namagudang</th>
                    <th>lk_namacabang</th>
                    <th>cl_soawal</th>
                    <th>lk_namabarang</th>
                    <th>cl_masuk</th>
                    <th>cl_keluar</th>
                    <th>cl_soakhir</th>
                    <th>cl_satuan</th>
                    <th>spec</th>
                    <th>kd_merk</th>
                    <th>nama_barang</th>
                    <th>cl_nama_merk</th>
                    <th>kd_supplier</th>
                    <th>perusahaan</th>
                    <th>Nama File</th>
                    <th>Tanggal Upload</th> 
                </tr>
            </tfoot>
        </table>
    </div>


    </div>
</div>







