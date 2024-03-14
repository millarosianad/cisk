<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>
    <div class="card-block">
    
        <div class="row">
            <div class="col-md-6">
                <?php echo form_open_multipart($url); ?>
                <div class="form-group">
                    <label for="olshopid">Olshop ID</label>
                    <input type="text" class="form-control" id="olshopid" name="olshopid" value="<?= $get_olshop->row()->olshopid; ?>">
                    <small id="emailHelp" class="form-text text-muted">tipe data dapat berubah string ataupun angka</small>
                </div>
                <div class="form-group">
                    <label for="nama_olshop">Nama Olshop</label>
                    <input type="text" class="form-control" id="nama_olshop" name="nama_olshop" value="<?= $get_olshop->row()->nama_olshop; ?>">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" class="form-control" id="status" required>
                        <option value="1" <?= ($get_olshop->row()->status == '1') ? 'selected' : ''; ?>> Aktif (default) </option>
                        <option value="0" <?= ($get_olshop->row()->status == '0') ? 'selected' : ''; ?>> Non Aktif </option>
                    </select>
                </div>                                    
                <div class="form-group">

                    <input type="hidden" class="form-control" name="signature" value="<?= $signature; ?>">
                    <?php echo form_submit('submit', 'Update Olshop', 'class="btn btn-primary"'); ?>
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
                            <th>Olshop ID</th>
                            <th width="100%">Nama Olshop</th>
                            <th>Status</th>
                            <th><center>#</center></th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_olshop_all->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->olshopid; ?></td>
                            <td><?= $a->nama_olshop; ?></td>
                            <td>
                                <?=
                                $a->status == '1' ? 'aktif' : 'non aktif';
                                ?>
                            </td>
                            <td>
                            <a href="<?= base_url().'mes/olshop_edit/'.$a->signature ?>" class="btn btn-warning">edit</a>
                            <a href="<?= base_url().'mes/olshop_delete/'.$a->signature ?>" class="btn btn-danger">delete</a>

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