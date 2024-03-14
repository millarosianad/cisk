<style>
    td {
        font-size: 12px;
    }

    th {
        font-size: 12px;
    }
</style>

<?php $test = count($get_noted); ?>;
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">



                        <!-- Button trigger modal -->
                        <div class="col-xl-12 col-md-12 note">
                            <button type="button" class="btn btn-primary btn-round" data-toggle="modal" data-target="#exampleModal">
                                Noted
                            </button>
                            <br>
                            <br>
                        </div>

                        <!-- chart pie -->
                        <div class="col-md-12 col-xl-6">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5>Closing Bulan <?= $get_bulan_sekarang; ?></h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li class="first-opt"><i class="feather icon-chevron-left open-card-option"></i>
                                            </li>
                                            <li><i class="feather icon-maximize full-card"></i></li>
                                            <li><i class="feather icon-minus minimize-card"></i>
                                            </li>
                                            <li><i class="feather icon-refresh-cw reload-card"></i>
                                            </li>
                                            <li><i class="feather icon-trash close-card"></i></li>
                                            <li><i class="feather icon-chevron-left open-card-option"></i>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div id="donutchart" style="width: 80%; height: 200%;"></div>
                                </div>
                            </div>
                        </div>





                        <div class="col-xl-6 col-md-6">
                            <div class="card prod-bar-card">
                                <div class="card-header">
                                    <h5>Belum Closing Bulan <?= $get_bulan_sekarang; ?></h5>
                                </div>
                                <div class="card-block">

                                    <?php

                                    if ($get_dp_belum_closing == array()) {
                                        echo "seluruh sub branch belum closing";
                                        echo "<br><br><br><br><br><br>";
                                    } else
                                    ?>
                                    <ul class="col-11">
                                        <?php {
                                        foreach ($get_dp_belum_closing as $a) { ?>
                                                <li><?= $a->nama_comp; ?></li>
                                            <?php } ?>
                                            <li></li>
                                        <?php
                                    } ?>
                                    </ul>
                                    <hr>
                                    <div class="noted">
                                        Keterangan IT : <?= $get_noted->noted; ?>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-xl-12 col-md-6">
                            <div class="card prod-bar-card">
                                <div class="card-header">
                                    <h5>Kalender Data</h5>
                                </div>
                                <div class="card-block">
                                    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th width="1%"><font size="2px">Branch</th>
                                                <th><font size="2px">SubBranch</th>
                                                <th><font size="2px">Tanggal Data <?= $get_bulan_sekarang; ?></th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($get_tanggal_data as $a) : ?>
                                                <tr>
                                                    <td><font size="2px"><?= $a->branch_name; ?></td>
                                                    <td><font size="2px"><?= $a->nama_comp; ?></td>
                                                    <td><font size="2px"><?= $a->tanggal_data; ?></td>
                                                    <td><font size="2px"><a href="<?= base_url().'dashboard_dummy/detail_kalender_data/'.$a->kode ?>" class="btn btn-primary" target="_blank">history</a></td>
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
    </div>
</div>


<!-- Modal Noted-->
<?php $this->load->view('dashboard_dummy/form_noted'); ?>

<!-- script chart pie -->
<script type="text/javascript" src="<?= base_url() ?>assets_new/js/charts.js"></script>

<script type="text/javascript">
    google.charts.load("current", {
        packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Informasi Closing DP'],
            <?php foreach ($get_closing as $a) {
                echo "['$a->status', $a->jumlah_dp],";
            } ?>
        ]);

        var options = {
            title: '',
            pieHole: 0.3,
            //   width: 700,
            //   width_units: '%',
            chartArea: {
                'width': '60%',
                'height': '100%'
            },
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
    }
</script>

<!-- fungsi noted -->
<script type="text/javascript">
    $(document).ready(function() {
        var id = <?= $this->session->userdata('id'); ?>;
        var note = <?= $test; ?>;

        // alert(id);
        if (id == '547' || id == '297' || id == '289') {
            $('.note').show()
        } else {
            $('.note').remove()
        }
        if (note == '1') {
            $('.noted').show()
        } else {
            $('.noted').remove()
        }

        $('#order-table_wrapper').DataTable();
        $('.stock').hide();
    });
</script>

<!-- fungsi button -->
<script>
    $('#btn-year').hide()
    $('#month').hide()

    function year() {
        $('#btn-month').show()
        $('#btn-year').hide()
        $('#year').show()
        $('#month').hide()
    }

    function month() {
        $('#btn-year').show()
        $('#btn-month').hide()
        $('#year').hide()
        $('#month').show()
    }

    function stock() {
        $('.btn-stock').hide()
        $('.stock').show()
    }
</script>

<!-- upload tercepat -->
<script type="text/javascript" src="<?= base_url('assets/jquery.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/datatables/media/js/dataTables.bootstrap.min.js') ?>"></script>
<!-- <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script> -->
<!-- https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js -->


<!-- table-sales-bulan-berjalan -->
<script type="text/javascript">
    $(".table-sales-bulan-berjalan").DataTable({
        bInfo: false,
        bLengthChange: false,
        "sDom": "lfrti",
        bFilter: false,
        bAutoWidth: true,
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= base_url('dashboard_dummy/get_bulan_berjalan') ?>",
            type: 'POST',
        }
    });
</script>
