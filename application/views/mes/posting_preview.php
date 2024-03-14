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

                <div class="form-group">
                    <label for="periode_1">Olshop</label>
                    <input class="form-control" type="text"  value="<?= $olshopid. ' | '.$nama_olshop; ?>">
                </div>
                <div class="form-group">
                    <label for="periode_1">Store</label>
                    <input class="form-control" type="text"  value="<?= $storeid. ' | '.$nama_store; ?>">
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
                            <th width="50%">No Proses</th>
                            <th>Invoice</th>
                            <th>SKU ID</th>
                            <th>QTY SKU</th>
                            <th>Product ID</th>
                            <th>Nama Product</th>
                            <th>QTY Rule</th>
                            <th>QTY</th>
                            <th>CreatedBy</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_posting_preview->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->tgl_proses; ?></td>
                            <td><?= $a->no_proses; ?></td>
                            <td><?= $a->no_invoice; ?></td>
                            <td><?= $a->skuid; ?></td>
                            <td><?= $a->qty_sku; ?></td>
                            <td><?= $a->productid; ?></td>
                            <td><?= $a->nama_product; ?></td>
                            <td><?= $a->qty_rule; ?></td>
                            <td><?= $a->qty; ?></td>
                            <td><?= $a->username; ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

    <?php 
        if($status_posting == 1){ ?>
            <a href="#" class="btn btn-outline-warning">Sudah Posting at <?= $tgl_posting; ?></a>
        <?php }else{ ?>
            <a href="<?= base_url().'mes/posting_submit/'.$signature ?>" class="btn btn-warning">Cek kembali data di atas. Jika sudah oke Klik disini</a>
        <?php } 
    ?>

</div>

<script>
    $(document).ready(function () {
        $('#example').DataTable({
            "pageLength": 100,
            "aLengthMenu": [
                [10, 20, -1],
                [10, 20, "All"]
            ],
        });
    });
</script>