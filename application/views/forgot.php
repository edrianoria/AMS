<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/ams_favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Reset Password Request</title>
</head>

<body>
  <form class="forgot p-2" action="reset" method="post">
    <a class="a-left mt-1" href="<?php echo site_url('main/index'); ?>"><i class="fa fa-arrow-left"></i> Cancel</a><br><br>
    <img class="forgot-logo mt-2 mb-2" src="<?php echo base_url(); ?>assets/images/ams_logo.png" alt="AMS logo">
    <h1>Reset your password</h1><br>
    <p>An e-mail will be send to you with instructions on how to reset your password.</p><br>
    <div class="mb-2">
      <input type="text" name="txtemail" placeholder="Enter your e-mail address" required>
    </div>
    <div class="mb-1">
      <input class="f-btn" type="submit" name="btnsubmit" value="Send reset password">
    </div>
    <?php
    if ($this->session->tempdata('email_success')) {
    ?>
      <div class="success mt-2">
        <?php echo $this->session->tempdata('email_success'); ?>
      </div>
    <?php
    } elseif ($this->session->tempdata('email_error')) {
    ?>
      <div class="danger mt-2">
        <?php echo $this->session->tempdata('email_error'); ?>
      </div>
    <?php
    }
    ?>
  </form>
</body>

</html>