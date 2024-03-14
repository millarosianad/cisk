
 

<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>
    <div class="card-block">
    
        <div class="row">
            <div class="col-md-6">
                <?php echo form_open_multipart($url); ?>
                <div class="form-group">
                    <label for="skuid">Sku ID</label>
                    <input type="text" class="form-control" id="skuid" name="skuid" placeholder="Enter skuid">
                    <small id="emailHelp" class="form-text text-muted">tipe data dapat berubah string ataupun angka</small>
                </div>
                <div class="form-group">
                    <label for="nama_sku">Nama Sku</label>
                    <input type="text" class="form-control" name="nama_sku" placeholder="Enter nama sku">
                </div>             
                
                <div class="form-group">
                    <label for="olshop">Olshop</label>
                    <select name="olshop" id="id_olshop" class="form-control" required>
                    </select>
                </div>            
                    
                <div class="form-group">
                    <label for="nama_store">Status Jual</label>
                    <select name="status_jual" class="form-control" id="status_jual" required>
                        <option value="1"> Aktif (default) </option>
                        <option value="0"> Non Aktif </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nama_store">Status Aktif</label>
                    <select name="status_aktif" class="form-control" id="status_aktif" required>
                        <option value="1"> Aktif (default) </option>
                        <option value="0"> Non Aktif </option>
                    </select>
                </div>

                <div class="form-group">
                    <?php echo form_submit('submit', 'Simpan Sku Olshop', 'class="btn btn-primary"'); ?>
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
                    <a href="<?= base_url().'mes/template_sku_olshop' ?>" class="btn btn-outline-warning">download template sku olshop</a>
                    <?php echo form_submit('submit', 'Import Sku Olshop With CSV', 'class="btn btn-primary"'); ?>
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
                            <th>Olshop</th>
                            <th>Sku ID</th>
                            <th width="100%">Nama Sku</th>
                            <th>Count Product</th>
                            <th>Status Jual</th>
                            <th>Status Aktif</th>
                            <th><center>#</center></th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_sku_olshop->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->nama_olshop; ?></td>
                            <td><?= $a->skuid; ?></td>
                            <td><?= $a->nama_sku; ?></td>
                            <!-- <td><?= $a->productid; ?></td> -->
                            <!-- <td><?= $a->nama_product; ?></td> -->
                            <!-- <td><?= $a->qty_rule; ?></td> -->
                            <td><?= $a->count_product; ?></td>
                            <td><?= $a->status_jual; ?></td>
                            <td><?= $a->status_aktif; ?></td>
                            <td>
                                <a href="<?= base_url().'mes/sku_olshop_add/'.$a->signature ?>" class="btn btn-outline-success">+ productid</a>
                                <a href="<?= base_url().'mes/sku_olshop_edit/'.$a->signature ?>" class="btn btn-outline-warning">edit</a>
                                <a href="<?= base_url().'mes/sku_olshop_delete/'.$a->signature ?>" class="btn btn-outline-danger">delete</a>
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