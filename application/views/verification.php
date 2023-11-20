<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/ams_favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
  <title>Log in</title>
</head>

<body>
  <?php
  if ($this->session->flashdata('email')) {
  ?>
      <form class="verification p-2" method="post" action="verifylogin">
      <img class="login-logo mb-4 mt-2" src="<?php echo base_url();?>assets/images/ams_logo.png" alt="AMS logo">
      <div class="mb-2">
        <h1>Enter the verification code to know it's really you.</h1>
      </div>
      <div class ="mb-2">
        <input type ="hidden" name ="txtemail" value="<?=$this->session->flashdata('email')?>" required>
      </div>
      <div class ="mb-2">
        <input type ="hidden" name ="txtselector" value="<?=$this->session->flashdata('selector')?>" required>
      </div>
      <div class="mb-2">
        <input type ="text" name ="txtvcode" placeholder="Enter verification code">
      </div>
      <div class="mb-5">
        <input class ="mr-1" type ="submit" name ="btnlogin" value ="Login">
        <input class ="mr-1" type ="submit" name ="btnresend" value ="Re-send">
      </div>
      <div class="mb-1">
      </div>
      <?php
      if($this->session->tempdata('email_success')) {
        ?>
              <div class="success mt-2">
                <?php echo $this->session->tempdata('email_success');?>
              </div>
        <?php
      }
      elseif($this->session->tempdata('verify_error')) {
        ?>
              <div class="danger mt-2">
                <?php echo $this->session->tempdata('verify_error');?>
              </div>
        <?php
      }
      ?>
    </form>
  <?php
  } else {
    redirect('main/index');
  }
  ?>
</body>

</html>