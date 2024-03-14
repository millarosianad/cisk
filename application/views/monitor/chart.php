<style type="text/css">
  /* .chartBox{
      width: 450px;
      height: 290px;
  } */
</style>

    <div class="az-content az-content-dashboard">
      <div class="container">
        <div class="az-content-body">
          
          <?php 
            // $this->load->view('monitor/chart_1'); 
            // $this->load->view('monitor/chart_2'); 
          ?>


          <div class="row row-sm mg-b-20">
            <div class="col-lg-6 ht-lg-90p">
              <div class="card card-dashboard-one">
                <div class="card-header mt-3">
                  <div>
                    <h6 class="card-title">Sales D1</h6>
                    <p class="card-text"></p>
                  </div>
                  <div class="btn-group">
                    <button class="btn active">2023</button>
                    <button class="btn">2022</button>
                    <button class="btn">2021</button>
                    <button class="btn">2020</button>
                    <button class="btn">2019</button>
                    <button class="btn">2018</button>
                  </div>
                </div><!-- card-header -->
                <div class="card-body">
                  
                  <div class="col-md-12">
                    <?php 

                      $bulan = array();
                      $d1 = array();
                      $d2 = array();
                      $rtd = array();
                      $total = array();

                      foreach ($get_dashboard_monitor->result_array() as $key ) {
                          $bulan[] = $key['bulan'];
                          $d1[] = $key['D1'];
                          $d2[] = $key['D2'];
                          $rtd[] = $key['RTD'];
                          $total[] = $key['TOTAL'];
                      }
                    ?>

                    <div class="flot-chart">
                      <canvas id="myChart"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-6 ht-lg-90p">
              <div class="card card-dashboard-one">
                <div class="card-header mt-3">
                  <div>
                    <h6 class="card-title">Sales D2</h6>
                    <p class="card-text"></p>
                  </div>
                  <div class="btn-group">
                    <button class="btn active">2023</button>
                    <button class="btn">2022</button>
                    <button class="btn">2021</button>
                    <button class="btn">2020</button>
                    <button class="btn">2019</button>
                    <button class="btn">2018</button>
                  </div>
                </div><!-- card-header -->
                <div class="card-body">
                  <div class="flot-chart">
                    <canvas id="myChart2"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-6 ht-lg-90p">
              <div class="card card-dashboard-one">
                <div class="card-header mt-3">
                  <div>
                    <h6 class="card-title">Sales RTD</h6>
                    <p class="card-text"></p>
                  </div>
                  <div class="btn-group">
                    <button class="btn active">2023</button>
                    <button class="btn">2022</button>
                    <button class="btn">2021</button>
                    <button class="btn">2020</button>
                    <button class="btn">2019</button>
                    <button class="btn">2018</button>
                  </div>
                </div><!-- card-header -->
                <div class="card-body">
                  <div class="flot-chart">
                    <canvas id="myChart3"></canvas>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <div class="row row-sm mg-b-20">
            
          </div>

          <div class="row row-sm mg-b-20">
            
          </div>
            
        </div>
          <?php 
            // $this->load->view('monitor/chart_4');
            // $this->load->view('monitor/chart_5');
          ?>
        </div>
      </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const d1 = <?php echo json_encode($d1); ?>;
const bulan = <?php echo json_encode($bulan); ?>;

// setup block
const data = {
labels: bulan,
    datasets: [{
    label: 'omzet D1',
    data: d1,
    borderWidth: 1,
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(255, 205, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(201, 203, 207, 0.2)'
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
      'rgb(54, 162, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)'
    ],
}]};

// config block

const config = {
    type: 'bar',
    data,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
};

// render block
const myChart = new Chart(
    document.getElementById('myChart'),
    config
);
 
</script>

<script>
const d2 = <?php echo json_encode($d2); ?>;
const bulan_d2 = <?php echo json_encode($bulan); ?>;

// setup block
const data_d2 = {
labels: bulan_d2,
    datasets: [{
    label: 'omzet D2 exclude rtd',
    data: d2,
    borderWidth: 1,
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(255, 205, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(201, 203, 207, 0.2)'
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
      'rgb(54, 162, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)'
    ],
}]};

// config block

const config_d2 = {
    type: 'bar',
    data: data_d2,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
};

// render block
const myChart2 = new Chart(
    document.getElementById('myChart2'),
    config_d2
);
 
</script>

<script>
const rtd = <?php echo json_encode($rtd); ?>;

// setup block
const data_rtd = {
labels: bulan,
    datasets: [{
    label: 'omzet RTD',
    data: rtd,
    borderWidth: 1,
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(255, 205, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(201, 203, 207, 0.2)'
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
      'rgb(54, 162, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)'
    ],
}]};

// config block

const config_rtd = {
    type: 'bar',
    data: data_rtd,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
};

// render block
const myChart3 = new Chart(
    document.getElementById('myChart3'),
    config_rtd
);
</script>