<style>
    td{
        font-size: 11px;
    }
    th{
        font-size: 12px; 
    }
    a:link { text-decoration: none; }
    a:visited { text-decoration: none; }
    a:hover { text-decoration: none; }
    a:active { text-decoration: none; }
    
    .btn-submit {
        color: #f0f0f0;
        background-color: #383838;
    }

</style>

</div>
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>
</div>


<div class="container">

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

    <form action="<?= $url ?>">

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="supp" class="form-label">Principal</label> 
            </div>
            <div class="col-md-5">
                <select id="supp" name="supp" class="form-control" onchange="getTipe()" required>
                    <option value=""> -- pilih principal -- </option>
                    <option value="001" <?= $this->input->get('supp') == 001 ? 'selected' : '' ?>> Deltomed</option>
                    <option value="002" <?= $this->input->get('supp') == 002 ? 'selected' : '' ?>> Marguna </option>
                    <option value="005" <?= $this->input->get('supp') == 005 ? 'selected' : '' ?>> Ultra Sakti </option>
                    <option value="012" <?= $this->input->get('supp') == 012 ? 'selected' : '' ?>> Intrafood </option>
                    <option value="013" <?= $this->input->get('supp') == 013 ? 'selected' : '' ?>> Strive </option>
                    <option value="015" <?= $this->input->get('supp') == 015 ? 'selected' : '' ?>> MDJ </option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="kategori" class="form-label">Kategori</label>
            </div>
            <div class="col-md-5">
                <select id="kategori" name="kategori" class="form-control" required>
                    <option value=""> -- pilih kategori -- </option>
                    <option value="bonus_barang"> Bonus Barang</option>
                    <option value="diskon_herbal"> Diskon Herbal</option>
                    <option value="diskon_candy"> Diskon Candy</option>
                    <option value="diskon"> Diskon</option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="supp" class="form-label">DP</label> 
            </div>
            <div class="col-md-5">
                <select name="site_code" class="form-control" required>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="from" class="form-label">Periode</label> 
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <input type="date" name="from" id="from" class="form-control" value="<?= $this->input->get('from') ?>">
                    <input type="date" name="to" class="form-control" value="<?= $this->input->get('to') ?>">
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="supp" class="form-label"></label> 
            </div>
            <div class="col-md-5">
                <input type="submit" value="export data" class="btn btn-submit">
            </div>
        </div>

    </form>
</div>



</div>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/nama_comp_claim') ?>',
        data: '',
        success: function(hasil_branch) {
            $("select[name = site_code]").html(hasil_branch);
        }
    });
</script>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>