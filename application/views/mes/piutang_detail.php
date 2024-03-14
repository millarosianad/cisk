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
<?php echo form_open_multipart($url); ?>
<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>

    <div class="card-block">    
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="periode_1">tgl_pesanan_gudang</label>
                    <input class="form-control" type="text"  value="<?= $tgl_pesanan_gudang; ?>">
                </div>
                <div class="form-group">
                    <label for="periode_1">No Pesanan Gudang</label>
                    <input class="form-control" type="text"  value="<?= $no_pesanan_gudang; ?>">
                </div>                
                <div class="form-group">
                    <label for="periode_1">Email At</label>
                    <input class="form-control" type="text"  value="<?= $email_at; ?>">
                </div>
                <div class="form-group">
                    <label for="email_to">Email To</label>
                    <input class="form-control" type="text" name="email_to" value="<?= $email_to ? $email_to : 'suffy.yanuar@gmail.com'; ?>">
                    <input class="form-control" type="hidden" name="signature" value="<?= $signature ?>">
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
                            <th>Product Id</th>
                            <th width="100%">Nama Product</th>
                            <th>Qty</th>
                            <th>No Faktur</th>
                            <th>Tgl Faktur</th>
                            <th>Nilai Faktur</th>
                            <th>Tgl Bayar</th>
                            <th>Bayar</th>
                            <th>Transfer</th>
                            <th>BuktiTransfer</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_piutang_detail->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->productid; ?></td>
                            <td><?= $a->nama_product; ?></td>
                            <td><?= $a->qty; ?></td>
                            <td><?= $a->no_faktur; ?></td>
                            <td><?= $a->tgl_faktur; ?></td>
                            <td><?= $a->nilai_faktur; ?></td>
                            <td><?= $a->tgl_bayar; ?></td>
                            <td><?= $a->bayar; ?></td>
                            <td><?= $a->transfer; ?></td>
                            <td></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>        
    </div>

    <?php echo form_submit('submit', 'Email ke JKT', 'class="btn btn-warning"'); ?>
    <?php echo form_close(); ?>
        
</div>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>