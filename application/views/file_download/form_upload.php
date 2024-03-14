<?php echo form_open_multipart('/file_download/upload_file'); ?>

<!-- Modal -->
<div class="modal fade" id="share" tabindex="-1" role="dialog" aria-labelledby="shareLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareLabel">Share</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Versi</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="versi" id="versi" readonly required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Sub Branch</label>
                    <div class="col-sm-10">
                        <select class="form-control" value="0" name="site_code" id="site_code">
                            <option value="1">ALL</option>
                            <option value="2">Area 1</option>
                            <option value="3">Area 2</option>
                            <option value="4">Area 3</option>
                            <option value="5">Custom</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="customize">
                    <label class="col-sm-2 col-form-label">Custom</label>
                    <div class="col-sm-10">
                        <?php
                        foreach ($s_code as $value) {
                            $branch[$value->kode] = "$value->nama_comp | $value->kode";
                        }
                        echo form_dropdown('branch', $branch, '', 'id="branch" class="form-control"');
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Link Download (GDRIVE) </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="link_gdrive">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Pilih File</label>
                    <div class="col-sm-10">
                        <div class="col-sm-12">
                            <input type="file" name="userfile" class="filestyle" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success btn-round center-block" required'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#customize').hide()
        $("select#site_code").change(function() {
            var selectedLocation = $(this).children("option:selected").val();
            if (selectedLocation == '5') {
                $('#customize').show()
            } else {
                $('#customize').hide()
            }
        });
    });
</script>