<button type="button" class="btn btn-success btn-mat btn-round btn-md" data-toggle="modal" data-target="#tambahModal">
    Tambah
</button>

<div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th>
                        <font size="2px">Status
                    </th>
                    <th>
                        <font size="2px">Sub Branch
                    </th>
                    <th>
                        <font size="2px">Nama
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
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $a) : ?>
                    <tr>
                        <td>
                            <font size="2px"><?= $a->status; ?>
                        </td>
                        <td>
                            <font size="2px"><?= $a->nama_comp; ?>
                        </td>
                        <td>
                            <font size="2px"><?= $a->nama; ?>
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
                            <a href="#" onclick="getImage('<?= $a->id ?>')"><img alt="" src="<?= base_url() . 'assets/uploads/helpdesk/' . $a->filename; ?>" style='max-width: 30%;'></a>
                        </td>
                        <td>
                            <?php if ($a->status == 'belum diproses') { ?>
                                <a href="helpdesk/proses_helpdesk/<?= $a->id; ?>" type="button" class="btn btn-warning btn-mat btn-round btn-sm" role="button">
                                    PROCESS
                                </a>
                                <a href="helpdesk/success_helpdesk/<?= $a->id; ?>" type="button" class="btn btn-success btn-mat btn-round btn-sm" role="button">
                                    FINISHED
                                </a>
                            <?php } elseif ($a->status == 'proses') { ?>
                                <a href="helpdesk/success_helpdesk/<?= $a->id; ?>" type="button" class="btn btn-success btn-mat btn-round btn-sm" role="button">
                                    FINISHED
                                </a>
                            <?php } else { ?>
                                <a href="helpdesk/success_helpdesk/<?= $a->id; ?>" type="button" class="btn btn-success btn-mat btn-round btn-sm disabled" role="button">
                                    FINISHED
                                </a>
                            <?php } ?>
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
                        <font size="2px">Sub Branch
                    </th>
                    <th>
                        <font size="2px">Nama
                    </th>
                    <th>
                        <font size="2px">Masalah
                    </th>
                    <th>
                        <font size="2px">Note
                    </th>
                    <th>
                        <font size="2px">Image
                    </th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- ============================================ MODAL TAMBAH ========================================= -->
<?php $this->load->view('helpdesk/modal_form_helpdesk'); ?>

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