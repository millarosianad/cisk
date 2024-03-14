<div class="content-body">

            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Home</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $title; ?></h4>
                                <hr>
                                
                                <?php foreach ($get_po_by_produk as $key) { ?>
                                 
                                    <?php echo form_open($url);?>
                                    <input type="hidden" name="id" value="<?php echo $key->id; ?>" class="form-control">
                                    <div class="col-lg-12">                                        
                                        <div class="form-group row">
                                            <label for="divisi" class="col-sm-2 col-form-label">Kodeprod</label>                                        
                                            <div class="col-sm-5">
                                                <input type="text" name="asn_kodeprod" value="<?php echo $key->kodeprod; ?>" class="form-control" placeholder="Nama Divisi" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">                                        
                                        <div class="form-group row">
                                            <label for="divisi" class="col-sm-2 col-form-label">Namaprod</label>                                        
                                            <div class="col-sm-5">
                                                <input type="text" name="namaDivisi" value="<?php echo $key->namaprod; ?>" class="form-control" placeholder="Nama Divisi" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">                                        
                                        <div class="form-group row">
                                            <label for="divisi" class="col-sm-2 col-form-label">Unit</label>                                        
                                            <div class="col-sm-5">
                                                <input type="text" name="namaDivisi" value="<?php echo $key->banyak; ?>" class="form-control" placeholder="" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    $asn_tanggal_kirim = trim($key->asn_tanggal_kirim);
                                    $convert_asn_tanggal_kirim=strftime('%m/%d/%Y',strtotime($asn_tanggal_kirim));
                                    ?>

                                    <div class="col-lg-12">                                        
                                        <div class="form-group row">
                                            <label for="divisi" class="col-sm-2 col-form-label">Tanggal Kirim</label>                                        
                                            <div class="col-sm-5">
                                                <input type="text" name="asn_tanggalKirim" value="<?php echo $convert_asn_tanggal_kirim; ?>" class="form-control mydatepicker" placeholder="" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">                                        
                                        <div class="form-group row">
                                            <label for="divisi" class="col-sm-2 col-form-label">Unit ASN</label>                                        
                                            <div class="col-sm-5">
                                                <input type="text" name="asn_unit" value="<?php echo $key->asn_unit; ?>" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">                                        
                                        <div class="form-group row">
                                            <label for="divisi" class="col-sm-2 col-form-label">Nama Expedisi</label>                                        
                                            <div class="col-sm-5">
                                                <input type="text" name="asn_nama_expedisi" value="<?php echo $key->asn_nama_expedisi; ?>" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">                                        
                                        <div class="form-group row">
                                            <label for="divisi" class="col-sm-2 col-form-label">EST Lead Time</label>                                        
                                            <div class="col-sm-5">
                                                <input type="text" name="asn_est_lead_time" value="<?php echo $key->asn_est_lead_time; ?>" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    $asn_eta = trim($key->asn_eta);
                                    $convert_asn_eta=strftime('%m/%d/%Y',strtotime($asn_eta));
                                    ?>

                                    <div class="col-lg-12">                                        
                                        <div class="form-group row">
                                            <label for="divisi" class="col-sm-2 col-form-label">ETA / Est Time Arrived</label>                                        
                                            <div class="col-sm-5">
                                                <input type="text" name="asn_eta" value="<?php echo $convert_asn_eta; ?>" class="form-control mydatepicker" placeholder="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">                                        
                                        <div class="form-group row">
                                            <label for="divisi" class="col-sm-2 col-form-label"></label>
                                        
                                            <div class="col-sm-5">
                                            <input type="submit" name="submit" value="Submit" id="submit" class="btn btn-primary">
                                            </div>
                                        </div>
                                    </div>

                                    <?php echo form_close(); ?>

                                    <hr>

                                <?php } ?>

                                <br><br>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>                                                
                                                <th>Tanggal PO</th>                                           
                                                <th>No PO</th>
                                                <th>Branch</th>
                                                <th>SubBranch</th>
                                                <th>Company</th>
                                                <th>Tipe</th>
                                                <th>Kodeprod</th>
                                                <th>Namaprod</th>
                                                <th>Total Unit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <!-- <?php    var_dump($get_po); ?> -->
                                        <?php foreach ($get_po as $key) { ?>
                                            <tr>        
                                                <td><?php echo $key->tglpo; ?></td>                                        
                                                <td><?php echo $key->nopo; ?></td>
                                                <td><?php echo $key->branch_name; ?></td>
                                                <td><?php echo $key->nama_comp; ?></td>
                                                <td><?php echo $key->company; ?></td>
                                                <td><?php echo $key->tipe; ?></td>
                                                <td><?php echo $key->kodeprod; ?></td>
                                                <td><?php echo $key->namaprod; ?></td>
                                                <td><?php echo $key->banyak; ?></td>
                                            </tr>
                                        <?php } ?>
                                            
                                        <?php  ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Tanggal PO</th>                                           
                                                <th>No PO</th>
                                                <th>Branch</th>
                                                <th>SubBranch</th>
                                                <th>Company</th>
                                                <th>Tipe</th>
                                                <th>Kodeprod</th>
                                                <th>Namaprod</th>
                                                <th>Total Unit</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #/ container -->
        </div>

<!--         
    <script type="text/javascript">
    $(document).ready(function(){
        alert("Hello");
        // $("#submit").click(function(){

        //     $('#namaDivisiError').html('');

        //     if($("#namaDivisi").val() == '') {
        //         $("#namaDivisiError").html(' * pilih divisi. ');
        //         return false;
        //     }
        // }
    }
    </script> -->