<?php

// $required = "";
$required = "required";

?>
<!-- modal tambah profile -->
<div class="modal fade" id="tambah_profile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Barang Masuk</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open($url); ?>
            <div class="modal-body">

                <div class="form-group row">
                    <label class="col-sm-3">Tanggal DO</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="date" name="tgldo" id="tgldo" required />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3">Nomor Surat Jalan</label>
                    <div class="col-sm-8">
                        <!-- <input class="form-control" type="text" name="kodeprod" id="kodeprod" required /> -->
                        <select name="nodo" class="form-control" required>
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
    function barang_masuk() {
        console.log('a');
        $.ajax({
            success: function(response) {
                console.log('b');
                $("#tambah_profile").modal() // Buka Modal
            }
        });

        $("input[name = tgldo]").on("change", function() {
            var tgldo_terpilih = document.getElementById('tgldo').value;
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('dc/get_nodo') ?>',
                data: 'tgldo=' + tgldo_terpilih,
                success: function(hasil_nodo) {
                    $("select[name = nodo").html(hasil_nodo);
                }
            });
        });

    }
</script>