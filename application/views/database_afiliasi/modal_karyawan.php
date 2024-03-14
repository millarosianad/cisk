<?php
$required = "";
?>

<!-- modal karyawan -->
<div class="modal fade" id="tambah_karyawan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Data Karyawan</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open($url_karyawan); ?>
            <div class="modal-body">

                <div class="form-group row">
                    <label class="col-sm-4">Sub Branch</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="id_krywn" id="id_krywn" hidden >
                        <select name="site_code" id="site_code_krywn" class="form-control" <?= $required; ?>>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Nama (Sesuai KTP)</label>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <input class="form-control" type="text" name="nama" id="nama_krywn" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Jenis Kelamin</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="jenis_kelamin" id="jenis_kelamin_krywn">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="pria">Pria</option>
                            <option value="wanita">Wanita</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tempat Tanggal Lahir</label>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <input class="form-control" type="text" name="tempat" id="tempat_krywn"
                                    placeholder="tempat" <?= $required; ?> />
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control" type="date" name="tanggal_lahir" id="tanggal_lahir_krywn"
                                    <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tingkat Pendidikan</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="tingkat_pendidikan" id="tingkat_pendidikan_krywn">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="sarjana_sederajat">Sarjana/Sederajat</option>
                            <option value="diploma_sederajat">Diploma/Sederajat </option>
                            <option value="slta_sederajat">SLTA/Sederajat</option>
                            <option value="sltp_sederajat">SLTP/Sederajat</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Status Pernikahan</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="status_pernikahan" id="status_pernikahan_krywn">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="belum_menikah">Belum Menikah</option>
                            <option value="menikah">Menikah</option>
                            <option value="cerai">Cerai</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Department / Divisi</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="department" id="department_krywn">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="finance">Finance</option>
                            <option value="logistik">Logistik</option>
                            <option value="hrga">Hrga</option>
                            <option value="sales">Sales</option>
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-4">Jabatan</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="jabatan" id="jabatan_krywn">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="direktur">Direktur</option>
                            <option value="bm">BM (Bussiness Manager)</option>
                            <option value="sm">SM (Sales Manager)</option>
                            <option value="hsb">HSB</option>
                            <option value="abm">ABM (Area Bussiness Manager)</option>
                            <option value="spv_sales">Spv Sales</option>
                            <option value="salesforce_grosir">Salesforce Grosir</option>
                            <option value="salesforce_reguler">Salesforce Reguler</option>
                            <option value="salesforce_mt">Salesforce Mt</option>
                            <option value="salesforce_apotik">Salesforce Apotik</option>
                            <option value="logistik_manager">Logistik Manager</option>
                            <option value="spv_logistik">Spv Logistik</option>
                            <option value="kepala_logistik">Kepala Logistik</option>
                            <option value="admin_logistik">Admin Logistik</option>
                            <option value="helper_picker">Helper / Picker</option>
                            <option value="checker">Checker</option>
                            <option value="admin_ekspedisi">Admin Ekspedisi</option>
                            <option value="driver">Driver</option>
                            <option value="finance_manager">Finance Manager</option>
                            <option value="spv_finance">Spv Finance</option>
                            <option value="admin_spv">Admin Spv</option>
                            <option value="fakturis">Fakturis</option>
                            <option value="kasir">Kasir</option>
                            <option value="debitur">Debitur</option>
                            <option value="ob">Ob</option>
                            <option value="security">Security</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Status Karyawan</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="status_karyawan" id="status_karyawan_krywn">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="tetap">Tetap</option>
                            <option value="kontrak">Kontrak</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Masuk Kerja</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="date" name="tanggal_masuk_kerja"
                            id="tanggal_masuk_kerja_krywn" <?= $required; ?> />
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
    function addKaryawan() {
        $.ajax({
            success: function (response) {
                console.log(response.get_karyawan);
                $("#tambah_karyawan").modal() // Buka Modal
                $('#id_krywn').val('') // parameter
                $('#site_code_krywn').val('')
                $('#nama_krywn').val('')
                $('#jenis_kelamin_krywn').val('')
                $('#tempat_krywn').val('')
                $('#tanggal_lahir_krywn').val('')
                $('#tingkat_pendidikan_krywn').val('')
                $('#status_pernikahan_krywn').val('')
                $('#department_krywn').val('')
                $('#jabatan_krywn').val('')
                $('#status_karyawan_krywn').val('')
                $('#tanggal_masuk_kerja_krywn').val('')
                    .change();

            }
        });
    }

    function editKaryawan(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('database_afiliasi/get_dbafiliasi') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.get_karyawan);
                $("#tambah_karyawan").modal() // Buka Modal
                $('#id_krywn').val(params) // parameter
                $('#site_code_krywn').val(response.get_karyawan.site_code)
                $('#nama_krywn').val(response.get_karyawan.nama)
                $('#jenis_kelamin_krywn').val(response.get_karyawan.jenis_kelamin)
                $('#tempat_krywn').val(response.get_karyawan.tempat)
                $('#tanggal_lahir_krywn').val(response.get_karyawan.tanggal_lahir)
                $('#tingkat_pendidikan_krywn').val(response.get_karyawan.tingkat_pendidikan)
                $('#status_pernikahan_krywn').val(response.get_karyawan.status_pernikahan)
                $('#department_krywn').val(response.get_karyawan.department)
                $('#jabatan_krywn').val(response.get_karyawan.jabatan)
                $('#status_karyawan_krywn').val(response.get_karyawan.status_karyawan)
                $('#tanggal_masuk_kerja_krywn').val(response.get_karyawan.tanggal_masuk_kerja)
                    .change();

            }
        });
    }
    
</script>

