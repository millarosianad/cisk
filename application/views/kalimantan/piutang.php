
<div class="az-content-body pd-lg-l-40 d-flex flex-column">
  <div class="az-content-breadcrumb">
    <span>Dashboard</span>
  </div>

  <div class="az-content-label mg-b-5 mt-3"><?= $title ?></div>

  <div class="az-dashboard-nav mt-5">
    <nav class="nav">
    <strong>Status : PO by Tanggal</strong>
    </nav>
    <nav class="nav">
      <a class="nav-link" href="<?= base_url() ?>kalimantan/raw_data_po"><i class="far fa-save"></i> Save Report</a>
    </nav>
  </div>

  <div class="table-responsive mt-3">
    <table id="library" class="table table-hover mg-b-0">
      <thead class="table-danger">
        <tr>
          <th class="col-md-1">No</th>
          <th class="col-auto">subbranch</th>
          <th class="col-auto">principal</th>
          <th class="col-auto">nopo</th>
          <th class="col-auto">tglpo</th>
          <th class="col-auto">value</th>
          <th class="col-auto">status</th>
        </tr>
      </thead>
      <?php  
          if ($get_po) {  ?>
          <tbody>
              <?php 
                $no = 1;
                foreach ($get_po->result() as $a) : 
              ?>
              <tr>
                <th><?= $no++ ?></th>
                <td><?= $a->nama_comp; ?></td>
                <td><?= $a->namasupp; ?></td>
                <td><?= $a->nopo; ?></td>
                <td><?= $a->tglpo; ?></td>
                <td><?= number_format($a->total) ?></td>
                <td>
                <?php 
                    if($a->status == '1')
                    {
                        echo "<strong><font color='red'>logistic approval</font>";
                    }elseif($a->status == '2'){
                        if ($a->open == '1') {
                            if ($a->nopo == null) {
                                echo "<strong><font color='blue'>Proses PO</font>";                                            
                            }else{
                                echo "<strong><font color='black'>Selesai</font>";        
                            }
                        }else{
                            echo "<strong><font color='orange'>finance approval</font>";
                        }
                        
                    }else{
                        echo "<strong><font color='red'>doi checking</font>";
                    }
                ?>
                </td>
              </tr>
              <?php endforeach; ?> 
          </tbody>
          <?php 
          }
        ?>
    </table>
  </div>


    <script>
      $(document).ready(function () {
        $("#library").DataTable({
            "pageLength": 7,
            "aLengthMenu": [
                [1, 2, -1],
                [1, 2, "All"]
            ],
            "order": [[0, 'asc']]
        });
      });
    </script>
