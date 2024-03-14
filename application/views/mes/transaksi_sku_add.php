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
                <?php echo form_open_multipart($url); ?>

                <div class="form-group">
                    <label for="olshop">Olshop - Store</label>
                    <input type="text" class="form-control" name="olshop" value="<?= $get_transaksi_detail_by_signature->row()->nama_olshop.' - '.$get_transaksi_detail_by_signature->row()->nama_store; ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label for="no_proses">No Proses</label>
                    <input type="text" class="form-control" name="no_proses" value="<?= $get_transaksi_detail_by_signature->row()->no_proses; ?>" readonly>
                   
                </div>
                
                <div class="form-group">
                    <label for="no_invoice">Tgl Invoice</label>
                    <input type="text" class="form-control" name="tgl_invoice" value="<?= $get_transaksi_detail_by_signature->row()->tgl_invoice; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="no_invoice">No Invoice</label>
                    <input type="text" class="form-control" name="no_invoice" value="<?= $get_transaksi_detail_by_signature->row()->no_invoice; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="customer">Customer</label>
                    <input type="text" class="form-control" name="customer" value="<?= $get_transaksi_detail_by_signature->row()->customer; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="store">Sku Olshop</label>
                    <select name="sku_olshop" id="id_sku" class="form-control" required>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="customer">Qty Sku</label>
                    <input type="number" class="form-control" name="qty_sku" value="" placeholder="enter qty sku">
                </div>

                <div class="form-group">
                    <input type="hidden" class="form-control" name="id_invoice" value="<?= $get_transaksi_detail_by_signature->row()->id; ?>" readonly>
                    <?php echo form_submit('submit', 'Simpan SKU', 'class="btn btn-primary"'); ?>
                    <?php echo form_close(); ?>
                    <a href="javascript:history.back()" class="btn btn-outline-dark">Kembali</a>
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
                            <th width="1%">No</th>
                            <th>Sku ID</th>
                            <th width="10%">Nama Sku</th>
                            <th>Qty Sku</th>
                            <th>CreatedBy</th>
                            <th><center>#</center></th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_transaksi_sku->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->skuid; ?></td>
                            <td><?= $a->nama_sku; ?></td>
                            <td><?= $a->qty_sku; ?></td>
                            <td><?= $a->username; ?></td>
                            <td>
                                <a href="<?= base_url().'mes/transaksi_sku_edit/'.$a->signature.'/'.$signature_proses_detail ?>" class="btn btn-outline-warning">edit</a>
                                <a href="<?= base_url().'mes/transaksi_detail_delete/'.$a->signature ?>" class="btn btn-outline-danger">delete</a>
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
        url: '<?php echo base_url('database_afiliasi/mes_sku_olshop') ?>',
        data: 'signature=<?= $this->uri->segment('3') ?>',
        success: function(hasil_sku_olshop) {
            $("select[name = sku_olshop]").html(hasil_sku_olshop);
        }
    });
</script>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>