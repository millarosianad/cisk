<?php echo form_open($url);
$kodeprod=array();
foreach($get_po_by_produk as $value)
{
    $kodeprod[$value->kodeprod]= "$value->kodeprod - $value->namaprod - ($value->banyak unit)";
    $nopo = $value->nopo;
    $tglpo = $value->tglpo;
    $tanggal_kirim =trim($value->tanggal_kirim);
    $convert_tglasn=date('Y-m-d',strtotime($tanggal_kirim));
    $tanggal_tiba =trim($value->tanggal_tiba);
    $convert_tgleta=date('Y-m-d',strtotime($tanggal_tiba));
    $no_asn = $value->no_asn;
    $jumlah_unit = $value->jumlah_unit;
    $jumlah_karton = $value->jumlah_karton;
    $nama_ekspedisi = $value->nama_ekspedisi;
    $keterangan = $value->keterangan;
    $status_pemenuhan = $value->status_pemenuhan;
} 
$id_po = $this->uri->segment('3');
$id_asn = $this->uri->segment('4');
$supp = $this->uri->segment('5');
?>
<div>
    <a href="<?php echo base_url()."asn/list_asn"; ?>"class="btn btn-dark btn-sm" role="button">
                kembali</a>
</div>
<br>
    <input type="hidden" name="id_asn" value="<?php echo $id_asn ?>" class="form-control">
    <input type="hidden" name="id_po" value="<?php echo $id_po ?>" class="form-control">
    <input type="hidden" name="supp" value="<?php echo $supp ?>" class="form-control">
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
                <?php echo form_dropdown('asn_kodeprod', $kodeprod, 'ALL','class="form-control" id="group" readonly');?>             
                </div>
            </div>
        </div>
    </div>
    
    <!-- <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">No.ASN</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="no_asn" value="<?php echo $no_asn; ?>" class="form-control" required>                
                </div>
            </div>
        </div>
    </div> -->

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal Kirim</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="date" name="tanggal_kirim" value="<?php echo $convert_tglasn; ?>" class="form-control mydatepicker" placeholder="" required>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Jumlah Karton</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="text" name="jumlah_karton" value="<?php echo $jumlah_karton; ?>" class="form-control" required>                
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
                        <?php 
                        // echo "status pemenuhan : ".$status_pemenuhan;
                        if ($status_pemenuhan == 1) { ?>
                            <input id="checkbox4" type="checkbox"  name="status_pemenuhan" value="1" checked>
                        <?php 
                        }else{ ?>
                            <input id="checkbox4" type="checkbox"  name="status_pemenuhan" value="1">
                        <?php
                        }
                        ?>
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
                <input type="text" name="nama_ekspedisi" value="<?php echo $nama_ekspedisi; ?>" class="form-control" required>                
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">ETA / Est Time Arrived</label>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <input type="date" name="tanggal_tiba" value="<?php echo $convert_tgleta; ?>" class="form-control mydatepicker" required>
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
            <label class="col-sm-2 col-form-label"></label>            
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-8">
                <?php echo form_submit('submit','Update', 'class="btn btn-success"');?>
                <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>