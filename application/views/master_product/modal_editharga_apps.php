<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Harga</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php echo form_open('master_product/update_harga_apps/'); ?>
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
                <label class="col-sm-4">Apps Harga Ritel GT</label>
                <div class="col-sm-6">
                    <input id="apps_hrg" class="form-control" type="text" name="apps_hrg"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Apps Harga Grosir MT</label>
                <div class="col-sm-6">
                    <input id="apps_hgm" class="form-control" type="text" name="apps_hgm"/>
                </div>
            </div><div class="form-group row">
                <label class="col-sm-4">Apps Harga Semi Grosir</label>
                <div class="col-sm-6">
                    <input id="apps_hsg" class="form-control" type="text" name="apps_hsg"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Apps Harga Promosi Coret</label>
                <div class="col-sm-6">
                    <input id="apps_hpc" class="form-control" type="text" name="apps_hpc"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Apps Harga Promosi Coret Ritel GT</label>
                <div class="col-sm-6">
                    <input id="apps_hpc_rg" class="form-control" type="text" name="apps_hpc_rg"/>
                </div>
            </div><div class="form-group row">
                <label class="col-sm-4">Apps Harga Promosi Coret Grosir MT</label>
                <div class="col-sm-6">
                    <input id="apps_hpc_gm" class="form-control" type="text" name="apps_hpc_gm"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Apps Harga Promosi Coret Semi Grosir</label>
                <div class="col-sm-6">
                    <input id="apps_hpc_sg" class="form-control" type="text" name="apps_hpc_sg"/>
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
    function get_hargaproduct_apps(params){
        $.ajax({
            type: "GET",
            url: "<?= base_url('master_product/get_productsid') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.harga_apps);
                $("#edit").modal() // Buka Modal
                $("#harga_id").val(params)
                $("#kodeprod").val(response.harga_apps.kodeprod)
                $("#namaprod").val(response.harga_apps.namaprod)
                $("#tgl").val(response.harga_apps.tgl)
                $("#apps_hrg").val(response.harga_apps.apps_harga_ritel_gt)
                $("#apps_hgm").val(response.harga_apps.apps_harga_grosir_mt)
                $("#apps_hsg").val(response.harga_apps.apps_harga_semi_grosir)
                $("#apps_hpc").val(response.harga_apps.apps_harga_promosi_coret)
                $("#apps_hpc_rg").val(response.harga_apps.apps_harga_promosi_coret_ritel_gt)
                $("#apps_hpc_gm").val(response.harga_apps.apps_harga_promosi_coret_grosir_mt)
                $("#apps_hpc_sg").val(response.harga_apps.apps_harga_promosi_coret_semi_grosir)
                .change();
            }
        });
    }
</script>