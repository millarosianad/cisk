<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
    .batas{
        border: 1px dotted grey;
        border-radius: 5px;
    }

    th{
        font-weight: bold;
        background-color: #383838;
        border: 1px solid #383838;
        color: #f0f0f0;
        align-items: center;
        align-content: center;
        font-size: 13px;
        /* text-align: center; */
    }
    td{
        background-color: #ffffff;
        border: 1px solid #383838;
        font-size: 11px;
        
        /* align-items: center;
        align-content: center; */
    }

    table.dataTable th,
    table.dataTable td {
        white-space: nowrap;
    }

    

    a:link { text-decoration: none; }
    a:visited { text-decoration: none; }
    a:hover { text-decoration: none; }
    a:active { text-decoration: none; }
    
    .btn-submit {
        color: #f0f0f0;
        background-color: #383838;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-submit:hover {
        color: #f0f0f0;
        background-color: #365486;
    }

    .btn-hardcopy {
        color: #f0f0f0;
        background-color: #37B5B6;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-hardcopy:hover {
        color: black;
    }

    .btn-pendingmpm {
        color: #f0f0f0;
        background-color: #FE7A36;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-pendingprincipal {
        color: #f0f0f0;
        background-color: #D04848;
        border-radius: 10px;
        border: 2px solid black;
    }
    
    .btn-null {
        color: black;
        background-color: #F9EFDB;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-pendingdp {
        color: #f0f0f0;
        background-color: #7077A1;
    }
</style>

</div>

<div class="container">
    
<?php echo form_open_multipart($url); ?>

    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <?php 
                if($this->session->flashdata('pesan')){ ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $this->session->flashdata('pesan'); ?>
                    </div>
                <?php
                }elseif($this->session->flashdata('pesan_success')){ ?>
                    <div class="alert alert-success" role="alert">
                        <?= $this->session->flashdata('pesan_success'); ?>
                    </div>
                <?php
                }
            ?>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="supp" class="form-label">Principal (*)</label> 
        </div>
        <div class="col-md-4">
            <select id="supp" name="supp" class="form-control" required>
                <option value=""> -- pilih principal -- </option>
                <option value="001"> Deltomed </option>
                <option value="002"> Marguna </option>
                <option value="005"> Ultra Sakti </option>
                <option value="012"> Intrafood </option>
                <option value="013"> Strive </option>
                <option value="015"> MDJ </option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="from" class="form-label">Periode Program (*)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="from" type="date" name="from" required>
            <input class="form-control form-control-md" id="from" type="date" name="to" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="kategori" class="form-label">Kategori (*)</label>
        </div>
        <div class="col-md-4">
            <select id="kategori" name="kategori" class="form-control" required>
                <option value=""> -- pilih kategori -- </option>
                <option value="loyalty"> Loyalty </option>
                <option value="bonus_barang"> Bonus Barang</option>
                <option value="diskon_herbal"> Diskon Herbal</option>
                <option value="diskon_candy"> Diskon Candy</option>
                <option value="diskon"> Diskon</option>
                <option value="insentif"> Insentif </option>
                <option value="listing_fee"> Listing Fee </option>
                <option value="rafaksi"> Rafaksi </option>
                <option value="program MT"> Program MT </option>
                <option value="sewa_display"> Sewa Display </option>
                <option value="salesman_herbana"> Salesman Herbana </option>
                <option value="sample_promosi"> Sample Promosi </option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="status_template" class="form-label">Lampirkan Template tambahan ?  (*)</label>
        </div>
        <div class="col-md-4">
            <select id="status_template" name="status_template" class="form-control" required>
                <option value=""> -- pilih status template -- </option>
                <option value=1> Ya </option>
                <option value=0> Tidak</option>
            </select>
        </div>
    </div>

    <div class="row mt-3" hidden id="mydiv">
        <div class="col-md-2">
            <label for="nomor_surat" class="form-label">Template Program</label>
        </div>
        <div class="col-md-4">
            <input class="form-control form-control-md" type="file" id="template_program" name="template_program">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nomor_surat" class="form-label">Nomor Surat Program (*)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="nomor_surat" type="text" name="nomor_surat" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program" class="form-label">Nama Program (*)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="nama_program" type="text" name="nama_program" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="syarat" class="form-label">Syarat Ketentuan (*)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <textarea class="form-control" id="syarat" name="syarat" cols="5" rows="5" required></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="duedate" class="form-label">Deadline Ajuan Claim (*)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="duedate" type="date" name="duedate" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="upload_jpg" class="form-label">Upload Dokumen (.jpg)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="upload_jpg" type="file" name="upload_jpg">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="upload_pdf" class="form-label">Upload Dokumen (.pdf) (*)</label>
        </div>
        <div class="col-md-4 d-flex flex-row">
            <input class="form-control form-control-md" id="upload_pdf" type="file" name="upload_pdf" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            
        </div>
        <div class="col-md-4 d-flex flex-row">
            <button type="submit" class="btn btn-submit">Save Registrasi Program</button>
        </div>
    </div>
