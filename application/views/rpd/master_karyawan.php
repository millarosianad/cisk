<button class="btn btn-primary btn-primary"  onclick="addKaryawan()"><i
        class="fa fa-plus"></i>Tambah</button>

<div class="dt-responsive table-responsive mt-4">
    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
        <thead>
            <tr>
                <th>No</th>
                <th>Karyawan</th>
                <th>Email Karyawan</th>
                <th>Nama Atasan</th>
                <th>Email Atasan</th>
                <th>Status</th>
                <th>
                    <center>#
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; 
                foreach ($data_karyawan as $key) :
            ?>
            <tr>
                <td><?= $no++ ;?></td>
                <td><?= $key->nama_karyawan;?></td>
                <td><?= $key->email_karyawan;?></td>
                <td><?= $key->nama_atasan;?></td>
                <td><?= $key->email_atasan;?></td>
                <td>
                    <?php if($key->status == '1'){
                    echo 'Aktif';
                }else{
                    echo 'Tidak Aktif';
                };?>
                </td>
                <td><button class="btn btn-warning btn-sm" onclick="editKaryawan('<?= $key->id; ?>')">Edit</button> | <a href="<?= base_url().'rpd/master_karyawan_delete/'.md5($key->id); ?>" class="btn btn-danger btn-sm">Delete</a></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<?php
$this->load->view('rpd/modal_addkaryawan');
?>