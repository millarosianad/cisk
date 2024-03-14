<?php echo form_open('/file_download/add_versi'); ?>
<div class="modal fade" id="tambahversi" tabindex="-1" role="dialog" aria-labelledby="tambahversiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahversiLabel">Tambah Versi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Versi</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="versi" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Tanggal</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" name="tanggal" idrequired>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <input type="radio" name="status" id="exampleRadios1" value="1">
                        <label class="form-check-label" for="exampleRadios1">Aktif</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success btn-round center-block" required'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>