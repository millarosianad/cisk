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
                    <input type="text" class="form-control" id="skuid" name="skuid" value="<?= $get_sku_olshop->row()->skuid; ?>">
                    <small id="emailHelp" class="form-text text-muted">tipe data dapat berubah string ataupun angka</small>
                </div>
                <div class="form-group">
                    <label for="nama_sku">Nama Sku</label>
                    <input type="text" class="form-control" id="nama_sku" name="nama_sku" value="<?= $get_sku_olshop->row()->nama_sku; ?>">
                </div>

                <div class="form-group">
                    <label for="olshop">Olshop</label>
                    <select name="olshop" id="id_olshop" class="form-control" required>
                    </select>
                </div> 

                <div class="form-group">
                    <label for="productid">Product Id</label>
                    <select name="productid" id="id_kodeprod" class="form-control" required>
                    </select>
                </div>                         
                                   
                <div class="form-group">
                    <label for="nama_product">Nama Product</label>
                    <input type="text" class="form-control" name="nama_product" value="<?= $get_sku_olshop->row()->nama_product; ?>">
                </div>                         
                                   
                                     
                <div class="form-group">
                    <label for="qty_rule">Qty Rule</label>
                    <input type="text" class="form-control" name="qty_rule" value="<?= $get_sku_olshop->row()->qty_rule; ?>">
                </div>       
                <div class="form-group">
                    <label for="nama_store">Status Jual</label>
                    <select name="status_jual" class="form-control" id="status_jual" required>
                        <option value="1" <?= ($get_sku_olshop->row()->status_jual == '1') ? 'selected' : ''; ?>> Aktif (default) </option>
                        <option value="0" <?= ($get_sku_olshop->row()->status_jual == '0') ? 'selected' : ''; ?>> Non Aktif </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nama_store">Status Aktif</label>
                    <select name="status_aktif" class="form-control" id="status_aktif" required>
                        <option value="1" <?= ($get_sku_olshop->row()->status_aktif == '1') ? 'selected' : ''; ?>> Aktif (default) </option>
                        <option value="0" <?= ($get_sku_olshop->row()->status_aktif == '0') ? 'selected' : ''; ?>> Non Aktif </option>
                    </select>
                </div>
                <div class="form-group">

                    <input type="hidden" class="form-control" name="signature" value="<?= $signature; ?>">
                    <?php echo form_submit('submit', 'Update Sku Olshop', 'class="btn btn-primary"'); ?>
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
                            <th>Olshop</th>
                            <th>Sku ID</th>
                            <th width="100%">Nama Sku</th>
                            <th>Product Id</th>
                            <th>Nama Product</th>
                            <th>Qty Rule</th>
                            <th>Status Jual</th>
                            <th>Status Aktif</th>
                            <th><center>#</center></th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_sku_olshop_all->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->nama_olshop; ?></td>
                            <td><?= $a->skuid; ?></td>
                            <td><?= $a->nama_sku; ?></td>
                            <td><?= $a->productid; ?></td>
                            <td><?= $a->nama_product; ?></td>
                            <td><?= $a->qty_rule; ?></td>
                            <td><?= $a->status_jual; ?></td>
                            <td><?= $a->status_aktif; ?></td>
                            <td>
                            <a href="<?= base_url().'mes/sku_olshop_edit/'.$a->signature ?>" class="btn btn-warning">edit</a>
                            <a href="<?= base_url().'mes/sku_olshop_delete/'.$a->signature ?>" class="btn btn-danger">delete</a>

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
        url: '<?php echo base_url('database_afiliasi/mes_kodeprod') ?>',
        data: 'supp=<?= $this->uri->segment('4') ?>',
        success: function(hasil_kodeprod) {
            $("select[name = productid]").html(hasil_kodeprod);
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