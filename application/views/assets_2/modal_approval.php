<div class="modal fade" id="approv_mutasi" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Approval Mutasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open("assets_2/approv_mutasi_assets/"); ?>
            <div class="modal-body">
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Alasan
                            Approval</label>
                        <div class="col-sm-9">
                            <input type="text" name="id_approv" id="id_approv" hidden>
                            <textarea class="form-control" type="text" name="approv"></textarea>
                            <br>
                            <?PHP
                                echo form_submit('submit','Simpan', 'class="btn btn-success btn-sm"');
                                echo form_close();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

function approvMutasi(param) {
    $.ajax({
        success: function (response) {
            $("#approv_mutasi").modal() // Buka Modal
            $('#id_approv').val(param) // parameter
        }
    });
}
</script>