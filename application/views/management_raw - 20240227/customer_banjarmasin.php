<style>

td {
  text-align: left;
  font-size: 11px;
}

th {
  text-align: left;
  font-size: 12px;
}

</style>

<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>
    <div class="card-block">
        <div class="row">
            <div class="col-md-7">
                <?php echo form_open_multipart($url_import); ?>
                <div class="form-group">
                    <label for="import_transaksi">Import CSV</label>
                    <input class="form-control" type="file" name="file" required/>
                </div>

                <div class="form-group">
                    <a href="<?= base_url().'management_raw/template_customer_banjarmasin' ?>" class="btn btn-outline-warning">download template customer banjarmasin</a>
                    <?php echo form_submit('submit', 'Import CSV Customer Banjarmasin', 'class="btn btn-primary"'); ?>
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
                            <th>Site Code</th>
                            <th>Branch</th>
                            <th>Namacomp</th>
                            <th>File Mapping</th>
                            <th>Count Raw</th>
                            <th>Count Mapping</th>
                            <th>Created at</th>
                            <th>Created by</th>
                            <th>File Raw (click for download)</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        // var_dump($get_log_customer_upload->result());
                        foreach ($get_log_customer_upload->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->site_code; ?></td>                            
                            <td><?= $a->branch_name; ?></td>                            
                            <td width="50%"><?= $a->nama_comp; ?></td>                               
                            <td width="10%"><a href="<?= base_url().'assets/uploads/management_raw/import/'.$a->filename ?>"><?= $a->filename; ?></a></td>                                                     
                            <td><?= $a->count_raw; ?></td>                            
                            <td><?= $a->count_mapping; ?></td>                            
                            <td><?= $a->created_at; ?></td>                            
                            <td><?= $a->username; ?></td>                      
                            <td><a href="<?= base_url().'management_raw/download_customer_banjarmasin/'.$a->signature ?>" class="btn btn-warning">download</a></td>      
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