<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>List Order</title>
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
        <script type="text/javascript">       
        $(document).ready(function() { 
        $("#supp").click(function(){
            $.ajax({
            url:"<?php echo base_url(); ?>omzet/buildgroup",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
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
<?php
          $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier[$value->supp]= $value->namasupp;
        }
    ?>
 
<?php echo form_open_multipart($url);?>

<div class="row">
    <div class="col-xs-13">       
        <div class="col-xs-2">
            Tahun DP
        </div>
        <div class="col-xs-5">
            <?php             
                $interval=date('Y')-2019;
                $options=array();
                $options['0']='- Pilih Tahun -';
                $options['2019']='2019';
                for($i=1;$i<=$interval;$i++)
                {
                    $options[''.$i+2019]=''.$i+2019;
                }
                echo form_dropdown('tahun', $options, $options['0'],'class="form-control"  id="year"');
            ?>
        </div>
        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-2">
            Supplier (3)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
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
            Tampilkan
        </div>
        <div class="col-xs-5">
            <select name="kondisi" class="form-control">
                <option value="1">SubBranch</option>
                <option value="2">SubBranch dan SubTotal</option>
            </select>
            <!-- <input type="text"  name="from" placeholder="" autocomplete="off" class="bulan" id="datepicker2"> -->
        </div>

        <div class="col-xs-12">&nbsp;</div>
        <div class="col-xs-2">
        </div>
        <div class="col-xs-10">
           <?php echo br().form_submit('proses','Proses','onclick="return ValidateCompare();" class="btn btn-primary btn-md"');?>
           <a href="<?php echo base_url()."monitor_claim/export_rekap_klaim/"; ?>   " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
       
        </div>        
    
    </div>
</div>

<hr>
            </div>
    </div>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width='10%'><center>Branch</th>
                            <th width='10%'><center>SubBranch</th>
                            <th width='1%'><center>KodeProd</th>
                            <th width='10%'><center>Namaprod</th>
                            <th width='5%'><center>Sales_Unit</th>
                            <th width='10%'><center>Sales_value</th>
                            <th width='5%'><center>Bonus_Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($proses as $x) : ?>
                        <tr>          
                            <td><font size="2"><?php echo $x->branch_name; ?></td>  
                            <td><font size="2"><?php echo $x->nama_comp; ?></td>
                            <td><font size="2"><?php echo $x->kodeprod; ?></td>
                            <td><font size="2"><?php echo $x->namaprod; ?></td>
                            <td><?php echo number_format($x->sales_unit); ?></td>
                            <td><?php echo number_format($x->sales_value); ?></td>
                            <td><?php echo number_format($x->bonus_unit); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    
                    </tbody>
                    </table>
                    </div>
                    <div class="col-xs-11">&nbsp; </div>
        </div>
    </div>
    

     <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                "ordering": false,
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>