<div class="modal fade" id="edit_realisasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">RPD | Edit Realisasi</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url().'rpd/proses_realisasi';?>" method="post" enctype="multipart/form-data">
                <div class="modal-body" id="form_realisasi">
                    <input class="form-control" type="text" name="id_realisasi" id="id_realisasi" />
                    <input class="form-control" type="text" name="id_rpd_realisasi" id="id_rpd_realisasi" />
                    <input type="text" name="signature_realisasi" id="signature_realisasi" >
                    <input class="form-control" type="text" name="id_aktivitas_realisasi" id="id_aktivitas_realisasi" />
                    <div class="form-group row">
                        <label class="col-sm-4">Aktivitas</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text"
                                name="rencana" id="rencana_realisasi_input" placeholder="Isi Rencana" readonly/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Tanggal Realisasi</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="datetime-local" name="tanggal_realisasi[]" id="tanggal_realisasi" required/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Detail Realisasi</label>
                        <div class="col-sm-6">
                            <textarea name="detail_realisasi[]" id="detail_realisasi" class="form-control"
                                cols="30" rows="3"
                                placeholder="Isi Detail Aktivitas" required></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Jenis Pengeluaran Realisasi</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text"
                                name="jenis_pengeluaran_realisasi[]" id="jenis_pengeluaran_realisasi" placeholder="Isi Pengeluaran" required/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Nominal Biaya Realisasi</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number"
                                name="nominal_biaya_realisasi[]" id="nominal_biaya_realisasi" placeholder="Contoh: 100000" required/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Keterangan Realisasi</label>
                        <div class="col-sm-6">
                            <textarea name="keterangan_realisasi[]" id="keterangan_realisasi" class="form-control"
                                cols="30" rows="5"
                                placeholder="Isi Detail Keterang" required></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4"></label>
                        <div class="col-sm-6">
                            <img alt="img_foto_struk" id="img_foto_struk" style='max-width: 50%;' class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Foto/Capture Bill / Struk (.jpg)</label>
                        <div class="col-sm-6">
                            <input type="file" name="foto_struk[]" id="foto_struk" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editRealisasi(param) { 
        $("#edit_realisasi").modal() // Buka Modal
        $.ajax({
            type: "POST",
            url: "<?= base_url('rpd/get_data') ?>",
            data: {
                id: param
            },
            dataType: "json",
            success: function (response) {
                // console.log(response.get_realisasi);
                $('input#id_realisasi').val(param).hide()
                $('input#id_rpd_realisasi').val(response.get_realisasi.id_rpd).hide()
                $('input#id_aktivitas_realisasi').val(response.get_realisasi.aktivitas_id).hide()
                $('input#signature_realisasi').val(response.get_realisasi.signature).hide()
                $('input#rencana_realisasi_input').val(response.get_realisasi.rencana)
                $('input#tanggal_realisasi').val(response.get_realisasi.tanggal)
                $('textarea#detail_realisasi').val(response.get_realisasi.detail)
                $('input#jenis_pengeluaran_realisasi').val(response.get_realisasi.jenis_pengeluaran)
                $('input#nominal_biaya_realisasi').val(response.get_realisasi.nominal_biaya)
                $('textarea#keterangan_realisasi').val(response.get_realisasi.keterangan)
                $("img#img_foto_struk").attr("src", "<?= base_url().'assets/file/rpd/';?>".concat(response.get_realisasi.file_struk))
                    .change();
            }
        });
    }
</script>