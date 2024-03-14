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
            <div class="col-md-6">
                <?php echo form_open_multipart($url); ?>
                <div class="form-group">
                    <label for="tgl_proses">Tanggal Proses</label>
                    <input type="date" class="form-control" id="tgl_proses" name="tgl_proses" placeholder="Enter tgl_proses">
                </div>

                <div class="form-group">
                    <label for="olshop">Olshop</label>
                    <select name="olshop" id="id_olshop" class="form-control" required>
                    </select>
                </div>

                <div class="form-group">
                    <label for="store">Store</label>
                    <select name="store" id="id_store" class="form-control" required>
                    </select>
                </div>

                <div class="form-group">
                    <?php echo form_submit('submit', 'Simpan Transaksi', 'class="btn btn-primary"'); ?>
                    <?php echo form_close(); ?>
                </div>  
            </div>
        </div>
    </div>

    <hr>

    <div class="card-block">
        <div class="row">
            <div class="col-md-6">
                <?php echo form_open_multipart($url_import); ?>
                <div class="form-group">
                    <label for="import_transaksi">Import CSV</label>
                    <input class="form-control" type="file" name="file" required/>
                </div>

                <div class="form-group">
                    <label for="olshop">Olshop</label>
                    <select name="olshop" id="id_olshop" class="form-control" required>
                    </select>
                </div>

                <div class="form-group">
                    <label for="store">Store</label>
                    <select name="store" id="id_store" class="form-control" required>
                    </select>
                </div>

                <div class="form-group">
                    <a href="<?= base_url().'mes/template_transaksi' ?>" class="btn btn-outline-warning">download template</a>
                    <?php echo form_submit('submit', 'Import Transaksi', 'class="btn btn-success"'); ?>
                    <?php echo form_close(); ?>
                </div> 

            </div>
        </div>
    </div>

    <hr>

    <div class="card-block">    
        <div class="row">
            <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Proses</th>
                            <th width="60%">No Proses</th>
                            <th>Store Id</th>
                            <th>Olshop Id</th>
                            <th>Count Invoice</th>
                            <th>Status Posting</th>
                            <th>Tgl Posting</th>
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
                            <td><?= $a->storeid.' ('.$a->nama_store.')'; ?></td>
                            <td><?= $a->olshopid.' ('.$a->nama_olshop.')'; ?></td>
                            <td><?= $a->count_invoice; ?></td>
                            <td><?= $a->status_posting; ?></td>
                            <td><?= $a->tgl_posting; ?></td>
                            <td><?= $a->username; ?></td>
                            <td>
                                <a href="<?= base_url().'mes/transaksi_add/'.$a->signature ?>" class="btn btn-outline-success">+ invoice</a>
                                <a href="<?= base_url().'mes/transaksi_edit/'.$a->signature ?>" class="btn btn-outline-warning">edit</a>
                                <a href="<?= base_url().'mes/transaksi_delete/'.$a->signature ?>" class="btn btn-outline-danger">delete</a>
                            </td>
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
    });
</script>