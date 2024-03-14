<div class="modal fade" id="pricelist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Isi data pengajuan</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart($url); ?>

            <div class="modal-body">
                <div>
                    <p>Harap diperhatikan ! Silahkan masukkan dari tanggal transaksi yang terlama terlebih dulu</p>
                </div>
                <hr>
                <div class="form-biayapengajuan">
                    <div class="form-group row">
                        <label class="col-sm-4">Kategori</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="id" id="id" hidden />
                            <input type="text" name="kategori" id="kategori_input" class="form-control kategori_input" readonly>
                            <select class="form-control kategori" name="kategori" id="kategori" required>
                                <option value=""> -- Pilih Salah Satu -- </option>
                                <option value="1">Klaim Bensin</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Tanggal Transaksi</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="date" name="tanggal_transaksi[]" id="tanggal_transaksi" required />
                            <p style="color: red;">*Harap diisi sesuai struk !</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Km Saat Pengisian</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number" name="km_akhir[]" id="km_akhir" placeholder="150000" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Pengisian (Liter)</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="decimal" name="liter[]" id="liter" placeholder="24.95" required />
                            <p style="color: red;">*Harap diisi sesuai struk !</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Biaya (Rp)</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number" name="biaya[]" id="biaya" placeholder="100000" required />
                            <p style="color: red;">*Harap diisi sesuai struk !</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Foto/Capture Kilometer (.jpg)</label>
                        <div class="col-sm-6">
                            <input type="file" name="foto_km[]" id="foto_km" class="form-control" required>
                            <a href ="#" id="link_km" target="_blank">
                                <img alt="" id="img_km" style='max-width: 50%;' class="form-control">
                            </a>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Foto/Capture Bill / Struk (.jpg)</label>
                        <div class="col-sm-6">
                            <input type="file" name="foto_struk[]" id="foto_struk" class="form-control" required>
                            <a href ="#" id="link_struk" target="_blank">
                                <img alt="" id="img_struk" style='max-width: 50%;' class="form-control">
                            </a>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4"></label>
                    <div class="col-sm-6">
                        <a href="#" class="btn btn-warning btn-sm" id="btn-tambah">+ Tambah</a>
                    </div>
                </div>
                <!-- <div class="form-group row">
                    <label class="col-sm-4"></label>
                    <div class="col-sm-6">
                        <a href="#" class="btn btn-danger btn-sm" id="btn-delete">Delete</a>
                    </div>
                </div> -->
            </div>

            <div class="modal-footer">
                <?= form_submit('submit', 'Simpan', 'class="btn btn-success" required'); ?>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let index = 1;
        $("#btn-tambah").click(function() {
            var i = index;
            // var kategori =
            //     '<div class="form-group row"><label class="col-sm-4">Kategori</label><div class="col-sm-6"><select class="form-control kategori" name="kategori[]" id="kategori" required><option value=""> -- Pilih Salah Satu -- </option><option value="1">Klaim Bensin</option></select></div></div>';

            var tanggal_transaksi =
                '<div class="form-group row"><label class="col-sm-4">Tanggal Transaksi</label><div class="col-sm-6"><input class="form-control" type="date" name="tanggal_transaksi[]" id="tanggal_transaksi"required /><p style="color: red;">*Harap diisi sesuai struk !</p></div></div>';

            var km_pengisian =
                '<div class="form-group row"><label class="col-sm-4">Km Saat Pengisian</label><div class="col-sm-6"><input class="form-control" type="number" name="km_akhir[]" id="km_akhir" placeholder="150000"required /></div></div>';

            var liter =
                '<div class="form-group row"><label class="col-sm-4">Pengisian (Liter)</label><div class="col-sm-6"><input class="form-control" type="decimal" name="liter[]" id="liter" placeholder="24.95"required /><p style="color: red;">*Harap diisi sesuai struk !</p></div></div>';

            var biaya =
                '<div class="form-group row"><label class="col-sm-4">Biaya (Rp)</label><div class="col-sm-6"><input class="form-control" type="number" name="biaya[]" id="biaya" placeholder="100000"required /><p style="color: red;">*Harap diisi sesuai struk !</p></div></div>';

            var foto_km =
                '<div class="form-group row"><label class="col-sm-4">Foto/Capture Kilometer (.jpg)</label><div class="col-sm-6"><input type="file" name="foto_km[]" id="foto_km" class="form-control" required></div></div>';

            var foto_struk =
                '<div class="form-group row"><label class="col-sm-4">Foto/Capture Bill / Struk (.jpg)</label><div class="col-sm-6"><input type="file" name="foto_struk[]" id="foto_struk" class="form-control" required></div></div>';

            var btn_delete =
                '<div class="form-group row"><label class="col-sm-4"></label><div class="col-sm-6"><a href="#" class="btn btn-danger btn-sm" id="btn-delete'.concat(+i, '">Delete</a></div></div>');


            $("div.form-biayapengajuan").append('<div class="append append'.concat(+i, '">', '<hr>',
                tanggal_transaksi, km_pengisian, liter, biaya, foto_km, foto_struk, btn_delete));

            $("#btn-delete" + i).click(function() {
                $(".append" + i).remove();
            });
            
            index++;
        });
    });

    function addBiaya() {
        $("#btn-tambah").show()
        $("#btn-delete").show()
        $('.kategori').show();
        $('.kategori_input').hide();
        $("#pricelist").modal() // Buka Modal
        $(".append").remove();
        $('#id').val('') // parameter
        $('#kategori').val('')
        $('#tanggal_transaksi').val('')
        $('#km_akhir').val('')
        $('#liter').val('')
        $('#biaya').val('')
        $('#foto_km').attr("required", true)
        $('#foto_struk').attr("required", true)
        $("#img_km").attr('src', '').hide()
        $("#img_struk").attr('src', '').hide()
    }

    function editBiaya(params) {
        $("#btn-tambah").hide()
        $("#btn-delete").hide()
        $(".append").remove();
        $('.kategori').hide();
        $('.kategori_input').show();
        $('#foto_km').removeAttr('required');
        $('#foto_struk').removeAttr('required');
        $.ajax({
            type: "GET",
            url: "<?= base_url('biaya_operasional/get_data') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function(response) {
                // console.log(response.get_history);
                $("#pricelist").modal() // Buka Modal
                $('#id').val(params) // parameter
                $('#kategori').val(response.get_history.kategori)
                $('#kategori_input').val(response.get_history.kategori_input)
                $('#tanggal_transaksi').val(response.get_history.tanggal_transaksi)
                $('#km_akhir').val(response.get_history.km_akhir)
                $('#liter').val(response.get_history.liter)
                $('#biaya').val(response.get_history.biaya)
                $("a#link_km").attr("href", '<?= base_url() . '/assets/file/biaya_operasional/'; ?>'
                    .concat(response.get_history.foto_km));
                $("#img_km").attr('src', '<?= base_url() . '/assets/file/biaya_operasional/'; ?>'
                    .concat(response.get_history.foto_km)).show()
                $("a#link_struk").attr("href", '<?= base_url() . '/assets/file/biaya_operasional/'; ?>'
                        .concat(response.get_history.foto_struk));
                $("#img_struk").attr('src', '<?= base_url() . '/assets/file/biaya_operasional/'; ?>'
                        .concat(response.get_history.foto_struk)).show()
                    .change();
            }
        });
    }
</script>