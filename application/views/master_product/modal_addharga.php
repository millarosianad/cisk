<div class="modal fade" id="harga" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Tambah Harga</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open('master_product/tambah_harga/'); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4">Kodeprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="kodeprod" id="h_product_id" required readonly/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Namaprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="namaprod" id="h_namaprod" required readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Aktif</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="date" name="tgl" required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Beli DP</label>
                    <div class="col-sm-3">
                        <input id="" class="form-control" type="text" name="hb_dp"/>
                    </div>
                    Disc
                    <div class="col-sm-3">
                        <input id="" class="form-control" type="text" name="db_dp"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Beli BSP</label>
                    <div class="col-sm-3">
                        <input id="" class="form-control" type="text" name="hb_bsp" />
                    </div>
                    Disc
                    <div class="col-sm-3">
                        <input id="" class="form-control" type="text" name="db_bsp"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Beli PBF</label>
                    <div class="col-sm-3">
                        <input id="unit" class="form-control" type="text" name="hb_pbf" />
                    </div>
                    Disc
                    <div class="col-sm-3">
                        <input id="" class="form-control" type="text" name="db_pbf"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga DP</label>
                    <div class="col-sm-3">
                        <input id="" class="form-control" type="text" name="h_dp"/>
                    </div>
                    Disc
                    <div class="col-sm-3">
                        <input id="" class="form-control" type="text" name="d_dp"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga BSP</label>
                    <div class="col-sm-3">
                        <input id="" class="form-control" type="text" name="h_bsp" />
                    </div>
                    Disc
                    <div class="col-sm-3">
                        <input id="" class="form-control" type="text" name="d_bsp"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga PBF</label>
                    <div class="col-sm-3">
                        <input id="unit" class="form-control" type="text" name="h_pbf" />
                    </div>
                    Disc
                    <div class="col-sm-3">
                        <input id="" class="form-control" type="text" name="d_pbf"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Khusus Batam (Dji Go Lak)</label>
                    <div class="col-sm-3">
                        <input id="unit" class="form-control" type="text" name="hk_batam" />
                    </div>
                    Disc
                    <div class="col-sm-3">
                        <input id="" class="form-control" type="text" name="dk_batam"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga DP (Luar Pulau Jawa)</label>
                    <div class="col-sm-3">
                        <input id="unit" class="form-control" type="text" name="h_dp_ljawa" />
                    </div>
                    Disc
                    <div class="col-sm-3">
                        <input id="" class="form-control" type="text" name="d_dp_ljawa"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Beli MPM - DP</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="hbm_dp"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Beli MPM - BSP</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="hbm_bsp" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Beli MPM Batam</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="hbm_batam"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Beli MPM - Candy (Pulau Jawa)</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="hbm_candy_pj" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Beli MPM - Candy (Luar Pulau Jawa)</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="hbm_candy_lj" value=""/>
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
    function getHargaIDProduct(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('master_product/get_productsid') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.edit);
                $("#harga").modal() // Buka Modal
                $('#h_product_id').val(params) // parameter
                $('#h_namaprod').val(response.edit.namaprod).change();

            }
        });
    }
</script>