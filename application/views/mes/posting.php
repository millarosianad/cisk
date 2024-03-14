<style>

td {
  text-align: left;
  font-size: 12px;
}

th {
  text-align: left;
  font-size: 13px;
}

</style>
<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>
    <div class="card-block">
    
        <div class="row">
            <div class="col-md-6">
                <?php echo form_open_multipart($url); ?>

                <div class="form-group">
                    <label for="periode_1">Periode Awal Transaksi</label>
                    <input class="form-control" type="date" name="periode_1" required />
                </div>

                <div class="form-group">
                    <label for="periode_2">Periode Akhir Transaksi</label>
                    <input class="form-control" type="date" name="periode_2" required />
                </div>

                <div class="form-group">
                    <?php echo form_submit('submit', 'Search Transaksi', 'class="btn btn-default"'); ?>
                    <?php echo form_close(); ?>
                </div>  

            </div>
        </div>

    </div>

    <div class="card-block">
    
        <div class="row">
            <div class="col-md-12">

                <table id="example" class="table table-striped table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Proses</th>
                            <th width="100%">No Proses</th>
                            <th>Store - Olshop</th>
                            <th>Count Invoice</th>
                            <th>Status Posting</th>
                            <th>CreatedBy</th>
                            <th><center>#</center></th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_transaksi->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->tgl_proses; ?></td>
                            <td><?= $a->no_proses; ?></td>
                            <td><?= $a->nama_store.' - '.$a->nama_olshop; ?></td>
                            <td><?= $a->count_invoice; ?></td>
                            <td><?= $a->status_posting; ?></td>
                            <td><?= $a->username; ?></td>
                            <td>
                                <a href="<?= base_url().'mes/posting_preview/'.$a->signature ?>" class="btn btn-outline-warning">Preview Posting</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <hr>

    <div class="card-block">
        <div class="row mb-2 text-center">
            <div class="col-md-12">
            Keranjang Posting
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-12">

                <table id="example2" class="table table-striped table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Posting</th>
                            <th width="20%">No Proses</th>
                            <th>No Pesanan Gudang</th>
                            <th>Olshop</th>
                            <th>Store</th>
                            <th>Product Id</th>
                            <th width="20%">Nama Product</th>
                            <th>Qty</th>
                            <th>Posting by</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_proses_posting->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->tgl_posting; ?></td>
                            <td><?= $a->no_proses; ?></td>
                            <td><?= $a->no_pesanan_gudang; ?></td>
                            <td><?= $a->nama_olshop; ?></td>
                            <td><?= $a->nama_store; ?></td>
                            <td><?= $a->productid; ?></td>
                            <td><?= $a->nama_product; ?></td>
                            <td><?= $a->qty; ?></td>
                            <td><?= $a->username; ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/mes_store') ?>',
        data: '',
        success: function(hasil_store) {
            $("select[name = store]").html(hasil_store);
        }
    });
</script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/mes_olshop') ?>',
        data: '',
        success: function(hasil_olshop) {
            $("select[name = olshop]").html(hasil_olshop);
        }
    });
</script>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
        $('#example2').DataTable({
            "pageLength": 10,
            "aLengthMenu": [
                [10, 20, -1],
                [10, 20, "All"]
            ],"ordering": false
        });
    });
</script>