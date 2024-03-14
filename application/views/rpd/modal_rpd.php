<div class="modal fade" id="rpd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Rencana Perjalanan Dinas</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open('rpd/proses_rpd/'); ?>

            <input class="form-control" type="text" name="id" id="id" hidden />

            <div class="modal-body">

                <div class="form-group row">
                    <label class="col-sm-4">Pelaksana</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="pelaksana" id="pelaksana" placeholder="pelaksana"
                            required readonly />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Maksud Perjalanan Dinas</label>
                    <div class="col-sm-6">
                        <textarea name="maksud_perjalanan_dinas" class="form-control" cols="30" rows="3"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Berangkat</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="datetime-local" name="tanggal_berangkat"
                            id="tanggal_berangkat" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tanggal Pulang</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="datetime-local" name="tanggal_tiba" id="tanggal_tiba"
                            required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tempat Berangkat</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="tempat_berangkat" id="tempat_berangkat"
                            placeholder="tempat keberangkatan" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Tempat Tujuan</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="tempat_tujuan" id="tempat_tujuan"
                            placeholder="tempat tujuan" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Keterangan</label>
                    <div class="col-sm-6">
                        <textarea name="keterangan" class="form-control" cols="30" rows="5"></textarea>
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
    function addRpd() {
        $('.kategori').show();
        $('.kategori_input').hide();
        $.ajax({
            type: "POST",
            url: "<?= base_url('rpd/get_pelaksana') ?>",
            data: {
                id: null
            },
            dataType: "json",
            success: function (response) {
                console.log(response)
                console.log(response.get_pelaksana.username);
                $("#rpd").modal() // Buka Modal
                $('#id').val('') // parameter
                $('#pelaksana').val(response.get_pelaksana.username) // parameter
                    .change();
            }
        });
    }
</script>