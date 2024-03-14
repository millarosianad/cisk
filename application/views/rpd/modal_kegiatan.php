<div class="modal fade" id="pricelist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">RPD | Rencana Kegiatan</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open_multipart('rpd/proses_kegiatan/'); ?>

            <input class="form-control" type="text" name="id" id="id" hidden />

            <div class="modal-body">

                <div class="form-group row">
                    <label class="col-sm-4">Rencana Aktivitas</label>
                    <div class="col-sm-6">
                        <textarea name="aktivitas" class="form-control" cols="30" rows="3"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Aktivitas</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="datetime-local" name="tanggal_aktivitas" id="tanggal_aktivitas" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Detail Aktivitas</label>
                    <div class="col-sm-6">
                        <textarea name="detail_aktivitas" class="form-control" cols="30" rows="3"></textarea>
                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <label class="col-sm-4">Jenis Biaya Yang Dibutuhkan (opsional)</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="jenis_biaya" id="jenis_biaya" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Nominal Biaya Yang Dibutuhkan (opsional)</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="jenis_biaya" id="jenis_biaya" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Keterangan</label>
                    <div class="col-sm-6">
                        <textarea name="keterangan" class="form-control" cols="30" rows="5"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Status Approval</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="status_approval" id="status_approval" placeholder="status_approval" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Nama Status Approval</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="nama_status_approval" id="nama_status_approval" placeholder="nama_status_approval" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Approved At</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="approved_at" id="approved_at" placeholder="approved_at" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Approved By</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="approved_by" id="approved_by" placeholder="approved_by" />
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
    function addKegiatan() {
        $('.kategori').show();
        $('.kategori_input').hide();
        $.ajax({
            type: "POST",
            url: "<?= base_url('rpd/get_pelaksana') ?>",
            data: {
                id: null
            },
            dataType: "json",
            success: function(response) {
                console.log(response)
                console.log(response.get_pelaksana.username);
                $("#pricelist").modal() // Buka Modal
                $('#id').val('') // parameter
                $('#pelaksana').val(response.get_pelaksana.username) // parameter
                    // $('#kategori').val('')
                    // $('#tanggal_transaksi').val('')
                    // $('#km_akhir').val('')
                    // $('#liter').val('')
                    // $('#biaya').val('')
                    // $('#foto_km').attr("required", true)
                    // $('#foto_struk').attr("required", true)
                    // $("#img_km").attr('src', '')
                    // $("#img_struk").attr('src', '')
                    .change();
            }
        });
    }

    function editBiaya(params) {
        $('.kategori').hide();
        $('.kategori_input').show();
        $('#foto_km').removeAttr('required');
        $('#foto_struk').removeAttr('required');
        $.ajax({
            type: "POST",
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
                $("#img_km").attr('src', '<?= base_url() . '/assets/file/biaya_operasional/'; ?>'
                    .concat(response.get_history.foto_km))
                $("#img_struk").attr('src', '<?= base_url() . '/assets/file/biaya_operasional/'; ?>'
                        .concat(response.get_history.foto_struk))
                    .change();
            }
        });
    }
</script>