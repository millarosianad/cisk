<?php 

// die;
$kodeprod=array();
foreach($get_po_by_produk as $value)
{
    $kodeprod[$value->kodeprod]= "$value->kodeprod - $value->namaprod - ($value->banyak_karton karton)";
    $nopo = $value->nopo;
    $tglpo = $value->tglpo;
    $status_closed = $value->status_closed;
    $batch_number = $value->batch_number;
    $jumlah_unit = $value->jumlah_unit;
    $jumlah_karton = $value->jumlah_karton;
    $tanggal_kirim = $value->tanggal_kirim;
    $nama_ekspedisi = $value->nama_ekspedisi;
    $tanggal_tiba = $value->tanggal_tiba;
    $keterangan = $value->keterangan;
    $id_po = $value->id;
    $status_pemenuhan = $value->status_pemenuhan;
    $id_asn = $value->id_asn;
    $supp = $value->supp;
    $nodo = $value->nodo;
    $ed = $value->ed;
    // $signature = $value->signature;
    // var_dump($kodeprod);
} 
// $id_po = $this->uri->segment('3');
if ($this->uri->segment('2') == 'input_asn') {
    $jumlah_unit = '';
    $tanggal_kirim = '';
    $batch_number = '';
    $nama_ekspedisi = '';
    $tanggal_tiba = '';
    $keterangan = '';
    $status_pemenuhan = '';
    $nodo = '';
    $ed = '';

    echo form_open($url_input);
}else{
    echo form_open($url_edit);
}

// echo "jumlah_unit : ".$jumlah_unit;

// die;
?>
<div>
    <a href="<?php echo base_url()."asn/list_asn"; ?>"class="btn btn-dark btn-sm" role="button">kembali</a>
</div>
<br>
    <input type="hidden" name="id_po" value="<?php echo $id_po ?>" class="form-control">
    <input type="hidden" name="id_asn" value="<?php echo $id_asn ?>" class="form-control">
    <input type="hidden" name="supp" value="<?php echo $supp ?>" class="form-control">
    <!-- <input type="text" name="no_asn" value="<?php echo $no_asn ?>" class="form-control"> -->
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
            <label class="col-sm-2 col-form-label">Pilih Produk (*)</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <?php echo form_dropdown('kodeprod', $kodeprod, 'ALL','class="form-control" id="group"');?>             
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Jumlah Karton (*)</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="jumlah_karton" class="form-control" value="<?php echo $jumlah_karton; ?>" required>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal Kirim (*)</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="date" name="tanggal_kirim" class="form-control mydatepicker" value="<?php echo $tanggal_kirim; ?>" required>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Batch Number</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="batch_number" value="<?php echo $batch_number; ?>" class="form-control">           
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">No Surat Jalan / DO (*)</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="nodo" value="<?php echo $nodo; ?>" class="form-control" required>           
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Expired Date</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="date" name="ed" value="<?php echo $ed; ?>" class="form-control">           
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Ekspedisi (*)</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="nama_ekspedisi" value="<?php echo $nama_ekspedisi; ?>" class="form-control" required>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal Tiba (*)</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="date" class="form-control mydatepicker" name="tanggal_tiba" value="<?php echo $tanggal_tiba; ?>" required>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <textarea class="form-control" id="keterangan" name="keterangan" cols="60" rows="5"><?php echo $keterangan; ?></textarea>               
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
                        <input id="checkbox4" type="checkbox"  name="status_pemenuhan" value="1" <?php echo ($status_pemenuhan == 1) ? 'checked' : '' ?>>
                        <!-- <input id="checkbox4" type="checkbox"  name="status_pemenuhan" value="" checked> -->
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
            <label class="col-sm-2 col-form-label"></label>            

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
                                    <th colspan="8"><center>Data Advanced Shipping Notes</center></th>
                                    <!-- <th colspan="7"><center>Data ASN</th> -->
                                </tr>
                                <th>Kodeprod</th>
                                <th>Namaprod</th>
                                <th>Jumlah Karton</th>                                
                                <th>Batch Number</th>
                                <th>No Surat Jalan/DO</th>
                                <th>ED</th>
                                <th>Tanggal Kirim</th>
                                <th>Jumlah Karton</th>
                                <th>Ekspedisi</th>
                                <th>EST Lead Time</th>
                                <th>ETA/EST Time Arrived</th>
                                <th>Keterangan</th>
                                <th><center>#</center></th>                                       
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
                            <td><?php echo $key->batch_number; ?></td>
                            <td><?php echo $key->nodo; ?></td>
                            <td><?php echo $key->ed; ?></td>
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
                                    <a href="<?php echo base_url()."asn/edit_asn/$key->signature/$key->id_asn/$key->supp/$key->kodeprod" ?>" class="btn-sm btn-warning  fa fa-edit" role="button"> Edit</a>
                                    <a href="<?php echo base_url()."asn/delete_produk_asn/$key->id/$key->id_asn" ?>" class="btn-sm btn-danger fa fa-trash" role="button" onclick="return confirm('Are you sure?')"> Delete</a>                      
                                <?php
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                        </tbody>          
                    </table>
                </div>
            </div> 
        </div>
    </div>