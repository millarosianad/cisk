<button class="btn btn-info" onclick="openModalReportRetur()">Generate Retur PDF</button>
<!-- <button class="btn btn-warning" onclick="openModalAlasanRetur()">Update Alasan Retur</button> -->
<a href="<?= base_url().'retur/alasan_retur' ?>" target="_blank" class="btn btn-success">update alasan retur</a>
<hr>

<?php echo form_open($url); ?>

<div class="card">

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Periode Awal</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="periode_1" required />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Periode Akhir</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="periode_2" required />
                </div>
            </div>
        </div>
    </div>


    <div class="col-sm-12">
        <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_submit('submit', 'Proses', '" class="btn btn-primary"'); ?>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<?php
$this->load->view('retur/modal_report_retur');
$this->load->view('retur/modal_alasan_retur');
?>



<script>
    function openModalReportRetur() {

        $.ajax({
            success: function(response) {
                $("#x").modal()
            }
        });
    }
</script>

<script>
    function openModalAlasanRetur() {

        $.ajax({
            success: function(response) {
                $("#y").modal()
            }
        });
    }
</script>