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
    <br><br><br>
    </div>

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-11">
            <h3><?php echo br(1).' '.$page_title; ?></h3><hr />
        </div>
    </div>

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-1">Company</div>
        <div class="col-xs-5">
            
            <?php echo form_open($url);
            $company=array();
            foreach($query->result() as $value)
            {
                $company[$value->id]= $value->company. ' / '.  $value->username;
            }
            echo form_dropdown('company',$company,'','class="form-control"');
            ?>
        </div>
    </div><br>

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-1"></div>
        <div class="col-xs-5">
            
            
            <?php echo form_submit('submit','Pilih Company','class="btn btn-primary"')?>
            <?php echo form_close(); ?>

            <a href="<?php echo base_url()."all_po/list_order" ?> " class="btn btn-default" role="button" target="blank">All Company</a>

            <a href="<?php echo base_url()."trans/po/show_supp" ?> " class="btn btn-success" role="button" target="blank">Create Order</a>
            

            </div>
        
    </div>


    <div class="col-xs-4">
        
    </div>

    </div>
    <hr>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width='1%'><center>No</font></th>
                            <th width='12%'><center>Company</th>
                            <th width='9%'><center>Branch</th>
                            <th width='10%'><center>SubBranch</th>
                            <th width='1%'><center>Tgl Pesan</th>
                            <th width='1%'><center>Tgl PO</th>
                            <th width='12%'><center>No PO</th>
                            <th width='1%'><center>Tipe</th>
                            <th width='10%'><center>Supplier</th>
                            <th width='1%'><center>Total</th>
                            <th width='1%'><center>Status</th>
                            <th width='8%'><center>#</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($hasil as $x) : ?>
                        <tr>        
                            <td><font size="2"><?php echo $x->id; ?></font></center></td>   
                            <td><font size="2"><?php echo $x->company; ?></td>            
                            <td><font size="2"><?php echo $x->branch_name; ?></td>
                            <td><font size="2"><?php echo $x->nama_comp; ?></td>
                            <td><font size="2"><?php echo $x->tglpesan; ?></td>
                            <td><?php 
                                if($x->tglpo == ''){
                                    echo "<font color='red'><i>belum ada";
                                }else{
                                    echo $x->tglpo;
                                } 
                                ?>
                            </td>
                            <td><center><?php 
                                if($x->nopo == ''){
                                    echo "<font color='red'><i><center>belum ada";
                                }else{
                                    echo $x->nopo;
                                }  
                                ?>
                            
                            </td>
                            <td><center><?php echo $x->tipe; ?></td>
                            <td><?php echo $x->namasupp; ?></td>
                            <td><?php echo number_format($x->total); ?></td>
                            
                            <td><?php 
                                if($x->status == '1')
                                {
                                    echo "<strong><font color='red'>logistic approval</font>";
                                }elseif($x->status == '2'){
                                    if ($x->open == '1') {
                                        if ($x->nopo == null) {
                                            echo "<strong><font color='blue'>Proses PO</font>";                                            
                                        }else{
                                            echo "<strong><font color='black'>Selesai</font>";        
                                        }
                                    }else{
                                        echo "<strong><font color='orange'>finance approval</font>";
                                    }
                                    
                                }else{
                                    echo "<strong><font color='red'>doi checking</font>";
                                }
                                ?>
                            </td>
                            <td><center>
                            <?php
                            if ($x->open == '1') {
                                echo anchor('all_po/list_order_detail/' . $x->id.'/'. $x->supp.'/'. $x->userid, ' ',array('class' => 'glyphicon glyphicon-menu-hamburger', 'target' => 'new'));
                                echo "   |   ";
                                echo anchor('trans/po/print/' . $x->id, ' ',array('class' => 'glyphicon glyphicon-print', 'target' => 'blank'));
                                echo "   |   ";
                                echo anchor('trans/po/email/' .$x->id.'/'.$x->userid.$x->supp, ' ',array('class' => 'glyphicon glyphicon-envelope', 'target' => 'blank'));
                                echo "   |   ";
                                echo anchor('all_po/export_csv/' . $x->id, ' ',array('class' => 'glyphicon glyphicon-floppy-disk', 'target' => 'blank'));
                                echo "   |   ";
                                echo anchor('all_po/delete_po/' . $x->id, ' ',array('class' => 'glyphicon glyphicon-remove','onclick'=>'return confirm(\'Are you sure?\')'));

                            }else{
                                echo anchor('all_po/list_order_detail/' . $x->id.'/'. $x->supp.'/'. $x->userid, ' ',array('class' => 'glyphicon glyphicon-menu-hamburger', 'target' => 'new'));
                                echo "   |   ";
                                echo anchor('trans/po/print/' . $x->id, ' ',array('class' => 'glyphicon glyphicon-print', 'target' => 'blank'));
                                echo "   |   ";
                                echo anchor('all_po/export_csv/' . $x->id, ' ',array('class' => 'glyphicon glyphicon-floppy-disk', 'target' => 'blank'));
                                echo "   |   ";
                                echo anchor('all_po/delete_po/' . $x->id, ' ',array('class' => 'glyphicon glyphicon-remove','onclick'=>'return confirm(\'Are you sure?\')'));
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
                "pageLength": 100,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>