<?php
foreach ($site_code as $a) {
    $site_dp = $a->site_code;
    $subbranch_dp = $a->nama_comp;
    $company_dp = $a->company;
    $site[$a->site_code] = $a->nama_comp;
}
?>

<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <div class="d-inline">
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                </div>
            </div>

        </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <div class="page-body">
                    <div class="row">

                        <!-- =================================================== Form Helpdesk ========================================== -->
                        <div class="col-md-12 col-xl-5">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title; ?></h5>
                                </div>
                                <div class="card-block">
                                    <?php echo form_open_multipart('/helpdesk/add_helpdesk'); ?>

                                    <div class="form-group row admin">
                                        <label class="col-sm-3">Sub Branch</label>
                                        <div class="col-sm-9">
                                            <?php
                                            echo form_dropdown('site_code', $site, '', 'class="form-control"  id="site_code" required');
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group row dp" hidden>
                                        <label class="col-sm-3">Site Code</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="site_code" value="<?= $site_dp; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row dp">
                                        <label class="col-sm-3">Sub Branch</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="subbranch" value="<?= $subbranch_dp; ?>" readonly required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3">Nama</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="nama" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">No. Telp</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="telp" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="email" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3">Masalah</label>
                                        <div class="col-sm-9">
                                            <select name="masalah" class="form-control" id="masalah" required>
                                                <option value="">Pilih</option>
                                                <option value="1">Barang Kurang</option>
                                                <option value="2">Barang Lebih</option>
                                                <option value="3">Barang Rusak</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row" id="customize">
                                        <label class="col-sm-3"></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="custom" placeholder="Masalah" id="custom">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">Deskripsi</label>
                                        <div class="col-sm-9">
                                            <textarea id='deskripsi' name="deskripsi" rows="6" cols="90%" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Pilih File</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="userfile" class="form-control" required>
                                            <p style="color: rgb(0 0 0 / 30%);">Format (.jpg)</p>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row" style="justify-content: center;">
                                    <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success btn-round center-block" required'); ?>
                                    <?php echo form_close(); ?>
                                </div>
                                <br>
                            </div>
                        </div>

                        <!-- ================================================ History ==================================================================== -->

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
                                                                    <font size="2px">No. Ticket
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
                                                                    <font size="2px">Note
                                                                </th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($history as $a) : ?>
                                                                <tr>
                                                                    <td>
                                                                        <font size="2px">
                                                                            <?php if ($a->status == '0') { ?>
                                                                                <button class="btn btn-primary btn-round btn-sm disabled">OPEN</button>
                                                                            <?php } elseif ($a->status == '1') { ?>
                                                                                <button class="btn btn-warning btn-round btn-sm disabled">PROCESS</button>
                                                                            <?php } elseif ($a->status == '2') { ?>
                                                                                <button class="btn btn-success btn-round btn-sm disabled">CLOSED</button>
                                                                            <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px"><?= $a->id_ticket; ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px"><?= $a->nama; ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px"><?= $a->created_date; ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px">
                                                                        <?php
                                                                        if ($a->id_kategori == '1') { 
                                                                            echo "Barang Kurang" ;
                                                                        }elseif ($a->id_kategori == '2'){
                                                                            echo "Barang Lebih" ;
                                                                        }elseif ($a->id_kategori == '3'){
                                                                            echo "Barang Rusak" ;
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <a href="<?= base_url()."helpdesk/note_helpdesk/".$a->id_ticket; ?>" type="button" class="btn waves-effect waves-light btn-info btn-outline-info btn-sm" role="button">
                                                                            Note
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($a->status == '0') { ?>
                                                                            <a href="<?= base_url()."helpdesk/proses_helpdesk/".$a->id; ?>" type="button" class="btn btn-warning btn-mat btn-round btn-sm admin" role="button">
                                                                                PROCESS
                                                                            </a>
                                                                            <a href="<?= base_url()."helpdesk/success_helpdesk/".$a->id; ?>" type="button" class="btn btn-success btn-mat btn-round btn-sm admin" role="button">
                                                                                CLOSED
                                                                            </a>
                                                                        <?php } elseif ($a->status == '1') { ?>
                                                                            <a href="<?= base_url()."helpdesk/success_helpdesk/".$a->id; ?>" type="button" class="btn btn-success btn-mat btn-round btn-sm admin" role="button">
                                                                                CLOSED
                                                                            </a>
                                                                        <?php } else { ?>
                                                                            <a href="<?= base_url()."helpdesk/success_helpdesk/".$a->id; ?>" type="button" class="btn btn-success btn-mat btn-round btn-sm admin disabled" role="button">
                                                                                CLOSED
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
                                                                    <font size="2px">No. Ticket
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
                                                                    <font size="2px">Note
                                                                </th>
                                                                <th></th>
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
    $(document).ready(function() {
        var userid = "<?= $this->session->userdata('id'); ?>";
        if (userid == 547 || userid == 297 || userid == 588) {
            $('.dp').remove();
        } else {
            $('.admin').remove();
        }

        $("#site_code").click(function() {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>helpdesk/get_company_sitecode",
                data: {
                    site_code: $(this).val(),
                },
                success: function(data) {
                    console.log(data)
                    $('#company').html(data).change();
                }
            });
        });

        $("#site_code").click(function() {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>helpdesk/get_subbranch_sitecode",
                data: {
                    site_code: $(this).val(),
                },
                success: function(data) {
                    console.log(data)
                    $('#subbranch').html(data).change();
                }
            });
        });

        $('#customize').hide()
        $("select#masalah").change(function() {
            var selectedLocation = $(this).children("option:selected").val();
            if (selectedLocation == 'lain') {
                $('#customize').show()
                $("input#custom").attr("required", "true")
            } else {
                $('#customize').hide()
                $("input#custom").removeAttr("required")
            }
        });
    });
</script>