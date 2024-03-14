<div class="modal fade" id="harga_dp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Tambah Harga Jual DP</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open('master_product/tambah_harga_jual_dp/'); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4">Kodeprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="kodeprod" id="h_product_id_dp" required readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Namaprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="namaprod" id="h_namaprod_dp" required readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Naik Harga</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="date" name="tgl_naik_harga" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Jual DP Grosir</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="h_jual_dp_grosir" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Jual DP Retail</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="h_jual_dp_retail" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Jual DP Motoris Retail</label>
                    <div class="col-sm-6">
                        <input id="" class="form-control" type="text" name="h_jual_dp_motoris_retail" />
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
                            <option value="5">Customize</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="customize">
                    <label class="col-sm-4">Customize</label>
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