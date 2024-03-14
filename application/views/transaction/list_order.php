<style>
    table th,
    table td {
        white-space: normal !important;
    }
</style>
<div class="col-12">
    <div class="row">
        <div class="col-2">Company</div>
        <div class="col-6">
            <?php echo form_open($url1);
            $company = array();
            foreach ($query->result() as $value) {
                $company[$value->id] = $value->company . ' / ' .  $value->username;
            }
            echo form_dropdown('company', $company, '', 'class="form-control"');
            ?>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-2"></div>
        <div class="col-6">
            <?php echo form_submit('submit', 'Pilih Company', 'class="btn btn-primary btn-sm"') ?>
            <?php echo form_close(); ?>

            <a href="<?php echo base_url() . "transaction/list_order" ?> " class="btn btn-warning btn-outline-warning btn-sm" role="button">All Company</a>

            <?php if ($this->session->userdata('id') == 442 || $this->session->userdata('id') == 588 || $this->session->userdata('id') == 515 || $this->session->userdata('id') == 2971) { ?>
                <a href="<?php echo base_url() . "transaction" ?> " class="btn btn-success btn-sm" role="button">Create Order</a>
            <?php } else {
                echo '';
            } ?>

            <a href="#" class="btn btn-warning btn-sm" role="button" data-toggle="modal" data-target="#cetakpdf" data-keyboard="false" data-backdrop="static">Cetak Pdf by Date</a>
        </div>
    </div>
    <br><br>
    <div class="dt-responsive table-responsive">
        <table id="table-listorder" class="table table-striped table-bordered nowrap" style="font-size: 12px;">
            <thead>
                <tr>
                    <th></th>
                    <th>No</th>
                    <th>Company</th>
                    <th>Sub Branch</th>
                    <th>Tgl Pesan</th>
                    <th>Tgl PO</th>
                    <th>No PO</th>
                    <th>Tipe</th>
                    <th>Supplier</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hasil as $x) : ?>
                    <tr>
                        <td>
                            <center>
                                <?php if ($this->session->userdata('id') == 442 || $this->session->userdata('id') == 588 || $this->session->userdata('id') == 857 || $this->session->userdata('id') == 515 || $this->session->userdata('id') == 2971) {

                                    if ($x->open == '1') {
                                        echo anchor('trans/po/email/' . $x->id . '/' . $x->userid . $x->supp, '<i class="ti-email fa-2x" style="color:blue"></i>', array('target' => 'blank'));
                                        echo "   |   ";
                                        echo anchor('transaction/delete_po/' . $x->id, '<i class="fa fa-times-circle-o fa-2x" style="color:red"></i>', array('onclick' => 'return confirm(\'Are you sure?\')'));
                                    } else {
                                        echo anchor('transaction/delete_po/' . $x->id, '<i class="fa fa-times-circle-o fa-2x" style="color:red"></i>', array('onclick' => 'return confirm(\'Are you sure?\')'));
                                    }
                                } else {
                                    echo '';
                                } ?>
                        </td>
                        <td>
                            <?php
                            if ($this->session->userdata('id') == 442 || $this->session->userdata('id') == 588 || $this->session->userdata('id') == 515 || $this->session->userdata('id') == 2971) {
                            ?>
                                <a href="<?= base_url() . "transaction/list_order_detail/" . $x->id . '/' . $x->supp; ?>" target="blank"><?= $x->id; ?></a>
                            <?php } else {
                                echo '';
                            } ?>

                        </td>
                        <td>
                            <?= $x->company; ?>
                        </td>
                        <td>
                            <?= $x->nama_comp; ?>
                        </td>
                        <td>
                            <?= $x->tglpesan; ?>
                        </td>
                        <td><?php
                            if ($x->tglpo == '') {
                                echo "<font color='red'><i>Belum Ada";
                            } else {
                                echo $x->tglpo;
                            }
                            ?>
                        </td>
                        <td>
                            <center>
                                <?php
                                if ($x->nopo == null) {
                                    echo "<i>" . anchor(base_url() . "transaction/download_pdf/" . $x->id, 'belum tersedia', ['target' => '_blank']);
                                } else {
                                    echo anchor(base_url() . "transaction/download_pdf/" . $x->id, $x->nopo, ['target' => '_blank']);
                                }
                                ?>
                        </td>
                        <td>
                            <center><?= $x->tipe; ?>
                        </td>
                        <td><?= $x->namasupp; ?></td>
                        <td>Rp. <?= number_format($x->total); ?></td>

                        <td><?php
                            if ($x->status == '1') {
                                echo "<strong><font color='red'>logistic approval</font>";
                            } elseif ($x->status == '2') {
                                if ($x->open == '1') {
                                    if ($x->nopo == null) {
                                        echo "<strong><font color='blue'>Proses PO</font>";
                                    } else {
                                        echo "<strong><font color='black'>Selesai</font>";
                                    }
                                } else {
                                    echo "<strong><font color='orange'>finance approval</font>";
                                }
                            } else {
                                echo "<strong><font color='red'>doi checking</font>";
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- =============================================== modal ======================================================== -->

<div class="modal fade" id="cetakpdf" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cetak Pdf By Date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('transaction/download_pdf_by_date'); ?>" method="post">
                <div class="modal-body">
                    <label for="from" class="form-label">From</label>
                    <input type="date" class="form-control" name="from" id="from" required>
                    <label for="to" class="form-label">To</label>
                    <input type="date" class="form-control" name="to" id="to" required>
                </div>
                <div class="modal-footer" style="justify-content: center;">
                    <button class="btn btn-round btn-sm btn-success submit" value="upload">Cetak</button>
                </div>
        </div>
        </form>
    </div>
</div>