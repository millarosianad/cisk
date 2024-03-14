
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
                        

                        <!-- History -->

<div class="col-md-12 col-xl-12">
    <div class="card comp-card">
        <div class="card-header">
            <h5 class="mb-1">Contact List</h5>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="card-block">
                        <div class="dt-responsive table-responsive">
                            <div>
                                <?php 
                                echo anchor(base_url() . "broadcast/", 'back', array(
                                    'class' => 'btn btn-dark'
                                ));



                                echo anchor(base_url() . "broadcast/send_broadcast/".$signature, 'send broadcast', array(
                                    'class' => 'btn btn-primary'
                                ));

                                ?>
                            </div>
                            <hr>
                            
                            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th width="1%">No</th>
                                        <th>Nama</th>
                                        <th>Kontak</th>
                                        <th>Message Original</th>
                                        <th>Message Result</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($get_preview as $a) : 
                                    $no = 1;
                                ?>        
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $a->nama; ?></td>
                                        <td><?= $a->no_wa; ?></td>
                                        <td><?= $a->message_original; ?></td>
                                        <td><?= $a->message_result; ?></td>
                                        <td><?= $a->created_at; ?></td>
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

                        