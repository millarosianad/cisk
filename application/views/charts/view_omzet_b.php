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

        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
        
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

    <?php 
    $no = 1;
    foreach($omzets as $omzet) : ?>
                       
    <?php endforeach; ?>



    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Bulan : ');
      data.addColumn('number', 'Rp');
      
      data.addRows([
        
        [{v:7, f:'b7'}, <?php echo $omzet->b7; ?>],
        [{v:8, f:'b8'}, <?php echo $omzet->b8; ?>],
        [{v:9, f:'b9'}, <?php echo $omzet->b9; ?>],
        [{v:10, f:'b10'}, <?php echo $omzet->b10; ?>],
        [{v:11, f:'b11'}, <?php echo $omzet->b11; ?>],
        [{v:12, f:'b12'}, <?php echo $omzet->b12; ?>],
       
      ]);


      var options = {
        height: 500,
        hAxis: {
          title: 'Bulan',
          axisFontSize : 0,
          gridlines: { count: 6 },
          minValue: 6
        },
        vAxis: {
            title: 'Omzet',
            gridlines: { count: 10 }
        },
        
        legend: 'none',
        trendlines: {
          0: {
            type: 'exponential',
          }
        },
         
        colors: ['#a52714'] 
      };

      // Instantiate and draw the chart.
      var chart = new google.visualization.ColumnChart(document.getElementById('myPieChart'));
      chart.draw(data, options);
    }
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
        $year['2018']='2018';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2010]=''.$i+2010;
        }
    ?>

    <div class="row">        
        <div class="col-xs-16">
            <h3><?php echo $page_title; ?></h3><hr />
        </div>

        <div class="col-md-3">
            <div class="form-group">
            </div>
        </div>
    </div>

        <div class="col-xs-2">
            Tahun
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('year', $year,'','class="form-control"');?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Periode
        </div>

        <div class="col-xs-5">
            <?php        
                $periode=array();
                $periode['1']=' 1 Tahun (Januari - Desember) ';
                $periode['2']=' 6 Bulan (Januari - Juni)';
                $periode['3']=' 6 Bulan (Juli - Desember)';
                echo form_dropdown('periode', $periode, '0','class="form-control"');
            ?>
        </div>

        <div class="col-xs-11">&nbsp;</div>


        <div class="col-xs-2">
            Supplier
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supplier', $supplier,'','class="form-control"  id="supp"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Group Product
        </div>

        <div class="col-xs-5">
                <?php
                    $group=array();
                    $group['0']='--';
                   
                ?>
            <?php  echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"'); ?>

        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Branch
        </div>

        <div class="col-xs-5">
            <?php        
                $branch=array();
                $branch['88']=' JAVAS KARYA TRIPTA - JKT ';
                $branch['95']=' JAYA BAKTI RAHARJA - JBR';
                $branch['89']=' JAVAS TRIPTA GEMALA - JTG';
                echo form_dropdown('branch', $branch, '0','class="form-control"');
            ?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-5">
            
            <?php
                $data = array(
                  'menuid'  => $getmenuid
                );
            echo form_hidden($data);
            //echo "menuid di view : ".$getmenuid;
            ?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>

        </div>
    </div>
    
    
       
    <hr />
    <?php 

        if ($suppliers == '000') {
            $suppliers = '4 besar (deltomed, ultra sakti, marguna, jaya agung';
        } elseif ($suppliers == '001') {
            $suppliers = 'deltomed';
        } elseif ($suppliers == '002') {
            $suppliers = 'marguna';
        } elseif ($suppliers == '003') {
            $suppliers = 'jamu jago';
        } elseif ($suppliers == '004') {
            $suppliers = 'jaya agung';
        } elseif ($suppliers == '005') {
            $suppliers = 'ultra sakti';
        } elseif ($suppliers == '009') {
            $suppliers = 'Unilever';
        } elseif ($suppliers == 'XXX') {
            $suppliers = 'all supplier';
        }
         else {
           $suppliers = 'belum dipilih';
        }
        
        echo "<pre>";
        echo "Anda memilih ";
        echo "tahun : ".$years." | ";
        echo "periode : ".$periodes." | ";
        echo "supplier : ".$suppliers." | ";
        echo "grup : ".$note;
        echo "</pre>";
        $no = 1;
    ?>

    </div>
    <hr>

    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">

            <?php foreach($omzets as $omzet) : ?>
            <tr>        
                <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                <td><font size="2px"><?php echo $omzet->namacomp; ?></td>
                <td><font size="2px"><?php echo number_format($omzet->b1); ?></td>
            </tr>
            <?php endforeach; ?>
                    
                   
        </div>
    </div>







    <div id="myPieChart"/>

    

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