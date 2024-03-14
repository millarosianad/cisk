<!-- Modal -->
<div class="modal fade" id="import" tabindex="-0" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Monitoring Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            <?php echo form_open_multipart('inventory/export_template_ms/');?>
                <!-- <div class="container"> -->
                    <div class="row mb-2">
                        <div class="col">
                            Pertama => silahkan export template terlebih dahulu
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <select class="" value="0" name="supp" required>
                                <option value="" selected="selected"> -- Pilih Principal -- </option>
                                <option value="001">Deltomed</option>
                                <option value="005">Ultra Sakti</option>
                            </select>
                            <?php echo form_submit('submit','Export Template','class="btn btn-default btn-md"');?>
                        </div>
                    </div>
            <?php echo form_close();?>
                    <hr>
                    <div class="row mb-2">
                        <div class="col">
                            Kedua => buka file tsb. Isi kolom "estimasi sales" dan "stock ideal". Lalu klik save. (Jangan save as karena bisa merubah format file)
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-2">
                        <div class="col">
                            Ketiga => Attach file tsb dan klik button import
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <?php echo form_open_multipart('inventory/import_ms/');?>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <?php echo form_submit('submit','Import','class="btn btn-warning btn-md"');?>
                            <?php echo form_close();?>
                        </div>
                    </div>

            </div>


        </div>
    </div>
</div>