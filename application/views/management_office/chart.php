<div class="container">
    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>
</div>

<hr>


<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" style="background-color: #fff;" id="headingChart">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseChart" aria-expanded="true" aria-controls="collapseOne"><font color="black">Closing Bulan <?= $get_bulan_sekarang; ?> <i>click here</i></font>
                            </button>
                        </h5>
                    </div>

                    <div id="collapseChart" class="collapse" aria-labelledby="headingChart" data-parent="#accordionExample" style="width:200%; overflow:hidden;">
                        <div class="card-body">
                            <div class="mt-3" id="donutchart" style="width: 150%; height: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>






<!-- <div class="container mt-3">
    <div class="row">
        <div class="col-md-12 mt-3">            
            <h6>Closing Bulan <?= $get_bulan_sekarang; ?></h6>
        </div>        
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">     
            <div class="card cold-md-12">
              <div class="mt-3" id="donutchart" style="width: 80%; height: 100%;"></div>
            </div>
        </div>        
    </div>
</div> -->


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