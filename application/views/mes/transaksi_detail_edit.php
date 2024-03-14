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
                    <input type="text" class="form-control" name="olshop" value="<?= $get_transaksi_header->row()->nama_olshop.' - '.$get_transaksi_header->row()->nama_store; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="tgl_proses">Tanggal Proses</label>
                    <input type="text" class="form-control" name="tgl_proses" value="<?= $get_transaksi_header->row()->tgl_proses; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="no_proses">No Proses</label>
                    <input type="text" class="form-control" name="no_proses" value="<?= $get_transaksi_header->row()->no_proses; ?>" readonly>
                </div>
                
                <hr>
                
                <div class="form-group">
                    <label for="no_invoice">Tgl Invoice</label>
                    <input type="date" class="form-control" name="tgl_invoice" value="<?= $get_transaksi->row()->tgl_invoice; ?>">
                </div>
                <div class="form-group">
                    <label for="no_invoice">No Invoice</label>
                    <input type="text" class="form-control" name="no_invoice" value="<?= $get_transaksi->row()->no_invoice; ?>">
                </div>
                <div class="form-group">
                    <label for="customer">Customer</label>
                    <input type="text" class="form-control" name="customer" value="<?= $get_transaksi->row()->customer; ?>">
                </div>

                <div class="form-group">
                    <label for="kurir">Kurir</label>
                    <select name="kurir" class="form-control" required>
                        <option value=""> -- Pilih Kurir -- </option>
                        <option value="JNE">JNE</option>
                        <option value="TIKI">TIKI</option>
                        <option value="SICEPAT">SICEPAT</option>
                        <option value="GOJEK">GOJEK</option>
                        <option value="GRAB">GRAB</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="no_resi">No Resi</label>
                    <input type="text" class="form-control" id="no_resi" name="no_resi" value="<?= $get_transaksi->row()->no_resi; ?>">
                </div>

                <input type="hidden" class="form-control" name="signature" value="<?= $signature; ?>">

                <div class="form-group">
                    <?php echo form_submit('submit', 'Update Invoice Header', 'class="btn btn-primary"'); ?>
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
                            <th>No</th>
                            <th width="10%">No Proses</th>
                            <th>No Invoice</th>
                            <th>Tgl Invoice</th>
                            <th>Count Sku</th>
                            <th>Customer</th>
                            <th>Kurir</th>
                            <th>No Resi</th>
                            <th><center>#</center></th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_transaksi_detail->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->no_proses; ?></td>
                            <td><?= $a->no_invoice; ?></td>
                            <td><?= $a->tgl_invoice; ?></td>
                            <td><?= $a->count_sku; ?></td>
                            <td><?= $a->customer; ?></td>
                            <td><?= $a->kurir; ?></td>
                            <td><?= $a->no_resi; ?></td>
                            <td>
                                <a href="<?= base_url().'mes/transaksi_sku_add/'.$a->signature ?>" class="btn btn-outline-success">+ sku</a>
                                <a href="<?= base_url().'mes/transaksi_detail_edit/'.$a->signature ?>" class="btn btn-outline-warning">edit</a>
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
        data: '',
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