   <!-- <head>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
   </head> -->
   <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
<style>

/* a:hover{
  text-decoration: none ; 
  color: #1d1d1d;
  cursor: pointer;
} */

a{
  text-decoration: none ; 
  color: #1d1d1d;
}
</style>
<div class="container">
<div class="az-content-body col-md-12 d-flex flex-column">
  <!-- <div class="az-content-breadcrumb">
    <span>UPLOAD DATA TEXT</span>
  </div>     -->
  <div class="az-content-label mg-b-5 mt-5"><?= $title ?></div>         
    
  <hr class="mg-y-20" />
    
  <?php echo form_open_multipart($url); ?>
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

  <hr class="mg-y-30" />

  <p class="mg-b-20">2. Upload Data Anda</p>
  <div class="row row-sm">
    <div class="col-sm-7 col-md-6 col-lg-4">
      <!-- <div class="form-group">
        <!-- <label for="import_transaksi"></label> -->
        <!-- <input class="form-control" type="file" name="file" required/>
      </div> --> 
      <div class="custom-file">
        <label class="custom-file-label" for="customFile">Upload Data Anda</label>
        <input type="file" class="custom-file-input" id="customFile" name="file" required/>                
      </div>
    </div>
  </div>

  <br>
          
  <div class="row row-sm"> 
    <div class="col-sm-7 col-md-6 col-lg-4">
      <?php echo form_submit('submit', 'Proses Upload', 'class="btn btn-info"'); ?>
      <?php echo form_close(); ?>
    </div>
  </div>
  
  <hr class="mg-y-20" />
        
  <p class="mg-b-20">Log Import</p>
  <div class="row row-sm">
    <div class="col-sm-12">   
      <div class="table-responsive">
        <table id="example" class="cell-border" style="display: inline-block;">
          <thead>
            <tr>
              <th style="background-color: darkslategray;" class="text-center "><font color="white">No</th>
              <th style="background-color: darkslategray;" class="text-center "><font color="white">Created by</th>
              <th style="background-color: darkslategray;" class="text-center "><font color="white">Type Data</th>
              <th style="background-color: darkslategray;" class="text-center "><font color="white">Month</th>
              <th style="background-color: darkslategray;" class="text-center col-3"><font color="white">Filename (Click For Download)</th>
              <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">CreateDate</th>
              <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Status</th>
              <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">#</th>
            </tr>
          </thead>

          <tbody>
          <?php 
          $no =1;
          foreach($file as $files): 
          ?> 
          <!-- <?=var_dump($files);?> -->
          <tr>
            <th scope="row"><?= $no++; ?></th>
            <th scope="row"><?= $files->username;?></th>
            <th scope="row"><?= $files->typedata;?></th>
            <th scope="row"><?= $files->bulan;?></th>
            <th scope="row"><a href="<?=base_url("./assets/uploads/tester/$files->filename")?>"  ><?= $files->filename; ?></a></th>
            <th scope="row"><?= $files->created_at;?></th>
            <th>
              <?php 
              // echo $files->status_proses;
              if ($files->status_proses == 1) {
                echo "Pending";
              }else if($files->status_proses == 2){ 
                echo "On process";
              }else if($files->status_proses == 3){
                echo "Finish";
              }else if($files->status_proses == 4){
                echo "Error";
              }
              ?>
            </th>
            
            <th>
              <!-- status 
              1 = pending
              2 = on process
              3 = finish 
              4 = error  -->
              <?php 
              if ($files->status_proses == 1) { 
              ?>
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#proses<?=$files->signature;?>">Process
              </button>
              <!-- Modal -->
              <div class="modal fade" id="proses<?=$files->signature;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Masukkan Waktu Proses Data Text</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="<?= base_url().'monitor/status/' ?>" method="post">
                      <div class="modal-body">
                        <input type="datetime-local" name="event-dt" class="form-control" step="1" required/>
                        <input type="hidden" name="signature" width="234" value="<?=$files->signature?>">
                      </div> 
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" value="Proses" class="btn btn-warning">
                            <!-- <button type="submit" name="save_datetime" class="btn btn-warning" value="<?= base_url().'monitor/status/'.$files->signature ?>/2">Process</button> -->
                            <!-- <a href="<?= base_url().'monitor/status/'.$files->signature ?>/2" class="btn btn-warning btn-sm" > process</a> -->
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- <a href="<?= base_url()."monitor/status_error" ?>" class="btn btn-danger btn-sm"> Error</a> -->
              <!-- <a href="<?= base_url() ?>monitor/status_error/<?=$files->signature;?>" class="btn btn-danger btn-sm">Error</a> -->
              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#error<?=$files->signature;?>" data-whatever="@mdo">Error</button>
              <div class="modal fade" id="error<?=$files->signature;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                    <form action="<?= base_url().'monitor/status_error/' ?>" method="post">
                        <div class="form-group">
                          <label for="message-text" class="col-form-label">Message:</label>
                          <textarea name="message-text" class="form-control" id="message-text"></textarea>
                          <input type="hidden" name="signature" width="234" value="<?=$files->signature?>">
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <input type="submit" value="Error" class="btn btn-danger">
                          <!-- <a href="<?= base_url().'monitor/status_error/'.$files->signature ?>" class="btn btn-danger btn-sm">Error</a> -->
                        </div>
                    </form>
                    </div>
                  </div>
                </div>
              </div>
              
              <?php
              }elseif($files->status_proses == 2){ 
              ?>
              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#finish<?=$files->signature;?>">Finish
              </button>
              <!-- Modal -->
              <div class="modal fade" id="finish<?=$files->signature;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Masukkan Waktu Finish Data Text</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="<?= base_url().'monitor/status_finish/' ?>" method="post"> 
                      <div class="modal-body">
                        <!-- <input id="datetimepicker" name="datetimepicker" width="234"/> -->
                        <input type="datetime-local" name="event-dt" class="form-control"step="1" required/>
                        <input type="hidden" name="signature" width="234" value="<?=$files->signature?>">
                        <!-- <input id="input" name="signature" type="text" width="234" value="<?=$files->signature?>" hidden> -->
                      </div> 
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" value="Finish" class="btn btn-success">
                          <!-- <button type="submit" name="save_datetime" class="btn btn-warning" value="<?= base_url().'monitor/status/'.$files->signature ?>/2">Process</button> -->
                          <!-- <a href="<?= base_url().'monitor/status/'.$files->signature ?>/3" class="btn btn-success btn-sm"> finish</a> -->
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- <a href="<?= base_url()."monitor/status_error" ?>" class="btn btn-danger btn-sm"> Error</a> -->
              <!-- <a href="<?= base_url() ?>monitor/status_error/<?=$files->signature;?>" class="btn btn-danger btn-sm">Error</a> -->
              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#error2<?=$files->signature;?>" data-whatever="@mdo">Error</button>
              <div class="modal fade" id="error2<?=$files->signature;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                    <form action="<?= base_url().'monitor/status_error/' ?>" method="post">
                        <div class="form-group">
                          <label for="message-text" class="col-form-label">Message:</label>
                          <textarea name="message-text" class="form-control" id="message-text"></textarea>
                          <input type="hidden" name="signature" width="234" value="<?=$files->signature?>">
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <input type="submit" value="Error" class="btn btn-danger">
                          <!-- <a href="<?= base_url().'monitor/status_error/'.$files->signature ?>" class="btn btn-danger btn-sm">Error</a> -->
                        </div>
                    </form>
                    </div>
                  </div>
                </div>
              </div>

              <?php  
               }elseif($files->status_proses == 4){ 
                ?>
                <?= $files->pesan_error;?>
              <?php
               }?>
            </th>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    
    <hr class="mg-y-30" />

    <div class="ht-40"></div>
  </div><!-- az-content-body -->
</div>
</div><!-- container -->

<script>
      $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 7,
            
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "order": [[6, 'desc']],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
      });
</script>

<script>
    const dateControl = document.querySelector('input[type="datetime-local"]');
    dateControl.value = "y-m-dTH:i:s";
</script>

<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>