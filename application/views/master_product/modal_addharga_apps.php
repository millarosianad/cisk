<div class="modal fade" id="harga_apps" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">Tambah Harga</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php echo form_open('master_product/tambah_harga_apps/'); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4">Kodeprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="kodeprod" id="h_product_id_apps" required readonly/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Namaprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="namaprod" id="h_namaprod_apps" required readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Aktif</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="date" name="tgl" required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Harga Ritel GT</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="apps_hrg"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Harga Grosir MT</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="apps_hgm"/>
                    </div>
                </div><div class="form-group row">
                    <label class="col-sm-4">Apps Harga Semi Grosir</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="apps_hsg"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Harga Promosi Coret</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="apps_hpc"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Harga Promosi Coret Ritel GT</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="apps_hpc_rg"/>
                    </div>
                </div><div class="form-group row">
                    <label class="col-sm-4">Apps Harga Promosi Coret Grosir MT</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="apps_hpc_gm"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Harga Promosi Coret Semi Grosir</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="apps_hpc_sg"/>
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
    function getHargaIDProduct_Apps(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('master_product/get_productsid') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.harga);
                $("#harga_apps").modal() // Buka Modal
                $('#h_product_id_apps').val(params) // parameter
                $('#h_namaprod_apps').val(response.edit.namaprod).change();
            }
        });
    }
</script>