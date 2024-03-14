<button class="btn btn-primary" onclick="addBiaya()"><i class="fa fa-plus"></i>Tambah</button>
<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#add_kategori">
<i class="fa fa-plus"></i>Tambah Kategori
</button>

<div class="dt-responsive table-responsive mt-4">
    <!-- <table id="multi-colum-dt" class="table table-striped table-bordered nowrap"> -->
    <table id="multi-colum-dt" class="table table table-hover m-b-0">
        <thead>
            <tr>
                <th>No</th>
                <th>Karyawan</th>
                <th>Kategori</th>
                <th>Biaya</th>
                <th>
                    <center>#
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; 
                foreach ($data_biaya as $key):
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= ucwords($key->username); ?></td>
                <td><?= ucwords($key->nama_kategori); ?></td>
                <td>Rp. <?= number_format($key->biaya); ?></td>
                <td>
                    <center><button class="btn btn-warning btn-sm"
                            onclick="editBiaya('<?= $key->id; ?>')">Edit</button> | <a
                            href="<?= base_url().'rpd/master_biaya_delete/'.md5($key->id); ?>"
                            class="btn btn-danger btn-sm">Delete</a>
                    </center>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$this->load->view('rpd/modal_addbiaya');
$this->load->view('rpd/modal_kategori_biaya');
?>