<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Harga</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php echo form_open('master_product/update_harga/'); ?>
        <div class="modal-body">
            <div class="form-group row">
                <input class="form-control" type="text" name="harga_id" required id="harga_id" hidden/>
                <label class="col-sm-4">Product Code</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="kodeprod" required id="kodeprod" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Product Name</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="namaprod" required id="namaprod" readonly />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Tanggal Aktif</label>
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="tgl" id="tgl" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Harga Beli DP</label>
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="hb_dp" id="h_beli_dp"/>
                </div>
                Disc
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="db_dp" id="d_beli_dp"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Harga Beli BSP</label>
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="hb_bsp" id="h_beli_bsp" />
                </div>
                Disc
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="db_bsp" id="d_beli_bsp"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Harga Beli PBF</label>
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="hb_pbf" id="h_beli_pbf"/>
                </div>
                Disc
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="db_pbf" id="d_beli_pbf"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Harga DP</label>
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="h_dp" id="h_dp"/>
                </div>
                Disc
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="d_dp" id="d_dp"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Harga BSP</label>
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="h_bsp" id="h_bsp" />
                </div>
                Disc
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="d_bsp" id="d_bsp"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Harga PBF</label>
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="h_pbf" id="h_pbf" />
                </div>
                Disc
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="d_pbf" id="d_pbf"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Harga Khusus Batam (Dji Go Lak)</label>
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="hk_batam" id="h_dpbatam"/>
                </div>
                Disc
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="dk_batam" id="d_dpbatam"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Harga DP (Luar Pulau Jawa)</label>
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="h_dp_ljawa" id="h_luarjawa"/>
                </div>
                Disc
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="d_dp_ljawa" id="d_luarjawa"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Harga Beli MPM - DP</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="hbm_dp" id="h_beli_mpm"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Harga Beli MPM - BSP</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="hbm_bsp" id="h_beli_mpm_bsp"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Harga Beli MPM Batam</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="hbm_batam" id="h_beli_mpm_batam"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Harga Beli MPM - Candy (Pulau Jawa)</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="hbm_candy_pj" id="h_beli_mpm_candy_jawa"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Harga Beli MPM - Candy (Luar Pulau Jawa)</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="hbm_candy_lj" value="" id="h_beli_mpm_candy_ljawa"/>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <?php echo form_submit('submit','Simpan', 'class="btn btn-success" required');?>
            <?php echo form_close();?>
        </div>
        </div>
    </div>
</div>

<script>
    function get_hargaproduct(params){
        $.ajax({
            type: "GET",
            url: "<?= base_url('master_product/get_productsid') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.harga);
                $("#edit").modal() // Buka Modal
                $("#harga_id").val(params)
                $("#kodeprod").val(response.harga.kodeprod)
                $("#namaprod").val(response.harga.namaprod)
                $("#tgl").val(response.harga.tgl)
                $("#h_beli_dp").val(response.harga.h_beli_dp)
                $("#d_beli_dp").val(response.harga.d_beli_dp)
                $("#h_beli_bsp").val(response.harga.h_beli_bsp)
                $("#d_beli_bsp").val(response.harga.d_beli_bsp)
                $("#h_beli_pbf").val(response.harga.h_beli_pbf)
                $("#d_beli_pbf").val(response.harga.d_beli_pbf)
                $("#h_dp").val(response.harga.h_dp)
                $("#d_dp").val(response.harga.d_dp)
                $("#h_bsp").val(response.harga.h_bsp)
                $("#d_bsp").val(response.harga.d_bsp)
                $("#h_pbf").val(response.harga.h_pbf)
                $("#d_pbf").val(response.harga.d_pbf)
                $("#h_dpbatam").val(response.harga.h_dpbatam)
                $("#d_dpbatam").val(response.harga.d_dpbatam)
                $("#h_luarjawa").val(response.harga.h_luarjawa)
                $("#d_luarjawa").val(response.harga.d_luarjawa)
                $("#h_beli_mpm").val(response.harga.h_beli_mpm)
                $("#h_beli_mpm_bsp").val(response.harga.h_beli_mpm_bsp)
                $("#h_beli_mpm_batam").val(response.harga.h_beli_mpm_batam)
                $("#h_beli_mpm_candy_jawa").val(response.harga.h_beli_mpm_candy_jawa)
                $("#h_beli_mpm_candy_ljawa").val(response.harga.h_beli_mpm_candy_Ljawa)
                .change();
            }
        });
    }
</script>