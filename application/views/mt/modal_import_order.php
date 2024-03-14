<?php

// $required = "";
$required = "required";

?>
<!-- modal tambah profile -->
<div class="modal fade" id="tambah_profile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Import File Original</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open_multipart($url); ?>
            <div class="modal-body">
                
                <!-- <div class="form-group row">
                    <label class="col-sm-4">Sub Branch</label>
                    <div class="col-sm-6">
                        <select name="site_code" id="site_code_profile" class="form-control" <?= $required; ?>>
                        </select>
                    </div>
                </div> -->

                <div class="form-group row">
                    <label class="col-sm-4">Partner</label>
                    <div class="col-sm-6">
                        <select class="form-control" value="0" name="partner">
                            <option value=""> - Pilih Salah Satu - </option>
                            <option value="sbl">SBL</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Csv Original</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="file" name="file_csv" <?= $required; ?> />
                            
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Pdf Original</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="file" name="file_pdf" <?= $required; ?> />
                            
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
        console.log('teststruktur')
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('database_afiliasi/subbranch') ?>',
            success: function(hasil_subbranch) {
                $("select[name = site_code]").html(hasil_subbranch);
            }
        });
        
    });
</script>

<script>
    function addProfile() 
    {
        console.log('ini function addProfile')
        $.ajax({
            success: function(response) {
                console.log(response.add_profile);
                $("#tambah_profile").modal() // Buka Modal
              
                .change();
                
            }
        });

    }
    
    
</script>