
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

    .accordion {
        cursor: pointer;
        padding: 1px;
        width: 100%;
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
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" style="background-color: #fff;" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><font color="black">Kalender Data <i>click here</i></font>
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample" style="width:100%; overflow:hidden;">
                        <div class="card-body">
                            <table id="kalender_data" class="display">
                                <thead>
                                    <tr>
                                        <th style="background-color: darkslategray;" class="text-center" width="70%"><font color="white">Branch</th>
                                        <th style="background-color: darkslategray;" class="text-center" ><font color="white">Sub Branch</th>
                                        <th style="background-color: darkslategray;" class="text-center" ><font color="white">Tanggal Data <?= $get_bulan_sekarang; ?></th>
                                        <th style="background-color: darkslategray;" class="text-center" ><font color="white">Status Closing</th>
                                        <th style="background-color: darkslategray;" class="text-center" ><font color="white">Created At</th>
                                        <th style="background-color: darkslategray;" class="text-center" ><font color="white">History</th>
                                        <!-- <th style="background-color: darkslategray;" class="text-center" ><font color="white">History Upload</th> -->
                                    </tr>
                                </thead>
                                <tbody>     
                                <?php 
                                    foreach ($get_kalender_data->result() as $a) : ?>
                                    <tr>
                                        <td width="10%"><?= $a->branch_name ?></td>
                                        <td width="10%"><?= $a->nama_comp ?></td>
                                        <td width="5%"><?= $a->tanggal_data ?></td>
                                        <td width="10%">
                                            <?php 
                                                if ($a->status_closing == 1) {
                                                    echo "CLOSING";
                                                }else{
                                                    echo "BELUM";
                                                }
                                            ?>
                                        </td>
                                        <td width="5%"><?= $a->created_at ?></td>
                                        <td width="5%">
                                        <a href="<?= base_url().'dashboard_dummy/detail_kalender_data/'.$a->kode ?>" class="btn btn-info btn-sm" target="_blank">history upload</a>
                                        </td>
                                        <!-- <td width="20%"><a href="" class="btn btn-info btn-sm">click here</a></td> -->
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

    



<!-- <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script> -->