</form>

</div>
</div>

<div class="container mt-1">

    <div class="card-block mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <hr class="batas">
            </div>
        
            <div class="col-md-12 mt-4">

                <table id="example" class="display" style="overflow-x: scroll;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="width:60px" class="text-center col-3">#</th>
                            <th>Principal</th>
                            <th>Kategori</th>
                            <th>Periode</th>
                            <th>NomorSurat</th>
                            <th>NamaProgram</th>
                            <th>TemplateTambahan</th>
                            <th>Syarat</th>
                            <th>Deadline</th>
                            <th>CreatedBy</th>
                            <th style="width:120px" class="text-center col-3">Dokumen</th>
                            
                        </tr>
                    </thead>
                    <tbody>     
                        <?php $no = 1;
                        foreach ($get_registrasi_program->result() as $a) : ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td>
                                <a href="<?= base_url().'management_claim/edit_registrasi_program/'.$a->signature ?>" class="btn btn-pendingmpm">edit</a>
                                <a href="<?= base_url().'management_claim/delete_registrasi_program/'.$a->signature ?>" onclick="return confirm('Anda yakin menghapus data ini ?')" class="btn btn-pendingprincipal">del</a>
                            </td>
                            <td><?= $a->namasupp; ?></td>
                            <td><?= $a->kategori; ?></td>
                            <td><?= $a->from.' sd '.$a->to; ?></td>
                            <td><?= $a->nomor_surat; ?></td>
                            <td><?= $a->nama_program; ?></td>
                            <td>
                                <?php 
                                    if ($a->upload_template_program) { ?>
                                        <a href="<?= base_url().'assets/uploads/management_claim/'.$a->upload_template_program ?>"><?= $a->upload_template_program ?></a>
                                    <?php
                                    }else{ ?>
                                        <label class="form-label"><i>blank</i></label>    
                                    <?php
                                    }
                                ?>
                            </td>
                            <td><?= $a->syarat; ?></td>
                            <td><?= $a->duedate; ?></td>
                            <td><?= $a->username.' at '.$a->created_at; ?></td>
                            <td>
                                <?php 
                                    if ($a->upload_jpg) { ?>
                                        <a href="<?= base_url().'assets/uploads/management_claim/'.$a->upload_jpg ?>" class="btn btn-null" target="_blank">jpg</a>
                                    <?php
                                    }else{ ?>
                                        <a href="#" class="btn btn-sm btn-success rounded" style="background-color: darkslategray;">jpg</a>
                                    <?php
                                    }
                                ?>                                
                                <a href="<?= base_url().'assets/uploads/management_claim/'.$a->upload_pdf ?>" class="btn btn-null" target="_blank">pdf</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#example').DataTable(
                {
                    scrollX: true
                }
            );
        });
    </script>

    <script>    
        $("select[name = status_template]").on("change", function() {
            var status_template_terpilih = document.getElementById('status_template').value;
            let element = document.getElementById("mydiv");
            console.log(status_template_terpilih);
            if (status_template_terpilih == 1) { //jika ya
                document.getElementById("template_program").required = true;
                element.removeAttribute("hidden");
            }else{
                element.setAttribute("hidden", "hidden");
                document.getElementById('template_program').removeAttribute('required');
            }
        });
    </script>