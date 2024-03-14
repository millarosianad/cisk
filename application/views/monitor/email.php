<!doctype html>
<html lang="en">
  <head>
  
  <style type="text/css">

    .button_accept {
        position: absolute;
        top:90%;
        padding-top: 5px;
        padding-bottom: 5px;
        padding-left: 8px;
        padding-right: 8px;
        background-color:green;
        color: #fff;
        border:none;
    }
    .button_reject {
        position: absolute;
        top:90%;
        padding-top: 5px;
        padding-bottom: 5px;
        padding-left: 8px;
        padding-right: 8px;
        background-color:orangered;
        color: #fff;
        border:none;
    }

    table
    {
        border-collapse: collapse;
    }

    th, td 
    {
        padding: 5px;
        text-align: left;
    }


  </style>

  </head>
  <body>

    <table border="0">
        <tr>
            <td width="20%">Subject</td>
            <td width="50%">: <i>Automatic Email From <a href="http://site.muliaputramandiri.com/cisk/monitor/dashboard">MPM SITE</i></a></td>
        </tr>
        <tr>
            <td width="20%">Principal</td>
            <td width="50%">: PT. Deltomed</td>
        </tr>
    </table>
    <br>
    <table border = 1>
        <tr>
            <!-- <th width="10%">Kodeprod</th>
            <th width="80%">Namaprod</th>
            <th width="10%">Qty</th> -->
            <th width="5%">Tahun</th>
            <th width="5%">Bulan</th>
            <th width="10%">D1</th>
            <th width="10%">D2-Exclude-RTD</th>
            <th width="10%">RTD</th>
            <th width="20%">TOTAL</th>
            <th width="5%">Created At</th>
        </tr>
        <?php foreach ($data->result() as $a) : ?>
        <tr>
            <!-- <td><?= $a->kodeprod ?></td>
            <td><?= $a->namaprod ?></td>
            <td><?= $a->qty ?></td> -->
            <th><?= $a->tahun ?></th>
            <td><?= $a->bulan ?></td>
            <td><?= number_format($a->D1) ?></td>
            <td><?= number_format($a->D2) ?></td>
            <td><?= number_format($a->RTD) ?></td>
            <td><?= number_format($a->TOTAL) ?></td>
            <td><?= $a->created_at ?></td>
        </tr>
        <?php endforeach; ?>

    </table>  

    

  </body>
</html>