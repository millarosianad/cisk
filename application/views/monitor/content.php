
<div class="az-content-body pd-lg-l-40 d-flex flex-column">
  <div class="az-content-breadcrumb">
    <span>UPLOAD DATA TEXT</span>
  </div>

  <div class="az-content-label mg-b-5 mt-3"><?= $title ?></div>
  <p class="mg-b-10"></p>

  <!-- <div class="col-md-13">
    <a href="<?= base_url().'monitor/update_data' ?>" class="btn btn-warning"><i class="fas fa-cog"></i> update</a>
    <a href="<?= base_url().'monitor/email/'.$signature ?>" class="btn btn-danger"><i class="far fa-envelope"></i> Send to Email</a>
    <a href="<?= base_url().'monitor/export' ?>" class="btn btn-primary"><i class="far fa-save"></i> Save Report</a>
  </div> -->
  <br>

  <div class="az-dashboard-nav">
    <nav class="nav">
      <a class="nav-link" href="<?= base_url().'monitor/update_data' ?>"><i class="fas fa-cog"></i> Update Data</a>
      <a class="nav-link" href="<?= base_url().'monitor/export' ?>"><i class="far fa-save"></i> Save Report</a>
      <a class="nav-link" href="<?= base_url().'monitor/email/'.$signature ?>"><i class="far fa-envelope"></i> Send to Email</a>
      <a class="nav-link" href="<?= base_url().'monitor/manage_email' ?>"><i class="fas fa-wrench"></i> Manage Email</a>
      <a class="nav-link" href="<?= base_url().'monitor/upload_data_text' ?>"><i class="fas fa-wrench"></i> Upload Data Text</a>    
    </nav>
    <nav class="nav">
      <a class="nav-link" data-toggle="tab" href="#">Created At : <?= $get_dashboard_monitor->row()->created_at ?></a>
    </nav>
  </div>

  <div class="table-responsive mt-3">
    <table id="summary" class="table table-hover table-striped mg-b-0">
      <thead>
        <tr>
          <th class="col-md-1">Tahun</th>
          <th class="col-md-1">Bulan</th>
          <th class="col-md-2">D1</th>
          <th class="col-md-2">D2-Exclude-RTD</th>
          <th class="col-md-2">RTD</th>
          <th class="col-md-3">TOTAL</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $no = 1;
          foreach ($get_dashboard_monitor->result() as $a) : 
        ?>
        <tr>
          <th><font size="2px"><?= $a->tahun ?></th>
          <td><font size="2px"><?= $a->bulan ?></td>
          <td><font size="2px"><?= number_format($a->D1) ?></td>
          <td><font size="2px"><?= number_format($a->D2) ?></td>
          <td><font size="2px"><?= number_format($a->RTD) ?></td>
          <td><font size="2px"><?= number_format($a->TOTAL) ?></td>
        </tr>
        <?php endforeach; ?> 
      </tbody>
    </table>
  </div>

      <!-- </div>
    </div>
  </div> -->


    <script>
      $(document).ready(function () {
        $("#summary").DataTable({
            "pageLength": 7,
            "aLengthMenu": [
                [1, 2, -1],
                [1, 2, "All"]
            ],
            "order": [[6, 'desc']]
        });
      });
    </script>
