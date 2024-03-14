<?php echo form_open($url); 
$id_nopo = $this->uri->segment('3');?>
<div>
    <a href="<?php echo base_url()."asn/view_do/$id_nopo"; ?>"class="btn btn-dark" role="button">
                kembali</a>
</div>
<br>
<?php foreach ($get_po_by_produk as $key) : ?>
<div class="card">
<br>
    <input type="hidden" name="id" value="<?php echo $key->id; ?>" class="form-control">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Kodeprod</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <input type="text" name="do_kodeprod" value="<?php echo $key->kodeprod; ?>" class="form-control" placeholder="Nama Divisi" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nama Produk</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <input type="text" name="namaDivisi" value="<?php echo $key->namaprod; ?>" class="form-control" placeholder="Nama Divisi" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Unit ASN</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <input type="text" name="namaDivisi" value="<?php echo $key->asn_unit; ?>" class="form-control" placeholder="" readonly>
                </div>
            </div>
        </div>
    </div>
    <?php
        $asn_tanggal_kirim = trim($key->asn_tanggal_kirim);
        $convert_asn_tanggal_kirim=strftime('%m/%d/%Y',strtotime($asn_tanggal_kirim));
    ?>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal Kirim</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <input type="text" name="do_tanggalKirim" value="<?php echo $convert_asn_tanggal_kirim; ?>" class="form-control mydatepicker" placeholder="">
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Unit DO </label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <input type="text" name="do_unit" value="<?php echo $key->do_unit; ?>" class="form-control" placeholder="">
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nama Expedisi </label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <input type="text" name="do_nama_expedisi" value="<?php echo $key->asn_nama_expedisi; ?>" class="form-control" placeholder="">
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">EST Lead Time</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <input type="text" name="do_est_lead_time" value="<?php echo $key->asn_est_lead_time; ?>" class="form-control" placeholder="">
                </div>
            </div>
        </div>
    </div>

    <?php
        $asn_eta = trim($key->asn_eta);
        $convert_asn_eta=strftime('%m/%d/%Y',strtotime($asn_eta));
    ?>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">ETA / Est Time Arrived</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <input type="text" name="do_eta" value="<?php echo $convert_asn_eta; ?>" class="form-control mydatepicker" placeholder="">
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label"></label>            
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <?php echo form_submit('submit','Simpan', 'class="btn btn-success"');?>
                <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>