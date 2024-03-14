<style>
    th, td, .row, .form{
        font-size: 12px;
    }
</style>
<?php echo form_open_multipart($url); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">No Barang Masuk</div>
        <div class="col-md-10">: 
            <?php 
                if ($header->kode_kartu_masuk == '') { ?>
                    <font color="red"><i>
                        <?= "Empty. Silahkan Lakukan Proses Barang Masuk Terlebih Dahulu."; ?>
                    </i></font>
                <?php
                }else{ ?>
                    <i><?= $header->kode_kartu_masuk; ?></i>
                <?php }
            ?>
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">Principal</div>
        <div class="col-md-3">: <?= $header->namasupp; ?></div>
    </div>


    <div class="row">
        <div class="col-md-2">NO.Surat Jalan / NODO</div>
        <div class="col-md-10">: <?= $header->nodo; ?></div>
    </div>
    <div class="row">
        <div class="col-md-2">Tanggal DO</div>
        <div class="col-md-10">: <?= $header->tgldo; ?></div>
    </div>
    
    <br>
    <div class="row">
        <div class="col-md-2">NO.Purchase Order</div>
        <div class="col-md-10">: <?= $header->nopo; ?></div>
    </div>
    <div class="row">
        <div class="col-md-2">TglPO</div>
        <div class="col-md-10">: <?= $header->tglpo; ?></div>
    </div>
    <div class="row">
        <div class="col-md-2">Company</div>
        <div class="col-md-10">: <?= $header->company; ?></div>
    </div>
    <div class="row">
        <div class="col-md-2">Alamat (Kode Alamat)</div>
        <div class="col-md-10">: <?= $header->alamat. " (".$header->kode_alamat.")"; ?></div>
    </div>
    <br>
</div>

<hr>

<div class="container-fluid">
    <div class="form-group row">
        <label class="col-sm-2">Batch Number</label>
        <div class="col-sm-4">
            <input id="" class="form-control" type="text" name="batch_number" placeholder="isi batch number" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2">Expired Date</label>
        <div class="col-sm-4">
            <input id="" class="form-control" type="date" name="ed" placeholder="isi ED" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2">Jumlah Barang Masuk</label>
        <div class="col-sm-4">
            <select class="form-control row" value="0" name="status_qty" id="status_qty">
                <option value="1">Full / Terima Sesuai DO (surat jalan pabrik)</option>
                <option value="2">Terima Sebagian (harus sertakan berita acara)</option>
            </select>
        </div>
    </div>
    <div class="form-group row" id="jumlah_qty">
        <label class="col-sm-2">Jumlah Barang (satuan terkecil)</label>
        <div class="col-sm-4">
            <input id="" class="form-control" type="text" name="masuk" value="" />
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2"></label>
        <div class="col-sm-4">

        <input type="hidden" value="<?= $this->uri->segment('5'); ?>" name="nodo">
        <input type="hidden" value="<?= $this->uri->segment('4'); ?>" name="supp">
        <input type="hidden" value="<?= $this->uri->segment('3'); ?>" name="signature">

        


        <?php
        echo form_submit('submit', 'Proses Barang Masuk', 'class="btn btn-success center-block" required');
        ?>

        <a href="<?= base_url(); ?>dc/list_po" class="btn btn-md btn-dark">kembali</a>

        </div>
    </div>
</div>
<hr>
<div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="table-dc" class="table table-striped table-bordered nowrap table-outlet">   
            <thead>     
                <tr>                                        
                    <th width="1"><font size="1px"><input type="button" class="btn btn-primary btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>                                             
                    <th><font size='2px'>kodeprod</font></th>                                           
                    <th><font size='2px'>namaprod</font></th>
                    <th><font size='2px'>Qty do</th>                     
                    <th><font size='2px'>masuk</th>                     
                    <th><font size='2px'>Batch Number</font></th>                                           
                    <th><font size='2px'>Expired Date</font></th>                                           
                </tr>
            </thead>
            <tbody>
                <?php foreach($detail as $key) : ?>
                <tr>                                       
                    <td>
                        <center>
                            <input type="checkbox" id="<?php echo $key->id_do; ?>" name="options[]" class = "<?php echo $key->id_do; ?>" value="<?php echo $key->id_do; ?>">
                        </center>
                    </td>
                    <td><font size='2px'><?php echo $key->kodeprod; ?></td>    
                    <td><font size='2px'><?php echo $key->namaprod; ?>
                    <?php 
                        if ($key->banyak <> $key->masuk) {
                            echo '<i class="fa fa-exclamation fa-lg" style="color:red"></i><i class="fa fa-exclamation fa-lg" style="color:red"></i>';
                        }
                        ?>
                    </td> 
                    <td><font size='2px'><?php echo $key->banyak; ?></td>
                    <td><font size='2px'><?php echo $key->masuk; ?></td>
                    <td><font size='2px'><?php echo $key->batch_number; ?></td>    
                    <td><font size='2px'><?php echo $key->ed; ?></td>    
                </tr>
            <?php endforeach; ?>
            </tbody>                 
        </table>
    </div>
</div> 


<!-- <input type="text" value="<?= $header->namasupp; ?>" name="namasupp"> -->


<?php echo form_close();  ?>

<script>
   
    $(document).ready(function() {
        $('#jumlah_qty').hide()
        $("select#status_qty").change(function() {
            var status_qty = $(this).children("option:selected").val();
            if (status_qty == '2') {
                $('#jumlah_qty').show()
            } else {
                $('#jumlah_qty').hide()
            }
        });
    });


</script>

<script type="text/javascript">
    $(".table-outlet").DataTable({
        ordering: true,
        processing: true,
        serverSide: false,
        
    });

</script>

