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
                $("#subbranch").html(data);
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
            <h3>Data Omzet <?php echo $note; ?></h3><hr />
        </div>

        <div class="col-xs-16">
            <div class="form-group">
                <?php
                    $group=array();
                    $group['0']='--';                   
                ?>
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
            Supplier (*)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
            Group Product (*)
        </div>

        <div class="col-xs-5">
            <?php  echo form_dropdown('group', $group, 'ALL','class="form-control" id="subbranch"'); ?>

        </div>


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

        <div class="col-xs-3">
            Status Closing di Bulan 
        </div>

        <?php 
            $bulan = array(
                // '1'  => 'Januari',
                // '2'  => 'Februari',
                //'3'  => 'Maret', 
                //  '4'  => 'April',
                // '5'  => 'Mei',
                // '6'  => 'Juni',
                // '7'  => 'Juli',
                // '8'  => 'Agustus',
                '9'  => 'September',
                // '10'  => 'Oktober',
                // '11'  => 'November',
                // '12'  => 'Desember 2019'               
              );
        ?>



        <div class="col-xs-5">
            <?php echo form_dropdown('bulan', $bulan,'','class="form-control"');?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
        Tipe Outlet <br><i>Sementara berfungsi hanya untuk Deltomed (herbal,candy,beverages) </i><?php echo anchor(base_url()."omzet/info", '(?)'); ?>
        </div>

        <div class="col-xs-8">
            <label class="fancy-checkbox">
                <input type="checkbox" name="tipe_1" value="1"><span> Apotik</span>
            </label>
            <label class="fancy-checkbox">&nbsp;</label>
            <label class="fancy-checkbox">
                <input type="checkbox" name="tipe_2" value="2"><span> Perusahaan Besar Farmasi</span>
            </label>
            <label class="fancy-checkbox">&nbsp;</label>
            <label class="fancy-checkbox">
                <input type="checkbox" name="tipe_3" value="3"><span> MT Lokal</span>
            </label>
        </div>

        <div class="col-xs-3">
        </div>
        <div class="col-xs-11"><hr></div>
        <div class="col-xs-3">
        </div>
        <div class="col-xs-6">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>

             <a href="<?php echo base_url()."omzet/export_omzet/". $tahun."/".$supp."/".$header."/".$note_x; ?>   " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        </div>
    </div>
    
    
       
    <hr />
    <?php 

        if ($supp == '000') {
            $supplier = '4 besar (deltomed, ultra sakti, marguna, jaya agung';
        } elseif ($supp == '001') {
            $supplier = 'deltomed';
        } elseif ($supp == '002') {
            $supplier = 'marguna';
        } elseif ($supp == '003') {
            $supplier = 'jamu jago';
        } elseif ($supp == '004') {
            $supplier = 'jaya agung';
        } elseif ($supp == '005') {
            $supplier = 'ultra sakti';
        } elseif ($supp == '009') {
            $supplier = 'Unilever';
        } elseif ($supp == 'XXX') {
            $supplier = 'all supplier';
        }
         else {
           $supplier = 'belum dipilih';
        }
        
        echo "<pre>";
        echo "Anda memilih ";
        echo "tahun : ".$tahun." | ";
        echo "supplier : ".$supplier." | ";
        echo "grup : ".$note;
        echo "</pre>";
        $no = 1;
    ?>

    </div>
    <hr>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">No</font></th>
                            <th width="1"><font size="2px">NAMA DP</th>
                            <th><font size="2px">JAN</th>
                            <th><font size="2px">FEB</th>
                            <th><font size="2px">MAR</th>
                            <th><font size="2px">APR</th>
                            <th><font size="2px">MEI</th>
                            <th><font size="2px">JUN</th>
                            <th><font size="2px">JUL</th>
                            <th><font size="2px">AGS</th>
                            <th><font size="2px">SEP</th>
                            <th><font size="2px">OKT</th>
                            <th><font size="2px">NOV</th>
                            <th><font size="2px">DES</th>
                            <th><font size="2px">Total</th>
                            <th><font size="2px">Rata</th>
                            <th width="1"><font size="2px">Upload Terakhir</th>
                            <th width="1"><font size="2px">Closing <?php echo $header; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($omzets as $omzet) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                            <td><font size="2px"><?php echo $omzet->namacomp; ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b1); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b2); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b3); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b4); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b5); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b6); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b7); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b8); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b9); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b10); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b11); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b12); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->total); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->rata); ?></td>
                            <td><font size="2px"><?php echo $omzet->lastupload; ?></td>
                            <td><font size="2px">
                            <?php 
                                if ($omzet->status_closing == '0') {
                                    echo "belum";
                                }elseif ($omzet->status_closing == '1'){

                                
                                    echo "closing";
                                }else{
                                    echo " - ";
                                }
                                 ?>
                            
                            </td>
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