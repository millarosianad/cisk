<!-- Modal -->
<div class="modal fade" id="raw_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Raw Data Pengajuan Retur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <a href="<?= base_url().'retur/download_template/'.$this->uri->segment('3').'/'.$this->uri->segment('4');?>" type="button" class="btn btn-success btn-sm">Download
                    Template</a>
                <br><br> -->
                <?php echo form_open('retur/raw_data');?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-inline row">
                            <div class="col-sm-12">
                                <input class="form-control" type="date" name="from" required />
                                <input class="form-control" type="date" name="to" required />
                                <?php echo form_submit('submit','Export','class="btn btn-success btn-sm"');?>            
                            </div>
                        </div>
                    </div>
                </div>


                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>