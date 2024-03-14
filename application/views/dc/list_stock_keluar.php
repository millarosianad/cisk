<?php echo form_open_multipart($url); ?>

<div class="container">
    <div class="row">
        <div class="col">
            
            <?php 
                echo "<pre><hr>";
                echo "principal : ".$namasupp."<br>";
                echo "nopo : ".$nopo."<br>";
                echo "nodo : ".$nodo."<br>";
                echo "tgldo : ".$tgldo."<br>";
                echo "<hr></pre>";
            
            ?>

        </div>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="table-dc" class="table table-striped table-bordered nowrap table-outlet">   
                    <thead>     
                        <tr>                                        
                            <th width="1"><font size="1px"><input type="button" class="btn btn-primary btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>                                             
                            <th><font size='2px'>kodeprod</font></th>                                           
                            <th><font size='2px'>namaprod</font></th>              
                            <th><font size='2px'>on hand</th>                     
                            <th><font size='2px'>keluar</th>                     
                            <th><font size='2px'>sisa</th>                                                        
                            <th><font size='2px'>ekspedisi</th>                                                        
                            <th><font size='2px'>keluar</th>                                                        
                            <th><font size='2px'>tiba</th>                                                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($get_kartu_masuk as $key) : ?>
                        <tr>                                       
                            <td>
                                <center>
                                    <input type="checkbox" id="<?php echo $key->id; ?>" name="options[]" class = "<?php echo $key->id; ?>" value="<?php echo $key->id; ?>">
                                </center>
                            </td>
                            <td><font size='2px'><?php echo $key->kodeprod; ?></td>    
                            <td><font size='2px'><?php echo $key->namaprod; ?></td>
                            <td><font size='2px'><?php echo $key->masuk; ?></td>
                            <td><font size='2px'><?php echo $key->keluar; ?></td>
                            <td><font size='2px'><?php echo $key->sisa; ?></td>  
                            <td><font size='2px'><?php echo $key->ekspedisi; ?></td>  
                            <td><font size='2px'><?php echo $key->tanggal_keluar; ?></td>  
                            <td><font size='2px'><?php echo $key->eta; ?></td>  
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
                <label class="col-sm-3">Jumlah Barang Yang Di Keluarkan (*)</label>
                <div class="col-sm-4">
                    <select class="form-control" value="0" name="status_qty" id="status_qty">
                        <option value="1">Full / Keluar Sesuai Stock on hand (qty yang masuk)</option>
                        <option value="2">Keluar Sebagian (harus sertakan berita acara)</option>
                    </select>
                </div>
            </div>
            <div class="form-group row" id="jumlah_qty">
                <label class="col-sm-3">Jumlah Barang (unit terkecil)</label>
                <div class="col-sm-4">
                    <input id="" class="form-control" type="text" name="keluar" value="" />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3">Tanggal Keluar</label>
                <div class="col-sm-4">
                    <input class="form-control" type="date" name="tanggal_keluar" value="" />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3">Ekspedisi</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" name="ekspedisi" value="" />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3">Estimasi Tiba</label>
                <div class="col-sm-4">
                    <input class="form-control" type="date" name="eta" value="" />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3"></label>
                <div class="col-sm-9">

                <input type="hidden" value="<?= $this->uri->segment('5'); ?>" name="nodo">
                <input type="hidden" value="<?= $this->uri->segment('4'); ?>" name="supp">
                <input type="hidden" value="<?= $this->uri->segment('3'); ?>" name="signature">
                <input type="hidden" value="<?= $kode_kartu_keluar ?>" name="kode_kartu_keluar">

                <?php
                echo form_submit('submit', 'Proses Keluar Barang', 'class="btn btn-warning center-block" required');
                echo form_close(); 
                ?>
                <br><hr><br>

                <a href="<?php echo base_url()."dc/berita_acara"; ?>"class="btn btn-dark btn-sm" role="button">proses berita acara</a>
                <a href="<?php echo base_url()."dc/list_po"; ?>"class="btn btn-dark btn-sm" role="button">proses barang masuk</a>

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

