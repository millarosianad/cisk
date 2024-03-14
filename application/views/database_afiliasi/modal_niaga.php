<?php
$required = "";

?>

<!-- modal niaga -->
<div class="modal fade" id="tambah_niaga" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Data Niaga</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open($url_niaga); ?>
            <div class="modal-body">

                <div class="form-group row">
                    <label class="col-sm-4">Sub Branch</label>
                    <div class="col-sm-6">
                        <!-- <input class="form-control" type="text" name="kodeprod" id="kodeprod" <?= $required; ?> /> -->
                        <select name="site_code" id="site_code_niaga" class="form-control" <?= $required; ?>>
                        </select>
                        <input class="form-control" type="text" name="id_niaga" id="id_niaga" hidden>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Jenis Kendaraan</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="jenis_kendaraan" id="jenis_kendaraan_niaga">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="mobil - engkel">Mobil - Engkel</option>
                            <option value="mobil - blind van">Mobil - Blind Van</option>
                            <option value="mobil - box carry">Mobil - Box Carry</option>
                            <option value="motor - three wheeler">Motor - Three Wheeler</option>
                            <option value="motor - beat">Motor - Beat</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Kepemilikan</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="kepemilikan" id="kepemilikan_niaga">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="milik PT">milik PT</option>
                            <option value="sewa">sewa</option>
                        </select>
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Bahan Bakar</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="bahan_bakar" id="bahan_bakar_niaga">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="bensin">Bensin</option>
                            <option value="solar">Solar</option>
                        </select>
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">No Polisi</label>                    
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="no_polisi" id="no_polisi_niaga" placeholder="nomor polisi" <?= $required; ?> />
                        </div>                    
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tahun Pembuatan</label>                    
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="tahun_pembuatan" id="tahun_pembuatan_niaga" placeholder="tahun pembuatan" <?= $required; ?> />
                        </div>                    
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Pajak Berakhir</label>                    
                        <div class="col-sm-6">
                            <input class="form-control" type="date" name="tanggal_pajak_berakhir" id="tanggal_pajak_berakhir_niaga" placeholder="tanggal pajak terakhir" <?= $required; ?> />
                        </div>                    
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Pajak KIR</label>                    
                        <div class="col-sm-6">
                            <input class="form-control" type="date" name="tanggal_pajak_kir" id="tanggal_pajak_kir_niaga" placeholder="tanggal pajak kir" <?= $required; ?> />
                        </div>                    
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Nama Vendor</label>                    
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="vendor" id="vendor_niaga" placeholder="vendor" <?= $required; ?> />
                    </div>                    
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Awal Sewa</label>                    
                    <div class="col-sm-6">
                        <input class="form-control" type="date" name="tanggal_awal_sewa" id="tanggal_awal_sewa_niaga" placeholder="tanggal awal sewa" <?= $required; ?> />
                    </div>                    
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Akhir Sewa</label>                    
                    <div class="col-sm-6">
                        <input class="form-control" type="date" name="tanggal_akhir_sewa" id="tanggal_akhir_sewa_niaga" placeholder="tanggal akhir sewa" <?= $required; ?> />
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
    function addNiaga() {
        $.ajax({
            success: function (response) {
                console.log(response.get_niaga);
                $("#tambah_niaga").modal() // Buka Modal
                $('#id_niaga').val('') // parameter
                $('#site_code_niaga').val('')
                $('#jenis_kendaraan_niaga').val('')
                $('#kepemilikan_niaga').val('')
                $('#bahan_bakar_niaga').val('')
                $('#no_polisi_niaga').val('')
                $('#tahun_pembuatan_niaga').val('')
                $('#tanggal_pajak_berakhir_niaga').val('')
                $('#tanggal_pajak_kir_niaga').val('')
                $('#vendor_niaga').val('')
                $('#tanggal_awal_sewa_niaga').val('')
                $('#tanggal_akhir_sewa_niaga').val('')
                    .change();

            }
        });
    }

    function editNiaga(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('database_afiliasi/get_dbafiliasi') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.get_niaga);
                $("#tambah_niaga").modal() // Buka Modal
                $('#id_niaga').val(params) // parameter
                $('#site_code_niaga').val(response.get_niaga.site_code)
                $('#jenis_kendaraan_niaga').val(response.get_niaga.jenis_kendaraan)
                $('#kepemilikan_niaga').val(response.get_niaga.kepemilikan)
                $('#bahan_bakar_niaga').val(response.get_niaga.bahan_bakar)
                $('#no_polisi_niaga').val(response.get_niaga.no_polisi)
                $('#tahun_pembuatan_niaga').val(response.get_niaga.tahun_pembuatan)
                $('#tanggal_pajak_berakhir_niaga').val(response.get_niaga.tanggal_pajak_berakhir)
                $('#tanggal_pajak_kir_niaga').val(response.get_niaga.tanggal_pajak_kir)
                $('#vendor_niaga').val(response.get_niaga.vendor)
                $('#tanggal_awal_sewa_niaga').val(response.get_niaga.tanggal_awal_sewa)
                $('#tanggal_akhir_sewa_niaga').val(response.get_niaga.tanggal_akhir_sewa)
                    .change();

            }
        });
    }
</script>