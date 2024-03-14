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
                                                                <th width="1">Tanggal Cut Off</font>
                                                                </th>
                                                                <th width="1">Total Stock On Hand</font>
                                                                </th>
                                                                <th width="1">Total GIT</font>
                                                                </th>
                                                                <th width="1">Total Stock (OnHand + GIT)</font>
                                                                </th>
                                                                <th width="1">Total Stock in Value (OnHand + GIT)</font>
                                                                </th>
                                                                <th width="1">Last Update</th>
                                                                <th width="1">Proses Oleh</th>
                                                                <th width="1">Export to CSV</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach($stock as $x) : ?>
                                                            <tr>
                                                                <td><?php echo $x->cut_off; ?></td>
                                                                <td><?php echo number_format($x->onhand); ?></td>
                                                                <td><?php echo number_format($x->git); ?></td>
                                                                <td><?php echo number_format($x->stock); ?></td>
                                                                <td><?php echo "Rp ".number_format($x->stock_value); ?></td>
                                                                <td><?php echo $x->last_update; ?></td>
                                                                <td><?php echo $x->username; ?></td>
                                                                <td><?php
                                                                    echo anchor('mpi/export_stock_mpi/' . $x->tgl, 'Download',array('class' => 'btn btn-warning btn-sm', 'target' => '_blank'));
                                                                    ?>
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