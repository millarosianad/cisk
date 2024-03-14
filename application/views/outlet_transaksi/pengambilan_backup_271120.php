
<?php echo form_open($url);  ?>
<div class="card">

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Periode Awal</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="periode_1" required />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Periode Akhir</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="periode_2" required />
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
                        <input id="checkbox1" type="checkbox"  name="tipe_1" value="1">
                        <label for="checkbox1">
                            Class Outlet
                        </label>
                    </div>

                    <div class="checkbox-color checkbox-primary">
                        <input id="checkbox2" type="checkbox"  name="tipe_2" value="1">
                        <label for="checkbox2">
                        Tipe Outlet
                        </label>
                    </div>
                </div>
            </div>
         </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <?php echo form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>
                <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>
    <br>
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
                    <?php echo $a->namasupp.'-'.$a->supp; ?>
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

