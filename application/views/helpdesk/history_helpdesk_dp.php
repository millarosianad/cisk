<div class="col-md-12 col-xl-7">
    <div class="card comp-card">
        <div class="card-header">
            <h5>History</h5>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col">
                    <div class="card-block">
                        <div class="dt-responsive table-responsive">
                            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>
                                            <font size="2px">Status
                                        </th>
                                        <th>
                                            <font size="2px">Nama
                                        </th>
                                        <th>
                                            <font size="2px">Tanggal
                                        </th>
                                        <th>
                                            <font size="2px">Masalah
                                        </th>
                                        <th>
                                            <font size="2px">Deskripsi
                                        </th>
                                        <th>
                                            <font size="2px">Note
                                        </th>
                                        <th>
                                            <font size="2px">Image
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($history as $a) : ?>
                                        <tr>
                                            <td>
                                                <font size="2px">
                                                    <?php if ($a->status == 'belum diproses') { ?>
                                                        <button class="btn btn-primary btn-round btn-sm disabled">WAITING</button>
                                                    <?php } elseif ($a->status == 'proses') { ?>
                                                        <button class="btn btn-warning btn-round btn-sm disabled">PROCESS</button>
                                                    <?php } elseif ($a->status == 'selesai') { ?>
                                                        <button class="btn btn-success btn-round btn-sm disabled">FINISHED</button>
                                                    <?php } ?>
                                            </td>
                                            <td>
                                                <font size="2px"><?= $a->nama; ?>
                                            </td>
                                            <td>
                                                <font size="2px"><?= $a->created_date; ?>
                                            </td>
                                            <td>
                                                <font size="2px"><?= $a->masalah; ?>
                                            </td>
                                            <td>
                                                <font size="2px"><?= $a->deskripsi; ?>
                                            </td>
                                            <td>
                                                <a href="helpdesk/note_helpdesk/<?= $a->id; ?>" type="button" class="btn waves-effect waves-light btn-info btn-outline-info btn-sm" role="button">
                                                    Note
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" onclick="getImage('<?= $a->id ?>')"><img alt="" src="<?= base_url() . 'assets/uploads/helpdesk/' . $a->filename; ?>" style='max-width: 70%;'></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th>
                                            <font size="2px">Status
                                        </th>
                                        <th>
                                            <font size="2px">Nama
                                        </th>
                                        <th>
                                            <font size="2px">Tanggal
                                        </th>
                                        <th>
                                            <font size="2px">Masalah
                                        </th>
                                        <th>
                                            <font size="2px">Deskripsi
                                        </th>
                                        <th>
                                            <font size="2px">Note
                                        </th>
                                        <th>
                                            <font size="2px">Image
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== View Image ============================================ -->
<?php $this->load->view('helpdesk/view_image'); ?>

<script>
    function getImage(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('helpdesk/get_ImagebyID') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function(response) {
                console.log(response.image);

                $("#viewimage").modal() // Buka Modal
                $("#img_view").attr('src', './assets/uploads/helpdesk/'.concat(response.image.filename))
            }
        });
    }
</script>