<?php

// $required = "";
$required = "required";

?>
<!-- modal tambah profile -->
<div class="modal fade" id="barang_keluar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Barang Keluar</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open($url_keluar); ?>
            <div class="modal-body">

                <div class="form-group row">
                    <label class="col-sm-3">Nomor LBM (Barang Masuk)</label>
                    <div class="col-sm-8">
                        <!-- <input class="form-control" type="text" name="kodeprod" id="kodeprod" required /> -->
                        <select name="kode_masuk" class="form-control" required>
                        </select>
                        <input class="form-control" type="text" name="id_profile" id="id" hidden>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <?php echo form_submit('submit', 'Tampilkan Data', 'class="btn btn-success" required'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function barang_keluar() {
        // $.ajax({
        //     success: function(response) {
        //         console.log(response.add_profile);
        $("#barang_keluar").modal() // Buka Modal
        //         .change();
        //     }
        // });

    }
</script>