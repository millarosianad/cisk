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

                        <!-- =================================================== Form Helpdesk ========================================== -->
                        <div class="col-md-12 col-xl-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title; ?></h5>
                                </div>
                                <div class="card-block">
                                    <?php echo form_open_multipart($url); ?>

                                    <div class="form-group row">
                                        <label class="col-sm-3">Tanggal Terima</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" name="tanggal_terima" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3">Nama Penerima</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="nama_penerima" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">Nomor LPK</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="no_terima">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">File LPK</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="file_terima" class="form-control" required>
                                            <p style="color: rgb(0 0 0 / 30%);">Format yang diijinkan (jpg,doc,docx,xls,xlsx)</p>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row" style="justify-content: center;">
                                    <input type="hidden" name="signature" value="<?php echo $this->uri->segment('3'); ?>" class="form-control">
                                    <?php echo form_submit('submit', 'Proses Terima Barang', 'class="btn btn-success btn-round center-block" required'); ?>
                                    <?php echo form_close(); ?>
                                </div>
                                <div>&nbsp;</div>
                            </div>
                        </div>


                        