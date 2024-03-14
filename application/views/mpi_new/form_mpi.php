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
                        <!-- form mpi -->
                        <div class="col-4">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title; ?></h5>
                                </div>
                                <div class="card-block">
                                <?php echo form_open($url);?>
                                    <div>
                                        Periode dari
                                    </div>
                                    <div>
                                        <input type="date" class='form-control' name="from" placeholder=""
                                            autocomplete="off">
                                    </div>

                                    <div>
                                        sampai
                                    </div>
                                    <div>
                                        <input type="date" class='form-control' name="to" placeholder=""
                                            autocomplete="off">
                                    </div>
                                    <br>
                                    <div>
                                        <?php echo form_submit('submit','Proses','class="btn btn-primary btn-round btn-sm"');?>
                                        <?php echo form_close();?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-8">
                            <div class="card comp-card">
                                <div class="card-header">
                                    <h5>History</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="card-block">
                                                <div class="dt-responsive table-responsive">
                                                    <table id="multi-colum-dt"
                                                        class="table table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <font size="2px">No
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Tanggal Invoice
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Proses Oleh
                                                                </th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <?php 
                                                            $no = 1;
                                                            foreach($omzets as $omzet) : ?>
                                                            <tr>
                                                                <td><?php echo $no++; ?></td>
                                                                <td><?php echo $omzet->tgl_invoice; ?></td>
                                                                <td><?php echo $omzet->username; ?></td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>

                                                        <tfoot>
                                                            <tr>
                                                                <th>
                                                                    <font size="2px">No
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Tanggal Invoice
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Proses Oleh
                                                                </th>
                                                            </tr>
                                                        </tfoot>
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