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
                                            <div class="col-xs-3">
                                                <?php 
                                                    $id = $this->session->userdata('id');
                                                    if ($id == '297' || $id == '547') {
                                                        echo form_submit('submit','Update Stock Terbaru dari Web MPI','class="btn btn-primary btn-round btn-sm"');
                                                    }else{
                                                        echo "";
                                                    }
                                                ?>
                                                <?php echo form_close();?>
                                                <a href="<?php echo base_url()."mpi/insert_stock_to_db/" ; ?>   "
                                                    class="btn btn-success btn-round btn-sm" role="button"><span
                                                        class="glyphicon glyphicon-floppy-disk"
                                                        aria-hidden="true"></span> Insert ke Db MPM</a>
                                                <a href="<?php echo base_url()."mpi/export_stock_mpi"; ?>   "
                                                    class="btn btn-warning btn-round btn-sm" role="button"><span
                                                        class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                                    Export (.csv)</a>

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
                                                                    <font size="2px">Stock Cut Off</font>
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">Produk
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">HNA
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">Kemasan
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">ED
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">OnHand
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">GIT
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">BranchName
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach($proses as $x) : ?>
                                                            <tr>
                                                                <td>
                                                                    <font size="2px"><?php echo $x->cut_off; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $x->produk; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $x->hna; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $x->kemasan; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $x->ed; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $x->onhand; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $x->git; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $x->branch_name; ?>
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