<!doctype html>
<html>
    <head>        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View All Omzet</title>
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
    </head>
    <body>
    <?php //echo br(3); ?>
    <?php echo form_open('all_raw/data_raw_hasil/');?>
    <?php  
        //$interval=date('Y')-2010;
        $interval=date('Y')-2017;
        $year=array();
        $year['2017']='2017';
        for($i=1;$i<=$interval;$i++)
        {
            //$year[''.$i+2010]=''.$i+2010;
            $year[''.$i+2017]=''.$i+2017;
        }
    ?>
    <?php
        //echo form_label(" SUPPLIER : ");
        $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier[$value->supp]= $value->namasupp;
        }
    ?>
    <?php
        $month = array(
                  '1'  => 'Januari',
                  '2'  => 'Februari',
                  '3'  => 'Maret', 
                  '4'  => 'April',
                  '5'  => 'Mei',
                  '6'  => 'Juni',
                  '7'  => 'Juli',
                  '8'  => 'Agustus',
                  '9'  => 'September',
                  '10'  => 'Oktober',
                  '11'  => 'November',
                  '12'  => 'Desember'               
                );

        $minggu = array(
            '0'  => ' -- Pilih Minggu --',
            '1'  => 'I',
            '2'  => 'I - II',
            '3'  => 'I - III', 
            '4'  => 'I - IV'          
            );
    ?>
    <div class="row">        
        <div class="col-xs-16">
            
            <h3>Data Raw</h3><hr />
        </div>
        <div class="col-xs-2">
            Tahun :
        </div>
        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,date('Y'),"class='form-control'");?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Bulan :
        </div>
        <div class="col-xs-5">
            <?php echo form_dropdown('month', $month,date('Y'),"class='form-control'");?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Minggu :
        </div>
        <div class="col-xs-5">
            <?php echo form_dropdown('minggu', $minggu,date('Y'),"class='form-control'");?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Silahkan pilih supplier :
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,date('Y'),"class='form-control'");?>
        </div>
        <div class="col-xs-11">&nbsp;</div>
        <div class="col-xs-2">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>
        </div>
    </div>
       
    <hr />

     <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                "ordering": false,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>