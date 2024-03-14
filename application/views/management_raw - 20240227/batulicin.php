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
            <div class="col-md-6">
                <?php echo form_open_multipart($url_import); ?>

                <div class="form-group">
                    <label for="bulan">Pilih Bulan</label>
                    <input class="form-control" type="month" name="bulan" required/>
                </div>

                <div class="form-group">
                    <label for="import_transaksi">Import CSV</label>
                    <input class="form-control" type="file" name="file" required/>
                </div>

                <div class="form-group">
                    <a href="<?= base_url().'management_raw/template_batulicin' ?>" class="btn btn-outline-warning">download template</a>
                    <?php echo form_submit('submit', 'Import CSV', 'class="btn btn-primary"'); ?>
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
                            <th width="50%">Bulan</th>
                            <th>Count Raw</th>
                            <th>Count Mapping</th>
                            <th>Omzet Raw</th>
                            <th>Omzet Web</th>
                            <th>Created at</th>
                            <th>Created by</th>
                            <th>File Raw (click for download)</th>
                            <th>File Mapping</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_log_upload->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $a->site_code; ?></td>                            
                            <td><?= $a->branch_name; ?></td>                            
                            <td><?= $a->nama_comp; ?></td>    
                            <td><?= $a->bulan.' -'.$a->tahun; ?></td>                            
                            <td><?= $a->count_raw; ?></td>                            
                            <td><?= $a->count_mapping; ?></td>       
                            <td><?= number_format($a->omzet_raw); ?></td>       
                            <td><?= number_format($a->omzet_web); ?></td>       
                            <td><?= $a->created_at; ?></td>                            
                            <td><?= $a->username; ?></td>                                   
                            <td><a href="<?= base_url().'assets/uploads/management_raw/import/'.$a->filename ?>"><?= $a->filename; ?></a></td>                            
                            <td><a href="<?= base_url().'management_raw/download_raw/SSJD2/'.$a->signature ?>" class="btn btn-warning">download</a></td>           
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