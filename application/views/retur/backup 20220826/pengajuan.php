<?php
foreach ($site_code as $a) {
    $site_dp = $a->site_code;
    $subbranch_dp = $a->nama_comp;
    $company_dp = $a->company;
    $site[$a->site_code] = $a->nama_comp;
}

foreach ($history as $a) {
    $supp = $a->supp;
    // echo "supp : ".$supp;
}

// var_dump($history);

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
                        <div class="col-md-12 col-xl-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title; ?></h5>
                                </div>
                                <div class="card-block">
                                    <?php echo form_open_multipart($url); ?>

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
                                        <label class="col-sm-3">Principal</label>
                                        <div class="col-sm-9">
                                            <select name="principal" class="form-control" id="principal" required>
                                                <option value="">Pilih</option>
                                                <option value="001">PT. DELTOMED LABORATORIES</option>
                                                <option value="002">PT. MARGUNA TARULATA APK FARMA </option>
                                                <option value="004">PT. JAYA AGUNG MAKMUR</option>
                                                <option value="005">PT. ULTRA SAKTI</option>
                                                <option value="012">PT. INTRAFOOD SINGABERA INDONESIA</option>
                                                <option value="013">PT. NUTRISI HARAPAN BANGSA (STRIVE) </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">Tanggal Pengajuan</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" name="tanggal_pengajuan" value="<?= date("Y-m-d"); ?>" readonly required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">Nama Yang Mengajukan</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="nama" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Pilih File Pendukung</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="file" class="form-control">
                                            <p style="color: rgb(0 0 0 / 30%);">Format yang diijinkan (jpg,doc,docx,xls,xlsx)</p>
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

                        <div class="col-md-12 col-xl-12">
                            <div class="card comp-card">
                                <div class="card-header">
                                    <h5 class="mb-4">History</h5>
                                    <div>
                                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#raw_data">
                                            Raw Data Pengajuan Retur
                                        </button>

                                    </div>
                                    <?php $this->load->view('retur/raw_data'); ?>
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
                                                                    <font size="2px">SubBranch
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Tanggal Pengajuan
                                                                </th>
                                                                <th>
                                                                    <font size="2px">No.Pengajuan
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Principal
                                                                </th>
                                                                <!-- <th><font size="2px">Detail Produk</th> -->
                                                                <th class="admin">Closed</th>
                                                                <th>Delete</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($history as $a) : ?>
                                                                <tr>
                                                                    <td>
                                                                        <font size="2px">
                                                                            <?php
                                                                            $userid = $this->session->userdata('id');
                                                                            if ($a->status == '1') {
                                                                                echo anchor(base_url() . "retur/produk_pengajuan/" . $a->signature . '/' . $a->supp, $a->nama_status, array(
                                                                                    'class' => 'btn btn-dark btn-round btn-sm'
                                                                                ));
                                                                            } elseif ($a->status == '2') {
                                                                                echo anchor(base_url() . "retur/produk_pengajuan/" . $a->signature . '/' . $a->supp, $a->nama_status, array(
                                                                                    'class' => 'btn btn-warning btn-round btn-sm'
                                                                                ));
                                                                            } elseif ($a->status == '3') {
                                                                                echo anchor(base_url() . "retur/produk_pengajuan/" . $a->signature . '/' . $a->supp, $a->nama_status, array(
                                                                                    'class' => 'btn btn-danger btn-round btn-sm'
                                                                                ));
                                                                            } elseif ($a->status == '4') {
                                                                                if ($userid == 297 || $userid == 588 || $userid == 547) {
                                                                                    echo anchor(base_url() . "retur/approval_pengajuan_retur/" . $a->signature . '/' . $a->supp, $a->nama_status, array(
                                                                                        'class' => 'btn btn-success btn-round btn-sm'
                                                                                    ));
                                                                                } else {
                                                                                    echo anchor('', $a->nama_status, array(
                                                                                        'class' => 'btn btn-success btn-round btn-sm disabled'
                                                                                    ));
                                                                                }
                                                                            } elseif ($a->status == '5') {
                                                                                echo anchor(base_url() . "retur/kirim_barang/" . $a->signature, $a->nama_status, array(
                                                                                    'class' => 'btn btn-danger btn-round btn-sm'
                                                                                ));
                                                                            } elseif ($a->status == '6') {
                                                                                echo anchor(base_url() . "retur/pemusnahan/" . $a->signature, $a->nama_status, array(
                                                                                    'class' => 'btn btn-danger btn-round btn-sm'
                                                                                ));
                                                                            } elseif ($a->status == '7') {
                                                                                if ($userid == 297 || $userid == 588 || $userid == 547) {
                                                                                    echo anchor(base_url() . "retur/terima_barang/" . $a->signature, $a->nama_status, array(
                                                                                        'class' => 'btn btn-info btn-round btn-sm'
                                                                                    ));
                                                                                } else {
                                                                                    echo anchor(base_url() . "retur/terima_barang/" . $a->signature, $a->nama_status, array(
                                                                                        'class' => 'btn btn-info btn-round btn-sm disabled'
                                                                                    ));
                                                                                }
                                                                            } elseif ($a->status == '8') { ?>
                                                                                <button class="btn btn-dark btn-round btn-sm">Barang Tiba di Principal</button>

                                                                            <?php
                                                                            } elseif ($a->status == '9') { ?>
                                                                                <button class="btn btn-dark btn-round btn-sm">Pemusnahan oleh dp</button>

                                                                            <?php
                                                                            }

                                                                            ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px"><?= $a->nama_comp; ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px"><?= $a->tanggal_pengajuan; ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px">
                                                                            <?= anchor(base_url() . "retur/produk_pengajuan/" . $a->signature . "/" . $a->supp, $a->no_pengajuan, "target = blank");
                                                                            ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px"><?= $a->namasupp; ?>
                                                                    </td>
                                                                    <td class="admin"><?php
                                                                                        if ($a->status == '8' || $a->status == '9') {
                                                                                            echo anchor(base_url() . "retur/closed_pengajuan_retur/" . $a->signature, 'closed', array(
                                                                                                'class' => 'btn btn-dark btn-round btn-sm'
                                                                                            ));
                                                                                        } else {
                                                                                        } ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo
                                                                        anchor(
                                                                            'retur/delete_retur/' . $a->signature,
                                                                            ' ',
                                                                            array(
                                                                                'class' => 'fa fa-times fa-2x', 'style' => 'color:red',
                                                                                'onclick' => 'return confirm(\'Are you sure?\')'
                                                                            )
                                                                        );
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        echo anchor(base_url() . "retur/log_retur/" . $a->signature . '/' . $a->supp, 'Log', array(
                                                                            'class' => 'btn btn-dark btn-round btn-sm',
                                                                            'target' => 'blank'
                                                                        ));
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>

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


                                // $("#principal").click(function(){                                 
                                //     const principal = $(this).val()
                                //     console.log(principal)

                                //     if (principal == 1) { //pilih deltomed
                                //         console.log('anda pilih 1')
                                //         // $('.dp').remove();
                                //     } else {
                                //         console.log('anda pilih else')
                                //         // $('.admin').remove();
                                //     }

                                // });


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