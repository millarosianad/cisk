<div class="modal fade" id="edit_mutasi" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Mutasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open_multipart("assets_2/edit_mutasi_assets/"); ?>
            <div class="modal-body">
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">User</label>
                        <div class="col-sm-9">
                            <div class="col-sm-6">
                                <input type="text" name='id_assets_mutasi' id="id_edit" hidden>
                                <?php
                                    $username=array();
                                    foreach($user->result() as $value)
                                    {
                                        $username[$value->id]= $value->username.' - ('.$value->email.')';
                                    }
                                    
                                    echo form_dropdown('userid', $username,'','class="form-control"  id="userid"');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal
                            Mutasi</label>
                        <div class="col-sm-9">
                            <div class="col-sm-6">
                                <input class="form-control" type="date" name="tm" id="tgl_mutasi">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Alasan Mutasi</label>
                        <div class="col-sm-9">
                            <div class="col-sm-6">
                                <input class="form-control" type="text" name="am" id="alasan_mutasi">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Upload 1 </label>
                        <div class="col-sm-9">
                            <div class="col-sm-6">
                                <img alt="" id="bukti_upload"  width='100%' height='100px'>
                                <br>
                                <font color="red">*barang</font>
                                <input type="file" name="file" id="file" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Upload 2</label>
                        <div class="col-sm-9">
                            <div class="col-sm-6">
                            <img alt="" id="bukti_upload2"  width='100%' height='100px'>
                                <br>
                                <font color="red">*barcode / serial number</font>
                                <input type="file" name="file2" id="file2" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-9">
                            <div class="col-sm-6">
                                <?php 
                                    echo form_submit('submit','Simpan', 'class="btn btn-success"');
                                    echo form_close();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function editMutasi(param) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('assets_2/get_assets') ?>",
            data: {
                id: param
            },
            dataType: "json",
            success: function(response) {
                console.log(response.get_assets_mutasi);
                $("#edit_mutasi").modal() // Buka Modal
                $('#id_edit').val(param) // parameter
                $('#userid').val(response.get_assets_mutasi.userid)
                $('#tgl_mutasi').val(response.get_assets_mutasi.tgl_mutasi)
                $('#alasan_mutasi').val(response.get_assets_mutasi.alasan_mutasi)
                $('#alasan_approve').val(response.get_assets_mutasi.alasan_approve)
                $("#bukti_upload").attr('src', '<?=base_url().'assets/file/bukti_mutasi/';?>'.concat(response.get_assets_mutasi.bukti_upload))
                $("#bukti_upload2").attr('src','<?=base_url().'assets/file/bukti_mutasi/';?>'.concat(response.get_assets_mutasi.bukti_upload2))
                .change();
            }
        });
    }
</script>