</div>
<!DOCTYPE html>
<html>
<head>
    <title>Monitor Claim</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>"> 
    <script type="text/javascript">                   
        $(document).ready(function() { 
            $("#year").click(function(){
                console.log('year');
                $.ajax({
                url:"<?php echo base_url(); ?>c_dp/build_namacomp",    
                data: {id_year: $(this).val()},
                type: "POST",
                success: function(data){
                    console.log(data);
                    $("#subbranch").html(data);
                    }
                });
            });

            $("#bulan").click(function(){
                // const tahun = document.getElementById('year');
                const tahun = $("#year").val();                
                console.log(tahun) 
                $.ajax({
                url:"<?php echo base_url(); ?>monitor_claim/get_program_by_bulan",    
                data: {
                    id_bulan: $(this).val(),
                    id_tahun:tahun
                    },
                type: "POST",
                success: function(data){
                    // console.log(data);
                    $("#program").html(data);
                    }
                });
            });

            $("#program").click(function(){
                console.log('claim');
                $.ajax({
                url:"<?php echo base_url(); ?>monitor_claim/get_program",    
                data: {id_claim: $(this).val()},
                type: "POST",
                success: function(data){
                    // console.log(data);
                    $("#detailprogram").html(data);
                    }
                });
            });
        });                    
    </script>
</head>
<body>
<div class="container">
<div class="row">
    <div class="col-md-9">
        <div class="col-xs-16">            
            <h3><?php echo $page_title; ?></h3><hr />
        </div>
        <br>
    </div>
</div>
 
<?php echo form_open_multipart($url);?>

<div class="row">
    <div class="col-xs-13">       
        <div class="col-xs-2">
            Tahun DP
        </div>
        <div class="col-xs-5">
            <?php             
                $interval=date('Y')-2010;
                $options=array();
                $options['0']='- Pilih Tahun -';
                $options['2010']='2010';
                for($i=1;$i<=$interval;$i++)
                {
                    $options[''.$i+2010]=''.$i+2010;
                }
                echo form_dropdown('year', $options, $options['0'],'class="form-control"  id="year"');
            ?>
            <!-- <input type="text" class = 'form-control' id="datepicker2" name="from" placeholder="" autocomplete="off" id="year"> -->
        </div>
        <div class="col-xs-12">&nbsp;</div>

        
        
        <div class="col-xs-2">
            Periode Bulan
        </div>
        <div class="col-xs-5">
            <select name="bulan" class="form-control" id="bulan">
                <option value="0"> - Pilih Bulan - </option>
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
            <!-- <input type="text"  name="from" placeholder="" autocomplete="off" class="bulan" id="datepicker2"> -->
        </div>

        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-2">
            Nama Program
        </div>

        <div class="col-xs-5">
            <?php                 
            $options=array();
            $options['0']='Pilih bulan terlebih dahulu';  
            echo form_dropdown('program', $options, $options['0'],'class="form-control"  id="program"');
            ?>
        </div>

        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-2">
            Detail Program
        </div>

        <div class="col-xs-5">
            <?php                 
            $options=array();
            $options['0']='Pilih program terlebih dahulu';  
            echo form_dropdown('program', $options, $options['0'],'class="form-control"  id="detailprogram"');
            ?>
        </div>


        <div class="col-xs-12">&nbsp;</div>
        <div class="col-xs-2">
        </div>
        <div class="col-xs-10">
           <?php echo br().form_submit('proses','Proses','onclick="return ValidateCompare();" class="btn btn-primary btn-md"');?>
        </div>        
    
    </div>
</div>

<hr>

<script src="<?php echo base_url() ?>assets/js/script.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.min.js') ?>"></script>




</body>
</html>