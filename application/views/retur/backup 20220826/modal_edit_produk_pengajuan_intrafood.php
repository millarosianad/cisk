<div class="modal fade" id="edit_apps" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Form Approval Intrafood</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php echo form_open('retur/approval_produk_pengajuan/'); ?>
            <div class="modal-body">
                <!-- <p id="loadingImage" style="font-size: 60px; display: none ">Loading ...</p> -->

            
                <div class="form-group row">
                    <label class="col-sm-4">Kodeprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="kodeprod" minlength='6' maxlength='6' id="kodeprodx" required readonly />
                        <input class="form-control" type="hidden" name="id_produk_pengajuan" minlength='6' maxlength='6' id="id_produk_pengajuan" required readonly />
                        <input class="form-control" type="hidden" name="signature" minlength='6' maxlength='6' id="signaturex" required readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Namaprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="namaprod" id="namaprodx" required readonly />
                    </div>
                </div>
                <!-- <div class="form-group row">
                    <label class="col-sm-4">Batch Number</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="batch_number" id="batch_numberx" readonly />
                    </div>
                </div> -->
                <!-- <div class="form-group row">
                    <label class="col-sm-4">ED</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="ed" id="edx" readonly />
                    </div>
                </div> -->
                <div class="form-group row">
                    <label class="col-sm-4">Total Karton</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="ed" id="total_kartonx" readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Total Dus</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="ed" id="total_dusx" readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Total Pcs</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="ed" id="total_pcsx" readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Keterangan</label>
                    <div class="col-sm-6">
                        <textarea name="" class="form-control" cols="30" rows="5" id="keteranganx" readonly></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Status Approval</label>
                    <div class="col-sm-6">
                        <select name="status_approval" class="form-control" id="" required>
                            <option value="">-- Pilih --</option>
                            <option value="3">Data sesuai (verified)</option>
                            <option value="4">Data tidak sesuai (not verified)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Deskripsi</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="deskripsi" id="deskripsi" required />
                    </div>
                    <input class="form-control" type="hidden" name="supp" value="<?php echo $this->uri->segment('4'); ?>" required />
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
    function get_produk_retur_by_id(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('retur/get_produk_retur_by_id') ?>",
            data: {
                id: params
            },
            dataType: "json",
            beforeSend: function() {
                // setting a timeout
                $('#loadingImage').show();

            },
            success: function(response) {
                console.log(response.edit);
                console.log(params);
                
                // $('#loadingImage').hide();
                $("#edit_apps").modal() // Buka Modal
                $('#id_produk_pengajuan').val(params) // parameter
                $('#kodeprodx').val(response.edit.kodeprod)
                $('#edx').val(response.edit.expired_date)
                $('#batch_numberx').val(response.edit.batch_number)
                $('#namaprodx').val(response.edit.namaprod)                
                $('#keteranganx').val(response.edit.keterangan)                
                $('#signaturex').val(response.edit.signature)                
                $('#jumlahsatuanx').val(response.edit.jumlahsatuan)                
                $('#jumlahx').val(response.edit.jumlah)   
                $('#total_kartonx').val(response.edit.total_karton)   
                $('#total_dusx').val(response.edit.total_dus)   
                $('#total_pcsx').val(response.edit.total_pcs)   

                .change();
            }
        });

    }
</script>