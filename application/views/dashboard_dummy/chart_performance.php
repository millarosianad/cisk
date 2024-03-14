<!DOCTYPE html>
<html>
<head>

    <script type="text/javascript" src="<?php echo base_url() ?>assets_new/js/charts.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart4);
      google.charts.setOnLoadCallback(drawChart5);
      google.charts.setOnLoadCallback(drawChart6);

      <?php 
      $bln= date('m');
       if ($bln == '1'){
           $bulan ='Januari' ;
           $bulan2='Desember' ;
           $bulan3 ='November';
       }elseif ($bln == '2'){
           $bulan ='Februari';
           $bulan2='Januari' ;
           $bulan3 ='Desember';
       }elseif ($bln == '3'){
           $bulan = 'Maret'  ;
           $bulan2='Februari' ;
           $bulan3 ='Januari';
       }elseif ($bln == '4'){
           $bulan ='April'   ;
           $bulan2='Maret' ;
           $bulan3 ='Februari';
       }elseif ($bln == '5'){
           $bulan ='Mei'     ;
           $bulan2='April' ;
           $bulan3 ='Maret';
       }elseif ($bln == '6'){
           $bulan ='Juni'    ;
           $bulan2='Mei' ;
           $bulan3 ='April';
       }elseif ($bln == '7'){
           $bulan ='Juli'    ;
           $bulan2='juni' ;
           $bulan3 ='Mei';
       }elseif ($bln == '8'){
           $bulan ='Agustus' ;
           $bulan2='Juli' ;
           $bulan3 ='Juni';
       }elseif ($bln == '9'){
           $bulan ='September';
           $bulan2='Agustus' ;
           $bulan3 ='Juli';
       }elseif ($bln == '10'){
           $bulan = 'Oktober' ;
           $bulan2='September' ;
           $bulan3 ='Agustus';
       }elseif ($bln == '11'){
           $bulan = 'November';
           $bulan2='Oktober' ;
           $bulan3 ='September';
       }elseif ($bln == '12'){
           $bulan = "Desember";
           $bulan2='November' ;
           $bulan3 ='Oktober';
       }
       $sub = $jmlh_branch['sub'];
       $subx = explode(" ",$sub);
       $jmlh = count($subx);
      ?>

      function drawChart4() {
        var data = google.visualization.arrayToDataTable([
          ['Dp', 'omzet','stok','piutang'],
          <?php 
            foreach ($get_chart_performance_1 as $a) {
                echo "['$a->branch_name', $a->tot_omzet,$a->tot_harga_stok,$a->tot_piutang],";
            }
            ?>
        ]);

        var options = {
          chart: {
            title: '<?php echo $bulan ;?>',
            subtitle: 'Sales, Stok, and Piutang ',
          },
          bars: 'horizontal', // Required for Material Bar Charts.
          bar: { groupWidth: "85%" },
          hAxis: {format: 'decimal'},
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }

      function drawChart5() {
        var data = google.visualization.arrayToDataTable([
          ['Dp', 'omzet','stok','piutang'],
          <?php 
            foreach ($get_chart_performance_2 as $a) {
                echo "['$a->branch_name', $a->tot_omzet,$a->tot_harga_stok,$a->tot_piutang],";
            }
            ?>
        ]);

        var options = {
          chart: {
            title: '<?php echo $bulan2 ;?>',
            subtitle: 'Sales, Stok, and Piutang ',
          },
          bars: 'horizontal', // Required for Material Bar Charts.
          bar: { groupWidth: "80%" },
          hAxis: {format: 'decimal'},
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material_2'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }

      function drawChart6() {
        var data = google.visualization.arrayToDataTable([
          ['Dp', 'omzet','stok','piutang'],
          <?php 
            foreach ($get_chart_performance_3 as $a) {
                echo "['$a->branch_name', $a->tot_omzet,$a->tot_harga_stok,$a->tot_piutang],";
            }
            ?>
        ]);

        var options = {
          chart: {
            title: '<?php echo $bulan3 ;?>',
            subtitle: 'Sales, Stok, and Piutang ',
          },
          bars: 'horizontal', // Required for Material Bar Charts.
          bar: { groupWidth: "80%" },
          hAxis: {format: 'decimal'},
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material_3'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
</head>
<body>

  <!-- ====================================== bulan sekarang ======================================== -->
  <div class="col-xl-12 col-md-12">
    <div class="card latest-update-card">
      <div class="card-header">
        <h5>Performance</h5>
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

  <div id="barchart_material" style="width: 100%; height: <?php if ($sub == '') {
        $a = 700*$jmlh;
        echo $a;
      }else{
        $a = 70*$jmlh;
        echo $a;
      } ;?>px;"></div>
  </div>
  </div>
  </div>

  <!-- ====================================== 1 bulan sebelumnya ======================================== -->

  <div class="col-xl-6 col-md-12">
    <div class="card latest-update-card">
      <div class="card-header">
        <h5>Performance</h5>
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

  <div id="barchart_material_3" style="width: 100%; height: <?php if ($sub == '') {
        $a = 900*$jmlh;
        echo $a;
      }else{
        $a = 70*$jmlh;
        echo $a;
      } ;?>px;"></div>
  </div>
  </div>
  </div>

  <!-- ====================================== 2 bulan sebelumnya ======================================== -->

  <div class="col-xl-6 col-md-12">
    <div class="card latest-update-card">
      <div class="card-header">
        <h5>Performance</h5>
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

  <div id="barchart_material_2" style="width: 100%; height:<?php if ($sub == '') {
        $a = 900*$jmlh;
        echo $a;
      }else{
        $a = 70*$jmlh;
        echo $a;
      } ;?>px;"></div>
  </div>
  </div>
  </div>

</body>
</html>