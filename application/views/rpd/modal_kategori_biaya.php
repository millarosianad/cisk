<!-- Modal -->
<div class="modal fade" id="add_kategori" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <?php echo form_open('rpd/add_kategori'); ?>
                <input class="form-control" type="text" name="id" id="id" hidden />

                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-4">Nama Kategori</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="kategori"
                                placeholder="Nama Kategori">
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
</div>