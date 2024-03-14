<button class="btn btn-primary btn-succes btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus"></i>Tambah</button>
<br>
<br>
<div class="card table-card">
    <div class="card-header">
    <!-- <p id="loadingImage" style="font-size: 60px; display: none">Loading ...</p> -->

    <div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                <thead>
                    <tr>                
                        <th width="1"><font size="2px">Code</th>
                        <th><font size="2px">Branch</th>
                        <th><font size="2px">Sub Branch</th>
                        <th><font size="2px">Kode_comp</th>
                        <th><font size="2px">Nocab</th>
                        <th><font size="2px">Sub</th>
                        <th><font size="2px">Active</th>
                        <th><font size="2px">Stok</th>
                        <th><font size="2px">Api</th>
                        <th><font size="2px">Jawa</th>
                        <th><font size="2px">Cluster</th>
                        <th><font size="2px">Active Repl</th>
                        <th><font size="2px">Status Group Repl</th>
                        <th><font size="2px">Group Repl</th>
                        <th><font size="2px">Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tabcomp as $a): 
                         switch ($a->active)
                         {
                             case 1:$active=anchor('master_tabcomp/activer_tabcomp/0/'.$a->id.'/'.$a->active, ' ',
                                             array('class' => 'fa fa-check fa-2x','style' => 'color:green',
                                             'onclick'=>'return confirm(\'Are you sure?\')'));break;
                             case 0:$active=anchor('master_tabcomp/activer_tabcomp/1/'.$a->id.'/' . $a->active, ' ',
                                             array('class' => 'fa fa-times fa-2x','style' => 'color:red',
                                             'onclick'=>'return confirm(\'Are you sure?\')'));break;
                         }
                         switch ($a->stok)
                         {
                             case 1:$stok=anchor('master_tabcomp/activer_stok/0/'.$a->id.'/'.$a->stok, ' ',
                                             array('class' => 'fa fa-check fa-2x','style' => 'color:green',
                                             'onclick'=>'return confirm(\'Are you sure?\')'));break;
                             case 0:$stok=anchor('master_tabcomp/activer_stok/1/'.$a->id.'/'.$a->stok, ' ',
                                             array('class' => 'fa fa-times fa-2x','style' => 'color:red',
                                             'onclick'=>'return confirm(\'Are you sure?\')'));break;
                         }
                         switch ($a->status_api)
                         {
                             case 1:$api=anchor('master_tabcomp/activer_api/0/'.$a->id.'/'.$a->status_api, ' ',
                                             array('class' => 'fa fa-check fa-2x','style' => 'color:green',
                                             'onclick'=>'return confirm(\'Are you sure?\')'));break;
                             case 0:$api=anchor('master_tabcomp/activer_api/1/'.$a->id.'/'.$a->status_api, ' ',
                                             array('class' => 'fa fa-times fa-2x','style' => 'color:red',
                                             'onclick'=>'return confirm(\'Are you sure?\')'));break;
                         }

                         switch ($a->jawa)
                         {
                             case 1:$jawa=anchor('master_tabcomp/activer_jawa/0/'.$a->id.'/'.$a->jawa, ' ',
                                             array('class' => 'fa fa-check fa-2x','style' => 'color:green',
                                             'onclick'=>'return confirm(\'Are you sure?\')'));break;
                             case 0:$jawa=anchor('master_tabcomp/activer_jawa/1/'.$a->id.'/'.$a->jawa, ' ',
                                             array('class' => 'fa fa-times fa-2x','style' => 'color:red',
                                             'onclick'=>'return confirm(\'Are you sure?\')'));break;
                         }
                         switch ($a->status_cluster)
                         {
                             case 1:$cluster=anchor('master_tabcomp/activer_cluster/0/'.$a->id.'/'.$a->status_cluster, ' ',
                                             array('class' => 'fa fa-check fa-2x','style' => 'color:green',
                                             'onclick'=>'return confirm(\'Are you sure?\')'));break;
                             case 0:$cluster=anchor('master_tabcomp/activer_cluster/1/'.$a->id.'/'.$a->status_cluster, ' ',
                                             array('class' => 'fa fa-times fa-2x','style' => 'color:red',
                                             'onclick'=>'return confirm(\'Are you sure?\')'));break;
                         }
                         switch ($a->active_repl)
                         {
                             case 1:$active_repl=anchor('master_tabcomp/activer_repl/0/'.$a->id.'/'.$a->active_repl, ' ',
                                             array('class' => 'fa fa-check fa-2x','style' => 'color:green',
                                             'onclick'=>'return confirm(\'Are you sure?\')'));break;
                             case 0:$active_repl=anchor('master_tabcomp/activer_repl/1/'.$a->id.'/'.$a->active_repl, ' ',
                                             array('class' => 'fa fa-times fa-2x','style' => 'color:red',
                                             'onclick'=>'return confirm(\'Are you sure?\')'));break;
                         }switch ($a->status_group_repl)
                         {
                             case 1:$status_group_repl=anchor('master_tabcomp/activer_grouprepl/0/'.$a->id.'/'.$a->status_group_repl, ' ',
                                             array('class' => 'fa fa-check fa-2x','style' => 'color:green',
                                             'onclick'=>'return confirm(\'Are you sure?\')'));break;
                             case 0:$status_group_repl=anchor('master_tabcomp/activer_grouprepl/1/'.$a->id.'/'.$a->status_group_repl, ' ',
                                             array('class' => 'fa fa-times fa-2x','style' => 'color:red',
                                             'onclick'=>'return confirm(\'Are you sure?\')'));break;
                         }
                     ?>
                    <tr>  
                        <td><font size="2px"><?= $a->kode;?></td>
                        <td><font size="2px"><?= $a->branch_name;?></td>
                        <td><font size="2px"><?= $a->nama_comp;?></td>
                        <td><font size="2px"><?= $a->kode_comp;?></td>
                        <td><font size="2px"><?= $a->nocab;?></td>
                        <td><font size="2px"><?= $a->sub;?></td>
                        <td><font size="2px"><?= $active;?></td>
                        <td><font size="2px"><?= $stok;?></td>
                        <td><font size="2px"><?= $api;?></td>
                        <td><font size="2px"><?= $jawa;?></td>
                        <td><font size="2px"><?= $cluster?></td>
                        <td><font size="2px"><?= $active_repl;?></td>
                        <td><font size="2px"><?= $status_group_repl;?></td>
                        <td><font size="2px"><?= $a->group_repl;?></td>
                        <td><font size="2px">
                            <button class="fa fa-edit fa-xl btn-info" id="testOnclick" onclick="getEdittabcomp('<?= $a->id ?>')"></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>                
                </tbody>
                <tfoot>
                    <tr>                
                        <th width="1"><font size="2px">Code</th>
                        <th><font size="2px">Branch</th>
                        <th><font size="2px">Sub Branch</th>
                        <th><font size="2px">Kode_comp</th>
                        <th><font size="2px">Nocab</th>
                        <th><font size="2px">Sub</th>
                        <th><font size="2px">Active</th>
                        <th><font size="2px">Stok</th>
                        <th><font size="2px">Api</th>
                        <th><font size="2px">Jawa</th>
                        <th><font size="2px">Cluster</th>
                        <th><font size="2px">Active Repl</th>
                        <th><font size="2px">Status Group Repl</th>
                        <th><font size="2px">Group Repl</th>
                        <th><font size="2px">Edit</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        </div>
        </div>
</div>

<!-- =========================================== Tambah tabcomp =============================================== -->
<?php $this->load->view('master_tabcomp/modal_addtabcomp'); ?>

<!-- =========================================== Edit tabcomp =============================================== -->
<?php $this->load->view('master_tabcomp/modal_edittabcomp'); ?>
