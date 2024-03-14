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
                        <div class="col-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title; ?></h5>
                                </div>
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-12">
                                            <?php echo form_open($url);?>
                                            <div>
                                                Tanggal Cut Off Stock
                                            </div>
                                            <div class="col-xs-12 col-xl-6">
                                                <input type="date" class='form-control' name="cut_off_stock"
                                                    placeholder="" autocomplete="off">
                                            </div>
                                            <div>
                                                Rata-rata Sales
                                            </div>
                                            <div class="col-xs-12 col-xl-6">
                                                <?php 
                                                    $avg = array(
                                                        '3'  => '3 bulan',
                                                        '6'  => '6 bulan',           
                                                        );
                                                ?>
                                                <?php echo form_dropdown('avg', $avg,'','class="form-control"');?>
                                            </div>
                                            <br>
                                            <div class="col-xs-12 col-xl-6">
                                                <?php echo form_submit('submit','Proses','class="btn btn-primary btn-round btn-sm"');?>
                                                <?php echo form_close();?>
                                                <a href="<?php echo base_url()."mpi/export_monitoring_doi"; ?>"
                                                    class="btn btn-warning btn-round btn-sm" role="button"><span
                                                        class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                                    Export (.csv)</a>
                                            </div>
                                            <?php $no = 1; ?>
                                            <div class="card-block">
                                                <div class="dt-responsive table-responsive">
                                                    <table id="multi-colum-dt"
                                                        class="table table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama Cabang</th>
                                                                <th>Nama Produk</th>
                                                                <th>Total Unit</th>
                                                                <th>AVG Unit</th>
                                                                <th>Cut off Stock</th>
                                                                <th>Stock Onhand (Unit)</th>
                                                                <th>GIT (Unit)</th>
                                                                <th>DOI Onhand (Unit)</th>
                                                                <th>DOI Stock Unit (git+onhand)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach($stock as $x) : ?>
                                                            <tr>
                                                                <td><?php echo $x->nama_cab; ?></td>
                                                                <td><?php echo $x->nama_produk; ?></td>
                                                                <td><?php echo $x->total_unit; ?></td>
                                                                <td><?php echo $x->avg_unit; ?></td>
                                                                <td><?php echo $x->cut_off_stock; ?></td>
                                                                <td><?php echo $x->onhand_unit; ?></td>
                                                                <td><?php echo $x->git_unit; ?></td>
                                                                <td><?php echo $x->doi_onhand_unit; ?></td>
                                                                <td><?php echo $x->doi_stock_unit; ?></td>
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