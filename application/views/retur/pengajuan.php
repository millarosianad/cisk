<?php
foreach ($site_code as $a) {
    $site_dp = $a->site_code;
    $subbranch_dp = $a->nama_comp;
    $company_dp = $a->company;
    $site[$a->site_code] = $a->nama_comp . ' - ' . $a->site_code;
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

                        <div class="col-md-12 col-xl-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title; ?></h5>
                                </div>
                                <div class="card-block">
                                    <?php echo form_open_multipart($url); ?>

                                    <div class="form-group row admin">
                                        <label class="col-sm-3">Sub Branch</label>
                                        <div class="col-sm-5">
                                            <?php
                                            echo form_dropdown('site_code', $site, '', 'class="form-control"  id="site_code" required');
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group row dp" hidden>
                                        <label class="col-sm-3">Site Code</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="site_code" value="<?= $site_dp; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row dp">
                                        <label class="col-sm-3">Sub Branch</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="subbranch" value="<?= $subbranch_dp; ?>" readonly required>
                                        </div>
                                    </div>

                                    <?php
                                    if ($status_mpi == 1) { ?>

                                        <div class="form-group row">
                                            <label class="col-sm-3">Principal</label>
                                            <div class="col-sm-5">
                                                <select name="principal" class="form-control" id="principal" required>
                                                    <option value="">Pilih</option>
                                                    <option value="001-herbal">Deltomed Herbal Candy</option>
                                                    <option value="001-herbana">Deltomed Herbana Herbamojo</option>
                                                    <option value="002">Marguna </option>
                                                </select>
                                            </div>
                                        </div>

                                    <?php
                                    } else { ?>


                                        <div class="form-group row">
                                            <label class="col-sm-3">Principal</label>
                                            <div class="col-sm-5">
                                                <select name="principal" class="form-control" id="principal" required>
                                                    <option value="">Pilih</option>
                                                    <option value="001-herbal">Deltomed Herbal Candy</option>
                                                    <option value="001-herbana">Deltomed Herbana Herbamojo</option>
                                                    <option value="005">Ultra Sakti</option>
                                                    <option value="002">Marguna </option>
                                                    <option value="004">Jaya Agung Makmur</option>
                                                    <option value="012">Intrafood</option>
                                                    <option value="013">Strive</option>
                                                </select>
                                            </div>
                                        </div>

                                    <?php
                                    }
                                    ?>


                                    <div class="form-group row">
                                        <label class="col-sm-3">Tanggal Pengajuan</label>
                                        <div class="col-sm-5">
                                            <input type="date" class="form-control" name="tanggal_pengajuan" value="<?= $tanggal_hari_ini; ?>" readonly required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">Nama Yang Mengajukan</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="nama" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Pilih File Pendukung</label>
                                        <div class="col-sm-5">
                                            <input type="file" name="file" class="form-control">
                                            <p style="color: rgb(0 0 0 / 30%);">Format yang diijinkan (jpg,doc,docx,xls,xlsx)</p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Signature Digital</label>
                                        <div class="col-sm-5">
                                            <a href="<?= base_url() . 'assets/uploads/signature/' . $this->session->userdata('username') . '-signature.png' ?>" target="_blank"><img src="<?= base_url() . 'assets/uploads/signature/' . $this->session->userdata('username') . '-signature.png' ?>" width="150px"></a>
                                            <br>
                                            <a href="<?= base_url() ?>retur/register_signature" class="btn btn-outline-warning btn-sm">Manage Signature</a>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-5">

                                            <?php
                                            if ($this->session->userdata('level') == '10') {

                                                if ($this->session->userdata('id')  == '442' || $this->session->userdata('id')  == '588' || $this->session->userdata('id')  == '857' || $this->session->userdata('id')  == '515' || $this->session->userdata('id')  == '297') {

                                                    // echo form_submit('submit', 'Lanjutkan ke Penginputan Produk', 'class="btn btn-success" required');
                                                    echo form_close();
                                                } else {
                                                    echo '';
                                                }
                                            } elseif ($this->session->userdata('username') == 'maserih' || $this->session->userdata('username') == 'rizki' || $this->session->userdata('username') == 'marketing') {
                                                echo '';
                                            } else {
                                                // echo form_submit('submit', 'Lanjutkan ke Penginputan Produk', 'class="btn btn-success" required');
                                            }

                                            ?>


                                        </div>
                                    </div>

                                    <?php
                                    echo form_close();
                                    ?>


                                </div>
                            </div>
                        </div>

                        <!-- ================================================ History ==================================================================== -->

                        <div class="col-md-12 col-xl-12">
                            <div class="card comp-card">
                                <div class="card-header">
                                    <h5 class="mb-4">History</h5>

                                    <div class="row">
                                        <div class="col-md-12 d-flex justify-content-center">
                                            <div class="form-inline row">
                                                <div class="col-sm-12">
                                                    <form action="<?= $ur_search ?>">
                                                        From
                                                        <input class="form-control" type="date" name="from" required />
                                                        To
                                                        <input class="form-control" type="date" name="to" required />
                                                        <button type="submit" value="1" class="btn btn-outline-danger btn-sm" name="type">Search By Date</button>
                                                        <button type="submit" value="2" class="btn btn-outline-danger btn-sm" name="type">Export To CSV</button>
                                                        <a href="<?= base_url() ?>retur/pengajuan" class="btn btn-outline-dark btn-sm">Show 100 Latest Data</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                                                    <font size="1px">Status
                                                                </th>
                                                                <th>
                                                                    <font size="1px">SubBranch
                                                                </th>
                                                                <th>
                                                                    <font size="1px">Tanggal Pengajuan
                                                                </th>
                                                                <th>
                                                                    <font size="1px">No.Pengajuan
                                                                </th>
                                                                <th>
                                                                    <font size="1px">Principal
                                                                </th>
                                                                <th class="admin">
                                                                    <font size="1px">Closed
                                                                </th>
                                                                <th>
                                                                    <font size="1px">Delete
                                                                </th>
                                                                <th>
                                                                    <font size="1px">#
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($history as $a) : ?>
                                                                <tr>
                                                                    <td>
                                                                        <font size="1px">
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
                                                                                if ($userid == 297 || $userid == 588 || $userid == 857 || $userid == 547) {
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
                                                                                if ($userid == 297 || $userid == 588 || $userid == 857 || $userid == 547) {
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
                                                                            } elseif ($a->status == '10') { ?>
                                                                                <button class="btn btn-dark btn-round btn-sm">Lainnya</button>

                                                                            <?php
                                                                            }

                                                                            ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="1px"><?= $a->nama_comp; ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="1px"><?= $a->tanggal_pengajuan; ?>
                                                                    </td>
                                                                    <td>
                                                                        <a href="<?= base_url() . 'retur/produk_pengajuan/' . $a->signature . '/' . $a->supp; ?>" target="_blank">
                                                                            <font size="1px"><?= $a->no_pengajuan; ?>
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        <font size="1px"><?= $a->namasupp; ?>
                                                                    </td>
                                                                    <td class="admin">
                                                                        <?php
                                                                        if ($a->status == '8' || $a->status == '9' || $a->status == '10') { ?>
                                                                            <button class="btn btn-dark btn-round btn-sm">closed</button>

                                                                        <?php
                                                                        } else {
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td><?php
                                                                        echo anchor(
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
                                if (userid == 547 || userid == 297 || userid == 588 || userid == 857) {
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