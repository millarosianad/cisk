<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" style="background-color: #fff;" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne"><font color="black">Monitoring Perubahan Data <i>click here</i></font>
                            </button>
                        </h5>
                    </div>

                    <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample" style="width:100%; overflow:hidden;">
                        <div class="card-body">
                            <table id="monitoring_count" class="display" width="100%">
                            <thead>
                                <tr>
                                    <th style="background-color: darkslategray;" class="text-center" width="10%"><font color="white">Tahun</th>
                                    <th style="background-color: darkslategray;" class="text-center" width="10%"><font color="white">Bulan</th>
                                    <th style="background-color: darkslategray;" class="text-center" ><font color="white">Count FI</th>
                                    <th style="background-color: darkslategray;" class="text-center" ><font color="white">Count RI</th>
                                    <th style="background-color: darkslategray;" class="text-center" ><font color="white">Created At</th>
                                </tr>
                            </thead>
                            <tbody>     
                            <?php 
                                foreach ($monitoring_count->result() as $a) : ?>
                                <tr>
                                    <td width="10%"><?= $a->tahun ?></td>
                                    <td width="10%"><?= $a->bulan ?></td>
                                    <td width="20%">
                                        <?php 
                                            if ($a->flag_fi == 1) { ?>
                                                <a href="#" class="btn btn-info btn-sm"><?= $a->count_fi ?></a>
                                            <?php
                                            }else{ ?>
                                                <a href="#" class="btn btn-info btn-danger"><?= $a->count_fi ?></a>
                                            <?php
                                            }
                                        ?>
                                    </td>
                                    <td width="20%">
                                        <?php 
                                            if ($a->flag_ri == 1) { ?>
                                                <a href="#" class="btn btn-info btn-sm"><?= $a->count_ri ?></a>
                                            <?php
                                            }else{ ?>
                                                <a href="#" class="btn btn-info btn-danger"><?= $a->count_ri ?></a>
                                            <?php
                                            }
                                        ?>
                                    </td>
                                    <td width="20%"><?= $a->created_at ?></td>
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
