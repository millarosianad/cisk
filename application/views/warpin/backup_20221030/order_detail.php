<style>
    th {
        font-size: 12px;
    }
    td {
        font-size: 12px;
    }
</style>
<?php 
    // var_dump($get_warpin_order_detail);
    foreach ($get_warpin_order_detail as $key) {
        $client_so_number = $key->client_so_number;
        $merchant_name = $key->merchant_name;
        $merchant_shop_name = $key->merchant_shop_name;
        $merchant_shop_address = $key->merchant_shop_address;
        $merchant_phone = $key->merchant_phone;
        $merchant_shop_postal_code = $key->merchant_shop_postal_code;
        $status_erp = $key->status_erp;
        $nama_status_erp = $key->nama_status_erp;
    }
?>

<?php echo form_open($url); ?>




<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="title">
                <div class="row">
                    <div class="col">
                        <h4>Detail Order</h4>
                    </div>
                    <?php $signature = $this->uri->segment('3'); ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="invoice apps : <?= $client_so_number; ?>">
                            </div>
                            <div class="col-md-6">
                            <input type="text" class="form-control" value="merchant shop | name : <?= $merchant_name; ?> | <?= $merchant_shop_name; ?>">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="phone : <?= $merchant_phone; ?>">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="address : <?= $merchant_shop_address; ?>">
                            </div>
                            <div class="col-md-3">
                            <input type="text" class="form-control" value="<?= $merchant_shop_postal_code; ?>">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <input type="text" class="form-control" value="STATUS ORDERS : <?= $status_erp." / ".$nama_status_erp; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
             
                
                <hr>
            </div>
            <div class="dt-responsive table-responsive mt-2">
                <!-- <table id="multi-colum-dt" class="table table-striped table-bordered nowrap"> -->
                <table id="multi-colum-dt" class="table table-columned">
                    <thead>
                        <tr>
                            <th width="1">No</th>
                            <th>Product Id</th>
                            <th>SKU</th>
                            <th>Namaprod</th>
                            <th>Quantity</th>
                            <th>UOM</th>
                            <th>Price Unit</th>
                            <th>Last Status</th>
                            <th>Manual Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php $no = 1; ?>
                        <?php foreach ($get_warpin_order_detail as $key) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?php echo $key->product_id; ?></td>
                                <td><?php echo $key->sku; ?></td>
                                <td><?php echo $key->namaprod; ?></td>
                                <td><?php echo $key->product_quantity; ?></td>
                                <td><?php echo $key->uom; ?></td>
                                <td><?php echo $key->price_unit; ?></td>
                                <td><?php echo $key->nama_status_erp; ?></td>
                                <td>
                                    <select name="status_product_manual[]" class="form-control">
                                        <option value=""> - Pilih Status - </option>
                                        <option value=NULL>terpenuhi</option>
                                        <option value="1">cancel</option>
                                    </select>                                  
                                </td>
                                <input type="hidden" name="signature" value="<?= $key->signature; ?>">
                                <input type="hidden" name="id[]" value="<?= $key->id; ?>">
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <select name="status_transaksi_manual" class="form-control">
                    <option value=""> - Pilih Status Transaksi Manual - </option>
                    <option value="5">Delivery</option>
                    <option value="6">Terkirim</option>
                    <option value="7">Batal</option>
                    <option value="8">Pending</option>
                </select>  
            </div>
            <div class="col-md-5">
                <?php echo form_submit('submit', 'Proses Status Manual', 'class="btn btn-primary" required'); ?>
                <?php echo form_close(); ?>
            </div>
                
        
        </div>

        


    </div>
</div>



