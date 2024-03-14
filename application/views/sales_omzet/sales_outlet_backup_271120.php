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
<div class="col-sm-12"><h6>Custom Produk</h6></div>
    <br>
    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-12">
                <input type="button" class="btn btn-default" id="toggle" value="select all" onclick="click_all()" >
                <input type="button" class="btn btn-default" id="deltomed" value="pilih deltomed" onclick="click_deltomed()" > 
                <input type="button" class="btn btn-default" id="us" value="pilih us" onclick="click_us()" > 
                <input type="button" class="btn btn-default" id="marguna" value="pilih marguna" onclick="click_marguna()" >
                <input type="button" class="btn btn-default" id="jaya_agung" value="pilih jaya agung" onclick="click_jaya_agung()" > 
                <input type="button" class="btn btn-default" id="natura" value="pilih natura" onclick="click_natura()" >
                <input type="button" class="btn btn-default" id="intrafood" value="pilih intrafood" onclick="click_intrafood()" > 
                <input type="button" class="btn btn-default" id="strive" value="pilih strive" onclick="click_strive()" > 
            </div>
        </div>
    </div>

    <hr>

    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-12">
                <input type="button" class="btn btn-default" id="herbal" value="pilih herbal" onclick="click_herbal()" > 
                <input type="button" class="btn btn-default" id="candy" value="pilih candy" onclick="click_candy()" > 
                <input type="button" class="btn btn-default" id="herbana" value="pilih herbana" onclick="click_herbana()" > 
            </div>
        </div>
    </div>

    <hr>

    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-12">
                <input type="button" class="btn btn-default" id="freshcare" value="pilih freshcare" onclick="click_freshcare()" >
                <input type="button" class="btn btn-default" id="hotin" value="pilih hotin" onclick="click_hotin()" >  
                <input type="button" class="btn btn-default" id="madu" value="pilih madu" onclick="click_madu()" > 
                <input type="button" class="btn btn-default" id="mywell" value="pilih mywell" onclick="click_mywell()" > 
                <input type="button" class="btn btn-default" id="tresnojoyo" value="pilih tresnojoyo" onclick="click_tresnojoyo()" >
            </div>
        </div>
    </div>

    <hr>

    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-12">
                <input type="button" class="btn btn-default" id="pilkita" value="pilih pilkita" onclick="click_pilkita()" >
                <input type="button" class="btn btn-default" id="other_marguna" value="pilih other marguna" onclick="click_other_marguna()" >
            </div>
        </div>
    </div>

    <hr>
 
    <?php foreach ($header_supp as $a) { ?>
        
        <div class="col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12"><h6><u>
                    <?php echo $a->namasupp; ?>
                    </u></h6>
                </div>        
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group row">

            <?php 
                $proses = $this->model_per_hari->cari_kodeprod_view($a->supp);
                foreach ($proses as $b) { ?>
                    <div class="col-sm-4" id="test">                        
                        <input type="checkbox" id="<?php echo $b->kodeprod; ?>" name="options[]" class = "<?php echo $b->supp.$b->grup; ?>" value="<?php echo $b->kodeprod; ?>">
                        <label for="<?php echo $b->kodeprod; ?>">
                        <?php echo $b->namaprod.' ('.$b->kodeprod.')'; ?>
                        </label>              
                    </div>
                <?php
                }
            ?>  
            </div>
        </div>
    <?php
    } ?>
</div>

</div>