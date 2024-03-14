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

    <!-- <div class="card-block">    
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="periode_1">Olshop</label>
                    <input class="form-control" type="text"  value="<?= $olshop; ?>">
                </div>
                <div class="form-group">
                    <label for="periode_1">Store</label>
                    <input class="form-control" type="text"  value="<?= $store; ?>">
                </div>                
                <div class="form-group">
                    <label for="periode_1">Nomor Pesanan Gudang</label>
                    <input class="form-control" type="text"  value="<?= $no_pesanan_gudang; ?>">
                </div>
            </div>
        </div>
    </div> -->

    <div class="card-block">    
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="periode_1">Olshop</label>
                    <input class="form-control" type="text" value="<?= $olshop; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="periode_1">Store</label>
                    <input class="form-control" type="text" value="<?= $store; ?>" readonly>
                </div>                
                <div class="form-group">
                    <label for="periode_1">Nomor Pesanan Gudang</label>
                    <input class="form-control" type="text" value="<?= $no_pesanan_gudang; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="periode_1">Tanggal Faktur SDS</label>
                    <input class="form-control" type="text" value="<?= $tgl_faktur; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="periode_1">Nomor Faktur SDS</label>
                    <input class="form-control" type="text"  value="<?= $no_faktur; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="periode_1">Nilai Faktur</label>
                    <input class="form-control" type="text"  value="<?= $nilai_faktur; ?>" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <table id="example" class="table table-striped table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Product Id</th>
                            <th width="80%">Nama Product</th>
                            <th>QTY</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($detail->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->productid; ?></td>
                            <td><?= $a->nama_product; ?></td>
                            <td><?= $a->qty; ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
            
        </div>
        
        <hr>
        
        <div class="row">
        </div>

        <div class="row">
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tgl_faktur">Masukkan Tanggal Faktur SDS</label>
                    <input class="form-control" id="tgl_faktur" type="date" name="tgl_faktur">
                </div>
                <div class="form-group">
                    <label for="no_faktur">Masukkan Nomor Faktur</label>
                    <input class="form-control" id="no_faktur" type="text" name="no_faktur">
                </div>
                <div class="form-group">
                    <label for="nilai_faktur">Masukkan Nilai Faktur</label>
                    <input class="form-control" id="nilai_faktur" type="text" name="nilai_faktur">
                    <input class="form-control" type="hidden" name="signature" value="<?= $signature ?>">
                </div>
            </div>
        </div>


    </div>

    

    <?php 
        if($status_konfirmasi_faktur == 1){ ?>
            <a href="#" class="btn btn-outline-warning">Sudah Konfirmasi Faktur at <?= $konfirmasi_faktur_at; ?></a>
        <?php }else{ 
            echo form_submit('submit', 'Proses Konfirmasi Faktur', 'class="btn btn-warning"'); 
            echo form_close(); ?>
        <?php } 
    ?>

    <br><br><br><br>
        
</div>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>