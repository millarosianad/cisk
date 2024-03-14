<!-- Modal -->
<div class="modal fade" id="modal_biaya" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Biaya</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <?php echo form_open('rpd/master_biaya_simpan'); ?>
                <input class="form-control" type="text" name="id" id="id" hidden />

                <div class="modal-body">

                    <div class="form-group row">
                        <label class="col-sm-4">Nama Karyawan</label>
                        <div class="col-sm-8 add">
                            <?php 
                                foreach($list_user as $a) 
                                {
                                    $user ['0']= '-- Pilih --' ;
                                    $user[$a->id]= ucwords($a->username). " - " .ucwords($a->email);
                                }
                                echo form_dropdown('userid', $user,'','class="form-control"');
                            ?>
                        </div>

                        <div class="col-sm-8 edit">
                            <input type="text" id="userid" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Kategori</label>
                        <div class="col-sm-8 add">
                            <?php 
                                foreach($list_kategori_biaya as $a) 
                                {
                                    $kategori ['']= '-- Pilih --' ;
                                    $kategori[$a->id]= ucwords($a->nama_kategori);
                                }
                                echo form_dropdown('kategori_id', $kategori,'','class="form-control"');
                            ?>
                        </div>
                        <div class="col-sm-8 edit">
                            <input type="text" id="kategori_id" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Biaya</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="biaya" id="biaya" placeholder="(contoh : 100000)">
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
</div>

<script>
    function addBiaya() 
    {
        $('.add').show();
        $('.edit').hide();
        $.ajax({
            success: function(response) {
                console.log(response.get_master_biaya);
                $("#modal_biaya").modal() // Buka Modal
                $('#id').val('') // parameter
                .change();
            }
        });
    }
    
    function editBiaya(params)
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
                console.log(response.get_master_biaya);
                $("#modal_biaya").modal() // Buka Modal
                $('#id').val(params) // parameter
                $('#userid').val(response.get_master_biaya.username)
                $('#kategori_id').val(response.get_master_biaya.nama_kategori)
                $('#biaya').val(response.get_master_biaya.biaya)
                .change();
            }
        });
    }
</script>