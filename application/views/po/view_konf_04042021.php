<!-- Load Datatables dan Bootstrap dari CDN -->
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">



<h2>
<?php echo form_label($page_title);?>
</h2><hr />

<pre>
    <b>Panduan Update Status PO : !!</b>
     - Klik <button type="button" class="btn btn-primary btn-sm">terima</button> = jika barang tersebut sudah diterima dalam kondisi baik/normal
     
     - Klik <button type="button" class="btn btn-danger btn-sm">batal</button> = jika barang tersebut tidak diterima / akan di retur / lainnya
     
     - Klik <button type="button" class="btn btn-default btn-sm">reset</button> = untuk mengembalikan status pengiriman barang ke kondisi semula
     
     <b>Status Barang : !!</b>
     - <font color="black"><b>dalam perjalanan</b></font> = berarti barang masih dalam perjalanan. 
        Total unit barang ini akan dijadikan sebagai barang GIT (Goods in transit) dalam perhitungan Replineshment.
     - <font color="blue"><b>sudah diterima</b></font> = berarti barang sudah diterima oleh DP dalam kondisi baik/normal
     - <font color="red"><b>barang tidak sampai</b></font> = berarti barang tsb : 
        1. mungkin diterima tapi dalam kondisi rusak sehingga harus di retur, 
        2. mungkin tidak pernah di kirim oleh pabrik
     
     
</pre>
<hr>
<?php $no = 1; ?>
<div class="row">        
        <div class="col-xs-12">
            
            <div class="col-xs-12">
                <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="5%"><font size="2px">no</th>
                            <th width="5%"><font size="2px">Kodeprod</th>
                            <th width="30%"><font size="2px">Namaprod</th>
                            <th width="5%"><font size="2px">QTY</th>
                            <th width="5%"><font size="2px">Berat(Kg)</th>
                            <th width="10%"><font size="2px"><center>Status</center></th>
                            <th width="20%"><font size="2px"><center>Ubah Status</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($query as $query) : ?>
                        <tr>        
                            <td width="1"><font size="2px"><center><?php echo $no++; ?></center></td>         
                            <td width="1"><font size="2px"><?php echo $query->kodeprod; ?></td>
                            <td width="1"><font size="2px"><?php echo $query->namaprod; ?></td>
                            <td width="1"><font size="2px"><?php echo $query->banyak; ?></td>
                            <td width="1"><font size="2px"><?php echo $query->subberat; ?></td>    
                            <td width="1"><font size="2px"><?php  
                            if($query->nopo == ''){                                
                                echo "po belum di proses";
                            }else{                                
                                if ($query->status_terima == '1') {
                                    echo "<font color=blue><strong>sudah diterima</strong></font>";
                                }elseif($query->status_terima == '2'){
                                    echo "<font color=red><strong>barang tidak sampai</strong></font>";
                                }                                
                                else {
                                    echo "<font color=black><strong>dalam perjalanan</strong></font>";
                                }
                            }

                            ?>                            
                            </td>     
                            <td width="1"><font size="2px">
                            <?php
                            if($query->nopo == '')
                            {
                                echo "<center>po belum di proses</center>";
                            }else{

                                if ($query->status_terima == null || $query->status_terima == '0' ) {
                                    ?>
                                        <center>                                
                                        <a href="<?php echo base_url()."all_po/updateStatusBarang/1/".$query->id."/".$query->kodeprod; ?>  " 
                                        class="btn btn-primary" role="button"> terima</a> - 
                                        <a href="<?php echo base_url()."all_po/updateStatusBarang/2/".$query->id."/".$query->kodeprod; ?>  " 
                                        class="btn btn-danger" role="button"> batal</a>
                                        
                                    <?php
                                } else {
                                    ?>
                                    <center>
                                        <a href="<?php echo base_url()."all_po/updateStatusBarang/0/".$query->id."/".$query->kodeprod; ?>  " 
                                        class="btn btn-default" role="button"> reset</a>
                                    </center>
                                    <?php
                                }

                            }

                                
                                
                            ?>
                            
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <th colspan="3">Total Berat<th>
                    <?php foreach($proses as $proses) : ?>
                        <th width="1" font size="2px"><?php echo $proses->tot_berat; ?></th>
                    <?php endforeach; ?>
                    <th colspan="1"><th>
                    </tfoot>
                </table>
            </div>
        </div> 
    </div>

     <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                
            });
        });
        </script>

        <!--jquery dan select2-->
        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/select2/js/select2.min.js') ?>"></script>
        <script>
            $(document).ready(function () {
                $(".select2").select2({
                    placeholder: "Please Select"
                });
            });
        </script>
    </body>
</html>