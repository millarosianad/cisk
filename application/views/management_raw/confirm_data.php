<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart($url); ?>
                <div class="row">
                    <div class="col-md-6">
                        <h4>Omzet :</h4>
                    </div>
                    <div class="col-md-6">
                        <h4>Rp <?= number_format($omzet_raw,2,',','.'); ?></h4>
                    </div>
                    <br>
                    <br>
                    <div class="col-md-6"><strong>Apakah ini data closing di bulan ini ?</strong></div>
                    <div class="col-md-6">
                        <select name="status_closing" class="form-control" required>
                            <option value="null">Pilih</option>
                            <option value="0">Bukan Closing Bulan Ini</option>
                            <option value="1" name=>Ya, Closing Bulan Ini</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-12 loading" align="center">
                    <img src="<?= base_url() . 'assets/gif/loading.gif' ?>" alt="">
                </div>

                <hr>
                <div class="row" align="center">
                    <div class="col-md-12">
                        <input type="hidden" name="signature" value="<?= $signature ?>">
                        <?php echo form_submit('submit', 'Submit', 'class="btn btn-primary submit"'); ?>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>