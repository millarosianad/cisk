<!DOCTYPE html>
<html>
<head>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart3);

      <?php 
      $bln= date('m')-2;
       if ($bln == '1'){
           $bulan ='Januari' ;
       }elseif ($bln == '2'){
           $bulan ='Februari';
       }elseif ($bln == '3'){
           $bulan = 'Maret'  ;
       }elseif ($bln == '4'){
           $bulan ='April'   ;
       }elseif ($bln == '5'){
           $bulan ='Mei'     ;
       }elseif ($bln == '6'){
           $bulan ='Juni'    ;
       }elseif ($bln == '7'){
           $bulan ='Juli'    ;
       }elseif ($bln == '8'){
           $bulan ='Agustus' ;
       }elseif ($bln == '9'){
           $bulan ='September';
       }
       elseif ($bln == '10'){
           $bulan = 'Oktober' ;
       }elseif ($bln == '11'){
           $bulan = 'November';
       }elseif ($bln == '12'){
           $bulan = "Desember";
       }
       $sub = $jmlh_branch['sub'];
       $subx = explode(" ",$sub);
       $jmlh = count($subx);
      ?>

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
            title: '<?php echo $bulan ;?>',
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
          width: 400,
          colors: ['#1b9e77', '#d95f02', '#7570b3'],
          bar: { groupWidth: "80%" }
        };

        var chart = new google.charts.Bar(document.getElementById('chart_div3'));

        chart.draw(data, google.charts.Bar.convertOptions(options));

      }
  </script>
</head>
<body>
<div class="row">
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
</body>
</html>