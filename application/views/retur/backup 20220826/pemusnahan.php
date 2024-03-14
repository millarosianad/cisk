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
                                        <label class="col-sm-3">Tanggal Pemusnahan</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" name="tanggal_pemusnahan" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3">Nama PIC Pemusnahan</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="nama_pemusnahan" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Berita Acara Pemusnahan</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="file_pemusnahan" class="form-control" required>
                                            <p style="color: rgb(0 0 0 / 30%);">Format yang diijinkan (jpg,doc,docx,xls,xlsx)</p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Foto Pemusnahan</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="foto_pemusnahan_1" class="form-control" required>
                                            <p style="color: rgb(0 0 0 / 30%);">Format yang diijinkan (jpg,doc,docx,xls,xlsx)</p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Foto Pemusnahan</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="foto_pemusnahan_2" class="form-control" required>
                                            <p style="color: rgb(0 0 0 / 30%);">Format yang diijinkan (jpg,doc,docx,xls,xlsx)</p>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row" style="justify-content: center;">
                                    <input type="hidden" name="signature" value="<?php echo $this->uri->segment('3'); ?>" class="form-control">
                                    <?php echo form_submit('submit', 'Proses Pemusnahan', 'class="btn btn-success btn-round center-block" required'); ?>
                                    <?php echo form_close(); ?>
                                </div>
                                <div>&nbsp;</div>
                            </div>
                        </div>


                        