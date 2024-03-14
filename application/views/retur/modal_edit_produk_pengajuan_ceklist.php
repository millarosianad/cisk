<div class="modal fade" id="ceklist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Form Approval</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php echo form_open('retur/approval_produk_pengajuan_ceklist/'); ?>
            <div class="modal-body">
                <!-- <p id="loadingImage" style="font-size: 60px; display: none ">Loading ...</p> -->

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