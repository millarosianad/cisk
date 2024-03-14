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
                            <th>customer_id</th>
                            <th>customer_id_nd6</th>
                            <th>nama_customer</th>
                            <th>alamat</th>
                            <th>kode_type</th>
                            <th>kode_class</th>
                            <th>kode_kota</th>
                            <th>nama_kota</th>
                            <th>kode_kecamatan</th>
                            <th>nama_kecamatan</th>
                            <th>kode_kelurahan</th>
                            <th>nama_kelurahan</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_raw_customer_draft->result() as $a) : ?>
                        <tr>
                            <td><?= $a->customer_id; ?></td>
                            <td><?= $a->customer_id_nd6; ?></td>
                            <td><?= $a->nama_customer; ?></td>
                            <td><?= $a->alamat; ?></td>
                            <td><?= $a->kode_type; ?></td>
                            <td><?= $a->kode_class; ?></td>
                            <td><?= $a->kode_kota; ?></td>
                            <td><?= $a->nama_kota; ?></td>
                            <td><?= $a->kode_kecamatan; ?></td>
                            <td><?= $a->nama_kecamatan; ?></td>
                            <td><?= $a->kode_kelurahan; ?></td>
                            <td><?= $a->nama_kelurahan; ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <?php echo form_submit('submit', 'Cek total data di atas dan klik disini untuk lanjut proses mapping customer', 'class="btn btn-primary"'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
        
</div>

<script>
    $(document).ready(function () {
        $('#example').DataTable({
            "pageLength": 10,
            "aLengthMenu": [
                [1000, 2000, -1],
                [1000, 2000, "All"]
            ],
        });
    });
</script>