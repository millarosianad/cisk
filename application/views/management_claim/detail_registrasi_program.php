
<?php 
    foreach ($get_registrasi_program->result() as $key) {
        $kategori = $key->kategori;
        $supp = $key->supp;
        $nama_program = $key->nama_program;
        $from = $key->from;
        $to = $key->to;
        $syarat = $key->syarat;
        $upload_jpg = $key->upload_jpg;
        $upload_pdf = $key->upload_pdf;
    }

?>

</div>


<div class="container">
    
<?php echo form_open_multipart($url); ?>

<div class="row">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

<!-- <div class="row mt-3">
    <div class="col-md-5">        
        <label for="kategori" class="form-label">Kategori</label>
        <input class="form-control form-control-md" type="text" name="kategori" value="<?= $kategori ?>">
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-5">        
        <label for="supp" class="form-label">Principal</label>        
        <input class="form-control form-control-md" type="text" name="supp" value="<?= $supp ?>">
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">     
        <label for="from" class="form-label">Periode</label>
        <div class="col-md-10 d-flex flex-row">
            <input class="form-control form-control-md" id="from" type="text" name="from" value="<?= $from ?>">
            <input class="form-control form-control-md" id="from" type="date" name="to" value="<?= $to ?>">
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-5">        
        <label for="nama_program" class="form-label">Nama Program</label>
        <input class="form-control form-control-md" id="nama_program" type="text" name="nama_program" value="<?= $nama_program ?>">
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-5">        
        <label for="syarat" class="form-label">Syarat Ketentuan</label>
        <textarea class="form-control" id="syarat" name="syarat" cols="5" rows="5"><?= $syarat; ?></textarea>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-5">        
        <label for="upload_jpg" class="form-label">Upload Dokumen (.jpg)</label>
        <img src="<?= base_url()."assets/uploads/management_claim/".$upload_jpg; ?>" height="70" width="70">
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-5">        
        <label for="upload_pdf" class="form-label">Upload Dokumen (.pdf)</label>
        <img src="<?= base_url()."assets/uploads/management_claim/".$upload_pdf; ?>">
    </div>
</div> -->

<div class="row mt-3">
    <div class="col-md-5">        
        <label for="kategori" class="form-label">Nomor Surat</label>
        <input class="form-control form-control-md" type="text" name="kategori" value="<?= $kategori ?>">
    </div>
</div>
    
</form>

</div>
</div>

<div class="container mt-4">

  <div class="card-block mt-5">
        <div class="row">
            <div class="col-md-12">

                <table id="example" class="display" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th style="background-color: maroon;" class="text-center col-auto"><font color="white">Principal</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        foreach ($get_registrasi_program->result() as $a) : ?>
                        <tr>
                            <td><?= $a->site_code; ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });
    </script>