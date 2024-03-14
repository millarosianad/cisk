<?php

$required = "";
?>

<!-- modal asset -->
<div class="modal fade" id="tambah_asset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Tambah Asset</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open($url_asset); ?>
            <div class="modal-body">

                <div class="form-group row">
                    <label class="col-sm-4">Sub Branch</label>
                    <div class="col-sm-6">
                        <!-- <input class="form-control" type="text" name="kodeprod" id="kodeprod" required /> -->
                        <select name="site_code" id="site_code_asset" class="form-control" <?= $required; ?>>
                        </select>
                        <input class="form-control" type="text" name="id_asset" id="id_asset" hidden>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Jenis Asset</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="jenis_asset" id="jenis_asset_asset" placeholder="jenis asset" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Merk</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="merk" id="merk_asset" placeholder="merk" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Type</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="type" id="type_asset" placeholder="type" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Pembelian</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="date" name="tanggal_pembelian" id="tanggal_pembelian_asset" placeholder="" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Department / Divisi Pemakai</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="divisi_pemakai" id="divisi_pemakai_asset">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="finance">finance</option>
                            <option value="logistik">logistik</option>
                            <option value="hrga">hrga</option>
                            <option value="sales">sales</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Jabatan</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="jabatan_pemakai" id="jabatan_pemakai_asset">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="direktur">direktur</option>
                            <option value="bm">bm (bussiness manager)</option>
                            <option value="sm">sm (Sales manager)</option>
                            <option value="hsb">hsb</option>
                            <option value="abm">abm (area bussiness manager)</option>
                            <option value="spv_sales">spv sales</option>
                            <option value="salesforce_grosir">salesforce grosir</option>
                            <option value="salesforce_reguler">salesforce reguler</option>
                            <option value="salesforce_mt">salesforce mt</option>
                            <option value="salesforce_apotik">salesforce apotik</option>
                            <option value="logistik_manager">logistik manager</option>
                            <option value="spv_logistik">spv logistik</option>
                            <option value="kepala_logistik">kepala logistik</option>
                            <option value="admin_logistik">admin logistik</option>
                            <option value="helper_picker">helper / picker</option>
                            <option value="checker">checker</option>
                            <option value="admin_ekspedisi">admin ekspedisi</option>
                            <option value="driver">driver</option>
                            <option value="finance_manager">finance manager</option>
                            <option value="spv_finance">spv finance</option>
                            <option value="admin_spv">admin spv</option>
                            <option value="fakturis">fakturis</option>
                            <option value="kasir">kasir</option>
                            <option value="debitur">debitur</option>
                            <option value="ob">ob</option>
                            <option value="security">security</option>
                        </select>
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
    function addAsset() {
        $.ajax({
            success: function (response) {
                console.log(response.get_asset);
                $("#tambah_asset").modal() // Buka Modal
                $('#id_asset').val('') // parameter
                $('#site_code_asset').val('')
                $('#jenis_asset').val('')
                $('#merk_asset').val('')
                $('#type_asset').val('')
                $('#tanggal_pembelian_asset').val('')
                $('#divisi_pemakai_asset').val('')
                $('#jabatan_pemakai_asset').val('')
                    .change();

            }
        });
    }

    function editAsset(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('database_afiliasi/get_dbafiliasi') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.get_asset);
                $("#tambah_asset").modal() // Buka Modal
                $('#id_asset').val(params) // parameter
                $('#site_code_asset').val(response.get_asset.site_code)
                $('#jenis_asset_asset').val(response.get_asset.jenis_asset)
                $('#merk_asset').val(response.get_asset.merk)
                $('#type_asset').val(response.get_asset.type)
                $('#tanggal_pembelian_asset').val(response.get_asset.tanggal_pembelian)
                $('#divisi_pemakai_asset').val(response.get_asset.divisi_pemakai)
                $('#jabatan_pemakai_asset').val(response.get_asset.jabatan_pemakai)
                    .change();

            }
        });
    }
</script>