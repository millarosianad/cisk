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
                        <font size="2px">Nama
                    </th>
                    <th>
                        <font size="2px">Masalah
                    </th>
                    <th>
                        <font size="2px">Deskripsi
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
                            <font size="2px"><?= $a->nama; ?>
                        </td>
                        <td>
                            <font size="2px"><?= $a->masalah; ?>
                        </td>
                        <td>
                            <font size="2px"><?= $a->deskripsi; ?>
                        </td>
                        <td>
                            <a href="<?= base_url() . 'assets/uploads/helpdesk/' . $a->filename; ?>" onclick="getImage('<?= $a->id ?>')" ><img alt="" src="<?= base_url() . 'assets/uploads/helpdesk/' . $a->filename; ?>" style='max-width: 30%;'></a>
                        </td>
                        <td>
                            <?php if ($a->status == 'belum diproses') { ?>
                                <a href="helpdesk/proses_helpdesk/<?= $a->id;?>" type="button" class="btn btn-warning btn-mat btn-round btn-sm" role="button">
                                    PROCESS
                                </a>
                                <a href="helpdesk/success_helpdesk/<?= $a->id;?>" type="button" class="btn btn-success btn-mat btn-round btn-sm" role="button">
                                    FINISHED
                                </a>
                            <?php } else { ?>
                                <a href="helpdesk/success_helpdesk/<?= $a->id;?>" type="button" class="btn btn-success btn-mat btn-round btn-sm disabled" role="button">
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
                        <font size="2px">Nama
                    </th>
                    <th>
                        <font size="2px">Masalah
                    </th>
                    <th>
                        <font size="2px">Deskripsi
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