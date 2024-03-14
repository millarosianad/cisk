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
    
    <?php echo form_open_multipart($url); ?>

    <div class="card-block">    
        <div class="row">
            <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th width="1"><font size="1px"><input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>                            
                            <th>No Pesanan Gudang</th>
                            <th>Tanggal Pesanan Gudang</th>
                            <th>Posting By</th>
                            <th>No Faktur</th>
                            <th>Tgl Faktur</th>
                            <th>Nilai Faktur</th>
                            <th>Bayar</th>
                            <th>Tgl Bayar</th>
                            <th>Transfer</th>
                            <th>Bukti Transfer</th>
                            <th>UpdatedAt</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        foreach ($get_piutang->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                    <input type="checkbox" id="<?php echo $a->signature; ?>" name="options[]" class = "<?php echo $a->signature; ?>" value="<?php echo $a->signature; ?>">
                                </center>
                            </td>
                            <td><a href="<?= base_url().'mes/piutang_detail/'.$a->signature ?>"><?= $a->no_pesanan_gudang; ?></a>
                                
                            </td>
                            <td><?= $a->tgl_pesanan_gudang; ?></td>
                            <td><?= $a->username; ?></td>                            
                            <td><?= $a->no_faktur; ?></td>                            
                            <td><?= $a->tgl_faktur; ?></td>                            
                            <td><?= $a->nilai_faktur; ?></td>                            
                            <td><?= $a->bayar; ?></td>                            
                            <td><?= $a->tgl_bayar; ?></td>                            
                            <td><?= $a->transfer; ?></td>                            
                            <td>
                                <a href="<?= base_url().'assets/uploads/mes/'.$a->bukti_transfer ?>" target="_blank"><img src="<?= base_url().'assets/uploads/mes/'.$a->bukti_transfer ?>" alt="<?= $a->bukti_transfer ?>" width="30"></a>
                            </td>                            
                            <td><?= $a->updated_at; ?></td>                            
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tgl_bayar">Tanggal Bayar</label>
                    <input class="form-control" type="date" name="tgl_bayar" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="bayar">Nominal Bayar</label>
                    <input class="form-control" type="text" name="bayar" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="transfer">Nominal Transfer</label>
                    <input class="form-control" type="text" name="transfer" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="bukti_transfer">Bukti Transfer</label>
                    <input class="form-control" type="file" name="bukti_transfer" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php echo form_submit('submit', 'Checklist Nomor NPG dan Klik Disini', 'class="btn btn-primary"'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>


    </div>
</div>
<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>