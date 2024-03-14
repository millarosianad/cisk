<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                <a href="<?php echo base_url()."assets_2/view_assets"; ?>  " class="btn btn-primary" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Kembali</a>
                <a href="<?php echo base_url()."assets_2/qrcode/".$this->uri->segment(3).""; ?>  " target="_blank" class="btn btn-success" role="button"><span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span> Generate QR Code</a>
                <br><br>
                <div class="row">

                    <div class="col-xl-6 col-md-12">
                            <div class="card latest-update-card">
                                <div class="card-header">
                                <h5>Detail Asset</h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="feather icon-maximize full-card"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                <?php foreach ($asset as $a) : ?>
                                    <ul>
                                       
                                        <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">No Voucher</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type="text" name="nv" value="<?php echo $a->kode ;?>" readonly />
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Nama Barang</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type="text" name="nb" value="<?php echo $a->namabarang ;?>" readonly />
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">S/N</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type="text" name="sn" value="<?php echo $a->sn ;?>" readonly />
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Jumlah Barang</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type="text" name="jb" value="<?php echo $a->jumlah ;?>" readonly/>
                                                </div>
                                        </div> 
                                        <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Keperluan</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type="text" name="kpr" value="<?php echo $a->untuk ;?>" readonly/>
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Tanggal Payroll</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type="date" name="tp" value="<?php echo $a->tglperol ;?>" readonly />
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Nilai Perolehan</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type="text" name="np" value="Rp. <?php echo number_format($a->np) ;?>" readonly />
                                                </div>
                                        </div>
                                        <?php if ($a->nj >= 1) {?>
                                        <hr>
                                        <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Tanggal Jual</label>
                                                <div class="col-sm-8">
                                                <input class="form-control" type="date" name="tj" value="<?php echo($a->tgljual) ;?>" readonly />
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Nilai Jual</label>
                                                <div class="col-sm-8">
                                                <input class="form-control" type="text" name="nj" value="Rp. <?php echo number_format($a->nj) ;?>" readonly />
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Deskripsi</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type="text" name="deskripsi" value="<?php echo $a->deskripsi;?>" readonly />
                                                </div>
                                        </div>
                                        <?php }?>
                                   </ul>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <div class="col-xl-12 col-md-12">
                            <div class="card latest-update-card">
                                <div class="card-header">
                                <h5>History Mutasi</h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="feather icon-maximize full-card"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card table-card">
                                        <div class="card-block">
                                            <div class="col-sm-12">
                                                <div class="dt-responsive table-responsive">
                                                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                                                        <thead>
                                                            <tr>                
                                                                <th><font size="2px">User</th>
                                                                <th><font size="2px">Nama Barang</th>
                                                                <th><font size="2px">SN</th>
                                                                <th><font size="2px">Tanggal Mutasi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($proses as $a) : ?> 
                                                            <tr>                   
                                                                <td><font size="2px"><?php echo $a->user; ?></td>
                                                                <td><font size="2px"><?php echo $a->barang_mutasi; ?></td>
                                                                <td><font size="2px"><?php echo $a->sn_mutasi; ?></td>
                                                                <td><font size="2px"><?php echo $a->tgl_mutasi; ?></td>
                                                                <?php if ($a->user >0) { ?>
                                                                <td><a href="<?php echo base_url()."assets_2/file/bukti_mutasi/".$a->bukti_upload; ?>"class="btn btn-info btn-sm" role="button" target="_blank">Bukti Mutasi</a></td>
                                                                <?php } ?>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>