<?php

// $required = "";
$required = "required";

?>
<!-- modal tambah profile -->
<div class="modal fade" id="tambah_profile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Tambah Proforma</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open($url); ?>
            <div class="modal-body">

                <div class="form-group row">
                    <label class="col-sm-3">Principal</label>
                    <div class="col-sm-8">
                        <!-- <input class="form-control" type="text" name="kodeprod" id="kodeprod" required /> -->
                        <select name="supp" class="form-control" required>
                        </select>
                        <input class="form-control" type="text" name="id_profile" id="id" hidden>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3">Kode produk</label>
                    <div class="col-sm-8">
                        <!-- <input class="form-control" type="text" name="kodeprod" id="kodeprod" required /> -->
                        <select name="kodeprod_supp" class="form-control" required>
                        </select>
                        <input class="form-control" type="text" name="id_profile" id="id" hidden>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3">Unit</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="unit" required />
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-3">Expired Date</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="date" name="ed" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3">Batch Number</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="batch_number" required />
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
    function addProfile() {

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
                $('#img_tampak_depan_profile').attr('src', '')
                $('#img_gudang_profile').attr('src', '')
                $('#img_kantor_profile').attr('src', '')
                $('#img_area_loading_gudang_profile').attr('src', '')
                $('#img_gudang_baik_profile').attr('src', '')
                $('#img_gudang_retur_profile').attr('src', '')
                $('#foto_tampak_depan_profile').attr("required", true)
                $('#foto_gudang_profile').attr("required", true)
                $('#foto_kantor_profile').attr("required", true)
                $('#foto_area_loading_gudang_profile').attr("required", true)
                $('#foto_gudang_baik_profile').attr("required", true)
                $('#foto_gudang_retur_profile').attr("required", true)
                    .change();
            }
        });

        $("select[name = supp]").on("change", function() {
            var supp_terpilih = $("option:selected", this).attr("supp");
            console.log(supp_terpilih)

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('rtd/get_kodeprod_supp') ?>',
                data: 'supp=' + supp_terpilih,
                success: function(hasil_kodeprod_supp) {
                    $("select[name = kodeprod_supp]").html(hasil_kodeprod_supp);
                }
            });
        });

    }

    function editProfile(params) {
        $('#foto_tampak_depan_profile').removeAttr('required');
        $('#foto_gudang_profile').removeAttr('required');
        $('#foto_kantor_profile').removeAttr('required');
        $('#foto_area_loading_gudang_profile').removeAttr('required');
        $('#foto_gudang_baik_profile').removeAttr('required');
        $('#foto_gudang_retur_profile').removeAttr('required');
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
                $("#img_tampak_depan_profile").attr('src', '<?= base_url() . '/assets/file/database_afiliasi/'; ?>'.concat(response.get_profile.foto_tampak_depan))
                $("#img_gudang_profile").attr('src', '<?= base_url() . '/assets/file/database_afiliasi/'; ?>'.concat(response.get_profile.foto_gudang))
                $("#img_kantor_profile").attr('src', '<?= base_url() . '/assets/file/database_afiliasi/'; ?>'.concat(response.get_profile.foto_kantor))
                $("#img_area_loading_gudang_profile").attr('src', '<?= base_url() . '/assets/file/database_afiliasi/'; ?>'.concat(response.get_profile.foto_area_loading_gudang))
                $("#img_gudang_baik_profile").attr('src', '<?= base_url() . '/assets/file/database_afiliasi/'; ?>'.concat(response.get_profile.foto_gudang_baik))
                $("#img_gudang_retur_profile").attr('src', '<?= base_url() . '/assets/file/database_afiliasi/'; ?>'.concat(response.get_profile.foto_gudang_retur))
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