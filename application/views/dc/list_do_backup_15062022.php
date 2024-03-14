<?php echo form_open_multipart($url); ?>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <!-- <table class="table table table-striped table-bordered nowrap table-hover table-outlet">    -->
                <table id="table-dc" class="table table-striped table-bordered nowrap table-outlet">   
                <!-- <table class="table table table-striped table-bordered nowrap table-hover table-outlet">  -->
                    <thead>     
                        <tr>   
                            <!-- <th><font size='2px'>Terima FULL</font></th>                                            -->
                            <th><font size='2px'>Edit</font></th>                                           
                            <th width="1"><font size="1px"><input type="button" class="btn btn-primary btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>
                            <!-- <th><font size='2px'>Id</font></th>                                            -->
                            <th><font size='2px'>Kodeprod</font></th>                                           
                            <th><font size='2px'>Namaprod</th>
                            <th><font size='2px'>Nodo</font></th>                                           
                            <th><font size='2px'>TglDO</font></th>                                           
                            <th><font size='2px'>Qty do</th>                     
                            <th><font size='2px'>masuk</th>                     
                            <th><font size='2px'>Batch Number</font></th>                                           
                            <th><font size='2px'>Expired Date</font></th>                                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($get_do as $key) : ?>
                            <tr>    
                                <td><font size='2px'> 
                                    <!-- <button class="btn btn-warning btn-sm" onclick="editProfile('<?= $key->id; ?>')">Edit</button> -->
                                         <a href="<?php echo base_url() ?>dc/edit_kartu_stock/<?= $key->id; ?>" class="btn btn-warning btn-sm" target="blank">Edit</a>
                                </td>    
                                <td>
                                <?php if ($key->kode_kartu_masuk == null) { ?>
                                    <center>
                                    <input type="checkbox" id="<?php echo $key->id; ?>" name="options[]" class = "<?php echo $key->id; ?>" value="<?php echo $key->id; ?>">
                                
                                <?php }else{ 
                                    echo $key->kode_kartu_masuk; 
                                     } ?>
                            </center>
                            </td>
                            <!-- <td><font size='2px'><?php echo $key->id; ?></td>     -->
                            <td><font size='2px'><?php echo $key->kodeprod; ?></td>    
                            <td><font size='2px'><?php echo $key->namaprod; ?></td>
                            <td><font size='2px'><?php echo $key->nodo; ?></td>    
                            <td><font size='2px'><?php echo $key->tgldo; ?></td>    
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
    </div>
</div>



<div class="card table-card">
    <div class="card-header">
        <div class="card-block">

            <div class="form-group row">
                <label class="col-sm-3">Kode Barang Masuk</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" name="kode_kartu_masuk" value="<?= $generate_kode_kartu; ?>" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Batch Number</label>
                <div class="col-sm-4">
                    <input id="" class="form-control" type="text" name="batch_number" value="" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Expired Date</label>
                <div class="col-sm-4">
                    <input id="" class="form-control" type="date" name="ed" value="" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Jumlah Barang Yang Di Terima (*)</label>
                <div class="col-sm-4">
                    <select class="form-control" value="0" name="status_qty" id="status_qty">
                        <option value="1">Full / Terima Sesuai DO (surat jalan pabrik)</option>
                        <option value="2">Terima Sebagian (harus sertakan berita acara)</option>
                    </select>
                </div>
            </div>
            <div class="form-group row" id="jumlah_qty">
                <label class="col-sm-3">Jumlah Barang (unit terkecil)</label>
                <div class="col-sm-4">
                    <input id="" class="form-control" type="text" name="jumlah_qty" value="" />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3"></label>
                <div class="col-sm-9">

                <input type="hidden" value="<?= $this->uri->segment('4'); ?>" name="supp">
                <input type="hidden" value="<?= $this->uri->segment('3'); ?>" name="signature">

                <?php
                echo form_submit('submit', 'Proses Barang Masuk', 'class="btn btn-success center-block" required');
                echo form_close(); 
                ?>
                <br><hr><br>

                <a href="<?php echo base_url()."dc/berita_acara"; ?>"class="btn btn-dark btn-sm" role="button">proses berita acara</a>
                <a href="<?php echo base_url()."dc/list_kartu_stock"; ?>"class="btn btn-dark btn-sm" role="button">proses barang keluar</a>

                </div>
            </div>

        </div>
    </div>
</div>

<?php 
    $this->load->view('dc/modal_edit_terima_barang');
?>


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

