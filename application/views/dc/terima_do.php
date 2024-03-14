<?php 

var_dump($params);


?>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>     
                        <tr>   
                            <!-- <th><font size='2px'>Terima FULL</font></th>                                            -->
                            <th width="1"><font size="1px"><input type="button" class="btn btn-primary btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>
                            <!-- <th><font size='2px'>Id</font></th>                                            -->
                            <th><font size='2px'>Kodeprod</font></th>                                           
                            <th><font size='2px'>QTY</th>                     
                            <th><font size='2px'>Namaprod</th>
                            <th><font size='2px'>Nodo</font></th>                                           
                            <th><font size='2px'>TglDO</font></th>                                           
                            <th><font size='2px'>Batch Number</font></th>                                           
                            <th><font size='2px'>Expired Date</font></th>                                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($get_do as $key) : ?>
                        <tr>    
                            <td>
                            <center>
                                <input type="checkbox" id="<?php echo $key->id; ?>" name="options[]" class = "<?php echo $key->id; ?>" value="<?php echo $key->id; ?>">
                            </center>
                            </td>
                            <!-- <td><font size='2px'><?php echo $key->id; ?></td>     -->
                            <td><font size='2px'><?php echo $key->kodeprod; ?></td>    
                            <td><font size='2px'><?php echo $key->banyak; ?></td>
                            <td><font size='2px'><?php echo $key->namaprod; ?></td>
                            <td><font size='2px'><?php echo $key->nodo; ?></td>    
                            <td><font size='2px'><?php echo $key->tgldo; ?></td>    
                            <td></td>    
                            <td></td>    
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
                <label class="col-sm-3">Status Terima Jumlah Barang (*)</label>
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
                <div class="col-sm-4">

                <input type="hidden" value="<?= $this->uri->segment('4'); ?>" name="supp">
                <input type="hidden" value="<?= $this->uri->segment('3'); ?>" name="signature">

                <?php
                echo form_submit('submit', 'Simpan ke Kartu Stock', 'class="btn btn-success center-block" required');
                echo form_close(); 
                ?>

                </div>
            </div>

        </div>
    </div>
</div>


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