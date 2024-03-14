<!doctype html>
<html>
    <head>        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View All Omzet</title>
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

    <?php 
    
    foreach($kalender_data_jtg as $data) 
    {
        $data_kalender[] = array($data->nama_comp, (int)$data->b7);
    }
    $data_kalender_z = json_encode($data_kalender);

    foreach($kalender_data_jts as $data) 
    {
        $data_kalender_jts[] = array($data->nama_comp, (int)$data->b7);
    }
    $data_kalender_jts = json_encode($data_kalender_jts);

    foreach($kalender_data_diy as $data) 
    {
        $data_kalender_diy[] = array($data->nama_comp, (int)$data->b7);
    }
    $data_kalender_diy = json_encode($data_kalender_diy);

    foreach($kalender_data_jtm as $data) 
    {
        $data_kalender_jtm[] = array($data->nama_comp, (int)$data->b7);
    }
    $data_kalender_jtm = json_encode($data_kalender_jtm);

    foreach($kalender_data_jkt as $data) 
    {
        $data_kalender_jkt[] = array($data->nama_comp, (int)$data->b7);
    }
    $data_kalender_jkt = json_encode($data_kalender_jkt);

    foreach($kalender_data_jbr as $data) 
    {
        $data_kalender_jbr[] = array($data->nama_comp, (int)$data->b7);
    }
    $data_kalender_jbr = json_encode($data_kalender_jbr);

    foreach($kalender_data_jbl as $data) 
    {
        $data_kalender_jbl[] = array($data->nama_comp, (int)$data->b7);
    }
    $data_kalender_jbl = json_encode($data_kalender_jbl);

    foreach($kalender_data_non as $data) 
    {
        $data_kalender_non[] = array($data->nama_comp, (int)$data->b7);
    }
    $data_kalender_non = json_encode($data_kalender_non);

    ?>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawBasic_jtg);
    google.charts.setOnLoadCallback(drawBasic_jts);
    google.charts.setOnLoadCallback(drawBasic_diy);
    google.charts.setOnLoadCallback(drawBasic_jtm);
    google.charts.setOnLoadCallback(drawBasic_jkt);
    google.charts.setOnLoadCallback(drawBasic_jbr);
    google.charts.setOnLoadCallback(drawBasic_jbl);
    google.charts.setOnLoadCallback(drawBasic_non);

    function drawBasic_jtg() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', '');
      data.addColumn('number', 'Tanggal Transaksi');
      data.addRows(<?php echo $data_kalender_z; ?>);
      var options = {
        title: "PT Javas Tripta Gemala",
        width: '100%',
        bar: {groupWidth: "60%"},
        legend: { position: "none" },
        hAxis: {
          title: 'Tanggal',
          axisFontSize : 0,
          gridlines: { count: 18 },
          minValue: 0,
          viewWindow: {
              min: 0,
              max: 31
          }
        },
        vAxis: {
          title: 'Sub Branch',
        }
      };
      var chart = new google.visualization.BarChart(document.getElementById("chart_div_jtg"));
      chart.draw(data, options);
    }

    function drawBasic_jts() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', '');
      data.addColumn('number', 'Tanggal Transaksi');
      data.addRows(<?php echo $data_kalender_jts; ?>);
      var options = {
        title: "PT Javas Tripta Sejahtera",
        width: '100%',
        bar: {groupWidth: "60%"},
        legend: { position: "none" },
        hAxis: {
          title: 'Tanggal',
          axisFontSize : 0,
          gridlines: { count: 18 },
          minValue: 0,
          viewWindow: {
              min: 0,
              max: 31
          }
        },
        vAxis: {
          title: 'Sub Branch',
        }
      };
      var chart = new google.visualization.BarChart(document.getElementById("chart_div_jts"));
      chart.draw(data, options);
    }

    function drawBasic_diy() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', '');
      data.addColumn('number', 'Tanggal Transaksi');
      data.addRows(<?php echo $data_kalender_diy; ?>);
      var options = {
        title: "PT Duta Intra Yasa",
        width: '100%',
        bar: {groupWidth: "60%"},
        legend: { position: "none" },
        hAxis: {
          title: 'Tanggal',
          axisFontSize : 0,
          gridlines: { count: 18 },
          minValue: 0,
          viewWindow: {
              min: 0,
              max: 31
          }
        },
        vAxis: {
          title: 'Sub Branch',
        }
      };
      var chart = new google.visualization.BarChart(document.getElementById("chart_div_diy"));
      chart.draw(data, options);
    }

    function drawBasic_jtm() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', '');
      data.addColumn('number', 'Tanggal Transaksi');
      data.addRows(<?php echo $data_kalender_jtm; ?>);
      var options = {
        title: "PT Javas Tripta Mandala",
        width: '100%',
        bar: {groupWidth: "60%"},
        legend: { position: "none" },
        hAxis: {
          title: 'Tanggal',
          axisFontSize : 0,
          gridlines: { count: 18 },
          minValue: 0,
          viewWindow: {
              min: 0,
              max: 31
          }
        },
        vAxis: {
          title: 'Sub Branch',
        }
      };
      var chart = new google.visualization.BarChart(document.getElementById("chart_div_jtm"));
      chart.draw(data, options);
    }

    function drawBasic_jkt() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', '');
      data.addColumn('number', 'Tanggal Transaksi');
      data.addRows(<?php echo $data_kalender_jkt; ?>);
      var options = {
        title: "PT Javas Karya Tripta",
        width: '100%',
        bar: {groupWidth: "60%"},
        legend: { position: "none" },
        hAxis: {
          title: 'Tanggal',
          axisFontSize : 0,
          gridlines: { count: 18 },
          minValue: 0,
          viewWindow: {
              min: 0,
              max: 31
          }
        },
        vAxis: {
          title: 'Sub Branch',
        }
      };
      var chart = new google.visualization.BarChart(document.getElementById("chart_div_jkt"));
      chart.draw(data, options);
    }

    function drawBasic_jbr() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', '');
      data.addColumn('number', 'Tanggal Transaksi');
      data.addRows(<?php echo $data_kalender_jbr; ?>);
      var options = {
        title: "PT Jaya Bakti Raharja",
        width: '100%',
        bar: {groupWidth: "60%"},
        legend: { position: "none" },
        hAxis: {
          title: 'Tanggal',
          axisFontSize : 0,
          gridlines: { count: 18 },
          minValue: 0,
          viewWindow: {
              min: 0,
              max: 31
          }
        },
        vAxis: {
          title: 'Sub Branch',
        }
      };
      var chart = new google.visualization.BarChart(document.getElementById("chart_div_jbr"));
      chart.draw(data, options);
    }

    function drawBasic_jbl() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', '');
      data.addColumn('number', 'Tanggal Transaksi');
      data.addRows(<?php echo $data_kalender_jbl; ?>);
      var options = {
        title: "PT Javas Bali Lestari ",
        width: '100%',
        bar: {groupWidth: "30%"},
        legend: { position: "none" },
        hAxis: {
          title: 'Tanggal',
          axisFontSize : 0,
          gridlines: { count: 18 },
          minValue: 0,
          viewWindow: {
              min: 0,
              max: 31
          }
        },
        vAxis: {
          title: 'Sub Branch',
        }
      };
      var chart = new google.visualization.BarChart(document.getElementById("chart_div_jbl"));
      chart.draw(data, options);
    }

    function drawBasic_non() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', '');
      data.addColumn('number', 'Tanggal Transaksi');
      data.addRows(<?php echo $data_kalender_non; ?>);
      var options = {
        title: "Non Afiliasi",
        width: '100%',
        bar: {groupWidth: "60%"},
        legend: { position: "none" },
        hAxis: {
          title: 'Tanggal',
          axisFontSize : 0,
          gridlines: { count: 18 },
          minValue: 0,
          viewWindow: {
              min: 0,
              max: 31
          }
        },
        vAxis: {
          title: 'Sub Branch',
        }
      };
      var chart = new google.visualization.BarChart(document.getElementById("chart_div_non"));
      chart.draw(data, options);
    }

  </script>
  </head>
  <body>
</div><br><br><br><br><center><h3>Kalender Data</h3></center>
    <div class="row">        
        <div class="col-xs-6">
            <div id="chart_div_jtg" style="width: 110%; height: 400px;"></div>
        </div>
        <div class="col-xs-6">
            <div id="chart_div_jts" style="width: 110%; height: 400px;"></div>
        </div>
    </div>
    <div class="row">        
        <div class="col-xs-6">
            <div id="chart_div_diy" style="width: 110%; height: 400px;"></div>
        </div>
        <div class="col-xs-6">
            <div id="chart_div_jtm" style="width: 110%; height: 400px;"></div>
        </div>
    </div>  
    <div class="row">        
        <div class="col-xs-6">
            <div id="chart_div_jkt" style="width: 110%; height: 400px;"></div>
        </div>
        <div class="col-xs-6">
            <div id="chart_div_jbr" style="width: 110%; height: 400px;"></div>
        </div>
    </div>
    <div class="row">        
        <div class="col-xs-6">
            <div id="chart_div_jbl" style="width: 110%; height: 400px;"></div>
        </div>
        <div class="col-xs-6">
            <div id="chart_div_non" style="width: 110%; height: 750px;"></div>
        </div>
    </div>        
  </body>
</html>