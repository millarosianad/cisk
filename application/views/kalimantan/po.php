
<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
    td{
        font-size: 12px;
        /* text-align: center; */
    }
    th{
        font-size: 13px; 
    }

    .accordion {
        cursor: pointer;
        padding: 1px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.2s;
        /* border: 2px solid;
        border-radius: 25px; */
        border-top: 5px solid darkslategray;
        border-bottom: 5px solid darkslategray;
        border-left: 5px solid darkslategray;
        border-right: 5px solid darkslategray;
        border-radius: 14px;
        margin-top: 1rem;
        border-top: 1em solid darkslategray;

    }

    table{
        width: 10000px;
    }

</style>

</div>

<div class="container">

  <div class="row">
      <div class="col-md-12 az-content-label">
          <?= $title ?>
      </div>
  </div>

  <div class="row">
      <div class="col-md-12 az-dashboard-nav mt-4">
        <nav class="nav">
          <a class="nav-link" href="<?= base_url() ?>kalimantan/raw_data_po"><i class="far fa-save"></i> Save Report</a>
        </nav>
      </div>
  </div>

  <div class="row">

  <div class="az-dashboard-nav mt-2">
    <nav class="nav">
    <strong>Status : PO by Tanggal</strong>
    </nav>
  </div>

  <div class="table-responsive mt-3">
    <table id="library" class="table table-hover mg-b-0">
      <thead class="table-warning">
        <tr>
          <th style="background-color: darkslategray;" class="text-center" class="col-md-1"><font color="white">No</th>
          <th style="background-color: darkslategray;" class="text-center" class="col-auto"><font color="white">subbranch</th>
          <th style="background-color: darkslategray;" class="text-center" class="col-auto"><font color="white">principal</th>
          <th style="background-color: darkslategray;" class="text-center" class="col-auto"><font color="white">nopo</th>
          <th style="background-color: darkslategray;" class="text-center" class="col-auto"><font color="white">tglpo</th>
          <th style="background-color: darkslategray;" class="text-center" class="col-auto"><font color="white">value</th>
          <th style="background-color: darkslategray;" class="text-center" class="col-auto"><font color="white">status</th>
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
            "pageLength": 20,
            "aLengthMenu": [
                [1, 2, -1],
                [1, 2, "All"]
            ],
            "order": [[0, 'asc']]
        });
      });
    </script>
