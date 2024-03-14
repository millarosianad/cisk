<div class="col-sm-10">
    - periode : <b> <?php echo $periode_1.' s/d '.$periode_2; ?> </b> <br>
    - Kodeprod : <b> <?php echo $kodeprod; ?> <br>
    - Breakdown : Class, Type, Kodeproduk<hr>
    Informasi !! : Halaman ini hanya menampilkan data Omzet DP (<?php echo $judul?>). Silahkan klik Export
    <br>
    <br>
    <a href="<?php echo base_url()."outlet_transaksi/outlet_transaksi_ytd"; ?>"class="btn btn-dark" role="button">
                kembali</a>
    <a href="<?php echo base_url()."outlet_transaksi/export_outlet_transaksi_ytd_kodesalur_kode_type_kodeprod"; ?>" class="btn btn-warning" role="button">export (.csv)</a>
    
    </div>
    <br>
    </div>

    <div class="card table-card">
        <div class="card-header">
        <div class="card-block">
        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">No</font></th>   
                            <th width="1"><font size="2px">SubBranch</font></th>
                            <th width="1"><font size="2px">Kodeprod</font></th>
                            <th width="1"><font size="2px">Namaprod</font></th>
                            <th width="1"><font size="2px">Class</font></th>
                            <th width="1"><font size="2px">Type</font></th>
                            <th width="1"><font size="2px">Nama Type</font></th>
                            <th width="1"><font size="2px">YTD</font></th> 
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; ?>
                    <?php foreach($proses as $x) : ?>
                        <tr>                      
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>
                            <td><font size="2px">
                                <?php 
                                    echo $x->nama_comp.' (';
                                    echo $x->kode_comp.') ';
                                ?>                            
                            </td>
                            <td><font size="2px"><?php echo $x->kodeprod; ?></td>
                            <td><font size="2px"><?php echo $x->namaprod; ?></td>
                            <td><font size="2px"><?php echo $x->jenis; ?></td>
                            <td><font size="2px"><?php echo $x->kode_type; ?></td>
                            <td><font size="2px"><?php echo $x->nama_type; ?></td>
                            <td><font size="2px"><?php echo $x->ytd; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                            <tr>                
                            <th width="1"><font size="2px">No</font></th>   
                            <th width="1"><font size="2px">SubBranch</font></th>
                            <th width="1"><font size="2px">Kodeprod</font></th>
                            <th width="1"><font size="2px">Namaprod</font></th>
                            <th width="1"><font size="2px">Class</font></th>
                            <th width="1"><font size="2px">Type</font></th>
                            <th width="1"><font size="2px">Nama Type</font></th>
                            <th width="1"><font size="2px">YTD</font></th> 
                            </tr>
                    </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>