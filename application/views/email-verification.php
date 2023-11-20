<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/ams_favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url();?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Email Verification</title>
</head>
<body>
  <form class="verify pl-2 pr-2 pb-2" action="verification" method="post">
  <a class="a-left mt-1" href="<?php echo site_url('main/index');?>"><i class="fa fa-arrow-left"></i> Cancel</a><br><br>
      <img class="verify-logo mt-2 mb-2" src="<?php echo base_url();?>assets/images/ams_logo.png" alt="AMS logo">
    <h1>Email Verification</h1><br>
    <p>Check your E-mail to know your verification code, if you can't find it click Re-send.</p><br>
    <div class="mb-2">
    <input type="hidden" name="email" value="<?=$this->session->flashdata('email');?>">
    <input type="text" name="txtvcode" placeholder="Enter verification code">
    </div>
    <div class="mb-2">
    <input class="f-btn" type="submit" name="btnverify" value="Verify email">
    </div>
    <div class="mb-1">
    <input class="f-btn" type="submit" name="btnresend" value="Re-send">
    </div>
    <?php
    if($this->session->tempdata('email_error')) {
      ?>
      <div class="danger mt-2">
        <?php echo $this->session->tempdata('email_error');?>
      </div>
      <?php
    }
    elseif($this->session->tempdata('email_success')) {
      ?>
      <div class="success mt-2">
        <?php echo $this->session->tempdata('email_success');?>
      </div>
      <?php
    }
    ?>
  </form>
</body>
</html>