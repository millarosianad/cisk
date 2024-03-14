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
                                        <label class="col-sm-3">Tanggal Approval</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" name="tanggal_approval" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">Keterangan dari Principal</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" name="keterangan_principal" id="" cols="30" rows="10"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">File dari Principal</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="file_principal" class="form-control" required>
                                            <p style="color: rgb(0 0 0 / 30%);">Format yang diijinkan (jpg,doc,docx,xls,xlsx)</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Action</label>
                                        <div class="col-sm-9">
                                            <select name="status" class="form-control" required>
                                                <option value="">Pilih</option>
                                                <option value="5">Kirim Barang</option>
                                                <option value="6">Pemusnahan</option>
                                            </select>                                   
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="justify-content: center;">
                                    <input type="hidden" name="signature" value="<?php echo $this->uri->segment('3'); ?>" class="form-control">
                                    <input type="hidden" name="supp" value="<?php echo $this->uri->segment('4'); ?>" class="form-control">
                                    <?php echo form_submit('submit', 'Proses Approval Principal', 'class="btn btn-success btn-round center-block" required'); ?>
                                    <?php echo form_close(); ?>
                                </div>
                                <div>&nbsp;</div>
                            </div>
                        </div>


                        