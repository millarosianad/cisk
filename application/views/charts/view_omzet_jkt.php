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

        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
        
        <script type="text/javascript">       
        $(document).ready(function() { 
        $("#supp").click(function(){
            $.ajax({
            url:"<?php echo base_url(); ?>omzet/buildgroup",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
        });            
        </script>

    <?php 
    $no = 1;
    foreach($omzets_deltomed as $omzet) : ?>                       
    <?php endforeach;

    foreach($omzets_us as $omzet_us) : ?>
    <?php endforeach;

    foreach($omzets_marguna as $omzet_marguna) : ?>
    <?php endforeach; 

    foreach($omzets_jaya_agung as $omzet_jaya_agung) : ?>
    <?php endforeach; ?>



    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart_deltomed);
    google.charts.setOnLoadCallback(drawChart_us);
    google.charts.setOnLoadCallback(drawChart_marguna);
    google.charts.setOnLoadCallback(drawChart_jaya_agung);

    function drawChart_deltomed() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('number', '');
      data.addColumn('number', 'Bulan');
      
      data.addRows([
        [{v:1, f:'b1'}, <?php echo $omzet->b1; ?>],
        [{v:2, f:'b2'}, <?php echo $omzet->b2; ?>],
        [{v:3, f:'b3'}, <?php echo $omzet->b3; ?>],
        [{v:4, f:'b4'}, <?php echo $omzet->b4; ?>],
        [{v:5, f:'b5'}, <?php echo $omzet->b5; ?>],
        [{v:6, f:'b6'}, <?php echo $omzet->b6; ?>],
        [{v:7, f:'b7'}, <?php echo $omzet->b7; ?>]
      ]);


      var options = {
        title: 'DELTOMED',
        height: 400,
        hAxis: {
          title: 'Bulan',
          axisFontSize : 0,
          gridlines: { count: 0 },
          minValue: 0
        },
        vAxis: {
            title: 'Omzet',
            gridlines: { count: 10 }
        },
        
        legend: 'none',
        trendlines: {
          0: {
            type: 'exponential',
          }
        },
         
        colors: ['#a52714'] 
      };

      // Instantiate and draw the chart.
      var chart = new google.visualization.ColumnChart(document.getElementById('myPieChart'));
      chart.draw(data, options);
    }

    function drawChart_us() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('number', '');
      data.addColumn('number', 'Bulan');
      
      data.addRows([
        [{v:1, f:'b1'}, <?php echo $omzet_us->b1; ?>],
        [{v:2, f:'b2'}, <?php echo $omzet_us->b2; ?>],
        [{v:3, f:'b3'}, <?php echo $omzet_us->b3; ?>],
        [{v:4, f:'b4'}, <?php echo $omzet_us->b4; ?>],
        [{v:5, f:'b5'}, <?php echo $omzet_us->b5; ?>],
        [{v:6, f:'b6'}, <?php echo $omzet_us->b6; ?>],
        [{v:7, f:'b7'}, <?php echo $omzet_us->b7; ?>]
      ]);


      var options = {
        title: 'ULTRASAKTI',
        height: 400,
        hAxis: {
          title: 'Bulan',
          axisFontSize : 0,
          gridlines: { count: 0 },
          minValue: 0
        },
        vAxis: {
            title: 'Omzet',
            gridlines: { count: 10 }
        },
        
        legend: 'none',
        trendlines: {
          0: {
            type: 'exponential',
          }
        },
         
        colors: ['#a52714'] 
      };

      // Instantiate and draw the chart.
      var chart = new google.visualization.ColumnChart(document.getElementById('myPieChart_us'));
      chart.draw(data, options);
    }

    function drawChart_marguna() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('number', '');
      data.addColumn('number', 'Bulan');
      
      data.addRows([
        [{v:1, f:'b1'}, <?php echo $omzet_marguna->b1; ?>],
        [{v:2, f:'b2'}, <?php echo $omzet_marguna->b2; ?>],
        [{v:3, f:'b3'}, <?php echo $omzet_marguna->b3; ?>],
        [{v:4, f:'b4'}, <?php echo $omzet_marguna->b4; ?>],
        [{v:5, f:'b5'}, <?php echo $omzet_marguna->b5; ?>],
        [{v:6, f:'b6'}, <?php echo $omzet_marguna->b6; ?>],
        [{v:7, f:'b7'}, <?php echo $omzet_marguna->b7; ?>]
      ]);


      var options = {
        title: 'MARGUNA',
        height: 400,
        hAxis: {
          title: 'Bulan',
          axisFontSize : 0,
          gridlines: { count: 0 },
          minValue: 0
        },
        vAxis: {
            title: 'Omzet',
            gridlines: { count: 10 }
        },
        
        legend: 'none',
        trendlines: {
          0: {
            type: 'exponential',
          }
        },
         
        colors: ['#a52714'] 
      };

      // Instantiate and draw the chart.
      var chart = new google.visualization.ColumnChart(document.getElementById('myPieChart_marguna'));
      chart.draw(data, options);
    }

    function drawChart_jaya_agung() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('number', '');
      data.addColumn('number', 'Bulan');
      
      data.addRows([
        [{v:1, f:'b1'}, <?php echo $omzet_jaya_agung->b1; ?>],
        [{v:2, f:'b2'}, <?php echo $omzet_jaya_agung->b2; ?>],
        [{v:3, f:'b3'}, <?php echo $omzet_jaya_agung->b3; ?>],
        [{v:4, f:'b4'}, <?php echo $omzet_jaya_agung->b4; ?>],
        [{v:5, f:'b5'}, <?php echo $omzet_jaya_agung->b5; ?>],
        [{v:6, f:'b6'}, <?php echo $omzet_jaya_agung->b6; ?>],
        [{v:7, f:'b7'}, <?php echo $omzet_jaya_agung->b7; ?>]
      ]);


      var options = {
        title: 'JAYA AGUNG',
        height: 400,
        hAxis: {
          title: 'Bulan',
          axisFontSize : 0,
          gridlines: { count: 0 },
          minValue: 0
        },
        vAxis: {
            title: 'Omzet',
            gridlines: { count: 10 }
        },
        
        legend: 'none',
        trendlines: {
          0: {
            type: 'exponential',
          }
        },
         
        colors: ['#a52714'] 
      };

      // Instantiate and draw the chart.
      var chart = new google.visualization.ColumnChart(document.getElementById('myPieChart_jaya_agung'));
      chart.draw(data, options);
    }

  </script>


    </head>
    <body>
</div>
<center><h3>PT Javas Karya Tripta</h3></center>
    <div class="row">        
        <div class="col-xs-6">
           
            <div id="myPieChart"/></div>
        </div>
        <div class="col-xs-6">
            
            <div id="myPieChart_us"/></div>
        </div>
    </div>

    <div class="row">        
        <div class="col-xs-6">
            
            <div id="myPieChart_marguna"/></div>
        </div>
        <div class="col-xs-6">
            
            <div id="myPieChart_jaya_agung"/></div>
        </div>
    </div>

    

     <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                "ordering": false,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>