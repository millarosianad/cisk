<!-- Modal -->
<div class="modal fade" id="bonusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Skema Bonus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a href="<?= base_url().'sales_omzet/bonus_csv' ?>" type="button" class="btn btn-dark btn-sm">Download
                    Template</a>
                <br><br>
                <?php echo form_open_multipart("sales_omzet/proses_mapping_product/"); ?>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Pilih File</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="file" name="file">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php 
                    echo form_submit('submit','Simpan', 'class="btn btn-success"');
                    echo form_close();
                ?>
            </div>
        </div>
    </div>
</div>