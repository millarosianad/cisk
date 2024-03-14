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
                                        <div class="col-xs-12 col-xl-6">
                                            <?php echo form_open($url);?>
                                            <div class="col-xs-2">
                                                Custom Periode dari
                                            </div>

                                            <div class="col-xs-3">
                                                <input type="date" class='form-control' id="datepicker2" name="from"
                                                    placeholder="" autocomplete="off">
                                            </div>

                                            <div class="col-xs-1">
                                                sampai
                                            </div>
                                            <div class="col-xs-3">
                                                <input type="date" class='form-control' id="datepicker" name="to"
                                                    placeholder="" autocomplete="off">
                                            </div>

                                            <div class="col-xs-11">
                                                &nbsp;
                                            </div>

                                            <div class="col-xs-2">
                                                Database
                                            </div>
                                            <div class="col-xs-3">

                                                <?php 
                                                    $database = array(
                                                        '1'  => 'Lokal MPM',
                                                        '2'  => 'Live MPI (hanya bisa 2 bulan terakhir)',           
                                                        );
                                                ?>
                                                <?php echo form_dropdown('database', $database,'','class="form-control"');?>
                                            </div>
                                            <br>
                                            <div class="col-xs-3">
                                                
                                                <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
                                                <?php echo form_close();?>
                                                <a href="<?php echo base_url()."mpi/export_omzet_mpi"; ?>   " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export csv</a>
                                            </div>

                                            <hr>
                                        </div>
                                        <br>

                                        <div class="col-12">
                                            <?php $no = 1; ?>

                                            <div class="card-block">
                                                <div class="dt-responsive table-responsive">
                                                    <table id="multi-colum-dt"
                                                        class="table table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th width="1">
                                                                    <font size="2px">Tgl Invoice</font>
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">Cabang
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">Jenis
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">NoInvoice
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">NamaLang
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">SalesType
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">NamaProduk
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">Kemasan
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">Qty
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">Sales
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach($omzets as $omzet) : ?>
                                                            <tr>
                                                                <td>
                                                                    <font size="2px"><?php echo $omzet->tgl_invoice; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $omzet->nama_cab; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $omzet->jenis; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $omzet->no_invoice; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $omzet->namalang; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $omzet->sales_type; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $omzet->nama_produk; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $omzet->kemasan; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $omzet->banyak; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->hna * $omzet->banyak); ?>
                                                                </td>
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