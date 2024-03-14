<pre>
* Klik nomor invoice untuk melihat detail produk
* Klik status untuk mengubah status transaksi secara manual
* Apps semut gajah secara periodik akan membaca data ini
</pre>

<!-- <a href="<?php echo base_url()."ecommerce/export_csv/transaksi"; ?>" type="button" class="btn btn-md btn-success" data-toggle="modal" data-target="#uploadModal">Export</a> -->
<a href="#" type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#uploadModal">Export

<!-- <a href="#" data-toggle="modal" data-target="#uploadModal">             -->
            </a>

<hr>

<div class="col-12">
    <!-- <button>Filter</button>
    <br> -->
    
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th><font size="2px"><center>TanggalTransaksi</center></th>
                    <th><font size="2px"><center><button>Invoice</button></center></th>
                    <th><font size="2px"><center>Name</center></th>
                    <th><font size="2px"><center>Alamat</center></th>
                    <th><font size="2px"><center>Phone</center></th>
                    <th><font size="2px"><center>Total (Rp)</center></th>
                    <th><font size="2px"><center><button>Update Status</button></center></th>
                </tr>
            </thead>
            <tbody>

                <?php foreach($transaksi as $x) : ?>
                <tr>
                    <td><font size="2px"><?php echo $x->created_at; ?></font></td>
                    <td>
                    
                        <a target = "blank" href="<?php echo base_url()."ecommerce/detail_transaksi/".$x->invoice ?>">
                        <font size='2px'>
                        <?php echo $x->invoice; ?>
                        </font>
                        </a>
                    
                    </td>
                    <td><font size="2px"><?php echo $x->name; ?></font></td>
                    <td><font size="2px"><?php echo $x->address; ?></font></td>
                    <td><font size="2px"><?php echo $x->phone; ?></font></td>
                    <td><font size="2px"><?php echo number_format($x->payment_final); ?></font></td>
                    <td><font size="2px">
                            <?php if($x->status_update_erp == 1){
                                echo "<a href='#' onclick='getEditTransaksi($x->order_id)'>Order Baru</a>";
                            }elseif($x->status_update_erp == 2){
                                echo "<a href='#' onclick='getEditTransaksi($x->order_id)'>Order Terkonfirmasi</a>";
                            }elseif($x->status_update_erp == 3){
                                echo "<a href='#' onclick='getEditTransaksi($x->order_id)'>Order Proses</a>";
                            }elseif($x->status_update_erp == 4){
                                echo "<a href='#' onclick='getEditTransaksi($x->order_id)'>Order Selesai</a>";
                            }else{
                                echo "<a href='#' onclick='getEditTransaksi($x->order_id)'>Order Batal</a>";
                            } ?>
                        </font>
                    </td>
                </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
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


<!-- =============================================== modal ======================================================== -->

<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Export</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <?php echo form_open('ecommerce/export'); ?>

        <div class="col-sm-12">
            <div class="form-inline row">
                <div class="col-sm-2">
                    <label>Periode</label>
                </div>
                <div class="col-sm-10">
                    <input class="form-control" type="date" name="from" required />
                    <input class="form-control" type="date" name="to" required />
                    <!-- <select name="type" class="form-control" required>
                        <option value="1">breakdown by subbranch</option>
                        <option value="2">row data</option>
                    </select> -->
                </div>
            </div>
        </div>

        <div class="col-sm-12 mt-3">
            <div class="form-inline row">
                <div class="col-sm-2">
                Format
                </div>
                <div class="col-sm-10">
                    <select name="format" class="form-control" required>
                        <option value="1">By Subbranch</option>
                        <option value="2">Raw Data</option>
                    </select>
                    
                </div>
            </div>
        </div>

        <div class="col-sm-12 mt-4">
            <div class="form-inline row">
                <div class="col-sm-2">
                
                </div>
                <div class="col-sm-10">
                    
                    <?php echo form_submit('submit','Export (csv)','class="btn btn-primary btn-md"');?>          
                </div>
            </div>
        </div>





        <?php echo form_close(); ?>
        <br>

        <!-- <?php echo form_open_multipart('ecommerce/import_mapping_sektor'); ?>
        <div class="col-sm-12">
            <div class="form-inline row">
                <div class="col-sm-12">            
                    <input type="file" name="file" class="form-control" required>
                    <?php echo form_submit('submit','Update Data','class="btn btn-primary btn-sm"');?>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?> -->

    
    </div>
  </div>
</div>