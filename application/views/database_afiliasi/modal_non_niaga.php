<?php

$required = "";
?>

<!-- modal nonniaga -->
<div class="modal fade" id="tambah_non_niaga" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Data Non Niaga</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open($url_non_niaga); ?>
            <div class="modal-body">

                <div class="form-group row">
                    <label class="col-sm-4">Sub Branch</label>
                    <div class="col-sm-6">
                        <select name="site_code" id="site_code_non_niaga" class="form-control" <?= $required; ?>>
                        </select>
                        <input class="form-control" type="text" name="id_non_niaga" id="id_non_niaga" hidden>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Jenis Kendaraan</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="jenis_kendaraan" id="jenis_kendaraan_non_niaga">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="mobil - avanza">mobil - avanza</option>
                            <option value="mobil - xenia">mobil - xenia</option>
                            <option value="mobil - evalia">mobil - evalia</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Kepemilikan</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="kepemilikan" id="kepemilikan_non_niaga">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="milik_pt">milik PT</option>
                            <option value="sewa">sewa</option>
                        </select>
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Nama Pemakai</label>                    
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="nama_pemakai" id="nama_pemakai_non_niaga" placeholder="nama pemakai" <?= $required; ?> />
                    </div>                    
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Jabatan</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="jabatan" id="jabatan_non_niaga">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="direktur">direktur</option>
                            <option value="bm">bm (bussiness manager)</option>
                            <option value="sm">sm (Sales manager)</option>
                            <option value="hsb">hsb</option>
                            <option value="abm">abm (area bussiness manager)</option>
                            <option value="spv_sales">spv sales</option>
                            <option value="salesforce_grosir">salesforce grosir</option>
                            <option value="salesforce_reguler">salesforce </option>
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
                            <option value="spv_finance">spv finance</option>reguler
                            <option value="admin_spv">admin spv</option>
                            <option value="fakturis">fakturis</option>
                            <option value="kasir">kasir</option>
                            <option value="debitur">debitur</option>
                            <option value="ob">ob</option>
                            <option value="security">security</option>
                        </select>
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Bahan Bakar</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="bahan_bakar" id="bahan_bakar_non_niaga">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="bensin">bensin</option>
                            <option value="solar">solar</option>
                        </select>
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">No Polisi</label>                    
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="no_polisi" id="no_polisi_non_niaga" placeholder="no polisi" <?= $required; ?> />
                    </div>                    
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tahun Pembuatan</label>                    
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="tahun_pembuatan" id="tahun_pembuatan_non_niaga" placeholder="tahun pembuatan" <?= $required; ?> />
                        </div>                    
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Pajak Berakhir</label>                    
                        <div class="col-sm-6">
                            <input class="form-control" type="date" name="tanggal_pajak_berakhir" id="tanggal_pajak_berakhir_non_niaga" placeholder="tanggal pajak berakhir" <?= $required; ?> />
                        </div>                    
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Nama Vendor</label>                    
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="vendor" id="vendor_non_niaga" placeholder="vendor" <?= $required; ?> />
                    </div>                    
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Awal Sewa</label>                    
                    <div class="col-sm-6">
                        <input class="form-control" type="date" name="tanggal_awal_sewa" id="tanggal_awal_sewa_non_niaga" placeholder="tanggal awal sewa" <?= $required; ?> />
                    </div>                    
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Akhir Sewa</label>                    
                    <div class="col-sm-6">
                        <input class="form-control" type="date" name="tanggal_akhir_sewa" id="tanggal_akhir_sewa_non_niaga" placeholder="tanggal akhir sewa" <?= $required; ?> />
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
    function addNon_niaga() {
        $.ajax({
            success: function (response) {
                console.log(response.get_non_niaga);
                $("#tambah_non_niaga").modal() // Buka Modal
                $('#id_non_niaga').val('') // parameter
                $('#site_code_non_niaga').val('')
                $('#jenis_kendaraan_non_niaga').val('')
                $('#kepemilikan_non_niaga').val('')
                $('#nama_pemakai_non_niaga').val('')
                $('#jabatan_non_niaga').val('')
                $('#bahan_bakar_non_niaga').val('')
                $('#no_polisi_non_niaga').val('')
                $('#tahun_pembuatan_non_niaga').val('')
                $('#tanggal_pajak_berakhir_non_niaga').val('')
                $('#tanggal_pajak_kir_non_niaga').val('')
                $('#vendor_non_niaga').val('')
                $('#tanggal_awal_sewa_non_niaga').val('')
                $('#tanggal_akhir_sewa_non_niaga').val('')
                    .change();

            }
        });
    }

    function editNon_niaga(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('database_afiliasi/get_dbafiliasi') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.get_non_niaga);
                $("#tambah_non_niaga").modal() // Buka Modal
                $('#id_non_niaga').val(params) // parameter
                $('#site_code_non_niaga').val(response.get_non_niaga.site_code)
                $('#jenis_kendaraan_non_niaga').val(response.get_non_niaga.jenis_kendaraan)
                $('#kepemilikan_non_niaga').val(response.get_non_niaga.kepemilikan)
                $('#nama_pemakai_non_niaga').val(response.get_non_niaga.nama_pemakai)
                $('#jabatan_non_niaga').val(response.get_non_niaga.jabatan)
                $('#bahan_bakar_non_niaga').val(response.get_non_niaga.bahan_bakar)
                $('#no_polisi_non_niaga').val(response.get_non_niaga.no_polisi)
                $('#tahun_pembuatan_non_niaga').val(response.get_non_niaga.tahun_pembuatan)
                $('#tanggal_pajak_berakhir_non_niaga').val(response.get_non_niaga.tanggal_pajak_berakhir)
                $('#tanggal_pajak_kir_non_niaga').val(response.get_non_niaga.tanggal_pajak_kir)
                $('#vendor_non_niaga').val(response.get_non_niaga.vendor)
                $('#tanggal_awal_sewa_non_niaga').val(response.get_non_niaga.tanggal_awal_sewa)
                $('#tanggal_akhir_sewa_non_niaga').val(response.get_non_niaga.tanggal_akhir_sewa)
                    .change();

            }
        });
    }
</script>