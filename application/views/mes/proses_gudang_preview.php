
 

<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>

    

    <div class="card-block">

        <div class="row mb-2 text-center">
            <div class="col-md-12">
                Sebelum
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-12">

                <table class="table table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th width="1%">No</th>
                            <th width="10%">No Proses</th>
                            <th width="10%">No Pesanan Gudang</th>
                            <th width="10%">ProductId</th>
                            <th width="10%">Nama Product</th>
                            <th width="20%">QTY</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;    
                        foreach ($get_proses_gudang_log->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->no_proses; ?></td>
                            <td><?= $a->no_pesanan_gudang; ?></td>
                            <td><?= $a->productid; ?></td>
                            <td><?= $a->nama_product; ?></td>
                            <td><?= $a->qty; ?></td>
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
                Menjadi
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <table class="table table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th width="1%">No</th>
                            <th width="10%">ProductId</th>
                            <th width="50%">Nama Product (Unit1 | Unit2)</th>
                            <th width="10%">Total QTY</th>
                            <th width="10%">Total Box</th>
                            <th width="10%">Total Sachet</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_proses_gudang_log_group->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->productid; ?></td>
                            <td><?= $a->nama_product.' ('.$a->unit1.'|'.$a->unit2.') '; ?></td>
                            <td><?= $a->qty; ?></td>
                            <td><?= $a->box; ?></td>
                            <td><?= $a->sachet; ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <?php echo form_open_multipart($url); ?>

                <div class="form-group">
                    <label for="tgl_proses_gudang">Tanggal Pengambilan Barang di Gudang</label>
                    <input class="form-control" type="date" name="tgl_pesanan_gudang" required />
                </div>

                <input type="hidden" name="signature" value="<?= $signature ?>">
                <input type="hidden" name="no_proses" value="<?= $no_proses ?>">

                <div class="form-group">
                    <?php echo form_submit('submit', 'Proses Gudang', 'class="btn btn-primary"'); ?>
                    <?php echo form_close(); ?>
                </div>  

            </div>
        </div>


    </div>


</div>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>