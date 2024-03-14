<div class="modal fade" id="pricelist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Produk Ajuan Retur</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php 
                // echo form_open('master_product/tambah_pricelist/'); 
                echo form_open('retur/tambah_produk_ajuan_retur/'.$this->uri->segment('3').'/'.$this->uri->segment('4')); 
            ?>
            <!-- <?php echo "aaaa : ".$this->uri->segment('4'); ?> -->
            <div class="modal-body">
               
                <div class="form-group row">
                    <label class="col-sm-4">Kodeprod</label>
                    <div class="col-sm-6">
                        <select name="kodeprod" id="id_kodeprod" class="form-control" <?= $required; ?>>

                        </select>
                    </div>
                </div>

                <input type="hidden" value="" name="namaprod" id="namaprod">

                <div class="form-group row">
                    <label class="col-sm-4">Batch Number</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="batch_number" required />
                    </div>
                </div>

                <!-- <div class="form-group row">
                    <label class="col-sm-4">Unit / Sat</label>
                    <div class="col-sm-6">
                        <select name="satuan" class="form-control" required>
                            <option value=""> -- Pilih Satuan -- </option>
                            <option value="sachet">1.sachet</option>
                            <option value="botol">2.botol</option>
                            <option value="bag">3.bag</option>
                            <option value="toples">4.toples</option>
                            <option value="blister">5.blister</option>
                        </select>
                    </div>
                </div> -->

                <div class="form-group row">
                    <label class="col-sm-4">Expired Date</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="date" name="expired_date" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Jumlah <br> (* masukkan dalam satuan terkecil)</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="number" name="jumlah" required />
                    </div>
                </div>    
                
                <div class="form-group row">
                    <label class="col-sm-4">Nama Outlet</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="nama_outlet" required />
                    </div>
                </div>    

                <div class="form-group row">
                    <label class="col-sm-4">Alasan Retur</label>
                    <div class="col-sm-6">
                        <select name="alasan" class="form-control" required>
                            <option value=""> -- Pilih Alasan -- </option>
                            <option value="A"> A. Cacat Produksi </option>
                            <option value="B"> B. Kadaluarsa </option>
                            <option value="C"> C. Produk Discontinue( </option>
                            <option value="D"> D. Penarikan PabrikNama </option>
                            <option value="E"> E. Salah Kirim oleh Principal </option>
                            <option value="F"> F. Rusak dalam Pengiriman (ada bukti kerusakan terjadi dalam pengiriman) </option>
                            <option value="G"> G. Lain-lain </option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Keterangan Tambahan</label>
                    <div class="col-sm-6">
                        <textarea name="keterangan" class="form-control" cols="30" rows="5"></textarea>
                    </div>
                </div>

                <input class="form-control" type="hidden" name="id_pengajuan" value=<?php echo $id_pengajuan; ?>>
                    

            </div>

            <div class="modal-footer">
                <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success" required'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/kodeprod') ?>',
        data: 'supp=<?= $this->uri->segment('4') ?>',
        success: function(hasil_kodeprod) {
            $("select[name = kodeprod]").html(hasil_kodeprod);
        }
    });

    $("select[name = kodeprod]").on("change", function() {
            var id_kodeprod_terpilih = $("option:selected", this).attr("id_kodeprod");

            // console.log('namaprod')
            // console.log(id_kodeprod_terpilih)

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('database_afiliasi/namaprod_input_type') ?>',
                data: 'id_kodeprod=' + id_kodeprod_terpilih,
                success: function(hasil_namaprod) {
                    $('#namaprod').val(hasil_namaprod);
                }
            });

        });

</script>



<script>
    function get_pricelist_by_id(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('master_product/get_pricelist_by_id') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function(response) {
                // console.log(response.harga);
                // console.log(response.pricelist);
                // console.log(response.pricelist.id);
                $("#harga_dp").modal() // Buka Modal
                // $('#h_product_id_dp').val(params) // parameter
                // $('#h_namaprod_dp').val(response.edit.namaprod).change();
                $('#id').val(response.pricelist.id).change();
                $('#versi').val(response.pricelist.versi).change();
                $('#keterangan').val(response.pricelist.keterangan).change();
            }
        });
    }
    $(document).ready(function() {
        $('#customize').hide()
        $("select#site_code").change(function() {
            var selectedLocation = $(this).children("option:selected").val();
            if (selectedLocation == '5') {
                $('#customize').show()
            } else {
                $('#customize').hide()
            }
        });
    });

    $("#cek_kodeprod").click(function(){
        const kodeprod = $("#kodeprod").val();             
        // console.log(kodeprod);
        $.ajax({
        url:"<?php echo base_url(); ?>master_product/get_namaprod",     
        data: {
            kodeprod: kodeprod
            },
        type: "POST",
        success: function(data){
            // console.log(data);
            // $("#namaprod").html(data);
            $("#namaprod").val(data);
            }
        });
    });

    function y(){
        // var c = document.getElementsByClassName('namaprod').val;
        const namaprod = $("#namaprod").val();   
        console.log(namaprod);
        if (namaprod == "produk tidak ditemukan") {
            alert("kodeprod yg dimasukkan salah");
            return false;
        }else{
            console.log("b");
        }
  
    }

</script>