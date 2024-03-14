<?php echo form_open_multipart($url);?>

<<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                <br><br>
                <div class="row">

                    <div class="col-xl-12 col-md-12">
                            <div class="card latest-update-card">
                                <div class="card-header">
                                <h5>Pengajuan Asset</h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="feather icon-maximize full-card"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <ul>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">No. PO</label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" type="text" name="np" placeholder="( CONTOH: 000MPM032020 )" required />
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Nama Toko</label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" type="text" name="nt" required />
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Alamat</label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" type="text" name="alamat" required />
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">No. Telp</label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" type="text" name="telp" required/>
                                                </div>
                                        </div> 
                                        <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Fax</label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" type="text" name="fax" required/>
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Attn</label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" type="text" name="attn" required/>
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Upload</label>
                                                <div class="col-sm-6">
                                                    <input type="file" name="file" id="file" class="form-control" required/>
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Tanggal</label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" type="date" name="tgl" required/>
                                                    <br>
                                                    <?php echo form_submit('submit','Simpan', 'class="btn btn-success"');?>
                                                    <?php echo form_close();?>
                                                </div>
                                        </div>
                                   </ul>
                                </div>
                            </div>
                        </div>