<script type="text/javascript">       
    $(document).ready(function() { 
        $("#supp").click(function(){
            console.log('a');
            $.ajax({
            url:"<?php echo base_url(); ?>omzet/buildgroup",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#group").html(data);
                }
            });
        });
    });            
</script>

    <?php echo form_open($url); 

        //echo form_label(" SUPPLIER : ");
        $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier[$value->supp]= $value->namasupp;
        }
    
        $interval=date('Y')-2012;
        $year=array();
        $year['2022']='2022';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2012]=''.$i+2012;
        }

        $tgl=date('d');
        $bln=date('m');

        if($tgl > 7){

            if ($bln == 1)
            {
                $bulan = array(
                    '1'  => 'Januari'        
                );
            }elseif ($bln == 2){
                $bulan = array(
                    '2'  => 'Februari'         
                );
            }elseif ($bln == 3){
                $bulan = array(
                    '3'  => 'Maret'           
                );
            }elseif ($bln == 4){
                $bulan = array(
                    '4'  => 'April' 
                );
            }elseif ($bln == 5){
                $bulan = array(
                    '5'  => 'Mei'             
                );
            }elseif ($bln == 6){
                $bulan = array(
                    '6'  => 'Juni'       
                );
            }elseif ($bln == 7){
                $bulan = array(
                    '7'  => 'Juli'           
                );
            }elseif ($bln == 8){
                $bulan = array(
                    '8'  => 'Agustus'           
                );
            }elseif ($bln == 9){
                $bulan = array(
                    '9'  => 'September'           
                );
            }
            elseif ($bln == 10){
                $bulan = array(
                    '10'  => 'Oktober'           
                );
            }elseif ($bln == 11){
                $bulan = array(
                    '11'  => 'November'           
                );
            }elseif ($bln == 12){
                $bulan = array(
                    '12'  => "Desember"           
                );
            }

        }else{

            if ($bln == 1)
            {
                $bulan = array(
                    '12'  => "Desember"         
                );
            }elseif ($bln == 2){
                $bulan = array(
                    '1'  => 'Januari'         
                );
            }elseif ($bln == 3){
                $bulan = array(
                    '2'  => 'Februari'          
                );
            }elseif ($bln == 4){
                $bulan = array(
                    '3'  => 'Maret' 
                );
            }elseif ($bln == 5){
                $bulan = array(
                    '4'  => 'April'             
                );
            }elseif ($bln == 6){
                $bulan = array(
                    '5'  => 'Mei'        
                );
            }elseif ($bln == 7){
                $bulan = array(
                    '6'  => 'Juni'            
                );
            }elseif ($bln == 8){
                $bulan = array(
                    '7'  => 'Juli'           
                );
            }elseif ($bln == 9){
                $bulan = array(
                    '8'  => 'Agustus'            
                );
            }
            elseif ($bln == 10){
                $bulan = array(
                    '9'  => 'September'          
                );
            }elseif ($bln == 11){
                $bulan = array(
                    '10'  => 'Oktober'           
                );
            }elseif ($bln == 12){
                $bulan = array(
                    '11'  => "November"           
                );
            }

        }
    ?>
        <div class="card">
                <div class="col-sm-10">
                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-10">
                        <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
                        </div>
                    </div>
                </div>
        
                <div class="col-sm-10">
                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Supplier</label>
                        <div class="col-sm-10">
                        <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-10">
                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Group Product</label>
                        <div class="col-sm-10">
                            <?php
                                    $group=array();
                                    $group['0']='--';
                            ?>
                             <?php echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"');?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-10">
                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Status Closing di Bulan </label>  
                        <div class="col-sm-10">
                            <?php echo form_dropdown('bulan', $bulan,'','class="form-control"');?>
                        </div>    
                    </div> 
                </div>

        <div class="col-sm-10">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Sektor Outlet <?php echo anchor(base_url()."omzet/info", '(lihat detail ?)'); ?></label>
        <div class="col-sm-10">        

                <div class="checkbox-color checkbox-primary">
                    <input id="checkbox1" type="checkbox"  name="tipe_1" value="1">
                    <label for="checkbox1">
                    Apotik
                    </label>
                </div>
                
                <div class="checkbox-color checkbox-primary">
                    <input id="checkbox2" type="checkbox"  name="tipe_2" value="1">
                    <label for="checkbox2">
                    Perusahaan Besar Farmasi
                    </label>
                </div>

                <div class="checkbox-color checkbox-primary">
                    <input id="checkbox3" type="checkbox"  name="tipe_3" value="1">
                    <label for="checkbox3">
                    MT Lokal
                    </label>
                </div>
                
            </div>
        </div>

       <div class="col-sm-10">
        <div class="form-group row">            
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
