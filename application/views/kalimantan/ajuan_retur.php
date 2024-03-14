
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
      <div class="az-dashboard-nav mt-5">
        <nav class="nav">
          <a class="nav-link" href="<?= base_url() ?>kalimantan/update_retur"><i class="fas fa-cog"></i> Update Data (<?= $get_ajuan_retur_created_at ?>)</a>
          <a class="nav-link" href="<?= base_url() ?>kalimantan/raw_data_retur"><i class="far fa-save"></i> Save Report</a>
        </nav>
      </div>
    </div>

    <div class="row">

  <div class="az-dashboard-nav mt-2">
    <nav class="nav">
    <strong>Status : Pending DP</strong>
    </nav>
    
  </div>

  <div class="table-responsive mt-3">
    <table id="library" class="table table-hover mg-b-0">
      <thead>
        <tr>
          <th style="background-color: darkslategray;" class="text-center" class="col-md-1"><font color="white">No</th>
          <th style="background-color: darkslategray;" class="text-center" class="col-auto"><font color="white">branch</th>
          <th style="background-color: darkslategray;" class="text-center" class="col-auto"><font color="white">subbranch</th>
          <th style="background-color: darkslategray;" class="text-center" class="col-auto"><font color="white">principal</th>
          <th style="background-color: darkslategray;" class="text-center" class="col-auto"><font color="white">no_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center" class="col-auto"><font color="white">tgl_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center" class="col-auto"><font color="white">value</th>
        </tr>
      </thead>
      <?php  
          if ($get_retur_pending_dp) {  ?>
          <tbody>
              <?php 
                $no = 1;
                foreach ($get_retur_pending_dp->result() as $a) : 
              ?>
              <tr>
                <th><?= $no++ ?></th>
                <td><?= $a->branch_name; ?></td>
                <td><?= $a->nama_comp; ?></td>
                <td><?= $a->principal; ?></td>
                <td><?= $a->no_pengajuan; ?></td>
                <td><?= $a->tanggal_pengajuan; ?></td>
                <td><?= $a->value_perkiraan; ?></td>
              </tr>
              <?php endforeach; ?> 
          </tbody>
          <?php 
          }
        ?>
    </table>
  </div>

  <div class="az-dashboard-nav mt-5">
    <nav class="nav">
    <strong>Status : Proses MPM</strong>
    </nav>
  </div>

  <div class="table-responsive mt-3">
    <table id="proses_mpm" class="table table-hover mg-b-0">
      <thead class="table-success">
        <tr>
          <th style="background-color: darkslategray;" class="text-center"  class="col-md-1"><font color="white">No</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">branch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">subbranch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">principal</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">no_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">tgl_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">value</th>
        </tr>
      </thead>
      <?php  
          if ($get_retur_proses_mpm) {  ?>
          <tbody>
              <?php 
                $no = 1;
                foreach ($get_retur_proses_mpm->result() as $a) : 
              ?>
              <tr>
                <th><?= $no++ ?></th>
                <td><?= $a->branch_name; ?></td>
                <td><?= $a->nama_comp; ?></td>
                <td><?= $a->principal; ?></td>
                <td><?= $a->no_pengajuan; ?></td>
                <td><?= $a->tanggal_pengajuan; ?></td>
                <td><?= $a->value_perkiraan; ?></td>
              </tr>
              <?php endforeach; ?> 
          </tbody>
          <?php 
          }
        ?>
    </table>
  </div>

  <div class="az-dashboard-nav mt-5">
    <nav class="nav">
    <strong>Status : Proses DP</strong>
    </nav>
  </div>

  <div class="table-responsive mt-3">
    <table id="proses_dp" class="table table-hover mg-b-0">
      <thead class="table-success">
        <tr>
          <th style="background-color: darkslategray;" class="text-center"  class="col-md-1"><font color="white">No</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">branch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">subbranch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">principal</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">no_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">tgl_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">value</th>
        </tr>
      </thead>
        <?php  
          if ($get_retur_proses_dp) {  ?>
          <tbody>
              <?php 
                $no = 1;
                foreach ($get_retur_proses_dp->result() as $a) : 
              ?>
              <tr>
                <th><?= $no++ ?></th>
                <td><?= $a->branch_name; ?></td>
                <td><?= $a->nama_comp; ?></td>
                <td><?= $a->principal; ?></td>
                <td><?= $a->no_pengajuan; ?></td>
                <td><?= $a->tanggal_pengajuan; ?></td>
                <td><?= $a->value_perkiraan; ?></td>
              </tr>
              <?php endforeach; ?> 
          </tbody>
          <?php 
          }
        ?>
    </table>
  </div>

  <div class="az-dashboard-nav mt-5">
    <nav class="nav">
    <strong>Status : Pending Principal</strong>
    </nav>
  </div>

  <div class="table-responsive mt-3">
    <table id="pending_principal" class="table table-hover table-borderless mg-b-0">
      <thead class="table-success">
        <tr>
          <th style="background-color: darkslategray;" class="text-center"  class="col-sm-1"><font color="white">No</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">branch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">subbranch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">principal</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">no_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">tgl_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">value</th>
        </tr>
      </thead>
      <?php  
          if ($get_retur_pending_principal) {  ?>
          <tbody>
              <?php 
                $no = 1;
                foreach ($get_retur_pending_principal->result() as $a) : 
              ?>
              <tr>
                <th><?= $no++ ?></th>
                <td><?= $a->branch_name; ?></td>
                <td><?= $a->nama_comp; ?></td>
                <td><?= $a->principal; ?></td>
                <td><?= $a->no_pengajuan; ?></td>
                <td><?= $a->tanggal_pengajuan; ?></td>
                <td><?= $a->value_perkiraan; ?></td>
              </tr>
              <?php endforeach; ?> 
          </tbody>
          <?php 
          }
        ?>
    </table>
  </div>

  <div class="az-dashboard-nav mt-5">
    <nav class="nav">
    <strong>Status : Proses Kirim Barang</strong>
    </nav>
  </div>

  <div class="table-responsive mt-3">
    <table id="proses_kirim_barang" class="table table-hover table-borderless mg-b-0">
      <thead class="table-success">
        <tr>
          <th style="background-color: darkslategray;" class="text-center"  class="col-sm-1"><font color="white">No</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">branch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">subbranch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">principal</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">no_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">tgl_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">value</th>
        </tr>
      </thead>
      <?php  
          if ($get_retur_proses_kirim_barang) {  ?>
          <tbody>
              <?php 
                $no = 1;
                foreach ($get_retur_proses_kirim_barang->result() as $a) : 
              ?>
              <tr>
                <th><?= $no++ ?></th>
                <td><?= $a->branch_name; ?></td>
                <td><?= $a->nama_comp; ?></td>
                <td><?= $a->principal; ?></td>
                <td><?= $a->no_pengajuan; ?></td>
                <td><?= $a->tanggal_pengajuan; ?></td>
                <td><?= $a->value_perkiraan; ?></td>
              </tr>
              <?php endforeach; ?> 
          </tbody>
          <?php 
          }
        ?>
    </table>
  </div>

  <div class="az-dashboard-nav mt-5">
    <nav class="nav">
    <strong>Status : Proses Pemusnahan</strong>
    </nav>
  </div>

  <div class="table-responsive mt-3">
    <table id="proses_pemusnahan" class="table table-hover table-borderless mg-b-0">
      <thead class="table-success">
        <tr>
          <th style="background-color: darkslategray;" class="text-center"  class="col-sm-1"><font color="white">No</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">branch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">subbranch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">principal</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">no_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">tgl_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">value</th>
        </tr>
      </thead>
      <?php  
          if ($get_retur_proses_pemusnahan) {  ?>
          <tbody>
              <?php 
                $no = 1;
                foreach ($get_retur_proses_pemusnahan->result() as $a) : 
              ?>
              <tr>
                <th><?= $no++ ?></th>
                <td><?= $a->branch_name; ?></td>
                <td><?= $a->nama_comp; ?></td>
                <td><?= $a->principal; ?></td>
                <td><?= $a->no_pengajuan; ?></td>
                <td><?= $a->tanggal_pengajuan; ?></td>
                <td><?= $a->value_perkiraan; ?></td>
              </tr>
              <?php endforeach; ?> 
          </tbody>
          <?php 
          }
        ?>
    </table>
  </div>

  <div class="az-dashboard-nav mt-5">
    <nav class="nav">
    <strong>Status : Proses Principal Terima Barang</strong>
    </nav>
  </div>

  <div class="table-responsive mt-3">
    <table id="proses_principal_terima_barang" class="table table-hover table-borderless mg-b-0">
      <thead class="table-success">
        <tr>
          <th style="background-color: darkslategray;" class="text-center"  class="col-sm-1"><font color="white">No</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">branch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">subbranch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">principal</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">no_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">tgl_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">value</th>
        </tr>
      </thead>
      <?php  
          if ($get_retur_proses_principal_terima_barang) {  ?>
          <tbody>
              <?php 
                $no = 1;
                foreach ($get_retur_proses_principal_terima_barang->result() as $a) : 
              ?>
              <tr>
                <th><?= $no++ ?></th>
                <td><?= $a->branch_name; ?></td>
                <td><?= $a->nama_comp; ?></td>
                <td><?= $a->principal; ?></td>
                <td><?= $a->no_pengajuan; ?></td>
                <td><?= $a->tanggal_pengajuan; ?></td>
                <td><?= $a->value_perkiraan; ?></td>
              </tr>
              <?php endforeach; ?> 
          </tbody>
          <?php 
          }
        ?>
    </table>
  </div>

  <div class="az-dashboard-nav mt-5">
    <nav class="nav">
    <strong>Status : Barang diterima Principal</strong>
    </nav>
  </div>

  <div class="table-responsive mt-3">
    <table id="barang_diterima_principal" class="table table-hover table-borderless mg-b-0">
      <thead class="table-success">
        <tr>
          <th style="background-color: darkslategray;" class="text-center"  class="col-sm-1"><font color="white">No</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">branch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">subbranch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">principal</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">no_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">tgl_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">value</th>
        </tr>
      </thead>
      <?php  
          if ($get_retur_barang_diterima_principal) {  ?>
          <tbody>
              <?php 
                $no = 1;
                foreach ($get_retur_barang_diterima_principal->result() as $a) : 
              ?>
              <tr>
                <th><?= $no++ ?></th>
                <td><?= $a->branch_name; ?></td>
                <td><?= $a->nama_comp; ?></td>
                <td><?= $a->principal; ?></td>
                <td><?= $a->no_pengajuan; ?></td>
                <td><?= $a->tanggal_pengajuan; ?></td>
                <td><?= $a->value_perkiraan; ?></td>
              </tr>
              <?php endforeach; ?> 
          </tbody>
          <?php 
          }
        ?>
    </table>
  </div>

  <div class="az-dashboard-nav mt-5">
    <nav class="nav">
    <strong>Status : Pemusnahan oleh DP</strong>
    </nav>
  </div>

  <div class="table-responsive mt-3">
    <table id="pemusnahan_oleh_dp" class="table table-hover table-borderless mg-b-0">
      <thead class="table-success">
        <tr>
          <th style="background-color: darkslategray;" class="text-center"  class="col-sm-1"><font color="white">No</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">branch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">subbranch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">principal</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">no_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">tgl_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">value</th>
        </tr>
      </thead>
      <?php  
          if ($get_retur_pemusnahan_oleh_dp) {  ?>
          <tbody>
              <?php 
                $no = 1;
                foreach ($get_retur_pemusnahan_oleh_dp->result() as $a) : 
              ?>
              <tr>
                <th><?= $no++ ?></th>
                <td><?= $a->branch_name; ?></td>
                <td><?= $a->nama_comp; ?></td>
                <td><?= $a->principal; ?></td>
                <td><?= $a->no_pengajuan; ?></td>
                <td><?= $a->tanggal_pengajuan; ?></td>
                <td><?= $a->value_perkiraan; ?></td>
              </tr>
              <?php endforeach; ?> 
          </tbody>
          <?php 
          }
        ?>
    </table>
  </div>

  <div class="az-dashboard-nav mt-5">
    <nav class="nav">
    <strong>Status : Lainnya</strong>
    </nav>
  </div>

  <div class="table-responsive mt-3">
    <table id="lainnya" class="table table-hover table-borderless mg-b-0">
      <thead class="table-success">
        <tr>
          <th style="background-color: darkslategray;" class="text-center"  class="col-sm-1"><font color="white">No</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">branch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">subbranch</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">principal</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">no_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">tgl_ajuan</th>
          <th style="background-color: darkslategray;" class="text-center"  class="col-auto"><font color="white">value</th>
        </tr>
      </thead>
      <?php  
          if ($get_retur_lainnya) {  ?>
          <tbody>
              <?php 
                $no = 1;
                foreach ($get_retur_lainnya->result() as $a) : 
              ?>
              <tr>
                <th><?= $no++ ?></th>
                <td><?= $a->branch_name; ?></td>
                <td><?= $a->nama_comp; ?></td>
                <td><?= $a->principal; ?></td>
                <td><?= $a->no_pengajuan; ?></td>
                <td><?= $a->tanggal_pengajuan; ?></td>
                <td><?= $a->value_perkiraan; ?></td>
              </tr>
              <?php endforeach; ?> 
          </tbody>
          <?php 
          }
        ?>
    </table>
  </div>

  </div>
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
        $("#proses_mpm").DataTable({
            "pageLength": 7,
            "aLengthMenu": [
                [1, 2, -1],
                [1, 2, "All"]
            ],
            "order": [[0, 'asc']]
        });
        $("#proses_dp").DataTable({
            "pageLength": 7,
            "aLengthMenu": [
                [1, 2, -1],
                [1, 2, "All"]
            ],
            "order": [[0, 'asc']]
        });
        $("#pending_principal").DataTable({
            "pageLength": 7,
            "aLengthMenu": [
                [1, 2, -1],
                [1, 2, "All"]
            ],
            "order": [[0, 'asc']]
        });
        $("#proses_kirim_barang").DataTable({
            "pageLength": 7,
            "aLengthMenu": [
                [1, 2, -1],
                [1, 2, "All"]
            ],
            "order": [[0, 'asc']]
        });
        $("#proses_pemusnahan").DataTable({
            "pageLength": 7,
            "aLengthMenu": [
                [1, 2, -1],
                [1, 2, "All"]
            ],
            "order": [[0, 'asc']]
        });
        $("#proses_principal_terima_barang").DataTable({
            "pageLength": 7,
            "aLengthMenu": [
                [1, 2, -1],
                [1, 2, "All"]
            ],
            "order": [[0, 'asc']]
        });
        $("#barang_diterima_principal").DataTable({
            "pageLength": 7,
            "aLengthMenu": [
                [1, 2, -1],
                [1, 2, "All"]
            ],
            "order": [[0, 'asc']]
        });
        $("#pemusnahan_oleh_dp").DataTable({
            "pageLength": 7,
            "aLengthMenu": [
                [1, 2, -1],
                [1, 2, "All"]
            ],
            "order": [[0, 'asc']]
        });
        $("#lainnya").DataTable({
            "pageLength": 7,
            "aLengthMenu": [
                [1, 2, -1],
                [1, 2, "All"]
            ],
            "order": [[0, 'asc']]
        });
      });
    </script>
