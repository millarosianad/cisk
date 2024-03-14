<div class="modal fade" id="pricelist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Paket pricelist</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open('master_product/tambah_pricelist/'); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4">Versi</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="versi" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Keterangan (opsional)</label>
                    <div class="col-sm-6">
                        <textarea name="keterangan" class="form-control" cols="30" rows="5"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Naik Harga</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="date" name="tgl_naik_harga" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Status</label>
                    <div class="col-sm-6">
                        <input id="checkbox" type="checkbox" name="status_aktif" value="1">
                        <label for = "checkbox">Aktif</label>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success" required'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

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
</script>