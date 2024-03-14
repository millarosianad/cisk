<?php $test = count($get_noted);?>;
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <!-- Button trigger modal -->
                        <div class="col-xl-12 col-md-12 note">
                            <button type="button" class="btn btn-primary btn-round" data-toggle="modal"
                                data-target="#exampleModal">
                                Noted
                            </button>
                            <br>
                            <br>
                        </div>

                        <!-- chart pie -->
                        <div class="col-md-12 col-xl-8">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5>Closing Bulan <?= $get_bulan_sekarang; ?></h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li class="first-opt"><i
                                                    class="feather icon-chevron-left open-card-option"></i>
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

                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5>Belum Closing Bulan <?= $get_bulan_sekarang; ?></h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li class="first-opt"><i
                                                    class="feather icon-chevron-left open-card-option"></i>
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
                                    <div class="row">
                                        <ul class="col-6">
                                            <?php foreach ($get_dp_belum_closing as $a) { ?>
                                            <li><?= $a->nama_comp; ?></li>
                                            <?php }?>
                                            <li>..</li>
                                        </ul>
                                        <ul class=" col-6 noted">
                                            <h5>Noted :</h5>
                                            <br>
                                            <?= $get_noted->noted; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card comp-card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h5 class="m-b-25">Upload Data Tercepat</h5>
                                            <ul>
                                                <?php foreach ($get_upload_tercepat as $a) { ?>
                                                <li class="row">
                                                    <p class="col-6"><?= $a->nama_comp; ?></p>
                                                    <p class="col-6"><?= $a->tgl; ?></p>
                                                </li>
                                                <?php }?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card comp-card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h5 class="m-b-25">Upload Data Terlama</h5>
                                            <ul>
                                                <?php foreach ($get_upload_terlama as $a) { ?>
                                                    <li class="row">
                                                    <p class="col-6"><?= $a->nama_comp; ?></p>
                                                    <p class="col-6"><?= $a->tgl; ?></p>
                                                    </li>
                                                <?php }?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- button -->
                        <button id="btn-year" type="button" class="btn btn-primary btn-round" onclick="year()">
                            Sales Year
                        </button>
                        <button id="btn-month" type="button" class="btn btn-success btn-round" onclick="month()">
                            Sales Month
                        </button>
                        <button type="button" class="btn btn-warning btn-round btn-stock" onclick="stock()">
                            Stock
                        </button>
                        <br><br>

                    <!-- sales -->
                    <?php $this->load->view('dashboard/table_sales');?>
                    
                    <!-- stock -->
                    <?php $this->load->view('dashboard/table_stock');?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Noted-->
<?php $this->load->view('dashboard/form_noted');?>

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
            <?php foreach($get_closing as $a) {
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
    $(document).ready(function () {
        var id = <?= $this->session->userdata('id');?>;
        var note = <?= $test;?>;

        // alert(id);
        if(id == '547' || id == '297' || id == '289' ){
            $('.note').show()
        }else{
            $('.note').remove()
        }
        if(note == '1' ){
            $('.noted').show()
        }else{
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
    function month(){
        $('#btn-year').show()
        $('#btn-month').hide()
        $('#year').hide()
        $('#month').show()
    }
    function stock(){
        $('.btn-stock').hide()
        $('.stock').show()
    }
</script>