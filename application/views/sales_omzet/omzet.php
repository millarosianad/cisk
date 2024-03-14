<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<script type="text/javascript">         
    $(document).ready(function() { 
        $("#to").click(function(){
            $.ajax({
            url:"<?php echo base_url(); ?>c_dp/get_subbranch",    
            data: {id_to: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
    });
</script>

<?php echo form_open($url); 
$tgl=date('d');
$bln=date('m');

if($tgl > 7){
    $bulan = $bln;
}else{
    $bulan = $bln - 1;
}
?>

    <div class="card">
        <div class="col-lg-12">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">From</label>
                <div class="col-lg-9">
                    <input class="form-control" type="date" name="from" required />
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">To</label>
                <div class="col-lg-9">
                    <input class="form-control" type="date" name="to" id = "to" required />
                </div>
            </div>
        </div>

        
        <input class="form-control" type="hidden" name="bulan_closing" value=<?php echo $bulan ?>>

        <div class="col-sm-12">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Principal</label>
                <div class="col-lg-9">       

                        <div class="checkbox-color checkbox-primary">
                            <input id="checkbox0" type="checkbox"  name="principal" value="XXX" onclick="checkUncheckAll(this);"/>
                            <label for="checkbox0">
                                All Principal
                            </label>
                        </div>

                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"></label>
                <!-- <div class="col-lg-7"> -->
                    
                    <div class="col-lg-2">
                        <div class="checkbox-color checkbox-primary">
                            <input id="checkbox1" type="checkbox"  name="deltomed" value="001" class="checkbox">
                            <label for="checkbox1">
                                Deltomed
                            </label>
                        </div>
                    </div>
                        
                    <div class="col-lg-2">
                        <div class="checkbox-color checkbox-primary">
                            <input id="checkbox2" type="checkbox"  name="us" value="005" class="checkbox">
                            <label for="checkbox2">
                                Ultra Sakti
                            </label>
                        </div>
                    </div>
                        
                    <div class="col-lg-2">
                        <div class="checkbox-color checkbox-primary">
                            <input id="checkbox3" type="checkbox"  name="marguna" value="002" class="checkbox">
                            <label for="checkbox3">
                                Marguna
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="checkbox-color checkbox-primary">
                            <input id="checkbox4" type="checkbox"  name="jaya" value="004" class="checkbox">
                            <label for="checkbox4">
                                Jaya Agung
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="checkbox-color checkbox-primary">
                            <input id="checkbox5" type="checkbox"  name="natura" value="010" class="checkbox">
                            <label for="checkbox5">
                                Natura Vita
                            </label>
                        </div>
                    </div>
                    
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"></label>

                    <div class="col-lg-2">
                        <div class="checkbox-color checkbox-primary">
                            <input id="checkbox6" type="checkbox"  name="intrafood" value="012" class="checkbox">
                            <label for="checkbox6">
                                Intrafood
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="checkbox-color checkbox-primary">
                            <input id="checkbox7" type="checkbox"  name="strive" value="013" class="checkbox">
                            <label for="checkbox7">
                                Strive
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="checkbox-color checkbox-primary">
                            <input id="checkbox8" type="checkbox"  name="hni" value="014" class="checkbox">
                            <label for="checkbox8">
                                Heavenly
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="checkbox-color checkbox-primary">
                            <input id="checkbox9" type="checkbox"  name="vonix" value="007" class="checkbox">
                            <label for="checkbox9">
                                Vonix
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="checkbox-color checkbox-primary">
                            <input id="checkbox10" type="checkbox"  name="mdj" value="015" class="checkbox">
                            <label for="checkbox10">
                                MDJ
                            </label>
                        </div>
                    </div>
            </div>
        </div>

        <!-- <div class="col-lg-12">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Sub Branch</label>
                <div class="col-lg-9">
                    <select multiple name = "nocab[]" id = "subbranch" class = "form-control" size = "7">
                    </select>
                </div>
            </div>
        </div>         -->

        <br><hr>

        <div class="col-lg-12">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Output</label>
                <div class="col-lg-9">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="output1">
                            <input type="radio" id="output1" name="output" value="1">                
                                <img src="<?php echo base_url() ?>assets_new/images/breakdown_dp.jpg" style="max-width: 100px; max-height:100px;"><br><br>
                                <a href="#" data-toggle="modal" data-target="#imageModal-1">&nbsp;&nbsp;&nbsp;&nbsp; By : Dp (view)</a>
                            </label>   
                        </div>
                        <div class="col-auto">            
                            <label for="output2">
                            <input type="radio" id="output2" name="output" value="2" checked>                
                                <img src="<?php echo base_url() ?>assets_new/images/breakdown_dp_bulan.jpg" style="max-width: 100px; max-height:100px;"><br><br>
                                <a href="#" data-toggle="modal" data-target="#imageModal-2">&nbsp;&nbsp;&nbsp;&nbsp; By : Dp, Bulan (view)</a>
                            </label>   
                        </div>
                        <div class="col-auto">            
                            <label for="output3">
                            <input type="radio" id="output3" name="output" value="3">                
                                <img src="<?php echo base_url() ?>assets_new/images/breakdown_principal.jpg" style="max-width: 100px; max-height:100px;"><br><br>
                                <a href="#" data-toggle="modal" data-target="#imageModal-3">&nbsp;&nbsp;&nbsp;&nbsp; By : Principal (view)</a>
                            </label>   
                        </div> 
                        <div class="col-auto">            
                            <label for="output4">
                            <input type="radio" id="output4" name="output" value="4">                
                                <img src="<?php echo base_url() ?>assets_new/images/breakdown_principal_bulan.jpg" style="max-width: 100px; max-height:100px;"><br><br>
                                <a href="#" data-toggle="modal" data-target="#imageModal-4">&nbsp;&nbsp;&nbsp;&nbsp; By : Principal, Bulan (view)</a>
                            </label>   
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label"></label>
                <div class="col-lg-9">
                    <?php echo form_submit('submit','Proses','onclick="return x();" class="btn btn-primary" id="btnKirim"');?>
                    <a href="<?php echo base_url()."sales_omzet/omzet_dp"; ?>"class="btn btn-default" id="btnLama" target="blank" role="button">Ingin membuka menu omzet sebelumnya ?</a>
    
                    <button class="btn btn-primary" id="btnLoading" type="button" disabled>
                    <!-- <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> -->
                    ... mohon tunggu ...
                    </button>

                   
                    <?php echo form_close();?>
                </div>
            </div>
        </div>

    </div>

    
<!-- ---------------- Modal ------------ -->

 <?php $this->load->view('sales_omzet/modal_omzet_break_dp');?>
 <?php $this->load->view('sales_omzet/modal_omzet_break_dp_bulan');?>
 <?php $this->load->view('sales_omzet/modal_omzet_break_principal');?>
 <?php $this->load->view('sales_omzet/modal_omzet_break_principal_bulan');?>

 <script>
    function x(){
        var c = document.getElementsByClassName('checkbox');
        console.log(c);
        var count = 0;
        for (var i = 0; i < c.length; i++) {
            if (c[i].checked) {
            count++;
            }
        }
        if (count < 1) {
            alert("Pilih Principal yang akan diamati.");
            return false;
        }
        // return true;
        else{
            $("#btnKirim").hide();
            $("#btnLama").hide();
            $("#btnLoading").show();
        }
        
    }

    $(document).ready(function() {
        
        $("#btnLoading").hide();
    });


    </script>

    
