<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }
</style>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="title">
                <div class="row">
                    <div class="col">
                        <h4>Manage api warung pintar</h4>
                    </div>
                </div>
            </div>
            <hr>
            <div class="dt-responsive table-responsive mt-2">
                <!-- <table id="multi-colum-dt" class="table table-striped table-bordered nowrap"> -->
                <table id="multi-colum-dt" class="table table-columned">
                    <thead>
                        <tr>
                            <th width="1">No</th>
                            <th>Kategori</th>
                            <th>EndPoint</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_warpin_action as $key) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?php echo $key->kategori; ?></td>
                                <td><?php echo $key->endpoint; ?></td>
                                <td><a href="<?php echo base_url()  ?><?php echo $key->endpoint; ?>"  target="_blank" class="btn btn-primary btn-sm">Proses</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="title">
                <div class="row">
                    <div class="col">
                        <h4>Warpin Order</h4>
                    </div>
                </div>
            </div><hr>
            <div class="dt-responsive table-responsive mt-2">
                <!-- <table id="multi-colum-dt" class="table table-striped table-bordered nowrap"> -->
                <table id="table-dc" class="table table-columned">
                    <thead>
                        <tr>
                            <th width="1">No</th>
                            <th>Client SO Number</th>
                            <!-- <th>Merchant ID</th> -->
                            <th>Merchant Name</th>
                            <!-- <th>Merchant Phone</th> -->
                            <!-- <th>Merchant Shop ID</th> -->
                            <th>Merchant Shop Name</th>
                            <th>Store ID</th>
                            <th>Created At</th>
                            <!-- <th>#</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_warpin_order as $key) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                <a href="<?php echo base_url()  ?>warpin/order_detail/<?php echo $key->signature; ?>" target="_blank"><?php echo $key->client_so_number; ?></a>
                                </td>
                                <!-- <td><?php echo $key->merchant_id; ?></td> -->
                                <td><?php echo $key->merchant_name; ?></td>
                                <!-- <td><?php echo $key->merchant_phone; ?></td> -->
                                <!-- <td><?php echo $key->merchant_shop_id; ?></td> -->
                                <td><?php echo $key->merchant_shop_name; ?></td>
                                <td><?php echo $key->store_id; ?></td>
                                <td><?php echo $key->created_at; ?></td>
                                <!-- <td><a href="<?php echo base_url()  ?>warpin/order_detail/<?php echo $key->signature; ?>"  target="_blank" class="btn btn-primary">Detail</a></td> -->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="title">
                <div class="row">
                    <div class="col">
                        <h4>Coverage warpin</h4>
                    </div>
                </div>
            </div><hr>
            <div class="dt-responsive table-responsive mt-2">
                <!-- <table id="multi-colum-dt" class="table table-striped table-bordered nowrap"> -->
                <table id="table-karyawan" class="table table-columned">
                    <thead>
                        <tr>
                            <th width="1">No</th>
                            <th>Site Code</th>
                            <th>Branch</th>
                            <th>SubBranch</th>
                            <th>Coverage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_warpin_coverage as $key) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?php echo $key->site_code; ?></td>
                                <td><?php echo $key->branch_name; ?></td>
                                <td><?php echo $key->nama_comp; ?></td>
                                <td><?php echo $key->coverage; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="title">
                <div class="row">
                    <div class="col">
                        <h4>Log Warpin Api</h4>
                    </div>
                </div>
            </div><hr>
            <div class="dt-responsive table-responsive mt-2">
                <!-- <table id="multi-colum-dt" class="table table-striped table-bordered nowrap"> -->
                <table id="table-asset" class="table table-columned">
                    <thead>
                        <tr>
                            <th width="1">No</th>
                            <th>Message Success</th>
                            <th>Message Error</th>
                            <th>EndPoint</th>
                            <th>CreatedAt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_warpin_log as $key) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?php echo $key->message_success; ?></td>
                                <td><?php echo $key->message_error; ?></td>
                                <td><?php echo $key->endpoint; ?></td>
                                <td><?php echo $key->created_at; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
