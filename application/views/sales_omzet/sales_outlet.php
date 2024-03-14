<script type="text/javascript">         
    $(document).ready(function() { 
        $("#year").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>c_dp/build_namacomp",    
            data: {id_year: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
    });
</script>

<?php echo form_open($url);
$interval=date('Y')-2015;
$options=array();
$options['0']='- Pilih Tahun -';
// $options['2015']='2015';
for($i=1;$i<=$interval;$i++)
{
    $options[''.$i+2015]=''.$i+2015;
}
?>

<div class="card">
    <div class="col-sm-12">
        <div class="form-group row">
        <label class="col-sm-2 col-form-label">Tahun (*) :</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                  <?php   
                  
                  echo form_dropdown('year', $options, $options['0'],'class="form-control"  id="year"');
                  ?>
                </div>
            </div>
        </div>
    </div>
  
<div class="col-sm-12">
    <div class="form-group row">
     <label class="col-sm-2 col-form-label">Sub Branch (*)
      Untuk menarik beberapa Sub Branch Sekaligus. 
      Caranya : tekan tombol ctrl / shift di keyboard lalu klik Sub Branch - Sub Branch yang diinginkan)</label>
        <div class="col-sm-9">
            <div class="col-sm-6">
                <?php        
                    $options=array();
                    // $options['0']='- Pilih Sub Branch -';
                    // echo form_dropdown('nocab', $options, 'ALL','class="form-control" id="subbranch"');
                ?>
                <select multiple name = "nocab[]" id = "subbranch" class = "form-control" size = "7">
              </select>
            </div>
        </div> 
      </div> 
    </div>   

<div class="col-sm-12">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Breakdown</label>
        <div class="col-sm-9">
            <div class="col-sm-6">        

            <div class="checkbox-color checkbox-primary">
                <input id="checkbox1" type="checkbox"  name="bd" value="1">
                <label for="checkbox1">
                    Kode produk
                </label>
            </div>

            <div class="checkbox-color checkbox-primary">
                <input id="checkbox2" type="checkbox"  name="sm" value="1">
                <label for="checkbox2">
                    Salesman
                </label>
            </div>

        </div>
    </div>
</div>

<div class="col-sm-12">
  <div class="form-group row">            
      <div class="col-sm-2"></div>
        <div class="col-sm-9">
            <div class="col-sm-6">
                <?php echo form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<hr>
    <?php
    $this->load->view('templates/layout_button_produk');
    ?>
    <hr>
</div>

</div>