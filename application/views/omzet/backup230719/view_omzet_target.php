<!DOCTYPE html>
<html>
<head>
    <title>Sell Out DP</title>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>">
    
    <script type="text/javascript">       
    $(document).ready(function() { 
        $("#supp").click(function(){
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
</head>
<body>


    <?php echo form_open($url);?>   
    <?php
        //echo form_label(" SUPPLIER : ");
        $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier[$value->supp]= $value->namasupp;
        }
    ?>

    <?php 
        $interval=date('Y')-2010;
        $year=array();
        $year['0']=' -- Pilih Tahun --';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2010]=''.$i+2010;
        }
    ?>

    <div class="row">        
        <div class="col-xs-16">
            <?php echo br(3); ?>
            <h3>Data Omzet dan Target</h3><hr />
        </div>

        <div class="col-md-3">
            <div class="form-group">
            </div>
        </div>
    </div>

        <div class="col-xs-2">
            Tahun (1)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Bulan (2)
        </div>

        <div class="col-xs-5">
            <?php        
                $bulan=array();
                $bulan['0']=' -- Pilih Bulan --';
                $bulan['1']='Januari';
                $bulan['2']='Februari';
                $bulan['3']='Maret';
                $bulan['4']='April';
                $bulan['5']='Mei';
                $bulan['6']='Juni';
                $bulan['7']='Juli';
                $bulan['8']='Agustus';
                $bulan['9']='September';
                $bulan['10']='Oktober';
                $bulan['11']='November';
                $bulan['12']='Desember';
                echo form_dropdown('bulan', $bulan, '0','class="form-control"');
            ?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Supplier (3)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Group Product (4)
        </div>
        <div class="col-xs-5">
                <?php
                    $group=array();
                    $group['0']='--';
                   
                ?>
            <?php  echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"'); ?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

  
       
        <div class="col-xs-5">
            <a href="<?php echo base_url()."omzet/omzet_target/" ?>  " class="btn btn-default" role="button"> kembali</a>
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>
<a href="<?php echo base_url()."omzet/export_omzet_target/" ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        
<br><br><hr>
        </div>
    

    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table class="table table table-striped table-bordered table-hover table-target">   
                    <thead>
                        <tr>                
                            <th><font size="2px"><center><br>No</font></th>
                            <th><font size="2px"><center><br>NAMA DP</th>
                            <th><font size="2px"><center>Target</center></th>
                            <th><font size="2px"><center>Omzet</center></th>
                            <th><font size="2px"><center>%</center></th>
                            <th><font size="2px"><center>Tanggal Transaksi</center></th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    
                    </tbody>
                    </table>
                    </div>
        </div>
    </div>   

    <script type="text/javascript" src="<?php echo base_url('assets/jquery.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.min.js') ?>"></script>

    <script type="text/javascript">
    $(".table-target").DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?php echo base_url('omzet/get_omzet_target') ?>",
            type:'POST',
        }
    });

    </script>
    </body>
</html>