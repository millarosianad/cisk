<?php

// $required = "";
$required = "requried";

?>
<!-- modal tambah profile -->
<div class="modal fade" id="tambah_surat_jalan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Tambah Surat Jalan (barang keluar)</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- <?php echo form_open($url_surat_jalan); ?> -->
            <!-- <?php echo form_open($url_surat_jalan); ?> -->
            <form action="proses_surat_jalan" method="POST">
                <div class="modal-body">

                    <div class="form-group row">
                        <label class="col-sm-3">Nomor PO</label>
                        <div class="col-sm-8">
                            <select name="id_po" id="id_po" class="form-control" <?= $required; ?>>
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-3">Produk</label>
                        <div class="col-sm-8">
                            <select name="kodeprod" id="kodeprod" class="form-control" <?= $required; ?>>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3">Nomor DO</label>
                        <div class="col-sm-8">
                            <!-- <input class="form-control" type="text" name="kodeprod" id="kodeprod" required /> -->
                            <select name="id_do" class="form-control" <?= $required; ?>>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-sm-11"><i>Data Barang Keluar</i></label>
                    </div>

                    <!-- <div class="copy"> -->
                    <!-- <div class="control-group"> -->

                    <div class="form-group row">
                        <label class="col-sm-3">Pilih ED</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="hidden" name="ed_text" id="ed_text" />
                            <select name="ed" class="form-control" required>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3">Pilih Batch Number</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="hidden" name="batch_number_text" id="batch_number_text" />
                            <select name="batch_number" class="form-control" required>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row after-add-more">
                        <label class="col-sm-3">Unit</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="unit" id="unit" required />
                        </div>
                    </div>

                    <!-- </div> -->
                    <!-- </div> -->

                    <!-- <div class="copy invisiblex d-none" id="copy">
                        <div class="control-group">
                            <hr>
                            <div class="form-group row">
                                <label class="col-sm-3">ED Tambahan</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="hidden" name="ed_text[]" id="ed_text" />
                                    <select name="ed_tambahan" class="form-control" <?= $required; ?>>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3">Batch Number Tambahan</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="hidden" name="batch_number_text[]" id="batch_number_text" />
                                    <select name="batch_number" class="form-control" <?= $required; ?>>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3">Unit Tambahan</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="unit[]" id="unit" value="" />
                                </div>
                            </div>

                            <br>
                            <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                        </div>
                    </div> -->


                </div>
                <div class="modal-footer">
                    <!-- <button class="btn btn-warning add-more" type="button">
                        <i class="glyphicon glyphicon-plus"></i> Tambah ED
                    </button> -->
                    <input type="submit" class="btn btn-primary" value="submit">

            </form>


            <!-- <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success" required'); ?> -->
            <!-- <?php echo form_close(); ?> -->
        </div>
    </div>
</div>
</div>

<script>
    function addSuratJalan() {

        $.ajax({
            success: function(response) {
                // console.log(response.add_profile);
                $("#tambah_surat_jalan").modal() // Buka Modal
                    .change();
            }
        });


        $("select[name = id_po]").on("change", function() {
            var id_po_terpilih = $("option:selected", this).attr("id_po");

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('rtd/get_kodeprod_po') ?>',
                data: 'id_ref=' + id_po_terpilih,
                success: function(hasil_kodeprod) {
                    $("select[name = kodeprod]").html(hasil_kodeprod);
                }
            });
        });

        $("select[name = kodeprod]").on("change", function() {
            var id_po_terpilih = document.getElementById("id_po").value;
            var kodeprod_terpilih = $("option:selected", this).attr("kodeprod");

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('rtd/get_do') ?>',
                data: {
                    nopo: id_po_terpilih,
                    kodeprod: kodeprod_terpilih,
                },
                success: function(hasil_do) {
                    $("select[name = id_do]").html(hasil_do);
                }
            });
        });

        $("select[name = id_do]").on("change", function() {
            var kodeprod_terpilih = document.getElementById("kodeprod").value;

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('rtd/get_ed') ?>',
                data: {
                    kodeprod: kodeprod_terpilih,
                },
                success: function(hasil_ed) {
                    $("select[name = ed").html(hasil_ed);
                    $("select[name = ed_tambahan").html(hasil_ed);
                }
            });
        });

        $("select[name = ed]").on("change", function() {
            var ed_terpilih = $("option:selected", this).attr("ed");
            var ed_text = document.getElementById('ed_text').value = ed_terpilih
            console.log("ed terpilih : " + ed_terpilih)
            console.log("ed_text : " + ed_text)
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('rtd/get_batch_number') ?>',
                data: {
                    ed: ed_terpilih,
                },
                success: function(hasil_ed) {
                    $("select[name = batch_number]").html(hasil_ed);
                }
            });
        });

        $("select[name = ed_tambahan]").on("change", function() {

            console.log("ed_tambahan")
            // var ed_text = document.getElementById('ed_text').value = ed_tambahan

            console.log(ed_text)



            // var ed_terpilih = $("option:selected", this).attr("ed");
            // var ed_text = document.getElementById('ed_text').value = ed_terpilih
            // console.log("ed terpilih : " + ed_terpilih)
            // console.log("ed_text : " + ed_text)
            // $.ajax({
            //     type: 'POST',
            //     url: '<?php echo base_url('rtd/get_batch_number') ?>',
            //     data: {
            //         ed: ed_terpilih,
            //     },
            //     success: function(hasil_ed) {
            //         $("select[name = batch_number]").html(hasil_ed);
            //     }
            // });
        });

        $("select[name = batch_number]").on("change", function() {
            var batch_number_terpilih = $("option:selected", this).attr("batch_number");
            var ebatch_number_text = document.getElementById('batch_number_text').value = batch_number_terpilih
        });

        $(".add-more").click(function() {
            console.log('addmore')
            // var html = $(".copy").html();
            var html = $(".copy").html();
            // document.getElementById('copy').id = 'copy2';
            $(".after-add-more").after(html);

            var kodeprod_terpilih = document.getElementById("kodeprod").value;

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('rtd/get_ed') ?>',
                data: {
                    kodeprod: kodeprod_terpilih,
                },
                success: function(hasil_ed) {
                    $("select[name = ed_tambahan").html(hasil_ed);
                }
            });



            // var tambah;
            // tambah = `
            // <div class="form-group row ">
            //     <label class="col-sm-3">Pilih ED</label>
            //     <div class="col-sm-8">
            //         <!-- <input class="form-control" type="text" name="kodeprod" id="kodeprod" required /> -->
            //         <select name="ed[]" class="form-control" <?= $required; ?>>
            //         </select>
            //     </div>
            // </div>
            // `;
            // tambah = "<p>sufy</p>";

            // $("#divHobi").append(stre);

        });

        $("body").on("click", ".remove", function() {
            console.log('remove')
            $(this).parents(".control-group").remove();
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



    }
</script>