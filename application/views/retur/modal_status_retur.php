<?php

// $required = "";
$required = "required";

?>
<!-- modal tambah profile -->
<div class="modal fade" id="vendor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Switch Status Retur</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open('retur/update_status_retur'); ?>
            <div class="modal-body">

            <div class="row">
                <div class="col-md-2">
                    Pilih status
                </div>
                <div class="col-md-8">
                    <select name="status_retur" class="form-control" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="1">1. Pending DP</option>
                        <option value="2">2. Proses MPM</option>
                        <option value="3">3. Proses DP</option>
                        <option value="4">4. Pending Principal</option>
                        <option value="5">5. Proses Kirim Barang</option>
                        <option value="6">6. Proses Pemusnahan</option>
                        <option value="7">7. Proses Principal Terima Barang</option>
                        <option value="8">8. Barang Diterima oleh Principal</option>
                        <option value="9">9. Pemusnahan oleh DP</option>
                        <option value="10">10. Lainnya</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-2">
                    Keterangan Lain
                </div>
                <div class="col-md-8">                 
                    <input type="text" class="form-control" name="keterangan_lain" value="">
                </div>
            </div>
                
                <input type="hidden" name="signature" id="signature">

            </div>
            <div class="modal-footer">
                <?php
                    if ($this->session->userdata('id')  == '442' || $this->session->userdata('id')  == '588' || $this->session->userdata('id')  == '857' ||$this->session->userdata('id')  == '515' || $this->session->userdata('id')  == '297') {
                        echo form_submit('submit', 'update data', 'class="btn btn-success" required');
                    }
                ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function get_site_code(params){
        // console.log(params)
        $("#signature").val(params)
    }
</script>