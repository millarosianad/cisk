<div class="content-body">

            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Home</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $title; ?></h4>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>     
                                                <th>Tanggal PO</th>                                           
                                                <th>No PO</th>
                                                <th>Branch</th>
                                                <th>SubBranch</th>
                                                <th>Company</th>
                                                <th>Tipe</th>
                                                <th>Total Unit</th>
                                                <th>Total Value</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <!-- <?php    var_dump($get_po); ?> -->
                                        <?php foreach ($get_po as $key) { ?>
                                            <tr>                           
                                                <td><?php echo $key->tglpo; ?></td>                     
                                                <td><?php echo $key->nopo; ?></td>
                                                <td><?php echo $key->branch_name; ?></td>
                                                <td><?php echo $key->nama_comp; ?></td>
                                                <td><?php echo $key->company; ?></td>
                                                <td><?php echo $key->tipe; ?></td>
                                                <td><?php echo number_format($key->u); ?></td>
                                                <td><?php echo number_format($key->v); ?></td>                                                
                                                <td>
                                                    <a href="<?php echo base_url()."mhi/tambah_asn/".$key->id ?>" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-humburger">input asn</a>

                                                </td>
                                            </tr>
                                        <?php } ?>
                                            
                                        <?php  ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Tanggal PO</th>                                           
                                                <th>No PO</th>
                                                <th>Branch</th>
                                                <th>SubBranch</th>
                                                <th>Company</th>
                                                <th>Tipe</th>
                                                <th>Total Unit</th>
                                                <th>Total Value</th>
                                                <th>#</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #/ container -->
        </div>

