<!-- stock -->
<div class="row stock">
    <!-- table sales year over year -->
    <div class="col">
        <div class="card latest-update-card">
            <div class="card-header">
                <h5>Stock</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        <li class="first-opt"><i class="feather icon-chevron-left open-card-option"></i>
                        </li>
                        <li><i class="feather icon-maximize full-card"></i></li>
                        <li><i class="feather icon-minus minimize-card"></i>
                        </li>
                        <li><i class="feather icon-refresh-cw reload-card"></i>
                        </li>
                        <li><i class="feather icon-trash close-card"></i></li>
                        <li><i class="feather icon-chevron-left open-card-option"></i>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th align="center">
                                        Principal
                                    </th>
                                    <th align="center">
                                        DP
                                    </th>
                                    <th align="center">
                                        DOI
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($stock as $a) : ?>
                                <tr>
                                    <td>
                                        <?= $a->namasupp; ?>
                                    </td>
                                    <td>
                                        <?= $a->nama_comp; ?>
                                    </td>
                                    <td>
                                        <?= $a->doi; ?>
                                    </td>
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