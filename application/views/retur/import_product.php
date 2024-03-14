<!-- Modal -->
<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Csv</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a href="<?= base_url().'retur/download_template/'.$this->uri->segment('3').'/'.$this->uri->segment('4');?>" type="button" class="btn btn-success btn-sm">Download Template</a>
                <br><br>
                <?php echo form_open_multipart('retur/upload_pengajuan_retur/'.$this->uri->segment('3').'/'.$this->uri->segment('4'));?>
                <div class="row">
                    <div class="col-8">
                        <input type="file" name="file" class="form-control">
                    </div>
                    <div class="col-4">
                        <?php echo form_submit('submit','Proses','class="btn btn-primary btn-sm"');?>
                    </div>
                </div>
                <?php echo form_close();?>
                <br>
                <p>
                   Klik <a href="<?= base_url().'assets/file/panduan_import.pdf';?>" target="_blank"><u>Panduan</u></a> untuk langkah-langkah import csv
                </p>
            </div>
        </div>
    </div>
</div>