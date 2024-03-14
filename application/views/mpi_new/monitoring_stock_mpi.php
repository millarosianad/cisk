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
                                            <div>
                                                <?php echo form_submit('submit','Proses','class="btn btn-primary btn-round btn-sm"');?>
                                                <?php echo form_close();?>
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