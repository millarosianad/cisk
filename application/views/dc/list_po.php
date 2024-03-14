<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<!-- Load Datatables dan Bootstrap dari CDN -->
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

<style>
    th{
        font-size: 12px;
    }
    td{
        font-size: 12px;
    }
</style>

<?php 
    // var_dump($get_list_po);
?>


<div class="row">
    <div class="col-sm-6">
        <h4>Purchase Order MPM</h4>
        <hr>
        <table id="multi-colum-dt_po" class="table table-striped table-bordered nowrap">    
        <!-- <table id="myTable" class="table table-striped table-bordered table-hover">    -->
            <thead>     
                <tr>   
                    <th width="1%">No</th>
                    <th width="50%">nopo</font></th>                                           
                    <th width="50%">tglpo</font></th>                                           
                    <th width="50%">company</font></th>                                      
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach($get_list_po->result() as $key) : ?>
                <tr>    
                    <td><?= $no++; ?></td>
                    <td><?php echo $key->nopo; ?></td>    
                    <td><?php echo $key->tglpo; ?></td>    
                    <td><?php echo $key->company; ?></td>                                   
                </tr>
            <?php endforeach; ?>
            </tbody>                 
        </table>
    </div>
    <div class="col-sm-6">
        <h4>Surat Jalan US</h4>
        <hr>
        <table id="multi-colum-dt_do" class="table table-striped table-bordered nowrap">    
        <!-- <table id="myTable" class="table table-striped table-bordered table-hover">    -->
            <thead>     
                <tr>   
                    <th width="1%">No</th>
                    <th width="50%">nopo</font></th>                                           
                    <th width="50%">tglpo</font></th>                                           
                    <th width="50%">company</font></th>                                      
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach($get_list_po->result() as $key) : ?>
                <tr>    
                    <td><?= $no++; ?></td>
                    <td><?php echo $key->nopo; ?></td>    
                    <td><?php echo $key->tglpo; ?></td>    
                    <td><?php echo $key->company; ?></td>                                   
                </tr>
            <?php endforeach; ?>
            </tbody>                 
        </table>
    </div>
</div>


<div class="card table-card col-5">
    <div class="row">
        <!-- <div class="col text-center mt-3"> -->
        <div class="col mt-3">
            <h4>Purchase Order MPM</h4>
        </div>
    </div>
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt_tampil_full" class="table table-striped table-bordered nowrap">    
                <!-- <table id="myTable" class="table table-striped table-bordered table-hover">    -->
                    <thead>     
                        <tr>   
                            <th width="1%">No</th>
                            <th width="50%">nopo</font></th>                                           
                            <th width="50%">tglpo</font></th>                                           
                            <th width="50%">company</font></th>                                      
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach($get_list_po->result() as $key) : ?>
                        <tr>    
                            <td><?= $no++; ?></td>
                            <td><?php echo $key->nopo; ?></td>    
                            <td><?php echo $key->tglpo; ?></td>    
                            <td><?php echo $key->company; ?></td>                                   
                        </tr>
                    <?php endforeach; ?>
                    </tbody>                 
                </table>
            </div>
        </div> 
    </div>
</div>

<div class="card table-card col-5">
    <div class="row">
        <!-- <div class="col text-center mt-3"> -->
        <div class="col mt-3">
            <h4>Surat Jalan US</h4>
        </div>
    </div>
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt_tampil_full" class="table table-striped table-bordered nowrap">    
                <!-- <table id="myTable" class="table table-striped table-bordered table-hover">    -->
                    <thead>     
                        <tr>   
                            <th width="1%">No</th>
                            <th width="50%">nopo</font></th>                                           
                            <th width="50%">tglpo</font></th>                                           
                            <th width="50%">company</font></th>                                      
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach($get_list_po->result() as $key) : ?>
                        <tr>    
                            <td><?= $no++; ?></td>
                            <td><?php echo $key->nopo; ?></td>    
                            <td><?php echo $key->tglpo; ?></td>    
                            <td><?php echo $key->company; ?></td>                                   
                        </tr>
                    <?php endforeach; ?>
                    </tbody>                 
                </table>
            </div>
        </div> 
    </div>
</div>


<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>     
                        <tr>   
                            <tr>
                                <th colspan="2"><center>Masuk - Keluar</center></th>
                                <th colspan="4"><center>Data Purchase Order</center></th>
                                <th colspan="1"><center>Surat Jalan / DO</center></th>
                                <th colspan="1"><center>#</center></th>
                                <!-- <th colspan="7"><center>Data ASN</th> -->
                            </tr>
                            <th>Masuk</font></th>                                           
                            <th>Keluar</font></th>                                           
                            <th>Principal</font></th>                                           
                            <th>Company</font></th>                                           
                            <th>Tanggal PO</font></th>                                           
                            <th>Nomor PO</th>
                            <th>Nomor DO</th>
                            <th><center>Proses</th>                                      
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($get_po as $key) : ?>
                        <tr>    
                            <td><?php echo $key->kode_kartu_masuk; ?></td>    
                            <td><?php echo $key->kode_kartu_keluar; ?></td>    
                            <td><?php echo $key->namasupp; ?></td>    
                            <td><?php echo $key->company; ?></td>    
                            <td><?php echo $key->tglpo; ?></td>    
                            <td><?php echo $key->nopo; ?></td>
                            <td><?php echo $key->nodo." at ".$key->tgldo; ?></td>
                            <td>
                                <a href="<?php echo base_url()."dc/list_do/".$key->id."/".$key->supp."/".$key->nodo ?>" class="btn btn-primary btn-sm" role="button">masuk</a>
                                <a href="<?php echo base_url()."dc/list_kartu_stock"; ?>"class="btn btn-warning btn-sm" role="button">keluar</a>
                                <a href="<?php echo base_url()."dc/berita_acara"; ?>"class="btn btn-success btn-sm" role="button">berita acara</a>

                            </td>
                               
                        </tr>
                    <?php endforeach; ?>
                    </tbody>                 
                </table>
            </div>
        </div> 
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