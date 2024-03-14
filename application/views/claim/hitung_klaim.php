</div>
<!DOCTYPE html>
<html>
<head>
    <title>MPM | Claim</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>"> 
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
        </div>        
    
    </div>
</div>

<hr>

<script src="<?php echo base_url() ?>assets/js/script.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.min.js') ?>"></script>




</body>
</html>