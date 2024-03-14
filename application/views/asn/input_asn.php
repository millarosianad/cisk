<?php echo form_open($url);
$kodeprod=array();

// echo "<pre>";
// var_dump($get_po_by_produk);
// echo "</pre>";
foreach($get_po_by_produk as $value)
{
    $kodeprod[$value->kodeprod]= "$value->kodeprod - $value->namaprod - ($value->banyak_karton karton)";
    $nopo = $value->nopo;
    $tglpo = $value->tglpo;
    $status_closed = $value->status_closed;
    // var_dump($kodeprod);
} 
$id_po = $this->uri->segment('3');
?>
<div>
    <a href="<?php echo base_url()."asn/list_asn"; ?>"class="btn btn-dark btn-sm" role="button">
                kembali</a>
</div>
<br>
    <input type="hidden" name="id_po" value="<?php echo $id_po ?>" class="form-control">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">No.PO</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="nopo" value="<?php echo $nopo; ?>" class="form-control" readonly>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal PO</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="nopo" value="<?php echo $tglpo; ?>" class="form-control" readonly>                
                </div>
            </div>
        </div>
    </div>

    <hr />

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Pilih Produk</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <?php echo form_dropdown('kodeprod', $kodeprod, 'ALL','class="form-control" id="group"');?>             
                </div>
            </div>
        </div>
    </div>
    
    <!-- <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">No.ASN</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="no_asn" class="form-control" required>                
                </div>
            </div>
        </div>
    </div> -->

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal Kirim</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="date" name="tanggal_kirim" class="form-control mydatepicker" placeholder="" required>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Jumlah Karton</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="jumlah_karton" class="form-control" required>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Kunci Pemenuhan</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                    <div class="checkbox-color checkbox-primary">
                        <input id="checkbox4" type="checkbox"  name="status_pemenuhan" value="1">
                        <label for="checkbox4">
                            closed
                        </label>
                    </div>             
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Ekspedisi</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="nama_ekspedisi" class="form-control">                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal Tiba</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="date" name="tanggal_tiba" class="form-control mydatepicker" placeholder="">
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <textarea  id="keterangan" name="keterangan" cols="60" rows="5"></textarea>               
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label"></label>            
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-8">
                    <?php 
                        // echo $status_closed;
                        if ($status_closed == '1') {
                            
                        }else{
                            echo form_submit('submit','Simpan', 'class="btn btn-success"');
                        }
                    ?>
                <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>

    <br>
    <div class="card table-card">
        <div class="card-header">
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap ">    
                        <thead>     
                            <tr>    
                                <!-- <th>Tanggal PO</th>                                           
                                <th>No PO</th>
                                <th>No ASN</th> -->
                                <tr>
                                    <th colspan="3"><center>Data PO</center></th>
                                    <th colspan="7"><center>Data ASN</th>
                                </tr>
                                <th>Kodeprod</th>
                                <th>Namaprod</th>
                                <th>Jumlah Karton</th>
                                <th>Tanggal Kirim</th>
                                <th>Jumlah Karton</th>
                                <th>Ekspedisi</th>
                                <th>EST Lead Time</th>
                                <th>ETA/EST Time Arrived</th>
                                <th>Keterangan</th>
                                <th></th>                                       
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($get_po_by_produk_table_asn as $key) : ?>
                        <tr>                           
                            <!-- <td><?php echo $key->tglpo; ?></td>                                        
                            <td><?php echo $key->nopo; ?></td>
                            <td><?php echo $key->no_asn; ?></td> -->
                            <td><?php echo $key->kodeprod; ?></td>
                            <td><?php echo $key->namaprod; ?></td>
                            <td><?php echo $key->banyak_karton; ?></td>
                            <td><?php echo strftime('%d/%m/%Y',strtotime($key->tanggal_kirim)); ?></td>
                            <td><?php echo $key->jumlah_karton; ?></td>
                            <td><?php echo $key->nama_ekspedisi; ?></td>
                            <td><?php echo $key->est_lead_time; ?></td>
                            <td><?php echo strftime('%d/%m/%Y',strtotime($key->tanggal_tiba)); ?></td>
                            <td><?php echo $key->keterangan; ?></td>
                            <td><?php 
                                    // echo $status_closed;
                                    if ($status_closed == '1') { 
                                        
                                    }else{ ?>
                                    <a href="<?php echo base_url()."asn/edit_asn/$key->id/$key->id_asn" ?>" class="btn-sm btn-warning" role="button">Edit</a>
                                    <a href="<?php echo base_url()."asn/delete_produk_asn/$key->id/$key->id_asn" ?>" class="btn-sm btn-danger fa fa-trash" role="button" onclick="return confirm('Are you sure?')"></a>                      
                                <?php
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                        </tbody>
                        <tfoot><tr>    
                        <th>Kodeprod</th>
                        <th>Namaprod</th>
                        <th>Total Unit</th>
                        <th>Tanggal Kirim</th>
                        <th>Unit ASN(Satuan Terkecil)</th>
                        <th>Ekspedisi</th>
                        <th>EST Lead Time</th>
                        <th>ETA/EST Time Arrived</th>
                        <th>Keterangan</th>
                        <th></th>                                
                            </tr>
                        </tfoot>                    
                    </table>
                </div>
            </div> 
        </div>
    </div>