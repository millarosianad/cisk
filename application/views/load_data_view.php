<!--
Used basic html and bootstrap skeleton from the below link for style purpose which contains bootstrap.css,bootstrap.js,jquery.js
https://www.w3schools.com/bootstrap/bootstrap_get_started.asp
Used bootstrap form elements from below link.
https://www.w3schools.com/bootstrap/bootstrap_forms.asp
-->
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Live Demo: Load more data on scroll in codeigniter</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <style>
            .message_box {
                height: 75px;
                width: 600px;
                border: dashed 1px #48B1D9;
                padding: 5px;
            }
            .load-data-msg{
                font-size: 20px;
                color: #48B1D9
            }
            .msg-number{
                background-color: #48B1D9;
                color: #000;
                padding-left: 3px;
                padding-bottom: 3px;
                padding-right: 3px;
                font-weight: bold;
                font-size: 20px;
                width: 30px;
            }
            #msg_loader{
                position: fixed;
                bottom: 20%;
                left: 38%;
                display: none;
            }
        </style>
    </head>
    <body>
        <div class="container" style="max-width:800px;margin:0 auto;margin-top:50px;">  
            <div>
                <h2 style="margin-bottom:50px;">Live Demo: Load more data on scroll in codeigniter</h2>              
            </div>
            <div style="margin-bottom:20px;">
                <?php
                if (isset($messages) && !empty($messages)) {
                    foreach ($messages as $msg) {
                        ?>
                        <div class="load-data-wrap message_box" data-id="<?php echo $msg->id; ?>">
                            <div class="load-data-msg"><?php echo $msg->message; ?></div>
                            <div class="msg-number"><?php echo $msg->id; ?></div>
                        </div>
                        <?php
                    }
                }
                ?>
                <div id="msg_loader"><img src="<?php echo base_url("assets/images/bigLoader.gif"); ?>"></div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script>
            var base_url = "<?php echo site_url(); ?>";
            console.log(base_url)
            $(document).ready(function () {
                $(window).scroll(function () {
                    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                        var msg_id = $(".message_box:last").data("id");
                        $("#msg_loader").show();
                        $.ajax({
                            type: "POST",
                            url: base_url + "BaseController/load_messages",
                            data: {msg_id: msg_id},
                            cache: false,
                            dataType: 'json',
                            success: function (data) {
                                if (data.result == "Success") {
                                    //Insert data after the message_box 
                                    $(".message_box:last").after(data.page);
                                    $("#msg_loader").hide();
                                } else {
                                    console.log(data.msg);
                                }
                            }
                        });
                    }
                });
            });
        </script>
    </body>
</html>