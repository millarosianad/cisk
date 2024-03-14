<?php

$required = "";
?>

<!-- modal itasset -->
<div class="modal fade" id="tambah_it_asset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Data IT Asset</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open($url_it_asset); ?>
            <div class="modal-body">

                <div class="form-group row">
                    <label class="col-sm-4">Sub Branch</label>
                    <div class="col-sm-6">
                        <!-- <input class="form-control" type="text" name="kodeprod" id="kodeprod" <?= $required; ?> /> -->
                        <select name="site_code" id="site_code_it_asset" class="form-control" <?= $required; ?>>
                        </select>
                        <input class="form-control" type="text" name="id_it_asset" id="id_it_asset" hidden>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Nama Asset</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="nama_asset" id="nama_asset_it_asset" placeholder="nama_asset" <?= $required; ?> />
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Merk</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="merk" id="merk_it_asset" placeholder="merk" <?= $required; ?> />
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Type</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="type" id="type_it_asset" placeholder="type" <?= $required; ?> />
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Pembelian</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="date" name="tanggal_pembelian" id="tanggal_pembelian_it_asset" placeholder="tanggal_pembelian" <?= $required; ?> />
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Operating System</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="operating_system" id="operating_system_it_asset" placeholder="operating_system" <?= $required; ?> />
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Processor</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="processor" id="processor_it_asset" placeholder="processor" <?= $required; ?> />
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Ram</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="ram" id="ram_it_asset" placeholder="ram" <?= $required; ?> />
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Storage</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="storage" id="storage_it_asset" placeholder="storage" <?= $required; ?> />
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Kapasitas Baterai</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="kapasitas_baterai" id="kapasitas_baterai_it_asset" placeholder="kapasitas baterai" <?= $required; ?> />
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Department / Divisi Pemakai</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="divisi_pemakai" id="divisi_pemakai_it_asset">
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
                        <select class="form-control" value="0" name="jabatan_pemakai" id="jabatan_pemakai_it_asset">
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
                <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success"'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function addIt_asset() {
        $.ajax({
            success: function (response) {
                console.log(response.get_it_asset);
                $("#tambah_it_asset").modal() // Buka Modal
                $('#id_it_asset').val('') // parameter
                $('#site_code_it_asset').val('')
                $('#nama_asset_it_asset').val('')
                $('#merk_it_asset').val('')
                $('#type_it_asset').val('')
                $('#tanggal_pembelian_it_asset').val('')
                $('#operating_system_it_asset').val('')
                $('#processor_it_asset').val('')
                $('#ram_it_asset').val('')
                $('#storage_it_asset').val('')
                $('#kapasitas_baterai_it_asset').val('')
                $('#divisi_pemakai_it_asset').val('')
                $('#jabatan_pemakai_it_asset').val('')
                    .change();

            }
        });
    }

    function editIt_asset(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('database_afiliasi/get_dbafiliasi') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.get_it_asset);
                $("#tambah_it_asset").modal() // Buka Modal
                $('#id_it_asset').val(params) // parameter
                $('#site_code_it_asset').val(response.get_it_asset.site_code)
                $('#nama_asset_it_asset').val(response.get_it_asset.nama_asset)
                $('#merk_it_asset').val(response.get_it_asset.merk)
                $('#type_it_asset').val(response.get_it_asset.type)
                $('#tanggal_pembelian_it_asset').val(response.get_it_asset.tanggal_pembelian)
                $('#operating_system_it_asset').val(response.get_it_asset.operating_system)
                $('#processor_it_asset').val(response.get_it_asset.processor)
                $('#ram_it_asset').val(response.get_it_asset.ram)
                $('#storage_it_asset').val(response.get_it_asset.storage)
                $('#kapasitas_baterai_it_asset').val(response.get_it_asset.kapasitas_baterai)
                $('#divisi_pemakai_it_asset').val(response.get_it_asset.divisi_pemakai)
                $('#jabatan_pemakai_it_asset').val(response.get_it_asset.jabatan_pemakai)
                    .change();

            }
        });
    }
</script>