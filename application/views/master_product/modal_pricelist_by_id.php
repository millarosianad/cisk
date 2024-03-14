<div class="modal fade" id="harga_dp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Tambah DP</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open('master_product/tambah_pricelist_dp/'); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4">versi</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="versi" id="versi" required readonly />
                        <input class="form-control" type="hidden" name="id" id="id" required readonly />
                    </div>
                </div>                
                <div class="form-group row">
                    <label class="col-sm-4">keterangan</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="5" readonly></textarea>
                        <!-- <input class="form-control" type="text" name="namaprod" id="keterangan" required readonly /> -->
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">kodeprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="kodeprod" id="kodeprod" required />
                    </div> <button type="button" class="btn btn-info" id = "cek_kodeprod">Cek</button>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">namaprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="namaprod" id="namaprod" placeholder="klik button cek" readonly required/>
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">h_jual_dp_grosir</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="h_jual_dp_grosir" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">h_jual_dp_retail</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="h_jual_dp_retail" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">h_jual_dp_motoris_retail</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="h_jual_dp_motoris_retail" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Site Code</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="site_code" id="site_code">
                            <option value="1">ALL DP</option>
                            <option value="2">Area 1</option>
                            <option value="3">Area 2</option>
                            <option value="4">Area 3</option>
                            <option value="5">Custom</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="customize">
                    <label class="col-sm-4">Custom</label>
                    <div class="col-sm-6">
                        <?php
                        foreach ($s_code as $value) {
                            $branch[$value->kode] = "$value->nama_comp | $value->kode";
                        }
                        echo form_dropdown('branch', $branch, '', 'id="branch" class="form-control"');
                        ?>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <?php echo form_submit('submit', 'Simpan', 'onclick="return y();" class="btn btn-success"'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

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