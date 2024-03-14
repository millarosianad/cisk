<!-- Modal -->
<div class="modal fade" id="setting_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mapping Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            <?php echo form_open_multipart('mt/download_mapping_product/');?>
                <div class="row">
                    <div class="col-md-9">                           
                        <?php echo form_submit('submit','Download Template','class="btn btn-dark btn-md"');?>
                    </div>
                </div>
            <?php echo form_close();?>                    
                <hr>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <?php echo form_open_multipart('mt/proses_mapping_product/');?>
                        <input type="file" name="file" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <?php echo form_submit('submit','Proses Mapping','class="btn btn-success btn-md"');?>
                        <?php echo form_close();?>
                    </div>
                </div>

            </div>


        </div>
    </div>
</div>