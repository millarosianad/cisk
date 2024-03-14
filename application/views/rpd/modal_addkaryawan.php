<!-- Modal -->
<div class="modal fade" id="karyawan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <?= form_open('rpd/master_karyawan_simpan'); ?>
                <input class="form-control" type="text" name="id" id="id" hidden />

                <div class="modal-body">

                    <div class="form-group row">
                        <label class="col-sm-4">Nama Karyawan</label>
                        <div class="col-sm-8 add">
                            <?php 
                                foreach($list_user as $a) 
                                {
                                    $user ['0']= '-- Pilih --' ;
                                    $user[$a->id]= $a->username;
                                }
                                echo form_dropdown('userid', $user,'','class="form-control"');
                            ?>
                        </div>

                        <div class="col-sm-8 edit">
                            <input type="text" id="userid" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Nama Atasan</label>
                        <div class="col-sm-8">
                            <?php 
                                foreach($list_user as $a) 
                                {
                                    $user ['']= '-- Pilih --' ;
                                    $user[$a->id]= $a->username;
                                }
                                echo form_dropdown('atasan_id', $user,'','class="form-control" id="atasan_id"');
                            ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Status</label>
                        <div class="col-sm-8">
                            <select name="status" id="status" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="0">Tidak AKtif</option>
                                <option value="1">Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <?= form_submit('submit', 'Simpan', 'class="btn btn-success" required'); ?>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addKaryawan() 
    {
        $('.add').show();
        $('.edit').hide();
        $.ajax({
            success: function(response) {
                console.log(response.get_master_karyawan);
                $("#karyawan").modal() // Buka Modal
                $('#id').val('') // parameter
                $('#atasan_id').val('')
                $('#status').val('')
                .change();
            }
        });
    }
    
    function editKaryawan(params) 
    {
        $('.edit').show();
        $('.add').hide();
        $.ajax({
            type: "POST",
            url: "<?= base_url('rpd/get_data') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function(response) {
                console.log(response.get_master_karyawan);
                $("#karyawan").modal() // Buka Modal
                $('#id').val(params) // parameter
                $('#userid').val(response.get_master_karyawan.nama_karyawan)
                $('#atasan_id').val(response.get_master_karyawan.atasan_id)
                $('#status').val(response.get_master_karyawan.status)
                .change();
            }
        });
    }
</script>