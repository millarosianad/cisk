<!-- Modal -->
<div class="modal fade" id="tambah_suratjalan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Surat Jalan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class='row'>
        <div class="col-sm-12 col-lg-12 col-xl-12">
        <?php echo form_open('surat_jalan/viewfaktur');?>
          <div class="col-sm-12 col-lg-12 col-xl-12">
              <div class="form-group">
              <?php
                  echo form_label("Silahkan Pilih Client : ");
                  foreach($query->result() as $value)
                  {
                      $z[$value->grup_lang]= $value->grup_nama;                                       
                  }
                  echo form_dropdown('grup_lang',$z,'','class="form-control" id="grup_lang"');
                  //echo $x;
              ?>
              </div>
          </div>
          <div class="col-sm-12 col-lg-12 col-xl-12">
              <div class="form-group">
                  <?php echo form_label("Tampilkan Jenis Faktur : "); ?>
                  <?php $ketdd=array(
                          1=>'Faktur Lunas',
                          0=>'Copy Faktur',
                          2=>'Seluruh Faktur'
                      );?>
                      <?php echo form_dropdown('keterangan',$ketdd,'','class="form-control"');?>
              </div>
          </div>
          <div class="col-sm-12 col-lg-12 col-xl-12">
          <?php echo form_label("Periode : "); ?>
              <div class="input-group input-daterange">
                  <input type="date" class ='form-control' id="datepicker2" name="from" placeholder="" autocomplete="off">
                  <div class="input-group-addon"> to </div>
                      <input type="date" class = 'form-control' id="datepicker" name="to" placeholder="" autocomplete="off">
                  </div>
              </div>
          <div class="col-sm-12 col-lg-12 col-xl-12">
              <div class="form-group">
                  <?php echo br().form_submit('submit','Proses','class="btn btn-primary"');?>    
              </div>
              <?php echo form_close();?>
          </div>
        </div>         
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url() ?>assets/js/script.js"></script>