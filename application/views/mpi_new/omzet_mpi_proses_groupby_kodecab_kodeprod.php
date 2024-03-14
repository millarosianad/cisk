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
                                                Tahun
                                            </div>
                                            <div class="col-6">
                                                <?php 
                                                $year = date("Y");
                                                    $tahun = array(
                                                        "$year"  => "$year"          
                                                        );
                                                ?>
                                                <?php echo form_dropdown('tahun', $tahun,'','class="form-control"');?>
                                            </div>
                                            <div>
                                                Unit / Value
                                            </div>
                                            <div class="col-6">
                                                <?php 
                                                $uv = array(
                                                    '1'  => 'Unit',
                                                    '2'  => 'Value',          
                                                    );
                                            ?>
                                                <?php echo form_dropdown('uv', $uv,'','class="form-control"');?>
                                            </div>

                                            <div>
                                                Group By
                                            </div>
                                            <div class="col-6">

                                                <?php 
                                                $group_by = array(
                                                    '1'  => 'Kode Cabang',
                                                    '2'  => 'Kode Produk',
                                                    '3'  => 'Kode Cabang dan Kode Produk ',          
                                                    );
                                            ?>
                                                <?php echo form_dropdown('group_by', $group_by,'','class="form-control"');?>
                                            </div>

                                            <div>
                                                Tipe Outlet
                                            </div>
                                            <div class="col-6">
                                                <label class="fancy-checkbox">
                                                    <input type="checkbox" name="apotik" value="1"><span> Apotik
                                                        (tanpa
                                                        kimia farma)</span> &nbsp;</span>
                                                </label>
                                                <label class="fancy-checkbox">
                                                    <input type="checkbox" name="kimia_farma" value="1"><span>
                                                        Apotik
                                                        Kimia Farma</span> &nbsp;</span>
                                                </label>
                                            </div>
                                            <br>
                                            <div>
                                                <?php echo form_submit('submit','Proses','class="btn btn-primary btn-round btn-sm"');?>
                                                <?php echo form_close();?>
                                                <a href="<?php echo base_url()."mpi/export_omzet_mpi_groupby_kodecab_kodeprod_temp"; ?>"
                                                    class="btn btn-warning btn-round btn-sm" role="button"><span
                                                        class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                                    Export (.csv)</a>
                                            </div>
                                            <hr>
                                            <?php $no = 1; ?>
                                            <div class="card-block">
                                                <div class="dt-responsive table-responsive">
                                                    <table id="multi-colum-dt"
                                                        class="table table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th width="1">
                                                                    <font size="2px">Cabang
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">ProdukMPI
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">b1
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">t1
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">b2
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">t2
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">b3
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">t3
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">b4
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">t4
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">b5
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">t5
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">b6
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">t6
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">b7
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">t7
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">b8
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">t8
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">b9
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">t9
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">b10
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">t10
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">b11
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">t11
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">b12
                                                                </th>
                                                                <th width="1">
                                                                    <font size="2px">t12
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php foreach($omzets as $omzet) : ?>
                                                            <tr>
                                                                <td>
                                                                    <font size="2px"><?php echo $omzet->nama_cab; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $omzet->namaprod_mpi; ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->b1); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->t1); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->b2); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->t2); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->b3); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->t3); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->b4); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->t4); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->b5); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->t5); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->b6); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->t6); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->b7); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->t7); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->b8); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->t8); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->b9); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->t9); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->b10); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->t10); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->b11); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->t11); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->b12); ?>
                                                                </td>
                                                                <td>
                                                                    <font size="2px">
                                                                        <?php echo number_format($omzet->t12); ?>
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