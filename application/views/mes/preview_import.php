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
            <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th width="1"><font size="1px"><input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>     
                            <th>Tanggal</th>
                            <th width="50%">invoice</th>
                            <th>pembeli</th>
                            <th>storeid</th>
                            <th>store</th>
                            <th>olshopid</th>
                            <th>olshop</th>
                            <th>kurir</th>
                            <th>resi</th>
                            <th>skuid</th>
                            <th>nama sku</th>
                            <th>qty_sku</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_import->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                    <input type="checkbox" id="<?php echo $a->id; ?>" name="options[]" class = "<?php echo $a->id; ?>" value="<?php echo $a->id; ?>">
                                </center>
                            </td>
                            <td><?= $a->tanggal; ?></td>
                            <td><?= $a->invoice; ?></td>
                            <td><?= $a->pembeli; ?></td>
                            <td><?= $a->storeid; ?></td>
                            <td><?= $a->nama_store; ?></td>
                            <td><?= $a->olshopid; ?></td>
                            <td><?= $a->nama_olshop; ?></td>
                            <td><?= $a->kurir; ?></td>
                            <td><?= $a->resi; ?></td>
                            <td><?= $a->skuid; ?></td>
                            <td><?= $a->nama_sku; ?></td>
                            <td><?= $a->qty_sku; ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        <h3>Pastikan Total Row Excel === Total yang tampil di layar</h3>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <?php echo form_submit('submit', 'Commit ke Transaksi', 'class="btn btn-primary"'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
        
</div>

<script>
    $(document).ready(function () {
        $('#example').DataTable({
            "pageLength": 1000,
            "aLengthMenu": [
                [1000, 2000, -1],
                [1000, 2000, "All"]
            ],
        });
    });
</script>