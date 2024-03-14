<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>
    <div class="card-block">
    
        <div class="row">
            <div class="col-md-6">
                <?php echo form_open_multipart($url); ?>

                <div class="form-group">
                    <label for="periode_1">Periode Awal</label>
                    <input class="form-control" type="date" name="periode_1" required />
                </div>

                <div class="form-group">
                    <label for="periode_2">Periode Akhir</label>
                    <input class="form-control" type="date" name="periode_2" required />
                    <!-- <input class="form-control" type="text" name="signature" value="<?= $signature; ?>" /> -->
                </div>

                <div class="form-group">
                    <?php echo form_submit('submit', 'Search Raw Data', 'class="btn btn-default"'); ?>
                    <?php echo form_close(); ?>
                </div>  

            </div>
        </div>

    </div>

    <div class="card-block">

        <div class="row">
            <div class="col-md-12">
                <a href="<?= base_url().'mes/export_raw_data/'.$signature ?>" class="btn btn-outline-danger">Export CSV</a>
            </div>
        </div>
    
        <div class="row mt-5">
            <div class="col-md-12">

                <table id="example" class="table table-striped table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th>Tgl Proses</th>
                            <th>No Proses</th>
                            <th>Tgl Invoice</th>
                            <th>No Invoice</th>
                            <th>Buyer</th>
                            <th>Storeid</th>
                            <th>Nama Store</th>
                            <th>Olshopid</th>
                            <th>Nama Olshop</th> 
                            <th>Skuid</th>
                            <th>Qty Sku</th>
                            <th>Nama Sku</th>
                            <th>Productid</th>
                            <th>Nama Product</th>
                            <th>Qty Rule</th>
                            <th>Qty</th>
                            <th>Status Post</th>
                            <th>Tgl Post</th>
                            <th>No Pesanan</th>
                            <th>Tgl Pesanan</th>
                            <th>Harga</th>
                            <th>Discount</th>
                            <th>Rp Discount</th>
                            <th>Bruto</th>
                            <th>Netto</th>
                            <th>No Faktur</th>
                            <th>Tgl Faktur</th>
                            <th>Nilai Faktur</th>
                            <th>Bayar</th>
                            <th>transfer</th>
                            <th>Tgl Bayar</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        foreach ($get_raw_data->result() as $a) : ?>
                        <tr>
                            <td><?= $a->tgl_proses; ?></td>
                            <td><?= $a->no_proses; ?></td>
                            <td><?= $a->tgl_invoice; ?></td>
                            <td><?= $a->no_invoice; ?></td>
                            <td><?= $a->customer; ?></td>
                            <td><?= $a->storeid; ?></td>
                            <td><?= $a->nama_store; ?></td>
                            <td><?= $a->olshopid; ?></td>
                            <td><?= $a->nama_olshop; ?></td> 
                            <td><?= $a->skuid; ?></td>
                            <td><?= $a->qty_sku; ?></td>
                            <td><?= $a->nama_sku; ?></td>
                            <td><?= $a->productid; ?></td>
                            <td><?= $a->nama_product; ?></td>
                            <td><?= $a->qty_rule; ?></td>
                            <td><?= $a->qty; ?></td>
                            <td><?= $a->status_posting; ?></td>
                            <td><?= $a->tgl_posting; ?></td>
                            <td><?= $a->no_pesanan_gudang; ?></td>
                            <td><?= $a->tgl_pesanan_gudang; ?></td>
                            <td><?= $a->harga; ?></td>
                            <td><?= $a->discount; ?></td>
                            <td><?= $a->rp_discount; ?></td>
                            <td><?= $a->bruto; ?></td>
                            <td><?= $a->netto; ?></td>
                            <td><?= $a->no_faktur; ?></td>
                            <td><?= $a->tgl_faktur; ?></td>
                            <td><?= $a->nilai_faktur; ?></td>
                            <td><?= $a->bayar; ?></td>
                            <td><?= $a->transfer; ?></td>
                            <td><?= $a->tgl_bayar; ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>