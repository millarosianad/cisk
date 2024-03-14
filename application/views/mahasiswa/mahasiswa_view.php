<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mahasiswa</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <style>
        td,
        th{
            border: 1px solid #ccc;
            padding: 3px 10px;
        }
        th{
            background-color: #dedede;
        }
        table{
            margin      : auto;
            min-width   : 800px;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>no</th>
                <th>filename</th>
                <th>lastupload</th>
            </tr>
        </thead>
        <tbody>
            <?php $nomor = 1;
            foreach ($get_upload as $values) {
                // $nomor = 0;
                // $nomor = $nomor++;
            ?>
                <tr>
                    <td><?php echo $nomor++; ?></td>
                    <td><?php echo $values->branch_name; ?></td>
                    <td><?php echo $values->nama_comp; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <script type="text/javascript">
        $(document).ready(function(){
            $(window).scroll(function(){
                var posisi = $(window).scrollTop();
                var bawah = $(document).height() - $(window).height();
                console.log('posisi = ' + posisi + 'lokasi bawah = ' + bawah);
            });
        });
    </script>

</body>
</html>