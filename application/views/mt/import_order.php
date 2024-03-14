<style>
    th{
        font-size: 12px;
    }
    td{
        font-size: 12px;
    }
</style>

<?php
$required = "required";
?>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <div class="page-body">
                    <div class="row">

                        <div class="col-md-12 col-xl-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title; ?></h5>
                                </div>
                                <div class="card-block">

                                    <div class="mb-4">
                                        

<button type="button" class="btn btn-dark" data-toggle="modal" data-target="#setting_product">
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
  <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
  <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
</svg> Mapping Product</button>

<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#setting_outlet">
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
  <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
  <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
</svg> Mapping Outlet</button>

<button class="btn btn-primary btn-primary" onclick="addProfile()">
                                        <i class="fa fa-plus"></i>Import Order</button>

                                    </div>                                    

<table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
    <thead>
        <tr>
            <!-- <th>Branch</th> -->
            <th width="1%">SubBranch</th>
            <th width="1%">Partner</th>
            <th width="1">Filename Csv</th>
            <th width="1">Filename Pdf</th>
            <th width="1">mapping (produk - outlet)</th>
            <th width="1">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($history as $a) : ?>
        <tr>
            <td>
                <?php 
                    if ($a->nama_comp == NULL) { ?>
                         <i><font color="red">Failed</font></i>
                    <?php } else{
                        echo $a->nama_comp; 
                    }
                ?>
            </td>
            <td><?= $a->partner; ?></td>
            <td>
                <?php
                echo anchor(base_url().'assets/uploads/import_mt/'.$a->filename_csv,
                $a->filename_csv_cut); 
                ?>
            </td>
            <td>
                <?php
                echo anchor(base_url().'assets/uploads/import_mt/'.$a->filename_pdf,
                $a->filename_pdf_cut); 
                ?>
            </td>
            <td>
                <?php
                        if ($a->status_mapping_kodeprod == 'failed') { ?>
                        <i><font color="red">Failed</font></i>
                    <?php }else{ ?>
                        <i><font color="blue"><?= $a->status_mapping_kodeprod; ?></font></i>
                    <?php }
                ?> - 
                <?php
                    if ($a->status_mapping_outlet == 'failed') { ?>
                    <i><font color="red">Failed</font></i>
                <?php }else{ ?>
                    <i><font color="blue"><?= $a->status_mapping_outlet; ?></font></i>
                <?php }
            ?>                                                    
            </td>
            <td>        
                <?php
                    echo anchor('mt/detail_history/'.$a->signature,
                    'detail', array(
                        'class'     => 'btn btn-success btn-sm',
                        'target'    => 'blank'
                    )
                    );
                    echo " | "; 
                    echo anchor('mt/reload_order/'.$a->signature,
                    'reload', array(
                        'class' => 'btn btn-primary btn-sm',
                    )
                    );
                    echo " | ";
                    echo anchor('mt/delete_order/'.$a->signature,
                        'delete', array(
                            'class' => 'btn btn-danger btn-sm',
                            'onclick' => 'return confirm(\'Are you sure?\')'
                        )
                    ); 
                    if ($a->status_mapping_kodeprod == 'failed' || $a->status_mapping_outlet == 'failed') {
                        
                    }else{
                        
                        echo ' | ';
                        echo anchor('mt/cek_json/'.$a->signature,
                        'cek api', array(
                            'class' => 'btn btn-dark btn-sm',
                            'target'    => 'blank'
                        )
                        );

                        echo ' | ';
                        echo anchor('mt/log_history/'.$a->signature,
                        'log', array(
                            'class' => 'btn btn-dark btn-sm',
                            'target'    => 'blank'
                        )
                        );

                    }                                                        
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

                                </div>

                                <br>
                            </div>
                        </div>



<?php 
    $this->load->view('mt/modal_import_order');
    $this->load->view('mt/modal_mapping_product');
    $this->load->view('mt/modal_mapping_outlet');
?>