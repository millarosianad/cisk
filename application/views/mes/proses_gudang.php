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
                    <label for="periode_1">Periode Awal Transaksi (sudah posting)</label>
                    <input class="form-control" type="date" name="periode_1" required />
                </div>

                <div class="form-group">
                    <label for="periode_2">Periode Akhir Transaksi (sudah posting)</label>
                    <input class="form-control" type="date" name="periode_2" required />
                </div>

                <div class="form-group">
                    <?php echo form_submit('submit', 'Search Transaksi', 'class="btn btn-default"'); ?>
                    <?php echo form_close(); ?>
                </div>  

            </div>
        </div>

    </div>

    <?php echo form_open_multipart($url_update); ?>

    <div class="card-block">    
        <div class="row">
            <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th width="1"><font size="1px"><input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>
                            <th>No Proses</th>
                            <th>Tanggal Proses</th>
                            <th width="50%">NoPesananGudang</th>                            
                            <th>ProductId</th>
                            <th>NamaProduct</th>
                            <th>Qty</th>
                            <th>PostingBy</th>
                            <!-- <th><center>#</center></th> -->
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        foreach ($get_proses_posting->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                    <input type="checkbox" id="<?php echo $a->id; ?>" name="options[]" class = "<?php echo $a->id; ?>" value="<?php echo $a->id; ?>">
                                </center>
                            </td>
                            
                            <td><?= $a->no_proses; ?></td>
                            <td><?= $a->tgl_proses; ?></td>
                            <td><?= $a->no_pesanan_gudang; ?></td>
                            <td><?= $a->productid; ?></td>
                            <td><?= $a->nama_product; ?></td>
                            <td><?= $a->qty; ?></td>
                            <td><?= $a->username; ?></td>
                            
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card-block">
        <div class="row">            
            <div class="col-md-12">
                <?php 
                    if ($this->uri->segment('2') == 'proses_gudang') {
                        echo '';
                    }else{ ?> 
                        <div class="form-group">
                            <?php echo form_submit('submit', 'Lanjutkan ke Preview Pesanan Gudang', 'class="btn btn-primary"'); ?>
                            <?php echo form_close(); ?>
                        </div> 
                    <?php }
                ?>
                 
            </div>
        </div>
    </div>
    <hr>
    <div class="card-block"> 
        <div class="row mb-2 text-center">
            <div class="col-md-12">
                History NPG (No Pesanan Gudang)
            </div>
        </div>   
        <div class="row">
            <div class="col-md-12">
                <table id="example2" class="table table-striped table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th>Tgl Pesanan Gudang</th>
                            <th width="20%">No Pesanan Gudang</th>
                            <th>No Transaksi</th>
                            <th>ProductId</th>
                            <th width="50%">Nama Product</th>
                            <th width="10%">Qty</th>
                            <th width="10%">Box</th>
                            <th width="10%">Sachet</th>
                            <th>Created By</th>
                            <th>Pdf</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        foreach ($get_piutang_detail->result() as $a) : ?>
                        <tr>
                            
                            <td><?= $a->tgl_pesanan_gudang; ?></td>
                            <td><?= $a->no_pesanan_gudang; ?></td>
                            <td><?= $a->no_proses; ?></td>
                            <td><?= $a->productid; ?></td>
                            <td><?= $a->nama_product; ?></td>
                            <td><?= $a->qty; ?></td>
                            <td><?= $a->box; ?></td>
                            <td><?= $a->sachet; ?></td>
                            <td><?= $a->username; ?></td>
                            <td><a href="<?= base_url().'mes/pdf_gudang/'.$a->signature ?>" class="btn btn-outline-danger" target="_blank">Pdf</a></td>
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
        $('#example').DataTable({
            "pageLength": 100,
            "aLengthMenu": [
                [10, 20, -1],
                [10, 20, "All"]
            ],
        });
        $('#example2').DataTable({
            "pageLength": 100,
            "aLengthMenu": [
                [10, 20, -1],
                [10, 20, "All"]
            ],"ordering": false
        });
    });
</script>
