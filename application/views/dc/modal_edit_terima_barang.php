<?php

$required = "";
// $required = "required";

?>
<!-- modal tambah profile -->
<div class="modal fade" id="edit_terima_barang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Data Profile</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open_multipart($url_edit_terima_barang); ?>
            <div class="modal-body">
                
                <div class="form-group row">
                    <label class="col-sm-4">Sub Branch</label>
                    <div class="col-sm-6">
                        <!-- <input class="form-control" type="text" name="kodeprod" id="kodeprod" required /> -->
                        <select name="site_code" id="site_code_profile" class="form-control" <?= $required; ?>>
                        </select>
                        <input class="form-control" type="text" name="id_profile" id="id" hidden>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Nama DP Afiliasi</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="nama" id="nama_profile" placeholder="contoh : PT Javas Karya Tripta" <?= $required; ?>/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Status Afiliasi</label>
                    <div class="col-sm-6">
                        <select class="form-control" name="status_afiliasi" id="status_afiliasi_profile">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="head office">Head Office</option>
                            <option value="sub branch">Sub Branch</option>
                            <option value="stock point">Stock Point</option>
                            <option value="sales office">Sales Office</option>
                        </select>
                    </div> 
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Alamat</label>
                    <div class="col-sm-6">
                        <textarea name="alamat" id="alamat_profile" class="form-control" cols="30" rows="5" <?= $required; ?>></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Provinsi</label>
                    <div class="col-sm-6">
                        <select name="propinsi" id="propinsi_profile" class="form-control" <?= $required; ?>>

                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Kota / Kabupaten</label>
                    <div class="col-sm-6">
                        <select name="kabupaten" id="kabupaten_profile" class="form-control" <?= $required; ?>>

                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Kecamatan</label>
                    <div class="col-sm-6">
                        <select name="kecamatan" id="kecamatan_profile" class="form-control" <?= $required; ?>>

                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Kelurahan</label>
                    <div class="col-sm-6">
                        <select name="kelurahan" id="kelurahan_profile" class="form-control" <?= $required; ?>>

                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Kodepos</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="kodepos" id="kodepos_profile" <?= $required; ?> />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">No. Telp</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="telp" id="telp_profile" <?= $required; ?> />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Status Properti</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="status_properti"  id="status_properti_profile" <?= $required; ?>>
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="milik sendiri">Milik Sendiri</option>
                            <option value="sewa">Sewa</option>
                        </select>
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Periode Sewa</label>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-5"><input class="form-control" type="date" name="sewa_from" id="sewa_from_profile" <?= $required; ?> /></div> s/d
                            <div class="col-sm-5"><input class="form-control" type="date" name="sewa_to" id="sewa_to_profile" <?= $required; ?> /></div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Harga Sewa (per tahun)</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" name="harga_sewa" id="harga_sewa_profile" <?= $required; ?> />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Bentuk Bangunan</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="bentuk_bangunan" id="bentuk_bangunan_profile" <?= $required; ?>>
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="gudang">Gudang</option>
                            <option value="ruko">Ruko</option>
                            <option value="rumah">Rumah</option>
                        </select>
                    </div> 
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Foto Lokasi</label>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <img alt="" id="img_tampak_depan_profile" style='max-width: 60%;'>
                                <br>
                                Foto Tampak Depan
                                <input class="form-control" type="file" name="foto_tampak_depan_profile" id="foto_tampak_depan" <?= $required; ?> />
                            </div>
                            <div class="col-sm-6">
                                <img alt="" id="img_gudang_profile" style='max-width: 60%;'>
                                <br>
                                Foto Gudang
                                <input class="form-control" type="file" name="foto_gudang" id="foto_gudang_profile" <?= $required; ?> />
                            </div>                   
                        </div>
                    </div>
                </div>   
                <div class="form-group row">
                    <label class="col-sm-4"></label>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <img alt="" id="img_kantor_profile" style='max-width: 60%;'>
                                <br>
                                Foto Kantor
                                <input class="form-control" type="file" name="foto_kantor" id="foto_kantor_profile" <?= $required; ?> />
                            </div>
                            <div class="col-sm-6">
                                <img alt="" id="img_area_loading_gudang_profile" style='max-width: 60%;'>
                                <br>
                                Foto Area Loading Gudang
                                <input class="form-control" type="file" name="foto_area_loading_gudang" id="foto_area_loading_gudang_profile" <?= $required; ?> />
                            </div>                   
                        </div>
                    </div>
                </div>   

                <div class="form-group row">
                    <label class="col-sm-4"></label>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <img alt="" id="img_gudang_baik_profile" style='max-width: 60%;'>
                                <br>
                                Foto Gudang Baik
                                <input class="form-control" type="file" name="foto_gudang_baik" id="foto_gudang_baik_profile" <?= $required; ?> />
                            </div>
                            <div class="col-sm-6">
                                <img alt="" id="img_gudang_retur_profile" style='max-width: 60%;'>
                                <br>
                                Foto Gudang Retur
                                <input class="form-control" type="file" name="foto_gudang_retur" id="foto_gudang_retur_profile" <?= $required; ?> />
                            </div>                   
                        </div>
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
    function addProfile() 
    {
        $.ajax({
            success: function(response) {
                console.log(response.add_profile);
                $("#tambah_profile").modal() // Buka Modal
                $('#id').val('') // parameter
                $('#site_code_profile').val('')
                $('#subbranch_profile').val('')
                $('#nama_profile').val('')
                $('#status_afiliasi_profile').val('')
                $('#alamat_profile').val('')
                $('#propinsi_profile').val('')
                $('#kabupaten_profile').val('')
                $('#kecamatan_profile').val('')
                $('#kelurahan_profile').val('')
                $('#kodepos_profile').val('')
                $('#telp_profile').val('')
                $('#status_properti_profile').val('')
                $('#sewa_from_profile').val('')
                $('#sewa_to_profile').val('')
                $('#harga_sewa_profile').val('')
                $('#bentuk_bangunan_profile').val('')
                $('#img_tampak_depan_profile').attr('src','')
                $('#img_gudang_profile').attr('src','')
                $('#img_kantor_profile').attr('src','')
                $('#img_area_loading_gudang_profile').attr('src','')
                $('#img_gudang_baik_profile').attr('src','')
                $('#img_gudang_retur_profile').attr('src','')
                .change();
                
            }
        });

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('database_afiliasi/provinsi') ?>',
            success: function(hasil_provinsi) {
                $("select[name = propinsi]").html(hasil_provinsi);
            }
        });

        $("select[name = propinsi]").on("change", function() {
            var id_provinsi_terpilih = $("option:selected", this).attr("id_provinsi");
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('database_afiliasi/kabupaten') ?>',
                data: 'id_provinsi=' + id_provinsi_terpilih,
                success: function(hasil_kabupaten) {
                    $("select[name = kabupaten]").html(hasil_kabupaten);
                }
            });

        });

        $("select[name = kabupaten]").on("change", function() {
            var id_kabupaten_terpilih = $("option:selected", this).attr("id_kabupaten");
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('database_afiliasi/kecamatan') ?>',
                data: 'id_kabupaten=' + id_kabupaten_terpilih,
                success: function(hasil_kecamatan) {
                    $("select[name = kecamatan]").html(hasil_kecamatan);
                }
            });
        });

        $("select[name = kecamatan]").on("change", function() {
            var id_kecamatan_terpilih = $("option:selected", this).attr("id_kecamatan");

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('database_afiliasi/kelurahan') ?>',
                data: 'id_kecamatan=' + id_kecamatan_terpilih,
                success: function(hasil_kelurahan) {
                    $("select[name = kelurahan]").html(hasil_kelurahan);
                }
            });
        });
    }
    
    function editProfile(params) 
    {
        $.ajax({
            type: "GET",
            url: "<?= base_url('database_afiliasi/get_dbafiliasi') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function(response) {
                console.log(response.get_profile);
                $("#tambah_profile").modal() // Buka Modal
                $('#id').val(params) // parameter
                $('#site_code_profile').val(response.get_profile.site_code)
                $('#subbranch_profile').val(response.get_profile.nama_comp)
                $('#nama_profile').val(response.get_profile.nama)
                $('#status_afiliasi_profile').val(response.get_profile.status_afiliasi)
                $('#alamat_profile').val(response.get_profile.alamat)
                $('#propinsi_profile').val(response.get_profile.propinsi)
                $('#kabupaten_profile').val(response.get_profile.kota)
                $('#kecamatan_profile').val(response.get_profile.kecamatan)
                $('#kelurahan_profile').val(response.get_profile.kelurahan)
                $('#kodepos_profile').val(response.get_profile.kodepos)
                $('#telp_profile').val(response.get_profile.telp)
                $('#status_properti_profile').val(response.get_profile.status_properti)
                $('#sewa_from_profile').val(response.get_profile.sewa_from)
                $('#sewa_to_profile').val(response.get_profile.sewa_to)
                $('#harga_sewa_profile').val(response.get_profile.harga_sewa)
                $('#bentuk_bangunan_profile').val(response.get_profile.bentuk_bangunan)
                $("#img_tampak_depan_profile").attr('src', '<?=base_url().'/assets/file/database_afiliasi/';?>'.concat(response.get_profile.foto_tampak_depan))
                $("#img_gudang_profile").attr('src', '<?=base_url().'/assets/file/database_afiliasi/';?>'.concat(response.get_profile.foto_gudang))
                $("#img_kantor_profile").attr('src', '<?=base_url().'/assets/file/database_afiliasi/';?>'.concat(response.get_profile.foto_kantor))
                $("#img_area_loading_gudang_profile").attr('src', '<?=base_url().'/assets/file/database_afiliasi/';?>'.concat(response.get_profile.foto_area_loading_gudang))
                $("#img_gudang_baik_profile").attr('src', '<?=base_url().'/assets/file/database_afiliasi/';?>'.concat(response.get_profile.foto_gudang_baik))
                $("#img_gudang_retur_profile").attr('src', '<?=base_url().'/assets/file/database_afiliasi/';?>'.concat(response.get_profile.foto_gudang_retur))
                // $('#foto_tampak_depan').val(response.get_profile.foto_tampak_depan)
                // $('#foto_gudang').val(response.get_profile.foto_gudang)
                // $('#foto_kantor').val(response.get_profile.foto_kantor)
                // $('#foto_area_loading_gudang').val(response.get_profile.foto_area_loading_gudang)
                // $('#foto_gudang_baik').val(response.get_profile.foto_gudang_baik)
                // $('#foto_gudang_retur').val(response.get_profile.foto_gudang_retur)
                .change();
            }
        });

        $(document).ready(function() {
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('database_afiliasi/provinsi') ?>',
            success: function(hasil_provinsi) {
                $("select[name = propinsi]").html(hasil_provinsi);
            }
        });

        $("select[name = propinsi]").on("change", function() {
            var id_provinsi_terpilih = $("option:selected", this).attr("id_provinsi");
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('database_afiliasi/kabupaten') ?>',
                data: 'id_provinsi=' + id_provinsi_terpilih,
                success: function(hasil_kabupaten) {
                    $("select[name = kabupaten]").html(hasil_kabupaten);
                }
            });

        });

        $("select[name = kabupaten]").on("change", function() {
            var id_kabupaten_terpilih = $("option:selected", this).attr("id_kabupaten");
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('database_afiliasi/kecamatan') ?>',
                data: 'id_kabupaten=' + id_kabupaten_terpilih,
                success: function(hasil_kecamatan) {
                    $("select[name = kecamatan]").html(hasil_kecamatan);
                }
            });
        });

        $("select[name = kecamatan]").on("change", function() {
            var id_kecamatan_terpilih = $("option:selected", this).attr("id_kecamatan");

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('database_afiliasi/kelurahan') ?>',
                data: 'id_kecamatan=' + id_kecamatan_terpilih,
                success: function(hasil_kelurahan) {
                    $("select[name = kelurahan]").html(hasil_kelurahan);
                }
            });
        });
    });

    }
</script>