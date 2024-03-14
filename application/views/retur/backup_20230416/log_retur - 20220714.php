<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <div class="d-inline">
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                </div>
            </div>

        </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?= $title; ?></h5>
                                </div>
                                <div class="card-block">
                                    <a href="<?= base_url() . "retur/pengajuan"; ?>" class="btn btn-dark"
                                        role="button">kembali</a>
                                    <br><br>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">No.Pengajuan</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" type="text" name="username"
                                                            value="<?= $log->no_pengajuan;?>" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Nama</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control user" type="text" name="email"
                                                            value="<?= $log->nama;?>" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Principal</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control user" type="text"
                                                            name="email_finance" value="<?= $log->namasupp; ?>"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Tanggal Pengajuan</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control user" type="text" name="name"
                                                            value="<?= $log->tanggal_pengajuan; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">File Pengajuan</label>
                                                    <div class="col-sm-8">
                                                        <?php if ($log->file == '' ) { ?>
                                                        <input type="text" class="form-control" value="File Tidak Tersedia"
                                                            readonly>
                                                        <?php } else { ?>
                                                        <div class="input-group mb-3">
                                                            <input type="text" class="form-control"
                                                                value="<?= $log->file; ?>" readonly>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-secondary"
                                                                    onclick="window.open('<?= base_url().'assets/file/retur/'.$log->file; ?>','_blank')">Lihat</button>
                                                            </div>
                                                        </div>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">File Principal</label>
                                                    <div class="col-sm-8">
                                                        <?php if ($log->file_principal == '' ) { ?>
                                                        <input type="text" class="form-control" value="File Tidak Tersedia"
                                                            readonly>
                                                        <?php } else { ?>
                                                        <div class="input-group mb-3">
                                                            <input type="text" class="form-control"
                                                                value="<?= $log->file_principal; ?>" readonly>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-secondary"
                                                                    onclick="window.open('<?= base_url().'assets/file/retur/'.$log->file_principal; ?>','_blank')">Lihat</button>
                                                            </div>
                                                        </div>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">File Pengiriman</label>
                                                    <div class="col-sm-8">
                                                        <?php if ($log->file_pengiriman == '' ) { ?>
                                                        <input type="text" class="form-control" value="File Tidak Tersedia"
                                                            readonly>
                                                        <?php } else { ?>
                                                        <div class="input-group mb-3">
                                                            <input type="text" class="form-control"
                                                                value="<?= $log->file_pengiriman; ?>" readonly>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-secondary"
                                                                    onclick="window.open('<?= base_url().'assets/file/retur/'.$log->file_pengiriman; ?>','_blank')">Lihat</button>
                                                            </div>
                                                        </div>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">File Terima</label>
                                                    <div class="col-sm-8">
                                                        <?php if ($log->file_terima == '' ) { ?>
                                                        <input type="text" class="form-control" value="File Tidak Tersedia"
                                                            readonly>
                                                        <?php } else { ?>
                                                        <div class="input-group mb-3">
                                                            <input type="text" class="form-control"
                                                                value="<?= $log->file_terima; ?>" readonly>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-secondary"
                                                                    onclick="window.open('<?= base_url().'assets/file/retur/'.$log->file_terima; ?>','_blank')">Lihat</button>
                                                            </div>
                                                        </div>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">File Pemusnahan</label>
                                                    <div class="col-sm-8">
                                                        <?php if ($log->file_pemusnahan == '' ) { ?>
                                                            <input type="text" class="form-control" value="File Tidak Tersedia" readonly>
                                                        <?php } else { ?>
                                                            <div class="input-group mb-3">
                                                                <input type="text" class="form-control" value="<?= $log->file_pemusnahan; ?>" readonly>
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-outline-secondary" onclick="window.open('<?= base_url().'assets/file/retur/'.$log->file_pemusnahan; ?>','_blank')">Lihat</button>
                                                                </div>
                                                            </div>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Foto Pemusnahan 1</label>
                                                    <div class="col-sm-8">
                                                        <img src="<?= base_url().'assets/file/retur/'.$log->foto_pemusnahan_1; ?>"
                                                            alt="<?= $log->foto_pemusnahan_1; ?>" width="100%">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Foto Pemusnahan 2</label>
                                                    <div class="col-sm-8">
                                                        <img src="<?= base_url().'assets/file/retur/'.$log->foto_pemusnahan_2; ?>"
                                                            alt="<?= $log->foto_pemusnahan_2; ?>" width="100%">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>