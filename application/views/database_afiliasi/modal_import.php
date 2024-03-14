<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a href='<?= base_url()."assets/template_import/template.xlsx";?>' class="btn btn-success btn-mat btn-sm">Download
                    Template</a>
                    <br><br>
                <?php echo form_open_multipart("database_afiliasi/import_proses/"); ?>
                <div class="form-group row">
                    <label class="col-sm-4">Pilih File</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="file" name="file" id="file">
                    </div>
                </div>
                <div align="center">
                    <?php echo form_submit('submit', 'Simpan', 'class="btn btn-primary" required'); ?>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>