<hr>

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="title">
                <div class="row">
                    <div class="col">
                        <h4>Log post SDS => MPM</h4>
                    </div>
                </div>
            </div>

            <hr>
            <div class="dt-responsive table-responsive mt-2">
                <!-- <table id="multi-colum-dt" class="table table-striped table-bordered nowrap"> -->
                <table id="table-listorder" class="table table-columned">
                    <thead>
                        <tr>
                            <!-- <th width="1">No</th> -->
                            <!-- <th>Invoice Aplikasi</th>
                            <th>SignatureMpm</th>
                            <th>Invoice Sds</th>
                            <th>Status Erp</th>
                            <th>Nama Status</th>
                            <th>Payment Total</th> -->
                            <th width="1">status</th>
                            <th width="1">Kodeprod</th>
                            <th width="1">Namaprod</th>
                            <th width="1">qty pcs</th>
                            <th width="1">qty jual</th>
                            <th width="1">hna</th>
                            <th width="1">total harga</th>
                            <th>Alasan</th>
                            <th width="1">TanggalUpdate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_erp_order_status as $key) : ?>

                            <?php 
                                if ($key->status_cancel == 1) {
                                    $bg_aktif = 'style="background-color: red;"';
                                } else{
                                    $bg_aktif = '';
                                }
                            ?>

                            <tr>
                                <!-- <td><?= $no++; ?></td> -->
                                <!-- <td><?php echo $key->invoice_aplikasi; ?></td>
                                <td><?php echo $key->signature_mpm; ?></td>
                                <td><?php echo $key->invoice_sds; ?></td>
                                <td><?php echo $key->status_erp; ?></td>
                                <td><?php echo $key->nama_status_erp; ?></td>
                                <td><?php echo $key->payment_total; ?></td> -->
                                <td <?= $bg_aktif; ?>><?php echo $key->status_erp . " (" . $key->nama_status_erp . ")"; ?></td>
                                <td <?= $bg_aktif; ?>><?php echo $key->kodeprod; ?></td>
                                <td <?= $bg_aktif; ?>><?php echo $key->namaprod; ?></td>
                                <td <?= $bg_aktif; ?>><?php echo $key->qty_pcs; ?></td>
                                <td <?= $bg_aktif; ?>><?php echo $key->qty_jual; ?></td>
                                <td <?= $bg_aktif; ?>><?php echo $key->hna; ?></td>
                                <td <?= $bg_aktif; ?>><?php echo $key->total_harga; ?></td>
                                <td <?= $bg_aktif; ?>><?php echo $key->alasan_cancel; ?></td>
                                <td <?= $bg_aktif; ?>><?php echo $key->tanggal_update; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <a href="<?php echo base_url()  ?>broadcast/order_confirmation/<?php echo $signature; ?>" target="_blank" class="btn btn-danger">force POST MPM to WARPIN</a>
    </div>
</div>



<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="title">
                <div class="row">
                    <div class="col">
                        <h4>Tabel Status Order API</h4>
                    </div>
                </div>
            </div>

            <hr>
            <div class="dt-responsive table-responsive mt-2">
                <!-- <table id="multi-colum-dt" class="table table-striped table-bordered nowrap"> -->
                <table id="table-listorder" class="table table-columned">
                    <thead>
                        <tr>
                            <th width="1">Status</th>
                            <th>Nama</th>
                            <th>SDS</th>
                            <th>WARPIN</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>transfered</td>
                            <td>sds sukses get data dari api dan insert ke temporary</td>
                            <td> - </td>
                            <td>SPOxxx / kode_dokumen</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>posting</td>
                            <td>sds posting</td>
                            <td>order.confirmed</td>
                            <td>SNRxxx</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>printed</td>
                            <td>sds print</td>
                            <td> - </td>
                            <td> - </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>do</td>
                            <td>sds rekap do</td>
                            <td> - </td>
                            <td>SPBxxx</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>delivery</td>
                            <td>sds cetak sj</td>
                            <td>order.delivery</td>
                            <td>SJLxxx</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>terkirim</td>
                            <td>ekspedisi update status 'terkirim' di sds </td>
                            <td>order.received.full</td>
                            <td>tgl_kirim dari t_ekspedisi</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>batal</td>
                            <td>toko menolak</td>
                            <td>order.canceled</td>
                            <td>alasan</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>pending</td>
                            <td>pending pengiriman</td>
                            <td> - </td>
                            <td>alasan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open('ecommerce/update_transaksi'); ?>
                <input type="text" class="form-control" id="order" name="order" hidden>
                <div class="row">
                    <div class="col-4">
                        Status
                    </div>
                    <div class="col-8">
                        <select class="form-control" name="status">
                            <option value="1">Order Baru</option>
                            <option value="2">Order Terkonfirmasi</option>
                            <option value="3">Order Proses</option>
                            <option value="4">Order Selesai</option>
                            <option value="10">Order Batal</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success btn-round center-block btn-sm" required'); ?>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function getEditTransaksi(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('ecommerce/get_transaksi')?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.edit);
                // $('#loadingImage').hide();
                $("#edit").modal() // Buka Modal
                $('#order').val(params) // parameter
            }
        });
    }
</script>