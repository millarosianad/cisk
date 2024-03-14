<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <a href="<?= base_url()."assets_2/view_assets"; ?>  " class="btn btn-dark" role="button"><span
                            class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        Kembali</a>
                    <a href="<?= base_url()."assets_2/qrcode/".$this->uri->segment(3).""; ?>  " target="_blank"
                        class="btn btn-success" role="button"><span class="glyphicon glyphicon-qrcode"
                            aria-hidden="true"></span> Generate QR Code</a>
                    <br><br>
                    <div class="row">
                        <!-------------- detail asset ----------------->
                        <div class="col-xl-6 col-md-12">
                            <?= form_open_multipart('assets_2/update_assets/' .$this->uri->segment(3)); ?>
                            <div class="card latest-update-card">
                                <div class="card-header">
                                    <h5>Detail Asset</h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="feather icon-maximize full-card"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <button type="button" class="btn btn-primary btn-sm" id="edit">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" id="mutasi">Mutasi</button>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">No Voucher</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="nv"
                                                value="<?= $asset->kode ;?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">No. PO</label>
                                        <div class="col-sm-8">
                                            <input class="form-control edit-input" type="text" name="nopo"
                                                value="<?= $asset->no_pengajuan ;?>" readonly />
                                            <?php 
                                                // echo "xxxx : ".$a->no_pengajuan;
                                                // die; 
                                                if ($asset->no_pengajuan == '0' || $asset->no_pengajuan == '' ){
                                                    $nopo=array();
                                                    $nopo['Hanya Mutasi']='Hanya Mutasi';
                                                    foreach($no_po->result() as $value)
                                                    {
                                                        $nopo[$value->no_po]= "$value->no_po - $value->username";
                                                    }
                                                }else{
                                                    $nopo=array();
                                                    $nopo[$asset->no_pengajuan]=$asset->no_pengajuan;
                                                    foreach($no_po->result() as $value)
                                                    {
                                                        $nopo[$value->no_po]= "$value->no_po - $value->username";
                                                    }
                                                }
                                                echo form_dropdown('nopo', $nopo,'','class="form-control edit-dropdown"');
                                                ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Nama Barang</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="nb"
                                                value="<?= $asset->namabarang ;?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">S/N</label>
                                        <div class="col-sm-8">
                                            <input class="form-control edit" type="text" name="sn"
                                                value="<?= $asset->sn ;?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Jumlah Barang</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="jb"
                                                value="<?= $asset->jumlah ;?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Keperluan</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="kpr"
                                                value="<?= $asset->untuk ;?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Tanggal Payroll</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="date" name="tp"
                                                value="<?= $asset->tglperol ;?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Nilai Perolehan</label>
                                        <div class="col-sm-8">
                                            <input class="form-control edit edit-num" type="text" name="np"
                                                value="Rp. <?= number_format($asset->np) ;?>" readonly />
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Tanggal Jual</label>
                                        <div class="col-sm-8">
                                            <?php if ($asset->tgljual == '1970-01-01'){
                                                $tgl_jual = '';
                                            }else{
                                                $tgl_jual = "$asset->tgljual";
                                            }
                                            ?>
                                            <input class="form-control edit" type="date" name="tj"
                                                value="<?= $tgl_jual ;?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Nilai Jual</label>
                                        <div class="col-sm-8">
                                            <input class="form-control edit edit-num" type="text" name="nj"
                                                value="Rp. <?= number_format($asset->nj) ;?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Deskripsi</label>
                                        <div class="col-sm-8">
                                            <input class="form-control edit" type="text" name="deskripsi"
                                                value="<?= $asset->deskripsi;?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Upload Faktur (<font color="red">*PDF
                                            </font>)</label>
                                        <div class="col-sm-8">
                                            <?php if($asset->upload_faktur != ''){
                                                echo anchor("assets_2/file/faktur_asset/".$asset->upload_faktur, 'Lihat Faktur', "class='btn btn-primary btn-sm'");
                                                echo '<br><br>';
                                            } ?>
                                            <input type="file" name="file" id="file" class="form-control edit" readonly/>
                                        </div>
                                    </div>
                                    <div align="right">
                                        <?= form_submit('submit','update', 'class="btn btn-success save"');?>
                                        <?= form_close();?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!----------------- mutasi -------------------->
                        <div class="col-xl-6 col-md-12 mutasi">
                            <div class="card latest-update-card">
                                <?= form_open_multipart('assets_2/input_mutasi_assets/' . $this->uri->segment(3)); ?>
                                <div class="card-header">
                                    <h5>Mutasi Asset</h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="feather icon-maximize full-card"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">User</label>
                                        <div class="col-sm-8">
                                            <?php
                                                $username=array();
                                                foreach($user->result() as $value)
                                                {
                                                    $username[$value->id]= $value->username.' - ('.$value->email.')';
                                                }
                                                
                                                echo form_dropdown('user', $username,'','class="form-control"  id="user"');
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Tanggal Mutasi</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="date" name="tm" required />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Alasan Mutasi</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" type="text" name="am" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Upload 1</label>
                                        <div class="col-sm-8">
                                            <font color="red">*barang</font>
                                            <input type="file" name="file" id="file" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Upload 2</label>
                                        <div class="col-sm-8">
                                            <font color="red">*barcode / serial number</font>
                                            <input type="file" name="file2" id="file2" class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"></label>
                                        <div class="col-sm-8">
                                            <?php if($asset->no_pengajuan == '0' || $asset->no_pengajuan == '' ){?>
                                            <a href='#' class="btn btn-success">update</a>
                                            <font color="red">No. PO harap diisi</font>
                                            <?php 
                                            }else{
                                            echo form_submit('submit','Simpan', 'class="btn btn-success"');
                                            echo form_close();
                                            }?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!----------------------------------- history --------------------------------->
                    <div class="row">
                        <div class="col-12">
                            <div class="col-xl-12 col-md-12">
                                <div class="card latest-update-card">
                                    <div class="card-header">
                                        <h5>History Mutasi</h5>
                                        <div class="card-header-right">
                                            <ul class="list-unstyled card-option">
                                                <li><i class="feather icon-maximize full-card"></i></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="card table-card">
                                            <div class="card-block">
                                                <div class="col-sm-12">
                                                    <div class="dt-responsive table-responsive">
                                                        <table id="multi-colum-dt"
                                                            class="table table-striped table-bordered nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th>
                                                                        <font size="2px">User
                                                                    </th>
                                                                    <th>
                                                                        <font size="2px">Nama Barang
                                                                    </th>
                                                                    <th>
                                                                        <font size="2px">Tanggal Mutasi
                                                                    </th>
                                                                    <th>
                                                                        <font size="2px">Status
                                                                    </th>
                                                                    <th>
                                                                        <font size="2px">
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($history as $a) : ?>
                                                                <tr>
                                                                    <td>
                                                                        <font size="2px"><?= $a->username; ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px"><?= $a->namabarang; ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px"><?= $a->tgl_mutasi; ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px"><?php if($a->status){
                                                                            echo "APPROVE";
                                                                        }else{
                                                                            echo "PENDING";
                                                                        } ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($a->status == '0' ){?>
                                                                        <button type="button"
                                                                            class="btn btn-primary btn-sm"
                                                                            onclick="editMutasi(<?= $a->id_mutasi; ?>)">
                                                                            Edit
                                                                        </button>

                                                                        <?php 
                                                                            if ($this->session->userdata('id') == '297' || $this->session->userdata('id') == '547'){?>
                                                                        <button type="button"
                                                                            class="btn btn-success btn-sm"
                                                                            onclick="approvMutasi(<?= $a->id_mutasi; ?>)">
                                                                            Approve
                                                                        </button>
                                                                        <?php }?>
                                                                        <?php }?>
                                                                        <a href="<?= base_url()."assets/file/bukti_mutasi/".$a->bukti_upload; ?>"
                                                                            class="btn btn-warning btn-sm" role="button"
                                                                            download>Gambar 1</a>
                                                                        <a href="<?= base_url()."assets/file/bukti_mutasi/".$a->bukti_upload2; ?>"
                                                                            class="btn btn-warning btn-sm" role="button"
                                                                            download>Gambar 2</a>
                                                                        <a href="<?= base_url()."assets_2/delete_mutasi/$a->id/$a->id_mutasi"; ?>"
                                                                            class="btn btn-danger btn-sm"
                                                                            role="button">Delete</a>
                                                                    </td>
                                                                </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    $this->load->view('assets_2/modal_mutasi');
    $this->load->view('assets_2/modal_approval');
?>
<script>
    $(document).ready(function () {
        $('.mutasi').hide();
        $('.edit-dropdown').hide();
        $('.save').hide();

        $("#edit").click(function () {
            $('.edit').attr('readonly', false);
            $('.edit-num').attr('type', 'number');
            $('.edit-dropdown').show();
            $('.edit-input').remove();
            $('.save').show();
        });

        $("#mutasi").click(function () {
            $('.mutasi').show();
        });
    });
</script>