<?php
$required = "";
?>

<!-- modal asset -->
<div class="modal fade" id="tambah_struktur" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Tambah Struktur</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open($url_struktur); ?>
            <div class="modal-body">

                <div class="form-group row">
                    <label class="col-sm-4">Sub Branch</label>
                    <div class="col-sm-6">
                        <select name="site_code" class="form-control" <?= $required; ?>>

                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Karyawan</label>
                    <div class="col-sm-6">
                        <select name="karyawan" class="form-control" <?= $required; ?>>

                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Atasan Langsung</label>
                    <div class="col-sm-6">
                        <select name="atasan_langsung" class="form-control" <?= $required; ?>>

                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success" required'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("select[name = site_code]").on("change", function() {
            var site_code_terpilih = $("option:selected", this).attr("site_code");
            console.log(site_code_terpilih)
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('database_afiliasi/karyawan') ?>',
                data: 'site_code=' + site_code_terpilih,
                success: function(hasil_karyawan) {
                    $("select[name = karyawan]").html(hasil_karyawan);
                    $("select[name = atasan_langsung]").html(hasil_karyawan);
                }
            });
        });
    });
</script>