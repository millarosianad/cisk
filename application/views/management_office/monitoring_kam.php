
<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
    td{
        font-size: 12px;
        /* text-align: center; */
    }
    th{
        font-size: 13px; 
    }

    .accordion_kam {
        cursor: pointer;
        padding: 1px;
        width: 130%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.2s;
        /* border: 2px solid;
        border-radius: 25px; */
        border-top: 5px solid darkslategray;
        border-bottom: 5px solid darkslategray;
        border-left: 5px solid darkslategray;
        border-right: 5px solid darkslategray;
        border-radius: 14px;
        margin-top: 1rem;
        border-top: 1em solid darkslategray;

    }

</style>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="accordion_kam" id="accordionExample">
                <div class="card">
                    <div class="card-header" style="background-color: #fff;" id="headingKam">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseKam" aria-expanded="true" aria-controls="collapseOne"><font color="black">Monitoring Kam <i>click here</i></font>
                            </button>
                        </h5>
                    </div>

                    <div id="collapseKam" class="collapse show" aria-labelledby="headingKam" data-parent="#accordionExample" style="width:100%; overflow:hidden;">
                        <div class="card-body">

                            <div class="mb-5">
                                <label class="form-control d-inline">Last Updated at <?= $kam_updated; ?></label>
                                <a href="<?= base_url().'management_office/update_data_kam' ?>" class="btn btn-secondary btn-sm" style="background-color: darkslategray;">force update data</a>
                            </div>

                            <div class="mt-3">
                                <table id="kam" class="display" width="100%">
                                <thead>
                                    <tr>
                                        <th style="background-color: darkslategray;" class="text-center" width="10%"><font color="white">Tahun</th>
                                        <th style="background-color: darkslategray;" class="text-center" width="10%"><font color="white">Bulan</th>
                                        <th style="background-color: darkslategray;" class="text-center" width="10%"><font color="white">Segment</th>
                                        <th style="background-color: darkslategray;" class="text-center" width="20%"><font color="white">Divisi</th>
                                        <th style="background-color: darkslategray;" class="text-center" width="20%"><font color="white">Omzet</th>
                                        <th style="background-color: darkslategray;" class="text-center" width="10%"><font color="white">Unit</th>
                                    </tr>
                                </thead>
                                <tbody>     
                                <?php 
                                    foreach ($monitoring_kam->result() as $a) : ?>
                                    <tr>
                                        <td><?= $a->tahun ?></td>
                                        <td><?= $a->bulan ?></td>
                                        <td><?= $a->segment ?></td>
                                        <td><?= $a->divisi ?></td>
                                        <td>Rp. <?= number_format($a->omzet,0) ?></td>
                                        <td><?= number_format($a->unit,0) ?></td>
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
