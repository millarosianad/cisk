<!-- sales -->
<div class="row">
    <!-- table sales year over year -->
    <div class="col">
        <div class="card latest-update-card">
            <div class="card-header">
                <h5>Sales</h5>
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

            <!-- tahun -->
            <div class="card-block" id="year">
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead align="center">
                                <tr>
                                    <th rowspan="2" align="center" scope="col">
                                        Principal
                                    </th>
                                    <th colspan="2" align="center" scope="col">
                                        Tahun
                                    </th>
                                </tr>
                                <tr>
                                    <th scope="col">
                                        2021
                                    </th>
                                    <th scope="col">
                                        2022
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($sales_year as $a) : ?>
                                <tr>
                                    <th scope="row"><?=$a->namasupp; ?></th>
                                    <td>Rp.<?=number_format($a->tot1); ?></td>
                                    <td>Rp.<?=number_format($a->tot2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- bulan -->
            <div class="card-block" id="month">
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead align="center" style="font-size: 10px;">
                                <tr>
                                    <th rowspan="2" align="center" scope="col">
                                        Principal
                                    </th>
                                    <th colspan="24" align="center" scope="col">
                                        Tahun
                                    </th>
                                </tr>
                                <tr>
                                    <th scope="col">
                                        JAN 2021
                                    </th>
                                    <th scope="col">
                                        JAN 2022
                                    </th>
                                    <th scope="col">
                                        FEB 2021
                                    </th>
                                    <th scope="col">
                                        FEB 2022
                                    </th>
                                    <th scope="col">
                                        MAR 2021
                                    </th>
                                    <th scope="col">
                                        MAR 2022
                                    </th>
                                    <th scope="col">
                                        APR 2021
                                    </th>
                                    <th scope="col">
                                        APR 2022
                                    </th>
                                    <th scope="col">
                                        MEI 2021
                                    </th>
                                    <th scope="col">
                                        MEI 2022
                                    </th>
                                    <th scope="col">
                                        JUN 2021
                                    </th>
                                    <th scope="col">
                                        JUN 2022
                                    </th>
                                    <th scope="col">
                                        JUL 2021
                                    </th>
                                    <th scope="col">
                                        JUL 2022
                                    </th>
                                    <th scope="col">
                                        AGS 2021
                                    </th>
                                    <th scope="col">
                                        AGS 2022
                                    </th>
                                    <th scope="col">
                                        SEP 2021
                                    </th>
                                    <th scope="col">
                                        SEP 2022
                                    </th>
                                    <th scope="col">
                                        OKT 2021
                                    </th>
                                    <th scope="col">
                                        OKT 2022
                                    </th>
                                    <th scope="col">
                                        NOV 2021
                                    </th>
                                    <th scope="col">
                                        NOV 2022
                                    </th>
                                    <th scope="col">
                                        DES 2021
                                    </th>
                                    <th scope="col">
                                        DES 2022
                                    </th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 10px;">
                                <?php foreach($sales_month as $a) : ?>
                                <tr>
                                    <th scope="row"><?=$a->namasupp; ?></th>
                                    <td>Rp.<?=number_format($a->tot1_1); ?></td>
                                    <td>Rp.<?=number_format($a->tot2_1); ?></td>
                                    <td>Rp.<?=number_format($a->tot1_2); ?></td>
                                    <td>Rp.<?=number_format($a->tot2_2); ?></td>
                                    <td>Rp.<?=number_format($a->tot1_3); ?></td>
                                    <td>Rp.<?=number_format($a->tot2_3); ?></td>
                                    <td>Rp.<?=number_format($a->tot1_4); ?></td>
                                    <td>Rp.<?=number_format($a->tot2_4); ?></td>
                                    <td>Rp.<?=number_format($a->tot1_5); ?></td>
                                    <td>Rp.<?=number_format($a->tot2_5); ?></td>
                                    <td>Rp.<?=number_format($a->tot1_6); ?></td>
                                    <td>Rp.<?=number_format($a->tot2_6); ?></td>
                                    <td>Rp.<?=number_format($a->tot1_7); ?></td>
                                    <td>Rp.<?=number_format($a->tot2_7); ?></td>
                                    <td>Rp.<?=number_format($a->tot1_8); ?></td>
                                    <td>Rp.<?=number_format($a->tot2_8); ?></td>
                                    <td>Rp.<?=number_format($a->tot1_9); ?></td>
                                    <td>Rp.<?=number_format($a->tot2_9); ?></td>
                                    <td>Rp.<?=number_format($a->tot1_10); ?></td>
                                    <td>Rp.<?=number_format($a->tot2_10); ?></td>
                                    <td>Rp.<?=number_format($a->tot1_11); ?></td>
                                    <td>Rp.<?=number_format($a->tot2_11); ?></td>
                                    <td>Rp.<?=number_format($a->tot1_12); ?></td>
                                    <td>Rp.<?=number_format($a->tot2_12); ?></td>
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