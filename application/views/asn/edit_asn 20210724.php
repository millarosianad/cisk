<?php echo form_open($url);
$kodeprod=array();
foreach($get_po_by_produk as $value)
{
    $kodeprod[$value->kodeprod]= "$value->kodeprod - $value->namaprod - ($value->banyak Unit)";
    $nopo = $value->nopo;
    $tglpo = $value->tglpo;
    $tglasn =trim($value->asn_tanggal_kirim);
    $convert_tglasn=date('Y-m-d',strtotime($tglasn));
    $tgleta =trim($value->asn_eta);
    $convert_tgleta=date('Y-m-d',strtotime($tgleta));
    $no_asn = $value->no_asn;
    $asn_unit = $value->asn_unit;
    $expedisi = $value->asn_nama_expedisi;
} 
$id_po = $this->uri->segment('3');
$id_asn = $this->uri->segment('4');
?>
<div>
    <a href="<?php echo base_url()."asn/list_asn"; ?>"class="btn btn-dark btn-sm" role="button">
                kembali</a>
</div>
<br>
    <input type="hidden" name="id_asn" value="<?php echo $id_asn ?>" class="form-control">
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
            <label class="col-sm-2 col-form-label">Pilih Produk</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <?php echo form_dropdown('asn_kodeprod', $kodeprod, 'ALL','class="form-control" id="group"');?>             
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">No.ASN</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="no_asn" value="<?php echo $no_asn; ?>" class="form-control" required>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal Kirim</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="date" name="asn_tanggalKirim" value="<?php echo $convert_tglasn; ?>" class="form-control mydatepicker" placeholder="" required>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Unit ASN</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="asn_unit" value="<?php echo $asn_unit; ?>" class="form-control" required>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Expedisi</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="asn_nama_expedisi" value="<?php echo $expedisi; ?>" class="form-control" required>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">ETA / Est Time Arrived</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="date" name="asn_eta" value="<?php echo $convert_tgleta; ?>" class="form-control mydatepicker" required>
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