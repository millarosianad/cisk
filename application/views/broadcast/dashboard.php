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
                        <div class="col-md-12 col-xl-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title; ?></h5>
                                </div>
                                <div class="card-block">
                                    <?php echo form_open_multipart($url); ?>
                                    <div class="form-group row">
                                        <label class="col-sm-2">Isi Broadcast</label>
                                        <div class="col-sm-5">
                                            <textarea name="message" class="form-control" rows="5"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2"></label>
                                        <div class="col-sm-5">
                                            <?php echo form_submit('submit', 'Send', 'class="btn btn-success"'); ?>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- History -->

                        <div class="col-md-12 col-xl-12">
                            <div class="card comp-card">
                                <div class="card-header">
                                    <h5 class="mb-1">Contact List</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="card-block">
                                                <div class="dt-responsive table-responsive">
                                                    <div>
                                                        <button type="button" class="btn btn-primary mb-3"
                                                            data-toggle="modal" data-target="#import">
                                                            Import Data
                                                        </button>
                                                        <a href="<?= base_url().'broadcast/clear_contact'; ?>" type="button" class="btn btn-danger mb-3">Clear Contact</a>
                                                    </div>
                                                    <hr>
                                                    <table id="multi-colum-dt"
                                                        class="table table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th width="1%">No</th>
                                                                <th width="50%">Nama</th>
                                                                <th width="40%">No WA</th>
                                                                <th width="40%">created_at</th>
                                                                <th width="1%">#</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 1;    
                                                            foreach ($get_contact as $c) : 
                                                            ?>
                                                            <tr>
                                                                <td><?= $no++; ?></td>
                                                                <td><?= $c->nama; ?></td>
                                                                <td><?= $c->no_wa; ?></td>
                                                                <td><?= $c->created_at; ?></td>
                                                                <td></td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('broadcast/modal_import'); ?>