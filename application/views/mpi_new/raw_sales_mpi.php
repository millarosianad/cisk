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
                                            </div>

                                            <hr>
                                        </div>
                                        <br>

                                        <div class="col-12">
                                            <h3>Raw Bulanan</h3>
                                            <?php $no = 1; ?>

                                            <div class="card-block">
                                                <div class="dt-responsive table-responsive">
                                                    <table id="multi-colum-dt"
                                                        class="table table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th width="1">
                                                                    <font size="2px">
                                                                        <center>No
                                                                    </font>
                                                                </th>
                                                                <th>
                                                                    <font size="2px">
                                                                        <center>File
                                                                </th>
                                                                <th>
                                                                    <font size="2px">
                                                                        <center>Download
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php foreach($query as $x) : ?>
                                                            <tr>
                                                                <td>
                                                                    <center>
                                                                        <font size="2px"><?php echo $no++; ?></font>
                                                                    </center>
                                                                </td>
                                                                <td>
                                                                    <font size="2px"><?php echo $x->filename; ?>
                                                                    </font>
                                                                </td>
                                                                <td>
                                                                    <center>
                                                                        <?php
                                                                            if ($x->link <> null) {
                                                                                echo anchor(base_url().'assets/file/portal_raw/raw_data/mpi/'.$x->link, 'download',"class='btn btn-primary btn-sm'");
                                                                            }else{
                                                                                echo "belum ada";
                                                                            }                                    
                                                                        ?>
                                                                    </center>
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