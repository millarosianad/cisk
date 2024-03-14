<!-- Modal Noted-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Input Noted</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open('dashboard_dummy/input_noted/'); ?>
                <textarea class="form-control" placeholder="Masukan Catatan" rows="7" cols="50" name="noted"></textarea>
            </div>
            <div class="modal-footer">
                <?= form_submit('submit', 'Simpan', 'class="btn btn-success btn-round btn-sm"'); ?>
                <a href="<?= base_url().'dashboard_dummy/delete_noted';?>" type="button" class="btn waves-effect waves-light btn-inverse btn-outline-inverse btn-round btn-sm">Reset</a>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>