<a href="<?php echo base_url()."assets_2/view_assets"; ?>" class='btn btn-sm btn-warning'>Kembali</a>
<br>
<br>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th width="1">
                                <font size="2px">No. Voucher
                            </th>
                            <th>
                                <font size="2px">Keterangan
                            </th>
                            <th>
                                <font size="2px">Harga
                            </th>
                            <th>
                                <font size="2px">Tanggal
                            </th>
                            <th>
                                <font size="2px">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($q_modal as $a) : 
                    $f = strtotime($a->tgl_entry);
                    $tgl_payroll = date("Y-m-d",$f);?>
                        <tr>
                            <td>
                                <font size="2px"><?php echo $a->nick_voucher; ?>
                            </td>
                            <td>
                                <font size="2px"><?php echo $a->keterangan; ?>
                            </td>
                            <td>
                                <font size="2px"><?php if ($a->debet > $a->kredit){
                                                    echo number_format ($a->debet);
                                                }else{
                                                    echo number_format ($a->kredit);
                                                } ?>
                            </td>
                            <td>
                                <font size="2px"><?php echo $a->tgl_entry; ?>
                            </td>
                            <td><button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#exampleModal<?php echo $a->nourut; ?>">
                                    Pilih
                                </button>
                            </td>
                        </tr>

                        <!----------------------------------------------------------- Modal ----------------------------------------------------------------------------------->

                        <div class="modal fade" id="exampleModal<?php echo $a->nourut; ?>" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg " role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Tambah Asset</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <?php echo form_open($url);?>
                                    <div class="modal-body">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">No. Voucher</label>
                                                <div class="col-sm-9">
                                                    <input class="form-control" value="<?php echo $a->nick_voucher; ?>"
                                                        type="text" name="nv" readonly required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Tanggal Payroll</label>
                                                <div class="col-sm-9">
                                                    <input class="form-control" type="date" name="tp"
                                                        value="<?php echo $tgl_payroll; ?>" readonly required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Keperluan :</label>
                                                <div class="col-sm-9">
                                                    <input class="form-control"
                                                        value="<?php echo $a->cos_description; ?>" type="text"
                                                        name="kpr" readonly required />
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Nilai Perolehan</label>
                                                <div class="col-sm-9">
                                                    <input class="form-control" value="<?php if ($a->debet > $a->kredit){
                                                    echo $a->debet;
                                                }else{
                                                    echo $a->kredit;
                                                } ?>" type="text" name="np" readonly required />
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">No. PO</label>
                                                <div class="col-sm-9">
                                                    <?php
                                                    $nopo=array();
                                                    $nopo['0']='--';
                                                    foreach($no_po->result() as $value)
                                                    {
                                                        $nopo[$value->no_po] = "$value->no_po - $value->username";
                                                    }
                                                    echo form_dropdown('nopo', $nopo,'','class="form-control"  id="user"');
                                                ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Nama Barang</label>
                                                <div class="col-sm-9">
                                                    <input class="form-control" value="<?php echo $a->keterangan; ?>"
                                                        type="text" name="nb" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">S/N</label>
                                                <div class="col-sm-9">
                                                    <input class="form-control" type="text" name="sn" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Jumlah Barang</label>
                                                <div class="col-sm-9">
                                                    <input class="form-control" type="number" name="jb" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Golongan</label>
                                                <div class="col-sm-9">
                                                    <?php
                                                    $gol=array('0.25'=>'GOL I','0.125'=>'GOL II','0.0625'=>'GOL III','0.05'=>'GOL IV','0'=>'GOL V');
                                                    echo isset($edit)?form_dropdown('gol',$gol,$golongan,"class=form-control"):form_dropdown('gol',$gol,'',"class=form-control");
                                                ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Group Asset</label>
                                                <div class="col-sm-9">
                                                    <?php
                                                    if(isset($query))
                                                    {
                                                        foreach($query->result() as $value)
                                                        {
                                                            $grup[$value->id]= $value->namagrup;
                                                        }
                                                    
                                                        echo isset($edit)?form_dropdown('grup',$grup,$grupid,"class=form-control"):form_dropdown('grup',$grup,'',"class=form-control");
                                                    }
                                                    
                                                ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <?php echo form_submit('submit','Simpan', 'class="btn btn-success"');?>
                                            <?php echo form_close();?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <!------------------------------------------------------------------------------------------------------------------------------------------------------->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th width="1">
                                <font size="2px">No. Voucher
                            </th>
                            <th>
                                <font size="2px">Keterangan
                            </th>
                            <th>
                                <font size="2px">Harga
                            </th>
                            <th>
                                <font size="2px">Tanggal
                            </th>
                            <th>
                                <font size="2px">
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-xs-11">&nbsp; </div>
        </div>
    </div>
</div>