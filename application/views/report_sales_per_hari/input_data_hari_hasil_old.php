<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View Sales Perhari</title>

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
        $interval=date('Y')-2013;
        $year=array();
        $year['2020']='2020';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2013]=''.$i+2013;
        }
    ?>

    

    <div class="row">        
        <div class="col-xs-16">
            <?php echo br(3); ?>
            <h3><?php echo $page_title; ?></h3><hr />
        </div>

        <div class="col-md-3">
            <div class="form-group">
                
                
            </div>
        </div>
    </div>

        <div class="col-xs-3">
            Tahun (*)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
            Bulan 
        </div>
        <?php 
            $bulan = array(
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
        ?>
        <div class="col-xs-5">
            <?php echo form_dropdown('bulan', $bulan,'','class="form-control"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
            Supplier (*)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>
     
        <div class="col-xs-3">
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
        

        <div class="col-xs-3">
            Group By <?php echo anchor(base_url()."omzet/info", '(lihat detail ?)'); ?>
        </div>

        <div class="col-xs-8">
            <label class="fancy-checkbox">
                <input type="checkbox" name="tipe_1" value="1"><span> Kode produk</span>
            </label>
            <label class="fancy-checkbox">&nbsp;</label>
            <label class="fancy-checkbox">
                <input type="checkbox" name="tipe_2" value="1"><span> Class Outlet</span>
            </label>
            <label class="fancy-checkbox">&nbsp;</label>
            <label class="fancy-checkbox">
                <input type="checkbox" name="tipe_3" value="1"><span> Tipe Outlet</span>
            </label>
        </div>

        
        <div class="col-xs-3"></div>        
        
        <div class="col-xs-11"><br></div>

        <div class="col-xs-3">        
            
        </div>
        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>
            <a href="<?php echo base_url()."report_sales_per_hari/export_sales/"; ?>   " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        </div>
    </div>
    </div>
    <hr>
    <div class="row"> 
        <div class="col-xs-12">
        
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th ><font size="2px">Kode</th>
                            <th ><font size="2px">Branch</th>
                            <th ><font size="2px">Sub Branch</th>
                            <th ><font size="2px">1</th>
                            <th ><font size="2px">2</th>
                            <th ><font size="2px">3</th>
                            <th ><font size="2px">4</th>
                            <th ><font size="2px">5</th>
                            <th ><font size="2px">6</th>
                            <th ><font size="2px">7</th>
                            <th ><font size="2px">8</th>
                            <th ><font size="2px">9</th>
                            <th ><font size="2px">10</th>
                            <th ><font size="2px">11</th>
                            <th ><font size="2px">12</th>
                            <th ><font size="2px">13</th>
                            <th ><font size="2px">14</th>
                            <th ><font size="2px">15</th>
                            <th ><font size="2px">16</th>
                            <th ><font size="2px">17</th>
                            <th ><font size="2px">18</th>
                            <th ><font size="2px">19</th>
                            <th ><font size="2px">20</th>
                            <th ><font size="2px">21</th>
                            <th ><font size="2px">22</th>
                            <th ><font size="2px">23</th>
                            <th ><font size="2px">24</th>
                            <th ><font size="2px">25</th>
                            <th ><font size="2px">26</th>
                            <th ><font size="2px">27</th>
                            <th ><font size="2px">28</th>
                            <th ><font size="2px">29</th>
                            <th ><font size="2px">30</th>
                            <th ><font size="2px">31</th>
                            <th ><font size="2px">Bulan</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($proses as $a) : ?>
                        <tr>
                            <td><font size="2px"><?php echo $a->kode; ?></td>                      
                            <td><font size="2px"><?php echo $a->branch_name; ?></td>
                            <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                            <td><font size="2px"><?php echo $a->unit_1; ?></td>
                            <td><font size="2px"><?php echo $a->unit_2; ?></td>
                            <td><font size="2px"><?php echo $a->unit_3; ?></td>
                            <td><font size="2px"><?php echo $a->unit_4; ?></td>
                            <td><font size="2px"><?php echo $a->unit_5; ?></td>
                            <td><font size="2px"><?php echo $a->unit_6; ?></td>
                            <td><font size="2px"><?php echo $a->unit_7; ?></td>
                            <td><font size="2px"><?php echo $a->unit_8; ?></td>
                            <td><font size="2px"><?php echo $a->unit_9; ?></td>
                            <td><font size="2px"><?php echo $a->unit_10; ?></td>

                            <td><font size="2px"><?php echo $a->unit_11; ?></td>
                            <td><font size="2px"><?php echo $a->unit_12; ?></td>
                            <td><font size="2px"><?php echo $a->unit_13; ?></td>
                            <td><font size="2px"><?php echo $a->unit_14; ?></td>
                            <td><font size="2px"><?php echo $a->unit_15; ?></td>
                            <td><font size="2px"><?php echo $a->unit_16; ?></td>
                            <td><font size="2px"><?php echo $a->unit_17; ?></td>
                            <td><font size="2px"><?php echo $a->unit_18; ?></td>
                            <td><font size="2px"><?php echo $a->unit_19; ?></td>
                            <td><font size="2px"><?php echo $a->unit_20; ?></td>

                            <td><font size="2px"><?php echo $a->unit_21; ?></td>
                            <td><font size="2px"><?php echo $a->unit_22; ?></td>
                            <td><font size="2px"><?php echo $a->unit_23; ?></td>
                            <td><font size="2px"><?php echo $a->unit_24; ?></td>
                            <td><font size="2px"><?php echo $a->unit_25; ?></td>
                            <td><font size="2px"><?php echo $a->unit_26; ?></td>
                            <td><font size="2px"><?php echo $a->unit_27; ?></td>
                            <td><font size="2px"><?php echo $a->unit_28; ?></td>
                            <td><font size="2px"><?php echo $a->unit_29; ?></td>
                            <td><font size="2px"><?php echo $a->unit_30; ?></td>

                            <td><font size="2px"><?php echo $a->unit_31; ?></td>
                            <td><font size="2px"><?php echo $a->bulan; ?></td>
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
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>