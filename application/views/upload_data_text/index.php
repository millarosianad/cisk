
<div class="az-content-body pd-lg-l-40 d-flex flex-column">
<div class="az-content-breadcrumb">
    <span>UPLOAD DATA TEXT</span>
  </div>
      <!-- <div class="container"> -->
        <!-- <div class="az-content-left az-content-left-components">

          <div class="component-item">

            <label>Upload Data Text</label>
            <nav class="nav flex-column">
              <a href="#" class="nav-link active">Form Upload</a>
              <a href="#" class="nav-link">History</a>
            </nav>

          </div>

        </div> -->
        <!-- az-content-left -->
        <!-- <div class="az-content-body pd-lg-l-40 d-flex flex-column">
          <div class="az-content-breadcrumb">
            <span>Upload data text</span>
            <span>form upload</span>
          </div> -->
          <!-- <h2 class="az-content-title">Summary Sales MTI</h2> -->
          
             <div class="az-content-label mg-b-5 mt-3"><?= $title ?></div>         

          <!-- row -->

          <hr class="mg-y-20" />

          <?php echo form_open_multipart('/upload_data_text/upload'); ?>

          
          <p class="mg-b-20">1. Pilih Jenis Data Text</p>

          <div class="col-lg-12">
            <div class="form-group row">
                <div class="col-lg-9">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto ">
                            <label for="output1">
                            <input type="radio" id="output1" name="typedata" value="Daily" required>
                              <span>Daily</span>
                            </label>   
                        </div>
                        <div class="col-auto">            
                            <label for="output2">
                            <input type="radio" id="output2" name="typedata" value="All" required>                
                              <span>All</span>
                            </label>   
                        </div>
                        <div class="col-auto">            
                            <label for="output3">
                            <input type="radio" id="output3" name="typedata" value="Sudah Fix" required>                
                              <span>Sudah Fix</span>
                            </label>   
                        </div> 
                        <div class="col-auto">            
                            <label for="output4">
                            <input type="radio" id="output4" name="typedata" value="Belum Fix" required>                
                              <span>Belum Fix</span>
                            </label>   
                        </div>
                    </div>
                </div>
            </div>
        </div>

          <!-- <div class="row row-sm">
            <div class="col-sm-7 col-md-6 col-lg-4">
              <div class="custom-file form-check-inline">
                    <label class="rdiobox">
                      <input name="rdio" type="radio">
                      <span>Daily</span>
                    </label>
                    <label class="rdiobox">
                      <input name="rdio" type="radio">
                      <span>All</span>
                    </label>
                    <label class="rdiobox">
                      <input name="rdio" type="radio">
                      <span>Belum Fix</span>
                    </label>
                    <label class="rdiobox">
                      <input name="rdio" type="radio">
                      <span>Sudah Fix</span>
                    </label> -->
                    <!-- <input type="radio" id="html" name="fav_language" value="HTML">
                    <label for="html">Daily</label><br>
                    <input type="radio" id="css" name="fav_language" value="CSS">
                    <label for="css">All</label> <br>
                    <input type="radio" id="csss" name="fav_language" value="CSSS">
                    <label for="csss">Belum Fix</label> <br>
                    <input type="radio" id="csss" name="fav_language" value="CSSS">
                    <label for="csss">Sudah Fix</label> <br> -->
                <!-- </div>
              </div>
            </div> -->
          <!-- row -->

          <hr class="mg-y-30" />
          <!-- <div class="row row-sm">

            <div class="col-sm-7 col-md-6 col-lg-4">

          <div class="form-group">
                    <label for="bulan">2. Pilih Bulan</label>
                    <input class="form-control" type="month" name="bulan" required/>
          </div>
            </div>
          </div>


          <hr class="mg-y-30" /> -->
          
          <p class="mg-b-20">2. Upload Data Anda</p>
          <div class="row row-sm">
            <div class="col-sm-7 col-md-6 col-lg-4">
              <!-- <a href="<?= base_url(); ?> index.php/upload_data_text/create"></a> -->
              <div class="form-group">
                <!-- <label for="import_transaksi"></label> -->
                <input class="form-control" type="file" name="file" required/>
              </div>
              <!-- <div class="custom-file">
                <input type="file" class="custom-file-input" id="customFile" name="file" />
                <label class="custom-file-label" for="customFile">Upload Data Anda</label>                
              </div> -->
            </div>
            
            
          </div>
          <!-- row -->
          <br>
          
          <div class="row row-sm">
            
            <div class="col-sm-7 col-md-6 col-lg-4">
              <!-- <input type="submit" class="btn btn-az-primary value="Upload"> -->
              <?php echo form_submit('submit', 'Proses Upload', 'class="btn btn-az-primary"'); ?>
              <?php echo form_close(); ?>
            </div>
            
          </div>
          
        <!-- row -->
        
        <!-- row -->
        
        <hr class="mg-y-20" />
        
        <p class="mg-b-20">Log Import</p>
        <div class="row row-sm">
          <div class="col-sm-12">   
            <div class="table-responsive">
              <table id="data" class="table table-hover table-striped mg-b-0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Created by</th>
                    <th>Type Data Text</th>
                    <th>Month</th>
                    <th>Filename</th>
                    <th>CreateDate</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Update</th>
                  </tr>
                </thead>

                <tbody>
                <?php 
                $no =1;
                foreach($file as $files):
                ?>
                    <tr>
                        <th scope="row"><?= $no++; ?></th>
                        <th scope="row"><?= $files->username;?></th>
                        <th scope="row"><?= $files->typedata;?></th>
                        <th scope="row"><?= $files->bulan;?></th>
                        <th scope="row"><?= $files->filename; ?></th>
                        <th scope="row"><?= $files->created_at;?></th>
                        <th scope="row"><a href="<?=base_url("./assets/uploads/tester/".date('Ym')."/$files->filename")?>" class="btn btn-az-primary" download>Download</a></td></th>
                        <th>
                          <?php 
                          // echo $files->status_proses;
                          if ($files->status_proses == 1) {
                            echo "pending";
                          }else if($files->status_proses == 2){ 
                            echo "on process";
                          }else if($files->status_proses == 3){
                            echo "finish";
                          }elseif($files->status_proses == 3){
                            echo "error";
                          }
                          ?>
                        </th>
                        <th>
                          <?php 

                          // status 
                          // 1 = pending
                          // 2 = on process
                          // 3 = finish 
                          // 4 = error 

                          if ($files->status_proses == 1) { ?>
                            <a href="<?= base_url().'upload_data_text/status/'.$files->id ?>/2" class="btn btn-primary btn-sm">-> process</a>
                            <a href="<?= base_url().'upload_data_text/status_error'?>" class="btn btn-danger btn-sm">Error</a>
                            <?php
                          }elseif($files->status_proses == 2){ ?>
                            <a href="<?= base_url().'upload_data_text/status/'.$files->id ?>/3" class="btn btn-success btn-sm">-> finish</a>
                            <a href="<?= base_url().'upload_data_text/status_error'?>" class="btn btn-danger btn-sm">Error</a>
                          <?php  
                          } ?>
                        </th>
                    </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            </div>


            </div>
            <!-- col -->
          <!-- row -->

          <!-- row -->
          <hr class="mg-y-30" />

          
          
          <div class="ht-40"></div>
        </div><!-- az-content-body -->
      </div><!-- container -->
    </div><!-- az-content -->


    <script>
      $(document).ready(function () {
        $("#data").DataTable();
      });
    </script>