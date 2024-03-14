    <div class="az-content az-content-dashboard">
      <div class="container">
        <div class="az-content-body">
          <div class="row row-sm mg-b-20">
            <div class="col-lg-6 ht-lg-90p">
              <div class="card card-dashboard-one">
                <div class="card-header mt-3">
                  <div>
                    <h6 class="card-title">Sales D1 by Year</h6>
                    <p class="card-text"></p>
                  </div>
                  <div class="btn-group">
                    <a href="<?= base_url() ?>monitor/dashboard/2023" class="btn <?php echo $this->uri->segment(3) == 2023  ? 'active' : '<b>' ?>">2023</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2022" class="btn <?php echo $this->uri->segment(3) == 2022  ? 'active' : '<b>' ?>">2022</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2021" class="btn <?php echo $this->uri->segment(3) == 2021  ? 'active' : '<b>' ?>">2021</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2020" class="btn <?php echo $this->uri->segment(3) == 2020  ? 'active' : '<b>' ?>">2020</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2019" class="btn <?php echo $this->uri->segment(3) == 2019  ? 'active' : '<b>' ?>">2019</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2018" class="btn <?php echo $this->uri->segment(3) == 2018  ? 'active' : '<b>' ?>">2018</a>                    
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
                      $x = $this->model_monitor->get_dashboard_monitor_custom($this->uri->segment('3'));
                      // var_dump($x);
                      foreach ($x->result_array() as $key ) {
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
                    <h6 class="card-title">Sales D2 by Year</h6>
                    <p class="card-text"></p>
                  </div>
                  <div class="btn-group">
                    <a href="<?= base_url() ?>monitor/dashboard/2023" class="btn <?php echo $this->uri->segment(3) == 2023  ? 'active' : '<b>' ?>">2023</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2022" class="btn <?php echo $this->uri->segment(3) == 2022  ? 'active' : '<b>' ?>">2022</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2021" class="btn <?php echo $this->uri->segment(3) == 2021  ? 'active' : '<b>' ?>">2021</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2020" class="btn <?php echo $this->uri->segment(3) == 2020  ? 'active' : '<b>' ?>">2020</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2019" class="btn <?php echo $this->uri->segment(3) == 2019  ? 'active' : '<b>' ?>">2019</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2018" class="btn <?php echo $this->uri->segment(3) == 2018  ? 'active' : '<b>' ?>">2018</a>                    
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
                    <h6 class="card-title">Sales RTD by Year</h6>
                    <p class="card-text"></p>
                  </div>
                  <div class="btn-group">
                    <a href="<?= base_url() ?>monitor/dashboard/2023" class="btn <?php echo $this->uri->segment(3) == 2023  ? 'active' : '<b>' ?>">2023</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2022" class="btn <?php echo $this->uri->segment(3) == 2022  ? 'active' : '<b>' ?>">2022</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2021" class="btn <?php echo $this->uri->segment(3) == 2021  ? 'active' : '<b>' ?>">2021</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2020" class="btn <?php echo $this->uri->segment(3) == 2020  ? 'active' : '<b>' ?>">2020</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2019" class="btn <?php echo $this->uri->segment(3) == 2019  ? 'active' : '<b>' ?>">2019</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2018" class="btn <?php echo $this->uri->segment(3) == 2018  ? 'active' : '<b>' ?>">2018</a>                    
                  </div>
                </div><!-- card-header -->
                <div class="card-body">
                  <div class="flot-chart">
                    <canvas id="myChart3"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-6 ht-lg-90p">
              <div class="card card-dashboard-one">
                <div class="card-header mt-3">
                  <div>
                    <h6 class="card-title">Total by Year</h6>
                    <p class="card-text"></p>
                  </div>
                  <div class="btn-group">
                    <a href="<?= base_url() ?>monitor/dashboard/2023" class="btn <?php echo $this->uri->segment(3) == 2023  ? 'active' : '<b>' ?>">2023</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2022" class="btn <?php echo $this->uri->segment(3) == 2022  ? 'active' : '<b>' ?>">2022</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2021" class="btn <?php echo $this->uri->segment(3) == 2021  ? 'active' : '<b>' ?>">2021</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2020" class="btn <?php echo $this->uri->segment(3) == 2020  ? 'active' : '<b>' ?>">2020</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2019" class="btn <?php echo $this->uri->segment(3) == 2019  ? 'active' : '<b>' ?>">2019</a>
                    <a href="<?= base_url() ?>monitor/dashboard/2018" class="btn <?php echo $this->uri->segment(3) == 2018  ? 'active' : '<b>' ?>">2018</a>                    
                  </div>
                </div><!-- card-header -->
                <div class="card-body">
                  <div class="flot-chart">
                    <canvas id="myChart4"></canvas>
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

<script>
const total = <?php echo json_encode($total); ?>;

// setup block
const data_total = {
labels: bulan,
    datasets: [{
    label: 'omzet total',
    data: total,
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

const config_total = {
    type: 'bar',
    data: data_total,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
};

// render block
const myChart4 = new Chart(
    document.getElementById('myChart4'),
    config_total);
</script>