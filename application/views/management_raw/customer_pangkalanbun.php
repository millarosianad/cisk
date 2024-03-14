<style>

td {
  text-align: left;
  font-size: 11px;
}

th {
  text-align: left;
  font-size: 12px;
}

</style>

<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>
    <div class="card-block">
        <div class="row">
            <div class="col-md-7">
                <?php echo form_open_multipart($url_import); ?>
                <div class="form-group">
                    <label for="import_transaksi">Import CSV</label>
                    <input class="form-control" type="file" name="file" required/>
                </div>

                <!-- <div class="form-group"> -->
                    <a href="<?= base_url().'management_raw/template_customer_pangkalanbun' ?>" class="btn btn-outline-warning">download template customer pangkalanbun</a>
                    <?php echo form_submit('submit', 'Import CSV Customer PANGKALANBUN', 'class="btn btn-primary"'); ?>
                    <?php echo form_close(); ?>
                </div> 

            </div>
        </div>
    </div>

    <div class="card-block">
        <div class="row">
            <div class="col-md-12">

                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kodelang MPM</th>
                            <th>Kodelang DP</th>
                            <th>Namalang</th>
                            <th>Kode Type</th>
                            <th>Kodesalur</th>
                            <th>Segment</th>
                            <th>Alamat</th>
                            <th>Nama Kota</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        // var_dump($get_log_customer_upload->result());
                        foreach ($get_customer->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>         
                            <td><?= $a->customer_id; ?></td>
                            <td><?= $a->customer_id_nd6; ?></td>
                            <td><?= $a->nama_customer; ?></td>
                            <td><?= $a->kode_type; ?></td>
                            <td><?= $a->kode_class; ?></td>
                            <td><?= $a->segment; ?></td>
                            <td><?= $a->alamat; ?></td>
                            <td><?= $a->nama_kota; ?></td>
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
        $('#example').DataTable({
            "order": [[4, 'asc']]});
    });
</script>