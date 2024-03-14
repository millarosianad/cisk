<div class="col-sm-10">
    <button type="button" class="btn btn-success btn-mat btn-sm btn-round" data-toggle="modal" data-target="#tambahversi">
        Tambah Versi
    </button>
</div>

<div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th>
                        <font size="2px">Versi
                    </th>
                    <th>
                        <font size="2px">Tanggal
                    </th>
                    <th>
                        <font size="2px">Status
                    </th>
                    <th>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proses as $a) : ?>
                    <tr>
                        <td>
                            <font size="2px"><?php echo $a->versi; ?>
                        </td>
                        <td>
                            <font size="2px"><?php echo date('d F Y', strtotime($a->tanggal)); ?>
                        </td>
                        <td>
                            <font size="2px">
                                <?php
                                if ($a->status == 0) {
                                    echo 'Tidak Aktif';
                                } else {
                                    echo 'Aktif';
                                } ?>
                        </td>
                        <td>
                            <font size="2px">
                                <a href="detail_versi/<?= $a->versi; ?>" class="btn btn-warning btn-sm btn-round" role="button">Detail</a>
                                <?php
                                if ($a->status == 0) { ?>
                                    <a href="aktiv_versi/<?= $a->id;?>/<?= $a->versi;?>" class="btn btn-success btn-sm btn-round" role="button">Aktifkan</a>
                                <?php } else { ?>
                                    <a href="#" class="btn btn-info btn-sm btn-round" role="button" id="testOnclick" onclick="getVersiFile('<?= $a->versi ?>')">Share</a>
                                    <a href="nonaktiv_versi//<?= $a->id;?>/<?= $a->versi;?>" class="btn btn-danger btn-sm btn-round" role="button">Non-Aktif</a>
                                <?php } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>

            <tfoot>
                <tr>
                    <th>
                        <font size="2px">Versi
                    </th>
                    <th>
                        <font size="2px">Tanggal
                    </th>
                    <th>
                        <font size="2px">Status
                    </th>
                    <th>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<!-- ================================================= MODAL Form Tambah Versi ====================================================================== -->
<?php $this->load->view('file_download/form_tambah_versi'); ?>

<!-- ==================================================== Modal Form Upload =================================================================== -->
<?php $this->load->view('file_download/form_upload'); ?>

<script>
    function getVersiFile(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('file_download/get_versifile') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function(response) {
                console.log(response.versi);
                $("#share").modal() // Buka Modal
                $('#versi').val(params) // parameter
                    .change();
            }
        });
    }
</script>