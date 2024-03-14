<!DOCTYPE html>
<html>
<head>

    <script type="text/javascript" src="<?php echo base_url() ?>assets_new/js/charts.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart1);
      google.charts.setOnLoadCallback(drawChart2);
      google.charts.setOnLoadCallback(drawChart3);

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

      function drawChart1() {
        var data = google.visualization.arrayToDataTable([
          ['Dp', '2021','2020'],
          <?php 
            foreach ($get_chart_sales_growth_1 as $a) {
                echo "['$a->branch_name', $a->tot1,$a->tot2],";
            }
            ?>
            ]);

        var options = {
          chart: {
            title: '<?php echo $bulan ;?>',
            subtitle: '',
          },
          bars: 'horizontal', // Required for Material Bar Charts.
          hAxis: {format: 'decimal'},
          height: <?php if ($sub == '') {
            $a = 500*$jmlh;
            echo $a;
          }else{
            $a = 50*$jmlh;
            echo $a;
          } ;?>,
          width: '100%',
          colors: ['#1b9e77', '#d95f02', '#7570b3'],
          bar: { groupWidth: "80%" }
        };

        var chart = new google.charts.Bar(document.getElementById('chart_div'));

        chart.draw(data, google.charts.Bar.convertOptions(options));

        var btns = document.getElementById('btn-group');

        btns.onclick = function (e) {

          if (e.target.tagName === 'BUTTON') {
            options.hAxis.format = e.target.id === 'none' ? '' : e.target.id;
            chart.draw(data, google.charts.Bar.convertOptions(options));
          }
        }
      }

      function drawChart2() {
        var data = google.visualization.arrayToDataTable([
          ['Dp', '2021','2020'],
          <?php 
            foreach ($get_chart_sales_growth_2 as $a) {
                echo "['$a->branch_name', $a->tot1,$a->tot2],";
            }
            ?>
            ]);

        var options = {
          chart: {
            title: '<?php echo $bulan2 ;?>',
            subtitle: '',
          },
          bars: 'horizontal', // Required for Material Bar Charts.
          hAxis: {format: 'decimal', maxvalue: '14000000000'},
          height: <?php if ($sub == '') {
            $a = 500*$jmlh;
            echo $a;
          }else{
            $b = 50*$jmlh;
            echo $b;
          } ;?>,
          width: '100%',
          colors: ['#1b9e77', '#d95f02', '#7570b3'],
          bar: { groupWidth: "80%" }
        };

        var chart = new google.charts.Bar(document.getElementById('chart_div2'));

        chart.draw(data, google.charts.Bar.convertOptions(options));

      }

      function drawChart3() {
        var data = google.visualization.arrayToDataTable([
          ['Dp', '2021','2020'],
          <?php 
            foreach ($get_chart_sales_growth_3 as $a) {
                echo "['$a->branch_name', $a->tot1,$a->tot2],";
            }
            ?>
            ]);

        var options = {
          chart: {
            title: '<?php echo $bulan3 ;?>',
            subtitle: '',
          },
          bars: 'horizontal', // Required for Material Bar Charts.
          hAxis: {format: 'decimal'},
          height: <?php if ($sub == '') {
            $a = 500*$jmlh;
            echo $a;
          }else{
            $b = 50*$jmlh;
            echo $b;
          } ;?>,
          width: '100%',
          colors: ['#1b9e77', '#d95f02', '#7570b3'],
          bar: { groupWidth: "80%" }
        };

        var chart = new google.charts.Bar(document.getElementById('chart_div3'));

        chart.draw(data, google.charts.Bar.convertOptions(options));

      }

  </script>
</head>
<body>

<!-- ====================================== bulan sekarang ======================================== -->

<div class="row">
  <div class="col-xl-12 col-md-12">
    <div class="card latest-update-card">
      <div class="card-header">
        <h5>Sales Growth YTD</h5>
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
  <div id="chart_div"></div>
  <br/>
    <div id="btn-group">
      <button class="button button-blue" id="none">No Format</button>
      <button class="button button-blue" id="scientific">Scientific Notation</button>
      <button class="button button-blue" id="decimal">Decimal</button>
      <button class="button button-blue" id="short">Short</button>
    </div>
  </div>
  </div>
  </div>

  <!-- ====================================== 1 bulan sebelumnya ======================================== -->

  <div class="col-xl-6 col-md-12">
    <div class="card latest-update-card">
      <div class="card-header">
        <h5>Sales Growth YTD</h5>
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
  <div id="chart_div3"></div>    
  </div>
  </div>
  </div>

 <!-- ====================================== 2 bulan sebelumnya ======================================== -->

  <div class="col-xl-6 col-md-12">
    <div class="card latest-update-card">
      <div class="card-header">
        <h5>Sales Growth YTD</h5>
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
  <div id="chart_div2"></div>    
  </div>
  </div>
  </div>

</body>
</html>