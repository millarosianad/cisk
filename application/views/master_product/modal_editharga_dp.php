<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Harga</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php echo form_open('master_product/update_harga_dp/'); ?>
        <div class="modal-body">
            <div class="form-group row">
                <input class="form-control" type="text" name="harga_id" required id="harga_id" hidden/>
                <label class="col-sm-4">kodeprod</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="kodeprod" required id="kodeprod" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">namaprod</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="namaprod" required id="namaprod" readonly />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">tgl_naik_harga</label>
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="tgl_naik_harga" id="tgl_naik_harga" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">branch_name</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="branch_name" id="branch_name" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">nama_comp</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="nama_comp" id="nama_comp" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">site_code</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="site_code" id="site_code" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">h_jual_dp_grosir</label>
                <div class="col-sm-6">
                    <input id="h_jual_dp_grosir" class="form-control" type="text" name="h_jual_dp_grosir"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">h_jual_dp_retail</label>
                <div class="col-sm-6">
                    <input id="h_jual_dp_retail" class="form-control" type="text" name="h_jual_dp_retail"/>
                </div>
            </div><div class="form-group row">
                <label class="col-sm-4">h_jual_dp_motoris_retail</label>
                <div class="col-sm-6">
                    <input id="h_jual_dp_motoris_retail" class="form-control" type="text" name="h_jual_dp_motoris_retail"/>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <?php echo form_submit('submit','Update', 'class="btn btn-success" required');?>
            <?php echo form_close();?>
        </div>
        </div>
    </div>
</div>

<script>
    function get_hargaproduct_dp(params){
        $.ajax({
            type: "GET",
            url: "<?= base_url('master_product/get_productsid') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.harga_dp);
                $("#edit").modal() // Buka Modal
                $("#harga_id").val(params)
                $("#kodeprod").val(response.harga_dp.kodeprod)
                $("#namaprod").val(response.harga_dp.namaprod)
                $("#tgl_naik_harga").val(response.harga_dp.tgl_naik_harga)
                $("#branch_name").val(response.harga_dp.branch_name)
                $("#nama_comp").val(response.harga_dp.nama_comp)
                $("#h_jual_dp_grosir").val(response.harga_dp.h_jual_dp_grosir)
                $("#h_jual_dp_retail").val(response.harga_dp.h_jual_dp_retail)
                $("#h_jual_dp_motoris_retail").val(response.harga_dp.h_jual_dp_motoris_retail)
                $("#site_code").val(response.harga_dp.site_code)
                .change();
            }
        });
    }
</script>