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
    }
    th{
        font-size: 13px; 
    }

    .accordion_sellout {
        cursor: pointer;
        padding: 1px;
        width: 130%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.2s;
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
            <div class="accordion_sellout" id="accordionExample">
                <div class="card">
                    <div class="card-header" style="background-color: #fff;" id="headingKam">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseSellout" aria-expanded="true" aria-controls="collapseOne"><font color="black">Monitoring Sell Out Us <i>click here</i></font>
                            </button>
                        </h5>
                    </div>

                    <div id="collapseSellout" class="collapse show" aria-labelledby="headingKam" data-parent="#accordionExample" style="width:100%; overflow:hidden;">
                        <div class="card-body">

                            <div class="mb-5">
                                <label class="form-control d-inline">Last Updated at <?= $sell_out_updated; ?></label>
                            </div>

                            <div class="mt-3">
                                <a href="<?= base_url().'management_office/export_sell_out_us' ?>" class="btn btn-secondary btn-sm" style="background-color: darkslategray;">export sell out</a>
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>