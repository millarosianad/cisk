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

<div class="container">
    <!-- <div class="card-header">
        <h5><?= $title; ?></h5>
    </div> -->
    <div class="card-block">
        <div class="row">
            <div class="col-md-6">
                <?php echo form_open_multipart($url); ?>
                
                <div class="row mt-3">
                <div class="col-md-2">
                    <label for="supp" class="form-label">DP</label> 
                </div>
                <div class="col-md-5">
                    <select name="site_code" class="form-control">
                    </select>
                </div>
                </div>
                <div class="form-group">
                    <label for="bulan">Pilih Bulan</label>
                    <input class="form-control" type="month" name="bulan" required/>
                </div>

                <div class="form-group">
                    <label for="import_transaksi">Import CSV</label>
                    <input class="form-control" type="file" name="file" required/>
                </div>

                <div class="form-group">
                    <a href="<?= base_url().'management_raw/template_banjarmasin' ?>" class="btn btn-outline-warning">download template</a>
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
                            <td width="50%"><?= $a->nama_comp; ?></td>    
                            <td><?= $a->bulan.' -'.$a->tahun; ?></td>    
                            <td><?= $a->count_raw; ?></td>                            
                            <td><?= $a->count_mapping; ?></td>      
                            <td><?= number_format($a->omzet_raw); ?></td>       
                            <td><?= number_format($a->omzet_web); ?></td>    
                            <td><?= $a->created_at; ?></td>                            
                            <td><?= $a->username; ?></td>                                   
                            <td><a href="<?= base_url().'assets/uploads/management_raw/import/'.$a->filename ?>"><?= $a->filename; ?></a></td>                  
                            <td><a href="<?= base_url().'management_raw/download_raw/SSMS9/'.$a->signature ?>" class="btn btn-warning">download</a></td>                
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
    $("#example").DataTable({
        "pageLength": 7,
        "ordering": true,
        "order": [9, 'desc'],
        "aLengthMenu": [
            [10, 20, 50, -1],
            [10, 20, 50, "All"]
        ],
        "fixedHeader": {
            header: true,
            footer: true
        },
        scrollX: true
    });
    });
</script>

<!-- <script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/nama_comp_claim') ?>',
        data: '',
        success: function(hasil_branch) {
            $("select[name = site_code]").html(hasil_branch);
        }
    });
</script> -->

<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>