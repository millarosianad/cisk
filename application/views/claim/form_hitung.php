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

            // $("#program").click(function(){
            //     console.log('claim');
            //     $.ajax({
            //     url:"<?php echo base_url(); ?>monitor_claim/get_program",    
            //     data: {id_claim: $(this).val()},
            //     type: "POST",
            //     success: function(data){
            //         // console.log(data);
            //         $("#detailprogram").html(data);
            //         }
            //     });
            // });

            $("#cek_program").click(function(){
                const from = $("#datepicker").val();
                const to = $("#datepicker2").val();                
                console.log(from);
                console.log(to);
                $.ajax({
                url:"<?php echo base_url(); ?>monitor_claim/get_program_by_periode",     
                data: {
                    from: from,
                    to: to
                    },
                type: "POST",
                success: function(data){
                    console.log(data);
                    $("#program").html(data);
                    }
                });
            });

            $("#cek_detail_program").click(function(){
                const program = $("#program").val();              
                console.log(program);
                $.ajax({
                url:"<?php echo base_url(); ?>monitor_claim/get_detail_program",     
                data: {
                    program: program
                    },
                type: "POST",
                success: function(data){
                    console.log(data);
                    $("#detailprogram").html(data);
                    }
                });

            });

        });                    
    </script>
</head>
<body>
<br>
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
        <div class="col-xs-2">
            Periode Program
        </div>
        <div class="col-xs-4">
            <div class="input-group input-daterange">
                <input type="text" class = 'form-control' id="datepicker" name="from" placeholder="" autocomplete="off">
                <div class="input-group-addon">to</div>
                <input type="text" class = 'form-control' id="datepicker2" name="to" placeholder="" autocomplete="off">                
            </div>
        </div>  
        
        <div class="col-xs-4">
            <button type="button" class="btn btn-info" id = "cek_program">Get Program</button>
        </div>  

    </div><br>

    <div class="row">          
        <div class="col-xs-2">
            Nama Program
        </div>
        <div class="col-xs-4">
            <?php                 
            $options=array();
            $options['0']='Pilih periode terlebih dahulu';  
            echo form_dropdown('program', $options, $options['0'],'class="form-control"  id="program"');
            ?>
        </div>    
        <div class="col-xs-4">
            <button type="button" class="btn btn-info" id = "cek_detail_program">Get Detail Porgram</button>
        </div>  
    </div><br>


<div class="row">
    <div class="col-xs-13">       
       
        <div class="col-xs-2">
            Detail Program
        </div>

        <div class="col-xs-5">
            <?php                 
            $options=array();
            $options['0']='Pilih program terlebih dahulu';  
            echo form_dropdown('detail_program', $options, $options['0'],'class="form-control"  id="detailprogram"');
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