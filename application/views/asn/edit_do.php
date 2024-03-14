<?php echo form_open($url);
$kodeprod=array();
foreach($get_po_by_produk as $value)
{
    $kodeprod[$value->kodeprod]= "$value->kodeprod - $value->namaprod - ($value->banyak Unit)";
    $nopo = $value->nopo;
    $tglpo = $value->tglpo;
    $no_do = $value->no_do;
    $do_unit = $value->do_unit;
    $expedisi = $value->do_nama_expedisi;
    $est = $value->do_est_lead_time;
    $tgldo =trim($value->do_tanggal_kirim);
    $convert_tgldo=date('Y-m-d',strtotime($tgldo));
    $tgleta =trim($value->do_eta);
    $convert_tgleta=date('Y-m-d',strtotime($tgleta));
} 
$id_po = $this->uri->segment('3');
$no_asn = $this->uri->segment('4');
$id_do = $this->uri->segment('5');
?>
<div>
    <a href="<?php echo base_url()."do/list_do"; ?>"class="btn btn-dark btn-sm" role="button">
                kembali</a>
</div>
<br>
    <input type="hidden" name="id_do" value="<?php echo $id_do ?>" class="form-control">
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
            <label class="col-sm-2 col-form-label">No.DO</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="no_do" value="<?php echo $no_do; ?>" class="form-control" required>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal Kirim</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="date" name="do_tanggalKirim" value="<?php echo $convert_tgldo; ?>" class="form-control mydatepicker" placeholder="" required>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Unit do</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="do_unit" value="<?php echo $do_unit; ?>"  class="form-control" required>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Expedisi</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="do_nama_expedisi" value="<?php echo $expedisi; ?>" class="form-control" required>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">ETA / Est Time Arrived</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="date" name="do_eta" value="<?php echo $convert_tgleta; ?>" class="form-control mydatepicker" placeholder="" required>
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