
 

<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>
    <div class="card-block">
    
        <div class="row">
            <div class="col-md-6">
                <?php echo form_open_multipart($url); ?>
                <div class="form-group">
                    <label for="userID">User ID</label>
                    <input type="text" class="form-control" id="userID" name="userid" placeholder="Enter UserID">
                    <small id="emailHelp" class="form-text text-muted">tipe data dapat berubah string ataupun angka</small>
                </div>
                <div class="form-group">
                    <label for="nama_store">Nama User</label>
                    <input type="text" class="form-control" name="nama_user" placeholder="Enter nama user">
                </div>
                <div class="form-group">
                    <label for="nama_store">Status</label>
                    <select name="status" class="form-control" id="status" required>
                        <option value="1"> Aktif (default) </option>
                        <option value="0"> Non Aktif </option>
                    </select>
                </div>                                    
                <div class="form-group">
                    <?php echo form_submit('submit', 'Simpan User', 'class="btn btn-primary"'); ?>
                    <?php echo form_close(); ?>
                </div>  

            </div>
        </div>

    </div>

    <div class="card-block">
    
        <div class="row">
            <div class="col-md-12">

                <table id="example" class="table table-striped table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User ID</th>
                            <th width="100%">Nama User</th>
                            <th>Status</th>
                            <th><center>#</center></th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_user->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->userid; ?></td>
                            <td><?= $a->nama_user; ?></td>
                            <td>
                                <?=
                                $a->status == '1' ? 'aktif' : 'non aktif';
                                ?>
                            </td>
                            <td>
                            <a href="<?= base_url().'mes/user_edit/'.$a->signature ?>" class="btn btn-warning">edit</a>
                            <a href="<?= base_url().'mes/user_delete/'.$a->signature ?>" class="btn btn-danger">delete</a>

                            </td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>

            </div>
        </div>

    </div>


</div>



<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>