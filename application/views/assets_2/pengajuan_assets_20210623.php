<?php echo form_open($url);?>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                <br><br>
                <div class="row">
                    <div class="col-xl-12">
                        <a href="<?php echo base_url()."assets/view_pengajuan"; ?>  " class="btn btn-info" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Kembali</a>
                    <hr>
                    </div>

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
                                    <!-- <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">No_Po</label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" type="text" name="np" readonly />
                                                </div>
                                        </div> -->
                                        <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Nama Barang</label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" type="text" name="nb" required />
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Tipe</label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" type="text" name="tipe" required />
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Jumlah Barang</label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" type="text" name="jb" required/>
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Harga</label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" type="text" name="harga" required/>
                                                </div>
                                        </div>  
                                        <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"></label>
                                                <div class="col-sm-6">
                                                    <div class="checkbox-color checkbox-primary">
                                                        <input id="checkbox1" type="checkbox"  name="tax" value="10">
                                                        <label for="checkbox1">
                                                            Tax
                                                        </label>
                                                    </div>
                                                <br>
                                                <?php echo form_submit('submit','Tambah', 'class="btn btn-success"');?>
                                                <?php echo form_close();?>
                                        </div>
                                   </ul>
                                </div>
                            </div>
                        </div>