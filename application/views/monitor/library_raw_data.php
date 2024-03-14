
  <div class="az-content-label mg-b-5 mt-3"><?= $title ?></div>
  <p class="mg-b-10"></p>

  <!-- <div class="col-md-13">
    <a href="<?= base_url().'monitor/update_data' ?>" class="btn btn-warning"><i class="fas fa-cog"></i> update</a>
    <a href="<?= base_url().'monitor/email/'.$signature ?>" class="btn btn-danger"><i class="far fa-envelope"></i> Send to Email</a>
    <a href="<?= base_url().'monitor/export' ?>" class="btn btn-primary"><i class="far fa-save"></i> Save Report</a>
  </div> -->
  <br>

  <div class="table-responsive mt-3">
    <table id="library" class="table table-hover mg-b-0">
      <thead>
        <tr>
          <th class="col-md-1">No</th>
          <th class="col-md-3">File</th>
          <th class="col-md-4">Keterangan</th>
          <th class="col-md-3">Created At</th>
          <th class="col-md-3">download here</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $no = 1;
          foreach ($get_library_raw_data->result() as $a) : 
        ?>
        <tr>
          <th><?= $no++ ?></th>
          <td><?= $a->filename; ?></td>
          <td><?= $a->keterangan; ?></td>
          <td><?= $a->created_at; ?></td>
          <td><a href="<?= $a->link; ?>" class="btn btn-warning">download</a></td>
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
        $("#library").DataTable({
            "pageLength": 7,
            "aLengthMenu": [
                [1, 2, -1],
                [1, 2, "All"]
            ],
            "order": [[6, 'desc']]
        });
      });
    </script>
