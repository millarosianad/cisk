<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View Data PO</title>
        <!-- Load Jquery, Bootstrap, dan DataTables dari CDN -->
        <!-- buka url ini: http://pastebin.com/index/WeaY5Fra -->
        <!-- load Jquery dari CDN -->
       
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

        <!-- Load SCRIPT.JS which will create datepicker for input field  -->
        <script src="<?php echo base_url() ?>assets/js/script.js"></script>

    </head>
    <body>
    <?php echo br(3); ?>
    <?php echo form_open($url);?>
    <br>
    <h2>Open Credit Limit</h2>
    <hr>

    
    
    <div class="row">        
        <div class="col-xs-3">            
        <?php
            echo form_label("Silahkan Pilih Client : ");                
        ?>            
        </div>
        <div class="col-xs-5">
            <div class="form-group">
            <?php
                foreach($query_pel->result() as $value)
                {
                    $x[$value->grup_lang]= $value->branch_name. ' / ' .$value->grup_nama;                                       
                }
                echo form_dropdown('grup_lang',$x,'','class="form-control" id="grup_lang"');
            ?>
            </div>
        </div>
    </div>
    

    <div class="row">        
        <div class="col-xs-3">            
        <?php
            echo form_label("Tanggal Order: ");                
        ?>            
        </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" id="datepicker" name="tgl" placeholder="(opsional)">
        </div>
    </div>
    <br>
    <div class="row">        
        <div class="col-xs-3">                       
        </div>
        <div class="col-xs-5">
             <?php echo form_submit('submit','  -- Cari --  ','class = "btn btn-success btn-md"'); ?>
             
        </div>
    </div>

    <?php echo form_close();?>
</div>

    <hr><br>
    <div class="row">
        <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1%"><font size="2px">Order</font></th>
                            <th width="2%">Nama DP </th>                            
                            <th width="1%"><font size="2px">Supplier</th>
                            <th width="3%"><font size="2px">tgl order</th>
                            <th width="3%"><font size="2px">tgl po</th>
                            <th width="1%"><font size="2px">Tipe</th>
                            <th width="1%"><font size="2px">No PO</th>
                            <th width="1%"><font size="2px">Nilai Order</th>
                            <th width="1%"><font size="2px">Piutang</th>
                            <th width="1%"><font size="2px">TotalPO</th>
                            <th width="1%"><font size="2px">Bank Garansi</th>
                            <th width="1%"><font size="2px">Credit Limit</th>
                            <th width="1%"><font size="2px">Due Date(>7)</th>
                            <th width="1%"><font size="2px">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($query as $querys) : ?>
                        <tr>             
                            <td>
                                <?php 
                                echo anchor('trans/po/print/' . $querys->id, $querys->id);
                                ?>
                            </td>
                            <td><?php echo $querys->company; ?></td>
                            <td><font size="2px">
                                <?php 
                                    if ($querys->supp == '001') {
                                        $supp = "Deltomed";
                                    }elseif ($querys->supp == '002') {
                                        $supp = "Marguna";
                                    }elseif ($querys->supp == '004') {
                                        $supp = "Jaya Agung";
                                    }elseif ($querys->supp == '005') {
                                        $supp = "Ultra Sakti";
                                    }elseif ($querys->supp == '010') {
                                        $supp = "Natura";
                                    }elseif ($querys->supp == '012') {
                                        $supp = "Intrafood";
                                    }else {
                                        $supp = $querys->supp;
                                    }
                                    echo $supp; 
                                ?>
                            </td>
                            <td><font size="2px">
                                <?php 
                                    $x = $querys->tglpesan;
                                    //echo date_format('2017-12-08', '%d %M %Y, %T'); 
                                    //echo $querys->tglpesan;
                                    echo substr($x, 0,10)
                                ?>
                            </td>
                            <td><font size="2px">
                                <?php 
                                    $x = $querys->tglpox;
                                    //echo date_format('2017-12-08', '%d %M %Y, %T'); 
                                    //echo $querys->tglpesan;
                                    echo $x;
                                ?>
                            </td>
                            
                            <td><font size="2px"><?php echo $querys->tipe; ?></td>
                            <td><font size="2px"><?php echo $querys->nopo; ?></td>
                            <td><font size="2px"><?php echo number_format($querys->value); ?></td>
                            <td><font size="2px"><?php echo number_format($querys->saldoakhir); ?></td>
                            <td><font size="2px"><?php echo number_format($querys->total_po); ?></td>
                            <td><font size="2px"><?php echo $querys->bank_garansi; ?></td>
                            <td><font size="2px"><?php echo $querys->cl; ?></td>
                            <td><font size="2px"><?php echo number_format($querys->jt); ?></td>
                            <td><font size="2px"><center>
                            <?php 
                                if ($querys->open == '0') {
                                    if ($querys->status_approval <> '1') {
                                        echo "<font color = 'red'><strong>DOI Checking</strong></font>";
                                    }else{
                                        echo "<font color = 'red'><strong>Finance Checking</strong></font>";
                                    }
                                }elseif ($querys->open == '3') {
                                    echo "<font color = 'red'><strong>expired</strong></font>";

                                }else{
                                    echo "<font color = 'blue'><strong>OPEN by ".$querys->username."</strong></font>";
                                }
                                
                             ?>
                            </center>
                            </td>
                            <!--<td><font size="2px"><?php echo $querys->note_acc; ?></td>-->
                            <!-- <td>
                                <center>
                                <?php
                                    echo anchor('all_transaction/open_credit_limit_detail/' . $querys->id, 'detail', ['class' => 'btn btn-success btn-sm']);
                                ?>                               
                                </center>

                            </td> -->

                        </tr>
                    <?php endforeach; ?>
                    
                    </tbody>
                    </table>
        </div>
    </div>
    

     <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                "ordering": true,
                "pageLength": 100,
                "order": [[ 0, "desc" ]],
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>

    


    </body>
</html>