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
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4">Kodeprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="kodeprod" id="kodeprod" required />
                    </div> <button type="button" class="btn btn-info" id = "cek_kodeprod">Cek</button>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">namaprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="namaprod" id="namaprod" placeholder="klik button cek" required readonly/>
                    </div> 
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Batch Number</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="batch_number" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Expired Date</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="date" name="expired_date" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Jumlah</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="number" name="jumlah" required />
                    </div>
                </div>    

                <div class="form-group row">
                    <label class="col-sm-4">Satuan</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="satuan" id="satuan">
                            <option value="sachet">sachet</option>
                            <option value="botol">botol</option>
                            <option value="amplop">amplop</option>
                            <option value="tube">tube</option>
                            <option value="pump">pump</option>
                            <option value="patch">patch</option>
                            <option value="pouch">pouch</option>
                            <option value="box">box</option>
                            <option value="pot">pot</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Alasan</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="alasan">
                            <option value="1a">kadaluarsa (EX-Gudang MPM)</option>
                            <option value="1b">penarikan oleh principal (EX-Gudang MPM)</option>
                            <option value="1c">rusak dalam pengiriman (EX-Gudang MPM)</option>
                            <option value="1d">rusak dalam penyimpanan (EX-Gudang MPM)</option>
                            <option value="1e">tidak laku (EX-Gudang MPM)</option>
                            <option value="1f">salah kirim oleh principal (EX-Gudang MPM)</option>
                            <option value="1g">lain-lain (EX-Gudang MPM)</option>
                            <option value="2a">kadaluarsa (EX-Outlet)</option>
                            <option value="2b">penarikan oleh principal (EX-Outlet)</option>
                            <option value="2c">rusak dalam penyimpanan (EX-Outlet)</option>
                            <option value="2d">tidak laku (EX-Outlet)</option>
                            <option value="2e">lain-lain (EX-Outlet)</option>
                            
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Nama Outlet</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="nama_outlet" required />
                    </div>
                </div>   

                <div class="form-group row">
                    <label class="col-sm-4">Keterangan</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="10" required></textarea>
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
<!-- 
<script>
    function getHargaIDProduct_Dp(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('master_product/get_productsid') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function(response) {
                console.log(response.harga);
                $("#harga_dp").modal() // Buka Modal
                $('#h_product_id_dp').val(params) // parameter
                $('#h_namaprod_dp').val(response.edit.namaprod).change();
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
</script> -->


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
        const supp = $("#supp").val();             
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
        // var count = 0;
        // for (var i = 0; i < c.length; i++) {
        //     if (c[i].checked) {
        //     count++;
        //     }
        // }
        // if (count < 1) {
        //     alert("Pilih Principal yang akan diamati.");
        //     return false;
        // }
        // // return true;
        // else{
        //     $("#btnKirim").hide();
        //     $("#btnLama").hide();
        //     $("#btnLoading").show();
        // }
        
    }

</script>