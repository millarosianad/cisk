<?php 
    $interval=date('Y')-2019;
    $year=array();
    $year['2020']='2020';
    for($i=1;$i<=$interval;$i++)
    {
        $year[''.$i+2019]=''.$i+2019;
    }
?>

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
                                        Di Masukkan ke dalam tahun
                                    </div>
                                    <div>
                                        <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
                                    </div>

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
                                        <a href="<?php echo base_url()."mpi/insert_mpi_to_db/" ; ?>   "
                                            class="btn btn-success btn-round btn-sm" role="button"><span
                                                class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                            Insert ke Db MPM</a>
                                        <a href="<?php echo base_url()."mpi/export_omzet_mpi"; ?>   "
                                            class="btn btn-warning btn-round btn-sm" role="button"><span
                                                class="glyphicon glyphicon-print" aria-hidden="true"></span>Export (.csv) </a>

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
                                                            <?php 
                                                            $no = 1;
                                                            foreach($omzets as $omzet) : ?>
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

                                                        <tfoot>
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