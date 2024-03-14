<!-- Modal -->
<div class="modal fade" id="setting_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirm Picklist</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">  
                <div class="row mb-2">
                    <div class="col">
                        silahkan upload file confirm picklist yang sudah di cetak dan ttd di bawah ini 
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <?php echo form_open_multipart('dc/proses_confirm_picklist/');?>
                        <input type="file" name="file" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <?php echo form_submit('submit','Confirm','class="btn btn-success btn-md"');?>
                        <?php echo form_close();?>
                    </div>
                </div>

            </div>


        </div>
    </div>
</div>