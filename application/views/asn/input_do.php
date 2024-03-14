<?php echo form_open($url);
$kodeprod=array();
foreach($get_po_by_produk as $value)
{
    $kodeprod[$value->kodeprod]= "$value->kodeprod - $value->namaprod - ($value->banyak Unit)";
    $nopo = $value->nopo;
    $tglpo = $value->tglpo;
} 
$id_po = $this->uri->segment('3');
$no_asn = $this->uri->segment('4');?>
<div>
    <a href="<?php echo base_url()."asn/list_do/$id_po"; ?>"class="btn btn-dark btn-sm" role="button">
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

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">No.ASN</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="no_asn" value="<?php echo $no_asn; ?>" class="form-control" readonly>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Pilih Produk</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <?php echo form_dropdown('do_kodeprod', $kodeprod, 'ALL','class="form-control" id="group"');?>             
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">No. DO</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="no_do" class="form-control" reaquired>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal Kirim</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="date" name="do_tanggalKirim" class="form-control mydatepicker" placeholder="" required>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Unit DO</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="do_unit" class="form-control" required>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Expedisi</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="do_nama_expedisi" class="form-control" required>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">ETA / Est Time Arrived</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="date" name="do_eta" class="form-control mydatepicker" placeholder="" required>
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
                <?php echo form_submit('submit','Simpan', 'class="btn btn-success"');?>
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
                    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                        <thead>     
                            <tr>    
                                <th>No ASN</th>
                                <th>No DO</th>
                                <th>Tanggal Kirim</th>
                                <th>Kodeprod</th>
                                <th>Namaprod</th>
                                <th>Total Unit</th>
                                <th>Unit ASN</th>
                                <th>Unit DO</th>
                                <th>Expedisi</th>
                                <th>EST Lead Time</th>
                                <th>ETA/EST Time Arrived</th>
                                <th></th>                                       
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($get_po_by_produk_table_do as $key) : ?>
                        <tr>        
                            <td><?php echo $key->no_asn; ?></td>                   
                            <td><?php echo $key->no_do; ?></td>
                            <td><?php echo strftime('%d/%m/%Y',strtotime($key->do_tanggal_kirim)); ?></td>
                            <td><?php echo $key->kodeprod; ?></td>
                            <td><?php echo $key->namaprod; ?></td>
                            <td><?php echo $key->banyak; ?></td>
                            <td><?php echo $key->asn_unit; ?></td>
                            <td><?php echo $key->do_unit; ?></td>
                            <td><?php echo $key->do_nama_expedisi; ?></td>
                            <td><?php echo $key->do_est_lead_time; ?></td>
                            <td><?php echo strftime('%d/%m/%Y',strtotime($key->do_eta)); ?></td>
                            <td>
                                <a href="<?php echo base_url()."asn/edit_do/$key->id/$key->no_asn/$key->id_do" ?>" class="btn-sm btn-warning" role="button">Edit</a>
                                <a href="<?php echo base_url()."asn/delete_produk_do/$key->id/$key->no_asn/$key->id_do" ?>" class="btn-sm btn-danger fa fa-trash" role="button" onclick="return confirm('Are you sure?')"></a>                              
                            </td>
                        </tr>
                    <?php endforeach; ?>
                        </tbody>
                        <tfoot><tr>    
                                <th>No ASN</th>
                                <th>No DO</th>
                                <th>Tanggal Kirim</th>
                                <th>Kodeprod</th>
                                <th>Namaprod</th>
                                <th>Total Unit</th>
                                <th>Unit ASN</th>
                                <th>Unit DO</th>
                                <th>Expedisi</th>
                                <th>EST Lead Time</th>
                                <th>ETA/EST Time Arrived</th>
                                <th></th>                                     
                            </tr>
                        </tfoot>                    
                    </table>
                </div>
            </div> 
        </div>
    </div>