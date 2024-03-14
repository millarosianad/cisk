<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <div class="d-inline">
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                </div>
            </div>
        </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                

                <div class="page-body">
                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5>Detail Kalender Data : <?= $company ?>
                                </div>
                                <div class="card-block">
                                    <table width="100%" id="multi-colum-dt" class="table table-striped table-bordered nowrap" style="display: inline-block; overflow-y: scroll">
                                        <thead>
                                            <tr>
                                                <th width="10%"><font size="1px">No</th>
                                                <th width="10%"><font size="1px">Filename</th>
                                                <th width="10%"><font size="1px">LastUpload</th>
                                                <th width="10%"><font size="1px">Tahun</th>
                                                <th width="10%"><font size="1px">Bulan</th>
                                                <th width="10%"><font size="1px">Tanggal</th>
                                                <th width="10%"><font size="1px">Status</th>
                                                <th width="10%"><font size="1px">Status Closing</th>
                                                <!-- <th width="10%"><font size="1px">Omzet</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>                                        
                                            <?php 
                                            $no = 1;
                                            foreach ($detail_kalender_data->result() as $a) : ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a->filename; ?></td>
                                                <td><?= $a->lastupload; ?></td>
                                                <td><?= $a->tahun; ?></td>
                                                <td><?= $a->bulan; ?></td>
                                                <td><?= $a->tanggal; ?></td>
                                                <td><?= $a->status; ?></td>
                                                <td><?= $a->status_closing; ?></td>
                                                <!-- <td><?= $a->omzet; ?></td> -->
                                            </tr>
                                            <?php endforeach; ?>    
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/subbranch') ?>',
        data: {},
        success: function(hasil) {
            $("select[name = from_site]").html(hasil);
            $("select[name = to_site]").html(hasil);
        }
    });
</script>

                        
                        