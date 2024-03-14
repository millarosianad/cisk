


<div class="az-content pd-y-20 pd-lg-y-30 pd-xl-y-40">
      <div class="container">
        <div class="az-content-left az-content-left-components">
          <div class="component-item">
                      
          </div>
          <div class="component-item mt-3">
            <label>Other Links</label>
            <nav class="nav flex-column">
              <a href="<?= base_url() ?>monitor/dashboard" class="nav-link">Dashboard</a>
            </nav>            
          </div>
        </div>
        
        
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
          <div class="az-content-breadcrumb">
            <span>Library Raw Data</span>
            <!-- <span>Summary</span> -->
          </div>
          <!-- <h2 class="az-content-title">Summary Sales MTI</h2> -->

<div class="az-content-label mg-b-5 mt-3"><?= $title ?></div>
          <p class="mg-b-20"></p>

          <?php echo form_open_multipart($url); ?>

          <div class="row">
            <div class="col-md-3">Email To :</div>
            <div class="col-md-7"><input type="text" name="email_to" class="form-control" value="<?= $get_email->row()->email_to; ?>"></div>
          </div>
          
          <div class="row mt-3">
            <div class="col-md-3">Email Cc :</div>
            <div class="col-md-7"><input type="text" name="email_cc" class="form-control" value="<?= $get_email->row()->email_cc; ?>"></div>
          </div>
          
          <div class="row mt-3">
            <div class="col-md-3"></div>
            <div class="col-md-7">
                <?php echo form_submit('submit', 'Update Email', 'class="btn btn-primary"'); ?>
                <?php echo form_close(); ?>
            </div>
          </div>

          

          <br>


          <br><br>








          <hr class="mg-y-30">

          

          <div class="ht-40"></div>
        </div><!-- az-content-body -->
      </div><!-- container -->
    </div><!-- az-content -->


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
