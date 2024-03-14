<div class="modal fade" id="cekapi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">API Testing</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open('master_product/cekapi/'); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4">Versi</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="site_code" required />
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
