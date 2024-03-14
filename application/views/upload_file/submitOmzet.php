<!DOCTYPE html>
<html lang="en">
<!-- css -->
<style>
  {
    margin: 0;
    padding: 0;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    font-family: 'Roboto', sans-serif;
  }

  .backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.644);
    display: flex;
    justify-content: center;
    align-items: center;

  }

  .popup {
    width: 19rem;
    background-color: rgb(255, 255, 255);
    padding: 1rem;
    border-radius: 4px;
    font-family: 'Poppins', sans-serif;
    text-align: center;
    box-shadow: 0 0 1rem rgba(0, 0, 0, 0.1);
  }

  .the-blood-coders-name,
  .the-blood-coders-name:link,
  .the-blood-coders-name:visited {
    font-size: 1.2em;
    font-weight: 600;
    margin-top: -2.6rem;
    margin-bottom: 2rem;
    background-image: linear-gradient(to right top, #e90606, #ff4a4a);
    color: rgb(241, 241, 241);
    padding: .5rem 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 1px;
    transform: skew(-10deg) rotate(-3deg);
    box-shadow: 0 0 1rem rgba(0, 0, 0, 0.2);
    -webkit-tap-highlight-color: transparent;
    text-decoration: none;

  }

  .title {
    font-size: 1.2em;
    margin-bottom: 1rem;
  }

  .title--span {
    background-image: linear-gradient(145deg, #232526, #414345);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .link {
    word-break: break-all;
    line-height: 1.4;
    display: block;
    color: rgb(29, 29, 250);
    margin-bottom: 1.2rem;
  }

  .btn-ok,
  .btn-ok:link,
  .btn-ok:active,
  .btn-ok:visited {
    text-transform: uppercase;
    margin-top: 1rem;
    display: inline-block;
    padding: .5rem 2rem;
    color: #ffffff;
    background-image: linear-gradient(145deg, #ffe530, #ff2525);
    font-family: 'Poppins', sans-serif;
    text-decoration: none;
    font-weight: 400;
    -webkit-tap-highlight-color: transparent;
    transform: skew(-10deg) rotate(-3deg);
    box-shadow: 0 0 1rem rgba(0, 0, 0, 0.1);
  }
</style>

<head>
  <title>Alert</title>
</head>

<body>
  <!--- alert --->
  <div class="backdrop">
    <div class="popup">
      <h1 class="title">
        <span class="title--span">SUCCESS</span>
      </h1>
      <i class="fa fa-check-circle-o fa-5x" style="color: green;"></i>
      <br>
      <br>
      <p>Data anda sudah berhasil masuk ke database kami. Terima kasih</p>
      <a href="<?php echo base_url().'upload_file/simpanOmzet/'.$id_upload;?>" class="btn btn-primary btn-round" >Ok</a>
    </div>
  </div>
</body>

</html>