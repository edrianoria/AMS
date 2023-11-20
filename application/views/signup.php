<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/ams_favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url();?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Sign up</title>
</head>
<body>
  <form class="register p-2" method="post" action="get_info">
    <a class="a-left mt-1 ml-1" href="<?=site_url('main/index');?>"><i class="fa fa-arrow-left"></i> Cancel</a><br><br>
    <img class="register-logo mt-2 mb-3" src="<?php echo base_url();?>assets/images/ams_logo.png" alt="AMS logo"> 
    <h2 class="mb-2">Create your Account</h2>
    <?php 
      if(isset($userdata)) {
        ?>
              <div class="mb-2">
              <input type="text" name="txtname" required value="<?=$userdata['name'];?>">
              </div>
              <div class="mb-2">
              <input type="text" name="txtuname" required value="<?=$userdata['username'];?>">
              </div>
              <div class="mb-2">
              <input type="text" name="txtemail" required value="<?=$userdata['email'];?>">
              </div>
              <div class="mb-2">
              <input type="password" name="txtpword" required placeholder="Enter your password">
              </div>
              <div class="mb-3">
              <input type="password" name="txtcpword" required placeholder="Re-enter your password">
              </div>
              <div class="mb-1">
              <input type="submit" name="btnregister" value="Sign Up">
              </div>
        <?php
      }
      else {
        ?>
              <div class="mb-2">
              <input type="text" name="txtname" required placeholder="Enter your name">
              </div>
              <div class="mb-2">
              <input type="text" name="txtuname" required placeholder="Enter your username">
              </div>
              <div class="mb-2">
              <input type="text" name="txtemail" required placeholder="Enter your email">
              </div>
              <div class="mb-2">
              <input type="password" name="txtpword" required placeholder="Enter your password">
              </div>
              <div class="mb-3">
              <input type="password" name="txtcpword" required placeholder="Re-enter your password">
              </div>
              <div class="mb-1">
              <input type="submit" name="btnregister" value="Sign Up">
              </div>
        <?php
      }   
    ?>
    <?php
    if($this->session->tempdata('signup_error')) {
      ?>
      <div class="danger mt-2">
        <?php echo $this->session->tempdata('signup_error');?>
      </div>
      <?php
    }
    ?>
  </form>
</body>
</html>