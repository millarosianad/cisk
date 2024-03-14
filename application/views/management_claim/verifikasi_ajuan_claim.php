<style>
    td{
        font-size: 11px;
    }
    th{
        font-size: 12px; 
    }
</style>

<?php
foreach ($site_code as $a) {
    $site_dp = $a->site_code;
    $subbranch_dp = $a->nama_comp;
    $company_dp = $a->company;
    $site[$a->site_code] = $a->nama_comp.' - '.$a->site_code;
}
?>

</div>

<div class="container">

<div class="row">
    <div class="col-md-12 mb-5 border border-primary">
        <div class="row mt-4"> 
            <div class="col-md-12 text-center">      
                <h5>Detail Ajuan Claim DP</h5>
            </div> 
        </div>


        <div class="row mt-4"> 
            <div class="col-md-6">      
                <div class="form-group ml-4 row">
                    <label for="nomor_ajuan" class="col-sm-3 col-form-label">Nomor Ajuan</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= $get_registrasi_program->row()->nomor_ajuan ?>" readonly>
                    </div>
                </div>
            </div> 

            <div class="col-md-6">      
                <div class="form-group ml-4 row">
                    <label for="nomor_ajuan" class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= $get_registrasi_program->row()->nama_status ?>" readonly>
                    </div>
                </div>
            </div> 
        </div>

        <div class="row mt-2"> 
            <div class="col-md-6">      
                <div class="form-group ml-4 row">
                    <label for="nomor_ajuan" class="col-sm-3 col-form-label">Subbranch</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= $get_registrasi_program->row()->nama_comp ?>" readonly>
                    </div>
                </div>
            </div> 

            <div class="col-md-6">      
                <div class="form-group ml-4 row">
                    <label for="nomor_ajuan" class="col-sm-3 col-form-label">Pengirim | Email</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= $get_registrasi_program->row()->email_pengirim ?>" readonly>
                    </div>
                </div>
            </div> 

        </div>

        <div class="row mt-2"> 
            <div class="col-md-6">      
                <div class="form-group ml-4 row">
                    <label for="nomor_ajuan" class="col-sm-3 col-form-label">File Excel & Zip</label>
                    <div class="col-sm-9">
                        <a href="<?= base_url().'assets/uploads/management_claim/'.$get_registrasi_program->row()->ajuan_excel ?>" class="btn btn-sm btn-outline-success" target="_blank">excel</a>
                        <a href="<?= base_url().'assets/uploads/management_claim/'.$get_registrasi_program->row()->ajuan_zip ?>" class="btn btn-sm btn-outline-info" target="_blank">zip</a>
                    </div>
                </div>
            </div> 
        </div>
    </div>


    <div class="col-md-12 mb-5 border border-primary">
        <div class="row mt-4 mb-2"> 
            <div class="col-md-12 text-center">      
                <h5>Verifikasi oleh MPM</h5>
            </div> 
        </div>

        <?php foreach ($get_verifikasi_ajuan->result() as $key) : ?>

        <div class="row mt-4"> 
            <div class="col-md-6">      
                <div class="form-group ml-4 row">
                    <label for="nomor_ajuan" class="col-sm-3 col-form-label">Created At</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= $key->created_at ?>" readonly>
                    </div>
                </div>
            </div> 

            <div class="col-md-6">      
                <div class="form-group ml-4 row">
                    <label for="nomor_ajuan" class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= $key->nama_status ?>" readonly>
                    </div>
                </div>
            </div> 
        </div>

        <div class="row mt-2"> 
            <div class="col-md-6">      
                <div class="form-group ml-4 row">
                    <label for="nomor_ajuan" class="col-sm-3 col-form-label">Catatan</label>
                    <div class="col-sm-9">
                        <textarea name="catatan_verifikasi" cols="30" rows="5" class="form-control" readonly><?= $key->catatan_verifikasi ?></textarea>
                    </div>
                </div>
            </div> 
            <div class="col-md-6">      
                <div class="form-group ml-4 row">
                    <label for="nomor_ajuan" class="col-sm-3 col-form-label">Created by</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= $key->username ?>" readonly>
                    </div>
                </div>
            </div> 
        </div>

        <hr>
        
        <?php endforeach ?>

    </div>

</div>
    
<?php echo form_open_multipart($url); ?>
<div class="row">
    <div class="col-md-6 mb-5">
        <div class="col-md-12 mt-4 mb-4 ml-4">
            <h4>Input Verifikasi</h4>
        </div><hr>
        
        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
            </div>
            <div class="col-md-8">
                <select id="status" name="status" class="form-control" required>
                <option value=""> -- pilih status -- </option>
                <option value="3"> On MPM Check </option>
                <option value="4"> On Principal Check </option>
                <option value="5"> Reject Principal </option>
                <option value="6"> Approve </option>
                <option value="7"> DP Kirim DN (Debit Note / Faktur Pajak) </option>
                <option value="8"> Finance (Principal kirim ke MPM) </option>
                <option value="9"> Finance (MPM kirim ke DP) </option>
            </select>
            </div>
        </div>

        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="tanggal_verifikasi" class="form-label">Tanggal</label>
            </div>
            <div class="col-md-8 d-inline">
                <input type="date" class="form-control d-inline" name="tanggal">
            </div>
        </div>

        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="catatan_verifikasi" class="form-label">Catatan/Ket</label>
            </div>
            <div class="col-md-8">
                <textarea name="catatan_verifikasi"cols="30" rows="5" class="form-control"></textarea>
            </div>
        </div>

        <div class="col-md-12 mt-3 d-flex justify-content-center">     
            <div class="col-md-3">
                <label for="pengirim" class="form-label"></label>
            </div>
            <div class="col-md-8">
                <input type="hidden" name="signature_ajuan" value="<?= $signature_ajuan ?>">
                <input type="hidden" name="nomor_ajuan" value="<?= $get_registrasi_program->row()->nomor_ajuan ?>">

                <?php 
                    if ($this->session->userdata('id') == '297' || $this->session->userdata('id') == '444') { ?>
                        <input type="submit" class="btn btn-outline-primary" value="update status verifikasi">
                    <?php 
                    }
                ?>

            </div>
        </div>
    </div>
</div>
</form>




</div>
</div>
