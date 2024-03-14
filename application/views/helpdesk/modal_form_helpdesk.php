<?php
foreach ($site_code as $a) {
    $site['0'] = 'Pilih';
    $site[$a->site_code] = $a->nama_comp;
}
?>

<div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('/helpdesk/add_helpdesk'); ?>
                <div class="form-group row">
                    <label class="col-sm-3">Sub Branch</label>
                    <div class="col-sm-9">
                        <?php
                        echo form_dropdown('site_code', $site, '', 'class="form-control"  id="site_code" required');
                        ?>
                    </div>
                </div>
                <div class="form-group row" hidden>
                    <label class="col-sm-3">Sub Branch</label>
                    <div class="col-sm-9">
                        <?php $subbranch = array();
                        echo form_dropdown('subbranch', $subbranch, 'ALL', 'class="form-control" id="subbranch" required');
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3">Nama Company</label>
                    <div class="col-sm-9">
                        <?php $company = array();
                        echo form_dropdown('company', $company, 'ALL', 'class="form-control" id="company" required');
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3">Nama</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3">No. Telp</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="telp" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3">Email</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" name="email" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3">Masalah</label>
                    <div class="col-sm-9">
                        <select name="masalah" class="form-control" id="masalah" required>
                            <option value="">Pilih</option>
                            <option value="Barang Kurang">Barang Kurang</option>
                            <option value="Barang Lebih">Barang Lebih</option>
                            <option value="Barang Rusak">Barang Rusak</option>
                            <option value="lain">Lain-nya</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="customize">
                    <label class="col-sm-3"></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="custom" placeholder="Masalah" id="custom">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3">Deskripsi</label>
                    <div class="col-sm-9">
                        <textarea id='deskripsi' name="deskripsi" rows="6" cols="90%" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Pilih Gambar</label>
                    <div class="col-sm-9">
                        <input type="file" name="userfile" class="form-control" required>
                        <p style="color: rgb(0 0 0 / 30%);">Format (.jpg)</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success btn-round center-block" required'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#site_code").click(function() {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>helpdesk/get_company_sitecode",
                data: {
                    site_code: $(this).val(),
                },
                success: function(data) {
                    console.log(data)
                    $('#company').html(data).change();
                }
            });
        });

        $("#site_code").click(function() {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>helpdesk/get_subbranch_sitecode",
                data: {
                    site_code: $(this).val(),
                },
                success: function(data) {
                    console.log(data)
                    $('#subbranch').html(data).change();
                }
            });
        });

        $('#customize').hide()
        $("select#masalah").change(function() {
            var selectedLocation = $(this).children("option:selected").val();
            if (selectedLocation == 'lain') {
                $('#customize').show()
                $("input#custom").attr("required", "true")
            } else {
                $('#customize').hide()
                $("input#custom").removeAttr("required")
            }
        });
    });
</script>